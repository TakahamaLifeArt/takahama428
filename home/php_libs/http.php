<?php
/*
*	HTTP Request
*	charset utf-8
*
*/
class HTTP {

	private $_url;
	
	public function __construct($args){
		$this->_url = $args;
	}
	
	public function request($method, $params = array(), $headers = array()){
		$url = $this->_url;
		$data = http_build_query($params);
		if ($method == 'GET') {
			$url = ($data != '')?$url.'?'.$data:$url;
		}
	
		$ch = curl_init($url);
		
		if ($method == 'POST') {
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		} else if ($method == 'PUT') {
			curl_setopt($ch,CURLOPT_PUT,1);
		}
	 
		curl_setopt($ch, CURLOPT_HEADER,false); //header情報も一緒に欲しい場合はtrue
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 60000);
		
		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		$res = curl_exec($ch);
		//ステータスをチェック
		$respons = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if(preg_match("/^(400|401|403|404|405|500)$/",$respons)){
			return 'HTTP error: '.$respons;
		}
	 
		return $res;
	}
	
	
	public function setURL($url){
		$this->_url = $url;
	}
	
	
	public function request2($method, $params = array()){
		$url = $this->_url;
		$data = http_build_query($params);
		$header = Array("Content-Type: application/x-www-form-urlencoded");
		$options = array('http' => Array(
			'method' => $method,
			'header'  => implode("\r\n", $header),
		));
	 
		//ステータスをチェック / PHP5専用 get_headers()
		$respons = get_headers($url);
		if(preg_match("/(404|403|500)/",$respons['0'])){
			return false;
		}
	 
		if($method == 'GET') {
			$url = ($data != '')?$url.'?'.$data:$url;
		}elseif($method == 'POST') {
			$options['http']['content'] = $data;
		}
		$content = file_get_contents($url, false, stream_context_create($options));
	 
		return $content;
	}
}
?>
