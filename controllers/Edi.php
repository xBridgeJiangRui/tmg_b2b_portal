<?php

use function GuzzleHttp\Promise\queue;

class Edi extends CI_Controller
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
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
    }

    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];

            $edi_batch_no = $this->input->post('edi_batch_no');
            $status = $this->input->post('status');
            $generate_date_from = $this->input->post('generate_date_from');
            $generate_date_to = $this->input->post('generate_date_to');
            $period_code = $this->input->post('period_code');
            $supplier_guid = $this->input->post('supplier_guid');
            $customer_guid = $this->input->post('customer_guid');

            $get_customer_name_list = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`
            FROM lite_b2b.`set_user` AS a
            INNER JOIN lite_b2b.acc AS b
            ON a.`acc_guid` = b.`acc_guid`
            WHERE a.`user_guid` = '$user_guid'");

            $get_supplier_name_list = $this->db->query("SELECT b.`supplier_name`,c.`supplier_group_name`,d.`acc_name`,c.supplier_guid
            FROM lite_b2b.`set_user` AS a
            INNER JOIN lite_b2b.`set_supplier` AS b
            ON a.`supplier_guid` = b.`supplier_guid`
            INNER JOIN lite_b2b.set_supplier_group AS c
            ON b.`supplier_guid` = c.`supplier_guid`
            INNER JOIN lite_b2b.`acc` AS d
            ON a.`acc_guid` = d.`acc_guid`
            WHERE a.`user_guid` = '$user_guid'
            GROUP BY c.`supplier_guid`");

            $get_period_code = $this->db->query("SELECT DATE_FORMAT(NOW(),'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 1 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 2 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 3 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 4 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 5 MONTH ,'%Y-%m') AS period_code");

            $get_edi_status = $this->db->query("SELECT * FROM lite_b2b.set_setting WHERE module_name = 'EDI_PO_FILTER'");

            $data = array(
                'get_customer_name_list' => $get_customer_name_list,
                'get_supplier_name_list' => $get_supplier_name_list,
                'get_period_code' => $get_period_code,
                'get_edi_status' => $get_edi_status,
                //
                'edi_batch_no' => $edi_batch_no,
                'status' => $status,
                'generate_date_from' => $generate_date_from,
                'generate_date_to' => $generate_date_to,
                'period_code' => $period_code,
                'supplier_guid' => $supplier_guid,
                'customer_guid' => $customer_guid,
            );

            $this->load->view('header');
            $this->load->view('edi/edi_log_list', $data);
            $this->load->view('footer');
            // $json['edi_log_list'] =  $edi_log_list;
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

        // $this->app_output->display($json);
    }


    public function edi_log_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $edi_batch_no = $this->input->post('edi_batch_no');
            // echo $edi_batch_no;
            // die;
            $status = $this->input->post('status');
            $generate_date_from = $this->input->post('generate_date_from');
            $generate_date_to = $this->input->post('generate_date_to');
            $period_code = $this->input->post('period_code');
            $supplier_guid = $this->input->post('supplier_guid');
            $customer_guid = $this->input->post('customer_guid');
            $user_guid = $_SESSION['user_guid'];

            $columns = array(
                0 => 'status',
                1 => 'edi_batch_no',
                2 => 'refno',
                3 => 'file_name',
                4 => 'acc_name',
                5 => 'supplier_name',
                6 => 'created_at',
                7 => 'error_message_reason',
                8 => 'refno',
            );


            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $columns[$this->input->post('order')[0]['column']];
            $dir = $this->input->post('order')[0]['dir'];

            $totalData = $this->General_model->edi_log_list($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, '', '', '', '', '', $user_guid)->num_rows();

            $totalFiltered = $totalData;

            if (empty($this->input->post('search')['value'])) {
                $posts = $this->General_model->edi_log_list($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, $limit, $start, $order, $dir, '', $user_guid)->result();
            } else {
                $search = $this->input->post('search')['value'];

                $posts =  $this->General_model->edi_log_list($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, $limit, $start, $order, $dir, $search, $user_guid)->result();

                $totalFiltered = $this->General_model->edi_log_list($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, $limit, $start, $order, $dir, $search, $user_guid)->result();
            }

            $data = array();

            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $nestedData['supplier_guid'] = $post->supplier_guid;
                    $nestedData['status'] = $post->status;
                    $nestedData['edi_batch_no'] = $post->edi_batch_no;
                    $nestedData['file_name'] = $post->file_name;
                    $nestedData['acc_name'] = $post->acc_name;
                    $nestedData['supplier_name'] = $post->supplier_name;
                    $nestedData['created_at'] = $post->created_at;
                    $nestedData['error_message_reason'] = $post->error_message_reason;
                    $nestedData['refno_data'] = $post->refno;
                    if (in_array('IAVA', $_SESSION['module_code'])) {

                        if($post->status == 'NEW')
                        {
                            $nestedData['refno'] = "<button id='view_pdf_btn' class='btn btn-xs btn-success' title='PDF' guid='" . $post->guid . "' post_refno='" . $post->refno . "' ><i class='fa fa-file'></i></button> <button id='download_copy_new' class='btn btn-xs btn-warning' title='Download' guid='" . $post->guid . "' acc_guid='" . $post->acc_guid . "' get_edi_batch_no='" . $post->edi_batch_no . "' get_status='" . $post->status . "' get_file_name='" . $post->file_name . "' dl_supplier_guid='" . $post->supplier_guid . "'><i class='fa fa-download'></i></button> <button id='refnoList' class='btn btn-xs btn-primary' edi_batch_no='" . $post->edi_batch_no . "' acc_guid='" . $post->acc_guid . "' supplier_guid='" . $post->supplier_guid . "' title='Details'><i class='fa fa-list'></i></button>
                            <a href='" . site_url("Edi/edi_detail?batch_no=$post->edi_batch_no&guid=$post->acc_guid") . "'><button class='btn btn-xs btn-info' title='Edit'><i class='fa fa-edit'></i></button></a>";
                        }
                        else
                        {
                            $nestedData['refno'] = "<button id='view_pdf_btn' class='btn btn-xs btn-success' title='PDF' guid='" . $post->guid . "' post_refno='" . $post->refno . "' ><i class='fa fa-file'></i></button> <a href='https://file.xbridge.my/b2b-pdf/edi/$post->supplier_guid/$post->acc_guid/$post->file_name' download='$post->file_name'><button id='download_copy' class='btn btn-xs btn-warning' title='Download' guid='" . $post->guid . "' acc_guid='" . $post->acc_guid . "'><i class='fa fa-download'></i></button></a> <button id='refnoList' class='btn btn-xs btn-primary' edi_batch_no='" . $post->edi_batch_no . "' acc_guid='" . $post->acc_guid . "' supplier_guid='" . $post->supplier_guid . "' title='Details'><i class='fa fa-list'></i></button>
                            <a href='" . site_url("Edi/edi_detail?batch_no=$post->edi_batch_no&guid=$post->acc_guid") . "'><button class='btn btn-xs btn-info' title='Edit'><i class='fa fa-edit'></i></button></a>";
                        }

                        // $nestedData['refno'] = "<button id='refnoList' class='btn btn-info btn-sm' edi_batch_no='" . $post->edi_batch_no . "' acc_guid='" . $post->acc_guid . "' ><i class='fa fa-list'></i></button>
                        // <a href='https://file.xbridge.my/b2b-pdf/edi/$post->supplier_guid/$post->acc_guid/$post->file_name' download='$post->file_name'><button id='download_copy' class='btn btn-info btn-sm'><i class='fa fa-download'></i></button></a>";
                    } else {

                        if($post->status == 'NEW')
                        {
                            $nestedData['refno'] = "<button id='view_pdf_btn' class='btn btn-xs btn-success' title='PDF' guid='" . $post->guid . "' post_refno='" . $post->refno . "' ><i class='fa fa-file'></i></button> <button id='download_copy_new' class='btn btn-xs btn-warning' title='Download' guid='" . $post->guid . "' acc_guid='" . $post->acc_guid . "' get_edi_batch_no='" . $post->edi_batch_no . "' get_status='" . $post->status . "' get_file_name='" . $post->file_name . "' dl_supplier_guid='" . $post->supplier_guid . "'><i class='fa fa-download'></i></button> </a>";
                        }
                        else
                        {
                            $nestedData['refno'] = "<button id='view_pdf_btn' class='btn btn-xs btn-success' title='PDF' guid='" . $post->guid . "' post_refno='" . $post->refno . "' ><i class='fa fa-file'></i></button> <button id='refnoList' class='btn btn-xs btn-warning' edi_batch_no='" . $post->edi_batch_no . "' acc_guid='" . $post->acc_guid . "' supplier_guid='" . $post->supplier_guid . "' title='Details'><i class='fa fa-list'></i></button>
                            <a href='https://file.xbridge.my/b2b-pdf/edi/$post->supplier_guid/$post->acc_guid/$post->file_name' download='$post->file_name'><button id='download_copy' class='btn btn-xs btn-info'><i class='fa fa-download'></i></button></a> ";
                        }


                    }

                    $data[] = $nestedData;
                }
            }



            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
            );

            echo json_encode($json_data);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

        // $this->app_output->display($json);
    }

    public function edi_refno_list()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {

            $columns = array(
                0 => 'edi_batch_no',
                1 => 'acc_name',
                2 => 'refno',
                2 => 'total_line',
            );

            $supplier_guid = $this->input->post('get_supplier_guid');
            $customer_guid = $this->input->post('customer_guid');
            $edi_batch_no = $this->input->post('edi_batch_no');

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $columns[$this->input->post('order')[0]['column']];
            $dir = $this->input->post('order')[0]['dir'];

            $totalData = $this->General_model->edi_refno_list($supplier_guid, $customer_guid, $edi_batch_no, '', '', '', '', '')->num_rows();

            // $totalData = count(json_decode($totalData_temp[0]->refno));
            $totalFiltered = $totalData;

            if (empty($this->input->post('search')['value'])) {
                $posts = $this->General_model->edi_refno_list($supplier_guid, $customer_guid, $edi_batch_no, $limit, $start, $order, $dir, '')->result();
            } else {
                $search = $this->input->post('search')['value'];

                $posts =  $this->General_model->edi_refno_list($supplier_guid, $customer_guid, $edi_batch_no, $limit, $start, $order, $dir, $search)->result();

                $totalFiltered = $this->General_model->edi_refno_list($supplier_guid, $customer_guid, $edi_batch_no, $limit, $start, $order, $dir, $search)->num_rows();
            }
            $data = array();

            if (!empty($posts)) {
                foreach ($posts as $post) {

                    $nestedData['edi_batch_no'] = $post->edi_batch_no;
                    $nestedData['acc_name'] = $post->acc_name;
                    $nestedData['refno'] =  $post->refno;
                    $nestedData['total_line'] =  $post->total_line;
                    $data[] = $nestedData;
                }
            }

            // $totalFiltered =   count(json_decode($posts[0]->refno));
            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
            );

            echo json_encode($json_data);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function edi_detail()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {

            $user_guid = $_SESSION['user_guid'];
            $edi_batch_no =  $_REQUEST['batch_no'];

            $get_customer_name_list = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`
            FROM lite_b2b.`set_user` AS a
            INNER JOIN lite_b2b.acc AS b
            ON a.`acc_guid` = b.`acc_guid`
            WHERE a.`user_guid` = '$user_guid'");

            $get_supplier_name_list = $this->db->query("SELECT b.`supplier_name`,c.`supplier_group_name`,d.`acc_name`
            FROM lite_b2b.`set_user` AS a
            INNER JOIN lite_b2b.`set_supplier` AS b
            ON a.`supplier_guid` = b.`supplier_guid`
            INNER JOIN lite_b2b.set_supplier_group AS c
            ON b.`supplier_guid` = c.`supplier_guid`
            INNER JOIN lite_b2b.`acc` AS d
            ON a.`acc_guid` = d.`acc_guid`
            WHERE a.`user_guid` = '$user_guid'
            GROUP BY c.`supplier_group_name`");

            $get_period_code = $this->db->query("SELECT DATE_FORMAT(NOW(),'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 1 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 2 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 3 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 4 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 5 MONTH ,'%Y-%m') AS period_code");

            $get_edi_status = $this->db->query("SELECT * FROM lite_b2b.set_setting WHERE module_name = 'EDI_PO_FILTER'");

            $refno = $this->db->query('SELECT REPLACE(REPLACE(a.`refno`, "[", ""),"]","") AS refno
            FROM lite_b2b.`edi_log` AS a
            WHERE a.`edi_batch_no` = "' . $edi_batch_no . '"
            AND a.type = "PO" ');

            $po_status = $this->General_model->status('PO_FILTER_STATUS');

            $data = array(
                'get_customer_name_list' => $get_customer_name_list,
                'get_supplier_name_list' => $get_supplier_name_list,
                'get_period_code' => $get_period_code,
                'refno' => $refno->result_array()[0]['refno'],
                'get_edi_status' => $get_edi_status,
                'po_status' => $po_status,
                'user_guid' => $user_guid,
            );

            $this->load->view('header');
            $this->load->view('edi/edi_log_detail', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function edi_detail_table()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {


            $columns = array(
                0 => 'RefNo',
                1 => 'loc_group',
                2 => 'SCode',
                3 => 'SName',
                4 => 'PODate',
                5 => 'DeliverDate',
                6 => 'expiry_date',
                7 => 'amount',
                8 => 'include_tax',
                9 => 'amount',
                10 => 'status',
                11 => 'rejected_remark',
                12 => 'export_status',
                13 => 'edi_batch_no',
                14 => 'action',
            );

            $customer_guid = $this->input->post('customer_guid');
            $refno = $this->input->post('refno');
            $edi_status = $this->input->post('edi_status');
            $po_status = $this->input->post('po_status');
            $date_range = $this->input->post('date_range');
            $expiry_from = $this->input->post('expiry_from');
            $expiry_to = $this->input->post('expiry_to');
            $period_code = $this->input->post('period_code');
            $supplier_guid = $this->input->post('supplier_guid');

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $columns[$this->input->post('order')[0]['column']];
            $dir = $this->input->post('order')[0]['dir'];

            if ($date_range != '') {
                $daterange_temp = explode(' - ', $date_range);
                $daterange_from = date('Y-m-d', strtotime($daterange_temp[0]));
                $daterange_to = date('Y-m-d', strtotime($daterange_temp[1]));
            } else {
                $daterange_from = '';
                $daterange_to = '';
            }

            $totalData = $this->General_model->edi_detail_list($customer_guid, $refno, $edi_status, $po_status, $daterange_from, $daterange_to, $expiry_from, $expiry_to, $period_code, $supplier_guid, '', '', '', '', '')->num_rows();

            $totalFiltered = $totalData;

            if (empty($this->input->post('search')['value'])) {
                $posts = $this->General_model->edi_detail_list($customer_guid, $refno, $edi_status, $po_status, $daterange_from, $daterange_to, $expiry_from, $expiry_to, $period_code, $supplier_guid, $limit, $start, $order, $dir, '')->result();
            } else {
                $search = $this->input->post('search')['value'];

                $posts =  $this->General_model->edi_detail_list($customer_guid, $refno, $edi_status, $po_status, $daterange_from, $daterange_to, $expiry_from, $expiry_to, $period_code, $supplier_guid, $limit, $start, $order, $dir, $search)->result();

                $totalFiltered = $this->General_model->edi_detail_list($customer_guid, $refno, $edi_status, $po_status, $daterange_from, $daterange_to, $expiry_from, $expiry_to, $period_code, $supplier_guid, $limit, $start, $order, $dir, $search)->result();
            }
            // echo $this->db->last_query();
            // die;

            $data = array();

            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $nestedData['RefNo'] = $post->RefNo;
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['SCode'] = $post->SCode;
                    $nestedData['SName'] = $post->SName;
                    $nestedData['PODate'] = $post->PODate;
                    $nestedData['DeliverDate'] = $post->DeliverDate;
                    $nestedData['expiry_date'] = $post->expiry_date;
                    $nestedData['amount'] = $post->amount;
                    $nestedData['include_tax'] = $post->include_tax;
                    $nestedData['amount'] = $post->amount;
                    $nestedData['status'] = $post->status;
                    $nestedData['rejected_remark'] = $post->rejected_remark;
                    $nestedData['export_status'] = ($post->export_status == '0') ? 'Pending' : 'Exported';
                    $nestedData['edi_batch_no'] = $post->edi_batch_no;
                    if ($post->status == 'gr_completed') {
                        $nestedData['action'] = '';
                    } else {
                        $nestedData['action'] = '<input type="checkbox" id="index" name="refno[]" value="' . $post->RefNo . '">';
                    }
                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
            );

            echo json_encode($json_data);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function reflag_edi_export_status()
    {
        $refno = $this->input->post('refno');
        $type = $this->input->post('type');
        $customer_guid = $this->input->post('customer_guid');
        $user_guid = $this->input->post('user_guid');
        
        $status = '';
        $message = '';

        if ($type == 'PO') {
            $refno_in = implode("','", $refno);

            $refno_in = "'" . $refno_in . "'";

            $this->db->query("UPDATE b2b_summary.pomain_info
            SET export_status = '0'
            WHERE RefNo IN ($refno_in)
            AND customer_guid = '$customer_guid' ");

            if ($this->db->affected_rows() > 0) {
                $status = 'true';
                $message = 'Success';

                $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid ")->row('guid');
                $now =  $this->db->query("SELECT NOW() as now")->row('now');

                $log = array(
                    'guid' => $guid,
                    'customer_guid' => $customer_guid,
                    'type' => $type,
                    'refno' => json_encode($refno),
                    'created_at' => $now,
                    'created_by' => $user_guid,
                    'updated_at' => $now,
                    'updated_by' =>  $user_guid,
                );

                $this->db->insert('lite_b2b.flag_edi_log', $log);
            } else {
                $status = 'false';
                $message = 'Unsucces';
            }
        }

        $json = array(
            'status' => $status,
            'message' => $message,
        );

        echo json_encode($json);
    }

    public function download_status()
    {
        $guid = $this->input->post('guid');
        $customer_guid = $this->input->post('customer_guid');
        $supplier_guid = $this->input->post('dl_supplier_guid');
        $edi_batch_no = $this->input->post('get_edi_batch_no');
        $status = $this->input->post('get_status');
        $get_file_name = $this->input->post('get_file_name');
        $user_guid = $_SESSION['user_guid'];
        
        $to_shoot_url = "https://api.xbridge.my/rest_b2b/index.php/Edi/update_edi_po";

        //echo $to_shoot_url; die;

        if(($guid == '') || ($guid == 'null') || ($guid == null))
        {
            $data = array(  
                'para' => 'false',
                'msg' => 'Invalid GUID.',
            );
            echo json_encode($data);
            exit();
        }

        if(($customer_guid == '') || ($customer_guid == 'null') || ($customer_guid == null))
        {
            $data = array(  
                'para' => 'false',
                'msg' => 'Invalid Retailer GUID.',
            );
            echo json_encode($data);
            exit();
        }

        if(($supplier_guid == '') || ($supplier_guid == 'null') || ($supplier_guid == null))
        {
            $data = array(  
                'para' => 'false',
                'msg' => 'Invalid Supplier GUID.',
            );
            echo json_encode($data);
            exit();
        }

        if(($edi_batch_no == '') || ($edi_batch_no == 'null') || ($edi_batch_no == null))
        {
            $data = array(  
                'para' => 'false',
                'msg' => 'Invalid Batch No.',
            );
            echo json_encode($data);
            exit();
        }

        if(($status == '') || ($status == 'null') || ($status == null))
        {
            $data = array(  
                'para' => 'false',
                'msg' => 'Invalid Status.',
            );
            echo json_encode($data);
            exit();
        }

        if(($get_file_name == '') || ($get_file_name == 'null') || ($get_file_name == null))
        {
            $data = array(  
                'para' => 'false',
                'msg' => 'Invalid File Name.',
            );
            echo json_encode($data);
            exit();
        }


        $data = array(
            'guid' => $guid,
            'supplier_guid' => $supplier_guid,
            'user_guid' => $user_guid,
            'customer_guid' => $customer_guid,
            'edi_batch_no' => $edi_batch_no,
            'status' => $status,
            'get_file_name' => $get_file_name,
        );
        //print_r($data); die;

        $cuser_name = 'ADMIN';
        $cuser_pass = '1234';

        $ch = curl_init($to_shoot_url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        $output = json_decode($result);
        //$status = json_encode($output);
        // print_r($output->result);die;
        //echo $result;die;
        //close connection
        curl_close($ch);  
        //echo $output->status;
        //die;
        
        if($output->status == "true")
        {
            $msg = $output->message;
            $dl_path = $output->dl_path;

            $data = array(
            'para' => 'true',
            'msg' => $msg,
            'dl_path' => $dl_path,
            );

            echo json_encode($data);
        }
        else
        {
            $msg = $output->message;

            $data = array(
            'para' => 'false',
            'msg' => $msg,
            );

            echo json_encode($data);
        }
    }

    public function edi_grn()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];

            $edi_batch_no = $this->input->post('edi_batch_no');
            $status = $this->input->post('status');
            $generate_date_from = $this->input->post('generate_date_from');
            $generate_date_to = $this->input->post('generate_date_to');
            $period_code = $this->input->post('period_code');
            $supplier_guid = $this->input->post('supplier_guid');
            $customer_guid = $this->input->post('customer_guid');

            $get_customer_name_list = $this->db->query("SELECT b.`acc_guid`,b.`acc_name`
            FROM lite_b2b.`set_user` AS a
            INNER JOIN lite_b2b.acc AS b
            ON a.`acc_guid` = b.`acc_guid`
            WHERE a.`user_guid` = '$user_guid'");

            $get_supplier_name_list = $this->db->query("SELECT b.supplier_guid,b.`supplier_name`,c.`supplier_group_name`,d.`acc_name`
            FROM lite_b2b.`set_user` AS a
            INNER JOIN lite_b2b.`set_supplier` AS b
            ON a.`supplier_guid` = b.`supplier_guid`
            INNER JOIN lite_b2b.set_supplier_group AS c
            ON b.`supplier_guid` = c.`supplier_guid`
            INNER JOIN lite_b2b.`acc` AS d
            ON a.`acc_guid` = d.`acc_guid`
            WHERE a.`user_guid` = '$user_guid'
            GROUP BY b.`supplier_name`");

            $get_period_code = $this->db->query("SELECT DATE_FORMAT(NOW(),'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 1 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 2 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 3 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 4 MONTH ,'%Y-%m') AS period_code
            UNION ALL
            SELECT DATE_FORMAT(NOW() - INTERVAL 5 MONTH ,'%Y-%m') AS period_code");

            $get_edi_status = $this->db->query("SELECT * FROM lite_b2b.set_setting WHERE module_name = 'EDI_GRN_FILTER'");

            $data = array(
                'get_customer_name_list' => $get_customer_name_list,
                'get_supplier_name_list' => $get_supplier_name_list,
                'get_period_code' => $get_period_code,
                'get_edi_status' => $get_edi_status,
                //
                'edi_batch_no' => $edi_batch_no,
                'status' => $status,
                'generate_date_from' => $generate_date_from,
                'generate_date_to' => $generate_date_to,
                'period_code' => $period_code,
                'supplier_guid' => $supplier_guid,
                'customer_guid' => $customer_guid,
            );

            $this->load->view('header');
            $this->load->view('edi/edi_grn_view', $data);
            $this->load->view('footer');
            // $json['edi_log_list'] =  $edi_log_list;
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

        // $this->app_output->display($json);
    }

    public function edi_grn_tb()
    {
        // if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
        $edi_batch_no = $this->input->post('edi_batch_no'); // in GRN edi_batch_no = REFNO 
        // echo $edi_batch_no;
        // die;
        $status = $this->input->post('status');
        $generate_date_from = $this->input->post('generate_date_from');
        $generate_date_to = $this->input->post('generate_date_to');
        $period_code = $this->input->post('period_code');
        $supplier_guid = $this->input->post('supplier_guid'); // no data
        $customer_guid = $this->input->post('customer_guid'); // no data
        $type = $this->input->post('type');

        $columns = array(
            0 => 'status',
            1 => 'error_message_reason',
            2 => 'edi_batch_no',
            3 => 'refno',
            4 => 'supplier_name',
            5 => 'file_name',
            6 => 'created_at',
            7 => 'updated_at',
            8 => 'guid',
        );


        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->General_model->edi_grn_tb($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, $type, '', '', '', '', '')->num_rows();

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->General_model->edi_grn_tb($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, $type, $limit, $start, $order, $dir, '')->result();
        } else {
            $search = $this->input->post('search')['value'];

            $posts =  $this->General_model->edi_grn_tb($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, $type, $limit, $start, $order, $dir, $search)->result();

            $totalFiltered = $this->General_model->edi_grn_tb($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_guid, $type, $limit, $start, $order, $dir, $search)->result();
        }

        $data = array();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                //$nestedData['guid'] = $post->guid;
                $nestedData['status'] = $post->status;
                $nestedData['type'] = $post->type;
                $nestedData['edi_batch_no'] = $post->edi_batch_no;
                $nestedData['file_name'] = $post->file_name;
                $nestedData['acc_name'] = $post->acc_name;
                $nestedData['supplier_name'] = $post->supplier_name;
                $nestedData['created_at'] = $post->created_at;
                $nestedData['error_message_reason'] = $post->error_message_reason;
                $nestedData['updated_at'] = $post->updated_at;
                $nestedData['updated_by'] = $post->updated_by;
                if($post->status == 'MATCHED' || $post->status == 'MATCHED with INV Generated' || $post->status == 'UNMATCHED' || $post->status == 'MATCHED with Errors')
                {
                    $nestedData['refno'] = "<a target='framename' href='../panda_gr/gr_child?trans=".json_decode($post->refno)."'&loc=HQ'>".json_decode($post->refno)."";
                }
                else
                {
                    $nestedData['refno'] = "";
                }

                if($post->remark != '' && $post->remark != 'null' && $post->remark != null)
                {
                    $nestedData['guid'] = "<button id='remarktb' title='REMARK' class='btn btn-xs btn-primary' guid='" . $post->guid . "'><i class='fa fa-list'></i></button>";
                }
                else
                {
                    $nestedData['guid'] = '';
                }


                $data[] = $nestedData;
            }
        }



        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        );

        echo json_encode($json_data);
    }

    public function edi_grn_remark_tb()
    {
        // if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {

        // $columns = array(
        //     0 => 'edi_batch_no',
        //     1 => 'acc_name',
        //     2 => 'refno',
        //     2 => 'total_line',
        // );

        $customer_guid = $this->input->post('customer_guid');
        $guid = $this->input->post('guid');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->General_model->edi_grn_remark_tb($customer_guid, $guid, '', '', '', '', '')->num_rows();

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->General_model->edi_grn_remark_tb($customer_guid, $guid, $limit, $start, $order, $dir, '')->result();
        } else {
            $search = $this->input->post('search')['value'];

            $posts =  $this->General_model->edi_grn_remark_tb($customer_guid, $guid, $limit, $start, $order, $dir, $search)->result();

            $totalFiltered = $this->General_model->edi_grn_remark_tb($customer_guid, $guid, $limit, $start, $order, $dir, $search)->num_rows();
        }
        $data = array();

        if (!empty($posts)) {
            foreach ($posts as $post) {
                $remark_encode = $post->remark;
                $array = json_decode($remark_encode);

                foreach ($array as $key => $value) {
                    foreach($value as $val)
                    {
                        $nestedData['remark'] = $val->reason;
                        $nestedData['message'] = $val->message;

                        $data[] = $nestedData;
                    }
                }
            }
        }

        // $totalFiltered =   count(json_decode($posts[0]->refno));
        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        );

        echo json_encode($json_data);
    }

    public function retrieve_pdf_path()
    {
        $refno_data = $this->input->post('type_val');

        $check_scode = $this->db->query("SELECT supplier_code from b2b_summary.pomain_info where refno = '$refno_data' and customer_guid = '".$_SESSION['customer_guid']."'")->row('supplier_code');

        if($check_scode == '')
        {
            $check_scode = $this->db->query("SELECT scode AS supplier_code from b2b_summary.pomain where refno = '$refno_data' and customer_guid = '".$_SESSION['customer_guid']."'")->row('supplier_code');
        }

        $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', 'PO'), 'code', '$check_scode'), 'refno' , '$refno_data') AS query FROM menu where module_link = 'panda_po_2'")->row('query');
        //echo $this->db->last_query();die;

        $virtual_path = $this->db->query("SELECT file_path FROM lite_b2b.acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
       
        $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

        // $test = $this->file_config_b2b->file_path('$customer_guid','$device_type','$module_type','$type,$code');
        $file_config_final_path = $this->file_config_b2b->file_path($_SESSION['customer_guid'],'web','general_doc','main_path','GDMP');
        // $test = $this->file_config_b2b->file_path();die;

        $filename = $file_config_final_path.'/'.$replace_var.'.pdf';

        $file_headers = @get_headers($filename);

        if($file_headers[0] == 'HTTP/1.1 404 Not Found') 
        {
            $filename = site_url('B2b_po/po_report?refno='.$refno_data);

            $check_archive = $this->db->query("SELECT refno FROM b2b_archive.pomain WHERE refno = '$refno_data' AND customer_guid = '".$_SESSION['customer_guid']."' ")->result_array();
        }

        $data = array(
            'filename' => $filename,
            'count_archive' => count($check_archive),
        );
    
        echo json_encode($data);
    }
} // nothing after this
