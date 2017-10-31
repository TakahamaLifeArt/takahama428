<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
	require_once dirname(__FILE__).'/garbage.php';
/** 
*	デザインイメージのセッション情報の更新とガーベージ
*	charset utf-8
*
*	ID, top, left, zoom, degree, zIndex, type, src, width, height, fontsize, fontfamily(path), color, inkname, direction, text 
*/
	if(isset($_POST['act'], $_SESSION['imagefile']) && !empty($_SESSION['imagefile']) ){
		if( isset($_POST['id']) ) $id = $_POST['id'];
		switch($_POST['act']){
		case 'getsess':
			$isFirst = true;
			$i = 0;
			foreach($_SESSION['imagefile'] as $key => $row){
				$foo[$key] = $row["id"];
			}
			array_multisort($foo,SORT_ASC,$_SESSION['imagefile']);
			foreach($_SESSION['imagefile'] as $key=>$val){
				$i++;
				if($isFirst){
					$isFirst = false;
				}else{
					$result .= ";";
				}
				$result .= $val['id'].",".$val['top'].",".$val['left'].",".$val['zoom'].",".$val['degree'].",".$val['zid'].",".$val['type'].",".$val['path'].",".$val['width'].",".$val['height'].",".$val['size'].",".$val['font'].",".$val['color'].",".$val['inkname'].",".$val['direct'].",".$val['text'].",".$val['position'];
			}
			echo $result;
			break;
		case 'setsess':
		/** 
		*	ダミー画像生成前にテキスト画像の状態を更新する
		*/
			$res = "";
			if(isset($_SESSION['imagefile'][$id])){
				foreach($_POST as $key=>$val){
					$_SESSION['imagefile'][$id][$key] = $val;
				}
				$res = 1;
			}
			echo count($_SESSION['imagefile']);
			break;
		case 'sort':
		/** 
		*	Design Creator で作成した画像を合成する為に、
		*	重なり順にSessionのデータを並べ替えて格納する　creator.jsから
		*/
			foreach($_SESSION['imagefile'] as $key => $row){
				$foo[$key] = $row["zid"];
			}
			array_multisort($foo,SORT_ASC,$_SESSION['imagefile']);
			echo count($_SESSION['imagefile']);
			break;
		case 'move':
		/*
		*	画像の移動による位置情報の更新
		*/
			if( isset($_POST['top'], $_POST['left']) ){
				if(isset($_SESSION['imagefile'][$id])){
					$_SESSION['imagefile'][$id]['top'] = $_POST['top'];
					$_SESSION['imagefile'][$id]['left'] = $_POST['left'];
				}
			}
			break;
		case 'zindex':
		/*
		*	Z-Index を更新
		*/
			foreach($_SESSION['imagefile'] as $key => $row){
				$foo[$key] = $row["zid"];
			}
			array_multisort($foo,SORT_ASC,$_SESSION['imagefile']);
			
			$i = 1;
			foreach($_SESSION['imagefile'] as $key=>$val){
				if($val['id']==$id){
					$_SESSION['imagefile'][$key]['zid'] = count($_SESSION['imagefile']);
				}else{
					$_SESSION['imagefile'][$key]['zid'] = $i++;
				}
			}
			break;
		case 'set':
		/*
		*	回転角度と拡大縮小率を設定
		*/
			if( isset($_POST['zoom']) ){
				$param = 'zoom';
				$data = $_POST['zoom'];
			}elseif( isset($_POST['degree']) ){
				$param = 'degree';
				$data = $_POST['degree'];
			}
			foreach($_SESSION['imagefile'] as $key=>$val){
				if($val['id']==$id){
					$_SESSION['imagefile'][$key][$param] = $data;
					break;
				}
			}
			break;
		case 'remove':
		/*
		*	画像ファイルと当該セッションデータの削除
		*/
			Garbage::collect($id, $_POST['design']);
			break;
		case 'gettext':
		/*
		*	テキストアリアの文字列を取得
		*/
			foreach($_SESSION['imagefile'] as $key=>$val){
				if($val['pid']==$id){
					$res = $_SESSION['imagefile'][$key]['text'];
					break;
				}
			}
			break;
		}
	}else{
		echo '';
	}
?>