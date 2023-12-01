<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consignment extends CI_Controller {
    
    function __construct()
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
        $this->general_internal_ip = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'), 'web', 'general_doc', 'general_internal_ip', 'GIP');
        $this->api_url = $this->general_internal_ip . '/rest_b2b/index.php/';
    }

    public function index()
    {
        $customer_guid = $_SESSION['customer_guid'];

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $check_table = $this->db->query("SELECT COUNT(*) AS result FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'lite_b2b' AND TABLE_NAME = 'consign_statement_email'")->row('result');

            if($check_table == '0'){
                $this->db->query("CREATE TABLE `consign_statement_email` (
                    `guid` varchar(32) NOT NULL,
                    `customer_guid` varchar(32) DEFAULT NULL,
                    `email` varchar(100) DEFAULT NULL,
                    `effective_date` varchar(100) DEFAULT NULL,
                    `statement_date` varchar(100) DEFAULT NULL,
                    `issend` smallint(6) DEFAULT '0',
                    `status` varchar(32) DEFAULT NULL,
                    `response` text,
                    `send_at` datetime DEFAULT NULL,
                    PRIMARY KEY (`guid`),
                    KEY `customer_guid` (`customer_guid`),
                    KEY `email` (`email`),
                    KEY `effective_date` (`effective_date`),
                    KEY `statement_date` (`statement_date`),
                    KEY `issend` (`issend`),
                    KEY `status` (`status`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8
                  
                ;");
            }

            $check_table_consign = $this->db->query("SELECT COUNT(*) AS result FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'lite_b2b' AND TABLE_NAME = 'consign_email_setup'")->row('result');

            if($check_table_consign == '0'){
                $this->db->query("CREATE TABLE `consign_email_setup` (
                    `guid` varchar(32) NOT NULL,
                    `customer_guid` varchar(32) DEFAULT NULL,
                    `consignment_email_name` varchar(120) DEFAULT NULL COMMENT 'This name will be displayed at the consignment email body',
                    `email_subject` varchar(120) DEFAULT NULL,
                    PRIMARY KEY (`guid`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8                  
                ;");
            }

            if($_SESSION['user_group_name'] == "SUPER_ADMIN"){
                $retailer = $this->db->query("SELECT * FROM acc WHERE isactive = '1'");
            }else{
                $retailer = $this->db->query("SELECT * FROM acc WHERE isactive = '1' AND acc_guid = '$customer_guid'");
            }

            $data = array(
                'retailer' => $retailer,
            );

            $this->load->view('header');
            $this->load->view('Consignment/process', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function view_log()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && in_array('IAVA',$_SESSION['module_code']))
        { 

            $retailer = $this->db->query("SELECT * FROM lite_b2b.acc WHERE isactive = '1'");
            $process = $this->db->query("SELECT process FROM lite_b2b.consign_process_log GROUP BY process");

            $data = array(
                'retailer' => $retailer,
                'process' => $process,
            );

            $this->load->view('header');
            $this->load->view('Consignment/view_log', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function view_log_list()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0); 
  
      $retailer = $this->input->post('retailer');
      $process = $this->input->post('process');
      $user_id = $this->input->post('user_id');
      
      $sql = "SELECT 
            *
        FROM 
            `lite_b2b`.`consign_process_log` log
        INNER JOIN 
            `lite_b2b`.`acc` a
            ON log.`customer_guid` = a.`acc_guid`
        WHERE
            1 = 1";
        
        if($retailer != '' || $retailer != null)
        {
            $sql .= " AND log.`customer_guid` = '$retailer'";
        }

        if($process != '' || $process != null)
        {
            $sql .= " AND log.`process` = '$process'";
        }

        if($user_id != '' || $user_id != null)
        {
            $sql .= " AND log.user_id LIKE '%". $user_id ."%'";
        }

        $sql .= " ORDER BY date_added DESC";

        $query_data = $this->db->query($sql);

      $data = array(  
        'query_data' => $query_data->result(),
      );
  
      echo json_encode($data); 
    }

    public function check_email()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && in_array('IAVA',$_SESSION['module_code']))
        { 

            $retailer = $this->db->query("SELECT * FROM lite_b2b.acc WHERE isactive = '1'");
            // $effective_date = $this->db->query("SELECT effective_date FROM lite_b2b.consign_statement_email GROUP BY effective_date ORDER by effective_date DESC");
            // $statement_date = $this->db->query("SELECT statement_date FROM lite_b2b.consign_statement_email GROUP BY statement_date ORDER by statement_date DESC");
            $effective_date = $this->db->query("SELECT JSON_UNQUOTE(JSON_EXTRACT(`json_param`,'$.effective_date')) AS effective_date FROM lite_b2b.blast_email_list WHERE json_param IS NOT NULL AND json_param <> '' GROUP BY effective_date ORDER BY effective_date DESC");
            $statement_date = $this->db->query("SELECT JSON_UNQUOTE(JSON_EXTRACT(`json_param`,'$.statement_date')) AS statement_date FROM lite_b2b.blast_email_list WHERE json_param IS NOT NULL AND json_param <> '' GROUP BY statement_date ORDER by statement_date DESC");

            $data = array(
                'retailer' => $retailer,
                'effective_date' => $effective_date,
                'statement_date' => $statement_date,
            );

            $this->load->view('header');
            $this->load->view('Consignment/check_email', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function check_email_list()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0); 
  
      $retailer = $this->input->post('retailer');
      $effective_date = $this->input->post('effective_date');
      $statement_date = $this->input->post('statement_date');
      $email = $this->input->post('email');
      $status = $this->input->post('status');
      
      $sql = "SELECT 
            *,
            JSON_UNQUOTE(JSON_EXTRACT(bel.`json_param`,'$.effective_date')) AS effective_date,
            JSON_UNQUOTE(JSON_EXTRACT(bel.`json_param`,'$.statement_date')) AS statement_date
        FROM 
            `lite_b2b`.`blast_email_list` bel
        INNER JOIN 
            `lite_b2b`.`acc` a
            ON bel.`customer_guid` = a.`acc_guid`
        WHERE
            email_type LIKE '%consign_email%'";
        
        if($retailer != '' || $retailer != null)
        {
            $sql .= " AND bel.`customer_guid` = '$retailer'";
        }

        if($effective_date != '' || $effective_date != null)
        {
            $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(bel.`json_param`,'$.effective_date')) = '$effective_date'";
        }

        if($statement_date != '' || $statement_date != null)
        {
            $sql .= " AND JSON_UNQUOTE(JSON_EXTRACT(bel.`json_param`,'$.statement_date')) = '$statement_date'";
        }

        if($email != '' || $email != null)
        {
            $sql .= " AND bel.email_add LIKE '%". $email ."%'";
        }

        if($status != '' || $status != null)
        {
            if($status == 'success'){
                $sql .= " AND bel.`status` = '3'";
            }else if($status == 'fail'){
                $sql .= " AND bel.`status` = '99'";
            }else{
                $sql .= " AND bel.`status` NOT IN ('3','99')";
            }
            
        }

        $sql .= " ORDER BY bel.created_at DESC";

        $query_data = $this->db->query($sql);

      $data = array(  
        'query_data' => $query_data->result(),
      );
  
      echo json_encode($data); 
    }
    public function check_hq_variance()
    {

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $retailer_guid = $this->input->post('retailer');
            $date_from = $this->input->post('date_start');
            $date_to = $this->input->post('date_end');
            $selected_supplier = isset($_POST['selectedCheckboxes']) ? $this->input->post('selectedCheckboxes') : array();
            $doublecheck = isset($_POST['doublecheck']) ? $this->input->post('doublecheck') : false;

            // $retailer_guid = '610BB0EA76AE11EDB37C72B64FC54D79';
            // $date_from = '2023-02-01';
            // $date_to = '2023-02-28';

            $acc_info = $this->db->query("SELECT b2b_database, b2b_hub_database, public_ip FROM lite_b2b.acc WHERE acc_guid = '$retailer_guid'");
            $hq_url = rtrim($acc_info->row('public_ip'), '/');
            // $hq_url = preg_replace('/\/$/', '', $hq_url);
            $backend_db = $acc_info->row('b2b_database');
            $hub_db = $acc_info->row('b2b_hub_database');
    
            $curl = curl_init();

            curl_setopt_array($curl, array(
            //CURLOPT_URL => "https://crm103.panda-eco.com/panda/restful_member/index.php/Api/signup_new",
            CURLOPT_URL => $hq_url.'/lite_panda_b2b_checking_rest/index.php/Consignment_process?date_from='.$date_from.'&date_to='.$date_to,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            // $test = json_decode($response, true);
            // echo '<pre>';
            // print_r($test);
            // echo '</pre>'; die;

            $temp_result1 = array();
            $temp_result2 = array();
            $temp_missing_b2b = array();
            $result_array = json_decode($response,true);

            foreach($result_array['query_data'] as $result){

                $result['missing_b2b'] = '';

                if($result['trans_guid'] == ''){
                    $diff_supplier[] = $result['CODE']; 
                    $temp_result1[] = $result;
                }else{

                    $trans_guid    = $this->db->escape($result['trans_guid']);

                    $trans_guid_array = explode(',', $trans_guid);

                    foreach($trans_guid_array as $trans_guid){
                        $trans_guid = trim($trans_guid, "'");
                        $check_hub = $this->db->query("SELECT COUNT(*) AS cnt FROM $hub_db.`acc_trans` WHERE `trans_guid` = '$trans_guid'")->row('cnt');

                        if($check_hub == 0){
                            $temp_missing_b2b[] = $trans_guid;
                        }
                    }

                    $result['missing_b2b'] = implode(',', $temp_missing_b2b);

                    $quoted_trans_guid_array = array_map(function($refno) {
                        return "'" . $refno . "'";
                    }, $trans_guid_array);

                    $trans_guids = implode(', ', $quoted_trans_guid_array);
                    $trans_guids = trim($trans_guids, "'");

                    $check_exist = $this->db->query("SELECT * FROM $backend_db.`acc_trans` WHERE `trans_guid` IN ('$trans_guids')")->result_array();

                    if(sizeof($check_exist) == 0){

                        $temp_result2[] = $result;

                    }
                }

            }

            $temp_result = array(
                'query_data' => array_merge($temp_result1,$temp_result2),
            );

            // print("<pre>".print_r($temp_result,true)."</pre>"); die;

            $response = json_encode($temp_result);

            if($doublecheck == true){
                
                foreach($selected_supplier as $supplier){
                    $supplier_detail = explode('|',$supplier);
                    $supplier_code = $supplier_detail[1];

                    if(in_array($supplier_code, $diff_supplier)){
                        $response['query_data'] = '';
                        break;
                    }
                }

            }else{

                $log_data = array(
                    'process'       => 'Check HQ Variance',
                    'customer_guid' => $retailer_guid,
                    'user_id'       => $this->session->userdata('userid'),
                    'post_url'      => $hq_url.'/lite_panda_b2b_checking_rest/index.php/Consignment_process?date_from='.$date_from.'&date_to='.$date_to,
                    'response'      => stripslashes($response),
                );
    
                $this->insert_consign_log($log_data);

            }

            echo stripslashes($response); 
        }
    }

    public function run_process()
    {
        // nabil hardcode
        // $result = array(
        //     'status' => 1,
        //     'message' => 'Success',
        // );
        // echo json_encode($result); die;
        
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $user_guid = $this->session->userdata('user_guid');
            $retailer_guid = $this->input->post('retailer');
            $date_from = $this->input->post('date_start');
            $date_to = $this->input->post('date_end');
            $selected_supplier = $this->input->post('selectedCheckboxes');
            
            $log_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS UUID")->row('UUID');

            $log_data = array(
                'log_guid'      => $log_guid,
                'process'       => 'Release Consign',
                'customer_guid' => $retailer_guid,
                'user_id'       => $this->session->userdata('userid'),
                'response'      => array(
                    'status'            => 'failed',
                    'message'           => 'Failed to release consign due to an error',
                    'invoices_count'    => sizeof($selected_supplier),
                    'success_release'   => 0,
                    'fail_release'      => sizeof($selected_supplier),
                ),
            );

            $this->insert_consign_log($log_data);

            foreach($selected_supplier as $supplier){
                $supplier_detail = explode('|',$supplier);
                $supplier_periodcode = $supplier_detail[0];
                $supplier_code = $supplier_detail[1];

                $supplier_list[] = $supplier_code;
            }

            if(sizeof($supplier_list) > 0){
                $supplier = "'" . implode("', '", $supplier_list) . "'";
            }
 
            $effective_date = date('d-M-Y');
            $statement_date = date("M-Y", strtotime($date_from));

            // $retailer_guid = '610BB0EA76AE11EDB37C72B64FC54D79';
            // $date_from = '2023-02-01';
            // $date_to = '2023-02-28';

            $acc_info = $this->db->query("SELECT acc_name, azure_container_name, b2b_database, b2b_hub_database, public_ip FROM lite_b2b.acc WHERE acc_guid = '$retailer_guid'");
            $backend_db = $acc_info->row('b2b_database');
            $hub_db = $acc_info->row('b2b_hub_database');
            $hq_url = $acc_info->row('public_ip');
            $hq_url = rtrim($hq_url, '/');

            // check consign sales.sql
            $check_variance = $this->db->query("SELECT 
            a.refno,a.operation,ROUND(IFNULL(SUM(b.cost_cs),0),2) AS cost,a.`total_inc_tax`, a.`total_inc_tax` - ROUND(IFNULL(SUM(b.cost_cs),0),2) AS variance_val
            FROM
                $hub_db.`acc_trans` a 
                LEFT JOIN $backend_db.`sku_cs_date` b 
                ON a.refno = b.acc_refno 
            WHERE a.date_trans BETWEEN '$date_from' AND '$date_to'
            GROUP BY a.refno HAVING a.`total_inc_tax` <> IFNULL(cost,0) AND (variance_val <= '-1.00' OR variance_val >= '1.00');")->result_array();

            $refno_array = array();

            if(sizeof($check_variance) > 0){

                foreach($check_variance as $variance){
                    array_push($refno_array, $variance['refno']);
                }
            }

            if(sizeof($refno_array) > 0){
                $refno_list = "'" . implode("', '", $refno_array) . "'";

                $post_date = [
                    'refno' => $refno_list,
                ];

                // reupload_sku_cs_date.sql
                $curl = curl_init();

                curl_setopt_array($curl, array(
                //CURLOPT_URL => "https://crm103.panda-eco.com/panda/restful_member/index.php/Api/signup_new",
                CURLOPT_URL => $hq_url.'/lite_panda_b2b_checking_rest/index.php/Consignment_process/reupload_sku_cs_date?date_from='.$date_from.'&date_to='.$date_to,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_date,
                ));

                $response = curl_exec($curl);

                curl_close($curl);
            }

            // move_to_hub_checking.sql

            // Line 372 - 438 nabil commentted since no use anymore. Hugh stored procedure will flow acc trans data from hub to backend
            // $missing_trans_header = array();
            // $missing_trans_child = array();
            // $this->db->query("UPDATE lite_b2b.acc SET redirect = 1 WHERE acc_guid = '$retailer_guid'");

            // // acc trans header
            // $backend_acc_trans = $this->db->query("SELECT * FROM $backend_db.`acc_trans` WHERE date_trans BETWEEN '$date_from' AND '$date_to';")->result_array();
            // $hub_acc_trans = $this->db->query("SELECT * FROM $hub_db.`acc_trans` WHERE date_trans BETWEEN '$date_from' AND '$date_to' AND operation <> 'DELETE';")->result_array();

            // if(sizeof($backend_acc_trans) != sizeof($hub_acc_trans)){

            //     $check_missing_trans = $this->db->query("SELECT trans_guid, SUM(total_count_backend) AS backend_count, SUM(total_count_hub) AS hub_count, missing_at FROM (SELECT trans_guid, COUNT(*) AS total_count_backend, 0 AS total_count_hub, '$hub_db' AS missing_at FROM $backend_db.`acc_trans` WHERE date_trans BETWEEN '$date_from' AND '$date_to' GROUP BY trans_guid
            //     UNION ALL
            //     SELECT trans_guid, 0 AS total_count_backend, COUNT(*) AS total_count_hub, '$backend_db' AS missing_at FROM $hub_db.`acc_trans` WHERE date_trans BETWEEN '$date_from' AND '$date_to' AND operation <> 'DELETE' GROUP BY trans_guid) a GROUP BY trans_guid HAVING backend_count <> hub_count;")->result_array();

            //     foreach($check_missing_trans AS $row){
            //         $missing_trans_header[$row['missing_at']][] = $row['trans_guid'];
            //     }

            //     $log_data = array(
            //         'process'       => 'Missing Acc Trans (header)',
            //         'customer_guid' => $retailer_guid,
            //         'user_id'       => $this->session->userdata('userid'),
            //         'response'      => json_encode($missing_trans_header),
            //     );
    
            //     $this->insert_consign_log($log_data);

            //     $result = array(
            //         'status' => 0,
            //         'message' => 'Cannot proceed, acc_trans data is not tally',
            //     );
            //     echo json_encode($result); die;

            // }

            // // acc trans child
            // $backend_acc_trans_child = $this->db->query("SELECT * FROM $backend_db.`acc_trans_c2` WHERE bizdate BETWEEN '$date_from' AND '$date_to'")->result_array();
            // $hub_acc_trans_child = $this->db->query("SELECT * FROM $hub_db.`acc_trans_c2` WHERE bizdate BETWEEN '$date_from' AND '$date_to';")->result_array();

            // if(sizeof($backend_acc_trans_child) != sizeof($hub_acc_trans_child)){

            //     $check_missing_trans = $this->db->query("SELECT trans_guid, SUM(total_count_backend) AS backend_count, SUM(total_count_hub) AS hub_count, missing_at FROM (SELECT trans_guid, COUNT(*) AS total_count_backend, 0 AS total_count_hub, '$hub_db' AS missing_at FROM $backend_db.`acc_trans_c2` WHERE bizdate BETWEEN '$date_from' AND '$date_to' GROUP BY trans_guid
            //     UNION ALL
            //     SELECT trans_guid, 0 AS total_count_backend, COUNT(*) AS total_count_hub, '$backend_db' AS missing_at FROM $hub_db.`acc_trans_c2` WHERE bizdate BETWEEN '$date_from' AND '$date_to' AND operation <> 'DELETE' GROUP BY trans_guid) a GROUP BY trans_guid HAVING backend_count <> hub_count;")->result_array();

            //     foreach($check_missing_trans AS $row){
            //         $missing_trans_child[$row['missing_at']][] = $row['trans_guid'];
            //     }

            //     $log_data = array(
            //         'process'       => 'Missing Acc Trans C2 (child)',
            //         'customer_guid' => $retailer_guid,
            //         'user_id'       => $this->session->userdata('userid'),
            //         'response'      => json_encode($missing_trans_child),
            //     );
    
            //     $this->insert_consign_log($log_data);

            //     $result = array(
            //         'status' => 0,
            //         'message' => 'Cannot proceed, acc_trans_c2 data is not tally',
            //     );
            //     echo json_encode($result); die;

            // }

            // $this->db->query("UPDATE lite_b2b.acc SET redirect = 0 WHERE acc_guid = '$retailer_guid'");

            $period_date_trans = date("Y-m", strtotime($date_from));

            $this->db->query("UPDATE $hub_db.`acc_trans` SET date_due = '1980-01-01', sup_doc_date = '1980-01-01', acc_post_date = '1980-01-01' WHERE LEFT(date_trans, 7) = '$period_date_trans'");

            $count_release = 0;

            foreach($selected_supplier as $details){
                $sp_detail = explode("|", $details);
                $this->db->query("CALL SP_acc_trans('$retailer_guid', '$sp_detail[0]', '$sp_detail[1]')");

                $this->db->query("UPDATE $backend_db.`acc_trans` SET unique_key = supcus_code WHERE date_trans BETWEEN '$date_from' AND '$date_to' AND supcus_code = '$sp_detail[1]' AND unique_key IS NULL");

                $count_release++;
            }

            $array_info = array(
                '%retailer_name%'   => $acc_info->row('acc_name'),
                '%short_name%'      => strtoupper($acc_info->row('azure_container_name')),
                '%effective_date%'  => $effective_date,
                '%statement_date%'  => $statement_date,
                'effective_date'    => $effective_date,
                'statement_date'    => $statement_date
            );

            $json_param = stripslashes(json_encode($array_info));

            $get_username = $this->db->query("SELECT user_name FROM lite_b2b.set_user WHERE `user_guid` = '$user_guid' LIMIT 1")->row('user_name');

            $email_template = $this->db->query("SELECT template_guid FROM lite_b2b.email_template WHERE `type` = 'CS' AND mail_type = 'Consign_process' AND is_active = '1' LIMIT 1")->row('template_guid');

            $this->db->query("INSERT IGNORE INTO lite_b2b.blast_email_list
            SELECT 
            REPLACE(UPPER(UUID()),'-','') AS UUID, c.customer_guid, '$email_template', 'consign_email-$statement_date', d.`user_id`, 0, NULL, '$json_param', NOW(),'$get_username', NULL, NULL
            FROM
                $backend_db.`acc_trans` a 
                INNER JOIN lite_b2b.set_supplier_group b 
                ON a.`supcus_code` = b.`supplier_group_name` 
                AND b.`customer_guid` = '$retailer_guid' 
                INNER JOIN lite_b2b.`set_supplier_user_relationship` c 
                ON b.`supplier_group_guid` = c.`supplier_group_guid` 
                AND c.`customer_guid` = '$retailer_guid' 
                INNER JOIN lite_b2b.set_user d 
                ON c.`user_guid` = d.user_guid 
                AND isactive = 1
                AND d.`acc_guid` = '$retailer_guid' 
                AND user_id LIKE '%@%' 
                INNER JOIN $hub_db.`acc_trans_c2` e 
                ON a.trans_guid = e.trans_guid
            WHERE date_trans BETWEEN '$date_from' 
                AND '$date_to' AND
                NOT EXISTS (
                    SELECT * FROM lite_b2b.blast_email_list
                    WHERE customer_guid = c.customer_guid
                    AND template_guid = '$email_template'
                    AND email_type = 'consign_email-$statement_date'
                    AND email_add = d.`user_id`
                )
            GROUP BY d.`user_id`;
            ");

            $result = array(
                'status' => 1,
                'message' => 'Success',
            );

            if(sizeof($selected_supplier) != $count_release){

                $log_response = array(
                    'status'            => 'partial success',
                    'message'           => 'Some of the invoices were not successfully released, You may released again the one that fail.',
                    'invoices_count'    => sizeof($selected_supplier),
                    'success_release'   => $count_release,
                    'fail_release'      => (sizeof($selected_supplier) - $count_release),
                );

            }else{

                $log_response = array(
                    'status'            => 'success',
                    'message'           => 'Successfully release the selected invoices',
                    'invoices_count'    => sizeof($selected_supplier),
                    'success_release'   => $count_release,
                    'fail_release'      => 0,
                );

            }

            $this->db->query("UPDATE lite_b2b.consign_process_log SET `response` = '" . json_encode($log_response) . "' WHERE `guid` = '" . $log_guid ."'");

            echo json_encode($result); die;

        }else{

            $result = array(
                'status' => 0,
                'message' => 'Missing session, please relogin',
            );
            echo json_encode($result); die;
        }
    }

    public function consignment_email_statement(){

        // insert consignment statement email record.sql

        $retailer_guid = $this->input->post('retailer');
        $date_from = $this->input->post('date_start');
        $date_to = $this->input->post('date_end');

        // $retailer_guid = '610BB0EA76AE11EDB37C72B64FC54D79';
        // $date_from = '2023-02-01';
        // $date_to = '2023-02-28';

        $effective_date = date('d-M-Y');
        $statement_date = date("M-Y", strtotime($date_from));

        $email_statement = $this->db->query("SELECT *, JSON_UNQUOTE(JSON_EXTRACT(`json_param`,'$.effective_date')) AS effective_date, JSON_UNQUOTE(JSON_EXTRACT(`json_param`,'$.statement_date')) AS statement_date FROM lite_b2b.blast_email_list WHERE customer_guid = '$retailer_guid' AND email_type = 'consign_email-$statement_date' AND `status` = '0'")->result_array();

        // echo $this->db->last_query(); die;

        $data = array(
            'result' => $email_statement,
            'retailer' => $retailer_guid,
            'date_start' => $date_from,
            'date_end' => $date_to,
        );

        $this->load->view('Consignment/email_recipient_list', $data);
    }

    public function blast_email_preview(){

        $retailer_guid = $this->input->post('retailer');
        $date_from = $this->input->post('date_start');
        $date_to = $this->input->post('date_end');

        $acc_info = $this->db->query("SELECT acc_name, azure_container_name, b2b_database, b2b_hub_database, public_ip FROM lite_b2b.acc WHERE acc_guid = '$retailer_guid'");

        $effective_date = date('d-M-Y');
        $statement_date = date("M-Y", strtotime($date_from));

        $info = $this->db->query("SELECT * FROM lite_b2b.email_template WHERE `type` = 'CS' AND mail_type = 'Consign_process' AND is_active = '1' LIMIT 1")->row();

        $email_subject = $info->mail_subject;
        $email_header = $info->body_header;
        $email_body = $info->body_content;
        $email_footer = $info->body_footer;

        $html = '<b>Email Subject = '. $email_subject .'</b>';
        $html .= '<br><br>';
        $html .= $email_header;
        $html .= $email_body;
        $html .= $email_footer;

        $html = str_replace('%short_name%', strtoupper($acc_info->row('azure_container_name')), $html);
        $html = str_replace('%retailer_name%', $acc_info->row('acc_name'), $html);
        $html = str_replace('%effective_date%', $effective_date, $html);
        $html = str_replace('%statement_date%', $statement_date, $html);

        echo $html;
    }

    public function blast_email(){

        $email_array = $this->input->post('selected');
        $email_list = "'" . implode("', '", $email_array) . "'";

        $this->db->query("UPDATE lite_b2b.`blast_email_list` SET `status` = 1 WHERE email_guid IN ($email_list)");

        $response = array(
            'status'    => 1,
            'message'   => 'Success insert email into blast email agent'
        );

        echo json_encode($response); die;

    }

    public function blast_email_old()
    {
        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','1500M');

        $retailer_guid = $this->input->post('retailer');
        $date_from = $this->input->post('date_start');
        $date_to = $this->input->post('date_end');
        $email_array = $this->input->post('selected');
        $email_list = "'" . implode("', '", $email_array) . "'";

        $effective_date = date('d-M-Y');
        $statement_date = date("M-Y", strtotime($date_from));

        $email_info = $this->db->query("SELECT * FROM lite_b2b.consign_email_setup WHERE customer_guid = '$retailer_guid'")->row();

        $email_body = $email_info->email_body;
        $email_body = str_replace('@effective_date', $effective_date, $email_body);
        $email_body = str_replace('@statement_date', $statement_date, $email_body);
        
       //https://api.xbridge.my/blast_email/index.php/blast_temp/bataras_send_consign_sale_statement_remind
        $user_guid = $this->db->query("SELECT *,REPLACE(email,' ','') as emails FROM lite_b2b.`consign_statement_email` WHERE guid IN ($email_list)");

        // print_r($user_guid->result_array()); die;

        $curl_ip = $this->db->query("SELECT value FROM lite_b2b.`config` WHERE code = 'GIP' LIMIT 1")->row('value');
        $fail_cnt = 0;

        foreach($user_guid->result() as $value) 
        {
            $Subject = $email_info->email_subject;
            $Body    = $email_body;
            $from_email = 'support@xbridge.my';
            $from_email_name = 'B2B-noreply';
            $to_email = $value->emails;
            $to_email_name = $value->emails;

            $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');
            $variable1 = array($variable);
            $variables = array('var1' => $variable1);

            $from = array('Email' => $from_email,'Name' => $from_email_name);
            $to = array('Email' => $to_email,'Name' => $to_email_name);
            $to_array = array($to);

            $variable1 = array($variable);
            $variables = array('var1' => $variable1);

            $TextPart = $Subject;
            $HTMLPart = $Body; 

            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables);

            $myJSON = json_encode($data);

            $to_shoot_url = $curl_ip."/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
            $ch = curl_init(); 

            curl_setopt_array($ch, array(
              CURLOPT_URL => $to_shoot_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 3000,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $myJSON,
              CURLOPT_HTTPHEADER => array(
                "x-api-key: 123456",
                "Content-Type: application/json"
              ),
            ));

            $result = curl_exec($ch);
            $result1 = json_decode($result);
            // print_r($result);die;
            // print_r($result1->Messages[0]->Errors[0]->StatusCode);die;
            $retry = 0;
            while(curl_errno($ch) == 28 && $retry < 3){
                $response = curl_exec($ch);
                $retry++;
            }

            if(!curl_errno($ch))
            {
                if(isset($result1->Messages[0]))
                {
                    $status = $result1->Messages[0]->Status;
                }
                else
                {
                    $status = $result1->ErrorMessage;
                }


                if($status == 'success')
                {
                    $this->db->query("UPDATE lite_b2b.consign_statement_email SET issend = 1, status = 'success', response = '$result', send_at = NOW() WHERE guid = '$value->guid'");
                }
                else
                {
                    $this->db->query("UPDATE lite_b2b.consign_statement_email SET issend = 0, status = 'failed', response = '$result', send_at = NOW() WHERE guid = '$value->guid'");
                }

                curl_close($ch);
            }
            else
            {
                    $this->db->query("UPDATE lite_b2b.consign_statement_email SET issend = 0, status = 'failed', response = '$result', send_at = NOW() WHERE guid = '$value->guid'");

                    $fail_cnt++;
            }        
        }

        if($fail_cnt == 0){
            $response = array(
                'status'    => 1,
                'message'   => 'Success blast email'
            );
            echo json_encode($response); die;
        }

        if(sizeof($user_guid->result()) == $fail_cnt){
            $response = array(
                'status'    => 0,
                'message'   => 'Fail to blast email'
            );
        }else{
            $response = array(
                'status'    => 0,
                'message'   => 'Some email cannot be send, Kindly check the email log'
            );
        }

        echo json_encode($response); die;

        // $this->session->set_flashdata('message', 'Success blast email');
        // redirect('Consignment');
    }

    public function insert_consign_log($data = array())
    {
        $check_log_table = $this->db->query("SELECT COUNT(*) AS result FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'lite_b2b' AND TABLE_NAME = 'consign_process_log'")->row('result');

        if($check_log_table == '0'){
            $this->db->query("CREATE TABLE `consign_process_log` (
                `guid` varchar(32) NOT NULL,
                `process` varchar(150) DEFAULT NULL,
                `customer_guid` varchar(32) DEFAULT NULL,
                `user_id` varchar(100) DEFAULT NULL,
                `post_url` text,
                `response` text,
                `date_added` datetime DEFAULT NULL,
                PRIMARY KEY (`guid`),
                KEY `process` (`process`),
                KEY `customer_guid` (`customer_guid`),
                KEY `user_id` (`user_id`),
                KEY `date_added` (`date_added`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8
              ");
        }

        if(isset($data['log_guid'])){
            $log_guid = $data['log_guid'];
        }else{
            $log_guid  = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS UUID")->row('UUID');
        }

        $process = isset($data['process']) ? $data['process'] : '';
        $customer_guid = isset($data['customer_guid']) ? $data['customer_guid'] : '';
        $user_id = isset($data['user_id']) ? $data['user_id'] : '';
        $post_url = isset($data['post_url']) ? $data['post_url'] : '';
        $response = isset($data['response']) ? $data['response'] : '';

        if(is_array($response)){
            $response = json_encode($response);
        }

        $response = addslashes($response);

        $this->db->query("INSERT INTO lite_b2b.consign_process_log SELECT '$log_guid', '$process', '$customer_guid','$user_id','$post_url','$response',NOW()");
        
    }

}
?>
