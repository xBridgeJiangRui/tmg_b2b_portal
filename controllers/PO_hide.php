<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class PO_hide extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->library('Panda_PHPMailer');   
        $this->load->model('General_model');

    }


    public function view_status()
    {   
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $check_loc = $_REQUEST['loc'];
            $check_module = $_SESSION['frommodule'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array=array();

            foreach ($hq_branch_code as $key) {

                array_push($hq_branch_code_array,$key->branch_code);
            }

            if(in_array('VHFSP',$_SESSION['module_code']))
            {
                $hide_url = '<a class="btn btn-app" href="'.site_url('PO_hide/view_status').'?status=HFSP&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=" style="color:#ffad33"><i class="fa fa-edit"></i>View Hide</a>';
            }
            else
            {
                $hide_url = '';
            }

            $data = array(
                        'datatable_url' => site_url('PO_hide/view_table'),
                        'set_admin_code' => $this->db->query("SELECT code,portal_description as reason from  status_setting where type = 'unhide_po_filter' AND isactive = 1 order by code='ALL' desc, code asc
"),
                        'set_code' => $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc"),
                        'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PO_FILTER_STATUS' order by code='ALL' desc, code asc"),
                        'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),

                        'location' => $this->db->query("SELECT DISTINCT branch_code FROM acc_branch AS a INNER JOIN acc_concept AS b ON b.`concept_guid` = a.`concept_guid` WHERE branch_code IN  (".$_SESSION['query_loc'].") and b.`acc_guid` = '".$_SESSION['customer_guid']."' order by branch_code asc "),

                        'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '".$_SESSION['customer_guid']."'"),
                        'hide_url' => $hide_url,
                        'status' => 'HFSP'
                    );  

            $data['check_loc'] = $check_loc;

            $data['hq_branch_code_array'] = $hq_branch_code_array;


            $this->load->view('header');       
            $this->load->view('po/PO_hide_view',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');


        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }//close veiw_status function



    public function view_table()
    {   
        $customer_guid = $this->session->userdata('customer_guid');
        $status = $this->input->post('status');
        $check_loc = $this->input->post('loc');
        //order by refno asc limit 0,10

        $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

        $hq_branch_code_array=array();

        foreach ($hq_branch_code as $key) {

            array_push($hq_branch_code_array,$key->branch_code);
        }

        if(in_array($check_loc, $hq_branch_code_array)) 
        {
            $loc = 'location';
        }
        else
        {
            $loc = "'".$check_loc."'";
        }

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        

        if(!empty($order))
        {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];
          }
        }

        if($dir != "asc" && $dir != "desc")
        {
          $dir = "desc";
        }

        $valid_columns = array( 
            0 => 'refno', 
            1 => 'gr_refno',
            2 => 'location',
            3 => 'scode',
            4 => 'sname',
            5=> 'podate',
            6=> 'expiry_date',
            7=> 'total',
            8=> 'gst_tax_sum',
            9=> 'total_include_tax',
            10=> 'status',
            11=> 'c.portal_description',
          /* 8=> 'checkbox',*/
        ); 

        if(!isset($valid_columns[$col]))
        {
          $order = null;
        }
        else
        {
          $order = $valid_columns[$col];
        }

        if($order !=null)
        {   
          // $this->db->order_by($order, $dir);

          $order_query = "ORDER BY " .$order. "  " .$dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if(!empty($search))
        {
          $x=0;
          foreach($valid_columns as $sterm)
          {
              if($x==0)
              {

                    $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

              }
              else
              {
                  // $this->db->or_like($sterm,$search);

                  $like_second_query .= "OR $sterm LIKE '%".$search."%'";

              }
              $x++;
          }

          $total = $this->db->query("SELECT COUNT(*) as count FROM (SELECT hide_reason,customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode as scode1, sname as sname1, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname from b2b_summary.pomain where customer_guid = '$customer_guid' and location IN ($loc) and status IN ('HFSP') ) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('',gr_refno,'') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '$customer_guid' GROUP BY po_refno) b ON a.refno = b.po_refno LEFT JOIN (SELECT * FROM status_setting WHERE type = 'hide_po_filter' AND common_code = 'HFSP') c ON a.hide_reason = c.code ".$like_first_query.$like_second_query." ")->row('count');

          // echo $this->db->last_query();                 
        }else
        {
        $total = $this->db->query("SELECT COUNT(*) as count FROM (SELECT hide_reason,customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode as scode1, sname as sname1, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname from b2b_summary.pomain where customer_guid = '$customer_guid' and location IN ($loc) and status IN ('HFSP') ) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('',gr_refno,'') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '$customer_guid' GROUP BY po_refno) b ON a.refno = b.po_refno LEFT JOIN (SELECT * FROM status_setting WHERE type = 'hide_po_filter' AND common_code = 'HFSP') c ON a.hide_reason = c.code ")->row('count');
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;

        $sql = "SELECT a.*,b.gr_refno,c.portal_description as hide_reason FROM (SELECT hide_reason,customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode as scode1, sname as sname1, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname,loc_group from b2b_summary.pomain where customer_guid = '$customer_guid' and location IN ($loc) and status IN ('HFSP') ) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('',gr_refno,'') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '$customer_guid' GROUP BY po_refno) b ON a.refno = b.po_refno LEFT JOIN (SELECT * FROM status_setting WHERE type = 'hide_po_filter' AND common_code = 'HFSP') c ON a.hide_reason = c.code ";

        $query = $sql.$like_first_query.$like_second_query.$order_query.$limit_query;

        $query_result = $this->db->query($query);
        // echo $this->db->last_query();die;

        // echo $this->db->last_query();


        $data = array();
        foreach($query_result->result() as $post)
        {

            $nestedData['refno'] = $post->refno;
            $nestedData['gr_refno'] = $post->gr_refno;
            $nestedData['location'] = $post->location;
            $nestedData['code'] = $post->scode;
            $nestedData['name'] = $post->sname;
            $nestedData['podate'] = $post->podate;
            $nestedData['expiry_date'] = $post->expiry_date;
            $nestedData['total'] = "<span class='pull-right'>".number_format($post->total,2)."</span>";
            $nestedData['gst_tax_sum'] = "<span class='pull-right'>".number_format($post->gst_tax_sum,2)."</span>";
            $nestedData['total_include_tax'] = "<span class='pull-right'>".number_format($post->total_include_tax,2)."</span>";
            $nestedData['status'] = $post->status;
            $nestedData['hide_reason'] = $post->hide_reason;

            if(in_array('HFSP',$_SESSION['module_code']) && $status =='' && $this->session->userdata('customer_guid') != '8D5B38E931FA11E79E7E33210BD612D3')
            {
                 $fbutton = "<a href=".site_url('panda_po_2/po_child')."?trans=".$post->refno."&loc=".$check_loc." style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                  ";
            }
            else
            {
                $fbutton = "<a href=".site_url('panda_po_2/po_child')."?trans=".$post->refno."&loc=".$check_loc."&accpt_po_status=".$post->status." style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                ";
            }


            if(in_array('UHFSP',$_SESSION['module_code']))
            {
                  $sbutton = "<a role='button' class='hide_po_modal btn-sm btn-success' style='float:left'
                  refno='".$post->refno."' loc_group='".$post->loc_group."' loc='".$check_loc."''><span class='glyphicon glyphicon-eye-open'></span></a>";
            }
            

            $nestedData['button'] = $fbutton.$sbutton;

            $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'">';

            $data[] = $nestedData;

        }

        // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);


    }//close function view_table



    public function view_table_filter()
    {      
        $customer_guid = $this->session->userdata('customer_guid');
        $status = $this->input->post('status');
        $check_loc = $this->input->post('loc');

        $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

        $hq_branch_code_array=array();

        foreach ($hq_branch_code as $key) {

            array_push($hq_branch_code_array,$key->branch_code);
        }

        if(in_array($check_loc, $hq_branch_code_array)) 
        {
            $loc = 'location';
        }
        else
        {
            $loc = "'".$check_loc."'";
        }

        $frommodule = $this->input->post('frommodule');
        $current_location = $this->input->post('current_location');

        $refno = $this->input->post('po_num');
        $daterange = $this->input->post('daterange');
        $expiry_from = $this->input->post('expiry_from');
        $expiry_to = $this->input->post('expiry_to');

        $period_code = $this->input->post('period_code');

        if($period_code == 'null')
        {
            //$query_period_code = " ";
            $_SESSION['filter_period_code'] = "null";
        }
        else
        {
            //$query_period_code = " and podate like left('$period_code', 7)";
            $_SESSION['filter_period_code'] = $period_code;
        }


        if($daterange != '')
        {
            $daterange1 = explode(' - ', $daterange);
            $daterange_from = date('Y-m-d',strtotime($daterange1[0]));
            $daterange_to = date('Y-m-d',strtotime($daterange1[1]));
        }
        else
        {   //initial idea is to have default 7 days from today
            //$daterange_from = date('Y-m-d', strtotime('-7 days'));
            //$daterange_to = date('Y-m-d');
            $daterange_from = "";
            $daterange_to = "";
        };

        if($expiry_from  == '' && $expiry_to != '')
        {
            $expiry_from == $expiry_to;
        };


        if(!in_array('VGR',$_SESSION['module_code']))
        {
          $module = 'gr_download_child';
        }
        else
        {
          $module = 'gr_child';
        }
        // echo $start;
        

        if($daterange != '')
        {
            $q_doc_from_to = " and podate between '$daterange_from' and '$daterange_to'";    
        }
        else
        {
            $q_doc_from_to = "";
        };



        if($expiry_to != '')
        {
            $q_exp_from_to = " and expiry_date between '$expiry_from' and '$expiry_to'";
        }
        else
        {
            $q_exp_from_to = "";
        };


        // refno == 
        if($refno == '')
        {
            $q_refno = "";
        }
        elseif($refno == 'ALL')
        {
            $q_refno = "";
        }
        else
        {
            $q_refno = " and refno = '$refno'";
        };



        //status option
        if($status == '')
        {
            $check_status = '';
        }
        else
        {
            $check_status = $status;
        };


        $setsession = array(
        // 'from_other' => '1',
        'check_status' => $check_status,
        );
        $this->session->set_userdata($setsession);


        //filter_via_periodcode cz i lazy add in parameter so and due date too soon 2019-08-13
        if(isset($_SESSION['filter_period_code']) &&  $_SESSION['filter_period_code'] != "" )
        {
            $period_code = $_SESSION['filter_period_code'];

            $q_period_code = " and left(podate,7)  = '$period_code'";

        }
        else
        {
            //$_SESSION['filter_period_code'] = "";
            $period_code = " "; 
            $q_period_code = " ";
        };



        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        

        if(!empty($order))
        {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];
          }
        }

        if($dir != "asc" && $dir != "desc")
        {
          $dir = "desc";
        }

        $valid_columns = array( 
            0 => 'refno', 
            1 => 'gr_refno',
            2 => 'location',
            3 => 'scode',
            4 => 'sname',
            5=> 'podate',
            6=> 'expiry_date',
            7=> 'total',
            8=> 'gst_tax_sum',
            9=> 'total_include_tax',
            10=> 'status',
          /* 8=> 'checkbox',*/
        ); 

        if(!isset($valid_columns[$col]))
        {
          $order = null;
        }
        else
        {
          $order = $valid_columns[$col];
        }

        if($order !=null)
        {   
          // $this->db->order_by($order, $dir);

          $order_query = "ORDER BY " .$order. "  " .$dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if(!empty($search))
        {
          $x=0;
          foreach($valid_columns as $sterm)
          {
              if($x==0)
              {

                    $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

              }
              else
              {
                  // $this->db->or_like($sterm,$search);

                  $like_second_query .= "OR $sterm LIKE '%".$search."%'";

              }
              $x++;
          }


          if($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_CLERK' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_NO_HIDE' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_FINANCE')
            {
                 $total = $this->db->query("SELECT COUNT(*) as count FROM (SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode as scode1, sname as sname1, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname from b2b_summary.pomain where customer_guid = '".$customer_guid."' and location IN ($loc)  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ('$check_status') $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$loc."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$customer_guid."' GROUP BY po_refno) b ON a.refno = b.po_refno ".$like_first_query.$like_second_query." ")->row('count');
                 // echo $this->db->last_query();
                  
            }
            else
            {
                $total = $this->db->query("SELECT COUNT(*) as count FROM (SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode as scode1, sname as sname1 from b2b_summary.pomain where customer_guid = '".$customer_guid."'and scode IN (".$_SESSION['query_supcode'].")  and location IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno and status IN ('$check_status')  $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$loc."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$customer_guid."' GROUP BY po_refno) b ON a.RefNo = b.po_refno ".$like_first_query.$like_second_query." ")->row('count');

            };

          // echo $this->db->last_query();                 
        }else
        {
            if($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_CLERK' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_NO_HIDE' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_FINANCE')
            {
                 $total = $this->db->query("SELECT COUNT(*) as count FROM (SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode as scode1, sname as sname1, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname from b2b_summary.pomain where customer_guid = '".$customer_guid."' and location IN ($loc)  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ('$check_status') $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$loc."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$customer_guid."' GROUP BY po_refno) b ON a.refno = b.po_refno ")->row('count');
                 // echo $this->db->last_query();
                  
            }
            else
            {
                $total = $this->db->query("SELECT COUNT(*) as count FROM (SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode as scode1, sname as sname1 from b2b_summary.pomain where customer_guid = '".$customer_guid."'and scode IN (".$_SESSION['query_supcode'].")  and location IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno and status IN ('$check_status')  $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$loc."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$customer_guid."' GROUP BY po_refno) b ON a.RefNo = b.po_refno ")->row('count');

            };
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;

        if($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_CLERK' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_NO_HIDE' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_FINANCE')
        {
             $sql = "SELECT a.*,b.gr_refno FROM (SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode as scode1, sname as sname1, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname from b2b_summary.pomain where customer_guid = '".$customer_guid."' and location IN ($loc)  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ('$check_status') $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$loc."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$customer_guid."' GROUP BY po_refno) b ON a.refno = b.po_refno ";
             // echo $this->db->last_query();
              
        }
        else
        {
            $sql = "SELECT * FROM (SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode as scode1, sname as sname1 from b2b_summary.pomain where customer_guid = '".$customer_guid."'and scode IN (".$_SESSION['query_supcode'].")  and location IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno and status IN ('$check_status')  $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$loc."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$customer_guid."' GROUP BY po_refno) b ON a.RefNo = b.po_refno ";

        };

        $query = $sql.$like_first_query.$like_second_query.$order_query.$limit_query;

        $query_result = $this->db->query($query);

        $data = array();
        foreach($query_result->result() as $post)
        {

            $nestedData['refno'] = $post->refno;
            $nestedData['gr_refno'] = $post->gr_refno;
            $nestedData['location'] = $post->location;
            $nestedData['code'] = $post->scode;
            $nestedData['name'] = $post->sname;
            $nestedData['podate'] = $post->podate;
            $nestedData['expiry_date'] = $post->expiry_date;
            $nestedData['total'] = "<span class='pull-right'>".number_format($post->total,2)."</span>";
            $nestedData['gst_tax_sum'] = "<span class='pull-right'>".number_format($post->gst_tax_sum,2)."</span>";
            $nestedData['total_include_tax'] = "<span class='pull-right'>".number_format($post->total_include_tax,2)."</span>";
            $nestedData['status'] = $post->status;

            if(in_array('HFSP',$_SESSION['module_code']) && $status =='' && $this->session->userdata('customer_guid') != '8D5B38E931FA11E79E7E33210BD612D3')
            {
                 $fbutton = "<a href=".site_url('panda_po_2/po_child')."?trans=".$post->refno."&loc=".$check_loc." style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                  ";
            }
            else
            {
                $fbutton = "<a href=".site_url('panda_po_2/po_child')."?trans=".$post->refno."&loc=".$check_loc."&accpt_po_status=".$post->status." style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                ";
            }


            if(in_array('UHFSP',$_SESSION['module_code']))
            {
                  $sbutton = "<a role='button' class='hide_po_modal btn-sm btn-success' style='float:left'
                  refno='".$post->refno."' loc='".$check_loc."''><span class='glyphicon glyphicon-eye-open'></span></a>";
            }
            

            $nestedData['button'] = $fbutton.$sbutton;
            $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'">';

            $data[] = $nestedData;

        }

        // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);



    }//close filter table function



    public function status_flat()
    {
        $refno = $this->input->post('refno');
        $reason = $this->input->post('reason');

        echo $reason.'<br>';
        echo $refno;die;
    }//close status_flat




    public function check_supplier()
    {   
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {

            $RefNo = $this->input->post('RefNo');

            $customer_guid = $this->session->userdata('customer_guid');

            $userlog = $this->db->query("SELECT created_by,MAX(created_at) as created_at FROM `lite_b2b`.`userlog` WHERE `section` = 'po' AND field = '$RefNo' AND module_group_guid = '$customer_guid' LIMIT 1");


            $data = array(
                'created_by' => $userlog->row('created_by'),
                'created_at' => $userlog->row('created_at')
            );

            echo json_encode($data);

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }//close veiw_status function



}
?>