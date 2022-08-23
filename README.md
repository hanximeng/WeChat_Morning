# WeChat_Morning
利用微信公众号给女朋友、对象、朋友或者自己发送消息，支持同时多人使用！支持同时多人使用！支持同时多人使用！

## 登录微信公众平台测试号申请地址
https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=sandbox/login
![](https://s1.328888.xyz/2022/08/23/bgvrC.png)

## 填写配置信息
![](https://s1.328888.xyz/2022/08/23/b8lyB.png)
将从微信公众平台获取到的Token、appid、secret、template_id填写到第34、77、79、81行。

## 模板ID（template_id）获取
![](https://s1.328888.xyz/2022/08/23/b8mdR.png)

## 模板代码
今天是 {{date.DATA}}

以下是 {{city.DATA}} 今日天气情况

最高气温：{{xMax.DATA}}

最低气温：{{xMin.DATA}}

{{tips.DATA}}

今日一言：{{hitokoto.DATA}}

## 定时发送
![](https://s1.328888.xyz/2022/08/23/b86rr.png)
可以利用Linux的Corn或者宝塔的计划任务，反正只要每天能定时访问一次指定的地址即可。

指定地址：http://你的域名或者IP/index.php?type=corn

## 回复消息
将微信测试号的接口配置信息中的URL对接到以下地址即可

http://你的域名或者IP/index.php

对接完成后删除27-42行

## Tips
有任何问题都可添加QQ群：549522943
