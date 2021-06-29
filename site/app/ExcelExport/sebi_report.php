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
foreach ($client->schemes as $scheme) {

    if(sizeof($scheme->reports) > 0){

        $sheetTitle = str_replace($invalidCharacters, '', $scheme->customer_number);
        $spreadsheet->setActiveSheetIndex($sheet_count);

        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle(substr($sheetTitle ,0,30));
        $spreadsheet->getActiveSheet()->mergeCells('A1:K1');
        $spreadsheet -> getActiveSheet() -> setCellValue('A1' , "Details of Votes cast during from ".date('dMy',strtotime($request->date_from))." to ".date('dMy',strtotime($request->date_to))." , of financial year ".$financial_year);
        $spreadsheet -> getActiveSheet() -> getStyle('A1')->applyFromArray($styleArray4);

        $seq = 2;
        foreach ($scheme->reports as $report) {
            if(sizeof($report->resolutions) > 0){

                $ar_fields = array("meeting_date", "com_full_name","meeting_type","man_share_reco","resolution_name","man_reco","vote_value","comment","result","resolution_number","isin");

                $ar_names = array("Meeting Date", "Company Name","Type of Meeting","Proposal by Management or Shareholder","Proposal","Investee company's Management Recommendation","Vote(For/Against/Abstrain)","Reason supporting the vote decision","Result of Meeting","Resolution No","ISIN");

                $ar_width = array("20","25","20","20","20","20","20","20","20","15","15","15","15","15","30","20","20","20","20","20","20");

                $spreadsheet -> getActiveSheet() -> getColumnDimension('A') -> setWidth(25);
                $spreadsheet -> getActiveSheet() -> getRowDimension('1') -> setRowHeight(25);

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
                $spreadsheet -> getActiveSheet() -> getStyle('A'.$seq.':K'.$seq)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ffd3d3d3');

                $spreadsheet -> getActiveSheet() -> getStyle('A' . $seq . ':' . $cell_val . $seq);

                $seq++;

                $count = 1;
                foreach ($report->resolutions as $row) {
                    
                    $i = 0;

                    foreach ($ar_fields as $ar) {
                        $var = '';
                        $cell = $i + $offset;
                        $cell_val = $this->getNameFromNumber($cell);

                        if($ar == 'sn'){
                            $var = $count++;
                        }else {
                            $var = (isset($row->$ar))?$row->$ar:'';
                        }
                        $i++;

                        $spreadsheet ->getActiveSheet()-> setCellValue($cell_val . $seq, $var);

                        $spreadsheet->getActiveSheet()->getStyle($cell_val . $seq)->getAlignment()->setWrapText(true);
                    }
                    
                    $seq++;

                }
                $seq++;

            }
        }
        
        $spreadsheet->createSheet();
        $sheet_count++;
        $spreadsheet->setActiveSheetIndex($sheet_count);
    }  
}

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