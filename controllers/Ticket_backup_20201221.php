<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ticket extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ticket_model');
        $this->load->library('form_validation');        
        $this->load->library('datatables');
         
    }

    public function index()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   

            if ($_SERVER['HTTPS'] !== "on") 
            {
            $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];   
            header("Location: $url");
            } 

            $last_12_month_ticket = $this->db->query("

                SELECT COUNT(*) as Count FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 11 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 11 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 10 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 10 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 9 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 9 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 8 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 8 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 7 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 7 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 6 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 6 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 5 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 5 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 4 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 3 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 2 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)
                            UNION ALL
                SELECT COUNT(*) FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                            UNION ALL
                SELECT COUNT(*) AS ticket_created_count FROM ticket WHERE YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 0 MONTH)
                            AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)




                            ")->result();


                $Topic = $this->db->query("SELECT a.name, COUNT(a.name) AS topic_count FROM ticket_topic a INNER JOIN ticket b ON a.t_topic_guid = b.`topic_guid` GROUP BY name ORDER BY topic_count DESC");

 
                $topic1 = $this->db->query("SELECT 
  a.topic_guid,
  ROUND(
    COUNT(*) / 
    (SELECT 
      COUNT(*) 
    FROM
      ticket) * 100,
    0
  ) AS percent,
  COUNT(*) AS count,
  (SELECT 
    COUNT(*) 
  FROM
    ticket) - COUNT(*) AS others,
  b.name 
FROM
  ticket a 
  INNER JOIN ticket_topic b 
    ON a.topic_guid = b.t_topic_guid 
GROUP BY a.topic_guid ORDER BY count DESC ");

                $all = $this->db->query("SELECT COUNT(*) as count FROM ticket")->row('count');

                $New = $this->db->query("SELECT COUNT(*) as count FROM ticket WHERE ticket_status = 'New'")->row('count');

                $In_Progress = $this->db->query("SELECT COUNT(*) as count FROM ticket WHERE ticket_status = 'In-Progress'")->row('count');

                $Closed = $this->db->query("SELECT COUNT(*) as count FROM ticket WHERE ticket_status = 'Closed'")->row('count');

                $category = $this->db->query("SELECT * FROM ticket_topic ORDER BY name ASC");

                $sub_category = $this->db->query("SELECT * FROM ticket_sub_topic ORDER BY name ASC");




                //  $ticket_child = $this->db->query("
                //     SELECT 
                //   a.hide,
                //   a.ticket_c_guid,                
                //   a.messages_type,
                //   a.messages,
                //   a.created_at,
                //   a.created_by,
                //   a.ticket_file,
                //   a.ticket_path,
                //   b.user_name 
                // FROM
                //   ticket_child a 
                //   INNER JOIN (SELECT * FROM lite_b2b.`set_user` GROUP BY user_guid) b 
                //     ON a.`created_by` = b.`user_guid` 
                // WHERE a.hide = 0
                // ORDER BY created_at ASC ");

                  $ticket = $this->db->query("SELECT DISTINCT e.name as sub_name,d.user_name,c.rr_name ,a.resolved_reason,a.ticket_number,a.ticket_status, b.`name`, a.created_at,f.user_name as created_name, a.ticket_path, a.ticket_file FROM ticket a INNER JOIN ticket_topic b ON a.topic_guid = b.`t_topic_guid` 
                LEFT JOIN ticket_resolved_reason c ON a.resolved_reason = c.rr_guid 
                LEFT JOIN set_user d ON a.assigned = d.`user_guid` 
                INNER JOIN set_user f ON a.created_by = f.`user_guid`
                INNER JOIN ticket_sub_topic e ON a.sub_topic_guid = e.`t_sub_topic_guid` 
                 ");

            $data = array (

                'last_12_month_ticket' => $last_12_month_ticket,
                'Topic' => $Topic->result(),
                'topic_bar' => $Topic,//stacked column chart `month` must have cancel
                'topic1' => $topic1,//stacked column chart `month` must have cancel
                'all' => $all,
                'New' => $New,
                //'New1' => $New1,
                'In_Progress' => $In_Progress,
                //'In_Progress1' => $In_Progress1,
                'Closed' => $Closed,
                //'Closed1' => $Closed1,
                'category' => $category,
                'sub_category' => $sub_category,
                // 'ticket_child' => $ticket_child,
                'ticket' =>$ticket,


            );

            

            $this->load->view('header');
            $this->load->view('ticket/ticket', $data);
            $this->load->view('footer');

        }

        else
        {
            redirect('#');
        }
    }

    public function ticket_table()

    {
            // print_r($this->input->post());
            ini_set('memory_limit', -1); 
            //if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
            {
                $columns = array(
                    0 => 'ticket_number',
                    1 => 'name',
                    2 => 'sub_name',
                    3 => 'ticket_status',
                    4 => 'created_at',
                    5 => 'user_name',
                    6 => 'supplier_group_name',
                    7 => 'supplier_name',
                    8 => 'acc_name',
                    9 => 'assigned_name',
                    10 => 'seq',
                    11 => 'action',

                );
            }
           //else
           //{
           //    $columns = array(
           //        0 => 'ticket_number',
           //        1 => 'name',
           //        2 => 'sub_name',
           //        3 => 'ticket_status',
           //        4 => 'created_at',
           //        5 => 'user_name',
           //        6 => 'seq',
           //        7 => 'action',
           //    );
           //}
            $customer_guid = $_SESSION['customer_guid'];
            $user_guid = $_SESSION['user_guid'];
            $user_group = $_SESSION['user_group_name'];
            $ticket_status = $this->input->post('ticket_status');
            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $this->input->post('order');
            $dir = "";
            $dropdown_ticket_status = $this->input->post('ticket_status_value');
            if($dropdown_ticket_status == '' || $dropdown_ticket_status == 'All')
            {
                $dropdown_ticket_status = "'NEW','In-Progress','Closed'";
            }
            else
            {
                $dropdown_ticket_status = "'".$dropdown_ticket_status."'";
            }
            // echo '*******'.$dropdown_ticket_status.'++++++++++';
            $order_query = "";

            if(!empty($order))
            {
              foreach($order as $o)
              {
                  $col = $o['column'];
                  $dir= $o['dir'];

                  $order_query .= $columns[$col]." ".$dir.",";

              }
            }   
            $dir = '';    
            $order_query = rtrim($order_query,',');

            if (in_array('OAT',$this->session->userdata('module_code'))) {
                $query = "SELECT DISTINCT a.topic_guid,a.sub_topic_guid,a.ticket_guid,CASE WHEN ticket_status = 'New' THEN 5 WHEN ticket_status = 'In-Progress' THEN 4 WHEN ticket_status = 'Closed' THEN 3 END as seq,CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a> <small class=".'pull-right'.">',(SELECT COUNT(*) FROM ticket_child WHERE ticket_guid = a.`ticket_guid` ) ,' <i class=\"fa fa-comments\"></i></small>') as ticket_number,c.name as sub_name,b.name,a.details,a.created_at,d.user_name,a.ticket_status ,f.supplier_name,h.acc_name,g.supplier_group_name, (SELECT messages_type FROM ticket_child WHERE ticket_guid = a.ticket_guid ORDER BY created_at DESC LIMIT 1) AS messages_type,e.user_name  as assigned_name
                    FROM ticket a 
                    INNER JOIN ticket_topic b ON a.topic_guid = b.t_topic_guid 
                    INNER JOIN ticket_child i ON a.ticket_guid = i.ticket_guid 
                    INNER JOIN ticket_sub_topic c ON a.sub_topic_guid = c.t_sub_topic_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) d ON a.created_by = d.user_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) e ON a.assigned = e.user_guid 
                    LEFT JOIN set_supplier f ON a.supplier_guid = f.supplier_guid
                    LEFT JOIN (SELECT * FROM set_supplier_group GROUP BY supplier_guid) g ON f.`supplier_guid` = g.`supplier_guid`
                    LEFT JOIN acc  h ON a.acc_guid = h.acc_guid WHERE a.ticket_status IN($dropdown_ticket_status)";
            }
            else

               if (in_array('OABYCUST',$this->session->userdata('module_code'))) {
                $query = "SELECT DISTINCT a.topic_guid,a.sub_topic_guid,a.ticket_guid,CASE WHEN ticket_status = 'New' THEN 5 WHEN ticket_status = 'In-Progress' THEN 4 WHEN ticket_status = 'Closed' THEN 3 END as seq,CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a> <small class=".'pull-right'.">',(SELECT COUNT(*) FROM ticket_child WHERE ticket_guid = a.`ticket_guid` ) ,' <i class=\"fa fa-comments\"></i></small>') as ticket_number,c.name as sub_name,b.name,a.details,a.created_at,d.user_name,a.ticket_status ,f.supplier_name,h.acc_name,g.supplier_group_name, (SELECT messages_type FROM ticket_child WHERE ticket_guid = a.ticket_guid ORDER BY created_at DESC LIMIT 1) AS messages_type,e.user_name  as assigned_name
                    FROM ticket a 
                    INNER JOIN ticket_topic b ON a.topic_guid = b.t_topic_guid 
                    INNER JOIN ticket_child i ON a.ticket_guid = i.ticket_guid 
                    INNER JOIN ticket_sub_topic c ON a.sub_topic_guid = c.t_sub_topic_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) d ON a.created_by = d.user_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) e ON a.assigned = e.user_guid 
                    LEFT JOIN set_supplier f ON a.supplier_guid = f.supplier_guid
                    LEFT JOIN (SELECT * FROM set_supplier_group GROUP BY supplier_guid) g ON f.`supplier_guid` = g.`supplier_guid`
                    LEFT JOIN acc  h ON a.acc_guid = h.acc_guid  WHERE a.acc_guid = '$customer_guid' AND a.ticket_status IN($dropdown_ticket_status) AND a.hide = 0";
            }
            
            else{
                $query = "SELECT DISTINCT a.topic_guid, a.sub_topic_guid, a.ticket_guid, CASE WHEN ticket_status = 'New' THEN 5 WHEN ticket_status = 'In-Progress' THEN 4 WHEN ticket_status = 'Closed' THEN 3 END AS seq, CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a> <small class=".'pull-right'.">',(SELECT COUNT(*) FROM ticket_child WHERE ticket_guid = a.`ticket_guid` ) ,' <i class=\"fa fa-comments\"></i></small>') AS ticket_number, e.name AS sub_name, d.name, a.details, a.created_at, c.user_name, a.ticket_status, f.supplier_name, g.acc_name, c.user_name AS assigned_name FROM (SELECT * FROM set_user a WHERE a.acc_guid = '$customer_guid' AND a.user_guid = '$user_guid') c INNER JOIN set_supplier_user_relationship b ON c.user_guid = b.`user_guid` INNER JOIN ticket a ON b.supplier_guid = a.supplier_guid INNER JOIN ticket_topic d ON a.topic_guid = d.t_topic_guid INNER JOIN ticket_sub_topic e ON a.sub_topic_guid = e.t_sub_topic_guid INNER JOIN set_supplier f ON a.supplier_guid = f.supplier_guid INNER JOIN acc g ON a.acc_guid = g.acc_guid WHERE a.acc_guid = '$customer_guid' AND a.ticket_status IN($dropdown_ticket_status) AND a.hide = 0";
            }



            if (in_array('OAT',$this->session->userdata('module_code'))) {
                $query2 = "SELECT DISTINCT a.topic_guid,CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a> <small class=".'pull-right'.">',(SELECT COUNT(*) FROM ticket_child WHERE ticket_guid = a.`ticket_guid` ) ,' <i class=\"fa fa-comments\"></i></small>') as ticket_number,c.name as sub_name,b.name,a.details,a.created_at,d.user_name,a.ticket_status ,f.supplier_name,h.acc_name,g.supplier_group_name, i.messages_type,e.user_name  as assigned_name
                    FROM ticket a 
                    INNER JOIN ticket_topic b ON a.topic_guid = b.t_topic_guid 
                    INNER JOIN ticket_child i ON a.ticket_guid = i.ticket_guid 
                    INNER JOIN ticket_sub_topic c ON a.sub_topic_guid = c.t_sub_topic_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) d ON a.created_by = d.user_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) e ON a.assigned = e.user_guid 
                    LEFT JOIN set_supplier f ON a.supplier_guid = f.supplier_guid
                    LEFT JOIN (SELECT * FROM set_supplier_group GROUP BY supplier_guid) g ON f.`supplier_guid` = g.`supplier_guid`
                    LEFT JOIN acc  h ON a.acc_guid = h.acc_guid WHERE a.ticket_status IN($dropdown_ticket_status)";
            }
            else

               if (in_array('OABYCUST',$this->session->userdata('module_code'))) {
                $query2 = "SELECT DISTINCT a.topic_guid,CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a> <small class=".'pull-right'.">',(SELECT COUNT(*) FROM ticket_child WHERE ticket_guid = a.`ticket_guid` ) ,' <i class=\"fa fa-comments\"></i></small>') as ticket_number,c.name as sub_name,b.name,a.details,a.created_at,d.user_name,a.ticket_status ,f.supplier_name,h.acc_name,g.supplier_group_name, i.messages_type,e.user_name  as assigned_name
                    FROM ticket a 
                    INNER JOIN ticket_topic b ON a.topic_guid = b.t_topic_guid 
                    INNER JOIN ticket_child i ON a.ticket_guid = i.ticket_guid 
                    INNER JOIN ticket_sub_topic c ON a.sub_topic_guid = c.t_sub_topic_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) d ON a.created_by = d.user_guid 
                    LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) e ON a.assigned = e.user_guid 
                    LEFT JOIN set_supplier f ON a.supplier_guid = f.supplier_guid
                    LEFT JOIN (SELECT * FROM set_supplier_group GROUP BY supplier_guid) g ON f.`supplier_guid` = g.`supplier_guid`
                    LEFT JOIN acc  h ON a.acc_guid = h.acc_guid  WHERE a.acc_guid = '$customer_guid' AND a.ticket_status IN($dropdown_ticket_status) AND a.hide = 0";
            }
            
            else{
                $query2 = "SELECT DISTINCT a.topic_guid, CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a> <small class=".'pull-right'.">',(SELECT COUNT(*) FROM ticket_child WHERE ticket_guid = a.`ticket_guid` ) ,' <i class=\"fa fa-comments\"></i></small>') AS ticket_number, e.name AS sub_name, d.name, a.details, a.created_at, c.user_name, a.ticket_status, f.supplier_name, g.acc_name, c.user_name AS assigned_name FROM (SELECT * FROM set_user a WHERE a.acc_guid = '$customer_guid' AND a.user_guid = '$user_guid') c INNER JOIN set_supplier_user_relationship b ON c.user_guid = b.`user_guid` INNER JOIN ticket a ON b.supplier_guid = a.supplier_guid INNER JOIN ticket_topic d ON a.topic_guid = d.t_topic_guid INNER JOIN ticket_sub_topic e ON a.sub_topic_guid = e.t_sub_topic_guid INNER JOIN set_supplier f ON a.supplier_guid = f.supplier_guid INNER JOIN acc g ON a.acc_guid = g.acc_guid WHERE a.acc_guid = '$customer_guid' AND a.ticket_status IN($dropdown_ticket_status) AND a.hide = 0";
            }            
            if(empty($this->input->post('search')['value']))
            {
                $posts = $this->Ticket_model->allposts($query,$limit,$start,$order_query,$dir);
                $totalDataquery = $this->Ticket_model->ticket($query,$limit,$start,$order_query,$dir);
                $totalData = count($totalDataquery);
                $totalFiltered = $totalData;
                // echo $this->db->last_query();
            }
            else 
            {
                $search = $this->input->post('search')['value']; 

                $posts =  $this->Ticket_model->posts_search($query,$limit,$start,$search,$order_query,$dir);
                // echo $this->db->last_query();die;

                $totalFiltered = $this->Ticket_model->posts_search_count($query2,$search);
            }

            $data = array();
            if(!empty($posts))
            {
                foreach ($posts as $post)
                {
                    $nestedData['ticket_number'] = $post->ticket_number;
                    $nestedData['name'] = $post->name;
                    $nestedData['sub_name'] = $post->sub_name;
                    $nestedData['ticket_status'] = $post->ticket_status;
                    $nestedData['created_at'] = $post->created_at;
                    $nestedData['user_name'] = $post->user_name;
                    $nestedData['seq'] = $post->seq;

                    //if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') {
                    $nestedData['supplier_group_name'] = $post->supplier_group_name; 
                    $nestedData['supplier_name'] = $post->supplier_name;
                    $nestedData['acc_name'] = $post->acc_name;
                    $nestedData['assigned_name'] = $post->assigned_name;
                    if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') {
                    $nestedData['messages_type'] = $post->messages_type;


                    }
                    $nestedData['action'] = '<button ticket_guid='.$post->ticket_guid.' category_guid='.$post->topic_guid.' sub_category_guid='.$post->sub_topic_guid.' id="edit_ticket" class="btn btn-xs btn-primary" type="button" data-toggle="modal" data-target="#edit_ticket_modal"><i class="glyphicon glyphicon-pencil"></i></button>';
                    $data[] = $nestedData;

                }
            }               

            $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data,   
                    "hahaha"=>'*******'.$ticket_status.'++++++++++'
                    );
            
            echo json_encode($json_data); 

            //print_r($data);die;


    }

      public function details()
       {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   

            $ticket_guid = $_REQUEST['t_g'];
            $ticket = $this->db->query("SELECT e.name as sub_name,d.user_name,c.rr_name ,a.resolved_reason,a.ticket_number,a.ticket_status, b.`name`, a.created_at,f.user_name as created_name,g.acc_name as retailer_name, h.supplier_name as supplier_name, a.ticket_path, a.ticket_file,a.hide,a.`resolved_remark` FROM ticket a INNER JOIN ticket_topic b ON a.topic_guid = b.`t_topic_guid` 
                LEFT JOIN ticket_resolved_reason c ON a.resolved_reason = c.rr_guid 
                LEFT JOIN set_user d ON a.assigned = d.`user_guid` 
                INNER JOIN set_user f ON a.created_by = f.`user_guid`
                INNER JOIN ticket_sub_topic e ON a.sub_topic_guid = e.`t_sub_topic_guid` 
                INNER JOIN acc g ON a.acc_guid = g.acc_guid
                INNER JOIN set_supplier h ON a.supplier_guid = h.supplier_guid
                WHERE a.`ticket_guid` = '$ticket_guid' GROUP BY a.ticket_number ");
            if(in_array('SHTICCH',$_SESSION['module_code']))
            {
                $ticket_child = $this->db->query("
                    SELECT 
                  a.hide,
                  a.ticket_c_guid,                
                  a.messages_type,
                  a.messages,
                  a.created_at,
                  a.created_by,
                  a.ticket_file,
                  a.ticket_path,
                  b.user_name 
                FROM
                  ticket_child a 
                  INNER JOIN (SELECT * FROM lite_b2b.`set_user` GROUP BY user_guid) b 
                    ON a.`created_by` = b.`user_guid` 
                WHERE a.ticket_guid = '$ticket_guid'
                ORDER BY created_at ASC ");
            }
            else
            {
                $ticket_child = $this->db->query("
                    SELECT 
                  a.hide,
                  a.ticket_c_guid,                
                  a.messages_type,
                  a.messages,
                  a.created_at,
                  a.created_by,
                  a.ticket_file,
                  a.ticket_path,
                  b.user_name 
                FROM
                  ticket_child a 
                  INNER JOIN (SELECT * FROM lite_b2b.`set_user` GROUP BY user_guid) b 
                    ON a.`created_by` = b.`user_guid` 
                WHERE a.ticket_guid = '$ticket_guid' AND a.hide = 0
                ORDER BY created_at ASC ");
            }

            $super_admin = $this->db->query("SELECT * FROM set_user WHERE user_group_guid IN ('3379ECDBDB0711E7B504A81E8453CCF0', '4F354103006B11EA84CD000D3AA2838A') GROUP BY user_guid");

            $type = $this->db->query( "SHOW COLUMNS FROM ticket WHERE FIELD = 'ticket_status'" )->row( 0 )->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
            $ticket_status = explode("','", $matches[1]);

            //$ticket_status = $this->db->query("SELECT DISTINCT ticket_status from ticket");

            $ticket_resolved_reason = $this->db->query("SELECT * FROM ticket_resolved_reason order by rr_name"); 
            $customer_guid = $ticket->row('acc_guid');
            $file_path = $this->db->query("SELECT DISTINCT a.file_path FROM acc a INNER JOIN ticket b ON a.acc_guid = b.`acc_guid` WHERE b.ticket_guid = '$ticket_guid'");
            $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid,'web','ticket','sec_path','SECTIC');
            $folder_path = $file_path->row('file_path');
            $path = $file_config_main_path.$folder_path;
            $supplier_guid = $this->db->query("SELECT supplier_guid from lite_b2b.ticket WHERE ticket_guid = '$ticket_guid'");
            $supp = $supplier_guid->row('supplier_guid');
            $tick_test = $this->db->query("SELECT SUBSTRING(ticket_path, 63,114) As Tick FROM lite_b2b.`ticket_child` WHERE ticket_guid = '$ticket_guid'");
            $test = $tick_test->row('Tick');
      
           //var_dump($ticket_status);die;

            $data = array(
                'ticket' => $ticket,
                'ticket_child' => $ticket_child,
                'ticket_status' => $ticket_status,
                'ticket_resolved_reason' =>$ticket_resolved_reason,
                'super_admin' => $super_admin,
                'path' => $path,
                'supp' => $supp,
                'test' => $test,
            );

            $this->load->view('header');
            $this->load->view('ticket/ticket_details', $data);
            $this->load->view('footer');

        }

        else
        {
            redirect('#');
        }
    }

     public function user_open_ticket()

    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   

            $previous_url = $_SERVER['HTTP_REFERER'];

            $run_no = $this->db->query("SELECT IFNULL( MAX(LPAD(RIGHT(ticket_number, 4) + 1, 4, 0)), LPAD(1, 4, 0) ) AS runno  FROM `ticket`  WHERE  SUBSTRING(ticket_number, - 8, 4) = CONCAT( RIGHT(YEAR(NOW()), 2), LPAD(MONTH(NOW()), 2, 0) )")->row('runno');

            $todaydate = date('Ym');
    
            $todaydate2 = substr($todaydate, 2);

            $ticket_number = $this->db->query("SELECT concat( '$todaydate2', '$run_no' ) as refno")->row('refno');

            $topic_guid = $this->input->post('topic_guid');

             $acc_nm = substr($this->db->query("SELECT file_path FROM acc WHERE acc_guid = '".$_SESSION['customer_guid']."'")->row('file_path'),1);

            $acc_guid = $this->input->post('acc_guid');

            $supplier_guid =  $this->input->post('supplier_guid');

            $sub_topic_guid = $this->input->post('sub_topic_guid');

            $messages = $this->input->post('messages');

             $file = $this->input->post('myFile');

            $countfiles = count($file);

            $file_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '$acc_guid'")->row('file_path');

            $file_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

             $path2 = $file_path;

            $customer_guid = $_SESSION['customer_guid'];

            $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid,'web','ticket','main_path','TIC');
            $file_config_sec_path = $this->file_config_b2b->file_path_name($customer_guid,'web','ticket','sec_path','SECTIC');

             if(isset($_POST['submit']) && $_FILES['myFile']){

                $paths = array();
                $filename = array();
     
                // Count total files
                $countfiles = count($_FILES['myFile']['name']);

                // Looping all files
                for($i=0;$i<$countfiles;$i++){

                    if ($_FILES['myFile']['name'][0] != "") {

                        $filename[] = $file.$_FILES['myFile']['name'][$i];

                       
                        //$paths1[] = $file.$_FILES['file']['name'][$i];
                        /*echo $_SERVER['SERVER_NAME'];die;*/
                        /*$path = base_url('asset/manual_guide/').$filename;*/
                       $file_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '$acc_guid'")->row('file_path');
                       $path = $file_config_main_path.$file_path.'/'.$file_guid.'-'.$filename[$i];

                       $path1 = $path;
                            // Upload file
                        $paths[] = $file_config_sec_path.$file_path.'/'.$file_guid.'-'.$filename[$i];

                         
                         if (!file_exists($file_config_main_path.$file_path.'/')) {
           
                           mkdir($file_config_main_path.$file_path.'/', 0777, true); 
            

                         };
                        move_uploaded_file($_FILES['myFile']['tmp_name'][$i],$path1);

                        //print_r($path1);die;
                        
                    }

                    else
                    {
                       //echo 'Error in uploading file - '.$_FILES['myFile']['name'][$i].'<br/>';
                    }
                    
                }
            }

            $ticket_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $created_at = $this->db->query("SELECT now() as now")->row('now');

            $user_group = $_SESSION['user_group_name'];

            if ( $user_group== 'SUPER_ADMIN') {
                $messages_type = 'A';
            } else {
                $messages_type = 'U';
            }
