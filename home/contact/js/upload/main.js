/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
	'use strict';

	// Sets the upload status.
	$('#fileupload-table').data('uploaded', 1);
	
	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload({
		// Uncomment the following to send cross-domain cookies:
		//xhrFields: {withCredentials: true},
		url: '/user/support/data/'
	}).on('fileuploadadd', function(e, data) {

	}).on('fileuploadsubmit', function (e, data) {
//		var isValid = false;
//		if ($('#fileupload').data('confirm')==1) {
//			$('#fileupload').data('confirm', 0);
//			isValid = true;
//		}
//		return isValid;
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
		
//		var attach = "";
//		$('#fileupload-table tbody tr').each(function(){
//			var self = $(this);
//			if (self.is('.template-download')) {
//				var path = self.find('.path').text();
//				attach += '<input type="hidden" name="uploadfilename[]" value="'+path+'">';
//			}
//		});
//		$('#conf_attach').html(attach);
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
});
