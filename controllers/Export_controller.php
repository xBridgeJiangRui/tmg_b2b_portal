<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Export_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Export_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer');
        $this->local_ip = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'general_doc', 'local_ip', 'LIP');
        $this->load->model('Send_email_model');
    }

    public function main()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
            $data = array(
                'datefrom' => $this->db->query("SELECT DATE_FORMAT(curdate(),'%Y-%m-%d') - INTERVAL 7 DAY as weekago")->row('weekago'),
                'dateto' => $this->db->query("SELECT curdate() as today")->row('today'),
                'report_type' => 'testing',
                'form_submit' => site_url('Export_controller/generate_excel')
            );
            $this->load->view('header');
            $this->load->view('troubleshoot_excel', $data);
            $this->load->view('footer');
        } else {
            redirect('#');
        }
    }

    public function generate_excel()
    {
        // $raw_date_start = $_REQUEST['date_start'];
        $datefrom = $this->db->query("SELECT DATE_FORMAT('" . $_REQUEST['date_start'] . "','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
        $dateto = $_REQUEST['date_start'];
        $report_id = $_REQUEST['report_id'];
        $customer_guid = $_REQUEST['customer_guid'];

        //$datefrom = $this->db->query("SELECT LEFT(FROM_UNIXTIME($raw_datefrom - to_seconds('1970-01-01')),10) AS second_to_date ")->row('second_to_date');
        // $dateto = $this->db->query("SELECT LEFT(FROM_UNIXTIME($raw_dateto - to_seconds('1970-01-01')),10) AS second_to_date ")->row('second_to_date');
        $check_excel_query = $this->db->query("SELECT * from set_report_query where report_id = '$report_id'");
        $check_schedule_type = $this->db->query("SELECT * from set_report_schedule where report_guid = '" . $check_excel_query->row('report_guid') . "'");

        if ($check_schedule_type->row('schedule_type') == 'weekly') {
            if ($check_excel_query->num_rows() > 0) {
                foreach ($check_excel_query->result() as $row) {
                    $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@EDateFrom', '$datefrom'), '@EDateTo', '$dateto'),'@customer_guid','$customer_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);
                    //   echo $this->db->last_query();die;
                }
            } else {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");
            };
        };

        if ($check_schedule_type->row('schedule_type') == 'daily') {
            if ($check_excel_query->num_rows() > 0) {
                $datefrom = $this->db->query("SELECT DATE_FORMAT('" . $_REQUEST['date_start'] . "','%Y-%m-%d') - INTERVAL 30 DAY as datefrom")->row('datefrom');
                $user_guid =  $_REQUEST['user_guid'];
                $customer_guid = $check_schedule_type->row('customer_guid');

                foreach ($check_excel_query->result() as $row) {
                    $replace_var = $this->db->query("SELECT REPLACE(query, '@user_guid', '$user_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);
                    if ($q_result->num_rows() == 0) {
                        redirect('Export_controller/no_result');
                    }

                    //   echo $this->db->last_query();die;
                }
            } else {
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
        foreach ($fields as $field) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }

        $row = 2;
        foreach ($q_result->result() as $data) {
            $col = 0;
            foreach ($fields as $field) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                $col++;
            }
            $row++;
        }
        $name = "" . $check_excel_query->row('report_name') . " between $datefrom to $dateto.xls"; //save our workbook as this file name
        //$name ="".$check_excel_query->row('report_name').".xls"; 
        $filename = (string)$name;

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();

        //force user to download the Excel file without writing it to server's HD
        /*close this if u wanna run on PC*/
        //$objWriter->save('php://output');

        //for server enable this
        //$path = "/media/data/hughlim.com/downloads/".$customer_guid;
        $db_path = $this->db->query("SELECT export_path from general_setting limit 1")->row('export_path');
        $path = $db_path . $customer_guid;
        if (!file_exists($path)) {
            $oldmask = umask(0);
            mkdir($path, 0777, true);
            umask($oldmask);
        };
        $objWriter->save($path . "/" . $filename);


        redirect("Export_controller/email_excel?datefrom='" . $datefrom . "'&dateto='" . $dateto . "'&report_id=" . $report_id . "&customer_guid=" . $customer_guid);
        //auto save to a page
        //$path =  base_url('downloads/'.$filename);
        // $path = "/media/data/hughlim.com/downloads/".$filename;
    }

    public function direct_content_old()
    {
        // echo 1;die;
        $datefrom = $this->db->query("SELECT DATE_FORMAT('" . $_REQUEST['date_start'] . "','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
        $dateto = $_REQUEST['date_start'];
        $report_id = $_REQUEST['report_id']; //YWFmNzEyMGYtOQ
        $customer_guid = $this->db->query("SELECT customer_guid from set_report_schedule where schedule_guid = '" . $_REQUEST['schedule_guid'] . "'")->row('customer_guid');

        $check_excel_query = $this->db->query("SELECT * from set_report_query where report_id = '$report_id'");
        $check_schedule_type = $this->db->query("SELECT * from set_report_schedule where report_guid = '" . $check_excel_query->row('report_guid') . "'");
        $q_result2_show = 0; // strb
        $q_result3_show = 0; // e-inv

        if ($check_schedule_type->row('schedule_type') == 'weekly') {
            if ($check_excel_query->num_rows() > 0) {
                foreach ($check_excel_query->result() as $row) {
                    $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@EDateFrom', '$datefrom'), '@EDateTo', '$dateto'),'@customer_guid','$customer_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);
                    //   echo $this->db->last_query();die;
                }
            } else {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");
            };
        };

        if ($check_schedule_type->row('schedule_type') == 'daily') {
            if ($check_excel_query->num_rows() > 0) {
                $datefrom = $this->db->query("SELECT DATE_FORMAT('" . $_REQUEST['date_start'] . "','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
                $user_guid =  $_REQUEST['user_guid'];
                $customer_guid = $check_schedule_type->row('customer_guid');

                foreach ($check_excel_query->result() as $row) {
                    $replace_var = $this->db->query("SELECT REPLACE(query, '@user_guid', '$user_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    //pomain reminder here... 
                    $q_result = $this->db->query($replace_var);
                    // $q_result = $this->db->query("SELECT acc_name, pm.refno, pm.podate, scode, sname, IF( cc.branch_desc IS NULL || cc.`branch_desc` = '', loc_group, CONCAT(loc_group, ' - ', cc.branch_desc) ) AS branch FROM b2b_summary.pomain AS pm INNER JOIN lite_b2b.acc AS acc ON pm.customer_guid = acc.acc_guid INNER JOIN (SELECT supplier_group_name, acc_guid, a.user_guid FROM lite_b2b.check_user_supplier_customer_relationship AS a INNER JOIN lite_b2b.check_email_schedule AS b ON a.user_guid = b.user_guid WHERE report_id = 'YWFmNzEyMGYtOQ' AND LEFT(date_start, 10) >= CURDATE() AND a.user_guid = '$user_guid' AND suspended != 1) cus ON cus.supplier_group_name = pm.scode AND cus.acc_guid = pm.`customer_guid` INNER JOIN b2b_summary.cp_set_branch cc ON pm.loc_group = cc.branch_code AND pm.customer_guid = cc.customer_guid INNER JOIN (SELECT a.acc_guid, c.branch_code FROM lite_b2b.acc_concept a INNER JOIN lite_b2b.acc_branch_group b ON a.concept_guid = b.concept_guid INNER JOIN lite_b2b.acc_branch c ON b.branch_group_guid = c.branch_group_guid INNER JOIN lite_b2b.set_user_branch d ON c.branch_guid = d.branch_guid WHERE d.user_guid = '$user_guid') dd ON cc.branch_code = dd.branch_code AND cc.customer_guid = dd.acc_guid WHERE `status` IN ('') AND podate BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND user_guid = '$user_guid' AND in_kind = 0 GROUP BY pm.customer_guid, pm.refno");

                    //strb reminder here
                    $task = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE force_strb = '1' AND CURDATE() >= strb_start_date AND strb_start_date IS NOT NULL GROUP BY customer_guid");
                    // $task = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE force_strb = '1' AND CURDATE() >= strb_start_date UNION ALL SELECT * FROM lite_b2b.acc_settings WHERE force_strb = '1' AND CURDATE() >= strb_start_date");
                    $strb_loop = array();
                    // echo $this->db->last_query();die;
                    if ($task->num_rows() > 0) {
                        foreach ($task->result() as $row) {
                            $database1 = 'lite_b2b';
                            $updated_record = 0;
                            $rb_notification = $row->RB_email_notification;
                            $rb_notification_1 = $row->RB_email_notification_1;
                            $rb_notification_2 = $row->RB_email_notification_2;
                            $rb_auto_gen_dn_days = $row->RB_auto_gen_DN_days;
                            $rb_start_date = $row->strb_start_date;

                            $customer_guid = $row->customer_guid;
                            $customer_name = $this->db->query("SELECT * FROM $database1.acc WHERE acc_guid = '$customer_guid'");
                            $database2 = 'b2b_summary';
                            // echo $rb_notification.'--'.$rb_notification_1.'--'.$rb_notification_2.'--'.$rb_auto_gen_dn_days;
                            $q_result2 = $this->db->query("SELECT a.*, doc_date, expiry_date, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), doc_date ) AS TIMEDIFF,'New' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND doc_date = CURDATE() - INTERVAL 1 DAY 
                            AND doc_date >= '$rb_start_date' 
                            AND a.status = 0
                            UNION ALL 
                            SELECT a.*, doc_date, expiry_date, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), doc_date ) AS TIMEDIFF,'1st Reminder' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND DATEDIFF( CURDATE(), doc_date ) = $rb_notification_1  
                            AND doc_date >= '$rb_start_date' 
                            AND a.status = 0
                            UNION ALL 
                            SELECT a.*, doc_date, expiry_date, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), doc_date ) AS TIMEDIFF,'2nd Reminder' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND DATEDIFF( CURDATE(), doc_date ) = $rb_notification_2  
                            AND doc_date >= '$rb_start_date' 
                            AND a.status = 0
                            UNION ALL 
                            SELECT a.*, doc_date, expiry_date, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), doc_date ) AS TIMEDIFF,'Last Reminder' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND a.status = 0 
                            AND DATEDIFF( CURDATE(), doc_date ) = $rb_auto_gen_dn_days");
                            //echo $this->db->last_query();die;
                            if ($q_result2->num_rows() > 0) {
                                foreach ($q_result2->result() as $strb) {
                                    $strb_loop[] = array(
                                        'acc_name' => $strb->acc_name,
                                        'batch_no' => $strb->batch_no,
                                        'doc_date' => $strb->doc_date,
                                        'sup_code' => $strb->sup_code,
                                        'sup_name' => $strb->sup_name,
                                        'loc_group' => $strb->loc_group,
                                        'TYPE' => $strb->TYPE,
                                        'expiry_date' => $strb->expiry_date,
                                    );
                                }
                                //print_r($strb_loop); die;
                            }
                            if ($q_result2->num_rows() > 0) {
                                $q_result2_show++;
                            }
                        }
                    }

                    //e-inv reminder here
                    $task2 = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE force_einvoice = '1' AND CURDATE() >= GRN_start_date AND GRN_start_date IS NOT NULL GROUP BY customer_guid");
                    $einv_main_loop = array();
                    // echo $this->db->last_query();die;
                    if ($task2->num_rows() > 0) {
                        // echo 1;die;
                        foreach ($task2->result() as $row) {
                            $database1 = 'lite_b2b';
                            $interval_setting_day = $row->GRN_auto_einv_days;
                            $first_notification_setting_day = $row->GRN_einv_notification_1;
                            $customer_guid = $row->customer_guid;
                            $ver = 0;
                            $start_gr_date = $row->GRN_start_date;

                            // echo $interval_setting_day;die;
                            $table2 = 'grmain';

                            $q_result3 = $this->db->query("SELECT f.acc_name,a.*,DATE_ADD(a.grdate,INTERVAL $first_notification_setting_day DAY) AS after_interval,'Reminder' as TYPE FROM b2b_summary.grmain a INNER JOIN $database1.set_supplier_group b ON a.customer_guid = b.customer_guid AND a.code = b.supplier_group_name INNER JOIN $database1.set_supplier_user_relationship c ON b.supplier_group_guid = c.supplier_group_guid AND b.customer_guid = c.customer_guid AND c.user_guid = '$user_guid' INNER JOIN $database1.set_user_branch d ON c.user_guid = d.user_guid AND c.customer_guid = d.acc_guid INNER JOIN $database1.acc_branch e ON d.branch_guid = e.branch_guid AND a.loc_group = e.branch_code INNER JOIN lite_b2b.acc f ON a.customer_guid = f.acc_guid WHERE a.grdate >= '$start_gr_date' AND a.customer_guid = '$customer_guid' AND DATE_ADD(a.grdate,INTERVAL $first_notification_setting_day DAY) = CURDATE() UNION ALL SELECT f.acc_name,a.*,DATE_ADD(a.grdate,INTERVAL $interval_setting_day DAY) AS after_interval,'System Generate' as TYPE FROM b2b_summary.grmain a INNER JOIN $database1.set_supplier_group b ON a.customer_guid = b.customer_guid AND a.code = b.supplier_group_name INNER JOIN $database1.set_supplier_user_relationship c ON b.supplier_group_guid = c.supplier_group_guid AND b.customer_guid = c.customer_guid AND c.user_guid = '$user_guid' INNER JOIN $database1.set_user_branch d ON c.user_guid = d.user_guid AND c.customer_guid = d.acc_guid INNER JOIN $database1.acc_branch e ON d.branch_guid = e.branch_guid AND a.loc_group = e.branch_code INNER JOIN lite_b2b.acc f ON a.customer_guid = f.acc_guid WHERE a.grdate >= '$start_gr_date' AND a.customer_guid = '$customer_guid' AND DATE_ADD(a.grdate,INTERVAL $interval_setting_day DAY) = CURDATE() ");

                            // echo $this->db->last_query();die;
                            if ($q_result3->num_rows() > 0) {
                                foreach ($q_result3->result() as $einv) {
                                    $einv_main_loop[] = array(
                                        'acc_name' => $einv->acc_name,
                                        'gr_refno' => $einv->RefNo,
                                        'doc_date' => $einv->GRDate,
                                        'sup_code' => $einv->Code,
                                        'sup_name' => $einv->Name,
                                        'loc_group' => $einv->loc_group,
                                        'TYPE' => $einv->TYPE,
                                    );
                                }
                            }
                            // echo json_encode($einv_main_loop);die;
                            if ($q_result3->num_rows() > 0) {
                                $q_result3_show++;
                            }
                        }
                    }

                    if ($q_result->num_rows() == 0 && $q_result2_show == 0 && $q_result3_show == 0) {
                        // echo 1;die;
                        redirect('Export_controller/no_result');
                    }
                    // echo $this->db->last_query();die;
                }
            } else {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");

                //$data = $q_result->result_array();
            };
        };

        $email_add = $this->db->query("SELECT email from check_email_schedule where schedule_guid = '" . $_REQUEST['schedule_guid'] . "' and report_id = '" . $_REQUEST['report_id'] . "' and user_guid = '" . $_REQUEST['user_guid'] . "'")->row('email');

        // $email_subject = $this->db->query("SELECT concat(report_name, ' between ', '$datefrom' , ' to ', '$dateto') as email_subject from set_report_query where report_id = '$report_id'")->row('email_subject');
        $email_subject = "xBridge B2B Reminder";
        //echo $this->db->last_query();die;
        $date = $this->db->query("SELECT now() as now")->row('now');

        $data = array(
            'q_result' => $q_result,
            'q_result2' => $q_result2,
            'q_result2_show' => $q_result2_show,
            'strb_loop' => $strb_loop,
            'q_result3_show' => $q_result3_show,
            'einv_main_loop' => $einv_main_loop,
        );
        // if($q_result2_show > 0)
        // {
        //     $data = array(
        //         'q_result' => $q_result,
        //         'q_result2' => $q_result2, 
        //         'q_result2_show' => $q_result2_show,           
        //     );
        // }
        // else
        // {
        //     $data = array(
        //         'q_result' => $q_result ,
        //         'q_result2_show' => $q_result2_show,            
        //     );            
        // }

        $bodyContent = $this->load->view('email_content', $data, TRUE);
        $module = 'direct_content';
        // echo $bodyContent;die;

        $email_result = $this->send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module, 'support@xbridge.my');

        //$this->send_direct_content($email_add, $date, $bodyContent, $email_subject, $module);

    }

    public function direct_content()
    {
        //echo 1;die;
        // print_r($_REQUEST); die;
        $datefrom = $this->db->query("SELECT DATE_FORMAT('" . $_REQUEST['date_start'] . "','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
        $dateto = $_REQUEST['date_start'];
        $report_id = $_REQUEST['report_id']; //YWFmNzEyMGYtOQ
        $customer_guid = $this->db->query("SELECT customer_guid from set_report_schedule where schedule_guid = '" . $_REQUEST['schedule_guid'] . "'")->row('customer_guid');

        $check_excel_query = $this->db->query("SELECT * from set_report_query where report_id = '$report_id'");
        $check_schedule_type = $this->db->query("SELECT * from set_report_schedule where report_guid = '" . $check_excel_query->row('report_guid') . "'");

        // echo $this->db->last_query(); die;
        $q_result2_show = 0; // strb
        $q_result3_show = 0; // e-inv

        if ($check_schedule_type->row('schedule_type') == 'weekly') {
            if ($check_excel_query->num_rows() > 0) {
                foreach ($check_excel_query->result() as $row) {
                    $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@EDateFrom', '$datefrom'), '@EDateTo', '$dateto'),'@customer_guid','$customer_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    $q_result = $this->db->query($replace_var);

                                        
                    // testing data
                    // $q_result = $this->db->query("SELECT 
                    //     b.acc_name,
                    //     a.refno,
                    //     a.podate,
                    //     a.supplier_code AS scode,
                    //     a.supplier_name AS sname,
                    //     a.loc_group 
                    // FROM b2b_summary.pomain_info a 
                    // INNER JOIN lite_b2b.acc b
                    // ON a.customer_guid = b.acc_guid");
                    //   echo $this->db->last_query();die;
                }
            } else {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");
            };
        };

        if ($check_schedule_type->row('schedule_type') == 'daily') {
            if ($check_excel_query->num_rows() > 0) {
                $datefrom = $this->db->query("SELECT DATE_FORMAT('" . $_REQUEST['date_start'] . "','%Y-%m-%d') - INTERVAL 7 DAY as datefrom")->row('datefrom');
                $user_guid =  $_REQUEST['user_guid'];
                $customer_guid = $check_schedule_type->row('customer_guid');

                foreach ($check_excel_query->result() as $row) {
                    $replace_var = $this->db->query("SELECT REPLACE(query, '@user_guid', '$user_guid') as query1 from set_report_query where report_id = '$report_id' order by seq desc")->row('query1');
                    //pomain reminder here... 
                    $q_result = $this->db->query($replace_var);
                    // $q_result = $this->db->query("SELECT 
                    //     b.acc_name,
                    //     a.refno,
                    //     a.podate,
                    //     a.supplier_code AS scode,
                    //     a.supplier_name AS sname,
                    //     a.loc_group 
                    // FROM b2b_summary.pomain_info a 
                    // INNER JOIN lite_b2b.acc b
                    // ON a.customer_guid = b.acc_guid");
                    
                    // $q_result = $this->db->query("SELECT acc_name, pm.refno, pm.podate, scode, sname, IF( cc.branch_desc IS NULL || cc.`branch_desc` = '', loc_group, CONCAT(loc_group, ' - ', cc.branch_desc) ) AS branch FROM b2b_summary.pomain AS pm INNER JOIN lite_b2b.acc AS acc ON pm.customer_guid = acc.acc_guid INNER JOIN (SELECT supplier_group_name, acc_guid, a.user_guid FROM lite_b2b.check_user_supplier_customer_relationship AS a INNER JOIN lite_b2b.check_email_schedule AS b ON a.user_guid = b.user_guid WHERE report_id = 'YWFmNzEyMGYtOQ' AND LEFT(date_start, 10) >= CURDATE() AND a.user_guid = '$user_guid' AND suspended != 1) cus ON cus.supplier_group_name = pm.scode AND cus.acc_guid = pm.`customer_guid` INNER JOIN b2b_summary.cp_set_branch cc ON pm.loc_group = cc.branch_code AND pm.customer_guid = cc.customer_guid INNER JOIN (SELECT a.acc_guid, c.branch_code FROM lite_b2b.acc_concept a INNER JOIN lite_b2b.acc_branch_group b ON a.concept_guid = b.concept_guid INNER JOIN lite_b2b.acc_branch c ON b.branch_group_guid = c.branch_group_guid INNER JOIN lite_b2b.set_user_branch d ON c.branch_guid = d.branch_guid WHERE d.user_guid = '$user_guid') dd ON cc.branch_code = dd.branch_code AND cc.customer_guid = dd.acc_guid WHERE `status` IN ('') AND podate BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND user_guid = '$user_guid' AND in_kind = 0 GROUP BY pm.customer_guid, pm.refno");

                    //strb reminder here
                    $task = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE force_strb = '1' AND CURDATE() >= strb_start_date AND strb_start_date IS NOT NULL GROUP BY customer_guid");
                    // $task = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE force_strb = '1' AND CURDATE() >= strb_start_date UNION ALL SELECT * FROM lite_b2b.acc_settings WHERE force_strb = '1' AND CURDATE() >= strb_start_date");
                    $strb_loop = array();
                    // echo $this->db->last_query();die;
                    if ($task->num_rows() > 0) {
                        foreach ($task->result() as $row) {
                            $database1 = 'lite_b2b';
                            $updated_record = 0;
                            $rb_notification = $row->RB_email_notification;
                            $rb_notification_1 = $row->RB_email_notification_1;
                            $rb_notification_2 = $row->RB_email_notification_2;
                            $rb_auto_gen_dn_days = $row->RB_auto_gen_DN_days;
                            $rb_start_date = $row->strb_start_date;

                            $customer_guid = $row->customer_guid;
                            $customer_name = $this->db->query("SELECT * FROM $database1.acc WHERE acc_guid = '$customer_guid'");
                            $database2 = 'b2b_summary';
                            // echo $rb_notification.'--'.$rb_notification_1.'--'.$rb_notification_2.'--'.$rb_auto_gen_dn_days;
                            $q_result2 = $this->db->query("SELECT a.*, doc_date,  IF(a.srb_accept_days <= '0', a.expiry_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY)) AS `expiry_date`, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), IF(a.srb_accept_days <= '0', a.doc_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY))) AS TIMEDIFF,'New' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND doc_date = CURDATE() - INTERVAL 1 DAY 
                            AND doc_date >= '$rb_start_date' 
                            AND a.status = 0
                            UNION ALL 
                            SELECT a.*, doc_date,  IF(a.srb_accept_days <= '0', a.expiry_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY)) AS `expiry_date`, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), IF(a.srb_accept_days <= '0', a.doc_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY))) AS TIMEDIFF,'1st Reminder' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND DATEDIFF( CURDATE(), IF(a.srb_accept_days <= '0', a.doc_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY))) = $rb_notification_1  
                            AND doc_date >= '$rb_start_date' 
                            AND a.status = 0
                            UNION ALL 
                            SELECT a.*, doc_date,  IF(a.srb_accept_days <= '0', a.expiry_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY)) AS `expiry_date`, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), IF(a.srb_accept_days <= '0', a.doc_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY))) AS TIMEDIFF,'2nd Reminder' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND DATEDIFF( CURDATE(), IF(a.srb_accept_days <= '0', a.doc_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY))) = $rb_notification_2  
                            AND doc_date >= '$rb_start_date' 
                            AND a.status = 0
                            UNION ALL 
                            SELECT a.*, doc_date,  IF(a.srb_accept_days <= '0', a.expiry_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY)) AS `expiry_date`, CURDATE(), DATE_ADD(doc_date, INTERVAL 7 DAY) as after_config_date, DATEDIFF( CURDATE(), IF(a.srb_accept_days <= '0', a.doc_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY))) AS TIMEDIFF,'Last Reminder' as TYPE,f.acc_name 
                            FROM $database2.dbnote_batch a 
                            INNER JOIN $database1.set_supplier_group b 
                            ON a.customer_guid = b.customer_guid 
                            AND a.sup_code = b.supplier_group_name 
                            INNER JOIN $database1.set_supplier_user_relationship c 
                            ON b.supplier_group_guid = c.supplier_group_guid 
                            AND b.customer_guid = c.customer_guid 
                            AND c.user_guid = '$user_guid' 
                            INNER JOIN $database1.set_user_branch d 
                            ON c.user_guid = d.user_guid 
                            AND c.customer_guid = d.acc_guid 
                            INNER JOIN $database1.acc_branch e 
                            ON d.branch_guid = e.branch_guid 
                            AND a.loc_group = e.branch_code 
                            INNER JOIN lite_b2b.acc f 
                            ON a.customer_guid = f.acc_guid 
                            WHERE a.customer_guid = '$customer_guid' 
                            AND a.status = 0 
                            AND DATEDIFF( CURDATE(), IF(a.srb_accept_days <= '0', a.doc_date , DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY))) = $rb_auto_gen_dn_days");
                            //echo $this->db->last_query();die;
                            if ($q_result2->num_rows() > 0) {
                                foreach ($q_result2->result() as $strb) {
                                    $strb_loop[] = array(
                                        'acc_name' => $strb->acc_name,
                                        'batch_no' => $strb->batch_no,
                                        'doc_date' => $strb->doc_date,
                                        'sup_code' => $strb->sup_code,
                                        'sup_name' => $strb->sup_name,
                                        'loc_group' => $strb->loc_group,
                                        'TYPE' => $strb->TYPE,
                                        'expiry_date' => $strb->expiry_date,
                                    );
                                }
                                //print_r($strb_loop); die;
                            }
                            if ($q_result2->num_rows() > 0) {
                                $q_result2_show++;
                            }
                        }
                    }

                    //e-inv reminder here
                    $task2 = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE CURDATE() >= einv_grab_date AND einv_grab_date IS NOT NULL GROUP BY customer_guid");
                    $einv_main_loop = array();
                    // echo $this->db->last_query();die;
                    if ($task2->num_rows() > 0) {
                        // echo 1;die;
                        foreach ($task2->result() as $row) {
                            $database1 = 'lite_b2b';
                            $interval_setting_day = $row->GRN_auto_einv_days;
                            $first_notification_setting_day = $row->GRN_einv_notification_1;
                            $second_notification_setting_day = $row->GRN_einv_notification_2;
                            $customer_guid = $row->customer_guid;
                            $ver = 0;
                            //$start_gr_date = $row->GRN_start_date; // old column no use
                            $einv_grab_date = $row->einv_grab_date;

                            // echo $interval_setting_day;die;
                            $table2 = 'grmain_info';

                            $q_result3 = $this->db->query("SELECT 
                                f.acc_name, 
                                a.refno,
                                a.GRDate,
                                a.supplier_code,
                                a.supplier_name,
                                a.loc_group,
                                DATE_ADD(a.grdate, INTERVAL $first_notification_setting_day DAY) AS after_interval, 
                                'Reminder' AS TYPE 
                            FROM 
                                b2b_summary.$table2 a 
                                INNER JOIN lite_b2b.set_supplier_group b ON a.customer_guid = b.customer_guid 
                                AND a.supplier_code = b.supplier_group_name 
                                INNER JOIN lite_b2b.set_supplier_user_relationship c ON b.supplier_group_guid = c.supplier_group_guid 
                                AND b.customer_guid = c.customer_guid 
                                AND c.user_guid = '$user_guid' 
                                INNER JOIN lite_b2b.set_user_branch d ON c.user_guid = d.user_guid 
                                AND c.customer_guid = d.acc_guid 
                                INNER JOIN lite_b2b.acc_branch e ON d.branch_guid = e.branch_guid 
                                AND a.loc_group = e.branch_code 
                                INNER JOIN lite_b2b.acc f ON a.customer_guid = f.acc_guid 
                            WHERE 
                                a.grdate >= '$einv_grab_date' 
                                AND a.customer_guid = '$customer_guid' 
                                AND DATE_ADD(a.grdate, INTERVAL $first_notification_setting_day DAY) = CURDATE() 
                                AND a.status IN ('', 'viewed') 
                            UNION ALL 
                            SELECT 
                                f.acc_name, 
                                a.refno,
                                a.GRDate,
                                a.supplier_code,
                                a.supplier_name,
                                a.loc_group,
                                DATE_ADD(a.grdate, INTERVAL $second_notification_setting_day DAY) AS after_interval, 
                                'Last Reminder' AS TYPE 
                            FROM 
                                b2b_summary.$table2 a 
                                INNER JOIN lite_b2b.set_supplier_group b ON a.customer_guid = b.customer_guid 
                                AND a.supplier_code = b.supplier_group_name 
                                INNER JOIN lite_b2b.set_supplier_user_relationship c ON b.supplier_group_guid = c.supplier_group_guid 
                                AND b.customer_guid = c.customer_guid 
                                AND c.user_guid = '$user_guid' 
                                INNER JOIN lite_b2b.set_user_branch d ON c.user_guid = d.user_guid 
                                AND c.customer_guid = d.acc_guid 
                                INNER JOIN lite_b2b.acc_branch e ON d.branch_guid = e.branch_guid 
                                AND a.loc_group = e.branch_code 
                                INNER JOIN lite_b2b.acc f ON a.customer_guid = f.acc_guid 
                            WHERE 
                                a.grdate >= '$einv_grab_date' 
                                AND a.customer_guid = '$customer_guid' 
                                AND DATE_ADD(a.grdate, INTERVAL $second_notification_setting_day DAY) = CURDATE() 
                                AND a.status IN ('', 'viewed')                        
                            ");

                            // echo $this->db->last_query();die;
                            if ($q_result3->num_rows() > 0) {
                                foreach ($q_result3->result() as $einv) {
                                    $einv_main_loop[] = array(
                                        'acc_name' => $einv->acc_name,
                                        'gr_refno' => $einv->refno,
                                        'doc_date' => $einv->GRDate,
                                        'sup_code' => $einv->supplier_code,
                                        'sup_name' => $einv->supplier_name,
                                        'loc_group' => $einv->loc_group,
                                        'TYPE' => $einv->TYPE,
                                    );
                                }
                            }
                            // echo json_encode($einv_main_loop);die;
                            if ($q_result3->num_rows() > 0) {
                                $q_result3_show++;
                            }
                        }
                    }

                    if ($q_result->num_rows() == 0 && $q_result2_show == 0 && $q_result3_show == 0) {
                        // echo 1;die;
                        redirect('Export_controller/no_result');
                    }
                    // echo $this->db->last_query();die;
                }
            } else {
                $check_excel_query =  $this->db->query("SELECT 'No Data' as result, 'No Data' as report_name");
                $q_result = $this->db->query("SELECT 'No Data' as result");

                //$data = $q_result->result_array();
            };
        };

        $email_add = $this->db->query("SELECT email from check_email_schedule where schedule_guid = '" . $_REQUEST['schedule_guid'] . "' and report_id = '" . $_REQUEST['report_id'] . "' and user_guid = '" . $_REQUEST['user_guid'] . "'")->row('email');

        // $email_subject = $this->db->query("SELECT concat(report_name, ' between ', '$datefrom' , ' to ', '$dateto') as email_subject from set_report_query where report_id = '$report_id'")->row('email_subject');
        $email_subject = "Sunshine B2B Reminder";
        //echo $this->db->last_query();die;
        $date = $this->db->query("SELECT now() as now")->row('now');

        $data = array(
            'q_result' => $q_result,
            'q_result2' => $q_result2,
            'q_result2_show' => $q_result2_show,
            'strb_loop' => $strb_loop,
            'q_result3_show' => $q_result3_show,
            'einv_main_loop' => $einv_main_loop,
        );
        
        $bodyContent = $this->load->view('email_content', $data, TRUE);
        $module = 'direct_content';
        
        //echo $bodyContent;die;
        //$email_add = 'jiangrui.goh@pandasoftware.my';
        // $email_add = 'xytai@xbridge.my';

        $this->load->library('Daily_attachment');
        $pdf = new Daily_attachment('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('');
        $pdf->SetAuthor('B2B');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setPageUnit('pt');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $font_size = $pdf->pixelsToUnits('8');
        $pdf->SetFont('times', '', $font_size, '', 'default', true);
        $pdf->AddPage('L', 'A4');

        $html = $bodyContent;

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();
        ob_end_clean();

        $running_number = rand();

        $file_name = 'Daily_attachment_'.$running_number.'.pdf';

        $local_file_path = $this->db->query("SELECT value
        FROM lite_b2b.config
        WHERE device = 'web'
        AND module = 'dailymail'
        AND type = 'main_path'
        AND code = 'DEML'")->row('value');

        //echo $local_file_path;die;

        $supplier_array_file = explode('/', substr($local_file_path, 0, -1));

        $file_path_string = '';

        // check path exists
        foreach ($supplier_array_file as $row) {
            $file_path_string .= $row . '/';

            if (!file_exists($file_path_string)) {
                mkdir($file_path_string, 0777, true);
                chmod($file_path_string, 0777);
            }
        }

        $pdf->Output($local_file_path  . $file_name, 'F'); // I - preview , F - insert

        //echo $bodyContent;die;
        
        //$email_result = $this->Send_email_modal->send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module, 'support@xbridge.my',$local_file_path.$file_name , $file_name);

        $email_result = $this->Send_email_model->send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module , '',$local_file_path.$file_name ,'no-reply@Sunshineonline.com.my', $file_name);
        // $email_result = $this->send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module , '',$local_file_path.$file_name ,'support@xbridge.my', $file_name);

        unlink($local_file_path  . $file_name);

        if($email_result == 'Success')
        {
            $response = array(
                'status' => true,
                'message' => "Daily Notification Send Success.",
            );
          
            echo json_encode($response);die;
        }
        else
        {
            $response = array(
                'status' => true,
                'message' => "Daily Notification Send Failed.",
            );
          
            echo json_encode($response);die;
        }

        
        //$this->send_direct_content($email_add, $date, $bodyContent, $email_subject, $module);
    }

    public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module, $cc_list_string, $pdf, $reply_to, $filename)
    {
        //$b64Doc = chunk_split(base64_encode(file_get_contents('uploads/qr_code/4/cc.pdf')));
        if ($pdf != '' || $pdf != null) {   //echo substr($pdf,162);;die;
            //$b64Doc = chunk_split($pdf); 
            $b64Doc = base64_encode(file_get_contents($pdf));

            $filename = $filename;
        } else {
            $b64Doc = '';
        }
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM mailjet_setup WHERE type = 'daily_po_notification' LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
        // $to_email = 'danielweng57@gmail.com';
        // $to_email_name = 'danielweng57@gmail.com';
        // $cc = 'desmondm520@gmail.com';
        // $cc_name = 'desmondm520@gmail.com';
        $variable = array('api_key' => '1234', 'secret_key' => '123456', 'module' => 'test');

        $replyto = array('Email' => $reply_to, 'Name' => $reply_to);
        $from = array('Email' => $from_email->row('sender_email'), 'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email, 'Name' => $to_email_name);
        $to_array = array($to);

        // cc here
        if ($cc_list_string != '' || $cc_list_string != null) {
            // $test_array = explode(',', $cc_list_string);
            // $cc_array = array();
            // foreach ($test_array as $tarray) {
            //     // echo $tarray->sender_email;
            //     $cc = array('Email' => $tarray, 'Name' => $tarray);
            //     array_push($cc_array, $cc);
            // }
            $cc_array = array();
            foreach ($cc_list_string as $tarray) {

                //print_r($tarray); die;
                $cc = array('Email' => $tarray, 'Name' => $tarray);
                array_push($cc_array, $cc);
            }
        } else {
            $cc_array = '';
        }

        // $cc = array('Email' => $cc_name, 'Name' => $cc_name);
        // $cc_array = array($cc);
        // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
        $bcc_array = array();
        $variable1 = array($variable);
        $variables = array('var1' => $variable1);
        // $variables_array = array($variables);
        $templateid = 1090613;
        $Subject = $email_subject;
        $TextPart = $email_subject;
        $HTMLPart = $bodyContent;
        $attachment = array('ContentType' => 'application/pdf', 'Filename' => $filename, 'Base64Content' => $b64Doc);
        $attachment1 = array($attachment);
        $attachment_array = array($attachment);

        if ($b64Doc != '') {
            $data = array('from' => $from, 'to' => $to_array, 'subject' => $Subject, 'textpart' => $TextPart, 'htmlpart' => $HTMLPart, 'variables' => $variables, 'cc' => $cc_array, 'replyto' => $replyto, 'attachments' => $attachment_array);
        } else {
            $data = array('from' => $from, 'to' => $to_array, 'subject' => $Subject, 'textpart' => $TextPart, 'htmlpart' => $HTMLPart, 'variables' => $variables, 'cc' => $cc_array, 'replyto' => $replyto);
        }

        //$data = array('from' => $from, 'to' => $to_array, 'subject' => $Subject, 'textpart' => $TextPart, 'htmlpart' => $HTMLPart, 'variables' => $variables, 'replyto' => $replyto);
        //$data2 = array($data);
        // $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data);
        // echo $myJSON;die;

        $to_shoot_url = $this->local_ip . "/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $myJSON,
            CURLOPT_HTTPHEADER => array(
                "x-api-key: 123456",
                "Content-Type: application/json"
            ),
        ));

        // $to_shoot_url = $this->local_ip.'/pandaapi3rdparty/index.php/email_agent/mj_sendemail';
        // $ch = curl_init($to_shoot_url); 
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "123456" ));
        // curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        // // curl_setopt($ch, CURLOPT_USERPWD, $mailjet_user.":".$mailjet_pass);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $myJSON);
        $result = curl_exec($ch);
        $result1 = json_decode($result);
        //print_r($result);die;
        $retry = 0;
        while (curl_errno($ch) == 28 && $retry < 3) {
            $response = curl_exec($ch);
            $retry++;
        }

        if (!curl_errno($ch)) {
            if (isset($result1->Messages[0])) {
                $status = $result1->Messages[0]->Status;
            } else {
                $status = $result1->ErrorMessage;
            }


            if ($status == 'success') {
                $ereponse = $result1->Messages[0]->To[0]->MessageID;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' => 'URL_TASK',
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'SUCCESS',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                if ($module != 'alert_notification') {
                    echo json_encode(array(
                        'status' => true,
                        'message' => 'success',
                        'action' => 'next',
                    ));
                };
            } else {
                $ereponse = $result1->StatusCode . '-' . $result1->ErrorMessage;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' => 'URL_TASK',
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                // if($module != 'alert_notification')
                // {
                echo json_encode(array(
                    'status' => true,
                    'message' => 'success',
                    'action' => 'next',
                ));
                // };
            }

            curl_close($ch);
        } else {
            $ereponse = 'Curl error: ' . curl_error($ch);

            $data = array(
                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => 'URL_TASK',
                'recipient' => $to_email,
                'sender' => $from_email->row('sender_email'),
                'subject' => $email_subject,
                'status' => 'FAIL',
                'respond_message' => $retry . $ereponse,
                'smtp_server' => 'mailjet',
                'smtp_port' => 'mailjet',
                'smtp_security' => 'mailjet',
            );
            $this->db->insert('email_transaction', $data);
            // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            //redirect('Email_controller/setup');
            // if($module != 'alert_notification')
            // {
            echo json_encode(array(
                'status' => true,
                'message' => 'success',
                'action' => 'next',
            ));
        }
    }

    public function send_mailjet($email_add, $date, $bodyContent, $email_subject, $module)
    {
        // echo $email_add.$date.$bodyContent.'-'.$email_subject.'-'.$module;die;
        $b64Doc = chunk_split(base64_encode(file_get_contents('uploads/qr_code/4/cc.pdf')));
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM mailjet_setup LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
        $to_email = 'danielweng57@gmail.com';
        $to_email_name = 'danielweng57@gmail.com';
        $cc = '';
        $cc_name = '';
        $variable = 'daniel';

        $from = array('Email' => $from_email->row('sender_email'), 'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email, 'Name' => $to_email_name);
        $to_array = array($to);
        $cc = array();
        $cc_array = array();
        // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
        $bcc_array = array();
        $variables = array('name' => $variable);
        // $variables_array = array($variables);
        $templateLanguage = true;
        $Subject = $email_subject;
        $TextPart = $email_subject;
        $HTMLPart = $bodyContent;
        $attachment = array('ContentType' => 'application/pdf', 'Filename' => 'sample.pdf', 'Base64Content' => $b64Doc);
        $attachment_array = array($attachment);
        $data = array('From' => $from, 'To' => $to_array, 'Cc' => $cc_array, 'Bcc' => $bcc_array, 'Variables' => $variables, "TemplateLanguage" => $templateLanguage, 'Subject' => $Subject, 'TextPart' => $TextPart, 'HTMLPart' => $HTMLPart);
        $data2 = array($data);
        $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data3);
        // echo $myJSON;die;

        $mailjet_user = $from_email->row('username');
        $mailjet_pass = $from_email->row('password');

        // echo $myJSON;die;
        $to_shoot_url = 'https://api.mailjet.com/v3.1/send';
        $ch = curl_init($to_shoot_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
        curl_setopt($ch, CURLOPT_USERPWD, $mailjet_user . ":" . $mailjet_pass);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myJSON);
        $result = curl_exec($ch);
        $result1 = json_decode($result);
        // print_r($result1);
        $retry = 0;
        while (curl_errno($ch) == 28 && $retry < 3) {
            $response = curl_exec($ch);
            $retry++;
        }

        if (!curl_errno($ch)) {
            if (isset($result1->Messages[0])) {
                $status = $result1->Messages[0]->Status;
            } else {
                $status = $result1->ErrorMessage;
            }


            if ($status == 'success') {
                $ereponse = $result1->Messages[0]->To[0]->MessageID;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' => 'URL_TASK',
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'SUCCESS',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                if ($module != 'alert_notification') {
                    echo json_encode(array(
                        'status' => true,
                        'message' => 'success',
                        'action' => 'next',
                    ));
                };
            } else {
                $ereponse = $result1->StatusCode . '-' . $result1->ErrorMessage;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' => 'URL_TASK',
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                // if($module != 'alert_notification')
                // {
                echo json_encode(array(
                    'status' => false,
                    'message' => $ereponse,
                    'action' => 'retry',
                ));
                // };
            }

            curl_close($ch);
        } else {
            $ereponse = 'Curl error: ' . curl_error($ch);

            $data = array(
                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => 'URL_TASK',
                'recipient' => $to_email,
                'sender' => $from_email->row('sender_email'),
                'subject' => $email_subject,
                'status' => 'FAIL',
                'respond_message' => $retry . $ereponse,
                'smtp_server' => 'mailjet',
                'smtp_port' => 'mailjet',
                'smtp_security' => 'mailjet',
            );
            $this->db->insert('email_transaction', $data);
            // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            //redirect('Email_controller/setup');
            // if($module != 'alert_notification')
            // {
            echo json_encode(array(
                'status' => false,
                'message' => $ereponse,
                'action' => 'retry',
            ));
        }
    }

    public function send_direct_content($email_add, $date, $bodyContent, $email_subject, $module)
    {
        $email_the_data = $this->db->query("SELECT * from lite_b2b.email_setup limit 1");

        $mail = new PHPMailer;

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host =  $email_the_data->row('smtp_server');
        //$mail->Host = 'smtp.gmail.com'; //$email_the_data->row('smtp_server'); // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username =   $email_the_data->row('username'); // SMTP username
        //$mail->Username = 'rexbridge.b2b@gmail.com'; // $email_the_data->row('email_username'); // SMTP username
        $mail->Password =   $email_the_data->row('password'); // SMTP password
        //$mail->Password = '80998211';// $email_the_data->row('email_password'); // SMTP password
        //$mail->SMTPSecure =   $email_the_data->row('smtp_security');// Enable TLS encryption, `ssl` also accepted
        $mail->SMTPSecure = 'TLS'; // $email_the_data->row('smtp_security');// Enable TLS encryption, `ssl` also accepted
        //$mail->Port =  $email_the_data->row('smtp_port');// TCP port to connect to
        $mail->Port = '587'; // $email_the_data->row('smtp_port');// TCP port to connect to

        //$mail->setFrom($email_the_data->row('sender_email'), $email_the_data->row('sender_name'));
        $mail->setFrom('support@xbridge.my', 'B2B-No Reply');
        //$mail->addReplyTo($email_the_data->row('sender_email'), $email_the_data->row('sender_name'));
        $mail->addReplyTo('support@xbridge.my', 'B2B');
        $mail->addAddress($email_add, 'Admin'); // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML
        $path = base_url('assets/img/new.png');


        $mail->Subject = $email_subject;
        $mail->Body    = $bodyContent;

        if (!$mail->send()) {
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo; 
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
                'message' => $mail->ErrorInfo,
                'action' => 'retry',
            ));
        } else {


            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => 'URL_TASK',
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
            //  redirect('Email_controller/setup');
            // echo 'Message has been sent';

            if ($module != 'alert_notification') {
                echo json_encode(array(
                    'status' => true,
                    'message' => 'success',
                    'action' => 'next',
                ));
            };
        }
    }

    public function no_result()
    {
        $response = array(
            'status' => true,
            'message' => "No Data.",
        );
      
        echo json_encode($response);die;

        // echo json_encode(array(
        //     'status' => true,
        //     'message' => 'nodata',
        // ));

        // die;
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
            'smtp_server' => $email->row('smtp_server'),
            'en' => $email->row('username'),
            'ep' => $email->row('password'),
            'smtp_security' => $email->row('smtp_security'),
            'smtp_port' => $email->row('smtp_port'),
            'sender_email' => $email->row('sender_email'),
            'sender_name' => $email->row('sender_name'),
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
                                           ' . $email_subject . '
                                        <br>
                                        Regards,<br>
                                        <a href="' . $_SESSION['url'] . '"> B2B Mail</a>
                                    </p>
                                </div>
                            </div>
                        </div>';


        foreach ($email_group->result() as $row) {
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
        $mail->SMTPSecure = $_SESSION['smtp_security']; // Enable TLS encryption, `ssl` also accepted
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
        $filepath = $db_path . $customer_guid;
        $file = $filepath . "/" . $email_subject . ".xls";
        $mail->addAttachment($file, $email_subject . ".xls");
        $_SESSION['userid'] = 'URLTaskAgent';
        if (!$mail->send()) {
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
        } else {
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
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
            $data = array(
                'location' => $this->db->query("SELECT * from config order by location asc"),
                'menutype' => $this->db->query("SELECT * from set_menu where hide_menu <> '1' and rep_code is not null order by parent_sequence asc, sequence asc"),
                'status_recon' => $this->db->query("SELECT * from set_option where active = '1' and type = 'REPORT_STATUS' and code <> 'POST' order by trans_guid"),
                'status_other' => $this->db->query("SELECT * from set_option where active = '1' and type = 'REPORT_STATUS' and code = 'POST'  order by trans_guid"),
            );

            $this->load->view('header');
            $this->load->view('report/report1', $data);
            $this->load->view('footer');
        } else {
            redirect('#');
        }
    }

    public function preview_data()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
            //$menutitle = $this->input->query('menutitle');
            $title = explode('->', $this->input->post('menutitle'));

            $check_menu = $title[0];
            $location = $this->input->post('location');

            if ($check_menu == 'salesrecon' || $check_menu == 'card_trans') {
                $status = $this->input->post('status');
            } else {
                $status = $this->input->post('status_other');
            }

            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            $db_setting = $title[1];

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(query1, '@location', '$location'), '@datefrom', '$datefrom'), '@dateto' , '$dateto'), '@status', '$status'), '@db_setting', '$db_setting') AS query FROM set_query WHERE CODE = '$check_menu' AND TYPE = 'advance_report' AND `condition` = '$status'")->row('query');

            $q_result = $this->db->query($replace_var);

            if ($q_result->num_rows() > 0) {
                $data = $q_result->result_array();
                //load our new PHPExcel library
                $this->load->library('excel');
                //activate worksheet number 1
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle($db_setting . '' . $status);

                $check_export = $this->db->query("SELECT * FROM set_export AS a INNER JOIN set_export_c AS b ON a.`trans_guid`  = b.trans_guid WHERE `key` = '$db_setting'");

                foreach ($check_export->result() as $row) {
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

                $filename = $db_setting . '-' . $today . '.XLSX'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache

                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                ob_end_clean();
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
            } else {
                $this->session->set_flashdata('warning', 'No Data on selected details');
                redirect('export_controller/get_data');
                //echo $replace_var;
            }
        } else {
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

        $filename = 'merchant_report.XLSX'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
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

        $filename = 'merchant_report.XLSX'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        ob_end_clean();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

    //hugh add in return collection, prdn notification 2019-08-29
    public function email_notification()
    {
        echo json_encode(array(
            'status' => true,
            'message' => 'Function inactive at controller',
            'action' => 'inactive',
        ));
        die;
        $module = $_REQUEST['module'];
        // modules to consider
        // - new Return Batch [x]
        // - unattended 1st warning [x]
        // - unattended 2nd warning [x]
        // - PRDN generated [x]
        // - GRN alert need einvoice
        // - GRN alert need ecn
        if ($module == 'alert_notification') {
            $check_unique_supplier = $this->db->query("SELECT * FROM ( /* Return Collection new stock return*/ SELECT '1' AS sort, 'NEW STOCK RETURN' AS title1, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` WHERE STATUS = '0' AND doc_date = DATE_ADD(CURDATE(), INTERVAL - 1 DAY) GROUP BY a.`customer_guid` UNION ALL /* Return Collection 1st warning */ SELECT '2' AS sort, 'Requires Attention #1' AS title1, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD( doc_date, INTERVAL + c.RB_email_notification_1 DAY ) = CURDATE() GROUP BY a.`customer_guid` UNION ALL /* Return Collection 2nd warning */ SELECT '3' AS sort, 'Last Warning Alert #2' AS title1, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD( doc_date, INTERVAL + c.RB_email_notification_2 DAY ) = CURDATE() GROUP BY a.`customer_guid` UNION ALL /* PRDN require einvoice */ SELECT '4' AS sort, 'Pending E-CN' AS title1, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '3' GROUP BY a.`customer_guid` UNION ALL /* PRDN To Collect */ SELECT '5' AS sort, 'To Collect Due in day(s)' AS title1, DATEDIFF(MIN(expiry_date), CURDATE()) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '3' GROUP BY a.`customer_guid` ) a GROUP BY supplier_guid ");

            if ($check_unique_supplier->num_rows() > 0) {
                foreach ($check_unique_supplier->result() as $row) {
                    $email_content = $this->db->query("SELECT sort, title1, doc_type, total, bb.acc_name FROM ( /* Return Collection new stock return*/ SELECT '1' AS sort, CONCAT('New Stock Return @ ', doc_date) AS title1, 'Return Collection' AS doc_type, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` WHERE STATUS = '0' AND doc_date = DATE_ADD(CURDATE(), INTERVAL - 1 DAY) AND b.`supplier_guid` = '" . $row->supplier_guid . "' GROUP BY a.`customer_guid` UNION ALL /* Return Collection 1st warning */ SELECT '2' AS sort, CONCAT( 'Existing Stock Return since ', doc_date, ' email #1' ) AS title1, 'Return Collection' AS doc_type, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD( doc_date, INTERVAL + c.RB_email_notification_1 DAY ) = CURDATE() AND b.`supplier_guid` = '" . $row->supplier_guid . "' GROUP BY a.`customer_guid` UNION ALL /* Return Collection 2nd warning */ SELECT '3' AS sort, CONCAT( 'Existing Stock Return since ', doc_date, ' email #2' ) AS title1, 'Return Collection' AS doc_type, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '0' AND DATE_ADD( doc_date, INTERVAL + c.RB_email_notification_2 DAY ) = CURDATE() AND b.`supplier_guid` = '" . $row->supplier_guid . "' GROUP BY a.`customer_guid` UNION ALL /* PRDN require einvoice */ SELECT '4' AS sort, 'Pending E-CN' AS title1, 'Return Collection' AS doc_type, COUNT(*) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '3' AND b.`supplier_guid` = '" . $row->supplier_guid . "' GROUP BY a.`customer_guid` UNION ALL /* PRDN To Collect */ SELECT '5' AS sort, 'To Collect Due in day(s)' AS title1, 'Purchase Return Debit Note' AS doc_type, DATEDIFF(MIN(expiry_date), CURDATE()) AS total, a.customer_guid, b.`supplier_group_guid`, b.`supplier_guid`, a.batch_no, a.sup_code, a.doc_date, a.expiry_date, a.`status` FROM b2b_summary.dbnote_batch AS a INNER JOIN lite_b2b.`set_supplier_group` AS b ON a.sup_code = b.`backend_supplier_code` AND a.`customer_guid` = b.`customer_guid` INNER JOIN lite_b2b.acc_settings AS c ON a.`customer_guid` = c.customer_guid WHERE STATUS = '3' AND b.`supplier_guid` = '" . $row->supplier_guid . "' GROUP BY a.`customer_guid` ) aa INNER JOIN lite_b2b.acc bb ON aa.customer_guid = bb.acc_guid ORDER BY acc_name, sort   ");

                    $email_add = $this->db->query("SELECT user_id FROM check_user_supplier_customer_relationship WHERE supplier_guid = '" . $row->supplier_guid . "' GROUP BY user_id");

                    $date = $this->db->query("SELECT curdate() as now")->row('now');
                    $email_subject = 'B2B Email Notification @ ' . $date;
                    $data = array(
                        'q_result' => $email_content,
                    );

                    $bodyContent = $this->load->view('email_notification', $data, TRUE);
                    foreach ($email_add->result() as $row) {
                        $this->send_direct_content($row->user_id, $date, $bodyContent, $email_subject, $module);
                    };
                }
                echo json_encode(array(
                    'status' => true,
                    'message' => 'looping done',
                    'action' => 'update_scheduler',
                ));
            } else {
                echo json_encode(array(
                    'status' => true,
                    'message' => 'No New Alert',
                    'action' => 'update_scheduler',
                ));
            }
        } // end alert_notification
        else {
            echo json_encode(array(
                'status' => FALSE,
                'message' => 'Error Module',
                'action' => 'retry',
            ));
        }
    }
}
