<?php
/*
 *	File_name    : fontcolrs.php
 *	Description  : font and inkcolor information class.
 				   for fontcolor.php
 *	The date     : 2012.03.15
 */

class Fontcolors{
	private $hash = array('en'=>array('basic','art','pop','impact','sports','others'),
						  'ja'=>array('basic','brush','pop','retro','others')
						);
	private $fonttype = array('basic'=>'基本','art'=>'アート','pop'=>'ポップ','impact'=>'インパクト','sports'=>'スポーツ','others'=>'その他','brush'=>'純和風','retro'=>'レトロ');
	private $fontname = array(
		'AMOSB___'=>'アモス　ボールド',
		'AMOSBI__'=>'アモス　ボールドイタリック',
		'AMOSEB__'=>'アモス　EXボールド',
		'AMOSEBI_'=>'アモス　EXボールドイタリック',
		'AMOSEI__'=>'アモス　EXイタリック',
		'AMOSEN__'=>'アモス　EX',
		'AMOSI___'=>'アモス　イタリック',
		'AMOSN___'=>'アモス',
		'AMOSTB__'=>'アモス　THINボールド',
		'AMOSTBI_'=>'アモス　THINボールドイタリック',
		'AMOSTI__'=>'アモス　THINイタリック',
		'AMOSTN__'=>'アモス　THIN',
		'C018016D'=>'クーパー',
		'CAVEMAN_'=>'ケイヴマン',
		'DEFECAFO'=>'ディフェカ',
		'FATASSFI'=>'ファット',
		'GRAFFITI'=>'グラフィティー',
		'jaggyfries'=>'ジャギー',
		'american-bold'=>'アメリカン',
		'american-med'=>'ミディアム',
		'HORSERADISH'=>'ホースラディッシュ',
		'Plain Germanica'=>'ジャーマニカ',
		'amsterdam'=>'アムステルダム',
		'Daft Font'=>'ダフト',
		'Megadeth'=>'メガデス',
		'MISFITS_'=>'ミスフィッツ',
		'NITEMARE'=>'ナイトメアー',
		'RUFA'=>'ルーファ',
		'renaissance'=>'ルネッサンス',
		'WREXHAM_'=>'レクハム',
		'AMAZB___'=>'アメイズ　ボールド',
		'AMAZDI__'=>'アメイズD　イタリック',
		'AMAZDZ__'=>'アメイズD　ボールドイタリック',
		'AMAZI___'=>'アメイズ　イタリック',
		'AMAZR___'=>'アメイズ',
		'AMAZZ___'=>'アメイズ　ボールドイタリック',
		'AppleGaramond'=>'アップル',
		'AppleGaramond-Bold'=>'アップル　ボールド',
		'AppleGaramond-BoldItalic'=>'アップル　ボールドイタリック',
		'AppleGaramond-Light'=>'アップル　ライト',
		'AppleGaramond-LightItalic'=>'アップル　ライトイタリック',
		'MarketingScript'=>'マーケティング',
		'MarketingScript-Shadow'=>'マーケティング　シャドウ',
		'Anderson'=>'アンダーソン',
		'CloisterBlack'=>'クリスター',
		'Army Condensed'=>'アーミーCondense',
		'Army Expanded'=>'アーミーEx',
		'Army Thin'=>'アーミーThin',
		'Army Wide'=>'アーミーWide',
		'Army'=>'アーミー',
		'ENGLB___'=>'イングランド　ボールド',
		'ENGLBI__'=>'イングランド　ボールドイタリック',
		'ENGLI___'=>'イングランド　イタリック',
		'ENGLN___'=>'イングランド',
		'judasc__'=>'アイロン',
		'allstar'=>'オールスター',
		'COLLEGEB'=>'カレッジ',
		'DEFTONE'=>'デフトーン',
		'varsity_regular'=>'バーシティ',
		'ARCADEPI'=>'アーケデピックス',
		'NIRVANA'=>'ニルバーナ',
		'vintage'=>'ヴィンテージ',
		'Yahoo'=>'ヤフー',
		'GREMSB__'=>'グレムリン　ボールド',
		'GREMSBI_'=>'グレムリン　ボールドイタリック',
		'GREMSI__'=>'グレムリン　イタリック',
		'GREMSN__'=>'グレムリン',
		'LCD2B___'=>'LCD　ボールド',
		'LCD2L___'=>'LCD　ライト',
		'LCD2N___'=>'LCD',
		'LCD2U___'=>'LCD　ウルトラ',
		'DFGOTC'=>'極太ゴシック',
		'DFKAI9'=>'極太楷書',
		'DFMINC'=>'極太明朝',
		'CRBajoka-R'=>'バジョカ',
		'DCKGMC'=>'籠',
		'DCYSM7'=>'寄席',
		'DFSGYO5'=>'祥南行書',
		'KswGoryuNew'=>'豪龍',
		'samurai'=>'さむらい',
		'SMODERN'=>'昭和モダン',
		'DFCRS9'=>'クラフト墨',
		'DFMRGC'=>'極太丸ゴシック',
		'DFMRM9'=>'まるもじ',
		'nipple'=>'ニップル',
		'DFRULE7'=>'流麗',
		'DFSHT7'=>'しんてん',
		'DFSOGE7'=>'そうげい'
	);
	private $fontnote = array(
		'FATASSFI'=>'大文字のみ（小文字なし）　カッティングシートOK',
		'DEFECAFO'=>'大文字のみ（小文字なし、＆など記号なし）',
		'jaggyfries'=>'小文字のみ（大文字なし）',
		'HORSERADISH'=>'カッティングシートOK',
		'LCD2N___'=>'大文字のみ（小文字なし）',
		'Yahoo'=>'大文字のみ（小文字なし）　カッティングシートOK',
		'GREMSN__'=>'大文字のみ（小文字なし）',
		'allstar'=>'大文字のみ（小文字なし）　カッティングシートOK',
		'COLLEGEB'=>'大文字のみ（小文字なし）　カッティングシートOK',
		'varsity_regular'=>'大文字のみ（小文字なし）',
		'Army'=>'大文字のみ（小文字なし）',
		'Anderson'=>'大文字のみ（小文字なし）　カッティングシートOK',
		'judasc__'=>'大文字のみ（小文字なし）',
		'amsterdam'=>'大文字のみ（小文字なし）　カッティングシートOK',
		'RUFA'=>'大文字のみ（小文字なし）',
		'Megadeth'=>'大文字のみ（小文字なし）　カッティングシートOK',
		'MISFITS_'=>'大文字のみ（小文字なし）',
		'Daft Font'=>'小文字のみ（大文字なし）',
		'nipple'=>'ひらがな、カタカナ、英数字のみ（漢字なし）　カッティングシートOK',
		'renaissance'=>'小さい文字には不向きです',
		'AppleGaramond'=>'カッティングシートOK',
		'WREXHAM_'=>'カッティングシートOK',
		'AMOSN___'=>'カッティングシートOK',
		'C018016D'=>'カッティングシートOK',
		'american-bold'=>'カッティングシートOK',
		'DEFTONE'=>'カッティングシートOK',
		'vintage'=>'カッティングシートOK',
		'DFGOTC'=>'小さい文字には不向きです。　カッティングシートOK',
		'DFKAI9'=>'カッティングシートOK',
		'DCYSM7'=>'小さい文字には不向きです。　カッティングシートOK',
		'DCKGMC'=>'小さい文字には不向きです。　カッティングシートOK',
		'SMODERN'=>'カッティングシートOK',
		'DFCRS9'=>'カッティングシートOK',
		'DFMRGC'=>'小さい文字には不向きです。　カッティングシートOK',
		'DFMRM9'=>'カッティングシートOK',
		'DFRULE7'=>'カッティングシートOK',
		'DFSHT7'=>'カッティングシートOK',
		'DFSOGE7'=>'小さい文字には不向きです。　カッティングシートOK'
	);
	
