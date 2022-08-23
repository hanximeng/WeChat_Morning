<?php
error_reporting(0);

//CURL
function Curl($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$data = curl_exec($curl);
	curl_close($curl);
	return $data;
}

//POST提交
function Curl_Post($remote_server, $post_string) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $remote_server);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'HxmBot');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

// 微信token认证
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    $echostr = $_GET["echostr"];

    // 你的设置Token
    $token = "";

    $tmpArr = array($nonce,$token,$timestamp);
    sort($tmpArr,SORT_STRING);
    $str = implode($tmpArr);
    $sign = sha1($str);
    if ($sign == $signature) {
    exit($echostr);
}

$keyword = trim($postObj->Content);
$fromUsername=$postObj->FromUserName;
$toUsername = $postObj->ToUserName;
$time = time();
$textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>0</FuncFlag></xml>";

if(!empty($keyword)) {
	$ID=json_decode($ID,true);
	if(strpos($keyword,"绑定城市")!==false) {
		$Info=explode("绑定城市 ",$keyword);
		if(empty($Info[0])) {
			$ID=Curl("https://weather.cma.cn/api/autocomplete?q=".urlencode($Info[1]));
		}
		$Info=explode("|",$ID['data']['0']);
		if(!empty($Info[0])) {
			$x=0;
			$text='';
			foreach ($ID['data'] as $value) {
				$text.=''.$x++.'：'.$value."\n";
			}
			$text=str_replace('null|','',$text);
			$text=str_replace('台湾','中国台湾',$text);
			$msgType = "text";
			$contentStr = $text."\n请回复：ID 编号；\n例如：ID 123456";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
			exit;
		}
	} elseif(strpos($keyword,"ID")!==false) {
		$Info=explode("ID ",$keyword);
		if(empty($Info[0])) {
			file_put_contents('./Data/'.$fromUsername.'.txt',$Info[1]);
			$msgType = "text";
			$contentStr = "绑定成功！可发送Test查看效果。";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
	}
}

$ID=file_get_contents('./Data/'.$fromUsername.'.txt');
if(empty($ID)) {
	$msgType = "text";
	$contentStr = "检测到还未绑定城市，请回复：绑定城市+城市名；\n例如：绑定城市北京。";
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	echo $resultStr;
}

function Hxm_WeaTher($ID,$fromUsername) {
	//这里填写appid
	$appid='';
	//这里填写secret
	$secret='';
	//这里填写模板ID
	$template_id='';
	$Weather=Curl("https://weather.cma.cn/api/weather/".$ID);
	$Weather=json_decode($Weather,true);
	if($Weather['data']['daily']['0']['dayText']==$Weather['data']['daily']['0']['nightText']) {
		$天气=$Weather['data']['daily']['0']['dayText'];
	} else {
		$天气=$Weather['data']['daily']['0']['dayText'].'转'.$Weather['data']['daily']['0']['nightText'];
	}
	$Weather_v2=Curl("https://weather.cma.cn/api/now/".$ID);
	$Weather_v2=json_decode($Weather_v2,true);
	$Json=Curl('https://v1.hitokoto.cn');
	$Hitokoto=json_decode($Json,true);
	//推送至微信
	$Json='{"touser":"'.$fromUsername.'","template_id":"'.$template_id.'","topcolor":"#FF0000","data":{"date":{"value":"'.$Weather['data']['daily']['0']['date'].'","color":"#173177"},"city":{"value":"'.$Weather['data']['location']['path'].'","color":"#173177"},"weather":{"value":"'.$天气.'","color":"#173177"},"xMax":{"value":"'.$Weather['data']['daily']['0']['high'].'℃","color":"#173177"},"xMin":{"value":"'.$Weather['data']['daily']['0']['low'].'℃","color":"#173177"},"tips":{"value":"'.$Weather_v2['data']['alarm']['0']['title'].'","color":"#FF0000"},"hitokoto":{"value":"'.$Hitokoto['hitokoto'].'","color":"#173177"}}}';
	$access_token=json_decode(curl('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret.''),true)['access_token'];
	$Url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
	$WeChat=Curl_Post($Url,$Json);
}

if($keyword == 'Test') {
	$WeChat=Hxm_WeaTher($ID,$fromUsername);
} elseif($_GET['type'] == 'corn') {
	//推送至用户列表
	$List=glob('./Data/*.txt');
	foreach ($List as $value) {
		preg_match("#Data/(.*?.).txt#",$value,$fromUsername);
		if(!empty($Info['1'])) {
			$ID=file_get_contents('./Data/'.$fromUsername['1'].'.txt');
			Hxm_WeaTher($ID,$fromUsername['1']);
		}
	}
} else {
	echo 'Hello World! :)';
}
