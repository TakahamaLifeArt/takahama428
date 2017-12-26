<?php
require_once $_SERVER['DOCUMENT_ROOT']."/php_libs/conndb.php";
define('_MAX_LIST_LENGTH', 20);

//$features = new ItemFeatures();
$conn = new Conndb();

// sort
if(isset($_GET['sort'])){
	$sort = $_GET['sort'];
}else{
	$sort = 'post';
}

// item id
if(isset($_GET['item'])){
	$item_id = $_GET['item'];
}else{
	$item_id = 4;	// 085-cvt
}

// レビュー情報取得
$data = $conn->getItemReview(array('sort'=>$sort, 'itemid'=>$item_id));

// アイテム情報
//$ic = $features->getInitColor();
if(empty($data)){
	$category_key = 't-shirts';
	$item_name = 'ヘビーウェイトＴシャツ';
	$item_code = '085-cvt';
	$itemname = '<a href="/items/item.php?code='.$item_code.'" target="_blank">'.$item_name.' 085-CVT</a>';
	$anchor = '<a href="/items/item.php?code='.$item_code.'">このアイテムを見る</a>';
	$goto_estimation = '<a href="/items/item.php?code='.$item_code.'#howmuch">見積もり計算</a>';
	$image_path = _IMG_PSS.'items/list/'.$category_key.'/'.$item_code.'/'.$item_code.'_001.jpg';
}else{
	$item = $data[0];
	$item_name = $item['item_name'];
	$item_code = $item['item_code'];
	$itemname = '<a href="/items/item.php?code='.$item_code.'" target="_blank">'.$item_name.' '.strtoupper($item['item_code']).'</a>';
	$anchor = '<a href="/items/item.php?code='.$item_code.'">このアイテムを見る</a>';
	$goto_estimation = '<a href="/items/item.php?code='.$item_code.'#howmuch">見積もり計算</a>';
	$image_path = _IMG_PSS.'items/list/'.$item['category_key'].'/'.$item['item_code'].'/'.$item['item_code'].'_'.$item['i_color_code'].'.jpg';
}

// 商品単価
list($rows, $isSwitch, $cost) = $conn->priceFor($item_id);
	list($sizeid, $mincost) = each($cost);

// プリント方法
$printname = array(
	'silk'=>'シルクスクリーン',
	'trans'=>'カラー転写',
	'digit'=>'デジタル転写',
	'inkjet'=>'インクジェット',
	'cutting'=>'カッティング',
);

// 評価を0.5単位に変換し画像パスを返す
function getStar($args){
	if($args<0.5){
		$r = 'star00';
	}else if($args>=0.5 && $args<1){
		$r = 'star05';
	}else if($args>=1 && $args<1.5){
		$r = 'star10';
	}else if($args>=1.5 && $args<2){
		$r = 'star15';
	}else if($args>=2 && $args<2.5){
		$r = 'star20';
	}else if($args>=2.5 && $args<3){
		$r = 'star25';
	}else if($args>=3 && $args<3.5){
		$r = 'star30';
	}else if($args>=3.5 && $args<4){
		$r = 'star35';
	}else if($args>=4 && $args<4.5){
		$r = 'star40';
	}else if($args>=4.5 && $args<5){
		$r = 'star45';
	}else{
		$r = 'star50';
	}
	return $r;
}

// ページ指定
$len = count($data);
$page = ceil($len/_MAX_LIST_LENGTH);

if(isset($_GET['pg'])){
	if(preg_match('/^[1-9]+$/', $_GET['pg'])){
		$curr = $_GET['pg'];
	}else{
		$curr = 1;
	}
}else{
	$curr = 1;
}

if($len==0){
	$start = 0;
}else if($curr>$page){
	$curr = $page;
	$start = ($page*_MAX_LIST_LENGTH)-_MAX_LIST_LENGTH;
}else{
	$start = ($curr*_MAX_LIST_LENGTH)-_MAX_LIST_LENGTH;
}
$end = $start+_MAX_LIST_LENGTH;
if($end>$len) $end = $len;

// ページングのタグ生成
$prev = $curr-1;
$next = $curr+1;
$last = $page;
$first = 1;
$nums = 5;		// ページ番号を表示する数
							
// スタートのページ番号を設定
if($curr-ceil($nums/2)>0){
	$first = $curr-floor($nums/2);		// 中央をカレントページにする
	
	$rest = $last-$curr-floor($nums/2);	// 右端に最終ページが有るときの表示
	if($rest<0){
		$first+=$rest;
		if($first<1) $first=1;
	}
}

