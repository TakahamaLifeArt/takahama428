/**
 * Dialog message
 */
$(function(){
	'use strict';

	//	プリント方法
	$('.print_link').on("TAP_EVENT", function () {
		var msg = '';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">シルクスクリーン</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/silk.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">人気NO.1プリント！版画のように、職人が一回一回 手刷りでプリントしていきます。</p>';
		msg += '<div class="print_rec">';
		msg += '<p class=""><img src="/order/img/sp_order_print_clothes.png" width="18px">おすすめ枚数：20枚以上</p>';
		msg += '<p class=""><img src="/order/img/sp_order_print_color.png" width="18px">おすすめ色数：3色以内</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">インクジェット</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/inc.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">何色使っても料金は変わらずに一枚から作成できます。 手触りがよく、柔らかい風合いに仕上がります。</p>';
		msg += '<div class="print_rec">';
		msg += '<p class=""><img src="/order/img/sp_order_print_clothes.png" width="18px">おすすめ枚数：1~10枚</p>';
		msg += '<p class=""><img src="/order/img/sp_order_print_color.png" width="18px">おすすめ色数：フルカラー</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">デジタル転写</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/digi.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">シートに印刷をし、スタンプのように熱で圧着させるプリント方法です。 色の発色が良く、 グラデーションなど細やかな柄の再現に優れています。</p>';
		msg += '<div class="print_rec">';
		msg += '<p class=""><img src="/order/img/sp_order_print_clothes.png" width="18px">おすすめ枚数：30 枚以上</p>';
		msg += '<p class=""><img src="/order/img/sp_order_print_color.png" width="18px">おすすめ色数：フルカラー</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">カッティングプリント</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/cut.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">背番号やゼッケンへのプリントに適しています。一枚一枚デザイン を変更できるので、チームユニフォームのように背番号や名前を 一枚一枚変更したい方におすすめです。</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">刺繍</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/emb.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">糸を使って、布地の表面に文字や絵を表現します。 耐久性にも 優れ、 色落ち、高級感ある表現ができるので、企業 や店舗のユニフォームにおすすめです。</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		$.msgbox(msg, '<h2>プリント方法の説明</h2>');
	});

	//お支払ご利用方法（未使用）
	$('#pop_payment').on("TAP_EVENT", function () {
		var msg = '<h3 class="syousai">銀行振込</h3><hr>';
		msg += '<p>下記の口座にお振込ください。</p>';
		msg += '<p>ご希望の納品日より2日前までにお振込をお願い致します。（土日祝は入金確認ができないのでご注意ください）お振込手数料は、お客様のご負担とさせていただいております。</p>';
		msg += '<dl class="list">';
		msg += '<dt>銀行名</dt>';
		msg += '<dd>三菱東京ＵＦＪ銀行</dd>';
		msg += '<dt>支店名</dt>';
		msg += '<dd>新小岩支店　744</dd>';
		msg += '<dt>口座種別</dt>';
		msg += '<dd>普通</dd>';
		msg += '<dt>口座番号</dt>';
		msg += '<dd>3716333</dd>';
		msg += '<dt>口座名義</dt>';
		msg += '<dd>ユ）タカハマライフアート</dd>';
		msg += '</dl>';

		msg += '<hr><br><h3 class="syousai">代金引換</h3><hr>';
		msg += '代金引換手数料は1件につき&yen;800（税抜）かかります。';
		msg += 'お支払い総額（商品代+送料＋代金引換手数料＋消費税）を配送業者にお支払いください。';
		msg += 'お客様のご都合でお支払い件数が複数になった場合、1件につき&yen;800（税抜）を追加させていただきます。';

		msg += '<hr><br><h3 class="syousai">カード決済</h3><hr>';
		msg += '各種クレジットカードがご利用いただけます。';
		msg += 'ご希望の納品日より2日前までにカード決済手続きをお願い致します。';
		msg += '（土日祝は入金確認ができないのでご注意ください）カード決済システム利用料（5%）は、お客様のご負担とさせていただいております。';
		msg += '弊社の「マイページ」＞「お支払い状況」＞「カード決済のお申し込はこちらから」にて決済が可能です。';
		msg += '<center><p><img width="60%" alt="カード種類" src="./img/card.png"></p></center>';
		$.msgbox(msg);
	});
	
	//	ファイル転送サービス説明を見る
	$('#upload_link').on("TAP_EVENT", function () {
		var msg = '<div class="sale_txt">';
		msg += '<div class="sale_block">';
		msg += '<h3 style="margin-bottom:30px;text-align:center;">下記ボタンからご利用くださいませ</h3>';
		msg += '<p style="display: none;">ファイルアップロードできない場合は、 下記のファイル転送サービスをご利用ください。</p>';
		msg += '<a href="http://up300.net/" target="_blank"><button class="btn btn-success fileinput-button waves-effect waves-light" style="margin:0 auto;display:block;">ファイル転送サービスへ</button></a>';
		msg += '</div>';
		msg += '<div class="sale_block">';
		msg += '<h3>使い方</h3>';
		msg += '<p>①「ファイルを選択」をクリックし、ファイルをアップロード</p>';
		msg += '<center><p><img width="100%" alt="アップロード1" src="./img/design_step_01.jpg"></p></center>';
		msg += '<p>②「ファイルの転送開始する」をクリック</p>';
		msg += '<center><p><img width="100%" alt="アップロード1" src="./img/design_step_02.jpg"></p></center>';
		msg += '<p>③ダウンロードURLをコピー</p>';
		msg += '<center><p><img width="100%" alt="アップロード1" src="./img/design_step_03.jpg"></p></center>';
		msg += '<p>④お問い合わせ、もしくはお申し込みフォームの メッセージ欄にリンクをペースト</p>';
		msg += '<center><p><img width="100%" alt="アップロード1" src="./img/design_step_04_01.jpg"></p></center>';
		msg += '<center><p><img width="100%" alt="アップロード1" src="./img/design_step_04_02.jpg"></p></center>';
		msg += '<p>⑤お問い合わせ・お申し込みを完了させる</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		$.msgbox(msg, '<h2>ファイル転送サービス</h2>');
	});
	
	//	割引　説明を見る
	$('#sale_link').on("TAP_EVENT", function () {
		var msg = '<div class="sale_txt">';
		msg += '<div class="sale_block">';
		msg += '<h3>学割<span class="red_txt">(3%OFF)</span></h3>';
		msg += '<p>学生の方を対象とした割引です。（幼・保・小・中・高・専・短・大・院）</p>';
		msg += '<p class="txt_sml"><span class="red_txt">※</span>ご注文の際に学校名をお伝えください。</p>';
		msg += '</div>';
		msg += '<div class="sale_block">';
		msg += '<h3>写真掲載割<span class="red_txt">(3%OFF)</span></h3>';
		msg += '<p>購入後のお客様アンケートご回答と、商品写真と感想コメントのHP掲載にご協力いただける方を対象とした割引です。</p>';
		msg += '<p class="txt_sml"><span class="red_txt">※</span>商品到着後にマイページからアンケートと写真を送信していただきます。</p>';
		msg += '</div>';
		msg += '<div class="sale_block">';
		msg += '<h3>そのままプリント割<span class="red_txt">(1000円OFF)</span></h3>';
		msg += '<p>お客様のデザインデータを、弊社で修正せずにそのままプリントに使用した場合に適用される割引です。</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		$.msgbox(msg, '<h2>割引の説明</h2>');
	});

	//	お支払い　ご利用方法
	$('.cashflow').on("TAP_EVENT", function () {
		var msg = '<div class="payflow">';
		msg += '<div class="paylist">';
		msg += '<h3>銀行振込</h3>';
		msg += '<p class="payttl">下記の口座にお振込ください。ご希望の納品日より2日前までにお振込をお願い致します。（土日祝は入金確認ができないのでご注意ください）お振込手数料は、お客様のご負担とさせていただいております。</p>';
		msg += '<p class="txt_sml"><span class="red_txt"></span></p>';
		msg += '<div class="print_rec">';
		msg += '<ul>';
		msg += '<li><p class="bldtxt">銀行名<span class="nortxt">三菱UFJ銀行</span></p></li>';
		msg += '<li><p class="bldtxt">支店名<span class="nortxt">新小岩支店 744</span></li>';
		msg += '<li><p class="bldtxt">口座種別<span class="nortxt">普通</span></li>';
		msg += '<li><p class="bldtxt">口座番号<span class="nortxt">3716333</span></li>';
		msg += '<li><p class="bldtxt">口座名義<span class="nortxt">ユ）タカハマライフアート</span></li>';
		msg += '</ul>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="bdr_line"></div>';
		msg += '<div class="paylist">';
		msg += '<h3>代金引換</h3>';
		msg += '<p class="payttl">代金引換手数料は1件につき&yen;800（税抜）かかります。お支払い総額（商品代+送料＋代金引換手数料＋消費税）を配送業者にお支払いください。お客様のご都合でお支払い件数が複数になった場合、1件につき&yen;800（税抜）を追加させていただきます。</p>';
		msg += '</div>';
		msg += '<div class="bdr_line"></div>';
		msg += '<div class="paylist">';
		msg += '<h3>カード決済</h3>';
		msg += '<p class="payttl">ご希望の納品日より2日前までにカード決済手続きをお願い致します。カード決済確認後、商品を発送致します。（土日祝は決済確認ができないのでご注意ください）</p>';
		msg += '<p class="txt_sml"><span class="red_txt">※</span>ご指定の期日までにカード決済手続きをお願い致します。お客様のカード決済が遅れてご指定の納品日に商品の到着が間に合わなかった場合、当方では一切責任を負いかねますのでご了承ください。</p>';
		msg += '<p class="txt_sml"><span class="red_txt">※</span>お支払い期日を過ぎた後、再三の催促・督促にもかかわらず、何のご連絡もなくお支払いのないお客様は、法的手段を含め対応させて頂きます。この場合に発生する手数料等の諸費用、法的手続きにかかった諸費用のすべてを、未払い代金に加算して請求致します。</p>';
		msg += '<div class="cardimg"><img src="  /order/img/card.png" width="100%"></div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		$.msgbox(msg, '<h2>ご利用方法</h2>');
	});

	// ご利用規約
	$('#user_policy').on("TAP_EVENT", function(){
		var msg = '<p>この利用規約（以下、「本規約」といいます。）は、有限会社タカハマライフアート（以下、「当社」といいます。）がこのウェブサイト上で提供するサービス（以下「本サービス」といいます。）の利用条件を定めるものです。登録ユーザーの皆様（以下、「ユーザー」といいます。）には、本規約に従って、本サービスをご利用いただきます。</p>' +
			'<h3>第１条　＜適用＞</h3>' +
			'<p>本規約は、ユーザーと当社との間の本サービスの利用に関わる一切の関係に適用されるものとします。</p>' +
			'<h3>第２条　＜利用登録＞</h3>' +
			'<p>登録希望者が当社の定める方法によって利用登録を申請し、当社がこれを承認することによって、利用登録が完了するものとします。</p>' +
			'<p>当社は、利用登録の申請者に以下の事由があると判断した場合に、利用登録の申請を承認しないことがあり、その理由については一切の開示義務を負わないものとします。</p>' +
			'<p>利用登録の申請に際して虚偽の事項を届け出た場合</p>' +
			'<p>本規約に違反したことがある者からの申請である場合</p>' +
			'<p>その他，当社が利用登録を相当でないと判断した場合</p>' +
			'<h3>第３条　＜ユーザーID及びパスワード管理＞</h3>' +
			'<p>ユーザーは，自己の責任において，本サービスのユーザーIDおよびパスワードを管理するものとします。</p>' +
			'<p>ユーザーは，いかなる場合にも，ユーザーIDおよびパスワードを第三者に譲渡または貸与することはできません。当社は，ユーザーIDとパスワードの組み合わせが登録情報と一致してログインされた場合には，そのユーザーIDを登録しているユーザー自身による利用とみなします。</p>' +
			'<h3>第4条　＜支払い、キャンセルについて＞</h3>' +
			'<p>ユーザーは，本サービス利用の対価として，当社が別途定め，本ウェブサイトに表示する利用料金を，当社が指定する方法により支払うものとします。</p>' +
			'<p>お支払い期日を過ぎた後、再三の催促・督促にもかかわらず、何のご連絡もなくお支払いのないお客様は、法的手段を含め対応させて頂きます。 この場合に発生する手数料等の諸費用、法的手続きにかかった諸費用のすべてを、未払い代金に加算して請求致します。</p>' +
			'<p>注文確定後、変更キャンセルはできかねますが、やむおえない場合、下記の通りキャンセル料をお支払いただきます。</p>' +
			'<h3>【納期プラン別キャンセル料】</h3>' +
			'<ul>' +
			'<li>' +
			'<h5>通常３日仕上げ</h5>' +
			'<p>ご注文当日18:00まで</p>' +
			'<p>1枚200円※1</p>' +
			'<p>翌日午前中まで</p>' +
			'<p>ご注文金額の50%※2</p>' +
			'<p>翌日午後以降</p>' +
			'<p>ご注文金額の100%※2</p>' +
			'</li>' +
			'<li>' +
			'<h5>2日仕上げ</h5>' +
			'<p>ご注文当日18:00まで</p>' +
			'<p>1枚200円※1</p>' +
			'<p>翌日以降</p>' +
			'<p>ご注文金額の100%※2</p>' +
			'</li>' +
			'<li>' +
			'<h5>翌日仕上げ</h5>' +
			'<p>ご注文当日18:00まで</p>' +
			'<p>1枚200円※1</p>' +
			'<p>翌日以降</p>' +
			'<p>ご注文金額の100%※2</p>' +
			'</li>' +
			'<li>' +
			'<h5>当日仕上げ</h5>' +
			'<p>ご注文確定後</p>' +
			'<p>ご注文金額の100%※2</p>' +
			'</li>' +
			'<li>' +
			'<p>※1　アイテム返品手数料として</p>' +
			'<p>※2　プリント作業の進捗により</p>' +
			'</li>' +
			'</ul>' +
			'<h3>第５条　＜禁止事項＞</h3>' +
			'<p>ユーザーは，本サービスの利用にあたり，以下の行為をしてはなりません。</p>' +
			'<p>法令または公序良俗に違反する行為</p>' +
			'<p>犯罪行為に関連する行為</p>' +
			'<p>当社のサーバーまたはネットワークの機能を破壊したり，妨害したりする行為</p>' +
			'<p>当社のサービスの運営を妨害するおそれのある行為</p>' +
			'<p>他のユーザーに関する個人情報等を収集または蓄積する行為</p>' +
			'<p>他のユーザーに成りすます行為</p>' +
			'<p>当社のサービスに関連して，反社会的勢力に対して直接または間接に利益を供与する行為</p>' +
			'<p>その他，当社が不適切と判断する行為</p>' +
			'<h3>第６条　＜本サービスの提供の停止等＞</h3>' +
			'<p>当社は，以下のいずれかの事由があると判断した場合，ユーザーに事前に通知することなく本サービスの全部または一部の提供を停止または中断することができるものとします。</p>' +
			'<ol>' +
			'<li>本サービスにかかるコンピュータシステムの保守点検または更新を行う場合</li>' +
			'<li>地震，落雷，火災，停電または天災などの不可抗力により，本サービスの提供が困難となった場合</li>' +
			'<li>コンピュータまたは通信回線等が事故により停止した場合</li>' +
			'<li>その他，当社が本サービスの提供が困難と判断した場合</li>' +
			'</ol>' +
			'<p>当社は，本サービスの提供の停止または中断により，ユーザーまたは第三者が被ったいかなる不利益または損害について，理由を問わず一切の責任を負わないものとします。</p>' +
			'<h3>第７条　＜利用制限及び登録抹消＞</h3>' +
			'<p>当社は，以下の場合には，事前の通知なく，ユーザーに対して，本サービスの全部もしくは一部の利用を制限し，またはユーザーとしての登録を抹消することができるものとします。</p>' +
			'<ol>' +
			'<li>本規約のいずれかの条項に違反した場合</li>' +
			'<li>登録事項に虚偽の事実があることが判明した場合</li>' +
			'<li>その他，当社が本サービスの利用を適当でないと判断した場合</li>' +
			'</ol>' +
			'<p>当社は，本条に基づき当社が行った行為によりユーザーに生じた損害について，一切の責任を負いません。</p>' +
			'<h3>第８条　＜免責事項＞</h3>' +
			'<p>当社の債務不履行責任は，当社の故意または重過失によらない場合には免責されるものとします。</p>' +
			'<p>当社は，何らかの理由によって責任を負う場合にも，通常生じうる損害の範囲内かつ有料サービスにおいては代金額（継続的サービスの場合には1か月分相当額）の範囲内においてのみ賠償の責任を負うものとします。</p>' +
			'<p>当社は，本サービスに関して，ユーザーと他のユーザーまたは第三者との間において生じた取引，連絡または紛争等について一切責任を負いません。</p>' +
			'<h3>第９条　＜サービス内容変更等＞</h3>' +
			'<p>当社はユーザーに通知すること開く、本サービスの内容を変更しまた本サービスの提供を中止できるものとし、これによってユーザーに生じた損害について一切の責任を負いません。</p>' +
			'<h3>第１０条　＜利用規約の変更＞</h3>' +
			'<p>当社は、必要と判断した場合には、ユーザーに通知することなくいつでも本規約を変更することができるものとします。</p>' +
			'<h3>第１１条　＜通知または連絡＞</h3>' +
			'<p>ユーザーと当社との間の通知または連絡は、当社の定める方向によって行うものとします。</p>' +
			'<h3>第１２条　＜権利義務の譲渡の禁止＞</h3>' +
			'<p>ユーザーは、当社の書面による事前の承諾なく、利用規約上の地位または本規約に基づく権利もしくは義務を第三者に譲渡し、または担保に供することはできません。</p>' +
			'<h3>第１３条　＜準拠法・裁判管轄＞</h3>' +
			'<p>本規約の解釈にあたっては，日本法を準拠法とします。</p>' +
			'<p>本サービスに関して紛争が生じた場合には，当社の本店所在地を管轄する裁判所を専属的合意管轄とします。</p>' +
			'<h3>第１４条　＜加工・商品について＞</h3>' +
			'<p>弊社責任における不良の発生は、無条件で良品との交換いたします。（但し商品ごとの微妙な色合いの差や数ミリ～センチ程度の位置差、プリントずれ等による不良扱いはお受けできません。)</p>' +
			'<p>弊社から発送する商品は十分な検品をしておりますが、商品到着後はお客様ご自身での検品もお願いいたします。万一サイズ・本体色・数量がご注文内容と異なった場合は、商品到着後7日以内にお知らせ願います。正規内容との不足分を直ちに制作、補填いたします。</p>' +
			'<p>誤送品は着払いにて弊社にご返送下さい。特にウェアのサイズについては事前にお客様のほうでご確認のうえ、ご購入下さい。オリジナルプリントＴシャツなど、受注生産の場合は原則として納品後のサイズ交換は出来かねますのでご注意願います。（オリジナル加工のため、加工後のカラー・サイズの交換、クレームなどはお受けできませんのでご了承下さい。）</p>' +
			'<p>既成商品のサイズ規格について、2～3センチ程度までの縫製誤差はご容赦願います。 商品の画像は、できる限り実際の商品に近づけた状態で掲載をしておりますが お客様のモニターの設定により、色味に違いが発生してしまう場合もございますので予めご了承ください。</p>' +
			'<p>弊社では、ご製作いただいた商品に対する営業損害・売上補償に関しましては、一切ご対応を致しかねますので予めご了承くださいませ。なお、お客様のご都合で変更・交換される場合は発生費用をご負担いただきます。追加も同様とします。</p>' +
			'<h3>第１５条　＜在庫について＞</h3>' +
			'<p>当サイトの商品は、メーカーからの仕入れの関係上、在庫状況は日々変動しております。定期的に在庫状況を確認しておりますが、売り切れの場合はご容赦ください。</p>' +
			'<p>ご注文フォームより受付後に、在庫切れが判明した場合、担当者より連絡致しますので、 内容変更やキャンセル等のご指示をお願い致します。</p>' +
			'<h2>プライバシーポリシー</h2>' +
			'<h3>＜個人情報保護方針＞</h3>' +
			'<p>本プライバシーポリシーは、当社が行う各種サービスにおいて、お客様の個人情報もしくはそれに準ずる情報を取り扱う際に、＜タカハマライフアート＞ (以下、「当社」)が遵守する方針を示したものです。</p>' +
			'<p>当社は、個人情報の重要性を認識し、個人情報を保護することが社会的責務であると考え、個人情報に関する法令及び社内規程等を遵守し、当社で取扱う個人情報の取得、利用、管理を適正に行います。</p>' +
			'<h3>＜個人情報とは＞</h3>' +
			'<p>プライバシー情報のうち「個人情報」とは、個人情報保護法にいう「個人情報」を指すものとし、生存する個人に関する情報であって、当該情報に含まれる氏名、生年月日、住所、電話番号、連絡先その他の記述等により特定の個人を識別できる情報を指します。</p>' +
			'<p>プライバシー情報のうち「履歴情報および特性情報」とは、上記に定める「個人情報」以外のものをいい、ご利用いただいたサービスやご購入いただいた商品、ご覧になったページや広告の履歴、お客様が検索された検索キーワード、ご利用日時、ご利用の方法、ご利用環境、郵便番号や性別、職業、年齢、お客様のIPアドレス、クッキー情報、位置情報、端末の個体識別情報などを指します。</p>' +
			'<h3>＜プライバシー情報の収集方法＞</h3>' +
			'<p>当社は、お客様が利用登録をする際に氏名、生年月日、住所、電話番号、メールアドレス、銀行口座番号などの個人情報をお尋ねすることがあります。</p>' +
			'<p>また、お客様と提携先などとの間でなされたお客様の個人情報を含む取引記録や、決済に関する情報を当社の提携先（情報提供元、広告主、広告配信先などを含みます。以下、｢提携先｣といいます。）などから収集することがあります。</p>' +
			'<p>当社は、お客様について、利用したサービスやソフトウエア、購入した商品、閲覧したページや広告の履歴、検索した検索キーワード、利用日時、利用方法、利用環境（携帯端末を通じてご利用の場合の当該端末の通信状態、利用に際しての各種設定情報なども含みます）、IPアドレス、クッキー情報、位置情報、端末の個体識別情報などの履歴情報および特性情報を、お客様が当社や提携先のサービスを利用しまたはページを閲覧する際に収集します。</p>' +
			'<h3>＜クッキーについて＞</h3>' +
			'<p>Cookieとは、当社がWEBを通じてお客様がウェブページを利用した際に、閲覧履歴や入力内容などを、お客様のコンピュータにファイルとして保存しておく仕組みです。</p>' +
			'<p>当社は、これを利用してお客様のブラウザを認識し、次回のご利用時までカート内の商品を保管するなどの機能を提供することができます。また、当社の広告の配信を委託するYahoo! JAPAN、Googleを含む第三者はCookie を使用して、当社への過去のアクセス情報に基づいて広告を配信します。</p>' +
			'<p>cookie機能の無効を設定する方法は、ほとんどのブラウザの 「ヘルプ」機能に説明がありますのでご確認ください。ただし、当社のサービスの一部や、重要な機能を活用していただくために、cookieを受け付ける設定にしておくことをおすすめします。cookieの利用を拒否した場合、カートに商品を追加できない、注文画面に進めないなどのエラーが生じる可能性があります。</p>' +
			'<p>当社では、より良いサービスをご提供するためにクッキーを使用しておりますが、クッキーによってお客様ご自身を特定し、識別することはできませんので、お客様個人を特定する情報は一切含まれておりません。</p>' +
			'<p>また、個人を特定できるようなデータ（名、姓、電話番号、住所など）を意図的に保存することは決してありません。当社の運営するメディアにおいては、たとえ特定の個人を識別することができなくとも、クッキー及びＩＰアドレス情報を利用する場合には、その目的と方法を開示してまいります。</p>' +
			'<h3>＜アクセス解析ツールについて＞</h3>' +
			'<p>当社では、Googleによるアクセス解析ツール「Googleアナリティクス」を利用しています。このGoogleアナリティクスはトラフィックデータの収集のためにCookieを使用しています。</p>' +
			'<p>このトラフィックデータは匿名で収集されており、個人を特定するものではありません。この機能はCookieを無効にすることで収集を拒否することが出来ますので、お使いのブラウザの設定をご確認ください。この規約に関して、詳しくは<a href="https://www.google.com/analytics/terms/jp.html" target="_blank">こちら</a>をご確認ください。</p>' +
			'<h3>＜個人情報の取得と利用目的＞</h3>' +
			'<p>氏名、住所、連絡先などを商品のお申込時にWEBのフォームにて入力していただくことで、当社が収集しております。また、当社は、お客様からご提供いただく情報を以下の目的の範囲内において、当社が提供するサービスに利用します。お客様の同意なく、情報の収集、目的外の利用を行うことはありません。</p>' +
			'<p>・メールマガジン、DM、各種お知らせ等の配信などで、特価提供、最新情報、その他の新サービスについてお知らせするため。</p>' +
			'<p>・お客様に連絡をするためにメールアドレスを利用する場合やお客様に商品を送付する際に必要に応じて連絡するため。</p>' +
			'<p>・お客様の承諾、申込みに基づく、本サービス利用企業、提携企業、団体等への個人情報の提供のため。</p>' +
			'<p>・本サービスの改善、新規サービスの開発およびマーケティングのため。</p>' +
			'<p>・キャンペーン、アンケート、取材等の実施のため。</p>' +
			'<p>・本サービスに関するご意見、お問い合わせ内容の確認・回答のため。</p>' +
			'<p>・利用規約等で禁じている行為などの調査のため。</p>' +
			'<p>・お客様に自分の登録情報の閲覧や修正、利用状況の閲覧を行っていただくために、氏名、住所、連絡先、支払方法などの登録情報、利用されたサービスや購入された商品、およびそれらの代金などに関する情報を表示するため。</p>' +
			'<p>・お客様の本人確認を行うために、氏名、生年月日、住所、電話番号、銀行口座番号、配達証明付き郵便の到達結果などの情報を利用するため。</p>' +
			'<p>・お客様に代金を請求するために、購入された商品名や数量、利用されたサービスの種類や期間、回数、請求金額、氏名、住所などの支払に関する情報などを利用するため。</p>' +
			'<p>・お客様の利便性を高めるため、再度同一の内容を入力させないため</p>' +
			'<p>・当社に登録されている情報を入力画面に表示させたり、お客様のご指示に基づいて配送サービスや、決済サービス（提携先が提供するものも含みます）を使用するため。</p>' +
			'<p>・代金の支払の遅滞、第三者に損害を発生させるなど、本サービスの利用規約に違反したお客様や、不正・不当な目的でサービスを利用しようとするお客様の利用をお断りするために、利用態様、氏名や住所など個人を特定するための情報を利用するため。</p>' +
			'<p>・お客様からのお問い合わせに対応するため。</p>' +
			'<p>・お客様へ当社サービスを提供するため。</p>' +
			'<p>・本人のご了解がある場合。</p>' +
			'<p>・お客様ごとにカスタマイズされたサービスをご利用いただくため。</p>' +
			'<p>・お問い合わせやお申込み、資料請求など個人情報を利用する場面において、発生する通信エラーなど、問題のトラブルシューティングを行うため。</p>' +
			'<p>・上記の利用目的に付随する目的。</p>' +
			'<h3>＜個人情報の管理＞</h3>' +
			'<p>当社は、個人情報の正確性及び安全性を確保するために、情報セキュリティ対策をはじめとする安全対策を実施し、個人情報への不正アクセス、紛失、破壊、改ざん及び漏えい等の予防に努めます。</p>' +
			'<p>お客様の情報のセキュリティ確保のために、当社は、SSL （Secure Socket Layer）という仕組みを使って、入力された情報を暗号化します。通信を暗号化することで第三者による盗聴・改ざんを防ぎます。</p>' +
			'<p>事故が発生した場合に備えて、証拠を保全しその原因が追求できるような体制を構築するとともに、万一事故が発生した場合でも迅速かつ適切に対処して、事故の再発防止等、その是正のため最大限の努力をいたします。</p>' +
			'<p>また当社は、お客様からご提供いただいた情報の管理について、以下を徹底します。</p>' +
			'<p>1)　情報の正確性の確保</p>' +
			'<p>お客様からご提供いただいた情報については、常に正確かつ最新の情報となるよう努めます。</p>' +
			'<p>2)　安全管理措置</p>' +
			'<p>当社は、組織的な個人情報の管理については、社内規定による厳重に取扱い方法を規定し、それに基づいた取扱いを徹底しています。</p>' +
			'<p>3)　従業者の監督</p>' +
			'<p>当社は、当社の規程に基づき、個人情報取扱い規程の厳格な運用を徹底しています。</p>' +
			'<p>4)　保存期間と廃棄</p>' +
			'<p>お客様からご提供いただいた情報については、保存期間を設定し、保存期間終了後は廃棄します。また、保存期間内であっても、不要となった場合にはすみやかに廃棄します。</p>' +
			'<h3>＜第三者提供の有無＞</h3>' +
			'<p>個人情報保護法上提供が認められている場合、当社は、次に掲げる場合を除いて、あらかじめお客様の同意を得ることなく、第三者に個人情報を提供することはありません。ただし、個人情報保護法その他の法令で認められる場合を除きます。</p>' +
			'<h3>＜ご本人の照会＞</h3>' +
			'<p>お客様がご本人の個人情報の照会、修正、削除などをご希望される場合には、ご本人であることを確認の上、対応させていただきます。</p>' +
			'<h3>＜個人情報の開示・訂正・利用停止等＞</h3>' +
			'<p>当社は、本人から個人情報の開示を求められたときは、本人に対し、遅滞なくこれを開示します。ただし、開示することにより次のいずれかに該当する場合は、その全部または一部を開示しないこともあり、開示しない決定をした場合には、その旨を遅滞なく通知します。</p>' +
			'<p>（1）本人または第三者の生命，身体，財産その他の権利利益を害するおそれがある場合</p>' +
			'<p>（2）当社の業務の適正な実施に著しい支障を及ぼすおそれがある場合</p>' +
			'<p>（3）その他法令に違反することとなる場合</p>' +
			'<p>前項の定めにかかわらず、履歴情報および特性情報などの個人情報以外の情報については、原則として開示いたしません。</p>' +
			'<p>お客様からご提供いただいた情報の詳細については、以下の手続きにていつでもご確認することができます。</p>' +
			'<p>また、情報の内容の訂正や利用停止等についても、お客様の申請に基づき、速やかに処理します。</p>' +
			'<p>1)　個人情報取扱事業者の名称</p>' +
			'<p>有限会社タカハマライフアート</p>' +
			'<p>本社所在地：〒124-0025 葛飾区西新小岩3-14-26</p>' +
			'<p>代表者氏名：高濱　猛</p>' +
			'<p>2)　保有個人データの訂正等</p>' +
			'<p>ご提供いただいた情報に訂正が必要な場合は、<a href="https://www.takahama428.com/user/my_menu.php" target="_blank">マイページ</a>にログインして頂き、お客様情報の編集より、情報の更新を行うことができます</p>' +
			'<p>3)　保有個人データの利用停止等</p>' +
			'<p>ご提供いただいた情報の利用停止、情報のご提供の停止、ご提供いただいた情報の削除等については、<a href="https://www.takahama428.com/contact/" target="_blank">お問い合わせ</a>より、手続きを行うことができます。その際に、メッセージ欄に個人データの利用停止の旨を記載し、送信してください。</p>' +
			'<p>4)　手数料</p>' +
			'<p>上記の手続きには、手数料等は発生いたしません。</p>' +
			'<h3>＜プライバシーポリシーの変更＞</h3>' +
			'<p>当社は、個人情報に関して適用される日本の法令を遵守するとともに、本ポリシーの内容を適宜見直しその改善に努めます。本ポリシーの内容は、お客様に通知することなく、変更することができるものとします。</p>' +
			'<p>当社が別途定める場合を除いて、変更後のプライバシーポリシーは，本ウェブサイトに掲載したときから効力を生じるものとします。修正された最新のプライバシーポリシーは常に本ページにて開示されます。</p>' +
			'<h3>＜著作権＞</h3>' +
			'<p>当社のWEBサイトに掲載されている全てのコンテンツは、当社が所有しています。書面による許可なく、個人的な目的以外で使用することは禁止されています。</p>' +
			'<h3>＜免責事項＞</h3>' +
			'<p>当社からリンクやバナーなどによって他のサイトに移動された場合、移動先サイトで提供される情報、サービス等について一切の責任を負いません。</p>' +
			'<p>当社のコンテンツ・情報につきまして、可能な限り正確な情報を掲載するよう努めておりますが、誤情報が入り込んだり、情報が古くなっていることもございます。当社に掲載された内容によって生じた損害等の一切の責任を負いかねますのでご了承ください。</p>' +
			'<p>当社は、暗号化処理等（SSL）を行い厳重に保管・管理し個人情報の保護に十分な注意を払っておりますが、情報の漏洩、紛失、第三者による改ざん等の防止の保証いたしません。よって、これにより発生したお客様または第三者の損害については一切の責任を負いません。</p>' +
			'<p>当社は、お客様から提供された連絡先の誤り、変更等により生じた損害については、一切の責任を負わないものとします。</p>' +
			'<p>当サイトで会員登録を行ったメールアドレス及びパスワードについて、お客様自身の管理不十分による第三者の利用等に起因する損害等に関しては、当社は一切の責任を負いません。</p>' +
			'<h3>＜問い合わせ先＞</h3>' +
			'<p>本サービス、又は個人情報の取扱いに関しては、下記の窓口まで電話または E メール、WEBのお問い合わせフォームにてご連絡ください。</p>' +
			'<p>有限会社タカハマライフアート</p>' +
			'<p>電話番号： 0120-130-428</p>' +
			'<p>E メールアドレス： info@takahama428.com</p>' +
			'<p>お問い合わせページ：<a href="https://www.takahama428.com/contact/" target="_blank">https://www.takahama428.com/contact/</a></p>' +
			'<h3>＜改訂＞</h3>' +
			'<p>平成 10 年 6 月 23 日 策定</p>' +
			'<p>平成 29 年 4 月 9 日 改訂</p>' +
			'<p>以上</p>';
		
		$.dialogBox(msg, '<h2>利用規約について</h2>', '同意する', '閉じる').then(function(){
			$('#agree').prop('checked', true);
			$('#order').prop('disabled', false);
		});
	});

	//	3回入力してもログインできない際に表示（未使用）
	$('＃forgot').click(function () {
		var msg = '<div class="pass_forget">';
		msg += '<p>こちらから再発行をおこなってください。</p>';
		msg += '<button class="pop_btn btn waves-effect waves-light">再発行</button>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		msg += '</div>';
		$.msgbox(msg, '<h2>パスワードを忘れた方へ</h2>');
	});

	//	会員ランク（未使用）
	$('#bronze').on("TAP_EVENT", function () {
		var msg = '<div class="cust_rank_b">';
		msg += '<div class="rank_img"><img src="/order/img/flow/sp_customer_rank_bronze.png" width="100%"></div>';
		msg += '<div class="rank_inner">合計金額から</div>';
		msg += '<p class="rank_discount">3%OFF</p>';
		msg += '<p class="cust_info">お客様は<span class="bro">ブロンズ</span>会員です。</p>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		msg += '</div>';
		$.msgbox(msg, '<h2>会員ランク特典</h2>');
	});
	$('#silver').on("TAP_EVENT", function () {
		var msg = '<div class="cust_rank_s">';
		msg += '<div class="rank_img"><img src="/order/img/flow/sp_customer_rank_silver.png" width="100%"></div>';
		msg += '<div class="rank_inner">合計金額から</div>';
		msg += '<p class="rank_discount">5%OFF</p>';
		msg += '<p class="cust_info">お客様は<span class="sil">シルバー</span>会員です。</p>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		msg += '</div>';
		$.msgbox(msg, '<h2>会員ランク特典</h2>');
	});
	$('#gold').on("TAP_EVENT", function () {
		var msg = '<div class="cust_rank_g">';
		msg += '<div class="rank_img"><img src="/order/img/flow/sp_customer_rank_gold.png" width="100%"></div>';
		msg += '<div class="rank_inner">合計金額から</div>';
		msg += '<p class="rank_discount">7%OFF</p>';
		msg += '<p class="cust_info">お客様は<span class="gol">ゴールド</span>会員です。</p>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		msg += '</div>';
		$.msgbox(msg, '<h2>会員ランク特典</h2>');
	});

	//	カレンダーの当日特急の納期を選択した場合の表示
	$('#ex_form').on("TAP_EVENT", function () {
		var msg = '<div class="date_ex">';
		msg += '<h3><i class="fa fa-exclamation-triangle red_mark" aria-hidden="true"></i>「当日特急プランについて」</h3>';
		msg += '<p class="txt_sml"><span class="red_mark">カレンダーで選択できないお日にちは当日特急プランとなります。ご希望の方は恐れ入りますが、当日特急フォームからお問い合わせください。</span></p>';
		msg += '<p class="ttltxt">当日特急プランご利用</p>';
		msg += '<p class="ttltxt">3つの条件</p>';
		msg += '<div class="date_list">';
		msg += '<ul>';
		msg += '<li class="date_txt"><p class="txt_sml"><span class="red_mark">当日12時まで</span>にお電話で<span class="red_mark">注文確定</span></p></li>';
		msg += '<li class="date_txt"><p class="txt_sml">085-CVT Tシャツ白色と黒色(Sサイズ&#65374;XLサイズ)522-FT タオルの白色(フリーサイズ)<span class="red_mark">2つの商品のみ対応</span></p></li>';
		msg += '<li class="date_txt"><p class="txt_sml">注文確定後、<span class="red_mark">すぐに入金</span></p><p>商品の発送は入金確認後になりますので、お早めのご入金をお願い致します。</p>';
		msg += '<p class="txt_sml"><span class="red_mark">※</span>詳しくはスタッフまでお問合わせください。</p>';
		msg += '</li>';
		msg += '</ul>';
		msg += '</div>';
		msg += '<p class=""><img src="../order/img/flow/sp_order_cart_express_item.png" width="100%"></p>';
		msg += '<div class="btn_box">';
		msg += '<a href="/order/express/#overtime" target="_blank" class="btn btn-info">当日特急フォームへ</a>';
		msg += '</div>';
		msg += '</div>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		$.msgbox(msg);
	});

})