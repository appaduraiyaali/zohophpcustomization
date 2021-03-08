
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.inc.php');
$accPlanId=$_GET["AccountPlanId"];
$action=$_GET["action"];
$fname=$_GET["fname"];
$loginuser=$_GET["owner"];
if(!empty($fname))
{
	$loginuser=$fname.' '.$loginuser;
}
$pdfyear=$_GET["pdfyear"];
$apitoken=$_GET["apitoken"];
/*
if(empty($apitoken) || $apitoken != APITOKEN)
{
	 http_response_code(403);
die('Forbidden');
}
*/
if(!empty($pdfyear))
{
	$tok=strtok($pdfyear, " ");
	if($tok !== false)
	{
		$pdfyear=$tok;
	}
	trigger_error ('PDF Year ' . $pdfyear);
}
trigger_error ('Hii! the accPlanId id is ' . $accPlanId);
$accessToken=getAccessToken();
$accountPlanData=getAccountPlanDetails();
$accountData;
$contactDataArray;
$acctTargetGlobal;
$lastyear;
$currentyear;
$nextyear;
$bussinessOppRender;
$keyinitiativearray;
$valuepropositionarray; 
$barrierstosuccess;
$p2premittancestrategy;
$p2prequiredlist;
$p2prequireddetails;
$partnername;
$accountowner;
$b2binvoicing;
$b2brequiredlist;
$b2brequireddetails;
$c2bpayment;
$c2brequiredlist;
$c2brequireddetails;
//$accId='4463989000004037016';
$latestfundingyear;
$latestfunding;
$filePath=PDFTARGETDIR.DIRECTORY_SEPARATOR.'08012021Consultant.pdf';
$fileName='08012021Consultant.pdf';
/*
attachPdf($filePath);

if(true)
{
	exit;
}
*/

function getAccessToken()
{

	$reFresh_token= "1000.0d87a47d0ef6b21d042515d1d63a5f09.d4d2e79edf5fd0de94700f81947a01c1";
$client_id= "1000.7GVYUZJ8E917PTE60DV8IGRK418UYD";
$client_secret= "f46ea876e3066f817dcb96ae6f4a484ed29cb11829";

/*
	$reFresh_token= "1000.5650d3b57a9ecd7f168b9eec9d991336.5521d75d8c10d51acca82bf957f5f815";
$client_id= "1000.U83ELA07E8DQ55QGE1TM1SO6VZV0CH";
$client_secret= "a49d8da19086aa6901ee1a0526b9d65a93fbbe5a05";
*/
$access_url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=".$reFresh_token."&client_id=".$client_id."&client_secret=".$client_secret."&grant_type=reFresh_token";
	if(DEPLOYMENT == 'SANDBOX')
	{
		$reFresh_token= "1000.8eeb5615a2628727b9c13d583d849760.8edbfc10931b3fe984e405e245c2458a";
$client_id= "1000.XHHYNP5SPKBTHR698T07OCTH45FDZR";
$client_secret= "fde15b519fa14fe2132654a2b0058000513639cd30";
$access_url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=".$reFresh_token."&client_id=".$client_id."&client_secret=".$client_secret."&grant_type=reFresh_token";

    }else{
		trigger_error('Production is invoked');
	}


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
trigger_error('Access Token Response ' . $access_response);
$access_err = curl_error($access_curl);
trigger_error('Access token error ' . $access_err);
curl_close($access_curl);
$res = json_decode($access_response);
$final_access_token = $res->access_token;
trigger_error('Latest Access Token ' . $final_access_token);
return $final_access_token;
}


function getAccountPlanDetails()
 {
	 global $accessToken;
	 global $accPlanId;
	 global $keyinitiativearray;
	 global $valuepropositionarray; 
	 global $barriertosuccess;
	 global $p2premittancestrategy;
	 global $p2prequiredlist;
	 global $p2prequireddetails;
	 global $b2cmasspayoutstrategy;
	 global $b2crequiredlist;
	 global $b2crequireddetails;
	 global $b2binvoicing;
	 global $b2brequiredlist;
	 global $b2brequireddetails;
	 global $c2bpayment;
	 global $c2brequiredlist;
	 global $c2brequireddetails;
	 global $accountowner;
	 global $accId;

	$p2premittancestrategy=array();
	$p2prequiredlist=array();
	$p2prequireddetails=array();
	$b2cmasspayoutstrategy=array();
	$b2crequiredlist=array();
	$b2crequireddetails=array();
	$b2binvoicing=array();
	$b2brequiredlist=array();
	$b2brequireddetails=array();
	$c2bpayment=array();
	$c2brequiredlist=array();
	$c2brequireddetails=array();

	$downloadUrl = "https://www.zohoapis.com/crm/v2/Account_Plan/".$accPlanId;
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accessToken )
        );


    $downch = curl_init();

    curl_setopt($downch, CURLOPT_URL, $downloadUrl);
    curl_setopt($downch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($downch, CURLOPT_TIMEOUT, 60);

    $result_down = curl_exec($downch);
	$response=utf8_decode($result_down);
		//trigger_error('Decoded Response ' . $response);
		$jsonresponse=json_decode($response,JSON_PRETTY_PRINT);	
		trigger_error('Account plan Response is '.$response);
	    $dataarray=$jsonresponse["data"];
	trigger_error('Account plan details is '.json_encode($dataarray));
	$accplanData=$dataarray[0];
	$accDetails=$accplanData["Account_Name"];
	$accountownerdata=$accplanData["Owner"];
	$accountowner=$accountownerdata["name"];

	$accId=$accDetails["id"];
	trigger_error('Account Id is '.$accId);
	$keyinitiativeraw=$accplanData['Key_Initiatives_Priorities'];
	$keyinitiativearray = preg_split("/\r\n|\n|\r/", $keyinitiativeraw);

	$valuepropostionraw=$accplanData['Thunes_Value_Proposition'];
	$valuepropositionarray = preg_split("/\r\n|\n|\r/", $valuepropostionraw);

	$barriertosuccess=$accplanData['Barrier_to_Success'];
	$growthstrategylist=$accplanData['Our_Growth_Strategy'];
	
	foreach($growthstrategylist as $strategy)
	{
		$vertical=$strategy['Vertical'];
		if($vertical == 'P2P Remittance')
		{
			$whatsrequired=$strategy['What_is_Required'];
			$requireddetails=$strategy['Required_Details'];
			trigger_error('P2P Required Details ' . $requireddetails);
			array_push($p2premittancestrategy,$strategy);
			
			if(!empty($whatsrequired) || !empty($requireddetails))
			{
				if(!empty($whatsrequired))
				{
					array_push($p2prequiredlist,$whatsrequired);
				}else{
					array_push($p2prequiredlist,'');
				}
				if(!empty($requireddetails))
				{
					array_push($p2prequireddetails,$requireddetails);
				}else{
					array_push($p2prequireddetails,'');
				}
			}
		}
		
		if($vertical == 'B2C Mass Payout')
		{
			$whatsrequired=$strategy['What_is_Required'];
			$requireddetails=$strategy['Required_Details'];
			array_push($b2cmasspayoutstrategy,$strategy);
			if(!empty($whatsrequired) || !empty($requireddetails))
			{
				if(!empty($whatsrequired))
				{
					array_push($b2crequiredlist,$whatsrequired);
				}else{
					array_push($b2crequiredlist,'');
				}
				if(!empty($requireddetails))
				{
					array_push($b2crequireddetails,$requireddetails);
				}else{
					array_push($b2crequireddetails,'');
				}
			}
		}

		if($vertical == 'B2B Invoicing')
		{
			$whatsrequired=$strategy['What_is_Required'];
			$requireddetails=$strategy['Required_Details'];
			array_push($b2binvoicing,$strategy);
			if(!empty($whatsrequired) || !empty($requireddetails))
			{
				if(!empty($whatsrequired))
				{
					array_push($b2brequiredlist,$whatsrequired);
				}else{
					array_push($b2brequiredlist,'');
				}
				if(!empty($requireddetails))
				{
					array_push($b2brequireddetails,$requireddetails);
				}else{
					array_push($b2brequireddetails,'');
				}
			}
		}

		if($vertical == 'C2B Payment')
		{
			$whatsrequired=$strategy['What_is_Required'];
			$requireddetails=$strategy['Required_Details'];
			array_push($c2bpayment,$strategy);
			if(!empty($whatsrequired) || !empty($requireddetails))
			{
				if(!empty($whatsrequired))
				{
					array_push($c2brequiredlist,$whatsrequired);
				}else{
					array_push($c2brequiredlist,'');
				}
				if(!empty($requireddetails))
				{
					array_push($c2brequireddetails,$requireddetails);
				}else{
					array_push($c2brequireddetails,'');
				}
			}
		}
	}
	getAccountDetails($accId);
	trigger_error($accplanData["Name"]);
	return $accplanData;
	 
 }

