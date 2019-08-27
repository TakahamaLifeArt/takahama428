<?php
/*
*	Database connection
*	charset utf-8
*
*/
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/http.php';

class Conndb extends HTTP {
	
	public function __construct($args=_API){
		parent::__construct($args);
	}

	/*
	*	商品名とカラーごとのコード一覧データ
	*	@itemid			アイテムID　default: 1
	*
	*	@return			['name':[アイテムコード:アイテム名],
						 'category':[カテゴリーキー:カテゴリー名],
						 'code':[code:カラー名, ...],
						 'size':[...],
						 'ppid':[プリントポジションID]
	*					codeのフォーマットは、「アイテムコード＿カラーコード」　ex) 085-cvt_001
	*/
	public function itemAttr($itemid=1){
		$res = parent::request('POST', array('act'=>'itemattr', 'itemid'=>$itemid));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/**
	*	注文履歴を取得（member）
	*	@args		customer ID
	*
	*	return		[注文情報]
	*/
	public function getOroderHistory($args){
		$res = parent::request('POST', array('act'=>'getorderhistory', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/**
	*	製作進行状況の取得（member）
	*	@args		[customer ID, order ID]
	*
	*	return		[進行状況]
	*/
	public function getProgress($args){
		$res = parent::request('POST', array('act'=>'getprogress', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/*
	*	プリント情報（member）
	*	@args			受注No.
	*
	*	@return			[プリント情報]
	*/
	public function getDetailsPrint($args){
		$res = parent::request('POST', array('act'=>'getdetailsprint', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/*
	*	請求書・領収書・納品書のデータ（member）
	*	@args			受注No.
	*
	*	@return			[出力情報]
	*/
	public function getPrintform($args){
		$res = parent::request('POST', array('act'=>'getprintform', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/*
	*	領収書の発行回数を設定（member）
	*	@args			受注No.
	*
	*	@return			[発行回数]
	*/
	public function setReceiptCount($args){
		$res = parent::request('POST', array('act'=>'setreceiptcount', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}
	
	
	/*
	*	フォローメールの配信停止処理
	*	@args			{'customer_id', 'cancel'(停止:1)'
	*
	*	@return			成功:true  失敗:false
	************************************************/
	public function unsubscribe($args){
		$res = parent::request('POST', array('act'=>'unsubscribe', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	
	
	/*====================================

		写真館サーバー（master）
		2017-2-13から
		takahamalifeartサーバー(User)に移動した

	=====================================*/
	
	/*
	*	ユーザー新規登録
	*	@args	['uname','email','pass','uicon','filename']
	*
	*	reutrn	true:OK　false:NG
	*/
	public function setUser($args) {
		$res = parent::request('POST', array('act'=>'setuser', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}

	/*
	*	ユーザーの存在確認
	*	@args	['email','pass']
	*
	*	reutrn	OK:{ユーザー情報}　NG:false
	*/
	public function getUser($args) {
		$res = parent::request('POST', array('act'=>'getuser', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}
	
	
	/*
	*	ユーザー情報の取得
	*	@args	ユーザーID　defult:null
	*
	*	reutrn	[ユーザー情報]
	*/
	public function getUserList($args=null) {
		$res = parent::request('POST', array('act'=>'getuserlist', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}

	/*
	*	ユーザーのお届け先の取得
	*	@args	ユーザーID　defult:null
	*
	*	reutrn	[お届け先情報]
	*/
	public function getDeli($args) {
		$res = parent::request('POST', array('act'=>'getdeli', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}

	/*
	*	ユーザーのお届け先更新
	*	@args	ユーザーID　defult:null
	*
	*	reutrn OK:{ユーザー情報}　NG:false
	*/
	public function updateDeli($args) {
		$res = parent::request('POST', array('act'=>'updatedeli', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}

	/*
	*	ユーザー情報の更新
	*	@args	['userid','uname','email','uicon','filename']
	*
	*	reutrn	true:OK　false:NG
	*/
	public function updateUser($args) {
		$res = parent::request('POST', array('act'=>'updateuser', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}
	
	
	/*
	*	パスワードの変更
	*	@args	['userid','pass']
	*
	*	reutrn	true:OK　false:NG
	*/
	public function updatePass($args) {
		$res = parent::request('POST', array('act'=>'updatepass', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}
	

	/*
	*	アドレスの変更
    * 2016-12-21
	*	@args	{'userid','zipcode','addr0','addr1','addr2'}
	*
	*	reutrn	true:OK　false:NG
	*/
	public function updateAddr($args) {
		$res = parent::request('POST', array('act'=>'updateaddr', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}


	/*
	*	メールアドレスの重複チェック
	*	@args	[メールアドレス]
	*	return	ユーザー情報:重複　false:新規
	*/
	public function checkExistEmail($args){
		$res = parent::request('POST', array('act'=>'checkexistemail', 'args'=>$args));
		$data = unserialize($res);

		return $data;
	}
	
	
	/*
	*	ユーザーネームの重複チェック
	*	@args	[ユーザーネーム, ユーザーID(default: null)]
	*	reutrn	true:重複　false:新規
	*/
	public function checkExistName($args) {
		$res = parent::request('POST', array('act'=>'checkexistname', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}

	/*
	*	イメージ画像表示
	*	return		イメージ画像
	*/
	public function getDesigned($order_id) {
		$res = parent::request('POST', array('act'=>'showDesignImg', 'order_id'=>$order_id, 'folder'=>'imgfile'));
		$data = json_decode($res, true);

		return $data;

	}


}
?>
