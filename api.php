<?php

function apiNewTokenAccess(){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL,"https://wstraining.bkn.go.id/oauth/token"); //ini bukan server production
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST"); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_USERPWD, "user:pass"); //diubah sesuai user dan pass yang didapat
	curl_setopt($curl, CURLOPT_POSTFIELDS,"client_id=user&grant_type=client");
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','origin: http://localhost:20000'));


	// server response ...
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


	if(($jsondata = curl_exec($curl)) === false)
	{
		exit( 'Curl error: ' . curl_error($curl));
	}
	else
	{
		
// file token akan dibuat otomatis
		$obj = json_decode($jsondata, true);
		if(isset($obj['access_token'])){
			$token_file = fopen("token-key.txt", "w") or die("Unable to open file!");
			$txt = "ini-nanti-diisi-token-key";
			fwrite($token_file, $obj['access_token']);
			fclose($token_file);
		}
	}
	
	curl_close ($curl);
}

// GET data setelah token berhasil dibuat

function apiResult( $url = ''){
	$token_file = fopen("token-key.txt", "r") or die("Unable to open file!");
	$tokenKey = fread($token_file,filesize("token-key.txt"));
	fclose($token_file);
	
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_POST, true);
	
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Origin: http://localhost:20000', 
		'Authorization: Bearer '. $tokenKey
	));


	// server response ...
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	return curl_exec($curl);
}

?>
