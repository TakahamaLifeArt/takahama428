/**
 * API
 * log
 * 2017-10-21 Created
 */
$(function(){
	'use strict';
	const API_URL = 'https://takahamalifeart.com/v3/';
	const ACCESS_TOKEN = 'cuJ5yaqUqufruSPasePRazasUwrevawU';
	$.extend({
		api: function (args, method, callback) {
			/**
			 * @param args リソースのコレクション
			 * @param method HTTP Method{@dode GET, POST, PUT}
			 * @param callback 成功後に実行する関数
			 * @param arguments[3]
			 *		プリント代計算のパラメータ、他引数（json形式）
			 *		または
			 *		タグIDの配列、量販単価の枚数
			 * @return jqXHR object
			 */
			if (Array.isArray(args) !== true) return;
			var resource = '',
				param = {},
				len = args.length,
				isAsync = true;
			if (len == 0) return;
			resource += args[0];
			for (var i = 1; i < len; i++) {
				resource += '/' + args[i];
			}
			if (arguments.length > 3) {
				//				query = encodeURIComponent(arguments[2]);
				param = {
					'args': arguments[3]
				};
				resource += '/';
			}

			if (method=='sync') {
				method = 'GET';
				isAsync = false;
			}

			return $.ajax({
				async: isAsync,
				url: API_URL + resource,
				type: method,
				dataType: 'json',
				data: param,
				timeout: 5000,
				headers: {
					'X-TLA-Access-Token': ACCESS_TOKEN
				},
				statusCode: {
					400: function () {
						alert("400 Bad Request");
					},
					401: function () {
						alert("401 Unauthorixed");
					},
					403: function () {
						alert("403 Forbidden");
					},
					404: function () {
						alert("404 page not found");
					},
					405: function () {
						alert("405 Method Not Allowed");
					},
					0: function() {
						alert("通信状況が不安定なためリソースの読み込みができませんでした");
						window.location.href = 'https://www.takahama428.com/err/error400.php';
					}
				}
			}).done(function (response, textStatus, jqXHR) {
				if (Object.prototype.toString.call(callback)==='[object Function]') callback(response);
			}).fail(function (jqXHR, textStatus, errorThrown) {
				if (jqXHR.status != 0) {
					alert(textStatus+": ネットワークでエラーが発生しました");
					window.location.href = 'https://www.takahama428.com/err/error400.php';
				}
			}).always(function (data_or_jqXHR, textStatus, jqXHR_or_errorThrown) {

			});
		}
	});
});