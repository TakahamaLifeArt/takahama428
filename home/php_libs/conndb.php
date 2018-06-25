<?php
/*
*	Database connection
*	charset     : utf-8
*
*/
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once dirname(__FILE__).'/http.php';

class Conndb extends HTTP {
	
	public function __construct($args=_API){
		parent::__construct($args);
	}
	
	
	/*
	*	消費税率を取得
	*	@curdate		検索する日付（default:今日）
	*	@mode			general(default):2014-05-26より前は内税のため0を返す、industry:消費税率
	*	@return			消費税率
	*/
	public function getSalesTax($curdate='', $mode='general'){
		if(empty($curdate)){
			$curdate = date('Y-m-d');
		}
		$res = parent::request('POST', array('act'=>'salestax', 'curdate'=>$curdate, 'ordertype'=>$mode));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	商品カテゴリー一覧
	*	@return		[id:category_id, code:category_key, name:category_name][...]
	*/
	public function categoryList(){
		$res = parent::request('POST', array('act'=>'category'));
		$data = unserialize($res);
		return $data;
	}
	/**
	 * 商品カテゴリー一覧 - API3
	 *
	 */
	public function categoryListV3(){
		$param = array();
		$endPoint = '/categories/';
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		parent::setURL(_API_3.$endPoint);
		$data = parent::request('GET', $param, $headers);
		parent::setURL(_API);
		return $data;
	}


	/*
	*	商品一覧
	*	@categoryid		カテゴリーID　default: 1
	*	@mode			NULL:default,　'item':第一引数でアイテムIDを渡す場合
	*	@return			[id:item_id, code:item_code, name:item_name, posid:printposition_id][...]
	*/
	public function itemList($categoryid=1, $mode=NULL){
		$res = parent::request('POST', array('act'=>'item', 'categoryid'=>$categoryid, 'mode'=>$mode, 'show_site'=>_SITE));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	サイズ一覧
	*	@id				アイテムID
	*	@colorcode		カラーコード
	*	@mode			id:アイテムID(default),  code:アイテムコード
	*	@return			[id:size_from, name:size_name][...]
	*/
	public function itemSize($id, $colorcode=NULL, $mode='id'){
		$res = parent::request('POST', array('act'=>'size', 'itemid'=>$id, 'colorcode'=>$colorcode, 'mode'=>$mode, 'show_site'=>_SITE));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	商品価格
	*	@id				アイテムID
	*	@mode			id:アイテムID(default), code:アイテムコード
	*	@amount			量販単価の判別 0-149枚、150-299枚、300枚以上
	*	@return			[sizeid:size_from, price_color:price_0, price_white:price_1, maker_color:price_0, maker_white:price_1]
	*/
	public function itemPrice($id, $mode='id', $amount=NULL){
		$res = parent::request('POST', array('act'=>'price', 'itemid'=>$id, 'mode'=>$mode, 'show_site'=>_SITE));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	アイテムコードからIDを返す
	*	@itemcode		アイテムコード
	*	@return			item_id
	*/
	public function itemID($itemcode){
		$res = parent::request('POST', array('act'=>'itemid', 'itemcode'=>$itemcode, 'show_site'=>_SITE));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	アイテムごとに、シルクとデジタル転写で最安のプリント代を計算（プリント位置は1ヵ所）して最安の商品単価から見積り
	*	（商品詳細とシーン別ページ用）
	*	@itemid		アイテムコードの配列
	*	@amount		枚数の配列
	*	@ink		インク数の配列
	*	@pos		プリント位置の配列
	*	@sheetsize	転写のデザインサイズ　default:1
	*	@return		{item_code:['price':見積金額, 'perone':1枚あたり],[...]}　引数に配列以外を設定した時はNULL
	*/
	public function estimateEach($itemcode, $amount, $ink, $pos, $sheetsize='1'){
		$res = parent::request('POST', array('act'=>'estimateeach', 'sheetsize'=>$sheetsize, 'itemcode'=>$itemcode, 'amount'=>$amount, 'ink'=>$ink, 'pos'=>$pos));
		$data = unserialize($res);	
		return $data;
	}
		
	
	/*
	*	プリント位置（絵型）の画像情報を返す
	*	@curitemid		ID
	*	@mode			id:アイテムID(default), code:アイテムコード, pos:プリントポジションID
	*	@return			[プリント位置の絵型を配置するタグのテキストファイルへのパス, 位置名, position_id, ディレクトリ情報][...]
	*/
	public function positionFor($curitemid, $mode='id'){
		$res = parent::request('POST', array('act'=>'position', 'itemid'=>$curitemid, 'mode'=>$mode));
		$data = unserialize($res);
		/*---------------------------------------------------
		*	2013-10-24 
		*	下記2点のパーカーの絵型（フード前なし）の暫定的対応
		*	ID:348	241-cfh 裏起毛プルパーカー
		*	ID:349	242-cfz 裏起毛ジップパーカー
		*/
		
		if($curitemid=='348'){
			$data[0]['item']='parker-non-hood';
		}else if($curitemid=='349'){
			$data[0]['item']='zip-parker-non-hood';
		}
		
		$tmp = array();
		$path = dirname(__FILE__).'/../common/txt/'.$data[0]['category'].'/'.$data[0]['item'].'/*.txt';
		$posid = $data[0]['id'];
		foreach (glob($path) as $filename) {
			$base = basename($filename, '.txt');
			if(strpos($base, 'front')!==false){
				$base_name = '前';
				$tmp[0] = array('filename'=>$filename, 'base_name'=>$base_name, 'posid'=>$posid, 'ppdata'=>$data[0]);
			}elseif(strpos($base, 'back')!==false){
				$base_name = '後';
				$tmp[1] = array('filename'=>$filename, 'base_name'=>$base_name, 'posid'=>$posid, 'ppdata'=>$data[0]);
			}elseif(strpos($base, 'side')!==false){
				$base_name = '横';
				$tmp[2] = array('filename'=>$filename, 'base_name'=>$base_name, 'posid'=>$posid, 'ppdata'=>$data[0]);
			}elseif(strpos($base, 'noprint')!==false){
				$base_name = 'プリントなし';
				$tmp[0] = array('filename'=>$filename, 'base_name'=>$base_name, 'posid'=>$posid, 'ppdata'=>$data[0]);
			}
		}
		
		// 添え字indexの付替え
		ksort($tmp, SORT_NUMERIC);
		foreach($tmp as $index=>$dat){
			$files[] = $dat;
		}
		
		return $files;
	}
	
	
	/*
	*	見積ページ
	************************************************/
	
	/*
	*	価格ごとのサイズ構成を取得してテーブルデータタグを生成
	*	@curitemid		アイテムID
	*	@colormode		白色が白色以外かの指定　white(default), color
	*	@mode			id:アイテムID(default), code:アイテムコード
	*	@return			[[サイズ - サイズ　0,000円～, ...],白色が安い商品はture,[size_id:price,...]]
	*/
	public function priceFor($curitemid, $colormode='white', $mode='id'){
		$res = parent::request('POST', array('act'=>'price', 'itemid'=>$curitemid, 'mode'=>$mode));
		$data = unserialize($res);
		$isSwitch = false;
		if(empty($colormode)) $colormode = 'white';
		foreach($data as $key=>$val){
			$price[$val['sizeid']] = $val['price_'.$colormode];
			if($val['price_white']<$val['price_color']){
				$isSwitch = true;
			}
		}
		
		// 価格ごとにサイズ展開を設定して配列に代入
		$res = parent::request('POST', array('act'=>'size', 'itemid'=>$curitemid, 'mode'=>$mode));
		$size = unserialize($res);
		$rows = array();
		foreach($size as $key=>$val){
			if(empty($size_from)){
				$size_from = $val['name'];
				$size_to = '';
				$minprice = $price[$val['id']];
				$size_id = $val['id'];
			}else if($minprice==$price[$val['id']]){
				$size_to = $val['name'];
			}else{
				if($size_to==''){
					$rows[] = '<th class="sizefrom_'.$size_id.'">'.$size_from.'</th><td>'.number_format($minprice).'円&#65374;'.'</td>';
				}else{
					$rows[] = '<th class="sizefrom_'.$size_id.'">'.$size_from.' &minus; '.$size_to.'</th><td>'.number_format($minprice).'円&#65374;'.'</td>';
				}
				$size_from = $val['name'];
				$size_to = '';
				$minprice = $price[$val['id']];
				$size_id = $val['id'];
			}
		}
		if($size_to==''){
			$rows[] = '<th class="sizefrom_'.$size_id.'">'.$size_from.'</th><td>'.number_format($minprice).'円&#65374;'.'</td>';
		}else{
			$rows[] = '<th class="sizefrom_'.$size_id.'">'.$size_from.' &minus; '.$size_to.'</th><td>'.number_format($minprice).'円&#65374;'.'</td>';
		}
		
		$line ='';
		for($i=0; $i<count($rows); $i++){
			$line .= '<tr>'.$rows[$i].'<td><input type="number" value="0" min="0" class="forNum" /> '.'枚'.'</td></tr>';
		}
		return array($line, $isSwitch, $price);
	}

	
	/*
	*	注文フォーム、商品ページ
	************************************************/
	
	/*
	*	商品名とカラーごとのコード一覧データ
	*	@itemid			アイテムID　default: 1
	*	@return			['name':[アイテムコード:アイテム名], 'category':[カテゴリーキー:カテゴリー名], code:[code:カラー名, ...], size[...], ppid:プリントポジションID]
	*					codeのフォーマットは、「アイテムコード＿カラーコード」　ex) 085-cvt_001
	*/
	public function itemAttr($itemid=1){
		$res = parent::request('POST', array('act'=>'itemattr', 'itemid'=>$itemid, 'show_site'=>_SITE));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	アイテム一覧の商品情報、タグ情報あり
	*	@id				カテゴリID, タグID
	*	@tag			タグの配列
	*	@mode			idの種類 - category(default), tag
	*	@limit			検索レコード数
	*	@return			[商品情報]
	*/
	public function itemOf($id, $tag=array(), $mode="category", $limit=0, $output=''){
		$res = parent::request('POST', array('act'=>'itemof', 'id'=>$id, 'tag'=>$tag, 'mode'=>$mode, 'limit'=>$limit, 'show_site'=>_SITE, 'output'=>$output));
		if ($output === 'json') {
			$data = json_decode($res);
		} else {
			$data = unserialize($res);
		}
		return $data;
	}
	
	
	
	/**
	*	指定したタグ及びカテゴリのアイテムIDを返す
	*	@id			一覧ページの基底ID - カテゴリID, タグID
	*	@tag		タグID
	*	@mode		idの種類 - category(default), tag
	*	@target		検索対象のアイテムIDの配列
	*	@limit		検索レコード数
	*	@curdate	抽出条件に使用する日付(0000-00-00)。NULL:今日(default)
	*	@return		[アイテムID, ...]
	*/
	public function itemIdOf($id, $tag=null, $mode='category', $target=null, $limit=null, $curdate=null){
		$res = parent::request('POST', array('act'=>'itemidof', 'id'=>$id, 'tag'=>$tag, 'mode'=>$mode, 'target'=>$target, 'limit'=>$limit, 'curdate'=>$curdate, 'show_site'=>_SITE));
		$data = unserialize($res);
		//------------------------
		return $data;
	}
	
	
	
	/*
	*	アイテムタグのマスター情報
	*	@id				タグID
	*	@return			{tagid, tag_name, tag_type, tagtype_name, tagtype_key}
	*/
	public function tagInfo($id){
		$res = parent::request('POST', array('act'=>'taginfo', 'id'=>$id));
		$data = unserialize($res);
		return $data;
	}
	
	
	/**
	 * 消費税 - API3
	 * @return 現在の消費税率
	 */
	public function salesTax(){
		$param = array();
		$endPoint = '/taxes/';
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		parent::setURL(_API_3.$endPoint);
		$data = parent::request('GET', $param, $headers);
		parent::setURL(_API);
		return $data;
	}
	
	
	/**
	 * アイテムタグ一覧 - API3
	 * @id		カテゴリID, カテゴリー指定なしの場合は０
	 * @tag		条件絞り込み用の複数のタグIDの配列
	 * @return タグ一覧の配列
	 */
	public function itemTag($id=0, $tag=array()){
		$param = array();
		if (empty($id)) {
			$endPoint = '/itemtags/';
		} else {
			$endPoint = '/itemtags/'.$id;
		}
		if (!empty($tag)){
			for ($i=0; $i<count($tag); $i++) {
				$param['args'][] = $tag[$i];
			}
		}
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
			];
		parent::setURL(_API_3.$endPoint);
		$data = parent::request('GET', $param, $headers);
		parent::setURL(_API);
		return $data;
	}
	
	
	/**
	 * 納期計算 - API3
	 * $baseSec		注文確定日（UNIXタイムスタンプの秒数）{@code 0 は今日}
	 * $workday		作業日数の配列（発送日を含む）
	 * $transport	配送日数（通常は１日、北海道、九州、本州離島、島根隠岐郡は配送に2日）
	 * $extraday	作業日数に加算する日数
	 * @return お届日付情報 {'year','month','day','weekname'}
	 */
	public function delidate($baseSec=0, $workday=array(4), $transport=1, $extraday=0){
		$param = array('args' => array(
				'basesec' => $baseSec,
				'workday' => $workday,
				'transport' => $transport,
				'extraday' => $extraday,
			)
		);
		$endPoint = '/deliveries/';
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		parent::setURL(_API_3.$endPoint);
		$data = parent::request('GET', $param, $headers);
		parent::setURL(_API);
		return $data;
	}
	
	
	/**
	 * カテゴリー別のアイテムランキング - API3
	 * @id		カテゴリID | タグID
	 * @tag		タグの配列
	 * @mode	@idの種類'  category', 'tag'
	 * @sort	並び順
	 * @limit	検索レコード数 {@code length|offset-length}
	 *
	 * @return 商品情報の配列
	 * 			[category_key, category_name, item_id, item_name, item_code, cost, pos_id, maker_id, 
	 * 			oz, colors, i_color_code, i_caption, reviews, sizename_from, sizename_to, range_id, screen_id]
	 */
	public function categoryInfo($id=0, $tag=array(), $mode='category', $sort='popular', $limit=''){
		$param = array();
		if ($mode != 'category') {
			$endPoint = '/categories/0/'.$sort.'/'.$limit;
			$param['args'][] = $id;
		} else {
			$endPoint = '/categories/'.$id.'/'.$sort.'/'.$limit;
		}
		if (!empty($tag)) {
			for ($i=0; $i<count($tag); $i++) {
				$param['args'][] = $tag[$i];
			}
		}
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		parent::setURL(_API_3.$endPoint);
		$data = parent::request('GET', $param, $headers);
		parent::setURL(_API);
		return $data;
	}
	
	
	/*
	*	カテゴリーの商品情報
	*	@categoryid		カテゴリーID
	*	@mode			id:カテゴリーID、code:カテゴリーキー、list:カテゴリーIDで全サイズのリスト
	*	@return			[category_key,item_id,item_name,item_code,size_id,size_from,size_to,colors,cost,pos_id][...]
	*/
	public function categories($categoryid, $mode='id'){
		$res = parent::request('POST', array('act'=>'categories', 'id'=>$categoryid, 'mode'=>$mode ,'curdate'=>$curdate, 'show_site'=>_SITE ));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	商品ページの基本情報（サイズ数、カラー数、最安価格）
	*	@id				アイテムID
	*	@mode			id:アイテムID、code:アイテムコード
	*	@return			{'item_name':item_name, 'sizes':size_count, 'colors':color_count, 'mincost':mincost}
	*/
	public function itemPageInfo($id, $mode='id'){
		$res = parent::request('POST', array('act'=>'itempageinfo', 'id'=>$id, 'mode'=>$mode));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	注文フォーム
	************************************************/
	
	/*
	*	サイズと価格のデータ
	*	@curitemid		アイテムID　default: 1
	*	@colorcode		アイテムカラーコード　default: ''
	*	@return			['id':サイズID, 'name':サイズ名, 'cost':販売価格, 'series':サイズシリーズID][...]
	*/
	public function sizePrice($itemid=1, $colorcode=''){
		$res = parent::request('POST', array('act'=>'sizeprice', 'itemid'=>$itemid, 'colorcode'=>$colorcode));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	シルクとデジタル転写で最安のプリント代合計を返す - API3
	*	@args		['itemid'=>itemid, 'amount'=>amount, 'ink'=>inkcount, 'pos'=>posname][][]
	*	@sheetsize	転写のデザインサイズ　default:1
	*	@return		['tot':プリント代, 'volume':枚数, 'tax':消費税率] 引数に配列以外を設定した時はNULL
	*/
	public function printfee($args, $sheetsize='1'){
		//		$res = parent::request('POST', array('act'=>'printfee', 'sheetsize'=>$sheetsize, 'args'=>$args, 'show_site'=>_SITE));
		//		$data = unserialize($res);
		//		return $data;

		$recommendType = ['silk', 'digit', 'inkjet'];
		$param = [];
		$endPoint = '/items/' . $args['itemid'] . '/details';
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		parent::setURL(_API_3.$endPoint);
		$r = json_decode(parent::request('GET', $param, $headers), true);
		$printable = [];
		$ary = [$r[0]['silk'],$r[0]['digit'],$r[0]['inkjet']];
		for ($i=0; $i<count($ary); $i++){
			if ($ary[$i]===1) $printable[] = $recommendType[$i];
		}

		$param['args'] = json_encode(array(
			'amount' => 0,
			'items' => [$args['itemid'] => $args['amount']],
			'size' => 0,
			'ink' => 1,
			'option' => 0,
			'repeat' => ['silk' => 0, 'digit' => 0, 'emb' => 0],
			'printable' => $printable
		));
		$endPoint = '/printcharges/recommend';
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		parent::setURL(_API_3.$endPoint);
		$data = parent::request('GET', $param, $headers);
		parent::setURL(_API);
		return $data;
	}
	
	
	
	/*
	*	注文ページ
	*	注文内容をデータベースに登録
	*	@args			[data1, data2, data3, ...]
	*	@return			成功：ID　失敗：null
	************************************************/
	public function acceptingorder($args){
		$res = parent::request('POST', array('act'=>'acceptingorder', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	
	/*
	*	資料請求ページ
	*	フォームの内容をデータベースに登録
	*	@args			["requester", "subject", "message", "reqmail", "site_id"]
	*	@return			成功：ID　失敗：null
	************************************************/
	public function requestmail($args){
		$res = parent::request('POST', array('act'=>'requestmail', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	
	/*
	*	商品到着確認後のアンケート結果を
	*	データベースに登録
	*	@args			["requester", "subject", "message", "reqmail", "site_id"]
	*	@return			成功：ID　失敗：null
	************************************************/
	public function setEnquete($args){
		$res = parent::request('POST', array('act'=>'enquete', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	
	/*
	*	アンケートページ生成用データを取得 - API3
	*	@return		{'enquete':{}, 'question':[], 'choice':[]}
	*/
	public function getEnquete() {
		$param = array();
		$endPoint = '/enquetes/forms';
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		parent::setURL(_API_3.$endPoint);
		$data = parent::request('GET', $param, $headers);
		parent::setURL(_API);
		return $data;
	}
	
	
	
	/*
	*	プリント位置画像の相対パスのフォルダー名をを返す
	*	@curitemid		ID
	*	@mode			id:アイテムID(default), code:アイテムコード, pos:プリントポジションID
	*	@return			[id: position_id, category:category_type, item:item_type, pos:position_type]　idがNULLのときは全て
	*/
	public function getPrintPosition($curitemid, $mode='id'){
		$res = parent::request('POST', array('act'=>'position', 'itemid'=>$curitemid, 'mode'=>$mode));
		$data = unserialize($res);
		return $data;
	}
	
	
	// テスト用
//	public function getPattern($posid) {
//		$res = parent::request('POST', array('act'=>'pattern', 'posid'=>$posid));
//		$data = unserialize($res);
//		return $data;
//	}
	
	
	
	
	/*
	*	カスタマーセンター
	************************************************/
	
	/*
	*	ユーザーの存在確認
	*	@args	['email','pass']
	*	@reutrn	OK:{ユーザー情報}　NG:false
	*/
	public function getUser($args) {
		$res = parent::request('POST', array('act'=>'getuser', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	ユーザー情報の取得
	*	@args			[customer.id(default: null)]
	*	@return			[顧客情報]
	************************************************/
	public function getUserList($args=null) {
		$res = parent::request('POST', array('act'=>'getuserlist', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	お届け先情報の取得
	*	@args			[customer.id(default: null)]
	*	@return			[お届け先情報]
	************************************************/
	public function getDeliveryList($args=null) {
		$res = parent::request('POST', array('act'=>'getdeliverylist', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	ユーザーのお届け先の取得
	*	@args	ユーザーID　defult:null
	*	@reutrn	[お届け先情報]
	*/
	public function getDeli($args) {
		$res = parent::request('POST', array('act'=>'getdeli', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	メールアドレスの存在確認
	*	@args			[e-mail]
	*	@return			[顧客情報]
	************************************************/
	public function checkExistEmail($args){
		$res = parent::request('POST', array('act'=>'checkexistemail', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/**
	*	注文履歴を取得（member）
	*	@args		customer ID
	*	@id			受注No.
	*	@shipped	0:全て, 1:未発送, 2:発送済み
	*	@return		[注文情報]
	*/
	public function getOroderHistory($args, $id=0, $shipped=0){
		$res = parent::request('POST', array('act'=>'getorderhistory', 'args'=>$args, 'id'=>$id, 'shipped'=>$shipped));
		$data = unserialize($res);
		return $data;
	}


	/**
	*	製作進行状況の取得（member）
	*	@args		[customer ID, order ID]
	*	@return		[進行状況]
	*/
	public function getProgress($args){
		$res = parent::request('POST', array('act'=>'getprogress', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}


	/*
	*	プリント情報（member）
	*	@args			受注No.
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
	*	@return			[出力情報]
	*/
	public function getPrintform($args){
		$res = parent::request('POST', array('act'=>'getprintform', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	お知らせメールの配信停止処理
	*	@args			{'customer_id', 'cancel'(停止:1)'
	*	@return			成功:true  失敗:false
	************************************************/
	public function unsubscribe($args){
		$res = parent::request('POST', array('act'=>'unsubscribe', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	ユーザー新規登録
	*	@args	['uname','email','pass','uicon','filename']
	*	@reutrn	true:OK　false:NG
	*/
	public function setUser($args) {
		$res = parent::request('POST', array('act'=>'setuser', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}


	/*
	*	ユーザーのお届け先更新
	*	@args	ユーザーID　defult:null
	*	@reutrn OK:{ユーザー情報}　NG:false
	*/
	public function updateDeli($args) {
		$res = parent::request('POST', array('act'=>'updatedeli', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}

	
	/*
	*	ユーザー情報の更新
	*	@args	['userid','uname','email','uicon','filename']
	*	@reutrn	true:OK　false:NG
	*/
	public function updateUser($args) {
		$res = parent::request('POST', array('act'=>'updateuser', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}


	/*
	*	パスワードの変更
	*	@args	['userid','pass']
	*	@reutrn	true:OK　false:NG
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
	*	@reutrn	true:OK　false:NG
	*/
	public function updateAddr($args) {
		$res = parent::request('POST', array('act'=>'updateaddr', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	
	/*
	*	ユーザーネームの重複チェック
	*	@args	[ユーザーネーム, ユーザーID(default: null)]
	*	@reutrn	true:重複　false:新規
	*/
	public function checkExistName($args) {
		$res = parent::request('POST', array('act'=>'checkexistname', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}

	
	/*
	*	イメージ画像表示
	*	@return		イメージ画像
	*/
	public function getDesigned($order_id) {
		$res = parent::request('POST', array('act'=>'showDesignImg', 'order_id'=>$order_id, 'folder'=>'imgfile'));
		$data = json_decode($res);
		return $data;

	}
	
	
	/*
	*	レビュー
	************************************************/
	
	/*
	*	お客様レビュー取得
	*	@args			ソート項目（新着順：post　評価の高い順：high　評価の低い順：low）
	*	@return			[]
	************************************************/
	public function getUserReview($args){
		$res = parent::request('POST', array('act'=>'userreview', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	/*
	*	アイテムレビュー取得
	*	@args			{sort: ソート項目（新着順：posted　評価の高い順：high　評価の低い順：low）,
	*					itemid:	アイテムID}
	*	@return			[]
	************************************************/
	public function getItemReview($args){
		$res = parent::request('POST', array('act'=>'itemreview', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	/*
	*	アイテム詳細ページデータ取得
	*	@args			アイテムコード
	*	@return			[]
	************************************************/
	public function getItemDetail($args){
		$res = parent::request('POST', array('act'=>'itemdetail', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	/*
	*	寸法データ取得
	*	@args			アイテムコード
	*	@return			[]
	************************************************/
	public function getItemMeasure($args){
		$res = parent::request('POST', array('act'=>'itemmeasure', 'args'=>$args));
		$data = unserialize($res);
		return $data;
	}
	
	/*
	*	モデル写真
	*	@cat		categorykey	
	* 	@code		itemcode
	*	@return			[]
	************************************************/
	public function getModelPhoto($cat, $code){
		$res = parent::request('POST', array('act'=>'modelphoto', 'categorykey'=>$cat, 'itemcode'=>$code));
		$data = unserialize($res);
		return $data;
	}
	
	/*
	*	スタイル写真
	*	@cat		categorykey	
	* 	@code		itemcode
	*	@return			[]
	************************************************/
	public function getStylePhoto($cat, $code){
		$res = parent::request('POST', array('act'=>'stylephoto', 'categorykey'=>$cat, 'itemcode'=>$code));
		$data = unserialize($res);
		return $data;
	}


}
?>
