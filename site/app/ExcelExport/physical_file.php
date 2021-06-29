<?php
namespace App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

$spreadsheet = new Spreadsheet();
$Excel_writer = new Xlsx($spreadsheet);
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();

$sheet_name = 'VOTE_'.'Physical'.'_'.$report->even;

$activeSheet->setTitle($sheet_name);

$seq = 1;

$ar_names = array("Instruction Date (ddmmyyyy) *","Instruction Ref. no. *","TYPE OF INSTRUCTION (NEW / REVISED /CANCELLED)*","Group Number**","Account no**","ISIN*","COMPANY_NAME*","EVENT_DATE (ddmmyy)*","MEETING_TYPE*","Proposal (Optional)","Type of Resolution (Optional)","RESOLUTION NO*","SUMMARY_PROPOSAL (optional)","VOTE (FOR/ AGAINST/ ABSTAIN)*","REASON SUPPORTING THE VOTE DECISION (optional)","DBT Event no (Optional)","Quantity / Record date Quantity [R]*","Event time*","Previous Instructon Ref. no (to be filled with  Instruction ref no. of the NEWmessage,  in case of Revised / Cancellation instruction)");

$ar_fields = array("instruction_date", "blank","instruction_type", "group_no", "customer_number","com_isin", "com_name", "meeting_date_format","meeting_type_name", "man_share_reco","type_res_os", "resolution_number", "resolution_name","vote", "comment","even","shares_held", "meeting_time");

$ar_width = array("15","25","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20");

$seq = 1;
$offset = 0;
$count = 0;
$i = 0;
$spreadsheet->setActiveSheetIndex(0);
foreach ($ar_fields as $ar) {

    $cell_val = $i + $offset;
    $cell_val = $this->getNameFromNumber($cell_val);

    $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $ar_names[$count]);
    $spreadsheet->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
    $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->applyFromArray($styleArrayborder);
    $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->getAlignment()->setWrapText(true);
    $i++;
    $count++;
}


$seq = 2;
$count = 0;
foreach ($store_results as $voting) {
    $i = 0;

    foreach ($ar_fields as $ar) {
        $cell = $i;
        $cell_val = $this->getNameFromNumber($cell);
        if($ar=='blank' ){
            $var = "";
        } else if(in_array($ar, ["meeting_type_name","meeting_date_format","meeting_time","even"])){
            $var = (isset($report->$ar))?$report->$ar:'';
        } else if(in_array($ar, ["com_isin","com_name","man_share_reco","type_res_os","resolution_name","comment","instruction_date","group_no","customer_number"])){
            $var = "";
            if(isset($other_info[$count])){
                if($ar == "instruction_date"){
                    $var = (isset($other_info[$count][$ar]))?date("d-m-Y",strtotime($other_info[$count][$ar])):"";
                } else {
                    $var = (isset($other_info[$count][$ar]))?$other_info[$count][$ar]:"";
                }
            }
        } else if($ar == "instruction_type"){
            $var = 'NEWM';
        } else if($ar == "vote"){
            $var = '';
            if($voting["vote"] == 1) $var = "FOR";
            if($voting["vote"] == 2) $var = "AGAINST";
            if($voting["vote"] == 3) $var = "ABSTAIN";
        } else {
            $var = (isset($voting[$ar]))?$voting[$ar]:'';
        }

        $spreadsheet ->getActiveSheet()-> setCellValue($cell_val . $seq, $var);

        $i++;
    }

    $seq++;
    $count++;
}

$filename = $name.'.xlsx';

$writer = new Xlsx($spreadsheet);

$path = app_path()."/../temp/";
$writer->save($path.$filename);
