<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/package/mail/Mailer.php';
use package\mail\Mailer;

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

$conndb = new Conndb(_API_U);

if($_SERVER['REQUEST_METHOD']!='POST'){
	setToken();
}else{
	chkToken();
	$customerNumber = strtoupper($me['cstprefix']).str_pad($me['number'], 6, '0', STR_PAD_LEFT);

	//お届け先情報を取得
	$deli = $conndb->getDeli($me['id']);

	if(isset($_POST['profile'])){
		$mailBody = array(
			'title' => 'ユーザー情報の変更',
			'顧客ID' => $customerNumber,
			'旧顧客名' => $me['customername'],
			'新顧客名' => $_POST['uname'],
			'旧フリガナ' => $me['customerruby'],
			'新フリガナ' => $_POST['ukana'],
		);
		$args = array(
			'profile'=>true,
			'uname'=>$_POST['uname'],
			//'email'=>$_POST['email'],
			'ukana'=>$_POST['ukana'],
			'userid'=>$_POST['userid']
		);
		$err = update_user($args);
		if(!empty($err)){
			$hide = 'style="display:none;"';
			$show = 'style="display:block;"';
		}else{
			$hide = '';
			$show = '';
		}
	}else if(isset($_POST['mypass'])){
		$mailBody = array(
			'title' => 'パスワードの変更',
			'顧客ID' => $customerNumber,
		);
		$args = array(
			'userid'=>$_POST['userid'],
			'pass'=>$_POST['pass'],
			'passconf'=>$_POST['passconf']
		);
		$err = update_pass($args);
	}else if(isset($_POST['myaddr'])){
		$mailBody = array(
			'title' => '住所の変更',
			'顧客ID' => $customerNumber,
			'旧住所' => $me['zipcode'].' '.$me['addr0'].$me['addr1'].$me['addr2'],
			'旧TEL' => $me['tel'],
			'新住所' => $_POST['zipcode'].' '.$_POST['addr0'].$_POST['addr1'].$_POST['addr2'],
			'新TEL' => $_POST['tel'],
		);
		$args = array(
			'userid'=>$_POST['userid'],
			'zipcode'=>$_POST['zipcode'],
			'addr0'=>$_POST['addr0'],
			'addr1'=>$_POST['addr1'],
			'addr2'=>$_POST['addr2'],
			'tel'=>$_POST['tel']
		);
		$err = update_addr($args);
	}else if(isset($_POST['mydeli'])){
		$mailBody = array(
			'title' => 'お届け先の変更',
			'顧客ID' => $customerNumber,
			'旧お届け先' => $deli[0]['organization'],
			'旧住所' => $deli[0]['delizipcode'].' '.$deli[0]['deliaddr0'].$deli[0]['deliaddr1'].$deli[0]['deliaddr2'],
			'旧会社部門' => $deli[0]['deliaddr3'].''.$deli[0]['deliaddr4'],
			'旧TEL' => $deli[0]['delitel'],
			'新お届け先' => $_POST['organization'],
			'新住所' => $_POST['delizipcode'].' '.$_POST['deliaddr0'].$_POST['deliaddr1'].$_POST['deliaddr2'],
			'新会社部門' => $_POST['deliaddr3'].''.$_POST['deliaddr4'],
			'新TEL' => $_POST['delitel'],
		);
		$args = array(
			'userid'=>$_POST['userid'],
			'deliid'=>$_POST['deliid'],
			'organization'=>$_POST['organization'],
			'delizipcode'=>$_POST['delizipcode'],
			'deliaddr0'=>$_POST['deliaddr0'],
			'deliaddr1'=>$_POST['deliaddr1'],
			'deliaddr2'=>$_POST['deliaddr2'],
			'deliaddr3'=>$_POST['deliaddr3'],
			'deliaddr4'=>$_POST['deliaddr4'],
			'delitel'=>$_POST['delitel']
		);
		$err = update_deli($args,'a');
	}else if(isset($_POST['mydeli1'])){
		$mailBody = array(
			'title' => 'お届け先の変更',
			'顧客ID' => $customerNumber,
			'旧お届け先' => $deli[1]['organization'],
			'旧住所' => $deli[1]['delizipcode'].' '.$deli[1]['deliaddr0'].$deli[1]['deliaddr1'].$deli[1]['deliaddr2'],
			'旧会社部門' => $deli[1]['deliaddr3'].''.$deli[1]['deliaddr4'],
			'旧TEL' => $deli[1]['delitel'],
			'新お届け先' => $_POST['organization'],
			'新住所' => $_POST['delizipcode'].' '.$_POST['deliaddr0'].$_POST['deliaddr1'].$_POST['deliaddr2'],
			'新会社部門' => $_POST['deliaddr3'].''.$_POST['deliaddr4'],
			'新TEL' => $_POST['delitel'],
		);
		$args = array(
			'userid'=>$_POST['userid'],
			'deliid'=>$_POST['deliid1'],
			'organization'=>$_POST['deli1organization'],
			'delizipcode'=>$_POST['deli1zipcode'],
			'deliaddr0'=>$_POST['deli1addr0'],
			'deliaddr1'=>$_POST['deli1addr1'],
			'deliaddr2'=>$_POST['deli1addr2'],
			'deliaddr3'=>$_POST['deli1addr3'],
			'deliaddr4'=>$_POST['deli1addr4'],
			'delitel'=>$_POST['deli1tel']
		);
		$err = update_deli($args,'b');
	}

	// ユーザー情報の変更を通知
	if(empty($err)){
		$mail = new Mailer();
		$mail->setMailBody($mailBody);
		$sendTo = array($_POST['send-to']);
		$result = $mail->send($_POST['mail-subject'], $sendTo);
	}

	// ユーザー情報の再取得
	$me = checkLogin();
	if(!$me){
		jump(_DOMAIN);
	}
}