function getAccountDetails($accountId)
 {
	 global $accessToken;
	 global $accountData;
	 global	$bussinessOppRender;
	 global	$latestfundingyear;
	 global	$latestfunding;
	 global $finances;

	 	 global $partnername;
	$downloadUrl = "https://www.zohoapis.com/crm/v2/Accounts/".$accountId;
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accessToken )
        );


    $downch = curl_init();

    curl_setopt($downch, CURLOPT_URL, $downloadUrl);
    curl_setopt($downch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($downch, CURLOPT_TIMEOUT, 60);

    $result_down = curl_exec($downch);
	$response=utf8_decode($result_down);
		//trigger_error('Decoded Response ' . $response);
		$jsonresponse=json_decode($response,JSON_PRETTY_PRINT);	
	    $dataarray=$jsonresponse["data"];
	    trigger_error('Account details  '.json_encode($dataarray));
	    $accountData=$dataarray[0];
		trigger_error('Account Primary Business '.$accountData["Primary_Business"]);
		$businessOppArr=$accountData['Business_Opportunities'];
			$partnername=$accountData["Account_Name"];
			$finfunddetail=$accountData['Financial_Funding'];

		$p2pdataset=array("assigned"=>"No","Addressable_Opportunities"=>"","Remarks"=>"");
		$b2cdataset=array("assigned"=>"No","Addressable_Opportunities"=>"","Remarks"=>"");
		$b2bdataset=array("assigned"=>"No","Addressable_Opportunities"=>"","Remarks"=>"");
		$p2pdataset=array("assigned"=>"No","Addressable_Opportunities"=>"","Remarks"=>"");
	    $c2bdataset=array("assigned"=>"No","Addressable_Opportunities"=>"","Remarks"=>"");
		$bussinessOppRender=array("C2C Remittance"=>$p2pdataset,
				"B2C Mass Payout"=>$b2cdataset,
				"B2B Invoicing"=>$b2bdataset,
				"C2B Payment Solution"=>$c2bdataset
		);
		foreach($businessOppArr as $busineOpp)
		{
			$verticalname=$busineOpp["Vertical"];
			$businessOppdataset=$bussinessOppRender[$verticalname];
			if($businessOppdataset != null)
			{
				$businessOppdataset["assigned"]="Yes";
				$businessOppdataset["Addressable_Opportunities"]=$busineOpp["Addressable_Opportunities"];
				$businessOppdataset["Remarks"]=$busineOpp["Remarks"];
				$bussinessOppRender[$verticalname]=$businessOppdataset;	
			}
			 trigger_error("Busienss Opp to Render " . json_encode($bussinessOppRender));
	    }		


		$finances=array();
		$latestfundingyear=null;
		$latestfunding=null;
		foreach($finfunddetail as $finfundrow)
	    {
			
			$finorfund=$finfundrow['Financial_Funding'];
			$estrevenue=$finfundrow['Est_Revenue_in_M'];
            $fundamount=$finfundrow['Funding_Raised_in_M'];
			$period=$finfundrow['Period'];
			if($finorfund == 'Financial')
			{
				if(!array_key_exists($period,$finances))				
				{
					if(!empty($estrevenue))
					{
						$finances[$period]=$estrevenue;	
					}else{
						$finances[$period]='';	
					}
				}								
			}

			

			if($finorfund=='Funding')
			{
				if(empty($lastfundingyear) || $period > $latestfundingyear)
				{
					$latestfundingyear=$period;
					$latestfunding=$fundamount;
				}												
			}
	    }
		if(!empty($finances))
	    {
			ksort($finances,SORT_NUMERIC);
			if(count($finances) > 3)
			{

				$offset=count($finances)-3;
				$length=3;
				$finances=array_slice($finances,$offset,3,true);
			}else if(count($finances) == 2){
				$temp=array('-'=>'-');
				$finances=$temp+$finances;

			}else if(count($finances) == 1){
				$temp=array('-'=>'-','- '=>'-');
				$finances=$temp+$finances;
			}
		}

		trigger_error('Financial Details ' . json_encode($finances));
		trigger_error('Funding Details ' . $latestfundingyear .' '. $latestfunding );
		getContactDetails($accountId);
		getAccountTargetDetails($accountId);
 }

 function getAccountTargetDetails($accountId)
 {
	 global $currentyear;
	 global $lastyear;
	 global $nextyear;
	 global $accessToken;
	 global $accountData;
	 global $acctTargetGlobal;
	 global $pdfyear;

	//
	$currentyear=$pdfyear;
	if(empty($pdfyear))
	 {
		$currentyear=date("Y");
	 }else{

	 }

	
	$lastyear=$currentyear-1;
	$nextyear=$currentyear+1;
	$downloadUrl = "https://www.zohoapis.com/crm/v2/Accounts/".$accountId."/Accounts_Target";
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accessToken )
        );


    $downch = curl_init();
    curl_setopt($downch, CURLOPT_URL, $downloadUrl);
    curl_setopt($downch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($downch, CURLOPT_TIMEOUT, 60);

    $result_down = curl_exec($downch);
	$response=utf8_decode($result_down);
		trigger_error('AccountTarget Decoded Response ' . $response);
		$jsonresponse=json_decode($response,JSON_PRETTY_PRINT);	
	    $dataArray=$jsonresponse["data"];
	    trigger_error('Account Target Details  '.json_encode($dataArray));

		$size_of_the_array=4;
		$initvalue="";
		$section2data=array();
		
		$performancedata=array('Principal'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Margin'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Revenue'=>array_fill(0, $size_of_the_array, $initvalue));
		$targetdata=array('Principal'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Margin'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Revenue'=>array_fill(0, $size_of_the_array, $initvalue));
		$currentforcast=array('Principal'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Margin'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Revenue'=>array_fill(0, $size_of_the_array, $initvalue));
		$nextforecast=array('Principal'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Margin'=>array_fill(0, $size_of_the_array, $initvalue)
			,'Revenue'=>array_fill(0, $size_of_the_array, $initvalue));    	
		
		$acctTargetLocal=array('Performance'=>$performancedata,'Target'=>$targetdata,'CurrentForecast'=>$currentforcast,
			'FutureForecast'=>$nextforecast);

		foreach($dataArray as $singledata) 
		{
			$datayear=$singledata["Year"];
			$acctTargetType=$singledata["Type"];
			$periodentity=$singledata["PeriodEntity"];			
			
			if($datayear != null)
			{
				trigger_error('Year is ' . $datayear . ' Type ' .$acctTargetType . ' Period ' . $periodentity  );
				if($datayear == $currentyear && ($acctTargetType == 'Target'
				|| $acctTargetType == 'Forecast' ))
				{					
					if($acctTargetType == 'Target')
					{					
						
						$commonrow=$acctTargetLocal['Target'];		
						$processedrow=populateAmount($periodentity,$singledata,$commonrow);
						//trigger_error('TARGET Processed Row ' . json_encode($processedrow) );
						$acctTargetLocal['Target']=$processedrow;
					}else{
						$commonrow=$acctTargetLocal['CurrentForecast'];
						$processedrow=populateAmount($periodentity,$singledata,$commonrow);
						$acctTargetLocal['CurrentForecast']=$processedrow;
						//trigger_error('CF Processed Row ' . json_encode($processedrow) );
					}										
				}else if($datayear == $lastyear && $acctTargetType == 'Performance')
				{
						$commonrow=$acctTargetLocal['Performance'];		
						$processedrow=populateAmount($periodentity,$singledata,$commonrow);
						$acctTargetLocal['Performance']=$processedrow;
					   //trigger_error('PF Processed Row ' . json_encode($processedrow) );
				}else if($datayear == $nextyear && $acctTargetType == 'Forecast')
				{
						$commonrow=$acctTargetLocal['FutureForecast'];
						$processedrow=populateAmount($periodentity,$singledata,$commonrow);
						$acctTargetLocal['FutureForecast']=$processedrow;
						//trigger_error('FC Processed Row ' . json_encode($processedrow) );
				}		
								
			}
			
		}     
		trigger_error('Account Target after Processing ' . json_encode($acctTargetLocal));
		foreach($acctTargetLocal as $eachtargetName=>$targetData )
		{

			foreach($targetData as $dataname=>$amountrow)
			{
				$total=0;
				foreach($amountrow as $amount)
				{
					if($amount != null && !empty($amount))
					{
						$total=$total+$amount;
					}					
				}
				$amountrow[4]=$total;
				$targetData[$dataname]=$amountrow;
			}
			$acctTargetLocal[$eachtargetName]=$targetData;
		}
		trigger_error('Account Target after Total Calculation ' . json_encode($acctTargetLocal));
		$acctTargetGlobal=$acctTargetLocal;	
 }

 function populateAmount($periodentity,$singledata,$commonrow)
 {
	            if($periodentity=='Q1')
				{
					$principleamount=$singledata['Principle'];				
					$principlerow=$commonrow['Principal'];
					$principlerow[0]=$principleamount;
					$marginamount=$singledata['Margin'];				
					$marginrow=$commonrow['Margin'];
					$marginrow[0]=$marginamount;
					$revenueamount=$singledata['Revenue'];
					$revenuerow=$commonrow['Revenue'];
					$revenuerow[0]=$revenueamount;
					$commonrow['Principal']=$principlerow;
				    $commonrow['Margin']=$marginrow;
					$commonrow['Revenue']=$revenuerow;

					
				}else if($periodentity=='Q2')
				{
					$principleamount=$singledata['Principle'];				
					$principlerow=$commonrow['Principal'];
					$principlerow[1]=$principleamount;
					$marginamount=$singledata['Margin'];				
					$marginrow=$commonrow['Margin'];
					$marginrow[1]=$marginamount;
					$revenueamount=$singledata['Revenue'];
					$revenuerow=$commonrow['Revenue'];
					$revenuerow[1]=$revenueamount;
					$commonrow['Principal']=$principlerow;
				    $commonrow['Margin']=$marginrow;
					$commonrow['Revenue']=$revenuerow;
				}
				else if($periodentity=='Q3')
				{
					$principleamount=$singledata['Principle'];				
					$principlerow=$commonrow['Principal'];
					$principlerow[2]=$principleamount;
					$marginamount=$singledata['Margin'];				
					$marginrow=$commonrow['Margin'];
					$marginrow[2]=$marginamount;
					$revenueamount=$singledata['Revenue'];
					$revenuerow=$commonrow['Revenue'];
					$revenuerow[2]=$revenueamount;
					$commonrow['Principal']=$principlerow;
				    $commonrow['Margin']=$marginrow;
					$commonrow['Revenue']=$revenuerow;
				}
				else if($periodentity=='Q4')
				{
					$principleamount=$singledata['Principle'];				
					$principlerow=$commonrow['Principal'];
					$principlerow[3]=$principleamount;
					$marginamount=$singledata['Margin'];				
					$marginrow=$commonrow['Margin'];
					$marginrow[3]=$marginamount;
					$revenueamount=$singledata['Revenue'];
					$revenuerow=$commonrow['Revenue'];
					$revenuerow[3]=$revenueamount;
					$commonrow['Principal']=$principlerow;
				    $commonrow['Margin']=$marginrow;
					$commonrow['Revenue']=$revenuerow;
				}
//									trigger_error('Principle Row ' . json_encode($principlerow) . ' RR ' . json_encode($revenuerow) .
//						' MR  ' . json_encode($marginrow) . ' CR ' .json_encode($commonrow));
	return $commonrow;
 }
 
