<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_log extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $data = array(
                'user' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'User' ORDER BY created_at DESC"),
                'user_module' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'User Module' ORDER BY created_at DESC"),
                'module' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'Module' ORDER BY created_at DESC"),
                'module_group' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'Module Group' ORDER BY created_at DESC"),
                'user_group' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'User Group' ORDER BY created_at DESC"),
                'acc_branch' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'Account Branch' ORDER BY created_at DESC"),
                'acc_concept' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'Account Concept' ORDER BY created_at DESC"),
                'acc_branch_grp' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'Account Branch Group' ORDER BY created_at DESC"),
                'acc' => $this->db->query("SELECT * FROM panda_b2b.userlog WHERE section = 'Account' ORDER BY created_at DESC"),
                );

            $this->load->view('header');
            $this->load->view('user_log', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('Login_c/index');
        }
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect('Login_c/index');
    }



    public function logs()
    {

        $section = $this->input->post('section');
        $start = $this->input->post('start');
        $end = $this->input->post('end');
        $loc = $this->input->post('loc');

        $text = '';
        $user_guid = $this->session->userdata('user_guid');
        $customer_guid = $this->session->userdata('customer_guid');
        $database = 'lite_b2b';
        $database2 = 'b2b_summary';


        if($section == 'dashboard')
        {
            $logs = $this->db->query("SELECT * FROM $database2.pomain ORDER BY IssueStamp DESC LIMIT $start, $end ");

            if($logs->num_rows() > 0)
            {
                foreach($logs->result() as $row)
                {   
                    $date_start = $row->IssueStamp;

                    $different = $this->db->query("SELECT DATEDIFF(NOW(), '$date_start') as different")->row('different');


                    if($different >= 365)
                    {   
                        $different = $different/365;
                        $color = 'danger';
                        $date = (int)$different.' years ago';
                    }
                    elseif($different >= 30)
                    {   
                        $different = $different/30;

                        $color = 'danger';
                        $date = (int)$different.' months ago';
                    }
                    elseif($different > 7)
                    {
                        $color = 'warning';
                        $date = $different.' days ago';
                    }
                    elseif($different > 0)
                    {
                        $color = 'success';
                        $date = $different.' days ago';
                    }
                    else
                    {   
                        $different_check = $this->db->query("SELECT DATE_FORMAT(NOW(),'%d') as xcurrent_date, DATE_FORMAT('$date_start','%d') as compare_date");

                        $different = $this->db->query("SELECT DATE_FORMAT((TIMEDIFF(NOW(), '$date_start')), '%H') as diff_hour, DATE_FORMAT((TIMEDIFF(NOW(), '$date_start')), '%i') as diff_minute, DATE_FORMAT((TIMEDIFF(NOW(), '$date_start')), '%s') as diff_second");

                        if($different->row('diff_hour') > 0)
                        {
                            $color = 'warning';
                            $date = (int)$different->row('diff_hour').' hours ago';
                        }
                        elseif($different->row('diff_minute') > 0)
                        {
                            $color = 'info';
                            $date = (int)$different->row('diff_minute').' mins ago';
                        }
                        else
                        {
                            $color = 'default" style="background-color:#605ca8; color:white;"';
                            $date = (int)$different->row('diff_second').' seconds ago';
                        }


                    }

                    $text .= '<li>';

                    $text .= '<span class="text">'.$row->RefNo.'</span>';

                    $text .= '<small class="label label-'.$color.'"><i class="fa fa-clock-o"></i> '.$date.'</small>';

                    $text .= '</li>';

                }//close foreach
            }
            else
            {
                $text = '';
            }//close dashboard elase

        }//close dashboard



        if($section == 'po')
        {   
            $user_guid = $this->session->userdata('user_guid');
            $customer_guid = $this->session->userdata('customer_guid');
            $query_supcode = $this->session->userdata('query_supcode');

            if(($query_supcode == '') || ($query_supcode == null))
            {
                $text = '';

                $data = array(
                    'logs' => $text
                );

                echo json_encode($data);
                exit();
            }

            $check_sup_code_array = $this->db->query("SELECT * FROM $database.set_supplier_user_relationship a INNER JOIN $database.set_supplier_group b ON a.supplier_group_guid = b.supplier_group_guid AND a.customer_guid = b.customer_guid WHERE a.user_guid = '$user_guid' AND a.customer_guid = '$customer_guid'");

            $check_sup_code = '';
            foreach($check_sup_code_array->result() as $row)
            {
                $check_sup_code .= "'".$row->supplier_group_name."',";
            }
            $check_sup_code_string = rtrim($check_sup_code, ",");
            // echo $check_sup_code_string;die;

           // $logs = $this->db->query("SELECT *,r3.action as status FROM (SELECT r2.* FROM (SELECT b.* FROM $database.set_supplier_user_relationship a INNER JOIN $database.set_supplier_user_relationship b ON a.supplier_group_guid = b.supplier_group_guid AND a.customer_guid = b.customer_guid WHERE a.user_guid = '$user_guid' AND a.customer_guid = '$customer_guid' GROUP BY b.user_guid) r INNER JOIN $database.supplier_movement r2 ON r.user_guid = r2.user_guid AND r.customer_guid = r2.customer_guid WHERE r2.created_at > DATE_ADD(NOW(), INTERVAL - 50 DAY) AND r2.module IN ('panda_po_2') ORDER BY r2.value DESC, r2.action = 'accepted_po' DESC, r2.action ASC) r3 GROUP BY r3.value ORDER BY r3.created_at DESC LIMIT $start, $end");
        
           $mapped_loc = $this->session->userdata('query_loc');

           $logs = $this->db->query("SELECT r.*,r.action as status FROM (SELECT * FROM (SELECT r2.* FROM (SELECT b.* FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.supplier_group_guid = b.supplier_group_guid AND a.customer_guid = b.customer_guid WHERE a.user_guid = '$user_guid' AND a.customer_guid = '$customer_guid' GROUP BY b.user_guid) r INNER JOIN lite_b2b.supplier_movement r2 ON r.user_guid = r2.user_guid AND r.customer_guid = r2.customer_guid WHERE r2.created_at > DATE_ADD(NOW(), INTERVAL - 7 DAY) AND r2.module IN ('panda_po_2') ORDER BY r2.value DESC, r2.action = 'accepted_po' DESC, r2.action ASC) r3 GROUP BY r3.value) r INNER JOIN $database2.pomain rr ON r.value = rr.refno WHERE rr.customer_guid = '$customer_guid' AND rr.SCode IN ($check_sup_code_string) AND rr.loc_group IN ($mapped_loc) ORDER BY r.created_at DESC LIMIT $start, $end");
           // $haha = $this->db->last_query();
           // echo $haha;die;

            



            if($logs->num_rows() > 0)
            {
                foreach($logs->result() as $row)
                {   
                    $status = $row->status;

                    if($status == 'viewed')
                    {
                        $color = 'info';
                        $link = "href=".site_url('panda_po_2/po_child')."?trans=".$row->value."&loc=".$loc."&accpt_po_status=".$row->status." ";
                    }
                    elseif($status == 'printed')
                    {
                        $color = 'primary';
                        $link = "href=".site_url('panda_po_2/po_child')."?trans=".$row->value."&loc=".$loc."&accpt_po_status=".$row->status." ";
                    }
                    elseif($status == 'accepted')
                    {
                        $color = 'success';
                        $link = "href=".site_url('panda_po_2/po_child')."?trans=".$row->value."&loc=".$loc."&accpt_po_status=".$row->status." ";
                    }
                    elseif($status == 'rejected')
                    {
                        $color = 'danger';
                        $link = "href=".site_url('panda_po_2/po_child')."?trans=".$row->value."&loc=".$loc."&accpt_po_status=".$row->status." ";
                    }
                    else
                    {
                        $color = 'default';
                        $link = "href=".site_url('panda_po_2/po_child')."?trans=".$row->value."&loc=".$loc."&accpt_po_status=".$row->status." ";
                    }



                    $date_start = $row->created_at;

                    $different = $this->db->query("SELECT DATEDIFF(NOW(), '$date_start') as different")->row('different');


                    if($different >= 365)
                    {   
                        $different = $different/365;
                        $date_color = 'color:red;';
                        $date = (int)$different.' years ago';
                    }
                    elseif($different >= 30)
                    {   
                        $different = $different/30;

                        $date_color = 'color:red;';
                        $date = (int)$different.' months ago';
                    }
                    elseif($different > 7)
                    {
                        $date_color = 'color:#f39c12;';//warning
                        $date = $different.' days ago';
                    }
                    elseif($different > 0)
                    {
                        $date_color = 'color:#00a65a;';
                        $date = $different.' days ago';
                    }
                    else
                    {   
                        $different_check = $this->db->query("SELECT DATE_FORMAT(NOW(),'%d') as xcurrent_date, DATE_FORMAT('$date_start','%d') as compare_date");

                        $different = $this->db->query("SELECT DATE_FORMAT((TIMEDIFF(NOW(), '$date_start')), '%H') as diff_hour, DATE_FORMAT((TIMEDIFF(NOW(), '$date_start')), '%i') as diff_minute, DATE_FORMAT((TIMEDIFF(NOW(), '$date_start')), '%s') as diff_second");

                        if($different->row('diff_hour') > 0)
                        {
                            $date_color = 'color:#f39c12;';//warning
                            $date = (int)$different->row('diff_hour').' hours ago';
                        }
                        elseif($different->row('diff_minute') > 0)
                        {
                            $date_color = 'color:#00c0ef;';//info
                            $date = (int)$different->row('diff_minute').' mins ago';
                        }
                        else
                        {
                            $date_color = 'color:#605ca8';//purple
                            $date = (int)$different->row('diff_second').' seconds ago';
                        }


                    }


                    $text .= '<li class="item"><div class="product-info">';

                    // $text .= '<span class="text">'.$row->value.'</span>';
                    $text .= '<a '.$link.' class="product-title">'.$row->value.'</a>';

                    $text .= '<span class="product-description label label-'.$color.'" style="font-size:11px;">'.$row->status.' </span> <br><small style="'.$date_color.'" ><i class="fa fa-clock-o"></i> '.$date.'</small>';
                    // $text .= '<a class="label label-'.$color.'" '.$link.' >'.$row->status.'</a> <span class="label label-'.$date_color.'" ><i class="fa fa-clock-o"></i> '.$date.'</span>';

                    $text .= '</div></li>';




                }//close foreach
            }
            else
            {
                $text = '';
            }//close dashboard elase

        }//close dashboard



        $data = array(
            'logs' => $text
            // 'haha' =>$haha
        );


        echo json_encode($data);

    }//close logs




}
