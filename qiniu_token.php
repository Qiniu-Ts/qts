<?php
require 'vendor/autoload.php';

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

$client = new Client([]);

$param = array(
    'data' => array(
        "uri" => "http://7xlv47.com1.z0.glb.clouddn.com/pulpsexy.jpg",
    ),
);

$param = \GuzzleHttp\json_encode($param);

$req = new Request('POST', 'https://argus.atlab.ai/v1/pulp', array(), $param);
$req = $req->withHeader("Content-Type", 'application/json');

$ak = 'lVgtk5xr03Oz_uvkzDtQ8LtpiEUWx5tGEDUZVg1y';
$sk = '';


$req = signReq($req, $ak, $sk);


$response = $client->send($req, ['timeout' => 2]);

var_dump($response);


//<Method> + " " + <Path> + "?<RawQuery>" + "\nHost: " + <Host> + "\nContent-Type: " + <contentType> + "\n\n" + <bodyStr>
function signReq(Request $req, $ak, $sk)
{
    $data = $req->getMethod() . ' ' . $req->getUri()->getPath();

    $query = $req->getUri()->getQuery();
    if ($query !== '') {
        $data .= '?' . $query;
    }

    $host = $req->getHeader("Host");
    $data .= "\nHost: " . $host[0];

    $ct = $req->getHeader('Content-Type');
    if (count($ct) !== 0) {
        $data .= "\nContent-Type: " . $ct[0];
    }

    $body = $req->getBody();
    if (count($ct) !== 0 && $ct[0] !== 'application/octet-stream' && $body !== '') {
        $data .= "\n\n" . $body;
    }

    $hmac = hash_hmac('sha1', $data, $sk, true);
    $sign = 'Qiniu ' . $ak . ':' . base64_urlSafeEncode($hmac);

    return $req->withHeader('Authorization', $sign);
}

function base64_urlSafeEncode($data)
{
    $find = array('+', '/');
    $replace = array('-', '_');
    return str_replace($find, $replace, base64_encode($data));
}