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
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login())
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
                    redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=');
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
                redirect('general/view_status?status='.$_REQUEST['loc']);
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
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            if(isset($_REQUEST['accpt_po_status']))
            {
                $accpt_po_status = $_REQUEST['accpt_po_status'];
            }
            else
            {
                // $accpt_po_status = $_REQUEST['accpt_po_status'];
                // if($accpt_po_status == '' || $accpt_po_status == null)
                // {
                    $accpt_po_status = 'NEW';
                // }
            }
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];

            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_po'
                , '$from_module'
                , '$refno'
                , now()
                ");
                
                $this->db->query("UPDATE b2b_summary.pomain set status = 'viewed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");

            
            };
          
            
            $check_scode = $this->db->query("SELECT scode from b2b_summary.pomain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');

            $check_scode = str_replace("/","+-+",$check_scode);

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');
            // echo $this->db->last_query();die;

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');

            // $test = $this->file_config_b2b->file_path('$customer_guid','$device_type','$module_type','$type,$code');
            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');
            // $test = $this->file_config_b2b->file_path();die;
            // echo $test;die;
            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';
            // echo $filename;die;
            $file_headers = @get_headers($filename);

            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status, rejected_remark from b2b_summary.pomain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");

            // $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc");
            $set_code = $this->db->query("SELECT code,portal_description as reason from status_setting where type = 'reject_po' AND isactive = 1 order by portal_description asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");

            $subscribe_edi = $this->db->query("SELECT b.subscribe_edi
            FROM lite_b2b.set_supplier_group AS a
            INNER JOIN lite_b2b.set_supplier AS b
            ON a.supplier_guid = b.supplier_guid
            WHERE a.supplier_group_name = '$check_scode'
            GROUP BY a.supplier_group_name")->row('subscribe_edi');

         
            if ($check_status->row('status') == 'Pending' || $check_status->row('status') == 'viewed' || $check_status->row('status') == 'printed') {

                if (!in_array('BAPO', $_SESSION['module_code']) || in_array('VEL', $_SESSION['module_code']) && $subscribe_edi == '1') {
                    $show_action_button = '0';
                } else {
                    $show_action_button = '1';
                }
            } else {

                $show_action_button = '0';
                //echo $check_status->row('status'); echo  $show_action_button; die;
            };

            if ($check_status->row('status') == 'Pending' || $check_status->row('status') == 'viewed' || $check_status->row('status') == 'printed') {

                if (in_array('!RPO', $_SESSION['module_code'])|| in_array('VEL', $_SESSION['module_code']) && $subscribe_edi == '1') {
                    $show_action_button2 = '0';
                } else {
                    $show_action_button2 = '1';
                }
            } elseif ($check_status->row('status') == 'Accepted' && in_array('VEL', $_SESSION['module_code']) && $subscribe_edi == '1') {
                $show_action_button2 = '1';
            } else {

                $show_action_button2 = '0';
                //echo $check_status->row('status'); echo  $show_action_button; die;
            };  //reject button                     


            // $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            //     $to_shoot_url = $check_url."/pochild?table=pochild"."&refno=".$refno;

            // $ch = curl_init($to_shoot_url);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $response = curl_exec($ch);

            //     if($response !== false) {
            //         echo "string";
            //     }   
            //     else
            //     {                     
            //         $this->session->set_flashdata("noconnection", '<div class="alert alert-warning fade in">Please Check Your Internet Connection</div>');
            //     }
            if(in_array('VHFSP',$_SESSION['module_code']))
            {
                $hide_url = '<a class="btn btn-app" href="'.site_url('PO_hide/view_status').'?status=HFSP&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=" style="color:#ffad33"><i class="fa fa-edit"></i>View Hide</a>';
            }
            else
            {
                $hide_url = '';
            }


            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Purchase Order',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'set_admin_code' =>  $set_admin_code,
                'accpt_po_status' => $accpt_po_status,
                'show_action_button' => $show_action_button,
                'show_action_button2' => $show_action_button2,
                'hide_url' => $hide_url,
            );

            $data_footer = array(
                'activity_logs_section' => 'po'
            );            
            
            $this->load->view('header');       
            $this->load->view('po/panda_po_pdf',$data);
            $this->load->view('general_modal',$data);
            $this->load->view('footer',$data_footer);
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function export_excel()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {   
            $this->panda->get_uri();
            $refno = $_REQUEST['refno'];
            $loc = $_REQUEST['loc'];
            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            $to_shoot_url = $check_url."/pochild?table=pochild"."&refno=".$refno;

            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $response = curl_exec($ch);

                if($response !== false) 
                {
                    $get_child_detail = json_decode(file_get_contents($to_shoot_url), true);
                
                    // foreach($get_child_detail['pochild'][0] AS $key => $value) 
                    // {
                    //     $headers[] = $key; 
                    // }

                    $headers = array(
                        'description',
                        'qty',          
                        'netunitprice',
                        'gst_tax_code',
                        'gst_tax_rate',
                        'gst_tax_amount',
                        'sname',
                        'issuedby',
                    );  

                    foreach ($get_child_detail['pochild'] as $child) 
                    {
                        $data[] = array(
                            // 'refno' => $child['refno'],
                            // 'podate' => $child['podate'],
                            // 'deliverdate' => $child['deliverdate'],
                            // 'itemcode' => $child['itemcode'],                        
                            'description' => $child['description'],
                            // 'barcode' => $child['barcode'],
                            // 'articleno' => $child['articleno'],
                            'qty' => $child['qty'],
                            // 'um' => $child['um'],                            
                            'netunitprice' => $child['netunitprice'],
                            
                            'gst_tax_code' => $child['gst_tax_code'],
                            'gst_tax_rate' => $child['gst_tax_rate'],
                            'gst_tax_amount' => $child['gst_tax_amount'],
                            // 'price_include_tax' => $child['price_include_tax'],
                            //'totalprice_include_tax' => $child['totalprice_include_tax'],

                            //'location' => $child['location'],
                            //'scode' => $child['scode'],
                            'sname' => $child['sname'],
                            //'line' => $child['line'],
                            //'expiry_date' => $child['expiry_date'],                            
                            'issuedby' => $child['issuedby'],
                            );                                      
                    }
            
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);            
                        header("Content-type: application/csv");
                        header("Content-Disposition: attachment; filename=\"".$refno."".".csv\"");
                        header("Pragma: no-cache");
                        ob_clean();
                        
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, $headers);
                        foreach ($data as $data) 
                        {
                            fputcsv($handle, $data);
                        }
                            fclose($handle);
                        exit;
                }   
                else
                {                     
                    $this->session->set_flashdata("noconnection", '<div class="alert alert-warning fade in">Error Connecting Client Server. </div>');
                    redirect('panda_po_2/po_child?trans='.$refno.'&loc='.$loc.'');
                }
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }


    public function direct_print()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];

            $check_scode = $this->db->query("SELECT scode from b2b_summary.pomain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';

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
            //NEW
           /* $files = $data['filename'];
            var_dump($files);die;
            require('fpdf.php');
require('fpdi.php');

                $pdf = new FPDI();
                
                // iterate over array of files and merge
                foreach ($files as $file) {
                    $pageCount = $pdf->setSourceFile($file);
                    for ($i = 0; $i < $pageCount; $i++) {
                        $tpl = $pdf->importPage($i + 1, '/MediaBox');
                        $pdf->addPage();
                        $pdf->useTemplate($tpl);
                    }
                }
                
                // output the pdf as a file (http://www.fpdf.org/en/doc/output.htm)
                $pdf->Output('F','merged.pdf');
                */
                
            // OLD
          
            if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
            {
                
              echo "<script>window.close();</script>";
            }
            else
            {
                if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                {                
                    $this->db->query("UPDATE b2b_summary.pomain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$refno' and status IN ('','viewed') ");
                    
                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'printed_po'
                    , '$from_module'
                    , '$refno'
                    , now()
                    ");
                   
                }
                redirect ($filename);
            } 
          
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

    public function direct_print_merge()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $pdf_name = $_REQUEST['pdfname'];
                // echo $refno;die;
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
            // echo $refno;
            // print_r($refno_array);die;
            foreach($refno_array as $row2)
            {
                // echo 1;
                $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status, rejected_remark from b2b_summary.pomain where refno = '$row2' and customer_guid = '".$_SESSION['customer_guid']."'");

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
               
                if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
                {
                    
                  echo "<script>window.close();</script>";
                }
                else
                {
                    // if($user_guid == '7BA14C79BDDB11EBB0C4000D3AA2838A')
                    // {
                    //     if(in_array('!SUPPMOV',$_SESSION['module_code']))
                    //     { 
                    //         $this->db->query("UPDATE b2b_summary.pomain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status = '' ");
                    //     }
                    // }

                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    {                    
                        $this->db->query("UPDATE b2b_summary.pomain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status IN ('','viewed') ");

                        $this->db->query("REPLACE into supplier_movement select 
                        upper(replace(uuid(),'-','')) as movement_guid
                        , '$customer_guid'
                        , '$user_guid'
                        , 'printed_po'
                        , '$from_module'
                        , '$row2'
                        , now()
                        ");
                    }
                    // redirect ($filename);
                }

            }
            $file = $filename; 
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
            header('Cache-Control: public, must-revalidate, max-age=0');
            // header("Content-Disposition: attachment; filename=\"".$Filename."\"");
            // header("Content-Length: ".filesize($Filename));
            ob_clean();
            flush();
            readfile($file);
            die;               
            // echo $filename;die;
            // redirect ($filename);
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

    public function direct_print_merge_post_method()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $this->session->userdata('user_logs') == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $this->input->post('trans');
            $loc = $this->input->post('loc');
            $pdf_name = $this->input->post('pdfname');
            //$refno = $_REQUEST['trans'];
            //$loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            //$pdf_name = $_REQUEST['pdfname'];
                // echo $refno;die;
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
            // echo $refno;
            // print_r($refno_array);die;
            foreach($refno_array as $row2)
            {
                // echo 1;
                $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status, rejected_remark from b2b_summary.pomain where refno = '$row2' and customer_guid = '".$_SESSION['customer_guid']."'");

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
               
                if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
                {
                    
                  echo "<script>window.close();</script>";
                }
                else
                {
                    if(!in_array('!SUPPMOV',$_SESSION['module_code']))
                    {                    
                        $this->db->query("UPDATE b2b_summary.pomain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$row2' and status IN ('','viewed') ");

                        $this->db->query("REPLACE into supplier_movement select 
                        upper(replace(uuid(),'-','')) as movement_guid
                        , '$customer_guid'
                        , '$user_guid'
                        , 'printed_po'
                        , '$from_module'
                        , '$row2'
                        , now()
                        ");
                    }
                    // redirect ($filename);
                }

            }
            $file = $filename; 
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
            header('Cache-Control: public, must-revalidate, max-age=0');
            // header("Content-Disposition: attachment; filename=\"".$Filename."\"");
            // header("Content-Length: ".filesize($Filename));
            ob_clean();
            flush();
            readfile($file);
            die;               
            // echo $filename;die;
            // redirect ($filename);
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
            $this->panda->get_uri();
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
            $this->panda->get_uri();
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

    public function bulk_accept()
    {
        $refno = $this->input->post('list_id');
        $customer_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid'];
        $from_module = $_SESSION['frommodule'];

        // echo var_dump($refno);

        foreach($refno as $row)
        {
            $this->db->query("REPLACE into supplier_movement select 
            upper(replace(uuid(),'-','')) as movement_guid
            , '$customer_guid'
            , '$user_guid'
            , 'accepted_po'
            , '$from_module'
            , '$row'
            , now()
            ");
            $this->db->query("UPDATE b2b_summary.pomain SET status = 'Accepted',b2b_status = 'readysend' WHERE refno = '$row' AND customer_guid='$customer_guid'");
            // echo $this->db->last_query();die;
        }

        if($this->db->affected_rows() > 0)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    public function preview_po_item_line()
    {
        $customer_guid = $this->session->userdata('customer_guid');
        $refno = $this->input->post('refno');
        // $ver = $this->session->userdata('redirect');
        $ver = 0;
        if($ver == 1)
        {
            $url = '127.0.0.1/PANDA_GITHUB/rest_b2b/index.php/';

            $to_shoot_url = $url."/Select/S_hq_branch_code";
        }
        else
        {
            $database1 = 'lite_b2b';
            $rest_link = $this->db->query("SELECT * FROM $database1.acc WHERE acc_guid = '$customer_guid'");
            // $url = '127.0.0.1/rest_api/index.php/';
            // $url = 'http://18.139.87.215/rest_api/index.php/return_json';
            $url = $rest_link->row('rest_url');

            $to_shoot_url = $url."/po_child_preview";
        }
        // echo $to_shoot_url;
        // die;
        
        // $block = $this->db->query("SELECT * FROM set_setting WHERE module_name = 'CONSIGNMENT' AND code = 'CONS' LIMIT 1");
        $data = array(
            'refno' => $refno,
        );

        $cuser_name = 'ADMIN';
        $cuser_pass = '1234';

        $ch = curl_init($to_shoot_url);
       // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "CODEX1234" ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
        curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        $output = json_decode($result);
        // $status = json_encode($output);
        // print_r($output->result);die;
        // echo $result;die;
        //close connection
        curl_close($ch);  
        // echo $output->status;
        // die;
        
        if($output->status == "true")
        {
            $po_item_line = $output->result;
        }
        else
        {
            $po_item_line = $output->result;
        }  

        $data = array(
            'po_item_line' => $po_item_line,
        );
        // print_r($data);die;
        echo json_encode($data);
    }
} // nothing after this
