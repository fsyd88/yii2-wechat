<?php

namespace fsyd88\wechat\api;

/**
 * Description of Component
 *
 * @author ZHAO
 */
class Component extends BaseApi
{
    /**
     * 获取预授权码
     * @return string
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/pre_auth_code.html
     */
    public function getCreatePreauthcode()
    {
        $res = $this->postByComponent('cgi-bin/component/api_create_preauthcode', ['component_appid' => $this->component_appid]);
        return $res['pre_auth_code'];
    }

    /**
     * 获取登陆页面
     * @param type $redirect_uri
     * @param type $auth_type
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Before_Develop/Official_Accounts/official_account_website_authorization.html
     */
    public function getLoginPage($redirect_uri, $auth_type = null)
    {
        $query = [
            'component_appid' => $this->component_appid,
            'pre_auth_code' => $this->getCreatePreauthcode(),
            'redirect_uri' => $redirect_uri,
        ];
        if ($auth_type) {
            $query['auth_type'] = $auth_type;
        }
        return $this->buildGetUri('https://mp.weixin.qq.com/cgi-bin/componentloginpage', $query);
    }

    /**
     * 获取授权信息
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/authorization_info.html
     */
    public function getQueryAuth($auth_code)
    {
        return $this->post('cgi-bin/component/api_query_auth', [
            'component_appid' => $this->component_appid,
            'authorization_code' => $auth_code
        ]);
    }

    /**
     * 获取授权方的帐号基本信息
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/api_get_authorizer_info.html
     */
    public function getAuthorizerInfo()
    {
        return $this->postByComponent('cgi-bin/component/api_get_authorizer_info', [
            'component_appid' => $this->component_appid,
            'authorizer_appid' => $this->authorizer_appid
        ]);
    }

    /**
     * 获取授权方选项信息
     * @param string $option_name 选项名称
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/Account_Authorization/api_get_authorizer_option.html
     */
    public function getAuthorizerOption($option_name)
    {
        return $this->postByComponent('cgi-bin/component/api_get_authorizer_option', [
            'component_appid' => $this->component_appid,
            'authorizer_appid' => $this->authorizer_appid,
            'option_name' => $option_name
        ]);
    }

    /**
     * 设置授权方选项信息
     * @param string $option_name 选项名称
     * @param string $option_value 设置的选项值
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/Account_Authorization/api_set_authorizer_option.html
     */
    public function setAuthorizerOption($option_name, $option_value)
    {
        return $this->postByComponent('cgi-bin/component/api_set_authorizer_option', [
            'component_appid' => $this->component_appid,
            'authorizer_appid' => $this->authorizer_appid,
            'option_name' => $option_name,
            'option_value' => $option_value,
        ]);
    }

    /**
     * 拉取所有已授权的帐号信息
     * @param number $offset 偏移位置/起始位置
     * @param number $count 拉取数量，最大为 500
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/Account_Authorization/api_get_authorizer_list.html
     */
    public function getAuthorizerList($offset, $count)
    {
        return $this->postByComponent('cgi-bin/component/api_get_authorizer_list', [
            'component_appid' => $this->component_appid,
            'offset' => $offset,
            'count' => $count
        ]);
    }

    /**
     * 登陆获取 openid
     * @param type $js_code js获取的code
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/others/WeChat_login.html
     */
    public function jscode2session($js_code)
    {
        return $this->getByComponent('sns/component/jscode2session', [
            'appid' => $this->authorizer_appid,
            'js_code' => $js_code,
            'grant_type' => 'authorization_code',
            'component_appid' => $this->component_appid,
            'component_access_token' => $this->getComponentToken(),
        ]);
    }

    /**
     * 快速注册企业小程序
     * {
     * "name": "tencent", // 企业名 （需与工商部门登记信息一致）；如果是“无主体名称个体工商户”则填“个体户+法人姓名”，例如“个体户张三”
     * "code": "123", // 企业代码
     * "code_type": 1, // 企业代码类型（1：统一社会信用代码， 2：组织机构代码，3：营业执照注册号）
     * "legal_persona_wechat": "123", // 法人微信
     * "legal_persona_name": "candy", // 法人姓名
     * "component_phone": "1234567" //第三方联系电话
     * }
     * @param $params
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Register_Mini_Programs/Fast_Registration_Interface_document.html
     */
    public function fastRegisterWeapp($params)
    {
        $options = [
            'body' => json_encode($params, JSON_UNESCAPED_UNICODE),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];
        return $this->postByComponent('cgi-bin/component/fastregisterweapp', $options, ['action' => 'create'], true);
    }

    /**
     * 快速注册企业小程序 查询状态
     * {
     * "name": "tencent", // 企业名 （需与工商部门登记信息一致）；如果是“无主体名称个体工商户”则填“个体户+法人姓名”，例如“个体户张三”
     * "legal_persona_wechat": "123", // 法人微信
     * "legal_persona_name": "candy", // 法人姓名
     * }
     * @param $params
     *
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Register_Mini_Programs/Fast_Registration_Interface_document.html
     */
    public function fastRegisterQuery($params)
    {
        $options = [
            'body' => json_encode($params, JSON_UNESCAPED_UNICODE),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];
        return $this->postByComponent('cgi-bin/component/fastregisterweapp', $options, ['action' => 'search'], true);
    }
}
