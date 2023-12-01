<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class b2b_billing_invoice_controller extends CI_Controller {
    
    public function __construct()
    {   
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->model('Datatable_model');
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

    }

    public function invoices_new()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {     
            $user_guid = $_SESSION['user_guid'];
            $file_path = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '8D5B38E931FA11E79E7E33210BD612D3'");
            $path = $file_path->row('file_path');
            $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid,'web','manual_guide','sec_path','MNLGS');
            $defined_path = $file_config_main_path.$path;
            $manual_guide = $this->db->query("SELECT title,file_name FROM lite_b2b.manual_guide WHERE active = '1' AND lang_type = 'EN' AND title = 'Upload Payment Remitttance' LIMIT 1");

            if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_FINANCE') {
                $get_customer = $this->db->query("SELECT acc_guid, acc_name,seq 
                FROM `acc` WHERE isactive = 1 order by seq asc,row_seq ASC");

                $get_supplier_name_list = $this->db->query("SELECT a.supplier_guid, b.`supplier_name` FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` GROUP BY a.supplier_guid ORDER BY b.supplier_name ASC ");

                $get_period_code = $this->db->query("SELECT a.* FROM ( SELECT period_code FROM b2b_invoice.supplier_monthly_main GROUP BY period_code ) a GROUP BY period_code ORDER BY period_code DESC ");

                // $get_period_code = $this->db->query("SELECT a.* FROM ( SELECT period_code FROM b2b_invoice.supplier_monthly_main GROUP BY period_code UNION ALL SELECT period_code FROM b2b_invoice.inv_doc GROUP BY period_code ) a GROUP BY period_code ORDER BY period_code DESC ");

                $data = array(
                    'customer' => $get_customer,
                    'defined_path' => $defined_path,
                    'file_name' => $manual_guide->row('file_name'),
                    'title' => $manual_guide->row('title'),
                    'get_supplier_name_list' => $get_supplier_name_list,
                    'get_period_code' => $get_period_code,
                    );
                    // print_r($data);die;


                    $this->load->view('header');
                    $this->load->view('b2b_billing_invoice/invoices', $data);
                    $this->load->view('footer'); 
                
            } else {

                $get_customer = $this->db->query("SELECT DISTINCT d.acc_guid,e.acc_name,e.seq FROM `set_user` AS a 
                INNER JOIN `set_user_branch` b ON a.user_guid = b.user_guid
                INNER JOIN `acc_branch` c ON b.branch_guid = c.branch_guid 
                INNER JOIN `acc_concept` d ON c.concept_guid = d.concept_guid 
                INNER JOIN `acc` e ON d.`acc_guid` = e.`acc_guid` 
                where a.user_id = '".$_SESSION['userid']."' 
                and e.isactive = '1' 
                and a.module_group_guid = '".$_SESSION['module_group_guid']."' 
                order by e.seq asc,e.row_seq ASC");
                // echo $this->db->last_query();die;

                $data = array(
                    'customer' => $get_customer,
                    'defined_path' => $defined_path,
                    'file_name' => $manual_guide->row('file_name'),
                    'title' => $manual_guide->row('title'),
                );
                // print_r($data);die;
        
                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('b2b_billing_invoice/invoices_new', $data);
                $this->load->view('footer');  
        
            }
            
        }
        else
        {
            redirect('#');
        }    
    }

    public function invoice_tb()
    {
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

        if(!empty($order))
        {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];
          }
        }

        if($dir != "asc" && $dir != "desc")
        {
          $dir = "desc";
        }

        $valid_columns = array(
            0=>'name',
            1=>'invoice_number',
            2=>'invoice_date',
            3=>'cn_inv_no',
            4=>'total_include_tax',
            5=>'created_at',
        );

        if(!isset($valid_columns[$col]))
        {
          $order = null;
        }
        else
        {
          $order = $valid_columns[$col];
        }

        if($order !=null)
        {   
          // $this->db->order_by($order, $dir);

          $order_query = "ORDER BY " .$order. "  " .$dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if(!empty($search))
        {
          $x=0;
          foreach($valid_columns as $sterm)
          {
              if($x==0)
              {
                  // $this->db->like($sterm,$search);

                  $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

              }
              else
              {
                  // $this->db->or_like($sterm,$search);

                  $like_second_query .= "OR $sterm LIKE '%".$search."%'";

              }
              $x++;
          }
           
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;
        //$group_query = " GROUP BY b.supplier_guid ";

        $user_guid = $_SESSION['user_guid'];
        $new_guid = '';
        $combine_guid = '';
        $array = array();
        $array_name = array();
        $array1 = array();

        // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
        // {
        //     $user_guid = 'E9E3C7AAAB4811ED8D8C000D3AA232B1';
        // }

        if ($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_FINANCE') {
            
            $sql = "SELECT * FROM (SELECT 'Subscription' AS invoice_type, a.`inv_guid`, a.`biller_guid`, a.`name`, a.`invoice_number`, a.`inv_status`, a.`inv_date`, DATE_FORMAT(a.`inv_date`, '%d-%M-%Y') AS invoice_date, a.`period_code`, a.`total_include_tax`, a.`final_amount`, a.`created_at`,b.`inv_guid` AS cn_inv_guid, b.`invoice_number` AS cn_inv_no FROM b2b_invoice.supplier_monthly_main a LEFT JOIN b2b_invoice.`supplier_cn_main` b ON a.invoice_number = b.monthly_invoice_number WHERE a.inv_status != 'New' GROUP BY a.`inv_guid` ) a ORDER BY a.name ASC";
            //ORDER BY FIELD (a.sorting_two, '1','2','3') ASC , FIELD (a.sorting, '1','2') ASC  , a.slip_created_at DESC
        }
        else{

            $supplier_guid = $this->db->query("SELECT a.supplier_guid, b.`supplier_name`, b.`old_supplier_name` FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.user_guid = '$user_guid' GROUP BY a.supplier_guid");

            foreach($supplier_guid->result() as $key)
            {
                $array[] = $key->supplier_guid;
                $array_name[] = addslashes($key->supplier_name);
                $array_name[] = addslashes($key->old_supplier_name);
            }
    
            $guid = implode("','", $array);
            $guid = "'".$guid."'";
    
            $name = implode("','", $array_name);
            $name = "'".$name."'";
    
            $check_group = $this->db->query("SELECT * FROM b2b_invoice.group_supplier_general WHERE main_supp_guid IN ($guid) ");
    
            if($check_group->num_rows() > 0)
            {
                foreach($check_group->result() as $row)
                {
                    $array1[] = $row->sub_supp_guid;
                }
        
                $new_guid = implode("','", $array1);
                $new_guid = "'".$new_guid."'";
            }
    
            if($new_guid != '')
            {
                $combine_guid = $guid.','.$new_guid;
            }
            else
            {
                $combine_guid = $guid;
            }

            $sql = "SELECT * FROM ( SELECT 'Subscription' AS invoice_type, a.`inv_guid`, a.`biller_guid`, a.`name`, a.`invoice_number`, a.`inv_status`, a.`inv_date`, DATE_FORMAT(a.`inv_date`, '%d-%M-%Y') AS invoice_date, a.`period_code`, a.`total_include_tax`, a.`final_amount`, a.`created_at`,b.`inv_guid` AS cn_inv_guid, b.`invoice_number` AS cn_inv_no FROM b2b_invoice.supplier_monthly_main a LEFT JOIN b2b_invoice.`supplier_cn_main` b ON a.invoice_number = b.monthly_invoice_number WHERE a.biller_guid IN ($combine_guid) AND a.name IN ($name) AND a.inv_status != 'New' GROUP BY a.`inv_guid` ) a ORDER BY a.name ASC";
            // print_r($sql);die;
            //($guid)
        }

        //print_r($query); die;
        $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query. $order_query .$limit_query;

        $result = $this->db->query($query);

        //echo $this->db->last_query(); die;


        if(!empty($search))
        {
            $query_filter = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        }
        else
        {
            $total = $this->db->query($sql)->num_rows();
        }

        $data = array();
        foreach($result->result() as $row)
        {
            
            $nestedData['name'] = $row->name;
            $nestedData['invoice_number'] = $row->invoice_number;
            $nestedData['inv_guid'] = $row->inv_guid;
            $nestedData['period_code'] = $row->period_code;
            $nestedData['total_include_tax'] = $row->total_include_tax;
            $nestedData['final_amount'] = $row->final_amount;
            $nestedData['inv_status'] = $row->inv_status;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['biller_guid'] = $row->biller_guid;
            // $nestedData['file_status'] = $row->file_status;
            // $nestedData['slip_created_at'] = $row->slip_created_at;
            // $nestedData['slip_created_by'] = $row->slip_created_by;
            // $nestedData['slip_updated_at'] = $row->slip_updated_at;
            // $nestedData['slip_updated_by'] = $row->slip_updated_by;
            $nestedData['invoice_type'] = $row->invoice_type;
            // $nestedData['sorting'] = $row->sorting;
            // $nestedData['sorting_two'] = $row->sorting_two;
            // $nestedData['variance_status'] = $row->variance_status;
            $nestedData['invoice_date'] = $row->invoice_date;
            $nestedData['cn_inv_no'] = $row->cn_inv_no;
            $nestedData['cn_inv_guid'] = $row->cn_inv_guid;
            
            

            $data[] = $nestedData;

        }

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);
    }

    public function invoice_tb_admin()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $status_data = $this->input->post('filter_status_data');
        $supplier_guid = $this->input->post('filter_supplier_guid');
        $period_code = $this->input->post('filter_period_code');

        if($status_data == '' && $period_code == '' && $supplier_guid == '')
        {
            $condition = '';
        }
        else if($status_data != '' && $period_code == '' && $supplier_guid == '')
        {
            $condition = " WHERE a.file_status = '$status_data' AND a.inv_status = 'Emailed'";
        }
        else if($status_data != '' && $period_code != '' && $supplier_guid == '')
        {
            $condition = " WHERE a.file_status = '$status_data' AND a.inv_status = 'Emailed' AND LEFT(a.inv_date,7) = '$period_code' ";
        }
        else if($status_data != '' && $period_code != '' && $supplier_guid != '')
        {
            $condition = " WHERE a.file_status = '$status_data' AND a.inv_status = 'Emailed' AND LEFT(a.inv_date,7) = '$period_code' AND a.biller_guid = '$supplier_guid'  ";
        }
        else if($status_data == '' && $period_code != '' && $supplier_guid == '')
        {
            $condition = "WHERE LEFT(a.inv_date,7) = '$period_code'";
        }
        else if($status_data == '' && $period_code != '' && $supplier_guid != '')
        {
            $condition = "WHERE LEFT(a.inv_date,7) = '$period_code' AND a.biller_guid = '$supplier_guid'";
        }
        else if($status_data == '' && $period_code == '' && $supplier_guid != '')
        {
            $condition = "WHERE a.biller_guid = '$supplier_guid'";
        }

        $query_data = $this->db->query("SELECT * FROM (SELECT 'Subscription' AS invoice_type, a.`inv_guid`, a.`biller_guid`, a.`name`, a.`invoice_number`, a.`inv_status`, a.`inv_date`, DATE_FORMAT(a.`inv_date`, '%d-%M-%Y') AS invoice_date, a.`period_code`, a.`total_include_tax`, a.`final_amount`, a.`created_at`,b.`inv_guid` AS cn_inv_guid, b.`invoice_number` AS cn_inv_no FROM b2b_invoice.supplier_monthly_main a LEFT JOIN b2b_invoice.`supplier_cn_main` b ON a.invoice_number = b.monthly_invoice_number WHERE a.inv_status != 'New' GROUP BY a.`inv_guid` ) a $condition ORDER BY a.name ASC");
        //echo $this->db->last_query();die;
        $data = array(  
            'query_data' => $query_data->result(),
        );

        echo json_encode($data); 
    }

    public function invoice_upload_process()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $user_guid = $this->session->userdata('user_guid');
            $supplier_guid = $this->input->post("supplier_guid");
            $invoice_no = $this->input->post("invoice_number");
            $remark_slip = $this->input->post("remark_slip");

            //$url = $this->file_config_b2b->file_path_name($customer_guid,'app','general_doc','general_internal_ip','GIPL');

            $to_shoot_url = "https://api.xbridge.my/rest_b2b/index.php/Invoice_remittance";

            //echo $to_shoot_url; die;

            if(($user_guid == '') || ($user_guid == 'null') || ($user_guid == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid User GUID.',
                );
                echo json_encode($data);
                exit();
            }

            if(($supplier_guid == '') || ($supplier_guid == 'null') || ($supplier_guid == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Supplier GUID.',
                );
                echo json_encode($data);
                exit();
            }

            if(($invoice_no == '') || ($invoice_no == 'null') || ($invoice_no == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Invoice Number.',
                );
                echo json_encode($data);
                exit();
            }

            if ($_FILES['file']['error'] != 0) 
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Post File Error.',
                );
                echo json_encode($data);
                exit();
            }

            $data = array(
                'supplier_guid' => $supplier_guid,
                'user_guid' => $user_guid,
                'invoice_no' => $invoice_no,
                'upload_remittance_doc'=> new CURLFILE($_FILES['file']['tmp_name'],$_FILES['file']['type'],$_FILES['file']['name']),
                'remark' => $remark_slip,
            );
            //print_r($data); die;

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
            //$status = json_encode($output);
            // print_r($output->result);die;
            //echo $result;die;
            //close connection
            curl_close($ch);  
            //echo $output->status;
            //die;
            
            if($output->status == "true")
            {
                $msg = $output->message;

                $data = array(
                'para' => 0,
                'msg' => $msg,
                );

                echo json_encode($data);
            }
            else
            {
                $msg = $output->message;

                $data = array(
                'para' => 1,
                'msg' => $msg,
                );

                echo json_encode($data);
            }
        }
        else
        {
            redirect('#');
        }
    }

    public function invoice_view_process()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            
            $supplier_guid = $this->input->post("supplier_guid");
            $invoice_no = $this->input->post("invoice_number");

            $to_shoot_url = "https://api.xbridge.my/rest_b2b/index.php/Invoice_remittance/view_invoice_remittance_document?supplier_guid=$supplier_guid&invoice_no=$invoice_no";

            //echo $to_shoot_url; die;

            if(($supplier_guid == '') || ($supplier_guid == 'null') || ($supplier_guid == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Supplier GUID.',
                );
                echo json_encode($data);
                exit();
            }

            if(($invoice_no == '') || ($invoice_no == 'null') || ($invoice_no == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Invoice Number.',
                );
                echo json_encode($data);
                exit();
            }

            $data = array(
                'supplier_guid' => $supplier_guid,
                'invoice_no' => $invoice_no,
            );
            //print_r($data); die;

            $cuser_name = 'ADMIN';
            $cuser_pass = '1234';

            $ch = curl_init($to_shoot_url);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            //curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); // clear cache
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            
            $result = curl_exec($ch);
            $output = json_decode($result);
            //$status = json_encode($output);
            // print_r($output->result);die;
            //echo $result;die;
            //close connection
            curl_close($ch);  
            //echo $output->status;
            //die;
            
            if($output->status == "true")
            {
                $file_path = $output->file_path;
                $remark = $output->remark;

                $data = array(
                'para' => 0,
                'file_path' => $file_path,
                'remark' => $remark,
                );

                echo json_encode($data);
            }
            else
            {
                $data = array(
                'para' => 1,
                'msg' => 'No Remittance.',
                );

                echo json_encode($data);
            }
        }
        else
        {
            redirect('#');
        }
    }

    public function invoice_update_process()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {           
            $user_guid = $this->session->userdata('user_guid');
            $supplier_guid = $this->input->post("supplier_guid");
            $invoice_no = $this->input->post("invoice_number");

            $to_shoot_url = "https://api.xbridge.my/rest_b2b/index.php/Invoice_remittance/update_status_invoice_remittance";

            //echo $to_shoot_url; die;

            if(($user_guid == '') || ($user_guid == 'null') || ($user_guid == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid User GUID.',
                );
                echo json_encode($data);
                exit();
            }

            if(($supplier_guid == '') || ($supplier_guid == 'null') || ($supplier_guid == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Supplier GUID.',
                );
                echo json_encode($data);
                exit();
            }

            if(($invoice_no == '') || ($invoice_no == 'null') || ($invoice_no == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Invoice Number.',
                );
                echo json_encode($data);
                exit();
            }

            $data = array(
                'user_guid' => $user_guid,
                'supplier_guid' => $supplier_guid,
                'invoice_no' => $invoice_no,
            );
            //print_r($data); die;

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
            //$status = json_encode($output);
            // print_r($output->result);die;
            //echo $result;die;
            //close connection
            curl_close($ch);  
            //echo $output->status;
            //die;
            
            if($output->status == "true")
            {
                $msg = $output->message;

                $data = array(
                'para' => 0,
                'msg' => $msg,
                );
                echo json_encode($data);
            }
            else
            {
                $msg = $output->message;

                $data = array(
                'para' => 1,
                'msg' => $msg,
                );

                echo json_encode($data);
            }
        }
        else
        {
            redirect('#');
        }
    }

    public function invoice_delete_process()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {           
            $supplier_guid = $this->input->post("supplier_guid");
            $invoice_no = $this->input->post("invoice_number");

            $to_shoot_url = "https://api.xbridge.my/rest_b2b/index.php/Invoice_remittance/delete_invoice_remittance_document";

            //echo $to_shoot_url; die;

            if(($supplier_guid == '') || ($supplier_guid == 'null') || ($supplier_guid == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Supplier GUID.',
                );
                echo json_encode($data);
                exit();
            }

            if(($invoice_no == '') || ($invoice_no == 'null') || ($invoice_no == null))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Invalid Invoice Number.',
                );
                echo json_encode($data);
                exit();
            }

            $data = array(
                'supplier_guid' => $supplier_guid,
                'invoice_no' => $invoice_no,
            );
            //print_r($data); die;

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
            //$status = json_encode($output);
            // print_r($output->result);die;
            //echo $result;die;
            //close connection
            curl_close($ch);  
            //echo $output->status;
            //die;
            
            if($output->status == "true")
            {
                $msg = $output->message;

                $data = array(
                'para' => 0,
                'msg' => $msg,
                );
                echo json_encode($data);
            }
            else
            {
                $msg = $output->message;

                $data = array(
                'para' => 1,
                'msg' => $msg,
                );

                echo json_encode($data);
            }
        }
        else
        {
            redirect('#');
        }
    }

    public function invoices_break()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $_SESSION['user_guid'];

                $customer_guid = $_SESSION['customer_guid'];

                $supplier_guid = "SELECT supplier_guid FROM lite_b2b.set_supplier_user_relationship WHERE user_guid = '$user_guid' ";

                if ($_SESSION['user_group_name'] == "SUPER_ADMIN" ) {
                    
                    $invoice_list = $this->db->query("SELECT a.* FROM b2b_invoice.supplier_monthly_main a LEFT JOIN b2b_invoice.supplier_monthly_child b ON a.inv_guid = b.inv_guid WHERE b.customer_guid = '$customer_guid' GROUP BY a.invoice_number ");

                } else{

                    $invoice_list = $this->db->query("SELECT a.* FROM b2b_invoice.supplier_monthly_main a LEFT JOIN b2b_invoice.supplier_monthly_child b ON a.inv_guid = b.inv_guid WHERE a.biller_guid IN (".$supplier_guid.") AND b.customer_guid = '$customer_guid' AND inv_status != 'New' GROUP BY a.invoice_number ");

                }

                $data = array(

                'invoice' => $invoice_list,
                
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('b2b_billing_invoice/invoices_break', $data);
                $this->load->view('footer');  

            }
            else
            {
                redirect('#');
            }  
        
    }

    public function invoices_detail()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $user_guid = $_SESSION['user_guid'];
            $customer_guid = $_SESSION['customer_guid'];
            $supplier_guid = "SELECT supplier_guid FROM lite_b2b.set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid' GROUP BY supplier_guid";


            if(isset($_REQUEST['period']))
            {
                $period = $_REQUEST['period'];
            }
            else
            {
                $period = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL - 1 MONTH), '%Y-%m') AS period_code")->row('period_code');
            };



            if ($period != 'All') {
                if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == "CUSTOMER_ADMIN" || $_SESSION['user_group_name'] == "CUSTOMER_CLERK" || $_SESSION['user_group_name'] == "CUSTOMER_ADMIN_NO_HIDE" || $_SESSION['user_group_name'] == "CUSTOMER_ADMIN_FINANCE") 
                {
                    $query_isall = "AND period_code = '".$period."'";
                }
                else
                {
                    $query_isall = "AND a.period_code = '".$period."'";
                }

            }

            else if ($period == 'All') {

                $query_isall = '';

            }


            if(in_array('IAVA',$this->session->userdata('module_code'))) 
            {


                $detail_list = $this->db->query("SELECT reg_no,period_code, acc_doc_name , c.supplier_name, a.supplier_guid, customer_guid,
                            IF(doc_type = 'PO', 'Purchase Order'
                            , IF(doc_type = 'GR', 'Goods Received Note'
                            , IF(doc_type =  'promo_taxinv', 'Promo Tax Inv'
                            , IF(doc_type = 'CN', 'Credit Note'
                            , IF(doc_type = 'DBnote', 'Return Note'
                            , IF(doc_type = 'discheme_taxinv', 'Display Incentive'
                            , IF(doc_type = 'acc_doc', 'Accounting Document'
                            , IF(doc_type = 'PDN', 'Purchase Debit Note'
                            , IF(doc_type = 'PCN', 'Purchase Credit Note'
                            , IF( doc_type = 'CONSIGN', 'Consign Outlet'
                            , IF( doc_type = 'SI', 'Sale Invoice'
                            , IF( doc_type = 'archived_doc', 'Archived Doc'
                            , IF( doc_type = 'external_doc', 'External Doc'
                            , IF( doc_type = 'other_doc', 'Other Doc'
                            , 'Others' )
                            ))))))))))))) AS doc_type , SUM(doc_count) as doc_count 
                            FROM b2b_invoice.supplier_monthly_doc_count  AS a
                            INNER JOIN lite_b2b.acc  AS b
                            ON a.customer_guid = b.acc_guid
                            LEFT JOIN lite_b2b.set_supplier AS c
                            ON a.`supplier_guid` = c.supplier_guid
                            WHERE  hide_supplier_invoice = '0' AND isissued >= '1' AND isissued <= '6' ".$query_isall." AND a.customer_guid = '$customer_guid' GROUP BY a.customer_guid,a.supplier_guid,a.doc_type,a.period_code");      
                            // UNION ALL         
                            // SELECT reg_no,period_code, acc_doc_name , c.supplier_name, a.supplier_guid, customer_guid,
                            // 'Consign Outlet' AS doc_count,SUM(doc_count) AS doc_count 
                            // FROM b2b_invoice.supplier_monthly_doc_count_consignment  AS a
                            // INNER JOIN lite_b2b.acc  AS b
                            // ON a.customer_guid = b.acc_guid
                            // LEFT JOIN lite_b2b.set_supplier AS c
                            // ON a.`supplier_guid` = c.supplier_guid
                            // WHERE  hide_supplier_invoice = '0' AND isissued >= '1' AND isissued <= '6' ".$query_isall." AND a.customer_guid = '$customer_guid' GROUP BY a.customer_guid,a.supplier_guid,a.doc_type,a.period_code ORDER BY doc_type ASC");

                $period_list = $this->db->query("SELECT period_code FROM b2b_invoice.supplier_monthly_doc_count GROUP BY period_code DESC");

            } 
            // else if($_SESSION['user_group_name'] == "CUSTOMER_ADMIN_NO_HIDE" || $_SESSION['user_group_name'] == "CUSTOMER_ADMIN" || $_SESSION['user_group_name'] == "CUSTOMER_CLERK" || $_SESSION['user_group_name'] == "CUSTOMER_ADMIN_FINANCE") 
            // {
            //     $detail_list = $this->db->query("SELECT reg_no,period_code, acc_doc_name , c.supplier_name, a.supplier_guid, customer_guid,
            //                 IF(doc_type = 'PO', 'Purchase Order'
            //                 , IF(doc_type = 'GR', 'Goods Received Note'
            //                 , IF(doc_type =  'promo_taxinv', 'Promo Tax Inv'
            //                 , IF(doc_type = 'CN', 'Credit Note'
            //                 , IF(doc_type = 'DBnote', 'Return Note'
            //                 , IF(doc_type = 'discheme_taxinv', 'Display Incentive'
            //                 , IF(doc_type = 'acc_doc', 'Accounting Document','Others'
            //                 ))))))) AS doc_type , SUM(doc_count) as doc_count  
            //                 FROM b2b_invoice.supplier_monthly_doc_count  AS a
            //                 INNER JOIN lite_b2b.acc  AS b
            //                 ON a.customer_guid = b.acc_guid
            //                 LEFT JOIN lite_b2b.set_supplier AS c
            //                 ON a.`supplier_guid` = c.supplier_guid
            //                 WHERE customer_guid = '$customer_guid' 
            //                 AND hide_supplier_invoice = '0' AND isissued >= '1' AND isissued <= '6' ".$query_isall." AND a.customer_guid = '$customer_guid' GROUP BY a.customer_guid,a.supplier_guid,a.doc_type,a.period_code 
            //                 UNION ALL         
            //                 SELECT reg_no,period_code, acc_doc_name , c.supplier_name, a.supplier_guid, customer_guid,
            //                 'Consign outlet' AS doc_count,SUM(doc_count) AS doc_count 
            //                 FROM b2b_invoice.supplier_monthly_doc_count_consignment  AS a
            //                 INNER JOIN lite_b2b.acc  AS b
            //                 ON a.customer_guid = b.acc_guid
            //                 LEFT JOIN lite_b2b.set_supplier AS c
            //                 ON a.`supplier_guid` = c.supplier_guid
            //                 WHERE  hide_supplier_invoice = '0' AND isissued >= '1' AND isissued <= '6' ".$query_isall." AND a.customer_guid = '$customer_guid' GROUP BY a.customer_guid,a.supplier_guid,a.doc_type,a.period_code ORDER BY doc_type ASC ");

            //     $period_list = $this->db->query("SELECT period_code FROM b2b_invoice.supplier_monthly_doc_count where customer_guid = '$customer_guid' GROUP BY period_code DESC");
            // } 
            else //supp admin clerk and limited supp admin
            {
                $detail_list = $this->db->query("SELECT c.reg_no,a.period_code, acc_doc_name , c.supplier_name, a.supplier_guid, customer_guid,
                            IF(doc_type = 'PO', 'Purchase Order'
                            , IF(doc_type = 'GR', 'Goods Received Note'
                            , IF(doc_type =  'promo_taxinv', 'Promo Tax Inv'
                            , IF(doc_type = 'CN', 'Credit Note'
                            , IF(doc_type = 'DBnote', 'Return Note'
                            , IF(doc_type = 'discheme_taxinv', 'Display Incentive'
                            , IF(doc_type = 'acc_doc', 'Accounting Document'
                            , IF(doc_type = 'PDN', 'Purchase Debit Note'
                            , IF(doc_type = 'PCN', 'Purchase Credit Note'
                            , IF( doc_type = 'CONSIGN', 'Consign Outlet'
                            , IF( doc_type = 'SI', 'Sale Invoice'
                            , IF( doc_type = 'archived_doc', 'Archived Doc'
                            , IF( doc_type = 'external_doc', 'External Doc'
                            , IF( doc_type = 'other_doc', 'Other Doc'
                            , 'Others' )
                            ))))))))))))) AS doc_type , SUM(doc_count) as doc_count 
                            FROM b2b_invoice.supplier_monthly_doc_count  AS a
                            INNER JOIN lite_b2b.acc  AS b
                            ON a.customer_guid = b.acc_guid
                            LEFT JOIN lite_b2b.set_supplier AS c
                            ON a.`supplier_guid` = c.supplier_guid
                            WHERE a.supplier_guid IN (".$supplier_guid.")
                            AND hide_supplier_invoice = '0' AND isissued >= '1' AND isissued <= '6' ".$query_isall." AND a.customer_guid = '$customer_guid' GROUP BY a.customer_guid,a.supplier_guid,a.doc_type,a.period_code");
                            // UNION ALL         
                            // SELECT c.reg_no,a.period_code, acc_doc_name , c.supplier_name, a.supplier_guid, customer_guid,
                            // 'Consign Outlet' AS doc_type , SUM(doc_count) AS doc_count 
                            // FROM b2b_invoice.supplier_monthly_doc_count_consignment  AS a
                            // INNER JOIN lite_b2b.acc  AS b
                            // ON a.customer_guid = b.acc_guid
                            // LEFT JOIN lite_b2b.set_supplier AS c
                            // ON a.`supplier_guid` = c.supplier_guid
                            // WHERE a.supplier_guid IN (".$supplier_guid.")
                            // AND hide_supplier_invoice = '0' AND isissued >= '1' AND isissued <= '6' ".$query_isall." AND a.customer_guid = '$customer_guid' GROUP BY a.customer_guid,a.supplier_guid,a.doc_type,a.period_code");
                            // AND hide_supplier_invoice = '0' AND isissued >= '1' AND isissued <= '6' ".$query_isall." order by 
                //echo $this->db->last_query(); die;
                $period_list = $this->db->query("SELECT period_code FROM b2b_invoice.supplier_monthly_doc_count where supplier_guid IN (".$supplier_guid.") GROUP BY period_code DESC");

            };

            

            $data = array(
                'detail_list' => $detail_list,
                'period_list' => $period_list,
                'period' => $period
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('b2b_billing_invoice/detail', $data);
            $this->load->view('footer');

        }
        else
        {
            redirect('#');

        }
    }

    public function invoices_process()
    {   
        $user_guid = $_SESSION['user_guid'];

        $guid = $_REQUEST['g'];

        $invoice_number = $this->db->query("SELECT invoice_number FROM b2b_invoice.supplier_monthly_main WHERE inv_guid = '$guid' ")->row('invoice_number');

        $_SESSION['inv_no'] = $invoice_number;

        $_SESSION['guid'] = $guid;

        $checking = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$invoice_number' ")->num_rows();

        $reportchildinfo = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_child WHERE invoice_number = '$invoice_number' " );

        $reportheaderinfo = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$invoice_number' ")->row();

        $check_supplier_invoice = $this->db->query("SELECT supplier_guid FROM lite_b2b.set_supplier_user_relationship WHERE user_guid = '$user_guid' AND supplier_guid = '$reportheaderinfo->biller_guid' ")->num_rows('supplier_guid');

        if ($_SESSION['user_group_name'] == "SUPER_ADMIN") {
            
            $taxpercent = $reportheaderinfo->tax/$reportheaderinfo->subtotal_aft_disc*100;

        //payment instruction
        $abc = $this->db->query("SELECT query FROM b2b_invoice.set_report WHERE `describe` = 'payment' ")->row('query');
                 $execute = $this->db->query($abc);

        $data = array(

                'execute' => $execute, // payment instructions
                'table' => $reportchildinfo, //child
                'reportheaderinfo' => $reportheaderinfo, //header
                'checking' => $checking, //diff table view if exist
                'discountamount' => $reportheaderinfo->discount, //if = 0 not show discount row in view
                'taxpercent' =>$taxpercent,

                'name' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value'),
                'reg_no' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'reg_no' ")->row('value'),
                'tel' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'tel' ")->row('value'),
                'fax' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'fax' ")->row('value'),
                'add1' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add1' ")->row('value'),
                'add2' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add2' ")->row('value'),
                'add3' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add3' ")->row('value'),
                'email' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'email' ")->row('value'),
                'website' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'website' ")->row('value'),    

            );

              
             $name = $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value');

        if ($checking != 0 ) { 

            $haha = $this->load->view('b2b_billing_invoice/invoice_pdf', $data, true);
            /*$this->load->library('Pdf');*/
            $this->load->library('Pdfheaderfooter_b2binv');
            $pdf = new Pdfheaderfooter_b2binv('P', 'mm', 'A4', true, 'UTF-8', false);
            ob_start();
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor($name);
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, 100, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
            $pdf->AddPage();
            ob_start();
            $pdf->writeHTML($haha, true, false, true, false, '');
            ob_end_clean();
            $pdf->Output('B2B_'.$invoice_number.'.pdf', 'I');        

            }

            else{

                echo "<script> alert('Invoice ".$invoice_number." doest not exist in b2b_invoice, please contact support. ');</script>";
                echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";
            }

        }

        else if ($check_supplier_invoice == '0' ) {
        echo "<script> alert('You are not authorized.');</script>";
        echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";
        }

        else{

        $taxpercent = $reportheaderinfo->tax/$reportheaderinfo->subtotal_aft_disc*100;

        //payment instruction
        $abc = $this->db->query("SELECT query FROM b2b_invoice.set_report WHERE `describe` = 'payment' ")->row('query');
                 $execute = $this->db->query($abc);

        $data = array(

                'execute' => $execute, // payment instructions
                'table' => $reportchildinfo, //child
                'reportheaderinfo' => $reportheaderinfo, //header
                'checking' => $checking, //diff table view if exist
                'discountamount' => $reportheaderinfo->discount, //if = 0 not show discount row in view
                'taxpercent' =>$taxpercent,

                'name' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value'),
                'reg_no' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'reg_no' ")->row('value'),
                'tel' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'tel' ")->row('value'),
                'fax' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'fax' ")->row('value'),
                'add1' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add1' ")->row('value'),
                'add2' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add2' ")->row('value'),
                'add3' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add3' ")->row('value'),
                'email' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'email' ")->row('value'),
                'website' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'website' ")->row('value'),    

            );

              
             $name = $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value');

        if ($checking != 0 ) { 

            $haha = $this->load->view('b2b_billing_invoice/invoice_pdf', $data, true);
            /*$this->load->library('Pdf');*/
            $this->load->library('Pdfheaderfooter_b2binv');
            $pdf = new Pdfheaderfooter_b2binv('P', 'mm', 'A4', true, 'UTF-8', false);
            ob_start();
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor($name);
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, 100, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
            $pdf->AddPage();
            ob_start();
            $pdf->writeHTML($haha, true, false, true, false, '');
            ob_end_clean();
            $pdf->Output('B2B_'.$invoice_number.'.pdf', 'I');        

            }

            else{

                echo "<script> alert('Invoice ".$invoice_number." doest not exist in b2b_invoice, please contact support. ');</script>";
                echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";

            }

        }
            
    }

    public function doc_count_details()
    {
        $supplier_guid= $_REQUEST['supplier_guid'];
        $period_code= $_REQUEST['period_code'];
        $doc_type = $_REQUEST['doc_type'];
        if($_SESSION['customer_guid'] == '13EE932D98EB11EAB05B000D3AA2838A')
        {
            $customer_guid = '8D5B38E931FA11E79E7E33210BD612D3';
        }
        else
        {
            $customer_guid = $_REQUEST['customer_guid'];
        }
        // $convert
        $new_period_code = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 YEAR),'%Y-%m') AS new_period_code")->row('new_period_code');
        // echo $this->db->last_query().$period_code.$idate;die; //$period_code <= '2020-02'$new_period_code <= $period_code
        if($period_code > '2020-02' && $period_code <= '2020-08')
        {
            if ($doc_type == 'Purchase Order') {
                $data = $this->db->query("SELECT a.refno,b.loc_group as loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_po AS a LEFT JOIN b2b_summary.pomain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            } else if ($doc_type == 'Goods Received Note') {

                $data = $this->db->query("SELECT a.refno, b.loc_group AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_gr AS a LEFT JOIN b2b_summary.grmain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
                
            } else if ($doc_type == 'Promo Tax Inv'){

                $data = $this->db->query("SELECT a.refno, b.loc_group as loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_promo_taxinv AS a LEFT JOIN b2b_summary.promo_taxinv b ON a.refno = b.inv_refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Credit Note'){

                $data = $this->db->query("SELECT a.refno, b.locgroup AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_cn AS a LEFT JOIN b2b_summary.cnnotemain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Return Note'){

                $data = $this->db->query("SELECT a.refno, b.locgroup AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_dbnote AS a LEFT JOIN b2b_summary.dbnotemain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Display Incentive'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_discheme_taxinv a LEFT JOIN b2b_summary.discheme_taxinv b ON a.refno = b.inv_refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Accounting Document'){

                $data = $this->db->query("SELECT b.refno AS refno, doctype AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_acc_doc a LEFT JOIN b2b_summary.other_doc b ON a.refno = b.refno AND a.doc_type = b.doctype WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND b.customer_guid = '$customer_guid' GROUP BY a.doc_type,a.refno")->result();


            }else if ($doc_type == 'Consign Outlet'){

                $data = $this->db->query("SELECT a.refno AS refno, '-' AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_consignment_outlet a WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Purchase Debit Note'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_pdn a LEFT JOIN b2b_summary.cndn_amt b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Purchase Credit Note'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_pcn a LEFT JOIN b2b_summary.cndn_amt b ON a.refno = b.refno AND a.customer_guid = b.customer_guid  WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Sale Invoice'){

                $data = $this->db->query("SELECT a.refno AS refno, CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`si_json_info`,'$.simain[0].loc_group')) AS CHAR(10)) AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_si a LEFT JOIN b2b_summary.simain_info b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Archived Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'archived_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'External Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'external_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Other Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'other_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
        }   
        elseif($period_code > '2020-08' && $period_code <= '2021-05')
        {
            if ($doc_type == 'Purchase Order') {
                $data = $this->db->query("SELECT a.refno,b.loc_group as loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_po AS a LEFT JOIN b2b_summary.pomain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            } else if ($doc_type == 'Goods Received Note') {

                $data = $this->db->query("SELECT a.refno, b.loc_group AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_gr AS a LEFT JOIN b2b_summary.grmain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
                
            } else if ($doc_type == 'Promo Tax Inv'){

                $data = $this->db->query("SELECT a.refno, b.loc_group as loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_promo_taxinv AS a LEFT JOIN b2b_summary.promo_taxinv b ON a.refno = b.inv_refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Credit Note'){

                $data = $this->db->query("SELECT a.refno, b.locgroup AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_cn AS a LEFT JOIN b2b_summary.cnnotemain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Return Note'){

                $data = $this->db->query("SELECT a.refno, b.locgroup AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_dbnote AS a LEFT JOIN b2b_summary.dbnotemain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Display Incentive'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_discheme_taxinv a LEFT JOIN b2b_summary.discheme_taxinv b ON a.refno = b.inv_refno AND b.customer_guid = '$customer_guid' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Accounting Document'){

                $data = $this->db->query("SELECT b.refno AS refno, doctype AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_acc_doc a LEFT JOIN b2b_summary.other_doc b ON a.refno = b.refno AND a.doc_type = b.doctype WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND b.customer_guid = '$customer_guid' GROUP BY a.doc_type,a.refno")->result();


            }else if ($doc_type == 'Consign Outlet'){

                $data = $this->db->query("SELECT a.refno AS refno, '-' AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_consignment_outlet a WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Purchase Debit Note'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_pdn a LEFT JOIN b2b_summary.cndn_amt b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Purchase Credit Note'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_pcn a LEFT JOIN b2b_summary.cndn_amt b ON a.refno = b.refno AND a.customer_guid = b.customer_guid  WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Sale Invoice'){

                $data = $this->db->query("SELECT a.refno AS refno, CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`si_json_info`,'$.simain[0].loc_group')) AS CHAR(10)) AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_si a LEFT JOIN b2b_summary.simain_info b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Archived Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'archived_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'External Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'external_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Other Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'other_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }

        }                
        elseif($period_code <= '2020-02' || $_SESSION['customer_guid'] == '13EE932D98EB11EAB05B000D3AA2838A')
        {
            if ($doc_type == 'Purchase Order') {
                $data = $this->db->query("SELECT 
                refno , loc_group as loc_group,postdatetime
                FROM b2b_summary.pomain  AS a
                INNER JOIN lite_b2b.acc AS zz
                ON a.`customer_guid` = zz.acc_guid
                INNER JOIN lite_b2b.`set_supplier_group` AS b
                ON a.`customer_guid` = b.`customer_guid`
                INNER JOIN lite_b2b.`set_supplier` AS c
                ON b.supplier_guid = c.`supplier_guid`
                WHERE c.isactive = '1'
                AND a.`cancel` = '0'
                AND a.`BillStatus` = '1'
                AND a.scode = b.`supplier_group_name`
                AND a.status != 'HFSP'
                AND zz.`trial_mode` = '0'
                AND LEFT(postdatetime,7) = '$period_code'
                AND rejected = '0'
                AND b.supplier_guid = '$supplier_guid'
                AND a.customer_guid = '$customer_guid' ")->result();
                if(count($data) <= 0)
                {                
                    $data = $this->db->query("SELECT 
                    refno , loc_group as loc_group,postdatetime
                    FROM b2b_archive.pomain  AS a
                    INNER JOIN lite_b2b.acc AS zz
                    ON a.`customer_guid` = zz.acc_guid
                    INNER JOIN lite_b2b.`set_supplier_group` AS b
                    ON a.`customer_guid` = b.`customer_guid`
                    INNER JOIN lite_b2b.`set_supplier` AS c
                    ON b.supplier_guid = c.`supplier_guid`
                    WHERE c.isactive = '1'
                    AND a.`cancel` = '0'
                    AND a.`BillStatus` = '1'
                    AND a.scode = b.`supplier_group_name`
                    AND a.status != 'HFSP'
                    AND zz.`trial_mode` = '0'
                    AND LEFT(postdatetime,7) = '$period_code'
                    AND rejected = '0'
                    AND b.supplier_guid = '$supplier_guid'
                    AND a.customer_guid = '$customer_guid' ")->result();
                }
            } else if ($doc_type == 'Goods Received Note') {
                $data = $this->db->query("SELECT 
                refno ,loc_group as loc_group, postdatetime
                FROM b2b_summary.`grmain`  AS a
                INNER JOIN lite_b2b.acc AS zz
                ON a.`customer_guid` = zz.acc_guid
                INNER JOIN lite_b2b.`set_supplier_group` AS b
                ON a.`customer_guid` = b.`customer_guid`
                INNER JOIN lite_b2b.`set_supplier` AS c
                ON b.supplier_guid = c.`supplier_guid`
                WHERE c.isactive = '1'
                AND a.`Cancelled` = '0'
                AND a.`BillStatus` = '1'
                AND a.code = b.`supplier_group_name`
                AND zz.trial_mode = '0'
                AND LEFT(postdatetime,7) = '$period_code'
                AND b.supplier_guid = '$supplier_guid'
                AND a.customer_guid = '$customer_guid' ")->result();
                if(count($data) <= 0)
                {                  
                    $data = $this->db->query("SELECT 
                    refno ,loc_group as loc_group, postdatetime
                    FROM b2b_archive.`grmain`  AS a
                    INNER JOIN lite_b2b.acc AS zz
                    ON a.`customer_guid` = zz.acc_guid
                    INNER JOIN lite_b2b.`set_supplier_group` AS b
                    ON a.`customer_guid` = b.`customer_guid`
                    INNER JOIN lite_b2b.`set_supplier` AS c
                    ON b.supplier_guid = c.`supplier_guid`
                    WHERE c.isactive = '1'
                    AND a.`Cancelled` = '0'
                    AND a.`BillStatus` = '1'
                    AND a.code = b.`supplier_group_name`
                    AND zz.trial_mode = '0'
                    AND LEFT(postdatetime,7) = '$period_code'
                    AND b.supplier_guid = '$supplier_guid'
                    AND a.customer_guid = '$customer_guid' ")->result();
                }
                
            } else if ($doc_type == 'Promo Tax Inv'){

                $data = $this->db->query("SELECT 
                refno , loc_group as loc_group,posted_at as postdatetime
                FROM b2b_summary.`promo_taxinv`  AS a
                INNER JOIN lite_b2b.acc AS zz
                ON a.`customer_guid` = zz.acc_guid
                INNER JOIN lite_b2b.`set_supplier_group` AS b
                ON a.`customer_guid` = b.`customer_guid`
                AND a.sup_code = b.`supplier_group_name`
                INNER JOIN lite_b2b.`set_supplier` AS c
                ON b.supplier_guid = c.`supplier_guid`
                WHERE c.isactive = '1'
                AND zz.`trial_mode` = '0'
                AND LEFT(posted_at,7) = '$period_code'
                #AND LEFT(docdate,7) = '$period_code' 
                AND b.supplier_guid = '$supplier_guid'
                AND a.customer_guid = '$customer_guid'  ")->result();
                if(count($data) <= 0)
                {   
                    $data = $this->db->query("SELECT 
                    refno , loc_group as loc_group,posted_at as postdatetime
                    FROM b2b_archive.`promo_taxinv`  AS a
                    INNER JOIN lite_b2b.acc AS zz
                    ON a.`customer_guid` = zz.acc_guid
                    INNER JOIN lite_b2b.`set_supplier_group` AS b
                    ON a.`customer_guid` = b.`customer_guid`
                    AND a.sup_code = b.`supplier_group_name`
                    INNER JOIN lite_b2b.`set_supplier` AS c
                    ON b.supplier_guid = c.`supplier_guid`
                    WHERE c.isactive = '1'
                    AND zz.`trial_mode` = '0'
                    AND LEFT(posted_at,7) = '$period_code'
                    #AND LEFT(docdate,7) = '$period_code' 
                    AND b.supplier_guid = '$supplier_guid'
                    AND a.customer_guid = '$customer_guid'  ")->result();
                }


            } else if ($doc_type == 'Credit Note'){

                $data = $this->db->query("SELECT 
                refno ,locgroup as loc_group, postdatetime
                FROM b2b_summary.`cnnotemain`  AS a
                INNER JOIN lite_b2b.acc AS zz
                ON a.`customer_guid` = zz.acc_guid
                INNER JOIN lite_b2b.`set_supplier_group` AS b
                ON a.`customer_guid` = b.`customer_guid`
                INNER JOIN lite_b2b.`set_supplier` AS c
                ON b.supplier_guid = c.`supplier_guid`
                WHERE c.isactive = '1'
                AND a.`Closed` = '0'
                AND a.`BillStatus` = '1'
                AND a.code = b.`supplier_group_name`
                AND zz.`trial_mode` = '0'
                AND LEFT(postdatetime,7) = '$period_code'
                AND b.supplier_guid = '$supplier_guid'
                AND a.customer_guid = '$customer_guid'  ")->result();

                if(count($data) <= 0)
                {
                    $data = $this->db->query("SELECT 
                    refno ,locgroup as loc_group, postdatetime
                    FROM b2b_archive.`cnnotemain`  AS a
                    INNER JOIN lite_b2b.acc AS zz
                    ON a.`customer_guid` = zz.acc_guid
                    INNER JOIN lite_b2b.`set_supplier_group` AS b
                    ON a.`customer_guid` = b.`customer_guid`
                    INNER JOIN lite_b2b.`set_supplier` AS c
                    ON b.supplier_guid = c.`supplier_guid`
                    WHERE c.isactive = '1'
                    AND a.`Closed` = '0'
                    AND a.`BillStatus` = '1'
                    AND a.code = b.`supplier_group_name`
                    AND zz.`trial_mode` = '0'
                    AND LEFT(postdatetime,7) = '$period_code'
                    AND b.supplier_guid = '$supplier_guid'
                    AND a.customer_guid = '$customer_guid'  ")->result();
                }


            } else if ($doc_type == 'Return Note'){

                $data = $this->db->query("SELECT 
                refno ,locgroup as loc_group, postdatetime
                FROM b2b_summary.`dbnotemain`  AS a
                INNER JOIN lite_b2b.acc AS zz
                ON a.`customer_guid` = zz.acc_guid
                INNER JOIN lite_b2b.`set_supplier_group` AS b
                ON a.`customer_guid` = b.`customer_guid`
                INNER JOIN lite_b2b.`set_supplier` AS c
                ON b.supplier_guid = c.`supplier_guid`
                WHERE c.isactive = '1'
                AND a.`Closed` = '0'
                AND a.`BillStatus` = '1'
                AND a.code = b.`supplier_group_name`
                AND zz.`trial_mode` = '0'
                AND LEFT(postdatetime,7) = '$period_code'
                AND b.supplier_guid = '$supplier_guid'
                AND a.customer_guid = '$customer_guid'   ")->result();
                if(count($data) <= 0)
                {
                    $data = $this->db->query("SELECT 
                    refno ,locgroup as loc_group, postdatetime
                    FROM b2b_archive.`dbnotemain`  AS a
                    INNER JOIN lite_b2b.acc AS zz
                    ON a.`customer_guid` = zz.acc_guid
                    INNER JOIN lite_b2b.`set_supplier_group` AS b
                    ON a.`customer_guid` = b.`customer_guid`
                    INNER JOIN lite_b2b.`set_supplier` AS c
                    ON b.supplier_guid = c.`supplier_guid`
                    WHERE c.isactive = '1'
                    AND a.`Closed` = '0'
                    AND a.`BillStatus` = '1'
                    AND a.code = b.`supplier_group_name`
                    AND zz.`trial_mode` = '0'
                    AND LEFT(postdatetime,7) = '$period_code'
                    AND b.supplier_guid = '$supplier_guid'
                    AND a.customer_guid = '$customer_guid'   ")->result();
                }


            } else if ($doc_type == 'Display Incentive'){

                $data = $this->db->query("SELECT 
                inv_refno as refno , loc_group as loc_group,posted_at as postdatetime
                FROM b2b_summary.`discheme_taxinv`  AS a
                INNER JOIN lite_b2b.acc AS zz
                ON a.`customer_guid` = zz.acc_guid
                INNER JOIN lite_b2b.`set_supplier_group` AS b
                ON a.`customer_guid` = b.`customer_guid`
                INNER JOIN lite_b2b.`set_supplier` AS c
                ON b.supplier_guid = c.`supplier_guid`
                WHERE c.isactive = '1'
                AND a.`posted` = '1'
                AND a.sup_code = b.`supplier_group_name`
                AND zz.`trial_mode` = '0'
                AND LEFT(posted_at,7) = '$period_code'
                AND b.supplier_guid = '$supplier_guid'
                AND a.customer_guid = '$customer_guid' ")->result();
                if(count($data) <= 0)
                {
                    $data = $this->db->query("SELECT 
                    inv_refno as refno , loc_group as loc_group,posted_at as postdatetime
                    FROM b2b_archive.`discheme_taxinv`  AS a
                    INNER JOIN lite_b2b.acc AS zz
                    ON a.`customer_guid` = zz.acc_guid
                    INNER JOIN lite_b2b.`set_supplier_group` AS b
                    ON a.`customer_guid` = b.`customer_guid`
                    INNER JOIN lite_b2b.`set_supplier` AS c
                    ON b.supplier_guid = c.`supplier_guid`
                    WHERE c.isactive = '1'
                    AND a.`posted` = '1'
                    AND a.sup_code = b.`supplier_group_name`
                    AND zz.`trial_mode` = '0'
                    AND LEFT(posted_at,7) = '$period_code'
                    AND b.supplier_guid = '$supplier_guid'
                    AND a.customer_guid = '$customer_guid' ")->result();
                }


            }else if ($doc_type == 'Accounting Document'){

                $data = $this->db->query("SELECT 
                refno AS refno ,'-' as loc_group, a.created_at AS postdatetime
                FROM b2b_summary.`other_doc`  AS a
                INNER JOIN lite_b2b.acc AS zz
                ON a.`customer_guid` = zz.acc_guid
                INNER JOIN lite_b2b.`set_supplier_group` AS b
                ON a.`customer_guid` = b.`customer_guid`
                INNER JOIN lite_b2b.`set_supplier` AS c
                ON b.supplier_guid = c.`supplier_guid`
                WHERE c.isactive = '1'
                AND a.supcode = b.`supplier_group_name`
                AND zz.`trial_mode` = '0'
                AND LEFT(a.created_at,7) = '$period_code'
                AND b.supplier_guid = '$supplier_guid'
                AND a.customer_guid = '$customer_guid'")->result();                
                if(count($data) <= 0)
                {                
                    $data = $this->db->query("SELECT 
                    refno AS refno ,'-' as loc_group, a.created_at AS postdatetime
                    FROM b2b_archive.`other_doc`  AS a
                    INNER JOIN lite_b2b.acc AS zz
                    ON a.`customer_guid` = zz.acc_guid
                    INNER JOIN lite_b2b.`set_supplier_group` AS b
                    ON a.`customer_guid` = b.`customer_guid`
                    INNER JOIN lite_b2b.`set_supplier` AS c
                    ON b.supplier_guid = c.`supplier_guid`
                    WHERE c.isactive = '1'
                    AND a.supcode = b.`supplier_group_name`
                    AND zz.`trial_mode` = '0'
                    AND LEFT(a.created_at,7) = '$period_code'
                    AND b.supplier_guid = '$supplier_guid'
                    AND a.customer_guid = '$customer_guid'")->result();
                }
            }
            else if ($doc_type == 'Sale Invoice'){

                $data = $this->db->query("SELECT a.refno AS refno, CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`si_json_info`,'$.simain[0].loc_group')) AS CHAR(10)) AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_si a LEFT JOIN b2b_summary.simain_info b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Archived Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'archived_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'External Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'external_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Other Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'other_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
        }
        else
        {
            if ($doc_type == 'Purchase Order') {
                $data = $this->db->query("SELECT a.refno,b.loc_group as loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_po AS a LEFT JOIN b2b_summary.pomain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'PO' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            } else if ($doc_type == 'Goods Received Note') {

                $data = $this->db->query("SELECT a.refno, b.loc_group AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_gr AS a LEFT JOIN b2b_summary.grmain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'GR' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
                
            } else if ($doc_type == 'Promo Tax Inv'){

                $data = $this->db->query("SELECT a.refno, b.loc_group as loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_promo_taxinv AS a LEFT JOIN b2b_summary.promo_taxinv b ON a.refno = b.inv_refno AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'promo_taxinv' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Credit Note'){

                $data = $this->db->query("SELECT a.refno, b.locgroup AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_cn AS a LEFT JOIN b2b_summary.cnnotemain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'CN' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Return Note'){

                $data = $this->db->query("SELECT a.refno, b.locgroup AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_dbnote AS a LEFT JOIN b2b_summary.dbnotemain b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'DBnote' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            } else if ($doc_type == 'Display Incentive'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_discheme_taxinv a LEFT JOIN b2b_summary.discheme_taxinv b ON a.refno = b.inv_refno AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'discheme_taxinv' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Accounting Document'){
                $b2b_database = 'b2b_summary';
                if(in_array('IAVA',$_SESSION['module_code']))
                {
                    $get_code = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");
                    $sup = '';
                    foreach($get_code->result() as $row)
                    {
                        $sup .= "'".$row->supplier_group_name."'," ;
                    }
                    $supcode = $sup;
                    $xsupcode = rtrim($supcode,",");
                    $debit_supcode = $this->db->query("SELECT accpdebit FROM $b2b_database.supcus WHERE customer_guid = '$customer_guid' AND code IN($xsupcode) AND (accpdebit <> '' AND accpdebit IS NOT NULL)");
                    if($debit_supcode->num_rows() > 0)
                    {
                        $dcode = '';
                        foreach($debit_supcode->result()  as $debitcode)
                        {
                            $dcode .= "'".$debitcode->accpdebit."',";
                        }
                        $dcode = rtrim($dcode,",");
                        // echo $dcode;die;
                    }
                    else
                    {
                        $dcode = '';
                    }
                    // if($dcode != '' || $dcode != null)
                    // {
                    //     $supcode = $xsupcode.','.$dcode;
                    // }                    
                    // echo $supcode;die;
                    // echo $this->db->last_query();die;
                }
                else
                {
                    $xsupcode = $this->session->userdata('query_supcode');
                    $debit_supcode = $this->db->query("SELECT accpdebit FROM $b2b_database.supcus WHERE customer_guid = '$customer_guid' AND code IN($xsupcode) AND (accpdebit <> '' AND accpdebit IS NOT NULL)");
                    // echo $this->db->last_query();die;
                    if($debit_supcode->num_rows() > 0)
                    {
                        $dcode = '';
                        foreach($debit_supcode->result()  as $debitcode)
                        {
                            $dcode .= "'".$debitcode->accpdebit."',";
                        }
                        $dcode = rtrim($dcode,",");
                        // if($dcode != '' || $dcode != null)
                        // {
                        //     $supcode = $supcode.','.$dcode;
                        // }
                    }
                    else
                    {
                        $dcode = '';
                    }
                }

                if($dcode != '' && $dcode != null)
                {
                    // echo $supcode;die;
                    $data = $this->db->query("SELECT a.refno AS refno, doctype AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_acc_doc a LEFT JOIN b2b_summary.other_doc b ON a.refno = b.refno AND a.doc_type = b.doctype AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'acc_doc' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND b.customer_guid = '$customer_guid' GROUP BY a.doc_type,a.refno UNION ALL SELECT b.refno AS refno, doctype AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_acc_doc a LEFT JOIN b2b_summary.other_doc b ON a.refno = b.refno AND a.doc_type = b.doctype INNER JOIN b2b_summary.supcus w ON a.sup_code = w.accpdebit AND a.customer_guid = b.customer_guid AND w.type <> 'C' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND w.code = z.sup_code AND z.doc_type = 'acc_doc' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'  GROUP BY a.doc_type,a.refno")->result();
                    // echo $this->db->last_query();die;
                    // echo 1;die;
                    //AND b.customer_guid = '$customer_guid'
                }
                else
                {
                        $data = $this->db->query("SELECT a.refno AS refno, doctype AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_acc_doc a LEFT JOIN b2b_summary.other_doc b ON a.refno = b.refno AND a.doc_type = b.doctype AND b.customer_guid = '$customer_guid' INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'acc_doc' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' GROUP BY a.doc_type,a.refno")->result();
                }


            }else if ($doc_type == 'Consign Outlet'){

                $data = $this->db->query("SELECT a.refno AS refno, '-' AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_consignment_outlet a INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'CONSIGN' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Purchase Debit Note'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_pdn a LEFT JOIN b2b_summary.cndn_amt b ON a.refno = b.refno AND a.customer_guid = b.customer_guid INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'PDN' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6' WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();


            }else if ($doc_type == 'Purchase Credit Note'){

                $data = $this->db->query("SELECT a.refno AS refno, b.loc_group AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_pcn a LEFT JOIN b2b_summary.cndn_amt b ON a.refno = b.refno AND a.customer_guid = b.customer_guid INNER JOIN b2b_invoice.supplier_monthly_doc_count z ON a.supplier_guid = z.supplier_guid AND a.customer_guid = z.customer_guid AND a.sup_code = z.sup_code AND z.doc_type = 'PCN' AND a.period_code = z.period_code AND isissued >= '1' AND isissued <= '6'  WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();

            }
            else if ($doc_type == 'Sale Invoice'){

                $data = $this->db->query("SELECT a.refno AS refno, CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`si_json_info`,'$.simain[0].loc_group')) AS CHAR(10)) AS loc_group, a.postdatetime AS postdatetime FROM b2b_invoice.supplier_monthly_doc_count_si a LEFT JOIN b2b_summary.simain_info b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Archived Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'archived_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'External Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'external_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
            else if ($doc_type == 'Other Doc'){

                $data = $this->db->query("SELECT a.refno, b.doc_type AS loc_group, a.postdatetime FROM b2b_invoice.supplier_monthly_doc_count_external AS a LEFT JOIN b2b_summary.extra_doc b ON a.refno = b.refno AND b.customer_guid = '$customer_guid' WHERE b.`charge_type` = 'other_doc' AND a.period_code = '$period_code' AND a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'")->result();
            }
        }
        // echo $_SESSION['customer_guid'];die;

        echo json_encode($data);


    }

    public function invoices_process_break()
    {   
        $auth = $this->file_config_b2b->auth($customer_guid,'web','consignment','consignment_jasper_auth','CONJASPERAUTH');
        $inv_guid = $_REQUEST['g'];
        $customer_guid = $this->session->userdata('customer_guid');
        $url = $this->jasper_ip.'/jasperserver/rest_v2/reports/reports/B2BReports/Invoices_1_1.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid.'&cus_guid='.$customer_guid;
        // echo $url;die;
        $curl = curl_init();

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
            "Authorization: Basic $auth",
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
        header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$inv_no.'.pdf');
        echo $response; 

        curl_close($curl); 
        die;

        $user_guid = $_SESSION['user_guid'];

        $customer_guid = $_SESSION['customer_guid'];

        $guid = $_REQUEST['g'];

        $invoice_number = $this->db->query("SELECT invoice_number FROM b2b_invoice.supplier_monthly_main WHERE inv_guid = '$guid' ")->row('invoice_number');

        $_SESSION['inv_no'] = $invoice_number;

        $_SESSION['guid'] = $guid;

        $checking = $this->db->query("SELECT a.* FROM b2b_invoice.supplier_monthly_main a LEFT JOIN b2b_invoice.supplier_monthly_child b ON a.inv_guid = b.inv_guid WHERE b.customer_guid = '$customer_guid' AND a.invoice_number = '$invoice_number' GROUP BY a.invoice_number ")->num_rows();


        $reportchildinfo = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_child WHERE invoice_number = '$invoice_number' AND customer_guid = '$customer_guid' " );

        $reportheaderinfo = $this->db->query("SELECT a.* FROM b2b_invoice.supplier_monthly_main a LEFT JOIN b2b_invoice.supplier_monthly_child b ON a.inv_guid = b.inv_guid WHERE b.customer_guid = '$customer_guid' AND a.invoice_number = '$invoice_number' GROUP BY a.invoice_number ")->row();

        $check_supplier_invoice = $this->db->query("SELECT supplier_guid FROM lite_b2b.set_supplier_user_relationship WHERE user_guid = '$user_guid' AND supplier_guid = '$reportheaderinfo->biller_guid' ")->num_rows('supplier_guid');

        if ($_SESSION['user_group_name'] == "SUPER_ADMIN") {
            
            $taxpercent = $reportheaderinfo->tax/$reportheaderinfo->subtotal_aft_disc*100;

        //payment instruction
        $abc = $this->db->query("SELECT query FROM b2b_invoice.set_report WHERE `describe` = 'payment' ")->row('query');
                 $execute = $this->db->query($abc);

        $data = array(

                'execute' => $execute, // payment instructions
                'table' => $reportchildinfo, //child
                'reportheaderinfo' => $reportheaderinfo, //header
                'checking' => $checking, //diff table view if exist
                'discountamount' => $reportheaderinfo->discount, //if = 0 not show discount row in view
                'taxpercent' =>$taxpercent,

                'name' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value'),
                'reg_no' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'reg_no' ")->row('value'),
                'tel' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'tel' ")->row('value'),
                'fax' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'fax' ")->row('value'),
                'add1' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add1' ")->row('value'),
                'add2' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add2' ")->row('value'),
                'add3' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add3' ")->row('value'),
                'email' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'email' ")->row('value'),
                'website' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'website' ")->row('value'),    

            );

              
             $name = $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value');

            if ($checking != 0 ) { 

                $haha = $this->load->view('b2b_billing_invoice/invoice_pdf', $data, true);
                /*$this->load->library('Pdf');*/
                $this->load->library('Pdfheaderfooter_b2binv');
                $pdf = new Pdfheaderfooter_b2binv('P', 'mm', 'A4', true, 'UTF-8', false);
                ob_start();
                $pdf->SetTitle($invoice_number);
                $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
                $pdf->SetAuthor($name);
                $pdf->SetDisplayMode('real', 'default');
                $pdf->SetMargins(PDF_MARGIN_LEFT, 100, PDF_MARGIN_RIGHT);
                $pdf->setPageUnit('pt');
                $x = $pdf->pixelsToUnits('20');
                $y = $pdf->pixelsToUnits('20');
                $font_size = $pdf->pixelsToUnits('9.5');
                $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
                $pdf->AddPage();
                ob_start();
                $pdf->writeHTML($haha, true, false, true, false, '');
                ob_end_clean();
                $pdf->Output('B2B_'.$invoice_number.'.pdf', 'I');        

            }
            else
            {
                echo "<script> alert('Invoice ".$invoice_number." doest not exist in b2b_invoice, please contact support. ');</script>";
                echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";
            }

        }

        else if ($check_supplier_invoice == '0' ) {
        echo "<script> alert('You are not authorized.');</script>";
        echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";
        }

        else{

        $taxpercent = $reportheaderinfo->tax/$reportheaderinfo->subtotal_aft_disc*100;

        //payment instruction
        $abc = $this->db->query("SELECT query FROM b2b_invoice.set_report WHERE `describe` = 'payment' ")->row('query');
                 $execute = $this->db->query($abc);

        $data = array(

                'execute' => $execute, // payment instructions
                'table' => $reportchildinfo, //child
                'reportheaderinfo' => $reportheaderinfo, //header
                'checking' => $checking, //diff table view if exist
                'discountamount' => $reportheaderinfo->discount, //if = 0 not show discount row in view
                'taxpercent' =>$taxpercent,

                'name' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value'),
                'reg_no' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'reg_no' ")->row('value'),
                'tel' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'tel' ")->row('value'),
                'fax' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'fax' ")->row('value'),
                'add1' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add1' ")->row('value'),
                'add2' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add2' ")->row('value'),
                'add3' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'add3' ")->row('value'),
                'email' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'email' ")->row('value'),
                'website' => $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'website' ")->row('value'),    

            );

              
             $name = $this->db->query("SELECT value FROM b2b_invoice.company_profile WHERE type = 'name' ")->row('value');

        if ($checking != 0 ) { 

            $haha = $this->load->view('b2b_billing_invoice/invoice_pdf', $data, true);
            /*$this->load->library('Pdf');*/
            $this->load->library('Pdfheaderfooter_b2binv');
            $pdf = new Pdfheaderfooter_b2binv('P', 'mm', 'A4', true, 'UTF-8', false);
            ob_start();
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor($name);
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, 100, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
            $pdf->AddPage();
            ob_start();
            $pdf->writeHTML($haha, true, false, true, false, '');
            ob_end_clean();
            $pdf->Output('B2B_'.$invoice_number.'.pdf', 'I');        

            }

            else{

                echo "<script> alert('Invoice ".$invoice_number." doest not exist in b2b_invoice, please contact support. ');</script>";
                echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";

            }

        }
            
    }


    public function official_receipt()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $this->session->userdata('user_guid');
                $customer_guid = $this->session->userdata('customer_guid');
                if(in_array('IAVA',$_SESSION['module_code']))
                {
                    $supplier_guid = $this->db->query("SELECT a.* FROM set_supplier a INNER JOIN set_supplier_group b ON a.supplier_guid = b.supplier_guid AND b.customer_guid = '$customer_guid' GROUP BY a.supplier_guid ORDER BY a.supplier_name ASC");

                    $supplier_drop_down = '<select class="form-control select2" id="receipt_supplier" name="supplier_selected">';
                    
                    if($supplier_guid->num_rows() > 0)
                    {
                        $supplier_drop_down .= '<option>Please Select One Supplier</option>';
                        foreach($supplier_guid->result() as $row)
                        {
                            $supplier_drop_down .= '<option value="'.$row->supplier_guid.'"">'.$row->supplier_name.'</option>';
                        }
                    } 
                    else
                    {
                        $supplier_drop_down .= '<option>No Supplier Assigned</option>';
                    }
                    $supplier_drop_down .= '</select>';
                }
                else
                {
                    $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$customer_guid' INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' AND a.`customer_guid` = '$customer_guid' GROUP BY b.`supplier_guid` ORDER BY c.supplier_name ASC");

                    $supplier_drop_down = '<select class="select2" id="receipt_supplier" name="supplier_selected">';
                    
                    if($supplier_guid->num_rows() > 0)
                    {
                        $supplier_drop_down .= '<option>Please Select One Supplier</option>';
                        foreach($supplier_guid->result() as $row)
                        {
                            $supplier_drop_down .= '<option value="'.$row->supplier_guid.'"">'.$row->supplier_name.'</option>';
                        }
                    } 
                    else
                    {
                        $supplier_drop_down .= '<option>No Supplier Assigned</option>';
                    }
                    $supplier_drop_down .= '</select>';                    
                }           
                // echo $supplier_drop_down;die;

                if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') {
                    $get_customer = $this->db->query("SELECT acc_guid, acc_name,seq 
                    FROM `acc` WHERE isactive = 1 order by seq asc,row_seq ASC");
                } else {

                    $get_customer = $this->db->query("SELECT DISTINCT d.acc_guid,e.acc_name,e.seq FROM `set_user` AS a 
                    INNER JOIN `set_user_branch` b ON a.user_guid = b.user_guid
                    INNER JOIN `acc_branch` c ON b.branch_guid = c.branch_guid 
                    INNER JOIN `acc_concept` d ON c.concept_guid = d.concept_guid 
                    INNER JOIN `acc` e ON d.`acc_guid` = e.`acc_guid` 
                    where a.user_id = '".$_SESSION['userid']."' 
                    and e.isactive = '1' 
                    and a.module_group_guid = '".$_SESSION['module_group_guid']."' 
                    order by e.seq asc,e.row_seq ASC");
                    // echo $this->db->last_query();die;
                }
                $data = array(

                'receipt_list' => array(),
                'supplier_drop_down' => $supplier_drop_down,
                'customer' => $get_customer,
                
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('b2b_billing_invoice/official_receipt', $data);
                $this->load->view('footer');  

            }
            else
            {
                redirect('#');
            }         
    } 

    public function official_receipt_table()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $this->session->userdata('user_guid');
                $customer_guid = $this->session->userdata('customer_guid');
                $supplier_guid = $this->input->post('supplier_guid');
                $columns = array(
                    0 => 'supp_name',
                    1 => 'receipt_no',
                    2 => 'receipt_date',
                    3 => 'receipt_total',
                    4 => 'receipt_unapply',                    
                    5 => 't_inv_no',
                    6 => 'inv_apply_amount_total',
                    7 => 'inv_amount_total',
                    8 => 'inv_count',
                    // 9 => 'action',
                    9 => 'knock_off_amount',                    

                );

                $limit = $this->input->post('length');
                $draw = intval($this->input->post("draw"));
                $start = intval($this->input->post("start"));
                $length = intval($this->input->post("length"));
                $order = $this->input->post("order");
                $search= $this->input->post("search");
                $search = $search['value'];
                $col = 0;
                $dir = "";
                // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

                $order_query = "";

                if(!empty($order))
                {
                  foreach($order as $o)
                  {
                      $col = $o['column'];
                      $dir= $o['dir'];

                      $order_query .= $columns[$col]." ".$dir.",";

                  }
                }      
                $order_query = rtrim($order_query,',');
                // echo $order_query;die;
                if($supplier_guid != '')
                {
                    if(in_array('IAVA',$_SESSION['module_code']))
                    {                
                        // $query = "SELECT DebtorCode,PayDocDate,DocDate,companyname AS supp_name, PayDocNo AS receipt_no, PayDocDate AS receipt_date, GROUP_CONCAT(DISTINCT DocNo, ' <br><span style=\"color:red;\"> <b>(', ROUND(NetTotal,2), ')</b></span>', ' <span style=\"color:green;\"><b>(', ROUND(InvApplyAmt,2), ')</b></span>') AS t_inv_no, ROUND(SUM(InvApplyAmt),2) AS inv_apply_amount_total, ROUND(SUM(NetTotal),2) AS inv_amount_total,COUNT(*) as inv_count,ROUND(LocalPaymentAmt,2) as receipt_total,ROUND(LocalUnappliedAmount,2) as receipt_unapply,ROUND(KnockOffAmt,2) as knock_off_amount FROM b2b_invoice.`autocount_payment_hub` GROUP BY PayDocNo";

                        $supplier_guid = $this->db->query("SELECT * FROM set_supplier a WHERE a.supplier_guid = '$supplier_guid'");
                        // echo $this->db->last_query();die;
                        $con_deb_code = '';
                        foreach($supplier_guid->result() as $row)
                        {
                            $con_deb_code .= "'".$row->acc_code."',"; 
                        }
                        $con_deb_code = rtrim($con_deb_code, ",");
                        // echo $con_deb_code;
                        $query = "SELECT a.DebtorCode, a.DocDate AS PayDocDate, d.DocDate, b.supplier_name AS supp_name, a.DocNo AS receipt_no, a.DocDate AS receipt_date, GROUP_CONCAT(DISTINCT d.DocNo) AS t_inv_no, ROUND(a.KnockOffAmt, 2) AS inv_apply_amount_total, ROUND(SUM(d.NetTotal), 2) AS inv_amount_total, COUNT(c.I_DocNo) AS inv_count, ROUND(a.LocalPaymentAmt, 2) AS receipt_total, ROUND(a.LocalUnappliedAmount, 2) AS receipt_unapply, ROUND(a.KnockOffAmt, 2) AS knock_off_amount FROM b2b_account.arpayment a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.arpaymentknockoff c ON a.DocNo = c.R_DocNo LEFT JOIN b2b_account.arinvoice d ON c.I_DocNo = d.DocNo WHERE a.DebtorCode IN ($con_deb_code) GROUP BY a.DocNo";                    
                    }
                    else
                    {
                        // echo $user_guid;
                        $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$customer_guid' INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' AND a.`customer_guid` = '$customer_guid' AND c.supplier_guid = '$supplier_guid' GROUP BY b.`supplier_guid`");
                        $con_deb_code = '';
                        foreach($supplier_guid->result() as $row)
                        {
                            $con_deb_code .= "'".$row->acc_code."',"; 
                        }
                        $con_deb_code = rtrim($con_deb_code, ",");
                        // echo $con_deb_code;
                        $query = "SELECT a.DebtorCode, a.DocDate AS PayDocDate, d.DocDate, b.supplier_name AS supp_name, a.DocNo AS receipt_no, a.DocDate AS receipt_date, GROUP_CONCAT(DISTINCT d.DocNo) AS t_inv_no, ROUND(a.KnockOffAmt, 2) AS inv_apply_amount_total, ROUND(SUM(d.NetTotal), 2) AS inv_amount_total, COUNT(c.I_DocNo) AS inv_count, ROUND(a.LocalPaymentAmt, 2) AS receipt_total, ROUND(a.LocalUnappliedAmount, 2) AS receipt_unapply, ROUND(a.KnockOffAmt, 2) AS knock_off_amount FROM b2b_account.arpayment a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.arpaymentknockoff c ON a.DocNo = c.R_DocNo LEFT JOIN b2b_account.arinvoice d ON c.I_DocNo = d.DocNo WHERE a.DebtorCode IN ($con_deb_code) GROUP BY a.DocNo";
                    }
                    $query2 = " ORDER BY " .$order_query. " LIMIT " .$start. " , " .$limit. ";";
                    

                    // echo $query.$query2;die;
                    // $receipt_list = $this->db->query("$execute_query");
                    // echo $this->db->last_query();die;
                    // print_r($receipt_list->result());die;

                    if(empty($this->input->post('search')['value']))
                    {
                        $execute_query = $query.$query2;
                        $posts = $this->db->query("$execute_query");
                        $totalDataquery = $this->db->query("$query");;
                        $totalData = $totalDataquery->num_rows();
                        $totalFiltered = $totalData;
                        // echo $this->db->last_query();die;
                    }
                    else 
                    {
                        $search = addslashes($this->input->post('search')['value']); 
                        $search_query = " WHERE (supp_name LIKE '%$search%' OR receipt_no LIKE '%$search%' OR receipt_date LIKE '%$search%' OR inv_apply_amount_total LIKE '%$search%' OR t_inv_no LIKE '%$search%' OR inv_amount_total LIKE '%$search%')";
                        $execute_query = "SELECT * FROM "."(".$query.") a ".$search_query.$query2;
                        $execute_query_count = "SELECT count(*) as count FROM "."(".$query.") a ".$search_query;
                        // echo $execute_query;die; 

                        $posts =  $this->db->query("$execute_query");
                        $totalData = $this->db->query("$execute_query")->num_rows('count');
                        // echo $this->db->last_query();die;

                        $totalFiltered = $totalData;
                        // echo $totalFiltered;die;
                    }
                    // print_r($posts->result());die;
                    $doc_type = 'SVV';
                    $inv_doc_type = 'SIN';
                    $data = array();
                    if(!empty($posts))
                    {                   
                        foreach ($posts->result() as $post)
                        {
                            // $virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00" target="_blank">'.$post->receipt_no.'</a>'; 

                            $virtual_path = '<a href='.site_url('B2b_billing_invoice_controller/official_receipt_child').'?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00">'.$post->receipt_no.'</a>'; 

                            $inv_no_string = '';

                            $inv_no = explode(',',$post->t_inv_no);
                            // print_r($inv_no);
                            foreach($inv_no as $row)
                            {
                                $row_no = $row;
                                // echo $row_no;die;
                                $check_is_b2b_invoice = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$row_no'");
                                if($check_is_b2b_invoice->num_rows() > 0)
                                {
                                    $inv_no_string .= '<a href="'.site_url().'/Invoice/invoices_process?inv_guid='.$check_is_b2b_invoice->row('inv_guid').'" target="_blank">'.$row.'</a> ,<br>';
                                }
                                else
                                {
                                    $check_is_b2b_reg_invoice = $this->db->query("SELECT * FROM b2b_invoice.inv_doc WHERE invoice_number = '$row_no'");
                                    if($check_is_b2b_reg_invoice->num_rows() > 0)
                                    {
                                        $inv_no_string = '<a href="'.site_url().'/Invoice/view_report_reg?inv_guid='.$check_is_b2b_reg_invoice->row('inv_guid').'" target="_blank">'.$row_no;
                                    }
                                    else
                                    {
                                        $inv_docdate = $this->db->query("SELECT docdate FROM b2b_account.arinvoice WHERE docno = '$row'")->row('docdate');
                                        // echo $row.'<br>';
                                        // echo strtok($row, '(');die;
                                        $inv_virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$row.'&doctype='.$inv_doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$inv_docdate.' 00:00:00" target="_blank" > '.$row.'</a>'; 
                                        // echo $post->DocDate;die;

                                        $inv_no_string .= $inv_virtual_path.' ,<br>';
                                    }
                                }
                            }    
                            $inv_no_string = rtrim($inv_no_string, ",<br>");
                            // echo $inv_no_string;die;                     

                            $nestedData['supp_name'] = $post->supp_name;
                            $nestedData['receipt_no'] = $virtual_path;
                            $nestedData['receipt_date'] = date("Y-m-d",strtotime($post->receipt_date));
                            $nestedData['inv_apply_amount_total'] = $post->inv_apply_amount_total;
                            $nestedData['receipt_total'] = $post->receipt_total;
                            $nestedData['receipt_unapply'] = $post->receipt_unapply;
                            $nestedData['t_inv_no'] = $inv_no_string;
                            $nestedData['inv_amount_total'] = $post->inv_amount_total;
                            $nestedData['inv_count'] = $post->inv_count;

                            // $nestedData['action'] = '<button ticket_guid='.$post->supp_name.' category_guid='.$post->supp_name.' sub_category_guid='.$post->supp_name.' id="edit_ticket" class="btn btn-xs btn-primary" type="button" data-toggle="modal" data-target="#edit_ticket_modal"><i class="glyphicon glyphicon-pencil"></i></button>';
                            $nestedData['knock_off_amount'] = $post->knock_off_amount;                        
                            $data[] = $nestedData;

                        }
                    } 

                    $json_data = array(
                            "draw"            => intval($this->input->post('draw')),  
                            "recordsTotal"    => intval($totalData),  
                            "recordsFiltered" => intval($totalFiltered), 
                            "data"            => $data,  
                            "total_data" => $totalData, 
                            "execute_query" => $execute_query,
                            );                      
                }//close checking supplier_guid 1046
                else
                {
                    $json_data = array(
                            "draw"            => intval($this->input->post('draw')),  
                            "recordsTotal"    => 0,  
                            "recordsFiltered" => 0, 
                            "data"            => array(),  
                            "total_data" => 0, 
                            "execute_query" => '',
                            );                      
                }

                echo json_encode($json_data);              

            }
            else
            {
                redirect('#');
            }         
    } 

    public function official_receipt_child()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {  
                $user_guid = $this->session->userdata('user_guid');           

                if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') {
                $get_customer = $this->db->query("SELECT acc_guid, acc_name,seq 
                FROM `acc` WHERE isactive = 1 order by seq asc,row_seq ASC");
                } else {

                    $get_customer = $this->db->query("SELECT DISTINCT d.acc_guid,e.acc_name,e.seq FROM `set_user` AS a 
                    INNER JOIN `set_user_branch` b ON a.user_guid = b.user_guid
                    INNER JOIN `acc_branch` c ON b.branch_guid = c.branch_guid 
                    INNER JOIN `acc_concept` d ON c.concept_guid = d.concept_guid 
                    INNER JOIN `acc` e ON d.`acc_guid` = e.`acc_guid` 
                    where a.user_id = '".$_SESSION['userid']."' 
                    and e.isactive = '1' 
                    and a.module_group_guid = '".$_SESSION['module_group_guid']."' 
                    order by e.seq asc,e.row_seq ASC");
                    // echo $this->db->last_query();die;
                } 

                $data = array(
                    'customer' => $get_customer,
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('b2b_billing_invoice/official_receipt_child', $data);
                $this->load->view('footer');  

            }
            else
            {
                redirect('#');
            }         
    } 

    public function official_receipt_doc()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {  
                // echo 1;die;
                $refno=$_REQUEST['refno'];
                $doctype=$_REQUEST['doctype'];
                $supcode=$_REQUEST['supcode'];
                $doctime=$_REQUEST['doctime'];
                // '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00" target="_blank">'.$post->receipt_no.'</a>'; 

                $user_guid = $this->session->userdata('user_guid');           

                $customer_guid = $this->session->userdata('customer_guid');

                // echo $this->db->last_query();die;
                // print_r($filename->result());die;
                // $url = '127.0.0.1/PANDA_GITHUB/rest_b2b/index.php/';
                $url = 'b2b.xbridge.my/index.php';

                $to_shoot_url = $url.'/File_checking_autocount/Document_autocount_download?refno='.$refno.'&doctype='.$doctype.'&supcode='.$supcode.'&doctime='.$doctime.' 00:00:00';
                // echo $to_shoot_url;die;

                $virtual_path = base_url();
                $acc_sys_type = $this->db->query("SELECT accounting_doc FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('accounting_doc');
                
                $path = $to_shoot_url;
                // echo $path;die;
                // header("Location: ".$path, true, 301);
                // exit();

                $to_shoot_url = str_replace(' ','%20',$path);
                // http://18.139.87.215/rest_api/index.php/return_json/Document_download?refno=270118SM2PSPR0077&doctype=SIN&supcode=27Q006&doctime=2020-09-09%2023:15:00
            // echo $to_shoot_url;die;
                $ch = curl_init($to_shoot_url);

                $headers = [
                    'x-api-key: codex1234',
                    'Content-Type: application/x-www-form-urlencoded'               
                ];

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);     
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);        
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
           
                $result = curl_exec($ch);
                
                curl_close($ch);
                // echo '1'.$result;die;
                // return $result;          
                $result = $result;
                $b64Doc = $result;
                // echo str_replace('\r\n', '', $b64Doc);die;
                // echo base64_decode(str_replace('\r\n', '', $b64Doc));die;
                $pdf_b64 = base64_decode(str_replace('\r\n', '', $b64Doc));
                // $rute = 'http://192.168.10.29/github/panda_b2b/uploads/tfvaluemart/rtrtr.pdf';
                // $rute = '192.168.10.30/panda_web/haha.pdf';
                // $rute = 'C:\xampp\htdocs\lite_panda_b2b\uploads\tfvalue\acceptance_form\desmond.pdf';
                // if(file_put_contents($rute, $pdf_b64)){
            //just to force download by the browser
                header("Content-type: application/pdf");
                header('Content-Disposition: inline; filename="' . 'test' . '.pdf"'); 

                    //print base64 decoded
                echo $pdf_b64;die;

            }
            else
            {
                redirect('#');
            }         
    } 

    public function remittance()
    {
            // echo 1;die;
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $_SESSION['user_guid']; 
                $database1 = 'b2b_invoice';
                $bank_list = $this->db->query("SELECT * FROM $database1.parameter_type WHERE type = 'remittance_upload' AND isactive = 1 ORDER BY description ASC");
                $trans_type_list = $this->db->query("SELECT * FROM $database1.parameter_type WHERE type = 'remittance_upload_trans_type' AND isactive = 1 ORDER BY description ASC");

                if(in_array('IAVA',$_SESSION['module_code']))
                {
                    $supplier_guid = $this->db->query("SELECT * FROM lite_b2b.set_supplier ORDER BY supplier_name ASC");
                }
                else
                {
                    $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' GROUP BY b.`supplier_guid` ORDER BY c.supplier_name ASC");
                }

                $supplier_list_dropdown = '';
                $supplier_list_dropdown .= '<select required name="upload_remittance_supplier" style="width:100%" class="form-control select">';
                $supplier_list_dropdown .= '<option value="">-- Please Select --</option>';
                foreach($supplier_guid->result() as $row)
                {
                    $supplier_list_dropdown .= '<option value="'.$row->supplier_guid.'"'.'>'.$row->supplier_name.'</option>';
                }    
                $supplier_list_dropdown .= '</select>';                  
                // echo $supplier_list_dropdown;die;
                $bank_list_dropdown = '';
                $bank_list_dropdown .= '<select required name="upload_remittance_bank[]" class="select" style="width:100%;">';
                $bank_list_dropdown .= '<option value="">-- Please Select --</option>';
                foreach($bank_list->result() as $row)
                {
                    $bank_list_dropdown .= '<option value="'.$row->value.'"'.'>'.$row->description.'</option>';
                }    
                $bank_list_dropdown .= '</select>';   

                $trans_type_list_dropdown = '';
                $trans_type_list_dropdown .= '<select required name="upload_remittance_trans_type[]" class="select" style="width:100%;">';
                $trans_type_list_dropdown .= '<option value="">-- Please Select --</option>';
                foreach($trans_type_list->result() as $row)
                {
                    $trans_type_list_dropdown .= '<option value="'.$row->value.'"'.'>'.$row->description.'</option>';
                }    
                $trans_type_list_dropdown .= '</select>';                   
                // echo $bank_list_dropdown;die;
                $data = array(

                'remittance_list' => array(),
                'bank_list_dropdown' => $bank_list_dropdown,
                'supplier_list_dropdown' => $supplier_list_dropdown,
                'trans_type_list_dropdown' => $trans_type_list_dropdown,
                
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('b2b_billing_invoice/remittance', $data);
                $this->load->view('footer');  

            }
            else
            {
                redirect('#');
            }         
    } 

    public function remittance_table()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $this->session->userdata('user_guid');
                $customer_guid = $this->session->userdata('customer_guid');
                $columns = array(
                    0 => 'transaction_no',
                    1 => 'supplier_name',
                    2 => 'remittance_date',
                    3 => 'bank',
                    4 => 'amount',
                    5 => 'remark',
                    6 => 'action',

                );

                $limit = $this->input->post('length');
                $draw = intval($this->input->post("draw"));
                $start = intval($this->input->post("start"));
                $length = intval($this->input->post("length"));
                $order = $this->input->post("order");
                $search= $this->input->post("search");
                $search = $search['value'];
                $col = 0;
                $dir = "";
                // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

                $order_query = "";

                if(!empty($order))
                {
                  foreach($order as $o)
                  {
                      $col = $o['column'];
                      $dir= $o['dir'];

                      $order_query .= $columns[$col]." ".$dir.",";

                  }
                }      
                $order_query = rtrim($order_query,',');
                $database1 = 'lite_b2b';
                $database2 = 'b2b_invoice';
                // echo $order_query;die;
                if(in_array('IAVA',$_SESSION['module_code']))
                {                
                    $query = "SELECT * FROM $database2.`remittance_doc_log_main` a INNER JOIN $database1.set_supplier b ON a.`supplier_guid` = b.`supplier_guid`";
                }
                else
                {
                    // echo $user_guid;
                    $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$customer_guid' INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' AND a.`customer_guid` = '$customer_guid' GROUP BY b.`supplier_guid`");
                    $con_supplier_guid = '';
                    foreach($supplier_guid->result() as $row)
                    {
                        $con_supplier_guid .= "'".$row->supplier_guid."',"; 
                    }
                    $con_supplier_guid = rtrim($con_supplier_guid, ",");
                    // echo $con_deb_code;
                    $query = "SELECT * FROM $database2.`remittance_doc_log_main` a INNER JOIN $database1.set_supplier b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid IN($con_supplier_guid) ";
                }
                $query2 = " ORDER BY " .$order_query. " LIMIT " .$start. " , " .$limit. ";";
                

                // echo $query.$query2;die;
                // $receipt_list = $this->db->query("$execute_query");
                // echo $this->db->last_query();die;
                // print_r($receipt_list->result());die;

                if(empty($this->input->post('search')['value']))
                {
                    $execute_query = $query.$query2;
                    $posts = $this->db->query("$execute_query");
                    $totalDataquery = $this->db->query("$query");
                    $totalData = $totalDataquery->num_rows();
                    $totalFiltered = $totalData;
                    // echo $this->db->last_query();die;
                }
                else 
                {
                    $search = addslashes($this->input->post('search')['value']); 
                    $search_query = " WHERE (name LIKE '%$search%' OR transaction_no LIKE '%$search%' OR amount LIKE '%$search%' OR remark LIKE '%$search%')";
                    $execute_query = "SELECT * FROM "."(".$query.") a ".$search_query.$query2;
                    $execute_query_count = "SELECT count(*) as count FROM "."(".$query.") a ".$search_query;
                    // echo $execute_query;die; 

                    $posts =  $this->db->query("$execute_query");
                    $totalData = $this->db->query("$execute_query")->num_rows('count');
                    // echo $this->db->last_query();die;

                    $totalFiltered = $totalData;
                    // echo $totalFiltered;die;
                }
                // print_r($posts->result());die;
                $doc_type = 'SVV';
                $inv_doc_type = 'SIN';
                $data = array();
                if(!empty($posts))
                {                   
                    foreach ($posts->result() as $post)
                    {    
                        if($post->is_lock == 0)
                        {
                            $virtual_path = site_url('b2b_billing_invoice_controller/remittance_child').'?transaction_no='.$post->transaction_no;
                            $direct = '<a href="'.$virtual_path.'" class="btn btn-xs btn-primary" id="view_transaction" ><i class="fa fa-eye"></a>';          
                        }       
                        else
                        {
                            $direct = '';
                        }

                        $nestedData['transaction_no'] = $post->transaction_no;
                        $nestedData['supplier_name'] = $post->supplier_name;
                        $nestedData['remittance_date'] = $post->remittance_date;
                        $nestedData['total_amount'] = $post->total_amount;
                        $nestedData['remark'] = $post->remark;
                        $nestedData['file_count'] = $post->file_count;
                        $nestedData['action'] = $direct;
                        $data[] = $nestedData;

                    }
                } 

                $json_data = array(
                        "draw"            => intval($this->input->post('draw')),  
                        "recordsTotal"    => intval($totalData),  
                        "recordsFiltered" => intval($totalFiltered), 
                        "data"            => $data,  
                        "total_data" => $totalData, 
                        "execute_query" => $execute_query,
                        );  
                echo json_encode($json_data);              

            }
            else
            {
                redirect('#');
            }         
    } 

    public function remittance_upload()
    {
        // print_r($this->input->post());die;
        $database1 = 'lite_b2b';
        $database2 = 'b2b_invoice';
        $customer_guid = $this->session->userdata('customer_guid');
        $user_guid = $this->session->userdata('user_guid');

        $supplier_guid = $this->input->post('upload_remittance_supplier');
        $payment_vocher_no = $this->input->post('remittance_payment_voucher_no');
        $remittance_date = $this->input->post('upload_remittance_date');
        $remark = $this->input->post('upload_remittance_remark');
        $bank = $this->input->post('upload_remittance_bank[]');
        $transaction_type = $this->input->post('upload_remittance_trans_type[]');
        $amount = $this->input->post('upload_remittance_amount[]');
        $referrence_no = $this->input->post('upload_remittance_ref_no[]');
        $transaction_no = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        $debtor_code = $this->db->query("SELECT * FROM $database1.set_supplier WHERE supplier_guid = '$supplier_guid'");
        $is_lock = 1;

        $count_remittance_file = count($_FILES['upload_remittance_file']['name']);
        $count_payment_voucher_file = count($_FILES['upload_remittance_payment_voucher']['name']);
        $sum_count_file = $count_remittance_file+$count_payment_voucher_file;
        // $count_remittance_file = count($_FILES['upload_remittance_file']['name']);

        if($payment_vocher_no != '' && $payment_vocher_no != null)
        {
            // $filename[] = $_FILES['upload_remittance_payment_voucher']['name'];
            // print_r($filename);die;
            $path_syntax = '\\';
            $v_path = $this->db->query("SELECT value as file_path FROM $database2.parameter_type WHERE type = 'remittance_upload_pv_path' AND isactive = 1 AND code = 'path'")->row('file_path');
            $file_path = $v_path.$path_syntax.$supplier_guid.$path_syntax.'payment_voucher';
            // echo $file_path;
            $check_file = explode($path_syntax, $file_path);
            $check_file_path = '';
            // print_r($check_file);die;
            foreach ($check_file as $file_name)
            {
                $check_file_path .= $file_name.$path_syntax;
                // echo $check_file_path.'<br>';
                if(!file_exists($check_file_path))
                {
                    echo $check_file_path.'not exists'.'<br>';
                    mkdir($check_file_path, 0777, true);
                    // die;
                }
            }

            if(!file_exists($file_path))
            {
                echo 'not exists'.'<br>';
                // die;
            }   
            else
            {
                $target_file = $file_path.basename($_FILES['upload_remittance_payment_voucher']['name']);
                $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $file_type_array = array('pdf');
                $file_name = basename($_FILES['upload_remittance_payment_voucher']['name']);
                $rename_file_name = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
                // Check if image file is a actual image or fake image
                // echo $target_file.'**'.$file_type;die;  
                // echo $file_name;die;    
                if (!in_array($file_type,$file_type_array)) 
                {
                    $this->session->set_flashdata('message', 'Wrong File Type');
                    // redirect('panda_prdncn/prdncn_child?trans='.$refno.'&loc='.$loc.'&type='.$doc_type);
                } 
                $upload_file = $file_path.$path_syntax.$rename_file_name.'.'.$file_type;
                // echo $upload_file;die;
                // echo $file_path.$path_syntax.$transaction_no.'.'.$file_type;die;
                move_uploaded_file($_FILES['upload_remittance_payment_voucher']['tmp_name'],$upload_file);
                $data = array(
                    'transaction_no' => $transaction_no,
                    'transaction_no_count' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                    'customer_guid' => $customer_guid,
                    'supplier_guid' => $supplier_guid,
                    'bank' => '-',
                    'trans_type' => '-',
                    'amount' => '0',
                    'cross_refno' => $payment_vocher_no,
                    'file_path' => $upload_file,
                    'file_name' => $file_name,
                    'doc_type' =>  'PVV',
                    'file_type' =>  'pdf',
                    'is_lock' => $is_lock,
                    'created_by' => $user_guid,
                    'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                );
                $this->db->insert($database2.'.remittance_doc_log_child',$data);
                // echo $this->db->last_query();                
            }         
        }

        if($count_remittance_file > 0)
        {
            $total = 0;
            foreach($bank as $key=>$value)
            {
                // echo $value.'--'.$transaction_type[$key].'--'.$amount[$key].'--'.$referrence_no[$key].basename($_FILES['upload_remittance_file']['name'][$key]).'<br>';
                $path_syntax = '\\';
                $v_path = $this->db->query("SELECT value as file_path FROM $database2.parameter_type WHERE type = 'remittance_upload_pv_path' AND isactive = 1 AND code = 'path'")->row('file_path');
                $file_path = $v_path.$path_syntax.$supplier_guid.$path_syntax.'remittance';
                // echo $file_path;
                $check_file = explode($path_syntax, $file_path);
                $check_file_path = '';
                // print_r($check_file);die;

                foreach ($check_file as $file_name)
                {
                    $check_file_path .= $file_name.$path_syntax;
                    // echo $check_file_path.'<br>';
                    if(!file_exists($check_file_path))
                    {
                        echo $check_file_path.'not exists'.'<br>';
                        mkdir($check_file_path, 0777, true);
                        // die;
                    }
                }  

                if(!file_exists($file_path))
                {
                    echo 'not exists'.'<br>';die;
                    // die;
                }   
                else
                {
                    $target_file = $file_path.basename($_FILES['upload_remittance_file']['name'][$key]);
                    $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    $file_type_array = array('pdf','jpg','png');
                    $file_name = basename($_FILES['upload_remittance_file']['name'][$key]);
                    $rename_file_name = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
                    // Check if image file is a actual image or fake image
                    // echo $target_file.'**'.$file_type;die;  
                    // echo $file_name;die;    
                    if (!in_array($file_type,$file_type_array)) 
                    {
                        echo 'not correct file type';die;
                        $this->session->set_flashdata('message', 'Wrong File Type');
                        // redirect('panda_prdncn/prdncn_child?trans='.$refno.'&loc='.$loc.'&type='.$doc_type);
                    } 
                    $upload_file = $file_path.$path_syntax.$rename_file_name.'.'.$file_type;
                    // echo $upload_file;die;
                    // echo $file_path.$path_syntax.$transaction_no.'.'.$file_type;die;
                    move_uploaded_file($_FILES['upload_remittance_file']['tmp_name'][$key],$upload_file);
                    $data2 = array(
                        'transaction_no' => $transaction_no,
                        'transaction_no_count' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                        'customer_guid' => $customer_guid,
                        'supplier_guid' => $supplier_guid,
                        'bank' => $value,
                        'trans_type' => $transaction_type[$key],
                        'amount' => $amount[$key],
                        'cross_refno' => $referrence_no[$key],
                        'file_path' => $upload_file,
                        'file_name' => $file_name,
                        'doc_type' =>  'remittance',
                        'file_type' =>  $file_type,
                        'is_lock' => $is_lock,
                        'created_by' => $user_guid,
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                    );   
                    $this->db->insert($database2.'.remittance_doc_log_child',$data2);
                    echo $this->db->last_query();  
                    $total = $total + $amount[$key];          
                }                                      
            }
            // echo $total;die;
            $data = array(
                'transaction_no' => $transaction_no,
                'customer_guid' => $customer_guid,
                'supplier_guid' => $supplier_guid,
                'debtor_code' => $debtor_code->row('acc_code'),
                'remark' => $remark,
                'remittance_date' => $remittance_date,
                'total_amount' => $total,
                'doc_type' => 'remittance',
                'file_count' => $sum_count_file,
                'is_lock' => $is_lock,
                'created_by' => $user_guid,
                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            );
            $this->db->insert($database2.'.remittance_doc_log_main',$data);

            $this->session->set_flashdata('message', 'Save Successfully');
            redirect('b2b_billing_invoice_controller/remittance');
            echo $this->db->last_query();die;            
        }

    }

    public function remittance_child()
    {
            // echo 1;die;
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $_SESSION['user_guid']; 
                $database1 = 'b2b_invoice';
                $bank_list = $this->db->query("SELECT * FROM $database1.parameter_type WHERE type = 'remittance_upload' AND isactive = 1 ORDER BY description ASC");
                $trans_type_list = $this->db->query("SELECT * FROM $database1.parameter_type WHERE type = 'remittance_upload_trans_type' AND isactive = 1 ORDER BY description ASC");
                $transaction_no = $_REQUEST['transaction_no'];
                // echo $transaction_no;die;

                if(in_array('IAVA',$_SESSION['module_code']))
                {
                    $remittance_details = $this->db->query("SELECT * FROM $database1.remittance_doc_log_main WHERE transaction_no = '$transaction_no'");
                    $supplier_guid = $this->db->query("SELECT * FROM lite_b2b.set_supplier ORDER BY supplier_name ASC");                    
                }
                else
                {
                    $remittance_details = array();
                    $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' GROUP BY b.`supplier_guid` ORDER BY c.supplier_name ASC");
                }

                $supplier_list_dropdown = '';
                $supplier_list_dropdown .= '<select required name="upload_remittance_supplier" style="width:100%" class="form-control select">';
                $supplier_list_dropdown .= '<option value="">-- Please Select --</option>';
                foreach($supplier_guid->result() as $row)
                {
                    if($row->supplier_guid == $remittance_details->row('supplier_guid'))
                    {
                        $supplier_selected = 'selected';
                    }
                    else
                    {
                        $supplier_selected = '';
                    }
                    $supplier_list_dropdown .= '<option '.$supplier_selected.'value="'.$row->supplier_guid.'"'.'>'.$row->supplier_name.'</option>';
                }    
                $supplier_list_dropdown .= '</select>';   

                  
                // echo $bank_list_dropdown;die;
                $data = array(
                    'remittance_details' => $remittance_details,
                    'supplier_list_dropdown' => $supplier_list_dropdown,
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('b2b_billing_invoice/remittance_child', $data);
                $this->load->view('footer');  

            }
            else
            {
                redirect('#');
            }    
    }

    public function official_invoice_table()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $this->session->userdata('user_guid');
                // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
                // {   
                //     $user_guid = '3F386A10F62011ECA6B7000D3AA2838A'; 
                //     //print_r($user_guid); die;
                // }
                $customer_guid = $this->session->userdata('customer_guid');
                $supplier_guid = $this->input->post('supplier_guid');
                $columns = array(                  
                        0   => 'CompanyName',
                        1   => 'DocNo',
                        2   => 'DocDate',
                        3   => 'inv_payment_status_a_cn',
                        4   => 'inv_balance',
                        5   => 'inv_applied_amount',
                        6   => 't_receipt_no',
                        7   => 'cn_no',
                        8   => 'receipt_count',
                        9   => 'total_cn_amount',
                        10   => 't_contra_no',
                        11  => 'contra_apply_total',
                );

                $limit = $this->input->post('length');
                $draw = intval($this->input->post("draw"));
                $start = intval($this->input->post("start"));
                $length = intval($this->input->post("length"));
                $order = $this->input->post("order");
                $search= $this->input->post("search");
                $search = $search['value'];
                $col = 0;
                $dir = "";
                // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

                $order_query = "";

                if(!empty($order))
                {
                  foreach($order as $o)
                  {
                      $col = $o['column'];
                      $dir= $o['dir'];

                      $order_query .= $columns[$col]." ".$dir.",";

                  }
                }      
                $order_query = rtrim($order_query,',');
                // echo $order_query;die;
                if($supplier_guid != '')
                {
                    if(in_array('IAVA',$_SESSION['module_code']))
                    { 
                        $supplier_guid = $this->db->query("SELECT * FROM set_supplier a WHERE a.supplier_guid = '$supplier_guid'");
                        // echo $this->db->last_query();die;
                        $con_deb_code = '';
                        foreach($supplier_guid->result() as $row)
                        {
                            $con_deb_code .= "'".$row->acc_code."',"; 
                        }
                        $con_deb_code = rtrim($con_deb_code, ",");                                   
                        //$query = "SELECT * FROM (SELECT 'payment_table' AS query_type,COUNT(DISTINCT d.DocNo) AS receipt_count,'' AS inv_payment_status, ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) AS receipt_apply_total, a.debtorcode AS DebtorCode, IF( DATEDIFF(CURDATE(), a.DocDate) > 30, 1, 0 ) AS overdue_status, b.supplier_name AS CompanyName, a.DocNo, a.DocDate, a.outstanding AS inv_balance, ROUND(a.NetTotal, 2) AS inv_amount, ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) AS inv_applied_amount, IFNULL(ROUND(SUM(c.amount), 2), 0) AS receipt_apply_amount, GROUP_CONCAT(DISTINCT d.DocNo) AS t_receipt_no, COUNT(DISTINCT d.DocNo) AS t_receipt_count, GROUP_CONCAT(DISTINCT f.DocNo) AS cn_no, COUNT(DISTINCT f.DocNo) AS cn_apply_count, IFNULL(ROUND(SUM(e.Amount), 2), 0) AS total_cn_amount, CASE WHEN a.outstanding = 0 AND COUNT(DISTINCT f.DocNo) = 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Paid' WHEN a.outstanding = 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Paid + CN' WHEN a.outstanding = 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) = 0 THEN 'CN' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) = 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) = 0 THEN 'Not Paid' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Partial Paid + CN' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) = 0 THEN 'Partial CN' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) = 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Partial Paid' WHEN a.outstanding = 0 AND COUNT(DISTINCT g.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(g.paymentamt), 2), 0), 2) != 0 THEN 'Contra' WHEN a.outstanding != 0 AND COUNT(DISTINCT g.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(g.paymentamt), 2), 0), 2) != 0 THEN 'Partial Contra' ELSE 'Other' END AS inv_payment_status_a_cn,GROUP_CONCAT(DISTINCT g.ARAP_DocNo) AS t_contra_no, COUNT(DISTINCT g.ARAP_DocNo) AS t_contra_count,ROUND( IFNULL(ROUND(SUM(g.paymentamt), 2), 0), 2 ) as contra_apply_total FROM b2b_account.arinvoice a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.arpaymentknockoff c ON a.DocNo = c.I_DocNo LEFT JOIN b2b_account.arpayment d ON c.R_DocNo = d.DocNo LEFT JOIN b2b_account.arcnknockoff e ON a.DocNo = e.I_DocNo LEFT JOIN b2b_account.arcn f ON e.C_DocNo = f.DocNo LEFT JOIN b2b_account.arcontra g ON a.DocNo = g.DocNo WHERE a.DebtorCode IN ($con_deb_code) GROUP BY a.DocNo) a ";                    
                    }
                    else
                    {
                        // echo $user_guid;
                        $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$customer_guid' INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' AND a.`customer_guid` = '$customer_guid' AND c.supplier_guid = '$supplier_guid' GROUP BY b.`supplier_guid`");
                        $con_deb_code = '';
                        foreach($supplier_guid->result() as $row)
                        {
                            $con_deb_code .= "'".$row->acc_code."',"; 
                        }
                        $con_deb_code = rtrim($con_deb_code, ",");
                        // echo $con_deb_code;
                        // $query = "SELECT * FROM (SELECT 'payment_table' AS query_type,COUNT(DISTINCT d.DocNo) AS receipt_count,'' AS inv_payment_status, ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) AS receipt_apply_total, a.debtorcode AS DebtorCode, IF( DATEDIFF(CURDATE(), a.DocDate) > 30, 1, 0 ) AS overdue_status, b.supplier_name AS CompanyName, a.DocNo, a.DocDate, a.outstanding AS inv_balance, ROUND(a.NetTotal, 2) AS inv_amount, ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) AS inv_applied_amount, IFNULL(ROUND(SUM(c.amount), 2), 0) AS receipt_apply_amount, GROUP_CONCAT(DISTINCT d.DocNo) AS t_receipt_no, COUNT(DISTINCT d.DocNo) AS t_receipt_count, GROUP_CONCAT(DISTINCT f.DocNo) AS cn_no, COUNT(DISTINCT f.DocNo) AS cn_apply_count, IFNULL(ROUND(SUM(e.Amount), 2), 0) AS total_cn_amount, CASE WHEN a.outstanding = 0 AND COUNT(DISTINCT f.DocNo) = 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Paid' WHEN a.outstanding = 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Paid + CN' WHEN a.outstanding = 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) = 0 THEN 'CN' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) = 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) = 0 THEN 'Not Paid' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Partial Paid + CN' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) = 0 THEN 'Partial CN' WHEN a.outstanding != 0 AND COUNT(DISTINCT f.DocNo) = 0 AND ROUND(IFNULL(ROUND(SUM(c.amount), 2), 0),2) != 0 THEN 'Partial Paid' WHEN a.outstanding = 0 AND COUNT(DISTINCT g.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(g.paymentamt), 2), 0), 2) != 0 THEN 'Contra' WHEN a.outstanding != 0 AND COUNT(DISTINCT g.DocNo) != 0 AND ROUND(IFNULL(ROUND(SUM(g.paymentamt), 2), 0), 2) != 0 THEN 'Partial Contra' ELSE 'Other' END AS inv_payment_status_a_cn,GROUP_CONCAT(DISTINCT g.ARAP_DocNo) AS t_contra_no, COUNT(DISTINCT g.ARAP_DocNo) AS t_contra_count,ROUND( IFNULL(ROUND(SUM(g.paymentamt), 2), 0), 2 ) as contra_apply_total FROM b2b_account.arinvoice a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.arpaymentknockoff c ON a.DocNo = c.I_DocNo LEFT JOIN b2b_account.arpayment d ON c.R_DocNo = d.DocNo LEFT JOIN b2b_account.arcnknockoff e ON a.DocNo = e.I_DocNo LEFT JOIN b2b_account.arcn f ON e.C_DocNo = f.DocNo LEFT JOIN b2b_account.arcontra g ON a.DocNo = g.DocNo WHERE a.DebtorCode IN ($con_deb_code) GROUP BY a.DocNo) a ";                           
                    }

                    $query = "SELECT 
                        *,
                        CASE 
                        WHEN aa.inv_balance = 0 AND aa.receipt_apply_amount != 0 THEN 'Paid' 
                        WHEN aa.inv_balance != 0 AND aa.cn_apply_count = 0 AND aa.receipt_apply_amount != 0 THEN 'Partial Paid'
                        WHEN aa.inv_balance != 0 AND aa.cn_apply_count = 0 AND aa.receipt_apply_amount = 0 THEN 'Not Paid'  
                        WHEN aa.cal_cn_outstanding = 1 THEN 'Not Paid'
                        WHEN aa.inv_balance != 0 AND aa.cn_apply_count != 0 AND aa.receipt_apply_amount != 0 THEN 'Paid'
                        WHEN aa.inv_balance = 0 AND aa.cotra_apply_count != 0 AND aa.contra_apply_total != 0 THEN 'Paid'
                        WHEN aa.cn_apply_count != 0 AND aa.receipt_apply_amount = 0 THEN 'CN' 
                        ELSE 'Other' END AS inv_payment_status_a_cn
                    FROM 
                        (
                        SELECT 
                            'payment_table' AS query_type, 
                            COUNT(DISTINCT d.DocNo) AS receipt_count, 
                            '' AS inv_payment_status, 
                            ROUND( IFNULL( ROUND( SUM(c.amount), 2 ), 0 ), 2 ) AS receipt_apply_total,
                            a.debtorcode AS DebtorCode, 
                            IF( DATEDIFF( CURDATE(), a.DocDate ) > 30, 1, 0 ) AS overdue_status,
                            b.supplier_name AS CompanyName, 
                            a.DocNo, 
                            a.DocDate, 
                            a.outstanding AS inv_balance_old, 
                            IF(COUNT(DISTINCT f.DocNo) != '0' AND a.NetTotal != '0', a.NetTotal - IF(COUNT(f.DocNo) = COUNT(DISTINCT f.DocNo) , IFNULL(SUM(e.Amount),0), IFNULL(SUM(DISTINCT e.Amount),0) ) - ROUND(IFNULL(ROUND(SUM(c.amount),2),0),2) , a.outstanding ) AS inv_balance,
                            ROUND(a.NetTotal, 2) AS inv_amount, 
                            IF(COUNT(f.DocNo) = COUNT(DISTINCT f.DocNo) , IFNULL(SUM(e.Amount),0), IFNULL(SUM(DISTINCT e.Amount),0) ) AS total_cn_amount,
                            IFNULL( ROUND( SUM(e.Amount), 2 ), 0 ) AS total_cn_amount_old,
                            ROUND( IFNULL( ROUND( SUM(c.amount), 2 ), 0 ), 2 ) AS inv_applied_amount, 
                            IFNULL( ROUND( SUM(c.amount), 2 ), 0 ) AS receipt_apply_amount,
                            ROUND( IFNULL( ROUND( SUM(g.paymentamt), 2 ), 0 ), 2 ) AS contra_apply_total,
                            IF(a.NetTotal != 0 , IF(a.NetTotal != IF(COUNT(f.DocNo) = COUNT(DISTINCT f.DocNo) , IFNULL(SUM(e.Amount),0) + ROUND( IFNULL( ROUND( SUM(g.paymentamt), 2 ), 0 ), 2 ) , IFNULL(SUM(DISTINCT e.Amount),0) + ROUND( IFNULL( ROUND( SUM(g.paymentamt), 2 ), 0 ), 2 ) ), 1, 0) 
                            , 0 ) AS cal_cn_outstanding,
                            GROUP_CONCAT(DISTINCT d.DocNo) AS t_receipt_no, 
                            GROUP_CONCAT(DISTINCT f.DocNo) AS cn_no, 
                            GROUP_CONCAT(DISTINCT g.ARAP_DocNo) AS t_contra_no, 
                            COUNT(DISTINCT f.DocNo) AS cn_apply_count, 
                            COUNT(DISTINCT d.DocNo) AS t_receipt_count, 
                            COUNT(DISTINCT g.ARAP_DocNo) AS t_contra_count,
                            COUNT(DISTINCT g.DocNo) AS cotra_apply_count
                        FROM 
                            b2b_account.arinvoice a 
                            INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code 
                            LEFT JOIN b2b_account.arpaymentknockoff c ON a.DocNo = c.I_DocNo 
                            LEFT JOIN b2b_account.arpayment d ON c.R_DocNo = d.DocNo 
                            LEFT JOIN b2b_account.arcnknockoff e ON a.DocNo = e.I_DocNo 
                            LEFT JOIN b2b_account.arcn f ON e.C_DocNo = f.DocNo 
                            LEFT JOIN b2b_account.arcontra g ON a.DocNo = g.DocNo 
                        WHERE a.DebtorCode IN ($con_deb_code)
                        GROUP BY 
                            a.DocNo
                        ) aa ";    

                    $query2 = " ORDER BY " .$order_query. " LIMIT " .$start. " , " .$limit. ";";
                    

                    // echo $query.$query2;die;
                    // $receipt_list = $this->db->query("$execute_query");
                    // echo $this->db->last_query();die;
                    // print_r($receipt_list->result());die;

                    if(empty($this->input->post('search')['value']))
                    {
                        $execute_query = $query.$query2;
                        $posts = $this->db->query("$execute_query");
                        $totalDataquery = $this->db->query("$query");
                        // echo $query;die;
                        // echo $this->db->last_query();die;
                        // print_r($totalDataquery);die;
                        // echo $totalDataquery->num_rows();die;
                        $totalData = $totalDataquery->num_rows();
                        $totalFiltered = $totalData;
                        // echo $this->db->last_query();die;
                    }
                    else 
                    {
                        $search = addslashes($this->input->post('search')['value']); 
                        $search_query = " WHERE (CompanyName LIKE '%$search%' OR DocDate LIKE '%$search%' OR DocNo LIKE '%$search%' OR inv_amount LIKE '%$search%' OR inv_applied_amount LIKE '%$search%' OR t_receipt_no LIKE '%$search%' OR receipt_apply_total LIKE '%$search%' OR inv_payment_status LIKE '%$search%' OR receipt_count LIKE '%$search%' OR cn_no LIKE '%$search%' OR total_cn_amount LIKE '%$search%' OR cn_apply_count LIKE '%$search%' OR inv_balance LIKE '%$search%' OR query_type LIKE '%$search%' OR inv_payment_status_a_cn LIKE '%$search%')";
                        $execute_query = "SELECT * FROM "."(".$query.") a ".$search_query.$query2;
                        $execute_query_count = "SELECT count(*) as count FROM "."(".$query.") a ".$search_query;
                        // echo $execute_query;die; 

                        $posts =  $this->db->query("$execute_query");
                        $totalData = $this->db->query("$execute_query")->num_rows('count');
                        // echo $this->db->last_query();die;

                        $totalFiltered = $totalData;
                        // echo $totalFiltered;die;
                    }
                    // print_r($posts->result());die;
                    $doc_type = 'SVV';
                    $doc_type2 = 'SVI';
                    $inv_doc_type = 'SIN';
                    $data = array();
                    if(!empty($posts))
                    {                   
                        foreach ($posts->result() as $post)
                        {
                            // $virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00" target="_blank">'.$post->receipt_no.'</a>'; 

                            // $virtual_path = '<a href='.site_url('B2b_billing_invoice_controller/official_receipt_child').'?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00">'.$post->receipt_no.'</a>'; 

                            $receipt_no_string = '';

                            $receipt_no = explode(',',$post->t_receipt_no);
                            // print_r($receipt_no);
                            foreach($receipt_no as $row)
                            {
                                $row_no1 = $row;
                                $check_is_b2b_invoice = $this->db->query("SELECT * FROM b2b_account.arpayment WHERE DocNo = '$row_no1'");
                                    // echo $row.'<br>';
                                    // echo strtok($row, '(');die;
                                $inv_virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$row_no1.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$check_is_b2b_invoice->row('DocDate').' 01:00:00" target="_blank" > '.$row.'</a>'; 
                                // echo $inv_virtual_path;die;

                                $receipt_no_string .= $inv_virtual_path.' ,<br>';
                            }    
                            $receipt_no_string = rtrim($receipt_no_string, ",<br>");


                            $cn_no_string = '';

                            $cn_no = explode(',',$post->cn_no);
                            // print_r($cn_no);
                            foreach($cn_no as $row)
                            {
                                $row_no2 = $row;
                                $check_is_b2b_invoice = $this->db->query("SELECT * FROM b2b_account.arcn WHERE DocNo = '$row_no2'");
                                    // echo $row.'<br>';
                                    // echo strtok($row, '(');die;
                                $inv_virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$row_no2.'&doctype='.$doc_type2.'&supcode='.$post->DebtorCode.'&doctime='.$check_is_b2b_invoice->row('DocDate').' 00:00:00" target="_blank" > '.$row.'</a>'; 
                                // echo $inv_virtual_path;die;

                                $cn_no_string .= $inv_virtual_path.' ,<br>';
                            }    
                            $cn_no_string = rtrim($cn_no_string, ",<br>");                        
                            // echo $receipt_no_string;die;   
                            $contra_no_string = '';

                            $contra_no = explode(',',$post->t_contra_no);
                            // print_r($$post->t_contra_no);
                            foreach($contra_no as $row)
                            {
                                $row_no1 = $row;
                                // $check_is_b2b_invoice = $this->db->query("SELECT * FROM b2b_account.arpayment WHERE DocNo = '$row_no1'");
                                    // echo $row.'<br>';
                                    // echo strtok($row, '(');die;
                                $inv_virtual_path = $row; 
                                // echo $inv_virtual_path;die;

                                $contra_no_string .= $inv_virtual_path.' ,<br>';
                            }    
                            $contra_no_string = rtrim($contra_no_string, ",<br>");
                            // echo $contra_no_string;die; 
                            $row_no = $post->DocNo;
                            $check_is_b2b_invoice = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$row_no'");
                            if($check_is_b2b_invoice->num_rows() > 0)
                            {
                                $inv_no_string = '<a href="'.site_url().'/Invoice/invoices_process?inv_guid='.$check_is_b2b_invoice->row('inv_guid').'" target="_blank">'.$row_no;
                            }
                            else
                            {
                                    $check_is_b2b_reg_invoice = $this->db->query("SELECT * FROM b2b_invoice.inv_doc WHERE invoice_number = '$row_no'");
                                    if($check_is_b2b_reg_invoice->num_rows() > 0)
                                    {
                                        $inv_no_string = '<a href="'.site_url().'/Invoice/view_report_reg?inv_guid='.$check_is_b2b_reg_invoice->row('inv_guid').'" target="_blank">'.$row_no;
                                    }
                                    else
                                    {

                                        // echo $row.'<br>';
                                        // echo strtok($row, '(');die;
                                        $inv_virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$row_no.'&doctype='.$inv_doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->DocDate.' 00:00:00" target="_blank" > '.$row_no.'</a>'; 
                                        // echo $inv_virtual_path;die;
                                        $inv_no_string = $inv_virtual_path;
                                    }
                            }                                          

                            $nestedData['CompanyName'] = $post->CompanyName;
                            $nestedData['DocDate'] = date("Y-m-d",strtotime($post->DocDate));
                            $nestedData['DocNo'] = $inv_no_string;
                            $nestedData['inv_amount'] = $post->inv_amount;
                            $nestedData['inv_applied_amount'] = $post->inv_applied_amount;
                            $nestedData['t_receipt_no'] = $receipt_no_string;
                            $nestedData['receipt_apply_total'] = $post->receipt_apply_total;
                            $nestedData['inv_payment_status'] = $post->inv_payment_status;
                            $nestedData['receipt_count'] = $post->receipt_count;
                            $nestedData['cn_no'] = $cn_no_string;
                            $nestedData['total_cn_amount'] = $post->total_cn_amount;
                            $nestedData['cn_apply_count'] = $post->cn_apply_count;
                            $nestedData['inv_balance'] = $post->inv_balance;
                            $nestedData['query_type'] = $post->query_type;
                            $nestedData['inv_payment_status_a_cn'] = $post->inv_payment_status_a_cn;
                            $nestedData['overdue_status'] = $post->overdue_status;
                            $nestedData['t_contra_no'] = $contra_no_string;
                            $nestedData['contra_apply_total'] = $post->contra_apply_total;

                            $data[] = $nestedData;

                        }
                    } 

                    $json_data = array(
                            "draw"            => intval($this->input->post('draw')),  
                            "recordsTotal"    => intval($totalData),  
                            "recordsFiltered" => intval($totalFiltered), 
                            "data"            => $data,  
                            "total_data" => $totalData, 
                            "execute_query" => $execute_query,
                            );  
                }
                else
                {
                    $json_data = array(
                            "draw"            => intval($this->input->post('draw')),  
                            "recordsTotal"    => 0,  
                            "recordsFiltered" => 0, 
                            "data"            => array(),  
                            "total_data" => 0, 
                            "execute_query" => '',
                            );                      
                }
                echo json_encode($json_data);              

            }
            else
            {
                redirect('#');
            }         
    } 

    public function official_cn_table()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $this->session->userdata('user_guid');
                $customer_guid = $this->session->userdata('customer_guid');
                $supplier_guid = $this->input->post('supplier_guid');
                $columns = array(
                    0 => 'supplier_name',
                    1 => 'CNDocNo',
                    2 => 'CNDocDate',
                    3 => 'total_cn_amount',
                    4 => 'cn_balance_amount',                    
                    5 => 'invoice_number',
                    6 => 'apply_cn_amount',
                    7 => 'total_invoice_number',
                    8 => 'inv_count',                

                );

                $limit = $this->input->post('length');
                $draw = intval($this->input->post("draw"));
                $start = intval($this->input->post("start"));
                $length = intval($this->input->post("length"));
                $order = $this->input->post("order");
                $search= $this->input->post("search");
                $search = $search['value'];
                $col = 0;
                $dir = "";
                // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

                $order_query = "";

                if(!empty($order))
                {
                  foreach($order as $o)
                  {
                      $col = $o['column'];
                      $dir= $o['dir'];

                      $order_query .= $columns[$col]." ".$dir.",";

                  }
                }      
                $order_query = rtrim($order_query,',');
                // echo $order_query;die;
                if($supplier_guid != '')
                {
                    if(in_array('IAVA',$_SESSION['module_code']))
                    { 
                        $supplier_guid = $this->db->query("SELECT * FROM set_supplier a WHERE a.supplier_guid = '$supplier_guid'");
                        // echo $this->db->last_query();die;
                        $con_deb_code = '';
                        foreach($supplier_guid->result() as $row)
                        {
                            $con_deb_code .= "'".$row->acc_code."',"; 
                        }
                        $con_deb_code = rtrim($con_deb_code, ",");                                   
                        // $query = "SELECT DebtorCode,CompanyName AS supplier_name, CNDocNo, CNDocDate, ROUND(LocalTotal,2) AS total_cn_amount, ROUND(RefundAmt,2) AS cn_balance_amount, GROUP_CONCAT( DISTINCT DocNo, '<br><span style=\"color:red;\">(', ROUND(NetTotal,2), ')</span>', '<span style=\"color:green;\">(', ROUND(CNAmt,2), ')</span>' ) AS invoice_number, ROUND(KnockOffAmt, 2) AS apply_cn_amount, ROUND(SUM(NetTotal),2) AS total_invoice_number, COUNT(DISTINCT DocNo) AS inv_count FROM b2b_invoice.`autocount_cn_hub` GROUP BY CNDocNo";
                        $query = "SELECT a.DebtorCode, b.`supplier_name` AS supplier_name, a.DocNo AS CNDocNo, a.DocDate AS CNDocDate, ROUND(a.LocalTotal, 2) AS total_cn_amount, ROUND(a.RefundAmt, 2) AS cn_balance_amount, GROUP_CONCAT(DISTINCT c.I_DocNo) AS invoice_number, ROUND(a.KnockOffAmt, 2) AS apply_cn_amount, ROUND(SUM(d.NetTotal), 2) AS total_invoice_number, COUNT(DISTINCT c.I_DocNo) AS inv_count FROM b2b_account.`arcn` a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.`arcnknockoff` c ON a.`DocNo` = c.`C_DocNo` LEFT JOIN b2b_account.`arinvoice` d ON c.`I_DocNo` = d.`DocNo` WHERE a.debtorcode IN ($con_deb_code) GROUP BY a.`DocNo`";                        
                    }
                    else
                    {
                        // echo $user_guid;
                        $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$customer_guid' INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' AND a.`customer_guid` = '$customer_guid' AND c.supplier_guid = '$supplier_guid' GROUP BY b.`supplier_guid`");
                        $con_deb_code = '';
                        foreach($supplier_guid->result() as $row)
                        {
                            $con_deb_code .= "'".$row->acc_code."',"; 
                        }
                        $con_deb_code = rtrim($con_deb_code, ",");
                        // echo $con_deb_code;
                        $query = "SELECT a.DebtorCode, b.`supplier_name` AS supplier_name, a.DocNo AS CNDocNo, a.DocDate AS CNDocDate, ROUND(a.LocalTotal, 2) AS total_cn_amount, ROUND(a.RefundAmt, 2) AS cn_balance_amount, GROUP_CONCAT(DISTINCT c.I_DocNo) AS invoice_number, ROUND(a.KnockOffAmt, 2) AS apply_cn_amount, ROUND(SUM(d.NetTotal), 2) AS total_invoice_number, COUNT(DISTINCT c.I_DocNo) AS inv_count FROM b2b_account.`arcn` a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.`arcnknockoff` c ON a.`DocNo` = c.`C_DocNo` LEFT JOIN b2b_account.`arinvoice` d ON c.`I_DocNo` = d.`DocNo` WHERE a.debtorcode IN ($con_deb_code) GROUP BY a.`DocNo`";
                    }
                    $query2 = " ORDER BY " .$order_query. " LIMIT " .$start. " , " .$limit. ";";
                    

                    // echo $query.$query2;die;
                    // $receipt_list = $this->db->query("$execute_query");
                    // echo $this->db->last_query();die;
                    // print_r($receipt_list->result());die;

                    if(empty($this->input->post('search')['value']))
                    {
                        $execute_query = $query.$query2;
                        $posts = $this->db->query("$execute_query");
                        $totalDataquery = $this->db->query("$query");;
                        $totalData = $totalDataquery->num_rows();
                        $totalFiltered = $totalData;
                        // echo $this->db->last_query();die;
                    }
                    else 
                    {
                        $search = addslashes($this->input->post('search')['value']); 
                        $search_query = " WHERE (supplier_name LIKE '%$search%' OR CNDocNo LIKE '%$search%' OR CNDocDate LIKE '%$search%' OR total_cn_amount LIKE '%$search%' OR cn_balance_amount LIKE '%$search%' OR invoice_number LIKE '%$search%' OR apply_cn_amount LIKE '%$search%' OR total_invoice_number LIKE '%$search%' OR inv_count LIKE '%$search%')";
                        $execute_query = "SELECT * FROM "."(".$query.") a ".$search_query.$query2;
                        $execute_query_count = "SELECT count(*) as count FROM "."(".$query.") a ".$search_query;
                        // echo $execute_query;die; 

                        $posts =  $this->db->query("$execute_query");
                        $totalData = $this->db->query("$execute_query")->num_rows('count');
                        // echo $this->db->last_query();die;

                        $totalFiltered = $totalData;
                        // echo $totalFiltered;die;
                    }
                    // print_r($posts->result());die;
                    $doc_type = 'SVV';
                    $doc_type2 = 'SVI';
                    $inv_doc_type = 'SIN';
                    $data = array();
                    if(!empty($posts))
                    {                   
                        foreach ($posts->result() as $post)
                        {
                            // $virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00" target="_blank">'.$post->receipt_no.'</a>'; 

                            $cn_no_string = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$post->CNDocNo.'&doctype='.$doc_type2.'&supcode='.$post->DebtorCode.'&doctime='.$post->CNDocDate.' 00:00:00" target="_blank" > '.$post->CNDocNo.'</a>'; 

                            // $virtual_path = '<a href='.site_url('B2b_billing_invoice_controller/official_receipt_child').'?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00">'.$post->receipt_no.'</a>'; 

                            $inv_no_string = '';

                            $inv_no = explode(',',$post->invoice_number);
                            // print_r($inv_no);
                            foreach($inv_no as $row)
                            {
                                $row_no = $row;
                                $check_is_b2b_invoice = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$row_no'");
                                if($check_is_b2b_invoice->num_rows() > 0)
                                {
                                    $inv_no_string .= '<a href="'.site_url().'/Invoice/invoices_process?inv_guid='.$check_is_b2b_invoice->row('inv_guid').'" target="_blank">'.$row.'</a> ,<br>';
                                }
                                else
                                {
                                    // echo $row.'<br>';
                                    // echo strtok($row, '(');die;
                                    $invoice_doc_date = $this->db->query("SELECT * FROM b2b_account.arinvoice WHERE docno = '".$row."'")->row('DocDate');
                                    // echo $this->db->last_query();die;
                                    $inv_virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$row.'&doctype='.$inv_doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$invoice_doc_date.' 00:00:00" target="_blank" > '.$row.'</a>'; 
                                    // echo $inv_virtual_path;die;

                                    $inv_no_string .= $inv_virtual_path.' ,<br>';
                                }
                            }    
                            $inv_no_string = rtrim($inv_no_string, ",<br>");
                            // // echo $inv_no_string;die;                     

                            $nestedData['supplier_name'] = $post->supplier_name;
                            $nestedData['CNDocNo'] = $cn_no_string;
                            $nestedData['CNDocDate'] = date("Y-m-d",strtotime($post->CNDocDate));
                            $nestedData['total_cn_amount'] = $post->total_cn_amount;
                            $nestedData['cn_balance_amount'] = $post->cn_balance_amount;                    
                            $nestedData['invoice_number'] = $inv_no_string;
                            $nestedData['apply_cn_amount'] = $post->apply_cn_amount;
                            $nestedData['total_invoice_number'] = $post->total_invoice_number;
                            $nestedData['inv_count'] = $post->inv_count;                             
                            $data[] = $nestedData;

                        }
                    } 

                    $json_data = array(
                            "draw"            => intval($this->input->post('draw')),  
                            "recordsTotal"    => intval($totalData),  
                            "recordsFiltered" => intval($totalFiltered), 
                            "data"            => $data,  
                            "total_data" => $totalData, 
                            "execute_query" => $execute_query,
                            ); 
                }//close supplier_guid checking
                else
                {
                    $json_data = array(
                            "draw"            => intval($this->input->post('draw')),  
                            "recordsTotal"    => 0,  
                            "recordsFiltered" => 0, 
                            "data"            => array(),  
                            "total_data" => 0, 
                            "execute_query" => '',
                            );                    
                }
                echo json_encode($json_data);              

            }
            else
            {
                redirect('#');
            }         
    } 

    public function official_refund_table()
    {
        $database = 'lite_b2b';
        $user_guid = $this->session->userdata('user_guid');
        $customer_guid = $this->session->userdata('customer_guid');
        $supplier_guid = $this->input->post('supplier_guid');
        $columns = array(
            0 => 'supplier_name',
            1 => 'DocNo',
            2 => 'DocDate',
            3 => 'DocNo',
            4 => 'apply_refund_amount',                    
            5 => 'total_invoice_number',
            6 => 'inv_count',                

        );

        $limit = $this->input->post('length');
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

        $order_query = "";

        if(!empty($order))
        {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];

              $order_query .= $columns[$col]." ".$dir.",";

          }
        }      
        $order_query = rtrim($order_query,',');
        // echo $order_query;die;
        if($supplier_guid != '')
        {
            $supplier_guid = $this->db->query("SELECT * FROM $database.set_supplier a WHERE a.supplier_guid = '$supplier_guid'");
            // echo $this->db->last_query();die;
            $con_deb_code = '';
            foreach($supplier_guid->result() as $row)
            {
                $con_deb_code .= "'".$row->acc_code."',"; 
            }
            $con_deb_code = rtrim($con_deb_code, ",");                                   

            $query = "SELECT * FROM (SELECT a.DebtorCode, b.`supplier_name` AS supplier_name, a.DocNo AS DocNo, a.DocDate AS DocDate, GROUP_CONCAT(DISTINCT d.DocNo) AS invoice_number, ROUND(a.KnockOffAmt, 2) AS apply_refund_amount, ROUND(SUM(d.LocalPaymentAmt), 2) AS total_invoice_number, COUNT(DISTINCT c.knockoffdockey) AS inv_count, knockoffdoctype FROM b2b_account.`arrefund` a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.`arrefundknockoff` c ON a.`DocKey` = c.`Dockey` INNER JOIN b2b_account.`arpayment` d ON c.`knockoffdockey` = d.`dockey` WHERE a.debtorcode IN ($con_deb_code) GROUP BY a.`DocNo`) a UNION ALL SELECT * FROM (SELECT a.DebtorCode, b.`supplier_name` AS supplier_name, a.DocNo AS DocNo, a.DocDate AS DocDate, GROUP_CONCAT(DISTINCT d.DocNo) AS invoice_number, ROUND(a.KnockOffAmt, 2) AS apply_refund_amount, ROUND(SUM(d.NetTotal), 2) AS total_invoice_number, COUNT(DISTINCT c.knockoffdockey) AS inv_count, knockoffdoctype FROM b2b_account.`arrefund` a INNER JOIN lite_b2b.set_supplier b ON a.DebtorCode = b.acc_code LEFT JOIN b2b_account.`arrefundknockoff` c ON a.`DocKey` = c.`Dockey` INNER JOIN b2b_account.`arcn` d ON c.`knockoffdockey` = d.`dockey` WHERE a.debtorcode IN ($con_deb_code) GROUP BY a.`DocNo`) b";                        

            $query2 = " ORDER BY " .$order_query. " LIMIT " .$start. " , " .$limit. ";";
            

            // echo $query.$query2;die;
            // $receipt_list = $this->db->query("$execute_query");
            // echo $this->db->last_query();die;
            // print_r($receipt_list->result());die;

            if(empty($this->input->post('search')['value']))
            {
                $execute_query = $query.$query2;
                $posts = $this->db->query("$execute_query");
                $totalDataquery = $this->db->query("$query");;
                $totalData = $totalDataquery->num_rows();
                $totalFiltered = $totalData;
                // echo $this->db->last_query();die;
            }
            else 
            {
                $search = addslashes($this->input->post('search')['value']); 
                $search_query = " WHERE (supplier_name LIKE '%$search%' OR DocNo LIKE '%$search%' OR DocDate LIKE '%$search%' OR invoice_number LIKE '%$search%' OR apply_refund_amount LIKE '%$search%' OR total_invoice_number LIKE '%$search%' OR inv_count LIKE '%$search%')";
                $execute_query = "SELECT * FROM "."(".$query.") a ".$search_query.$query2;
                $execute_query_count = "SELECT count(*) as count FROM "."(".$query.") a ".$search_query;
                // echo $execute_query;die; 

                $posts =  $this->db->query("$execute_query");
                $totalData = $this->db->query("$execute_query")->num_rows('count');
                // echo $this->db->last_query();die;

                $totalFiltered = $totalData;
                // echo $totalFiltered;die;
            }
            // print_r($posts->result());die;
            $doc_type = 'SVV';
            $doc_type2 = 'SVI';
            $inv_doc_type = 'SIN';
            $data = array();
            if(!empty($posts))
            {                   
                foreach ($posts->result() as $post)
                {
                    // $virtual_path = '<a href="https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00" target="_blank">'.$post->receipt_no.'</a>'; 

                    $cn_no_string = '<a href='.site_url('File_checking_autocount/Document_autocount').'?refno='.$post->DocNo.'&doctype='.$doc_type2.'&supcode='.$post->DebtorCode.'&doctime='.$post->DocDate.' 00:00:00" target="_blank" > '.$post->DocNo.'</a>'; 

                    // $virtual_path = '<a href='.site_url('B2b_billing_invoice_controller/official_receipt_child').'?refno='.$post->receipt_no.'&doctype='.$doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$post->PayDocDate.' 00:00:00">'.$post->receipt_no.'</a>'; 

                    $inv_no_string = '';

                    $inv_no = explode(',',$post->invoice_number);
                    // print_r($inv_no);
                    foreach($inv_no as $row)
                    {
                        $row_no = $row;
                        $check_is_b2b_invoice = $this->db->query("SELECT * FROM b2b_invoice.supplier_monthly_main WHERE invoice_number = '$row_no'");
                        if($check_is_b2b_invoice->num_rows() > 0)
                        {
                            $inv_no_string .= '<a href="'.site_url().'/manage_controller_new/view_report_inv?inv_guid='.$check_is_b2b_invoice->row('inv_guid').'&inv_number='.$check_is_b2b_invoice->row('invoice_number').'" target="_blank">'.$row.'</a> ,<br>';
                        }
                        else
                        {
                            // echo $row.'<br>';
                            // echo strtok($row, '(');die;
                            $invoice_doc_date = $this->db->query("SELECT * FROM b2b_account.arinvoice WHERE docno = '".$row."'")->row('DocDate');
                            // echo $this->db->last_query();die;
                            if($post->knockoffdoctype == 'RC')
                            {
                                $cn_inv_doc_type = 'SVI';
                                $invoice_doc_date = $this->db->query("SELECT * FROM b2b_account.arcn WHERE docno = '".$row."'")->row('DocDate');
                            }
                            elseif($post->knockoffdoctype == 'RP')
                            {
                                $cn_inv_doc_type = 'RF';
                                $invoice_doc_date = $this->db->query("SELECT * FROM b2b_account.arpayment WHERE docno = '".$row."'")->row('DocDate');
                            }
                            $inv_virtual_path = '<a href='.'https://b2b.xbridge.my/index.php/File_checking_autocount/Document_autocount'.'?refno='.$row.'&doctype='.$cn_inv_doc_type.'&supcode='.$post->DebtorCode.'&doctime='.$invoice_doc_date.' 00:00:00" target="_blank" > '.$row.'</a>'; 
                            // echo $inv_virtual_path;die;

                            $inv_no_string .= $inv_virtual_path.' ,<br>';
                        }
                    }    
                    $inv_no_string = rtrim($inv_no_string, ",<br>");
                    // // echo $inv_no_string;die;                     

                    $nestedData['supplier_name'] = $post->supplier_name;
                    $nestedData['DocNo'] = $cn_no_string;
                    $nestedData['DocDate'] = date("Y-m-d",strtotime($post->DocDate));                 
                    $nestedData['invoice_number'] = $inv_no_string;
                    $nestedData['apply_refund_amount'] = $post->apply_refund_amount;
                    $nestedData['total_invoice_number'] = $post->total_invoice_number;
                    $nestedData['inv_count'] = $post->inv_count;                             
                    $data[] = $nestedData;

                }
            } 

            $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data,  
                    "total_data" => $totalData, 
                    "execute_query" => $execute_query,
                    ); 
        }//close supplier_guid checking
        else
        {
            $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => 0,  
                    "recordsFiltered" => 0, 
                    "data"            => array(),  
                    "total_data" => 0, 
                    "execute_query" => '',
                    );                    
        }
        echo json_encode($json_data);

       
    }

    public function statement()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $user_guid = $this->session->userdata('user_guid');
            // $customer_guid = $this->session->userdata('customer_guid');
            $customer_guid = $this->session->userdata('customer_guid');
            $database = 'lite_b2b';

            if(in_array('IAVA',$_SESSION['module_code']))
            {
                $supplier_guid = $this->db->query("SELECT a.* FROM set_supplier a INNER JOIN set_supplier_group b ON a.supplier_guid = b.supplier_guid AND b.customer_guid = '$customer_guid' GROUP BY a.supplier_guid ORDER BY a.supplier_name ASC");

                $supplier_drop_down = '<select class="form-control select2" id="receipt_supplier" name="supplier_selected">';
                
                if($supplier_guid->num_rows() > 0)
                {
                    $supplier_drop_down .= '<option>Please Select One Supplier</option>';
                    foreach($supplier_guid->result() as $row)
                    {
                        $supplier_drop_down .= '<option value="'.$row->supplier_guid.'"">'.$row->supplier_name.'</option>';
                    }
                } 
                else
                {
                    $supplier_drop_down .= '<option>No Supplier Assigned</option>';
                }
                $supplier_drop_down .= '</select>';
            }
            else
            {
                $supplier_guid = $this->db->query("SELECT c.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$customer_guid' INNER JOIN lite_b2b.`set_supplier` c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.user_guid = '$user_guid' AND a.`customer_guid` = '$customer_guid' GROUP BY b.`supplier_guid` ORDER BY c.supplier_name ASC");

                $supplier_drop_down = '<select class="select2" id="receipt_supplier" name="supplier_selected">';
                
                if($supplier_guid->num_rows() > 0)
                {
                    $supplier_drop_down .= '<option>Please Select One Supplier</option>';
                    foreach($supplier_guid->result() as $row)
                    {
                        $supplier_drop_down .= '<option value="'.$row->supplier_guid.'"">'.$row->supplier_name.'</option>';
                    }
                } 
                else
                {
                    $supplier_drop_down .= '<option>No Supplier Assigned</option>';
                }
                $supplier_drop_down .= '</select>';                    
            }

            if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') {
                $get_customer = $this->db->query("SELECT acc_guid, acc_name,seq 
                FROM `acc` WHERE isactive = 1 order by seq asc,row_seq ASC");
            } else {

                $get_customer = $this->db->query("SELECT DISTINCT d.acc_guid,e.acc_name,e.seq FROM `set_user` AS a 
                INNER JOIN `set_user_branch` b ON a.user_guid = b.user_guid
                INNER JOIN `acc_branch` c ON b.branch_guid = c.branch_guid 
                INNER JOIN `acc_concept` d ON c.concept_guid = d.concept_guid 
                INNER JOIN `acc` e ON d.`acc_guid` = e.`acc_guid` 
                where a.user_id = '".$_SESSION['userid']."' 
                and e.isactive = '1' 
                and a.module_group_guid = '".$_SESSION['module_group_guid']."' 
                order by e.seq asc,e.row_seq ASC");
                // echo $this->db->last_query();die;
            }          
            // echo $supplier_drop_down;die;
            $data = array(
                'receipt_list' => array(),
                'supplier_drop_down' => $supplier_drop_down,
                'customer' => $get_customer,
            );

            $this->load->view('header');
            $this->load->view('b2b_billing_invoice/statement',$data);
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
        // echo 'No page found';die;
    }

    public function statement_report()
    {
        if(!isset($_REQUEST['acc_code']))
        {
            echo 'Please Select Debtor Code';die;
        }

        if(!isset($_REQUEST['date_from']))
        {
            echo 'Please Select Date From';die;
        }

        if(!isset($_REQUEST['date_to']))
        {
            echo 'Please Select Date To';die;
        }

        $acc_code = $_REQUEST['acc_code'];
        $date_from = $_REQUEST['date_from'];
        $date_to = $_REQUEST['date_to'];
        $session_id = $_REQUEST['session'];
        $customer_guid = $this->session->userdata('customer_guid');
        $user_guid = $this->session->userdata('user_guid');

        // $data = array(
        //     '$session_id' => 0,
        // );
        // $this->session->set_userdata($data);
        // print_r($this->session->userdata('$session_id'));die;
        // echo 'test'.$acc_code;die;
        $ip = $this->file_config_b2b->file_path_name('','web','consignment','consignment_jasper_ip','CONJASPERIP');
        // $ip = 'http://52.163.112.202:59090';
        // echo $ip;die;
        $acc_code = $this->db->query("SELECT acc_code FROM lite_b2b.set_supplier WHERE supplier_guid = '$acc_code' LIMIT 1")->row('acc_code');
        // echo $this->db->last_query();die;
        // echo $acc_code;die;

        if(!in_array('!SUPPMOV',$_SESSION['module_code']))
        {
            $value_record = 'FROM '.$date_from.' TO '.$date_to;
            // echo $value_record;die;
            $this->db->query("REPLACE into b2b_account.supplier_movement select 
            upper(replace(uuid(),'-','')) as movement_guid
            , '$customer_guid'
            , '$user_guid'
            , 'viewed_statement'
            , 'account_statement'
            , '$acc_code'
            , '$value_record'
            , now()
            ");
        };

        $doc_template = '/AutoCount_Report/statement.pdf';

        $url = $ip."/jasperserver/rest_v2/reports/reports".$doc_template."?ARCODE=".$acc_code."&datefrom=".$date_from."&dateto=".$date_to;
        // echo $url;die;
        // http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/AutoCount_Report/statement?ARCODE=DK0002&datefrom=2020-10-01&dateto=2021-09-28

        // http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/B2BReports/Consignment_pur_inv_sku.pdf?db_be=r_big_backend&taxinv_guid=BTR21070131-E026
        $curl = curl_init();

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
        // echo $response;die;
        if (strpos($response, 'Error') !== false || strpos($response, 'Bad Request') !== false) {
            echo 'No report';
            $data = array(
                '$session_id' => 1,
            );
            $this->session->set_userdata($data);
            die;
        }
        else
        {
            $data = array(
                '$session_id' => 1,
            );
            $this->session->set_userdata($data);
            // print_r($this->session->userdata('$session_id'));die;
            // echo base64_encode($response);die;
        }
        $data = array(
                '$session_id' => 1,
        );
        $this->session->set_userdata($data);
        // echo $this->session->userdata('$session_id');die;
        header('Content-type: ' . 'application/pdf');
        header('Content-Disposition: ' .'inline'.'; filename=Statement_'.$acc_code.'.pdf');
        echo $response;
        // die;

        curl_close($curl);
        die;
        // echo 1;die;
    }

    public function set_download_session()
    {
        $session_id = $this->input->post('session_id');

        $data = array(
            '$session_id' => 0,
        );
        $this->session->set_userdata($data);

        $data = array(
            'para' => 1,
        );
        echo json_encode($data);
        die;
    }

    public function check_download_session()
    {
        $session_id = $this->input->post('session_id');

        $data = array(
            'done_download' => $this->session->userdata('$session_id'),
        );
        echo json_encode($data);
        die;
    }

    public function unset_download_session()
    {
        $session_id = $this->input->post('session_id');

        $this->session->unset_userdata('$session_id');

        $data = array(
            'para' => 1,
        );

        echo json_encode($data);
        die;
    }
}
?>