<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edi_info extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Panda_PHPMailer');
        $this->load->library('datatables');
        $this->load->library('session');
    }


    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $group_guid = $_SESSION['module_group_guid'];

            $acc_name = $this->db->query("SELECT a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' AND a.acc_guid = '$customer_guid' LIMIT 1")->row('acc_name');

            if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
            {
                $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY a.acc_name ASC");

                $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");
            }
            else
            {
                $get_acc = $this->db->query("SELECT d.acc_guid, e.acc_name FROM lite_b2b.`set_user` AS a INNER JOIN lite_b2b.`set_user_branch` b ON a.user_guid = b.user_guid INNER JOIN lite_b2b.`acc_branch` c ON b.branch_guid = c.branch_guid INNER JOIN lite_b2b.`acc_concept` d ON c.concept_guid = d.concept_guid INNER JOIN lite_b2b.`acc` e ON d.`acc_guid` = e.`acc_guid` WHERE a.user_guid = '$user_guid' AND e.isactive = '1' AND a.module_group_guid = '$group_guid' GROUP BY d.acc_guid");
 
                $get_supplier = $this->db->query("SELECT b.`supplier_guid`,b.`supplier_name` FROM `b2b_summary`.`itemmaster_info` a INNER JOIN lite_b2b.set_supplier b ON a.`supplier_guid` = b.`supplier_guid` INNER JOIN lite_b2b.`set_supplier_user_relationship` c ON a.`supplier_guid` = c.`supplier_guid` AND c.`user_guid` = '$user_guid' WHERE a.customer_guid = '$customer_guid' GROUP BY b.supplier_name");
            }

            $data = array(
                'get_supplier' => $get_supplier->result(),
                'get_acc' => $get_acc->result(),
                'customer_guid' => $customer_guid,
                'acc_name' => $acc_name,

            );

            $this->load->view('header');
            $this->load->view('edi/edi_itemmaster', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function itemmaster_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid'];
        $post_retailer = $this->input->post("filter_retailer");
        $post_supplier = $this->input->post("filter_supplier");

        $filter_retailer = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' AND a.acc_name = '$post_retailer'")->row('acc_guid');

        $filter_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' AND a.supplier_name = '$post_supplier' ORDER BY a.supplier_name ASC")->row('supplier_guid');

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
            0=>'guid',
            1=>'supplier_name',
            2=>'supplier_code',
            3=>'b2b_item_code',
            4=>'b2b_barcode',
            5=>'b2b_article_no',
            6=>'b2b_item_description',
            7=>'b2b_um',
            8=>'supplier_item_code',
            9=>'supplier_article_no',
            10=>'supplier_item_description',
            11=>'supplier_um',
	    12=>'validity_start_date',
	    13=>'validity_end_date',
            14=>'import_at',
            15=>'is_active',
            16=>'updated_at',
            17=>'user_name',

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

        if($filter_retailer != '')
        {
            $retailer_filter = "WHERE a.customer_guid = '$filter_retailer'";
        }
        else
        {
            $retailer_filter = "WHERE a.customer_guid = '$customer_guid'";
        }

        if($filter_supplier != '')
        {
            $supplier_filter = "AND d.supplier_guid LIKE '%$filter_supplier%'";
        }
        else
        {
            $supplier_filter = '';
        }

        if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
        {

            $sql = "SELECT a.*, b.`supplier_item_code`, b.`supplier_article_no`, b.`supplier_item_description`, b.`supplier_um`, b.`validity_start_date`, b.`validity_end_date`, c.`acc_name`, d.`supplier_name`, e.`user_name` FROM `b2b_summary`.`itemmaster_info` a LEFT JOIN b2b_summary.`supplier_itemmaster_info` b ON a.`supplier_itemmaster_guid` = b.`guid` INNER JOIN lite_b2b.acc c ON a.`customer_guid` = c.`acc_guid` INNER JOIN lite_b2b.set_supplier d ON a.`supplier_guid` = d.`supplier_guid` LEFT JOIN lite_b2b.set_user e ON a.`updated_by` = e.user_guid $retailer_filter $supplier_filter GROUP BY a.guid";
        }
        else
        {
            $sql = "SELECT a.*, b.`supplier_item_code`, b.`supplier_article_no`, b.`supplier_item_description`, b.`supplier_um`, b.`validity_start_date`, b.`validity_end_date`, c.`acc_name`, d.`supplier_name`, e.`user_name` FROM `b2b_summary`.`itemmaster_info` a LEFT JOIN b2b_summary.`supplier_itemmaster_info` b ON a.`supplier_itemmaster_guid` = b.`guid` INNER JOIN lite_b2b.acc c ON a.`customer_guid` = c.`acc_guid` INNER JOIN lite_b2b.set_supplier d ON a.`supplier_guid` = d.`supplier_guid` LEFT JOIN lite_b2b.set_user e ON a.`updated_by` = e.user_guid INNER JOIN lite_b2b.`set_supplier_user_relationship` f ON a.`supplier_guid` = f.`supplier_guid` AND f.`user_guid` = '$user_guid' $retailer_filter $supplier_filter GROUP BY a.guid";
        }
        
        $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query.$order_query.$limit_query;

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
            $nestedData['guid'] = $row->guid;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['supplier_code'] = $row->supplier_code;
            $nestedData['b2b_item_code'] = $row->b2b_item_code;
            $nestedData['b2b_barcode'] = $row->b2b_barcode;
            $nestedData['b2b_article_no'] = $row->b2b_article_no;
            $nestedData['b2b_item_description'] = $row->b2b_item_description;
            $nestedData['b2b_um'] = $row->b2b_um;
            $nestedData['supplier_itemmaster_guid'] = $row->supplier_itemmaster_guid;
            $nestedData['supplier_item_code'] = $row->supplier_item_code;
            $nestedData['supplier_article_no'] = $row->supplier_article_no;
            $nestedData['supplier_item_description'] = $row->supplier_item_description;
	    $nestedData['validity_start_date'] = $row->validity_start_date;
	    $nestedData['validity_end_date'] = $row->validity_end_date;
            $nestedData['supplier_um'] = $row->supplier_um;
            $nestedData['import_at'] = $row->import_at;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_by'] = $row->updated_by;
            $nestedData['is_active'] = $row->is_active;
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['user_name'] = $row->user_name;
            

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

    public function edit_itemmaster()
    {
        $user_guid = $_SESSION['user_guid'];
        $guid = $this->input->post('guid');
        $supplier_guid = $this->input->post('supplier_guid');
        $supplier_itemmaster_guid = $this->input->post('supplier_itemmaster_guid');
        $item_code = $this->input->post('item_code');
        $item_article_no = $this->input->post('item_article_no');
        $item_description = $this->input->post('item_description');
        $item_um = $this->input->post('item_um');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');


        if(($supplier_itemmaster_guid == '') || ($supplier_itemmaster_guid == 'null') || ($supplier_itemmaster_guid == null))
        {
            $supplier_itemmaster_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_itemmaster_guid")->row('supplier_itemmaster_guid');

            $data_1 = array(
                'guid' => $supplier_itemmaster_guid,
                'supplier_guid' => $supplier_guid,
                'supplier_item_code' => $item_code,
                'supplier_article_no' => $item_article_no,
                'supplier_item_description' => $item_description,
                'validity_start_date' => $start_date,
                'validity_end_date' => $end_date,
                'supplier_um' => $item_um,
                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'created_by' => $user_guid,
                'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'updated_by' => $user_guid,
            );

            $this->db->insert('b2b_summary.supplier_itemmaster_info', $data_1);
        }
        else
        {

            $data_1 = array(
                'supplier_guid' => $supplier_guid,
                'supplier_item_code' => $item_code,
                'supplier_article_no' => $item_article_no,
                'supplier_item_description' => $item_description,
                'supplier_um' => $item_um,
                'validity_start_date' => $start_date,
                'validity_end_date' => $end_date,
                'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'updated_by' => $user_guid,
            );

            $this->db->where('guid', $supplier_itemmaster_guid);
            $this->db->update('b2b_summary.supplier_itemmaster_info', $data_1);
        }

        $data = array(
            'supplier_itemmaster_guid' => $supplier_itemmaster_guid,
            'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'updated_by' => $user_guid,
        );
        //print_r($data); die;
        $this->db->where('guid', $guid);
        $this->db->update('b2b_summary.itemmaster_info', $data);

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 'true',
               'msg' => 'Update Successfully.',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Error to update.',
            );    
            echo json_encode($data);  
            exit(); 
        }
    }
}
?>

