<?php
/**
 * 会員クラス
 *
 */
declare(strict_types=1);
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/http.php';
class TlaMember extends HTTP {
	private $_userId = 0;
	private $_sales = 0;
	private $_my = null;
	private $_Gold = null;
	private $_Silver = null;
	private $_Bronze = null;
	private $_Ordinary = null;
	private $_headers = [
		'X-TLA-Access-Token:'._ACCESS_TOKEN,
		'Origin:'._DOMAIN
	];
	
	/**
	 * Constructor
	 * @param {int} uid customerテーブルのID
	 */
	public function __construct($uid) {
		// ゴールド会員
		$this->Gold = new stdClass();
		$this->_Gold->name = 'ゴールド';
		$this->_Gold->code = 'gold';
		$this->_Gold->ratio = 7;
		$this->_Gold->sales = 300000;
		$this->_Gold->nextRank = null;

		// シルバー会員
		$this->_Silver = new stdClass();
		$this->_Silver->name = 'シルバー';
		$this->_Silver->code = 'silver';
		$this->_Silver->ratio = 5;
		$this->_Silver->sales = 150000;
		$this->_Silver->nextRank = clone $this->_Gold;

		// ブロンズ会員
		$this->_Bronze = new stdClass();
		$this->_Bronze->name = 'ブロンズ';
		$this->_Bronze->code = 'bronze';
		$this->_Bronze->ratio = 3;
		$this->_Bronze->sales = 80000;
		$this->_Bronze->nextRank = clone $this->_Silver;
		
		// 一般会員
		$this->_Ordinary = new stdClass();
		$this->_Ordinary->name = '一般';
		$this->_Ordinary->code = '';
		$this->_Ordinary->ratio = 0;
		$this->_Ordinary->sales = 1;
		$this->_Ordinary->nextRank = clone $this->_Bronze;
		
		// 会員情報を設定
		$this->_userId = $uid;
		$this->_sales = $this->_salesVolume($uid);
		$this->_ranking($this->_sales);
	}
	
	/**
	 * 売上高の総合計金額
	 * @param {int} userId customerテーブルのID
	 * @return {int} 売り上げ金額合計
	 */
	private function _salesVolume(int $userId): int {
		$this->setURL('https://takahamalifeart.com/v3/users/'.$userId.'/sales');
		$r = $this->request('GET', [], $this->_headers);
		$data = json_decode($r, true);
		return (int)$data[0]['total_price'] ?? 0;
	}
	
	/**
	 * 売上高から会員を設定
	 * @param {int} sales 売上高
	 */
	private function _ranking(int $sales) {
		if ($sales >= $this->_Gold->sales) {
			$this->_my = $this->_Gold;
		} else if($sales >= $this->_Silver->sales) {
			$this->_my = $this->_Silver;
		} else if($sales >= $this->_Bronze->sales) {
			$this->_my = $this->_Bronze;
		} else {
			$this->_my = $this->_Ordinary;
		}
	}


	/**
	 * 割引率
	 * @return {int}
	 */
	public function getRankRatio(): int {
		return $this->_my->ratio;
	}
	
	/**
	 * 会員名称
	 * @return {string}
	 */
	public function getRankName(): string {
		return $this->_my->name;
	}
	
	/**
	 * 会員名称コード
	 * @return {string}
	 */
	public function getRankCode(): string {
		return $this->_my->code;
	}
	
	/**
	 * 次のランクになるために必要な金額
	 * @return {int}
	 */
	public function priceForBeNext(): int {
		if ($this->_my->nextRank===null) {
			return 0;
		} else {
			return $this->_my->nextRank->sales - $this->_sales;
		}
	}
	
	/**
	 * 次のランクの名称
	 * @return {string}
	 */
	public function nextRankName(): string {
		if ($this->_my->nextRank===null) {
			return '';
		} else {
			return $this->_my->nextRank->name;
		}
	}
}
?>