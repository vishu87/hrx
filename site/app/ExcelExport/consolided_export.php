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
$activeSheet->setTitle("Event Details");

$ar_names = array(
        "ISIN Number",
        "Script Name",
        "Type of Report",
        "Location",
        "Holding Date(CUT OFF DATE)",
        "DB Deadline",
        "Company Deadline",
        "Evoting Start",
        "Evoting End",
        "Event Date",
        "Weblink",
        "Group Name"
        );

$ar_fields = array("meeting_isin", "com_full_name","meeting_type","meeting_city","record_date","deadline_date","company_deadline","evoting_start","evoting_end","meeting_date","notice_link","group_name");

$ar_width = array("15","25","20","20","20","20","20","20","45","45","45","45","45","100","30","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20");

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
 $spreadsheet->getActiveSheet()->getStyle('A'.$seq.':L'.$seq)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ffc2cad8');

$seq++;

foreach ($event_details as $row) {
    
    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if($ar=='blank' ){
            $var = '';
        } elseif($ar == "notice_link") { 
            $var = $row->notice_link;

            if($row->meeting_type == "AGM" && $row->annual_report){
                $var .= ",".$row->annual_report;
            }

        } elseif ($ar == "group_name") {
            $var = "";
            if(isset($row->groups)){
                $var = implode(', ', $row->groups);
            }
        } else {
            $var = (isset($row->$ar))?$row->$ar:'';
        }
        $i++;

        $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
    }

    $seq++;
}

$spreadsheet->createSheet();
$spreadsheet->setActiveSheetIndex(1);
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("Synopsis");

$ar_names = array("SES Event ID","ISIN","Company Name","Event Date","Meeting Type","Proposal" , "Type","No","Summary Proposals","Your Vote* (FOR/AGAINST/ABSTAIN)","Your Comment");

$ar_fields = array("id","meeting_isin", "com_full_name","meeting_date","meeting_type","man_share_reco","type","resolution_number","resolution_name","voted","blank");

$ar_width = array("8","15","25","12","10","15","10","5","30","15","30","30","25","25","10","30","30");

$spreadsheet->getActiveSheet()->setCellValue("A1", "*The votes shall be applied across all schemes in that particular ISIN/event.");

$seq = 2;

$i = 0;
$offset = 0;
$count = 0;
foreach ($ar_fields as $ar) {

    $cell_val = $i + $offset;
    $cell_val = $this->getNameFromNumber($cell_val);

    $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $ar_names[$count]);
    $spreadsheet->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
    $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->applyFromArray($styleArrayborder);
    $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->getAlignment()->setWrapText(true);
    $i++;
    $count++;
    
    $spreadsheet->getActiveSheet()->getStyle('A'.$seq.':K'.$seq)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ffc2cad8');
}
$seq++;
$com_id = 0;
$count_com = 0;
foreach ($syno_records as $record) {

    if(sizeof($record->resolutions) > 0){
        $com_id = $record->id;
        $count_com++;
        foreach ($record->resolutions as $row) {
            
            $i = 0;

            foreach ($ar_fields as $ar) {
                $var = '';
                $cell = $i + $offset;
                $cell_val = $this->getNameFromNumber($cell);

                if($ar=='blank' ){
                    $var = '';
                } elseif($ar == 'id'){
                    $var = (isset($record->$ar))?$record->$ar:'';
                } elseif($ar == 'meeting_isin'){
                    $var = (isset($record->$ar))?$record->$ar:'';
                } elseif($ar == 'com_full_name'){
                    $var = (isset($record->$ar))?$record->$ar:'';
                } elseif($ar == 'meeting_type'){
                    $var = (isset($record->$ar))?$record->$ar:'';
                } elseif($ar == 'meeting_date'){
                    $var = (isset($record->$ar))?date("d/M/Y",strtotime($record->$ar)):'';
                } elseif($ar == 'voted'){
                    $var = "";
                    if($record->status == 1){
                        $var = "Voted";
                    } elseif($record->status == 2){
                        $var = "Partially Voted";
                    } elseif($record->status == 0 && $record->evoting_ended){
                        if($record->evoting_plateform == "Physical"){
                            $var = "e-Voting ended";
                        } else {
                            $var = env("APP_CLIENT").' Deadline Ended';
                        }
                    }
                } else {
                    $var = (isset($row->$ar))?$row->$ar:'';
                }
                $i++;

                $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);

                $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->getAlignment()->setWrapText(true);


            }
            if($count_com%2==0){

                $spreadsheet->getActiveSheet()->getStyle('A'.$seq.':K'.$seq)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffeaeaea');
            }

            $seq++;
            
        }
        // $seq++;
    }
}

// die();
$spreadsheet->setActiveSheetIndex(0);

$filename = 'Event Details With Synopsis_'.date("d-m-Y",strtotime("today")).'.xlsx';

$writer = new Xlsx($spreadsheet);

if(env("FTP_STATUS") == 1){
    $path = app_path()."/../uploads/";
    $writer->save($path.$filename);
    echo "Meeting Details has been saved to SFTP";
} else {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename); 
    header('Cache-Control: max-age=0');
    $writer->save("php://output");

}

exit();