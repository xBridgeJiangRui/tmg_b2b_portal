<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class message {

	public function success_message($button , $success_msg, $para1 = null)
	{
		$data = array(
			'status' => true,
			'button' => $button,
			'icons' => '<i class="fa fa-check fa-5x" style="color:green"></i>',
			'msg' => $success_msg,
			'para1' =>$para1,
		);

		echo json_encode($data);
	}

	public function error_message($error_msg, $para1 = null)
	{	
		$CI = &get_instance();

		if($CI->db->error())
		{
			$db_error = $CI->db->error();
			$error = $error_msg.'<br>'.$db_error['message'];
		}
		else
		{
			$error = $error_msg;	
		}

		$data = array(
			'status' => true,
			'button' => '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>',
			'icons' => '<i class="fa fa-times-circle-o fa-5x" style="color:red"></i>',
			'msg' => $error,	
			'para1' =>$para1,	
		);

		echo json_encode($data);
	}


	public function error_message_with_status($error_msg, $para1 = null, $parameter)
	{	
		$CI = &get_instance();

		if($CI->db->error())
		{
			$db_error = $CI->db->error();
			$error = $error_msg.'<br>'.$db_error['message'];
		}
		else
		{
			$error = $error_msg;	
		}

		$data = array(
			'status' => true,
			'button' => '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>',
			'icons' => '<i class="fa fa-times-circle-o fa-5x" style="color:red"></i>',
			'msg' => $error,	
			'para1' =>$para1,
			'parameter' => $parameter	
		);

		echo json_encode($data);
	}

}
