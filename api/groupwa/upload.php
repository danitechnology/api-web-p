<?php
include "tools/function.php";
$get = new RndyTech();
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');
header('Content-type: application/json');

if(isset($_POST["server"]) && isset($_POST["user"]) && isset($_POST["pass"]) && isset($_POST["userwhm"]) && isset($_POST["passwhm"])){
//LINK LOGIN
$server = $_POST["server"];
//AKSES WHM
$user = $_POST["user"];
$pass = $_POST["pass"];
$userwhm = $_POST["userwhm"];
$passwhm = $_POST["passwhm"];

$tujuan = "public_html";
$file = "script/grupwa.zip";

$upload = $get->upload($server,$userwhm,$passwhm,$user,$pass,$file,$tujuan);
print_r($upload);

}else{
       $r["status"] = false;
$r["code"] = 500;
$r["msg"] = "Masukkan Parameter Yang Valid!";
$hasil = json_encode($r, JSON_PRETTY_PRINT);
print_r($hasil);
}
?>