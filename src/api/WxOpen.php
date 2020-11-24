<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fsyd88\wechat\api;

/**
 * Description of WxOpen
 *
 * @author ZHAO
 */
class WxOpen extends WxBase
{

    /**
     * 获取基本信息
     */
    public function getAccountBasicInfo()
    {
        return $this->get('cgi-bin/account/getaccountbasicinfo');
    }

    /**
     * 获取可以设置的所有类
     */
    public function getAllCategories()
    {
        return $this->get('cgi-bin/wxopen/getallcategories');
    }

    /**
     * 获取已设置的所有类目
     * @return type
     */
    public function getCategory()
    {
        return $this->get('cgi-bin/wxopen/getcategory');
    }

    /**
     * 添加类目
     * @param array $categories
     */
    public function addCategory(array $categories)
    {
        return $this->post('cgi-bin/wxopen/addcategory', $categories);
    }

    /**
     * 删除类目
     * @param type $first 一级类目 ID
     * @param type $second 二级类目 ID
     * @return type
     */
    public function deleteCategory($first, $second)
    {
        return $this->post('cgi-bin/wxopen/addcategory', ['first' => $first, 'second' => $second]);
    }

    /**
     * 查询当前设置的最低基础库版本及各版本用户占比
     * 调用本接口可以查询小程序当前设置的最低基础库版本，以及小程序在各个基础库版本的用户占比
     * @return type
     */
    public function getWeappSupportVersion()
    {
        return $this->post('cgi-bin/wxopen/getweappsupportversion', []);
    }

    /**
     * 设置最低基础库版本
     * 调用本接口可以设置小程序的最低基础库支持版本，可以先查询当前小程序在各个基础库的用户占比来辅助进行决策
     * @param string $version 为已发布的基础库版本号
     */
    public function setWeappSupportVersion($version)
    {
        return $this->post('cgi-bin/wxopen/setweappsupportversion', ['version' => $version]);
    }

    public function createWxaQrcode($path, $width = 430)
    {
        $data = [
            'path' => $path,
            'width' => $width
        ];
        return $this->post('cgi-bin/wxaapp/createwxaqrcode', $data);
    }
}
