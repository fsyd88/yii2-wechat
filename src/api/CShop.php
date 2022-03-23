<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\api;

/**
 * 自定义交易组件
 *
 * @author ZHAO
 */
class CShop extends BaseApi
{
    /**
     * 申请自定义交易组件
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/enter/enter_apply.html
     */
    public function apply()
    {
        return $this->post('shop/register/apply', (object)[]);
    }

    /**
     * 获取接入状态
     * 如果账户未接入，将返回错误码1040003
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/enter/enter_check.html
     */
    public function check()
    {
        return $this->post('shop/register/check', (object)[]);
    }

    /**
     * 完成接入任务
     * @param number $access_info_item 6:完成spu接口，7:完成订单接口，8:完成物流接口，9:完成售后接口，10:测试完成，11:发版完成
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/enter/finish_access_info.html
     */
    public function finishAccessInfo($access_info_item)
    {
        return $this->post('shop/register/finish_access_info', [
            'access_info_item' => $access_info_item
        ]);
    }

    /**
     * 场景接入申请
     * @param number $scene_group_id 1:视频号、公众号场景
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/ministore/minishopopencomponent2/API/enter/scene_apply.html
     */
    public function applyScene($scene_group_id = 1)
    {
        return $this->post('shop/register/apply_scene', [
            'scene_group_id' => $scene_group_id
        ]);
    }

    /**
     * 获取商品类目
     *
     * 获取所有三级类目及其资质相关信息 注意：该接口拉到的是【全量】三级类目数据，数据回包大小约为2MB。
     * 所以请商家自己做好缓存，不要频繁调用（有严格的频率限制），该类目数据不会频率变动，推荐商家每天调用一次更新商家自身缓存
     * 若该类目资质必填，则新增商品前，必须先通过该类目资质申请接口进行资质申请; 若该类目资质不需要，则该类目自动拥有，无需申请，
     * 如依然调用，会报错1050011； 若该商品资质必填，则新增商品时，带上商品资质字段。 接入类目审核回调，才可获取审核结果。
     */
    public function catGet()
    {
        $data = \Yii::$app->cache->get('wx_shop_cat_get');
        if (!$data) {
            $data = $this->post('shop/cat/get', (object)[]);
            if ($data['errcode'] == 0) {
                \Yii::$app->cache->set('wx_shop_cat_get', $data, 86400); //缓存1天
            }
        }
        return $data;
    }

    /**
     * 上传图片
     * 上传类目、品牌、商品时，需使用图片上传接口换取临时链接用于上传，上传后微信侧会转为永久链接。临时链接在微信侧永久有效，商家侧可以存储临时链接，避免重复换取链接。
     * 微信侧组件图片域名，store.mp.video.tencent-cloud.com,mmbizurl.cn,mmecimage.cn/p/。
     *
     * @param string $img_url upload_type=1时必传
     */
    public function imgUpload($img_url, $resp_type = 1, $upload_type = 1)
    {
        return $this->post('shop/img/upload', [
            'resp_type' => $resp_type,
            'upload_type' => $upload_type,
            'img_url' => $img_url
        ]);
    }

    /**
     * 品牌审核
     * @param array $audit_req audit_req的数组
     */
    public function auditBrand($audit_req)
    {
        return $this->post('shop/audit/audit_brand', [
            'audit_req' => $audit_req,
        ]);
    }

    /**
     * 类目审核
     * @param array $audit_req audit_req的数组
     */
    public function auditCategory($audit_req)
    {
        return $this->post('shop/audit/audit_category', [
            'audit_req' => $audit_req,
        ]);
    }

    /**
     * 获取审核结果
     * @param number $audit_id 提交审核时返回的id
     */
    public function auditResult($audit_id)
    {
        return $this->post('shop/audit/result', [
            'audit_id' => $audit_id,
        ]);
    }

    /**
     * 获取小程序资质
     * @param number $req_type 1 类目  , 2品牌
     */
    public function getMiniappCertificate($req_type)
    {
        return $this->post('shop/audit/get_miniapp_certificate', [
            'req_type' => $req_type,
        ]);
    }

    /**
     * 获取已申请成功的类类目列表
     */
    public function getCategoryList()
    {
        return $this->post('shop/account/get_category_list', (object)[]);
    }

    /**
     * 获取已申请成功的品牌列表
     */
    public function getBrandList()
    {
        return $this->post('shop/account/get_brand_list', (object)[]);
    }

    /**
     * 更新商家信息
     */
    public function updateInfo($data)
    {
        return $this->post('shop/account/update_info', $data);
    }

    /**
     * 添加商品
     */
    public function spuAdd($data)
    {
        return $this->post('shop/spu/add', $data);
    }

    /**
     * 删除商品
     * @param number|null $out_product_id 商家自定义商品ID，与product_id二选一
     * @param number|null $product_id 交易组件平台内部商品ID，与out_product_id二选一
     * @return array
     */
    public function spuDel($out_product_id, $product_id = null)
    {
        $data = ['out_product_id' => $out_product_id];
        if ($product_id) {
            $data = ['product_id' => $product_id];
        }
        return $this->post('shop/spu/del', $data);
    }

