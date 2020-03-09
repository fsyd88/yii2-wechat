<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat;

use GuzzleHttp\Client;

/**
 * 原始小程序api
 *
 * @author ZHAO
 */
class MiniProgram extends common\Base {

    protected $appid;
    protected $secret;
    protected $access_token;

    public function init($config = array()) {
        $this->access_token = $this->getAccessToken();
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

    public function getAccessToken() {
        $access_token = \Yii::$app->cache->get('wechat_mini_access_token');
        if (!$access_token) {
            $uri = $this->buildGetUri('cgi-bin/token', ['grant_type' => 'client_credential', 'appid' => $this->appid, 'secret' => $this->secret]);
            $res = $this->request('GET', $uri, []);
            if ($res['errcode'] != 0) {
                throw new exception\ResponseException($res);
            }
            $access_token = $res['access_token'];
            \Yii::$app->cache->set('wechat_mini_access_token', $access_token, $res['expires_in']);
        }
        return $access_token;
    }

    /**
     * 获取小程序二维码，适用于需要的码数量较少的业务场景
     * @param string $path   扫码进入的小程序页面路径，最大长度 128 字节，不能为空；
     * @param number $width
     */
    public function createQRCode($path, $width = 430) {
        return $this->post('cgi-bin/wxaapp/createwxaqrcode', ['path' => $path, 'width' => $width]);
    }

    /**
     * 
     * @param string $scene
     * @param string $page
     * @param number $width
     * @param boolean $auto_color
     * @param Object $line_color
     * @param boolean $is_hyaline
     * @return type
     */
    public function getUnlimited($scene, $page = null, $width = null, $auto_color = null, $line_color = null, $is_hyaline = null) {
        $params = ['scene' => $scene, 'page' => $page, 'width' => $width,
            'auto_color' => $auto_color, 'line_color' => $line_color, 'is_hyaline' => $is_hyaline];
        foreach ($params as $k => $v) {
            if ($v === null) {
                unset($params[$k]);
            }
        }
        return $this->post('wxa/getwxacodeunlimit', $params);
    }

}
