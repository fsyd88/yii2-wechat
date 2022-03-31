<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\api;

use GuzzleHttp\Utils;

/**
 * Description of WxOpen
 *
 * @author ZHAO
 */
class WxOpen extends BaseApi
{

    /**
     * 获取基本信息
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/Mini_Program_Basic_Info/Mini_Program_Information_Settings.html
     */
    public function getAccountBasicInfo()
    {
        return $this->httpGet('cgi-bin/account/getaccountbasicinfo');
    }

    /**
     * 获取可以设置的所有类
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/category/getallcategories.html
     */
    public function getAllCategories()
    {
        return $this->httpGet('cgi-bin/wxopen/getallcategories');
    }

    /**
     * 获取已设置的所有类目
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/category/getcategory.html
     */
    public function getCategory()
    {
        return $this->httpGet('cgi-bin/wxopen/getcategory');
    }

    /**
     * 添加类目
     * @param array $categories
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/category/addcategory.html
     */
    public function addCategory($categories)
    {
        return $this->post('cgi-bin/wxopen/addcategory', [
            'categories' => $categories,
        ]);
    }

    /**
     * 删除类目
     * @param type $first 一级类目 ID
     * @param type $second 二级类目 ID
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/category/deletecategory.html
     */
    public function deleteCategory($first, $second)
    {
        return $this->post('cgi-bin/wxopen/addcategory', ['first' => $first, 'second' => $second]);
    }

    /**
     * 查询当前设置的最低基础库版本及各版本用户占比
     * 调用本接口可以查询小程序当前设置的最低基础库版本，以及小程序在各个基础库版本的用户占比
     * @return type
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/getweappsupportversion.html
     */
    public function getWeappSupportVersion()
    {
        return $this->post('cgi-bin/wxopen/getweappsupportversion', []);
    }

    /**
     * 设置最低基础库版本
     * 调用本接口可以设置小程序的最低基础库支持版本，可以先查询当前小程序在各个基础库的用户占比来辅助进行决策
     * @param string $version 为已发布的基础库版本号
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/code/setweappsupportversion.html
     */
    public function setWeappSupportVersion($version)
    {
        return $this->post('cgi-bin/wxopen/setweappsupportversion', ['version' => $version]);
    }

    /**
     * 获取小程序二维码  数量限制 10w
     * @param $path
     * @param int $width
     * @return array
     *
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/qrcode/createwxaqrcode.html
     */
    public function createWxaQrcode($path, $width = 430)
    {
        $data = [
            'path' => $path,
            'width' => $width
        ];
        return $this->post('cgi-bin/wxaapp/createwxaqrcode', $data);
    }

    /**
     * 配置小程序用户隐私保护
     * @param $owner_setting
     * @param $setting_list
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/privacy_config/set_privacy_setting.html
     */
    public function setPrivacySetting($owner_setting, $setting_list)
    {
        $data = [
            'owner_setting' => $owner_setting,
            'setting_list' => $setting_list,
        ];
        $postData = [
            'body' => Utils::jsonEncode($data, JSON_UNESCAPED_UNICODE),
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ];
        return $this->post('cgi-bin/component/setprivacysetting', $postData, [], true);
    }

    /**
     * 查询小程序用户隐私保护
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/privacy_config/get_privacy_setting.html
     */
    public function getPrivacySetting()
    {
        return $this->post('cgi-bin/component/getprivacysetting', (object)[]);
    }
}