    /**
     * 撤回商品审核
     * @param number|null $out_product_id 商家自定义商品ID，与product_id二选一
     * @param number|null $product_id 交易组件平台内部商品ID，与out_product_id二选一
     */
    public function spuDelAudit($out_product_id, $product_id = null)
    {
        $data = ['out_product_id' => $out_product_id];
        if ($product_id) {
            $data = ['product_id' => $product_id];
        }
        return $this->post('shop/spu/del_audit', $data);
    }

    /**
     * 获取商品列表
     */
    public function spuGetList($data)
    {
        return $this->post('shop/spu/get_list', $data);
    }

    /**
     * 更新商品
     * 注意：更新成功后会更新到草稿数据并直接提交审核，审核完成后有回调，也可通过get接口的edit_status查看是否通过审核
     */
    public function spuUpdate($data)
    {
        return $this->post('shop/spu/update', $data);
    }

    /**
     * 上架商品
     * 如果该商品处于自主下架状态，调用此接口可把直接把商品重新上架 该接口不影响已经在审核流程的草稿数据
     * @param number|null $out_product_id 商家自定义商品ID，与product_id二选一
     * @param number|null $product_id 交易组件平台内部商品ID，与out_product_id二选一
     */
    public function spuListing($out_product_id, $product_id = null)
    {
        $data = ['out_product_id' => $out_product_id];
        if ($product_id) {
            $data = ['product_id' => $product_id];
        }
        return $this->post('shop/spu/listing', $data);
    }

    /**
     * 下架商品
     * 从初始值/上架状态转换成自主下架状态
     * @param number|null $out_product_id 商家自定义商品ID，与product_id二选一
     * @param number|null $product_id 交易组件平台内部商品ID，与out_product_id二选一
     */
    public function spuDelisting($out_product_id, $product_id = null)
    {
        $data = ['out_product_id' => $out_product_id];
        if ($product_id) {
            $data = ['product_id' => $product_id];
        }
        return $this->post('shop/spu/delisting', $data);
    }

    /**
     * 生成订单
     */
    public function orderAdd($data)
    {
        return $this->post('shop/order/add', $data);
    }

    /**
     * 同步订单支付结果
     */
    public function orderPay($data)
    {
        return $this->post('shop/order/pay', $data);
    }

    /**
     * 获取订单详情
     */
    public function orderGet($out_order_id, $openid)
    {
        $data = [
            'out_order_id' => $out_order_id,
            'openid' => $openid,
        ];
        return $this->post('shop/order/get', $data);
    }

    /**
     * 按照推广员获取订单
     */
    public function orderListByFinder($data)
    {
        return $this->post('shop/order/get_list_by_finder', $data);
    }

    /**
     * 按照分享员获取订单
     */
    public function orderListByShare($data)
    {
        return $this->post('shop/order/get_list_by_share', $data);
    }

    /**
     * 获取订单列表
     */
    public function orderGetList($data)
    {
        return $this->post('shop/order/get_list', $data);
    }

    /**
     * 获取快递公司列表
     */
    public function getCompanyList()
    {
        $data = \Yii::$app->cache->get('shop_get_company_list');
        if (!$data) {
            $data = $this->post('shop/delivery/get_company_list', (object)[]);
            \Yii::$app->cache->set('shop_get_company_list', $data, 86400);
        }
        return $data;
    }

    /**
     * 订单发货
     */
    public function deliverySend($data)
    {
        return $this->post('shop/delivery/send', $data);
    }

    /**
     * 订单确认收货
     */
    public function deliveryRecieve($out_order_id, $openid)
    {
        return $this->post('shop/delivery/recieve', [
            'out_order_id' => $out_order_id,
            'openid' => $openid,
        ]);
    }

    /**
     * 生成售后单
     */
    public function ecaftersaleAdd($params)
    {
        return $this->post('shop/ecaftersale/add', $params);
    }

    /**
     * 用户取消售后单
     */
    public function ecaftersaleCancel($params)
    {
        return $this->post('shop/ecaftersale/cancel', $params);
    }

    /**
     * 同意退款
     */
    public function ecaftersaleAcceptrefund($params)
    {
        return $this->post('shop/ecaftersale/acceptrefund', $params);
    }

    /**
     * 同意退货
     */
    public function ecaftersaleAcceptreturn($params)
    {
        return $this->post('shop/ecaftersale/acceptreturn', $params);
    }

    /**
     * 拒绝售后
     */
    public function ecaftersaleReject($aftersale_id)
    {
        return $this->post('shop/ecaftersale/reject', ['aftersale_id' => $aftersale_id]);
    }

    /**
     * 更新售后单
     */
    public function ecaftersaleUpdate($params)
    {
        return $this->post('shop/ecaftersale/update', $params);
    }

    /**
     * 获取推广员列表
     */
    public function promoterList($page, $page_size = 10)
    {
        return $this->post('shop/promoter/list', [
            'page' => $page,
            'page_size' => $page_size,
        ]);
    }
}
