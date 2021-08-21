<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat;

/**
 * 原始小程序api
 *
 * @author ZHAO
 */
class MiniProgram extends common\Base
{

    protected $appid;
    protected $secret;
    protected $access_token;

    public function init($config = array())
    {
        $this->access_token = $this->getAccessToken();
    }

    /**
     * POST 请求
     * @param string $uri api 地址
     * @param array $data post 数据
     * @return array $response
     */
    public function post($uri, $data)
    {
        $get_uri = $this->buildGetUri($uri, ['access_token' => $this->access_token]);
        return $this->request('POST', $get_uri, $data);
    }

    public function get($uri, $data = [])
    {
        $data['access_token'] = $this->access_token;
        return $this->request('GET', $uri, $data);
    }

    public function getAccessToken()
    {
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
     * @param string $path 扫码进入的小程序页面路径，最大长度 128 字节，不能为空；
     * @param number $width
     */
    public function createQRCode($path, $width = 430)
    {
        return $this->post('cgi-bin/wxaapp/createwxaqrcode', ['path' => $path, 'width' => $width]);
    }

    /**
     * 获取小程序码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * @param string $path 小程序页面
     * @param int $width 消息码宽度
     * @return array
     */
    public function getWxaCode($path, $width = 430)
    {
        return $this->post('wxa/getwxacode', ['path' => $path, 'width' => $width]);
    }

    /**
     * 获取小程序码，适用于需要的码数量极多的业务场景
     * @param string $scene
     * @param string $page
     * @param number $width
     * @param boolean $auto_color
     * @param Object $line_color
     * @param boolean $is_hyaline
     * @return type
     */
    public function getUnlimited($scene, $page = null, $width = null, $auto_color = null, $line_color = null, $is_hyaline = null)
    {
        $params = ['scene' => $scene, 'page' => $page, 'width' => $width,
            'auto_color' => $auto_color, 'line_color' => $line_color, 'is_hyaline' => $is_hyaline];
        foreach ($params as $k => $v) {
            if ($v === null) {
                unset($params[$k]);
            }
        }
        return $this->post('wxa/getwxacodeunlimit', $params);
    }

    /**
     * 发送订阅消息
     * @param $data
     *  touser    string        是    接收者（用户）的 openid
     * template_id    string    是    所需下发的订阅模板id
     * page    string        否    点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
     * data    Object        是    模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
     * @return array
     */
    public function sendTplMsg($data)
    {
        return $this->post('cgi-bin/message/subscribe/send', $data);
    }

    /**
     * 获取订阅消息模板
     * @return mixed|string
     */
    public function getTplList()
    {
        return $this->get('wxaapi/newtmpl/gettemplate');
    }

    /**
     * 获取订阅消息 分类
     * @return mixed|string
     */
    public function getTplCategory()
    {
        return $this->get('wxaapi/newtmpl/getcategory');
    }

    /**
     * 获取订阅消息标题列表
     * @param $ids  类目 id，多个用逗号隔开
     * @param int $start 用于分页，表示从 start 开始。从 0 开始计数。
     * @param int $limit 用于分页，表示拉取 limit 条记录。最大为 30
     * @return mixed|string
     */
    public function getTplTitleList($ids, $start = 0, $limit = 20)
    {
        return $this->get('wxaapi/newtmpl/getpubtemplatetitles', [
            'ids' => $ids,
            'start' => $start,
            'limit' => $limit
        ]);
    }

    /**
     * 添加订阅消息模板
     * @param $data
     * tid    string        是    模板标题 id，可通过接口获取，也可登录小程序后台查看获取
     * kidList    Array.<number>        是    开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如 [3,5,4] 或 [4,5,3]），最多支持5个，最少2个关键词组合
     * sceneDesc    string        否    服务场景描述，15个字以内
     * @return mixed|string
     */
    public function addTpl($data)
    {
        return $this->post('wxaapi/newtmpl/addtemplate', $data);
    }

    /**
     * 删除订阅消息
     * @param $priTmplId  消息模板ID
     * @return array
     */
    public function deleteTpl($priTmplId)
    {
        return $this->post('wxaapi/newtmpl/deltemplate', [
            'priTmplId' => $priTmplId
        ]);
    }

    /**
     * 获取小程序 Short Link，适用于微信内拉起小程序的业务场景
     * @param string $path 通过 Short Link 进入的小程序页面路径，必须是已经发布的小程序存在的页面，可携带 query，最大1024个字符
     * @param string $title 页面标题
     * @param bool $is_permanent 短期有效：false，永久有效：true
     * @return array
     */
    public function getSortLink($path, $title, $is_permanent = false)
    {
        return $this->post('wxa/genwxashortlink', [
            'page_url' => $path,
            'page_title' => $title
        ]);
    }

    /**
     * 获取小程序 scheme 码，适用于短信、邮件、外部网页、微信内等拉起小程序的业务场景
     * @param array $jump_wxa path:小程序路径，query 小程序参数
     * @param bool $is_expire 到期失效：true，永久有效：false
     * @param integer $expire_type 到期失效的 scheme 码失效类型，失效时间：0，失效间隔天数：1
     * @param integer $expire_time 到期失效的 scheme 码的失效时间，为 Unix 时间戳。生成的到期失效 scheme 码在该时间前有效。最长有效期为1年。is_expire 为 true 且 expire_type 为 0 时必填
     * @param integer $expire_interval 到期失效的 scheme 码的失效间隔天数。生成的到期失效 scheme 码在该间隔时间到达前有效。最长间隔天数为365天。is_expire 为 true 且 expire_type 为 1 时必填
     */
    public function getUrlScheme($jump_wxa, $is_expire = false, $expire_type = 0, $expire_time = null, $expire_interval = null)
    {
        return $this->post('wxa/generatescheme', [
            'jump_wxa' => $jump_wxa,
            'is_expire' => $is_expire,
            'expire_type' => $expire_type,
            'expire_time' => $expire_time,
            'expire_interval' => $expire_interval,
        ]);
    }

    /**
     * 获取小程序 URL Link，适用于短信、邮件、网页、微信内等拉起小程序的业务场景
     * @param string $path 小程序页面路径
     * @param string $query 小程序的query
     * @param bool $is_expire 到期失效：true，永久有效：false
     * @param int $expire_type 失效时间：0，失效间隔天数：1
     * @param int $expire_time Unix 时间戳 ,最长有效期为1年。expire_type 为 0 必填
     * @param int $expire_interval 最长间隔天数为365天。expire_type 为 1 必填
     */
    public function getUrlLink($path, $query, $is_expire = false, $expire_type = 0, $expire_time = null, $expire_interval = null)
    {
        $data = $this->post('wxa/generate_urllink', [
            'path' => $path,
            'query' => $query,
            'is_expire' => $is_expire,
            'expire_type' => $expire_type,
            'expire_time' => $expire_time,
            'expire_interval' => $expire_interval,
        ]);
    }
}
