<?php
class panda_prdncn extends CI_Controller
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
        $this->load->model('GR_model');
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $setsession = array(
                'frommodule' => 'panda_prdncn',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            if(isset($_SESSION['from_other']) == 0 )
            {

                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=');
            }
            else
            {
                if($_REQUEST['status'] == '')
                {
                    unset($_SESSION['from_other']);
                    redirect('panda_prdncn?loc='.$_REQUEST['loc']);
                };
                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=');
            };
        }
        else
        {
             $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function index_ori()
    {
         if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $setsession = array(
                'frommodule' => 'panda_prdncn',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };

            if($_REQUEST['loc'] == 'HQ')
            {
                $loc = $_SESSION['query_loc'];
            }
            else
            {
                $loc = "'".$_REQUEST['loc']."'";
            };

                if(in_array('IAVA',$_SESSION['module_code']))
                {
                    $result = $this->db->query("SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."'  and location IN ($loc) 
                    UNION ALL
                    SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."'  and location IN ($loc)");
                }
                else
                {
                    $result = $this->db->query("SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location IN ($loc) and code IN (".$_SESSION['query_supcode'].")
                    UNION ALL
                    SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location IN ($loc)  and code IN (".$_SESSION['query_supcode'].")");
                };
            


            if(!in_array('IAVA',$_SESSION['module_code']) && $_REQUEST['loc'] != 'HQ')
            {
                $result = $this->db->query("SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."' and code IN (".$_SESSION['query_supcode'].")
                    UNION ALL
                    SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."' and code IN (".$_SESSION['query_supcode'].")");
            };

            if(!in_array('IAVA',$_SESSION['module_code']) && $_REQUEST['loc'] != 'HQ')
            {
                $result = $this->db->query("SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."'   and code IN (".$_SESSION['query_supcode'].")
                    UNION ALL
                    SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and code IN (".$_SESSION['query_supcode'].")");
            };

            if(in_array('IAVA',$_SESSION['module_code']) && $_REQUEST['loc'] != 'HQ')
            {
                $result = $this->db->query("SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."' 
                    UNION ALL
                    SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."'");
            };
            $data = array (
                'result' => $result,
            );
      
           /* $data = array (
                'result' => $this->db->query("SELECT customer_guid, refno, location, dono, invno, date_format(docdate, '%Y-%m-%d %W') as docdate,date_format(grdate, '%Y-%m-%d %W') grdate, code, name, total, gst_tax_sum, tax_code_purchase, total_include_tax, doc_name_reg, status from b2b_summary.grmain where customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."'"),
            );*/
        //$this->GR_model->update_expired();
        //$this->GR_model->update_grn($branch_code,$customer,$user_guid);
        //load the department_view
        $this->load->view('header');
       /* $this->load->view('panda_menu_view.php');*/
        $this->load->view('prdncn/panda_prdncn_list_view',$data);
        $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    /*
    to prevent user from cincai key in the refno based on url, 
    remember to join all back to user guid so that when they key by refno, it will check if the user is valid to query or not then will show result or not..
    */

    public function prdncn_child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $database = 'lite_b2b';
            $database1 = 'b2b_summary';
            $customer_guid = $this->session->userdata('customer_guid');
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $xtype = $_REQUEST['type'];
            $check_status = $this->db->query("SELECT * from b2b_summary.dbnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
            $check_strb_data = $this->db->query("SELECT batch_no,uploaded_image,LEFT(doc_date,7) AS docdate FROM b2b_summary.`dbnote_batch` WHERE customer_guid = '$customer_guid' AND converted_by = '$refno' ");
            // echo $this->db->last_query();die;
            // print_r($check_status->row('status'));die;
            if($xtype == 'DEBIT')
            {
                $check_scode = $this->db->query("SELECT code from b2b_summary.dbnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');
            }
            else
            {
                $check_scode = $this->db->query("SELECT code from b2b_summary.cnnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');    
            }
            $check_scode = str_replace("/","+-+",$check_scode);

            if($xtype == 'DEBIT')
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdn'");
            }
            else
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prcn'");   
            }

            // $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdncn'");
            //due to session data is from return collection direct click from there..

            $set_row = $this->db->query("SET @row=0");
            /*$get_DN_detail =  $this->db->query("SELECT @row:=@row+1 AS rowx, dbnotemain.* from b2b_summary.dbnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' and type = 'DEBIT'");*/
            if($xtype == 'DEBIT')
            {
                $get_DN_detail = $this->db->query("SELECT @row := @row + 1 AS rowx, a.customer_guid, a.status, a.Type, a.RefNo, a.Location, a.DocNo, a.DocDate, a.IssueStamp, a.LastStamp, a.PONo, a.SCType, a.Code, a.Name, a.Term, a.Issuedby, a.Remark, a.BillStatus, a.AccStatus, a.DueDate, a.Amount, a.Closed, a.SubDeptCode, a.postby, a.postdatetime, a.Consign, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.hq_update, a.locgroup, a.ibt, a.SubTotal1, a.Discount1, a.Discount1Type, a.SubTotal2, a.Discount2, a.Discount2Type, a.gst_tax_sum, a.tax_code_purchase, IF(b.ext_doc1 IS NULL, a.sup_cn_no, b.ext_doc1 ) AS sup_cn_no, IF(b.ext_date1 IS NULL, a.sup_cn_date, b.ext_date1) AS sup_cn_date, a.doc_name_reg, a.gst_tax_rate, a.multi_tax_code, a.refno2, a.surchg_tax_sum, a.gst_adj, a.rounding_adj, a.unpostby, a.unpostdatetime, a.ibt_gst, a.acc_posting_date, a.RoundAdjNeed FROM b2b_summary.dbnotemain AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' AND `type` = 'PRDNCN') AS b ON a.refno = b.refno WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' AND a.type = 'DEBIT' ");
            }
            else
            {
                $get_DN_detail = $this->db->query("SELECT @row := @row + 1 AS rowx, a.customer_guid, a.status, a.Type, a.RefNo, a.Location, a.DocNo, a.DocDate, a.IssueStamp, a.LastStamp, a.PONo, a.SCType, a.Code, a.Name, a.Term, a.Issuedby, a.Remark, a.BillStatus, a.AccStatus, a.DueDate, a.Amount, a.Closed, a.SubDeptCode, a.postby, a.postdatetime, a.Consign, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.hq_update, a.locgroup, a.ibt, a.SubTotal1, a.Discount1, a.Discount1Type, a.SubTotal2, a.Discount2, a.Discount2Type, a.gst_tax_sum, a.tax_code_purchase, IF(b.ext_doc1 IS NULL, a.sup_cn_no, b.ext_doc1 ) AS sup_cn_no, IF(b.ext_date1 IS NULL, a.sup_cn_date, b.ext_date1) AS sup_cn_date, a.doc_name_reg, a.gst_tax_rate, a.multi_tax_code, a.refno2, a.surchg_tax_sum, a.gst_adj, a.rounding_adj, a.unpostby, a.unpostdatetime, a.ibt_gst, a.acc_posting_date, a.RoundAdjNeed FROM b2b_summary.cnnotemain AS a LEFT JOIN (SELECT * FROM b2b_summary.ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' AND `type` = 'PRDNCN') AS b ON a.refno = b.refno WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' AND a.type = 'CN' ");
            }

            $type = $parameter->row('type');
            $code = $check_scode;

            if($xtype == 'DEBIT')
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prdn'")->row('query');
            }
            else
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prcn'")->row('query');
            }   

            $upload_cn_setting = $this->db->query("SELECT * FROM $database.acc_settings WHERE customer_guid = '$customer_guid'");
            // echo $this->db->last_query();die;
            if(count($upload_cn_setting->result()) > 0)
            {
                $upload_cn_setting_flag = $upload_cn_setting->row('upload_cn_setting');
            }
            else
            {
                $upload_cn_setting_flag = 0;
            }

            if($upload_cn_setting_flag == 1)
            {
                $check_upload_cn = 1;
                $check_supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.dbnotemain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
                //echo $this->db->last_query();die;
                // $target_dir = "retailer_file/".$customer_guid."/prdncn_cn/".$check_supplier_guid->row('supplier_guid')."/".$refno.'.pdf';

                $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');

                // $file_config_final_path = $this->file_config_b2b->merge_print_create_file_path($customer_guid,'web','general_doc','merge_print','MPMPCP');
                $file_path_name = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','upload_grn_cn','UGRNCN');

                $target_dir = $file_path_name.$path_seperator.$customer_guid.$path_seperator."prdncn_cn".$path_seperator.$check_supplier_guid->row('supplier_guid').$path_seperator.$refno.'.pdf';
                if(file_exists($target_dir))
                {
                    $exists_upload_cn_file = 1;
                }
                else
                {
                    $exists_upload_cn_file = 0;
                }
                // echo $exists_upload_cn_file;die;
            }
            else
            {
                $check_supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.dbnotemain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$refno'");
                // if others retailer need generate cn need copy the above query file targer dir.
                $check_upload_cn = 0;
                $exists_upload_cn_file = 1;
            }
            // echo $this->db->last_query();die;
            // echo 'asda'.$replace_var.'<br>';
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';
            // echo $filename;die;
            $file_headers = @get_headers($filename);

            $check_upload_doc_log = $this->db->query("SELECT refno,created_at,DATE_ADD(created_at,INTERVAL 14 DAY) AS new_check_date, IF(DATE_FORMAT(DATE_ADD(created_at,INTERVAL 14 DAY), '%Y-%m-%d') > CURDATE() , '1' , '0') AS valid_reupload FROM b2b_summary.upload_doc_log WHERE refno = '$refno' AND customer_guid = '$customer_guid' AND doc_type = 'prdn_cn' ORDER BY created_at DESC LIMIT 1 ");

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'PR DN/CN',
                'sup_cn_header' => $get_DN_detail,
                'xtype' => $xtype,
                'check_status' => $check_status,
                'exists_upload_cn_file' => $exists_upload_cn_file,
                'check_upload_cn' => $check_upload_cn,
                'cnfilepath' => $target_dir,
                'file_supplier_guid' => $check_supplier_guid->row('supplier_guid'),
                'file_upload_type' => 'prdncn_cn',
                'check_uploaded_image_strb' => $check_strb_data->row('uploaded_image'),
                'strb_refno' => $check_strb_data->row('batch_no'),
                'strb_docdate' => $check_strb_data->row('docdate'),
                'valid_reupload_time' => $check_upload_doc_log->row('valid_reupload'),
            );
            // echo $filename;die;
            $customer_guid = $_SESSION['customer_guid'];        
            $user_guid = $_SESSION['user_guid'];        
            $from_module = $_SESSION['frommodule'];   

            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                if($xtype == 'DEBIT')      
                {       
                    $this->db->query("UPDATE b2b_summary.dbnotemain set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");      
                    $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'viewed_PRDN'        
                    , '$from_module'        
                    , '$refno'      
                    , now()     
                    ");     
                    // redirect ($filename);       
                }       
                else        
                {       
                    $this->db->query("UPDATE b2b_summary.cnnotemain set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");       
                    $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'viewed_PRCN'        
                    , '$from_module'        
                    , '$refno'      
                    , now()     
                    ");     
                    // redirect ($filename);       
                } 
            }

            $this->load->view('header');       
            $this->load->view('prdncn/panda_prdncn_pdf',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function generate_ecn1()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('ecn_refno[]');
            $type = $this->input->post('ecn_type[]');
            $sup_cn_no = $this->input->post('sup_cn_no[]');
            $sup_cn_date = $this->input->post('sup_cn_date[]');
            $amount = $this->input->post('ecn_varianceamt[]');
            $tax_rate = $this->input->post('ecn_tax_rate[]');
            $tax_amount = $this->input->post('ecn_gst_tax_sum[]');
            $total_incl_tax = $this->input->post('ecn_total_incl_tax[]');
            $loc = $this->input->post('ecn_loc[]');
            $line = $this->input->post('ecn_rows[]');
           /*  $gr_refno = $this->input->post('gr_refno');*/
            $current_loc = $this->input->post('current_loc');


            //print_r($this->input->post());die;
            //latest for retrieve invoice number
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];
            $invoice_number = $this->db->query("SELECT invno FROM einv_main WHERE refno = '$req_refno'  ")->row('invno');

            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            // $to_shoot_url = "http://18.139.87.215/rest_api/index.php/return_json/childdata?table=dbnotebatch_child"."&refno=1030DNB2019040014&transtype=DEBIT";
            $to_shoot_url = "http://192.168.10.29/rest_api/index.php/return_json/childdata?table=batch_e_cn"."&refno=1030DNB2019040014&transtype=DEBIT";
                echo $to_shoot_url ;die;
            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            //from here get child, then we need insert child
            if($response !== false) 
            {
                    $get_child_dncn = json_decode(file_get_contents($to_shoot_url), true);
                    $child_result_validation = $get_child_dncn[0]['line']; 
                    
            }
            else
            {
                $get_child_dncn = array();
                $child_result_validation = '0';
                $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E CN is currently not available.'); 

            }
           echo var_dump($get_child_dncn) ;die;


            foreach($line as $i => $id) 
            {
                /*$check_exist = $this->db->query("SELECT * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");*/
                $check_exist = $this->db->query("SELECT * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
             
                if($check_exist->num_rows() > 0)
                {
                    $revision = $check_exist->row('revision') + 1;
                   /* $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
                    $this->db->query("DELETE FROM ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");*/
                     $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
                    $this->db->query("DELETE FROM ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
                }
                else
                {
                    $revision = '0';
                }
 
                if(is_null($sup_cn_no[$i]) || $sup_cn_no[$i] == ' '|| $sup_cn_no[$i] == '')
                {
                   //echo $refno[$i];echo $type[$i];die;
                    unset($refno[$i]);  unset($type[$i]);
                    $this->session->set_flashdata('message', 'E-CN ext Doc cannot be empty');
                };

                //echo var_dump($ext_doc1[0]);die;
                
                $data1[] = [
                    'customer_guid' => $customer_guid, 
                    'ecn_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    //'status' => '',
                    'refno' => $refno[$i],
                    'type' => $transtype,
                    'ext_doc1' => str_replace(' ', '',$sup_cn_no[$i]),
                    'ext_date1' => $sup_cn_date[$i],
                    'amount' =>  $amount[$i],
                    'tax_rate' =>   $tax_rate[$i],
                    'tax_amount' =>  $tax_amount[$i],
                    'total_incl_tax' =>  $total_incl_tax[$i],
                    'revision' =>  $revision,
                    'posted' =>  '0',
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' =>  $_SESSION['user_guid'],
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $_SESSION['user_guid'],
                ]; 
            }
            $ecnmain = $this->db->query("SELECT * from lite_b2b.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = '$transtype'");
            if($ecnmain->num_rows() == 0)
            {
                $this->db->insert_batch('ecn_main', $data1);
            };
            $header =  $this->db->query("SELECT * from lite_b2b.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = '$transtype'");
            //$this->General_model->replace_data('ecn_main', $data1);
            $this->db->query("DELETE FROM ecn_main where refno is null and type is null and customer_guid = '".$_SESSION['customer_guid']."'"); 
            // /echo var_dump(count($get_child_dncn));die;

            //echo 'insert here; pause';die;
            for($i = 0; $i < count($get_child_dncn); $i++ )
            {
                $data_ecn_child[] = [
                        'customer_guid' => $customer_guid,
                        'status' => '',
                        'refno' => $get_child_dncn[$i]['refno'],
                        'refno_dn' => $get_child_dncn[$i]['pono'],
                        'transtype' => 'PRDNCN',
                        'location' => $get_child_dncn[$i]['location'],
                        'line' => $get_child_dncn[$i]['line'],
                        'itemcode' => $get_child_dncn[$i]['itemcode'],
                        'barcode' => $get_child_dncn[$i]['barcode'],
                        'description' => $get_child_dncn[$i]['description'],
                        'qty' => $get_child_dncn[$i]['qty'],
                        /*'inv_qty' => $get_child_dncn[$i]['inv_qty'],
                        'inv_netunitprice' => $get_child_dncn[$i]['inv_netunitprice'],
                        'inv_totalprice' => $get_child_dncn[$i]['inv_totalprice'],*/
                        'supplier' => $get_child_dncn[$i]['supplier'],
                        'invno' => $get_child_dncn[$i]['pono'],
                        'dono' => $get_child_dncn[$i]['docno'],
                        'porefno' => $get_child_dncn[$i]['pono'],
                        'title2' => $get_child_dncn[$i]['reason'],
                        'notes' => $get_child_dncn[$i]['title'],
                        'pounitprice' => $get_child_dncn[$i]['unitprice1'],
                        'invactcost'=> $get_child_dncn[$i]['sysavgcost'],
                        'netunitprice'=> $get_child_dncn[$i]['averagecost'],
                        'pototal'=> $get_child_dncn[$i]['totalsysavgcostafter'],
                        'articleno'=> $get_child_dncn[$i]['articleno'],
                        'packsize'=> $get_child_dncn[$i]['packsize'],
                        'variance_amt'=> $get_child_dncn[$i]['totalsysavgcostafter'],
                        'reason'=> $get_child_dncn[$i]['reason'],
                        /*'tax_amount'=> $get_child_dncn[$i]['tax_amount'],*/
                        'total_gross'=> $get_child_dncn[$i]['amount'],
                        'created_at'=> $this->db->query("select now() as naw")->row('naw'),
                        'created_by'=> $_SESSION['user_guid'],
                        'updated_at'=> $this->db->query("select now() as naw")->row('naw'),
                        'updated_by'=> $_SESSION['user_guid'],
                    ];
            }
            //remove existing data
            $this->db->query("DELETE FROM lite_b2b.ecn_child where refno = '".$req_refno."' and transtype = '".$transtype."' and customer_guid = '$customer_guid'");

            $ecnchild = $this->db->query("SELECT * from lite_b2b.ecn_child where customer_guid = '$customer_guid' and refno = '$req_refno' and transtype = '$transtype'");
            if($ecnchild->num_rows() != count($get_child_dncn))
            {
               $execute =  $this->db->insert_batch('ecn_child', $data_ecn_child);
            };

            if($this->db->affected_rows() > '0')
            {
                $this->db->query("UPDATE b2b_summary.dbnotemain set status = '2' where refno = '$req_refno' and customer_guid = '$customer_guid' and type = 'DEBIT'");
            };

            // generate PDF for grda
            // 2019-08-18
           // echo $this->db->last_query();die;
           //redirect('panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$current_loc);


           // $this->db->insert_batch('ecn_child', $data_ecn_child);
            /*$get_ecn_child_data = $this->db->query("SELECT b.* from lite_b2b.ecn_main as a inner join lite_b2b.ecn_child as b on a.refno = b.refno where a.customer_guid = '$customer_guid' and a.refno = '$req_refno' and transtype = '$transtype'");*/
            $get_ecn_child_data = $this->db->query("SELECT  * FROM  lite_b2b.ecn_child  WHERE customer_guid = '$customer_guid'  AND refno = '$req_refno'  AND transtype = 'PRDNCN' ");

             
            //$invoice_number = $_REQUEST['refno'].'_'.$_REQUEST['transtype'];
            $invoice_number = $_REQUEST['refno'].'_'.'PRDNCN';
           // echo  $_REQUEST['refno'];die;
          /*  $gr_info = $this->db->query("SELECT 
            a.`Location`
            , a.`Code`
            , a.`Name`
            , ifnull(b.invno,a.`Invno`) as Invno
            FROM b2b_summary.dbnotemain AS a 
            LEFT JOIN lite_b2b.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid and a where a.refno = '$req_refno' 
            and a.customer_guid = '$customer_guid'");
*/

                $gr_info = $this->db->query("SELECT  b.* 
FROM
  lite_b2b.ecn_main AS a 
  INNER JOIN b2b_summary.dbnotemain AS b 
    ON a.refno = b.refno 
    AND a.customer_guid = b.customer_guid  where a.type = 'PRDNCN' and a.refno = '$req_refno' ");

            $data = array  (
                'query_data' =>  $this->db->query("SELECT a.refno ,a.status , a.type , a.ext_doc1 , a.ext_date1, a.amount , a.`tax_rate` , a.`tax_amount` , a.`total_incl_tax` , a.posted , b.refno_dn , b.transtype , b.location , b.itemcode , b.barcode , b.description , b.qty , b.inv_qty , b.inv_netunitprice , b.supplier , b.invno , b.dono , b.porefno , b.title2 , b.notes , b.pounitprice , b.invactcost , b.netunitprice , b.pototal , b.articleno , b.packsize , b.variance_amt , b.reason , b.tax_amount , b.total_gross FROM lite_b2b.ecn_main AS a INNER JOIN lite_b2b.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = 'PRDNCN'"),
                'supcus_supplier' => $this->db->query("SELECT * FROM b2b_summary.supcus WHERE Code = '".$gr_info->row('Location')."' and customer_guid = '$customer_guid'"),
                'supcus_customer' => $this->db->query("SELECT * from b2b_summary.supcus where code = '".$gr_info->row('Code')."' and customer_guid = '$customer_guid'"),
                'customer_branch_info' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '".$gr_info->row('Location')."'   and customer_guid = '$customer_guid'"),
            );

            
            $load_pdf = $this->load->view('prdncn/panda_ecn_pdf', $data, true);
            $this->load->library('Pdf_ecn');
            $pdf = new Pdf_ecn('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor('xBridge');
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
            $pdf->AddPage('L');
            ob_start();
            $pdf->writeHTML($load_pdf, true, false, true, false, '');
            ob_end_clean();
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'github/panda_b2b_test/uploads/tfvaluemart/invoice/B2B_'.$invoice_number.'.pdf', 'F');           
            
            $data = array(

               'filename' =>  'B2B_'.$invoice_number.'.pdf',
               'path' => $_SERVER["DOCUMENT_ROOT"].'invoice/B2B_'.$invoice_number.'.pdf'

            ); 

            ob_end_clean();
            $pdf->Output($req_refno.$transtype, 'I');

        }
        else
        {
           $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#'); 
        }
    }

    public function view_ecn()
{
    if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
    {       
            $this->panda->get_uri();
            $database1 = 'b2b_summary';
            $customer_guid = $_SESSION['customer_guid'];
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];
            $invoice_number = $_REQUEST['refno']-$_REQUEST['transtype'];

            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            $to_shoot_url = $check_url."/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://192.168.10.29/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://18.139.87.215/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://202.75.55.22/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
                // echo $to_shoot_url ;die;
            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            //from here get child, then we need insert child
            if($response !== false) 
            {
                    $get_child_dncn = json_decode(file_get_contents($to_shoot_url), true);
                    $child_result_validation = $get_child_dncn['child'][0]['line']; 
                    
            }
            else
            {
                $get_child_dncn = array();
                $child_result_validation = '0';
                $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E CN is currently not available.'); 

            }

            $gr_info = $this->db->query("SELECT  b.* 
FROM
  $database1.ecn_main AS a 
  INNER JOIN $database1.dbnotemain AS b 
    ON a.refno = b.refno 
    AND a.customer_guid = b.customer_guid  where a.type = 'PRDNCN' and a.refno = '$req_refno'  ");


             $data = array  (
                'query_data' =>  $this->db->query("SELECT a.refno ,a.status , a.type , a.ext_doc1 , a.ext_date1, a.amount , a.`tax_rate` , a.`tax_amount` , a.`total_incl_tax` , a.posted , b.refno_dn , b.transtype , b.location , b.itemcode , b.barcode , b.description , b.qty , b.inv_qty , b.inv_netunitprice , b.supplier , b.invno , b.dono , b.porefno , b.title2 , b.notes , b.pounitprice , b.invactcost , b.netunitprice , b.pototal , b.articleno , b.packsize , b.variance_amt , b.reason , b.tax_amount , b.total_gross FROM $database1.ecn_main AS a INNER JOIN $database1.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = 'PRDNCN'"),
                  'supcus_supplier' => $this->db->query("SELECT * FROM $database1.supcus WHERE Code = '".$gr_info->row('Location')."' and customer_guid = '$customer_guid'"),
                'supcus_customer' => $this->db->query("SELECT * from $database1.supcus where code = '".$gr_info->row('Code')."' and customer_guid = '$customer_guid'"),
                'customer_branch_info' => $this->db->query("SELECT * FROM $database1.cp_set_branch WHERE BRANCH_CODE = '".$gr_info->row('Location')."'   and customer_guid = '$customer_guid'"),
                'header' => $get_child_dncn['header'],
                'child' => $get_child_dncn['child'],
            );

            
            $load_pdf = $this->load->view('prdncn/panda_ecn_pdf', $data, true);
            $this->load->library('Pdf_ecn');
            $pdf = new Pdf_ecn('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetTitle($invoice_number);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->SetAuthor('xBridge');
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->setPageUnit('pt');
            $x = $pdf->pixelsToUnits('20');
            $y = $pdf->pixelsToUnits('20');
            $font_size = $pdf->pixelsToUnits('9.5');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
            $pdf->AddPage('L');
            ob_start();
            $pdf->writeHTML($load_pdf, true, false, true, false, '');
            ob_end_clean();
            //$pdf->Output($_SERVER['DOCUMENT_ROOT'] .'invoice/invoice/B2B_'.$invoice_number.'.pdf', 'F');           
            // $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'invoice/invoice/B2B_'.$invoice_number.'.pdf', 'F');           
            
            $data = array(

                   'filename' =>  'B2B_'.$invoice_number.'.pdf',
                   'path' => $_SERVER["DOCUMENT_ROOT"].'invoice/B2B_'.$invoice_number.'.pdf'

            ); 

            ob_end_clean();
            $pdf->Output($req_refno.$transtype, 'I');
    }
    else
    {
        $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#'); 
    }
}


//danieladdprint        
    public function direct_print()      
    {       
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())     
        {       
            $this->panda->get_uri();        
            $refno = $_REQUEST['trans'];        
            $loc = $_REQUEST['loc'];        
            $customer_guid = $_SESSION['customer_guid'];        
            $user_guid = $_SESSION['user_guid'];        
            $from_module = $_SESSION['frommodule'];     
            $xtype = $_REQUEST['dncn'];     
            // echo $from_module;       
            if($xtype == 'CN')      
            {       
                $check_scode = $this->db->query("SELECT code from b2b_summary.cnnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');        
            }       
            else        
            {       
                $check_scode = $this->db->query("SELECT code from b2b_summary.dbnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');        
            }    
            $check_scode = str_replace("/","+-+",$check_scode);   
            // $check_scode = $this->db->query("SELECT scode from b2b_summary.pomain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');       
            // $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");      
            if($xtype == 'DEBIT')
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdn'");
            }
            else
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prcn'");   
            }

            $type = $parameter->row('type');        
            $code = $check_scode;       
            // $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');      

            if($xtype == 'DEBIT')
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prdn'")->row('query');
            }
            else
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prcn'")->row('query');
            }  

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');      
                
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');        
            // echo $replace_var;die;      
            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';

            $file_headers = @get_headers($filename);        
                        
            // OLD      
            if(in_array('HTTP/1.1 404 Not Found', $file_headers ))      
            {       
                // echo 1;die;      
              echo "<script>alert('No PDF Found');</script>";       
              echo "<script>window.close();</script>";      
            }       
            else        
            {       
                if($xtype == 'CN')      
                {    
                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    {  
                        $this->db->query("UPDATE b2b_summary.cnnotemain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$refno' and status IN('','viewed') ");       
                        $this->db->query("REPLACE into supplier_movement select         
                        upper(replace(uuid(),'-','')) as movement_guid      
                        , '$customer_guid'      
                        , '$user_guid'      
                        , 'printed_PRCN'        
                        , '$from_module'        
                        , '$refno'      
                        , now()     
                        ");     
                    }
                        redirect ($filename);     
                }       
                else        
                {     
                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    { 
                        $this->db->query("UPDATE b2b_summary.dbnotemain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$refno' and status IN ('','viewed') ");       
                        $this->db->query("REPLACE into supplier_movement select         
                        upper(replace(uuid(),'-','')) as movement_guid      
                        , '$customer_guid'      
                        , '$user_guid'      
                        , 'printed_PRDN'        
                        , '$from_module'        
                        , '$refno'      
                        , now()     
                        ");  
                    }   
                    redirect ($filename);       
                }       
            }       
                
        }       
        else        
        {       
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');        
            redirect('#');      
        }       
    }       
        
    public function direct_print_merge()        
    {       
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())     
        {       
            $this->panda->get_uri();        
            $refno = $_REQUEST['trans'];        
            $loc = $_REQUEST['loc'];        
            $customer_guid = $_SESSION['customer_guid'];        
            $user_guid = $_SESSION['user_guid'];        
            $from_module = $_SESSION['frommodule'];     
            $pdf_name = $_REQUEST['pdfname'];       
            $xtype = $_REQUEST['dncn'];     
                // echo $xtype;die;     
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');      
                
            // $filename = base_url($virtual_path.'/'.$pdf_name.'.pdf');        
            // $filename = base_url('merge/'.$pdf_name.'.pdf');
            $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');

            $file_config_final_path = $this->file_config_b2b->merge_print_create_file_path($customer_guid,'web','general_doc','merge_print','MPMPCP');
            $merge_path = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','merge_print','MPN');

            $filename = $file_config_final_path.$path_seperator.$merge_path.$path_seperator.$pdf_name.'.pdf';
            // $filename = 'http://192.168.10.29/lite_panda_b2b/uploads/tfvalue/merge.pdf';     
            // echo $filename;die;      
        
            $file_headers = @get_headers($filename);        
            $refno_array = explode(",",$refno);     
            $xtype_array = explode(",",$xtype);     
            // echo $refno;     
            // print_r($xtype_array);       
            foreach($xtype_array as $row2)      
            {       
                // echo 1;      
                $arr = explode("-->", $row2, 2);        
                $erefno = $arr[0];      
                $etype = $arr[1];       
                // echo $erefno.'**'.$etype.'<br>';     
                // $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status, rejected_remark from b2b_summary.pomain where refno = '$row2' and customer_guid = '".$_SESSION['customer_guid']."'");      
                // $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc");       
                // $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");      
                // $data = array(       
                //     'filename' => $filename,     
                //     'file_headers' => $file_headers,     
                //     'virtual_path' => $virtual_path,     
                //     'title' => 'Purchase Order',     
                //     'check_status' => $check_status,     
                //     'set_code' => $set_code,     
                //     'set_admin_code' =>  $set_admin_code,        
                // );       
                    
                if(in_array('HTTP/1.1 404 Not Found', $file_headers ))      
                {       
                    echo "<script>alert('No PDF Found');</script>";     
                    echo "<script>window.close();</script>";        
                }       
                else        
                {       
                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    {                    
                        if($etype == 'CN')      
                        {       
                            $this->db->query("UPDATE b2b_summary.cnnotemain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$erefno' and status = '' ");      
                            $this->db->query("REPLACE into supplier_movement select         
                            upper(replace(uuid(),'-','')) as movement_guid      
                            , '$customer_guid'      
                            , '$user_guid'      
                            , 'printed_PRCN'        
                            , '$from_module'        
                            , '$erefno'     
                            , now()     
                            ");     
                        }       
                        else        
                        {       
                            $this->db->query("UPDATE b2b_summary.dbnotemain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$erefno' and status = '' ");      
                            $this->db->query("REPLACE into supplier_movement select         
                            upper(replace(uuid(),'-','')) as movement_guid      
                            , '$customer_guid'      
                            , '$user_guid'      
                            , 'printed_PRDN'        
                            , '$from_module'        
                            , '$erefno'     
                            , now()     
                            ");     
                        }  
                    }     
                    // redirect ($filename);        
                }       
            }       
            $file = $filename; 
            // echo $filename;die;
            if (!file_exists($file))
            {
                echo "The file not exists. Please Contact Admin";die;
            }
            // die;
            $type = 'inline';
            // $pdf_name = 'merge';
            // echo $pdf_name;die;
            header("Content-type: application/pdf");
            header('Content-Disposition: '.$type.'; filename="'.$pdf_name.'.pdf"'); 
            // header("Content-Disposition: attachment; filename=\"".$Filename."\"");
            // header("Content-Length: ".filesize($Filename));
            header('Cache-Control: public, must-revalidate, max-age=0');
            ob_clean();
            flush();
            readfile($file);
            die; 
            /*$this->load->view('header');              
            $this->load->view('po/panda_po_pdf',$data);     
            $this->load->view('general_modal',$data);       
            $this->load->view('footer');*/      
        }       
        else        
        {       
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');        
            redirect('#');      
        }       
    }
 
    public function generate_ecn()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 1;die;
            $this->panda->get_uri();
            $database1 = 'b2b_summary';
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('ecn_refno[]');
            $type = $this->input->post('ecn_type[]');
            $sup_cn_no = $this->input->post('sup_cn_no[]');
            $sup_cn_date = $this->input->post('sup_cn_date[]');
            $amount = $this->input->post('ecn_varianceamt[]');
            $tax_rate = $this->input->post('ecn_tax_rate[]');
            $tax_amount = $this->input->post('ecn_gst_tax_sum[]');
            $total_incl_tax = $this->input->post('ecn_total_incl_tax[]');
            $loc = $this->input->post('ecn_loc[]');
            $line = $this->input->post('ecn_rows[]');
           /*  $gr_refno = $this->input->post('gr_refno');*/
            $current_loc = $this->input->post('current_loc');
            $prdn_loc = $this->input->post('prdn_loc');
            $prdn_type = $this->input->post('ecn_type');


            // print_r($this->input->post());die;
            //latest for retrieve invoice number
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];

            $check_if_exists_ecn = $this->db->query("SELECT * FROM b2b_summary.ecn_main WHERE refno != '$req_refno' AND type = '$transtype' AND customer_guid = '$customer_guid' AND ext_doc1 = '$sup_cn_no[0]'");
            // echo $check_if_exists_ecn->num_rows();die;
            if($check_if_exists_ecn->num_rows() > 0)
            {
                // echo 'cn number duplicate';die;
                $check_if_exists_ecn2 = $this->db->query("SELECT * FROM b2b_summary.dbnotemain WHERE refno = '$req_refno' AND customer_guid = '$customer_guid'");
                // echo $this->db->last_query();die;
                $check_if_exists_ecn2_code = $check_if_exists_ecn2->row('Code');
                $check_if_exists_ecn2_supcode = $this->db->query("SELECT b.* FROM b2b_summary.supcus a LEFT JOIN b2b_summary.`supcus` b ON a.`AccountCode` = b.`AccountCode` AND a.`customer_guid` = b.customer_guid WHERE a.code = '$check_if_exists_ecn2_code' AND a.customer_guid = '$customer_guid' GROUP BY b.`customer_guid`,b.code");
                // echo $this->db->last_query();die;
                $check_if_exists_ecn2_supcode_string = '';
                foreach ($check_if_exists_ecn2_supcode->result() as $row)
                {
                    $check_if_exists_ecn2_supcode_string .= "'".$row->Code."',";
                }
                $check_if_exists_ecn2_supcode_string2 = rtrim($check_if_exists_ecn2_supcode_string,',');
                // echo rtrim($check_if_exists_ecn2_supcode_string,',').'sdsd<br>';die;
                $check_if_exists_ecn3 = $this->db->query("SELECT b.* FROM b2b_summary.ecn_main a INNER JOIN b2b_summary.dbnotemain b ON a.`customer_guid` = b.`customer_guid` AND a.refno = b.refno WHERE a.refno != '$req_refno' AND a.customer_guid = '$customer_guid' AND a.ext_doc1 = '$sup_cn_no[0]' AND CODE IN($check_if_exists_ecn2_supcode_string2)");
                // echo $this->db->last_query();die;
                if($check_if_exists_ecn3->num_rows() > 0)
                {
                    $this->session->set_flashdata('warning',  'CN number repeat');
                    // echo '/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT';die;
                    redirect('/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT');
                }
                // echo $this->db->last_query();die;
            }

            $invoice_number = $this->db->query("SELECT invno FROM $database1.einv_main WHERE refno = '$req_refno'  ")->row('invno');

            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            $to_shoot_url = $check_url."/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://192.168.10.29/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://18.139.87.215/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
            // $to_shoot_url = "http://202.75.55.22/rest_api/index.php/return_json/batch_e_cn?refno=".$req_refno."&transtype=DEBIT";
                // echo $to_shoot_url ;die;
            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);
            //from here get child, then we need insert child
            if($response !== false) 
            {
                    $get_child_dncn = json_decode(file_get_contents($to_shoot_url), true);
                    $child_result_validation = $get_child_dncn['child'][0]['line']; 
                    
            }
            else
            {
                $get_child_dncn = array();
                $child_result_validation = '0';
                $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E CN is currently not available.'); 

            }
            // print_r(count($get_child_dncn));die;
            // echo $get_child_dncn['child'][0]['refno'];die;
           // echo var_dump(count($get_child_dncn['child'])) ;die;
            foreach($line as $i => $id) 
            {
                /*$check_exist = $this->db->query("SELECT * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");*/
                $check_exist = $this->db->query("SELECT * from $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
             
                if($check_exist->num_rows() > 0)
                {
                    $revision = $check_exist->row('revision') + 1;
                   /* $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
                    $this->db->query("DELETE FROM ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");*/
                     $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
                    $this->db->query("DELETE FROM $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = 'PRDNCN'");
                }
                else
                {
                    $revision = '0';
                }
 
                if(is_null($sup_cn_no[$i]) || $sup_cn_no[$i] == ' '|| $sup_cn_no[$i] == '')
                {
                   //echo $refno[$i];echo $type[$i];die;
                    unset($refno[$i]);  unset($type[$i]);
                    $this->session->set_flashdata('message', 'E-CN ext Doc cannot be empty');
                };

                //echo var_dump($ext_doc1[0]);die;
                
                $data1[] = [
                    'customer_guid' => $customer_guid, 
                    'ecn_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    //'status' => '',
                    'refno' => $refno[$i],
                    'type' => $transtype,
                    'ext_doc1' => str_replace(' ', '',$sup_cn_no[$i]),
                    'ext_date1' => $sup_cn_date[$i],
                    'amount' =>  $amount[$i],
                    'tax_rate' =>   $tax_rate[$i],
                    'tax_amount' =>  $tax_amount[$i],
                    'total_incl_tax' =>  $total_incl_tax[$i],
                    'revision' =>  $revision,
                    'posted' =>  '0',
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' =>  $_SESSION['user_guid'],
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $_SESSION['user_guid'],
                ]; 
            }
            // print_r($data1);die;
            $ecnmain = $this->db->query("SELECT * from $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = '$transtype'");
            // echo $ecnmain->num_rows() ;die;
            if($ecnmain->num_rows() == 0)
            {
                $this->db->insert_batch($database1.'.ecn_main', $data1);
                // echo $this->db->last_query();die;
            };

            $this->db->query("DELETE FROM ecn_main where refno is null and type is null and customer_guid = '".$_SESSION['customer_guid']."'"); 
            // echo var_dump(count($get_child_dncn));die;
            // echo var_dump($get_child_dncn['header']);die;
            // echo  $get_child_dncn['child'][0]['porefno'];die;
            //echo 'insert here; pause';die;
            // print_r(count($get_child_dncn['child']));die;
            for($i = 0; $i < count($get_child_dncn['child']); $i++ )
            {
                $data_ecn_child[] = [
                        'customer_guid' => $customer_guid,
                        'status' => '',
                        'refno' => $get_child_dncn['child'][$i]['refno'],
                        // 'refno_dn' => $get_child_dncn['child'][$i]['pono'],
                        'transtype' => 'PRDNCN',
                        // 'location' => $get_child_dncn['child'][$i]['location'],
                        'line' => $get_child_dncn['child'][$i]['line'],
                        'itemcode' => $get_child_dncn['child'][$i]['itemcode'],
                        'barcode' => $get_child_dncn['child'][$i]['barcode'],
                        'description' => $get_child_dncn['child'][$i]['description'],
                        'qty' => $get_child_dncn['child'][$i]['qty'],
                        /*'inv_qty' => $get_child_dncn['child'][$i]['inv_qty'],
                        'inv_netunitprice' => $get_child_dncn['child'][$i]['inv_netunitprice'],
                        'inv_totalprice' => $get_child_dncn['child'][$i]['inv_totalprice'],*/
                        // 'supplier' => $get_child_dncn['child'][$i]['supplier'],
                        // 'invno' => $get_child_dncn['child'][$i]['pono'],
                        // 'dono' => $get_child_dncn['child'][$i]['docno'],
                        // 'porefno' => $get_child_dncn['child'][$i]['pono'],
                        'title2' => $get_child_dncn['child'][$i]['reason'],
                        // 'notes' => $get_child_dncn['child'][$i]['title'],
                        // 'pounitprice' => $get_child_dncn['child'][$i]['unitprice1'],
                        // 'invactcost'=> $get_child_dncn['child'][$i]['sysavgcost'],
                        // 'netunitprice'=> $get_child_dncn['child'][$i]['averagecost'],
                        // 'pototal'=> $get_child_dncn['child'][$i]['totalsysavgcostafter'],
                        'articleno'=> $get_child_dncn['child'][$i]['articleno'],
                        'packsize'=> $get_child_dncn['child'][$i]['packsize'],
                        // 'variance_amt'=> $get_child_dncn['child'][$i]['totalsysavgcostafter'],
                        'reason'=> $get_child_dncn['child'][$i]['reason'],
                        /*'tax_amount'=> $get_child_dncn['child'][$i]['tax_amount'],*/
                        // 'total_gross'=> $get_child_dncn['child'][$i]['amount'],
                        'created_at'=> $this->db->query("select now() as naw")->row('naw'),
                        'created_by'=> $_SESSION['user_guid'],
                        'updated_at'=> $this->db->query("select now() as naw")->row('naw'),
                        'updated_by'=> $_SESSION['user_guid'],
                    ];
            }
            // print_r($data_ecn_child[0]['line']);die;
            if($data_ecn_child[0]['line'] == 'No Records Found')
            {
                $this->db->query("DELETE FROM $database1.ecn_main where customer_guid = '$customer_guid' and refno = '$req_refno' and type = 'PRDNCN'");
                // echo $this->db->last_query();die;
                $this->session->set_flashdata('warning',  'Record not found, Please contact Support');
                    // echo '/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT';die;
                redirect('/panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$prdn_loc.'&type=DEBIT');
            };
            //remove existing data
            $this->db->query("DELETE FROM $database1.ecn_child where refno = '".$req_refno."' and transtype = '".$transtype."' and customer_guid = '$customer_guid'");

            $ecnchild = $this->db->query("SELECT * from $database1.ecn_child where customer_guid = '$customer_guid' and refno = '$req_refno' and transtype = '$transtype'");
            if($ecnchild->num_rows() != count($get_child_dncn))
            {
               $execute =  $this->db->insert_batch($database1.'.ecn_child', $data_ecn_child);
            };            

             // die;
            //$invoice_number = $_REQUEST['refno'].'_'.$_REQUEST['transtype'];
            $invoice_number = $_REQUEST['refno'].'_'.'PRDNCN';
           // echo  $_REQUEST['refno'];die;
          /*  $gr_info = $this->db->query("SELECT 
            a.`Location`
            , a.`Code`
            , a.`Name`
            , ifnull(b.invno,a.`Invno`) as Invno
            FROM b2b_summary.dbnotemain AS a 
            LEFT JOIN lite_b2b.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid and a where a.refno = '$req_refno' 
            and a.customer_guid = '$customer_guid'");
*/

                $gr_info = $this->db->query("SELECT  b.* 
FROM
  $database1.ecn_main AS a 
  INNER JOIN $database1.dbnotemain AS b 
    ON a.refno = b.refno 
    AND a.customer_guid = b.customer_guid  where a.type = 'PRDNCN' and a.refno = '$req_refno' ");
                // print_r($gr_info->result());die;

            $data = array  (
                'query_data' =>  $this->db->query("SELECT a.refno ,a.status , a.type , a.ext_doc1 , a.ext_date1, a.amount , a.`tax_rate` , a.`tax_amount` , a.`total_incl_tax` , a.posted , b.refno_dn , b.transtype , b.location , b.itemcode , b.barcode , b.description , b.qty , b.inv_qty , b.inv_netunitprice , b.supplier , b.invno , b.dono , b.porefno , b.title2 , b.notes , b.pounitprice , b.invactcost , b.netunitprice , b.pototal , b.articleno , b.packsize , b.variance_amt , b.reason , b.tax_amount , b.total_gross FROM $database1.ecn_main AS a INNER JOIN $database1.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = 'PRDNCN'"),
                'supcus_supplier' => $this->db->query("SELECT * FROM $database1.supcus WHERE Code = '".$gr_info->row('Location')."' and customer_guid = '$customer_guid'"),
                'supcus_customer' => $this->db->query("SELECT * from $database1.supcus where code = '".$gr_info->row('Code')."' and customer_guid = '$customer_guid'"),
                'customer_branch_info' => $this->db->query("SELECT * FROM $database1.cp_set_branch WHERE BRANCH_CODE = '".$gr_info->row('Location')."'   and customer_guid = '$customer_guid'"),
                'header' => $get_child_dncn['header'],
                'child' => $get_child_dncn['child'],
            );

            if($child_result_validation > 0)
            {         
                $customer_guid = $_SESSION['customer_guid'];        
                $user_guid = $_SESSION['user_guid'];        
                $from_module = $_SESSION['frommodule'];     

                $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'generated_prdn_ecn'        
                    , '$from_module'        
                    , '$req_refno'      
                    , now()     
                    ");   
                // echo 1;die;
                $this->db->query("UPDATE $database1.dbnotemain SET status = 'cn_generated' WHERE refno = '$req_refno' AND type = 'DEBIT'");

                $load_pdf = $this->load->view('prdncn/panda_ecn_pdf', $data, true);
                $this->load->library('Pdf_ecn');
                $pdf = new Pdf_ecn('P', 'mm', 'A4', true, 'UTF-8', false);
                $pdf->SetTitle($invoice_number);
                $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
                $pdf->SetAuthor('xBridge');
                $pdf->SetDisplayMode('real', 'default');
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->setPageUnit('pt');
                $x = $pdf->pixelsToUnits('20');
                $y = $pdf->pixelsToUnits('20');
                $font_size = $pdf->pixelsToUnits('7.5');
                $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );
                $pdf->AddPage('L');
                // $pdf->AddPage('L','A4','0');
                ob_start();
                $pdf->writeHTML($load_pdf, true, false, true, false, '');
                ob_end_clean();
                // $pdf->Output('name.pdf', 'I');;die;
                // $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'github/panda_b2b_test/uploads/tfvaluemart/invoice/B2B_'.$invoice_number.'.pdf', 'S');//create pdf file           

                $data = array(

                   'filename' =>  'B2B_'.$invoice_number.'.pdf',
                   'path' => $_SERVER["DOCUMENT_ROOT"].'invoice/B2B_'.$invoice_number.'.pdf'

                ); 

                ob_end_clean();
                $pdf->Output($req_refno.$transtype, 'I');//view pdf file
            }
            else
            {
                echo 'No data found';
            }
        }
        else
        {
           $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#'); 
        }
    }    

    public function cn_file_unlink()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $_SESSION['customer_guid'];
            $db_refno = $this->input->post('db_refno');

            $supplier_guid = $this->db->query("SELECT b.`supplier_guid` FROM b2b_summary.dbnotemain a INNER JOIN lite_b2b.`set_supplier_group` b ON a.code = b.`supplier_group_name` AND b.`customer_guid` = '$customer_guid' WHERE a.customer_guid = '$customer_guid' AND a.refno = '$db_refno'");
            
            $get_supplier_guid = $supplier_guid->row('supplier_guid');

            //$get_supplier_guid = '7526D74B421F11E99994000D3AA2838A';

            if ($get_supplier_guid == '' || $get_supplier_guid == null) 
            {
                $this->session->set_flashdata('message', 'Supplier ID Empty, Please Contact Admin.');
                redirect('panda_prdncn/prdncn_child?trans='.$refno.'&loc='.$loc.'&type='.$doc_type);
            } 

            $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');

            $file_path_name = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','upload_prdn_cn','UPRDNCN');

            $get_data_log = $this->db->query("SELECT customer_guid,refno,created_at,file_path FROM b2b_summary.upload_doc_log WHERE refno = '$db_refno' AND customer_guid = '$customer_guid' ORDER BY created_at DESC LIMIT 1");

            $file_path = $get_data_log->row('file_path');
            $target_dir = $file_path;

            if (file_exists($target_dir))
            {
                //print_r($target_dir); die;
                unlink($target_dir);
                ///media/b2b-pdf/retailer_file/13EE932D98EB11EAB05B000D3AA2838A/prdncn_cn/7526D74B421F11E99994000D3AA2838A/1017DN21050001.pdf
            }

            if (!file_exists($target_dir))
            {
                $data = array(
                    'para1' => 'true',
                    'msg' => 'Success removed Document.',
                 );    
                 echo json_encode($data);   
                 exit();
            }
            else
            {   
                $data = array(
                'para1' => 'false',
                'msg' => 'File still exisit.',
                );    
                echo json_encode($data);  
                exit(); 
            }

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#'); 
        }
    }

}
?>