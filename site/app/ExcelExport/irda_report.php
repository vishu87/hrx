<?php
namespace App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
error_reporting(0);

$styleArrayborder = array(
    'borders' => array(
        'outline' => array(
            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
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
$activeSheet->setTitle("IRDA Report");
$spreadsheet->getActiveSheet()->mergeCells('A1:H1');
$spreadsheet -> setActiveSheetIndex(0) -> setCellValue('A1' , "Details of Votes cast during from ".date('dMy',strtotime($request->date_from))." to ".date('dMy',strtotime($request->date_to))." , of financial year 2018-2019");

$ar_fields = array("sn", "meeting_date", "com_bse_code","com_isin","evoting_plateform","meeting_type","man_reco","vote_value");

$ar_names = array("SN", "Meeting Date", "BSE Code","ISIN","Evoting Platform" ,"Types of Meeting (AGM/EGM/PB/TCM) Proposal" , "Management Recommendation" , "Vote (For/AGAINST/ABSTAIN) and Rationale");
$ar_width = array("6","25","20","20","20","20","20","20","20","45","45","45","45","45","30","20","20","20","20","20","20");

$spreadsheet -> getActiveSheet() -> getColumnDimension('A') -> setWidth(25);
$spreadsheet -> getActiveSheet() -> getRowDimension('1') -> setRowHeight(25);
$seq = 2;
$offset = 0;
$count = 0;
$i = 0;
foreach ($ar_fields as $ar) {

    $cell_val = $i + $offset;
    $cell_val = $this->getNameFromNumber($cell_val);

    $spreadsheet -> setActiveSheetIndex(0) -> setCellValue($cell_val . $seq, $ar_names[$count]);
    $spreadsheet -> getActiveSheet() -> getColumnDimension($cell_val)->setWidth($ar_width[$count]);
    $spreadsheet -> getActiveSheet() -> getStyle($cell_val . $seq)->applyFromArray($styleArrayborder);
    $i++;
    $count++;

}

$spreadsheet -> setActiveSheetIndex(0) -> getStyle('A2:M2')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('2977f7');

$spreadsheet -> getActiveSheet() -> getStyle('A' . $seq . ':' . $cell_val . $seq);

$seq++;

$count = 1;
foreach ($resolutions as $row) {
    
    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if($ar == 'sn'){
            $var = $count++;
        }else if($ar == 'meeting_type'){
            $var = $row->com_name."\r"."Resolution Number -".$row->resolution_number."\r".$row->resolution_name;
        }else if($ar == 'vote_value'){
            $var = $row->vote_value."\r".$row->comment;
        }
        else {
            $var = (isset($row->$ar))?$row->$ar:'';
        }
        $i++;

        $spreadsheet ->getActiveSheet()-> setCellValue($cell_val . $seq, $var);
    }

    $spreadsheet -> getActiveSheet() -> getStyle('A' . $seq . ':E' . $seq);

    $seq++;
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
header('Cache-Control: max-age=0');
$Excel_writer=IOFactory::createWriter($spreadsheet, 'Xlsx');
$Excel_writer->save('php://output');
exit();