<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier_setup_new extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Profile_setup_model');
        $this->load->model('General_model');
        $this->load->library('Panda_PHPMailer');   
        $this->load->library('form_validation');        
        $this->load->library('datatables');
    }

    function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            if(isset($_REQUEST['supplier_guid'])) //if using highlight supplier mode
            { 

                $set_user = $this->db->query("SELECT a.acc_guid, a.user_guid, b.supplier_guid, user_id,  user_name, f.supplier_name , all_sup_assigned ,  f.`name_reg` FROM  ( SELECT  `acc_guid`,
                    `branch_guid`,
                    `module_group_guid`,
                    `user_group_guid`,
                    a.`user_guid`,
                    a.`supplier_guid`,
                    `user_id`,
                    `user_password`,
                    `user_name`,
                    a.`created_at`,
                    a.`created_by`,
                    a.`updated_at`,
                    a.`updated_by` ,
                    `supplier_name`,
                    `reg_no`,
                    `gst_no`,
                    `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid GROUP BY a.user_id ORDER BY a.updated_at DESC)  AS a INNER JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned, acc_guid FROM `check_user_supplier_customer_relationship` where acc_guid='".$_SESSION['customer_guid']."' and  supplier_guid = '".$_REQUEST['supplier_guid']."'  GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid INNER JOIN set_supplier f ON b.supplier_guid = f.supplier_guid");

                $set_supplier_group = $this->db->query("SELECT a.supplier_guid, a.supplier_group_name , a.supplier_group_guid, b.supplier_name from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where b.supplier_guid = '".$_REQUEST['supplier_guid']."' and customer_guid = '".$_SESSION['customer_guid']."' order by a.created_at desc");
            }
            else
            {
                if(isset($_REQUEST['customer_guid'])) // if using customer filter mode
                {
                    $set_user = $this->db->query("SELECT b.supplier_guid,acc_guid, a.user_guid,user_id,  user_name, f.supplier_name , all_sup_assigned ,  f.`name_reg` FROM  ( SELECT  `acc_guid`,
                        `branch_guid`,
                        `module_group_guid`,
                        `user_group_guid`,
                        a.`user_guid`,
                        a.`supplier_guid`,
                        `user_id`,
                        `user_password`,
                        `user_name`,
                        a.`created_at`,
                        a.`created_by`,
                        a.`updated_at`,
                        a.`updated_by` ,
                        `supplier_name`,
                        `reg_no`,
                        `gst_no`,
                        `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid  GROUP BY a.user_id ORDER BY a.updated_at DESC )  AS a inner JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned FROM `check_user_supplier_customer_relationship` where acc_guid = '".$_REQUEST['customer_guid']."'  GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid LEFT JOIN set_supplier f ON b.supplier_guid = f.supplier_guid ORDER BY f.supplier_name");

                    $set_supplier_group =  $this->db->query("SELECT a.supplier_guid, a.supplier_group_name , a.supplier_group_guid, b.supplier_name from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where customer_guid = '".$_SESSION['customer_guid']."'  order by a.created_at desc");
                }
                else
                {
                    $set_user = $this->db->query("SELECT b.supplier_guid,acc_guid, a.user_guid, user_id,  user_name, f.supplier_name , all_sup_assigned ,  f.`name_reg` FROM  ( SELECT  `acc_guid`,
                        `branch_guid`,
                        `module_group_guid`,
                        `user_group_guid`,
                        a.`user_guid`,
                        a.`supplier_guid`,
                        `user_id`,
                        `user_password`,
                        `user_name`,
                        a.`created_at`,
                        a.`created_by`,
                        a.`updated_at`,
                        a.`updated_by` ,
                        `supplier_name`,
                        `reg_no`,
                        `gst_no`,
                        `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid  GROUP BY a.user_id ORDER BY a.updated_at DESC )  AS a LEFT JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned FROM `check_user_supplier_customer_relationship` GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid LEFT JOIN set_supplier f ON b.supplier_guid = f.supplier_guid ORDER BY supplier_name");

                    $set_supplier_group =  $this->db->query("SELECT a.supplier_guid, a.supplier_group_name , a.supplier_group_guid, b.supplier_name from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where customer_guid = '".$_SESSION['customer_guid']."'  order by a.created_at desc");
                }
               
            }
            // if release to supplier, filter  need to be turn off limit to those that they see ONLY
            $data = array(
                'set_supplier' => $this->db->query("SELECT * FROM set_supplier order by supplier_name asc"),
                'set_user' => $set_user,
                'set_supplier_group' => $set_supplier_group,
                'set_code' => $this->db->query("SELECT code, name, supcus_guid FROM b2b_summary.supcus where customer_guid = '".$_SESSION['customer_guid']."' order by name asc"),
                'acc_current' => $this->db->query("SELECT acc_name from lite_b2b.acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_name'),
                'acc_filter' => $this->db->query("SELECT acc_guid, acc_name from lite_b2b.acc order by acc_name asc"),
                );
            $this->load->view('header');
            $this->load->view('supplier_setup_new', $data);
            $this->load->view('supplier_setup_modal_new', $data);

            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
    }



    public function reg_supplier_table()
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
            0 => 'supplier_guid',
            1 => 'isactive', 
            2 => 'name_reg', 
            3 => 'reg_no', 
            4 => 'created_at',
            5 => 'acc_code',
            6 => 'payment_term', 
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

          }//close foreach
           
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;


        $sql = "SELECT * FROM set_supplier ";

        $query = $sql.$like_first_query.$like_second_query.$order_query.$limit_query;


        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        // echo $this->db->last_query();

        if(!empty($search))
        {   
            $query_filter = $sql.$like_first_query.$like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        }
        else
        {   
            $total = $this->db->query("SELECT COUNT(*) AS count FROM set_supplier ")->row('count');
        }


        $data = array();

        foreach($result->result() as $row)
        {   
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['isactive'] = $row->isactive;
            $nestedData['reg_no'] = $row->reg_no;
            $nestedData['gst_no'] = $row->gst_no;
            $nestedData['name_reg'] = $row->name_reg;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_by'] = $row->updated_by;
            $nestedData['acc_code'] = $row->acc_code;
            $nestedData['suspended'] = $row->suspended;
            $nestedData['payment_term'] = $row->payment_term;

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
    }



    public function group_supplier_table()
    {   
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $variable = $this->input->post('variable');

        $supplier_guid = $this->input->post('supplier_guid');

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
            0 => 'supplier_guid',
            1 => 'supplier_group_name', 
            2 => 'supplier_name',
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

          }//close foreach
           
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;


        if($variable == 'supplier_guid') //if using highlight supplier mode
        { 

            $sql = "SELECT a.supplier_guid, a.supplier_group_name , a.supplier_group_guid, b.supplier_name from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where b.supplier_guid = '$supplier_guid' and customer_guid = '".$_SESSION['customer_guid']."' ";
        }
        else
        {
            if($variable == 'customer_guid') // if using customer filter mode
            {

                $sql =  "SELECT a.supplier_guid, a.supplier_group_name , a.supplier_group_guid, b.supplier_name from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where customer_guid = '".$_SESSION['customer_guid']."'  ";
            }
            else
            {

                $sql =  "SELECT a.supplier_guid, a.supplier_group_name , a.supplier_group_guid, b.supplier_name from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where customer_guid = '".$_SESSION['customer_guid']."' ";
            }//clsoe else
        }



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

            if($variable == 'supplier_guid') //if using highlight supplier mode
            { 

                $total = $this->db->query("SELECT COUNT(*) AS count from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where b.supplier_guid = '$supplier_guid' and customer_guid = '".$_SESSION['customer_guid']."' ")->row('count');
            }
            else
            {
                if($variable == 'customer_guid') // if using customer filter mode
                {

                    $total =  $this->db->query("SELECT COUNT(*) AS count from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where customer_guid = '".$_SESSION['customer_guid']."' ")->row('count');
                }
                else
                {

                    $total =  $this->db->query("SELECT COUNT(*) AS count from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where customer_guid = '".$_SESSION['customer_guid']."' ")->row('count');
                }//clsoe else
            }//close else total

        }


        $data = array();

        foreach($result->result() as $row)
        {   
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['supplier_group_name'] = $row->supplier_group_name;
            $nestedData['supplier_group_guid'] = $row->supplier_group_guid;
            $nestedData['supplier_name'] = $row->supplier_name;

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

    }//close group supplier_table


    public function acc1_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $variable = $this->input->post('variable');

        $guid = $this->input->post('supplier_guid');

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
            0 => 'a.acc_guid',
            1 => 'a.user_guid',
            2 => 'b.supplier_guid',
            3 => 'user_id',
            4 => 'user_name',
            5 => 'f.supplier_name',
            6 => 'all_sup_assigned',
            7 => 'f.`name_reg`',
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

          }//close foreach
           
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;

        if($variable == 'supplier_guid') //if using highlight supplier mode
        { 

            $sql = "SELECT a.acc_guid, a.user_guid, b.supplier_guid, user_id,  user_name, f.supplier_name , all_sup_assigned ,  f.`name_reg` FROM  ( SELECT  `acc_guid`, `branch_guid`, `module_group_guid`, `user_group_guid`, a.`user_guid`, a.`supplier_guid`, `user_id`, `user_password`, `user_name`, a.`created_at`, a.`created_by`, a.`updated_at`, a.`updated_by` , `supplier_name`, `reg_no`, `gst_no`, `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid GROUP BY a.user_id ORDER BY a.updated_at DESC)  AS a INNER JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned, acc_guid FROM `check_user_supplier_customer_relationship` where acc_guid='".$_SESSION['customer_guid']."' and  supplier_guid = '$guid'  GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid INNER JOIN set_supplier f ON b.supplier_guid = f.supplier_guid ";
        }
        else
        {
            if($variable == 'customer_guid') // if using customer filter mode
            {

                $sql =  "SELECT b.supplier_guid,acc_guid, a.user_guid,user_id,  user_name, f.supplier_name , all_sup_assigned ,  f.`name_reg` FROM  ( SELECT  `acc_guid`, `branch_guid`, `module_group_guid`, `user_group_guid`, a.`user_guid`, a.`supplier_guid`, `user_id`, `user_password`, `user_name`, a.`created_at`, a.`created_by`, a.`updated_at`, a.`updated_by` , `supplier_name`, `reg_no`, `gst_no`, `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid  GROUP BY a.user_id ORDER BY a.updated_at DESC )  AS a inner JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned FROM `check_user_supplier_customer_relationship` where acc_guid = '$guid'  GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid LEFT JOIN set_supplier f ON b.supplier_guid = f.supplier_guid ";
            }
            else
            {

                $sql =  "SELECT b.supplier_guid,acc_guid, a.user_guid, user_id,  user_name, f.supplier_name , all_sup_assigned ,  f.`name_reg` FROM  ( SELECT  `acc_guid`, `branch_guid`, `module_group_guid`, `user_group_guid`, a.`user_guid`, a.`supplier_guid`, `user_id`, `user_password`, `user_name`, a.`created_at`, a.`created_by`, a.`updated_at`, a.`updated_by` , `supplier_name`, `reg_no`, `gst_no`, `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid  GROUP BY a.user_id ORDER BY a.updated_at DESC )  AS a LEFT JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned FROM `check_user_supplier_customer_relationship` GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid LEFT JOIN set_supplier f ON b.supplier_guid = f.supplier_guid  ";
            }//clsoe else
        }


        $query = $sql.$like_first_query.$like_second_query.$order_query.$limit_query;


        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        // echo $this->db->last_query();

        if(!empty($search))
        {   
            $query_filter = $sql.$like_first_query.$like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        }
        else
        {   
            if($variable == 'supplier_guid') //if using highlight supplier mode
            { 

                $total = $this->db->query("SELECT COUNT(*) AS count FROM  ( SELECT  `acc_guid`, `branch_guid`, `module_group_guid`, `user_group_guid`, a.`user_guid`, a.`supplier_guid`, `user_id`, `user_password`, `user_name`, a.`created_at`, a.`created_by`, a.`updated_at`, a.`updated_by` , `supplier_name`, `reg_no`, `gst_no`, `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid GROUP BY a.user_id ORDER BY a.updated_at DESC)  AS a INNER JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned, acc_guid FROM `check_user_supplier_customer_relationship` where acc_guid='".$_SESSION['customer_guid']."' and  supplier_guid = '$guid'  GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid INNER JOIN set_supplier f ON b.supplier_guid = f.supplier_guid ")->row('count');;
            }
            else
            {
                if($variable == 'customer_guid') // if using customer filter mode
                {

                    $total =  $this->db->query("SELECT COUNT(*) AS count FROM  ( SELECT  `acc_guid`, `branch_guid`, `module_group_guid`, `user_group_guid`, a.`user_guid`, a.`supplier_guid`, `user_id`, `user_password`, `user_name`, a.`created_at`, a.`created_by`, a.`updated_at`, a.`updated_by` , `supplier_name`, `reg_no`, `gst_no`, `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid  GROUP BY a.user_id ORDER BY a.updated_at DESC )  AS a inner JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned FROM `check_user_supplier_customer_relationship` where acc_guid = '$guid'  GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid LEFT JOIN set_supplier f ON b.supplier_guid = f.supplier_guid ")->row('count');
                }
                else
                {

                    $total =  $this->db->query("SELECT COUNT(*) AS count FROM ( SELECT  `acc_guid`, `branch_guid`, `module_group_guid`, `user_group_guid`, a.`user_guid`, a.`supplier_guid`, `user_id`, `user_password`, `user_name`, a.`created_at`, a.`created_by`, a.`updated_at`, a.`updated_by` , `supplier_name`, `reg_no`, `gst_no`, `name_reg` FROM set_user  AS a  LEFT JOIN set_supplier AS b  ON a.supplier_guid = b.supplier_guid  GROUP BY a.user_id ORDER BY a.updated_at DESC )  AS a LEFT JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned FROM `check_user_supplier_customer_relationship` GROUP BY user_guid,  acc_guid ) b ON a.user_guid = b.user_guid LEFT JOIN set_supplier f ON b.supplier_guid = f.supplier_guid ")->row('count');
                }//clsoe else
            }
        }


        $data = array();

        foreach($result->result() as $row)
        {   
            $nestedData['acc_guid'] = $row->acc_guid;
            $nestedData['user_guid'] = $row->user_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['user_id'] = $row->user_id;
            $nestedData['user_name'] = $row->user_name;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['all_sup_assigned'] = $row->all_sup_assigned;
            $nestedData['name_reg'] = $row->name_reg;

            $data[] = $nestedData;

        }


        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);


    }//close acc1table


  public function view_records()
    {
      if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
      {     
        //echo 'asa';die;
            $user_guid = $_REQUEST['user_guid'];
            $data = array (
                'view_rec' => $this->db->query("SELECT * from `check_user_supplier_customer_relationship` where user_guid = '$user_guid'"),
                );  
            //echo var_dump($data);die;
            $this->load->view('header');
            $this->load->view('supplier_view', $data);
             $this->load->view('supplier_view_modal', $data);
            $this->load->view('footer');
      }
      else
      {
        redirect('#');
      }
    }

    public function create() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $customer_guid = $this->session->userdata('customer_guid');
            $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid' LIMIT 1");
            $supplier_name = strtoupper($this->input->post('supplier_name'));
            $reg_no = strtoupper($this->input->post('reg_no'));
            $gst_no = $this->input->post('gst_no');
            $table = $this->input->post('table');
            $mode = $this->input->post('mode');
            // $payment_term = $this->input->post('payment_term');
            $verification = $this->input->post('verification');
            $check_password = $this->db->query("SELECT LEFT(LPAD((LPAD(HOUR(NOW()),4,0)*64+DAY(NOW()))*MONTH(NOW()),4,0),4) AS `password`")->row('password');
            /*$check_password = '123';*/
            $check_current_data = $this->db->query("SELECT * from set_supplier where reg_no = '$reg_no' union all select * from set_supplier where reg_no = replace('$reg_no','-','') union all select * from set_supplier where reg_no  = CONCAT(SUBSTRING('$reg_no','1',LENGTH('$reg_no')-1),'-', SUBSTRING('$reg_no',LENGTH('$reg_no'),LENGTH('$reg_no')-1))");
        
            
                $cross_check_reg_no = str_replace("-","",$reg_no);
                $cross_check_existed = str_replace("-","",$check_current_data->row('reg_no'));
            
            
           // echo $cross_check_reg_no ; echo " $$ " ; echo $cross_check_existed ;die;
            if($mode == 'create')
            {
                if($cross_check_reg_no == $cross_check_existed)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Not Saved. Registration No. already exist.<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    //redirect('Supplier_setup');
                    redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
                };
            }
            else
            {
                // redo checking bcz when edit and found out got same id, will show error also
                $s_guid = $this->input->post('guid');
                $other_comp = $this->db->query("SELECT * from (SELECT * from set_supplier where reg_no = '$reg_no' union all select * from set_supplier where reg_no = replace('$reg_no','-','') union all select * from set_supplier where reg_no  = CONCAT(SUBSTRING('$reg_no','1',LENGTH('$reg_no')-1),'-', SUBSTRING('$reg_no',LENGTH('$reg_no'),LENGTH('$reg_no')-1)) ) a where supplier_guid <> '$s_guid'");

                $c_check_existed = str_replace("-","",$other_comp->row('reg_no'));

                if($cross_check_reg_no == $c_check_existed)
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Unable to edit. Registration No. already exist.<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    //redirect('Supplier_setup');
                    redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
                };
            }

                 if($verification != $check_password)
                 {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Not Saved. Wrong Verfication ID<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                    //redirect('Supplier_setup');
                    redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
                 }

            if($mode == 'create')
            {
                $gen_guid = $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid');
              $data = array(
                     'supplier_guid' => $gen_guid,
                    'supplier_name' => $supplier_name,
                    'isactive' => '1',
                    'reg_no' => $reg_no,
                    'gst_no' => $gst_no,
                    // 'payment_term' => $payment_term,
                    'name_reg' => $supplier_name,
                    'created_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'created_by' => $_SESSION['userid'],
                    'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'updated_by' => $_SESSION['userid'],
                );
                $this->General_model->insert_data($table, $data); 

                //check and create acc_code

                $query_supplier_name = addslashes($supplier_name);
                $check_max_acc_code = $this->db->query("SELECT MAX(acc_code) as max_code FROM set_supplier WHERE acc_code LIKE CONCAT('D', LEFT('$query_supplier_name', 1), '%')")->row('max_code');

                $get_digits_add_itiration =  $this->db->query("SELECT digits(right('$check_max_acc_code',4)) + 1 as num ")->row('num');
            
                $new_acc_code = $this->db->query("SELECT concat('D',LEFT('$query_supplier_name', 1), lpad('$get_digits_add_itiration', '4', '0') ) as new_code ")->row('new_code');
                //echo $new_acc_code;die;

                $this->db->query("UPDATE set_supplier set acc_code = '$new_acc_code' where supplier_guid = '$gen_guid'");

                //redirect('Supplier_setup');
                redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
            };

            if($mode == 'update')
            {

                $col_guid = 'supplier_guid';
                $guid = $this->input->post('guid');

                $check_inactive = $this->input->post('isactive');
                $check_user_under_company = $this->db->query("SELECT user_guid from set_supplier_user_relationship where supplier_guid = '$guid'")->result();
                $check_if_update_status = $this->db->query("SELECT * FROM set_supplier WHERE supplier_guid = '$guid'");

                foreach($check_user_under_company as $row)
                {
                    $this->db->query("UPDATE set_user set isactive = '".$check_inactive."' where user_guid = '".$row->user_guid."'");
                }
                    $data = array(
                        'supplier_name' => $supplier_name,
                        'reg_no' => $reg_no,
                        'gst_no' => $gst_no,
                        'name_reg' => $supplier_name,
                        // 'payment_term' => $payment_term,
                        'isactive' => $check_inactive,
                        'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
                        'updated_by' => $_SESSION['userid'],
                    );
                    $this->General_model->update_data($table,$col_guid, $guid, $data);

                    // echo $check_inactive . $check_if_update_status->row('isactive');die;
                    if($check_inactive != $check_if_update_status->row('isactive'))
                    {
                        $email_group = $this->db->query("SELECT a.user_id as email,a.user_name as first_name FROM set_user a INNER JOIN set_user_group b ON a.user_group_guid = b.user_group_guid INNER JOIN set_user_module c ON b.user_group_guid = c.user_group_guid INNER JOIN set_module d ON c.module_guid = d.module_guid INNER JOIN set_module_group e ON d.module_group_guid = e.module_group_guid WHERE a.isactive = 1 AND a.acc_guid = '$customer_guid' AND e.module_group_name = 'Panda B2B' AND c.isenable = 1 AND d.module_code = 'RENSS' AND a.acc_guid != 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND a.acc_guid != '1F90F5EF90DF11EA818B000D3AA2CAA9' AND a.acc_guid != '599348EDCB2F11EA9A81000C29C6CEB2' AND a.acc_guid != '907FAFE053F011EB8099063B6ABE2862' AND a.acc_guid != '13EE932D98EB11EAB05B000D3AA2838A' GROUP BY a.user_guid");
                        // print_r($email_group->result());die;
                        if($email_group->num_rows() > 0)
                        {
                            $email_name = $email_group->row('first_name');
                            $email_add = $email_group->row('email');
                            $date = $this->db->query("SELECT now() as now")->row('now');
                            $supplier_detail = $this->db->query("SELECT * FROM set_supplier WHERE supplier_guid = '$guid'");
                            $supplier_vendor_code_detail = $this->db->query("SELECT GROUP_CONCAT(supplier_group_name) as code FROM set_supplier_group WHERE supplier_guid = '$guid' AND customer_guid = '$customer_guid' ORDER BY supplier_group_name ASC");
                            $url = 'https://b2b.xbridge.my';
                            
                            if($check_inactive == 1)
                            {
                                $bodyContent = '<div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h3 class="text-info">
                                                            B2B Notification
                                                        </h3>
                                                        <p class="lead">
                                                        Following suppliers has registered @ xBridge B2B Portal for Retailer: '.$customer_name->row('acc_name').'<br>
                                                             Supplier: '.$supplier_detail->row('supplier_name').' ('.$supplier_detail->row('reg_no').')<br>Vendor Code: '.$supplier_vendor_code_detail->row('code').'<br>Date: '.$date.'
                                                            <br>Note: Please turn on the B2B Flag @ Panda Backend Supplier Setup.<br>
                                                            Regards,<br>
                                                            <a href="'.$url.'"> xBridge B2B Portal</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>';
                            }
                            else
                            {
                                $bodyContent = '<div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h3 class="text-info">
                                                            B2B Notification
                                                        </h3>
                                                        <p class="lead">
                                                        Following suppliers has unregistered @ xBridge B2B Portal for Retailer: '.$customer_name->row('acc_name').'<br>
                                                             Supplier: '.$supplier_detail->row('supplier_name').' ('.$supplier_detail->row('reg_no').')<br>Vendor Code: '.$supplier_vendor_code_detail->row('code').'<br>Date: '.$date.'
                                                            <br>Note: Please turn off the B2B Flag @ Panda Backend Supplier Setup.<br>
                                                            Regards,<br>
                                                            <a href="'.$url.'"> xBridge B2B Portal</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>';                                
                            }
                                            // echo $bodyContent;die;
                            foreach($email_group->result() as $row)
                            {
                                $email_name = $row->first_name;
                                $email_add = $row->email;
                                $subject = 'Supplier Subscription';
                                // echo $email_name,$email_add;die;
                                // $this->send_to_manager($email_add, $email_name, $date, $bodyContent);
                                $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');
                                // echo 1;die;
                            }
                        } 
                    }
                    //redirect('Supplier_setup');
                    redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
                
               
            };
        }
        else
        {
            redirect('login_c');
        }
    }

    public function create_group()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $supplier_group_name =  $this->input->post('supplier_group_name');
            $supplier_guid =  $this->input->post('supplier_guid');
            $b2b_summary_table = 'b2b_summary';
            $table = $this->input->post('table');
            $url = 'https://b2b.xbridge.my';
            $mode = $this->input->post('mode');
            $customer_guid = $this->session->userdata('customer_guid');
            $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid' LIMIT 1");
            
            if($mode == 'create')
            {
                // echo 1;die;
              $data = array(
                    'supplier_guid' => $supplier_guid,
                    'supplier_group_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
                    'supplier_group_name' => $supplier_group_name,
                    'customer_guid' => $_SESSION['customer_guid'],
                    'backend_supcus_guid' => $this->db->query("SELECT supcus_guid as  backend_supcus_guid from b2b_summary.supcus where customer_guid =  '".$_SESSION['customer_guid']."' and code = '$supplier_group_name'")->row('backend_supcus_guid'),
                    'backend_supplier_code' =>  $supplier_group_name,
                    'created_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'created_by' => $_SESSION['userid'],
                    'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'updated_by' => $_SESSION['userid'],
                );
                $this->General_model->insert_data($table, $data);

                $email_group = $this->db->query("SELECT a.user_id as email,a.user_name as first_name FROM set_user a INNER JOIN set_user_group b ON a.user_group_guid = b.user_group_guid INNER JOIN set_user_module c ON b.user_group_guid = c.user_group_guid INNER JOIN set_module d ON c.module_guid = d.module_guid INNER JOIN set_module_group e ON d.module_group_guid = e.module_group_guid WHERE a.isactive = 1 AND a.acc_guid = '$customer_guid' AND e.module_group_name = 'Panda B2B' AND c.isenable = 1 AND d.module_code = 'RENSS' AND a.acc_guid != 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND a.acc_guid != '1F90F5EF90DF11EA818B000D3AA2CAA9' AND a.acc_guid != '599348EDCB2F11EA9A81000C29C6CEB2' AND a.acc_guid != '907FAFE053F011EB8099063B6ABE2862' AND a.acc_guid != '13EE932D98EB11EAB05B000D3AA2838A' GROUP BY a.user_guid");
                // print_r($email_group->result());die;
                if($email_group->num_rows() > 0)
                {
                    $email_name = $email_group->row('first_name');
                    $email_add = $email_group->row('email');
                    $date = $this->db->query("SELECT now() as now")->row('now');
                    $supplier_detail = $this->db->query("SELECT * FROM set_supplier WHERE supplier_guid = '$supplier_guid'");
                    $supplier_vendor_code_detail = $this->db->query("SELECT code as code,name as name from $b2b_summary_table.supcus where customer_guid =  '".$_SESSION['customer_guid']."' and code = '$supplier_group_name'");
                    

                    $bodyContent = '<div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="text-info">
                                                    B2B Notification
                                                </h3>
                                                <p class="lead">
                                                Following suppliers has registered @ xBridge B2B Portal for Retailer: '.$customer_name->row('acc_name').'<br>
                                                     Supplier: '.$supplier_detail->row('supplier_name').' ('.$supplier_detail->row('reg_no').')<br>Vendor Code: '.$supplier_vendor_code_detail->row('code').'<br>Date: '.$date.'
                                                    <br>Note: Please turn on the B2B Flag @ Panda Backend Supplier Setup.<br>
                                                    Regards,<br>
                                                    <a href="'.$url.'"> xBridge B2B Portal</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>';
                                    // echo $bodyContent;die;
                    foreach($email_group->result() as $row)
                    {
                        $email_name = $row->first_name;
                        $email_add = $row->email;
                        $subject = 'Supplier Subscription';
                        // echo $email_name,$email_add;die;
                        // $this->send_to_manager($email_add, $email_name, $date, $bodyContent);
                        $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');
                        // echo 1;die;
                    }
                }                
                //redirect('Supplier_setup');
                redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
            };

            if($mode == 'update')
            {
                // echo 2;die;
                $col_guid = 'supplier_group_guid';
                $guid = $this->input->post('guid');

                $data = array(
                    'supplier_guid' => $supplier_guid,
                    'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'updated_by' => $_SESSION['userid'],
                );
                $this->General_model->update_data($table,$col_guid, $guid, $data);
               // redirect('Supplier_setup');
                redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
            };


            
        }
        else
        {
            redirect('#');
        }
    }

    public function assign()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            
            $guid = $this->input->post('guid');
            $supplier_mode = $this->input->post('supplier_mode');
            $customer_guid = $_SESSION['customer_guid'];
            $array = $this->input->post('supplier_code');
            // echo $this->input->post('supplier_name');
            // print_r($array);die;

            if($supplier_mode == 'supplier_multiple')
            {
                $result = $this->input->post('supplier_name');
                $table = 'set_user';
                $col_guid = 'user_guid';

                $data = array(
                'supplier_guid' => $result,
                );

                $this->General_model->update_data($table,$col_guid, $guid, $data);
                $this->db->query("DELETE FROM set_supplier_user_relationship WHERE customer_guid = '$customer_guid' AND user_guid = '$guid'");
                $sresult = '';
                foreach($array as $row)
                {
                    echo $row.'<br>';
                    $get_supplier_detail = $this->db->query("SELECT customer_guid,supplier_guid,supplier_group_guid FROM set_supplier_group WHERE supplier_group_guid = '$row' AND customer_guid = '$customer_guid'");

                    $icustomer_guid = $get_supplier_detail->row('customer_guid');
                    $isupplier_guid = $get_supplier_detail->row('supplier_guid');
                    $isupplier_group_guid = $get_supplier_detail->row('supplier_group_guid');
                    $iuser_id = $_SESSION['userid'];

                    $sresult .= "'".$isupplier_guid."',";

                    // echo var_dump($get_supplier_detail->result());
                    $this->db->query("REPLACE INTO set_supplier_user_relationship (customer_guid,supplier_guid,supplier_group_guid,user_guid,created_at,created_by) VALUES('$icustomer_guid','$isupplier_guid','$isupplier_group_guid','$guid',now(),'$iuser_id');");
                    // echo $this->db->last_query();
                    $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated supplier <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                }
                $result = rtrim($sresult, ",");

                // $this->General_model->update_data($table,$col_guid, $guid, $data);
                // $get_relationship = $this->db->query("SELECT * FROM set_supplier_group where supplier_guid ='$result' and customer_guid = '$customer_guid'");
            }

            if($supplier_mode == 'Supplier')
            {
                $result = $this->input->post('supplier');
                $table = 'set_user';
                $col_guid = 'user_guid';

                $data = array(
                'supplier_guid' => $result,
                );

            $this->General_model->update_data($table,$col_guid, $guid, $data);
            $get_relationship = $this->db->query("SELECT * FROM set_supplier_group where supplier_guid ='$result' and customer_guid = '$customer_guid'");

            foreach($get_relationship->result() as $row)
            {
                $this->db->query("REPLACE INTO set_supplier_user_relationship 
                        SELECT '".$_SESSION['customer_guid']."' as customer_guid
                        , '$result' as supplier_guid
                        , '".$row->supplier_group_guid."' as supplier_group_guid
                        , '$guid'
                        , now()
                        , '".$_SESSION['userid']."'
                        ");

            }
           

            $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated supplier <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
            //echo $this->db->last_query();die;
            };

            if($supplier_mode == 'SupplierGroup')
            {
                $supplier = $this->db->query("SELECT  supplier_guid FROM set_supplier_group WHERE supplier_group_guid = '".$this->input->post('supplier_group')."'  and customer_guid = '$customer_guid' ")->row('supplier_guid');

                $result = $supplier;
                $table = 'set_user';
                $col_guid = 'user_guid';

                $data = array(
                'supplier_guid' => $result,
                );
                $this->General_model->update_data($table,$col_guid, $guid, $data);
                
                 $get_relationship = $this->db->query("SELECT * FROM set_supplier_group where supplier_guid ='$result'");

            /*foreach($get_relationship->result() as $row)
            {*/
                $this->db->query("DELETE FROM set_supplier_user_relationship where user_guid = '$guid' and supplier_guid = '$result' and customer_guid = '".$_SESSION['customer_guid']."'");
                
                $this->db->query("REPLACE INTO set_supplier_user_relationship 
                        SELECT '".$_SESSION['customer_guid']."' as customer_guid
                        , '$result' as supplier_guid
                        , '".$this->input->post('supplier_group')."' as supplier_group_guid
                        , '$guid'
                        , now()
                        , '".$_SESSION['userid']."'
                        ");
            /*}*/
 
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated supplier group <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');   
            };


            $email = $this->db->query("SELECT * from email_setup");

            $setsession = array(
                'smtp_server' =>$email->row('smtp_server'),
                'email_username' =>$email->row('username'),
                'email_password' => $email->row('password'),
                'smtp_security' =>$email->row('smtp_security'),
                'smtp_port' =>$email->row('smtp_port'),
                'sender_email' =>$email->row('sender_email'),
                'sender_name' =>$email->row('sender_name'),
                'subject' => 'Supplier Subscription',
                'url' => $email->row('url'),
                );
        $this->session->set_userdata($setsession);

        // loop all group
        $user_name = $this->db->query("SELECT user_name from set_user where user_guid = '$guid' limit 1 ")->row('user_name');

        // $email_group = $this->db->query("SELECT a.user_id as email,a.user_name as first_name FROM set_user a INNER JOIN set_user_group b ON a.user_group_guid = b.user_group_guid INNER JOIN set_user_module c ON b.user_group_guid = c.user_group_guid INNER JOIN set_module d ON c.module_guid = d.module_guid INNER JOIN set_module_group e ON d.module_group_guid = e.module_group_guid WHERE a.isactive = 1 AND a.acc_guid = '$customer_guid' AND e.module_group_name = 'Panda B2B' AND c.isenable = 1 AND d.module_code = 'RENSS' GROUP BY a.user_guid");
        // // print_r($email->result());die;
        // if($email_group->num_rows() > 0)
        // {
        //     $email_name = $email_group->row('first_name');
        //     $email_add = $email_group->row('email');
        //     $date = $this->db->query("SELECT now() as now")->row('now');
            
        //     if($supplier_mode == 'supplier_multiple')
        //     {
        //         // echo '----------';die;
        //         $supplier_detail = $this->db->query("SELECT * from set_supplier where supplier_guid IN($result) GROUP BY supplier_guid");
        //         // print_r($supplier_detail->result());
        //         $xsupplier_name = '';
        //         foreach($supplier_detail->result() as $sd)
        //         {
        //             $xsupplier_name .= addslashes($sd->supplier_name).' , ';
        //         }
        //         $supplier_name = rtrim($xsupplier_name, " ,");
        //     }   
        //     else
        //     { 
        //         // echo 2;die;
        //         $supplier_detail = $this->db->query("SELECT * from set_supplier where supplier_guid = '$result'");
        //         $supplier_name = addslashes($supplier_detail->row('supplier_name'));
        //     }
            

        //     $bodyContent = '<div class="container-fluid">
        //                         <div class="row">
        //                             <div class="col-md-12">
        //                                 <h3 class="text-info">
        //                                     B2B Notification
        //                                 </h3>
        //                                 <p class="lead">
        //                                      '.$supplier_name.' => '.$user_name.' has signed up for B2B on '.$date.'
        //                                     <br>
        //                                     Regards,<br>
        //                                     <a href="'.$_SESSION['url'].'"> B2B Mail</a>
        //                                 </p>
        //                             </div>
        //                         </div>
        //                     </div>';
        //                     // echo $bodyContent;die;
        //     foreach($email_group->result() as $row)
        //     {
        //         $email_name = $row->first_name;
        //         $email_add = $row->email;
        //         $subject = 'Supplier Subscription';
        //         // echo $email_name,$email_add;die;
        //         // $this->send_to_manager($email_add, $email_name, $date, $bodyContent);
        //         // $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');
        //         // echo 1;die;
        //     }
        // }

        //echo $this->db->last_query();die;
        //redirect('Supplier_setup');
        redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);

        }
        else
        {
            redirect('login_c');
        }
    }


    public function delete_guid()
    {
        $acc_guid = $_REQUEST['acc_guid'];
        $supplier_guid = $_REQUEST['supplier_guid'];
        $supplier_group_guid = $_REQUEST['supplier_group_guid'];
        $user_guid = $_REQUEST['user_guid'];

        $this->db->query("DELETE from set_supplier_user_relationship where customer_guid = '$acc_guid' 
            and supplier_guid = '$supplier_guid' and supplier_group_guid = '$supplier_group_guid' and user_guid = '$user_guid' ");
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('message', 'Successful');    
        }
        else
        {
              $this->session->set_flashdata('warning', 'Fail To Delete');   
        }
        
        redirect('Supplier_setup/view_records?user_guid='.$user_guid);

    }

    public function send_to_manager($email_add, $email_name, $date, $bodyContent)
    {
        $mail = new PHPMailer;

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $_SESSION['smtp_server']; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_SESSION['email_username']; // SMTP username
        $mail->Password = $_SESSION['email_password']; // SMTP password
        $mail->SMTPSecure = $_SESSION['smtp_security'];// Enable TLS encryption, `ssl` also accepted
        $mail->Port = $_SESSION['smtp_port']; // TCP port to connect to

        $mail->setFrom($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addReplyTo($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addAddress($email_add, $email_name); // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML
        $path= base_url('assets/img/new.png');
        
        
        $mail->Subject = $_SESSION['subject'];
        $mail->Body    = $bodyContent;

        if(!$mail->send()) 
        {
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
        } 
        else 
        {
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
            // $this->session->set_flashdata('message', 'Message has been sent');
            //  redirect('Email_controller/setup');
            // echo 'Message has been sent';
        }
    }
    
    
    public function create_action() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $this->_rules();

            $query_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid");
            $guid = $query_guid->row('guid');

            $query_now = $this->db->query("SELECT NOW() AS datetime");
            $datetime = $query_now->row('datetime');

            $query_supplier_guid = $this->db->query
            ("SELECT supplier_guid FROM acc_concept WHERE concept_name = '".$this->input->post('concept')."' ");
            $supplier_guid = $query_supplier_guid->row('supplier_guid');

            if ($this->form_validation->run() == FALSE) {
                $this->create();
            } else {
                $data = array(
            'branch_guid' => $guid,
            'supplier_guid' => $supplier_guid,
            'branch_group_guid' => $this->input->post('branch_group',TRUE),
            'isactive' => $this->input->post('isactive',TRUE),
            'branch_code' => $this->input->post('branch_code',TRUE),
            'branch_name' => $this->input->post('branch_name',TRUE),
            'branch_regno' => $this->input->post('branch_regno',TRUE),
            'branch_gstno' => $this->input->post('branch_gstno',TRUE),
            'branch_fax' => $this->input->post('branch_fax',TRUE),
            'branch_add1' => $this->input->post('branch_add1',TRUE),
            'branch_add2' => $this->input->post('branch_add2',TRUE),
            'branch_add3' => $this->input->post('branch_add3',TRUE),
            'branch_add4' => $this->input->post('branch_add4',TRUE),
            'branch_postcode' => $this->input->post('branch_postcode',TRUE),
            'branch_state' => $this->input->post('branch_state',TRUE),
            'branch_country' => $this->input->post('branch_country',TRUE),
            'created_at' => $datetime,
            'created_by' => $_SESSION['userid'],
            'updated_at' => $datetime,
            'updated_by' => $_SESSION['userid'],
            );

                $this->Acc_branch_model->insert($data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Inserted<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }
    
    public function update() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $_SESSION['guid'] = $_REQUEST['guid'];
            $row = $this->Acc_branch_model->get_by_id($_SESSION['guid']);

            $query_concept = $this->db->query("SELECT a.*,b.`acc_name` FROM acc_concept a INNER JOIN acc b ON a.`acc_guid` = b.`acc_guid` where a.isactive = '1' ORDER BY a.updated_at DESC;");
            $concept = $query_concept->result();

            $query_branch_group = $this->db->query("SELECT a.*,b.`concept_name` FROM acc_branch_group a INNER JOIN acc_concept b ON a.`supplier_guid` = b.`supplier_guid` where a.isactive = '1' ORDER BY a.updated_at DESC");
            $branch_group = $query_branch_group->result();

            if ($row) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('acc_branch/update_action'),
                    'supplier_guid' => set_value('supplier_guid', $row->supplier_guid),
                    'branch_guid' => set_value('branch_guid', $row->branch_guid),
                    'isactive' => set_value('isactive', $row->isactive),
                    'branch_code' => set_value('branch_code', $row->branch_code),
                    'branch_name' => set_value('branch_name', $row->branch_name),
                    'branch_regno' => set_value('branch_regno', $row->branch_regno),
                    'branch_gstno' => set_value('branch_gstno', $row->branch_gstno),
                    'branch_fax' => set_value('branch_fax', $row->branch_fax),
                    'branch_add1' => set_value('branch_add1', $row->branch_add1),
                    'branch_add2' => set_value('branch_add2', $row->branch_add2),
                    'branch_add3' => set_value('branch_add3', $row->branch_add3),
                    'branch_add4' => set_value('branch_add4', $row->branch_add4),
                    'branch_postcode' => set_value('branch_postcode', $row->branch_postcode),
                    'branch_state' => set_value('branch_state', $row->branch_state),
                    'branch_country' => set_value('branch_country', $row->branch_country),
                    'created_at' => set_value('created_at', $row->created_at),
                    'created_by' => set_value('created_by', $row->created_by),
                    'disabled' => '',
                    'concept_select' => $row->concept_name,
                    'concept' => $concept,
                    'branch_group' => $branch_group,
                    'branch_group_select' => $row->group_name,

            );
                $this->load->view('header');
                $this->load->view('acc_branch/acc_branch_form', $data);
                $this->load->view('footer');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-warning text-center" style="font-size: 18px">Record Not Found<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }
    
    public function update_action() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $this->_rules();

            $query_now = $this->db->query("SELECT NOW() AS datetime");
            $datetime = $query_now->row('datetime');

            $query_supplier_guid = $this->db->query
            ("SELECT supplier_guid FROM acc_concept WHERE concept_name = '".$this->input->post('concept')."' ");
            $supplier_guid = $query_supplier_guid->row('supplier_guid');

            $query_branch_guid = $this->db->query
            ("SELECT branch_group_guid FROM acc_branch WHERE branch_name = '".$this->input->post('branch_name')."' ");
            $branch_group_guid = $query_branch_guid->row('branch_group_guid');



            if ($this->form_validation->run() == FALSE) {
                $this->update($_SESSION['guid']);
            } else {
                $data = array(
            'supplier_guid' => $supplier_guid,
            'branch_group_guid' => $branch_group_guid,
            'isactive' => $this->input->post('isactive',TRUE),
            'branch_code' => $this->input->post('branch_code',TRUE),
            'branch_name' => $this->input->post('branch_name',TRUE),
            'branch_regno' => $this->input->post('branch_regno',TRUE),
            'branch_gstno' => $this->input->post('branch_gstno',TRUE),
            'branch_fax' => $this->input->post('branch_fax',TRUE),
            'branch_add1' => $this->input->post('branch_add1',TRUE),
            'branch_add2' => $this->input->post('branch_add2',TRUE),
            'branch_add3' => $this->input->post('branch_add3',TRUE),
            'branch_add4' => $this->input->post('branch_add4',TRUE),
            'branch_postcode' => $this->input->post('branch_postcode',TRUE),
            'branch_state' => $this->input->post('branch_state',TRUE),
            'branch_country' => $this->input->post('branch_country',TRUE),
            'created_at' => $this->input->post('created_at',TRUE),
            'created_by' => $this->input->post('created_by',TRUE),
            'updated_at' => $datetime,
            'updated_by' => $_SESSION['userid'],
            );

                $this->Acc_branch_model->update($_SESSION['guid'], $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success text-center" style="font-size: 18px">Record Updated<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br></div>');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }
    
    public function delete_group()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $customer_guid = $_REQUEST['customer_guid'];
            $supplier_guid = $_REQUEST['supplier_guid'];
            $supplier_group_guid = $_REQUEST['supplier_group_guid'];

            $check_assigned_user = $this->db->query("SELECT * from set_supplier_user_relationship where customer_guid = '$customer_guid' and supplier_guid = '$supplier_guid' and supplier_group_guid = '$supplier_group_guid'");

            if($check_assigned_user->num_rows() > 0)
            {
                $this->db->query("DELETE from set_supplier_user_relationship where customer_guid = '$customer_guid' and supplier_group_guid = '$supplier_group_guid' and supplier_guid = '$supplier_guid'");
            };

            $this->db->query("DELETE from set_supplier_group where customer_guid = '$customer_guid' and supplier_group_guid = '$supplier_group_guid' and supplier_guid = '$supplier_guid'");

            $this->panda->get_uri();

            //echo $this->db->last_query();die;
            redirect('Supplier_setup_new?supplier_guid='.$supplier_guid); 
        }
        else
        {
            redirect('login_c');    
        }  
    }

    public function delete() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $_SESSION['guid'] = $_REQUEST['guid'];
            $row = $this->Acc_branch_model->get_by_id($_SESSION['guid']);

            if ($row) {
                $this->Acc_branch_model->delete($_SESSION['guid']);
                $this->session->set_flashdata('message', 'Delete Record Success');
                redirect(site_url('Profile_setup'));
            } else {
                $this->session->set_flashdata('message', 'Record Not Found');
                redirect(site_url('Profile_setup'));
            }
        }
        else
        {
            redirect('login_c');
        }
    }

    public function suspend() 
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $guid = $_REQUEST['guid'];
            //$row = $this->Acc_branch_model->get_by_id($_SESSION['guid']);
            $check_current_data = $this->db->query("SELECT suspended from set_supplier where supplier_guid = '".$guid."'");
            $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
            $user_id = $_SESSION['userid'];
            //echo $this->db->last_query();die;
            if($check_current_data->row('suspended') == '1')
            {
                $this->db->query("UPDATE set_supplier set suspended = '0',updated_at = '$updated_at', updated_by = '$user_id' where supplier_guid = '$guid'");
            }
            else
            {
                 $this->db->query("UPDATE set_supplier set suspended = '1',updated_at = '$updated_at', updated_by = '$user_id' where supplier_guid = '$guid'");
            };
            //echo $this->db->last_query();die;

            redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
        }
        else
        {
            redirect('login_c');
        }
    }

    public function _rules() 
    {
    $this->form_validation->set_rules('concept', 'concept name', 'trim|required');
    // $this->form_validation->set_rules('isactive', 'isactive', 'trim|required');
    $this->form_validation->set_rules('branch_code', 'branch code', 'trim|required');
    $this->form_validation->set_rules('branch_name', 'branch name', 'trim|required');
    $this->form_validation->set_rules('branch_regno', 'branch regno', 'trim|required');
    $this->form_validation->set_rules('branch_gstno', 'branch gstno', 'trim|required');
    $this->form_validation->set_rules('branch_fax', 'branch fax', 'trim|required');
    $this->form_validation->set_rules('branch_add1', 'branch add1', 'trim|required');
    $this->form_validation->set_rules('branch_add2', 'branch add2', 'trim|required');
    $this->form_validation->set_rules('branch_add3', 'branch add3', 'trim|required');
    $this->form_validation->set_rules('branch_add4', 'branch add4', 'trim|required');
    $this->form_validation->set_rules('branch_postcode', 'branch postcode', 'trim|required');
    $this->form_validation->set_rules('branch_state', 'branch state', 'trim|required');
    $this->form_validation->set_rules('branch_country', 'branch country', 'trim|required');

    $this->form_validation->set_rules('branch_guid', 'branch_guid', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function check()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {

            $guid = $this->input->post('guid[]');       
            $active = $this->input->post('active[]');
            
            $data = array();

            foreach($guid as $i => $id) {
                $data[] = [$_REQUEST['col_guid'] => $id, $_REQUEST['col_check'] => $active[$i] ];
            }

            $this->db->update_batch($_REQUEST['table'], $data, $_REQUEST['col_guid']); 


            //echo $this->db->last_query();die;
            //redirect('Supplier_setup');
            redirect('Supplier_setup_new?customer_guid='.$_SESSION['customer_guid']);
        }
        else
        {
            redirect('login_c');
        }
    }

    public function change_cus_guid()
    {
        $_SESSION['customer_guid'] = $_REQUEST['customer_guid'];
        redirect('Supplier_setup_new?customer_guid='.$_REQUEST['customer_guid']);
    }

    public function check_verification_id()
    {
        $verification_id = $this->input->post('verification_id');
        $check_password = $this->db->query("SELECT LEFT(LPAD((LPAD(HOUR(NOW()),4,0)*64+DAY(NOW()))*MONTH(NOW()),4,0),4) AS `password`")->row('password');

        if($verification_id == $check_password)
        {
            $verification_status = 1;
        }
        else
        {
            $verification_status = 0;
        }
        echo $verification_status;
        // echo $verification_id;
    }

    public function check_supplier_multiple()
    {
        $supplier_name = $this->db->query("SELECT * FROM set_supplier order by supplier_name asc");
        // $array = addslashes($array);
        // $supplier_code = $this->input->post('supplier_code');
        // echo var_dump($array);
        $dropdown = '';
        $dropdown .= '<div class="form-group"  id="div0"><label class="control-label col-md-3">Supplier Name</label><div class="col-md-7"><select id="supplier_name0" no="0" name="supplier_name" class="supplier_name form-control" style="width: 100%;">';
        $dropdown .= '<option required data-default value="">Please Select A Supplier</option>';
        foreach($supplier_name->result() as $row)
        {
            $dropdown .= '<option required data-default value="'.$row->supplier_guid.'">'.$row->supplier_name.'</option>';
        }
        $dropdown .= '</select></div><label class="control-label col-md-3">Supplier Code</label><div class="col-md-7" ><select id="supplier_code0" no="0" name="supplier_code[]" class="supplier_code form-control" multiple style="width: 100%;" required>';
        $dropdown .= '<option required data-default value="">Please Select Supplier Name</option>';
        $dropdown .= '</select></div></div>';
        echo $dropdown;
    }   

    public function check_supplier_multiple2()
    {
        $array = $this->input->post('supplier_code'); 
        $array = json_decode($array);
        $supplier_name = $this->db->query("SELECT * FROM set_supplier order by supplier_name asc");
        $no = $this->input->post('no');
        // $array = addslashes($array);
        // $supplier_code = $this->input->post('supplier_code');
        // echo var_dump($array);
        $supplier_code_array = array();
        // print_r($supplier_code_array);
        foreach($array as $row1) {
            // echo $row1->supplier_code.'<br>';
         $supplier_code_array[] = $row1->supplier_code;
        }

        $dropdown = '';
        $dropdown .= '<div class="form-group"  id="div'.$no.'"><label class="control-label col-md-3">Supplier Name</label><div class="col-md-7"><select id="supplier_name'.$no.'" no='.$no.' name="supplier_name" class="supplier_name form-control" style="width: 100%;">';
        $dropdown .= '<option required data-default value="">Please Select A Supplier</option>';
        foreach($supplier_name->result() as $row)
        {
            if(!in_array($row->supplier_guid,$supplier_code_array))
            {
                $dropdown .= '<option required data-default value="'.$row->supplier_guid.'">'.$row->supplier_name.'</option>';
            }
        }
        $dropdown .= '</select></div><label class="control-label col-md-3">Supplier Code</label><div class="col-md-7"><select id="supplier_code'.$no.'" no="'.$no.'" name="supplier_code[]" class="supplier_code form-control" multiple style="width: 100%;" required>';
        $dropdown .= '<option required data-default value="">Please Select Supplier Name</option>';
        $dropdown .= '</select></div><div class="col-md-2"><button type="button" name="remove_supplier" id="remove_supplier" no="'.$no.'" class="pull-right btn-sm btn-danger">x</button></div></div>';
        echo $dropdown;
    } 

    public function check_supplier_code_multiple()
    {
        $supplier_guid = $this->input->post('supplier_guid');
        $no = $this->input->post('no');
        $supplier_group = $this->db->query("SELECT a.supplier_guid, a.supplier_group_name , a.supplier_group_guid, b.supplier_name from set_supplier_group as a inner join set_supplier  as b on a.supplier_guid = b.supplier_guid where b.supplier_guid = '".$supplier_guid."' and customer_guid = '".$_SESSION['customer_guid']."' order by a.created_at desc");
        // $array = addslashes($array);
        // $supplier_code = $this->input->post('supplier_code');
        // echo var_dump($array);
        $dropdown = '';
        $dropdown .= '<select id="supplier_code" no="'.$no.'" name="supplier_code[]" class="supplier_code[]" multiple style="width: 100%;" required>';
        if($supplier_group->num_rows() > 0)
        {   
            foreach($supplier_group->result() as $row)
            {
                $dropdown .= '<option required data-default value="'.$row->supplier_group_guid.'">'.$row->supplier_group_name.'</option>';
            }
        }
        else
        {
                // $dropdown .= '<option required data-default value="">No Supplier Code</option>';    
        }
        $dropdown .= '</select>';
        echo $dropdown;
    }   

    public function check_supplier_name_multiple2()
    {
        $array = $this->input->post('supplier_code'); 
        $array = json_decode($array);
        $supplier_name = $this->db->query("SELECT * FROM set_supplier order by supplier_name asc");
        $no = $this->input->post('no');
        // $array = addslashes($array);
        // $supplier_code = $this->input->post('supplier_code');
        // echo var_dump($array);
        $supplier_code_array = array();
        // print_r($supplier_code_array);
        foreach($array as $row1) {
            // echo $row1->supplier_code.'<br>';
         $supplier_code_array[] = $row1->supplier_code;
        }

        $dropdown = '';
        $dropdown .= '<select id="supplier_name'.$no.'" no='.$no.' name="supplier_name" class="supplier_name form-control" style="width: 100%;">';
        $dropdown .= '<option required data-default value="">Please Select A Supplier</option>';
        foreach($supplier_name->result() as $row)
        {
            if(!in_array($row->supplier_guid,$supplier_code_array))
            {
                $dropdown .= '<option required data-default value="'.$row->supplier_guid.'">'.$row->supplier_name.'</option>';
            }
        }
        $dropdown .= '</select>';
        echo $dropdown;
    } 

    public function check_supplier_code_multiple_empty()
    {
        $no = $this->input->post('no');
        $dropdown = '';
        $dropdown .= '<select id="supplier_code'.$no.'" no="'.$no.'" name="supplier_code[]" class="supplier_code form-control" style="width: 100%;" required>';
        $dropdown .= '<option required data-default value="">Please Select Supplier Name</option>';    
        $dropdown .= '</select>';
        echo $dropdown;
    }    
  

    public function get_supplier_multiple()
    {
        // $user_guid = 'ABC47F5BE1A311E98731E4E7491C3E1E';
        $user_guid = $this->input->post('user_guid');
        $customer_guid = $_SESSION['customer_guid'];
        $no = 2;

        if(isset($_REQUEST['supplier_guid'])) //if using highlight supplier mode
        { 
            $result = $this->db->query("SELECT * FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') as a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') as b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM set_supplier) as c ON a.supplier_guid = c.supplier_guid GROUP BY a.supplier_guid");
        }
        else
        {
            $result = $this->db->query("SELECT * FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') as a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') as b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM set_supplier) as c ON a.supplier_guid = c.supplier_guid GROUP BY a.supplier_guid");
        }

        $result1 = $this->db->query("SELECT * FROM set_supplier ORDER BY supplier_name");

        $selected_supplier_array = array();
        foreach($result->result() as $row1)
        {
            $selected_supplier_array[] = $row1->supplier_guid;
        }
        // print_r($selected_supplier_array);die;
        $i = 0;
        $dropdown = '';
        foreach($result->result() as $row)
        {
            if(isset($_REQUEST['customer_guid'])) //if using highlight supplier mode
            {       
                $supplier_code = $this->db->query("SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid' AND supplier_guid = '$row->supplier_guid'");

                $selected_supplier_code = $this->db->query("SELECT * FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') as a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') as b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM set_supplier) as c ON a.supplier_guid = c.supplier_guid ");
            }
            else
            {
                $supplier_code = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$row->supplier_guid'");

                $selected_supplier_code = $this->db->query("SELECT * FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') as a INNER JOIN (SELECT * FROM set_supplier_group) as b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM set_supplier) as c ON a.supplier_guid = c.supplier_guid ");
            }

                $selected_supplier_code_array = array();
                foreach($selected_supplier_code->result() as $row3)
                {
                    $selected_supplier_code_array[] = $row3->supplier_group_guid;
                }
                // print_r($selected_supplier_code_array);
            // echo $row->supplier_guid.'->'.$row->supplier_name.'<br>';
            if($i == 0)
            {
                $dropdown .= '<div class="form-group"  id="div0"><label class="control-label col-md-3">Supplier Name</label><div class="col-md-7"><select id="supplier_name0" no="0" name="supplier_name" class="supplier_name form-control" style="width: 100%;">';
                $dropdown .= '<option required data-default value="">Please Select A Supplier</option>';
                foreach($result1->result() as $row1)
                {
                    if(in_array($row1->supplier_guid,$selected_supplier_array))
                    {
                        $dropdown .= '<option selected required data-default value="'.$row->supplier_guid.'">'.$row->supplier_name.'</option>';
                    }
                    else
                    {
                        $dropdown .= '<option required data-default value="'.$row1->supplier_guid.'">'.$row1->supplier_name.'</option>';  
                    }
                }
                $dropdown .= '</select></div><label class="control-label col-md-3">Supplier Code</label><div class="col-md-7" ><select id="supplier_code0" no="0" name="supplier_code[]" class="supplier_code form-control" multiple style="width: 100%;" required>';
                foreach($supplier_code->result() as $row2)
                {
                    if(in_array($row2->supplier_group_guid,$selected_supplier_code_array))
                    {
                        $dropdown .= '<option selected required data-default value="'.$row2->supplier_group_guid.'">'.$row2->supplier_group_name.'</option>';    
                    }
                    else
                    {
                        $dropdown .= '<option required data-default value="'.$row2->supplier_group_guid.'">'.$row2->supplier_group_name.'</option>';  
                    }
                }
                $dropdown .= '</select></div></div>';
            }
            else
            {
                $dropdown .= '<div class="form-group"  id="div'.$no.'"><label class="control-label col-md-3">Supplier Name</label><div class="col-md-7"><select id="supplier_name'.$no.'" no="'.$no.'" name="supplier_name" class="supplier_name form-control" style="width: 100%;">';
                $dropdown .= '<option required data-default value="">Please Select A Supplier</option>';
                foreach($result1->result() as $row1)
                {
                    if(in_array($row1->supplier_guid,$selected_supplier_array))
                    {
                        $dropdown .= '<option selected required data-default value="'.$row->supplier_guid.'">'.$row->supplier_name.'</option>';
                    }
                    else
                    {
                        $dropdown .= '<option required data-default value="'.$row1->supplier_guid.'">'.$row1->supplier_name.'</option>';  
                    }
                }
                $dropdown .= '</select></div><label class="control-label col-md-3">Supplier Code</label><div class="col-md-7" ><select id="supplier_code'.$no.'" no="'.$no.'" name="supplier_code[]" class="supplier_code form-control" multiple style="width: 100%;" required>';
                foreach($supplier_code->result() as $row2)
                {
                    if(in_array($row2->supplier_group_guid,$selected_supplier_code_array))
                    {
                        $dropdown .= '<option selected required data-default value="'.$row2->supplier_group_guid.'">'.$row2->supplier_group_name.'</option>';    
                    }
                    else
                    {
                        $dropdown .= '<option required data-default value="'.$row2->supplier_group_guid.'">'.$row2->supplier_group_name.'</option>';  
                    }
                }
                $dropdown .= '</select></div><div class="col-md-2"><button type="button" name="remove_supplier" id="remove_supplier" no="'.$no.'" class="pull-right btn-sm btn-danger">x</button></div></div>';
                $no++;
            }
            $i++;
        }
        if($no == '' || $no == null || $no == 2)
        {
            $no = 2;
        }
        $output = array();
        $output['dropdown'] = $dropdown;
        $output['countdropdown'] = $no;
        echo json_encode($output);
        // echo $dropdown;
    }    

    public function check_user_assign()
    {
        $user_guid = $this->input->post('user_guid');
        $customer_guid = $_SESSION['customer_guid'];

        if(isset($_REQUEST['customer_guid'])) //if using highlight supplier mode
        { 
            $result = $this->db->query("SELECT * FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') as a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') as b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM set_supplier) as c ON a.supplier_guid = c.supplier_guid GROUP BY a.supplier_guid");
        }
        else
        {
            $result = $this->db->query("SELECT * FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid') as a INNER JOIN (SELECT * FROM set_supplier_group) as b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM set_supplier) as c ON a.supplier_guid = c.supplier_guid GROUP BY a.supplier_guid");
        }

        if($result->num_rows() > 0)
        {
            echo 1;
        }
        else
        {
            echo 2;
        }
    }   

    public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module,$cc_list_string,$pdf,$reply_to)
    {
        // die;
        if($pdf != '' || $pdf != null)
        { 
            $b64Doc = chunk_split(base64_encode(file_get_contents($pdf))); 
            $filename = substr($pdf, strrpos($pdf, '/') + 1);
        }
        else
        {
            $b64Doc = ''; 
        }
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup WHERE type = 'alert_retailer_supplier_setup' LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
        $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');

        $replyto = array('Email' => $reply_to,'Name' => $reply_to);
        $from = array('Email' => $from_email->row('sender_email'),'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email,'Name' => $to_email_name);
        $to_array = array($to);

        if($cc_list_string != '' || $cc_list_string != null)
        {
            $test_array = explode(',',$cc_list_string);
            $cc_array=array();
            foreach($test_array as $tarray)
            {
                // echo $tarray->sender_email;
                $cc = array('Email' => $tarray,'Name' => $tarray);
                array_push($cc_array, $cc);
            }
        }
        else
        {
            $cc_array = '';  
        }

        // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
        $bcc_array = array();
        $variable1 = array($variable);
        $variables = array('var1' => $variable1);
        // $variables_array = array($variables);
        $templateid = 1090613;
        $Subject = $email_subject;
        $TextPart = $email_subject;
        $HTMLPart = $bodyContent; 

        if($b64Doc != '')
        {
            $attachment = array('ContentType' => 'application/pdf','Filename' => $filename,'Base64Content' => $b64Doc);
            $attachment1 = array($attachment);
            $attachment_array = array($attachment);            
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables,'cc' => $cc_array, 'replyto' =>$replyto,'attachments' => $attachment_array);
        }
        else
        {
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables, 'replyto' =>$replyto);
        }
        // $data2 = array($data);
        // $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data);
        // echo $myJSON;die;

        $to_shoot_url = "localhost/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
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

        // $to_shoot_url = 'localhost/pandaapi3rdparty/index.php/email_agent/mj_sendemail';
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
        // print_r($result);die;
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
                $ereponse = $result1->Messages[0]->To[0]->MessageID;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
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
                if($module != 'alert_notification')
                {
                    echo json_encode(array(
                            'status' => true,
                            'message' => 'success',
                            'action'=> 'next',
                            ));
                };
            }
            else
            {
                $ereponse = $result1->StatusCode.'-'.$result1->ErrorMessage;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
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
                    'action'=> 'retry',
                    ));
                // };
            }

            curl_close($ch);
        }
        else
        {
                $ereponse = 'Curl error: '.curl_error($ch);

                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $retry.$ereponse,
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
                    'action'=> 'retry',
                    ));          
        }         
    }    
}
// (select `d`.`user_id` AS `user_id`,`c`.`supplier_name` AS `supplier_name`,`c`.`reg_no` AS `reg_no`,`b`.`supplier_group_name` AS `supplier_group_name`,`e`.`acc_name` AS `acc_name`,`d`.`user_guid` AS `user_guid`,`c`.`supplier_guid` AS `supplier_guid`,`b`.`supplier_group_guid` AS `supplier_group_guid`,`e`.`acc_guid` AS `acc_guid` from ((((`set_supplier_user_relationship` `a` join `set_supplier_group` `b` on((`a`.`supplier_group_guid` = `b`.`supplier_group_guid`))) join `set_supplier` `c` on((`a`.`supplier_guid` = `c`.`supplier_guid`))) join `get_unique_user` `d` on((`a`.`user_guid` = `d`.`user_guid`))) join `acc` `e` on((`a`.`customer_guid` = `e`.`acc_guid`))) order by `d`.`user_id`,`c`.`supplier_name`) 