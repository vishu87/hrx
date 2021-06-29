<?php
namespace App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    'font' => array('bold' => true,'color' => array('argb' => 'FF000000',),),
    'alignment' => array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,),
    'fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'color' => array('argb' => 'FFd3d3d3',),),
    'borders' => array(
        'outline' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('argb' => 'FF000000'),
        ),
    ),
 );

$spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/

$invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');

$sheet_count = 0;


$sheetTitle = "Consolidated";
$spreadsheet->setActiveSheetIndex($sheet_count);

if($with_quarter){
    $ar_fields = array("quarter","meeting_date", "com_full_name","meeting_type","man_share_reco","resolution_name","man_reco","vote_value","comment");

    $ar_names = array("Quarter","Meeting Date", "Company Name","Type of Meeting","Proposal by Management or Shareholder","Proposal","Investee company's Management Recommendation","Vote(For/Against/Abstrain)","Reason supporting the vote decision");
    $last_col = "I";
} else {

    $ar_fields = array("meeting_date", "com_full_name","meeting_type","man_share_reco","resolution_name","man_reco","vote_value","comment","result","resolution_number","isin");

    $ar_names = array("Meeting Date", "Company Name","Type of Meeting","Proposal by Management or Shareholder","Proposal","Investee company's Management Recommendation","Vote(For/Against/Abstrain)","Reason supporting the vote decision","Result of Meeting","Resolution No","ISIN");
    $last_col = "K";
}

$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle(substr($sheetTitle ,0,30));
$spreadsheet->getActiveSheet()->mergeCells('A1:'.$last_col.'1');
$spreadsheet -> getActiveSheet() -> setCellValue('A1' , "Details of Votes cast during from ".date('dMy',strtotime($request->date_from))." to ".date('dMy',strtotime($request->date_to))." , of financial year ".$financial_year);
$spreadsheet -> getActiveSheet() -> getStyle('A1')->applyFromArray($styleArray4);

$seq = 2;

$ar_width = array("20","25","20","20","20","20","20","20","20","15","15","15","15","15","30","20","20","20","20","20","20");

$spreadsheet -> getActiveSheet() -> getColumnDimension('A') -> setWidth(25);
$spreadsheet -> getActiveSheet() -> getRowDimension('1') -> setRowHeight(25);

$count = 1;

$total_resolutions = 0;
$total_for = 0;
$total_against = 0;
$total_abstain = 0;

$last_report_id = 0;
$count_reports = 0;

$quarter_total = [];

foreach ($final_records[$client->id] as $row) {
    
    if($last_report_id != $row->report_id){

        if($count_reports != 0){
            $seq++;
        }

        $offset = 0;
        $count = 0;
        $i = 0;
        foreach ($ar_fields as $ar) {

            $cell_val = $i + $offset;
            $cell_val = $this->getNameFromNumber($cell_val);

            $spreadsheet -> getActiveSheet() -> setCellValue($cell_val . $seq, $ar_names[$count]);
            $spreadsheet -> getActiveSheet() -> getColumnDimension($cell_val)->setWidth($ar_width[$count]);
            $spreadsheet -> getActiveSheet() -> getStyle($cell_val . $seq)->applyFromArray($styleArray4);

            $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->getAlignment()->setWrapText(true);

            $i++;
            $count++;

        }
        
        $spreadsheet -> getActiveSheet() -> getStyle('A'.$seq.':'.$last_col.$seq)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('ffd3d3d3');

        
        $seq++;
        $count_reports++;
    }

    $last_report_id = $row->report_id;

    $i = 0;
    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if($ar == 'sn'){
            $var = $count++;
        }elseif($ar == 'quarter'){
            $var = '';

            $month = date("n",strtotime($row->meeting_date));

            if($month < 4){
                $var = 4;
                $quarter = 4;
            } else if($month < 7) {
                $var = 1;
                $quarter = 1;
            } else if($month < 10) {
                $var = 2;
                $quarter = 2;
            } else {
                $var = 3;
                $quarter = 3;
            }

        }else {
            $var = (isset($row->{$ar}))?$row->{$ar}:'';
        }
        $i++;

        $spreadsheet ->getActiveSheet()-> setCellValue($cell_val . $seq, $var);

        $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->getAlignment()->setWrapText(true);
    }

    //total
    $total_resolutions++;
    if($row->vote == 1) $total_for++;
    if($row->vote == 2) $total_against++;
    if($row->vote == 3) $total_abstain++;


    //quarter wise
    if(isset($quarter)){
        $year = date("Y",strtotime($row->meeting_date));
        if(!isset($quarter_total[$year.$quarter])){
            $quarter_total[$year.$quarter] = array(
                "total_resolutions" => 0,
                "total_for" => 0,
                "total_against" => 0,
                "total_abstain" => 0
            );
        }
        $quarter_total[$year.$quarter]["total_resolutions"]++;
        if($row->vote == 1) $quarter_total[$year.$quarter]["total_for"]++;
        if($row->vote == 2) $quarter_total[$year.$quarter]["total_against"]++;
        if($row->vote == 3) $quarter_total[$year.$quarter]["total_abstain"]++;
    }
    //quarter wise end

    $seq++;

}
$seq++;