// ユーザー情報を設定
$u = $conndb->getUserList($me['id']);
$username = $me['customername'];
$userkana = $me['customerruby'];
$email = $u[0]['email'];

//お届け先情報を再度取得
$deli = $conndb->getDeli($me['id']);
?>
<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="早い！Tシャツでオリジナルを作成するならタカハマへ！タカハマライフアートのログイン画面です。メールアドレスとパスワードを入れてください。マイページからご注文履歴などをご確認することができます。ログインにする為のパスワードをお忘れの方はこちら。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>お客様情報変更 | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_account.css" />
		
		<style>
			.lightbox {
				display: none;
			}

		</style>
		
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner">
						<div class="pagetitle">
							<h1>お客様情報変更</h1>
						</div>
					</div>
				</div>

				<form class="section" name="prof" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" onSubmit="return false;">
					<table class="form_table me" id="profile_table">
						<tfoot>
							<tr>
								<td colspan="2">
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
									<input type="hidden" name="profile" value="1">
									<input type="hidden" name="mail-subject" value="ユーザー情報の変更">
									<input type="hidden" name="send-to" value="info@takahama428.com">
									<p class="view" <?php echo $hide; ?>><input type="button" value="編集" class="edit_profile"></p>
									<p class="edit" <?php echo $show; ?>><span class="ok_button" data-featherlight="#fl1">更新</span><span class="cancel_button">Cancel</span></p>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th class="ttl_left"><h2>お客様情報</h2></th>
							</tr>
							<tr>
								<th>お客様番号</th>
							</tr>
							<tr>
								<td><p class="view">K012345</p></td>
							</tr>
							<tr>
								<th>お名前<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td>
									<p class="view" <?php echo $hide; ?>>
										<?php echo $username;?>
									</p>
									<p class="edit" <?php echo $show; ?>><input type="text" name="uname" value="<?php echo $username;?>"></p>
									<ins class="err"> <?php echo $err['uname']; ?></ins>
								</td>
							</tr>
							<tr>
								<th>フリガナ</th>
							</tr>
							<tr>
								<td>
									<p class="view" <?php echo $hide; ?>>
										<?php echo $userkana;?>
									</p>
									<p class="edit" <?php echo $show; ?>><input type="text" name="ukana" value="<?php echo $userkana;?>"></p>
								</td>
							</tr>

							<tr>
								<th>電話番号</th>
							</tr>
							<tr>
								<td>
									<p class="view" <?php echo $hide; ?>>
										<?php echo $userkana;?>
									</p>
									<p class="edit" <?php echo $show; ?>><input type="text" name="ukana" value="<?php echo $userkana;?>"></p>
								</td>
							</tr>

							<tr>
								<th>メールアドレス</th>
							</tr>
							<tr>
								<td>
									<p class="view" <?php echo $hide; ?>>
										<?php echo $email;?>
									</p>
									<p class="edit" id="mail_addr" <?php echo $show; ?>>
										<?php echo $email;?>
									</p>
									<ins class="err"> <?php echo $err['email']; ?></ins>
								</td>
							</tr>



						</tbody>
					</table>
				</form>

				<form class="section" name="pass" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
					<table class="form_table me" id="pass_table">
						<tfoot>
							<tr>
								<td colspan="2">
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
									<input type="hidden" name="mypass" value="1">
									<input type="hidden" name="mail-subject" value="ユーザー情報の変更">
									<input type="hidden" name="send-to" value="info@takahama428.com">
									<p><span class="ok_button" data-featherlight="#fl1">更新</span><span class="cancel_button">Cancel</span></p>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th class="ttl_left"><h2>パスワードの変更</h2></th>
							</tr>
							<tr>
								<th>パスワード</th>
							</tr>
							<tr>
								<td><input type="password" name="pass" value=""><br><ins class="err"> <?php echo $err['pass']; ?></ins></td>
							</tr>
							<tr>
								<th>パスワード確認用</th>
							</tr>
							<tr>
								<td><input type="password" name="passconf" value=""><br><ins class="err"> <?php echo $err['passconf']; ?></ins></td>
							</tr>
						</tbody>
					</table>
				</form>


				<form class="section" name="addr" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
					<table class="form_table addr" id="addr_table">
						<ins class="err"> <?php echo $err['addr']; ?></ins>
						<tfoot>
							<tr>
								<td colspan="2">
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
									<input type="hidden" name="myaddr" value="1">
									<input type="hidden" name="mail-subject" value="ユーザー情報の変更">
									<input type="hidden" name="send-to" value="info@takahama428.com">
									<p><span class="ok_button" data-featherlight="#fl1">更新</span><span class="cancel_button">Cancel</span></p>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th class="ttl_left"><h2>住所の変更</h2></th>
							</tr>
							<tr>
								<th>〒郵便番号<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="zipcode" class="forZip" id="zipcode1" value="<?php echo $u[0]['zipcode']; ?>" onChange="AjaxZip3.zip2addr(this,'','addr0','addr1');" />
									<ins class="err"> <?php echo $err['zipcode']; ?></ins></td>
							</tr>
							<tr>
								<th>都道府県<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="addr0" id="addr0" value="<?php echo $u[0]['addr0']; ?>" maxlength="4" />
									<br><ins class="err"> <?php echo $err['addr0']; ?></ins></td>
							</tr>
							<tr>
								<th>住所１<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="addr1" id="addr1" value="<?php echo $u[0]['addr1']; ?>" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" />
									<br><ins class="err"> <?php echo $err['addr1']; ?></ins></td>
							</tr>
							<tr>
								<th>住所２<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="addr2" id="addr1" value="<?php echo $u[0]['addr2']; ?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" />
									<br><ins class="err"> <?php echo $err['addr2']; ?></ins></td>
							</tr>
							<tr>
								<th>電話番号<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="tel" id="tel" class="forPhone" value="<?php echo $u[0]['tel']; ?>" />
									<br><ins class="err"> <?php echo $err['tel']; ?></ins></td>
							</tr>
						</tbody>
					</table>
				</form>

				<form class="section" name="deli" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
					<table class="form_table deli" id="deli_table">
						<ins class="err"> <?php echo $err['a_deliaddr']; ?></ins>
						<tfoot>
							<tr>
								<td colspan="2">
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
									<input type="hidden" name="mydeli" value="1">
									<input type="hidden" name="deliid" value="<?php echo $deli[0]['id'];?>">
									<input type="hidden" name="mail-subject" value="ユーザー情報の変更">
									<input type="hidden" name="send-to" value="info@takahama428.com">
									<p><span class="ok_button" data-featherlight="#fl1">更新</span><span class="cancel_button">Cancel</span></p>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th class="ttl_left"><h2>お届先1の変更</h2></th>
							</tr>
							<tr>
								<th>お届先<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="organization" id="organization" value="<?php echo $deli[0][" organization "]; ?>" maxlength="30" class="restrict" />
									<br><ins class="err"> <?php echo $err['a_organization']; ?></ins></td>
							</tr>
							<tr>
								<th>〒郵便番号<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="delizipcode" class="forZip" id="zipcode1" value="<?php echo $deli[0][" delizipcode "]; ?>" onChange="AjaxZip3.zip2addr(this,'','deliaddr0','deliaddr1');" />
									<ins class="err"> <?php echo $err['a_delizipcode']; ?></ins></td>
							</tr>
							<tr>
								<th>都道府県<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deliaddr0" id="addr0" value="<?php echo $deli[0][" deliaddr0 "]; ?>" maxlength="4" />
									<br><ins class="err"> <?php echo $err['a_deliaddr0']; ?></ins></td>
							</tr>
							<tr>
								<th>住所１<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deliaddr1" id="addr1" value="<?php echo $deli[0][" deliaddr1 "]; ?>" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" />
									<br><ins class="err"> <?php echo $err['a_deliaddr1']; ?></ins></td>
							</tr>
							<tr>
								<th>住所２<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deliaddr2" id="addr1" value="<?php echo $deli[0][" deliaddr2 "];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" />
									<br><ins class="err"> <?php echo $err['a_deliaddr2']; ?></ins></td>
							</tr>
							<tr>
								<th>会社・部門１</th>
							</tr>
							<tr>
								<td><input type="text" name="deliaddr3" id="addr1" value="<?php echo $deli[0][" deliaddr3 "];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
							</tr>
							<tr>
								<th>会社・部門２</th>
							</tr>
							<tr>
								<td><input type="text" name="deliaddr4" id="addr1" value="<?php echo $deli[0][" deliaddr4 "];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
							</tr>
							<tr>
								<th>電話番号<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="delitel" id="tel" class="forPhone" value="<?php echo $deli[0][" delitel "] ?>"/>
									<br><ins class="err"> <?php echo $err['a_delitel']; ?></ins></td>
							</tr>
							<tr></tr>
						</tbody>
					</table>
				</form>

				<form class="section" name="deli1" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
					<table class="form_table deli1" id="deli1_table">
						<ins class="err"> <?php echo $err['b_deliaddr']; ?></ins>
						<tfoot>
							<tr>
								<td colspan="2">
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
									<input type="hidden" name="deliid1" value="<?php echo $deli[1]['id'];?>">
									<input type="hidden" name="mydeli1" value="1">
									<input type="hidden" name="mail-subject" value="ユーザー情報の変更">
									<input type="hidden" name="send-to" value="info@takahama428.com">
									<p><span class="ok_button" data-featherlight="#fl1">更新</span><span class="cancel_button">Cancel</span></p>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th class="ttl_left"><h2>お届先2の変更</h2></th>
							</tr>
							<tr>
								<th>お届先<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deli1organization" id="deli1organization" value="<?php echo $deli[1][" organization "]; ?>" maxlength="30" class="restrict" />
									<br><ins class="err"> <?php echo $err['b_deli1organization']; ?></ins></td>
							</tr>
							<tr>
								<th>〒郵便番号<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deli1zipcode" class="forZip" id="zipcode1" value="<?php echo $deli[1][" delizipcode "]; ?>" onChange="AjaxZip3.zip2addr(this,'','deli1addr0','deli1addr1');" />
									<ins class="err"> <?php echo $err['b_delizipcode']; ?></ins></td>
							</tr>
							<tr>
								<th>都道府県<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deli1addr0" id="addr0" value="<?php echo $deli[1][" deliaddr0 "]; ?>" maxlength="4" />
									<br><ins class="err"> <?php echo $err['b_deliaddr0']; ?></ins></td>
							</tr>
							<tr>
								<th>住所１<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deli1addr1" id="addr1" value="<?php echo $deli[1][" deliaddr1 "]; ?>" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" />
									<br><ins class="err"> <?php echo $err['b_deliaddr1']; ?></ins></td>
							</tr>
							<tr>
								<th>住所２<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deli1addr2" id="addr1" value="<?php echo $deli[1][" deliaddr2 "];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" />
									<br><ins class="err"> <?php echo $err['b_deliaddr2']; ?></ins></td>
							</tr>
							<tr>
								<th>会社・部門１</th>
							</tr>
							<tr>
								<td><input type="text" name="deli1addr3" id="addr1" value="<?php echo $deli[1][" deliaddr3 "];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
							</tr>
							<tr>
							<tr>
								<th>会社・部門２</th>
							</tr>
							<tr>
								<td><input type="text" name="deli1addr4" id="addr1" value="<?php echo $deli[1][" deliaddr4 "];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
							</tr>
							<tr>
								<th>電話番号<span class="fontred">※</span></th>
							</tr>
							<tr>
								<td><input type="text" name="deli1tel" id="tel" class="forPhone" value="<?php echo $deli[1][" delitel "] ?>"/>
									<br><ins class="err"> <?php echo $err['b_delitel']; ?></ins></td>
							</tr>
						</tbody>
					</table>
				</form>
				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<a href="./my_menu.php"><div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left"></i>戻る</div></a>
				</div>
			</div>
		</div>
		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>
		
		<div class="lightbox" id="fl1">

			<div class="modal-content">
				<div class="modal_window">
					<h2>お客様情報の変更を保存しました</h2>
					<button type="button" class="on_button">マイページトップへ戻る</button>
				</div>
			</div>
		</div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script type="text/javascript" src="./js/account.js"></script>
		<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	</body>

</html>
