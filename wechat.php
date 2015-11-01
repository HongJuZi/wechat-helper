<?php

/**
 * @version $Id$
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @description HongJuZi Framework
 * @copyright Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */


/**
 * 微信基本类
 * 
 * @author xjiujiu <xjiujiu@foxmail.com>
 * @package WeiXin
 * @since 1.0.0
 */
class Wechat 
{

    /**
     * @var protected $_appid 应用编号
     */
    protected $_appid;

    /**
     * @var protected $_secret 密钥
     */
    protected $_secret;

    /**
     * @var protected $_token 操作口令
     */
    protected $_accessToken;

    /**
     * @var protected $_token 认证令牌
     */
    protected $_token;

    /**
     * @var protected static $_urlMap  地址映射
     */
    protected static $_urlMap  = array(
        'access_token' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={appid}&secret={secret}'
    );

    /**
     * 构造函数
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param  $appid APPId
     * @param  $secret 密钥
     */
    public function __construct($appid, $secret)
    {
        $this->_appid   = $appid;
        $this->_secret  = $secret;
        $this->_token   = null;
        $this->_asscessToken    = null;
    }

    /**
     * 得到入口令
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function requestAccessToken($identifier = 'wx-access-token')
    {
        $staticCfg  = HClass::quickLoadModel('staticcfg');
        $record     = $staticCfg->getRecordByIdentifier($identifier);
        if($record) {
            $json       = json_decode(HString::decodeHtml($record['content']), true);
            if($json['end_time'] > $_SERVER['REQUEST_TIME']) {
                $this->_accessToken     = $json['access_token'];
                return $this;
            }
        }
        $json       = HRequest::getContents(strtr(
            self::$_urlMap['access_token'], 
            array('{appid}' => $this->_appid, '{secret}' => $this->_secret)
        ));
        $json       = json_decode($json, true);
        if(isset($json['errcode']) && 0 < $json['errcode']) {
            throw new HRequestException($json['errmsg']);
        }
        $json['end_time']   = time() + $json['expires_in'];
        $data       = array(
            'name' => '微信口令ACCESS-TOKEN',
            'identifier' => $identifier,
            'content' => json_encode($json),
            'author' => HSession::getAttribute('id', 'user')
        );
        if($record) {
            $staticCfg->editByWhere($data, '`id` = ' . $record['id']);
        } else {
            $staticCfg->add($data);
        }
        $this->_accessToken    = $json['access_token'];

        return $this;
    }

    /**
     * @var protected static $_shortUrl     短链接请求地址
     */
    protected static $_shortUrl     = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token={access_token}&action=long2short&long_url={url}';

    /**
     * 得到短链接[需要服务号～]
     * {@see http://mp.weixin.qq.com/wiki/10/165c9b15eddcfbd8699ac12b0bd89ae6.html}
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function getShortUrl($url)
    {
        $response       = HRequest::getContents(
            strtr(
                self::$_shortUrl,
                array(
                    '{access_token}' => $this->_accessToken,
                    '{url}' => $url
                )
            )
        );
        $json       = json_decode($response, JSON_UNESCAPED_UNICODE);
        if(0 == $json['errcode']) {
            return $json['short_url'];
        }
        throw new HRequestException('短链接生成失败，' . $response);
    }

    /**
     * 得到已经获得的
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @return {string}
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }

}

