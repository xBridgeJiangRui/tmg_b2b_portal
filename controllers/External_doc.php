<?php
class External_doc extends CI_Controller
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

    public function list_view()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 1;die;
            $customer_guid = $this->session->userdata('customer_guid');
            $charge_type = $_REQUEST['parameter'];
            $table_name = '';
            $database = 'lite_b2b';
            $module = 'misc_doc';
            $doc_code = 'LV';

            $all_setting = $this->db->query("SELECT * FROM $database.menu_setting WHERE customer_guid = '$customer_guid' AND module = '$module' AND code = '$doc_code' LIMIT 1");
            $seperator = $this->db->query("SELECT * FROM $database.menu_setting WHERE module = 'general_setting' AND code = 'SP' LIMIT 1")->row('link');

            $header_array = explode($seperator,$all_setting->row('table_header'));
            $footer_array = explode($seperator,$all_setting->row('table_child'));
            $footer_string = '';
            foreach($footer_array as $row)
            {
               $footer_string .= '{ "data" : "'.$row.'"},';
            }

            $sorting_array = explode($seperator,$all_setting->row('column_sorting'));
            $sorting_string = '[';
            foreach($sorting_array as $row)
            {
               $sorting_string .= '['.$row.'],';
            }            
            $sorting_string .= ']';
            $status_array = explode($seperator,$all_setting->row('doc_status'));
            $status_string = '';
            foreach($status_array as $row)
            {
                $i = 0;
                $status_val = explode('*',$row);
                foreach($status_val as $row1)
                {
                    if($i == 0)
                    {
                        $status_string_1 = $row1;
                    }
                    else
                    {
                        $status_string .= '<option value="'.$row1.'">'.$status_string_1.'</option>';
                    }
                    $i++;
                }
            }            
            // echo $status_string;die;

            if($charge_type == 'external_doc')
            {
                $table_name = 'External Document';
            }
            else if($charge_type == 'other_doc')
            {
                $table_name = 'Other Document';
            }
            else if($charge_type == 'archived_doc')
            {
                $table_name = 'Archived Document';
            }
            else if($charge_type == 'extra_doc')
            {
                $table_name = 'Document Link';
            }
            else
            {
                $table_name = 'Unknown';
            }

            $data = array(
                'header_data' => $header_array,
                'footer_data' => $footer_string,
                'sorting_data' => $sorting_string,
                'charge_type' => $charge_type,
                'status_string' => $status_string,
                'table_name' => $table_name,
            );

            $this->load->view('header');       
            $this->load->view('External_doc/External_doc_list_view',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function list_view_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        $charge_type = $this->input->post('charge_type');

        $status = $this->input->post('status');
        $refno = $this->input->post('refno');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $database = 'lite_b2b';
        $table = 'acc';
        $customer_guid = $this->session->userdata('customer_guid');
        $module = 'misc_doc';
        $doc_code = 'LV';

        $all_setting = $this->db->query("SELECT * FROM $database.menu_setting WHERE customer_guid = '$customer_guid' AND module = '$module' AND code = '$doc_code' LIMIT 1");

        $seperator = $this->db->query("SELECT * FROM $database.menu_setting WHERE module = 'general_setting' AND code = 'SP' LIMIT 1")->row('link');
 
        if($status == '')
        {
            $status_array = explode($seperator,$all_setting->row('doc_status'));
            $status_string = '';
            foreach($status_array as $row)
            {
                $i = 0;
                $status_val = explode('*',$row);
                foreach($status_val as $row1)
                {
                    if($i == 0)
                    {
                        $status_string_1 = $row1;
                    }
                    else
                    {
                        $status_string .= '<option value="'.$row1.'">'.$status_string_1.'</option>';
                    }
                    $i++;
                }
            }
            $status = $status_string; 
        }

        if($refno == '')
        {
            $refno = 'refno';
        }
        else
        {
            $refno = "'".$refno."'";
        }

        if($date_from == '')
        {
            $date_from = '1990-01-01';
        }

        if($date_to == '')
        {
            $date_to = $this->db->query("SELECT LEFT(NOW(),10) as now")->row('now');
        }

        $header_array = explode($seperator,$all_setting->row('table_child'));

        //$database_setting = $this->db->query("SELECT b2b_database,b2b_hub_database FROM $database.$table WHERE acc_guid = '$customer_guid' LIMIT 1");
        //$database_production = $database_setting->row('b2b_database');
        //$database_hub = $database_setting->row('b2b_hub_database');
        $database_production = 'b2b_summary';

        if(!empty($order))
        {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];
          }
        }

        if($dir != "asc" && $dir != "desc")
        {
          $dir = "desc";
        }

        $valid_columns = $header_array;

        if(!isset($valid_columns[$col]))
        {
          $order = null;
        }
        else
        {
          $order = $valid_columns[$col];
        }

        if($order !=null)
        {   
          $order_query = "ORDER BY " .$order. "  " .$dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if(!empty($search))
        {
          $x=0;
          foreach($valid_columns as $sterm)
          {
              if($x==0)
              {

                $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

              }
              else
              {
                $like_second_query .= "OR $sterm LIKE '%".$search."%'";
              }
              $x++;
          }
           
        }

        $query_supcode = $this->session->userdata('query_supcode');

        $limit_query = " LIMIT " .$start. " , " .$length;

        if(in_array('IAVA',$_SESSION['module_code']))
        {
            $query1 = str_replace('$charge_type', $charge_type,$all_setting->row('table_query'));
        }
        else
        {
            $query1 = str_replace('$charge_type', $charge_type,$all_setting->row('table_query_supplier'));
        }
        $query1 = str_replace('$customer_guid', $customer_guid,$query1);
        $query1 = str_replace('$database_production', $database_production,$query1);
        $query1 = str_replace('$refno', $refno,$query1);
        $query1 = str_replace('$status', $status,$query1);
        $query1 = str_replace('$date_from', $date_from,$query1);
        $query1 = str_replace('$date_to', $date_to,$query1);
        $query1 = str_replace('$sup_code', $query_supcode,$query1);

        $sql = $query1;

        $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

        $result = $this->db->query($query);

        // echo $this->db->last_query();die;

        if(!empty($search))
        {
            $query_filter = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query;
            $result_filter = $this->db->query($query_filter)->result();
            $total = count($result_filter);
        }
        else
        {
            $total = $this->db->query($sql)->num_rows();
        }

        $data = array();
        foreach($result->result() as $row)
        {
            foreach ($row as $header => $value) {
                $nestedData[$header] = $value;
                if($header == 'b2b_action')
                {
                    $i = 0;
                    $val = explode($seperator,$value);
                    $parameter = '?xparameter='.$charge_type;
                    foreach($val as $row1)
                    {
                        if($i == 0)
                        {
                            $val_type = $row1 ;
                        }

                        if($i != 0)
                        {
                            if($i == 1)
                            {
                                $parameter .= '&xparameter'.$i.'='.$row1;
                            }
                            else
                            {
                                $parameter .= '&xparameter'.$i.'='.$row1;
                            }
                        }

                        $i++;
                    }

                    if($val_type == 'button')
                    {
                        if(in_array('IAVA',$_SESSION['module_code']))
                        {
                          $nestedData[$header] = "<a href=".site_url('External_doc/child').$parameter." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a><button type='button' style='margin-left:5px;' class='btn btn-sm btn-danger' id='remove_data' doc_type=".$row->doc_type." charge_type=".$row->charge_type." refno=".$row->refno." supcode=".$row->sup_code." uploaded_at=".$row->created_at."><i class='fa fa-trash'></i></button>";
                        }
                        else
                        {
                          $nestedData[$header] = "<a href=".site_url('External_doc/child').$parameter." style='float:left' class='btn btn-sm btn-info' role='button'><span class='glyphicon glyphicon-eye-open'></span></a>";
                        }
                    }
                    // elseif($val_type == 'checkbox')
                    // {
                    //     $nestedData[$header] = '<input type="checkbox" class="data-check" '.$parameter.'>';
                    // }
                }
            }
            $data[] = $nestedData;

        }

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);
        die;

    }//close record_data_table      

    public function child()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            $customer_guid = $this->session->userdata('customer_guid');
            $user_guid = $this->session->userdata('user_guid');

            $charge_type = $_REQUEST['xparameter'];
            $refno = $_REQUEST['xparameter1'];
            $doc_type = $_REQUEST['xparameter3'];
            $database = 'lite_b2b';
            //$database_setting = $this->db->query("SELECT b2b_database,b2b_hub_database FROM $database.acc WHERE acc_guid = '$customer_guid' LIMIT 1");
            //$database_production = $database_setting->row('b2b_database');
            //$database_hub = $database_setting->row('b2b_hub_database');
            $database_production = 'b2b_summary';

            $result_array = $this->db->query("SELECT *,LEFT(created_at,7) as xdate FROM $database_production.extra_doc WHERE refno = '$refno' AND doc_type = '$doc_type' AND charge_type = '$charge_type' AND customer_guid = '$customer_guid'");
            //echo $this->db->last_query();die;

            $extension = '.pdf';
            $page = $result_array->row('page');
            $sup_code = $result_array->row('SCode');

            $supplier_guid = $this->db->query("SELECT b.supplier_guid FROM $database.set_supplier_group a INNER JOIN $database.set_supplier b ON a.supplier_guid = b.supplier_guid WHERE a.supplier_group_name = '$sup_code' AND a.customer_guid = '$customer_guid' AND b.isactive = 1 LIMIT 1")->row('supplier_guid');
            // echo $this->db->last_query();die;

            // $file_path = base_url().'external_doc/';
            $file_config_sec_path = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','sec_misc_doc','UMDPATH');
            $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');
            $file_path = $file_config_sec_path;

            $file_paths = array();
            for($i=1; $i <= $page; $i++)
            {
                $file_paths[] .= $file_path.'/'.$customer_guid.'/'.$supplier_guid.'/'.$charge_type.'/'.$result_array->row('xdate').'/'.$doc_type.'/'.$refno.$extension;

            }
            //print_r($file_paths); die;
            $home_url = site_url('External_doc/list_view?parameter='.$charge_type);

            $data = array(
                'page' => $page,
                'home_url' => $home_url,
                'refno' => $refno,
                'file_upload_supplier_guid' => $supplier_guid,
                'file_upload_doc_date' => $result_array->row('xdate'),
                'file_paths' => $file_paths, 
                'doc_type' => $doc_type,
            );

            // $data_footer = array(
            //     'activity_logs_section' => 'po'
            // );            
            
            if(!in_array('!SUPPMOV',$_SESSION['module_code']))
            {
                $this->db->query("REPLACE into supplier_movement select 
                upper(replace(uuid(),'-','')) as movement_guid
                , '$customer_guid'
                , '$user_guid'
                , 'viewed_$doc_type'
                , 'external_doc'
                , '$refno'
                , now()
                ");
                
                $this->db->query("UPDATE $database_production.extra_doc SET status = 'viewed' WHERE refno = '$refno' AND doc_type = '$doc_type' AND customer_guid = '$customer_guid'");
            };
            $this->load->view('header');       
            $this->load->view('External_doc/External_doc_pdf',$data);
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function upload_doc()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != ''   && $_SESSION['user_logs'] == $this->panda->validate_login())
        {
            // echo 1;die; && in_array('UEXD',$_SESSION['module_code'])
            $customer_guid = $this->session->userdata('customer_guid');
            $charge_type = $_REQUEST['parameter'];
            $database = 'lite_b2b';
            $module = 'misc_doc';
            $doc_code = 'LV';

            $all_setting = $this->db->query("SELECT * FROM $database.menu_setting WHERE customer_guid = '$customer_guid' AND module = '$module' AND code = '$doc_code' LIMIT 1");
            $seperator = $this->db->query("SELECT * FROM $database.menu_setting WHERE module = 'general_setting' AND code = 'SP' LIMIT 1")->row('link');

            $header_array = explode($seperator,$all_setting->row('table_header'));
            $footer_array = explode($seperator,$all_setting->row('table_child'));
            $footer_string = '';
            foreach($footer_array as $row)
            {
               $footer_string .= '{ "data" : "'.$row.'"},';
            }

            $sorting_array = explode($seperator,$all_setting->row('column_sorting'));
            $sorting_string = '[';
            foreach($sorting_array as $row)
            {
               $sorting_string .= '['.$row.'],';
            }            
            $sorting_string .= ']';
            $status_array = explode($seperator,$all_setting->row('doc_status'));
            $status_string = '';
            foreach($status_array as $row)
            {
                $i = 0;
                $status_val = explode('*',$row);
                foreach($status_val as $row1)
                {
                    if($i == 0)
                    {
                        $status_string_1 = $row1;
                        // echo $status_string_1;die;
                    }
                    else
                    {
                        $status_string .= '<option value="'.$row1.'">'.$status_string_1.'</option>';
                        // echo ''.$status_string;die;
                    }
                    $i++;
                }
               // print_r($status_val);die;
               // $status_string .= $status_val;
            }            

            $data = array(
                'header_data' => $header_array,
                'footer_data' => $footer_string,
                'sorting_data' => $sorting_string,
                'charge_type' => $charge_type,
                'doc_type_description' => $all_setting->row('link_description'),
                'upload_doc_format' => $all_setting->row('upload_doc_format'),
                'upload_doc_format_description' => $all_setting->row('upload_doc_format_description'),
                'status_string' => $status_string,
            );
           
            $this->load->view('header');       
            $this->load->view('External_doc/External_doc_upload_doc',$data);
            $this->load->view('footer');
        }
        else
        {
            $this->session->set_flashdata('message', 'Session Expired! Please relogin');
            redirect('#');
        }
    }

    public function check_format()
    {
        $file_name = rtrim($this->input->post('filename'),'.pdf');
        $doc_type = $this->input->post('doc_type');
        $charge_type = $this->input->post('charge_type');
        $customer_guid = $this->session->userdata('customer_guid');
        $module = 'misc_doc';
        $database = 'lite_b2b';
        $table = 'acc';
        $doc_code = $this->input->post('doc_code');
        //$database_setting = $this->db->query("SELECT b2b_database,b2b_hub_database FROM $database.$table WHERE acc_guid = '$customer_guid' LIMIT 1");
        //$database_production = $database_setting->row('b2b_database');
        //$database_hub = $database_setting->row('b2b_hub_database');
        $database_production = 'b2b_summary';

        $all_setting = $this->db->query("SELECT * FROM $database.menu_setting WHERE customer_guid = '$customer_guid' AND module = '$module' AND code = '$doc_code' LIMIT 1");
        // echo $this->db->last_query();die;

        $file_format_upload = explode('_',$file_name);
        // print_r($file_format_upload);die;

        $file_format = explode('_',$all_setting->row('upload_doc_format'));
        if(count($file_format_upload) <> count($file_format))
        {
            $data = array(
                'status' => 'false',
                'file_name' =>$file_name,
                'message' => 'File Format Error',
            );

            echo json_encode($data);
            die;
        }
        // print_r($file_format);die;
        $i = 0;
        $error_message = '';
        $error_status = 0;
        foreach($file_format as $key=>$row)
        {   
            if($row == 'refno')
            {
                $val = $file_format_upload[$key];
                $pass_refno = $val;
                $check = $this->db->query("SELECT * FROM $database_production.extra_doc WHERE refno = '$val' AND doc_type='$doc_type' AND charge_type ='$charge_type' AND customer_guid = '$customer_guid' ");
                if($check->num_rows() > 0)
                {
                    $error_message .= 'RefNo existed, ';
                    $error_status++; 
                }
            }
            elseif($row == 'supcode')
            {
                $val = $file_format_upload[$key];
                $check = $this->db->query("SELECT * FROM $database.set_supplier_group WHERE customer_guid = '$customer_guid' AND supplier_group_name = '$val'");
                if($check->num_rows() <= 0)
                {
                    $error_message .= 'Sup Code not existed, ';
                    $error_status++; 
                }
            }
            elseif($row == 'date')
            {
                $val = $file_format_upload[$key];
                $check = $this->db->query("SELECT DATE_FORMAT('$val','%Y-%m-%d') as now")->row('now');
                if($check == '' || $check == null)
                {
                    $error_message .= 'Date Format Error, ';
                    $error_status++;
                }
            }
            elseif($row == 'amount')
            {
                $val = $file_format_upload[$key];
                $val = explode('.', $val);
                if(count($val) > 2)
                {
                    $error_message .= 'Amount Error, ';
                    $error_status++;
                }
                else
                {
                    $check_after =  strlen(substr($file_format_upload[$key], strrpos($file_format_upload[$key], '.' )+1));
                    if($check_after > 2)
                    {
                        $error_message .= 'More than 2 decimal, ';
                        $error_status++; 
                    }
                }
            }            
            $i++;
        }
        $error_message = rtrim($error_message,', ');
        if($error_status > 0)
        {
            $data = array(
                'status' => 'false',
                'file_name' => $file_name,
                'pass_refno' => $pass_refno,
                'message' => $error_message,
            );
        }
        else
        {
            $data = array(
                'status' => 'true',
                'file_name' => $file_name,
                'pass_refno' => $pass_refno,
                'message' => 'File Format Correct',
            );
        }
        echo json_encode($data);
    }

    public function upload_doc_ajax()
    {
        $error = 0;
        $module = 'misc_doc';
        $table = 'acc';
        $charge_type = $this->input->post('charge_type');
        $doc_code = $this->input->post('doc_code');
        $doc_type = $this->input->post('doc_type');
        $customer_guid = $this->session->userdata('customer_guid');
        $file = $_FILES['file_upload']['tmp_name'];
        $database = 'lite_b2b';
        $table = 'acc';
        //$database_setting = $this->db->query("SELECT b2b_database,b2b_hub_database FROM $database.$table WHERE acc_guid = '$customer_guid' LIMIT 1");
        //$database_production = $database_setting->row('b2b_database');
        //$database_hub = $database_setting->row('b2b_hub_database');
        $database_production = 'b2b_summary';
        $user_guid = $this->session->userdata('user_guid');
        $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

        $error_meassage = '';
        // echo $target_dir;die;        
        // print_r($file['name']);die;
        $i = 0;
        $check_refno_array = array();
        $insert_query = '';
        $error_message = '';
        // print_r($file);die;
        foreach($file as $row1)
        {
            $file_dash = '-1';
            $file_name = $_FILES["file_upload"]["name"][$i];
            // print_r($_FILES["file_upload"]);die;
            // $target_file = $target_dir . basename($_FILES["file_upload"]["name"][$i]);
            $file_type_extension = '.'.strtolower(pathinfo($_FILES["file_upload"]["name"][$i],PATHINFO_EXTENSION));
            // echo $file_type_extension;die;
            // echo $file_name;die;
            $all_setting = $this->db->query("SELECT * FROM $database.menu_setting WHERE customer_guid = '$customer_guid' AND module = '$module' AND code = '$doc_code' LIMIT 1");
            $file_format_upload = explode('_',rtrim($file_name,$file_type_extension));
            $file_format = explode('_',$all_setting->row('upload_doc_format'));
            $created_at = $this->db->query("SELECT NOW() as now")->row('now');
            $insert_query .= '('."'".$customer_guid."',"."'".$created_at."',"."'".$user_id."',"."'".$doc_type."',"."'".$charge_type."',";
            //$insert_query .= '('."'".$created_at."',"."'".$user_id."',"."'".$doc_type."',"."'".$charge_type."',";
            // print_r($file_format);die;
            foreach($file_format as $key=>$row)
            {
                if($row == 'refno')
                {
                    $val = $file_format_upload[$key];
                    $pass_refno = $val;
                    $insert_query .= "'".$val."',";
                    $check_refno_array[] .= $val;
                }
                elseif($row == 'supcode')
                {
                    $val = $file_format_upload[$key];
                    $pass_supcode = $val;
                    $insert_query .= "'".$val."',";
                }
                elseif($row == 'date')
                {
                    $val = $file_format_upload[$key];
                    $pass_date = $val;
                    $insert_query .= "'".$val."',";
                }
                elseif($row == 'amount')
                {
                    $val = $file_format_upload[$key];
                    $pass_amount = $val;
                    $insert_query .= "'".$val."',";
                }            
                // $i++;
            }  
            // echo $pass_refno.$pass_supcode.$pass_date.$pass_amount;
            $insert_query = rtrim($insert_query,',');
            $insert_query = $insert_query.'),';
            // echo $insert_query; die;

            if ($_FILES["file_upload"]["size"][$i] >= 2000000) 
            {
                $error++;
                $error_message .= "Sorry, your file is too large(".$file_name."),";
            }

            $i++;
        }
        $insert_query = rtrim($insert_query,',');
        // echo $insert_query;
        if(count(array_unique($check_refno_array)) < count($check_refno_array))
        {
            $error++;
            $error_message = 'Ref No Duplicated';
        }

        if($error <= 0)
        {
            $a = 0;
            $execute_query = 0;
            $file_config_final_path = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','misc_doc','UMD');
            foreach($file as $row1)
            {
                $file_dash = '-1';
                $file_name = $_FILES["file_upload"]["name"][$a];
                // $target_file = $target_dir . basename($_FILES["file_upload"]["name"][$a]);
                $file_type_extension = '.'.strtolower(pathinfo($_FILES["file_upload"]["name"][$a],PATHINFO_EXTENSION));
                // echo $insert_query;
                $all_setting = $this->db->query("SELECT * FROM $database.menu_setting WHERE customer_guid = '$customer_guid' AND module = '$module' AND code = '$doc_code' LIMIT 1");
                $file_format = explode('_',$all_setting->row('upload_doc_format'));
                $file_format_upload = explode('_',rtrim($file_name,$file_type_extension));
                foreach($file_format as $key=>$row)
                {
                    if($row == 'refno')
                    {
                        $val = $file_format_upload[$key];
                        $upload_file_refno = $val;
                    }
                    elseif($row == 'supcode')
                    {
                        $val = $file_format_upload[$key];
                        $upload_file_supcode = $val;
                    }
                    elseif($row == "date")
                    {
                        $val = $file_format_upload[$key];
                        $upload_file_date = $val;
                    }
                }
                $upload_file_supplier_guid = $this->db->query("SELECT b.supplier_guid FROM $database.set_supplier_group a INNER JOIN $database.set_supplier b ON a.supplier_guid = b.supplier_guid WHERE a.supplier_group_name = '$upload_file_supcode' AND a.customer_guid = '$customer_guid' AND b.isactive = 1 LIMIT 1")->row('supplier_guid');
                $upload_file_period_code = $this->db->query("SELECT LEFT('$upload_file_date',7) as period_code")->row('period_code');
                $path_seperator = $this->file_config_b2b->path_seperator($customer_guid,'web','general_doc','path_seperator','PS');
                $second_path = $customer_guid.$path_seperator.$upload_file_supplier_guid.$path_seperator.$charge_type.$path_seperator.$upload_file_period_code.$path_seperator.$doc_type.$path_seperator;
                $target_dir = $file_config_final_path.$path_seperator.$second_path;
                // echo $target_dir;die;
                $supplier_array_file = explode($path_seperator,substr($target_dir,0,-1));
                // print_r($supplier_array_file);die;
                $file_path_string = '';
                foreach($supplier_array_file as $row)
                {
                    $file_path_string .= $row.$path_seperator;
                    if(!file_exists($file_path_string))
                    {
                        // echo $row.'<br>';die;
                        mkdir($file_path_string);
                        chmod($file_path_string,0777);
                    }
                }
                // die;

                if (move_uploaded_file($_FILES["file_upload"]["tmp_name"][$a], $target_dir.$upload_file_refno.'.pdf')) 
                {
                    $execute_query++;
                    // echo $upload_file_refno;die;
                    $error_message .= "(".$file_name.") Uploaded ";
                    // echo $error_message;die;

                    $now_time = $this->db->query("SELECT NOW() AS now_time")->row('now_time');

                    $logs_1 = array(
                        'log_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),  
                        'customer_guid' => $customer_guid,
                        'supplier_guid' => $upload_file_supplier_guid,
                        'doc_charge_type' => $charge_type,
                        'doc_type' => $doc_type,
                        'action_type' => 'upload',
                        'file_name' => $file_name,
                        'created_at' => $now_time,
                        'created_by' => $user_id,
                    );   
                    $this->db->insert('lite_b2b.misc_doc_log',$logs_1);
                }

                if($execute_query == count($_FILES["file_upload"]["name"]))
                {
                    $insert_header = explode('_',$all_setting->row('upload_doc_column'));
                    $insert_header_query = 'INSERT INTO '.$database_production.'.extra_doc (customer_guid,created_at,created_by,doc_type,charge_type,';
                    foreach($insert_header as $row)
                    {
                        $insert_header_query .= $row.',';
                    }
                    $insert_header_query = rtrim($insert_header_query,',').') VALUES';
                    $insert_header_query = $insert_header_query.$insert_query;
                    // echo $insert_header_query;die;

                    $this->db->query($insert_header_query);
                }

                $a++;
            }

            $this->db->query("UPDATE $database_production.extra_doc a INNER JOIN b2b_summary.supcus b ON a.scode = b.code AND b.customer_guid= '$customer_guid' SET a.sname = b.name WHERE a.doc_type = '$doc_type' AND (a.sname = '' OR a.sname IS NULL)");
        }

        if($error > 0)
        {
            $status = 'false';
            $error_message = $error_message.', Cannot Proceed';
        }
        else
        {
            $status = 'true';
        }
        $data = array(
            'status' => $status,
            'message' => $error_message,
        );

        echo json_encode($data);die;
    }

    public function remove_doc()
    {
        $customer_guid = $_SESSION['customer_guid'];
        $doc_type = $this->input->post('doc_type');
        $charge_type = $this->input->post('charge_type');
        $refno = $this->input->post('refno');
        $supcode = $this->input->post('supcode');
        $uploaded_at = $this->input->post('uploaded_at');
        $user_guid = $this->session->userdata('user_guid');
        $file_config_final_path = $this->file_config_b2b->file_path_name($customer_guid,'web','general_doc','misc_doc','UMD');
        $user_id = $this->db->query("SELECT a.user_id FROM lite_b2b.set_user a WHERE a.user_guid ='$user_guid'")->row('user_id');

        //$database_setting = $this->db->query("SELECT b2b_database,b2b_hub_database FROM $database.acc WHERE acc_guid = '$customer_guid' LIMIT 1");
        //$database_production = $database_setting->row('b2b_database');
        $database_production = 'b2b_summary';

        $supplier_guid = $this->db->query("SELECT b.supplier_guid FROM $database.set_supplier_group a INNER JOIN $database.set_supplier b ON a.supplier_guid = b.supplier_guid WHERE a.supplier_group_name = '$supcode' AND a.customer_guid = '$customer_guid' AND b.isactive = 1 LIMIT 1")->row('supplier_guid');

        $period_code = $this->db->query("SELECT LEFT('$uploaded_at',7) as period_code")->row('period_code');

        $unlink_path = $file_config_final_path."/$customer_guid/$supplier_guid/$charge_type/$period_code/$doc_type/$refno.pdf";

        if(file_exists($unlink_path)){
            $result_array = $this->db->query("SELECT * FROM $database_production.extra_doc WHERE refno = '$refno' AND doc_type = '$doc_type' AND charge_type = '$charge_type' AND SCode = '$supcode' AND customer_guid = '$customer_guid'");

            if($result_array->num_rows() == '1')
            {
                unlink($unlink_path);
                $update_data = $this->db->query("DELETE FROM $database_production.extra_doc WHERE refno = '$refno' AND doc_type = '$doc_type' AND charge_type = '$charge_type' AND SCode = '$supcode' AND customer_guid = '$customer_guid'");
                
                $now_time = $this->db->query("SELECT NOW() AS now_time")->row('now_time');

                $logs_1 = array(
                    'log_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),  
                    'customer_guid' => $customer_guid,
                    'supplier_guid' => $supplier_guid,
                    'doc_charge_type' => $charge_type,
                    'doc_type' => $doc_type,
                    'action_type' => 'delete',
                    'file_name' => $refno.'.pdf',
                    'created_at' => $now_time,
                    'created_by' => $user_id,
                );   
                $this->db->insert('lite_b2b.misc_doc_log',$logs_1);
        }
            else
            {
                $data = array(
                'para1' => 1,
                'msg' => 'No Data or more than 1 rows.',
                );    
                echo json_encode($data);  
                exit(); 
            }
        }
        else
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Cannot Find The PDF file.',
            );    
            echo json_encode($data);  
            exit(); 
        }

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Remove Succesfully.',
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'Error do Remove.',
            );    
            echo json_encode($data);  
            exit(); 
        }
    }

    public function misc_log()
    {
        if($this->session->userdata('loginuser') == true && in_array('IAVA',$_SESSION['module_code']))
        {
            $customer_guid = $_SESSION['customer_guid'];

            $name = $this->db->query("SELECT * from acc where acc_guid = '".$_SESSION['customer_guid']."'")->row('acc_guid'); 
       
            $data = array (
             'name' => $name,
             'customer_guid' =>$customer_guid,
            );

        }
        else
        {
            redirect('#');
        }

        $this->load->view('header'); 
        $this->load->view('External_doc/External_log_list', $data);  
        $this->load->view('footer');  
    } 


    public function misc_log_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 
        
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $customer_guid = $this->session->userdata('customer_guid');
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";

        if(!empty($order))
        {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];
          }
        }

        if($dir != "asc" && $dir != "desc")
        {
          $dir = "desc";
        }

        $valid_columns = array(
          0=>'acc_name',
          1=>'supplier_name',
          2=>'doc_charge_type',
          3=>'doc_type',
          4=>'file_name',
          5=>'action_type',
          6=>'created_at',
          7=>'created_by',
        );

        if(!isset($valid_columns[$col]))
        {
          $order = null;
        }
        else
        {
          $order = $valid_columns[$col];
        }

        if($order !=null)
        {   
          // $this->db->order_by($order, $dir);

          $order_query = "ORDER BY " .$order. "  " .$dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if(!empty($search))
        {
          $x=0;
          foreach($valid_columns as $sterm)
          {
              if($x==0)
              {
                  // $this->db->like($sterm,$search);

                  $like_first_query = "WHERE $sterm LIKE '%".$search."%'";

              }
              else
              {
                  // $this->db->or_like($sterm,$search);

                  $like_second_query .= "OR $sterm LIKE '%".$search."%'";

              }
              $x++;
          }
               
        }

        $limit_query = " LIMIT " .$start. " , " .$length;

        $sql = "SELECT a.*, b.`acc_name`, c.`supplier_name` FROM lite_b2b.misc_doc_log a INNER JOIN lite_b2b.acc b ON a.`customer_guid` = b.`acc_guid` INNER JOIN lite_b2b.`set_supplier` c ON a.`supplier_guid` = c.`supplier_guid` WHERE a.`created_at` >= CURDATE() - INTERVAL 6 MONTH AND a.`customer_guid` = '$customer_guid' ";

        $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

        $result = $this->db->query($query);
        //echo $this->db->last_query(); die;
        if(!empty($search))
        {
          $query_filter = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query;
          $result_filter = $this->db->query($query_filter)->result();
          $total = count($result_filter);
        }
        else
        {
          $total = $this->db->query($sql)->num_rows();
        }

        $data = array();
        foreach ($result->result() as $row)
        {
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['action_type'] = $row->action_type;
            $nestedData['file_name'] = $row->file_name;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['log_guid'] = $row->log_guid;
            $nestedData['doc_charge_type'] = $row->doc_charge_type;
            $nestedData['doc_type'] = $row->doc_type;

            $data[] = $nestedData;

        }

        $json_data = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );
        
        echo json_encode($json_data); 
    }
} // nothing after this
?>