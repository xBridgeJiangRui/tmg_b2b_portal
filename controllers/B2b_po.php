<?php
class b2b_po extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('html');
        
        //load the department_model
        $this->load->model('Po_model');
        $this->load->model('General_model');
        $this->load->model('Datatable_model');
        $this->jasper_ip = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'),'web','general_doc','jasper_invoice_ip','GDJIIP');
        $this->jasper_path = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'),'web','general_doc','jasper_document_path','GDJDP');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $setsession = array(
                'frommodule' => 'b2b_po',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            // if(isset($_SESSION['from_other']) == 0 )
            // {
                $setsession = array(
                    'po_loc' => $_REQUEST['loc'],
                );
                $this->session->set_userdata($setsession);
                redirect('b2b_po/po_list');
                $this->panda->get_uri();
               
            // }
            // else
            // {
            //     if($_REQUEST['status'] == '')
            //     {
            //         unset($_SESSION['from_other']);
            //         redirect('b2b_po?loc='.$_REQUEST['loc']);
            //     };
            //     $this->panda->get_uri();
            //     redirect('general/view_status?status='.$_REQUEST['loc']);
            // }
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function po_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $check_loc = $_SESSION['po_loc'];
 
            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            if (in_array('VHFSP', $_SESSION['module_code'])) {
                $hide_url_code = '<a class="btn btn-app" href="' . site_url('PO_hide/view_status') . '?status=HFSP&loc=' . $_REQUEST['loc'] . '&p_f=&p_t=&e_f=&e_t=&r_n=" style="color:#ffad33"><i class="fa fa-edit"></i>View Hide</a>';
            } else {
                $hide_url_code = '';
            }

            $data = array(
                'set_admin_code' => $this->db->query("SELECT code,portal_description as reason from status_setting where type = 'hide_po_filter' AND isactive = 1 order by portal_description asc"),
                'set_code' => $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc"),
                'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PO_FILTER_STATUS' order by code='ALL' desc, code asc"),
                'period_code' => $this->db->query("SELECT period_code from lite_b2b.list_period_code"),
                'location' => $this->db->query("SELECT DISTINCT branch_code 
                    FROM acc_branch AS a
                    INNER JOIN acc_concept AS b
                    ON b.`concept_guid` = a.`concept_guid`
                     WHERE branch_code IN  (" . $_SESSION['query_loc'] . ") and b.`acc_guid` = '" . $_SESSION['customer_guid'] . "' order by branch_code asc "),
                'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'"),
                'hide_url' => $hide_url_code
            );

            $data_footer = array(
                'activity_logs_section' => 'po'
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('po/b2b_po_list_view', $data);
            //$this->load->view('general_modal', $data);
            $this->load->view('footer', $data_footer);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function po_datatable()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            //die;
            $doc = 'po_table';
            $ref_no = $this->input->post('ref_no');
            $status = $this->input->post('status');
            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            $exp_datefrom = $this->input->post('exp_datefrom');
            $exp_dateto = $this->input->post('exp_dateto');
            $period_code = $this->input->post('period_code');
            $type = $this->input->post('type');
            $customer_guid = $_SESSION['customer_guid'];
            $query_loc = $_SESSION['query_loc'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();
            $hq_branch_code_array = array();
            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            if (in_array($_SESSION['po_loc'], $hq_branch_code_array)) {
                $loc = $query_loc;
            } else {
                $loc = "'" . $_SESSION['po_loc'] . "'";
            }
            
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $module_code_in = '';
            } else {
                $module_code_in = "AND a.supplier_code IN (" . $_SESSION['query_supcode'] . ") ";
            }

            if ($ref_no == '') {
                $ref_no_in = '';
            } else {
                $ref_no_in = " AND a.RefNo LIKE '%" . $ref_no . "%' ";
            }

            if ($status == '') {
                $status_in = " AND a.status = '' ";
            } elseif ($status == 'READ') {
                $status_in = " AND a.status IN ('printed', 'viewed') ";
            } elseif ($status == 'ALL') {
                $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'PO_FILTER_STATUS'");

                foreach ($get_stat->result() as  $row) {
                    $check_stat[] = $row->code;
                }

                foreach ($check_stat as &$value) {
                    $value = "'" . trim($value) . "'";
                }
                $check_status = implode(',', array_filter($check_stat));
                $status_in = " AND a.status IN ($check_status) ";
            } else {
                $status_in = " AND a.status = '$status' ";
            }

            if ($datefrom == '' || $dateto == '') {
                $doc_daterange_in = '';
            } else {
                $doc_daterange_in = " AND a.podate BETWEEN '$datefrom' AND '$dateto' ";
            }

            if ($exp_datefrom == '' || $exp_dateto == '') {
                $exp_daterange_in = '';
            } else {
                $exp_daterange_in = " AND a.expiry_date BETWEEN '$exp_datefrom' AND '$exp_dateto' ";
            }

            if ($period_code == '') {
                $period_code_in = '';
            } else {
                $period_code_in = " AND LEFT(a.podate, 7) = '$period_code'";
            }

            if (!in_array('VGR', $_SESSION['module_code'])) {
                $module = 'gr_download_child';
            } else {
                $module = 'gr_child';
            }

            $query_count = "SELECT * FROM ( SELECT a.customer_guid AS customer_guid,
            a.RefNo AS refno,
            a.supplier_code,
            a.supplier_name,
            a.podate,
            a.loc_group,
            b.gr_refno AS grn_refno
            FROM b2b_summary.pomain_info a FORCE INDEX (customer_guid)
            LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND a.`customer_guid` =  b.`customer_guid`
            WHERE a.customer_guid = '$customer_guid' 
            AND a.loc_group IN ($loc)
            AND a.in_kind = 0
            $module_code_in
            $status_in 
            $doc_daterange_in
            $exp_daterange_in 
            $ref_no_in
            $period_code_in
            ) zzz
            ";

            $query = "SELECT a.customer_guid AS customer_guid,
            a.RefNo AS refno,
            b.gr_refno AS grn_refno ,
            a.supplier_code,
            a.supplier_name,
            a.podate,
            a.loc_group,
            a.delivery_date,
            a.expiry_date,
            a.amount,
            a.tax,
            a.total_include_tax,
            c.portal_description AS rejected_remark,
            IF(a.status = '', 'NEW', a.status) as status
            FROM b2b_summary.pomain_info a FORCE INDEX (customer_guid)
            LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND a.`customer_guid` =  b.`customer_guid`
            LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' 
            WHERE a.customer_guid = '$customer_guid' 
            AND a.loc_group IN ($loc)
            AND a.in_kind = 0
            $module_code_in
            $status_in 
            $doc_daterange_in
            $exp_daterange_in 
            $ref_no_in
            $period_code_in
            ";
            //AND a.loc_group IN ($loc) -- up to production need change this

            $sql = "SELECT * FROM (
                $query
            ) zzz ";
            
            $query_po = $this->Datatable_model->datatable_main($sql, $type, $doc);
            //print_r($query); die;
            $fetch_data = $query_po->result();
            //print_r($fetch_data); die;
            //echo $this->db->last_query(); die;
            $data = array();
            if (count($fetch_data) > 0) {
                
                foreach ($fetch_data as $row) {
                    $tab = array();
                    
                    $tab["refno"] = '<span style="display:flex;">' . $row->refno . '<i data-toggle="tooltip" data-placement="top" title="Click to preview item details" class="fa fa-info-circle" style="padding-top:5px;padding-left:10px;cursor: pointer;"  id="preview_po_item_line" refno=' . $row->refno . '></i></span>';
                    $tab["grn_refno"] = '<a href="'. base_url() .'index.php/b2b_gr/' . $module . '?trans=' . $row->grn_refno . '&loc=' . $_SESSION['po_loc'] . '&fmodule=1">' . $row->grn_refno . '</a>';
                    $tab["loc_group"] = $row->loc_group;
                    $tab["supplier_code"] = $row->supplier_code;
                    $tab["supplier_name"] = $row->supplier_name;
                    $tab["podate"] = $row->podate;
                    $tab["delivery_date"] = $row->delivery_date;
                    $tab["expiry_date"] = $row->expiry_date;
                    $tab['amount'] = "<span class='pull-right'>" . number_format($row->amount, 2) . "</span>";
                    $tab['tax'] = "<span class='pull-right'>" . number_format($row->tax, 2) . "</span>";
                    $tab['total_include_tax'] = "<span class='pull-right'>" . number_format($row->total_include_tax, 2) . "</span>";
                    $tab["status"] = $row->status;
                    $tab["rejected_remark"] = $row->rejected_remark;

                    if (in_array('HFSP', $_SESSION['module_code']) && $this->session->userdata('customer_guid') != '8D5B38E931FA11E79E7E33210BD612D3') {

                        if($row->status == '')
                        {
                            $tab["action"] = "<a href=" . site_url('b2b_po/po_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['po_loc'] . "&accpt_po_status=" . $row->status . " style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                            <button id='hide_doc_btn' class='btn btn-sm btn-danger' style='float:left;margin-left:2px;' refno='" . $row->refno . "' loc='" . $_SESSION['po_loc'] . "''><span class='fa fa-eye-slash'></span></button> ";
                        }
                        else
                        {
                            $tab["action"] = "<a href=" . site_url('b2b_po/po_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['po_loc'] . "&accpt_po_status=" . $row->status . " style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a> ";
                        }

                    } else {
                        $tab["action"] = "<a href=" . site_url('b2b_po/po_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['po_loc'] . "&accpt_po_status=" . $row->status . " style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a> ";
                        //$tab["action"] = "<a href=" . site_url('b2b_po/po_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['po_loc'] . " style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    }
                    $tab["chkb"] = '<input type="checkbox" class="data-check" value="' . $row->refno . '">';

                    $data[] = $tab;
                }
            } else {
                $data = '';
            }
            
            $output = array(
                "draw"                =>     intval($_POST["draw"]),
                "recordsTotal"        =>     $this->Datatable_model->general_get_all_data($query_count, $doc),
                "recordsFiltered"     =>     $this->Datatable_model->general_get_filtered_data($query_count, $doc),
                "data"                =>     $data
            );
            
            echo json_encode($output);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function po_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            //print_r($refno); die;
            if(isset($_REQUEST['accpt_po_status']))
            {
                $accpt_po_status = $_REQUEST['accpt_po_status'];
            }
            else
            {
                // $accpt_po_status = $_REQUEST['accpt_po_status'];
                // if($accpt_po_status == '' || $accpt_po_status == null)
                // {
                    $accpt_po_status = 'NEW';
                // }
            }
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];

            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_po'
                , '$from_module'
                , '$refno'
                , now()
                ");
                
                $this->db->query("UPDATE b2b_summary.pomain_info set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");

            
            };
          
            
            $check_scode = $this->db->query("SELECT supplier_code AS scode from b2b_summary.pomain_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');

            $check_scode = str_replace("/","+-+",$check_scode);

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            //$file_headers = @get_headers($filename);
            //remove , rejected_remark
            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.pomain_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");

            // $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc");
            $set_code = $this->db->query("SELECT code,portal_description as reason from status_setting where type = 'reject_po' AND isactive = 1 order by portal_description asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");

            $subscribe_edi = $this->db->query("SELECT b.subscribe_edi
            FROM lite_b2b.set_supplier_group AS a
            INNER JOIN lite_b2b.set_supplier AS b
            ON a.supplier_guid = b.supplier_guid
            WHERE a.supplier_group_name = '$check_scode'
            GROUP BY a.supplier_group_name")->row('subscribe_edi');

            if ($check_status->row('status') == 'Pending' || $check_status->row('status') == 'viewed' || $check_status->row('status') == 'printed' || $check_status->row('status') == 'extended') {

                if (!in_array('BAPO', $_SESSION['module_code']) || in_array('VEL', $_SESSION['module_code']) && $subscribe_edi == '1' ) {
                    $show_action_button = '1';
                } else {
                    $show_action_button = '1';
                }
            } else {

                $show_action_button = '0';
                //echo $check_status->row('status'); echo  $show_action_button; die;
            };

            if ($check_status->row('status') == 'Pending' || $check_status->row('status') == 'viewed' || $check_status->row('status') == 'printed' || $check_status->row('status') == 'extended') {

                if (in_array('!RPO', $_SESSION['module_code'])|| in_array('VEL', $_SESSION['module_code']) && $subscribe_edi == '1') {
                    $show_action_button2 = '0';
                } else {
                    $show_action_button2 = '1';
                }
            } elseif ($check_status->row('status') == 'Accepted' && in_array('VEL', $_SESSION['module_code']) && $subscribe_edi == '1') {
                $show_action_button2 = '1';
            } else {

                $show_action_button2 = '0';
                //echo $check_status->row('status'); echo  $show_action_button; die;
            };  //reject button            


            // $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            //     $to_shoot_url = $check_url."/pochild?table=pochild"."&refno=".$refno;

            // $ch = curl_init($to_shoot_url);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $response = curl_exec($ch);

            //     if($response !== false) {
            //         echo "string";
            //     }   
            //     else
            //     {                     
            //         $this->session->set_flashdata("noconnection", '<div class="alert alert-warning fade in">Please Check Your Internet Connection</div>');
            //     }
            if(in_array('VHFSP',$_SESSION['module_code']))
            {
                $hide_url = '<a class="btn btn-app" href="'.site_url('PO_hide/view_status').'?status=HFSP&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=" style="color:#ffad33"><i class="fa fa-edit"></i>View Hide</a>';
            }
            else
            {
                $hide_url = '';
            }

            $hide_report_toolbar = 0;
            if(!in_array($check_status->row('status'), ['Accepted','gr_completed'])){
                $get_current_settings = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE customer_guid = '$customer_guid'");

                // $check_movement = $this->db->query("SELECT COUNT(*) AS `count` FROM lite_b2b.supplier_movement WHERE `value` = '$refno' and customer_guid = '".$customer_guid."' and action = 'accepted_po'")->row('count');

                if($get_current_settings->row('supplier_mandatory_to_accept_po') == 1){
                    $hide_report_toolbar = 1;
                }

            }

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Purchase Order',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'set_admin_code' =>  $set_admin_code,
                'accpt_po_status' => $accpt_po_status,
                'show_action_button' => $show_action_button,
                'show_action_button2' => $show_action_button2,
                'hide_url' => $hide_url,
                'hide_report_toolbar' => $hide_report_toolbar,
                'request_link' => site_url('B2b_po/po_report?refno='.$refno), //site_url('B2b_po/po_report?refno='.$refno)
            );

            $data_footer = array(
                'activity_logs_section' => 'po'
            );            
            
            $this->load->view('header');       
            $this->load->view('po/b2b_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer',$data_footer);
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function export_excel()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            //$this->panda->get_uri();
            $refno = $_REQUEST['refno'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];

            $get_po_child_data = $this->db->query("SELECT a.`po_json_info`
            FROM b2b_summary.`pomain_info` AS a
            WHERE a.`refno` = '$refno'
            AND a.`customer_guid`='$customer_guid'")->row('po_json_info');
            //$check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            // $to_shoot_url = $check_url."/pochild?table=pochild"."&refno=".$refno;

            // $ch = curl_init($to_shoot_url);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            // $response = curl_exec($ch);
 
            if (count(json_decode($get_po_child_data, true)['pochild']) > 0) 
            {
                foreach(json_decode($get_po_child_data, true)['pochild'] as $json) 
                {
                    $headers = array(
                        'description',
                        'qty',          
                        'netunitprice',
                        'gst_tax_code',
                        'gst_tax_rate',
                        'gst_tax_amount',
                        'sname',
                        'issuedby',
                    );  
    
                    $headers[] = $key;
                }

                foreach(json_decode($get_po_child_data, true)['pomain'] as $main) 
                {
                    $sname = $main['SName'];
                    $issuedby = $main['IssuedBy'];                              
                }

                foreach(json_decode($get_po_child_data, true)['pochild'] as $child) 
                {
                    $data[] = array(                        
                        'description' => $child['Description'],
                        'qty' => $child['Qty'],                         
                        'netunitprice' => round($child['NetUnitPrice'],4),
                        'gst_tax_code' => $child['gst_tax_code'],
                        'gst_tax_rate' => $child['gst_tax_rate'],
                        'gst_tax_amount' => $child['gst_tax_amount'],
                        'sname' => $sname,
                        'issuedby' => $issuedby,
                    );                                    
                }

                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false);            
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"".$refno."".".csv\"");
                header("Pragma: no-cache");
                ob_clean();
                
                $handle = fopen('php://output', 'w');
                fputcsv($handle, $headers);
                foreach ($data as $data) 
                {
                    fputcsv($handle, $data);
                }
                    fclose($handle);
                exit;
            }   
            else
            {                     
                $this->session->set_flashdata("noconnection", '<div class="alert alert-warning fade in">Error Connecting Client Server. </div>');
                    redirect('b2b_po/po_child?trans='.$refno.'&loc='.$loc.'');
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }


    public function direct_print()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];

            $check_scode = $this->db->query("SELECT supplier_code AS scode from b2b_summary.pomain_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            $file_headers = @get_headers($filename);

            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status, rejected_remark from b2b_summary.pomain_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");

            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Purchase Order',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'set_admin_code' =>  $set_admin_code,
            );
            //NEW
           /* $files = $data['filename'];
            var_dump($files);die;
            require('fpdf.php');
            require('fpdi.php');

                $pdf = new FPDI();
                
                // iterate over array of files and merge
                foreach ($files as $file) {
                    $pageCount = $pdf->setSourceFile($file);
                    for ($i = 0; $i < $pageCount; $i++) {
                        $tpl = $pdf->importPage($i + 1, '/MediaBox');
                        $pdf->addPage();
                        $pdf->useTemplate($tpl);
                    }
                }
                
                // output the pdf as a file (http://www.fpdf.org/en/doc/output.htm)
                $pdf->Output('F','merged.pdf');
                */
                
            // OLD
          
            if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
            {
                
              echo "<script>window.close();</script>";
            }
            else
            {
                if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                {                
                    $this->db->query("UPDATE b2b_summary.pomain_info set status = 'printed' where customer_guid ='$customer_guid' and refno = '$refno' and status IN ('','viewed') ");

                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'printed_po'
                    , '$from_module'
                    , '$refno'
                    , now()
                    ");
                }
                redirect ($filename);
            } 
          
            /*$this->load->view('header');       
            $this->load->view('po/panda_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');*/
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function direct_print_merge()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $pdf_name = $_REQUEST['pdfname'];
                // echo $refno;die;
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$pdf_name.'.pdf');
            $filename = base_url('merge/'.$pdf_name.'.pdf');

            // $filename = 'http://192.168.10.29/lite_panda_b2b/uploads/tfvalue/merge.pdf';
            // echo $filename;die;
 
            $file_headers = @get_headers($filename);


            $refno_array = explode(",",$refno);
            // echo $refno;
            // print_r($refno_array);die;
            foreach($refno_array as $row2)
            {
                // echo 1;
                $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status, rejected_remark from b2b_summary.pomain_info where refno = '$row2' and customer_guid = '".$_SESSION['customer_guid']."'");

                $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc");
                $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");


                $data = array(
                    'filename' => $filename,
                    'file_headers' => $file_headers,
                    'virtual_path' => $virtual_path,
                    'title' => 'Purchase Order',
                    'check_status' => $check_status,
                    'set_code' => $set_code,
                    'set_admin_code' =>  $set_admin_code,
                );
               
                if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
                {
                    
                  echo "<script>window.close();</script>";
                }
                else
                {
                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    {                    
                        $this->db->query("UPDATE b2b_summary.pomain_info set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status = '' ");

                        $this->db->query("REPLACE into supplier_movement select 
                        upper(replace(uuid(),'-','')) as movement_guid
                        , '$customer_guid'
                        , '$user_guid'
                        , 'printed_po'
                        , '$from_module'
                        , '$row2'
                        , now()
                        ");
                    }
                    // redirect ($filename);
                }

            }
            redirect ($filename);
            /*$this->load->view('header');       
            $this->load->view('po/panda_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');*/
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function po_child_old()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $setsession = array(
                'refno' =>$_REQUEST['trans'],
                );
        $this->session->set_userdata($setsession);
        $refno = $_SESSION['refno'];
        $session_data = $this->session->userdata('customers');

        $data = array (
            'main' => $this->db->query("SELECT refno,podate,deliverdate,expiry_date,location,tax_code_purchase,gst_tax_rate,total,
             gst_tax_sum,total_include_tax,status from ".$session_data['customer_db'].".pomain where refno = '$refno' "),
            'child' => $this->db->query("SELECT line, pricetype,itemcode,barcode,description,
            concat(qty,'',LOWER(um)) as qty, unitprice, discamt,
            ROUND((hcost_po)/qty,4) AS unit_disc_prorate, 
            ROUND(IF(hcost_po=0,netunitprice,(totalprice-(hcost_po))/qty),4) AS unit_price_bfr_tax,
            ROUND((totalprice-(hcost_po)),2) AS total_price_bfr_tax, gst_tax_amount, 
            ROUND(((totalprice-(hcost_po))+gst_tax_amount),2) AS gst_unit_total,
            ifnull(reason, '') as reason 
            FROM ".$session_data['customer_db'].".pochild where refno = '$refno' order by line asc"),
            'child_reject' => $this->db->query("SELECT reason from set_setting where module_name = 'PO'"),
            );

        $this->load->view('header');
        $this->load->view('panda_menu_view.php');
        $this->load->view('po/panda_po_child',$data);
        $this->load->view('po/panda_po_modal', $data);
        $this->load->view('footer');
        }
        else
        {
            
            redirect('#');
        }
    }

    public function check_accept()
    {
        if($this->session->userdata('logged_in') == true)
        {
            $this->panda->get_uri();
            $session_data = $this->session->userdata('logged_in');
            $user_guid = $data['user_guid'] = $session_data['user_guid'];
            $session_data = $this->session->userdata('branch');
            $branch_code = $data['branch_code'] = $session_data['branch_code'];
            $session_data = $this->session->userdata('customers');
            $customer = $data['customer'] = $session_data['customer'];
            $customer_guid = $data['customer_guid'] = $session_data['customer_guid'];
            $session_data = $this->session->userdata('customers');
        
                $reason = $this->input->post('reason[]');
                $line = $this->input->post('line[]');
                $itemcode = $this->input->post('itemcode');
    
                $pochild = array();
                    foreach($line as $row => $id)
                    {
                        $pochild[] = [
                           'line' => $id, 
                           'reason' => $reason[$row],
                           ];
                    }

         
                $table= $session_data['customer_db'].".pomain";
                $db_update = $session_data['customer_db'].".pochild";

                $check_status = $this->db->query("SELECT status from ".$session_data['customer_db'].".pomain where refno  = '".$_SESSION['refno']."'");

                if ($check_status->row('status') == 'Pending')
                {

               $data = array (
                 'status' => 'PO_Accepted',
                    );
                $this->Po_model->update_accepted($table,$data);
                $this->db->where_in('refno', $_SESSION['refno']);
                $this->db->update_batch($db_update, $pochild, 'line');
                //echo $this->db->last_query();die;

                    $check_child = $this->db->query("SELECT REPLACE(GROUP_CONCAT(reason), ',', '')  as reason from ".$session_data['customer_db'].".pochild where refno = '".$_SESSION['refno']."' group by refno");
                    if ($check_child->row('reason') != '' )
                    {
                        $p_accepted = array (
                            'status' => 'Partially Accepted',
                        );
                        $this->Po_model->update_accepted($table,$p_accepted);
                       // echo $this->db->last_query();die;
                        $this->session->set_flashdata('message', 'PO is Partially Accepted.');
                        redirect('b2b_po/po_child?trans='.$_SESSION['refno']);
                    }
                    else
                    {
                        $this->session->set_flashdata('message', 'PO Accepted.');
                        redirect('b2b_po/po_child?trans='.$_SESSION['refno']);
                    };
                //echo $this->db->last_query();die;
            }
            else
            {
                $this->session->set_flashdata('message', 'Document status is not Pending. Please make sure PO status is Pending before making any changes.');
                redirect('b2b_po/po_child?trans='.$_SESSION['refno']);
            };


        }
        else
        {
            redirect('#');
        }
    }

    public function check_rejected()
    {
         if($this->session->userdata('logged_in') == true)
        {
            $this->panda->get_uri();
            $session_data = $this->session->userdata('logged_in');
            $user_guid = $data['user_guid'] = $session_data['user_guid'];
            $username = $data['username'] = $session_data['username'];
            $session_data = $this->session->userdata('branch');
            $branch_code = $data['branch_code'] = $session_data['branch_code'];
            $session_data = $this->session->userdata('customers');
            $customer = $data['customer'] = $session_data['customer'];
            $customer_guid = $data['customer_guid'] = $session_data['customer_guid'];
            $session_data = $this->session->userdata('customers');

            $table = $session_data['customer_db'].".pomain";
            
             $check_status = $this->db->query("SELECT status from ".$session_data['customer_db'].".pomain where refno  = '".$_SESSION['refno']."'");

                if ($check_status->row('status') == 'Pending')
                {

                $data = array (
                    'status' => 'PO_Rejected',
                    'rejected_remark' => $this->input->post('rejected'),
                    'rejected' => '1',
                    'rejected_by' => $username,
                    'rejected_at' => $this->db->query("SELECT now() as now")->row('now'),
                        );
                    $this->Po_model->update_accepted($table,$data);
                    redirect('b2b_po/po_child?trans='.$_SESSION['refno']);
                }
                else
                {
                     $this->session->set_flashdata('message', 'Document status is not Pending. Please make sure PO status is Pending before making any changes.');
                    redirect('b2b_po/po_child?trans='.$_SESSION['refno']);
                }

        }
        else
        {
            redirect('#');
        }
    }

    public function bulk_print_check_status()
    {
        $refno = $this->input->post('list_id');
        $customer_guid = $_SESSION['customer_guid'];

        $get_current_settings = $this->db->query("SELECT * FROM lite_b2b.acc_settings WHERE customer_guid = '$customer_guid'");

        foreach($refno as $row)
        {
            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.pomain_info where refno = '$row' and customer_guid = '".$customer_guid."'");

            if(!in_array($check_status->row('status'), ['Accepted','gr_completed'])){

                // $check_movement = $this->db->query("SELECT COUNT(*) AS `count` FROM lite_b2b.supplier_movement WHERE `value` = '$row' and customer_guid = '".$customer_guid."' and action = 'accepted_po'")->row('count');

                if($get_current_settings->row('supplier_mandatory_to_accept_po') == 1){
                    echo 0;
                    die;
                }
            }
        }

        echo 1;
    }

    public function bulk_accept()
    {
        $refno = $this->input->post('list_id');
        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid'];
        $from_module = $_SESSION['frommodule'];

        // echo var_dump($refno);

        foreach($refno as $row)
        {
            $this->db->query("REPLACE into supplier_movement select 
            upper(replace(uuid(),'-','')) as movement_guid
            , '$customer_guid'
            , '$user_guid'
            , 'accepted_po'
            , '$from_module'
            , '$row'
            , now()
            ");
            $this->db->query("UPDATE b2b_summary.pomain_info SET status = 'Accepted',b2b_status = 'readysend' WHERE refno = '$row' AND customer_guid='$customer_guid'");
            // echo $this->db->last_query();die;
        }

        if($this->db->affected_rows() > 0)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    public function preview_po_item_line()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $refno = $this->input->post('refno');

        $query = $this->db->query("SELECT 
        JSON_UNQUOTE(JSON_EXTRACT(a.`po_json_info`,'$.pochild')) as pochild
        FROM b2b_summary.pomain_info a
        WHERE refno = '$refno' AND customer_guid = '$customer_guid'")->row('pochild');
        $pochild_arr = json_decode($query);
        $result_arr = array();
        foreach($pochild_arr as $row){
            $tab = array();
            $tab['Line'] = $row->Line;
            $tab['Itemcode'] = $row->Itemcode;
            $tab['Qty'] = $row->Qty;
            $tab['TotalPrice'] = $row->TotalPrice;
            $tab['Description'] = $row->Description;
            $result_arr[] = $tab;
        }


        $data = array(
            'po_item_line' => $result_arr,
        );
        // print_r($data);die;
        echo json_encode($data);
    }

    public function po_report()
    {
        $refno = $_REQUEST['refno'];
        //$customer_guid = $_SESSION['customer_guid'];

        $get_status = $this->db->query("SELECT `status` FROM lite_b2b.jasper_server WHERE isactive = '1'")->row('status');

        if($get_status == '0')
        {
            print_r('Report Under Maintenance.'); 
            die;
        }

        $url = $this->jasper_ip . $this->jasper_path ."/Backend_PO/main_jrxml.pdf?refno=".$refno; // po

        //print_r($url); die;
        $check_code = $this->db->query("SELECT supplier_code from b2b_summary.pomain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('supplier_code');

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_po_2'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_po_2'")->row('query');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==',
                'Cookie: userLocale=en_US; JSESSIONID=5221928B4926B138CB796C763F550CB4'
            ),
        ));
            
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl,CURLINFO_HTTP_CODE);

        if($httpcode == '400')
        {
            print_r('Failed to load PDF Report. Please refresh the page or contact our support.'); 
            die;
        }

        header('Content-type:application/pdf');
        header('Content-Disposition: inline; filename='.$filename.'.pdf');
        echo $response; 

        curl_close($curl); 


    }

    public function update_hide_status()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $refno = $this->input->post('refno');
            //$loc = $this->input->post('loc');
            $reason_hide = $this->input->post('reason_hide');

            //print_r($reason_hide); die;
            $description = $this->db->query("SELECT portal_description FROM status_setting WHERE type = 'hide_po_filter' AND code = '$reason_hide' LIMIT 1")->row('portal_description');
            // echo $reason.$common_code;die;

            $insert_log = $this->db->query("INSERT INTO  userlog SELECT UPPER(REPLACE(UUID(),'-','')) AS trans_guid , '" . $_SESSION['customer_guid'] . "' , 'Panda B2B' , 'PO' , '$refno' , 'status' , '' , '$reason_hide' , NOW() , '" . $_SESSION['userid'] . "'");

            $update_hide_doc = $this->db->query("UPDATE b2b_summary.pomain_info set status = '$reason_hide',hide_reason = '$description' where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");

            $error = $this->db->affected_rows();

            if ($error > 0) {

            $data = array(
                'para1' => 'true',
                'msg' => 'Update Successfully.',
            );
            echo json_encode($data);
            exit();
            } else {
            $data = array(
                'para1' => 'false',
                'msg' => 'Error to update.',
            );
            echo json_encode($data);
            exit();
            }
        } else {
            redirect('#');
        }
    }

    public function update_document_status()
    {
        //$this->panda->get_uri();
        //$json_data = file_get_contents("php://input");
        //$json_data = json_decode($json_data);
        $refno = $this->input->post('trans');
        $type = $this->input->post('type');

        $customer_guid = '8D5B38E931FA11E79E7E33210BD612D3';
        $user_guid = $_SESSION['user_guid'];
        $from_module = $_SESSION['frommodule'];

        $refno_array = explode(",",$refno);
        print_r($refno_array); die; #TPGPO22011387 TPGPO21121043
        foreach($refno_array as $row2)
        {
            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {               
                $this->db->query("UPDATE b2b_summary.pomain_info set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status IN ('','viewed') ");

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'printed_po'
                , '$from_module'
                , '$row2'
                , now()
                ");
            }
        }

    }
} // nothing after this
