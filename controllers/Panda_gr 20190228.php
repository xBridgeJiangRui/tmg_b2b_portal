<?php
class panda_gr extends CI_Controller
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

    public function get_username()
    {
        $username = $this->db->query("SELECT user_name FROM set_user  WHERE user_guid = '".$_SESSION['user_guid']."' GROUP BY user_guid")->row('user_name');
        return $username;
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

    public function index()
    {
         if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $setsession = array(
                'frommodule' => 'panda_gr',
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
                //unset($_SESSION['from_other']);
                if($_REQUEST['status'] == '')
                {
                    unset($_SESSION['from_other']);
                    redirect('panda_gr?loc='.$_REQUEST['loc']);
                };
                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=');
            }
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

    public function gr_child()
    {
         if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $refno = $_REQUEST['trans'];
            $loc = $_REQUEST['loc'];

            $grmain_status = $this->db->query("SELECT status from grmain where refno = '$refno'")->row('status');
            if($grmain_status != 'CONFIRM_EINV' )
            {
                //child data from rest
                $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
                $to_shoot_url = $check_url."/childdata?table=grchild"."&refno=".$refno;
                // echo $to_shoot_url;

           /*   $ping_url = explode("/", $check_url)[2];
                $result = array();
               
                $cmd_result = shell_exec("ping ". $ping_url);
               
                $result = explode(",",$cmd_result);
                
                $key=key($result);
                $val=$result[3];
             
                if($val  == " "){
                    
                    $ping_result= 'offline';
                }
                else
                {
                    $ping_result=  'online';
                } 
                echo $ping_result;die;
               */

                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                $response = curl_exec($ch);
                //echo var_dump($response);die;
                if($response !== false) {

                        $get_child_detail = json_decode(file_get_contents($to_shoot_url), true);
                        $child_result_validation = $get_child_detail[0]['line']; 
                }
                else
                {
                    $get_child_detail = array();
                    $child_result_validation = '0';
                    $this->session->set_flashdata('message', 'Connection fail at customer server.Generation of E Invoice is currently not available.');
                    //$this->session->set_flashdata('warning', 'Connection fail at customer server. Child Data Not Found.');
                    
                }
                // child data
                // echo var_dump($get_child_detail); 
            }
            else
            {
                echo 'status <> ""';
            } 
           
          
            $get_header_detail = $this->db->query("SELECT a.`customer_guid`
, a.`status`
, a.`RefNo`
, a.`Location`
, IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
, IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
, IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
, a.`GRDate`
, a.`Code`
, a.`Name`
, a.`consign`
, a.Total
, a.gst_tax_sum
, a.total_include_tax
FROM b2b_summary.grmain AS a 
LEFT JOIN lite_b2b.grmain_proposed AS b 
ON a.refno = b.refno 
AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '".$_SESSION['customer_guid']."'"); 
             
            $set_row = $this->db->query("SET @row=0");
            $get_DN_detail = $this->db->query("SELECT a.customer_guid, @row:=@row+1 AS rowx, IFNULL(b.ecn_guid, 'Pending') AS ecn_guid, IFNULL(b.status, 'Pending' ) AS ecn_status, IFNULL(b.type, 'Pending') AS ecn_type,   ext_doc1 ,  a.status, a.location, a.RefNo, a.VarianceAmt, a.Created_at, a.Created_by, a.Updated_at, a.Updated_by, a.hq_update, a.EXPORT_ACCOUNT, a.EXPORT_AT, a.EXPORT_BY, a.transtype, a.share_cost, a.gst_tax_sum, a.gst_adjust, a.gl_code, a.tax_invoice, a.ap_sup_code, a.refno2, a.rounding_adj, a.sup_cn_no, a.sup_cn_date, a.dncn_date, a.dncn_date_acc FROM b2b_summary.grmain_dncn AS a LEFT JOIN (SELECT * FROM lite_b2b.ecn_main WHERE customer_guid = '".$_SESSION['customer_guid']."' AND refno = '$refno' ) AS b ON a.refno = b.refno AND a.transtype = b.type WHERE a.refno = '$refno' AND a.customer_guid = '".$_SESSION['customer_guid']."' order by transtype asc"); 

            //echo $this->db->last_query();die;
            //$check_e_cn = $this->db->query("SELECT * from lite_b2b.ecn_main where customer_guid = '".$_SESSION['customer_guid']."' and refno = '$refno'");      
            
            $check_scode = $this->db->query("SELECT code from b2b_summary.grmain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
            
            //echo $replace_var;die;
            $file_headers = @get_headers($filename);


            $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'GRN' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");
            // check if einv has open
            $check_e_inv = $this->db->query("SELECT * from einv_main where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");

            if($check_e_inv->num_rows() == '1')
            {
                $open_panel2 = 'collapsed-box';
                $open_panel3 = TRUE;
                $version = $check_e_inv->row('revision');
                $check_e_inv_c = $this->db->query("SELECT * from einv_child where einv_guid = '".$check_e_inv->row('einv_guid')."'");
            }
            else
            {
                $open_panel2 = '';
                $open_panel3 = FALSE;
                $version = '0';
                $check_e_inv_c = '';
            };

            if(isset($_REQUEST['edit']))
            {
                $hidden_text = 'text';
                $edit_header_url = site_url('panda_gr/gr_child?trans='.$_REQUEST['trans'].'&loc='.$_REQUEST['loc']);
            }
            else
            {
                $hidden_text = 'hidden';
                $edit_header_url = site_url('panda_gr/gr_child?trans='.$_REQUEST['trans'].'&loc='.$_REQUEST['loc'].'&edit');
            }

            /*if($get_DN_detail->num_rows() == '1')
            {
                $show_generate_ecn = '2';
                $show_ecn = '1';
            }
            else
            {
                $show_generate_ecn = '1';
                $show_ecn = '0';
            };*/



            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Goods Received',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'check_header' => $get_header_detail,
                'child_result_validation' => $child_result_validation,
                'check_child' => $get_child_detail,
                'set_admin_code' => $set_admin_code,
                'open_panel2' => $open_panel2,
                'open_panel3' => $open_panel3,
                'version' => $version,
                'check_e_inv_c' => $check_e_inv_c,
                'get_DN_detail' => $get_DN_detail,
                'hidden_text' => $hidden_text,
                'edit_header_url' => $edit_header_url,
                /*'show_generate_ecn' => $show_generate_ecn,
                'show_ecn' => $show_ecn,*/
            );

            $this->load->view('header');       
            $this->load->view('gr/panda_gr_pdf',$data);
            $this->load->view('general_modal',$data);
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
            $user_guid = $_SESSION['userid'];
            $from_module = $_SESSION['frommodule'];

            $get_header_detail = $this->db->query("SELECT * from b2b_summary.grmain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");        
            
            $check_scode = $this->db->query("SELECT code from b2b_summary.grmain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');

            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

            $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
            $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');
 
            $file_headers = @get_headers($filename);

           $check_status = $this->db->query("SELECT refno, if(status = '', 'Pending', status) as status from b2b_summary.grmain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
            $set_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'GRN' order by reason asc");
            $set_admin_code = $this->db->query("SELECT code,reason from  set_setting where module_name = 'ADMIN' order by reason asc");

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Goods Received',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'set_admin_code' => $set_admin_code,
            );

            if(in_array('HTTP/1.1 404 Not Found', $file_headers ))
            {
              echo "<script>window.close();</script>";
            }
            else
            {
                $this->db->query("UPDATE b2b_summary.grmain set status = 'printed' where customer_guid ='$customer_guid' and refno = '$refno' and status = '' ");

                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'printed_grn'
                , '$from_module'
                , '$refno'
                , now()
                ");
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

    public function supplier_check()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();
            if($_SESSION['customer_guid'] == '' )
            {
                $this->session->set_flashdata('message', 'Session Expired! Please relogin');
                redirect('#');
            };

            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('H_refno');
            $loc = $this->input->post('location');
            
            $check_einv_header = $this->db->query("SELECT * from einv_main where refno = '$refno' and customer_guid = '$customer_guid'");
            if($check_einv_header->num_rows() == 0)
            {
                $H_refno = $this->input->post('H_refno');
                $H_invno = $this->input->post('H_invno');
                $H_dono = $this->input->post('H_dono');
                $H_inv_date = $this->input->post('H_docdate');
                $H_gr_date = $this->input->post('H_grdate');
                $H_total_excl_tax = $this->input->post('H_total_excl_tax');
                $H_tax_amount = $this->input->post('H_tax_amount');
                $H_total_incl_tax = $this->input->post('H_total_incl_tax');


                $data1 = array(
                    'einv_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    'customer_guid' => $customer_guid,
                    'refno' => $refno,
                    'invno'=> addslashes($H_invno),
                    'dono'=> addslashes($H_dono),
                    'inv_date'=> $H_inv_date,
                    'gr_date'=> $H_gr_date,
                   
                    'total_excl_tax' => $H_total_excl_tax,
                    'tax_amount'=> $H_tax_amount,
                    'total_incl_tax'=> $H_total_incl_tax,
                    'created_at'=> $this->db->query("select now() as naw")->row('naw'),
                    'created_by'=>  '',
                    'updated_at'=> $this->db->query("select now() as naw")->row('naw'),
                    'updated_by'=> '',
                );

                $this->db->insert('einv_main', $data1);

                $get_einv_guid = $this->db->query("SELECT einv_guid from einv_main where refno = '$refno' and customer_guid = '$customer_guid'")->row('einv_guid');
            }
            else
            {
                $query_data =  $this->db->query("SELECT * from einv_main where refno = '$refno' and customer_guid = '$customer_guid'");
                $revision = $query_data->row('revision')+1;
                $get_einv_guid = $query_data->row('einv_guid');

                $data_main = array(
                    'revision' => $revision,
                );

                $table = 'einv_main';
                $col_guid = 'einv_guid';
                $guid = $get_einv_guid;

                $this->General_model->update_data($table,$col_guid, $guid, $data_main);
                $query_child = $this->db->query("SELECT * from einv_child where einv_guid = '$get_einv_guid'");

                $table_archive = 'b2b_archive.einv_child';
                foreach($query_child->result() as $row)
                {
                    $data_archive  =  array(
                        'child_guid' =>  $row->child_guid,
                        'einv_guid' =>  $row->einv_guid,
                        'line' => $row->line,
                        'itemtype' => $row->itemtype,
                        'itemlink' => $row->itemlink,
                        'itemcode' => $row->itemcode,
                        'barcode' => $row->barcode,
                        'description' => $row->description,
                        'packsize' => $row->packsize,
                        'qty' => $row->qty,
                        'uom' => $row->uom,
                        'unit_price_before_disc' => $row->unit_price_before_disc,
                        'item_discount_description' => $row->item_discount_description,
                        'item_disc_amt' => $row->item_disc_amt,
                        'total_bill_disc_prorated' => $row->total_bill_disc_prorated,
                        'total_amt_excl_tax' => $row->total_amt_excl_tax,
                        'total_tax_amt' => $row->total_tax_amt,
                        'total_amt_incl_tax' => $row->total_amt_incl_tax,
                        'checked' => $row->checked,
                        'checked_at' =>  $row->checked_at,
                        'checked_by' => $row->checked_by,
                        'created_at' => $row->created_at,
                        'created_by' => $row->created_by,
                        'updated_at' => $row->updated_at,
                        'updated_by' => $row->updated_by,
                        'revision'=> $query_data->row('revision'),
                    );
                
                    $this->db->insert($table_archive, $data_archive);
                }
            }
            $this->db->query("DELETE FROM einv_child where einv_guid = '$get_einv_guid'");
            $itemcode = $this->input->post("itemcode[]");
            $supcheck = $this->input->post("supcheck2[]");
            $line = $this->input->post("line[]");
            $barcode = $this->input->post("barcode[]");
            $description = addslashes($this->input->post("description[]"));
            $packsize = $this->input->post("packsize[]");
            $qty = $this->input->post("qty[]");
            $uom = $this->input->post("um[]");
            $unitprice = $this->input->post("unitprice[]");
            $disc_desc = $this->input->post("disc_desc[]");
            $discamt = $this->input->post("discamt[]");
            $unit_disc_prorate = $this->input->post("unit_disc_prorate[]");
            $unit_price_bfr_tax = $this->input->post("unit_price_bfr_tax[]");
            $totalprice = $this->input->post("totalprice[]");
            $gst_tax_amount = $this->input->post("gst_tax_amount[]");
            $gst_unit_total = $this->input->post("gst_unit_total[]");
            
            foreach($line as $i => $id) 
            {
                $data[] =  [
                        'child_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                        'einv_guid' => $get_einv_guid,
                        'line' => $id,
                        'itemtype' => '',
                        'itemlink' => $itemcode[$i],
                        'itemcode' => $itemcode[$i],
                        'barcode' => $barcode[$i],
                        'description' => $description[$i],
                        'packsize' => $packsize[$i],
                        'qty' => $qty[$i],
                        'uom' => $uom[$i],
                        'unit_price_before_disc' => $unitprice[$i],
                        'item_discount_description' => $disc_desc[$i],
                        'item_disc_amt' => $discamt[$i],
                        'total_bill_disc_prorated' => $unit_disc_prorate[$i],
                        'total_amt_excl_tax' => $unit_price_bfr_tax[$i],
                        'total_tax_amt' => $gst_tax_amount[$i],
                        'total_amt_incl_tax' => $totalprice[$i],
                        'checked' => $supcheck[$i],
                        'checked_at' => $this->db->query("select now() as naw")->row('naw'),
                        'checked_by' => $this->get_username(),
                        'created_at' => $this->db->query("select now() as naw")->row('naw'),
                        'created_by' => $this->get_username(),
                        'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                        'updated_by' => $this->get_username(),
                    ];
            }
            
            $table = 'einv_child';
            $this->db->insert_batch($table, $data);
            redirect('panda_gr/gr_child?trans='.$refno.'&loc='.$loc);
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function edit_gr_header()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $_SESSION['customer_guid'];
            $header_refno = $this->input->post('header_refno');
            $header_loc = $this->input->post('header_loc');

            $ext_invno = $this->input->post('ext_invno[]');
            $ext_dono = $this->input->post('ext_dono[]');
            $ext_docdate = $this->input->post('ext_docdate[]');
            $line = $this->input->post('line[]');
            $ori_docdate = $this->input->post('docdate[]');

            foreach($line as $i => $id)
            {
                $check_exist = $this->db->query("SELECT * from lite_b2b.grmain_proposed where customer_guid = '$customer_guid' and refno = '$header_refno' ");
                //echo $this->db->last_query();die;
                if($check_exist->num_rows() > 0)
                {
                   $this->db->query("DELETE FROM lite_b2b.grmain_proposed where customer_guid = '$customer_guid' and refno = '$header_refno'");
                };

                if($ext_docdate[$i] == '' ||$ext_docdate[$i] == ' ')
                {
                    $ext_docdate = $ori_docdate;
                }

                $data1[] = [
                    'customer_guid' => $customer_guid, 
                    'status' => '',
                    'refno' => $header_refno,
                    'invno' => $ext_invno[$i],
                    'dono' => $ext_dono[$i],
                    'docdate' => $ext_docdate[$i],
                    'created_at' => $this->db->query("select now() as naw")->row('naw'),
                    'created_by' =>  $_SESSION['user_guid'],
                    'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                    'updated_by' => $_SESSION['user_guid'],
                ]; 

                
            }
            $table = 'lite_b2b.grmain_proposed';
            $this->db->insert_batch($table, $data1);
             
            $this->db->query("UPDATE lite_b2b.grmain_proposed AS a
            INNER JOIN b2b_summary.grmain AS b
            ON a.customer_guid = b.customer_guid
            AND a.refno = b.refno
            SET
            a.location = b.location
            , a.grdate = b.grdate
            , a.issuestamp = b.issuestamp
            , a.laststamp = b.laststamp
            , a.code = b.code
            , a.name = b.name
            where a.customer_guid = '$customer_guid' and a.refno = '$header_refno' and posted = '0'
             ");

            redirect('panda_gr/gr_child?trans='.$header_refno.'&loc='.$header_loc);


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
            $customer_guid = $_SESSION['customer_guid'];
            $refno = $this->input->post('ecn_refno[]');
            $type = $this->input->post('ecn_type[]');
            $ext_doc1 = $this->input->post('ext_doc1[]');
            $amount = $this->input->post('ecn_varianceamt[]');
            $tax_rate = $this->input->post('ecn_tax_rate[]');
            $tax_amount = $this->input->post('ecn_gst_tax_sum[]');
            $total_incl_tax = $this->input->post('ecn_total_incl_tax[]');
            $loc = $this->input->post('ecn_loc[]');
            $line = $this->input->post('ecn_rows[]');
            $gr_refno = $this->input->post('gr_refno');
            $gr_loc = $this->input->post('gr_loc');
 
            //echo var_dump($_SESSION);die;
            foreach($line as $i => $id) 
            {
                $check_exist = $this->db->query("SELECT * from lite_b2b.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
             
                if($check_exist->num_rows() > 0)
                {
                    $revision = $check_exist->row('revision') + 1;
                    $this->db->query("REPLACE INTO b2b_archive.ecn_main select * from lite_b2b.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
                    $this->db->query("DELETE FROM lite_b2b.ecn_main where customer_guid = '$customer_guid' and refno = '$refno[$i]' and type = '$type[$i]'");
                }
                else
                {
                    $revision = '0';
                }
 
                if(is_null($ext_doc1[$i]) || $ext_doc1[$i] == ' '|| $ext_doc1[$i] == '')
                {
                    unset($refno[$i]); unset($type[$i]);
                    //$this->session->set_flashdata('message', 'E-CN ext Doc is null');
                };

                //echo var_dump($ext_doc1[0]);die;
         
                $data1[] = [
                    'customer_guid' => $customer_guid, 
                    'ecn_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                    'status' => '',
                    'refno' => $refno[$i],
                    'type' => $type[$i],
                    'ext_doc1' => str_replace(' ', '',$ext_doc1[$i]),
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

            $table = 'einv_child';
            $this->db->insert_batch('lite_b2b.ecn_main', $data1);
            
            $this->db->query("DELETE FROM ecn_main where refno is null and type is null and customer_guid = '".$_SESSION['customer_guid']."'");

            redirect('panda_gr/gr_child?trans='.$gr_refno.'&loc='.$gr_loc);


        }
        else
        {
           $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#'); 
        }
    }


    public function gr_child_old()
    {
        if($this->session->userdata('loginuser') == true)
        {
            //$session_data = $this->session->userdata('customers');
            $grmain = $this->db->query("SELECT refno, grdate, docdate, dono, invno, location, tax_code_purchase,gst_tax_rate,total, gst_tax_sum, total_include_tax, status from lite_b2b.grmain where refno = '".$_REQUEST['trans']."'");

            foreach($grmain->result() as $row)
            {
                $data2  = array(
                  'refno' => $row->refno,
                  'grdate' => $row->grdate,
                  'docdate' => $row->docdate,
                  'dono' => $row->dono,
                  'invno' => $row->invno,
                  'location' => $row->location,
                  'tax_code_purchase' => $row->tax_code_purchase,
                  'gst_tax_rate' => $row->gst_tax_rate,
                  'total' => $row->total,
                  'gst_tax_sum' => $row->gst_tax_sum,
                  'total_include_tax' => $row->total_include_tax,
                  'status' => $row->status
                    );
                $this->session->set_userdata($data2);
            }
            

            $data = array (
                'grmain' => $this->db->query("SELECT refno, grdate, docdate, dono, invno, location, tax_code_purchase,gst_tax_rate,total, gst_tax_sum, total_include_tax, status from lite_b2b.grmain where refno = '".$_REQUEST['trans']."' "),
                'grchild' => $this->db->query("SELECT  * from lite_b2b.grchild where refno = '".$_REQUEST['trans']."' order by line asc "),
                'child_reject' => $this->db->query("SELECT reason from lite_b2b.set_setting where module_name = 'GR'"),
                );
             $this->load->view('header');
            /*$this->load->view('panda_menu_view.php');*/
            $this->load->view('gr/panda_gr_child',$data);
            $this->load->view('gr/panda_gr_modal', $data);
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function confirm()
    {
        if($this->session->userdata('loginuser') == true)
        {
            $refno = $_REQUEST['trans'];

            $check_cur_rec = $this->db->query("SELECT status from lite_b2b.grmain where refno = '$refno'");
            if($check_cur_rec->row('status') == 'Pending')
            {
                

                $table = "lite_b2b.grmain";
                $data = array (
                 'status' => 'GR Confirmed',
                    );
                $this->GR_model->update_accepted($table,$data);
                $this->session->set_flashdata('message', 'Document Confirmed.');
                redirect('panda_gr/gr_child?trans='.$_REQUEST['trans']);
            }
            else
            {
                $this->session->set_flashdata('warning', 'Document status is not Pending. Please make sure GR status is Pending before making any changes.');
                redirect('panda_gr/gr_child?trans='.$_REQUEST['trans']);
            }
        }
        else
        {
            redirect('#');
        }
    }

    public function check_accept()
    {
        if($this->session->userdata('loginuser') == true)
        {  
            $session_data = $this->session->userdata('loginuser');
            $user_guid = $data['user_guid'] = $session_data['user_guid'];
            $session_data = $this->session->userdata('branch');
            $branch_code = $data['branch_code'] = $session_data['branch_code'];
            $session_data = $this->session->userdata('customers');
            $customer = $data['customer'] = $session_data['customer'];
            $customer_guid = $data['customer_guid'] = $session_data['customer_guid'];

            $_SESSION['refno'] = $this->input->post("refno");

            $reason = $this->input->post('reason[]');
            $line = $this->input->post('line[]');
            $itemcode = $this->input->post('itemcode');
    
                $grchild = array();
                    foreach($line as $row => $id)
                    {
                         $grchild[] = [
                            'line' => $id, 
                            'reason' => $reason[$row],
                            ];
                    }

         
                $table= $session_data['customer_db'].".grmain"; 
                $dbchild= $session_data['customer_db'].".grchild"; 

                $check_status = $this->db->query("SELECT status from $table where refno  = '".$_SESSION['refno']."'");
                /*echo var_dump($_SESSION);
                echo $this->db->last_query();die;*/
                if ($check_status->row('status') == 'Pending')
                {

               $data = array (
                 'status' => 'Accepted',
                    );
                $this->GR_model->update_accepted($table,$data);
                $this->db->where_in('refno', $_SESSION['refno']);
                $this->db->update_batch($dbchild, $grchild, 'line');
                // echo $this->db->last_query();die;

                    $check_child = $this->db->query("SELECT REPLACE(GROUP_CONCAT(reason), ',', '')  as reason from $dbchild where refno = '".$_SESSION['refno']."' group by refno");
                    if ($check_child->row('reason') != '' )
                    {
                        $p_accepted = array (
                            'status' => 'Partially Accepted',
                        );
                        $this->Po_model->update_accepted($table,$p_accepted);
                       // echo $this->db->last_query();die;
                        $this->session->set_flashdata('message', 'GR is Partially Accepted.');
                        redirect('panda_gr/gr_child?trans='.$_SESSION['refno']);
                    }
                    else
                    {
                        $this->session->set_flashdata('message', 'GR Accepted.');
                        redirect('panda_gr/gr_child?trans='.$_SESSION['refno']);
                    };
                //echo $this->db->last_query();die;
            }
            else
            {
                $this->session->set_flashdata('message', 'Document status is not Pending. Please make sure GR status is Pending before making any changes.');
                redirect('panda_gr/gr_child?trans='.$_SESSION['refno']);
            };

        }   
        else
        {
            redirect('#');
        }
    }











    //************************************************************************************
    //    OLD FUNCTIONS BELOW THIS POINT     888888888888888888888888888888888888888888888
    //************************************************************************************
    //pagination settings
    public function index2()
    {

        //************************************************************************************
        //     BEGIN# STANDARD     88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************
        //pagination settings
        $config['base_url'] = site_url('Panda_gr/index');
        $config['total_rows'] = $this->GR_model->countGR();
        $config['per_page'] = "10";
        $config["uri_segment"] = 3;
        $choice = 10; //$config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = true;
        $config['last_link'] = true;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        //************************************************************************************
        //     END# STANDARD       88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************


        //Begin# Define Field Type and Title using multi-array
        $DataType = array();

        $DataType[0]['title'] = 'GR refno';         $DataType[0]['align'] = 'left';
        $DataType[0]['fieldtype'] = 'string';       $DataType[0]['decimal'] = 0;

        $DataType[1]['title'] = 'GR Date';          $DataType[1]['align'] = 'left';
        $DataType[1]['fieldtype'] = 'string';       $DataType[1]['decimal'] = 0;

        $DataType[2]['title'] = 'Invoice Date';    $DataType[2]['align'] = 'left';
        $DataType[2]['fieldtype'] = 'string';       $DataType[2]['decimal'] = 0;

        $DataType[3]['title'] = 'Doc. No';    $DataType[3]['align'] = 'left';
        $DataType[3]['fieldtype'] = 'string';        $DataType[3]['decimal'] = 0;

        $DataType[4]['title'] = 'Inv. No';          $DataType[4]['align'] = 'left';
        $DataType[4]['fieldtype'] = 'string';        $DataType[4]['decimal'] = 0;

        $DataType[5]['title'] = 'Location';          $DataType[5]['align'] = 'center';
        $DataType[5]['fieldtype'] = 'string';        $DataType[5]['decimal'] = 0;

        $DataType[6]['title'] = 'Tax Code';          $DataType[6]['align'] = 'center';
        $DataType[6]['fieldtype'] = 'string';        $DataType[6]['decimal'] = 0;

        $DataType[7]['title'] = 'Tax Rate';   $DataType[7]['align'] = 'center';
        $DataType[7]['fieldtype'] = 'number';        $DataType[7]['decimal'] = 2;

        $DataType[8]['title'] = 'Total (exc tax)';               $DataType[8]['align'] = 'left';
        $DataType[8]['fieldtype'] = 'number';        $DataType[8]['decimal'] = 2;

        $DataType[9]['title'] = 'GST';   $DataType[9]['align'] = 'center';
        $DataType[9]['fieldtype'] = 'number';        $DataType[9]['decimal'] = 2;

        $DataType[10]['title'] = 'Total (inc tax)';           $DataType[10]['align'] = 'center';
        $DataType[10]['fieldtype'] = 'string';       $DataType[10]['decimal'] = 2;

        $DataType[11]['title'] = 'Status';           $DataType[11]['align'] = 'center';
        $DataType[11]['fieldtype'] = 'string';       $DataType[11]['decimal'] = 2;


        $data['DataType'] = $DataType;
        //End# Define Field Type and Title using multi-array
    
        //Begin# Define View,Edit,Delete button 
        $ButtonLink = array();
        $ButtonLink[0]['Href']="index.php/panda_gr/gr_details/";
        $ButtonLink[0]['FieldNo']=0;

        $data['ButtonLink']=$ButtonLink;
        //End# Define View,Edit,Delete button 
        
        //$ButtonAddVisible = false;        
        //$data['ButtonAddVisible'] = $ButtonAddVisible;        
        $ButtonViewVisible = true;
        $data['ButtonViewVisible'] = $ButtonViewVisible;
        $ButtonEditVisible = false;
        $data['ButtonEditVisible'] = $ButtonEditVisible;
        $ButtonDeleteVisible = false;        
        $data['ButtonDeleteVisible'] = $ButtonDeleteVisible;
        $ButtonDownloadVisible = false;        
        $data['ButtonDownloadVisible'] = $ButtonDownloadVisible;
        $ButtonBackVisible = true;        
        $data['ButtonBackVisible'] = $ButtonBackVisible;         


        //capture customer/branch selected
        $session_data = $this->session->userdata('loginuser');
        $data['user_guid'] = $session_data['user_guid'];
        $session_data = $this->session->userdata('branch');
        $data['branch_code'] = $session_data['branch_code'];
        $session_data = $this->session->userdata('customers');
        $data['customer'] = $session_data['customer'];

        //Destroyed Session
        $this->session->unset_userdata('po_status');

        //call the model function to get the department data
        $getQueryResult = $this->GR_model->get_gr_list(
            'select a.RefNo,a.grdate,a.docdate,a.dono,a.invno,a.Location,a.tax_code_purchase,a.gst_tax_rate,a.Total,
             a.gst_tax_sum,a.total_include_tax,a.status FROM grmain a 
             INNER JOIN customer_profile b 
             ON a.customer_guid = b.customer_guid 
             INNER JOIN customer_supcus c
             ON b.customer_guid = c.customer_guid AND a.code = c.code
             INNER JOIN user_customer d
             ON c.supcus_guid = d.supcus_guid
             WHERE ibt = 0 AND a.loc_group = "'.$data['branch_code'].'" AND b.customer_name = "'.$data['customer'].'"
             AND d.user_guid = "'.$data['user_guid'].'"
             order by GrDate desc',$config["per_page"], $data['page']); 
        
        //Break the array to get the 2nd level array size
        $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[0]));
        $flat_arr = iterator_to_array($recur_flat_arr_obj, false);
        $ColumnCount = count($flat_arr);

        //Begin# Create 2 Dimensional Array so that it will not depend on query field name
        $DataArray = array();

        for ($i = 0; $i < count($getQueryResult); ++$i)
        {
            $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[$i]));
            $flat_arr = iterator_to_array($recur_flat_arr_obj, false);
            
            
                for ($j = 0; $j < $ColumnCount; ++$j)
                {
                    $DataArray[$i][] = $flat_arr[$j];
                }
            
        }
        //End# Create 2 Dimensional Array

        $data['GridTitle'] = 'Goods Receipt';
        $data['DataArray'] = $DataArray;
        $data['ColumnCount'] = $ColumnCount;
        $data['pagination'] = $this->pagination->create_links();
        //$data['get_company'] = "Panda Software House Sdn Bhd";
        $data['home_page'] = site_url('panda_search/gr_branch_list');

        //load the department_view
        $this->load->view('header');
        $this->load->view('panda_menu_view.php');
        $this->load->view('panda_gr_list_view',$data);
        $this->load->view('footer');
    }

    public function gr_details()
    {
        
        $grrefno = $this->uri->segment(3);
    
        $grmain = $this->GR_model->get_gr_details(
            'select refno,grdate,docdate,dono,invno,location,tax_code_purchase,gst_tax_rate,total,
             gst_tax_sum,total_include_tax,status from grmain
             where refno = "'.$grrefno.'"'); 

            
        foreach($grmain as $row)
        {

            $data  = array(
              'refno' => $row->refno,
              'grdate' => $row->grdate,
              'docdate' => $row->docdate,
              'dono' => $row->dono,
              'invno' => $row->invno,
              'location' => $row->location,
              'tax_code_purchase' => $row->tax_code_purchase,
              'gst_tax_rate' => $row->gst_tax_rate,
              'total' => $row->total,
              'gst_tax_sum' => $row->gst_tax_sum,
              'total_include_tax' => $row->total_include_tax,
              'status' => $row->status

        );
        }$this->session->set_userdata('gr_detail', $data);

        redirect('Panda_gr/gr_item_details'); 


    }


    public function gr_item_details()
    {
       
        //retrieve session data
        $session_data = $this->session->userdata('gr_detail');
        $refno = $session_data['refno'];
        $grdate = $session_data['grdate'];
        $docdate = $session_data['docdate'];
        $dono = $session_data['dono'];
        $invno = $session_data['invno'];
        $location = $session_data['location'];
        $tax_code_purchase = $session_data['tax_code_purchase'];
        $gst_tax_rate = $session_data['gst_tax_rate'];
        $total = $session_data['total'];
        $gst_tax_sum = $session_data['gst_tax_sum'];
        $total_include_tax = $session_data['total_include_tax'];
        $status = $session_data['status'];

        $session_data = $this->session->userdata('branch_detail');
        $branch_name = $session_data['branch_name'];

        //define error message display on view
        if($this->session->userdata('gr_status'))
        {
            $session_data = $this->session->userdata('gr_status');
            $query = $session_data['query'];
        }
        else
        {
            $query = '';
        }
        $data['query'] = $query;  


        //************************************************************************************
        //     BEGIN# STANDARD     88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************
        //pagination settings
        $config['base_url'] = site_url('Panda_gr/gr_item_details');
        $config['total_rows'] = $this->GR_model->count_grchild();
        $config['per_page'] = "10";
        $config["uri_segment"] = 3;
        $choice = 10; //$config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = true;
        $config['last_link'] = true;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        //************************************************************************************
        //     END# STANDARD       88888888888888888888888888888888888888888888888888888888888
        //************************************************************************************


        //Begin# Define Field Type and Title using multi-array
        $DataType = array();

        $DataType[0]['title'] = 'Price Type';       $DataType[0]['align'] = 'left';
        $DataType[0]['fieldtype'] = 'string';       $DataType[0]['decimal'] = 0;

        $DataType[1]['title'] = 'Itemcode';         $DataType[1]['align'] = 'left';
        $DataType[1]['fieldtype'] = 'string';       $DataType[1]['decimal'] = 0;

        $DataType[2]['title'] = 'Barcode';          $DataType[2]['align'] = 'left';
        $DataType[2]['fieldtype'] = 'string';       $DataType[2]['decimal'] = 0;

        $DataType[3]['title'] = 'Description';      $DataType[3]['align'] = 'left';
        $DataType[3]['fieldtype'] = 'string';       $DataType[3]['decimal'] = 0;

        $DataType[4]['title'] = 'Qty';              $DataType[4]['align'] = 'center';
        $DataType[4]['fieldtype'] = 'string';       $DataType[4]['decimal'] = 1;

        $DataType[5]['title'] = 'Net Unit Price';               $DataType[5]['align'] = 'center';
        $DataType[5]['fieldtype'] = 'number';                   $DataType[5]['decimal'] = 4;

        $DataType[6]['title'] = 'GR Total Amount(Inc tax)';     $DataType[6]['align'] = 'center';
        $DataType[6]['fieldtype'] = 'number';                   $DataType[6]['decimal'] = 4;

        $DataType[7]['title'] = 'Invoice QTY';                  $DataType[7]['align'] = 'center';
        $DataType[7]['fieldtype'] = 'number';                   $DataType[7]['decimal'] = 0;

        $DataType[8]['title'] = 'Invoice Unit Price';           $DataType[8]['align'] = 'center';
        $DataType[8]['fieldtype'] = 'number';                   $DataType[8]['decimal'] = 4;

        $DataType[9]['title'] = 'Invoice Total Amount(Inc tax)';      $DataType[9]['align'] = 'center';
        $DataType[9]['fieldtype'] = 'number';                         $DataType[9]['decimal'] = 2;

        $DataType[10]['title'] = 'Total Tax Amount';            $DataType[10]['align'] = 'center';
        $DataType[10]['fieldtype'] = 'number';                  $DataType[10]['decimal'] = 4;

        $DataType[11]['title'] = 'Amend QTY';            $DataType[11]['align'] = 'center';
        $DataType[11]['fieldtype'] = 'label';                  $DataType[11]['decimal'] = 0;

        $DataType[12]['title'] = 'Amend Unit Price';            $DataType[12]['align'] = 'center';
        $DataType[12]['fieldtype'] = 'label';                  $DataType[12]['decimal'] = 0;

        $DataType[13]['title'] = 'Amend Total Amount';            $DataType[13]['align'] = 'center';
        $DataType[13]['fieldtype'] = 'label';                  $DataType[13]['decimal'] = 0;


        $data['DataType'] = $DataType;
        //End# Define Field Type and Title using multi-array

        //Begin# Define View,Edit,Delete button 
        $ButtonLink = array();
        $ButtonLink[0]['Href']="index.php/panda_gr/grchild_details/";
        $ButtonLink[0]['FieldNo']=1;

        $data['ButtonLink']=$ButtonLink;
        //End# Define View,Edit,Delete button 
        
    
        $ButtonEditVisible = true;
        $data['ButtonEditVisible'] = $ButtonEditVisible;      



         $getQueryResult = $this->GR_model->get_gr_list(
            'select pricetype,itemcode,barcode,description,
            qty, netunitprice, 
            ROUND(((totalprice-(hcost_gr))+gst_tax_amount),2) AS totalprice,
            IF(pay_by_invoice=1=1,inv_qty,0) AS inv_qty,
            IF(pay_by_invoice=1,inv_netunitprice,0) AS inv_netunitprice,
            IF(pay_by_invoice=1,invacttotcost,0) AS invacttotcost,gst_tax_amount,sup_qty,sup_netunitprice,sup_totalprice
            FROM grmain a inner join grchild b on a.refno = b.refno
            where b.RefNo = "'.$refno.'" order by line',$config["per_page"], $data['page']); 
        

        //Break the array to get the 2nd level array size
        $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[0]));
        $flat_arr = iterator_to_array($recur_flat_arr_obj, false);
        $ColumnCount = count($flat_arr);

        //Begin# Create 2 Dimensional Array so that it will not depend on query field name
        $DataArray = array();

        for ($i = 0; $i < count($getQueryResult); ++$i)
        {
            $recur_flat_arr_obj =  new RecursiveIteratorIterator(new RecursiveArrayIterator($getQueryResult[$i]));
            $flat_arr = iterator_to_array($recur_flat_arr_obj, false);
            
            
                for ($j = 0; $j < $ColumnCount; ++$j)
                {
                    $DataArray[$i][] = $flat_arr[$j];
                }
            
        }
        //End# Create 2 Dimensional Array


        $data['refno'] = $refno ;
        $data['grdate'] = $grdate ;
        $data['docdate'] = $docdate ;
        $data['dono'] = $dono ;
        $data['invno'] = $invno ;
        $data['location'] = $location ;
        $data['tax_code_purchase'] = $tax_code_purchase ;
        $data['gst_tax_rate'] = $gst_tax_rate ;
        $data['total'] = $total ;
        $data['gst_tax_sum'] = $gst_tax_sum ;
        $data['total_include_tax'] = $total_include_tax ;
        $data['status'] = $status ;
        $data['branch_name'] = $branch_name ;
        

        $data['DataArray'] = $DataArray;
        $data['ColumnCount'] = $ColumnCount;
        $data['pagination'] = $this->pagination->create_links();
        //$data['get_company'] = "Panda Software House Sdn Bhd";

        $this->session->set_userdata('referred_from', current_url());
        $this->load->view('header');
        $this->load->view('panda_menu_view.php');
        $this->load->view('panda_grchild_view',$data);
        $this->load->view('footer');
    }


    public function grchild_details()
    {
        
        $itemcode = $this->uri->segment(3);
    
        $grchild = $this->GR_model->get_gr_details(
            'select itemcode,qty,netunitprice,totalprice,inv_qty,inv_netunitprice,invacttotcost,sup_qty,sup_netunitprice,
             sup_totalprice from grchild
             where refno = "'.$itemcode.'"'); 

            
        foreach($grchild as $row)
        {

            $data  = array(
              'itemcode' => $row->itemcode,
              'qty' => $row->qty,
              'netunitprice' => $row->netunitprice,
              'totalprice' => $row->totalprice,
              'inv_qty' => $row->inv_qty,
              'inv_netunitprice' => $row->inv_netunitprice,
              'invacttotcost' => $row->invacttotcost,
              'sup_qty' => $row->sup_qty,
              'sup_netunitprice' => $row->sup_netunitprice,
              'sup_totalprice' => $row->sup_totalprice

        );
        }$this->session->set_userdata('grchild_detail', $data);

        redirect('Panda_gr/grchild_amend'); 


    }


    function grchild_amend()
    {
        $session_data = $this->session->userdata('grchild_detail');
        $itemcode = $session_data['itemcode'];
        $qty = $session_data['qty'];
        $netunitprice = $session_data['netunitprice'];
        $totalprice = $session_data['totalprice'];
        $inv_qty = $session_data['inv_qty'];
        $inv_netunitprice = $session_data['inv_netunitprice'];
        $invacttotcost = $session_data['invacttotcost'];
        $sup_qty = $session_data['sup_qty'];
        $sup_netunitprice = $session_data['sup_netunitprice'];
        $sup_totalprice = $session_data['sup_totalprice'];



    }


    function accept_gr()
    {

        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result  = $this->Po_model->get_po_details('select status from grmain where refno = "'.$refno.'"
                                                    and status = "Pending"');

        if($result)
        {
            $query=$this->Po_model->accept_po();
            $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
            
            redirect($_SERVER['HTTP_REFERER'],'refresh');

        }
        else
        {
            $query=$this->Po_model->accept_po();
            $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
            
            redirect($_SERVER['HTTP_REFERER'],'refresh');

        }
    }


    function download_gr()
    {

        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result  = $this->Po_model->get_po_details('select status from pomain where refno = "'.$refno.'"
                                                    and status = "Pending"');

        $result2  = $this->Po_model->get_po_details('select status from pomain where refno = "'.$refno.'"
                                                    and status = "Completed"');

        if($result)
        {

            $data = array("query" => 'Access Denied! Please proceed Accept/Reject PO before Download PO');
            $this->session->set_userdata('po_status', $data);

            redirect($_SERVER['HTTP_REFERER'],'refresh');
        }
        else
        {
            if($result2)
            {
                $query=$this->Po_model->download_po();

                if($query == 1)
                {
                    redirect('Panda_po/index','refresh');
                }
                else
                {
                    $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
                    redirect($_SERVER['HTTP_REFERER'],'refresh');
                }

            }
                
            else
            {
                $query=$this->Po_model->download_po();

                if($query == 1)
                {
                    redirect('Panda_po/index','refresh');
                }
                else
                {
                    $data = array("query" => $query);$this->session->set_userdata('po_status', $data);
                    redirect($_SERVER['HTTP_REFERER'],'refresh');
                }
            } 
        }

    }



}
?>