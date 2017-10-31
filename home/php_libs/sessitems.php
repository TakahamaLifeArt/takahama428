<?php
/*
*	session for request page
*	charset utf-8
*
*	log
*	created		; 2014-08-21
*
*	['items'][category_id]['category_key']
*			  			  ['category_name']
*		   	  			  ['item'][id]['code']
*					   			  	  ['name']
*							      	  ['color'][code]['name']
*											 		 ['size'][sizeid]['sizename']
*											  						 ['amount']
*																	 ['cost']
*
*									  ['posid']
*									  ['noprint']
*									  ['makerid']
*									  ['design'][base][0]['posname']
*												   		 ['ink']
*												   		 ['attachname']
*/


require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';

class SessItems {
	
	public function __construct(){}
	
	
	/*
	*	セッションの更新
	*	@mode		items, estimation, customer, attach
	*/
	public function update($mode){
		$isExist = false;
		
		if($mode=='items'){
		/*
		*	商品情報の更新
		*	@categoryid
		*	@categorykey
		*	@categoryname
		*	@itemid
		*	@itemcode
		*	@itemname
		*	@colorcode			array
		*	@colorname			array
		*	@posid
		*	@noprint
		*	@makerid
		*	@sizename			array
		*	@amount				array
		*	@cost				array
		*
		*	['items'][category_id]['category_key']
		*		  				  ['category_name']
		*	   	  				  ['item'][id]['code']
		*				   				  	  ['name']
		*							      	  ['color'][code]['name']
		*											 		 ['size'][sizeid]['sizename']
		*											  						 ['amount']
		*																	 ['cost']
		*									  ['posid']
		*									  ['noprint']
		*									  ['makerid']
		*
		*	return		 {'amount':合計枚数, 
		*				  'category': [{category_id,category_key,category_name,item_id,item_code,item_name,color_code,color_name,cost,amount},{} ...] }
		*/
			$catid = $_REQUEST['categoryid'];
			$itemid = $_REQUEST['itemid'];
			
			if(isset($_SESSION['request']['items'][$catid])){
				$isExist = 'category';
				if(isset($_SESSION['request']['items'][$catid]['item'][$itemid])){
					$isExist = 'item';
				}
			}
			
			if(!$isExist){
				$_SESSION['request']['items'][$catid]['category_key'] = $_REQUEST['categorykey'];
				$_SESSION['request']['items'][$catid]['category_name'] = $_REQUEST['categoryname'];
				
				$_SESSION['request']['items'][$catid]['item'][$itemid]['code'] = $_REQUEST['itemcode'];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['name'] = $_REQUEST['itemname'];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['posid'] = $_REQUEST['posid'];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['makerid'] = $_REQUEST['makerid'];
			}else if($isExist=='category'){
				$_SESSION['request']['items'][$catid]['item'][$itemid]['code'] = $_REQUEST['itemcode'];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['name'] = $_REQUEST['itemname'];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['posid'] = $_REQUEST['posid'];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['makerid'] = $_REQUEST['makerid'];
			}
			
			$_SESSION['request']['items'][$catid]['item'][$itemid]['noprint'] = $_REQUEST['noprint'];
			
			//$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['name'] = $_REQUEST['colorname'];
			//$item_sum = 0;
			$amount = 0;
			$tmp = $_SESSION['request']['items'][$catid]['item'][$itemid]['color'];
			$cnt= count($_REQUEST['sizeid']);
			for($i=0; $i<$cnt; $i++){
				$sizeid = $_REQUEST['sizeid'][$i];
				if($colorcode!=$_REQUEST['colorcode'][$i]){
					if($colorcode!=""){
						if($amount==0){
							unset($_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]);
							if(count($_SESSION['request']['items'][$catid]['item'][$itemid]['color'])==0){
								unset($_SESSION['request']['items'][$catid]['item'][$itemid]);
							}
						}
					}
					$colorcode = $_REQUEST['colorcode'][$i];
					$amount = 0;
					unset($tmp[$colorcode]);
				}
				
				if($_REQUEST['noprint']==1){	// プリントなしで10％UPし1円単位を切り上げる
					$cost = round($_REQUEST['cost'][$i]*1.1+4, -1);
				}else{
					$cost = $_REQUEST['cost'][$i];
				}
				
				if(!isset($_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['name'])){
					$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['name'] = $_REQUEST['colorname'][$i];
				}
				
				$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['size'][$sizeid]['sizename'] = $_REQUEST['sizename'][$i];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['size'][$sizeid]['amount'] = $_REQUEST['amount'][$i];
				$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['size'][$sizeid]['cost'] = $cost;
				$amount += $_REQUEST['amount'][$i];
				//$item_sum += $_REQUEST['cost'][$i]*$_REQUEST['amount'][$i];
			}
			
			// Step2で削除されたカラーのアイテム情報を消去
			if(!empty($tmp)){
				foreach($tmp as $key=>$val){
					unset($_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$key]);
				}
			}
			
