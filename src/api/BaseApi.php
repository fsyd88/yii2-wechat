<?php

namespace fsyd88\wechat\api;

use fsyd88\wechat\common\Base;
use fsyd88\wechat\exception\ResponseException;

/**
 * Description of Base
 *
 * @author ZHAO
 */
class BaseApi extends Base
{
    protected $component_appid;
    protected $component_appsecret;
    protected $component_verify_ticket;
    protected $authorizer_appid;
    protected $authorizer_refresh_token;

    public function setComponentAppid($component_appid)
    {
        $this->component_appid = $component_appid;
        return $this;
    }

    public function setComponentAppsecret($component_appsecret)
    {
        $this->component_appsecret = $component_appsecret;
        return $this;
    }

    public function setComponentVerifyTicket($component_verify_ticket)
    {
        $this->component_verify_ticket = $component_verify_ticket;
        return $this;
    }

    public function setAuthorizerAppid($authorizer_appid)
    {
        $this->authorizer_appid = $authorizer_appid;
        return $this;
    }

    public function setAuthorizerRefreshToken($authorizer_refresh_token)
    {
        $this->authorizer_refresh_token = $authorizer_refresh_token;
        return $this;
    }

    /**
     * get token
     * @return object $result  json object
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/component_access_token.html
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
     * 获取/刷新接口调用令牌
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/api_authorizer_token.html
     */
    public function getAuthorizerToken()
    {
        $authorization_code = Yii::$app->cache->get($this->authorizer_appid);
        if (!$authorization_code) {
            $res = $this->postByComponent('cgi-bin/component/api_authorizer_token', [
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
     * 带 access_token POST 请求
     * @param string $uri api 地址
     * @param array $data post 数据
     * @return array $response
     */
    protected function post($uri, $data, $query = [], $raw_post = false)
    {
        if (!isset($query['access_token'])) {
            $query['access_token'] = $this->getAuthorizerToken();
        }
        $httpUri = $this->buildGetUri($uri, $query);
        $res = $raw_post ? $this->rawRequest('POST', $httpUri, $data) : $this->request('POST', $httpUri, $data);
        if ($res['errcode']) {
            throw new ResponseException($res, $res['errcode']);
        }
        return $res;
    }

    /**
     * 带 access_token GET 请求
     * @param $uri
     * @param array $data
     * @return mixed|string
     * @throws ResponseException
     */
    protected function httpGet($uri, $data = [])
    {
        if (!isset($data['access_token'])) {
            $data['access_token'] = $this->getAuthorizerToken();
        }
        $res = $this->request('GET', $uri, $data);
        if ($res['errcode']) {
            throw new ResponseException($res, $res['errcode']);
        }
        return $res;
    }

    /**
     * 带 component_access_token 的POST提交方式
     * @param $uri
     * @param $data
     * @param array $query
     * @param bool $raw_post
     * @return mixed|string
     * @throws ResponseException
     */
    protected function postByComponent($uri, $data, $query = [], $raw_post = false)
    {
        $query['component_access_token'] = $this->getComponentToken();
        $httpUri = $this->buildGetUri($uri, $query);
        $res = $raw_post ? $this->rawRequest('POST', $httpUri, $data) : $this->request('POST', $httpUri, $data);
        if ($res['errcode']) {
            throw new ResponseException($res, $res['errcode']);
        }
        return $res;
    }

    /**
     * 带 access_token 的GET提交方式
     * @param $uri
     * @param array $data
     * @return mixed|string
     * @throws ResponseException
     */
    protected function getByComponent($uri, $data = [])
    {
        $data['component_access_token'] = $this->getAuthorizerToken();
        $res = $this->request('GET', $uri, $data);
        if ($res['errcode']) {
            throw new ResponseException($res, $res['errcode']);
        }
        return $res;
    }

    /**
     * 未定义的 可以用这个直接调用接口
     * @param $uri
     * @param $options
     * @param string $method
     * @return mixed|string
     */
    public function execute($uri, $options, $method = 'POST')
    {
        return $this->rawRequest($method, $uri, $options);
    }
}
