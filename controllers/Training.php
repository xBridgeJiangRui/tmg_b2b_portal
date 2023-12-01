<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Training extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');

    }
    

    //bring unique qr code and scan for login////////////////////////////////////////////////////////////////////////////////

        public function index()
    {
        $this->load->view('training/webcam_qr');
        
    }
        public function scan_qr_code_attendance()
    {
         
        $ta_guid = $_REQUEST['g'];

        /*echo $password;die;

        $checking_user = $this->db->query("SELECT md5('$password') as toa ")->row('toa');
        echo $checking_user;die;*/

        $checking_user = $this->db->query("SELECT * FROM lite_b2b.training_attendance WHERE ta_guid = '$ta_guid' ");

        if ($checking_user->num_rows() > '0') {

            $checking_attended = $this->db->query("SELECT status FROM lite_b2b.training_attendance WHERE ta_guid = '$ta_guid' ")->row('status');

            if ($checking_attended == '0') 

            {

                $now =  $this->db->query("SELECT NOW() as now")->row('now');

                $data = array(

                'attended_at' => $now,
                'status' => '1'

                );

                $this->db->where('ta_guid', $ta_guid);
                $this->db->update('lite_b2b.training_attendance', $data);

                $message = 'Hi '.$checking_user->row('name').', welcome and thanks for joining us today.';

                $data = array(

                'message' => $message,
                'status' => 'Sign'

                );

                echo json_encode($data);
            
            }

            else

            {
                $message = 'Hi '.$checking_user->row('name').' , it seems like you already signed your attendance.';

                $data = array(

                'message' => $message,
                'status' => 'Signed'

                );

                echo json_encode($data);


            }

           
            
        } else {

            $message = 'Sorry, QR Code Wrong.('.$ta_guid.')';

            $data = array(

                'message' => $message,
                'status' => 'Error'

            );

            echo json_encode($data);

        }

        

   
    }

    //staff manually sign attendance////////////////////////////////////////////////////////////////////////////////

    public function staff()
    {

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_group_name'] == 'SUPER_ADMIN' )
        { 

            $selected_session = $this->input->post('session');
            if($selected_session == '' || $selected_session == null)
            {
                $checking_attended = $this->db->query("SELECT * FROM lite_b2b.training_attendance ");

                $session = $this->db->query("SELECT session FROM lite_b2b.training_attendance GROUP BY session");
            }
            else
            {
                if($selected_session == 'all')
                {
                    $checking_attended = $this->db->query("SELECT * FROM lite_b2b.training_attendance");
                }
                else
                {
                    $checking_attended = $this->db->query("SELECT * FROM lite_b2b.training_attendance WHERE session = '$selected_session'");    
                }

                $session = $this->db->query("SELECT session FROM lite_b2b.training_attendance GROUP BY session");
            }


        $data = array(

            'checking_attended' => $checking_attended,
            'selected_session' => $selected_session,
            'session' => $session

        );

        $this->load->view('header');
        $this->load->view('training/staff', $data);
        $this->load->view('footer');

        }
        else
        {
            redirect('#');
        }
        
    }    



    public function change_status_attendance()
    {
         
        $ta_guid = $this->input->post('ta_guid');
        $status = $this->input->post('status');

        /*echo $password;die;

        $checking_user = $this->db->query("SELECT md5('$password') as toa ")->row('toa');
        echo $checking_user;die;*/
        

        $checking_user = $this->db->query("SELECT * FROM lite_b2b.training_attendance WHERE ta_guid = '$ta_guid' ");

        $ta_guid = $checking_user->row('ta_guid');

        $name = $checking_user->row('name');

        $now =  $this->db->query("SELECT NOW() as now")->row('now');

        if ($status == 1) {

            $attended_at =  $this->db->query("SELECT NOW() as now")->row('now');

        } else {

            $attended_at =  '';

        }

        

        $data = array(

        'attended_at' => $attended_at,
        'status' => $status,
        'updated_by' => $_SESSION['userid'],
        'updated_at' => $now,

        );

        $this->db->where('ta_guid', $ta_guid);
        $this->db->update('lite_b2b.training_attendance', $data);

        echo "<script> alert('".$name." status change successfuly.');</script>";
        echo "<script> document.location='" . base_url() . "index.php/Training/Staff' </script>";
   
    }

    //Scan Qr Code self login by username and password//////////////////////////////////////////////////////////////////////////

    public function sign_in()
    {
            $this->load->view('training/sign_in');
        
    }

    public function sign_in_attend()
    {
         
        $i_c = $this->input->post('i_c');
        /*$name = $this->input->post('name');*/

        $checking_user = $this->db->query("SELECT status,name FROM lite_b2b.training_attendance WHERE i_c = '$i_c' ");

        if ($checking_user->num_rows() > '0') {

            if ($checking_user->row('status') == '0') 

            {

                $now =  $this->db->query("SELECT NOW() as now")->row('now');

                $data = array(

                'attended_at' => $now,
                'status' => '1'

                );

                $this->db->where('i_c', $i_c);
                $this->db->update('lite_b2b.training_attendance', $data);


                echo "<script> alert('Hi ".$checking_user->row('name').", welcome and thanks for joining us today.');</script>";
                echo "<script> document.location='" . base_url() . "index.php/Training/sign_in' </script>";
            
            }

            else

            {

                echo "<script> alert('Hi ".$checking_user->row('name')." , it seems like you already signed your attendance.');</script>";
                echo "<script> document.location='" . base_url() . "index.php/Training/sign_in' </script>";


            }

           
            
        } else {

            echo "<script> alert('IC number not found, please try again or contact staff.');</script>";
            echo "<script> document.location='" . base_url() . "index.php/Training/sign_in' </script>";

        }

        

   
    }


}
?>