##说明
文件基于THinkpHP3.2.3，不懂的可以去官网查看一下，或者在网上搜索教程看一下！
配置文件在weixin/Home/Conf里面，接口放在weixin/Home/Common 的function.php里面,后期会陆续更新！
##已有接口
目前以及开发了验证接口
*1、Check() 微信校验接口
*2、CURLGet()以及CURLPost() 封装CURL==GET 以及CURLPost()
*3、GetWXAccessToken() 获取ACCESS_TOKEN并写入数据库
*4、GetWXIPList() 获取微信Ip地址库
*5、GetUserList() 拉取用户列表
*6、GetUserBase() 用户基本信心
*7、PostMenu() 创建自定义菜单
*8、AddMaterial() 新增永久多媒体素材
*9、GetMaterialList() 获取永久素材
*10、GoZero() 调用清零接口（一个月只有10次哦）
