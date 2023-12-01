<?php
defined('BASEPATH') or exit('No direct script access allowed');

class login_c extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->model('login_model');
        $this->load->library('user_agent');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        //$this->load->library('session');

    }

    public function index()
    {
        if ($_SERVER['HTTPS'] !== "on") {
            $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            header("Location: $url");
        }

        $sessiondata = array(
            'userid' => '',
            'userpass' => '',
            'module_group_guid' => '',
        );

        $this->session->set_userdata($sessiondata);
        $this->load->view('login');
        //     $this->panda->load('index', 'login');
    }

    function logout()
    {

        $this->session->sess_destroy();
        redirect('login_c');
    }

    public function check()
    {

        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        } else {
            $userid = $this->input->post('userid');
            $password = addslashes($this->input->post('password'));

            $result = $this->login_model->check_login($userid, $password);

            if($result->num_rows() == '0')
            {
                $this->session->set_flashdata('message','Invalid User ID / Password. <br>&nbsp; Please verify and try again.');
                redirect('login_c');
            }
            else if($result->row('isactive') == '0')
            {
                $this->session->set_flashdata('message','User Account Deactive. <br>&nbsp; Please contact support team.');
                redirect('login_c');
            }
            else if($result->row('isactive') == '9')
            {
                $this->session->set_flashdata('message','User Account Incomplete. <br>&nbsp; Please contact support team.');
                redirect('login_c');
            } 
            else
            {

                if ($result->row('user_group_name') == 'SUPP_ADMIN' || $result->row('user_group_name') == 'SUPP_CLERK' || $result->row('user_group_name') == 'LIMITED_SUPP_ADMIN') {
                    // $user_guid = $result->row('user_guid');
    
                    $check_supplier = $this->db->query("SELECT a.*,b.* FROM set_supplier_user_relationship AS a INNER JOIN set_supplier AS b ON a.`supplier_guid` = b.supplier_guid WHERE user_guid = '" . $result->row('user_guid') . "'");
    
                    $total_supplier = $check_supplier->num_rows();
                    $i = 0;
                    foreach ($check_supplier->result() as $row) {
                        // echo $row->supplier_group_guid.'<br>'; 
                        if ($row->isactive == 0) {
                            $i++;
                        }
                    }
                    if ($total_supplier > 0) {
                        if ($i == $total_supplier) {
                            $this->session->set_flashdata('message', 'Company Inactive! Please contact Support!');
                            redirect('login_c');
                        }
                    }
                    // if($check_supplier->row('suspended') == '1')
                    // {
                    //     $this->session->set_flashdata('message', 'Company Suspended! Please contact Support!');
                    //     redirect('login_c');
                    // };
    
                };
                //  echo $this->db->last_query();die;
                if ($result->num_rows() > 0) {
                    $browser = $this->agent->browser();
                    $ip_addr = $this->input->ip_address();
                    $this->db->query("REPLACE INTO user_logs SELECT UPPER(REPLACE(UUID(), '-', '')), '" . $result->row('user_guid') . "', '$userid', now(), '$ip_addr', '$browser'");
                    $check_userlog = $this->db->query("SELECT * from user_logs where user_guid = '" . $result->row('user_guid') . "'");
    
                    //set the session variables
                    $sessiondata = array(
                        'userid' => $userid,
                        'user_logs' => $check_userlog->row('user_logs_guid'),
                        'location' => '',
                        'user_guid' => $result->row('user_guid'),
                        'user_group_name' => $result->row('user_group_name'),
                        'module_group_guid' => $result->row('module_group_guid'),
                        'isenable' => $result->row('isenable'),
                        'loginuser' => TRUE,
                    );
                    $this->session->set_userdata($sessiondata);
                    $this->panda->get_uri();
    
                    redirect('login_c/customer_setsession');
                }
            }
        }
    }

    public function password()
    {
        if ($this->session->userdata('loginuser') == true) {
            $this->load->view('header');
            $this->load->view('changepassword');
            $this->load->view('footer');
        } else {
            redirect('#');
        }
    }

    public function submit_password()
    {
        if ($this->session->userdata('loginuser') == true) {
            $this->panda->get_uri();
            $prev_pass = $this->input->post('prev_password');
            $new_pass = $this->input->post('new_password');
            $confirm_password = $this->input->post('confirm_password');
            $user_guid = $this->session->userdata('user_guid');

            if ($new_pass != $confirm_password) {
                $this->session->set_flashdata('warning', 'New Password and Confirm Password does not match!');
                redirect('login_c/password');
            };

            // print_r($this->session->userdata());die;
            $old_password = $this->db->query("SELECT * FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid LIMIT 1");
            $prev_password = $this->db->query("SELECT md5('$prev_pass') as prev_pass");
            // echo $this->db->last_query();die;
            $old_passwords = $old_password->row('user_password');
            $prev_passwords = $prev_password->row('prev_pass');
            if ($prev_passwords != $old_passwords) {
                $this->session->set_flashdata('message', 'Old Password Wrong');
                redirect('login_c/password');
            }



            /* if($prev_pass != $_SESSION['userpass'])
            {
              $this->session->set_flashdata('warning', 'Previous Password does not match login password! Please redo.');
                redirect('login_c/password');
            };*/

            $check_module = $this->db->query("SELECT acc_module_group_guid FROM acc_module_group WHERE acc_module_group_name = 'Panda B2B'")->row('acc_module_group_guid');

            $this->db->query("UPDATE set_user set user_password = md5('$confirm_password'),updated_by = '" . $_SESSION['userid'] . "',updated_at = NOW() where user_guid = '$user_guid' and module_group_guid = '$check_module'");
            $new_passwords = $this->db->query("SELECT md5('$confirm_password') as new_pass")->row('new_pass');

            $this->db->query("INSERT INTO reset_pwd_self (transaction_guid,user_guid,from_value,to_value,created_by,created_at) SELECT UPPER(REPLACE(UUID(), '-', '')), '$user_guid','$old_passwords','$new_passwords','" . $_SESSION['userid'] . "',now()");

            // echo $this->db->last_query();die;

            $_SESSION['userpass'] = $confirm_password;

            $this->session->set_flashdata('message', 'Password Updated');
            redirect('login_c/password');
        } else {
            redirect('#');
        }
    }

    public function customer()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
            if ($_SERVER['HTTPS'] !== "on") {
                $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                header("Location: $url");
            }

            $requiredSessionVar = array('userid', 'userpass', 'location', 'user_guid', 'user_group_name', 'module_group_guid', 'isenable', 'loginuser', 'isHQ', 'query_loc', 'user_logs');
            foreach ($_SESSION as $key => $value) {
                if (!in_array($key, $requiredSessionVar)) {
                    unset($_SESSION[$key]);
                }
            }

            if ($_SESSION['user_group_name'] == 'SUPER_ADMIN') {
                $get_customer = $this->db->query("SELECT *
                    FROM(
                    SELECT DISTINCT a.logo,a.acc_guid,a.acc_name,a.seq,'' AS register_guid,a.row_seq, a.maintenance,DATE_FORMAT(a.maintenance_date,'%d-%m-%Y') AS maintenance_date
                    FROM `acc` AS a
                    WHERE isactive = 1 
                    
                    UNION ALL
                    
                    SELECT 
                    aa.logo,
                    aa.acc_guid,
                    aa.acc_name,
                    aa.seq,
                    GROUP_CONCAT(aa.register_guid) AS register_guid,
                    aa.row_seq,
                    '0' AS maintenance,
                    '' AS maintenance_date
                    FROM(
                    SELECT 'https://file.xbridge.my/b2b-img/app/customer/cks/cks_2.png' AS logo,
                    'B00CA0BE403611EBA2FC000D3AC8DFD7' AS acc_guid,
                    'CHUA KAH SENG SUPERMARKET SDN BHD' AS acc_name,
                    '4' AS seq,
                    CONCAT('https://b2b.xbridge.my/index.php/Supplier_registration/register_form_edit?link=',c.register_guid,'&platform=web') AS register_guid,
                    '6' AS row_seq
                    FROM lite_b2b.set_user AS a
                    INNER JOIN lite_b2b.set_supplier_user_relationship AS b
                    ON a.user_guid = b.user_guid
                    AND a.acc_guid = b.customer_guid
                    INNER JOIN lite_b2b.register_new AS c
                    ON b.supplier_guid = c.supplier_guid
                    AND c.customer_guid = 'B00CA0BE403611EBA2FC000D3AC8DFD7'
                    WHERE a.user_id = '" . $_SESSION['userid'] . "'
                    AND c.form_status IN ('Send','Save-Progress')
                    AND a.acc_guid IN('1F90F5EF90DF11EA818B000D3AA2CAA9','D361F8521E1211EAAD7CC8CBB8CC0C93','907FAFE053F011EB8099063B6ABE2862')
                    GROUP BY b.supplier_guid,c.register_guid)aa
                    ) AS aa
                    WHERE aa.acc_guid IS NOT NULL
                    GROUP BY aa.acc_guid,aa.seq,aa.row_seq
                    ORDER BY aa.seq ASC,aa.row_seq ASC");
            } else {

                // $get_customer = $this->db->query("SELECT DISTINCT d.logo,c.acc_guid, acc_name 
                // FROM `set_user` AS a 
                // INNER JOIN `acc_branch` AS b 
                // ON a.branch_guid = b.branch_guid 
                // INNER JOIN `acc_concept` AS c
                // ON b.concept_guid = c.concept_guid
                // INNER JOIN acc AS d
                // ON d.`acc_guid` = c.`acc_guid`
                //  where user_id = '".$_SESSION['userid']."' and d.isactive = '1' and module_group_guid = '".$_SESSION['module_group_guid']."' order by acc_name asc");

                $get_customer = $this->db->query("SELECT *
                    FROM(
                    SELECT DISTINCT e.logo,d.acc_guid,e.acc_name,e.seq,'' AS register_guid,e.row_seq,e.maintenance,DATE_FORMAT(e.maintenance_date,'%d-%m-%Y') AS maintenance_date
                    FROM `set_user` AS a 
                    INNER JOIN `set_user_branch` b ON a.user_guid = b.user_guid
                    INNER JOIN `acc_branch` c ON b.branch_guid = c.branch_guid 
                    INNER JOIN `acc_concept` d ON c.concept_guid = d.concept_guid 
                    INNER JOIN `acc` e ON d.`acc_guid` = e.`acc_guid` 
                    where a.user_id = '" . $_SESSION['userid'] . "' 
                    and e.isactive = '1' 
                    AND a.isactive = '1' 
                    and a.module_group_guid = '" . $_SESSION['module_group_guid'] . "' 

                    UNION ALL
                    
                    SELECT 
                    aa.logo,
                    aa.acc_guid,
                    aa.acc_name,
                    aa.seq,
                    GROUP_CONCAT(aa.register_guid) AS register_guid,
                    aa.row_seq,
                    '0' AS maintenance,
                    '' AS maintenance_date
                    FROM(
                    SELECT 'https://file.xbridge.my/b2b-img/app/customer/cks/cks_2.png' AS logo,
                    'B00CA0BE403611EBA2FC000D3AC8DFD7' AS acc_guid,
                    'CHUA KAH SENG SUPERMARKET SDN BHD' AS acc_name,
                    '4' AS seq,
                    CONCAT('https://b2b.xbridge.my/index.php/Supplier_registration/register_form_edit?link=',c.register_guid,'&platform=web') AS register_guid,
                    '6' AS row_seq
                    FROM lite_b2b.set_user AS a
                    INNER JOIN lite_b2b.set_supplier_user_relationship AS b
                    ON a.user_guid = b.user_guid
                    AND a.acc_guid = b.customer_guid
                    INNER JOIN lite_b2b.register_new AS c
                    ON b.supplier_guid = c.supplier_guid
                    AND c.customer_guid = 'B00CA0BE403611EBA2FC000D3AC8DFD7'
                    WHERE a.user_id = '" . $_SESSION['userid'] . "' 
                    AND a.isactive = '1' 
                    AND c.form_status IN ('Send','Save-Progress')
                    AND a.acc_guid IN('1F90F5EF90DF11EA818B000D3AA2CAA9','D361F8521E1211EAAD7CC8CBB8CC0C93','907FAFE053F011EB8099063B6ABE2862')
                    GROUP BY b.supplier_guid,c.register_guid)aa
                    ) AS aa
                    WHERE aa.acc_guid IS NOT NULL
                    GROUP BY aa.acc_guid,aa.seq,aa.row_seq
                    ORDER BY aa.seq ASC,aa.row_seq ASC");

                // echo $this->db->last_query();die;

            }

            //echo var_dump($get_customer->result()); die;
            // echo $this->db->last_query();die;
            $data = array(
                'customer' => $get_customer,
            );

            $this->load->view('header');
            $this->load->view('customer', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired. Please relogin');
            redirect('#');
        }
    }


    public function customer_setsession()
    {
        if ($this->session->userdata('loginuser') == true) {
            // echo '123';
            //$customer_guid = $this->input->post('customer');
            $customer_guid = $this->db->query("SELECT acc_guid FROM lite_b2b.acc WHERE isactive = '1' LIMIT 1")->row('acc_guid');
            $blocked_guid = $this->input->post('blocked_guid');
            if ($blocked_guid != '') {
                $blocked_guid = explode(",", $blocked_guid);
                $blocked_guid = implode("','", $blocked_guid);
                $blocked_guid = "'" . $blocked_guid . "'";
                $query_blocked = "AND b.supplier_guid NOT IN ($blocked_guid)";
            }

            $query_set_user_group = $this->db->query("SELECT b.user_group_name 
            FROM lite_b2b.set_user a 
            INNER JOIN lite_b2b.set_user_group b 
            ON a.user_group_guid = b.user_group_guid 
            WHERE a.user_id = '" . $_SESSION['userid'] . "'
            AND a.acc_guid = '$customer_guid'")->row('user_group_name');

            // echo $customer_guid;die;
            // $get_loc = $this->db->query("SELECT distinct branch_code, branch_name from  set_user as a inner join  acc_branch as b on a.branch_guid = b.branch_guid where user_id = '".$_SESSION['userid']."' and a.isactive = '1' and module_group_guid = '".$_SESSION['module_group_guid']."' order by branch_code asc");
            $get_loc = $this->db->query("SELECT distinct c.branch_code, c.branch_name from set_user a inner join set_user_branch b ON a.user_guid = b.user_guid inner join acc_branch c on b.branch_guid = c.branch_guid where a.user_id = '" . $_SESSION['userid'] . "' and a.isactive = '1' and a.module_group_guid = '" . $_SESSION['module_group_guid'] . "' and b.acc_guid = '$customer_guid' AND c.isactive = '1' order by branch_code asc");
            // echo $this->db->last_query();die;

            // echo var_dump($get_loc->result()); die;
            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {

                array_push($hq_branch_code_array, $key->branch_code);
            }

            foreach ($get_loc->result() as  $row) {
                $check_HQ[] = $row->branch_code;
            }

            if (!array_diff($hq_branch_code_array, $check_HQ)) {

                $sessiondata = array(
                    'isHQ' => '1',
                );
                $this->session->set_userdata($sessiondata);
            } else {
                $sessiondata = array(
                    'isHQ' => '0',
                );
                $this->session->set_userdata($sessiondata);
            }

            $sessiondata = array(
                'customer_guid' => $customer_guid,
                /*'customer' => $this->db->query("SELECT acc where")*/
                'show_side_menu' => '1',
            );
            $this->session->set_userdata($sessiondata);


            foreach ($check_HQ as &$value) {
                $value = "'" . trim($value) . "'";
            }
            $query_loc = implode(',', array_filter($check_HQ));
            $sessiondata = array(
                'query_loc' => $query_loc,
            );
            $this->session->set_userdata($sessiondata);


            $userid = $_SESSION['userid'];
            $password = $_SESSION['userpass'];
            $result = $this->login_model->check_module($userid, $password);

            foreach ($result->result() as $row) {
                $module_code[] = $row->module_code;
            }

            $_SESSION['module_code'] = $module_code;
            // print_r($module_code);die;
            if (in_array("C1MS", $module_code)) {
                $_SESSION['system_admin'] = 1;
            } else {
                $_SESSION['system_admin'] = 0;
            }
            //@@@@@@@@@@@@@@@@@ end module_name session @@@@@@@@@@@@@@@@@@@@@@@   
            /* echo var_dump($_SESSION['module_code']);
            die;*/

            $acc_table = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid' AND isactive = 1 ");

            $session_data = array(
                'idle_time' => $acc_table->row('idle_time'),
            );

            $sessiondata = array(
                'user_group_name' => $query_set_user_group,
            );
            $this->session->set_userdata($sessiondata);

            if (!in_array('IAVA', $_SESSION['module_code'])) {
                $query_supcode = $this->db->query("SELECT distinct backend_supplier_code from set_supplier_user_relationship as a inner join set_supplier_group as b on a.supplier_group_guid = b.supplier_group_guid $query_blocked INNER JOIN set_supplier c ON a.supplier_guid = c.supplier_guid where a.user_guid = '" . $_SESSION['user_guid'] . "' AND b.customer_guid = '" . $_SESSION['customer_guid'] . "' AND c.isactive = 1");

                if ($query_supcode->num_rows() == 0) {
                    //echo '<script>alert("Company inactive, Please contact our support!");window.location.href = "'.site_url('login_c/customer').'";</script>;';die;

                    $data = array(
                        'para' => '0',
                        'msg' => 'Company inactive, Please contact our support!',
                    );
                    echo json_encode($data);
                    exit();
                    // window.location.href = "http://www.w3schools.com";
                    // redirect(site_url('login_c/customer'));
                }
            } else {
                $query_supcode = $this->db->query("SELECT distinct backend_supplier_code from set_supplier_user_relationship as a inner join set_supplier_group as b on a.supplier_group_guid = b.supplier_group_guid $query_blocked where a.user_guid = '" . $_SESSION['user_guid'] . "' AND b.customer_guid = '" . $_SESSION['customer_guid'] . "'");
            }
            // $query_supcode = $this->db->query("SELECT distinct backend_supplier_code from set_supplier_user_relationship as a inner join set_supplier_group as b on a.supplier_group_guid = b.supplier_group_guid where a.user_guid = '".$_SESSION['user_guid']."'");

            if (!in_array('IAVA', $_SESSION['module_code'])) {
                $query_consign_supcode = $this->db->query("SELECT distinct backend_supplier_code from set_supplier_user_relationship as a inner join set_supplier_group as b on a.supplier_group_guid = b.supplier_group_guid $query_blocked inner join b2b_summary.supcus c on b.backend_supcus_guid = c.supcus_guid INNER JOIN lite_b2b.set_supplier c ON a.supplier_guid = c.supplier_guid where a.user_guid = '" . $_SESSION['user_guid'] . "' and b.customer_guid = '" . $_SESSION['customer_guid'] . "' and c.consign = 1 and c.isactive = 1");
            } else {
                $query_consign_supcode = $this->db->query("SELECT distinct backend_supplier_code from set_supplier_user_relationship as a inner join set_supplier_group as b on a.supplier_group_guid = b.supplier_group_guid $query_blocked inner join b2b_summary.supcus c on b.backend_supcus_guid = c.supcus_guid where a.user_guid = '" . $_SESSION['user_guid'] . "' and c.consign = 1 AND b.customer_guid = '" . $_SESSION['customer_guid'] . "' ");
            }
            // echo $this->db->last_query();die;
            // print_r($query_consign_supcode->result());die;
            // $query_consign_supcode = $this->db->query("SELECT distinct backend_supplier_code from set_supplier_user_relationship as a inner join set_supplier_group as b on a.supplier_group_guid = b.supplier_group_guid inner join b2b_summary.supcus c on b.backend_supcus_guid = c.supcus_guid where a.user_guid = '".$_SESSION['user_guid']."' and c.consign = 1");

            /* $get_loc = $this->db->query("SELECT distinct branch_code, branch_name from  set_user as a inner join  acc_branch as b on a.branch_guid = b.branch_guid where user_id = '".$_SESSION['userid']."' and user_password = '".$_SESSION['userpass']."' and a.isactive = '1' and module_group_guid = '".$_SESSION['module_group_guid']."' order by branch_code asc");
            */
            // echo $this->db->last_query();
            $check_supcode = array();
            foreach ($query_supcode->result() as  $row) {
                $check_supcode[] = $row->backend_supplier_code;
            }
            // print_r($check_supcode);die;
            $check_supcode_without_quote =  $check_supcode;
            foreach ($check_supcode as &$value) {
                $value = "'" . trim($value) . "'";
            }
            $query_supcode = implode(',', array_filter($check_supcode));

            $check_consign_supcode = array();
            foreach ($query_consign_supcode->result() as  $row2) {
                $check_consign_supcode[] = $row2->backend_supplier_code;
            }
            // print_r($check_supcode);die;

            foreach ($check_consign_supcode as &$value) {
                $value = "'" . trim($value) . "'";
            }
            $query_consign_supcode = implode(',', array_filter($check_consign_supcode));

            $other_doc = $this->db->query("SELECT * FROM other_doc_setting WHERE customer_guid = '$customer_guid' AND isactive = 1 ORDER BY seq ASC");
            $check_user_group = $this->db->query("SELECT * FROM set_user WHERE acc_guid = '$customer_guid' AND user_guid = '" . $_SESSION['user_guid'] . "' LIMIT 1");
            $sessiondata = array(
                'query_supcode' => $query_supcode,
                'query_consign_supcode' => $query_consign_supcode,
                'other_doc' => $other_doc->result(),
                'check_supcode_without_quote' => $check_supcode_without_quote,
                'user_group_guid' => $check_user_group->row('user_group_guid'),
                'query_blocked' => $blocked_guid,
            );
            $this->session->set_userdata($sessiondata);


            /* push data to populate client server data*/
            /*rexbridge to throw query and just return result start*/
            // $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            // $to_shoot_url = $check_url."/temp_data";

            // $customer_guid = $_SESSION['customer_guid'];
            // $user_guid = $_SESSION['user_guid'];
            // $session_guid = $_SESSION['user_logs'];

            // if($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN')
            // {
            //         $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@user_guid', \"'".$user_guid."'\"),'@customer_guid', \"'".$customer_guid."'\" ), '@session_guid',  \"'".$session_guid."'\")  AS query 
            //         FROM restful_query WHERE title = 'jasper_temporary_super_data'")->row('query');
            // }
            // else
            // {
            //         $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@user_guid', \"'".$user_guid."'\"),'@customer_guid', \"'".$customer_guid."'\" ), '@session_guid',  \"'".$session_guid."'\")  AS query 
            //         FROM restful_query WHERE title = 'jasper_temporary_data'")->row('query');    
            // }
            // // $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(query, '@customer_guid', '$customer_guid'), '@user_guid' , '$user_guid'), '@session_guid', '$session_guid')  AS query FROM restful_query WHERE title = 'jasper_temporary_data'")->row('query');
            // $rb_query = $this->db->query($replace_var)->result();
            // // echo var_dump($replace_var);die;

            // $rb_query = $this->db->query("SELECT @session_guid AS session_guid, @user_guid AS user_guid, 'location' AS `field`, branch_code AS `value`
            //         FROM (
            //         SELECT branch_code FROM
            //         (SELECT * FROM lite_b2b.set_user WHERE user_id = 'super'
            //         GROUP BY branch_guid) a
            //         INNER JOIN  (SELECT * FROM lite_b2b.`acc_branch` WHERE isactive = '1' )b
            //         ON a.branch_guid = b.branch_guid
            //         ) location")->result();

            // //$to_shoot_url = "http://192.168.10.235/panda_api/index.php/return_json/temp_data";
            // $fields_string = json_encode($rb_query);

            // $data = array(
            //     'restful_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(), '-', '')) as guid")->row('guid'),
            //     'customer_guid' => $_SESSION['customer_guid'],
            //     'session_guid' => $_SESSION['user_logs'],
            //     'user_guid' => $_SESSION['user_guid'],
            //     'json_string' => $fields_string,
            //     'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
            // );
            // $this->db->insert('lite_b2b.restful_log', $data);


            //  $ch = curl_init($to_shoot_url); 
            //    // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            //     curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            //     curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            //     $result = curl_exec($ch);

            //     //close connection
            //     curl_close($ch);    

            // $get_return_msg = json_decode($result);

            //   if($result)
            //    {
            //         echo $get_return_msg['message'];  
            //    }
            //    else
            //    {
            //         echo $get_return_msg['message'];  
            //    }

            /*end*/


            $userid = $_SESSION['userid'];
            $password = $_SESSION['userpass'];
            $result = $this->login_model->check_module($userid, $password);
            //@@@@@@@@@@@@@@@@@@@@@@ module_name session @@@@@@@@@@@@@@@@@@@@@@@@@@@@
            // echo $this->db->last_query();die;

            // $check_acc_user = $this->login_model->check_module($userid, $password);
            // if($check_acc_user->row('module_code') == 'C1MS')
            // {
            //     $_SESSION['system_admin'] = 1;
            //    // redirect("Acc_master_setup");
            // }
            // else
            // {
            //     $_SESSION['system_admin'] = 0;
            //    // redirect("Module_setup");
            // }

            foreach ($result->result() as $row) {
                $module_code[] = $row->module_code;
            }

            $_SESSION['module_code'] = $module_code;
            // print_r($module_code);die;
            if (in_array("C1MS", $module_code)) {
                $_SESSION['system_admin'] = 1;
            } else {
                $_SESSION['system_admin'] = 0;
            }
            //@@@@@@@@@@@@@@@@@ end module_name session @@@@@@@@@@@@@@@@@@@@@@@   
            /* echo var_dump($_SESSION['module_code']);
                    die;*/
            $this->panda->get_uri();
            if (in_array('DASH', $_SESSION['module_code'])) {
                redirect('dashboard');
                //$redirect = 'dashboard';

            } else {
                redirect('panda_home');
                //$redirect = 'panda_home';
            };


            // $data = array(
            //     'para' => '1',
            //     'redirect' => $redirect,
            // );
            // echo json_encode($data);
        } else {
            redirect('#');
        }
    }

    public function location()
    {
        $this->General_model->check_router();
        if ($this->session->userdata('loginuser') == true) {

            if ($_SERVER['HTTPS'] !== "on") {
                $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                header("Location: $url");
            }
            $get_loc = $this->db->query("SELECT aa.*,bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '" . $_SESSION['customer_guid'] . "' AND a.branch_code IN (" . $_SESSION['query_loc'] . ") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '" . $_SESSION['customer_guid'] . "') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC,branch_code ASC");

            //             $get_loc = $this->db->query("SELECT * FROM acc_branch WHERE branch_code IN (".$_SESSION['query_loc'].") 
            // AND  concept_guid IN (SELECT concept_guid FROM acc_concept WHERE acc_guid = '".$_SESSION['customer_guid']."') and isactive = '1'
            // ORDER BY branch_name ASC ");
            // echo $this->db->last_query();die;

            /* $location_data = $this->db->query("SELECT branch_code from acc_branch where branch_guid in ('$branch_guid[]')");*/
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');


            $data = array(
                'location' => $get_loc,
            );
            unset($_SESSION['from_other']);
            //
            $this->load->view('header');
            $this->load->view('location', $data);
            $this->load->view('footer');
        } else {
            redirect('login_c');
        }
    }

    public function location_setsession()
    {
        if ($this->session->userdata('loginuser') == true) {
            // echo 1;die;

            /* $get_loc = $this->db->query("SELECT distinct branch_code, branch_name from set_user as a inner join acc_branch as b on a.branch_guid = b.branch_guid where user_id = '".$_SESSION['userid']."' and user_password = '".$_SESSION['userpass']."' and a.isactive = '1' and module_group_guid = '".$_SESSION['module_group_guid']."' order by branch_code asc"); */
            $session_reminder_block_id = $_SESSION['query_blocked'];
            $get_location = $this->input->post('location');
            $get_customer_guid = $_SESSION['customer_guid'];

            //check for QRA location 
            if ($get_customer_guid == '3C475C473DB311EBB4F2AEF59F86279D') {
                if ($get_location == 'QFHQ') {
                    $get_location = 'HQ';
                }
            }

            if (($session_reminder_block_id != '') && ($session_reminder_block_id != 'null') && ($session_reminder_block_id != null)) {
                $inner_join = "AND b.supplier_guid NOT IN ($session_reminder_block_id)";
            }

            $query_supcode = $this->db->query("SELECT distinct backend_supplier_code from set_supplier_user_relationship as a
            inner join set_supplier_group as b 
            on a.supplier_group_guid = b.supplier_group_guid $inner_join INNER JOIN set_supplier c ON a.supplier_guid = c.supplier_guid where a.user_guid = '" . $_SESSION['user_guid'] . "' and b.customer_guid = '" . $_SESSION['customer_guid'] . "' and isactive = 1");

            /* $get_loc = $this->db->query("SELECT distinct branch_code, branch_name from  set_user as a inner join  acc_branch as b on a.branch_guid = b.branch_guid where user_id = '".$_SESSION['userid']."' and user_password = '".$_SESSION['userpass']."' and a.isactive = '1' and module_group_guid = '".$_SESSION['module_group_guid']."' order by branch_code asc");
            */

            foreach ($query_supcode->result() as  $row) {
                $check_supcode[] = $row->backend_supplier_code;
            }

            foreach ($check_supcode as &$value) {
                $value = "'" . trim($value) . "'";
            }
            $query_supcode = implode(',', array_filter($check_supcode));
            $sessiondata = array(
                'query_supcode' => $query_supcode,
            );
            $this->session->set_userdata($sessiondata);

            $this->panda->get_uri();
            redirect($_SESSION['frommodule'] . "?loc=" . $get_location . '&first=1');
        } else {
            redirect('#');
        }
    }

    public function outside_view_statement()
    {
        if ($this->session->userdata('loginuser') == true) {
            $customer_guid = $this->input->post('customer_guid');
            //print_r($customer_guid); die;
            $sessiondata = array(
                'customer_guid' => $customer_guid,
            );
            $this->session->set_userdata($sessiondata);

            // $userid = $_SESSION['userid'];
            // $password = $_SESSION['userpass'];
            // $result = $this->login_model->check_module($userid, $password);

            // foreach($result->result() as $row)
            // {   
            //     $module_code[] = $row->module_code;
            // }

            // $_SESSION['module_code'] = $module_code;

            $data = array(
                'para1' => 1,
            );
            echo json_encode($data);
        } else {
            redirect('#');
        }
    }
}
