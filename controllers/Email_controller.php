<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_controller extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Panda_PHPMailer');
        $this->local_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','local_ip','LIP');
        $this->load->model('Send_email_model');
    }


    public function index()
    {
       // $this->load->view('header');
        $this->load->view('forgot_email_form');
        //$this->load->view('footer');
    }

    public function submit_email()
    {
        $input_email = $this->input->post("email");

        $email_validation = $this->db->query("SELECT * from set_user where user_id = '$input_email' group by user_guid");

        if($email_validation->num_rows() == 0)
        {
            $this->session->set_flashdata('warning', 'Email does not exist. Please contact admin for further assistance');
            redirect('Email_controller');
        };

        // $email = $this->db->query("SELECT * from email_setup");

        // $setsession = array(
        //         'smtp_server' =>$email->row('smtp_server'),
        //         'email_username' =>$email->row('username'),
        //         'email_password' => $email->row('password'),
        //         'smtp_security' =>$email->row('smtp_security'),
        //         'smtp_port' =>$email->row('smtp_port'),
        //         'sender_email' =>$email->row('sender_email'),
        //         'sender_name' =>$email->row('sender_name'),
        //         'subject' => 'Forgot Password',
        //         'url' => $email->row('url'),
        //         );
        // $this->session->set_userdata($setsession);

        // loop all group

        $email_group = $this->db->query("SELECT user_id, user_name, user_guid from set_user group by  user_guid");
        $email_name = $email_validation->row('user_name');
        $email_add = $input_email;
        $date = $this->db->query("SELECT now() as now")->row('now');
        $user_guid = $email_validation->row('user_guid');
        $uuid= $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid');
        $rep_id= $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid');

        $url = site_url('Email_controller/changeviaparam')."?rep_id=".$rep_id."&id=".$user_guid."&token=".$uuid;

        $this->db->query("REPLACE INTO reset_pwd select '$rep_id' as rep_id, '$user_guid' as user_guid, '$uuid' as token_id, now() as created_at ");

        $get_header = $this->db->query("SELECT a.value AS body_header FROM lite_b2b.config a WHERE a.type = 'header_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EHTMP' LIMIT 1")->row('body_header');
        
        $get_footer = $this->db->query("SELECT a.value AS body_footer FROM lite_b2b.config a WHERE a.type = 'footer_val' AND a.module = 'email_temp' AND a.device = 'web' AND a.code = 'EFTMP' LIMIT 1")->row('body_footer');

        $get_mail_template = $this->db->query("SELECT mail_subject,body_content FROM lite_b2b.email_template WHERE is_active = '1' AND `type` = 'RESET_PWD' AND mail_type = 'Forgot_pwd'");

        $url_redirect = "<a href='$url' style='color:black;background-color: #008CBA;border-radius: 2px;text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;'>RESET LINK HERE</a>";

        $html_view = $get_header . $get_mail_template->row('body_content') . $get_footer;
         
        $replace_key = ['%user_id%','%reset_link%'];
        $replace_value = [$email_add,$url_redirect];
        $bodyContent = str_replace($replace_key , $replace_value , $html_view);
        
        $module = 'reset_pwd';
        $subject = $get_mail_template->row('mail_subject');

        $email_result = $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '' ,'','support@xbridge.my','');

        //$email_result = $this->send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module,'support@xbridge.my');
        
        $this->session->set_flashdata('message', 'Email has been sent.');
        redirect('login_c');

    }

    public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module,$reply_to)
    {
        // $b64Doc = chunk_split(base64_encode(file_get_contents('uploads/qr_code/4/cc.pdf'))); 
        $b64Doc = ''; 
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM mailjet_setup WHERE type = 'reset_password' LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
        //$to_email = 'danielweng57@gmail.com';
        //$to_email_name = 'danielweng57@gmail.com';
        $cc = 'desmondm520@gmail.com';
        $cc_name = 'desmondm520@gmail.com';
        $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');

        $replyto = array('Email' => $reply_to,'Name' => $reply_to);
        $from = array('Email' => $from_email->row('sender_email'),'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email,'Name' => $to_email_name);
        $to_array = array($to);
        $cc = array('Email' => $cc_name,'Name' => $cc_name);
        $cc_array = array($cc);
        // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
        $bcc_array = array();
        $variable1 = array($variable);
        $variables = array('var1' => $variable1);
        // $variables_array = array($variables);
        $templateid = 1090613;
        $Subject = $email_subject;
        $TextPart = $email_subject;
        $HTMLPart = $bodyContent; 
        $attachment = array('ContentType' => 'application/pdf','Filename' => 'sample.pdf','Base64Content' => $b64Doc);
        $attachment1 = array($attachment);
        $attachment_array = array($attachment);
        $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables,'replyto' =>$replyto);
        $data2 = array($data);
        // $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data);
        // echo $myJSON;die;

        $to_shoot_url = $this->local_ip."/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
        // echo $to_shoot_url;die;
        $ch = curl_init(); 

        curl_setopt_array($ch, array(
          CURLOPT_URL => $to_shoot_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $myJSON,
          CURLOPT_HTTPHEADER => array(
            "x-api-key: 123456",
            "Content-Type: application/json"
          ),
        ));

        // $to_shoot_url = $this->local_ip.'/pandaapi3rdparty/index.php/email_agent/mj_sendemail';
        // $ch = curl_init($to_shoot_url); 
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "123456" ));
        // curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        // // curl_setopt($ch, CURLOPT_USERPWD, $mailjet_user.":".$mailjet_pass);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $myJSON);
        $result = curl_exec($ch);
        $result1 = json_decode($result);
        // print_r($result);die;
        $retry = 0;
        while(curl_errno($ch) == 28 && $retry < 3){
            $response = curl_exec($ch);
            $retry++;
        }

        if(!curl_errno($ch))
        {
            if(isset($result1->Messages[0]))
            {
                $status = $result1->Messages[0]->Status;
            }
            else
            {
                $status = $result1->ErrorMessage;
            }


            if($status == 'success')
            {
                $ereponse = $result1->Messages[0]->To[0]->MessageID;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>'URL_TASK',
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'SUCCESS',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                if($module != 'alert_notification')
                {
                    echo json_encode(array(
                            'status' => true,
                            'message' => 'success',
                            'action'=> 'next',
                            ));
                };
            }
            else
            {
                $ereponse = $result1->StatusCode.'-'.$result1->ErrorMessage;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>'URL_TASK',
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                // if($module != 'alert_notification')
                // {
                 echo json_encode(array(
                    'status' => false,
                    'message' => $ereponse,
                    'action'=> 'retry',
                    ));
                // };
            }

            curl_close($ch);
        }
        else
        {
                $ereponse = 'Curl error: '.curl_error($ch);

                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>'URL_TASK',
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $retry.$ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                // if($module != 'alert_notification')
                // {
                 echo json_encode(array(
                    'status' => false,
                    'message' => $ereponse,
                    'action'=> 'retry',
                    ));          
        }        
    }    

    public function changeviaparam()
    {
        $id = $_REQUEST['id'];
        $check_id = $this->db->query("SELECT * from set_user where user_guid ='".$id."'");
        $check_time = $this->db->query("SELECT TIMESTAMPDIFF(HOUR,'created_at', NOW()) as check_token from reset_pwd where user_guid = '".$id."'")->row('check_time');
        $check_token = $this->db->query("SELECT token_id from reset_pwd where user_guid = '".$id."'")->row('token_id');


        if($check_id->num_rows() == 0)
        {
            $this->session->set_flashdata('message', 'Invalid User ID');
            redirect('login_c');
        };

        if($check_token != $_REQUEST['token'])
        {
            $this->session->set_flashdata('message', 'URL invalid : Wrong Token');
            redirect('login_c');
        };

        if($check_time > 6)
        {
            $this->session->set_flashdata('message', 'URL invalid : Token Expired');
            redirect('login_c');
        };

        //$this->load->view('header');
        $this->load->view('forgot_password_form');
        //$this->load->view('footer');
    }

    public function reset_password()
    {
        $first_password = $this->input->post('first_password');
        $second_password = $this->input->post('second_password');
        $rep_id = $this->input->post('rep_id');
        $user_guid = $this->input->post('id');
        $uuid = $this->input->post('token');

        if($first_password != $second_password)
        {
            $this->session->set_flashdata('warning', 'Password Mismatch');
            redirect("Email_controller/changeviaparam"."?rep_id=".$rep_id."&id=".$user_guid."&token=".$uuid);
        };

        $password = md5($this->input->post('first_password'));
        $this->db->query("UPDATE set_user set user_password = '$password',updated_at = NOW(),updated_by = 'reset_pwd' where user_guid = '$user_guid'");

        $this->session->set_flashdata('message', 'Password Updated. Please Relogin');
        redirect('login_c');

    }

    public function subscription()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $customer_guid = $_SESSION['customer_guid'];

            $data = array(
                'email_subscription' => $this->db->query("SELECT module_name
, a.customer_guid
, user_guid
, user_group
, user_id
, IFNULL(b.customer_guid, 'No') AS subscribe
, IFNULL(email, 'No') AS email 
, IFNULL(email_group, 'No') AS email_group
FROM
(
SELECT module_name, customer_guid, user_group, user_id , user_guid
FROM location_user_modulegroup  AS a
WHERE module_name = 'Panda B2B'
GROUP  BY user_id
) a
LEFT JOIN 
(
SELECT customer_guid, email, email_group 
FROM email_list 
) b
ON  a.user_id = b.email
-- where user_group = 'CUSTOMER_ADMIN'
ORDER BY user_id ASC"),
                'email_schedule' => $this->db->query("SELECT * from check_email_schedule"),
                'email_user' => $this->db->query("SELECT * from email_list where customer_guid = '$customer_guid' order by email asc"),
                'report_type' => $this->db->query("SELECT * from set_report_query where active = '1' order by report_type , report_name asc"),
            );
            $this->load->view('header');
            $this->load->view('email_subscription',$data);
            $this->load->view('email_subscription_modal',$data);
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function subscription_detail()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $user_guid = $_REQUEST['user_guid'];
            $check_user_detail = $this->db->query("SELECT * from location_user_modulegroup where user_guid = '$user_guid' group by user_guid ");
            if(strstr($check_user_detail->row('user_id'), '@') != '')
            {
                $email = $check_user_detail->row('user_id');
            }
            else
            {
                $this->session->set_flashdata('warning', 'Warning. User ID not in email format');
                redirect('Email_controller/subscription');
            };

            $check_existing_data = $this->db->query("SELECT email from email_list where email = '$email'");

            if($check_existing_data->num_rows() >= 1)
            {
                $this->session->set_flashdata('warning', 'Warning. Email already in subscription list');
                redirect('Email_controller/subscription');
            };

            $data = array(
                'trans_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                'customer_guid' => $check_user_detail->row('customer_guid'),
                'user_guid' => $user_guid,
                'first_name' => addslashes($check_user_detail->row('user_name')),
                'last_name' => '',
                'email' => $email,
                'email_group' => $check_user_detail->row('user_group'),
                'isactive' => '1',
                );
            $this->db->insert('email_list', $data);
            $this->session->set_flashdata('message', 'Successful');
            redirect('Email_controller/subscription');
        }
        else
        {
            redirect('#');
        }
    }

        public function batch_subscribe()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $user_guid_all = $this->input->post('user_guid[]');

            /*print_r($user_guid_all);die;*/

            foreach ($user_guid_all as $user_guid) {

                $check_user_detail = $this->db->query("SELECT * from location_user_modulegroup where user_guid = '$user_guid' group by user_guid ");
                if(strstr($check_user_detail->row('user_id'), '@') != '')
                {
                    $email = $check_user_detail->row('user_id');

                    $check_existing_data = $this->db->query("SELECT email from email_list where email = '$email'");

                    if($check_existing_data->num_rows() >= 1)
                    {
                     

                        echo "<script> alert('Warning. Email ".$check_user_detail->row('user_id')." already in subscription list');</script>";
                       
                    }


                    else{


                        $data = array(
                            'trans_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                            'customer_guid' => $check_user_detail->row('customer_guid'),
                            'user_guid' => $user_guid,
                            'first_name' => addslashes($check_user_detail->row('user_name')),
                            'last_name' => '',
                            'email' => $email,
                            'email_group' => $check_user_detail->row('user_group'),
                            'isactive' => '1',
                            );
                        $this->db->insert('email_list', $data);

                         /*echo $this->db->last_query();die;*/
                        $this->session->set_flashdata('message', 'Successful');
                        


                    }

                }
                else
                {
                    

                    echo "<script> alert('Warning. User ID ".$check_user_detail->row('user_id')." not in email format');</script>";
                    
                };

            }

            redirect('Email_controller/subscription');
            

            
        }
        else
        {
            redirect('#');
        }
    }

    public function delete_schedule_guid()
    {
        $schedule_guid = $_REQUEST['schedule_guid'];
        $this->db->query("DELETE FROM set_report_schedule where schedule_guid =  '$schedule_guid'");
        $this->session->set_flashdata('message', 'Successful');
        redirect('Email_controller/subscription');

    }

    public function subscription_schedule()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $customer_guid = $this->input->post('customer_guid');
            $email_user_all = $this->input->post('email_user[]');

            // print_r($email_user_all);die;
            $mode = $this->input->post('mode');
            $report_guid = $this->input->post('report_guid');
            $selected_day_name = $this->input->post('day_name');
            $selected_day_name_ever = $this->input->post('day_name_ever');

            if($this->input->post('mode') == 'create')
            {

                foreach ($email_user_all as $email_user) {

                    $schedule_type = $this->db->query("SELECT if(report_type<>'each_trans','weekly','each_trans') as schedule_type from set_report_query where report_guid = '$report_guid' ")->row('schedule_type');
                    if($schedule_type != 'each_trans')
                    {
                        $schedule_type =  $this->input->post('schedule_type');
                        $day_name =  $this->input->post('day_name');
                        $date_start = $this->db->query("SELECT $day_name as date_start from calendar")->row('date_start');
                    }
                    else
                    {
                        $day_name = $this->input->post('day_name_ever');
                        $date_start = $this->db->query("SELECT curdate() as today")->row('today');
                    };

                    $checking_duplicate = $this->db->query("SELECT * from set_report_schedule where customer_guid = '$customer_guid' and email_list_trans_guid = '$email_user' and report_guid = '$report_guid'");
                
                    if($checking_duplicate->num_rows() >= 1)
                    {
                        echo "<script> alert('Warning. Email id ".$check_user_detail->row('user_id')." already in subscription list');</script>";
                    };

                    $data = array(
                    'schedule_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    'customer_guid' => $customer_guid,
                    'email_list_trans_guid' => $email_user,
                    'schedule_type' =>  $schedule_type,
                    'report_guid' => $report_guid,
                    'date_start' => $date_start,
                    'day_name' =>  $day_name,
                    'active' => '1',
                    'created_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'created_by' => $_SESSION['userid'],
                    'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'updated_by' => $_SESSION['userid'],
                    );
                    $this->db->insert('set_report_schedule', $data);
                }
                
            };

            if($this->input->post('mode') == 'update')
            {
                 $schedule_guid = $this->input->post('schedule_guid');
                 $schedule_type =  $this->input->post('schedule_type');
                 $checking_duplicate = $this->db->query("SELECT * from set_report_schedule where schedule_guid = '$schedule_guid' and day_name = '$day_name' and schedule_type = '$schedule_type' ");
            
                if($checking_duplicate->num_rows() >= 1)
                {
                    $this->session->set_flashdata('warning', 'Data already exist unable to edit');
                    redirect('Email_controller/subscription');
                };

                if($schedule_type != 'each_trans')
                {
                    $day_name =  $this->input->post('day_name');
                    $date_start = $this->db->query("SELECT $day_name as date_start from calendar")->row('date_start');
                }
                else
                {
                    $day_name = $this->input->post('day_name_ever');
                    $date_start = $this->db->query("SELECT curdate() as today")->row('today');
                };

                $data = array(
                    'schedule_type' =>  $schedule_type,
                    'report_guid' => $report_guid,
                    'date_start' =>  $date_start,
                    'day_name' =>  $day_name,
                    'active' => '1',
                    'updated_at' => $this->db->query("SELECT now() as today")->row('today'),
                    'updated_by' => $_SESSION['userid'],
                );
                $this->db->where('schedule_guid', $this->input->post('schedule_guid'));
                $this->db->update('set_report_schedule', $data);
            };

            $this->session->set_flashdata('message', 'Successful');
            redirect('Email_controller/subscription');
        }
        else
        {
            redirect('#');
        }
    }

    public function password()
    {
        $key = 'jWK/jUI1W6o1yXZJHgbK+Bu2DgiFygw07tlPQns0S6I=';
        $string = "1234abc"; // note the spaces
        $iv = mcrypt_create_iv(
            mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
            MCRYPT_DEV_URANDOM
        );

        $encrypted = base64_encode(
            $iv .
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128,
                hash('sha256', $key, true),
                $string,
                MCRYPT_MODE_CBC,
                $iv
            )
        );

        $data = base64_decode($encrypted);
        $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

        $decrypted = rtrim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128,
                hash('sha256', $key, true),
                substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
                MCRYPT_MODE_CBC,
                $iv
            ),
            "\0"
        );

        echo $encrypted;
        echo '<br>' .$decrypted;
                   
    }

    public function setup()
    {
        $email_setup = $this->db->query("SELECT * FROM email_setup LIMIT 1");
        $data = array(
            'guid' => $email_setup->row('guid'),
            'smtp_server' => $email_setup->row('smtp_server'),
            'smtp_port' => $email_setup->row('smtp_port'),
            'smtp_security' => $email_setup->row('smtp_security'),
            'username' => $email_setup->row('username'),
            'password' => $email_setup->row('password'),
            'sender_name' => $email_setup->row('sender_name'),
            'sender_email' => $email_setup->row('sender_email'),
            'recipient_name' => $email_setup->row('recipient_name'),
            'recipient_email' => $email_setup->row('recipient_email'),
            'subject' => $email_setup->row('subject'),
            'active' => $email_setup->row('active'),
            );
        $this->load->view('header');
        $this->load->view('email_setup', $data);
        $this->load->view('footer');
    }

    public function update()
    {   
        // if email setup active , sms setup should inactive.
        if($this->input->post('active') == '1')
        {
            $data = array(
                'active' => '0',
                );
           // $this->db->update('sms_setup', $data);
        };


        $data = array(
            'smtp_server' => $this->input->post('smtp_server'),
            'smtp_port' => $this->input->post('smtp_port'),
            'smtp_security' => $this->input->post('smtp_security'),
            'username' => addslashes($this->input->post('username')),
            'password' => $this->input->post('password'),
            'sender_name' => addslashes($this->input->post('sender_name')),
            'sender_email' => $this->input->post('sender_email'),
            'recipient_name' => addslashes($this->input->post('recipient_name')),
            'recipient_email' => $this->input->post('recipient_email'),
            'subject' => $this->input->post('subject'),
            'active' => $this->input->post('active'),
            );
        $this->session->set_userdata($data);
        $this->db->where('guid', $this->input->post('guid'));
        $this->db->update('email_setup', $data);
        // echo $this->db->last_query();die;
        if($this->input->post('save'))
        {
            $this->session->set_flashdata('message', 'Record Updated');
            redirect('Email_controller/setup');
        };
        
        if($this->input->post('test'))
        {
            $this->send_email();
        };
        
    }

    public function report()
    {
        if($this->session->userdata('loginuser')== true)
        { 

            $data = array(
                'transaction' => $this->db->query('SELECT * from email_transaction order by created_at desc'),
                'export_url' => site_url('Email_controller/export'),
                );
            $this->load->view('header');
            $this->load->view('email_report', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('login_c');
        }
    }

    public function export()
    {
        if($this->session->userdata('loginuser')== true)
        {

            $query = $this->db->query("SELECT status,recipient,sender,subject,smtp_server,smtp_port,smtp_security,respond_message,created_at,created_by FROM email_transaction;");

            //$query = $this->db->query('select trans_guid,merchant_guid,ref_no from web_merchant.transaction  ');
            
            $data = $query->result_array();

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('email report');
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'Status');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Recipient');
            $this->excel->getActiveSheet()->setCellValue('C1', 'Sender');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Subject');
            $this->excel->getActiveSheet()->setCellValue('E1', 'SMTP Server');
            $this->excel->getActiveSheet()->setCellValue('F1', 'SMTP Port');
            $this->excel->getActiveSheet()->setCellValue('G1', 'SMTP Security');
            $this->excel->getActiveSheet()->setCellValue('H1', 'Respond Message');
            $this->excel->getActiveSheet()->setCellValue('I1', 'Created At');
            $this->excel->getActiveSheet()->setCellValue('J1', 'Created By');
            
            $this->excel->getActiveSheet()->getStyle("A:A")->getNumberFormat()->setFormatCode('0');
            
            $this->excel->getActiveSheet()->fromArray($data, ' ', 'A2');

            $filename='email_report.XLSX'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            ob_end_clean();
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
        }
        else
        {
            redirect('login_c');
        }
        
    }

    public function send_email()
    {
        $mail = new PHPMailer;

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $_SESSION['smtp_server']; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_SESSION['username']; // SMTP username
        $mail->Password = $_SESSION['password']; // SMTP password
        $mail->SMTPSecure = $_SESSION['smtp_security'];// Enable TLS encryption, `ssl` also accepted
        $mail->Port = $_SESSION['smtp_port']; // TCP port to connect to

        $mail->setFrom($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addReplyTo($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addAddress($_SESSION['recipient_email'], $_SESSION['recipient_name']); // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML
        $path= base_url('assets/img/new.png');
        $bodyContent = '<div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                        B2B Mailer
                                    </h3>
                                    <p class="lead">
                                        It works
                                        </p>
                                </div>
                            </div>
                        </div>';
        
        $mail->Subject = $_SESSION['subject'];
        $mail->Body    = $bodyContent;

        if(!$mail->send()) 
        {
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            $data = array(

                'created_at' => $_SESSION['datetime'],
                'created_by' => $_SESSION["userID"],
                'recipient' => $_SESSION['recipient_email'],
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'FAIL(TESTING)',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
            $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            redirect('Email_controller/setup');
        } 
        else 
        {
            $data = array(

                'created_at' => $_SESSION['datetime'],
                'created_by' => $_SESSION["userID"],
                'recipient' => $_SESSION['recipient_email'],
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'SUCCESS(TESTING)',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
            $this->session->set_flashdata('message', 'Message has been sent');
            redirect('Email_controller/setup');
            // echo 'Message has been sent';
        }
    }


    public function send_to_email($email_add, $email_name, $date, $bodyContent)
    {
        $mail = new PHPMailer;

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $_SESSION['smtp_server']; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_SESSION['email_username']; // SMTP username
        $mail->Password = $_SESSION['email_password']; // SMTP password
        $mail->SMTPSecure = $_SESSION['smtp_security'];// Enable TLS encryption, `ssl` also accepted
        $mail->Port = $_SESSION['smtp_port']; // TCP port to connect to

        $mail->setFrom($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addReplyTo($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addAddress($email_add, $email_name); // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML
        $path= base_url('assets/img/new.png');
        
        
        $mail->Subject = $_SESSION['subject'];
        $mail->Body    = $bodyContent;

        if(!$mail->send()) 
        {
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => $_SESSION["userid"],
                'recipient' => $email_add,
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'FAIL',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
           // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            //redirect('Email_controller/setup');
        } 
        else 
        {
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => $_SESSION["userid"],
                'recipient' => $email_add,
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'SUCCESS',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
            // $this->session->set_flashdata('message', 'Message has been sent');
            //  redirect('Email_controller/setup');
            // echo 'Message has been sent';
        }
    }

    // $mail = new PHPMailer;

        // $mail->isSMTP();                            // Set mailer to use SMTP
        // $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
        // $mail->SMTPAuth = true;                     // Enable SMTP authentication
        // $mail->Username = '';          // SMTP username
        // $mail->Password = ''; // SMTP password
        // $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
        // $mail->Port = 587;                          // TCP port to connect to

        // $mail->setFrom('info@example.com', 'panda');
        // $mail->addReplyTo('info@example.com', 'panda');
        // $mail->addAddress('bushido.speed@gmail.com');   // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // $mail->isHTML(true);  // Set email format to HTML
        // $path= base_url('assets/img/new.png');
        // $bodyContent = '<div class="container-fluid">
        //                     <div class="row">
        //                         <div class="col-md-12">
        //                             <h3 class="text-info">
        //                                 h3. Lorem ipsum dolor sit amet.
        //                             </h3>
        //                             <p class="lead">
        //                                 Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>. Aliquam eget sapien sapien. Curabitur in metus urna. In hac habitasse platea dictumst. Phasellus eu sem sapien, sed vestibulum velit. Nam purus nibh, lacinia non faucibus et, pharetra in dolor. Sed iaculis posuere diam ut cursus. <em>Morbi commodo sodales nisi id sodales. Proin consectetur, nisi id commodo imperdiet, metus nunc consequat lectus, id bibendum diam velit et dui.</em> Proin massa magna, vulputate nec bibendum nec, posuere nec lacus. <small>Aliquam mi erat, aliquam vel luctus eu, pharetra quis elit. Nulla euismod ultrices massa, et feugiat ipsum consequat eu.</small>
        //                             </p>
        //                         </div>
        //                     </div>
        //                 </div>';
        
        // $mail->Subject = 'testing';
        // $mail->Body    = $bodyContent;

        // if(!$mail->send()) {
        //     echo 'Message could not be sent.';
        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
        //     echo phpinfo();
        // } else {
        //     echo 'Message has been sent';
        // }

}
?>

