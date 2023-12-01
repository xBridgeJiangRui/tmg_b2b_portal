<?php
class b2b_gr_download extends CI_Controller
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
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
        $this->jasper_path = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'),'web','general_doc','jasper_document_path','GDJDP');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $setsession = array(
                'frommodule' => 'b2b_gr_download',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            // if(isset($_SESSION['from_other']) == 0 )
            // {
                $setsession = array(
                    'gr_dl_loc' => $_REQUEST['loc'],
                );
                $this->session->set_userdata($setsession);

                redirect('b2b_gr_download/gr_dl_list');
                $this->panda->get_uri();
            // }
            // else
            // {
            //     if($_REQUEST['status'] == '')
            //     {
            //         unset($_SESSION['from_other']);
            //         redirect('b2b_gr?loc='.$_REQUEST['loc']);
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

    public function gr_dl_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $check_loc = $_SESSION['gr_dl_loc'];
            
            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            $data = array(
                'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'GR_FILTER_STATUS' order by code='ALL' desc, code asc"),
                'period_code' => $this->db->query("SELECT period_code from lite_b2b.list_period_code"),
                'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'"),
            );

            $data_footer = array(
                'activity_logs_section' => 'gr'
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('gr/b2b_gr_dl', $data);
            //$this->load->view('general_modal', $data);
            $this->load->view('footer', $data_footer);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function gr_datatable()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $doc = 'gr_table';
            $po_ref_no = $this->input->post('po_ref_no');
            $ref_no = $this->input->post('ref_no');
            $status = $this->input->post('status');
            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            // $exp_datefrom = $this->input->post('exp_datefrom');
            // $exp_dateto = $this->input->post('exp_dateto');
            // $period_code = $this->input->post('period_code');
            $type = $this->input->post('type');
            $customer_guid = $_SESSION['customer_guid'];
            $query_loc = $_SESSION['query_loc'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();
            $hq_branch_code_array = array();
            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            if (in_array($_SESSION['gr_dl_loc'], $hq_branch_code_array)) {
                $loc = $query_loc;
            } else {
                $loc = "'" . $_SESSION['gr_dl_loc'] . "'";
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

            if ($po_ref_no == '') {
                $po_ref_no_in = '';
            } else {
                $po_ref_no_in = " AND a.PORefNo LIKE '%" . $po_ref_no . "%' ";
            }

            if ($status == '') {
                $status_in = " AND a.status = '' ";
            } elseif ($status == 'READ') {
                $status_in = " AND a.status IN ('printed', 'viewed') ";
            } elseif ($status == 'ALL') {
                $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'GR_FILTER_STATUS'");

                foreach ($get_stat->result() as  $row) {
                    $check_stat[] = $row->code;
                }

                foreach ($check_stat as &$value) {
                    $value = "'" . trim($value) . "'";
                }
                $check_status = implode(',', array_filter($check_stat));
                $status_in = " AND a.status IN ($check_status) ";
            } 
            else if ($status == 'geinv')
            {
                $status_in = " AND a.status IN ('','printed','viewed') ";
            }
            else {
                $status_in = " AND a.status = '$status' ";
            }

            if ($datefrom == '' || $dateto == '') {
                $doc_daterange_in = '';
            } else {
                $doc_daterange_in = " AND a.grdate BETWEEN '$datefrom' AND '$dateto' ";
            }

            // if ($exp_datefrom == '' || $exp_dateto == '') {
            //     $exp_daterange_in = '';
            // } else {
            //     $exp_daterange_in = " AND a.docdate BETWEEN '$exp_datefrom' AND '$exp_dateto' ";
            // }

            // if ($period_code == '') {
            //     $period_code_in = '';
            // } else {
            //     $period_code_in = " AND LEFT(a.grdate, 7) = '$period_code'";
            // }

            // if (!in_array('VGR', $_SESSION['module_code'])) {
            //     $module = 'gr_download_child';
            // } else {
            //     $module = 'gr_child';
            // }

            $query_count = "SELECT zzz.refno FROM ( SELECT 
            a.customer_guid,
            a.refno,
            a.porefno,
            IFNULL(b.refno,'') AS grda_status,
            DATE_FORMAT(a.grdate, '%Y-%m-%d %a') AS grdate,
            a.loc_group,
            a.dono,
            a.invno,
            a.supplier_code,
            a.supplier_name,
            a.cross_ref,
            c.einvno,
            c.inv_date as einvdate
            FROM
            b2b_summary.grmain_info  AS a FORCE INDEX (customer_guid)
            LEFT JOIN  b2b_summary.grmain_dncn_info AS b
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid 
            LEFT JOIN b2b_summary.einv_main c 
                ON a.refno = c.refno 
                AND a.customer_guid = c.customer_guid 
            LEFT JOIN b2b_summary.grmain AS d
                ON a.refno = d.refno
                AND a.customer_guid = d.customer_guid
            WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' 
            AND a.loc_group in ($loc)
                AND a.in_kind = 0  
                $module_code_in 
                $status_in 
                $doc_daterange_in
                $po_ref_no_in
                $ref_no_in
                GROUP BY a.refno
            ) zzz
            ";
            //print_r($query_count); die;
            $query = "SELECT 
            a.customer_guid,
            a.refno,
            a.porefno,
            IFNULL(b.refno,'') AS grda_status,
            a.loc_group,
            a.dono,
            a.invno,
            DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
            DATE_FORMAT(a.grdate, '%Y-%m-%d %a') AS grdate,
            a.supplier_code,
            a.supplier_name,
            a.total,
            a.gst_tax_sum,
            a.total_include_tax,
            a.cross_ref,
            IF(a.status = '', 'NEW', a.status) AS status,
            c.einvno,
            c.inv_date as einvdate
            FROM
            b2b_summary.grmain_info  AS a FORCE INDEX (customer_guid)
            LEFT JOIN b2b_summary.grmain_dncn_info AS b
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid 
            LEFT JOIN b2b_summary.einv_main c 
                ON a.refno = c.refno 
                AND a.customer_guid = c.customer_guid 
            WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' 
            AND a.loc_group in ($loc)
                AND a.in_kind = 0  
                $module_code_in 
                $status_in 
                $doc_daterange_in
                $po_ref_no_in
                $ref_no_in
            GROUP BY a.refno"; 
            
            //AND a.loc_group in ($loc)  -- up to production need change this
            //print_r($query); die;
            $sql = "SELECT * FROM (
                $query
            ) zzz ";
            //echo $this->db->last_query();die;
            
            $query = $this->Datatable_model->datatable_main($sql, $type, $doc);
            //echo $this->db->last_query(); die;
            $fetch_data = $query->result();
            $data = array();
            if (count($fetch_data) > 0) {
                foreach ($fetch_data as $row) {
                    $tab = array();

                    $tab['refno'] = $row->refno;
                    $tab['grda_status'] = "<a href=" . site_url('b2b_grda/grda_child?trans=' . $row->grda_status . '&loc=' . $_SESSION['gr_dl_loc']) . ">" . $row->grda_status . "</a>";
                    $tab['porefno'] = $row->porefno;
                    $tab['loc_group'] = $row->loc_group;
                    $tab['supplier_code'] = $row->supplier_code;
                    $tab['supplier_name'] = $row->supplier_name;
                    $tab['grdate'] = $row->grdate;
                    $tab['docdate'] = $row->docdate;
                    $tab['dono'] = $row->dono;
                    $tab['invno'] = $row->invno;
                    $tab['einvno'] = $row->einvno;
                    $tab['einvdate'] = $row->einvdate;
                    $tab['cross_ref'] = $row->cross_ref;
                    $tab['total'] = "<span class='pull-right'>" . number_format($row->total, 2) . "</span>";
                    $tab['gst_tax_sum'] = "<span class='pull-right'>" . number_format($row->gst_tax_sum, 2) . "</span>";
                    $tab['total_include_tax'] = "<span class='pull-right'>" . number_format($row->total_include_tax, 2) . "</span>";
                    $tab['status'] = $row->status;
                    $tab['button'] = "<a href=" . site_url('b2b_gr_download/gr_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['gr_dl_loc'] . "&accpt_gr_status=" . $row->status . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    //$tab["button"] = "<a href=" . site_url('b2b_gr/gr_child') . "?trans=" . $row->refno . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    $tab['box'] = '<input type="checkbox" class="data-check" value="' . $row->refno . '" grda_status="' . $row->grda_status . '" doc_status="' . $row->status . '" refno="' . $row->refno . '" invno="' . $row->invno . '">';

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

    public function gr_child()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            if (isset($_REQUEST['accpt_gr_status'])) {
                $accpt_gr_status = $_REQUEST['accpt_gr_status'];
            } else {
                // $accpt_po_status = $_REQUEST['accpt_po_status'];
                // if($accpt_po_status == '' || $accpt_po_status == null)
                // {
                $accpt_gr_status = 'NEW';
                // }
            }
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $this->panda->get_uri();
            $database_production = 'b2b_summary';

            $grmain_status = $this->db->query("SELECT status from $database_production.grmain where refno = '$refno' AND customer_guid = '$customer_guid'")->row('status');
            if ($grmain_status != 'CONFIRM_EINV') {
                if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                    $this->db->query("UPDATE b2b_summary.grmain set status = 'viewed' where status = '' and customer_guid = '" . $_SESSION['customer_guid'] . "' and refno = '$refno' ");

                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'viewed_grn'
                    , '$from_module'
                    , '$refno'
                    , now()
                    ");
                };

                // $get_header_detail = $this->db->query("SELECT a.`customer_guid`
                // , a.`status`
                // , a.`RefNo`
                // , a.`Location`
                // , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
                // , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
                // , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
                // , a.`GRDate`
                // , a.`Code`
                // , a.`Name`
                // , a.`consign`
                // , a.Total
                // , a.gst_tax_sum
                // , a.total_include_tax
                // FROM b2b_summary.grmain AS a 
                // LEFT JOIN b2b_summary.grmain_proposed AS b 
                // ON a.refno = b.refno 
                // AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "'");

                $get_header_detail = $this->db->query("SELECT a.`customer_guid`
                , a.`status`
                , a.`RefNo`
                , a.`loc_group`
                , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
                , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
                , a.`InvNo` as ori_inv_no
                , a.`DocDate` AS DocDate
                , a.`GRDate`
                , a.`supplier_code`
                , a.`supplier_name`
                , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].consign')) AS CHAR ) AS consign
                , JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Total')) AS Total
                , JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].gst_tax_sum')) AS gst_tax_sum
                , JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].total_include_tax')) AS total_include_tax
                , IF(c.einvno IS NOT NULL,c.einvno,IF(b.invno IS NULL,a.invno,b.invno)) as einvno
                , IF(c.inv_date IS NOT NULL,c.inv_date,IF(b.docdate IS NULL,CURDATE(),b.docdate)) as einv_date
                , JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].subtotal1')) AS subtotal1
                , a.cross_ref
                , IFNULL((JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].total_include_tax')) - SUM(d.`VarianceAmt`)), JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Total')) ) AS after_amount
                , a.gr_json_report
                FROM b2b_summary.grmain_info AS a 
                LEFT JOIN b2b_summary.grmain_proposed AS b 
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid 
                LEFT JOIN b2b_summary.einv_main c ON a.refno = c.refno AND a.customer_guid = c.customer_guid 
                LEFT JOIN b2b_summary.`grmain_dncn_info` d ON a.`RefNo` = d.`RefNo` AND a.`customer_guid` = d.`customer_guid`
                where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "'
                GROUP BY a.refno");

                //child data from rest
                // $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
                // $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
                // //  echo $to_shoot_url ;die;
                // $ch = curl_init($to_shoot_url);
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                // $response = curl_exec($ch);
                //echo var_dump($response);die;
                // making sure got header baru show child to gen inv
                if ($get_header_detail->num_rows() > 0) {
                    // echo $to_shoot_url;die;
                    $response = $get_header_detail->row('gr_json_report');
                    $get_child_detail = json_decode($response, true)['query2'];

                    // $get_child_detail = json_decode(file_get_contents($to_shoot_url), true);
                    // print_r($get_child_detail);die;
                    $child_result_validation = $get_child_detail[0]['line'];
                    // print_r($child_result_validation);die;

                    if ($child_result_validation == 'No Records Found') {
                        $get_child_detail = array();
                        $child_result_validation = '0';
                        $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E Invoice is currently not available. Please refresh this page.');
                    } else {
                        $get_child_detail = json_decode($response, true)['query2'];;
                        $child_result_validation = $get_child_detail[0]['line'];
                        // print_r($child_result_validation);die;
                    }
                } else {
                    $get_child_detail = array();
                    $child_result_validation = '0';
                    $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E Invoice is currently not available.');
                    //$this->session->set_flashdata('warning', 'Connection fail at customer server. Child Data Not Found.'); 
                }
                // child data
                // echo var_dump($get_child_detail); 
            } else {
                echo 'status <> ""';
            }

            $set_row = $this->db->query("SET @row=0");
            $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row := @row + 1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending') AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type, ext_doc1, IFNULL(ext_date1, CURDATE()) AS ext_date1, IFNULL(b.posted, '0') AS posted, a.status, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_report`, '$.query1[0].location' ) ) AS CHAR(10) ) AS location, a.RefNo, a.VarianceAmt, a.Created_at, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].Created_by' ) ) AS CHAR ) AS Created_by, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].Updated_at' ) ) AS CHAR ) AS Updated_at, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].Updated_by' ) ) AS CHAR ) AS Updated_by, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].hq_update' ) ) AS CHAR ) AS hq_update, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].EXPORT_ACCOUNT' ) ) AS CHAR ) AS EXPORT_ACCOUNT, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].EXPORT_AT' ) ) AS CHAR ) AS EXPORT_AT, CAST( JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].EXPORT_BY' ) ) AS CHAR ) AS EXPORT_BY, a.transtype, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].share_cost' ) ) AS share_cost, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].gst_tax_sum' ) ) AS gst_tax_sum, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].gst_adjust' ) ) AS gst_adjust, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].gl_code' ) ) AS gl_code, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].tax_invoice' ) ) AS tax_invoice, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].ap_sup_code' ) ) AS ap_sup_code, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].refno2' ) ) AS refno2, JSON_UNQUOTE ( JSON_EXTRACT ( a.`grda_json_info`, '$.Grmain_dncn[0].rounding_adj' ) ) AS rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc FROM b2b_summary.grmain_dncn_info AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '".$_SESSION[ 'customer_guid' ] ."' AND refno = '$refno') AS b ON a.refno = b.refno AND a.transtype = b.type WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid'] ."' ORDER BY transtype ASC");

            //echo $this->db->last_query();die;
            //$check_e_cn = $this->db->query("SELECT * from ecn_main where customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno'");      

            //$check_scode = $this->db->query("SELECT supplier_code from b2b_summary.grmain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('supplier_code');
            //$check_scode = str_replace("/", "+-+", $check_scode);

            // if (isset($_REQUEST['fmodule'])) {
            //     $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_gr'");
            // } else {
            //     $parameter = $this->db->query("SELECT * from menu where module_link = '" . $_SESSION['frommodule'] . "'");
            // }
            // $type = $parameter->row('type');
            // $code = $check_scode;

            // $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '" . $_SESSION['frommodule'] . "'")->row('query');

            // $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            // // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

            // $file_config_final_path = $this->file_config_b2b->file_path($customer_guid, 'web', 'general_doc', 'main_path', 'GDMP');
            // // $test = $this->file_config_b2b->file_path();die;
            // // echo $test;die;
            // $filename = $file_config_final_path . '/' . $replace_var . '.pdf';
            // //echo $replace_var;die;
            // $file_headers = @get_headers($filename);

            // // find invoice folder in client folder

            // if (!file_exists("." . $virtual_path . "/invoice")) {
            //     $oldmask = umask(0);
            //     mkdir("." . $virtual_path . "/invoice", 0777, true);
            //     umask($oldmask);
            // };


            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");
            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'GRN' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");
            // check if einv has open
            $check_e_inv = $this->db->query("SELECT * from b2b_summary.einv_main where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");

            if ($check_e_inv->num_rows() == '1') {
                $open_panel2 = 'collapsed-box';
                $open_panel3 = 0;
                $version = $check_e_inv->row('revision');
                //$check_e_inv_c = $this->db->query("SELECT * from b2b_summary.einv_child where einv_guid = '" . $check_e_inv->row('einv_guid') . "'");
                //$check_einv_filepath = base_url($virtual_path . '/invoice/' . 'B2B_' . $_REQUEST['trans'] . '.pdf');
                //print_r($check_einv_filepath); die;
            } else {
                $open_panel2 = '';
                $open_panel3 = 1;
                $version = '0';
                //$check_e_inv_c = '';
                //$check_einv_filepath = '';
            };

            // if (isset($_REQUEST['edit'])) {
            //     $hidden_text = 'text';
            //     $edit_header_url = site_url('panda_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc']);
            // } else {
            //     $hidden_text = 'hidden';
            //     $edit_header_url = site_url('panda_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc'] . '&edit');
            // }

            if ($get_DN_detail->num_rows() == '1') {
                $show_generate_ecn = '2';
                $show_ecn = '1';
                $show_grda_pdf = '1';

                //grda details
                // $grda_parameter  = $this->db->query("SELECT * from menu where module_link = 'panda_grda'");
                // $grda_type = $grda_parameter->row('type');
                // $grda_code = $check_scode;

                // $grda_replace_var  = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$grda_type'), 'code', '$grda_code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_grda'")->row('query');

                // $grda_virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

                // $grda_filename = base_url($grda_virtual_path . '/' . $grda_replace_var . '.pdf');

                // $grda_file_headers = @get_headers($grda_filename);
            } else {
                $show_generate_ecn = '1';
                $show_ecn = '0';
                $show_grda_pdf = '0';

                // $grda_filename = 'not valid';
                // $grda_file_headers = 'not valid';
                // $grda_virtual_path = 'not valid';
            };

            $check_grn_based_on_pocost = $this->db->query("SELECT * from b2b_summary.supcus where customer_guid = '" . $_SESSION['customer_guid'] . "' and code = '$check_scode'");

            if ($check_grn_based_on_pocost->row('grn_baseon_pocost') == '1') {
                //paybyinvoice is got grda,no need to do ecn
                $paybyinvoice_got_grda = '1';
            } else {
                //meaning pay by grn, need do e invoice
                $paybyinvoice_got_grda  = '0';
            }

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Goods Received',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'check_header' => $get_header_detail,
                'child_result_validation' => $child_result_validation,
                'check_child' => $get_child_detail,
                'set_admin_code' => $set_admin_code,
                'open_panel2' => $open_panel2,
                'open_panel3' => $open_panel3,
                'version' => $version,
                // 'check_e_inv_c' => $check_e_inv_c,
                'get_DN_detail' => $get_DN_detail,
                'hidden_text' => $hidden_text,
                // 'edit_header_url' => $edit_header_url,
                'show_generate_ecn' => $show_generate_ecn,
                'show_ecn' => $show_ecn,
                'paybyinvoice_got_grda' => $paybyinvoice_got_grda,
                // 'check_einv_filepath' => $check_einv_filepath,
                //'aaa' => $check_grn_based_on_pocost->result(),
                'show_grda_pdf' => $show_grda_pdf,
                // 'grda_filename' => $grda_filename,
                // 'grda_file_headers' => $grda_file_headers,
                // 'grda_virtual_path' => $grda_virtual_path,
                'accpt_gr_status' => $accpt_gr_status,
                'request_link_gr' => site_url('B2b_gr_download/gr_report?refno='.$refno),
                'request_link_grda' => site_url('B2b_gr_download/grda_report?refno='.$refno),
            );



            $this->load->view('header');
            $this->load->view('gr/b2b_gr_dl_pdf', $data);
            $this->load->view('general_modal', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function fetch_e_invoice_pdf()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $customer_guid = $_SESSION['customer_guid'];
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            // $refno = 'MRGR19080053';

            // $header = $this->db->query("SELECT * FROM einv_main WHERE refno = '$refno' ");

            // $child_info = $this->db->query("SELECT * FROM einv_child WHERE einv_guid = 'D7A6BF4A8F5611EA8EF0E4E7491CBF27' order by line asc");

            // HUGH 2019-04-25

            $refno = $_REQUEST['trans'];

            $header = $this->db->query("SELECT * FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $einv_guidd = $this->db->query("SELECT einv_guid FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ")->row('einv_guid');

            $child_info = $this->db->query("SELECT * FROM b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by `line` asc");

            //$haha = $this->load->view('print/invoice_pdf', $data, true);
            $this->load->library('Pdf_invoice_json');
            $pdf = new Pdf_invoice_json('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetTitle($refno);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor('B2B');
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, 200, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont('helvetica', '', $font_size, '', 'default', true);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            /*$pdf->setHeaderFont(array('helvetica', '', 12));
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetHeaderData('logo.jpg', 85, $name .'('.$reg_no.')', $add1. chr(10) .$add2. chr(10) .$add3. chr(10)."TEL: ".$tel." Email: ".$email );*/
            /*$pdf->setFooterData(array(0,64,0), array(0,64,128));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);*/
            $pdf->AddPage('L', 'A4');;
            // ob_start();



            $html = '  
            <table border="1">
            <tr style ="text-align:center;">
              
             
            <th style="width:23.32%;"><b>Barcode / Itemcode</b></th>
            
            <th style="width:23.66%;"><b>Description</b></th>
            <th style="width:6.66%;"><b>Packsize</b></th>
            <th style="width:6.66%;"><b>Unit Price</b></th>
            <th style="width:6.66%;"><b>Discount Description</b></th>
            <th style="width:6.66%;"><b>Discount Amt</b></th>
            <th style="width:6.66%;"><b>Total Bill Disc Prorated</b></th>
            
            <th style="width:6.66%;"><b>Quantity</b></th>
            <th style="width:6.66%;"><b>Total Amt Exclude Tax</b></th>
            <th style="width:6.66%;"><b>Total Amount Include Tax</b></th>
              

            </tr>
            </table>';

            $pdf->writeHTML($html, true, false, true, false, '');


            foreach ($child_info->result() as $row) {

                if ($row->item_disc_amt == 0) {
                    $row->item_disc_amt = '';
                }

                if ($row->item_disc_amt == 0) {
                    $row->total_bill_disc_prorated = null;
                }

                $html = '  
            <table style="border-bottom: 1px solid black;">
            <tr style="" >

             
            <td style="width:23.32%;text-align:left">' . $row->barcode . '<br>' . $row->itemcode . '</td>
            
            <td style="width:23.66%"> ' . $row->description . '</td>
            <td style="width:6.66%;text-align:right">' . $row->packsize . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->unit_price_before_disc, 2) . '</td>
            <td style="width:6.66%;text-align:right">' . $row->item_discount_description . '</td>
            <td style="width:6.66%;text-align:right">' . $row->item_disc_amt . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->total_bill_disc_prorated, 2) . '</td>

            <td style="width:6.66%;text-align:right">' . $row->qty . ' ' . $row->uom . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->total_amt_incl_tax, 2) . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->total_amt_excl_tax, 2) . '</td>
              

            </tr>
            </table>';

                $pdf->writeHTML($html, true, false, true, false, '');
            }

            $html = ' <div class="col-xs-12 table-responsive">
            <table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
            <tr>
            <td style="width: 55%;text-align: left">
                    
                   <table cellspacing="0" cellpadding="0">
                     
                    <tbody>
                      
                      <tr>
                        
                        <td style="border: 1px solid black;"> Remark: </td>

                      </tr>

                      <tr>
                        
                        <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">The parties hereby agree that the actual physical amount of goods received will be reflected in the Goods Received Note(GRN) not withstanding validation of the supplier invoice. The parties also hereby agree that in the event the total purchase price in the GRN and the supplier Invoice different amount, we shall pay the lower amount of the two.
                          
                        </td>

                      </tr>

                    </tbody>

            </table>
             
               
                   
              
            </td>
            <td style="width: 45%;">


                    <table id="right-table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                    <thead>
                            <tr>
                            
                               

                                <th style="width:100%;text-align: center;border: 1px solid black;" colspan = "2"><b>Document Summary</b></th>
                           
                           

                            </tr>
                    </thead>
                    <tbody> 
                            <tr>
                              
                              

                              <td style="width:70%;text-align: center;border: 1px solid black;" nowrap="">Total Before Tax & Discount</td>

                              <td style="width:30%;text-align: right;border: 1px solid black;" nowrap="">' . number_format($header->row('total_excl_tax'), 2) . '</td>

                            </tr>

                            <tr>

                       
                              <td style="text-align: center; border: 1px solid black;" >Item Tax Amount</td>
                              <td style="text-align: right; border: 1px solid black;" >' . number_format($header->row('tax_amount'), 2) . '</td>


                            </tr>

                            <tr>

                              
                              <td style="text-align: center; border: 1px solid black;" >Total Amount Include Tax</td>
                              <td style="text-align: right; border: 1px solid black;" >' . number_format($header->row('total_incl_tax'), 2) . '</td>



                            </tr>


                    </tbody>
                  
                    </table>


              </td>
            </tr>

                </table>
              </div>';

            $pdf->writeHTML($html, true, false, true, false, '');


            /*$html = ' <div style="">
            Important Note : Below is the list of item to be returned to you. Kindly arrange your transport team to collect from us within 14 days from the date hereof.<br>

            Failure which we reserve the right to dispose the goods and debit your account accordingly
              </div><br>';

                $pdf->writeHTML($html, true, false, true, false, ''); */



            $pdf->lastPage();

            // ---------------------------------------------------------
            ob_end_clean();

            $pdf->Output('B2B_' . $refno . '.pdf', 'I');

            // if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            //    // echo 'This is a server using Windows!';die;
            //     $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'/lite_panda_b2b'.$virtual_path.'/invoice/B2B_'.$refno.'.pdf', 'F');  
            // } 
            // else {
            //     //echo 'This is a server not using Windows!';die;
            //     $pdf->Output($_SERVER['DOCUMENT_ROOT'] .$virtual_path.'/invoice/B2B_'.$refno.'.pdf', 'F');  
            // }
            //window 
            //$pdf->Output($_SERVER['DOCUMENT_ROOT'] .'/lite_panda_b2b/invoice/B2B_'.$refno.'.pdf', 'F');  
            //linux 

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    } //close function 

    public function gr_report()
    {
        $refno = $_REQUEST['refno'];
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_PO/main_jrxml.pdf?refno=".$refno; // po
        $url = $this->jasper_ip . $this->jasper_path ."/Backend_GRN/gr_supplier_copy.pdf?refno=".$refno; // grn
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_GRN/GRDA.pdf?refno=SGPGR22040255"; // grda
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_Promotion/promo_claim_inv.pdf?refno=BT1PCI19090033"; // PCI
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_DIncentives/display_incentive_report.pdf?refno=RBDI20010018"; // DI
        //print_r($url); die;
        $check_code = $this->db->query("SELECT supplier_code from b2b_summary.grmain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('supplier_code');

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_gr'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_gr'")->row('query');

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

        header('Content-type:application/pdf');
        header('Content-Disposition: inline; filename='.$filename.'.pdf');
        echo $response; 

        curl_close($curl); 


    }

    public function grda_report()
    {
        $refno = $_REQUEST['refno'];
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_PO/main_jrxml.pdf?refno=".$refno; // po
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_GRN/gr_supplier_copy.pdf?refno=BLPGR22030862"; // grn
        $url = $this->jasper_ip . $this->jasper_path ."/Backend_GRN/GRDA.pdf?refno=".$refno; // grda
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_Promotion/promo_claim_inv.pdf?refno=BT1PCI19090033"; // PCI
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_DIncentives/display_incentive_report.pdf?refno=RBDI20010018"; // DI
        //print_r($url); die;
        $check_code = $this->db->query("SELECT b.supplier_code from b2b_summary.grmain_dncn_info a INNER JOIN b2b_summary.grmain_info b ON a.refno = b.refno where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY a.refno")->row('supplier_code');

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_grda'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_grda'")->row('query');

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

        header('Content-type:application/pdf');
        header('Content-Disposition: inline; filename='.$filename.'.pdf');
        echo $response; 

        curl_close($curl); 


    }

    public function supplier_check()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            function odd($var)
            {
                return ($var & 1);
            }

            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            if ($_SESSION['customer_guid'] == '') {
                $this->session->set_flashdata('warning', 'Session Expired! Please relogin');
                redirect('#');
            };

            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('H_refno');
            $loc = $this->input->post('location');
            $user_guid = $_SESSION['user_guid'];

            $check_einvno = $this->input->post('H_invno');

            $check_if_exists_einv = $this->db->query("SELECT * FROM b2b_summary.einv_main WHERE refno != '$refno' AND customer_guid = '$customer_guid' AND invno = '$check_einvno'");
            // echo $this->db->last_query();die;
            if ($check_if_exists_einv->num_rows() > 0) {
                // echo 'invoice number duplicate';
                $check_if_exists_einv2 = $this->db->query("SELECT * FROM b2b_summary.grmain_info WHERE refno = '$refno' AND customer_guid = '$customer_guid'");
                $check_if_exists_einv2_code = $check_if_exists_einv2->row('supplier_code');
                $check_if_exists_einv2_supcode = $this->db->query("SELECT b.* FROM b2b_summary.supcus a LEFT JOIN b2b_summary.`supcus` b ON a.`AccountCode` = b.`AccountCode` AND a.`customer_guid` = b.customer_guid WHERE a.code = '$check_if_exists_einv2_code' AND a.customer_guid = '$customer_guid' GROUP BY b.`customer_guid`,b.code");
                $check_if_exists_einv2_supcode_string = '';
                foreach ($check_if_exists_einv2_supcode->result() as $row) {
                    $check_if_exists_einv2_supcode_string .= "'" . $row->Code . "',";
                }
                $check_if_exists_einv2_supcode_string2 = rtrim($check_if_exists_einv2_supcode_string, ',');
                echo rtrim($check_if_exists_einv2_supcode_string, ',') . 'sdsd<br>';
                $check_if_exists_einv3 = $this->db->query("SELECT b.* FROM b2b_summary.einv_main a INNER JOIN b2b_summary.grmain_info b ON a.`customer_guid` = b.`customer_guid` AND a.refno = b.refno WHERE a.refno != '$refno' AND a.customer_guid = '$customer_guid' AND a.invno = '$check_einvno' AND b.supplier_code IN($check_if_exists_einv2_supcode_string2)");
                if ($check_if_exists_einv3->num_rows() > 0) {
                    $this->session->set_flashdata('warning',  'Invoice number repeat');
                    redirect('b2b_gr/gr_child?trans=' . $refno . '&loc=' . $loc);
                }
                // echo $this->db->last_query();die;
            }
            // print_r($check_if_exists_einv->result());
            // die;

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            $supcheck = $this->input->post("supcheck2[]");
            // $check_loop = array_filter($supcheck, "odd"); 
            $check_loop = $supcheck;
            // print_r($check_loop);die;
            if (empty($check_loop)) {
                $this->session->set_flashdata('warning',  'Please confirm item  received in the GR list.');
                redirect('b2b_gr/gr_child?trans=' . $refno . '&loc=' . $loc);
            }

            $check_einv_header = $this->db->query("SELECT * from b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'");
            if ($check_einv_header->num_rows() == 0) {
                $H_refno = $this->input->post('H_refno');
                $H_invno = $this->input->post('H_invno');
                $H_dono = $this->input->post('H_dono');
                $H_inv_date = $this->input->post('H_docdate');
                $H_gr_date = $this->input->post('H_grdate');
                $H_total_excl_tax = $this->input->post('H_subtotal1');
                $H_tax_amount = $this->input->post('H_gst_tax_sum');
                $H_total_incl_tax = $this->input->post('H_total');


                $data1 = array(
                    'einv_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    'customer_guid' => $customer_guid,
                    'refno' => $refno,
                    'einvno' => addslashes($H_invno),
                    'invno' => addslashes($H_invno),
                    'dono' => addslashes($H_dono),
                    'inv_date' => $H_inv_date,
                    'gr_date' => $H_gr_date,

                    'total_excl_tax' => $H_total_excl_tax,
                    'tax_amount' => $H_tax_amount,
                    'total_incl_tax' => $H_total_incl_tax,
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' =>  $this->session->userdata('userid'),
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $this->session->userdata('userid'),
                );

                $this->db->insert('b2b_summary.einv_main', $data1);

                $this->db->query("UPDATE b2b_summary.grmain_info set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");

                $this->db->query("UPDATE b2b_summary.grmain_info set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");

                $get_einv_guid = $this->db->query("SELECT einv_guid from b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'")->row('einv_guid');

                $this->db->query("UPDATE b2b_summary.grmain_info a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_excl_tax =  CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Subtotal1')) AS CHAR(20) ) WHERE CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Subtotal1')) AS CHAR(20) ) <> b.total_excl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");

                $this->db->query("UPDATE b2b_summary.grmain_info a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_incl_tax = CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].total_include_tax')) AS CHAR(20) ) WHERE CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].total_include_tax')) AS CHAR(20) ) <> b.total_incl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");
            } else {
                $this->session->set_flashdata('warning',  'Invoice Generated, Cannot Regenerate');
                redirect('b2b_gr/gr_child?trans=' . $refno . '&loc=' . $loc);

                $query_data =  $this->db->query("SELECT * from b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'");
                $revision = $query_data->row('revision') + 1;
                $get_einv_guid = $query_data->row('einv_guid');
                $H_total_excl_tax = $this->input->post('H_total');
                $H_tax_amount = $this->input->post('H_gst_tax_sum');
                $H_total_incl_tax = $this->input->post('H_total_include_tax');

                $data_main = array(
                    'revision' => $revision,
                    'total_excl_tax' => $H_total_excl_tax,
                    'tax_amount' => $H_tax_amount,
                    'total_incl_tax' => $H_total_incl_tax,
                );

                $table = 'einv_main';
                $col_guid = 'einv_guid';
                $guid = $get_einv_guid;

                $this->General_model->update_data($table, $col_guid, $guid, $data_main);
                $query_child = $this->db->query("SELECT * from b2b_summary.einv_child where einv_guid = '$get_einv_guid'");

                $table_archive = 'b2b_archive.einv_child';
                foreach ($query_child->result() as $row) {
                    $data_archive  =  array(
                        'child_guid' =>  $row->child_guid,
                        'einv_guid' =>  $row->einv_guid,
                        'line' => $row->line,
                        'itemtype' => $row->itemtype,
                        'itemlink' => $row->itemlink,
                        'itemcode' => $row->itemcode,
                        'barcode' => $row->barcode,
                        'description' => $row->description,
                        'packsize' => $row->packsize,
                        'qty' => $row->qty,
                        'uom' => $row->uom,
                        'unit_price_before_disc' => $row->unit_price_before_disc,
                        'item_discount_description' => $row->item_discount_description,
                        'item_disc_amt' => $row->item_disc_amt,
                        'total_bill_disc_prorated' => $row->total_bill_disc_prorated,
                        'total_amt_excl_tax' => $row->total_amt_excl_tax,
                        'total_tax_amt' => $row->total_tax_amt,
                        'total_amt_incl_tax' => $row->total_amt_incl_tax,
                        'checked' => $row->checked,
                        'checked_at' =>  $row->checked_at,
                        'checked_by' => $row->checked_by,
                        'created_at' => $row->created_at,
                        'created_by' => $row->created_by,
                        'updated_at' => $row->updated_at,
                        'updated_by' => $row->updated_by,
                        'revision' => $query_data->row('revision'),
                    );

                    $this->db->insert($table_archive, $data_archive);
                }
            }
            $this->db->query("DELETE FROM b2b_summary.einv_child where einv_guid = '$get_einv_guid'");
            $itemcode = $this->input->post("itemcode[]");
            $supcheck = $this->input->post("supcheck2[]");
            $line = $this->input->post("line[]");
            $barcode = $this->input->post("barcode[]");
            $description = $this->input->post("description[]");
            $packsize = $this->input->post("packsize[]");
            $qty = $this->input->post("qty[]");
            $uom = $this->input->post("um[]");
            $unitprice = $this->input->post("unitprice[]");
            $disc_desc = $this->input->post("disc_desc[]");
            $discamt = $this->input->post("discamt[]");
            $unit_disc_prorate = $this->input->post("unit_disc_prorate[]");
            $unit_price_bfr_tax = $this->input->post("unit_price_bfr_tax[]");
            $totalprice = $this->input->post("totalprice[]");
            $gst_tax_amount = $this->input->post("gst_tax_amount[]");
            $gst_unit_total = $this->input->post("gst_unit_total[]");

            foreach ($check_loop as $i => $id) {
                $data[] =  [
                    'child_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    'einv_guid' => $get_einv_guid,
                    'line' => $line[$i],
                    'itemtype' => '',
                    /*'itemlink' => $itemcode[$i],*/
                    'itemcode' => $itemcode[$i],
                    'barcode' => $barcode[$i],
                    'description' => $description[$i],
                    'packsize' => $packsize[$i],
                    'qty' => $qty[$i],
                    'uom' => $uom[$i],
                    'unit_price_before_disc' => $unitprice[$i],
                    'item_discount_description' => $disc_desc[$i],
                    'item_disc_amt' => $discamt[$i],
                    'total_bill_disc_prorated' => $unit_disc_prorate[$i],
                    'total_amt_excl_tax' => $unit_price_bfr_tax[$i] * $qty[$i],
                    'total_tax_amt' => $gst_tax_amount[$i],
                    'total_amt_incl_tax' => $totalprice[$i],
                    'checked' => $supcheck[$i],
                    'checked_at' => $this->db->query("select now() as naw")->row('naw'),
                    'checked_by' => $this->get_username(),
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' => $this->get_username(),
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $this->get_username(),
                ];
            }

            $table = 'b2b_summary.einv_child';
            $this->db->insert_batch($table, $data);

            // $total_child = $this->db->query("SELECT round(sum(total_amt_excl_tax),2) as total_excl_tax , round(sum(total_tax_amt),2) as tax_amount, round(sum(total_amt_incl_tax),2) as total_incl_tax  from b2b_summary.einv_child where einv_guid = '$get_einv_guid' ");

            // $this->db->query("UPDATE b2b_summary.einv_main set total_excl_tax  = '".$total_child->row('total_excl_tax')."' , tax_amount = '".$total_child->row('tax_amount')."' ,total_incl_tax =  '".$total_child->row('total_incl_tax')."' where einv_guid = '$get_einv_guid' ");

            $header = $this->db->query("SELECT * FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $einv_guidd = $this->db->query("SELECT einv_guid FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid'")->row('einv_guid');

            $child_info = $this->db->query("SELECT * FROM b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by line asc");

            // HUGH 2019-04-25


            $from_module = $_SESSION['frommodule'];

            if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {

                // $this->db->query("UPDATE b2b_summary.grmain set status = 'EINV_GENERATED' where status = 'viewed' and customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno' ");

                $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'generate_inv'
                    , '$from_module'
                    , '$refno'
                    , now()
                    ");
            };
            // END 2019-04-25
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

        redirect('panda_gr/gr_child?trans=' . $refno . '&loc=' . $loc);
    }

    public function update_document_status()
    {
        $this->panda->get_uri();
        $refno = $this->input->post('trans');
        $type = $this->input->post('type');

        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid'];
        $from_module = $_SESSION['frommodule'];

        $refno_array = explode(",",$refno);

        foreach($refno_array as $row2)
        {
            if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                $this->db->query("UPDATE b2b_summary.grmain_info set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status IN ('','viewed') ");

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'printed_grn'
                , '$from_module'
                , '$row2'
                , now()
                ");
            }
            // redirect ($filename);
        }


    }
   
} // nothing after this
