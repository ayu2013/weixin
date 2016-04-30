
## 说明

文件基于ThinkPHP3.2.3，不懂的可以去官网查看一下，或者在网上搜索教程看一下！
配置文件在Weixin/Home/Conf里面，接口放在weixin/Home/Common 的function.php里面,关注的过少，不再更新！！！！

## 已有接口

目前已经开发了的接口，后期会陆续更新：

*   1、Check() 微信校验接口；
*   2、CURLGet()以及CURLPost() 封装CURL==GET 以及CURLPost()；
*   3、GetWXAccessToken() 获取ACCESS_TOKEN并写入数据库；
*   4、GetWXIPList() 获取微信Ip地址库；
*   5、GetUserList() 拉取用户列表；
*   6、GetUserBase() 用户基本信息；
*   7、PostMenu() 创建自定义菜单；
*   8、GoZero() 调用清零接口（一个月只有10次哦）；
*   9、RemarkUser() 设置用户别名，传入用户ID和别名既可；
*   10、ShakeAround()申请开通微信摇一摇，未测试，测试号没的权限；
*   11、OpenIdSend($MsgArr) 根据openID群发信息（传入群发信息的数组，实现群发功能，目前测试了群发图片、文本）。

      01、根据需要群发的素材类型，实现群发图文，bingo!

## 素材管理--较多就单独拿出来了

*   1、AddMedia() 增加临时素材
*   2、GetMedia() 获取临时素材
*   3、AddMaterial() 新增永久素材(图文以及图片)
*   4、ModifyMaterial()  修改图文素材---注意只能修改图文素材
*   5、GetMaterial() 获取永久素材
*   6、DelMaterial() 删除永久素材
*   7、GetMaterialCount() 获取永久素材总数
*   8、GetMaterialList() 获取永久素材列表

## 数据库

数据库采用的mysql，主要涉及1张表，存储微信发送我们的access_token。可以看GetWXAccessToken()这个接口，自行去搭建数据库吧！

## 注意事项：

本测试采用的微信公众号测试帐号来验证的，因为个人订阅号的功能不完善！

## 联系方式

Email:492775622@qq.com，只接收邮件，请勿添加QQ，谢谢理解！

## 测试二维码
![image](https://github.com/ayu2013/weixin/raw/master/Public/images/erweima.jpg?raw=true)

## 请我喝杯咖啡吧！
![image](https://github.com/ayu2013/weixin/raw/master/Public/images/alipay.jpg?raw=true)
