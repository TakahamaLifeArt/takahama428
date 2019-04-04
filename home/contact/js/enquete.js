/**
 * Takahama Life Art
 * enquete page
 * log
 * 2018-03-06 項目変更に伴いファイルアップロード機能を追加
 * 2019-03-14 ファイルアップロード機能を更新
 * 2019-04-02 Edgeのみ進捗バーの表示を切り替える
 */
	
$(function(){
	'use strict';

	/**
	 * 入力項目の検証
	 */
	eMailer.onValidate('#sendmail', function () {
		// アップロードファイル
		let dlToken = JSON.parse(sessionStorage.getItem('dl_token')),
			downURL = document.getElementById('deownload_link'),
			store = $.getSessStorage('attach'),
			fileName = '';

		if (Object.keys(Object(store)).length > 0) {
			$.each(store, function(upid, fName){
				fileName += fName + "\r\n";		// form submission value
			});
		}
		document.getElementById('filename').value = fileName;
		eMailer.onChanged('#filename');

		downURL.value = '';
		if (Object.keys(Object(dlToken)).length > 0) {
			downURL.value = 'https://www.alesteq.com/itemmanager/files/uploader/' + dlToken[0];
		}
		eMailer.onChanged('#deownload_link');

		return true;
	});


	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		$('#fileupload').submit();
	});
	
	
	/**
	 * submit
	 */
	$('#send_button').on('click', function() {
		$('#sendmail').click();
	});

	// Edgeのみ進捗バーの表示を切り替える
	if (window.navigator.userAgent.toLowerCase().indexOf('edge') !== -1) {
		$('#file-uploader .progress').css('position', 'relative').append('<div class="edge_progress">アップロード中...</div>')
	}
});
