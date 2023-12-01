<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

class Menu_loop_all_new extends CI_Controller {
var $data;
  public function __construct()
  {    
      date_default_timezone_set('Asia/Kuala_Lumpur');
      parent::__construct();
      $this->data['name'] = "http://192.168.10.30/report_panda_api";
      $this->load->helper('html');
      $this->data['i'] = 1;
      $acc_guid = $_SESSION["customer_guid"];
      $acc_jasper_url = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$acc_guid'")->row('jasper_url');
      $this->data['acc_jasper_url'] = $acc_jasper_url;
  }

    public function index()
    {
        // echo $this->data['acc_jasper_url'];die;
        $this->getItem();
    }

    public function getItem()
    {     
          $data = '';

          $output = $this->db->query("SELECT *  from report_module_ci WHERE parentID = '0' AND hide = 0 ORDER BY seq");
          // print_r($output->result());die;

          // echo var_dump($this->db->error());die;

          // $childID = $this->db->query('SELECT childID, Description from report_module_ci WHERE parentID = "0" ORDER BY seq')->result();
          $error = $this->db->error();
          // echo $error['message'];die;
          if($error['message'] !=  '')
          {
            $childID = array();
          } 
          else
          {
            $childID = $output->result();
          }
          // print_r($childID);die;
          // $childID = $output->treeview;
          $data .= '<li><a href="#"><i class="glyphicon glyphicon-list-alt"></i> <span>Subscription Report</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>';

          $data .= '<ul class="treeview-menu">';
          foreach($childID as $row){

            // $childIDrow = $this->db->query("SELECT childID, Description from report_module_ci WHERE parentID = '".$row->childID."' ORDER BY seq");

            $output2 = $this->db->query("SELECT * from report_module_ci WHERE parentID = '$row->childID' AND hide = 0 ORDER BY seq");
            // $error2 = $this->db->error(); 
             // echo var_dump($output2->result());die;
            // echo $this->db->last_query();
             if($output2->num_rows() > 0){

                    $data .= '<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i>'.$row->Description.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>';
                    // echo $row->childID;
                      $data .= $this->membersTree($row->childID);
                      $data.= '</li>';

              }
              else
              { 
                    $location = $_SESSION['query_loc'];
                    // $supcode = $_SESSION['query_supcode'];
                    $supcode = $_SESSION['query_consign_supcode'];
                    $ssupcode = str_replace("'", "", $supcode);
                    // $ssupcode = str_replace("'", "", $supcode);
                    $xlocation = explode(",",$location);
                    $xxlocation = $xlocation;
                    $xxxlocation = '';
                    // echo $location;die;
                    foreach($xxlocation as $srow)
                    {
                      $xxxlocation .=  $srow.',';
                    }
                    // echo $xxxlocation;die;
                    $slocation = str_replace("'", "", $xxxlocation);
                    // echo $slocation;die;
                    $sslocation = substr(trim($slocation), 0, -1);

                    $database1 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database1'")->row('reason');
                    $database2 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database2'")->row('reason');
                    $database3 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database3'")->row('reason');
                    $database4 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database4'")->row('reason');

                    $data .= '<li><a style="cursor:pointer" href="'."".site_url('Report_jasper_controller/jasper_new_view')."?link=$row->report_guid".'"><i class="fa fa-bars"></i>'.$row->Description.'<span class="pull-right-container"></span></a>';

              }

          }
          $data .= '</ul></li>';
          echo $data;

    }
    
   public function membersTree($parent_key)
    {
        $data = '';
        // SELECT childID, Description from report_module_ci WHERE parentID= @parentID 
        $output = $this->db->query("SELECT * from report_module_ci WHERE parentID= '$parent_key' AND hide = 0 ORDER BY seq");
             // echo var_dump($result);die;
        $error = $this->db->error();

             // if($output->message == 'success'){
            if($error['message'] == ''){

               
                  $value = $output->result();

                  $data .= '<ul class="treeview-menu">';


                  foreach($value as $row)
                  { 


                          // $childrow = $this->db->query('SELECT childID, Description from report_module_ci WHERE parentID="'.$value->childID.'"');


                          $output2 = $this->db->query("SELECT * from report_module_ci WHERE parentID='$row->childID' AND hide = 0 ORDER BY seq");

                          $error2 = $this->db->error(); 

                          if($output2->num_rows() > 0){
                              $childrow = 1;
                              
                          }else
                          {
                              $childrow = 0;
                              
                          }

                                  if($childrow > 0 )
                                  {
                                      $data .= '<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i>'.$row->Description.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>';
                                      $data .= $this->membersTree($row->childID);
                                      $data.= '</li>';

                                  }
                                  else
                                  {
                                      $location = $_SESSION['query_loc'];
                                      $supcode = $_SESSION['query_consign_supcode'];
                                      $ssupcode = str_replace("'", "", $supcode);
                                      $xlocation = explode(",",$location);
                                      $xxlocation = $xlocation;
                                      $xxxlocation = '';
                                      // echo $location;die;
                                      foreach($xxlocation as $srow)
                                      {
                                        $xxxlocation .=  $srow.',';
                                      }
                                      // echo $xxxlocation;die;
                                      $slocation = str_replace("'", "", $xxxlocation);
                                      // echo $slocation;die;
                                      $sslocation = substr(trim($slocation), 0, -1);

                                      $database1 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database1'")->row('reason');
                                      $database2 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database2'")->row('reason');
                                      $database3 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database3'")->row('reason');
                                      $database4 = $this->db->query("SELECT reason FROM set_setting WHERE module_name = 'jasper_report' AND code = 'database4'")->row('reason');

                                      $data .= '<li><a style="cursor:pointer" href="'."".site_url('Report_jasper_controller/jasper_new_view')."?link=$row->report_guid".'"><i class="fa fa-bars"></i>'.$row->Description.'<span class="pull-right-container"></span></a>';

                                  }

                          // $data .= $this->membersTree($row->childID);

                          // $data .= '</li></ul>';

                  }//close foreach
                  $data .= '</ul>';
             // }else//message success else
             // {
     } 
             // }

        return $data;
    }

}
