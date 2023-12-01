<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Upload extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->library('Panda_PHPMailer');   
        //$this->load->library('PDFMerger');   
        $this->load->model('General_model');
        /*$this->load->library('myfpdf');   
        $this->load->library('mytcpdf');   
        $this->load->library('myfpdi'); */  
    }


    public function upload_prdn_cn()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 2;die;
            // print_r($this->input->post());die;
            $database = 'lite_b2b';
            $database1 = 'b2b_summary';
            $refno = $this->input->post('upload_cn_refno');
            $doc_type = $this->input->post('upload_prdn_cn_doc_type');
            $loc = $this->input->post('upload_prdn_cn_loc');
            $customer_guid = $this->session->userdata('customer_guid');
            $supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.dbnotemain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
            // echo $this->db->last_query();die;
            // echo $refno.$doc_type;die;
            $target_dir = "retailer_file/".$customer_guid."/prdncn_cn/".$supplier_guid->row('supplier_guid')."/";
            $supplier_array_file = explode('/',substr($target_dir,0,-1));
            // print_r($supplier_array_file);die;
            $file_path_string = '';
            foreach($supplier_array_file as $row)
            {
                $file_path_string .= $row.'/';
                if(!file_exists($file_path_string))
                {
                    mkdir($file_path_string);
                    chmod($file_path_string,0777);
                }


            }
            // echo $target_dir;
            // echo '<br>'.$file_path_string;
            // die;

            $target_file = $target_dir . basename($_FILES["upload_cn_doc"]["name"]);
            // echo $target_file;die;
            $uploadOk = 1;
            $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $file_type_array = array('pdf');
            // Check if image file is a actual image or fake image
            // echo $file_type;die;      
            if (!in_array($file_type,$file_type_array)) 
            {
                $this->session->set_flashdata('message', 'Wrong File Type');
                redirect('panda_prdncn/prdncn_child?trans='.$refno.'&loc='.$loc.'&type='.$doc_type);
            } 

            if ($_FILES["upload_cn_doc"]["size"] >= 2000000) 
            {
                echo "Sorry, your file is too large.";
            }  
            // echo $target_dir.$refno.'.pdf';die;
            if (move_uploaded_file($_FILES["upload_cn_doc"]["tmp_name"], $target_dir.$refno.'.pdf')) 
            {
                chmod($target_dir.$refno.'.pdf',0777);
                $data = array(
                        'customer_guid' => $customer_guid,
                        'supplier_guid' => $supplier_guid->row('supplier_guid'),
                        'doc_type' => 'prdn_cn',
                        'refno' => $refno,
                        'file_path' => $target_dir.$refno.'.pdf',
                        'file_name' => $refno.'.pdf',
                        'ori_file_name' => $_FILES["upload_cn_doc"]["tmp_name"],
                        'device_type' => 'browser',
                        'created_by' => $this->session->userdata('user_guid'),
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                        'updated_by' => $this->session->userdata('user_guid'),
                        'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                );
                $this->db->replace($database1.'.upload_doc_log',$data);                
                $this->session->set_flashdata('message', "The file ". $refno. " has been uploaded.");
                redirect('panda_prdncn/prdncn_child?trans='.$refno.'&loc='.$loc.'&type='.$doc_type);
            }            
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    } 

    public function upload_grn_cn()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 2;die;
            // print_r($this->input->post());die;
            $database = 'lite_b2b';
            $database1 = 'b2b_summary';
            $refno = $this->input->post('upload_cn_refno');
            $doc_type = $this->input->post('upload_prdn_cn_status');
            $loc = $this->input->post('upload_prdn_cn_loc');
            $transtype = $this->input->post('upload_prdn_cn_transtype');
            $customer_guid = $this->session->userdata('customer_guid');
            $supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.grmain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
            // echo $this->db->last_query();die;
            // echo $refno.$doc_type.'---'.$loc;die;
            $target_dir = "retailer_file/".$customer_guid."/grn_cn/".$supplier_guid->row('supplier_guid')."/";
            $supplier_array_file = explode('/',substr($target_dir,0,-1));
            // print_r($supplier_array_file);die;
            $file_path_string = '';
            foreach($supplier_array_file as $row)
            {
                $file_path_string .= $row.'/';
                if(!file_exists($file_path_string))
                {
                    mkdir($file_path_string);
                    chmod($file_path_string,0777);
                }


            }
            // echo $target_dir;
            // echo '<br>'.$file_path_string;
            // die;

            $target_file = $target_dir . basename($_FILES["upload_grn_cn_doc"]["name"]);
            // echo $target_file;die;
            $uploadOk = 1;
            $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $file_type_array = array('pdf');
            // Check if image file is a actual image or fake image
            // echo $file_type;die;      
            if (!in_array($file_type,$file_type_array)) 
            {
                $this->session->set_flashdata('message', 'Wrong File Type');
                redirect('panda_gr/gr_child?trans='.$refno.'&loc='.$loc.'&accpt_gr_status='.$doc_type);
            } 

            if ($_FILES["upload_grn_cn_doc"]["size"] >= 2000000) 
            {
                echo "Sorry, your file is too large.";
            }  
            // echo $target_dir.$refno.'.pdf';die;
            if (move_uploaded_file($_FILES["upload_grn_cn_doc"]["tmp_name"], $target_dir.$refno.'-'.$transtype.'.pdf')) 
            {
                chmod($target_dir.$refno.'-'.$transtype.'.pdf',0777);
                $data = array(
                        'customer_guid' => $customer_guid,
                        'supplier_guid' => $supplier_guid->row('supplier_guid'),
                        'doc_type' => 'grn_cn',
                        'refno' => $refno.'-'.$transtype,
                        'file_path' => $target_dir.$refno.'-'.$transtype.'.pdf',
                        'file_name' => $refno.'-'.$transtype.'.pdf',
                        'ori_file_name' => $_FILES["upload_grn_cn_doc"]["tmp_name"],
                        'device_type' => 'browser',
                        'created_by' => $this->session->userdata('user_guid'),
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                        'updated_by' => $this->session->userdata('user_guid'),
                        'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                );
                $check_supp_guid = $supplier_guid->row('supplier_guid');
                $check_refno = $refno.'-'.$transtype;
                $check_log = $this->db->query("SELECT * FROM $database1.upload_doc_log WHERE customer_guid = '$customer_guid' AND supplier_guid='$check_supp_guid' AND doc_type = 'grn_cn' AND refno = '$check_refno'");
                if($check_log->num_rows() <= 0)
                {
                        $this->db->replace($database1.'.upload_doc_log',$data);
                }
                else
                {
                        $this->db->replace($database1.'.upload_doc_log',$data);
                        $this->db->query("UPDATE $database1.upload_doc_log SET updated_by ='".$this->session->userdata('user_guid')."',updated_at = NOW() WHERE customer_guid = '$customer_guid' AND supplier_guid='$check_supp_guid' AND doc_type = 'grn_cn' AND refno = '$check_refno'");
                }                 
                $this->session->set_flashdata('message', "The file ". $refno.'-'.$transtype. " has been uploaded.");
                redirect('panda_gr/gr_child?trans='.$refno.'&loc='.$loc.'&accpt_gr_status='.$doc_type);
            }            
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }     

    public function upload_consign_inv()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 2;die;
            // print_r($this->input->post());die;
            $database = 'lite_b2b';
            $database1 = 'b2b_summary';
            $refno = $this->input->post('upload_sup_code');
            $doc_type = $this->input->post('upload_consign_inv_attachment_doc_type');
            $loc = $this->input->post('upload_consign_inv_loc');
            $period_code = $this->input->post('upload_consign_inv_period_code');
            $upload_consign_inv_trans_list = $this->input->post('upload_consign_inv_trans_list');
            $upload_consign_inv_statuss = $this->input->post('upload_consign_inv_statuss');
            $customer_guid = $this->session->userdata('customer_guid');
            $database_array = $this->db->query("SELECT b2b_database FROM acc WHERE acc_guid = '$customer_guid'");
            $database_string = $database_array->row('b2b_database');
            $supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM $database_string.acc_trans a INNER JOIN lite_b2b.`set_supplier_group` b ON a.supcus_code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.unique_key = '$refno' AND LEFT(a.date_trans,7) = '$period_code'");
            // echo $this->db->last_query();die;
            // print_r($supplier_guid->result());die;
            // echo $refno.$doc_type;die;
            $target_dir = "retailer_file/".$customer_guid."/consign_inv/".$supplier_guid->row('supplier_guid')."/";
            $supplier_array_file = explode('/',substr($target_dir,0,-1));
            // print_r($supplier_array_file);die;
            $file_path_string = '';
            foreach($supplier_array_file as $row)
            {
                $file_path_string .= $row.'/';
                if(!file_exists($file_path_string))
                {
                    mkdir($file_path_string);
                    chmod($file_path_string,0777);
                }


            }
            // echo $target_dir;
            // echo '<br>'.$file_path_string;
            // die;

            $target_file = $target_dir . basename($_FILES["upload_consign_inv_attachment"]["name"]);
            // echo $target_file;die;
            $uploadOk = 1;
            $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $file_type_array = array('pdf');
            // Check if image file is a actual image or fake image
            // echo $file_type;die;      
            if (!in_array($file_type,$file_type_array)) 
            {
                $this->session->set_flashdata('message', 'Wrong File Type');
                redirect('Consignment_report/consignment_sales_statement_by_supcode_list?trans='.$upload_consign_inv_trans_list.'&status='.$upload_consign_inv_statuss.'&loc='.$loc.'&period_code='.$period_code.'&supcode='.$refno);
            } 

            if ($_FILES["upload_consign_inv_attachment"]["size"] >= 40000000) 
            {
                $this->session->set_flashdata('message', 'Sorry, your file is too large.');
                redirect('Consignment_report/consignment_sales_statement_by_supcode_list?trans='.$upload_consign_inv_trans_list.'&status='.$upload_consign_inv_statuss.'&loc='.$loc.'&period_code='.$period_code.'&supcode='.$refno);
            }  
            // echo $target_dir.$refno.'.pdf';die;
            $file_name_save = $refno.'_'.$period_code;
            if (move_uploaded_file($_FILES["upload_consign_inv_attachment"]["tmp_name"], $target_dir.$file_name_save.'.pdf')) 
            {
                chmod($target_dir.$refno.'.pdf',0777);
                $data = array(
                        'customer_guid' => $customer_guid,
                        'supplier_guid' => $supplier_guid->row('supplier_guid'),
                        'doc_type' => 'consign_inv',
                        'refno' => $file_name_save,
                        'file_path' => $target_dir.$file_name_save.'.pdf',
                        'file_name' => $file_name_save.'.pdf',
                        'ori_file_name' => $_FILES["upload_consign_inv_attachment"]["tmp_name"],
                        'device_type' => 'browser',
                        'created_by' => $this->session->userdata('user_guid'),
                        'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                        'updated_by' => $this->session->userdata('user_guid'),
                        'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                );
                $this->db->replace($database1.'.upload_doc_log',$data);                
                $this->session->set_flashdata('message', "The file has been uploaded.");
                redirect('Consignment_report/consignment_sales_statement_by_supcode_list?trans='.$upload_consign_inv_trans_list.'&status='.$upload_consign_inv_statuss.'&loc='.$loc.'&period_code='.$period_code.'&supcode='.$refno);
            }            
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }      
}
