<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edi_setup extends CI_Controller {
    
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

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");

            $data = array(
                'get_supplier' => $get_supplier->result(),
                'get_acc' => $get_acc->result(),
            );

            $this->load->view('header');
            $this->load->view('edi/edi_subscriber', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function edit_main_tb()
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
            0=>'guid',
            1=>'acc_name',
            2=>'supplier_name',
            3=>'supplier_code',
            4=>'doc_type',
            5=>'issend',
            6=>'status',
            7=>'created_at',
            8=>'created_name',
            9=>'updated_at',
            10=>'updated_name',
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

        $sql = "SELECT a.`guid`,c.`acc_name`, b.`supplier_name`, a.`supplier_guid`, a.`customer_guid`, a.`supplier_code`, a.`doc_type`, IF(a.`issend` = '1','Send To','Receive From') AS `issend`, IF(a.`status` = '1','Completed','Not Complete') AS `status`, a.`created_at`, d.`user_name` AS `created_name`, a.`updated_at`, e.`user_name` AS `updated_name`  FROM lite_b2b.`set_supplier_group_edi`  a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid`= b.`supplier_guid` INNER JOIN lite_b2b.`acc` c ON a.`customer_guid` = c.`acc_guid` INNER JOIN lite_b2b.`set_user` d ON a.`created_by` = d.`user_guid` LEFT JOIN lite_b2b.`set_user` e ON a.`updated_by` = e.`user_guid` WHERE a.`status` IN ('0','1') GROUP BY a.`guid`";
        
        $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

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
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['supplier_code'] = $row->supplier_code;
            $nestedData['issend'] = $row->issend;
            $nestedData['status'] = $row->status;
            $nestedData['doc_type'] = $row->doc_type;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_name'] = $row->created_name;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_name'] = $row->updated_name;
            $nestedData['guid'] = $row->guid;

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

    public function fetch_sup_code()
    {
        $val_acc = $this->input->post('val_acc');
        $val_sup = $this->input->post('val_sup');

        $Code = $this->db->query("SELECT supplier_group_name FROM lite_b2b.`set_supplier_group` a WHERE a.`supplier_guid` = '$val_sup' AND a.`customer_guid` = '$val_acc' ");

        $data = array(
            'Code' => $Code->result(),
        );

        echo json_encode($data);
    }

    public function edi_sub_new()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $user_guid = $_SESSION['user_guid'];
            $new_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
            $new_retailer = $this->input->post("new_retailer");
            $new_supplier = $this->input->post("new_supplier");
            $new_doctype = $this->input->post("new_doctype");
            $new_code = $this->input->post("new_code");

            if(($new_retailer == '') || ($new_retailer == null) || ($new_retailer == 'null'))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Error Get Retailer Name.',
                );
                echo json_encode($data);
                exit();
            }

            if(($new_supplier == '') || ($new_supplier == null) || ($new_supplier == 'null'))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Error Get Supplier Name.',
                );
                echo json_encode($data);
                exit();
            }

            if(($new_doctype == '') || ($new_doctype == null) || ($new_doctype == 'null'))
            {
                $data = array(  
                    'para' => '1',
                    'msg' => 'Error Get Document Type.',
                );
                echo json_encode($data);
                exit();
            }

            $check_edi_tb = $this->db->query("SELECT guid FROM lite_b2b.set_supplier_group_edi a WHERE a.supplier_guid = '$new_supplier' AND a.customer_guid = '$new_retailer' AND a.supplier_code = '$new_code' AND a.doc_type = '$new_doctype' ");

            if($check_edi_tb->num_rows() >= 1)
            {
                $data = array(
                'para1' => 1,
                'msg' => 'Duplicate Data. Please check again.',
                );    
                echo json_encode($data);  
                exit(); 
            }

            $data = array(
                'guid' => $new_guid,
                'customer_guid' => $new_retailer,
                'supplier_guid' => $new_supplier,
                'supplier_code' => $new_code,
                'doc_type' => $new_doctype,
                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'created_by' => $user_guid,
            );
            $this->db->insert('lite_b2b.set_supplier_group_edi',$data);

            $error = $this->db->affected_rows();

            if($error > 0){

                $data = array(
                   'para1' => 0,
                   'msg' => 'Success. OK to Next Process.',
                   'link' => $new_guid,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 1,
                'msg' => 'Error to next process.',
                //'link' => 'Unknown URL.',

                );    
                echo json_encode($data);  
                exit(); 
            }


        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function tab_one()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $link = $_REQUEST['link'];

            $check_link = $this->db->query("SELECT `guid`,`status` FROM `lite_b2b`.`set_supplier_group_edi` WHERE guid = '$link'");

            if($check_link->num_rows() == 0)
            {
                echo '<script>alert("Invalid EDI Subscriber Data. Will redirect you to List Page.");window.location.href = "'.site_url('Edi_setup').'";</script>;';die;
            }

            $status = $check_link->row('status');
            $tab_2 = site_url('Edi_setup/tab_two?link='.$link);
            $tab_3 = site_url('Edi_setup/tab_three?link='.$link);
            $tab_summary = site_url('Edi_setup/final_summary?link='.$link);

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");

            $data = array(
                'link' => $link,
                'get_supplier' => $get_supplier->result(),
                'get_acc' => $get_acc->result(),
                'status' => $status,
                'tab_2' => $tab_2,
                'tab_3' => $tab_3,
                'tab_summary' => $tab_summary,
            );

            $this->load->view('header');
            $this->load->view('edi/edi_tab_one', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function tab_one_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $guid = $this->input->post("tab_guid");

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
            1=>'acc_name',
            2=>'supplier_name',
            3=>'supplier_code',
            4=>'doc_type',
            5=>'reg_no',
            6=>'acc_code',
            7=>'edi_status',
            8=>'created_at',
            9=>'created_name',
            10=>'updated_at',
            11=>'updated_name',
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

        $sql = "SELECT a.`guid`,a.`customer_guid`,a.`supplier_guid`,a.`doc_type`, IF(a.`status` = '1','Completed','Process') AS edi_status , a.`supplier_code`, c.`acc_name`, b.`supplier_name`, b.`reg_no`, b.`acc_code`, a.`issend`,a.`export_date_format`, a.`export_file_name_format`, a.`export_format`, a.`export_header`, a.`export_method`, a.`export_round_decimal`, a.`sftp_host`, a.`sftp_password`, a.`sftp_port`, a.`sftp_remote_path`, a.`sftp_username`, a.`local_file_path`, a.`created_at`, d.user_name AS created_name, a.`updated_at`, e.user_name AS updated_name FROM `lite_b2b`.`set_supplier_group_edi` a INNER JOIN lite_b2b.set_supplier b ON a.`supplier_guid` = b.`supplier_guid` INNER JOIN lite_b2b.`acc` c ON a.`customer_guid` = c.`acc_guid` INNER JOIN lite_b2b.set_user d ON a.`created_by` = d.`user_guid` LEFT JOIN lite_b2b.set_user e ON a.`updated_by` = e.`user_guid` WHERE a.guid = '$guid' GROUP BY a.guid";
        
        $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

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
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['reg_no'] = $row->reg_no;
            $nestedData['acc_code'] = $row->acc_code;
            $nestedData['edi_status'] = $row->edi_status;
            $nestedData['doc_type'] = $row->doc_type;
            $nestedData['supplier_code'] = $row->supplier_code;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_name'] = $row->created_name;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_name'] = $row->updated_name;

            $nestedData['issend'] = $row->issend;
            $nestedData['export_date_format'] = $row->export_date_format;
            $nestedData['export_file_name_format'] = $row->export_file_name_format;
            $nestedData['export_format'] = $row->export_format;
            $nestedData['export_header'] = $row->export_header;
            $nestedData['export_method'] = $row->export_method;
            $nestedData['export_round_decimal'] = $row->export_round_decimal;
            $nestedData['sftp_host'] = $row->sftp_host;
            $nestedData['sftp_password'] = $row->sftp_password;
            $nestedData['sftp_port'] = $row->sftp_port;
            $nestedData['sftp_remote_path'] = $row->sftp_remote_path;
            $nestedData['sftp_username'] = $row->sftp_username;

            $nestedData['local_file_path'] = $row->local_file_path;
            
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

    public function edit_information()
    {
        $tab_guid = $this->input->post("tab_guid");
        $edit_retailer = $this->input->post("edit_retailer");
        $edit_supplier = $this->input->post("edit_supplier");
        $edit_code = $this->input->post("edit_code");
        $edit_doctype = $this->input->post("edit_doctype");

        $check_data = $this->db->query("SELECT guid FROM lite_b2b.set_supplier_group_edi WHERE guid = '$tab_guid' ");

        if($check_data->num_rows() == 0)
        {
            $data = array(  
                'para' => '1',
                'msg' => 'Error Get Supplier EDI DATA.',
            );
            echo json_encode($data);
            exit();
        }

        $data = array(
            'customer_guid' => $edit_retailer,
            'supplier_guid' => $edit_supplier,
            'supplier_code' => $edit_code,
            'doc_type' => $edit_doctype,
            'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'updated_by' => $user_guid,
        );
         
        $this->db->where('guid', $tab_guid);
        $this->db->update('lite_b2b.set_supplier_group_edi', $data);

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Update Success.',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error to Update.',
            );    
            echo json_encode($data);  
            exit(); 
        }

    }

    public function tab_two()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $link = $_REQUEST['link'];
            $show = '0';
            $show_child_tb = '0';

            $check_link = $this->db->query("SELECT a.`guid`,a.`doc_type`,a.selected_field,a.selected_field_child,a.status FROM `lite_b2b`.`set_supplier_group_edi` a WHERE a.`guid` = '$link'");

            if($check_link->num_rows() == 0)
            {
                echo '<script>alert("Invalid EDI Subscriber Data. Will redirect you to List Page.");window.location.href = "'.site_url('Edi_setup').'";</script>;';die;
            }

            $selected_main = $check_link->row('selected_field');
            $selected_child = $check_link->row('selected_field_child');

            $status = $check_link->row('status');
            $tab_1 = site_url('Edi_setup/tab_one?link='.$link);
            $tab_3 = site_url('Edi_setup/tab_three?link='.$link);
            $tab_summary = site_url('Edi_setup/final_summary?link='.$link);

            if(($selected_main != '') && ($selected_main != 'null') && ($selected_main != null))
            {
                $show = '1';
            }

            if(($selected_child != '') && ($selected_child != 'null') && ($selected_child != null))
            {
                $show = '1';
                $show_child_tb = '1';
            }

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");

            $get_itemmaster = $this->db->query("SELECT a.`COLUMN_NAME`,a.`ORDINAL_POSITION` FROM information_schema.columns a WHERE a.`TABLE_NAME` = 'supplier_itemmaster_info' AND a.`TABLE_SCHEMA` = 'b2b_summary' AND a.`COLUMN_NAME` IN ('supplier_item_code','supplier_article_no','supplier_item_description','supplier_um') ORDER BY a.`ORDINAL_POSITION` ASC");

            $get_erp = $this->db->query("SELECT a.type,a.value,a.`description` FROM lite_b2b.b2b_selection_list a WHERE a.type = 'ERP' AND a.`active` = '1' ORDER BY a.seq ASC");

            $get_datatype = $this->db->query("SELECT a.type,a.value,a.`description` FROM lite_b2b.b2b_selection_list a WHERE a.type = 'DataType' AND a.`active` = '1' ORDER BY a.seq ASC");

            $data = array(
                'link' => $link,
                'get_supplier' => $get_supplier->result(),
                'get_acc' => $get_acc->result(),
                'get_itemmaster' => $get_itemmaster->result(),
                'link_doc' => $check_link->row('doc_type'),
                'show_button' => $show,
                'show_child_tb' => $show_child_tb,
                'status' => $status,
                'tab_1' => $tab_1,
                'tab_3' => $tab_3,
                'tab_summary' => $tab_summary,
                'get_erp' => $get_erp->result(),
                'get_datatype' => $get_datatype->result(),
            );

            $this->load->view('header');
            $this->load->view('edi/edi_tab_two', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function tab_two_main_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $guid = $this->input->post("tab_guid");
        $link_doc = $this->input->post("link_doc");
	    $erp_type = $this->input->post("erp_type");
        $erp_type = implode("','", $erp_type);

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
            0=>'doc_type',
            1=>'column_name',
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

	    if($erp_type == 'SAP')
        {
           $erp_filter = "AND a.erp_type = '$erp_type'";
        }
        else if($erp_type == 'JDE')
        {
           $erp_filter = "AND a.erp_type = '$erp_type'";
        }
	    else if($erp_type == '')
        { 
	       $erp_filter = "";
	    }
        else
        {
           $erp_filter = "AND a.erp_type IN ('$erp_type')";
        }

        $limit_query = " LIMIT " .$start. " , " .$length;

        $sql = "SELECT a.guid,a.`column_name`,a.`doc_table`,a.`doc_type`,a.`table_position`,a.`column_length`,a.`column_datatype`,a.`column_description`,a.`erp_type` FROM lite_b2b.`set_edi_column_info` a WHERE a.`doc_type` = '$link_doc' $erp_filter ORDER BY a.`table_position` ASC , a.`created_at` ASC ";

        $query = "SELECT * FROM ( ".$sql." ) aa ";

        $sql_1 = $this->db->query("SELECT a.guid,a.selected_field FROM lite_b2b.`set_supplier_group_edi` a WHERE a.guid = '$guid'");

        $selected_main = $sql_1->row('selected_field');

        $selected_main = json_decode($selected_main,true);
        
        //echo $this->db->last_query(); die;

        $data_1 = array();
        foreach($selected_main as $key => $value)
        {   
            //print_r($value); die;
            foreach($value as $row => $value_data)
            {   

                $nestedData_1['b2b_field'] = $value_data['b2b_field'];
                $nestedData_1['default_value'] = $value_data['default_value'];
                $nestedData_1['seq'] = $value_data['seq'];
                $nestedData_1['supplier_field'] = $value_data['supplier_field'];
                $nestedData_1['cross_ref'] = $value_data['cross_ref'];
                $nestedData_1['different_b2b_field'] = $value_data['different_b2b_field'];
                $nestedData_1['position'] = $value_data['position'];

                $data_1[] = $nestedData_1;
            }
        }

        $result = $this->db->query($query);
        //echo $this->db->last_query(); die;

        $data_2 = array();
        foreach($result->result() as $row1 )
        {   
            $nestedData_2['guid'] = $row1->guid;
            $nestedData_2['column_name'] = $row1->column_name;
            $nestedData_2['column_length'] = $row1->column_length;
            $nestedData_2['column_datatype'] = $row1->column_datatype;
            $nestedData_2['column_description'] = $row1->column_description;
            $nestedData_2['doc_table'] = $row1->doc_table;
            $nestedData_2['doc_type'] = $row1->doc_type;
            $nestedData_2['table_position'] = $row1->table_position;
            $nestedData_2['erp_type'] = $row1->erp_type;

            $data_2[] = $nestedData_2;

        }
        //print_r($data_1); echo ''; die;
        //print_r($data_2); die;
       
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

        $final = $data_2; // default get all values from first array
        foreach ($data_1 as $value) { // loop through second array to match
            $flag = 0;
            foreach ($final as $key => $data) {
                // Check for date, size and type
                // print_r($value); die;
                // print_r($data); die;
                if ($data['column_name']===$value['b2b_field'] && $data['table_position']===$value['position'] ) {
                    $final[$key]['column_data'] = $value['supplier_field'];
                    $final[$key]['default_data'] = $value['default_value'];
                    $final[$key]['seq_data'] = $value['seq'];
                    $final[$key]['different_b2b_field_data'] = $value['different_b2b_field'];
                    $final[$key]['cross_ref_data'] = $value['cross_ref'];
                    $flag = 1;
                    break;
                }
                // else
                // {
                //     continue;
                // }
            }
            if ($flag === 0) { // If similar not found, then add new one
                //array_push($final, $value);
            }
        }

        //print_r($final); die;
        //print_r($data); die;
        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $final,
        );

        echo json_encode($output);
    }

    public function tab_two_child_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $guid = $this->input->post("tab_guid");
        $link_doc = $this->input->post("link_doc");
	    $erp_type = $this->input->post("erp_type");
        $erp_type = implode("','", $erp_type);
        
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
            0=>'doc_type',
            1=>'column_name',
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

        if($erp_type == 'SAP')
        {
           $erp_filter = "AND a.erp_type = '$erp_type'";
        }
        else if($erp_type == 'JDE')
        {
           $erp_filter = "AND a.erp_type = '$erp_type'";
        }
        else if($erp_type == '')
        { 
           $erp_filter = "";
        }
        else
        {
           $erp_filter = "AND a.erp_type IN ('$erp_type')";
        }

        $limit_query = " LIMIT " .$start. " , " .$length;

        $sql = "SELECT a.guid,a.`column_name`,a.`doc_table`,a.`doc_type`,a.`table_position`,a.`column_length`,a.`column_datatype`,a.`column_description`,a.`erp_type` FROM lite_b2b.`set_edi_column_info` a WHERE a.`doc_type` = '$link_doc' $erp_filter ORDER BY a.`table_position` DESC , a.`created_at` ASC";
        
        $query = "SELECT * FROM ( ".$sql." ) aa ";

        $sql_1 = $this->db->query("SELECT a.guid,a.selected_field_child FROM lite_b2b.`set_supplier_group_edi` a WHERE a.guid = '$guid'");

        $selected_child = $sql_1->row('selected_field_child');

        $selected_child = json_decode($selected_child,true);
        
        //echo $this->db->last_query(); die;

        $data_1 = array();
        foreach($selected_child as $key => $value)
        {   
            //print_r($value); die;
            foreach($value as $row => $value_data)
            {   

                $nestedData_1['b2b_field'] = $value_data['b2b_field'];
                $nestedData_1['default_value'] = $value_data['default_value'];
                $nestedData_1['seq'] = $value_data['seq'];
                $nestedData_1['supplier_field'] = $value_data['supplier_field'];
                $nestedData_1['cross_ref'] = $value_data['cross_ref'];
                $nestedData_1['different_b2b_field'] = $value_data['different_b2b_field'];
                $nestedData_1['position'] = $value_data['position'];

                $data_1[] = $nestedData_1;
            }
        }
        //print_r($data_1); die;
        $result = $this->db->query($query);

        $data_2 = array();
        foreach($result->result() as $row1 )
        {   
            $nestedData_2['guid'] = $row1->guid;
            $nestedData_2['column_name'] = $row1->column_name;
            $nestedData_2['column_datatype'] = $row1->column_datatype;
            $nestedData_2['column_length'] = $row1->column_length;
            $nestedData_2['column_description'] = $row1->column_description;
            $nestedData_2['doc_table'] = $row1->doc_table;
            $nestedData_2['doc_type'] = $row1->doc_type;
            $nestedData_2['table_position'] = $row1->table_position;
            $nestedData_2['erp_type'] = $row1->erp_type;

            $data_2[] = $nestedData_2;

        }

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

        $final = $data_2; // default get all values from first array
        foreach ($data_1 as $value) { // loop through second array to match
            $flag = 0;
            foreach ($final as $key => $data) {
                // Check for date, size and type
                if ($data['column_name']===$value['b2b_field'] && $data['table_position']===$value['position'] ) {
                    $final[$key]['column_data'] = $value['supplier_field'];
                    $final[$key]['default_data'] = $value['default_value'];
                    $final[$key]['seq_data'] = $value['seq'];
                    $final[$key]['different_b2b_field_data'] = $value['different_b2b_field'];
                    $final[$key]['cross_ref_data'] = $value['cross_ref'];
                    $flag = 1;
                    break;
                }
            }
            if ($flag === 0) { // If similar not found, then add new one
                //array_push($final, $value);
            }
        }

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $final
        );

        echo json_encode($output);
    }
    
    public function add_extra_column()
    {
        $user_guid = $_SESSION['user_guid'];
	    $link_doc = $this->input->post("link_doc");
        //$tb_position= $this->input->post("tb_position");
        $add_column = $this->input->post("add_column");
        $add_position = '1';
        $column_data_type = $this->input->post("column_data_type");
        $column_length = $this->input->post("column_length");
        $column_description = $this->input->post("column_description");
        $add_erp_type = $this->input->post("add_erp_type");
        //print_r($add_erp_type); die;

     	$check_table = $this->db->query("SELECT a.* FROM `lite_b2b`.`set_edi_column_info` a WHERE a.`doc_type` = '$link_doc' AND a.`table_position` = '$add_position' AND a.`column_name` = '$add_column'");

        if($check_table->num_rows() > 0)
        {
            $data = array(  
                'para' => '1',
                'msg' => 'Duplicate Column Name.',
            );
            echo json_encode($data);
            exit();
        }
        
        $get_table = $this->db->query("SELECT a.`doc_table` FROM `lite_b2b`.`set_edi_column_info` a WHERE a.`doc_type` = '$link_doc' AND a.`table_position` = '$add_position' GROUP BY a.`doc_table` ");

        $table_name = $get_table->row('doc_table');

        if(($add_erp_type != '')|| ($add_erp_type != 'null')|| ($add_erp_type != null))
        {
            foreach($add_erp_type as $row)
            {
                $new_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');

                $data = array(
                    'guid' => $new_guid,
                    'column_name' => $add_column,
                    'column_datatype' => $column_data_type,
                    'column_length' => $column_length,
                    'column_description' => $column_description,
                    'erp_type' => $row,
                    'doc_type' => $link_doc,
                    'doc_table' => $table_name,
                    'table_position' => $add_position,
                    'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                    'created_by' => $user_guid,
                );
                $this->db->insert('lite_b2b.set_edi_column_info',$data);
            }
        }
        
        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Success to add new column.',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error to add new column.',
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function selected_process()
    {
        $user_guid = $_SESSION['user_guid'];
        $tab_guid = $this->input->post('tab_guid');
        $main_details = $this->input->post('main_details');
        $child_details = $this->input->post('child_details');

        if(is_null($main_details))
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Error. Empty Selected Main Field Data.',
            );    
            echo json_encode($data);  
            exit(); 
        }

        if(($main_details != '') || ($main_details != 'null') || ($main_details != null))
        {
            $post_main = array(
              'selected_field' => $main_details
            );

            $main = json_encode($post_main);
        }

        if(!is_null($child_details))
        {
            
            $post_child = array(
                'selected_field_child' => $child_details
                
            );
            
            $child = json_encode($post_child);
        }

        //print_r($main); echo '<br>';
        //print_r($child); echo '<br>'; die;

        $data = array(
            'selected_field' => $main,
            'selected_field_child' => $child,
            'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'updated_by' => $user_guid,
        );
         
        $this->db->where('guid', $tab_guid);
        $this->db->update('lite_b2b.set_supplier_group_edi', $data);

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Success save record(s).',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error to next process.',
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function tab_three()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $link = $_REQUEST['link'];

            $check_link = $this->db->query("SELECT `guid`,doc_type,status FROM `lite_b2b`.`set_supplier_group_edi` WHERE guid = '$link'");

            if($check_link->num_rows() == 0)
            {
                echo '<script>alert("Invalid EDI Subscriber Data. Will redirect you to List Page.");window.location.href = "'.site_url('Edi_setup').'";</script>;';die;
            }

            $doc_type = $check_link->row('doc_type');
            $status = $check_link->row('status');
            $tab_1 = site_url('Edi_setup/tab_one?link='.$link);
            $tab_2 = site_url('Edi_setup/tab_two?link='.$link);
            $tab_summary = site_url('Edi_setup/final_summary?link='.$link);

            $get_column_info = $this->db->query("SELECT a.column_name FROM lite_b2b.set_edi_column_info a WHERE a.doc_type IN ('$doc_type','file') GROUP BY a.column_name ");


            $data = array(
                'link' => $link,
                'get_column_info' => $get_column_info->result(),
                'status' => $status,
                'doc_type' => $doc_type,
                'tab_1' => $tab_1,
                'tab_2' => $tab_2,
                'tab_summary' => $tab_summary,
            );

            $this->load->view('header');
            $this->load->view('edi/edi_tab_three', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function edi_method_info()
    {
        $tab_guid = $this->input->post('tab_guid');

        $get_edi_data = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group_edi WHERE guid = '$tab_guid' ");

        if($get_edi_data->num_rows() == 0)
        {
            echo '<script>alert("Invalid EDI Subscriber Data. Will redirect you to List Page.");window.location.href = "'.site_url('Edi_setup').'";</script>;';die;
        }

        $data = array(
            'doc_type' => $get_edi_data->row('doc_type'),
            'supplier_guid' => $get_edi_data->row('supplier_guid'),
            'supplier_code' => $get_edi_data->row('supplier_code'),
            'customer_guid' => $get_edi_data->row('customer_guid'),
            'selected_field' => $get_edi_data->row('selected_field'),
            'selected_field_child' => $get_edi_data->row('selected_field_child'),
            'issend' => $get_edi_data->row('issend'),
            'export_method' => $get_edi_data->row('export_method'),
            'export_format' => $get_edi_data->row('export_format'),
            'export_round_decimal' => $get_edi_data->row('export_round_decimal'),
            'export_date_format' => $get_edi_data->row('export_date_format'),
            'export_file_name_format' => $get_edi_data->row('export_file_name_format'),
            'export_add_extra_name' => $get_edi_data->row('export_add_extra_name'),
            'status' => $get_edi_data->row('status'),
            'export_header' => $get_edi_data->row('export_header'),
            'sftp_host' => $get_edi_data->row('sftp_host'),
            'sftp_port' => $get_edi_data->row('sftp_port'),
            'sftp_username' => $get_edi_data->row('sftp_username'),
            'sftp_password' => $get_edi_data->row('sftp_password'),
            'sftp_remote_path' => $get_edi_data->row('sftp_remote_path'),
            'created_at' => $get_edi_data->row('created_at'),
            'created_by' => $get_edi_data->row('created_by'),
            'updated_at' => $get_edi_data->row('updated_at'),
            'updated_by' => $get_edi_data->row('updated_by'),
            'local_file_path' => $get_edi_data->row('local_file_path'),
            'split_batch' => $get_edi_data->row('split_batch'),
        );

        echo json_encode($data);
    }

    public function update_method_info()
    {
        $user_guid = $_SESSION['user_guid'];
        $tab_guid = $this->input->post('tab_guid');
        $export_file_name_format = $this->input->post('export_file_name_format');
        $export_date_format = $this->input->post('export_date_format');
        $export_round_decimal = $this->input->post('export_round_decimal');
        $export_method = $this->input->post('export_method');
        $export_format = $this->input->post('export_format');
        $sftp_remote_path = $this->input->post('sftp_remote_path');
        $sftp_port = $this->input->post('sftp_port');
        $sftp_password = $this->input->post('sftp_password');
        $sftp_username = $this->input->post('sftp_username');
        $sftp_host = $this->input->post('sftp_host');
        $issend = $this->input->post('issend');
        $export_header = $this->input->post('export_header');
        $export_add_extra_name = $this->input->post('export_add_extra_name');
        $local_file_path = $this->input->post('local_file_path');
        $split_batch = $this->input->post('export_split_batch');

        $check_data = $this->db->query("SELECT `guid`,supplier_guid,customer_guid,doc_type FROM lite_b2b.set_supplier_group_edi WHERE `guid` = '$tab_guid' ");

        if($check_data->num_rows() == 0)
        {
            $data = array(
                'para1' => 1,
                'msg' => 'No Data Found.',
            );    
            echo json_encode($data);  
            exit(); 
        }

        $supplier_guid = $check_data->row('supplier_guid');
        $customer_guid = $check_data->row('customer_guid');
        $doc_type = $check_data->row('doc_type');

        //$export_file_name_format = implode(",@",$export_file_name_format);
        //$export_file_name_format = "@".$export_file_name_format."";
        //print_r($export_file_name_format); echo '<br>'; die;
        //print_r($child); echo '<br>'; die;

        $data = array(
            'split_batch' => $split_batch,
            'export_file_name_format' => $export_file_name_format,
            'export_date_format' => $export_date_format,
            'export_round_decimal' => $export_round_decimal,
            'export_method' => $export_method,
            'export_format' => $export_format,
            'export_add_extra_name' => $export_add_extra_name,
            'sftp_remote_path' => $sftp_remote_path,
            'sftp_port' => $sftp_port,
            'sftp_password' => $sftp_password,
            'sftp_username' => $sftp_username,
            'sftp_host' => $sftp_host,
            'issend' => $issend,
            'export_header' => $export_header,
            'local_file_path' => $local_file_path,
            'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'updated_by' => $user_guid,
        );
        //print_r($data); die;
        $this->db->where('guid', $tab_guid);
        $this->db->update('lite_b2b.set_supplier_group_edi', $data);

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Update Successfully.',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error to update.',
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function final_summary()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {

            $link = $_REQUEST['link'];

            $check_link = $this->db->query("SELECT `guid`,doc_type,status FROM `lite_b2b`.`set_supplier_group_edi` WHERE guid = '$link'");

            if($check_link->num_rows() == 0)
            {
                echo '<script>alert("Invalid EDI Subscriber Data. Will redirect you to List Page.");window.location.href = "'.site_url('Edi_setup').'";</script>;';die;
            }

            $doc_type = $check_link->row('doc_type');
            $status = $check_link->row('status');
            $tab_1 = site_url('Edi_setup/tab_one?link='.$link);
            $tab_2 = site_url('Edi_setup/tab_two?link='.$link);
            $tab_3 = site_url('Edi_setup/tab_three?link='.$link);

            //$get_column_info = $this->db->query("SELECT a.column_name FROM lite_b2b.set_edi_column_info a WHERE a.doc_type IN ('$doc_type','file') GROUP BY a.column_name ");


            $data = array(
                'link' => $link,
                'status' => $status,
                'doc_type' => $doc_type,
                'tab_1' => $tab_1,
                'tab_2' => $tab_2,
                'tab_3' => $tab_3,
                //'get_column_info' => $get_column_info->result(),
            );

            $this->load->view('header');
            $this->load->view('edi/edi_tab_summary', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function final_tab_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $guid = $this->input->post("tab_guid");

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
            // 0=>'guid',
            // 1=>'acc_name',
            // 2=>'supplier_name',
            // 3=>'supplier_code',
            // 4=>'doc_type',
            // 5=>'reg_no',
            // 6=>'acc_code',
            // 7=>'edi_status',
            // 8=>'created_at',
            // 9=>'created_name',
            // 10=>'updated_at',
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

        $sql = "SELECT a.`issend`,a.`export_date_format`, a.`export_file_name_format`, a.`export_format`, a.`export_header`, a.`export_method`, a.`export_round_decimal`, a.`export_add_extra_name`, a.`sftp_host`, a.`sftp_password`, a.`sftp_port`, a.`sftp_remote_path`, a.`sftp_username`, a.`created_at`, d.user_name AS created_name, a.`updated_at`, e.user_name AS updated_name, a.local_file_path,a.split_batch FROM `lite_b2b`.`set_supplier_group_edi` a INNER JOIN lite_b2b.set_supplier b ON a.`supplier_guid` = b.`supplier_guid` INNER JOIN lite_b2b.`acc` c ON a.`customer_guid` = c.`acc_guid` INNER JOIN lite_b2b.set_user d ON a.`created_by` = d.`user_guid` LEFT JOIN lite_b2b.set_user e ON a.`updated_by` = e.`user_guid` WHERE a.guid = '$guid' GROUP BY a.guid ";
        
        $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

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
            // $nestedData['guid'] = $row->guid;
            // $nestedData['customer_guid'] = $row->customer_guid;
            // $nestedData['supplier_guid'] = $row->supplier_guid;
            // $nestedData['acc_name'] = $row->acc_name;
            // $nestedData['supplier_name'] = $row->supplier_name;
            // $nestedData['reg_no'] = $row->reg_no;
            // $nestedData['acc_code'] = $row->acc_code;
            // $nestedData['edi_status'] = $row->edi_status;
            // $nestedData['doc_type'] = $row->doc_type;
            // $nestedData['supplier_code'] = $row->supplier_code;
            // $nestedData['created_at'] = $row->created_at;
            // $nestedData['created_name'] = $row->created_name;
            // $nestedData['updated_at'] = $row->updated_at;
            // $nestedData['updated_name'] = $row->updated_name;
            $nestedData['export_add_extra_name'] = $row->export_add_extra_name;
            $nestedData['export_date_format'] = $row->export_date_format;
            $nestedData['export_file_name_format'] = $row->export_file_name_format;
            $nestedData['export_format'] = $row->export_format;
            $nestedData['export_header'] = $row->export_header;
            $nestedData['export_method'] = $row->export_method;
            $nestedData['export_round_decimal'] = $row->export_round_decimal;
            $nestedData['sftp_host'] = $row->sftp_host;
            $nestedData['sftp_password'] = $row->sftp_password;
            $nestedData['sftp_port'] = $row->sftp_port;
            $nestedData['sftp_remote_path'] = $row->sftp_remote_path;
            $nestedData['sftp_username'] = $row->sftp_username;
            $nestedData['issend'] = $row->issend;
            $nestedData['local_file_path'] = $row->local_file_path;
            $nestedData['split_batch'] = $row->split_batch;

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

    public function selected_main_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $guid = $this->input->post("tab_guid");

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
            0=>'guid'
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

        $sql = "SELECT a.guid,a.selected_field FROM lite_b2b.`set_supplier_group_edi` a WHERE a.guid = '$guid'";

        $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query.$order_query.$limit_query;
        
        $result = $this->db->query($query);

        $selected_main = $result->row('selected_field');

        $selected_main = json_decode($selected_main,true);
        
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
        foreach($selected_main as $key => $value)
        {   
            //print_r($value); die;
            foreach($value as $row => $value_data)
            {   

                $nestedData['b2b_field'] = $value_data['b2b_field'];
                $nestedData['default_value'] = $value_data['default_value'];
                $nestedData['seq'] = $value_data['seq'];
                $nestedData['supplier_field'] = $value_data['supplier_field'];
                $nestedData['cross_ref'] = $value_data['cross_ref'];
                $nestedData['different_b2b_field'] = $value_data['different_b2b_field'];
                $nestedData['position'] = $value_data['position'];

                $data[] = $nestedData;
            }
        }
        
        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);
    }

    public function selected_child_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $guid = $this->input->post("tab_guid");

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
            0=>'guid'
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

        $sql = "SELECT a.guid,a.selected_field_child FROM lite_b2b.`set_supplier_group_edi` a WHERE a.guid = '$guid'";

        $query = "SELECT * FROM ( ".$sql." ) aa ".$like_first_query.$like_second_query.$order_query.$limit_query;
        
        $result = $this->db->query($query);

        $selected_child = $result->row('selected_field_child');

        $selected_child = json_decode($selected_child,true);
        
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
        foreach($selected_child as $key => $value)
        {   
            //print_r($value); die;
            foreach($value as $row => $value_data)
            {   

                $nestedData['b2b_field'] = $value_data['b2b_field'];
                $nestedData['default_value'] = $value_data['default_value'];
                $nestedData['seq'] = $value_data['seq'];
                $nestedData['supplier_field'] = $value_data['supplier_field'];
                $nestedData['cross_ref'] = $value_data['cross_ref'];
                $nestedData['different_b2b_field'] = $value_data['different_b2b_field'];
                $nestedData['position'] = $value_data['position'];

                $data[] = $nestedData;
            }
        }
        
        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);
    }

    public function done_setup_edi()
    {
        $user_guid = $_SESSION['user_guid'];
        $tab_guid = $this->input->post('tab_guid');
        $get_edi_data = $this->db->query("SELECT supplier_guid FROM lite_b2b.set_supplier_group_edi WHERE guid = '$tab_guid' LIMIT 1");
        //echo $this->db->last_query();die;
        $user_name = $this->db->query("SELECT a.user_name,a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

        $supplier_guid = $get_edi_data->row('supplier_guid');

        if(($supplier_guid == '') || ($supplier_guid == 'null') || ($supplier_guid == null))
        {
            $data = array(
               'para1' => 1,
               'msg' => 'Invalid Supplier GUID.',
            );    
            echo json_encode($data);   
            exit();
        }

        $data = array(
            'status' => 1,
            'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'updated_by' => $user_guid,
        );
        //print_r($data); die;
        $this->db->where('guid', $tab_guid);
        $this->db->update('lite_b2b.set_supplier_group_edi', $data);


        $data_1 = array(
            'subscribe_edi' => 1,
            'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'updated_by' => $user_name,
        );
        //print_r($data); die;
        $this->db->where('supplier_guid', $supplier_guid);
        $this->db->update('lite_b2b.set_supplier', $data_1);

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Completed Setup.',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error to complete.',
            );    
            echo json_encode($data);  
            exit(); 
        }
    }
}
?>

