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
class WechatReplyHelper extends Wechat
{

    /**
     * @var private static $_msgTplMap  消息回复模板
     */
    private static $_msgTplMap  = array(
        'text' => '<xml><ToUserName><![CDATA[{to}]]></ToUserName>'
        . '<FromUserName><![CDATA[{from}]]></FromUserName>'
        . '<CreateTime>{t}</CreateTime>'
        . '<MsgType><![CDATA[text]]></MsgType>'
        . '<Content><![CDATA[{content}]]></Content>'
        . '</xml>',
        'image' => '<xml>'
        . '<ToUserName><![CDATA[{to}]]></ToUserName>'
        . '<FromUserName><![CDATA[{from}]]></FromUserName>'
        . '<CreateTime>{t}</CreateTime>'
        . '<MsgType><![CDATA[image]]></MsgType>'
        . '<Image><MediaId><![CDATA[{id}]]></MediaId></Image>'
        . '</xml>',
        'voice' => '<xml>'
        . '<ToUserName><![CDATA[{to}]]></ToUserName>'
        . '<FromUserName><![CDATA[{from}]]></FromUserName>'
        . '<CreateTime>{t}</CreateTime>'
        . '<MsgType><![CDATA[voice]]></MsgType>'
        . '<Voice><MediaId><![CDATA[{id}]]></MediaId></Voice>'
        . '</xml>',
        'video' => '<xml>'
        . '<ToUserName><![CDATA[{to}]]></ToUserName>'
        . '<FromUserName><![CDATA[{from}]]></FromUserName>'
        . '<CreateTime>{t}</CreateTime>'
        . '<MsgType><![CDATA[video]]></MsgType>'
        . '<Video>'
        . '<MediaId><![CDATA[{id}]]></MediaId>'
        . '<Title><![CDATA[{title}]]></Title>'
        . '<Description><![CDATA[{desc}]]></Description>'
        . '</Video>'
        . '</xml>',
        'music' => '<xml>'
        . '<ToUserName><![CDATA[{to}]]></ToUserName>'
        . '<FromUserName><![CDATA[{from}]]></FromUserName>'
        . '<CreateTime>{t}</CreateTime>'
        . '<MsgType><![CDATA[music]]></MsgType>'
        . '<Music>'
        . '<Title><![CDATA[{title}]]></Title>'
        . '<Description><![CDATA[{desc}]]></Description>'
        . '<MusicUrl><![CDATA[{url}]]></MusicUrl>'
        . '<HQMusicUrl><![CDATA[{h_url}]]></HQMusicUrl>'
        . '<ThumbMediaId><![CDATA[{id}]]></ThumbMediaId>'
        . '</Music>'
        . '</xml>',
        'news' => '<xml>'
        . '<ToUserName><![CDATA[{to}]]></ToUserName>'
        . '<FromUserName><![CDATA[{from}]]></FromUserName>'
        . '<CreateTime>{t}</CreateTime>'
        . '<MsgType><![CDATA[news]]></MsgType>'
        . '<ArticleCount>{total}</ArticleCount>'
        . '<Articles>{items}</Articles>'
        . '</xml>',
        'item' => '<item>'
        . '<Title><![CDATA[{title}]]></Title> '
        . '<Description><![CDATA[{desc}]]></Description>'
        . '<PicUrl><![CDATA[{img}]]></PicUrl>'
        . '<Url><![CDATA[{url}]]></Url>'
        . '</item>'
    );

    /**
     * 得到文本发送模板
     *
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public static
     * @param $data 需要发送的数据
     * 格式：
     * array('to' => 'xxxx', 'from' => 'xxxx', 'content' => 'xxxx')
     * @return {String}
     */
    public static function getTextTpl($data)
    {
        $data['t']  = !$_SERVER['REQUEST_TIME'] ? time() : $_SERVER['REQUEST_TIME'];

        return strtr(self::$_msgTplMap['text'], self::_formatToStrtrArray($data));
    }

    /**
     * 得到图片消息模板
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public static
     * @param $data 发送数据
     * 格式：
     * array('to' => 'xxxx', 'from' => 'xxxx', 'id' => 'xxx')
     * @return {string}
     */
    public static function getImageTpl($data)
    {
        $data['t']  = !$_SERVER['REQUEST_TIME'] ? time() : $_SERVER['REQUEST_TIME'];

        return strtr(self::$_msgTplMap['image'], self::_formatToStrtrArray($data));
    }

    /**
     * 得到声音发送信息数据
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public static
     * @param $data 需要发送的数据
     * 格式：
     * array('to' => 'xxxx', 'from' => 'xxxx', 'id' => 'xxx')
     * @return {String}
     */
    public static function getVoiceTpl($data)
    {
        $data['t']  = !$_SERVER['REQUEST_TIME'] ? time() : $_SERVER['REQUEST_TIME'];

        return strtr(self::$_msgTplMap['voice'], self::_formatToStrtrArray($data));
    }

    /**
     * 得到音乐发送信息数据
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public static
     * @param $data 需要发送的数据
     * 格式：
     * array('to', 'from', 'title' => 'xxxx', 'desc' => 'xxxx', 'id' => 'xxx', 'url' => 'xxxx', 'h_url' => 'xxxx')
     * @return {String}
     */
    public static function getMusicTpl($data)
    {
        $data['t']  = !$_SERVER['REQUEST_TIME'] ? time() : $_SERVER['REQUEST_TIME'];

        return strtr(self::$_msgTplMap['music'], self::_formatToStrtrArray($data));
    }

    /**
     * 得到需要发送的图文模板
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public static
     * @param $data 基础数据
     * 格式：
     * array('to' => 'xxxx', 'from' => 'xxxx')
     * @param  $list 新闻列表
     * 格式：
     * array(array('title' => 'xxxx', 'desc' => 'xxxx', 'img' => 'xxx', 'url' => 'xxxx'), ....)
     * @return {String}
     */
    public static function getNewsTpl($data, $list)
    {
        $data['items']      = '';
        foreach($list as $item) {
            $data['items']  .= strtr(self::$_msgTplMap['item'], self::_formatToStrtrArray($item));
        }
        $data['total']  = count($list);
        $data['t']      = !$_SERVER['REQUEST_TIME'] ? time() : $_SERVER['REQUEST_TIME'];

        return strtr(self::$_msgTplMap['video'], self::_formatToStrtrArray($data));
    }

    /**
     * 得到视频发送信息数据
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public static
     * @param $data 需要发送的数据
     * 格式：
     * array('to' => 'xxxx', 'from' => 'xxxx', 'id' => 'xxx')
     * @return {String}
     */
    public static function getVideoTpl($data)
    {
        $data['t']  = !$_SERVER['REQUEST_TIME'] ? time() : $_SERVER['REQUEST_TIME'];

        return strtr(self::$_msgTplMap['video'], self::_formatToStrtrArray($data));
    }

    /**
     * 给Key上加上{}外包
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private static
     * @param $data 数据
     * @return {Array}
     */
    private static function _formatToStrtrArray($data)
    {
        $new    = array();
        foreach($data as $key => $item) {
            $new['{' . $key . '}']     = $item;
        }
        unset($data);

        return $new;
    }

}
