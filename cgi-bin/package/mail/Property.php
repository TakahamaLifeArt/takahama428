<?php
/**
 * メール定義ファイル
 * charset "UTF-8"
 * @author (c) 2014 ks.desk@gmail.com
 * log	2017-10-14 created
 */
declare(strict_types=1);
namespace package\mail;

$https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0 ||
	!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
$protocol = ($https ? 'https://' : 'http://');
$full_url = $protocol.(!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
	(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
															 ($https && $_SERVER['SERVER_PORT'] === 443 ||
															  $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT'])));
define('_DOMAIN', $full_url);
define('_MY_NAME', 'タカハマライフアート');
define('_INFO_EMAIL', 'info@takahama428.com');
define('_ORDER_EMAIL', 'order@takahama428.com');
define('_REQUEST_EMAIL', 'request@takahama428.com');
define('_ESTIMATE_EMAIL', 'estimate@takahama428.com');

define('_TEST_EMAIL', 'test@takahama428.com');

define('_OFFICE_TEL', '03-5670-0787');
define('_OFFICE_FAX', '03-5670-0730');
define('_TOLL_FREE', '0120-130-428');

class Property {
	
	protected $_mailFoot = '';
	
	public function __construct() {
		$this->setMailFoot();
	}
	
	
	/**
	 * メール本文のフッターを設定
	 */
	private function setMailFoot() {
		$txt .= "\n\n※ご不明な点やお気づきのことがございましたら、ご遠慮なくお問い合わせください。\n";
		$txt .= "■営業時間　10:00 - 18:00　　■定休日：　土日祝\n\n";
		$txt .= "━ タカハマライフアート ━━━━━━━━━━━━━━━━━━━━━━━\n\n";
		$txt .= "　Phone：　　"._OFFICE_TEL."\n";
		$txt .= "　E-Mail：　　"._INFO_EMAIL."\n";
		$txt .= "　Web site：　"._DOMAIN."/\n";
		$txt .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
		$this->_mailFoot = $txt;
	}
}
?>