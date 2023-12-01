<?php
class b2b_si extends CI_Controller
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
    }

    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $setsession = array(
                'frommodule' => 'b2b_si',
            );
            $this->session->set_userdata($setsession);

            if ($_REQUEST['loc'] == '') {
                redirect('login_c/location');
            };

            if (isset($_SESSION['from_other']) == 0) {
                $setsession = array(
                    'si_loc' => $_REQUEST['loc'],
                );
                $this->session->set_userdata($setsession);
                redirect('b2b_si/si_list');
                $this->panda->get_uri();
            } else {
                if ($_REQUEST['status'] == '') {
                    unset($_SESSION['from_other']);
                    redirect('b2b_si?loc=' . $_REQUEST['loc']);
                };
                $this->panda->get_uri();
                redirect('general/view_status?status=' . $_REQUEST['loc']);
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function si_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $check_loc = $_SESSION['si_loc'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            $data = array(
                'status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'SI_FILTER_STATUS' order by code='ALL' desc, code asc"),
                'datatable_url' => site_url('general/view_table?loc=' . $_SESSION['si_loc']),
                'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                'check_loc' => $check_loc,
                'hq_branch_code_array' => $hq_branch_code_array,
                'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'")
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('si/b2b_si_list_view', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function si_datatable()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $doc = 'si_table';
            $si_ref_no = $this->input->post('si_ref_no');
            $status = $this->input->post('status');
            $datefrom = $this->input->post('datefrom');
            $dateto = $this->input->post('dateto');
            $period_code = $this->input->post('period_code');
            $type = $this->input->post('type');
            $customer_guid = $_SESSION['customer_guid'];
            $query_loc = $_SESSION['query_loc'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();
            $hq_branch_code_array = array();
            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            if (in_array($_SESSION['si_loc'], $hq_branch_code_array)) {
                $si_loc = $query_loc;
            } else {
                $si_loc = "'" . $_SESSION['si_loc'] . "'";
            }

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $module_code_in = '';
            } else {
                $module_code_in = "AND a.Code IN (" . $_SESSION['query_supcode'] . ") ";
            }

            if ($si_ref_no == '') {
                $si_ref_no_in = '';
            } else {
                $si_ref_no_in = " AND a.RefNo LIKE '%" . $si_ref_no . "%' ";
            }

            if ($status == '') {
                $status_in = " AND a.status = '' ";
            } elseif ($status == 'ALL') {
                $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'SI_FILTER_STATUS'");

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
                $daterange_in = '';
            } else {
                $daterange_in = " AND a.InvoiceDate BETWEEN '$datefrom' AND '$dateto' ";
            }

            if ($period_code == '') {
                $period_code_in = '';
            } else {
                $period_code_in = " and left(a.InvoiceDate, 7) = '$period_code'";
            }

            $query = "SELECT a.RefNo as si_refno,
            CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`si_json_info`,'$.simain[0].loc_group')) AS CHAR(10)) AS loc_group,
            a.Code as code,
            CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`si_json_info`,'$.simain[0].Name')) AS CHAR(100)) AS name,
            a.InvoiceDate as invoice_date,
            a.DeliverDate as delivery_date,
            a.DocNo as doc_no,
            ROUND(JSON_UNQUOTE(JSON_EXTRACT(a.`si_json_info`,'$.simain[0].Total')), 2) AS amount,
            ROUND(JSON_UNQUOTE(JSON_EXTRACT(a.`si_json_info`,'$.simain[0].gst_tax_sum')), 2) AS tax,
            ROUND(JSON_UNQUOTE(JSON_EXTRACT(a.`si_json_info`,'$.simain[0].total_include_tax')), 2) AS total_include_tax,
            IF(a.status = '', 'NEW', status) as status 
            FROM b2b_summary.`simain_info` AS a
            WHERE a.`customer_guid` = '$customer_guid'
            AND JSON_UNQUOTE(JSON_EXTRACT(a.`si_json_info`,'$.simain[0].loc_group')) IN ($si_loc)
            $module_code_in
            $si_ref_no_in
            $status_in
            $daterange_in
            $period_code_in
            ";

            $sql = "SELECT * FROM (
                $query
            ) zzz ";

            $query = $this->Datatable_model->datatable_main($sql, $type, $doc);

            $fetch_data = $query->result();

            $data = array();
            if (count($fetch_data) > 0) {
                foreach ($fetch_data as $row) {
                    $tab = array();
                    $tab["si_refno"] = $row->si_refno;
                    $tab["loc_group"] = $row->loc_group;
                    $tab["code"] = $row->code;
                    $tab["name"] = $row->name;
                    $tab["invoice_date"] = $row->invoice_date;
                    $tab["delivery_date"] = $row->delivery_date;
                    $tab["doc_no"] = $row->doc_no;
                    $tab['amount'] = "<span class='pull-right'>" . number_format($row->amount, 2) . "</span>";
                    $tab['tax'] = "<span class='pull-right'>" . number_format($row->tax, 2) . "</span>";
                    $tab['total_include_tax'] = "<span class='pull-right'>" . number_format($row->total_include_tax, 2) . "</span>";
                    $tab["status"] = $row->status;
                    $tab["action"] = "<a href=" . site_url('b2b_si/si_child') . "?trans=" . $row->si_refno . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    $tab["chkb"] = '<input type="checkbox" class="data-check" value="' . $row->si_refno . '">';

                    $data[] = $tab;
                }
            } else {
                $data = '';
            }

            $output = array(
                "draw"                =>     intval($_POST["draw"]),
                "recordsTotal"        =>     $this->Datatable_model->general_get_all_data($sql, $doc),
                "recordsFiltered"     =>     $this->Datatable_model->general_get_filtered_data($sql, $doc),
                "data"                =>     $data
            );

            echo json_encode($output);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function si_child()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];

            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];

            if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_si'
                , 'b2b_si'
                , '$refno'
                , now()
                ");

                $this->db->query("UPDATE b2b_summary.simain_info set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");
            };

            $check_status = $this->db->query("SELECT refno, if(status = '', 'new', status) as status from b2b_summary.simain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");

            // $set_code = $this->db->query("SELECT code,portal_description as reason from status_setting where type = 'reject_po' AND isactive = 1 order by portal_description asc");
            // $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");

            $data = array(
                'title' => 'Sales Invoice',
                'check_status' => $check_status,
                'request_link' => site_url('B2b_si/si_report?refno='.$refno),
            );

            $this->load->view('header');
            $this->load->view('si/b2b_si_pdf', $data);
            //$this->load->view('general_modal',$data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function si_report()
    {
        $refno = $_REQUEST['refno'];
        //print_r($refno); die;
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
        $url = $this->jasper_ip."/jasperserver/rest_v2/reports/reports/PandaReports/Backend_SI/si_landscape.pdf?refno=".$refno;

        $check_code = $this->db->query("SELECT code from b2b_summary.simain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('code');

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'b2b_si'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'b2b_si'")->row('query');

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

    public function merge_pdf()
    {
        $list_id = $this->input->post('id');
        $pdf_content = '';
        foreach ($list_id as $refno) {
            $url = "http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/PandaReports/Backend_SI/si_landscape.pdf?refno=".$refno;
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
            header('Content-Disposition: inline; filename=abc.pdf');
            echo $response; 
    
            curl_close($curl); 
            // $response = curl_exec($curl);
            // $pdf_content .= base64_encode($response);
        }
        // print_r(base64_decode($pdf_content));
        // header('Content-type:application/pdf');
        // header('Content-Disposition: inline; filename=abc.pdf');
        // echo base64_decode($response);

        // echo json_encode(array("link_url" => base64_decode($pdf_content)));
        

    }

    public function direct_print_merge()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login())
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
            // $filename = base_url('merge/'.$pdf_name.'.pdf');
            $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');

            $file_config_final_path = $this->file_config_b2b->merge_print_create_file_path($customer_guid,'web','general_doc','merge_print','MPMPCP');
            $merge_path = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','merge_print','MPN');

            $filename = $file_config_final_path.$path_seperator.$merge_path.$path_seperator.$pdf_name.'.pdf';
            // echo $filename;die;
            // $filename = 'http://192.168.10.29/lite_panda_b2b/uploads/tfvalue/merge.pdf';
            // echo $filename;die;
 
            $file_headers = @get_headers($filename);
            $refno_array = explode(",",$refno);
            // echo $refno;
            // print_r($refno_array);die;
            foreach($refno_array as $row2)
            {
                // echo 1;
                 $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$row2' and customer_guid = '".$_SESSION['customer_guid']."'");
                $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'GR_FILTER_STATUS' order by reason asc");
                $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");
                $data = array(
                    'filename' => $filename,
                    'file_headers' => $file_headers,
                    'virtual_path' => $virtual_path,
                     'title' => 'Goods Received',
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
                        $this->db->query("UPDATE b2b_summary.grmain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status = '' ");

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
            // echo $filename;die;
            $file = $filename; 
            if (!file_exists($file))
            {
                echo "The file not exists. Please Contact Admin";die;
            }
            // die;
            $type = 'inline';
            // $pdf_name = 'merge';
            // echo $pdf_name;die;
            header("Content-type: application/pdf");
            header('Content-Disposition: '.$type.'; filename="'.$pdf_name.'.pdf"'); 
            // header("Content-Disposition: attachment; filename=\"".$Filename."\"");
            // header("Content-Length: ".filesize($Filename));
            header('Cache-Control: public, must-revalidate, max-age=0');
            ob_clean();
            flush();
            readfile($file);
            die; 
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
}