function getContactDetails($accountId)
 {
	 global $accessToken;
	 global $accountData;
	 global $contactDataArray;
	 $conDataArray=array();
	$downloadUrl = "https://www.zohoapis.com/crm/v2/Accounts/".$accountId."/Contacts";
        $headers_downloadfile = array(
        sprintf('Authorization: Zoho-oauthtoken '.$accessToken )
        );


    $downch = curl_init();

    curl_setopt($downch, CURLOPT_URL, $downloadUrl);
    curl_setopt($downch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($downch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($downch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($downch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($downch, CURLOPT_TIMEOUT, 60);

    $result_down = curl_exec($downch);
	$response=utf8_decode($result_down);
		//trigger_error('Decoded Response ' . $response);
		$jsonresponse=json_decode($response,JSON_PRETTY_PRINT);	
	    $dataArray=$jsonresponse["data"];
	    trigger_error('Contact details  '.json_encode($dataArray));
		for ($con = 0; $con < sizeof($dataArray); $con++) {
			$contactData=$dataArray[$con];
			$isKeyDecisionMaker=$contactData['Key_Decision_Makers'];
			if($isKeyDecisionMaker)
			{
				array_push($conDataArray,$contactData);
				trigger_error('Contact Name  is '.$dataArray[$con]["Full_Name"]);
			}		
		}
		$contactDataArray=$conDataArray;		
 }











try{

$mpdf = new \Mpdf\Mpdf();

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/custom-fonts/open-sans',
    ]),
    'fontdata' => $fontData + [
        'opensans' => [
            'R' => 'OpenSans-Regular.ttf',
            'B' => 'OpenSans-Regular.ttf',
        ],
		'opensansbold' => [
            'R' => 'OpenSans-Bold.ttf',
            'B' => 'OpenSans-Bold.ttf',
        ]
    ],
    'default_font' => 'opensans'
]);

$mpdf->shrink_tables_to_fit=0;
//echo 'hii'; 
$pagestyles = "<style>@page {
	size: 8.5in 11in; 
	margin: 0; 
	margin-left:29px;
	margin-right:29px;
	margin-header: 0;
	margin-footer: 0;
}
body{
	font-size:14px;
	font-family:opensans;
}
.titlediv{
	font-size:16px;
	text-align:center;
	font-family:opensansbold;
	float:left;
	width:100%;
	background:#69BE29;
		color:white;
}
.sectiontitle{
	font-size:16px;
	text-align:left;
	font-family:opensansbold;
	float:left;
	width:100%;
	color:black;
	margin-top:10px;
	
}
tr, td{
	border:.5px solid black;
	font-size:12px;
}
table{
	border-collapse: collapse;
}
</style>";

