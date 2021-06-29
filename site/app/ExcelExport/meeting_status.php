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
$activeSheet->setTitle("Status");

$ar_names = array("Security Name", "ISIN Number","Meeting Type","Holding Date / Record Date","Event Date","eVoting Deadline","Client","Status","Total Schemes","Total Approved","Vote File Downloaded By","Vote File Downloaded On","Response File Uploaded By","Response File Uploaded On");

$ar_fields = array("com_full_name", "meeting_isin","meeting_type","record_date","meeting_date","evoting_end","user_name","status","total_schemes","vote_approved","vf_dwn_by","vf_dwn_on","rf_upl_by","rf_upl_on");

$ar_width = array("25","15","12","12","12","12","20","20","12","12","12","12","12","12","12");

$spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('ffaaaaaa');

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

$seq++;
$report_covered = 0;
$last_report_id = 0;
foreach ($reports as $row) {
    
    if($row->report_id != $last_report_id){
        $report_covered++;
        $last_report_id = $row->report_id;
    }

    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        $resp_file_uploaded = false;

        if(isset($response_data[$row->report_id]) && $row->vote_approved != 0){
            if(isset($response_records[$row->report_id])){
                if(in_array($row->user_id, $response_records[$row->report_id])){
                    $resp_file_uploaded = true;
                }
            }
        }

        if($ar == 'status'){

            $var = 'Pending';
            
            if ($row->total_schemes == $row->vote_approved){
                $var = "Voted by client";
            }

            if ($row->total_schemes > $row->vote_approved && $row->vote_approved > 0){
                $var = "Partially voted by client";
            }

            if ($row->total_schemes == $row->vote_approved){
                $var = "Voted by client";
            }

            if ($row->total_schemes <= ($row->total_voted + $row->total_abstained)){
                $var = "Voted by Custodian";
            }

            if ($row->total_schemes == $row->total_abstained){
                $var = "All Abtsained";
            }

            if($row->vote_approved == 0){
                $meeting_date = date("Y-m-d",strtotime($row->meeting_date));
                $today = date("Y-m-d");
                if($meeting_date < $today){
                    $var = "Not voted";
                }
            }

            if($resp_file_uploaded){
                $var = "Response file uploaded";
            }

        } elseif($ar == 'vf_dwn_by'){
            $var = "";
            if(isset($votefile_data[$row->report_id]) && $row->vote_approved != 0){
                $var = $votefile_data[$row->report_id]->name;
            }

        } elseif($ar == 'vf_dwn_on'){

            $var = "";
            if(isset($votefile_data[$row->report_id]) && $row->vote_approved != 0){
                $var = date("d-m-y H:i:s", strtotime($votefile_data[$row->report_id]->updated_at));
            }
            
        } elseif($ar == 'rf_upl_by'){

            $var = "";
            if(isset($response_data[$row->report_id]) && $row->vote_approved != 0){
                if($resp_file_uploaded){
                    $var = $response_data[$row->report_id]->name;
                }
            }
            
        } elseif($ar == 'rf_upl_on'){

            $var = "";
            if(isset($response_data[$row->report_id]) && $row->vote_approved != 0){
                if($resp_file_uploaded){
                    $var = date("d-m-Y H:i:s",strtotime($response_data[$row->report_id]->created_at));
                }
            }
            
        } else {
            $var = (isset($row->$ar))?$row->$ar:'';
        }
        $i++;

        $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
    }

    if($report_covered%2 == 0){
        $spreadsheet->getActiveSheet()->getStyle('A'.$seq.':N'.$seq)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('dddddd');
    }

    $seq++;
}

$filename = 'MeetingStatus_'.date("dmY_hiA",strtotime("now")).'.xlsx';

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