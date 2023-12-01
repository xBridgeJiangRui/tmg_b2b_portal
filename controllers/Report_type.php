<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class report_type extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $data = array(

            	'query' => $this->db->query("

            		SELECT 	
            		`type` AS 'Type', 
					`code` AS 'Code', 
					`description` AS 'Description', 
					`updated_at` AS 'Updated', 
					`updated_by` AS 'Updated By'
					 
					FROM `report_type` 

            		"),

            	'report_type' => array(

            		'0' => 'ALL', 
	            	'1' => 'BY_RETAILER'


            	),


            );
            $this->load->view('header');
            $this->load->view('report_type/report_type', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
        
    } 

    public function add()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {	
        	$type = $this->input->post("type");
            $code = $this->input->post("code");
            $description = $this->input->post("description");

			$data = array(
				'type' => $type, 
				'code' => $code,
			    'description' => $description,
				'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => $_SESSION["userid"],
			    'updated_at' => $this->db->query("SELECT now() as now")->row('now'),
			    'updated_by' =>	$_SESSION["userid"],
			        
			        
			);

			$this->db->insert('report_type', $data);

			if ($this->db->affected_rows() > 0 ) {
				echo "<script> alert('Successfully insert code ".$code." ');</script>";
			} else {
				echo "<script> alert('Fail to insert');</script>";
			}

			
            echo "<script> document.location='" . base_url() . "index.php/report_type' </script>";
        }
        else
        {
            redirect('login_c');
        }
        
    } 

    public function action()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {	
        	$type = $this->input->post("submit");
        	$tids = $this->input->post("tids[]");

        	if ($type == 'delete') {
        		$this->db->where_in('code', $tids);
				$this->db->delete('report_type');
        	}

			if ($this->db->affected_rows() > 0 ) {
				echo "<script> alert('Successfully insert code ".$code." ');</script>";
			} else {
				echo "<script> alert('Fail to insert');</script>";
			}

			
            echo "<script> document.location='" . base_url() . "index.php/report_type' </script>";
        }
        else
        {
            redirect('login_c');
        }
        
    } 

    public function relation()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $data = array(

            	'query' => $this->db->query("

            		SELECT IF(a.customer_guid = 'ALL', '<b>ALL</b>', b.acc_name) AS 'Customer',
            		d.user_name AS 'User name',
            		c.code AS 'Code',
					c.description AS 'Description',
					IF(a.isactive = '1','<i class=\"fa fa-check\" aria-hidden=\"true\"></i>' , '<i class=\"fa fa-cross\" aria-hidden=\"true\"></i>' ) AS isactive,
					a.updated_at,
					a.updated_by FROM report_type_set_relations a 

					LEFT JOIN acc b 
					ON a.customer_guid = b.acc_guid
					INNER JOIN report_type c 
					ON a.report_code = c.code
					INNER JOIN set_user d 
					ON a.user_guid = d.user_guid

					GROUP BY a.guid
            		"),

            	'customer' => $this->db->query("

            		SELECT acc_guid, acc_name FROM acc


            		"),

            	'set_user' => $this->db->query("

            		SELECT user_name,user_id,user_guid FROM set_user GROUP BY user_guid

            		"),

            	'report_type' => $this->db->query("

            		SELECT `code`,description FROM report_type


            		"),

            	


            );
            $this->load->view('header');
            $this->load->view('report_type/relation', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
        
    } 

    public function add_relation()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
        	$customer_guid = $this->input->post("customer_guid[]");
        	$user_guid = $this->input->post("user_guid[]");
			$report_code = $this->input->post("report_code");
			$isactive = $this->input->post("isactive");

			$next_run_time = $next_run_date.' '.$next_run_time;

			if ($isactive == true) {
				$isactive = '1';
			} else {
				$isactive = '0';
			}

            foreach ($customer_guid as $key ) {

            	foreach ($user_guid as $key1 ) {

	            	$guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

	                $data = array(
	                    'guid' => $guid, 
						'customer_guid' => $key, 
						'user_guid' => $key1, 
						'report_code' => $report_code, 
						'isactive' => $isactive, 
						'created_at' => $this->db->query("SELECT now() as now")->row('now'),
	                    'created_by' => $_SESSION["userid"],
	                    'updated_at' => $this->db->query("SELECT now() as now")->row('now'),
	                    'updated_by' => $_SESSION["userid"],
	                );

	                $this->db->insert('report_type_set_relations', $data);

            	}
            }

			

			if ($this->db->affected_rows() > 0 ) {
				echo "<script> alert('Successfully insert task code ".$task_code." ');</script>";
			} else {
				echo "<script> alert('Fail to insert');</script>";
			}

			
            echo "<script> document.location='" . base_url() . "index.php/report_type/relation' </script>";
        }
        else
        {
            redirect('login_c');
        }
        
    } 
}
?>

