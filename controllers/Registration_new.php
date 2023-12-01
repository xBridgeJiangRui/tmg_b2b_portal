<?php
defined('BASEPATH') or exit('No direct script access allowed');

class registration_new extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->load->database();
    $this->load->library('pagination');
    $this->load->library('form_validation');
    $this->load->library('session');
    $this->load->model('Registration_model');
    $this->load->model('Send_email_model');
    $this->load->library('datatables');
    $this->load->library('Panda_PHPMailer');
  }

  public function index()
  {
    if ($this->session->userdata('loginuser') == true) {
      $session_id = $_REQUEST['session_id'];
      $supplier_group_guid = $this->session->userdata('supplier_group_guid');
      $name = $this->db->query("SELECT * from acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('acc_guid');
      $supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='" . $_SESSION['customer_guid'] . "'  ")->row('supplier_guid');

      $sessiondata = array(
        'name' => $name,
        'supplier' => $supplier,
        'session_id' => $session_id,

      );
      //print_r($sessiondata);die;
      $this->session->set_userdata($sessiondata);
      $this->load->view('register/register.php', $sessiondata);
    } else {
      redirect('#');
    }
  }

  //add vendor site..
  public function register_vendor()
  {
    if ($this->session->userdata('loginuser') == true) {
      $customer_guid = $_SESSION['customer_guid'];
      //print_r($customer_guid); die;
      //$supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->result();
      $supplier = $this->db->query("SELECT a.* FROM set_supplier a ORDER BY a.supplier_name ASC")->result();
      $retailer = $this->db->query("SELECT DISTINCT c.acc_name FROM  acc c INNER JOIN set_supplier_group a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='" . $_SESSION['customer_guid'] . "'  ")->row('acc_name');
      $get_new_status = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`, COUNT(b.`acc_name`) AS numbering,IF(b.acc_guid = '$customer_guid' ,'1','2') AS sort FROM lite_b2b.register_add_user_main a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.acc_guid WHERE a.form_status = 'New' GROUP BY a.`customer_guid` ORDER BY sort ASC , b.acc_name ASC");
      $data = array(
        'supplier' => $supplier,
        'retailer' => $retailer,
        'get_new_status' => $get_new_status,
        'customer_guid' => $customer_guid,
      );

      $this->load->view('header');
      $this->load->view('register/register_vendor', $data);
      $this->load->view('footer');
    } else {
      redirect('#');
    }
  }

  //add vendor site..
  public function register_vendor_table()
  {
    ini_set('memory_limit', -1);
    $session_supcode = $_SESSION['query_supcode'];
    if ($session_supcode == '') {
      $query1 = "AND f.`supplier_group_name` = '' ";
    } else {
      $query1 = "AND f.`supplier_group_name` IN ($session_supcode)";
    }

    if ($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') {
      $columns = array(
        0 => 'register_guid',
        1 => 'register_guid',
        2 => 'register_no',
        3 => 'supplier_name',
        4 => 'acc_name',
        5 => 'memo_type',
        6 => 'comp_email',
        7 => 'acc_no',
        8 => 'cnt',
        9 => 'form_status',
        10 => 'create_at',
        11 => 'create_by',
        12 => 'update_at',
        13 => 'update_by',
      );
    } else {
      $columns = array(
        0 => 'register_guid',
        1 => 'register_guid',
        2 => 'register_no',
        3 => 'supplier_name',
        4 => 'acc_name',
        5 => 'memo_type',
        6 => 'comp_email',
        7 => 'acc_no',
        8 => 'cnt',
        9 => 'form_status',
        10 => 'create_at',
        11 => 'create_by',
        12 => 'update_at',
        13 => 'update_by',
      );
    }

    $user_guid = $_SESSION['user_guid'];
    $user_group = $_SESSION['user_group_name'];
    $limit = $this->input->post('length');
    $start = $this->input->post('start');
    $order = $this->input->post('order');
    $dir = "";
    //$totalData = $this->Registration_model->register()->row('numrow');
    $customer_guid = $_SESSION['customer_guid'];
    $totalData = $this->db->query("SELECT COUNT(*) as numrow FROM lite_b2b.register_add_user_main WHERE customer_guid = '$customer_guid' ")->row('numrow');
    $totalFiltered = $totalData;

    $order_query = "";

    if (!empty($order)) {
      foreach ($order as $o) {
        $col = $o['column'];
        $dir = $o['dir'];

        $order_query .= $columns[$col] . " " . $dir . ",";
      }
    }
    $dir = '';
    $order_query = rtrim($order_query, ',');

    if ($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') {
      $query = "
          SELECT 
          aa.`supplier_info_guid`,
          aa.`register_no`,
          aa.`supplier_name`,
          aa.`acc_name`,
          aa.`comp_email`,
          aa.`acc_no`,
          aa.`create_at`,
          aa.`create_by`,
          aa.`update_at`,
          aa.`update_by`,
          aa.`register_guid`,
          aa.`cnt`,
          aa.`part_cnt`,
          aa.`customer_guid`,
          aa.`form_status`,
          aa.`memo_type`,
          aa.`form_merge`,
          aa.`comp_no`
        FROM
          (SELECT 
            c.`supplier_name`,
            e.`supplier_info_guid`,
            a.*,
            d.* 
          FROM
            register_add_user_main a 
            INNER JOIN acc b 
              ON b.acc_guid = a.`customer_guid` 
            LEFT JOIN set_supplier c 
              ON c.supplier_guid = a.`supplier_guid` 
            LEFT JOIN set_supplier_info e 
              ON a.`register_guid` = e.register_guid 
            LEFT JOIN 
              (SELECT DISTINCT 
                register_guid AS register_id,
                COUNT(`ven_name`) AS cnt, 
                COUNT(`part_name`) AS part_cnt
              FROM
                register_add_user_child 
              GROUP BY register_guid) d 
              ON a.`register_guid` = d.register_id
              WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "') aa 
              WHERE aa.customer_guid = '" . $_SESSION['customer_guid'] . "' ";
    } else {
      $query = "
          SELECT 
          aa.`supplier_info_guid`,
          aa.`register_no`,
          aa.`supplier_name`,
          aa.`acc_name`,
          aa.`comp_email`,
          aa.`acc_no`,
          aa.`create_at`,
          aa.`create_by`,
          aa.`update_at`,
          aa.`update_by`,
          aa.`register_guid`,
          aa.`cnt`,
          aa.`part_cnt`,
          aa.`customer_guid`,
          aa.`form_status`,
          aa.`memo_type`,
          aa.`form_merge`,
          aa.`comp_no`
        FROM
          (SELECT 
            c.`supplier_name`,
            e.`supplier_info_guid`,
            a.*,
            d.* 
          FROM
            register_add_user_main a 
            INNER JOIN acc b 
              ON b.acc_guid = a.`customer_guid` 
            LEFT JOIN set_supplier c 
              ON c.supplier_guid = a.`supplier_guid` 
            LEFT JOIN set_supplier_info e 
              ON a.`register_guid` = e.register_guid
            INNER JOIN lite_b2b.`set_supplier_group` f
            ON a.supplier_guid = f.`supplier_guid`
            AND f.`customer_guid` = '" . $_SESSION['customer_guid'] . "'
            $query1
            LEFT JOIN 
              (SELECT DISTINCT 
                register_guid AS register_id,
                COUNT(`ven_name`) AS cnt, 
                COUNT(`part_name`) AS part_cnt
              FROM
                register_add_user_child 
              GROUP BY register_guid) d 
              ON a.`register_guid` = d.register_id
            WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "') aa 
              WHERE aa.customer_guid = '" . $_SESSION['customer_guid'] . "' 
              GROUP BY aa.register_guid";
    }

    $totalData = $this->db->query($query)->num_rows();
    $totalFiltered = $totalData;

    if (empty($this->input->post('search')['value'])) {
      $posts = $this->Registration_model->allposts($query, $limit, $start, $order_query, $dir);
      //echo $this->db->last_query(); die;
    } else {
      $search = $this->input->post('search')['value'];

      $posts =  $this->Registration_model->posts_search($query, $limit, $start, $search, $order_query, $dir);

      $totalFiltered = $this->Registration_model->posts_search_count($query, $search);
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $post) {
        $nestedData['register_no'] = $post->register_no;
        $nestedData['supplier_name'] = $post->supplier_name;
        $nestedData['acc_name'] = $post->acc_name;
        $nestedData['comp_email'] = $post->comp_email;
        $nestedData['create_at'] = $post->create_at;
        $nestedData['create_by'] = $post->create_by;
        $nestedData['update_at'] = $post->update_at;
        $nestedData['update_by'] = $post->update_by;
        $nestedData['cnt'] = $post->cnt;
        //$nestedData['part_cnt'] = $post->part_cnt;
        $nestedData['acc_no'] = $post->acc_no;
        $nestedData['form_status'] = $post->form_status;
        $nestedData['memo_type'] = $post->memo_type;
        $nestedData['register_guid'] = $post->register_guid;

        if ($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') {
          if ($post->form_status == '') {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_forms_vendor?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a register_guid=' . $post->register_guid . ' id="send_btn" title="SEND" class="btn btn-xs btn-warning" type="button"  form_type ="vendor" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" form_status="' . $post->form_status . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_type . '" form_merge="' . $post->form_merge . '" style="margin-top:5px;"><i class="fa fa-edit"></i></a><a class="btn btn-xs btn-danger" type="button" id="btn_delete_form" title="DELETE" register_guid="' . $post->register_guid . '" style="margin-top:5px;margin-left:5px;"><i class="glyphicon glyphicon-remove"></i></a>';
          } else if (($post->form_status == 'Advance')) {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_forms_vendor?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a register_guid=' . $post->register_guid . ' id="send_btn" title="SEND" class="btn btn-xs btn-warning" type="button"  form_type ="vendor" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" form_status="' . $post->form_status . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_type . '" form_merge="' . $post->form_merge . '"  style="margin-top:5px;"><i class="fa fa-edit"></i></a><a class="btn btn-xs btn-danger" type="button" id="btn_delete_form" title="DELETE" register_guid="' . $post->register_guid . '" style="margin-top:5px;margin-left:5px;"><i class="glyphicon glyphicon-remove"></i></a>';
          } else if (($post->form_status == 'Send') || ($post->form_status == 'Save-Progress') ) {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_forms_vendor?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a register_guid=' . $post->register_guid . ' id="send_btn" title="SEND" class="btn btn-xs btn-warning" type="button"  form_type ="vendor" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" form_status="' . $post->form_status . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_type . '" form_merge="' . $post->form_merge . '"  style="margin-top:5px;"><i class="fa fa-edit"></i></a>';
          } else {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_forms_vendor?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" form_status="' . $post->form_status . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_type . '" form_merge="' . $post->form_merge . '"  style="margin-left:5px;"><i class="fa fa-edit"></i></a>';
          }
        } else {
          $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_forms_vendor?register_guid=' . $post->register_guid . '"><i class="fa fa-eye"></i></a>';
        }

        $data[] = $nestedData;
      }
    }

    $json_data = array(
      "draw"            => intval($this->input->post('draw')),
      "recordsTotal"    => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data"            => $data
    );

    echo json_encode($json_data);
  }

  //add vendor site..
  public function transaction_vendor()
  {
    $comp_name = $this->input->post('comp_name');
    $memo_type = $this->input->post('memo_type');
    $form_merge = $this->input->post('form_merge');
    $register_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_guid")->row('register_guid');
    $register_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_c_guid")->row('register_c_guid');
    $supplier_query = $this->db->query("SELECT a.* FROM set_supplier a WHERE supplier_guid = '$comp_name' ORDER BY a.supplier_name ASC");
    $supplier_guid = $supplier_query->row('supplier_guid');
    $supplier_name = $supplier_query->row('supplier_name');
    $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_info_guid")->row('supplier_info_guid');
    $active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='" . $_SESSION['customer_guid'] . "'")->row('isactive');
    $supplier = $this->db->query("SELECT a.*,b.* FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='" . $_SESSION['customer_guid'] . "'  ")->row('supplier_guid');
    // $retailer = $this->db->query("SELECT acc_name FROM  acc c INNER JOIN register_new a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('acc_name');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $session_id = $this->db->query("SELECT supplier_group_guid FROM set_supplier_group a WHERE a.`supplier_guid`= '$supplier_guid'")->row('supplier_group_guid');
    //a.`customer_guid`= '".$_SESSION['customer_guid']." ' AND
    //$re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM `register_add_user_main`  WHERE  SUBSTRING(register_no, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('re_no');
    $todaydate = date('Ymd');
    $todaydate2 = substr($todaydate, 2);
    $re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM lite_b2b.`register_add_user_main`  WHERE  SUBSTRING(register_no, - 10, 6) = '$todaydate2' ")->row('re_no');
    $register_no = $this->db->query("SELECT concat( '$todaydate2', '$re_no' ) as refno")->row('refno');
    $comp_no = $this->input->post('comp_no');
    $acc_name = $this->input->post('acc_name');
    $acc_no = $this->input->post('acc_no');

    $comp_email = $this->input->post('comp_email');

    $create_at = $this->db->query("SELECT now() as now")->row('now');
    $update_at = $this->db->query("SELECT now() as now")->row('now');

    $acc_no = implode(",", $acc_no);
    $acc_no = "" . $acc_no . "";

    $data = array(
      'register_guid' => $register_guid,
      'customer_guid' => $_SESSION['customer_guid'],
      'supplier_guid' => $supplier_guid,
      'create_at' => $create_at,
      'create_by' => $user_id,
      'update_at' => $update_at,
      'update_by' => $user_id,
      'isactive' => $active,
      'comp_email' => $comp_email,
      'register_no' => $register_no,
      'comp_name' => $supplier_name,
      'comp_no' => $comp_no,
      'acc_name' => $acc_name,
      'acc_no' => $acc_no,
      'store_code' => $acc_no,
      'isactive' => 1,
      'memo_type' => $memo_type,
      'form_merge' => $form_merge,
    );

    $this->db->insert('register_add_user_main', $data);

    $data = array(
      'supplier_info_guid' => $supplier_info_guid,
      'register_guid' => $register_guid
    );

    $this->db->insert('set_supplier_info', $data);

    redirect('Registration_new/register_vendor');
  }

  //add vendor site..
  public function register_forms_vendor()
  {
    if ($this->session->userdata('loginuser') == true) {
      $register_guid = $_REQUEST['register_guid'];

      $register = $this->db->query("SELECT a.*,b.* FROM lite_b2b.register_add_user_main a LEFT JOIN set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid'");

      $register_child = $this->db->query("SELECT a.*, b.`register_mapping_guid`, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code FROM lite_b2b.register_add_user_child a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

      $register_child_training = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_child a WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' ");

      $customer_guid = $register->row('customer_guid');

      $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'sec_path', 'REGPDF');

      $defined_path = $file_config_main_path;

      $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");

      $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid' AND a.branch_code IN (" . $_SESSION['query_loc'] . ") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");

      $get_supp = $this->db->query("SELECT supplier_guid FROM register_add_user_main b WHERE b.`register_guid` = '$register_guid'");

      $supplier_guid = $get_supp->row('supplier_guid');

      $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");

      $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

      $vendor = $register->row('store_code');
      $myArray_1 = explode(',', $vendor);
      $myArray = array_filter($myArray_1); //show vendor code array

      $user_details = $this->db->query("SELECT a.`customer_guid`, a.`register_guid`, b.`register_c_guid`, b.`ven_name`, b.`ven_email`, c.`ven_agency` FROM lite_b2b.register_add_user_main a LEFT JOIN lite_b2b.register_add_user_child b ON a.`register_guid` = b.`register_guid` LEFT JOIN lite_b2b.register_add_user_child_mapping c ON b.`register_c_guid` = c.`register_c_guid` WHERE b.`register_guid` = '$register_guid' AND b.`part_type` = 'registration' AND c.mapping_type = 'outlet' ORDER BY b.`created_at` ASC");

      $table_array = array();
      foreach ($user_details->result() as $row) {
        $part1 = $row->ven_email;
        $part2 = $row->ven_name;
        $loc_group_array = $row->ven_agency;

        $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '" . $register->row('customer_guid') . "'");
        // echo $this->db->last_query().';<br>';
        if ($check_exists->num_rows() > 0) {
          $dis_msg = 'Mapped';
        } else {
          $dis_msg = 'Not Map';
        }

        $check_exists2 = $this->db->query("SELECT GROUP_CONCAT(acc_name) as acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '$part1' AND a.acc_guid != '" . $register->row('customer_guid') . "' GROUP BY a.user_guid");
        // echo $this->db->last_query().';<br>';
        if ($check_exists2->num_rows() > 0) {
          $dis_msg2 = $check_exists2->row('acc_name');
        } else {
          $dis_msg2 = 'Not Map';
        }

        $user_group_dropdown_array = $this->db->query("SELECT * FROM set_user_group WHERE module_group_guid = '" . $this->session->userdata('module_group_guid') . "'");

        $check_user_group_dropdown = $this->db->query("SELECT * FROM lite_b2b.set_user a WHERE a.user_id = '$part1' AND a.acc_guid = '" . $register->row('customer_guid') . "' GROUP BY a.user_guid");
        // echo $this->db->last_query().';<br>';
        $user_group_dropdown = '<select class="" style="width:100%" name="user_group_down[]">';
        $user_group_dropdown .= '<option value="">Please Select</option>';
        if ($user_group_dropdown_array->num_rows() > 0) {
          foreach ($user_group_dropdown_array->result() as $u_dropdown) {
            if ($u_dropdown->user_group_guid == $check_user_group_dropdown->row('user_group_guid')) {
              // echo $this->db->last_query();die;
              $user_group_selected = 'selected';
            } else {
              $user_group_selected = '';
            }
            $user_group_dropdown .= '<option value="' . $u_dropdown->user_group_guid . '"' . $user_group_selected . '>' . $u_dropdown->user_group_name . '</option>';
          }
          $user_group_dropdown .= '</select>';
        } else {
          $user_group_dropdown = 'contact admin';
        }
        $loc_group_input = '<input type="hidden" name="loc_group_group_hidden" value="' . $loc_group_array . '"';

        $data2[] = array($part2, $part1, $user_group_dropdown, $dis_msg, $dis_msg2, $loc_group_input);
      } // end proceed User Details

      foreach ($user_details->result() as $row) {
        $part1 = $row->ven_email;
        $part2 = $row->ven_name;

        $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '" . $register->row('customer_guid') . "'");
        // echo $this->db->last_query().';<br>';
        if ($check_exists->num_rows() > 0) {
          $dis_msg = 'Mapped';
        } else {
          $dis_msg = 'Not Map';
        }

        $check_exists2 = $this->db->query("SELECT GROUP_CONCAT(acc_name) as acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '$part1' AND a.acc_guid != '" . $register->row('customer_guid') . "' GROUP BY a.user_guid");
        // echo $this->db->last_query().';<br>';
        if ($check_exists2->num_rows() > 0) {
          $dis_msg2 = $check_exists2->row('acc_name');
        } else {
          $dis_msg2 = 'Not Map';
        }

        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

        $selected_vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_user_relationship a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND user_guid = '$get_user_guid'");
        // echo $this->db->last_query();
        $selected_vendor_code_array = array();
        foreach ($selected_vendor_code_query->result() as $selected_code) {
          $selected_vendor_code_array[] = $selected_code->supplier_group_guid;
        }
        // print_r($selected_vendor_code_array);die;

        $vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'");
        // echo $this->db->last_query();die;
        $vendor_code_dropdown = '<select style="width:100%" class="selectpicker" multiple>';
        $mapped_string = '';
        foreach ($vendor_code_query->result() as $dropdown) {
          if (in_array($dropdown->supplier_group_guid, $selected_vendor_code_array)) {
            $selected = 'selected';
            $mapped_string .= $dropdown->supplier_group_name . ',';
          } else {
            // echo 1;
            $selected = '';
            $mapped_string .= '';
          }
          $vendor_code_dropdown .= '<option value="' . $dropdown->supplier_group_name . '"' . $selected . ' >' . $dropdown->supplier_group_name . '</option>';
        }
        $vendor_code_dropdown .= '</select>';

        if ($mapped_string == '') {
          $mapped_string = 'Not Map';
        } else {
          $mapped_string = rtrim($mapped_string, ',');
        }
        $data3[] = array($part1, $vendor_code_dropdown, $mapped_string);
      } // end proceed User Details Mapping

      // email array 
      $key = 0;  // added new
      foreach ($user_details->result() as $row) {
        $reset_pass_link = '';
        $part1 = $row->ven_email;
        $register_guid = $row->register_guid;

        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
        $empty = '';
        $check_date = $this->db->query("SELECT created_at FROM lite_b2b.register_child_new WHERE ven_email = '$part1' AND register_guid = '$register_guid' ");
        $child_date_data = $check_date->row('created_at');

        $check_reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE user_guid = '$get_user_guid' AND customer_guid = '$customer_guid' AND created_at >= '$child_date_data' ");
        // echo $this->db->last_query();
        if ($check_reset_link->num_rows() > 0) {
          $reset_url = 'https://tunasmanja.xbridge.my/index.php/Key_in/key_in?si=' . $check_reset_link->row('reset_guid') . '&ug=' . $check_reset_link->row('user_guid');
          $reset_pass_link = $reset_url;
          $duplicate = 0;
        } else {
          $reset_pass_link = 'No reset link';
          $duplicate = 1;
        }
        $reset_link_text = '<div style="display:flex;"><input class="form-control" type="text" id="copy_link_' . $key . '" value="' . $reset_pass_link . '" readonly>&nbsp;&nbsp;<i class="fa fa-copy" id="copy_link" seq="' . $key . '"></i></div>';
        $checkbox = '<input type="checkbox" class="form-checkbox" id="check_email_link" name="checkall_input_table[]" table_id="email_tb" supplier_guid="' . $supplier_guid . '" reset_g= "' . $check_reset_link->row('reset_guid') . '" customer_guid="' . $customer_guid . '" duplicate="' . $duplicate . '" link="' . $reset_pass_link . '" u_g="' . $get_user_guid . '" vendor_email=' . $part1 . '/>';
        $data4[] = array($part1, $reset_link_text, $checkbox);

        $key++; // added new
      }

      //proceed table email array
      foreach ($user_details->result() as $row) {
        $part1 = $row->ven_email;
        $check_exists2 = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '" . $user_details->row('customer_guid') . "'");

        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

        $selected_report = $this->db->query("SELECT * FROM check_email_schedule a WHERE a. user_guid = '$get_user_guid'");
        // echo $this->db->last_query();
        $selected_report_array = array();
        foreach ($selected_report->result() as $selected_code) {
          $selected_report_array[] = $selected_code->report_guid;
        }

        $report_type = $this->db->query("SELECT * from set_report_query where active = '1' AND report_guid = 'AAF708CA914A11E887B5000D3AA2838A' order by report_type , report_name asc");

        $report_type_dropdown = '<select style="width:100%" class="selectpicker" multiple>';
        $mapped_string = '';
        foreach ($report_type->result() as $dropdown) {
          if (in_array($dropdown->report_guid, $selected_report_array)) {
            $selected = 'selected';
            $mapped_string .= $dropdown->report_name . ',';
          } else {
            // echo 1;
            $selected = '';
            $mapped_string .= '';
          }
          $report_type_dropdown .= '<option value="' . $dropdown->report_guid . '"' . $selected . ' >' . $dropdown->report_name . '</option>';
        }
        $report_type_dropdown .= '</select>';

        $empty = '';
        $email_list_status = $this->db->query("SELECT GROUP_CONCAT(DISTINCT report_name) as report_name from check_email_schedule WHERE user_guid = '$get_user_guid' GROUP BY user_guid")->row('report_name');
        if ($email_list_status == '' || $email_list_status == null) {
          $email_list_status = 'No report schedule';
        }
        $email_subscribe_status = $this->db->query("SELECT GROUP_CONCAT(DISTINCT report_name) as report_name from check_email_schedule WHERE user_guid = '$get_user_guid' GROUP BY user_guid")->row('report_name');
        if ($email_subscribe_status == '' || $email_subscribe_status == null) {
          $email_subscribe_status = 'Email not subscribe';
        }
        $data5[] = array($part1, $report_type_dropdown, $email_list_status);
      }

      if ($user_details->num_rows() == 0) {
        $data2 = '';
        $data3 = '';
        $data4 = '';
        $data5 = '';
      }

      $data = array(
        'supplier_guid' => $supplier_guid,
        'customer_guid' => $customer_guid,
        'register' => $register,
        'register_child' => $register_child,
        'register_child_training' => $register_child_training,
        'acc_branch' => $acc_branch,
        'ven_agency_sql' => $ven_agency_sql, // outlet array
        //'vendor_code_sql' => $vendor_code_sql, // not use 
        'myArray' => $myArray, // Vendor Code (refer to Retailer) array
        'add_vendor_code' => $add_vendor_code->result(), // add vendor code
        'table_array' => $data2,
        'table_array2' => $data3,
        'email_array' => $data4,
        'table_array3' => $data5,
        'defined_path' => $defined_path,
      );

      $this->load->view('header');
      $this->load->view('register/register_forms_vendor', $data);
      $this->load->view('footer');
    } else {
      redirect('#');
    }
  }

  //add vendor site..
  public function register_vendor_update()
  {
    if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
      $register_guid = $_REQUEST['register_guid'];
      $register = $this->db->query("SELECT * FROM lite_b2b.register_add_user_main a WHERE a.`register_guid` = '$register_guid' ");

      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');

      $created_at = $this->db->query("SELECT now() as now")->row('now');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');
      $supply_outright = $this->input->post('supply_outright');
      $supply_consignment = $this->input->post('supply_consignment');
      $save_status = $this->input->post('save_status');

      if ($save_status == '1') {
        $form_status = 'Save-Progress';
      } else {
        $form_status = 'Processing';
      }

      $data = array(
        'supply_outright' => $supply_outright,
        'supply_consignment' => $supply_consignment,
      );

      $this->db->where('register_guid', $register_guid);
      $this->db->update('set_supplier_info', $data);

      $data = array(
        'update_at' => $updated_at,
        'update_by' => $user_id,
        'isactive' => 0,
        'form_status' => $form_status
      );

      $this->db->where('register_guid', $register_guid);
      $this->db->update('register_add_user_main', $data);

      if ($form_status == 'Processing') {
        echo "<script> alert('Submit Successfully.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Registration_new/register_forms_vendor?register_guid=" . $register_guid . "' </script>";
      } else {
        echo "<script> alert('Save Successfully.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Registration_new/register_forms_vendor?register_guid=" . $register_guid . "' </script>";
      }
    } else {
      redirect('login_c');
    }
  }

  //add vendor site..
  public function add_vendor_tb()
  {
    $register_guid = $this->input->post('register_guid');
    $register_child = $this->db->query("SELECT a.*, GROUP_CONCAT(b.`register_mapping_guid` ORDER BY b.`mapping_type` DESC) AS register_mapping_guid, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code, c.`customer_guid`, GROUP_CONCAT(b.`vendor_code_remark`) AS vendor_code_remark,c.`form_status` FROM lite_b2b.register_add_user_child a LEFT JOIN lite_b2b.register_add_user_child_mapping b ON a.`register_c_guid` = b.`register_c_guid` INNER JOIN lite_b2b.register_add_user_main c ON a.`register_guid` = c.`register_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid ORDER BY created_at ASC");

    echo json_encode($register_child->result());
  }

  //add vendor site..
  public function add_vendor_info_vens()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $ven_name = $this->input->post('ven_name');
    $ven_designation = $this->input->post('ven_designation');
    $ven_phone = $this->input->post('ven_phone');
    $ven_email = $this->input->post('ven_email');
    $ven_agency = $this->input->post('ven_agency');
    $ven_code = $this->input->post('ven_code');
    $remark_no = $this->input->post('remark_no');
    $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $m_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $m_guid_1 = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_add_user_main b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $ven_agency = implode(",", $ven_agency);
    $ven_agency = "" . $ven_agency . "";

    $ven_code = implode(",", $ven_code);
    $ven_code = "" . $ven_code . "";

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_child a WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) {
      $check_ven_name = $key->ven_name;
      $check_ven_email = $key->ven_email;

      if ($ven_name == $check_ven_name) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

        );
        echo json_encode($data);
        die;
      }

      if ($ven_email == $check_ven_email) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

        );
        echo json_encode($data);
        die;
      }
    }

    $data_1 = array(
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_add_user_main', $data_1);

    $data_2 = array(
      'supplier_guid' => $supplier_guid,
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'ven_name' => $ven_name,
      'ven_designation' => $ven_designation,
      'ven_phone' => $ven_phone,
      'ven_email' => $ven_email,
      'ven_name' => $ven_name,
      'isdelete' => 0,
      'part_type' => 'registration',
      'created_at' => $updated_at,
      'created_by' => $user_id
    );

    $this->db->insert('register_add_user_child', $data_2);

    $data_3 = array(
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'register_mapping_guid' => $m_guid,
      'ven_agency' => $ven_agency,
      'mapping_type' => 'outlet'
    );

    $this->db->insert('register_add_user_child_mapping', $data_3);

    $data_4 = array(
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'register_mapping_guid' => $m_guid_1,
      'ven_code' => $ven_code,
      'vendor_code_remark' => $remark_no,
      'mapping_type' => 'code'
    );

    $this->db->insert('register_add_user_child_mapping', $data_4);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Add Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  //add vendor site..
  public function edit_vendor_info_vens()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $register_c_guid = $this->input->post('register_c_guid');
    $register_mapping_guid = $this->input->post('register_mapping_guid');
    $ven_name = $this->input->post('ven_name');
    $ven_designation = $this->input->post('ven_designation');
    $ven_phone = $this->input->post('ven_phone');
    $ven_email = $this->input->post('ven_email');
    $ven_agency = $this->input->post('ven_agency');
    $ven_code = $this->input->post('ven_code');
    $ven_code_remark = $this->input->post('ven_code_remark');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_add_user_main b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $ven_agency = implode(",", $ven_agency);
    $ven_agency = "" . $ven_agency . "";

    $ven_code = implode(",", $ven_code);
    $ven_code = "" . $ven_code . "";

    $register_mapping_guid = explode(",", $register_mapping_guid);
    $register_mapping_guid_1 = $register_mapping_guid[0]; // outlet guid
    $register_mapping_guid_2 = $register_mapping_guid[1]; // code guid

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_child a WHERE a.`register_guid` = '$register_guid' AND a.`register_c_guid` != '$register_c_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) {
      $check_ven_name = $key->ven_name;
      $check_ven_email = $key->ven_email;

      if ($ven_name == $check_ven_name) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

        );
        echo json_encode($data);
        die;
      }

      if ($ven_email == $check_ven_email) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

        );
        echo json_encode($data);
        die;
      }
    }

    $data_2 = array(
      'ven_name' => $ven_name,
      'ven_designation' => $ven_designation,
      'ven_phone' => $ven_phone,
      'ven_email' => $ven_email,
      'ven_name' => $ven_name,
      'isdelete' => 0,
    );

    $this->db->where('register_c_guid', $register_c_guid);
    $this->db->update('register_add_user_child', $data_2);

    $data_3 = array(
      'ven_agency' => $ven_agency,
      'mapping_type' => 'outlet'
    );

    $this->db->where('register_mapping_guid', $register_mapping_guid_1);
    $this->db->update('register_add_user_child_mapping', $data_3);

    $data_4 = array(
      'ven_code' => $ven_code,
      'vendor_code_remark' => $ven_code_remark,
      'mapping_type' => 'code'
    );

    $this->db->where('register_mapping_guid', $register_mapping_guid_2);
    $this->db->update('register_add_user_child_mapping', $data_4);

    $data_1 = array(
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_add_user_main', $data_1);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Edit Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  // END HERE ADD VENDOR SITE @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function register_admin()
  {
    if ($this->session->userdata('loginuser') == true) {
      $customer_guid = $_SESSION['customer_guid'];

      $supplier = $this->db->query("SELECT a.* FROM set_supplier a ORDER BY a.supplier_name ASC")->result();
      $retailer = $this->db->query("SELECT DISTINCT a.acc_name FROM acc a WHERE a.acc_guid ='" . $_SESSION['customer_guid'] . "'  ")->row('acc_name');
      $get_new_status = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`, COUNT(b.`acc_name`) AS numbering,IF(b.acc_guid = '$customer_guid' ,'1','2') AS sort FROM lite_b2b.register_new a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.acc_guid WHERE a.form_status = 'New' GROUP BY a.`customer_guid` ORDER BY sort ASC , b.acc_name ASC");
      $get_memo_type = $this->db->query("SELECT * FROM b2b_invoice.`template_settings_general` WHERE template_group IN ('outright','consign' ) AND template_type = 'amount' ORDER BY template_name");

      $get_outright_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'outright' ORDER BY template_name");

      $get_consign_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'consign' ORDER BY template_name");

      $get_cap_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'cap' ORDER BY template_name ASC");

      $get_waive_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'waive' ORDER BY template_name ASC");

      $check_template_name = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_guid = '$reg_memo_type'");

      if ($customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7' || $customer_guid == '13EE932D98EB11EAB05B000D3AA2838A') {
        $file_config_acceptance_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'acpt_path', 'ACPTPDF');
        $acceptance_path = $file_config_acceptance_path;
      } else {
        $acceptance_path = 'hide';
      }

      $data = array(
        'supplier' => $supplier,
        'retailer' => $retailer,
        'get_new_status' => $get_new_status,
        'customer_guid' => $customer_guid,
        'get_memo_type' => $get_memo_type->result(),
        'acceptance_path' => $acceptance_path,
        'get_outright_template' => $get_outright_template->result(),
        'get_consign_template' => $get_consign_template->result(),
        'get_cap_template' => $get_cap_template->result(),
        'get_waive_template' => $get_waive_template->result(),
      );

      $this->load->view('header');
      $this->load->view('register/register_admin_new', $data);
      $this->load->view('footer');
    } else {
      redirect('#');
    }
  }

  //edited
  public function register_table()
  {
    ini_set('memory_limit', -1);
    $session_supcode = $_SESSION['query_supcode'];

    if ($session_supcode == '') {
      $query1 = "AND f.`supplier_group_name` = ''";
    } else {
      $query1 = "AND f.`supplier_group_name` IN ($session_supcode)";
    }

    if ($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') {
      $columns = array(
        0 => 'register_guid',
        1 => 'register_guid',
        2 => 'register_no',
        3 => 'supplier_name',
        4 => 'acc_name',
        5 => 'memo_type',
        6 => 'comp_email',
        7 => 'acc_no',
        8 => 'cnt',
        9 => 'part_cnt',
        10 => 'form_status',
        11 => 'update_at',
        12 => 'update_by',
        13 => 'create_at',
        14 => 'create_by',

      );
    } else {
      $columns = array(
        0 => 'register_guid',
        1 => 'register_guid',
        2 => 'register_no',
        3 => 'supplier_name',
        4 => 'acc_name',
        5 => 'memo_type',
        6 => 'comp_email',
        7 => 'acc_no',
        8 => 'cnt',
        9 => 'part_cnt',
        10 => 'form_status',
        11 => 'update_at',
        12 => 'update_by',
        13 => 'create_at',
        14 => 'create_by',
      );
    }


    $user_guid = $_SESSION['user_guid'];
    $user_group = $_SESSION['user_group_name'];
    $limit = $this->input->post('length');
    $start = $this->input->post('start');
    $order = $this->input->post('order');
    $dir = "";
    $customer_guid = $_SESSION['customer_guid'];
    //$totalData = $this->Registration_model->register($customer_guid)->row('numrow');
    // $totalData = $this->db->query("SELECT COUNT(*) as numrow FROM lite_b2b.register_new WHERE customer_guid = '$customer_guid' ")->row('numrow');
    // $totalFiltered = $totalData;

    $order_query = "";

    if (!empty($order)) {
      foreach ($order as $o) {
        $col = $o['column'];
        $dir = $o['dir'];

        $order_query .= $columns[$col] . " " . $dir . ",";
      }
    }
    $dir = '';
    $order_query = rtrim($order_query, ',');

    if ($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') {
      $query = "
          SELECT 
          aa.`isacceptance`,
          aa.`supplier_guid`,
          aa.`comp_contact`,
          aa.`second_comp_contact`,
          aa.`supplier_info_guid`,
          aa.`register_no`,
          aa.`supplier_name`,
          aa.`acc_name`,
          aa.`comp_email`,
          aa.`acc_no`,
          aa.`create_at`,
          aa.`create_by`,
          aa.`update_at`,
          aa.`update_by`,
          aa.`register_guid`,
          aa.`cnt`,
          aa.`part_cnt`,
          aa.`customer_guid`,
          aa.`form_status`,
          IF(aa.template_name IS NULL ,aa.`memo_type`,aa.template_name) AS memo_type,
          IF(aa.template_name IS NULL ,aa.`memo_type`,aa.memo_type) AS memo_name,
          aa.`url`,
          aa.`comp_no`,
          aa.outright_template,
          aa.consignment_template,
          aa.cap_template,
          aa.waive_template,
          aa.outright_start_date,
          aa.consign_start_date,
          aa.cap_start_date,
          aa.cap_end_date,
          aa.waive_start_date,
          aa.waive_end_date,
          aa.isinvoice
        FROM
          (SELECT 
            c.`supplier_name`,
            e.`supplier_info_guid`,
            a.*,
            d.*,
            f.template_name,
            h.url,
            e.outright_template,
            e.consignment_template,
            e.cap_template,
            e.waive_template,
            e.outright_start_date,
            e.consign_start_date,
            e.cap_start_date,
            e.cap_end_date,
            e.waive_start_date,
            e.waive_end_date
          FROM
            register_new a 
            INNER JOIN acc b 
              ON b.acc_guid = a.`customer_guid` 
            LEFT JOIN set_supplier c 
              ON c.supplier_guid = a.`supplier_guid` 
            LEFT JOIN set_supplier_info e 
              ON a.`register_guid` = e.register_guid 
            LEFT JOIN 
              (SELECT DISTINCT 
                register_guid AS register_id,
                COUNT(`ven_name`) AS cnt, 
                COUNT(`part_name`) AS part_cnt
              FROM
                register_child_new 
              GROUP BY register_guid) d 
              ON a.`register_guid` = d.register_id
            LEFT JOIN b2b_invoice.`template_settings_general` f
              ON a.`memo_type` = f.`template_guid`
            LEFT JOIN lite_b2b.`reg_acceptance` h
              ON a.`register_guid` = h.`register_guid`
              AND a.`customer_guid` = h.`customer_guid`
              AND a.`supplier_guid` = h.`supplier_guid`
            WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "' 
            ORDER BY FIELD(a.form_status, 'Advance','New','Processing','Save-Progress','Send','Registered','Archived','') , a.create_at DESC) aa 
            WHERE aa.customer_guid = '" . $_SESSION['customer_guid'] . "' ";
    } else {
      $query = "
        SELECT 
          aa.`isacceptance`,
          aa.`supplier_guid`,
          aa.`comp_contact`,
          aa.`second_comp_contact`,
          aa.`supplier_info_guid`,
          aa.`register_no`,
          aa.`supplier_name`,
          aa.`acc_name`,
          aa.`comp_email`,
          aa.`acc_no`,
          aa.`create_at`,
          aa.`create_by`,
          aa.`update_at`,
          aa.`update_by`,
          aa.`register_guid`,
          aa.`cnt`,
          aa.`part_cnt`,
          aa.`customer_guid`,
          aa.`form_status`,
          IF(aa.template_name IS NULL ,aa.`memo_type`,aa.template_name) AS memo_type,
          IF(aa.template_name IS NULL ,aa.`memo_type`,aa.memo_type) AS memo_name,
          aa.url,
          aa.`comp_no`
        FROM
          (SELECT 
            c.`supplier_name`,
            e.`supplier_info_guid`,
            a.*,
            d.*,
            g.template_name,
            h.url
          FROM
            register_new a 
            INNER JOIN acc b 
              ON b.acc_guid = a.`customer_guid` 
            LEFT JOIN set_supplier c 
              ON c.supplier_guid = a.`supplier_guid` 
            LEFT JOIN set_supplier_info e 
              ON a.`register_guid` = e.register_guid 
            INNER JOIN lite_b2b.`set_supplier_group` f
            ON a.supplier_guid = f.`supplier_guid`
            AND f.`customer_guid` = '" . $_SESSION['customer_guid'] . "'
            $query1
            LEFT JOIN 
              (SELECT DISTINCT 
                register_guid AS register_id,
                COUNT(`ven_name`) AS cnt, 
                COUNT(`part_name`) AS part_cnt
              FROM
                register_child_new 
              GROUP BY register_guid) d 
              ON a.`register_guid` = d.register_id
            LEFT JOIN b2b_invoice.`template_settings_general` g
              ON a.`memo_type` = g.`template_guid`
            LEFT JOIN lite_b2b.`reg_acceptance` h
              ON a.`register_guid` = h.`register_guid`
              AND a.`customer_guid` = h.`customer_guid`
              AND a.`supplier_guid` = h.`supplier_guid`
            WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "' 
            ORDER BY a.create_at DESC) aa 
              WHERE aa.customer_guid = '" . $_SESSION['customer_guid'] . "' 
              GROUP BY aa.register_guid";
    }

    $totalData = $this->db->query($query)->num_rows();
    $totalFiltered = $totalData;

    if (empty($this->input->post('search')['value'])) {
      $posts = $this->Registration_model->allposts($query, $limit, $start, $order_query, $dir);
      //echo $this->db->last_query(); die;
    } else {
      $search = $this->input->post('search')['value'];

      $posts =  $this->Registration_model->posts_search($query, $limit, $start, $search, $order_query, $dir);

      $totalFiltered = $this->Registration_model->posts_search_count($query, $search);
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $post) {
        $nestedData['register_no'] = $post->register_no;
        $nestedData['supplier_name'] = $post->supplier_name;
        $nestedData['acc_name'] = $post->acc_name;
        $nestedData['comp_email'] = $post->comp_email;
        $nestedData['create_at'] = $post->create_at;
        $nestedData['create_by'] = $post->create_by;
        $nestedData['update_at'] = $post->update_at;
        $nestedData['update_by'] = $post->update_by;
        $nestedData['cnt'] = $post->cnt;
        $nestedData['part_cnt'] = $post->part_cnt;
        $nestedData['acc_no'] = $post->acc_no;
        $nestedData['form_status'] = $post->form_status;
        $nestedData['memo_type'] = $post->memo_type;
        $nestedData['memo_name'] = $post->memo_name;
        $nestedData['register_guid'] = $post->register_guid;


        if ($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE') {

          if ($post->form_status == '') {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_form_edit_new?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a register_guid=' . $post->register_guid . ' id="send_btn" title="SEND" class="btn btn-xs btn-warning" type="button"  form_type ="register" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_guid="' . $post->supplier_guid . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_name . '" form_status="' . $post->form_status . '" comp_contact="' . $post->comp_contact . '" second_comp_contact="' . $post->second_comp_contact . '" outright_template="' . $post->outright_template . '" consignment_template="' . $post->consignment_template . '" cap_template="' . $post->cap_template . '" waive_template="' . $post->waive_template . '" outright_start_date="' . $post->outright_start_date . '" consign_start_date="' . $post->consign_start_date . '" cap_start_date="' . $post->cap_start_date . '" cap_end_date="' . $post->cap_end_date . '" waive_start_date="' . $post->waive_start_date . '" waive_end_date="' . $post->waive_end_date . '" isinvoice="' . $post->isinvoice . '" style="margin-top:5px;"><i class="fa fa-edit"></i></a><a class="btn btn-xs btn-danger" type="button" id="btn_delete_form" title="DELETE" register_guid="' . $post->register_guid . '" style="margin-top:5px;margin-left:5px;"><i class="glyphicon glyphicon-remove"></i></a><a class="btn btn-xs btn-danger" type="button" id="btn_archive" title="Archive" register_guid="' . $post->register_guid . '" form_status="' . $post->form_status . '" style="margin-top:5px;"><i class="fa fa-file-archive-o"></i></a>';
          } else if (($post->form_status == 'Send') || ($post->form_status == 'Save-Progress') || ($post->form_status == 'Advance')) {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_form_edit_new?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a register_guid=' . $post->register_guid . ' id="send_btn" title="SEND" class="btn btn-xs btn-warning" type="button"  form_type ="register" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_guid="' . $post->supplier_guid . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_name . '" form_status="' . $post->form_status . '" comp_contact="' . $post->comp_contact . '" second_comp_contact="' . $post->second_comp_contact . '" outright_template="' . $post->outright_template . '" consignment_template="' . $post->consignment_template . '" cap_template="' . $post->cap_template . '" waive_template="' . $post->waive_template . '" outright_start_date="' . $post->outright_start_date . '" consign_start_date="' . $post->consign_start_date . '" cap_start_date="' . $post->cap_start_date . '" cap_end_date="' . $post->cap_end_date . '" waive_start_date="' . $post->waive_start_date . '" waive_end_date="' . $post->waive_end_date . '" isinvoice="' . $post->isinvoice . '" style="margin-top:5px;"><i class="fa fa-edit"></i></a><a class="btn btn-xs btn-danger" style="margin-top:5px;margin-left:5px;" type="button" id="btn_archive" title="Archive" register_guid="' . $post->register_guid . '" form_status="' . $post->form_status . '" style="margin-top:5px;"><i class="fa fa-file-archive-o"></i></a>';
          } else if (($post->form_status == 'New') || ($post->form_status == 'Processing')) {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_form_edit_new?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_guid="' . $post->supplier_guid . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_type . '" form_status="' . $post->form_status . '" comp_contact="' . $post->comp_contact . '" second_comp_contact="' . $post->second_comp_contact . '" outright_template="' . $post->outright_template . '" consignment_template="' . $post->consignment_template . '" cap_template="' . $post->cap_template . '" waive_template="' . $post->waive_template . '" outright_start_date="' . $post->outright_start_date . '" consign_start_date="' . $post->consign_start_date . '" cap_start_date="' . $post->cap_start_date . '" cap_end_date="' . $post->cap_end_date . '" waive_start_date="' . $post->waive_start_date . '" waive_end_date="' . $post->waive_end_date . '" isinvoice="' . $post->isinvoice . '" style="margin-left:5px;"><i class="fa fa-edit"></i></a><a class="btn btn-xs btn-danger" type="button" id="btn_archive" title="Archive" register_guid="' . $post->register_guid . '" form_status="' . $post->form_status . '" style="margin-top:5px;"><i class="fa fa-file-archive-o"></i></a>';
          } else if (($post->form_status == 'Archived')) {
            $nestedData['action'] = '<a class="btn btn-xs btn-danger" type="button" id="btn_archive" title="Archive" register_guid="' . $post->register_guid . '" form_status="' . $post->form_status . '"><i class="fa fa-file-archive-o"></i></a>';
          } else {
            $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_form_edit_new?register_guid=' . $post->register_guid . '"><i class="glyphicon glyphicon-pencil"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" register_guid="' . $post->register_guid . '" register_no="' . $post->register_no . '" supplier_guid="' . $post->supplier_guid . '" supplier_name="' . $post->supplier_name . '" comp_no="' . $post->comp_no . '" acc_name="' . $post->acc_name . '" comp_email="' . $post->comp_email . '" edit_acc_no="' . $post->acc_no . '" memo_type="' . $post->memo_name . '" form_status="' . $post->form_status . '" comp_contact="' . $post->comp_contact . '" second_comp_contact="' . $post->second_comp_contact . '" outright_template="' . $post->outright_template . '" consignment_template="' . $post->consignment_template . '" cap_template="' . $post->cap_template . '" waive_template="' . $post->waive_template . '" outright_start_date="' . $post->outright_start_date . '" consign_start_date="' . $post->consign_start_date . '" cap_start_date="' . $post->cap_start_date . '" cap_end_date="' . $post->cap_end_date . '" waive_start_date="' . $post->waive_start_date . '" waive_end_date="' . $post->waive_end_date . '" isinvoice="' . $post->isinvoice . '" style="margin-left:5px;"><i class="fa fa-edit"></i></a><a class="btn btn-xs btn-danger" type="button" id="btn_archive" title="Archive" register_guid="' . $post->register_guid . '" form_status="' . $post->form_status . '" style="margin-top:5px;"><i class="fa fa-file-archive-o"></i></a>';

            //<a register_guid='.$post->register_guid.'id="view_ticket" class="btn btn-xs btn-primary" type="button" href="register_form_view?register_guid='.$post->register_guid.'" style="margin-left:5px;"><i class="glyphicon glyphicon-eye-open"></i></a>
          }
        } else {
          if ($_SESSION['customer_guid'] == '13EE932D98EB11EAB05B000D3AA2838A' || $_SESSION['customer_guid'] == '610BB0EA76AE11EDB37C72B64FC54D79') {
            if ($post->form_status == 'Registered') {

              $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_form_edit_new?register_guid=' . $post->register_guid . '"><i class="fa fa-eye"></i></a>';
              
            } else {
              $nestedData['action'] = '';
            }
          } else {
            if ($post->form_status == 'Registered') {
              $nestedData['action'] = '<a register_guid=' . $post->register_guid . ' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="register_form_edit_new?register_guid=' . $post->register_guid . '"><i class="fa fa-eye"></i></a>';
            } else {
              $nestedData['action'] = '';
            }
          }
        }

        $data[] = $nestedData;
      }
    }

    $json_data = array(
      "draw"            => intval($this->input->post('draw')),
      "recordsTotal"    => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data"            => $data
    );

    echo json_encode($json_data);
  }

  //edited
  public function register_form_edit_new()
  {
    if ($this->session->userdata('loginuser') == true) {
      $register_guid = $_REQUEST['register_guid'];

      $register = $this->db->query("SELECT a.*, b.* FROM lite_b2b.register_new a LEFT JOIN set_supplier_info b ON a.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid'");

      // memo type added 15-06-22
      $reg_memo_type = $register->row('memo_type');

      if($reg_memo_type == 'outright')
      {
        $get_user_group_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status = '4'")->row('user_group_guid');
      }
      else if($reg_memo_type == 'consignment')
      {
        $get_user_group_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status = '5'")->row('user_group_guid');
      }
      else if($reg_memo_type == 'both')
      {
        $get_user_group_guid = $this->db->query("SELECT * FROM lite_b2b.set_user_group WHERE group_info_status = '1'")->row('user_group_guid');
      }
      else
      {
        $get_user_group_guid = '';
      }

      $register_charge_type = $this->db->query("SELECT a.`memo_type`, b.`template_group`, IF(b.`template_group` IS NULL , a.`memo_type` , b.`template_group`) AS template_type FROM lite_b2b.register_new a LEFT JOIN b2b_invoice.template_settings_general b ON a.`memo_type` = b.`template_guid` WHERE a.`register_guid` = '$register_guid' ");

      $register_child = $this->db->query("SELECT a.*, b.`register_mapping_guid`, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code, b.vendor_code_remark FROM lite_b2b.register_child_new a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

      $register_child_training = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' ");

      $customer_guid = $register->row('customer_guid');

      $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");

      $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid' AND a.branch_code IN (" . $_SESSION['query_loc'] . ") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");

      $get_outright_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'outright' ORDER BY template_name ASC");

      $get_consign_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'consign' ORDER BY template_name ASC");

      $get_cap_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'cap' ORDER BY template_name ASC");

      $get_waive_template = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_group = 'waive' ORDER BY template_name ASC");

      $check_template_name = $this->db->query("SELECT template_guid,template_name,template_group FROM b2b_invoice.`template_settings_general` WHERE template_guid = '$reg_memo_type'");

      if($check_template_name->num_rows() == 0)
      {
        $check_template_name = $reg_memo_type;
      }
      else
      {
        $check_template_name = $check_template_name->row('template_name');
      }

      $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");

      $supplier_guid = $get_supp->row('supplier_guid');

      $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");

      $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

      $vendor = $register->row('store_code');
      $myArray_1 = explode(',', $vendor);
      $myArray = array_filter($myArray_1); //show vendor code array

      $vendor_remark = $register->row('vendor_code_remark');
      $myArray_2 = explode(',', $vendor_remark);
      $myArray_2 = array_filter($myArray_2); //show vendor code remark array

      $vendor_remark_edit = $register_child->row('vendor_code_remark');
      $myArray_3 = explode(',', $vendor_remark_edit);
      $myArray_3 = array_filter($myArray_3); //show vendor code remark array

      $get_user_group = $this->db->query("SELECT user_group_guid,user_group_name FROM lite_b2b.set_user_group WHERE group_info_status = '1'");

      // if($get_user_group->num_rows() == 0)
      // {
      //   echo "<script> alert('Please Contact Support. Invalid User Group Found.');</script>";
      //   echo "<script> document.location='" . base_url() . "index.php/' </script>";
      //   exit();
      // }
      // else if($get_user_group->num_rows() > 1)
      // {
      //   echo "<script> alert('Please Contact Support. Invalid User Group Found.');</script>";
      //   echo "<script> document.location='" . base_url() . "index.php/' </script>";
      //   exit();
      // }

      $user_details = $this->db->query("SELECT a.`customer_guid`, a.`register_guid`, b.`register_c_guid`, b.`ven_name`, b.`ven_email`, c.`ven_agency`, b.user_group_info FROM lite_b2b.register_new a LEFT JOIN lite_b2b.register_child_new b ON a.`register_guid` = b.`register_guid` LEFT JOIN lite_b2b.register_child_mapping c ON b.`register_c_guid` = c.`register_c_guid` WHERE b.`register_guid` = '$register_guid' AND b.`part_type` = 'registration' AND c.mapping_type = 'outlet' ORDER BY b.`created_at` ASC");

      $table_array = array();
      $user_details_count = '1';
      foreach ($user_details->result() as $row) {
        $part1 = $row->ven_email;
        $part2 = $row->ven_name;
        $loc_group_array = $row->ven_agency;
        $set_user_group = $row->user_group_info;

        $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '" . $register->row('customer_guid') . "'");
        // echo $this->db->last_query().';<br>';
        if($check_exists->num_rows() > 0 )
        {
          $dis_msg = '<span style="background-color:#3afa14;font-weight:bold;"> Mapped </span>';
        }
        else
        {
          $dis_msg = '<span style="background-color:#ff738c;font-weight:bold;"> Not Map </span>';
        }

        $check_exists2 = $this->db->query("SELECT b.acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '$part1' AND a.acc_guid != '".$register->row('customer_guid')."' GROUP BY a.acc_guid")->result_array();

        if(count($check_exists2) > 0 )
        {
          $other_retailer_value = "-" . implode("\n-",array_filter(array_column($check_exists2,'acc_name')));
          $dis_msg2 = '<textarea rows="4" cols="30" readonly>'.$other_retailer_value.'</textarea>';
        }
        else
        {
          $dis_msg2 = 'Not Found';
        }

        $user_group_dropdown_array = $this->db->query("SELECT * FROM set_user_group WHERE module_group_guid = '".$this->session->userdata('module_group_guid')."' AND isactive = '1' AND admin_active = '1' ORDER BY group_info_status DESC, admin_active DESC , user_group_name ASC");

        $check_user_group_dropdown = $this->db->query("SELECT * FROM lite_b2b.set_user a WHERE a.user_id = '$part1' AND a.acc_guid = '" . $register->row('customer_guid') . "' GROUP BY a.user_guid");

        $user_group_dropdown = '<select class="form-control select2 user_group_css" style="width:100%" name="user_group_down[]" id="user_details_selection'.$user_details_count.'">';
        $user_group_dropdown .= '<option value="">Please Select</option>';

        if ($user_group_dropdown_array->num_rows() > 0) {
          foreach ($user_group_dropdown_array->result() as $u_dropdown) {
            if ($u_dropdown->user_group_guid == $check_user_group_dropdown->row('user_group_guid')) {
              // echo $this->db->last_query();die;
              $user_group_selected = 'selected';
            }
            else if($u_dropdown->user_group_guid == $set_user_group)
            {

              $user_group_selected = 'selected';
            }
            else 
            {
              $user_group_selected = '';
            }

            $user_group_dropdown .= '<option value="' . $u_dropdown->user_group_guid . '"' . $user_group_selected . '>' . $u_dropdown->user_group_name . '</option>';
          }
          $user_group_dropdown .= '</select>';
        } else {
          $user_group_dropdown = 'contact admin';
        }
        $loc_group_input = '<input type="hidden" name="loc_group_group_hidden" value="' . $loc_group_array . '"';

        $data2[] = array($part2, $part1, $user_group_dropdown, $dis_msg, $dis_msg2, $loc_group_input);
        $user_details_count++;
      } // end proceed User Details

      $countid = '1';
      foreach ($user_details->result() as $row) {
        $part1 = $row->ven_email;
        $part2 = $row->ven_name;

        $check_exists = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '" . $register->row('customer_guid') . "'");
        // echo $this->db->last_query().';<br>';
        if ($check_exists->num_rows() > 0) {
          $dis_msg = 'Mapped';
        } else {
          $dis_msg = 'Not Map';
        }

        $check_exists2 = $this->db->query("SELECT GROUP_CONCAT(acc_name) as acc_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.acc b ON a.`acc_guid` = b.acc_guid WHERE a.user_id = '$part1' AND a.acc_guid != '" . $register->row('customer_guid') . "' GROUP BY a.user_guid");
        // echo $this->db->last_query().';<br>';
        if ($check_exists2->num_rows() > 0) {
          $dis_msg2 = $check_exists2->row('acc_name');
        } else {
          $dis_msg2 = 'Not Map';
        }

        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

        $selected_vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_user_relationship a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid' AND user_guid = '$get_user_guid'");
        // echo $this->db->last_query();
        $selected_vendor_code_array = array();
        foreach ($selected_vendor_code_query->result() as $selected_code) {
          $selected_vendor_code_array[] = $selected_code->supplier_group_guid;
        }
        // print_r($selected_vendor_code_array);die;

        $vendor_code_query = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group a WHERE a.supplier_guid = '$supplier_guid' AND a.customer_guid = '$customer_guid'");
        // echo $this->db->last_query();die;
        $vendor_code_dropdown = '<select class="selectpicker" multiple id="detail_option'.$countid.'" style="width:100%;">';
        $mapped_string = '';
        foreach ($vendor_code_query->result() as $dropdown) {
          if (in_array($dropdown->supplier_group_guid, $selected_vendor_code_array)) {
            $selected = 'selected';
            $mapped_string .= $dropdown->supplier_group_name . ',';
          } else {
            // echo 1;
            $selected = '';
            $mapped_string .= '';
          }
          $vendor_code_dropdown .= '<option value="' . $dropdown->supplier_group_name . '"' . $selected . ' >' . $dropdown->supplier_group_name . '</option>';
        }
        $vendor_code_dropdown .= '</select> <button id="proceed_all_dis" get_id="detail_option'.$countid.'" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="proceed_all" get_id="detail_option'.$countid.'" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button> ';

        if($mapped_string == '-')
        {
          $mapped_string = '<span style="background-color:#ff738c;font-weight:bold;"> Not Map </span>';  
        }
        else
        {
          $mapped_string = rtrim(rtrim($mapped_string,','),'-');
          $mapped_string = '<textarea rows="4" cols="20" readonly>'.$mapped_string.'</textarea>';
        }
        $data3[] = array($part1, $vendor_code_dropdown, $mapped_string);
        $countid++;
      } // end proceed User Details Mapping

      // email array 
      $key = 0;  // added new
      foreach ($user_details->result() as $row) {
        $reset_pass_link = '';
        $part1 = $row->ven_email;
        $register_guid = $row->register_guid;

        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
        $empty = '';
        $check_date = $this->db->query("SELECT created_at FROM lite_b2b.register_child_new WHERE ven_email = '$part1' AND register_guid = '$register_guid' ");
        $child_date_data = $check_date->row('created_at');

        $check_reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE user_guid = '$get_user_guid' AND customer_guid = '$customer_guid' AND created_at >= '$child_date_data' ");
        // echo $this->db->last_query();
        if ($check_reset_link->num_rows() > 0) {
          $reset_url = 'https://tunasmanja.xbridge.my/index.php/Key_in/key_in?si=' . $check_reset_link->row('reset_guid') . '&ug=' . $check_reset_link->row('user_guid');
          $reset_pass_link = $reset_url;
          $duplicate = 0;
        } else {
          $reset_pass_link = 'No reset link';
          $duplicate = 1;
        }
        $reset_link_text = '<div style="display:flex;"><input class="form-control" type="text" id="copy_link_' . $key . '" value="' . $reset_pass_link . '" readonly>&nbsp;&nbsp;<i class="fa fa-copy" id="copy_link" seq="' . $key . '"></i></div>';
        $checkbox = '<input type="checkbox" class="form-checkbox" id="check_email_link" name="checkall_input_table[]" table_id="email_tb" supplier_guid="' . $supplier_guid . '" reset_g= "' . $check_reset_link->row('reset_guid') . '" customer_guid="' . $customer_guid . '" duplicate="' . $duplicate . '" link="' . $reset_pass_link . '" u_g="' . $get_user_guid . '" vendor_email=' . $part1 . '/>';
        $data4[] = array($part1, $reset_link_text, $checkbox);

        $key++; // added new
      }

      //proceed table email array
      $subscribe_id = '1';
      foreach ($user_details->result() as $row)
      {
        $part1 = $row->ven_email;
        $check_exists2 = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '".$user_details->row('customer_guid')."'");

        $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$part1' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');

        //$selected_report = $this->db->query("SELECT * FROM check_email_schedule a WHERE a.user_guid = '$get_user_guid'");

        $selected_report = $this->db->query("SELECT a.rep_option_guid AS report_guid, b.log_table AS report_name FROM lite_b2b.set_report_query_option a INNER JOIN lite_b2b.set_logs_query b ON a.report_guid = b.guid INNER JOIN lite_b2b.set_report_query_option_c c ON a.rep_option_guid = c.rep_option_guid AND a.customer_guid = c.customer_guid WHERE a.isactive = '1' AND a.customer_guid = '$customer_guid' AND c.user_guid = '$get_user_guid' AND c.isactive = '1' GROUP BY a.rep_option_guid");

        $selected_report_array = array();
        foreach($selected_report->result() as $selected_code)
        {
          $selected_report_array[] = $selected_code->report_guid;   
        }  

        //$report_type = $this->db->query("SELECT * from set_report_query where active = '1' AND report_guid = 'AAF708CA914A11E887B5000D3AA2838A' order by report_type , report_name asc");

        // jr edit 14-07-2023
        $report_type = $this->db->query("SELECT a.rep_option_guid AS report_guid, b.log_table AS report_name FROM lite_b2b.set_report_query_option a INNER JOIN lite_b2b.set_logs_query b ON a.report_guid = b.guid WHERE a.isactive = '1' AND a.customer_guid = '$customer_guid'");

        $report_type_dropdown = '<select class="selectpicker" id="email_subscribe'.$subscribe_id.'" multiple>';
        $mapped_string = '';
        foreach($report_type->result() as $dropdown)
        {
          if(in_array($dropdown->report_guid,$selected_report_array))
          {
            $selected = 'selected';
            $mapped_string .= $dropdown->report_name.',';     
          }
          else
          {
            // echo 1;
            $selected = '';  
            $mapped_string .= '';  
          }
          $report_type_dropdown .= '<option value="'.$dropdown->report_guid.'"'.$selected.' >'.$dropdown->report_name.'</option>';
        }
        $report_type_dropdown .= '</select> <button id="proceed_notification_remove" get_id="email_subscribe'.$subscribe_id.'" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="proceed_notification_all" get_id="email_subscribe'.$subscribe_id.'" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button>';


        $get_notification_name = $this->db->query("SELECT c.log_table FROM lite_b2b.`set_report_query_option` a
        INNER JOIN lite_b2b.`set_report_query_option_c` b
        ON a.rep_option_guid = b.rep_option_guid
        AND a.isactive = '1'
        INNER JOIN lite_b2b.set_logs_query c
        ON a.report_guid = c.guid
        AND c.isactive = '2'
        WHERE b.user_guid = '$get_user_guid'
        AND b.customer_guid = '".$user_details->row('customer_guid')."'
        AND b.isactive = '1'
        GROUP BY a.report_guid")->result_array();

        if(count($get_notification_name) > 0)
        {
          $datatest = "-" . implode("\n-",array_filter(array_column($get_notification_name,'log_table'))) ."";
          $notification_name = '<textarea rows="4" cols="30" readonly>'.$datatest.'</textarea>';
        }
        else
        {
          $notification_name = '<span style="background-color:#ff738c;font-weight:bold;"> No Subscribe Notification </span>';
        }

        $data5[] = array($part1,$report_type_dropdown,$notification_name);  
        $subscribe_id++;
      } 

      if ($user_details->num_rows() == 0) {
        $data2 = '';
        $data3 = '';
        $data4 = '';
        $data5 = '';
      }

      $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'sec_path', 'REGPDF');

      $defined_path = $file_config_main_path;

      $data = array(
        'supplier_guid' => $supplier_guid,
        'customer_guid' => $customer_guid,
        'register' => $register,
        'register_child' => $register_child,
        'register_child_training' => $register_child_training,
        'acc_branch' => $acc_branch,
        'ven_agency_sql' => $ven_agency_sql, // outlet array
        //'vendor_code_sql' => $vendor_code_sql, // not use 
        'myArray' => $myArray, // Vendor Code (refer to Retailer) array
        'myArray_2' => $myArray_2, // Vendor Code remark array
        'myArray_3' => $myArray_3, // edit Vendor Code remark array
        'add_vendor_code' => $add_vendor_code->result(), // add vendor code
        'table_array' => $data2,
        'table_array2' => $data3,
        'email_array' => $data4,
        'table_array3' => $data5,
        'defined_path' => $defined_path,
        'register_charge_type' => $register_charge_type,
        'is_download' => $register->row('term_download'),
        'reg_memo_type' => $reg_memo_type,
        'get_outright_template' => $get_outright_template->result(),
        'get_consign_template' => $get_consign_template->result(),
        'get_cap_template' => $get_cap_template->result(),
        'get_waive_template' => $get_waive_template->result(),
        'check_template_name' => strtoupper($check_template_name),
        'get_user_group' => $get_user_group,
        'get_user_group_guid' => $get_user_group_guid,
      );

      $this->load->view('header');
      $this->load->view('register/register_forms_edit_new', $data);
      $this->load->view('footer');
    } else {
      redirect('#');
    }
  }

  //edited
  public function register_update()
  {
    if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
      $register_guid = $_REQUEST['register_guid'];
      $register = $this->db->query("SELECT * FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid' ");
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');

      $comp_mail = $this->input->post('comp_mail');
      $comp_no = $this->input->post('comp_no');
      $comp_add = $this->input->post('comp_add');
      $comp_email = $this->input->post('comp_email');
      $billing_contact = $this->input->post('billing_contact');
      $comp_contact = $this->input->post('comp_contact');
      $second_comp_contact = $this->input->post('second_comp_contact');
      $comp_fax = $this->input->post('comp_fax');
      $comp_post = $this->input->post('comp_post');
      $comp_state = $this->input->post('comp_state');
      $supply_outright = $this->input->post('supply_outright'); //new
      $supply_consignment = $this->input->post('supply_consignment'); //new
      $business_desc = $this->input->post('business_desc'); // new
      $bus_desc_others = $this->input->post('bus_desc_others');
      $acc_name = $this->input->post('acc_name');
      // $acc_no = $this->input->post('acc_no');
      // $acc_no_other = $this->input->post('acc_no_other');
      $isdelete = $this->input->post('isdelete');
      $created_at = $this->db->query("SELECT now() as now")->row('now');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');
      $old_form_status = $register->row('form_status');
      // $myArray = explode(',', $store_code);
      // $diff_code = array_diff($acc_no, $myArray);
      // $combine = array_merge($myArray, $diff_code);

      $save_status = $this->input->post('save_status');

      if ($save_status == '1') {
        $form_status = 'Save-Progress';
      } else {
        $form_status = 'Processing';
      }

      $data = array(
        'comp_add' => $comp_add,
        'billing_contact' => $billing_contact,
        'comp_contact' => $comp_contact,
        'second_comp_contact' => $second_comp_contact,
        'comp_fax' => $comp_fax,
        'acc_name' => $acc_name,
        // 'acc_no' =>implode(',', $acc_no),
        // 'store_code' => implode(",",$combine),
        // 'vendor_code_remark' =>implode(',', $acc_no_other),
        'org_email' => $comp_mail,
        'org_part_email' => $comp_email,
        'update_at' => $updated_at,
        'update_by' => $user_id,
        'isactive' => 0,
        'form_status' => $form_status,
        'term_download' => 1,
      );

      $this->db->where('register_guid', $register_guid);
      $this->db->update('register_new', $data);

      $data = array(
        'supply_outright' => $supply_outright,
        'supply_consignment' => $supply_consignment,
        'bus_desc_others' => $bus_desc_others,
        'business_description' => $business_desc,
        'supplier_add' => $comp_add,
        'supplier_postcode' => $comp_post,
        'supplier_state' => $comp_state

      );

      $this->db->where('register_guid', $register_guid);
      $this->db->update('set_supplier_info', $data);

      if ($form_status == 'Processing') {
        echo "<script> alert('Update Successfully.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Registration_new/register_form_edit_new?register_guid=" . $register_guid . "' </script>";
      } else {
        echo "<script> alert('Submit Successfully.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Registration_new/register_form_edit_new?register_guid=" . $register_guid . "' </script>";
      }
    } else {
      redirect('login_c');
    }
  }

  public function send_mail()
  {
    $register_guid = $this->input->post('register_guid');
    $type = $this->input->post('type');

    $register_form_details = $this->db->query("SELECT * FROM lite_b2b.register_new WHERE register_guid = '$register_guid'");
    $vendor_form_details = $this->db->query("SELECT * FROM lite_b2b.register_add_user_main WHERE register_guid = '$register_guid'");
    $now = $this->db->query("SELECT now() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');

    if (($register_form_details->num_rows() == '1') && ($type == 'register')) {
      $supplier_guid = $register_form_details->row('supplier_guid');
      $customer_guid = $register_form_details->row('customer_guid');

      $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");
      $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
      $get_logo = $this->db->query("SELECT acc_guid,acc_name,file_path FROM lite_b2b.`acc` WHERE acc_guid = '$customer_guid'");

      $subject = $customer_name->row('acc_name') . ' B2B Online Registration Form';
      $form_name = 'Online Registration Form';
      $store_logo = $get_logo->row('file_path');
      $get_pic = substr($store_logo, strrpos($store_logo, '/') + 1);
      $store_logo = 'https://tunasmanja.xbridge.my/asset/dist/img_sunshine/icon-512.png'; // need change path
      $email_name = $register_form_details->row('comp_email'); //$supplier_detail->row('supplier_name');
      $email_add = $register_form_details->row('comp_email');
      $acc_no_reg = $register_form_details->row('acc_no');
      $memo_type_reg = $register_form_details->row('memo_type');
      $form_no = $register_form_details->row('register_no');

      if ($email_add == '') {
        $data = array(
          'para1' => 1,
          'msg' => $form_no . '\nNo Email Address. Please set up first before send.',
        );
        echo json_encode($data);
        exit();
      }

      if ($acc_no_reg == '') {
        $data = array(
          'para1' => 1,
          'msg' => $form_no . '\nNo Vendor Code. Please set up first before send.',
        );
        echo json_encode($data);
        exit();
      }

      if ($memo_type_reg == '') {
        $data = array(
          'para1' => 1,
          'msg' => $form_no . '\nNo Memo Type. Please set up first before send.',
        );
        echo json_encode($data);
        exit();
      }

      $url = 'https://tunasmanja.xbridge.my/index.php/Supplier_registration/register_form_edit?link=' . $register_guid;

      $send_data = array(
        'customer_name' => $customer_name,
        'supplier_detail' => $supplier_detail,
        'get_logo' => $get_logo,
        'store_logo' => $store_logo,
        'url' => $url,
        'subject' => $subject,
        'form_name' => $form_name,
      );

      $bodyContent = $this->load->view('register/supplier_email_template', $send_data, TRUE);
      //print_r($bodyContent);die;

      //$email_name = 'jiangrui.goh@pandasoftware.my';
      //$email_add = 'jiangrui.goh@pandasoftware.my';        

      $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'register@xbridge.my','');

      $update_data = $this->db->query("UPDATE lite_b2b.register_new SET form_status = 'Send', update_at = '$now' , update_by = '$user_id' WHERE register_guid = '$register_guid' AND customer_guid = '$customer_guid'  AND supplier_guid = '$supplier_guid' ");
    }

    if (($vendor_form_details->num_rows() == '1') && ($type == 'vendor')) {
      $supplier_guid = $vendor_form_details->row('supplier_guid');
      $customer_guid = $vendor_form_details->row('customer_guid');

      $vendor_supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");
      $vendor_customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
      $get_logo = $this->db->query("SELECT acc_guid,acc_name,file_path FROM lite_b2b.`acc` WHERE acc_guid = '$customer_guid'");

      $subject = $vendor_customer_name->row('acc_name') . ' B2B User Account Creation Form';
      $form_name = 'User Account Creation Form';
      $store_logo = $get_logo->row('file_path');
      $get_pic = substr($store_logo, strrpos($store_logo, '/') + 1);
      $store_logo = 'https://tunasmanja.xbridge.my/asset/dist/img_sunshine/icon-512.png';
      $email_name = $vendor_form_details->row('comp_email'); //$vendor_supplier_detail->row('supplier_name');
      $email_add = $vendor_form_details->row('comp_email');
      $acc_no_add_on = $vendor_form_details->row('acc_no');
      $memo_type_add_on = $vendor_form_details->row('memo_type');
      $form_no = $register_form_details->row('register_no');

      if ($email_add == '') {
        $data = array(
          'para1' => 1,
          'msg' => $form_no . '\nNo Email Address. Please set up first before send.',
        );
        echo json_encode($data);
        exit();
      }

      if ($acc_no_add_on == '') {
        $data = array(
          'para1' => 1,
          'msg' => $form_no . '\nNo Vendor Code. Please set up first before send.',
        );
        echo json_encode($data);
        exit();
      }

      if ($memo_type_add_on == '') {
        $data = array(
          'para1' => 1,
          'msg' => $form_no . '\nNo Memo Type. Please set up first before send.',
        );
        echo json_encode($data);
        exit();
      }

      $url = 'https://tunasmanja.xbridge.my/index.php/Supplier_registration/vendor_form_edit?link=' . $register_guid;

      $send_data = array(
        'customer_name' => $vendor_customer_name,
        'supplier_detail' => $vendor_supplier_detail,
        'get_logo' => $get_logo,
        'store_logo' => $store_logo,
        'url' => $url,
        'subject' => $subject,
        'form_name' => $form_name,
      );

      $bodyContent = $this->load->view('register/supplier_email_template', $send_data, TRUE);
      //print_r($bodyContent);die;

      //$email_name = 'jiangrui.goh@pandasoftware.my';
      //$email_add = 'jiangrui.goh@pandasoftware.my';                

      $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'register@xbridge.my','');

      $update_data = $this->db->query("UPDATE lite_b2b.register_add_user_main SET form_status = 'Send', update_at = '$now' , update_by = '$user_id' WHERE register_guid = '$register_guid' AND customer_guid = '$customer_guid'  AND supplier_guid = '$supplier_guid' ");
    }

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Record Send Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Send Failed.',

      );
      echo json_encode($data);
    }
  }

  public function batch_send_process()
  {
    $table_main = $this->input->post('table_name1');
    $details = $this->input->post('details');
    $details = json_encode($details);
    $details = json_decode($details);
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $i = 0;
    foreach ($details as $row) {
      $register_guid = $row->register_guid;
      $register_form_details = $this->db->query("SELECT * FROM lite_b2b.$table_main WHERE register_guid = '$register_guid'");
      $vendor_form_details = $this->db->query("SELECT * FROM lite_b2b.$table_main WHERE register_guid = '$register_guid'");
      $now = $this->db->query("SELECT now() as now")->row('now');

      if (($register_form_details->num_rows() == '1') && ($table_main == 'register_new')) {
        $supplier_guid = $register_form_details->row('supplier_guid');
        $customer_guid = $register_form_details->row('customer_guid');

        $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");
        $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $get_logo = $this->db->query("SELECT acc_guid,acc_name,file_path FROM lite_b2b.`acc` WHERE acc_guid = '$customer_guid'");

        $subject = $customer_name->row('acc_name') . ' B2B Online Registration Form';
        $form_name = 'Online Registration Form';
        $store_logo = $get_logo->row('file_path');
        $get_pic = substr($store_logo, strrpos($store_logo, '/') + 1);
        $store_logo = 'https://tunasmanja.xbridge.my/asset/dist/img_sunshine/icon-512.png';
        $email_name = $register_form_details->row('comp_email'); //$supplier_detail->row('supplier_name');
        $email_add = $register_form_details->row('comp_email');
        $acc_no_reg = $register_form_details->row('acc_no');
        $memo_type_reg = $register_form_details->row('memo_type');
        $form_no = $register_form_details->row('register_no');

        if ($email_add == '') {
          $data = array(
            'para1' => 1,
            'msg' => $form_no . '\nNo Email Address. Please set up first before send.',
          );
          echo json_encode($data);
          exit();
        }

        if ($acc_no_reg == '') {
          $data = array(
            'para1' => 1,
            'msg' => $form_no . '\nNo Vendor Code. Please set up first before send.',
          );
          echo json_encode($data);
          exit();
        }

        if ($memo_type_reg == '') {
          $data = array(
            'para1' => 1,
            'msg' => $form_no . '\nNo Memo Type. Please set up first before send.',
          );
          echo json_encode($data);
          exit();
        }

        $url = 'https://tunasmanja.xbridge.my/index.php/Supplier_registration/register_form_edit?link=' . $register_guid;

        $send_data = array(
          'customer_name' => $customer_name,
          'supplier_detail' => $supplier_detail,
          'get_logo' => $get_logo,
          'store_logo' => $store_logo,
          'url' => $url,
          'subject' => $subject,
          'form_name' => $form_name,
        );

        $bodyContent = $this->load->view('register/supplier_email_template', $send_data, TRUE);
        //echo $bodyContent;die;

        //$email_name = 'jiangrui.goh@pandasoftware.my';
        //$email_add = 'jiangrui.goh@pandasoftware.my';                
        $subject = $customer_name->row('acc_name') . ' B2B Online Registration Form';

        $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'register@xbridge.my','');

        $update_data = $this->db->query("UPDATE lite_b2b.$table_main SET form_status = 'Send', update_at = '$now' , update_by = '$user_id' WHERE register_guid = '$register_guid' AND customer_guid = '$customer_guid'  AND supplier_guid = '$supplier_guid' ");

        $i++;
      } // close register_new

      if (($vendor_form_details->num_rows() == '1') && ($table_main == 'register_add_user_main')) {
        //print_r('123'); die;
        $supplier_guid = $vendor_form_details->row('supplier_guid');
        $customer_guid = $vendor_form_details->row('customer_guid');

        $vendor_supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");
        $vendor_customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $get_logo = $this->db->query("SELECT acc_guid,acc_name,file_path FROM lite_b2b.`acc` WHERE acc_guid = '$customer_guid'");

        $subject = $vendor_customer_name->row('acc_name') . ' B2B User Account Creation Form';
        $form_name = 'User Account Creation Form';
        $store_logo = $get_logo->row('file_path');
        $get_pic = substr($store_logo, strrpos($store_logo, '/') + 1);
        $store_logo = 'https://tunasmanja.xbridge.my/asset/dist/img_sunshine/icon-512.png';
        $email_name = $vendor_form_details->row('comp_email'); //$vendor_supplier_detail->row('supplier_name');
        $email_add = $vendor_form_details->row('comp_email');
        $acc_no_add_on = $vendor_form_details->row('acc_no');
        $memo_type_add_on = $vendor_form_details->row('memo_type');
        $form_no = $register_form_details->row('register_no');

        if ($email_add == '') {
          $data = array(
            'para1' => 1,
            'msg' => $form_no . '\nNo Email Address. Please set up first before send.',
          );
          echo json_encode($data);
          exit();
        }

        if ($acc_no_add_on == '') {
          $data = array(
            'para1' => 1,
            'msg' => $form_no . '\nNo Vendor Code. Please set up first before send.',
          );
          echo json_encode($data);
          exit();
        }

        if ($memo_type_add_on == '') {
          $data = array(
            'para1' => 1,
            'msg' => $form_no . '\nNo Memo Type. Please set up first before send.',
          );
          echo json_encode($data);
          exit();
        }

        $url = 'https://tunasmanja.xbridge.my/index.php/Supplier_registration/vendor_form_edit?link=' . $register_guid;

        $send_data = array(
          'customer_name' => $vendor_customer_name,
          'supplier_detail' => $vendor_supplier_detail,
          'get_logo' => $get_logo,
          'store_logo' => $store_logo,
          'url' => $url,
          'subject' => $subject,
          'form_name' => $form_name,
        );

        $bodyContent = $this->load->view('register/supplier_email_template', $send_data, TRUE);
        //echo $bodyContent;die;

        //$email_name = 'jiangrui.goh@pandasoftware.my';
        //$email_add = 'jiangrui.goh@pandasoftware.my';                
        $subject = $vendor_customer_name->row('acc_name') . ' B2B User Account Creation Form';

        $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'register@xbridge.my','');

        $update_data = $this->db->query("UPDATE lite_b2b.$table_main SET form_status = 'Send', update_at = '$now' , update_by = '$user_id' WHERE register_guid = '$register_guid' AND customer_guid = '$customer_guid'  AND supplier_guid = '$supplier_guid' ");
        $i++;
      } // close add on            
    } // close foreach

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => $i . ' Record(s) Send Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Send Failed.',

      );
      echo json_encode($data);
    }
  }

  //edited
  public function transaction()
  {
    $comp_name = $this->input->post('comp_name');
    $memo_type = $this->input->post('memo_type');
    $register_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_guid")->row('register_guid');
    $register_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS register_c_guid")->row('register_c_guid');
    $supplier_query = $this->db->query("SELECT a.* FROM set_supplier a WHERE supplier_guid = '$comp_name' ORDER BY a.supplier_name ASC");
    $supplier_guid = $supplier_query->row('supplier_guid');
    $supplier_name = $supplier_query->row('supplier_name');
    $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_info_guid")->row('supplier_info_guid');
    $active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='" . $_SESSION['customer_guid'] . "'")->row('isactive');
    $supplier = $this->db->query("SELECT a.*,b.* FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='" . $_SESSION['customer_guid'] . "'  ")->row('supplier_guid');
    // $retailer = $this->db->query("SELECT acc_name FROM  acc c INNER JOIN register_new a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('acc_name');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $session_id = $this->db->query("SELECT supplier_group_guid FROM set_supplier_group a WHERE a.`supplier_guid`= '$supplier_guid'")->row('supplier_group_guid');
    //a.`customer_guid`= '".$_SESSION['customer_guid']." ' AND
    //$re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM `register_new`  WHERE  SUBSTRING(register_no, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('re_no');
    $todaydate = date('Ymd');
    $todaydate2 = substr($todaydate, 2);
    $re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM lite_b2b.`register_new`  WHERE  SUBSTRING(register_no, - 10, 6) = '$todaydate2' ")->row('re_no');
    $register_no = $this->db->query("SELECT concat( '$todaydate2', '$re_no' ) as refno")->row('refno');
    $comp_no = $this->input->post('comp_no');
    $acc_name = $this->input->post('acc_name');
    $acc_no = $this->input->post('acc_no');

    $comp_email = $this->input->post('comp_email');
    //$comp_post = $this->input->post('comp_post');
    //$comp_state = $this->input->post('comp_state');
    $create_at = $this->db->query("SELECT now() as now")->row('now');
    $update_at = $this->db->query("SELECT now() as now")->row('now');

    $contact1 = $this->input->post('contact1');
    $contact2 = $this->input->post('contact2');

    $acc_no = implode(",", $acc_no);
    $acc_no = "" . $acc_no . "";

    $check_transaction = $this->db->query("SELECT register_guid FROM register_new WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' AND supplier_guid = '$supplier_guid' AND form_status != 'Terminated'");

    if ($check_transaction->num_rows() > 0) {
      echo "<script> alert('Error create new transaction due to more than one supplier under the retailer.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/Registration_new/register_admin' </script>";
      exit();
    }

    $data = array(
      'supplier_info_guid' => $supplier_info_guid,
      //'supplier_add' => $comp_add,
      //'supplier_postcode' => $comp_post,
      //'supplier_state' => $comp_state,
      'register_guid' => $register_guid
    );

    $this->db->insert('set_supplier_info', $data);

    $data = array(
      'register_guid' => $register_guid,
      'customer_guid' => $_SESSION['customer_guid'],
      'supplier_guid' => $supplier_guid,
      'create_at' => $create_at,
      'create_by' => $user_id,
      'update_at' => $update_at,
      'update_by' => $user_id,
      'isactive' => $active,
      'comp_email' => $comp_email,
      'register_no' => $register_no,
      'comp_name' => $supplier_name,
      'comp_no' => $comp_no,
      'acc_name' => $acc_name,
      'acc_no' => $acc_no,
      'store_code' => $acc_no,
      'isactive' => 1,
      'memo_type' => $memo_type,
      'comp_contact' => $contact1,
      'second_comp_contact' => $contact2,
    );

    $this->db->insert('register_new', $data);

    redirect('Registration_new/register_admin');
  }

  // for edit register admin email address
  public function edit_reg_app()
  {
    $edit_reg_guid = $this->input->post('edit_reg_guid');
    $edit_email = $this->input->post('edit_email');
    $edit_acc_no = $this->input->post('edit_acc_no');
    $edit_acc_no = implode(',', $edit_acc_no);
    $memo_type = $this->input->post('edit_memo_type');
    $form_status = $this->input->post('edit_form_status');
    $edit_comp_contact = $this->input->post('edit_comp_contact');
    $edit_sec_comp_contact = $this->input->post('edit_sec_comp_contact');
    $edit_supp_name = $this->input->post('edit_supp_name');
    $edit_comp_no = $this->input->post('edit_comp_no');

    $outright_template = $this->input->post('edit_outright_template');
    $consignment_template = $this->input->post('edit_consign_template');
    $cap_template = $this->input->post('edit_cap_template');
    $waive_template = $this->input->post('edit_waive_template');
    $outright_start_date = $this->input->post('edit_outright_start');
    $consign_start_date = $this->input->post('edit_consign_start');
    $cap_start_date = $this->input->post('edit_cap_start');
    $cap_end_date = $this->input->post('edit_cap_end');
    $waive_start_date = $this->input->post('edit_waive_start');
    $waive_end_date = $this->input->post('edit_waive_end');

    $edit_is_invoice = $this->input->post('edit_is_invoice');

    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $updated_at = $this->db->query("SELECT now() as now")->row('now');

    if($form_status != 'Terminated' && $form_status != 'Advance') // due to register new data created by system no have set supplier info
    {
      $check_data = $this->db->query("SELECT a.form_status FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_info b ON a.register_guid = b.register_guid WHERE a.register_guid = '$edit_reg_guid' ");

      if($check_data->num_rows() == 0)
      {
        $data = array(
          'para1' => 1,
          'msg' => 'Data No Found.',
  
        );
        echo json_encode($data);
        exit();
      }
    }

    //default value set at view when is select empty..
    if ($form_status == 'default') {
      $form_status = $check_data->row('form_status');
    }

    $data_template = array(   
      'outright_template' => $outright_template,
      'consignment_template' => $consignment_template,
      'cap_template' => $cap_template,
      'waive_template' => $waive_template,
      'outright_start_date' => $outright_start_date,
      'consign_start_date' => $consign_start_date,
      'cap_start_date' => $cap_start_date,
      'cap_end_date' => $cap_end_date,
      'waive_start_date' => $waive_start_date,
      'waive_end_date' => $waive_end_date,
    );

    $data = array(
      'acc_no' => $edit_acc_no,
      'store_code' => $edit_acc_no,
      'memo_type' => $memo_type,
      'comp_email' => $edit_email,
      'comp_contact' => $edit_comp_contact,
      'second_comp_contact' => $edit_sec_comp_contact,
      'update_at' => $updated_at,
      'update_by' => $user_id,
      'form_status' => $form_status,
      'comp_name' => $edit_supp_name,
      'comp_no' => $edit_comp_no,
      'isinvoice' => $edit_is_invoice,
    );

    if($form_status != 'Terminated')
    {
      $this->db->where('register_guid', $edit_reg_guid);
      $this->db->update('set_supplier_info', $data_template);

      $this->db->where('register_guid', $edit_reg_guid);
      $this->db->update('register_new', $data);
    }
    else
    {
      $this->db->where('register_guid', $edit_reg_guid);
      $this->db->update('register_new', $data);
    }

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Update Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'No Data Update.',

      );
      echo json_encode($data);
    }
  }

  // for edit register admin email address
  public function edit_reg_app_vendor()
  {
    $edit_reg_guid = $this->input->post('edit_reg_guid');
    $edit_email = $this->input->post('edit_email');
    $edit_acc_no = $this->input->post('edit_acc_no');
    $edit_acc_no = implode(',', $edit_acc_no);
    $memo_type = $this->input->post('edit_memo_type');
    $edit_form_merge = $this->input->post('edit_form_merge');
    $edit_form_status = $this->input->post('edit_form_status');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $updated_at = $this->db->query("SELECT now() as now")->row('now');

    $data = array(
      'acc_no' => $edit_acc_no,
      'store_code' => $edit_acc_no,
      'memo_type' => $memo_type,
      'comp_email' => $edit_email,
      'form_merge' => $edit_form_merge,
      'form_status' => $edit_form_status,
      'update_at' => $updated_at,
      'update_by' => $user_id,
    );

    $this->db->where('register_guid', $edit_reg_guid);
    $this->db->update('register_add_user_main', $data);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Update Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'No Data Update.',

      );
      echo json_encode($data);
    }
  }

  //created by jr for fetch reg no
  public function fetch_reg_no()
  {
    $customer_guid = $_SESSION['customer_guid'];
    $type_val = $this->input->post('type_val');
    $acc_no_array = $this->input->post('acc_no_array');
    $column_add = '';
    if ($acc_no_array != '') {
      $acc_no_array = implode("','", $acc_no_array);
      $acc_no_array = "'" . $acc_no_array . "'";
      $column_add = ",IF(a.`code` IN ($acc_no_array) , '1' , '0') AS selected";
    }

    $Code = $this->db->query("SELECT a.reg_no, a.supplier_guid FROM set_supplier a WHERE  a.`supplier_guid` = '$type_val' GROUP BY reg_no");
    $vendor = $this->db->query("SELECT a.`code` AS vendor_code,a.name $column_add FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY a.customer_guid, a.`code`,a.type ");

    $data = array(
      'Code' => $Code->result(),
      'vendor' => $vendor->result(),
    );

    echo json_encode($data);
  }

  //edited
  public function proceed_vendor()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    // echo $customer_guid;die;
    $table_child = $this->input->post('table_name1');
    $table_main = $this->input->post('table_name2');

    //$register = $this->db->query("SELECT * FROM $table_child a INNER JOIN $table_main b ON a.register_guid = b.register_guid LEFT JOIN set_supplier_info d ON d.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");

    $register = $this->db->query("SELECT a.*, b.* FROM $table_main a LEFT JOIN set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid'"); //added this

    $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    $ven_code = '';
    $vendor_code1 = $register->row('acc_no');
    $vendor_code1 =  explode(',', $vendor_code1);
    $msg = '';
    foreach ($vendor_code1 as $supplier_group_name) {
      $value = trim($supplier_group_name);
      $check_name = $this->db->query("SELECT * FROM lite_b2b.`set_supplier_group` a WHERE supplier_group_name = '$value' AND supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

      if ($check_name->num_rows() > 0) {
        $ven_code .= $supplier_group_name;
        $ven_code = "" . $ven_code . " ";
        $result = explode(' ', $ven_code);
        $result = implode(' ', $result);


        $para1 = 0;
        $msg .= 'Vendor Code :' . $supplier_group_name . ' already exists.\n';
      }

      $supplier_group_name = trim($supplier_group_name);
      if ($check_name->num_rows() <= 0) {
        $register_update = $this->db->query("UPDATE $table_main SET form_status = 'Processing' WHERE `register_guid` = '$register_guid' AND customer_guid = '$customer_guid' AND form_status = 'New' "); //added this

        $insert_value = array(
          'supplier_guid' => $supplier_guid,
          'supplier_group_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
          'supplier_group_name' => $supplier_group_name,
          'customer_guid' => $customer_guid,
          'backend_supcus_guid' => $this->db->query("SELECT supcus_guid as  backend_supcus_guid from b2b_summary.supcus where customer_guid =  '" . $customer_guid . "' and code = '$supplier_group_name'")->row('backend_supcus_guid'),
          'backend_supplier_code' =>  $supplier_group_name,
          'created_at' => $this->db->query("SELECT now() as today")->row('today'),
          'created_by' => $_SESSION['userid'],
          'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
          'updated_by' => $_SESSION['userid'],
        );
        $this->db->insert('lite_b2b.set_supplier_group', $insert_value);

        $public_ip = $this->db->query("SELECT a.`public_ip`,a.`public_ip_3`
        FROM lite_b2b.`acc` AS a
        WHERE a.`acc_guid` = '$customer_guid'");

        $username = 'admin'; //get from rest.php
        $password = '1234'; //get from rest.php
        $insert_sql_query = '1';
        // ninso update HHQ and HQ 
        if ($customer_guid == '599348EDCB2F11EA9A81000C29C6CEB2') {
          $url = array(
            'url' => $public_ip->row('public_ip') . '/lite_panda_b2b_checking_rest/index.php/Update_b2b_flag',
            'url_2' => $public_ip->row('public_ip_3') . '/lite_panda_b2b_checking_rest/index.php/Update_b2b_flag',
          );
        } else {
          // $url = array(
          //   'url' => $public_ip->row('public_ip') . '/lite_panda_b2b_checking_rest/index.php/Update_b2b_flag',
          // );
        }

        // disabled url 
        // foreach ($url as $key => $value) {

        //   $ch = curl_init($value);
        //   curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //   curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
        //   curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //   curl_setopt($ch, CURLOPT_POST, 1);
        //   curl_setopt($ch, CURLOPT_POSTFIELDS, array('vendor_code' => $supplier_group_name, 'type' => 'create', 'insert_sql_query' => $insert_sql_query));

        //   $result = curl_exec($ch);

        //   $output =  json_decode($result, true);

        //   $log = array(
        //     'guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
        //     'customer_guid' => $customer_guid,
        //     'status' => ($output['status'] == 'true') ? 'Success' : 'Error',
        //     'reason' => $output['message'],
        //     'vendor_code' => json_encode($supplier_group_name),
        //     'module' => 'manual',
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'created_by' =>  $_SESSION['userid'],
        //   );

        //   $this->db->insert('lite_b2b.mapping_vendor_code_error_log', $log);
        // }

        $para1 = 0;
        $msg .= 'Vendor Code :' . $supplier_group_name . ' Mapped Successfully.\n';

        $email_group = $this->db->query("SELECT a.user_id as email,a.user_name as first_name FROM set_user a INNER JOIN set_user_group b ON a.user_group_guid = b.user_group_guid INNER JOIN set_user_module c ON b.user_group_guid = c.user_group_guid INNER JOIN set_module d ON c.module_guid = d.module_guid INNER JOIN set_module_group e ON d.module_group_guid = e.module_group_guid WHERE a.isactive = 1 AND a.acc_guid = '$customer_guid' AND e.module_group_name = 'Panda B2B' AND c.isenable = 1 AND d.module_code = 'RENSS' AND a.acc_guid != 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND a.acc_guid != '1F90F5EF90DF11EA818B000D3AA2CAA9' AND a.acc_guid != '599348EDCB2F11EA9A81000C29C6CEB2' AND a.acc_guid != '907FAFE053F011EB8099063B6ABE2862' AND a.acc_guid != '13EE932D98EB11EAB05B000D3AA2838A' GROUP BY a.user_guid");
        // AND a.acc_guid != 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND a.acc_guid != '1F90F5EF90DF11EA818B000D3AA2CAA9' AND a.acc_guid != '599348EDCB2F11EA9A81000C29C6CEB2'
        // print_r($email_group->result());die;
        $ver_disabled = '0'; //$email_group->num_rows() > 0
        if ($ver_disabled == 'disabled') {
          $b2b_summary_table = 'b2b_summary';
          $email_name = $email_group->row('first_name');
          $email_add = $email_group->row('email');
          $date = $this->db->query("SELECT now() as now")->row('now');
          $supplier_detail = $this->db->query("SELECT * FROM set_supplier WHERE supplier_guid = '$supplier_guid'");
          $supplier_vendor_code_detail = $this->db->query("SELECT code as code,name as name from $b2b_summary_table.supcus where customer_guid =  '" . $_SESSION['customer_guid'] . "' and code = '$supplier_group_name'");
          $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
          $url = 'https://tunasmanja.xbridge.my/';

          $bodyContent = '<div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-info">
                                            B2B Notification
                                        </h3>
                                        <p class="lead">
                                        Following suppliers has registered @ TMG B2B Portal for Retailer: ' . $customer_name->row('acc_name') . '<br>
                                             Supplier: ' . $supplier_detail->row('supplier_name') . ' (' . $supplier_detail->row('reg_no') . ')<br>Vendor Code: ' . $supplier_vendor_code_detail->row('code') . '<br>Date: ' . $date . '
                                            <br>Note: Please turn on the B2B Flag @ Panda Backend Supplier Setup.<br>
                                            Regards,<br>
                                            <a href="' . $url . '"> TMG B2B Portal</a>
                                        </p>
                                    </div>
                                </div>
                            </div>';
          // echo $bodyContent;die;
          foreach ($email_group->result() as $row) {
            $email_name = $row->first_name;
            $email_add = $row->email;
            // $email_name = 'danielweng57@gmail.com';
            // $email_add = 'danielweng57@gmail.com';                
            $subject = 'Supplier Subscription';
            // echo $email_name,$email_add;die;
            // $this->send_to_manager($email_add, $email_name, $date, $bodyContent);
            $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'register@xbridge.my','');
            // echo 1;die;
            // echo $email_name.$email_add.'<br>';
          }
        }
      } else {
        $public_ip = $this->db->query("SELECT a.`public_ip`,a.`public_ip_3`
        FROM lite_b2b.`acc` AS a
        WHERE a.`acc_guid` = '$customer_guid'");

        $username = 'admin'; //get from rest.php
        $password = '1234'; //get from rest.php
        $insert_sql_query = '1';
        // ninso update HHQ and HQ 
        if ($customer_guid == '599348EDCB2F11EA9A81000C29C6CEB2') {
          $url = array(
            'url' => $public_ip->row('public_ip') . '/lite_panda_b2b_checking_rest/index.php/Update_b2b_flag',
            'url_2' => $public_ip->row('public_ip_3') . '/lite_panda_b2b_checking_rest/index.php/Update_b2b_flag',
          );
        } else {
          // $url = array(
          //   'url' => $public_ip->row('public_ip') . '/lite_panda_b2b_checking_rest/index.php/Update_b2b_flag',
          // );
        }

        // disabled url 
        // foreach ($url as $key => $value) {

        //   $ch = curl_init($value);
        //   curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //   curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
        //   curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //   curl_setopt($ch, CURLOPT_POST, 1);
        //   curl_setopt($ch, CURLOPT_POSTFIELDS, array('vendor_code' => $supplier_group_name, 'type' => 'create', 'insert_sql_query' => $insert_sql_query));

        //   $result = curl_exec($ch);

        //   $output =  json_decode($result, true);

        //   $log = array(
        //     'guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
        //     'customer_guid' => $customer_guid,
        //     'status' => ($output['status'] == 'true') ? 'Success' : 'Error',
        //     'reason' => $output['message'],
        //     'vendor_code' => json_encode($supplier_group_name),
        //     'module' => 'manual',
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'created_by' =>  $_SESSION['userid'],
        //   );

        //   $this->db->insert('lite_b2b.mapping_vendor_code_error_log', $log);
        // }

        $para1 = 0;
        $msg .= 'Vendor Code :' . $supplier_group_name . ' Flow HQ Successfully.\n';

        // continue;
      }
    }
    $data = array(
      'para1' => $para1,
      'msg' => $msg,
    );
    $error = $this->db->affected_rows();
    $check_status = 1;
    if ($check_status > 0) {
      echo json_encode($data);
    } else if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error',

      );
      echo json_encode($data);
    }
  }

  //edited ( user details )
  public function proceed_user()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $table_child = $this->input->post('table_name1');
    $table_main = $this->input->post('table_name2');
    $table_child_mapping = $this->input->post('table_name3');
    //print_r($table_main); die;
    //$register = $this->db->query("SELECT * FROM $table_child a INNER JOIN $table_main b ON a.register_guid = b.register_guid LEFT JOIN set_supplier_info d ON d.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid' ");

    $register = $this->db->query("SELECT a.*, b.* FROM $table_main a LEFT JOIN set_supplier_info b ON a.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid'"); //added this

    $register_child = $this->db->query("SELECT a.*, b.`register_mapping_guid`, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code FROM $table_child a LEFT JOIN $table_child_mapping b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid ORDER BY a.created_at ASC"); //added this

    $register_child_training = $this->db->query("SELECT a.* FROM $table_child a WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' "); //added this

    $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    // $user_group_guid = 'F6E92188DF5D11E9814B000D3AA2838A';
    //INNER JOIN set_supplier_group c ON c.supplier_group_guid = b.session_id

    $get_default_pass = $this->db->query("SELECT MIN(supplier_group_name) as default_pass FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'")->row('default_pass');

    $details = $this->input->post('details');
    $details = json_encode($details);
    $details = json_decode($details);
    //print_r($details); die;  
    // print_r($details[1]->user_group);die;
    // echo $this->db->last_query();die;

    // echo $get_default_pass->row('default_pass');die;

    $ven_name = '';
    foreach ($register_child->result() as $key => $row) {
      $vendor_name[] = $row->ven_name;
      $vendor_email[] = $row->ven_email;
    }
    //$vendor_name = $register_child->row('ven_name');
    //$vendor_name =  explode('/', $vendor_name); // explode / data
    //$vendor_email = $register_child->row('ven_email');
    //$vendor_email =  explode('/', $vendor_email); // explode / data

    $vendor_array = array_combine($vendor_name, $vendor_email); // combine it
    $msg = '';
    $para = 0;
    $i = 0;
    foreach ($vendor_array as $vendor_name => $vendor_email) {
      $insert_loc = 0;
      $user_group_guid = $details[$i]->user_group;
      $i_loc_group = $details[$i]->loc_group;
      $i++;

      if ($i_loc_group == '') {
        $para1 = 1;
        $msg .= 'Email Address :' . $vendor_email . ' duplicate successfully\n';
        $insert_loc = 0;
        continue;
      }
      // print_r($user_group_guid);
      // print_r($vendor_email);
      // $check_data = $this->db->query("SELECT a.*,d.`module_group_name`,e.`user_group_name` FROM set_user a INNER JOIN set_user_module b ON a.`user_group_guid` = b.`user_group_guid` INNER JOIN set_module c ON c.`module_guid` = b.`module_guid` INNER JOIN set_module_group d ON d.`module_group_guid` = c.`module_group_guid` AND d.`module_group_guid` = a.`module_group_guid` INNER JOIN set_user_group e ON e.`user_group_guid` = a.`user_group_guid` WHERE d.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND a.`user_id` = '$vendor_email' GROUP BY a.`user_id`;");
      $check_data = $this->db->query("SELECT a.* FROM set_user a WHERE a.`module_group_guid` = '" . $_SESSION['module_group_guid'] . "' AND a.`user_id` = '$vendor_email' AND a.acc_guid = '$customer_guid' GROUP BY a.`user_id`");
      //echo $this->db->last_query(); die;
      // echo $check_data->num_rows();die;
      // if($check_data->num_rows() > 0)
      // { 

      //   $check_data = $this->db->query("SELECT a.* FROM set_user a WHERE a.`module_group_guid` = '".$_SESSION['module_group_guid']."' AND a.`user_id` = '$vendor_email' GROUP BY a.`user_id` ");
      //   $ven_name .= $vendor_name;
      //   $ven_name = "".$ven_name." ";
      //   $result = explode(' ', $vendor_email );
      //   $result = implode(' ', $result );


      //   $para1 = 1;
      //   $msg .= 'Email address :'.$result.' already exists\n';

      // };

      if ($check_data->num_rows() <= 0) {
        $check_status = 1;

        $register_update = $this->db->query("UPDATE $table_main SET form_status = 'Processing' WHERE `register_guid` = '$register_guid' AND customer_guid = '$customer_guid' AND form_status = 'New' "); //added this

        $check_data2 = $this->db->query("SELECT a.* FROM set_user a WHERE a.`module_group_guid` = '" . $_SESSION['module_group_guid'] . "' AND a.`user_id` = '$vendor_email' AND a.acc_guid != '$customer_guid' GROUP BY a.`user_guid`");

        $check_data3 = $this->db->query("SELECT a.* FROM set_user a WHERE a.`module_group_guid` = '" . $_SESSION['module_group_guid'] . "' AND a.`user_id` = '$vendor_email' AND a.acc_guid = '$customer_guid' ");
        $ven_name .= $vendor_name;
        $ven_name = "" . $ven_name . " ";
        // $result = explode(' ', $vendor_email );
        // $result = implode(' ', $result );

        if ($check_data3->num_rows() >= 1) 
        {
          $para1 = 1;
          $msg .= 'Email address :' . $vendor_email . ' error - existed twice with same unique id.\n';
        }
        else if ($check_data2->num_rows() == 1) 
        {
          $i_user_guid = $check_data2->row('user_guid');
          $data = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => $check_data2->row('user_password'),
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
          );
          $this->db->insert('lite_b2b.set_user', $data);

          $data_temp = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => $check_data2->row('user_password'),
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
          );
          $this->db->insert('lite_b2b.set_user_temp_record', $data_temp);

          $para1 = 0;
          $msg .= 'Email Address :' . $vendor_email . ' duplicate successfully\n';
          $insert_loc = 1;
        } 
        else if ($check_data2->num_rows() > 1) {
          $para1 = 1;
          $msg .= 'Email address :' . $vendor_email . ' error - existed twice with same unique id.\n';
        } 
        else 
        {
          $i_user_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
          $data = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => md5($get_default_pass),
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
          );
          $this->db->insert('lite_b2b.set_user', $data);

          $data_temp = array(
            //if b2b, acc_guid will be using session customer_guid
            'acc_guid' => $customer_guid,
            'module_group_guid' => $_SESSION['module_group_guid'],
            'isactive' => 1,
            'user_guid' => $i_user_guid,
            'user_group_guid' => $user_group_guid,
            'user_id' => $vendor_email,
            'user_name' => $vendor_name,
            'user_password' => $get_default_pass,
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'created_by' => $_SESSION['userid'],
            'limited_location' => 0,
          );
          $this->db->insert('lite_b2b.set_user_temp_record', $data_temp);

          $data_rest_list = array(
            //if b2b, acc_guid will be using session customer_guid
            'reset_guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid'),
            'customer_guid' => $customer_guid,
            'user_guid' => $i_user_guid,
            'email_id' => $vendor_email,
            'is_reset' => 0,
            'reset_at' => '1001-01-01 00:00:00',
            'created_by' => $_SESSION['userid'],
            'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'updated_by' => $_SESSION['userid'],
            'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
            'ip' => '',
            'browser' => '',
          );
          $this->db->insert('lite_b2b.reset_pass_list', $data_rest_list);

          $para1 = 0;
          $msg .= 'Email Address :' . $vendor_email . ' insert successfully\n';
          $insert_loc = 1;
        }
        if ($insert_loc == 1) {
          $branch_array = explode(',', $i_loc_group);
          $this->db->query("DELETE FROM set_user_branch WHERE user_guid = '$i_user_guid' AND acc_guid = '$customer_guid'");

          foreach ($branch_array as $i_branch_code) {
            $i_branch_details = $this->db->query("SELECT b.acc_guid,a.branch_guid FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid AND a.isactive = '1' WHERE b.acc_guid = '$customer_guid' AND a.branch_code = '$i_branch_code'");
            // print_r($i_branch_details->result());
            if ($i_branch_details->num_rows() > 0) {
              $data_branch = array(
                'acc_guid' =>  $customer_guid,
                'branch_guid' => $i_branch_details->row('branch_guid'),
                'user_guid' => $i_user_guid,
                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'created_by' => 'reg_form',
                'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'updated_by' => $_SESSION['userid'],
              );
              $this->db->insert('set_user_branch', $data_branch);
            }
          }
        }
      } else {
        $check_status = 1;
        $para1 = 1;
        $msg .= 'Email address :' . $vendor_email . ' already existsed.\n';
      }
      $para = $para + $para1;
      // echo $check_data->num_rows();die;
    }
    if ($para == 0) {
      $para = 0;
    } else {
      $para = 1;
    }

    $data = array(
      'para1' => $para,
      'msg' => $msg,
    );
    // echo $check_data->num_rows();die;
    $error = $this->db->affected_rows();



    if ($check_status > 0) {
      echo json_encode($data);
    } else if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error',

      );
      echo json_encode($data);
    }
  }

  public function proceed_mapping()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $table_child = $this->input->post('table_name1');
    $table_main = $this->input->post('table_name2');
    // $vendor_email = $this->input->post('vendor_email');
    // $vendor_code = $this->input->post('vendor_code');

    $details = $this->input->post('details');
    $details = json_encode($details);
    $details = json_decode($details);

    $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    $created_by = $this->session->userdata('userid');

    // print_r($details);
    // die;

    // print_r($vendor_email); echo '<br>';
    // print_r($vendor_code);  
    foreach ($details as $row4) {
      $get_supplier_code = $row4->vendor_code;
      // print_r(count($row4->vendor_code));die;
      if ($get_supplier_code == '') {
        $data = array(
          'para1' => 1,
          'msg' => 'Vendor code cannot be empty',
        );
        echo json_encode($data);
        die;
      }

      foreach ($get_supplier_code as $supplier_group_name) {
        $supplier = $this->db->query("SELECT supplier_guid FROM set_supplier_group WHERE supplier_group_name = '$supplier_group_name' and customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid'");
        if ($supplier->num_rows() <= 0) {
          // echo "<script> alert('no vendor code mapped') </script>";
          // die;
          $data = array(
            'para1' => 1,
            'msg' => 'no vendor code mapped',
          );
          echo json_encode($data);
          die;
        }
      }
    }

    $string_code = '';
    foreach ($details as $row) {
      // echo '1'.'<br>';
      // $vendor_code = $row->vendor_code;
      // foreach($vendor_code as $supplier_group_name)
      // {
      //   echo $supplier_group_name.'<br>';
      // }
      // echo $row->vendor_email.'<br>';

      //$user_id = $row->user_id;// if you get from query
      $user_id = $row->vendor_email; //if you post from table
      $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$user_id' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
      // echo $get_user_guid->row('user_guid').'<br>';
      $check_relationship = $this->db->query("SELECT * FROM set_supplier_user_relationship where user_guid = '$get_user_guid' and supplier_guid = '$supplier_guid' and customer_guid = '$customer_guid'");

      if ($check_relationship->num_rows() > 0) {
        // echo "<script> alert('need miss loo confirmation tommorrow') </script>";
        // die;
        // $para1 = 0;
        $get_supplier_code = $row->vendor_code;

        if ($supplier_guid == '' || $customer_guid == '' || $get_user_guid == '' || $supplier_guid == null || $customer_guid == null || $get_user_guid == null) {
          $data = array(
            'para1' => 1,
            'msg' => 'Value Empty Error',
          );
          echo json_encode($data);
          die;
        }

        $this->db->query("DELETE FROM lite_b2b.set_supplier_user_relationship WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid' AND user_guid='$get_user_guid'");

        foreach ($get_supplier_code as $supplier_group_name) {
          // echo '2'.'<br>';
          $supplier_group_name = $supplier_group_name;
          $supplier_group_guid = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid' AND supplier_group_name = '$supplier_group_name'")->row('supplier_group_guid');

          // $data_test = $this->db->query("SELECT '$customer_guid' as customer_guid
          //     , '$supplier_guid' as supplier_guid
          //     , '".$supplier_group_name."' as supplier_group_guid
          //     , '$get_user_guid' as user_guid
          //     , now()
          //     , '".$this->session->userdata('userid')."'
          //     ");

          $data = array(
            'customer_guid' => $customer_guid,
            'supplier_guid' => $supplier_guid,
            'supplier_group_guid' => $supplier_group_guid,
            'user_guid' => $get_user_guid,
            'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            // 'created_by' => $this->session->userdata('userid'),
            'created_by' => $created_by,
          );
          // print_r($data);

          $this->db->replace('lite_b2b.set_supplier_user_relationship', $data);

          $register_update = $this->db->query("UPDATE $table_main SET form_status = 'Processing' WHERE `register_guid` = '$register_guid' AND customer_guid = '$customer_guid' AND form_status = 'New' "); //added this

          $this->db->query("UPDATE lite_b2b.set_user SET supplier_guid = '$supplier_guid' WHERE user_guid = '$get_user_guid' AND acc_guid = '$customer_guid'");

          // $string_code .= 'need miss loo confirmation tommorrow\n';  
          $string_code .= $supplier_group_name . ' delete and mapped to ' . $user_id . '\n';
        }
      } else {
        // $get_supplier_code = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");
        $get_supplier_code = $row->vendor_code;
        foreach ($get_supplier_code as $supplier_group_name) {
          // echo '2'.'<br>';
          $supplier_group_name = $supplier_group_name;
          $supplier_group_guid = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid' AND supplier_group_name = '$supplier_group_name'")->row('supplier_group_guid');

          // $data_test = $this->db->query("SELECT '$customer_guid' as customer_guid
          //     , '$supplier_guid' as supplier_guid
          //     , '".$supplier_group_name."' as supplier_group_guid
          //     , '$get_user_guid' as user_guid
          //     , now()
          //     , '".$this->session->userdata('userid')."'
          //     ");

          $data = array(
            'customer_guid' => $customer_guid,
            'supplier_guid' => $supplier_guid,
            'supplier_group_guid' => $supplier_group_guid,
            'user_guid' => $get_user_guid,
            'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            // 'created_by' => $this->session->userdata('userid'),
            'created_by' => $created_by,
          );
          // print_r($data);

          $this->db->replace('lite_b2b.set_supplier_user_relationship', $data);

          $register_update = $this->db->query("UPDATE $table_main SET form_status = 'Processing' WHERE `register_guid` = '$register_guid' AND customer_guid = '$customer_guid' AND form_status = 'New' "); //added this

          $this->db->query("UPDATE lite_b2b.set_user SET supplier_guid = '$supplier_guid' WHERE user_guid = '$get_user_guid' AND acc_guid = '$customer_guid'");

          $string_code .= $supplier_group_name . ' mapped to ' . $user_id . '\n';
        }
      }
    }

    $para1 = 0;
    $msg = $string_code;
    $check_status = 1;
    $data = array(
      'para1' => $para1,
      'msg' => $msg,
    );


    // $vendor_email = explode(' ', $vendor_email);

    // foreach($vendor_email as $row)
    // {
    //   //$user_id = $row->user_id;// if you get from query
    //   $user_id = $row;//if you post from table
    //   $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$user_id' AND customer_guid = '$customer_guid' LIMIT 1");
    //   $check_relationship = $this->db->query("SELECT * FROM set_supplier_user_relationship where user_guid = '$get_user_guid' and supplier_guid = '$supplier_guid' and customer_guid = '$customer_guid'");

    //   if($check_relationship->num_rows() > 0)
    //   {
    //     // echo "<script> alert('need miss loo confirmation tommorrow') </script>";
    //     // die;
    //     $data = array(
    //     'para1' => 1,
    //     'msg' => 'need miss loo confirmation tommorrow',
    //     );    
    //   }
    //   else
    //   {
    //     $get_supplier_code = $this->db->query("SELECT * FROM set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

    //     foreach($get_supplier_code as $row3)
    //     {
    //       $supplier_group_guid = $row3->supplier_group_guid;
    //       $this->db->query("REPLACE INTO set_supplier_user_relationship 
    //           SELECT '$customer_guid' as customer_guid
    //           , '$supplier_guid' as supplier_guid
    //           , '".$supplier_group_guid."' as supplier_group_guid
    //           , '$get_user_guid'
    //           , now()
    //           , '".$this->session->userdata('userid')."'
    //           ");
    //     }
    //   }
    // }


    $error = $this->db->affected_rows();
    if ($check_status > 0) {
      echo json_encode($data);
    } else if ($check_relationship->num_rows() > 0) {
      echo json_encode($data);
    } else if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error',

      );
      echo json_encode($data);
    }
  }

  // edited
  public function add_vendor_code()
  {
    // $table_child = $this->input->post('table_name1');
    $table_main = $this->input->post('table_name2');
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $code = $this->input->post('code');

    $register = $this->db->query("SELECT a.`store_code` FROM $table_main a LEFT JOIN lite_b2b.set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid' ");

    $store_code = $register->row('store_code');
    $store_code = explode(',', $store_code);
    $myArray = array_unique(array_merge($store_code, $code));
    $myArray = array_filter($myArray);
    $myArray = implode(',', $myArray);

    $this->db->query("UPDATE $table_main SET store_code = '$myArray', acc_no = '$myArray' WHERE register_guid = '$register_guid'");

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Add Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Add Error',

      );
      echo json_encode($data);
    }
  }

  public function send_mailjet_third_party_backup($email_add, $date, $bodyContent, $email_subject, $module, $cc_list_string, $pdf, $reply_to)
  {
    if ($pdf != '' || $pdf != null) {
      $b64Doc = chunk_split(base64_encode(file_get_contents($pdf)));
      $filename = substr($pdf, strrpos($pdf, '/') + 1);
    } else {
      $b64Doc = '';
    }

    $attachment = array('ContentType' => 'application/pdf', 'Filename' => $filename, 'Base64Content' => $b64Doc);
    $attachment1 = array($attachment);
    $attachment_array = array($attachment);

    //10.0.0.251
    $to_shoot_url = "20.212.51.33/pandaapi3rdparty/index.php/send_mail/blast_email_api";

    //echo $to_shoot_url ;die;

    $data = array(
      "email_subject"  => $email_subject,
      "bodyContent"  => $bodyContent,
      "to_email" => $email_add,
      "cc_list_string"  => $cc_list_string,
      "pdf" => $attachment_array,
    );
    //print_r($data); die;

    $myJSON = json_encode($data);
    //echo $myJSON;die;

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

    $result = curl_exec($ch);
    $result1 = json_decode($result);
    //print_r($result1); die;

    curl_close($ch);
    
    return;
    
  }

  public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module, $cc_list_string, $pdf, $reply_to)
  {
    // die;
    if ($pdf != '' || $pdf != null) {
      $b64Doc = chunk_split(base64_encode(file_get_contents($pdf)));
      $filename = substr($pdf, strrpos($pdf, '/') + 1);
    } else {
      $b64Doc = '';
    }
    // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
    // echo $b64Doc;die;      
    $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup WHERE type = 'alert_retailer_supplier_setup' LIMIT 1");
    $to_email = $email_add;
    $to_email_name = $email_add;
    $variable = array('api_key' => '1234', 'secret_key' => '123456', 'module' => 'test');

    $replyto = array('Email' => $reply_to, 'Name' => $reply_to);
    $from = array('Email' => $from_email->row('sender_email'), 'Name' => $from_email->row('sender_name'));
    $to = array('Email' => $to_email, 'Name' => $to_email_name);
    $to_array = array($to);

    if ($cc_list_string != '' || $cc_list_string != null) {
      $test_array = explode(',', $cc_list_string);
      $cc_array = array();
      foreach ($test_array as $tarray) {
        // echo $tarray->sender_email;
        $cc = array('Email' => $tarray, 'Name' => $tarray);
        array_push($cc_array, $cc);
      }
    } else {
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

    if ($b64Doc != '') {
      $attachment = array('ContentType' => 'application/pdf', 'Filename' => $filename, 'Base64Content' => $b64Doc);
      $attachment1 = array($attachment);
      $attachment_array = array($attachment);
      $data = array('from' => $from, 'to' => $to_array, 'subject' => $Subject, 'textpart' => $TextPart, 'htmlpart' => $HTMLPart, 'variables' => $variables, 'cc' => $cc_array, 'replyto' => $replyto, 'attachments' => $attachment_array);
    } else {
      $data = array('from' => $from, 'to' => $to_array, 'subject' => $Subject, 'textpart' => $TextPart, 'htmlpart' => $HTMLPart, 'variables' => $variables, 'replyto' => $replyto);
    }
    // $data2 = array($data);
    // $data3 = array('Messages' => $data2);
    // $t = array($t, "Mary", "Peter", "Sally");

    $myJSON = json_encode($data);
    //echo $myJSON;die;
    // die here
    $to_shoot_url = "20.212.51.33/tmg/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
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
    while (curl_errno($ch) == 28 && $retry < 3) {
      $response = curl_exec($ch);
      $retry++;
    }

    if (!curl_errno($ch)) {
      if (isset($result1->Messages[0])) {
        $status = $result1->Messages[0]->Status;
      } else {
        $status = $result1->ErrorMessage;
      }


      if ($status == 'success') {
        $ereponse = $result1->Messages[0]->To[0]->MessageID;
        $data = array(
          'created_at' => $this->db->query("SELECT now() as now")->row('now'),
          'created_by' => $_SESSION["userid"],
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
        return $result1->Messages[0]->Status;
        // if($module != 'alert_notification')
        // {
        //     // echo json_encode(array(
        //     //         'status' => true,
        //     //         'message' => 'success',
        //     //         'action'=> 'next',
        //     //         ));
        // };
      } else {
        $ereponse = $result1->StatusCode . '-' . $result1->ErrorMessage;
        $data = array(
          'created_at' => $this->db->query("SELECT now() as now")->row('now'),
          'created_by' => $_SESSION["userid"],
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
        // echo json_encode(array(
        //    'status' => false,
        //    'message' => $ereponse,
        //    'action'=> 'retry',
        //    ));
        // };
        return $result1->Messages[0]->Status . '_' . $ereponse;
      }

      curl_close($ch);
    } else {
      $ereponse = 'Curl error: ' . curl_error($ch);

      $data = array(
        'created_at' => $this->db->query("SELECT now() as now")->row('now'),
        'created_by' => $_SESSION["userid"],
        'recipient' => $to_email,
        'sender' => $from_email->row('sender_email'),
        'subject' => $email_subject,
        'status' => 'FAIL',
        'respond_message' => $retry . $ereponse,
        'smtp_server' => 'mailjet',
        'smtp_port' => 'mailjet',
        'smtp_security' => 'mailjet',
      );
      $this->db->insert('email_transaction', $data);
      // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
      //redirect('Email_controller/setup');
      // if($module != 'alert_notification')
      // {
      // echo json_encode(array(
      //    'status' => false,
      //    'message' => $ereponse,
      //    'action'=> 'retry',
      //    ));    
      return $result1->Messages[0]->Status . $ereponse;
    }
  }

  //edited
  public function complete_status()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    // $outright_template = $this->input->post('add_outright_template');
    // $consignment_template = $this->input->post('add_consign_template');
    // $cap_template = $this->input->post('add_cap_template');
    // $waive_template = $this->input->post('add_waive_template');
    // $outright_start_date = $this->input->post('add_outright_start');
    // $consign_start_date = $this->input->post('add_consign_start');
    // $cap_start_date = $this->input->post('add_cap_start');
    // $cap_end_date = $this->input->post('add_cap_end');
    // $waive_start_date = $this->input->post('add_waive_start');
    // $waive_end_date = $this->input->post('add_waive_end');

    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $updated_at = $this->db->query("SELECT now() as now")->row('now');

    $check_data = $this->db->query("SELECT a.* FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_info b ON a.register_guid = b.register_guid WHERE a.register_guid = '$register_guid' GROUP BY a.register_guid");

    $check_child_reg = $this->db->query("SELECT COUNT(a.register_guid) AS reg_rows FROM lite_b2b.register_child_new a INNER JOIN lite_b2b.register_new b ON a.register_guid = b.register_guid WHERE a.register_guid = '$register_guid' AND b.customer_guid = '$customer_guid' AND a.part_type = 'registration'")->row('reg_rows');

    $check_child_training = $this->db->query("SELECT COUNT(a.register_guid) AS training_rows FROM lite_b2b.register_child_new a INNER JOIN lite_b2b.register_new b ON a.register_guid = b.register_guid WHERE a.register_guid = '$register_guid' AND b.customer_guid = '$customer_guid' AND a.part_type = 'training'")->row('training_rows');

    if ($check_data->num_rows() != 1) {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid GUID. Please refresh page.',

      );
      echo json_encode($data);
      exit();
    }

    // $data_template = array(   
    //   'outright_template' => $outright_template,
    //   'consignment_template' => $consignment_template,
    //   'cap_template' => $cap_template,
    //   'waive_template' => $waive_template,
    //   'outright_start_date' => $outright_start_date,
    //   'consign_start_date' => $consign_start_date,
    //   'cap_start_date' => $cap_start_date,
    //   'cap_end_date' => $cap_end_date,
    //   'waive_start_date' => $waive_start_date,
    //   'waive_end_date' => $waive_end_date,
    // );

    // $this->db->where('register_guid', $register_guid);
    // $this->db->update('set_supplier_info', $data_template);

    $data = array(
      'update_at' => $updated_at,
      'update_by' => $user_id,
      'form_status' => 'Registered'
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_new', $data);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Login Account(s) : ' . $check_child_reg . '\nRegistered Successfully',
        //'msg' => 'Login Account(s) : ' . $check_child_reg . '\nParticipant User(s) : ' . $check_child_training . '\nRegistered Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function complete_status_vendor()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $updated_at = $this->db->query("SELECT now() as now")->row('now');

    $check_data = $this->db->query("SELECT * FROM lite_b2b.register_add_user_main WHERE register_guid = '$register_guid' GROUP BY register_guid");

    $check_child_reg = $this->db->query("SELECT COUNT(a.register_guid) AS reg_rows FROM lite_b2b.register_add_user_child a INNER JOIN lite_b2b.register_add_user_main b ON a.register_guid = b.register_guid WHERE a.register_guid = '$register_guid' AND b.customer_guid = '$customer_guid' AND a.part_type = 'registration'")->row('reg_rows');

    if ($check_data->num_rows() != 1) {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid GUID. Please refresh page.',

      );
      echo json_encode($data);
      exit();
    }

    $data = array(
      'update_at' => $updated_at,
      'update_by' => $user_id,
      'form_status' => 'Registered'
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_add_user_main', $data);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Login Account(s) : ' . $check_child_reg . '\nRegistered Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function email_subs_function()
  {
    $details = $this->input->post('details');
    $vendor_check = $this->input->post('vendor_check');
    $details = json_encode($details);
    $details = json_decode($details);
    $msg = '';
    //print_r($details); die;

    foreach ($details as $row) {
      if ($row->duplicate == 0) {
        $user_guid = $row->u_g;
        $customer_guid = $row->customer_guid;
        $get_user_array = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_guid = '$user_guid' LIMIT 1");
        if ($get_user_array->num_rows() <= 0) {
          $msg .= 'Email address :' . $row->vendor_email . ' duplicate template send.\n';
          continue;
        }
        $email_name = $get_user_array->row('user_id');
        $email_add = $get_user_array->row('user_id');
        //$email_name = 'jiangrui.goh@pandasoftware.my';
        //$email_add = 'jiangrui.goh@pandasoftware.my';                
        $subject = 'Login Details';
        $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
        $reset_guid = $row->reset_guid;
        $url = 'https://tunasmanja.xbridge.my/';
        $reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE reset_guid = '$reset_guid'");

        $reset_url = 'https://tunasmanja.xbridge.my/index.php/Key_in/key_in?si=' . $reset_link->row('reset_guid') . '&ug=' . $reset_link->row('user_guid');

        $supplier_guid = $row->supplier_guid;

        $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");

        $supplier_code = $this->db->query("SELECT GROUP_CONCAT(DISTINCT supplier_group_name) as vendor_code FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

        $email_data = array(
          'reset_detail' => $reset_link,
          'customer_name' => $customer_name,
          'user_detail' => $get_user_array,
          'reset_url' => $reset_url,
          'supplier_detail' => $supplier_detail,
          'supplier_code' => $supplier_code,
        );

        $bodyContent    = $this->load->view('email_template/user_login_reset_view', $email_data, TRUE);
        //echo $bodyContent;die;  
        // die here;    
        $send_result = $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'register@xbridge.my','');
        $data_email = array(
          'guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
          'module' => 'key_in',
          'remark' => 'new',
          'customer_guid' => $customer_guid,
          'user_guid' => $get_user_array->row('user_guid'),
          'status' => $send_result,
          'from_email' => 'register@xbridge.my',
          'email_id' => $email_add,
          'subject' => $subject,
          'content' => $bodyContent,
          'created_by' => $_SESSION['userid'],
          'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
          'updated_by' => $_SESSION['userid'],
          'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
        );
        $this->db->insert('lite_b2b.email_send_content', $data_email);

        //$this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');
        if ($vendor_check == 'register') {
          $data_update = array(
            'form_status' => 'Emailed',
          );

          $this->db->where('customer_guid', $customer_guid);
          $this->db->where('supplier_guid', $supplier_guid);
          $this->db->update('register_new', $data_update);
        } else if ($vendor_check == 'vendor') {
          $data_update = array(
            'form_status' => 'Emailed',
          );

          $this->db->where('customer_guid', $customer_guid);
          $this->db->where('supplier_guid', $supplier_guid);
          $this->db->update('register_add_user_main', $data_update);
        }

        $msg .= 'Email address :' . $row->vendor_email . ' new user template send.\n';
      } else if ($row->duplicate == 1) {
        $user_guid = $row->u_g;
        $customer_guid = $row->customer_guid;
        $get_user_array = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_guid = '$user_guid' LIMIT 1");
        if ($get_user_array->num_rows() <= 0) {
          $msg .= 'Email address :' . $row->vendor_email . ' duplicate template send.\n';
          continue;
        }
        $email_name = $get_user_array->row('user_id');
        $email_add = $get_user_array->row('user_id');
        //$email_name = 'jiangrui.goh@pandasoftware.my';
        //$email_add = 'jiangrui.goh@pandasoftware.my';                
        $subject = 'Login Details';
        $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
        $reset_guid = $row->reset_guid;
        $url = 'https://tunasmanja.xbridge.my/';

        $supplier_guid = $row->supplier_guid;

        $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");

        $supplier_code = $this->db->query("SELECT GROUP_CONCAT(DISTINCT supplier_group_name) as vendor_code FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

        $email_data = array(
          'customer_name' => $customer_name,
          'user_detail' => $get_user_array,
          'supplier_detail' => $supplier_detail,
          'supplier_code' => $supplier_code,
        );

        $bodyContent    = $this->load->view('email_template/user_login_duplicate_view', $email_data, TRUE);
        //echo $bodyContent;die;  
        // die here;   
        $send_result = $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'register@xbridge.my','');
        $data_email = array(
          'guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
          'module' => 'key_in',
          'remark' => 'duplicate',
          'customer_guid' => $customer_guid,
          'user_guid' => $get_user_array->row('user_guid'),
          'status' => $send_result,
          'from_email' => 'register@xbridge.my',
          'email_id' => $email_add,
          'subject' => $subject,
          'content' => $bodyContent,
          'created_by' => $_SESSION['userid'],
          'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
          'updated_by' => $_SESSION['userid'],
          'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
        );
        $this->db->insert('lite_b2b.email_send_content', $data_email);

        //$this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','support@xbridge.my');

        if ($vendor_check == 'register') {
          $data_update = array(
            'form_status' => 'Emailed'
          );

          $this->db->where('customer_guid', $customer_guid);
          $this->db->where('supplier_guid', $supplier_guid);
          $this->db->update('register_new', $data_update);
        } else if ($vendor_check == 'vendor') {
          $data_update = array(
            'form_status' => 'Emailed'
          );

          $this->db->where('customer_guid', $customer_guid);
          $this->db->where('supplier_guid', $supplier_guid);
          $this->db->update('register_add_user_main', $data_update);
        }

        $msg .= 'Email address :' . $row->vendor_email . ' duplicate template send.\n';
      } else {
        $msg .= $row->vendor_email . ' send error.\n';
      }
    }

    $data = array(
      'para1' => 1,
      'msg' => $msg,

    );

    echo json_encode($data);
  }

  public function proceed_subscribe_email_old()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $table_main = $this->input->post('table_main');
    // $vendor_code = $this->input->post('vendor_code');

    $details = $this->input->post('details');
    $details = json_encode($details);
    $details = json_decode($details);

    $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    $created_by = $this->session->userdata('userid');

    // print_r($details);
    // die;

    // print_r($vendor_email); echo '<br>';
    // print_r($vendor_code);  
    foreach ($details as $row4) {
      $get_dropdown_guid = $row4->report_guid;
      // print_r(count($row4->vendor_code));die;
      if ($get_dropdown_guid == '') {
        $data = array(
          'para1' => 1,
          'msg' => 'Vendor code cannot be empty',
        );
        echo json_encode($data);
        die;
      }
    }

    $string_code = '';
    foreach ($details as $row) {
      $report_guid_array = $row->report_guid;
      $user_id = $row->vendor_email; //if you post from table
      $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_id = '$user_id' AND acc_guid = '$customer_guid' LIMIT 1")->row('user_guid');
      // echo $get_user_guid->row('user_guid').'<br>';
      foreach ($report_guid_array as $report_loop_guid) {
        $report_guid = $report_loop_guid;
        $schedule_type = $this->db->query("SELECT if(report_type<>'each_trans','weekly','each_trans') as schedule_type from set_report_query where report_guid = '$report_guid' ")->row('schedule_type');
        // echo $this->db->last_query();die;
        // echo $report_guid.$schedule_type;die;
        if ($schedule_type != 'each_trans') {
          $schedule_type =  'daily';
          // $day_name =  $this->input->post('day_name');
          $day_name = $this->db->query("SELECT DAYNAME(DATE_ADD(NOW(), INTERVAL 1 DAY)) as day")->row('day');
          $date_start = $this->db->query("SELECT $day_name as date_start from calendar")->row('date_start');
          // echo $this->db->last_query();die;
          // echo $date_start.$day_name;die;
        } else {
          $day_name = $this->input->post('day_name_ever');
          $date_start = $this->db->query("SELECT curdate() as today")->row('today');
        };
        // $day_name = $this->db->query("SELECT DAYNAME(DATE_ADD(NOW(), INTERVAL 1 DAY)) as day")->row('day');
        // echo $day_name;
        // die;
        $email_user = $this->db->query("SELECT * FROM email_list WHERE user_guid = '$get_user_guid'")->row('trans_guid');
        // echo $email_user;die;
        if ($email_user == '' || $email_user == null) {
          $get_user_name = $this->db->query("SELECT a.*,b.user_group_name FROM lite_b2b.set_user a INNER JOIN lite_b2b.set_user_group b ON a.user_group_guid = b.user_group_guid WHERE a.user_id = '$user_id' AND a.acc_guid = '$customer_guid' LIMIT 1");
          $data = array(
            'trans_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
            'customer_guid' => $customer_guid,
            'user_guid' => $get_user_guid,
            'first_name' => addslashes($get_user_name->row('user_name')),
            'last_name' => '',
            'email' => $get_user_name->row('user_id'),
            'email_group' => $get_user_name->row('user_group_name'),
            'isactive' => '1',
          );
          // print_r($data);die;
          $this->db->insert('email_list', $data);
        }
        $email_user = $this->db->query("SELECT * FROM email_list WHERE user_guid = '$get_user_guid'")->row('trans_guid');
        // echo $this->db->last_query();die;
        // echo $email_user;die;
        $checking_duplicate = $this->db->query("SELECT * from set_report_schedule where email_list_trans_guid = '$email_user' and report_guid = '$report_guid'");
        // echo $this->db->last_query();
        // echo $checking_duplicate->num_rows();die;

        if ($checking_duplicate->num_rows() <= 0) {
          // echo 2;die;
          $data = array(
            'schedule_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
            'customer_guid' => $customer_guid,
            'email_list_trans_guid' => $email_user,
            'schedule_type' =>  $schedule_type,
            'report_guid' => $report_guid,
            'date_start' => $date_start,
            'day_name' =>  $day_name,
            'active' => '1',
            'created_at' => $this->db->query("SELECT now() as today")->row('today'),
            'created_by' => $_SESSION['userid'],
            'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
            'updated_by' => $_SESSION['userid'],
          );
          // print_r($data);die;
          $this->db->insert('set_report_schedule', $data);
          $string_code .= $user_id . ' schedule successfully.\n';
        } else {
          $string_code .= $user_id . ' scheduled exists.\n';
        }
      }
    }

    $para1 = 0;
    $msg = $string_code;
    $check_status = 1;
    $data = array(
      'para1' => $para1,
      'msg' => $msg,
    );


    $error = $this->db->affected_rows();
    if ($check_status > 0) {
      echo json_encode($data);
    } else if ($check_relationship->num_rows() > 0) {
      echo json_encode($data);
    } else if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error',

      );
      echo json_encode($data);
    }
  }

  public function proceed_subscribe_email()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $table_main = $this->input->post('table_main');

    $details = $this->input->post('details');
    $details = json_decode(json_encode($details));

    $get_supp = $this->db->query("SELECT supplier_guid FROM $table_main b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    $created_by = $this->session->userdata('userid');
    $message = '';

    // print_r($details);
    // die;

    foreach($details as $row4)
    {
      $get_user_id = $row4->vendor_email;
      
      $get_dropdown_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user a WHERE a.user_id = '$get_user_id' AND a.acc_guid = '$customer_guid' GROUP BY a.user_guid")->row('user_guid');

      if($get_dropdown_user_guid == '' || $get_dropdown_user_guid == null  || $get_dropdown_user_guid == 'null' )
      {
        $data = array(
         'para1' => 1,
         'msg' => 'Invalid User',
         );    
        echo json_encode($data);die;
      }
      
    }

    foreach($details as $row)
    {
      $report_guid_array = $row->report_guid;
      $notification_type = $row->action_status;
      $user_id = $row->vendor_email;//if you post from table
      $get_user_guid = $this->db->query("SELECT * FROM lite_b2b.set_user a WHERE a.user_id = '$user_id' AND a.acc_guid = '$customer_guid' GROUP BY a.user_guid")->row('user_guid');

      $noti_run = 0;
      $existed = 0;
      $inserted = 0;
      $deleted = 0;
      foreach($report_guid_array as $report_loop_guid)
      {
        $report_guid = $report_loop_guid;

        $check_data = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$customer_guid' AND a.user_guid = '$get_user_guid' AND a.rep_option_guid = '$report_guid'")->result_array();
    
        if($notification_type == 'insert')
        {
            $check_notification_data = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$customer_guid' AND a.user_guid = '$get_user_guid' AND a.rep_option_guid = '$report_guid' AND a.isactive = '1'")->result_array();
  
            if(count($check_notification_data) > 0 )
            {
              $noti_run++;
              $existed++;
              continue;
            }
  
            $check_notification_active = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$customer_guid' AND a.user_guid = '$get_user_guid' AND a.rep_option_guid = '$report_guid' AND a.isactive = '0'")->result_array();
  
            if(count($check_notification_active) > 0 )
            {
                // print_r($report_guid); die;
                $update_notification = $this->db->query("UPDATE lite_b2b.set_report_query_option_c
                SET isactive = '1', updated_at = NOW() , updated_by = '$created_by'
                WHERE customer_guid = '$customer_guid' 
                AND user_guid = '$get_user_guid' 
                AND rep_option_guid = '$report_guid'
                AND isactive = '0' ");
            }
            else
            {
                $data = array(
                    'customer_guid' => $customer_guid,
                    'rep_option_guid_c' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                    'rep_option_guid' => $report_guid,
                    'user_guid' => $get_user_guid,
                    'isactive' => '1',
                    'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'created_by' => $created_by,
                    'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                    'updated_by' => $created_by,
                );
                $this->db->insert('lite_b2b.set_report_query_option_c',$data);
            }
  
            $inserted_tb = $this->db->affected_rows();
  
            if($inserted_tb > 0)
            {
              $noti_run++;
              $inserted++;
            }
        }
        else if($notification_type == 'delete')
        {
            $check_notification_data = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$customer_guid' AND a.user_guid = '$get_user_guid' AND a.rep_option_guid = '$report_guid'")->result_array();
  
            if(count($check_notification_data) == 0 )
            {
              $noti_run--;
              continue;
            }

            $check_notification_no_active = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$customer_guid' AND a.user_guid = '$get_user_guid' AND a.rep_option_guid = '$report_guid' AND a.isactive = '0'")->result_array();

            if(count($check_notification_no_active) > 0 )
            {
              continue;
            }
  
            $update_notification = $this->db->query("UPDATE lite_b2b.set_report_query_option_c
            SET isactive = '0', updated_at = NOW() , updated_by = '$created_by'
            WHERE customer_guid = '$customer_guid' 
            AND user_guid = '$get_user_guid' 
            AND rep_option_guid = '$report_guid'
            AND isactive = '1' ");
  
            $noti_run--;
            $deleted++;
        }
      }

      $check_after_foreach = $this->db->query("SELECT a.* FROM lite_b2b.set_report_query_option_c a WHERE a.customer_guid = '$customer_guid' AND a.user_guid = '$get_user_guid' AND a.isactive = '1'")->result_array();

      if(count($check_after_foreach) > 0)
      {
        $update_user_notification = $this->db->query("UPDATE lite_b2b.set_user
        SET isnotification = '1'
        WHERE user_guid = '$get_user_guid'
        AND acc_guid = '$customer_guid'
        AND isnotification = '0' ");
      }
      else
      {
        $update_user_notification = $this->db->query("UPDATE lite_b2b.set_user
        SET isnotification = '0'
        WHERE user_guid = '$get_user_guid'
        AND acc_guid = '$customer_guid'
        AND isnotification = '1' ");
      }
      
      if($inserted != 0 || $existed != 0 || $deleted != 0)
      {
        $message .= $user_id . ': Inserted : ' . $inserted . ' | Existed : ' .$existed . ' | Deleted : ' .$deleted . '\n'; 
      }
    
    }

    $data = array(
      'para1' => 0,
      'msg' => $message,
    );    
    echo json_encode($data);    
  } 

  public function add_vendor_info()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $ven_name = $this->input->post('ven_name');
    $ven_designation = $this->input->post('ven_designation');
    $ven_phone = $this->input->post('ven_phone');
    $ven_email = $this->input->post('ven_email');
    $ven_agency = $this->input->post('ven_agency');
    $ven_code = $this->input->post('ven_code');
    $remark_no = $this->input->post('remark_no');
    $add_user_group = $this->input->post('add_user_group');
    $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $m_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $m_guid_1 = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $ven_code = implode(",", $ven_code);
    $ven_code = "" . $ven_code . "";

    $ven_agency = implode(",", $ven_agency);
    $ven_agency = "" . $ven_agency . "";

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) {
      $check_ven_name = $key->ven_name;
      $check_ven_email = $key->ven_email;

      if ($ven_name == $check_ven_name) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

        );
        echo json_encode($data);
        die;
      }

      if ($ven_email == $check_ven_email) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

        );
        echo json_encode($data);
        die;
      }
    }

    $admin_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE admin_active = '2' AND group_info_status >= '1'")->result_array();

    $valid_admin_guid = implode("','",array_filter(array_column($admin_user_group,'user_group_guid')));

    $check_set_user_group = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND a.user_group_info IN ('$valid_admin_guid') AND part_type = 'registration' GROUP BY a.register_c_guid")->result_array();

    if(count($check_set_user_group) > 0 )
    {
      $data = array(
        'para1' => 1,
        'msg' => 'Admin User Group Cannot more than 1.',
      );
      echo json_encode($data);
      die;
    }

    $check_user_info = $this->db->query("SELECT b.supplier_guid,c.supplier_name FROM lite_b2b.set_user a 
    INNER JOIN lite_b2b.set_supplier_user_relationship b
    ON a.user_guid = b.user_guid
    AND a.acc_guid = b.customer_guid
    INNER JOIN lite_b2b.set_supplier c
    ON b.supplier_guid = c.supplier_guid
    WHERE a.user_id = '$ven_email' 
    AND a.acc_guid = '$customer_guid'");

    if($check_user_info->num_rows() > 0)
    {
      foreach($check_user_info->result() as $row)
      {
        $user_mapping_supplier_guid = $row->supplier_guid;

        $check_is_admin_user_exists = $this->db->query("SELECT b.user_group_guid,b.user_id FROM lite_b2b.set_supplier_user_relationship a
        INNER JOIN lite_b2b.set_user b
        ON a.user_guid = b.user_guid
        AND a.customer_guid = b.acc_guid
        INNER JOIN lite_b2b.set_user_group c
        ON b.user_group_guid = c.user_group_guid
        AND c.admin_active = '1'
        AND c.group_info_status = '1'
        WHERE a.supplier_guid = '$user_mapping_supplier_guid' 
        AND a.customer_guid = '$customer_guid'
        GROUP BY b.user_guid,b.acc_guid")->result_array();
  
        if(count($check_is_admin_user_exists) > 0 )
        {
          $user_details_admin = implode(",",array_filter(array_column($check_is_admin_user_exists,'user_id')));
  
          $data = array(
            'para1' => 1,
            'msg' => $user_details_admin . ' Admin User Exists. Please contact support.',
          );
          echo json_encode($data);
          die;
        }
      }
    }

    $data_1 = array(
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_new', $data_1);

    $data_2 = array(
      'supplier_guid' => $supplier_guid,
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'ven_name' => $ven_name,
      'ven_designation' => $ven_designation,
      'ven_phone' => $ven_phone,
      'ven_email' => $ven_email,
      'ven_name' => $ven_name,
      'user_group_info' => $add_user_group,
      'isdelete' => 0,
      'part_type' => 'registration',
      'created_at' => $updated_at,
      'created_by' => $user_id
    );

    $this->db->insert('register_child_new', $data_2);

    $data_3 = array(
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'register_mapping_guid' => $m_guid,
      'ven_agency' => $ven_agency,
      'vendor_code_remark' => $remark_no,
      'mapping_type' => 'outlet'
    );

    $this->db->insert('register_child_mapping', $data_3);

    $data_4 = array(
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'register_mapping_guid' => $m_guid_1,
      'ven_code' => $ven_code,
      'mapping_type' => 'code'
    );

    $this->db->insert('register_child_mapping', $data_4);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Add Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function edit_vendor_info()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $register_c_guid = $this->input->post('register_c_guid');
    $register_mapping_guid = $this->input->post('register_mapping_guid');
    $ven_name = $this->input->post('ven_name');
    $ven_designation = $this->input->post('ven_designation');
    $ven_phone = $this->input->post('ven_phone');
    $ven_email = $this->input->post('ven_email');
    $ven_agency = $this->input->post('ven_agency');
    $ven_code = $this->input->post('ven_code');
    $ven_code_remark = $this->input->post('ven_code_remark');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $ven_agency = implode(",", $ven_agency);
    $ven_agency = "" . $ven_agency . "";

    $ven_code = implode(",", $ven_code);
    $ven_code = "" . $ven_code . "";

    $register_mapping_guid = explode(",", $register_mapping_guid);
    $register_mapping_guid_1 = $register_mapping_guid[0]; // outlet guid
    $register_mapping_guid_2 = $register_mapping_guid[1]; // code guid

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND a.`register_c_guid` != '$register_c_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) {
      $check_ven_name = $key->ven_name;
      $check_ven_email = $key->ven_email;

      if ($ven_name == $check_ven_name) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

        );
        echo json_encode($data);
        die;
      }

      if ($ven_email == $check_ven_email) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

        );
        echo json_encode($data);
        die;
      }
    }

    $admin_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE admin_active = '2' AND group_info_status >= '1'")->result_array();

    $valid_admin_guid = implode("','",array_filter(array_column($admin_user_group,'user_group_guid')));

    $check_set_user_group = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND a.user_group_info IN ('$valid_admin_guid') AND part_type = 'registration' AND a.register_c_guid != '$register_c_guid' GROUP BY a.register_c_guid")->result_array();

    if(count($check_set_user_group) > 0 )
    {
      $data = array(
        'para1' => 1,
        'msg' => 'Admin User Group Cannot more than 1.',
      );
      echo json_encode($data);
      die;
    }

    $check_user_info = $this->db->query("SELECT b.supplier_guid,c.supplier_name FROM lite_b2b.set_user a 
    INNER JOIN lite_b2b.set_supplier_user_relationship b
    ON a.user_guid = b.user_guid
    AND a.acc_guid = b.customer_guid
    INNER JOIN lite_b2b.set_supplier c
    ON b.supplier_guid = c.supplier_guid
    WHERE a.user_id = '$ven_email' 
    AND a.acc_guid = '$customer_guid'");

    if($check_user_info->num_rows() > 0)
    {
      foreach($check_user_info->result() as $row)
      {
        $user_mapping_supplier_guid = $row->supplier_guid;

        $check_is_admin_user_exists = $this->db->query("SELECT b.user_group_guid,b.user_id FROM lite_b2b.set_supplier_user_relationship a
        INNER JOIN lite_b2b.set_user b
        ON a.user_guid = b.user_guid
        AND a.customer_guid = b.acc_guid
        INNER JOIN lite_b2b.set_user_group c
        ON b.user_group_guid = c.user_group_guid
        AND c.admin_active = '1'
        AND c.group_info_status = '1'
        WHERE a.supplier_guid = '$user_mapping_supplier_guid' 
        AND a.customer_guid = '$customer_guid'
        GROUP BY b.user_guid,b.acc_guid")->result_array();
  
        if(count($check_is_admin_user_exists) > 0 )
        {
          $user_details_admin = implode(",",array_filter(array_column($check_is_admin_user_exists,'user_id')));
  
          $data = array(
            'para1' => 1,
            'msg' => $user_details_admin . ' Admin User Exists. Please contact support.',
          );
          echo json_encode($data);
          die;
        }
      }
    }

    $data_2 = array(
      'ven_name' => $ven_name,
      'ven_designation' => $ven_designation,
      'ven_phone' => $ven_phone,
      'ven_email' => $ven_email,
      'ven_name' => $ven_name,
      'user_group_info' => $edit_user_group,
      'isdelete' => 0,
    );

    $this->db->where('register_c_guid', $register_c_guid);
    $this->db->update('register_child_new', $data_2);

    $data_3 = array(
      'ven_agency' => $ven_agency,
      'mapping_type' => 'outlet'
    );

    $this->db->where('register_mapping_guid', $register_mapping_guid_1);
    $this->db->update('register_child_mapping', $data_3);

    $data_4 = array(
      'ven_code' => $ven_code,
      'vendor_code_remark' => $ven_code_remark,
      'mapping_type' => 'code'
    );

    $this->db->where('register_mapping_guid', $register_mapping_guid_2);
    $this->db->update('register_child_mapping', $data_4);

    $data_1 = array(
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_new', $data_1);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Edit Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function add_part_info()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $part_name = $this->input->post('part_name');
    $part_ic = $this->input->post('part_ic');
    $part_mobile = $this->input->post('part_mobile');
    $part_email = $this->input->post('part_email');
    $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) {
      $check_part_name = $key->part_name;
      $check_part_ic = $key->part_ic;
      $check_part_email = $key->part_email;

      if ($part_name == $check_part_name) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

        );
        echo json_encode($data);
        die;
      }

      if ($part_ic == $check_part_ic) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate IC NO.',

        );
        echo json_encode($data);
        die;
      }

      if ($part_email == $check_part_email) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

        );
        echo json_encode($data);
        die;
      }
    }

    $data_1 = array(
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_new', $data_1);

    $data_2 = array(
      'supplier_guid' => $supplier_guid,
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'part_name' => $part_name,
      'part_ic' => $part_ic,
      'part_mobile' => $part_mobile,
      'part_email' => $part_email,
      'isdelete' => 0,
      'part_type' => 'training',
      'created_at' => $updated_at,
      'created_by' => $user_id
    );

    $this->db->insert('register_child_new', $data_2);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Add Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function edit_part_info()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $register_c_guid = $this->input->post('register_c_guid');
    $part_name = $this->input->post('part_name');
    $part_ic = $this->input->post('part_ic');
    $part_mobile = $this->input->post('part_mobile');
    $part_email = $this->input->post('part_email');
    $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND a.`register_c_guid` != '$register_c_guid' AND part_type = 'training' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) {
      $check_part_name = $key->part_name;
      $check_part_ic = $key->part_ic;
      $check_part_email = $key->part_email;

      if ($part_name == $check_part_name) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Name.',

        );
        echo json_encode($data);
        die;
      }

      if ($part_ic == $check_part_ic) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate IC NO.',

        );
        echo json_encode($data);
        die;
      }

      if ($part_email == $check_part_email) {
        $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

        );
        echo json_encode($data);
        die;
      }
    }

    $data_1 = array(
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_new', $data_1);

    $data_2 = array(
      'part_name' => $part_name,
      'part_ic' => $part_ic,
      'part_mobile' => $part_mobile,
      'part_email' => $part_email,
      'isdelete' => 0,
    );

    $this->db->where('register_c_guid', $register_c_guid);
    $this->db->update('register_child_new', $data_2);

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Edit Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function vendor_tb()
  {
    $register_guid = $this->input->post('register_guid');
    $register_child = $this->db->query("SELECT a.*, GROUP_CONCAT(b.`register_mapping_guid` ORDER BY b.`mapping_type` DESC) AS register_mapping_guid, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code, c.`customer_guid`, GROUP_CONCAT(b.`vendor_code_remark`) AS vendor_code_remark, c.`form_status`, IFNULL(d.user_group_guid,'') AS user_group_guid, IFNULL(d.user_group_name,'') AS user_group_name FROM lite_b2b.register_child_new a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` INNER JOIN lite_b2b.register_new c ON a.`register_guid` = c.`register_guid` LEFT JOIN lite_b2b.set_user_group d ON a.user_group_info = d.user_group_guid WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid ORDER BY created_at ASC");
    //echo $this->db->last_query(); die;

    echo json_encode($register_child->result());
  }

  public function participant_tb()
  {
    $register_guid = $this->input->post('register_guid');

    $register_child_training = $this->db->query("SELECT a.*, b.`customer_guid`,b.`form_status` FROM lite_b2b.register_child_new a INNER JOIN lite_b2b.register_new b ON a.register_guid = b.`register_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' ORDER BY created_at ASC");

    echo json_encode($register_child_training->result());
  }

  public function active_status_old()
  {
    $register_c_guid = $this->input->post('register_c_guid');
    $isdelete = $this->input->post('isdelete');

    if ($isdelete == 0) {
      $data = array(
        'isdelete' => 1,
      );

      $this->db->where('register_c_guid', $register_c_guid);
      $this->db->update('register_child_new', $data);

      $error = $this->db->affected_rows();

      if ($error > 0) {

        $data = array(
          'para1' => 0,
          'msg' => 'Deactivated',

        );
        echo json_encode($data);
      } else {
        $data = array(
          'para1' => 1,
          'msg' => 'Error.',

        );
        echo json_encode($data);
      }
    } else {
      $data = array(
        'isdelete' => 0,
      );

      $this->db->where('register_c_guid', $register_c_guid);
      $this->db->update('register_child_new', $data);

      $error = $this->db->affected_rows();

      if ($error > 0) {

        $data = array(
          'para1' => 0,
          'msg' => 'Activated',

        );
        echo json_encode($data);
      } else {
        $data = array(
          'para1' => 1,
          'msg' => 'Error.',

        );
        echo json_encode($data);
      }
    }
  }

  public function active_status_vendor_old()
  {
    $register_c_guid = $this->input->post('register_c_guid');
    $isdelete = $this->input->post('isdelete');

    if ($isdelete == 0) {
      $data = array(
        'isdelete' => 1,
      );

      $this->db->where('register_c_guid', $register_c_guid);
      $this->db->update('register_add_user_child', $data);

      $error = $this->db->affected_rows();

      if ($error > 0) {

        $data = array(
          'para1' => 0,
          'msg' => 'Deactivated',

        );
        echo json_encode($data);
      } else {
        $data = array(
          'para1' => 1,
          'msg' => 'Error.',

        );
        echo json_encode($data);
      }
    } else {
      $data = array(
        'isdelete' => 0,
      );

      $this->db->where('register_c_guid', $register_c_guid);
      $this->db->update('register_add_user_child', $data);

      $error = $this->db->affected_rows();

      if ($error > 0) {

        $data = array(
          'para1' => 0,
          'msg' => 'Activated',

        );
        echo json_encode($data);
      } else {
        $data = array(
          'para1' => 1,
          'msg' => 'Error.',

        );
        echo json_encode($data);
      }
    }
  }

  public function active_status()
  {
    $register_guid = $this->input->post('register_guid');
    $register_c_guid = $this->input->post('register_c_guid');
    //$isdelete = $this->input->post('isdelete');

    if ($register_guid != '' || $register_c_guid != '') {
      $delete_register = $this->db->query("DELETE FROM lite_b2b.register_child_mapping WHERE register_guid = '$register_guid' AND register_c_guid = '$register_c_guid' ");

      $delete_register = $this->db->query("DELETE FROM lite_b2b.register_child_new WHERE register_guid = '$register_guid' AND register_c_guid = '$register_c_guid' ");
    }

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Removed',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function active_status_vendor()
  {
    $register_guid = $this->input->post('register_guid');
    $register_c_guid = $this->input->post('register_c_guid');
    //$isdelete = $this->input->post('isdelete');

    if ($register_guid != '' || $register_c_guid != '') {
      $delete_register = $this->db->query("DELETE FROM lite_b2b.register_add_user_child_mapping WHERE register_guid = '$register_guid' AND register_c_guid = '$register_c_guid' ");

      $delete_register = $this->db->query("DELETE FROM lite_b2b.register_add_user_child WHERE register_guid = '$register_guid' AND register_c_guid = '$register_c_guid' ");
    }

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Removed',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  public function file_upload()
  {
    $customer_guid = $_SESSION['customer_guid'];

    //$active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'")->row('isactive');

    //$user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

    $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');


    $file_uuid = $this->db->query("SELECT REPLACE(LOWER(UUID()),'-','') AS uuid")->row('uuid');
    $now = $this->db->query("SELECT NOW() as now")->row('now');

    $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'main_path', 'REG');

    $defined_path = $file_config_main_path; // './uploads/empty/';
    //print_r($defined_path); die;

    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
    ini_set('post_max_size', '64M');
    ini_set('upload_max_filesize', '64M');

    $config['upload_path']          = $defined_path;
    $config['allowed_types']        = '*';
    $config['max_size']             = 50000;
    $config['file_name'] = $file_uuid;
    // var_dump( $this->input->post('file') );die; 

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file')) {
      $error = array('error' => $this->upload->display_errors());

      if (null != $error) {
        $data = array(
          'para1' => 1,
          'msg' => 'Error do upload.',
        );
        echo json_encode($data);
        exit();
      } //close else

    } else {
      $data = array('upload_data' => $this->upload->data());

      // print_r($_FILES["file"]);

      $filename = $defined_path . $data['upload_data']['file_name'];

      //  Include PHPExcel_IOFactory
      $this->load->library('Excel');

      $inputFileName = $filename;

      //  Read your Excel workbook
      try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
      } catch (Exception $e) {

        $error_message = $this->lang->line('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        $xerror = $this->db->error();
        $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
        $this->message->error_message_with_status($xerror['message'], '1', '');
        exit();
      }

      unlink($filename);
    }
    //Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    // $sheetCount = $sheet->getSheetCount();

    for ($row = 1; $row <= 1; $row++) {
      //  Read a row of data into an array
      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
      //  Insert row data array into your database of choice here

    }

    // print_r($objPHPExcel->getSheetCount());die;

    //$header_array = array();

    $header_array = ['Retailer Name', 'Supplier Name', 'Reg No', 'Vendor Code', 'Company Email', 'Phone', 'Second Phone', 'Memo Type'];

    $checking_array = array();

    //make array into string with comma
    $exheader = '';
    $exchild_main = '';
    $set_supplier_exchild = '';
    $update_syntax = '';

    $values = '';

    foreach ($rowData as $eheader) {
      foreach ($eheader as $row) {
        if ($row == '' || $row == null) {
          continue;
        } else {
          $row = $row;
        }

        $checking_array[] = $row;
        $update_syntax .= $row . ' = ' . 'VALUES(' . $row . '),';
      } //close small foreacH
    } //close loop header array

    $update_syntax = rtrim($update_syntax, ',');

    $result = array_diff($header_array, $checking_array); //compare form

    if (null != $result) {

      $message = '';

      foreach ($result as $header) {
        $message .= $header . ' ';
      }

      $data = array(
        'para1' => 1,
        'msg' => $message . 'header is required.',
      );
      echo json_encode($data);
      // $error_message = $message.$this->lang->line('header_is_required');
      // $xerror = $this->db->error();
      // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
      // $this->message->error_message_with_status($xerror['message'], '1', '');
      exit();
    } //close if

    $result = array_diff($checking_array, $header_array); //compare form

    if (null != $result) {

      $message = '';

      foreach ($result as $header) {
        $message .= $header . ' ';
      }

      $data = array(
        'para1' => 1,
        'msg' => $message . 'header is not exists.',
      );
      echo json_encode($data);
      // $error_message = $message.$this->lang->line('header_is_not_exist');
      // $xerror = $this->db->error();
      // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
      // $this->message->error_message_with_status($xerror['message'], '1', '');
      exit();
    } //close if

    $check_escape_header_index = array();

    foreach ($rowData as $eheader) {
      foreach ($eheader as $key => $row) {
        if ($row == '' || $row == null) {
          $check_escape_header_index[] = $key;

          continue;
        } else {
          $row = $row;
        }

        $exheader .= $row . ',';
      } //close loop through row
    } //close loop header array
    $xrow_b = 2;
    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('A' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $xrowData_b = $sheet->rangeToArray('A' . ++$xrow_b . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);
      $merge = array_merge($xrowData, $xrowData_b);

      $search_array = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $type_search = array_search('Supplier Name', $search_array[0]);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              $check_name = $this->db->query("SELECT * FROM register_new WHERE comp_name = '" . addslashes($row2) . "' AND customer_guid ='" . $_SESSION['customer_guid'] . "' ");

              if ($check_name->num_rows() > 0) {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Error create new transaction due to more than one supplier under the retailer.',

                );
                echo json_encode($data);
                exit();
              } //close num rows
            } //close else
          } //close itemcode
        } //close foreach td itemcode
      } //close loop row

      foreach ($merge as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              $check_name = $this->db->query("SELECT * FROM register_new WHERE comp_name = '" . addslashes($row2) . "' AND customer_guid ='" . $_SESSION['customer_guid'] . "' ");

              if ($check_name->num_rows() > 0) {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Error create new transaction due to more than one supplier in this retailer.',

                );
                echo json_encode($data);
                exit();
              } //close num rows
            } //close else
          } //close itemcode
        } //close foreach td itemcode
      } //close loop row


    } //close foreach child data checking

    //$supplier_guid = [];
    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('B' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('B' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $type_search = array_search('Retailer Name', $search_array[0]);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              $supplier_query = $this->db->query("SELECT * FROM set_supplier WHERE supplier_name = '" . addslashes($row2) . "' AND isactive = '1' ORDER BY supplier_name ASC");

              $supplier_guid[] = $supplier_query->row('supplier_guid');

              if ($supplier_query->num_rows() == 0) {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Error find Supplier Name: ' . $row2 . '.',
                );
                echo json_encode($data);
                exit();
              } //close num rows
            } //close else
          } //close itemcode
        } //close foreach td itemcode
      } //close loop row

    } //close foreach child data checking

    //check duplicate supplier
    $unqiue_supplier = array_unique($supplier_guid);
    $count_array_1 = count($supplier_guid);
    $count_array_2 = count($unqiue_supplier);
    if ($count_array_1 != $count_array_2) {
      $data = array(
        'para1' => 1,
        'msg' => 'Duplicate Supplier Name',
      );
      echo json_encode($data);
      exit();
    }

    $r = '0';
    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('C' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('C' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $type_search = array_search('Retailer Name', $search_array[0]);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              $supplier_query = $this->db->query("SELECT * FROM set_supplier WHERE reg_no = '$row2' AND supplier_guid = '$supplier_guid[$r]'  AND isactive = '1' ORDER BY supplier_name ASC");
              //echo $this->db->last_query(); die;
              $r++;
              if ($supplier_query->num_rows() == 0) {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Error find Reg No: ' . $row2 . '.',
                );
                echo json_encode($data);
                exit();
              } //close num rows
            } //close else
          } //close itemcode
        } //close foreach td itemcode
      } //close loop row

    } //close foreach child data checking

    $r = '0';
    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('D' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('D' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $type_search = array_search('Vendor Code', $search_array[0]);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        foreach ($echild as $key => $row2) {
          if ($key == $type_search) {
            if (!($row2 == '' && $row2 == null)) {
              $excel_vcode = explode(",", $row2);
              $excel_vcode = implode("','", $excel_vcode);
              $excel_vcode = "" . $excel_vcode . "";
              $vendor = $this->db->query("SELECT a.`code` AS vendor_code,a.name FROM b2b_summary.supcus a WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "' AND a.code IN ('$excel_vcode') GROUP BY a.customer_guid, a.`code` ");
              $r++;
              if ($vendor->num_rows() == 0) {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Error find Vendor Code: ' . $row2 . '.',
                );
                echo json_encode($data);
                exit();
              } //close num rows
            } //close else

          } //close itemcode
        } //close foreach td itemcode
      } //close loop row

    } //close foreach child data checking


    $lexheader = rtrim($exheader, ',');

    $string = '';
    $string_main = '';
    $typehead = '';
    $valuechild = '';
    $i = '0';

    for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
      //  Read a row of data into an array
      $xrowData = $sheet->rangeToArray('A' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

      $search_array = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

      $exchild = '';

      //if($this->isEmptyRow(reset($xrowData))) { continue; }

      foreach ($xrowData as $echild) {
        unset($checking_child);
        $v = 0;
        foreach ($echild as $key => $row2) {
          if ($v == '0') {
            $row2 = strtoupper($row2);
          }

          if ($v == '1') {
            $row2 = strtoupper($row2);
          }

          if ($v == '3') {
            $acc_no = $row2;
          }

          if ($v == '7') {
            if ($row2 == 'outright' || $row2 == 'Outright') {
              $row2 = 'outright';
            } else if ($row2 == 'consignment' || $row2 == 'Consignment') {
              $row2 = 'consignment';
            } else if ($row2 == 'both' || $row2 == 'Both') {
              $row2 = 'both';
            } else if ($row2 == 'outright iks' || $row2 == 'Outright iks') {
              $row2 = 'outright_iks';
            } else if ($row2 == 'waive consign' || $row2 == 'Waive consign') {
              $row2 = 'waive_consign';
            } else if ($row2 == 'waive outright' || $row2 == 'Waive outright') {
              $row2 = 'waive_outright';
            } else {
              $check_template = $this->db->query("SELECT template_guid,template_name FROM `b2b_invoice`.`template_settings_general` WHERE template_name = '$row2'");
              if ($check_template->num_rows() > 0) {
                $row2 = $check_template->row('template_guid');
              } else {
                $data = array(
                  'para1' => 1,
                  'msg' => 'Error find Memo Type: ' . $row2 . '. (Example: outright, consignment, both, outright iks, waive consign, waive outright, or go to B2B Invoice Template Settings check the template name)',
                );
                echo json_encode($data);
                exit();
              }
            }
          }
          if (in_array($key, $check_escape_header_index)) {
            continue;
          }
          // type->value
          if ($v == '3') {
            $exchild .= "'" . addslashes($row2) . "','" . addslashes($acc_no) . "',";
          } else {
            $exchild .= "'" . addslashes($row2) . "',";
          }

          $v++;
        } //close foreach
        $register_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        //$re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM `register_new`  WHERE  SUBSTRING(register_no, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('re_no');
        $todaydate = date('Ymd');
        $todaydate2 = substr($todaydate, 2);
        $re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(register_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM lite_b2b.`register_new`  WHERE  SUBSTRING(register_no, - 10, 6) = '$todaydate2' ")->row('re_no');
        $register_no = $this->db->query("SELECT concat( '$todaydate2', '$re_no' ) as refno")->row('refno');
        $register_number = $register_no + $i;

        $exchild_main .= "(" . $exchild . "'$register_guid','$now','$user_id','$now','$user_id','1','$customer_guid','$register_number','$supplier_guid[$i]'),";

        $set_supplier_exchild .= "('$supplier_info_guid','$register_guid'),";
      } //5
      $i++;
    } //close loop row

    $exchild_main = rtrim($exchild_main, ',');
    $set_supplier_exchild = rtrim($set_supplier_exchild, ',');

    if ($exchild_main == '' || $exchild_main == null) {
      $data = array(
        'para1' => 1,
        'msg' => 'No Data.',
      );
      echo json_encode($data);
      exit();
    }

    $insert_main = $this->db->query("INSERT INTO lite_b2b.register_new (`acc_name`,`comp_name`,`comp_no`,`acc_no`,`store_code`,`comp_email`,`comp_contact`,`second_comp_contact`,`memo_type`,`register_guid`,`create_at`,`create_by`,`update_at`,`update_by`,`isactive`,customer_guid,register_no,supplier_guid) VALUES $exchild_main ");

    $insert_supplier_info = $this->db->query("INSERT INTO lite_b2b.set_supplier_info (`supplier_info_guid`,register_guid) VALUES $set_supplier_exchild ");

    //echo $this->db->last_query(); die;

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Successfully Import',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error Import.',

      );
      echo json_encode($data);
    }

    // }//close else for success upload file
  } //close file upload

  public function remove_online_form()
  {
    $register_guid = $this->input->post('register_guid');
    $table_1 = $this->input->post('table_name1');
    $table_2 = $this->input->post('table_name2');

    if ($register_guid == '' || $register_guid == null || $register_guid == 'null') {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid GUID.',

      );
      echo json_encode($data);
      exit();
    }

    if ($table_1 == '' || $table_1 == null || $table_1 == 'null') {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid Table Name 1.',

      );
      echo json_encode($data);
      exit();
    }

    if ($table_2 == '' || $table_2 == null || $table_2 == 'null') {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid Table Name 2.',

      );
      echo json_encode($data);
      exit();
    }

    $delete_form = $this->db->query("DELETE FROM lite_b2b.$table_1 WHERE register_guid = '$register_guid' ");

    $delete_set_supplier_info = $this->db->query("DELETE FROM lite_b2b.$table_2 WHERE register_guid = '$register_guid' ");

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Removed Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error.',

      );
      echo json_encode($data);
    }
  }

  ## one off admin view
  public function one_off_admin()
  {
    if ($this->session->userdata('loginuser') == true) {
      $customer_guid = $_SESSION['customer_guid'];
      //$supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->result();
      $supplier = $this->db->query("SELECT a.* FROM set_supplier a ORDER BY a.supplier_name ASC")->result();
      $retailer = $this->db->query("SELECT DISTINCT c.acc_name FROM  acc c INNER JOIN set_supplier_group a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='" . $_SESSION['customer_guid'] . "'  ")->row('acc_name');
      $get_new_status = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`, COUNT(b.`acc_name`) AS numbering,IF(b.acc_guid = '$customer_guid' ,'1','2') AS sort FROM lite_b2b.register_add_user_main a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.acc_guid WHERE a.form_status = 'New' GROUP BY a.`customer_guid` ORDER BY sort ASC , b.acc_name ASC");
      $data = array(
        'supplier' => $supplier,
        'retailer' => $retailer,
        'get_new_status' => $get_new_status,
        'customer_guid' => $customer_guid,
      );

      $this->load->view('header');
      $this->load->view('register/one_off_admin', $data);
      $this->load->view('footer');
    } else {
      redirect('#');
    }
  }

  ## one off admin view table
  public function one_off_table()
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
    $customer_guid = $_SESSION['customer_guid'];

    $draw = intval($this->input->post("draw"));
    $start = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));
    $order = $this->input->post("order");
    $search = $this->input->post("search");
    $search = $search['value'];
    $col = 0;
    $dir = "";

    if (!empty($order)) {
      foreach ($order as $o) {
        $col = $o['column'];
        $dir = $o['dir'];
      }
    }

    if ($dir != "asc" && $dir != "desc") {
      $dir = "desc";
    }

    $valid_columns = array(
      0 => 'acc_name',
      1 => 'supplier_name',
      2 => 'template_name',
      3 => 'template_start_date',
      4 => 'template_end_date',
      5 => 'template_amount',
      6 => 'form_no',
      7 => 'form_type',
      8 => 'created_at',
      9 => 'created_by',
      10 => 'updated_at',
      11 => 'updated_by',
    );

    if (!isset($valid_columns[$col])) {
      $order = null;
    } else {
      $order = $valid_columns[$col];
    }

    if ($order != null) {
      // $this->db->order_by($order, $dir);

      $order_query = "ORDER BY " . $order . "  " . $dir;
    }

    $like_first_query = '';
    $like_second_query = '';

    if (!empty($search)) {
      $x = 0;
      foreach ($valid_columns as $sterm) {
        if ($x == 0) {
          // $this->db->like($sterm,$search);

          $like_first_query = "WHERE $sterm LIKE '%" . $search . "%'";
        } else {
          // $this->db->or_like($sterm,$search);

          $like_second_query .= "OR $sterm LIKE '%" . $search . "%'";
        }
        $x++;
      }
    }

    $limit_query = " LIMIT " . $start . " , " . $length;

    $sql = "SELECT 
              a.*,
              b.`acc_name`,
              c.`supplier_name`,
              d.`template_name`,
              CASE
                WHEN e.`register_guid` != '' 
                THEN 'Registration' 
                WHEN f.`register_guid` != '' 
                THEN 'User Account Creation' 
                WHEN g.`training_guid` != '' 
                THEN 'Training' 
                ELSE 'Advance' 
              END AS form_type 
            FROM
              lite_b2b.`register_one_off` a 
              INNER JOIN lite_b2b.`acc` b 
                ON a.`customer_guid` = b.`acc_guid` 
              INNER JOIN lite_b2b.`set_supplier` c 
                ON a.`supplier_guid` = c.`supplier_guid` 
              INNER JOIN b2b_invoice.`template_settings_general` d 
                ON a.`template_guid` = d.`template_guid` 
              LEFT JOIN lite_b2b.register_new e
              ON a.`cross_guid` = e.register_guid
              LEFT JOIN lite_b2b.register_add_user_main f
              ON a.`cross_guid` = f.register_guid
              LEFT JOIN lite_b2b.training_user_main g
              ON a.`cross_guid` = g.training_guid ";
    //WHERE a.customer_guid = '$customer_guid'";

    $query = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query . $order_query . $limit_query;

    $result = $this->db->query($query);
    //echo $this->db->last_query(); die;
    if (!empty($search)) {
      $query_filter = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query;
      $result_filter = $this->db->query($query_filter)->result();
      $total = count($result_filter);
    } else {
      $total = $this->db->query($sql)->num_rows();
    }

    $data = array();
    foreach ($result->result() as $row) {
      $nestedData['one_off_guid'] = $row->one_off_guid;
      $nestedData['customer_guid'] = $row->customer_guid;
      $nestedData['supplier_guid'] = $row->supplier_guid;
      $nestedData['template_guid'] = $row->template_guid;
      $nestedData['template_start_date'] = $row->template_start_date;
      $nestedData['template_end_date'] = $row->template_end_date;
      $nestedData['template_amount'] = $row->template_amount;
      $nestedData['isinvoice'] = $row->isinvoice;
      $nestedData['form_no'] = $row->form_no;
      $nestedData['cross_guid'] = $row->cross_guid;
      $nestedData['created_at'] = $row->created_at;
      $nestedData['created_by'] = $row->created_by;
      $nestedData['updated_at'] = $row->updated_at;
      $nestedData['updated_by'] = $row->updated_by;

      $nestedData['acc_name'] = $row->acc_name;
      $nestedData['supplier_name'] = $row->supplier_name;
      $nestedData['template_name'] = $row->template_name;
      $nestedData['form_type'] = $row->form_type;

      $data[] = $nestedData;
    }

    $json_data = array(
      "draw" => $draw,
      "recordsTotal" => $total,
      "recordsFiltered" => $total,
      "data" => $data
    );

    echo json_encode($json_data);
  }

  // edited
  public function set_archive()
  {
    $customer_guid = $_SESSION['customer_guid'];
    $register_guid = $this->input->post('register_guid');
    $user_name = $this->db->query("SELECT a.user_name FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_name');
    $now = $this->db->query("SELECT now() as now")->row('now');

    $register = $this->db->query("SELECT a.register_guid,a.isarchive,a.form_status,a.supplier_guid FROM lite_b2b.register_new a WHERE a.`register_guid` = '$register_guid' AND customer_guid =  '$customer_guid' LIMIT 1");

    if ($register->num_rows() == 0) {
      $data = array(
        'para1' => 'false',
        'msg' => 'Data No Found',

      );
      echo json_encode($data);
      exit();
    }

    $isarchive = $register->row('isarchive');
    $form_status = $register->row('form_status');
    $supplier_guid = $register->row('supplier_guid');

    if ($isarchive == '0') {
      $flag = '1';
      $status = 'Archived';

      if($form_status == 'Registered')
      {
        $check_inv_doc_cn = $this->db->query("SELECT invoice_number,inv_status,cross_guid FROM b2b_invoice.inv_doc WHERE cross_guid = '$register_guid' AND biller_guid = '$supplier_guid'");

        if($check_inv_doc_cn->num_rows() > 0)
        {
          if($check_inv_doc_cn->row('inv_status') != 'CN')
          {
            $data = array(
              'para1' => 'false',
              'msg' => 'Please Reset Invoice Status To CN : '.$check_inv_doc_cn->row('invoice_number'),
      
            );
            echo json_encode($data);
            exit();
          }
        }
        else
        {
          $data = array(
            'para1' => 'false',
            'msg' => 'Invoice not found. Please take note on creating Invoices.',
    
          );
          echo json_encode($data);
          exit();
        }
      }

    } else {
      $flag = '0';
      $status = '';
    }

    $this->db->query("UPDATE lite_b2b.register_new SET isarchive = '$flag', form_status = '$status' , update_at = '$now' , update_by = '$user_name' WHERE register_guid = '$register_guid' AND customer_guid = '$customer_guid'");

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Update Successfully',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error update',

      );
      echo json_encode($data);
    }
  }

  //upload acceptance form here
  public function upload_acceptance_form()
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
    ini_set('post_max_size', '10M');
    ini_set('upload_max_filesize', '10M');
    //set php.ini upload_max_filesize=64M
    //$acc_guid = $_SESSION['customer_guid'];
    $session_guid = $_SESSION['user_guid'];
    $file_uuid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $cur_date = $this->db->query("SELECT now() as now")->row('now');
    $created_at = $this->db->query("SELECT now() as now")->row('now');
    //print_r($file_config_main_path); die;
    $file_name = $this->input->post('file_name');
    $register_guid_data = $this->input->post('register_guid_data');
    $supplier_guid = $this->input->post('supplier_guid_data');
    $acc_guid = $this->input->post('customer_guid_data');
    $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid, 'web', 'reg_acpt', 'main_path', 'REGACPT');
    $file_config_sec_path = $this->file_config_b2b->file_path_name($acc_guid, 'web', 'reg_acpt', 'sec_path', 'REGACPTPATH');

    // due to manual upload from admin site
    if ($register_guid_data == '' || $register_guid_data == 'null' || $register_guid_data == null) {
      $register_guid_data = $this->db->query("SELECT register_guid FROM lite_b2b.register_new WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid'")->row('register_guid');
    }

    $check_data_reg = $this->db->query("SELECT register_guid FROM lite_b2b.register_new WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND register_guid = '$register_guid_data' ");
    //echo $this->db->last_query(); die;
    if ($check_data_reg->num_rows() == 0) {
      $data = array(
        'para1' => 1,
        'msg' => 'Data Not Found.',
      );
      echo json_encode($data);
      exit();
    }

    $check_rejected = $this->db->query("SELECT * FROM lite_b2b.reg_acceptance WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND register_guid = '$register_guid_data' AND `status` = 'Rejected' LIMIT 1");

    if ($check_rejected->num_rows() >= 1) {
      $r_acc_guid = $check_rejected->row('customer_guid');
      $r_register_guid = $check_rejected->row('register_guid');
      $r_url = $check_rejected->row('url');
      $rejected_guid = $check_rejected->row('upload_guid');
      $r_file_name = basename($r_url);
      $r_unlink_path = $file_config_main_path . "$r_acc_guid/$r_register_guid/$r_file_name";
      unlink($r_unlink_path);
      $update_data = $this->db->query("DELETE FROM lite_b2b.reg_acceptance WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND register_guid = '$register_guid_data' AND `status` = 'Rejected' ");
    }

    $check_data = $this->db->query("SELECT * FROM lite_b2b.reg_acceptance WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND register_guid = '$register_guid_data'  AND `status` != 'Rejected' ");

    if ($check_data->num_rows() >= 1) {
      $data = array(
        'para1' => 1,
        'msg' => 'Acceptance Form Already Exists. Please Contact Support Admin.',
      );
      echo json_encode($data);
      exit();
    }

    $file_name = str_replace(' ', '_', $file_name);
    $file_name = str_replace('&', '', $file_name);
    $defined_path_acc = $file_config_main_path . $acc_guid . '/';
    $defined_path = $file_config_main_path . $acc_guid . '/' . $register_guid_data . '/';

    $user_id = $this->db->query("SELECT user_id FROM lite_b2b.set_user WHERE user_guid = '$session_guid' ")->row('user_id');

    //print_r($file_name); die;
    $extension = explode('.', $file_name);

    if (count($extension) > 2) {
      $data = array(
        'para1' => 1,
        'msg' => 'Error File Name. Please remove comma dot for naming',
      );
      echo json_encode($data);
      exit();
    }

    if (!file_exists($defined_path_acc)) {
      mkdir($defined_path_acc, 0777);
    }

    if (!file_exists($defined_path)) {
      mkdir($defined_path, 0777);
    }

    //if want add date uncomment here @@@@@
    $cur_date = str_replace(' ', '_', $cur_date);
    $cur_date = str_replace(':', '', $cur_date);
    $file_name = $cur_date . '_' . $file_name;

    $unlink_path = $file_config_main_path . $acc_guid . '/' . $register_guid_data . '/' . $file_name;
    $unlink_path_check = $file_config_sec_path . $acc_guid . '/' . $register_guid_data . '/' . $file_name . '';

    // if(file_exists($unlink_path)){
    // unlink($unlink_path);
    // }

    $check_path = $file_config_main_path . $acc_guid . '/' . $register_guid_data . '/' . $file_name;

    if (file_exists($check_path)) {
      $data = array(
        'para1' => 1,
        'msg' => 'Document File Name Exists.',
      );
      echo json_encode($data);
      exit();
    }

    $config['upload_path']          = $defined_path;
    $config['allowed_types']        = '*';
    $config['max_size']             = 500000000;
    $config['file_name'] = $file_name;
    //var_dump( $this->input->post('file') );die; 
    //print_r($this->input->post());die;
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file')) {
      $error = array('error' => $this->upload->display_errors());

      if (null != $error) {
        $data = array(
          'para1' => 1,
          'msg' => $this->upload->display_errors(),
        );
        echo json_encode($data);
        exit();
      } //close else

    } else {
      $data = array('upload_data' => $this->upload->data());

      //$filename = $defined_path_1.$data['upload_data']['file_name'];

      if (in_array('IAVA', $_SESSION['module_code'])) {

        $update_reg = $this->db->query("UPDATE `lite_b2b`.`register_new` SET isacceptance = '1' WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND register_guid = '$register_guid_data'");

        $insert_data = $this->db->query("INSERT INTO `lite_b2b`.`reg_acceptance` (`acceptance_guid`, `customer_guid`, `supplier_guid`,`register_guid`, `status`, `url`, `created_at`, `created_by`) VALUES ('$file_uuid', '$acc_guid', '$supplier_guid' , '$register_guid_data', 'Accepted', '$unlink_path_check','$created_at', '$user_id');");
      } else {

        $update_reg = $this->db->query("UPDATE `lite_b2b`.`register_new` SET isacceptance = '1' WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND register_guid = '$register_guid_data'");

        $insert_data = $this->db->query("INSERT INTO `lite_b2b`.`reg_acceptance` (`acceptance_guid`, `customer_guid`, `supplier_guid`,`register_guid`, `status`, `url`, `created_at`, `created_by`) VALUES ('$file_uuid', '$acc_guid', '$supplier_guid' , '$register_guid_data', 'Pending', '$unlink_path_check','$created_at', '$user_id');");
      }
    }

    $error = $this->db->affected_rows();

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'msg' => 'Upload Completed.',
        //'link' => $url_link,
      );
      echo json_encode($data);
      exit();
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Error Upload Data.',
        //'link' => 'Unknown URL.',

      );
      echo json_encode($data);
      exit();
    }
  }
}
