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
class WechatCheckHelper extends Wechat
{

    /**
     * 构造函数
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @param $token 口令定义
     */
    public function __construct($token)
    {
        $this->_token   = $token;
    }

    /**
     * 验证是否有效
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    /**
     * 响应消息
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (empty($postStr)){
            echo "";
            exit;
        }
        /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
           the best way is to check the validity of xml by yourself */
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             
        if(empty( $keyword )) {
            echo "Input something...";
            return;
        }
        $msgType = "text";
        $contentStr = "Welcome to wechat world!";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        echo $resultStr;
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!$this->_token) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$tmpArr = array($this->_token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}
        return false;
    }

}
