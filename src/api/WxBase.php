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

    public function init() {
        $component = new Component();
        $this->access_token = $component->getAuthorizerToken();
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
