<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 14.09.2018
 * Time: 0:16
 */

namespace gruzovichkof\models;

use gruzovichkof\traits\Singleton;

class XmlModel
{
	use Singleton;

	protected $xml;
	protected $searchResults;

	public function getResults():array {
		$arItems = [];
		if(empty($this->searchResults)) return $arItems;
		foreach ($this->searchResults as $item) {
			$arItems[] = (string) $item->doc->url;
		}
		return $arItems;
	}

	/**
	 * Парсит xml-строку
	 * @param string $xml
	 * @return $this
	 * @throws \Exception
	 */
	public function parse($xml):self {
		if(empty($xml))
			throw new \Exception('XML is empty!');
		$this->xml = new \SimpleXMLElement($xml);
		$this->searchResults = $this->xml->response->results->grouping->group;
		return $this;
	}
}