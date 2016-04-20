<?php
/**
 * Created by PhpStorm.
 * User: ayu2012
 * Date: 2016/4/12
 * Time: 21:50
 */
namespace Home\Model;

use Think\Model;

class IndexModel /*extends Model */
{

    //订阅回复文本SDK
    public function Subscribe($postObj, $Content)
    {
        $toUser = $postObj->FromUserName;
        $FromUserName = $postObj->ToUserName;
        $time = time();
        $MsgType = "text";
        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
        //sprintf();把百分号（%）符号替换成一个作为参数进行传递的变量：
        $information = sprintf($template, $toUser, $FromUserName, $time, $MsgType, $Content);
        echo $information;
    }

    //自动回复==纯文本
    public function autoMsg($postObj, $Content)
    {
        $toUser = $postObj->FromUserName;
        $FromUserName = $postObj->ToUserName;
        $time = time();
        $MsgType = "text";
        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
        $information = sprintf($template, $toUser, $FromUserName, $time, $MsgType, $Content);
        echo $information;
    }

    //自动回复==多图文
    public function autoPics($postObj, $Articles)
    {
        $toUser = $postObj->FromUserName;
        $FromUserName = $postObj->ToUserName;
        $time = time();
        $MsgType = "news";
        //$Content="感谢关注我的个人微信".$FromUserName;
        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>" . count($Articles) . "</ArticleCount>
                            <Articles>";
        foreach ($Articles as $key => $value) {
            $template .= "
                            <item>
                            <Title><![CDATA[" . $value['title'] . "]]></Title>
                            <Description><![CDATA[" . $value['description'] . "]]></Description>
                            <PicUrl><![CDATA[" . $value['picurl'] . "]]></PicUrl>
                            <Url><![CDATA[" . $value['url'] . "]]></Url>
                            </item>";
        }
        $template .= " 
                            </Articles>
                            </xml>";
        $infor = sprintf($template, $toUser, $FromUserName, $time, $MsgType);
        echo $infor;
    }

    

}