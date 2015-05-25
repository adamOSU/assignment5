<?php
$xml=simplexml_load_file("http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findCompletedItems&SERVICE-NAME=FindingService&SERVICE-VERSION=1.13.0&GLOBAL-ID=EBAY-US&SECURITY-APPNAME=SLcoinafa-2536-4ac4-af19-68edaf5f9f6&RESPONSE-DATA-FORMAT=XML&REST-PAYLOAD&categoryId=11970&itemFilter(0).name=SoldItemsOnly&itemFilter(0).value=true&aspectFilter(0).aspectName=Certification&aspectFilter(0).aspectValueName=PCGS&aspectFilter(1).aspectName=Certification&aspectFilter(1).aspectValueName=NGC") or die("Error: Cannot create object");

$mysqli = new mysqli("localhost", "root", "", "testdb");
if ($mysqli->connect_errno)
{
	echo "Connection error ".$mysqli->connect_errno ." ".$mysqli->connect_error;
}

if ($xml->ack == "Success") 
{
  $results = '';

  foreach($xml->searchResult->item as $item) 
  {
  	$title = $item->title;
  	$idNum = $item->itemId;
  	$price = $item->sellingStatus->currentPrice;
  	$date = $item->listingInfo->endTime;

  	$stmt = $mysqli->prepare("INSERT INTO SoldItems (title, idNum, price, sellDate) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssss", $title, $idNum, $price, $date);
	$stmt->execute();

  }
}

else {
  $results  = "<h3>Uh-oh.  There was an error with your API call.";
}
$stmt->close();
?>