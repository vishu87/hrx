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
$activeSheet->setTitle("Followup Report");

$ar_names = array("ISIN Number","Scrip code","Security Name","Meeting Type","Holding Date / Record Date","Event Date","Result Date","Source of data (Date)","Flag(N=New, M=Modification, C=Cancellation/Withdrawal)","Annual report upload date (Depository holding)","DB Deadline date","Venue of Meeting","COMPANY DEADLINE","Link","eVoting_start_date / time","eVoting_End_date / time","eVoting_DbDeadline_date","Depository(NSDL/CDSL) ","Evoting platform Event No ","No of Resoltuions","Tracking (Y/N)","INSTRUMENT MASTER STATUS","Intimation Date","Agenda notice sent Date","Agenda with Synopsis sent Date","Reliance upload Date","Shareholder module update (Date)","Shareholder module update (Event no)","Result of Meeting ","Result of Meeting weblink","Result intimation date","SES ID");

$ar_fields = array("meeting_isin", "scrip_code", "com_full_name","meeting_type","record_date","meeting_date","result_date","source_of_data","flag","annual_report_date","deadline_date","meeting_venue","company_deadline","notice_link","evoting_start","evoting_end","deadline_date_evoting","evoting_plateform","even","resolutions","tracking","instrument_status","intimation_date","agenda_notice_date","agenda_synopsis_date","reliance_date","shareholder_module_update_date","shareholder_module_update","result_of_voting","meeting_results","result_initimation_date","id");

$ar_width = array("15","25","20","20","20","20","20","20","45","45","45","45","45","100","30","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20","20");

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

$spreadsheet->getActiveSheet()->getStyle('B1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('aaaaaa');

$spreadsheet->getActiveSheet()->getStyle('J1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('aaaaaa');

$spreadsheet->getActiveSheet()->getStyle('U1:AB1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('aaaaaa');


$seq++;

$last_isin = "";
$last_meeting_type = "";
$last_date = "";
$count = 0;

$covered_ids = [];

foreach ($records as $row) {

    if(in_array($row->id, $covered_ids)){
        continue;
    } else {
        $covered_ids[] = $row->id;
    }
    
    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if($ar == 'blank'){
            $var = '';
        } elseif($ar == "deadline_date_evoting") { 
            $var = ($row->evoting_plateform == "Physical")?"":$row->deadline_date;
        } elseif($ar == "com_full_name") { 
            $var = ($row->com_full_name == "")?$row->com_name:$row->com_full_name;
        } elseif($ar == "notice_link") { 
            $var = $row->notice_link;

            if($row->meeting_type == "AGM" && $row->annual_report){
                $var .= ",".$row->annual_report;
            }

        } elseif ($ar == "meeting_type") {

            if($last_date == $row->meeting_date && $last_meeting_type == $row->meeting_type && $last_isin == $row->com_isin){
                $count++;
                $var = $row->meeting_type.$count;
            } else {
                $count = 0;
                $var = $row->meeting_type;
            }
        
        } else {
            $var = (isset($row->$ar))?$row->$ar:'';
        }
        $i++;

        $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
    }

    // if($row->updated){
    //     $spreadsheet->getActiveSheet()->getStyle('A'.$seq.':AF'.$seq)->getFill()
    //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //     ->getStartColor()->setARGB('dddddd');
    // }

    $last_isin = $row->com_isin;
    $last_meeting_type = $row->meeting_type;
    $last_date = $row->meeting_date;

    $seq++;
}

$filename = 'Events_Detail_'.date("dmY",strtotime("today")).'.xlsx';

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