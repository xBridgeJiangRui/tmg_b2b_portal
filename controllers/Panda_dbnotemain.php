<?php
class Panda_dbnotemain extends CI_Controller
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
        $this->load->model('Dbnotemain_model');
    }

    public function index()
    {
        //capture customer/branch selected
        $session_data = $this->session->userdata('logged_in');
        $data['user_guid'] = $session_data['user_guid'];
        $session_data = $this->session->userdata('branch');
        $data['branch_code'] = $session_data['branch_code'];
        $session_data = $this->session->userdata('customers');
        $data['customer'] = $session_data['customer'];

        //call the model function to get the department data
        $data  = array ( 'dbnotelist' => $this->db->query(
            "SELECT a.refno,a.location,a.docdate,a.code,a.name,ROUND(a.amount,2) as amount,ROUND(a.gst_tax_sum,2) as gst_tax_sum, ROUND(a.amount+a.gst_tax_sum,2)  as total_incl_tax ,export_account
             FROM dbnotemain AS a 
             INNER JOIN customer_profile AS b 
             ON a.customer_guid = b.customer_guid 
             INNER JOIN customer_supcus c
             ON b.customer_guid = c.customer_guid AND a.code = c.code
             INNER JOIN user_customer d
             ON c.supcus_guid = d.supcus_guid
             where ibt = 0 and a.location = '".$data['branch_code']."' and b.customer_name = '".$data['customer']."' and d.user_guid = '".$data['user_guid']."' order by docdate desc"),
        );
        //Break the array to get the 2nd level array size
        //load the department_view
        $this->load->view('header');
        $this->load->view('panda_menu_view.php');
        $this->load->view('Panda_dbnotemain_list_view',$data);
        $this->load->view('footer');
    }

    public function dbnotechild()
    {
        if($this->session->userdata('logged_in') == true)
        {
            $refno = $_REQUEST['refno'];
            $check_child = $this->db->query("SELECT refno, line, itemcode, description, qty, ROUND(unitprice,2) as unitprice, ROUND(totalprice,2) as totalprice, reason, gst_tax_code, ROUND(gst_tax_amount,2)as gst_tax_amount, ROUND(totalprice+gst_tax_amount,2) as total_incl_tax from dbnotechild where refno = '$refno'");
            if ($check_child->num_rows() == '0')
            {
                site_url('Panda_dbnotemain');
            }
            else
            {
                $data = array (
                    'child' => $check_child,
                    'main' => $this->db->query("SELECT refno,location,docdate,code,name,ROUND(amount,2) as amount,ROUND(gst_tax_sum,2) as gst_tax_sum, ROUND(amount+gst_tax_sum,2)  as total_incl_tax ,export_account FROM dbnotemain where refno = '$refno' "),
                    );
                $this->load->view('header');
                $this->load->view('panda_menu_view.php');
                $this->load->view('Panda_dbnotechild_view',$data);
                $this->load->view('footer');
            }
        }
        else
        {
            redirect('#');
        }
    }



    

    function accept_po()
    {

        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result  = $this->Dbnotemain_model->get_po_details('select status from pomain where refno = "'.$refno.'"
                                                    and status = "Pending"');

        if($result)
        {
            $query=$this->Dbnotemain_model->accept_po();
            $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
            
            redirect($_SERVER['HTTP_REFERER'],'refresh');

        }
        else
        {
            $query=$this->Dbnotemain_model->accept_po();
            $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
            
            redirect($_SERVER['HTTP_REFERER'],'refresh');

        }
    }


    function reject_po()
    {

        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result  = $this->Dbnotemain_model->get_po_details('select status from pomain where refno = "'.$refno.'"
                                                    and status = "Pending"');

        if($result)
        {
            $query=$this->Dbnotemain_model->reject_po();
            $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
            
            redirect($_SERVER['HTTP_REFERER'],'refresh');


        }
        else
        {
            $query=$this->Dbnotemain_model->reject_po();
            $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
            
            redirect($_SERVER['HTTP_REFERER'],'refresh');

        }

    }


    function download_po()
    {

        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result  = $this->Dbnotemain_model->get_po_details('select status from pomain where refno = "'.$refno.'"
                                                    and status = "Pending"');

        $result2  = $this->Dbnotemain_model->get_po_details('select status from pomain where refno = "'.$refno.'"
                                                    and status = "Completed"');

        if($result)
        {

            $data = array("query" => 'Access Denied! Please proceed Accept/Reject PO before Download PO');
            $this->session->set_userdata('po_status', $data);

            redirect($_SERVER['HTTP_REFERER'],'refresh');
        }
        else
        {
            if($result2)
            {
                $query=$this->Dbnotemain_model->download_po();

                if($query == 1)
                {
                    redirect('Panda_dbnotemain/index','refresh');
                }
                else
                {
                    $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
                    redirect($_SERVER['HTTP_REFERER'],'refresh');
                }

            }
                
            else
            {
                $query=$this->Dbnotemain_model->download_po();

                if($query == 1)
                {
                    redirect('Panda_dbnotemain/index','refresh');
                }
                else
                {
                    $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
                    redirect($_SERVER['HTTP_REFERER'],'refresh');
                }
            } 
        }

    }



}
?>