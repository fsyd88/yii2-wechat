<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\exception;

/**
 * Description of ResponseException
 *
 * @author ZHAO
 */
class ResponseException extends \Exception {

    //put your code here
    public function __construct($error, int $code = 0) {
        parent::__construct(json_encode($error, JSON_UNESCAPED_UNICODE), $code);
    }

}
