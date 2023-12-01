<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Panda_other_doc extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            // if ($_SERVER['HTTPS'] == "on")
            // {
            //     $url = "http://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];    
            //     header("Location: $url");
            // } 

            // echo $query;

            $b2b_doc_code = $_REQUEST['code'];
            $customer_guid = $this->session->userdata('customer_guid');
            $other_doc_filter_drop_down = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'other_doc_filter' order by code='ALL' desc, code asc");

            $details = $this->db->query("SELECT * FROM other_doc_setting WHERE customer_guid = '$customer_guid' AND code = '$b2b_doc_code' LIMIT 1");

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     echo $this->db->last_query();die;
            // }
            // echo $this->db->last_query();die;
            $url = site_url('Panda_other_doc/view_table');

            $query = '';
            if (isset($_REQUEST['refno'])) {
                $refno = $_REQUEST['refno'];
            } else {
                $refno = '';
            }

            if (isset($_REQUEST['status'])) {
                $status = $_REQUEST['status'];
            } else {
                $status = '';
            }

            if (isset($_REQUEST['datetime'])) {
                $datetime = $_REQUEST['datetime'];
                $start_date = substr($datetime, 0, 10);
                $end_date = substr($datetime, 12, 21);
            } else {
                $start_date = '';
                $end_date = '';
            }

            $data = array(
                'b2b_doc_code' => $b2b_doc_code,
                'b2b_details' => $details,
                'other_doc_table_url' => $url,
                'refno' => $refno,
                'status' => $status,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'other_doc_filter_drop_down' => $other_doc_filter_drop_down,
            );
            // print_r($data);die;

            $this->load->view('header');
            $this->load->view('other_doc/panda_other_doc', $data);
            $this->load->view('footer');
            // echo $url.$b2b_doc_code.$customer_guid;die;
        } else {
            redirect('login_c');
        }
    }

    public function view_table()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $customer_guid = $this->session->userdata('customer_guid');
            $doctype = $this->input->post('b2b_doc_code');
            // $supcode = $this->session->userdata('query_supcode');
            // $b2b_database  = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');
            $b2b_database  = "b2b_summary";
            $b2b_table = $this->db->query("SELECT * FROM other_doc_setting WHERE customer_guid = '$customer_guid' AND code = '$doctype'")->row('table');
            // echo $b2b_database.$b2b_table;die;

            $query = '';
            if ($this->input->post('refno') != '') {
                $refno = $this->input->post('refno');
                $query .= " AND refno = '" . $refno . "'";
            } else {
                $query .= '';
            }

            if ($this->input->post('status') != '') {
                $status = $this->input->post('status');
                if ($status == 'ALL') {
                    $get_status = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'other_doc_filter'");
                    $status = '';
                    foreach ($get_status->result() as $row) {
                        $status .= "'" . $row->code . "',";
                    }
                    $status = rtrim($status, ',');
                } else {
                    $status = "'" . $this->input->post('status') . "'";
                }
                $query .= ' AND status IN (' . $status . ')';
            } else {
                $query .= "AND status IN ('')";
            }

            if ($this->input->post('start_date') != '') {
                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');

                $query .= " AND doctime BETWEEN '" . $start_date . " 00:00:00' AND '" . $end_date . " 23:59:59'";
            } else {
                $query .= "";
            }
            // echo $query;die;

            $columns = array(
                0 => 'refno',
                1 => 'supcode',
                2 => 'debtor_code',
                3 => 'supname',
                4 => 'doctime',
                5 => 'status',
                6 => 'button',
                7 => 'box',
            );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $columns[$this->input->post('order')[0]['column']];
            $dir = $this->input->post('order')[0]['dir'];

            // print_r($this->session->userdata('check_supcode_without_quote'));die;
            if ($customer_guid == '403810171FA711EA9BB8E4E7491C3E1E') {
                if ($doctype == 'SIN') {
                    $supcode_array = $this->session->userdata('check_supcode_without_quote');
                    $supcode_string = '';
                    foreach ($supcode_array as $row) {
                        $supcode_string .= "'" . 'D' . $row . "'" . ',';
                    }
                    // echo $supcode_string;die;
                    $supcode = rtrim($supcode_string, ',');
                } else {
                    $supcode = $this->session->userdata('query_supcode');
                }
            } else {
                $supcode = $this->session->userdata('query_supcode');
            }
            if (!in_array('IAVA', $_SESSION['module_code'])) {
                $xsupcode = $this->session->userdata('query_supcode');
                $debit_supcode = $this->db->query("SELECT accpdebit FROM $b2b_database.supcus WHERE customer_guid = '$customer_guid' AND code IN($xsupcode) AND (accpdebit <> '' AND accpdebit IS NOT NULL)");
                // echo $this->db->last_query();die;
                if ($debit_supcode->num_rows() > 0) {
                    $dcode = '';
                    foreach ($debit_supcode->result()  as $debitcode) {
                        $dcode .= "'" . $debitcode->accpdebit . "',";
                    }
                    $dcode = rtrim($dcode, ",");
                    if ($dcode != '' || $dcode != null) {
                        $supcode = $supcode . ',' . $dcode;
                    }
                }
            }
            // echo $dcode;die;
            // echo $this->db->last_query();die;
            // echo $supcode;die;
            if (empty($this->input->post('search')['value'])) {
                if (in_array('IAVA', $_SESSION['module_code'])) {
                    //$totalData = $this->db->query("SELECT COUNT(DISTINCT refno) as count FROM $b2b_database.$b2b_table WHERE customer_guid = '$customer_guid' AND doctype = '$doctype' $query")->row('count');
                    $totalData = $this->db->query("SELECT COUNT(`count`) AS `count` FROM ( SELECT COUNT(DISTINCT refno) as count FROM $b2b_database.$b2b_table WHERE customer_guid = '$customer_guid' AND doctype = '$doctype' $query GROUP BY refno,doctime ) a ")->row('count');
                    // echo $this->db->last_query();die;
                    $totalFiltered = $totalData;
                } else {
                    $totalData = $this->db->query("SELECT COUNT(DISTINCT refno) as count FROM $b2b_database.$b2b_table WHERE customer_guid = '$customer_guid' AND doctype = '$doctype' AND supcode IN ($supcode) $query")->row('count');
                    $totalFiltered = $totalData;
                }
                // echo $this->db->last_query();die;
                // scode IN (".$_SESSION['query_supcode'].")

            } else {
                $search = addslashes($this->input->post('search')['value']);
                if (in_array('IAVA', $_SESSION['module_code'])) {
                    $totalData = $this->db->query("SELECT count(refno) as count FROM (SELECT IF( STATUS IS NULL || STATUS = '', 'NEW', STATUS ) AS status, refno, supname, doctime, doctype, IF( b.code IS NULL, a.supcode, b.accpdebit ) AS debtor_code, CASE WHEN b.code IS NULL AND c.code IS NOT NULL THEN GROUP_CONCAT(DISTINCT c.code) WHEN b.code IS NULL AND c.code IS NULL THEN '' WHEN b.code IS NOT NULL AND c.code IS NOT NULL THEN 'error' WHEN b.code IS NOT NULL AND c.code IS NULL THEN GROUP_CONCAT(DISTINCT b.code) WHEN b.code IS NOT NULL AND c.code = '' THEN 'error' ELSE '0' END AS supcode FROM $b2b_database.$b2b_table  a LEFT JOIN b2b_summary.supcus b ON a.customer_guid = b.customer_guid AND a.supcode = b.code AND b.type <> 'C' LEFT JOIN b2b_summary.supcus c ON a.supcode = c.`AccPDebit` AND a.`customer_guid` = c.`customer_guid` AND c.type <> 'C' WHERE a.customer_guid = '$customer_guid' AND a.doctype = '$doctype' $query GROUP BY a.refno,a.doctime) a WHERE (refno like '%" . $search . "%' OR supname like '%" . $search . "%' OR supcode like '%" . $search . "%')")->row('count');
                    $totalFiltered = $totalData;
                } else {
                    $totalData = $this->db->query("SELECT count(refno) FROM (SELECT IF( STATUS IS NULL || STATUS = '', 'NEW', STATUS ) AS status, refno, supname, doctime, doctype, IF( b.code IS NULL, a.supcode, b.accpdebit ) AS debtor_code, CASE WHEN b.code IS NULL AND c.code IS NOT NULL THEN GROUP_CONCAT(DISTINCT c.code) WHEN b.code IS NULL AND c.code IS NULL THEN '' WHEN b.code IS NOT NULL AND c.code IS NOT NULL THEN 'error' WHEN b.code IS NOT NULL AND c.code IS NULL THEN GROUP_CONCAT(DISTINCT b.code) WHEN b.code IS NOT NULL AND c.code = '' THEN 'error' ELSE '0' END AS supcode FROM $b2b_database.$b2b_table  a LEFT JOIN b2b_summary.supcus b ON a.customer_guid = b.customer_guid AND a.supcode = b.code AND b.type <> 'C' LEFT JOIN b2b_summary.supcus c ON a.supcode = c.`AccPDebit` AND a.`customer_guid` = c.`customer_guid` AND c.type <> 'C' WHERE a.customer_guid = '$customer_guid' AND a.doctype = '$doctype' AND supcode IN ($supcode) $query GROUP BY a.refno) a WHERE (refno like '%" . $search . "%' OR supname like '%" . $search . "%' OR supcode like '%" . $search . "%') ORDER BY $order $dir LIMIT $start,$limit")->row('count');
                    $totalFiltered = $totalData;
                }

                // $totalFiltered = $totalData; 
                // echo $this->db->last_query();
            }

            if (empty($this->input->post('search')['value'])) {
                if (in_array('IAVA', $_SESSION['module_code'])) {
                    // echo $start;die;
                    $posts = $this->db->query("SELECT IF( STATUS IS NULL || STATUS = '', 'NEW', STATUS ) AS status, refno, supname, doctime, doctype, IF( b.code IS NULL, a.supcode, b.accpdebit ) AS debtor_code, CASE WHEN b.code IS NULL AND c.code IS NOT NULL THEN GROUP_CONCAT(DISTINCT c.code) WHEN b.code IS NULL AND c.code IS NULL THEN '' WHEN b.code IS NOT NULL AND c.code IS NOT NULL THEN 'error' WHEN b.code IS NOT NULL AND c.code IS NULL THEN GROUP_CONCAT(DISTINCT b.code) WHEN b.code IS NOT NULL AND c.code = '' THEN 'error' ELSE '0' END AS supcode FROM $b2b_database.$b2b_table  a LEFT JOIN b2b_summary.supcus b ON a.customer_guid = b.customer_guid AND a.supcode = b.code AND b.type <> 'C' LEFT JOIN b2b_summary.supcus c ON a.supcode = c.`AccPDebit` AND a.`customer_guid` = c.`customer_guid` AND c.type <> 'C' WHERE a.customer_guid = '$customer_guid' AND a.doctype = '$doctype' $query GROUP BY a.refno,a.doctime ORDER BY $order $dir LIMIT $start,$limit")->result();
                    // echo $this->db->last_query();die;
                } else {
                    $posts = $this->db->query("SELECT IF( STATUS IS NULL || STATUS = '', 'NEW', STATUS ) AS status, refno, supname, doctime, doctype, IF( b.code IS NULL, a.supcode, b.accpdebit ) AS debtor_code, CASE WHEN b.code IS NULL AND c.code IS NOT NULL THEN GROUP_CONCAT(DISTINCT c.code) WHEN b.code IS NULL AND c.code IS NULL THEN '' WHEN b.code IS NOT NULL AND c.code IS NOT NULL THEN 'error' WHEN b.code IS NOT NULL AND c.code IS NULL THEN GROUP_CONCAT(DISTINCT b.code) WHEN b.code IS NOT NULL AND c.code = '' THEN 'error' ELSE '0' END AS supcode FROM $b2b_database.$b2b_table  a LEFT JOIN b2b_summary.supcus b ON a.customer_guid = b.customer_guid AND a.supcode = b.code AND b.type <> 'C' LEFT JOIN b2b_summary.supcus c ON a.supcode = c.`AccPDebit` AND a.`customer_guid` = c.`customer_guid` AND c.type <> 'C' WHERE a.customer_guid = '$customer_guid' AND a.doctype = '$doctype' AND supcode IN ($supcode) $query GROUP BY a.refno ORDER BY $order $dir LIMIT $start,$limit")->result();
                    // $posts = $this->db->query("SELECT IF(status is null || status = '','NEW',status) as status,refno,supcode,supname,doctime,doctype FROM $b2b_database.$b2b_table WHERE customer_guid = '$customer_guid' AND doctype = '$doctype' AND supcode IN ($supcode) $query ORDER BY $order $dir LIMIT $start,$limit")->result();
                }
                // echo $this->db->last_query();die;
            } else {

                $search = addslashes($this->input->post('search')['value']);
                if (in_array('IAVA', $_SESSION['module_code'])) {
                    $posts =  $this->db->query("SELECT * FROM (SELECT IF( STATUS IS NULL || STATUS = '', 'NEW', STATUS ) AS status, refno, supname, doctime, doctype, IF( b.code IS NULL, a.supcode, b.accpdebit ) AS debtor_code, CASE WHEN b.code IS NULL AND c.code IS NOT NULL THEN GROUP_CONCAT(DISTINCT c.code) WHEN b.code IS NULL AND c.code IS NULL THEN '' WHEN b.code IS NOT NULL AND c.code IS NOT NULL THEN 'error' WHEN b.code IS NOT NULL AND c.code IS NULL THEN GROUP_CONCAT(DISTINCT b.code) WHEN b.code IS NOT NULL AND c.code = '' THEN 'error' ELSE '0' END AS supcode FROM $b2b_database.$b2b_table  a LEFT JOIN b2b_summary.supcus b ON a.customer_guid = b.customer_guid AND a.supcode = b.code AND b.type <> 'C' LEFT JOIN b2b_summary.supcus c ON a.supcode = c.`AccPDebit` AND a.`customer_guid` = c.`customer_guid` AND c.type <> 'C' WHERE a.customer_guid = '$customer_guid' AND a.doctype = '$doctype' $query GROUP BY a.refno,a.doctime) a WHERE (refno like '%" . $search . "%' OR supname like '%" . $search . "%' OR supcode like '%" . $search . "%') ORDER BY $order $dir LIMIT $start,$limit")->result();
                    $totalFiltered = $totalData;
                } else {
                    $posts =  $this->db->query("SELECT * FROM (SELECT IF( STATUS IS NULL || STATUS = '', 'NEW', STATUS ) AS status, refno, supname, doctime, doctype, IF( b.code IS NULL, a.supcode, b.accpdebit ) AS debtor_code, CASE WHEN b.code IS NULL AND c.code IS NOT NULL THEN GROUP_CONCAT(DISTINCT c.code) WHEN b.code IS NULL AND c.code IS NULL THEN '' WHEN b.code IS NOT NULL AND c.code IS NOT NULL THEN 'error' WHEN b.code IS NOT NULL AND c.code IS NULL THEN GROUP_CONCAT(DISTINCT b.code) WHEN b.code IS NOT NULL AND c.code = '' THEN 'error' ELSE '0' END AS supcode FROM $b2b_database.$b2b_table  a LEFT JOIN b2b_summary.supcus b ON a.customer_guid = b.customer_guid AND a.supcode = b.code AND b.type <> 'C' LEFT JOIN b2b_summary.supcus c ON a.supcode = c.`AccPDebit` AND a.`customer_guid` = c.`customer_guid` AND c.type <> 'C' WHERE a.customer_guid = '$customer_guid' AND a.doctype = '$doctype' AND supcode IN ($supcode) $query GROUP BY a.refno) a WHERE (refno like '%" . $search . "%' OR supname like '%" . $search . "%' OR supcode like '%" . $search . "%') ORDER BY $order $dir LIMIT $start,$limit")->result();
                    // $posts =  $this->db->query("SELECT IF(status is null || status = '','NEW',status) as status,refno,supcode,supname,doctime,doctype FROM $b2b_database.$b2b_table WHERE customer_guid = '$customer_guid' AND doctype = '$doctype' AND supcode IN ($supcode) $query AND (refno like '%".$search."%' OR supname like '%".$search."%' OR supcode like '%".$search."%') ORDER BY $order $dir LIMIT $start,$limit")->result();
                    $totalFiltered = $totalData;
                }

                // $totalFiltered =   $totalData; 
            }

            $data = array();
            if (!empty($posts)) {
                // print_r($posts) ;die;
                // echo var_dump($posts);die;

                foreach ($posts as $post) {

                    if($customer_guid == '599348EDCB2F11EA9A81000C29C6CEB2') // ninso
                    {
                        if ($doctype == 'SDN' || $doctype == 'SIN' || $doctype == 'SVI') 
                        {
                            $get_supcode = $post->debtor_code;
                        }
                        else
                        {
                            $get_supcode = $post->supcode;

                            if ($get_supcode == '') 
                            {
                                $get_supcode = $post->debtor_code;
                            }
                        }
                    }
                    else
                    {
                        $get_supcode = $post->supcode;
                    
                        if ($get_supcode == '') 
                        {
                            $get_supcode = $post->debtor_code;
                        }
                    }
                    
                    $nestedData['refno'] = $post->refno;
                    $nestedData['supcode'] = $post->supcode;
                    $nestedData['debtor_code'] = $post->debtor_code;
                    $nestedData['supname'] = $post->supname;
                    $nestedData['doctime'] = $post->doctime;
                    $nestedData['status'] = $post->status;
                    $nestedData['button'] = "<a href=" . site_url('Panda_other_doc/other_doc_child') . "?trans=" . urlencode(str_replace(' ', '%20', $post->refno)) . "&code=" . $doctype . "&supcode=" . $get_supcode . " style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                              ";
                    $nestedData['box'] = '<input type="checkbox" class="data-check" value="' . $post->refno . '">';


                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );

            echo json_encode($json_data);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function other_doc_child()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            // echo 1;die;
            $this->panda->get_uri();
            $refno = urldecode($_REQUEST['trans']);
            $doctype = urldecode($_REQUEST['code']);
            $supcode = urldecode($_REQUEST['supcode']);
            // echo $refno.$doctype;die;

            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];

            if ($supcode != '' && $supcode != 'null' && $supcode != null) {
                $add_where_supcode = "AND supcode = '$supcode' ";
            } else {
                $add_where_supcode = "";
            }

            //print_r($add_where_supcode); die;

            // $b2b_database  = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');
            $b2b_database  = "b2b_summary";
            $b2b_table1 = $this->db->query("SELECT * FROM other_doc_setting WHERE customer_guid = '$customer_guid' AND code = '$doctype'");
            $b2b_table = $b2b_table1->row('table');
            $status = $this->db->query("SELECT IF(status is null || status = '','NEW',status) as status,supcode FROM $b2b_database.$b2b_table WHERE customer_guid = '$customer_guid' AND doctype = '$doctype' AND refno = '$refno' AND supcode = '$supcode'");

            $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'acc_doc', 'main_path', 'ACC');

            if (!in_array('!VODSUPPMOV', $_SESSION['module_code'])) {

                $this->db->query("UPDATE $b2b_database.$b2b_table set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' AND doctype = '$doctype' AND supcode = '$supcode' ");

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_$doctype-$supcode'
                , 'other_doc'
                , '$refno'
                , now()
                ");
            };

            $description = $b2b_table1->row('description');

            // echo $this->db->last_query();die;
            // print_r ($status->result());die;
            // echo $status->row('status');die;
            $main_path = $file_config_main_path;  //'./acc_doc';
            if (!file_exists($main_path)) {
                // echo 1;
                mkdir($main_path, 0777);
                chmod($main_path, 0777);
            }
            $file_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '$customer_guid'")->row('file_path');
            // echo $file_path.'<br>';

            $filename2 = $main_path . substr($file_path, 0, strripos($file_path, '/'));

            $file_path_checking_2 = $filename2;
            // echo $file_path_checking_2.'<br>';
            // mkdir('./acc_doc/uploads/tfvaluemart', 0777,true);
            // chmod($file_path_checking, 0777,true);
            if (!file_exists($file_path_checking_2)) {
                // echo 1;
                mkdir($file_path_checking_2, 0777, true);
                chmod($file_path_checking_2, 0777);
            }

            $filename = $main_path . $file_path;

            $file_path_checking = $filename;
            // echo $file_path_checking.'<br>';
            // mkdir('./acc_doc/uploads/tfvaluemart', 0777,true);
            // chmod($file_path_checking, 0777,true);
            if (!file_exists($file_path_checking)) {
                // echo 1;
                mkdir($file_path_checking, 0777, true);
                chmod($file_path_checking, 0777);
            }

            $file_path_checking2 = $file_path_checking . '/' . $doctype;
            // echo $file_path_checking2.'<br>';die;
            if (!file_exists($file_path_checking2)) {
                // echo 1;
                mkdir($file_path_checking2, 0777);
                chmod($file_path_checking2, 0777);
            }
            $excel_supcode = $status->row('supcode');
            $excel_file = $doctype . '_' . $excel_supcode . '_' . $refno . '.xlsx';
            // echo $file_path_checking2.$excel_file.'<br>';

            $file_path_checking3 = $file_path_checking2 . '/' . $excel_file;
            // echo $file_path_checking3.'<br>';
            if (!file_exists($file_path_checking3)) {
                $show_excel_button = 0;
                $excel_file_path = '';
                // mkdir($file_path_checking2, 0777);
                // chmod($file_path_checking2, 0777);
            } else {
                $download_ip = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'acc_doc', 'main_path', 'ACC_link');
                $show_excel_button = 1;
                $main_path1 = 'acc_doc';
                $filename_excel = $main_path1 . $file_path;
                // echo $filename_excel;die;
                $file_path_excel_checking = $filename_excel;
                // echo $file_path_excel_checking;
                $file_path_excel_checking2 = $file_path_excel_checking . '/' . $doctype;
                $file_path_checking_3 = $file_path_excel_checking2 . '/' . $excel_file;
                $excel_file_path = $download_ip . $file_path . '/' . $doctype . '/' . $excel_file;
            }
            // echo $show_excel_button;  
            // echo $excel_file_path;          
            // die;            

            $data = array(
                // 'filename' => $filename, 
                'title' => $b2b_table1->row('description'),
                'refno' => $refno,
                'doctype' => $doctype,
                'supcode' => $supcode,
                'status' => $status->row('status'),
                'show_excel_button' => $show_excel_button,
                'excel_file_path' => $excel_file_path,
                // 'file_headers' => $file_headers,
            );
            // print_r($data);die;

            $this->load->view('header');
            $this->load->view('other_doc/other_doc_pdf', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function readfile()
    {
        $customer_guid = $this->session->userdata('customer_guid');
        $refno = urldecode($_REQUEST['refno']);
        $code = urldecode($_REQUEST['code']);
        $supcode = urldecode($_REQUEST['supcode']);
        $user_guid = $this->session->userdata('user_guid');

        // echo urldecode($refno);die;
        $filename = $this->db->query("SELECT * FROM b2b_summary.other_doc WHERE refno = '$refno' AND customer_guid = '$customer_guid' AND doctype = '$code' AND supcode = '$supcode' LIMIT 1");
        // echo $this->db->last_query();die;
        // print_r($filename->result());die;
        $virtual_path = $this->db->query("SELECT rest_url FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
        $acc_sys_type = $this->db->query("SELECT accounting_doc FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('accounting_doc');

        if ($acc_sys_type == 'nav') {
            $path = $virtual_path . '/' . 'Document?refno=' . urlencode($filename->row('refno')) . '&doctype=' . $filename->row('doctype') . '&supcode=' . $filename->row('supcode') . '&doctime=' . $filename->row('doctime');
        } else {
            $path = $virtual_path . '/' . 'Document_autocount?refno=' . urlencode(str_replace('/', '', $filename->row('refno'))) . '&doctype=' . $filename->row('doctype') . '&supcode=' . str_replace('/', '', $filename->row('supcode')) . '&doctime=' . $filename->row('doctime');
        }
        // echo $path;die;
        $to_shoot_url = str_replace(' ', '%20', $path);

        // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
        // {
        //     echo $to_shoot_url;die;
        // }
        // http://18.139.87.215/rest_api/index.php/return_json/Document_download?refno=270118SM2PSPR0077&doctype=SIN&supcode=27Q006&doctime=2020-09-09%2023:15:00
        // echo $to_shoot_url;die;
        $ch = curl_init($to_shoot_url);

        $headers = [
            'x-api-key: codex1234',
            'Content-Type: application/x-www-form-urlencoded'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $response = curl_exec($ch);
        // echo $response;die;
        header('Content-type: ' . 'application/pdf');
        header('Content-Disposition: ' . $disposition . '; filename=Invoice_' . $refno . '.pdf');
        echo $response;

        curl_close($curl);
        die;

        // header("Location: ".$path, true, 301);
        // exit();
        // doctype_doctime_supcode_refno
    }

    public function other_doc_filter()
    {
        $refno = $this->input->post('other_doc_refno');
        $status = $this->input->post('other_doc_status');
        $datetime = $this->input->post('other_doc_datetime');
        $code = $_REQUEST['code'];

        redirect(site_url('Panda_other_doc') . '?code=' . $code . '&refno=' . $refno . '&status=' . $status . '&datetime=' . $datetime);
        // print_r($this->input->post());die;
    }

    public function read_64()
    {

        $customer_guid = $this->session->userdata('customer_guid');
        $refno = $_REQUEST['refno'];
        $code = $_REQUEST['code'];
        $filename = $this->db->query("SELECT * FROM b2b_summary.other_doc WHERE refno = '$refno' AND customer_guid = '$customer_guid' AND doctype = '$code' LIMIT 1");
        // echo $this->db->last_query();die;
        // print_r($filename->result());die;
        $virtual_path = $this->db->query("SELECT rest_url FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
        $acc_sys_type = $this->db->query("SELECT accounting_doc FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('accounting_doc');

        if ($acc_sys_type == 'nav') {
            $path = $virtual_path . '/' . 'Document_download?refno=' . $filename->row('refno') . '&doctype=' . $filename->row('doctype') . '&supcode=' . $filename->row('supcode') . '&doctime=' . $filename->row('doctime');
        } else {
            $path = $virtual_path . '/' . 'Document_autocount_download?refno=' . urlencode(str_replace('/', '', $filename->row('refno'))) . '&doctype=' . $filename->row('doctype') . '&supcode=' . str_replace('/', '', $filename->row('supcode')) . '&doctime=' . $filename->row('doctime');
        }
        // echo $path;die;
        // header("Location: ".$path, true, 301);
        // exit();

        $to_shoot_url = str_replace(' ', '%20', $path);
        // http://18.139.87.215/rest_api/index.php/return_json/Document_download?refno=270118SM2PSPR0077&doctype=SIN&supcode=27Q006&doctime=2020-09-09%2023:15:00
        // echo $to_shoot_url;die;
        $ch = curl_init($to_shoot_url);

        $headers = [
            'x-api-key: codex1234',
            'Content-Type: application/x-www-form-urlencoded'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $result = curl_exec($ch);

        curl_close($ch);
        // echo $result;die;
        // return $result;          
        $result = $result;
        $b64Doc = $result;
        // echo str_replace('\r\n', '', $b64Doc);die;
        // echo base64_decode(str_replace('\r\n', '', $b64Doc));die;
        $pdf_b64 = base64_decode(str_replace('\r\n', '', $b64Doc));
        // $rute = 'http://192.168.10.29/github/panda_b2b/uploads/tfvaluemart/rtrtr.pdf';
        // $rute = '192.168.10.30/panda_web/haha.pdf';
        // $rute = 'C:\xampp\htdocs\lite_panda_b2b\uploads\tfvalue\acceptance_form\desmond.pdf';
        // if(file_put_contents($rute, $pdf_b64)){
        //just to force download by the browser
        header("Content-type: application/pdf");
        header('Content-Disposition: inline; filename="' . $filename->row('refno') . '.pdf"');

        //print base64 decoded
        echo $pdf_b64;
        // }die;
    }

    public function direct_print_merge()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $this->panda->get_uri();
            // $refno = $_REQUEST['trans'];
            // $loc = $_REQUEST['loc'];
            // $customer_guid = $_SESSION['customer_guid'];
            // $user_guid = $_SESSION['user_guid'];
            // $from_module = $_SESSION['frommodule'];
            $pdf_name = $_REQUEST['pdfname'];
            $action = $_REQUEST['action'];
            $file_config_final_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'general_doc', 'merge_print_acc_doc', 'ACCMRGPRT');
            $file = $file_config_final_path . '/' . $pdf_name . '.pdf';
            //$file = 'merge/'.$pdf_name.'.pdf'; // generate the file

            // $file = $to_location.'/'.$to_location2.'/'.$filename.'.pdf'; 
            // echo $action;die;
            // $filename =$filename.'.pdf'; 
            if ($action == 'print') {
                $type = 'inline';
            } elseif ($action == 'download') {
                $type = 'attachment';
            } else {
                $type = 'attachment';
            }
            // echo $type;die;
            $b64Doc = chunk_split(base64_encode(file_get_contents($file)));
            // echo $b64Doc;
            $pdf_b64 = base64_decode($b64Doc);
            // $pdf_b64 = base64_decode(str_replace('\r\n', '', $b64Doc));
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $pdf_name1 = 'Accounting_document_' . date('d-m-Y_H:i:s');
            unlink($file_config_final_path . '/' . $pdf_name . '.pdf');
            header("Content-type: application/pdf");
            header('Content-Disposition: ' . $type . '; filename="' . $pdf_name1 . '.pdf"');
            echo $pdf_b64;
            die;
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function upload_acc_excel()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            // echo 1;die;
            $this->panda->get_uri();
            $customer_guid = $this->session->userdata('customer_guid');
            // $refno = $_REQUEST['trans'];
            // $loc = $_REQUEST['loc'];
            // $customer_guid = $_SESSION['customer_guid'];
            // $user_guid = $_SESSION['user_guid'];
            // $from_module = $_SESSION['frommodule'];
            $drop_down = $this->db->query("SELECT * FROM lite_b2b.other_doc_setting WHERE customer_guid = '$customer_guid' ORDER BY seq ASC");
            $supcode = $this->db->query("SELECT * FROM lite_b2b.set_supplier_group a INNER JOIN b2b_summary.supcus b ON a.supplier_group_name = b.code AND b.customer_guid = '$customer_guid' WHERE a.customer_guid = '$customer_guid' ORDER BY a.supplier_group_name ASC");
            // echo $this->db->last_query();die;
            // print_r($supcode->result());die;
            $data = array(
                'drop_down' => $drop_down,
                'supcode' => $supcode,
            );
            // echo $this->db->last_query();
            $this->load->view('header');
            $this->load->view('other_doc/upload_other_doc', $data);
            $this->load->view('footer');

            // print_r($drop_down);die;

        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }



    public function upload_excel_file_acc_doc()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        ini_set('post_max_size', '64M');
        ini_set('upload_max_filesize', '64M');


        $refno = $this->input->post('other_doc_refno');
        $supcode = $this->input->post('supcode');
        $doctype = $this->input->post('doctype');
        $customer_guid = $_SESSION['customer_guid'];
        $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'acc_doc', 'main_path', 'ACC');
        $file_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '$customer_guid'")->row('file_path');
        $path2 = $file_path;
        // print_r($this->input->post());die;
        // echo $_FILES["file"]["tmp_name"];die;
        $defined_path = $file_config_main_path . $path2 . '/' . $doctype; //'./acc_doc/uploads/tfvaluemart/'
        $file_uuid = $doctype . '_' . $supcode . '_' . $refno;
        // echo $file_uuid;die;
        $config['upload_path'] = $defined_path;
        $config['allowed_types'] = '*';
        $config['max_size'] = 50000;
        $config['file_name'] = $file_uuid;
        // echo $defined_path;die;
        // var_dump( $this->input->post('file') );die; 
        // print_r($this->input->post());die;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            // print_r($this->upload->display_errors());die;
            $error = array('error' => $this->upload->display_errors());

            if (null != $error) {
                // $error_message = $error['error'];
                // echo $error_message;die;
                // $xerror = $this->db->error();
                // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
                // $this->message->error_message($xerror['message'], '1');
                // exit();
            } //close else

        } else {
            $data = array('upload_data' => $this->upload->data());

            // print_r($_FILES["file"]);

            $filename = $defined_path . $data['upload_data']['file_name'];

            //  Include PHPExcel_IOFactory
            $this->load->library('Excel');

            $inputFileName = $filename;

            //  Read your Excel workbook
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {

                //   $error_message = $this->lang->line('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                // $xerror = $this->db->error();
                // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
                // $this->message->error_message_with_status($xerror['message'], '1', '');
                // exit();

            }

            $this->session->set_flashdata('message', 'File Uploaded');
            redirect(site_url('Panda_other_doc/upload_acc_excel'));
            // unlink($filename);

        }

        // return $objPHPExcel;

    }
}
