<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Article_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        //$this->load->model('Export_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer');
        $this->load->model('excel_model');
        $this->general_internal_ip = $this->file_config_b2b->file_path_name($customer_guid, 'web', 'general_doc', 'general_internal_ip', 'GIP');
        $this->retailer_doc_name = $this->file_config_b2b->file_path_name('', 'web', 'retailer_doc', 'retailer_doc_name', 'RTDN');
        $this->api_url = $this->general_internal_ip . $this->retailer_doc_name .'/rest_b2b/index.php/';
    }

    public function report()
    {
        if(isset($_GET['report_type']) && $this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $user_guid = $this->session->userdata('user_guid');
            $customer_guid = $this->session->userdata('customer_guid');
            $backend_db = $this->db->query("SELECT b2b_database FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');

            if(in_array('IAVA',$_SESSION['module_code']))
            {
                $code = $this->db->query("SELECT c.Code,c.Name, if(c.Consign = 1, 'consign', 'outright') AS supp_type FROM (SELECT * FROM set_supplier_user_relationship WHERE customer_guid = '$customer_guid' GROUP BY supplier_group_guid) a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC");

                //echo $this->db->last_query(); die;
            }
            else
            {
                $code = $this->db->query("SELECT c.Code,c.Name, '' AS supp_type FROM (SELECT * FROM set_supplier_user_relationship WHERE user_guid = '$user_guid' AND customer_guid = '$customer_guid') a INNER JOIN (SELECT * FROM set_supplier_group WHERE customer_guid = '$customer_guid') b ON a.supplier_group_guid = b.supplier_group_guid INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE customer_guid = '$customer_guid' AND type = 'S') c ON b.backend_supcus_guid = c.supcus_guid");
                // echo $this->db->last_query();die;

                $location = $this->db->query("SELECT * FROM (SELECT * FROM set_user_branch WHERE user_guid = '$user_guid' AND acc_guid = '$customer_guid' GROUP BY branch_guid) a INNER JOIN (SELECT * FROM acc_branch WHERE isactive = '1' )b ON a.branch_guid = b.branch_guid INNER JOIN (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') c ON b.branch_code = c.branch_code ORDER BY b.branch_code ASC"); 
            }

            $category = $this->db->query("SELECT category, categorydesc FROM $backend_db.hierarchy ORDER BY category DESC");

            if($_GET['report_type'] == 'sum_daily_list'){

                $view_report = 'propose_report/article_sum_daily_list';

            }else if($_GET['report_type'] == 'supplier_daily_inventory'){

                $view_report = 'propose_report/article_supplier_daily_inventory_list';

            }else if($_GET['report_type'] == 'supplier_article_information_query'){

                $view_report = 'propose_report/article_supplier_article_information_list';

            }else{
                redirect('#');
            }

            $data = array(
                'location' => $location,
                'code' => $code,
                'category' => $category,
            );

            $this->load->view('header');
            $this->load->view($view_report, $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function sum_daily_table()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0); 
  
      $customer_guid = $this->session->userdata('customer_guid');
      $user_guid = $this->session->userdata('user_guid');
      $supplier_code = isset($_GET['outright_code']) ? $this->input->get('outright_code') : $this->input->post('outright_code');
      $location = isset($_GET['outright_location']) ? $this->input->get('outright_location') : $this->input->post('outright_location');
      $date_start = isset($_GET['date_start']) ? $this->input->get('date_start') : $this->input->post('date_start');
      $date_end = isset($_GET['date_end']) ? $this->input->get('date_end') : $this->input->post('date_end');

      //print_r($date_end); die;

      if(!is_array($location)){
        $location = explode(',', $location);
      }

      $location = implode("','",$location);

      $database1 = 'lite_b2b';
      $database2 = $this->db->query("SELECT b2b_database FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');
      $acc_name = $this->db->query("SELECT acc_name FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('acc_name');
      
      $sql = "SELECT 
      f.supplier_name AS 'Supplier Name',
      b.code AS 'Supplier Code',
      a.bizdate AS 'Biz Date',
      CONCAT(a.location, ' - ',c.branch_name) AS 'Location',
      a.itemcode AS 'Item Code',
      IF(a.consign = '1', 'Consign', 'Outright') AS 'Item Type',
      a.description AS 'Description',
      a.barcode AS 'Barcode',
      SUM(a.qty) AS 'Total Qty',
      a.um AS 'UOM',
      SUM(a.netsales) AS 'Total NetSales'
      FROM 
        $database2.`sum_daily_sku_sales` a
        INNER JOIN $database2.`itemmastersupcode` b
        ON a.itemcode = b.itemcode
        LEFT JOIN $database2.cp_set_branch c
        ON a.loc_group = c.branch_code
        INNER JOIN lite_b2b.set_supplier_group e
        ON b.code = e.supplier_group_name
        AND e.customer_guid = '$customer_guid'
        INNER JOIN lite_b2b.set_supplier f
        ON e.supplier_guid = f.supplier_guid
        AND f.isactive = '1'
      WHERE
      a.bizdate BETWEEN '$date_start' AND '$date_end'
      AND b.code = '$supplier_code'
      AND a.location IN ('$location')
      GROUP BY a.bizdate,b.code,a.loc_group,a.um,a.itemcode
      HAVING `Total Qty` <> 0 
      ORDER BY a.bizdate ASC ";

      if(isset($_GET['download_excel'])){
                    
        $objPHPExcel = $this->excel_model->download_excel($sql);

        $today = $this->db->query("SELECT date(now()) as today")->row('today');
        // $filename = $today.'.XLSX'; //save our workbook as this file name
        $fileName = 'Daily Sales By Supplier - '.$today.'.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$fileName.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        header("Pragma: no-cache");
        header("Expires: 0");

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        ob_end_clean();

        $objWriter->save('php://output'); die;
      }

      //echo $this->db->last_query(); die;

      $query_data = $this->db->query($sql);

      $data = array(  
        'query_data' => $query_data->result(),
      );
  
      echo json_encode($data); 
    }

    public function supplier_daily_inventory_table()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0); 
  
      $customer_guid = $this->session->userdata('customer_guid');
      $user_guid = $this->session->userdata('user_guid');
      $supplier_code = isset($_GET['outright_code']) ? $this->input->get('outright_code') : $this->input->post('outright_code');
      $location = isset($_GET['outright_location']) ? $this->input->get('outright_location') : $this->input->post('outright_location');
      $date_start = isset($_GET['date_start']) ? $this->input->get('date_start') : $this->input->post('date_start');
      $itemtype = isset($_GET['itemtype']) ? $this->input->get('itemtype') : $this->input->post('itemtype');

      $query_supcode = $_SESSION['query_supcode'];
      $query_loc = $_SESSION['query_loc'];

      if ($query_loc == null || $query_loc == '') {
        $query_loc = "''";
      }

      if(!is_array($location)){
        $location = explode(',', $location);
      }

      $location = implode("','",$location);

      //nabil testing
    //   $customer_guid = '610BB0EA76AE11EDB37C72B64FC54D79';
    //   $user_guid = 'D94B408C776411ED8D8C000D3AA232B1';
    //   $supplier_code = 'T0104';
    //   $location = "AGT2','ASE','ASE2','BMI','CLNK','DELL','HPMM','HQ','INT1','INT2','INT3','INT4','INT5','KOBE','LUM2','OSR','SCM','SSB','SSFM','SSJ2','SSL','SSQ','SWA";
    //   $date_start = '2023-02-01';
    //   $date_end = '2023-02-28';

      $database1 = 'lite_b2b';
      $database2 = $this->db->query("SELECT b2b_database FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');
      $acc_name = $this->db->query("SELECT acc_name FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('acc_name');

      $sql = "SELECT 
        s.`Name` AS 'Supplier Name',
        s.`Code` AS 'Supplier Code',
        CONCAT(accb.`branch_code`,' - ',accb.`branch_name`) as Location,
        im.`Itemcode` AS 'Item Code',
        ib.`Barcode`,
        im.`description` AS Description,
        im.`um` AS UOM,
        FORMAT(imsc.`NetUnitPrice`,4) AS `Default BP`,
        FORMAT(IF(imsc.`SupLastPrice` > 0, imsc.`SupLastPrice`, imsc.`NetUnitPrice`),4) AS `Actual BP`,
        FORMAT(imbs.`sellingprice`,2) AS `Selling Price`,
        imbs.`QOH`,
        FORMAT(imbs.`QOH` * imbs.fifocost,2) AS 'Inventory Amount'
        FROM 
            $database2.`itemmaster` im 
        INNER JOIN
            $database2.`itembarcode` ib
	        ON im.`Itemcode` = ib.`Itemcode` 
        INNER JOIN 
            $database2.itemmastersupcode imsc 
            ON im.`Itemcode` = imsc.`Itemcode`
        INNER JOIN
            $database2.`itemmaster_branch_stock` imbs
            ON im.`Itemcode` = imbs.`itemcode`
        INNER JOIN 
            lite_b2b.`acc_branch` accb 
            ON imbs.`branch` = accb.branch_code
        INNER JOIN
            $database2.`supcus` s
            ON imsc.`Code` = s.`Code`
        WHERE
             1 = 1";     

        if($supplier_code != '' || $supplier_code != null)
        {
            $sql .= " AND imsc.`Code` = '$supplier_code'";
        }

        if($location != '' || $location != null)
        {
            $sql .= " AND imbs.branch IN ('$location')";
        }

        if($date_start != '' || $date_start != null)
        {
            $sql .= " AND ib.`LastStamp` = '$date_start'";
        }

        if($itemtype != '' || $itemtype != null)
        {
            $sql .= " AND im.`Consign` = '$itemtype'";
        }

        $sql .= " GROUP BY accb.branch_code,im.itemcode ORDER BY im.`Itemcode` ASC;";

        if(isset($_GET['download_excel'])){
            
            $objPHPExcel = $this->excel_model->download_excel($sql);

            $today = $this->db->query("SELECT date(now()) as today")->row('today');

            // $filename = $today.'.XLSX'; //save our workbook as this file name
            $fileName = 'Supplier Daily Inventory - '.$today.'.xlsx';
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$fileName.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            header("Pragma: no-cache");
            header("Expires: 0");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            ob_end_clean();

            $objWriter->save('php://output'); die;
        }

        $query_data = $this->db->query($sql);

      //echo $this->db->last_query(); die;

      $data = array(  
        'query_data' => $query_data->result(),
      );
  
      echo json_encode($data); 
    }
    public function supplier_article_information_query_table()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 0); 
  
      $customer_guid = $this->session->userdata('customer_guid');
      $user_guid = $this->session->userdata('user_guid');
      $supplier_code = $this->input->post('outright_code');
      $location = implode("','",$this->input->post('outright_location'));
      $category = implode("','",$this->input->post('category'));
      $article_no = $this->input->post('article_no');
      $article_desc = $this->input->post('article_desc');
      $itemcode = $this->input->post('itemcode');
      $itemtype = $this->input->post('itemtype');

      $query_supcode = $_SESSION['query_supcode'];
      $query_loc = $_SESSION['query_loc'];

      if ($query_loc == null || $query_loc == '') {
        $query_loc = "''";
      }

      $database1 = 'lite_b2b';
      $database2 = $this->db->query("SELECT b2b_database FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('b2b_database');
      $acc_name = $this->db->query("SELECT acc_name FROM $database1.acc WHERE acc_guid = '$customer_guid'")->row('acc_name');
      
      $sql = "SELECT 
            s.`Code`,
            s.`Name`,
            h.`deptdesc`,
            h.`categorydesc`,
            im.`Itemcode`,
            im.`ArticleNo`,
            im.`cost_code` AS `main_barcode`,
            ib.`Barcode`,
            im.`description`,
            im.`um`,
            imsc.`OrderLotSize`,  
            FORMAT(imbs.`fifocost`,2) AS `averagecost`,
            '0' AS tax_rate,
            FORMAT(imbs.`sellingprice`,2) AS `netsales`
        FROM 
            `r_sunshine_backend`.`itemmaster` im 
        INNER JOIN 
            `r_sunshine_backend`.`hierarchy` h
            ON im.`Dept` = h.`dept`
            AND im.`SubDept` = h.`subdept`
            AND im.`Category` = h.`category`
        INNER JOIN
            `r_sunshine_backend`.`itembarcode` ib
            ON im.`Itemcode` = ib.`Itemcode`
        INNER JOIN 
            `r_sunshine_backend`.itemmastersupcode imsc 
            ON im.`Itemcode` = imsc.`Itemcode`
        INNER JOIN
            `r_sunshine_backend`.`itemmaster_branch_stock` imbs
            ON im.`Itemcode` = imbs.`itemcode`
        INNER JOIN
            `r_sunshine_backend`.`supcus` s
            ON imsc.`Code` = s.`Code`
        WHERE
            1 = 1";
        
        if($supplier_code != '' || $supplier_code != null)
        {
            $sql .= " AND imsc.`Code` IN ('" . $supplier_code . "')";
        }else{
            if (!in_array('IAVA', $_SESSION['module_code'])) {
                $sql .= " AND imsc.`Code` IN ('" . $query_supcode . "')";  
            }
        }

        if($location != '' || $location != null)
        {   
            $sql .= " AND imbs.branch IN ('" . $location . "')";
        }else{
            if($query_loc != '' || $query_loc != null){
                $sql .= " AND imbs.branch IN (" . $query_loc . ")";
            }
        }

        if($category != '' || $category != null)
        {
            if (preg_match("/\bAll_jing\b/i", $category) == 0) {
                $sql .= " AND im.Category IN ('" . $category . "')";
            }
        }

        if($itemcode != '' || $itemcode != null)
        {
            $sql .= " AND im.Itemcode = '" . $itemcode . "'";
        }

        if($article_no != '' || $article_no != null)
        {
            $sql .= " AND im.articleNo = '" . $article_no . "'";
        }

        if($article_desc != '' || $article_desc != null)
        {
            $sql .= " AND im.description LIKE '%". $article_desc ."%'";
        }   
        
        if($itemtype != '' || $itemtype != null)
        {
            $sql .= " AND im.Consign = '" . $itemtype . "'";
        }
            
        $sql .= " GROUP BY ib.`Barcode` ORDER BY im.`Itemcode` ASC ;";

        $query_data = $this->db->query($sql);

      $data = array(  
        'query_data' => $query_data->result(),
      );
  
      echo json_encode($data); 
    }
    public function api_sum_daily_table()
    {
        if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login()) {
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');
            $supplier_code = $this->input->post('consign_code');
            $location = $this->input->post('consign_location');
            $date_start = $this->input->post('date_start');
            $date_end = $this->input->post('date_end');

            $location = implode(",",$this->input->post('consign_location'));

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0);

            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $order = $this->input->post("order");
            $search = $this->input->post("search");
            $search = $search['value'];
            $col = 0;
            $dir = "";

            $query_supcode = $this->session->userdata('query_supcode');

            $url = $this->api_url;

            //$to_shoot_url = $url . "/Select/S_view_table_consignment_sales_statement_by_supcode";
            $to_shoot_url = $url . "Consign_select/sum_daily_sku_sales";

            //print_r($to_shoot_url);die;
            $data =  array(
                'draw' => $draw,
                'start' => $start,
                'length' => $length,
                'order' => $order,
                'search' =>  $search,
                'col' => $col,
                'dir' => $dir,
                'customer_guid' => $customer_guid,
                'supplier_code' => $supplier_code,
                'user_guid' => $user_guid,
                'location' => $location,
                'date_start' => $date_start,
                'date_end' => $date_end,
            );
            //print_r($data);die;
            print_r(json_encode($data)); die;
            // die;
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
            $result = curl_exec($ch);
            $output = json_decode($result);
            // $status = json_encode($output);
            //print_r($output->result);die;
            //echo $result;die;
            //close connection
            curl_close($ch);
            // echo $output->status;
            // die;

            if ($output->status == "true") {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;
            } else {
                $result = $output->data;
                $draw = $output->draw;
                $recordsTotal = $output->recordsTotal;
                $recordsFiltered = $output->recordsFiltered;
                $data = $output->data;
            }
            // print_r($result);die;             

            // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

            $output = array(
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
            );

            echo json_encode($output);
        } else {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    
}
