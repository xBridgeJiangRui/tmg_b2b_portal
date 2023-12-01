<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Restreport extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_model');
        $this->load->library('form_validation');        
		$this->load->library('datatables');
    }

    public function index()
    {
    	if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
	    	$data['report_type'] = $this->db->query("SELECT * from set_troubleshoot_menu WHERE module_name = 'rest_report' ORDER BY seq");
	    	$customer_guid = $_SESSION['customer_guid'];
	    	$data['supplier'] = $this->db->query("SELECT a.supplier_name as supplier_name,b.supplier_group_name as supplier_group_name from set_supplier a INNER JOIN set_supplier_group b ON a.supplier_guid = b.supplier_guid WHERE b.customer_guid = '$customer_guid' ORDER BY a.supplier_name");
	    	$data['details'] = $this->db->query("SELECT * from acc");
	    	$data['columns'] = '';
	    	$data['report_types'] = '';
	    	$data['supplier_name'] = '';
	    	$data['tablecolumnhead'] = '<th>No Data</th>';
	    	$data['datatable'] = '';
	    	$this->load->view('header');
		    $this->load->view('restreportfilter', $data);
		    $this->load->view('footer');
		}
		else
		{
			redirect('login_c');
		}
    }

    public function read_report()
    {
    	if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
        	$company = $this->input->post('company');
        	// echo $company;die;
	    	$report_types= $this->input->post('report_type');
	    	$supplier_name = $this->input->post('supplier_name');
	    	$location = $this->input->post('location');
	    	$start_date = $this->input->post('start_date');
	    	$end_date = $this->input->post('end_date');
		$RefNo = $this->input->post('RefNo');
	    	// print_r($location);die;
	    	/*if($RefNo != null || $RefNo != '')
	    	{

		    	$RefNo_array = explode(",",$RefNo);

		    	$RefNo_string = '';
		    	foreach($RefNo_array as $row)
		    	{
		    		$RefNo_string .= "'".$row."'".",";
		    	}

		    	$xRefNo = rtrim($RefNo_string, ",");

	    	}
	    	else
	    	{
	    		$xRefNo = '';
	    	}*/


	    	if(count($location) > 0)
	    	{
		    	$xlocation = '';
		    	foreach($location as $row)
		    	{
		    		$xlocation .= $row.',';
		    	}
		    }
		    else
		    {
		    	$xlocation = ',';
		    }

		    if(count($supplier_name) > 0)
	    	{
		    	$xsupplier_name = '';
		    	foreach($supplier_name as $row2)
		    	{
		    		$xsupplier_name .= $row2.',';
		    	}
		    }
		    else
		    {
		    	$xsupplier_name = ',';
		    }
	    	// echo $xlocation;die;
	    	// echo $report_types.$supplier_name;

	    	$customer_guid = $_SESSION['customer_guid'];

	    	$table_column = $this->db->query("SELECT table_column FROM set_troubleshoot_menu WHERE module_name = 'rest_report' AND code = '$report_types'")->row('table_column');
	    	$where_column = $this->db->query("SELECT where_column FROM set_troubleshoot_menu WHERE module_name = 'rest_report' AND code = '$report_types'")->row('where_column');
	    	$date_type = $this->db->query("SELECT date_type FROM set_troubleshoot_menu WHERE module_name = 'rest_report' AND code = '$report_types'")->row('date_type');
	    	// echo $this->db->last_query();die;

	    	$xrest_url = $this->db->query("SELECT rest_url FROM acc WHERE acc_guid = '$company'");

	    	$rest_url = $xrest_url->row('rest_url');
	    	$username = 'admin'; //get from rest.php
	        $password = '1234'; //get from rest.php

	        $data = array(
	                          "report_types"     =>$report_types,
	                          "supplier_name"     =>$xsupplier_name,
	                          "table_column"     =>$table_column,
	                          "where_column"     =>$where_column,
	                          "location"		=>$xlocation,
	                          "start_date"		=>$start_date,
	                          "end_date"		=>$end_date,
	                          "date_type"		=>$date_type,
				  "RefNo"		=>$RefNo
	                     );
	        // print_r($data);die;

	        // $url = 'http://192.168.10.29/lite_panda_b2b_rest/index.php/Api/checkreport';
	        $url = $rest_url.'/checkreport';
	        // echo $url;die;
	        $ch = curl_init($url);

	        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
	        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	        $result = curl_exec($ch);
	        //echo $result;
	        $output = json_decode($result);
	        $output2 = json_encode($output->rresult);
	        $data['datatable'] = $output2;

	        // echo var_dump($output->last_query);

		      $data['tabledatas'] = $output->rresult;
		      $columnhead = array();
		      $columnhead = '';
		      $columnhead .= '[';
	   		  foreach($data['tabledatas'] as $row) 
	          {
	             $i = 0;
	             
	             foreach($row as $key => $val) 
	             {
	             	// echo $key;
	     			$render_status = $this->db->query("SELECT guid,rename_column FROM set_troubleshoot_menu WHERE code = '$report_types'");
	     			// print_r($render_status->result());

	     			$render_string = '';

	     			foreach($render_status->result() as $row)
	     			{
	     				$render_string .= $row->rename_column;
	     			}

	     			// echo '<br>'.$key.'-';

	     			$render_array = explode(",",$render_string);
	     			// print_r($render_array);

	             	if(in_array($key, $render_array))
	             	{
	             	// echo '<br>'.$key.'<br>';	
	             		$xmenu_guid = $render_status->row('guid');
	             		$render_description = $this->db->query("SELECT a.boolean_condition,a.boolean_value,a.description,a.description2 FROM set_troubleshoot_condition a INNER JOIN set_troubleshoot_menu b ON a.menu_guid = b.guid WHERE b.guid = '$xmenu_guid' AND column_name = '$key' LIMIT 2");
	             		// echo $this->db->last_query();

             			if($render_description->num_rows() == 1)
             			{
             				// echo 1;die;
	             			$description = $render_description->row('description');
	             			$description2 = $render_description->row('description2');
	             			$boolean_condition = $render_description->row('boolean_condition');
	             			$boolean_value = $render_description->row('boolean_value');
	             			// echo $boolean_condition;die;
		             		$columnhead .= "{ data: '$key',render: function ( data, type, row ) {
	                        if (data $boolean_condition $boolean_value) { ischecked = '$description' } else { ischecked = '$description2' }
	                        return ''+ischecked+'';
	                        }},";
	                    }
	                    else if($render_description->num_rows() == 2)
             			{
             				// echo 2;die;
	             			// $description = $render_description->row('description');
	             			// $description2 = $render_description->row('description2');
	             			// $boolean_condition = $render_description->row('boolean_condition');
	             			// $boolean_value = $render_description->row('boolean_value');
	             			// echo $boolean_condition;die;
		             		$columnhead .= "{ data: '$key',render: function ( data, type, row ) {";
		             		$i = 0;
		             		foreach($render_description->result() as $row2)
		             		{
		             			if($i == 0)
		             			{
		             				$columnhead .= "if (data $row2->boolean_condition $row2->boolean_value) { ischecked = '$row2->description' } ";
		             				$description2 = $row2->description2;
		             			}
		             			else
		             			{
		             				$columnhead .= "else if(data $row2->boolean_condition $row2->boolean_value) { ischecked = '$row2->description' }";
		             				$description2 = $row2->description2;
		             			}
		             			$i++;
		             		}
	                        $columnhead .= "else { ischecked = '$description2' }
	                        return ''+ischecked+'';
	                        }},";
	                    }
	                    else
	                    {
                    		$columnhead .= "{ data: '$key'},";
	                    }
	                    // echo $columnhead;die;





	             	}else if($key == 'message'){
	             		$xmenu_guid = $render_status->row('guid');
	             		$message_status = $this->db->query("SELECT description,table_column FROM set_troubleshoot_status WHERE module_name = 'render_multiple_condition' AND code = '$report_types' AND menu_guid = '$xmenu_guid'");
	             		// echo var_dump($message_status->result());die;
             			// $message_condition = $message_status->row('table_column');
             			// $message_description = $message_status->row('description');
             			if($message_status->num_rows() > 0)
             			{ 
	             			$ii = 0;
	             			foreach($message_status->result() as $row2)
	             			{
	             				$message_condition = $row2->table_column;
	             				$message_description = $row2->description;
			             		if($ii == 0)
			             		{
			             			$columnhead .= "{ data: '$key',render: function ( data, type, row ) {
		                        	if ($message_condition) { ischecked = '$message_description' }";
		                        	$ii++;
		                        }
		                        else
		                        {
		                        	$columnhead .= "else if ($message_condition) { ischecked = '$message_description' }";
		                        	$ii++;
		                        }
		                    }

		                        $columnhead .= "else { ischecked = 'Status Not Found.Please contact Rexbridge with PO No.' }
		                        return ''+ischecked+'';
		                        }},";
	                    }
	                    else
	                    {
                    		$columnhead .= "{ data: '$key',render: function ( data, type, row ) {
	                        return 'Message not set';
	                        }},";
	                    }

	             	}
	             	else
	             	{
	             		$columnhead .= "{ data: '$key'},";
	             	}
	             	// echo $columnhead;die;
	             	// $columnhead .= "{ data: '$key',render: function ( data, type, row ) {
               //          if (row['uploaded'] < 3) { ischecked = '$description' } else { ischecked = '$description2' }
               //          return ''+ischecked+'';
               //          }},";
	                 
	         
	             }break;   
	    	  }
	    	  $columnhead .= ']';
	    	  // echo $columnhead;die;


	    	  $xcolumnheads = array();
	    	  $xcolumnheads = '';
	          foreach($data['tabledatas'] as $rows) 
	          {
	             $i = 0;
	             
	             foreach($rows as $key => $val) 
	             {
	     
	                 $xcolumnheads .= '<th>'.$key.'</th>';
	         
	             }break;   
	    	  }

	          $data['tablecolumnhead'] = $xcolumnheads;

	          // echo $xcolumnheads;



	    	$data['details'] = $this->db->query("SELECT * from acc");
	    	$data['report_type'] = $this->db->query("SELECT * from set_troubleshoot_menu WHERE module_name = 'rest_report' ORDER BY seq");
	    	$customer_guid = $this->input->post('company');
	    	$data['supplier'] = $this->db->query("SELECT a.supplier_name as supplier_name,b.supplier_group_name as supplier_group_name from set_supplier a INNER JOIN set_supplier_group b ON a.supplier_guid = b.supplier_guid WHERE b.customer_guid = '$customer_guid' ORDER BY a.supplier_name");


	    	$data['sreport_type'] = $report_types;
	    	$data['ssupplier_name'] = $supplier_name;

	    	$data['sreport_type_description'] = $this->db->query("SELECT description from set_troubleshoot_menu WHERE module_name = 'rest_report' AND code = '$report_types' ")->row('description');
	    	// print_r($data['sreport_type_description']);die;

	    	$sssupplier_name_description = $this->db->query("SELECT a.supplier_name as supplier_name,b.supplier_group_name as supplier_group_name from set_supplier a INNER JOIN set_supplier_group b ON a.supplier_guid = b.supplier_guid WHERE b.customer_guid = '$customer_guid' AND FIND_IN_SET(b.supplier_group_name,'$xsupplier_name')")->result();

	    	if(count($sssupplier_name_description) > 0)
	    	{
		    	$csupplier_name = '';
		    	foreach($sssupplier_name_description as $row3)
		    	{
		    		$csupplier_name .= $row3->supplier_name.',';		    	
		    	}
		    }
		    else
		    {
		    	$csupplier_name = 'All Supplier';
		    }

		    $data['ssupplier_name_description'] = rtrim($csupplier_name,",");


	    	$data['company'] = $company;
	    	$data['company_name'] = $this->db->query("SELECT acc_name FROM acc WHERE acc_guid = '$company'")->row('acc_name');

	    	$data['columns'] = $columnhead;
	    	// echo var_dump($data['columns']);die;
	    	$this->load->view('header');
		    $this->load->view('restreportfilter', $data);
		    $this->load->view('footer');
		}
		else
		{
			redirect('login_c');
		}
    }

    public function checkreport()
    {
    	$report_type = $this->input->post('report_type');
        $supplier_name = $this->input->post('supplier_name');

        // echo $report_type.$supplier_name;die;
        if($report_type == '' || $supplier_name == '')
        {
        	$xdata = array(
	        	'table' => array(),
	        	'columns' => ''
	        );

	        echo json_encode($xdata);
        }
        else
        {
	    	$username = 'admin'; //get from rest.php
	        $password = '1234'; //get from rest.php

	        $data = array(
	                          "report_type"     =>$report_type,
	                          "supplier_name"     =>$supplier_name,
	                     );

	        $url = 'http://192.168.10.29/lite_panda_b2b_rest/index.php/Api/checkreport';
	        $ch = curl_init($url);

	        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
	        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	        $result = curl_exec($ch);
	        $output = json_decode($result);
	        $output2 = $output->rresult;

	        
	        $xdata = array(
	        	'table' => $output2,
	        	'columns' => '[{ data: "RefNo"},{ data: "PODate"},]'
	        );

	        echo json_encode($xdata);
	    }
    } 


    public function fetch_supplier()
    {
    	$company = $this->input->post('company');
    	$company_selected = $this->db->query("SELECT a.supplier_name as supplier_name,b.supplier_group_name as supplier_group_name from set_supplier a INNER JOIN set_supplier_group b ON a.supplier_guid = b.supplier_guid WHERE b.customer_guid = '$company' ORDER BY b.supplier_group_name ASC");

    	$company_option = '';
    	if($company_selected->num_rows() > 0)
    	{
    		// $company_option .= '<option value="">Select A Supplier</option>';
	    	foreach($company_selected->result() as $row)
	    	{
	    		$company_option .= '<option value="'.$row->supplier_group_name.'">'.$row->supplier_group_name.'&nbsp&nbsp&nbsp&nbsp->&nbsp&nbsp&nbsp&nbsp'.$row->supplier_name.'</option>';
	    	}
	    }
	    else
	    {
	    	$company_option .= '<option value="">No Supplier Under This Company</option>';
	    }
    	echo $company_option;
    }

    public function fetch_location()
    {
    	$company = $this->input->post('company');
    	$branch_selected = $this->db->query("SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$company' ORDER BY a.branch_code ASC");

    	$branch_option = '';
    	if($branch_selected->num_rows() > 0)
    	{
    		// $branch_option .= '<option value="">Select A Location</option>';
	    	foreach($branch_selected->result() as $row)
	    	{
	    		$branch_option .= '<option value="'.$row->branch_code.'">'.$row->branch_code.'&nbsp&nbsp&nbsp&nbsp->&nbsp&nbsp&nbsp&nbsp'.$row->branch_name.'</option>';
	    	}
	    }
	    else
	    {
	    	$branch_option .= '<option value="">No Location Under This Company</option>';
	    }
    	echo $branch_option;
    }   
}
?>
