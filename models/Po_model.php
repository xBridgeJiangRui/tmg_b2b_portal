<?php
class Po_model extends CI_Model{
     function __construct()
     {
          // Call the Model constructor
          parent::__construct();
          $this->load->library('session');
     }


     //read the department list from db
     function get_po_list($sqlStatement,$limit, $start)
     {
        $sql = $sqlStatement.' limit ' . $start . ', ' . $limit;
        $query = $this->db->query($sql);
        return $query->result();
        
     }


    function countPO()
    {
        $session_data = $this->session->userdata('logged_in');
        $user_guid = $session_data['user_guid'];
        $session_data = $this->session->userdata('branch');
        $branch_code = $session_data['branch_code'];
        $session_data = $this->session->userdata('customers');
        $customer = $session_data['customer'];

        $result = $this->db->from('pomain')
                           ->join('customer_profile', 'customer_profile.customer_guid = pomain.customer_guid')
                           ->join('customer_supcus', 'customer_supcus.customer_guid = customer_profile.customer_guid and pomain.scode = customer_supcus.code')
                           ->join('user_customer', 'user_customer.supcus_guid = customer_supcus.supcus_guid')
                           ->where('pomain.loc_group', $branch_code)
                           ->where('customer_profile.customer_name', $customer)
                           ->where('user_customer.user_guid', $user_guid)
                           ->where('ibt',0)
                           ->get();

        return $result->num_rows();
    }

     

     function get_po_details($sqlStatement)
     {
        $sql = $sqlStatement;
        $query = $this->db->query($sql);
        return $query->result();
     }



     function countPochild()
    {
        $session_data = $this->session->userdata('po_detail');
        $refno = $session_data['refno'];

        $result = $this->db->from('pochild')
                           ->where('refno', $refno)
                           ->get();

        return $result->num_rows();
    }

      function update_accepted($table, $data)
    {
        $this->db->where('refno', $_SESSION['refno']);        
        $this->db->update($table, $data);
    }

