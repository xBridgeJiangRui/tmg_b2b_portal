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

            if($_SESSION['user_group_name'] == 'SUPER_ADMIN' || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN' ||$_SESSION['user_group_name'] == 'CUSTOMER_CLERK' )
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
            


            if($_SESSION['user_group_name'] != 'SUPER_ADMIN' && $_REQUEST['loc'] != 'HQ')
            {
                $result = $this->db->query("SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."' and code IN (".$_SESSION['query_supcode'].")
                    UNION ALL
                    SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and location = '".$_REQUEST['loc']."' and code IN (".$_SESSION['query_supcode'].")");
            };

            if($_SESSION['user_group_name'] != 'SUPER_ADMIN' && $_REQUEST['loc'] == 'HQ')
            {
                $result = $this->db->query("SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."'   and code IN (".$_SESSION['query_supcode'].")
                    UNION ALL
                    SELECT customer_guid, type, refno, location, docno, docdate, code, amount, gst_tax_sum   FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '".$_SESSION['customer_guid']."' and code IN (".$_SESSION['query_supcode'].")");
            };

            if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
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
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];
            
            $check_scode = $this->db->query("SELECT code from b2b_summary.dbnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');

            $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdncn'");
            //due to session data is from return collection direct click from there..

            $set_row = $this->db->query("SET @row=0");
            /*$get_DN_detail =  $this->db->query("SELECT @row:=@row+1 AS rowx, dbnotemain.* from b2b_summary.dbnotemain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."' and type = 'DEBIT'");*/
            $get_DN_detail = $this->db->query("SELECT @row := @row + 1 AS rowx, a.customer_guid, a.status, a.Type, a.RefNo, a.Location, a.DocNo, a.DocDate, a.IssueStamp, a.LastStamp, a.PONo, a.SCType, a.Code, a.Name, a.Term, a.Issuedby, a.Remark, a.BillStatus, a.AccStatus, a.DueDate, a.Amount, a.Closed, a.SubDeptCode, a.postby, a.postdatetime, a.Consign, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.hq_update, a.locgroup, a.ibt, a.SubTotal1, a.Discount1, a.Discount1Type, a.SubTotal2, a.Discount2, a.Discount2Type, a.gst_tax_sum, a.tax_code_purchase, IF(b.ext_doc1 IS NULL, a.sup_cn_no, b.ext_doc1 ) AS sup_cn_no, IF(b.ext_date1 IS NULL, a.sup_cn_date, b.ext_date1) AS sup_cn_date, a.doc_name_reg, a.gst_tax_rate, a.multi_tax_code, a.refno2, a.surchg_tax_sum, a.gst_adj, a.rounding_adj, a.unpostby, a.unpostdatetime, a.ibt_gst, a.acc_posting_date, a.RoundAdjNeed FROM b2b_summary.dbnotemain AS a LEFT JOIN (SELECT * FROM ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' AND `type` = 'PRDNCN') AS b ON a.refno = b.refno WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' AND a.type = 'DEBIT' ");

            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = 'panda_prdncn'")->row('query');
            //echo $this->db->last_query();die;
            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
            //echo $filename;die;
            $file_headers = @get_headers($filename);

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'PR DN/CN',
                'sup_cn_header' => $get_DN_detail,
            );

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


    public function generate_ecn()
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


            //echo $ext_doc1;die;
            //latest for retrieve invoice number
            $req_refno = $_REQUEST['refno'];
            $transtype = $_REQUEST['transtype'];
            $invoice_number = $this->db->query("SELECT invno FROM einv_main WHERE refno = '$req_refno'  ")->row('invno');

            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
            $to_shoot_url = $check_url."/childdata?table=dbnotemain"."&refno=".$req_refno."&transtype=DEBIT";
                // echo $to_shoot_url 
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
            //echo var_dump($get_child_dncn) ;die;


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
            redirect('panda_prdncn/prdncn_child?trans='.$req_refno.'&loc='.$current_loc);
           // $this->db->insert_batch('ecn_child', $data_ecn_child);
            /*$get_ecn_child_data = $this->db->query("SELECT b.* from lite_b2b.ecn_main as a inner join lite_b2b.ecn_child as b on a.refno = b.refno where a.customer_guid = '$customer_guid' and a.refno = '$req_refno' and transtype = '$transtype'");*/
            $get_ecn_child_data = $this->db->query("SELECT  * FROM  lite_b2b.ecn_child  WHERE customer_guid = '$customer_guid'  AND refno = '$req_refno'  AND transtype = '$transtype' ");

             
            $invoice_number = $_REQUEST['refno'].'_'.$_REQUEST['transtype'];
           // echo  $_REQUEST['refno'];die;
            $gr_info = $this->db->query("SELECT 
            a.`Location`
            , a.`Code`
            , a.`Name`
            , ifnull(b.invno,a.`Invno`) as Invno
            FROM b2b_summary.grmain AS a 
            LEFT JOIN lite_b2b.grmain_proposed AS b 
            ON a.refno = b.refno 
            AND a.customer_guid = b.customer_guid where a.refno = '$req_refno' 
            and a.customer_guid = '$customer_guid'");

            $data = array  (
                'query_data' =>  $this->db->query("SELECT a.refno ,a.status , a.type , a.ext_doc1 , a.ext_date1, a.amount , a.`tax_rate` , a.`tax_amount` , a.`total_incl_tax` , a.posted , b.refno_dn , b.transtype , b.location , b.itemcode , b.barcode , b.description , b.qty , b.inv_qty , b.inv_netunitprice , b.supplier , b.invno , b.dono , b.porefno , b.title2 , b.notes , b.pounitprice , b.invactcost , b.netunitprice , b.pototal , b.articleno , b.packsize , b.variance_amt , b.reason , b.tax_amount , b.total_gross FROM lite_b2b.ecn_main AS a INNER JOIN lite_b2b.ecn_child AS b ON a.refno = b.refno AND a.type = b.`transtype` WHERE a.customer_guid = '$customer_guid' AND a.refno = '$req_refno' AND a.type = '$transtype'"),
                'supcus_supplier' => $this->db->query("SELECT * FROM b2b_summary.supcus WHERE Code = '".$gr_info->row('Code')."' and customer_guid = '$customer_guid'"),
                'supcus_customer' => $this->db->query("SELECT * from b2b_summary.supcus where code = '".$gr_info->row('Location')."' and customer_guid = '$customer_guid'"),
                'customer_branch_info' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '".$gr_info->row('Location')."'   and customer_guid = '$customer_guid'"),
            );

            
            $load_pdf = $this->load->view('gr/panda_ecn_pdf', $data, true);
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
            $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'invoice/B2B_'.$invoice_number.'.pdf', 'F');           
            
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

 



}
?>