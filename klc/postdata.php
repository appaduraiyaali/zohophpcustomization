<?php
require 'vendor/autoload.php';
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
//echo phpinfo ();
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$Class_id=$_GET["ClassID"];
trigger_error ('Hellooooo! the class id is ' . $Class_id .'!');
$attach_id=$_GET["AttachID"];
trigger_error('attachid '.$attach_id);
$accesstoken=getAccessToken();
fetchAttachment();


 function fetchAttachment()
 {
	 
	global $accesstoken;
	global $Class_id;
	global $attach_id;
	
	$downloadUrl = "https://www.zohoapis.com/crm/v2/Classes/".$Class_id."/Attachments/".$attach_id;
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accesstoken )
        );


    $downch = curl_init();

    curl_setopt($downch, CURLOPT_URL, $downloadUrl);
    curl_setopt($downch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($downch, CURLOPT_TIMEOUT, 60);

    $result_down = curl_exec($downch);
    trigger_error('attachmentdetails'.json_encode($result_down));
    $ext = pathinfo($result_down, PATHINFO_EXTENSION);
	//echo 'result of attachment '.$ext;
    $current = time();
    $rawfilename=__DIR__.DIRECTORY_SEPARATOR.'xlsdata'.DIRECTORY_SEPARATOR.'xlsdata'.$current.'.xls';
    $response = file_put_contents($rawfilename,$result_down);
	
    readfiles($rawfilename);
	 
	
 }
 
 function readfiles($filetoread)
{
	
	global $Class_id;
	$ext = pathinfo($filetoread, PATHINFO_EXTENSION);
	//echo 'extension is '.$ext   .'extention';
	if($ext=='xls'){
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
		echo 'xls read executed';
	}
    elseif($ext=='xlsx'){
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
		echo 'xlsx read executed';
	}
	//$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
    $reader->setReadDataOnly(TRUE);
    $spreadsheet = $reader->load($filetoread);
	$worksheet = $spreadsheet->getActiveSheet();
	$GeneratedDate = $spreadsheet->getActiveSheet()->getCell('A1')->getValue();
    $GeneratedDateValue = $spreadsheet->getActiveSheet()->getCell('C1')->getValue();
    $CourseTitle = $spreadsheet->getActiveSheet()->getCell('B3')->getValue();
    $CourseTitleValue = $spreadsheet->getActiveSheet()->getCell('C3')->getValue();
    $ClassCode = $spreadsheet->getActiveSheet()->getCell('B4')->getValue();
    $ClassCodeValue = $spreadsheet->getActiveSheet()->getCell('C4')->getValue();
    $ClassDate = $spreadsheet->getActiveSheet()->getCell('B5')->getValue();
    $ClassDateValue = $spreadsheet->getActiveSheet()->getCell('C5')->getValue();
    $ClassTimeValue = $spreadsheet->getActiveSheet()->getCell('D5')->getValue();
	

    $datatop = array($GeneratedDate=>$GeneratedDateValue, $CourseTitle=>$CourseTitleValue, $ClassCode=>$ClassCodeValue ,$ClassDate=>$ClassDateValue);
    trigger_error("top data ".json_encode($datatop));
	foreach($datatop as $key=>$val) { 
      $val=str_replace("\n", "", $val,$i);
	  $datatop[$key]=$val;
	  trigger_error($val);
    } 
	//trigger_error('trimmed data '.json_encode($datatop));
	$removeslash=json_encode($datatop);
	$datatopnew= stripslashes($removeslash);
	trigger_error('datatop value issssss '.$datatopnew);
	
	 $crmkeyValueMap=array();
	 $dataMaptoUpload=array();
     $datakeyarray=array("dummy","S_no","Last_Name","Mobile","Email","ID_Type","ID_Number","Nationality","Date_of_Birth","Occupational_Title","Centre_Name","Organisation_Name","Address"," "," "," "," ","Type_of_Sponsorship","Status");
	 trigger_error(json_encode($datakeyarray).PHP_EOL);

    $highestRow = $worksheet->getHighestRow();
	$highestColumn ='R';
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); 
	for ($row = 9; $row <= $highestRow; ++$row) {
    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
        $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
		$crmkeyValueMap[$datakeyarray[$col]] = $value;
    }
	//$crmkeyValueMap["Class"] = $Class_id;
	//trigger_error('crm key value map'.json_encode($crmkeyValueMap));
	foreach($crmkeyValueMap as $key=>$val) { 
	  if($key=='Date_of_Birth'){
		  //echo $val;
		  
          $date = str_replace('/', '-', $val);  
          $val = date("Y-m-d", strtotime($date));  
         // echo " New date format is: ".$val. " (YYYY-MM-DD)  ";  
		  $crmkeyValueMap[$key]=$val; 
	  }
      $val=str_replace("\n", "", $val,$i);
	  $crmkeyValueMap[$key]=$val;
	  trigger_error($val);
    } 
	
	
	$encodedcrmkeyValueMap=json_encode($crmkeyValueMap);
	array_push($dataMaptoUpload,$crmkeyValueMap);
	
    }
	
    if(count($dataMaptoUpload) > 100)
	{
		$updatechunkarr=array_chunk($dataMaptoUpload,100);
		trigger_error("TOTAL BATCH TO UPDATE " . count($updatechunkarr));
		foreach($updatechunkarr as $updatechunk)
		{
			trigger_error('STUDENT SUMMARY - CHUNK TO UPDATE ' . json_encode($updatechunk));
			
			postStudentrecords($updatechunk);
		}
	}else{
		postStudentrecords($dataMaptoUpload);
	}
}	




