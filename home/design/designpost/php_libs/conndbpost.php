<?php
/*
*	Database connection
*	charset utf-8
*
*/
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/http.php';

class Conndbpost extends HTTP {
	
	public function __construct($args=_API_U){
		parent::__construct($args);
	}
	
	
	/**
	*	写真の登録
	*	@args	[user_id, pst_filename, pst_basename, pst_content, pst_thumb, pst_mime_type, pst_width, pst_height, pst_byte, pst_comment, pst_taggroup, pst_show, pst_tagdesign]
	*
	*	return	直近のクエリで生成した自動生成の ID
	*/
	public function setPicture($args){
		$res = parent::request('POST', array('act'=>'setpicture', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/**
	*	写真情報の更新
	*	@args	[pst_comment, pst_taggroup, pst_show, postid, pst_tagdesignt]
	*
	*	return	true:OK　false:NG
	*/
	public function updatePicture($args){
		$res = parent::request('POST', array('act'=>'updatepicture', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/**
	*	写真情報一覧を取得
	*	@args	null:default, {'taggroupid':tagdesign_id}, {'tagdesignid':taggroup_id}
	*
	*	return	[投稿写真とユーザー情報]
	*/
	public function getPictureList($args=null){
		$res = parent::request('POST', array('act'=>'getpicturelist', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
	/**
	*	写真情報の削除
	*	@args	postid
	*
	*	return	true:OK　false:NG
	*/
	public function deletePicture($args){
		$res = parent::request('POST', array('act'=>'deletepicture', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}
	
	
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
	*	ユーザーの存在確認
	*	@args	['email','pass']
	*
	*	reutrn	OK:{'username:':ユーザー名, 'userid':ユーザーID}　NG:false
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
	*	メールアドレスの重複チェック
	*	@args	[メールアドレス, ユーザーID(default: null)]
	*	return	ユーザー情報:重複　false:新規
	*/
	public function checkExistEmail($args) {
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
	*	アカウントの削除
	*	@args	ユーザーID
	*	reutrn	true:成功　false:失敗
	*/
	public function leaveAccount($args) {
		$res = parent::request('POST', array('act'=>'leaveaccount', 'args'=>$args));
		$data = unserialize($res);
		
		return $data;
	}

}
?>
