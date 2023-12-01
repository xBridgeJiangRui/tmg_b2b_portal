<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sign_upload_doc extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
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

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $acc_guid = $_SESSION['customer_guid'];

            $supplier = $this->db->query("SELECT supplier_name,supplier_guid FROM lite_b2b.set_supplier WHERE isactive = '1' ORDER BY supplier_name ASC");

            $ann_data = $this->db->query("SELECT * FROM lite_b2b.announcement WHERE upload_docs = '1' AND customer_guid = '$acc_guid' ");

            $data = array(
                'supplier' => $supplier->result(),
                'ann_data' => $ann_data->result(),

            );
            $this->load->view('header');
            $this->load->view('Sign_upload/doc_upload_list', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function doc_list_table()
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
            0=>'document_guid',
            1=>'title',
            2=>'supplier_name',
            3=>'user_name',
            4=>'created_by',
            5=>'created_at',
            //6=>'upload_docs',
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

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;
        //$group_query = " GROUP BY b.supplier_guid ";
        $acc_guid = $_SESSION['customer_guid'];

        $sql = "SELECT *,GROUP_CONCAT(aa.user_name) AS all_users FROM (SELECT a.*, b.title ,b.upload_docs,c.supplier_name,d.user_name,d.user_id FROM lite_b2b.`sign_document` a INNER JOIN lite_b2b.`announcement` b ON a.`announcement_guid` = b.`announcement_guid` LEFT JOIN lite_b2b.set_supplier c ON a.supplier_guid = c.supplier_guid INNER JOIN lite_b2b.set_user d ON a.user_guid = d.user_guid WHERE a.customer_guid = '$acc_guid' GROUP BY a.user_guid ORDER BY a.created_at DESC) aa GROUP BY aa.supplier_guid ";

        $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

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
        foreach($result->result() as $row)
        {
            
            $nestedData['document_guid'] = $row->document_guid;
            $nestedData['announcement_guid'] = $row->announcement_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['title'] = $row->title;
            $nestedData['url_value'] = $row->url_value;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['upload_docs'] = $row->upload_docs;
            $nestedData['user_name'] = $row->user_name;
            $nestedData['user_id'] = $row->user_id;
            $nestedData['all_users'] = $row->all_users;
            
            $data[] = $nestedData;

        }

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);
    }

    public function fetch_user()
    {
      $customer_guid = $_SESSION['customer_guid'];
      $supplier_guid = $this->input->post('type_val');
      $Code = $this->db->query("SELECT b.user_name,b.user_id, b.user_guid FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid WHERE  a.`supplier_guid` = '$supplier_guid' AND a.customer_guid = '$customer_guid' GROUP BY a.supplier_guid , a.customer_guid , b.user_guid ");
      
      $data = array(
          'Code' => $Code->result()
      );

      echo json_encode($data);
    }

    //admin upload docs
    public function upload_docs_list()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 
        ini_set('post_max_size', '10M');
        ini_set('upload_max_filesize', '10M');
        //set php.ini upload_max_filesize=64M

        $acc_guid = $_SESSION['customer_guid'];
        //$user_guid = $_SESSION['user_guid'];
        $file_name = $this->input->post('file_name');
        $announcement_guid = $this->input->post('announcement_guid');
        $supplier_guid = $this->input->post('supplier_guid');
        $user_guid = $this->input->post('user_guid');
        $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid,'web','sign_docs','main_path','SIGND');
        $file_config_full_path = $this->file_config_b2b->file_path_name($acc_guid,'web','sign_docs','full_path','SIGNDFLP');
        $foreach_users = explode(",",$user_guid);

        foreach ($foreach_users as $user_guid) 
        {   
            if($supplier_guid != 'no_data')
            {
                $check_doc_upload_supplier = $this->db->query("SELECT * FROM lite_b2b.sign_document WHERE announcement_guid = '$announcement_guid' AND supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND user_guid = '$user_guid' ");
                //echo $this->db->last_query(); die;
                if($check_doc_upload_supplier->num_rows() > 0)
                {
                    $data = array(
                    'para1' => 1,
                    'msg' => 'Upload Error. Already Upload by this Supplier.',
                    );    
                    echo json_encode($data); 
                    exit();
                }  
            }
        }

        $cur_date = $this->db->query("SELECT now() as now")->row('now');
        $retailer = $this->db->query("SELECT acc_name from lite_b2b.acc where acc_guid = '$acc_guid'");
        $created_at = $this->db->query("SELECT now() as now")->row('now');
        
        $controller = $this->router->fetch_class();
        $function = $this->router->fetch_method();

        if($retailer->num_rows() == 0)
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Error retailer.',
            );    
            echo json_encode($data); 
            exit();
        }

        $check_settings = $this->db->query("SELECT * FROM lite_b2b.announcement WHERE announcement_guid = '$announcement_guid'");

        $upload_docs_value = $check_settings->row('upload_docs');

        if($upload_docs_value == '0')
        {
            $data = array(
                'para1' => 1,
                'msg' => 'Upload Error. Please contact support due to annoucement settings not correct.',
                );    
                echo json_encode($data); 
                exit();
        }

        //print_r($user_guid); die;   

        $file_name = str_replace(' ','_',$file_name);  
        $file_name = str_replace('&','',$file_name); 
        $defined_path_acc = $file_config_main_path.$acc_guid.'/';  
        $defined_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/';  
        $defined_path_1 = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/';

        $extension = explode('.', $file_name );

        if(count($extension) > 2)
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Error File Name. Please remove comma dot for naming',
            );    
            echo json_encode($data); 
            exit();
        }

        if(!file_exists($defined_path_acc)){
        mkdir($defined_path_acc,0777);
        }

        if(!file_exists($defined_path)){
        mkdir($defined_path,0777);
        }

        if(!file_exists($defined_path_1)){
        mkdir($defined_path_1,0777);
        }

        //if want add date uncomment here @@@@@
        // $cur_date = str_replace(' ','_',$cur_date); 
        // $cur_date = str_replace(':','',$cur_date);
        // $file_name = $cur_date.'_'.$file_name;

        $unlink_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name;
        $unlink_path_check = $file_config_full_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name.'';

        // if(file_exists($unlink_path)){
        // unlink($unlink_path);
        // }

        $check_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name;

        if (file_exists($check_path)) {
            $data = array(
            'para1' => 1,
            'msg' => 'Document File Name Exists.',
            );    
            echo json_encode($data); 
            exit();
        }

        //$url_link = 'https://b2b.xbridge.my/sup_doc/'.$acc_guid.'/'.$file_name.'';
        //'http://office.panda-eco.com:18243/panda_b2b/sup_doc/'.$acc_guid.'/'.$announcement_guid.'/'.$user_guid.'/'.$file_name.''
        $url_link = $file_config_full_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name.'';

        $config = array(
            'upload_path'   => $defined_path_1,
            'allowed_types' => '*',
            'max_size'     => 500000000,    
            'file_name' => $file_name,                   
        );

        // $config['upload_path']          = $defined_path_1;
        // $config['allowed_types']        = '*';
        // $config['max_size']             = 500000000;
        // $config['file_name'] = $file_name;
        
        $config_run = $this->load->library('upload', $config);

        if(!$this->upload->do_upload('file'))
        {
            $error = array('error' => $this->upload->display_errors());

            if(null != $error)
            {   
              $data = array(
              'para1' => 1,
              'msg' => $this->upload->display_errors(),
              );    
              echo json_encode($data); 
              exit();
            }//close else

        }else
        {
            $data = array('upload_data' => $this->upload->data() );
    
            //$filename = $defined_path_1.$data['upload_data']['file_name'];
            
            if($supplier_guid == 'no_data')
            {
                $supplier_guid = null;
            }
            
            if($upload_docs_value == 1)
            {   
                foreach ($foreach_users as $user_guid) 
                {   
                    $document_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

                    $data = array(
                            'document_guid' => $document_guid,
                            'announcement_guid' => $announcement_guid,
                            'customer_guid' => $acc_guid,
                            'user_guid' => $user_guid,
                            'supplier_guid' => $supplier_guid,
                            'url_value' => $url_link,
                            'created_at' => $created_at,
                            'created_by' => $_SESSION['userid'],
                    ); 

                    $this->db->insert('lite_b2b.sign_document', $data);

                    $announcement_guid_c = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

                    $data = array(
                        'announcement_guid_c' => $announcement_guid_c,
                        'announcement_guid' => $announcement_guid,
                        'supplier_guid' => $supplier_guid,
                        'user_guid' => $user_guid,
                        'acknowledge' => '1',
                        'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                        'created_by' => $_SESSION['userid'], 
                    );
                    $this->db->insert('announcement_child_supplier', $data);

                    $check_insert_ann_child = $this->db->query("SELECT * FROM lite_b2b.announcement_child_supplier WHERE announcement_guid_c = '$announcement_guid_c' AND user_guid = '$user_guid'");

                    $logs_1 = array(
                      'logs_controller' => $controller,
                      'logs_function' => $function,
                      'logs_query' => $this->db->last_query(),
                      'logs_details' => json_encode($check_insert_ann_child->result()),
                      'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                      'created_by' => $_SESSION['userid'],
                      'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                      'announcement_guid' => $announcement_guid,
                      'document_guid' => $document_guid,
                    );   
                    $this->db->insert('lite_b2b.sign_document_logs',$logs_1);
                }
            }
        }

            $error = $this->db->affected_rows();

            if($error > 0){

                $data = array(
                   'para1' => 0,
                   'msg' => 'Upload Completed.',
                   //'link' => $url_link,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 1,
                'msg' => 'Error Insert Data.',
                //'link' => 'Unknown URL.',

                );    
                echo json_encode($data);  
                exit(); 
            }
        
    }

    public function doc_upload_sites()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $announcement_guid = $_REQUEST['announcement_guid'];
            $acc_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];

            $check_url = $this->db->query("SELECT a.*, b.supplier_name,b.supplier_guid FROM lite_b2b.sign_document a LEFT JOIN lite_b2b.set_supplier b ON a.supplier_guid = b.supplier_guid WHERE announcement_guid = '$announcement_guid' and a.user_guid = '$user_guid'");
            $file_name = $check_url->row('url_value');
            $check_num_rows = $check_url->num_rows();
            

            $check_ann = $this->db->query("SELECT * FROM lite_b2b.announcement WHERE announcement_guid = '$announcement_guid'");
            $announcement_title = $check_ann->row('title');
            $upload_docs_value = $check_ann->row('upload_docs');

            $get_supplier = $this->db->query("SELECT b.`supplier_guid`,b.`supplier_name` FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.set_supplier b ON a.`supplier_guid` = b.`supplier_guid` WHERE a.user_guid = '$user_guid' AND a.customer_guid = '$acc_guid' GROUP BY a.supplier_guid");

            $supplier_num_rows = $get_supplier->num_rows();

            $data = array(
                'announcement_guid' => $announcement_guid,
                'check_url' => $check_url->result(),
                'file_name' => $file_name,
                'check_num_rows' => $check_num_rows,
                'supplier_num_rows' => $supplier_num_rows,
                'get_supplier' => $get_supplier->result(),
                'upload_docs_value' => $upload_docs_value,
                'announcement_title' => $announcement_title,

            );
            $this->load->view('header');
            $this->load->view('Sign_upload/doc_upload_sites', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function upload_docs()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 
        ini_set('post_max_size', '10M');
        ini_set('upload_max_filesize', '10M');
        //set php.ini upload_max_filesize=64M
        $acc_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid'];
        $cur_date = $this->db->query("SELECT now() as now")->row('now');
        $retailer = $this->db->query("SELECT acc_name from lite_b2b.acc where acc_guid = '$acc_guid'");
        $created_at = $this->db->query("SELECT now() as now")->row('now');
        $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid,'web','sign_docs','main_path','SIGND');
        $file_config_full_path = $this->file_config_b2b->file_path_name($acc_guid,'web','sign_docs','full_path','SIGNDFLP');
        $controller = $this->router->fetch_class();
        $function = $this->router->fetch_method();
        //$user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');
        //print_r($retailer);die;
        if($retailer->num_rows() == 0)
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Error retailer.',
            );    
            echo json_encode($data); 
            exit();
        }

        $file_name = $this->input->post('file_name');
        $announcement_guid = $this->input->post('announcement_guid');
        $supplier_guid = $this->input->post('supplier_guid');
        $check_settings = $this->db->query("SELECT * FROM lite_b2b.announcement WHERE announcement_guid = '$announcement_guid'");

        $upload_docs_value = $check_settings->row('upload_docs');

        if($upload_docs_value == '0')
        {
            $data = array(
                'para1' => 1,
                'msg' => 'Upload Error. Please contact support due to annoucement settings not correct.',
                );    
                echo json_encode($data); 
                exit();
        }

        if($supplier_guid != 'no_data')
        {
            $check_doc_upload_supplier = $this->db->query("SELECT * FROM lite_b2b.sign_document WHERE announcement_guid = '$announcement_guid' AND supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid'  ");
            //echo $this->db->last_query(); die;
            foreach($check_doc_upload_supplier->result() as $key )
            {
                $check_path      = $key->url_value;
                $delete_doc_gui = $key->document_guid;
                $old_user_guid      = $key->user_guid;
                $tmp           = explode('/', $check_path);
                $old_file_name = end($tmp);

                $unlink_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$old_file_name;

                if(file_exists($unlink_path)){
                unlink($unlink_path);
                }

                $check_remove = $this->db->query("SELECT * FROM lite_b2b.sign_document WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND user_guid = '$old_user_guid'");

                $logs_3 = array(
                  'logs_controller' => $controller,
                  'logs_function' => 'delete_ann',
                  'logs_query' => $this->db->last_query(),
                  'logs_details' => json_encode($check_remove->result()),
                  'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                  'created_by' => $_SESSION['userid'],
                  'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                  'announcement_guid' => $announcement_guid,
                  'document_guid' => $delete_doc_gui,
                );   
                $this->db->insert('lite_b2b.sign_document_logs',$logs_3);

                $remove = $this->db->query("DELETE FROM lite_b2b.sign_document WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND user_guid = '$old_user_guid'");

                $check_settings = $this->db->query("SELECT * FROM lite_b2b.announcement WHERE announcement_guid = '$announcement_guid'");

                $upload_docs_value = $check_settings->row('upload_docs');

                if($upload_docs_value == 1)
                {
                    if($supplier_guid != 'no_data')
                    {   
                        $remove_1 = $this->db->query("DELETE FROM lite_b2b.announcement_child_supplier WHERE announcement_guid = '$announcement_guid' AND supplier_guid = '$supplier_guid' AND user_guid = '$old_user_guid'");
                    }

                 
                }

            }
 
        }

        //print_r($user_guid); die;   

        $file_name = str_replace(' ','_',$file_name);  
        $file_name = str_replace('&','',$file_name); 
        $defined_path_acc = $file_config_main_path.$acc_guid.'/';  
        $defined_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/';  
        $defined_path_1 = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/';

        $extension = explode('.', $file_name );

        if(count($extension) > 2)
        {
            $data = array(
            'para1' => 1,
            'msg' => 'Error File Name. Please remove comma dot for naming',
            );    
            echo json_encode($data); 
            exit();
        }

        if(!file_exists($defined_path_acc)){
        mkdir($defined_path_acc,0777);
        }
        
        if(!file_exists($defined_path)){
        mkdir($defined_path,0777);
        }

        if(!file_exists($defined_path_1)){
        mkdir($defined_path_1,0777);
        }

        //if want add date uncomment here @@@@@
        // $cur_date = str_replace(' ','_',$cur_date); 
        // $cur_date = str_replace(':','',$cur_date);
        // $file_name = $cur_date.'_'.$file_name; 'https://localhost/b2b_portal/sup_doc/'

        $unlink_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name;
        $unlink_path_check = $file_config_full_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name.'';
        
        // if(file_exists($unlink_path)){
        // unlink($unlink_path);
        // }

        $check_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name;

        if (file_exists($check_path)) {
            $data = array(
            'para1' => 1,
            'msg' => 'Document File Name Exists.',
            );    
            echo json_encode($data); 
            exit();
        }

        //$url_link = 'https://b2b.xbridge.my/sup_doc/'.$acc_guid.'/'.$file_name.'';
        $url_link = $file_config_full_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name.'';

        $config['upload_path']          = $defined_path_1;
        $config['allowed_types']        = '*';
        $config['max_size']             = 500000000;
        $config['file_name'] = $file_name;
        //var_dump( $this->input->post('file') );die; 
        //print_r($this->input->post());die;
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('file'))
        {
            $error = array('error' => $this->upload->display_errors());

            if(null != $error)
            {   
              $data = array(
              'para1' => 1,
              'msg' => $this->upload->display_errors(),
              );    
              echo json_encode($data); 
              exit();
            }//close else

        }else
        {
            $data = array('upload_data' => $this->upload->data());

            //$filename = $defined_path_1.$data['upload_data']['file_name'];

            if($supplier_guid == 'no_data')
            {
                $supplier_guid = null;
            }   

            $get_user_guid = $this->db->query("SELECT b.user_name,b.user_id, b.user_guid FROM lite_b2b.set_supplier_user_relationship a INNER JOIN lite_b2b.set_user b ON a.user_guid = b.user_guid LEFT JOIN lite_b2b.`sign_document` c ON a.user_guid = c.`user_guid` WHERE a.`supplier_guid` = '$supplier_guid' AND a.customer_guid = '$acc_guid' GROUP BY a.supplier_guid , a.customer_guid , b.user_guid");
             
            foreach($get_user_guid->result() as $key)
            {
                $user_guid = $key->user_guid;

                // if($supplier_guid != 'no_data')
                // {
                //     $check_doc_upload_supplier = $this->db->query("SELECT * FROM lite_b2b.sign_document WHERE announcement_guid = '$announcement_guid' AND supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND user_guid = '$user_guid' ");

                //     if($check_doc_upload_supplier->num_rows() > 0)
                //     {
                //         $data = array(
                //         'para1' => 1,
                //         'msg' => 'Upload Error. Already Upload by this Supplier.',
                //         );    
                //         echo json_encode($data); 
                //         exit();
                //     }  
                // }

                $document_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

                $data = array(
                    'document_guid' => $document_guid,
                    'announcement_guid' => $announcement_guid,
                    'customer_guid' => $acc_guid,
                    'user_guid' => $user_guid,
                    'supplier_guid' => $supplier_guid,
                    'url_value' => $url_link,
                    'created_at' => $created_at,
                    'created_by' => $_SESSION['userid'],
                ); 

                $this->db->insert('lite_b2b.sign_document', $data);
                
                if($upload_docs_value == 1)
                {
                    $announcement_guid_c = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
                    //$get_user_guid = $row->user_guid;
                    //print_r($get_user_guid);
                    $data = array(
                        'announcement_guid_c' => $announcement_guid_c,
                        'announcement_guid' => $announcement_guid,
                        'supplier_guid' => $supplier_guid,
                        'user_guid' => $user_guid,
                        'acknowledge' => '1',
                        'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                        'created_by' => $_SESSION['userid'], 
                    );
                    $this->db->insert('announcement_child_supplier', $data);

                    $check_insert_ann_child = $this->db->query("SELECT * FROM lite_b2b.announcement_child_supplier WHERE announcement_guid_c = '$announcement_guid_c' AND user_guid = '$user_guid'");

                    $logs_1 = array(
                      'logs_controller' => $controller,
                      'logs_function' => $function,
                      'logs_query' => $this->db->last_query(),
                      'logs_details' => json_encode($check_insert_ann_child->result()),
                      'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                      'created_by' => $_SESSION['userid'],
                      'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                      'announcement_guid' => $announcement_guid,
                      'document_guid' => $document_guid,
                    );   
                    $this->db->insert('lite_b2b.sign_document_logs',$logs_1);
                }

            }
        }
            $error = $this->db->affected_rows();

            if($error > 0){

                $data = array(
                   'para1' => 0,
                   'msg' => 'Upload Completed.',
                   //'link' => $url_link,
                );    
                echo json_encode($data);   
                exit();
            }
            else
            {   
                $data = array(
                'para1' => 1,
                'msg' => 'Error Insert Data.',
                //'link' => 'Unknown URL.',

                );    
                echo json_encode($data);  
                exit(); 
            }
    }

    public function delete_url_file()
    {
        $acc_guid = $_SESSION['customer_guid'];
        //$user_guid = $_SESSION['user_guid'];
        $cur_date = $this->db->query("SELECT now() as now")->row('now');
        $retailer = $this->db->query("SELECT acc_name from lite_b2b.acc where acc_guid = '$acc_guid'");
        $created_at = $this->db->query("SELECT now() as now")->row('now');
        $document_guid = $this->input->post('document_guid');
        $supplier_guid = $this->input->post('supplier_guid');
        $announcement_guid = $this->input->post('announcement_guid');
        $controller = $this->router->fetch_class();
        $function = $this->router->fetch_method();
        $file_config_main_path = $this->file_config_b2b->file_path_name($acc_guid,'web','sign_docs','main_path','SIGND');
        $check_doc_upload = $this->db->query("SELECT * FROM lite_b2b.sign_document WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND announcement_guid = '$announcement_guid'");
        //echo $this->db->last_query(); die;
        foreach ($check_doc_upload->result() as $key ) 
        {
            $check_path      = $key->url_value;
            $announcement_guid      = $key->announcement_guid;
            $user_guid      = $key->user_guid;
            $tmp           = explode('/', $check_path);
            $file_name = end($tmp);

            $guid = $key->document_guid;

            $unlink_path = $file_config_main_path.$acc_guid.'/'.$announcement_guid.'/'.$supplier_guid.'/'.$file_name;

            // $check_doc_delete = $this->db->query("SELECT * FROM lite_b2b.sign_document WHERE customer_guid = '$acc_guid' AND supplier_guid = '$supplier_guid' AND url_value = '$check_path'");

            // if($check_doc_delete->num_rows() == 1)
            // {
            if(file_exists($unlink_path)){
            unlink($unlink_path);
            }
            //}

            $check_remove = $this->db->query("SELECT * FROM lite_b2b.sign_document WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND user_guid = '$user_guid'");

            $logs_1 = array(
              'logs_controller' => $controller,
              'logs_function' => $function,
              'logs_query' => $this->db->last_query(),
              'logs_details' => json_encode($check_remove->result()),
              'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
              'created_by' => $_SESSION['userid'],
              'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
              'announcement_guid' => $announcement_guid,
              'document_guid' => $document_guid,
            );   
            $this->db->insert('lite_b2b.sign_document_logs',$logs_1);

            $remove = $this->db->query("DELETE FROM lite_b2b.sign_document WHERE supplier_guid = '$supplier_guid' AND customer_guid = '$acc_guid' AND user_guid = '$user_guid'");

            $check_settings = $this->db->query("SELECT * FROM lite_b2b.announcement WHERE announcement_guid = '$announcement_guid' ");

            $upload_docs_value = $check_settings->row('upload_docs');

            if($upload_docs_value == 1)
            {
                if($supplier_guid != 'no_data')
                {   
                    $check_ann_supplier = $this->db->query("SELECT * FROM lite_b2b.announcement_child_supplier WHERE announcement_guid = '$announcement_guid' AND supplier_guid = '$supplier_guid' AND user_guid = '$user_guid'");

                    $logs_2 = array(
                      'logs_controller' => $controller,
                      'logs_function' => $function,
                      'logs_query' => $this->db->last_query(),
                      'logs_details' => json_encode($check_ann_supplier->result()),
                      'created_at' => $this->db->query("SELECT now() as naw")->row('naw'),
                      'created_by' => $_SESSION['userid'],
                      'logs_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                      'announcement_guid' => $announcement_guid,
                      'document_guid' => $document_guid,
                    );   
                    $this->db->insert('lite_b2b.sign_document_logs',$logs_2);

                    $remove_1 = $this->db->query("DELETE FROM lite_b2b.announcement_child_supplier WHERE announcement_guid = '$announcement_guid' AND supplier_guid = '$supplier_guid' AND user_guid = '$user_guid'");
                }
                
            }
                
        }

        $error = $this->db->affected_rows();

        if($error > 0){

            $data = array(
               'para1' => 0,
               'msg' => 'Remove Completed.'
            );    
            echo json_encode($data);   
            exit();
        }
        else
        {   
            $data = array(
            'para1' => 1,
            'msg' => 'No Data To Remove.'
            );    
            echo json_encode($data);  
            exit(); 
        }
    }


    public function upload_docs_user()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            $data = array(
                //'supplier' => $supplier->result(),
            );
            $this->load->view('header');
            $this->load->view('Sign_upload/doc_upload_user', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function doc_list_user_table()
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
            0=>'document_guid',
            1=>'title',
            2=>'supplier_name',
            3=>'user_name',
            4=>'created_by',
            5=>'created_at',
            //6=>'upload_docs',
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

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;

        $acc_guid = $_SESSION['customer_guid'];
        $user_guid = $_SESSION['user_guid'];

        $get_supplier = $this->db->query("SELECT a.`supplier_guid` FROM lite_b2b.set_supplier_user_relationship a WHERE a.user_guid = '$user_guid' AND a.customer_guid = '$acc_guid' GROUP BY a.supplier_guid");

        $supplier_guid = array();

        if($get_supplier->num_rows() != 0)
        {
            foreach ($get_supplier->result() as $key ) 
            {
                $supplier = "'".$key->supplier_guid."'"; 
                $supplier_guid[] = $supplier;   
            
            }
            
            $supplier_guid = implode(",",$supplier_guid);
            $add_query = 'AND a.supplier_guid IN ('.$supplier_guid.')';
        }
        else
        {
            $user_guid_add_dot = "'".$user_guid."'"; 
            $add_query = "AND a.supplier_guid = 'null' AND a.user_guid = ".$user_guid_add_dot."";
        }

        $sql = "SELECT a.*, b.title ,b.upload_docs,c.supplier_name,d.user_name,d.user_id FROM lite_b2b.`sign_document` a INNER JOIN lite_b2b.`announcement` b ON a.`announcement_guid` = b.`announcement_guid` LEFT JOIN lite_b2b.set_supplier c ON a.supplier_guid = c.supplier_guid INNER JOIN lite_b2b.set_user d ON a.user_guid = d.user_guid WHERE a.customer_guid = '$acc_guid' $add_query GROUP BY d.user_guid , a.document_guid";

        $query = "SELECT * FROM ( ".$sql." ) a ".$like_first_query.$like_second_query.$order_query.$limit_query;

        // $import_item_gen_c = $this->db->get("backend.import_item_gen_c");

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
        foreach($result->result() as $row)
        {
            $nestedData['document_guid'] = $row->document_guid;
            $nestedData['announcement_guid'] = $row->announcement_guid;
            $nestedData['supplier_guid'] = $row->supplier_guid;
            $nestedData['supplier_name'] = $row->supplier_name;
            $nestedData['title'] = $row->title;
            $nestedData['url_value'] = $row->url_value;
            $nestedData['created_by'] = $row->created_by;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['upload_docs'] = $row->upload_docs;
            $nestedData['user_name'] = $row->user_name;
            $nestedData['query_guid'] = $row->user_guid;
            $nestedData['session_guid'] = $user_guid;
            $nestedData['user_id'] = $row->user_id;
      
            $data[] = $nestedData;

        }

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);
    }
}
