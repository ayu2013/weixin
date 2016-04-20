<?php
/**
 * Created by PhpStorm.
 * User: ayu2012
 * Date: 2016/4/16
 * Time: 21:56
 */
//微信校验
if (!function_exists('Check')) {
    function Check()
    {
        //1.获取timestamp,nonce,token,signature,按字典序排序
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $signature = $_GET['signature'];
        $echostr = $_GET['echostr'];
        $token = C('WX_TOKEN');
        $array = array($timestamp, $nonce, $token);
        sort($array);
        //2.拼接排序后的数组然后用sha1加密
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
        //3.加密后的数组与signature进行对比
        if ($tmpstr == $signature && $echostr) {
            //1.第一次接入微信API接口的时候，才会生成$echostr;
            echo $echostr;
            exit();
        } else {
            return;
        }
    }
}

//封装CURL==GET
if (!function_exists('CURLGet')) {
    function CURLGet($urls)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urls);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $GetJson = curl_exec($ch);
        curl_close($ch);
        if (curl_errno($ch)) {
            dump(curl_errno($ch));
        }
        return $GetJson;
    }
}
//封装CURL==POST
if (!function_exists('CURLPost')) {
    function CURLPost($urls, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urls);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt ($ch, CURLOPT_SAFE_UPLOAD, FALSE);//PHP5.6
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $GetJson = curl_exec($ch);
        curl_close($ch);
        return $GetJson;
    }
}

//获取ACCESS_TOKEN
if (!function_exists('GetWXAccessToken')) {
    function GetWXAccessToken()
    {
        $APPId = C('WX_APPID');
        $APPSecret = C('WX_APPSECRET');
        $condition = array('appid' => $APPId, 'appsecret' => $APPSecret);
        $access_token_set = M('accesstoken')->where($condition)->find();//获取数据
        //检查是否超时，超时了重新获取
        if ($access_token_set['accessexpires'] > time()) {
            //未超时，直接返回access_token
            return $access_token_set['accesstoken'];
        } else {
            //已超时，重新获取
            $urls = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $APPId . '&secret=' . $APPSecret;
            $GetJson = CURLGet($urls);
            $arr = json_decode($GetJson, true);
            $AccessToken = $arr['access_token'];
            $AccessExpires = time() + intval($arr['expires_in']);
            $data['accesstoken'] = $AccessToken;
            $data['accessexpires'] = $AccessExpires;
            $result = M('accesstoken')->where($condition)->save($data);//更新数据
            if ($result) {
                return $AccessToken;
            } else {
                return $AccessToken;
            }
        }
    }
}

//获取微信IP地址
if (!function_exists('GetWXIPList')) {
    function GetWXIPList()
    {
        $AccessToken = GetWXAccessToken();
        $urls = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=" . $AccessToken;
        $output = CURLGet($urls);
        $arr = json_decode($output, true);
        dump($arr);
    }
}

//拉取用户列表
if (!function_exists('GetUserList')) {
    function GetUserList()
    {
        $AccessToken = GetWXAccessToken();
        // $AccessToken=session('access_token');
        $urls = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=" . $AccessToken . "&next_openid=";
        $output = CURLGet($urls);
        $arr = json_decode($output, true);
        dump($arr);
    }
}
//拉取用户基本信息，传入用户的OPENID
if (!function_exists('GetUserBase')) {
    function GetUserBase($openid)
    {
        $AccessToken = GetWXAccessToken();
        $urls = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $AccessToken . "&openid=" . $openid . "&lang=zh_CN";
        $output = CURLGet($urls);
        $arr = json_decode($output, true);
        //dump($arr);
        return "昵称：" . $arr['nickname'] . ";所在市区：" . $arr['city'];
    }
}


//创建自定义菜单
if (!function_exists('PostMenu')) {
    function PostMenu()
    {
        $AccessToken = GetWXAccessToken();
        $urls = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $AccessToken;
        $post_data = '{
                         "button":[
                         {
                              "name":"我的博客",
                              "sub_button":[
                                    {
                                         "type":"view",
                                         "name":"前端笔记",
                                         "url":"http://www.sc-www.com"
                                    }
                              ]
                         },
                         {
                               "type":"click",
                               "name":"ECHO ME",
                               "key":"introduct"
                         },
                         {
                               "name":"二级菜单",
                               "sub_button":[
                                {
                                   "type": "location_select", 
                                   "name": "发送位置",                   
                                   "key": "rselfmenu_2_0"
                                },{
                                    "type": "pic_photo_or_album", 
                                    "name": "拍照或者相册发图", 
                                    "key": "rselfmenu_1_1", 
                                    "sub_button": [ ]
                                }]
                         }]
                    }';
        $output = CURLPost($urls, $post_data);
        $arr = json_decode($output, true);
        dump($arr);
    }
}

//新增永久素材素材==除图文
if (!function_exists('AddMaterial')) {
    function AddMaterial($array)
    {
        $AccessToken = GetWXAccessToken();
        $urls = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=" . $AccessToken . "&type=image";
        $file_info = $array;
        $real_path = "{$_SERVER['DOCUMENT_ROOT']}{$file_info['filename']}";
        $post_data = array("media" => "@{$real_path}", 'form-data' => $file_info);
        $output = CURLPost($urls, $post_data);
        $arr = json_decode($output, true);
        dump($arr);
    }
}
//获取素材列表
if (!function_exists('GetMaterialList')) {
    function GetMaterialList($length)
    {
        $AccessToken = GetWXAccessToken();
        $urls = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=" . $AccessToken;
        $post_data = '{
                        "type":"image",
                        "offset":0,
                        "count":10
                      }';
        //dump($post_data);
        $output = CURLPost($urls, $post_data);
        $arr = json_decode($output, true);
        dump($arr['item']);
    }
}

//调用接口->清零，一个月10次
if(!function_exists('GoZero')){
    function GoZero(){
        $AccessToken = GetWXAccessToken();
        $urls="https://api.weixin.qq.com/cgi-bin/clear_quota?access_token=".$AccessToken;
        $post_data='{
            "appid":"'.C("WX_APPID").'";
        }';
        $output = CURLPost($urls, $post_data);
        $arr = json_decode($output, true);
        dump($arr);
    }
}
