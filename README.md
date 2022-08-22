# WeChat_Morning
利用微信公众号给女朋友、对象、朋友或者自己发送消息
## 填写配置信息
将从微信公众平台获取到的appid、secret、template_id填写到第77、79、81行。

## 定时发送
可以利用Linux的Corn或者宝塔的计划任务，反正只要每天能定时访问一次指定的地址即可。

指定地址：http://你的域名或者IP/index.php?type=corn

## 回复消息
将微信测试号的API地址对接到以下地址即可

http://你的域名或者IP/index.php

## 微信公众平台测试号申请

https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=sandbox/login

## 模板代码
今天是 {{date.DATA}}

以下是 {{city.DATA}} 今日天气情况

最高气温：{{xMax.DATA}}

最低气温：{{xMin.DATA}}

{{tips.DATA}}

今日一言：{{hitokoto.DATA}}
