<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class task extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $data = array(

            	'query' => $this->db->query("SELECT `code` As 'Code',description AS 'Description',updated_at AS 'Updated',updated_by AS 'Updated By' FROM b2b_backend_process"),


            );
            $this->load->view('header');
            $this->load->view('task/task', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
        
    } 

    public function add_task()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $code = $this->input->post("code");
            $description = $this->input->post("description");

			$data = array(
			        'code' => $code,
			        'description' => $description,
			        'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                	'created_by' => $_SESSION["userid"],
			        'updated_at' => $this->db->query("SELECT now() as now")->row('now'),
			        'updated_by' =>	$_SESSION["userid"],
			);

			$this->db->insert('b2b_backend_process', $data);

			if ($this->db->affected_rows() > 0 ) {
				echo "<script> alert('Successfully insert task code ".$task_code." ');</script>";
			} else {
				echo "<script> alert('Fail to insert');</script>";
			}

			
            echo "<script> document.location='" . base_url() . "index.php/task' </script>";
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

            		SELECT IF(customer_guid = 'ALL', '<b>ALL</b>', b.acc_name) AS 'Customer',
					c.description AS 'Task Description',
					c.code AS 'Task Code',
					a.run_time ,
					a.run_time_type,
					a.seq,
					a.isactive,
					a.next_run_time,
					a.updated_at,
					a.updated_by FROM b2b_backend_process_setting a 

					LEFT JOIN acc b 
					ON a.customer_guid = b.acc_guid
					INNER JOIN b2b_backend_process c 
					ON a.task_code = c.code


            		"),

            	'customer' => $this->db->query("

            		SELECT acc_guid, acc_name FROM acc


            		"),

            	'b2b_backend_process' => $this->db->query("

            		SELECT `code`,description FROM b2b_backend_process


            		"),

            	'interval' => array(

            		'0' => 'DAY', 
	            	'1' => 'HOUR', 
	            	'2' => 'MINUTE', 
	            	'3' => 'MONTH', 
	            	'4' => 'YEAR', 
	            	'5' => 'WEEK',


            	),

            	


            );
            $this->load->view('header');
            $this->load->view('task/relation', $data);
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
            $customer_all = $this->input->post("customer_all");
        	
			$task_code = $this->input->post("task_code");
			$run_time = $this->input->post("run_time");
			$run_time_type = $this->input->post("run_time_type");
			$seq = $this->input->post("seq");
			$isactive = $this->input->post("isactive");
			$next_run_date = $this->input->post("next_run_date");
			$next_run_time = $this->input->post("next_run_time");

			$next_run_time = $next_run_date.' '.$next_run_time;

			if ($isactive == true) {
				$isactive = '1';
			} else {
				$isactive = '0';
			}

            if ($customer_all == true) {
                $customer_guid = array(
                    '0' => 'ALL'
                );

            } else {
                $customer_guid = $this->input->post("customer_guid[]");
            }

            foreach ($customer_guid as $key ) {
                $data = array(
                    'customer_guid' => $key,
                    'task_code' => $task_code,
                    'run_time' => $run_time,
                    'run_time_type' => $run_time_type,
                    'seq' => $seq,
                    'isactive' => $isactive,
                    'next_run_time' => $next_run_time,
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' => $_SESSION["userid"],
                    'updated_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'updated_by' => $_SESSION["userid"],
                );

                $this->db->insert('b2b_backend_process_setting', $data);
            }

			

			if ($this->db->affected_rows() > 0 ) {
				echo "<script> alert('Successfully insert task code ".$task_code." ');</script>";
			} else {
				echo "<script> alert('Fail to insert');</script>";
			}

			
            echo "<script> document.location='" . base_url() . "index.php/task/relation' </script>";
        }
        else
        {
            redirect('login_c');
        }
        
    } 
}
?>

