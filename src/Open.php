<?php

namespace fsyd88\wechat;

use fsyd88\wechat\api\Component;
use fsyd88\wechat\api\NewTemp;
use fsyd88\wechat\api\WxOpen;
use fsyd88\wechat\api\Wxa;
use fsyd88\wechat\api\MsgCrypt;

/**
 * wechat 第三方平台
 * 
 * @property \fsyd88\wechat\api\Component $component component
 * @property \fsyd88\wechat\api\NewTemp $newtemp newtemp
 * @property \fsyd88\wechat\api\WxOpen $wxopen wxopen
 * @property \fsyd88\wechat\api\Wxa $wxa wxa
 * 
 * 
 */
class Open extends \yii\base\BaseObject {

    private $_config;

    /**
     * wechat open
     * @param array $config  [component_appid=>'',component_appsecret=>'',component_verify_ticket=>'',authorizer_appid=>'',authorizer_refresh_token=>'']
     */
    public function __construct(array $config) {
        $this->_config = $config;
    }

    public function getComponent() {
        return new Component($this->_config);
    }

    public function getNewtemp() {
        return new NewTemp($this->_config);
    }

    public function getWxopen() {
        return new WxOpen($this->_config);
    }

    public function getWxa() {
        return new Wxa($this->_config);
    }

}
