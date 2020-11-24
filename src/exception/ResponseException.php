<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\exception;

use yii\base\UserException;

/**
 * Description of ResponseException
 *
 * @author ZHAO
 */
class ResponseException extends UserException
{

    //put your code here
    public function __construct($error, $code = 0)
    {
        parent::__construct(json_encode($error, JSON_UNESCAPED_UNICODE), $code);
    }

}
