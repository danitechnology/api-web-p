<?php
class rndytech{

	//CREATE SESSION
	public function session($url,$user,$pass){
$query = "https://".$url.":2087/login/?login_only=1";
        $dataUrl = "user=".$user."&pass=".$pass;
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $query);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $dataUrl);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch2, CURLOPT_COOKIEJAR, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_COOKIEFILE, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        $ress = curl_exec($ch2);
        curl_close($ch2);
        $dec = json_decode($ress,true);
        return $dec['security_token'];
        }
     

public function createcp($url,$user,$pass,$domain){
	
	$pkg = "Cpanel Super";
	
        $data = "api.version=1&username=$user&pass=$pass&domain=$domain&pkg=$pkg";
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch2, CURLOPT_COOKIEJAR, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_COOKIEFILE, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        $ress = curl_exec($ch2);
        curl_close($ch2);
        $dec = json_decode($ress,true);
       
        return $dec;
        
}


public function unzip($server,$user,$pass,$url,$file){
     
  $data = "cpanel_jsonapi_user=$user&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Fileman&cpanel_jsonapi_func=fileop&op=extract&sourcefiles=$file&destfiles=chat&doubledecode=1";
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch2, CURLOPT_COOKIEJAR, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_COOKIEFILE, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        $ress = curl_exec($ch2);
        curl_close($ch2);
        $dec = json_decode($ress,true);
       if($dec["cpanelresult"]["data"][0]["result"] == 1){
       
       //BATAS

$file = realpath("script/index.php");
$uploadurl = "https://$server:2083/execute/Fileman/upload_files";
$tujuan = "public_html";
// Set up the payload to send to the server.
if( function_exists( 'curl_file_create' ) ) {
    $cf = curl_file_create($file);
} else {
    $cf = "@/".$file;
}

//upload
$payload = array(
    'dir'    => $tujuan,
    'file-1' => $cf
);

$hasil = $this->curl($user,$pass,$uploadurl,$payload);

if($hasil != ""){
$value  = (object)$hasil;
$file = $value->data->uploads;
$file = $file[0];
$status = $file->status;
if($status == 1){
	
$result["code"] = 200;
	$result["status"] = true;
	$result["msg"] = "semua file Berhasil Unzip";
	return $result;
}
}
//BATAS
       
       
       
       
       
        
        }else{
        	$result["code"] = 500;
	$result["status"] = false;
	$result["msg"] = "ada masalah ketika unzip";
	return $result;
        	}
        
}


public function curl($user,$pass,$url,$payload){
	
// Set up the curl request object.
$ch = curl_init($url);
curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
curl_setopt( $ch, CURLOPT_USERPWD,$user . ':' . $pass);
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

// Set up a POST request with the payload.
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_POSTFIELDS,$payload);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

// Make the call, and then terminate the curl caller object.
$curl_response = curl_exec($ch);
curl_close($ch);

// Decode and validate output.
$res = json_decode( $curl_response );
return $res;
}



function upload($server,$userwhm,$passwhm,$user,$pass,$file,$tujuan){

$file = realpath($file);
$uploadurl = "https://$server:2083/execute/Fileman/upload_files";

// Set up the payload to send to the server.
if( function_exists( 'curl_file_create' ) ) {
    $cf = curl_file_create($file);
} else {
    $cf = "@/".$file;
}



//upload
$payload = array(
    'dir'    => $tujuan,
    'file-1' => $cf
);

$hasil = $this->curl($user,$pass,$uploadurl,$payload);

if($hasil != ""){
	$value  = (object)$hasil;
$file = $value->data->uploads;
$file = $file[0];
$status = $file->status;
$file = "public_html/".$file->file;
	
	if($status == 1 || $status == true){
		sleep(2);
	
		$session = $this->session($server,$userwhm,$passwhm);
$url = "https://$server:2087$session/json-api/cpanel";
$unzip = $this->unzip($server,$user,$pass,$url,$file);
		
		
		$result["code"] = 200;
	$result["status"] = true;
	$result["location"] = $file;
	$result["msg"] = "File Berhasil TerUpload dan terUnzip";
	print_r($unzip);
		
}else{
	$result["code"] = 500;
	$result["status"] = false;
	$result["msg"] = "File Tidak TerUpload/mungkin sudah ada";
	print_r($result);
	}
}
		
		}

	
public function addShortlink($iniUrl){
	$url = "https://website-resmii.my.id/add.php";
    $data = "url=".$iniUrl."&RandKey=kikikasep";
   
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch2, CURLOPT_COOKIEJAR, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_COOKIEFILE, getcwd()."/cok.txt");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        $ress = curl_exec($ch2);
       return $ress;
        }
public function tambahdomain($domain,$ip){
	$domain = $domain.rand(10,100000);
$zone = "0cfb51d3fc0b7c2ba140f60ddced0c52";

$url = 'https://api.cloudflare.com/client/v4/zones/'.$zone.'/dns_records';

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
       "Authorization: Bearer sqFXT9_U2VhhCoQdAyzit6aCA0uOA2Tzleo5NZeD",
       "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
    $data = array(
        'type' => 'A',
        'name' => $domain,
        'content' => $ip,
        'proxied' => true
        );
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($curl);
    
    $data = json_decode($resp,true);
    if($data["success"] == 1){
    	return $data["result"]["name"];
	
	
    	}else{
    	return "error dek";
    	}
    }
}
?>
