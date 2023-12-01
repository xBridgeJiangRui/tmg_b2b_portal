<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_controller extends CI_Controller {
    
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
                'datefrom' => $this->db->query("SELECT DATE_FORMAT(curdate(),'%Y-%m-%d') - INTERVAL 7 DAY as weekago")->row('weekago'),
                'dateto' => $this->db->query("SELECT curdate() as today")->row('today'),
                'report_type' => 'testing',
                'form_submit' => site_url('Export_controller/generate_excel')
            );
            $this->load->view('header');
            $this->load->view('troubleshoot_excel', $data);    
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function generate_excel()
    {
       // $raw_date_start = $_REQUEST['date_start'];
        $datefrom = $this->db->query("SELECT DATE_FORMAT('".$_REQUEST['date_start']."','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
        $dateto = $_REQUEST['date_start'];
        $report_id = $_REQUEST['report_id'];
        $customer_guid = $_REQUEST['customer_guid'];

        //$datefrom = $this->db->query("SELECT LEFT(FROM_UNIXTIME($raw_datefrom - to_seconds('1970-01-01')),10) AS second_to_date ")->row('second_to_date');
        // $dateto = $this->db->query("SELECT LEFT(FROM_UNIXTIME($raw_dateto - to_seconds('1970-01-01')),10) AS second_to_date ")->row('second_to_date');
        $check_excel_query = $this->db->query("SELECT * from set_report_query where report_id = '$report_id'");    
        $check_schedule_type = $this->db->query("SELECT * from set_report_schedule where report_guid = '".$check_excel_query->row('report_guid')."'");

        if($check_schedule_type->row('schedule_type') == 'weekly')
        {
            if($check_excel_query->num_rows() > 0)
            {
                foreach ($check_excel_query->result() as $row)
                {
                    $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@EDateFrom', '$datefrom'), '@EDateTo', '$dateto'),'@customer_guid','$customer_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);
                    //   echo $this->db->last_query();die;
                }
            }
            else
            {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");
            };
        };

        if($check_schedule_type->row('schedule_type') == 'daily')
        {
            if($check_excel_query->num_rows() > 0)
            {
                $datefrom = $this->db->query("SELECT DATE_FORMAT('".$_REQUEST['date_start']."','%Y-%m-%d') - INTERVAL 30 DAY as datefrom")->row('datefrom');
                $user_guid =  $_REQUEST['user_guid'];
                $customer_guid = $check_schedule_type->row('customer_guid');

                foreach ($check_excel_query->result() as $row)
                {
                    $replace_var = $this->db->query("SELECT REPLACE(query, '@user_guid', '$user_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);
                    if($q_result->num_rows() == 0)
                    {
                        redirect('Export_controller/no_result');
                    }

                    //   echo $this->db->last_query();die;
                }
            }
            else
            {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");
            
            //$data = $q_result->result_array();
            };
        };
        
       
            //echo $this->db->last_query();die;
            //$q_result = $this->db->query($replace_var);
                $data = $q_result->result_array();
                 //load our new PHPExcel library
                $this->load->library('excel');

                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
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

                $row = 2;
                foreach($q_result->result() as $data)
                {
                    $col = 0;
                    foreach ($fields as $field)
                    {
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col , $row , $data->$field);
                    $col++;
                    }
                 $row++;
                }
                $name ="".$check_excel_query->row('report_name')." between $datefrom to $dateto.xls"; //save our workbook as this file name
                //$name ="".$check_excel_query->row('report_name').".xls"; 
                $filename = (string)$name;

                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

                header('Cache-Control: max-age=0'); //no cache

                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                ob_end_clean();
                 
                //force user to download the Excel file without writing it to server's HD
                /*close this if u wanna run on PC*/
                //$objWriter->save('php://output');
                
                //for server enable this
                 //$path = "/media/data/hughlim.com/downloads/".$customer_guid;
                $db_path = $this->db->query("SELECT export_path from general_setting limit 1")->row('export_path');
                $path = $db_path.$customer_guid;
                if (!file_exists($path)) 
                {
                   $oldmask = umask(0);
                   mkdir($path, 0777, true); 
                   umask($oldmask);
                };
                $objWriter->save($path."/".$filename);
                

                redirect("Export_controller/email_excel?datefrom='".$datefrom."'&dateto='".$dateto."'&report_id=".$report_id."&customer_guid=".$customer_guid);
                //auto save to a page
                    //$path =  base_url('downloads/'.$filename);
                // $path = "/media/data/hughlim.com/downloads/".$filename;
    }

    public function direct_content()
    {
        $datefrom = $this->db->query("SELECT DATE_FORMAT('".$_REQUEST['date_start']."','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
        $dateto = $_REQUEST['date_start'];
        $report_id = $_REQUEST['report_id'];
        $customer_guid = $this->db->query("SELECT customer_guid from set_report_schedule where schedule_guid = '".$_REQUEST['schedule_guid']."'")->row('customer_guid');

        $check_excel_query = $this->db->query("SELECT * from set_report_query where report_id = '$report_id'");    
        $check_schedule_type = $this->db->query("SELECT * from set_report_schedule where report_guid = '".$check_excel_query->row('report_guid')."'");

        if($check_schedule_type->row('schedule_type') == 'weekly')
        {
            if($check_excel_query->num_rows() > 0)
            {
                foreach ($check_excel_query->result() as $row)
                {
                    $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@EDateFrom', '$datefrom'), '@EDateTo', '$dateto'),'@customer_guid','$customer_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);
                    //   echo $this->db->last_query();die;
                }
            }
            else
            {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");
            };
        };

        if($check_schedule_type->row('schedule_type') == 'daily')
        {
            if($check_excel_query->num_rows() > 0)
            {
                $datefrom = $this->db->query("SELECT DATE_FORMAT('".$_REQUEST['date_start']."','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
                $user_guid =  $_REQUEST['user_guid'];
                $customer_guid = $check_schedule_type->row('customer_guid');

                foreach ($check_excel_query->result() as $row)
                {
                    $replace_var = $this->db->query("SELECT REPLACE(query, '@user_guid', '$user_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);
                    //echo $this->db->last_query();die;
                    if($q_result->num_rows() == 0)
                    {
                        redirect('Export_controller/no_result');
                    }
                }
            }
            else
            {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");
            
            //$data = $q_result->result_array();
            };
        };

        $email_add = $this->db->query("SELECT email from check_email_schedule where schedule_guid = '".$_REQUEST['schedule_guid']."' and report_id = '".$_REQUEST['report_id']."' and user_guid = '".$_REQUEST['user_guid']."'")->row('email');

        $email_subject = $this->db->query("SELECT concat(report_name, ' between ', '$datefrom' , ' to ', '$dateto') as email_subject from set_report_query where report_id = '$report_id'")->row('email_subject');
        //echo $this->db->last_query();die;
        $date = $this->db->query("SELECT now() as now")->row('now'); 

        $data = array(
            'q_result' => $q_result,            
        );

        $bodyContent = $this->load->view('email_content', $data, TRUE);

        $this->send_direct_content($email_add, $date, $bodyContent, $email_subject);
        
    }

    public function send_direct_content($email_add, $date, $bodyContent, $email_subject)
    {   
        $email_the_data = $this->db->query("SELECT * from email_setup limit 1");

        $mail = new PHPMailer;

        $mail->isSMTP(); // Set mailer to use SMTP
        //$mail->Host =  $email_the_data->row('smtp_server'); 
        $mail->Host = 'smtp.gmail.com'; //$email_the_data->row('smtp_server'); // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        //$mail->Username =   $email_the_data->row('email_username'); // SMTP username
        $mail->Username = 'xbridge.b2b@gmail.com'; // $email_the_data->row('email_username'); // SMTP username
        //$mail->Password =   $email_the_data->row('email_password'); // SMTP password
        $mail->Password = '80998211';// $email_the_data->row('email_password'); // SMTP password
        //$mail->SMTPSecure =   $email_the_data->row('smtp_security');// Enable TLS encryption, `ssl` also accepted
        $mail->SMTPSecure = 'TLS'; // $email_the_data->row('smtp_security');// Enable TLS encryption, `ssl` also accepted
        //$mail->Port =  $email_the_data->row('smtp_port');// TCP port to connect to
        $mail->Port = '587';// $email_the_data->row('smtp_port');// TCP port to connect to

        //$mail->setFrom($email_the_data->row('sender_email'), $email_the_data->row('sender_name'));
        $mail->setFrom('xbridge.b2b@gmail.com', 'B2B');
        //$mail->addReplyTo($email_the_data->row('sender_email'), $email_the_data->row('sender_name'));
        $mail->addReplyTo('xbridge.b2b@gmail.com', 'B2B');
        $mail->addAddress($email_add,'Admin'); // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML
        $path= base_url('assets/img/new.png');
        //$mail->SMTPDebug = 2;
         
        $mail->Subject = $email_subject;
        $mail->Body    = $bodyContent;
        //var_dump($mail->send());die;
        if(!$mail->send()) 
        {
            // echo 'Message could not be sent.';
             echo 'Mailer Error: ' . $mail->ErrorInfo;
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => 'URL_TASK',
                'recipient' => $email_add,
                'sender' => $email_the_data->row('sender_email'),
                'subject' => $email_subject,
                'status' => 'FAIL',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $email_the_data->row('smtp_server'),
                'smtp_port' => $email_the_data->row('smtp_port'),
                'smtp_security' => $email_the_data->row('smtp_security'),
                );
            $this->db->insert('email_transaction', $data);
           // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            //redirect('Email_controller/setup');
             echo json_encode(array(
                'status' => false,
                'message' => 'failshit',
                ));
        } 
        else 
        {
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' =>'URL_TASK',
                'recipient' => $email_add,
                'sender' => $email_the_data->row('sender_email'),
                'subject' => $email_subject,
                'status' => 'SUCCESS',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $email_the_data->row('smtp_server'),
                'smtp_port' => $email_the_data->row('smtp_port'),
                'smtp_security' => $email_the_data->row('smtp_security'),
                );
            $this->db->insert('email_transaction', $data);
            // $this->session->set_flashdata('message', 'Message has been sent');
            // redirect('Email_controller/setup');
            // echo 'Message has been sent';
             echo json_encode(array(
                'status' => true,
                'message' => 'successful sent',
                ));
        }
    }

    public function no_result()
    {
        echo json_encode(array(
                        'status' => true,
                        'message' => 'nodata',
                        ));
    }

    public function email_excel()
    {
        
        $datefrom = $_REQUEST['datefrom'];
        $dateto = $_REQUEST['dateto'];
        $report_id = $_REQUEST['report_id'];
        $customer_guid = $_REQUEST['customer_guid'];

        //$datefrom = $this->db->query("SELECT LEFT(FROM_UNIXTIME($raw_datefrom - to_seconds('1970-01-01')),10) AS second_to_date ")->row('second_to_date');
        //$dateto = $this->db->query("SELECT LEFT(FROM_UNIXTIME($raw_dateto - to_seconds('1970-01-01')),10) AS second_to_date ")->row('second_to_date');

        $email_subject = $this->db->query("SELECT concat(report_name, ' between ', $datefrom , ' to ', $dateto) as email_subject from set_report_query where report_id = '$report_id'")->row('email_subject');
        //echo $email_subject;die;
        $email = $this->db->query("SELECT * from email_setup");
        $setsession = array(
                'smtp_server' =>$email->row('smtp_server'),
                'en' =>$email->row('username'),
                'ep' =>$email->row('password'),
                'smtp_security' =>$email->row('smtp_security'),
                'smtp_port' =>$email->row('smtp_port'),
                'sender_email' =>$email->row('sender_email'),
                'sender_name' =>$email->row('sender_name'),
                'subject' =>  $email_subject,
                'url' => $email->row('url'),
                );
        $this->session->set_userdata($setsession);

        // loop all admin group
        //$email_data = $this->db->query("SELECT * from batch_disb where trans_guid = '$guid'");

        //$email_group = $this->db->query("SELECT first_name, email from email_list where email_group = 'HQM' and isactive = '1'");

        $email_group = $this->db->query("SELECT first_name,email from check_email_schedule where customer_guid = '$customer_guid' and report_id = '$report_id'");

        $email_name = $email_group->row('first_name');
        $email_add = $email_group->row('email');
        $date = $this->db->query("SELECT now() as now")->row('now');
       // $location = $_SESSION['location'];
       // $amount =  number_format($email_data->row('amount'),2);
        //$msgtype = 'Batch Request';
        $bodyContent = '<div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                        B2B Notification
                                    </h3>
                                    <p class="lead">
                                           '.$email_subject.'
                                        <br>
                                        Regards,<br>
                                        <a href="'.$_SESSION['url'].'"> B2B Mail</a>
                                    </p>
                                </div>
                            </div>
                        </div>';

        
        foreach($email_group->result() as $row)
        {
            $email_name = $row->first_name;
            $email_add = $row->email;
            $this->send_to_manager($email_add, $email_name,  $bodyContent, $datefrom, $dateto, $customer_guid,  $email_subject);
        }
         $this->session->set_flashdata('message', 'Message has been sent');
            
            
                echo json_encode(array(
                'status' => true,
                'message' => 'successfulx',
                ));
            
           
           // redirect('Export_controller/main');
    }

    public function send_to_manager($email_add, $email_name, $bodyContent, $datefrom, $dateto, $customer_guid,  $email_subject)
    {
        $mail = new PHPMailer;

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $_SESSION['smtp_server']; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_SESSION['en']; // SMTP username
        $mail->Password = $_SESSION['ep']; // SMTP password
        $mail->SMTPSecure = $_SESSION['smtp_security'];// Enable TLS encryption, `ssl` also accepted
        $mail->Port = $_SESSION['smtp_port']; // TCP port to connect to

        $mail->setFrom($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addReplyTo($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addAddress($email_add, $email_name); // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML
        //$path= base_url('assets/img/new.png');
        
        
        $mail->Subject = $_SESSION['subject'];
        $mail->Body    = $bodyContent;
        $db_path = $this->db->query("SELECT export_path from general_setting limit 1")->row('export_path');
        $filepath = $db_path.$customer_guid;
        $file = $filepath."/".$email_subject.".xls";
        $mail->addAttachment( $file, $email_subject.".xls" );
        $_SESSION['userid'] = 'URLTaskAgent';
        if(!$mail->send()) 
        {
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => $_SESSION["userid"],
                'recipient' => $email_add,
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'FAIL',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
           // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            //redirect('Email_controller/setup');
        } 
        else 
        {
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => $_SESSION["userid"],
                'recipient' => $email_add,
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'SUCCESS',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
           
            // echo 'Message has been sent';
        }
    }

    public function get_data()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array (
                'location' => $this->db->query("SELECT * from config order by location asc"),
                'menutype' => $this->db->query("SELECT * from set_menu where hide_menu <> '1' and rep_code is not null order by parent_sequence asc, sequence asc"),
                'status_recon' => $this->db->query("SELECT * from set_option where active = '1' and type = 'REPORT_STATUS' and code <> 'POST' order by trans_guid"),
                'status_other' => $this->db->query("SELECT * from set_option where active = '1' and type = 'REPORT_STATUS' and code = 'POST'  order by trans_guid"),
            );

            $this->load->view('header');
            $this->load->view('report/report1', $data);    
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function preview_data()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {      
            //$menutitle = $this->input->query('menutitle');
            $title = explode('->', $this->input->post('menutitle') );

            $check_menu = $title[0];
            $location = $this->input->post('location');

            if($check_menu == 'salesrecon' || $check_menu == 'card_trans')
            {
                $status = $this->input->post('status');
            }
            else
            {
                $status = $this->input->post('status_other');
            }

            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            $db_setting = $title[1];

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(query1, '@location', '$location'), '@datefrom', '$datefrom'), '@dateto' , '$dateto'), '@status', '$status'), '@db_setting', '$db_setting') AS query FROM set_query WHERE CODE = '$check_menu' AND TYPE = 'advance_report' AND `condition` = '$status'")->row('query');

            $q_result = $this->db->query($replace_var);
 
            if($q_result->num_rows() > 0 )
            {
                $data = $q_result->result_array();
                    //load our new PHPExcel library
                $this->load->library('excel');
                //activate worksheet number 1
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle($db_setting.''.$status);

                $check_export = $this->db->query("SELECT * FROM set_export AS a INNER JOIN set_export_c AS b ON a.`trans_guid`  = b.trans_guid WHERE `key` = '$db_setting'");

                    foreach($check_export->result() as $row)
                    {
                         $this->excel->getActiveSheet()->setCellValue($row->column, $row->title);
                    }
                //set cell A1 content with some text
               /* $this->excel->getActiveSheet()->setCellValue('A1', 'Location');
                $this->excel->getActiveSheet()->setCellValue('B1', 'Bizdate');
                $this->excel->getActiveSheet()->setCellValue('C1', 'Amount');
                $this->excel->getActiveSheet()->setCellValue('D1', 'Short / Excess');
                $this->excel->getActiveSheet()->setCellValue('E1', 'Actual Cash ');
                $this->excel->getActiveSheet()->setCellValue('F1', 'Should Bank In');
                $this->excel->getActiveSheet()->setCellValue('G1', 'Bank In Amount');
                $this->excel->getActiveSheet()->setCellValue('H1', 'Petty Cash');
                $this->excel->getActiveSheet()->setCellValue('I1', 'Disbursement');
                $this->excel->getActiveSheet()->setCellValue('J1', 'Actual Bank In');
                $this->excel->getActiveSheet()->setCellValue('K1', 'Actual Short Bank In');
                $this->excel->getActiveSheet()->setCellValue('L1', 'Actual Difference');
                }*/
                
                $this->excel->getActiveSheet()->fromArray($data, ' ', 'A2');
                
                $today = $this->db->query("SELECT date(now()) as today")->row('today');

                $filename = $db_setting.'-'.$today.'.XLSX'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache

                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                ob_end_clean();
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');

            }
            else
            {
                $this->session->set_flashdata('warning', 'No Data on selected details');
                redirect('export_controller/get_data');
                //echo $replace_var;
            }

        }
        else
        {
            redirect('#');
        }
    }


    public function export_details()
    {

        $merchant_guid = $_REQUEST['merchant_guid'];
        $query = $this->Export_model->report_data_export($merchant_guid);

        //$query = $this->db->query('select trans_guid,merchant_guid,ref_no from slp_midas.transaction  ');
        
        $data = $query->result_array();

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('merchant report');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Ref No');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Account No');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Account Name');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Amount');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Point Earn');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Remark');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Created at');
        $this->excel->getActiveSheet()->setCellValue('H1', 'Created by');
        $this->excel->getActiveSheet()->setCellValue('I1', 'Void');
        $this->excel->getActiveSheet()->setCellValue('J1', 'Void at');
        $this->excel->getActiveSheet()->setCellValue('K1', 'Void by');
        $this->excel->getActiveSheet()->setCellValue('L1', 'Void Cross Ref No');
        $this->excel->getActiveSheet()->setCellValue('M1', 'Void Reason');
        $this->excel->getActiveSheet()->setCellValue('N1', 'Browser');
        $this->excel->getActiveSheet()->setCellValue('O1', 'IP Address');
        
        $this->excel->getActiveSheet()->fromArray($data, ' ', 'A2');

        $filename='merchant_report.XLSX'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        
    }


    public function export_result()
    {

        $merchant_guid = $_REQUEST['merchant_guid'];
        $datefrom = $_REQUEST['datefrom'];
        $dateto = $_REQUEST['dateto'];
        $query = $this->Export_model->report_result_export($datefrom, $dateto, $merchant_guid);

        //$query = $this->db->query('select trans_guid,merchant_guid,ref_no from slp_midas.transaction  ');
        
        $data = $query->result_array();

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('merchant report');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Ref No');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Account No');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Account Name');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Amount');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Point Earn');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Remark');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Created at');
        $this->excel->getActiveSheet()->setCellValue('H1', 'Created by');
        $this->excel->getActiveSheet()->setCellValue('I1', 'Void');
        $this->excel->getActiveSheet()->setCellValue('J1', 'Void at');
        $this->excel->getActiveSheet()->setCellValue('K1', 'Void by');
        $this->excel->getActiveSheet()->setCellValue('L1', 'Void Cross Ref No');
        $this->excel->getActiveSheet()->setCellValue('M1', 'Void Reason');
        $this->excel->getActiveSheet()->setCellValue('N1', 'Browser');
        $this->excel->getActiveSheet()->setCellValue('O1', 'IP Address');
        
        $this->excel->getActiveSheet()->fromArray($data, ' ', 'A2');

        $filename='merchant_report.XLSX'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
        
    }

    //hugh add in PRDN notification 2019-08-02
    public function email_notification()
    {
        $module = $_REQUEST['module'];
        // modules to consider
        // - new Return Batch
        // - unattended 1st warning
        // - unattended 2nd warning
        // - PRDN generated
        if($module == 'return_collection')
        {
            $check_unique_supplier = $this->db->query("SELECT * FROM ( SELECT '1' AS sort , 'NEW STOCK RETURN' AS title1 , COUNT(*) AS total , a.customer_guid , b.`supplier_group_guid` , b.`supplier_guid` , a.batch_no , a.sup_code , a.doc_date , a.expiry_date , a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` WHERE STATUS = '0' AND doc_date = DATE_ADD(CURDATE(), INTERVAL - 1 DAY) GROUP BY a.`customer_guid` UNION ALL SELECT '2' AS sort , 'Requires Attention #1' AS title1 , COUNT(*) AS total , a.customer_guid , b.`supplier_group_guid` , b.`supplier_guid` , a.batch_no , a.sup_code , a.doc_date , a.expiry_date , a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD(doc_date, INTERVAL +c.prdn_email_notification_1 DAY) = CURDATE() GROUP BY a.`customer_guid` UNION ALL SELECT '3' AS sort , 'Last Warning Alert #2' AS title1 , COUNT(*) AS total , a.customer_guid , b.`supplier_group_guid` , b.`supplier_guid` , a.batch_no , a.sup_code , a.doc_date , a.expiry_date , a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD(doc_date, INTERVAL +c.prdn_email_notification_2 DAY) = CURDATE() GROUP BY a.`customer_guid` ) a GROUP BY supplier_guid");

            if($check_unique_supplier->num_rows() > 0)
            { 
                foreach($check_unique_supplier->result() as $row)
                { 
                    $email_content = $this->db->query("SELECT sort,title1, doc_type, total, bb.acc_name FROM ( SELECT '1' AS sort , concat('New Stock Return @ ', doc_date) AS title1 , 'Return Collection' AS doc_type , COUNT(*) AS total , a.customer_guid , b.`supplier_group_guid` , b.`supplier_guid` , a.batch_no , a.sup_code , a.doc_date , a.expiry_date , a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` WHERE STATUS = '0' AND doc_date = DATE_ADD(CURDATE(), INTERVAL - 1 DAY) AND b.`supplier_guid` = '".$row->supplier_guid."' GROUP BY a.`customer_guid` UNION ALL SELECT '2' AS sort , concat('Existing Stock Return  since ', doc_date, ' email #1') AS title1 , 'Return Collection' AS doc_type , COUNT(*) AS total , a.customer_guid , b.`supplier_group_guid` , b.`supplier_guid` , a.batch_no , a.sup_code , a.doc_date , a.expiry_date , a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD(doc_date, INTERVAL +c.prdn_email_notification_1 DAY) = CURDATE() AND b.`supplier_guid` = '".$row->supplier_guid."' GROUP BY a.`customer_guid` UNION ALL SELECT '3' AS sort , concat('Existing Stock Return  since ', doc_date, ' email #2')  AS title1 , 'Return Collection' AS doc_type , COUNT(*) AS total , a.customer_guid , b.`supplier_group_guid` , b.`supplier_guid` , a.batch_no , a.sup_code , a.doc_date , a.expiry_date , a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD(doc_date, INTERVAL +c.prdn_email_notification_2 DAY) = CURDATE() AND b.`supplier_guid` = '".$row->supplier_guid."' GROUP BY a.`customer_guid` ) aa INNER JOIN lite_b2b.acc bb ON aa.customer_guid = bb.acc_guid ORDER BY acc_name , sort ");

                    $email_add = $this->db->query("SELECT user_id FROM check_user_supplier_customer_relationship WHERE supplier_guid = '".$row->supplier_guid."' GROUP BY supplier_guid")->row('user_id');

                    $date = $this->db->query("SELECT curdate() as now")->row('now'); 
                    $email_subject = 'B2B Email Notification @ '.$date;
                    $data = array(
                            'q_result' => $email_content,            
                        );
                
                        $bodyContent = $this->load->view('email_notification', $data, TRUE);
                
                        $this->send_direct_content($email_add, $date, $bodyContent, $email_subject);

                }
            }
            else
            {
                echo json_encode(array(
                'status' => true,
                'message' => 'no new prdn data',
                ));
            }
        }


    }

}
?>