$firsttitle = "<div class='titlediv'>ACCOUNT PLAN ".$pdfyear.": " .$partnername."</div><div class='titlediv'>ACCOUNT MANAGER: ".$accountowner."</div>";

$sectiontitle1 = "<div class='sectiontitle'>Section 1  - Background </div>";

$abouttable = "<table>
<tbody>
	<tr>
		<td rowspan='8' style='font-family:opensansbold;width:15%'>
			Customer Background
		</td>
		<td style='width:15%'>
			Type of Business
		</td>
		<td colspan='5'>
			 ".$accountData["Primary_Business"]."
		</td>
	</tr>
	<tr>
		<td>
			Geographical Location 
		</td>
		<td colspan='5'>
			".$accountData["Country"]."
		</td>
	</tr>
	<tr>
		<td>
			Date of Incorporation 
		</td>
		<td colspan='5'>
			".$accountData["Date_of_Incorporation"]."
		</td>
	</tr>
	<tr>
		<td>
			Contract Due Date 
		</td>
		<td colspan='5'>
			No expiry 
		</td>
	</tr>
	<tr>
		<td>
			Description 
		</td>
		<td colspan='5'>
			".$accountData["Description"]."
		</td>
	</tr>
	<tr>
		<td colspan='2' style='background:lightgray;font-family:opensansbold;text-align:center;'>
			Latest Funding Round 
		</td>
		<td colspan='4' style='background:black;color:white;font-family:opensansbold;text-align:center;'>
			Customer Financials 
		</td>
	</tr>
	<tr>
		<td style='text-align:center;'>
			Period
		</td>
		<td style='text-align:center;width:30%;'> "
			.$latestfundingyear.
		"</td>
		<td style='text-align:center;'>
			Period
		</td>";

$financeheader="";
$fincount=count($finances);
$fincolspan=1;
if($fincount == 1)
{
	$fincolspan=3;
}
foreach($finances as $period=>$revenue)
{
	if($fincount == 1)
	{
		$financeheader=$financeheader."<td colspan='3' style='text-align:center;'>".$period."</td>";		
	}
	else{

	$financeheader=$financeheader."<td style='text-align:center;width:10%'>".$period."</td>";		
	}
}
if($fincount == 0)
	{
		$financeheader=$financeheader."<td colspan='3' style='text-align:center;'>&nbsp;</td>";		

	}
$latestfundingtoshow=$latestfunding;
if(!empty($latestfunding))
{
$latestfundingtoshow=number_format($latestfunding);
}
$financeheader = $financeheader."</tr><tr><td style='text-align:center;'>Funding (USD Million)</td>
		<td style='text-align:center;'>"
			.$latestfundingtoshow.
		"</td>";
$financeheader=$financeheader."<td style='text-align:center;'>Revenue (USD Million) </td>";
foreach($finances as $period=>$revenue)
{
	$revenuetoshow=$revenue;
	if(trim($revenue) != '-')
	{
		$revenuetoshow=number_format($revenue);
	}
	if($fincount == 1)
	{	

		$financeheader=$financeheader."<td colspan='3' style='text-align:center;'>".$revenuetoshow."</td>";		
	}else{
		$financeheader=$financeheader."<td  style='text-align:center;width:10%'>".$revenuetoshow."</td>";		
	}
}

if($fincount == 0)
{
	$financeheader=$financeheader."<td colspan='3' style='text-align:center;'>&nbsp;</td>";		
}

$financeheader=$financeheader."</tr>";
$abouttable=$abouttable.$financeheader;	
	$contactheaderrowspan=count($contactDataArray) + 1;
