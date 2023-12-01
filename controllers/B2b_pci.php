<?php
class b2b_pci extends CI_Controller
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
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            //print_r($_SESSION['from_other']); die;
            $setsession = array(
                'frommodule' => 'b2b_pci',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            // if(isset($_SESSION['from_other']) == 0 )
            // {
                $setsession = array(
                    'pci_loc' => $_REQUEST['loc'],
                );
                $this->session->set_userdata($setsession);

                redirect('b2b_pci/pci_list');
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

    public function pci_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $check_loc = $_SESSION['pci_loc'];
            
            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            $data = array(
                'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PCI_FILTER_STATUS' order by code='ALL' desc, code asc"),
                'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'"),
            );

            $data_footer = array(
                'activity_logs_section' => 'pci'
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('pci/b2b_pci_list_view', $data);
            //$this->load->view('general_modal', $data);
            $this->load->view('footer', $data_footer);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function pci_datatable()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $doc = 'pci_table';
            $type = $this->input->post('type');
            $customer_guid = $_SESSION['customer_guid'];
            $query_loc = $_SESSION['query_loc'];

            $ref_no = $this->input->post('ref_no');
            $status = $this->input->post('status');
            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            $period_code = $this->input->post('period_code');

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();
            $hq_branch_code_array = array();
            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            if (in_array($_SESSION['pci_loc'], $hq_branch_code_array)) {
                $loc = $query_loc;
            } else {
                $loc = "'" . $_SESSION['pci_loc'] . "'";
            }

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $module_code_in = '';
            } else {
                $module_code_in = "AND a.supplier_code IN (" . $_SESSION['query_supcode'] . ") ";
            }

            if ($ref_no == '') {
                $ref_no_in = '';
            } else {
                $ref_no_in = " AND a.inv_refno LIKE '%" . $ref_no . "%' ";
            }

            if ($status == '') {
                $status_in = " AND a.status = '' ";
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
                $doc_daterange_in = " AND a.docdate BETWEEN '$datefrom' AND '$dateto' ";
            }

            if ($period_code == '') {
                $period_code_in = '';
            } else {
                $period_code_in = " AND LEFT(a.docdate, 7) = '$period_code'";
            }

            $query_count = "SELECT * FROM ( SELECT 
            a.customer_guid,
            a.inv_refno,
            a.promo_refno,
            a.supplier_code,
            a.supplier_name,
            a.loc_group,
            a.docdate,
            IF(a.status = '', 'NEW', a.status) AS status
            FROM
            b2b_summary.promo_taxinv_info AS a
            WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' 
            AND a.loc_group in ($loc)
                $module_code_in 
                $status_in 
                $doc_daterange_in
                $ref_no_in
                $period_code_in
            ) zzz
            ";

            $query = "SELECT 
            a.customer_guid,
            a.inv_refno,
            a.promo_refno,
            a.supplier_code,
            a.supplier_name,
            a.loc_group,
            a.docdate,
            JSON_UNQUOTE(JSON_EXTRACT(a.`pci_json_info`,'$.promo_taxinv[0].total_bf_tax')) AS total_bf_tax,
            JSON_UNQUOTE(JSON_EXTRACT(a.`pci_json_info`,'$.promo_taxinv[0].gst_value')) AS gst_value,
            JSON_UNQUOTE(JSON_EXTRACT(a.`pci_json_info`,'$.promo_taxinv[0].total_af_tax')) AS total_af_tax,
            IF(a.status = '', 'NEW', a.status) AS status
            FROM
            b2b_summary.promo_taxinv_info AS a
            WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' 
            AND a.loc_group in ($loc)
                $module_code_in 
                $status_in 
                $doc_daterange_in
                $ref_no_in
                $period_code_in"; 
            
            //AND b.loc_group in ($loc)  -- up to production need change this

            $sql = "SELECT * FROM (
                $query
            ) zzz ";

            $query = $this->Datatable_model->datatable_main($sql, $type, $doc);
            // echo $this->db->last_query(); die;
            $fetch_data = $query->result();
            $data = array();
            if (count($fetch_data) > 0) {
                foreach ($fetch_data as $row) {
                    $tab = array();

                    $tab['inv_refno'] = $row->inv_refno;
                    $tab['promo_refno'] = $row->promo_refno;
                    $tab['loc_group'] = $row->loc_group;
                    $tab['docdate'] = $row->docdate;
                    $tab['supplier_code'] = $row->supplier_code;
                    $tab['supplier_name'] = $row->supplier_name;
                    $tab['total_bf_tax'] = "<span class='pull-right'>" . number_format($row->total_bf_tax, 2) . "</span>";
                    $tab['gst_value'] = "<span class='pull-right'>" . number_format($row->gst_value, 2) . "</span>";
                    $tab['total_af_tax'] = "<span class='pull-right'>" . number_format($row->total_af_tax, 2) . "</span>";
                    $tab['status'] = $row->status;

                    if ($this->session->userdata('customer_guid') == '1F90F5EF90DF11EA818B000D3AA2CAA9' || $this->session->userdata('customer_guid') == '907FAFE053F011EB8099063B6ABE2862' || $this->session->userdata('customer_guid') == 'D361F8521E1211EAAD7CC8CBB8CC0C93') 
                    {
                        // bataras , Gmart , everrise
                        $tab['button'] = "<a href=" . site_url('b2b_pci/pci_child') . "?trans=" . $row->inv_refno . "&loc=" . $_SESSION['pci_loc'] . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                        $tab['box'] = '<input type="checkbox" class="data-check" value="' . $row->inv_refno . '">';
                    } else {
                        $tab['button'] = "<a href=" . site_url('b2b_pci/pci_child') . "?trans=" . $row->promo_refno . "&loc=" . $_SESSION['pci_loc'] . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                        $tab['box'] = '<input type="checkbox" class="data-check" value="' . $row->promo_refno . '">';
                    }

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

    public function pci_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $this->session->userdata('customer_guid');
            // bataras , Gmart , everrise
            if( $customer_guid == '1F90F5EF90DF11EA818B000D3AA2CAA9' ||  $customer_guid == '907FAFE053F011EB8099063B6ABE2862' ||  $customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93')
            {
                $check_scode = $this->db->query("SELECT supplier_code from b2b_summary.promo_taxinv_info where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('supplier_code');
            }
            else
            {
                $check_scode = $this->db->query("SELECT supplier_code from b2b_summary.promo_taxinv_info where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('supplier_code');
            }

            //if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862' && $this->session->userdata('customer_guid') != 'D361F8521E1211EAAD7CC8CBB8CC0C93')

            $check_scode = str_replace("/","+-+",$check_scode);

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');
            // echo $customer_guid;die;

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';
            // echo $filename;die;

            $file_headers = @get_headers($filename);
            $customer_guid = $_SESSION['customer_guid'];        
            $user_guid = $_SESSION['user_guid'];        
            $from_module = $_SESSION['frommodule'];
            
            if(!in_array('!PCISUPPMOV',$_SESSION['module_code']))
            {
                if( $customer_guid == '1F90F5EF90DF11EA818B000D3AA2CAA9' ||  $customer_guid == '907FAFE053F011EB8099063B6ABE2862' ||  $customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93')
                {
                    $this->db->query("UPDATE b2b_summary.promo_taxinv_info SET status = 'viewed' where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' AND status = ''");
                }
                else
                {
                    $this->db->query("UPDATE b2b_summary.promo_taxinv_info SET status = 'viewed' where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' AND status = ''");
                }
                    
                    $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'viewed_PCI'        
                    , '$from_module'        
                    , '$refno'      
                    , now()     
                    ");     
                    // redirect ($filename);       
            }

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Promo Tax Invoice',
                'request_link' => site_url('B2b_pci/pci_report?refno='.$refno),
            );

            $this->load->view('header');       
            $this->load->view('pci/b2b_pci_pdf',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function pci_report()
    {
        $refno = $_REQUEST['refno'];
        $customer_guid = $this->session->userdata('customer_guid');
        $url = $this->jasper_ip ."/jasperserver/rest_v2/reports/reports/PandaReports/Backend_Promotion/promo_claim_inv.pdf?refno=".$refno; // PCI
        //print_r($url); die;

        if( $customer_guid == '1F90F5EF90DF11EA818B000D3AA2CAA9' ||  $customer_guid == '907FAFE053F011EB8099063B6ABE2862' ||  $customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93')
        {
            $check_code = $this->db->query("SELECT a.supplier_code from b2b_summary.promo_taxinv_info a where a.inv_refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY a.refno")->row('supplier_code');
        }
        else
        {
            $check_code = $this->db->query("SELECT a.supplier_code from b2b_summary.promo_taxinv_info a where a.promo_refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY a.refno")->row('supplier_code');
        }

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_pci'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_pci'")->row('query');

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
            if(!in_array('!PCISUPPMOV',$_SESSION['module_code']))
            {
                // $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and promo_refno = '$row2' and status IN ('','viewed') ");
                if( $customer_guid == '1F90F5EF90DF11EA818B000D3AA2CAA9' ||  $customer_guid == '907FAFE053F011EB8099063B6ABE2862' ||  $customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93')
                {
                    $this->db->query("UPDATE b2b_summary.promo_taxinv_info set status = 'printed' where customer_guid ='$customer_guid' and inv_refno = '$row2' and status IN ('','viewed') ");
                }
                else
                {
                    $this->db->query("UPDATE b2b_summary.promo_taxinv_info set status = 'printed' where customer_guid ='$customer_guid' and promo_refno = '$row2' and status IN ('','viewed') ");
                }             

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'printed_PCI'
                , '$from_module'
                , '$row2'
                , now()
                ");
            }
            // redirect ($filename);
        }
    }

} // nothing after this
