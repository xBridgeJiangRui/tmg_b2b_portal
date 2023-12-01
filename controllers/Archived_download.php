<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Archived_download extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer'); 
         
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $acc_guid = $_SESSION['customer_guid'];

            $get_acc = $this->db->query("SELECT acc_name ,acc_guid FROM lite_b2b.acc WHERE isactive = '1' ORDER BY acc_name ASC");

            $data = array(
                'get_acc' => $get_acc->result(),
            );
            $this->load->view('header');
            $this->load->view('download_pdf', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function fetch_other_doc()
    {   
        $acc_guid = $this->input->post('acc_guid');

        $query = $this->db->query("SELECT * FROM lite_b2b.other_doc_setting WHERE customer_guid = '$acc_guid' ORDER BY seq ASC");

        if($query->num_rows() == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'This Retailer No have Accouting Document.',
            );
    
            echo json_encode($data); 
            exit();
        }

        $data = array(
            'para1' => 'true',
            'query' => $query->result(),
        );

        echo json_encode($data);
        
    } 

    public function fetch_report()
    {   
        $doc_type = $this->input->post('doc_type');
        $refno = strtoupper($this->input->post('refno'));
        $acc_guid = $this->input->post('acc_guid');
        $supcode = $this->input->post('scode');
        $other_doc_type = $this->input->post('other_doc_type');
        $trans_type = '';

        $get_customer = $this->db->query("SELECT SUBSTRING(file_path,10,20) AS file_path, rest_url FROM lite_b2b.acc WHERE acc_guid = '$acc_guid' AND isactive = '1'");

        $customer = $get_customer->row('file_path');

        if($customer == '' || $customer == 'null' || $customer == null)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Invalid File Path Name.',
            );
    
            echo json_encode($data);
            exit();
        }

        if($doc_type == 'pomain')
        {
            $type = 'PO';
            $column_scode = 'SCode';
            $module_link = 'panda_po_2';
            $and_refno = "AND refno = '$refno' ";
        }
        else if($doc_type == 'grmain')
        {
            $type = 'GRN';
            $column_scode = 'Code';
            $module_link = 'panda_gr';
            $and_refno = "AND refno = '$refno' ";
        }
        else if($doc_type == 'grmain_dncn')
        {
            $type = 'GRDA';
            $column_scode = 'GRDA_DATA';
            $module_link = 'panda_grda';
        }
        else if($doc_type == 'dbnotemain')
        {
            $type = 'PRDN';
            $column_scode = 'Code';
            $module_link = 'panda_prdncn';
            $and_refno = "AND refno = '$refno' ";
        }
        else if($doc_type == 'cnnotemain')
        {
            $type = 'PRCN';
            $column_scode = 'Code';
            $module_link = 'panda_pdncn';
            $and_refno = "AND refno = '$refno' ";
        }
        else if($doc_type == 'pdn_amt')
        {
            $doc_type = 'cndn_amt';
            $type = 'PDN';
            $column_scode = 'code';
            $trans_type = "AND trans_type = 'PDNAMT'";
            $module_link = 'panda_prdn';
            $and_refno = "AND refno = '$refno' ";
        }
        else if($doc_type == 'pcn_amt')
        {
            $doc_type = 'cndn_amt';
            $type = 'PCN';
            $column_scode = 'code';
            $trans_type = "AND trans_type = 'PCNAMT'";
            $module_link = 'panda_prcn';
            $and_refno = "AND refno = '$refno' ";
        }
        else if($doc_type == 'promo_taxinv')
        {
            $type = 'PROMO';
            $column_scode = 'sup_code';
            $module_link = 'panda_pci';
            $and_refno = "AND inv_refno = '$refno' ";
        }
        else if($doc_type == 'discheme_taxinv')
        {
            $type = 'DISCHEME';
            $column_scode = 'sup_code';
            $module_link = 'panda_di';
            $and_refno = "AND inv_refno = '$refno' ";
        }
        else if($doc_type == 'other_doc')
        {
            $type = $other_doc_type;
            $and_refno = "AND refno = '$refno' ";
            // if($acc_guid == '8D5B38E931FA11E79E7E33210BD612D3')
            // {
            //     $column_scode = 'supcode';
            // }
            // else
            // {
                $column_scode = 'supcode';
            //}
            
        }
        else
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Invalid Document Type',
            );
    
            echo json_encode($data);
            exit();
        }

        if($supcode == '' || $supcode == null || $supcode == 'null')
        {   
            if($column_scode == 'GRDA_DATA')
            {
                $supcode = $this->db->query("SELECT b.code as scode FROM b2b_archive.$doc_type a INNER JOIN b2b_archive.grmain b ON a.refno = b.refno WHERE a.customer_guid = '$acc_guid' AND a.refno = '$refno' ")->row('scode');
            }
            else if($doc_type == 'promo_taxinv')
            {
                $get_supcode = $this->db->query("SELECT * FROM b2b_archive.$doc_type a WHERE a.customer_guid = '$acc_guid' AND a.promo_refno = '$refno' ");

                if($get_supcode->num_rows() == 0)
                {
                    $get_supcode = $this->db->query("SELECT * FROM b2b_archive.$doc_type a WHERE a.customer_guid = '$acc_guid' AND a.inv_refno = '$refno' ");
                }
                //echo $this->db->last_query(); die;
                if($acc_guid == '907FAFE053F011EB8099063B6ABE2862' || $acc_guid == '1F90F5EF90DF11EA818B000D3AA2CAA9' || $acc_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93')
                {
                    $refno = $get_supcode->row('inv_refno');
                }
                else
                {
                    $refno = $get_supcode->row('promo_refno');
                }

                $supcode = $get_supcode->row($column_scode);
            }
            else
            {
                $get_supcode = $this->db->query("SELECT * FROM b2b_archive.$doc_type WHERE customer_guid = '$acc_guid' $and_refno $trans_type ");
                $supcode = $get_supcode->row($column_scode);
            }
        
            if($supcode == '' || $supcode == 'null' || $supcode == null)
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Cant Find Data in Archived Database. Please insert supplier code.',
                );
        
                echo json_encode($data);
                exit();
            }
        }
        
        if($doc_type != 'other_doc')
        {
            // $type = 'GRDA';
            // $refno = 'ASTGR22030795';
            // $supcode = 'V020';
            $redirect = 'true';
            //$replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$supcode'), 'refno' , '$refno') AS query FROM menu where module_link = '$module_link'")->row('query');
            // echo $this->db->last_query();die;

            //$virtual_path = $this->db->query("SELECT CONCAT(file_host,file_path) AS file_path FROM acc WHERE acc_guid = '$acc_guid'")->row('file_path');

            //$url = $virtual_path.'/'.$replace_var.'.pdf';
            $url = "http://52.163.112.202/rest_api/index.php/Panda_b2b_/Document?type=".$type."&refno=".$refno."&supcode=".$supcode."&customer=".$customer."";
            $url2 = "http://52.163.112.202/rest_api/index.php/Panda_b2b_/Document_new?type=".$type."&refno=".$refno."&supcode=".$supcode."&customer=".$customer."";
            //$url = "https://api.xbridge.my/rest_api/index.php/Panda_b2b_/Document?type=".$type."&refno=".$refno."&supcode=".$supcode."&customer=".$customer."";
            //print_r($url); die;
        }
        else
        {
            //$refno = '';
            //$type = '';
            //$supcode = '';
            $url2 = '';
            $redirect = 'true';
            $doctime = $this->db->query("SELECT doctime FROM b2b_archive.$doc_type WHERE customer_guid = '$acc_guid' $and_refno ")->row('doctime');
            $acc_sys_type = $this->db->query("SELECT accounting_doc FROM acc WHERE acc_guid = '$acc_guid'")->row('accounting_doc');
            if($doctime == '' || $doctime == 'null' || $doctime == null)
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Data Not Found.',
                );
        
                echo json_encode($data);
                exit();
            }

            $doctime = str_replace(' ', '%20', $doctime);
            $path = $get_customer->row('rest_url');
            if($path == '' || $path == 'null' || $path == null)
            {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Path Not Found.',
                );
        
                echo json_encode($data);
                exit();
            }
            //$path = "http://18.139.87.215/rest_api/index.php/return_json";

            //$url = "http://18.139.87.215/rest_api/index.php/return_json/Document?refno=THSBAT21000004&doctype=PVV&supcode=S404&doctime=2021-01-29%2023:15:02";

            if ($acc_sys_type == 'nav') {
                $url = "".$path."/Document?refno=". urlencode($refno)."&doctype=".$type."&supcode=".$supcode."&doctime=".$doctime."";
            } else {
                $url = "".$path."/Document_autocount?refno=". urlencode(str_replace('/', '', $refno))."&doctype=".$type."&supcode=".$supcode."&doctime=".$doctime."";
            }
            
            //print_r($url); die;
        }

        $data = array(
            'para1' => 'true',
            'redirect' => $redirect,
            'url' => $url,
            'url2' => $url2,
        );

        echo json_encode($data);
    }

}
