<?php

/**
 * Automation script to generate Passkit for iOS 6. (iPhone only.)
 *
 * @author Laks Gandikota <laks@wow.com>
 * @copyright Copyright (c) 2012, laksg.com
 * @link http://www.laksg.com
 * @license licensed under the MIT.
 * @version 0.1
 *
 */
 
ini_set('error_reporting', E_ALL);
date_default_timezone_set('America/Chicago');
define('DOCUMENT_ROOT', dirname(realpath(__FILE__)).'/');
$useragent = $_SERVER['HTTP_USER_AGENT'];
require_once(DOCUMENT_ROOT . 'settings/globals.php');
require_once DOCUMENT_ROOT . 'classes/KLogger.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
/*
//echo $_SERVER['HTTP_USER_AGENT'];
echo $passname . " > " . filesize($passname);
die();
*/
if (strpos($useragent, "iPhone"))
{
	$isIphone = TRUE;
	
	header('Content-disposition: attachment; filename=pass.pkpass'); 
	header("Content-Length: ".filesize($passname)); 
	header('Content-Type: application/vnd.apple.pkpass');
	header('Content-Transfer-Encoding: binary'); 

}
else
{
	$isIphone = FALSE;
}
$log = KLogger::instance(dirname(__FILE__) . '/log/', KLogger::DEBUG);
$log->logInfo('Start.');
require_once(DOCUMENT_ROOT . 'classes/manifest.class.php');

//Creating manifest file.

$manifest = new Manifest();
$fp = fopen($manifestfile, 'w');
$log->logInfo('Creating manifest file.');
fwrite($fp, json_encode($manifest->arrayoffileswithsha1($package_path)));
fclose($fp);
$log->logInfo('Created manifest file.');
$log->logInfo('End.');

//Creating Signature.

$certdata = openssl_x509_read(file_get_contents($certificate));
$privkey = openssl_pkey_get_private(file_get_contents($key), $key_password );
openssl_pkcs7_sign($manifestfile, $signature . 'tmp', $certdata, $privkey,array(),PKCS7_BINARY|PKCS7_NOATTR|PKCS7_DETACHED,$intermediatecert);

$signatureRaw = $signature . 'tmp';
$handle = fopen($signatureRaw, "r");
$toDecode = fread($handle, filesize($signatureRaw));
fclose($handle);


$pattern = "/.*?Content-Disposition: attachment; filename=\".*?\"(.*?)-----.*?/sm";
preg_match_all($pattern, $toDecode, $matchResult);


$toDecode = base64_decode($matchResult[1][0]);

$fp = fopen($signature, "w");
fwrite($fp, $toDecode);
fclose($fp);

unlink($signature . 'tmp');

//Creating pkpass

//Compress the pass

//unlink($passname);

$zip = new ZipArchive();
$log->logInfo('Creating pkpass');
if ($zip->open($passname, ZIPARCHIVE::CREATE)!==TRUE) {
	$log->logErr('Permission Denied.');
}

chdir($package_path);

foreach(glob('*') as $file) 
{
	$zip->addFile($file);
}
$log->logInfo('Created.');
$zip->close();

readfile($passname);
