<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
      
		$this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('datatables');
        $this->load->library('Panda_PHPMailer');
		$this->load->model('Send_email_model');
        $this->local_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','local_ip','LIP');
    }

    public function index()
    {
       if($this->session->userdata('loginuser') == true)
       {

        	$session_id = $_REQUEST['session_id'];
        	$supplier_group_guid = $this->session->userdata('supplier_group_guid');
        	$name = $this->db->query("SELECT * from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_guid'); 
        	
        	$sessiondata = array (
        	   'name' => $name,
        	   'session_id' => $session_id,
        	   );

        	$this->session->set_userdata($sessiondata);
        	$this->load->view('email_report/email_report.php', $sessiondata); 
      }
      else
	  {
	       redirect('#');
	  }

    }

    public function email_report()
    {
    	if($this->session->userdata('loginuser') == true)
       	{
       		$customer_guid = $_SESSION['customer_guid'];

       		$name = $this->db->query("SELECT * from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_guid'); 
       
	       	$data = array (
	         'name' => $name,
	         'customer_guid' =>$customer_guid,
	        );

      	}
      	else
	    {
	        redirect('#');
	    }

    	$this->load->view('header'); 
        $this->load->view('email_report/email_report', $data);  
        $this->load->view('footer');  
    } 
    
    public function email_report_tb()
    {
    	ini_set('memory_limit', '-1');
    	ini_set('max_execution_time', 0); 

		$draw = intval($this->input->post("draw"));
		$start = intval($this->input->post("start"));
		$length = intval($this->input->post("length"));
		$order = $this->input->post("order");
		$search= $this->input->post("search");
		$search = $search['value'];
		$col = 0;
		$dir = "";

		if(!empty($order))
		{
		  foreach($order as $o)
		  {
		      $col = $o['column'];
		      $dir= $o['dir'];
		  }
		}

		if($dir != "asc" && $dir != "desc")
		{
		  $dir = "desc";
		}

		$valid_columns = array(
			0=>'guid',
            1=>'subject',
            2=>'email_id',
            3=>'from_email',
            4=>'updated_at',
            5=>'module',
            6=>'remark',
            7=>'status',
	
		);

		if(!isset($valid_columns[$col]))
		{
		  $order = null;
		}
		else
		{
		  $order = $valid_columns[$col];
		}

		if($order !=null)
		{   
		  // $this->db->order_by($order, $dir);

		  $order_query = "ORDER BY " .$order. "  " .$dir;
		}

		$like_first_query = '';
		$like_second_query = '';

		if(!empty($search))
		{
		  $x=0;
		  foreach($valid_columns as $sterm)
		  {
		      if($x==0)
		      {
		          // $this->db->like($sterm,$search);

		          $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

		      }
		      else
		      {
		          // $this->db->or_like($sterm,$search);

		          $like_second_query .= "OR $sterm LIKE '%".$search."%'";

		      }
		      $x++;
		  }
           
		}

		// $this->db->limit($length,$start);

		$limit_query = " LIMIT " .$start. " , " .$length;
		
		$sql = "SELECT * FROM lite_b2b.email_send_content WHERE module = 'key_in' ";

		$query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

		// $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

		$result = $this->db->query($query);

		// echo $this->db->last_query();

		if(!empty($search))
		{
			$query_filter = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query;
			$result_filter = $this->db->query($query_filter)->result();
			$total = count($result_filter);
		}
		else
		{
			$total = $this->db->query($sql)->num_rows();
		}


		$data = array();
		foreach($result->result() as $row)
		{
			$nestedData['guid'] = $row->guid;
			$nestedData['module'] = $row->module;
			$nestedData['remark'] = $row->remark;
			$nestedData['customer_guid'] = $row->customer_guid;
			$nestedData['user_guid'] = $row->user_guid;
			$nestedData['email_id'] = $row->email_id;
			$nestedData['subject'] = $row->subject;
			$nestedData['created_by'] = $row->created_by;
			$nestedData['created_at'] = $row->created_at;
			$nestedData['updated_by'] = $row->updated_by;
			$nestedData['updated_at'] = $row->updated_at;
			$nestedData['from_email'] = $row->from_email;
			$nestedData['status'] = $row->status;


		    $data[] = $nestedData;

		}

		$output = array(
		  "draw" => $draw,
		  "recordsTotal" => $total,
		  "recordsFiltered" => $total,
		  "data" => $data
		);

		echo json_encode($output);
    }

    public function fetch_content()
    {
    	$guid = $this->input->post("guid");

    	$content = $this->db->query("SELECT `content` FROM lite_b2b.email_send_content WHERE guid = '$guid' ");

    	$data = array(
    		"para1" => 0,
		  	"content" => $content->result(),
			);

		echo json_encode($data);

    }

    public function email_reset_list()
    {
    	if($this->session->userdata('loginuser') == true)
       	{
       		$customer_guid = $_SESSION['customer_guid'];

       		$name = $this->db->query("SELECT * from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_guid'); 
       		$customer = $this->db->query("SELECT a.* FROM lite_b2b.acc a ORDER BY a.acc_name ASC ")->result();

	       	$data = array (
	         'name' => $name,
	         'customer_guid' =>$customer_guid,
	         'customer' => $customer
	        );

      	}
      	else
	    {
	        redirect('#');
	    }

    	$this->load->view('header'); 
        $this->load->view('email_report/email_reset_list', $data);  
        $this->load->view('footer');  
    } 

    public function email_reset_list_tb()
    {
    	ini_set('memory_limit', '-1');
    	ini_set('max_execution_time', 0); 

		$draw = intval($this->input->post("draw"));
		$start = intval($this->input->post("start"));
		$length = intval($this->input->post("length"));
		$order = $this->input->post("order");
		$search= $this->input->post("search");
		$search = $search['value'];
		$col = 0;
		$dir = "";

		if(!empty($order))
		{
		  foreach($order as $o)
		  {
		      $col = $o['column'];
		      $dir= $o['dir'];
		  }
		}

		if($dir != "asc" && $dir != "desc")
		{
		  $dir = "desc";
		}

		$valid_columns = array(
			0=>'reset_guid',
            1=>'acc_name',
            2=>'email_id',
            3=>'is_reset',
            4=>'reset_at',
            5=>'viewed_at',
            6=>'ip',
            7=>'browser',
            8=>'deleted',
            9=>'updated_at',
            10=>'updated_by',
            11=>'created_at',
            12=>'created_by',

		);

		if(!isset($valid_columns[$col]))
		{
		  $order = null;
		}
		else
		{
		  $order = $valid_columns[$col];
		}

		if($order !=null)
		{   
		  // $this->db->order_by($order, $dir);

		  $order_query = "ORDER BY " .$order. "  " .$dir;
		}

		$like_first_query = '';
		$like_second_query = '';

		if(!empty($search))
		{
		  $x=0;
		  foreach($valid_columns as $sterm)
		  {
		      if($x==0)
		      {
		          // $this->db->like($sterm,$search);

		          $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

		      }
		      else
		      {
		          // $this->db->or_like($sterm,$search);

		          $like_second_query .= "OR $sterm LIKE '%".$search."%'";

		      }
		      $x++;
		  }
           
		}

		// $this->db->limit($length,$start);

		$limit_query = " LIMIT " .$start. " , " .$length;
		
		$sql = "SELECT a.`reset_guid`, a.`user_guid`, a.`customer_guid`, b.`acc_name`, a.`email_id`, a.`is_reset`, a.`reset_at`, a.`viewed_at`, a.`created_by`, a.`created_at`, a.`updated_by`, a.`updated_at`, a.`ip`, a.`browser` , a.`deleted` FROM lite_b2b.reset_pass_list a LEFT JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid ";

		$query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

		// $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

		$result = $this->db->query($query);

		//echo $this->db->last_query(); die;

		if(!empty($search))
		{
			$query_filter = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query;
			$result_filter = $this->db->query($query_filter)->result();
			$total = count($result_filter);
		}
		else
		{
			$total = $this->db->query($sql)->num_rows();
		}


		$data = array();
		foreach($result->result() as $row)
		{
			$nestedData['reset_guid'] = $row->reset_guid;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['user_guid'] = $row->user_guid;
            $nestedData['email_id'] = $row->email_id;
            $nestedData['is_reset'] = $row->is_reset;
            $nestedData['reset_at'] = $row->reset_at;
            $nestedData['viewed_at'] = $row->viewed_at;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['updated_by'] = $row->updated_by;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['ip'] = $row->ip;
            $nestedData['browser'] = $row->browser;
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['deleted'] = $row->deleted;

		    $data[] = $nestedData;
		}

		$output = array(
		  "draw" => $draw,
		  "recordsTotal" => $total,
		  "recordsFiltered" => $total,
		  "data" => $data
		);

		echo json_encode($output);
    }

    public function save_status_reset()
    {
    	$reset_guid = $this->input->post("reset_guid");
    	$reset_val = $this->input->post("reset_val");
    	$created_at = $this->input->post("created_at");
    	$time_at = $this->input->post("time_at");
    	$created_at = $created_at . ' ' . $time_at;

    	$updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    	$user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

    	if($reset_val == 1 )
    	{
	    	$data_1 = array(   
	    	  'is_reset' => $reset_val,
	    	  'reset_at' => $updated_at,
	    	  'created_at' => $created_at,
	    	  'created_by' => $user_id,
		      'updated_at' => $updated_at,
		      'updated_by' => $user_id
		    );

		    $this->db->where('reset_guid', $reset_guid);
		    $this->db->update('reset_pass_list', $data_1);
    	}else
    	{
    		$data_1 = array(   
	    	  'is_reset' => $reset_val,
	    	  'reset_at' => '1001-01-01 00:00:00',
	    	  'created_at' => $created_at,
	    	  'created_by' => $user_id,
		      'updated_at' => $updated_at,
		      'updated_by' => $user_id
		    );

		    $this->db->where('reset_guid', $reset_guid);
		    $this->db->update('reset_pass_list', $data_1);
    	}


	    $error = $this->db->affected_rows();

	    if($error > 0){

	         $data = array(
	          'para1' => 0,
	          'msg' => 'Save Successfully',

	          );    
	          echo json_encode($data);   
	    }
	    else
	    {   
	        $data = array(
	        'para1' => 1,
	        'msg' => 'Error.',

	        );    
	        echo json_encode($data);   
	    }
    }

    public function fetch_email()
	{
	  $type_val = $this->input->post('type_val');
	  $Code = $this->db->query("SELECT user_id FROM lite_b2b.`set_user` WHERE acc_guid = '$type_val' AND user_id != 'null' ORDER BY user_id ASC");

	  $data = array(
	      'Code' => $Code->result(),
	  );

	  echo json_encode($data);
	}

	public function create_email_reset()
    {
    	$acc_name = $this->input->post("acc_name");
    	$email_data = $this->input->post("email_data");
    	$updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    	$user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
    	$reset_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
    	$user_guid = $this->db->query("SELECT user_guid FROM set_user WHERE user_id = '$email_data' ")->row('user_guid');
    	$check_email_data = $this->db->query("SELECT * FROM reset_pass_list WHERE customer_guid ='$acc_name' AND email_id ='$email_data'");

    	if($check_email_data->num_rows() > 0)
    	{
	      	$data = array(
	        'para1' => 1,
	        'msg' => 'Already Exists.',

	        );    
	        echo json_encode($data);   
	      exit();
    	}

	    $data_1 = array(   
	    	'reset_guid`' => $reset_guid,
        	'customer_guid`' => $acc_name,
        	'user_guid`' => $user_guid,
        	'email_id`' => $email_data,
        	'is_reset`' => 0,
        	'reset_at`' => '1001-01-01 00:00:00',
        	'created_by`' => $user_id,
        	'created_at`' => $updated_at,
        	'updated_by`' => $user_id,
        	'updated_at`' => $updated_at
		);

		$this->db->insert('reset_pass_list', $data_1);
    	
	    $error = $this->db->affected_rows();

	    if($error > 0){

	         $data = array(
	          'para1' => 0,
	          'msg' => 'Create Successfully',

	          );    
	          echo json_encode($data);   
	    }
	    else
	    {   
	        $data = array(
	        'para1' => 1,
	        'msg' => 'Error.',

	        );    
	        echo json_encode($data);   
	    }
    }

    public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module,$cc_list_string,$pdf,$reply_to)
  	{
        // die;
        if($pdf != '' || $pdf != null)
        { 
            $b64Doc = chunk_split(base64_encode(file_get_contents($pdf))); 
            $filename = substr($pdf, strrpos($pdf, '/') + 1);
        }
        else
        {
            $b64Doc = ''; 
        }
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup WHERE type = 'alert_retailer_supplier_setup' LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
        $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');

        $replyto = array('Email' => $reply_to,'Name' => $reply_to);
        $from = array('Email' => $from_email->row('sender_email'),'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email,'Name' => $to_email_name);
        $to_array = array($to);

        if($cc_list_string != '' || $cc_list_string != null)
        {
            $test_array = explode(',',$cc_list_string);
            $cc_array=array();
            foreach($test_array as $tarray)
            {
                // echo $tarray->sender_email;
                $cc = array('Email' => $tarray,'Name' => $tarray);
                array_push($cc_array, $cc);
            }
        }
        else
        {
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

        if($b64Doc != '')
        {
            $attachment = array('ContentType' => 'application/pdf','Filename' => $filename,'Base64Content' => $b64Doc);
            $attachment1 = array($attachment);
            $attachment_array = array($attachment);            
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables,'cc' => $cc_array, 'replyto' =>$replyto,'attachments' => $attachment_array);
        }
        else
        {
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables, 'replyto' =>$replyto);
        }
        // $data2 = array($data);
        // $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data);
        // echo $myJSON;die;

        $to_shoot_url = $this->local_ip."/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
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
        // print_r($result1);die;
        // print_r($result1->Messages[0]->Errors[0]->StatusCode);die;
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
                    'created_by' =>$_SESSION["userid"],
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
                return $result1->Messages[0]->Status;             

            }
            else
            {
                $ereponse = $result1->Messages[0]->Errors[0]->StatusCode;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
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
                return $result1->Messages[0]->Status.'_'.$ereponse;
                // };
            }

            curl_close($ch);
        }
        else
        {
                $ereponse = 'Curl error: '.curl_error($ch);

                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
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
                return $result1->Messages[0]->Status.$ereponse;
        }         
  	} 

    public function resend_link()
    {	
    	$reset_guid = $this->input->post("reset_guid");
    	$user_guid = $this->input->post("user_guid");
    	$customer_guid = $this->input->post("customer_guid");
    	$email_id = $this->input->post("email_id");
    	$msg ='';
        $get_user_array = $this->db->query("SELECT * FROM lite_b2b.set_user WHERE user_guid = '$user_guid' LIMIT 1");
        if($get_user_array->num_rows() <= 0)
        {
          $msg .= 'Email address :'.$email_id.' duplicate template send.';
          // continue;
        }
        $email_name = $email_id;
        $email_add = $email_id; 
        // $email_name = 'danielweng57';
        // $email_add = 'danielweng57';  
                      
        $subject = 'Login Details';
        $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
        // $reset_guid = $row->reset_guid;
        $url = base_url();
        $reset_link = $this->db->query("SELECT * FROM lite_b2b.reset_pass_list WHERE reset_guid = '$reset_guid'");

        $reset_url = base_url() . 'index.php/Key_in/key_in?si='.$reset_link->row('reset_guid').'&ug='.$reset_link->row('user_guid');
        // echo $reset_url;die;

        // $get_supp_guid = $this->db->query("SELECT a.*, b.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.`customer_guid` = '$customer_guid' ");
		$get_supp_guid = $this->db->query("SELECT a.*, b.* FROM lite_b2b.`set_supplier_user_relationship` a INNER JOIN lite_b2b.`set_supplier_group` b ON a.`supplier_guid` = b.`supplier_guid` AND a.`customer_guid` = b.`customer_guid` WHERE a.`user_guid` = '$user_guid' AND a.`customer_guid` = '$customer_guid' ");             

        $supplier_guid = $get_supp_guid->row('supplier_guid');

        $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");

        $supplier_code = $this->db->query("SELECT GROUP_CONCAT(DISTINCT supplier_group_name) as vendor_code FROM lite_b2b.set_supplier_group WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$customer_guid'");

        $email_data = array(
                'reset_detail' => $reset_link,
                'customer_name' => $customer_name,
                'user_detail' => $get_user_array,
                'reset_url' => $reset_url,
                'supplier_detail' => $supplier_detail,
                'supplier_code' => $supplier_code,
                );

        $bodyContent    = $this->load->view('email_template/user_login_reset_view',$email_data,TRUE);
        // echo $bodyContent;die;  
        // die;    
		$send_result = $this->Send_email_model->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '', '', '', 'no-reply@sunshineonline.com.my','');

        $data_email = array(
          'guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
          'module' => 'key_in',
          'remark' => 'new',
          'customer_guid' => $customer_guid,
          'user_guid' => $get_user_array->row('user_guid'),
          'status' => $send_result,
          'from_email' => 'no-reply@sunshineonline.com.my',
          'email_id' => $email_add,
          'subject' => $subject,
          'content' => $bodyContent,
          'created_by' => $_SESSION['userid'],
          'created_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
          'updated_by' => $_SESSION['userid'],
          'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),          
        );   
        $this->db->insert('lite_b2b.email_send_content',$data_email);
        // echo $email_add;die;

        $msg .= 'Email address :'.$email_id.' new user template send.';

        $data = array(
	      'para1' => 1,
	      'msg' => $msg,

	    );  

	    echo json_encode($data);
    }

    public function delete_email_reset_list()
   	{
	    $reset_guid = $this->input->post('reset_guid');
    	$updated_at = $this->db->query("SELECT NOW() as now")->row('now');
    	$user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

	    $data = array(
	      'deleted' => 1,
	      'updated_at' => $updated_at,
	      'updated_by' => $user_id,
	    );

	    $this->db->where('reset_guid',$reset_guid);
	    $this->db->update("lite_b2b.reset_pass_list",$data);

	    $error = $this->db->affected_rows();

	    if($error > 0){

	         $data = array(
	          'para1' => 0,
	          'msg' => 'Delete Successfully',

	          );    
	          echo json_encode($data);   
	    }
	    else
	    {   
	        $data = array(
	        'para1' => 1,
	        'msg' => 'Error.',

	        );    
	        echo json_encode($data);   
	    }

   	}//close delete_shop_category_item
}
?>