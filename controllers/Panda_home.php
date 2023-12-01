<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start(); //we need to call PHP's session object to access it through CI
class panda_home extends CI_Controller {

  function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
        /*
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $session_data = $this->session->userdata('customers');
        $data['customer'] = $session_data['customer'];*/
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {

            if ($_SERVER['HTTPS'] !== "on") 
                {
                $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];   
                header("Location: $url");
                } 

            $backend_db = $this->db->query("SELECT b2b_database FROM lite_b2b.acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('b2b_database');

            $get_latest_sales_datetime = $this->db->query("SELECT updateddatetime FROM $backend_db.sum_daily_sku_sales ORDER BY updateddatetime DESC LIMIT 1")->row('updateddatetime');

            $check_announcement = $this->db->query("SELECT * from announcement where customer_guid = '".$_SESSION['customer_guid']."' and posted= '1' and now() >= publish_at and acknowledgement = 0 order by publish_at desc, created_at desc limit 1");

                if($check_announcement->num_rows() < 1)
                {
                    $announcement = $this->db->query("SELECT 'Welcome' as title, 'No New announcement at this moment' as content, curdate() as docdate ");
                   // echo $this->db->last_query();die;
                }   
                else
                {
                    $announcement = $check_announcement;
                };

               $check_rows_supplier = $this->db->query("SELECT b.`supplier_guid`, b.`supplier_name` FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.set_supplier b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.user_guid = '".$_SESSION['user_guid']."' AND a.customer_guid = '".$_SESSION['customer_guid']."' GROUP BY a.supplier_guid , a.`customer_guid`"); // check supplier how many for that users

            $row_supplier = $check_rows_supplier->num_rows();

            if($row_supplier == 0)
            {
                $row_supplier = '1';
            }
            else
            {
                $row_supplier = $check_rows_supplier->num_rows();
            }

            $check_announcement_acknowledgement = $this->db->query("SELECT * FROM (SELECT a.*, '0' AS need_docs, COUNT(c.announcement_guid) AS counting FROM lite_b2b.announcement AS a LEFT JOIN (SELECT * FROM lite_b2b.announcement_child WHERE user_guid = '".$_SESSION['user_guid']."') AS b ON a.`announcement_guid` = b.`announcement_guid` LEFT JOIN (SELECT * FROM lite_b2b.announcement_child_supplier WHERE user_guid = '".$_SESSION['user_guid']."') AS c ON a.`announcement_guid` = c.`announcement_guid` WHERE a.`customer_guid` = '".$_SESSION['customer_guid']."' AND a.`acknowledgement` = '1' AND a.posted = '1' AND b.announcement_guid_c IS NULL UNION ALL SELECT * FROM (SELECT a.*, '1' AS need_docs, COUNT(b.announcement_guid) AS counting FROM lite_b2b.announcement AS a LEFT JOIN (SELECT * FROM lite_b2b.announcement_child_supplier WHERE user_guid = '".$_SESSION['user_guid']."') AS b ON a.`announcement_guid` = b.`announcement_guid` WHERE a.`customer_guid` = '".$_SESSION['customer_guid']."' AND a.`acknowledgement` = '1' AND a.posted = '1' AND a.`upload_docs` = '1' GROUP BY a.announcement_guid) aa ) aaa WHERE aaa.counting != '$row_supplier' AND aaa.announcement_guid IS NOT NULL GROUP BY aaa.announcement_guid ORDER BY aaa.`posted_at` ASC limit 10 ");
                // print_r($check_announcement_acknowledgement->result());die;

                if($check_announcement_acknowledgement->num_rows() >= 1)
                { 
                    $show_panel = '1';
                    $mandatory = $check_announcement_acknowledgement->row('mandatory');
                    $pdf = $check_announcement_acknowledgement->row('pdf_status');
                    $show_announcement_sidebar = $this->db->query("SELECT a.* ,b.created_at AS acknowledged_at FROM lite_b2b.announcement AS a LEFT JOIN  (SELECT * FROM lite_b2b.announcement_child  WHERE  user_guid = '".$_SESSION['user_guid']."' ) AS b ON a.`announcement_guid` = b.`announcement_guid` WHERE a.`customer_guid` = '".$_SESSION['customer_guid']."' AND a.`pdf_status` = '1' AND a.posted = '1' ORDER BY a.`publish_at` DESC ");
                    
                }
                else
                {
                    $show_panel = '0';
                    $mandatory = $check_announcement_acknowledgement->row('mandatory');
                    $pdf = $check_announcement_acknowledgement->row('pdf_status');
                    $show_announcement_sidebar =  $this->db->query("SELECT a.* ,b.created_at AS acknowledged_at FROM lite_b2b.announcement AS a LEFT JOIN  (SELECT * FROM lite_b2b.announcement_child  WHERE  user_guid = '".$_SESSION['user_guid']."' ) AS b ON a.`announcement_guid` = b.`announcement_guid` WHERE a.`customer_guid` = '".$_SESSION['customer_guid']."' AND a.`pdf_status` = '1' AND a.posted = '1' ORDER BY a.`publish_at` DESC ");
                }           

                if(in_array('VNMA',$_SESSION['module_code']))
                {
                    $notification = $this->db->query("SELECT b.*, b.query_admin as query FROM notification_modal_subscribe a INNER JOIN notification_modal b ON a.notification_guid = b.notification_guid WHERE b.isactive = 1 AND a.customer_guid = '".$this->session->userdata('customer_guid')."' ORDER BY b.seq ASC ");
                }
                else
                {
                    $notification = $this->db->query("SELECT b.*, b.query_user as query FROM notification_modal_subscribe a INNER JOIN notification_modal b ON a.notification_guid = b.notification_guid WHERE b.isactive = 1 AND a.customer_guid = '".$this->session->userdata('customer_guid')."' ORDER BY b.seq ASC ");
                }

                $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');

                if($pdf == 1)
                {
                    $announcement_guid = $check_announcement_acknowledgement->row('announcement_guid');
                    $file_name = $this->db->query("SELECT content FROM announcement WHERE announcement_guid = '$announcement_guid'");
                    // print_r (explode("-+0+-",$file_name->row('content')));
                    $file_name_array = explode("-+0+-",$file_name->row('content'));
                    // print_r($file_name_array);die;
                    
                    // $data2 = array();
                    // $i = 1;
                    // foreach($file_name_array as $row)
                    // {
                    //     $aa = 'file_name'.$i;
                    //     // $$aa = $row.'<br>';
                    //     $data2[''.$$aa.''] = $row;
                    //     $i++;
                    // }
                    // $count_array = count($file_name_array);
                    // // echo $count_array;
                    // print_r($data2);
                    // die;
                    // for($x=1;$x<=$count_array;$x++)
                    // {
                    //     $bb = 'file_name'.$x;
                    //     echo $$bb;
                    // }
                    // die;
                }
                else
                {
                    $file_name_array = array();
                }

                // print_r($file_name->result());die;
               
                // $filename_1 = base_url($virtual_path.'/acceptance_form/one.pdf');
                // $filename_2 = base_url($virtual_path.'/acceptance_form/two.pdf');

                if(in_array('VNMA',$_SESSION['module_code']))
                {
                    $notification = $this->db->query("SELECT b.*, b.query_admin as query FROM notification_modal_subscribe a INNER JOIN notification_modal b ON a.notification_guid = b.notification_guid WHERE b.isactive = 1 AND a.customer_guid = '".$this->session->userdata('customer_guid')."' ORDER BY b.seq ASC ");
                }
                else
                {
                    $notification = $this->db->query("SELECT b.*, b.query_user as query FROM notification_modal_subscribe a INNER JOIN notification_modal b ON a.notification_guid = b.notification_guid WHERE b.isactive = 1 AND a.customer_guid = '".$this->session->userdata('customer_guid')."' ORDER BY b.seq ASC ");
                }

                
                
                $data = array (
                      'userid' => $_SESSION['userid'],
                      'customer' => $_SESSION['customer_guid'],
                      'customer_name' => $this->db->query("SELECT * FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'"),
                      'announcement' => $announcement,
                      'show_announcement_sidebar' => $show_announcement_sidebar, 
                      'check_announcement_acknowledgement' => $check_announcement_acknowledgement,     
                      'notification' => $notification,
                      'mandatory' => $mandatory,
                      'pdf' => $pdf,
                      'virtual_path' => base_url($virtual_path),
                      'file_name_array' => $file_name_array,
                      'notification' => $notification,
                      'session_guid' => $_SESSION['customer_guid'], 
                      'latest_sales_datetime' => $get_latest_sales_datetime                               
                );

                $this->load->view('header');
                $this->load->view('panda_home_view',$data);
                $this->load->view('panda_home_modal',$data);
                $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
  }
}

?>