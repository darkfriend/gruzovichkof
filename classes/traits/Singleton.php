<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 16.03.2018
 * Time: 13:52
 */

namespace gruzovichkof\traits;


trait Singleton
{
	protected static $_instance;

	/**
	 * Singleton
	 * @return self
	 */
	public static function getInstance() {
		if(!self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}