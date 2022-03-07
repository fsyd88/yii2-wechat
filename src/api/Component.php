<?php

namespace fsyd88\wechat\api;

use fsyd88\wechat\exception\ResponseException;
use Yii;

/**
 * Description of Component
 *
 * @author ZHAO
 */
class Component extends BaseApi
{

    /**
     * POST 请求
     * @param string $uri api 地址
     * @param array $data post 数据
     * @return array $response
     */
    protected function post($uri, $data, $query = [], $raw_post = false)
    {
        $component_access_token = $this->getComponentToken();
        $query['component_access_token'] = $component_access_token;
        $get_uri = $this->buildGetUri($uri, $query);
        if ($raw_post) {
            $res = $this->rawRequest('POST', $get_uri, $data);
        } else {
            $res = $this->request('POST', $get_uri, $data);
        }
        if ($res['errcode']) {
            throw new ResponseException($res, $res['errcode']);
        }
        return $res;
    }

    /**
     * get token
     * @return object $result  json object
     */
    public function getComponentToken()
    {
        $token = Yii::$app->cache->get('wechat_open_component_access_token');
        if (!$token) {
            $res = $this->request('POST', 'cgi-bin/component/api_component_token', [
                    'component_appid' => $this->component_appid,
                    'component_appsecret' => $this->component_appsecret,
                    'component_verify_ticket' => $this->component_verify_ticket]
            );
            if ($res['errcode']) {
                throw new ResponseException($res, $res['errcode']);
            }
            Yii::$app->cache->set('wechat_open_component_access_token', $res['component_access_token'], $res['expires_in']);
            $token = $res['component_access_token'];
        }
        return $token;
    }

    /**
     * 获取预授权码
     * @return string
     */
    public function getCreatePreauthcode()
    {
        $res = $this->post('cgi-bin/component/api_create_preauthcode', ['component_appid' => $this->component_appid]);
        return $res['pre_auth_code'];
    }

    /**
     * 获取登陆页面
     * @param type $redirect_uri
     * @param type $auth_type
     * @return type
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
     * 获取/刷新接口调用令牌
     */
    public function getAuthorizerToken()
    {
        $authorization_code = Yii::$app->cache->get($this->authorizer_appid);
        if (!$authorization_code) {
            $res = $this->post('cgi-bin/component/api_authorizer_token', [
                    'component_appid' => $this->component_appid,
                    'authorizer_appid' => $this->authorizer_appid,
                    'authorizer_refresh_token' => $this->authorizer_refresh_token]
            );
            $authorization_code = $res['authorizer_access_token'];
            Yii::$app->cache->set($this->authorizer_appid, $authorization_code, $res['expires_in']);
        }
        return $authorization_code;
    }

    /**
     * 获取授权信息
     * @return type
     */
    public function getQueryAuth($auth_code)
    {
        return $this->post('cgi-bin/component/api_query_auth', ['component_appid' => $this->component_appid, 'authorization_code' => $auth_code]);
    }

    /*
     * 获取授权方的帐号基本信息
     */

    public function getAuthorizerInfo()
    {
        return $this->post('cgi-bin/component/api_get_authorizer_info', ['component_appid' => $this->component_appid, 'authorizer_appid' => $this->authorizer_appid]);
    }

    /**
     * 获取授权方选项信息
     * @param string $option_name 选项名称
     */
    public function getAuthorizerOption($option_name)
    {
        return $this->post('cgi-bin/component/api_get_authorizer_option', ['component_appid' => $this->component_appid,
            'authorizer_appid' => $this->authorizer_appid, 'option_name' => $option_name]);
    }

    /**
     * 设置授权方选项信息
     * @param string $option_name 选项名称
     * @param string $option_value 设置的选项值
     */
    public function setAuthorizerOption($option_name, $option_value)
    {
        return $this->post('cgi-bin/component/api_set_authorizer_option', [
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
     */
    public function getAuthorizerList($offset, $count)
    {
        return $this->post('cgi-bin/component/api_get_authorizer_list', ['component_appid' => $this->component_appid, 'offset' => $offset, 'count' => $count]);
    }

    /**
     * 登陆获取 openid
     * @param type $js_code js获取的code
     * @return type
     */
    public function jscode2session($js_code)
    {
        $uri = $this->buildGetUri('sns/component/jscode2session', [
            'appid' => $this->authorizer_appid,
            'js_code' => $js_code,
            'grant_type' => 'authorization_code',
            'component_appid' => $this->component_appid,
            'component_access_token' => $this->getComponentToken(),
        ]);
        return $this->request('GET', $uri, []);
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
     */
    public function fastRegisterWeapp($params)
    {
        $options = [
            'body' => json_encode($params, JSON_UNESCAPED_UNICODE),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];
        return $this->post('cgi-bin/component/fastregisterweapp', $options, ['action' => 'create'], true);
    }

    /**
     * 快速注册企业小程序 查询状态
     * {
     * "name": "tencent", // 企业名 （需与工商部门登记信息一致）；如果是“无主体名称个体工商户”则填“个体户+法人姓名”，例如“个体户张三”
     * "legal_persona_wechat": "123", // 法人微信
     * "legal_persona_name": "candy", // 法人姓名
     * }
     * @param $params
     */
    public function fastRegisterQuery($params)
    {
        $options = [
            'body' => json_encode($params, JSON_UNESCAPED_UNICODE),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];
        return $this->post('cgi-bin/component/fastregisterweapp', $options, ['action' => 'search'], true);
    }
}
