<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

class Menu_loop_user extends CI_Controller {
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
        $this->getItem();
    }

    public function getItem()
    {     
          $data = '';

          $output = $this->db->query("SELECT *  from report_module_ci WHERE parentID = '0' AND hide = 0 ORDER BY seq ASC");
          

          
          // // $this->db->query("SELECT * FROM report_module_ci WHERE childID IN('ee')");
          // $try3 = 'ee';
          // $output2 = array();
          // foreach($try as $try3)
          // {
          //   echo '<br>'.$try3.'<br>'.'****';
          //   for ($i = 1; ; $i++) {
          //       $try2 = $this->db->query("SELECT * FROM report_module_ci WHERE childID IN('$try3')");
          //       if ($try2->row('parentID') == '') {
          //           break;
          //       }
          //       echo $try2->row('childID').$try2->row('parentID').'<br>';
          //       $try3 = $try2->row('parentID');
          //       $try4 = $try2->row('childID');
          //       // $sub=array("haha => parentID"," haha2 => childID");
          //       // array_push($sub,"chemistry","English");//It will push new entries to the end of array
                
          //       // $output['one'] .= $try3;
          //       // $output['two'] .= $try4;
          //   }
          //   $output2[] = array("xparentID" => $try3, "xchildID" => $try4);
          // }
          // // print_r($output2);
          // // echo $try4.$try3;
          // // echo var_dump($this->db->error());die;
          // foreach($output2 as $row2)
          // {
          //   echo '1'.'<br>';
          //   echo '***'.$row2['xparentID'];
          //   echo '***'.$row2['xchildID'];
          // }
          // die;
          // $childID = $this->db->query('SELECT childID, Description from report_module_ci WHERE parentID = "0" ORDER BY seq')->result();
          // print_r($childID);die;
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
          $data .= '<li><a href="#"><i class="glyphicon glyphicon-list-alt"></i> <span>Subscribtion Report</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>';
          // print_r($childID);
          $data .= '<ul class="treeview-menu">';
          foreach($childID as $row){
            // echo $row->childID;die;
            // $childIDrow = $this->db->query("SELECT childID, Description from report_module_ci WHERE parentID = '".$row->childID."' ORDER BY seq");

            $output2 = $this->db->query("SELECT * from report_module_ci WHERE parentID = '$row->childID' AND hide = 0 ORDER BY seq ASC");

            // echo var_dump($output2->result());
            // $error2 = $this->db->error(); 
             // echo var_dump($output2->result());die;
             if($output2->num_rows() > 0){
                    // $data2 = $this->membersTree2($row->childID);
                    // echo $data2;
                    // if($data2 == 1)
                    // {
                    //   $data .= '';
                    // }
                    // else
                    // {
                    $xuser_guid = $_SESSION['user_guid'];
                    $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                    $xxinput = '';
                    $xinput = array();
                    foreach($input->result() as $xrow1)
                    {
                      // echo $xrow1->report_guid.'<br>';
                      $xxinput .= "'".$xrow1->report_guid."',";
                      $xinput[] .= $xrow1->report_guid;
                      // echo $xxinput.'<br>';
                    }
                    $ainput = substr(trim($xxinput), 0, -1);
                    // echo $ainput;
                    // $haha = "'aa'";
                    $getarray = $this->get_user_report_childID($ainput);
                    // echo $getarray;die;
                    $arraydata = substr(trim($getarray), 0, -1);
                    // echo $arraydata;die;
                    $arraydata2 = explode(",",$arraydata);
                    // print_r($arraydata2);die;
                    // $data9  = array("cc","bb","cc","cc");
                    if(in_array($row->childID,$arraydata2))
                    {

                    $data .= '<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i>'.$row->Description.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>';
                    // echo $row->childID;
                    // $data2 = $this->membersTree2($row->childID);

                    $data2 = $this->db->query("SELECT * from report_module_ci WHERE parentID= '$row->childID' AND hide = 0 ORDER BY seq ASC");
                    // print_r($data2->result());die;
                    // echo $data2;
                    $data.= '<ul class="treeview-menu">';
                    foreach($data2->result() as $row3)
                    {
                      $data3 = $this->membersTree2($row->childID,$row3->childID);
                      // echo '**'.$data3;
                      if($data3 == 1)
                      {
                        $data .= '';
                      }
                      else
                      {
                        // echo '---';
                        // echo $row->childID.$row3->childID;
                        $data .= $this->membersTree3($row->childID,$row3->childID);
                      }
                    }
                    $data .="</ul>";

                      $data.= '</li>';
                    }
                    else
                    {
                      $data .= '';
                    }
                    // }

              }
              else
              { 
                    $xuser_guid = $_SESSION['user_guid'];
                    $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                    $xinput = array();
                    foreach($input->result() as $xrow)
                    {
                      // echo $xrow->report_guid;
                      $xinput[] .= $xrow->report_guid;
                    }

                    if (in_array($row->childID, $xinput)) 
                    {
                        $location = $_SESSION['query_loc'];
                        $supcode = $_SESSION['query_supcode'];
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

                        $data .= '<li><a style="cursor:pointer" onclick="preview('."'".$this->data['acc_jasper_url']."$row->jasper_report_url&location="."$sslocation"."&supcode="."$ssupcode"."&web_module=".$database1."&db_backend=".$database2."&db_member=".$database3."&db_frontend=".$database4."&$row->jasper_report_folder'".')"><i class="fa fa-bars"></i>'.$row->Description.'<span class="pull-right-container"></span></a>';
                    }
                    else
                    {
                        // $data .= '<li><a style="cursor:pointer" onclick="preview('."'$row->jasper_report_url'".')"><i class="fa fa-close"></i>No report Subscribe<span class="pull-right-container"></span></a>';
                        // $data.= '</li>';
                      $data .= '';
                    }

              }

          }
          $data .= '</ul></li>';
          echo $data;

    }
  
    public function get_user_report_childID($subscription){

      $data2 = '';

      $haha = explode(",",$subscription);
      // print_r($haha);die;
      foreach($haha as $row)
      {
        if($row == '' || $row == null)
        {
          $query = $this->db->query("SELECT parentID,childID FROM report_module_ci WHERE childID IN('$row') AND hide = 0 ORDER BY seq ASC")->result();
          // echo $this->db->last_query();
        }
        else
        {
          $query = $this->db->query("SELECT parentID,childID FROM report_module_ci WHERE childID IN($row) AND hide = 0 ORDER BY seq ASC")->result();
          // echo $this->db->last_query();
        }

        if(!empty($query)){
          foreach($query as $row){
          // echo $this->db->last_query();
          // echo "--'".$row->childID."',".'<br>';
          $data2 .= $row->childID.",";
          $data5 = "'".$row->parentID."',";
          $data6 = substr(trim($data5), 0, -1);
          $data2 .= $this->get_user_report_childID($data6);
          
          }
        }
      }

      return $data2;
      
    }

   public function membersTree($parent_key)
    {
        $data = '';
        // SELECT childID, Description from report_module_ci WHERE parentID= @parentID 
        $output = $this->db->query("SELECT * from report_module_ci WHERE parentID= '$parent_key' AND hide = 0 ORDER BY seq ");
             // echo var_dump($result);die;
        $error = $this->db->error();

             // if($output->message == 'success'){
            if($error['message'] == ''){

               
                  $value = $output->result();

                  $data .= '<ul class="treeview-menu">';


                  foreach($value as $row)
                  { 


                          // $childrow = $this->db->query('SELECT childID, Description from report_module_ci WHERE parentID="'.$value->childID.'"');

                          // echo '    '.$row->childID;
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
                                          $xuser_guid = $_SESSION['user_guid'];
                                          $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                          $xxinput = '';
                                          foreach($input->result() as $xrow1)
                                          {
                                            // echo $xrow1->report_guid.'<br>';
                                            $xxinput .= "'".$xrow1->report_guid."',";
                                            // echo $xxinput.'<br>';
                                          }
                                          $ainput = substr(trim($xxinput), 0, -1);

                                          // $haha = '"cc"';
                                          $output3 = $this->db->query("SELECT * from report_module_ci WHERE parentID='$row->childID' AND childID IN($ainput) AND hide = 0 ORDER BY seq");
                                          if ($output3->num_rows() > 0) 
                                          {
                                            $data .= '<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i>'.$row->Description.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>';
                                            $data .= $this->membersTree($row->childID);
                                            $data.= '</li>';  
                                          }
                                          else
                                          {
                                            $xuser_guid = $_SESSION['user_guid'];
                                                $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                                $xxinput = '';
                                                $xinput = array();
                                                foreach($input->result() as $xrow1)
                                                {
                                                  // echo $xrow1->report_guid.'<br>';
                                                  $xxinput .= "'".$xrow1->report_guid."',";
                                                  $xinput[] .= $xrow1->report_guid;
                                                  // echo $xxinput.'<br>';
                                                }
                                                $ainput = substr(trim($xxinput), 0, -1);
                                                // echo $ainput;
                                                // $haha = "'aa'";
                                                $getarray = $this->get_user_report_childID($ainput);
                                                // echo $getarray;die;
                                                $arraydata = substr(trim($getarray), 0, -1);
                                                // echo $arraydata;die;
                                                $arraydata2 = explode(",",$arraydata);
                                                if (in_array($row->childID, $arraydata2)) 
                                                {
                                                $data .='<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i>'.$row->Description.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>';
                                                  $data .= $this->membersTree($row->childID);
                                                  $data.= '</li>';
                                            }
                                            else
                                            {
                                              $data .= '';
                                            } 
                                          }
                                  }
                                  else
                                  {
                                      $xuser_guid = $_SESSION['user_guid'];
                                      $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                      $xinput = array();
                                      foreach($input->result() as $xrow)
                                      {
                                        // echo $xrow->report_guid;
                                        $xinput[] .= $xrow->report_guid;
                                      }

                                      // print_r($input);
                                      $xinput = array();
                                      foreach($input->result() as $xrow)
                                      {
                                        // echo $xrow->report_guid;
                                        $xinput[] .= $xrow->report_guid;
                                      }
                                      // print_r($xinput);die;
                                      if (in_array($row->childID, $xinput)) 
                                      {
                                          $location = $_SESSION['query_loc'];
                                          $supcode = $_SESSION['query_supcode'];
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

                                          $data .= '<li><a style="cursor:pointer" onclick="preview('."'".$this->data['acc_jasper_url']."$row->jasper_report_url&location="."$sslocation"."&supcode="."$ssupcode"."&web_module=".$database1."&db_backend=".$database2."&db_member=".$database3."&db_frontend=".$database4."&$row->jasper_report_folder'".')"><i class="fa fa-bars"></i>'.$row->Description.'<span class="pull-right-container"></span></a>';

                                      }
                                      else
                                      {
                                        $data .= '';
                                        // '<li><a style="cursor:pointer" onclick="preview('."'$row->jasper_report_url'".')"><i class="fa fa-close"></i>No report Subscribe<span class="pull-right-container"></span></a>';
                                        // $status = 1;

                                      }
                                      

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

       public function membersTree3($parent_key,$xchildID)
    {
        $data = '';
        // SELECT childID, Description from report_module_ci WHERE parentID= @parentID 
        $output = $this->db->query("SELECT * from report_module_ci WHERE parentID= '$parent_key' AND childID = '$xchildID' AND hide = 0 ORDER BY seq ");
        // echo '############'.$parent_key.$xchildID;
             // echo var_dump($output->result());die;
        $error = $this->db->error();

             // if($output->message == 'success'){
            if($error['message'] == ''){

               
                  $value = $output->result();

                  // $data .= '<ul class="treeview-menu">';


                  foreach($value as $row)
                  { 

                          // echo $row->childID;
                          // $childrow = $this->db->query('SELECT childID, Description from report_module_ci WHERE parentID="'.$value->childID.'"');


                          $output2 = $this->db->query("SELECT * from report_module_ci WHERE parentID='$row->childID' AND hide = 0 ORDER BY seq");
                          // echo $this->db->last_query();
                          // print_r($output2->result());
                          $error2 = $this->db->error(); 

                          if($output2->num_rows() > 0){
                              $childrow = 1;
                              
                          }else
                          {
                              $childrow = 0;
                              
                          }

                                  if($childrow > 0 )
                                  {

                                          $xuser_guid = $_SESSION['user_guid'];
                                          $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                          $xxinput = '';
                                          foreach($input->result() as $xrow1)
                                          {
                                            // echo $xrow1->report_guid.'<br>';
                                            $xxinput .= "'".$xrow1->report_guid."',";
                                            // echo $xxinput.'<br>';
                                          }
                                          $ainput = substr(trim($xxinput), 0, -1);

                                          // $haha = '"cc"';
                                          $output3 = $this->db->query("SELECT * from report_module_ci WHERE parentID='$row->childID' AND childID IN($ainput) AND hide = 0 ORDER BY seq");
                                          if ($output3->num_rows() > 0) 
                                          {
                                            $data .= '<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i>'.$row->Description.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>';
                                            $data .= $this->membersTree($row->childID);
                                            $data.= '</li>';  
                                          }
                                          else
                                          {
                                              $xuser_guid = $_SESSION['user_guid'];
                                              $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                              $xinput = array();
                                              foreach($input->result() as $xrow)
                                              {
                                                // echo $xrow->report_guid;
                                                $xinput[] .= $xrow->report_guid;
                                              }

                                              if (in_array($row->childID, $xinput)) 
                                              {
                                                  $location = $_SESSION['query_loc'];
                                                  $supcode = $_SESSION['query_supcode'];
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

                                                  $data .= '<li><a style="cursor:pointer" onclick="preview('."'".$this->data['acc_jasper_url']."$row->jasper_report_url&location="."$sslocation"."&supcode="."$ssupcode"."&web_module=".$database1."&db_backend=".$database2."&db_member=".$database3."&db_frontend=".$database4."&$row->jasper_report_folder'".')"><i class="fa fa-bars"></i>'.$row->Description.'<span class="pull-right-container"></span></a>';

                                              }
                                              else
                                              {
                                                $xuser_guid = $_SESSION['user_guid'];
                                                $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                                $xxinput = '';
                                                $xinput = array();
                                                foreach($input->result() as $xrow1)
                                                {
                                                  // echo $xrow1->report_guid.'<br>';
                                                  $xxinput .= "'".$xrow1->report_guid."',";
                                                  $xinput[] .= $xrow1->report_guid;
                                                  // echo $xxinput.'<br>';
                                                }
                                                $ainput = substr(trim($xxinput), 0, -1);
                                                // echo $ainput;
                                                // $haha = "'aa'";
                                                $getarray = $this->get_user_report_childID($ainput);
                                                // echo $getarray;die;
                                                $arraydata = substr(trim($getarray), 0, -1);
                                                // echo $arraydata;die;
                                                $arraydata2 = explode(",",$arraydata);
                                                if (in_array($row->childID, $arraydata2)) 
                                                {
                                                  $data .= '<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i>'.$row->Description.'<span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span></a>';
                                                  $data .= $this->membersTree($row->childID);
                                                  $data.= '</li>';
                                                }
                                                else
                                                {
                                                  $data .= '';
                                                  // '<li><a style="cursor:pointer" onclick="preview('."'$row->jasper_report_url'".')"><i class="fa fa-close"></i>No report Subscribe<span class="pull-right-container"></span></a>';
                                                  // $status = 1;

                                                }
                                                // '<li><a style="cursor:pointer" onclick="preview('."'$row->jasper_report_url'".')"><i class="fa fa-close"></i>No report Subscribe<span class="pull-right-container"></span></a>';
                                                // $status = 1;
                                              }



                                          }
                                  }
                                  else
                                  {
                                      $xuser_guid = $_SESSION['user_guid'];
                                      $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                      $xinput = array();
                                      foreach($input->result() as $xrow)
                                      {
                                        // echo $xrow->report_guid;
                                        $xinput[] .= $xrow->report_guid;
                                      }

                                      if (in_array($row->childID, $xinput)) 
                                      {
                                          $location = $_SESSION['query_loc'];
                                          $supcode = $_SESSION['query_supcode'];
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

                                          $data .= '<li><a style="cursor:pointer" onclick="preview('."'".$this->data['acc_jasper_url']."$row->jasper_report_url&location="."$sslocation"."&ssupcode="."$supcode"."&web_module=".$database1."&db_backend=".$database2."&db_member=".$database3."&db_frontend=".$database4."&$row->jasper_report_folder'".')"><i class="fa fa-bars"></i>'.$row->Description.'<span class="pull-right-container"></span></a>';

                                      }
                                      else
                                      {
                                        $data .= '';
                                        // '<li><a style="cursor:pointer" onclick="preview('."'$row->jasper_report_url'".')"><i class="fa fa-close"></i>No report Subscribe<span class="pull-right-container"></span></a>';
                                        // $status = 1;

                                      }
                                      

                                  }

                          // $data .= $this->membersTree($row->childID);

                          // $data .= '</li></ul>';

                  }//close foreach
                  // $data .= '</ul>';
             // }else//message success else
             // {
     } 
             // }

        return $data;
    }

   public function membersTree2($parent_key,$xchildID)
    {
        $data = '';
        // SELECT childID, Description from report_module_ci WHERE parentID= @parentID 
        $output = $this->db->query("SELECT * from report_module_ci WHERE parentID= '$parent_key' AND childID = '$xchildID' AND hide = 0 ORDER BY seq ");
             // echo var_dump($result);die;
        $error = $this->db->error();

             // if($output->message == 'success'){
            if($error['message'] == ''){

               
                  $value = $output->result();


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
                                          $data = $this->membersTree3($row->childID,$xchildID);
                                  }
                                  else
                                  {
                                      $xuser_guid = $_SESSION['user_guid'];
                                      $input = $this->db->query("SELECT report_guid FROM user_subscribe_report WHERE user_guid = '$xuser_guid'");

                                      $xinput = array();
                                      foreach($input->result() as $xrow)
                                      {
                                        // echo $xrow->report_guid;
                                        $xinput[] .= $xrow->report_guid;
                                      }

                                      if (in_array($row->childID, $xinput)) 
                                      {
                                        $data = 0;
                                      }
                                      else
                                      {
                                        $data = 1;//no report

                                      }
                                      

                                  }

                          // $data .= $this->membersTree($row->childID);

                          // $data .= '</li></ul>';

                  }//close foreach
             // }else//message success else
             // {
     } 
             // }
// echo $this->db->last_query();die;
        return $data;
    }    

}