$contactheadercontent="
	<tr>
		<td rowspan=".$contactheaderrowspan." style='font-family:opensansbold;'>
			Key Contacts
		</td>
		<td style='background:lightgray;font-family:opensansbold;'>
			Name
		</td>
		<td style='background:lightgray;font-family:opensansbold;'>
			Role 
		</td>
		<td colspan='2' style='background:lightgray;font-family:opensansbold;'>
			Designation
		</td>
		<td  colspan='2' style='background:lightgray;font-family:opensansbold;'>
			Attitude towards Thunes
		</td>
	</tr>";
   $contacthtmlcontent="";
   foreach($contactDataArray as $eachcontact)
   {
		$contacthtmlcontent=$contacthtmlcontent."<tr>
		<td>
			".$eachcontact["Full_Name"]."
		</td>";

		$contactrole="";
		$rolearray=$eachcontact["Contact_Role1"];
		$commaseproles="";
		foreach($rolearray as $role)
	    {
			if(!empty($commaseproles))
			{
				$commaseproles=$commaseproles.",";
			}
			$commaseproles=$commaseproles.$role;				
	    }
		$contacthtmlcontent=$contacthtmlcontent."<td >".$commaseproles."</td>
		<td colspan='2'>".$eachcontact["Title"]."</td>
		<td  colspan='2'>".$eachcontact["Attitude_towards_Thunes"]."</td></tr>";
   }
   if($contacthtmlcontent != null && $contacthtmlcontent!="")
   {
		$abouttable=$abouttable.$contactheadercontent.$contacthtmlcontent;
   }

   
   $section2="<div class='sectiontitle'>Section 2  - Thunes Partnership</div><table><tbody>
	";
	$section2headers=["Performance","Target","CurrentForecast","FutureForecast"];   
	$subheaders=["Principal","Revenue","Margin"];
	$performancedata=null;	
	//trigger_error("Performance Data to render " . json_encode($performancedata));
	$accounttargetleft="<tr>
		<td rowspan='20' style='font-family:opensansbold;width:15%;'>
			Account Targets
		</td>";
	$targetdatahtml="";	
	foreach($section2headers as $header)
	{
		$performancehtml="";
		$performancedata=$acctTargetGlobal[$header];
		$targettitlehtml="";
		
		if($header == 'Performance')
		{
			$targettitlehtml="<td colspan='6' style='background:black;color:white;font-family:opensansbold;text-align:center;'>";
			$targettitlehtml=$targettitlehtml.$lastyear ." Performance </td>
			</tr>";		
		}else if($header == 'Target')
		{
			$targettitlehtml="<tr><td colspan='6' style='background:lightgray;color:black;font-family:opensansbold;text-align:center;'>";
			$targettitlehtml=$targettitlehtml.$currentyear ." Target </td>
			</tr>";		
		}
		else if($header == 'CurrentForecast')
		{
			$targettitlehtml="<tr><td colspan='6' style='background:lightgray;color:black;font-family:opensansbold;text-align:center;'>";
			$targettitlehtml=$targettitlehtml.$currentyear ." Forecast </td>
			</tr>";		
		}
		else if($header == 'FutureForecast')
		{
			$targettitlehtml="<tr><td colspan='6' style='background:lightgray;color:black;font-family:opensansbold;text-align:center;'>";
			$targettitlehtml=$targettitlehtml.$nextyear ." Forecast </td>
			</tr>";		
		}
		$targettitlehtml=$targettitlehtml."			
	<tr>
		<td style='font-family:opensansbold;'>
		</td>
		<td style='font-family:opensansbold;text-align:center;width:14%;'>
			Q1		
		</td>
		<td style='font-family:opensansbold;text-align:center;width:14%;'>
			Q2		
		</td>
		<td style='font-family:opensansbold;text-align:center;width:14%;'>
			Q3		
		</td>
		<td style='font-family:opensansbold;text-align:center;width:14%;'>
			Q4	
		</td>
		<td style='font-family:opensansbold;text-align:center;width:14%;'>
			Total		
		</td>
	</tr>";
		foreach($subheaders as $eachsub)
		{

			$rowdata=$performancedata[$eachsub];
			//trigger_error("RowData " . json_encode($rowdata));
			$performancehtml=$performancehtml."<tr>";
			$performancehtml=$performancehtml."<td>".$eachsub."</td>";
			foreach($rowdata as $amount)
			{
				$performancehtml=$performancehtml."<td style='text-align:center;'>";
				if($amount != null && !empty($amount))
				{
					$performancehtml=$performancehtml."$".number_format($amount);
					$performancehtml=$performancehtml."</td>";

				}else{
					$performancehtml=$performancehtml."-";
					$performancehtml=$performancehtml."</td>";
				}			
			}
			$performancehtml=$performancehtml."</tr>";
		}
	    trigger_error("Performance HTML " . $performancehtml);
		$targettitlehtml=$targettitlehtml.$performancehtml;
		$targetdatahtml=$targetdatahtml.$targettitlehtml;
	}
	$targetdatahtml=$accounttargetleft.$targetdatahtml;
	$section2=$section2.$targetdatahtml;

	
	$businessOpphtml="<tr>
		<td rowspan='5' style='font-family:opensansbold;'>
			Business Verticals
		</td>
		<td colspan='2' style='font-family:opensansbold;text-align:center;'>
			Thunes Business Verticals
		</td>
		<td colspan='2' style='font-family:opensansbold;text-align:center;'>
			Addressable Opportunities	
		</td>
		<td colspan='2' style='font-family:opensansbold;text-align:center;'>
			Remarks			
		</td>
	</tr>";

	$busineeOpphtmldata="";
	foreach($bussinessOppRender as $verticalname=>$dataset)
	{
		$busineeOpphtmldata=$busineeOpphtmldata."<tr>";
		$busineeOpphtmldata=$busineeOpphtmldata."<td style='text-align:center;'>".$verticalname."</td>";
		$busineeOpphtmldata=$busineeOpphtmldata."<td style='text-align:center;'>".$dataset['assigned']."</td>";
		$amount=$dataset['Addressable_Opportunities'];
		if($amount != null && !empty($amount))
		{
			$amount="USD " . number_format($amount);
		}else{
			$amount='';
		}
		$busineeOpphtmldata=$busineeOpphtmldata."<td colspan='2' style='text-align:center;'>".$amount."</td>";
		$busineeOpphtmldata=$busineeOpphtmldata."<td  colspan='2' style='text-align:center;'>".$dataset['Remarks']."</td>";
		$busineeOpphtmldata=$busineeOpphtmldata."</tr>";

	}

	
	$businessOpphtml=$businessOpphtml.$busineeOpphtmldata;
	trigger_error("Business Opportunity Content " .$busineeOpphtmldata );
	
	$keyinitiativesize=count($keyinitiativearray);
	$valuepropositionsize=count($valuepropositionarray);
	$iterationlength=$keyinitiativesize;
	if($valuepropositionsize > $keyinitiativesize)
	{
		$iterationlength=$valuepropositionsize;
	}
	$keyinitiatives="<tr>
		<td rowspan='".($iterationlength+1)."' style='font-family:opensansbold;'>
			Customer's Key Initiatives & Priorities
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;color:black;'>
			Description
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;color:black;'>
			How Thunes can help
		</td>
	</tr>";
	$keyinitiativecontent="";
	trigger_error("KeyInitiative content " . json_encode($keyinitiativearray));
		trigger_error("valuepropositionarray content " . json_encode($valuepropositionarray));
	for($i=0;$i<$iterationlength;$i++)
	{

		$keyinitiativecontent=$keyinitiativecontent."<tr>";
		if($keyinitiativesize > $i)
		{
			$keyinitiativecontent=$keyinitiativecontent."<td colspan='3'>".$keyinitiativearray[$i]." </td>";
		}else{
			$keyinitiativecontent=$keyinitiativecontent."<td colspan='3'></td>";
		}
		if($valuepropositionsize > $i)
		{
			$keyinitiativecontent=$keyinitiativecontent."<td colspan='3'>".$valuepropositionarray[$i]." </td>";
		}else{
			$keyinitiativecontent=$keyinitiativecontent."<td colspan='3'> </td>";
		}
		$keyinitiativecontent=$keyinitiativecontent."</tr>";
	}
	$keyinitiatives=$keyinitiatives.$keyinitiativecontent."</tbody></table>";

$section2=$section2.$businessOpphtml.$keyinitiatives;

$growthtitle = "<div class='sectiontitle'>Section 3  - Growth Strategy </div>";

$growthtable = "<div style='float:leftlwidth:100%;background:gray;color:white;font-family:opensansbold;padding-left:10px;'>P2P Remittance</div><table style='position:relative;'><tbody>";


$p2pspan=count($p2premittancestrategy )+1;
$growthp2pheaders="<tr>
		<td rowspan='".$p2pspan."' style='font-family:opensansbold;width:15%'>
			Objective(s) & Action Plan(s)
		</td>
		<td colspan='2' style='font-family:opensansbold;background:lightgray;'>
			Objective/Focus
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;width:42%;'>
			Activities/Action Plan(s)
		</td>
		<td style='font-family:opensansbold;background:lightgray;width:14%;'>
			Expected Delivery
		</td>
	</tr>";
$growthtable=$growthtable.$growthp2pheaders;
$growthp2pcontent="";
foreach($p2premittancestrategy as $p2psingle)
{

	$objective=$p2psingle['Objective_Focus'];
	$activities=$p2psingle['Activities_Action_Plans'];	
		$delivery=$p2psingle['Expected_Delivery'];	
		$growthp2pcontent=$growthp2pcontent."<tr><td colspan='2'>".$objective.
	"</td><td colspan='3'>".$activities."</td>".
	"<td>".$delivery."</td></tr>";
}
$growthtable=$growthtable.$growthp2pcontent;
$p2prequiredlistsize=count($p2prequiredlist);
$p2pdetailsize=count($p2prequireddetails);
$iterationlength=$p2prequiredlistsize;
	if($p2pdetailsize > $p2prequiredlistsize)
	{
		$iterationlength=$p2pdetailsize;
	}

