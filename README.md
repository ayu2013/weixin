
## 说明

文件基于ThinkPHP3.2.3，不懂的可以去官网查看一下，或者在网上搜索教程看一下！
配置文件在Weixin/Home/Conf里面，接口放在weixin/Home/Common 的function.php里面,后期会陆续更新！

## 已有接口

目前以及开发了验证接口

*  1、Check() 微信校验接口
*  2、CURLGet()以及CURLPost() 封装CURL==GET 以及CURLPost()
*  3、GetWXAccessToken() 获取ACCESS_TOKEN并写入数据库
*  4、GetWXIPList() 获取微信Ip地址库
*  5、GetUserList() 拉取用户列表
*  6、GetUserBase() 用户基本信心
*  7、PostMenu() 创建自定义菜单
*  8、AddMaterial() 新增永久多媒体素材
*  9、GetMaterialList() 获取永久素材
*  10、GoZero() 调用清零接口（一个月只有10次哦）

## 数据库

数据库采用的mysql，主要涉及1张表，存储微信发送我们的access_token。可以看GetWXAccessToken()这个接口，自行去搭建数据库吧！

## 注意事项：

本测试采用的微信公众号测试帐号来验证的，因为个人订阅号的功能不完善！

## 联系方式

Email:139087006@qq.com，只接收邮件，请勿添加QQ，谢谢理解！