$spreadsheet->createSheet();
$sheet_count++;
$spreadsheet->setActiveSheetIndex($sheet_count);

if($with_quarter){
    $spreadsheet->getActiveSheet()->setCellValue('A1', 'Summary of Votes cast');
    $spreadsheet->getActiveSheet()->mergeCells('A1:F1');


    $spreadsheet->getActiveSheet()->setCellValue('A2', 'F.Y.');
    $spreadsheet->getActiveSheet()->setCellValue('B2', 'Quarter');
    $spreadsheet->getActiveSheet()->setCellValue('C2', 'Total No. of Resolutions');
    $spreadsheet->getActiveSheet()->setCellValue('D2', 'Break Up of Vote Decision');
    $spreadsheet->getActiveSheet()->mergeCells('A2:A3');
    $spreadsheet->getActiveSheet()->mergeCells('B2:B3');
    $spreadsheet->getActiveSheet()->mergeCells('C2:C3');

    $spreadsheet->getActiveSheet()->mergeCells('D2:F2');

    $spreadsheet->getActiveSheet()->setCellValue('D3', 'FOR');
    $spreadsheet->getActiveSheet()->setCellValue('E3', 'AGAINST');
    $spreadsheet->getActiveSheet()->setCellValue('F3', 'ABSTAIN');

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);

    $seq = 4;
    foreach ($quarter_total as $key => $quarter_details) {

        $year = substr($key, 0,4);
        $quarter = substr($key, 4,1);
        if($quarter == 4) $year = $year -1;

        $fy = $year."-".substr($year + 1,2,2);

        $spreadsheet->getActiveSheet()->setCellValue('A'.$seq, $fy);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$seq, $quarter);

        $spreadsheet->getActiveSheet()->setCellValue('C'.$seq, $quarter_details["total_resolutions"]);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$seq, $quarter_details["total_for"]);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$seq, $quarter_details["total_against"]);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$seq, $quarter_details["total_abstain"]);

        $seq++;
    }

} else {
    $spreadsheet->getActiveSheet()->setCellValue('A1', 'Summary of Votes cast');
    $spreadsheet->getActiveSheet()->mergeCells('A1:D1');


    $spreadsheet->getActiveSheet()->setCellValue('A2', 'Total No. of Resolutions');
    $spreadsheet->getActiveSheet()->setCellValue('B2', 'Break Up of Vote Decision');
    $spreadsheet->getActiveSheet()->mergeCells('A2:A3');
    $spreadsheet->getActiveSheet()->mergeCells('B2:D2');

    $spreadsheet->getActiveSheet()->setCellValue('B3', 'FOR');
    $spreadsheet->getActiveSheet()->setCellValue('C3', 'AGAINST');
    $spreadsheet->getActiveSheet()->setCellValue('D3', 'ABSTAIN');

    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);

    $spreadsheet->getActiveSheet()->setCellValue('A4', $total_resolutions);
    $spreadsheet->getActiveSheet()->setCellValue('B4', $total_for);
    $spreadsheet->getActiveSheet()->setCellValue('C4', $total_against);
    $spreadsheet->getActiveSheet()->setCellValue('D4', $total_abstain);
}



$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("Summary");

$spreadsheet->setActiveSheetIndex(0);

$Excel_writer=IOFactory::createWriter($spreadsheet, 'Xlsx');
// if(env("FTP_STATUS") == 1){
    
// } else {
//     header('Content-Type: application/vnd.ms-excel');
//     header('Content-Disposition: attachment;filename="'. $filename); 
//     header('Cache-Control: max-age=0');
//     $Excel_writer->save("php://output");

// }

$path = app_path()."/../temp/";
$Excel_writer->save($path.$filename);