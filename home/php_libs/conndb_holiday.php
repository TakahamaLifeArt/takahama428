<?php
/*
 * 休業日付と告知文を取得
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once dirname(__FILE__).'/http.php';

class Conndb_holiday extends HTTP {

	public function __construct($args=_API){
		parent::__construct($args);
	}

	public function getHolidayinfo(){
		$res = parent::request('POST', array('act'=>'holidayinfo', 'mode'=>'r', 'site'=>_SITE));
		$data = unserialize($res);
		return $data;
	}
}

?>