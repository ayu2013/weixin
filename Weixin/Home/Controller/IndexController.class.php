<?php
namespace Home\Controller;

use Home\Model\IndexModel;
use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        Check();
        $this->reposeMsg();
    }

    public function reposeMsg()
    {
        //1.获取微信推送的数据，xml格式
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置恢复类型和内容，把微信推送过来的XML转为字符串
        $postObj = simplexml_load_string($postArr);
        //采用$postObj->ToUserName="";获取
        //3.判断该数据包是否是订阅的事件推送
        if (strtolower($postObj->MsgType) == "event") {
            //如果是关注 subscribe(订阅)
            if (strtolower($postObj->Event) == "subscribe") {
                $Content = "感谢关注我的个人微信，最近进展如下：" . "\n" . "1、完成SDK（接入校验、关注回复、纯文本回复、多图文回复）的接入;" . "\n" . "2、TOKEN、APPID、APPSCRET等写入到配置文件中，以后直接修改配置文件就行了;" . "\n" . "3、采用CURL采集ACCESS_TOKEN，并封装为SDK。";
                $Subscribe = new IndexModel();
                $Subscribe->Subscribe($postObj, $Content);
            }
            else if(strtolower($postObj->Event) == "click"){
                switch (strtolower(trim($postObj->EventKey))) {
                    case 'introduct':
                        $Content="姓名：丁建，职业：web前端+php，年龄：29岁，个人博客：<a href='http://www.sc-www.com'>前端笔记</a>，爱好：打篮球、羽毛球，身高：165cm，体重:65KG。";
                        break;
                }
                $Subscribe = new IndexModel();
                $Subscribe->Subscribe($postObj, $Content);
            }
        }
        //自动回复
        //1==多图文
        if (strtolower($postObj->MsgType) == "text" && strtolower($postObj->Content) == "图文1") {
            $Articles = array(
                array(
                    "title" => "【今日头条】富顺一个80的老人流浪汉被拒载，高中生花钱给...",
                    "description" => "百度的描述",
                    "picurl" => "http://www.baidu.com/img/bd_logo1.png",
                    "url" => "http://www.baidu.com"
                ),
                array(
                    "title" => "你为什么不好好参加同学会，来我们好好谈谈",
                    "description" => "微博的描述",
                    "picurl" => "http://u1.img.mobile.sina.cn/public/files/image/620x300_img570b26e084465.png",
                    "url" => "http://www.weibo.com"
                ),
            );
            $autoPics = new IndexModel();
            $autoPics->autoPics($postObj, $Articles);
        } //2==纯文本
        else if (strtolower($postObj->MsgType) == "text") {
            switch (strtolower(trim($postObj->Content))) {
                case '联系方式':
                    $Content = "邮箱：139087006@qq.com";
                    break;
                case 'qq':
                    $Content = "QQ：139087006";
                    break;
                case 'email':
                    $Content = "邮箱:139087006@qq.com";
                    break;
                case '个人信息':
                    $Content = GetUserBase($postObj->FromUserName);
                    break;
                case '网址':
                    $Content = "<a href='http://www.sc-www.com'>前端笔记</a>";
                    break;
                case '笔记':
                    $Content = "1、完成SDK（接入校验、关注回复、纯文本回复、多图文回复）的接入;" . "\n" . "2、TOKEN、APPID、APPSCRET等写入到配置文件中，以后直接修改配置文件就行了;" . "\n" . "3、采用CURL采集ACCESS_TOKEN，并封装为SDK。" . date('Y-m-d H:i:s', time());
                    break;
                default:
                    $Content = "问我的联系方式请输入'联系方式',问我的个人信息请输入'个人信息',回复'笔记'可以看到最新进度";
            }
            $autoMsg = new IndexModel();
            $autoMsg->autoMsg($postObj, $Content);
        }
        else if (strtolower($postObj->MsgType) == "image") {
            $Content="您的图片我们已经收到，谢谢您参与我们的活动！";
            $Subscribe = new IndexModel();
            $Subscribe->Subscribe($postObj, $Content);
        }
    }

    //测试ACCESS_TOKEN
    public function get()
    {
        //getUserBase($openid);
        $length=$_GET['length'];
        $arr = GetMaterialList($length);
        dump($arr);
//        foreach ($arr as $key=>$value){
//            $data['media_id']=$value['media_id'];
//            $data['name']=$value['name'];
//            $data['update_time']=$value['update_time'];
//            $data['url']=$value['url'];
//            $str=M('mat')->data($data)->add();
//        }
    }

    //测试新增图片
    public  function  add(){
        $FileName="http://www.jb51.net/images/logo.gif";
        $array=array(
            'filename' => '/public/images/1.png',  //国片相对于网站根目录的路径
            'content-type' => 'image/png',  //文件类型
            'filelength' => '3610'         //图文大小
        );
        AddMaterial($array);
    }

    //清零操作
    public function GoZero(){
        GoZero();
    }

    //备注
    public function remark(){
        $OpenId=$_GET['OpenId'];
        $RemarkName=$_GET['RemarkName'];
        RemarkUser($OpenId,$RemarkName);
    }
    //用户基本信息
    public function GetUserBase(){
        $OpenId=$_GET['OpenId'];
        GetUserBase($OpenId);
    }

    //测试
    public function test(){
        $MediaId=$_GET['media_id'];
        GetMaterial($MediaId);
    }
}