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
class WxBase extends BaseApi {

    protected $access_token;
    private static $_ins_component;

    public function init() {
        if (!self::$_ins_component) {
            self::$_ins_component = new Component([
                'component_appid' => $this->component_appid,
                'component_appsecret' => $this->component_appsecret,
                'component_verify_ticket' => $this->component_verify_ticket,
                'authorizer_appid' => $this->authorizer_appid,
                'authorizer_refresh_token' => $this->authorizer_refresh_token,
            ]);
        }
        $this->access_token = self::$_ins_component->getAuthorizerToken();
    }

    /**
     * POST 请求
     * @param string $uri  api 地址
     * @param array $data post 数据
     * @return array $response
     */
    public function post($uri, $data) {
        $get_uri = $this->buildGetUri($uri, ['access_token' => $this->access_token]);
        return $this->request('POST', $get_uri, $data);
    }

    public function get($uri, $data = []) {
        $data['access_token'] = $this->access_token;
        return $this->request('GET', $uri, $data);
    }

}
