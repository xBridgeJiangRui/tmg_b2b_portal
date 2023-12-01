<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Excel_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('excel');
    }

    // insert data
    public function download_excel($sql)
    {
        ini_set('max_execution_time', 0); 

        $q_result = $this->db->query($sql)->result_array();

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        // set Header
        $set_paramater_header = array();

        foreach($q_result[0] as $xheader => $xrow)
        {
            $set_paramater_header[] = $xheader;
            continue;
        }

        $x = 'A';

        foreach($set_paramater_header AS $header_name)
        {
            $objPHPExcel->getActiveSheet()->SetCellValue($x.'1', $header_name);
            $objPHPExcel->getActiveSheet()->getStyle($x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);  
            $objPHPExcel->getActiveSheet()->getStyle($x.'1')->getFont()->setBold(true);
            // $objPHPExcel->getActiveSheet()->getStyle($x.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('808080');

            $x++;
             
        }
        
        $rowCount = '2';
        foreach($q_result AS $q_row)
        { 
            $c = 'A';
            foreach($q_row AS $row)
            {
                $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, $row);
                $objPHPExcel->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);          
                $c++;
            }

            $objPHPExcel->getActiveSheet()->getStyle($c)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $rowCount++;
                
        }

        return $objPHPExcel;

    }

}
