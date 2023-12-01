<?php
class b2b_grda extends CI_Controller
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
        //$this->load->model('General_model');
        $this->load->model('Datatable_model');
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login() )
        {   
            //print_r($_SESSION['from_other']); die;
            $setsession = array(
                'frommodule' => 'b2b_grda',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            // if(isset($_SESSION['from_other']) == 0 )
            // {
                $setsession = array(
                    'grda_loc' => $_REQUEST['loc'],
                );
                $this->session->set_userdata($setsession);

                redirect('b2b_grda/grda_list');
                $this->panda->get_uri();
            // }
            // else
            // {
            //     if($_REQUEST['status'] == '')
            //     {
            //         unset($_SESSION['from_other']);
            //         redirect('b2b_grda?loc='.$_REQUEST['loc']);
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

    public function grda_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login() ) {
            $check_loc = $_SESSION['grda_loc'];
            
            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            $data = array(
                'grda_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'GRDA_FILTER_DOCTYPE' order by code='' desc, code asc"),
                'period_code' => $this->db->query("SELECT period_code from lite_b2b.list_period_code"),
                'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'"),
            );

            $data_footer = array(
                'activity_logs_section' => 'grda'
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('grda/b2b_grda_list_view', $data);
            //$this->load->view('general_modal', $data);
            $this->load->view('footer', $data_footer);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function grda_datatable()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $doc = 'grda_table';
            $grn_ref_no = $this->input->post('grn_ref_no');
            $ref_no = $this->input->post('ref_no');
            $status = $this->input->post('status');
            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            //$exp_datefrom = $this->input->post('exp_datefrom');
            //$exp_dateto = $this->input->post('exp_dateto');
            //$period_code = $this->input->post('period_code');
            $type = $this->input->post('type');
            $customer_guid = $_SESSION['customer_guid'];
            $query_loc = $_SESSION['query_loc'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();
            $hq_branch_code_array = array();
            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            if (in_array($_SESSION['grda_loc'], $hq_branch_code_array)) {
                $loc = $query_loc;
            } else {
                $loc = "'" . $_SESSION['grda_loc'] . "'";
            }

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $module_code_in = '';
            } else {
                $module_code_in = "AND a.supplier_code IN (" . $_SESSION['query_supcode'] . ") ";
            }

            if ($grn_ref_no == '') {
                $grn_ref_no_in = '';
            } else {
                $grn_ref_no_in = " AND a.refno LIKE '%" . $grn_ref_no . "%' ";
            }

            if ($ref_no == '') {
                $ref_no_in = '';
            } else {
                $ref_no_in = " AND b.refno LIKE '%" . $ref_no . "%' ";
            }

            if ($status == '') {
                $status_in = "";
            } elseif ($status == 'UNREAD') {
                $status_in = " AND b.status = '' ";
            } elseif ($status == 'READ') {
                $status_in = " AND b.status IN ('printed', 'viewed') ";
            } elseif ($status == 'ALL') {
                $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'GRDA_FILTER_DOCTYPE'");

                foreach ($get_stat->result() as  $row) {
                    $check_stat[] = $row->code;
                }

                foreach ($check_stat as &$value) {
                    $value = "'" . trim($value) . "'";
                }
                $check_status = implode(',', array_filter($check_stat));
                $status_in = " AND b.transtype IN ($check_status) ";
            } else {
                $status_in = " AND b.transtype = '$status' ";
            }

            if ($datefrom == '' || $dateto == '') {
                $doc_daterange_in = '';
            } else {
                $doc_daterange_in = " AND b.sup_cn_date BETWEEN '$datefrom' AND '$dateto' ";
            }

            // if ($exp_datefrom == '' || $exp_dateto == '') {
            //     $exp_daterange_in = '';
            // } else {
            //     $exp_daterange_in = " AND a.docdate BETWEEN '$exp_datefrom' AND '$exp_dateto' ";
            // }

            // if ($period_code == '') {
            //     $period_code_in = '';
            // } else {
            //     $period_code_in = " AND LEFT(b.sup_cn_date, 7) = '$period_code'";
            // }

            $query_count = "SELECT * FROM ( SELECT 
            a.refno AS grn_refno,
            b.customer_guid,
            b.refno,
            a.supplier_code,
            a.supplier_name,
            a.loc_group,
            b.transtype,
            b.sup_cn_no,
            b.sup_cn_date,
            b.dncn_date,
            b.varianceamt,
            IF(b.status = '', 'NEW', b.status) AS status
            FROM
            b2b_summary.grmain_info AS a
            INNER JOIN  b2b_summary.grmain_dncn_info  AS b
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid 
            WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' 
            AND a.loc_group in ($loc)
                AND a.in_kind = 0  
                $module_code_in 
                $status_in 
                $ref_no_in
                $grn_ref_no_in
                $doc_daterange_in
            ) zzz
            ";

            $query = "SELECT 
            a.refno AS grn_refno,
            b.customer_guid,
            b.refno,
            a.supplier_code,
            a.supplier_name,
            a.loc_group,
            b.transtype,
            b.sup_cn_no,
            b.sup_cn_date,
            b.dncn_date,
            b.varianceamt,
            IF(b.status = '', 'NEW', b.status) AS status
            FROM
            b2b_summary.grmain_info AS a
            INNER JOIN  b2b_summary.grmain_dncn_info AS b
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid 
            WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' 
            AND a.loc_group in ($loc)
                AND a.in_kind = 0  
                $module_code_in 
                $status_in 
                $ref_no_in
                $grn_ref_no_in
                $doc_daterange_in "; 
            
            //AND b.loc_group in ($loc)  -- up to production need change this

            $sql = "SELECT * FROM (
                $query
            ) zzz ";

            $query = $this->Datatable_model->datatable_main($sql, $type, $doc);
            //echo $this->db->last_query(); die;
            $fetch_data = $query->result();
            $data = array();
            if (count($fetch_data) > 0) {
                foreach ($fetch_data as $row) {
                    $tab = array();
                    $tab['grn_refno'] = $row->grn_refno; 
                    $tab['refno'] = $row->refno;
                    $tab['loc_group'] = $row->loc_group;
                    $tab['transtype'] = $row->transtype;
                    $tab['supplier_code'] = $row->supplier_code;
                    $tab['supplier_name'] = $row->supplier_name;
                    $tab['sup_cn_no'] = $row->sup_cn_no;
                    $tab['sup_cn_date'] = $row->sup_cn_date;
                    $tab['dncn_date'] = $row->dncn_date;
                    $tab['varianceamt'] = "<span class='pull-right'>" . number_format($row->varianceamt, 2) . "</span>";
                    $tab['status'] = $row->status;
                    $tab['button'] = "<a href=" . site_url('b2b_grda/grda_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['grda_loc'] . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    $tab['box'] = '<input type="checkbox" class="data-check" value="' . $row->refno . '">';

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

    public function grda_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $check_status = '';
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $_SESSION['user_guid'];
            
            $check_scode = $this->db->query("SELECT JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].ap_sup_code')) AS ap_sup_code from b2b_summary.grmain_dncn_info a where a.refno = '$refno' and a.customer_guid = '".$_SESSION['customer_guid']."'")->row('ap_sup_code');
            $check_scode = str_replace("/","+-+",$check_scode);

            /*$parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");*/
            $parameter  = $this->db->query("SELECT * from menu where module_link = 'panda_grda'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';
            
            $file_headers = @get_headers($filename);
            $from_module = $_SESSION['frommodule'];

            if(!in_array('!GRDASUPPMOV',$_SESSION['module_code']))
            {
                $this->db->query("UPDATE b2b_summary.grmain_dncn_info set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");
                // echo $this->db->last_query();die;

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_grda'
                , '$from_module'
                , '$refno'
                , now()
                ");
            
            };

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Goods Received Difference Advice',
                'request_link_grda' => site_url('B2b_grda/grda_report?refno='.$refno),
            );

            $this->load->view('header');       
            $this->load->view('grda/b2b_grda_pdf',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function grda_report()
    {
        $refno = $_REQUEST['refno'];
        // $refno = 'KKDGR22040683';
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_PO/main_jrxml.pdf?refno=".$refno; // po
        //$url = "http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_GRN/gr_supplier_copy.pdf?refno=BLPGR22030862"; // grn
        $url = $this->jasper_ip ."/jasperserver/rest_v2/reports/reports/PandaReports/Backend_GRN/GRDA.pdf?refno=".$refno; // grda
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
            if(!in_array('!GRDASUPPMOV',$_SESSION['module_code']))
            {
                $this->db->query("UPDATE b2b_summary.grmain_dncn_info set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status IN('','viewed') ");

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'printed_grda'
                , '$from_module'
                , '$row2'
                , now()
                ");
            }
            // redirect ($filename);
        }


    }

} // nothing after this
