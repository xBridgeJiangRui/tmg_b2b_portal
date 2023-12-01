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

            $data = array (

                'last_12_month_ticket' => $last_12_month_ticket,
                'Topic' => $Topic->result(),
                'topic_bar' => $Topic,//stacked column chart `month` must have cancel
                'topic1' => $topic1,//stacked column chart `month` must have cancel
                'all' => $all,
                'New' => $New,
                'In_Progress' => $In_Progress,
                'Closed' => $Closed,
                'category' => $category,
                'sub_category' => $sub_category,


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

            ini_set('memory_limit', -1); 
            if($_SESSION['user_group_name'] == 'SUPER_ADMIN')
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
                    8 => 'assigned_name',
                    9 => 'seq',
                    10 => 'action',

                );
            }
            else
            {
                $columns = array(
                    0 => 'ticket_number',
                    1 => 'name',
                    2 => 'sub_name',
                    3 => 'ticket_status',
                    4 => 'created_at',
                    5 => 'user_name',
                    6 => 'seq',
                    7 => 'action',
                );
            }


            $user_guid = $_SESSION['user_guid'];
            $user_group = $_SESSION['user_group_name'];
            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $this->input->post('order');
            $dir = "";
            $totalData = $this->Ticket_model->ticket()->row('numrow');
            $totalFiltered = $totalData;

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

            if ($user_group == 'SUPER_ADMIN' ) {
                $query = "SELECT a.topic_guid,a.sub_topic_guid,a.ticket_guid,CASE WHEN ticket_status = 'New' THEN 5 WHEN ticket_status = 'In-Progress' THEN 4 WHEN ticket_status = 'Closed' THEN 3 END as seq,CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a> <small class=".'pull-right'.">',(SELECT COUNT(*) FROM ticket_child WHERE ticket_guid = a.`ticket_guid` ) ,' <i class=\"fa fa-comments\"></i></small>') as ticket_number,c.name as sub_name,b.name,a.details,a.created_at,d.user_name,a.ticket_status ,f.supplier_name,g.supplier_group_name,e.user_name as assigned_name
                FROM ticket a 
                INNER JOIN ticket_topic b ON a.topic_guid = b.t_topic_guid 
                INNER JOIN ticket_sub_topic c ON a.sub_topic_guid = c.t_sub_topic_guid 
                LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) d ON a.created_by = d.user_guid 
                LEFT JOIN (SELECT * FROM set_user GROUP BY user_guid) e ON a.assigned = e.user_guid 
                LEFT JOIN set_supplier f ON d.supplier_guid = f.supplier_guid
                LEFT JOIN (SELECT * FROM set_supplier_group GROUP BY supplier_guid) g ON f.`supplier_guid` = g.`supplier_guid`";
            }
            else{
            $query = "SELECT a.topic_guid,a.sub_topic_guid,a.ticket_guid,CASE WHEN ticket_status = 'New' THEN 5 WHEN ticket_status = 'In-Progress' THEN 4 WHEN ticket_status = 'Closed' THEN 3 END as seq,CONCAT('<a href=".site_url('Ticket/details')."','?t_g=',a.ticket_guid,'>',a.ticket_number,'</a>') as ticket_number,  c.name AS sub_name,
  b.name,
  a.details,
  a.created_at,
  d.user_name,
  a.ticket_status 
FROM
  ticket a 
  INNER JOIN ticket_topic b 
    ON a.topic_guid = b.t_topic_guid 
  INNER JOIN ticket_sub_topic c 
    ON a.sub_topic_guid = c.t_sub_topic_guid 
  LEFT JOIN 
    (SELECT 
      * 
    FROM
      set_user 
    GROUP BY user_guid) d 
    ON a.created_by = d.user_guid 
WHERE a.`created_by` IN (SELECT user_guid FROM set_supplier_user_relationship WHERE supplier_group_guid IN (SELECT supplier_group_guid FROM set_supplier_user_relationship WHERE user_guid = '$user_guid'))
  ";
            }
            if(empty($this->input->post('search')['value']))
            {
                $posts = $this->Ticket_model->allposts($query,$limit,$start,$order_query,$dir);
                // echo $this->db->last_query();
            }
            else 
            {
                $search = $this->input->post('search')['value']; 

                $posts =  $this->Ticket_model->posts_search($query,$limit,$start,$search,$order_query,$dir);

                $totalFiltered = $this->Ticket_model->posts_search_count($query,$search);
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

                    if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') {
                    $nestedData['supplier_group_name'] = $post->supplier_group_name; 
                    $nestedData['supplier_name'] = $post->supplier_name;
                    $nestedData['assigned_name'] = $post->assigned_name;
                    }
                    $nestedData['action'] = '<button ticket_guid='.$post->ticket_guid.' category_guid='.$post->topic_guid.' sub_category_guid='.$post->sub_topic_guid.' id="edit_ticket" class="btn btn-xs btn-primary" type="button" data-toggle="modal" data-target="#edit_ticket_modal"><i class="glyphicon glyphicon-pencil"></i></button>';
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

    public function details()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   

            $ticket_guid = $_REQUEST['t_g'];
            $ticket = $this->db->query("SELECT e.name as sub_name,d.user_name,c.rr_name ,a.resolved_reason,a.ticket_number,a.ticket_status, b.`name`, a.created_at,f.user_name as created_name FROM ticket a INNER JOIN ticket_topic b ON a.topic_guid = b.`t_topic_guid` 
                LEFT JOIN ticket_resolved_reason c ON a.resolved_reason = c.rr_guid 
                LEFT JOIN set_user d ON a.assigned = d.`user_guid` 
                INNER JOIN set_user f ON a.created_by = f.`user_guid`
                INNER JOIN ticket_sub_topic e ON a.sub_topic_guid = e.`t_sub_topic_guid` 
                WHERE a.`ticket_guid` = '$ticket_guid' ");
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

            $ticket_resolved_reason = $this->db->query("SELECT * FROM ticket_resolved_reason order by rr_name");

            $data = array(

                'ticket' => $ticket,
                'ticket_child' => $ticket_child,
                'ticket_status' => $ticket_status,
                'ticket_resolved_reason' =>$ticket_resolved_reason,
                'super_admin' => $super_admin
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

            $sub_topic_guid = $this->input->post('sub_topic_guid');

            $messages = $this->input->post('messages');

            $ticket_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $created_at = $this->db->query("SELECT now() as now")->row('now');

            $user_group = $_SESSION['user_group_name'];

            if ( $user_group== 'SUPER_ADMIN') {
                $messages_type = 'A';
            } else {
                $messages_type = 'U';
            }

            $data = array(

                'ticket_guid' => $ticket_guid,
                'ticket_number' => $ticket_number,
                'topic_guid' => $topic_guid,
                'sub_topic_guid' => $sub_topic_guid,
                'details' => $messages,
                'created_at' => $created_at,
                'created_by' => $_SESSION['user_guid'],
                'ticket_status' => 'New'

            );

            $this->db->insert('ticket', $data);

            $ticket_c_guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS guid")->row('guid');

            $data = array(

                'ticket_c_guid' => $ticket_c_guid,
                'ticket_guid' => $ticket_guid,
                'messages' => $messages,
                'created_at' => $created_at,
                'created_by' => $_SESSION['user_guid'],
                'messages_type' => $messages_type,
                

            );

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
                    

                );

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
                    

                );

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

                );

                $this->db->insert('ticket_child', $data);

            }

            
            echo "<script> document.location='" . base_url() . "index.php/Ticket/details?t_g=".$ticket_guid."' </script>";

        }

        else
        {
            redirect('#');
        }
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

        public function change_ticket_status()
    {
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $_SESSION['user_logs'] == $this->panda->validate_login())
        {   
            $status = $this->input->post('status');

            $ticket_guid = $this->input->post('ticket_guid');

            $previous_url = $_SERVER['HTTP_REFERER'];

            $user_guid = $_SESSION['user_guid'];

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
                    'messages' => '<b>Ticket Had Been Closed</b>',
                    'created_at' => $now,
                    'created_by' => $_SESSION['user_guid'],
                    'messages_type' => 'A',
                    

                );

                $this->db->insert('ticket_child', $data);

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

}

?>