if($first>1){
	$paging .= '<p class="pagination-parts"><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$prev.'&item='.$item_id.'">前へ</a></p>';
	$paging .= '<ol class="pagination-parts">';
	$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg=1&item='.$item_id.'">1</a></li>';
	$paging .= '<li>...</li>';
}else{
	$paging .= '<ol class="pagination-parts">';
}
if($first+$nums<=$last){
	for($i=$first; $i<$first+$nums; $i++){
		if($i!=$curr){
			$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$i.'&item='.$item_id.'">'.$i.'</a></li>';
		}else{
			$paging .= '<li><span>'.$curr.'</span></li>';
		}
	}
}else{
	for($i=$first; $i<=$last; $i++){
		if($i!=$curr){
			$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$i.'&item='.$item_id.'">'.$i.'</a></li>';
		}else{
			$paging .= '<li><span>'.$curr.'</span></li>';
		}
	}
}
if($i<=$last){
	$paging .= '<li>...</li>';
	$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$last.'&item='.$item_id.'">'.$last.'</a></li>';
	$paging .= '</ol>';
	$paging .= '<p class="pagination-parts"><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$next.'&item='.$item_id.'">次へ</a></p>';
}else{
	$paging .= '</ol>';
}

// レビュー
for($i=$start; $i<$end; $i++){
	// レビューのタグ生成
	$star = getStar($data[$i]['vote']);
	$amount = number_format($data[$i]['amount']);
	$text = nl2br($data[$i]['review']);
	
	// プリント方法
	$prnkeys = explode(',', $data[$i]['printkey']);
	$pnames = array();
	for($t=0; $t<count($prnkeys); $t++){
		$pnames[] = $printname[$prnkeys[$t]];
	}
	$pname = implode('<br>', $pnames);
	
	$review .= <<<EOD
		<div id="list" class="clearfix">
			<div class="list_unit">
				<div class="unit_header">
					<table class="unit_header_info">
						<tbody>
							<tr>
								<td class="lbl">プリント：</td>
								<td>{$pname}</td>
								<td>枚数： $amount 枚</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="unit_body clearfix">
					<div class="unit_body_left">
						<p><img src="img/{$star}.png" width="114" height="21" alt="">{$data[$i]['vote']}</p>
					</div>
					<div class="unit_body_right">
						<ul class="unit_body_right_inner">
							<li>{$text}</li>
						</ul>
					</div>
				</div>
			</div>
			
		</div>
EOD;
}

// 総合評価
$v = 0;
for($i=0; $i<$len; $i++){
	$v += $data[$i]['vote'];
}
$avg['ratio'] = round($v/$len, 1);
$avg['img'] = getStar($avg['ratio']);

?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta name="Description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>アイテムレビュー：
			<?php echo strtoupper($item_code).' '.$item_name;?>　|　オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="css/itemreviews.css" media="screen" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	</head>

	<body>


		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">
				<input type="hidden" id="item" value="<?php echo $item_id;?>" />
				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<li>アイテムレビュー：
						<?php echo $item_name;?>
					</li>
				</ul>

				<div id="item_info">
					<div id="item_left">
						<div id="item_image">
							<img id="item_image_m" src="<?php echo $image_path;?>" width="170" height="170">
						</div>
					</div>
					<div id="item_right">
						<h1>アイテムレビュー</h1>
						<h2>
							<?php echo $itemname;?>
						</h2>
						<p id="price">Takahama価格：<span id="price_detail"><?php echo number_format($mincost); ?>円&#65374;/１枚</span></p>
						<div id="rating">
							総合評価
							<span id="totalstar">
							<img src="img/<?php echo $avg['img'];?>_l.png" width="150" height="40" alt=""><?php echo $avg['ratio'];?>
						</span>
							<span id="numof_user">(評価数<?php echo number_format($len);?>)</span>
						</div>
						<div id="btns">
							<p id="ckitem_btn">
								<?php echo $anchor;?>
							</p>
							<p id="estimate_btn">
								<?php echo $goto_estimation;?>
							</p>
						</div>
					</div>
				</div>

				<div id="lists" class="clearfix">
					<div class="pagenation_set">
						<div class="pagenation_left">
							<span><?php echo number_format($len);?></span>件中
							<?php if($end==0) echo '0'; else echo ($start+1).'～'.$end;?>件を表示
						</div>
						<div class="pagenation_right">
							<div class="pagenation_nav">
								並び順：
								<select id="cond_sortby" name="">
							<?php
								$opt = '<option value="post">新着順</option><option value="high">評価が高い順</option><option value="low">評価が低い順</option>';
								$opt = preg_replace('/value=\"'.$sort.'\"/', 'value="'.$sort.'" selected="selected"', $opt);
								echo $opt;
							?>
							</select>
							</div>

							<div class="pagenation_nav">
								<?php echo $paging;?>
							</div>
						</div>
					</div>

					<?php echo $review;?>

				</div>

			</div>
		</div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="../common/js/jquery.js"></script>
		<script type="text/javascript" src="./js/review.js"></script>
	</body>

	</html>
