<?php

//このページのsourceをほとんどそのまま流用
//http://ameblo.jp/itboy/entry-10629266660.html

include 'HTTP/OAuth/Consumer.php';

//別ファイルに格納したコンシューマキー、シークレットキーを取得
$contents = @file('mytwitlogger_key.txt');
$i = 0;
foreach($contents as $line)
{
	$oauthkey[$i] = str_replace(array("\r\n","\r","\n"),'',$line);
	$i = $i + 1;
}

$consumer_key    = $oauthkey[0];
$consumer_secret = $oauthkey[1];
$access_token        = $oauthkey[2];
$access_token_secret = $oauthkey[3];

$consumer = new HTTP_OAuth_Consumer($consumer_key, $consumer_secret);

$http_request = new HTTP_Request2();
$http_request->setConfig('ssl_verify_peer', false);
$consumer_request = new HTTP_OAuth_Consumer_Request;
$consumer_request->accept($http_request);
$consumer->accept($consumer_request);

// リクエストトークンの発行を依頼
$consumer->getRequestToken('https://twitter.com/oauth/request_token');

// リクエストトークンを取得
$request_token = $consumer->getToken();
$request_token_secret = $consumer->getTokenSecret();

// リクエストトークンをセット
$consumer->setToken($request_token);
$consumer->setTokenSecret($request_token_secret);

// 発行済みのアクセストークンをセット
$consumer->setToken($access_token);
$consumer->setTokenSecret($access_token_secret);

?>