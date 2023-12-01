<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consignment_b2b_report extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Export_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        // $this->load->helper('form');
        // $this->load->helper('url');
        // $this->load->helper(array('form','url'));
        // $this->load->helper('html');
        // $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer'); 
        $this->api_url = '127.0.0.1/rest_b2b/index.php/';
        $this->jasper_ip = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'),'web','general_doc','jasper_invoice_ip','GDJIIP');
    }

    public function index()
    {

        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   

            $request_link = site_url('Consignment_b2b_report/consignment_report');
            $user_guid = $_SESSION['user_guid'];
            $customer_guid = $_SESSION['customer_guid'];
            
            if(in_array('IAVA',$_SESSION['module_code']))
            {
                $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE customer_guid = '$customer_guid' GROUP BY supplier_group_guid) a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC");

                //echo $this->db->last_query(); die;
            }
            else
            {
                $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC"); 
            }

            if($_REQUEST['link'] == 'consignment_sales_report_summary_b2b'){
                $report_title = 'Consign Report Summary New';
            }else if($_REQUEST['link'] == 'consignment_daily_sales_report'){
                $report_title = 'Consign Daily Sales Report';
            }else{
                $report_title = 'Consign Report';
            }

            $data = array(
                'location' => $location,
                'code' => $code,
                'request_link' => $request_link,
                'report_title' => $report_title
            );
            
            $this->load->view('header');
            $this->load->view('Consignment/consignment_b2b_report',$data);
            $this->load->view('footer'); 
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }//close consignment_report_mul_loc
    
    public function consignment_report()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('customer_guid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        { 

            if(isset($_REQUEST['Date_From']) && isset($_REQUEST['Date_To']) && isset($_REQUEST['main_loc']) && isset($_REQUEST['Consign_code']))
            {

                $report_type = $_REQUEST['report_guid'];
                $Date_From = $_REQUEST['Date_From'];
                $Date_To = $_REQUEST['Date_To'];
                $main_loc = $_REQUEST['main_loc'];
                $Consign_code = urlencode($_REQUEST['Consign_code']);
                $customer_guid = $this->session->userdata('customer_guid');
                $user_guid = $this->session->userdata('user_guid');

                $from_module = 'consign_report';
                $check_limit_start = $this->db->query("SELECT * FROM lite_b2b.`consign_limiter` WHERE customer_guid = '$customer_guid' AND sup_code = '$Consign_code' AND '$Date_From' > limit_date");
                // echo $this->db->last_query();
                $limit_start = $check_limit_start->row('limit_date');
                if($check_limit_start->num_rows() > 0)
                {
                    echo 'Date From is exceeded maximum setting for business day('.$limit_start.'). Please contact admin for confirmation.';
                    exit();
                }

                $check_limit_end = $this->db->query("SELECT * FROM lite_b2b.`consign_limiter` WHERE customer_guid = '$customer_guid' AND sup_code = '$Consign_code' AND '$Date_To' > limit_date");
                // echo $this->db->last_query();die;
                $limit_end = $check_limit_end->row('limit_date');
                if($check_limit_end->num_rows() > 0)
                {
                    echo 'Date To is exceeded maximum setting for business day('.$limit_end.'). Please contact admin for confirmation.';
                    exit();
                }

                if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                {
                    
                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'viewed_consign'
                    , '$from_module'
                    , ''
                    , now()
                    ");
                
                };

                $db_be = $this->db->query("SELECT b2b_database FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');

                $jasper_url = 'http://192.168.8.243:59090';

                $report_template = $this->db->query("SELECT * FROM jasper_report_template WHERE report_type = '$report_type'")->row('report_template');
                //$report_template = 'Consignment_Sales_Report_New';

                $report_ip = $this->jasper_ip.'/jasperserver/rest_v2/reports/reports/B2BReports/'.$report_template.'.pdf'.'?Date_From='.$Date_From.'&Date_To='.$Date_To.'&db_be='.$db_be.'&supcode='.$Consign_code.$main_loc;
                // echo $report_ip;die;
                $url = $report_ip;
                //echo $url; die;
                // echo 'report_guid = '.$report_guid.'<br>';
                // echo 'Date_From = '.$Date_From.'<br>';
                // echo 'Date_To = '.$Date_To.'<br>';
                // echo 'main_loc = '.$main_loc.'<br>';
                // echo 'Consign_code = '.$Consign_code.'<br>';

                $userid = $this->session->userdata('userid');
                $this->db->query("INSERT INTO lite_b2b.`jasper_report_log` SELECT REPLACE(UPPER(UUID()),'-','') AS guid, '$report_template' AS report_type, '$userid' AS user_id, '$url' AS post_url, NOW() AS date_added");

                $curl = curl_init();

                $url = $url;

                //echo $url;die;

                curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Cache-Control: public",
                    "Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==",
                    "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
                ),
                // CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
                CURLOPT_SSL_VERIFYPEER => false,        //
                // CURLOPT_VERBOSE        => 1,
                ));

                // if ($type == 'download') {
                //     $disposition = 'attachment';
                // } elseif($type == 'view') {
                //     $disposition = 'inline';
                // } else {
                //     echo 'Variable type not found';die;
                // }

                $response = curl_exec($curl);
                header('Content-type: ' . 'application/pdf');
                header('Content-Disposition: ' .$disposition.'; filename=Consignment_report.pdf');
                echo $response; die;

                curl_close($curl);     
            
            }
            else
            {   
                echo 'Please select input.';
                exit();
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }

    }//close jasper_report_multiple_loc 

    public function consignment_sum_daily_list()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $user_guid = $this->session->userdata('user_guid');
            $customer_guid = $this->session->userdata('customer_guid');

            if(in_array('IAVA',$_SESSION['module_code']))
            {
                $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE customer_guid = '$customer_guid' GROUP BY supplier_group_guid) a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC");

                //echo $this->db->last_query(); die;
            }
            else
            {
                $code = $this->db->query("SELECT c.Code,c.Name FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE consign = 1 AND customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC"); 
            }

            $data = array(
                'location' => $location,
                'code' => $code,
            );

            $this->load->view('header');
            $this->load->view('Consignment/consignment_daily_sales', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function consign_sum_daily_table()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0); 
  
      $customer_guid = $this->session->userdata('customer_guid');
      $user_guid = $this->session->userdata('user_guid');
      $supplier_code = $this->input->post('consign_code');
      $location = $this->input->post('consign_location');
      $date_start = $this->input->post('date_start');
      $date_end = $this->input->post('date_end');

      //print_r($date_end); die;

      $location = implode("','",$this->input->post('consign_location'));

      $database1 = 'lite_b2b';
      $database2 = $this->db->query("SELECT b2b_database FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');
      $acc_name = $this->db->query("SELECT acc_name FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('acc_name');

      $query_data = $this->db->query("SELECT 
        COUNT(a.itemcode) AS group_count,
        e.supplier_name,
        a.code AS supplier_code,
        a.bizdate,
        a.loc_group,
        IFNULL(b.branch_name,'') AS location_name,
        a.itemcode,
        a.barcode,
        a.description,
        IFNULL(f.um,'') AS uom,
        a.qty AS sum_qty,
        a.amount AS total_amount,
        a.cost AS total_cost,
        a.profit AS total_profit,
        a.gp AS total_gp,
        a.gp AS gross_profit
        FROM 
        $database2.`sum_daily_consign` a 
        LEFT JOIN $database2.cp_set_branch b
        ON a.loc_group = b.branch_code
        INNER JOIN lite_b2b.set_supplier_group d
        ON a.code = d.supplier_group_name
        AND d.customer_guid = '$customer_guid'
        INNER JOIN lite_b2b.set_supplier e
        ON d.supplier_guid = e.supplier_guid
        AND e.isactive = '1'
        LEFT JOIN $database2.`itemmaster` f
        ON a.`itemcode` = f.itemcode
        WHERE a.bizdate BETWEEN '$date_start' AND '$date_end' 
        AND a.`loc_group` IN ('$location')
        AND a.`code` = '$supplier_code'
        GROUP BY a.loc_group, a.bizdate, a.code, a.itemcode,a.cost_margin,a.price,a.price_net");

      //echo $this->db->last_query(); die;

      $data = array(  
        'query_data' => $query_data->result(),
      );
  
      echo json_encode($data); 
    }

}
?>
