<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\api;

/**
 * Description of Wxa
 *
 * @author ZHAO
 */
class Wxa extends BaseApi
{

    /**
     * 获取/设置/删除 服务器域名
     * @param string $action 操作类型[add,delete,set,get] 添加，删除，覆盖，获取
     * @param array $domains 合法域名[requestdomain=>[] request 合法域名；当 action 是 get 时不需要此字段
     *                                wsrequestdomain=>[]  socket 合法域名；当 action 是 get 时不需要此字段
     *                                uploaddomain=>[] uploadFile 合法域名；当 action 是 get 时不需要此字段
     *                                downloaddomain=>[] downloadFile 合法域名；当 action 是 get 时不需要此字段
     *                               ]
     *
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Mini_Program_Basic_Info/Server_Address_Configuration.html
     */
    public function modifyDomain(string $action, array $domains = [])
    {
        if ($action == 'get') {
            return $this->post('wxa/modify_domain', ['action' => $action]);
        }
        return $this->post('wxa/modify_domain', [
            'action' => $action,
            'requestdomain' => $domains['requestdomain'],
            'wsrequestdomain' => $domains['wsrequestdomain'],
            'uploaddomain' => $domains['uploaddomain'],
            'downloaddomain' => $domains['downloaddomain']
        ]);
    }

    /**
     * 设置业务域名
     * @param string $action 操作类型[add,delete,set,get] 添加，删除，覆盖，获取
     * @param type $webviewdomain
     *
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Mini_Program_Basic_Info/setwebviewdomain.html
     */
    public function setWebViewDomain(string $action, array $domains)
    {
        return $this->post('wxa/setwebviewdomain', ['action' => $action, 'webviewdomain' => $domains]);
    }

    /**
     * 绑定微信用户为体验者
     * @param string $wechatid
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Mini_Program_AdminManagement/Admin.html
     */
    public function bindTester($wechatid)
    {
        return $this->post('wxa/bind_tester', ['wechatid' => $wechatid]);
    }

    /**
     * 获取体验者列表
     * @param string $action
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Mini_Program_AdminManagement/memberauth.html
     */
    public function getMemberAuth($action = 'get_experiencer')
    {
        return $this->post('wxa/memberauth', ['action' => $action]);
    }

    /**
     * 绑定微信用户为体验者
     * @param string $userstr
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Mini_Program_AdminManagement/unbind_tester.html
     */
    public function unbindTester($userstr)
    {
        return $this->post('wxa/unbind_tester', ['userstr' => $userstr]);
    }

    /**
     * 获取代码草稿列表
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/code_template/gettemplatedraftlist.html
     */
    public function getTemplatedRaftList()
    {
        return $this->httpGet('wxa/gettemplatedraftlist', [
            'access_token' => $this->getComponentToken()
        ]);
    }

    /**
     * 将草稿添加到代码模板库
     * @param type $draft_id
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/code_template/addtotemplate.html
     */
    public function addToTemplate($draft_id)
    {
        return $this->post('wxa/addtotemplate', [
            'draft_id' => $draft_id,
        ], ['access_token' => $this->getComponentAccessToken()]);
    }

    /**
     * 获取代码模板列表
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/code_template/gettemplatelist.html
     */
    public function gettemplatelist($template_type = 0)
    {
        return $this->httpGet('wxa/gettemplatelist', [
            'template_type' => $template_type,
            'access_token' => $this->getComponentAccessToken()
        ]);
    }

    /**
     * 删除指定代码模版
     * @param number $template_id
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/code_template/deletetemplate.html
     */
    public function deleteTemplate($template_id)
    {
        return $this->post('wxa/deletetemplate', ['template_id' => $template_id,],
            ['access_token' => $this->getComponentAccessToken()]);
    }

    /**
     * 上传小程序代码
     * @param number $template_id 代码库中的代码模版 ID
     * @param string $ext_json 第三方自定义的配置
     * @param string $user_version 代码版本号，开发者可自定义（长度不要超过 64 个字符）
     * @param string $user_desc 代码描述，开发者可自定义
     *
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/commit.html
     */
    public function commit($template_id, $ext_json, $user_version, $user_desc)
    {
        return $this->post('wxa/commit', [
            'template_id' => $template_id,
            'ext_json' => $ext_json,
            'user_version' => $user_version,
            'user_desc' => $user_desc
        ]);
    }

    /**
     * 获取已上传的代码的页面列表
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/get_page.html
     */
    public function getPage()
    {
        return $this->httpGet('wxa/get_page');
    }

    /**
     * 获取的体验版二维码
     * @param string $path 指定二维码扫码后直接进入指定页面并可同时带上参数）
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/get_qrcode.html
     */
    public function getQrcode($path = null)
    {
        $params = [];
        if ($path) {
            $params['path'] = urlencode($path);
        }
        return $this->httpGet('wxa/get_qrcode', $params);
    }

