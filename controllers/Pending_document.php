<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pending_document extends CI_Controller
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

            $acc = $this->db->query("SELECT * FROM lite_b2b.acc WHERE isactive = '1' ");

            $data = array(
                'supplier' => $supplier->result(),
                'acc' => $acc->result(),
            );
            $this->load->view('header');
            $this->load->view('b2b_document_dashboard', $data);      
            $this->load->view('footer');
        }
        else
        {
            redirect('#');
        }
    }

    public function pending_table()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0); 

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $user_guid = $_SESSION['user_guid'];
        $acc_guid = $_SESSION['customer_guid'];
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
            0 =>'acc_name',
            1 =>'type',
            2 =>'po',
            3 =>'grn',
            4 =>'grda',
            5 =>'strb',
            6 =>'prdn',
            7 =>'prcn',
            8 =>'pdn',
            9 =>'pcn',
            10 =>'pci',
            11 =>'di',
            12 =>'created_at',
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

        //print_r($supplier_guid); die;

        $sql = "SELECT 
        b.acc_name,
        a.*,
        IF(a.po >= c.po ,'1','0') AS check_po,
        IF(a.grn >= c.grn ,'1','0') AS check_grn,
        IF(a.grda >= c.grda ,'1','0') AS check_grda,
        IF(a.strb >= c.strb ,'1','0') AS check_strb,
        IF(a.prdn >= c.prdn ,'1','0') AS check_prdn,
        IF(a.prcn >= c.prcn ,'1','0') AS check_prcn,
        IF(a.pdn >= c.pdn ,'1','0') AS check_pdn,
        IF(a.pcn >= c.pcn ,'1','0') AS check_pcn,
        IF(a.pci >= c.pci ,'1','0') AS check_pci,
        IF(a.di >= c.di ,'1','0') AS check_di
      FROM
        b2b_summary.pending_document a 
        INNER JOIN lite_b2b.`acc` b 
          ON a.`customer_guid` = b.`acc_guid` 
        INNER JOIN lite_b2b.`pending_document_config` c 
          ON a.`customer_guid` = c.`customer_guid` 
        GROUP BY a.customer_guid,a.type
        ORDER BY b.acc_name ASC";
        
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
            $nestedData['acc_name'] = $row->acc_name;
            $nestedData['customer_guid'] = $row->customer_guid;
            $nestedData['type'] = $row->type;
            $nestedData['po'] = $row->po;
            $nestedData['grn'] = $row->grn;
            $nestedData['grda'] = $row->grda;
            $nestedData['strb'] = $row->strb;
            $nestedData['prdn'] = $row->prdn;
            $nestedData['prcn'] = $row->prcn;
            $nestedData['pdn'] = $row->pdn;
            $nestedData['pcn'] = $row->pcn;
            $nestedData['pci'] = $row->pci;
            $nestedData['di'] = $row->di;
            $nestedData['created_at'] = $row->created_at;
            $nestedData['created_by'] = $row->created_by;

            $nestedData['check_po'] = $row->check_po;
            $nestedData['check_grn'] = $row->check_grn;
            $nestedData['check_grda'] = $row->gcheck_rda;
            $nestedData['check_strb'] = $row->scheck_trb;
            $nestedData['check_prdn'] = $row->pcheck_rdn;
            $nestedData['check_prcn'] = $row->pcheck_rcn;
            $nestedData['check_pdn'] = $row->check_pdn;
            $nestedData['check_pcn'] = $row->check_pcn;
            $nestedData['check_pci'] = $row->check_pci;
            $nestedData['check_di'] = $row->check_di;
            
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

    public function resync_data()
    {
      ini_set('max_execution_time', 0);
      $to_shoot_url = 'http://20.212.51.33/rest_b2b/index.php/Get_pending_document/?uploaded_status=0&uploaded_status_strb=0&tf_uploaded_status=99&tf_uploaded_status_strb=0';
      //echo $to_shoot_url;die;
      $data2 = array();

      $cuser_name = 'ADMIN';
      $cuser_pass = '1234';

      $ch = curl_init($to_shoot_url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-KEY: 123456"));
      curl_setopt($ch, CURLOPT_USERPWD, "$cuser_name:$cuser_pass");
      // curl_setopt($ch, CURLOPT_POST, 1);
      // curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);

      $result = curl_exec($ch);
      //$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $output = json_decode($result);
      //print_r($output);die;
      curl_close($ch);
  
      if($output->status == 'true')
      {
        $data = array(
         'para1' => 'true',
         'msg' => 'Sync Completed ',
        );
        echo json_encode($data);
      } else {
        $data = array(
          'para1' => 'false',
          'msg' =>  'Error Syncing',
        );
        echo json_encode($data);
      }
      
    }

}
