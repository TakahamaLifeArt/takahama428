<?php
include $_SERVER['DOCUMENT_ROOT']."/php_libs/conndb.php";
define('_MAX_LIST_LENGTH', 20);
$conn = new Conndb();

// sort
if(isset($_REQUEST['sort'])){
	$sort = $_REQUEST['sort'];
}else{
	$sort = 'post';
}

// レビュー情報取得
$data = $conn->getUserReview($sort);

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
	$paging .= '<p class="pagination-parts"><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$prev.'">前へ</a></p>';
	$paging .= '<ol class="pagination-parts">';
	$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg=1">1</a></li>';
	$paging .= '<li>...</li>';
}else{
	$paging .= '<ol class="pagination-parts">';
}
if($first+$nums<=$last){
	for($i=$first; $i<$first+$nums; $i++){
		if($i!=$curr){
			$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$i.'">'.$i.'</a></li>';
		}else{
			$paging .= '<li><span>'.$curr.'</span></li>';
		}
	}
}else{
	for($i=$first; $i<=$last; $i++){
		if($i!=$curr){
			$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$i.'">'.$i.'</a></li>';
		}else{
			$paging .= '<li><span>'.$curr.'</span></li>';
		}
	}
}
if($i<=$last){
	$paging .= '<li>...</li>';
	$paging .= '<li><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$last.'">'.$last.'</a></li>';
	$paging .= '</ol>';
	$paging .= '<p class="pagination-parts"><a href="'.$_SERVER['SCRIPT_NAME'].'?pg='.$next.'">次へ</a></p>';
}else{
	$paging .= '</ol>';
}

// レビュー
$v = array(0,0,0,0);
for($i=$start; $i<$end; $i++){
	// 総合評価の集計
	$v[0] += $data[$i]['vote_1'];
	$v[1] += $data[$i]['vote_2'];
	$v[2] += $data[$i]['vote_3'];
	$v[3] += $data[$i]['vote_4'];

	// レビューのタグ生成
	$star = getStar($data[$i]['avg']);
	$posted = substr($data[$i]['posted'],0,-3);
	$amount = number_format($data[$i]['amount']);
	$reason = nl2br($data[$i]['reason']);
	$impression = nl2br($data[$i]['impression']);
	$comment = nl2br($data[$i]['staff_comment']);

	// プリント方法
	$prnkeys = explode(',', $data[$i]['printkey']);
	$pnames = array();
	for($t=0; $t<count($prnkeys); $t++){
		$pnames[] = $printname[$prnkeys[$t]];
	}
	$pname = implode('<br>', $pnames);

	// アイテム
	$itemnames = array();
	for($t=0; $t<count($data[$i]['category_id']); $t++){
		$item_name = $data[$i]['itemname'][$t];
		if($data[$i]['category_id'][$t]==0){
			$itemnames[] = $item_name;
		}else{
			$itemnames[] = '<a href="/items/item.php?code='.$data[$i]['item_code'][$t].'" target="_blank">'.$item_name.'</a>';
		}
	}
	$itemname = implode('<br>', $itemnames);

	$review .= <<<EOD
					<div class="list_unit">
						<div class="unit_header">
							<p>{$posted} ご注文のお客様</p>
							<table class="unit_header_info">
								<tbody>
									<tr>
										<td class="lbl">注文アイテム：</td>
										<td>{$itemname}</td>
										<td class="lbl">プリント：</td>
										<td>{$pname}</td>
										<td>枚数： $amount 枚</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="unit_body clearfix">
							<div class="unit_body_left">
								<p><img src="img/{$star}.png" width="114" height="21" alt="">{$data[$i]['avg']}</p>
								<ul class="unit_body_left_inner">
									<li>
										<span class="point">商品の仕上がり</span>
										<span class="star">{$data[$i]['vote_1']}</span>
									</li>
									<li>
										<span class="point">梱包について</span>
										<span class="star">{$data[$i]['vote_2']}</span>
									</li>
									<li>
										<span class="point">スタッフの対応</span>
										<span class="star">{$data[$i]['vote_3']}</span>
									</li>
									<li>
										<span class="point">サイトの使いやすさ</span>
										<span class="star">{$data[$i]['vote_4']}</span>
									</li>
								</ul>
							</div>
							<div class="unit_body_right">
								<ul class="unit_body_right_inner">
									<li>
										<h4>タカハマライフアートを選んだ理由</h4>
										$reason
									</li>
									<li>
										<h4>利用した感想</h4>
										$impression
									</li>
									<li>
										<h4 class="staffcome">スタッフコメント</h4>
										$comment
									</li>
								</ul>
							</div>
						</div>
					</div>
EOD;
}

