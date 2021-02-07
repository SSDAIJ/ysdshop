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
namespace app\admin\validate;
use think\Db;
use think\Validate;

class SeckillGoods extends Validate{
    protected $rule = [
        'seckill_id'        => 'require|checkSeckill',
        'item'              => 'require|checkActivity'

    ];
    protected $message = [
        'seckill_id.require'    => '请选择秒杀时段',
        'item.require'          => '请选择秒杀商品',
    ];

    public function checkSeckill($value,$rule,$data){

        $seckill = Db::name('seckill_time')->where(['del'=>0,'id'=>$value])->find();
        if(empty($seckill)){
            return '秒杀时间段已被调整，请重新选择时间段';
        }
        return true;
    }

    public function sceneAdd(){
        $this->append('item','checkAddGoods');

    }
    public function sceneEdit(){
        $this->append('item','checkEditGoods');

    }
    
    public function checkActivity($value,$rule,$data){
        $goods_ids = array_keys($value);
        $goods_item_list = Db::name('goods_item')->where(['goods_id'=>$goods_ids])->column('price,spec_value_str','id');
        $team_goods_item = Db::name('team_goods_item')->where(['goods_id'=>$goods_ids])->column('item_id');
        foreach ($value as $goods_item){
            $id = array_keys($goods_item);
            if(in_array($id,$team_goods_item)){
                return $goods_item_list[$id]['spec_value_str'].'商品正在参加拼团活动中，不能在参加秒杀活动';
            }
        }
        return true;
    }

    public function checkAddGoods($value,$rule,$data){

        $goods_ids = array_keys($value);
        $shop_goods = Db::name('goods')->where(['del'=>0])->column('id');
        $goods_item = Db::name('goods_item')->where(['id'=>$goods_ids])->column('price,spec_value_str','id');
        $seckill_goods = Db::name('seckill_goods')->where(['seckill_id'=>$data['seckill_id'],'del'=>0])->column('item_id');

        foreach ($value as $each => $item){

            if(!in_array($each,$shop_goods)){
                return '商品ID:'.$each.'已下架';
            }
            foreach ($item as $item_each => $item_item){
                if($seckill_goods && in_array($item_item,$seckill_goods)){

                    return '商品规格:'.$goods_item[$item_each]['spec_value_str'].'已在活动中，请勿重复添加';
                }
                if(isset($goods_item[$item_each])){
                    //验证商品价格
                    if($item_item > $goods_item[$item_each]['price']){
                        return '商品规格:'.$goods_item[$item_each]['spec_value_str'] .'的秒杀价格高于原价';
                    }
                }
            }
        }
        return true;
    }

    public function checkEditGoods($value,$rule,$data){
        $goods_ids = array_keys($value);
        $shop_goods = Db::name('goods')->where(['del'=>0])->column('id');
        $goods_item = Db::name('goods_item')->where(['goods_id'=>$goods_ids])->column('price,spec_value_str','id');

        foreach ($value as $each => $item){
            if(!in_array($each,$shop_goods)){
                return '商品ID:'.$each.'已下架';
            }
            foreach ($item as $item_each => $item_item){
                if(isset($goods_item[$item_each])){
                    //验证商品价格
                    if($item_item > $goods_item[$item_each]['price']){
                        return '商品规格:'.$goods_item[$item_each]['spec_value_str'] .'的秒杀价格高于原价';
                    }
                }
            }
        }
        return true;
    }

}