    /**
     *
     * @param array $item_list 审核项列表（选填，至多填写 5 项） 参考：https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/Mini_Programs/code/submit_audit.html#%E5%AE%A1%E6%A0%B8%E9%A1%B9%E8%AF%B4%E6%98%8E
     * @param array $preview_info 预览信息（小程序页面截图和操作录屏）
     * @param string $version_desc 小程序版本说明和功能解释
     * @param string $feedback_info 反馈内容，至多 200 字 注：只有上个版本被驳回，才能使用 feedback_info、feedback_stuff 这两个字段，否则忽略处理
     * @param string $feedback_stuff 用 | 分割的 media_id 列表，至多 5 张图片, 可以通过新增临时素材接口上传而得到
     *
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/submit_audit.html
     */
    public function submitAudit($item_list = [], $preview_info = [], $version_desc = '', $feedback_info = '', $feedback_stuff = '')
    {
        $data = [];
        if (!empty($item_list)) {
            $data['item_list'] = $item_list;
        }
        if (!empty($preview_info)) {
            $data['preview_info'] = $preview_info;
        }
        if ($item_list) {
            $data['version_desc'] = $version_desc;
        }
        if ($item_list) {
            $data['feedback_info'] = $feedback_info;
        }
        if ($item_list) {
            $data['feedback_stuff'] = $feedback_stuff;
        }
        return $this->post('wxa/submit_audit', $data);
    }

    /**
     * 查询指定发布审核单的审核状态
     * @param number $auditid 提交审核时获得的审核 id
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/get_auditstatus.html
     */
    public function getAuditStatus($auditid)
    {
        return $this->post('wxa/get_auditstatus', ['auditid' => $auditid]);
    }

    /**
     * 查询最新一次提交的审核状态
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/get_latest_auditstatus.html
     */
    public function getLatestAuditStatus()
    {
        return $this->httpGet('wxa/get_latest_auditstatus');
    }

    /**
     * 小程序审核撤回  注意： 单个帐号每天审核撤回次数最多不超过 1 次，一个月不超过 10 次。
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/undocodeaudit.html
     */
    public function undocodeAudit()
    {
        return $this->httpGet('wxa/undocodeaudit');
    }

    /**
     * 发布已通过审核的小程序  调用本接口可以发布最后一个审核通过的小程序代码版本
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/release.html
     */
    public function release()
    {
        return $this->post('wxa/release', (object)[]);
    }

    /**
     * 版本回退
     * 1.如果没有上一个线上版本，将无法回退
     * 2.只能向上回退一个版本，即当前版本回退后，不能再调用版本回退接口
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/revertcoderelease.html
     */
    public function revertCodeRelease()
    {
        return $this->httpGet('wxa/revertcoderelease');
    }

    /**
     * 分阶段发布
     * 发布小程序接口 是全量发布，会影响到现网的所有用户。
     * 而本接口时创建一个灰度发布的计划，可以控制发布的节奏，避免一上线就影响到所有的用户。
     * 可以多次调用本次接口，将灰度的比例（gray_percentage）逐渐增大
     * @param number $gray_percentage 灰度的百分比 1 ~ 100 的整数
     *
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/grayrelease.html
     *
     */
    public function grayRelease($gray_percentage)
    {
        return $this->post('wxa/grayrelease', ['gray_percentage' => $gray_percentage]);
    }

    /**
     * 查询当前分阶段发布详情
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/getgrayreleaseplan.html
     */
    public function getGrayReleasePlan()
    {
        return $this->httpGet('wxa/getgrayreleaseplan');
    }

    /**
     * 取消分阶段发布
     * 在小程序分阶段发布期间，可以随时调用本接口取消分阶段发布。
     * 取消分阶段发布后，受影响的微信用户（即被灰度升级的微信用户）的小程序版本将回退到分阶段发布前的版本
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/revertgrayrelease.html
     */
    public function revertGrayRelease()
    {
        return $this->httpGet('wxa/revertgrayrelease');
    }

    /**
     * 修改小程序线上代码的可见状态（仅供第三方代小程序调用）
     * @param type $action 设置可访问状态，发布后默认可访问，close 为不可见，open 为可见
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/change_visitstatus.html
     */
    public function changeVisitstatus($action)
    {
        return $this->post('wxa/change_visitstatus', ['action' => $action]);
    }

    /**
     * 查询服务商的当月提审限额（quota）和加急次数
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/query_quota.html
     */
    public function queryQuota()
    {
        return $this->httpGet('wxa/queryquota');
    }

    /**
     * 加急审核申请
     * 有加急次数的第三方可以通过该接口，对已经提审的小程序进行加急操作，加急后的小程序预计2-12小时内审完
     * @param number $auditid 审核单ID
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/speedup_audit.html
     */
    public function speedupAudit($auditid)
    {
        return $this->post('wxa/speedupaudit', ['auditid' => $auditid]);
    }

    /**
     * 获取审核时可填写的类目信息
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/category/get_category.html
     */
    public function getCategory()
    {
        return $this->httpGet('wxa/get_category');
    }

    /**
     * 获取小程序码  数量限制 10w
     * @param $path
     * @param int $width
     * @return array
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/qrcode/getwxacode.html
     */
    public function getWxacode($path, $width = 430)
    {
        $data = [
            'path' => $path,
            'width' => $width
        ];
        return $this->post('wxa/getwxacode', $data);
    }

    /**
     * 获取小程序码 无数量限制
     * @param $scene
     * @param $path
     * @param int $width
     * @return array
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/qrcode/getwxacodeunlimit.html
     */
    public function getWxacodeUnlimit($scene, $path, $width = 430)
    {
        $data = [
            'scene' => $scene,
            'path' => $path,
            'width' => $width
        ];
        return $this->post('wxa/getwxacodeunlimit', $data);
    }
}
