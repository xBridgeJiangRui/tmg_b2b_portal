<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class training_user extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('Training_user_model');
        $this->load->library('datatables');
        $this->load->library('Panda_PHPMailer');

    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true)
        {

            $session_id = $_REQUEST['session_id'];
            $supplier_group_guid = $this->session->userdata('supplier_group_guid');
            $name = $this->db->query("SELECT * from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_guid'); 
            $supplier = $this->db->query("SELECT * FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('supplier_guid'); 
       

            
            $sessiondata = array (
               'name' => $name,
               'supplier' =>$supplier,
               'session_id' => $session_id,
             
            );

            $this->session->set_userdata($sessiondata);
            
            $this->load->view('register/training.php', $sessiondata);          
        }
        else
        {
            redirect('#');
        }
    }

    public function training_admin()
    {    
        if($this->session->userdata('loginuser') == true)
        {
            $customer_guid = $_SESSION['customer_guid'];
            $supplier = $this->db->query("SELECT a.* FROM set_supplier a ORDER BY a.supplier_name ASC")->result();
            $retailer = $this->db->query("SELECT DISTINCT c.acc_name FROM  acc c INNER JOIN set_supplier_group a ON c.acc_guid = a.customer_guid WHERE a.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('acc_name');
            $get_new_status = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`, COUNT(b.`acc_name`) AS numbering, IF(b.acc_guid = '$customer_guid' ,'1','2') AS sort FROM lite_b2b.training_user_main a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.acc_guid WHERE a.form_status = 'New' GROUP BY a.`customer_guid` ORDER BY sort ASC , b.acc_name ASC");

            $data = array(
                'supplier' => $supplier,
                'retailer' => $retailer,
                'get_new_status' => $get_new_status,
                'customer_guid' => $customer_guid,   
                );
            $this->load->view('header'); 
            $this->load->view('register/training_admin', $data);  
            $this->load->view('footer' );  
        }
        else
        {
            redirect('#');
        }

    }

    public function training_table()
    {
        ini_set('memory_limit', -1); 
        $session_supcode = $_SESSION['query_supcode'];
        if($session_supcode == '')
        {
          $query1 = "AND f.`supplier_group_name` = ''";
        }
        else
        {
          $query1 = "AND f.`supplier_group_name` IN ($session_supcode)";
        }
        if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
        {
            $columns = array(
                0 => 'training_guid',
                1 => 'training_guid',
                2 => 'training_no',
                3 => 'supplier_name',
                4 => 'acc_name',
                5 => 'memo_type',
                6 => 'comp_email',
                7 => 'acc_no',
                8 => 'part_cnt',
                9 => 'form_status',
                10 => 'create_at',
                11 => 'create_by',
                12 => 'update_at',
                13 => 'update_by',
            );
        }
        else
        {
            $columns = array(
                0 => 'training_guid',
                1 => 'training_guid',
                2 => 'training_no',
                3 => 'supplier_name',
                4 => 'acc_name',
                5 => 'memo_type',
                6 => 'comp_email',
                7 => 'acc_no',
                8 => 'part_cnt',
                9 => 'form_status',
                10 => 'create_at',
                11 => 'create_by',
                12 => 'update_at',
                13 => 'update_by',
            );
        }


        $user_guid = $_SESSION['user_guid'];
        $user_group = $_SESSION['user_group_name'];
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $this->input->post('order');
        $dir = "";
        $customer_guid = $_SESSION['customer_guid'];
        $totalData = $this->Training_user_model->register($customer_guid)->row('numrow');
        $totalFiltered = $totalData;

        $order_query = "";

        if(!empty($order))
        {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];

              $order_query .= $columns[$col]." ".$dir.",";

          }
        }   
        $dir = '';    
        $order_query = rtrim($order_query,',');

        if($_SESSION['user_group_name'] == 'SUPER_ADMIN'  ) 
        {
            $query = "
                SELECT 
                aa.`supplier_info_guid`,
                aa.`training_no`,
                aa.`supplier_name`,
                aa.`acc_name`,
                aa.`comp_email`,
                aa.`acc_no`,
                aa.`create_at`,
                aa.`create_by`,
                aa.`update_at`,
                aa.`update_by`,
                aa.`training_guid`,
                aa.`part_cnt`,
                aa.`customer_guid`,
                aa.`form_status`,
                aa.`memo_type` 
              FROM
                (SELECT 
                  c.`supplier_name`,
                  e.`supplier_info_guid`,
                  a.*,
                  d.* 
                FROM
                  training_user_main a 
                  INNER JOIN acc b 
                    ON b.acc_guid = a.`customer_guid` 
                  LEFT JOIN set_supplier c 
                    ON c.supplier_guid = a.`supplier_guid` 
                  LEFT JOIN set_supplier_info e 
                    ON a.`training_guid` = e.training_guid 
                  LEFT JOIN 
                    (SELECT DISTINCT 
                      training_guid AS training_id,
                      COUNT(`part_name`) AS part_cnt
                    FROM
                      training_user_child 
                    GROUP BY training_guid) d 
                    ON a.`training_guid` = d.training_id
                    WHERE a.customer_guid = '".$_SESSION['customer_guid']."') aa 
                    WHERE aa.customer_guid = '".$_SESSION['customer_guid']."' ";
        }
        else
        {
            $query = "
                SELECT 
                aa.`supplier_info_guid`,
                aa.`training_no`,
                aa.`supplier_name`,
                aa.`acc_name`,
                aa.`comp_email`,
                aa.`acc_no`,
                aa.`create_at`,
                aa.`create_by`,
                aa.`update_at`,
                aa.`update_by`,
                aa.`training_guid`,
                aa.`part_cnt`,
                aa.`customer_guid`,
                aa.`form_status`,
                aa.`memo_type`  
              FROM
                (SELECT 
                  c.`supplier_name`,
                  e.`supplier_info_guid`,
                  a.*,
                  d.* 
                FROM
                  training_user_main a 
                  INNER JOIN acc b 
                    ON b.acc_guid = a.`customer_guid` 
                  LEFT JOIN set_supplier c 
                    ON c.supplier_guid = a.`supplier_guid` 
                  LEFT JOIN set_supplier_info e 
                    ON a.`training_guid` = e.training_guid 
                  INNER JOIN lite_b2b.`set_supplier_group` f
                    ON a.supplier_guid = f.`supplier_guid`
                    AND f.`customer_guid` = '".$_SESSION['customer_guid']."'
                   $query1
                  LEFT JOIN 
                    (SELECT DISTINCT 
                      training_guid AS training_id,
                      COUNT(`part_name`) AS part_cnt
                    FROM
                      training_user_child 
                    GROUP BY training_guid) d 
                    ON a.`training_guid` = d.training_id
                  WHERE a.customer_guid = '".$_SESSION['customer_guid']."') aa 
                    WHERE aa.customer_guid = '".$_SESSION['customer_guid']."' 
                    GROUP BY aa.training_guid";
        }

        $totalData = $this->db->query($query)->num_rows();
        $totalFiltered = $totalData;
        if(empty($this->input->post('search')['value']))
        {
            $posts = $this->Training_user_model->allposts($query,$limit,$start,$order_query,$dir);
            //echo $this->db->last_query(); die;
        }
        else 
        {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->Training_user_model->posts_search($query,$limit,$start,$search,$order_query,$dir);

            $totalFiltered = $this->Training_user_model->posts_search_count($query,$search);
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['training_no'] = $post->training_no;
                $nestedData['supplier_name'] = $post->supplier_name;
                $nestedData['acc_name'] = $post->acc_name;
                $nestedData['comp_email'] = $post->comp_email;
                $nestedData['create_at'] = $post->create_at; 
                $nestedData['create_by'] = $post->create_by;
                $nestedData['part_cnt'] = $post->part_cnt;
                $nestedData['update_at'] = $post->update_at; 
                $nestedData['update_by'] = $post->update_by;
                $nestedData['acc_no'] = $post->acc_no;
                $nestedData['form_status'] = $post->form_status;
                $nestedData['memo_type'] = $post->memo_type;
                $nestedData['training_guid'] = $post->training_guid;
               
               if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN'  ) {

                if($post->form_status == '')
                {
                  $nestedData['action'] = '<a training_guid='.$post->training_guid.' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="training_form_edit?training_guid='.$post->training_guid.'"><i class="glyphicon glyphicon-pencil"></i></a><a training_guid='.$post->training_guid.' id="send_btn" title="SEND" class="btn btn-xs btn-warning" type="button" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" training_guid="'.$post->training_guid.'" training_no="'.$post->training_no.'" supplier_name="'.$post->supplier_name.'" acc_name="'.$post->acc_name.'" comp_email="'.$post->comp_email.'" edit_acc_no="'.$post->acc_no.'" memo_type="'.$post->memo_type.'" style="margin-top:5px;"><i class="fa fa-edit"></i></a><a class="btn btn-xs btn-danger" type="button" id="btn_delete_form" title="DELETE" training_guid="'.$post->training_guid.'" style="margin-top:5px;margin-left:5px;"><i class="glyphicon glyphicon-remove"></i></a>';
                }
                else if(($post->form_status == 'Send') || ($post->form_status == 'Save-Progress') || ($post->form_status == 'Advance'))
                {
                    $nestedData['action'] = '<a training_guid='.$post->training_guid.' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="training_form_edit?training_guid='.$post->training_guid.'"><i class="glyphicon glyphicon-pencil"></i></a><a training_guid='.$post->training_guid.' id="send_btn" title="SEND" class="btn btn-xs btn-warning" type="button" style="margin-left:5px;" ><i class="glyphicon glyphicon-send"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" training_guid="'.$post->training_guid.'" training_no="'.$post->training_no.'" supplier_name="'.$post->supplier_name.'" acc_name="'.$post->acc_name.'" comp_email="'.$post->comp_email.'" edit_acc_no="'.$post->acc_no.'" memo_type="'.$post->memo_type.'" style="margin-top:5px;"><i class="fa fa-edit"></i></a>';
                }
                else
                {
                    $nestedData['action'] = '<a training_guid='.$post->training_guid.' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="training_form_edit?training_guid='.$post->training_guid.'"><i class="glyphicon glyphicon-pencil"></i></a><a class="btn btn-xs btn-info" type="button" id="btn_edit_form" title="EDIT" training_guid="'.$post->training_guid.'" training_no="'.$post->training_no.'" supplier_name="'.$post->supplier_name.'" acc_name="'.$post->acc_name.'" comp_email="'.$post->comp_email.'" edit_acc_no="'.$post->acc_no.'" memo_type="'.$post->memo_type.'" style="margin-left:5px;"><i class="fa fa-edit"></i></a>';
                }
               } 
               else
               {
                    $nestedData['action'] = '<a training_guid='.$post->training_guid.' id="view_ticket" title="FORM" class="btn btn-xs btn-primary" type="button" href="training_form_edit?training_guid='.$post->training_guid.'"><i class="fa fa-eye"></i></a>';
               }

                $data[] = $nestedData;

            }
        }               

        $json_data = array(
                "draw"            => intval($this->input->post('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data   
                );
        
        echo json_encode($json_data); 
    }

    public function send_mail() 
    {
        $training_guid = $this->input->post('training_guid');

        $training_form_details = $this->db->query("SELECT * FROM lite_b2b.training_user_main WHERE training_guid = '$training_guid'");
        $now = $this->db->query("SELECT now() as now")->row('now');
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
        if(($training_form_details->num_rows() == '1'))
        {
          $supplier_guid = $training_form_details->row('supplier_guid');
          $customer_guid = $training_form_details->row('customer_guid');

          $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");
          $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
          $get_logo = $this->db->query("SELECT acc_guid,acc_name,file_path FROM lite_b2b.`acc` WHERE acc_guid = '$customer_guid'");

          $subject = $customer_name->row('acc_name').' B2B Online Training Form';
          $form_name = 'Online Training Form';
          $store_logo = $get_logo->row('file_path');
          $get_pic = substr($store_logo, strrpos($store_logo, '/' )+1);
          $store_logo = 'https://b2b.xbridge.my/asset'.$store_logo.'/'.$get_pic.'.jpg'; // need change path
          $email_name = $supplier_detail->row('supplier_name');
          $email_add = $training_form_details->row('comp_email');
          $acc_no = $training_form_details->row('acc_no');
          $memo_type = $training_form_details->row('memo_type');
          $form_no = $training_form_details->row('training_no');

          if($email_add == '')
          {
            $data = array(
            'para1' => 1,
            'msg' => $form_no.'\nNo Email Address. Please set up first before send.',
            );    
            echo json_encode($data); 
            exit();
          }

          if($acc_no == '')
          {
            $data = array(
            'para1' => 1,
            'msg' => $form_no.'\nNo Vendor Code. Please set up first before send.',
            );    
            echo json_encode($data); 
            exit();
          }

          if($memo_type == '')
          {
            $data = array(
            'para1' => 1,
            'msg' => $form_no.'\nNo Memo Type. Please set up first before send.',
            );    
            echo json_encode($data); 
            exit();
          }
          
          $url = 'https://b2b.xbridge.my/index.php/Supplier_registration/training_form_edit?link='.$training_guid;

          $send_data = array(
          'customer_name' => $customer_name,
          'supplier_detail' => $supplier_detail,
          'get_logo' => $get_logo,
          'store_logo' => $store_logo,
          'url' => $url,
          'subject' => $subject,
          'form_name' => $form_name,
          );

          $bodyContent = $this->load->view('register/supplier_email_template',$send_data,TRUE);
          //echo $bodyContent;die;

          //$email_name = 'jiangrui.goh@pandasoftware.my';
          //$email_add = 'jiangrui.goh@pandasoftware.my';                

          $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','register@xbridge.my');

          $update_data = $this->db->query("UPDATE lite_b2b.training_user_main SET form_status = 'Send', update_at = '$now' , update_by = '$user_id' WHERE training_guid = '$training_guid' AND customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid' ");
        } 

        $error = $this->db->affected_rows();

        if($error > 0){

           $data = array(
            'para1' => 0,
            'msg' => $i.' Record(s) Send Successfully',

            );    
            echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Send Failed.',

            );    
            echo json_encode($data);   
        }

    }

    public function batch_send_process()
    {
      $details = $this->input->post('details');
      $details = json_encode($details);
      $details = json_decode($details);
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $i = 0;
      foreach($details as $row)
      {
        $training_guid = $row->training_guid;
        $training_form_details = $this->db->query("SELECT * FROM lite_b2b.training_user_main WHERE training_guid = '$training_guid'");
        $now = $this->db->query("SELECT now() as now")->row('now');

        if($training_form_details->num_rows() == '1')
        {
          $supplier_guid = $training_form_details->row('supplier_guid');
          $customer_guid = $training_form_details->row('customer_guid');

          $supplier_detail = $this->db->query("SELECT * FROM lite_b2b.set_supplier WHERE supplier_guid = '$supplier_guid'");
          $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
          $get_logo = $this->db->query("SELECT acc_guid,acc_name,file_path FROM lite_b2b.`acc` WHERE acc_guid = '$customer_guid'");

          $subject = $customer_name->row('acc_name').' B2B Online Training Form';
          $form_name = 'Online Training Form';
          $store_logo = $get_logo->row('file_path');
          $get_pic = substr($store_logo, strrpos($store_logo, '/' )+1);
          $store_logo = 'https://b2b.xbridge.my/asset'.$store_logo.'/'.$get_pic.'.jpg'; // need change path
          $email_name = $supplier_detail->row('supplier_name');
          $email_add = $training_form_details->row('comp_email');
          $acc_no = $training_form_details->row('acc_no');
          $memo_type = $training_form_details->row('memo_type');
          $form_no = $training_form_details->row('training_no');

          if($email_add == '')
          {
            $data = array(
            'para1' => 1,
            'msg' => $form_no.'\nNo Email Address. Please set up first before send.',
            );    
            echo json_encode($data); 
            exit();
          }

          if($acc_no == '')
          {
            $data = array(
            'para1' => 1,
            'msg' => $form_no.'\nNo Vendor Code. Please set up first before send.',
            );    
            echo json_encode($data); 
            exit();
          }

          if($memo_type == '')
          {
            $data = array(
            'para1' => 1,
            'msg' => $form_no.'\nNo Memo Type. Please set up first before send.',
            );    
            echo json_encode($data); 
            exit();
          }

          $url = 'https://b2b.xbridge.my/index.php/Supplier_registration/training_form_edit?link='.$training_guid;

          $send_data = array(
          'customer_name' => $customer_name,
          'supplier_detail' => $supplier_detail,
          'get_logo' => $get_logo,
          'store_logo' => $store_logo,
          'url' => $url,
          'subject' => $subject,
          'form_name' => $form_name,
          );

          $bodyContent = $this->load->view('register/supplier_email_template',$send_data,TRUE);
          //echo $bodyContent;die;

          //$email_name = 'jiangrui.goh@pandasoftware.my';
          //$email_add = 'jiangrui.goh@pandasoftware.my';                

          $this->send_mailjet_third_party($email_add, '', $bodyContent, $subject, '','','','register@xbridge.my');

          $update_data = $this->db->query("UPDATE lite_b2b.training_user_main SET form_status = 'Send', update_at = '$now' , update_by = '$user_id' WHERE training_guid = '$training_guid' AND customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid' ");

          $i++;
        } // close register_new        
      } // close foreach
      
      $error = $this->db->affected_rows();

      if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => $i.' Record(s) Send Successfully',

          );    
          echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Send Failed.',

          );    
          echo json_encode($data);   
      }
    }

    public function register_thank()
    {
        $this->load->view('register/header2'); 
        $this->load->view('register/thank');  
    }

    public function transaction()
    {
        $comp_name = $this->input->post('comp_name');
        $memo_type = $this->input->post('memo_type');
        $training_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS training_guid")->row('training_guid');
        $training_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS training_c_guid")->row('training_c_guid');
        $supplier_query = $this->db->query("SELECT a.* FROM set_supplier a WHERE supplier_guid = '$comp_name' ORDER BY a.supplier_name ASC");
        $supplier_guid = $supplier_query->row('supplier_guid');
        $supplier_name = $supplier_query->row('supplier_name');
        $supplier_info_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS supplier_info_guid")->row('supplier_info_guid');
        $active = $this->db->query("SELECT a.isactive FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid  INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'")->row('isactive');
        $supplier = $this->db->query("SELECT a.*,b.* FROM set_supplier a INNER JOIN set_supplier_group b ON a.`supplier_guid` = b.supplier_guid INNER JOIN acc c ON c.acc_guid = b.customer_guid WHERE b.customer_guid ='".$_SESSION['customer_guid']."'  ")->row('supplier_guid');
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
        $session_id = $this->db->query("SELECT supplier_group_guid FROM set_supplier_group a WHERE a.`supplier_guid`= '$supplier_guid'")->row('supplier_group_guid');
        //a.`customer_guid`= '".$_SESSION['customer_guid']." ' AND
        //$re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(training_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM `training_user_main`  WHERE SUBSTRING(training_no, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('re_no');
        $todaydate = date('Ymd');
        $todaydate2 = substr($todaydate, 2);
        $re_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(training_no, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS re_no  FROM lite_b2b.`training_user_main`  WHERE  SUBSTRING(training_no, - 10, 6) = '$todaydate2' ")->row('re_no');
        $training_no = $this->db->query("SELECT concat( '$todaydate2', '$re_no' ) as refno")->row('refno');
        $comp_no = $this->input->post('comp_no');
        $acc_name = $this->input->post('acc_name');
        $acc_no = $this->input->post('acc_no');
        
        $comp_email = $this->input->post('comp_email');
        //$comp_post = $this->input->post('comp_post');
        //$comp_state = $this->input->post('comp_state');
        $create_at = $this->db->query("SELECT now() as now")->row('now');
        $update_at = $this->db->query("SELECT now() as now")->row('now');

        $acc_no = implode(",",$acc_no);
        $acc_no = "".$acc_no."";

        $check_transaction = $this->db->query("SELECT * FROM training_user_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND supplier_guid = '$supplier_guid' ");

        if($check_transaction->num_rows() > 0)
        {
          echo "<script> alert('Error create new transaction due to more than one supplier under the retailer.');</script>";
          echo "<script> document.location='" . base_url() . "index.php/Training_user/register_admin' </script>";
          exit();
        }

        $data = array(
          'supplier_info_guid' => $supplier_info_guid,
          //'supplier_add' => $comp_add,
          //'supplier_postcode' => $comp_post,
          //'supplier_state' => $comp_state,
          'training_guid' => $training_guid
        );

        $this->db->insert('set_supplier_info', $data);

        $data = array(
          'training_guid' => $training_guid,
          'customer_guid' => $_SESSION['customer_guid'],
          'supplier_guid' => $supplier_guid,
          'create_at' => $create_at,
          'create_by' => $user_id,
          'update_at' => $update_at,
          'update_by' => $user_id,
          'isactive' => $active,
          'comp_email' =>$comp_email,
          'training_no' => $training_no,
          'comp_name' => $supplier_name,
          'comp_no' => $comp_no,
          'acc_name' =>$acc_name,
          'acc_no' =>$acc_no,
          'store_code' => $acc_no,
          'isactive' => 1,
          'memo_type' => $memo_type,
        );
         
        $this->db->insert('training_user_main', $data);
        redirect('Training_user/training_admin');              
    }

    public function fetch_reg_no()
    {
      $customer_guid = $_SESSION['customer_guid'];
      $type_val = $this->input->post('type_val');
      $acc_no_array = $this->input->post('acc_no_array');
      $column_add = '';
      if($acc_no_array != '')
      {
        $acc_no_array = implode("','",$acc_no_array);
        $acc_no_array = "'".$acc_no_array."'";
        $column_add = ",IF(a.`code` IN ($acc_no_array) , '1' , '0') AS selected";
      }
      $Code = $this->db->query("SELECT a.reg_no, a.supplier_guid FROM set_supplier a WHERE  a.`supplier_guid` = '$type_val' GROUP BY reg_no");
      $vendor = $this->db->query("SELECT a.`code` AS vendor_code,a.name $column_add FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY a.customer_guid,a.`code` ");
      
      $data = array(
          'Code' => $Code->result(),
          'vendor' => $vendor->result(),
      );

      echo json_encode($data);
    }

    // for edit register admin email address
    public function edit_reg_app()
    {
      $edit_trn_guid = $this->input->post('edit_trn_guid');
      $edit_email = $this->input->post('edit_email');
      $edit_acc_no = $this->input->post('edit_acc_no');
      $edit_acc_no = implode(',',$edit_acc_no);
      $memo_type = $this->input->post('edit_memo_type');

      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');

      $data = array(
          'acc_no' => $edit_acc_no,
          'store_code' => $edit_acc_no,
          'memo_type' => $memo_type,
          'comp_email' => $edit_email,
          'update_at' => $updated_at,
          'update_by' => $user_id,
      );

      $this->db->where('training_guid', $edit_trn_guid);
      $this->db->update('training_user_main', $data);

      $error = $this->db->affected_rows();

      if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => 'Update Successfully',

          );    
          echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error.',

          );    
          echo json_encode($data);   
      }
    }

    public function training_form_edit()
    {   
        if($this->session->userdata('loginuser') == true)
        {   
          $training_guid = $_REQUEST['training_guid'];

          $training = $this->db->query("SELECT a.*, b.* FROM lite_b2b.training_user_main a LEFT JOIN set_supplier_info b ON a.training_guid = b.training_guid WHERE b.`training_guid` = '$training_guid'");

          $training_child_training = $this->db->query("SELECT a.* FROM lite_b2b.training_user_child a WHERE a.`training_guid` = '$training_guid' AND part_type = 'training' ");
          
          $customer_guid = $training->row('customer_guid');          

          $acc_branch = $this->db->query("SELECT a.NAME FROM b2b_summary.`supcus` a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid LIMIT 0, 100");

          $ven_agency_sql = $this->db->query("SELECT aa.*, bb.branch_desc FROM (SELECT a.* FROM acc_branch a INNER JOIN acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid' AND a.branch_code IN (".$_SESSION['query_loc'].") AND a.isactive = '1') aa INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code ORDER BY aa.is_hq DESC, branch_code ASC ");
          
          $get_supp = $this->db->query("SELECT supplier_guid FROM training_user_main b WHERE b.`training_guid` = '$training_guid'");

          $supplier_guid = $get_supp->row('supplier_guid');

          $vendor_code_sql = $this->db->query("SELECT b.`supplier_name`, a.supplier_group_name FROM lite_b2b.set_supplier_group a INNER JOIN lite_b2b.`set_supplier` b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.supplier_guid = '$supplier_guid' GROUP BY  supplier_name,supplier_group_name ");
          
          $add_vendor_code = $this->db->query("SELECT a.`code` AS vendor_code FROM b2b_summary.supcus a WHERE a.customer_guid = '$customer_guid' GROUP BY customer_guid,`code` ");

          $vendor = $training->row('store_code');
          $myArray_1 = explode(',', $vendor);
          $myArray = array_filter($myArray_1); //show vendor code array

          $vendor_remark = $training->row('vendor_code_remark');
          $myArray_2 = explode(',', $vendor_remark);
          $myArray_2 = array_filter($myArray_2); //show vendor code remark array

          // $vendor_remark_edit = $training_child->row('vendor_code_remark');
          // $myArray_3 = explode(',', $vendor_remark_edit);
          // $myArray_3 = array_filter($myArray_3); //show vendor code remark array

          $data = array(
            'supplier_guid' => $supplier_guid,
            'customer_guid' => $customer_guid,
            'training' => $training,
            'training_child_training' => $training_child_training,
            'acc_branch' => $acc_branch,
            'ven_agency_sql' => $ven_agency_sql, // outlet array
            //'vendor_code_sql' => $vendor_code_sql, // not use 
            'myArray' =>$myArray, // Vendor Code (refer to Retailer) array
            'myArray_2' =>$myArray_2, // Vendor Code remark array
            //'myArray_3' =>$myArray_3, // edit Vendor Code remark array
            'add_vendor_code' => $add_vendor_code->result(), // add vendor code
          );

          $this->load->view('header'); 
          $this->load->view('register/training_form_edit', $data);  
          $this->load->view('footer' ); 
        }
        else
        {
            redirect('#');
        } 
    }

    public function participant_tb()
    {
      $training_guid = $this->input->post('training_guid');

      $training_part = $this->db->query("SELECT a.*, b.`customer_guid`,b.form_status FROM lite_b2b.training_user_child a INNER JOIN lite_b2b.training_user_main b ON a.training_guid = b.`training_guid` WHERE a.`training_guid` = '$training_guid' AND part_type = 'training' ORDER BY created_at ASC");

      echo json_encode($training_part->result());
    }

    public function add_part_info()
    {
      $training_guid = $this->input->post('training_guid');
      $customer_guid = $this->input->post('customer_guid');
      $part_name = $this->input->post('part_name');
      $part_ic = $this->input->post('part_ic');
      $part_mobile = $this->input->post('part_mobile');
      $part_email = $this->input->post('part_email');
      $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
      $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $get_supp = $this->db->query("SELECT supplier_guid FROM training_user_main b WHERE b.`training_guid` = '$training_guid'");
      $supplier_guid = $get_supp->row('supplier_guid');
    
      $training_child = $this->db->query("SELECT a.* FROM lite_b2b.training_user_child a WHERE a.`training_guid` = '$training_guid' AND part_type = 'training' GROUP BY a.training_c_guid");

      foreach ($training_child->result() as $key) 
      {
        $check_part_name= $key->part_name; 
        $check_part_ic = $key->part_ic;  
        $check_part_email = $key->part_email; 

        if($part_name == $check_part_name)
        {
          $data = array(
            'para1' => 1,
            'msg' => 'Duplicate Name.',

            );    
            echo json_encode($data); 
            die; 
        }
        
        if($part_ic == $check_part_ic)
        {
          $data = array(
          'para1' => 1,
          'msg' => 'Duplicate IC NO.',

          );    
          echo json_encode($data);  
          die;
        }

        if($part_email == $check_part_email)
        {
          $data = array(
          'para1' => 1,
          'msg' => 'Duplicate Email.',

          );    
          echo json_encode($data);  
          die;
        } 

      }

      $data_1 = array(   
        'update_at' => $updated_at,
        'update_by' => $user_id
      );

      $this->db->where('training_guid', $training_guid);
      $this->db->update('training_user_main', $data_1);

      $data_2 = array(   
        'supplier_guid' => $supplier_guid,
        'training_guid' => $training_guid,
        'training_c_guid' => $c_guid,
        'part_name' => $part_name,
        'part_ic' => $part_ic,
        'part_mobile' => $part_mobile,
        'part_email' => $part_email,
        'isdelete' => 0,
        'part_type' => 'training',
        'created_at' => $updated_at,
        'created_by' => $user_id
      );

      $this->db->insert('training_user_child', $data_2);

      $error = $this->db->affected_rows();

      if($error > 0){

           $data = array(
            'para1' => 0,
            'msg' => 'Add Successfully',

            );    
            echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error.',

          );    
          echo json_encode($data);   
      }
    }

    public function edit_part_info()
    {
      $training_guid = $this->input->post('training_guid');
      $customer_guid = $this->input->post('customer_guid');
      $training_c_guid = $this->input->post('training_c_guid');
      $part_name = $this->input->post('part_name');
      $part_ic = $this->input->post('part_ic');
      $part_mobile = $this->input->post('part_mobile');
      $part_email = $this->input->post('part_email');
      $c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
      $updated_at = $this->db->query("SELECT NOW() as now")->row('now');
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $get_supp = $this->db->query("SELECT supplier_guid FROM training_user_main b WHERE b.`training_guid` = '$training_guid'");
      $supplier_guid = $get_supp->row('supplier_guid');
    
      $training_child = $this->db->query("SELECT a.* FROM lite_b2b.training_user_child a WHERE a.`training_guid` = '$training_guid' AND a.`training_c_guid` != '$training_c_guid' AND part_type = 'training' GROUP BY a.training_c_guid"); 

      foreach ($training_child->result() as $key) 
      {
        $check_part_name= $key->part_name; 
        $check_part_ic = $key->part_ic;  
        $check_part_email = $key->part_email; 

        if($part_name == $check_part_name)
        {
          $data = array(
            'para1' => 1,
            'msg' => 'Duplicate Name.',

            );    
            echo json_encode($data); 
            die; 
        }
        
        if($part_ic == $check_part_ic)
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Duplicate IC NO.',

            );    
            echo json_encode($data);  
            die;
        }

        if($part_email == $check_part_email)
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Duplicate Email.',

            );    
            echo json_encode($data);  
            die;
        } 
      }

      $data_1 = array(   
        'update_at' => $updated_at,
        'update_by' => $user_id
      );

      $this->db->where('training_guid', $training_guid);
      $this->db->update('training_user_main', $data_1);

      $data_2 = array(   
        'part_name' => $part_name,
        'part_ic' => $part_ic,
        'part_mobile' => $part_mobile,
        'part_email' => $part_email,
        'isdelete' => 0,
      );

      $this->db->where('training_c_guid', $training_c_guid);
      $this->db->update('training_user_child', $data_2);

      $error = $this->db->affected_rows();

      if($error > 0){

           $data = array(
            'para1' => 0,
            'msg' => 'Edit Successfully',

            );    
            echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error.',

          );    
          echo json_encode($data);   
      }
    }

    public function active_status()
    {
      $training_guid = $this->input->post('training_guid');
      $training_c_guid = $this->input->post('training_c_guid');
      $isdelete = $this->input->post('isdelete');

        if($training_guid != '' || $training_c_guid != '')
        {
          $delete_training = $this->db->query("DELETE FROM lite_b2b.training_user_child WHERE training_guid = '$training_guid' AND training_c_guid = '$training_c_guid' ");
        }

        $error = $this->db->affected_rows();

        if($error > 0){

             $data = array(
              'para1' => 0,
              'msg' => 'Removed',

              );    
              echo json_encode($data);   
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error.',

            );    
            echo json_encode($data);   
        }
    }

    //edited
    public function training_update() 
    {
      if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
      {
        $training_guid = $_REQUEST['training_guid'];
        $register = $this->db->query("SELECT * FROM lite_b2b.training_user_main a INNER JOIN lite_b2b.set_supplier_info b ON a.training_guid = b.training_guid WHERE a.`training_guid` = '$training_guid' ");
        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');

        $comp_email = $this->input->post('comp_email');
        $supply_outright = $this->input->post('supply_outright'); //new
        $supply_consignment = $this->input->post('supply_consignment'); //new
        $updated_at = $this->db->query("SELECT now() as now")->row('now');

        $save_status = $this->input->post('save_status');

        if($save_status == '1')
        {
          $form_status = 'Save-Progress';
        }
        else
        {
          $form_status = 'Processing';
        }

        $data = array(
          'org_part_email' =>$comp_email,
          'update_at' => $updated_at,
          'update_by' => $user_id,
          'isactive' => 0,
          'form_status' => $form_status
        );
       
        $this->db->where('training_guid', $training_guid);
        $this->db->update('training_user_main', $data);

        $data = array(
          'supply_outright' =>$supply_outright,
          'supply_consignment' =>$supply_consignment,
        );

        $this->db->where('training_guid', $training_guid);
        $this->db->update('set_supplier_info', $data);
    
        if($form_status == 'Processing')
        {
          echo "<script> alert('Submit Successfully.');</script>";
          echo "<script> document.location='" . base_url() . "index.php/Training_user/training_form_edit?training_guid=" .$training_guid. "' </script>";
         
        }
        else
        {
          echo "<script> alert('Save Successfully.');</script>";
          echo "<script> document.location='" . base_url() . "index.php/Training_user/training_form_edit?training_guid=" .$training_guid. "' </script>";
        }
        
      }  
      else
      {
        redirect('login_c');
      }
    }

    public function add_vendor_code()
    {
      // $table_child = $this->input->post('table_name1');
      $table_main = $this->input->post('table_name2');
      $training_guid = $this->input->post('training_guid');
      $customer_guid = $this->input->post('customer_guid');
      $code = $this->input->post('code');

      $register = $this->db->query("SELECT a.`store_code` FROM $table_main a LEFT JOIN lite_b2b.set_supplier_info b ON a.training_guid = b.training_guid WHERE a.`training_guid` = '$training_guid' ");

      $store_code = $register->row('store_code');
      $store_code = explode(',', $store_code);
      $myArray = array_unique(array_merge($store_code,$code));
      $myArray = array_filter($myArray);
      $myArray = implode(',',$myArray);

      $this->db->query("UPDATE $table_main SET store_code = '$myArray' , acc_no = '$myArray' WHERE training_guid = '$training_guid'");

      $error = $this->db->affected_rows();

      if($error > 0)
      {
          $data = array(
          'para1' => 0,
          'msg' => 'Add Successfully',

          );    
          echo json_encode($data);      
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Add Error',

          );  
          echo json_encode($data);        
      }
    }

    //edited
    public function complete_status()
    {
      $training_guid = $this->input->post('training_guid');
      $customer_guid = $this->input->post('customer_guid');
      $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='".$_SESSION['user_guid']."'")->row('user_id');
      $updated_at = $this->db->query("SELECT now() as now")->row('now');
      $check_data = $this->db->query("SELECT * FROM lite_b2b.training_user_main WHERE training_guid = '$training_guid' GROUP BY training_guid");

      $check_child_training = $this->db->query("SELECT COUNT(a.training_guid) AS training_rows FROM lite_b2b.training_user_child a INNER JOIN lite_b2b.training_user_main b ON a.training_guid = b.training_guid WHERE a.training_guid = '$training_guid' AND b.customer_guid = '$customer_guid' AND a.part_type = 'training'")->row('training_rows');
      
      if($check_data->num_rows() != 1)
      {
        $data = array(
          'para1' => 1,
          'msg' => 'Invalid GUID. Please refresh page.',

          );    
          echo json_encode($data);   
          exit();
      }

      $data = array(   
        'update_at' => $updated_at,
        'update_by' => $user_id,
        'form_status' => 'Registered'
      );

      $this->db->where('training_guid', $training_guid);
      $this->db->update('training_user_main', $data);

      $error = $this->db->affected_rows();

      if($error > 0){

         $data = array(
          'para1' => 0,
          'msg' => '\nParticipant User(s) : '.$check_child_training.'\nRegistered Successfully',

          );    
          echo json_encode($data);   
      }
      else
      {   
          $data = array(
          'para1' => 1,
          'msg' => 'Error.',

          );    
          echo json_encode($data);   
      }
    }  

    public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module,$cc_list_string,$pdf,$reply_to)
    {
      // die;
      if($pdf != '' || $pdf != null)
      { 
          $b64Doc = chunk_split(base64_encode(file_get_contents($pdf))); 
          $filename = substr($pdf, strrpos($pdf, '/') + 1);
      }
      else
      {
          $b64Doc = ''; 
      }
      // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
      // echo $b64Doc;die;      
      $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup WHERE type = 'alert_retailer_supplier_setup' LIMIT 1");
      $to_email = $email_add;
      $to_email_name = $email_add;
      $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');

      $replyto = array('Email' => $reply_to,'Name' => $reply_to);
      $from = array('Email' => $from_email->row('sender_email'),'Name' => $from_email->row('sender_name'));
      $to = array('Email' => $to_email,'Name' => $to_email_name);
      $to_array = array($to);

      if($cc_list_string != '' || $cc_list_string != null)
      {
          $test_array = explode(',',$cc_list_string);
          $cc_array=array();
          foreach($test_array as $tarray)
          {
              // echo $tarray->sender_email;
              $cc = array('Email' => $tarray,'Name' => $tarray);
              array_push($cc_array, $cc);
          }
      }
      else
      {
          $cc_array = '';  
      }

      // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
      $bcc_array = array();
      $variable1 = array($variable);
      $variables = array('var1' => $variable1);
      // $variables_array = array($variables);
      $templateid = 1090613;
      $Subject = $email_subject;
      $TextPart = $email_subject;
      $HTMLPart = $bodyContent; 

      if($b64Doc != '')
      {
          $attachment = array('ContentType' => 'application/pdf','Filename' => $filename,'Base64Content' => $b64Doc);
          $attachment1 = array($attachment);
          $attachment_array = array($attachment);            
          $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables,'cc' => $cc_array, 'replyto' =>$replyto,'attachments' => $attachment_array);
      }
      else
      {
          $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables, 'replyto' =>$replyto);
      }
      // $data2 = array($data);
      // $data3 = array('Messages' => $data2);
      // $t = array($t, "Mary", "Peter", "Sally");

      $myJSON = json_encode($data);
      //echo $myJSON;die;
      // die here
      $to_shoot_url = "localhost/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
      $ch = curl_init(); 

      curl_setopt_array($ch, array(
        CURLOPT_URL => $to_shoot_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $myJSON,
        CURLOPT_HTTPHEADER => array(
          "x-api-key: 123456",
          "Content-Type: application/json"
        ),
      ));

      // $to_shoot_url = "localhost/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
      // $ch = curl_init($to_shoot_url); 
      // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "123456" ));
      // curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
      // curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
      // // curl_setopt($ch, CURLOPT_USERPWD, $mailjet_user.":".$mailjet_pass);
      // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
      // curl_setopt($ch,CURLOPT_POSTFIELDS, $myJSON);
      $result = curl_exec($ch);
      $result1 = json_decode($result);
      // print_r($result);die;
      $retry = 0;
      while(curl_errno($ch) == 28 && $retry < 3){
          $response = curl_exec($ch);
          $retry++;
      }

      if(!curl_errno($ch))
      {
          if(isset($result1->Messages[0]))
          {
              $status = $result1->Messages[0]->Status;
          }
          else
          {
              $status = $result1->ErrorMessage;
          }


          if($status == 'success')
          {
              $ereponse = $result1->Messages[0]->To[0]->MessageID;
              $data = array(
                  'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                  'created_by' =>$_SESSION["userid"],
                  'recipient' => $to_email,
                  'sender' => $from_email->row('sender_email'),
                  'subject' => $email_subject,
                  'status' => 'SUCCESS',
                  'respond_message' => $ereponse,
                  'smtp_server' => 'mailjet',
                  'smtp_port' => 'mailjet',
                  'smtp_security' => 'mailjet',
                  );
              $this->db->insert('email_transaction', $data);
              // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
              //redirect('Email_controller/setup');
              if($module != 'alert_notification')
              {
                  // echo json_encode(array(
                  //         'status' => true,
                  //         'message' => 'success',
                  //         'action'=> 'next',
                  //         ));
              };
          }
          else
          {
              $ereponse = $result1->StatusCode.'-'.$result1->ErrorMessage;
              $data = array(
                  'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                  'created_by' =>$_SESSION["userid"],
                  'recipient' => $to_email,
                  'sender' => $from_email->row('sender_email'),
                  'subject' => $email_subject,
                  'status' => 'FAIL',
                  'respond_message' => $ereponse,
                  'smtp_server' => 'mailjet',
                  'smtp_port' => 'mailjet',
                  'smtp_security' => 'mailjet',
                  );
              $this->db->insert('email_transaction', $data);
              // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
              //redirect('Email_controller/setup');
              // if($module != 'alert_notification')
              // {
               // echo json_encode(array(
               //    'status' => false,
               //    'message' => $ereponse,
               //    'action'=> 'retry',
               //    ));
              // };
          }

          curl_close($ch);
      }
      else
      {
              $ereponse = 'Curl error: '.curl_error($ch);

              $data = array(
                  'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                  'created_by' =>$_SESSION["userid"],
                  'recipient' => $to_email,
                  'sender' => $from_email->row('sender_email'),
                  'subject' => $email_subject,
                  'status' => 'FAIL',
                  'respond_message' => $retry.$ereponse,
                  'smtp_server' => 'mailjet',
                  'smtp_port' => 'mailjet',
                  'smtp_security' => 'mailjet',
                  );
              $this->db->insert('email_transaction', $data);
              // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
              //redirect('Email_controller/setup');
              // if($module != 'alert_notification')
              // {
               // echo json_encode(array(
               //    'status' => false,
               //    'message' => $ereponse,
               //    'action'=> 'retry',
               //    ));          
      }         
    }  

    public function remove_online_form()
    {
      $training_guid = $this->input->post('training_guid');
      $table_1 = $this->input->post('table_name1');
      $table_2 = $this->input->post('table_name2');

      if($training_guid == '' || $training_guid == null || $training_guid == 'null')
      {
        $data = array(
        'para1' => 1,
        'msg' => 'Invalid GUID.',

        );    
        echo json_encode($data);  
        exit();
      }

      if($table_1 == '' || $table_1 == null || $table_1 == 'null')
      {
        $data = array(
        'para1' => 1,
        'msg' => 'Invalid Table Name 1.',

        );    
        echo json_encode($data);  
        exit();
      }

      if($table_2 == '' || $table_2 == null || $table_2 == 'null')
      {
        $data = array(
        'para1' => 1,
        'msg' => 'Invalid Table Name 2.',

        );    
        echo json_encode($data);  
        exit();
      }

      $delete_form = $this->db->query("DELETE FROM lite_b2b.$table_1 WHERE training_guid = '$training_guid' ");

      $delete_set_supplier_info = $this->db->query("DELETE FROM lite_b2b.$table_2 WHERE training_guid = '$training_guid' ");

      $error = $this->db->affected_rows();

      if($error > 0){
        $data = array(
         'para1' => 0,
         'msg' => 'Removed Successfully',

         );    
         echo json_encode($data);   
      }
      else
      {   
        $data = array(
        'para1' => 1,
        'msg' => 'Error.',

        );    
        echo json_encode($data);   
      }
    }
}
?>