<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class dashboard2 extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->General_model->check_router();
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            if ($_SERVER['HTTPS'] !== "on") {
                $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                header("Location: $url");
            }
            $announcement_guid = '';
            $location = $_SESSION['location'];
            $dashboard_num_days_data = $this->db->query("SELECT * FROM  acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('dashboard_num_days_data');

            $iquery_loc = $_SESSION['query_loc'];

            if ($iquery_loc == null || $iquery_loc == '') {
                $iquery_loc = "''";
            }

            if (!in_array('IAVA', $_SESSION['module_code'])) {
                $check_outstanding_pomain = $this->db->query("SELECT count(refno) as count_pomain from b2b_summary.pomain_info where status = '' and customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") and podate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() AND in_kind = '0'");

                //echo $this->db->last_query(); die;

                $check_grn =  $this->db->query("SELECT COUNT(refno) AS count_doc FROM b2b_summary.grmain_info WHERE STATUS = '' AND customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN (" . $_SESSION['query_loc'] . ") AND supplier_code IN (" . $_SESSION['query_supcode'] . ") AND grdate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() AND in_kind = '0'");

                // $check_grda =  $this->db->query("SELECT count(refno) as count_doc from b2b_summary.pomain where status = 'rejected' and customer_guid = '".$_SESSION['customer_guid']."' and loc_group in (".$_SESSION['query_loc'].") and supplier_code in (".$_SESSION['query_supcode'].") and podate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() ");

                $check_grda =  $this->db->query("SELECT COUNT(a.refno) AS count_doc FROM b2b_summary.grmain_info AS a INNER JOIN (SELECT * FROM b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY refno) AS b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE b.status = '' AND b.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.loc_group IN (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ")  AND a.grdate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() AND a.in_kind = '0'");

                $no_respond =  $this->db->query("SELECT count(refno) as count_pomain from b2b_summary.pomain_info where status = '' and customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") and podate < CURDATE() - INTERVAL $dashboard_num_days_data DAY and CURDATE() ");

                /* current alert include
                    - grmain header proposed [x]
                    - grmain reject item [ ]
                    - return reject proposed cost [ ]
                */

                //$alert = $this->db->query("SELECT COUNT(refno) as count_alert FROM b2b_summary.grmain_proposed  WHERE posted = '1' and customer_guid = '".$_SESSION['customer_guid']."' and location in (".$_SESSION['query_loc'].") and code in (".$_SESSION['query_supcode']."); ")->row('count_alert');

                $check_strb = $this->db->query("SELECT COUNT(batch_no) AS count_doc FROM b2b_summary.`dbnote_batch` WHERE `status` = '0' AND loc_group IN (" . $_SESSION['query_loc'] . ")  AND customer_guid = '" . $_SESSION['customer_guid'] . "' AND sup_code in (" . $_SESSION['query_supcode'] . ") AND doc_date BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE()");


            } else {
                $check_outstanding_pomain = $this->db->query("SELECT count(refno) as count_pomain from b2b_summary.pomain_info where status = '' and customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in ($iquery_loc)  and podate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() AND in_kind = '0'");


                $check_grn =  $this->db->query("SELECT COUNT(refno) AS count_doc 
                    FROM b2b_summary.grmain_info WHERE STATUS = '' 
                    AND customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($iquery_loc)  
                    AND grdate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() AND in_kind = '0'");

                // $check_grda =  $this->db->query("SELECT count(refno) as count_doc from b2b_summary.pomain where status = 'rejected' and customer_guid = '".$_SESSION['customer_guid']."' and loc_group in ($iquery_loc) and podate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() ");

                $check_grda =  $this->db->query("SELECT COUNT(a.refno) AS count_doc FROM b2b_summary.grmain_info AS a INNER JOIN (SELECT * FROM b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY refno) AS b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE b.status = '' AND b.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.loc_group IN ($iquery_loc) AND a.grdate BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE() AND a.in_kind = '0'");

                $no_respond =  $this->db->query("SELECT count(refno) as count_pomain from b2b_summary.pomain_info where status = '' and customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in ($iquery_loc) and podate < CURDATE() - INTERVAL $dashboard_num_days_data DAY and CURDATE() ");

                $check_strb = $this->db->query("SELECT COUNT(batch_no) AS count_doc FROM b2b_summary.`dbnote_batch` WHERE `status` = '0' and loc_group in ($iquery_loc)  AND customer_guid = '" . $_SESSION['customer_guid'] . "' AND doc_date BETWEEN CURDATE() - INTERVAL $dashboard_num_days_data DAY AND CURDATE()");

                /* current alert include
                    - grmain header proposed [x]
                    - grmain reject item [ ]
                    - return reject proposed cost [ ]
                */

                //$this->panda->set_global_variable();

                // $alert = $this->db->query("SELECT COUNT(refno) as count_alert  FROM b2b_summary.grmain_proposed  WHERE posted = '0' and customer_guid = '" . $_SESSION['customer_guid'] . "' and location in (SELECT branch_code from query_loc_" . $_SESSION['user_guid'] . ")  ")->row('count_alert');

                //echo $this->db->last_query();die;
            };

            $check_announcement = $this->db->query("SELECT * from announcement where customer_guid = '" . $_SESSION['customer_guid'] . "' and posted= '1' and now() >= publish_at and acknowledgement = 0 order by publish_at desc, created_at desc limit 1");

            if ($check_announcement->num_rows() < 1) {
                $announcement = $this->db->query("SELECT 'Welcome' as title, 'No New announcement at this moment' as content, curdate() as docdate ");
                // echo $this->db->last_query();die;
            } else {
                $announcement = $check_announcement;
            };

            // check if acknowledgement need to be alerted

            $check_rows_supplier = $this->db->query("SELECT b.`supplier_guid`, b.`supplier_name` FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.set_supplier b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.user_guid = '" . $_SESSION['user_guid'] . "' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY a.supplier_guid , a.`customer_guid`"); // check supplier how many for that users

            $row_supplier = $check_rows_supplier->num_rows();

            if ($row_supplier == 0) {
                $row_supplier = '1';
            } else {
                $row_supplier = $check_rows_supplier->num_rows();
            }

            $check_announcement_acknowledgement = $this->db->query("SELECT * FROM (SELECT a.*, '0' AS need_docs, COUNT(c.announcement_guid) AS counting FROM lite_b2b.announcement AS a LEFT JOIN (SELECT * FROM lite_b2b.announcement_child WHERE user_guid = '" . $_SESSION['user_guid'] . "') AS b ON a.`announcement_guid` = b.`announcement_guid` LEFT JOIN (SELECT * FROM lite_b2b.announcement_child_supplier WHERE user_guid = '" . $_SESSION['user_guid'] . "') AS c ON a.`announcement_guid` = c.`announcement_guid` WHERE a.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.`acknowledgement` = '1' AND a.posted = '1' AND b.announcement_guid_c IS NULL GROUP BY a.announcement_guid UNION ALL SELECT * FROM (SELECT a.*, '1' AS need_docs, COUNT(b.announcement_guid) AS counting FROM lite_b2b.announcement AS a LEFT JOIN (SELECT * FROM lite_b2b.announcement_child_supplier WHERE user_guid = '" . $_SESSION['user_guid'] . "') AS b ON a.`announcement_guid` = b.`announcement_guid` WHERE a.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.`acknowledgement` = '1' AND a.posted = '1' AND a.`upload_docs` = '1' GROUP BY a.announcement_guid) aa ) aaa WHERE aaa.counting != '$row_supplier' AND aaa.announcement_guid IS NOT NULL GROUP BY aaa.announcement_guid ORDER BY aaa.`posted_at` ASC limit 10 ");

            if($user_guid = '7BA14C79BDDB11EBB0C4000D3AA2838A')
            {   
                //echo $this->db->last_query(); die;
            }
            // print_r($check_announcement_acknowledgement->result());die;

            if ($check_announcement_acknowledgement->num_rows() >= 1) {
                $show_panel = '1';
                $mandatory = $check_announcement_acknowledgement->row('mandatory');
                $pdf = $check_announcement_acknowledgement->row('pdf_status');
                $show_announcement_sidebar = $this->db->query("SELECT a.* ,b.created_at AS acknowledged_at FROM lite_b2b.announcement AS a LEFT JOIN  (SELECT * FROM lite_b2b.announcement_child  WHERE  user_guid = '" . $_SESSION['user_guid'] . "' ) AS b ON a.`announcement_guid` = b.`announcement_guid` WHERE a.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.`pdf_status` = '1' AND a.posted = '1' ORDER BY a.`publish_at` DESC ");
            } else {
                $show_panel = '0';
                $mandatory = $check_announcement_acknowledgement->row('mandatory');
                $pdf = $check_announcement_acknowledgement->row('pdf_status');
                $show_announcement_sidebar =  $this->db->query("SELECT a.* ,b.created_at AS acknowledged_at FROM lite_b2b.announcement AS a LEFT JOIN  (SELECT * FROM lite_b2b.announcement_child  WHERE  user_guid = '" . $_SESSION['user_guid'] . "' ) AS b ON a.`announcement_guid` = b.`announcement_guid` WHERE a.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.`pdf_status` = '1' AND a.posted = '1' ORDER BY a.`publish_at` DESC ");
            }
            // echo $this->db->last_query();die;

            $redirect_pomain = site_url('dashboard/redirect_from_dashboard?mode=b2b_po&loc=HQ');
            $redirect_grmain = site_url('dashboard/redirect_from_dashboard?mode=b2b_gr&loc=HQ');
            $redirect_grmain_download = site_url('dashboard/redirect_from_dashboard?mode=b2b_gr_download&loc=HQ');
            $redirect_grda  = site_url('dashboard/redirect_from_dashboard?mode=b2b_grda&loc=HQ');
            $redirect_strb  = site_url('dashboard/redirect_from_dashboard?mode=b2b_return_collection&loc=HQ');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            if ($pdf == 1) {
                $announcement_guid = $check_announcement_acknowledgement->row('announcement_guid');
                $file_name = $this->db->query("SELECT content FROM announcement WHERE announcement_guid = '$announcement_guid'");
                // print_r (explode("-+0+-",$file_name->row('content')));
                $file_name_array = explode("-+0+-", $file_name->row('content'));
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
            } else {
                $file_name_array = array();
            }

            // print_r($file_name->result());die;

            // $filename_1 = base_url($virtual_path.'/acceptance_form/one.pdf');
            // $filename_2 = base_url($virtual_path.'/acceptance_form/two.pdf');

            if (in_array('VNMA', $_SESSION['module_code'])) {
                $notification = $this->db->query("SELECT b.*, b.query_admin as query FROM notification_modal_subscribe a INNER JOIN notification_modal b ON a.notification_guid = b.notification_guid WHERE b.isactive = 1 AND a.customer_guid = '" . $this->session->userdata('customer_guid') . "' ORDER BY b.seq ASC ");
            } else {
                $notification = $this->db->query("SELECT b.*, b.query_user as query FROM notification_modal_subscribe a INNER JOIN notification_modal b ON a.notification_guid = b.notification_guid WHERE b.isactive = 1 AND a.customer_guid = '" . $this->session->userdata('customer_guid') . "' ORDER BY b.seq ASC ");
            }

            $week_sql = '';
            $week_implode = array(); 

            for ($i = 7; $i >= 1; $i--) {

                if (!in_array('IAVA', $_SESSION['module_code'])) {
                    $week_implode[] .= "SELECT COUNT(*) AS Count FROM b2b_summary.pomain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") AND in_kind = '0' AND PODate = (CURRENT_DATE - INTERVAL $i DAY) ";
                }else{
                    $week_implode[] .= "SELECT COUNT(*) AS Count FROM b2b_summary.pomain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") AND in_kind = '0' AND PODate = (CURRENT_DATE - INTERVAL $i DAY) ";
                }
            }

            if ($week_implode) {
                $week_sql .= " " . implode(" UNION ALL ", $week_implode) . "";
            }   

            $last_week_order = $this->db->query($week_sql)->result();

            $month_sql = '';
            $month_implode = array();

            $current_month = date('m');
            $current_year = date('Y');
            $day_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);

            for ($i = $day_in_month; $i >= 1; $i--) {

                $filter_date = $current_year.'-'.$current_month.'-'.$i;
                $filter_date = date("Y-m-d", strtotime ($filter_date));

                if (!in_array('IAVA', $_SESSION['module_code'])) {
                    $month_implode[] .= "SELECT COUNT(*) AS Count FROM b2b_summary.pomain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") AND in_kind = '0' AND PODate = '".$filter_date."' ";
                }else{
                    $month_implode[] .= "SELECT COUNT(*) AS Count FROM b2b_summary.pomain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") AND in_kind = '0' AND PODate = '".$filter_date."' ";
                }
            }

            if ($month_implode) {
                $month_sql .= " " . implode(" UNION ALL ", $month_implode) . "";
            }   

            $current_month_order = $this->db->query($month_sql)->result();

            $data = array(
                'pomain' => $check_outstanding_pomain->row('count_pomain'),
                'grmain' => $check_grn->row('count_doc'),
                'grda' => $check_grda->row('count_doc'),
                'strb' => $check_strb->row('count_doc'),
                'no_respond' => $no_respond->row('count_pomain'),
                'date_from' => $this->db->query("SELECT curdate() - INTERVAL $dashboard_num_days_data DAY as date_from")->row('date_from'),
                'date_to' => $this->db->query("SELECT curdate()  as date_to")->row('date_to'),
                'announcement' => $announcement,
                'redirect_pomain' => $redirect_pomain,
                'redirect_grmain' => $redirect_grmain,
                'redirect_grda' => $redirect_grda,
                'redirect_strb' => $redirect_strb,
                'show_panel' => $show_panel,
                'show_announcement_sidebar' => $show_announcement_sidebar,
                'check_announcement_acknowledgement' => $check_announcement_acknowledgement,
                // 'filename_1' => $filename_1,
                // 'filename_2' => $filename_2,
                'redirect_grmain_download' => $redirect_grmain_download,
                'mandatory' => $mandatory,
                'pdf' => $pdf,
                'virtual_path' => base_url($virtual_path),
                'file_name_array' => $file_name_array,
                'notification' => $notification,
                'session_guid' => $_SESSION['customer_guid'],
                'announcement_guid' => $announcement_guid,
                'last_week_order' => $last_week_order,
                'current_month_order' => $current_month_order,
                'day_in_month' => $day_in_month,
                /* 'alert' => $alert,*/
            );

            $this->load->view('header');
            $this->load->view('dashboard', $data);
            $this->load->view('dashboard_modal', $data);
            $this->load->view('footer');
        } else {
            redirect('#');
        }
    }

    public function realtime_dashboard_datetime(){
        echo date('Y-m-d h:i:s');
    }

    public function realtime_dashboard(){

        $iquery_loc = $_SESSION['query_loc'];

        if ($iquery_loc == null || $iquery_loc == '') {
            $iquery_loc = "''";
        }

        if (!in_array('IAVA', $_SESSION['module_code'])) {

            $get_pomain = $this->db->query("SELECT COUNT(*) as count_total_pomain, SUM(total_include_tax) as sum_po_amount from b2b_summary.pomain_info where customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") and in_kind = '0' GROUP BY customer_guid;");

            $get_cndn = $this->db->query("SELECT SUM(total_count) AS total_count, SUM(total_amount) AS total_amount FROM (SELECT COUNT(*) AS total_count, SUM(total_incl_tax) AS total_amount FROM b2b_summary.cndn_amt_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") AND trans_type = 'PDNAMT' GROUP BY customer_guid
            UNION ALL
            SELECT COUNT(*) AS total_count, SUM(total_incl_tax) AS total_amount FROM b2b_summary.dbnotemain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") AND `type` = 'DEBIT' GROUP BY customer_guid) aa;");

            $get_grmain = $this->db->query("SELECT COUNT(*) as count_total_grmain, SUM(total_include_tax) as sum_gr_amount from b2b_summary.grmain_info where customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") GROUP BY customer_guid;");

            $get_po_store = $this->db->query("SELECT COUNT(count_total_po_store) AS count_total_po_store FROM (SELECT COUNT(*) AS count_total_po_store FROM b2b_summary.pomain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and supplier_code in (" . $_SESSION['query_supcode'] . ") GROUP BY loc_group) aa;");

        }else{

            $get_pomain = $this->db->query("SELECT COUNT(*) as count_total_pomain, SUM(total_include_tax) as sum_po_amount from b2b_summary.pomain_info where customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") and in_kind = '0' GROUP BY customer_guid;");

            $get_cndn = $this->db->query("SELECT SUM(total_count) AS total_count, SUM(total_amount) AS total_amount FROM (SELECT COUNT(*) AS total_count, SUM(total_incl_tax) AS total_amount FROM b2b_summary.cndn_amt_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") AND trans_type = 'PDNAMT' GROUP BY customer_guid
            UNION ALL
            SELECT COUNT(*) AS total_count, SUM(total_incl_tax) AS total_amount FROM b2b_summary.dbnotemain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") AND `type` = 'DEBIT' GROUP BY customer_guid) aa;");

            $get_grmain = $this->db->query("SELECT COUNT(*) as count_total_grmain, SUM(total_include_tax) as sum_gr_amount from b2b_summary.grmain_info where customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") GROUP BY customer_guid;");

            $get_po_store = $this->db->query("SELECT COUNT(count_total_po_store) AS count_total_po_store FROM (SELECT COUNT(*) AS count_total_po_store FROM b2b_summary.pomain_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' and loc_group in (" . $_SESSION['query_loc'] . ") GROUP BY loc_group) aa;");

        }

        $redirect_pomain = site_url('dashboard/redirect_from_dashboard?mode=b2b_po&loc=HQ');
        $redirect_grmain = site_url('dashboard/redirect_from_dashboard?mode=b2b_gr&loc=HQ');
        $redirect_cndn = site_url('dashboard/redirect_from_dashboard?mode=b2b_pdncn&loc=HQ');

        $data = array(
            'pomain_total' => ($get_pomain->row('count_total_pomain') != null) ? $get_pomain->row('count_total_pomain') : 0,
            'pomain_amount' => ($get_pomain->row('sum_po_amount') != null) ? $get_pomain->row('sum_po_amount') : 0,
            'cndn_total' => ($get_cndn->row('total_count') != null) ? $get_cndn->row('total_count') : 0,
            'cndn_amount' => ($get_cndn->row('total_amount') != null) ? $get_cndn->row('total_amount') : 0,
            'grmain_total' => ($get_grmain->row('count_total_grmain') != null) ? $get_grmain->row('count_total_grmain') : 0,
            'grmain_amount' => ($get_grmain->row('sum_gr_amount') != null) ? $get_grmain->row('sum_gr_amount') : 0,
            'po_store' => ($get_po_store->row('count_total_po_store') != null) ? $get_po_store->row('count_total_po_store') : 0,
            'redirect_pomain' => $redirect_pomain,
            'redirect_grmain' => $redirect_grmain,
            'redirect_cndn' => $redirect_cndn,
        );

        // ($requestVars->_name == '') ? $redText : '';

        $this->load->view('dashboard_realtime', $data);
    }

    public function previous_announcement()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $check_announcement = $this->db->query("SELECT * from announcement where customer_guid = '" . $_SESSION['customer_guid'] . "' and posted= '1' and now() >= publish_at and acknowledgement=0 order by publish_at desc, created_at desc limit 100 ");
            //echo $this->db->last_query();die;

            if ($check_announcement->num_rows() < 1) {
                $announcement = $this->db->query("SELECT 'Welcome' as title, 'No New announcement at this moment' as content, curdate() as docdate ");
            } else {
                $announcement = $check_announcement;
            }

            $data = array(
                'announcement' => $announcement,
            );

            $this->load->view('header');
            $this->load->view('announcement_preview', $data);
            $this->load->view('footer');
        } else {
            redirect('#');
        }
    }

    public function redirect_from_dashboard()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $loc = $_REQUEST['loc'];
            $mode = $_REQUEST['mode'];

            redirect("$mode" . "?loc=" . $loc);
        } else {
            redirect('#');
        }
    }

    public function see_more_alert()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $query_loc  = $_SESSION['query_loc'];
            $customer_guid  = $_SESSION['customer_guid'];


            // echo var_dump($query_loc);die;
        } else {
            redirect('#');
        }
    }

    public function cust_get_ip()
    {
        $acc_guid = $_SESSION['customer_guid'];
        $ip = $this->db->query("SELECT jasper_url FROM acc WHERE acc_guid = '$acc_guid'")->row('jasper_url');
        // echo $ip;
        $output = array();

        $output['ip'] = $ip;

        echo json_encode($output);
    }

    public function term_upload()
    {
        $user_guid = $_SESSION['user_guid'];
        $customer_guid = $_SESSION['customer_guid'];

        // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
        // {
        //     $user_guid = '1F67A71CEE1D11EC8BED000D3AA2838A';
        // }

        $check_upload_term = $this->db->query("SELECT a.register_guid, a.`memo_type`, b.`customer_guid`, b.`supplier_guid`, b.`user_guid`, d.`supplier_name`,d.`reg_no`, e.`acc_name`,c.`status`, c.`url`, DATE_FORMAT(DATE_ADD(a.`update_at`, INTERVAL + 1 DAY),'%e-%M-%Y') AS `start_date`, DATE_FORMAT(DATE_ADD(a.`update_at`, INTERVAL + 8 DAY),'%e-%M-%Y') AS last_date , IF(f.`setting_guid` IS NULL,IF(DATE_FORMAT(NOW(),'%Y-%m-%d') > DATE_FORMAT(DATE_ADD(a.`update_at`, INTERVAL + 8 DAY),'%Y-%m-%d'),IF(e.`trial_mode` = '1', '2', '1'),'2'),'2') AS `block`, IF(g.`setting_guid` IS NULL, '0','1') AS `validate`  FROM lite_b2b.register_new a INNER JOIN lite_b2b.set_supplier_user_relationship b ON a.`supplier_guid` = b.`supplier_guid` AND a.customer_guid = b.`customer_guid` AND b.`user_guid` = '$user_guid' LEFT JOIN lite_b2b.`reg_upload_doc` c ON b.`customer_guid` = c.`customer_guid` AND b.`supplier_guid` = c.`supplier_guid` INNER JOIN lite_b2b.`set_supplier` d ON b.`supplier_guid` = d.`supplier_guid` INNER JOIN lite_b2b.`acc` e ON b.`customer_guid` = e.`acc_guid` LEFT JOIN lite_b2b.`reg_upload_settings` f ON a.`customer_guid` = f.customer_guid AND a.`supplier_guid` = f.supplier_guid AND f.action_status = 'no_check' LEFT JOIN lite_b2b.`reg_upload_settings` g ON a.`customer_guid` = g.customer_guid AND a.`supplier_guid` = g.supplier_guid AND g.action_status = 'need_check' WHERE a.term_download = '1' AND a.customer_guid = '$customer_guid' LIMIT 1");

        // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
        // {
        //     echo $this->db->last_query(); die;
        // }

        $validate_data = $check_upload_term->row('validate');
        $supplier_guid = $check_upload_term->row('supplier_guid');
        $reg_memo_type = $check_upload_term->row('memo_type');
        $reg_guid_data = $check_upload_term->row('register_guid');

        if (($reg_memo_type == 'outright') || ($reg_memo_type == 'consignment') || ($reg_memo_type == 'both')) {
            $doc_reqeust = '1';

            if($validate_data == '1')
            {
                $check_reg_doc = $this->db->query("SELECT a.* FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND a.`status` IN ('Accepted')");
            }
            else
            {
                $check_reg_doc = $this->db->query("SELECT a.* FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND a.`status` IN ('Pending','Accepted')");
            }

            $upload = $check_reg_doc->num_rows();

            $url_normal_rejected = $this->db->query("SELECT a.url FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND term_type = 'normal_term' AND `status` = 'Rejected'")->row('url');

            $url_normal = $this->db->query("SELECT a.url FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND term_type ='normal_term' AND a.`status` IN ('Pending','Accepted') ")->row('url');
        } else {
            $doc_reqeust = '2';

            if($validate_data == '1')
            {
                $check_reg_doc = $this->db->query("SELECT a.* FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND a.`status` IN ('Accepted')");
            }
            else
            {
                $check_reg_doc = $this->db->query("SELECT a.* FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND a.`status` IN ('Pending','Accepted')");
            }

            $upload = $check_reg_doc->num_rows();

            $url_normal = $this->db->query("SELECT a.url FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND term_type ='normal_term' AND a.`status` IN ('Pending','Accepted') ")->row('url');

            $url_special = $this->db->query("SELECT a.url FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND term_type ='special_term' AND a.`status` IN ('Pending','Accepted')")->row('url');

            $url_normal_rejected = $this->db->query("SELECT a.url FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND term_type ='normal_term' AND `status` = 'Rejected' ")->row('url');

            $url_special_rejected = $this->db->query("SELECT a.url FROM lite_b2b.`reg_upload_doc` a WHERE a.`supplier_guid` = '$supplier_guid' AND a.`customer_guid` = '$customer_guid' AND term_type ='special_term' AND `status` = 'Rejected'")->row('url');
        }


        $data = array(
            'check_upload_term' => $check_upload_term->result(),
            'result' => $check_upload_term->num_rows(),
            'upload' => $upload, //important
            'doc_reqeust' => $doc_reqeust, //important
            'term_url' => $url_normal,
            'special_term_url' => $url_special,
            'term_url_rejected' => $url_normal_rejected,
            'special_term_url_rejected' => $url_special_rejected,
            'reg_guid_data' => $reg_guid_data,
        );

        echo json_encode($data);
    }


    public function upload_term_docs()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        ini_set('post_max_size', '10M');
        ini_set('upload_max_filesize', '10M');
        //set php.ini upload_max_filesize=64M
        //$acc_guid = $_SESSION['customer_guid'];
        $session_guid = $_SESSION['user_guid'];
        $file_uuid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');
        $cur_date = $this->db->query("SELECT now() as now")->row('now');
        $created_at = $this->db->query("SELECT now() as now")->row('now');
        //print_r($file_config_main_path); die;
        $file_name = $this->input->post('file_name');
        $user_guid = $this->input->post('term_user_guid');
        $supplier_guid = $this->input->post('supplier_guid');
        $acc_guid = $this->input->post('customer_guid');
        $term_type = $this->input->post('term_type');
        $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid, 'web', 'reg_docs', 'main_path', 'REGTERM');
        $file_config_sec_path = $this->file_config_b2b->file_path_name($acc_guid, 'web', 'reg_docs', 'sec_path', 'REGTERMPATH');
        //print_r($term_type); die;  

        $check_rejected = $this->db->query("SELECT * FROM lite_b2b.reg_upload_doc WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND term_type = '$term_type' AND `status` = 'Rejected' LIMIT 1");
        //echo $this->db->last_query(); die;

        if ($check_rejected->num_rows() >= 1) {
            $r_acc_guid = $check_rejected->row('customer_guid');
            $r_supplier_guid = $check_rejected->row('supplier_guid');
            $r_user_guid = $check_rejected->row('user_guid');
            $r_term_type = $check_rejected->row('term_type');
            $r_url = $check_rejected->row('url');
            $rejected_guid = $check_rejected->row('upload_guid');
            $r_file_name = basename($r_url);
            $r_unlink_path = $file_config_main_path . "$r_acc_guid/$r_supplier_guid/$r_user_guid/$r_term_type/$r_file_name";
            unlink($r_unlink_path);
            $update_data = $this->db->query("DELETE FROM lite_b2b.reg_upload_doc WHERE upload_guid = '$rejected_guid' ");
        }

        $check_data = $this->db->query("SELECT * FROM lite_b2b.reg_upload_doc WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND term_type = '$term_type' ");

        if ($check_data->num_rows() >= 1) {
            $data = array(
                'para1' => 1,
                'msg' => 'Term Sheet Already Exists. Please Contact Support Admin.',
            );
            echo json_encode($data);
            exit();
        }

        $file_name = str_replace(':', '', str_replace('&', '', str_replace(' ', '_', $file_name)));
        //$file_name = ,$file_name); 
        $defined_path_acc = $file_config_main_path . $acc_guid . '/';
        $defined_path = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/';
        $defined_path_1 = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/';
        $defined_path_2 = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/' . $term_type . '/';

        $user_id = $this->db->query("SELECT user_id FROM lite_b2b.set_user WHERE user_guid = '$session_guid' ")->row('user_id');

        if($user_id == '' || $user_id == 'null' || $user_id == null)
        {
            $user_id = $this->db->query("SELECT user_id FROM lite_b2b.set_user WHERE user_guid = '$user_guid' ")->row('user_id');
        }

        //print_r($file_name); die;
        $extension = explode('.', $file_name);

        if (count($extension) > 2) {
            $data = array(
                'para1' => 1,
                'msg' => 'Error File Name. Please remove comma dot for naming',
            );
            echo json_encode($data);
            exit();
        }

        if (!file_exists($defined_path_acc)) {
            mkdir($defined_path_acc, 0777);
        }

        if (!file_exists($defined_path)) {
            mkdir($defined_path, 0777);
        }

        if (!file_exists($defined_path_1)) {
            mkdir($defined_path_1, 0777);
        }

        if (!file_exists($defined_path_2)) {
            mkdir($defined_path_2, 0777);
        }

        //if want add date uncomment here @@@@@
        $cur_date = str_replace(' ', '_', $cur_date);
        $cur_date = str_replace(':', '', $cur_date);
        $file_name = $cur_date . '_' . $file_name;
        $file_name = str_replace('[', '', $file_name);
        $file_name = str_replace(']', '', $file_name);

        $unlink_path = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/' . $term_type . '/' . $file_name;
        $unlink_path_check = $file_config_sec_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/' . $term_type . '/' . $file_name . '';

        // if(file_exists($unlink_path)){
        // unlink($unlink_path);
        // }

        $check_path = $file_config_main_path . $acc_guid . '/' . $supplier_guid . '/' . $user_guid . '/' . $term_type . '/' . $file_name;

        if (file_exists($check_path)) {
            $data = array(
                'para1' => 1,
                'msg' => 'Document File Name Exists.',
            );
            echo json_encode($data);
            exit();
        }

        $config['upload_path']          = $defined_path_2;
        $config['allowed_types']        = '*';
        $config['max_size']             = 500000000;
        $config['file_name'] = $file_name;
        //var_dump( $this->input->post('file') );die; 
        //print_r($this->input->post());die;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());

            if (null != $error) {
                $data = array(
                    'para1' => 1,
                    'msg' => $this->upload->display_errors(),
                );
                echo json_encode($data);
                exit();
            } //close else

        } else {
            $data = array('upload_data' => $this->upload->data());

            //$filename = $defined_path_1.$data['upload_data']['file_name'];

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $insert_data = $this->db->query("INSERT INTO `lite_b2b`.`reg_upload_doc` (`upload_guid`, `customer_guid`, `supplier_guid`, `user_guid`, `term_type`, `status`, `url`, `created_at`, `created_by`) VALUES ('$file_uuid', '$acc_guid', '$supplier_guid' , '$user_guid', '$term_type','Accepted', '$unlink_path_check','$created_at', '$user_id');");
            } else {
                $insert_data = $this->db->query("INSERT INTO `lite_b2b`.`reg_upload_doc` (`upload_guid`, `customer_guid`, `supplier_guid`, `user_guid`, `term_type`, `status`, `url`, `created_at`, `created_by`) VALUES ('$file_uuid', '$acc_guid', '$supplier_guid' , '$user_guid', '$term_type','Pending', '$unlink_path_check','$created_at', '$user_id');");
            }
        }

        $error = $this->db->affected_rows();

        if ($error > 0) {

            $data = array(
                'para1' => 0,
                'msg' => 'Upload Completed.',
                //'link' => $url_link,
            );
            echo json_encode($data);
            exit();
        } else {
            $data = array(
                'para1' => 1,
                'msg' => 'Error Upload Data.',
                //'link' => 'Unknown URL.',

            );
            echo json_encode($data);
            exit();
        }
    }
}
