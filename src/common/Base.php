<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\common;

use GuzzleHttp\Client;
use GuzzleHttp\json_decode;

/**
 * Description of ApiBase
 *
 * @author ZHAO
 */
class Base {

    protected $base_uri = 'https://api.weixin.qq.com/';

    public function __construct(array $config) {
        foreach ($config as $name => $value) {
            $this->$name = $value;
        }
        $this->init($config);
    }

    protected function buildGetUri($uri, $data = null) {
        return $uri . '?' . http_build_query($data);
    }

    public function request($method, $uri, array $data) {
        $options = [];
        if ($method == 'POST' && $data) {
            $options['json'] = $data;
        } elseif ($data) {
            $options['query'] = $data;
        }
        $client = new Client(['base_uri' => $this->base_uri]);
        $response = $client->request($method, $uri, $options);
        $content = $response->getBody()->getContents();
        $result = json_decode($content, true);
        if (JSON_ERROR_NONE == json_last_error()) {
            return $result;
        } else {
            return $content;
        }
    }

    public function init($config = []) {
        
    }

}
