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
    protected function post($uri, $data)
    {
        $component_access_token = $this->getComponentToken();
        $get_uri = $this->buildGetUri($uri, ['component_access_token' => $component_access_token]);
        $res = $this->request('POST', $get_uri, $data);
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
        $pre_auth_code = \Yii::$app->cache->get('wechat_open_pre_auth_code');
        if (!$pre_auth_code) {
            $res = $this->post('cgi-bin/component/api_create_preauthcode', ['component_appid' => $this->component_appid]);
            Yii::$app->cache->set('wechat_open_pre_auth_code', $res['pre_auth_code'], $res['expires_in']);
            $pre_auth_code = $res['pre_auth_code'];
        }
        return $pre_auth_code;
    }

    /**
     * 删除预授权码
     */
    public function deletePreauthcode()
    {
        Yii::$app->cache->delete('wechat_open_pre_auth_code');
        return $this;
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

}
