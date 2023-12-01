<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_renewal extends CI_Controller {

    public function renewal_list()
    {      
        if($this->session->userdata('loginuser') == true)
        {
            $customer_guid = $_SESSION['customer_guid'];
            $config_module = 'renewal';
            $config_type = 'sec_path';
            $config_code = 'SECRNL';

            $sign_config_module = 'renewal_sign';
            $sign_config_code = 'SECRNL_SIGN';

            $get_customer = $this->db->query("SELECT acc_guid, acc_name,seq 
                FROM `acc` WHERE isactive = 1 order by acc_name asc");
            
            $get_supplier = $this->db->query("SELECT a.supplier_guid,b.supplier_name 
            FROM b2b_invoice.`supplier_miscellaneous_general` a 
            INNER JOIN lite_b2b.`set_supplier` b 
            ON a.supplier_guid = b.supplier_guid
            GROUP BY a.supplier_guid 
            ORDER BY b.supplier_name ASC");

            $virtual_path = $this->db->query("SELECT `value`
            FROM lite_b2b.config
            WHERE device = 'web'
            AND module = '$config_module'
            AND type = '$config_type'
            AND code = '$config_code'")->row('value');

            $virtual_path_sign = $this->db->query("SELECT `value`
            FROM lite_b2b.config
            WHERE device = 'web'
            AND module = '$sign_config_module'
            AND type = '$config_type'
            AND code = '$sign_config_code'")->row('value');

            $data = array(
                'get_customer' => $get_customer,
                'get_supplier' => $get_supplier,
                'virtual_path' => $virtual_path,
                'virtual_path_sign' => $virtual_path_sign,
            );
        
            $this->load->view('header'); 
            $this->load->view('register/register_renewal_list', $data);  
            $this->load->view('footer' ); 
        }
        else
        {
            redirect('#');
        }
    }

    public function renewal_table()
    {
        $select_supplier_guid = $this->input->post('select_supplier_guid');
        $select_retailer_guid = $this->input->post('select_retailer_guid');
        $status_type = $this->input->post('status_type');

        if($select_supplier_guid != ''  && $select_retailer_guid != '' && $status_type != '') // all condition
        {
            $condition1 = "WHERE a.supplier_guid = '$select_supplier_guid' AND a.customer_guid = '$select_retailer_guid' AND a.is_confirm = '$status_type'";
        }
        else if($select_supplier_guid == ''  && $select_retailer_guid == '' && $status_type != '' ) // status
        {
            $condition1 = "WHERE a.is_confirm = '$status_type'";
        }
        else if($select_supplier_guid == ''  && $select_retailer_guid != '' && $status_type == '' ) // retailer 
        {
            $condition1 = "WHERE a.customer_guid = '$select_retailer_guid'";
        }
        else if($select_supplier_guid != ''  && $select_retailer_guid == '' && $status_type == '' ) // supplier 
        {
            $condition1 = "WHERE a.supplier_guid = '$select_supplier_guid'";
        }
        else if($select_supplier_guid == ''  && $select_retailer_guid != '' && $status_type != '' ) // retailer and status
        {
            $condition1 = "WHERE a.customer_guid = '$select_retailer_guid' AND a.is_confirm = '$status_type'";
        }
        else if($select_supplier_guid != ''  && $select_retailer_guid == '' && $status_type != '' ) // supplier and status
        {
            $condition1 = "WHERE a.supplier_guid = '$select_supplier_guid' AND a.is_confirm = '$status_type'";
        }
        else if($select_supplier_guid != ''  && $select_retailer_guid != '' && $status_type == '' ) // supplier and retailer 
        {
            $condition1 = "WHERE a.supplier_guid = '$select_supplier_guid' AND a.customer_guid = '$select_retailer_guid'";
        }
        else if($select_supplier_guid == ''  && $select_retailer_guid == '' && $status_type == '' ) // no filter
        {
            $condition1 = '';
        }

        $query_data = $this->db->query("SELECT a.guid, a.cross_guid, a.customer_guid, a.supplier_guid, a.old_template_guid, a.template_guid, a.acc_name, a.supplier_name, a.reg_no, a.email_add, a.cc_email_add, a.url_path, a.rejected, a.renewal_old_start_at , a.renewal_start_at, a.renewal_old_end_at, a.renewal_end_at, a.renewal_paid_at, a.cross_guid, IF( a.is_confirm = '0', 'Pending', IF( a.is_confirm = '1', 'Agree', IF( a.is_confirm = '3', 'Uploaded', IF( a.is_confirm = '4', 'PDF Approved', 'Rejected' ) ) ) ) AS `status`, a.is_confirm, b.`template_name`, b.`template_description`, b.`template_group`, c.template_name AS old_template_name, IF(d.file_name = '','', d.file_name) AS file_name, a.created_at, a.created_by, a.updated_at, a.updated_by, a.send_at FROM lite_b2b.register_renewal a INNER JOIN b2b_invoice.`template_settings_general` b ON a.`template_guid` = b.`template_guid` INNER JOIN b2b_invoice.`template_settings_general` c ON a.`old_template_guid` = c.`template_guid` LEFT JOIN b2b_invoice.renewal_template d ON a.cross_guid = d.guid $condition1");

        $data = array(  
            'query_data' => $query_data->result(),
          );
      
        echo json_encode($data);
    }

    public function update_status()
    {
        $user_guid = $_SESSION['user_guid'];
        $renewal_guid = $this->input->post('renewal_guid');
        $status = $this->input->post('status');
        $modal = $this->input->post('modal');
        $date_time = $this->db->query("SELECT now() as now")->row('now');
        $user_name = $this->db->query("SELECT a.user_name FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');

        $get_renewal = $this->db->query("SELECT a.* FROM lite_b2b.register_renewal a WHERE a.`guid` = '$renewal_guid' ");

        if($get_renewal->num_rows() == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Invalid Data. Please contact admin.',
            );    
            echo json_encode($data);   
            exit();
        }

        $update_data = $this->db->query("UPDATE `lite_b2b`.`register_renewal` SET `is_confirm` = '$status', updated_at = '$date_time', updated_by = '$user_name' WHERE `guid` = '$renewal_guid' ");

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
            'para1' => 'true',
            'msg' => $modal.' Successful.',

            );    
            echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Update Data Error.',

            );    
            echo json_encode($data);   
        }
    }

    public function remove_renewal_data()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $renewal_guid = $this->input->post('renewal_guid');

        $get_renewal = $this->db->query("SELECT a.guid,a.cross_guid FROM lite_b2b.register_renewal a WHERE a.`guid` = '$renewal_guid' ");

        if($get_renewal->num_rows() == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Register renewal Data Not Found.',
            );    
            echo json_encode($data);   
            exit();
        }

        $cross_guid = $get_renewal->row('cross_guid');

        if($cross_guid != '')
        {
            $check_supplier_renewal = $this->db->query("SELECT a.guid,a.file_name,a.customer_guid,a.supplier_guid FROM b2b_invoice.renewal_template a WHERE a.`guid` = '$cross_guid' ");

            if($check_supplier_renewal->num_rows() == 0)
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Renewal Template Data Not Found.',
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {
                if($check_supplier_renewal->row('file_name') != '')
                {
                    $virtual_path = $this->file_config_b2b->file_path('', 'web', 'renewal', 'main_path', 'RNL');
                    //print_r($virtual_path); die;
                    $file_long_path = $check_supplier_renewal->row('customer_guid') . '/' . $check_supplier_renewal->row('supplier_guid') . '/' . $cross_guid . '/' ;
                    $file_path = $virtual_path . $file_long_path . $check_supplier_renewal->row('file_name');
                    //print_r($file_path); die;
                    $remove_file = unlink($file_path);
                }
    
                $delete_template_data = $this->db->query("DELETE FROM `b2b_invoice`.`renewal_template` WHERE `guid` = '$cross_guid' ");
            }
        }

        $delete_data = $this->db->query("DELETE FROM `lite_b2b`.`register_renewal` WHERE `guid` = '$renewal_guid' ");

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
            'para1' => 'true',
            'msg' => 'Delete Successful.',

            );    
            echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Data Error.',

            );    
            echo json_encode($data);   
        }
    }

    public function reject_pdf_data()
    {
        $user_guid = $_SESSION['user_guid'];
        $pdf_renewal_guid = $this->input->post('pdf_renewal_guid');

        $date_time = $this->db->query("SELECT now() as now")->row('now');
        $user_name = $this->db->query("SELECT a.user_name FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');

        $get_renewal = $this->db->query("SELECT a.* FROM lite_b2b.register_renewal a WHERE a.`guid` = '$pdf_renewal_guid' ");

        if($get_renewal->num_rows() == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Invalid Data. Please contact admin.',
            );    
            echo json_encode($data);   
            exit();
        }

        $update_data = $this->db->query("UPDATE `lite_b2b`.`register_renewal` SET `is_confirm` = '1', `rejected` = '1', updated_at = '$date_time', updated_by = '$user_name' WHERE `guid` = '$pdf_renewal_guid' ");

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
            'para1' => 'true',
            'msg' => $modal.' Successful.',

            );    
            echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Update Data Error.',

            );    
            echo json_encode($data);   
        }
    }

    public function okay_pdf_data()
    {
        $user_guid = $_SESSION['user_guid'];
        $pdf_renewal_guid = $this->input->post('pdf_renewal_guid');

        $date_time = $this->db->query("SELECT now() as now")->row('now');
        $user_name = $this->db->query("SELECT a.user_name FROM set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');

        $get_renewal = $this->db->query("SELECT a.* FROM lite_b2b.register_renewal a WHERE a.`guid` = '$pdf_renewal_guid' ");

        if($get_renewal->num_rows() == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Invalid Data. Please contact admin.',
            );    
            echo json_encode($data);   
            exit();
        }

        $update_data = $this->db->query("UPDATE `lite_b2b`.`register_renewal` SET `is_confirm` = '4', `rejected` = '0', updated_at = '$date_time', updated_by = '$user_name' WHERE `guid` = '$pdf_renewal_guid' ");

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
            'para1' => 'true',
            'msg' => $modal.' Successful.',

            );    
            echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Update Data Error.',

            );    
            echo json_encode($data);   
        }
    }

    public function sign_appendix_upload_process()
    {
      $username = $_SESSION['username'];
      $supplier_guid = $this->input->post("supplier_guid");
      $renewal_template_guid = $this->input->post("renewal_guid");
      $customer_guid = $this->input->post("customer_guid");
      $database = 'lite_b2b';
      $table = 'register_renewal';
      $config_module = 'renewal_sign';
      $config_type = 'main_path';
      $config_code = 'RNL_SIGN';
      $column_name = 'url_path';
      //print_r($renewal_template_guid); die;
      $to_shoot_url = "https://api.xbridge.my/rest_b2b/index.php/Upload_document";
  
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
  
      if(($customer_guid == '') || ($customer_guid == 'null') || ($customer_guid == null))
      {
          $data = array(  
              'para' => '1',
              'msg' => 'Invalid Customer GUID.',
          );
          echo json_encode($data);
          exit();
      }
  
      if(($renewal_template_guid == '') || ($renewal_template_guid == 'null') || ($renewal_template_guid == null))
      {
        $data = array(  
            'para' => '1',
            'msg' => 'Invalid Renewal Guid.',
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
  
      $check_file_exists = $this->db->query("SELECT 
      CAST(IF(`url_path` = '' OR `url_path` IS NULL,'0','1') AS CHAR(1)) AS file_exists, 
      IFNULL(`url_path`,'') AS 'file_name' 
      FROM $database.$table 
      WHERE `guid` = '$renewal_template_guid'
      ");
      //echo $this->db->last_query(); die;
      $file_long_path = $customer_guid . '/' . $supplier_guid . '/' . $renewal_template_guid . '/' ;
  
      //print_r($check_file_exists->row('file_name')); die;
  
      $data = array(
        'upload_doc'=> new CURLFILE($_FILES['file']['tmp_name'],$_FILES['file']['type'],$_FILES['file']['name']),
        'customer_guid' => $customer_guid,
        'supplier_guid' => $supplier_guid,
        'user_guid' => $username,
        'file_guid' => $renewal_template_guid,
        'file_long_path' => $file_long_path,
        'file_exists' => $check_file_exists->row('file_exists'),
        'r_file_name' => $check_file_exists->row('file_name'),
        'config_module' => $config_module,
        'config_type' => $config_type,
        'config_code' => $config_code,
        'column_name' => $column_name,
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
        //print_r($output->array_result); die;
        $data_array = $output->array_result;
        $data_array_v2 =  array(
            'is_confirm' => 3, // update for uploaded status 
        );
        $msg = $output->message;
        
        //update file name
        $this->db->where('guid', $renewal_template_guid);
        $this->db->update('lite_b2b.register_renewal', $data_array);

        //update is confirm
        $this->db->where('guid', $renewal_template_guid);
        $this->db->update('lite_b2b.register_renewal', $data_array_v2);
  
        $data = array(
          'para' => 'true',
          'msg' => $msg,
        );
  
        echo json_encode($data);
      }
      else
      {
        $msg = $output->message;
  
        $data = array(
          'para' => 'false',
          'msg' => $msg,
        );
  
        echo json_encode($data);
      }
    }
    
}
?>