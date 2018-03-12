/*
	My Page
	2013-06-06 created
	2016-11-29 ユーザーネーム更新機能を追加
	2018-01-17 マイページ改修に伴い更新
*/

$(function(){

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
				// loading終了
				eMailer.loaderOff();
				$.msgbox('Error: 更新できませんでした');
				return d.reject().promise();
			}
		}).then(function(){
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
					// loading終了
					eMailer.loaderOff();
					$.msgbox('<p>Error: 顧客情報を更新できませんでした</p>');
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
				// loading終了
				eMailer.loaderOff();
				$.msgbox('Error: 更新できませんでした');
				return d.reject().promise();
			}
		});
	}
	
	
	//ユーザー情報更新フォームのバリデーション
	eMailer.onValidate('#profile tfoot .ok_button', function(){
		var user,
			args = {};
		
		// 更新データ取得
		$('#profile .update-args').each(function(){
			var self = $(this),
				key = self.attr('name');
			args[key] = self.val();
		});

		// 更新
		user = new User(args);
		user.update();

		return true;
	});
	
	// ユーザー情報更新フォーム送信完了
	eMailer.onComplete('#profile tfoot .ok_button', function(){
		$.dialogBox('顧客情報を更新いたしました。', 'メッセージ', 'OK').then(function(){
			window.location.reload();
		});
	});
	
	
	// ユーザー情報の変更をキャンセル
	$('#profile tfoot .cancel_button').on('click', function(){
		document.forms.prof.reset();
		$('.err').text('');
	});
	
	
	// パスワード変更フォームのバリデーション
	eMailer.onValidate('#pass_table tfoot .ok_button', function () {
		/**
		 * 必須項目の検証
		 */
		var password = $('#pass_table [name="password"]').val().trim(),
			passconf = $('#pass_table [name="passconf"]').val().trim(),
			args = {},
			user;
		
		// パスワード確認の照合
		if (password != passconf) {
			$.msgbox('パスワードをご確認ください');
			return false;
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

		return true;
	});
	
	// パスワード変更フォーム送信完了
	eMailer.onComplete('#pass_table tfoot .ok_button', function(){
		$.dialogBox('パスワードを更新いたしました。', 'メッセージ', 'OK').then(function(){
			// フォームをリセット
			document.forms.pass.reset();
		});
	});
	
	
	// パスワードの変更をキャンセル
	$('#pass_table tfoot .cancel_button').on('click', function(){
		document.forms.pass.reset();
		$('.err').text('');
	});

});
