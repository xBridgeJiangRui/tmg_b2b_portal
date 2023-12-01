<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class E_document extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->internal_ip = $this->file_config_b2b->file_path_name('', 'web', 'general_doc', 'general_internal_ip', 'GIP');
        $this->retailer_doc_name = $this->file_config_b2b->file_path_name('', 'web', 'retailer_doc', 'retailer_doc_name', 'RTDN');

        $this->api_url = $this->internal_ip . '/' . $this->retailer_doc_name;
    }

    public function grmain_proposed()
    {
        $einvno = $this->input->post('einvno');
        $einvdate = $this->input->post('einvdate');
        $e_gr_refno = $this->input->post('e_gr_refno');
        $user_guid = $this->session->userdata('user_guid');
        $customer_guid = $this->session->userdata('customer_guid');
        // print_r($this->session->userdata());die;
        // print_r($this->input->post());die;
        // $data = array();
        $data[] = array(
            'einvno' => trim($einvno),
            'einvdate' => $einvdate,
            'e_gr_refno' => $e_gr_refno,
            'user_guid' => $user_guid,
            'customer_guid' => $customer_guid,
        );

        // echo json_encode($data);die;


        $url = $this->api_url;

        $to_shoot_url = $url . "/rest_b2b/index.php/E_document_process/grmain_proposed";
        //echo $to_shoot_url;die;
        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");

        $cuser_name = 'ADMIN';
        $cuser_pass = '1234';

        $ch = curl_init($to_shoot_url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $output = json_decode($result);
        // $status = json_encode($output);
        // print_r($output->result);die;
        //echo $result;die;
        //close connection
        curl_close($ch);

        if ($output->status == 'true') {
            $status = 1;
            $message = 'Updated Successfully';
        } else {
            $status = 0;
            $message = 'Update Unsuccessful';
        }

        $response = array(
            'status' => $status,
            'message' => $message,
        );
        echo json_encode($response);
        die;
        // echo 1;die;

    }

    public function generate_all_doc_type()
    {
        $einvno = $this->input->post('einvno');
        $einvdate = $this->input->post('einvdate');
        $e_gr_refno = $this->input->post('e_gr_refno');
        $user_guid = $this->session->userdata('user_guid');
        $customer_guid = $this->session->userdata('customer_guid');
        $database2 = 'b2b_summary';
        $error = 0;
        $message = '';
        $ecn_generate_status = '';
        $add_msg = '';
        // print_r($this->session->userdata());die;
        // print_r($this->input->post());die;
        //$array_test = array($einvno,$einvdate,$e_gr_refno,$user_guid,$customer_guid);
        //print_r($array_test); die;
        // $data = array();
        $check_process = $this->db->query("SELECT * FROM lite_b2b.einv_process_log WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid'")->result_array();

        if(count($check_process) == 0 )
        {
            $insert_process_log = $this->db->query("INSERT INTO lite_b2b.einv_process_log (customer_guid,refno,`status`,created_at,created_by) VALUES('$customer_guid', '$e_gr_refno','1',NOW(),'$user_guid')");
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
                $error = 88;
                $add_msg = 'Error To Process E-invoice.';
            }
            
        }

        $check_grmain = $this->db->query("SELECT customer_guid,RefNo FROM $database2.grmain_info WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND status <> 'Invoice Generated' LIMIT 1");
        // print_r($check_grmain->result());die;

        $check_grmain_dncn = $this->db->query("SELECT customer_guid,RefNo,transtype FROM $database2.grmain_dncn_info WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND status <> 'Ecn Generated'");
        // print_r($check_grmain_dncn->result());die;

        if($check_grmain_dncn->num_rows() == 0)
        {
            $check_grmain_amt = $this->db->query("SELECT customer_guid,refno FROM $database2.grmain_info WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND total != ROUND(JSON_UNQUOTE(JSON_EXTRACT(`gr_json_info`,'$.grmain[0].InvNetAmt_Vendor')), 2 ) ");

            if($check_grmain_amt->num_rows() != 0)
            {
                $error = 99;
                $add_msg = 'GRN Amount Difference';
                $ecn_generate_status = 'error';
            }
        }
        else
        {
            $get_dncn_detail = $this->db->query("SELECT a.customer_guid, IF( ext_doc1 IS NULL, IF( d.ext_sup_cn_no IS NULL, a.sup_cn_no, d.ext_sup_cn_no ), ext_doc1 ) AS ext_doc1 FROM b2b_summary.grmain_dncn_info AS a LEFT JOIN ( SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '$customer_guid' AND refno = '$e_gr_refno' ) AS b ON a.refno = b.refno AND a.transtype = b.type LEFT JOIN lite_b2b.acc_settings c ON a.customer_guid = c.customer_guid LEFT JOIN b2b_summary.grmain_dncn_proposed d ON a.refno = d.refno AND a.transtype = d.trans_type AND a.customer_guid = d.customer_guid WHERE a.refno = '$e_gr_refno' AND a.customer_guid = '$customer_guid' ORDER BY transtype ASC");

            $ecn_no_data = $get_dncn_detail->row('ext_doc1');

            if(strlen($ecn_no_data) > '30')
            {
                $error = 99;
                $add_msg = 'E-CN No limit 30 characters.';
                $ecn_generate_status = 'error';
            }
        }

        $get_header_detail = $this->db->query("SELECT a.`customer_guid`, IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo,a.`supplier_code` AS `Code`,a.loc_group AS `location` FROM $database2.grmain_info AS a LEFT JOIN $database2.grmain_proposed AS b ON a.refno = b.refno AND a.customer_guid = b.customer_guid where a.refno = '$e_gr_refno' and a.customer_guid = '$customer_guid'");

        $einv_no_data = $get_header_detail->row('InvNo');
        $einv_code = $get_header_detail->row('Code'); 
        $einv_location = $get_header_detail->row('location'); 

        if(strlen($einv_no_data) > '30')
        {
            $error = 99;
            $add_msg = 'E-Inv No limit 30 characters.';
        }

        $acc_setting_query = $this->db->query("SELECT IF(CURDATE() >= a.einv_grab_date , 'Yes', 'No') AS check_inv_status
        FROM lite_b2b.acc_settings AS a
        WHERE a.customer_guid = '$customer_guid'");

        // echo $this->db->last_query();die;

        $check_inv_status = $acc_setting_query->row('check_inv_status');

        if($check_inv_status == 'Yes')
        {
            $store_refno = '';
            $url = $this->api_url;

            $to_check_duplicate = $url."/rest_b2b/index.php/E_invoice_validate/grn_einv_checking";
            //echo $to_check_duplicate ;die;

            $data_check_einv = array(
                "customer_guid" => $customer_guid,
                "refno" => $e_gr_refno,
                "doctype"  => 'GRN',
                "code"  => $einv_code,
                "invno"  => $einv_no_data,
                "loc_group" => $einv_location,
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
            $output = json_decode($result);
            $array_output = json_decode(json_encode($output->result), true);
            // $status = json_encode($output);
            //print_r($result);die;
            //print_r($output);die;
            //echo $result;die;
            curl_close($ch);
            
            if(isset($output->status))
            {
                if($output->status == false)
                {
                    foreach($array_output as $row)
                    {
                        //print_r($row['refno']); die;
                        $store_refno .= $row['refno'] . ',';
                    }
                    $store_refno = rtrim($store_refno, ',');
                    $error = 99;
                    $add_msg = $output->message. ' ' . $store_refno;
                }
            }
            else
            {
                $error = 99;
                $add_msg = 'Process Checking Einv Invalid';
            }
        }

        if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
            $record_log = 1;
        } else {
            $record_log = 0;
        }
        
        if($error == 0)
        {
            // check grmain_dncn have data run this function generate e cn
            if ($check_grmain_dncn->num_rows() > 0) {
                foreach ($check_grmain_dncn->result() as $row) {
                    $data = array();
                    $data[] = array(
                        // 'einvno' => $einvno,
                        // 'einvdate' => $einvdate,
                        'e_gr_refno' => $row->RefNo,
                        'trans_type' => $row->transtype,
                        'user_guid' => $user_guid,
                        'customer_guid' => $customer_guid,
                        'manual' => '1',
                        'record_log' => $record_log,
                    );
                    //echo json_encode($data);die;

                    $url = $this->api_url;

                    $to_shoot_url = $url . "/rest_b2b/index.php/E_document_process/generate_ecn";
                    // echo $to_shoot_url;die;
                    // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");

                    $cuser_name = 'ADMIN';
                    $cuser_pass = '1234';

                    $ch = curl_init($to_shoot_url);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                    curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $result = curl_exec($ch);
                    $output = json_decode($result);
                    // $status = json_encode($output);
                    // print_r($output->result);die;
                    // echo $result;die;
                    //close connection
                    curl_close($ch);
                    $ecn_generate_status = $output->status;
                    if ($output->status == "true") {
                        $message .= $output->message;
                    } else {
                        $error++;
                        $message .= $output->message;
                        if ($output->status == "false") {
                            $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid', '$row->RefNo' ,'ECN_CL','$message',NOW(),'$user_guid')");
                        }
                    }
                }
            }
            // echo $message;die;
            // after generate e cn return status is true just generate e invoice or only generate e invoice
            if (($check_grmain_dncn->num_rows() > 0 && $ecn_generate_status == 'true') || $check_grmain_dncn->num_rows() == 0) {

                if ($check_grmain->num_rows() > 0) {
                    foreach ($check_grmain->result() as $row) {
                        $data = array();
                        $data[] = array(
                            // 'einvno' => $einvno,
                            // 'einvdate' => $einvdate,
                            'e_gr_refno' => $row->RefNo,
                            'user_guid' => $user_guid,
                            'customer_guid' => $customer_guid,
                            'manual' => '1',
                            'record_log' => $record_log,
                        );
                        // echo json_encode($data);die;

                        $url = $this->api_url;

                        $to_shoot_url = $url . "/rest_b2b/index.php/E_document_process/generate_einvoice";
                        // echo $to_shoot_url;die;
                        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");

                        $cuser_name = 'ADMIN';
                        $cuser_pass = '1234';

                        $ch = curl_init($to_shoot_url);
                        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $result = curl_exec($ch);
                        $output = json_decode($result);
                        // $status = json_encode($output);
                        // print_r($output->result);die;
                        // echo $result;die;
                        //close connection
                        curl_close($ch);
                        // print_r($output->status);
                        // die;
                        if ($output->status == "true") {
                            $message .= $output->message;
                        } else {
                            $error++;
                            $message .= $output->message;
                            if ($output->status == "false") {
                                $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid', '$row->RefNo' ,'EINV_CL','$message',NOW(),'$user_guid')");
                            }
                        }
                    }
                }
            }
        }

        // print_r($error);
        if ($error <= 0) {
            $message = 'Generated Successfully';
            $status = 1;

            $update_process_log = $this->db->query("UPDATE lite_b2b.einv_process_log SET `status` = '2',updated_at = NOW() WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid'");

        } else {
            $message = 'Generate Unsuccessful ' . $add_msg;
            $status = 0;

            if($error != 88)
            {
                $delete_process_log = $this->db->query("DELETE FROM `lite_b2b`.`einv_process_log` WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND `status` = '1'");
            }
        }
        $response = array(
            'status' => $status,
            'message' => $message,
        );
        echo json_encode($response);
        die;
        // echo 1;die;

    }

    public function fetch_grmain_proposed()
    {
        $invno_array = $this->input->post('invno_array');
        $customer_guid = $this->session->userdata('customer_guid');
        // print_r($invno_array);die;

        $data = array();
        foreach ($invno_array as $row) {

            // print_r($row);die;
            $refno = $row['refno'];
            $result = $this->db->query("SELECT a.refno,IF(b.invno IS NULL,a.InvNo,b.invno) as invno FROM b2b_summary.grmain_info a LEFT JOIN b2b_summary.grmain_proposed b ON a.refno = b.refno AND a.customer_guid = b.customer_guid WHERE a.refno = '$refno' AND a.customer_guid = '$customer_guid'");
            $data['refno'] = $row['refno'];
            $data['invno'] = $result->row('invno');

            $main_data[] = $data;
        }

        echo json_encode($main_data);
        die;
    }

    public function grmain_dncn_proposed()
    {
        $refno = $this->input->post('refno');
        $type = $this->input->post('type');
        $ext_sup_cn_no = $this->input->post('ext_sup_cn_no');
        $user_guid = $this->session->userdata('user_guid');
        $customer_guid = $this->session->userdata('customer_guid');
        // print_r($this->session->userdata());die;
        // print_r($this->input->post());die;
        // $data = array();
        $data[] = array(
            'refno' => $refno,
            'type' => $type,
            'ext_sup_cn_no' => $ext_sup_cn_no,
            'user_guid' => $user_guid,
            'customer_guid' => $customer_guid,
        );

        // echo json_encode($data);die;


        $url = $this->api_url;

        $to_shoot_url = $url . "/rest_b2b/index.php/E_document_process/grmain_dncn_proposed";
        // echo $to_shoot_url;die;
        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");

        $cuser_name = 'ADMIN';
        $cuser_pass = '1234';

        $ch = curl_init($to_shoot_url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $output = json_decode($result);
        // $status = json_encode($output);
        // print_r($output->result);die;
        // echo $result;die;
        //close connection
        curl_close($ch);

        if ($output->status == 'true') {
            $status = 1;
            $message = 'Updated Successfully';
        } else {
            $status = 0;
            $message = 'Update Unsuccessful(' . $output->message . ')';
        }

        $response = array(
            'status' => $status,
            'message' => $message,
        );
        echo json_encode($response);
        die;
        // echo 1;die;

    }

    public function generate_all_doc_type_v2()
    {
        $einvno = $this->input->post('einvno');
        $einvdate = $this->input->post('einvdate');
        $e_gr_refno = $this->input->post('e_gr_refno');
        $user_guid = $this->input->post('user_guid');
        $customer_guid = $this->input->post('customer_guid');
        // $user_guid = $this->session->userdata('user_guid');
        // $customer_guid = $this->session->userdata('customer_guid');
        $database2 = 'b2b_summary';
        $error = 0;
        $message = '';
        $ecn_generate_status = '';
        // print_r($this->session->userdata());die;
        // print_r($this->input->post());die;
        // $data = array();
        $check_grmain = $this->db->query("SELECT * FROM $database2.grmain WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND status <> 'Invoice Generated' LIMIT 1");

        // echo $this->db->last_query();
        // die;
        $check_grmain_dncn = $this->db->query("SELECT * FROM $database2.grmain_dncn WHERE refno = '$e_gr_refno' AND customer_guid = '$customer_guid' AND status <> 'Ecn Generated'");
        // print_r($check_grmain_dncn->result());die;

        if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
            $record_log = 0;
        } else {
            $record_log = 0;
        }
        // check grmain_dncn have data run this function generate e cn
        if ($check_grmain_dncn->num_rows() > 0) {
            foreach ($check_grmain_dncn->result() as $row) {
                $data = array();
                $data[] = array(
                    // 'einvno' => $einvno,
                    // 'einvdate' => $einvdate,
                    'e_gr_refno' => $row->RefNo,
                    'trans_type' => $row->transtype,
                    'user_guid' => $user_guid,
                    'customer_guid' => $customer_guid,
                    'manual' => '1',
                    'record_log' => $record_log,
                );
                // echo json_encode($data);die;

                $url = $this->api_url;

                $to_shoot_url = $url . "/rest_b2b/index.php/E_document_process/generate_ecn";
                // echo $to_shoot_url;die;
                // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");

                $cuser_name = 'ADMIN';
                $cuser_pass = '1234';

                $ch = curl_init($to_shoot_url);
                // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($ch);
                $output = json_decode($result);
                // $status = json_encode($output);
                // print_r($output->result);die;
                // echo $result;die;
                //close connection
                curl_close($ch);
                $ecn_generate_status = $output->status;
                if ($output->status == "true") {
                    $message .= $output->message;
                } else {
                    $error++;
                    $message .= $output->message;
                    if ($output->status == "false") {
                        $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid', '$row->RefNo' ,'ECN_CL','$message',NOW(),'$user_guid')");
                    }
                }
            }
        }
        // echo $message;die;
        // after generate e cn return status is true just generate e invoice or only generate e invoice

        if (($check_grmain_dncn->num_rows() > 0 && $ecn_generate_status == 'true') || $check_grmain_dncn->num_rows() == 0) {

            if ($check_grmain->num_rows() > 0) {

                foreach ($check_grmain->result() as $row) {
                    $data = array();
                    $data[] = array(
                        // 'einvno' => $einvno,
                        // 'einvdate' => $einvdate,
                        'e_gr_refno' => $row->RefNo,
                        'user_guid' => $user_guid,
                        'customer_guid' => $customer_guid,
                        'manual' => '1',
                        'record_log' => $record_log,
                    );
                    // echo json_encode($data);die;

                    $url = $this->api_url;

                    $to_shoot_url = $url . "/rest_b2b/index.php/E_document_process/generate_einvoice";
                    // echo $to_shoot_url;
                    // die;
                    // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");

                    $cuser_name = 'ADMIN';
                    $cuser_pass = '1234';

                    $ch = curl_init($to_shoot_url);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
                    curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $result = curl_exec($ch);
                    $output = json_decode($result);
                    // $status = json_encode($output);
                    // echo $result;die;
                    //close connection
                    curl_close($ch);
                    if ($output->status == "true") {
                        $message .= $output->message;
                    } else {
                        $error++;
                        $message .= $output->message;
                        if ($output->status == "false") {
                            $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid', '$row->RefNo' ,'EINV_CL','$message',NOW(),'$user_guid')");
                        }
                    }
                }
            }
        }

        // print_r($error);
        if ($error <= 0) {
            $message = 'Generated Successfully';
            $status = 1;
        } else {
            $message = 'Generate Unsuccessful';
            $status = 0;
        }
        $response = array(
            'status' => $status,
            'message' => $message,
        );
        echo json_encode($response);
        die;
        // echo 1;die;

    }
}

/* End of file Acc_branch.php */
/* Location: ./application/controllers/Acc_branch.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-12-23 13:03:44 */
/* http://harviacode.com */