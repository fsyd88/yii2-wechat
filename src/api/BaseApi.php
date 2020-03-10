<?php

namespace fsyd88\wechat\api;

use fsyd88\wechat\common\Base;

/**
 * Description of Base
 *
 * @author ZHAO
 */
class BaseApi extends Base {

    protected $component_appid;
    protected $component_appsecret;
    protected $component_verify_ticket;
    protected $authorizer_appid;
    protected $authorizer_refresh_token;

    public function setComponentAppid($component_appid) {
        $this->component_appid = $component_appid;
        return $this;
    }

    public function setComponentAppsecret($component_appsecret) {
        $this->component_appsecret = $component_appsecret;
        return $this;
    }

    public function setComponentVerifyTicket($component_verify_ticket) {
        $this->component_verify_ticket = $component_verify_ticket;
        return $this;
    }

    public function setAuthorizerAppid($authorizer_appid) {
        $this->authorizer_appid = $authorizer_appid;
        return $this;
    }

    public function setAuthorizerRefreshToken($authorizer_refresh_token) {
        $this->authorizer_refresh_token = $authorizer_refresh_token;
        return $this;
    }

}
