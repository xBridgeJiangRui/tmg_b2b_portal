<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class general extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->library('Panda_PHPMailer');   
        //$this->load->library('PDFMerger');   
        $this->load->model('General_model');
        /*$this->load->library('myfpdf');   
        $this->load->library('mytcpdf');   
        $this->load->library('myfpdi'); */  
    }

    public function view_status()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $check_loc = $_REQUEST['loc'];
            $check_module = $_SESSION['frommodule'];

            $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

            $hq_branch_code_array=array();

            foreach ($hq_branch_code as $key) {

                array_push($hq_branch_code_array,$key->branch_code);
            }

            if(in_array('VHFSP',$_SESSION['module_code']))
            {
                $hide_url_code = '<a class="btn btn-app" href="'.site_url('PO_hide/view_status').'?status=HFSP&loc='.$_REQUEST['loc'].'&p_f=&p_t=&e_f=&e_t=&r_n=" style="color:#ffad33"><i class="fa fa-edit"></i>View Hide</a>';
            }
            else
            {
                $hide_url_code = '';
            }


            if($check_module == 'panda_po_2') //  check module = panda_po_2
            {
                $data = array(
                    'datatable_url' => site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'accepted' => site_url('general/view_status?status=accepted&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'rejected' => site_url('general/view_status?status=rejected&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'other' => site_url('general/view_status?status=other&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'set_admin_code' => $this->db->query("SELECT code,portal_description as reason from status_setting where type = 'hide_po_filter' AND isactive = 1 order by portal_description asc"),
                    'set_code' => $this->db->query("SELECT code,reason from  set_setting where module_name = 'PO' order by reason asc"),
                    'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PO_FILTER_STATUS' order by code='ALL' desc, code asc"),
                    'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                    'location' => $this->db->query("SELECT DISTINCT branch_code 
FROM acc_branch AS a
INNER JOIN acc_concept AS b
ON b.`concept_guid` = a.`concept_guid`
 WHERE branch_code IN  (".$_SESSION['query_loc'].") and b.`acc_guid` = '".$_SESSION['customer_guid']."' order by branch_code asc "),
                    'location_description' => $this->db->query("SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '$check_loc' and customer_guid = '".$_SESSION['customer_guid']."'"),
                    'hide_url' => $hide_url_code
                );  
            };

            if($check_module == 'panda_gr')
            {
                $data = array (
                    'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'GR_FILTER_STATUS' order by code='ALL' desc, code asc"),
                    'datatable_url' =>site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'confirmed' => site_url('general/view_status?status=confirmed&loc='.$_REQUEST['loc'].'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                        );

            };

            if($check_module == 'panda_grda')
            {
                 $data = array (
                    'datatable_url' => site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'grda_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'GRDA_FILTER_DOCTYPE' order by code='' desc, code asc"),
                    'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                 );
            };

            if($check_module == 'panda_prdncn')
            {
                 $data = array (
                    'datatable_url' => site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'filter_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'PRDNCN_FILTER_STATUS' order by code='' desc, code asc"),
                    'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                 );
            };

            if($check_module == 'panda_pdncn')
            {
                 $data = array (
                    'datatable_url' => site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                 );
            };

            if($check_module == 'panda_pci')
            {
                 $data = array (
                    'datatable_url' =>site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                 );
            };

            if($check_module == 'panda_di')
            {
                 $data = array (
                    'datatable_url' => site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                 );
            };

            /*return via batch 20190409*/
            if($check_module == 'panda_return_collection')
            {
                $data = array(
                    'datatable_url' => site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'RC_FILTER_STATUS' order by code='ALL' desc, code asc"),
                    'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
                );
            }

            if($check_module == 'panda_gr_download')
            {
                $data = array(
                    'po_status' => $this->db->query("SELECT code, reason from set_setting where module_name = 'GR_FILTER_STATUS' order by code='ALL' desc, code asc"),
                    'datatable_url' =>site_url('general/view_table?status='.$_REQUEST['status'].'&loc='.$check_loc.'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'confirmed' => site_url('general/view_status?status=confirmed&loc='.$_REQUEST['loc'].'&p_f='.$_REQUEST['p_f'].'&p_t='.$_REQUEST['p_t'].'&e_f='.$_REQUEST['e_f'].'&e_t='.$_REQUEST['e_t'].'&r_n='.$_REQUEST['r_n']),
                    'period_code' => $this->db->query("SELECT period_code from lite_b2b.period_code"),
   
                );
            }

            $data['check_loc'] = $check_loc;

            $data['hq_branch_code_array'] = $hq_branch_code_array;
        
            $this->General_model->load_page($data, $check_module);

            // end grda
 
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function view_table()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $check_module = $_SESSION['frommodule'];
            $check_status = $_REQUEST['status'];
            $check_loc = $_REQUEST['loc'];

            //po_filter
            $po_from = $_REQUEST['p_f'];
            $po_to = $_REQUEST['p_t'];
            $exp_from = $_REQUEST['e_f'];
            $exp_to = $_REQUEST['e_t'];
            $refno = $_REQUEST['r_n'];

            //filter_via_periodcode cz i lazy add in parameter so and due date too soon 2019-08-13
            if(isset($_SESSION['filter_period_code']) &&  $_SESSION['filter_period_code'] != "" )
            {
                $period_code = $_SESSION['filter_period_code'];

                if($check_module == 'panda_po_2')
                {
                    $query_period_code = " and left(podate,7)  = '$period_code'";
                };
                if($check_module == 'panda_gr')
                {
                    $query_period_code = " and left(grdate,7)  = '$period_code'";
                };
                if($check_module == 'panda_grda')
                {
                    $query_period_code = " and left(sup_cn_date,7)  = '$period_code'";
                };
                if($check_module == 'panda_return_collection')
                {
                    $query_period_code = " and left(doc_date,7)  = '$period_code'";
                };
                if($check_module == 'panda_prdncn')
                {
                    $query_period_code = " and left(docdate, 7) = '$period_code'";
                }
                if($check_module == 'panda_gr_download')
                {
                    $query_period_code = " and left(docdate, 7) = '$period_code'";
                }
               
            }
            else
            {
                //$_SESSION['filter_period_code'] = "";
                $period_code = " "; 
                $query_period_code = " ";
            };


            // PO date range option
            if($po_from != '')
            {
                if($check_module == 'panda_po_2')
                {
                    $query_doc_from_to = " and podate between '$po_from' and '$po_to'";    
                };
                if($check_module == 'panda_gr')
                {
                    $query_doc_from_to = " and grdate between '$po_from' and '$po_to'";    
                };
                if($check_module == 'panda_grda')
                {
                    $query_doc_from_to = " and sup_cn_date between '$po_from' and '$po_to'";    
                };
                if($check_module == 'panda_return_collection')
                {
                    $query_doc_from_to = " and doc_date between '$po_from' and '$po_to'";
                };
                if($check_module == 'panda_prdncn')
                {
                    $query_doc_from_to = " and docdate between '$po_from' and '$po_to'";
                };

                if($check_module == 'panda_gr_download')
                {
                    $query_doc_from_to = " and grdate between '$po_from' and '$po_to'";    
                };

                
            }
            else
            {
                $query_doc_from_to = "";
            };

            // refno == 
            if($refno == '')
            {
                $query_refno = "";
            }
            elseif($refno == 'ALL')
            {
                $query_refno = "";
            }
            else
            {
                if($check_module == 'panda_gr' || $check_module == 'panda_grda' || $check_module == 'panda_gr_download' )
                {
                    $query_refno = " and a.refno = '$refno'";
                }
                else if($check_module == 'panda_return_collection')
                {
                    $query_refno = " and a.batch_no = '$refno'";
                }
                else
                {
                    $query_refno = " and refno = '$refno'";
                }
            };

            // exp date range option 
            if($exp_to != '')
            {
                if($check_module == 'panda_po_2')
                {
                    $query_exp_from_to = " and expiry_date between '$exp_from' and '$exp_to'";
                };
                if($check_module == 'panda_gr')
                {
                    $query_exp_from_to = " and docdate between '$exp_from' and '$exp_to'";
                };
                if($check_module == 'panda_return_collection')
                {
                    $query_exp_from_to = " and expiry_date between '$exp_from' and '$exp_to'";
                };
                if($check_module == 'panda_gr_download')
                {
                    $query_exp_from_to = " and docdate between '$exp_from' and '$exp_to'";
                };

                
            }
            else
            {
                $query_exp_from_to = "";
            };

            //status option
            if($_REQUEST['status'] == '')
            {
                $check_status = '';
            }
            else
            {
                $check_status = $_REQUEST['status'];
            };

            // if($check_status != '')
            // {
                $setsession = array(
                // 'from_other' => '1',
                'check_status' => $check_status,
                );
                $this->session->set_userdata($setsession);    
            // };

            $q_doc_from_to = $query_doc_from_to;    
            $q_exp_from_to = $query_exp_from_to;
            $q_refno = $query_refno; 
            $q_period_code = $query_period_code;

            if($check_module == 'panda_po_2')
            {
                    $columns = array( 
                            0 => 'refno', 
                            1 => 'gr_refno',
                            2 => 'loc_group',
                            3 => 'scode',
                            4 => 'sname',
                            5=> 'podate',
                            6=> 'delivery_date',
                            7=> 'expiry_date',
                            8=> 'total',
                            9=> 'gst_tax_sum',
                            10=> 'total_include_tax',
                            11=> 'status',
                            12=> 'rejected_remark',
                          /* 8=> 'checkbox',*/ 
                            13=> 'button',
                            14 => 'box'
                        );   
                    //for filtering data
                    // doing it thisway because i wanna  force if empty = view printed and '';
                    // if u have better idea, please help :)
                    if($check_status == '')
                    {
                        $check_status = "''";
                    }
                    elseif($check_status == 'ALL')
                    {
                        $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'PO_FILTER_STATUS'");

                        foreach($get_stat->result() as  $row)
                        {
                           $check_stat[] = $row->code;
                        }
                        
                        foreach ($check_stat as &$value)
                        {
                            $value = "'" . trim($value) . "'";
                        }
                        $check_status = implode(',', array_filter($check_stat));
                    }
                    else
                    {
                        $check_status = "'".$check_status."'";
                    }
            };

            if($check_module == 'panda_gr')
            {
                    $columns = array( 
                            0=> 'refno', 
                            1=>'grda_status',
                            2=> 'loc_group',
                            3=> 'code',
                            4=> 'name',
                            5=> 'grdate',
                            6=> 'docdate',
                            7=> 'dono',
                            8=> 'invno',
                            9=> 'e_invno',
                            10=> 'cross_ref',
                            11=> 'total',
                            12=> 'gst_tax_sum',
                            13=> 'total_include_tax',
                            14=> 'status',
                            15=> 'button',
                            16=> 'box',
                        );
                    if($check_status == '')
                    {
                        $check_status = "''";
                    }
                    elseif($check_status == 'ALL')
                    {
                        $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'GR_FILTER_STATUS'");

                         foreach($get_stat->result() as  $row)
                        {
                           $check_stat[] = $row->code;
                        }
                        
                        foreach ($check_stat as &$value)
                        {
                            $value = "'" . trim($value) . "'";
                        }
                        $check_status = implode(',', array_filter($check_stat));
                    }
                    else
                    {
                        $check_status = "'".$check_status."'";
                    }
            };

            if($check_module == 'panda_grda')
            {
                    $columns = array( 
                            0=> 'refno',
                            1=> 'loc_group',
                            2=> 'transtype',
                            3=> 'code',
                            4=> 'name',
                            5=> 'sup_cn_no',
                            6=> 'sup_cn_date',
                            7=> 'dncn_date',
                            8=> 'varianceamt',
                            9=> 'status',
                            10=> 'button',
                            11=> 'box',
                        );

                    if($check_status == '')
                    {
                        $check_status = "'','GQV','GRV', 'IAV', 'GDS'";
                    }
                    elseif($check_status == 'ALL')
                    {
                        $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'GRDA_FILTER_DOCTYPE'");

                        foreach($get_stat->result() as  $row)
                        {
                           $check_stat[] = $row->code;
                        }
                        
                        foreach ($check_stat as &$value)
                        {
                            $value = "'" . trim($value) . "'";
                        }
                        $check_status = implode(',', array_filter($check_stat));
                    }
                    else
                    {
                        $check_status = "'".$check_status."'";
                    }
            };

            if($check_module == 'panda_prdncn')
            {
                if($check_status == '')
                    {
                        $check_status = "'','DEBIT','CN'";
                    }
                    elseif($check_status == 'ALL')
                    {
                        $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'PRDNCN_FILTER_STATUS'");

                        foreach($get_stat->result() as  $row)
                        {
                           $check_stat[] = $row->code;
                        }
                        
                        foreach ($check_stat as &$value)
                        {
                            $value = "'" . trim($value) . "'";
                        }
                        $check_status = implode(',', array_filter($check_stat));
                    }                      
                     else
                    {
                        $check_status = "'".$check_status."'";
                    }
                    $columns = array( 
                            0=> 'refno',
                            1=> 'locgroup',
                            2=> 'type',
                            3=> 'code',
                            4=> 'name',
                            5=> 'docdate',
                            //6=> 'docno', edit
                            6=> 'amount',
                            7=> 'gst_tax_sum',
                            8=> 'total_incl_tax',
                            9=>'stock_collected',
                            10=>'date_collected',
                            11=> 'status',
                            12=> 'button',
                            13=> 'box',
                        );
            };

            if($check_module == 'panda_pdncn')
            {
                    $columns = array( 
                            0=> 'refno',
                            1=> 'loc_group',
                            2=> 'code',
                            3=> 'name',
                            4=> 'trans_type',
                            5=> 'docno',
                            6=> 'docdate',
                            7=> 'amount',
                            8=> 'gst_tax_sum',
                            9=> 'amount_include_tax',
                            10=> 'status',
                            11=> 'button',
                            12=> 'box',
                        );
            };

            if($check_module == 'panda_pci')
            {
                    $columns = array( 
                            0=> 'inv_refno',
                            1=> 'promo_refno',
                            2=> 'loc_group',
                            3=> 'sup_code',
                            4=> 'sup_name',
                            5=> 'docdate',
                            6=> 'total_bf_tax',
                            7=> 'gst_value',
                            8=> 'total_af_tax',
                            9=> 'status',
                            10=> 'button',
                            11=> 'box',
                        );
            };

            if($check_module == 'panda_di')
            {
                    $columns = array( 
                            0=>'inv_refno',
                            1=>'refno',
                            2=>'loc_group',
                            3=>'sup_code',
                            4=>'sup_name',
                            5=>'docdate',
                            6=>'datedue',
                            7=>'total_net',
                            8=> 'status',
                            9=>'button',
                            10=> 'box',
                        );
            };

            if($check_module == 'panda_return_collection')
            {
                if($check_status == '')
                    {
                        $check_status = "'','-1','0','1','2','3','4'"; // edit '4'
                    }
                    $columns = array( 
                            0=> 'batch_no',
                            1=> 'location',
                            2=> 'prdn_refno',
                            3=> 'doc_date',
                            4=> 'expiry_date',
                            5=> 'sup_code',
                            6=> 'sup_name',
                            7=> 'status',
                            8=> 'canceled',
                            9=> 'accepted_by',
                            10=> 'button',
                        );
            };

            if($check_module == 'panda_gr_download')
            {
                    $columns = array( 
                            0=> 'refno', 
                            1=>'grda_status',
                            2=> 'loc_group',
                            3=> 'code',
                            4=> 'name',
                            5=> 'grdate',
                            6=> 'docdate',
                            7=> 'dono',
                            8=> 'invno',
                            9=> 'e_invno',
                            10=> 'cross_ref',
                            11=> 'total',
                            12=> 'gst_tax_sum',
                            13=> 'total_include_tax',
                            14=> 'status',
                            15=> 'button',
                            16=> 'box',
                        );
                    if($check_status == '')
                    {
                        $check_status = "''";
                    }
                    elseif($check_status == 'ALL')
                    {
                        $get_stat = $this->db->query("SELECT code from set_setting where module_name = 'GR_FILTER_STATUS'");

                         foreach($get_stat->result() as  $row)
                        {
                           $check_stat[] = $row->code;
                        }
                        
                        foreach ($check_stat as &$value)
                        {
                            $value = "'" . trim($value) . "'";
                        }
                        $check_status = implode(',', array_filter($check_stat));
                    }
                    else
                    {
                        $check_status = "'".$check_status."'";
                    }
            };

 
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        

        //HUGH START FROM view_status()
        $check_module = $_SESSION['frommodule'];
        //$check_status = $_REQUEST['status'];
        $check_loc = $_REQUEST['loc'];

        $hq_branch_code = $this->db->query("SELECT branch_code FROM acc_branch WHERE is_hq = '1'")->result();

        $hq_branch_code_array=array();

        foreach ($hq_branch_code as $key) {

            array_push($hq_branch_code_array,$key->branch_code);
        }

        if(in_array($check_loc, $hq_branch_code_array)) 
        {
            $loc = $_SESSION['query_loc'];
        }
        else
        {
            $loc = "'".$_REQUEST['loc']."'";
        }
            
        // HUGH END FROM view_status()

        //$totalData = $this->General_model->check_module_query($check_status,$loc, $check_module,$q_doc_from_to, $q_exp_from_to, $q_refno , $q_period_code)->num_rows();
        //$totalFiltered = $totalData; 
        if(empty($this->input->post('search')['value']))
        {    
            $totalData = $this->General_model->check_module_query($check_status,$loc, $check_module,$q_doc_from_to,$q_exp_from_to, $q_refno , $q_period_code,'')->row('count');
            $totalFiltered = $totalData; 
        }
        else
        {
            $search = addslashes($this->input->post('search')['value']); 
            $totalData = $this->General_model->check_module_query($check_status,$loc, $check_module,$q_doc_from_to,$q_exp_from_to, $q_refno , $q_period_code,$search)->row('count');
            $totalFiltered = $totalData; 
            // echo $this->db->last_query();
        }
    
        if(empty($this->input->post('search')['value']))
        {        
            
            $posts = $this->General_model->check_module_result($limit,$start,$check_status,$q_doc_from_to, $q_exp_from_to, $q_refno , $q_period_code,$loc,$check_module,$dir,$order );
           // echo $this->db->last_query();die;
        }
        else 
        {

            $search = addslashes($this->input->post('search')['value']); 

            $posts =  $this->General_model->posts_search($limit,$start,$check_status,$loc,$check_module,$dir,$order,$search,$q_doc_from_to, $q_exp_from_to, $q_refno , $q_period_code );

            $totalFiltered =   $totalData; 
        }

        $data = array();
        if(!empty($posts))
        {
            //print_r($posts) ;die;
           // echo var_dump($posts);die;

            foreach ($posts as $post)
            {
                if($check_module == 'panda_po_2')
                {
                    
                    if(in_array('VPOCITM',$_SESSION['module_code']))
                    {
                        $nestedData['refno'] = '<span style="display:flex;">'.$post->refno.'<i data-toggle="tooltip" data-placement="top" title="Click to preview item details" class="fa fa-info-circle" style="padding-top:5px;padding-left:10px;cursor: pointer;"  id="preview_po_item_line" refno='.$post->refno.'></i></span>';
                    }
                    else
                    {
                        $nestedData['refno'] = $post->refno;
                    }
                    $nestedData['gr_refno'] = $post->gr_refno;
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['code'] = $post->scode;
                    $nestedData['name'] = $post->sname;
                    $nestedData['podate'] = $post->podate;
                    $nestedData['delivery_date'] = $post->delivery_date;
                    $nestedData['expiry_date'] = $post->expiry_date;
                    $nestedData['total'] = "<span class='pull-right'>".number_format($post->total,2)."</span>";
                    $nestedData['gst_tax_sum'] = "<span class='pull-right'>".number_format($post->gst_tax_sum,2)."</span>";
                    $nestedData['total_include_tax'] = "<span class='pull-right'>".number_format($post->total_include_tax,2)."</span>";
                    $nestedData['status'] = $post->status;
                    $nestedData['rejected_remark'] = $post->rejected_remarks;
 
                    if(in_array('HFSP',$_SESSION['module_code']) && $_REQUEST['status']=='' && $this->session->userdata('customer_guid') != '8D5B38E931FA11E79E7E33210BD612D3')
                    {
                         $nestedData['button'] = "<a href=".site_url('panda_po_2/po_child')."?trans=".$post->refno."&loc=".$_REQUEST['loc']." style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>

                         <a onclick='hide_modal()' role='button' class='btn-sm btn-danger'  data-toggle='modal' data-target='#otherstatus' style='float:left'
                          data-refno='".$post->refno."' data-loc='".$_REQUEST['loc']."''><span class='glyphicon glyphicon-eye-open'></span></a>

                          ";
                    }
                    else
                    {
                        $nestedData['button'] = "<a href=".site_url('panda_po_2/po_child')."?trans=".$post->refno."&loc=".$_REQUEST['loc']."&accpt_po_status=".$post->status." style='float:left' class='btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>
                        ";
                    }
                    $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'">';

                    
                };

                if($check_module == 'panda_gr')
                {
                    $nestedData['refno'] = $post->refno;
                    $nestedData['grda_status'] = "<a href=".site_url('panda_grda/grda_child?trans='.$post->grda_status.'&loc='.$_REQUEST['loc']).">".$post->grda_status."</a>";
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['code'] = $post->code;
                    $nestedData['name'] = $post->name;
                    $nestedData['grdate'] = $post->grdate;
                    $nestedData['docdate'] = $post->docdate;
                    $nestedData['dono'] = $post->dono;
                    $nestedData['invno'] = $post->invno;
                    $nestedData['e_invno'] = $post->einvno;
                    $nestedData['cross_ref'] = $post->cross_ref;
                    $nestedData['total'] = "<span class='pull-right'>".number_format($post->total,2)."</span>";
                    $nestedData['gst_tax_sum'] = "<span class='pull-right'>".number_format($post->gst_tax_sum,2)."</span>";
                    $nestedData['total_include_tax'] = "<span class='pull-right'>".number_format($post->total_include_tax,2)."</span>";
                    $nestedData['status'] = $post->status;
                    $nestedData['button'] = "<a href=".site_url('panda_gr/gr_child')."?trans=".$post->refno."&loc=".$_REQUEST['loc']."&accpt_gr_status=".$post->status." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                     $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'">';
                };

                if($check_module == 'panda_grda')
                {
                    $nestedData['refno'] = $post->refno;
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['transtype'] = $post->transtype;
                    $nestedData['code'] = $post->code;
                    $nestedData['name'] = $post->name;
                    $nestedData['sup_cn_no'] = $post->sup_cn_no;
                    $nestedData['sup_cn_date'] = $post->sup_cn_date;
                    $nestedData['dncn_date'] = $post->dncn_date;
                    $nestedData['varianceamt'] = "<span class='pull-right'>".number_format($post->varianceamt,2)."</span>";
                    $nestedData['status'] = $post->status;
                    $nestedData['button'] = "<a href=".site_url('panda_grda/grda_child')."?trans=".$post->refno."&loc=".$_REQUEST['loc']." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>"; 
                    $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'">';
                    //$nestedData['button'] = "";
                };

                if($check_module == 'panda_prdncn')
                {
                    $nestedData['refno'] = $post->refno;
                    $nestedData['locgroup'] = $post->locgroup;
                    $nestedData['type'] = $post->type;
                    $nestedData['code'] = $post->code;
                    $nestedData['name'] = $post->name;
                    $nestedData['docno'] = $post->docno;
                    $nestedData['docdate'] = $post->docdate;
                    $nestedData['amount'] = "<span class='pull-right'>".number_format($post->amount,2)."</span>";
                    $nestedData['gst_tax_sum'] = "<span class='pull-right'>".number_format($post->gst_tax_sum,2)."</span>";
                    $nestedData['total_incl_tax'] = "<span class='pull-right'>".number_format($post->total_incl_tax,2)."</span>";
                    $nestedData['status'] = $post->status;
                    // $nestedData['stock_collected'] = $post->stock_collected==1?'stock collected':'stock not collected';
                    // $nestedData['date_collected'] = $post->date_collected;
                    if($post->stock_collected == '0' || $post->stock_collected == 0)
                    {
                        $nestedData['stock_collected'] = $post->stock_collected==1?'-':'-';
                        $nestedData['date_collected'] = '-';                     

                    }
                    else
                    {
                        $nestedData['stock_collected'] = $post->stock_collected==1?'stock collected':'stock not collected';
                        $nestedData['date_collected'] = $post->date_collected;                        
                    }
                    $nestedData['button'] = "<a href=".site_url('panda_prdncn/prdncn_child')."?trans=".$post->refno."&loc=".$_REQUEST['loc']."&type=".$post->type." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>"; 
                    $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'" dncn="'.$post->type.'">';
                    //$nestedData['button'] = "";//
                };


                if($check_module == 'panda_pdncn')
                {
                    $nestedData['refno'] = $post->refno;
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['code'] = $post->CODE;
                    $nestedData['name'] = $post->name;                    
                    $nestedData['trans_type'] = $post->trans_type;
                    $nestedData['docno'] = $post->docno;
                    $nestedData['docdate'] = $post->docdate;
                    $nestedData['amount'] = "<span class='pull-right'>".number_format($post->amount,2)."</span>";
                    $nestedData['gst_tax_sum'] = "<span class='pull-right'>".number_format($post->gst_tax_sum,2)."</span>";
                    $nestedData['amount_include_tax'] = "<span class='pull-right'>".number_format($post->amount_include_tax,2)."</span>";
                    $nestedData['status'] = $post->status;
                    $nestedData['button'] = "<a href=".site_url('panda_pdncn/pdncn_child')."?trans=".$post->refno."&loc=".$_REQUEST['loc']." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>"; 
                    $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'">';
                    //$nestedData['button'] = "";
                };


                if($check_module == 'panda_pci')
                {
                    $nestedData['inv_refno'] = $post->inv_refno;
                    $nestedData['promo_refno'] = $post->promo_refno;
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['docdate'] = $post->docdate;
                    $nestedData['sup_code'] = $post->sup_code;
                    $nestedData['sup_name'] = $post->sup_name;
                    $nestedData['total_bf_tax'] ="<span class='pull-right'>".number_format($post->total_bf_tax,2)."</span>";
                    $nestedData['gst_value'] = "<span class='pull-right'>".number_format($post->gst_value,2)."</span>";
                    $nestedData['total_af_tax'] = "<span class='pull-right'>".number_format($post->total_af_tax,2)."</span>";
                    $nestedData['status'] = $post->status; 
                    if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862')
                    {
                        $nestedData['button'] = "<a href=".site_url('panda_pci/pci_child')."?trans=".$post->promo_refno."&loc=".$_REQUEST['loc']." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>"; 
                        $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->promo_refno.'">';
                    }
                    else
                    {
                        $nestedData['button'] = "<a href=".site_url('panda_pci/pci_child')."?trans=".$post->inv_refno."&loc=".$_REQUEST['loc']." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>"; 
                        $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->inv_refno.'">';
                    }
                    //$nestedData['button'] = "";
                };

                if($check_module == 'panda_di')
                {
                    $nestedData['inv_refno'] = $post->inv_refno;
                    $nestedData['refno'] = $post->refno; // new
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['sup_code'] = $post->sup_code;
                    $nestedData['sup_name'] = $post->sup_name;
                    $nestedData['docdate'] = $post->docdate;
                    $nestedData['datedue'] = $post->datedue;
                    $nestedData['total_net'] = "<span class='pull-right'>".number_format($post->total_net,2)."</span>";
                    $nestedData['status'] = $post->status; 
                    $nestedData['button'] = "<a href=".site_url('panda_di/pdi_child')."?trans=".$post->inv_refno."&loc=".$_REQUEST['loc']." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>"; 
                    $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->inv_refno.'">';
                    //$nestedData['button'] = "";
                };

                if($check_module == 'panda_return_collection')
                {
                    /*if($post->status == 0)    // havent accept             
                    {*/
                        $datefrom = date_create(date('Y-m-d'));
                        $dateto = date_create($post->expiry_date);    
                   /* }
                    elseif($post->status == 1) //accepted havent collect
                    {

                        $datetocheck =  $post->accepted_at;   
                        $datefrom = date_create($post->accepted_at);   
                        $variable = $this->db->query("SELECT RB_total_days_accept FROM lite_b2b.acc_settings WHERE customer_guid = '".$_SESSION['customer_guid']."' ")->row('RB_total_days_accept');
                        $dateto =  date_create(date('Y-m-d', strtotime($datetocheck. ' + '.$variable.' days')));

                    } 
                    else
                    {
                        $datefrom = date_create(date('Y-m-d'));
                        $dateto = date_create($post->expiry_date);  
                    }*/

                    $interval = date_diff($dateto , $datefrom); 
 
                    $nestedData['batch_no'] = $post->batch_no;
                    $nestedData['prdn_refno'] = "<a href=".site_url('panda_prdncn/prdncn_child?trans='.$post->prdn_refno.'&loc='.$_REQUEST['loc']).'&type=DEBIT'.">".$post->prdn_refno."</a>";
                    $nestedData['location'] = $post->location;
                    $nestedData['doc_date'] = $post->doc_date;
                    $nestedData['expiry_date'] = $post->expiry_date;
                    $nestedData['sup_code'] = $post->sup_code;
                    $nestedData['sup_name'] = $post->sup_name;
                    $nestedData['status'] = $post->status_desc;
                    if($datefrom > $dateto)
                    {
                        if($post->status != 0)
                        {
                                $nestedData['canceled'] = "<span class='pull-right' style='color:red;font-weight:bold;'>-</span>";
                        }
                        else
                        {
                                $nestedData['canceled'] = "<span class='pull-right' style='color:red;font-weight:bold;'>-".$interval->format('%a')."</span>";
                        } 
                    }
                    else
                    {
                        if($post->status != 0)
                        {
                                $nestedData['canceled'] = "<span class='pull-right' style='color:red;font-weight:bold;'>-</span>";
                        }
                        else
                        {                        
                                $nestedData['canceled'] = "<span class='pull-right' style='color:red;font-weight:bold;'>".$interval->format('%a')."</span>";
                        }
                    }
                    $nestedData['accepted_at'] = $post->accepted_at;
                    $nestedData['button'] =  "<a href=".site_url('panda_return_collection/return_collection_child')."?refno=".$post->batch_no."&loc=".$_REQUEST['loc']." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    $nestedData['test'] = $post->status;

                    //$nestedData['button'] = "";
                };

                                if($check_module == 'panda_gr_download')
                {
                    $nestedData['refno'] = $post->refno;
                    $nestedData['grda_status'] = "<a href=".site_url('panda_grda/grda_child?trans='.$post->grda_status.'&loc='.$_REQUEST['loc']).">".$post->grda_status."</a>";
                    $nestedData['loc_group'] = $post->loc_group;
                    $nestedData['code'] = $post->code;
                    $nestedData['name'] = $post->name;
                    $nestedData['grdate'] = $post->grdate;
                    $nestedData['docdate'] = $post->docdate;
                    $nestedData['dono'] = $post->dono;
                    $nestedData['invno'] = $post->invno;
                    $nestedData['e_invno'] = $post->einvno;
                    $nestedData['cross_ref'] = $post->cross_ref;
                    $nestedData['total'] = "<span class='pull-right'>".number_format($post->total,2)."</span>";
                    $nestedData['gst_tax_sum'] = "<span class='pull-right'>".number_format($post->gst_tax_sum,2)."</span>";
                    $nestedData['total_include_tax'] = "<span class='pull-right'>".number_format($post->total_include_tax,2)."</span>";
                    $nestedData['status'] = $post->status;
                    $nestedData['button'] = "<a href=".site_url('panda_gr/gr_download_child')."?trans=".$post->refno."&loc=".$_REQUEST['loc']."&accpt_gr_status=".$post->status." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                     $nestedData['box'] = '<input type="checkbox" class="data-check" value="'.$post->refno.'">';
                };

                $data[] = $nestedData;

            }
        }
         
            $json_data = array(
                        "draw"            => intval($this->input->post('draw')),  
                        "recordsTotal"    => intval($totalData),  
                        "recordsFiltered" => intval($totalFiltered), 
                        "data"            => $data   
                        );
                
            echo json_encode($json_data); 
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public  function po_filter()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $frommodule = $this->input->post('frommodule');
            $current_location = $this->input->post('current_location');

            $po_num = $this->input->post('po_num');
            $po_status = $this->input->post('po_status');
            $daterange = $this->input->post('daterange');
            $expiry_from = $this->input->post('expiry_from');
            $expiry_to = $this->input->post('expiry_to');

            $period_code = $this->input->post('period_code');

            if($period_code == 'null')
            {
                //$query_period_code = " ";
                $_SESSION['filter_period_code'] = "null";
            }
            else
            {
                //$query_period_code = " and podate like left('$period_code', 7)";
                $_SESSION['filter_period_code'] = $period_code;
            }

            if($po_num != '')
            {
                $query_po_num = " and refno = '$po_num'";
            };

            if($po_status != 'NEW')
            {
                $query_po_status = "$po_status";
            }
            else
            {
                $query_po_status = " '' ";
            };

            if($daterange != '')
            {
                $daterange1 = explode(' - ', $daterange);
                $daterange_from = date('Y-m-d',strtotime($daterange1[0]));
                $daterange_to = date('Y-m-d',strtotime($daterange1[1]));
            }
            else
            {   //initial idea is to have default 7 days from today
                //$daterange_from = date('Y-m-d', strtotime('-7 days'));
                //$daterange_to = date('Y-m-d');
                $daterange_from = "";
                $daterange_to = "";
            };

            if($expiry_from  == '' && $expiry_to != '')
            {
                $expiry_from == $expiry_to;
            };
            

            redirect('general/view_status?status='.$query_po_status.'&loc='.$current_location.'&p_f='.$daterange_from.'&p_t='.$daterange_to.'&e_f='.$expiry_from.'&e_t='.$expiry_to.'&r_n='.$po_num);
        }
        else
        {
             redirect('#');
        }
    }

//functionsssss

    public function check_po_status()
    {           
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {

            $refno = $this->input->post('po_refno');
     
            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
                $to_shoot_url = $check_url."/postatus?refno=".$refno;

                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                $response = curl_exec($ch);
                $data = json_decode($response);
                curl_close($ch);

                if($data != null)
                {
                    if ($data->status == 'TRUE')
                    {
                        echo "1".$data->message;
                    }
                    else
                    {
                        echo "0".$data->message;
                    }
                }

        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }            
    }

    public function check_grn_no()
    {           
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {

            $po_check_grn_refno = $this->input->post('po_check_grn_refno');

            // echo $refno;
     
            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
                $to_shoot_url = $check_url."/po_grn_no?po_check_grn_refno=".$po_check_grn_refno;
                // echo $to_shoot_url;die;
                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                $response = curl_exec($ch);
                $data = json_decode($response);
                curl_close($ch);
                // print_r($data);
                if($data != null)
                {
                    if($data->status == 'TRUE')
                    {
                        $output = array();
                        if($data->count > 1)
                        {
                            $message = '';
                            $message .= 'GRN No is ';
                            foreach($data->message as $row)
                            {
                                $message .= $row->RefNo.', ';
                            }

                            $output['count'] = 1;
                            $output['xmessage'] = rtrim($message,', ');
                        }
                        else
                        {
                            $message = '';
                            foreach($data->message as $row)
                            {
                                $message .= 'GRN No is '.$row->RefNo.', ';
                            }

                            $output['count'] = 0;
                            $output['xmessage'] = rtrim($message,', ');
                        }

                    }
                    else
                    {
                        $message = $data->message;
                        $output['count'] = 2;
                        $output['xmessage'] = rtrim($message,', ');
                    }

                    echo json_encode($output);
                }
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }            
    }    

    public function check_po_no()
    {           
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {

            $grn_check_po_refno = $this->input->post('grn_check_po_refno');

            // echo $refno;
     
            $check_url = $this->db->query("SELECT rest_url from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('rest_url');
                $to_shoot_url = $check_url."/grn_po_no?grn_check_po_refno=".$grn_check_po_refno;
                // echo $to_shoot_url;die;
                $ch = curl_init($to_shoot_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                $response = curl_exec($ch);
                $data = json_decode($response);
                // print_r($data);
                if($data != null)
                {
                    if($data->status == 'TRUE')
                    {
                        $output = array();
                        if($data->count > 1)
                        {
                            $message = '';
                            $message .= 'PO No is ';
                            foreach($data->message as $row)
                            {
                                $message .= $row->PORefNo.', ';
                            }

                            $output['count'] = 1;
                            $output['xmessage'] = rtrim($message,', ');
                        }
                        else
                        {
                            $message = '';
                            foreach($data->message as $row)
                            {
                                $message .= 'PO No is '.$row->PORefNo.', ';
                            }

                            $output['count'] = 0;
                            $output['xmessage'] = rtrim($message,', ');
                        }

                    }
                    else
                    {
                        $message = $data->message;
                        $output['count'] = 2;
                        $output['xmessage'] = rtrim($message,', ');
                    }

                    echo json_encode($output);
                }
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }            
    }  

    public function accept()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $table = $_REQUEST['table'];
            $refno = $_REQUEST['refno'];
            $customer_guid = $_REQUEST['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $loc = $_REQUEST['loc'];

            $check_postatus = $this->db->query("SELECT * from b2b_summary.pomain where refno = '$refno' and customer_guid = '$customer_guid'");

            /*if($check_postatus->row('status') != '')
            {
            $this->session->set_flashdata('warning', 'Refno '.$refno.'fail to Accept! Please check the status of the PO.');
            redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);
            }*/
            $this->db->query("REPLACE into supplier_movement  select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'accepted_po'
                , '$from_module'
                , '$refno'
                , now()
                ");

            $this->db->query("UPDATE b2b_summary.pomain set status = 'Accepted',b2b_status = 'readysend' where customer_guid ='$customer_guid' and refno = '$refno'");
            
            $this->panda->get_uri();
            redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);


        }   
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function confirm()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $table = $_REQUEST['table'];
            $refno = $_REQUEST['refno'];
            $customer_guid = $_REQUEST['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $loc = $_REQUEST['loc'];

            // echo $from_module;die;

            if($from_module == 'panda_gr') //GR module confirm GR
            {
                $check_postatus = $this->db->query("SELECT * from b2b_summary.grmain where refno = '$refno' and customer_guid = '$customer_guid'");

                $userlog_module = 'confirmed_gr';

                if($check_postatus->row('status') == 'Confirmed') // make sure do dual status
                {
                    $this->session->set_flashdata('warning', 'Refno '.$refno.'fail to Confirmed! Please check the status of the GRN.');
                    //redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);
                    redirect($_SESSION['frommodule']."/gr_child?trans=".$refno."&loc=".$_REQUEST['loc']);
                }
                else
                {
                    $this->db->query("UPDATE b2b_summary.grmain set status = 'Confirmed' where customer_guid ='$customer_guid' and refno = '$refno'"); 
                }
            };

            if($from_module == 'panda_return_collection') // return collection accept collection detail
            {
                $check_status = $this->db->query("SELECT * from b2b_summary.dbnote_batch where batch_no = '$refno' and customer_guid = '$customer_guid'");

                $userlog_module = 'accept_return';

                if($check_status->row('status') == '1')
                {
                    $this->session->set_flashdata('warning', 'Unable to accept refno '.$refno.'! Status is not empty');

                    //echo $this->session->userdata('warning') ;die;
                    redirect($_SESSION['frommodule']."/return_collection_child?refno=".$refno."&loc=".$_REQUEST['loc']);
                }
                else
                {
                    $this->session->set_flashdata('message', 'Accept refno '.$refno.' Successfully');
                    $get_exp_day  = $this->db->query("SELECT RB_total_days_accept from acc_settings where customer_guid = '$customer_guid'")->row('RB_total_days_accept');
                     $this->db->query("UPDATE b2b_summary.dbnote_batch set status = '1' , accepted_by = '$user_guid', accepted_at =  now(),uploaded = 0,action_date = CURDATE() where customer_guid ='$customer_guid' and batch_no = '$refno' and status = '0'"); 
                    $this->db->query("REPLACE into supplier_movement select 
                        upper(replace(uuid(),'-','')) as movement_guid
                        , '$customer_guid'
                        , '$user_guid'
                        , '$userlog_module'
                        , '$from_module'
                        , '$refno'
                        , now()
                        ");                     
                     redirect($_SESSION['frommodule']."/return_collection_child?refno=".$refno."&loc=".$_REQUEST['loc']);
                }
            }; 
                // echo $this->db->last_query();DIE;
            $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , '$userlog_module'
                , '$from_module'
                , '$refno'
                , now()
                ");
            $this->panda->get_uri();
            redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);


        }   
        else
        {
              $this->session->set_flashdata('message', 'Session Expired! Please relogin');

            redirect('#');
        }
    }


    public function reject()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $this->panda->get_uri();

            $table = $this->input->post('table');
            $refno =  $this->input->post('refno');
            $customer_guid = $this->input->post('customer_guid');
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $loc = $this->input->post('loc');
            $reason  =$this->input->post('reason');
            $datetimenow = $this->db->query("SELECT now() as now")->row('now'); 
            $b2b_database = 'b2b_summary';

            $reject_reason = $this->db->query("SELECT * FROM status_setting WHERE type = 'reject_po' AND code = '$reason'");
            $email_reject_reason = $reject_reason->row('portal_description');
            // echo $email_reject_reason;die;

            if($from_module == 'panda_po_2')
            {
                    $refno_loc_group = $this->db->query("SELECT * FROM $b2b_database.pomain WHERE RefNo = '$refno' AND customer_guid = '$customer_guid' ");
                    $refno_loc_group_code = $refno_loc_group->row('loc_group');
                    $refno_customer_guid = $refno_loc_group->row('customer_guid');
                    $refno_supplier_name = $refno_loc_group->row('SName');
                    $check_postatus = $this->db->query("SELECT * from b2b_summary.pomain where refno = '$refno' and customer_guid = '$customer_guid' and status in ('', 'viewed', 'printed')");

                    if($check_postatus->num_rows() != '1' ) 
                    {
                        $this->session->set_flashdata('warning', 'Refno '.$refno.'fail to reject! Please check the status of the PO.');
                        redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);
                    };

                    $this->db->query("UPDATE b2b_summary.pomain set status = 'rejected', rejected_remark = '$reason' , rejected = '1' , b2b_status = 'readysend' ,rejected_at = now()  where customer_guid ='$customer_guid' and refno = '$refno'");
            };

            if($from_module == 'panda_return_collection')
            {
                $refno_loc_group = $this->db->query("SELECT * FROM $b2b_database.dbnote_batch WHERE batch_no = '$refno' AND customer_guid = '$customer_guid' ");
                $refno_loc_group_code = $refno_loc_group->row('loc_group');
                $refno_customer_guid = $refno_loc_group->row('customer_guid');
                $refno_supplier_name = $refno_loc_group->row('sup_name');
                $check_status = $this->db->query("SELECT * from b2b_summary.dbnote_batch where batch_no = '$refno' and customer_guid = '$customer_guid' and status = '0'");

                if($check_status->num_rows() != '1' ) 
                {
                        $this->session->set_flashdata('warning', 'Refno '.$refno.'fail to reject! Please check the status of the Doc.');
                        redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);
                };

                $this->db->query("UPDATE b2b_summary.dbnote_batch set status = '-1', rejected_remark = '$reason' ,  updated_at = now()  where customer_guid ='$customer_guid' and batch_no = '$refno'");
            } 

          /*  $this->db->query("UPDATE b2b_summary.pomain set status = 'Rejected', rejected_remark = '$reason'  where customer_guid ='$customer_guid' and refno = '$refno'");*/
          
            
            $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'rejected'
                , '$from_module'
                , '$refno'
                , now()
                ");
            unset($_SESSION['from_other']);
            // set session for required setting
        $email = $this->db->query("SELECT * from email_setup");

        $setsession = array(
                'smtp_server' =>$email->row('smtp_server'),
                'email_username' =>$email->row('username'),
                'email_password' => $email->row('password'),
                'smtp_security' =>$email->row('smtp_security'),
                'smtp_port' =>$email->row('smtp_port'),
                'sender_email' =>$email->row('sender_email'),
                'sender_name' =>$email->row('sender_name'),
                'subject' => 'Refno Rejected - '.$refno,
                'url' => $email->row('url'),
                );
        $this->session->set_userdata($setsession);

        // loop all group
        $user_right_code = 'RERPO';
       // $email_group = $this->db->query("SELECT * from check_email_schedule a INNER JOIN set_user_branch b ON a.user_guid = b.user_guid INNER JOIN acc_branch c ON b.branch_guid = c.branch_guid INNER JOIN acc_concept d ON c.concept_guid = d.concept_guid INNER JOIN set_user e ON a.user_guid = e.user_guid INNER JOIn set_user_group f ON e.user_group_guid = f.user_group_guid INNER JOIN set_user_module g ON f.user_group_guid = g.user_group_guid INNER JOIN acc_module h ON g.module_guid = h.acc_module_guid where b.acc_guid = '$customer_guid' and a.report_id in ('MTczNjQ0ODYtNg') and a.day_name = 'Everyday' AND d.acc_guid = '$customer_guid' AND c.branch_code = '$refno_loc_group_code' AND h.acc_module_code = '$user_right_code' AND e.acc_guid = '$refno_customer_guid' GROUP BY a.user_guid");

       $email_group = $this->db->query("SELECT a.user_id as email,a.user_name as first_name FROM set_user a INNER JOIN set_user_group b ON a.user_group_guid = b.user_group_guid INNER JOIN set_user_module c ON b.user_group_guid = c.user_group_guid INNER JOIN set_module d ON c.module_guid = d.module_guid INNER JOIN set_module_group e ON d.module_group_guid = e.module_group_guid INNER JOIN set_user_branch f ON a.user_guid = f.user_guid INNER JOIN acc_branch g ON f.branch_guid = g.branch_guid INNER JOIN acc_concept h ON g.concept_guid = h.concept_guid WHERE a.isactive = 1 AND a.acc_guid = '$customer_guid' AND e.module_group_name = 'Panda B2B' AND c.isenable = 1 AND d.module_code = 'RERPO' AND f.acc_guid = '$customer_guid' AND h.acc_guid = '$customer_guid' AND g.branch_code = '$refno_loc_group_code' GROUP BY a.user_guid");
       // echo $this->db->last_query();die;

        if($email_group->num_rows() == 0)
        {
            redirect($_SESSION['frommodule']."?loc=".$loc);
        };

        $check_scode = str_replace("/","+-+",$refno_loc_group->row('SCode'));

        $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
        $type = $parameter->row('type');
        $code = $check_scode;
        // echo $code;die;

        $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');

        $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
       
        $filename = $virtual_path.'/'.$replace_var.'.pdf';
        // echo $filename;die;
        $email_name = $email_group->row('first_name');
        $email_add = $email_group->row('email');
        $date = $this->db->query("SELECT now() as now")->row('now');
        $date_now = $this->db->query("SELECT DATE_FORMAT(now(),'%Y-%m-%d') as now")->row('now');
        $customer_name = $this->db->query("SELECT * FROM acc WHERE acc_guid = '$customer_guid' LIMIT 1");
       
        $bodyContent = '<div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                        B2B Notification
                                    </h3>
                                    <p class="lead">
                                        Document '.$refno.' has been rejected by Supplier at '.$datetimenow.'
                                        <br>
                                        Retailer : '.$customer_name->row('acc_name').'
                                        <br>Rejected by : '.$refno_supplier_name.'
                                        <br>Login id : '.$this->session->userdata('userid').'
                                        <br>Outlet : '.$refno_loc_group_code.'
                                        <br>Reason : '.$email_reject_reason.'
                                        <br>
                                        Regards,<br>
                                        <a href="'.$_SESSION['url'].'"> B2B Mail</a>
                                    </p>
                                </div>
                            </div>
                        </div>';
        $email_subject = 'Rejected Document at '. $date_now;
        foreach($email_group->result() as $row)
        {
            $module = 'reject_po';
            $email_name = $row->first_name;
            $email_add = $row->email;
            // $this->send_to_manager($email_add, $email_name, $date, $bodyContent);
            if($customer_guid == 'D361F8521E1211EAAD7CC8CBB8CC0C93')
            {
                    $this->send_mailjet_third_party($email_add, '', $bodyContent, $email_subject, '','','','support@xbridge.my',$filename);
            }
            else
            {
                    $this->send_mailjet_third_party($email_add, '', $bodyContent, $email_subject, '','','','support@xbridge.my','');    
            }
        }
        // end
        redirect($_SESSION['frommodule']."?loc=".$loc);
        }   
        else
        {
              $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function reject_via_type()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
           /* $this->panda->get_uri();
            $table = $this->input->post('table');
            $refno =  $this->input->post('refno');
            $customer_guid = $this->input->post('customer_guid');
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $loc = $this->input->post('loc');
            $reason  =$this->input->post('reason');
            $datetimenow = $this->db->query("SELECT now() as now")->row('now');

            $check_postatus = $this->db->query("SELECT * from b2b_summary.pomain where refno = '$refno' and customer_guid = '$customer_guid' and status in ('', 'viewed', 'printed')");*/
            $table = $_REQUEST['table'];
            $refno = $_REQUEST['refno'];
            $customer_guid = $_REQUEST['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $from_module = $_SESSION['frommodule'];
            $loc = $_REQUEST['loc'];

             if($from_module == 'panda_return_collection') // return collection accept collection detail
            {
                $check_status = $this->db->query("SELECT * from b2b_summary.dbnote_batch where batch_no = '$refno' and customer_guid = '$customer_guid'");

                $userlog_module = 'reject_return';
                $title = 'Return Batch Doucment';

                if($check_status->row('status') == '1')
                {
                    $this->session->set_flashdata('warning', 'Unable to accept refno '.$refno.'! Status is not empty');

                    //echo $this->session->userdata('warning') ;die;
                    redirect($_SESSION['frommodule']."/return_collection_child?refno=".$refno."&loc=".$_REQUEST['loc']);
                }
                else
                {
                     $this->db->query("UPDATE b2b_summary.dbnote_batch set status = '-1' , updated_at =  now() where customer_guid ='$customer_guid' and batch_no = '$refno' and status = '0'"); 
                }


            }; 
                //echo $this->db->last_query();DIE;
            $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , '$userlog_module'
                , '$from_module'
                , '$refno'
                , now()
                ");
            $this->panda->get_uri();
            redirect($_SESSION['frommodule']."?loc=".$_REQUEST['loc']);

           

          /*  $this->db->query("UPDATE b2b_summary.pomain set status = 'Rejected', rejected_remark = '$reason'  where customer_guid ='$customer_guid' and refno = '$refno'");*/
         
            // set session for required setting
        $email = $this->db->query("SELECT * from email_setup");

        $setsession = array(
                'smtp_server' =>$email->row('smtp_server'),
                'email_username' =>$email->row('username'),
                'email_password' => $email->row('password'),
                'smtp_security' =>$email->row('smtp_security'),
                'smtp_port' =>$email->row('smtp_port'),
                'sender_email' =>$email->row('sender_email'),
                'sender_name' =>$email->row('sender_name'),
                'subject' => $title.' Rejected - '.$refno,
                'url' => $email->row('url'),
                );
        $this->session->set_userdata($setsession);

        // loop all group

        $email_group = $this->db->query("SELECT first_name, email from check_email_schedule where customer_guid = '$customer_guid' and report_id in ('MTczNjQ0ODYtNg') and day_name = 'Everyday' ");

        if($email_group->num_rows() == 0)
        {
            redirect($_SESSION['frommodule']."?loc=".$loc);
        };

        $email_name = $email_group->row('first_name');
        $email_add = $email_group->row('email');
        $date = $this->db->query("SELECT now() as now")->row('now');
       
        $bodyContent = '<div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-info">
                                        B2B Notification
                                    </h3>
                                    <p class="lead">
                                        '.$title.' - '.$refno.' has been cancelled by Supplier at '.$datetimenow.'
                                        <br> 
                                        Regards,<br>
                                        <a href="'.$_SESSION['url'].'"> B2B Mail</a>
                                    </p>
                                </div>
                            </div>
                        </div>';
        foreach($email_group->result() as $row)
        {
            $email_name = $row->first_name;
            $email_add = $row->email;
            $this->send_to_manager($email_add, $email_name, $date, $bodyContent);
        }
        // end
        redirect($_SESSION['frommodule']."?loc=".$loc);
        }   
        else
        {
              $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function otherstatus()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $refno = $this->input->post('refno');
            $loc = $this->input->post('loc');
            $reason = $this->input->post('reason');
            $module_group_guid  = $_SESSION['module_group_guid'];

            $common_code = $this->db->query("SELECT common_code FROM status_setting WHERE type = 'hide_po_filter' AND code = '$reason' LIMIT 1")->row('common_code');
            // echo $reason.$common_code;die;

            $this->db->query("INSERT INTO  userlog SELECT UPPER(REPLACE(UUID(),'-','')) AS trans_guid , '".$_SESSION['customer_guid']."' , 'Panda B2B' , 'PO' , '$refno' , 'status' , '' , '$common_code' , NOW() , '".$_SESSION['userid']."'");

            $this->db->query("UPDATE b2b_summary.pomain set status = '$common_code',hide_reason = '$reason' where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");

            //echo $_SESSION['frommodule']."?status=&loc=".$loc;die;
            redirect($_SESSION['frommodule']."?loc=".$loc);

        }
        else
        {
            redirect('#');
        }
    }

    public function general_status()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
        }
        else
        {
            
        }
    }

    public function send_to_manager($email_add, $email_name, $date, $bodyContent)
    {
        $mail = new PHPMailer;

        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $_SESSION['smtp_server']; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_SESSION['email_username']; // SMTP username
        $mail->Password = $_SESSION['email_password']; // SMTP password
        $mail->SMTPSecure = $_SESSION['smtp_security'];// Enable TLS encryption, `ssl` also accepted
        $mail->Port = $_SESSION['smtp_port']; // TCP port to connect to

        $mail->setFrom($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addReplyTo($_SESSION['sender_email'], $_SESSION['sender_name']);
        $mail->addAddress($email_add, $email_name); // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->isHTML(true);  // Set email format to HTML
        $path= base_url('assets/img/new.png');
        
        
        $mail->Subject = $_SESSION['subject'];
        $mail->Body    = $bodyContent;

        if(!$mail->send()) 
        {
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => $_SESSION["userid"],
                'recipient' => $email_add,
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'FAIL',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
           // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
            //redirect('Email_controller/setup');
        } 
        else 
        {
            $data = array(

                'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                'created_by' => $_SESSION["userid"],
                'recipient' => $email_add,
                'sender' => $_SESSION['sender_email'],
                'subject' => $_SESSION['subject'],
                'status' => 'SUCCESS',
                'respond_message' => $mail->ErrorInfo,
                'smtp_server' => $_SESSION['smtp_server'],
                'smtp_port' => $_SESSION['smtp_port'],
                'smtp_security' => $_SESSION['smtp_security'],
                );
            $this->db->insert('email_transaction', $data);
            // $this->session->set_flashdata('message', 'Message has been sent');
            //  redirect('Email_controller/setup');
            // echo 'Message has been sent';
        }
    }

    public function ajax_bulk_print()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $frommodule = $_SESSION['frommodule'];
        //before changes
            $loc= $_REQUEST['loc'];
            $list_id = $this->input->post('id');
            foreach ($list_id as $id) {
                 $link_url[] = site_url($frommodule.'/direct_print?trans='.$id.'&loc='.$loc);
            }
            echo json_encode(array("link_url" => $link_url));
 

        // end before changes

          /*  //trying merge PDF but fail 2018-09-10 to 2018-09-14
          $list_id = $this->input->post('id');
            $loc = $_REQUEST['loc'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['userid'];
            $from_module = $_SESSION['frommodule'];
       

             
            foreach($list_id as $refno)
            {
                 $check_scode = $this->db->query("SELECT scode from b2b_summary.pomain where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');

                $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
                $type = $parameter->row('type');
                $code = $check_scode;

                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$refno') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query'); 

               $virtual_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path');
           
                $filename[] = base_url($virtual_path.'/'.$replace_var.'.pdf');
                $base_name[] = $virtual_path.'/'.$replace_var.'.pdf';
                $file_headers = @get_headers($filename);
            }

            $pdf = new \PDFMerger\PDFMerger;



              /*  $files = $filename; 
              //var_dump($base_name);die;
                $pdf = new PDFMerger;
                $pdf->addPDF($base_name[], 'all');
                $pdf->merge('download', 'invoice/TEST2.pdf', 'L');
                
                 */   

        }
        else
        {
            redirect('#');
        }
    }

    public function ajax_bulk_print_prdncn()        
    {       
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())       
        {       
            $frommodule = $_SESSION['frommodule'];      
        //before changes        
            $loc= $_REQUEST['loc'];     
            $list_id_object = $this->input->post('id');     
            $list_id = json_decode(stripslashes($list_id_object));      
            // $list_id = $this->input->post('id');     
                foreach ($list_id as $row) {        
                    $link_url[] = site_url($frommodule.'/direct_print?trans='.$row->id.'&loc='.$loc.'&dncn='.$row->type);       
            }       
            echo json_encode(array("link_url" => $link_url));       
        }       
        else        
        {       
            redirect('#');      
        }       
    }

    public function ajax_bulk_accept()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $frommodule = $_SESSION['frommodule'];
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];

        //before changes
            $loc= $_REQUEST['loc'];
            $list_id = $this->input->post('id');
            foreach ($list_id as $id) {
                // $link_url[] = site_url($frommodule.'/bulk_accept?trans='.$id.'&loc='.$loc);
                $check_grda = $this->db->query("SELECT * from b2b_summary.grmain_dncn where customer_guid = '$customer_guid' and refno = '$id'");

                if($check_grda->num_rows() == 0)
                {
                    $this->db->query("UPDATE b2b_summary.grmain set status = 'Confirmed' where customer_guid ='$customer_guid' and refno = '$id' and status in ('','viewed','printed_grn') ");

                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'Confirmed'
                    , '$frommodule'
                    , '$id'
                    , now()
                    ");
                }
                else
                {
                    $this->db->query("SELECT 'unable to update' as title");
                }
                
            }
            $this->session->set_flashdata('message', 'Bulk Accepted');
            redirect($_SESSION['frommodule']."?loc=".$loc);
            //echo json_encode(array("link_url" => $link_url));

        }
    }

    public function merge_pdf()
    {

        // require_once APPPATH.'libraries/pdf-merger-master/src/PDFMerger/PDFMerger.php';
        // require(APPPATH.'libraries/fpdf/Fpdf.php');
        // require(APPPATH.'libraries/fpdi/src/FpdiTrait.php');
        require(APPPATH.'third_party/PDFMerger-master/PDFMerger.php');
      //  echo APPPATH.'third_party\PDFMerger-master\PDFMerger.php';
        include 'PDFMerger.php';

        $pdf = new PDFMerger;

        // $frommodule = $_SESSION['frommodule'];
    // before changes
        // $loc= $_REQUEST['loc'];
        $list_id = $this->input->post('id');

        // $file_name = array('1','2','3','4');
        // $file_name = array('B2WDP18020020');

        $xrefno = '';
        foreach($list_id as $row)
        {
            $customer_guid = $_SESSION['customer_guid'];
            $frommodule = $_SESSION['frommodule'];
            $loc =$_REQUEST['loc'];
            // echo $_REQUEST['po_type'];die;
            if($_REQUEST['po_type'] == 'PO')
            {
                // echo 1;die;
                $check_scode = $this->db->query("SELECT scode from b2b_summary.pomain where refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('scode');
            }
            else if($_REQUEST['po_type'] == 'GRN')
            {
                // echo 2;die;
                $check_scode = $this->db->query("SELECT code from b2b_summary.grmain where refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');        
            }
            else if($_REQUEST['po_type'] == 'GRDA')
            {
                // echo 2;die;
                $check_scode = $this->db->query("SELECT ap_sup_code from b2b_summary.grmain_dncn where refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('ap_sup_code');    
            }
            else if($_REQUEST['po_type'] == 'PCI')
            {
                // echo 2;die;
                if($this->session->userdata('customer_guid') != '1F90F5EF90DF11EA818B000D3AA2CAA9' && $this->session->userdata('customer_guid') != '907FAFE053F011EB8099063B6ABE2862')
                {
                    $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where promo_refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
                }
                else
                {
                    $check_scode = $this->db->query("SELECT sup_code from b2b_summary.promo_taxinv where inv_refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code'); 
                }
            }
            else if($_REQUEST['po_type'] == 'DI')
            {
                // echo 2;die;
                $check_scode = $this->db->query("SELECT sup_code from b2b_summary.discheme_taxinv where inv_refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('sup_code');
            }
            // echo $type;die;
            $check_scode = str_replace("/","+-+",$check_scode);
            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $type = $parameter->row('type');
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$row') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');
            // echo $replace_var;die;
            // echo $this->db->last_query();

            $virtual_path = substr($this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path'),1);

        
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf'); 
            // $filename =  '192.168.10.29/lite_panda_b2b/uploads/tfvalue/'.$replace_var.'.pdf';  
            // echo $filename;
            // $pdf->addPDF('uploads/tfvalue/'.$row.'.pdf', 'all');
            // $xfilename = 'uploads/tfvalue/'.$row.'.pdf';
             // uploads/tfvalue/PO__1.pdf
          $filename =  $virtual_path.'/'.$replace_var.'.pdf';  
            
            // PO__PO_1
            $pdf->addPDF($filename, 'all');
            $xrefno .= $row . ',';

        }
        $xxrefno = rtrim($xrefno,",");
        if (!is_dir('././merge'))
        {
            mkdir('././merge', 0777,true);
            // echo 1;die;
        }
        $pdf_name = 'MERGE_'.uniqid();
        $link_url = site_url($frommodule.'/direct_print_merge?trans='.$xxrefno.'&loc='.$loc.'&pdfname='.$pdf_name);
        $pdf_file = $pdf_name;
        $pdf->merge('file', 'merge/'.$pdf_name.'.pdf'); // generate the file
        echo json_encode(array("link_url" => $link_url,"pdf_file" => $pdf_file));
    }

    public function merge_pdf_prdncn()      
    {       
        // require_once APPPATH.'libraries/pdf-merger-master/src/PDFMerger/PDFMerger.php';      
        // require(APPPATH.'libraries/fpdf/Fpdf.php');      
        // require(APPPATH.'libraries/fpdi/src/FpdiTrait.php');     
        require(APPPATH.'third_party/PDFMerger-master/PDFMerger.php');      
      //  echo APPPATH.'third_party\PDFMerger-master\PDFMerger.php';        
        include 'PDFMerger.php';     
        $pdf = new PDFMerger;       
        // $frommodule = $_SESSION['frommodule'];       
        // before changes       
        // $loc= $_REQUEST['loc'];      
        // echo 1;die;      
        // $list_id = $this->input->post('id');     
        $list_id_object = $this->input->post('id');     
        $list_id = json_decode(stripslashes($list_id_object));      
        // print_r($list_id);die;       
        // $file_name = array('1','2','3','4');     
        // $file_name = array('B2WDP18020020');     
        $xrefno = '';       
        $xlocation = '';        
        foreach($list_id as $row)       
        {       
            $customer_guid = $_SESSION['customer_guid'];        
            $frommodule = $_SESSION['frommodule'];      
            $loc =$_REQUEST['loc'];  

            // echo $_REQUEST['po_type'];die;       
            if($row->type == 'CN')      
            {       
                // echo 1;die;      
                $check_scode = $this->db->query("SELECT code from b2b_summary.cnnotemain where refno = '$row->id' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');      
            }       
            else        
            {       
                // echo 2;die;      
                $check_scode = $this->db->query("SELECT code from b2b_summary.dbnotemain where refno = '$row->id' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');              
            }    

            if($row->type == 'CN')      
            {       
                $check_scode = $this->db->query("SELECT code from b2b_summary.cnnotemain where refno = '$row->id' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');        
            }       
            else        
            {       
                $check_scode = $this->db->query("SELECT code from b2b_summary.dbnotemain where refno = '$row->id' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');        
            } 

            if($row->type == 'CN')
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prcn'");
            }
            else
            {
                $parameter = $this->db->query("SELECT * from menu where module_link = 'panda_prdn'");   
            }

            // $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");      
            $type = $parameter->row('type');  
            $check_scode = str_replace("/","+-+",$check_scode);      
            $code = $check_scode;       
            // $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$row->id') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');      

            if($xtype == 'CN')
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$row->id') AS query FROM menu where module_link = 'panda_prcn'")->row('query');
            }
            else
            {
                $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$row->id') AS query FROM menu where module_link = 'panda_prdn'")->row('query');
            }    
            // echo $replace_var;       
            // echo $this->db->last_query();        
            $virtual_path = substr($this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path'),1);        
                
                
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf');         
            // $filename =  '192.168.10.29/lite_panda_b2b/uploads/tfvalue/'.$replace_var.'.pdf';        
            // echo $filename;      
            // $pdf->addPDF('uploads/tfvalue/'.$row.'.pdf', 'all');     
            // $xfilename = 'uploads/tfvalue/'.$row.'.pdf';     
             // uploads/tfvalue/PO__1.pdf       
          $filename =  $virtual_path.'/'.$replace_var.'.pdf';       
                    
            // PO__PO_1     
            $pdf->addPDF($filename, 'all');//page       
            $xrefno .= $row->id . ',';      
            $xlocation .= $row->id.'-->'.$row->type.',';        
        }       
        $xxrefno = rtrim($xrefno,",");      
        $xxlocation = rtrim($xlocation,",");        
        if (!is_dir('././merge'))       
        {       
            mkdir('././merge', 0777,true);      
            // echo 1;die;      
        }       
        $pdf_name = 'MERGE_'.uniqid();      
        $link_url = site_url($frommodule.'/direct_print_merge?trans='.$xxrefno.'&loc='.$loc.'&pdfname='.$pdf_name.'&dncn='.$xxlocation);        
        $pdf_file = $pdf_name;      
        $pdf->merge('file', 'merge/'.$pdf_name.'.pdf'); // generate the file        
        echo json_encode(array("link_url" => $link_url,"pdf_file" => $pdf_file));       
    }           

    public function merge_pdf_pdncn()
    {

        // require_once APPPATH.'libraries/pdf-merger-master/src/PDFMerger/PDFMerger.php';
        // require(APPPATH.'libraries/fpdf/Fpdf.php');
        // require(APPPATH.'libraries/fpdi/src/FpdiTrait.php');
        require(APPPATH.'third_party/PDFMerger-master/PDFMerger.php');
      //  echo APPPATH.'third_party\PDFMerger-master\PDFMerger.php';
        include 'PDFMerger.php';

        $pdf = new PDFMerger;

        // $frommodule = $_SESSION['frommodule'];
    // before changes
        // $loc= $_REQUEST['loc'];
        $list_id = $this->input->post('id');

        // $file_name = array('1','2','3','4');
        // $file_name = array('B2WDP18020020');

        $xrefno = '';
        foreach($list_id as $row)
        {
            $customer_guid = $_SESSION['customer_guid'];
            $frommodule = $_SESSION['frommodule'];
            $loc =$_REQUEST['loc'];
            // echo $_REQUEST['po_type'];die;

            $check_scode = $this->db->query("SELECT code from b2b_summary.cndn_amt where refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('code');

            // echo $type;die;
            $parameter = $this->db->query("SELECT * from menu where module_link = '".$_SESSION['frommodule']."'");
            $ptype = $this->db->query("SELECT trans_type from b2b_summary.cndn_amt where refno = '$row' and customer_guid = '".$_SESSION['customer_guid']."'")->row('trans_type');
            $type = substr($ptype,0,3);
            $check_scode = str_replace("/","+-+",$check_scode);
            $code = $check_scode;

            $replace_var = $this->db->query("SELECT REPLACE(REPLACE(REPLACE(filename_format, 'type', '$type'), 'code', '$code'), 'refno' , '$row') AS query FROM menu where module_link = '".$_SESSION['frommodule']."'")->row('query');
            // echo $replace_var;die;
            // echo $this->db->last_query();

            $virtual_path = substr($this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path'),1);

        
           
            // $filename = base_url($virtual_path.'/'.$replace_var.'.pdf'); 
            // $filename =  '192.168.10.29/lite_panda_b2b/uploads/tfvalue/'.$replace_var.'.pdf';  
            // echo $filename;
            // $pdf->addPDF('uploads/tfvalue/'.$row.'.pdf', 'all');
            // $xfilename = 'uploads/tfvalue/'.$row.'.pdf';
             // uploads/tfvalue/PO__1.pdf
          $filename =  $virtual_path.'/'.$replace_var.'.pdf';  
            
            // PO__PO_1
            $pdf->addPDF($filename, 'all');
            $xrefno .= $row . ',';

        }
        $xxrefno = rtrim($xrefno,",");
        if (!is_dir('././merge'))
        {
            mkdir('././merge', 0777,true);
            // echo 1;die;
        }
        $pdf_name = 'MERGE_'.uniqid();
        $link_url = site_url($frommodule.'/direct_print_merge?trans='.$xxrefno.'&loc='.$loc.'&pdfname='.$pdf_name);
        $pdf_file = $pdf_name;
        $pdf->merge('file', 'merge/'.$pdf_name.'.pdf'); // generate the file
        echo json_encode(array("link_url" => $link_url,"pdf_file" => $pdf_file));
    }

    public function unlink_file()
    {
        $pdf_file = $this->input->post('url_link');
        unlink('merge/'.$pdf_file.'.pdf');
        echo $pdf_file;
        // rmdir('merge/tfvalue');
    }

    public function clear_notification()
    {
        $user_guid = $this->input->post('user_guid');

        $time = $this->input->post('time');

        $this->db->query("UPDATE notifications SET status = '1' WHERE user_guid = '$user_guid' AND status = '0' AND created_at < '$time' ");

    }

    public function get_notification()
    {
        $notification_li_length = $this->input->post('notification_li_length');

        $user_guid = $this->input->post('user_guid');

        $notifications = $this->db->query("SELECT * FROM (SELECT * FROM notifications WHERE user_guid = '$user_guid' ORDER BY created_at DESC)a GROUP BY a.message ORDER BY created_at DESC LIMIT 5 OFFSET $notification_li_length ")->result();

        echo json_encode($notifications);       


    }

    public function send_mailjet_third_party($email_add, $date, $bodyContent, $email_subject, $module,$cc_list_string,$pdf,$reply_to,$filename)
    {
        // die;
        if($pdf != '' || $pdf != null)
        { 
            $b64Doc = chunk_split(base64_encode(file_get_contents($pdf))); 
            $filename = substr($pdf, strrpos($pdf, '/') + 1);
        }
        else
        {
            $b64Doc = ''; 
        }
        // $pdfBase64 = base64_encode(file_get_contents('uploads/qr_code/4/hah.pdf')); 
        // echo $b64Doc;die;      
        $from_email = $this->db->query("SELECT * FROM lite_b2b.mailjet_setup WHERE type = 'reject_po_notification' LIMIT 1");
        $to_email = $email_add;
        $to_email_name = $email_add;
        // $to_email = 'danielweng57@gmail.com';
        // $to_email_name = 'danielweng57@gmail.com';            
        $variable = array('api_key' => '1234','secret_key' => '123456', 'module' => 'test');

        $replyto = array('Email' => $reply_to,'Name' => $reply_to);
        $from = array('Email' => $from_email->row('sender_email'),'Name' => $from_email->row('sender_name'));
        $to = array('Email' => $to_email,'Name' => $to_email_name);
        $to_array = array($to);

        if($cc_list_string != '' || $cc_list_string != null)
        {
            $test_array = explode(',',$cc_list_string);
            $cc_array=array();
            foreach($test_array as $tarray)
            {
                // echo $tarray->sender_email;
                $cc = array('Email' => $tarray,'Name' => $tarray);
                array_push($cc_array, $cc);
            }
        }
        else
        {
            $cc_array = '';  
        }

        // $Bc = array('Email' => 'desmondm520@gmail.com','Name' => 'you1');
        $bcc_array = array();
        $variable1 = array($variable);
        $variables = array('var1' => $variable1);
        // $variables_array = array($variables);
        $templateid = 1090613;
        $Subject = $email_subject;
        $TextPart = $email_subject;
        $HTMLPart = $bodyContent; 

        if($filename != '')
        {
            $pdf = ('.'.$filename);
            // echo $filename;die;
            // if(file_exists('./uploads/bataras/PO_S033_BRNPO20090296.pdf'))
            // {
            //     echo 1;
            // };
            // die;
            // $pdf = ('uploads/hwathai/Hwa Thai_B2B letter to suppliers.pdf');
            // echo $pdf;die; 
            $b64Doc = chunk_split(base64_encode(file_get_contents($pdf))); 
            // echo $b64Doc;die;
            // $b64Doc = '';
            $refno = substr($pdf, strrpos($pdf, "/") + 1);
            // echo $refno;die;
            $filename = $refno;
            // echo $refno.'asdadssadasdadasd-'.$b64Doc;die;

            $attachment = array('ContentType' => 'application/pdf','Filename' => $filename,'Base64Content' => $b64Doc);

            $attachment_array[] = $attachment;
        }
        else
        {
            $b64Doc = ''; 
        }

        if($b64Doc != '')
        {
            $attachment = array('ContentType' => 'application/pdf','Filename' => $filename,'Base64Content' => $b64Doc);
            $attachment1 = array($attachment);
            $attachment_array = array($attachment);            
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables,'cc' => $cc_array, 'replyto' =>$replyto,'attachments' => $attachment_array);
        }
        else
        {
            $data = array('from' => $from,'to' => $to_array,'subject' => $Subject,'textpart' => $TextPart,'htmlpart' => $HTMLPart,'variables' => $variables, 'replyto' =>$replyto);
        }
        // $data2 = array($data);
        // $data3 = array('Messages' => $data2);
        // $t = array($t, "Mary", "Peter", "Sally");

        $myJSON = json_encode($data);
        // echo $myJSON;die;

        $to_shoot_url = "localhost/pandaapi3rdparty/index.php/email_agent/mj_sendemail";
        $ch = curl_init(); 

        curl_setopt_array($ch, array(
          CURLOPT_URL => $to_shoot_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $myJSON,
          CURLOPT_HTTPHEADER => array(
            "x-api-key: 123456",
            "Content-Type: application/json"
          ),
        ));

        // $to_shoot_url = 'localhost/pandaapi3rdparty/index.php/email_agent/mj_sendemail';
        // $ch = curl_init($to_shoot_url); 
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . "123456" ));
        // curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        // // curl_setopt($ch, CURLOPT_USERPWD, $mailjet_user.":".$mailjet_pass);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        // curl_setopt($ch,CURLOPT_POSTFIELDS, $myJSON);
        $result = curl_exec($ch);
        $result1 = json_decode($result);
        // print_r($result);die;
        $retry = 0;
        while(curl_errno($ch) == 28 && $retry < 3){
            $response = curl_exec($ch);
            $retry++;
        }

        if(!curl_errno($ch))
        {
            if(isset($result1->Messages[0]))
            {
                $status = $result1->Messages[0]->Status;
            }
            else
            {
                $status = $result1->ErrorMessage;
            }


            if($status == 'success')
            {
                $ereponse = $result1->Messages[0]->To[0]->MessageID;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'SUCCESS',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                if($module != 'alert_notification')
                {
                    echo json_encode(array(
                            'status' => true,
                            'message' => 'success',
                            'action'=> 'next',
                            ));
                };
            }
            else
            {
                $ereponse = $result1->StatusCode.'-'.$result1->ErrorMessage;
                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                // if($module != 'alert_notification')
                // {
                 echo json_encode(array(
                    'status' => false,
                    'message' => $ereponse,
                    'action'=> 'retry',
                    ));
                // };
            }

            curl_close($ch);
        }
        else
        {
                $ereponse = 'Curl error: '.curl_error($ch);

                $data = array(
                    'created_at' => $this->db->query("SELECT now() as now")->row('now'),
                    'created_by' =>$_SESSION["userid"],
                    'recipient' => $to_email,
                    'sender' => $from_email->row('sender_email'),
                    'subject' => $email_subject,
                    'status' => 'FAIL',
                    'respond_message' => $retry.$ereponse,
                    'smtp_server' => 'mailjet',
                    'smtp_port' => 'mailjet',
                    'smtp_security' => 'mailjet',
                    );
                $this->db->insert('email_transaction', $data);
                // $this->session->set_flashdata('message', 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
                //redirect('Email_controller/setup');
                // if($module != 'alert_notification')
                // {
                 echo json_encode(array(
                    'status' => false,
                    'message' => $ereponse,
                    'action'=> 'retry',
                    ));          
        }         
    }  

    public function po_unhide()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 1;die;
            // print_r($this->session->userdata());die;
            $refno = $this->input->post('refno');
            $loc = $this->input->post('loc');
            $reason = $this->input->post('reason');
            $module_group_guid  = $_SESSION['module_group_guid'];
            $user_guid = $_SESSION['user_guid'];


            if($reason == '' || $reason == null)
            {
                // echo 1;die;
                $this->db->query("UPDATE b2b_summary.pomain set status = '',hide_reason = '',b2b_status = 'readysend' where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
                $affected_rows = $this->db->affected_rows();
                $change_to = 'NEW';

            }
            elseif($reason == 'rejected')
            {
                // echo 2;die;
                $change_value = 'rejected';
                $this->db->query("UPDATE b2b_summary.pomain set status = '$change_value',rejected = '1',rejected_at=NOW(),b2b_status = 'readysend' where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
                $affected_rows = $this->db->affected_rows();
                $change_to = $change_value;
            }
            elseif($reason == 'accepted')
            {
                // echo 3;die;
                $change_value = 'Accepted';
                $this->db->query("UPDATE b2b_summary.pomain set status = '$change_value',b2b_status = 'readysend' where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
                $affected_rows = $this->db->affected_rows();
                $change_to = $change_value;
            }
            else
            {
                // echo 4;die;
                $this->db->query("UPDATE b2b_summary.pomain set status = '$reason' where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
                $affected_rows = $this->db->affected_rows();
                $change_to = $reason;
            }

            // $this->db->query("UPDATE b2b_summary.pomain set status = '$reason' where refno = '$refno' and customer_guid = '".$_SESSION['customer_guid']."'");
            $this->db->query("INSERT INTO  userlog_new (trans_guid,customer_guid,user_guid,module_group_guid,module_group_description,section,field,value,value_from,value_to,created_at,created_by) VALUES (UPPER(REPLACE(UUID(),'-','')), '".$_SESSION['customer_guid']."' ,'$user_guid','".$_SESSION['module_group_guid']."', 'Panda B2B' , 'UNHIDE_PO' , 'status' ,'$refno','HFSP' , '$change_to' , NOW() , '".$_SESSION['userid']."')");
            // echo $this->db->last_query();die;

            //echo $_SESSION['frommodule']."?status=&loc=".$loc;die;
            // echo "<script> alert('Unhide Document Successfully')</script>";
            // echo "<script>
            // alert('Unhide Document Successfully');
            // </script>";
            // redirect('PO_hide/view_status?status=HFSP&loc=BDC&p_f=&p_t=&e_f=&e_t=&r_n=');
            if($affected_rows > 0)
            {
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('Unhide Document Successfully');
                window.location.href='".site_url().'/PO_hide/view_status?status=HFSP&loc='.$loc.'&p_f=&p_t=&e_f=&e_t=&r_n='."';
                </script>");
            }
            else
            {
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('Unhide Document Unsuccessfully');
                window.location.href='".site_url().'/PO_hide/view_status?status=HFSP&loc='.$loc.'&p_f=&p_t=&e_f=&e_t=&r_n='."';
                </script>");
            }

        }
        else
        {
            redirect('#');
        }
    }



    public function create_close_notification()
    {
        $customer_guid = $this->session->userdata('customer_guid');
        $user_guid = $this->session->userdata('user_guid');
        $now = $this->db->query("SELECT NOW() as now")->row('now');

        $data = array(
            'customer_guid' => $customer_guid,
            'user_guid' => $user_guid,
            'close_status' => 1,
            'closed' => $now,
        );

        $check_exist = $this->db->query("SELECT * FROM set_supplier_user_relationship_close WHERE customer_guid = '$customer_guid' AND user_guid = '$user_guid' ");

        if($check_exist->num_rows() <= 0)
        {
            $this->db->insert("set_supplier_user_relationship_close",$data);
        }
        else
        {
            $this->db->query("UPDATE set_supplier_user_relationship_close SET close_status = 1 WHERE customer_guid = '$customer_guid' AND user_guid = '$user_guid' ");
        }

    }//close create_close_notification



    public function check_for_close_notification()
    {
        $customer_guid = $this->session->userdata('customer_guid');
        $user_guid = $this->session->userdata('user_guid');

        $check_exist = $this->db->query("SELECT * FROM set_supplier_user_relationship_close WHERE customer_guid = '$customer_guid' AND user_guid = '$user_guid' ");

        if(($check_exist->num_rows() > 0) && ($check_exist->row('close_status') == 1) )
        {
            $data = array(
                'close_status' => 1
            );
        }
        elseif(($check_exist->num_rows() > 0) && ($check_exist->row('close_status') == 0) )
        {
            $data = array(
                'close_status' => 0
            );
        }
        else
        {
            $data = array(
                'close_status' => 0
            );
        }

        echo json_encode($data);

    }//close check_for_close_notification


    public function acc_doc_merge_pdf()
    {

        require(APPPATH.'third_party/PDFMerger-master/PDFMerger.php');
        $database1 = 'lite_b2b';
        $database2 = 'b2b_summary';
        $table1 = 'acc';
        $customer_guid = $this->session->userdata('customer_guid');
        $pdf = new PDFMerger;
        $list_id = $this->input->post('id');
        $code = $this->input->post('code');
        $action = $this->input->post('action');
        // echo $code;die;
        $xrefno = '';
        $to_delete = '';
        $user_guid = $this->session->userdata('user_guid');
        foreach($list_id as $row)
        {

            $filename = $this->db->query("SELECT * FROM $database2.other_doc WHERE refno = '$row' AND customer_guid = '$customer_guid' AND doctype = '$code' LIMIT 1");

            $virtual_path = $this->db->query("SELECT * FROM $database1.$table1 WHERE acc_guid = '$customer_guid' ");
            $vpath = $virtual_path->row('rest_url');
            $acc_sys_type = $this->db->query("SELECT accounting_doc FROM acc WHERE acc_guid = '$customer_guid'")->row('accounting_doc');

            if($acc_sys_type == 'nav')
            {
                $path = $vpath.'/'.'Document_download?refno='.$filename->row('refno').'&doctype='.$filename->row('doctype').'&supcode='.$filename->row('supcode').'&doctime='.$filename->row('doctime');
            }
            else
            {
                $path = $vpath.'/'.'Document_autocount_download?refno='.urlencode(str_replace('/','',$filename->row('refno'))).'&doctype='.$filename->row('doctype').'&supcode='.str_replace('/','',$filename->row('supcode')).'&doctime='.$filename->row('doctime');
            }  
            $to_shoot_url = str_replace(' ','%20',$path);
            // http://18.139.87.215/rest_api/index.php/return_json/Document_download?refno=270118SM2PSPR0077&doctype=SIN&supcode=27Q006&doctime=2020-09-09%2023:15:00
            // echo $to_shoot_url;die;
            $ch = curl_init($to_shoot_url);

            $headers = [
                'x-api-key: codex1234',
                'Content-Type: application/x-www-form-urlencoded'               
            ];

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);     
            curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);        
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
       
            $result = curl_exec($ch);
            
            curl_close($ch);
            // echo $result;die;
            // return $result;          
            $result = $result;
            $b64Doc = $result;
            // echo $b64Doc;die;
            // echo str_replace('\r\n', '', $b64Doc);die;
            // echo base64_decode(str_replace('\r\n', '', $b64Doc));die;
            $pdf_b64 = base64_decode(str_replace('\r\n', '', $b64Doc));
            // $rute = 'http://192.168.10.29/github/panda_b2b/uploads/tfvaluemart/rtrtr.pdf';
            // $rute = '192.168.10.30/panda_web/haha.pdf';
            $uid = $code.'_'.str_replace('/', '', $row);
            $rute = 'merge/'.$uid.'.pdf';
            if(file_put_contents($rute, $pdf_b64)){
            }

            $pdf->addPDF($rute, 'all');
 
            $xrefno .= $row . ',';    
            $to_delete .= $code.'_'.$row.'--++--';
 
        }    
        $xxrefno = rtrim($xrefno,",");
        if (!is_dir('././merge'))
        {
            mkdir('././merge', 0777,true);
            // echo 1;die;
        }
        $pdf_name = 'MERGE_'.uniqid();
        $pdf->merge('file', 'merge/'.$pdf_name.'.pdf'); // generate the file
        $link_url = site_url('Panda_other_doc/direct_print_merge?trans='.$xxrefno.'&pdfname='.$pdf_name.'&action='.$action);
        $pdf_file = $pdf_name;

        $loop_delete_array = rtrim($to_delete,'--++--');
        // echo $to_delete;die;
        $loop_delete = explode('--++--',$loop_delete_array);
        foreach($loop_delete as $row2)
        {
            // echo 1;
            // echo $row2;
            unlink('merge/'.$row2.'.pdf');
        }

        if(!in_array('!VODSUPPMOV',$_SESSION['module_code']))
        {
            foreach($list_id as $row3)
            {       
                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'printed_$code'
                , 'other_doc'
                , '$row3'
                , now()
                ");          
                $this->db->query("UPDATE $database2.other_doc SET status = 'printed' WHERE refno = '$row3' AND customer_guid = '$customer_guid' AND doctype = '$code'");
            }  
        }       

        echo json_encode(array("link_url" => $link_url,"pdf_file" => $pdf_file));
        die;
    }    
}
