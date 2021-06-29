<?php
namespace App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// error_reporting(0);

$spreadsheet = new Spreadsheet();
$Excel_writer = new Xlsx($spreadsheet);
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();

$sheet_name = 'LINKINTIME_VOTE_'.$report->even;

$activeSheet->setTitle($sheet_name);

$seq = 1;
$spreadsheet->getActiveSheet()->setCellValue('A'.$seq, 'SR_NO');
$spreadsheet->getActiveSheet()->setCellValue('B'.$seq, 'EVENT_NO');
$spreadsheet->getActiveSheet()->setCellValue('C'.$seq, 'USER_ID');
$spreadsheet->getActiveSheet()->setCellValue('D'.$seq, 'USER_F_NAME');
$spreadsheet->getActiveSheet()->setCellValue('E'.$seq, 'PAN');
$spreadsheet->getActiveSheet()->setCellValue('F'.$seq, 'CATEGORY');
$spreadsheet->getActiveSheet()->setCellValue('G'.$seq, 'UP_CD');
$spreadsheet->getActiveSheet()->setCellValue('H'.$seq, 'NO_OF_SHARES');
$spreadsheet->getActiveSheet()->setCellValue('I'.$seq, 'VOTING_RIGHTS');

$col = 9;
    
$col_name = $this->getNameFromNumber($col);
$spreadsheet->getActiveSheet()->setCellValue($col_name.$seq, "SUB_NO");
$col++;

$col_name = $this->getNameFromNumber($col);
$spreadsheet->getActiveSheet()->setCellValue($col_name.$seq, "RES_NO");
$col++;

$col_name = $this->getNameFromNumber($col);
$spreadsheet->getActiveSheet()->setCellValue($col_name.$seq, "VOTES_FAVOR");
$col++;

$col_name = $this->getNameFromNumber($col);
$spreadsheet->getActiveSheet()->setCellValue($col_name.$seq, "VOTES_AGAINST");
$col++;

$col_name = $this->getNameFromNumber($col);
$spreadsheet->getActiveSheet()->setCellValue($col_name.$seq, "VOTES_ABSTAIN");
$col++;

$col_name = $this->getNameFromNumber($col);
$spreadsheet->getActiveSheet()->setCellValue($col_name.$seq, "EOL_COL");

$seq = 2;

$scheme_count = 0;
$sn = 0;
foreach ($schemes as $scheme) {

    if(in_array($scheme->scheme_id, $allowed_sheme_ids)){

        $scheme_count++;

        $shares_value = $shares_held[$scheme->scheme_id]['shares_held'];

        foreach ($votings_all as $voting) {
            
            if($voting->scheme_id != $scheme->scheme_id) continue;

            $sn++;

            $spreadsheet->getActiveSheet()->setCellValue('A'.$seq, $sn);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$seq, $report->even);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$seq, $scheme->dp_id.$scheme->client_id);
            $spreadsheet->getActiveSheet()->setCellValue('D'.$seq, $scheme->scheme_name);
            $spreadsheet->getActiveSheet()->setCellValue('E'.$seq, "");
            $spreadsheet->getActiveSheet()->setCellValue('F'.$seq, "");
            $spreadsheet->getActiveSheet()->setCellValue('G'.$seq, 'M');
            $spreadsheet->getActiveSheet()->setCellValue('H'.$seq, $shares_value);
            $spreadsheet->getActiveSheet()->setCellValue('I'.$seq, $shares_value);

            $votes_array = ["","","",""];

            $votes_array[$voting->vote] = $shares_value;

            $spreadsheet->getActiveSheet()->setCellValue('J'.$seq, $scheme_count);
            $spreadsheet->getActiveSheet()->setCellValue('K'.$seq, $resolutions[$voting->voting_id]);

            $spreadsheet->getActiveSheet()->setCellValue("L".$seq, $votes_array[1]);
            $spreadsheet->getActiveSheet()->setCellValue("M".$seq, $votes_array[2]);
            $spreadsheet->getActiveSheet()->setCellValue("N".$seq, $votes_array[3]);
            $spreadsheet->getActiveSheet()->setCellValue("O".$seq, "#");
            
            $seq++;
        }
    }
}

$filename = $name.'.xlsx';

$writer = new Xlsx($spreadsheet);

$path = app_path()."/../temp/";
$writer->save($path.$filename);
