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
namespace app\api\controller;
use app\api\logic\ShareLogic;

class Share extends ApiBase{
    public function shareGoods(){
        $id = $this->request->get('id');
        $url = $this->request->get('url');
        $client = $this->request->get('client',1);
        if($id && $url){
            $result = ShareLogic::shareGoods($this->user_id,$id,$url,$client);
            $this->_success($result['msg'], $result['data'], $result['code']);
        }
        $this->_error('缺少参数', '');
    }


    //用户分销海报
    public function userPoster()
    {
        $url = $this->request->get('url');
        $client = $this->request->get('client');
        if (empty($client)){
            $this->_error('参数缺失');
        }
        $result = ShareLogic::getUserPoster($this->user_id, $url, $client);
        return $result;
    }
}