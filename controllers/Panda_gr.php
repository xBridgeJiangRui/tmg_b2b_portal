<?php
class panda_gr extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('form_validation');

        //load the department_model
        $this->load->model('GR_model');
        $this->load->model('General_model');
    }

    public function get_username()
    {
        $username = $this->db->query("SELECT user_name FROM set_user  WHERE user_guid = '" . $_SESSION['user_guid'] . "' GROUP BY user_guid")->row('user_name');
        return $username;
    }

    public function datetime()
    {
        $datetime = $this->db->query("SELECT NOW() as datetime")->row('datetime');
        return $datetime;
    }

    public function guid()
    {
        $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
        return $guid;
    }

    public function index()
    {
        // $this->db->query("UPDATE b2b_summary.einv_main SET total_incl_tax = total_excl_tax WHERE total_excl_tax <> total_incl_tax");
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $setsession = array(
                'frommodule' => 'panda_gr',
            );
            $this->session->set_userdata($setsession);

            if ($_REQUEST['loc'] == '') {
                redirect('login_c/location');
            };

            if (isset($_SESSION['from_other']) == 0) {

                redirect('general/view_status?status=' . $_REQUEST['status'] . '&loc=' . $_REQUEST['loc'] . '&p_f=&p_t=&e_f=&e_t=&r_n=');
            } else {
                //unset($_SESSION['from_other']);
                if ($_REQUEST['status'] == '') {
                    unset($_SESSION['from_other']);
                    redirect('panda_gr?loc=' . $_REQUEST['loc']);
                };
                redirect('general/view_status?status=' . $_REQUEST['status'] . '&loc=' . $_REQUEST['loc'] . '&p_f=&p_t=&e_f=&e_t=&r_n=');
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    /*
    to prevent user from cincai key in the refno based on url, 
    remember to join all back to user guid so that when they key by refno, it will check if the user is valid to query or not then will show result or not..
    */

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
            //$from_module = $_SESSION['frommodule'];
            if(isset($_SESSION['frommodule']))
            {
                $from_module = $_SESSION['frommodule']; 
            }
            else
            {
                $from_module = 'panda_gr'; //fo edi
            }
            $this->panda->get_uri();
            $database_production = 'b2b_summary';

            $grmain_status = $this->db->query("SELECT status from $database_production.grmain where refno = '$refno'")->row('status');
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

                $get_header_detail = $this->db->query("SELECT a.`customer_guid`
                , a.`status`
                , a.`RefNo`
                , a.`Location`
                , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
                , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
                , a.`InvNo` as ori_inv_no
                , a.`DocDate` AS DocDate
                , a.`GRDate`
                , a.`Code`
                , a.`Name`
                , a.`consign`
                , a.Total
                , a.gst_tax_sum
                , a.total_include_tax
                , IF(c.einvno IS NOT NULL,c.einvno,IF(b.invno IS NULL,a.invno,b.invno)) as einvno
                , IF(c.inv_date IS NOT NULL,c.inv_date,IF(b.docdate IS NULL,CURDATE(),b.docdate)) as einv_date
                , subtotal1
                , cross_ref
                , IFNULL((a.total_include_tax - SUM(d.`VarianceAmt`)),a.Total) AS after_amount
                , a.rounding_adj
                , a.duedate
                , a.term
                FROM b2b_summary.grmain AS a 
                LEFT JOIN b2b_summary.grmain_proposed AS b 
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid 
                LEFT JOIN b2b_summary.einv_main c ON a.refno = c.refno AND a.customer_guid = c.customer_guid 
                LEFT JOIN b2b_summary.`grmain_dncn` d ON a.`RefNo` = d.`RefNo` AND a.`customer_guid` = d.`customer_guid`
                where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "'
                GROUP BY a.refno");

                //-- , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
                //                -- , IF(b.created_at IS NULL, a.`DocDate`, DATE_FORMAT(b.created_at,'%Y-%m-%d')) AS DocDate
                //child data from rest
                $H_gr_date = $get_header_detail->row('GRDate');

                $json_upload_data = $this->db->query("SELECT IF('$H_gr_date' >= `start_date`, 'Yes', 'No') AS valid_upload FROM b2b_summary.b2b_json_upload WHERE customer_guid = '$customer_guid' AND document_type = 'grmain' AND active = '1'")->row('valid_upload');

                if($json_upload_data == 'Yes')
                {
                    $get_grn_child_data = $this->db->query("SELECT a.`gr_json_info`
                    FROM b2b_summary.`grmain_info` AS a
                    WHERE a.`refno` = '$refno'
                    AND a.`customer_guid`='$customer_guid'")->row('gr_json_info');
                
                    if (count(json_decode($get_grn_child_data, true)['grchild']) > 0) {
                        // print_r(json_decode($get_grn_child_data, true)['grchild']); die;
                        //$json_data = json_decode($get_grn_child_data, true)['grchild'];
                        $child_result_validation = count(json_decode($get_grn_child_data, true)['grchild']);
                        foreach (json_decode($get_grn_child_data, true)['grchild'] as $json)
                        {
                            $itemcode = $json['Itemcode'];
                            $line = $json['Line'];
                            $barcode = $json['barcode'];
                            $description = $json['Description'];
                            $packsize = $json['PackSize'];
                            $qty = $json['Qty'];
                            $uom = $json['UM'];
                            $unitprice = number_format($json['UnitPrice'], 4);
                            $disc_desc = $json['Disc2Type'] . number_format($json['Disc2Value'], 2);
                            $discamt = $json['DiscAmt'];
                            $unit_disc_prorate = ($json['hcost_gr'] == 0) ? number_format($json['hcost_gr'], 4) : number_format($json['hcost_gr'] / $json['Qty'], 4);
                            $unit_price_bfr_tax = ($json['hcost_gr'] == 0) ? number_format($json['NetUnitPrice'], 4) : number_format($json['TotalPrice'] - $json['hcost_gr'] / $json['Qty'], 4);
                            $totalprice = $json['TotalPrice'];
                            $gst_tax_amount = number_format($json['gst_tax_amount'], 4);
                            $gst_unit_total = number_format((($json['TotalPrice'] - number_format($json['hcost_gr'], 2)) + $json['gst_tax_amount']), 2, '.', '' );
                            
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
                        $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
                        $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
                        // echo $to_shoot_url ;die;
                        $ch = curl_init($to_shoot_url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        $response = curl_exec($ch);
                        // echo var_dump($response);die;
                        // making sure got header baru show child to gen inv
                        if ($response !== false && $get_header_detail->num_rows() > 0) {
                            // echo $to_shoot_url;die;
                            $get_child_detail = json_decode($response, true);
                            // $get_child_detail = json_decode(file_get_contents($to_shoot_url), true);
                            // print_r($get_child_detail);die;
                            $child_result_validation = $get_child_detail[0]['line'];
                            // print_r($child_result_validation);die;
        
                            if ($child_result_validation == 'No Records Found') {
                                $get_child_detail = array();
                                $child_result_validation = '0';
                                $this->session->set_flashdata('warning', 'Connection failed at customer server.Generation of E Invoice is currently not available. Please refresh this page.');
                            } else {
                                $get_child_detail = json_decode($response, true);
                                $child_result_validation = $get_child_detail[0]['line'];
                                // print_r($child_result_validation);die;
                            }
                        } else {
                            $get_child_detail = array();
                            $child_result_validation = '0';
                            $this->session->set_flashdata('warning', 'Connection failed at customer server.Generation of E Invoice is currently not available.');
                            //$this->session->set_flashdata('warning', 'Connection fail at customer server. Child Data Not Found.'); 
                        }
                    }
                }
                else
                {
                    $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
                    $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
                    // echo $to_shoot_url ;die;
                    $ch = curl_init($to_shoot_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    $response = curl_exec($ch);
                    // echo var_dump($response);die;
                    // making sure got header baru show child to gen inv
                    if ($response !== false && $get_header_detail->num_rows() > 0) {
                        // echo $to_shoot_url;die;
                        $get_child_detail = json_decode($response, true);
                        // $get_child_detail = json_decode(file_get_contents($to_shoot_url), true);
                        // print_r($get_child_detail);die;
                        $child_result_validation = $get_child_detail[0]['line'];
                        // print_r($child_result_validation);die;

                        if ($child_result_validation == 'No Records Found') {
                            $get_child_detail = array();
                            $child_result_validation = '0';
                            $this->session->set_flashdata('warning', 'Connection failed at customer server.Generation of E Invoice is currently not available. Please refresh this page.');
                        } else {
                            $get_child_detail = json_decode($response, true);
                            $child_result_validation = $get_child_detail[0]['line'];
                            // print_r($child_result_validation);die;
                        }
                    } else {
                        $get_child_detail = array();
                        $child_result_validation = '0';
                        $this->session->set_flashdata('warning', 'Connection failed at customer server.Generation of E Invoice is currently not available.');
                        //$this->session->set_flashdata('warning', 'Connection fail at customer server. Child Data Not Found.'); 
                    }
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
                $check_supplier_guid = $this->db->query("SELECT b.supplier_group_name,b.`supplier_guid` FROM b2b_summary.grmain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
                // echo $this->db->last_query();die;
                $target_dir = "retailer_file/" . $customer_guid . "/grn_cn/" . $check_supplier_guid->row('supplier_guid') . "/" . $refno . '.pdf';

                $set_row = $this->db->query("SET @row=0");
                $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row := @row + 1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending') AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type, IF(ext_doc1 IS NULL,IF(e.ext_sup_cn_no IS NULL,a.sup_cn_no,e.ext_sup_cn_no),ext_doc1) as ext_doc1, IFNULL(ext_date1, CURDATE()) AS ext_date1, IFNULL(b.posted, '0') AS posted, a.status, a.location, a.RefNo, a.VarianceAmt, a.Created_at, a.Created_by, a.Updated_at, a.Updated_by, a.hq_update, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.transtype, a.share_cost, a.gst_tax_sum, a.gst_adjust, a.gl_code, a.tax_invoice, a.ap_sup_code, a.refno2, a.rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc, c.upload_cn_setting, (SELECT file_path FROM b2b_summary.`upload_doc_log` WHERE customer_guid = '$customer_guid' AND refno = CONCAT(a.refno,'-',a.transtype) ORDER BY created_at DESC LIMIT 1) AS file_path,(SELECT supplier_guid FROM b2b_summary.`upload_doc_log` WHERE customer_guid = '$customer_guid' AND refno = CONCAT(a.refno,'-',a.transtype) ORDER BY created_at DESC LIMIT 1) AS file_supplier_guid,CONCAT(a.refno,'-',a.transtype) as file_refno FROM b2b_summary.grmain_dncn AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '$customer_guid' AND refno = '$refno') AS b ON a.refno = b.refno AND a.transtype = b.type LEFT JOIN lite_b2b.acc_settings c ON a.customer_guid = c.customer_guid LEFT JOIN b2b_summary.`upload_doc_log` d ON c.customer_guid = d.customer_guid AND CONCAT(a.refno, '-', a.transtype) = d.refno LEFT JOIN b2b_summary.grmain_dncn_proposed e ON a.refno = e.refno AND a.transtype = e.trans_type AND a.customer_guid = e.customer_guid WHERE a.refno = '$refno' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' ORDER BY transtype ASC");
                // $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row:=@row+1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending' ) AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type,   ext_doc1 , ifnull(ext_date1, curdate()) as ext_date1,   IFNULL(b.posted, '0') as posted, a.status, a.location, a.RefNo, a.VarianceAmt, a.Created_at, a.Created_by, a.Updated_at, a.Updated_by, a.hq_update, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.transtype, a.share_cost, a.gst_tax_sum, a.gst_adjust, a.gl_code, a.tax_invoice, a.ap_sup_code, a.refno2, a.rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc,c.upload_cn_setting FROM b2b_summary.grmain_dncn AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' ) AS b ON a.refno = b.refno AND a.transtype = b.type LEFT JOIN lite_b2b.acc_settings c ON a.customer_guid = c.customer_guid WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' order by transtype asc");  
                // echo $this->db->last_query();die;

                if (file_exists($target_dir)) {
                    $exists_upload_grn_cn_file = 1;
                } else {
                    $exists_upload_grn_cn_file = 0;
                }
                // echo $exists_upload_grn_cn_file;die;
            } else {
                $set_row = $this->db->query("SET @row=0");
                $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row:=@row+1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending' ) AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type, IF(ext_doc1 IS NULL,IF(d.ext_sup_cn_no IS NULL,a.sup_cn_no,d.ext_sup_cn_no),ext_doc1) as ext_doc1 , ifnull(ext_date1, curdate()) as ext_date1,   IFNULL(b.posted, '0') as posted, a.status, a.location, a.RefNo, a.VarianceAmt, a.Created_at, a.Created_by, a.Updated_at, a.Updated_by, a.hq_update, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.transtype, a.share_cost, a.gst_tax_sum, a.gst_adjust, a.gl_code, a.tax_invoice, a.ap_sup_code, a.refno2, a.rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc,c.upload_cn_setting,'#' as file_path FROM b2b_summary.grmain_dncn AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' AND refno = '$refno' ) AS b ON a.refno = b.refno AND a.transtype = b.type LEFT JOIN lite_b2b.acc_settings c ON a.customer_guid = c.customer_guid LEFT JOIN b2b_summary.grmain_dncn_proposed d ON a.refno = d.refno AND a.transtype = d.trans_type AND a.customer_guid = d.customer_guid WHERE a.refno = '$refno' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' order by transtype asc");
                $check_upload_grn_cn = 0;
                $exists_upload_grn_cn_file = 1;
            }
            // $set_row = $this->db->query("SET @row=0");
            // $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row:=@row+1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending' ) AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type,   ext_doc1 , ifnull(ext_date1, curdate()) as ext_date1,   IFNULL(b.posted, '0') as posted, a.status, a.location, a.RefNo, a.VarianceAmt, a.Created_at, a.Created_by, a.Updated_at, a.Updated_by, a.hq_update, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.transtype, a.share_cost, a.gst_tax_sum, a.gst_adjust, a.gl_code, a.tax_invoice, a.ap_sup_code, a.refno2, a.rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc,c.upload_cn_setting FROM b2b_summary.grmain_dncn AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' ) AS b ON a.refno = b.refno AND a.transtype = b.type LEFT JOIN lite_b2b.acc_settings c ON a.customer_guid = c.customer_guid WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' order by transtype asc");

            // echo $this->db->last_query();die; 
            // $check_ecn_main = $this->db->query("SELECT * FROM b2b_summary.ecn_main WHERE refno = '$refno' AND customer_guid = '".$_SESSION['customer_guid']."'");
            $check_ecn_main = $this->db->query("SELECT a.*, COUNT(a.refno) AS first_count, (SELECT COUNT(refno) AS scount FROM b2b_summary.`ecn_main` WHERE refno = '$refno' AND customer_guid = '" . $_SESSION['customer_guid'] . "') AS second_count FROM b2b_summary.grmain_dncn a WHERE a.refno = '$refno' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' HAVING second_count = first_count");
            // echo $this->db->last_query();die;

            //echo $this->db->last_query();die;
            //$check_e_cn = $this->db->query("SELECT * from ecn_main where customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno'");      

            $check_scode = $this->db->query("SELECT code from b2b_summary.grmain where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('code');
            $check_scode = str_replace("/", "+-+", $check_scode);

            $check_scode = str_replace("/", "+-+", $check_scode);

            if (isset($_REQUEST['fmodule'])) {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_gr'");
            } else {
                $parameter = $this->db->query("SELECT * from menu where module_link = '$from_module'");
            }
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '$from_module'")->row('query');

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


            $check_status = $this->db->query("SELECT grdate,refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");
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
                $edit_header_url = site_url('panda_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc']);
            } else {
                $hidden_text = 'hidden';
                $edit_header_url = site_url('panda_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc'] . '&edit');
            }

            if ($get_DN_detail->num_rows() == '1') {
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
                $check_supplier_guid = $this->db->query("SELECT b.supplier_group_name,b.`supplier_guid` FROM b2b_summary.grmain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
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

            $check_inv_no = $get_header_detail->row('InvNo');
            $check_code = $get_header_detail->row('Code');

            $get_sucpus_code = $this->db->query("SELECT b.* FROM b2b_summary.supcus a LEFT JOIN b2b_summary.`supcus` b ON a.`AccountCode` = b.`AccountCode` AND a.`customer_guid` = b.customer_guid WHERE a.code = '$check_code' AND a.customer_guid = '$customer_guid' GROUP BY b.`customer_guid`,b.code");

            $code_supcus = '';
            foreach ($get_sucpus_code->result() as $row) {
                $get_code .= "'" . $row->Code . "',";
            }
            $code_supcus = rtrim($get_code, ',');
            
            if($code_supcus == '' || $code_supcus == 'null' || $code_supcus == null)
            {
                $code_supcus = "'".$check_code."'";

                $log_refno = $refno.'_'.$check_code;

                $error_msg = 'Supcus No Code '.$log_refno;

                $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR-CODE','$error_msg',NOW(),'$user_guid')");
            }

            $check_inv_no_1 = $this->db->query("SELECT refno FROM b2b_summary.grmain WHERE customer_guid = '$customer_guid' AND invno = '$check_inv_no' AND `code` IN ($code_supcus)");

            $check_inv_no_2 = $this->db->query("SELECT a.refno FROM b2b_summary.einv_main a INNER JOIN b2b_summary.grmain b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.refno != '$refno' AND a.customer_guid = '$customer_guid' AND a.einvno = '$check_inv_no' AND b.code IN ($code_supcus)");

            // echo $this->db->last_query();die;

            if ($check_inv_no_1->num_rows() > 1) {
                $this->session->set_flashdata('warning', 'Please check duplicate Supplier Invoice Number.');
            }
            else if($check_inv_no_2->num_rows() > 0)
            {
                $this->session->set_flashdata('warning', 'Please check duplicate Supplier Invoice Number has generated.');
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
            );



            $this->load->view('header');
            $this->load->view('gr/panda_gr_pdf', $data);
            $this->load->view('general_modal', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function gr_download_child()
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

                $get_header_detail = $this->db->query("SELECT a.`customer_guid`
                , a.`status`
                , a.`RefNo`
                , a.`Location`
                , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
                , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
                , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
                , a.`GRDate`
                , a.`Code`
                , a.`Name`
                , a.`consign`
                , a.Total
                , a.gst_tax_sum
                , a.total_include_tax
                FROM b2b_summary.grmain AS a 
                LEFT JOIN b2b_summary.grmain_proposed AS b 
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '" . $_SESSION['customer_guid'] . "'");

                //child data from rest
                $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
                $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
                //  echo $to_shoot_url ;die;
                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                $response = curl_exec($ch);
                //echo var_dump($response);die;
                // making sure got header baru show child to gen inv
                if ($response !== false && $get_header_detail->num_rows() > 0) {
                    $get_child_detail = json_decode($response, true);
                    $child_result_validation = $get_child_detail[0]['line'];

                    if ($child_result_validation == 'No Records Found') {
                        $get_child_detail = array();
                        $child_result_validation = '0';
                        // $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E Invoice is currently not available.');
                    } else {
                        $get_child_detail = json_decode($response, true);
                        $child_result_validation = $get_child_detail[0]['line'];
                    }
                } else {
                    $get_child_detail = array();
                    $child_result_validation = '0';
                    // $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E Invoice is currently not available.');
                    //$this->session->set_flashdata('warning', 'Connection fail at customer server. Child Data Not Found.'); 
                }
                // child data
                // echo var_dump($get_child_detail); 
            } else {
                echo 'status <> ""';
            }

            $set_row = $this->db->query("SET @row=0");
            $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row:=@row+1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending' ) AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type,   ext_doc1 , ifnull(ext_date1, curdate()) as ext_date1,   IFNULL(b.posted, '0') as posted, a.status, a.location, a.RefNo, a.VarianceAmt, a.Created_at, a.Created_by, a.Updated_at, a.Updated_by, a.hq_update, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.transtype, a.share_cost, a.gst_tax_sum, a.gst_adjust, a.gl_code, a.tax_invoice, a.ap_sup_code, a.refno2, a.rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc FROM b2b_summary.grmain_dncn AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' AND refno = '$refno' ) AS b ON a.refno = b.refno AND a.transtype = b.type WHERE a.refno = '$refno' AND a.customer_guid = '" . $_SESSION['customer_guid'] . "' order by transtype asc");

            //echo $this->db->last_query();die;
            //$check_e_cn = $this->db->query("SELECT * from ecn_main where customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno'");      

            $check_scode = $this->db->query("SELECT code from b2b_summary.grmain where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('code');
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

            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid, 'web', 'general_doc', 'main_path', 'GDMP');
            // $test = $this->file_config_b2b->file_path();die;
            // echo $test;die;
            $filename = $file_config_final_path . '/' . $replace_var . '.pdf';
            //echo $replace_var;die;
            $file_headers = @get_headers($filename);

            // find invoice folder in client folder

            if (!file_exists("." . $virtual_path . "/invoice")) {
                $oldmask = umask(0);
                mkdir("." . $virtual_path . "/invoice", 0777, true);
                umask($oldmask);
            };


            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");
            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'GRN' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");
            // check if einv has open
            $check_e_inv = $this->db->query("SELECT * from b2b_summary.einv_main where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");

            if ($check_e_inv->num_rows() == '1') {
                $open_panel2 = 'collapsed-box';
                $open_panel3 = 0;
                $version = $check_e_inv->row('revision');
                $check_e_inv_c = $this->db->query("SELECT * from b2b_summary.einv_child where einv_guid = '" . $check_e_inv->row('einv_guid') . "'");
                $check_einv_filepath = base_url($virtual_path . '/invoice/' . 'B2B_' . $_REQUEST['trans'] . '.pdf');
            } else {
                $open_panel2 = '';
                $open_panel3 = 1;
                $version = '0';
                $check_e_inv_c = '';
                $check_einv_filepath = '';
            };

            if (isset($_REQUEST['edit'])) {
                $hidden_text = 'text';
                $edit_header_url = site_url('panda_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc']);
            } else {
                $hidden_text = 'hidden';
                $edit_header_url = site_url('panda_gr/gr_child?trans=' . $_REQUEST['trans'] . '&loc=' . $_REQUEST['loc'] . '&edit');
            }

            if ($get_DN_detail->num_rows() == '1') {
                $show_generate_ecn = '2';
                $show_ecn = '1';
                $show_grda_pdf = '1';

                // //grda details
                // $grda_parameter  = $this->db->query("SELECT * from menu where module_link = 'panda_grda'");
                // $grda_type = $grda_parameter->row('type');
                // $grda_code = $check_scode;

                // $grda_replace_var  = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$grda_type'), 'code', '$grda_code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_grda'")->row('query');

                // $grda_virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

                // $grda_filename = base_url($grda_virtual_path . '/' . $grda_replace_var . '.pdf');

                // $grda_file_headers = @get_headers($grda_filename);

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
            );



            $this->load->view('header');
            $this->load->view('gr/panda_gr_download_pdf', $data);
            $this->load->view('general_modal', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function direct_print()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];

            $get_header_detail = $this->db->query("SELECT * from b2b_summary.grmain where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");

            $check_scode = $this->db->query("SELECT code from b2b_summary.grmain where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'")->row('code');
            $check_scode = str_replace("/", "+-+", $check_scode);

            $parameter = $this->db->query("SELECT * from menu where module_link = '" . $_SESSION['frommodule'] . "'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '" . $_SESSION['frommodule'] . "'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid, 'web', 'general_doc', 'main_path', 'GDMP');

            $filename = $file_config_final_path . '/' . $replace_var . '.pdf';

            $file_headers = @get_headers($filename);

            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$refno' and customer_guid = '" . $_SESSION['customer_guid'] . "'");
            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'GRN' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Goods Received',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'set_admin_code' => $set_admin_code,
            );

            if (in_array('HTTP/1.1 404 Not Found', $file_headers)) {
                echo "<script>window.close();</script>";
            } else {
                if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                    $this->db->query("UPDATE b2b_summary.grmain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$refno' and status IN ('','viewed') ");

                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'printed_grn'
                    , '$from_module'
                    , '$refno'
                    , now()
                    ");
                }
                redirect($filename);
            }

            /*$this->load->view('header');       
            $this->load->view('po/panda_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');*/
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function direct_print_merge()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $pdf_name = $_REQUEST['pdfname'];
            // echo $refno;die;
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            // $filename = base_url($virtual_path.'/'.$pdf_name.'.pdf');
            // $filename = base_url('merge/'.$pdf_name.'.pdf');
            $path_seperator = $this->file_config_b2b->path_seperator($customer_guid, 'web', 'general_doc', 'path_seperator', 'PS');

            $file_config_final_path = $this->file_config_b2b->merge_print_create_file_path($customer_guid, 'web', 'general_doc', 'merge_print', 'MPMPCP');
            $merge_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'general_doc', 'merge_print', 'MPN');

            $filename = $file_config_final_path . $path_seperator . $merge_path . $path_seperator . $pdf_name . '.pdf';
            // echo $filename;die;
            // $filename = 'http://192.168.10.29/lite_panda_b2b/uploads/tfvalue/merge.pdf';
            // echo $filename;die;

            $file_headers = @get_headers($filename);
            $refno_array = explode(",", $refno);
            // echo $refno;
            // print_r($refno_array);die;
            foreach ($refno_array as $row2) {
                // echo 1;
                $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$row2' and customer_guid = '" . $_SESSION['customer_guid'] . "'");
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

                if (in_array('HTTP/1.1 404 Not Found', $file_headers)) {

                    echo "<script>window.close();</script>";
                } else {
                    if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
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
            if (!file_exists($file)) {
                echo "The file not exists. Please Contact Admin";
                die;
            }
            // die;
            $type = 'inline';
            // $pdf_name = 'merge';
            // echo $pdf_name;die;
            header("Content-type: application/pdf");
            header('Content-Disposition: ' . $type . '; filename="' . $pdf_name . '.pdf"');
            header('Cache-Control: public, must-revalidate, max-age=0');
            // header("Content-Disposition: attachment; filename=\"".$Filename."\"");
            // header("Content-Length: ".filesize($Filename)); 
            ob_clean();
            flush();
            readfile($file);
            die;
            /*$this->load->view('header');       
            $this->load->view('po/panda_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');*/
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function direct_print_merge_post_method()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $refno = $this->input->post('trans');
            $loc = $this->input->post('loc');
            $pdf_name = $this->input->post('pdfname');
            //$refno = $_REQUEST['trans'];
            //$loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            //$pdf_name = $_REQUEST['pdfname'];
            // echo $refno;die;
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');

            // $filename = base_url($virtual_path.'/'.$pdf_name.'.pdf');
            // $filename = base_url('merge/'.$pdf_name.'.pdf');
            $path_seperator = $this->file_config_b2b->path_seperator($customer_guid, 'web', 'general_doc', 'path_seperator', 'PS');

            $file_config_final_path = $this->file_config_b2b->merge_print_create_file_path($customer_guid, 'web', 'general_doc', 'merge_print', 'MPMPCP');
            $merge_path = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'general_doc', 'merge_print', 'MPN');

            $filename = $file_config_final_path . $path_seperator . $merge_path . $path_seperator . $pdf_name . '.pdf';
            // echo $filename;die;
            // $filename = 'http://192.168.10.29/lite_panda_b2b/uploads/tfvalue/merge.pdf';
            // echo $filename;die;

            $file_headers = @get_headers($filename);
            $refno_array = explode(",", $refno);
            // echo $refno;
            // print_r($refno_array);die;
            foreach ($refno_array as $row2) {
                // echo 1;
                $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$row2' and customer_guid = '" . $_SESSION['customer_guid'] . "'");
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

                if (in_array('HTTP/1.1 404 Not Found', $file_headers)) {

                    echo "<script>window.close();</script>";
                } else {
                    if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
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
            if (!file_exists($file)) {
                echo "The file not exists. Please Contact Admin";
                die;
            }
            // die;
            $type = 'inline';
            // $pdf_name = 'merge';
            // echo $pdf_name;die;
            header("Content-type: application/pdf");
            header('Content-Disposition: ' . $type . '; filename="' . $pdf_name . '.pdf"');
            header('Cache-Control: public, must-revalidate, max-age=0');
            // header("Content-Disposition: attachment; filename=\"".$Filename."\"");
            // header("Content-Length: ".filesize($Filename)); 
            ob_clean();
            flush();
            readfile($file);
            die;
            /*$this->load->view('header');       
            $this->load->view('po/panda_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');*/
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
                $check_if_exists_einv2 = $this->db->query("SELECT * FROM b2b_summary.grmain WHERE refno = '$refno' AND customer_guid = '$customer_guid'");
                $check_if_exists_einv2_code = $check_if_exists_einv2->row('Code');
                $check_if_exists_einv2_supcode = $this->db->query("SELECT b.* FROM b2b_summary.supcus a LEFT JOIN b2b_summary.`supcus` b ON a.`AccountCode` = b.`AccountCode` AND a.`customer_guid` = b.customer_guid WHERE a.code = '$check_if_exists_einv2_code' AND a.customer_guid = '$customer_guid' GROUP BY b.`customer_guid`,b.code");
                $check_if_exists_einv2_supcode_string = '';
                foreach ($check_if_exists_einv2_supcode->result() as $row) {
                    $check_if_exists_einv2_supcode_string .= "'" . $row->Code . "',";
                }
                $check_if_exists_einv2_supcode_string2 = rtrim($check_if_exists_einv2_supcode_string, ',');
                echo rtrim($check_if_exists_einv2_supcode_string, ',') . 'sdsd<br>';
                $check_if_exists_einv3 = $this->db->query("SELECT b.* FROM b2b_summary.einv_main a INNER JOIN b2b_summary.grmain b ON a.`customer_guid` = b.`customer_guid` AND a.refno = b.refno WHERE a.refno != '$refno' AND a.customer_guid = '$customer_guid' AND a.invno = '$check_einvno' AND CODE IN($check_if_exists_einv2_supcode_string2)");
                if ($check_if_exists_einv3->num_rows() > 0) {
                    $this->session->set_flashdata('warning',  'Invoice number repeat');
                    redirect('panda_gr/gr_child?trans=' . $refno . '&loc=' . $loc);
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
                redirect('panda_gr/gr_child?trans=' . $refno . '&loc=' . $loc);
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

                $this->db->query("UPDATE b2b_summary.grmain set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");

                $this->db->query("UPDATE b2b_summary.grmain set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");

                $get_einv_guid = $this->db->query("SELECT einv_guid from b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'")->row('einv_guid');

                $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_excl_tax = a.Subtotal1 WHERE a.subtotal1 <> b.total_excl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");

                $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_incl_tax = a.total_include_tax WHERE a.total_include_tax <> b.total_incl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");
            } else {
                $this->session->set_flashdata('warning',  'Invoice Generated, Cannot Regenerate');
                redirect('panda_gr/gr_child?trans=' . $refno . '&loc=' . $loc);

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
            INNER JOIN b2b_summary.grmain AS b
            ON a.customer_guid = b.customer_guid
            AND a.refno = b.refno
            SET
            a.location = b.location
            , a.grdate = b.grdate
            , a.issuestamp = b.issuestamp
            , a.laststamp = b.laststamp
            , a.code = b.code
            , a.name = b.name
            where a.customer_guid = '$customer_guid' and a.refno = '$header_refno' and posted = '0'
             ");

            redirect('panda_gr/gr_child?trans=' . $header_refno . '&loc=' . $header_loc);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function generate_ecn()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('ecn_refno[]');
            $type = $this->input->post('ecn_type[]');
            $ext_doc1 = $this->input->post('ext_doc1[]');
            $ext_date1 = $this->input->post('ext_date1[]');
            $amount = $this->input->post('ecn_varianceamt[]');
            $tax_rate = $this->input->post('ecn_tax_rate[]');
            $tax_amount = $this->input->post('ecn_gst_tax_sum[]');
            $total_incl_tax = $this->input->post('ecn_total_incl_tax[]');
            $loc = $this->input->post('ecn_loc[]');
            // $line = $this->input->post('ecn_rows[]');
            $line = [1];
            $gr_refno = $this->input->post('gr_refno');
            $gr_loc = $this->input->post('gr_loc');
            $ii = $this->input->post('index_no');

            //echo $ext_doc1;die;
            //latest for retrieve invoice number
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];
            $invoice_number = $this->db->query("SELECT invno FROM b2b_summary.einv_main WHERE refno = '$req_refno' AND customer_guid = '$customer_guid' ")->row('invno');
            $con_req_no = $req_refno . '-' . $transtype;

            $check_if_exists_ecn = $this->db->query("SELECT * FROM b2b_summary.ecn_main WHERE CONCAT(refno,'-',type) != '$con_req_no' AND customer_guid = '$customer_guid' AND ext_doc1 = '$ext_doc1[$ii]'");
            // echo $this->db->last_query();die;
            // echo $check_if_exists_ecn->num_rows();die;
            if ($check_if_exists_ecn->num_rows() > 0) {
                // echo 'cn number duplicate';die;
                $check_if_exists_ecn2 = $this->db->query("SELECT * FROM b2b_summary.grmain WHERE refno = '$req_refno' AND customer_guid = '$customer_guid'");
                // echo $this->db->last_query();die;
                $check_if_exists_ecn2_code = $check_if_exists_ecn2->row('Code');
                $check_if_exists_ecn2_supcode = $this->db->query("SELECT b.* FROM b2b_summary.supcus a LEFT JOIN b2b_summary.`supcus` b ON a.`AccountCode` = b.`AccountCode` AND a.`customer_guid` = b.customer_guid WHERE a.code = '$check_if_exists_ecn2_code' AND a.customer_guid = '$customer_guid' GROUP BY b.`customer_guid`,b.code");
                // echo $this->db->last_query();die;
                $check_if_exists_ecn2_supcode_string = '';
                foreach ($check_if_exists_ecn2_supcode->result() as $row) {
                    $check_if_exists_ecn2_supcode_string .= "'" . $row->Code . "',";
                }
                $check_if_exists_ecn2_supcode_string2 = rtrim($check_if_exists_ecn2_supcode_string, ',');
                // echo rtrim($check_if_exists_ecn2_supcode_string,',').'sdsd<br>';die;
                $check_if_exists_ecn3 = $this->db->query("SELECT b.* FROM b2b_summary.ecn_main a INNER JOIN b2b_summary.grmain b ON a.`customer_guid` = b.`customer_guid` AND a.refno = b.refno WHERE CONCAT(a.refno,'-',a.type) != '$con_req_no' AND a.customer_guid = '$customer_guid' AND a.ext_doc1 = '$ext_doc1[$ii]' AND CODE IN($check_if_exists_ecn2_supcode_string2)");
                // echo $this->db->last_query();die;
                if ($check_if_exists_ecn3->num_rows() > 0) {
                    $doc_status = $check_if_exists_ecn2->row('status');
                    if ($doc_status == '') {
                        $doc_status = 'NEW';
                    }
                    $this->session->set_flashdata('warning',  'CN number repeat');
                    // echo '/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT';die;
                    redirect('panda_gr/gr_child?trans=' . $req_refno . '&loc=' . $prdn_loc . '&accpt_gr_status=' . $doc_status);
                }
                // echo $this->db->last_query();die;
            }

            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('rest_url');
            $to_shoot_url = $check_url . "/childdata?table=grdncn" . "&refno=" . $req_refno . "&transtype=" . $transtype;
            // echo $to_shoot_url 
            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            //from here get child, then we need insert child
            if ($response !== false) {
                $get_child_dncn = json_decode(file_get_contents($to_shoot_url), true);
                $child_result_validation = $get_child_dncn[0]['line'];
            } else {
                $get_child_dncn = array();
                $child_result_validation = '0';
                $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E CN is currently not available.');
                redirect('panda_gr/gr_child?trans=' . $header_refno . '&loc=' . $header_loc);
            }


            //echo var_dump($to_shoot_url) ;die;

            foreach ($line as $i => $id) {
                $i = $this->input->post('index_no');

                $check_exist = $this->db->query("SELECT * from b2b_summary.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");

                if ($check_exist->num_rows() > 0) {
                    $revision = $check_exist->row('revision') + 1;
                    $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
                    $this->db->query("DELETE FROM b2b_summary.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
                } else {
                    $revision = '0';
                }

                if (is_null($ext_doc1[$i]) || $ext_doc1[$i] == ' ' || $ext_doc1[$i] == '') {
                    //echo $refno[$i];echo $type[$i];die;
                    unset($refno[$i]);
                    unset($type[$i]);
                    $this->session->set_flashdata('message', 'E-CN ext Doc cannot be null');
                };

                //echo var_dump($ext_doc1[0]);die;
                $ecn_guid = $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid');
                $data1[] = [
                    'customer_guid' => $customer_guid,
                    'ecn_guid' => $ecn_guid,
                    //'status' => '',
                    'refno' => $refno[$i],
                    'type' => $type[$i],
                    'ext_doc1' => str_replace(' ', '', $ext_doc1[$i]),
                    'ext_date1' => $ext_date1[$i],
                    'ecn_generated_date' => $this->db->query("SELECT CURDATE() as curdate")->row('curdate'),
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
            $ecnmain = $this->db->query("SELECT * from b2b_summary.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = '$transtype'");
            if ($ecnmain->num_rows() == 0) {
                $this->db->insert_batch('b2b_summary.ecn_main', $data1);
            };
            $header =  $this->db->query("SELECT * from b2b_summary.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = '$transtype'");
            //$this->General_model->replace_data('ecn_main', $data1);
            $this->db->query("DELETE FROM b2b_summary.ecn_main where refno is null and type is null and customer_guid = '" . $_SESSION['customer_guid'] . "'");
            // /echo var_dump(count($get_child_dncn));die;
            for ($i = 0; $i < count($get_child_dncn); $i++) {
                $data_ecn_child[] = [
                    'customer_guid' => $customer_guid,
                    'child_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    'ecn_guid' => $ecn_guid,
                    'status' => '',
                    'refno' => $get_child_dncn[$i]['refno'],
                    'refno_dn' => $get_child_dncn[$i]['refno_dn'],
                    'transtype' => $get_child_dncn[$i]['transtype'],
                    'location' => $get_child_dncn[$i]['location'],
                    'line' => $get_child_dncn[$i]['line'],
                    'itemcode' => $get_child_dncn[$i]['itemcode'],
                    'barcode' => $get_child_dncn[$i]['barcode'],
                    'description' => $get_child_dncn[$i]['description'],
                    'qty' => $get_child_dncn[$i]['qty'],
                    'inv_qty' => $get_child_dncn[$i]['inv_qty'],
                    'inv_netunitprice' => $get_child_dncn[$i]['inv_netunitprice'],
                    'inv_totalprice' => $get_child_dncn[$i]['inv_totalprice'],
                    'supplier' => $get_child_dncn[$i]['supplier'],
                    'invno' => $get_child_dncn[$i]['invno'],
                    'dono' => $get_child_dncn[$i]['dono'],
                    'porefno' => $get_child_dncn[$i]['porefno'],
                    'title2' => $get_child_dncn[$i]['title2'],
                    'notes' => $get_child_dncn[$i]['notes'],
                    'pounitprice' => $get_child_dncn[$i]['pounitprice'],
                    'invactcost' => $get_child_dncn[$i]['invactcost'],
                    'netunitprice' => $get_child_dncn[$i]['netunitprice'],
                    'pototal' => $get_child_dncn[$i]['pototal'],
                    'articleno' => $get_child_dncn[$i]['articleno'],
                    'packsize' => $get_child_dncn[$i]['packsize'],
                    'variance_amt' => $get_child_dncn[$i]['variance_amt'],
                    'reason' => $get_child_dncn[$i]['reason'],
                    'tax_amount' => $get_child_dncn[$i]['gst_tax_total_1'],
                    'total_gross' => $get_child_dncn[$i]['total_gross'],
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' => $_SESSION['user_guid'],
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $_SESSION['user_guid'],
                ];
            }
            //remove existing data
            $this->db->query("DELETE FROM b2b_summary.ecn_child where refno = '" . $req_refno . "' and transtype = '" . $transtype . "'");

            $ecnchild = $this->db->query("SELECT * from b2b_summary.ecn_child where customer_guid = '$customer_guid' and refno = '$req_refno' and transtype = '$transtype'");
            if ($ecnchild->num_rows() != count($get_child_dncn)) {
                $this->db->insert_batch('b2b_summary.ecn_child', $data_ecn_child);
            };

            $check_existed_child = $this->db->query("SELECT * FROM b2b_summary.ecn_main a LEFT JOIN b2b_summary.ecn_child b ON a.ecn_guid = b.ecn_guid WHERE b.ecn_guid IS NULL AND a.refno = '$req_refno' AND a.type = '$transtype' AND a.customer_guid = '$customer_guid'");

            if ($check_existed_child->num_rows() > 0) {
                $error++;
                $this->db->query("DELETE FROM b2b_summary.ecn_main WHERE refno = '$req_refno' AND type = '$transtype' AND customer_guid = '$customer_guid'");
                $redirect = 'gr_child?trans=' . $req_refno . '&loc=' . $prdn_loc . '&accpt_gr_status=' . $doc_status;
                echo '<script>alert("Itemline not exist, please contact admin");window.location.href = "' . $redirect . '";</script>';
                die;
                // echo '/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT';die;
            }
            // $this->db->insert_batch('ecn_child', $data_ecn_child);
            /*$get_ecn_child_data = $this->db->query("SELECT b.* from lite_b2b.ecn_main as a inner join lite_b2b.ecn_child as b on a.refno = b.refno where a.customer_guid = '$customer_guid' and a.refno = '$req_refno' and transtype = '$transtype'");*/
            $get_ecn_child_data = $this->db->query("SELECT  * FROM  b2b_summary.ecn_child  WHERE customer_guid = '$customer_guid'  AND refno = '$req_refno'  AND transtype = '$transtype' ");

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');
            $invoice_number = $_REQUEST['refno'] . '_' . $_REQUEST['transtype'];
            // echo  $_REQUEST['refno'];die;
            $gr_info = $this->db->query("SELECT 
            a.`loc_group` as Location
            , a.`Code`
            , a.`Name`
            , ifnull(b.invno,a.`Invno`) as Invno
            FROM b2b_summary.grmain AS a 
            LEFT JOIN b2b_summary.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid where a.refno = '$req_refno' 
            and a.customer_guid = '$customer_guid'");

            $data = array(
                'query_data' =>  $this->db->query("SELECT a.refno, a.status, a.type, a.ext_doc1, a.ext_date1, a.amount, a.`tax_rate`, a.`tax_amount`, a.`total_incl_tax`, a.posted, b.refno_dn, b.transtype, b.location, b.itemcode, b.barcode, b.description, b.qty, b.inv_qty, b.inv_netunitprice, b.supplier, b.invno, b.dono, b.porefno, b.title2, b.notes, b.pounitprice, b.invactcost, b.netunitprice, b.pototal, b.articleno, b.packsize, b.variance_amt, b.reason, b.tax_amount, b.total_gross, IFNULL(CONCAT('[',c.invno,']'), '' ) AS new_invno, IFNULL(CONCAT('[',c.dono,']'), '' ) AS new_dono, IFNULL(CONCAT('[',c.docdate,']'), '' ) AS new_docdate, IFNULL(CONCAT('[',c.grdate,']'), '' ) AS new_grdate FROM b2b_summary.ecn_main AS a LEFT JOIN b2b_summary.`grmain_proposed` AS c ON a.customer_guid = c.`customer_guid` AND a.refno = c.refno INNER JOIN b2b_summary.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = '$transtype' "),
                'supcus_supplier' => $this->db->query("SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info->row('Code') . "' and customer_guid = '$customer_guid'"),
                'supcus_customer' => $this->db->query("SELECT * from b2b_summary.cp_set_branch where branch_code = '" . $gr_info->row('Location') . "' and customer_guid = '$customer_guid'"),
                'customer_branch_info' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '" . $gr_info->row('Location') . "'   and customer_guid = '$customer_guid'"),
            );

            //echo var_dump($data['query_data']->result());die;
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '" . $_SESSION['customer_guid'] . "'")->row('file_path');
            //echo var_dump($data['query_data']->result());die;
            $from_module = $_SESSION['frommodule'];
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $req_refno;
            $user_guid = $_SESSION['user_guid'];

            if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {

                // $check_ecn_main = $this->db->query("SELECT a.*, COUNT(a.refno) AS first_count, (SELECT COUNT(refno) AS scount FROM b2b_summary.`ecn_main` WHERE refno = '$refno' AND customer_guid = '".$_SESSION['customer_guid']."') AS second_count FROM b2b_summary.grmain_dncn a WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' HAVING second_count = first_count"); 
                // // echo $this->db->last_query();die;    
                // if($check_ecn_main->num_rows() > 0)
                // {         
                // echo 1;die;  
                $this->db->query("UPDATE b2b_summary.grmain_dncn set status = 'Ecn Generated' where customer_guid = '" . $_SESSION['customer_guid'] . "' and refno = '$refno' AND transtype = '$transtype' ");
                // $this->db->query("UPDATE b2b_summary.grmain set status = 'EINV_GENERATED' where status = 'viewed' and customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno' ");
                // }

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'generate_ecn'
                , '$from_module'
                , '" . $refno . "-" . $transtype . "'
                , now()
                ");
            };

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


            // if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
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

            ob_end_clean();
            $pdf->Output($req_refno . $transtype, 'I');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function view_ecn()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login()) {
            $this->panda->get_uri();
            $customer_guid = $_SESSION['customer_guid'];
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];
            $invoice_number = $_REQUEST['refno'] . '_' . $_REQUEST['transtype'];

            if($customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93' )
            {
                $within_date = '3';
            }
            else
            {
                $within_date = '7';
            }
            // echo $invoice_number;die;
            $gr_info = $this->db->query("SELECT 
                a.`loc_group` as Location
                , a.`Code`
                , a.`Name`
                , ifnull(b.invno,a.`Invno`) as Invno
                FROM b2b_summary.grmain AS a 
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
                'within_date' => $within_date,
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


    /*
    public function gr_child_old()
    {
        if($this->session->userdata('loginuser') == true)
        { 
            $grmain = $this->db->query("SELECT refno, grdate, docdate, dono, invno, location, tax_code_purchase,gst_tax_rate,total, gst_tax_sum, total_include_tax, status from grmain where refno = '".$_REQUEST['trans']."'");

            foreach($grmain->result() as $row)
            {
                $data2  = array(
                  'refno' => $row->refno,
                  'grdate' => $row->grdate,
                  'docdate' => $row->docdate,
                  'dono' => $row->dono,
                  'invno' => $row->invno,
                  'location' => $row->location,
                  'tax_code_purchase' => $row->tax_code_purchase,
                  'gst_tax_rate' => $row->gst_tax_rate,
                  'total' => $row->total,
                  'gst_tax_sum' => $row->gst_tax_sum,
                  'total_include_tax' => $row->total_include_tax,
                  'status' => $row->status
                    );
                $this->session->set_userdata($data2);
            }
            

            $data = array (
                'grmain' => $this->db->query("SELECT refno, grdate, docdate, dono, invno, location, tax_code_purchase,gst_tax_rate,total, gst_tax_sum, total_include_tax, status from grmain where refno = '".$_REQUEST['trans']."' "),
                'grchild' => $this->db->query("SELECT  * from grchild where refno = '".$_REQUEST['trans']."' order by line asc "),
                'child_reject' => $this->db->query("SELECT reason from set_setting where module_name = 'GR'"),
                );
             $this->load->view('header'); 
            $this->load->view('gr/panda_gr_child',$data);
            $this->load->view('gr/panda_gr_modal', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }*/

    public function confirm()
    {
        if ($this->session->userdata('loginuser') == true) {
            $refno = $_REQUEST['trans'];
            $this->panda->get_uri();
            $check_cur_rec = $this->db->query("SELECT status from grmain where refno = '$refno'");
            if ($check_cur_rec->row('status') == 'Pending') {


                $table = "grmain";
                $data = array(
                    'status' => 'GR Confirmed',
                );
                $this->GR_model->update_accepted($table, $data);
                $this->session->set_flashdata('message', 'Document Confirmed.');
                redirect('panda_gr/gr_child?trans=' . $_REQUEST['trans']);
            } else {
                $this->session->set_flashdata('warning', 'Document status is not Pending. Please make sure GR status is Pending before making any changes.');
                redirect('panda_gr/gr_child?trans=' . $_REQUEST['trans']);
            }
        } else {
            redirect('#');
        }
    }

    public function check_accept()
    {
        if ($this->session->userdata('loginuser') == true) {
            $session_data = $this->session->userdata('loginuser');
            $user_guid = $data['user_guid'] = $session_data['user_guid'];
            $session_data = $this->session->userdata('branch');
            $branch_code = $data['branch_code'] = $session_data['branch_code'];
            $session_data = $this->session->userdata('customers');
            $customer = $data['customer'] = $session_data['customer'];
            $customer_guid = $data['customer_guid'] = $session_data['customer_guid'];

            $_SESSION['refno'] = $this->input->post("refno");

            $reason = $this->input->post('reason[]');
            $line = $this->input->post('line[]');
            $itemcode = $this->input->post('itemcode');

            $grchild = array();
            foreach ($line as $row => $id) {
                $grchild[] = [
                    'line' => $id,
                    'reason' => $reason[$row],
                ];
            }


            $table = $session_data['customer_db'] . ".grmain";
            $dbchild = $session_data['customer_db'] . ".grchild";

            $check_status = $this->db->query("SELECT status from $table where refno  = '" . $_SESSION['refno'] . "'");
            /*echo var_dump($_SESSION);
                echo $this->db->last_query();die;*/
            if ($check_status->row('status') == 'Pending') {

                $data = array(
                    'status' => 'Accepted',
                );
                $this->GR_model->update_accepted($table, $data);
                $this->db->where_in('refno', $_SESSION['refno']);
                $this->db->update_batch($dbchild, $grchild, 'line');
                // echo $this->db->last_query();die;

                $check_child = $this->db->query("SELECT REPLACE(GROUP_CONCAT(reason), ',', '')  as reason from $dbchild where refno = '" . $_SESSION['refno'] . "' group by refno");
                if ($check_child->row('reason') != '') {
                    $p_accepted = array(
                        'status' => 'Partially Accepted',
                    );
                    $this->Po_model->update_accepted($table, $p_accepted);
                    // echo $this->db->last_query();die;
                    $this->session->set_flashdata('message', 'GR is Partially Accepted.');
                    redirect('panda_gr/gr_child?trans=' . $_SESSION['refno']);
                } else {
                    $this->session->set_flashdata('message', 'GR Accepted.');
                    redirect('panda_gr/gr_child?trans=' . $_SESSION['refno']);
                };
                //echo $this->db->last_query();die;
            } else {
                $this->session->set_flashdata('message', 'Document status is not Pending. Please make sure GR status is Pending before making any changes.');
                redirect('panda_gr/gr_child?trans=' . $_SESSION['refno']);
            };
        } else {
            redirect('#');
        }
    }











    //************************************************************************************
    //    OLD FUNCTIONS BELOW THIS POINT     888888888888888888888888888888888888888888888
    //************************************************************************************
    //pagination settings
    public function index2()
    {

        //************************************************************************************
        //     BEGIN# STANDARD     88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************
        //pagination settings
        $config['base_url'] = site_url('Panda_gr/index');
        $config['total_rows'] = $this->GR_model->countGR();
        $config['per_page'] = "10";
        $config["uri_segment"] = 3;
        $choice = 10; //$config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = true;
        $config['last_link'] = true;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        //************************************************************************************
        //     END# STANDARD       88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************


        //Begin# Define Field Type and Title using multi-array
        $DataType = array();

        $DataType[0]['title'] = 'GR refno';
        $DataType[0]['align'] = 'left';
        $DataType[0]['fieldtype'] = 'string';
        $DataType[0]['decimal'] = 0;

        $DataType[1]['title'] = 'GR Date';
        $DataType[1]['align'] = 'left';
        $DataType[1]['fieldtype'] = 'string';
        $DataType[1]['decimal'] = 0;

        $DataType[2]['title'] = 'Invoice Date';
        $DataType[2]['align'] = 'left';
        $DataType[2]['fieldtype'] = 'string';
        $DataType[2]['decimal'] = 0;

        $DataType[3]['title'] = 'Doc. No';
        $DataType[3]['align'] = 'left';
        $DataType[3]['fieldtype'] = 'string';
        $DataType[3]['decimal'] = 0;

        $DataType[4]['title'] = 'Inv. No';
        $DataType[4]['align'] = 'left';
        $DataType[4]['fieldtype'] = 'string';
        $DataType[4]['decimal'] = 0;

        $DataType[5]['title'] = 'Location';
        $DataType[5]['align'] = 'center';
        $DataType[5]['fieldtype'] = 'string';
        $DataType[5]['decimal'] = 0;

        $DataType[6]['title'] = 'Tax Code';
        $DataType[6]['align'] = 'center';
        $DataType[6]['fieldtype'] = 'string';
        $DataType[6]['decimal'] = 0;

        $DataType[7]['title'] = 'Tax Rate';
        $DataType[7]['align'] = 'center';
        $DataType[7]['fieldtype'] = 'number';
        $DataType[7]['decimal'] = 2;

        $DataType[8]['title'] = 'Total (exc tax)';
        $DataType[8]['align'] = 'left';
        $DataType[8]['fieldtype'] = 'number';
        $DataType[8]['decimal'] = 2;

        $DataType[9]['title'] = 'GST';
        $DataType[9]['align'] = 'center';
        $DataType[9]['fieldtype'] = 'number';
        $DataType[9]['decimal'] = 2;

        $DataType[10]['title'] = 'Total (inc tax)';
        $DataType[10]['align'] = 'center';
        $DataType[10]['fieldtype'] = 'string';
        $DataType[10]['decimal'] = 2;

        $DataType[11]['title'] = 'Status';
        $DataType[11]['align'] = 'center';
        $DataType[11]['fieldtype'] = 'string';
        $DataType[11]['decimal'] = 2;


        $data['DataType'] = $DataType;
        //End# Define Field Type and Title using multi-array

        //Begin# Define View,Edit,Delete button 
        $ButtonLink = array();
        $ButtonLink[0]['Href'] = "index.php/panda_gr/gr_details/";
        $ButtonLink[0]['FieldNo'] = 0;

        $data['ButtonLink'] = $ButtonLink;
        //End# Define View,Edit,Delete button 

        //$ButtonAddVisible = false;        
        //$data['ButtonAddVisible'] = $ButtonAddVisible;        
        $ButtonViewVisible = true;
        $data['ButtonViewVisible'] = $ButtonViewVisible;
        $ButtonEditVisible = false;
        $data['ButtonEditVisible'] = $ButtonEditVisible;
        $ButtonDeleteVisible = false;
        $data['ButtonDeleteVisible'] = $ButtonDeleteVisible;
        $ButtonDownloadVisible = false;
        $data['ButtonDownloadVisible'] = $ButtonDownloadVisible;
        $ButtonBackVisible = true;
        $data['ButtonBackVisible'] = $ButtonBackVisible;


        //capture customer/branch selected
        $session_data = $this->session->userdata('loginuser');
        $data['user_guid'] = $session_data['user_guid'];
        $session_data = $this->session->userdata('branch');
        $data['branch_code'] = $session_data['branch_code'];
        $session_data = $this->session->userdata('customers');
        $data['customer'] = $session_data['customer'];

        //Destroyed Session
        $this->session->unset_userdata('po_status');

        //call the model function to get the department data
        $getQueryResult = $this->GR_model->get_gr_list(
            'select a.RefNo,a.grdate,a.docdate,a.dono,a.invno,a.Location,a.tax_code_purchase,a.gst_tax_rate,a.Total,
             a.gst_tax_sum,a.total_include_tax,a.status FROM grmain a 
             INNER JOIN customer_profile b 
             ON a.customer_guid = b.customer_guid 
             INNER JOIN customer_supcus c
             ON b.customer_guid = c.customer_guid AND a.code = c.code
             INNER JOIN user_customer d
             ON c.supcus_guid = d.supcus_guid
             WHERE ibt = 0 AND a.loc_group = "' . $data['branch_code'] . '" AND b.customer_name = "' . $data['customer'] . '"
             AND d.user_guid = "' . $data['user_guid'] . '"
             order by GrDate desc',
            $config["per_page"],
            $data['page']
        );

        //Break the array to get the 2nd level array size
        $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[0]));
        $flat_arr = iterator_to_array($recur_flat_arr_obj, false);
        $ColumnCount = count($flat_arr);

        //Begin# Create 2 Dimensional Array so that it will not depend on query field name
        $DataArray = array();

        for ($i = 0; $i < count($getQueryResult); ++$i) {
            $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[$i]));
            $flat_arr = iterator_to_array($recur_flat_arr_obj, false);


            for ($j = 0; $j < $ColumnCount; ++$j) {
                $DataArray[$i][] = $flat_arr[$j];
            }
        }
        //End# Create 2 Dimensional Array

        $data['GridTitle'] = 'Goods Receipt';
        $data['DataArray'] = $DataArray;
        $data['ColumnCount'] = $ColumnCount;
        $data['pagination'] = $this->pagination->create_links();
        //$data['get_company'] = "Panda Software House Sdn Bhd";
        $data['home_page'] = site_url('panda_search/gr_branch_list');

        //load the department_view
        $this->load->view('header');
        $this->load->view('panda_menu_view.php');
        $this->load->view('panda_gr_list_view', $data);
        $this->load->view('footer');
    }

    public function gr_details()
    {

        $grrefno = $this->uri->segment(3);

        $grmain = $this->GR_model->get_gr_details(
            'select refno,grdate,docdate,dono,invno,location,tax_code_purchase,gst_tax_rate,total,
             gst_tax_sum,total_include_tax,status from grmain
             where refno = "' . $grrefno . '"'
        );


        foreach ($grmain as $row) {

            $data  = array(
                'refno' => $row->refno,
                'grdate' => $row->grdate,
                'docdate' => $row->docdate,
                'dono' => $row->dono,
                'invno' => $row->invno,
                'location' => $row->location,
                'tax_code_purchase' => $row->tax_code_purchase,
                'gst_tax_rate' => $row->gst_tax_rate,
                'total' => $row->total,
                'gst_tax_sum' => $row->gst_tax_sum,
                'total_include_tax' => $row->total_include_tax,
                'status' => $row->status

            );
        }
        $this->session->set_userdata('gr_detail', $data);

        redirect('Panda_gr/gr_item_details');
    }


    public function gr_item_details()
    {

        //retrieve session data
        $session_data = $this->session->userdata('gr_detail');
        $refno = $session_data['refno'];
        $grdate = $session_data['grdate'];
        $docdate = $session_data['docdate'];
        $dono = $session_data['dono'];
        $invno = $session_data['invno'];
        $location = $session_data['location'];
        $tax_code_purchase = $session_data['tax_code_purchase'];
        $gst_tax_rate = $session_data['gst_tax_rate'];
        $total = $session_data['total'];
        $gst_tax_sum = $session_data['gst_tax_sum'];
        $total_include_tax = $session_data['total_include_tax'];
        $status = $session_data['status'];

        $session_data = $this->session->userdata('branch_detail');
        $branch_name = $session_data['branch_name'];

        //define error message display on view
        if ($this->session->userdata('gr_status')) {
            $session_data = $this->session->userdata('gr_status');
            $query = $session_data['query'];
        } else {
            $query = '';
        }
        $data['query'] = $query;


        //************************************************************************************
        //     BEGIN# STANDARD     88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************
        //pagination settings
        $config['base_url'] = site_url('Panda_gr/gr_item_details');
        $config['total_rows'] = $this->GR_model->count_grchild();
        $config['per_page'] = "10";
        $config["uri_segment"] = 3;
        $choice = 10; //$config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = true;
        $config['last_link'] = true;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        //************************************************************************************
        //     END# STANDARD       88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************


        //Begin# Define Field Type and Title using multi-array
        $DataType = array();

        $DataType[0]['title'] = 'Price Type';
        $DataType[0]['align'] = 'left';
        $DataType[0]['fieldtype'] = 'string';
        $DataType[0]['decimal'] = 0;

        $DataType[1]['title'] = 'Itemcode';
        $DataType[1]['align'] = 'left';
        $DataType[1]['fieldtype'] = 'string';
        $DataType[1]['decimal'] = 0;

        $DataType[2]['title'] = 'Barcode';
        $DataType[2]['align'] = 'left';
        $DataType[2]['fieldtype'] = 'string';
        $DataType[2]['decimal'] = 0;

        $DataType[3]['title'] = 'Description';
        $DataType[3]['align'] = 'left';
        $DataType[3]['fieldtype'] = 'string';
        $DataType[3]['decimal'] = 0;

        $DataType[4]['title'] = 'Qty';
        $DataType[4]['align'] = 'center';
        $DataType[4]['fieldtype'] = 'string';
        $DataType[4]['decimal'] = 1;

        $DataType[5]['title'] = 'Net Unit Price';
        $DataType[5]['align'] = 'center';
        $DataType[5]['fieldtype'] = 'number';
        $DataType[5]['decimal'] = 4;

        $DataType[6]['title'] = 'GR Total Amount(Inc tax)';
        $DataType[6]['align'] = 'center';
        $DataType[6]['fieldtype'] = 'number';
        $DataType[6]['decimal'] = 4;

        $DataType[7]['title'] = 'Invoice QTY';
        $DataType[7]['align'] = 'center';
        $DataType[7]['fieldtype'] = 'number';
        $DataType[7]['decimal'] = 0;

        $DataType[8]['title'] = 'Invoice Unit Price';
        $DataType[8]['align'] = 'center';
        $DataType[8]['fieldtype'] = 'number';
        $DataType[8]['decimal'] = 4;

        $DataType[9]['title'] = 'Invoice Total Amount(Inc tax)';
        $DataType[9]['align'] = 'center';
        $DataType[9]['fieldtype'] = 'number';
        $DataType[9]['decimal'] = 2;

        $DataType[10]['title'] = 'Total Tax Amount';
        $DataType[10]['align'] = 'center';
        $DataType[10]['fieldtype'] = 'number';
        $DataType[10]['decimal'] = 4;

        $DataType[11]['title'] = 'Amend QTY';
        $DataType[11]['align'] = 'center';
        $DataType[11]['fieldtype'] = 'label';
        $DataType[11]['decimal'] = 0;

        $DataType[12]['title'] = 'Amend Unit Price';
        $DataType[12]['align'] = 'center';
        $DataType[12]['fieldtype'] = 'label';
        $DataType[12]['decimal'] = 0;

        $DataType[13]['title'] = 'Amend Total Amount';
        $DataType[13]['align'] = 'center';
        $DataType[13]['fieldtype'] = 'label';
        $DataType[13]['decimal'] = 0;


        $data['DataType'] = $DataType;
        //End# Define Field Type and Title using multi-array

        //Begin# Define View,Edit,Delete button 
        $ButtonLink = array();
        $ButtonLink[0]['Href'] = "index.php/panda_gr/grchild_details/";
        $ButtonLink[0]['FieldNo'] = 1;

        $data['ButtonLink'] = $ButtonLink;
        //End# Define View,Edit,Delete button 


        $ButtonEditVisible = true;
        $data['ButtonEditVisible'] = $ButtonEditVisible;



        $getQueryResult = $this->GR_model->get_gr_list(
            'select pricetype,itemcode,barcode,description,
            qty, netunitprice, 
            ROUND(((totalprice-(hcost_gr))+gst_tax_amount),2) AS totalprice,
            IF(pay_by_invoice=1=1,inv_qty,0) AS inv_qty,
            IF(pay_by_invoice=1,inv_netunitprice,0) AS inv_netunitprice,
            IF(pay_by_invoice=1,invacttotcost,0) AS invacttotcost,gst_tax_amount,sup_qty,sup_netunitprice,sup_totalprice
            FROM grmain a inner join grchild b on a.refno = b.refno
            where b.RefNo = "' . $refno . '" order by line',
            $config["per_page"],
            $data['page']
        );


        //Break the array to get the 2nd level array size
        $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[0]));
        $flat_arr = iterator_to_array($recur_flat_arr_obj, false);
        $ColumnCount = count($flat_arr);

        //Begin# Create 2 Dimensional Array so that it will not depend on query field name
        $DataArray = array();

        for ($i = 0; $i < count($getQueryResult); ++$i) {
            $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[$i]));
            $flat_arr = iterator_to_array($recur_flat_arr_obj, false);


            for ($j = 0; $j < $ColumnCount; ++$j) {
                $DataArray[$i][] = $flat_arr[$j];
            }
        }
        //End# Create 2 Dimensional Array


        $data['refno'] = $refno;
        $data['grdate'] = $grdate;
        $data['docdate'] = $docdate;
        $data['dono'] = $dono;
        $data['invno'] = $invno;
        $data['location'] = $location;
        $data['tax_code_purchase'] = $tax_code_purchase;
        $data['gst_tax_rate'] = $gst_tax_rate;
        $data['total'] = $total;
        $data['gst_tax_sum'] = $gst_tax_sum;
        $data['total_include_tax'] = $total_include_tax;
        $data['status'] = $status;
        $data['branch_name'] = $branch_name;


        $data['DataArray'] = $DataArray;
        $data['ColumnCount'] = $ColumnCount;
        $data['pagination'] = $this->pagination->create_links();
        //$data['get_company'] = "Panda Software House Sdn Bhd";

        $this->session->set_userdata('referred_from', current_url());
        $this->load->view('header');
        $this->load->view('panda_menu_view.php');
        $this->load->view('panda_grchild_view', $data);
        $this->load->view('footer');
    }


    public function grchild_details()
    {

        $itemcode = $this->uri->segment(3);

        $grchild = $this->GR_model->get_gr_details(
            'select itemcode,qty,netunitprice,totalprice,inv_qty,inv_netunitprice,invacttotcost,sup_qty,sup_netunitprice,
             sup_totalprice from grchild
             where refno = "' . $itemcode . '"'
        );


        foreach ($grchild as $row) {

            $data  = array(
                'itemcode' => $row->itemcode,
                'qty' => $row->qty,
                'netunitprice' => $row->netunitprice,
                'totalprice' => $row->totalprice,
                'inv_qty' => $row->inv_qty,
                'inv_netunitprice' => $row->inv_netunitprice,
                'invacttotcost' => $row->invacttotcost,
                'sup_qty' => $row->sup_qty,
                'sup_netunitprice' => $row->sup_netunitprice,
                'sup_totalprice' => $row->sup_totalprice

            );
        }
        $this->session->set_userdata('grchild_detail', $data);

        redirect('Panda_gr/grchild_amend');
    }


    function grchild_amend()
    {
        $session_data = $this->session->userdata('grchild_detail');
        $itemcode = $session_data['itemcode'];
        $qty = $session_data['qty'];
        $netunitprice = $session_data['netunitprice'];
        $totalprice = $session_data['totalprice'];
        $inv_qty = $session_data['inv_qty'];
        $inv_netunitprice = $session_data['inv_netunitprice'];
        $invacttotcost = $session_data['invacttotcost'];
        $sup_qty = $session_data['sup_qty'];
        $sup_netunitprice = $session_data['sup_netunitprice'];
        $sup_totalprice = $session_data['sup_totalprice'];
    }


    function accept_gr()
    {

        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result  = $this->Po_model->get_po_details('select status from grmain where refno = "' . $refno . '"
                                                    and status = "Pending"');

        if ($result) {
            $query = $this->Po_model->accept_po();
            $data = array("query" => $query);
            $this->session->set_userdata('po_status', $data);

            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        } else {
            $query = $this->Po_model->accept_po();
            $data = array("query" => $query);
            $this->session->set_userdata('po_status', $data);

            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
    }


    function download_gr()
    {

        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result  = $this->Po_model->get_po_details('select status from pomain where refno = "' . $refno . '"
                                                    and status = "Pending"');

        $result2  = $this->Po_model->get_po_details('select status from pomain where refno = "' . $refno . '"
                                                    and status = "Completed"');

        if ($result) {

            $data = array("query" => 'Access Denied! Please proceed Accept/Reject PO before Download PO');
            $this->session->set_userdata('po_status', $data);

            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        } else {
            if ($result2) {
                $query = $this->Po_model->download_po();

                if ($query == 1) {
                    redirect('Panda_po/index', 'refresh');
                } else {
                    $data = array("query" => $query);
                    $this->session->set_userdata('po_status', $data);
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
            } else {
                $query = $this->Po_model->download_po();

                if ($query == 1) {
                    redirect('Panda_po/index', 'refresh');
                } else {
                    $data = array("query" => $query);
                    $this->session->set_userdata('po_status', $data);
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
            }
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
            //$user_guid = $_SESSION['user_guid'];
            $refno = $_REQUEST['trans'];

            // if($user_guid = '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {
            //     print_r($refno); die;
            // }

            $header = $this->db->query("SELECT * FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $einv_guidd = $this->db->query("SELECT einv_guid FROM b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ")->row('einv_guid');

            $child_info = $this->db->query("SELECT * FROM b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by `line` asc");

            //$haha = $this->load->view('print/invoice_pdf', $data, true);
            $this->load->library('Pdf_invoice');
            $pdf = new Pdf_invoice('L', 'mm', 'A4', true, 'UTF-8', false);
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


    public function bulk_convert_e_invoice_old()
    {
        // print_r($this->session->userdata('userid'));die;
        $user_id = $this->session->userdata('userid');
        $user_guid = $this->session->userdata('user_guid');
        $from_module = $_SESSION['frommodule'];

        $lite_b2b = 'lite_b2b';
        $b2b_summary = 'b2b_summary';

        $details = $this->input->post('bulk');
        $status = $this->input->post('status');
        $loc = $this->input->post('loc');
        // echo $loc;die;
        // print_r($details);die;

        $customer_guid = $this->session->userdata('customer_guid');

        $error_refno = '';

        //$haha = $this->load->view('print/invoice_pdf', $data, true);
        $this->load->library('Pdf_invoice');
        $pdf = new Pdf_invoice('L', 'mm', 'A4', true, 'UTF-8', false);
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

        foreach ($details as $refno2) {
            $check_gr_cn_exists = $this->db->query("SELECT * FROM b2b_summary.grmain_dncn a LEFT JOIN b2b_summary.`ecn_main` b ON a.`RefNo` = b.`refno` AND a.`customer_guid` = b.`customer_guid` WHERE a.refno = '$refno2' AND a.customer_guid = '$customer_guid' AND b.`refno` IS NULL");
            if ($check_gr_cn_exists->num_rows() > 0) {
                $this->session->set_flashdata('warning', 'Bulk Generation not support for GRN which have GRDA, Please generate ECN first.');
                redirect('general/view_status?status=' . $status . '&loc=' . $loc . '&p_f=&p_t=&e_f=&e_t=&r_n=');
                https: //b2b2.xbridge.my/index.php/general/view_status?status=&loc=HQ&p_f=&p_t=&e_f=&e_t=&r_n=
            }
        }

        foreach ($details as $refno) {
            $deletePage = 0;
            $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');

            $grmain = $this->db->query("SELECT a.`customer_guid`
                    , a.`status`
                    , a.`RefNo`
                    , a.`Location`
                    , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
                    , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
                    , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
                    , a.`GRDate`
                    , a.`Code`
                    , a.`Name`
                    , a.`consign`
                    , a.Total
                    , a.gst_tax_sum
                    , a.total_include_tax
                    , a.subtotal1
                    FROM $b2b_summary.grmain AS a 
                    LEFT JOIN $b2b_summary.grmain_proposed AS b 
                    ON a.refno = b.refno 
                    AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '$customer_guid' ");

            $check_url = $this->db->query("SELECT rest_url from $lite_b2b.acc where acc_guid = '$customer_guid' ")->row('rest_url');

            $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
            //  echo $to_shoot_url ;die;
            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $output = json_decode($response);



            $einv_main = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $H_refno = $grmain->row('RefNo');
            $H_invno = $grmain->row('InvNo');
            $H_dono = $grmain->row('DONo');
            $H_inv_date = $grmain->row('DocDate');
            $H_gr_date = $grmain->row('GRDate');
            $H_total_excl_tax = $grmain->row('total_include_tax');
            $H_tax_amount = $grmain->row('gst_tax_sum');
            $H_total_incl_tax = $grmain->row('total_include_tax');

            $pay_by_grn = $this->db->query("SELECT * FROM b2b_summary.grmain a INNER JOIN b2b_summary.supcus b ON a.Code = b.Code WHERE a.Refno = '$refno' AND a.customer_guid = '$customer_guid' AND b.grn_baseon_pocost = 0 AND b.type = 'S' AND b.customer_guid = '$customer_guid'");

            $pay_by_grn_status = $pay_by_grn->num_rows() > 0 ? 1 : 0;
            $pay_by_grn_status = 1;
            if (($httpcode == 200) && ($grmain->num_rows() > 0) && ($output[0]->line != 'No Records Found') && ($pay_by_grn_status == 1)) {
                if ($einv_main->num_rows() > 0) {
                    // echo 1;die;
                    //update
                    // $data = array(
                    //     'invno'=> addslashes($H_invno),
                    //     'dono'=> addslashes($H_dono),
                    //     'inv_date'=> $H_inv_date,
                    //     'gr_date'=> $H_gr_date,
                    //     'total_excl_tax' => $H_total_excl_tax,
                    //     'tax_amount'=> $H_tax_amount,
                    //     'total_incl_tax'=> $H_total_incl_tax,
                    //     'updated_at'=> $this->db->query("select now() as naw")->row('naw'),
                    //     'updated_by'=> $user_id,
                    // );

                    // $this->db->where('einv_guid', $einv_main->row('einv_guid'));
                    // $this->db->where('refno', $refno);
                    // $this->db->where('customer_guid', $customer_guid);
                    // $this->db->update("$lite_b2b.einv_main", $data);
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

                    $get_einv_guid = $this->db->query("SELECT einv_guid from $b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'")->row('einv_guid');
                    $affected_rows = $this->db->affected_rows();

                    $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_excl_tax = a.Subtotal1 WHERE a.subtotal1 <> b.total_excl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");

                    $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_incl_tax = a.total_include_tax WHERE a.total_include_tax <> b.total_incl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");
                } //close else for checking einv_main exist or not


                if ($affected_rows > 0) {
                    $this->db->query("DELETE FROM $b2b_summary.einv_child where einv_guid = '$get_einv_guid'");

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



                $header = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

                $einv_guidd = $this->db->query("SELECT einv_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ")->row('einv_guid');

                $child_info = $this->db->query("SELECT * FROM $b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by line asc");


                if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                    $this->db->query("UPDATE b2b_summary.grmain set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");

                    $this->db->query("UPDATE b2b_summary.grmain set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");


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
                , a.`Code`
                , a.`Name`
                , a.`Invno`
                FROM $b2b_summary.grmain AS a 
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
                 </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $customer_branch_info['BRANCH_ADD'] . '<br> </td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $customer_branch_info['BRANCH_TEL'] . ' <b>  Fax:</b> ' . $customer_branch_info['BRANCH_FAX'] . '</td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><b>Sup Code:</b> ' . $gr_info['Code'] . ' - ' . $gr_info['Name'] . ' <b><br>Received Loc:</b> ' . $gr_info['Location'] . ' - ' . $supcus_supplier['Name'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td colspan="2"><b>Tax Invoice No:</b> ' . $header_main['invno'] . ' <b><br>Delivery Order:</b> ' . $header_main['dono'] . '</td> <td><b>Invoice Date:</b> ' . $header_main['inv_date'] . ' <b><br>Ref No:</b> ' . $header_main['refno'] . '</td> </tr></tbody></table> </td> </tr> </tbody> </table> </td> <td style="width: 20%;"> <table id="right-table" border="0" cellspacing="0" cellpadding="0" style="width: 100%;height:500px;"> <tbody style="height:500px;"> <tr> <td style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>Invoice</b></p></td> </tr> <tr> <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Inv No</p><p style="font-size:12px;"><b>' . $header_main['einvno'] . '</b></p></td> </tr> </tbody> </table> </td> </tr> </tbody></table>';
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

    public function bulk_convert_e_invoice()
    {
        // print_r($this->session->userdata('userid'));die;
        $user_id = $this->session->userdata('userid');
        $user_guid = $this->session->userdata('user_guid');
        $from_module = $_SESSION['frommodule'];

        $lite_b2b = 'lite_b2b';
        $b2b_summary = 'b2b_summary';

        $details = $this->input->post('bulk');
        $status = $this->input->post('status');
        $loc = $this->input->post('loc');
        // echo $loc;die;
        // print_r($details);die;

        $customer_guid = $this->session->userdata('customer_guid');

        $error_refno = '';

        //$haha = $this->load->view('print/invoice_pdf', $data, true);
        $this->load->library('Pdf_invoice');
        $pdf = new Pdf_invoice('L', 'mm', 'A4', true, 'UTF-8', false);
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

        foreach ($details as $refno2) {
            $check_gr_cn_exists = $this->db->query("SELECT * FROM b2b_summary.grmain_dncn a LEFT JOIN b2b_summary.`ecn_main` b ON a.`RefNo` = b.`refno` AND a.`customer_guid` = b.`customer_guid` WHERE a.refno = '$refno2' AND a.customer_guid = '$customer_guid' AND b.`refno` IS NULL");
            if ($check_gr_cn_exists->num_rows() > 0) {
                $this->session->set_flashdata('warning', 'Bulk Generation not support for GRN which have GRDA, Please generate ECN first.');
                redirect('general/view_status?status=' . $status . '&loc=' . $loc . '&p_f=&p_t=&e_f=&e_t=&r_n=');
                https: //b2b2.xbridge.my/index.php/general/view_status?status=&loc=HQ&p_f=&p_t=&e_f=&e_t=&r_n=
            }
        }
        // current only tf get grchild data at b2b_summary.grmain_info

        foreach ($details as $refno) {
            $deletePage = 0;
            $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');

            $grmain = $this->db->query("SELECT a.`customer_guid`
                    , a.`status`
                    , a.`RefNo`
                    , a.`Location`
                    , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
                    , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
                    , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
                    , a.`GRDate`
                    , a.`Code`
                    , a.`Name`
                    , a.`consign`
                    , a.Total
                    , a.gst_tax_sum
                    , a.total_include_tax
                    , a.subtotal1
                    FROM $b2b_summary.grmain AS a 
                    LEFT JOIN $b2b_summary.grmain_proposed AS b 
                    ON a.refno = b.refno 
                    AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '$customer_guid' ");

            // future using this query
            // $grmain = $this->db->query("SELECT a.`customer_guid`
            // , a.`status`
            // , a.`RefNo`
            // , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Location')) AS CHAR(10)) AS Location
            // , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
            // , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
            // , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
            // , a.`GRDate`
            // , a.`supplier_code`
            // , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Name')) AS CHAR(60)) AS NAME
            // , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].consign')) AS CHAR(2)) AS consign
            // , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Total')) AS CHAR(10)) AS Total
            // , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].gst_tax_sum')) AS CHAR(10)) AS gst_tax_sum
            // , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].total_include_tax')) AS CHAR(10)) AS total_include_tax
            // , CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`gr_json_info`,'$.grmain[0].Subtotal1')) AS CHAR(10)) AS Subtotal1
            // , a.`gr_json_info`
            // FROM b2b_summary.`grmain_info` AS a 
            // LEFT JOIN b2b_summary.grmain_proposed AS b 
            // ON a.refno = b.refno 
            // AND a.customer_guid = b.customer_guid 
            // where a.refno = '$refno' 
            // AND a.customer_guid = '$customer_guid'");

            if ($customer_guid == '8D5B38E931FA11E79E7E33210BD612D3' && $grmain->row('GRDate') >= '2021-12-01') {
                //get full grmain json info data
                $get_grn_child_data = $this->db->query("SELECT a.`gr_json_info`
                FROM b2b_summary.`grmain_info` AS a
                WHERE a.`refno` = '$refno'
                AND a.`customer_guid`='$customer_guid'")->row('gr_json_info');
            } else {
                $check_url = $this->db->query("SELECT rest_url from $lite_b2b.acc where acc_guid = '$customer_guid' ")->row('rest_url');

                $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
                //  echo $to_shoot_url ;die;
                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $output = json_decode($response);
            }

            $einv_main = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $H_refno = $grmain->row('RefNo');
            $H_invno = $grmain->row('InvNo');
            $H_dono = $grmain->row('DONo');
            $H_inv_date = $grmain->row('DocDate');
            $H_gr_date = $grmain->row('GRDate');
            $H_total_excl_tax = $grmain->row('total_include_tax');
            $H_tax_amount = $grmain->row('gst_tax_sum');
            $H_total_incl_tax = $grmain->row('total_include_tax');

            $pay_by_grn = $this->db->query("SELECT * FROM b2b_summary.grmain a INNER JOIN b2b_summary.supcus b ON a.Code = b.Code WHERE a.Refno = '$refno' AND a.customer_guid = '$customer_guid' AND b.grn_baseon_pocost = 0 AND b.type = 'S' AND b.customer_guid = '$customer_guid'");

            $pay_by_grn_status = $pay_by_grn->num_rows() > 0 ? 1 : 0;
            $pay_by_grn_status = 1;
            if (($httpcode == 200) && ($grmain->num_rows() > 0) && ($output[0]->line != 'No Records Found') && ($pay_by_grn_status == 1) || count(json_decode($get_grn_child_data, true)['grchild']) > 0) {
                if ($einv_main->num_rows() > 0) {
                    // echo 1;die;
                    //update
                    // $data = array(
                    //     'invno'=> addslashes($H_invno),
                    //     'dono'=> addslashes($H_dono),
                    //     'inv_date'=> $H_inv_date,
                    //     'gr_date'=> $H_gr_date,
                    //     'total_excl_tax' => $H_total_excl_tax,
                    //     'tax_amount'=> $H_tax_amount,
                    //     'total_incl_tax'=> $H_total_incl_tax,
                    //     'updated_at'=> $this->db->query("select now() as naw")->row('naw'),
                    //     'updated_by'=> $user_id,
                    // );

                    // $this->db->where('einv_guid', $einv_main->row('einv_guid'));
                    // $this->db->where('refno', $refno);
                    // $this->db->where('customer_guid', $customer_guid);
                    // $this->db->update("$lite_b2b.einv_main", $data);
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

                    $get_einv_guid = $this->db->query("SELECT einv_guid from $b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'")->row('einv_guid');
                    $affected_rows = $this->db->affected_rows();

                    $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_excl_tax = a.Subtotal1 WHERE a.subtotal1 <> b.total_excl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");

                    $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_incl_tax = a.total_include_tax WHERE a.total_include_tax <> b.total_incl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");
                } //close else for checking einv_main exist or not


                if ($affected_rows > 0) {
                    $this->db->query("DELETE FROM $b2b_summary.einv_child where einv_guid = '$get_einv_guid'");
                    // tempary solution
                    if ($customer_guid == '8D5B38E931FA11E79E7E33210BD612D3' && $H_gr_date >= '2021-12-01') {
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
                }




                $header = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

                $einv_guidd = $this->db->query("SELECT einv_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ")->row('einv_guid');

                $child_info = $this->db->query("SELECT * FROM $b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by line asc");


                if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                    $this->db->query("UPDATE b2b_summary.grmain set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");

                    $this->db->query("UPDATE b2b_summary.grmain set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");


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
                , a.`Code`
                , a.`Name`
                , a.`Invno`
                FROM $b2b_summary.grmain AS a 
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
                 </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $customer_branch_info['BRANCH_ADD'] . '<br> </td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $customer_branch_info['BRANCH_TEL'] . ' <b>  Fax:</b> ' . $customer_branch_info['BRANCH_FAX'] . '</td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><b>Sup Code:</b> ' . $gr_info['Code'] . ' - ' . $gr_info['Name'] . ' <b><br>Received Loc:</b> ' . $gr_info['Location'] . ' - ' . $supcus_supplier['Name'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td colspan="2"><b>Tax Invoice No:</b> ' . $header_main['invno'] . ' <b><br>Delivery Order:</b> ' . $header_main['dono'] . '</td> <td><b>Invoice Date:</b> ' . $header_main['inv_date'] . ' <b><br>Ref No:</b> ' . $header_main['refno'] . '</td> </tr></tbody></table> </td> </tr> </tbody> </table> </td> <td style="width: 20%;"> <table id="right-table" border="0" cellspacing="0" cellpadding="0" style="width: 100%;height:500px;"> <tbody style="height:500px;"> <tr> <td style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>Invoice</b></p></td> </tr> <tr> <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Inv No</p><p style="font-size:12px;"><b>' . $header_main['einvno'] . '</b></p></td> </tr> </tbody> </table> </td> </tr> </tbody></table>';
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



    public function update_invno()
    {

        $new_invno = $this->input->post('new_invno');
        $new_invno = json_encode($new_invno);
        $new_invno = json_decode($new_invno);

        $customer_guid = $_SESSION['customer_guid'];


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
                INNER JOIN b2b_summary.grmain AS b
                ON a.customer_guid = b.customer_guid
                AND a.refno = b.refno
                SET
                a.location = b.location
                , a.grdate = b.grdate
                , a.issuestamp = b.issuestamp
                , a.laststamp = b.laststamp
                , a.code = b.code
                , a.name = b.name
                , a.InvNo = '$xinvno'
                where a.customer_guid = '$customer_guid' and a.refno = '$header_refno' and posted = '0'
                 ");
            } else {
                $grmain = $this->db->query("SELECT * FROM b2b_summary.grmain WHERE RefNo = '$header_refno' ");

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



    public function einv_main_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $customer_guid = $_SESSION['customer_guid'];
        $query_supcode = $_SESSION['query_supcode'];

        if ($query_supcode != '') {
            $einv_main = $this->db->query("SELECT b.`Code`, a.* FROM b2b_summary.einv_main a INNER JOIN b2b_summary.`grmain` b ON a.refno = b.`refno` WHERE a.customer_guid = '$customer_guid' AND b.`Code` IN (" . $_SESSION['query_supcode'] . ")");
        } else {
            $einv_main = $this->db->query("SELECT b.`Code`, a.* FROM b2b_summary.einv_main a INNER JOIN b2b_summary.`grmain` b ON a.refno = b.`refno` WHERE a.customer_guid = '$customer_guid' ");
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
        $this->load->library('Pdf_invoice');
        $pdf = new Pdf_invoice('L', 'mm', 'A4', true, 'UTF-8', false);
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
                $this->db->query("UPDATE b2b_summary.grmain set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");

                $this->db->query("UPDATE b2b_summary.grmain set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");


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
, a.`Code`
, a.`Name`
, a.`Invno`
FROM $b2b_summary.grmain AS a 
LEFT JOIN $b2b_summary.grmain_proposed AS b 
ON a.refno = b.refno 
AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '" . $header_customer_guid['customer_guid'] . "'";
            $result = $conn->query($gr_info);
            $gr_info = $result->fetch_assoc();


            $supcus_customer = "SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid' ";
            $result = $conn->query($supcus_customer);
            $supcus_customer = $result->fetch_assoc();

            // $supcus_customer = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid' ";
            // $result = $conn->query($supcus_customer);
            // $supcus_customer = $result->fetch_assoc();

            $supcus_supplier = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Code'] . "' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($supcus_supplier);
            $supcus_supplier = $result->fetch_assoc();


            $customer_branch_info = "SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid' ";
            $result = $conn->query($customer_branch_info);
            $customer_branch_info = $result->fetch_assoc();


            $pdf->SetFont('helvetica', '', 9.5);

            $html = '<table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;"> <tbody><tr> <td style="width: 80%;text-align: left"> <table cellspacing="0" cellpadding="0"> <tbody> <tr> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Purchase from Registered GST Supplier </td> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Goods Received Note Issued by </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $supcus_supplier['Name'] . '</b> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $customer_branch_info['BRANCH_NAME'] . ' </b> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_supplier['reg_no'] . ' </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_customer['acc_regno'] . '</td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $supcus_supplier['Add1'] . '
                <br>' . $supcus_supplier['Add2'] . '
                <br>' . $supcus_supplier['Add3'] . '
                <br>' . $supcus_supplier['Add4'] . '<br>
                </td>
                 </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $customer_branch_info['BRANCH_ADD'] . '<br> </td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><b>Sup Code:</b> ' . $gr_info['Code'] . ' - ' . $gr_info['Name'] . ' <b><br>Received Loc:</b> ' . $gr_info['Location'] . ' - ' . $supcus_supplier['Name'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td colspan="2"><b>Tax Invoice No:</b> ' . $header_main['invno'] . ' <b><br>Delivery Order:</b> ' . $header_main['dono'] . '</td> <td><b>Invoice Date:</b> ' . $header_main['inv_date'] . ' <b><br>Ref No:</b> ' . $header_main['refno'] . '</td> </tr></tbody></table> </td> </tr> </tbody> </table> </td> <td style="width: 20%;"> <table id="right-table" border="0" cellspacing="0" cellpadding="0" style="width: 100%;height:500px;"> <tbody style="height:500px;"> <tr> <td style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>Invoice</b></p></td> </tr> <tr> <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Inv No</p><p style="font-size:12px;"><b>' . $header_main['einvno'] . '</b></p></td> </tr> </tbody> </table> </td> </tr> </tbody></table>';
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

    public function fetch_display_message()
    {
        $customer_guid = $this->session->userdata('customer_guid');
        $refno = $this->input->post('refno');

        $database1 = 'b2b_summary';

        $check_grda = $this->db->query("SELECT * FROM $database1.grmain_dncn WHERE customer_guid = '$customer_guid' AND refno = '$refno'");

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

    public function view_po_grn()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $gr_refno = $this->input->post('gr_refno');
        // $ver = $this->session->userdata('redirect');

        $database1 = 'lite_b2b';
        $rest_link = $this->db->query("SELECT * FROM $database1.acc WHERE acc_guid = '$customer_guid'");

        $url = $rest_link->row('rest_url');

        $to_shoot_url = $url."/gr_child_preview";
       // echo $to_shoot_url; die;
        
        $data = array(
            'gr_refno' => $gr_refno,
        );

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
        //echo $result;die;
        //close connection
        curl_close($ch);  

        if($output->status == "true")
        {
            $content = $output->result;
        }
        else
        {
            $content = $output->result;
        }  

        $data = array(
            'content' => $content,
        );
        // print_r($data);die;
        echo json_encode($data);

        // $content = $this->db->query("SELECT a.*, b.`SName` FROM b2b_summary.`po_grn_inv` a LEFT JOIN b2b_summary.`pomain` b ON a.`po_refno` = b.`RefNo` WHERE a.`gr_refno` = '$gr_refno' AND a.`customer_guid` = '$customer_guid' ");

    }
}
