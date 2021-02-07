<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------


namespace app\common\logic;


use app\api\model\Order;
use app\common\model\AccountLog;
use app\common\model\Order as CommonOrder;
use app\common\model\OrderGoods;
use app\common\model\OrderLog;
use app\common\model\Pay;
use app\common\model\User;
use app\common\server\WeChatServer;
use think\Db;
use think\Exception;
use think\facade\Hook;

/**
 * 订单退款逻辑
 * Class OrderRefundLogic
 * @package app\common\logic
 */
class OrderRefundLogic
{

    /**
     * Desc: 取消订单
     * @param $order_id
     * @param int $handle_id 操作人id(不传默认为用户自己取消)
     * @return Order
     */
    public static function cancelOrder($order_id, $handle_type = OrderLog::TYPE_SYSTEM, $handle_id = 0)
    {
        //更新订单状态
        $order = Order::get($order_id);
        $order->order_status = CommonOrder::STATUS_CLOSE;
        $order->update_time = time();
        $order->cancel_time = time();
        $order->save();

        //取消订单后操作
        Hook::listen('cancel_order', ['order_id'  => $order_id, 'handle_id' => $handle_id, 'handle_type' => $handle_type]);
        return $order;
    }


    /**
     * Desc: 处理订单退款(事务在取消订单逻辑处)
     * @param $order
     * @return array|bool|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function refund($order)
    {
        //退款记录
        $refund_id = self::addRefundLog($order);

        switch ($order['pay_way']){
            //余额退款
            case Pay::BALANCE_PAY:
                self::balancePayRefund($order);
                break;
            //微信退款
            case Pay::WECHAT_PAY:
                self::wechatPayRefund($order, $refund_id);
                break;
        }
    }


    /**
     * Desc: 微信支付退款
     * @param $order
     * @param $refund_id
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function wechatPayRefund($order, $refund_id)
    {
        $config = WeChatServer::getPayConfigBySource($order['order_source']);

        if (empty($config)) {
            throw new Exception('请联系管理员设置微信相关配置!');
        }

        if (!isset($config['cert_path']) || !isset($config['key_path'])) {
            throw new Exception('请联系管理员设置微信证书!');
        }

        if (!file_exists($config['cert_path']) || !file_exists($config['key_path'])) {
            throw new Exception('微信证书不存在,请联系管理员!');
        }

        $refund_log = Db::name('order_refund')->where(['id' => $refund_id])->find();

        $data = [
            'transaction_id' => $order['transaction_id'],
            'refund_sn' => $refund_log['refund_sn'],
            'total_fee' => $order['order_amount'] * 100,//订单金额,单位为分
            'refund_fee' => $order['order_amount'] * 100,//退款金额
        ];
        $result = PaymentLogic::refund($config, $data);

        if (isset($result['return_code']) && $result['return_code'] == 'FAIL') {
            throw new Exception($result['return_msg']);
        }

        if (isset($result['err_code_des'])) {
            throw new Exception($result['err_code_des']);
        }

        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            //更新退款记录
            Db::name('order_refund')
                ->where(['id' => $refund_id])
                ->update([
                'wechat_refund_id' => $result['refund_id'] ?? 0,
                'refund_msg' => json_encode($result, JSON_UNESCAPED_UNICODE),
            ]);
        }
    }


    /**
     * Desc: 增加退款记录
     * @param $order
     * @param string $result_msg
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function addRefundLog($order, $result_msg = '退款成功')
    {
        $data = [
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'refund_sn' => createSn('order_refund', 'refund_sn'),
            'order_amount' => $order['order_amount'],
            'refund_amount' => $order['order_amount'],
            'transaction_id' => $order['transaction_id'],
            'create_time' => time(),
            'refund_status' => 1,
            'refund_at' => time(),
            'refund_msg' => json_encode($result_msg, JSON_UNESCAPED_UNICODE),
        ];
        return Db::name('order_refund')->insertGetId($data);
    }


    /**
     * Desc: 退款后更新订单
     * @param $order
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function refundAfterUpdateOrder($order)
    {
        //订单商品=>标记退款成功状态
        Db::name('order_goods')
            ->where(['order_id' => $order['id']])
            ->update(['refund_status' => OrderGoods::REFUND_STATUS_SUCCESS]);

        //更新订单支付状态为已退款
        Db::name('order')->where(['id' => $order['id']])->update([
            'pay_status' => Pay::REFUNDED,
            'refund_status' => 2,
            'refund_amount' => $order['order_amount'],
        ]);
    }



    /**
     * Desc: 余额退款
     * @param $order
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function balancePayRefund($order)
    {
        $user = User::get($order['user_id']);
        $user->user_money = ['inc', $order['order_amount']];
        $user->save();

        AccountLogLogic::AccountRecord(
            $order['user_id'],
            $order['order_amount'],
            1,
            AccountLog::cancel_order_refund,
            '',
            $order['id'],
            $order['order_sn']
        );

        return true;
    }



    /**
     * Desc: 退款失败增加错误记录
     * @param $order 订单信息
     * @param $err_msg 错误信息
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function addErrorRefund($order, $err_msg)
    {
        $refund_data = [
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'refund_sn' => createSn('order_refund', 'refund_sn'),
            'order_amount' => $order['order_amount'],//订单应付金额
            'refund_amount' => $order['order_amount'],//订单退款金额
            'transaction_id' => $order['transaction_id'],
            'create_time' => time(),
            'refund_status' => 2,
            'refund_msg' => json_encode($err_msg, JSON_UNESCAPED_UNICODE),
        ];
        return Db::name('order_refund')->insertGetId($refund_data);
    }

}