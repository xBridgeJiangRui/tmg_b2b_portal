<?php
class b2b_gr extends CI_Controller
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
        $this->api_url = '127.0.0.1/rest_b2b/index.php';
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $setsession = array(
                'frommodule' => 'b2b_gr',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            // if(isset($_SESSION['from_other']) == 0 )
            // {
                $setsession = array(
                    'gr_loc' => $_REQUEST['loc'],
                );
                $this->session->set_userdata($setsession);

                redirect('b2b_gr/gr_list');
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

    public function gr_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $check_loc = $_SESSION['gr_loc'];
            
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
            $this->load->view('gr/b2b_gr_list_view', $data);
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

            if (in_array($_SESSION['gr_loc'], $hq_branch_code_array)) {
                $loc = $query_loc;
            } else {
                $loc = "'" . $_SESSION['gr_loc'] . "'";
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
                    $tab['grda_status'] = "<a href=" . site_url('b2b_grda/grda_child?trans=' . $row->grda_status . '&loc=' . $_SESSION['gr_loc']) . ">" . $row->grda_status . "</a>";
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
                    $tab['button'] = "<a href=" . site_url('b2b_gr/gr_child') . "?trans=" . $row->refno . "&loc=" . $_SESSION['gr_loc'] . "&accpt_gr_status=" . $row->status . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
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
            $database = 'lite_b2b';

            $grmain_status = $this->db->query("SELECT status from $database_production.grmain where refno = '$refno'")->row('status');
            if ($grmain_status != 'CONFIRM_EINV') {
                if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                    $this->db->query("UPDATE b2b_summary.grmain_info set status = 'viewed' where status = '' and customer_guid = '" . $_SESSION['customer_guid'] . "' and refno = '$refno' ");

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
                , a.gr_json_info
                , IF(d.refno IS NULL, '0', JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].pay_by_invoice'))) AS pay_by_invoice
                FROM b2b_summary.grmain_info AS a 
                LEFT JOIN b2b_summary.grmain_proposed AS b 
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid 
                LEFT JOIN b2b_summary.einv_main c ON a.refno = c.refno AND a.customer_guid = c.customer_guid 
                LEFT JOIN b2b_summary.`grmain_dncn_info` d ON a.`RefNo` = d.`RefNo` AND a.`customer_guid` = d.`customer_guid`
                where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "'
                GROUP BY a.refno");

                $pay_by_invoice = $get_header_detail->row('pay_by_invoice');
                
                //-- , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
                //                -- , IF(b.created_at IS NULL, a.`DocDate`, DATE_FORMAT(b.created_at,'%Y-%m-%d')) AS DocDate
                //child data from rest
                // $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
                // $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
                // // echo $to_shoot_url ;die;
                // $ch = curl_init($to_shoot_url);
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                // $response = curl_exec($ch);
                // echo var_dump($response);die;
                // making sure got header baru show child to gen inv
                if ($get_header_detail->num_rows() > 0) {
                    // echo $to_shoot_url;die;

                    $get_grn_child_data = $get_header_detail->row('gr_json_info');
                    
                    if (count(json_decode($get_grn_child_data, true)['grchild']) > 0) {
                        // print_r(json_decode($get_grn_child_data, true)['grchild']); die;
                        //$json_data = json_decode($get_grn_child_data, true)['grchild'];
                        $child_result_validation = count(json_decode($get_grn_child_data, true)['grchild']);
                        foreach (json_decode($get_grn_child_data, true)['grchild'] as $json)
                        {
                            if($pay_by_invoice == '1')
                            {
                                $itemcode = $json['Itemcode'];
                                $line = $json['Line'];
                                $barcode = $json['barcode'];
                                $description = $json['Description'];
                                $packsize = $json['PackSize'];
                                $qty = $json['Qty'];
                                $uom = $json['UM'];
                                $inv_qty = $json['Inv_Qty'];
                                $unitprice = number_format($json['UnitPrice'], 4);
                                $disc_desc = $json['Disc2Type'] . number_format($json['Disc2Value'], 2);
                                $discamt = $json['DiscAmt'];
                                $unit_disc_prorate = ($json['hcost_gr'] == 0) ? number_format($json['hcost_gr'], 4) : number_format($json['hcost_gr'] / $json['Inv_Qty'], 4);
                                $unit_price_bfr_tax = ($json['hcost_gr'] == 0) ? number_format($json['Inv_NetUnitPrice'], 4) : number_format($json['Inv_TotalPrice'] - $json['hcost_gr'] / $json['Inv_Qty'], 4);
    
                                $totalprice = $json['Inv_TotalPrice'];
                                $gst_tax_amount = number_format($json['gst_tax_amount'], 4);
                                $gst_unit_total = number_format((($json['Inv_TotalPrice'] - number_format($json['hcost_gr'], 2)) + $json['gst_tax_amount']), 2, '.', '' );
    
                            }
                            else
                            {
                                $itemcode = $json['Itemcode'];
                                $line = $json['Line'];
                                $barcode = $json['barcode'];
                                $description = $json['Description'];
                                $packsize = $json['PackSize'];
                                $qty = $json['Qty'];
                                $uom = $json['UM'];
                                $inv_qty = $json['Inv_Qty'];
                                $unitprice = number_format($json['UnitPrice'], 4);
                                $disc_desc = $json['Disc2Type'] . number_format($json['Disc2Value'], 2);
                                $discamt = $json['DiscAmt'];
                                $unit_disc_prorate = ($json['hcost_gr'] == 0) ? number_format($json['hcost_gr'], 4) : number_format($json['hcost_gr'] / $json['Qty'], 4);
                                $unit_price_bfr_tax = ($json['hcost_gr'] == 0) ? number_format($json['NetUnitPrice'], 4) : number_format($json['TotalPrice'] - $json['hcost_gr'] / $json['Qty'], 4);
                                $totalprice = $json['TotalPrice'];
                                $gst_tax_amount = number_format($json['gst_tax_amount'], 4);
                                $gst_unit_total = number_format((($json['TotalPrice'] - number_format($json['hcost_gr'], 2)) + $json['gst_tax_amount']), 2, '.', '' );
                            }
                            
                            // $itemcode = $json['Itemcode'];
                            // $line = $json['Line'];
                            // $barcode = $json['barcode'];
                            // $description = $json['Description'];
                            // $packsize = $json['PackSize'];
                            // $qty = $json['Qty'];
                            // $uom = $json['UM'];
                            // $unitprice = number_format($json['UnitPrice'], 4);
                            // $disc_desc = $json['Disc2Type'] . number_format($json['Disc2Value'], 2);
                            // $discamt = $json['DiscAmt'];
                            // $unit_disc_prorate = ($json['hcost_gr'] == 0) ? number_format($json['hcost_gr'], 4) : number_format($json['hcost_gr'] / $json['Qty'], 4);
                            // $unit_price_bfr_tax = ($json['hcost_gr'] == 0) ? number_format($json['NetUnitPrice'], 4) : number_format($json['TotalPrice'] - $json['hcost_gr'] / $json['Qty'], 4);
                            // $totalprice = $json['TotalPrice'];
                            // $gst_tax_amount = number_format($json['gst_tax_amount'], 4);
                            // $gst_unit_total = number_format((($json['TotalPrice'] - number_format($json['hcost_gr'], 2)) + $json['gst_tax_amount']), 2, '.', '' );
                            
                            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
                            // {
                            //     $gst_unit_total = number_format((($json['TotalPrice'] - number_format($json['hcost_gr'], 2)) + $json['gst_tax_amount']), 2, '.', '');
                            // }

                            $get_child_detail[] = array(
                                'line' => $line,
                                'barcode' => $barcode,
                                'itemcode' => $itemcode,
                                'itemlink' => $json['ItemLink'], 
                                'description' => $description,
                                'qty' => $qty,
                                'netunitprice' => $json['NetUnitPrice'], 
                                'packsize' => $packsize,
                                'totalprice' => $totalprice, 
                                'itemremark' => $json['ItemRemark'],
                                'refno' => $json['RefNo'],
                                'groupno' => $json['GroupNo'], 
                                'um' => $uom, 
                                'discamt' => $discamt,
                                'gst_unit_total' => $gst_unit_total,
                                'unitprice' => $unitprice, 
                                'gst_tax_amount' => $gst_tax_amount,
                                'unit_disc_prorate' => $unit_disc_prorate, 
                                'unit_price_bfr_tax' => $unit_price_bfr_tax, 
                                'inv_qty' => $json['Inv_Qty'],
                                'inv_unitprice' => $json['Inv_UnitPrice'],
                            );
                        }
                    }
                    else
                    {
                        $get_child_detail = array();
                        $child_result_validation = '0';
                        $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E Invoice is currently not available. Please refresh this page.');
                    }
                    
                    // jr comment it due to use gr json report query 2 when qty <> 0 - 10/05/2023
                    // $response = $get_header_detail->row('gr_json_report');
                    // $get_child_detail = json_decode($response, true)['query2'];
    
                    // // $get_child_detail = json_decode(file_get_contents($to_shoot_url), true);
                    // // print_r($get_child_detail);die;
                    // $child_result_validation = $get_child_detail[0]['line'];
                    // // print_r($child_result_validation);die;
    
                    // if ($child_result_validation == 'No Records Found') {
                    //     $get_child_detail = array();
                    //     $child_result_validation = '0';
                    //     $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E Invoice is currently not available. Please refresh this page.');
                    // } else {
                    //     $get_child_detail = json_decode($response, true)['query2'];;
                    //     $child_result_validation = $get_child_detail[0]['line'];
                    //     // print_r($child_result_validation);die;
                    // }
                    
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

            $upload_grn_cn_setting = $this->db->query("SELECT * FROM $database.acc_settings WHERE customer_guid = '$customer_guid'");
            // echo $this->db->last_query();die;
            if (count($upload_grn_cn_setting->result()) > 0) {
                $upload_grn_cn_setting_flag = $upload_grn_cn_setting->row('upload_grn_cn_setting');
            } else {
                $upload_grn_cn_setting_flag = 0;
            }

            if ($upload_grn_cn_setting_flag == 1) {
                $check_upload_grn_cn = 1;
                $check_supplier_guid = $this->db->query("SELECT b.supplier_group_name,b.`supplier_guid` FROM b2b_summary.grmain_info a INNER JOIN lite_b2b.`set_supplier_group` b ON a.supplier_code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
                // echo $this->db->last_query();die;
                $target_dir = "retailer_file/" . $customer_guid . "/grn_cn/" . $check_supplier_guid->row('supplier_guid') . "/" . $refno . '.pdf';

                $set_row = $this->db->query("SET @row=0");
                $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row := @row + 1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending') AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type, IF(ext_doc1 IS NULL,IF(e.ext_sup_cn_no IS NULL,a.sup_cn_no,e.ext_sup_cn_no),ext_doc1) as ext_doc1, IFNULL(ext_date1, CURDATE()) AS ext_date1, IFNULL(b.posted, '0') AS posted, a.status,CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_report`,'$.query1[0].location')) AS CHAR(10) ) AS location, a.RefNo, a.VarianceAmt, a.Created_at, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].Created_by')) AS CHAR ) AS Created_by, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].Updated_at')) AS CHAR ) AS Updated_at, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].Updated_by')) AS CHAR ) AS Updated_by, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].hq_update')) AS CHAR ) AS hq_update, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].EXPORT_ACCOUNT')) AS CHAR ) AS EXPORT_ACCOUNT, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].EXPORT_AT')) AS CHAR ) AS EXPORT_AT, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].EXPORT_BY')) AS CHAR ) AS EXPORT_BY, a.transtype, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].share_cost')) AS share_cost, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].gst_tax_sum')) AS gst_tax_sum, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].gst_adjust')) AS gst_adjust, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].gl_code')) AS gl_code, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].tax_invoice')) AS tax_invoice, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].ap_sup_code')) AS ap_sup_code, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].refno2')) AS refno2, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].rounding_adj')) AS rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc, c.upload_cn_setting, (SELECT file_path FROM b2b_summary.`upload_doc_log` WHERE customer_guid = '$customer_guid' AND refno = CONCAT(a.refno,'-',a.transtype) ORDER BY created_at DESC LIMIT 1) AS file_path,(SELECT supplier_guid FROM b2b_summary.`upload_doc_log` WHERE customer_guid = '$customer_guid' AND refno = CONCAT(a.refno,'-',a.transtype) ORDER BY created_at DESC LIMIT 1) AS file_supplier_guid,CONCAT(a.refno,'-',a.transtype) as file_refno FROM b2b_summary.grmain_dncn_info AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '$customer_guid' AND refno = '$refno') AS b ON a.refno = b.refno AND a.transtype = b.type LEFT JOIN lite_b2b.acc_settings c ON a.customer_guid = c.customer_guid LEFT JOIN b2b_summary.`upload_doc_log` d ON c.customer_guid = d.customer_guid AND CONCAT(a.refno, '-', a.transtype) = d.refno LEFT JOIN b2b_summary.grmain_dncn_proposed e ON a.refno = e.refno AND a.transtype = e.trans_type AND a.customer_guid = e.customer_guid WHERE a.refno = '$refno' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' ORDER BY transtype ASC");

                // echo $this->db->last_query();die;

                if (file_exists($target_dir)) {
                    $exists_upload_grn_cn_file = 1;
                } else {
                    $exists_upload_grn_cn_file = 0;
                }
                // echo $exists_upload_grn_cn_file;die;
            } else {
                $set_row = $this->db->query("SET @row=0");
                $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row:=@row+1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending' ) AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type, IF(ext_doc1 IS NULL,IF(d.ext_sup_cn_no IS NULL,a.sup_cn_no,d.ext_sup_cn_no),ext_doc1) as ext_doc1 , ifnull(ext_date1, curdate()) as ext_date1,   IFNULL(b.posted, '0') as posted,a.status, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_report`,'$.query1[0].location')) AS CHAR(10) ) AS location, a.RefNo, a.VarianceAmt, a.Created_at, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].Created_by')) AS CHAR ) AS Created_by, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].Updated_at')) AS CHAR ) AS Updated_at, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].Updated_by')) AS CHAR ) AS Updated_by, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].hq_update')) AS CHAR ) AS hq_update, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].EXPORT_ACCOUNT')) AS CHAR ) AS EXPORT_ACCOUNT, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].EXPORT_AT')) AS CHAR ) AS EXPORT_AT, CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].EXPORT_BY')) AS CHAR ) AS EXPORT_BY, a.transtype, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].share_cost')) AS share_cost, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].gst_tax_sum')) AS gst_tax_sum, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].gst_adjust')) AS gst_adjust, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].gl_code')) AS gl_code, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].tax_invoice')) AS tax_invoice, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].ap_sup_code')) AS ap_sup_code, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].refno2')) AS refno2, JSON_UNQUOTE(JSON_EXTRACT(a.`grda_json_info`,'$.Grmain_dncn[0].rounding_adj')) AS rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc, c.upload_cn_setting, '#' AS file_path FROM b2b_summary.grmain_dncn_info AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' AND refno = '$refno' ) AS b ON a.refno = b.refno AND a.transtype = b.type LEFT JOIN lite_b2b.acc_settings c ON a.customer_guid = c.customer_guid LEFT JOIN b2b_summary.grmain_dncn_proposed d ON a.refno = d.refno AND a.transtype = d.trans_type AND a.customer_guid = d.customer_guid WHERE a.refno = '$refno' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' order by transtype asc");
                $check_upload_grn_cn = 0;
                $exists_upload_grn_cn_file = 1;
                //echo $this->db->last_query();die; 
            }
            // echo $this->db->last_query();die; 
            // $check_ecn_main = $this->db->query("SELECT * FROM b2b_summary.ecn_main WHERE refno = '$refno' AND customer_guid = '".$_SESSION['customer_guid']."'");
            $check_ecn_main = $this->db->query("SELECT a.*, COUNT(a.refno) AS first_count, (SELECT COUNT(refno) AS scount FROM b2b_summary.`ecn_main` WHERE refno = '$refno' AND customer_guid = '" . $_SESSION['customer_guid'] . "') AS second_count FROM b2b_summary.grmain_dncn_info a WHERE a.refno = '$refno' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' HAVING second_count = first_count");
            // echo $this->db->last_query();die;

            //echo $this->db->last_query();die;
            //$check_e_cn = $this->db->query("SELECT * from ecn_main where customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno'");      

            $check_scode = $this->db->query("SELECT supplier_code from b2b_summary.grmain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('code');
            $check_scode = str_replace("/", "+-+", $check_scode);

            $check_scode = str_replace("/", "+-+", $check_scode);

            if (isset($_REQUEST['fmodule'])) {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_gr'");
            } else {
                $parameter = $this->db->query("SELECT * from menu where module_link = '" . $_SESSION['frommodule'] . "'");
            }
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '" . $_SESSION['frommodule'] . "'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            $filename = base_url($virtual_path . '/' . $replace_var . '.pdf');

            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid, 'web', 'general_doc', 'main_path', 'GDMP');
            // $test = $this->file_config_b2b->file_path();die;
            // echo $test;die;
            $filename = $file_config_final_path . '/' . $replace_var . '.pdf';
            // echo $filename;die;
            //echo $replace_var;die;
            $file_headers = @get_headers($filename);

            // find invoice folder in client folder

            if (!file_exists("." . $virtual_path . "/invoice")) {
                $oldmask = umask(0);
                mkdir("." . $virtual_path . "/invoice", 0777, true);
                umask($oldmask);
            };


            $check_status = $this->db->query("SELECT grdate,refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain_info where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");
            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'GRN' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");
            // check if einv has open
            $check_e_inv = $this->db->query("SELECT * from b2b_summary.einv_main where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");

            if ($check_e_inv->num_rows() == '1') {
                $open_panel2 = 'collapsed-box';
                $item_detail_icon = 'fa fa-plus';
                $open_panel3 = 0;
                $version = $check_e_inv->row('revision');
                $check_e_inv_c = $this->db->query("SELECT * from b2b_summary.einv_child where einv_guid = '" . $check_e_inv->row('einv_guid') . "'");
                $check_einv_filepath = base_url($virtual_path . '/invoice/' . 'B2B_' . $_REQUEST['trans'] . '.pdf');
                $xcheck_einv_filepath = $virtual_path . '/invoice/' . 'B2B_' . $_REQUEST['trans'] . '.pdf';
                // echo $xcheck_einv_filepath;die;
            } else {
                $open_panel2 = '';
                $item_detail_icon = 'fa fa-minus';
                $open_panel3 = 1;
                $version = '0';
                $check_e_inv_c = '';
                $check_einv_filepath = '';
                $xcheck_einv_filepath = '';
            };

            if (isset($_REQUEST['edit'])) {
                $hidden_text = 'text';
                $edit_header_url = site_url('b2b_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc']);
            } else {
                $hidden_text = 'hidden';
                $edit_header_url = site_url('b2b_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc'] . '&edit');
            }
            
            if ($get_DN_detail->num_rows() >= '1') {
                $show_generate_ecn = '2';
                $show_ecn = '1';
                $show_grda_pdf = '1';

                //grda details
                $grda_parameter  = $this->db->query("SELECT * from menu where module_link = 'panda_grda'");
                $grda_type = $grda_parameter->row('type');
                $grda_code = $check_scode;

                $grda_replace_var  = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$grda_type'), 'code', '$grda_code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_grda'")->row('query');

                $grda_virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

                $grda_filename = base_url($grda_virtual_path . '/' . $grda_replace_var . '.pdf');
                $file_config_final_path = $this->file_config_b2b->file_path($customer_guid, 'web', 'general_doc', 'main_path', 'GDMP');
                $grda_filename = $file_config_final_path . '/' . $grda_replace_var . '.pdf';
                // echo $grda_filename;die;
                $grda_file_headers = @get_headers($grda_filename);
            } else {
                $show_generate_ecn = '1';
                $show_ecn = '0';
                $show_grda_pdf = '0';

                $grda_filename = 'not valid';
                $grda_file_headers = 'not valid';
                $grda_virtual_path = 'not valid';
            };

            $check_grn_based_on_pocost = $this->db->query("SELECT * from b2b_summary.supcus where customer_guid = '" . $_SESSION['customer_guid'] . "' and code = '$check_scode'");

            if ($check_grn_based_on_pocost->row('grn_baseon_pocost') == '1') {
                //paybyinvoice is got grda,no need to do ecn
                $paybyinvoice_got_grda = '1';
            } else {
                //meaning pay by grn, need do e invoice
                $paybyinvoice_got_grda  = '0';
            }
            $paybyinvoice_got_grda  = '0';
            $upload_grn_cn_setting = $this->db->query("SELECT * FROM $database.acc_settings WHERE customer_guid = '$customer_guid'");
            // echo $this->db->last_query();die;
            if (count($upload_grn_cn_setting->result()) > 0) {
                $upload_grn_cn_setting_flag = $upload_grn_cn_setting->row('upload_grn_cn_setting');
            } else {
                $upload_grn_cn_setting_flag = 0;
            }

            if ($upload_grn_cn_setting_flag == 1) {
                $check_upload_grn_cn = 1;
                $check_supplier_guid = $this->db->query("SELECT b.supplier_group_name,b.`supplier_guid` FROM b2b_summary.grmain_info a INNER JOIN lite_b2b.`set_supplier_group` b ON a.supplier_code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
                // echo $this->db->last_query();die;
                $target_dir = "retailer_file/" . $customer_guid . "/grn_cn/" . $check_supplier_guid->row('supplier_guid') . "/" . $refno . '.pdf';
                if (file_exists($target_dir)) {
                    $exists_upload_grn_cn_file = 1;
                } else {
                    $exists_upload_grn_cn_file = 0;
                }
                // echo $exists_upload_grn_cn_file;die;
            } else {
                $check_upload_grn_cn = 0;
                $exists_upload_grn_cn_file = 1;
            }

            if ($upload_grn_cn_setting_flag == 1) {
                $file_supplier_guid = $check_supplier_guid->row('supplier_guid');
                $file_sup_code = $check_supplier_guid->row('supplier_group_name');
            } else {
                $file_supplier_guid = '';
                $file_sup_code = '';
            }

            $check_grmain_proposed = $this->db->query("SELECT * FROM b2b_summary.grmain_proposed WHERE customer_guid = '$customer_guid' AND refno = '$refno'");

            if ($check_grmain_proposed->num_rows() > 0) {
                $gr_back_date = $check_grmain_proposed->row('DocDate');
            } else {
                $gr_back_date = $this->db->query("SELECT CURDATE() as curdate")->row('curdate');
            }

            $check_inv_no = addslashes($get_header_detail->row('InvNo'));
            $check_code = $get_header_detail->row('supplier_code');

            $check_inv_no = $this->db->query("SELECT refno FROM b2b_summary.grmain_info WHERE customer_guid = '$customer_guid' AND invno = '$check_inv_no' AND `supplier_code` = '$check_code' ");

            // $duplicate_refno = implode(",",array_filter(array_column($check_inv_no->result_array(),'refno')));
            // $exisiting_refno = str_replace($refno,'',$duplicate_refno);
            // $check_inv_no_refno = ($exisiting_refno[0] === ',') ? '' . substr($exisiting_refno, 1) : $exisiting_refno;

            if ($check_inv_no->num_rows() > 1) {
                $this->session->set_flashdata('warning', 'Please check duplicate Supplier Invoice No');
            }

            $data = array(
                'gr_back_date' => $gr_back_date,
                'backdate' => $this->db->query("SELECT CURDATE() as curdate")->row('curdate'),
                'filename' => $filename,
                'xcheck_einv_filepath' => $xcheck_einv_filepath,
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
                'item_detail_icon' => $item_detail_icon,
                'open_panel3' => $open_panel3,
                'version' => $version,
                'check_e_inv_c' => $check_e_inv_c,
                'get_DN_detail' => $get_DN_detail,
                'hidden_text' => $hidden_text,
                'edit_header_url' => $edit_header_url,
                'show_generate_ecn' => $show_generate_ecn,
                'show_ecn' => $show_ecn,
                'paybyinvoice_got_grda' => $paybyinvoice_got_grda,
                'check_einv_filepath' => $check_einv_filepath,
                //'aaa' => $check_grn_based_on_pocost->result(),
                'show_grda_pdf' => $show_grda_pdf,
                'grda_filename' => $grda_filename,
                'grda_file_headers' => $grda_file_headers,
                'grda_virtual_path' => $grda_virtual_path,
                'accpt_gr_status' => $accpt_gr_status,
                'exists_upload_grn_cn_file' => $exists_upload_grn_cn_file,
                'check_upload_grn_cn' => $check_upload_grn_cn,
                'grncnfilepath' => $target_dir,
                'check_ecn_main' => $check_ecn_main,
                'file_upload_type' => 'grn_cn',
                'file_supplier_guid' => $file_supplier_guid,
                'file_sup_code' => $file_sup_code,
                'file_refno' => $refno,
                'request_link_gr' => site_url('B2b_gr/gr_report?refno='.$refno),
                'request_link_grda' => site_url('B2b_gr/grda_report?refno='.$refno),
                'pay_by_invoice' => $pay_by_invoice,
            );



            $this->load->view('header');
            $this->load->view('gr/b2b_gr_pdf', $data);
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
        $get_status = $this->db->query("SELECT `status` FROM lite_b2b.jasper_server WHERE isactive = '1'")->row('status');

        if($get_status == '0')
        {
            print_r('Report Under Maintenance.'); 
            die;
        }

        $refno = $_REQUEST['refno'];

        $url = $this->jasper_ip . $this->jasper_path . "/Backend_GRN/gr_supplier_copy.pdf?refno=".$refno; // grn

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
        $get_status = $this->db->query("SELECT `status` FROM lite_b2b.jasper_server WHERE isactive = '1'")->row('status');

        if($get_status == '0')
        {
            print_r('Report Under Maintenance.'); 
            die;
        }

        $refno = $_REQUEST['refno'];

        $url = $this->jasper_ip . $this->jasper_path . "/Backend_GRN/GRDA.pdf?refno=".$refno; // grda

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

    public function edit_gr_header()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $customer_guid = $_SESSION['customer_guid'];
            $header_refno = $this->input->post('header_refno');
            $header_loc = $this->input->post('header_loc');

            $ext_invno = $this->input->post('ext_invno[]');
            $ext_dono = $this->input->post('ext_dono[]');
            $ext_docdate = $this->input->post('ext_docdate[]');
            $line = $this->input->post('line[]');
            $ori_docdate = $this->input->post('docdate[]');

            foreach ($line as $i => $id) {
                $check_exist = $this->db->query("SELECT * from b2b_summary.grmain_proposed where customer_guid = '$customer_guid' and refno = '$header_refno' ");
                //echo $this->db->last_query();die;
                if ($check_exist->num_rows() > 0) {
                    $this->db->query("DELETE FROM b2b_summary.grmain_proposed where customer_guid = '$customer_guid' and refno = '$header_refno'");
                };

                if ($ext_docdate[$i] == '' || $ext_docdate[$i] == ' ') {
                    $ext_docdate = $ori_docdate;
                }

                $data1[] = [
                    'customer_guid' => $customer_guid,
                    'status' => '',
                    'refno' => $header_refno,
                    'invno' => $ext_invno[$i],
                    'dono' => $ext_dono[$i],
                    'docdate' => $ext_docdate[$i],
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' =>  $_SESSION['user_guid'],
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $_SESSION['user_guid'],
                ];

                $check_einvmain = $this->db->query("SELECT * FROM b2b_summary.einv_main where customer_guid = '$customer_guid' and refno = '$header_refno'");

                if ($check_einvmain->num_rows() > 0) {
                    $einv_no = $ext_invno[$i];
                    $this->db->query("UPDATE b2b_summary.einv_main SET einvno = '$einv_no' where customer_guid = '$customer_guid' and refno = '$header_refno'
                     ");
                    // echo $this->db->last_query();die;
                }
            }
            $table = 'b2b_summary.grmain_proposed';
            $this->db->insert_batch($table, $data1);

            $this->db->query("UPDATE b2b_summary.grmain_proposed AS a
            INNER JOIN b2b_summary.grmain_info AS b
            ON a.customer_guid = b.customer_guid
            AND a.refno = b.refno
            SET
            a.location = b.loc_group
            , a.grdate = b.grdate
            , a.issuestamp = CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`gr_json_info`,'$.grmain[0].IssueStamp')) AS DATETIME )
            , a.laststamp = CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`gr_json_info`,'$.grmain[0].LastStamp')) AS DATETIME )
            , a.code = b.supplier_code
            , a.name = b.supplier_name
            where a.customer_guid = '$customer_guid' and a.refno = '$header_refno' and b.posted = '0'
             ");

            redirect('panda_gr/gr_child?trans=' . $header_refno . '&loc=' . $header_loc);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
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

    public function view_ecn()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $customer_guid = $_SESSION['customer_guid'];
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];
            $invoice_number = $_REQUEST['refno'] . '_' . $_REQUEST['transtype'];
            // echo $invoice_number;die;
            $gr_info = $this->db->query("SELECT 
            a.`loc_group` as Location
            , a.`supplier_name`
            , a.`supplier_code` as Code
            , ifnull(b.invno,a.`Invno`) as Invno
            FROM b2b_summary.grmain_info AS a 
            LEFT JOIN b2b_summary.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid where a.refno = '$req_refno' 
            and a.customer_guid = '$customer_guid'");

            $data = array(
                'query_data' =>  $this->db->query("SELECT a.refno, a.status, a.type, a.ext_doc1, a.ext_date1, a.amount, a.`tax_rate`, a.`tax_amount`, a.`total_incl_tax`, a.posted, b.refno_dn, b.transtype, b.location, b.itemcode, b.barcode, b.description, b.qty, b.inv_qty, b.inv_netunitprice, b.supplier, b.invno, b.dono, b.porefno, b.title2, b.notes, b.pounitprice, b.invactcost, b.netunitprice, b.pototal, b.articleno, b.packsize, b.variance_amt, b.reason, b.tax_amount, b.total_gross, IFNULL(CONCAT('[',c.invno,']'), '' ) AS new_invno, IFNULL(CONCAT('[',c.dono,']'), '' ) AS new_dono, IFNULL(CONCAT('[',c.docdate,']'), '' ) AS new_docdate, IFNULL(CONCAT('[',c.grdate,']'), '' ) AS new_grdate FROM b2b_summary.ecn_main AS a LEFT JOIN b2b_summary.`grmain_proposed` AS c ON a.customer_guid = c.`customer_guid` AND a.refno = c.refno INNER JOIN b2b_summary.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = '$transtype'"),
                'supcus_supplier' => $this->db->query("SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info->row('Code') . "' and customer_guid = '$customer_guid'"),
                'supcus_customer' => $this->db->query("SELECT * from b2b_summary.cp_set_branch where branch_code = '" . $gr_info->row('Location') . "' and customer_guid = '$customer_guid'"),
                'customer_branch_info' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '" . $gr_info->row('Location') . "'   and customer_guid = '$customer_guid'"),
                'retailer_acc' => $this->db->query("SELECT acc_regno FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'"),
            );
            // echo $this->db->last_query();die;

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            $from_module = $_SESSION['frommodule'];
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $req_refno;
            $user_guid = $_SESSION['user_guid'];

            if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {

                /*  $this->db->query("UPDATE b2b_summary.grmain set status = 'EINV_GENERATED' where status = 'viewed' and customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno' ");
                */
                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_ecn'
                , '$from_module'
                , '$refno'
                , now()
                ");
            }


            $load_pdf = $this->load->view('gr/panda_ecn_pdf', $data, true);
            $this->load->library('Pdf_ecn');
            $pdf = new Pdf_ecn('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor('xBridge');
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont('helvetica', '', $font_size, '', 'default', true);
            $pdf->AddPage('L');
            ob_start();
            $pdf->writeHTML($load_pdf, true, false, true, false, '');
            ob_end_clean();
            //$pdf->Output($_SERVER['DOCUMENT_ROOT'] .'invoice/invoice/B2B_'.$invoice_number.'.pdf', 'F');           
            //$pdf->Output($_SERVER['DOCUMENT_ROOT'] .'invoice/invoice/B2B_'.$invoice_number.'.pdf', 'F');           

            //  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            //  // echo 'This is a server using Windows!';die;

            //     $path = $_SERVER['DOCUMENT_ROOT'] .'lite_panda_b2b'.$virtual_path.'/invoice/B2B_'.$invoice_number.'.pdf';
            //     $pdf->Output( $path, 'F');  
            // } 
            // else {
            //     //echo 'This is a server not using Windows!';die;
            //     $path = $_SERVER['DOCUMENT_ROOT'] .$virtual_path.'/invoice/B2B_'.$invoice_number.'.pdf';
            //     $pdf->Output( $path, 'F');  
            // }

            $data = array(

                'filename' =>  'B2B_' . $invoice_number . '.pdf',
                'path' =>  $path,

            );
            // var_dump($data);die;

            ob_end_clean();
            $pdf->Output($req_refno . $transtype, 'I');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function update_invno()
    {
        $new_invno = $this->input->post('new_invno');
        $new_invno = json_encode($new_invno);
        $new_invno = json_decode($new_invno);

        $customer_guid = $_SESSION['customer_guid'];

        //print_r($new_invno); die;
        foreach ($new_invno as $row) {

            $header_refno = $row->refno;
            $xinvno = $row->invno;

            $check_einvmain = $this->db->query("SELECT * FROM b2b_summary.einv_main where customer_guid = '$customer_guid' and refno = '$header_refno'");

            if ($check_einvmain->num_rows() > 0) {
                $einv_no = $xinvno;
                $this->db->query("UPDATE b2b_summary.einv_main SET einvno = '$einv_no' where customer_guid = '$customer_guid' and refno = '$header_refno'
                 ");
                // echo $this->db->last_query();die;
            }


            $xxerror = $this->db->error();
            $error = $this->db->affected_rows();

            if ($error <= 0 && $xxerror['code'] != 0) {
                $error_message = "Update Failed";
                $xerror = $this->db->error();
                $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
                $this->message->error_message($xerror['message'], '1');
                exit();
            } //close else


            $check_grmain_proposed = $this->db->query("SELECT * FROM b2b_summary.grmain_proposed WHERE customer_guid = '$customer_guid' AND RefNo = '$header_refno' ");

            if ($check_grmain_proposed->num_rows() > 0) {

                $this->db->query("UPDATE b2b_summary.grmain_proposed AS a
                INNER JOIN b2b_summary.grmain_info AS b
                ON a.customer_guid = b.customer_guid
                AND a.refno = b.refno
                SET
                a.location = b.loc_group
                , a.grdate = b.grdate
                , a.issuestamp = CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`gr_json_info`,'$.grmain[0].IssueStamp')) AS DATETIME )
                , a.laststamp = CAST(JSON_UNQUOTE(JSON_EXTRACT(b.`gr_json_info`,'$.grmain[0].LastStamp')) AS DATETIME )
                , a.code = b.supplier_code
                , a.name = b.supplier_name
                , a.InvNo = '$xinvno'
                where a.customer_guid = '$customer_guid' and a.refno = '$header_refno' and a.posted = '0'
                 ");
            } else {
                $grmain = $this->db->query("SELECT * FROM b2b_summary.grmain_info WHERE RefNo = '$header_refno' ");

                $data1[] = [
                    'customer_guid' => $customer_guid,
                    'status' => '',
                    'refno' => $header_refno,
                    'invno' => $xinvno,
                    'dono' => $grmain->row('DONo'),
                    'docdate' => $grmain->row('DocDate'),
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' =>  $_SESSION['user_guid'],
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $_SESSION['user_guid'],
                ];
            }
        }

        if (isset($data1)) {

            $table = 'b2b_summary.grmain_proposed';
            $this->db->insert_batch($table, $data1);

            $xxerror = $this->db->error();
            $error = $this->db->affected_rows();
        }

        if ($error > 0 || $xxerror['code'] == 0) {

            $success_msg = "Update Successfully";
            $button = '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            $this->message->success_message($button, $success_msg, '');
        } else {
            $error_message = "Update Failed";
            $xerror = $this->db->error();
            $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
            $this->message->error_message($xerror['message'], '1');
            exit();
        } //close else

    }

    public function fetch_display_message()
    {
        $customer_guid = $this->session->userdata('customer_guid');
        $refno = $this->input->post('refno');

        $database1 = 'b2b_summary';

        $check_grda = $this->db->query("SELECT * FROM $database1.grmain_dncn_info WHERE customer_guid = '$customer_guid' AND refno = '$refno'");

        $check_grmain_proposed_insert = $this->db->query("SELECT * FROM $database1.grmain_proposed WHERE customer_guid = '$customer_guid' AND refno = '$refno'");

        if ($check_grmain_proposed_insert->num_rows() > 0) {
            $grmain_proposed_insert = 1;
        } else {
            $grmain_proposed_insert = 0;
        }

        // echo $this->db->last_query();die;
        if ($check_grda->num_rows() > 0) {
            $check_grmain_dncn_proposed_insert = $this->db->query("SELECT * FROM b2b_summary.grmain_dncn_proposed WHERE customer_guid = '$customer_guid' AND refno = '$refno'");

            if ($check_grmain_dncn_proposed_insert->num_rows() > 0) {
                $grmain_dncn_proposed_insert = 1;
            } else {
                $grmain_dncn_proposed_insert = 0;
            }

            if ($grmain_proposed_insert == 1 && $grmain_dncn_proposed_insert == 1) {
                $message = 'Yes to confirm generate E-Invoice and E-CN';
            } else if ($grmain_proposed_insert == 1 && $grmain_dncn_proposed_insert == 0) {
                $message = 'Please enter valid E-CN No. Click Yes to have system default GRDA Refno as E-CN NO and generate E-Invoice and E-CN';
            } else if ($grmain_proposed_insert == 0 && $grmain_dncn_proposed_insert == 1) {
                $message = 'Please enter valid E-Invoice No. Click Yes to have system default Supplier Invoice No as E-Invoice NO and generate E-Invoice and E-CN';
            } else if ($grmain_proposed_insert == 0 && $grmain_dncn_proposed_insert == 0) {
                $message = 'Please enter valid E-Invoice No and E-CN No.Click Yes to have system default E-Invoice No/Saved E-Invoice No and default GRDA Refno/Saved E-CN No as E-Invoice No and E-CN No';
            } else {
                $message = 'Description Not Exist. Please Contact Support.';
            }
        } else {
            if ($grmain_proposed_insert == 1) {
                $message = 'E-Invoice will be generated. Click Yes to proceed';
            } else if ($grmain_proposed_insert == 0) {
                $message = 'Please enter valid E-Invoice No. Click Yes to have system default Supplier Invoice No as E-Invoice and generate E-Invoice';
            } else {
                $message = 'Description Not Exist. Please Contact Support.';
            }
        }

        $data = array(
            'status' => 'true',
            'message' => $message,
        );

        echo json_encode($data);
        die;
    }

    public function bulk_convert_e_invoice()
    {
        // print_r($this->session->userdata('userid'));die;
        $user_id = $this->session->userdata('userid');
        $user_guid = $this->session->userdata('user_guid');
        $from_module = $_SESSION['frommodule'];

        $lite_b2b = 'lite_b2b';
        $b2b_summary = 'b2b_summary';

        $details = $this->input->post('details');
        //$status = $this->input->post('status');
        //$loc = $this->input->post('loc');
        // echo $loc;die;
        $details = explode(',', $details);
        //print_r($details);die;

        $customer_guid = $this->session->userdata('customer_guid');

        $error_refno = '';

        //$haha = $this->load->view('print/invoice_pdf', $data, true);
        $this->load->library('Pdf_invoice_json');
        $pdf = new Pdf_invoice_json('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Bulk E-Invoice');
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetAuthor('B2B');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->setPageUnit('pt');
        $x = $pdf->pixelsToUnits('20');
        $y = $pdf->pixelsToUnits('20');
        $font_size = $pdf->pixelsToUnits('9.5');
        $pdf->SetFont('helvetica', '', $font_size, '', 'default', true);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        // foreach ($details as $refno2) {
        //     $check_gr_cn_exists = $this->db->query("SELECT * FROM b2b_summary.grmain_dncn_info a LEFT JOIN b2b_summary.`ecn_main` b ON a.`RefNo` = b.`refno` AND a.`customer_guid` = b.`customer_guid` WHERE a.refno = '$refno2' AND a.customer_guid = '$customer_guid' AND b.`refno` IS NULL");
        //     if ($check_gr_cn_exists->num_rows() > 0) {
        //         $this->session->set_flashdata('warning', 'Bulk Generation not support for GRN which have GRDA, Please generate ECN first.');
        //         redirect('b2b_gr/view_status?status=' . $status . '&loc=' . $loc . '&p_f=&p_t=&e_f=&e_t=&r_n=');
        //         https: //b2b2.xbridge.my/index.php/general/view_status?status=&loc=HQ&p_f=&p_t=&e_f=&e_t=&r_n=
        //     }
        // }
        // current only tf get grchild data at b2b_summary.grmain_info

        foreach ($details as $refno) {
            $deletePage = 0;
            $error = 0;
            $einv_log_process_error = 0;
            $add_msg = '';
            $httpcode = 200;

            $check_process = $this->db->query("SELECT * FROM lite_b2b.einv_process_log WHERE refno = '$refno' AND customer_guid = '$customer_guid'")->result_array();

            if(count($check_process) == 0 )
            {
                $insert_process_log = $this->db->query("INSERT INTO lite_b2b.einv_process_log (customer_guid,refno,`status`,created_at,created_by) VALUES('$customer_guid', '$refno','1',NOW(),'$user_guid')");
            }
            else
            {
                $date = $check_process[0]['created_at'];
                $curdate = date("Y-m-d H:i:s");
                $start = strtotime($date);
                $end = strtotime($curdate);
                $mins = ($end - $start) / 60;
    
                if($mins >= '5')
                {
                    $reupdate_process_log = $this->db->query("UPDATE lite_b2b.einv_process_log SET `status` = '3',created_at = NOW() WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid'");
                }
                else
                {
                    $einv_log_process_error = 88;
                }
                
            }
            
            $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');

            $grmain = $this->db->query("SELECT a.`customer_guid`
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
            , a.total
            , a.gst_tax_sum
            , a.total_include_tax
            , IF(c.einvno IS NOT NULL,c.einvno,IF(b.invno IS NULL,a.invno,b.invno)) as einvno
            , IF(c.inv_date IS NOT NULL,c.inv_date,IF(b.docdate IS NULL,CURDATE(),b.docdate)) as einv_date
            , a.subtotal1
            , a.cross_ref
            , IFNULL(a.total_include_tax - SUM(d.`VarianceAmt`), a.total ) AS after_amount
            , a.gr_json_report
            FROM b2b_summary.grmain_info AS a 
            LEFT JOIN b2b_summary.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid 
            LEFT JOIN b2b_summary.einv_main c ON a.refno = c.refno AND a.customer_guid = c.customer_guid 
            LEFT JOIN b2b_summary.`grmain_dncn_info` d ON a.`RefNo` = d.`RefNo` AND a.`customer_guid` = d.`customer_guid`
            where a.refno = '$refno' and a.customer_guid = '$customer_guid'
            GROUP BY a.refno");

            $einv_main = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $H_refno = $grmain->row('RefNo');
            $H_invno = $grmain->row('InvNo');
            $H_dono = $grmain->row('DONo');
            $H_inv_date = $grmain->row('DocDate');
            $H_gr_date = $grmain->row('GRDate');
            $H_total_excl_tax = $grmain->row('total_include_tax');
            $H_tax_amount = $grmain->row('gst_tax_sum');
            $H_total_incl_tax = $grmain->row('total_include_tax');
            $H_supplier_code = $grmain->row('supplier_code');
            $H_location = $grmain->row('loc_group');

            ##check b2b invno
            $check_if_exists_einv = $this->db->query("SELECT * FROM b2b_summary.einv_main WHERE refno != '$H_refno' AND customer_guid = '$customer_guid' AND einvno = '$H_invno'");

            // echo $this->db->last_query();die;
            if ($check_if_exists_einv->num_rows() > 0) {
                // echo 'invoice number duplicate';
                $check_if_exists_einv2 = $this->db->query("SELECT * FROM b2b_summary.grmain_info WHERE refno = '$H_refno' AND customer_guid = '$customer_guid'");
                $check_if_exists_einv2_code = $check_if_exists_einv2->row('supplier_code');
                $check_if_exists_einv2_supcode = $this->db->query("SELECT b.* FROM b2b_summary.supcus a LEFT JOIN b2b_summary.`supcus` b ON a.`AccountCode` = b.`AccountCode` AND a.`customer_guid` = b.customer_guid WHERE a.code = '$check_if_exists_einv2_code' AND a.customer_guid = '$customer_guid' GROUP BY b.`customer_guid`,b.code");

                $check_if_exists_einv2_supcode_string = '';
                foreach ($check_if_exists_einv2_supcode->result() as $row5) {
                    $check_if_exists_einv2_supcode_string .= "'" . $row5->Code . "',";
                }
                $check_if_exists_einv2_supcode_string2 = rtrim($check_if_exists_einv2_supcode_string, ',');
                // echo rtrim($check_if_exists_einv2_supcode_string,',').'sdsd<br>';
                $check_if_exists_einv3 = $this->db->query("SELECT b.* FROM b2b_summary.einv_main a INNER JOIN b2b_summary.grmain_info b ON a.`customer_guid` = b.`customer_guid` AND a.refno = b.refno WHERE a.refno != '$H_refno' AND a.customer_guid = '$customer_guid' AND a.einvno = '$H_invno' AND b.supplier_code IN($check_if_exists_einv2_supcode_string2)");

                if ($check_if_exists_einv3->num_rows() > 0) {
                    $error = '99';
                    $add_msg = 'Duplicate Inv Number ' . $check_if_exists_einv3->row('RefNo');
                }
                // echo $this->db->last_query();die;
            }

            ## check hugh API inv_no
            $acc_setting_query = $this->db->query("SELECT a.e_document_copy, IF(CURDATE() >= a.einv_grab_date , 'Yes', 'No') AS check_inv_status
            FROM lite_b2b.acc_settings AS a
            WHERE a.customer_guid = '$customer_guid'");
            
            $e_document_copy = $acc_setting_query->row('e_document_copy');
            $check_inv_status = $acc_setting_query->row('check_inv_status');
    
            // if($check_inv_status == 'Yes')
            // {
            //     $store_refno = '';
            //     $public_ip_check = $this->db->query("SELECT public_ip from lite_b2b.acc where acc_guid = '$customer_guid'")->row('public_ip');
            //     // $check_url = "http://202.75.55.22/rest_api/index.php/return_json";
            //     $to_check_duplicate = $public_ip_check . "/rest_api/index.php/panda2finance/check_duplicate";
            //     //echo $to_check_duplicate ;die;
    
            //     $data_check_einv = array(
            //         "refno" => $H_refno,
            //         "doctype"  => 'GRN',
            //         "code"  => $H_supplier_code,
            //         "invno"  => $H_invno,
            //     );
            //     //print_r(json_encode($data_check_einv)); die;
            //     $data_encode = json_encode($data_check_einv);

            //     $cuser_name = 'ADMIN';
            //     $cuser_pass = '1234';
    
            //     $ch = curl_init($to_check_duplicate);
            //     // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            //     curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //     curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            //     curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            //     curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            //     curl_setopt($ch, CURLOPT_POST, 1);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_check_einv));
            //     //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
            //     $result = curl_exec($ch);
            //     $output_check_inv = json_decode($result);
            //     $array_output = json_decode(json_encode($output_check_inv->result), true);
            //     // $status = json_encode($output);
            //     //print_r($result);die;
            //     //print_r($output);die;
            //     //echo $result;die;
            //     curl_close($ch);
                
            //     if(isset($output_check_inv->status))
            //     {
            //         if($output_check_inv->status == false)
            //         {
            //             foreach($array_output as $row)
            //             {
            //                 //print_r($row['refno']); die;
            //                 $store_refno .= $row['refno'] . ',';
            //             }
            //             $store_refno = rtrim($store_refno, ',');
            //             $error = 99;
            //             $add_msg = 'Duplicate Inv Number ' . $store_refno;
            //         }
            //     }
            //     else
            //     {
            //         $insert_shoot_hq = $this->db->query("INSERT INTO lite_b2b.check_duplicate_log (customer_guid,refno,data_array,process_status,created_at,created_by) VALUES('$customer_guid','$H_refno','$data_encode','Retry',NOW(),'$user_guid')");
            //         // shoot 1 more time
            //         $store_refno = '';
            //         // $public_ip_check = $this->db->query("SELECT public_ip from lite_b2b.acc where acc_guid = '$customer_guid'")->row('public_ip');
            //         // // $check_url = "http://202.75.55.22/rest_api/index.php/return_json";
            //         // $to_check_duplicate = $public_ip_check . "/rest_api/index.php/panda2finance/check_duplicate";
            //         // //echo $to_check_duplicate ;die;
        
            //         // $data_check_einv = array(
            //         //     "refno" => $H_refno,
            //         //     "doctype"  => 'GRN',
            //         //     "code"  => $H_supplier_code,
            //         //     "invno"  => $H_invno,
            //         // );
            //         // //print_r(json_encode($data_check_einv)); die;
        
            //         // $cuser_name = 'ADMIN';
            //         // $cuser_pass = '1234';
        
            //         $ch = curl_init($to_check_duplicate);
            //         // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
            //         curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //         curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            //         curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
            //         curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
            //         curl_setopt($ch, CURLOPT_POST, 1);
            //         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_check_einv));
            //         //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
            //         $result = curl_exec($ch);
            //         $output_check_inv_v2 = json_decode($result);
            //         $array_output_v2 = json_decode(json_encode($output_check_inv_v2->result), true);
            //         // $status = json_encode($output);
            //         //print_r($result);die;
            //         //print_r($output);die;
            //         //echo $result;die;
            //         curl_close($ch);
                    
            //         if(isset($output_check_inv_v2->status))
            //         {
            //             $insert_shoot_hq = $this->db->query("INSERT INTO lite_b2b.check_duplicate_log (customer_guid,refno,data_array,process_status,created_at,created_by) VALUES('$customer_guid','$H_refno','$data_encode','Retry_Success',NOW(),'$user_guid')");

            //             if($output_check_inv_v2->status == false)
            //             {
            //                 foreach($array_output_v2 as $row)
            //                 {
            //                     //print_r($row['refno']); die;
            //                     $store_refno .= $row['refno'] . ',';
            //                 }
            //                 $store_refno = rtrim($store_refno, ',');
            //                 $error = 99;
            //                 $add_msg = 'Duplicate Inv Number ' . $store_refno;
            //             }
            //         }
            //         else
            //         {
            //             $error = 99;
            //             $add_msg = 'Process Checking Einv Invalid';
            //             $insert_shoot_hq = $this->db->query("INSERT INTO lite_b2b.check_duplicate_log (customer_guid,refno,data_array,process_status,created_at,created_by) VALUES('$customer_guid','$H_refno','$data_encode','Retry_Failed',NOW(),'$user_guid')");
            //         }
            //     }
            // }

            if($check_inv_status == 'Yes')
            {
                $store_refno = '';

                $url = $this->api_url;
                $to_check_duplicate = $url."/E_invoice_validate/grn_einv_checking";
                // $to_check_duplicate = "20.212.51.33/rest_b2b/index.php/E_invoice_validate/grn_einv_checking";
                // echo $to_check_duplicate ;die;
    
                $data_check_einv = array(
                    "customer_guid" => $customer_guid,
                    "refno" => $H_refno,
                    "doctype"  => 'GRN',
                    "code"  => $H_supplier_code,
                    "invno"  => $H_invno,
                    "loc_group" => $H_location,
                );

                //print_r(json_encode($data_check_einv)); die;
    
                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';
    
                $ch = curl_init($to_check_duplicate);
                // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_check_einv));
                //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
                $result = curl_exec($ch);
                $output_check_inv = json_decode($result);
                $array_output = json_decode(json_encode($output_check_inv->result), true);
                // $status = json_encode($output);
                //print_r($result);die;
                // print_r($array_output);die;
                //echo $result;die;
                curl_close($ch);
                
                if(isset($output_check_inv->status))
                {
                    if($output_check_inv->status == false)
                    {
                        foreach($array_output as $row)
                        {
                            //print_r($row['refno']); die;
                            $store_refno .= $row['refno'] . ',';
                        }
                        $store_refno = rtrim($store_refno, ',');
                        $error = 99;
                        $add_msg = $output_check_inv->message. ' ' . $store_refno;
                    }
                }
                else
                {
                    $error = 99;
                    $add_msg = 'Process Checking Einv Invalid';
                }
            }

            $pay_by_grn = $this->db->query("SELECT * FROM b2b_summary.grmain_info a INNER JOIN b2b_summary.supcus b ON a.supplier_code = b.Code WHERE a.Refno = '$refno' AND a.customer_guid = '$customer_guid' AND b.grn_baseon_pocost = 0 AND b.type = 'S' AND b.customer_guid = '$customer_guid'");

            $pay_by_grn_status = $pay_by_grn->num_rows() > 0 ? 1 : 0;
            $pay_by_grn_status = 1;

            if($error == 0)
            {
                $json_upload_data = $this->db->query("SELECT IF('$H_gr_date' >= `start_date`, 'Yes', 'No') AS valid_upload FROM b2b_summary.b2b_json_upload WHERE customer_guid = '$customer_guid' AND document_type = 'grmain' AND active = '1'")->row('valid_upload');

                if ($json_upload_data == 'Yes') {
                    //get full grmain json info data
                    $get_grn_child_data = $this->db->query("SELECT a.`gr_json_info`
                    FROM b2b_summary.`grmain_info` AS a
                    WHERE a.`refno` = '$H_refno'
                    AND a.`customer_guid`='$customer_guid'")->row('gr_json_info');

                    if(count(json_decode($get_grn_child_data, true)['grchild']) == 0)
                    {
                        $check_url = $this->db->query("SELECT rest_url from $lite_b2b.acc where acc_guid = '$customer_guid' ")->row('rest_url');

                        $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $H_refno;
                        //  echo $to_shoot_url ;die;
                        $ch = curl_init($to_shoot_url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                        $response = curl_exec($ch);
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $output = json_decode($response);
                    }

                } else {
                    $check_url = $this->db->query("SELECT rest_url from $lite_b2b.acc where acc_guid = '$customer_guid' ")->row('rest_url');

                    $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $H_refno;
                    //  echo $to_shoot_url ;die;
                    $ch = curl_init($to_shoot_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                    $response = curl_exec($ch);
                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $output = json_decode($response);
                }

                if (($httpcode == 200) && ($grmain->num_rows() > 0) && ($output[0]->line != 'No Records Found') && ($pay_by_grn_status == 1) || count(json_decode($get_grn_child_data, true)['grchild']) > 0) {
                    if ($einv_main->num_rows() > 0) {
                        $affected_rows = 0;
    
                        // $get_einv_guid = $einv_main->row('einv_guid');
                        $error_refno .= $refno . ' Invoice Already Generated,\n';
                        $deletePage = 1;
                        $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','EAG','E-Invoice ALready Generated',NOW(),'$user_guid')");
                    } else {
                        //insert
                        $data = array(
                            'einv_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                            'customer_guid' => $customer_guid,
                            'refno' => $refno,
                            'einvno' => addslashes($H_invno),
                            'invno' => addslashes($H_invno),
                            'dono' => addslashes($H_dono),
                            //'inv_date'=> $H_inv_date,
                            'inv_date' => $this->db->query("SELECT CURDATE() as curdate")->row('curdate'),
                            'gr_date' => $H_gr_date,
                            'einv_generated_date' => $this->db->query("SELECT CURDATE() as curdate")->row('curdate'),
                            'total_excl_tax' => $H_total_excl_tax,
                            'tax_amount' => $H_tax_amount,
                            'total_incl_tax' => $H_total_incl_tax,
                            'created_at' => $this->db->query("select now() as naw")->row('naw'),
                            'created_by' =>  $user_guid,
                            'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                            'updated_by' => $user_guid,
                        );
    
                        $this->db->insert("$b2b_summary.einv_main", $data);
                        //echo $this->db->last_query(); die;
                        $get_einv_guid = $this->db->query("SELECT einv_guid from $b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'")->row('einv_guid');
                        $affected_rows = $this->db->affected_rows();
    
                        $this->db->query("UPDATE b2b_summary.grmain_info a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_excl_tax = JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].subtotal1')) WHERE JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].subtotal1')) <> b.total_excl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");
    
                        $this->db->query("UPDATE b2b_summary.grmain_info a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_incl_tax = JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].total_include_tax')) WHERE JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].total_include_tax')) <> b.total_incl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");
                    } //close else for checking einv_main exist or not
    
    
                    if ($affected_rows > 0) {
                        $this->db->query("DELETE FROM $b2b_summary.einv_child where einv_guid = '$get_einv_guid'");
                        // tempary solution
                        if (count(json_decode($get_grn_child_data, true)['grchild']) > 0) {
                            foreach (json_decode($get_grn_child_data, true)['grchild'] as $output_row) {
    
                                $itemcode = $output_row['Itemcode'];
                                $supcheck = 0;
                                $line = $output_row['Line'];
                                $barcode = $output_row['barcode'];
                                $description = $output_row['Description'];
                                $packsize = $output_row['PackSize'];
                                $qty = $output_row['Qty'];
                                $uom = $output_row['UM'];
                                $unitprice = number_format($output_row['UnitPrice'], 4);
                                $disc_desc = $output_row['Disc2Type'] . number_format($output_row['Disc2Value'], 2);
                                $discamt = $output_row['DiscAmt'];
                                $unit_disc_prorate = ($output_row['hcost_gr'] == 0) ? number_format($output_row['hcost_gr'], 4) : number_format($output_row['hcost_gr'] / $output_row['Qty'], 4);
                                $unit_price_bfr_tax = ($output_row['hcost_gr'] == 0) ? number_format($output_row['NetUnitPrice'], 4) : number_format($output_row['TotalPrice'] - $output_row['hcost_gr'] / $output_row['Qty'], 4);
                                $totalprice = $output_row['TotalPrice'];
                                $gst_tax_amount =  number_format($output_row['gst_tax_amount'], 4);
                                $gst_unit_total = number_format((($output_row['TotalPrice'] - number_format($output_row['hcost_gr'], 2)) + $output_row['gst_tax_amount']), 2);
    
                                $data =  array(
                                    'child_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                                    'einv_guid' => $get_einv_guid,
                                    'line' => $line,
                                    'itemtype' => '',
                                    /*'itemlink' => $itemcode,*/
                                    'itemcode' => $itemcode,
                                    'barcode' => $barcode,
                                    'description' => $description,
                                    'packsize' => $packsize,
                                    'qty' => $qty,
                                    'uom' => $uom,
                                    'unit_price_before_disc' => $unitprice,
                                    'item_discount_description' => $disc_desc,
                                    'item_disc_amt' => $discamt,
                                    'total_bill_disc_prorated' => $unit_disc_prorate,
                                    'total_amt_excl_tax' => $unit_price_bfr_tax * $qty,
                                    'total_tax_amt' => $gst_tax_amount,
                                    'total_amt_incl_tax' => $totalprice,
                                    'checked' => $supcheck,
                                    'checked_at' => $this->db->query("select now() as naw")->row('naw'),
                                    'checked_by' => $user_guid,
                                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                                    'created_by' => $user_guid,
                                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                                    'updated_by' => $user_guid,
                                );
    
                                $this->db->insert("$b2b_summary.einv_child", $data);
                            } //close json foreach child
    
                        } //close else for checking einv_main got update or insert or not
                        else {
                            foreach ($output as $output_row) {
    
                                $itemcode = $output_row->itemcode;
                                $supcheck = 0;
                                $line = $output_row->line;
                                $barcode = $output_row->barcode;
                                $description = $output_row->description;
                                $packsize = $output_row->packsize;
                                $qty = $output_row->qty;
                                $uom = $output_row->um;
                                $unitprice = $output_row->unitprice;
                                $disc_desc = $output_row->disc_desc;
                                $discamt = $output_row->discamt;
                                $unit_disc_prorate = $output_row->unit_disc_prorate;
                                $unit_price_bfr_tax = $output_row->unit_price_bfr_tax;
                                $totalprice = $output_row->totalprice;
                                $gst_tax_amount = $output_row->gst_tax_amount;
                                $gst_unit_total = $output_row->gst_unit_total;
    
    
                                $data =  array(
                                    'child_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                                    'einv_guid' => $get_einv_guid,
                                    'line' => $line,
                                    'itemtype' => '',
                                    /*'itemlink' => $itemcode,*/
                                    'itemcode' => $itemcode,
                                    'barcode' => $barcode,
                                    'description' => $description,
                                    'packsize' => $packsize,
                                    'qty' => $qty,
                                    'uom' => $uom,
                                    'unit_price_before_disc' => $unitprice,
                                    'item_discount_description' => $disc_desc,
                                    'item_disc_amt' => $discamt,
                                    'total_bill_disc_prorated' => $unit_disc_prorate,
                                    'total_amt_excl_tax' => $unit_price_bfr_tax * $qty,
                                    'total_tax_amt' => $gst_tax_amount,
                                    'total_amt_incl_tax' => $totalprice,
                                    'checked' => $supcheck,
                                    'checked_at' => $this->db->query("select now() as naw")->row('naw'),
                                    'checked_by' => $user_guid,
                                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                                    'created_by' => $user_guid,
                                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                                    'updated_by' => $user_guid,
                                );
    
                                $this->db->insert("$b2b_summary.einv_child", $data);
                            } //close json foreach child
    
                        } //close else for checking einv_main got update or insert or not
    
                        $update_process_log = $this->db->query("UPDATE lite_b2b.einv_process_log SET `status` = '2',updated_at = NOW() WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid'");
                    }
    
    
    
    
                    $header = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");
    
                    $einv_guidd = $this->db->query("SELECT einv_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ")->row('einv_guid');
    
                    $child_info = $this->db->query("SELECT * FROM $b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by line asc");
    
                    $this->db->query("UPDATE b2b_summary.grmain_info set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");
    
                    //$this->db->query("UPDATE b2b_summary.grmain_info set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");
    
    
                    /*  $this->db->query("UPDATE b2b_summary.grmain set status = 'EINV_GENERATED' where status = 'viewed' and customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno' ");
                */
                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'bulk_generate_inv'
                    , '$from_module'
                    , '$refno'
                    , now()
                    ");
                    if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                        $this->db->query("UPDATE b2b_summary.grmain_info set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");
    
                        //$this->db->query("UPDATE b2b_summary.grmain_info set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");
    
    
                        /*  $this->db->query("UPDATE b2b_summary.grmain set status = 'EINV_GENERATED' where status = 'viewed' and customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno' ");
                    */
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
    
                    // HUGH 2019-04-25
    
    
    
                    $pdf->AddPage('L', 'A4');
                    $pdf->RefNo = $refno;
                    // ob_start();
    
                    $com = new Panda("");
                    $test = $com->get_serverdb();
    
    
                    $conn = new mysqli($test['servername'], $test['username'], $test['password'], $test['dbname']);
    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
    
    
    
                    $header_customer_guid = "SELECT customer_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
                    $result = $conn->query($header_customer_guid);
                    $header_customer_guid = $result->fetch_assoc();
    
    
                    // $header_customer_info = "SELECT BRANCH_CODE,BRANCH_NAME,REPLACE(BRANCH_ADD,'\n','<br>') AS BRANCH_ADD, BRANCH_TEL, BRANCH_FAX,comp_reg_no , gst_no FROM backend.`cp_set_branch` JOIN (SELECT comp_reg_no , gst_no FROM backend.`companyprofile` LIMIT 1 )a ";
                    // $result = $conn->query($header_customer_info);
                    // $header_customer_info = $result->fetch_assoc();
    
    
    
    
                    $header_main = "SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
                    $result = $conn->query($header_main);
                    $header_main = $result->fetch_assoc();
    
    
    
                    $customer_hq_branch_info = "SELECT * FROM $b2b_summary.cp_set_branch WHERE SET_SUPPLIER_CODE = 'HQ' AND SET_CUSTOMER_CODE = 'HQ' AND customer_guid = '$customer_guid'";
                    $result = $conn->query($customer_hq_branch_info);
                    $customer_hq_branch_info = $result->fetch_assoc();
    
    
    
                    $gr_info = "SELECT 
                    a.`loc_group` as Location
                    , a.`supplier_code` as Code
                    , a.`supplier_name` as Name
                    , a.`Invno`
                    FROM $b2b_summary.grmain_info AS a 
                    LEFT JOIN $b2b_summary.grmain_proposed AS b 
                    ON a.refno = b.refno 
                    AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '" . $header_customer_guid['customer_guid'] . "'";
                    $result = $conn->query($gr_info);
                    $gr_info = $result->fetch_assoc();
    
                    //??? condition code but find by location
                    $supcus_customer = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid'";
                    $result = $conn->query($supcus_customer);
                    $supcus_customer = $result->fetch_assoc();
    
                    $supcus_supplier = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Code'] . "' AND customer_guid = '$customer_guid'";
                    $result = $conn->query($supcus_supplier);
                    $supcus_supplier = $result->fetch_assoc();
    
    
                    $customer_branch_info = "SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid'";
                    $result = $conn->query($customer_branch_info);
                    $customer_branch_info = $result->fetch_assoc();
    
    
                    $pdf->SetFont('helvetica', '', 9.5);
    
                    $html = '<table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;"> <tbody><tr> <td style="width: 80%;text-align: left"> <table cellspacing="0" cellpadding="0"> <tbody> <tr> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Purchase from Registered GST Supplier </td> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Goods Received Note Issued by </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $supcus_supplier['Name'] . '</b> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $customer_branch_info['BRANCH_NAME'] . ' </b> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_supplier['reg_no'] . ' </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_supplier['reg_no'] . '</td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $supcus_supplier['Add1'] . '
                    <br>' . $supcus_supplier['Add2'] . '
                    <br>' . $supcus_supplier['Add3'] . '
                    <br>' . $supcus_supplier['Add4'] . '<br>
                    </td>
                     </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $customer_branch_info['BRANCH_ADD'] . '<br> </td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $customer_branch_info['BRANCH_TEL'] . ' <b>  Fax:</b> ' . $customer_branch_info['BRANCH_FAX'] . '</td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><b>Sup Code:</b> ' . $gr_info['Code'] . ' - ' . $gr_info['Name'] . ' <b><br>Received Loc:</b> ' . $gr_info['Location'] . ' - ' . $supcus_supplier['Name'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td colspan="2"><b>Tax Invoice No:</b> ' . $header_main['invno'] . ' <b><br>Delivery Order:</b> ' . $header_main['dono'] . '</td> <td><b>Invoice Date:</b> ' . $header_main['inv_date'] . ' <b><br>Ref No:</b> ' . $header_main['refno'] . '</td> </tr></tbody></table> </td> </tr> </tbody> </table> </td> <td style="width: 20%;"> <table id="right-table" border="0" cellspacing="0" cellpadding="0" style="width: 100%;height:500px;"> <tbody style="height:500px;"> <tr> <td style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>E-Invoice</b></p></td> </tr> <tr> <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Inv No</p><p style="font-size:12px;"><b>' . $header_main['einvno'] . '</b></p></td> </tr> </tbody> </table> </td> </tr> </tbody></table>';
                    if ($deletePage == 1) {
                        $html = '';
                        // $pdf->deletePage($pdf->PageNo());
                    }
                    $pdf->writeHTML($html, true, false, true, false, '');
    
    
    
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
                    if ($deletePage == 1) {
                        $html = '';
                        // $pdf->deletePage($pdf->PageNo());
                    }
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
                    
                    <td style="width:23.66%">' . $row->description . '</td>
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
                        if ($deletePage == 1) {
                            $html = '';
                            // $pdf->deletePage($pdf->PageNo());
                        }
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
                                
                                <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">The parties hereby agree that the actual physical amount of goods received will be reflected in the Goods Received Note(GRN) notwithstanding validation of the supplierinvoice. The parties also hereby agree that in the event the total purchase price in the GRN and the supplier Invoice different amount, we shall pay the lower amount of the two. 
                                
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
                    if ($deletePage == 1) {
                        $html = '';
                        $pdf->deletePage($pdf->PageNo());
                    }
                    $pdf->writeHTML($html, true, false, true, false, '');
                } //check api shoot success and grmain got the result or not
                else {
                    $pdf->AddPage('L', 'A4');
                    $pdf->RefNo = '';
                    $html = '';
                    $error_refno .= $refno . ' E-Invoice Generated Error,Please Contact Admin ,\n';
                    $pdf->deletePage($pdf->PageNo());
                    if ($httpcode != 200) {
                        $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error Not 200_1',NOW(),'$user_guid')");
                    } else if ($grmain->num_rows() == 0) {
                        $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error Missing grmain',NOW(),'$user_guid')");
                    } else if ($output[0]->line == 'No Records Found') {
                        $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error No Records Found',NOW(),'$user_guid')");
                    } else if ($pay_by_grn_status != 1) {
                        $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error pay_by_grn_status Not 200_2',NOW(),'$user_guid')");
                    } else {
                        $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error',NOW(),'$user_guid')");
                    }
                    // echo 'no data';die;

                    if($einv_log_process_error != 88)
                    {
                        $delete_process_log = $this->db->query("DELETE FROM `lite_b2b`.`einv_process_log` WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND `status` = '1'");
                    }
                }
            }
            else
            {
                $pdf->AddPage('L', 'A4');
                $pdf->RefNo = '';
                $html = '';
                $error_refno .= $refno . ' E-Invoice Generated Error. '. $add_msg .' Please Contact Admin ,\n';
                $pdf->deletePage($pdf->PageNo());
                if ($httpcode != 200) {
                    $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error Not 200_1',NOW(),'$user_guid')");
                } else if ($grmain->num_rows() == 0) {
                    $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error Missing grmain',NOW(),'$user_guid')");
                } else if ($output[0]->line == 'No Records Found') {
                    $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error No Records Found',NOW(),'$user_guid')");
                } else if ($pay_by_grn_status != 1) {
                    $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error pay_by_grn_status Not 200_2',NOW(),'$user_guid')");
                } else if ($error == '99') {
                    $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','$add_msg',NOW(),'$user_guid')");
                } else {
                    $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error',NOW(),'$user_guid')");
                }

                if($einv_log_process_error != 88)
                {
                    $delete_process_log = $this->db->query("DELETE FROM `lite_b2b`.`einv_process_log` WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND `status` = '1'");
                }
            }
            
        } //close foreach for looping refno

        //start create pdf
        // Delete page 6

        $pdf->lastPage();
        // ob_end_clean(); 

        // if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        //    // echo 'This is a server using Windows!';die;
        //     $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'/lite_panda_b2b'.$virtual_path.'/invoice/B2B_'.$refno.'.pdf', 'F');  
        // } 
        // else {
        //     //echo 'This is a server not using Windows!';die;
        //     $pdf->Output($_SERVER['DOCUMENT_ROOT'] .$virtual_path.'/invoice/B2B_'.$refno.'.pdf', 'F');  
        // }


        // $pdf->Output($refno.'.pdf', 'I');

        if (($error_refno != '') && ($error_refno != null)) {
            $error_refno = rtrim($error_refno, ',\n') . '';
            // echo $error_refno;die;

            // write some JavaScript code
            $js = <<<EOD
app.alert('$error_refno');
EOD;

            // force print dialog
            $js .= 'print(true);';
        }
        // set javascript
        $pdf->IncludeJS($js);

        ob_end_clean();
        //Close and output PDF document
        $pdf->Output('bulk_e_invoice' . date('Y-m-d') . '.pdf', 'I');

        // if($error > 0){
        //     $success_msg = 'Duplicate successfully';
        //     $button = '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        //     $this->message->success_message($button , $success_msg, '');
        // }
        // else
        // {   
        //     $error_message = 'Failed to duplicate';
        //     $xerror = $this->db->error();
        //     $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
        //     $this->message->error_message($xerror['message'], '1');
        // }//close else

    } //close bulk_convert_e_invoice

    public function einv_main_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $customer_guid = $_SESSION['customer_guid'];
       
        if (in_array('IAVA', $_SESSION['module_code'])) {
            $query_supcode = '';
        } else {
            $query_supcode = $_SESSION['query_supcode'];
        }

        if ($query_supcode != '') {
            $einv_main = $this->db->query("SELECT b.`supplier_code`, a.* FROM b2b_summary.einv_main a INNER JOIN b2b_summary.`grmain_info` b ON a.refno = b.`refno` WHERE a.customer_guid = '$customer_guid' AND b.`supplier_code` IN (" . $_SESSION['query_supcode'] . ")");
        } else {
            $einv_main = $this->db->query("SELECT b.`supplier_code`, a.* FROM b2b_summary.einv_main a INNER JOIN b2b_summary.`grmain_info` b ON a.refno = b.`refno` WHERE a.customer_guid = '$customer_guid' ");
        }
        //echo $this->db->last_query();die;
        $data = array(
            'einv_main' => $einv_main->result()
        );

        echo json_encode($data);
    } //close einv_main table

    public function bulk_print_e_invoice()
    {
        // print_r($this->session->userdata('userid'));die;
        $user_id = $this->session->userdata('userid');
        $user_guid = $this->session->userdata('user_guid');
        $from_module = $_SESSION['frommodule'];

        $lite_b2b = 'lite_b2b';
        $b2b_summary = 'b2b_summary';

        $details = $this->input->post('bulk_print');

        $customer_guid = $this->session->userdata('customer_guid');

        $error_refno = '';
        // echo 'hah';die;
        //$haha = $this->load->view('print/invoice_pdf', $data, true);
        $this->load->library('Pdf_invoice_json');
        $pdf = new Pdf_invoice_json('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Bulk E-Invoice');
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetAuthor('B2B');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->setPageUnit('pt');
        $x = $pdf->pixelsToUnits('20');
        $y = $pdf->pixelsToUnits('20');
        $font_size = $pdf->pixelsToUnits('9.5');
        $pdf->SetFont('helvetica', '', $font_size, '', 'default', true);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);


        foreach ($details as $refno) {
            $deletePage = 0;

            $einv_main = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $header = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid'");

            $einv_guidd = $this->db->query("SELECT einv_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' ")->row('einv_guid');

            $child_info = $this->db->query("SELECT * FROM $b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by line asc");


            if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                $this->db->query("UPDATE b2b_summary.grmain_info set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");

                $this->db->query("UPDATE b2b_summary.grmain_info set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");


                /*  $this->db->query("UPDATE b2b_summary.grmain set status = 'EINV_GENERATED' where status = 'viewed' and customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno' ");
                */
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



            $pdf->AddPage('L', 'A4');
            $pdf->RefNo = $refno;
            // ob_start();

            $com = new Panda("");
            $test = $com->get_serverdb();


            $conn = new mysqli($test['servername'], $test['username'], $test['password'], $test['dbname']);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }



            $header_customer_guid = "SELECT customer_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($header_customer_guid);
            $header_customer_guid = $result->fetch_assoc();



            $header_main = "SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($header_main);
            $header_main = $result->fetch_assoc();



            $customer_hq_branch_info = "SELECT * FROM $b2b_summary.cp_set_branch WHERE SET_SUPPLIER_CODE = 'HQ' AND SET_CUSTOMER_CODE = 'HQ' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($customer_hq_branch_info);
            $customer_hq_branch_info = $result->fetch_assoc();



            $gr_info = "SELECT 
            a.`loc_group` as Location
            , a.`supplier_code` as Code
            , a.`supplier_name` as Name
            , a.`Invno`
            FROM $b2b_summary.grmain_info AS a 
            LEFT JOIN $b2b_summary.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '" . $header_customer_guid['customer_guid'] . "'";
            $result = $conn->query($gr_info);
            $gr_info = $result->fetch_assoc();


            $supcus_customer = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($supcus_customer);
            $supcus_customer = $result->fetch_assoc();

            $supcus_supplier = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Code'] . "' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($supcus_supplier);
            $supcus_supplier = $result->fetch_assoc();


            $customer_branch_info = "SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($customer_branch_info);
            $customer_branch_info = $result->fetch_assoc();


            $pdf->SetFont('helvetica', '', 9.5);

            $html = '<table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;"> <tbody><tr> <td style="width: 80%;text-align: left"> <table cellspacing="0" cellpadding="0"> <tbody> <tr> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Purchase from Registered GST Supplier </td> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Goods Received Note Issued by </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $supcus_supplier['Name'] . '</b> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $customer_branch_info['BRANCH_NAME'] . ' </b> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_supplier['reg_no'] . ' </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_supplier['reg_no'] . '</td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $supcus_supplier['Add1'] . '
                <br>' . $supcus_supplier['Add2'] . '
                <br>' . $supcus_supplier['Add3'] . '
                <br>' . $supcus_supplier['Add4'] . '<br>
                </td>
                 </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $customer_branch_info['BRANCH_ADD'] . '<br> </td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><b>Sup Code:</b> ' . $gr_info['Code'] . ' - ' . $gr_info['Name'] . ' <b><br>Received Loc:</b> ' . $gr_info['Location'] . ' - ' . $supcus_supplier['Name'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td colspan="2"><b>Tax Invoice No:</b> ' . $header_main['invno'] . ' <b><br>Delivery Order:</b> ' . $header_main['dono'] . '</td> <td><b>Invoice Date:</b> ' . $header_main['inv_date'] . ' <b><br>Ref No:</b> ' . $header_main['refno'] . '</td> </tr></tbody></table> </td> </tr> </tbody> </table> </td> <td style="width: 20%;"> <table id="right-table" border="0" cellspacing="0" cellpadding="0" style="width: 100%;height:500px;"> <tbody style="height:500px;"> <tr> <td style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>E-Invoice</b></p></td> </tr> <tr> <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Inv No</p><p style="font-size:12px;"><b>' . $header_main['einvno'] . '</b></p></td> </tr> </tbody> </table> </td> </tr> </tbody></table>';
            if ($deletePage == 1) {
                $html = '';
                // $pdf->deletePage($pdf->PageNo());
            }
            $pdf->writeHTML($html, true, false, true, false, '');



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
            if ($deletePage == 1) {
                $html = '';
                // $pdf->deletePage($pdf->PageNo());
            }
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
            
            <td style="width:23.66%">' . $row->description . '</td>
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
                if ($deletePage == 1) {
                    $html = '';
                    // $pdf->deletePage($pdf->PageNo());
                }
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
                        
                        <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">The parties hereby agree that the actual physical amount of goods received will be reflected in the Goods Received Note(GRN) notwithstanding validation of the supplierinvoice. The parties also hereby agree that in the event the total purchase price in the GRN and the supplier Invoice different amount, we shall pay the lower amount of the two. 
                          
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
            if ($deletePage == 1) {
                $html = '';
                $pdf->deletePage($pdf->PageNo());
            }
            $pdf->writeHTML($html, true, false, true, false, '');
        } //close foreach for looping refno

        //start create pdf
        // Delete page 6

        $pdf->lastPage();
        // ob_end_clean(); 



        // $pdf->Output($refno.'.pdf', 'I');

        if (($error_refno != '') && ($error_refno != null)) {
            $error_refno = rtrim($error_refno, ',\n') . '';
            // echo $error_refno;die;

            // write some JavaScript code
            $js = <<<EOD
app.alert('$error_refno');
EOD;

            // force print dialog
            $js .= 'print(true);';
        }
        // set javascript
        $pdf->IncludeJS($js);

        ob_end_clean();
        //Close and output PDF document
        $pdf->Output('bulk_e_invoice' . date('Y-m-d') . '.pdf', 'I');

        // if($error > 0){
        //     $success_msg = 'Duplicate successfully';
        //     $button = '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        //     $this->message->success_message($button , $success_msg, '');
        // }
        // else
        // {   
        //     $error_message = 'Failed to duplicate';
        //     $xerror = $this->db->error();
        //     $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
        //     $this->message->error_message($xerror['message'], '1');
        // }//close else

    } //close bulk_convert_e_invoice    

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
        }


    }
} // nothing after this
