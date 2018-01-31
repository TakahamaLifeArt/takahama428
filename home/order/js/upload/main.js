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

	// 一括アップロード
	$('#fileupload .fileupload-buttonbar').on('click', '.start', function(e){
		e.preventDefault();
		$(e.currentTarget).data('active', 0);
		$('#fileupload').data('requiredMessage', 1);
	});

	// 個別キャンセル
	$('#fileupload-table tbody').on('click', '.template-upload .cancel', function(e){
		e.preventDefault();
		var len = $('#fileupload-table tbody .template-upload').length;
		if (len==1) {
			$('#fileupload .fileupload-buttonbar .start').removeClass('in');
		}
	});

	// 個別に削除
	$('#fileupload-table tbody').on('click', '.template-download .delete', function(e){
		var i = 0,
			self = $(this).closest('tr'),
			name = self.find('.name').children().text(),
			store = $.getStorage('attach'),
			attach = [];
		
		for (i=0; i<store.length; i++) {
			if (store[i]['name']!=name) {
				attach.push(store[i]);
			}
		}
		
		if (attach.length===0) attach = {};
		$.removeStorage('attach', attach);
	});

	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload({
		// Uncomment the following to send cross-domain cookies:
		//xhrFields: {withCredentials: true},
		url: '/user/member/data/'
	}).on('fileuploadadd', function(e, data) {
		$('#fileupload .fileupload-buttonbar .start').addClass('in');
	}).on('fileuploadalways', function(e, data){
		var rest = 0,
			idx = 0,
			files = [],
			names = [];
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
			
			// アップロード完了
			if (self.is('.template-download')) {
				path = self.find('.path').text();
				name = self.find('.name').children().text();
				size = self.find('.size').text();
				deleteURL = self.find('.delete').data('url');
				
				if (names.indexOf(name)>-1) {
					self.remove();
				} else {
					names[idx] = name;
					files[idx++] = {'name':name, 'url':path, 'size':size, 'deleteUrl':deleteURL};
				}
				
			}
		});

		if (files.length===0) {
			$.removeStorage('attach', {});
		} else {
			$.setStorage('attach', files);
		}
		
		// 全てのアップロードが完了
		if (rest==0) {
			$('#fileupload .fileupload-buttonbar .start').removeClass('in');
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
		url: '/user/member/data/',
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
			url: '//'+window.location.hostname+'/user/member/data/',
			type: 'HEAD'
		}).fail(function () {
			$('<div class="alert alert-danger"/>')
				.text('Upload server currently unavailable - ' +
					new Date())
				.appendTo('#fileupload');
		});
	}

});
