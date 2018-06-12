<?php
/**
 * Calendar Class
 *
 * log: 2018-06-12 created
 */
declare(strict_types=1);
namespace package;
require_once __DIR__.'/../config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/http.php';
use \HTTP;
	
class Calendar extends HTTP
{
	private $_year;
	private $_month;
	private $_from = 0;
	private $_to = 0;
	
	/**
	 *
	 * @param {int} year
	 * @param {int} month
	 */
	public function __construct(int $year = 0, int $month = 0)
	{
		$this->_year = empty($year)? (int)date('Y'): $year;
		$this->_month = empty($month)? (int)date('n'): $month;
		
		$this->setHoliday();
	}
	
	
	/**
	 * 休日期間の開始日と終了日のタイムスタンプを取得する
	 */
	private function setHoliday()
	{
		if (empty(_FROM_HOLIDAY)) {
			$this->_from = 0;
		} else {
			list($Y1, $M1, $D1) = explode('/', _FROM_HOLIDAY);
			$this->_from = mktime(0, 0, 0, (int)$M1, (int)$D1, (int)$Y1);
		}

		if (empty(_TO_HOLIDAY)) {
			$this->_to = 0;
		} else {
			list($Y2, $M2, $D2) = explode('/', _TO_HOLIDAY);
			$this->_to = $to = mktime(0, 0, 0, (int)$M2, (int)$D2, (int)$Y2);
		}
	}
	
	
	/**
	 * カレンダーAPIから日付情報を取得する
	 * @param {int} year
	 * @param {int} month
	 * @raturn {sting} カレンダー配列
	 */
	private function getCalendar(int $year = 0, int $month = 0): array
	{
		$param = array();
		$endPoint = '/calendar/'.$year.'/'.$month;
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		$this->setURL(_API_3.$endPoint);

		return json_decode($this->request('GET', $param, $headers), true);
	}
	
	
	/**
	 * htmlのtrタグを生成する
	 * @param {array} ca カレンダー配列
	 * @raturn {sting} HTML
	 */
	private function makeCalendarHTML($ca): string
	{
		// first week
		if (0 < $ca[0]['week']) {
			$calendar .= "<tr>";
			for ($n=0; $n<$ca[0]['week']; $n++) {
				$calendar .= "<td> </td>";
			}
		}

		for ($i=0, $len=count($ca); $i<$len; $i++) {
			if ($ca[$i]['week'] === 0) {
				$calendar .= '<tr>';
			}

			if (!empty($ca[$i]['holiday']) || ($this->_from<=$ca[$i]['time_stamp'] && $ca[$i]['time_stamp']<=$this->_to)) {
				$calendar .= "<td class=\"off";
			} else if($ca[$i]['week'] === 0) {
				$calendar .= "<td class=\"sun";
			} else if($ca[$i]['week'] === 6) {
				$calendar .= "<td class=\"sat";
			} else {
				$calendar .= "<td class=\"";
			}

			if($ca[$i]['day'] === date('j') && $ca[$i]['month'] === date('m')){
				$calendar .= " today\"><div>{$ca[$i]['day']}</div></td>";
			}else{
				$calendar .= "\">{$ca[$i]['day']}</td>";
			}

			if ($ca[$i]['week'] === 6) {
				$calendar .= '</tr>';
			}
		}

		// last week
		if ($ca[--$i]['week'] <= 6) {
			for ($n=$ca[$i]['week']; $n<6; $n++) {
				$calendar .= "<td> </td>";
			}
			$calendar .= '</tr>';
		}
		
		return $calendar;
	}
	
	
	/**
	 * カレンダーのHTMLを返す
	 * @param {int} offset 当該月からの増減月数、{@code 0}は当月、{@code 1}は翌月、{@code -1}は前月
	 * @raturn {string} HTML
	 */
	public function getHTML(int $offset = 0): string
	{
		$d = explode('-', date('Y-n', mktime(0, 0, 0, $this->_month + $offset, 1, $this->_year)));
		$year = (int)$d[0];
		$month = (int)$d[1];
		
		return $this->makeCalendarHTML($this->getCalendar($year, $month));
	}
	
	
	/**
	 * 年月を返す
	 * @param {int} offset 当該月からの増減月数、{@code 0}は当月、{@code 1}は翌月、{@code -1}は前月
	 * @raturn {array} ['year', 'month']
	 */
	public function getDate(int $offset = 0): array
	{
		$d = explode('-', date('Y-n', mktime(0, 0, 0, $this->_month + $offset, 1, $this->_year)));
		$year = (int)$d[0];
		$month = (int)$d[1];

		return ['year' => $year, 'month' => $month];
	}
}
?>