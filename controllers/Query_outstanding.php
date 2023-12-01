<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Query_outstanding extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Acc_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->api_url = '127.0.0.1/rest_b2b/index.php';
    }

    public function get_outstanding_old()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            // $user_guid = '00647A6F016511EBAAD5000D3AA2838A';
            $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
            $result = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM lite_b2b.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' GROUP BY a.`customer_guid`, a.`supplier_guid`");
            // echo $this->db->last_query();die;
            // $result2 = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM lite_b2b.`query_outstanding_one_off` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' GROUP BY a.`customer_guid`, a.`supplier_guid`");
            // $result = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM lite_b2b.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND a.supplier_guid = '8CE7320BC71C11E991FD000D3AA2838A' GROUP BY a.`customer_guid`, a.`supplier_guid`");
            // $result = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM lite_b2b.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND a.supplier_guid = '0E62E98975C411E887B5000D3AA2838A' GROUP BY a.`customer_guid`, a.`supplier_guid`");            

            // echo $this->db->last_query();die;
            // $result = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM daniel_invoice_email.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND b.user_guid = 'B153DE64038F11EB915C000D3AA2838A' GROUP BY a.`customer_guid`, a.`supplier_guid`");
            // echo $this->db->last_query();die;
            // print_r($result->result());die;
            // $result2 = $this->db->query("SELECT a.*, DATE_FORMAT(Registration_date, '%e-%M-%Y') AS reg_date, DATE_FORMAT(NOW(), '%e-%M-%Y') AS now FROM daniel_invoice_email_one_off.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '1F90F5EF90DF11EA818B000D3AA2CAA9' AND b.user_guid = '0665B3069F0011EA9E39000D3AA2838A' GROUP BY a.`customer_guid`, a.`supplier_guid`");

            // $string = 'This is the <b><span style="color:red;">2nd REMINDER</span></b> for the <b>TOTAL</b> Overdue Amount to <b>REXBRIDGE SDN BHD</b>.<br><br>';
            $type = '';
            $force_logout = 0;
            $string = '';
            $date_to = '13-January-2021';
            $result_count = $result->num_rows();
            foreach ($result->result() as $row) {
                if ($row->type == 1) {
                    $type = 'Blocked';
                    $force_logout = 1;

                    $from_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_From . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                    // echo $this->db->last_query();die;

                    $to_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_To . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $o_due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $last_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Invoice_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                    // echo $this->db->last_query();die;

                    $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $string .= 'Your Login Account is <b><span style="color:red;"> BLOCKED!!!</span></b><br><b><span style="color:red;">We have followed up several times but unfortunately still not getting your respond. </span></b><br><br>';
                } else if ($row->type == 2) {
                    $type = 'Warning';
                    $from_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_From . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                    // echo $this->db->last_query();die;

                    $to_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_To . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $o_due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $last_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Invoice_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                    // echo $this->db->last_query();die;

                    $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $warning_inv_date_string = $this->db->query("SELECT DATE_FORMAT(DATE_ADD('" . $row->created_at . "', INTERVAL 6 DAY),'%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $string .= 'This is the <b>LAST REMINDER</b> on</b><br><br>';
                } else if ($row->type == 3) {
                    $type = 'Gentle Reminder';
                    $last_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Invoice_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                    // echo $this->db->last_query();die;

                    $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                    $string .= 'This is a Gentle REMINDER on <br><br>';
                } else {
                    $result_count = 0;
                }

                if ($row->type == 1) {
                    $string .= 'Retailer Name : <b>' . $customer_name->row('acc_name') . '</b><br>';
                    $string .= 'Name : <b>' . $row->Supplier . '</b><br>';
                    $string .= 'Company Registration No : <b>' . $row->Reg_NO . '</b><br>';

                    $string .= '<span style="color:red;"><h3>Overdue</h3></span>';
                    $string .= 'Invoice Date : From <b><span style="color:red;">' . $from_inv_date_string . '</span></b> to <b><span style="color:red;">' . $to_inv_date_string . '</span></b><br>';
                    $string .= 'Total Overdue Invoice : ' . $row->Overdue_Invoices_Count . '<br>';
                    $string .= 'Total Overdue : <b>MYR ' . number_format($row->Total_Overdue, 2) . '</b><br>
                      Overdue BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR ' . number_format($row->Overdue_Registration_Fees, 2) . '</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Monthly Subscriptions : MYR ' . number_format($row->Overdue_Subscriptions, 2) . '</i><br>';
                    $string .= 'Due Date : <b><span class="blink" style="color:red;">' . $o_due_inv_date_string . '</span></b>';

                    if ($row->Last_Invoice_Date != '0000-00-00') {
                        $string .= '<span style="color:red;"><h3>Outstanding</h3></span>';
                        $string .= 'Invoice Date : <b>' . $last_inv_date_string . '</span></b><br>';
                        $string .= 'Invoice Amount : <b>MYR ' . number_format($row->Last_Invoice_Amt, 2) . '</b><br>';
                        $string .= 'Invoice Due Date : <b>' . $due_inv_date_string . '</span></b><br><br>';
                    } else {
                        $string .= '<br><br>';
                    }

                    $string .= 'Please make payment <b><span style="color:red;">NOW!!!</span></b><br>';
                    $string .= 'Please contact xBridge Support Team @ support@xbridge.my or call us @ +60177451185 / +0177159340 should you require further clarifications.<br>';
                    $string .= 'Please provide payment slip to support@xbridge.my once payment made. Thank You.<br><br>';
                    $string .= 'Please  <b><span style="color:green;">DISREGARD</span></b> this email if payment is made and thank you for your support.<br>';
                } else if ($row->type == 2) {
                    $string .= 'Retailer Name : <b>' . $customer_name->row('acc_name') . '</b><br>';
                    $string .= 'Name : <b>' . $row->Supplier . '</b><br>';
                    $string .= 'Company Registration No : <b>' . $row->Reg_NO . '</b>';
                    $string .= '<span style="color:red;"><h3>Overdue</h3></span>';
                    $string .= 'Invoice Date : From <b><span style="color:red;">' . $from_inv_date_string . '</span></b> to <b><span style="color:red;">' . $to_inv_date_string . '</span></b><br>';
                    $string .= 'Total Overdue Invoice : ' . $row->Overdue_Invoices_Count . '<br>';
                    $string .= 'Total Overdue : <b>MYR ' . number_format($row->Total_Overdue, 2) . '</b><br>
                      Overdue BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR ' . number_format($row->Overdue_Registration_Fees, 2) . '</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Monthly Subscriptions : MYR ' . number_format($row->Overdue_Subscriptions, 2) . '</i><br>';
                    $string .= 'Due Date : <b><span class="blink" style="color:red;">' . $o_due_inv_date_string . '</span></b>';
                    if ($row->Last_Invoice_Date != '0000-00-00') {
                        $string .= '<span style="color:red;"><h3>Outstanding</h3></span>';
                        $string .= 'Invoice Date : <b>' . $last_inv_date_string . '</span></b><br>';
                        $string .= 'Invoice Amount : <b>MYR ' . number_format($row->Last_Invoice_Amt, 2) . '</b><br>';
                        $string .= 'Invoice Due Date : <b>' . $due_inv_date_string . '</span></b><br><br>';
                    } else {
                        $string .= '<br><br>';
                    }


                    $string .= '<span class="blink" style="color:black;font-size:20px;">Your Login Account will be <span style="color:red;"><b>BLOCKED</b></span> if above overdue payment not pay by <b>' . $warning_inv_date_string . '</b></span> <br><br>';
                } else if ($row->type == 3) {
                    $string .= 'Retailer Name : <b>' . $customer_name->row('acc_name') . '</b><br>';
                    $string .= 'Name : <b>' . $row->Supplier . '</b><br>';
                    $string .= 'Company Registration No : <b>' . $row->Reg_NO . '</b><br>';
                    $string .= 'Invoice Date : <b>' . $last_inv_date_string . '</span></b><br>';
                    $string .= 'Invoice Amount : <b>MYR ' . number_format($row->Last_Invoice_Amt, 2) . '</b><br>';
                    $string .= 'Invoice Due Date : <b>' . $due_inv_date_string . '</span></b><br><br>';
                } else {
                    $result_count = 0;
                }
            }

            $data = array(
                'result' => $result_count,
                'string' => $string,
                'force_logout' => $force_logout,
                'type' => $type
            );
            echo json_encode($data);
            // echo 1;die;
        } else {
            echo 'error';
            die;
        }
    }

    public function reminder()
    {
        // echo 1;die;
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $database1 = 'b2b_invoice';

            $type_list = $this->db->query("SELECT * FROM lite_b2b.set_setting WHERE module_name = 'reminder' ORDER BY code ASC");

            $acc = $this->db->query("SELECT acc_name,acc_guid FROM lite_b2b.acc WHERE isactive = '1'");

            $type_list_dropdown = '';
            $type_list_dropdown = '<select style="width:100%" class="select" name="reminder_type" id="reminder_type">';
            foreach ($type_list->result() as $row) {
                $type_list_dropdown .= '<option value="' . $row->code . '">' . $row->reason . '</option>';
            }
            $type_list_dropdown .= '</select>';

            // echo $bank_list_dropdown;die;
            $data = array(
                'type_list_dropdown' => addslashes($type_list_dropdown),
                'acc' => $acc->result(),
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('query_outstanding/query_outstanding', $data);
            $this->load->view('footer');
        } else {
            redirect('main_controller');
        }
    }

    public function reminder_table()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $this->session->userdata('user_guid');
            $customer_guid = $this->session->userdata('customer_guid');
            $columns = array(
                0 => 'Retailer',
                1 => 'Supplier',
                2 => 'Reg_NO',
                3 => 'Overdue_Registration_Fees',
                4 => 'Overdue_Subscriptions',
                5 => 'Total_Overdue',
                6 => 'Last_Subscriptions_Invoice_Count',
                7 => 'Overdue_Invoice_Date_From',
                8 => 'Overdue_Invoice_Date_To',
                9 => 'Overdue_Invoice_Due_Date',
                10 => 'Last_Invoice_Date',
                11 => 'Last_Invoice_Amt',
                12 => 'Last_Due_Date',
                13 => 'Registration_date',
                14 => 'created_at',
                15 => 'created_by',
                16 => 'updated_at',
                17 => 'updated_by',
                18 => 'type_name',
                19 => 'action',

            );

            $limit = $this->input->post('length');
            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search = $this->input->post("search");
            $search = addslashes($search['value']);
            $col = 0;
            $dir = "";
            // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

            $order_query = "";

            if (!empty($order)) {
                foreach ($order as $o) {
                    $col = $o['column'];
                    $dir = $o['dir'];

                    $order_query .= $columns[$col] . " " . $dir . ",";
                }
            }
            $order_query = rtrim($order_query, ',');
            $database1 = 'lite_b2b';
            $database2 = 'b2b_invoice';
            $type = 'new';
            if ($type == 'old') {
                $database3 = 'daniel_invoice_email';
                $table3 = 'query_outstanding';
            } else {
                $database3 = 'lite_b2b';
                $table3 = 'query_outstanding';
            }
            // echo $order_query;die;
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
            } else {
                $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
            }
            $query2 = " ORDER BY " . $order_query . " LIMIT " . $start . " , " . $limit . ";";


            // echo $query.$query2;die;
            // $receipt_list = $this->db->query("$execute_query");
            // echo $this->db->last_query();die;
            // print_r($receipt_list->result());die;

            if (empty($this->input->post('search')['value'])) {
                $execute_query = $query . $query2;
                $posts = $this->db->query("$execute_query");
                $totalDataquery = $this->db->query("$query");;
                $totalData = $totalDataquery->num_rows();
                $totalFiltered = $totalData;
                // echo $this->db->last_query();die;
            } else {
                $search = addslashes($this->input->post('search')['value']);
                $search_query = " WHERE (Retailer LIKE '%$search%' OR Supplier LIKE '%$search%' OR Reg_NO LIKE '%$search%' OR Registration_date LIKE '%$search%' OR Overdue_Registration_Fees LIKE '%$search%' OR Overdue_Subscriptions LIKE '%$search%' OR Total_Overdue LIKE '%$search%' OR type_name LIKE '%$search%')";
                $execute_query = "SELECT * FROM " . "(" . $query . ") a " . $search_query;
                $execute_query_count = "SELECT count(*) as count FROM " . "(" . $query . ") a " . $search_query;
                // echo $execute_query;die; 

                $posts =  $this->db->query("$execute_query");
                $totalData = $this->db->query("$execute_query")->num_rows('count');
                // echo $this->db->last_query();die;

                $totalFiltered = $totalData;
                // echo $totalFiltered;die;
            }
            // print_r($posts->result());die;

            $data = array();
            if (!empty($posts)) {
                foreach ($posts->result() as $post) {
                    $e_supplier_guid = $post->supplier_guid;
                    $e_customer_guid = $post->customer_guid;
                    $e_Retailer = addslashes($post->Retailer);
                    $e_Supplier = $post->Supplier;
                    $e_Reg_NO = $post->Reg_NO;
                    $e_Overdue_Registration_Fees = $post->Overdue_Registration_Fees;
                    $e_Overdue_Subscriptions = $post->Overdue_Subscriptions;
                    $e_Total_Overdue = $post->Total_Overdue;
                    $e_Registration_date = $post->Registration_date;
                    $e_type_name = $post->type_name;
                    $e_type = $post->type;
                    $e_Last_Subscriptions_Invoice_Count = $post->Last_Subscriptions_Invoice_Count;
                    $e_Last_Invoice_Date = $post->Last_Invoice_Date;
                    $e_Last_Invoice_Amt = $post->Last_Invoice_Amt;
                    $e_Last_Due_Date = $post->Last_Due_Date;
                    $e_table_type = 'not_one_off';

                    $Overdue_Invoice_Date_From = $post->Overdue_Invoice_Date_From;
                    $Overdue_Invoice_Date_To = $post->Overdue_Invoice_Date_To;
                    $Overdue_Invoice_Due_Date = $post->Overdue_Invoice_Due_Date;

                    $attr = 'e_supplier_guid = "' . $e_supplier_guid . '" e_customer_guid = "' . $e_customer_guid . '" e_Retailer = "' . $e_Retailer . '" e_Supplier = "' . $e_Supplier . '" e_Reg_NO = "' . $e_Reg_NO . '" e_Overdue_Registration_Fees = "' . $e_Overdue_Registration_Fees . '" e_Overdue_Subscriptions = "' . $e_Overdue_Subscriptions . '" e_Total_Overdue = "' . $e_Total_Overdue . '" e_Registration_date = "' . $e_Registration_date . '" e_type = "' . $e_type . '" e_table_type = "' . $e_table_type . '"';
                    $update_button = '<button ' . $attr . ' class="btn btn-xs btn-primary" id="edit_reminder"><i class="fa fa-pencil"></i></button>';

                    $delete_button = '<button ' . $attr . ' class="btn btn-xs btn-danger" id="delete_reminder"><i class="fa fa-trash"></i></button>';

                    $nestedData['Retailer'] = $e_Retailer;
                    $nestedData['Supplier'] = $e_Supplier;
                    $nestedData['Reg_NO'] = $e_Reg_NO;
                    $nestedData['Overdue_Registration_Fees'] = $e_Overdue_Registration_Fees;
                    $nestedData['Overdue_Subscriptions'] = $e_Overdue_Subscriptions;
                    $nestedData['Total_Overdue'] = $e_Total_Overdue;
                    $nestedData['Registration_date'] = $e_Registration_date;
                    $nestedData['type_name'] = $e_type_name;

                    $nestedData['Last_Subscriptions_Invoice_Count'] = $e_Last_Subscriptions_Invoice_Count;
                    $nestedData['Overdue_Invoice_Date_From'] = $Overdue_Invoice_Date_From;
                    $nestedData['Overdue_Invoice_Date_To'] = $Overdue_Invoice_Date_To;
                    $nestedData['Overdue_Invoice_Due_Date'] = $Overdue_Invoice_Due_Date;
                    $nestedData['Last_Invoice_Date'] = $e_Last_Invoice_Date;
                    $nestedData['Last_Invoice_Amt'] = $e_Last_Invoice_Amt;
                    $nestedData['Last_Due_Date'] = $e_Last_Due_Date;
                    $nestedData['action'] = $update_button . $delete_button;
                    $nestedData['created_at'] = $post->created_at;
                    $nestedData['created_by'] = $post->created_by;
                    $nestedData['updated_at'] = $post->updated_at;
                    $nestedData['updated_by'] = $post->updated_by;
                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
                "total_data" => $totalData,
                "execute_query" => $execute_query,
            );
            echo json_encode($json_data);
        } else {
            redirect('main_controller');
        }
    }

    public function reminder_update()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
            $database1 = 'b2b_invoice';

            $reminder_customer_guid = $this->input->post('reminder_customer_guid');
            $reminder_supplier_guid = $this->input->post('reminder_supplier_guid');
            $reminder_table_type = $this->input->post('reminder_table_type');
            $reminder_retailer_name = $this->input->post('reminder_retailer_name');
            $reminder_supplier_name = $this->input->post('reminder_supplier_name');
            $reminder_reg_no = $this->input->post('reminder_reg_no');
            $reminder_overdue_registration_fees = $this->input->post('reminder_overdue_registration_fees');
            $reminder_subscription_fees = $this->input->post('reminder_subscription_fees');
            $reminder_total_overdue = $this->input->post('reminder_total_overdue');
            $reminder_reg_date = $this->input->post('reminder_reg_date');
            $reminder_type = $this->input->post('reminder_type');
            $type = 'new';

            if ($reminder_table_type == 'not_one_off') {
                if ($type == 'old') {
                    $database = 'daniel_invoice_email';
                    $table = 'query_outstanding';
                } else {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding';
                }
                $this->db->query("UPDATE $database.$table SET type = '$reminder_type',updated_at=NOW(),updated_by='$user_id' WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid'");
            }
            if ($reminder_table_type == 'one_off') {
                if ($type == 'old') {
                    $database = 'daniel_invoice_email_one_off';
                    $table = 'query_outstanding';
                } else {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding_one_off';
                }
                $this->db->query("UPDATE $database.$table SET type = '$reminder_type',updated_at=NOW(),updated_by='$user_id' WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid'");
            }
            $this->session->set_flashdata('message', 'Updated Sucessfully');
            redirect('Query_outstanding/reminder');
            print_r($this->input->post());
            die;
        } else {
            redirect('main_controller');
        }
    }

    public function reminder_delete()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $database1 = 'b2b_invoice';

            $reminder_customer_guid = $this->input->post('reminder_customer_guid');
            $reminder_supplier_guid = $this->input->post('reminder_supplier_guid');
            $reminder_table_type = $this->input->post('reminder_table_type');
            $reminder_type = $this->input->post('reminder_type');
            $type = 'new';
            // print_r($this->input->post());die;

            if ($reminder_table_type == 'not_one_off') {
                if ($type == 'old') {
                    $database = 'daniel_invoice_email';
                    $table = 'query_outstanding';
                } else {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding';
                }
                $this->db->query("DELETE FROM $database.$table WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid' AND type='$reminder_type'");
            }
            if ($reminder_table_type == 'one_off') {
                if ($type == 'old') {
                    $database = 'daniel_invoice_email_one_off';
                    $table = 'query_outstanding';
                } else {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding_one_off';
                }
                $this->db->query("DELETE FROM $database.$table WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid' AND type='$reminder_type'");
            }
            $this->session->set_flashdata('message', 'Deleted Sucessfully');
            redirect('Query_outstanding/reminder');
            print_r($this->input->post());
            die;
        } else {
            redirect('main_controller');
        }
    }

    public function reminder_one_off_table()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $this->session->userdata('user_guid');
            $customer_guid = $this->session->userdata('customer_guid');
            $columns = array(
                0 => 'Retailer',
                1 => 'Supplier',
                2 => 'Reg_NO',
                3 => 'Overdue_Registration_Fees',
                4 => 'Overdue_Subscriptions',
                5 => 'Total_Overdue',
                6 => 'Registration_date',
                7 => 'type_name',
                8 => 'action'

            );

            $limit = $this->input->post('length');
            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search = $this->input->post("search");
            $search = addslashes($search['value']);
            $col = 0;
            $dir = "";
            // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

            $order_query = "";

            if (!empty($order)) {
                foreach ($order as $o) {
                    $col = $o['column'];
                    $dir = $o['dir'];

                    $order_query .= $columns[$col] . " " . $dir . ",";
                }
            }
            $order_query = rtrim($order_query, ',');
            $database1 = 'lite_b2b';
            $database2 = 'b2b_invoice';
            $type = 'new';
            if ($type == 'old') {
                $database3 = 'daniel_invoice_email_one_off';
                $table3 = 'query_outstanding';
            } else {
                $database3 = 'lite_b2b';
                $table3 = 'query_outstanding_one_off';
            }
            // echo $order_query;die;
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
            } else {
                $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
            }
            $query2 = " ORDER BY " . $order_query . " LIMIT " . $start . " , " . $limit . ";";


            // echo $query.$query2;die;
            // $receipt_list = $this->db->query("$execute_query");
            // echo $this->db->last_query();die;
            // print_r($receipt_list->result());die;

            if (empty($this->input->post('search')['value'])) {
                $execute_query = $query . $query2;
                $posts = $this->db->query("$execute_query");
                $totalDataquery = $this->db->query("$query");;
                $totalData = $totalDataquery->num_rows();
                $totalFiltered = $totalData;
                // echo $this->db->last_query();die;
            } else {
                $search = addslashes($this->input->post('search')['value']);
                $search_query = " WHERE (Retailer LIKE '%$search%' OR Supplier LIKE '%$search%' OR Reg_NO LIKE '%$search%' OR Registration_date LIKE '%$search%' OR Overdue_Registration_Fees LIKE '%$search%' OR Overdue_Subscriptions LIKE '%$search%' OR Total_Overdue LIKE '%$search%' OR Total_Overdue LIKE '%$search%' OR type_name LIKE '%$search%')";
                $execute_query = "SELECT * FROM " . "(" . $query . ") a " . $search_query . $query2;
                $execute_query_count = "SELECT count(*) as count FROM " . "(" . $query . ") a " . $search_query;
                // echo $execute_query;die; 

                $posts =  $this->db->query("$execute_query");
                $totalData = $this->db->query("$execute_query")->num_rows('count');
                // echo $this->db->last_query();die;

                $totalFiltered = $totalData;
                // echo $totalFiltered;die;
            }
            // print_r($posts->result());die;

            $data = array();
            if (!empty($posts)) {
                foreach ($posts->result() as $post) {
                    $e_supplier_guid = $post->supplier_guid;
                    $e_customer_guid = $post->customer_guid;
                    $e_Retailer = addslashes($post->Retailer);
                    $e_Supplier = $post->Supplier;
                    $e_Reg_NO = $post->Reg_NO;
                    $e_Overdue_Registration_Fees = $post->Overdue_Registration_Fees;
                    $e_Overdue_Subscriptions = $post->Overdue_Subscriptions;
                    $e_Total_Overdue = $post->Total_Overdue;
                    $e_Registration_date = $post->Registration_date;
                    $e_type_name = $post->type_name;
                    $e_type = $post->type;
                    $e_table_type = 'one_off';

                    $attr = 'e_supplier_guid = "' . $e_supplier_guid . '" e_customer_guid = "' . $e_customer_guid . '" e_Retailer = "' . $e_Retailer . '" e_Supplier = "' . $e_Supplier . '" e_Reg_NO = "' . $e_Reg_NO . '" e_Overdue_Registration_Fees = "' . $e_Overdue_Registration_Fees . '" e_Overdue_Subscriptions = "' . $e_Overdue_Subscriptions . '" e_Total_Overdue = "' . $e_Total_Overdue . '" e_Registration_date = "' . $e_Registration_date . '" e_type = "' . $e_type . '" e_table_type = "' . $e_table_type . '"';
                    $update_button = '<button ' . $attr . ' class="btn btn-xs btn-primary" id="edit_reminder"><i class="fa fa-pencil"></i></button>';

                    $delete_button = '<button ' . $attr . ' class="btn btn-xs btn-danger" id="delete_reminder"><i class="fa fa-trash"></i></button>';

                    $nestedData['Retailer'] = $e_Retailer;
                    $nestedData['Supplier'] = $e_Supplier;
                    $nestedData['Reg_NO'] = $e_Reg_NO;
                    $nestedData['Overdue_Registration_Fees'] = $e_Overdue_Registration_Fees;
                    $nestedData['Overdue_Subscriptions'] = $e_Overdue_Subscriptions;
                    $nestedData['Total_Overdue'] = $e_Total_Overdue;
                    $nestedData['Registration_date'] = $e_Registration_date;
                    $nestedData['type_name'] = $e_type_name;
                    $nestedData['action'] = $update_button . $delete_button;
                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
                "total_data" => $totalData,
                "execute_query" => $execute_query,
            );
            echo json_encode($json_data);
        } else {
            redirect('main_controller');
        }
    }

    public function file_upload()
    {
        $customer_guid = $_SESSION['customer_guid'];

        $acc_guid = $this->input->post('acc_guid');

        $check_acc = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$acc_guid'");

        $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid, 'web', 'reminder', 'main_path', 'RMD');

        if ($check_acc->num_rows() == 0) {
            $data = array(
                'para1' => 1,
                'msg' => 'Error retailer.',
            );
            echo json_encode($data);
            exit();
        }

        $user_id = $this->db->query("SELECT a.user_id FROM set_user a WHERE a.user_guid ='" . $_SESSION['user_guid'] . "'")->row('user_id');

        $file_uuid = $this->db->query("SELECT REPLACE(LOWER(UUID()),'-','') AS uuid")->row('uuid');
        $now = $this->db->query("SELECT NOW() as now")->row('now');

        $defined_path = $file_config_main_path; // './uploads/empty/';

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        ini_set('post_max_size', '64M');
        ini_set('upload_max_filesize', '64M');

        $config['upload_path']          = $defined_path;
        $config['allowed_types']        = '*';
        $config['max_size']             = 50000;
        $config['file_name'] = $file_uuid;
        // var_dump( $this->input->post('file') );die; 

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());

            if (null != $error) {
                $data = array(
                    'para1' => 1,
                    'msg' => 'Error do upload.',
                );
                echo json_encode($data);
                exit();
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

                $error_message = $this->lang->line('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                $xerror = $this->db->error();
                $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
                $this->message->error_message_with_status($xerror['message'], '1', '');
                exit();
            }

            unlink($filename);
        }
        //Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        // $sheetCount = $sheet->getSheetCount();

        for ($row = 1; $row <= 1; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            //  Insert row data array into your database of choice here

        }

        // print_r($objPHPExcel->getSheetCount());die;

        //$header_array = array();
        $header_array = ['Code', 'Name', 'Reg No', 'Registration Invoice Date', 'Overdue Registraion Fees', 'Overdue Subscriptions Invoice Amt', 'Total Overdue', 'Overdue Invoices Count', 'Overdue Invoice Date From', 'Overdue Invoice Date To', 'Overdue Invoice Due Date', 'Last Subscriptions Invoice Count', 'Last Invoice Date', 'Last Invoice Amt', 'Last Invoice Due Date', 'Variance']; //,'customer_guid'

        $checking_array = array();

        //make array into string with comma
        $exheader = '';
        $exchild_main = '';
        $set_supplier_exchild = '';
        $update_syntax = '';

        $values = '';

        foreach ($rowData as $eheader) {
            foreach ($eheader as $row) {
                if ($row == '' || $row == null) {
                    continue;
                } else {
                    $row = $row;
                }

                $checking_array[] = $row;
                $update_syntax .= $row . ' = ' . 'VALUES(' . $row . '),';
            } //close small foreacH
        } //close loop header array

        $update_syntax = rtrim($update_syntax, ',');

        $result = array_diff($header_array, $checking_array); //compare form

        if (null != $result) {

            $message = '';

            foreach ($result as $header) {
                $message .= $header . ' ';
            }

            $data = array(
                'para1' => 1,
                'msg' => $message . 'header is required.',
            );
            echo json_encode($data);
            // $error_message = $message.$this->lang->line('header_is_required');
            // $xerror = $this->db->error();
            // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
            // $this->message->error_message_with_status($xerror['message'], '1', '');
            exit();
        } //close if

        $result = array_diff($checking_array, $header_array); //compare form

        if (null != $result) {

            $message = '';

            foreach ($result as $header) {
                $message .= $header . ' ';
            }

            $data = array(
                'para1' => 1,
                'msg' => $message . 'header is not exists.',
            );
            echo json_encode($data);
            // $error_message = $message.$this->lang->line('header_is_not_exist');
            // $xerror = $this->db->error();
            // $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
            // $this->message->error_message_with_status($xerror['message'], '1', '');
            exit();
        } //close if

        $check_escape_header_index = array();

        foreach ($rowData as $eheader) {
            foreach ($eheader as $key => $row) {
                if ($row == '' || $row == null) {
                    $check_escape_header_index[] = $key;

                    continue;
                } else {
                    $row = $row;
                }

                $exheader .= $row . ',';
            } //close loop through row
        } //close loop header array

        $supp_guid = array();
        for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
            //  Read a row of data into an array
            $xrowData = $sheet->rangeToArray('B' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

            $search_array = $sheet->rangeToArray('B' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

            $type_search = array_search('Name', $search_array[0]);

            $exchild = '';

            //if($this->isEmptyRow(reset($xrowData))) { continue; }

            foreach ($xrowData as $echild) {
                foreach ($echild as $key => $row2) {
                    if ($key == $type_search) {
                        if (!($row2 == '' && $row2 == null)) {
                            $slashesdata = addslashes($row2);
                            $supplier_query = $this->db->query("SELECT supplier_guid,supplier_name FROM lite_b2b.set_supplier WHERE supplier_name = '$slashesdata' AND isactive = '1' LIMIT 1");
                            //echo $this->db->last_query(); die;
                            if ($supplier_query->num_rows() == 0) {
                                $supplier_query = $this->db->query("SELECT supplier_guid,supplier_name FROM lite_b2b.set_supplier WHERE supplier_name = '$slashesdata' AND isactive = '0' LIMIT 1");
                                if ($supplier_query->num_rows() == 0) {
                                    $data = array(
                                        'para1' => 1,
                                        'msg' => 'Error find Supplier GUID: ' . $row2 . '. Please rename to new supplier name.',
                                    );
                                    echo json_encode($data);
                                    exit();
                                }
                            } //close num rows
                            $store_guid = $supplier_query->row('supplier_guid');
                            $supp_guid[] = $store_guid;
                        } //close else
                    } //close itemcode
                } //close foreach td itemcode
            } //close loop row

        } //close foreach child data checking

        $lexheader = rtrim($exheader, ',');

        $string = '';
        $string_main = '';
        $typehead = '';
        $valuechild = '';
        $i = '0';

        for ($xrow = 2; $xrow <= $highestRow; $xrow++) {
            //  Read a row of data into an array
            $xrowData = $sheet->rangeToArray('A' . $xrow . ':' . $highestColumn . $xrow, NULL, TRUE, FALSE);

            $search_array = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);

            $exchild = '';

            //if($this->isEmptyRow(reset($xrowData))) { continue; }

            foreach ($xrowData as $echild) {
                unset($checking_child); // destroy loop data after first loop
                foreach ($echild as $key => $row2) {

                    if (in_array($key, $check_escape_header_index)) {
                        continue;
                    }
                    // type->value
                    $exchild .= "'" . addslashes($row2) . "',";
                } //close foreach

                $exchild_main .= "(" . $exchild . "'$acc_guid','$user_id','$now','$user_id','$now','$supp_guid[$i]'),";
            } //5
            $i++;
        } //close loop row

        $exchild_main = rtrim($exchild_main, ',');

        if ($exchild_main == '' || $exchild_main == null) {
            $data = array(
                'para1' => 1,
                'msg' => 'No Data.',
            );
            echo json_encode($data);
            exit();
        }

        $check_reminder_data = $this->db->query("SELECT * FROM lite_b2b.`query_outstanding` WHERE customer_guid = '$acc_guid'");

        if ($check_reminder_data->num_rows() != 0) {
            $insert_to_backup = $this->db->query("INSERT INTO lite_b2b.`query_outstanding_testing` SELECT * FROM lite_b2b.`query_outstanding` WHERE customer_guid = '$acc_guid'");

            $error = $this->db->affected_rows();

            if ($error > 0) {
                $delete_reminder = $this->db->query("DELETE FROM lite_b2b.`query_outstanding` WHERE customer_guid = '$acc_guid'; ");
            } else {
                $data = array(
                    'para1' => 1,
                    'msg' => 'Error Insert For Backup.',

                );
                echo json_encode($data);
            }
        }

        $insert_main = $this->db->query("INSERT INTO lite_b2b.query_outstanding (`group`,`Supplier`,`Reg_NO`,`Registration_date`,`Overdue_Registration_Fees`,`Overdue_Subscriptions`,`Total_Overdue`,`Overdue_Invoices_Count`,`Overdue_Invoice_Date_From`,`Overdue_Invoice_Date_To`,`Overdue_Invoice_Due_Date`,`Last_Subscriptions_Invoice_Count`,`Last_Invoice_Date`,`Last_Invoice_Amt`,`Last_Due_Date`,`type`,`customer_guid`,`created_by`,`created_at`,`updated_by`,`updated_at`,`supplier_guid`) VALUES $exchild_main ");

        //echo $this->db->last_query(); die;

        $error = $this->db->affected_rows();

        if ($error > 0) {
            $data = array(
                'para1' => 0,
                'msg' => 'Successfully Import',

            );
            echo json_encode($data);
        } else {
            $data = array(
                'para1' => 1,
                'msg' => 'Error Import.',

            );
            echo json_encode($data);
        }

        // }//close else for success upload file
    } //close file upload

    public function b2b_reminder()
    {
        // echo 1;die;
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $database1 = 'b2b_invoice';

            $type_list = $this->db->query("SELECT * FROM lite_b2b.set_setting WHERE module_name = 'reminder' ORDER BY code ASC");

            $acc = $this->db->query("SELECT acc_name,acc_guid FROM lite_b2b.acc WHERE isactive = '1'");

            $type_list_dropdown = '';
            $type_list_dropdown = '<select class="form-control select2" name="reminder_type" id="reminder_type">';
            foreach ($type_list->result() as $row) {
                $type_list_dropdown .= '<option value="' . $row->code . '">' . $row->reason . '</option>';
            }
            $type_list_dropdown .= '</select>';

            $settings = $this->db->query("SELECT * FROM lite_b2b.reminder_settings WHERE module_name = 'reminder' ORDER BY seq ASC");

            $reminder_config_status = $this->db->query("SELECT * FROM lite_b2b.reminder_config WHERE type = 'Reminder_sync' AND code = 'RMDSYNC'");

            // echo $bank_list_dropdown;die;
            $data = array(
                'type_list_dropdown' => addslashes($type_list_dropdown),
                'acc' => $acc->result(),
                'settings' => $settings->result(),
                'sync_status' => $reminder_config_status->row('value'),
                'latest_sync_on' => $reminder_config_status->row('updated_at'),
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('query_outstanding/reminder_lists', $data);
            $this->load->view('footer');
        } else {
            redirect('main_controller');
        }
    }

    public function b2b_reminder_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = addslashes($search['value']);
        $col = 0;
        $dir = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }

        $valid_columns = array(

            0 => 'Code',
            1 => 'DebtorCode',
            2 => 'supplier_name',
            3 => 'reg_no',
            4 => 'Registration_Invoice_Date',
            5 => 'Overdue_Registration_Fees',
            6 => 'Overdue_Subscriptions_Invoice_Amt',
            7 => 'Total_Overdue',
            8 => 'Overdue_Invoices_Count',
            9 => 'Overdue_Invoice_Date_From',
            10 => 'Overdue_Invoice_Date_To',
            11 => 'Overdue_Invoice_Due_Date',
            12 => 'Last_Subscriptions_Invoice_Count',
            13 => 'Last_Invoice_Date',
            14 => 'Last_Due_Date',
            15 => 'Last_Invoice_Amt',
            16 => 'created_at',
            17 => 'created_by',
            18 => 'updated_at',
            19 => 'updated_by',
            20 => 'reminder_type',

        );

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if ($order != null) {
            // $this->db->order_by($order, $dir);

            $order_query = "ORDER BY " . $order . "  " . $dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if (!empty($search)) {
            $x = 0;
            foreach ($valid_columns as $sterm) {
                if ($x == 0) {
                    // $this->db->like($sterm,$search);

                    $like_first_query = "WHERE $sterm LIKE '%" . $search . "%'";
                } else {
                    // $this->db->or_like($sterm,$search);

                    $like_second_query .= "OR $sterm LIKE '%" . $search . "%'";
                }
                $x++;
            }
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " . $start . " , " . $length;

        $sql = "SELECT a.*, b.reason AS reminder_type FROM lite_b2b.query_outstanding_new a LEFT JOIN lite_b2b.set_setting b ON a.`Variance` = b.`code` AND module_name = 'reminder' ";

        $query = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query . $order_query . $limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        // echo $this->db->last_query();
        // die;

        if (!empty($search)) {
            $query_filter = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        } else {
            $total = $this->db->query($sql)->num_rows();
        }


        $data = array();
        foreach ($result->result() as $row) {
            $nestedData['dockey'] = $row->dockey;
            $nestedData['Code'] = $row->Code;
            $nestedData['DebtorCode'] = $row->DebtorCode;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['reg_no'] = $row->reg_no;
            $nestedData['Registration_Invoice_Date'] = $row->Registration_Invoice_Date;
            $nestedData['Overdue_Registration_Fees'] = $row->Overdue_Registration_Fees;
            $nestedData['Overdue_Subscriptions_Invoice_Amt'] = $row->Overdue_Subscriptions_Invoice_Amt;
            $nestedData['Total_Overdue'] = $row->Total_Overdue;
            $nestedData['Overdue_Invoices_Count'] = $row->Overdue_Invoices_Count;
            $nestedData['Overdue_Invoice_Date_From'] = $row->Overdue_Invoice_Date_From;
            $nestedData['Overdue_Invoice_Date_To'] = $row->Overdue_Invoice_Date_To;
            $nestedData['Overdue_Invoice_Due_Date'] = $row->Overdue_Invoice_Due_Date;
            $nestedData['Last_Subscriptions_Invoice_Count'] = $row->Last_Subscriptions_Invoice_Count;
            $nestedData['Last_Invoice_Date'] = $row->Last_Invoice_Date;
            $nestedData['Last_Due_Date'] = $row->Last_Due_Date;
            $nestedData['Last_Invoice_Amt'] = $row->Last_Invoice_Amt;
            $nestedData['Variance'] = $row->Variance;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_by'] = $row->updated_by;
            $nestedData['reminder_type'] = $row->reminder_type;

            $nestedData['action'] = $row->supplier_guid;

            $data[] = $nestedData;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        );

        echo json_encode($output);
    }

    public function b2b_reminder_update()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

            $supplier_guid = $this->input->post('supplier_guid');
            $customer_guid = $this->input->post('customer_guid');
            $DebtorCode = $this->input->post('DebtorCode');
            $Overdue_Registration_Fees = $this->input->post('Overdue_Registration_Fees');
            $Overdue_Subscriptions_Invoice_Amt = $this->input->post('Overdue_Subscriptions_Invoice_Amt');
            $Total_Overdue = $this->input->post('Total_Overdue');
            $Registration_Invoice_Date = $this->input->post('Registration_Invoice_Date');
            $subscription_fees = $this->input->post('subscription_fees');
            $Last_Invoice_Amt = $this->input->post('Last_Invoice_Amt');
            $Variance = $this->input->post('Variance');
            $table_name = $this->input->post('table_name');
            $now = $this->db->query("SELECT now() as now")->row('now');

            $database = 'lite_b2b';
            //$table = 'query_outstanding_new';   
            if ($table_name == 'query_outstanding_new') {
                $update_query = $this->db->query("UPDATE $database.$table_name SET variance = '$Variance',updated_at='$now',updated_by='$user_id' WHERE supplier_guid = '$supplier_guid' AND DebtorCode = '$DebtorCode'");
            } else if ($table_name == 'query_outstanding_retailer') {
                if ($Variance == '5') {
                    $update_query = $this->db->query("UPDATE $database.$table_name SET variance = '$Variance',variance_day = '7',updated_at='$now',updated_by='$user_id' WHERE customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid' AND DebtorCode = '$DebtorCode'");
                } else {
                    $update_query = $this->db->query("UPDATE $database.$table_name SET variance = '$Variance',variance_day = '0',updated_at='$now',updated_by='$user_id' WHERE customer_guid = '$customer_guid' AND supplier_guid = '$supplier_guid' AND DebtorCode = '$DebtorCode'");
                }
            }

            $error = $this->db->affected_rows();

            if ($error > 0) {

                $data = array(
                    'para1' => 0,
                    'msg' => 'Updated Successfully',

                );
                echo json_encode($data);
            } else {
                $data = array(
                    'para1' => 1,
                    'msg' => 'Error.',

                );
                echo json_encode($data);
            }
        } else {
            redirect('main_controller');
        }
    }

    public function b2b_reminder_delete()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

            $supplier_guid = $this->input->post('supplier_guid');
            $supplier_name = $this->input->post('supplier_name');
            $DebtorCode = $this->input->post('DebtorCode');

            $database = 'lite_b2b';
            $table = 'query_outstanding_new';

            $update_query = $this->db->query("DELETE FROM $database.$table WHERE supplier_guid = '$supplier_guid' AND DebtorCode = '$DebtorCode'");

            $error = $this->db->affected_rows();

            if ($error > 0) {

                $data = array(
                    'para1' => 0,
                    'msg' => 'Remove Successfully',

                );
                echo json_encode($data);
            } else {
                $data = array(
                    'para1' => 1,
                    'msg' => 'Error.',

                );
                echo json_encode($data);
            }
        } else {
            redirect('main_controller');
        }
    }

    public function b2b_reminder_retailer_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = addslashes($search['value']);
        $col = 0;
        $dir = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }

        $valid_columns = array(

            0 => 'DebtorCode',
            1 => 'acc_name',
            2 => 'supplier_name',
            3 => 'reg_no',
            4 => 'Registration_Invoice_Date',
            5 => 'One_Off_Invoice_Date',
            6 => 'Registration_AddON_Invoice_Amt',
            7 => 'Registration_AddON_Invoice_Amt',
            8 => 'Training_Invoice_Amt',
            9 => 'Ad_Hoc_Service_Invoice_Amt',
            10 => 'Overdue_Registration_Fees',
            11 => 'Overdue_Subscriptions_Invoice_Amt',
            12 => 'Total_Overdue',
            13 => 'Overdue_Invoices_Count',
            14 => 'Overdue_Invoice_Date_From',
            15 => 'Overdue_Invoice_Date_To',
            16 => 'Overdue_Invoice_Due_Date',
            17 => 'Last_Subscriptions_Invoice_Count',
            18 => 'Last_Invoice_Date',
            19 => 'Last_Due_Date',
            20 => 'Last_Invoice_Amt',
            21 => 'created_at',
            22 => 'created_by',
            23 => 'updated_at',
            24 => 'updated_by',
            25 => 'invoice_number',
            26 => 'extend_status',
            27 => 'reminder_type',

        );

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if ($order != null) {
            // $this->db->order_by($order, $dir);

            $order_query = "ORDER BY " . $order . "  " . $dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if (!empty($search)) {
            $x = 0;
            foreach ($valid_columns as $sterm) {
                if ($x == 0) {
                    // $this->db->like($sterm,$search);

                    $like_first_query = "WHERE $sterm LIKE '%" . $search . "%'";
                } else {
                    // $this->db->or_like($sterm,$search);

                    $like_second_query .= "OR $sterm LIKE '%" . $search . "%'";
                }
                $x++;
            }
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " . $start . " , " . $length;

        //$sql = "SELECT a.*, b.reason AS reminder_type,c.acc_name FROM lite_b2b.query_outstanding_retailer a LEFT JOIN lite_b2b.set_setting b ON a.`Variance` = b.`code` AND module_name = 'reminder' LEFT JOIN lite_b2b.acc c ON a.customer_guid = c.acc_guid";

        $sql = "SELECT a.*, b.reason AS reminder_type,c.acc_name, IF(a.`Variance` = '5' AND d.`day_limit` IS NOT NULL, DATE_FORMAT( DATE_ADD( IFNULL( a.`Overdue_Invoice_Date_To`, a.`updated_at` ), INTERVAL a.`variance_day` DAY ), '%e-%M-%Y' ), IF( a.`Variance` = '5', DATE_FORMAT( DATE_ADD( a.`updated_at`, INTERVAL a.`variance_day` DAY ), '%e-%M-%Y' ), IF( a.`Variance` = '6', DATE_FORMAT( DATE_ADD( a.`updated_at`, INTERVAL a.`variance_day` DAY ), '%e-%M-%Y' ), '' ) ) ) AS until_date, IF(a.is_extend = '1', 'Yes', '') AS extend_status  FROM lite_b2b.query_outstanding_retailer a LEFT JOIN lite_b2b.set_setting b ON a.`Variance` = b.`code` AND module_name = 'reminder' LEFT JOIN lite_b2b.acc c ON a.customer_guid = c.acc_guid LEFT JOIN lite_b2b.`reminder_duration` d ON a.`customer_guid` = d.`customer_guid` AND a.`supplier_guid` = d.`supplier_guid` ";

        $query = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query . $order_query . $limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

        $result = $this->db->query($query);

        // echo $this->db->last_query();
        // die;

        if (!empty($search)) {
            $query_filter = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        } else {
            $total = $this->db->query($sql)->num_rows();
        }


        $data = array();
        foreach ($result->result() as $row) {
            $nestedData['dockey'] = $row->dockey;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['DebtorCode'] = $row->DebtorCode;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['reg_no'] = $row->reg_no;
            $nestedData['Registration_Invoice_Date'] = $row->Registration_Invoice_Date;
            $nestedData['One_Off_Invoice_Date'] = $row->One_Off_Invoice_Date;
            $nestedData['Overdue_Registration_Fees'] = $row->Overdue_Registration_Fees;
            $nestedData['Overdue_Subscriptions_Invoice_Amt'] = $row->Overdue_Subscriptions_Invoice_Amt;
            $nestedData['Total_Overdue'] = $row->Total_Overdue;
            $nestedData['Overdue_Invoices_Count'] = $row->Overdue_Invoices_Count;
            $nestedData['Overdue_Invoice_Date_From'] = $row->Overdue_Invoice_Date_From;
            $nestedData['Overdue_Invoice_Date_To'] = $row->Overdue_Invoice_Date_To;
            $nestedData['Overdue_Invoice_Due_Date'] = $row->Overdue_Invoice_Due_Date;
            $nestedData['Last_Subscriptions_Invoice_Count'] = $row->Last_Subscriptions_Invoice_Count;
            $nestedData['Last_Invoice_Date'] = $row->Last_Invoice_Date;
            $nestedData['Last_Due_Date'] = $row->Last_Due_Date;
            $nestedData['Last_Invoice_Amt'] = $row->Last_Invoice_Amt;
            $nestedData['Variance'] = $row->Variance;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_by'] = $row->updated_by;
            $nestedData['reminder_type'] = $row->reminder_type;

            $nestedData['Registration_AddON_Invoice_Amt'] = $row->Registration_AddON_Invoice_Amt;
            $nestedData['Subscription_OneOFF_Invoice_Amt'] = $row->Subscription_OneOFF_Invoice_Amt;
            $nestedData['Training_Invoice_Amt'] = $row->Training_Invoice_Amt;
            $nestedData['Ad_Hoc_Service_Invoice_Amt'] = $row->Ad_Hoc_Service_Invoice_Amt;
            $nestedData['invoice_number'] = $row->invoice_number;

            $nestedData['variance_day'] = $row->variance_day;
            $nestedData['until_date'] = $row->until_date;
            $nestedData['action'] = $row->supplier_guid;
            $nestedData['extend_status'] = $row->extend_status;
            

            $data[] = $nestedData;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        );

        echo json_encode($output);
    }

    public function b2b_resync_data()
    {
        $user_guid = $_SESSION['user_guid'];

        $check_autocount = $this->db->query("SELECT a.*, a.total_knockoff_include_cn + ROUND(SUM(IFNULL(b.`PaymentAmt`, 0)), 2) AS total_knockoff_include_cn_contra FROM (SELECT a.*, a.total_knockoff + ROUND(SUM(IFNULL(b.`Amount`, 0)), 2) AS total_knockoff_include_cn FROM (SELECT c.`CompanyName`, a.DocNo, a.PaymentAmt, ROUND(SUM(IFNULL(b.`Amount`, 0)), 2) AS total_knockoff FROM b2b_account.arinvoice a LEFT JOIN b2b_account.`arpaymentknockoff` b ON a.docno = b.`I_DocNo` INNER JOIN b2b_account.`debtor` c ON a.`DebtorCode` = c.`AccNo` GROUP BY a.DocNo HAVING a.`PaymentAmt` <> total_knockoff) a LEFT JOIN b2b_account.`arcnknockoff` b ON a.DocNo = b.`I_DocNo` GROUP BY a.DocNo HAVING a.`PaymentAmt` <> total_knockoff_include_cn) a LEFT JOIN b2b_account.arcontra b ON a.DocNo = b.`DocNo` GROUP BY a.DocNo HAVING ROUND(a.`PaymentAmt`, 2) <> ROUND( total_knockoff_include_cn_contra, 2 )");

        if ($check_autocount->num_rows() > 0) {
            $data = array(
                'para1' => 1,
                'msg' => 'Error Resync. Payment KnockOff Have duplicate data.',

            );
            echo json_encode($data);
        }
        //die;
        $manual_resync = $this->db->query("UPDATE lite_b2b.reminder_config SET value = 'Requested' WHERE type = 'Reminder_sync' AND code = 'RMDSYNC'");

        $delete_reminder = $this->db->query("DELETE FROM `lite_b2b`.`query_outstanding_new`");

        $delete_reminder_retailer = $this->db->query("DELETE FROM `lite_b2b`.`query_outstanding_retailer` WHERE updated_by = '' ");

        $data = array(
            'para1' => 0,
            'msg' => 'Send Request To Re-Sync',

        );
        echo json_encode($data);
    }

    public function b2b_reminder_settings()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

            $e_registration_date = $this->input->post('e_registration_date');
            $e_outstanding_date = $this->input->post('e_outstanding_date');
            $e_overdue_date = $this->input->post('e_overdue_date');
            $e_overdue_count_block = $this->input->post('e_overdue_count_block');
            $e_overdue_count_warning = $this->input->post('e_overdue_count_warning');
            $e_overdue_count_gentle = $this->input->post('e_overdue_count_gentle');

            $update_data = $this->db->query("UPDATE `lite_b2b`.`reminder_settings` SET `value` = '$e_registration_date' WHERE `type` = 'registration_date' AND module_name = 'reminder' ");

            $update_data = $this->db->query("UPDATE `lite_b2b`.`reminder_settings` SET `value` = '$e_outstanding_date' WHERE `type` = 'outstanding_date' AND module_name = 'reminder' ");

            $update_data = $this->db->query("UPDATE `lite_b2b`.`reminder_settings` SET `value` = '$e_overdue_date' WHERE `type` = 'overdue_date' AND module_name = 'reminder' ");

            $update_data = $this->db->query("UPDATE `lite_b2b`.`reminder_settings` SET `value` = '$e_overdue_count_block' WHERE `type` = 'overdue_count_block' AND module_name = 'reminder' ");

            $update_data = $this->db->query("UPDATE `lite_b2b`.`reminder_settings` SET `value` = '$e_overdue_count_warning' WHERE `type` = 'overdue_count_warning' AND module_name = 'reminder' ");

            $update_data = $this->db->query("UPDATE `lite_b2b`.`reminder_settings` SET `value` = '$e_overdue_count_gentle' WHERE `type` = 'overdue_count_gentle' AND module_name = 'reminder' ");

            $error = $this->db->affected_rows();

            $data = array(
                'para1' => 0,
                'msg' => 'Updated Successfully',

            );
            echo json_encode($data);
        } else {
            redirect('main_controller');
        }
    }

    public function get_outstanding()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '') {
            $customer_guid = $this->input->post('acc_guid');
            $user_guid = $this->session->userdata('user_guid');
            //print_r($user_guid); die;
            // $user_data = '';
            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {   
            //     //$user_data = '1';
            //     $user_guid = '2B4D2FB93F0011ED8FDC000D3AA2838A'; 
            //     //print_r($user_guid); die; die;
            // }
            //temporary added

            $check_extend_btn = $this->db->query("SELECT `guid` FROM lite_b2b.extend_settings WHERE user_guid = '$user_guid' AND isactive = '1'")->result_array();

            if (count($check_extend_btn) > 0) {
                $button_extend = 'disable_btn';
            }
            else
            {
                $button_extend = 'extend_days';
            }

            $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");

            $result = $this->db->query("SELECT a.*, DATE_FORMAT(Registration_Invoice_Date, '%e-%M-%Y') AS reg_date, DATE_FORMAT(NOW(), '%e-%M-%Y') AS NOW, (Total_Overdue+Last_Invoice_Amt) AS total_amount_due FROM lite_b2b.`query_outstanding_new` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`supplier_guid` = b.`supplier_guid` AND b.`customer_guid` = '$customer_guid' WHERE b.user_guid = '$user_guid' GROUP BY a.`supplier_guid`");

            $check_extend_data = $this->db->query("SELECT a.`customer_guid` FROM lite_b2b.`query_outstanding_retailer` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' AND a.`Variance` = '1' AND a.is_extend = '0' GROUP BY a.`customer_guid`, a.`supplier_guid`")->num_rows();

            $type = '';
            $force_logout = 0;
            $reminder_count = 0;
            $string = '';
            $date_to = '13-January-2021';
            //$result_count = $result->num_rows();
            foreach ($result->result() as $main) {
                $debtorcode = $main->DebtorCode;

                if (($debtorcode == '') || ($debtorcode == null) || ($debtorcode == 'null')) {
                    $data = array(
                        'para1' => 1,
                        'msg' => 'Please Contact Support for Reminder Issue.'
                    );
                    echo json_encode($data);
                    exit();
                } else {
                    $result_child =  $this->db->query("SELECT c.acc_guid,c.acc_name,(Total_Overdue + Last_Invoice_Amt) AS amount_due,IF(c.acc_guid = '$customer_guid' , '1','2') AS seq FROM lite_b2b.query_outstanding_retailer a  LEFT JOIN lite_b2b.acc c ON a.customer_guid = c.acc_guid WHERE a.debtorcode = '$debtorcode' ORDER BY seq ASC");

                    //$result_by_retailer = $this->db->query("SELECT a.*, DATE_FORMAT(Registration_Invoice_Date, '%e-%M-%Y') AS reg_date, DATE_FORMAT(NOW(), '%e-%M-%Y') AS NOW, (Total_Overdue + Last_Invoice_Amt) AS total_amount_due FROM lite_b2b.`query_outstanding_retailer` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' AND a.debtorcode = '$debtorcode' GROUP BY a.`customer_guid`, a.`supplier_guid` ");

                    $result_by_retailer = $this->db->query("SELECT a.*, DATE_FORMAT(Registration_Invoice_Date, '%e-%M-%Y') AS reg_date, DATE_FORMAT(NOW(), '%e-%M-%Y') AS NOW, (Total_Overdue + Last_Invoice_Amt) AS total_amount_due, IF(a.`Variance` = '5' AND c.`day_limit` IS NOT NULL, DATE_FORMAT( DATE_ADD( IFNULL( a.`Overdue_Invoice_Date_To`, a.`updated_at` ), INTERVAL a.`variance_day` DAY ), '%e-%M-%Y' ), IF( a.`Variance` = '5', DATE_FORMAT( DATE_ADD( a.`updated_at`, INTERVAL a.`variance_day` DAY ), '%e-%M-%Y' ), IF( a.`Variance` = '6', DATE_FORMAT( DATE_ADD( a.`updated_at`, INTERVAL a.`variance_day` DAY ), '%e-%M-%Y' ), '' ) ) ) AS until_date FROM lite_b2b.`query_outstanding_retailer` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` LEFT JOIN lite_b2b.`reminder_duration` c ON a.`customer_guid` = c.`customer_guid` AND a.`supplier_guid` = c.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' AND a.debtorcode = '$debtorcode' GROUP BY a.`customer_guid`, a.`supplier_guid` ");

                    $variance = $result_by_retailer->row('Variance');

                    // if($user_data == '1')
                    // {
                    //     //$result_count = $result_by_retailer->num_rows(); 
                    //     $variance = '1';
                    //     //echo $this->db->last_query();die;
                    // }

                    $variance_day = $result_by_retailer->row('variance_day');

                    //$result_count = $result_by_retailer->num_rows();
                }
                //echo $this->db->last_query();die;

                foreach ($result_by_retailer->result() as $row) {
                    if ($variance == 1) {
                        $reminder_count++;
                        $store_supp_guid[] = $row->supplier_guid;
                        $type = 'Blocked';
                        $relation_query = $this->db->query("SELECT a.supplier_guid,a.user_guid FROM lite_b2b.`set_supplier_user_relationship` a WHERE a.`user_guid` = '$user_guid' AND a.customer_guid = '$customer_guid' GROUP BY a.customer_guid, a.`supplier_guid` , a.`user_guid` ")->num_rows();

                        $relation_query_variance = $this->db->query("SELECT a.supplier_guid, a.user_guid, b.`Variance` FROM lite_b2b.`set_supplier_user_relationship` a LEFT JOIN lite_b2b.`query_outstanding_retailer` b ON a.`supplier_guid` = b.`supplier_guid` AND b.`customer_guid` = '$customer_guid' WHERE a.`user_guid` = '$user_guid' AND a.customer_guid = '$customer_guid' AND b.`Variance` = '1' GROUP BY a.`supplier_guid`")->num_rows();

                        if ($relation_query > 1) {

                            if ($relation_query == $relation_query_variance) {
                                $force_logout = 1;
                            } else {
                                $force_logout = 0;
                            }
                        } else {
                            $force_logout = 1;
                        }

                        $from_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_From . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                        // echo $this->db->last_query();die;

                        $to_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_To . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $o_due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $last_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Invoice_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                        // echo $this->db->last_query();die;

                        $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                    } else if ($variance == 2) {
                        $reminder_count++;
                        $type = 'Warning';
                        $force_logout = 0;
                        $from_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_From . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                        // echo $this->db->last_query();die;

                        $to_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_To . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $o_due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $last_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Invoice_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                        // echo $this->db->last_query();die;

                        $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $warning_inv_date_string = $this->db->query("SELECT DATE_FORMAT(DATE_ADD('" . $row->created_at . "', INTERVAL 6 DAY),'%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                    } else if ($variance == 3) {
                        $reminder_count++;
                        $type = 'Gentle Reminder';
                        $force_logout = 0;
                        $last_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Invoice_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                        // echo $this->db->last_query();die;

                        $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        //$string .= 'This is a Gentle REMINDER on <br><br>';

                    } else if ($variance == 6) {
                        $reminder_count++;
                        $type = 'Warning';
                        $force_logout = 0;
                        $from_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_From . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                        // echo $this->db->last_query();die;

                        $to_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Date_To . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $o_due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Overdue_Invoice_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $last_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Invoice_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');
                        // echo $this->db->last_query();die;

                        $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT('" . $row->Last_Due_Date . "','%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        $warning_inv_date_string = $this->db->query("SELECT DATE_FORMAT(DATE_ADD('" . $row->created_at . "', INTERVAL 6 DAY),'%e-%M-%Y') as last_inv_date")->row('last_inv_date');

                        //$warning_inv_date_string = $this->db->query("SELECT DATE_FORMAT(DATE_ADD('".$row->Overdue_Invoice_Date_To."', INTERVAL $interval_reg_date DAY),'%e-%M-%Y') as last_inv_date")->row('last_inv_date'); 

                        //$string .= 'This is the <b>LAST REMINDER</b> on</b><br><br>';

                    } else {
                        if ($reminder_count > 0) {
                            $result_count = 1;
                        } else {
                            $result_count = 0;
                        }
                    }

                    if ($variance == 1) {
                        $string .= '<div class="col-md-12" style="font-size:20px;"><b>Blocked</b></div>';
                        $string .= '<div class="col-md-12"><b><span style="color:red;">Sorry, We have followed up several times but unfortunately still not getting your respond. </span></b></div>';
                        $string .= '<div class="col-md-12">Supplier Name & Registration No : <b>' . $row->supplier_name . ' (' . $row->reg_no . ')</b></div>';
                        $string .= '<div class="col-md-12">Retailer Name : <b>' . $customer_name->row('acc_name') . '</b></div>';

                        $string .= '<div class="col-md-6"><span style="color:red;"><h3>Overdue</h3></span>';
                        $string .= '<p class="blink text-muted well well-sm no-shadow" style="color:black;min-height:200px;margin-bottom:0px;background-color:#fad6d4;" >';
                        $string .= 'Invoice Date : From <b><span style="color:red;">' . $from_inv_date_string . '</span></b> to <b><span style="color:red;">' . $to_inv_date_string . '</span></b><br>';
                        $string .= 'Total Overdue Invoice : ' . $row->Overdue_Invoices_Count . '<br>';
                        $string .= 'Total Overdue : <b>MYR ' . number_format($row->Total_Overdue, 2) . '</b><br>
                          Overdue BreakDown :<br>';
                        $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR ' . number_format($row->Overdue_Registration_Fees, 2) . '</i><br>';
                        $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Monthly Subscriptions : MYR ' . number_format($row->Overdue_Subscriptions_Invoice_Amt, 2) . '</i><br>';

                        if (number_format($row->Overdue_Registration_Fees, 2) == '0.00') {
                            $string .= 'Due Date : <b><span style="color:red;">' . $o_due_inv_date_string . '</span></b></p></div>';
                        } else {
                            $string .= '</p></div>';
                        }

                        if ($row->Last_Invoice_Date != '0000-00-00') {
                            $string .= '<div class="col-md-6"><span style="color:red;"><h3>Outstanding</h3></span>';
                            $string .= '<p class="text-muted well well-sm no-shadow" style="color:black;min-height:200px;margin-bottom:0px;background-color:#d7f7ad;" >';
                            $string .= 'Invoice Date : <b>' . $last_inv_date_string . '</span></b><br>';
                            $string .= 'Invoice Amount : <b>MYR ' . number_format($row->Last_Invoice_Amt, 2) . '</b></div>';
                            //$string .= 'Invoice Due Date : <b><span style="color:red;">'.$due_inv_date_string.'</span></b></p></div>'; 
                        } else {
                            $string .= '';
                        }

                        $string .= '<div class="col-md-12 table-responsive"><span style="color:red;"><h3>Retailer Invoice Amount</h3></span> <table class="table table-striped"> <thead> <tr> <th>Customer Name</th> <th style="float:right;">Total Invoice Amount (MYR) </th> </tr> </thead> <tbody>';

                        foreach ($result_child->result() as $key) {
                            $string .= '<tr>';
                            if ($key->acc_guid == $customer_guid) {
                                $string .= '<td><b>' . $key->acc_name . '</b></td>';
                                $string .= '<td style="float:right;"><b>' . number_format($key->amount_due, 2) . '</b></td>';
                            } else {
                                $string .= '<td>' . $key->acc_name . '</td>';
                                $string .= '<td style="float:right;">' . number_format($key->amount_due, 2) . '</td>';
                            }

                            $string .= '</tr>';
                        }

                        $string .= '</tbody><tfoot><tr><td></td><td><span style="float:right;font-size:3vh">Total Amount : <b>MYR ' . number_format($main->total_amount_due, 2) . ' </b></span></td></tr></tfoot></table></div>';

                        $string .= '<div class="col-md-12">';
                        $string .= '<p class="text-muted well well-sm no-shadow" style="color:black;" >';
                        $string .= 'Please make payment <b><span style="color:red;">NOW!!!</span></b><br>';
                        $string .= 'Please contact xBridge Support Team @ billing@xbridge.my or call us @ +60177451185 / +0177159340 should you require further clarifications.<br>';
                        $string .= 'Please provide payment slip to billing@xbridge.my once payment made. Thank You.<br><br>';
                        $string .= 'Please <b><span style="color:green;">DISREGARD</span></b> this email if payment is made and thank you for your support.<br></p><hr style="height:1px;border-width:0;color:black;background-color:black"></div>';
                    } else if ($variance == 2) {
                        $string .= '<div class="col-md-12" style="font-size:20px;"><b>Warning</b></div>';
                        $string .= '<div class="col-md-12">Supplier Name & Registration No : <b>' . $row->supplier_name . ' (' . $row->reg_no . ')</b></div>';
                        $string .= '<div class="col-md-12">Retailer Name : <b>' . $customer_name->row('acc_name') . '</b></div>';

                        $string .= '<div class="col-md-6"><span style="color:red;"><h3>Overdue</h3></span>';
                        $string .= '<p class="blink text-muted well well-sm no-shadow" style="color:black;min-height:200px;margin-bottom:0px;background-color:#fad6d4;" >';
                        $string .= 'Invoice Date : From <b><span style="color:red;">' . $from_inv_date_string . '</span></b> to <b><span style="color:red;">' . $to_inv_date_string . '</span></b><br>';
                        $string .= 'Total Overdue Invoice : ' . $row->Overdue_Invoices_Count . '<br>';
                        $string .= 'Total Overdue : <b>MYR ' . number_format($row->Total_Overdue, 2) . '</b><br>
                          Overdue BreakDown :<br>';
                        $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR ' . number_format($row->Overdue_Registration_Fees, 2) . '</i><br>';
                        $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Monthly Subscriptions : MYR ' . number_format($row->Overdue_Subscriptions_Invoice_Amt, 2) . '</i><br>';

                        if (number_format($row->Overdue_Registration_Fees, 2) == '0.00') {
                            $string .= 'Due Date : <b><span style="color:red;">' . $o_due_inv_date_string . '</span></b></p></div>';
                        } else {
                            $string .= '</p></div>';
                        }

                        if ($row->Last_Invoice_Date != '0000-00-00') {
                            $string .= '<div class="col-md-6" ><span style="color:red;"><h3>Outstanding</h3></span>';
                            $string .= '<p class="text-muted well well-sm no-shadow" style="color:black;min-height:200px;margin-bottom:0px;background-color:#d7f7ad;">';
                            $string .= 'Invoice Date : <b>' . $last_inv_date_string . '</span></b><br>';
                            $string .= 'Invoice Amount : <b>MYR ' . number_format($row->Last_Invoice_Amt, 2) . '</b></div>';
                            //$string .= 'Invoice Due Date : <b><span style="color:red;">'.$due_inv_date_string.'</span></b></p></div>';        
                        } else {
                            $string .= '';
                        }

                        $string .= '<div class="col-md-12 table-responsive"><span style="color:red;"><h3>Retailer Invoice Amount</h3></span> <table class="table table-striped"> <thead> <tr> <th>Customer Name</th> <th style="float:right;">Total Invoice Amount (MYR) </th> </tr> </thead> <tbody>';

                        foreach ($result_child->result() as $key) {
                            $string .= '<tr>';
                            if ($key->acc_guid == $customer_guid) {
                                $string .= '<td><b>' . $key->acc_name . '</b></td>';
                                $string .= '<td style="float:right;"><b>' . number_format($key->amount_due, 2) . '</b></td>';
                            } else {
                                $string .= '<td>' . $key->acc_name . '</td>';
                                $string .= '<td style="float:right;">' . number_format($key->amount_due, 2) . '</td>';
                            }

                            $string .= '</tr>';
                        }

                        $string .= '</tbody><tfoot><tr><td></td><td><span style="float:right;font-size:3vh">Total Amount : <b>MYR ' . number_format($main->total_amount_due, 2) . ' </b></span></td></tr></tfoot></table><hr style="height:1px;border-width:0;color:black;background-color:black"></div>';
                    } else if ($variance == 3) {
                        $string .= '<div class="col-md-12" style="font-size:20px;"><b>Gentle Reminder</b></div>';
                        $string .= '<div class="col-md-12">Supplier Name & Registration No : <b>' . $row->supplier_name . ' (' . $row->reg_no . ')</b></div>';
                        $string .= '<div class="col-md-12">Retailer Name : <b>' . $customer_name->row('acc_name') . '</b></div>';

                        $string .= '<div class="col-md-12">Invoice Date : <span style="color:red;"><b>' . $last_inv_date_string . '</b></span></div>';
                        //$string .= 'Invoice Due Date : <span style="color:red;"><b>'.$due_inv_date_string.'</b></span></div>';

                        $string .= '<div class="col-md-12 table-responsive"><span style="color:red;"><h3>Retailer Invoice Amount</h3></span> <table class="table table-striped"> <thead> <tr> <th>Customer Name</th> <th style="float:right;">Total Invoice Amount (MYR) </th> </tr> </thead> <tbody>';

                        foreach ($result_child->result() as $key) {
                            $string .= '<tr>';
                            if ($key->acc_guid == $customer_guid) {
                                $string .= '<td><b>' . $key->acc_name . '</b></td>';
                                $string .= '<td style="float:right;"><b>' . number_format($key->amount_due, 2) . '</b></td>';
                            } else {
                                $string .= '<td>' . $key->acc_name . '</td>';
                                $string .= '<td style="float:right;">' . number_format($key->amount_due, 2) . '</td>';
                            }


                            $string .= '</tr>';
                        }

                        $string .= '</tbody><tfoot><tr><td></td><td><span style="float:right;font-size:3vh">Total Amount : <b>MYR ' . number_format($main->total_amount_due, 2) . ' </b></span></td></tr></tfoot></table><hr style="height:1px;border-width:0;color:black;background-color:black"></div>';
                    } else if ($variance == 6) {
                        $string .= '<div class="col-md-12" style="font-size:20px;"><b>Payment Date Extensions</b></div>';
                        $string .= '<div class="col-md-12"><mark style="background-color:yellow;">Payment Extensions is <b> APPROVED </b> for <b>' . $variance_day . ' DAYS.</b> Due Date : <b>' . $row->until_date . '</b>.</mark></div>';
                        $string .= '<div class="col-md-12">Supplier Name & Registration No : <b>' . $row->supplier_name . ' (' . $row->reg_no . ')</b></div>';
                        $string .= '<div class="col-md-12">Retailer Name : <b>' . $customer_name->row('acc_name') . '</b></div>';


                        if ($row->Overdue_Invoices_Count != '0') {
                            $string .= '<div class="col-md-6"><span style="color:red;"><h3>Overdue</h3></span>';
                            $string .= '<p class="blink text-muted well well-sm no-shadow" style="color:black;min-height:200px;margin-bottom:0px;background-color:#fad6d4;" >';
                            $string .= 'Invoice Date : From <b><span style="color:red;">' . $from_inv_date_string . '</span></b> to <b><span style="color:red;">' . $to_inv_date_string . '</span></b><br>';
                            $string .= 'Total Overdue Invoice : ' . $row->Overdue_Invoices_Count . '<br>';
                            $string .= 'Total Overdue : <b>MYR ' . number_format($row->Total_Overdue, 2) . '</b><br>
                            Overdue BreakDown :<br>';
                            $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR ' . number_format($row->Overdue_Registration_Fees, 2) . '</i><br>';
                            $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Monthly Subscriptions : MYR ' . number_format($row->Overdue_Subscriptions_Invoice_Amt, 2) . '</i><br>';
                            //$string .= 'Due Date : <b><span style="color:red;">'.$o_due_inv_date_string.'</span></b></p></div>';

                            // if(number_format($row->Overdue_Registration_Fees,2) == '0.00')
                            // {
                            //     $string .= 'Due Date : <b><span style="color:red;">'.$o_due_inv_date_string.'</span></b></p></div>';
                            // }
                            // else
                            // {
                            $string .= '</p></div>';
                            // }
                        } else {
                            $string .= '<div class="col-md-12">Invoice Date : <span style="color:red;"><b>' . $last_inv_date_string . '</b></span></div>';
                        }

                        if ($row->Last_Invoice_Date != '0000-00-00') {
                            $string .= '<div class="col-md-6" ><span style="color:red;"><h3>Outstanding</h3></span>';
                            $string .= '<p class="text-muted well well-sm no-shadow" style="color:black;min-height:200px;margin-bottom:0px;background-color:#d7f7ad;">';
                            $string .= 'Invoice Date : <b>' . $last_inv_date_string . '</span></b><br>';
                            $string .= 'Invoice Amount : <b>MYR ' . number_format($row->Last_Invoice_Amt, 2) . '</b></div>';
                            //$string .= 'Invoice Due Date : <b><span style="color:red;">'.$due_inv_date_string.'</span></b></p></div>';        
                        } else {
                            $string .= '';
                        }

                        $string .= '<div class="col-md-12 table-responsive"><span style="color:red;"><h3>Retailer Invoice Amount</h3></span> <table class="table table-striped"> <thead> <tr> <th>Customer Name</th> <th style="float:right;">Total Invoice Amount (MYR) </th> </tr> </thead> <tbody>';

                        foreach ($result_child->result() as $key) {
                            $string .= '<tr>';
                            if ($key->acc_guid == $customer_guid) {
                                $string .= '<td><b>' . $key->acc_name . '</b></td>';
                                $string .= '<td style="float:right;"><b>' . number_format($key->amount_due, 2) . '</b></td>';
                            } else {
                                $string .= '<td>' . $key->acc_name . '</td>';
                                $string .= '<td style="float:right;">' . number_format($key->amount_due, 2) . '</td>';
                            }

                            $string .= '</tr>';
                        }

                        $string .= '</tbody><tfoot><tr><td></td><td><span style="float:right;font-size:3vh">Total Amount : <b>MYR ' . number_format($main->total_amount_due, 2) . ' </b></span></td></tr></tfoot></table><hr style="height:1px;border-width:0;color:black;background-color:black"></div>';

                        //$string .= '<div class="col-md-12" ><span style="color:red;float:right;"><h3>Total Amount Due : <b style="color:black;"  class="blink">MYR '.number_format($main->total_amount_due,2).' </b></h3></span><br></div>';

                        // $string .= '<span class="blink" style="color:black;font-size:20px;">Your Login Account will be <span style="color:red;"><b>BLOCKED</b></span> if above overdue payment not pay by <b>'.$warning_inv_date_string.'</b></span> <br><br>'; 

                    } else {
                        if ($reminder_count > 0) {
                            $result_count = 1;
                        } else {
                            $result_count = 0;
                        }
                    }
                }
            }

            if ($reminder_count > 0) {
                $result_count = 1;
            } else {
                $result_count = 0;
            }
            
            $data = array(
                'para1' => 0,
                'result' => $result_count,
                'string' => $string,
                'force_logout' => $force_logout,
                'type' => $type,
                'store_supp_guid' => $store_supp_guid,
                'reminder_count' => $reminder_count,
                'store_user_guid' => $user_guid,
                'check_extend_data' => $check_extend_data,
                'button_extend' => $button_extend,
            );
            echo json_encode($data);
        } else {
            $this->session->set_flashdata('message', 'Session Expired. Please relogin');
            redirect('#');
        }
    }

    public function reminder_duration()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];

            $get_acc = $this->db->query("SELECT a.acc_guid,a.acc_name FROM lite_b2b.acc a WHERE a.isactive = '1' ORDER BY a.acc_name ASC");

            $get_supplier = $this->db->query("SELECT a.supplier_guid, a.supplier_name FROM lite_b2b.set_supplier a WHERE a.`isactive` = '1' AND a.`suspended` = '0' ORDER BY a.supplier_name ASC");

            // echo $bank_list_dropdown;die;
            $data = array(
                'get_acc' => $get_acc->result(),
                'get_supplier' => $get_supplier->result(),
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('query_outstanding/reminder_duration', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function duration_tb()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search = $this->input->post("search");
        $search = addslashes($search['value']);
        $col = 0;
        $dir = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }

        $valid_columns = array(
            0 => 'guid',
            1 => 'acc_name',
            2 => 'supplier_name',
            3 => 'day_limit',
            4 => 'created_at',
            5 => 'created_by',
            6 => 'updated_at',
            7 => 'updated_by',

        );

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if ($order != null) {
            // $this->db->order_by($order, $dir);

            $order_query = "ORDER BY " . $order . "  " . $dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if (!empty($search)) {
            $x = 0;
            foreach ($valid_columns as $sterm) {
                if ($x == 0) {
                    // $this->db->like($sterm,$search);

                    $like_first_query = "WHERE $sterm LIKE '%" . $search . "%'";
                } else {
                    // $this->db->or_like($sterm,$search);

                    $like_second_query .= "OR $sterm LIKE '%" . $search . "%'";
                }
                $x++;
            }
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " . $start . " , " . $length;

        $sql = "SELECT a.*,b.acc_name,c.supplier_name FROM lite_b2b.`reminder_duration` a INNER JOIN lite_b2b.`acc` b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid`";

        $query = "SELECT * FROM ( " . $sql . " ) aa " . $like_first_query . $like_second_query . $order_query . $limit_query;

        $result = $this->db->query($query);

        //echo $this->db->last_query(); die;

        if (!empty($search)) {
            $query_filter = "SELECT * FROM ( " . $sql . " ) a " . $like_first_query . $like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        } else {
            $total = $this->db->query($sql)->num_rows();
        }

        $data = array();
        foreach ($result->result() as $row) {
            $nestedData['guid'] = $row->guid;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['day_limit'] = $row->day_limit;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['updated_at'] = $row->updated_at;
            $nestedData['updated_by'] = $row->updated_by;


            $data[] = $nestedData;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        );

        echo json_encode($output);
    }

    public function add_duration()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
            $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $customer_guid = $this->input->post('new_retailer');
            $supplier_guid = $this->input->post('new_supplier');
            $day_limit = $this->input->post('add_day');

            $check_data = $this->db->query("SELECT customer_guid FROM lite_b2b.reminder_duration WHERE customer_guid = '$customer_guid' AND supplier_guid ='$supplier_guid'  ");

            if ($check_data->num_rows() > 0) {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Duplicate Data',

                );
                echo json_encode($data);
                exit();
            }

            $data = array(
                'guid' => $guid,
                'customer_guid' => $customer_guid,
                'supplier_guid' => $supplier_guid,
                'day_limit' => $day_limit,
                'created_at' => $this->db->query("SELECT NOW() as now")->row('now'),
                'created_by' => $user_id,
            );

            $this->db->insert('lite_b2b.reminder_duration', $data);

            $error = $this->db->affected_rows();

            if ($error > 0) {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Insert Successfully',

                );
                echo json_encode($data);
            } else {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Error',

                );
                echo json_encode($data);
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function update_duration()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
            $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $guid = $this->input->post('guid');
            $customer_guid = $this->input->post('edit_retailer');
            $supplier_guid = $this->input->post('edit_supplier');
            $day_limit = $this->input->post('edit_day');

            $check_data = $this->db->query("SELECT customer_guid FROM lite_b2b.reminder_duration WHERE guid = '$guid'");

            if ($check_data->num_rows() == 0) {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'No Data',

                );
                echo json_encode($data);
                exit();
            }

            $update_data = $this->db->query("UPDATE `lite_b2b`.`reminder_duration` SET `customer_guid` = '$customer_guid', `supplier_guid` = '$supplier_guid' , `day_limit` = '$day_limit' , `updated_at` = NOW() , `updated_by` = '$user_id' WHERE `guid` = '$guid' ");

            $error = $this->db->affected_rows();

            if ($error > 0) {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Update Successfully',

                );
                echo json_encode($data);
            } else {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'No Update',

                );
                echo json_encode($data);
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function delete_duration()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');
            $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $guid = $this->input->post('guid');
            $customer_guid = $this->input->post('new_retailer');
            $supplier_guid = $this->input->post('new_supplier');
            $day_limit = $this->input->post('add_day');

            $check_data = $this->db->query("SELECT customer_guid FROM lite_b2b.reminder_duration WHERE guid = '$guid'");

            if ($check_data->num_rows() == 0) {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'No Data',

                );
                echo json_encode($data);
                exit();
            }

            $update_data = $this->db->query("DELETE FROM lite_b2b.reminder_duration WHERE `guid` = '$guid' ");

            $error = $this->db->affected_rows();

            if ($error > 0) {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Delete Successfully',

                );
                echo json_encode($data);
            } else {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Error',

                );
                echo json_encode($data);
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function outside_extend_days()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $customer_guid = $this->input->post('customer_guid');
            $user_guid = $this->input->post('user_guid');

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {   
            //     $user_guid = '3B37607F299711ED8B53000D3AA2838A'; 
            // }

            $check_data = $this->db->query("SELECT a.`customer_guid`, a.`supplier_guid`, a.`supplier_name`, a.`Variance` FROM lite_b2b.`query_outstanding_retailer` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' AND a.`Variance` = '1' GROUP BY a.`customer_guid`, a.`supplier_guid`");

            // echo $this->db->last_query(); die;

            if ($check_data->num_rows() == 0) {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'No Data Found. Please Contact Support',

                );
                echo json_encode($data);
                exit();
            } else {
                $data = array(
                    'para1' => 'true',
                    'get_supplier' => $check_data->result(),

                );
                echo json_encode($data);
                exit();
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function update_extend_days()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $user_guid = $_SESSION['user_guid'];
            $user_name = $this->db->query("SELECT a.user_name FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_name');
            $customer_guid = $this->input->post('customer_guid');
            $supplier_guid = $this->input->post('select_supplier');
            $variance_day = '7';

            // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
            // {   
            //     $user_guid = '451F40FCC0C211E9A8F2000D3AA2838A'; 
            // }

            $check_data = $this->db->query("SELECT a.`customer_guid`, a.`supplier_guid`, a.`supplier_name`, a.`Variance` FROM lite_b2b.`query_outstanding_retailer` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND a.supplier_guid = '$supplier_guid' AND a.`Variance` = '1' GROUP BY a.`customer_guid`, a.`supplier_guid`");

            if ($check_data->num_rows() == 0) {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'No Data',

                );
                echo json_encode($data);
                exit();
            }

            $url = $this->api_url;

            $to_shoot_url = $url . "/B2b_api_shoot/payment_extend";

            $data = array(
                'customer_guid' => $customer_guid,
                'supplier_guid' => $supplier_guid,
                'user_guid' => $user_guid,
            );
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $result = curl_exec($ch);
            //echo $result; die;
            $output = json_decode($result);

            if ($output->status == "true") {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Request Extend Successfully',

                );
                echo json_encode($data);
            } else {
                $data = array(
                    'para1' => 'false',
                    'msg' => 'Request Extend Failed. Please Contact Support',

                );
                echo json_encode($data);
            }
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function view_last_gr_date()
    {
        $customer_guid = $this->input->post('customer_guid');
        $supplier_guid = $this->input->post('supplier_guid');

        $query = $this->db->query("SELECT a.refno,a.status,a.grdate,a.postdatetime FROM b2b_summary.grmain a INNER JOIN lite_b2b.set_supplier_group b ON a.code = b.supplier_group_name AND a.customer_guid = b.customer_guid WHERE a.customer_guid = '$customer_guid' AND b.supplier_guid = '$supplier_guid' ");

        //$query = $this->db->query("SELECT a.refno,a.status,a.grdate,a.postdatetime FROM b2b_summary.grmain a INNER JOIN lite_b2b.set_supplier_group b ON a.code = b.supplier_group_name AND a.customer_guid = b.customer_guid WHERE a.customer_guid = '$customer_guid' AND b.supplier_guid = '$supplier_guid' AND a.grdate IN (SELECT MAX(a.grdate) AS max_grdate FROM b2b_summary.grmain a INNER JOIN lite_b2b.set_supplier_group b ON a.code = b.supplier_group_name AND a.customer_guid = b.customer_guid WHERE a.customer_guid = '$customer_guid' AND b.supplier_guid = '$supplier_guid')");

        $data = array(  
            'query' => $query->result(),
        );

        echo json_encode($data);
    }
}
