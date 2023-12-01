<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scheduler_controller extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Export_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer'); 
    }

    public function troubleshoot()
    {
        $module = $_REQUEST['module'];

        if($module == 'email_agent')
        {
            $check_error_query = $this->db->query("SELECT status FROM email_transaction WHERE status = 'Fail' AND LEFT(created_at , 13) =  DATE_FORMAT(NOW(), '%Y-%m-%d %H') HAVING COUNT(status > 10 )");

            $check_next_runtime = $this->db->query("SELECT next_run_datetime from set_scheduler where type = 'swap_email_agent'")->row('next_run_datetime');
            $current_time = $this->db->query("SELECT now() as naw")->row('naw');

            if($check_error_query->num_rows() > 0)
            {
                if($current_time > $check_next_runtime)
                {
                        $check_curent_email = $this->db->query("SELECT username from email_setup limit 1")->row('username');

                         if($check_curent_email != 'rexbridge.b2b@gmail.com')
                        {
                            
                            $this->db->query("UPDATE email_setup set username = 'rexbridge.b2b@gmail.com' , password = '80998211' where username = '$check_curent_email' "); 
                           $this->db->query("UPDATE set_scheduler set next_run_datetime = DATE_ADD(NOW(), INTERVAL 2 HOUR)  where type = 'swap_email_agent'");                    
                        }
                        else
                        {

                            $this->db->query("UPDATE email_setup set username = 'admin@xbridge.my' , password = 'x123bridge' where username = '$check_curent_email' "); 
                           $this->db->query("UPDATE set_scheduler set next_run_datetime = DATE_ADD(NOW(), INTERVAL 2 HOUR)  where type = 'swap_email_agent'");                        
                        }


                        echo json_encode(array(
                        'status' => true,
                        'message' => 'successful',
                        //'last_query' => $this->db->last_query(),
                        ));
                }
                else
                {
                    echo json_encode(array(
                        'status' => true,
                        'message' => 'scheduler does not meet',
                        ));
                }
            }
            else
            {
                 echo json_encode(array(
                        'status' => true,
                        'message' => 'All Transaction in the past 2 hours are successful',
                        ));
            }
        }
        else
        {
             echo json_encode(array(
                        'status' => false,
                        'message' => 'Module not found',
                        ));
        }
    }

 

    /*public function delete_file()
    {
         
    };*/

   
 

}
?>
