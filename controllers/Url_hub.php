<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


class url_hub extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['account_get']['limit'] = 5000; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 1000; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->data['domain'] = $this->db->query("SELECT * from rest_api.client_parameter")->row('server_host');
        $this->data['ApiKey'] = $this->db->query("SELECT * from rest_api.client_parameter")->row('towards_api_key');
    }

    public function date()
    {
        $date = $this->db->query("SELECT CURDATE() as curdate")->row('curdate');
        return $date;
    }

    public function datetime()
    {
        $datetime = $this->db->query("SELECT NOW() as datetime")->row('datetime');
        return $datetime;
    }

    public function guid()
    {
        $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') as guid")->row('guid');
        return $guid;
    }

    public function error_log($module, $refno , $reason, $customer_guid)
    {
        $error_log = array(
            'error_guid' => $this->guid(),
            'customer_guid' => $customer_guid,
            'module' => $module,
            'refno' => $refno,
            'reason' => $reason,
            'created_at' => $this->datetime(),
        );
        $this->db->insert('b2b_resthub.error_logs', $error_log);
        return $error_log;
    }

    public function check_alert_get()
    {
        $check_alert = $this->db->query("SELECT next_run_datetime from lite_b2b.set_scheduler where type = 'supplier_email_alert' and active = '1'");

        if($this->datetime() >= $check_alert->row('next_run_datetime'))
        {
            //echo 'HAHA KENA!';die;
            $module = $_REQUEST['module'];
            $url = "https://b2b.xbridge.my/index.php/Export_controller/email_notification?module=".$module;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $this->data['ApiKey']));
            
            $response = curl_exec($ch);
           // var_dump($response);die;
            if($response !== false) 
            {
                $obj = json_decode($response,true);
		//echo $response;die;
                if($obj['action'] == "update_scheduler")
                {
                    $this->db->query("UPDATE lite_b2b.set_scheduler SET next_run_datetime = NOW() + INTERVAL 1 DAY WHERE TYPE = 'supplier_email_alert' and active = '1'");
                     echo json_encode(array(
                    'status' => TRUE,
                    'message' =>'Scheduler datetime Updated',
                    'action' => 'No Action',
                    ));

                }
                else
                {
                    echo json_encode(array(
                    'status' => FALSE,
                    'message' => $obj['message'],
                    'action' => $obj['action'],
                    ));
                }
            }
            else
            {
                echo json_encode(array(
                'status' => FALSE,
                'message' => 'Restful Path Error',
                'action' => 'retry',
                ));
                //echo 'Restful Path Error'; 
            }
        }
        else
        {
             echo json_encode(array(
                'status' => TRUE,
                'message' => 'Scheduler did not meet condition',
                'action' => 'No Action',
                ));
            //echo 'Scheduler did not meet condition';
        } 
    }

    public function acknowledge_restapi($ack_url, $status, $msg , $variable_code, $password)
    {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $ack_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => 
                    array('status' => $status,
                            'message' => $msg,
                            'variable_code' => $variable_code,
                        ),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data",
                "x-api-key: ".$password
            ),
        ));
            $asdasd = array('status' => $status,
                            'message' => $msg,
                            'variable_code' => $variable_code,
                        );
         //var_dump($asdasd);die;
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }  
    }

    public function integration_get()
    {
        $customer_guid = $_REQUEST['customer_guid'];
        $module = $_REQUEST['module'];

        $get_header_detail = $this->db->query("SELECT * from b2b_resthub.integration_profile where customer_guid = '$customer_guid'");
        $get_table_detail = $this->db->query("SELECT * from b2b_resthub.integration_table where customer_guid = '$customer_guid'");
        if($get_header_detail->num_rows() > 0)
        {
            $get_url = $get_header_detail->row('url_link');
            $url = $get_url."/get?module=".$module."&customer_guid=".$customer_guid;

            $ack_url = $get_url."/Post/ack?module=".$module;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $get_header_detail->row('api_key')));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($get_header_detail->row('api_type').": " . $get_header_detail->row('api_key')));

            $response = curl_exec($ch);
            //echo $response;

            if($response !== false) 
            {
                $obj = json_decode($response,true);
               // echo var_dump($obj);
                if($obj['status'] == "true")
                {
                    if($obj['module'] == "b2b_dnbatch")
                    {  
                        
                        foreach($obj['data'] as $row => $value)
                        { 
                            // get_main
                            $allow_main = 3;  // forcing it to be more than 1
                            $variable_code = $value['refno'];
                            $data = array(
                            'customer_guid' => $value['owner_code'],
                            'dbnote_guid' => $value['dbnote_guid'],
                            'refno' => $value['refno'],
                            'sup_code' => $value['sup_code'],
                            'sup_name' => $value['sup_name'],
                            'created_at' => $value['created_at'],
                            'posted_at' => $value['posted_at'],
                            'posted_by' => $value['posted_by'],
                            'location' => $value['location'],
                            'imported_flag' => '1',
                            'imported_at' => $this->datetime(),  
                            'acknowledge_flag' => '0',            
                            'acknowledge_at' => '',  
                            );

                            foreach($value['order_line'] as $child_row => $child_value)
                            {
                                $data_child[] = array(
                                'line_no' => $child_value['line_no'],
                                'dbnote_guid' => $child_value['dbnote_guid'],
                                'itemcode' => $child_value['itemcode'],
                                'itemlink' => $child_value['itemlink'],
                                'description' => $child_value['description'],
                                'packsize' => $child_value['packsize'],
                                'qty' => $child_value['qty'],
                                'um' => $child_value['um'],
                                'averagecost' => $child_value['AverageCost'],
                                'sellingprice' => $child_value['SellingPrice'],
                                'lastcost' =>  $child_value['lastcost'],
                                'subdept' =>  $child_value['subdept'],
                                'dept' =>  $child_value['dept'],         
                                'category' =>  $child_value['category'],
                                'reason' =>  $child_value['reason'],
                                'scan_barcode' =>  $child_value['scan_barcode'],
                                );
                                $insertchild = $this->db->replace_batch($get_table_detail->row('database').'.'.'dbnote_batch_c', $data_child);

                               
                                $checking_data = $this->db->query("SELECT * from b2b_resthub.dbnote_batch_c where dbnote_guid = '".$child_value['dbnote_guid']."'");

                                if($checking_data->row('reason') == null || $checking_data->row('reason') == '')
                                {
                                    $msg = 'Reason cannot be empty - '.$child_value['itemcode'];
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg , $customer_guid);
                                    $allow_main = FALSE;

                                    //echo var_dump($allow_main);die;

                                };
                                if($checking_data->row('itemcode') == null || $checking_data->row('itemcode') == '')
                                {
                                    $msg = 'Itemcode cannot be empty';
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid);
                                    $allow_main = FALSE;
                                };
                                if($checking_data->row('description') == null || $checking_data->row('description') == '')
                                {
                                    $msg = 'Description cannot be empty - '.$child_value['itemcode'];
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg ,$variable_code, $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid);
                                    $allow_main = FALSE;
                                };
                                if($checking_data->row('line_no') == null || $checking_data->row('line_no') == '')
                                {
                                    $msg = 'Line Number cannot be empty - '.$child_value['itemcode'];
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid);
                                    $allow_main = FALSE;
                                };
                                if($checking_data->row('lastcost') == null || $checking_data->row('lastcost') == '')
                                {
                                    $msg = 'Last Cost cannot be empty - '.$child_value['itemcode'];
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid);
                                    $allow_main = FALSE;
                                };
                                if($checking_data->row('AverageCost') == null || $checking_data->row('AverageCost') == '')
                                {
                                    $msg = 'AverageCost cannot be empty - '.$child_value['itemcode'];
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid);
                                    $allow_main = FALSE;
                                }; 
                            } // end foreach child

                            if($allow_main > 1)
                            {

                                $check_and_delete = $this->db->query("DELETE from b2b_resthub.dbnote_batch where dbnote_guid = '".$value['dbnote_guid']."'");
                                $insertmain = $this->db->insert($get_table_detail->row('database').'.'.$get_table_detail->row('staging_table'), $data);  
                                
                                $check_header = $this->db->query("SELECT * from b2b_resthub.dbnote_batch where refno = '".$variable_code."' and dbnote_guid = '".$value['dbnote_guid']."'");

                                if($check_header->row('customer_guid') == null || $check_header->row('customer_guid') == '')
                                {
                                    $msg = 'Customer ID cannot be empty';
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid); 
                                }
                                elseif($check_header->row('refno') == null || $check_header->row('refno') == '')
                                {
                                    $msg = 'Reference No cannot be empty';
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid); 
                                }
                                elseif($check_header->row('location') == null || $check_header->row('location') == '')
                                {
                                    $msg = 'Location cannot be empty';
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid); 
                                }
                                elseif($check_header->row('sup_code') == null || $check_header->row('sup_code') == '')
                                {
                                    $msg = 'Supplier Code cannot be empty';
                                    $status = 'false';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    $this->error_log($module, $variable_code , $msg, $customer_guid); 
                                }
                                else
                                {
                                    $msg = 'Success ';
                                    $status = 'true';
                                    $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key'));
                                    //$this->error_log($module, $variable_code , $msg); 
                                    $this->db->query("Update b2b_resthub.dbnote_batch SET acknowledge_flag = '1', acknowledge_at = now() where refno = '$variable_code' and customer_guid = '".$value['owner_code']."' and dbnote_guid = '".$value['dbnote_guid']."'");

                                    //echo $this->db->last_query();
                                } 
                            }
                            else
                            {

                                $msg = $variable_code.' Header Error';
                                $status = 'false';
                                $this->acknowledge_restapi($ack_url, $status, $msg , $variable_code , $get_header_detail->row('api_key')); 
                                $this->error_log($module, $variable_code , $msg, $customer_guid);  
                                // remove child data
                                $this->db->query("DELETE from b2b_resthub.dbnote_batch_c where dbnote_guid = '".$value['dbnote_guid']."'");
                                //echo $this->db->last_query();
                            } 
                        }; 


                    }; //END DN BATCH
                } 
                else
                {
                    echo json_encode(array(
                    'status' => $obj['status'],
                    'message' => $obj['message'],
                    'action' => 'No Action',
                    ));
                }
            }
            else
            {
                echo json_encode(array(
                    'status' => FALSE,
                    'message' => 'Connection Error',
                    'action' => 'retry',
                    ));
            }  
        }
        else
        {
            echo json_encode(array(
                'status' => FALSE,
                'message' => 'Unknown Customer ID',
                'action' => 'No Action',
                )); 
        } 
    } // end intergration get 
}
