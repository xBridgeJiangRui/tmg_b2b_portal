<?php 
require('fpdi/src/fpdi.php');
require_once('fpdi/src/autoload.php');

class myfpdi extends Fpdi{
	function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
	}
}
?>