$whatsrequiredspan=$iterationlength+1;
$p2pwhatsrequiredcontent="
	<tr><td colspan='7'>&nbsp;</td></tr><tr>
		<td rowspan='".$whatsrequiredspan."' style='font-family:opensansbold;width:15%'>
			What is Required?
		</td>";
$p2prequireddata="";	
/*
trigger_error("P2P Required List " .json_encode($p2prequiredlist ));
	trigger_error("P2p Iteration Length " . $iterationlength);
		trigger_error("P2p Details Size  " . $p2pdetailsize);
		trigger_error('P2p required details ' . json_encode($p2prequireddetails));
		*/
for($i=0;$i<$iterationlength;$i++)
{
	if($p2prequiredlistsize > $i)
	{
		$p2prequireddata=$p2prequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>".$p2prequiredlist[$i]."</td>";
	}else{
		$p2prequireddata=$p2prequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>&nbsp;</td>";
	}

	if($p2pdetailsize > $i)
	{
		$p2prequireddata=$p2prequireddata."<td colspan='3' style='width:42%;'>".$p2prequireddetails[$i]."</td>";
		
	}
	else{
		$p2prequireddata=$p2prequireddata."<td colspan='3' style='width:42%;'>&nbsp;</td>";
	}
    $p2prequireddata=$p2prequireddata."<td></td></tr>"	;	
}
if(empty($p2prequireddata))
{
	$p2prequireddata=$p2prequireddata."
		<td colspan='2' style='font-family:opensansbold;'>
			&nbsp;
		</td>
		<td colspan='3' >
			&nbsp;
		</td>
		<td >
			&nbsp;
		</td>
	</tr>";

}

$growthtable=$growthtable.$p2pwhatsrequiredcontent.$p2prequireddata."</tbody></table>";

/**** B2C MASS PAYOUT ***/

$b2cspan=count($b2cmasspayoutstrategy )+1;
if($b2cspan == 1)
{
	$b2cspan=2;
}

$growthtable2 = "<div style='float:leftlwidth:100%;background:gray;color:white;font-family:opensansbold;padding-left:10px;margin-top:20px;'>B2C Mass Payout</div><table style='position:relative;'><tbody>";
$growthb2cheaders="<tr>
		<td rowspan='".$b2cspan."' style='font-family:opensansbold;width:15%'>
			Objective(s) & Action Plan(s)
		</td>
		<td colspan='2' style='font-family:opensansbold;background:lightgray;'>
			Objective/Focus
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;width:42%;'>
			Activities/Action Plan(s)
		</td>
		<td style='font-family:opensansbold;background:lightgray;width:14%;'>
			Expected Delivery
		</td>
	</tr>";
$growthtable2=$growthtable2.$growthb2cheaders;

$growthb2ccontent="";
foreach($b2cmasspayoutstrategy as $b2csingle)
{

	$objective=$b2csingle['Objective_Focus'];
	$activities=$b2csingle['Activities_Action_Plans'];	
		$delivery=$b2csingle['Expected_Delivery'];	
		$growthb2ccontent=$growthb2ccontent."<tr><td colspan='2'>".$objective.
	"</td><td colspan='3'>".$activities."</td>".
	"<td>".$delivery."</td></tr>";
}
if(empty($growthb2ccontent))
{
	$growthb2ccontent=$growthb2ccontent."<tr><td colspan='2'> &nbsp;
	</td><td colspan='3'> &nbsp;</td>
	<td>&nbsp;</td></tr>";
}
$growthtable2=$growthtable2.$growthb2ccontent;
$b2crequiredlistsize=count($b2crequiredlist);
$b2cdetailsize=count($b2crequireddetails);
$iterationlength=$b2crequiredlistsize;
	if($b2cdetailsize > $b2crequiredlistsize)
	{
		$iterationlength=$b2cdetailsize;
	}

$whatsrequiredspan=$iterationlength+1;
$b2cwhatsrequiredcontent="
	<tr><td colspan='7'>&nbsp;</td></tr><tr>
		<td rowspan='".$whatsrequiredspan."' style='font-family:opensansbold;width:15%'>
			What is Required?
		</td>";
$b2crequireddata="";	
trigger_error("B2C Required List " .json_encode($b2crequiredlist));
for($i=0;$i<$iterationlength;$i++)
{
	if($b2crequiredlistsize > $i)
	{
		$b2crequireddata=$b2crequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>".$b2crequiredlist[$i]."</td>";
	}else{
		$b2crequireddata=$b2crequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>&nbsp;</td>";
	}

	if($b2cdetailsize > $i)
	{
		$b2crequireddata=$b2crequireddata."<td colspan='3' style='width:42%;'>".$b2crequireddetails[$i]."</td>";
		
	}
	else{
		$b2crequireddata=$b2crequireddata."<td colspan='3' style=';width:42%;'>&nbsp;</td>";
	}
    $b2crequireddata=$b2crequireddata."<td></td></tr>"	;	
}
if(empty($b2crequireddata))
{
	$b2crequireddata=$b2brequireddata."
		<td colspan='2' style='font-family:opensansbold;'>
			&nbsp;
		</td>
		<td colspan='3' >
			&nbsp;
		</td>
		<td >
			&nbsp;
		</td>
	</tr>";

}
$growthtable2=$growthtable2.$b2cwhatsrequiredcontent.$b2crequireddata."</tbody></table>";
//trigger_error('Growth TABLE222222 ' . $growthtable2);
/*********************************B2C END **************************************************/

/************************ B2B Invoicing *************************************************/
$b2bspan=count($b2binvoicing)+1;
if($b2bspan == 1)
{
	$b2bspan=2;
}
$growthtable3 = "<div style='float:leftlwidth:100%;background:gray;color:white;font-family:opensansbold;padding-left:10px;margin-top:20px;'>B2B Invoicing</div><table style='position:relative;'><tbody>";
$growthb2bheaders="<tr>
		<td rowspan='".$b2bspan."' style='font-family:opensansbold;width:15%'>
			Objective(s) & Action Plan(s)
		</td>
		<td colspan='2' style='font-family:opensansbold;background:lightgray;'>
			Objective/Focus
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;width:42%;'>
			Activities/Action Plan(s)
		</td>
		<td style='font-family:opensansbold;background:lightgray;width:14%;'>
			Expected Delivery
		</td>
	</tr>";
$growthtable3=$growthtable3.$growthb2bheaders;

$growthb2bcontent="";
foreach($b2binvoicing as $b2bsingle)
{

	$objective=$b2bsingle['Objective_Focus'];
	$activities=$b2bsingle['Activities_Action_Plans'];	
		$delivery=$b2bsingle['Expected_Delivery'];	
		$growthb2bcontent=$growthb2bcontent."<tr><td colspan='2'>".$objective.
	"</td><td colspan='3'>".$activities."</td>".
	"<td>".$delivery."</td></tr>";
}
if(empty($growthb2bcontent))
{
	$growthb2bcontent=$growthb2bcontent."<tr><td colspan='2'> &nbsp;
	</td><td colspan='3'> &nbsp;</td>
	<td>&nbsp;</td></tr>";
}
$growthtable3=$growthtable3.$growthb2bcontent;
$b2brequiredlistsize=count($b2brequiredlist);
$b2bdetailsize=count($b2brequireddetails);
$iterationlength=$b2brequiredlistsize;
	if($b2bdetailsize > $b2brequiredlistsize)
	{
		$iterationlength=$b2bdetailsize;
	}

