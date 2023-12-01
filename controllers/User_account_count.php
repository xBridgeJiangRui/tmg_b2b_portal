<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_account_count extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('session');
        $this->load->model('Datatable_model');
        $this->load->model('Send_email_model');
    }

    public function index()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login() ) 
        {
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid']; //030E3C41EAF011ECA43DB2C55218ACED
            $user_id = $_SESSION['user_id'];

            $get_billing = $this->db->query("SELECT a.* FROM b2b_invoice.inv_doc a WHERE a.inv_date BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND CURDATE()");
            
            $data = array(
                'get_billing' => $get_billing->result(),
            );

            $this->load->view('header');
            $this->load->view('user_account/user_supplier_count', $data);
            $this->load->view('footer');

        } else {
            $this->session->set_flashdata('message', 'You have not rights to access.');
            redirect('dashboard');
        }
    }

    public function user_count_tb()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login()) 
        {
            $doc = 'user_count';
            $user_guid = $_SESSION['user_guid'];
            $customer_guid = $_SESSION['customer_guid'];
            // $supplier_guid = $this->input->post("supplier_guid");
            // $selected_customer_guid = $this->input->post("selected_customer_guid");

            $query_data = "SELECT b.acc_name,c.supplier_name,a.user_count,a.invoice_number,a.created_at,a.created_by,a.guid,a.customer_guid,a.supplier_guid 
            FROM lite_b2b.set_supplier_user_count a
            INNER JOIN lite_b2b.acc b
            ON a.customer_guid = b.acc_guid
            INNER JOIN lite_b2b.set_supplier c
            ON a.supplier_guid = c.supplier_guid
            ORDER BY a.created_at ASC";

            // print_r($query_data); die;
            $sql = "SELECT * FROM (
                $query_data
            ) zzz ";
            // echo $sql; die;
            // echo $this->db->last_query();die;
            
            $query = $this->Datatable_model->datatable_main($sql,'',$doc);
            // echo $this->db->last_query(); die;
            $fetch_data = $query->result();
            $data = array();
            if (count($fetch_data) > 0) {
                foreach ($fetch_data as $row) {
                    $tab = array();

                    $tab['acc_name'] = $row->acc_name;
                    $tab['supplier_name'] = $row->supplier_name;
                    $tab['guid'] = $row->guid;
                    $tab['customer_guid'] = $row->customer_guid;
                    $tab['supplier_guid'] = $row->supplier_guid;
                    $tab['user_count'] = $row->user_count;
                    $tab['invoice_number'] = $row->invoice_number;
                    $tab['created_at'] = $row->created_at;
                    $tab['created_by'] = $row->created_by;

                    $data[] = $tab;
                }
            } else {
                $data = '';
            }

            $output = array(
                "draw"                =>     intval($_POST["draw"]),
                "recordsTotal"        =>     intval($this->Datatable_model->general_get_all_data($sql, $doc)),
                "recordsFiltered"     =>     intval($this->Datatable_model->general_get_filtered_data($sql, $doc)),
                "data"                =>     $data
            );

            echo json_encode($output);
        } 
        else 
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function update_user_count()
    {
        $user_id = $_SESSION['user_id'];
        $edit_guid = $this->input->post('edit_guid');
        $edit_customer_guid = $this->input->post('edit_customer_guid');
        $edit_supplier_guid = $this->input->post('edit_supplier_guid');
        $edit_billing_info = $this->input->post('edit_billing_info');

        $check_data = $this->db->query("SELECT * FROM lite_b2b.set_supplier_user_count WHERE `guid` = '$edit_guid' AND customer_guid = '$edit_customer_guid' AND supplier_guid = '$edit_supplier_guid'")->result_array();

        if(count($check_data) == 0)
        {
            $data = array(
                'para1' => 'false',
                'msg' => 'Data Not Found.',
            );    
            echo json_encode($data);   
            exit();
        }

        $data = array(
            'invoice_number' => $edit_billing_info,
            'updated_at' => $this->db->query("SELECT NOW() as now")->row('now'),
            'updated_by' => $user_id,
        );
        $this->db->where('guid', $edit_guid);
        $this->db->where('customer_guid', $edit_customer_guid);
        $this->db->where('supplier_guid', $edit_supplier_guid);
        $this->db->update('lite_b2b.set_supplier_user_count',$data);

        $error = $this->db->affected_rows();

        if($error > 0)
        {
            $data = array(
               'para1' => 'true',
               'msg' => 'Update Successfully.',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 'false',
            'msg' => 'Error Process.',
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function fetch_user_tb()
    {
        $info_customer_guid = $this->input->post('info_customer_guid');
        $info_supplier_guid = $this->input->post('info_supplier_guid');

        $query_table_data = $this->db->query("SELECT e.acc_name,d.supplier_name,a.user_name,a.user_id,f.user_group_name
        FROM 
        lite_b2b.set_user a
        INNER JOIN lite_b2b.set_supplier_user_relationship b
        ON a.user_guid = b.user_guid
        INNER JOIN lite_b2b.set_supplier_group c
        ON b.supplier_guid = c.supplier_guid
        AND b.customer_guid = c.customer_guid
        INNER JOIN lite_b2b.set_supplier d
        ON c.supplier_guid = d.supplier_guid
        INNER JOIN lite_b2b.acc e
        ON b.customer_guid = e.acc_guid
        INNER JOIN lite_b2b.set_user_group f
        ON a.user_group_guid = f.user_group_guid
        WHERE a.acc_guid = '$info_customer_guid'
        AND d.supplier_guid = '$info_supplier_guid' 
        AND a.hide_admin = '0'
        GROUP BY a.user_id,a.acc_guid 
        ORDER BY f.user_group_name ASC");

        $data = array(
            'query_table_data' => $query_table_data->result(),
        );

        echo json_encode($data);
    }
}
?>

