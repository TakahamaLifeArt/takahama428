/**
 * Takahama Life Art
 * enquete page
 * log
 * 2018-03-06 項目変更に伴いファイルアップロード機能を追加
 */
	
$(function(){
	'use strict';
	/**
	 * ファイルアップロード
	 */
	
	// Sets the upload status.
	$('#fileupload-table').data('uploaded', 1);


	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload({
		// Uncomment the following to send cross-domain cookies:
		//xhrFields: {withCredentials: true},
		url: '/user/support/data/'
	}).on('fileuploadadd', function(e, data) {

	}).on('fileuploadsubmit', function (e, data) {

	}).on('fileuploadalways', function(e, data){
		var rest = 0,
			idx = 0,
			attach = '',
			files = [],
			names = [];

		// Check all uploads
		$('#fileupload-table').data('uploaded', 0);

		$('#fileupload-table tbody tr').each(function(){
			var self = $(this),
				path = '',
				name = '',
				size = '',
				deleteURL = '';

			// アップロード中
			if (self.is('.template-upload')) {
				if (self.find('.error').text()=="") {
					rest++;
				}
			}

			// アップロードが完了している
			if (self.is('.template-download')) {
				path = self.find('.path').text();
				name = self.find('.name').children().text();
				size = self.find('.size').text();
				deleteURL = self.find('.delete').data('url');

				if (names.indexOf(name)>-1) {
					// 同名のファイルは除外
					self.remove();
				} else {
					names[idx] = name;
					files[idx++] = {'name':name, 'url':path, 'size':size, 'deleteUrl':deleteURL};
					attach += '<input type="hidden" name="uploadfilename" value="'+path+'?auth=admin">';
				}

			}
		});

		// Inputタグ再設定
		$('#uploaded-files').html(attach);

		// All uploads completed
		if (rest==0) {
			$('#fileupload-table').data('uploaded', 1);
		}
	});


	// Enable iframe cross-domain access via redirect option:
	$('#fileupload').fileupload(
		'option',
		'redirect',
		window.location.href.replace(
			/\/[^\/]*$/,
			'/user/cors/result.html?%s'
		)
	);


	$('#fileupload').fileupload('option', {
		url: '/user/support/data/',
		// Enable image resizing, except for Android and Opera,
		// which actually support image resizing, but fail to
		// send Blob objects via XHR requests:
		disableImageResize: /Android(?!.*Chrome)|Opera/
		.test(window.navigator.userAgent),
		maxFileSize: 104857600,	// 100MB
		maxNumberOfFiles: 5,
		acceptFileTypes: /(\.|\/)(ai|jpe?g|png|psd|pdf|zip)$/i,
		messages: {
			maxNumberOfFiles: '一度にアップできる最大数（5個）を超えています',
			acceptFileTypes: 'ファイル形式は、jpeg, png, ai, psd, pdf のみです',
			maxFileSize: 'ファイルサイズは100MBまでです',
			minFileSize: 'File is too small'
		},
		autoUpload: true
	});


	// Upload server status check for browsers with CORS support:
	if ($.support.cors) {
		$.ajax({
			url: '//'+window.location.hostname+'/user/support/data/',
			type: 'HEAD'
		}).fail(function () {
			$('<div class="alert alert-danger"/>')
				.text('Upload server currently unavailable - ' +
					  new Date())
				.appendTo('#fileupload');
		});
	}


	/**
	 * 入力項目の検証
	 */
	eMailer.onValidate('#sendmail', function () {
		/**
		 * Check the upload file
		 * Return true if All uploads are completed. false otherwise.
		 */
		if ($('#fileupload-table').data('uploaded') != 1) {
			$.msgbox('アップロードされていないフィルがあります');
			return false;
		}

		/**
		 * 複数選択可のチェックボックス
		 * @return {bool} 妥当であれば{@code true}を返す
		 */
		if ($('#q3 input[type="checkbox"]:checked').length==0) {
			$.msgbox('Ｑ３に最低１つチェックをお願いします');
			return false;
		}

		return true;
	});


	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		$('#fileupload').submit();
//		$.msgbox('<p>アンケートにお答えいただき、ありがとうございました。<br>またのご利用、心よりお待ち申し上げております。</p>');
	});
	
});