function postStudentrecords($datamap)
{
	global $accesstoken;
	$postdataarray=$datamap;
	$postUrl = "https://www.zohoapis.com/crm/v2/Contacts";
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accesstoken )
        );

    $postdata=array("data"=>$postdataarray);
    $encodedData=json_encode($postdata, JSON_UNESCAPED_SLASHES);
	trigger_error('encoded data '.$encodedData);
    $uploadch = curl_init();

    curl_setopt($uploadch, CURLOPT_URL, $postUrl);
    curl_setopt($uploadch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($uploadch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($uploadch, CURLOPT_POSTFIELDS, $encodedData);
    curl_setopt($uploadch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($uploadch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($uploadch, CURLOPT_TIMEOUT, 60);
        $response=curl_exec($uploadch);
		$response=utf8_decode($response);
		trigger_error('Decoded Response ' . $response);
		$jsonresponse=json_decode($response,JSON_PRETTY_PRINT);	
	    $dataarray=$jsonresponse["data"];
	
	trigger_error('data map is '.json_encode($dataarray));
	$studRecIdsArray=array();
	foreach($dataarray as $studentRecord)
		{
			trigger_error(json_encode($studentRecord));
			$details=$studentRecord["details"];
			$id=$details["id"];
			trigger_error('id is '.$id);
			array_push($studRecIdsArray, $id);
		}
		trigger_error(json_encode($studRecIdsArray));
		insertLinkingModule($studRecIdsArray);
		
	
}


function leadconversion($leadID)
{
	
	global $accesstoken;
	$data=array();
    $postdatamap=array("data"=>$data);
	
	$postUrl = "https://www.zohoapis.com/crm/v2/Leads/".$leadID."/actions/convert";
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accesstoken )
        );

    
    $uploadch = curl_init();

    curl_setopt($uploadch, CURLOPT_URL, $postUrl);
    curl_setopt($uploadch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($uploadch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($uploadch, CURLOPT_POSTFIELDS,json_encode($postdatamap));
    curl_setopt($uploadch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($uploadch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($uploadch, CURLOPT_TIMEOUT, 60);
        $response=curl_exec($uploadch);
		$response=utf8_decode($response);
		trigger_error('Response for Lead Conversion ' . $response);
	
}


function insertLinkingModule($StudentIds)
{
	global $Class_id;
	global $accesstoken;
	$data=array();
	
	
	foreach($StudentIds as $studId){
		
	$studidmap=array(
	'id'=>$studId
	);
	$classidmap=array(
	'id'=>$Class_id
	);
	$StudClassmap=array("StudentsList"=>$studidmap,"Classes"=>$classidmap );
	
	array_push($data,$StudClassmap);
	
	}
	$postdatamap=array("data"=>$data);
	trigger_error('linkingmodule insert data'.json_encode($postdatamap));
	$postUrl = "https://www.zohoapis.com/crm/v2/Students_X_Classes";
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accesstoken )
        );

    
    $uploadch = curl_init();

    curl_setopt($uploadch, CURLOPT_URL, $postUrl);
    curl_setopt($uploadch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($uploadch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($uploadch, CURLOPT_POSTFIELDS,json_encode($postdatamap));
    curl_setopt($uploadch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($uploadch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($uploadch, CURLOPT_TIMEOUT, 60);
        $response=curl_exec($uploadch);
		$response=utf8_decode($response);
		trigger_error('Decoded Response for linking module ' . $response);
	
}


		
		
function getAccessToken()
{
$reFresh_token= "1000.b210882826c48716aeb27e16ce9ec288.e0e30d2975baa18532597e45ddce8207";
$client_id= "1000.4XQ4V5IPQF43P4KV9MX5P7SNJVPDHH";
$client_secret= "4ca096b20aa072888988fe136b35661a91b4b06a7f";
$access_url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=".$reFresh_token."&client_id=".$client_id."&client_secret=".$client_secret."&grant_type=reFresh_token";

$access_curl = curl_init();

curl_setopt_array($access_curl, array(
  CURLOPT_URL => $access_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 45,
  CURLOPT_SSL_VERIFYPEER => FALSE,
  CURLOPT_SSL_VERIFYHOST => FALSE,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",

));

$access_response = curl_exec($access_curl);
//trigger_error('Access Token Response ' . $access_response);
$access_err = curl_error($access_curl);
//trigger_error('Access token error ' . $access_err);
curl_close($access_curl);
$res = json_decode($access_response);
$final_access_token = $res->access_token;
trigger_error('Latest Access Token ' . $final_access_token);
return $final_access_token;
}
?>