	public function __construct(){
	}
	
	public function getHash(){
		return $this->hash;
	}
	public function getFonttype(){
		return $this->fonttype;
	}
	public function getFontname(){
		return $this->fontname;
	}
	public function getFontnote(){
		return $this->fontnote;
	}
	

	/**
	*	インクのコードとインク名
	*
	*	@return			[コード番号] = インク名
	*/
	public static function getInkcolors(){
    	$inkcolors['c21'] = 'ホワイト';
	    $inkcolors['c22'] = 'ブラック';
	    $inkcolors['c23'] = 'ダークグレー';
	    $inkcolors['c24'] = 'ライトグレー';
	    $inkcolors['c25'] = 'ラディッシュ';
	    $inkcolors['c26'] = 'レッド';
	    $inkcolors['c27'] = 'ホットピンク';
	    $inkcolors['c28'] = 'ライトピンク';
	    $inkcolors['c29'] = 'オレンジ';
	    $inkcolors['c30'] = 'サンフラワー';
	    $inkcolors['c31'] = 'イエロー';
	    $inkcolors['c32'] = 'ダークグリーン';
	    $inkcolors['c33'] = 'グリーン';
	    $inkcolors['c34'] = 'イエローグリーン';
	    $inkcolors['c35'] = 'ネイビー';
	    $inkcolors['c36'] = 'ブルー';
	    $inkcolors['c37'] = 'サックス';
	    $inkcolors['c38'] = 'パープル';
	    $inkcolors['c39'] = 'ダークブラウン';
	    $inkcolors['c40'] = 'ライトブラウン';
	    $inkcolors['c41'] = 'シルバー';
	    $inkcolors['c42'] = 'ゴールド';
	    $inkcolors['c43'] = 'クリーム';
	    $inkcolors['c44'] = 'リフレックスブルー';
	    $inkcolors['c45'] = '蛍光イエロー';
	    $inkcolors['c46'] = '蛍光オレンジ';
	    $inkcolors['c47'] = '蛍光ピンク';
	    // $inkcolors['c48'] = '蛍光ブルー';
	    $inkcolors['c49'] = '蛍光グリーン';
	    $inkcolors['c50'] = 'ゴールドイエロー';
	    $inkcolors['c51'] = 'ワインレッド';
	    $inkcolors['c52'] = 'バイオレット';
	    $inkcolors['c53'] = 'オーシャン';
	    $inkcolors['c54'] = 'オリーブ';
	    $inkcolors['c55'] = 'アプリコット';
	    $inkcolors['c56'] = 'ラベンダー';
	    $inkcolors['c57'] = 'エメラルドグリーン';
	    $inkcolors['c58'] = 'グラスグリーン';
	    $inkcolors['c59'] = 'ライム';
	    $inkcolors['c60'] = 'パステルイエロー';
	    $inkcolors['c61'] = 'フレッシュ';
	    $inkcolors['c62'] = 'ライラック';
	    $inkcolors['c63'] = 'ミントグリーン';
	    $inkcolors['c64'] = 'ペールグリーン';
	    $inkcolors['c65'] = 'ベージュ';
	    $inkcolors['c66'] = 'ストロー';
	    $inkcolors['c67'] = 'サーモンピンク';
	    $inkcolors['c68'] = 'ピンク';
	    $inkcolors['c69'] = 'ラベンダーグレイ';
	    $inkcolors['c70'] = 'グリーンティ';
	    $inkcolors['c71'] = 'ショッキングピンク';
    	
    	return $inkcolors;
    }
	
	
	/**
	*	カッティングシートのコードとインク名
	*
	*	@return			[コード番号] = インク名
	*/
	public static function getCuttingcolors(){
    	$cuttingcolors['401-white'] = 'ホワイト';
    	$cuttingcolors['402-gold'] = 'ゴールド';
    	$cuttingcolors['403-black'] = 'ブラック';
    	$cuttingcolors['404-golden-yellow'] = 'ゴールデンイエロー';
    	$cuttingcolors['405-orange'] = 'オレンジ';
    	$cuttingcolors['406-red'] = 'レッド';
    	$cuttingcolors['408-light-blue'] = 'ライトブルー';
    	$cuttingcolors['409-royal-blue'] = 'ロイヤルブルー';
    	$cuttingcolors['410-dark-green'] = 'ダークグリーン';
    	$cuttingcolors['412-navy-blue'] = 'ネイビーブルー';
    	$cuttingcolors['413-lemon-yellow'] = 'レモンイエロー';
    	$cuttingcolors['423-silver'] = 'シルバー';
    	$cuttingcolors['428-pink'] = 'ピンク';
    	$cuttingcolors['432-fluo-pink'] = '蛍光ピンク';
    	$cuttingcolors['440-pastel-orange'] = 'パステルオレンジ';
    	$cuttingcolors['444-pastel-pink'] = 'パステルピンク';
    	$cuttingcolors['451-fushia'] = 'フーシャ';
    	$cuttingcolors['455-apple-green'] = 'アップルグリーン';
    	$cuttingcolors['457-apricot'] = 'アプリコット';
    	$cuttingcolors['459-plum'] = 'プラム';
    	$cuttingcolors['470-lilac'] = 'ライラック';
    /*	$cuttingcolors['610-jeans'] = 'ジーンズ';
    	$cuttingcolors['620-leopard'] = 'レオパード';
    	$cuttingcolors['630-cobra'] = 'コブラ';
    	$cuttingcolors['640-army'] = 'アーミー';
    	$cuttingcolors['660-brown-leather'] = 'ブラウンレザー';
    	$cuttingcolors['661-red-leather'] = 'レッドレザー';
    	$cuttingcolors['665-red-tartan'] = 'レッドタータン';
    	$cuttingcolors['2201-perfothan-white'] = 'パーフォタンホワイト';
    	$cuttingcolors['2202-perfothan-gold'] = 'パーフォタンゴールド';
    	$cuttingcolors['2203-perfothan-black'] = 'パーフォタンブラック';   */
    	
    	
    	return $cuttingcolors;
    }
}
?>
