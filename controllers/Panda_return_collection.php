<?php
class panda_return_collection extends CI_Controller
{
   public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        //load the department_model
        $this->load->model('GR_model');
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {

            //things to do
            // enable return policy date based on company
            // setting to be done at xbridge?s
            // enable customized date return for each supplier
            $this->panda->get_uri();
            $setsession = array(
                'frommodule' => 'panda_return_collection',
                );
            $this->session->set_userdata($setsession);

            //force check prdn created by no action
            // $get_prdn = $this->db->query("SELECT * FROM b2b_summary.dbnote_batch WHERE customer_guid = '".$_SESSION['customer_guid']."' AND converted_by != '' AND status <3");

            // foreach($get_prdn->result() as $row)
            // {
            //     $this->db->query("UPDATE b2b_summary.dbnote_batch set status = '3', accepted_at = now(), updated_at = now(), updated_by = 'AUTO_GENERATE' where batch_no = '".$row->batch_no."' and customer_guid = '".$_SESSION['customer_guid']."'");
            // } 

            if ($_SESSION['customer_guid'] != 'D361F8521E1211EAAD7CC8CBB8CC0C93' && $_SESSION['customer_guid'] != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $_SESSION['customer_guid'] != '8D5B38E931FA11E79E7E33210BD612D3') 
            {
                if($_SESSION['user_group_name'] != "SUPER_ADMIN" && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_TESTING_USE')
                {
                    echo '<script>alert("Stock Return Batch Document Not Yet Go Live.");window.location.href = "'.site_url('dashboard').'";</script>;';
                    die;  
                }
            }


            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            if(isset($_SESSION['from_other']) == 0 )
            { 
                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=');
            }
            else
            {
                if($_REQUEST['status'] == '')
                {
                    unset($_SESSION['from_other']);
                    redirect('panda_return_collection?loc='.$_REQUEST['loc']);
                };
                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=');
            };
         }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    /*
    to prevent user from cincai key in the refno based on url, 
    remember to join all back to user guid so that when they key by refno, it will check if the user is valid to query or not then will show result or not..
    */

    public function return_collection_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['refno'];
            $loc = $_REQUEST['loc'];
            $check_status = '';
            $request_link = '';
            $customer_guid = $this->session->userdata("customer_guid");
            $user_guid = $this->session->userdata("user_guid");

            if($refno == '')
            {
                echo 'refno hilang'; die;
            }

            $check_strb_json = $this->db->query("SELECT strb_json_info,dbnote_guid,`status`,LEFT(doc_date,7) as period_code,IF( STATUS NOT IN ('8','9') ,uploaded_image, '0') AS uploaded_image FROM b2b_summary.`dbnote_batch_info` WHERE batch_no = '$refno' AND customer_guid = '$customer_guid'");

            $get_current_status = $this->db->query("SELECT dbnote_guid,`status`,LEFT(doc_date,7) as period_code,IF( STATUS NOT IN ('8','9') ,uploaded_image, '0') AS uploaded_image FROM b2b_summary.`dbnote_batch` WHERE batch_no = '$refno' AND customer_guid = '$customer_guid'");

            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'RETURN_COLLECTION' order by reason asc");

            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");

            $uploaded_image = $get_current_status->row('uploaded_image');

            if($check_strb_json->num_rows() == 0 )
            {
                $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '$customer_guid'")->row('rest_url');
            
                $to_shoot_url = $check_url."/childdata?table=dbnotebatch_child"."&refno=".$refno;
                //echo $to_shoot_url;die;
                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                $response = curl_exec($ch);
    
                if($response !== false) 
                {
                    $get_child_detail = json_decode(file_get_contents($to_shoot_url), true);
                    $child_result_validation = $get_child_detail[0]['line']; 
                }
                else
                {
                    $get_child_detail = array();
                    $child_result_validation = '0';
                    $this->session->set_flashdata('message', 'Connection fail at customer server. Please try again in a few minutes');
                    //$this->session->set_flashdata('warning', 'Connection fail at customer server. Child Data Not Found.'); 
                }
            }
            else
            {
                $response = $check_strb_json->row('strb_json_info');
                $get_child_detail = json_decode($response, true)['dbnote_batch_c'];
                $child_result_validation = $get_child_detail[0]['line'];
                $request_link = site_url('panda_return_collection/strb_report?refno='.$refno);
            }


            if(isset($_REQUEST['edit']))
            {
                $hidden_text = 'number';
                $edit_url = site_url('panda_return_collection/return_collection_child?refno='.$_REQUEST['refno'].'&loc='.$_REQUEST['loc']);
                $hide_button = '0';
            }
            else
            {
                $hidden_text = 'hidden';
                $edit_url = site_url('panda_return_collection/return_collection_child?refno='.$_REQUEST['refno'].'&loc='.$_REQUEST['loc'].'&edit');
                $hide_button = '1';
            }

            //echo var_dump($response);die;
            //echo $child_result_validation;die;
            if($child_result_validation != '1')
            {
                $check_child = array(array(
                    'line' => 'No Records Founds',
                    'itemcode' => '',
                    'barcode' => '',
                    'description' => '',
                    'packsize' => '',
                    'um' => '',
                    'input_cost' => '0.00',
                    'reason' => '',
                    'qty' => '',
                )); 
                $set_disabled = '1';
                $this->session->set_flashdata('warning', 'Connection fail at customer server. Please try again in a few minutes');
                //redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);
            }
            else
            {
                $check_child = $get_child_detail; 
                $set_disabled = '0';
            }

            $data = array(
                'title' => 'Return Collection',
                'get_child_detail' => $get_child_detail,
                'check_child' => $check_child,
                'hidden_text' => $hidden_text,
                'edit_url' => $edit_url,
                'hide_button' => $hide_button,
                'set_code' => $set_code,
                'set_admin_code' => $set_admin_code,
                'get_current_status' => $get_current_status->row('status'),
                'stock_guid' => $get_current_status->row('dbnote_guid'),
                'uploaded_image' => $uploaded_image,
                'set_disabled' => $set_disabled,
                'request_link' => $request_link,
            );

            $this->load->view('header');       
            $this->load->view('return_collection/panda_rc_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }


    public function supplier_check()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();

            function odd($var)
            {
                return($var & 1);
            } 

            $refno = $this->input->post('h_refno');
            if($_SESSION['customer_guid'] == '' )
            {
                $this->session->set_flashdata('warning','Session Expired! Please relogin' );
                redirect('#');
            };
        
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('H_refno');
            $loc = $this->input->post('location');

            $supcheck = $this->input->post("supcheck2[]");
            $check_loop = array_filter($supcheck, "odd"); 
            if(empty($check_loop))
            {   
                $this->session->set_flashdata('warning',  'Please acknowledge item in item list.');
                //echo $refno;die;
                redirect('panda_return_collection/return_collection_child?refno='.$refno.'&loc='.$loc);
            }

            $customer_guid = $_SESSION['customer_guid']; 
            $refno = $_REQUEST['refno'];

            $itemcode = $this->input->post("itemcode[]");
            $supcheck = $this->input->post("supcheck2[]");
            $line = $this->input->post("line[]");
            $barcode = $this->input->post("barcode[]");
            $description = $this->input->post("description[]");
            $packsize = $this->input->post("packsize[]");
            $qty = $this->input->post("qty[]");
            $um = $this->input->post("um[]");
            $input_cost = $this->input->post("input_cost[]");
            $proposed_lastcost = $this->input->post("lastcost_new[]");

            $header_data = array (
                'customer_guid' => $_SESSION['customer_guid'],
                'dbnote_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                'batch_no' => $refno,
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['user_id'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['user_id'],
                /*'sup_code' => $_SESSION['customer_guid'],*/
            );

            $this->db->insert('return_collection', $header_data);
            // create main
            $this->session->set_flashdata('message',  'Header created');
            redirect('panda_return_collection/return_collection_child?refno='.$refno.'&loc='.$loc);


        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

 
    public function show_report()
    {

        if(isset($_REQUEST['report_type']) && isset($_REQUEST['guid']))
        {

            $report_type = $_REQUEST['report_type'];
            $guid = $_REQUEST['guid'];
            $customer_guid = $this->session->userdata('customer_guid');
            if($customer_guid == '13EE932D98EB11EAB05B000D3AA2838A')
            {
                $guid = 'EE38242259E411E98AD9B4B686DDC399';
            }
            $from_module = 'panda_return_collection';
            // echo die; 

            $ver = 0;
            if($ver == 0)
            {
                $db_be = 'backend';
                $db_re = 'backend';
                $jasper_url = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'")->row('jasper_url');
            }
            else
            {
                $db_be = '';
                $db_re = '';
                $jasper_url = '127.0.0.1';
            }
            if($jasper_url == '' || $jasper_url == null)
            {
                echo 'Link not found, Please contact our admin';die; 
            }
            // echo $this->db->last_query();die;
            // print_r($jasper_url);die;
            $report_template = $this->db->query("SELECT * FROM jasper_report_template WHERE report_type = '$report_type'")->row('report_template');
            // echo $this->db->last_query();die;
            // print_r($report_template);die;
            // $report_ip = $jasper_url.'/jasperserver/rest_v2/reports/reports/B2BReports/'.$report_template.'.pdf';
            $report_ip = $jasper_url.'/jasperserver/rest_v2/reports/reports/B2BReports/'.$report_template.'.pdf'.'?guid='.$guid.'&db_be='.$db_be.'&db_re='.$db_re;
            
            // if($customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93')
            // {
            //     echo 'Under Maintenance'; die;
            // }

            
            $url = $report_ip;
            // echo 'report_guid = '.$report_guid.'<br>';
            // echo 'Date_From = '.$Date_From.'<br>';
            // echo 'Date_To = '.$Date_To.'<br>';
            // echo 'main_loc = '.$main_loc.'<br>';
            // echo 'Consign_code = '.$Consign_code.'<br>';

            $curl = curl_init();

            $url = $url;
            // $url = 'http://18.139.87.215:58080/jasperserver/rest_v2/reports/reports/B2BReports/Consignment_Sales_Report.pdf?db_crm=backend_member&db_be=backend&db_fe=frontend&user_guid=DDEF6C89006B11EA84CD000D3AA2838A&session_user_guid=2758C350F63311EABF16000D3AA2838A&db_b2b=panda_b2b&db_backend=backend_member&db_member=frontend&db_frontend=crm&Date_From=2020-07-01&Date_To=2020-07-02';
            // echo $url;die;

            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_HTTPHEADER => array(
                "Cache-Control: public",
                "Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==",
                "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
              ),
            ));

            // if ($type == 'download') {
            //     $disposition = 'attachment';
            // } elseif($type == 'view') {
            //     $disposition = 'inline';
            // } else {
            //     echo 'Variable type not found';die;
            // }

            $response = curl_exec($curl);
            header('Content-type: ' . 'application/pdf');
            header('Content-Disposition: ' .$disposition.'; filename=STRB.pdf');
            echo $response; die;

            curl_close($curl);     
        
        }
        else
        {   
            echo 'Please select input.';
            exit();
        }

    }//close jasper_report_multiple_loc 

    public function strb_report()
    {
        $refno = $_REQUEST['refno'];
        $customer_guid = $_SESSION['customer_guid'];

        $url = $this->jasper_ip . "/jasperserver/rest_v2/reports/reports/PandaReports/Backend_PO/Stock_Return_Batch_Json.pdf?refno=".$refno."&customer_guid=".$customer_guid; // po

        //print_r($url); die;
        $check_code = $this->db->query("SELECT sup_code from b2b_summary.dbnote_batch where batch_no = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('sup_code');

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'b2b_strb'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'b2b_strb'")->row('query');

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

    public function strb_view_image()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $customer_guid = $this->session->userdata("customer_guid");
            $outlet = $this->input->post('outlet');
            $refno = $this->input->post('refno');
            //$period_code = date('Ym', strtotime($this->input->post('period_code')));
            $doc_type = $this->input->post('image_type');
            $action = 'list';
            if($outlet == '')
            {
                $outlet = $this->db->query("SELECT `location` FROM b2b_summary.dbnote_batch WHERE batch_no = '$refno' AND customer_guid = '$customer_guid' ")->row('location');
            }

            $period_code = $this->db->query("SELECT REPLACE(LEFT(created_at,7),'-','') as period_code FROM b2b_summary.dbnote_batch WHERE batch_no = '$refno' AND customer_guid = '$customer_guid' ")->row('period_code');

            $azure_container_name = $this->db->query("SELECT azure_container_name FROM lite_b2b.acc WHERE acc_guid = '$customer_guid' AND isactive = '1'")->row('azure_container_name');
    
            if($azure_container_name == '' || $azure_container_name == 'null' || $azure_container_name == null)
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Invalid ERR01. Please Contact Admin',

                );
                echo json_encode($data);
                exit();
            }

            $azure_directory_path = $outlet . "/" . $doc_type . "/" . $period_code . "/" . $refno ."/";
            //$azure_directory_path = '1017/STRB/202204/1017DNB22040004';
            //print_r($azure_directory_path); die;

            $to_shoot_url = 'https://api3.xbridge.my/api/token/';

            //echo $to_shoot_url;die;
            $data = array();

            //id - pass
            $data = array(
                'username' => 'panda',
                'password' => '&_)GZh9Kd?D6gHRu',
            );

            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456", "Content-Type: application/json")); // content-type important
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // need use json_encode
            $result = curl_exec($ch);
            //$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $output = json_decode($result);

            curl_close($ch);

            if (isset($output->refresh) || isset($output->access)) {
                $token_access = $output->access;
                $token_refresh = $output->refresh;
            } else {
                //insert_log
                $token_access = '';
                $token_refresh = '';
            }

            //print_r($token_access); die;

            $to_shoot_url2 = 'https://api3.xbridge.my/azure/upload/';
            //echo $to_shoot_url2;die;
            $data2 = array();

            $data2 = array(
                'file' => '',
                'action' => 'list',
                'azure_directory_path' => $azure_directory_path,
                'doc_type' => 'STRB',
                'azure_container_name' => $azure_container_name,
            );
            //print_r($data2); die;
            //echo json_encode($data2);die;

            $headers = array(
                "X-Api-KEY: 123456",
                "Authorization: Bearer $token_access",
            );
            //print_r($headers); die;

            $ch = curl_init($to_shoot_url2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);

            $result = curl_exec($ch);
            //$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $output = json_decode($result);
            //print_r($result);die;
            curl_close($ch);

            if ($output->status == 'true') {
                $user_guid = $_SESSION['user_guid'];
                $from_module = $_SESSION['frommodule'];

                $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
                $log_1 = array(
                    'movement_guid' => $guid,
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                    'action' => 'list_image_strb',
                    'module' => $from_module,
                    'value' => $refno,
                    'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                );
                $this->db->insert('lite_b2b.supplier_movement', $log_1);

                $total_file = $output->total_file;
                $file_path_list = $output->file_path_list;

                $data = array(
                    'para1' => 'true',
                    'total_file' => $total_file,
                    'file_path_list' => $file_path_list,
                );
                echo json_encode($data);
            } else {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Get Image Failed. Please Try Again.',

                );
                echo json_encode($data);
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }
}
?>
