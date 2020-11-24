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
class Open extends \yii\base\BaseObject
{

    private $_config;
    private static $_component;
    private static $_newtemp;
    private static $_wxopen;
    private static $_wxa;

    /**
     * wechat open
     * @param array $config [component_appid=>'',component_appsecret=>'',component_verify_ticket=>'',authorizer_appid=>'',authorizer_refresh_token=>'']
     */
    public function __construct(array $config)
    {
        $this->_config = $config;
        if (!$config['component_appid'] || !$config['component_appsecret']) {
            throw new \Exception('component_appid and component_appsecret can not be null');
        }
    }

    public function getComponent()
    {
        if (!self::$_component) {
            self::$_component = new Component($this->_config);
        }
        return self::$_component;
    }

    public function getNewtemp()
    {
        if (!self::$_newtemp) {
            self::$_newtemp = new NewTemp($this->_config);
        }
        return self::$_newtemp;
    }

    public function getWxopen()
    {
        if (!self::$_wxopen) {
            self::$_wxopen = new WxOpen($this->_config);
        }
        return self::$_wxopen;
    }

    public function getWxa()
    {
        if (!self::$_wxa) {
            self::$_wxa = new Wxa($this->_config);
        }
        return self::$_wxa;
    }

}