// print_r($filename);die;
            $data = array(

                'ticket_guid' => $ticket_guid,
                'ticket_number' => $ticket_number,
                'topic_guid' => $topic_guid,
                'acc_guid' => $acc_guid,
                'supplier_guid' => $supplier_guid,
                'sub_topic_guid' => $sub_topic_guid,
                'details' => $messages,
                'created_at' => $created_at,
                'created_by' => $_SESSION['user_guid'],
                'ticket_status' => 'New',
                'ticket_file' => implode(',', $filename),
                'ticket_path' => implode(',', $paths)

            );

           //print_r($data);die;

            $this->db->insert('ticket', $data);

             $ticket_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

             $data = array(

               'ticket_c_guid' => $ticket_c_guid,
                'ticket_guid' => $ticket_guid,
                'messages' => $messages,
                'created_at' => $created_at,
                'created_by' => $_SESSION['user_guid'],
                'messages_type' => $messages_type,
                'acc_guid' => $acc_guid,
                'supplier_guid' => $supplier_guid,
                'ticket_file' => implode(',', $filename),
                'ticket_path' => implode(',', $paths)
             );

           // print_r($data);die;

             $this->db->insert('ticket_child', $data);

            echo "<script> alert('Your Ticket number ".$ticket_number." open successfully! We will response to you as soon as possible.');</script>";
            echo "<script> document.location='" . base_url() . "index.php/Ticket/details?t_g=".$ticket_guid."' </script>";

        }

        else
        {
            redirect('#');
        }
    }

   
    public function ticket_messages_send()
    {
      if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
      {   

          $ticket_guid = $this->input->post('ticket_guid');

          $link = site_url('Ticket/details?t_g=').$ticket_guid;

          $notification_guid1 = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

          $ticket = $this->db->query("SELECT * FROM ticket WHERE ticket_guid = '$ticket_guid' ");

          $created_by = $ticket->row('created_by');

          $message = 'New messages for ticket '.$ticket->row('ticket_number');

          $ticket_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');


          //cannnot addslashes because imagebase64 will not show
          /*$messages = addslashes($this->input->post('messages'));*/

          $messages = $this->input->post('messages');

          $created_at = $this->db->query("SELECT now() as now")->row('now');

          $user_group = $_SESSION['user_group_name'];

          $user_guid = $_SESSION['user_guid'];

          $assigned = $ticket->row('assigned');

          $acc_guid = $ticket->row('acc_guid');

          $supplier_guid = $ticket->row('supplier_guid');

          $file = $this->input->post('myFile1');

          //$countfiles = count($file);

          $file_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '$acc_guid'")->row('file_path');

          $file_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

          $path = $file_path;

          $customer_guid = $_SESSION['customer_guid'];

          $file_config_main_path = $this->file_config_b2b->file_path_name($customer_guid,'web','ticket','main_path','TIC');
          $file_config_sec_path = $this->file_config_b2b->file_path_name($customer_guid,'web','ticket','sec_path','SECTIC');

         //print_r($_FILES);die;
           if(isset($_POST['submit']) && $_FILES['myFile1']){



              $paths1 = array();
              $filename1 = array();
   
              // Count total files
              $countfiles = count($_FILES['myFile1']['name']);

              // Looping all files
              for($i=0;$i<$countfiles;$i++){

                  if ($_FILES['myFile1']['name'][0] != "") {

                      $filename1[] = $file.$_FILES['myFile1']['name'][$i];

                     
                      //$paths1[] = $file.$_FILES['file']['name'][$i];
                      /*echo $_SERVER['SERVER_NAME'];die;*/
                      /*$path = base_url('asset/manual_guide/').$filename;*/
                     $file_path = $this->db->query("SELECT file_path FROM acc WHERE acc_guid = '$acc_guid'")->row('file_path');
                     $path = $file_config_main_path.$file_path.'/'.$file_guid.'-'.$filename1[$i];
                     

                     $path1 = $path;
                          // Upload file
                      $paths1[] = $file_config_sec_path.$file_path.'/'.$file_guid.'-'.$filename1[$i];
                       
                       if (!file_exists($file_config_main_path.$file_path.'/')) {
         
                         mkdir($file_config_main_path.$file_path.'/', 0777, true); 
          

                       };
                      move_uploaded_file($_FILES['myFile1']['tmp_name'][$i],$path1);
                      //print_r($path1);die;
                      
                  }

                  else
                  {
                     //echo 'Error in uploading file - '.$_FILES['myFile']['name'][$i].'<br/>';
                  }
                  
              }
          }
       
          
          $ticket_child = $this->db->query("SELECT a.created_by FROM ticket_child a WHERE a.ticket_guid = '$ticket_guid' AND a.created_by != '$user_guid' AND a.created_by != '$assigned' GROUP BY a.created_by")->result();


          foreach ($ticket_child as $key) {

              $notification_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
              $data = array(

                  'notification_guid' => $notification_guid,
                  'user_guid' => $key->created_by,
                  'icon' => 'ticket',
                  'message' => $message,
                  'link' => $link,
                  'created_at' => $created_at,
                  'status' => 0,
                  //'acc_guid' => $acc_guid,
                  //'supplier_guid' => $supplier_guid,
                  //'ticket_file' => implode(',', $filename1),
                  //'ticket_path' =>  implode(',', $path22)
                  
                  
              );
             //print_r($data);die;
              $this->db->insert('notifications', $data);
          }

          



          if ( isset($assigned ) && $user_guid != $assigned )  {
              

              $data = array(

                  'notification_guid' => $notification_guid1,
                  'user_guid' => $assigned,
                  'icon' => 'ticket',
                  'message' => $message,
                  'link' => $link,
                  'created_at' => $created_at,
                  'status' => 0,
                  //'acc_guid' => $acc_guid,
                  //'supplier_guid' => $supplier_guid,
                  //'ticket_file' => implode(',', $filename1),
                  //'ticket_path' =>  implode(',', $path22)
                  

              );
             
              $this->db->insert('notifications', $data);

          }

          if ( $user_group == 'SUPER_ADMIN') {

              $messages_type = 'A';
              $ticket_status = 'In-Progress';

              $data = array(

                  'ticket_c_guid' => $ticket_c_guid,
                  'ticket_guid' => $ticket_guid,
                  'messages' => $messages,
                  'created_at' => $created_at,
                  'created_by' => $user_guid,
                  'messages_type' => $messages_type,
                  'acc_guid' => $acc_guid,
                  'supplier_guid' => $supplier_guid,
                  'ticket_file' => implode(',', $filename1),
                  'ticket_path' =>  implode(',', $paths1),


              );

               //print_r($data);die;

              $this->db->insert('ticket_child', $data);

              $data = array(
                      'ticket_status' => $ticket_status
              );
             

              $this->db->where('ticket_guid', $ticket_guid);
              $this->db->update('ticket', $data);


          } else {

              $messages_type = 'U';

              $data = array(

                  'ticket_c_guid' => $ticket_c_guid,
                  'ticket_guid' => $ticket_guid,
                  'messages' => $messages,
                  'created_at' => $created_at,
                  'created_by' => $user_guid,
                  'messages_type' => $messages_type,
                  'acc_guid' => $acc_guid,
                  'supplier_guid' => $supplier_guid,
                  'ticket_file' => implode(',', $filename1),
                  'ticket_path' =>  implode(',', $paths1),

              );

             // print_r($data);die;

              $this->db->insert('ticket_child', $data);

          }

          
          echo "<script> document.location='" . base_url() . "index.php/Ticket/details?t_g=".$ticket_guid."' </script>";

      }

      else
      {
          redirect('#');
      }
  }
  public function get_supplier()
    {
         
            $acc_guid = $this->input->post('acc_guid');
            $database1 = 'lite_b2b';
            // $query_supcode = $this->session->userdata('query_supcode');
            $user_guid = $this->session->userdata('user_guid');
            // echo $user_guid;
            // echo $this->session->userdata('module_code');
            if($this->session->userdata('module_code') == null && $this->session->userdata('module_code') == '')
            {
                $user_guid = $this->session->userdata('user_guid');
                $check_user_right = $this->db->query("SELECT a.*,b.`user_group_name`,d.`module_name`,e.`module_group_name`,module_code,c.`isenable` FROM set_user a INNER JOIN set_user_group b ON a.user_group_guid = b.user_group_guid INNER JOIN set_user_module c ON b.user_group_guid = c.user_group_guid INNER JOIN set_module d ON c.module_guid = d.module_guid INNER JOIN set_module_group e ON d.module_group_guid = e.module_group_guid WHERE a.user_guid = '$user_guid' AND a.isactive = 1 AND e.module_group_name = 'Panda B2B' AND c.isenable = 1 AND d.module_code = 'OAT' GROUP BY a.user_guid,acc_guid,c.module_guid");
                // print_r(count($check_user_right->result()));die;
                if(count($check_user_right->result()) > 0)
                {
                    // echo 1;die;
                    $ticket_supplier = $this->db->query("SELECT a.*,b.* FROM $database1.set_supplier AS a LEFT JOIN $database1.set_supplier_group AS b ON a.supplier_guid= b.supplier_guid INNER JOIN acc AS c ON b.customer_guid = c.acc_guid WHERE b.customer_guid = '$acc_guid' GROUP BY a.supplier_guid ORDER BY a.supplier_name ASC ")->result();
                    // echo $this->db->last_query();die;
                }
                else
                {
                    $ticket_supplier = $this->db->query("SELECT b.*,c.* FROM $database1.`set_supplier_user_relationship` a INNER JOIN $database1.set_supplier_group b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$acc_guid' INNER JOIN $database1.set_supplier c ON b.`supplier_guid` = c.`supplier_guid` WHERE a.`user_guid` = '$user_guid' AND a.`customer_guid` = '$acc_guid' ORDER BY c.supplier_name ASC ")->result();
                }
            }
            else
            {                
                if(in_array('IAVA',$_SESSION['module_code']))
                {
                    $ticket_supplier = $this->db->query("SELECT a.*,b.* FROM $database1.set_supplier AS a LEFT JOIN $database1.set_supplier_group AS b ON a.supplier_guid= b.supplier_guid INNER JOIN acc AS c ON b.customer_guid = c.acc_guid WHERE b.customer_guid = '$acc_guid' GROUP BY a.supplier_guid ORDER BY a.supplier_name ASC ")->result();
                }
                else
                {
                    $ticket_supplier = $this->db->query("SELECT b.*,c.* FROM $database1.`set_supplier_user_relationship` a INNER JOIN $database1.set_supplier_group b ON a.`supplier_group_guid` = b.`supplier_group_guid` AND b.`customer_guid` = '$acc_guid' INNER JOIN $database1.set_supplier c ON b.supplier_guid = c.`supplier_guid` WHERE a.`user_guid` = '$user_guid' AND a.`customer_guid` = '$acc_guid' ORDER BY c.supplier_name ASC")->result();
                }
            }
            
            $data2 = array(
                'ticket_supplier' => $ticket_supplier,
         
            );

            echo json_encode($data2);
        
           //$data = $this->db->query("SELECT a.period_code FROM `supplier_monthly_doc_count` a LEFT JOIN `supplier_monthly_main` b ON a.period_code = b.`period_code` WHERE a.customer_guid = '$customerid' AND a.supplier_guid = '$supplierid' AND invoice_number IS NULL;")->result();/

   
    }

        public function get_subtopic()
    {
         
            $topic_guid= $this->input->post('topic_guid');

            $ticket_sub_topic = $this->db->query("SELECT * FROM ticket_sub_topic WHERE t_topic_guid = '$topic_guid' ")->result();

            $data2 = array(
                'ticket_sub_topic' => $ticket_sub_topic,
         
            );

            echo json_encode($data2);
        
            /*$data = $this->db->query("SELECT a.period_code FROM `supplier_monthly_doc_count` a LEFT JOIN `supplier_monthly_main` b ON a.period_code = b.`period_code` WHERE a.customer_guid = '$customerid' AND a.supplier_guid = '$supplierid' AND invoice_number IS NULL;")->result();*/

   
    }

    public function ticket_setup()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $ticket_topic = $this->db->query("SELECT * FROM ticket_topic order by name")->result();
            $ticket_sub_topic = $this->db->query("SELECT a.*,b.name as topic_name FROM ticket_sub_topic a INNER JOIN ticket_topic b ON a.t_topic_guid = b.t_topic_guid ")->result();
            $ticket_resolved_reason = $this->db->query("SELECT * FROM ticket_resolved_reason order by rr_name ")->result();

            $data = array(
                'ticket_topic' => $ticket_topic,
                'ticket_sub_topic' => $ticket_sub_topic,
                'ticket_resolved_reason' => $ticket_resolved_reason
         
            );
            

            $this->load->view('header');
            $this->load->view('ticket/ticket_setup', $data);
            $this->load->view('footer');

        }
    }

    public function add_topic()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $topic = $this->input->post('topic');

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

            $t_topic_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
            $created_at = $this->db->query("SELECT now() as now")->row('now');

            $user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');

            $data = array(

                    'name' => $topic,
                    't_topic_guid' => $t_topic_guid,
                    'created_at' => $created_at,
                    'created_by' => $user_name,

                );

            $this->db->insert('ticket_topic', $data);

            echo "<script> alert('Topic Created Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

        public function delete_topic()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $t_topic_guid = $this->input->post('t_topic_guid[]');

            $previous_url = $_SERVER['HTTP_REFERER'];

            foreach ($t_topic_guid as $key) {

                $checking = $this->db->query(" SELECT * FROM ticket WHERE topic_guid = '$key' ");

                $ticket_topic = $this->db->query(" SELECT Name FROM ticket_topic WHERE t_topic_guid = '$key' ");

                if ($checking->num_rows() > '0') {
                    echo "<script> alert('Topic ".$ticket_topic->row('Name')." Have Ticket, Delete Process Stop ');</script>";
                    echo "<script> document.location='".$previous_url."' </script>";
                    die;
                }
            }

            

            foreach ($t_topic_guid as $key ) {
                $this->db->where('t_topic_guid', $key);
                $this->db->delete('ticket_topic');

                $this->db->where('t_topic_guid', $key);
                $this->db->delete('ticket_sub_topic');

            }
            
            echo "<script> alert('Topic Delete Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

    public function add_sub_topic()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $subtopic = $this->input->post('subtopic');

            $t_sub_topic_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $t_topic_guid = $this->input->post('t_topic_guid');

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

            $created_at = $this->db->query("SELECT now() as now")->row('now');

            $user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');

            $data = array(

                    't_sub_topic_guid' => $t_sub_topic_guid,
                    't_topic_guid' => $t_topic_guid,
                    'name' => $subtopic,
                    'created_at' => $created_at,
                    'created_by' => $user_name,

                );

            $this->db->insert('ticket_sub_topic', $data);

            echo "<script> alert('SubTopic Created Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

        public function delete_sub_topic()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $t_sub_topic_guid = $this->input->post('t_sub_topic_guid[]');

            $previous_url = $_SERVER['HTTP_REFERER'];

            foreach ($t_sub_topic_guid as $key) {

                $checking = $this->db->query(" SELECT * FROM ticket WHERE sub_topic_guid = '$key' ");

                $ticket_topic = $this->db->query(" SELECT Name FROM ticket_sub_topic WHERE t_sub_topic_guid = '$key' ");

                if ($checking->num_rows() > '0') {
                    echo "<script> alert('SubTopic ".$ticket_topic->row('Name')." Have Ticket, Delete Process Stop ');</script>";
                    echo "<script> document.location='".$previous_url."' </script>";
                    die;
                }
            }

            foreach ($t_sub_topic_guid as $key ) {

                $this->db->where('t_sub_topic_guid', $key);
                $this->db->delete('ticket_sub_topic');

            }
            
            echo "<script> alert('SubTopic Delete Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

        public function add_rr()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $topic = $this->input->post('topic');

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

            $rr_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
            $created_at = $this->db->query("SELECT now() as now")->row('now');

            $user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');

            $data = array(

                    'rr_name' => $topic,
                    'rr_guid' => $rr_guid,
                    'created_at' => $created_at,
                    'created_by' => $user_name,

                );

            $this->db->insert('ticket_resolved_reason', $data);

            echo "<script> alert('Resolve Reason Created Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

        public function delete_rr()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $rr_guid = $this->input->post('rr_guid[]');

            $previous_url = $_SERVER['HTTP_REFERER'];

            foreach ($rr_guid as $key) {

                $checking = $this->db->query(" SELECT * FROM ticket WHERE resolved_reason = '$key' ");

                $rr_name = $this->db->query(" SELECT rr_name FROM ticket_resolved_reason WHERE rr_guid = '$key' ");

                if ($checking->num_rows() > '0') {
                    echo "<script> alert('Resolved Reason ".$rr_name->row('rr_name')." Have Ticket, Delete Process Stop ');</script>";
                    echo "<script> document.location='".$previous_url."' </script>";
                    die;
                }
            }

            foreach ($rr_guid as $key ) {

                $this->db->where('rr_guid', $key);
                $this->db->delete('ticket_resolved_reason');

            }
            
            echo "<script> alert('Resolved Reason Delete Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

        public function update_topic()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $topic = $this->input->post('topic');

            $guid = $this->input->post('guid');

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

            $t_topic_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');
            $now = $this->db->query("SELECT now() as now")->row('now');

            $user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');

            $data = array(

                    'name' => $topic,
                    'updated_at' => $now,
                    'updated_by' => $user_name,

                );

            $this->db->where('t_topic_guid', $guid);
            $this->db->update('ticket_topic', $data);

            echo "<script> alert('Topic Update Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

        public function update_sub_topic()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $subtopic = $this->input->post('subtopic');

            $guid = $this->input->post('guid');

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

            $now = $this->db->query("SELECT now() as now")->row('now');

            $user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');
            $check_guid = $this->db->query("SELECT * FROM lite_b2b.ticket_sub_topic WHERE t_sub_topic_guid = '$guid' ");

            if($check_guid->num_rows() > 0)
            {
                $data = array(

                    'name' => $subtopic,
                    'updated_at' => $now,
                    'updated_by' => $user_name,

                );

                $this->db->where('t_sub_topic_guid', $guid);
                $this->db->update('ticket_sub_topic', $data);

            }
            else
            {
                $data = array(

                    'rr_name' => $subtopic,
                    'updated_at' => $now,
                    'updated_by' => $user_name,

                );

                $this->db->where('rr_guid', $guid);
                $this->db->update('ticket_resolved_reason', $data);
            }

            echo "<script> alert('Update Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

        public function change_ticket_status()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $status = $this->input->post('status');

            $ticket_guid = $this->input->post('ticket_guid');

            $link = site_url('Ticket/details?t_g=').$ticket_guid;

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

            $ticket = $this->db->query("SELECT * FROM ticket WHERE ticket_guid = '$ticket_guid' ");

            $message = 'Ticket ' .$ticket->row('ticket_number').' Had Been Closed ';

            $created_by = $ticket->row('created_by');

            $ticket_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $now = $this->db->query("SELECT now() as now")->row('now');

            $user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');

            $data = array(

                    'ticket_status' => $status,
                    'updated_at' => $now,
                    'updated_by' => $user_name,

                );

            $this->db->where('ticket_guid', $ticket_guid);
            $this->db->update('ticket', $data);

            if ($status == 'Closed') {

                $resolved_reason = $this->input->post('resolved_reason');
                $resolved_remark = $this->input->post('resolved_remark');

                 $data = array(

                    'resolved_reason' => $resolved_reason,
                    'resolved_remark' => $resolved_remark,
                    'updated_at' => $now,
                    'updated_by' => $user_name,

                );

                $this->db->where('ticket_guid', $ticket_guid);
                $this->db->update('ticket', $data);

                $data = array(

                    'ticket_c_guid' => $ticket_c_guid,
                    'ticket_guid' => $ticket_guid,
                    'messages' => $message,
                    'created_at' => $now,
                    'created_by' => $_SESSION['user_guid'],
                    'messages_type' => 'A',
                    

                );

                $this->db->insert('ticket_child', $data);

                $notification_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

                    
                    $data = array(

                        'notification_guid' => $notification_guid,
                        'user_guid' => $created_by,
                        'icon' => 'ticket',
                        'message' => $message,
                        'link' => $link,
                        'created_at' => $now,
                        'status' => 0,
                        //'ticket_guid' => $ticket_guid,
                        

                    );

                    $this->db->insert('notifications', $data);

            }

            echo "<script> alert('Ticket Status Update Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

    public function search_message_result()
    {
            
            $search_value = $this->input->post('search_value');

            $result = $this->db->query("SELECT 
  a.`ticket_guid`,
  a.`messages`,
  REGEXP_REPLACE(a.`messages`,'$search_value',CONCAT('<span style=\"background-color:yellow\">','$search_value','</span>')) as messages,
  a.`created_at`,
  b.`ticket_number` 
FROM
  ticket_child a 
  INNER JOIN ticket b 
    ON a.`ticket_guid` = b.ticket_guid 
WHERE a.`messages` LIKE '%$search_value%' 
  AND a.`messages` NOT LIKE '%<img %$search_value%>%' ");

            if ($search_value == '') {

                $search_result = $this->db->query("SELECT 'a' limit 0 ")->result();

                $search_count = "0";

            } else{

                $search_result = $result->result();

                $search_count = $result->num_rows();

            }

            $data = array( 

                'search_result' => $search_result,
                'search_count' =>$search_count

            );

            echo json_encode($data);
            
   
    }

        public function assigned_user()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $assigned = $this->input->post('assigned');

            $ticket_guid = $this->input->post('ticket_guid');

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

            $ticket_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $now = $this->db->query("SELECT now() as now")->row('now');

            $user_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$user_guid' GROUP BY user_guid")->row('user_name');

            $assigned_name = $this->db->query("SELECT user_name FROM set_user WHERE user_guid = '$assigned' GROUP BY user_guid")->row('user_name');

            $ticket_status = 'In-Progress';


            $data = array(

                    'ticket_status' => $ticket_status,
                    'assigned' => $assigned,
                    'updated_at' => $now,
                    'updated_by' => $user_name,

                );

            $this->db->where('ticket_guid', $ticket_guid);
            $this->db->update('ticket', $data);



                if ( $_SESSION['user_guid'] == $assigned) {
                    $system_message = '<b>Claim this ticket</b>';
                } else {

                    $system_message = '<b>Ticket assigned to '.$assigned_name.'</b>';

                    $ticket = $this->db->query("SELECT * FROM ticket WHERE ticket_guid = '$ticket_guid' ");

                    $notification_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

                    $link = site_url('Ticket/details?t_g=').$ticket_guid;
                    $message = $user_name.' assigned ticket '.$ticket->row('ticket_number'). ' to you';
                    $created_at = $this->db->query("SELECT now() as now")->row('now');
                    
                    

                    $data = array(

                        'notification_guid' => $notification_guid,
                        'user_guid' => $assigned,
                        'icon' => 'ticket',
                        'message' => $message,
                        'link' => $link,
                        'created_at' => $created_at,
                        'status' => 0,
                        

                    );

                    $this->db->insert('notifications', $data);

                }
                

                $data = array(

                    'ticket_c_guid' => $ticket_c_guid,
                    'ticket_guid' => $ticket_guid,
                    'messages' => $system_message,
                    'created_at' => $now,
                    'created_by' => $_SESSION['user_guid'],
                    'messages_type' => 'A',
                    

                );

                $this->db->insert('ticket_child', $data);


                
            

            echo "<script> alert('Assign Success');</script>";
            echo "<script> document.location='".$previous_url."' </script>";

        }
    }

    public function edit_ticket()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {  
            $ticket_guid = $this->input->post('ticket_guid');
            $category_guid = $this->input->post('category_guid');
            $sub_category = $this->input->post('sub_category');
            $user_id = $_SESSION['userid'];
            // echo $ticket_guid.'-'.$category_guid.'-'.$sub_category;

            $check_ticket = $this->db->query("SELECT * FROM ticket WHERE topic_guid = '$category_guid' AND sub_topic_guid = '$sub_category' AND ticket_guid = '$ticket_guid'");
            if($check_ticket->num_rows() == 0)
            {
                $result = $this->db->query("UPDATE ticket SET topic_guid = '$category_guid',sub_topic_guid = '$sub_category',updated_at = NOW(),updated_by = '$user_id' WHERE ticket_guid = '$ticket_guid'");
                // echo $this->db->last_query();
                $affected_rows = $this->db->affected_rows();
                echo $affected_rows;
            }
            else
            {
                $affected_rows = 0;
                echo $affected_rows;
            }
            // echo $this->db->last_query();
            // $affected_rows = $this->db->affected_rows();
            // echo $affected_rows;
        }
        else
        {
            redirect('#');
        }
    }

    public function hide_ticket_child()
    {
        $ticket_c_guid = $this->input->post("ticket_c_guid");
        $user_id = $_SESSION['userid'];
        // echo $ticket_c_guid;
        $check_hide = $this->db->query("SELECT * FROM ticket_child WHERE ticket_c_guid = '$ticket_c_guid' AND hide = 0");

        if($check_hide->num_rows() > 0)
        {
            $this->db->query("UPDATE ticket_child SET hide = 1,updated_at = NOW(),updated_by = '$user_id' WHERE ticket_c_guid = '$ticket_c_guid'");

            $affected_rows = $this->db->affected_rows();
            if($affected_rows > 0)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
    }

    public function unhide_ticket_child()
    {
        $ticket_c_guid = $this->input->post("ticket_c_guid");
        $user_id = $_SESSION['userid'];
        // echo $ticket_c_guid;
        $check_hide = $this->db->query("SELECT * FROM ticket_child WHERE ticket_c_guid = '$ticket_c_guid' AND hide = 1");
        // echo $check_hide->num_rows();die;
        if($check_hide->num_rows() > 0)
        {
            $this->db->query("UPDATE ticket_child SET hide = 0,updated_at = NOW(),updated_by = '$user_id' WHERE ticket_c_guid = '$ticket_c_guid'");

            $affected_rows = $this->db->affected_rows();
            if($affected_rows > 0)
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
    }

        public function ticket_notification_count()
    {

        $ticket_status = $this->input->post("ticket_status");
        $New = $this->db->query("SELECT COUNT(*) as count FROM ticket WHERE ticket_status = 'New'")->row('count');

         $data = array(

                      'New' => $New,
                       
                      );

            echo json_encode($data);



    }
 
    public function hide_ticket()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $database = 'lite_b2b';
            $table = 'ticket';
            $ticket_guid = $this->input->post('hide_ticket_guid');
            $this->db->query("UPDATE $database.ticket SET hide = 1 WHERE ticket_guid = '$ticket_guid'");
            $this->session->set_flashdata('message', 'Ticket Hide Successfully');
            redirect('Ticket/details?t_g='.$ticket_guid);

        }
        else
        {
            redirect('login_c');
        }
    }

    public function unhide_ticket()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $database = 'lite_b2b';
            $table = 'ticket';
            $ticket_guid = $this->input->post('hide_ticket_guid');
            $this->db->query("UPDATE $database.ticket SET hide = 0 WHERE ticket_guid = '$ticket_guid'");
            $this->session->set_flashdata('message', 'Ticket Unhide Successfully');
            redirect('Ticket/details?t_g='.$ticket_guid);

        }
        else
        {
            redirect('login_c');
        }
    }    

    public function ticket_testing(){
        // echo mkdir('https://sunshineb2b.com.my/b2b-pdf/ticket/7526D74B421F11E99994000D3AA2838A', 0777);
        echo mkdir('https://sunshineb2b.com.my/admin_files/testing/', 0777);
    }

}

?>