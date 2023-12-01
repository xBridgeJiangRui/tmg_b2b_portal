<?php 
require('fpdf/fpdf.php');

class myfpdf extends Fpdf{
	function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
	}
}
?>