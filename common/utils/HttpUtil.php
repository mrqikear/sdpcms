<?php
/**
 * Created by PhpStorm.
 * User: narrowsky
 * Date: 19/3/27
 * Time: 下午4:49
 */

namespace common\utils;

class HttpUtil
{

	public static function post_json($url, $jsonStr)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		        'Accept: text/plain',
				'Content-Type: application/json; charset=utf-8',
				'Content-Length: ' . strlen($jsonStr)
			)
		);
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	/*
	 * post请求
	 * $params $url  地址
	 * $params $body 请求参数
	 */
	public static function post($url,$body,$timeout=5)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}

	public static function setApi()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$_GET['is_api'] = 1;
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_POST['is_api'] = 1;
		}
	}

	public static function setPageSize($pageSize)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$_GET['pageSize'] = $pageSize;
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_POST['pageSize'] = $pageSize;
		}
	}

    /**
     * openApi请求
     * @param $params array 请求参数
     * @param $domain_data array 域名密钥参数
     * @return mixed
     * @throws SDPException
     * @time 2020-07-10 15:46
     * @author tangxiao@movee.cn
     */
    public static function openApiRequest($params, $domain_data)
    {
        if (!isset($domain_data['domain']) || !isset($domain_data['appid']) || !isset($domain_data['app_secret'])) {
            throw new SDPException('缺少域名或密钥参数');
        }
        $url = $domain_data['domain'] . $domain_data['api'];
        $params['timestamp'] = time();
        $params['appid'] = $domain_data['appid'];
        $params['sign'] = SecurityUtil::getSign($params, $domain_data['app_secret']);
        $result = self::post($url, $params);
        if (empty($result)) {
            throw new SDPException('请求失败！域名地址有误');
        }
        $result = json_decode($result, true);
        if (empty($result['status'])) {
            throw new SDPException('处理失败！' . $result['message']);
        }
        return $result['data'];
    }
}