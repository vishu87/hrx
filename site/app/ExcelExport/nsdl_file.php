<?php

$destination = "temp/";

$filename = $name.'.txt';

$file = fopen("../".$destination.$filename, "w");
fwrite($file, '{}'."\r\n");

$batch_id = str_pad($batch_id, 7, '0', STR_PAD_LEFT);

$total_votes = 0;

$line = "";
$total_abstain = 0;

foreach ($votings_all as $voting) {

	if($voting->vote == 3) {
		$total_abstain++;
		continue;
	}

	$line .= $batch_id."^12^";

	if($schemes[$voting->scheme_id]["depository"] == "NSDL"){
		$line .= "N^";
	} elseif($schemes[$voting->scheme_id]["depository"] == "CDSL") {
		$line .= "C^";
	}

	$line .= $schemes[$voting->scheme_id]["dp_id"].$schemes[$voting->scheme_id]["client_id"]."^";

	$line .= "100016^".$resolutions[$voting->voting_id]."^".$voting->vote."^";

	$shares_value = $shares_held[$voting->scheme_id]['shares_held'];

	$shares_value = str_pad($shares_value, 15, '0', STR_PAD_LEFT);

	$line .= $shares_value."000\r\n";

	$total_votes++;

}

$header_line = $batch_id."^11^IN300167^".$total_votes."^".$report->even."^".date("Ymd")."^".date("Hi")."\r\n";
fwrite($file, $header_line);

if($total_abstain == sizeof($votings_all)){
	fwrite($file, "Abstain vote for all resolution");
}

fwrite($file, $line);
fwrite($file, '{}'."\r\n");

fclose($file);

// if(file_exists($destination.$filename)){
//     header('Content-Type: application/octet-stream');
//     header('Content-Disposition: attachment; filename='.basename($filename));
//     header('Expires: 0');
//     header('Cache-Control: must-revalidate');
//     header('Pragma: public');
//     header('Content-Length: ' . filesize($filename));
//     readfile($filename);
//     unlink($filename);
//     exit;
// }
?>