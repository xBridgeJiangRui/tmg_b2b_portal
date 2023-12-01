<?php

require(APPPATH.'/libraries/REST_Controller.php');

class File_checking_autocount extends REST_Controller{

    public function __construct()
    {
        parent::__construct();

        // $this->load->model('Main_model');
        $this->load->helper('url');
        $this->load->database();
        date_default_timezone_set("Asia/Kuala_Lumpur");
    } 

    public function Document_autocount_get()
    {
        $doctype = $_REQUEST['doctype'];
        $doctime = $_REQUEST['doctime'];
        $supcode = $_REQUEST['supcode'];
        $refno = $_REQUEST['refno'];

        $input_array = $this->db->query("SELECT * FROM b2b_doc.other_doc_mapping WHERE file_refno = '$refno' AND file_supcode = '$supcode'");
        // print_r($input_array->result());die;
        $supcode = $input_array->row('file_supcode');
        $refno = $input_array->row('file_refno');

        $file_format = $this->db->query("SELECT value FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' and type = 'file_format'")->row('value');
        $time_format_column = $this->db->query("SELECT * FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' AND type = 'time_format_column'")->row('value');
        $time_format = $this->db->query("SELECT * FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' AND type = 'time_format'")->row('value');
        $to_location = $this->db->query("SELECT * FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' AND type = 'to_location'")->row('value');
        $to_location2 = $this->db->query("SELECT DATE_FORMAT('$doctime','%Y-%m') as value")->row('value');
        $cut = explode('_',$file_format);
        $file_name = '';
        $i = 1;
        foreach($cut as $row)
        {
            if($i == $time_format_column)
            {
                $time = $$row;
                $row = $this->db->query("SELECT DATE_FORMAT('$time','$time_format') as xdate")->row('xdate');
                $file_name .= $row.'_';
                // echo $this->db->last_query().'asd'.$row;die;
            }
            else
            {
                $file_name .= $$row.'_';
                // echo $file_name;
            }
            
            $i++;
        }
        $filename = rtrim($file_name,'_');
        // echo $file_name;die;
        // echo $file_format.$doctype.$doctime.$supcode.$refno;die;
        $file = $to_location.'/'.$to_location2.'/'.$filename.'.pdf'; 
        // echo $file;die;
        $filename =$filename.'.pdf'; 
          // header('Location: http://www.example.com/');
        // Header content type 
        header('Content-type: application/pdf'); 
          
        header('Content-Disposition: inline; filename="' . $filename . '"'); 
          
        header('Content-Transfer-Encoding: binary'); 
          
        header('Accept-Ranges: bytes'); 
          
        // Read the file 
        readfile($file);
        die;
     }

    public function Document_autocount_download_get()
    {
        $doctype = $_REQUEST['doctype'];
        $doctime = $_REQUEST['doctime'];
        $supcode = $_REQUEST['supcode'];
        $refno = $_REQUEST['refno'];

        $input_array = $this->db->query("SELECT * FROM b2b_doc.other_doc_mapping WHERE file_refno = '$refno' AND file_supcode = '$supcode'");

        $supcode = $input_array->row('file_supcode');
        $refno = $input_array->row('file_refno');

        $file_format = $this->db->query("SELECT value FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' and type = 'file_format'")->row('value');
        $time_format_column = $this->db->query("SELECT * FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' AND type = 'time_format_column'")->row('value');
        $time_format = $this->db->query("SELECT * FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' AND type = 'time_format'")->row('value');
        $to_location = $this->db->query("SELECT * FROM b2b_doc.b2b_setting_parameter WHERE module = 'autocount' AND type = 'to_location'")->row('value');
        $to_location2 = $this->db->query("SELECT DATE_FORMAT('$doctime','%Y-%m') as value")->row('value');
        $cut = explode('_',$file_format);
        $file_name = '';
        $i = 1;
        foreach($cut as $row)
        {
            if($i == $time_format_column)
            {
                $time = $$row;
                $row = $this->db->query("SELECT DATE_FORMAT('$time','$time_format') as xdate")->row('xdate');
                $file_name .= $row.'_';
                // echo $this->db->last_query().'asd'.$row;die;
            }
            else
            {
                $file_name .= $$row.'_';
            }
            
            $i++;
        }
        $filename = rtrim($file_name,'_');
        // echo $file_name;die;
        // echo $file_format.$doctype.$doctime.$supcode.$refno;die;
        $file = $to_location.'/'.$to_location2.'/'.$filename.'.pdf'; 
        // echo $file;die;
        $filename =$filename.'.pdf'; 
        $b64Doc = chunk_split(base64_encode(file_get_contents($file)));
        echo $b64Doc;die;
     }
}

