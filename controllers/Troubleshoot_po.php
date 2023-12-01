<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Troubleshoot_po extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->library('Panda_PHPMailer');   
        //$this->load->library('PDFMerger');   
        $this->load->model('General_model');
        /*$this->load->library('myfpdf');   
        $this->load->library('mytcpdf');   
        $this->load->library('myfpdi'); */  
    }



    public function index()
    {

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        { 

         $data = array(
                    'set_admin_code' => $this->db->query("SELECT code,portal_description as reason from status_setting where type = 'hide_po_filter' AND isactive = 1 order by portal_description asc"),
                    'set_code' => $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc"),
                    'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PO_FILTER_STATUS' order by code='ALL' desc, code asc"),
                    'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                    'location' => $this->db->query("SELECT DISTINCT branch_code FROM acc_branch AS a INNER JOIN acc_concept AS b ON b.`concept_guid` = a.`concept_guid`  WHERE branch_code IN  (".$_SESSION['query_loc'].") and b.`acc_guid` = '".$_SESSION['customer_guid']."' order by branch_code asc "),
                    // 'supcus' => $this->db->query("SELECT * FROM b2b_summary.supcus WHERE Type = 'S' "),
                    'acc' => $this->db->query("SELECT * FROM acc WHERE isactive = 1")
                );  
         // print_r($data);die;

        $this->load->view('header');
        $this->load->view('troubleshoot_po/troubleshoot_po',$data);
        $this->load->view('footer');

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }//close index




    public function po_new_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        // $customer_guid = $this->session->userdata('customer_guid');
        $customer_guid = $this->input->post('customer_guid');
        $po_num = null != $this->input->post('po_num') ? $this->input->post('po_num') : '';
        $po_status = null != $this->input->post('po_status') ? $this->input->post('po_status') : '';
        $daterange = null != $this->input->post('daterange') ? $this->input->post('daterange') : '';
        $expiry_from = null != $this->input->post('expiry_from') ? $this->input->post('expiry_from') : '';
        $expiry_to = null != $this->input->post('expiry_to') ? $this->input->post('expiry_to') : '';
        $period_code = null != $this->input->post('period_code') ? $this->input->post('period_code') : '';
        $vendor_guid = null != $this->input->post('vendor_guid') ? $this->input->post('vendor_guid') : '';


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
            0 => 'RefNo', 
            1 => 'gr_refno',
            2 => 'loc_group',
            3 => 'SCode',
            4 => 'SName',
            5=> 'PODate',
            6=> 'DeliverDate',
            7=> 'expiry_date',
            8=> 'Total',
            9=> 'gst_tax_sum',
            10=> 'total_include_tax',
            11=> 'status',
            12=> 'portal_description',
            13=> 'RefNo',
            14 => 'RefNo'
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

            if($sterm != 'Total' && $sterm != 'gst_tax_sum' && $sterm != 'total_include_tax')
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

            }//close if
          }//close foreach
           
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;

        $vendor_code = '';

        $all_map_vendor_code = $this->db->query("SELECT * FROM lite_b2b.set_supplier a INNER JOIN lite_b2b.set_supplier_group b ON a.supplier_guid = b.supplier_guid WHERE b.customer_guid = '$customer_guid' AND a.supplier_guid = '$vendor_guid' ");


        foreach ($all_map_vendor_code->result() as $value) {

           $vendor_code .= "'".$value->supplier_group_name."',";

        }

        $vendor_code = rtrim($vendor_code,",");

        if($po_num == '')
        {
            $po_num = "";
        }//close po_num
        else
        {
            $po_num = "AND a.RefNo = '$po_num'";
        }//close else po_num


        if($daterange != '')
        {
            $daterange1 = explode(' - ', $daterange);
            $daterange_from = date('Y-m-d',strtotime($daterange1[0]));
            $daterange_to = date('Y-m-d',strtotime($daterange1[1]));

            $daterange = "AND a.PODate BETWEEN '$daterange_from' AND '$daterange_to'";
        }
        else
        {   //initial idea is to have default 7 days from today
            //$daterange_from = date('Y-m-d', strtotime('-7 days'));
            //$daterange_to = date('Y-m-d');
            $daterange_from = "";
            $daterange_to = "";
        };


        if($expiry_from == '' || $expiry_to == '')
        {
            $expiry_from = "";
        }//close expiry_from
        else
        {
            $expiry_from = "AND a.DueDate BETWEEN '$expiry_from' AND '$expiry_to'";
        }//close else expiry_from


        if($period_code == '')
        {
            $period_code = "";
        }//close period_code
        else
        {
            $period_code = "AND left(a.PODate,7)  = '$period_code'";
        }//close else period_code


        if($po_status == 'ALL')
        {
            $po_status = "WHERE a.status = a.status";
        }//close po_status
        else
        {
            $po_status = "WHERE a.status = '$po_status'";
        }//close else po_status

        $sql = "SELECT a.RefNo,b.gr_refno,a.loc_group,a.SCode,a.SName,a.PODate,a.DeliverDate,a.DueDate,a.Total,a.gst_tax_sum,a.total_include_tax,a.status,c.portal_description,a.expiry_date FROM b2b_summary.pomain a LEFT JOIN b2b_summary.po_grn_inv b ON a.RefNo = b.po_refno AND a.customer_guid = b.customer_guid LEFT JOIN status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' $po_status AND a.customer_guid = '$customer_guid' AND a.SCode IN($vendor_code) $po_num $daterange $expiry_from $period_code ";

        $query = "SELECT * FROM (".$sql.") a ".$like_first_query.$like_second_query.$order_query.$limit_query;


        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        // echo $this->db->last_query();

        if(!empty($search))
        {   
            $query_filter = "SELECT * FROM (".$sql.") a ".$like_first_query.$like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        }
        else
        {   
            $total = $this->db->query("SELECT COUNT(*) as count FROM b2b_summary.pomain a LEFT JOIN b2b_summary.po_grn_inv b ON a.RefNo = b.po_refno LEFT JOIN status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' $po_status AND a.customer_guid = '$customer_guid' AND a.SCode IN($vendor_code) $po_num $daterange $expiry_from $period_code ")->row('count');
        }


        $data = array();

        foreach($result->result() as $row)
        {   
            $refno = $row->RefNo;

            $check_scode = $this->db->query("SELECT Scode from b2b_summary.pomain where refno = '$refno' and customer_guid = '$customer_guid' ")->row('Scode');

            $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_po_2'");

            $type = $parameter->row('type');
            $code = $check_scode;
            // echo $code;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', REPLACE('$code','/','+-+')), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_po_2'")->row('query');
            // echo $replace_var;

            $virtual_path = $this->db->query("SELECT CONCAT(file_host,file_path) AS full_path FROM lite_b2b.acc WHERE acc_guid = '$customer_guid' ")->row('full_path');
           
            //$filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
            $filename = $virtual_path.'/'.$replace_var.'.pdf';

            $nestedData['RefNo'] = $row->RefNo;
            $nestedData['gr_refno'] = $row->gr_refno;
            $nestedData['loc_group'] = $row->loc_group;
            $nestedData['SCode'] = $row->SCode;
            $nestedData['SName'] = $row->SName;
            $nestedData['PODate'] = $row->PODate;
            $nestedData['DeliverDate'] = $row->DeliverDate;
            $nestedData['expiry_date'] = $row->expiry_date;
            $nestedData['Total'] = $row->Total;
            $nestedData['gst_tax_sum'] = $row->gst_tax_sum;
            $nestedData['total_include_tax'] = $row->total_include_tax;
            $nestedData['status'] = $row->status;
            $nestedData['portal_description'] = $row->portal_description;
            $nestedData['filename'] = $filename;

            $data[] = $nestedData;

        }



        // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);

    }//close itemmaster_table




    public function vendor_code_dropdown()
    {

        $customer_guid = $this->input->post('customer_guid');

        $set_supplier = $this->db->query("SELECT * FROM lite_b2b.set_supplier a INNER JOIN lite_b2b.set_supplier_group b ON a.supplier_guid = b.supplier_guid WHERE b.customer_guid = '$customer_guid' GROUP BY a.supplier_guid ORDER BY a.supplier_name ASC ");

        $data = array(
            'set_supplier' => $set_supplier->result()
        );

        echo json_encode($data);

    }//close vendor code dropdowm



    public function user_movements_table()
    {
        $customer_guid = $this->input->post('customer_guid');
        $RefNo = $this->input->post('RefNo');

        // $user_movements_table = $this->db->query("SELECT * FROM lite_b2b.supplier_movement WHERE VALUE = '$RefNo' AND customer_guid = '$customer_guid' ");


        $database = 'lite_b2b';
        $database_app = 'lite_b2b_apps';

        $user_movements_table = $this->db->query("SELECT a.*,a.created_at as c_date , IF( b.user_id IS NULL, a.user_guid, b.user_id ) AS user_id,'web' as type FROM $database.supplier_movement a LEFT JOIN $database.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.value = '$RefNo' AND a.customer_guid = '$customer_guid' AND b.acc_guid = '$customer_guid' UNION ALL SELECT a.*,(SELECT created_at FROM $database_app.supplier_movement WHERE value = a.value AND action = a.action  AND customer_guid = a.customer_guid GROUP BY VALUE ORDER BY created_at DESC LIMIT 1) as c_date , IF( b.user_id IS NULL, a.user_guid, b.user_id ) AS user_id,'app' as type FROM $database_app.supplier_movement a LEFT JOIN $database.set_user b ON a.user_guid = b.user_guid AND a.customer_guid = b.acc_guid WHERE a.value = '$RefNo' AND a.customer_guid = '$customer_guid' AND b.acc_guid = '$customer_guid' GROUP BY a.action,a.value ");

        $data = array(
            'movements' => $user_movements_table->result()
        );

        echo json_encode($data);

    }//close user_movements_table






    public function troubleshoot_report()
    {

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        { 


         if(isset($_REQUEST['customer_guid']) && isset($_REQUEST['report_guid']))
         {     
            $customer_guid = $_REQUEST['customer_guid'];
            $report_guid = $_REQUEST['report_guid'];

            $query = $this->db->query("SELECT query FROM set_report_query WHERE report_guid = '$report_guid' ")->row('query');

            $all_customer_query = strpos($query,'@customer_guid');

            $query = str_replace('@customer_guid', $customer_guid, $query);

            $execute_query = $this->db->query($query.' LIMIT 1');
            // echo $this->db->last_query();die;
            $header_array = array();
            $xheader_array = '';

            if($execute_query->num_rows() > 0)
            {
                foreach($execute_query->result()[0] as $header => $value)
                {
                    $header_array[] = $header;
                    $xheader_array .= '{"data":'.'"'.$header.'"},';
                }
            }
            

            $xheader_array = rtrim($xheader_array,',');

            $data = array(
                'acc' => $this->db->query("SELECT * FROM acc WHERE isactive = 1"),
                'report_type' => $this->db->query("SELECT * FROM set_report_query WHERE report_type = 'excel' AND active = 1 ORDER BY seq ASC "),
                'header' => $header_array,
                'datatable_columns' => $xheader_array,
                'query' => $query,
                'all_customer_query' => $all_customer_query,
            );  
         }
         else
         {  
            //without searching for report first load of page
            $data = array(
                'acc' => $this->db->query("SELECT * FROM acc WHERE isactive = 1"),
                'report_type' => $this->db->query("SELECT * FROM set_report_query WHERE report_type = 'excel' AND active = 1 ORDER BY report_name ASC ")
            );  
         }//close else


        $this->load->view('header');
        $this->load->view('troubleshoot_po/troubleshoot_report',$data);
        $this->load->view('footer');

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }//close index




    public function troubleshoot_report_search()
    {

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $this->input->post('customer_guid');
            $report_guid = $this->input->post('report_guid');

            redirect('Troubleshoot_po/troubleshoot_report?customer_guid='.$customer_guid.'&report_guid='.$report_guid);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }




    public function set_report_query_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        // $customer_guid = $this->session->userdata('customer_guid');

        $header = $this->input->post('header');
        $query = urldecode($this->input->post('query'));


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

        $valid_columns = $header;   

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

          }//close foreach
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;

        $sql = $query;

        $query = "SELECT * FROM (".$sql.") a ".$like_first_query.$like_second_query.$order_query.$limit_query;


        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        // echo $this->db->last_query();

        if(!empty($search))
        {   
            $query_filter = "SELECT * FROM (".$sql.") a ".$like_first_query.$like_second_query;
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

            foreach ($valid_columns as $property) {
                # code...
                $nestedData[$property] = $row->$property;

            }

            $data[] = $nestedData;

        }



        // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);

    }//close itemmaster_table

    public function preview_po_item_line()
    {
        $customer_guid = $this->input->post('customer_guid');
        $refno = $this->input->post('refno');
        // echo $customer_guid;
        // $ver = $this->session->userdata('redirect');
        $ver = 0;
        if($ver == 1)
        {
            $url = '127.0.0.1/PANDA_GITHUB/rest_b2b/index.php/';

            $to_shoot_url = $url."/Select/S_hq_branch_code";
        }
        else
        {
            $database1 = 'lite_b2b';
            $rest_link = $this->db->query("SELECT * FROM $database1.acc WHERE acc_guid = '$customer_guid'");
            // $url = '127.0.0.1/rest_api/index.php/';
            // $url = 'http://18.139.87.215/rest_api/index.php/return_json';
            $url = $rest_link->row('rest_url');

            $to_shoot_url = $url."/po_child_preview";
        }
        // echo $to_shoot_url;
        // die;
        
        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
        $data = array(
            'refno' => $refno,
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
            $po_item_line = $output->result;
        }
        else
        {
            $po_item_line = $output->result;
        }  

        $data = array(
            'po_item_line' => $po_item_line,
        );
        // print_r($data);die;
        echo json_encode($data);
    }


}
?>