// 総合評価
$vote = array(
	array('title'=>'商品の仕上がり'),
	array('title'=>'梱包について'),
	array('title'=>'スタッフの対応'),
	array('title'=>'サイトの使いやすさ'),
);
$v = array(0,0,0,0);
for($i=0; $i<$len; $i++){
	// 総合評価の集計
	$v[0] += $data[$i]['vote_1'];
	$v[1] += $data[$i]['vote_2'];
	$v[2] += $data[$i]['vote_3'];
	$v[3] += $data[$i]['vote_4'];
}
for($i=0; $i<count($v); $i++){
	$vote[$i]['ratio'] = round($v[$i]/$len, 1);
	$vote[$i]['img'] = getStar($vote[$i]['ratio']);
	$sub += $vote[$i]['ratio'];
}
$avg['ratio'] = round($sub/4, 1);
$avg['img'] = getStar($avg['ratio']);

?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="" />
		<meta name="keywords" content="" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="website" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>お客様ご利用レビュー　|　オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="css/userreviews.css" media="screen" />
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">

				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<li>お客様ご利用レビュー</li>
				</ul>

				<div class="heading1_wrapper">
					<h1>お客様ご利用レビュー</h1>
					<p class="sub">タカハマライフアートの評判・口コミ</p>
				</div>

				<div id="total" class="clearfix" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="totalRate">
					<h2>現在の総合評価 <small>(<span itemprop="ratingCount"><?php echo number_format($len);?></span>件)</small></h2>
					<div id="total_inner">
						<p id="totalstar"><img src="img/<?php echo $avg['img'];?>_l.png" width="170" height="31" alt="">
							<span itemprop="ratingValue"><?php echo $avg['ratio'];?></span>
						</p>
						<ul id="totalstar_inner">
							<?php
							$li = '';
							for($i=0; $i<count($vote); $i++){
								$li .= '<li>';
								$li .= '<span class="point">'.$vote[$i]['title'].'</span>';
								$li .= '<span class="star"><img src="img/'.$vote[$i]['img'].'.png" width="130" height="33" alt="">'.$vote[$i]['ratio'].'</span>';
								$li .= '</li>';
							}
							echo $li;
							?>
						</ul>
					</div>
				</div>

				<div id="lists">
					<div class="pagenation_set">
						<div class="pagenation_left">
							<span><?php echo number_format($len);?></span>件中
							<?php if($end==0) echo '0'; else echo ($start+1).'～'.$end;?>件を表示
						</div>
						<div class="pagenation_right">
							<div class="pagenation_nav">
								<form name="sortBy" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" enctype="application/x-www-form-urlencoded" method="get">
									並び順：
									<select name="sort" onchange="this.form.submit();">
										<?php
										$opt = '<option value="post">新着順</option><option value="high">評価が高い順</option><option value="low">評価が低い順</option>';
										$opt = preg_replace('/value=\"'.$sort.'\"/', 'value="'.$sort.'" selected="selected"', $opt);
										echo $opt;
										?>
									</select>
								</form>
							</div>

							<div class="pagenation_nav">
								<?php echo $paging;?>
							</div>
						</div>
					</div>

					<div id="list" class="clearfix">
						<?php echo $review;?>
					</div>
					<div class="pagenation_nav">
						<?php echo $paging;?>
					</div>
				</div>
			</div>
		</div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	</body>

	</html>
