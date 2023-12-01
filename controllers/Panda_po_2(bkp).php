<?php
class panda_po_2 extends CI_Controller
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
        

    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $setsession = array(
                'frommodule' => 'panda_po_2',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            if(isset($_SESSION['from_other']) == 0 )
            {
                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc']);
                /*
                // if is HQ, show all location
                if($_SESSION['user_group_name'] != 'SUPER_ADMIN' && $_REQUEST['loc'] != 'HQ' )
                {
                    $result = $this->db->query("SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %W') as podate,date_format(expiry_date, '%Y-%m-%d %W') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, status, rejected_remark from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."'and scode IN (".$_SESSION['query_supcode'].")  and location = '".$_REQUEST['loc']."' and status = '' ");
                };
                // if is not HQ, show that specific data
                if($_SESSION['user_group_name'] != 'SUPER_ADMIN' && $_REQUEST['loc'] == 'HQ' )
                {
                    $result = $this->db->query("SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %W') as podate,date_format(expiry_date, '%Y-%m-%d %W') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, status, rejected_remark from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."' and scode IN (".$_SESSION['query_supcode'].") and status = '' ");
                };

                if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
                {
                   $result = $this->db->query("SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %W') as podate,date_format(expiry_date, '%Y-%m-%d %W') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, status, rejected_remark from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."' and status = ''");
                };
           
                $data = array (
                    'result' => $result,
                    'accepted' => site_url('general/view_status?status=accepted&loc='.$_REQUEST['loc']),
                    'rejected' => site_url('general/view_status?status=rejected&loc='.$_REQUEST['loc']),
                    'other' => site_url('general/view_status?status=other&loc='.$_REQUEST['loc']),
                    );
                $check_module = $_SESSION['frommodule'];
                $this->General_model->load_page($data,$check_module);*/
                 
                $this->panda->get_uri();
               
            }
            else
            {
                //unset($_SESSION['from_other']);
                if($_REQUEST['status'] == '')
                {
                    unset($_SESSION['from_other']);
                    redirect('panda_po_2?loc='.$_REQUEST['loc']);
                };
                $this->panda->get_uri();
                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc']);
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    } 

    public function po_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            
            $check_scode = $this->db->query("SELECT scode from b2b_summary.pomain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            $file_headers = @get_headers($filename);

            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status, rejected_remark from b2b_summary.pomain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");

            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");


            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Purchase Order',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'set_admin_code' =>  $set_admin_code,
            );
            
            $this->load->view('header');       
            $this->load->view('po/panda_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function po_child_old()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $setsession = array(
                'refno' =>$_REQUEST['trans'],
                );
        $this->session->set_userdata($setsession);
        $refno = $_SESSION['refno'];
        $session_data = $this->session->userdata('customers');

        $data = array (
            'main' => $this->db->query("SELECT refno,podate,deliverdate,expiry_date,location,tax_code_purchase,gst_tax_rate,total,
             gst_tax_sum,total_include_tax,status from ".$session_data['customer_db'].".pomain where refno = '$refno' "),
            'child' => $this->db->query("SELECT line, pricetype,itemcode,barcode,description,
            concat(qty,'',LOWER(um)) as qty, unitprice, discamt,
            ROUND((hcost_po)/qty,4) AS unit_disc_prorate, 
            ROUND(IF(hcost_po=0,netunitprice,(totalprice-(hcost_po))/qty),4) AS unit_price_bfr_tax,
            ROUND((totalprice-(hcost_po)),2) AS total_price_bfr_tax, gst_tax_amount, 
            ROUND(((totalprice-(hcost_po))+gst_tax_amount),2) AS gst_unit_total,
            ifnull(reason, '') as reason 
            FROM ".$session_data['customer_db'].".pochild where refno = '$refno' order by line asc"),
            'child_reject' => $this->db->query("SELECT reason from set_setting where module_name = 'PO'"),
            );

        $this->load->view('header');
        $this->load->view('panda_menu_view.php');
        $this->load->view('po/panda_po_child',$data);
        $this->load->view('po/panda_po_modal', $data);
        $this->load->view('footer');
        }
        else
        {
            
            redirect('#');
        }
    }
// things to do
/*    PO
-- might need to change whole outline po ----------//done
-- PO issue date ----------------------------------//done
-- PO when reach expired will flag expired ----------//done
-- need reason when reject WHOLE PO ----------------//done
-- need reason when reject line PO -----------------//done
-- no stock, wrong SKU, wrong pricing, short delivery date, minimum qty order, wrong packsize ------------------------------------------------------//done
-- when PO generated GRN, change status to GReceived  //done
-- PO Status got pending, accepted, partially accepted(if not reject at least 1 line), rejected,  expired, completed ------------------------------------//done
-- make tab
-- make status dynamically change by them
*/
    public function check_accept()
    {
        if($this->session->userdata('logged_in') == true)
        {
            $session_data = $this->session->userdata('logged_in');
            $user_guid = $data['user_guid'] = $session_data['user_guid'];
            $session_data = $this->session->userdata('branch');
            $branch_code = $data['branch_code'] = $session_data['branch_code'];
            $session_data = $this->session->userdata('customers');
            $customer = $data['customer'] = $session_data['customer'];
            $customer_guid = $data['customer_guid'] = $session_data['customer_guid'];
            $session_data = $this->session->userdata('customers');
        
                $reason = $this->input->post('reason[]');
                $line = $this->input->post('line[]');
                $itemcode = $this->input->post('itemcode');
    
                $pochild = array();
                    foreach($line as $row => $id)
                    {
                        $pochild[] = [
                           'line' => $id, 
                           'reason' => $reason[$row],
                           ];
                    }

         
                $table= $session_data['customer_db'].".pomain";
                $db_update = $session_data['customer_db'].".pochild";

                $check_status = $this->db->query("SELECT status from ".$session_data['customer_db'].".pomain where refno  = '".$_SESSION['refno']."'");

                if ($check_status->row('status') == 'Pending')
                {

               $data = array (
                 'status' => 'PO_Accepted',
                    );
                $this->Po_model->update_accepted($table,$data);
                $this->db->where_in('refno', $_SESSION['refno']);
                $this->db->update_batch($db_update, $pochild, 'line');
                //echo $this->db->last_query();die;

                    $check_child = $this->db->query("SELECT REPLACE(GROUP_CONCAT(reason), ',', '')  as reason from ".$session_data['customer_db'].".pochild where refno = '".$_SESSION['refno']."' group by refno");
                    if ($check_child->row('reason') != '' )
                    {
                        $p_accepted = array (
                            'status' => 'Partially Accepted',
                        );
                        $this->Po_model->update_accepted($table,$p_accepted);
                       // echo $this->db->last_query();die;
                        $this->session->set_flashdata('message', 'PO is Partially Accepted.');
                        redirect('panda_po_2/po_child?trans='.$_SESSION['refno']);
                    }
                    else
                    {
                        $this->session->set_flashdata('message', 'PO Accepted.');
                        redirect('panda_po_2/po_child?trans='.$_SESSION['refno']);
                    };
                //echo $this->db->last_query();die;
            }
            else
            {
                $this->session->set_flashdata('message', 'Document status is not Pending. Please make sure PO status is Pending before making any changes.');
                redirect('panda_po_2/po_child?trans='.$_SESSION['refno']);
            };


        }
        else
        {
            redirect('#');
        }
    }

    public function check_rejected()
    {
         if($this->session->userdata('logged_in') == true)
        {
            $session_data = $this->session->userdata('logged_in');
            $user_guid = $data['user_guid'] = $session_data['user_guid'];
            $username = $data['username'] = $session_data['username'];
            $session_data = $this->session->userdata('branch');
            $branch_code = $data['branch_code'] = $session_data['branch_code'];
            $session_data = $this->session->userdata('customers');
            $customer = $data['customer'] = $session_data['customer'];
            $customer_guid = $data['customer_guid'] = $session_data['customer_guid'];
            $session_data = $this->session->userdata('customers');

            $table = $session_data['customer_db'].".pomain";
            
             $check_status = $this->db->query("SELECT status from ".$session_data['customer_db'].".pomain where refno  = '".$_SESSION['refno']."'");

                if ($check_status->row('status') == 'Pending')
                {

                $data = array (
                    'status' => 'PO_Rejected',
                    'rejected_remark' => $this->input->post('rejected'),
                    'rejected' => '1',
                    'rejected_by' => $username,
                    'rejected_at' => $this->db->query("SELECT now() as now")->row('now'),
                        );
                    $this->Po_model->update_accepted($table,$data);
                    redirect('panda_po_2/po_child?trans='.$_SESSION['refno']);
                }
                else
                {
                     $this->session->set_flashdata('message', 'Document status is not Pending. Please make sure PO status is Pending before making any changes.');
                    redirect('panda_po_2/po_child?trans='.$_SESSION['refno']);
                }

        }
        else
        {
            redirect('#');
        }
    }




} // nothing after this
?>






