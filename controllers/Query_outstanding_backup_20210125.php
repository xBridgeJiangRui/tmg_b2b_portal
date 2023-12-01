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
    }

    public function get_outstanding()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        {
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            // $user_guid = '00647A6F016511EBAAD5000D3AA2838A';
            $customer_name = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
            $result = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM daniel_invoice_email.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' GROUP BY a.`customer_guid`, a.`supplier_guid`");
            // echo $this->db->last_query();die;
            $result2 = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM daniel_invoice_email_one_off.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '$customer_guid' AND b.user_guid = '$user_guid' GROUP BY a.`customer_guid`, a.`supplier_guid`");
            // $result = $this->db->query("SELECT a.*,DATE_FORMAT(Registration_date, '%e-%M-%Y') as reg_date,DATE_FORMAT(NOW(), '%e-%M-%Y') as now FROM daniel_invoice_email.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = 'D361F8521E1211EAAD7CC8CBB8CC0C93' AND b.user_guid = 'B153DE64038F11EB915C000D3AA2838A' GROUP BY a.`customer_guid`, a.`supplier_guid`");
            // echo $this->db->last_query();die;
            // print_r($result->result());die;
            // $result2 = $this->db->query("SELECT a.*, DATE_FORMAT(Registration_date, '%e-%M-%Y') AS reg_date, DATE_FORMAT(NOW(), '%e-%M-%Y') AS now FROM daniel_invoice_email_one_off.`query_outstanding` a INNER JOIN lite_b2b.`set_supplier_user_relationship` b ON a.`customer_guid` = b.customer_guid AND a.`supplier_guid` = b.`supplier_guid` WHERE a.`customer_guid` = '1F90F5EF90DF11EA818B000D3AA2CAA9' AND b.user_guid = '0665B3069F0011EA9E39000D3AA2838A' GROUP BY a.`customer_guid`, a.`supplier_guid`");

            // $string = 'This is the <b><span style="color:red;">2nd REMINDER</span></b> for the <b>TOTAL</b> Overdue Amount to <b>REXBRIDGE SDN BHD</b>.<br><br>';
            $force_logout = 0;
            $string = '';
            $date_to = '13-January-2021';
            $result_count = $result->num_rows() + $result2->num_rows();               
            foreach($result->result() as $row)
            {
                if($row->type == 1)
                {
                    $force_logout = 1;
                    $string .= 'Your Login Account is <b><span style="color:red;"> BLOCKED!!!</span></b><br><b><span style="color:red;">We have followed up several times but unfortunately still not getting your respond. </span></b><br><br>This is the <b>TOTAL</b> Overdue Amount to <b>REXBRIDGE SDN BHD</b>.<br><br>';
                }
                else if($row->type == 2)
                {
                    $string .= 'This is the <b><span style="color:red;">LAST REMINDER</span></b> for the <b>TOTAL</b> Overdue Amount to <b>REXBRIDGE SDN BHD</b>.<br><br>';
                }
                else if($row->type == 3)
                {
                    $supplier_guid = $row->supplier_guid;
                    $last_inv_date = $this->db->query("SELECT DATE_FORMAT((SELECT DATE_ADD(MAX(inv_date),INTERVAL 29 DAY) FROM b2b_invoice.`supplier_monthly_main` WHERE biller_guid = a.supp_guid ORDER BY inv_date DESC LIMIT 1), '%e-%M-%Y') AS due_inv_date,DATE_FORMAT((SELECT MAX(inv_date) FROM b2b_invoice.`supplier_monthly_main` WHERE biller_guid = a.supp_guid ORDER BY inv_date DESC LIMIT 1), '%e-%M-%Y') AS last_inv_date FROM (SELECT a.*, a.biller_guid AS supp_guid FROM b2b_invoice.`supplier_monthly_main` a INNER JOIN b2b_invoice.`supplier_monthly_child` c ON a.`inv_guid` = c.inv_guid AND c.type = 'total_transaction' AND c.`customer_guid` = '$customer_guid' AND c.`supplier_guid` = '$supplier_guid' LEFT JOIN b2b_invoice.`group_supplier` b ON a.`biller_guid` = b.`sub_supp_guid` WHERE inv_status NOT IN ('paid', 'cn') AND b.`sub_supp_guid` IS NULL AND a.`biller_guid` = '$supplier_guid' UNION ALL SELECT a.*, main_supp_guid AS supp_guid FROM b2b_invoice.`supplier_monthly_main` a INNER JOIN b2b_invoice.`supplier_monthly_child` c ON a.`inv_guid` = c.`inv_guid` AND c.type = 'total_transaction' AND c.`customer_guid` = '$customer_guid' AND c.`supplier_guid` = '$supplier_guid' INNER JOIN b2b_invoice.`group_supplier` b ON a.`biller_guid` = b.`sub_supp_guid` WHERE inv_status NOT IN ('paid', 'cn') AND a.`biller_guid` = '$supplier_guid') a GROUP BY supp_guid");
                    if($last_inv_date->num_rows() > 0)
                    {
                        $last_inv_date_string = $last_inv_date->row('last_inv_date');
                        $due_inv_date_string = $last_inv_date->row('due_inv_date');
                    }
                    else
                    {
                        $last_inv_date_string = $row->reg_date;
                        $due_inv_date_string = $this->db->query("SELECT DATE_FORMAT(DATE_ADD(STR_TO_DATE('$last_inv_date_string','%e-%M-%Y'),INTERVAL 29 DAY),'%e-%M-%Y') as due_inv_date")->row('due_inv_date') ;

                    }
                    $string .= 'This is a Gentle REMINDER on Invoice Dated: <b><i>'.$last_inv_date_string.'</i></b> to <b>REXBRIDGE SDN BHD</b>.<br><br>';
                } 
                else
                {
                    $result_count = 0;
                } 

                if($row->type == 1)
                {
                    $string .= 'Retailer Name : <b>'.$customer_name->row('acc_name').'</b><br>';
                    $string .= 'Name : <b>'.$row->Supplier.'</b><br>';
                    $string .= 'Company Registration No : <b>'.$row->Reg_NO.'</b><br>';
                    $string .= 'Date : From <b><span style="color:red;">'.$row->reg_date.'</span></b> to <b><span style="color:red;">'.$date_to.'</span></b><br>';
                    $string .= 'Total Overdue : <b>MYR '.number_format($row->Total_Overdue,2).'</b><br>
                      Overdue BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR '.number_format($row->Overdue_Registration_Fees,2).'</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Monthly Subscriptions : MYR '.number_format($row->Overdue_Subscriptions,2).'</i><br><br>';

                    $string .= 'Please make payment <b><span style="color:red;">NOW!!!</span></b><br>';
                    $string .= 'Please contact xBridge Support Team @ support@xbridge.my or call us @ +60177451185 / +0177159340 should you require further clarifications.<br>'; 
                    $string .= 'Please provide payment slip to support@xbridge.my once payment made. Thank You.<br><br>';
                     $string .= 'Please  <b><span style="color:green;">DISREGARD</span></b> this email if payment is made and thank you for your support.<br>';
                }
                else if($row->type == 2)
                {
                    $string .= 'Retailer Name : <b>'.$customer_name->row('acc_name').'</b><br>';
                    $string .= 'Name : <b>'.$row->Supplier.'</b><br>';
                    $string .= 'Company Registration No : <b>'.$row->Reg_NO.'</b><br>';
                    $string .= 'Date : From <b><span style="color:red;">'.$row->reg_date.'</span></b> to <b><span style="color:red;">'.$date_to.'</span></b><br>';
                    $string .= 'Total Overdue : <b>MYR '.number_format($row->Total_Overdue,2).'</b><br>
                      Overdue BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR '.number_format($row->Overdue_Registration_Fees,2).'</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Monthly Subscriptions : MYR '.number_format($row->Overdue_Subscriptions,2).'</i><br><br>'; 
                    $string .= '<span id="blink" style="color:black;font-size:20px;">Your Login Account will be <span style="color:red;"><b>BLOCKED</b></span> if above overdue payment not pay by <b>22-January-2021</b></span> <br><br>'; 
                }
                else if($row->type == 3)
                {
                    $string .= 'Retailer Name : <b>'.$customer_name->row('acc_name').'</b><br>';
                    $string .= 'Name : <b>'.$row->Supplier.'</b><br>';
                    $string .= 'Company Registration No : <b>'.$row->Reg_NO.'</b><br>';
                    $string .= 'Invoice Date : <b>'.$last_inv_date_string.'</span></b><br>';
                    $string .= 'Invoice Total Amount : <b>MYR '.number_format($row->Total_Overdue,2).'</b><br>
                      Invoice Amount BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Registration/Training Fees : MYR '.number_format($row->Overdue_Registration_Fees,2).'</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Monthly Subscriptions : MYR '.number_format($row->Overdue_Subscriptions,2).'</i><br>'; 
                    $string .= 'Invoice Due Date : <b>'.$due_inv_date_string.'</span></b><br><br>';
                }    
                else
                {
                    $result_count = 0;
                }                                                            
                       
            }
            foreach($result2->result() as $row)
            {
                if($row->type == 1)
                {
                    $force_logout = 1;
                    $string .= 'Your Login Account is <b><span style="color:red;"> BLOCKED!!!</span></b><br><b><span style="color:red;">We have followed up several times but unfortunately still not getting your respond. </span></b><br><br>This is the <b>TOTAL</b> Overdue Amount to <b>REXBRIDGE SDN BHD</b>.<br><br>';
                }
                else if($row->type == 2)
                {
                    $string .= 'This is the <b><span style="color:red;">LAST REMINDER</span></b> for the <b>TOTAL</b> Overdue Amount to <b>REXBRIDGE SDN BHD</b>.<br><br>';
                }
                else if($row->type == 3)
                {
                    $string .= 'This is the <b>Gentle REMINDER</b> on Overdue Amount Last Invoice dated <b>'.date("d-M-y",strtotime("last day of previous month")).'</b> to <b>REXBRIDGE SDN BHD</b>.<br><br>';
                } 
                else
                {
                    $result_count = 0;
                }                 

                if($row->type == 1)
                {
                    $string .= 'Retailer Name : <b>'.$customer_name->row('acc_name').'</b><br>';
                    $string .= 'Name : <b>'.$row->Supplier.'</b><br>';
                    $string .= 'Company Registration No : <b>'.$row->Reg_NO.'</b><br>';
                    $string .= 'Date : From <b><span style="color:red;">'.$row->reg_date.'</span></b> to <b><span style="color:red;">'.$date_to.'</span></b><br>';
                    $string .= 'Total Overdue : <b>MYR '.number_format($row->Total_Overdue,2).'</b><br>
                      Overdue BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR '.number_format($row->Overdue_Registration_Fees,2).'</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue One-Off Subscriptions : MYR '. number_format($row->Overdue_Subscriptions,2).'</i><br>';

                    $string .= 'Please make payment <b><span style="color:red;">NOW!!!</span></b><br>';
                    $string .= 'Please contact xBridge Support Team @ support@xbridge.my or call us @ +60177451185 / +0177159340 should you require further clarifications.<br>'; 
                    $string .= 'Please provide payment slip to support@xbridge.my once payment made. Thank You.<br><br>';
                     $string .= 'Please  <b><span style="color:green;">DISREGARD</span></b> this email if payment is made and thank you for your support.<br>';                    
                }
                else if($row->type == 2)
                {
                    $string .= 'Retailer Name : <b>'.$customer_name->row('acc_name').'</b><br>';
                    $string .= 'Name : <b>'.$row->Supplier.'</b><br>';
                    $string .= 'Company Registration No : <b>'.$row->Reg_NO.'</b><br>';
                    $string .= 'Date : From <b><span style="color:red;">'.$row->reg_date.'</span></b> to <b><span style="color:red;">'.$date_to.'</span></b><br>';
                    $string .= 'Total Overdue : <b>MYR '.number_format($row->Total_Overdue,2).'</b><br>
                      Overdue BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR '.number_format($row->Overdue_Registration_Fees,2).'</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue One-Off Subscriptions : MYR '. number_format($row->Overdue_Subscriptions,2).'</i><br><br>'; 
                }
                else if($row->type == 3)
                {
                    $string .= 'Retailer Name : <b>'.$customer_name->row('acc_name').'</b><br>';
                    $string .= 'Name : <b>'.$row->Supplier.'</b><br>';
                    $string .= 'Company Registration No : <b>'.$row->Reg_NO.'</b><br>';
                    $string .= 'Date : From <b><span style="color:red;">'.$row->reg_date.'</span></b> to <b><span style="color:red;">'.$date_to.'</span></b><br>';
                    $string .= 'Total Overdue : <b>MYR '.number_format($row->Total_Overdue,2).'</b><br>
                      Overdue BreakDown :<br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue Registration/Training Fees : MYR '.number_format($row->Overdue_Registration_Fees,2).'</i><br>';
                    $string .= '<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overdue One-Off Subscriptions : MYR '. number_format($row->Overdue_Subscriptions,2).'</i><br><br>'; 
                }                                  
                else
                {
                    $result_count = 0;
                }    

            }      
            
            $data = array(
                'result' => $result_count,
                'string' => $string,
                'force_logout' => $force_logout,
            );
            echo json_encode($data);
            // echo 1;die;
        }
        else
        {
            echo 'error';die;
        }
    } 

    public function reminder()
    {
            // echo 1;die;
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
                $user_guid = $_SESSION['user_guid']; 
                $database1 = 'b2b_invoice';

                $type_list = $this->db->query("SELECT * FROM lite_b2b.set_setting WHERE module_name = 'reminder' ORDER BY code ASC");
                
                $type_list_dropdown = '';
                $type_list_dropdown = '<select style="width:100%" class="select" name="reminder_type" id="reminder_type">';
                foreach($type_list->result() as $row)
                {
                    $type_list_dropdown .= '<option value="'.$row->code.'">'.$row->reason.'</option>';
                }
                $type_list_dropdown .= '</select>';

                // echo $bank_list_dropdown;die;
                $data = array(
                    'type_list_dropdown' => addslashes($type_list_dropdown),
                );

                $this->panda->get_uri();
                $this->load->view('header');
                $this->load->view('query_outstanding/query_outstanding', $data);
                $this->load->view('footer');  

            }
            else
            {
                redirect('main_controller');
            }  
    }  

    public function reminder_table()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
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
                $search= $this->input->post("search");
                $search = $search['value'];
                $col = 0;
                $dir = "";
                // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

                $order_query = "";

                if(!empty($order))
                {
                  foreach($order as $o)
                  {
                      $col = $o['column'];
                      $dir= $o['dir'];

                      $order_query .= $columns[$col]." ".$dir.",";

                  }
                }      
                $order_query = rtrim($order_query,',');
                $database1 = 'lite_b2b';
                $database2 = 'b2b_invoice';
                $type = 'old';
                if($type == 'old')
                {
                    $database3 = 'daniel_invoice_email';
                    $table3 = 'query_outstanding';
                }
                else
                {
                    $database3 = 'lite_b2b';
                    $table3 = 'query_outstanding';   
                }                
                // echo $order_query;die;
                if(in_array('IAVA',$_SESSION['module_code']))
                {                
                    $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
                }
                else
                {
                    $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
                }
                $query2 = " ORDER BY " .$order_query. " LIMIT " .$start. " , " .$limit. ";";
                

                // echo $query.$query2;die;
                // $receipt_list = $this->db->query("$execute_query");
                // echo $this->db->last_query();die;
                // print_r($receipt_list->result());die;

                if(empty($this->input->post('search')['value']))
                {
                    $execute_query = $query.$query2;
                    $posts = $this->db->query("$execute_query");
                    $totalDataquery = $this->db->query("$query");;
                    $totalData = $totalDataquery->num_rows();
                    $totalFiltered = $totalData;
                    // echo $this->db->last_query();die;
                }
                else 
                {
                    $search = addslashes($this->input->post('search')['value']); 
                    $search_query = " WHERE (Retailer LIKE '%$search%' OR Supplier LIKE '%$search%' OR Reg_NO LIKE '%$search%' OR Registration_date LIKE '%$search%' OR Overdue_Registration_Fees LIKE '%$search%' OR Overdue_Subscriptions LIKE '%$search%' OR Total_Overdue LIKE '%$search%' OR type_name LIKE '%$search%')";
                    $execute_query = "SELECT * FROM "."(".$query.") a ".$search_query.$query2;
                    $execute_query_count = "SELECT count(*) as count FROM "."(".$query.") a ".$search_query;
                    // echo $execute_query;die; 

                    $posts =  $this->db->query("$execute_query");
                    $totalData = $this->db->query("$execute_query")->num_rows('count');
                    // echo $this->db->last_query();die;

                    $totalFiltered = $totalData;
                    // echo $totalFiltered;die;
                }
                // print_r($posts->result());die;

                $data = array();
                if(!empty($posts))
                {                   
                    foreach ($posts->result() as $post)
                    {    
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
                        $e_table_type = 'not_one_off';                      
                        
                        $attr = 'e_supplier_guid = "'.$e_supplier_guid.'" e_customer_guid = "'.$e_customer_guid.'" e_Retailer = "'.$e_Retailer.'" e_Supplier = "'.$e_Supplier.'" e_Reg_NO = "'.$e_Reg_NO.'" e_Overdue_Registration_Fees = "'.$e_Overdue_Registration_Fees.'" e_Overdue_Subscriptions = "'.$e_Overdue_Subscriptions.'" e_Total_Overdue = "'.$e_Total_Overdue.'" e_Registration_date = "'.$e_Registration_date.'" e_type = "'.$e_type.'" e_table_type = "'.$e_table_type.'"';                        
                        $update_button = '<button '.$attr.' class="btn btn-xs btn-primary" id="edit_reminder"><i class="fa fa-pencil"></i></button>';

                        $delete_button = '<button '.$attr.' class="btn btn-xs btn-danger" id="delete_reminder"><i class="fa fa-trash"></i></button>';

                        $nestedData['Retailer'] = $e_Retailer;
                        $nestedData['Supplier'] = $e_Supplier;
                        $nestedData['Reg_NO'] = $e_Reg_NO;
                        $nestedData['Overdue_Registration_Fees'] = $e_Overdue_Registration_Fees;
                        $nestedData['Overdue_Subscriptions'] = $e_Overdue_Subscriptions;
                        $nestedData['Total_Overdue'] = $e_Total_Overdue;
                        $nestedData['Registration_date'] = $e_Registration_date;
                        $nestedData['type_name'] = $e_type_name;
                        $nestedData['action'] = $update_button.$delete_button;
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

            }
            else
            {
                redirect('main_controller');
            }           
    } 

    public function reminder_update()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {     
            $user_guid = $_SESSION['user_guid']; 
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
            $type = 'old';

            if($reminder_table_type == 'not_one_off')
            {
                if($type == 'old')
                {
                    $database = 'daniel_invoice_email';
                    $table = 'query_outstanding';
                }
                else
                {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding';   
                }
                $this->db->query("UPDATE $database.$table SET type = '$reminder_type',updated_at=NOW(),updated_by='$user_guid' WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid'");
            }
            if($reminder_table_type == 'one_off')
            {
                if($type == 'old')
                {
                    $database = 'daniel_invoice_email_one_off';
                    $table = 'query_outstanding';
                }
                else
                {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding_one_off';   
                }
                $this->db->query("UPDATE $database.$table SET type = '$reminder_type',updated_at=NOW(),updated_by='$user_guid' WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid'");
            }            
            $this->session->set_flashdata('message', 'Updated Sucessfully');
            redirect('Query_outstanding/reminder');            
            print_r($this->input->post());die;
        }
        else
        {
            redirect('main_controller');
        }  
    } 

    public function reminder_delete()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {     
            $user_guid = $_SESSION['user_guid']; 
            $database1 = 'b2b_invoice';

            $reminder_customer_guid = $this->input->post('reminder_customer_guid');
            $reminder_supplier_guid = $this->input->post('reminder_supplier_guid');
            $reminder_table_type = $this->input->post('reminder_table_type');
            $reminder_type = $this->input->post('reminder_type');
            $type = 'old';
            // print_r($this->input->post());die;

            if($reminder_table_type == 'not_one_off')
            {
                if($type == 'old')
                {
                    $database = 'daniel_invoice_email';
                    $table = 'query_outstanding';
                }
                else
                {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding';   
                }
                $this->db->query("DELETE FROM $database.$table WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid' AND type='$reminder_type'");
            }
            if($reminder_table_type == 'one_off')
            {
                if($type == 'old')
                {
                    $database = 'daniel_invoice_email_one_off';
                    $table = 'query_outstanding';
                }
                else
                {
                    $database = 'lite_b2b';
                    $table = 'query_outstanding_one_off';   
                }
                $this->db->query("DELETE FROM $database.$table WHERE supplier_guid = '$reminder_supplier_guid' AND customer_guid = '$reminder_customer_guid' AND type='$reminder_type'");
            }            
            $this->session->set_flashdata('message', 'Deleted Sucessfully');
            redirect('Query_outstanding/reminder');            
            print_r($this->input->post());die;
        }
        else
        {
            redirect('main_controller');
        }  
    }    

    public function reminder_one_off_table()
    {
            if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
            {     
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
                $search= $this->input->post("search");
                $search = $search['value'];
                $col = 0;
                $dir = "";
                // print_r($limit.'-'.$draw.'-'.$start.'-'.$length.'-'.$order.'-'.$search.'-'.$search.'-');die;

                $order_query = "";

                if(!empty($order))
                {
                  foreach($order as $o)
                  {
                      $col = $o['column'];
                      $dir= $o['dir'];

                      $order_query .= $columns[$col]." ".$dir.",";

                  }
                }      
                $order_query = rtrim($order_query,',');
                $database1 = 'lite_b2b';
                $database2 = 'b2b_invoice';
                $type = 'old';
                if($type == 'old')
                {
                    $database3 = 'daniel_invoice_email_one_off';
                    $table3 = 'query_outstanding';
                }
                else
                {
                    $database3 = 'lite_b2b';
                    $table3 = 'query_outstanding_one_off';   
                }
                // echo $order_query;die;
                if(in_array('IAVA',$_SESSION['module_code']))
                {                
                    $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
                }
                else
                {
                    $query = "SELECT a.*,b.acc_name as Retailer,c.reason as type_name
                     FROM $database3.$table3 a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid INNER JOIN lite_b2b.set_setting c ON c.module_name='reminder' AND a.type = c.code";
                }
                $query2 = " ORDER BY " .$order_query. " LIMIT " .$start. " , " .$limit. ";";
                

                // echo $query.$query2;die;
                // $receipt_list = $this->db->query("$execute_query");
                // echo $this->db->last_query();die;
                // print_r($receipt_list->result());die;

                if(empty($this->input->post('search')['value']))
                {
                    $execute_query = $query.$query2;
                    $posts = $this->db->query("$execute_query");
                    $totalDataquery = $this->db->query("$query");;
                    $totalData = $totalDataquery->num_rows();
                    $totalFiltered = $totalData;
                    // echo $this->db->last_query();die;
                }
                else 
                {
                    $search = addslashes($this->input->post('search')['value']); 
                    $search_query = " WHERE (Retailer LIKE '%$search%' OR Supplier LIKE '%$search%' OR Reg_NO LIKE '%$search%' OR Registration_date LIKE '%$search%' OR Overdue_Registration_Fees LIKE '%$search%' OR Overdue_Subscriptions LIKE '%$search%' OR Total_Overdue LIKE '%$search%' OR Total_Overdue LIKE '%$search%' OR type_name LIKE '%$search%')";
                    $execute_query = "SELECT * FROM "."(".$query.") a ".$search_query.$query2;
                    $execute_query_count = "SELECT count(*) as count FROM "."(".$query.") a ".$search_query;
                    // echo $execute_query;die; 

                    $posts =  $this->db->query("$execute_query");
                    $totalData = $this->db->query("$execute_query")->num_rows('count');
                    // echo $this->db->last_query();die;

                    $totalFiltered = $totalData;
                    // echo $totalFiltered;die;
                }
                // print_r($posts->result());die;

                $data = array();
                if(!empty($posts))
                {                   
                    foreach ($posts->result() as $post)
                    {    
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
                        
                        $attr = 'e_supplier_guid = "'.$e_supplier_guid.'" e_customer_guid = "'.$e_customer_guid.'" e_Retailer = "'.$e_Retailer.'" e_Supplier = "'.$e_Supplier.'" e_Reg_NO = "'.$e_Reg_NO.'" e_Overdue_Registration_Fees = "'.$e_Overdue_Registration_Fees.'" e_Overdue_Subscriptions = "'.$e_Overdue_Subscriptions.'" e_Total_Overdue = "'.$e_Total_Overdue.'" e_Registration_date = "'.$e_Registration_date.'" e_type = "'.$e_type.'" e_table_type = "'.$e_table_type.'"';                        
                        $update_button = '<button '.$attr.' class="btn btn-xs btn-primary" id="edit_reminder"><i class="fa fa-pencil"></i></button>';

                        $delete_button = '<button '.$attr.' class="btn btn-xs btn-danger" id="delete_reminder"><i class="fa fa-trash"></i></button>';

                        $nestedData['Retailer'] = $e_Retailer;
                        $nestedData['Supplier'] = $e_Supplier;
                        $nestedData['Reg_NO'] = $e_Reg_NO;
                        $nestedData['Overdue_Registration_Fees'] = $e_Overdue_Registration_Fees;
                        $nestedData['Overdue_Subscriptions'] = $e_Overdue_Subscriptions;
                        $nestedData['Total_Overdue'] = $e_Total_Overdue;
                        $nestedData['Registration_date'] = $e_Registration_date;
                        $nestedData['type_name'] = $e_type_name;
                        $nestedData['action'] = $update_button.$delete_button;
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

            }
            else
            {
                redirect('main_controller');
            }           
    }            
}
?>
