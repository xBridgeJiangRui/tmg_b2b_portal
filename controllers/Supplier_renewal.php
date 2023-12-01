<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_renewal extends CI_Controller {

    public function renewal_form_confirmation()
    {      
      $renewal_guid = $_REQUEST['link'];
      $config_module = 'renewal';
      $config_type = 'sec_path';
      $config_code = 'SECRNL';

      $get_renewal = $this->db->query("SELECT a.*, b.`template_description`, c.`subsequent_fee`, b.`template_group`, e.guid AS renewal_guid, e.new_start_date, e.new_end_date, IF(e.file_name = '', '0','1') AS file_exists, e.file_name  FROM lite_b2b.register_renewal a INNER JOIN b2b_invoice.`template_settings_general` b ON a.`template_guid` = b.`template_guid` INNER JOIN b2b_invoice.`template_settings_child_general` c ON b.`template_guid` = c.`template_guid` LEFT JOIN b2b_invoice.renewal_template e ON a.cross_guid = e.guid WHERE a.`guid` = '$renewal_guid' LIMIT 1");
  
      if($get_renewal->num_rows() == 0)
      {
        echo "<script> alert('Invalid URL.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/' </script>";
        exit();
      }
  
      $form_status = $get_renewal->row('is_confirm');
  
      if($form_status == '1')
      {
        echo "<script> alert('Your Form was Confirmed.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/' </script>";
        exit();
      }
  
      else if($form_status == '2')
      {
        echo "<script> alert('Your Form was Declined.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/' </script>";
        exit();
      }

      else if($form_status == '3')
      {
        echo "<script> alert('Your Sign And Chop Sheet has Submitted.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/' </script>";
        exit();
      }


      if($get_renewal->row('file_name') != '')
      {
        //$virtual_path = $this->file_config_b2b->file_path('web', $config_module, $config_type, $config_code);
        $virtual_path = $this->db->query("SELECT `value`
        FROM " . $this->tb_lite_b2b . ".config
        WHERE device = 'web'
        AND module = '$config_module'
        AND type = '$config_type'
        AND code = '$config_code'")->row('value');
  
        $file_long_path = $get_renewal->row('customer_guid') . '/' . $get_renewal->row('supplier_guid') . '/' . $get_renewal->row('renewal_guid') . '/' ;
  
        $file_path = $virtual_path . $file_long_path . $get_renewal->row('file_name');
      }
      else
      {
        $file_path = '';
      }

      $data = array(
        'get_renewal' => $get_renewal,
        'supplier_name' => $get_renewal->row('supplier_name'),
        'link' => $renewal_guid,
        'file_path' => $file_path,
      ); 
  
      $this->load->view('header_s'); 
      $this->load->view('register/supplier_renewal_form', $data);  
      $this->load->view('footer_s' );  
    }

    public function update_status()
    {
        $renewal_guid = $this->input->post('renewal_guid');
        $status = $this->input->post('status');
        $modal = $this->input->post('modal');
        $date_time = $this->db->query("SELECT now() as now")->row('now');

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

        $update_data = $this->db->query("UPDATE `lite_b2b`.`register_renewal` SET `is_confirm` = '$status', updated_at = '$date_time', updated_by = 'Supplier' WHERE `guid` = '$renewal_guid' ");

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
}
?>