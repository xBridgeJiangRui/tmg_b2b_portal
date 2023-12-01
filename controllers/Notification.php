<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('form_validation');        
        $this->load->library('datatables');
         
    }

    public function notification_table()
    {
        $query_receive = $this->input->post('query');

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        
        $customer_guid = $this->session->userdata('customer_guid');
        $user_guid = $this->session->userdata('user_guid');
        $xquery_loc = $this->session->userdata("query_loc");

        $this->db->query("SET @customer_guid = '$customer_guid'");
        $this->db->query("SET @user_guid = '$user_guid'");
        $query_receive = str_replace("@user_mapped_loc",$xquery_loc,$query_receive);

        
        
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
          $dir = "asc";
        }


        $valid_columns = array();

        $header = $this->db->query($query_receive.' LIMIT 1');

        $array = $header->result();

        $array = json_decode(json_encode($array));

        foreach($array[0] as $header => $value){

          $valid_columns[] = $header;

        }



        // $valid_columns = array(
        // 0 => 'RefNo',
        // 1 => 'status',
        // 2 => 'LastStamp'
        // );

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

          $order_query = "ORDER BY `" .$order. "`  " .$dir;
        }

        $like_first_query = '';
        $like_second_query = '';

        if(!empty($search))
        {
          // $x=0;
          // foreach($valid_columns as $sterm)
          // {
          //     if($x==0)
          //     {
          //         // $this->db->like($sterm,$search);
          //       if(strpos($query_receive, 'WHERE') !== false){

          //           $like_first_query = " AND $sterm LIKE '%".$search."%'";

          //       } else{

          //           $like_first_query = " WHERE $sterm LIKE '%".$search."%'";

          //       }

          //     }
          //     else
          //     {
          //         // $this->db->or_like($sterm,$search);

          //         $like_second_query .= " OR $sterm LIKE '%".$search."%'";

          //     }
          //     $x++;
          // }

          $this->db->query("SET @search = '$search' ");

          $total = $this->db->query($query_receive.$like_first_query.$like_second_query." ")->num_rows();

          // echo $this->db->last_query();                 
        }else
        {
        $total = $this->db->query($query_receive)->num_rows();
        }

        // $this->db->limit($length,$start);

        $limit_query = " LIMIT " .$start. " , " .$length;

        $sql = $query_receive.' ';

        $query = $sql.$like_first_query.$like_second_query.$order_query.$limit_query;

        $query_result = $this->db->query($query);

        // echo $this->db->last_query();die;


        $data = array();
        foreach($query_result->result() as $row)
        {

          foreach($array[0] as $header => $value){
            $nestedData[$header] = $row->$header;
          }

            // $nestedData['RefNo'] = $row->RefNo;
            // $nestedData['status'] = $row->status;
            // $nestedData['postdatetime'] = $row->postdatetime;

            $data[] = $nestedData;

        }

        // $total = $this->db->query("SELECT COUNT(*) AS count FROM backend.import_item_gen_c WHERE import_guid = '$import_guid'")->row('count');

        $output = array(
          "draw" => $draw,
          "recordsTotal" => $total,
          "recordsFiltered" => $total,
          "data" => $data
        );

        echo json_encode($output);

    }//close notification_table

}

?>