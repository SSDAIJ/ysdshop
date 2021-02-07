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


use app\api\logic\DistributionLogic;

class Distribution extends ApiBase
{

    /**
     * 填写邀请码
     */
    public function code()
    {
        $code = $this->request->post('code');
        $data = [
            'user_id' => $this->user_id,
            'code' => $code,
        ];
        $result = $this->validate($data, 'app\api\validate\DistributionCode');
        if ($result !== true) {
            $this->_error($result, [], 0, 0);
        }
        $result = DistributionLogic::code($code, $this->user_id);
        if ($result !== true) {
            $this->_error($result, [], 0, 0);
        }
        $this->_success();
    }

    /**
     * 分销会员申请
     */
    public function apple()
    {
        $post = $this->request->post();
        $post['user_id'] = $this->user_id;
        $result = $this->validate($post, 'app\api\validate\DistributionApply');
        if ($result !== true) {
            $this->_error($result);
        }
        $result = DistributionLogic::apple($post,$this->user_id);
        if ($result !== true) {
            $this->_error($result);
        }
        $this->_success();
    }

    /**
     * 最新的分销会员申请详情
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function appleDetail()
    {
        $this->_success('',DistributionLogic::appleDetail($this->user_id));
    }


    /**
     * User: 意象信息科技 mjf
     * Desc: 我的分销上级
     */
    public function myLeader()
    {
        $this->_success('', DistributionLogic::myLeader($this->user_id));
    }


    /**
     * 分销推广主页信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->_success('', DistributionLogic::index($this->user_id));
    }



    /**
     * 分销订单
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function order()
    {
        $get = $this->request->get();
        $page = $this->request->get('page_no', $this->page_no);
        $size = $this->request->get('page_size', $this->page_size);
        $this->_success('', DistributionLogic::order($this->user_id, $get, $page, $size));
    }


    /**
     * 月度账单
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function monthBill()
    {
        $page = $this->request->get('page_no', $this->page_no);
        $size = $this->request->get('page_size', $this->page_size);
        $this->_success('', DistributionLogic::getMonthBill($this->user_id, $page, $size));
    }

    /**
     * 月度账单明细
     */
    public function monthDetail()
    {
        $get = $this->request->get();
        $page = $this->request->get('page_no', $this->page_no);
        $size = $this->request->get('page_size', $this->page_size);
        $this->_success('', DistributionLogic::getMonth($get, $this->user_id, $page, $size));
    }
}