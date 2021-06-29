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

$styleArray1 = array(
    'font' => array('bold' => true,'color' => array('argb' => 'FF000000',),),
    'alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,),
    'fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'color' => array('argb' => 'FFDDDDDD',),),
    'borders' => array(
        'outline' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('argb' => 'FF000000'),
        ),
    ),
 );

$spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/
$Excel_writer = new Xlsx($spreadsheet);  /*----- Excel (Xls) Object*/
$spreadsheet->getActiveSheet();
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("eVoting Alerts");

$spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth("10");
$spreadsheet->getActiveSheet()->getColumnDimension("B")->setWidth("15");
$spreadsheet->getActiveSheet()->getColumnDimension("C")->setWidth("20");
$spreadsheet->getActiveSheet()->getColumnDimension("D")->setWidth("15");
$spreadsheet->getActiveSheet()->getColumnDimension("E")->setWidth("15");
$spreadsheet->getActiveSheet()->getColumnDimension("F")->setWidth("15");
$spreadsheet->getActiveSheet()->getColumnDimension("G")->setWidth("15");

$seq = 1;
foreach($meetings as $num_days => $meeting_ar){
    $name = "Meeting alerts for ".$num_days." day";
    $name .= ($num_days > 1)?"s":"";

    $spreadsheet->getActiveSheet()->setCellValue("A". $seq, $name);
    $seq++;

    $spreadsheet->getActiveSheet()->setCellValue("A". $seq, "SN");
    $spreadsheet->getActiveSheet()->setCellValue("B". $seq, "ISIN");
    $spreadsheet->getActiveSheet()->setCellValue("C". $seq, "Company Name");
    $spreadsheet->getActiveSheet()->setCellValue("D". $seq, "Meeting Type");
    $spreadsheet->getActiveSheet()->setCellValue("E". $seq, "Meeting Date");
    $spreadsheet->getActiveSheet()->setCellValue("F". $seq, "Evoting End");
    $spreadsheet->getActiveSheet()->setCellValue("G". $seq, "DB Deadline");
    $spreadsheet->getActiveSheet()->getStyle("A".$seq.":G".$seq)->applyFromArray($styleArray1);

    $seq++;

    $count = 1;
    foreach($meeting_ar as $meeting){
        
        $spreadsheet->getActiveSheet()->setCellValue("A". $seq, $count++);
        $spreadsheet->getActiveSheet()->setCellValue("B". $seq, $meeting->com_isin);
        $spreadsheet->getActiveSheet()->setCellValue("C". $seq, $meeting->com_name);
        $spreadsheet->getActiveSheet()->setCellValue("D". $seq, $meeting->meeting_type_name);
        $spreadsheet->getActiveSheet()->setCellValue("E". $seq, $meeting->meeting_date);
        $spreadsheet->getActiveSheet()->setCellValue("F". $seq, $meeting->evoting_end);
        $spreadsheet->getActiveSheet()->setCellValue("G". $seq, $meeting->deadline_date);

        $seq++;
    }

    if(sizeof($meeting_ar) == 0){
        $spreadsheet->getActiveSheet()->setCellValue("A". $seq, "No meetings found");
    }

    $seq = $seq + 2;
}

$writer = new Xlsx($spreadsheet);
$path = app_path()."/../temp/";
$writer->save($path.$filename);
