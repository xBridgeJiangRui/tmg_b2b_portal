<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Propose_po extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('datatables');
        $this->load->library('Panda_PHPMailer');

        if(!($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()))
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);

        $_SESSION['outlet_count'] = $this->get_outlet_count();
    }

    // public function setting_column_list()
    // {
    //     $retailer = $this->input->post('retailer');

    //     $guide_list = $this->db->query("SELECT * FROM lite_b2b.mc_guide WHERE lang_type = '$language' ORDER BY seq ASC")->result_array();
    //         $selected_guide = $this->db->query("SELECT mg.guide_guid, mg.title, mg.description, mg.file_name FROM lite_b2b.mc_guide mg INNER JOIN mc_guide_c mgc ON mg.guide_guid = mgc.guide_guid WHERE mgc.customer_guid = '$retailer' AND mg.lang_type = '$language' ORDER BY mg.seq ASC")->result_array();

    //     $html = '<br>';
    //     $html .= '<label style="padding-right: 20px">Manual Guide</label>';
    //     $html .= '<span>';
    //     $html .= '<a style="cursor: pointer;" onclick="selectAllGuideCheckboxes()">Select All</a>'; 
    //     $html .= '/';
    //     $html .= '<a style="cursor: pointer;" onclick="unselectAllGuideCheckboxes()">Unselect All</a>';
    //     $html .= '</span>';

            

    //         foreach($guide_list as $row)
    //         {
    //             $checked = '';

    //             foreach($selected_guide as $data){
    //                 if($row['guide_guid'] == $data['guide_guid']){
    //                     $checked = 'checked';
    //                 }
    //             }

    //             $html .= '<input type="checkbox" class="'.$language.'_manual_guide_checkbox" name ="selected_guide[]" value="'.$row['guide_guid'].'" '.$checked.'>';
    //             $html .= '<label for="'.$row['title'].'">'.$row['title'].'</label><br>';
    //         }

    //         $html .= '</div>';

    //     echo $html;
    // }

    public function current_datetime(){
        return $this->db->query("SELECT NOW() AS `current_time`;")->row('current_time');
    }

    public function insert_log($doc_guid, $api_method, $action, $url, $post_data, $response, $start_datetime){
        
        $customer_guid = $_SESSION['customer_guid'];
        $retailer = $this->db->query("SELECT b2b_database FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $backend_db = $retailer->row('b2b_database');

        $log_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS `guid`;")->row('guid');
        $finish_datetime = $this->db->query("SELECT NOW() AS `current_time`;")->row('current_time');
        $duration = strtotime($finish_datetime) - strtotime($start_datetime);

        // insert log
        $insert_data = array(
            'guid' => $log_guid,
            'doc_type' => 'PO',
            'doc_guid' => $doc_guid,
            'api_method' => $api_method,
            'action' => $action,
            'url' => $url,
            'post_data' => trim(urldecode($post_data), "'"),
            'response' => trim($response, "'"),
            'start_datetime' => $start_datetime,
            'finish_datetime' => $finish_datetime,
            'duration_in_sec' => $duration,
        );

        $this->db->insert($backend_db.'.propose_trans_logs', $insert_data);

    }
    
    public function get_transmain_guid()
    {   
        $transmain_guid = $_SESSION['transmain_guid'];

        if($transmain_guid != null || $transmain_guid != ''){

            $response = array(
                'status'            => true,
                'message'           => 'Transmain guid session exists',
                'transmain_guid'    => $transmain_guid,
            );

        }else{

            $response = array(
                'status'            => false,
                'message'           => 'Transmain guid session not exists',
                'transmain_guid'    => '',
            );

        }

        echo json_encode($response); die;
    }

    public function get_active_outlet()
    {   
        $customer_guid = $_SESSION['customer_guid'];
        $supplier = $this->input->post("supplier");
        $supplier_info = explode('||', $supplier);
        $supplier_code = $supplier_info[0];

        $retailer = $this->db->query("SELECT acc_guid, acc_name, b2b_database FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $backend_db = $retailer->row('b2b_database');

        $outlet_list = $this->db->query("SELECT c.*,b.branch_name,IF(c.`branch_desc` = '' OR c.`branch_desc` IS NULL,CONCAT(c.branch_code,' - ',b.branch_name),CONCAT(c.branch_code,' - ',c.branch_desc)) AS branch_description FROM lite_b2b.acc_branch b INNER JOIN $backend_db.cp_set_branch c ON b.branch_code = c.branch_code INNER JOIN $backend_db.supcus_branch sb ON c.`BRANCH_CODE` = sb.`loc_group` INNER JOIN $backend_db.`supcus` s ON sb.`supcus_guid` = s.`supcus_guid` WHERE s.`Code` = '$supplier_code' AND sb.`set_active` = '1'  GROUP BY c.BRANCH_GUID ORDER BY b.branch_code ASC")->result_array();

        if(sizeof($outlet_list) > 0){

            $response = array(
                'status'    => true,
                'message'   => 'Success',
                'results'   => $outlet_list,
            );

        }else{

            $response = array(
                'status'    => false,
                'message'   => 'Unable to retrieve active outlets',
                'results'   => array(),
            );

        }

        echo json_encode($response); die;
    }

    public function get_delivery_date()
    {   
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $currentDateTime = date('Y-m-d H:i:s');

        $supplier = $this->input->post("supplier");
        $supplier_info = explode('||', $supplier);
        $supplier_code = $supplier_info[0];

        $supcus_info = $this->get_supcus_detail($supplier_code);

        if(sizeof($supcus_info['results']) > 0){

            $deliveryterms = $supcus_info['results'][0]['deliveryterm'];
            $modifiedDateTime = date('Y-m-d  D', strtotime($currentDateTime . ' +' . $deliveryterms . ' days'));

            $response = array(
                'status'        => true,
                'message'       => 'Success',
                'delivery_date' => $modifiedDateTime,
            );

        }else{

            $modifiedDateTime = date('Y-m-d  D', strtotime($currentDateTime . ' +0 days'));

            $response = array(
                'status'        => false,
                'message'       => 'Unable to retrieve delivery terms',
                'delivery_date' => $modifiedDateTime,
            );

        }

        echo json_encode($response); die;
    }

    public function propose_record()
    {   
        $customer_guid = $_SESSION['customer_guid'];

        // echo $this->view_child_listing($customer_guid, $_SESSION['transmain_guid']); die;

        $retailer = $this->db->query("SELECT acc_name FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'")->row('acc_name');
        $acc_settings = $this->db->query("SELECT * from lite_b2b.acc_settings where customer_guid = '$customer_guid'");

        $sup_code = $this->session->userdata('query_supcode');
        $sup_code = str_replace("'", "", $sup_code);

        if($_SESSION['user_group_name'] == 'SUPER_ADMIN'){
            if(isset($filter_array['supplier_code__in'])){
                unset($filter_array['supplier_code__in']);
            }
        }

        $poex_guid = isset($_GET['id']) ? $_GET['id'] : '';

        $complete_by_guid_results = $this->view_posted_header_by_guid($poex_guid);
        $complete_child_by_guid_results = $this->view_posted_child_by_guid($poex_guid);
        $supcus_info = $this->get_supcus_detail($complete_by_guid_results['results'][0]['sup_code']);

        $data = array(
            'retailer'      => $retailer,
            'header_list'   => $complete_by_guid_results['results'],
            'child_list'    => $complete_child_by_guid_results['results'],
            'supcus_info'   => $supcus_info['results'],
            'show_info'     => ($acc_settings->row('show_additional_info') == '' || $acc_settings->row('show_additional_info') == null) ? 0 : $acc_settings->row('show_additional_info'),
            'retrigger_url' => trim($acc_settings->row('propose_doc_api'), "/") . '/po_ex/po_ex/retrigger_mq',
        );

        $this->load->view('header');  
        $this->load->view('propose_po/propose_record', $data);  
        $this->load->view('footer' );  
    }

    public function propose_details()
    {   
        $customer_guid = $this->session->userdata('customer_guid');
        $user_guid = $this->session->userdata('user_guid');
        $retailer = $this->db->query("SELECT acc_guid, acc_name, b2b_database FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $backend_db = $retailer->row('b2b_database');
        $acc_settings = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE customer_guid = '$customer_guid'");

        if($this->session->userdata('loginuser') == true)
        {

            if(in_array('IAVA',$_SESSION['module_code']))
            {

                $code = $this->db->query("SELECT a.code,a.name,a.supcus_guid FROM $backend_db.supcus a WHERE a.type = 'S' ORDER BY a.code ASC");

                $location = $this->db->query("SELECT c.*,b.branch_name,IF(c.`branch_desc` = '' OR c.`branch_desc` IS NULL,CONCAT(c.branch_code,' - ',b.branch_name),CONCAT(c.branch_code,' - ',c.branch_desc)) AS branch_description FROM lite_b2b.acc_branch b INNER JOIN $backend_db.cp_set_branch c ON b.branch_code = c.branch_code GROUP BY c.BRANCH_GUID ORDER BY b.branch_code ASC");

            }else{

                $code = $this->db->query("SELECT c.`Name` AS `name`, c.`Code` AS `code` FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.set_supplier_group b ON a.supplier_group_guid = b.supplier_group_guid AND a.customer_guid = b.customer_guid INNER JOIN $backend_db.supcus c ON b.supplier_group_name = c.Code  WHERE a.user_guid = '$user_guid' AND a.customer_guid = '$customer_guid'");

                $location = $this->db->query("SELECT c.*,b.branch_name,IF(c.`branch_desc` = '' OR c.`branch_desc` IS NULL,CONCAT(c.branch_code,' - ',b.branch_name),CONCAT(c.branch_code,' - ',c.branch_desc)) AS branch_description FROM lite_b2b.set_user_branch a INNER JOIN lite_b2b.acc_branch b ON a.branch_guid = b.branch_guid INNER JOIN $backend_db.cp_set_branch c ON b.branch_code = c.branch_code WHERE a.user_guid = '$user_guid' AND a.acc_guid = '$customer_guid'");
                    
            }

            if(isset($_GET['id'])){

                $header_result = $this->view_header(1, array(), 1);
                $header_result = $header_result['results'][0];

                // print_r($header_result); die;
    
                // $child_result = $this->view_child_listing($customer_guid, $header_result['transmain_guid'], 1);
                // $child_result = $child_result['results'];
    
                $_SESSION['transmain_guid'] = $header_result['transmain_guid'];
    
                $data = array(
                    'sup_code'      => $header_result['supplier_code'],
                    'sup_name'      => $header_result['supplier_name'],
                    'po_refno'      => $header_result['refno'],
                    'delivery_date' => $header_result['delivery_date'],
                    'created_by'    => $header_result['created_by'],
                    'created_at'    => $header_result['created_at'],
                    'location_list' => $header_result['location_list'],
                    'remark_h'      => $header_result['remark_h'],
                    'code'          => $code->result(),
                    'location'      => $location->result(),
                    'customer_guid' => $customer_guid,
                    'retailer'      => $retailer,
                    'show_info'     => ($acc_settings->row('show_additional_info') == '' || $acc_settings->row('show_additional_info') == null) ? 0 : $acc_settings->row('show_additional_info'),
                    'block_cost'    => ($acc_settings->row('block_edit_cost') == '' || $acc_settings->row('block_edit_cost') == null) ? 0 : $acc_settings->row('block_edit_cost'),
                );

            }else{

                $data = array(
                    'code'          => $code->result(),
                    'location'      => $location->result(),
                    'customer_guid' => $customer_guid,
                    'retailer'      => $retailer,
                    'show_info'     => ($acc_settings->row('show_additional_info') == '' || $acc_settings->row('show_additional_info') == null) ? 0 : $acc_settings->row('show_additional_info'),
                    'block_cost'    => ($acc_settings->row('block_edit_cost') == '' || $acc_settings->row('block_edit_cost') == null) ? 0 : $acc_settings->row('block_edit_cost'),
                );
            }

            $this->load->view('header'); 
            $this->load->view('propose_po/propose_details', $data);  
            $this->load->view('footer' );  
        }
        else
        {
            redirect('#');
        }
    }
    public function propose_details_main()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $user_guid = $this->session->userdata("user_guid");
        $customer_guid = $this->input->post("retailer");
        $supplier = $this->input->post("outright_code");
        $delivery_date = $this->input->post("delivery_date");
        $supplier_info = explode('||', $supplier);
        $supplier_code = $supplier_info[0];
        $supplier_name = $supplier_info[1];
        $location = $this->input->post("outright_location");
        $refresh = isset($_POST['refresh']) ? $this->input->post("refresh") : 0;

        $username = $this->db->query("SELECT user_name FROM lite_b2b.set_user WHERE user_guid = '$user_guid' AND isactive = '1' LIMIT 1")->row('user_name');

        if($refresh == 1){

            if($_SESSION['outlet_count'] > 1){
                $this->view_child_listing($customer_guid, $_SESSION['transmain_guid']);
            }else{
                $this->view_propose_info();
            }

            die;
        }

        if(!is_array($location)){
            $location = explode(',', $location);
        }

        $transmain_guid = $this->generate_po_replenishment($customer_guid, $location, $username, $supplier_code, $supplier_name, $delivery_date);

        // print_r($transmain_guid); die;
        
        $result = json_decode($transmain_guid, true);

        if($result['status'] == true){

            $transmain_guid = $result['transmain_guid'];

            $_SESSION['transmain_guid'] = $transmain_guid;

            if($_SESSION['outlet_count'] > 1){
                $this->view_child_listing($customer_guid, $transmain_guid);
            }else{
                $this->view_propose_info();
            }

        }else{

            $data = array(  
                'status' => 0,
                'message' => isset($result['message']) ? $result['message'] : 'Fail to generate. Please try again.',
            );

            echo json_encode($data);

        }

    }

    public function propose_action_editor()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $customer_guid = $this->input->post("retailer");
        $transchild_guid = $this->input->post("transchild_guid");

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $data = array(
            'transchild_guid__transchild_guid'  => $transchild_guid,
            'limit'                             => '999999999'
        );

        $queryString = http_build_query($data);

        $to_shoot_url = $api_url."/purchase_order/pochild_2/".$customer_guid."/TsPoChild_2?".$queryString;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        echo $result;

    }

    public function propose_action_editor_by_outlet()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $customer_guid = $_SESSION['customer_guid'];
        $transmain_guid = isset($_GET['id']) ? $_GET['id'] : $_SESSION['transmain_guid'];
        $outlet = $this->input->post("outlet_code");

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $data = array(
            'location'         => $outlet,
            'transmain_guid'   => $transmain_guid,
            'retailer_guid'    => $customer_guid,
        );

        $to_shoot_url = $api_url."/purchase_order/pochild/by_outlet_by_item";

        $curl = curl_init();

        curl_setopt_array($curl, array(	
            CURLOPT_URL => $to_shoot_url,	
            CURLOPT_RETURNTRANSFER => true,	
            CURLOPT_ENCODING => '',	
            CURLOPT_MAXREDIRS => 10,	
            CURLOPT_TIMEOUT => 0,	
            CURLOPT_FOLLOWLOCATION => true,	
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,	
            CURLOPT_CUSTOMREQUEST => 'POST',	
            CURLOPT_POSTFIELDS => json_encode($data),	
            CURLOPT_HTTPHEADER => array(	
              'Content-Type: application/json'	
            ),	
          ));

        $result = curl_exec($curl);

        curl_close($curl);

        echo $result;

    }

    public function save_propose_child_new()
    {   
        $customer_guid = $this->input->post('retailer');
        $details = $this->input->post('details');

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        foreach($details as $row)
        {   

            $data = array(
                'qty' => $row['qty_actual'],
                'qty_foc' => $row['foc_actual'],
                'cost' => $row['price_actual'],
            );

            $curl = curl_init();

            // echo $api_url.'/purchase_order/pochild_2/'.$customer_guid.'/TsPoChild_2/'.$row['detail_guid'].'/'; echo '</br>';
            // echo json_encode($data); die;

            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url.'/purchase_order/pochild_2/'.$customer_guid.'/TsPoChild_2/'.$row['detail_guid'].'/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            try {
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($httpCode >= 200 && $httpCode < 300) {

                    $data = array(  
                        'status' => 1,
                        'message' => 'Successfully Update'
                    );
                    
                } else {

                    $error_response = json_decode($response, true);
                    $keys = array_keys($error_response);

                    $data = array(  
                        'status' => 0,
                        'error' => ucfirst($keys[0]),
                        'message' => $error_response['message']
                    );

                    break;
                }
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
                // handle exception
            }

            curl_close($curl);

            $this->insert_log($_SESSION['transmain_guid'], 'PATCH', 'Update Child', $api_url.'/purchase_order/pochild_2/'.$customer_guid.'/TsPoChild_2/'.$row['detail_guid'].'/', json_encode($data), $response, $this->current_datetime());
        }

        echo json_encode($data);  
    }

    public function view_cost_summary(){

        $customer_guid = $_SESSION['customer_guid'];
        $transmain_guid = isset($_GET['id']) ? $_GET['id'] : $_SESSION['transmain_guid'];
        $return_json = isset($_POST['return_json']) ? $_POST['return_json'] : 0;

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $to_shoot_url = $api_url."/purchase_order/pomain/calculate_total_amount_by_outlet";

        $data = array(
            'retailer_guid'     => $customer_guid,
            'transmain_guid'    => $transmain_guid
        );

        $ch = curl_init();	

        curl_setopt_array($ch, array(	
            CURLOPT_URL => $to_shoot_url,	
            CURLOPT_RETURNTRANSFER => true,	
            CURLOPT_ENCODING => '',	
            CURLOPT_MAXREDIRS => 10,	
            CURLOPT_TIMEOUT => 0,	
            CURLOPT_FOLLOWLOCATION => true,	
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,	
            CURLOPT_CUSTOMREQUEST => 'POST',	
            CURLOPT_POSTFIELDS => json_encode($data),	
            CURLOPT_HTTPHEADER => array(	
              'Content-Type: application/json'	
            ),	
          ));	

        $response = curl_exec($ch);

        curl_close($ch);

        $this->insert_log($transmain_guid, 'POST', 'Get Cost Summary', $api_url."/purchase_order/pomain/calculate_total_amount_by_outlet", json_encode($data), $response, $this->current_datetime());

        if($return_json == 1){
            echo $response; die;
        }

        $result = json_decode($response, true);

        $this->load->view('propose_po/propose_summary', $result);

    }

    public function save_delivery_date($customer_guid, $transmain_guid, $supplier_detail, $delivery_date)
    {   

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $data = array(
            'delivery_date' => $delivery_date,
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url.'/purchase_order/pomain/'.$customer_guid.'/TsPoMain/'.$transmain_guid.'/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $this->insert_log($transmain_guid, 'PATCH', 'Update Delivery Date', $api_url.'/purchase_order/pomain/'.$customer_guid.'/TsPoMain/'.$transmain_guid.'/', json_encode($data), $response, $this->current_datetime());

        try {
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpCode >= 200 && $httpCode < 300) {

                $data = array(  
                    'status' => 1,
                    'message' => 'Successfully Update'
                );
                    
            } else {

                $error_response = json_decode($response, true);
                $keys = array_keys($error_response);

                $data = array(  
                    'status' => 0,
                    'error' => ucfirst($keys[0]),
                    'message' => $error_response[$keys[0]][0]
                );

            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        curl_close($curl);

        return json_encode($data);  
    }

    public function update_header_info()
    {   
        $customer_guid = isset($_POST['retailer']) ? $_POST['retailer'] : $_SESSION['customer_guid'];
        $transmain_guid = isset($_POST['guid']) ? $_POST['guid'] : $_SESSION['transmain_guid'];
        $delivery_date = isset($_POST['delivery_date']) ? $_POST['delivery_date'] : '';
        $remark_h = isset($_POST['remark_h']) ? $_POST['remark_h'] : '';

        $data = array();

        if($delivery_date != ''){
            $data['delivery_date'] = $delivery_date;
        }

        if($remark_h != ''){
            $data['remark_h'] = $remark_h;
        }

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        // echo $api_url.'/purchase_order/pomain/'.$customer_guid.'/TsPoMain/'.$transmain_guid.'/'; echo '</br>';
        // print_r(json_encode($data)); die;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url.'/purchase_order/pomain/'.$customer_guid.'/TsPoMain/'.$transmain_guid.'/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $this->insert_log($transmain_guid, 'PATCH', 'Update Header', $api_url.'/purchase_order/pomain/'.$customer_guid.'/TsPoMain/'.$transmain_guid.'/', json_encode($data), $response, $this->current_datetime());

        try {
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpCode >= 200 && $httpCode < 300) {

                $data = array(  
                    'status' => 1,
                    'message' => 'Successfully Update'
                );
                    
            } else {

                $error_response = json_decode($response, true);
                $keys = array_keys($error_response);

                $data = array(  
                    'status' => 0,
                    'error' => ucfirst($keys[0]),
                    'message' => $error_response[$keys[0]][0]
                );

            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        curl_close($curl);

        echo json_encode($data);  
    }

    public function create_header(){

        $user_guid = $this->session->userdata("user_guid");
        $customer_guid = $this->input->post("retailer");
        $supplier = $this->input->post("outright_code");
        $delivery_date = $this->input->post("delivery_date");
        $supplier_info = explode('||', $supplier);
        $supplier_code = $supplier_info[0];
        $supplier_name = $supplier_info[1];
        $location = $this->input->post("outright_location");

        if(!is_array($location)){
            $location = explode(',', $location);
        }

        $username = $this->db->query("SELECT user_name FROM lite_b2b.set_user WHERE user_guid = '$user_guid' AND isactive = '1' LIMIT 1")->row('user_name');
        $retailer_name = $this->db->query("SELECT acc_name from lite_b2b.acc where acc_guid = '$customer_guid'")->row('acc_name');

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $to_shoot_url = $api_url."/purchase_order/pomain/generate_po_replenishment";

        $data = array(
            'retailer_guid' => $customer_guid,
            'retailer_name' => $retailer_name,
            'outlet' => $location,
            'supplier_code' => $supplier_code,
            'supplier_name' => $supplier_name,
            'delivery_date'  => $delivery_date,
            'updated_by' => $username
        );

        // echo $to_shoot_url; echo '</br>'; echo json_encode($data); die;

        $ch = curl_init();	

        curl_setopt_array($ch, array(	
            CURLOPT_URL => $to_shoot_url,	
            CURLOPT_RETURNTRANSFER => true,	
            CURLOPT_ENCODING => '',	
            CURLOPT_MAXREDIRS => 10,	
            CURLOPT_TIMEOUT => 0,	
            CURLOPT_FOLLOWLOCATION => true,	
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,	
            CURLOPT_CUSTOMREQUEST => 'POST',	
            CURLOPT_POSTFIELDS => json_encode($data),	
            CURLOPT_HTTPHEADER => array(	
              'Content-Type: application/json'	
            ),	
          ));	

        $response = curl_exec($ch);

        $array_response = json_decode($response, true);

        if($array_response['status'] == true){
            $_SESSION['transmain_guid'] = $array_response['transmain_guid'];
        }else{
            unset($_SESSION['transmain_guid']);
        }

        curl_close($ch);

        $this->insert_log($_SESSION['transmain_guid'], 'POST', 'Create Document', $api_url."/purchase_order/pomain/generate_po_replenishment", json_encode($data), $response, $this->current_datetime());

        echo $response;

    }

    public function generate_po_replenishment($customer_guid, $location, $user_guid, $supplier_code, $supplier_name, $delivery_date){

        $retailer_name = $this->db->query("SELECT acc_name from lite_b2b.acc where acc_guid = '$customer_guid'")->row('acc_name');

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $to_shoot_url = $api_url."/purchase_order/pomain/generate_po_replenishment";

        $data = array(
            'retailer_guid' => $customer_guid,
            'retailer_name' => $retailer_name,
            'outlet' => $location,
            'supplier_code' => $supplier_code,
            'supplier_name' => $supplier_name,
            'delivery_date'  => $delivery_date,
            'updated_by' => $user_guid
        );

        $ch = curl_init();	

        curl_setopt_array($ch, array(	
            CURLOPT_URL => $to_shoot_url,	
            CURLOPT_RETURNTRANSFER => true,	
            CURLOPT_ENCODING => '',	
            CURLOPT_MAXREDIRS => 10,	
            CURLOPT_TIMEOUT => 0,	
            CURLOPT_FOLLOWLOCATION => true,	
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,	
            CURLOPT_CUSTOMREQUEST => 'POST',	
            CURLOPT_POSTFIELDS => json_encode($data),	
            CURLOPT_HTTPHEADER => array(	
              'Content-Type: application/json'	
            ),	
          ));	

        $response = curl_exec($ch);

        curl_close($ch); 
        
        $this->insert_log($_SESSION['transmain_guid'], 'POST', 'Create Document', $api_url."/purchase_order/pomain/generate_po_replenishment", json_encode($data), $response, $this->current_datetime());

        return $response;

    }

    public function confirm_propose()
    {
        $user_guid = $this->session->userdata('user_guid');
        $customer_guid = $this->input->post("retailer");
        $transmain_guid = $_SESSION['transmain_guid'];
        $supplier_detail = $this->input->post("supplier_detail");
        $delivery_date = $this->input->post("delivery_date");

        $username = $this->db->query("SELECT user_name FROM lite_b2b.set_user WHERE user_guid = '$user_guid' AND isactive = '1' LIMIT 1")->row('user_name');

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $to_shoot_url = $api_url."/purchase_order/pomain/post_po_replenishment";

        $data = array(
            'retailer_guid' => $customer_guid,
            'transmain_guid' => $transmain_guid,
            'updated_by' => $username,
        );

        // echo $to_shoot_url; echo '</br>'; echo json_encode($data); die;

        $ch = curl_init($to_shoot_url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
        // curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);

        curl_close($ch);

        $this->insert_log($transmain_guid, 'POST', 'Post Document', $api_url."/purchase_order/pomain/post_po_replenishment", json_encode($data), $response, $this->current_datetime());

        $response = json_encode($response);

        echo trim(stripslashes($response), '"');


    }

    public function view_propose_info(){

        $customer_guid = $_SESSION['customer_guid'];
        $transmain_guid = $_SESSION['transmain_guid'];

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $data = array(
            'transmain_guid'    => $transmain_guid,
            'search'            => isset($_POST['search']['value']) ? $_POST['search']['value'] : '',
            'limit'             => isset($_POST['length']) ? $_POST['length'] : '10',
            'offset'            => isset($_POST['start']) ? $_POST['start'] : '0',
        );

        if(isset($_POST['order'][0])){

            $column_list = array(
                '0'     => 'itemcode',
                '1'     => 'barcode',
                '2'     => 'description',
                '3'     => 'packsize',
                '4'     => 'orderlotsize',
                '5'     => 'order_qty',
                '6'     => 'order_qty',
                '7'     => 'foc_qty',
                '8'     => 'net_cost',
                '9'     => 'amount',
                '10'    => 'qty',
                '11'    => 'qty_bal',
                '12'    => 'days',
                '13'    => 'ads',
                '14'    => 'aws',
                '15'    => 'ams',
                '16'    => 'qty_tbr',
            );

            $filter_column = $column_list[$_POST['order'][0]['column']];

            if($_POST['order'][0]['dir'] == 'desc'){
                $filter_column = '-'.$filter_column;
            }

            $data['ordering'] = $filter_column;
        }

        $queryString = http_build_query($data);

        $to_shoot_url = $api_url."/purchase_order/pochild/".$customer_guid."/TsPoChild_parent?".$queryString;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $result = curl_exec($curl);

        $this->insert_log($_SESSION['transmain_guid'], 'GET', 'Get Child Details', $api_url."/purchase_order/pochild/".$customer_guid."/TsPoChild_parent?", $queryString, $result, $this->current_datetime());

        try {
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (!($httpCode >= 200 && $httpCode < 300)) {

                $data = array(  
                    'status' => 0,
                    'message' => 'Unable to retrieve the data, please refresh the page'
                );

                echo json_encode($data); die;
            }
                    
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        curl_close($curl);

        $array_result = json_decode($result, true);
        $array_result['draw'] = intval($_REQUEST['draw']);
        $array_result['recordsTotal'] = $array_result['count'];
        $array_result['recordsFiltered'] = $array_result['count'];
        $array_result['data'] = $array_result['results'];

        $result = json_encode($array_result);

        echo $result;

    }

    public function view_child_listing($customer_guid, $transmain_guid, $return_response = 0){

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $data = array(
            'transmain_guid__transmain_guid'    => $transmain_guid,
            'limit'                             => '999999999'
        );

        $queryString = http_build_query($data);

        $to_shoot_url = $api_url."/purchase_order/pochild/".$customer_guid."/TsPoChild?".$queryString;

        // print_r($to_shoot_url);
        // echo '</br>';
        // echo json_encode($data); die;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        if($return_response == 1){
            return json_decode($result, true);
        }else{
            echo $result;
        }

    }

    public function view_header($return_response = 0, $filter_array = array(), $location = 0){

        $customer_guid = (isset($_GET['id']) || sizeof($filter_array) != 0) ? $this->session->userdata('customer_guid') : $this->input->post("retailer");
        $transmain_guid = isset($_GET['id']) ? $_GET['id'] : $_SESSION['transmain_guid'];

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        if(sizeof($filter_array) != 0){

            $data = $filter_array;

        }else{

            $data = array(
                'transmain_guid' => $transmain_guid,
            );
        }

        if(isset($_POST['datatable_load'])){

            $sup_code = $this->session->userdata('query_supcode');
            $sup_code = str_replace("'", "", $sup_code);

            $data = array(
                // 'supplier_code__in' => $sup_code, //nabil testing
                'doc_status__in'    => 'NEW',
                'search'            => isset($_POST['search']['value']) ? $_POST['search']['value'] : '',
                'limit'             => isset($_POST['length']) ? $_POST['length'] : '10',
                'offset'            => isset($_POST['start']) ? $_POST['start'] : '0',
            );

            $queryString = http_build_query($data);
        }

        if($location == 1){
            $data['_nested_'] = 2;
        }

        if(isset($_POST['order'][0])){

            $column_list = array(
                '0' => 'created_at',
                '1' => 'refno',
                '2' => 'supplier_code',
                '3' => 'delivery_date',
                '4' => 'created_at',
                '5' => 'updated_at',
            );

            $filter_column = $column_list[$_POST['order'][0]['column']];

            if($_POST['order'][0]['dir'] == 'desc'){
                $filter_column = '-'.$filter_column;
            }

            $filter_array['ordering'] = $filter_column;
        }

        $queryString = http_build_query($data);

        $to_shoot_url = $api_url."/purchase_order/pomain/".$customer_guid."/TsPoMain?".$queryString;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_VERBOSE => true,
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        $array_result = json_decode($result, true);
        $array_result['draw'] = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
        $array_result['recordsTotal'] = isset($array_result['count']) ? $array_result['count'] : 0;
        $array_result['recordsFiltered'] = isset($array_result['count']) ? $array_result['count'] : 0;
        $array_result['data'] = isset($array_result['results']) ? $array_result['results'] : array();

        $result = json_encode($array_result);

        $this->insert_log($_SESSION['transmain_guid'], 'GET', 'Get Header Details', $api_url."/purchase_order/pomain/".$customer_guid."/TsPoMain?", $queryString, $result, $this->current_datetime());

        if($return_response == 1){
            return json_decode($result, true);
        }else{
            echo $result;
        }

    }

    public function view_posted_header(){

        $customer_guid = $this->session->userdata('customer_guid');
        $sup_code = $this->session->userdata('query_supcode');
        $sup_code = str_replace("'", "", $sup_code);

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $search = array("((((", "))))");
        $replace = array("", ""); 
        
        $filter_array = array(
            'supplier_code__in' => $sup_code, // nabil testing
            'limit'             => isset($_POST['length']) ? $_POST['length'] : '10',
            'offset'            => isset($_POST['start']) ? $_POST['start'] : '0',
            'search'            => isset($_POST['search']['value']) ? $_POST['search']['value'] : '',
            // 'docdate'           => isset($_POST['columns'][4]['search']['value']) ? str_replace($search, $replace, $_POST['columns'][4]['search']['value']) : '',
            // 'delivery_date'     => isset($_POST['columns'][5]['search']['value']) ? str_replace($search, $replace, $_POST['columns'][5]['search']['value']) : '',
            // 'po_refno'          => isset($_POST['columns'][7]['search']['value']) ? str_replace($search, $replace, $_POST['columns'][7]['search']['value']) : '',
            'doc_status__in'    => isset($_POST['columns'][8]['search']['value']) ? str_replace($search, $replace, $_POST['columns'][8]['search']['value']) == 'AWAITING APPROVAL' ? 'POSTED,PROCESSING' : str_replace($search, $replace, $_POST['columns'][8]['search']['value']) : '',
        );

        if(isset($_POST['order'][0])){

            $column_list = array(
                '0' => 'posted_at',
                '1' => 'refno',
                '2' => 'branch',
                '3' => 'sup_code',
                '4' => 'docdate',
                '5' => 'delivery_date',
                '6' => 'posted_at',
                '7' => 'po_refno',
                '8' => 'doc_status',

            );

            $filter_column = $column_list[$_POST['order'][0]['column']];

            if($_POST['order'][0]['dir'] == 'desc'){
                $filter_column = '-'.$filter_column;
            }

            $filter_array['ordering'] = $filter_column;
        }

        $queryString = http_build_query($filter_array);

        $to_shoot_url = $api_url."/po_ex/po_ex/".$customer_guid."/ProposePoExPoEx?".$queryString;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        $this->insert_log('', 'GET', 'View Posted Record', $api_url."/po_ex/po_ex/".$customer_guid."/ProposePoExPoEx?", $queryString, $result, $this->current_datetime());

        $array_result = json_decode($result, true);
        $array_result['draw'] = intval($_REQUEST['draw']);
        $array_result['recordsTotal'] = $array_result['count'];
        $array_result['recordsFiltered'] = $array_result['count'];
        $array_result['data'] = $array_result['results'];

        $result = json_encode($array_result);

        echo $result;

    }

    public function view_posted_header_by_guid($poex_guid){

        $customer_guid = $this->session->userdata('customer_guid');

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");
        
        $filter_array = array(
            'poex_guid' => $poex_guid,
        );

        $queryString = http_build_query($filter_array);

        $to_shoot_url = $api_url."/po_ex/po_ex/".$customer_guid."/ProposePoExPoEx?".$queryString;

        // echo $to_shoot_url; die;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result, true);

    }

    public function view_posted_child_by_guid($poex_guid){

        $customer_guid = $this->session->userdata('customer_guid');
        $sup_code = $this->session->userdata('query_supcode');
        $sup_code = str_replace("'", "", $sup_code);

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");
        
        $filter_array = array(
            'poex_guid' => $poex_guid,
        );

        $queryString = http_build_query($filter_array);

        $to_shoot_url = $api_url."/po_ex/po_ex_c/".$customer_guid."/ProposePoExC?".$queryString;

        // return $to_shoot_url; die;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        $this->insert_log($poex_guid, 'GET', 'View Posted Child', $api_url."/po_ex/po_ex_c/".$customer_guid."/ProposePoExC?", $queryString, $result, $this->current_datetime());

        return json_decode($result, true);

    }

    public function get_outlet_count(){

        $outlet_count = 1; // hardcode to 1 because now live with 1 supplier 1 outlet only
        // $header = $this->view_header(1);

        // if(isset($header['results'])){
        //     $header_info = $header['results'];
        //     $outlet_count = sizeof($header_info[0]['location_list']);
        // }

        return isset($outlet_count) ? $outlet_count : 1;

    }

    public function get_supcus_detail($supplier_code){

        $customer_guid = $this->session->userdata('customer_guid');

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $data = array(
            'code' => $supplier_code,
        );

        $queryString = http_build_query($data);

        $to_shoot_url = $api_url."/ml_supcus/ml_supcus/".$customer_guid."/ml_supcus?".$queryString;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_VERBOSE => true,
        ));

        $result = curl_exec($curl);

        curl_close($curl);
        
        $this->insert_log('', 'GET', 'Get Supplier Info', $api_url."/ml_supcus/ml_supcus/".$customer_guid."/ml_supcus?", $queryString, $result, $this->current_datetime());

        return json_decode($result, true);

    }

    public function get_supcus_detail_2(){

        $supplier_code = 'F0048';

        $customer_guid = $this->session->userdata('customer_guid');

        $acc_settings = $this->db->query("SELECT propose_doc_api from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
        $api_url = trim($acc_settings->row('propose_doc_api'), "/");

        $data = array(
            'code' => $supplier_code,
        );

        $queryString = http_build_query($data);

        $to_shoot_url = $api_url."/ml_supcus/ml_supcus/".$customer_guid."/ml_supcus?".$queryString;

        echo $to_shoot_url; die;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $to_shoot_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_VERBOSE => true,
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result, true);

    }

    public function print_document()
    {
        $refno = $_REQUEST['refno'];
        $customer_guid = $_SESSION['customer_guid'];

        $jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');

        $retailer = $this->db->query("SELECT b2b_database FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $backend_db = $retailer->row('b2b_database');

        $filename = $refno."-ProposedPO";
        $url = $jasper_ip . "/jasperserver/rest_v2/reports/reports/PandaReports/Backend_PO/Proposed_Purchase_Order.pdf?db_be=".$backend_db."&refno=".$refno;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==',
                'Cookie: userLocale=en_US; JSESSIONID=5221928B4926B138CB796C763F550CB4'
            ),
        ));
            
        $response = curl_exec($curl);

        header('Content-type:application/pdf');
        header('Content-Disposition: inline; filename='.$filename.'.pdf');
        echo $response; 

        curl_close($curl); 
    }
    
}
?>