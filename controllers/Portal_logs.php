<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal_logs extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('session');
        $this->load->library('Panda_PHPMailer'); 
    }


    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");

            $get_user = $this->db->query("SELECT a.user_guid, a.user_id, a.user_name FROM lite_b2b.set_user a WHERE a.isactive = '1' GROUP BY a.user_guid");

            $get_table = $this->db->query("SELECT a.guid,a.log_table FROM lite_b2b.set_logs_query a WHERE a.`isactive` = '1' ORDER BY a.created_at ASC");

            $get_period = $this->db->query("SELECT aa.* FROM ( SELECT period_code FROM b2b_invoice.supplier_monthly_doc_count GROUP BY period_code UNION ALL SELECT LEFT(DATE_ADD(CURDATE(),INTERVAL - 1 MONTH),7) UNION ALL SELECT LEFT(CURDATE(),7) ) aa GROUP BY aa.period_code ORDER BY aa.period_code DESC");

            $data = array(
                'get_supplier' => $get_supplier->result(),
                'get_acc' => $get_acc->result(),
                'get_user' => $get_user->result(),
                'get_table' => $get_table->result(),
                'get_period' => $get_period->result(),
            );

            $this->load->view('header');
            $this->load->view('b2b_log/log_list', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function run_log_table()
    {

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        // b as acc table
        // c as user table
        // d as supplier table
        $customer_guid = isset($_GET['retailer_option']) ? $this->input->get('retailer_option') : $this->input->post('retailer_option');
        $table_option = isset($_GET['table_option']) ? $this->input->get('table_option') : $this->input->post('table_option');
        $retailer_option = isset($_GET['retailer_option']) ? $this->input->get('retailer_option') : $this->input->post('retailer_option');
        $supplier_option = isset($_GET['supplier_option']) ? $this->input->get('supplier_option') : $this->input->post('supplier_option');
        $user_option = isset($_GET['user_option']) ? $this->input->get('user_option') : $this->input->post('user_option');
        $date_from = isset($_GET['date_from']) ? $this->input->get('date_from') : $this->input->post('date_from');
        $date_to = isset($_GET['date_to']) ? $this->input->get('date_to') : $this->input->post('date_to');
        $period_from = isset($_GET['period_code_from']) ? $this->input->get('period_code_from') : $this->input->post('period_code_from');
        $period_to = isset($_GET['period_code_to']) ? $this->input->get('period_code_to') : $this->input->post('period_code_to');
        $period_code = isset($_GET['period_code']) ? $this->input->get('period_code') : $this->input->post('period_code');
        $period_explode = explode("-", $period_code);
        $period_year = $period_explode[0];
        $period_start_date = date('Y-m-d', strtotime($period_code));
        $get_query = $this->db->query("SELECT log_table,log_script,check_retailer,check_supplier,check_user,check_date,replace_customer_guid FROM lite_b2b.set_logs_query WHERE isactive = '1' AND `guid` = '$table_option' ");
        $get_table_sql = $get_query->row('log_script');
        $check_retailer = $get_query->row('check_retailer');
        $check_supplier = $get_query->row('check_supplier');
        $check_user = $get_query->row('check_user');
        $check_date = $get_query->row('check_date');
        $replace_customer_guid = $get_query->row('replace_customer_guid');
        $condition_where = '';
        $condition_and = ' AND ';
        $condition_retailer = '';
        $condition_supplier = '';
        $condition_user = '';
        $condition_date = '';
        $date_from = date('Y-m-d H:i:s', strtotime($_REQUEST['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($_REQUEST['date_to']));

        $db = $this->db->query("SELECT a.compare_database,a.b2b_database FROM lite_b2b.acc AS a WHERE a.acc_guid = '$customer_guid'");
        $db_backend = $db->row('compare_database');
    
        $db_consignment = $db->row('b2b_database');
        if ($db_consignment == '' || $db_consignment == null) {
            $db_consignment = 'r_tf_backend';
        }

        if($table_option == '10598C1C648711EDB862000D3AA2838A' || $table_option == '067F6ECA654E11EDA237000D3AA2838A' || $table_option == '10598C1C648711EDB862000D3AA2838B' || $table_option == '067F6ECA654E11EDA237000D3AA2838B') 
        {
          //print_r($period_code); die;
          //$inv_date = $this->input->post('inv_date');
      
          $loc_group_in = '';
          $locgroup_in = '';
          $inv_date = date('Y-m-d', strtotime("+1 month -1Day", strtotime($period_code)));
      
          $date_trans_consign = "LEFT(DATE_ADD('$inv_date', INTERVAL - 1 MONTH), 7)";
          $date_trans_curdate = date('Y-m-d', strtotime("+1 month -3Day", strtotime($inv_date)));

          // $db_supplier_monthly_main = 'supplier_monthly_main';
          // $db_supplier_monthly_child = 'supplier_monthly_child';
          $db_supplier_monthly_main = 'supplier_monthly_main_staging';
          $db_supplier_monthly_child = 'supplier_monthly_child_staging';
          // ninso
          if ($customer_guid == '599348EDCB2F11EA9A81000C29C6CEB2') {
            $loc_group_in = "AND loc_group IN ('NKWH','NJWH')";
            $locgroup_in = "AND locgroup IN ('NKWH','NJWH')";
          }
      
          // if select period == current month select b2b_invoice staging table
          if (date('Y-m', strtotime($period_code)) == date('Y-m')) {
            $db_supplier_monthly_main = 'supplier_monthly_main_staging';
            $db_supplier_monthly_child = 'supplier_monthly_child_staging';
          }
    
          $current_set_variable = ['@customer_guid', '@period_code', '@date_trans_consign', '@date_trans_curdate', '@db_backend', '@loc_group_in', '@locgroup_in', '@db_supplier_monthly_main', '@db_supplier_monthly_child', '@db_consignment'];
      
          $replace_set_variable = ["'$customer_guid'", "'$period_code'", "$date_trans_consign", "'$date_trans_curdate'", "$db_backend", "$loc_group_in", "$locgroup_in", "$db_supplier_monthly_main", "$db_supplier_monthly_child", "$db_consignment"];

          $sql = str_replace($current_set_variable, $replace_set_variable, $get_table_sql);
    
          //echo $this->db->last_query(); die;
        }
        else if($table_option == '9ADCC86479CB11ED9DE26045BD209184' || $table_option == 'B24C52DD79CB11ED9DE26045BD209184') 
        {
          //print_r($period_code); die;
          //$inv_date = $this->input->post('inv_date');
      
          $loc_group_in = '';
          $locgroup_in = '';
          $inv_date = date('Y-m-d', strtotime("+1 month -1Day", strtotime($period_code)));
          //print_r($inv_data); die;
          $date_trans_consign = "LEFT(DATE_ADD('$inv_date', INTERVAL - 1 MONTH), 7)";
          //$date_trans_curdate = date('Y-m-d', strtotime("+1 month -3Day", strtotime($inv_date)));

          $check_date = $this->db->query("SELECT DATE_FORMAT(NOW(), '%d') AS now_time")->row('now_time');

          if($check_date == '1')
          {
            $date_trans_from_curdate = $this->db->query("SELECT DATE_FORMAT(LAST_DAY(DATE_ADD(NOW(), INTERVAL - 1 MONTH)),'%Y-%m-%d 00:00:00') AS now_time")->row('now_time');
            $date_trans_curdate = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(NOW(),INTERVAL - 1 DAY), '%Y-%m-%d 23:59:59') AS now_time")->row('now_time');
          }
          else
          {
            $date_trans_from_curdate = $this->db->query("SELECT DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00') AS now_time")->row('now_time');
            $date_trans_curdate = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(NOW(),INTERVAL - 1 DAY), '%Y-%m-%d 23:59:59') AS now_time")->row('now_time');
          }

          //$date_trans_from_curdate = $this->db->query("SELECT DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00') AS now_time")->row('now_time');
          //$date_trans_curdate = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(NOW(),INTERVAL - 1 DAY), '%Y-%m-%d 23:59:59') AS now_time")->row('now_time');
          // $db_supplier_monthly_main = 'supplier_monthly_main';
          // $db_supplier_monthly_child = 'supplier_monthly_child';
          $db_supplier_monthly_main = 'supplier_monthly_main_staging';
          $db_supplier_monthly_child = 'supplier_monthly_child_staging';
          // ninso
          if ($customer_guid == '599348EDCB2F11EA9A81000C29C6CEB2') {
            $loc_group_in = "AND loc_group IN ('NKWH','NJWH')";
            $locgroup_in = "AND locgroup IN ('NKWH','NJWH')";
          }
      
          // if select period == current month select b2b_invoice staging table
          if (date('Y-m', strtotime($period_code)) == date('Y-m')) {
            $db_supplier_monthly_main = 'supplier_monthly_main_staging';
            $db_supplier_monthly_child = 'supplier_monthly_child_staging';
          }
    
          $current_set_variable = ['@customer_guid', '@period_code', '@date_trans_consign', '@date_trans_curdate', '@db_backend', '@loc_group_in', '@locgroup_in', '@db_supplier_monthly_main', '@db_supplier_monthly_child', '@db_consignment','@date_trans_from_curdate'];
      
          $replace_set_variable = ["'$customer_guid'", "'$period_code'", "$date_trans_consign", "'$date_trans_curdate'", "$db_backend", "$loc_group_in", "$locgroup_in", "$db_supplier_monthly_main", "$db_supplier_monthly_child", "$db_consignment","'$date_trans_from_curdate'"];
      
          $sql = str_replace($current_set_variable, $replace_set_variable, $get_table_sql);
          //print_r($sql);die;
          //echo $this->db->last_query(); die;
        }
        else if ($table_option == '7356B9388FBB11ED9DE26045BD209184')
        {
            $customer_guid = $this->input->post('retailer_option');
            $period_code = $this->input->post('period_code');
            $get_period_code = $this->db->query("SELECT `period_code` FROM lite_b2b.`list_period_code` WHERE period_code != LEFT(CURDATE(),7) ORDER BY period_code DESC LIMIT 3 ")->result_array();
            $get_customer = $this->db->query("SELECT acc_guid FROM lite_b2b.acc WHERE isactive = '1' AND trial_mode = '0'")->result_array();
            $condition_period = '';

            if($period_code == '')
            {
                foreach($get_period_code as $key => $value)
                {
                    $period_code .= "'".implode(',',$value)."',";
                }
    
                $period_code = rtrim($period_code,',');
            }
            else
            {
                $period_code = "'". $period_code . "'";
            }

            if($customer_guid == '')
            {
                foreach($get_customer as $key => $value)
                {
                    $customer_guid .= "'".implode(',',$value)."',";
                }
    
                $customer_guid = rtrim($customer_guid,',');
            }
            else
            {
                $customer_guid = "'". $customer_guid . "'";
            }

            $condition_period = "AND bb.period_code IN ($period_code)";
            $condition_guid = "AND aa.customer_guid IN ($customer_guid)";

            $current_set_variable = ['@condition_guid', '@condition_period'];
      
            $replace_set_variable = ["$condition_guid", "$condition_period"];
        
            $sql = str_replace($current_set_variable, $replace_set_variable, $get_table_sql);

        }
        else if($table_option == '7B1E0DADD4F911ED99CF6045BD209184')
        {

            $loc_group_in = '';
            $locgroup_in = '';
            //$inv_date = date('Y-m-d', strtotime("+1 month -1Day", strtotime($period_code)));
            $date_from = date('Y-m-d H:i:s', strtotime($_REQUEST['date_from']));
            $date_to = date('Y-m-d 23:59:59', strtotime($_REQUEST['date_to']));
            $check_date = $this->db->query("SELECT DATE_FORMAT(NOW(), '%d') AS now_time")->row('now_time');

            if($date_from == '1970-01-01 08:00:00')
            {
                if($check_date == '1')
                {
                    $date_trans_from_curdate = $this->db->query("SELECT DATE_FORMAT(LAST_DAY(DATE_ADD(NOW(), INTERVAL - 1 MONTH)),'%Y-%m-%d 00:00:00') AS now_time")->row('now_time');
                }
                else
                {
                    $date_trans_from_curdate = $this->db->query("SELECT DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00') AS now_time")->row('now_time');
                }
                //$date_trans_from_curdate = $this->db->query("SELECT DATE_FORMAT(NOW(), '%Y-%m-01 00:00:00') AS now_time")->row('now_time');
            }
            else
            {
                $date_trans_from_curdate = $date_from;
            }

            if($date_to == '1970-01-01 23:59:59')
            {
                $date_trans_curdate = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(NOW(),INTERVAL - 1 DAY), '%Y-%m-%d 23:59:59') AS now_time")->row('now_time');
            }
            else
            {
                $date_trans_curdate = $date_to;
            }

            //$date_trans_consign = "LEFT(DATE_ADD('$inv_date', INTERVAL - 1 MONTH), 7)";
            //print_r($inv_data); die;
            $current_set_variable = ['@customer_guid', '@date_trans_curdate', '@compare_db','@date_trans_from_curdate'];
            $replace_set_variable = ["'$customer_guid'", "'$date_trans_curdate'", "$db_backend","'$date_trans_from_curdate'"];
            //print_r($replace_set_variable); die;
            $sql = str_replace($current_set_variable, $replace_set_variable, $get_table_sql);
            //print_r($sql);die;
            //echo $this->db->last_query(); die;
        }
        else if($table_option == 'G28DA61316D411EDBBD7B2C55218A002')
        {

            $ts1 = strtotime($period_from);
            $ts2 = strtotime($period_to);

            $year1 = date('Y', $ts1);
            $year2 = date('Y', $ts2);

            $month1 = date('m', $ts1);
            $month2 = date('m', $ts2);

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            $diffs = $diff + 1;

            $period_range_sql = '';
            $period_range_implode = array();

            $period_range_implode[] .= "ROUND(SUM(aa.doc_count)/$diffs,2) AS 'Avg Doc Count'";
            $period_range_implode[] .= "ROUND(SUM(aa.po_amt)/$diffs,2) AS 'Avg PO Amt'";
            $period_range_implode[] .= "ROUND(SUM(aa.gr_amt)/$diffs,2) AS 'Avg GRN Amt'";
            $period_range_implode[] .= "ROUND(SUM(aa.pdn_amt)/$diffs,2) AS 'Avg PRDN Amt'";

            for ($i = 0; $i <= $diff; $i++) {

                $date_info = $this->db->query("SELECT ('$period_to-01' - INTERVAL $i MONTH) AS `current_date`, YEAR('$period_to-01' - INTERVAL $i MONTH) AS current_year, DATE_FORMAT('$period_to-01' - INTERVAL $i MONTH, '%m') AS current_month, DATE_FORMAT('$period_to-01' - INTERVAL $i MONTH, '%b') AS month_name");
                $current_year = $date_info->row('current_year');
                $current_month = $date_info->row('current_month');
                $month_name = $date_info->row('month_name');

                $period_range_implode[] .= "SUM(IF(aa.`period_code` = CONCAT('$current_year', '-', '$current_month'),aa.doc_count,0)) AS '$current_year-$month_name Doc Count'";
                $period_range_implode[] .= "SUM(IF(aa.`period_code` = CONCAT('$current_year', '-', '$current_month'),aa.po_nocost_count,0)) AS '$current_year-$month_name PO reject/cancel/hide Count'";
                $period_range_implode[] .= "SUM(IF(aa.`period_code` = CONCAT('$current_year', '-', '$current_month'),aa.other_doc_count,0)) AS '$current_year-$month_name Other Doc Count'";
                $period_range_implode[] .= "SUM(IF(aa.`period_code` = CONCAT('$current_year', '-', '$current_month'),aa.po_amt,0)) AS '$current_year-$month_name PO Amt'";
                $period_range_implode[] .= "SUM(IF(aa.`period_code` = CONCAT('$current_year', '-', '$current_month'),aa.gr_amt,0)) AS '$current_year-$month_name GRN Amt'";
                $period_range_implode[] .= "SUM(IF(aa.`period_code` = CONCAT('$current_year', '-', '$current_month'),aa.pdn_amt,0)) AS '$current_year-$month_name PRDN Amt'";
            }

            if ($period_range_implode) {
                $period_range_sql .= " " . implode(", ", $period_range_implode) . "";
            }
            
            $current_set_variable = ['@customer_guid', '@period_from', '@period_to', '@select_period_range'];
        
            $replace_set_variable = ["'$customer_guid'", "'$period_from'", "'$period_to'", "$period_range_sql"];

            $sql = str_replace($current_set_variable, $replace_set_variable, $get_table_sql);
        }
        else if($replace_customer_guid == '1')
        {
            $current_set_variable = ['@customer_guid'];
        
            $replace_set_variable = ["'$customer_guid'"];
            //print_r($replace_set_variable); die;
            $sql = str_replace($current_set_variable, $replace_set_variable, $get_table_sql);
        }
        else
        {
            if($check_retailer == '1')
            {
                if($retailer_option != '')
                {
                    $condition_retailer = "b.acc_guid = '$retailer_option'"; 
                }
            }

            if($check_supplier == '1')
            {
                if($supplier_option != '')
                {
                    $condition_supplier = "d.supplier_guid = '$supplier_option'"; 
                }
            }

            if($check_user == '1')
            {
                if($user_option != '')
                {
                    $condition_user = "a.user_guid = '$user_option'"; 
                }
            }

            if($check_date == '1')
            {
                if($date_from != '' && $date_to != '')
                {
                    if($date_from != '1970-01-01 08:00:00' && $date_to != '1970-01-01 23:59:59')
                    {
                        $condition_date = "a.created_at BETWEEN '$date_from' AND '$date_to'"; 
                    }
                }
            }

            if($condition_retailer != '' || $condition_supplier != '' || $condition_user != '' || $condition_date != '')
            {
                $condition_where = 'WHERE ';
            }
            
            if($condition_retailer != '' && $condition_supplier != '' && $condition_user != '')
            {
                $condition_combine = $condition_where.$condition_retailer.$condition_and.$condition_supplier.$condition_and.$condition_user;
            }
            else if($condition_retailer != '' && $condition_supplier == '' && $condition_user == '')
            {
                $condition_combine = $condition_where.$condition_retailer;
            }
            else if($condition_retailer != '' && $condition_supplier != '' && $condition_user == '')
            {
                $condition_combine = $condition_where.$condition_retailer.$condition_and.$condition_supplier;
            }
            else if($condition_retailer != '' && $condition_supplier == '' && $condition_user != '')
            {
                $condition_combine = $condition_where.$condition_retailer.$condition_and.$condition_user;
            }
            else if($condition_retailer == '' && $condition_supplier != '' && $condition_user != '')
            {
                $condition_combine = $condition_where.$condition_supplier.$condition_and.$condition_user;
            }
            else if($condition_retailer == '' && $condition_supplier != '' && $condition_user == '')
            {
                $condition_combine = $condition_where.$condition_supplier;
            }
            else if($condition_retailer == '' && $condition_supplier == '' && $condition_user != '')
            {
                $condition_combine = $condition_where.$condition_user;
            }
            else
            {
                $condition_combine = '';
            }

            if($condition_combine != '')
            {
                if($condition_date != '')
                {
                    $condition_combine = $condition_combine.$condition_and.$condition_date;
                }
            }
            else
            {
                if($condition_date != '')
                {
                    $condition_combine = $condition_where.$condition_date;
                }
            }

            //print_r($condition_combine); die;
            $current_set_variable = ['@condition', '@customer_guid', '@db_consignment', '@period_code', '@period_from', '@period_to', '@period_year', '@period_start_date'];
    
            $replace_set_variable = [ "$condition_combine", "'$customer_guid'", "$db_consignment", "'$period_code'", "'$period_from'", "'$period_to'", "'$period_year'", "'$period_start_date'"];
        
            $sql = str_replace($current_set_variable, $replace_set_variable, $get_table_sql);
            //echo $sql; die;
        }

        // echo $sql; die;
        if(isset($_GET['download_excel'])){
            
            $objPHPExcel = $this->download_excel($sql);

            $today = date("Y-m-d");

            // $filename = $today.'.XLSX'; //save our workbook as this file name
            $fileName = $get_query->row('log_table').$today.'.xlsx';
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$fileName.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            header("Pragma: no-cache");
            header("Expires: 0");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            ob_end_clean();

            $objWriter->save('php://output'); die;
        }

        // echo $sql; die;
        $sql = $this->db->query("$sql");

        if(count($sql->result_array()) > 0)
        {
            $csv_header = array_keys($sql->result_array()[0]);
            $no_data = 'True';
        }
        else
        {
            $no_data = 'False';
        }


        $data = array(
            "para" => $no_data,
            "sql" => $sql->result(),
            "csv_header" => $csv_header,
        );
        echo json_encode($data);
    }

    public function download_excel($sql)
    {
        ini_set('max_execution_time', 0); 

        $q_result = $this->db->query($sql)->result_array();

        $this->load->library('excel');
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

    public function fetch_user()
    {
      $supplier_data = $this->input->post('supplier_data');
      $retailer_data = $this->input->post('retailer_data');

      $vendor = $this->db->query("SELECT b.user_guid , b.user_name , b.user_id FROM lite_b2b.set_supplier_user_relationship a 
      INNER JOIN lite_b2b.set_user b 
      ON a.user_guid = b.user_guid
      WHERE a.customer_guid = '$retailer_data'
      AND a.supplier_guid = '$supplier_data'
      GROUP BY a.user_guid
      ORDER BY b.user_name ASC");
  
      $data = array(
        'vendor' => $vendor->result(),
      );
  
      echo json_encode($data);
    }

    public function user_action_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");

            $get_user = $this->db->query("SELECT a.user_guid, a.user_id, a.user_name FROM lite_b2b.set_user a WHERE a.isactive = '1' GROUP BY a.user_guid");

            $get_table = $this->db->query("SELECT a.guid,a.log_table FROM lite_b2b.set_logs_query a WHERE a.`isactive` = '1' ORDER BY a.created_at ASC");

            $data = array(
                'get_supplier' => $get_supplier->result(),
                'get_acc' => $get_acc->result(),
                'get_user' => $get_user->result(),
                'get_table' => $get_table->result(),
            );

            $this->load->view('header');
            $this->load->view('b2b_log/user_log_list', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function user_action_tb()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0); 

      $retailer_option = $this->input->post('retailer_option');
      $supplier_option = $this->input->post('supplier_option');
      $user_option = $this->input->post('user_option');
      $implode_data = "'" . implode("','",$user_option) . "'";

      $condtion_supplier = '';
      $condtion_user = '';

      if($supplier_option != '')
      {
        $condtion_supplier = "AND f.supplier_guid = '$supplier_option'";
      }

      if($implode_data != '')
      {
        $condtion_user = "AND b.ven_email IN ($implode_data)";
      }


      $query_data = $this->db->query("SELECT e.acc_name,a.register_guid,a.comp_name,b.ven_email,IF(c.id IS NULL, 'Not login web', 'Access') AS status_web,IF(c.id IS NULL, 'Not login apps', 'Access') AS status_apps,b.ven_phone,a.customer_guid,a.supplier_guid
      FROM lite_b2b.register_new AS a
      INNER JOIN lite_b2b.register_child_new AS b
      ON a.register_guid = b.register_guid
      AND b.part_type = 'registration'
      LEFT JOIN lite_b2b.user_logs AS c
      ON b.ven_email = c.id
      LEFT JOIN lite_b2b_apps.user_logs AS d
      ON b.ven_email = d.id
      INNER JOIN lite_b2b.acc e
      ON a.customer_guid = e.acc_guid
      INNER JOIN lite_b2b.set_supplier f
      ON a.supplier_guid = f.supplier_guid
      WHERE a.customer_guid = '$retailer_option'
      AND a.form_status = 'Registered'
      $condtion_supplier
      $condtion_user
      GROUP BY b.ven_email");

      $data = array(  
        'query_data' => $query_data->result(),
      );
  
      echo json_encode($data); 
    }

    public function fetch_reg_user()
    {
      $supplier_data = $this->input->post('supplier_data');
      $retailer_data = $this->input->post('retailer_data');

      $fetch_user = $this->db->query("SELECT b.ven_name,b.ven_email FROM lite_b2b.register_new a
      INNER JOIN lite_b2b.register_child_new b
      ON a.register_guid = b.register_guid
      AND b.part_type = 'registration'
      WHERE a.customer_guid = '$retailer_data'
      AND b.supplier_guid = '$supplier_data';");
  
      $data = array(
        'fetch_user' => $fetch_user->result(),
      );
  
      echo json_encode($data);
    }

    public function send_reminder()
    {
        $mail_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS mail_guid")->row('mail_guid');
        $customer_guid = $this->input->post('customer_guid');
        $supplier_guid = $this->input->post('supplier_guid');
        $email_address = $this->input->post('email_address');
        $mail_type = $this->input->post('mail_type');
        $reply_to = 'support@xbridge.my';
        // $to_email = [];
        // $cc_email_list = '';
        // $temp = [];
        // $filename = '';
        // $attachment_file_path = '';
        // print_r($template_guid); echo '<br>';
        // print_r($add_new_email); die;
        
        //$get_email_template_info = $this->lite_b2b_model->get_email_template($description, $type, $mail_type);
    
        $get_header = $this->db->query("SELECT a.value AS body_header FROM lite_b2b.config a WHERE a.type = 'header_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EHTMP' LIMIT 1")->row('body_header');
    
        $get_footer = $this->db->query("SELECT a.value AS body_footer FROM lite_b2b.config a WHERE a.type = 'footer_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EFTMP' LIMIT 1")->row('body_footer');
    
        $get_email_template_info = $this->db->query("SELECT a.`mail_subject`,a.`body_content`
            FROM lite_b2b.`email_template` AS a
            WHERE a.`template_guid` = '$template_guid'
            AND a.`is_active` = '1'
            LIMIT 1");
    
        //$get_email_list = $this->lite_b2b_model->get_email_user_list($supplier_guid, $customer_guid)->result_array();
    
        if($email_group != '')
        {
            $get_email_list = $this->db->query("SELECT a.`type`, a.`email_group_name`, a.`description`, b.`category`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, b.`user_email`, b.`user_email`, b.`email_name`,b.cc_email FROM lite_b2b.`set_email` AS a INNER JOIN lite_b2b.`set_email_group` AS b ON a.`guid` = b.`email_group_guid` WHERE a.guid = '$email_group' AND b.`is_active` = '1'")->result_array();
    
            // send email by each
            foreach ($get_email_list as $key => $value) {
            $to_email[] = $value['user_email'];
    
            $cc_email_list =  explode(',',$value['cc_email']);
            }
            if($add_new_email != '')
            {
            $to_email = array_merge( $to_email , $add_new_email );
            }
        }
        else 
        {
            $add_new_email = implode(',', $add_new_email);
            $to_email = array_unique(explode(',', $add_new_email));
        }
    
        // print_r($to_email);
        // print_r($cc_email_list);
        // die;
        if ($to_email == '' || $to_email == 'null' || $to_email == null || $to_email == []) {
            $data = array(
            "para1" => '1',
            "msg" => 'Invalid To Email Address',
            );
            echo json_encode($data);
            exit();
        }
    
        //$replace_key = ['%current_date%', '%retailer%', '%filename%', '%status%', '%reason%'];
        //$replace_value = [date('Y-m-d'), $retailer_name, $filename, 'Invalid', $description];
        //$content = str_replace($replace_key, $replace_value, $get_email_template_info->row('body_content'));
        $content =  $get_email_template_info->row('body_content');
    
        foreach ($to_email as $row => $value) {
            
            // print_r($value);
            // die;
            $email_address = $value;
            // send email to user
    
            $this->Send_email_model->send_mailjet_third_party(
            $email_address,
            '',
            $get_header . $content . $get_footer,
            $get_email_template_info->row('mail_subject'),
            '',
            $cc_email_list,
            $attachment_file_path,
            $reply_to,
            $filename
            );
    
            $this->mail_to_log(
            $acc_guid,
            $mail_guid,
            $template_guid,
            $email_address,
            $get_header . $content. $get_footer,
            $get_email_template_info->row('mail_subject'),
            $cc_email_list,
            $reply_to,
            $attachment_file_path,
            $filename
            );
        }
    
        $data = array(
            "para1" => 'true',
            "msg" => 'Send Successfully'
        );
    
        echo json_encode($data);
    }
}
?>

