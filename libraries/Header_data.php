<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
// require_once APPPATH."/third_party/PHPExcel.php";
 
class Header_data {
    public function __construct()
    {
      $this->ci =& get_instance();
      // $this->load->library(array('database', 'session', 'panda', 'form_validation','message'));
    }

    public function menu_child($module,$code)
    {
        $customer_guid = $this->ci->session->userdata('customer_guid');
        $database = 'lite_b2b';
        $table = 'menu_setting';

        $result = $this->ci->db->query("SELECT * FROM $database.$table WHERE customer_guid = '$customer_guid' AND module='$module' AND code='$code' AND is_active = 1");

        return $result;
    }
}