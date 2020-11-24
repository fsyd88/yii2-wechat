<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\api;

/**
 * Description of WxBase
 *
 * @author ZHAO
 */
class WxBase extends BaseApi
{

    protected $access_token;
    private static $_ins_component;

    public function init($config = [])
    {
        if (!self::$_ins_component) {
            self::$_ins_component = new Component($config);
        }
        $this->access_token = self::$_ins_component->getAuthorizerToken();
    }

    protected function getComponentAccessToken()
    {
        return self::$_ins_component->getComponentToken();
    }

    /**
     * POST 请求
     * @param string $uri api 地址
     * @param array $data post 数据
     * @return array $response
     */
    public function post($uri, $data)
    {
        if (isset($data['access_token'])) {
            $access_token = $data['access_token'];
            unset($data['access_token']);
        } else {
            $access_token = $this->access_token;
        }
        $get_uri = $this->buildGetUri($uri, ['access_token' => $access_token]);
        return $this->request('POST', $get_uri, $data);
    }

    public function get($uri, $data = [])
    {
        if (!isset($data['access_token'])) {
            $data['access_token'] = $this->access_token;
        }
        return $this->request('GET', $uri, $data);
    }

}
