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

$spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/
$Excel_writer = new Xlsx($spreadsheet);  /*----- Excel (Xls) Object*/
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("Schemes");

$ar_names = array("GRUL-PROX-GRP-ID","GRUL-GROUPNO","CUSTOMER NO","GRUL-SHORTNAME","GRUP-SHORTCODE","Client name","NSDL id","CDSL id","Status","Added By","Approved By");

$ar_fields = array("blank", "group_id","customer_number","short_name","short_code","scheme_name","nsdl_id","cdsl_id","status","added_by","approved_by");

$ar_width = array("15","15","20","20","20","20","20","20","20","20","20","20","20");

$seq = 1;
$offset = 0;
$count = 0;
$i = 0;
foreach ($ar_fields as $ar) {

    $cell_val = $i + $offset;
    $cell_val = $this->getNameFromNumber($cell_val);

    $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell_val . $seq, $ar_names[$count]);
    $spreadsheet->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
    $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->applyFromArray($styleArrayborder);
    $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->getAlignment()->setWrapText(true);
    $i++;
    $count++;
}

$seq++;

foreach ($schemes as $row) {
    
    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if($ar == 'blank' ){
            
        } else if($ar == "status") {
            $var = $row->getStatus();
        } else if($ar == "approved_by") {
            $var = isset($approvals[$row->id]) ? $approvals[$row->id]["approved_by_name"] : "";
        } else if($ar == "added_by") {
            $var = isset($approvals[$row->id]) ? $approvals[$row->id]["created_by_name"] : "";
        } else if($ar == "nsdl_id") {
            if( $row->depository == "NSDL") {
                $var = $row->scheme_master_id;
            }
        } else if($ar == "cdsl_id") {
            if( $row->depository == "CDSL") {
                $var = $row->scheme_master_id;
            }
        } else {
            $var = (isset($row->{$ar}))?$row->{$ar}:'';
        }
        $i++;

        $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
    }

    $seq++;
}

if($request->date){
    $filename = 'SchemesExport_'.date("dmY",strtotime($request->date)).'.xlsx';
} else {
    $filename = 'SchemesExport_'.date("dmY",strtotime("today")).'.xlsx';    
}


$writer = new Xlsx($spreadsheet);

if(env("FTP_STATUS") == 1){
    $path = app_path()."/../uploads/";
    $writer->save($path.$filename);
} else {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename); 
    header('Cache-Control: max-age=0');
    $writer->save("php://output");

}

exit();