<?php
class b2b_prdncn extends CI_Controller
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
        $this->jasper_path = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'),'web','general_doc','jasper_document_path','GDJDP');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            //print_r($_SESSION['from_other']); die;
            $setsession = array(
                'frommodule' => 'b2b_prdncn',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            // if(isset($_SESSION['from_other']) == 0 )
            // {
                $setsession = array(
                    'prdncn_loc' => $_REQUEST['loc'],
                );
                $this->session->set_userdata($setsession);

                redirect('b2b_prdncn/prdncn_list');
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

    public function prdncn_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $check_loc = $_SESSION['prdncn_loc'];
            
            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array = array();

            foreach ($hq_branch_code as $key) {
                array_push($hq_branch_code_array, $key->branch_code);
            }

            $data = array(
                'filter_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PRDNCN_FILTER_STATUS' order by code='' desc, code asc"),
                'period_code' => $this->db->query("SELECT period_code from lite_b2b.list_period_code"),
                'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '" . $_SESSION['customer_guid'] . "'"),
            );

            $data_footer = array(
                'activity_logs_section' => 'prdncn'
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('prdncn/b2b_prdncn_list_view', $data);
            //$this->load->view('general_modal', $data);
            $this->load->view('footer', $data_footer);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function prdncn_datatable()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $doc = 'prdncn_table';
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

            if (in_array($_SESSION['prdncn_loc'], $hq_branch_code_array)) {
                $loc = $query_loc;
            } else {
                $loc = "'" . $_SESSION['prdncn_loc'] . "'";
            }

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $module_code_in = '';
            } else {
                $module_code_in = "AND a.supplier_code IN (" . $_SESSION['query_supcode'] . ") ";
            }

            if ($ref_no == '') {
                $ref_no_in = '';
            } else {
                $ref_no_in = " AND a.refno LIKE '%" . $ref_no . "%' ";
            }

            if ($status == '') {
                $status_in = " WHERE a.type IN ('','DEBIT','CN') ";
            } elseif ($status == 'READ') {
                $status_in = " WHERE a.type IN ('','DEBIT','CN') AND a.`status` IN ('printed', 'viewed') ";
            } elseif ($status == 'UNREAD') {
                $status_in = " WHERE a.type IN ('','DEBIT','CN') ";
            } elseif ($status == 'ALL') {
                $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'PRDNCN_FILTER_STATUS'");

                foreach ($get_stat->result() as  $row) {
                    $check_stat[] = $row->code;
                }

                foreach ($check_stat as &$value) {
                    $value = "'" . trim($value) . "'";
                }
                $check_status = implode(',', array_filter($check_stat));
                $status_in = " WHERE a.type IN ($check_status) ";
            } else {
                $status_in = " WHERE a.type = '$status' ";
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

            $query_count = "SELECT zzz.refno FROM (SELECT 
            * 
            FROM 
            (
              SELECT 
                b.`batch_no`, 
                b.`doc_date` AS strb_doc_date, 
                b.uploaded_image, 
                a.stock_collected, 
                a.stock_collected_by, 
                a.date_collected, 
                a.customer_guid, 
                a.type, 
                a.refno, 
                a.loc_group, 
                a.docno, 
                a.docdate, 
                a.supplier_code, 
                a.supplier_name,
                IF(a.status = '', 'NEW', a.status) AS STATUS 
              FROM 
                b2b_summary.dbnotemain_info a 
                LEFT JOIN b2b_summary.dbnote_batch b ON a.refno = b.`b2b_dn_refno` 
                AND a.`customer_guid` = b.`customer_guid` 
                LEFT JOIN b2b_summary.dbnotemain c
                ON a.refno = c.refno
                AND a.customer_guid = c.customer_guid
              WHERE 
                a.customer_guid = '$customer_guid' 
                AND a.loc_group IN ($loc) 
                $module_code_in 
                $doc_daterange_in
                $ref_no_in
                $period_code_in 
              UNION ALL 
              SELECT 
                '' AS batch_no, 
                '' AS strb_doc_date, 
                '' AS uploaded_image, 
                0 AS stock_collected, 
                '' AS stock_collected_by, 
                '' AS date_collected, 
                a.customer_guid, 
                'CN' AS `type`, 
                a.refno, 
                a.loc_group, 
                a.docno, 
                a.docdate, 
                a.supplier_code, 
                a.supplier_name, 
                IF(a.status = '', 'NEW', a.status) AS STATUS 
              FROM 
                b2b_summary.cnnotemain_info a 
                LEFT JOIN b2b_summary.cnnotemain c
                ON a.refno = c.refno
                AND a.customer_guid = c.customer_guid
              WHERE 
                a.customer_guid = '$customer_guid' 
                AND a.loc_group IN ($loc)
                $module_code_in 
                $doc_daterange_in
                $ref_no_in
                $period_code_in 
            ) a
            $status_in
            ) zzz "; 

            $query = "SELECT 
            * 
            FROM 
            (
              SELECT 
                b.`batch_no`, 
                b.`doc_date` AS strb_doc_date, 
                b.uploaded_image, 
                a.stock_collected, 
                a.stock_collected_by, 
                a.date_collected, 
                a.customer_guid, 
                a.type, 
                a.refno, 
                a.loc_group, 
                a.docno, 
                a.docdate, 
                a.supplier_code, 
                a.supplier_name, 
                a.amount,
                a.gst_tax_sum,
                a.total_incl_tax, 
                IF(a.status = '', 'NEW', a.status) AS `status` 
              FROM 
                b2b_summary.dbnotemain_info a 
                LEFT JOIN b2b_summary.dbnote_batch b ON a.refno = b.`b2b_dn_refno` 
                AND a.`customer_guid` = b.`customer_guid` 
              WHERE 
                a.customer_guid = '$customer_guid' 
                AND a.loc_group IN ($loc) 
                $module_code_in 
                $doc_daterange_in
                $ref_no_in
                $period_code_in 
              UNION ALL 
              SELECT 
                '' AS batch_no, 
                '' AS strb_doc_date, 
                '' AS uploaded_image, 
                0 AS stock_collected, 
                '' AS stock_collected_by, 
                '' AS date_collected, 
                a.customer_guid, 
                'CN' AS `type`, 
                a.refno, 
                a.loc_group, 
                a.docno, 
                a.docdate, 
                a.supplier_code, 
                a.supplier_name, 
                a.amount,
                a.gst_tax_sum,
                a.total_incl_tax, 
                IF(a.status = '', 'NEW', a.status) AS `status` 
              FROM 
                b2b_summary.cnnotemain_info a 
              WHERE 
                a.customer_guid = '$customer_guid' 
                
                AND a.loc_group IN ($loc)
                $module_code_in 
                $doc_daterange_in
                $ref_no_in
                $period_code_in 
            ) a
            $status_in
                    
            "; 
            //and a.sctype IN ('S', 'C') 
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

                    $tab['refno'] = $row->refno;
                    $tab['loc_group'] = $row->loc_group;
                    $tab['type'] = $row->type;
                    $tab['supplier_code'] = $row->supplier_code;
                    $tab['supplier_name'] = $row->supplier_name;
                    $tab['docno'] = $row->docno;
                    $tab['docdate'] = $row->docdate;
                    $tab['amount'] = "<span class='pull-right'>" . number_format($row->amount, 2) . "</span>";
                    $tab['gst_tax_sum'] = "<span class='pull-right'>" . number_format($row->gst_tax_sum, 2) . "</span>";
                    $tab['total_incl_tax'] = "<span class='pull-right'>" . number_format($row->total_incl_tax, 2) . "</span>";
                    $tab['status'] = $row->status;
                    $tab['stock_collected_by'] = $row->stock_collected_by;
                    // $tab['stock_collected'] = $row->stock_collected==1?'stock collected':'stock not collected';
                    // $tab['date_collected'] = $row->date_collected;
                    if ($row->stock_collected == '0' || $row->stock_collected == 0) {
                        $tab['stock_collected'] = $row->stock_collected == 1 ? '-' : '-';
                        $tab['date_collected'] = '-';
                    } else {
                        $tab['stock_collected'] = $row->stock_collected == 1 ? 'stock collected' : 'stock not collected';
                        $tab['date_collected'] = $row->date_collected;
                    }

                    if($row->batch_no == '' || $row->batch_no == 'null' || $row->batch_no == null)
                    {
                        $tab['button'] = "<a href=" . site_url('b2b_prdncn/prdncn_child') . "?trans=" . $row->refno . "&loc=" . $_REQUEST['loc'] . "&type=" . $row->type . " style='margin-left:5px;' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    }
                    else if($row->uploaded_image == '0')
                    {
                        $tab['button'] = "<a href=" . site_url('b2b_prdncn/prdncn_child') . "?trans=" . $row->refno . "&loc=" . $_REQUEST['loc'] . "&type=" . $row->type . " style='margin-left:5px;' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    }
                    else
                    {
                        $tab['button'] = "<a href=" . site_url('b2b_prdncn/prdncn_child') . "?trans=" . $row->refno . "&loc=" . $_REQUEST['loc'] . "&type=" . $row->type . " style='margin-left:5px;' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                        <button style='margin-left:5px;' id='btn_image' type='button'  title='IMAGE' class='btn btn-sm btn-warning' refno=" . $row->batch_no . " period_code=" . $row->strb_doc_date . " outlet=" . $row->loc_group . " image_type='STRB'><i class='fa fa-file-image-o'></i></button>";
                    }

                    //$tab['button'] = "<a href=" . site_url('b2b_prdncn/prdncn_child') . "?trans=" . $row->refno . "&loc=" . $_REQUEST['loc'] . "&type=" . $row->type . " style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    $tab['box'] = '<input type="checkbox" class="data-check" value="' . $row->refno . '" dncn="' . $row->type . '">';
                    //$tab['button'] = "";//
                    $tab['batch_no'] = "<a href=" . site_url('panda_return_collection/return_collection_child') . "?refno=" . $row->batch_no . "&loc=" . $_REQUEST['loc'] . "&pc=" . substr($row->strb_doc_date,0,7) . " >" . $row->batch_no . "</a>";
                    $tab['uploaded_image'] = $row->uploaded_image;
                    

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

    public function prdncn_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $database = 'lite_b2b';
            $database1 = 'b2b_summary';
            $customer_guid = $this->session->userdata('customer_guid');
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $xtype = $_REQUEST['type'];
            $check_status = $this->db->query("SELECT * from b2b_summary.dbnotemain_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
            $check_strb_data = $this->db->query("SELECT batch_no,uploaded_image,LEFT(doc_date,7) AS docdate FROM b2b_summary.`dbnote_batch` WHERE customer_guid = '$customer_guid' AND converted_by = '$refno' ");
            // echo $this->db->last_query();die;
            // print_r($check_status->row('status'));die;
            if($xtype == 'DEBIT')
            {
                $check_scode = $this->db->query("SELECT supplier_code AS code from b2b_summary.dbnotemain_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');
            }
            else
            {
                $check_scode = $this->db->query("SELECT supplier_code AS code from b2b_summary.dbnotemain_info where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');    
            }
            $check_scode = str_replace("/","+-+",$check_scode);

            if($xtype == 'DEBIT')
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdn'");
            }
            else
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prcn'");   
            }

            // $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdncn'");
            //due to session data is from return collection direct click from there..

            $set_row = $this->db->query("SET @row=0");
            /*$get_DN_detail =  $this->db->query("SELECT @row:=@row+1 AS rowx, dbnotemain.* from b2b_summary.dbnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' and type = 'DEBIT'");*/
            if($xtype == 'DEBIT')
            {
                $get_DN_detail = $this->db->query("SELECT @row := @row + 1 AS rowx, a.customer_guid, a.status, a.Type, a.RefNo, a.DocNo, a.DocDate, a.supplier_code, a.supplier_name, a.loc_group, JSON_UNQUOTE(JSON_EXTRACT(a.`prdn_json_info`,'$.dbnotemain[0].Amount')) AS Amount, JSON_UNQUOTE(JSON_EXTRACT(a.`prdn_json_info`,'$.dbnotemain[0].gst_tax_sum')) AS gst_tax_sum, IF( b.ext_doc1 IS NULL, a.sup_cn_no, b.ext_doc1 ) AS sup_cn_no, IF( b.ext_date1 IS NULL, a.sup_cn_date, b.ext_date1 ) AS sup_cn_date FROM b2b_summary.dbnotemain_info AS a LEFT JOIN ( SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' AND `type` = 'PRDNCN' ) AS b ON a.refno = b.refno WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' AND a.type = 'DEBIT'");
            }
            else
            {
                $get_DN_detail = $this->db->query("SELECT @row := @row + 1 AS rowx, a.customer_guid, a.status, 'CN' AS `type`, a.RefNo, a.DocNo, a.DocDate, a.supplier_code, a.supplier_name, a.loc_group, JSON_UNQUOTE(JSON_EXTRACT(a.`prcn_json_info`,'$.cnnotemain[0].Amount')) AS Amount, JSON_UNQUOTE(JSON_EXTRACT(a.`prcn_json_info`,'$.cnnotemain[0].gst_tax_sum')) AS gst_tax_sum, IF( b.ext_doc1 IS NULL, a.sup_cn_no, b.ext_doc1 ) AS sup_cn_no, IF( b.ext_date1 IS NULL, a.sup_cn_date, b.ext_date1 ) AS sup_cn_date FROM b2b_summary.cnnotemain_info AS a LEFT JOIN ( SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' AND `type` = 'PRDNCN' ) AS b ON a.refno = b.refno WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' ");
            }

            $type = $parameter->row('type');
            $code = $check_scode;

            if($xtype == 'DEBIT')
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prdn'")->row('query');
            }
            else
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prcn'")->row('query');
            }   

            $upload_cn_setting = $this->db->query("SELECT * FROM $database.acc_settings WHERE customer_guid = '$customer_guid'");
            // echo $this->db->last_query();die;
            if(count($upload_cn_setting->result()) > 0)
            {
                $upload_cn_setting_flag = $upload_cn_setting->row('upload_cn_setting');
            }
            else
            {
                $upload_cn_setting_flag = 0;
            }

            if($upload_cn_setting_flag == 1)
            {
                $check_upload_cn = 1;
                $check_supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.dbnotemain_info a INNER JOIN lite_b2b.`set_supplier_group` b ON a.supplier_code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
                //echo $this->db->last_query();die;
                // $target_dir = "retailer_file/".$customer_guid."/prdncn_cn/".$check_supplier_guid->row('supplier_guid')."/".$refno.'.pdf';

                $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');

                // $file_config_final_path = $this->file_config_b2b->merge_print_create_file_path($customer_guid,'web','general_doc','merge_print','MPMPCP');
                $file_path_name = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','upload_grn_cn','UGRNCN');

                $target_dir = $file_path_name.$path_seperator.$customer_guid.$path_seperator."prdncn_cn".$path_seperator.$check_supplier_guid->row('supplier_guid').$path_seperator.$refno.'.pdf';
                if(file_exists($target_dir))
                {
                    $exists_upload_cn_file = 1;
                }
                else
                {
                    $exists_upload_cn_file = 0;
                }
                // echo $exists_upload_cn_file;die;
            }
            else
            {
                $check_supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.dbnotemain_info a INNER JOIN lite_b2b.`set_supplier_group` b ON a.supplier_code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
                // if others retailer need generate cn need copy the above query file targer dir.
                $check_upload_cn = 0;
                $exists_upload_cn_file = 1;
            }
            // echo $this->db->last_query();die;
            // echo 'asda'.$replace_var.'<br>';
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';
            //echo $filename;die;
            $file_headers = @get_headers($filename);

            $check_upload_doc_log = $this->db->query("SELECT refno,created_at,DATE_ADD(created_at,INTERVAL 14 DAY) AS new_check_date, IF(DATE_FORMAT(DATE_ADD(created_at,INTERVAL 14 DAY), '%Y-%m-%d') > CURDATE() , '1' , '0') AS valid_reupload FROM b2b_summary.upload_doc_log WHERE refno = '$refno' AND customer_guid = '$customer_guid' AND doc_type = 'prdn_cn' ORDER BY created_at DESC LIMIT 1 ");

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'PR DN/CN',
                'sup_cn_header' => $get_DN_detail,
                'xtype' => $xtype,
                'check_status' => $check_status,
                'exists_upload_cn_file' => $exists_upload_cn_file,
                'check_upload_cn' => $check_upload_cn,
                'cnfilepath' => $target_dir,
                'file_supplier_guid' => $check_supplier_guid->row('supplier_guid'),
                'file_upload_type' => 'prdncn_cn',
                'check_uploaded_image_strb' => $check_strb_data->row('uploaded_image'),
                'strb_refno' => $check_strb_data->row('batch_no'),
                'strb_docdate' => $check_strb_data->row('docdate'),
                'valid_reupload_time' => $check_upload_doc_log->row('valid_reupload'),
                'request_link' => site_url('B2b_prdncn/prdncn_report?refno='.$refno.'&type='.$xtype),
            );
            // echo $filename;die;
            $customer_guid = $_SESSION['customer_guid'];        
            $user_guid = $_SESSION['user_guid'];        
            $from_module = $_SESSION['frommodule'];   

            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                if($xtype == 'DEBIT')      
                {       
                    $this->db->query("UPDATE b2b_summary.dbnotemain_info set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");      
                    $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'viewed_PRDN'        
                    , '$from_module'        
                    , '$refno'      
                    , now()     
                    ");     
                    // redirect ($filename);       
                }       
                else        
                {       
                    $this->db->query("UPDATE b2b_summary.cnnotemain_info set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");       
                    $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'viewed_PRCN'        
                    , '$from_module'        
                    , '$refno'      
                    , now()     
                    ");     
                    // redirect ($filename);       
                } 
            }

            $this->load->view('header');       
            $this->load->view('prdncn/b2b_prdncn_pdf',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function prdncn_report()
    {
        $get_status = $this->db->query("SELECT `status` FROM lite_b2b.jasper_server WHERE isactive = '1'")->row('status');

        if($get_status == '0')
        {
            print_r('Report Under Maintenance.'); 
            die;
        }
        
        $refno = $_REQUEST['refno'];
        $doc_type = $_REQUEST['type'];
        $customer_guid = $this->session->userdata('customer_guid');
        
        if($doc_type == 'CN')
        {
            $url = $this->jasper_ip . $this->jasper_path ."/Backend_PRCN/main_jrxml.pdf?refno=".$refno;

            $check_code = $this->db->query("SELECT a.supplier_code from b2b_summary.cnnotemain_info a where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY a.refno")->row('supplier_code');
        }
        else 
        {
            $url = $this->jasper_ip . $this->jasper_path . "/Backend_PRDN/main_jrxml.pdf?refno=".$refno;

            $check_code = $this->db->query("SELECT a.supplier_code from b2b_summary.dbnotemain_info a where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "' GROUP BY a.refno")->row('supplier_code');
        }

        //print_r($url); die;

        $check_code = str_replace("/", "+-+", $check_code);

        $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdn'");
        $type = $parameter->row('type');
        $code = $check_code;

        $filename = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prdn'")->row('query');

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

    public function view_ecn()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {       
                $this->panda->get_uri();
                $database1 = 'b2b_summary';
                $customer_guid = $_SESSION['customer_guid'];
                $req_refno = $_REQUEST['refno'];
                $transtype = $_REQUEST['transtype'];
                $invoice_number = $_REQUEST['refno']-$_REQUEST['transtype'];
    
                $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
                $to_shoot_url = $check_url."/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
                // $to_shoot_url = "http://192.168.10.29/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
                // $to_shoot_url = "http://18.139.87.215/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
                // $to_shoot_url = "http://202.75.55.22/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
                    // echo $to_shoot_url ;die;
                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                $response = curl_exec($ch);
                //from here get child, then we need insert child
                if($response !== false) 
                {
                        $get_child_dncn = json_decode(file_get_contents($to_shoot_url), true);
                        $child_result_validation = $get_child_dncn['child'][0]['line']; 
                        
                }
                else
                {
                    $get_child_dncn = array();
                    $child_result_validation = '0';
                    $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E CN is currently not available.'); 
    
                }
    
                $gr_info = $this->db->query("SELECT  b.* 
                FROM
                $database1.ecn_main AS a 
                INNER JOIN $database1.dbnotemain AS b 
                    ON a.refno = b.refno 
                    AND a.customer_guid = b.customer_guid  where a.type = 'PRDNCN' and a.refno = '$req_refno'  ");
    
    
                 $data = array  (
                    'query_data' =>  $this->db->query("SELECT a.refno ,a.status , a.type , a.ext_doc1 , a.ext_date1, a.amount , a.`tax_rate` , a.`tax_amount` , a.`total_incl_tax` , a.posted , b.refno_dn , b.transtype , b.location , b.itemcode , b.barcode , b.description , b.qty , b.inv_qty , b.inv_netunitprice , b.supplier , b.invno , b.dono , b.porefno , b.title2 , b.notes , b.pounitprice , b.invactcost , b.netunitprice , b.pototal , b.articleno , b.packsize , b.variance_amt , b.reason , b.tax_amount , b.total_gross FROM $database1.ecn_main AS a INNER JOIN $database1.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = 'PRDNCN'"),
                      'supcus_supplier' => $this->db->query("SELECT * FROM $database1.supcus WHERE Code = '".$gr_info->row('Location')."' and customer_guid = '$customer_guid'"),
                    'supcus_customer' => $this->db->query("SELECT * from $database1.supcus where code = '".$gr_info->row('Code')."' and customer_guid = '$customer_guid'"),
                    'customer_branch_info' => $this->db->query("SELECT * FROM $database1.cp_set_branch WHERE BRANCH_CODE = '".$gr_info->row('Location')."'   and customer_guid = '$customer_guid'"),
                    'header' => $get_child_dncn['header'],
                    'child' => $get_child_dncn['child'],
                );
    
                
                $load_pdf = $this->load->view('prdncn/panda_ecn_pdf', $data, true);
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
                $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
                $pdf->AddPage('L');
                ob_start();
                $pdf->writeHTML($load_pdf, true, false, true, false, '');
                ob_end_clean();
                //$pdf->Output($_SERVER['DOCUMENT_ROOT'] .'invoice/invoice/B2B_'.$invoice_number.'.pdf', 'F');           
                // $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'invoice/invoice/B2B_'.$invoice_number.'.pdf', 'F');           
                
                $data = array(
    
                       'filename' =>  'B2B_'.$invoice_number.'.pdf',
                       'path' => $_SERVER["DOCUMENT_ROOT"].'invoice/B2B_'.$invoice_number.'.pdf'
    
                ); 
    
                ob_end_clean();
                $pdf->Output($req_refno.$transtype, 'I');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
                redirect('#'); 
        }
    }

    public function cn_file_unlink()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $_SESSION['customer_guid'];
            $db_refno = $this->input->post('db_refno');

            $supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.dbnotemain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$db_refno'");
            
            $get_supplier_guid = $supplier_guid->row('supplier_guid');

            //$get_supplier_guid = '7526D74B421F11E99994000D3AA2838A';

            if ($get_supplier_guid == '' || $get_supplier_guid == null) 
            {
                $this->session->set_flashdata('message', 'Supplier ID Empty, Please Contact Admin.');
                redirect('panda_prdncn/prdncn_child?trans='.$refno.'&loc='.$loc.'&type='.$doc_type);
            } 

            $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');

            $file_path_name = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','upload_prdn_cn','UPRDNCN');

            $get_data_log = $this->db->query("SELECT customer_guid,refno,created_at,file_path FROM b2b_summary.upload_doc_log WHERE refno = '$db_refno' AND customer_guid = '$customer_guid' ORDER BY created_at DESC LIMIT 1");

            $file_path = $get_data_log->row('file_path');
            $target_dir = $file_path;

            if (file_exists($target_dir))
            {
                //print_r($target_dir); die;
                unlink($target_dir);
                ///media/b2b-pdf/retailer_file/13EE932D98EB11EAB05B000D3AA2838A/prdncn_cn/7526D74B421F11E99994000D3AA2838A/1017DN21050001.pdf
            }

            if (!file_exists($target_dir))
            {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Success removed Document.',
                 );    
                 echo json_encode($data);   
                 exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'File still exisit.',
                );    
                echo json_encode($data);  
                exit(); 
            }

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#'); 
        }
    }

    public function generate_ecn()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 1;die;
            $this->panda->get_uri();
            $database1 = 'b2b_summary';
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('ecn_refno[]');
            $type = $this->input->post('ecn_type[]');
            $sup_cn_no = $this->input->post('sup_cn_no[]');
            $sup_cn_date = $this->input->post('sup_cn_date[]');
            $amount = $this->input->post('ecn_varianceamt[]');
            $tax_rate = $this->input->post('ecn_tax_rate[]');
            $tax_amount = $this->input->post('ecn_gst_tax_sum[]');
            $total_incl_tax = $this->input->post('ecn_total_incl_tax[]');
            $loc = $this->input->post('ecn_loc[]');
            $line = $this->input->post('ecn_rows[]');
           /*  $gr_refno = $this->input->post('gr_refno');*/
            $current_loc = $this->input->post('current_loc');
            $prdn_loc = $this->input->post('prdn_loc');
            $prdn_type = $this->input->post('ecn_type');


            // print_r($this->input->post());die;
            //latest for retrieve invoice number
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];

            $check_if_exists_ecn = $this->db->query("SELECT * FROM b2b_summary.ecn_main WHERE refno != '$req_refno' AND type = '$transtype' AND customer_guid = '$customer_guid' AND ext_doc1 = '$sup_cn_no[0]'");
            // echo $check_if_exists_ecn->num_rows();die;
            if($check_if_exists_ecn->num_rows() > 0)
            {
                // echo 'cn number duplicate';die;
                $check_if_exists_ecn2 = $this->db->query("SELECT * FROM b2b_summary.dbnotemain WHERE refno = '$req_refno' AND customer_guid = '$customer_guid'");
                // echo $this->db->last_query();die;
                $check_if_exists_ecn2_code = $check_if_exists_ecn2->row('Code');
                $check_if_exists_ecn2_supcode = $this->db->query("SELECT b.* FROM b2b_summary.supcus a LEFT JOIN b2b_summary.`supcus` b ON a.`AccountCode` = b.`AccountCode` AND a.`customer_guid` = b.customer_guid WHERE a.code = '$check_if_exists_ecn2_code' AND a.customer_guid = '$customer_guid' GROUP BY b.`customer_guid`,b.code");
                // echo $this->db->last_query();die;
                $check_if_exists_ecn2_supcode_string = '';
                foreach ($check_if_exists_ecn2_supcode->result() as $row)
                {
                    $check_if_exists_ecn2_supcode_string .= "'".$row->Code."',";
                }
                $check_if_exists_ecn2_supcode_string2 = rtrim($check_if_exists_ecn2_supcode_string,',');
                // echo rtrim($check_if_exists_ecn2_supcode_string,',').'sdsd<br>';die;
                $check_if_exists_ecn3 = $this->db->query("SELECT b.* FROM b2b_summary.ecn_main a INNER JOIN b2b_summary.dbnotemain b ON a.`customer_guid` = b.`customer_guid` AND a.refno = b.refno WHERE a.refno != '$req_refno' AND a.customer_guid = '$customer_guid' AND a.ext_doc1 = '$sup_cn_no[0]' AND CODE IN($check_if_exists_ecn2_supcode_string2)");
                // echo $this->db->last_query();die;
                if($check_if_exists_ecn3->num_rows() > 0)
                {
                    $this->session->set_flashdata('warning',  'CN number repeat');
                    // echo '/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT';die;
                    redirect('/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT');
                }
                // echo $this->db->last_query();die;
            }

            $invoice_number = $this->db->query("SELECT invno FROM $database1.einv_main WHERE refno = '$req_refno'  ")->row('invno');

            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            $to_shoot_url = $check_url."/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://192.168.10.29/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://18.139.87.215/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://202.75.55.22/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
                // echo $to_shoot_url ;die;
            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            //from here get child, then we need insert child
            if($response !== false) 
            {
                    $get_child_dncn = json_decode(file_get_contents($to_shoot_url), true);
                    $child_result_validation = $get_child_dncn['child'][0]['line']; 
                    
            }
            else
            {
                $get_child_dncn = array();
                $child_result_validation = '0';
                $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E CN is currently not available.'); 

            }
            // print_r(count($get_child_dncn));die;
            // echo $get_child_dncn['child'][0]['refno'];die;
           // echo var_dump(count($get_child_dncn['child'])) ;die;
            foreach($line as $i => $id) 
            {
                /*$check_exist = $this->db->query("SELECT * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");*/
                $check_exist = $this->db->query("SELECT * from $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
             
                if($check_exist->num_rows() > 0)
                {
                    $revision = $check_exist->row('revision') + 1;
                   /* $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
                    $this->db->query("DELETE FROM ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");*/
                     $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
                    $this->db->query("DELETE FROM $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
                }
                else
                {
                    $revision = '0';
                }
 
                if(is_null($sup_cn_no[$i]) || $sup_cn_no[$i] == ' '|| $sup_cn_no[$i] == '')
                {
                   //echo $refno[$i];echo $type[$i];die;
                    unset($refno[$i]);  unset($type[$i]);
                    $this->session->set_flashdata('message', 'E-CN ext Doc cannot be empty');
                };

                //echo var_dump($ext_doc1[0]);die;
                
                $data1[] = [
                    'customer_guid' => $customer_guid, 
                    'ecn_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    //'status' => '',
                    'refno' => $refno[$i],
                    'type' => $transtype,
                    'ext_doc1' => str_replace(' ', '',$sup_cn_no[$i]),
                    'ext_date1' => $sup_cn_date[$i],
                    'amount' =>  $amount[$i],
                    'tax_rate' =>   $tax_rate[$i],
                    'tax_amount' =>  $tax_amount[$i],
                    'total_incl_tax' =>  $total_incl_tax[$i],
                    'revision' =>  $revision,
                    'posted' =>  '0',
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' =>  $_SESSION['user_guid'],
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $_SESSION['user_guid'],
                ]; 
            }
            // print_r($data1);die;
            $ecnmain = $this->db->query("SELECT * from $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = '$transtype'");
            // echo $ecnmain->num_rows() ;die;
            if($ecnmain->num_rows() == 0)
            {
                $this->db->insert_batch($database1.'.ecn_main', $data1);
                // echo $this->db->last_query();die;
            };

            $this->db->query("DELETE FROM ecn_main where refno is null and type is null and customer_guid = '".$_SESSION['customer_guid']."'"); 
            // echo var_dump(count($get_child_dncn));die;
            // echo var_dump($get_child_dncn['header']);die;
            // echo  $get_child_dncn['child'][0]['porefno'];die;
            //echo 'insert here; pause';die;
            // print_r(count($get_child_dncn['child']));die;
            for($i = 0; $i < count($get_child_dncn['child']); $i++ )
            {
                $data_ecn_child[] = [
                        'customer_guid' => $customer_guid,
                        'status' => '',
                        'refno' => $get_child_dncn['child'][$i]['refno'],
                        // 'refno_dn' => $get_child_dncn['child'][$i]['pono'],
                        'transtype' => 'PRDNCN',
                        // 'location' => $get_child_dncn['child'][$i]['location'],
                        'line' => $get_child_dncn['child'][$i]['line'],
                        'itemcode' => $get_child_dncn['child'][$i]['itemcode'],
                        'barcode' => $get_child_dncn['child'][$i]['barcode'],
                        'description' => $get_child_dncn['child'][$i]['description'],
                        'qty' => $get_child_dncn['child'][$i]['qty'],
                        /*'inv_qty' => $get_child_dncn['child'][$i]['inv_qty'],
                        'inv_netunitprice' => $get_child_dncn['child'][$i]['inv_netunitprice'],
                        'inv_totalprice' => $get_child_dncn['child'][$i]['inv_totalprice'],*/
                        // 'supplier' => $get_child_dncn['child'][$i]['supplier'],
                        // 'invno' => $get_child_dncn['child'][$i]['pono'],
                        // 'dono' => $get_child_dncn['child'][$i]['docno'],
                        // 'porefno' => $get_child_dncn['child'][$i]['pono'],
                        'title2' => $get_child_dncn['child'][$i]['reason'],
                        // 'notes' => $get_child_dncn['child'][$i]['title'],
                        // 'pounitprice' => $get_child_dncn['child'][$i]['unitprice1'],
                        // 'invactcost'=> $get_child_dncn['child'][$i]['sysavgcost'],
                        // 'netunitprice'=> $get_child_dncn['child'][$i]['averagecost'],
                        // 'pototal'=> $get_child_dncn['child'][$i]['totalsysavgcostafter'],
                        'articleno'=> $get_child_dncn['child'][$i]['articleno'],
                        'packsize'=> $get_child_dncn['child'][$i]['packsize'],
                        // 'variance_amt'=> $get_child_dncn['child'][$i]['totalsysavgcostafter'],
                        'reason'=> $get_child_dncn['child'][$i]['reason'],
                        /*'tax_amount'=> $get_child_dncn['child'][$i]['tax_amount'],*/
                        // 'total_gross'=> $get_child_dncn['child'][$i]['amount'],
                        'created_at'=> $this->db->query("select now() as naw")->row('naw'),
                        'created_by'=> $_SESSION['user_guid'],
                        'updated_at'=> $this->db->query("select now() as naw")->row('naw'),
                        'updated_by'=> $_SESSION['user_guid'],
                    ];
            }
            // print_r($data_ecn_child[0]['line']);die;
            if($data_ecn_child[0]['line'] == 'No Records Found')
            {
                $this->db->query("DELETE FROM $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = 'PRDNCN'");
                // echo $this->db->last_query();die;
                $this->session->set_flashdata('warning',  'Record not found, Please contact Support');
                    // echo '/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT';die;
                redirect('/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT');
            };
            //remove existing data
            $this->db->query("DELETE FROM $database1.ecn_child where refno = '".$req_refno."' and transtype = '".$transtype."' and customer_guid = '$customer_guid'");

            $ecnchild = $this->db->query("SELECT * from $database1.ecn_child where customer_guid = '$customer_guid' and refno = '$req_refno' and transtype = '$transtype'");
            if($ecnchild->num_rows() != count($get_child_dncn))
            {
               $execute =  $this->db->insert_batch($database1.'.ecn_child', $data_ecn_child);
            };            

             // die;
            //$invoice_number = $_REQUEST['refno'].'_'.$_REQUEST['transtype'];
            $invoice_number = $_REQUEST['refno'].'_'.'PRDNCN';
           // echo  $_REQUEST['refno'];die;
          /*  $gr_info = $this->db->query("SELECT 
            a.`Location`
            , a.`Code`
            , a.`Name`
            , ifnull(b.invno,a.`Invno`) as Invno
            FROM b2b_summary.dbnotemain AS a 
            LEFT JOIN lite_b2b.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid and a where a.refno = '$req_refno' 
            and a.customer_guid = '$customer_guid'");
            */

            $gr_info = $this->db->query("SELECT  b.* 
            FROM
            $database1.ecn_main AS a 
            INNER JOIN $database1.dbnotemain AS b 
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid  where a.type = 'PRDNCN' and a.refno = '$req_refno' ");
                // print_r($gr_info->result());die;

            $data = array  (
                'query_data' =>  $this->db->query("SELECT a.refno ,a.status , a.type , a.ext_doc1 , a.ext_date1, a.amount , a.`tax_rate` , a.`tax_amount` , a.`total_incl_tax` , a.posted , b.refno_dn , b.transtype , b.location , b.itemcode , b.barcode , b.description , b.qty , b.inv_qty , b.inv_netunitprice , b.supplier , b.invno , b.dono , b.porefno , b.title2 , b.notes , b.pounitprice , b.invactcost , b.netunitprice , b.pototal , b.articleno , b.packsize , b.variance_amt , b.reason , b.tax_amount , b.total_gross FROM $database1.ecn_main AS a INNER JOIN $database1.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = 'PRDNCN'"),
                'supcus_supplier' => $this->db->query("SELECT * FROM $database1.supcus WHERE Code = '".$gr_info->row('Location')."' and customer_guid = '$customer_guid'"),
                'supcus_customer' => $this->db->query("SELECT * from $database1.supcus where code = '".$gr_info->row('Code')."' and customer_guid = '$customer_guid'"),
                'customer_branch_info' => $this->db->query("SELECT * FROM $database1.cp_set_branch WHERE BRANCH_CODE = '".$gr_info->row('Location')."'   and customer_guid = '$customer_guid'"),
                'header' => $get_child_dncn['header'],
                'child' => $get_child_dncn['child'],
            );

            if($child_result_validation > 0)
            {         
                $customer_guid = $_SESSION['customer_guid'];        
                $user_guid = $_SESSION['user_guid'];        
                $from_module = $_SESSION['frommodule'];     

                $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'generated_prdn_ecn'        
                    , '$from_module'        
                    , '$req_refno'      
                    , now()     
                    ");   
                // echo 1;die;
                $this->db->query("UPDATE $database1.dbnotemain SET status = 'cn_generated' WHERE refno = '$req_refno' AND type = 'DEBIT'");

                $load_pdf = $this->load->view('prdncn/panda_ecn_pdf', $data, true);
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
                $font_size = $pdf->pixelsToUnits('7.5');
                $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
                $pdf->AddPage('L');
                // $pdf->AddPage('L','A4','0');
                ob_start();
                $pdf->writeHTML($load_pdf, true, false, true, false, '');
                ob_end_clean();
                // $pdf->Output('name.pdf', 'I');;die;
                // $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'github/panda_b2b_test/uploads/tfvaluemart/invoice/B2B_'.$invoice_number.'.pdf', 'S');//create pdf file           

                $data = array(

                   'filename' =>  'B2B_'.$invoice_number.'.pdf',
                   'path' => $_SERVER["DOCUMENT_ROOT"].'invoice/B2B_'.$invoice_number.'.pdf'

                ); 

                ob_end_clean();
                $pdf->Output($req_refno.$transtype, 'I');//view pdf file
            }
            else
            {
                echo 'No data found';
            }
        }
        else
        {
           $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#'); 
        }
    }

} // nothing after this
