<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consignment_report extends CI_Controller {
    
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
        $this->api_url = '127.0.0.1/rest_b2b/index.php/';
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            // echo 1;die;
            $user_guid = $this->session->userdata('user_guid');
            $customer_guid = $this->session->userdata('customer_guid');
            
            if(in_array('IAVA',$_SESSION['module_code']))
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_sup_consign_supplier_code";

                $data = array(
                    'customer_guid' => $customer_guid,
                );
                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $code = $output->result;
                }
                else
                {
                    $code = $output->result;
                }
                // print_r($code);die;

                // $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE customer_guid = '$customer_guid' GROUP BY supplier_group_guid) a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_sup_consign_location";

                $data = array(
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                );
                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $location = $output->result;
                }
                else
                {
                    $location = $output->result;
                }   
                // print_r($location);die;             

                // $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC");
            }
            else
            {
                // $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_user_sup_consign_supplier_code";

                $data = array(
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                );
                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $code = $output->result;
                }
                else
                {
                    $code = $output->result;
                }
                // print_r($code);die;
                // echo $this->db->last_query();die;

                // $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC"); 

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_user_sup_consign_location";

                $data = array(
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                );
                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $location = $output->result;
                }
                else
                {
                    $location = $output->result;
                }
            }
    

            $data = array(
                'location' => $location,
                'code' => $code,
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/consignment_sales_report',$data);
            $this->load->view('footer'); 
        }
        else
        {
            redirect('#');
        }
    }

    public function consignment_sales_report_rest()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  

            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $from_module = 'consign_report';
            // print_r($this->input->post());die;

            // $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            // $to_shoot_url = $check_url."/temp_data";

            $session_guid = $this->session->userdata('user_logs');
            $rb_query = $this->input->post('details');
            $fields_string = json_encode($rb_query);
            // print_r($fields_string);die;
            // echo $to_shoot_url;die;
            $url = $this->api_url;

            $to_shoot_url = $url."/Insert/I_consignment_parameter";

            $data = array(
                'restful_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(), '-', '')) as guid")->row('guid'),
                'customer_guid' => $customer_guid,
                'session_guid' => $_SESSION['user_logs'],
                'user_guid' => $user_guid,
                'json_string' => $fields_string,
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                echo '1';
            }
            else
            {
                echo '2';
            }              
            // print_r($data);die;

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }   

    public function consignment_sales_report_view()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  

            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $from_module = 'consign_report';

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_report_block_flag";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'module_name' => 'CONSIGNMENT',
                'code' => 'CONS',
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $block = $output->result;
            }
            else
            {
                $block = $output->result;
            } 
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            // print_r($block);die;
            // echo $block['reason'];die;
            // echo $block[0]->reason;die;

            if($block[0]->reason == '1')
            {
                // $block_reason = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONSDESC' LIMIT 1")->row('reason');

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_consignment_report_block_reason";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'module_name' => 'CONSIGNMENT',
                    'code' => 'CONSDESC',
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $block_reason = $output->result;
                }
                else
                {
                    $block_reason = $output->result;
                } 

                echo $block_reason[0]->reason;die;
            }

            if(!isset($_REQUEST['Date_From']) || !isset($_REQUEST['Date_To']))
            {
                echo 'Please Choose Date';die;
            }
            else
            {
                $Date_From = $_REQUEST['Date_From'];
                $Date_To = $_REQUEST['Date_To'];

                $date1=date_create($Date_From);
                $date2=date_create($Date_To);
                $diff=date_diff($date1,$date2);
                // echo $diff->format("%R%a days");
                if($diff->format("%R%a days") > 30)
                {
                    echo 'Date Range Exceed 31 days';die;
                };
            }

            $from_module = 'consign_report';

            if($_SESSION['user_group_name'] == 'SUPP_ADMIN' || $_SESSION['user_group_name'] == 'SUPP_CLERK' || $_SESSION['user_group_name'] == 'LIMITED_SUPP_ADMIN')
            {
                
                // $this->db->query("REPLACE into supplier_movement select 
                // upper(replace(uuid(),'-','')) as movement_guid
                // , '$customer_guid'
                // , '$user_guid'
                // , 'viewed_consign'
                // , '$from_module'
                // , ''
                // , now()
                // ");
                $url = $this->api_url;

                $to_shoot_url = $url."/Insert/I_supplier_movement";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                    'action' => 'viewed_consign',
                    'from_module' => $from_module,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                echo $result;die;
                //close connection
                curl_close($ch);              
            
            };
            
            // echo $REQUEST_URI;die;
            // $haha = substr($REQUEST_URI, strpos($REQUEST_URI, "?") + 7);  
            $jasper_url = '127.0.0.1/haha';

            $furl = '?link=';
            $lurl = '';
            $user_guid = $this->session->userdata('user_guid');
            $session_user_guid=$this->session->userdata('user_logs');

            $db_b2b = '';
            $db_backend = '';
            $db_member = '';
            $db_frontend = '';

            // $jasper_report_folder = $row->jasper_report_folder;
            $run_url = $jasper_url.$furl.'&user_guid='.$user_guid.'&session_user_guid='.$session_user_guid.'&db_b2b='.$database1.'&db_backend='.$database2.'&db_member='.$database3.'&db_frontend='.$database4.'&j_username=panda_b2b&j_password=b2b@adnap'.$lurl.'&Date_From='.$Date_From.'&Date_To='.$Date_To;
            // echo $run_url;die;
             // header('Location:'.$run_url.'');
            redirect($run_url);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function consignment_location()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            // echo 1;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');
            // $from_module = 'consign_report';

            // $get_loc = $this->db->query("SELECT aa.*,bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '".$_SESSION['customer_guid']."' AND a.branch_code IN (".$_SESSION['query_loc'].") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '".$_SESSION['customer_guid']."') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC,branch_code ASC");

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_location";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'query_loc' => $query_loc,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $location = $output->result;
            }
            else
            {
                $location = $output->result;
            } 

            $data = array(
                'location' => $location,
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/location',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }      

    public function consignment_redirect_location()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $customer_guid = $this->session->userdata('customer_guid');
            // echo 1;die;
            // echo $this->input->post('location');die;

            if(null != $this->input->post('location'))
            {
                $loc = $this->input->post('location');
            }
            else
            {
                if(isset($_REQUEST['loc']))
                {
                    $loc = $_REQUEST['loc'];
                }
                else
                {
                    $loc = '';
                    echo '';die;
                }
                // echo $loc;die;
            }

            // $loc = $this->input->post('location');
            // echo $loc;die;
            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }

            if(isset($_REQUEST['period_code']))
            {
                $period_code = $_REQUEST['period_code'];
            }
            else
            {
                $check_loc = $loc;

                // $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_hq_branch_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $hq_branch_code = $output->result;
                }
                else
                {
                    $hq_branch_code = $output->result;
                } 
                // print_r($hq_branch_code);die;
                $hq_branch_code_array=array();

                foreach ($hq_branch_code as $key) {

                    array_push($hq_branch_code_array,$key->branch_code);
                }
                // print_r($hq_branch_code_array);die;

                if(in_array($check_loc, $hq_branch_code_array)) 
                {
                    $xloc = $this->session->userdata('query_loc');
                }
                else
                {
                    $xloc = "'".$loc."'";
                }   

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'loc' => $xloc,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $period_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $period_code = $period_code[0]->now;
                if($period_code == '')
                {
                    $period_code = "ALL";
                }
                // echo $period_code;die;       
                // $period_code = '';
            }
            // echo $period_code;die;


           //  $url = $this->api_url;

           //  $to_shoot_url = $url."/Select/S_encrypt";
           //  // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
           //  $data = array(
           //      'value' => $value,
           //  );

           //  $cuser_name = 'ADMIN';
           //  $cuser_pass = '1234';

           //  $ch = curl_init($to_shoot_url);
           // // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
           //  curl_setopt($ch, CURLOPT_TIMEOUT, 0);
           //  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
           //  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
           //  curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
           //  curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
           //  curl_setopt($ch, CURLOPT_POST, 1);
           //  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
           //  $result = curl_exec($ch);
           //  $output = json_decode($result);
           //  // $status = json_encode($output);
           //  // print_r($output->result);die;
           //  // echo $result;die;
           //  //close connection
           //  curl_close($ch);  
           //  // echo $output->status;
           //  // die;
            
           //  if($output->status == "true")
           //  {
           //      $loc = $output->result;
           //  }
           //  else
           //  {
           //      $loc = $output->result;
           //  } 
            // echo $loc;die;

            redirect('Consignment_report/consignment_sales_statement?status='.$status.'&loc='.$loc.'&period_code='.$period_code);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }      


    public function consignment_sales_statement()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $loc = $_REQUEST['loc'];
            // echo $loc;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['docdate']))
            {
                $docdate = $_REQUEST['docdate'];
            }
            else
            {
                $docdate = '';
            }

            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }  

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }
            // echo $speriod_code;die;

            $datatable_url = site_url('Consignment_report/view_table?status='.$status.'&loc='.$loc.'&period_code='.$speriod_code);

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_status";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_status = $output->result;
            }
            else
            {
                $consignment_sales_statement_status = $output->result;
            } 


            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_period";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $period_code = $output->result;
            }
            else
            {
                $period_code = $output->result;
            } 


            $data = array(
                // 'period_code' => $period_code,
                'datatable_url' => $datatable_url,
                'loc' => $loc,
                'status' => $status,
                'consignment_sales_statement_status' => $consignment_sales_statement_status,
                'period_code' => $period_code,
                'speriod_code' => $speriod_code,
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/consignment_sales_statement',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }              
 
    public function view_table()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['loc']))
            {
                $loc = $_REQUEST['loc'];
            }
            else
            {
                $loc = '';
            }

            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }   

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }            
            $xloc = $loc;
            $xstatus = $status;
            $xperiod_code = $speriod_code; 

            if($status == 'ALL')
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_status";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $consignment_sales_statement_status = $output->result;
                }
                else
                {
                    $consignment_sales_statement_status = $output->result;
                }   
                
                $status = '';
                foreach($consignment_sales_statement_status as $row)
                {
                    $status .= "'".$row->code."'".',';
                }   
                $lstatus = rtrim($status,',');           
            }
            elseif($status == '')
            {
                $lstatus = "''";
            }
            else
            {
                $lstatus = "'".$status."'";
            }
            // echo $lstatus;die;

            $check_loc = $_REQUEST['loc'];

            // $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_hq_branch_code";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $hq_branch_code = $output->result;
            }
            else
            {
                $hq_branch_code = $output->result;
            } 
            // print_r($hq_branch_code);die;
            $hq_branch_code_array=array();

            foreach ($hq_branch_code as $key) {

                array_push($hq_branch_code_array,$key->branch_code);
            }
            // print_r($hq_branch_code_array);die;

            if(in_array($check_loc, $hq_branch_code_array)) 
            {
                $loc = $this->session->userdata('query_loc');
            }
            else
            {
                $loc = "'".$loc."'";
            }      
            // echo $loc;die;

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0); 

            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search= $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";
            $site_url = site_url().'/';

            $query_supcode = $this->session->userdata('query_supcode');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_view_table_consignment_sales_statement";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            // print_r($order);die;
            $data =  array(       
              'draw' => $draw,
              'start' => $start,
              'length' => $length,
              'order' => $order,
              'search'=>  $search,
              'col' => $col,
              'dir' => $dir,
              'customer_guid' => $customer_guid,
              'status' => $lstatus,
              'loc' => $loc,
              'site_url' => $site_url,
              'period_code' => $speriod_code,
              'xloc' => $xloc,
              'xstatus' => $xstatus,
              'query_supcode' => $query_supcode,
              'view_all' => in_array('IAVA',$_SESSION['module_code']) ? 1 : 0,
              // 'view_all' => 0,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;
            }
            else
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;                
            }
            // print_r($result);die;             

            // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

            $output = array(
              "draw" => $draw,
              "recordsTotal" => $recordsTotal,
              "recordsFiltered" => $recordsFiltered,
              "data" => $data
            );

            echo json_encode($output);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }  

    public function consignment_sales_statement_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            if ($_SERVER['HTTPS'] == "on")
            {
                $url = "http://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];    
                header("Location: $url");
            }

            $trans_guid = $_REQUEST['trans'];
            // echo $trans_guid;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_e_invoice";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_e_invoices = $output->result;
                $show_consignment_e_invoice = 1;
            }
            else
            {
                $consignment_e_invoices = $output->result;
                $show_consignment_e_invoice = 0;
            }  
            // print_r($consignment_e_invoices);die;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_sale_statement_status";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_status_array = $output->result;
                $consignment_sales_statement_status = $consignment_sales_statement_status_array[0]->status;
                // echo $consignment_sales_statement_status;die;
            }
            else
            {
                $consignment_sales_statement_status_array = $output->result;
                $consignment_sales_statement_status = 'Error';
            }  

            if($consignment_sales_statement_status == 'Invoice Generated' || $consignment_sales_statement_status == 'rejected')
            {
                $show_reject = 0;
            }
            else
            {
                $show_reject = 1;
            }

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_sales_statement_header";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_header = $output->result;
            }
            else
            {
                $consignment_sales_statement_header = $output->result;
            }     

            $from_module = 'consignment_sales_statement';

            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Insert/I_supplier_movement";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                    'action' => 'viewed_consign_sale_statement',
                    'value' => $trans_guid,
                    'from_module' => $from_module,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);
                if($consignment_sales_statement_status == '')
                {
                    $url = $this->api_url;

                    $to_shoot_url = $url."/Update/U_consignment_sales_statement_status";
                    // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                    $data = array(
                        'customer_guid' => $customer_guid,
                        'trans_guid' => $trans_guid,
                        'status' => 'viewed',
                    );

                    $cuser_name = 'ADMIN';
                    $cuser_pass = '1234';

                    $ch = curl_init($to_shoot_url);
                   // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                    curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    $result = curl_exec($ch);
                    $output = json_decode($result);
                    // $status = json_encode($output);
                    // print_r($output->result);die;
                    // echo $result;die;
                    //close connection
                    curl_close($ch);
                }                              
            
            };                             

            $data = array(
                'consignment_sales_statement_header' => $consignment_sales_statement_header,
                'show_consignment_e_invoice' => $show_consignment_e_invoice,
                'show_reject' => $show_reject,
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/consignment_sales_statement_child',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }  

    public function header_save_inv_no()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $trans_guid = $this->input->post('trans_guid');
            $b2b_inv_no = $this->input->post('b2b_inv_no');
            $consign_invoice_date = $this->input->post('consign_invoice_date');
            // echo $trans_guid;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            // $query_loc = $this->session->userdata('query_loc');

            $url = $this->api_url;

            $to_shoot_url = $url."/Update/U_consignment_sales_statement_header_b2b_inv_no";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
                'b2b_inv_no' => $b2b_inv_no,
                'consign_invoice_date' => $consign_invoice_date,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $update_status = 1;
            }
            else
            {
                $update_status = 0;
            }                      

            echo $update_status;

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }    

    public function consignment_sales_statement_view()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  

            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $report_type = $_REQUEST['report_type'];
            $trans_guid = $_REQUEST['trans_guid'];
            // echo $report_type;die;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_sales_statement_refno";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_refno_array = $output->result;
            }
            else
            {
                $consignment_sales_statement_refno_array = $output->result;
            }  

            $consignment_sales_statement_refno = $consignment_sales_statement_refno_array[0]->refno;
            // echo $consignment_sales_statement_refno;die;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_jasper_report_template";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'report_type' => $report_type,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $jasper_report_template_array = $output->result;
            }
            else
            {
                $jasper_report_template_array = $output->result;
            }  

            // $run_url = $url_array[0]->ip.$url_array[0]->first_folder.'&taxinv_guid='.$consignment_sales_statement_refno;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_acc_setting";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $database_name_array = $output->result;
            }
            else
            {
                $database_name_array = $output->result;
            }        

            $database_name = $database_name_array[0]->b2b_database;      
            // echo $database_name;die;
            // echo $run_url;die;
            // echo 1;die;
            // echo $REQUEST_URI;die;
            // $haha = substr($REQUEST_URI, strpos($REQUEST_URI, "?") + 7);  
            // $jasper_url = '127.0.0.1/haha';

            // $furl = '?link=';
            // $lurl = '';
            // $user_guid = $this->session->userdata('user_guid');
            // $session_user_guid=$this->session->userdata('user_logs');

            // $db_b2b = '';
            // $db_backend = '';
            // $db_member = '';
            // $db_frontend = '';

            // $jasper_report_folder = $row->jasper_report_folder;
            // $run_url = $jasper_url.$furl.'&user_guid='.$user_guid.'&session_user_guid='.$session_user_guid.'&db_b2b='.$database1.'&db_backend='.$database2.'&db_member='.$database3.'&db_frontend='.$database4.'&j_username=panda_b2b&j_password=b2b@adnap'.$lurl.'&Date_From='.$Date_From.'&Date_To='.$Date_To;
            // echo $run_url;die;
             // header('Location:'.$run_url.'');
            // $run_url = "http://18.139.87.215:58080/jasperserver/flow.html?_flowId=viewReportFlow&_flowId=viewReportFlow&decorate=no&j_username=panda_b2b&j_password=b2b@adnap
            // &db_crm=backend_member&db_be=backend&db_fe=frontend

            // &db_b2b=panda_b2b&db_backend=backend_member&db_member=frontend&db_frontend=crm&j_username=panda_b2b&j_password=b2b@adnapParentFolderUri=/reports/B2BReports&reportUnit=/reports/B2BReports/Consignment_Sales_Report&Date_From=2020-06-01&Date_To=2020-06-01";
            // echo $run_url;die;
            // redirect($run_url);
            $AppPOST = json_decode(file_get_contents('php://input'), true);

            // if (!empty($AppPOST)) {
            //     $type = $AppPOST['type'];
            //     $doc_template = $AppPOST['doc_template'];
            //     $customer_guid = $AppPOST['customer_guid'];
            //     $refno = $AppPOST['refno'];
            // } else {

            //     $type = $this->input->post('type');
            //     $doc_template = $this->input->post('doc_template');
            //     $customer_guid = $this->input->post('customer_guid');
            //     $refno = $this->input->post('refno');
                
            // }

            // $db = $this->lite_b2b_model->customer_info($customer_guid)->row('old_db');

            $doc_template_name = $jasper_report_template_array[0]->report_template;
            $doc_template = $doc_template_name.'.pdf';

            $db = $database_name;
            // echo $url;die;
            // $db = 'r_panda_backend';
            // echo $db;die;

            $refno = $consignment_sales_statement_refno;

            $url = "http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/B2BReports/$doc_template?db_be=$db&taxinv_guid=$refno";
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/B2BReports/$doc_template?db_be=$db&taxinv_guid=$refno",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
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
            header('Content-Disposition: ' .$disposition.'; filename=SALE_STATEMENT_'.$refno.'.pdf');
            echo $response; 

            curl_close($curl);            

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }     

    public function consignment_generate_e_invoice()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $trans_guid = $this->input->post('trans_guid');
            $b2b_inv_no = $this->input->post('b2b_inv_no');
            $consign_invoice_date = $this->input->post('consign_invoice_date');
            $loc = $this->input->post('loc');
            $period_code = $this->input->post('period_code');
            $status = $this->input->post('status');            
            // print_r($this->input->post());die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            // $query_loc = $this->session->userdata('query_loc');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_e_invoice";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $check_e_invoices = count($output->result);
            }
            else
            {
                $check_e_invoices = count($output->result);
            }
            $check_e_invoices = 0;//skip checking of e invoices
            if($check_e_invoices > 0)
            {
                // echo 1;die;
                $this->session->set_flashdata('warning', 'E invoices already generated');
                redirect(site_url('Consignment_report/consignment_sales_statement_child').'?trans='.$trans_guid);
            }
            else
            {
                // echo 2;die;
                $url = $this->api_url;

                $to_shoot_url = $url."/Insert/I_consignment_e_invoice";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'trans_guid' => $trans_guid,
                    'b2b_inv_no' => $b2b_inv_no,
                    'consign_invoice_date' => $consign_invoice_date,
                    'user_guid' => $user_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    {
                        $from_module = 'consignment_sales_statement';

                        $url = $this->api_url;

                        $to_shoot_url = $url."/Insert/I_supplier_movement";
                        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                        $data = array(
                            'customer_guid' => $customer_guid,
                            'user_guid' => $user_guid,
                            'action' => 'generate_inv',
                            'value' => $trans_guid,
                            'from_module' => $from_module,
                        );

                        $cuser_name = 'ADMIN';
                        $cuser_pass = '1234';

                        $ch = curl_init($to_shoot_url);
                       // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        $result = curl_exec($ch);
                        $output = json_decode($result);
                        // $status = json_encode($output);
                        // print_r($output->result);die;
                        // echo $result;die;
                        //close connection
                        curl_close($ch); 

                        $url = $this->api_url;

                        $to_shoot_url = $url."/Update/U_consignment_sales_statement_status";
                        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                        $data = array(
                            'customer_guid' => $customer_guid,
                            'trans_guid' => $trans_guid,
                            'status' => 'Invoice Generated',
                        );

                        $cuser_name = 'ADMIN';
                        $cuser_pass = '1234';

                        $ch = curl_init($to_shoot_url);
                       // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        $result = curl_exec($ch);
                        $output = json_decode($result);
                        // $status = json_encode($output);
                        // print_r($output->result);die;
                        // echo $result;die;
                        //close connection
                        curl_close($ch); 
                    }

                    $check_e_invoices = count($output->result);

                    $this->session->set_flashdata('message', 'E invoices generated');
                    redirect(site_url('Consignment_report/consignment_sales_statement_child').'?trans='.$trans_guid.'&loc='.$loc.'&period_code='.$period_code.'&status='.$status);
                }
                else
                {
                    $check_e_invoices = count($output->result);

                    $this->session->set_flashdata('warning', 'E invoices not generated, please try again');
                    redirect(site_url('Consignment_report/consignment_sales_statement_child').'?trans='.$trans_guid.'&loc='.$loc.'&period_code='.$period_code.'&status='.$status);
                }
            }

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }     

    public function update_consign_sales_statement_status()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $trans_guid = $this->input->post('trans_guid');
            $status = $this->input->post('status');
            // echo $trans_guid;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            // $query_loc = $this->session->userdata('query_loc');

            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                $from_module = 'consignment_sales_statement';
                $url = $this->api_url;

                $to_shoot_url = $url."/Insert/I_supplier_movement";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                    'action' => 'rejected_consign_sale_statement',
                    'value' => $trans_guid,
                    'from_module' => $from_module,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);                            
            };   

            $url = $this->api_url;

            $to_shoot_url = $url."/Insert/I_reject_consignment_e_invoices";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
                'user_guid' => $user_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);

            $url = $this->api_url;

            $to_shoot_url = $url."/Update/U_consignment_sales_statement_status";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
                'status' => $status,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $update_status = 1;
            }
            else
            {
                $update_status = 0;
            }                      

            echo $update_status;

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }   

    public function consignment_e_invoice_view()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  

            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $report_type = $_REQUEST['report_type'];
            $trans_guid = $_REQUEST['trans_guid'];
            // echo $report_type;die;
            // echo $consignment_sales_statement_refno;die;

           //  $url = $this->api_url;

           //  $to_shoot_url = $url."/Select/S_consignment_report_link";
           //  // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
           //  $data = array(
           //      'customer_guid' => $customer_guid,
           //      'report_type' => $report_type,
           //  );

           //  $cuser_name = 'ADMIN';
           //  $cuser_pass = '1234';

           //  $ch = curl_init($to_shoot_url);
           // // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
           //  curl_setopt($ch, CURLOPT_TIMEOUT, 0);
           //  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
           //  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
           //  curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
           //  curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
           //  curl_setopt($ch, CURLOPT_POST, 1);
           //  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
           //  $result = curl_exec($ch);
           //  $output = json_decode($result);
           //  // $status = json_encode($output);
           //  // print_r($output->result);die;
           //  // echo $result;die;
           //  //close connection
           //  curl_close($ch);  
           //  // echo $output->status;
           //  // die;
            
           //  if($output->status == "true")
           //  {
           //      $url_array = $output->result;
           //  }
           //  else
           //  {
           //      $url_array = $output->result;
           //  }  

           //  $run_url = $url_array[0]->ip.$url_array[0]->first_folder.'&trans_guid='.$trans_guid;


            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_jasper_report_template";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'report_type' => $report_type,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $jasper_report_template_array = $output->result;
            }
            else
            {
                $jasper_report_template_array = $output->result;
            }  

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_acc_setting";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $database_name_array = $output->result;
            }
            else
            {
                $database_name_array = $output->result;
            }        

            $database_name = $database_name_array[0]->b2b_database;      

            $AppPOST = json_decode(file_get_contents('php://input'), true);

            // if (!empty($AppPOST)) {
            //     $type = $AppPOST['type'];
            //     $doc_template = $AppPOST['doc_template'];
            //     $customer_guid = $AppPOST['customer_guid'];
            //     $refno = $AppPOST['refno'];
            // } else {

            //     $type = $this->input->post('type');
            //     $doc_template = $this->input->post('doc_template');
            //     $customer_guid = $this->input->post('customer_guid');
            //     $refno = $this->input->post('refno');
                
            // }

            // $db = $this->lite_b2b_model->customer_info($customer_guid)->row('old_db');

            $doc_template_name = $jasper_report_template_array[0]->report_template;
            $doc_template = $doc_template_name.'.pdf';

            $db = $database_name;
            // $db = 'r_panda_backend';
            // echo $db;die;

            $refno = $trans_guid;

            $curl = curl_init();
            $url = "http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/B2BReports/$doc_template?db_be=$db&trans_guid=$refno";
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
            header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$refno.'.pdf');
            echo $response; 

            curl_close($curl);  

            // echo $run_url;die;
            // redirect($run_url);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }   

    public function consignment_report_mul_loc()
    {

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            if ($_SERVER['HTTPS'] == "on")
            {
                $url = "http://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];    
                header("Location: $url");
            } 

            $request_link = site_url('Consignment_report/consignment_report');
            $user_guid = $_SESSION['user_guid'];
            $customer_guid = $_SESSION['customer_guid'];
            
            if(in_array('IAVA',$_SESSION['module_code']))
            {
                $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE customer_guid = '$customer_guid' GROUP BY supplier_group_guid) a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC");
            }
            else
            {
                $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC"); 
            }



            

            $data = array(
                'location' => $location,
                'code' => $code,
                'request_link' => $request_link,
            );
            
            $this->load->view('header');
            $this->load->view('consignment_report_mul_loc',$data);
            $this->load->view('footer'); 
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }//close consignment_report_mul_loc
    
    public function consignment_report()
    {

        if(isset($_REQUEST['Date_From']) && isset($_REQUEST['Date_To']) && isset($_REQUEST['main_loc']) && isset($_REQUEST['Consign_code']))
        {

            $report_type = $_REQUEST['report_guid'];
            $Date_From = $_REQUEST['Date_From'];
            $Date_To = $_REQUEST['Date_To'];
            $main_loc = $_REQUEST['main_loc'];
            $Consign_code = urlencode($_REQUEST['Consign_code']);
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');

            $from_module = 'consign_report';
            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                
                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_consign'
                , '$from_module'
                , ''
                , now()
                ");
            
            };

            $ver = 0;
            if($ver == 0)
            {
                $db_be = 'backend';
                $db_re = 'report_summary';
                $jasper_url = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'")->row('jasper_url');
            }
            else
            {
                $db_be = '';
                $db_re = '';
                $jasper_url = '127.0.0.1';
            }
            // echo $this->db->last_query();die;
            // print_r($jasper_url);die;
            $report_template = $this->db->query("SELECT * FROM jasper_report_template WHERE report_type = '$report_type'")->row('report_template');
            // echo $this->db->last_query();die;
            // print_r($report_template);die;
            $report_ip = $jasper_url.'/jasperserver/rest_v2/reports/reports/B2BReports/'.$report_template.'.pdf';
            $report_ip = $jasper_url.'/jasperserver/rest_v2/reports/reports/B2BReports/'.$report_template.'.pdf'.'?Date_From='.$Date_From.'&Date_To='.$Date_To.'&db_be='.$db_be.'&db_re='.$db_re.'&supcode='.$Consign_code.$main_loc;

            // echo $report_ip;die;
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
              CURLOPT_HTTPHEADER => array(
                "Cache-Control: public",
                "Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==",
                "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
              ),
              // CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
              CURLOPT_SSL_VERIFYPEER => false,        //
              // CURLOPT_VERBOSE        => 1,
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
            header('Content-Disposition: ' .$disposition.'; filename=Consignment_report.pdf');
            echo $response; die;

            curl_close($curl);     
        
        }
        else
        {   
            echo 'Please select input.';
            exit();
        }

    }//close jasper_report_multiple_loc 

        public function consignment_report_excel()  
    {   
        if(isset($_REQUEST['Date_From']) && isset($_REQUEST['Date_To']) && isset($_REQUEST['main_loc']) && isset($_REQUEST['Consign_code']))    
        {   
            $report_type = $_REQUEST['report_guid'];    
            $Date_From = $_REQUEST['Date_From'];    
            $Date_To = $_REQUEST['Date_To'];    
            $main_loc = $_REQUEST['main_loc'];  
            $Consign_code = $_REQUEST['Consign_code'];  
            $customer_guid = $this->session->userdata('customer_guid'); 
            $user_guid = $this->session->userdata('user_guid'); 
            $from_module = 'consign_report';    
            if(!in_array('!SUPPMOV',$_SESSION['module_code']))  
            {   
                    
                $this->db->query("REPLACE into supplier_movement select     
                upper(replace(uuid(),'-','')) as movement_guid  
                , '$customer_guid'  
                , '$user_guid'  
                , 'viewed_consign'  
                , '$from_module'    
                , ''    
                , now() 
                "); 
                
            };  
            $ver = 0;   
            if($ver == 0)   
            {   
                $db_be = 'backend'; 
                $db_re = 'report_summary';  
                $jasper_url = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'")->row('jasper_url');   
            }   
            else    
            {   
                $db_be = '';    
                $db_re = '';    
                $jasper_url = '127.0.0.1';  
            }   
            // echo $this->db->last_query();die;    
            // print_r($jasper_url);die;    
            $report_template = $this->db->query("SELECT * FROM jasper_report_template WHERE report_type = '$report_type'")->row('report_template'); 
            // echo $this->db->last_query();die;    
            // print_r($report_template);die;   
            $type = $_REQUEST['type'];  
            if($type == 'xlsx') 
            {   
                $file_type = '.xlsx';   
                $paginate = '&ignorePagination=true';   
            }   
            elseif($type == 'xls')  
            {   
                $file_type = '.XLS';    
                $paginate = '&ignorePagination=true';   
            }   
            elseif($type == 'csv')  
            {   
                $file_type = '.CSV';    
                $paginate = '&ignorePagination=true';   
            }               
            else    
            {   
                $file_type = '';    
                $paginate = ''; 
            }   
            $report_ip = $jasper_url.'/jasperserver/rest_v2/reports/reports/B2BReports/'.$report_template.$file_type.'?Date_From='.$Date_From.'&Date_To='.$Date_To.'&db_be='.$db_be.'&db_re='.$db_re.'&supcode='.$Consign_code.$main_loc.$paginate; 
            // echo $report_ip;die; 
            $url = $report_ip;  
            $juser_name = 'panda_b2b';  
            $juser_pass = 'b2b@adnap';  
            if(strpos($url, 'http://') !== false)
            {
                $url = str_replace('http://','http://'.$juser_name.':'.$juser_pass.'@',$url);
            } else{
                $url = str_replace('https://','https://'.$juser_name.':'.$juser_pass.'@',$url);
            }

            // $url = str_replace('http://','http://'.$juser_name.':'.$juser_pass.'@',$url);  
            // $url = str_replace('https://','https://'.$juser_name.':'.$juser_pass.'@',$url);  
            // echo $url;die;   
            redirect($url);die;     
            
        }   
        else    
        {       
            echo 'Please select input.';    
            exit(); 
        }   
    }//close jasper_report_multiple_loc     

    public function consignment_location_by_supcode()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            // echo 1;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');
            // $from_module = 'consign_report';

            // $get_loc = $this->db->query("SELECT aa.*,bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '".$_SESSION['customer_guid']."' AND a.branch_code IN (".$_SESSION['query_loc'].") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '".$_SESSION['customer_guid']."') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC,branch_code ASC");

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_location";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'query_loc' => $query_loc,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $location = $output->result;
            }
            else
            {
                $location = $output->result;
            } 

            $data = array(
                'location' => $location,
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/location_by_supcode',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    } 

    public function consignment_redirect_location_by_supcode()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $customer_guid = $this->session->userdata('customer_guid');
            // echo 1;die;
            // echo $this->input->post('location');die;

            if(null != $this->input->post('location'))
            {
                $loc = $this->input->post('location');
            }
            else
            {
                if(isset($_REQUEST['loc']))
                {
                    $loc = $_REQUEST['loc'];
                }
                else
                {
                    $loc = '';
                    echo '';die;
                }
                // echo $loc;die;
            }

            // $loc = $this->input->post('location');
            // echo $loc;die;
            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }

            if(isset($_REQUEST['period_code']))
            {
                $period_code = $_REQUEST['period_code'];
            }
            else
            {
                $check_loc = $loc;

                // $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_hq_branch_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $hq_branch_code = $output->result;
                }
                else
                {
                    $hq_branch_code = $output->result;
                } 
                // print_r($hq_branch_code);die;
                $hq_branch_code_array=array();

                foreach ($hq_branch_code as $key) {

                    array_push($hq_branch_code_array,$key->branch_code);
                }
                // print_r($hq_branch_code_array);die;

                if(in_array($check_loc, $hq_branch_code_array)) 
                {
                    $xloc = $this->session->userdata('query_loc');
                }
                else
                {
                    $xloc = "'".$loc."'";
                }   

                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'loc' => $xloc,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $period_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $period_code = $period_code[0]->now;
                if($period_code == '')
                {
                    $period_code = "ALL";
                }
                // echo $period_code;die;       
                // $period_code = '';
            }

            redirect('Consignment_report/consignment_sales_statement_by_supcode?status='.$status.'&loc='.$loc.'&period_code='.$period_code);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }    

    public function consignment_sales_statement_by_supcode()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $loc = $_REQUEST['loc'];
            // echo $loc;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['docdate']))
            {
                $docdate = $_REQUEST['docdate'];
            }
            else
            {
                $docdate = '';
            }

            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }  

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }
            // echo $speriod_code;die;

            $datatable_url = site_url('Consignment_report/view_table_by_supcode?status='.$status.'&loc='.$loc.'&period_code='.$speriod_code);

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_status";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_status = $output->result;
            }
            else
            {
                $consignment_sales_statement_status = $output->result;
            } 


            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_period";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $period_code = $output->result;
            }
            else
            {
                $period_code = $output->result;
            } 


            $data = array(
                // 'period_code' => $period_code,
                'datatable_url' => $datatable_url,
                'loc' => $loc,
                'status' => $status,
                'consignment_sales_statement_status' => $consignment_sales_statement_status,
                'period_code' => $period_code,
                'speriod_code' => $speriod_code,
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/consignment_sales_statement_by_supcode',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }  

    public function view_table_by_supcode()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['loc']))
            {
                $loc = $_REQUEST['loc'];
            }
            else
            {
                $loc = '';
            }

            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }   

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }            
            $xloc = $loc;
            $xstatus = $status;
            $xperiod_code = $speriod_code; 

            if($status == 'ALL')
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_status";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $consignment_sales_statement_status = $output->result;
                }
                else
                {
                    $consignment_sales_statement_status = $output->result;
                }   
                
                $status = '';
                foreach($consignment_sales_statement_status as $row)
                {
                    $status .= "'".$row->code."'".',';
                }   
                $lstatus = rtrim($status,',');           
            }
            elseif($status == '')
            {
                $lstatus = "''";
            }
            else
            {
                $lstatus = "'".$status."'";
            }
            // echo $lstatus;
            // die;

            $check_loc = $_REQUEST['loc'];

            // $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_hq_branch_code";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $hq_branch_code = $output->result;
            }
            else
            {
                $hq_branch_code = $output->result;
            } 
            // print_r($hq_branch_code);die;
            $hq_branch_code_array=array();

            foreach ($hq_branch_code as $key) {

                array_push($hq_branch_code_array,$key->branch_code);
            }
            // print_r($hq_branch_code_array);die;

            if(in_array($check_loc, $hq_branch_code_array)) 
            {
                $loc = $this->session->userdata('query_loc');
            }
            else
            {
                $loc = "'".$loc."'";
            }      
            // echo $loc;die;

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0); 

            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search= $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";
            $site_url = site_url().'/';

            $query_supcode = $this->session->userdata('query_supcode');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_view_table_consignment_sales_statement_by_supcode";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            // print_r($order);die;
            $data =  array(       
              'draw' => $draw,
              'start' => $start,
              'length' => $length,
              'order' => $order,
              'search'=>  $search,
              'col' => $col,
              'dir' => $dir,
              'customer_guid' => $customer_guid,
              'status' => $lstatus,
              'loc' => $loc,
              'site_url' => $site_url,
              'period_code' => $speriod_code,
              'xloc' => $xloc,
              'xstatus' => $xstatus,
              'query_supcode' => $query_supcode,
              'view_all' => in_array('IAVA',$_SESSION['module_code']) ? 1 : 0,
              // 'view_all' => 0,
            );
            // print_r($data);die;
            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;
            }
            else
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;                
            }
            // print_r($result);die;             

            // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

            $output = array(
              "draw" => $draw,
              "recordsTotal" => $recordsTotal,
              "recordsFiltered" => $recordsFiltered,
              "data" => $data
            );

            echo json_encode($output);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }  

    public function consignment_sales_statement_by_supcode_list()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            // if ($_SERVER['HTTPS'] == "on")
            // {
            //     $url = "http://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];    
            //     header("Location: $url");
            // }

            // $trans_guid = $_REQUEST['trans'];
            // echo $trans_guid;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            $loc = $_REQUEST['loc'];
            // echo $loc;die;

            if(isset($_REQUEST['docdate']))
            {
                $docdate = $_REQUEST['docdate'];
            }
            else
            {
                $docdate = '';
            }

            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }  

            if(isset($_REQUEST['supcode']))
            {
                $ssupcode = $_REQUEST['supcode'];
            }
            else
            {
                echo 'no supcode';die;
            }  

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }
            // echo $speriod_code;die;

            $datatable_url = site_url('Consignment_report/view_table_by_supcode_list?status='.$status.'&loc='.$loc.'&period_code='.$speriod_code.'&supcode='.$ssupcode);

            // $datatable_url = site_url('Consignment_report/view_table_by_supcode_list?status=&loc=HQ&period_code=2020-09');

           //  $url = $this->api_url;

           //  $to_shoot_url = $url."/Select/S_consignment_e_invoice";
           //  // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
           //  $data = array(
           //      'customer_guid' => $customer_guid,
           //      'trans_guid' => $trans_guid,
           //  );

           //  $cuser_name = 'ADMIN';
           //  $cuser_pass = '1234';

           //  $ch = curl_init($to_shoot_url);
           // // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
           //  curl_setopt($ch, CURLOPT_TIMEOUT, 0);
           //  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
           //  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
           //  curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
           //  curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
           //  curl_setopt($ch, CURLOPT_POST, 1);
           //  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
           //  $result = curl_exec($ch);
           //  $output = json_decode($result);
           //  // $status = json_encode($output);
           //  // print_r($output->result);die;
           //  // echo $result;die;
           //  //close connection
           //  curl_close($ch);  
           //  // echo $output->status;
           //  // die;
            
           //  if($output->status == "true")
           //  {
           //      $consignment_e_invoices = $output->result;
           //      $show_consignment_e_invoice = 1;
           //  }
           //  else
           //  {
           //      $consignment_e_invoices = $output->result;
           //      $show_consignment_e_invoice = 0;
           //  }  
            // print_r($consignment_e_invoices);die;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_e_invoice_by_supcode";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                // 'trans_guid' => $trans_guid,
                'period_code' => $speriod_code,
                'supcode' => $ssupcode,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->result == "1")
            {
                $show_e_invoice = '1';
                // echo $consignment_sales_statement_status;die;
            }
            else
            {
                $show_e_invoice = '0';
            }  
            // echo $show_e_invoice;die;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_sales_statement_header_by_supcode";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                // 'trans_guid' => $trans_guid,
                'period_code' => $speriod_code,
                'supcode' => $ssupcode,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_header = $output->result;
                $consignment_sales_statement_sup_inv_no = $consignment_sales_statement_header[0]->sup_doc_no;
                $consignment_sales_statement_status = $consignment_sales_statement_header[0]->status;
                $consignment_sales_statement_sup_code = $consignment_sales_statement_header[0]->supcus_code;
            }
            else
            {
                $consignment_sales_statement_header = $output->result;
                $consignment_sales_statement_sup_inv_no = $consignment_sales_statement_header[0]->sup_doc_no;
                $consignment_sales_statement_status = $consignment_sales_statement_header[0]->status;
                $consignment_sales_statement_sup_code = $consignment_sales_statement_header[0]->supcus_code;
            }     

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_acc_setting_flag";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            $show_upload_e_invoice = '0';
            if($output->result == true || $output->result == "true" || $output->result == 1 || $output->result == "1")
            {
                // echo 1;die;
                // $show_upload_e_invoice = '1';
                $show_upload_e_invoice = $output->result[0]->upload_consign_invoice;
                // die;
            }
            else
            {
                $show_upload_e_invoice = 0;
                echo "Error. Please Contact Admin";
                die;
            }  
            // echo $show_upload_e_invoice;die;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consign_upload_inv_path_flag";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'sup_code' => $ssupcode,
                'period_code' => $speriod_code,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            if($output->result == true || $output->result == "true" || $output->result == 1 || $output->result == "1")
            {
                // echo 1;die;
                // $show_upload_e_invoice = '1';
                $target_dir_supplier_guid = $output->result[0]->supplier_guid;
                // echo $target_dir_supplier_guid;die;
                // die;
            }
            else
            {
                $target_dir_supplier_guid = '';
                // echo "Error. Please Contact Admin";
                // die;
            }  
            // echo $target_dir_supplier_guid;die;

            $target_dir = "retailer_file/".$customer_guid."/consign_inv/".$target_dir_supplier_guid."/".$ssupcode.'_'.$speriod_code.'.pdf';
            // echo $target_dir;die;
            if(file_exists($target_dir))
            {
                $exists_consign_inv_file = 1;
            }
            else
            {
                $exists_consign_inv_file = 0;
            }
            // echo $exists_consign_inv_file;die;

            $from_module = 'consignment_sales_statement';

            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Insert/I_supplier_movement";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'user_guid' => $user_guid,
                    'action' => 'viewed_consign_sale_statement',
                    'value' => $speriod_code.'_'.$ssupcode,
                    'from_module' => $from_module,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);                            
            
            };                     

            
            $data = array(
                'consignment_sales_statement_header' => $consignment_sales_statement_header,
                // 'show_consignment_e_invoice' => $show_consignment_e_invoice,
                // 'show_reject' => $show_reject,
                'consignment_sales_statement_sup_code' => $consignment_sales_statement_sup_code,
                'consignment_sales_statement_sup_inv_no' => $consignment_sales_statement_sup_inv_no,
                'consignment_sales_statement_status' => $consignment_sales_statement_status,
                'show_consignment_e_invoice' => 0,
                'show_reject' => '',                
                'datatable_url' => $datatable_url,
                'show_e_invoice' => $show_e_invoice,
                'show_upload_e_invoice' => $show_upload_e_invoice,
                'exists_consign_inv_file' => $exists_consign_inv_file,
                'target_dir' => $target_dir,
            );
            // echo $datatable_url;die;
            $this->load->view('header');
            $this->load->view('Consignment/consignment_sales_statement_by_supcode_list',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }     

    public function view_table_by_supcode_list()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['loc']))
            {
                $loc = $_REQUEST['loc'];
            }
            else
            {
                $loc = '';
            }

            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }   

            if(isset($_REQUEST['supcode']))
            {
                $ssupcode = $_REQUEST['supcode'];
            }
            else
            {
                $ssupcode = '';
            } 

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }            
            $xloc = $loc;
            $xstatus = $status;
            $xperiod_code = $speriod_code; 

            if($status == 'ALL')
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_status";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $consignment_sales_statement_status = $output->result;
                }
                else
                {
                    $consignment_sales_statement_status = $output->result;
                }   
                
                $status = '';
                foreach($consignment_sales_statement_status as $row)
                {
                    $status .= "'".$row->code."'".',';
                }   
                $lstatus = rtrim($status,',');           
            }
            elseif($status == '')
            {
                $lstatus = "''";
            }
            else
            {
                $lstatus = "'".$status."'";
            }
            // echo $lstatus;die;

            $check_loc = $_REQUEST['loc'];

            // $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_hq_branch_code";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $hq_branch_code = $output->result;
            }
            else
            {
                $hq_branch_code = $output->result;
            } 
            // print_r($hq_branch_code);die;
            $hq_branch_code_array=array();

            foreach ($hq_branch_code as $key) {

                array_push($hq_branch_code_array,$key->branch_code);
            }
            // print_r($hq_branch_code_array);die;

            if(in_array($check_loc, $hq_branch_code_array)) 
            {
                $loc = $this->session->userdata('query_loc');
            }
            else
            {
                $loc = "'".$loc."'";
            }      
            // echo $loc;die;

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0); 

            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search= $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";
            $site_url = site_url().'/';

            $query_supcode = $this->session->userdata('query_supcode');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_view_table_consignment_sales_statement_by_supcode_acc_trans";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            // print_r($order);die;
            $data =  array(       
              'draw' => $draw,
              'start' => $start,
              'length' => $length,
              'order' => $order,
              'search'=>  $search,
              'col' => $col,
              'dir' => $dir,
              'customer_guid' => $customer_guid,
              'status' => $lstatus,
              'loc' => $loc,
              'site_url' => $site_url,
              'period_code' => $speriod_code,
              'xloc' => $xloc,
              'xstatus' => $xstatus,
              'query_supcode' => $query_supcode,
              'supcode' => $ssupcode,
              'view_all' => in_array('IAVA',$_SESSION['module_code']) ? 1 : 0,
              // 'view_all' => 0,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;
            }
            else
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;                
            }
            // print_r($result);die;             

            // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

            $output = array(
              "draw" => $draw,
              "recordsTotal" => $recordsTotal,
              "recordsFiltered" => $recordsFiltered,
              "data" => $data
            );

            echo json_encode($output);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }  

    public function consignment_sales_statement_by_supcode_list_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            // if ($_SERVER['HTTPS'] == "on")
            // {
            //     $url = "http://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];    
            //     header("Location: $url");
            // }

            $trans_guid = $_REQUEST['trans'];
            // echo $trans_guid;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_e_invoice";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_e_invoices = $output->result;
                $show_consignment_e_invoice = 1;
            }
            else
            {
                $consignment_e_invoices = $output->result;
                $show_consignment_e_invoice = 0;
            }  
            // print_r($consignment_e_invoices);die;

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_sale_statement_status";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_status_array = $output->result;
                $consignment_sales_statement_status = $consignment_sales_statement_status_array[0]->status;
                // echo $consignment_sales_statement_status;die;
            }
            else
            {
                $consignment_sales_statement_status_array = $output->result;
                $consignment_sales_statement_status = 'Error';
            }  

            if($consignment_sales_statement_status == 'Invoice Generated' || $consignment_sales_statement_status == 'rejected')
            {
                $show_reject = 0;
            }
            else
            {
                $show_reject = 1;
            }

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_sales_statement_header";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'trans_guid' => $trans_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_header = $output->result;
            }
            else
            {
                $consignment_sales_statement_header = $output->result;
            }     

            // $from_module = 'consignment_sales_statement';

            // if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            // {
            //     $url = $this->api_url;

            //     $to_shoot_url = $url."/Insert/I_supplier_movement";
            //     // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            //     $data = array(
            //         'customer_guid' => $customer_guid,
            //         'user_guid' => $user_guid,
            //         'action' => 'viewed_consign_sale_statement',
            //         'value' => $trans_guid,
            //         'from_module' => $from_module,
            //     );

            //     $cuser_name = 'ADMIN';
            //     $cuser_pass = '1234';

            //     $ch = curl_init($to_shoot_url);
            //    // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            //     curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            //     curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            //     curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            //     curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            //     curl_setopt($ch, CURLOPT_POST, 1);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            //     $result = curl_exec($ch);
            //     $output = json_decode($result);
            //     // $status = json_encode($output);
            //     // print_r($output->result);die;
            //     // echo $result;die;
            //     //close connection
            //     curl_close($ch);
            //     if($consignment_sales_statement_status == '')
            //     {
            //         $url = $this->api_url;

            //         $to_shoot_url = $url."/Update/U_consignment_sales_statement_status";
            //         // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            //         $data = array(
            //             'customer_guid' => $customer_guid,
            //             'trans_guid' => $trans_guid,
            //             'status' => 'viewed',
            //         );

            //         $cuser_name = 'ADMIN';
            //         $cuser_pass = '1234';

            //         $ch = curl_init($to_shoot_url);
            //        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            //         curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            //         curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            //         curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            //         curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            //         curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            //         curl_setopt($ch, CURLOPT_POST, 1);
            //         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            //         $result = curl_exec($ch);
            //         $output = json_decode($result);
            //         // $status = json_encode($output);
            //         // print_r($output->result);die;
            //         // echo $result;die;
            //         //close connection
            //         curl_close($ch);
            //     }                              
            
            // };                             

            $data = array(
                'consignment_sales_statement_header' => $consignment_sales_statement_header,
                'show_consignment_e_invoice' => $show_consignment_e_invoice,
                'show_reject' => $show_reject,
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/consignment_sales_statement_by_supcode_list_child',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    } 

    public function consignment_e_invoice_by_supcode_view()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  

            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $report_type = $_REQUEST['report_type'];
            $trans_guid = str_replace(' ','%20',$_REQUEST['trans_guid']);
            $supcode = $_REQUEST['supcode'];
            // echo $report_type;die;
            // echo $consignment_sales_statement_refno;die;

           //  $url = $this->api_url;

           //  $to_shoot_url = $url."/Select/S_consignment_report_link";
           //  // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
           //  $data = array(
           //      'customer_guid' => $customer_guid,
           //      'report_type' => $report_type,
           //  );

           //  $cuser_name = 'ADMIN';
           //  $cuser_pass = '1234';

           //  $ch = curl_init($to_shoot_url);
           // // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
           //  curl_setopt($ch, CURLOPT_TIMEOUT, 0);
           //  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
           //  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
           //  curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
           //  curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
           //  curl_setopt($ch, CURLOPT_POST, 1);
           //  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
           //  $result = curl_exec($ch);
           //  $output = json_decode($result);
           //  // $status = json_encode($output);
           //  // print_r($output->result);die;
           //  // echo $result;die;
           //  //close connection
           //  curl_close($ch);  
           //  // echo $output->status;
           //  // die;
            
           //  if($output->status == "true")
           //  {
           //      $url_array = $output->result;
           //  }
           //  else
           //  {
           //      $url_array = $output->result;
           //  }  

           //  $run_url = $url_array[0]->ip.$url_array[0]->first_folder.'&trans_guid='.$trans_guid;


            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_jasper_report_template";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'report_type' => $report_type,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $jasper_report_template_array = $output->result;
            }
            else
            {
                $jasper_report_template_array = $output->result;
            }  

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_acc_setting";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $database_name_array = $output->result;
            }
            else
            {
                $database_name_array = $output->result;
            }        

            $database_name = $database_name_array[0]->b2b_database;      

            $AppPOST = json_decode(file_get_contents('php://input'), true);

            // if (!empty($AppPOST)) {
            //     $type = $AppPOST['type'];
            //     $doc_template = $AppPOST['doc_template'];
            //     $customer_guid = $AppPOST['customer_guid'];
            //     $refno = $AppPOST['refno'];
            // } else {

            //     $type = $this->input->post('type');
            //     $doc_template = $this->input->post('doc_template');
            //     $customer_guid = $this->input->post('customer_guid');
            //     $refno = $this->input->post('refno');
                
            // }

            // $db = $this->lite_b2b_model->customer_info($customer_guid)->row('old_db');

            $doc_template_name = $jasper_report_template_array[0]->report_template;
            $doc_template = $doc_template_name.'.pdf';

            $db = $database_name;
            // $db = 'r_panda_backend';
            // echo $db;die;

            $refno = $trans_guid;

            $curl = curl_init();
            $url = "http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/B2BReports/$doc_template?db_be=$db&trans_guid=$refno&supcode=$supcode";
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
            header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$refno.'.pdf');
            echo $response; 

            curl_close($curl);  

            // echo $run_url;die;
            // redirect($run_url);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }  

    public function header_save_inv_no_by_supcode()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            // $trans_guid = $this->input->post('trans_guid');
            $b2b_inv_no = $this->input->post('b2b_inv_no');
            $consign_invoice_date = $this->input->post('consign_invoice_date');
            $submit_period = $this->input->post('submit_period');
            $submit_supcode = $this->input->post('submit_supcode');            
            // echo $b2b_inv_no,$consign_invoice_date,$submit_period,$submit_supcode;die;    
            // echo $trans_guid;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            // $query_loc = $this->session->userdata('query_loc');
            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_e_inv_number_header";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'b2b_inv_no' => $b2b_inv_no,
                'consign_invoice_date' => $consign_invoice_date,
                'submit_period' => $submit_period,
                'submit_supcode' => $submit_supcode,                
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $checkexists = 1;
                $update_status = 0;
                echo $update_status;die;
            }
            else
            {
                $checkexists = 0;
            } 



            $url = $this->api_url;

            $to_shoot_url = $url."/Update/U_consignment_sales_statement_header_b2b_inv_no_by_supcode";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'b2b_inv_no' => $b2b_inv_no,
                'consign_invoice_date' => $consign_invoice_date,
                'submit_period' => $submit_period,
                'submit_supcode' => $submit_supcode,                
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $update_status = 1;
            }
            else
            {
                $update_status = 0;
            }                      

            echo $update_status;

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }    

    public function consignment_generate_e_invoices_by_supcode()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $b2b_inv_no = $this->input->post('b2b_inv_no');
            $consign_invoice_date = $this->input->post('consign_invoice_date');
            $loc = $this->input->post('loc');
            $period_code = $this->input->post('submit_period');
            $supcode = $this->input->post('submit_supcode');
            $status = $this->input->post('status');            
            // print_r($this->input->post());die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            // $query_loc = $this->session->userdata('query_loc');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_e_inv_number_consignment_e_inv";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'b2b_inv_no' => $b2b_inv_no,
                'submit_supcode' => $supcode,                
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $this->session->set_flashdata('warning', 'Invoices number already existed');
                redirect(site_url('Consignment_report/consignment_sales_statement_by_supcode_list').'?status='.$status.'&loc='.$loc.'&period_code='.$period_code.'&supcode='.$supcode);
            }
            else
            {
                $checkexists = 0;
            }  
            
            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_consignment_e_invoice_by_supcode";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
                'period_code' => $period_code,
                'supcode' => $supcode,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->result == "0")
            {
                $check_e_invoices = 0;
            }
            else
            {
                $check_e_invoices = 1;
            }
            $check_e_invoices = 0;//skip checking of e invoices
            if($check_e_invoices > 0)
            {
                // echo 1;die;
                $this->session->set_flashdata('warning', 'E invoices already generated');
                redirect(site_url('Consignment_report/consignment_sales_statement_by_supcode_list').'?status='.$status.'&loc='.$loc.'&period_code='.$period_code.'&supcode='.$supcode);
            }
            else
            {
                // echo 2;die;
                $url = $this->api_url;

                $to_shoot_url = $url."/Insert/I_consignment_e_invoice_by_supcode";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                    'b2b_inv_no' => $b2b_inv_no,
                    'consign_invoice_date' => $consign_invoice_date,
                    'user_guid' => $user_guid,
                    'period_code' => $period_code,
                    'supcode' => $supcode,                    
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    {
                        $from_module = 'consignment_sales_statement';

                        $url = $this->api_url;

                        $to_shoot_url = $url."/Insert/I_supplier_movement";
                        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                        $data = array(
                            'customer_guid' => $customer_guid,
                            'user_guid' => $user_guid,
                            'action' => 'generate_con_inv',
                            'value' => $period_code.'_'.$supcode,
                            'from_module' => $from_module,
                        );

                        $cuser_name = 'ADMIN';
                        $cuser_pass = '1234';

                        $ch = curl_init($to_shoot_url);
                       // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        $result = curl_exec($ch);
                        $output = json_decode($result);
                        // $status = json_encode($output);
                        // print_r($output->result);die;
                        // echo $result;die;
                        //close connection
                        curl_close($ch); 

                        $url = $this->api_url;

                        $to_shoot_url = $url."/Update/U_consignment_sales_statement_status_by_supcode";
                        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                        $data = array(
                            'customer_guid' => $customer_guid,
                            // 'trans_guid' => $trans_guid,
                            'status' => 'Invoice Generated',
                            'period_code' => $period_code,
                            'supcode' => $supcode,     
                        );

                        $cuser_name = 'ADMIN';
                        $cuser_pass = '1234';

                        $ch = curl_init($to_shoot_url);
                       // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        $result = curl_exec($ch);
                        $output = json_decode($result);
                        // $status = json_encode($output);
                        // print_r($output->result);die;
                        // echo $result;die;
                        //close connection
                        curl_close($ch); 
                    }

                    $check_e_invoices = count($output->result);

                    $this->session->set_flashdata('message', 'E invoices generated');
                    redirect(site_url('Consignment_report/consignment_sales_statement_by_supcode_list').'?status='.$status.'&loc='.$loc.'&period_code='.$period_code.'&supcode='.$supcode);
                }
                else
                {
                    $check_e_invoices = count($output->result);

                    $this->session->set_flashdata('warning', 'E invoices not generated, please try again');
                    redirect(site_url('Consignment_report/consignment_sales_statement_by_supcode_list').'?status='.$status.'&loc='.$loc.'&period_code='.$period_code.'&supcode='.$supcode);
                }
            }

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function consignment_e_inv_export()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            // $loc = $_REQUEST['loc'];
            // echo $loc;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['docdate']))
            {
                $docdate = $_REQUEST['docdate'];
            }
            else
            {
                $docdate = '';
            }

            if(isset($_REQUEST['status']))
            {
                $status = $_REQUEST['status'];
            }
            else
            {
                $status = '';
            }  

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }
            // echo $speriod_code;die;
            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_acc_setting";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            if($output->result == true || $output->result == "true" || $output->result == 1 || $output->result == "1")
            {
                // echo 1;die;
                // $show_upload_e_invoice = '1';
                $account_type = $output->result[0]->accounting_doc;
                // echo $target_dir_supplier_guid;die;
                // die;
            }
            else
            {
                // $target_dir_supplier_guid = '';
                echo "Error. Please Contact Admin";
                die;
            } 
            // echo $account_type;die;

            if($account_type == 'nav')
            {
                $datatable_url = site_url('Consignment_report/view_table_export_consignment_e_inv?period_code='.$speriod_code);
                $export_link = site_url('Consignment_report/consignment_e_inv_export_out');
            }
            else if($account_type == 'autocount')
            {
                $datatable_url = site_url('Consignment_report/view_table_export_consignment_e_inv_autocount?period_code='.$speriod_code);
                $export_link = site_url('Consignment_report/consignment_e_inv_export_out_autocount');
            }

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_status";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $consignment_sales_statement_status = $output->result;
            }
            else
            {
                $consignment_sales_statement_status = $output->result;
            } 


            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_get_consignment_sales_statement_period";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $period_code = $output->result;
            }
            else
            {
                $period_code = $output->result;
            } 

            // echo $datatable_url;die;
            $data = array(
                // 'period_code' => $period_code,
                'datatable_url' => $datatable_url,
                // 'loc' => $loc,
                'status' => $status,
                'consignment_sales_statement_status' => $consignment_sales_statement_status,
                'period_code' => $period_code,
                'speriod_code' => $speriod_code,
                'export_excel_path' => $export_link,
            );
            
            $this->load->view('header');
            if($account_type == 'nav')
            {
                $this->load->view('Consignment/consignment_e_inv_export',$data);
            }
            else if($account_type == 'autocount')
            {
                $this->load->view('Consignment/consignment_e_inv_export_autocount',$data);
            }            
            // $this->load->view('Consignment/consignment_e_inv_export',$data);
            $this->load->view('footer'); 

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }   

    public function view_table_export_consignment_e_inv()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }            
            // $xloc = $loc;
            // $xstatus = $status;
            $xperiod_code = $speriod_code; 

            // echo $lstatus;
            // die;

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0); 

            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search= $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";
            $site_url = site_url().'/';

            $query_supcode = $this->session->userdata('query_supcode');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_view_table_consignment_e_inv";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            // print_r($order);die;
            $data =  array(       
              'draw' => $draw,
              'start' => $start,
              'length' => $length,
              'order' => $order,
              'search'=>  $search,
              'col' => $col,
              'dir' => $dir,
              'customer_guid' => $customer_guid,
              // 'status' => $lstatus,
              // 'loc' => $loc,
              'site_url' => $site_url,
              'period_code' => $speriod_code,
              // 'xloc' => $xloc,
              // 'xstatus' => $xstatus,
              'query_supcode' => $query_supcode,
              'view_all' => in_array('IAVA',$_SESSION['module_code']) ? 1 : 0,
              // 'view_all' => 0,
              'user_guid' => $user_guid,
            );
            // print_r($data);die;
            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;
            }
            else
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;                
            }
            // print_r($result);die;             

            // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

            $output = array(
              "draw" => $draw,
              "recordsTotal" => $recordsTotal,
              "recordsFiltered" => $recordsFiltered,
              "data" => $data
            );

            echo json_encode($output);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    } 

    public function consignment_e_inv_export_out()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $sessiondata = array(
                            'done_download' => '0',
                        );

            $this->session->set_userdata($sessiondata);

            $customer_guid = $this->session->userdata('customer_guid');
            $period_code = $this->input->post('excel_period_code');
            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_export_consignment_e_inv";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $q_result = $output->result;
                $q_result_array = $output->result_array;
            // print_r($q_result_array);die;      
            }
            else
            {
                $q_result = $output->result;
                $q_result_array = $output->result_array;
            }       
            // print_r($q_result);die;
            // $q_result = $this->db->query("SELECT * FROM lite_b2b.set_user LIMIT 1");
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $set_paramater_header = array();
            // foreach($q_result AS $header => $row)
            // {
            foreach($q_result[0] as $xheader => $xrow)
            {
                $set_paramater_header[] = $xheader;
                continue;
            }
              //echo $header_name; exit();
            // }
            // print_r($set_paramater_header);die;
            $x = 'A';

            $row_head1 = ['','','PURCHASE INVOICE','VENDOR INVOICE','POSTING','PURCHASE INVOICE','','','','GL', 'OUTLET','PRODUCT','',''];

            foreach($row_head1 AS $header_name)
            {
              $objPHPExcel->getActiveSheet()->SetCellValue($x.'1', $header_name);
            //echo $header_name.$x; 
              $objPHPExcel->getActiveSheet()->getStyle($x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

              $objPHPExcel->getActiveSheet()->getStyle($x.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

              $x++;
             
            }

            $x = 'A';

            $row_head2 = ['VENDOR NO.','VENDOR NAME','DATE','NO','DATE','UNIT PRICE','GST CODE','CURRENCY','DESCRIPTION','CODE','CODE','GROUP','QUANTITY','POSTING DESCRIPTION'];

            foreach($row_head2 AS $header_name)
            {
              $objPHPExcel->getActiveSheet()->SetCellValue($x.'2', $header_name);
            //echo $header_name.$x; 
              $objPHPExcel->getActiveSheet()->getStyle($x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
              
              $objPHPExcel->getActiveSheet()->getStyle($x.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

              $x++;
             
            }
         
            $rowCount = '3';

            foreach($q_result AS $row)
            { 
                $c = 'A';
                
                foreach($set_paramater_header AS $header_name)
                {


                    $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, $row->$header_name);
                    $objPHPExcel->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
                    $c++;
                    
                    
                  //echo $header_name; exit();
                }

                $objPHPExcel->getActiveSheet()->getStyle($c)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $rowCount++;
                
            }
                
            $objPHPExcel->getActiveSheet()->getStyle('F0:F'.$rowCount)->getNumberFormat()->setFormatCode("_(* #,##0.00_);_(* \(#,##0.00\);_(* \"-\"??_);_(@_)");
            $objPHPExcel->getActiveSheet()->getStyle('E0:E'.$rowCount)->getNumberFormat()->setFormatCode("dd/mm/yyyy");
            $objPHPExcel->getActiveSheet()->getStyle('C0:C'.$rowCount)->getNumberFormat()->setFormatCode("dd/mm/yyyy");

            $today = $this->db->query("SELECT date(now()) as today")->row('today');

            // $filename = $today.'.XLSX'; //save our workbook as this file name
            $fileName = $defined_name.$today.'.xlsx';
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$fileName.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            header("Pragma: no-cache");
            header("Expires: 0");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            ob_end_clean();
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');

            $customer_guid = $this->session->userdata('customer_guid');
            $url = $this->api_url;

            $to_shoot_url = $url."/Update/U_export_consignment_e_inv";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch); 

            $sessiondata = array(
                            'done_download' => '1',
                        );

            $this->session->set_userdata($sessiondata);

            $this->session->set_flashdata('message', 'Excel Downloaded');    

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }     

    public function check_download_session()
    {

        if($this->session->userdata('done_download') == 1)
        {
            $data = array(
                'done_download' => 1
            );
        }
        else
        {
            $data = array(
                'done_download' => 0
            );
        }

        echo json_encode($data);

    }            

    public function view_table_export_consignment_e_inv_autocount()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $query_loc = $this->session->userdata('query_loc');

            if(isset($_REQUEST['period_code']))
            {
                $speriod_code = $_REQUEST['period_code'];
            }
            else
            {
                $url = $this->api_url;

                $to_shoot_url = $url."/Select/S_default_consignment_sales_statement_period_code";
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
                $data = array(
                    'customer_guid' => $customer_guid,
                );

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
               // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);  
                // echo $output->status;
                // die;
                
                if($output->status == "true")
                {
                    $speriod_code = $output->result;
                }
                else
                {
                    $period_code = $output->result;
                }          
                $speriod_code = $period_code[0]->now;
                // echo $period_code;die;       
                // $period_code = '';
            }            
            // $xloc = $loc;
            // $xstatus = $status;
            $xperiod_code = $speriod_code; 

            // echo $lstatus;
            // die;

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0); 

            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search= $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";
            $site_url = site_url().'/';

            $query_supcode = $this->session->userdata('query_supcode');

            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_view_table_consignment_e_inv_autocount";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            // print_r($order);die;
            $data =  array(       
              'draw' => $draw,
              'start' => $start,
              'length' => $length,
              'order' => $order,
              'search'=>  $search,
              'col' => $col,
              'dir' => $dir,
              'customer_guid' => $customer_guid,
              // 'status' => $lstatus,
              // 'loc' => $loc,
              'site_url' => $site_url,
              'period_code' => $speriod_code,
              // 'xloc' => $xloc,
              // 'xstatus' => $xstatus,
              'query_supcode' => $query_supcode,
              'view_all' => in_array('IAVA',$_SESSION['module_code']) ? 1 : 0,
              // 'view_all' => 0,
              'user_guid' => $user_guid,
            );
            // print_r($data);die;
            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;
            }
            else
            {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;                
            }
            // print_r($result);die;             

            // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

            $output = array(
              "draw" => $draw,
              "recordsTotal" => $recordsTotal,
              "recordsFiltered" => $recordsFiltered,
              "data" => $data
            );

            echo json_encode($output);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }    

    public function consignment_e_inv_export_out_autocount()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 1;die;
            $sessiondata = array(
                            'done_download' => '0',
                        );

            $this->session->set_userdata($sessiondata);

            $customer_guid = $this->session->userdata('customer_guid');
            $period_code = $this->input->post('excel_period_code');
            $url = $this->api_url;

            $to_shoot_url = $url."/Select/S_export_consignment_e_inv_autocount";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch);  
            // echo $output->status;
            // die;
            
            if($output->status == "true")
            {
                $q_result = $output->result;
                $q_result_array = $output->result_array;
            // print_r($q_result_array);die;      
            }
            else
            {
                $q_result = $output->result;
                $q_result_array = $output->result_array;
            }       
            // print_r($q_result);die;
            // $q_result = $this->db->query("SELECT * FROM lite_b2b.set_user LIMIT 1");
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $set_paramater_header = array();
            // foreach($q_result AS $header => $row)
            // {
            foreach($q_result[0] as $xheader => $xrow)
            {
                $set_paramater_header[] = $xheader;
                continue;
            }
              //echo $header_name; exit();
            // }
            // print_r($set_paramater_header);die;
            $x = 'A';

            $row_head1 = ['(20 chars)','(Date: dd/MM/yyyy)','(12 chars)','(20 chars)','(10 chars)','(30 chars)','(12 chars)','(80 chars)','(Number, use System Currency Rate Decimal)','(20 chars)','(Rich Text)','(Boolean: T or F)','(12 chars)','(Number, use System Currency Rate Decimal)','(100 chars)','(10 chars)','(10 chars)','(8 chars)','(Number, use System Currency Decimal)','(Number, use System Currency Decimal)','(Number, use System Currency Decimal)'];

            foreach($row_head1 AS $header_name)
            {
              $objPHPExcel->getActiveSheet()->SetCellValue($x.'1', $header_name);
            //echo $header_name.$x; 
              $objPHPExcel->getActiveSheet()->getStyle($x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

              // $objPHPExcel->getActiveSheet()->getStyle($x.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

              $x++;
             
            }

            $x = 'A';

            $row_head2 = ['DocNo','DocDate','CreditorCode','SupplierInvoiceNo','JournalType','DisplayTerm','PurchaseAgent','Description','CurrencyRate','RefNo2','Note','InclusiveTax','AccNo','ToAccountRate','DetailDescription','ProjNo','DeptNo','TaxType','TaxableAmt','TaxAdjustment','Amount'];

            foreach($row_head2 AS $header_name)
            {
              $objPHPExcel->getActiveSheet()->SetCellValue($x.'2', $header_name);
            //echo $header_name.$x; 
              $objPHPExcel->getActiveSheet()->getStyle($x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
              
              $objPHPExcel->getActiveSheet()->getStyle($x.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('808080');

              $x++;
             
            }
         
            $rowCount = '3';
            $trans_guid_ori = '';
            foreach($q_result AS $row)
            { 
                $c = 'A';
                
                if($row->trans_guid != $trans_guid_ori)
                {
                    $trans_guid_ori = $row->trans_guid;

                    foreach($set_paramater_header AS $key => $header_name)
                    {
                        if($key == 1)
                        {
                            // echo $row->$header_name;die;

                            $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, $row->$header_name);
                            // $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, strtotime($row->$header_name));
                            // $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, PHPExcel_Shared_Date::PHPToExcel( strtotime($row->$header_name) ));
                            // $sheet->setCellValueByColumnAndRow(0, 1, PHPExcel_Shared_Date::PHPToExcel( '2014-10-16' ));
                            $objPHPExcel->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
                            $c++;
                        }
                        else
                        {
                            // print_r($header_name);
                            $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, $row->$header_name);
                            $objPHPExcel->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
                            $c++;
                        }
                        
                        
                      //echo $header_name; exit();
                    }
                }
                else
                {
                    foreach($set_paramater_header AS $key => $header_name)
                    {
                        if($key <= 11)
                        {
                            // print_r($header_name);
                            $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, '');
                            $objPHPExcel->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
                            $c++;
                        }
                        else
                        {
                            $objPHPExcel->getActiveSheet()->SetCellValue($c.$rowCount, $row->$header_name);
                            $objPHPExcel->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
                            $c++;
                        }
                        
                        
                      //echo $header_name; exit();
                    }
                }

                $objPHPExcel->getActiveSheet()->getStyle($c)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $rowCount++;
                
            }
            // die;
                
            // $objPHPExcel->getActiveSheet()->getStyle('F0:F'.$rowCount)->getNumberFormat()->setFormatCode("_(* #,##0.00_);_(* \(#,##0.00\);_(* \"-\"??_);_(@_)");
            // $objPHPExcel->getActiveSheet()->getStyle('E0:E'.$rowCount)->getNumberFormat()->setFormatCode("dd/mm/yyyy");
            $objPHPExcel->getActiveSheet()->getStyle('B0:B'.$rowCount)->getNumberFormat()->setFormatCode("dd/mm/yyyy");
            $objPHPExcel->getActiveSheet()->getStyle('I0:I'.$rowCount)->getNumberFormat()->setFormatCode('0.00000000');
            $objPHPExcel->getActiveSheet()->getStyle('N0:N'.$rowCount)->getNumberFormat()->setFormatCode('0.00000000');
            $objPHPExcel->getActiveSheet()->getStyle('S0:S'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
            $objPHPExcel->getActiveSheet()->getStyle('U0:U'.$rowCount)->getNumberFormat()->setFormatCode('0.00');
            // $objPHPExcel->getActiveSheet()->getStyle('B0:B'.$rowCount)->getNumberFormat()->setFormatCode('0');

            $today = $this->db->query("SELECT date(now()) as today")->row('today');

            // $filename = $today.'.XLSX'; //save our workbook as this file name
            $fileName = $defined_name.$today.'.xlsx';
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$fileName.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            header("Pragma: no-cache");
            header("Expires: 0");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            ob_end_clean();
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');

            $customer_guid = $this->session->userdata('customer_guid');
            $url = $this->api_url;

            $to_shoot_url = $url."/Update/U_export_consignment_e_inv";
            // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
            $data = array(
                'customer_guid' => $customer_guid,
            );

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
           // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            // print_r($output->result);die;
            // echo $result;die;
            //close connection
            curl_close($ch); 

            $sessiondata = array(
                            'done_download' => '1',
                        );

            $this->session->set_userdata($sessiondata);

            $this->session->set_flashdata('message', 'Excel Downloaded');    

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }     

    #jr created for reset consignment
    public function reset_consignment_sales()
    {
        $supcus_code = $this->input->post('supcus_code');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $user_guid = $this->session->userdata('user_guid');
        $customer_guid = $this->session->userdata('customer_guid');
        $database1 = 'lite_b2b';
        $database2 = $this->db->query("SELECT b2b_database,b2b_hub_database FROM $database1.acc WHERE acc_guid = '$customer_guid'"); //get database
        $database3 = $database2->row('b2b_database'); //backend
        $database4= $database2->row('b2b_hub_database'); //hub
        $controller = $this->router->fetch_class();
        $function = $this->router->fetch_method();
        $now_time = $this->db->query("SELECT NOW() AS now_time")->row('now_time');
        $user_id = $this->db->query("SELECT a.user_id FROM $database1.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
        
        if(($database3 == '') || ($database3 == 'null') || ($database3 == null))
        {
            $data = array(
              'para1' => 1,
              'msg' => 'Invalid Database Backend.',
            );    
            echo json_encode($data); 
            die; 
        }

        if(($database4 == '') || ($database4 == 'null') || ($database4 == null))
        {
            $data = array(
              'para1' => 1,
              'msg' => 'Invalid Database Hub.',
            );    
            echo json_encode($data); 
            die; 
        }

        $check_acc_trans_backend = $this->db->query("SELECT * FROM $database3.acc_trans WHERE supcus_code = '$supcus_code' AND date_trans BETWEEN '$date_from' AND '$date_to' AND status = 'Invoice Generated'");

        if($check_acc_trans_backend->num_rows() == 0)
        {
            $data = array(
              'para1' => 1,
              'msg' => 'Empty Data.',
            );    
            echo json_encode($data); 
            die; 
        }

        $logs_1 = array(
          'logs_controller' => $controller,
          'logs_function' => $function,
          'logs_query' => $this->db->last_query(),
          'logs_details' => json_encode($check_acc_trans_backend->result()),
          'created_at' => $now_time,
          'created_by' => $user_id,
          'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),      
        );   
        $this->db->insert($database3.'.update_logs',$logs_1);

        $check_e_invoices_backend = $this->db->query("SELECT * FROM $database3.`consignment_e_invoices` WHERE supcus_code = '$supcus_code' AND date_trans BETWEEN '$date_from' AND '$date_to'");

        if($check_e_invoices_backend->num_rows() == 0)
        {
            $data = array(
              'para1' => 1,
              'msg' => 'Empty Data.',
            );    
            echo json_encode($data); 
            die; 
        }

        $logs_2 = array(
          'logs_controller' => $controller,
          'logs_function' => $function,
          'logs_query' => $this->db->last_query(),
          'logs_details' => json_encode($check_e_invoices_backend->result()),
          'created_at' => $now_time,
          'created_by' => $user_id,
          'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),      
        );   
        $this->db->insert($database3.'.update_logs',$logs_2);

        $check_e_invoices_hub = $this->db->query("SELECT * FROM $database4.`consignment_e_invoices` WHERE supcus_code = '$supcus_code' AND date_trans BETWEEN '$date_from' AND '$date_to'");

        if($check_e_invoices_hub->num_rows() == 0)
        {
            $data = array(
              'para1' => 1,
              'msg' => 'Empty Data.',
            );    
            echo json_encode($data); 
            die; 
        }

        $logs_3 = array(
          'logs_controller' => $controller,
          'logs_function' => $function,
          'logs_query' => $this->db->last_query(),
          'logs_details' => json_encode($check_e_invoices_hub->result()),
          'created_at' => $now_time,
          'created_by' => $user_id,
          'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),      
        );   
        $this->db->insert($database3.'.update_logs',$logs_3);

        $delete_e_invoices_backend = $this->db->query("DELETE FROM $database3.`consignment_e_invoices` WHERE supcus_code = '$supcus_code' AND date_trans BETWEEN '$date_from' AND '$date_to'");

        $logs_4 = array(
          'logs_controller' => $controller,
          'logs_function' => $function,
          'logs_query' => $this->db->last_query(),
          //'logs_details' => json_encode($check_e_invoices_backend->result()),
          'created_at' => $now_time,
          'created_by' => $user_id,
          'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),      
        );   
        $this->db->insert($database3.'.update_logs',$logs_4);

        $delete_e_invoices_hub = $this->db->query("DELETE FROM $database4.`consignment_e_invoices` WHERE supcus_code = '$supcus_code' AND date_trans BETWEEN '$date_from' AND '$date_to'");

        $logs_5 = array(
          'logs_controller' => $controller,
          'logs_function' => $function,
          'logs_query' => $this->db->last_query(),
          //'logs_details' => json_encode($check_e_invoices_hub->result()),
          'created_at' => $now_time,
          'created_by' => $user_id,
          'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),      
        );   
        $this->db->insert($database3.'.update_logs',$logs_5);

        $update_e_invoices = $this->db->query("UPDATE $database3.acc_trans SET sup_doc_no = '', sup_doc_date = '1001-01-01', STATUS = '', b2b_inv_no = '', b2b_inv_no_time = '1001-01-01 00:00:00' WHERE supcus_code = '$supcus_code' AND date_trans BETWEEN '$date_from' AND '$date_to'");

        $last_query = $this->db->last_query();

        $check_acc_trans_after_update = $this->db->query("SELECT * FROM $database3.acc_trans WHERE supcus_code = '$supcus_code' AND date_trans BETWEEN '$date_from' AND '$date_to'");

        $logs_6 = array(
          'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
          'logs_controller' => $controller,
          'logs_function' => $function,
          'logs_query' => $last_query,
          'logs_details' => json_encode($check_acc_trans_after_update->result()),
          'created_at' => $now_time,
          'created_by' => $user_id, 
        );   
        
        $error = $this->db->affected_rows();

        if($error > 0){

           $data = array(
            'para1' => 0,
            'msg' => 'Update Successfully',

            );    
            echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error.',

            );    
            echo json_encode($data);   
        }

        $this->db->insert($database3.'.update_logs',$logs_6);
    }    
}
?>
