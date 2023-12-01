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

                 redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc']);
            }
            else
            {
                //unset($_SESSION['from_other']);
                if($_REQUEST['status'] == '')
                {
                    unset($_SESSION['from_other']);
                    redirect('panda_gr?loc='.$_REQUEST['loc']);
                };
                redirect('general/view_status?status='.$_REQUEST['status'].'&loc='.$_REQUEST['loc']);
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

            $get_header_detail = $this->db->query("SELECT * from b2b_summary.grmain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");        
            
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

            $data = array(
                'filename' => $filename,
                'file_headers' => $file_headers,
                'virtual_path' => $virtual_path,
                'title' => 'Goods Received',
                'check_status' => $check_status,
                'set_code' => $set_code,
                'check_header' => $get_header_detail,
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