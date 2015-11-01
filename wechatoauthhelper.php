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
 * 微信登陆帮助类
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package weixin
 * @since 1.0.0
 */
class WechatOAuthHelper extends Wechat
{

    /**
     * @var private static $_scopeUrl   权限URLMap
     */
    private static $_scopeUrl   = array(
        'base' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s#wechat_redirect',
        'userinfo' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=%s#wechat_redirect'
    );

    /**
     * 基本权限认证
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param $url 回调地址
     * @param  $state 状态
     * @return String
     */
    public function getOAuthUrlByBase($url, $state = '')
    {
        return sprintf(self::$_scopeUrl['base'], $this->_appid, urlencode($url), $state);
    }

    /**
     * @var private static $_tokenUrl   令牌获取URL
     */
    private static $_tokenUrl   = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code'; 

    /**
     * 得到令牌
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param  $code 回调后的$code参数
     * @return String 
     */
    public function getToken($code)
    {
        $url    = sprintf(self::$_tokenUrl, $this->_appid, $this->_secret, $code);
        $json   = json_decode(HRequest::getContents($url), true);

        if(isset($json['errcode'])) {
            throw new HVerifyException($json['errmsg']);
        }

        return $json;
    }

    /**
     * 得到用户信息获取接口认证
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param $url 回调链接地址
     * @param  $state 状态
     * @return String
     */
    public function getOAuthByUserInfo($url, $state)
    {
        return sprintf(self::$_scopeUrl['userinfo'], $this->_appid, urlencode($url), $state);
    }

    /**
     * @var private static $_verifyTokenUrl     验证Token是否有效URL
     */
    private static $_verifyTokenUrl     = 'https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s';

    /**
     * 验证Token是否正常
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param $token 需要验证的Tooken
     * @return Boolean
     */
    public function verifyToken($token)
    {
        $url    = sprintf(self::$_verifyTokenUrl, $this->_appid, $token);
        $json   = json_decode(HRequest::getContents($url), true);
        if($json['errcode'] > 0) {
            throw new HVerifyException($json['errmsg']);
        }
    }

    /**
     * @var private static $_refreshTokenUrl    刷新得到新的$token
     */
    private static $_refreshTokenUrl    = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';

    /**
     * 刷新得到新的Token
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param $token 需要刷新的Tooken
     * @return array
     *   {
     *       "access_token":"ACCESS_TOKEN",
     *           "expires_in":7200,
     *           "refresh_token":"REFRESH_TOKEN",
     *           "openid":"OPENID",
     *           "scope":"SCOPE"
     *   }
     */
    public function refreshToken($token)
    {
        $url    = sprintf(self::$_refreshTokenUrl, $this->_appid, $token);
        $json   = json_decode(HRequest::getContents($url), true);
        if(isset($json['errcode'])) {
            throw new HVerifyException($json['errmsg']);
        }

        return $json;
    }

}
