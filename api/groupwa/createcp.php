<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');
header('Content-type: application/json');
include "tools/function.php";
$get = new RndyTech();

if(isset($_POST["server"]) && isset($_POST["userwhm"]) && isset($_POST["passwhm"]) && isset($_POST["ip"])){
//LINK LOGIN
$server = $_POST["server"];
//AKSES WHM
$userwhm = $_POST["userwhm"];
$passwhm = $_POST["passwhm"];
$ip = $_POST["ip"];
    

//AKSES CPANEL
$user = "kikihosting".rand(1,999);
$pass = "@@server44".rand();
$pkg = "cPanel Super";


//DOMAIN CPANEL
$domain = "whatsapp";

$domain = $get->tambahdomain($domain,$ip);

if($domain != ""){
	$pendek = $get->addShortlink("https://".$domain);
	$session = $get->session($server,$userwhm,$passwhm);


$url = "https://$server:2087$session/json-api/createacct";
 $hasil = $get->createcp($url,$user,$pass,$domain);
 
 if(isset($hasil["metadata"])){
 
if($hasil["metadata"]["result"] == 1){
	
$awal = "+===================================+";
$akhir = "+===================================+";
$respon = $hasil['metadata']['output']['raw'];
$hasil = explode($awal,$respon);
$return = explode($akhir,$hasil[2]);

$exp = explode("UserName: ",$return[0]);
$exp = explode("| PassWord: ",$exp[1]);
$user = $exp[0];
$user = explode("
",$user);
$user = $user[0];
$exp = explode("| CpanelMod:",$exp[1]);
$pass = $exp[0];
$pass = explode("
",$pass);
$pass = $pass[0];


$r["status"] = true;
$r["code"] = 200;
$r["domain"] = $domain;
$r["pendek"] = $pendek;
$r["server"] = $server;
$r["userwhm"] = $userwhm;
$r["passwhm"] = $passwhm;
$r["user"] = $user;
$r["pass"] = $pass;
$hasil = json_encode($r, JSON_PRETTY_PRINT);
print_r($hasil);


	}
	}
	}else{
	echo "error";

	}
 
        
  
}else{
   $r["status"] = false;
$r["code"] = 500;
$r["msg"] = "Masukkan Parameter Yang Valid!";
$hasil = json_encode($r, JSON_PRETTY_PRINT);
print_r($hasil);
}

?>