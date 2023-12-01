<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Key_in extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Export_model');
        $this->load->library('user_agent');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->library('form_validation');

    }

    public function key_in()
    {
        if(isset($_REQUEST['si']))
        {
            $session_id = $_REQUEST['si'];
            if($session_id == '' || $session_id == null)
            {
                echo 'Invalid Form. Please Contact Admin (UG2)';die;
            }            
            // echo $session_id;
        }
        else
        {
            echo 'Invalid Form. Please Contact Admin (ISI1)';die;
        }

        if(isset($_REQUEST['ug']))
        {
            $user_guid = $_REQUEST['ug'];
            if($user_guid == '' || $user_guid == null)
            {
                echo 'Invalid Form. Please Contact Admin (UG2)';die;
            }
            // echo $user_guid;
        }
        else
        {
            echo 'Invalid Form. Please Contact Admin (IUG2)';die;
        }

        $database = 'lite_b2b';
        $table = 'reset_pass_list';
        $result = $this->db->query("SELECT * FROM $database.$table WHERE reset_guid = '$session_id' AND user_guid = '$user_guid' AND is_reset = 0");

        $this->db->query("UPDATE $database.$table SET viewed_at = NOW() WHERE reset_guid = '$session_id' AND user_guid = '$user_guid'");            
        
        if($result->num_rows() > 0)
        {
            $interval_time = 2880;
            $created_reset_link_time = $result->row('created_at');
            $check_time = $this->db->query("SELECT DATE_ADD('$created_reset_link_time', INTERVAL $interval_time minute) as after_time,NOW() as now");
            $check_time_after = $check_time->row('after_time');
            $now = $check_time->row('now');
            // echo $now.'__'.$check_time_after;
            if($now >= $check_time_after)
            {
                echo 'Link Expired';die;
            }
            

            $database2 = 'lite_b2b';
            $table2 = 'set_user';
            $user_guid = $result->row('user_guid');
            $customer_guid = $result->row('customer_guid');

            $user_guid = $this->db->query("SELECT * FROM $database2.$table2 WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid'");
            $email_address = $user_guid->row('user_id');
            $active = $user_guid->row('isactive');
            // print_r($user_guid->result());die;
            if($active == 0)
            {
                echo 'Invalid Form, Please contact admin. (USA4)';die;
            }
            $data = array(
                'reset_guid' => $session_id,
                'user_guid'=> $user_guid->row('user_guid'),
                'email_address' => $email_address,
            );
            $this->load->view('key_in/key_in',$data);
            // echo $email_address;die;

        }
        else
        {
            echo 'Reset link not exist, please contact support. (RL3)';die;
        }



    }  

    public function key_in_pass()
    {
        // print_r($this->input->post());die;
        $reset_guid = $this->input->post('r_g');
        $user_guid = $this->input->post('u_g');
        $first_password = $this->input->post('f_p');
        $second_password = $this->input->post('s_p');

        if($first_password == '' || $first_password == null)
        {
            $this->session->set_flashdata('warning', 'First Password Cannot Be Empty');
            redirect('Key_in/key_in?si='.$reset_guid.'&ug='.$user_guid);
        }

        if($second_password == '' || $second_password == null)
        {
            $this->session->set_flashdata('warning', 'Second Password Cannot Be Empty');
            redirect('Key_in/key_in?si='.$reset_guid.'&ug='.$user_guid);
        }        

        if($first_password != $second_password)
        {
            $this->session->set_flashdata('warning', 'Password Not Match');
            redirect('Key_in/key_in?si='.$reset_guid.'&ug='.$user_guid);
        }
        
        $database = 'lite_b2b';
        $table3 = 'reset_pass_list';
        $reset_array = $this->db->query("SELECT * FROM $database.$table3 WHERE reset_guid = '$reset_guid'");
        if($reset_array->num_rows() <= 0)
        {
            $this->session->set_flashdata('warning', 'Error Occur Please Contact Support.(RGNE5)');
            redirect('Key_in/key_in?si='.$reset_guid.'&ug='.$user_guid);
        }

        $ip = $this->input->ip_address();
        $browser = $this->agent->browser();
        // echo $ip.$browser;die;
        $updated_by = 'key_in';
        $u_password = md5($first_password);
        $u_user_guid = $reset_array->row('user_guid');
        if($u_user_guid == '' || $u_user_guid == null)
        {
            echo 'Error Occur. Please Contact Admin. (UGNF6)';die;
        }

        $table2 = 'reset_pass_list';
        $this->db->query("UPDATE $database.$table2 SET is_reset = 1,reset_at = NOW(),ip = '$ip',browser = '$browser' WHERE reset_guid = '$reset_guid'");


        $table = 'set_user';
        $this->db->query("UPDATE $database.$table SET user_password = '$u_password',updated_at = NOW(),updated_by = '$updated_by' WHERE user_guid = '$u_user_guid'");

        $this->session->set_flashdata('message', 'Please Login Using password you key in');
        redirect('login_c');        

    }       
            
}
?>
