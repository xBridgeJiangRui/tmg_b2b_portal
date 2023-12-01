<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Library
 *
 * To display standard template for all pages
 * 
 * @link http://maestric.com/doc/php/codeigniter_template
 *
 */

class Panda {
		var $template_data = array();
		private $ci;

		public function __construct()
    	{
    	  $this->ci =& get_instance();
    	}

		function set($name, $value)
		{
			$this->template_data[$name] = $value;
		}
	
		function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
		{               
			$this->CI =& get_instance();
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
			return $this->CI->load->view($template, $this->template_data, $return);
		}

		public function set_database($db_name)
    	{
      		$db_data = $this->ci->load->database($db_name, TRUE);
      		$this->ci->db = $db_data;
    	}
 
		public function validate_login()
		{
			$user_guid = $_SESSION['user_guid'];
			$validate_login = $this->ci->db->query("SELECT user_logs_guid from lite_b2b.user_logs where user_guid = '$user_guid';")->row('user_logs_guid');
			//echo var_dump($validate_login);die;
			return $validate_login;
		}

		public function get_uri()
		{
			$request = parse_url($_SERVER['REQUEST_URI']);
			//echo $_SERVER['QUERY_STRING']; die;
			$path = $request["path"].$_SERVER['QUERY_STRING'];
			//echo $path; die;
			//$result = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $path), '/');
			$trans_guid =  $this->ci->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid');
			$user_guid = $_SESSION['user_guid'];
			if(isset($_SESSION['customer_guid']))
			{
				$customer_guid = $_SESSION['customer_guid'];	
			}
			else
			{
				$customer_guid = '';
			};

			$ses_guid = $this->ci->db->query("SELECT user_logs_guid from lite_b2b.user_logs where user_guid = '$user_guid';")->row('user_logs_guid');
			$trans_date = $this->ci->db->query("SELECT CURDATE() as curdate")->row('curdate');
			$created_at = $this->ci->db->query("SELECT now() as now")->row('now');
			
			$this->ci->db->query("REPLACE INTO lite_b2b.transaction_logs SELECT '$trans_guid', '$customer_guid', '$ses_guid','$user_guid', '$path', '$trans_date', '$created_at'");

		}

		public function get_serverdb()
		{
			$servername = "b2b-sunshine-mysql01.mysql.database.azure.com";
			$username = "panda_web";
			$password = "web@adnap";
			$dbname = "lite_b2b";

			return array(
				'servername' => $servername,
				'username' => $username,
				'password' => $password,
				'dbname' => $dbname,
			);
		}

		public function set_global_variable()
		{
			if(isset($_SESSION['customer_guid']))
			{
				$this->ci->db->query("SET @customer_guid = (SELECT '".$_SESSION['customer_guid']."' )");
			}

			if(isset($_SESSION['query_loc']))
			{
				//$this->ci->db->query("SET @query_loc = (SELECT ".$_SESSION['query_loc'].")");
				//$this->ci->db->query("SET @query_loc = (SELECT distinct branch_code from  set_user as a inner join  acc_branch as b on a.branch_guid = b.branch_guid where user_id = '".$_SESSION['userid']."' and a.isactive = '1' and module_group_guid = '".$_SESSION['module_group_guid']."' order by branch_code asc)");
				$this->ci->db->query("create temporary table if not exists query_loc_".$_SESSION['user_guid']." SELECT distinct branch_code from  set_user as a inner join  acc_branch as b on a.branch_guid = b.branch_guid where user_id = '".$_SESSION['userid']."' and a.isactive = '1' and module_group_guid = '".$_SESSION['module_group_guid']."' order by branch_code asc ");
			}

			if(isset($_SESSION['query_supcode']))
			{
				$this->ci->db->query("create temporary table if not exists query_supcode_".$_SESSION['user_guid']." SELECT distinct backend_supplier_code from set_supplier_user_relationship as a
            inner join set_supplier_group as b 
            on a.supplier_group_guid = b.supplier_group_guid where a.user_guid = '".$_SESSION['user_guid']."'");
			}

			/*if(isset($_SESSION['query_supcode']))
			{
				$this->ci->db->query("SET @query_supcode = '".$_SESSION['query_supcode']."'");
			}*/
		}
 
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */

