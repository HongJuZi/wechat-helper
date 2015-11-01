<?php 

/**
 * @version $Id$
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @description HongJuZi Framework
 * @copyright Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

HClass::import('vendor.sdk.weixin.wechat');

/**
 * 微信验证帮助类
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package weixin
 * @since 1.0.0
 */
class WechatMenuHelper extends Wechat
{

    /**
     * @var private static $_createUrl 创建菜单URL
     */
    private static $_createUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token={access_token}';

    /**
     * 创建菜单
     * 
     * {@see http://mp.weixin.qq.com/wiki/13/43de8269be54a0a6f64413e4dfa94f39.html }
     *
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param  {String} JSON字符串
     */
    public function create($menuList)
    {
        $json   = HRequest::post(str_replace('{access_token}', $this->_accessToken, self::$_createUrl), $menuList);
        $json   = json_decode($json, true);
        if(0 < $json['errcode']) {
            throw new HRequestException('创建失败，错误信息：' . $json['errcode'] . ':' . $json['errmessage']);
        }
    }

    /**
     * @var private static $_deleteUrl  删除URL
     */
    private static $_deleteUrl  = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={access_token}';

    /**
     * 删除菜单
     *
     * {@see https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=ACCESS_TOKEN}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function delete()
    {
        $json   = HRequest::getContents(str_replace('{access_token}', $this->_accessToken, self::$_deleteUrl));
        $json   = json_decode($json, true);
        if(0 < $json['errcode']) {
            throw new HRequestException('删除菜单失败！错误信息：' . $json['errcode'] . ':' . $json['errmsg']);
        }
    }

    /**
     * @var private static $_cfgUrl 配置URL
     */
    private static $_cfgUrl     = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token={access_token}'; 
    /**
     * 得到菜单配置
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function getMenuCfg()
    {
        $json   = HRequest::getContents(str_replace('{access_token}', $this->_accessToken, self::$_cfgUrl));

        return json_decode($json, true);
    }

    /**
     * @var private static $_queryUrl   查询接口
     */
    private static $_queryUrl   = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token={access_token}';

    /**
     * 菜单查询接口
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function query()
    {
        $json   = HRequest::getContents(str_replace('{access_token}', $this->_accessToken, self::$_queryUrl));
        $json   = json_decode($json, true);
        if(0 < $json['errcode']) {
            throw new HVerifyException('查询失败！' . $json['errcode'] . ':' . $json['errmsg']);
        }

        return $json;
    }

}
