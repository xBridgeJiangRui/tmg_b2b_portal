<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_loop extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_model');
        $this->load->library('form_validation');        
		$this->load->library('datatables');
    }

    public function index(){
    	$this->main_top_menu();
    }

    public function main_top_menu(){
    	$query = '';
    	$query .= '<li class="active treeview"><a href="#"><i class="glyphicon glyphicon-list-alt"></i> <span>Subscription Report</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>';
    	$query .= '<ul class="treeview-menu">';
    	$xuser_guid = $_SESSION['user_guid'];
    	// echo $xuser_guid;die; 
    	$user_report = $this->db->query("SELECT report_guid FROM user_subscribe_report a INNER JOIN report_module_ci b ON a.report_guid = b.childID WHERE a.user_guid = '$xuser_guid' ORDER BY b.seq ASC");
    	
    	// $input = array("ee","dd","aa");
    	// foreach($input as $row)
    	// {
    	// 	echo $row . ','.'<br>'.'<br>';
    	// }
    	// foreach($user_report->result() as $row)
    	// {
    	// 	echo $row->report_guid . ','.'<br>'.'<br>';
    	// }
    	// print_r($user_report->result());die;

    	foreach($user_report->result() as $row){
    	$data = $this->get_user_report($row->report_guid);
    	
    	// echo $data;die;
    	
    	$data2 = $this->get_user_report_childID($row->report_guid);


    	}

    	$parentID = substr(trim($data), 0, -1);
    	$childID = substr(trim($data2), 0, -1);
    	// echo $childID;die;
    	$query .= $this->main_menu($parentID,$childID);

    	$query .= '</ul></li>';

    	echo $query;
    }

    public function get_user_report($subscription){

    	$data = '';

    	$query = $this->db->query("SELECT parentID FROM report_module_ci WHERE childID IN('$subscription')")->result();
    	// echo $this->db->last_query();
    	if(!empty($query)){
    		foreach($query as $row){
    		$data .= "'".$row->parentID."',";
    		$data .= $this->get_user_report($row->parentID);

    		}
    	}

    	return $data;
    	
    }


    public function get_user_report_childID($subscription){

    	$data2 = '';

    	$query = $this->db->query("SELECT parentID,childID FROM report_module_ci WHERE childID IN('$subscription')")->result();

    	if(!empty($query)){
    		foreach($query as $row){
    		$data2 .= "'".$row->childID."',";
    		$data2 .= $this->get_user_report_childID($row->parentID);
    		
    		}
    	}

    	return $data2;
    	
    }

    public function main_menu($parentID,$childID){

    	$menu = '';
    	
    	if ($this->db->simple_query("SELECT parentID,childID,web_index FROM lite_b2b.report_module_ci WHERE parentID IN($parentID) AND childID IN($childID) AND parentID NOT IN($childID) "))
		{
		        $parentID = $this->db->query("SELECT parentID,childID,web_index FROM lite_b2b.report_module_ci WHERE parentID IN($parentID) AND childID IN($childID) AND parentID NOT IN($childID) ")->result();
		}
		else
		{
		        return $menu;
		}
    	
    	echo $this->db->last_query();die;
    	// echo var_dump($parentID);die;
    	
    	if(!empty($parentID)){
    		foreach($parentID as $row){

    			$status = $this->db->query("SELECT * FROM lite_b2b.report_module_ci WHERE parentID = '$row->childID' ");

    			if($status->num_rows() > 0 ){
    				$menu .= '<li class="treeview"><a href=""><i class="fa fa-circle-o"></i>'.$row->web_index.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>';
		    		$menu .= $this->child_menu($row->childID);
		    		$menu .= '</li>';
    			}else{
    				$menu .= '<li><a href="#"><i class="fa fa-circle-o"></i>'.$row->web_index.'<span class="pull-right-container"></span></a>';
    			}


	    		
    		}
    	}

    	return $menu;
    }

    public function child_menu($parentID){

    	// echo $this->db->last_query();die;
    	$menu_child = '';

    	if ($this->db->simple_query("SELECT parentID,childID,web_index FROM lite_b2b.report_module_ci WHERE parentID = '$parentID' "))
		{
		        $parentID = $this->db->query("SELECT parentID,childID,web_index FROM lite_b2b.report_module_ci WHERE parentID = '$parentID' ")->result();
		}
		else
		{
		        return $menu_child;
		}

    	if(!empty($parentID)){

    		$menu_child .= '<ul class="treeview-menu">';

	    	foreach($parentID as $row){


	    		$status = $this->db->query("SELECT * FROM lite_b2b.report_module_ci WHERE parentID = '$row->childID' ");

	    		if($status->num_rows() > 0 ){
	    			$menu_child .=  '<li class="treeview"><a href=""><i class="fa fa-circle-o"></i>'.$row->web_index.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>';
		    		$menu_child .= $this->child_menu($row->childID);
		    		$menu_child .= '</li>';
	    		}else{
	    			$menu_child .= '<li><a href="#">'.$row->web_index.'<span class="pull-right-container"></span></a></li>';
	    		}

	    		
	    	}//close foreach

	    	$menu_child .= '</ul>';

    	}


    	return $menu_child;
    }

}
?>