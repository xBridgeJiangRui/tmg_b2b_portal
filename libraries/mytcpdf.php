<?php 

require('tcpdf/tcpdf.php');

class mytcpdf extends Tcpdf
{
	function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
	}
}
?>