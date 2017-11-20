<?php
require_once dirname(__FILE__)."/config.php";
class MySessionHandler implements SessionHandlerInterface
{
	private $savePath;

	public function open($savePath, $sessionName)
	{
		$this->savePath = $savePath;
		if (!is_dir($this->savePath)) {
			mkdir($this->savePath, 0777);
		}

		return true;
	}

	public function close()
	{
		return true;
	}

	public function read($id)
	{
		return (string)@file_get_contents("$this->savePath/sess_$id");
	}

	public function write($id, $data)
	{
		return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
	}

	public function destroy($id)
	{
		$file = "$this->savePath/sess_$id";
		if (file_exists($file)) {
			unlink($file);
		}

		return true;
	}

	public function gc($maxlifetime)
	{
		foreach (glob("$this->savePath/sess_*") as $file) {
			if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
				unlink($file);
			}
		}

		return true;
	}
}

$handler = new MySessionHandler();
session_set_save_handler($handler, true);

session_save_path(_SESS_SAVE_PATH);

session_cache_expire(120);

session_set_cookie_params(0);

session_start();

// upload処理でsission_id を使用するため書き換え禁止
//session_regenerate_id(true);
