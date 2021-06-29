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
$activeSheet->setTitle("Current Synopsis");

$ar_names = array("ISIN","Company Name","Event Date","Meeting Type","Proposal" , "Type","Sr No","Summary Proposals","Investee company’s Management Recommendation","Passed By","Results");

$ar_fields = array("meeting_isin", "com_full_name","meeting_date","meeting_type","man_share_reco","type","resolution_number","resolution_name","man_reco","blank","result");

$ar_width = array("15","25","15","10","10","10","5","30","30","10","10","25","25","10");

$seq = 1;

foreach ($records as $record) {
    if(sizeof($record->resolutions) > 0){

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
        $sr_no = 1;
        foreach ($record->resolutions as $row) {
            
            $i = 0;

            foreach ($ar_fields as $ar) {
                $var = '';
                $cell = $i + $offset;
                $cell_val = $this->getNameFromNumber($cell);

                if($ar=='blank' ){
                    $var = '';
                }elseif($ar == 'meeting_isin'){
                    $var = (isset($record->$ar))?$record->$ar:'';
                }elseif($ar == 'com_full_name'){
                    $var = (isset($record->$ar))?$record->$ar:'';
                }elseif($ar == 'meeting_type'){
                    $var = (isset($record->$ar))?$record->$ar:'';
                }elseif($ar == 'meeting_date'){
                    $var = (isset($record->$ar))?date("d/M/Y",strtotime($record->$ar)):'';
                }elseif($ar == 'sr_no'){
                    $var = $sr_no;
                }else {
                    $var = (isset($row->$ar))?$row->$ar:'';
                }
                $i++;

                $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
            }

            $seq++;
            $sr_no++;
        }
        $seq++;
    }
}



$filename = 'Synopsis.xlsx';

$writer = new Xlsx($spreadsheet);

if(env("FTP_STATUS") == 1){
    $path = app_path()."/../uploads/";
    $writer->save($path.$filename);
    echo "Synopsis has been saved to SFTP";
} else {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename); 
    header('Cache-Control: max-age=0');
    $writer->save("php://output");

}

exit();