    public function update_expired($customer_db)
    {
         $sql = "UPDATE $customer_db.pomain set status = 'Expired' where date_format(NOW(),'%Y-%m-%d') >expiry_date  and status = 'Pending'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function update_grn($branch_code,$customer,$user_guid)
    {
        // write auto check GR completed item the flag GR status
    }
    // not in used
      function accept_po()
      {

           //retrieve session data
          $session_data = $this->session->userdata('po_detail');
          $refno = $session_data['refno'];
          $session_data = $this->session->userdata('logged_in');
          $user_guid = $session_data['user_guid'];
          $username = $session_data['username'];
          $logtype = 'Accept PO';


           $this -> db -> select('refno,status');
           $this -> db -> where('refno', $refno);
           $this -> db -> where('status','Pending');
           $query = $this -> db -> get('pomain');

           if($query -> num_rows() == 1)
           {
                    $row = $query->row();
                    
                    if($row->refno==$refno)
                    {
                        $data = array(
                          'status' => 'PO_Accepted'
                         );

                         $this->db->where('refno',$refno);
                         $this->db->where('status','Pending');

                         if($this->db->update('pomain', $data)) 
                         {

                            // Inserting in Table(userlog)
                            $data = array(
                               'user_guid' =>  $user_guid,
                               'logtype' =>  $logtype,
                               'docno' =>  $refno,
                               'downloaded_by' =>  $username
                            );

                            //set id column value as UUID
                            $this->db->set('log_guid', 'REPLACE(UPPER(UUID()),"-","")', FALSE);
                            $this->db->set('downloaded_at', 'NOW()', FALSE);
                            $this->db->insert('userlog', $data);


                            return "PO has been Accepted! Please Proceed Download PO";
                         }
                         else
                         {
                            return "Something Went Wrong, Please Check!";
                         }
                    }
                    else
                    {
                        return "Something Went Wrong, Please Check!";
                    }
             }
             else
             {

                   $this -> db -> select('refno,status');
                   $this -> db -> where('refno', $refno);
                   $query = $this -> db -> get('pomain');

                   $row = $query->row();

                   if($row->status=='PO_Rejected')
                    {
                        return "PO Status is Rejected! Unable to Accept. ";
                    }
                    else
                    {

                      if($row->status=='PO_Accepted')
                      {
                            return "PO Status is Accepted! Please Proceed Download PO ";
                      }
                      else
                      {
                            if($row->status=='Completed')
                            {
                                  return "PO Status is Completed!";
                            }
                            else
                            {
                                  return "Something Went Wrong, Please Check!";
                            }
                      }
                    }

                return "Something Went Wrong, Please Check!";
             }
        }


       function reject_po()
       {

           //retrieve session data
          $session_data = $this->session->userdata('po_detail');
          $refno = $session_data['refno'];
          $session_data = $this->session->userdata('logged_in');
          $user_guid = $session_data['user_guid'];
          $username = $session_data['username'];
          $logtype = 'Reject PO';

           $this -> db -> select('refno,status');
           $this -> db -> where('refno', $refno);
           $this -> db -> where('status','Pending');
           $query = $this -> db -> get('pomain');

           if($query -> num_rows() == 1)
           {
                    $row = $query->row();
                    
                    if($row->refno==$refno)
                    {
                        $data = array(
                          'status' => 'PO_Rejected'
                         );

                         $this->db->where('refno',$refno);
                         $this->db->where('status','Pending');

                         if($this->db->update('pomain', $data)) 
                         {

                            // Inserting in Table(userlog)
                            $data = array(
                               'user_guid' =>  $user_guid,
                               'logtype' =>  $logtype,
                               'docno' =>  $refno,
                               'downloaded_by' =>  $username
                            );

                            //set id column value as UUID
                            $this->db->set('log_guid', 'REPLACE(UPPER(UUID()),"-","")', FALSE);
                            $this->db->set('downloaded_at', 'NOW()', FALSE);
                            $this->db->insert('userlog', $data);


                            return "PO has been Rejected!";
                         }
                         else
                         {
                            return "Something Went Wrong, Please Check!";
                         }
                    }
                    else
                    {
                        return "Something Went Wrong, Please Check!";
                    }
             }
             else
             {

                   $this -> db -> select('refno,status');
                   $this -> db -> where('refno', $refno);
                   $query = $this -> db -> get('pomain');

                   $row = $query->row();

                   if($row->status=='PO_Accepted')
                   {
                        return "PO Status is Accepted! Unable to Reject. ";
                    }
                    else
                    {
                        if($row->status=='PO_Rejected')
                        {
                            return "PO Status is Rejected!";
                        }
                        else
                        {
                            if($row->status=='Completed')
                            {
                                return "PO Status is Completed! Unable to Reject. ";
                            }
                            else
                            {
                                return "Something Went Wrong, Please Check!";
                            }
                        }
                    }
              }
        }


        function download_po()
        {

              //retrieve session data
              $session_data = $this->session->userdata('po_detail');
              $refno = $session_data['refno'];
              $session_data = $this->session->userdata('logged_in');
              $user_guid = $session_data['user_guid'];
              $username = $session_data['username'];
              $logtype = 'Download PO';

              $this -> db -> select('refno,status');
              $this -> db -> where('refno', $refno);
              $this -> db -> where('status','PO_Accepted');
              $query = $this -> db -> get('pomain');

             if($query -> num_rows() == 1)
             {
                    $row = $query->row();
                    
                    if($row->refno==$refno)
                    {
                        $data = array(
                          'status' => 'Completed'
                         );

                         $this->db->where('refno',$refno);
                         $this->db->where('status','PO_Accepted');

                         if($this->db->update('pomain', $data)) 
                         {

                            // Inserting in Table(userlog)
                            $data = array(
                               'user_guid' =>  $user_guid,
                               'logtype' =>  $logtype,
                               'docno' =>  $refno,
                               'downloaded_by' =>  $username
                            );

                            //set id column value as UUID
                            $this->db->set('log_guid', 'REPLACE(UPPER(UUID()),"-","")', FALSE);
                            $this->db->set('downloaded_at', 'NOW()', FALSE);
                            $this->db->insert('userlog', $data);

                            return True;
                         }
                         else
                         {
                            return "Something Went Wrong, Please Check!";
                         }
                    }
                    else
                    {
                        return "Something Went Wrong, Please Check!";
                    }
             }
             else
             {

                   $this -> db -> select('refno,status');
                   $this -> db -> where('refno', $refno);
                   $query = $this -> db -> get('pomain');

                   $row = $query->row();

                  if($row->status=='PO_Rejected')
                  {
                      return "PO Status is Rejected! Unable to Download PO. ";

                  }
                  else
                  {
                      if($row->status=='Completed')
                      {
                          // Inserting in Table(userlog)
                          $data = array(
                             'user_guid' =>  $user_guid,
                             'logtype' =>  $logtype,
                             'docno' =>  $refno,
                             'downloaded_by' =>  $username
                          );

                          //set id column value as UUID
                          $this->db->set('log_guid', 'REPLACE(UPPER(UUID()),"-","")', FALSE);
                          $this->db->set('downloaded_at', 'NOW()', FALSE);
                          $this->db->insert('userlog', $data);
                          return TRUE;
                      }
                      else
                      {
                          return FALSE;
                          return "Something Went Wrong, Please Check!";
                      }
                  }
              }
        }


}
?>