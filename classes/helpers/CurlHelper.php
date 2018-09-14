<?php
/**
 * Created by PhpStorm.
 * User: km
 * Date: 25.09.17
 * Time: 13:19
 */

namespace gruzovichkof\helpers;

use gruzovichkof\traits\Singleton;

class CurlHelper
{
	use Singleton;

	private $ch;
	private $headers;

	public $lastCode;

	/**
	 * Установка заголовков
	 * @param array $headers
	 * @return $this
	 */
	public function setHeaders(array $headers) {
		if(!$this->headers) $this->headers = [];
		$this->headers = array_merge($this->headers,$headers);
		return $this;
	}

	/**
	 * Создание потока и установка параметров
	 * @param string $method
	 * @param string $requestType
	 * @return resource curl
	 */
	public function getCurl($method='post',$requestType='json') {
		if (!$this->ch) {
			$this->ch = curl_init();
			$headers = [
				'Accept-Language: ru',
			];
			if($requestType=='json') {
				$headers[] = 'Content-Type: application/json; charset=utf-8';
			} elseif(!empty($requestType)) {
				$headers[] = 'Content-Type: '.$requestType.'; charset=utf-8';
			}
			if($this->headers) {
				$headers = array_merge($headers,$this->headers);
			}

			curl_setopt(
				$this->ch,
				CURLOPT_HTTPHEADER,
				$headers
			);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
			if($method=='post') {
				curl_setopt($this->ch, CURLOPT_POST, 1);
			}
			curl_setopt($this->ch, CURLOPT_HEADER, 1);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->ch, CURLOPT_TIMEOUT, 600);
			curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 600);
		}
		return $this->ch;
	}

	/**
	 * Выполнение запроса
	 * @param string $url - ссылка
	 * @param array $request - запросы
	 * @param string $method - метод (post)
	 * @param string $requestType - тип запроса
	 * @param string $responseType - тип ответа (json)
	 * @return mixed|array
	 */
	public function request($url, $request=[], $method='post', $requestType='', $responseType='json') {
		$curl = $this->getCurl($method,$requestType);
		if($method=='get' && $request) {
			$request = http_build_query($request);
//			$request = str_replace('=','%3D',$request);
			$url .= '?'.$request;
		}
//		var_dump($url);
		curl_setopt($curl, CURLOPT_URL, $url);

		if($requestType=='json' && $request) {
			$request = json_encode($request, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		} else {
			$request = http_build_query($request);
		}
		if($method=='post') {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		}
		$response = curl_exec($curl);

		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$this->lastCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//		var_dump($header,$this->lastCode);

		$body = substr($response, $header_size);

		if($responseType=='json') {
			try {
				$data = json_decode($body,true);
				return $data;
			} catch (\Exception $e) {
				return [];
			}
		} else {
			return $body;
		}
	}
}