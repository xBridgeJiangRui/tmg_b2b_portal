<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Send_email_model extends CI_Model
{
    public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module, $cc_list_string, $pdf, $reply_to, $filename)
    {
        // return 'temp';
        // die;
        if ($pdf != '' || $pdf != null) {   //echo substr($pdf,162);;die;
            //$b64Doc = chunk_split($pdf); 
            $b64Doc = base64_encode(file_get_contents($pdf));

            $filename = $filename;
        } else {
            $b64Doc = '';
        }
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup WHERE type = 'daily_po_notification' LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
      
        $variable = array('api_key' => '1234', 'secret_key' => '123456', 'module' => 'test');

        $replyto = array('Email' => $reply_to, 'Name' => $reply_to);
        $from = array('Email' => $from_email->row('sender_email'), 'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email, 'Name' => $to_email_name);
        $to_array = array($to);

        if ($cc_list_string != '' || $cc_list_string != null) {
            // $test_array = explode(',', $cc_list_string);
            // $cc_array = array();
            // foreach ($test_array as $tarray) {
            //     // echo $tarray->sender_email;
            //     $cc = array('Email' => $tarray, 'Name' => $tarray);
            //     array_push($cc_array, $cc);
            // }
            $cc_array = array();
            foreach ($cc_list_string as $tarray) {

                //print_r($tarray); die;
                $cc = array('Email' => $tarray, 'Name' => $tarray);
                array_push($cc_array, $cc);
            }
        } else {
            $cc_array = '';
        }

        // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
        $bcc_array = array();
        $variable1 = array($variable);
        $variables = array('var1' => $variable1);
        // $variables_array = array($variables);
        $templateid = 1090613;
        $Subject = $email_subject;
        $TextPart = $email_subject;
        $HTMLPart = $bodyContent;
        $attachment = array('ContentType' => 'application/pdf', 'Filename' => $filename, 'Base64Content' => $b64Doc);
        $attachment1 = array($attachment);
        $attachment_array = array($attachment);

        if ($b64Doc != '') {
            $data = array('from' => $from, 'to' => $to_array, 'subject' => $Subject, 'textpart' => $TextPart, 'htmlpart' => $HTMLPart, 'variables' => $variables, 'cc' => $cc_array, 'replyto' => $replyto, 'attachments' => $attachment_array);
        } else {
            $data = array('from' => $from, 'to' => $to_array, 'subject' => $Subject, 'textpart' => $TextPart, 'htmlpart' => $HTMLPart, 'variables' => $variables, 'cc' => $cc_array, 'replyto' => $replyto);
        }
        // $data2 = array($data);
        // $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data);
        // echo $myJSON;die;

        // $to_shoot_url = $this->local_ip . "/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
        $to_shoot_url = "20.212.51.33/tmg/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
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
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result1 = json_decode($result);

        $retry = 0;
        while (curl_errno($ch) == 28 && $retry < 3) {
            $response = curl_exec($ch);
            $retry++;
        }

        // if($keep_log == 1)
        // {
        //     if (!curl_errno($ch)) {
        //         if (isset($result1->Messages[0])) {
        //             $status = $result1->Messages[0]->Status;
        //         } else {
        //             $status = $result1->ErrorMessage;
        //         }
    
        //         if ($status == 'success') {
        //             $ereponse = $result1->Messages[0]->To[0]->MessageID;
        //             $data = array(
        //                 'created_at' => $this->db->query("SELECT now() as now")->row('now'),
        //                 'created_by' => 'URL_TASK',
        //                 'recipient' => $to_email,
        //                 'sender' => $from_email->row('sender_email'),
        //                 'subject' => $email_subject,
        //                 'status' => 'SUCCESS',
        //                 'respond_message' => $ereponse,
        //                 'smtp_server' => 'mailjet',
        //                 'smtp_port' => 'mailjet',
        //                 'smtp_security' => 'mailjet',
        //             );
        //             $this->db->insert('email_transaction', $data);
        //             // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
        //             //redirect('Email_controller/setup');
        //             if ($module != 'alert_notification') {
        //                 echo json_encode(array(
        //                     'status' => true,
        //                     'message' => 'success',
        //                     'action' => 'next',
        //                 ));
        //             };
        //         } else {
        //             $ereponse = $result1->StatusCode . '-' . $result1->ErrorMessage;
        //             $data = array(
        //                 'created_at' => $this->db->query("SELECT now() as now")->row('now'),
        //                 'created_by' => 'URL_TASK',
        //                 'recipient' => $to_email,
        //                 'sender' => $from_email->row('sender_email'),
        //                 'subject' => $email_subject,
        //                 'status' => 'FAIL',
        //                 'respond_message' => $ereponse,
        //                 'smtp_server' => 'mailjet',
        //                 'smtp_port' => 'mailjet',
        //                 'smtp_security' => 'mailjet',
        //             );
        //             $this->db->insert('email_transaction', $data);
        //             // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
        //             //redirect('Email_controller/setup');
        //             // if($module != 'alert_notification')
        //             // {
        //             echo json_encode(array(
        //                 'status' => true,
        //                 'message' => 'success',
        //                 'action' => 'next',
        //             ));
        //             // };
        //         }
    
        //         curl_close($ch);
        //     } else {
        //         $ereponse = 'Curl error: ' . curl_error($ch);
    
        //         $data = array(
        //             'created_at' => $this->db->query("SELECT now() as now")->row('now'),
        //             'created_by' => 'URL_TASK',
        //             'recipient' => $to_email,
        //             'sender' => $from_email->row('sender_email'),
        //             'subject' => $email_subject,
        //             'status' => 'FAIL',
        //             'respond_message' => $retry . $ereponse,
        //             'smtp_server' => 'mailjet',
        //             'smtp_port' => 'mailjet',
        //             'smtp_security' => 'mailjet',
        //         );
        //         $this->db->insert('email_transaction', $data);
        //         // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
        //         //redirect('Email_controller/setup');
        //         // if($module != 'alert_notification')
        //         // {
        //         echo json_encode(array(
        //             'status' => true,
        //             'message' => 'success',
        //             'action' => 'next',
        //         ));
        //     }
        // }

        return $httpcode;
    }

    public function send_mailjet_third_party_sunshine($email_add, $date, $bodyContent, $email_subject, $module, $cc_list_string, $pdf, $reply_to, $filename)
    {
        if ($pdf != '' || $pdf != null) {   //echo substr($pdf,162);;die;
            //$b64Doc = chunk_split($pdf); 
            $b64Doc = base64_encode(file_get_contents($pdf));

            $filename = $filename;
        } else {
            $b64Doc = '';
        }

      $attachment = array('ContentType' => 'application/pdf', 'Filename' => $filename, 'Base64Content' => $b64Doc);
      $attachment1 = array($attachment);
      $attachment_array = array($attachment);
  
      $to_shoot_url = "20.212.51.33/pandaapi3rdparty/index.php/send_mail/blast_email_api";
  
      //echo $to_shoot_url ;die;
  
      $data = array(
        "email_subject"  => $email_subject,
        "bodyContent"  => $bodyContent,
        "to_email" => $email_add,
        "cc_list_string"  => $cc_list_string,
        "pdf" => $pdf,
      );
      //print_r($data); die;
  
      $myJSON = json_encode($data);
      //echo $myJSON;die;
  
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
  
      $result = curl_exec($ch);
      $output = json_decode($result);
      //print_r($output); die;
  
      curl_close($ch);

      if(isset($output->status))
      {
        if($output->status == true)
        {
          return "Success";
        }
        else
        {
          return "Failed";
        }
      }
      else
      {
        return "Failed";
      }

    }
}
