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

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\common\command;

use app\common\server\ConfigServer;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\facade\Config;

class Update extends Command
{
    protected function configure()
    {
        $this->setName('update')
            ->setDescription('更新代码、同步数据以后执行');
    }

    protected function execute(Input $input, Output $output)
    {

        $orders = Db::name('order_goods og')
            ->field('og.goods_id,og.item_id,og.goods_info,og.spec_value_ids,og.id as og_id')
            ->join('goods g', 'og.goods_id = g.id')
            ->join('goods_item i', 'i.id = og.item_id')
            ->where('og.goods_info', 'null')
            ->select();

        $goods_ids = array_column($orders, 'goods_id');
        $item_ids = array_column($orders, 'item_id');
        $spec_ids = array_column($orders, 'spec_value_ids');

        $goods = Db::name('goods')->where(['id' => $goods_ids])->column('image,name', 'id');
        $goods_item = Db::name('goods_item')->where(['id' => $item_ids])->column('image', 'id');
        $spec = Db::name('goods_spec_value')->where(['id' => $spec_ids])->column('value', 'id');

        $handle_arr = [];
        foreach ($orders as $order){
            if (!empty($order['goods_info'])){
                continue;
            }

            $goods_id = $order['goods_id'];
            $item_id =  $order['item_id'];
            $spec_id = $order['spec_value_ids'];

            $data = [];
            $data['goods_id'] = $order['goods_id'];
            $data['item_id'] = $order['item_id'];
            $data['goods_name'] =  $goods[$goods_id]['name'] ?? '';
            $data['image'] =  $goods[$goods_id]['image'] ?? '';
            $data['spec_image'] = $goods_item[$item_id] ?? '';
            $data['spec_value_str'] = $spec[$spec_id] ?? '';

            Db::name('order_goods')
                ->where(['id'=> $order['og_id']])
                ->update([
                    'goods_info' => json_encode($data, JSON_UNESCAPED_UNICODE),
                ]);

            $handle_arr[] = $order['og_id'];
        }

        return '修改'.count($handle_arr).'条数据';
    }

}