			$data = $this->reqDetails();
			
			return $data;
		}
		
		if($mode=='amount'){
		/*
		*	枚数の更新
		*/
			$catid = $_REQUEST['categoryid'];
			$itemid = $_REQUEST['itemid'];
			$colorcode = $_REQUEST['colorcode'];
			$sizeid = $_REQUEST['sizeid'];
			
			$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['size'][$sizeid]['amount'] = $_REQUEST['amount'];
			
			$data = $this->reqDetails();
			
			return $data;
		}
		
	}
	
	
	/*
	*	セッションの削除
	*	@mode		items
	*/
	public function remove($mode){
		if($mode=='items'){
		/*
		*	商品情報の削除
		*	@categoryid
		*	@itemid
		*	@colorcode
		*	@sizeid
		*
		*	['items'][category_id]['item'][id]['color'][code]['name']
		*											 		 ['size'][sizeid]['sizename']
		*											  						 ['amount']
		*																	 ['cost']
		*
		*	return		 {'amount':合計枚数, 
		*				  'category': [{category_id,category_key,category_name,item_id,item_code,item_name,color_code,color_name,cost,amount},{} ...] }
		*/
			$catid = $_REQUEST['categoryid'];
			$itemid = $_REQUEST['itemid'];
			$colorcode = $_REQUEST['colorcode'];
			$sizeid = $_REQUEST['sizeid'];
			
			$isExist = false;
			$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['size'][$sizeid]['amount'] = 0;
			foreach($_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]['size'] as $sizeid=>$val){
				if($val['amount']>0){
					$isExist = true;
					break;
				}
			}
			
			if(!$isExist){
				$_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode] = array();
				unset($_SESSION['request']['items'][$catid]['item'][$itemid]['color'][$colorcode]);
				if(count($_SESSION['request']['items'][$catid]['item'][$itemid]['color'])==0){
					$_SESSION['request']['items'][$catid]['item'][$itemid] = array();
					unset($_SESSION['request']['items'][$catid]['item'][$itemid]);
				}
			}
			
			$data = $this->reqDetails();
			
			return $data;
		}
	}
	
	
	/*
	*	見積ボックスに表示する明細を返す
	*
	*	['items'][category_id]['category_key']
	*		  				  ['category_name']
	*	   	  				  ['item'][id]['code']
	*				   				  	  ['name']
	*							      	  ['color'][code]['name']
	*											 		 ['size'][sizeid]['sizename']
	*											  						 ['amount']
	*																	 ['cost']
	*									  ['posid']
	*									  ['noprint']
	*									  ['design'][base][0]['posname']
	*												   		 ['ink']
	*												   		 ['attachname']
	*
	*	return		 {'amount':合計枚数, 
	*				  'category': [{category_id,category_key,category_name,item_id,item_code,item_name,posid,color_code,color_name,cost,amount},{} ...] }
	*/
	public function reqDetails(){
		//$subcat = Items::getSubCategory();
		//$data = $this->reqEstimation();
		$data = array('amount'=>0);
		$list = array();
		if(count($_SESSION['request']['items'])==0){
			$data['category'] = array();
			return $data;
		}
		foreach($_SESSION['request']['items'] as $catid=>$val){
			$cat_key = $val['category_key'];
			$cat_name = $val['category_name'];
			foreach($val['item'] as $itemid=>$val2){
				foreach($val2['color'] as $colorcode=>$val3){
					$tmp = array();
					foreach($val3['size'] as $sizeid=>$val4){
						$tmp['cost'] += $val4['cost']*$val4['amount'];
						$tmp['amount'] += $val4['amount'];
						
						$data['amount'] += $val4['amount'];	// 合計枚数
					}

					$list[] = array('categoryid'=>$catid,
									'categorykey'=>$cat_key,
									'categoryname'=>$cat_name,
									'itemid'=>$itemid,
									'itemcode'=>$val2['code'],
									'itemname'=>$val2['name'],
									'posid'=>$val2['posid'],
									'colorcode'=>$colorcode,
									'colorname'=>$val3['name'],
									'size'=>$val3['size'],
									'cost'=>$tmp['cost'],
									'amount'=>$tmp['amount']
									);
				}
			}
		}
		$data['category'] = $list;
		
		return $data;
	}
	
	
	/*
	*	アイテムIDとカラーを指定してセッションの商品情報を返す
	*	['items'][category_id]['category_key']
	*		  				  ['category_name']
	*	   	  				  ['item'][id]['code']
	*				   				  	  ['name']
	*							      	  ['color'][code]['name']
	*											 		 ['size'][sizeid]['sizename']
	*											  						 ['amount']
	*																	 ['cost']
	*									  ['posid']
	*									  ['makerid']
	*									  ['design'][base][0]['posname']
	*												   		 ['ink']
	*												   		 ['attachname']
	*
	*	@itemid			アイテムID
	*	@colorcode		カラーコード　（指定なしは当該アイテムで指定されている全てのカラーを取得する）
	*
	*	return			{'cateogryid','categorykey','categoryname','itemie','itemcode','itemname','posid','colorcode',vol:{size_id:枚数}}
	*/
	public function reqPage($itemid, $colorcode){
		$data = array();
		if(count($_SESSION['request']['items'])==0) return $data;
		if($colorcode==""){
			foreach($_SESSION['request']['items'] as $catid=>$val){
				if(isset($val['item'][$itemid])){
					foreach($val['item'][$itemid]['color'] as $colorcode=>$hash){
						$tmp = array();
						$tmp['categoryid'] = $catid;
						$tmp['categorykey'] = $val['category_key'];
						$tmp['categoryname'] = $val['category_name'];
						$tmp['itemid'] = $itemid;
						$tmp['itemcode'] = $val['item'][$itemid]['code'];
						$tmp['itemname'] = $val['item'][$itemid]['name'];
						$tmp['posid'] = $val['item'][$itemid]['posid'];
						$tmp['colorcode'] = $colorcode;
						foreach($hash['size'] as $sizeid=>$val2){
							$tmp['vol'][$sizeid] = $val2['amount'];
						}
						
						$data[] = $tmp;
					}
					
					break;
				}
			}
		}else{
			foreach($_SESSION['request']['items'] as $catid=>$val){
				if(isset($val['item'][$itemid]['color'][$colorcode])){
					$tmp = array();
					$tmp['categoryid'] = $catid;
					$tmp['categorykey'] = $val['category_key'];
					$tmp['categoryname'] = $val['category_name'];
					$tmp['itemid'] = $itemid;
					$tmp['itemcode'] = $val['item'][$itemid]['code'];
					$tmp['itemname'] = $val['item'][$itemid]['name'];
					$tmp['posid'] = $val['item'][$itemid]['posid'];
					$tmp['colorcode'] = $colorcode;
					foreach($val['item'][$itemid]['color'][$colorcode]['size'] as $sizeid=>$val2){
						$tmp['vol'][$sizeid] = $val2['amount'];
					}
					$data[] = $tmp;
					
					break;
				}
			}
		}
		
		return $data;
	}
	
}

if(isset($_REQUEST['act'])){
	$orders = new SessItems();
	$isJSON = true;
	switch($_REQUEST['act']){
	case 'update':
		$dat = $orders->update($_REQUEST['mode']);
		
		break;
		
	case 'remove':
		$dat = $orders->remove($_REQUEST['mode']);
		
		break;
		
	case 'page':
		$dat = $orders->reqPage($_REQUEST['itemid'], $_REQUEST['colorcode']);
		break;
		
	case 'details':
		$dat = $orders->reqDetails();
		
		break;
		
	}
	
	if($isJSON){
		$isJSON = false;
//		$json = new Services_JSON();
		$res = json_encode($dat);
		header("Content-Type: text/javascript; charset=utf-8");
		
		/*
		* $res = $json->encode($dat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		*/
		
		/*
		*	JSONP
		*	$res = $_REQUEST['callback'].'('.$json->encode($dat).')';
		*/
	}
	
	echo $res;
}
?>