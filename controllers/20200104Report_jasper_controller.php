<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_jasper_controller extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
        $this->load->library('Panda_PHPMailer');
	}


    public function index()
    {
       // $this->load->view('header');
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $this->load->view('forgot_email_form');
            $this->load->view('footer');
        }
        else
        {
            redirect('Logout');
        }
    }


    public function subscription()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $customer_guid = $_SESSION['customer_guid'];

            $data = array(
		                'report' => $this->db->query("SELECT * from report_module_ci WHERE childID NOT IN(SELECT parentID FROM report_module_ci)"),
		                //'user_list' => $this->db->query("SELECT a.user_guid,c.supplier_name,a.user_name,b.acc_name,a.user_id,a.user_password,GROUP_CONCAT(DISTINCT e.Description SEPARATOR ',') as description FROM set_user a INNER JOIN acc b ON a.acc_guid = b.acc_guid LEFT JOIN set_supplier c ON a.supplier_guid = c.supplier_guid LEFT JOIN user_subscribe_report d ON a.user_guid = d.user_guid LEFT JOIN report_module_ci e ON d.report_guid = e.childID GROUP BY a.user_guid"),
                        'user_list' => $this->db->query("SELECT GROUP_CONCAT(DISTINCT k.Description SEPARATOR ',') as description,i.acc_name,a.acc_guid, a.user_guid,user_id, user_name, f.supplier_name , all_sup_assigned,user_password FROM ( SELECT `acc_guid`, `branch_guid`, `module_group_guid`, `user_group_guid`, a.`user_guid`, a.`supplier_guid`, `user_id`, `user_password`, `user_name`, a.`created_at`, a.`created_by`, a.`updated_at`, a.`updated_by` , `supplier_name`, `reg_no`, `gst_no`, `name_reg` FROM set_user AS a 
                            LEFT JOIN set_supplier AS b ON a.supplier_guid = b.supplier_guid WHERE acc_guid = '$customer_guid' GROUP BY a.user_guid ORDER BY a.updated_at DESC ) AS a 
                            inner JOIN ( SELECT supplier_guid,user_guid , GROUP_CONCAT(supplier_group_name) AS all_sup_assigned FROM `check_user_supplier_customer_relationship` where acc_guid = '$customer_guid' GROUP BY user_guid, acc_guid ) b ON a.user_guid = b.user_guid 
                            LEFT JOIN set_supplier f ON b.supplier_guid = f.supplier_guid  
                            INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') g
                            ON b.supplier_guid = g.supplier_guid
                            INNER JOIN b2b_summary.supcus h ON g.backend_supcus_guid = h.supcus_guid
                            INNER JOIN acc i ON a.acc_guid = i.acc_guid
                            LEFT JOIN user_subscribe_report j ON a.user_guid = j.user_guid
                            LEFT JOIN report_module_ci k ON j.report_guid = k.childID
                            GROUP BY a.user_guid,a.acc_guid
                            ORDER BY f.supplier_name"),

                    );
		            $this->load->view('header');
		            $this->load->view('jasper_report_subscription',$data);
		            $this->load->view('jasper_report_subscription_modal',$data);
		            $this->load->view('footer');
        }
        else
        {
            redirect('Logout');
        }
    }

    public function subscription_report()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
        	// print_r($this->input->post()).'<br>';
        	// print_r($this->input->post('report_guid'));

        	$guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        	$user_guid = $this->input->post('user_guid');
        	$report_guid = $this->input->post('report_guid');
        	$table = 'user_subscribe_report';

        	foreach($report_guid as $row)
        	{
        		$data = array(
        			'guid' => $guid,
        			'user_guid' => $user_guid,
        			'report_guid' => $row,
        			'created_at' => $this->db->query("SELECT NOW() as created_at")->row('created_at'),
        			'created_by' => $_SESSION['userid'],
        		);

        		$this->db->insert($table,$data);
        		// print_r($data);
        	}

        	if($this->db->affected_rows() > 0)
        	{
        		$this->session->set_flashdata('message', 'Report Subscribe Successfully');
                redirect('Report_jasper_controller/subscription');
        	}
        	else
        	{
        		$this->session->set_flashdata('warning', 'Report Subscribe Unsuccessfully');
                redirect('Report_jasper_controller/subscription');	
        	}
        	die;
        }
        else
        {
            redirect('Logout');
        }
    }

    public function fetch_dropdown()
    {
    	$user_guid = $this->input->post('user_guid');
    	// $user_guid = '095D5566F03D11E791CBA81E8453CCF0';
    	$xreport = $this->db->query("SELECT report_guid from user_subscribe_report WHERE user_guid = '$user_guid'")->result();
    	$report = $this->db->query("SELECT * from report_module_ci WHERE childID NOT IN(SELECT parentID FROM report_module_ci)")->result();

    	$dropdown = '';

    	$dropdown2 = array();
    	foreach($xreport as $row2)
    	{
    		$dropdown2[] .= $row2->report_guid;
    	}
    	// print_r($dropdown2);die;

    	foreach($report as $row)
    	{
    		if(in_array($row->childID,$dropdown2))
    		{
	    		$dropdown .= '<option value="'.$row->childID.'" selected>';
	    		$dropdown .= $row->Description;
	    		$dropdown .= '</option>';
	    	}
	    	else
	    	{
	    		$dropdown .= '<option value="'.$row->childID.'">';
	    		$dropdown .= $row->Description;
	    		$dropdown .= '</option>';	
	    	}
    	}
    	echo $dropdown;

    }

    public function edit_subscription_report()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
        	// echo var_dump($this->input->post());die;
        	$guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        	$user_guid = $this->input->post('user_guid');
        	$report_guid = $this->input->post('report_guid');
		$acc_guid = $this->input->post('acc_guid');

        	$table = 'user_subscribe_report';

        	$status = $this->db->query("SELECT * FROM user_subscribe_report WHERE user_guid = '$user_guid'");
        	
        	if($status->num_rows() > 0)
        	{
        		$created_at = $status->row('created_at');
        		$created_by = $status->row('created_by');

        		$this->db->query("DELETE FROM user_subscribe_report WHERE user_guid = '$user_guid'");

        		if($this->db->affected_rows() > 0)
        		{
    		    	foreach($report_guid as $row)
    		    	{
    		    		$data = array(
    		    			'guid' => $guid,
    		    			'user_guid' => $user_guid,
					'customer_guid' =>$acc_guid,
    		    			'report_guid' => $row,
    		    			'updated_at' => $this->db->query("SELECT NOW() as created_at")->row('created_at'),
    		    			'updated_by' => $_SESSION['userid'],
    		    			'created_at' => $created_at,
    		    			'created_by' => $created_by,
    		    		);

    		    		$this->db->insert($table,$data);
    		    		// print_r($data);
    		    	}

    		    	if($this->db->affected_rows() > 0)
    		    	{
    		    		$this->session->set_flashdata('message', 'Update Report Subscribe Successfully');
    		            redirect('Report_jasper_controller/subscription');
    		    	}
    		    	else
    		    	{
    		    		$this->session->set_flashdata('warning', 'Update Report Subscribe Unsuccessfully');
    		            redirect('Report_jasper_controller/subscription');	
    		    	} 
    	    	}//close affected 

        	}
        	else
        	{
    	    	foreach($report_guid as $row)
    	    	{
    	    		$data = array(
    	    			'guid' => $guid,
    	    			'user_guid' => $user_guid,
				'customer_guid' =>$acc_guid,
    	    			'report_guid' => $row,
    	    			'created_at' => $this->db->query("SELECT NOW() as created_at")->row('created_at'),
    	    			'created_by' => $_SESSION['userid'],
    	    		);

    	    		$this->db->insert($table,$data);
    	    		// print_r($data);
    	    	}

    	    	if($this->db->affected_rows() > 0)
    	    	{
    	    		$this->session->set_flashdata('message', 'Report Subscribe Successfully');
    	            redirect('Report_jasper_controller/subscription');
    	    	}
    	    	else
    	    	{
    	    		$this->session->set_flashdata('warning', 'Report Subscribe Unsuccessfully');
    	            redirect('Report_jasper_controller/subscription');	
    	    	}    		
        	} 
        }
        else
        {
            redirect('Logout');
        }   	
    }

    public function setting()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array(
                // 'report' => $this->db->query("SELECT * from report_module_ci WHERE childID NOT IN(SELECT parentID FROM report_module_ci)"),
                'menu' => $this->db->query("SELECT * FROM lite_b2b.report_module_ci WHERE hide = 0 AND parentID = '0'  ORDER BY seq ASC LIMIT 0")->result(), 
                'report_list' => $this->db->query("SELECT * FROM report_module_ci ORDER BY seq ASC"),
                        );
            $this->load->view('header');
            $this->load->view('jasper_report_setting',$data);
            $this->load->view('jasper_report_setting_modal',$data);
            $this->load->view('footer');
        // $this->load->view('jasper_report_setting');
        }
        else
        {
            redirect('Logout');
        }
    }

    public function check_seq_avaibility()
    {
        $seq = $this->input->post('seq');
        $checkseq = $this->db->query("SELECT seq FROM report_module_ci WHERE seq = '$seq'");

        if($checkseq->num_rows() > 0)
        {
            $output = 1;
        }
        else
        {
            $output = 0;
        }

        echo $output;
    
    }

    public function check_reportmenu_avaibility()
    {

        $web_index = $this->input->post('web_index');
        $checkweb_index = $this->db->query("SELECT web_index FROM report_module_ci WHERE web_index = '$web_index'");

        if($checkweb_index->num_rows() > 0)
        {
            $output = 1;
        }
        else
        {
            $output = 0;
        }

        echo $output;

    }

    public function addreport()
    {
        // print_r($this->input->post());die;
        $ori_web_index = $this->input->post('ori_web_index');
        $parentreport = $this->input->post('parentreport');
        // $seqoriginal = $this->input->post('seqoriginal');
        $descriptionss = $this->input->post('descriptionss');
        $web_index = $this->input->post('web_index');
        // $web_index_original = $this->input->post('web_index_original');
        $actions = $this->input->post('actions');
        $hidestatus = $this->input->post('hidestatus');
        $seq = $this->input->post('seq');
        $childID = $this->input->post('childID');
        $table = "report_module_ci";
        // $jasper_report_url = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'url' ")->row('reason');
        // $jasper_report_folder = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'folder' ")->row('reason');
        $jasper_report_url = $this->input->post('jasper_report_url');
        $jasper_report_folder = $this->input->post('jasper_report_folder');   
        $customer_guid = $_SESSION['customer_guid'];     
        $report_uuid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');


        // echo $actions;die;
        if($hidestatus == '' || $hidestatus == null)
        {
            $xhidestatus = 0;
        }
        else
        {
            $xhidestatus = 1;
        }

        if($actions == 'Save')
        {
            $checkreportstatus = $this->db->query("SELECT * FROM user_subscribe_report WHERE report_guid = '$parentreport'");
            // echo 
            if($checkreportstatus->num_rows() == 0)
            {    
                $data = array(
                'customer_guid' => $customer_guid,    
                'seq' => $seq,
                'Description' => $descriptionss,
                'parentID' => $parentreport,
                'jasper_report_url' => $jasper_report_url,
                'jasper_report_folder'  => $jasper_report_folder,
                // 'childID' => $date,
                'web_index' => $web_index,
                // 'report_guid' => $report_uuid,
                'hide' => $xhidestatus,
                'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                'updated_by' => $_SESSION['userid']
                // 'created_at' => $this->db->query("SELECT NOW() as created_at")->row('created_at'),
                // 'created_by' => $_SESSION['user_id']
                );

                $this->db->where('childID', $childID);
                $this->db->update($table, $data);

                if($this->db->affected_rows() > 0)
                {
                    $this->session->set_flashdata('message', 'Update Successfully');
                    redirect('Report_jasper_controller/setting');  
                }
                else
                {
                    $this->session->set_flashdata('warning', 'Update Unsuccessfully');
                    redirect('Report_jasper_controller/setting');        
                }
            }
            else
            {
                $this->session->set_flashdata('warning', 'This choosen parent report is subscribed as a report, unavailable to add report under it.');
                redirect('Report_jasper_controller/setting');   
            }
        }
        else if($actions == 'Add')
        {
            $checkreportstatus = $this->db->query("SELECT * FROM user_subscribe_report WHERE report_guid = '$parentreport'");
            // echo 
            if($checkreportstatus->num_rows() == 0)
            { 
                $data2 = array(
                'customer_guid' => $customer_guid,
                'seq' => $seq,
                'Description' => $descriptionss,
                'parentID' => $parentreport,
                'childID' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
                'web_index' => $web_index,
                'hide' => $xhidestatus,
                'jasper_report_url' => $jasper_report_url,
                'jasper_report_folder'  => $jasper_report_folder,
                'report_guid'   =>  $report_uuid,
                // 'updated_at' => $this->db->query("SELECT NOW() as updated_at")->row('updated_at'),
                // 'updated_by' => $_SESSION['userid']
                'created_at' => $this->db->query("SELECT NOW() as created_at")->row('created_at'),
                'created_by' => $_SESSION['userid']
                );

                $this->db->insert($table, $data2);

                if($this->db->affected_rows() > 0)
                {
                    $this->session->set_flashdata('message', 'Add Report Successfully');
                    redirect('Report_jasper_controller/setting'); 
                }
                else
                {
                    $this->session->set_flashdata('warning', 'Update Unsuccessfully');
                    redirect('Report_jasper_controller/setting');
                }
            }
            else
            {
                    $this->session->set_flashdata('warning', 'This choosen parent report is subscribed as a report, unavailable to add report under it.');
                    redirect('Report_jasper_controller/setting');          
            } 
        }
        else
        {
            $this->session->set_flashdata('warning', 'Something Went Wrong');
            redirect('Report_jasper_controller/setting');  
        }


    }

    public function get_user_report($childID){

        $data = '';

        $query = $this->db->query("SELECT childID FROM report_module_ci WHERE parentID ='$childID'")->row('childID');
        // echo $this->db->last_query();
        if($query != '')
        {
            $data .= $query.",";
            $data .= $this->get_user_report($query);
        }
        return $data;
        
    }

    public function fetch_report_dropdown()
    {
        // echo 'haha';die;
        $childID = $this->input->post('childID');
        $report_guid = $this->input->post('report_guid');
        $data = $this->get_user_report($childID);
        $dropdown3 = array();

        $xdropdown3 = substr(trim($data), 0, -1);
        $sdropdown3 = explode(",",$xdropdown3);
        // echo var_dump($sdropdown3);die;
        // echo $childID;
        // $user_guid = '095D5566F03D11E791CBA81E8453CCF0';
        $jasper_report = $this->db->query("SELECT * FROM report_module_ci WHERE report_guid = '$report_guid'");
        $jasper_report_url = $jasper_report->row('jasper_report_url');
        $jasper_report_folder = $jasper_report->row('jasper_report_folder');

        $menu = $this->db->query("SELECT * FROM lite_b2b.report_module_ci WHERE hide = 0 AND parentID != '$childID' AND childID != '$childID' ORDER BY seq ASC")->result();
        // echo $this->db->last_query();die;
        $xmenu = $this->db->query("SELECT * FROM lite_b2b.report_module_ci WHERE hide = 0 AND childID = '$childID'  ORDER BY seq ASC")->result();

        $dropdown = '';

        $dropdown2 = array();
        foreach($xmenu as $row2)
        {
            $dropdown2[] .= $row2->parentID;
        }
        // print_r($dropdown2);die;
        // print_r($sdropdown3);
        // $sdropdown3 = array("bb","cc");
        // print_r($sdropdown3);
        $dropdown .= '<option value="0">No Parent Report</option>';
        foreach($menu as $row)
        {
            if(!in_array($row->childID,$sdropdown3))
            {
                if(in_array($row->childID,$dropdown2))
                {
                    $dropdown .= '<option value="'.$row->childID.'" selected>';
                    $dropdown .= $row->Description;
                    $dropdown .= '</option>';
                }
                else
                {
                    $dropdown .= '<option value="'.$row->childID.'">';
                    $dropdown .= $row->Description;
                    $dropdown .= '</option>';   
                }
            }
        }

        $output = array(
            'dropdown'  =>  $dropdown,
            'jasper_report_url' =>  $jasper_report_url,
            'jasper_report_folder'  =>  $jasper_report_folder
        );

        echo json_encode($output);

    }    

    public function fetch_report_dropdown_all()
    {
        // echo 'haha';die;
        $childID = $this->input->post('childID');
        // echo $childID;
        // $user_guid = '095D5566F03D11E791CBA81E8453CCF0';
        $menu = $this->db->query("SELECT * FROM lite_b2b.report_module_ci WHERE hide = 0 ORDER BY seq ASC")->result();
        // echo $this->db->last_query();die;

        $dropdown = '';

        // print_r($dropdown2);die;
        $dropdown .= '<option value="0" selected>No Parent Report</option>';
        foreach($menu as $row)
        {
                $dropdown .= '<option value="'.$row->childID.'">';
                $dropdown .= $row->Description;
                $dropdown .= '</option>';
        }
        echo $dropdown;
        // echo 'haha';

    }  

    public function deletereport()
    {
        $childID = $this->input->post('childID');

        $this->db->query("DELETE FROM report_module_ci WHERE childID = '$childID'");

        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('message', 'Delete Report Sucessfully');
            redirect('Report_jasper_controller/setting');
        }
        else
        {
            $this->session->set_flashdata('warning', 'Delete Report Unsucessfully');
            redirect('Report_jasper_controller/setting');
        }
    } 

    public function jasper_new_view()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
		echo '123';die;
	
            if (isset($_SERVER['HTTPS'] == "on")) 
            {
        //        $url = "http://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];    
          //      header("Location: $url");
            } 
            
            $this->load->view('header');
            $this->load->view('jasper_report_view_new');
            $this->load->view('footer'); 
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function jasper_report_new_view()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid'];
        $from_module = 'consign_report';

        if($_SESSION['user_group_name'] != 'SUPER_ADMIN' || $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN')
        {
            
            $this->db->query("REPLACE into supplier_movement select 
            upper(replace(uuid(),'-','')) as movement_guid
            , '$customer_guid'
            , '$user_guid'
            , 'viewed_consign'
            , '$from_module'
            , ''
            , now()
            ");
        
        };

        // $domain = $_SERVER['SERVER_NAME'];
        // $REQUEST_URI = $_SERVER['REQUEST_URI'];
        // $QUERY_STRING = $_SERVER['QUERY_STRING'];
        // $PATH_INFO = $_SERVER['PATH_INFO'];

        $database1 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database1'")->row('reason');
        $database2 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database2'")->row('reason');
        $database3 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database3'")->row('reason');
        $database4 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database4'")->row('reason');
        
        // echo $REQUEST_URI;die;
        // $haha = substr($REQUEST_URI, strpos($REQUEST_URI, "?") + 7);  
        $link = $_REQUEST['link2'];
        $url_array = $this->db->query("SELECT * FROM report_module_ci WHERE report_guid = '$link'");
        // echo $url_array->num_rows();die;
        $furl = $url_array->row('jasper_report_url');
        $lurl = $url_array->row('jasper_report_folder');
        $user_guid = $this->session->userdata('user_guid');
        $session_user_guid=$this->session->userdata('user_logs');
        $db_b2b = $database1;
        $db_backend = $database2;
        $db_member = $database3;
        $db_frontend = $database4;

        // $jasper_report_folder = $row->jasper_report_folder;
        $run_url = $furl.'&user_guid='.$user_guid.'&session_user_guid='.$session_user_guid.'&db_b2b='.$database1.'&db_backend='.$database2.'&db_member='.$database3.'&db_frontend='.$database4.'&j_username=panda_b2b&j_password=b2b@adnap'.$lurl;

         // header('Location:'.$run_url.'');
        redirect($run_url);
    }

    public function fetch_multiple_subscribe()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $customer_description = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'");
        $user_list = $this->db->query("SELECT * FROM (SELECT * FROM set_user WHERE acc_guid = '$customer_guid' GROUP BY user_guid) a INNER JOIN (SELECT * FROM set_supplier_user_relationship WHERE customer_guid = '$customer_guid' GROUP BY user_guid) b ON a.user_guid = b.user_guid");

        $report_list = $this->db->query("SELECT * FROM report_module_ci WHERE parentID = '0'");

        $select = '';
        $select .= '<div style="padding-left:0;" class="col-md-10"><select style="width:100%" name="jasper_subscribe_user_guid[]" id="jasper_subscribe_user_guid" class="form-control select2" multiple required>';
        foreach($user_list->result() as $row)
        {
            // echo $row->user_id.'<br>';
            $select .= '<option value="'.$row->user_guid.'">'.$row->user_id.'</option>';
        }
        $select .= '</select></div><div class="col-md-1"><button class="btn btn-sm btn-primary" type="button" id="jasper_user_all">All</button></div><div class="col-md-1"><button class="btn btn-sm btn-danger" type="button" id="jasper_user_diselect_all">X</button></div>';

        $report = '';
        $report .= '<select style="width:100%" name="jasper_subscribe_report_guid" class="form-control" required>';
        foreach($report_list->result() as $row)
        {
            // echo $row->user_id.'<br>';
            $report .= '<option value="'.$row->childID.'">'.$row->Description.'</option>';
        }
        $report .= '</select>';

        $data = array(
            'customer_guid' => $customer_guid,
            'customer_name' => $customer_description->row('acc_name'),
            'select' => $select,
            'report' => $report,
        );
        echo json_encode($data);
    }

    public function add_multiple()
    {
        $customer_guid = $this->input->post("jasper_user_customer_guid");
        $report_guid = $this->input->post("jasper_subscribe_report_guid");
        $user_guid = $this->input->post("jasper_subscribe_user_guid");
        $customer_guid = $_SESSION['customer_guid'];

        foreach($user_guid as $row)
        {
            $data[] = array(
            'customer_guid' => $customer_guid,
            'guid' => $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid'),
            'user_guid' => $row,
            'report_guid' => $report_guid,
            'created_at' => $this->db->query("SELECT NOW() as created_at")->row('created_at'),
            'created_by' => $_SESSION['userid'],
            );
        }
        $replace_query = $this->db->replace_batch('user_subscribe_report', $data);

        if($replace_query > 0)
        {
            $this->session->set_flashdata('message', 'Report Subscribe Successfully');
            redirect('Report_jasper_controller/subscription');
        }
    }
}
?>

