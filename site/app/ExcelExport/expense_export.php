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

$ar_names = array("SN","Date","Expense Category","Expense type","Amount","Expense From","Remarks");

$ar_fields = array("sn","date","expense_category","expense_type","total_amount","expense_account","remarks");

$ar_width = array("10","10","10","20","15","15","15","15","15","15","15","15","15","15","15","15","15","15","15");

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
$count = 0;

foreach ($expenses as $row) {
    
    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if($ar == 'sn' ){
            $var = ++$count;
        } else if($ar == "date") {
            $var = date("d-m-Y",strtotime($row->{$ar}));
        } else {
            $var = (isset($row->{$ar}))?$row->{$ar}:'';
        }
        $i++;

        $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
    }

    $seq++;
}

$filename = 'Expenses_'.date("dmY",strtotime("today")).'.xlsx';

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'. $filename); 
header('Cache-Control: max-age=0');
$writer->save("php://output");

exit();