$whatsrequiredspan=$iterationlength+1;
$b2bwhatsrequiredcontent="
	<tr><td colspan='7'>&nbsp;</td></tr><tr>
		<td rowspan='".$whatsrequiredspan."' style='font-family:opensansbold;width:15%'>
			What is Required?
		</td>";
$b2brequireddata="";	
trigger_error("B2B Required List " .json_encode($b2brequireddata));
for($i=0;$i<$iterationlength;$i++)
{
	if($b2brequiredlistsize > $i)
	{
		$b2brequireddata=$b2brequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>".$b2brequiredlist[$i]."</td>";
	}else{
		$b2brequireddata=$b2brequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>&nbsp;</td>";
	}

	if($b2bdetailsize > $i)
	{
		$b2brequireddata=$b2brequireddata."<td colspan='3' style='width:42%;'>".$b2brequireddetails[$i]."</td>";
		
	}
	else{
		$b2brequireddata=$b2brequireddata."<td colspan='3' style='width:42%;'>&nbsp;</td>";
	}
    $b2brequireddata=$b2brequireddata."<td></td></tr>"	;	
}
if(empty($b2brequireddata))
{
	$b2brequireddata=$b2brequireddata."
		<td colspan='2' style='font-family:opensansbold;'>
			&nbsp;
		</td>
		<td colspan='3' >
			&nbsp;
		</td>
		<td >
			&nbsp;
		</td>
	</tr>";

}
$growthtable3=$growthtable3.$b2bwhatsrequiredcontent.$b2brequireddata."</tbody></table>";
//trigger_error('Growth TABLE33333333 ' . $growthtable3);

/*****************************************  END OF B2B INVOICING ***************************************************/
/*** C2B PAYMENT SOLUTION ********************/
$c2pspan=count($c2bpayment)+1;
if($c2pspan == 1)
{
	$c2pspan=2;
}
$growthtable4 = "<div style='float:leftlwidth:100%;background:gray;color:white;font-family:opensansbold;padding-left:10px;margin-top:20px;'>C2B Payment Solution</div><table style='position:relative;'><tbody>";
$growthc2bheaders="<tr>
		<td rowspan='".$c2pspan."' style='font-family:opensansbold;width:15%'>
			Objective(s) & Action Plan(s)
		</td>
		<td colspan='2' style='font-family:opensansbold;background:lightgray;'>
			Objective/Focus
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;width:42%;'>
			Activities/Action Plan(s)
		</td>
		<td style='font-family:opensansbold;background:lightgray;width:14%;'>
			Expected Delivery
		</td>
	</tr>";
$growthtable4=$growthtable4.$growthc2bheaders;

$growthc2bcontent="";
foreach($c2bpayment as $c2bsingle)
{

	$objective=$c2bsingle['Objective_Focus'];
	$activities=$c2bsingle['Activities_Action_Plans'];	
		$delivery=$c2bsingle['Expected_Delivery'];	
		$growthc2bcontent=$growthc2bcontent."<tr><td colspan='2'>".$objective.
	"</td><td colspan='3'>".$activities."</td>".
	"<td>".$delivery."</td></tr>";
}
if(empty($growthc2bcontent))
{
	$growthc2bcontent=$growthc2bcontent."<tr><td colspan='2'> &nbsp;
	</td><td colspan='3'> &nbsp;</td>
	<td>&nbsp;</td></tr>";
}
$growthtable4=$growthtable4.$growthc2bcontent;
$c2brequiredlistsize=count($c2brequiredlist);
$c2bdetailsize=count($c2brequireddetails);
$iterationlength=$c2brequiredlistsize;
	if($c2bdetailsize > $c2brequiredlistsize)
	{
		$iterationlength=$c2bdetailsize;
	}

$whatsrequiredspan=$iterationlength+1;
$c2bwhatsrequiredcontent="
	<tr><td colspan='7'>&nbsp;</td></tr><tr>
		<td rowspan='".$whatsrequiredspan."' style='font-family:opensansbold;width:15%'>
			What is Required?
		</td>";
$c2brequireddata="";	
trigger_error("B2B Required List " .json_encode($c2brequireddata));
for($i=0;$i<$iterationlength;$i++)
{
	if($c2brequiredlistsize > $i)
	{
		$c2brequireddata=$c2brequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>".$c2brequiredlist[$i]."</td>";
	}else{
		$c2brequireddata=$c2brequireddata."<tr><td colspan='2' style='font-family:opensansbold;'>&nbsp;</td>";
	}

	if($c2bdetailsize > $i)
	{
		$c2brequireddata=$c2brequireddata."<td colspan='3' style='width:42%;'>".$c2brequireddetails[$i]."</td>";
		
	}
	else{
		$c2brequireddata=$c2brequireddata."<td colspan='3' style='width:42%;'>&nbsp;</td>";
	}
    $c2brequireddata=$c2brequireddata."<td></td></tr>"	;	
}
if(empty($c2brequireddata))
{
	$c2brequireddata=$c2brequireddata."
		<td colspan='2' style='font-family:opensansbold;'>
			&nbsp;
		</td>
		<td colspan='3' >
			&nbsp;
		</td>
		<td >
			&nbsp;
		</td>
	</tr>";

}
$growthtable4=$growthtable4.$c2bwhatsrequiredcontent.$c2brequireddata."</tbody></table>";
//trigger_error('Growth TABLE33333333 ' . $growthtable4);
/****************** END C2B PAYMENT *****************************/

$section4title = "<div class='sectiontitle' style='margin-top:20px;'>Section 4 - Barrier to Success & Mitigation Plan</div>";


$section4table = "<table style='position:relative;'><tbody>";
$barriersize=count($barriertosuccess)+1;
$section4datathml="<tr>
		<td rowspan='".$barriersize."' style='font-family:opensansbold;width:15%'>
			Barriers to Success
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;width:42%;'>
			Description
		</td>
		<td colspan='3' style='font-family:opensansbold;background:lightgray;width:42%;'>
			Plan to Overcome
		</td>
	</tr>";
foreach($barriertosuccess as $eachbarrier)
{
	$section4datathml=$section4datathml."<tr><td colspan='3'>".$eachbarrier['Barriers_to_Success']."</td>";
	$section4datathml=$section4datathml."<td colspan='3'>".$eachbarrier['Plans_to_Overcome']."</td>";
	$section4datathml=$section4datathml."</tr>";
}
$section4table=$section4table.	$section4datathml."</tbody></table>";

$mpdf->SetHTMLHeader("<img src='footer-logo.png' style='height:40px;margin-top:20px;'/>");
$mpdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        5, // margin_left
        5, // margin right
       20, // margin top
       5, // margin bottom
        0, // margin header
        0); // margin footer
$mpdf->WriteHTML($pagestyles);
$mpdf->WriteHTML($firsttitle);
$mpdf->WriteHTML($sectiontitle1);
$unusedSpaceH = $mpdf->h - $mpdf->y - $mpdf->bMargin;
trigger_error('Unused Space BEFORE OUTER' . $unusedSpaceH . ' Total ' . $mpdf->h);
$mpdf->WriteHTML($abouttable."</tbody></table>");
$unusedSpaceH = $mpdf->h - $mpdf->y - $mpdf->bMargin;
trigger_error('Unused Space ABOUT OUTER' . $unusedSpaceH);
getSection1Height($pagestyles,$firsttitle,$sectiontitle1,$abouttable. ' Total ' . $mpdf->h);

