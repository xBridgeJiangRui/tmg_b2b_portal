<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use Twilio\Rest\Client;
use Interfax\Client;
class Fax extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        require_once './application/libraries/interFAX-PHP-master/vendor/autoload.php';
        // require_once('./application/libraries/tcpdf/tcpdf.php');
        // require_once './pdfparser-master/src/autoload.php';
        // require_once './tcpdf/tcpdf.php';


	}

    public function send_fax()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            // require_once './twilio-php-master/Twilio/autoload.php';
        


            // Find your Account Sid and Auth Token at twilio.com/console
            // DANGER! This is insecure. See http://twil.io/secure
            // $sid    = "AC0bf99ce74d7763a1ca19e4747cb7cf5f";
            // $token  = "e6195085dbf3721ec63d10037ab362db";
            // $twilio = new Client($sid, $token);

            // $fax = $twilio->fax->v1->faxes
            //                        ->create("+063323566", // to
            //                                 "https://www.twilio.com/docs/documents/25/justthefaxmaam.pdf", // mediaUrl
            //                                 array("from" => "+12013545744")
            //                        );

            // print($fax->sid);

            // $message = $twilio->messages
            //       ->create("+601110753989", // to
            //                array("from" => "+12013545744", "body" => "Who are you")
            //       );

            // print($message->sid);

                ini_set('memory_limit', '-1');
                ini_set('max_execution_time', 0); 
                ini_set('memory_limit','2048M');

                $faxusername = $this->db->query("SELECT reason FROM lite_b2b.set_setting WHERE code = 'set_fax_user' AND module_name = 'fax'")->row('reason');
                $faxpassword = $this->db->query("SELECT reason FROM lite_b2b.set_setting WHERE code = 'set_fax_password' AND module_name = 'fax'")->row('reason');

                $interfax = new Client(['username' => $faxusername, 'password' => $faxpassword]);
                $fax = $interfax->deliver(['faxNumber' => '+6063323566', 'file' => 'faxsend/fax2.pdf']);

                // get the latest status:
                $fax->refresh()->status; // Pending if < 0. Error if > 0

                // Simple polling
                while ($fax->refresh()->status < 0) {
                    sleep(5);
                }
        }
        else
        {
            redirect('login_c');
        }


    }

    function faxgetBalance()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $faxusername = $this->db->query("SELECT reason FROM lite_b2b.set_setting WHERE code = 'set_fax_user' AND module_name = 'fax'")->row('reason');
            $faxpassword = $this->db->query("SELECT reason FROM lite_b2b.set_setting WHERE code = 'set_fax_password' AND module_name = 'fax'")->row('reason');

            $interfax = new Client(['username' => $faxusername, 'password' => $faxpassword]);
            $outbound = $interfax->outbound;
            $faxes = $interfax->outbound->recent();
     
            date_default_timezone_set("Asia/Kuala_Lumpur");

            $data = array(
                'faxlist' => $faxes,
            ); 

            $this->load->view('header');
            $this->load->view('fax_balance',$data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }

        
    }

    function insert_fax_record()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $set_time = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_insert' AND module_name = 'fax'")->row('reason');

            $sendTime=date("Y-m-d $set_time");

            if(date('Y-m-d H-i') >= $sendTime)   
            {
                $faxusername = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_user' AND module_name = 'fax'")->row('reason');
                $faxpassword = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_password' AND module_name = 'fax'")->row('reason');

                $interfax = new Client(['username' => $faxusername, 'password' => $faxpassword]);
                $outbound = $interfax->outbound;
                $faxes = $interfax->outbound->recent();
                // echo var_dump($faxes); 
                date_default_timezone_set("Asia/Kuala_Lumpur");
                foreach($faxes as $row)
                {
                    $faxdate = date('Y-m-d',strtotime($row->submitTime));
                    if($faxdate != date('Y-m-d'))
                    {
                        $userTime=$row->submitTime; 
                        $userTimezone="Asia/Kuala_Lumpur";
                        $userConvertedTime = $this->readtime($userTime,$userTimezone,'UTC');

                        $euserTime=$row->completionTime;
                        $userendConvertedTime = $this->readtime($euserTime,$userTimezone,'UTC');

                        $this->db->query("REPLACE INTO fax_record (id, username, destination_no, page_sent, start_time, end_time, status) VALUES ('$row->id','$row->userId','$row->destinationFax','$row->pagesSent','$userConvertedTime','$userendConvertedTime','$row->status')");
                    }
                    // break;
                }

                if($this->db->affected_rows() > 0)
                {
                    $this->session->set_flashdata('message', 'Record Saved Local');
                    redirect('fax/setup');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Record Save Local Unsuccessfully');
                    redirect('fax/setup');
                }
                // echo var_dump($outbound);
            }
            else
            {
                $this->session->set_flashdata('message', 'Time Not Reach');
                redirect('fax/setup');
            }
        }
        else
        {
            redirect('login_c');
        }
    }    

    public function readtime($time,$toTz,$fromTz)
    {   
        // timezone by php friendly values
        $date = new DateTime($time, new DateTimeZone($fromTz));
        $date->setTimezone(new DateTimeZone($toTz));
        $time= $date->format('Y-m-d H:i:s');
        return $time;
    }


    // function readpdf()
    // {
    //         $f = "./faxsend/fax3.pdf";
    //         $stream = fopen($f, "r");
    //         $content = fread ($stream, filesize($f));

    //         if(!$stream || !$content)
    //         return 0;

    //         $count = 0;
    //         // Regular Expressions found by Googling (all linked to SO answers):
    //         $regex  = "/\/Count\s+(\d+)/";
    //         $regex2 = "/\/Page\W*(\d+)/";
    //         $regex3 = "/\/N\s+(\d+)/";

    //         if(preg_match_all($regex, $content, $matches))
    //         $count = max($matches);

    //         echo var_dump($count);
        
    // }

    // public function count_pages() {

    //       $pdftext = file_get_contents('./faxsend/fax2.pdf');
    //       $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
    //       echo $num;

    // }    

    public function setup()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $fax_setup_user = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'fax' AND code = 'set_fax_user'")->row('reason');
            $fax_setup_password = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'fax' AND code = 'set_fax_password'")->row('reason');

            // $faxusername = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_user' AND module_name = 'fax'")->row('reason');
            // $faxpassword = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_password' AND module_name = 'fax'")->row('reason');
            // echo $faxusername.$faxpassword;
            $interfax = new Client(['username' => $fax_setup_user, 'password' => $fax_setup_password]);
            $outbound = $interfax->outbound;
            $faxes = $interfax->outbound->recent();
            // echo var_dump($faxes); 
            // echo 1;die;
            date_default_timezone_set("Asia/Kuala_Lumpur");

            $data = array(
                'fax_user' => $fax_setup_user,
                'fax_password' => $fax_setup_password,
                'faxlist' => $faxes,
                'fax_balance' => $interfax->getBalance(), 
                );
            $this->load->view('header');
            $this->load->view('fax_setup', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
    }

    public function update()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $save = $this->input->post('save');
            $test = $this->input->post('test');
            $fax_user = $this->input->post('fax_user');
            $fax_password = $this->input->post('password');
            // echo var_dump($this->input->post());die;
            if($save != '')
            {
                $data = array(
                    'reason' => $fax_user
                );

                $this->db->where('module_name', 'fax');
                $this->db->where('code', 'set_fax_user');
                $this->db->update('set_setting', $data);

                $data2 = array(
                'reason' => $fax_password
                );

                $this->db->where('module_name', 'fax');
                $this->db->where('code', 'set_fax_password');
                $this->db->update('set_setting', $data2);  

                if($this->db->affected_rows() > 0)
                {
                    $this->session->set_flashdata('message', 'Record Updated');
                    redirect('fax/setup');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Record Updated Unsuccessfully');
                    redirect('fax/setup');
                }
            }

            if($test != '')
            {
                ini_set('memory_limit', '-1');
                ini_set('max_execution_time', 0); 
                ini_set('memory_limit','2048M');

                $faxusername = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_user' AND module_name = 'fax'")->row('reason');
                $faxpassword = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_password' AND module_name = 'fax'")->row('reason');
                $faxreceiver = $this->db->query("SELECT reason FROM set_setting WHERE code = 'set_fax_receiver' AND module_name = 'fax'")->row('reason');

                $interfax = new Client(['username' => $faxusername, 'password' => $faxpassword]);
                if(!file_exists('faxsend'))
                {
                    mkdir('faxsend',0777);
                }
                $fax = $interfax->deliver(['faxNumber' => $faxreceiver, 'file' => 'faxsend/test.pdf']);

                // get the latest status:
                $fax->refresh()->status; // Pending if < 0. Error if > 0

                // Simple polling
                while ($fax->refresh()->status < 0) {
                    $this->session->set_flashdata('message', 'Processing');
                    redirect('fax/setup');
                }
            }
        }
        else
        {
            redirect('login_c');
        }
    }

}
?>

