<?php

namespace fsyd88\wechat\api;

/**
 * 新的模板功能，订阅消息接口
 *
 * @author ZHAO
 */
class NewTemp extends WxBase {

    /**
     * 获取当前帐号所设置的类目信息
     * 本接口用于获取小程序帐号当前所设置的类目
     * @return type
     */
    public function getCategory() {
        return $this->get('wxaapi/newtmpl/getcategory');
    }

    /**
     * 获取模板标题列表
     * 本接口用于获取小程序订阅消息的模板库标题列表
     */
    public function getPubTemplateTitles() {
        return $this->get('wxaapi/newtmpl/getcategory');
    }

    /**
     * 获取模板标题下的关键词库
     * 本接口用于获取小程序订阅消息模板库中某个模板标题下关键词库
     * @param string $tid 模板标题 id，可通过接口获取
     * @return type
     */
    public function getPubTemplateKeywords($tid) {
        return $this->get('wxaapi/newtmpl/getpubtemplatekeywords', ['tid' => $tid]);
    }

    /**
     * 组合模板并添加到个人模板库
     * 本接口用于组合模板并添加至帐号下的个人模板库，得到用于发消息的模板
     *  
     * @param string $tid 模板标题 id，可通过接口获取，也可登录小程序后台查看获取
     * @param array $kidList 开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如 [3,5,4] 或 [4,5,3]），最多支持5个，最少2个关键词组合
     * @param string $sceneDesc 服务场景描述，15个字以内
     * @return type
     */
    public function addTemplate(string $tid, array $kidList, string $sceneDesc) {
        return $this->post('wxaapi/newtmpl/addtemplate', ['tid' => $tid, 'kidList' => $kidList, 'sceneDesc' => $sceneDesc]);
    }

    /**
     * 获取帐号下的模板列表
     * 本接口用于获取小程序帐号下的个人模板库中已经存在的模板列表
     */
    public function getTemplate() {
        return $this->get('wxaapi/newtmpl/gettemplate');
    }

    /**
     * 删除帐号下的某个模板
     * @param string $priTmplId 要删除的模板id
     * @return type
     */
    public function delTemplate($priTmplId) {
        return $this->post('wxaapi/newtmpl/deltemplate', ['priTmplId' => $priTmplId]);
    }

}
