<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->local_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','local_ip','LIP');
	}//close contruct

	public function index()
	{	

		if(!isset($_REQUEST['id']))
		{
			echo 'ERROR occur.Please Contact Admin';die;
		}
		else
		{
			$session_id = addslashes($_REQUEST['id']);
			// echo $session_id;die;
	        if($session_id == '' || $session_id == null)
	        {
	        	echo "Session ID cannot be empty. Please Contact Admin";die;
	        }			
	        $type = 'attachment';
	        $file_array = $this->db->query("SELECT * FROM lite_b2b.download_file_details WHERE session_id = '$session_id' AND isactive = 1 ORDER BY created_at DESC LIMIT 1");
	        if($file_array->num_rows() <= 0)
	        {
	        	echo "Session ID Not exists. Please Contact Admin";die;
	        }	        
	        $date_now = $this->db->query("SELECT NOW() as now")->row('now');
	        $expired_date = $file_array->row('expired_date');
	        if($date_now >= $expired_date)
	        {
	        	echo "Link has expired. Please Contact Admin";die;
	        }
	        // echo $this->db->last_query();die;
	        $file = $file_array->row('file_path'); 
	        // echo $file;die;
	        if (!file_exists($file))
	        {
			    echo "The file not exists. Please Contact Admin";die;
			}
			// echo 'File Downloaded';	die;
	        $pdf_name1 = $file_array->row('file_name');
	        header("Content-type: application/zip");
	        header('Content-Disposition: '.$type.'; filename="'.$pdf_name1.'.zip"'); 
			// header("Content-Disposition: attachment; filename=\"".$Filename."\"");
		    // header("Content-Length: ".filesize($Filename));
	   		readfile($file);
	        die;   	
	    }
	}//close index

	public function pdf()
	{	

		if(!isset($_REQUEST['id']))
		{
			echo 'ERROR occur.Please Contact Admin';die;
		}
		else
		{
			$session_id = addslashes($_REQUEST['id']);
			// echo $session_id;die;
	        if($session_id == '' || $session_id == null)
	        {
	        	echo "Session ID cannot be empty. Please Contact Admin";die;
	        }			
	        $type = 'attachment';
	        $file_array = $this->db->query("SELECT * FROM lite_b2b.download_file_details WHERE session_id = '$session_id' AND isactive = 1 ORDER BY created_at DESC LIMIT 1");
	        if($file_array->num_rows() <= 0)
	        {
	        	echo "Session ID Not exists. Please Contact Admin";die;
	        }	        
	        $date_now = $this->db->query("SELECT NOW() as now")->row('now');
	        $expired_date = $file_array->row('expired_date');
	        if($date_now >= $expired_date)
	        {
	        	echo "Link has expired. Please Contact Admin";die;
	        }
	        // echo $this->db->last_query();die;
	        $file = $file_array->row('file_path'); 
	        // echo $file;die;
	        if (!file_exists($file))
	        {
			    echo "The file not exists. Please Contact Admin";die;
			}
			// echo 'File Downloaded';	die;
	        $pdf_name1 = $file_array->row('file_name');
	        header("Content-type: application/pdf");
	        header('Content-Disposition: '.$type.'; filename="'.$pdf_name1.'.pdf"'); 
			// header("Content-Disposition: attachment; filename=\"".$Filename."\"");
		    // header("Content-Length: ".filesize($Filename));
	   		readfile($file);
	        die;   	
	    }
	}//close index
	
    public function send_email ()
    {

	        ini_set('max_execution_time', 0); 
	        ini_set('memory_limit','2048M');
            $session_id = '1234';
            $email_data = array(
                            'email_info' => $value,
                            );
            $Subject = 'xBridge B2B Document Download Link';
            $Body    = '<a href="https://b2b2.xbridge.my/index.php/Download/show_link?id='.$session_id.'" target="_blank">Download</a>';
            echo $Body;die;

            // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
            $pdfBase64 = ''; 
            // echo $b64Doc;die;      
            $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup1 LIMIT 1");
            // $to_email = $value->emails;
            // $to_email_name = $value->emails;
            $to_email = 'danielweng57@gmail.com';
            $to_email_name = 'danielweng57@gmail.com';
            // $to_email = 'ssloo@xbridge.my';
            // $to_email_name = 'ssloo@xbridge.my';
            // $cc = 'desmondm520@gmail.com';
            // $cc_name = 'desmondm520@gmail.com';
            $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');
            $variable1 = array($variable);
            $variables = array('var1' => $variable1);

            $from = array('Email' => $from_email->row('sender_email'),'Name' => $from_email->row('sender_name'));
            $to = array('Email' => $to_email,'Name' => $to_email_name);
            $to_array = array($to);
            // $cc = array('Email' => $cc_name,'Name' => $cc_name);
            // $cc_array = array($cc);
            // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
            $bcc_array = array();
            $variable1 = array($variable);
            $variables = array('var1' => $variable1);
            // $variables_array = array($variables);
            $templateid = 1090613;
            $Subject = $Subject;
            $TextPart = $Subject;
            $HTMLPart = $Body; 

            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables);
            $data2 = array($data);
            // $data3 = array('Messages' => $data2);
            // $t = array($t, "Mary", "Peter", "Sally");

            $myJSON = json_encode($data);
            echo $myJSON;die;

            $to_shoot_url = $this->local_ip."/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
            echo $to_shoot_url;die;
            $ch = curl_init(); 

            curl_setopt_array($ch, array(
              CURLOPT_URL => $to_shoot_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 3000,
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
            $result1 = json_decode($result);
            print_r($result);die;
            // print_r($result1->Messages[0]->Errors[0]->StatusCode);die;
            $retry = 0;
            while(curl_errno($ch) == 28 && $retry < 3){
                $response = curl_exec($ch);
                $retry++;
            }
     
    }  	  

    public function show_link ()
    {

		if(!isset($_REQUEST['id']))
		{
			echo 'ERROR occur.Please Contact Admin';die;
		}
		else
		{
			$session_id = addslashes($_REQUEST['id']);
			// echo $session_id;die;
	        if($session_id == '' || $session_id == null)
	        {
	        	echo "Session ID cannot be empty. Please Contact Admin";die;
	        }			
	        $type = 'attachment';
	        $file_array = $this->db->query("SELECT * FROM lite_b2b.download_file_details WHERE session_id = '$session_id' AND isactive = 1 ORDER BY created_at DESC LIMIT 1");
	        if($file_array->num_rows() <= 0)
	        {
	        	echo "Session ID Not exists. Please Contact Admin";die;
	        }	        
	        $date_now = $this->db->query("SELECT NOW() as now")->row('now');
	        $expired_date = $file_array->row('expired_date');
	        if($date_now >= $expired_date)
	        {
	        	echo "Link has expired. Please Contact Admin";die;
	        }
	        // echo $this->db->last_query();die;
	        $file = $file_array->row('file_path'); 
	        // echo $file;die;
	        if (!file_exists($file))
	        {
			    echo "The file not exists. Please Contact Admin";die;
			}

    		echo 'Please Click to <a href="https://b2b2.xbridge.my/index.php/Download?id=1234" download>Download</a>';
	        die;   	
    	}
    } 

    public function display()
    {

		if(!isset($_REQUEST['id']))
		{
			echo 'ERROR occur.Please Contact Admin';die;
		}
		else
		{
			$session_id = addslashes($_REQUEST['id']);
			// echo $session_id;die;
	        if($session_id == '' || $session_id == null)
	        {
	        	echo "Session ID cannot be empty. Please Contact Admin";die;
	        }			
	        $type = 'attachment';
	        $file_array = $this->db->query("SELECT * FROM lite_b2b.download_file_details WHERE session_id = '$session_id' AND isactive = 1 ORDER BY created_at DESC LIMIT 1");
	        if($file_array->num_rows() <= 0)
	        {
	        	echo "Session ID Not exists. Please Contact Admin";die;
	        }	        
	        $date_now = $this->db->query("SELECT NOW() as now")->row('now');
	        $expired_date = $file_array->row('expired_date');
	        if($date_now >= $expired_date)
	        {
	        	echo "Link has expired. Please Contact Admin";die;
	        }
	        // echo $this->db->last_query();die;
	        $file = $file_array->row('file_path'); 
	        // echo $file;die;
	        if (!file_exists($file))
	        {
			    echo "The file not exists. Please Contact Admin";die;
			}
			$type = 'inline';
	        $pdf_name1 = $file_array->row('file_name');
	        header("Content-type: application/pdf");
	        header('Content-Disposition: '.$type.'; filename="'.$pdf_name1.'.pdf"'); 
			// header("Content-Disposition: attachment; filename=\"".$Filename."\"");
		    // header("Content-Length: ".filesize($Filename));
	   		readfile($file);
	        die;   	
    	}
    }           
}
?>