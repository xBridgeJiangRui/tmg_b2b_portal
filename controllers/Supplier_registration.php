<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_registration extends CI_Controller
{

  public function welcome_message()
  {
    $register_guid = $_REQUEST['link'];
    $form_type = $_REQUEST['form'];

    $register = $this->db->query("SELECT a.*, b.* FROM lite_b2b.register_new a LEFT JOIN set_supplier_info b ON a.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid'");

    $form_status = $register->row('form_status');

    $customer_guid = $register->row('customer_guid');
    //print_r($customer_guid); die;

    $file_config_acceptance_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'acpt_path', 'ACPTPDF');
    $acceptance_path = $file_config_acceptance_path.'TMG_ACCEPTANCE_FORM.pdf';
    //$acceptance_path = "/b2b-pdf/blast_pdf/Letter_of_acceptance_by_suppliers.pdf.pdf";
    //$acceptance_path = file_get_contents($acceptance_path);

    //print_r($acceptance_path); die;
    if ($form_status == 'New') {
      if ($form_type == '1') {
        $form_name = 'Online Registration Form';
        $content1 = 'All registered users will receive login details once registered successfully.';
        $reg_url = base_url() . 'index.php/Supplier_registration/register_form_edit?link=' . $register_guid . '';
      }
      // else if($form_type == '2')
      // {
      //   $form_name = 'User Account Creation Form';
      //   $content1 = 'All registered users will receive login details once registered successfully.';
      //   $reg_url = 'https://localhost/b2b_portal/index.php/Supplier_registration/vendor_form_edit?link='.$register_guid.'';
      // }
      // else if($form_type == '3')
      // {
      //   $form_name = 'Online Training Form';
      //   $content1 = 'All particpant users will receive email when training session is ready.';
      //   $reg_url = 'https://localhost/b2b_portal/index.php/Supplier_registration/training_form_edit?link='.$register_guid.'';
      // }
      else {
        echo "<script> window.history.back(); </script>";
      }

      $data = array(
        'register_guid' => $register_guid,
        'reg_url' => $reg_url,
        'form_name' => $form_name,
        'content1' => $content1,
        'memo_type' => $register->row('memo_type'),
        'supplier_name' => preg_replace('/\s+/', '_', $register->row('comp_name')),
        'acceptance_path' => $acceptance_path,
      );

      $this->load->view('header_s');
      $this->load->view('register/supplier_submit_template', $data);
      $this->load->view('footer_s' );  
    } else {
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    }
  }

  public function register_form_edit()
  {
    $register_guid = $_REQUEST['link'];

    $register = $this->db->query("SELECT a.*, b.* FROM lite_b2b.register_new a LEFT JOIN set_supplier_info b ON a.register_guid = b.register_guid WHERE b.`register_guid` = '$register_guid'");

    // echo date('Y-m-d H:m:s', strtotime($get_duration->row('created_at').'2022-07-02 02:54:38 + 30 minute'));
    // die;
    // keep log
    if ($_SESSION['user_group_name'] != 'SUPER_ADMIN' && $_GET['platform'] == 'web') {

      if ($register->row('form_status') == 'Send' || $register->row('form_status') == 'Save-Progress') {

        $get_duration = $this->db->query("SELECT a.customer_guid,a.register_guid,a.form_status,a.username,a.ip_address,a.created_at,a.created_by
        FROM lite_b2b.register_form_log AS a
        WHERE a.register_guid = '$register_guid'
        ORDER BY a.created_at DESC
        LIMIT 1");


        $ip_address = $_SERVER['REMOTE_ADDR'];

        $user_id = $_SESSION['user_guid'];

        if ($user_id == '' || $user_id == null) {
          $user_id = 'guest';
        }

        if ($get_duration->row('created_at') == '') {
          // echo 'insert new record';
          $data = array(
            "customer_guid" => $register->row('customer_guid'),
            "register_guid" => $register_guid,
            "form_status" => $register->row('form_status'),
            "username" => $user_id,
            'ip_address' => $ip_address,
            "created_at" => date("Y-m-d H:i:s"),
            "created_by" => $user_id,
          );

          $this->db->insert("lite_b2b.register_form_log", $data);
        } else if (strtotime(date("Y-m-d H:i:s")) >= (strtotime($get_duration->row('created_at')) + 1800)) {

          // insert new record
          $data = array(
            "customer_guid" => $register->row('customer_guid'),
            "register_guid" => $register_guid,
            "form_status" => $register->row('form_status'),
            "username" => $user_id,
            'ip_address' => $ip_address,
            "created_at" => date("Y-m-d H:i:s"),
            "created_by" => $user_id,
          );

          $this->db->insert("lite_b2b.register_form_log", $data);
        } else if ((strtotime($get_duration->row('created_at')) + 1800) >= strtotime(date("Y-m-d H:i:s")) && $ip_address != $get_duration->row('ip_address')) {
          // return error
          echo "<script> alert('Someone Viewed,Please visit after " . date('Y-m-d H:m:s', (strtotime($get_duration->row('created_at')) + 1800)) . "');</script>";
          echo "<script> document.location='" . base_url() . "index.php/login_c/customer' </script>";
          exit();
        }
      }
    }

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

    $tick_supply_type = $register_charge_type->row('template_type');

    $register_child = $this->db->query("SELECT a.*, b.`register_mapping_guid`, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code FROM lite_b2b.register_child_new a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    $register_child_training = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' ");

    $customer_guid = $register->row('customer_guid');

    $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'sec_path', 'REGPDF');

    $defined_path = $file_config_main_path;

    if ($customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7' || $customer_guid == '13EE932D98EB11EAB05B000D3AA2838A') {
      $file_config_acceptance_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'online_form', 'acpt_path', 'ACPTPDF');
      $acceptance_path = $file_config_acceptance_path;
    } else {
      $acceptance_path = 'hide';
    }

    $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");

    $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid'  AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");

    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");

    $get_user_group = $this->db->query("SELECT user_group_guid,user_group_name FROM lite_b2b.set_user_group WHERE group_info_status >= '1'");

    if($get_user_group->num_rows() == 0)
    {
      echo "<script> alert('Please Contact Support. Invalid User Group Found.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    }

    $supplier_guid = $get_supp->row('supplier_guid');

    $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");

    $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

    $vendor = $register->row('store_code');
    $myArray_1 = explode(',', $vendor);
    $myArray = array_filter($myArray_1); //show vendor code array

    if ($register->num_rows() == 0) {
      echo "<script> alert('Invalid URL.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    }

    $form_status = $register->row('form_status');

    if ($form_status == 'Processing') {
      echo "<script> alert('Processing Stage.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Emailed') {
      echo "<script> alert('Please Check Your Mail Inbox.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Registered') {
      echo "<script> alert('Registered Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Received') {
      echo "<script> alert('You already Upload Your Acceptance Form.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Archived') {
      echo "<script> alert('Registration Form is not valid.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
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
      'defined_path' => $defined_path,
      'register_charge_type' => $register_charge_type,
      'reg_memo_type' => $reg_memo_type,
      'acceptance_path' => $acceptance_path,
      'tick_supply_type' => $tick_supply_type,
      'get_user_group' => $get_user_group,
      'get_user_group_guid' => $get_user_group_guid,
    );

    $this->load->view('header_s');
    $this->load->view('register/supplier_registration_form', $data);
    $this->load->view('footer_s');
  }

  public function register_update()
  {
    $register_guid = $_REQUEST['link'];

    $register = $this->db->query("SELECT * FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid' ");
    //$user_id = 'Supplier';
    $status_view = $register->row('form_status');

    if (($status_view == 'New') || ($status_view == 'Emailed') || ($status_view == 'Registered') || ($status_view == 'Processing') || ($status_view == 'Received')) {
      echo "<script> alert('Cannot do any action.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/register_form_edit?link=" . $register_guid . "' </script>";
      die;
    }

    // check user group here
    $admin_user_group = $this->db->query("SELECT user_group_guid FROM lite_b2b.set_user_group WHERE admin_active = '1' AND group_info_status = '1'")->row('user_group_guid');
  
    $check_set_user_group = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND a.user_group_info = '$admin_user_group' AND part_type = 'registration' GROUP BY a.register_c_guid")->result_array();
  
    // if(count($check_set_user_group) == 0)
    // {
    //   echo "<script> alert('Error, Your User information didn't have one Admin User Group. Please edit immediately.');</script>";
    //   echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/register_form_edit?link=" . $register_guid . "' </script>";
    //   die;
    // }
    // else if (count($check_set_user_group) > 1)
    // {
    //   echo "<script> alert('Error, Your User information more than one Admin User Group. Please edit immediately.');</script>";
    //   echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/register_form_edit?link=" . $register_guid . "' </script>";
    //   die;
    // }

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
    //$acc_no = $this->input->post('acc_no');
    //$acc_no_other = $this->input->post('acc_no_other');
    $isdelete = $this->input->post('isdelete');
    $created_at = $this->db->query("SELECT now() as now")->row('now');
    $updated_at = $this->db->query("SELECT now() as now")->row('now');
    $store_code = $register->row('store_code');
    //$myArray = explode(',', $store_code);
    //$diff_code = array_diff($acc_no, $myArray);
    //$combine = array_merge($myArray, $diff_code);

    $save_status = $this->input->post('save_status');

    $check_child_data = $this->db->query("SELECT a.register_guid FROM lite_b2b.register_child_new a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    if ($save_status == '1') {
      $form_status = 'Save-Progress';
    } else {
      if ($check_child_data->num_rows() == 0) {
        $form_status = 'Save-Progress';
      } else {
        $form_status = 'New';
      }
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
      //'vendor_code_remark' =>implode(',', $acc_no_other),
      'org_email' => $comp_mail,
      'org_part_email' => $comp_email,
      'update_at' => $updated_at,
      'update_by' => 'Supplier',
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

    // $data_log_main = array(
    //   'register_guid' => $register_guid,
    //   'status' => 'log_main',
    //   'created_at' => $updated_at,
    //   'created_by' => 'Supplier'
    // );
    // $this->db->insert('lite_b2b.register_log', $data_log_main);

    if ($form_status == 'New') {
      echo "<script> alert('Submit Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/welcome_message?link=" . $register_guid . "&form=1&validdata=yes' </script>";
    } else {
      if ($check_child_data->num_rows() == 0) {
        echo "<script> alert('Save Successfully. Please Add Part 2 Login Account(s) Information and Submit Registration Form.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/register_form_edit?link=" . $register_guid . "' </script>";
      } else {
        echo "<script> alert('Save Successfully. To Complete Registration Process, Please Submit the Registration Form.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/register_form_edit?link=" . $register_guid . "' </script>";
      }
    }
  }

  public function vendor_form_edit()
  {
    $register_guid = $_REQUEST['link'];

    $register = $this->db->query("SELECT a.*,b.* FROM lite_b2b.register_add_user_main a LEFT JOIN set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid'");

    $register_child = $this->db->query("SELECT a.*, b.`register_mapping_guid`, GROUP_CONCAT(b.`mapping_type` ORDER BY b.`mapping_type` DESC) AS mapping_type, GROUP_CONCAT(b.`ven_agency`) AS ven_agency, GROUP_CONCAT(b.`ven_code`) AS ven_code FROM lite_b2b.register_add_user_child a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    $register_child_training = $this->db->query("SELECT a.* FROM lite_b2b.register_add_user_child a WHERE a.`register_guid` = '$register_guid' AND part_type = 'training' ");

    $customer_guid = $register->row('customer_guid');

    $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");

    $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid'  AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");

    $get_supp = $this->db->query("SELECT supplier_guid FROM register_add_user_main b WHERE b.`register_guid` = '$register_guid'");

    $supplier_guid = $get_supp->row('supplier_guid');

    $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");

    $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

    $vendor = $register->row('store_code');
    $myArray_1 = explode(',', $vendor);
    $myArray = array_filter($myArray_1); //show vendor code array

    if ($register->num_rows() == 0) {
      echo "<script> alert('Invalid URL.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    }

    $form_status = $register->row('form_status');

    if ($form_status == 'Processing') {
      echo "<script> alert('Processing Stage.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Emailed') {
      echo "<script> alert('Please Check Your Mail Inbox.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Registered') {
      echo "<script> alert('Registered Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Received') {
      echo "<script> alert('You already Upload Your Acceptance Form.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
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
    );

    $this->load->view('header_s');
    $this->load->view('register/supplier_vendor_form', $data);
    $this->load->view('footer_s');
  }

  public function register_vendor_update()
  {
    $register_guid = $_REQUEST['link'];
    $register = $this->db->query("SELECT * FROM lite_b2b.register_add_user_main a INNER JOIN lite_b2b.set_supplier_info b ON a.register_guid = b.register_guid WHERE a.`register_guid` = '$register_guid' ");
    $user_id = 'Supplier';

    $status_view = $register->row('form_status');

    if (($status_view == 'New') || ($status_view == 'Emailed') || ($status_view == 'Registered') || ($status_view == 'Processing') || ($status_view == 'Received')) {
      echo "<script> alert('Cannot do any action.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/register_form_edit?link=" . $register_guid . "' </script>";
      die;
    }
    //$acc_no = $this->input->post('acc_no');
    $created_at = $this->db->query("SELECT now() as now")->row('now');
    $updated_at = $this->db->query("SELECT now() as now")->row('now');

    $save_status = $this->input->post('save_status');

    if ($save_status == '1') {
      $form_status = 'Save-Progress';
    } else {
      $form_status = 'New';
    }

    // if($acc_no == '')
    // {
    //     $acc_no = '';
    // }
    // else
    // {
    //     $acc_no = implode(',', $acc_no);
    // }

    $data = array(
      //'acc_no' => $acc_no,
      'update_at' => $updated_at,
      'update_by' => $user_id,
      'isactive' => 0,
      'form_status' => $form_status
    );

    $this->db->where('register_guid', $register_guid);
    $this->db->update('register_add_user_main', $data);

    if ($form_status == 'New') {
      echo "<script> alert('Submit Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
    } else {
      echo "<script> alert('Save Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/vendor_form_edit?link=" . $register_guid . "' </script>";
    }
  }

  public function training_form_edit()
  {
    $training_guid = $_REQUEST['link'];

    $training = $this->db->query("SELECT a.*, b.* FROM lite_b2b.training_user_main a LEFT JOIN set_supplier_info b ON a.training_guid = b.training_guid WHERE b.`training_guid` = '$training_guid'");

    $training_child_training = $this->db->query("SELECT a.* FROM lite_b2b.training_user_child a WHERE a.`training_guid` = '$training_guid' AND part_type = 'training' ");

    $customer_guid = $training->row('customer_guid');

    $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");

    $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid'  AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");

    $get_supp = $this->db->query("SELECT supplier_guid FROM training_user_main b WHERE b.`training_guid` = '$training_guid'");

    $supplier_guid = $get_supp->row('supplier_guid');

    $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");

    $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

    $vendor = $training->row('store_code');
    $myArray_1 = explode(',', $vendor);
    $myArray = array_filter($myArray_1); //show vendor code array

    $vendor_remark = $training->row('vendor_code_remark');
    $myArray_2 = explode(',', $vendor_remark);
    $myArray_2 = array_filter($myArray_2); //show vendor code remark array

    // $vendor_remark_edit = $training_child->row('vendor_code_remark');
    // $myArray_3 = explode(',', $vendor_remark_edit);
    // $myArray_3 = array_filter($myArray_3); //show vendor code remark array

    if ($training->num_rows() == 0) {
      echo "<script> alert('Invalid URL.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    }

    $form_status = $training->row('form_status');

    if ($form_status == 'Processing') {
      echo "<script> alert('Processing Stage.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Emailed') {
      echo "<script> alert('Please Check Your Mail Inbox.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Registered') {
      echo "<script> alert('Registered Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    } else if ($form_status == 'Received') {
      echo "<script> alert('You already Upload Your Acceptance Form.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
      exit();
    }

    $data = array(
      'supplier_guid' => $supplier_guid,
      'customer_guid' => $customer_guid,
      'training' => $training,
      'training_child_training' => $training_child_training,
      'acc_branch' => $acc_branch,
      'ven_agency_sql' => $ven_agency_sql, // outlet array
      //'vendor_code_sql' => $vendor_code_sql, // not use 
      'myArray' => $myArray, // Vendor Code (refer to Retailer) array
      'myArray_2' => $myArray_2, // Vendor Code remark array
      //'myArray_3' =>$myArray_3, // edit Vendor Code remark array
      'add_vendor_code' => $add_vendor_code->result(), // add vendor code
    );

    $this->load->view('header_s');
    $this->load->view('register/supplier_training_form', $data);
    $this->load->view('footer_s');
  }

  //edited
  public function training_update()
  {
    $training_guid = $_REQUEST['link'];
    $register = $this->db->query("SELECT * FROM lite_b2b.training_user_main a INNER JOIN lite_b2b.set_supplier_info b ON a.training_guid = b.training_guid WHERE a.`training_guid` = '$training_guid' ");
    $user_id = 'Supplier';

    $status_view = $register->row('form_status');

    if (($status_view == 'New') || ($status_view == 'Emailed') || ($status_view == 'Registered') || ($status_view == 'Processing') || ($status_view == 'Received')) {
      echo "<script> alert('Cannot do any action.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/register_form_edit?link=" . $register_guid . "' </script>";
      die;
    }

    $comp_email = $this->input->post('comp_email');
    $supply_outright = $this->input->post('supply_outright'); //new
    $supply_consignment = $this->input->post('supply_consignment'); //new
    $updated_at = $this->db->query("SELECT now() as now")->row('now');
    $save_status = $this->input->post('save_status');

    if ($save_status == '1') {
      $form_status = 'Save-Progress';
    } else {
      $form_status = 'New';
    }

    $data = array(
      'org_part_email' => $comp_email,
      'update_at' => $updated_at,
      'update_by' => $user_id,
      'isactive' => 0,
      'form_status' => $form_status
    );

    $this->db->where('training_guid', $training_guid);
    $this->db->update('training_user_main', $data);

    $data = array(
      'supply_outright' => $supply_outright,
      'supply_consignment' => $supply_consignment,
    );

    $this->db->where('training_guid', $training_guid);
    $this->db->update('set_supplier_info', $data);

    if ($form_status == 'New') {
      echo "<script> alert('Submit Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/' </script>";
    } else {
      echo "<script> alert('Save Successfully.');</script>";
      echo "<script> document.location='" . base_url() . "index.php/Supplier_registration/training_form_edit?link=" . $training_guid . "' </script>";
    }
  }

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

    $this->db->query("UPDATE $table_main SET store_code = '$myArray' WHERE register_guid = '$register_guid'");

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
    $user_id = 'Supplier';
    $get_supp = $this->db->query("SELECT supplier_guid FROM register_new b WHERE b.`register_guid` = '$register_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $ven_agency = implode(",", $ven_agency);
    $ven_agency = "" . $ven_agency . "";

    $ven_code = implode(",", $ven_code);
    $ven_code = "" . $ven_code . "";

    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.register_child_new a WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    foreach ($register_child->result() as $key) {
      $check_ven_name = $key->ven_name;
      $check_ven_email = $key->ven_email;

      if ($ven_name == $check_ven_name) {
        $data = array(
          'para1' => 1,
          'check_child_data' => 0,
          'msg' => 'Duplicate Name.',

        );
        echo json_encode($data);
        die;
      }

      if ($ven_email == $check_ven_email) {
        $data = array(
          'para1' => 1,
          'check_child_data' => 0,
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

    $check_user_info = $this->db->query("SELECT a.user_guid,b.supplier_guid,c.supplier_name FROM lite_b2b.set_user a 
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
        $create_user_guid = $row->user_guid;

        // why need check group_info_status is due to exlcude out admin outright / consign and user
        $check_is_admin_user_exists = $this->db->query("SELECT b.user_group_guid,CONCAT(b.user_id,' - ',d.supplier_name ,' - ',c.user_group_name,'\n') AS concat_user_info FROM lite_b2b.set_supplier_user_relationship a
        INNER JOIN lite_b2b.set_user b
        ON a.user_guid = b.user_guid
        AND a.customer_guid = b.acc_guid
        INNER JOIN lite_b2b.set_user_group c
        ON b.user_group_guid = c.user_group_guid
        AND c.admin_active = '1'
        INNER JOIN lite_b2b.set_supplier d
        ON a.supplier_guid = d.supplier_guid
        WHERE a.supplier_guid = '$user_mapping_supplier_guid' 
        AND a.customer_guid = '$customer_guid'
        AND a.user_guid = '$create_user_guid'
        AND c.group_info_status NOT IN ('1','4','5')
        GROUP BY b.user_guid,b.acc_guid")->result_array();
  
        if(count($check_is_admin_user_exists) > 0 )
        {
          $user_details_admin = implode(",",array_filter(array_column($check_is_admin_user_exists,'concat_user_info')));
  
          $data = array(
            'para1' => 1,
            'msg' => $user_details_admin . 'Please contact support.',
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
      'mapping_type' => 'outlet'
    );

    $this->db->insert('register_child_mapping', $data_3);

    $data_4 = array(
      'register_guid' => $register_guid,
      'register_c_guid' => $c_guid,
      'register_mapping_guid' => $m_guid_1,
      'ven_code' => $ven_code,
      'vendor_code_remark' => $remark_no,
      'mapping_type' => 'code'
    );

    $this->db->insert('register_child_mapping', $data_4);

    $error = $this->db->affected_rows();

    $data_log_child = array(
      'register_guid' => $register_guid,
      'status' => 'log_child',
      'created_at' => $updated_at,
      'created_by' => $user_id
    );
    $this->db->insert('lite_b2b.register_log', $data_log_child);

    $check_child_data = $this->db->query("SELECT a.register_guid FROM lite_b2b.register_child_new a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    if ($error > 0) {

      $data = array(
        'para1' => 0,
        'check_child_data' => $check_child_data->num_rows(),
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
    $edit_user_group = $this->input->post('edit_user_group'); // new added 26/07/2023
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = 'Supplier';
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

    $check_user_info = $this->db->query("SELECT a.user_guid,b.supplier_guid,c.supplier_name FROM lite_b2b.set_user a 
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
        $create_user_guid = $row->user_guid;

        $check_is_admin_user_exists = $this->db->query("SELECT b.user_group_guid,CONCAT(b.user_id,' - ',d.supplier_name ,' - ',c.user_group_name,'\n') AS concat_user_info FROM lite_b2b.set_supplier_user_relationship a
        INNER JOIN lite_b2b.set_user b
        ON a.user_guid = b.user_guid
        AND a.customer_guid = b.acc_guid
        INNER JOIN lite_b2b.set_user_group c
        ON b.user_group_guid = c.user_group_guid
        AND c.admin_active = '1'
        INNER JOIN lite_b2b.set_supplier d
        ON a.supplier_guid = d.supplier_guid
        WHERE a.supplier_guid = '$user_mapping_supplier_guid' 
        AND a.customer_guid = '$customer_guid'
        AND a.user_guid = '$create_user_guid'
        AND c.group_info_status NOT IN ('1','4','5')
        GROUP BY b.user_guid,b.acc_guid")->result_array();
  
        if(count($check_is_admin_user_exists) > 0 )
        {
          $user_details_admin = implode(",",array_filter(array_column($check_is_admin_user_exists,'concat_user_info')));
  
          $data = array(
            'para1' => 1,
            'msg' => $user_details_admin . 'Please contact support.',
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
    $user_id = 'Supplier';
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
    $user_id = 'Supplier';
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

    $data_2 = array(
      'part_name' => $part_name,
      'part_ic' => $part_ic,
      'part_mobile' => $part_mobile,
      'part_email' => $part_email,
      'isdelete' => 0,
    );

    $this->db->where('register_c_guid', $register_c_guid);
    $this->db->update('register_child_new', $data_2);

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

  public function edit_part_info_training()
  {
    $training_guid = $this->input->post('training_guid');
    $customer_guid = $this->input->post('customer_guid');
    $training_c_guid = $this->input->post('training_c_guid');
    $part_name = $this->input->post('part_name');
    $part_ic = $this->input->post('part_ic');
    $part_mobile = $this->input->post('part_mobile');
    $part_email = $this->input->post('part_email');
    $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = 'Supplier';
    $get_supp = $this->db->query("SELECT supplier_guid FROM training_user_main b WHERE b.`training_guid` = '$training_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');
    $register_child = $this->db->query("SELECT a.* FROM lite_b2b.training_user_child a WHERE a.`training_guid` = '$training_guid' AND a.`training_c_guid` != '$training_c_guid' AND part_type = 'training' GROUP BY a.training_c_guid");

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

    $data_2 = array(
      'part_name' => $part_name,
      'part_ic' => $part_ic,
      'part_mobile' => $part_mobile,
      'part_email' => $part_email,
      'isdelete' => 0,
    );

    $this->db->where('training_c_guid', $training_c_guid);
    $this->db->update('training_user_child', $data_2);

    $data_1 = array(
      'update_at' => $updated_at,
      'update_by' => $user_id
    );

    $this->db->where('training_guid', $training_guid);
    $this->db->update('training_user_main', $data_1);

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
    $user_id = 'Supplier';
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
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = 'Supplier';
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

  public function add_training_info()
  {
    $training_guid = $this->input->post('training_guid');
    $customer_guid = $this->input->post('customer_guid');
    $part_name = $this->input->post('part_name');
    $part_ic = $this->input->post('part_ic');
    $part_mobile = $this->input->post('part_mobile');
    $part_email = $this->input->post('part_email');
    $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    $user_id = 'Supplier';
    $get_supp = $this->db->query("SELECT supplier_guid FROM training_user_main b WHERE b.`training_guid` = '$training_guid'");
    $supplier_guid = $get_supp->row('supplier_guid');

    $training_child = $this->db->query("SELECT a.* FROM lite_b2b.training_user_child a WHERE a.`training_guid` = '$training_guid' AND part_type = 'training' GROUP BY a.training_c_guid");

    foreach ($training_child->result() as $key) {
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

    $this->db->where('training_guid', $training_guid);
    $this->db->update('training_user_main', $data_1);

    $data_2 = array(
      'supplier_guid' => $supplier_guid,
      'training_guid' => $training_guid,
      'training_c_guid' => $c_guid,
      'part_name' => $part_name,
      'part_ic' => $part_ic,
      'part_mobile' => $part_mobile,
      'part_email' => $part_email,
      'isdelete' => 0,
      'part_type' => 'training',
      'created_at' => $updated_at,
      'created_by' => $user_id
    );

    $this->db->insert('training_user_child', $data_2);

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

    $check_child_data = $this->db->query("SELECT a.register_guid FROM lite_b2b.register_child_new a LEFT JOIN lite_b2b.`register_child_mapping` b ON a.`register_c_guid` = b.`register_c_guid` WHERE a.`register_guid` = '$register_guid' AND part_type = 'registration' GROUP BY a.register_c_guid");

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'check_child_data' => $check_child_data->num_rows(),
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

  public function active_status_training()
  {
    $training_guid = $this->input->post('training_guid');
    $training_c_guid = $this->input->post('training_c_guid');
    //$isdelete = $this->input->post('isdelete');

    if ($training_guid != '' || $training_c_guid != '') {
      $delete_training = $this->db->query("DELETE FROM lite_b2b.training_user_child WHERE training_guid = '$training_guid' AND training_c_guid = '$training_c_guid' ");
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

  public function is_download()
  {
    $register_guid = $this->input->post('register_guid');

    if ($register_guid != '' || $register_guid != 'null' || $register_guid != null) {
      $update_data = $this->db->query("UPDATE lite_b2b.register_new SET term_download = '1' WHERE register_guid = '$register_guid'");
    }

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Please Check Your New Tab browser To Complete Download Process.',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Please Check Your New Tab browser To Complete Download Process.',

      );
      echo json_encode($data);
    }
  }

  public function insert_terms_data()
  {
    $register_guid = $this->input->post('register_guid');
    $customer_guid = $this->input->post('customer_guid');
    $supplier_guid = $this->input->post('supplier_guid');
    $hidden_memo = $this->input->post('hidden_memo');
    $data_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    $created_at = $this->db->query("SELECT now() as now")->row('now');
    $current_date = $this->db->query("SELECT CURDATE() as curdate")->row('curdate');
    $set_date = date('Y-m-15');
    $one_off_start_date = '1001-01-01';
    $one_off_end_date = '1001-01-01';

    // if($customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7')
    // {
    //   $current_date = '2022-06-30';
    //   $set_date = '2022-06-15';
    // }

    if (($register_guid == '') || ($register_guid == 'null') || ($register_guid == null)) {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid GUID. Please click again the URL link.',

      );
      echo json_encode($data);
      exit();
    }

    if (($customer_guid == '') || ($customer_guid == 'null') || ($customer_guid == null)) {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid GUID. Please click again the URL link.',

      );
      echo json_encode($data);
      exit();
    }

    if (($supplier_guid == '') || ($supplier_guid == 'null') || ($supplier_guid == null)) {
      $data = array(
        'para1' => 1,
        'msg' => 'Invalid GUID. Please click again the URL link.',

      );
      echo json_encode($data);
      exit();
    }

    if ($hidden_memo == 'outright_iks') {
      $hidden_memo = 'D33B1061ECC611EAA4AF000D3AA2838A'; // one off 200 and reg fee need change at b2b invoice 200
    }

    $customer_name = $this->db->query("SELECT acc_name FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'")->row('acc_name');

    $supplier_name = $this->db->query("SELECT CONCAT(supplier_name,' (',reg_no,')') AS concat_data FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'")->row('concat_data');

    $supplier_name = addslashes($supplier_name);

    if ($current_date > $set_date) {
      $service_date = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH),'%Y-%m-01') AS service_date")->row('service_date');
      $billing_start_date = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 2 MONTH),'%Y-%m-01') AS billing_start_date")->row('billing_start_date');

      // if ($customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7')  // can be remove after 16-07-2022
      // {
      //   if ($current_date >= '2022-06-17' && $current_date <= '2022-07-15') {
      //     $service_date = '2022-07-01';
      //     $billing_start_date = '2022-09-01';
      //   } else {
      //     $service_date = $this->db->query("SELECT DATE_FORMAT(DATE_ADD('$current_date', INTERVAL 1 MONTH),'%Y-%m-01') AS service_date")->row('service_date');
      //     $billing_start_date = $this->db->query("SELECT DATE_FORMAT(DATE_ADD('$current_date', INTERVAL 2 MONTH),'%Y-%m-01') AS billing_start_date")->row('billing_start_date');
      //   }
      // }

      if ($hidden_memo != 'outright' || $hidden_memo != 'consignment' || $hidden_memo != 'both') {
        $get_price = $this->db->query("SELECT b.template_guid, b.subsequent_fee FROM b2b_invoice.template_settings_general a INNER JOIN b2b_invoice.`template_settings_child_general` b ON a.`template_guid` = b.`template_guid` WHERE a.template_guid = '$hidden_memo'");

        $store_one_off_price = $get_price->row('subsequent_fee');

        $one_off_start_date = $service_date;
        $one_off_end_date = date('Y-m-d', strtotime("+1 year", strtotime($service_date)));
        $one_off_end_date = date('Y-m-d', strtotime("-1 day", strtotime($one_off_end_date)));

        // if ($customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7') // can be remove after 16-07-2022
        // {
        //   if ($current_date < '2022-07-16') {
        //     $one_off_start_date = date('Y-m-d', strtotime("+1 month", strtotime($service_date)));
        //     $one_off_end_date = date('Y-m-d', strtotime("+1 year", strtotime($one_off_start_date)));
        //     $one_off_end_date = date('Y-m-d', strtotime("-1 day", strtotime($one_off_end_date)));
        //   }
        // }
      }
    } else {
      $service_date = date('Y-m-01');
      $billing_start_date = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH),'%Y-%m-01') AS billing_start_date")->row('billing_start_date');
      //date('Y-m-01', strtotime("+1 month"));

      // if ($customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7') // can be remove after 16-07-2022
      // {
      //   if ($current_date >= '2022-06-17' && $current_date <= '2022-07-15') {
      //     $service_date = '2022-07-01';
      //     $billing_start_date = '2022-09-01';
      //   } else {
      //     $service_date =  $this->db->query("SELECT DATE_FORMAT('$current_date','%Y-%m-01') AS billing_start_date")->row('billing_start_date');
      //     $billing_start_date = $this->db->query("SELECT DATE_FORMAT(DATE_ADD('$current_date', INTERVAL 1 MONTH),'%Y-%m-01') AS billing_start_date")->row('billing_start_date');
      //   }
      // }

      if ($hidden_memo != 'outright' || $hidden_memo != 'consignment' || $hidden_memo != 'both') {
        $get_price = $this->db->query("SELECT b.template_guid, b.subsequent_fee FROM b2b_invoice.template_settings_general a INNER JOIN b2b_invoice.`template_settings_child_general` b ON a.`template_guid` = b.`template_guid` WHERE a.template_guid = '$hidden_memo'");

        $store_one_off_price = $get_price->row('subsequent_fee');

        $one_off_start_date = $service_date;
        $one_off_end_date = date('Y-m-d', strtotime("+1 year", strtotime($service_date)));
        $one_off_end_date = date('Y-m-d', strtotime("-1 day", strtotime($one_off_end_date)));

        // if ($customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7') // can be remove after 16-07-2022
        // {
        //   if ($current_date < '2022-07-16') {
        //     $one_off_start_date = date('Y-m-d', strtotime("+1 month", strtotime($service_date)));
        //     $one_off_end_date = date('Y-m-d', strtotime("+1 year", strtotime($one_off_start_date)));
        //     $one_off_end_date = date('Y-m-d', strtotime("-1 day", strtotime($one_off_end_date)));
        //   }
        // }
      }
    }

    if (($store_one_off_price != '') && ($store_one_off_price != 'null') && ($store_one_off_price != null)) {
      $one_off_price = '<b> *RM ' . $store_one_off_price . '.00 only per annum</b>';
    } else {
      $one_off_price = '';
    }

    if ($hidden_memo == 'waive_outright' || $hidden_memo == 'waive_consign') {
      $one_off_price = '<b> Waive for first year only</b>';
    }

    if ($one_off_price != '') {
      if ($register_guid == 'D6828B45A33A11EC9786000D3AA2838A') //temparory
      {
        $service_date = '2022-01-01';
        $billing_start_date = '2022-02-01';
        $one_off_start_date = '2022-01-01';
        $one_off_end_date = '2022-12-31';

        $update_data = $this->db->query("INSERT INTO lite_b2b.reg_term_data (`data_guid`, `register_guid`, `supplier_guid` ,`customer_guid`,`supplier_name`,`customer_name`,`service_date`,`billing_start_date`,`one_off_start_date`,`one_off_end_date`,`one_off_price`,`created_at`) VALUES ( '$data_guid', '$register_guid', '$supplier_guid' ,'$customer_guid', '$supplier_name', '$customer_name', '$service_date', '$billing_start_date', '$one_off_start_date', '$one_off_end_date', '$one_off_price', '$created_at') ON DUPLICATE KEY UPDATE `supplier_name` = '$supplier_name' , `customer_name` = '$customer_name' , `service_date` = '$service_date' , `billing_start_date` = '$billing_start_date' ,`one_off_start_date` = '$one_off_start_date', `one_off_end_date` = '$one_off_end_date', `one_off_price` = '$one_off_price' ,`created_at` = '$created_at'");
      } else {
        $update_data = $this->db->query("INSERT INTO lite_b2b.reg_term_data (`data_guid`, `register_guid`, `supplier_guid` ,`customer_guid`,`supplier_name`,`customer_name`,`service_date`,`billing_start_date`,`one_off_start_date`,`one_off_end_date`,`one_off_price`,`created_at`) VALUES ( '$data_guid', '$register_guid', '$supplier_guid' ,'$customer_guid', '$supplier_name', '$customer_name', '$service_date', '$billing_start_date', '$one_off_start_date', '$one_off_end_date', '$one_off_price', '$created_at') ON DUPLICATE KEY UPDATE `supplier_name` = '$supplier_name' , `customer_name` = '$customer_name' , `service_date` = '$service_date' , `billing_start_date` = '$billing_start_date' ,`one_off_start_date` = '$one_off_start_date', `one_off_end_date` = '$one_off_end_date', `one_off_price` = '$one_off_price' ,`created_at` = '$created_at'");
      }
    } else {
      $update_data = $this->db->query("INSERT INTO lite_b2b.reg_term_data (`data_guid`, `register_guid`, `supplier_guid` ,`customer_guid`,`supplier_name`,`customer_name`,`service_date`,`billing_start_date`,`one_off_start_date`,`one_off_end_date`,`one_off_price`,`created_at`) VALUES ( '$data_guid', '$register_guid', '$supplier_guid' ,'$customer_guid', '$supplier_name', '$customer_name', '$service_date', '$billing_start_date', '1001-01-01', '1001-01-01', '$one_off_price', '$created_at') ON DUPLICATE KEY UPDATE `supplier_name` = '$supplier_name' , `customer_name` = '$customer_name' , `service_date` = '$service_date' , `billing_start_date` = '$billing_start_date' ,`one_off_start_date` = '1001-01-01', `one_off_end_date` = '1001-01-01', `one_off_price` = NULL ,`created_at` = '$created_at'");
    }

    $error = $this->db->affected_rows();

    if ($error > 0) {
      $data = array(
        'para1' => 0,
        'msg' => 'Next Process.',
        'admin_msg' => 'Update Successfull.',

      );
      echo json_encode($data);
    } else {
      $data = array(
        'para1' => 1,
        'msg' => 'Process to insert Data Error.',

      );
      echo json_encode($data);
    }
  }
}
