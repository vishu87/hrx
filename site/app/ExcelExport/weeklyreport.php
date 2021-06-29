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
$spreadsheet->getActiveSheet();
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("Weekly Report");

$ar_names = array("Group Name","Account No.","Client Name","Client Code", "ISIN Code","Scrip Name","Type of Meeting","Holding Date","DB Deadline","Company Deadline","Event Date","Status of Instruction");

$ar_fields = array("short_code","customer_number","scheme_name","short_name", "meeting_isin", "com_full_name","meeting_type_name","record_date","deadline_date","evoting_end","meeting_date","status");

$ar_width = array("15","25","20","20","20","20","20","20","20","20","20","50","20","20");

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

$spreadsheet->getActiveSheet()->getStyle('A1:AF1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('b3caf2');

$seq++;

$last_isin = "";
$last_meeting_type = "";
$last_date = "";
$count = 0;

foreach ($records as $row) {
    
    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if($ar == 'status'){
            $var = '';

            if(!$row->record_date){
                $var = "Event details not available";
            } else {
                if($row->status != 3){
                    $var = "Votes are not approved by client";
                } else {
                    $var = "Votes approved by client";

                    if($row->voting_processed == 1){
                        $var = "Votes approved by client and processed by custodian";
                    } elseif(in_array($row->evoting_plateform, ["nsdl"]) && $row->all_abstain == 1 ){
                        $var = "Votes received, but client has abstained for all resolution";
                    } elseif ($row->vote_file_download) {
                        $var = "Votes approved by client and freezed by custodian";
                    }
                }
            }

        } else {
            $var = (isset($row->$ar))?$row->$ar:'';
        }
        $i++;

        $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
    }

    $seq++;
}

$writer = new Xlsx($spreadsheet);
$path = app_path()."/../temp/";
$writer->save($path.$filename);