$mpdf->WriteHTML($section2."</tbody></table>");
//$mpdf->AddPage();
$mpdf->WriteHTML($growthtitle);
$mpdf->WriteHTML($growthtable);
$mpdf->WriteHTML($growthtable2);
$mpdf->WriteHTML($growthtable3);
$unusedSpaceH = $mpdf->h - $mpdf->y - $mpdf->bMargin;
trigger_error('Unused Space H111111111 *' . $unusedSpaceH . ' Total ' . $mpdf->h);
//$mpdf->AddPage();
$unusedSpaceH = $mpdf->h - $mpdf->y - $mpdf->bMargin;
trigger_error('Unused Space H 3333333 *' . $unusedSpaceH .' Total ' . $mpdf->h);
$mpdf->WriteHTML($growthtable4);
$section4data=$section4title.$section4table;
$mpdf->WriteHTML($section4data);
$unusedSpaceH = $mpdf->h - $mpdf->y - $mpdf->bMargin;
trigger_error('Unused Space H 22222222 *' . $unusedSpaceH . ' Total ' . $mpdf->h);


#$mpdf->SetAutoFont();
$current = date('dmY');//time();
trigger_error($accountData["Account_Name"]);
$filePath=PDFTARGETDIR.DIRECTORY_SEPARATOR.$accountData["Account_Name"].' '.$current. ' ' .$loginuser.".pdf";
$fileName=$accountData["Account_Name"].' '.$current. ' ' .$loginuser.".pdf";
$mpdf->Output($filePath);
//echo "Hello",$filePath;

if($action == 'save')
{
		trigger_error('file exists '.file_exists($filePath));
		attachPdf($filePath);
}

if($action == 'view')
	{
		header('Content-type: application/pdf');   
		header('Content-Disposition: inline; filename="' . $filePath . '"');   
		header('Content-Transfer-Encoding: binary');   
		header('Accept-Ranges: bytes');   
		// Read the file 
		@readfile($filePath); 

	}

}catch(Exception $e)
{
	trigger_error('Umnable to generate PDF ' . $filePath);
}

function getSection1Height($pagestyles,$firsttitle,$sectiontitle1,$abouttable)
{
	$mpdf = new \Mpdf\Mpdf();

	$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
	$fontDirs = $defaultConfig['fontDir'];

	$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
	$fontData = $defaultFontConfig['fontdata'];

	$mpdf = new \Mpdf\Mpdf([
		'fontDir' => array_merge($fontDirs, [
			__DIR__ . '/custom-fonts/open-sans',
		]),
		'fontdata' => $fontData + [
			'opensans' => [
				'R' => 'OpenSans-Regular.ttf',
				'B' => 'OpenSans-Regular.ttf',
			],
			'opensansbold' => [
				'R' => 'OpenSans-Bold.ttf',
				'B' => 'OpenSans-Bold.ttf',
			]
		],
		'default_font' => 'opensans'
	]);

	$mpdf->shrink_tables_to_fit=0;
	$mpdf->SetHTMLHeader("<img src='footer-logo.png' style='height:40px;margin-top:20px;'/>");
$mpdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        5, // margin_left
        5, // margin right
       20, // margin top
       5, // margin bottom
        0, // margin header
        0); // margin footer
$mpdf->WriteHTML($pagestyles);
$mpdf->WriteHTML($firsttitle);
$mpdf->WriteHTML($sectiontitle1);
$mpdf->WriteHTML($abouttable."</tbody></table>");

//$mpdf->WriteHTML($section2);
$unusedSpaceH = $mpdf->h - $mpdf->y - $mpdf->bMargin;
trigger_error('Unused Space from About Table' . $unusedSpaceH);
}

function getSectionHeight($section2)
{
	$mpdf = new \Mpdf\Mpdf();

	$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
	$fontDirs = $defaultConfig['fontDir'];

	$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
	$fontData = $defaultFontConfig['fontdata'];

	$mpdf = new \Mpdf\Mpdf([
		'fontDir' => array_merge($fontDirs, [
			__DIR__ . '/custom-fonts/open-sans',
		]),
		'fontdata' => $fontData + [
			'opensans' => [
				'R' => 'OpenSans-Regular.ttf',
				'B' => 'OpenSans-Regular.ttf',
			],
			'opensansbold' => [
				'R' => 'OpenSans-Bold.ttf',
				'B' => 'OpenSans-Bold.ttf',
			]
		],
		'default_font' => 'opensans'
	]);

	$mpdf->shrink_tables_to_fit=0;
	$mpdf->SetHTMLHeader("<img src='footer-logo.png' style='height:40px;margin-top:20px;'/>");
$mpdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
        5, // margin_left
        5, // margin right
       20, // margin top
       5, // margin bottom
        0, // margin header
        0); // margin footer
$mpdf->WriteHTML($pagestyles);
$mpdf->WriteHTML($firsttitle);
$mpdf->WriteHTML($sectiontitle1);
$mpdf->WriteHTML($abouttable."</tbody></table>");

//$mpdf->WriteHTML($section2);
$unusedSpaceH = $mpdf->h - $mpdf->y - $mpdf->bMargin;
trigger_error('Unused Space from About Table' . $unusedSpaceH);
}

function attachPdf($filePath)
 {
	 global $mpdf;
	 global $fileName;
	 global $accessToken;
	 global $accId;
	 $fileContent = file_get_contents($filePath);
	 trigger_error("Account  id is" .$accId);
	 trigger_error("File contentt ".$fileContent);
	$downloadUrl = "https://www.zohoapis.com/crm/v2/Accounts/".$accId."/Attachments";
	$boundary = uniqid();
      $delimiter = '-------------' . $boundary;
      $post_data = build_data_files($boundary,$fileName, $fileContent);


    $headers_downloadfile = array(
       'Authorization: Zoho-oauthtoken '.$accessToken,
	   "Content-Type: multipart/form-data; boundary=" . $delimiter,
       "Content-Length: " . strlen($post_data)
        );
    $upch = curl_init();
	 
    curl_setopt($upch, CURLOPT_URL, $downloadUrl);
    curl_setopt($upch, CURLOPT_HTTPHEADER, $headers_downloadfile);
    curl_setopt($upch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($upch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($upch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($upch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($upch, CURLOPT_TIMEOUT, 60);

    $result_down =curl_exec($upch);
	$response=utf8_decode($result_down);
		trigger_error('Decoded Response ' . $response);
		$jsonresponse=json_decode($response,JSON_PRETTY_PRINT);	
    $responsefile=__DIR__.DIRECTORY_SEPARATOR.'successresponse.html';
	@readfile($responsefile); 
 }
 
 
 function build_data_files($boundary, $name, $content){
	  $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;
	  $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="file"; filename="' . $name . '"' . $eol
            . 'Content-Type: application/pdf'.$eol
            . 'Content-Transfer-Encoding: binary'.$eol
            ;

        $data .= $eol;
        $data .= $content . $eol;
    
    $data .= "--" . $delimiter . "--".$eol;


    return $data;
 }
?>
