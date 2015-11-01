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
 * 微信用户帮助类
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package weixin
 * @since 1.0.0
 */
class WechatUserHelper extends Wechat
{

    /**
     * @var private static $_userInfoUrl    用户基本信息获取URL
     */
    private static $_userInfoUrl    = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=%s';

    /**
     * 获取用户基本信息
     *
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param  $token 令牌
     * @return Array
     */
    public function getUserInfo($token, $lang = 'zh_CN')
    {
        $url    = sprintf(self::$_userInfoUrl, $token, $this->_openid, $lang);
        $json   = json_decode(HRequest::getContents($url), true);
        if($json['errcode']) {
            throw new HVerifyException($json['errmsg']);
        }

        return $json;
    }

    /**
     * @var private static $_infoUrl        用户信息接口
     */
    private static $_infoUrl        = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=%s';

    /**
     * 获取用户基本信息
     *
     * {@see http://mp.weixin.qq.com/wiki/14/bb5031008f1494a59c6f71fa0f319c66.html}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param  $openid 令牌
     * @return Array
     */
    public function getInfo($openid, $lang = 'zh_CN')
    {
        $url    = sprintf(self::$_infoUrl, $this->_accessToken, $openid, $lang);
        $json   = json_decode(HRequest::getContents($url), true);
        if($json['errcode']) {
            throw new HVerifyException($json['errmsg']);
        }

        return $json;
    }

    /**
     * @var private static $_listUrl    用户列表请求URL
     */
    private static $_listUrl    = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token={access_token}&next_openid={next_openid}';

    /**
     * 得到用户列表
     *
     * {@see http://mp.weixin.qq.com/wiki/0/d0e07720fc711c02a3eab6ec33054804.html}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param $nextOpenId = '' 下一个OPENID用户编号
     * @return Array {"total":2,"count":2,"data":{"openid":["","OPENID1","OPENID2"]},"next_openid":"NEXT_OPENID"} 
     */
    public function getList($nextOpenId = '')
    {
        $json   = HRequest::getContents(strtr(self::$_listUrl, array('{access_token}' => $this->_accessToken, '{next_openid}' => $nextOpenId)));
        $json   = json_decode($json, true);
        if(isset($json['errcode']) && 0 < $json['errcode']) {
            throw new HVerifyException('获取用户列表失败，错误信息（' . $json['errcode'] . ':' . $json['errmsg'] . '）');
        }

        return $json;
    }

}
