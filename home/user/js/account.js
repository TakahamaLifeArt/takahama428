/*
	My Page
	2013-06-06 created
	2016-11-29 ユーザーネーム更新機能を追加
	2018-01-17 マイページ改修に伴い更新
*/

$(function(){

	/**
	 * メール送信
	 * $.sendData {boject} 送信データ
	 */
	$.extend({
		sendData: {},
		sendMail: function(){
			$.ajax({
				url: '/user/php_libs/sendMail.php',
				type: 'POST',
				dataType: 'json',
				async: true,
				timeout: 5000,
				data: $.sendData
			}).done(function(r){
//				if (r[0] == 'SUCCESS') {
//					$.msgbox('<p>顧客情報を更新いたしました。</p>');
//				} else {
//					$.msgbox('メールの送信ができませんでした。');
//				}
			}).fail(function(xhr, status, error){
//				alert("Error: "+error+"<br>通信エラーです。");
				
			});
		}
	});
	
	
	// ユーザー情報更新クラス
	function User(args) {
		this.args = args;
	}
	
	// 顧客情報の更新処理
	User.prototype.update = function() {
		var id = this.args.id;
		$.api(['users', id], 'GET', null, JSON.stringify(this.args)).then(function(r){
			var d = $.Deferred();
			// 更新結果を確認
			if (r.id) {
				return d.resolve().promise();
			} else {
				$.msgbox('Errpr: 更新できませんでした');
				return d.reject().promise();
			}
		}).then(function(){
			// 顧客情報変更の業務メール送信
			$.sendMail();
			
			// ログイン情報を更新してリロード
			var param = {
				'id': id,
				'reset': true
			}
			$.ajax({
				url: '/user/php_libs/loginState.php',
				type: 'get',
				dataType: 'json',
				async: true,
				timeout: 5000,
				data: param
			}).done(function(r){
				if (!r) {
					$.msgbox('<p>Error: 顧客情報を更新できませんでした</p>');
				} else {
					$.dialogBox('顧客情報を更新いたしました。', 'メッセージ', 'OK').then(function(){
						window.location.reload();
					});
				}

			});
		});
	}
	
	// パスワード変更処理
	User.prototype.setPass = function() {
		var id = this.args.id;
		$.api(['users', id, 'pass'], 'GET', null, JSON.stringify(this.args)).then(function(r){
			var d = $.Deferred();
			// 更新結果を確認
			if (r.id) {
				return d.resolve().promise();
			} else {
				$.msgbox('Errpr: 更新できませんでした');
				return d.reject().promise();
			}
		}).then(function(){
			// 顧客情報変更の業務メール送信
			$.sendMail();

			$.dialogBox('パスワードを更新いたしました。', 'メッセージ', 'OK').then(function(){
				// フォームをリセット
				document.forms.pass.reset();
				$('.err').text('');
			});
		});
	}
	
	
	//ユーザー情報の更新ボタン
	$('#profile tfoot .ok_button').on('click', function(){
		var user,
			args = {},
			isValid = true;
		
		// メール送信データ初期化
		$.sendData = {};
		
		// 必須確認とメール送信データ取得
		$('#profile input').each(function(){
			var self = $(this),
				key = self.attr('name'),
				data = self.val().trim();
			
			self.val(data);
			
			// メールデータ
			if (self.hasClass('send-args')) {
				$.sendData[key] = data;
			}
			
			// 必須項目
			if (self.prop('required') && !data) {
				self.closest('td').find('.err').text('必須です');
				isValid = false;
			}
		});
		
		if (isValid) {
			// 更新データ取得
			$('#profile .update-args').each(function(){
				var self = $(this),
					key = self.attr('name');
				args[key] = self.val();
			});
			
			// 更新
			user = new User(args);
			user.update();
		} else {
			$.msgbox('必須項目をご確認ください');
		}
	});
	
	
	// ユーザー情報の変更をキャンセル
	$('#profile tfoot .cancel_button').on('click', function(){
		document.forms.prof.reset();
		$('.err').text('');
	});
	
	
	// パスワードを変更
	$('#pass_table tfoot .ok_button').on('click', function(){
		var i = 0,
			user,
			args = {},
			isValid = true,
			pass = [];

		// 必須確認とメール送信データ取得
		$('#pass_table input').each(function(){
			var self = $(this),
				key = self.attr('name'),
				data = self.val().trim();

			self.val(data);

			// メールデータ
			$.sendData = {};
			if (self.hasClass('send-args')) {
				$.sendData[key] = data;
			}

			// 必須項目
			if (self.prop('required') && !data) {
				self.closest('td').find('.err').text('必須です');
				isValid = false;
			}
			
			// パスワード
			if (self.attr('type')=='password') {
				pass.push(data);
			}
		});
		
		if (isValid) {
			// パスワード確認の照合
			for (i=1; i<pass.length; i++) {
				if (pass[0]!=pass[i]) {
					isValid = false;
					break;
				}
			}
			if (!isValid) {
				$.msgbox('パスワードをご確認ください');
				return;
			}
			
			
			// 更新データ取得
			$('#pass_table .update-args').each(function(){
				var self = $(this),
					key = self.attr('name');
				args[key] = self.val();
			});

			// 更新
			user = new User(args);
			user.setPass();
		} else {
			$.msgbox('必須項目をご確認ください');
		}
	});
	
	
	// パスワードの変更をキャンセル
	$('#pass_table tfoot .cancel_button').on('click', function(){
		document.forms.pass.reset();
		$('.err').text('');
	});

});
