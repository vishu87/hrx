<?php

$destination = "temp/";

$filename = $name.'.txt';

$file = fopen("../".$destination.$filename, "w");

$header_line = "44=".sizeof($votings_all)."~30948\r\n";
fwrite($file, $header_line);

$line = "";
$total_abstain = true;
foreach ($votings_all as $voting) {

    $vote_array = [
        1 => '~'.$shares_held[$voting->scheme_id]['shares_held'].'~0~0',
        2 => '~0~'.$shares_held[$voting->scheme_id]['shares_held'].'~0',
        3 => '~0~0~'.$shares_held[$voting->scheme_id]['shares_held']
    ];

    $line .= "01=".$report->even."~";
    $line .= $schemes[$voting->scheme_id]["dp_id"].$schemes[$voting->scheme_id]["client_id"]."~";
    $line .= $resolutions[$voting->voting_id];
    $line .= $vote_array[$voting->vote]."\r\n";

    if($voting->vote != 3){
    	$total_abstain = false;
    }

}

if($total_abstain) $line = "Abstain vote for all resolution";

fwrite($file, $line);
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