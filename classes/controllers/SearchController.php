<?php

namespace gruzovichkof\controllers;

use gruzovichkof\helpers\CurlHelper;
use gruzovichkof\models\XmlModel;
use gruzovichkof\traits\Singleton;

class SearchController
{
	use Singleton;

	private $_key = '03.118404738:77984970f338f8242ec3a062a30b66f2';
	private $_login = 'Maslov-tlt';

	public $lastError;

	private function __construct()
	{
		$config = include __DIR__.'/../config/config.php';
		if(empty($config['key']))
			throw new \Exception('Key is empty!');
		if(empty($config['login']))
			throw new \Exception('Login is empty!');
		$this->_key = $config['key'];
		$this->_login = $config['login'];
	}

	/**
	 * Возвращает массив результатов поиска
	 * @param string $queryString
	 * @return array
	 * @throws \Exception
	 */
	public function query($queryString):array {
		$curl = CurlHelper::getInstance();
		$xml = $curl->request(
			'https://yandex.ru/search/xml',
			[
				'user' => $this->_login,
				'key' => $this->_key,
				'query' => $queryString,
			],
			'get',
			'',
			'xml'
		);
		$result = XmlModel::getInstance()
			->parse($xml)
			->getResults();
		if($result) {
			foreach ($result as &$item) {
				$item = [
					'value' => $item,
				];
			}
		}
		return $result;
	}
}