<?php
class panda_pci extends CI_Controller
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
         $this->load->model('General_model');
    }

    public function index()
    {
         if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
             $setsession = array(
                'frommodule' => 'panda_pci',
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
                    redirect('panda_pci?loc='.$_REQUEST['loc']);
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

    public function index_old()
    {
         if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $setsession = array(
                'frommodule' => 'panda_pci',
                );
            $this->session->set_userdata($setsession);

            if($_REQUEST['loc'] == '')
            {   
                redirect('login_c/location');
            };


            if($_SESSION['user_group_name'] != 'SUPER_ADMIN' &&  $_REQUEST['loc'] != 'HQ')
            {
                $result = $this->db->query("SELECT customer_guid, inv_refno , promo_refno, loc_group, docdate, sup_code as code, total_bf_tax gst_value, total_af_tax 
FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '".$_SESSION['customer_guid']."' and loc_group = '".$_REQUEST['loc']."' and sup_code IN (".$_SESSION['query_supcode'].")");
            };

            if($_SESSION['user_group_name'] != 'SUPER_ADMIN' &&  $_REQUEST['loc'] == 'HQ')
            {
                $result = $this->db->query("SELECT customer_guid, inv_refno , promo_refno, loc_group, docdate, sup_code as code, total_bf_tax gst_value, total_af_tax 
FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '".$_SESSION['customer_guid']."'  and sup_code IN (".$_SESSION['query_supcode'].")");
            };

            if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
            {
                $result = $this->db->query("SELECT customer_guid, inv_refno , promo_refno, loc_group, docdate, sup_code as code, total_bf_tax gst_value, total_af_tax 
FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '".$_SESSION['customer_guid']."' and loc_group = '".$_REQUEST['loc']."' ");
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
        $this->load->view('pci/panda_pci_list_view',$data);
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

    public function pci_child()
    {
         if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            $customer_guid = $this->session->userdata('customer_guid');

            
            
            if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862' && $this->session->userdata('customer_guid') != 'D361F8521E1211EAAD7CC8CBB8CC0C93' && $this->session->userdata('customer_guid') != '403810171FA711EA9BB8E4E7491C3E1E' && $this->session->userdata('customer_guid') != 'B00CA0BE403611EBA2FC000D3AC8DFD7' && $this->session->userdata('customer_guid') != '3C475C473DB311EBB4F2AEF59F86279D')
            {
                //print_r('server checking1'); die;
                $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
            }
            else
            {
                //print_r('server checking2'); die;
                $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
            }
            $check_scode = str_replace("/","+-+",$check_scode);

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');
            // echo $customer_guid;die;

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';
            // echo $filename;die;

            $file_headers = @get_headers($filename);

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Promo Tax Invoice',
            );

            $customer_guid = $_SESSION['customer_guid'];        
            $user_guid = $_SESSION['user_guid'];        
            $from_module = $_SESSION['frommodule'];

            if(!in_array('!PCISUPPMOV',$_SESSION['module_code']))
            {
                if( $customer_guid == '1F90F5EF90DF11EA818B000D3AA2CAA9' ||  $customer_guid == '907FAFE053F011EB8099063B6ABE2862' ||  $customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93' || $customer_guid == '403810171FA711EA9BB8E4E7491C3E1E' || $customer_guid == 'B00CA0BE403611EBA2FC000D3AC8DFD7' || $customer_guid == '3C475C473DB311EBB4F2AEF59F86279D')
                {
                    $this->db->query("UPDATE b2b_summary.promo_taxinv SET status = 'viewed' where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' AND status = ''");
                }
                else
                {
                    $this->db->query("UPDATE b2b_summary.promo_taxinv SET status = 'viewed' where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' AND status = ''");
                }
                    
                    $this->db->query("REPLACE into supplier_movement select         
                    upper(replace(uuid(),'-','')) as movement_guid      
                    , '$customer_guid'      
                    , '$user_guid'      
                    , 'viewed_PCI'        
                    , '$from_module'        
                    , '$refno'      
                    , now()     
                    ");     
                    // redirect ($filename);       
            }


            $this->load->view('header');       
            $this->load->view('pci/panda_pci_pdf',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

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

            if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862' && $this->session->userdata('customer_guid') != 'D361F8521E1211EAAD7CC8CBB8CC0C93' && $customer_guid != '403810171FA711EA9BB8E4E7491C3E1E' && $customer_guid != 'B00CA0BE403611EBA2FC000D3AC8DFD7' && $customer_guid != '3C475C473DB311EBB4F2AEF59F86279D')
            {
                $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
            }
            else
            {
                $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
            }
            $check_scode = str_replace("/","+-+",$check_scode);

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
            $file_config_final_path = $this->file_config_b2b->file_path($customer_guid,'web','general_doc','main_path','GDMP');

            $filename = $file_config_final_path.'/'.$replace_var.'.pdf';
            $file_headers = @get_headers($filename);

            // echo $filename;die;
            // if(!file_exists($filename))
            // {
            //     // echo 1;die;
            //     echo "<script>alert('Document Not Found, Please Contact Support');</script>";
            //     echo "<script>window.close();</script>";die;
            // }
                
            // OLD
            if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
            {
                
              echo "<script>window.close();</script>";
            }
            else
            {
                if(!in_array('!PCISUPPMOV',$_SESSION['module_code']))
                {
                    if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862' && $this->session->userdata('customer_guid') != 'D361F8521E1211EAAD7CC8CBB8CC0C93' || $this->session->userdata('customer_guid') != '403810171FA711EA9BB8E4E7491C3E1E' || $this->session->userdata('customer_guid') != 'B00CA0BE403611EBA2FC000D3AC8DFD7' || $this->session->userdata('customer_guid') != '3C475C473DB311EBB4F2AEF59F86279D')
                    {
                        // $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
                        $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and promo_refno = '$refno' and status IN ('','viewed') ");
                    }
                    else
                    {
                        // $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
                        $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and inv_refno = '$refno' and status IN ('','viewed') ");
                    }

                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'printed_PCI'
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
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
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
               
                if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
                {
                    
                  echo "<script>window.close();</script>";
                }
                else
                {
                    if(!in_array('!PCISUPPMOV',$_SESSION['module_code']))
                    {
                        // $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and promo_refno = '$row2' and status IN ('','viewed') ");
                        if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862' && $this->session->userdata('customer_guid') != 'D361F8521E1211EAAD7CC8CBB8CC0C93' && $this->session->userdata('customer_guid') != '403810171FA711EA9BB8E4E7491C3E1E' && $this->session->userdata('customer_guid') != 'B00CA0BE403611EBA2FC000D3AC8DFD7' && $this->session->userdata('customer_guid') != '3C475C473DB311EBB4F2AEF59F86279D')
                        {
                            // $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
                            $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and promo_refno = '$row2' and status IN ('','viewed') ");
                        }
                        else
                        {
                            // $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
                            $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and inv_refno = '$row2' and status IN ('','viewed') ");
                        }                        

                        $this->db->query("REPLACE into supplier_movement select 
                        upper(replace(uuid(),'-','')) as movement_guid
                        , '$customer_guid'
                        , '$user_guid'
                        , 'printed_PCI'
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

    public function direct_print_merge_post_method()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            $refno = $this->input->post('trans');
            $loc = $this->input->post('loc');
            $pdf_name = $this->input->post('pdfname');
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
               
                if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
                {
                    
                  echo "<script>window.close();</script>";
                }
                else
                {
                    if(!in_array('!PCISUPPMOV',$_SESSION['module_code']))
                    {
                        // $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and promo_refno = '$row2' and status IN ('','viewed') ");
                        if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862' && $this->session->userdata('customer_guid') != 'D361F8521E1211EAAD7CC8CBB8CC0C93' && $this->session->userdata('customer_guid') != '403810171FA711EA9BB8E4E7491C3E1E' && $this->session->userdata('customer_guid') != 'B00CA0BE403611EBA2FC000D3AC8DFD7' && $this->session->userdata('customer_guid') != '3C475C473DB311EBB4F2AEF59F86279D')
                        {
                            // $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where promo_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
                            $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and promo_refno = '$row2' and status IN ('','viewed') ");
                        }
                        else
                        {
                            // $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where inv_refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
                            $this->db->query("UPDATE b2b_summary.promo_taxinv set status = 'printed' where customer_guid ='$customer_guid' and inv_refno = '$row2' and status IN ('','viewed') ");
                        }                        

                        $this->db->query("REPLACE into supplier_movement select 
                        upper(replace(uuid(),'-','')) as movement_guid
                        , '$customer_guid'
                        , '$user_guid'
                        , 'printed_PCI'
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

}
?>
