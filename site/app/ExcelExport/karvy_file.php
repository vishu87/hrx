<?php
namespace App;
use Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// error_reporting(0);

$styleArrayborder = array(
    'borders' => array(
        'outline' => array(
            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => 'FF4E81BE'),
        ),
    ),
    'fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'color' => array('argb' => 'FF4E81BE',),),
);

$styleArray4 = array(
    'font' => array('bold' => true,'color' => array('argb' => 'FFFFFFFF',),),
    'alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,),
    'fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'color' => array('argb' => 'FF4E81BE',),),
    'borders' => array(
        'outline' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('argb' => 'FF000000'),
        ),
    ),
);

$karvy_user_id = Auth::user()->employee_code;
$karvy_pan = "";
$karvy_poa_flag = "Y";

$spreadsheet = new Spreadsheet();
$Excel_writer = new Xlsx($spreadsheet);
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();

$sheet_name = 'VOTE_DB'.$karvy_user_id.'_'.$report->even;

$activeSheet->setTitle($sheet_name);

$seq = 1;
$spreadsheet->getActiveSheet()->setCellValue('A'.$seq, 'EVEN');
$spreadsheet->getActiveSheet()->setCellValue('B'.$seq, 'USER_ID');
$spreadsheet->getActiveSheet()->setCellValue('C'.$seq, 'DPIDCLID');
$spreadsheet->getActiveSheet()->setCellValue('D'.$seq, 'PAN');
$spreadsheet->getActiveSheet()->setCellValue('E'.$seq, 'NAME');
$spreadsheet->getActiveSheet()->setCellValue('F'.$seq, 'POA_FLAG');
$spreadsheet->getActiveSheet()->setCellValue('G'.$seq, 'VOTING_RIGHTS');
$spreadsheet->getActiveSheet()->setCellValue('H'.$seq, 'VOTING_STATUS');
$spreadsheet->getActiveSheet()->setCellValue('I'.$seq, 'RESOU_NO');
$spreadsheet->getActiveSheet()->setCellValue('J'.$seq, 'VOTES_FAVOUR');
$spreadsheet->getActiveSheet()->setCellValue('K'.$seq, 'VOTES_AGAINST');
$spreadsheet->getActiveSheet()->setCellValue('L'.$seq, 'ABSTAIN');

$seq = 2;

foreach ($votings_all as $voting) {

    $shares_value = $shares_held[$voting->scheme_id]['shares_held'];

    $spreadsheet->getActiveSheet()->setCellValue('A'.$seq, $report->even);
    $spreadsheet->getActiveSheet()->setCellValue('B'.$seq, "dbank");
    // $spreadsheet->getActiveSheet()->setCellValue('B'.$seq, $karvy_user_id);

    $spreadsheet->getActiveSheet()->getStyle("C".$seq)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    $spreadsheet->getActiveSheet()->setCellValue('C'.$seq, $schemes[$voting->scheme_id]["dp_id"].$schemes[$voting->scheme_id]["client_id"]);

    // $spreadsheet->getActiveSheet()->getStyle("C".$seq)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
    // $spreadsheet->getActiveSheet()->getStyle("C".$seq)->getNumberFormat()->setFormatCode("000000000000000000");

    // $spreadsheet->getActiveSheet()->setCellValueExplicit('C'.$seq, $schemes[$voting->scheme_id]["dp_id"].$schemes[$voting->scheme_id]["client_id"], NumberFormat::FORMAT_TEXT);


    $spreadsheet->getActiveSheet()->setCellValue('D'.$seq, $karvy_pan);
    $spreadsheet->getActiveSheet()->setCellValue('E'.$seq, $schemes[$voting->scheme_id]["scheme_name"]);
    $spreadsheet->getActiveSheet()->setCellValue('F'.$seq, $karvy_poa_flag);
    $spreadsheet->getActiveSheet()->setCellValue('G'.$seq, $shares_value);
    $spreadsheet->getActiveSheet()->setCellValue('H'.$seq, 'N');
    $spreadsheet->getActiveSheet()->setCellValue('I'.$seq, $resolutions[$voting->voting_id]);

    $spreadsheet->getActiveSheet()->setCellValue('J'.$seq, 0);
    $spreadsheet->getActiveSheet()->setCellValue('K'.$seq, 0);
    $spreadsheet->getActiveSheet()->setCellValue('L'.$seq, 0);

    if($voting->vote == 1){
        $spreadsheet->getActiveSheet()->setCellValue('J'.$seq, $shares_value);
    } else if($voting->vote == 2) {
        $spreadsheet->getActiveSheet()->setCellValue('K'.$seq, $shares_value);
    } else if($voting->vote == 3) {
        $spreadsheet->getActiveSheet()->setCellValue('L'.$seq, $shares_value);
    }

    $seq++;
}

$spreadsheet->createSheet();
$spreadsheet->setActiveSheetIndex(1);
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("Instructions");

$spreadsheet->getActiveSheet()->setCellValue('A1', "Instructions for Voting");
$spreadsheet->getActiveSheet()->setCellValue('A2', "1.");
$spreadsheet->getActiveSheet()->setCellValue('B2', "Total of votes in favour and against should not exceed voting rights.");
$spreadsheet->getActiveSheet()->setCellValue('A3', "2.");
$spreadsheet->getActiveSheet()->setCellValue('B3', "For abstain from voting, enter all votes in abstain column and enter 0 in favour and against column.");
$spreadsheet->getActiveSheet()->setCellValue('A4', "3.");
$spreadsheet->getActiveSheet()->setCellValue('B4', "Partial voting is allowed, i.e if a client is holding 100 shares he can vote 90 in favour.");
$spreadsheet->getActiveSheet()->setCellValue('A5', "4.");
$spreadsheet->getActiveSheet()->setCellValue('B5', "No columns (favour,against & abstain) cannot contain blanks.");
$spreadsheet->getActiveSheet()->setCellValue('A6', "5.");
$spreadsheet->getActiveSheet()->setCellValue('B6', "Voting should be done against all resolutions for all clients.");


$spreadsheet->createSheet();
$spreadsheet->setActiveSheetIndex(2);
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("Resolutions");

$spreadsheet->getActiveSheet()->setCellValue('A1', "Resolution Details for ".$report->com_name);
$spreadsheet->getActiveSheet()->mergeCells('A1:D1');

$spreadsheet->getActiveSheet()->setCellValue('A2', "EVEN");
$spreadsheet->getActiveSheet()->setCellValue('B2', "RESOU_NO");
$spreadsheet->getActiveSheet()->setCellValue('C2', "RESOLUTION DESCRIPTION");
$spreadsheet->getActiveSheet()->setCellValue('D2', "RESOLUTION TYPE");

$seq = 3;
foreach ($resolutions_all as $resolution) {

    if($resolution->type_res_os == 1) $type_res_os = "Ordinary";
    if($resolution->type_res_os == 2) $type_res_os = "Special";

    $spreadsheet->getActiveSheet()->setCellValue('A'.$seq, $report->even);
    $spreadsheet->getActiveSheet()->setCellValue('B'.$seq, $resolution->resolution_number);
    $spreadsheet->getActiveSheet()->setCellValue('C'.$seq, $resolution->resolution_name);
    $spreadsheet->getActiveSheet()->setCellValue('D'.$seq, $type_res_os);
    $seq++;
}

$spreadsheet->setActiveSheetIndex(0);

$filename = $name.'.xlsx';

$writer = new Xlsx($spreadsheet);

$path = app_path()."/../temp/";
$writer->save($path.$filename);
