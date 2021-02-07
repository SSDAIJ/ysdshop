<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likemarket/likeshopv2
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------
namespace app\common\logic;

use app\api\model\User;
use app\common\model\AccountLog;
use app\common\server\ConfigServer;
use think\Db;

class IntegralLogic
{

    /**
     * Desc: 处理积分
     * @param $user_id
     * @param $use_integral
     * @param $channel
     * @param $source_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function handleIntegral($user_id, $use_integral, $channel, $source_id)
    {
        $user = User::get($user_id);
        switch ($channel) {
            //下单积分抵扣
            case AccountLog::order_deduction_integral:
                $user->user_integral = ['dec', $use_integral];
                $change_type = 2;
                break;

            //取消订单退回积分
            case AccountLog::cancel_order_refund_integral:
                $user->user_integral = ['inc', $use_integral];
                $change_type = 1;
                break;

            //下单奖励积分(每天一单)
            case AccountLog::order_add_integral:
                $user->user_integral = ['inc', $use_integral];
                $change_type = 1;
                break;

            default:
                return;
        }
        $user->save();
        //更新积分记录
        AccountLogLogic::AccountRecord($user_id, $use_integral, $change_type, $channel, '', $source_id);
    }




    /**
     * Desc: 下单奖励积分(每天第一单)
     * @param $user_id
     * @param $order_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function rewardIntegral($user_id, $order_id)
    {
        //是否为当天第一个订单(是->奖励积分)
        $check = Db::name('account_log')
            ->where(['user_id' => $user_id])
            ->where('source_type', AccountLog::order_add_integral)
            ->whereTime('create_time', 'today')
            ->find();

        //下单奖励开关;0-关闭;1-开启;
        $order_award_integral = ConfigServer::get('marketing', 'order_award_integral', 0);
        if ($order_award_integral == 0 || $check) {
            return;
        }

        self::handleIntegral($user_id, $order_award_integral, AccountLog::order_add_integral, $order_id);
    }

}