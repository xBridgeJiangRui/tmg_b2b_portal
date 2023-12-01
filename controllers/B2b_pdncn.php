<?php
class b2b_pdncn extends CI_Controller
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
        $this->load->model('GR_model');
        $this->load->model('Datatable_model');
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $setsession = array(
                'frommodule' => 'b2b_pdncn',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            $setsession = array(
                'pdncn_loc' => $_REQUEST['loc'],
            );
            $this->session->set_userdata($setsession);

            redirect('b2b_pdncn/pdncn_list');
            $this->panda->get_uri();

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    /*
    to prevent user from cincai key in the refno based on url, 
    remember to join all back to user guid so that when they key by refno, it will check if the user is valid to query or not then will show result or not..
    */

    public function pdncn_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            
            $check_loc = $_SESSION['pdncn_loc'];
            $check_status = $_REQUEST['status'];
            
            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            $result = $this->db->query("SELECT customer_guid, trans_type, loc_group, refno, docno, docdate, supplier_code, supplier_name, ROUND(JSON_UNQUOTE(JSON_EXTRACT(`pdncn_json_info`,'$.cndnamt[0].amount')), 2) AS amount,ROUND(JSON_UNQUOTE(JSON_EXTRACT(`pdncn_json_info`,'$.cndnamt[0].gst_tax_sum')), 2) AS gst_tax_sum, ROUND(JSON_UNQUOTE(JSON_EXTRACT(`pdncn_json_info`,'$.cndnamt[0].amount_include_tax')), 2) AS amount_include_tax,IF(status = '', 'NEW', status) as status FROM b2b_summary.cndn_amt_info WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ('$check_loc')"); 

            // $data = array(
            //     'pdncn_status' => $this->db->query("SELECT code, reason from lite_b2b.set_setting where module_name = 'PDNCN_FILTER_DOCTYPE' order by code='' desc, code asc"),
            //     'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
            //     'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'"),
            //     'result' => $result,
            //     'filter_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PDNCN_FILTER_TYPE' order by code='' desc, code asc"),
            //     'datatable_url' => site_url('b2b_pdncn/pdncn_list'),
            // );

            $data = array(
                'filter_status' => $this->db->query("SELECT code, reason from lite_b2b.set_setting where module_name = 'PDNCN_FILTER_TYPE' order by code='' desc, code asc"),
                'period_code' => $this->db->query("SELECT period_code from lite_b2b.list_period_code"),
                'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'"),
            );

            $data_footer = array(
                'activity_logs_section' => 'pdncn'
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('pdncn/b2b_pdncn_list_view', $data);
            //$this->load->view('general_modal', $data);
            $this->load->view('footer', $data_footer);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function pdncn_datatable()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $doc = 'pdncn_table';

            $ref_no = $this->input->post('ref_no');
            $txn_type = preg_replace('/\s+/', '', $this->input->post('txn_type'));
            // $period_code = preg_replace('/\s+/', '', $this->input->post('period_code'));
            $period_code = '';
            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            $type = $this->input->post('type');
            $customer_guid = $_SESSION['customer_guid'];
            $query_loc = $_SESSION['query_loc'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();
            $hq_branch_code_array = array();
            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            if (in_array($_SESSION['pdncn_loc'], $hq_branch_code_array)) {
                $loc = $query_loc;
            } else {
                $loc = "'" . $_SESSION['pdncn_loc'] . "'";
            }

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $module_code_in = '';
            } else {
                $module_code_in = "AND supplier_code IN (" . $_SESSION['query_supcode'] . ") ";
            }

            if ($ref_no == '') {
                $ref_no_in = '';
            } else {
                $ref_no_in = " AND refno LIKE '%" . $ref_no . "%' ";
            }

            if ($txn_type == 'ALL' || $txn_type == '') {
                $status_in = "";
            } elseif ($txn_type == 'READ'){
                $status_in = " AND `status` IN ('printed', 'viewed') ";
            } elseif ($txn_type == 'UNREAD'){
                $status_in = " AND `status` = '' ";
            }else {
                $status_in = " AND trans_type = '$txn_type' ";
            }

            // if ($period_code == 'None' || $period_code == '') {
            //     $period_code_in = '';
            // } else {
            //     $period_code_in = " AND LEFT(docdate, 7) = '$period_code'";
            // }

            if ($datefrom == '' || $dateto == '') {
                $doc_daterange_in = '';
            } else {
                $doc_daterange_in = " AND docdate BETWEEN '$datefrom' AND '$dateto' ";
            }


            $query_count = "SELECT * FROM (SELECT 
            * 
            FROM 
            (
              SELECT 
                customer_guid, 
                trans_type, 
                loc_group, 
                refno, 
                docno, 
                docdate, 
                supplier_code, 
                supplier_name, 
                amount, 
                gst_tax_sum, 
                total_incl_tax, 
                IF(status = '', 'NEW', status) as status
              FROM 
                b2b_summary.cndn_amt_info
              WHERE 
                customer_guid = '$customer_guid' 
                AND loc_group IN ($loc) 
                $module_code_in 
                $ref_no_in
                $doc_daterange_in 
                $status_in
            ) a
            ) zzz "; 

            $query = "SELECT 
            * 
            FROM 
            (
              SELECT 
                customer_guid, 
                trans_type, 
                loc_group, 
                refno, 
                docno, 
                docdate, 
                supplier_code, 
                supplier_name, 
                amount, 
                gst_tax_sum, 
                total_incl_tax, 
                IF(status = '', 'NEW', status) as status
              FROM 
                b2b_summary.cndn_amt_info
              WHERE 
                customer_guid = '$customer_guid' 
                AND loc_group IN ($loc) 
                $module_code_in 
                $ref_no_in
                $doc_daterange_in 
                $status_in
            ) a
                    
            ";

            $sql = "SELECT * FROM (
                $query
            ) zzz ";

            // print_r($sql); die;

            $query = $this->Datatable_model->datatable_main($sql, $type, $doc);
            // echo $this->db->last_query(); die;
            $fetch_data = $query->result();
            $data = array();
            if (count($fetch_data) > 0) {
                foreach ($fetch_data as $row) {
                    $tab = array();

                    $tab['refno'] = $row->refno;
                    $tab['loc_group'] = $row->loc_group;
                    $tab['supplier_code'] = $row->supplier_code;
                    $tab['supplier_name'] = $row->supplier_name;
                    $tab['trans_type'] = $row->trans_type;
                    $tab['docno'] = $row->docno;
                    $tab['docdate'] = $row->docdate;
                    $tab['amount'] = $row->amount;
                    $tab['gst_tax_sum'] = $row->gst_tax_sum;
                    $tab['amount_include_tax'] = $row->total_incl_tax;
                    $tab['status'] = $row->status;
                    $tab['button'] = "<a href=" . site_url('b2b_pdncn/pdncn_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['pdncn_loc'] . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
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

    public function pdncn_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {
            $database = 'lite_b2b';
            $database1 = 'b2b_summary';
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];

            $check_scode = $this->db->query("SELECT JSON_UNQUOTE(JSON_EXTRACT(a.`pdncn_json_info`,'$.cndnamt[0].code')) AS code from b2b_summary.cndn_amt_info a where a.refno = '$refno' and a.customer_guid = '".$customer_guid."'")->row('code');
            $check_scode = str_replace("/","+-+",$check_scode);

            $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_pdncn'");
            // $type = $parameter->row('type');
            $ptype = $this->db->query("SELECT trans_type from b2b_summary.cndn_amt_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('trans_type');
            $type = substr($ptype,0,3);

            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';

            $file_headers = @get_headers($filename);
            $from_module = $_SESSION['frommodule'];

            if(!in_array('!PDNCNSUPPMOV',$_SESSION['module_code']))
            {
                $this->db->query("UPDATE b2b_summary.cndn_amt_info SET status = 'viewed' where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' AND status = ''");

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_$type'
                , '$from_module'
                , '$refno'
                , now()
                ");
            
            };

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'PDN/CN',
                'request_link_pdncn' => site_url('B2b_pdncn/pdncn_report?refno='.$refno),
            );

            $this->load->view('header');       
            $this->load->view('pdncn/b2b_pdncn_pdf',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function pdncn_report()
    {
        $get_status = $this->db->query("SELECT `status` FROM lite_b2b.jasper_server WHERE isactive = '1'")->row('status');

        if($get_status == '0')
        {
            print_r('Report Under Maintenance.'); 
            die;
        }
        
        $refno = $_REQUEST['refno'];
        $url = $this->jasper_ip . "/jasperserver/rest_v2/reports/reports/PandaReports/Backend_PDN_PCN/main_jrxml.pdf?refno=".$refno; 
        //print_r($url); die;
        $check_code = $this->db->query("SELECT supplier_code from b2b_summary.cndn_amt_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY refno")->row('supplier_code');

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_pdncn'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_pdncn'")->row('query');

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
            if(!in_array('!PDNCNSUPPMOV',$_SESSION['module_code']))
            {
                $this->db->query("UPDATE b2b_summary.cndn_amt_info set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status IN('','viewed') ");

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'printed_pdncn'
                , '$from_module'
                , '$row2'
                , now()
                ");
            }
            // redirect ($filename);
        }


    }


}
?>