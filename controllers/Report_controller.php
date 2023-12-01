<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_controller extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Export_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer'); 
    }

    public function main()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array(
                'report' => $this->db->query("SELECT * from set_report_query where report_type <> 'each_trans' and active = '1'"),
                'form_submit' => site_url('Report_controller/main'),
            );
            $this->load->view('header');
            $this->load->view('report_setup', $data);    
            $this->load->view('report_setup_modal', $data);   
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function creat_new()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array(
                'report_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
                'report_id' => $this->db->query("SELECT REPLACE(TO_BASE64(LEFT(UUID(),'10')),'=','') as report_id")->row('report_id'),
                'seq' => $this->input->post('seq'),
                'report_name' => $this->input->post('report_name'),
                'report_type' => $this->input->post('report_type'),
                'query' => htmlentities($this->input->post('query')),
            );
            $this->db->insert('set_report_query', $data);
            $this->session->set_flashdata('message', 'Successful');
            redirect('Report_controller/main');
        }
        else
        {
            redirect('#');
        }
    }

    public function update()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            if($_REQUEST['mode']=='detail')
            {
                $data = array(
                'seq' => $this->input->post('seq'),
                'report_name' => $this->input->post('report_name'),
                'report_type' => $this->input->post('report_type'),
                //'query' => htmlentities($this->input->post('query')),
                );   
            };

            if($_REQUEST['mode']=='query')
            {
                $data = array(
                'query' => htmlentities($this->input->post('query')),
                );   
            };
            
            $this->db->where('report_guid', $this->input->post('report_guid'));
            $this->db->update('set_report_query', $data);
            $this->session->set_flashdata('message', 'Successful');
            redirect('Report_controller/main');
        }
        else
        {
            redirect('#');
        }
    }

    public function delete_report_guid()
    {
        $report_guid = $_REQUEST['report_guid'];
        $this->db->query("UPDATE  set_report_query set active = '0' where report_guid =  '$report_guid'");
        $this->session->set_flashdata('message', 'Successful');
        redirect('Report_controller/main');

    }

    public function gen_rep()
    {
        ini_set('max_execution_time', 0); 
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
           $datefrom = $this->db->query("SELECT DATE_FORMAT('".date('Y-m-d')."','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
            $dateto = date('Y-m-d');
            $report_id = $_REQUEST['report_id'];
            $customer_guid = $_SESSION['customer_guid'];

            $check_excel_query = $this->db->query("SELECT * from set_report_query where report_id = '$report_id'");    
            foreach ($check_excel_query->result() as $row)
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@EDateFrom', '$datefrom'), '@EDateTo', '$dateto'),'@customer_guid','$customer_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                $q_result = $this->db->query($replace_var);
              // echo $this->db->last_query();die;
            }
            // print_r($q_result->list_fields());die;
            // // echo count($q_result->result());die;
            // if(count($q_result->result()) == 0)
            // {
            //     // echo 1;die;
            //     $q_result = array("No data" => "No data");
            // }

                    $this->load->library('excel');

                    ob_start();
                    $this->excel = new PHPExcel();

                    $data = $q_result->result_array();
                        //load our new PHPExcel library;
                    //activate worksheet number 1

                    $this->excel->setActiveSheetIndex(0);
                    
                    //name the worksheet
                    $this->excel->getActiveSheet()->setTitle($check_excel_query->row('report_name'));

                    $fields = $q_result->list_fields();
                    $col = 0;
                    foreach ($fields as $field)
                    {
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                        $col++;
                    }

                    if(count($q_result->result()) == 0)
                    {
                        // echo 1;die;
                        $row = 2;
                            $col = 0;
                            $a='A';
                            foreach ($fields as $field)
                            {
                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col , $row , 'No data');
                            $this->excel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
                            $this->excel->getActiveSheet()->setCellValueExplicit($a.$row, 'No data', PHPExcel_Cell_DataType::TYPE_STRING);
                            // echo $a.$row.'<br>';
                            $a++;
                            $col++;
                            }
                    }
                    else
                    {
                        $row = 2;
                        foreach($q_result->result() as $data)
                        {
                            $col = 0;
                            $a='A';
                            foreach ($fields as $field)
                            {
                            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col , $row , $data->$field);
                            $this->excel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
                            $this->excel->getActiveSheet()->setCellValueExplicit($a.$row, $data->$field, PHPExcel_Cell_DataType::TYPE_STRING);
                            // echo $a.$row.'<br>';
                            $a++;
                            $col++;
                            }
                            $row++;
                        }
                    }



                        date_default_timezone_set("Asia/Kuala_Lumpur");
                        // $filename = 'trydaniel.xlsx'; //save our workbook as 
                        // $objPHPExcel = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                        // if(!file_exists('./excel_by_report_troubleshoot'))
                        // {
                        //     mkdir('./excel_by_report_troubleshoot');
                        // }
                        // $name = './excel_by_report_troubleshoot/'.$filename;
                        // $objPHPExcel->save($name);
                        ob_end_clean();



                        // header('Content-Type: application/vnd.ms-excel'); //mime type
                        // header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                        // header('Cache-Control: max-age=0'); //no cache
                        // header("Pragma: no-cache");
                        // header("Expires: 0");

                        // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                        // ob_end_clean();
                        // //force user to download the Excel file without writing it to server's HD
                        // $objWriter->save('php://output');

                        $today = $this->db->query("SELECT date(now()) as today")->row('today');

                        // $filename = $today.'.XLSX'; //save our workbook as this file name
                        $filename = $check_excel_query->row('report_name').$today.'.xlsx'; //save our workbook as 
                        header('Content-Type: application/vnd.ms-excel'); //mime type
                        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                        header('Cache-Control: max-age=0'); //no cache
                        header("Pragma: no-cache");
                        header("Expires: 0");

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                        ob_end_clean();
                        //force user to download the Excel file without writing it to server's HD
                        $objWriter->save('php://output');

                        
                 


        }
        else
        {
            redirect('#');

        }
    }     
 

    public function Document_get()
    {
        // echo 1;die;
        // print_r(scandir("/media/backup/b2b_archive/"));die;
        $filename ='/media/backup/b2b_archive/tfvaluemart/PROMO_C283_GRKPM19120143.pdf'; 
        // if(file_exists($filename))
        // {
        //     echo 1;die;
        //   // header('Location: http://www.example.com/');
        // }
        // else
        // {
        //     echo 2;die;
        // }
        // Header content type 
        header('Content-type: application/pdf'); 
          
        header('Content-Disposition: inline; filename="' . $filename . '"'); 
          
        header('Content-Transfer-Encoding: binary'); 
          
        header('Accept-Ranges: bytes'); 
          
        // Read the file 
        readfile($file);
        die;
     } 
}
