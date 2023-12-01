<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {
    
    public function __construct()
    {   
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->jasper_ip = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','jasper_invoice_ip','GDJIIP');
        $this->jasper_path_panda = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'),'web','general_doc','jasper_document_path','GDJDP');
        $this->jasper_path_b2b = $this->file_config_b2b->file_path_name($this->session->userdata('customer_guid'),'web','general_doc','jasper_document_path_b2b','GDJDPB2B');

    }

    public function view_report_inv()
    {   
        $auth = $this->file_config_b2b->auth($customer_guid,'web','consignment','consignment_jasper_auth','CONJASPERAUTH');
        $inv_guid = $_REQUEST['inv_guid'];
        $inv_no = $_REQUEST['inv_number'];
        $url = $this->jasper_ip. $this->jasper_path_b2b .'/Invoices_New.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
        // echo $url;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: public",
            "Authorization: Basic $auth",
            "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
          ),
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
        header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$inv_no.'.pdf');
        echo $response; 

        curl_close($curl);             
    }//close ger_supplier_last_login 

    public function view_cn_report()
    {   
        $inv_guid = $_REQUEST['inv_guid'];
        $inv_no = $_REQUEST['inv_number'];
        $url = $this->jasper_ip. $this->jasper_path_b2b .'/Invoices_CN.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
        // echo $url;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: public",
            "Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==",
            "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
          ),
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
        header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$inv_no.'.pdf');
        echo $response; 

        curl_close($curl);             
    }//close ger_supplier_last_login 

    public function invoices_process()
    {   
        $user_guid = $_SESSION['user_guid'];

        $inv_guid = $_REQUEST['inv_guid'];

        $invoice_number_array = $this->db->query("SELECT invoice_number,biller_guid FROM b2b_invoice.supplier_monthly_main WHERE inv_guid = '$inv_guid' ");

        $invoice_number = $invoice_number_array->row('invoice_number');
        $supplier_guid = $invoice_number_array->row('biller_guid');

        // $_SESSION['inv_no'] = $invoice_number;

        // $_SESSION['inv_guid'] = $inv_guid;

        if(in_array('IAVA',$_SESSION['module_code']))
        {
          $auth = $this->file_config_b2b->auth($customer_guid,'web','consignment','consignment_jasper_auth','CONJASPERAUTH');
          $inv_guid = $_REQUEST['inv_guid'];
          $inv_no = $invoice_number;
          $url = $this->jasper_ip. $this->jasper_path_b2b .'/Invoices_2.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
          // echo $url;die;
          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
              "Cache-Control: public",
              "Authorization: Basic $auth",
              "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
            ),
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
          header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$inv_no.'.pdf');
          echo $response; 

          curl_close($curl); 
        }
        else
        {
          $check_supplier_invoice = $this->db->query("SELECT supplier_guid FROM lite_b2b.set_supplier_user_relationship WHERE user_guid = '$user_guid' AND supplier_guid = '$supplier_guid' ")->num_rows();
          // echo $this->db->last_query();die;

          if($check_supplier_invoice == '0' )
          {
            echo "<script> alert('You are not authorized.');</script>";
            echo "<script> document.location='" . base_url() . "/index.php/b2b_billing_invoice_controller/invoices' </script>";
            die;
          }
          $inv_guid = $_REQUEST['inv_guid'];
          $inv_no = $invoice_number;
          $url = 'http://52.163.112.202:59090/jasperserver/rest_v2/reports/reports/B2BReports/Invoices_2.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
          // echo $url;die;
          $curl = curl_init();

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
          header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$inv_no.'.pdf');
          echo $response; 

          curl_close($curl);           

        }
            
    }     

    public function view_report_reg()
    {   
        $inv_guid = $_REQUEST['inv_guid'];
        $inv_no = $_REQUEST['inv_number'];
        $url = $this->jasper_ip. $this->jasper_path_b2b .'/acc_invoice.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
        // echo $url;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: public",
            "Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==",
            "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
          ),
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
        header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$inv_no.'.pdf');
        echo $response; 

        curl_close($curl);             
    }//close ger_supplier_last_login    

    public function view_report_cn()
    {   
        $inv_guid = $_REQUEST['inv_guid'];
        $inv_no = $_REQUEST['inv_number'];
        $url = $this->jasper_ip. $this->jasper_path_b2b .'/Invoices_CN.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
        // echo $url;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: public",
            "Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==",
            "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
          ),
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
        header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$inv_no.'.pdf');
        echo $response; 

        curl_close($curl);             
    }//close ger_supplier_last_login    

    public function view_report_term()
    {   
        $form_type = $_REQUEST['form_type'];
        $reg_guid = $_REQUEST['reg_guid'];
        $supplier_name = $_REQUEST['supplier_name'];
        $filename = 'TERM_SHEET_'.$supplier_name;
        //$path = htmlentities( $this->jasper_path_b2b .'/term_sheet.pdf?db_be=lite_b2b&reg_guid='); 
        $url = $this->jasper_ip. $this->jasper_path_b2b .'/term_sheet.pdf?db_be=lite_b2b&reg_guid='.$reg_guid.'&form_type='.$form_type;
        //echo $url;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: public",
            "Authorization: Basic cGFuZGFfYjJiOmIyYkBhZG5hcA==",
            "Cookie: userLocale=en_US; JSESSIONID=879B915DD28E3F45200A8EE4AB8C9247; ci_session=fd078d7476946360245b1455b22c0f843f46df74",
          ),
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
        header('Content-Disposition: ' .$disposition.'; filename='.$filename.'.pdf');
        echo $response;

        curl_close($curl);             
    }//close ger_supplier_last_login  

    public function view_term_special()
    {   
        $form_type = $_REQUEST['form_type'];
        $reg_guid = $_REQUEST['reg_guid'];
        $supplier_name = $_REQUEST['supplier_name'];
        $filename = 'SPECIAL_TERM_SHEET_'.$supplier_name;
        //$url = $this->jasper_ip $this->jasper_path_b2b .'/acc_invoice.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
        $path = htmlentities( $this->jasper_path_b2b .'/term_sheet_2.pdf?db_be=lite_b2b&reg_guid=');
        $url = $this->jasper_ip. $this->jasper_path_b2b .'/term_sheet_2.pdf?db_be=lite_b2b&reg_guid='.$reg_guid.'&form_type='.$form_type;
        // echo $url;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_SSL_VERIFYPEER => false,
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
        header('Content-Disposition: ' .$disposition.'; filename='.$filename.'.pdf');
        echo $response; 

        curl_close($curl);             
    }//close 

    public function view_renewal_report()
    {   
      $link = $_REQUEST['link'];
      $supplier_name = $_REQUEST['supplier_name'];

      $filename = 'Renwal_Form_'.$supplier_name;
      //$url = 'http://127.0.0.1:59090/jasperserver/rest_v2/reports/reports/B2BReports/acc_invoice.pdf?db_be=b2b_invoice&inv_guid='.$inv_guid;
      $url = $this->jasper_ip. $this->jasper_path_b2b .'/renewal.pdf?db_be=lite_b2b&guid='.$link;
      // echo $url;die;
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
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
      header('Content-Disposition: ' .$disposition.'; filename='.$filename.'.pdf');
      echo $response; 

      curl_close($curl);
    }//close 

    public function demand_letter_report()
    {   
      $type = $_REQUEST['type'];
      $customer_guid = $_REQUEST['customer_guid'];
      $supplier_guid = $_REQUEST['supplier_guid'];
      //$link = $_REQUEST['link'];

      $url = $this->jasper_ip. $this->jasper_path_b2b .'/demand_letter_json.pdf?type='.$type.'&customer_guid='.$customer_guid.'&supplier_guid='.$supplier_guid;
      //echo $url;die;
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
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
      header('Content-Disposition: ' .$disposition.'; filename='.$filename.'.pdf');
      echo $response; 

      curl_close($curl);
    }//close 

    public function demand_letter_report_old()
    {   
        $type = $_REQUEST['type'];
        $customer_guid = $_REQUEST['customer_guid'];
        $supplier_guid = $_REQUEST['supplier_guid'];

        $url = $this->jasper_ip. $this->jasper_path_b2b .'/demand_letter_json.pdf?type='.$type.'&customer_guid='.$customer_guid.'&supplier_guid='.$supplier_guid;
        //echo $url;die;
        $curl = curl_init();

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
        header('Content-Disposition: ' .$disposition.'; filename=Invoice_'.$type.'.pdf');
        echo $response; 

        curl_close($curl);             
    }//close ger_supplier_last_login
    
    public function einvoice_report()
    {   
      $customer_guid = $_REQUEST['customer_guid'];
      $refno = $_REQUEST['refno'];

      $filename = 'B2B_EINV_'.$refno;

      $url = $this->jasper_ip. $this->jasper_path_b2b .'/GRN_E_Invoice.pdf?db_b2b=b2b_summary&db_lite=lite_b2b&customer_guid='.$customer_guid.'&refno='.$refno;
      // echo $url;die;
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
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
      header('Content-Disposition: ' .$disposition.'; filename='.$filename.'.pdf');
      echo $response; 

      curl_close($curl);
    }//close ger_supplier_last_login  

    public function ecn_report()
    {   
      $customer_guid = $_REQUEST['customer_guid'];
      $refno = $_REQUEST['refno'];
      $trans_type = $_REQUEST['trans_type'];

      $filename = 'B2B_ECN_'.$refno.'_'.$trans_type;

      $url = $this->jasper_ip. $this->jasper_path_b2b .'/GRDA_E_Invoice.pdf?db_b2b=b2b_summary&db_lite=lite_b2b&customer_guid='.$customer_guid.'&refno='.$refno.'&trans_type='.$trans_type;
      // echo $url;die;
      // http://192.168.8.243:59090 http://127.0.0.1:59090
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
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
      header('Content-Disposition: ' .$disposition.'; filename='.$filename.'.pdf');
      echo $response; 

      curl_close($curl);     
    }//close ger_supplier_last_login  

    public function prdn_ecn_report()
    {   
      $customer_guid = $_REQUEST['customer_guid'];
      $refno = $_REQUEST['refno'];

      $filename = 'B2B_PRDN_ECN_'.$refno;

      $url = $this->jasper_ip. $this->jasper_path_b2b .'/PRDN_E_Invoice.pdf?db_b2b=b2b_summary&customer_guid='.$customer_guid.'&refno='.$refno;
      // echo $url;die;

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
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
      header('Content-Disposition: ' .$disposition.'; filename='.$filename.'.pdf');
      echo $response; 

      curl_close($curl);             
    }//close ger_supplier_last_login
}
?>