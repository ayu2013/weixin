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
            } else if (strtolower($postObj->Event) == "click") {
                switch (strtolower(trim($postObj->EventKey))) {
                    case 'introduct':
                        $Content = "姓名：丁建，职业：web前端+php，年龄：29岁，个人博客：<a href='http://www.sc-www.com'>前端笔记</a>，爱好：打篮球、羽毛球，身高：165cm，体重:65KG。";
                        $Subscribe = new IndexModel();
                        $Subscribe->Subscribe($postObj, $Content);
                        break;
                }
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
        } else if (strtolower($postObj->MsgType) == "image") {
            $Content = "您的图片我们已经收到，谢谢您参与我们的活动！";
            $Subscribe = new IndexModel();
            $Subscribe->Subscribe($postObj, $Content);
        }
    }

    //测试获取永久素材列表
    public function get()
    {
        //getUserBase($openid);
        $length = $_GET['length'];
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

    //测试群发（图片、图文）
    public function test()
    {
        $MsgType = $_GET['msgtype'];
        switch ($MsgType) {
            case 'image'://图片
                $MsgArr = '{
                   "touser":[
                    "oeFOQuGa9tgcekN4WLIV53iuR9Hg",
                    "oeFOQuAodWuU6KkReh9MEM3Ca7aE",
                    "oeFOQuE9GrZzZByf8M_W4_0GPhT4"
                   ],
                   "image":{
                          "media_id":"8C9az4AFxe_u7-bs3e_vNNdlcXWes0WicvtD9wnmSgI"
                          },
                   "msgtype":"image"
                }';
                OpenIdSend($MsgArr);
                break;
            case 'text'://文本
                $MsgArr = '{
                   "touser":[
                    "oeFOQuGa9tgcekN4WLIV53iuR9Hg",
                    "oeFOQuAodWuU6KkReh9MEM3Ca7aE",
                    "oeFOQuE9GrZzZByf8M_W4_0GPhT4"
                   ],
                   "text":{
                          "content":"今天是一个好天气"
                          },
                   "msgtype":"text"
                }';
                OpenIdSend($MsgArr);
                break;
            case 'mpnews'://图文消息
                $MsgArr = '{
                   "touser":[
                    "oeFOQuGa9tgcekN4WLIV53iuR9Hg",
                    "oeFOQuAodWuU6KkReh9MEM3Ca7aE",
                    "oeFOQuE9GrZzZByf8M_W4_0GPhT4"
                   ],
                    "mpnews":{
                      "media_id":"8C9az4AFxe_u7-bs3e_vNBIz5eSsSCYTb1DrValuNUE"
                   },
                    "msgtype":"mpnews"
                }';
                OpenIdSend($MsgArr);
                break;
            default:
                $MsgArr = '{
                   
                }';
                OpenIdSend($MsgArr);
        }
    }

    //测试新增永久素材
    public function add()
    {
        $MaterialType=$_GET['MaterialType'];//获取素材类型,调用不同的数据
        if(strtolower($MaterialType)=="image"){
            $PostData = array(
                'filename' => '/public/images/2.jpg',  //国片相对于网站根目录的路径
                'content-type' => 'image/jpeg',  //文件类型
                'filelength' => '331000'         //图文大小
            );
            AddMaterial($PostData,$MaterialType);
        }
        else if(strtolower($MaterialType)=="mpnews"){
            $PostData = '{
                 "articles": [
                      {
                           "title": "今天是一个好天气",
                           "thumb_media_id": "8C9az4AFxe_u7-bs3e_vNI1Xq04VblMqBqYCSo0yEbQ",
                           "author": "小叮当",
                           "digest": "送你一张百度的LOGO吧",
                           "show_cover_pic": 1,
                           "content": "今天是一个好天气，从你一张百度的LOGO吧！",
                           "content_source_url": "http://www.baidu.com"
                      },
                      {
                           "title": "今天我要发大财",
                           "thumb_media_id": "8C9az4AFxe_u7-bs3e_vNG98Dfcrym49HQBK0zm6MZc",
                           "author": "小叮当",
                           "digest": "如果我发财了，送各位一万元",
                           "show_cover_pic": 1,
                           "content": "今天我要发大财，如果我发财了，送各位一万元！",
                           "content_source_url": "http://www.sc-www.com"
                      },
                 ]
            }';
            AddMaterial($PostData,$MaterialType);
        }
    }

    //清零操作
    public function GoZero()
    {
        GoZero();
    }

    //修改用户备注
    public function remark()
    {
        $OpenId = $_GET['OpenId'];
        $RemarkName = $_GET['RemarkName'];
        RemarkUser($OpenId, $RemarkName);
    }

    //获取用户基本信息
    public function GetUserBase()
    {
        $OpenId = $_GET['OpenId'];
        GetUserBase($OpenId);
    }

//    //测试
//    public function test(){
//        $MediaId=$_GET['media_id'];
//        GetMaterialList($MediaId);
//    }
}