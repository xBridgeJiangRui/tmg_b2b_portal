<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CusAdmin_controller extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Export_model');
        $this->load->library(array('session'));
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper(array('form','url'));
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('Panda_PHPMailer'); 
    }

    public function annoucement()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array(
                'announcement' => $this->db->query("SELECT * from `announcement` where  customer_guid = '".$_SESSION['customer_guid']."' order by docdate desc limit 100"),
                'form_submit' => site_url('CusAdmin_controller/annoucement'),
                'payment_amount' => $this->db->query("SELECT '' as reason, '' as code "),
                'payment_status' => $this->db->query("SELECT '' as reason, '' as code "),
            );
            $this->load->view('header');
            $this->load->view('cusadmin/cusadmin_setup', $data);    
            $this->load->view('cusadmin/cusadmin_setup_modal', $data);   
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function creat_new()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array(
                'announcement_guid' => $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
                'customer_guid' => $_SESSION['customer_guid'],
                'docdate' => $this->input->post('docdate'),
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->insert('announcement', $data);
            //echo $this->db->last_query();die;
            $this->session->set_flashdata('message', 'Successful');
            redirect('CusAdmin_controller/annoucement');
        }
        else
        {
            redirect('#');
        }
    }

    public function update()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            if($_REQUEST['mode']=='detail')
            {
                $data = array(
                'title' => $this->input->post('title'),
                'docdate' => $this->input->post('docdate'),
                'content' => $this->input->post('content'),
                );   
            };

            if($_REQUEST['mode']=='publish')
            {
                $data = array(
                'publish_at' =>  $this->input->post('published_date'),
                'posted' => '1',
                'posted_at' => $this->db->query("select now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
                'updated_at' =>  $this->db->query("select now() as naw")->row('naw'),
                );   
            };
            
            $this->db->where('announcement_guid', $this->input->post('announcement_guid'));
            $this->db->update('announcement', $data);
           //echo $this->db->last_query();die;
            $this->session->set_flashdata('message', 'Successful');
            redirect('CusAdmin_controller/annoucement');
        }
        else
        {
            redirect('#');
        }
    }

    public function delete_announcement_guid()
    {
        $announcement_guid = $_REQUEST['announcement_guid'];
        $this->db->query("DELETE from  announcement where announcement_guid =  '$announcement_guid'");
        $this->session->set_flashdata('message', 'Successfully Delete Content ');
        redirect('CusAdmin_controller/annoucement');
    }

    public function unpublish_announcement_guid()
    {
        $announcement_guid = $_REQUEST['announcement_guid'];
        $this->db->query("UPDATE  announcement  set posted = '0' where announcement_guid =  '$announcement_guid'");
        $this->session->set_flashdata('message', 'Successfully Withdrew Content ');
        redirect('CusAdmin_controller/annoucement');

    }

    public function supplier_checklist()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {     
            $_SESSION['frommodule'] = 'checklist';
            $title = $this->db->query("SELECT acc_name from lite_b2b.acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_name');

            //$registered = $this->db->query("SELECT COUNT(*)  AS  registered  FROM ( SELECT * FROM b2b_summary.supcus WHERE customer_guid = '".$_SESSION['customer_guid']."' AND block = '0' AND TYPE = 'S') aa LEFT JOIN  (SELECT supplier_group_name, supplier_guid, customer_guid, backend_supcus_guid ,backend_supplier_code FROM set_supplier_group WHERE customer_guid = '".$_SESSION['customer_guid']."' )bb ON aa.code = bb.backend_supplier_code WHERE bb.supplier_guid IS NOT NULL")->row('registered');

            $registered  =  $this->db->query("SELECT count(*) as registered FROM (SELECT * FROM supplier_checklist WHERE customer_guid = '".$_SESSION['customer_guid']."' AND title1  = 'STATUS'  AND value1 = 'PAID') a INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE customer_guid = '".$_SESSION['customer_guid']."' AND Type = 'S') b ON a.supcus_guid = b.supcus_guid")->row('registered');


            $total = $this->db->query("SELECT 
  COUNT(*) AS total  
FROM b2b_summary.supcus  AS a
  LEFT JOIN
  (SELECT * FROM supplier_checklist 
  WHERE customer_guid = '".$_SESSION['customer_guid']."'
  AND title1 = 'IsActive' AND value1 <> '1' GROUP BY customer_guid, scode) b
  ON a.`customer_guid` = b.customer_guid AND a.code = b.scode
WHERE a.customer_guid = '".$_SESSION['customer_guid']."' 
  AND TYPE = 'S' 
    AND b.scode IS NULL
   ORDER BY a.code ASC ")->row('total');

            $percent_total = round(($registered / $total) * 100);

            $paid = $this->db->query("SELECT COUNT(*)  as paid FROM (SELECT * FROM supplier_checklist WHERE customer_guid = '".$_SESSION['customer_guid']."' AND title1  = 'STATUS'  AND value1 LIKE '%PENDING%') a INNER JOIN (SELECT * FROM b2b_summary.supcus WHERE customer_guid = '".$_SESSION['customer_guid']."' AND Type = 'S') b ON a.supcus_guid = b.supcus_guid")->row('paid');

            $percent_paid = round(($paid/$total) * 100);

            $training = $this->db->query("SELECT COUNT(*) as training from supplier_checklist where customer_guid = '".$_SESSION['customer_guid']."' and title1 = 'PAYMENT' and value1 >= '500'" )->row('training');

            $percent_training = round(($training/$total) * 100);

            $data = array(
                'title' => 'Checklist @'.$title,
                'datatable_url' => site_url('CusAdmin_controller/view_table'),
                'percent_total' => $percent_total,
                'registered' => $registered,
                'total' => $total,
                'percent_paid' => $percent_paid,
                'paid' => $paid,
                'percent_training' => $percent_training,
                'training' => $training,
                'payment_amount' => $this->db->query("SELECT module_name, CAST(reason AS SIGNED) AS reason, code FROM lite_b2b.set_setting WHERE module_name = 'PAYMENT_AMOUNT' ORDER BY reason ASC"),
                'payment_status' => $this->db->query("SELECT * from lite_b2b.set_setting where module_name = 'PAYMENT_STATUS'"),
            );

            $this->load->view('header');
            $this->load->view('cusadmin/cusadmin_checklist', $data);    
            $this->load->view('cusadmin/cusadmin_setup_modal', $data);   
            $this->load->view('footer');
            //$this->db->query("select * from set_user limit 1");
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

            $customer_guid = $_SESSION['customer_guid'];
            $url = site_url('CusAdmin_controller/supplier_folder');
          //  echo $customer_guid;die;
            $columns = array( 
                            0 =>'code',
                            1 =>'AccountCode', 
                            2 =>'name',
                            3 =>'sup_name',
                            4 =>'reg_no',
                            5 =>'block',
                            6 =>'remark1',
                            7 => 'PIC',
                            8 => 'tel',
                            9 =>'invoice_no',
                            10 =>'training_pax',
                            11 => 'PAYMENT',
                            12 => 'IsActive',
                            13 => 'STATUS',
                            14 => 'ACCEPT_FORM',
                            15 => 'REG_FORM',
                            16 => 'customer_guid',
                            17 => 'supply_type',
                        );   

            /*//debug mode
            $limit = 20;
            $start = 0;
            $order = 'code';*/

            
            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $columns[$this->input->post('order')[0]['column']];
            

            $dir = $this->input->post('order')[0]['dir'];

            $totalData = $this->General_model->check_supchecklist_query($customer_guid)->num_rows();
            $totalFiltered = $totalData; 
            
            if(empty($this->input->post('search')['value']))
            {        
                $posts = $this->General_model->check_supchecklist_result($limit,$start,$customer_guid,$dir,$order);
            //echo $this->db->last_query();die;
            }
            else 
            {
                $search = addslashes($this->input->post('search')['value']); 
                $posts =  $this->General_model->posts_supchecklist_search($limit,$start,$customer_guid,$dir,$order,$search );
                $totalFiltered =   $totalData; 
            }
             
            $data = array();
            if(!empty($posts))
            {
                foreach ($posts as $post)
                { 
                    /*$color =  "class = 'btn btn-success'";
                    $folder = "class='fa fa-folder'";*/
                     $rootDir = './checklist/'.$customer_guid.'/'.$post->supcus_guid;
                    if (is_dir($rootDir))
                    {
                        $allFiles  = array_diff(scandir($rootDir . "/"), [".", ".."]);
                        //echo $allFiles[2];die;
                        if(isset($allFiles[2]) != '')
                        {
                            $folder = "class='fa fa-folder-open'";
                            $color = "class = 'btn btn-success'";
                            $check_folder = 1;
                        }
                        else
                        {
                            $folder = "class='fa fa-folder'";
                            $color = "class = 'btn btn-warning'";
                            $check_folder = 0;
                        }
                    } 
                    else
                    {
                        $folder = "class='fa fa-folder'";
                       $color = "class = 'btn btn-warning'";
                       $check_folder = 0;
                    }

                    $supcus= $post->supcus_guid;

                    $nestedData['action'] = "<button title='Edit' onclick='hide_modal()' type='button'  class='btn btn-danger'  data-toggle='modal' data-target='#sup_checklist_action' style='float:left'
                          data-code='".$post->code."' 
                          data-customer_guid='".$post->customer_guid."' 
                          data-supcus_guid='".$post->supcus_guid."'
                          data-pic='".$post->PIC."'
                          data-invoice_no='".$post->invoice_no."'
                          data-sup_name='".$post->sup_name."'
                          data-tel='".$post->tel."'
                          data-remark='".$post->remark."'
                          data-payment='".$post->PAYMENT."'
                          data-isactive='".$post->IsActive."'
                          data-accform='".$post->ACCEPT_FORM."'
                          data-regform='".$post->REG_FORM."'
                          data-training_pax='".$post->training_pax."'
                          data-status='".$post->STATUS."'
                          ><i class='fa fa-pencil'></i></button>


                          <button title='hide' onclick='hide_supplier()' type='button'  class='btn btn-info'  data-toggle='modal' data-target='#sup_hide' style='float:left'
                          data-code='".$post->code."' 
                          data-customer_guid='".$post->customer_guid."' 
                          data-supcus_guid='".$post->supcus_guid."'
                          data-isactive='".$post->IsActive."'
                          data-accform='".$post->ACCEPT_FORM."'
                          data-regform='".$post->REG_FORM."'
                          ><i class='fa fa-eye'></i></button>
 
 

                        <a href='".$url."?supcus_guid=$supcus&customer_guid=$customer_guid' $color ><i $folder></i> Folder</a>


                          ";
                    $nestedData['AccountCode'] = $post->AccountCode;
                    $nestedData['code'] = $post->code;
                    $nestedData['name'] = $post->name;
                    $nestedData['sup_name'] = $post->sup_name;
                    $nestedData['reg_no'] = $post->reg_no;
                    if($post->block == 0)
                    {
                        $nestedData['block'] = 'Not Block';
                    }
                    else
                    {
                        $nestedData['block'] = 'Blocked';   
                    }
                    $nestedData['remark1'] = $post->remark1;
                    $nestedData['PIC'] = $post->PIC;
                    $nestedData['invoice_no'] = $post->invoice_no;
            $nestedData['training_pax'] = $post->training_pax;
                    $nestedData['tel'] = $post->tel;
                    $nestedData['PAYMENT'] = $post->PAYMENT;
                    /*if($post->IsActive == '1')
                    {
                        $nestedData['IsActive'] = "<input type='checkbox' value='1' checked disabled >  ";
                    }
                    else
                    {
                        $nestedData['IsActive'] = "<input type='checkbox' value='0'  disabled >  ";
                    }*/
                    
                    $nestedData['IsActive'] = $post->IsActive;
                    $nestedData['ACCEPT_FORM'] = $post->ACCEPT_FORM;
                    $nestedData['REG_FORM'] = $post->REG_FORM;
                    $nestedData['training_pax'] = $post->training_pax;
                    $nestedData['STATUS'] = $post->STATUS;
                    $nestedData['supply_type'] = $post->supply_type;
                    
                    
                    
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

    public function  action_button()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $this->input->post('customer_guid');
            $supcus_guid = $this->input->post('supcus_guid');
            $code = $this->input->post('code');

            // loop through all variable and make it dynamic variable 
            // fuck it, stop awhile as need to rush this for tf.. 

            /* $get_variable = $this->db->query("SELECT DISTINCT title1 FROM supplier_checklist order by title1 asc"); 
            foreach($get_variable->result() as $row)
            {
                $ echo $row->title = $this->input->post('$'.echo $row->title);
            };*/
            $PIC = $this->input->post('PIC');
            $IsActive = $this->input->post('IsActive');
            $STATUS = $this->input->post('STATUS');
            $PAYMENT = $this->input->post('PAYMENT');
            if($PAYMENT == 0)
            {
                $PAYMENT = '';
            }
            else
            {
                $PAYMENT = $this->input->post('PAYMENT');   
            }
            $tel = $this->input->post('tel');
            $ACCEPT_FORM = $this->input->post('form_a');
            $REG_FORM = $this->input->post('REG_FORM');
            $training_pax = $this->input->post('training_pax');
            if($training_pax == 0)
            {
                $training_pax = '';
            }
            else
            {
                $training_pax = $this->input->post('training_pax');   
            }
            $remark = $this->input->post('remark');
            $invoice_no = $this->input->post('invoice_no');


            //get all the detail for this supplier first
            $get_current_details = $this->db->query("SELECT * from supplier_checklist where supcus_guid = '$supcus_guid'");

            //if got data
            //PIC
            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'PIC',
                'value1' => $this->input->post('PIC'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'IsActive',
                'value1' => $this->input->post('IsActive'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'STATUS',
                'value1' => $this->input->post('STATUS'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'PAYMENT',
                'value1' => $PAYMENT,
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'tel',
                'value1' => $this->input->post('tel'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'sup_name',
                'value1' => $this->input->post('sup_name'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'invoice_no',
                'value1' => $this->input->post('invoice_no'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            
            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'ACCEPT_FORM',
                'value1' => $this->input->post('form_a'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'REG_FORM',
                'value1' => $this->input->post('REG_FORM'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'training_pax',
                'value1' => $training_pax,
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);


            $this->db->set('remark1', $this->input->post('remark'));
            $this->db->where('customer_guid', $customer_guid);
            $this->db->where('supcus_guid', $supcus_guid);
            $this->db->where('title1', 'STATUS');
            $this->db->update('supplier_checklist');
            
            //echo $this->db->last_query();die;
            $this->session->set_flashdata('message', 'Successful');
            redirect('CusAdmin_controller/supplier_checklist');

        }   
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function supplier_folder()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
        $customer_guid = $_REQUEST['customer_guid'];
        $supcus_guid = $_REQUEST['supcus_guid'];

        if($customer_guid == '' || $supcus_guid == '')
        {
            redirect('CusAdmin_controller/supplier_checklist');
        };

        if (!file_exists("./checklist/".$customer_guid)) {
            $oldmask = umask(0);
            mkdir('./checklist/'.$customer_guid, 0777, true); 
            umask($oldmask);

        };

        if (!file_exists("./checklist/".$customer_guid."/".$supcus_guid)) {
            $oldmask = umask(0);
            mkdir('./checklist/'.$customer_guid."/".$supcus_guid, 0777, true); 
            umask($oldmask);
        };


        $title = $this->db->query("SELECT name FROM b2b_summary.supcus WHERE supcus_guid = '$supcus_guid' AND customer_guid= '$customer_guid'")->row('name');   
       // echo $this->db->last_query();die;
        $data = array(
               'title' => $title,
               'supcus_guid' => $supcus_guid,
               'customer_guid' => $customer_guid,
               'url' =>  './checklist/'.$customer_guid.'/'.$supcus_guid,
               'doc_url' => 'checklist/'.$customer_guid.'/'.$supcus_guid,
                );   
        $this->panda->get_uri();
        $this->load->view('header');
        $this->load->view('cusadmin/cusadmin_checklist_c', $data);    
        $this->load->view('footer');
        }
        else
        {
             $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
            
    }


    public function add_image()
    {
        $customer_guid = $_REQUEST['customer_guid'];
        $supcus_guid = $_REQUEST['supcus_guid'];
        $full_url = './checklist/'.$customer_guid.'/'.$supcus_guid;


        if (!file_exists("./checklist/".$customer_guid)) {
            $oldmask = umask(0);
            mkdir('./checklist/'.$customer_guid, 0777, true); 
            umask($oldmask);

        };

        if (!file_exists("./checklist/".$customer_guid."/".$supcus_guid)) {
            $oldmask = umask(0);
            mkdir('./checklist/'.$customer_guid."/".$supcus_guid, 0777, true); 
            umask($oldmask);
        };


        $config['upload_path']          = $full_url;
        $config['allowed_types']        = 'gif|jpg|png|pdf|jpeg|doc|xls|xlsx|txt';
        $config['max_size']             = 60000;
        //$config['file_name'] = $this->db->query("SELECT replace(upper(uuid()),'-','') as guid")->row('guid');
        //$config['file_name'] = $this->upload->data();

        $this->load->library('upload', $config);



        if($this->upload->do_upload('userfile'))
        {
                //$this->thumb($this->upload->data());
                $upload_data = $this->upload->data();
                    
                    //$filename =$upload_data['raw_name']. $upload_data['file_ext'];
                    $filename =$upload_data['file_name']. $upload_data['file_ext'];
                    $thumbname = $upload_data['raw_name'].$upload_data['file_ext'];



            $data = array('upload_data' => $this->upload->data());
            $this->session->set_flashdata('message', "Upload Successful. Do you have any more document to upload?");
            $this->session->set_flashdata('warning', '');

                // delete the ori big size file
            if($upload_data['file_ext'] == '.jpg' || $upload_data['file_ext'] == '.png' || $upload_data['file_ext'] == '.jpeg' || $upload_data['file_ext'] == '.JPG' || $upload_data['file_ext'] == '.PNG' ) 
            {
                $path = $full_url.'/'.$filename.$upload_data['file_ext'];
               // unlink($path);
            };

               /* $this->load->view('header');
                $this->load->view('creditcard/uploadfiles');
                $this->load->view('footer');  */
              redirect("CusAdmin_controller/supplier_folder?customer_guid=".$customer_guid."&supcus_guid=".$supcus_guid);
        }
        else
        {
            $data = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('warning', 'Image fail to upload :'.$this->upload->display_errors());
             $this->session->set_flashdata('message', '');
                /*header("Refresh:0");*/
              redirect("CusAdmin_controller/supplier_folder?customer_guid=".$customer_guid."&supcus_guid=".$supcus_guid);
            
        }


    }

    function thumb($upload_data)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] =$upload_data['full_path'];
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = 500;
        $config['height'] = 500;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();

    }

    public function get_download()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $_REQUEST['customer_guid'];
            $supcus_guid = $_REQUEST['supcus_guid'];
            $title = $_REQUEST['title'];

            $this->panda->get_uri();
            redirect(base_url().'checklist/'.$customer_guid.'/'.$supcus_guid.'/'.$title);
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function unlink()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $_REQUEST['customer_guid'];
            $supcus_guid = $_REQUEST['supcus_guid'];
            $title = $_REQUEST['title'];

            $this->panda->get_uri();
    
            //redirect(base_url().'checklist/'.$customer_guid.'/'.$supcus_guid.'/'.$title);
            $path = './checklist/'.$customer_guid.'/'.$supcus_guid.'/'.$title;
            //$path = base_url().'checklist/'.$customer_guid.'/'.$supcus_guid.'/'.$title;
            unlink($path);
    
            redirect("CusAdmin_controller/supplier_folder?customer_guid=".$customer_guid."&supcus_guid=".$supcus_guid);
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function set_hide()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $this->input->post('customer_guid');
            $supcus_guid = $this->input->post('supcus_guid');
            $code = $this->input->post('code');

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'IsActive',
                'value1' => $this->input->post('IsActive'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'ACCEPT_FORM',
                'value1' => $this->input->post('form_a'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

            $data = array(
                'customer_guid' =>  $customer_guid,
                'supcus_guid' => $supcus_guid,
                'scode' => $code,
                'title1' => 'REG_FORM',
                'value1' => $this->input->post('REG_FORM'),
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'],
                'updated_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'updated_by' => $_SESSION['userid'],
            );
            $this->db->replace('supplier_checklist', $data);

             $this->session->set_flashdata('message', 'Successful');
            redirect('CusAdmin_controller/supplier_checklist');

        }
        else
        {
              $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }



    public function acknowledge()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $announcement_guid  = $this->input->post('announcement_guid');
            $user_guid  = $this->input->post('user_guid');

            $data = array(
                'announcement_guid_c' =>  $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid'),
                'announcement_guid' => $announcement_guid,
                'user_guid' => $user_guid,
                'acknowledge' => '1',
                'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                'created_by' => $_SESSION['userid'], 
            );
            $this->db->insert('announcement_child', $data);

            $this->session->set_flashdata('message', 'Terms Agreed');
            redirect('Dashboard');
        }
        else
        {
            redirect('#');
        } 
    }

    public function cusadmin_settings()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid  = $_SESSION['customer_guid'];
            //$user_guid  = $this->input->post('user_guid');

            $data = array(
                'get_current_settings' =>  $this->db->query("SELECT * from lite_b2b.acc_settings where customer_guid = '$customer_guid'"), 
            );
            //echo $this->db->last_query();die;
            $this->load->view('header');
            $this->load->view('cusadmin/cusadmin_setting', $data);    
            $this->load->view('footer');
            

        }
        else
        {
            redirect('#');
        } 
    }

    public function insert_config()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {

            $GRN_einv_notification_1 = $this->input->post('GRN_einv_notification_1');
            $GRN_auto_einv_days = $this->input->post('GRN_auto_einv_days'); 

            $RB_total_days_accept = $this->input->post('RB_total_days_accept'); 
            $RB_email_notification_1 = $this->input->post('RB_email_notification_1');
            $RB_email_notification_2 = $this->input->post('RB_email_notification_2');
            $RB_auto_gen_dn_days = $this->input->post('RB_auto_gen_dn_days');

            $PRDN_total_days_collect =  $this->input->post('PRDN_total_days_collect');
            $PRDN_auto_generate_DN =  $this->input->post('PRDN_auto_generate_DN');
 
            $customer_guid = $_SESSION['customer_guid']; 
            $check_current = $this->db->query("SELECT * from lite_b2b.acc_settings where customer_guid = '$customer_guid'");
 

            if($RB_email_notification_1 >= $RB_email_notification_2)
            {
                $this->session->set_flashdata('warning', 'Notification 1 cannot be greater or equal to Notification 2'); 
                redirect('CusAdmin_controller/cusadmin_settings');
            };
            if($RB_email_notification_1 >= $RB_auto_gen_dn_days)
            {
                $this->session->set_flashdata('warning', 'Notification 1 cannot be greater or equal to auto generate dn days');
                redirect('CusAdmin_controller/cusadmin_settings');
            };
            if($RB_email_notification_2 >= $RB_auto_gen_dn_days)
            {
                $this->session->set_flashdata('warning', 'Notification 2 cannot be greater or equal to auto generate dn ');
                redirect('CusAdmin_controller/cusadmin_settings');
            };
            if($GRN_auto_einv_days <= 14)
            {
                $this->session->set_flashdata('warning', 'GRN auto invoice day cannot be less than 14 days');
                redirect('CusAdmin_controller/cusadmin_settings');
            }; 
            
            if($check_current->num_rows() > 0)
            {
                $data = array(
                'GRN_einv_notification_1' => $GRN_einv_notification_1,
                'GRN_auto_einv_days' => $GRN_auto_einv_days,
                'RB_total_days_accept' => $RB_total_days_accept,
                'RB_email_notification_1' => $RB_email_notification_1,
                'RB_email_notification_2' => $RB_email_notification_2,
                'RB_auto_gen_dn_days' => $RB_auto_gen_dn_days,
                'PRDN_total_days_collect' => $PRDN_total_days_collect,
                'PRDN_auto_generate_DN' => $PRDN_auto_generate_DN,

                ); 
               // var_dump($data);die;
                $this->db->where('customer_guid', $customer_guid);
                $this->db->update('lite_b2b.acc_settings', $data);  
                $this->session->set_flashdata('message', 'Update Complete');
            }
            else
            {
                $data = array(
                'customer_guid' => $customer_guid,
                'GRN_einv_notification_1' => $GRN_einv_notification_1,
                'GRN_auto_einv_days' => $GRN_auto_einv_days,
                'RB_total_days_accept' => $RB_total_days_accept,
                'RB_email_notification_1' => $RB_email_notification_1,
                'RB_email_notification_2' => $RB_email_notification_2,
                'RB_auto_gen_dn_days' => $RB_auto_gen_dn_days,
                'PRDN_total_days_collect' => $PRDN_total_days_collect,
                'PRDN_auto_generate_DN' => $PRDN_auto_generate_DN,

                );  
                $this->db->insert('acc_settings', $data);  
                $this->session->set_flashdata('message', 'Insert Complete');
            } 
            redirect('CusAdmin_controller/cusadmin_settings');

        } 
        else
        {
            redirect('#');

        }
    }

    public function faq_manual_guide_setup()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array(
                'customer' => $this->db->query("SELECT * FROM lite_b2b.acc ORDER BY acc_name ASC"),
                'manual_guide' => $this->db->query("SELECT * FROM lite_b2b.manual_guide a INNER JOIN lite_b2b.acc b ON a.customer_guid = b.acc_guid"),
                'faq' => $this->db->query("SELECT * FROM lite_b2b.faq"),
            );
            $this->load->view('header');
            $this->load->view('cusadmin/cusadmin_faq_manual_guide_setup', $data); 
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function manual_guide_setup_add()
    {

        $active = $this->input->post('active');
        if ($active != '1') {
            $active = '0';
        }
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $seq = $this->input->post('seq');
        $customer_guid = $this->input->post('customer_guid');
        $lang_type = $this->input->post('lang_type');
        $guid = $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid');
        $now =  $this->db->query("SELECT NOW() as now")->row('now');
        $file_path = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $path = $file_path->row('file_path');

        //button type submit name have to be submit to go in this if condition
        if(isset($_POST['submit'])){
     
                // Count total files
                $countfiles = count($_FILES['file']['name']);

                // Looping all files
                for($i=0;$i<$countfiles;$i++){

                    if ($_FILES['file']['name'][0] != "") {

                        $filename = $title.'_'.$_FILES['file']['name'][$i];
                        /*echo $_SERVER['SERVER_NAME'];die;*/
                        /*$path = base_url('asset/manual_guide/').$filename;*/
                        $path = './asset/manual_guide/'.$path.'/'.$filename;

                            // Upload file
                        move_uploaded_file($_FILES['file']['tmp_name'][$i],$path);

                    }
                    
                }
            }

        $data = array(
        'customer_guid' => $customer_guid,
        'manual_guid' => $guid,
        'active' => $active,
        'title' => $title,
        'description' => $description,
        'file_name' => $filename,
        'lang_type' => $lang_type,
        'seq' => $seq,
        'created_at' => $now,
        'created_by' => $_SESSION['userid'],
        
        );

        $this->db->insert('lite_b2b.manual_guide', $data);

        echo "<script> alert('Successfully Created');</script>";
        echo "<script> document.location='" . base_url() . "index.php/CusAdmin_controller/faq_manual_guide_setup' </script>";



    }

    public function manual_guide_setup_edit()
    {
        $manual_guid = $this->input->post('manual_guid');
        $active = $this->input->post('active');
        if ($active != '1') {
            $active = '0';
        }
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $lang_type = $this->input->post('lang_type');
        $seq = $this->input->post('seq');
        $old_file_name = $this->input->post('file_name');
        $old_customer_guid = $this->input->post('old_customer_guid');
        $customer_guid = $this->input->post('customer_guid');
        $now =  $this->db->query("SELECT NOW() as now")->row('now');
        // echo $old_file_name;die;
        $old_file_path = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$old_customer_guid'");
        $old_path = $old_file_path->row('file_path');


        $old_file_path = './asset/manual_guide'.$old_path.'/'.$old_file_name;

        $file_path = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $path = $file_path->row('file_path');

        
        

        //button type submit name have to be submit to go in this if condition
        if(isset($_POST['submit'])){
     
                // Count total files
                $countfiles = count($_FILES['file']['name']);

                // Looping all files
                for($i=0;$i<$countfiles;$i++){
                    if ($_FILES['file']['name'][0] != "") {


                        if ($old_file_name != '') {
                            unlink($old_file_path);
                        }

                        $filename = $title.'_'.$_FILES['file']['name'][$i];
                        /*echo $_SERVER['SERVER_NAME'];die;*/
                        /*$path = base_url('asset/manual_guide/').$filename;*/
                        /*$path = '../lite_panda_b2b/asset/manual_guide/'.$filename;*/
                        $path = './asset/manual_guide'.$path.'/'.$filename;

                            // Upload file
                        move_uploaded_file($_FILES['file']['tmp_name'][$i],$path);

                    }

                    else {
                        $path = './asset/manual_guide'.$path.'/'.$old_file_name;
                        // echo base_url().$old_file_path.'<br>'.$path;die;
                        rename($old_file_path,$path);

                        $filename = $old_file_name;

                    }
                    
                }
            }

        $data = array(
        'customer_guid' => $customer_guid,
        'active' => $active,
        'title' => $title,
        'description' => $description,
        'file_name' => $filename,
        'lang_type' => $lang_type,
        'seq' => $seq,
        'updated_at' => $now,
        'updated_by' => $_SESSION['userid'],
        
        );

        $this->db->where('customer_guid', $old_customer_guid);
        $this->db->where('manual_guid', $manual_guid);
        $this->db->update('lite_b2b.manual_guide', $data);
        // echo $this->db->last_query();die;

        echo "<script> alert('Successfully Edit');</script>";
        echo "<script> document.location='" . base_url() . "index.php/CusAdmin_controller/faq_manual_guide_setup' </script>";



    }

        public function manual_guide_setup_delete()
    {
        $manual_guid = $this->input->post('manual_guid');
        $file_name = $this->input->post('file_name');
        $customer_guid = $this->input->post('customer_guid');
        $file_path = $this->db->query("SELECT * FROM lite_b2b.acc WHERE acc_guid = '$customer_guid'");
        $path = $file_path->row('file_path');
        $file_path1 = './asset/manual_guide'.$path.'/'.$file_name;

        unlink($file_path1);

        $this->db->where('manual_guid', $manual_guid);
        $this->db->where('customer_guid', $customer_guid);
        $this->db->delete('lite_b2b.manual_guide');

        // echo $this->db->last_query();die;

        echo "<script> alert('Successfully Delete');</script>";
        echo "<script> document.location='" . base_url() . "index.php/CusAdmin_controller/faq_manual_guide_setup' </script>";



    }

    public function faq_setup_add()
    {

        $active = $this->input->post('active');
        if ($active != '1') {
            $active = '0';
        }
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $lang_type = $this->input->post('lang_type');
        $seq = $this->input->post('seq');
        $guid = $this->db->query("SELECT UPPER(REPLACE(UUID(),'-','')) as guid")->row('guid');
        $now =  $this->db->query("SELECT NOW() as now")->row('now');

        //button type submit name have to be submit to go in this if condition
        if(isset($_POST['submit'])){
     
                // Count total files
                $countfiles = count($_FILES['file']['name']);

                // Looping all files
                for($i=0;$i<$countfiles;$i++){

                    if ($_FILES['file']['name'][0] != "") {

                        $filename = $title.'_'.$_FILES['file']['name'][$i];
                        /*echo $_SERVER['SERVER_NAME'];die;*/
                        /*$path = base_url('asset/faq/').$filename;*/
                        $path = './asset/faq/'.$filename;

                            // Upload file
                        move_uploaded_file($_FILES['file']['tmp_name'][$i],$path);

                    }
                    
                }
            }

        $data = array(
        'faq_guid' => $guid,
        'active' => $active,
        'title' => $title,
        'description' => $description,
        'file_name' => $filename,
        'lang_type' => $lang_type,
        'seq' => $seq,
        'created_at' => $now,
        'created_by' => $_SESSION['userid'],
        
        );

        $this->db->insert('lite_b2b.faq', $data);

        /*echo $this->db->last_query();die;*/

        echo "<script> alert('Successfully Created');</script>";
        echo "<script> document.location='" . base_url() . "index.php/CusAdmin_controller/faq_manual_guide_setup' </script>";



    }

    public function faq_setup_edit()
    {
        $faq_guid = $this->input->post('faq_guid');
        $active = $this->input->post('active');
        if ($active != '1') {
            $active = '0';
        }
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $lang_type = $this->input->post('lang_type');
        $seq = $this->input->post('seq');
        $old_file_name = $this->input->post('file_name');
        $now =  $this->db->query("SELECT NOW() as now")->row('now');

        $old_file_path = './asset/faq/'.$old_file_name;
        

        //button type submit name have to be submit to go in this if condition
        if(isset($_POST['submit'])){
     
                // Count total files
                $countfiles = count($_FILES['file']['name']);

                // Looping all files
                for($i=0;$i<$countfiles;$i++){

                    if ($_FILES['file']['name'][0] != "") {

                        if ($old_file_name != '') {
                            unlink($old_file_path);
                        }

                        $filename = $title.'_'.$_FILES['file']['name'][$i];
                        /*echo $_SERVER['SERVER_NAME'];die;*/
                        /*$path = base_url('asset/faq/').$filename;*/
                        /*$path = '../lite_panda_b2b/asset/faq/'.$filename;*/
                        $path = './asset/faq/'.$filename;

                            // Upload file
                        move_uploaded_file($_FILES['file']['tmp_name'][$i],$path);

                    }

                    else {

                        $filename = $old_file_name;

                    }
                    
                }
            }

        $data = array(
        
        'active' => $active,
        'title' => $title,
        'description' => $description,
        'file_name' => $filename,
        'lang_type' => $lang_type,
        'seq' => $seq,
        'updated_at' => $now,
        'updated_by' => $_SESSION['userid'],
        
        );
        $this->db->where('faq_guid', $faq_guid);
        $this->db->update('lite_b2b.faq', $data);
        echo "<script> alert('Successfully Edit');</script>";
        echo "<script> document.location='" . base_url() . "index.php/CusAdmin_controller/faq_manual_guide_setup' </script>";



    }

        public function faq_setup_delete()
    {
        $faq_guid = $this->input->post('faq_guid');
        $file_name = $this->input->post('file_name');
        $file_path = './asset/faq/'.$file_name;

        unlink($file_path);

        $this->db->where('faq_guid', $faq_guid);
        $this->db->delete('lite_b2b.faq');

        echo "<script> alert('Successfully Delete');</script>";
        echo "<script> document.location='" . base_url() . "index.php/CusAdmin_controller/faq_manual_guide_setup' </script>";



    }
 
     
 
}
?>
