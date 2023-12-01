<?php

class Registration_model extends CI_Model
{
      public function register ($customer_guid)
      {      
        $user_guid = $_SESSION['user_guid'];
        $user_group = $_SESSION['user_group_name'];
            if ( $user_group== 'SUPER_ADMIN') {
                $sql = "SELECT COUNT(*) as numrow FROM lite_b2b.register WHERE customer_guid = '$customer_guid'";
            } else {
                $sql = "SELECT COUNT(*) as numrow FROM lite_b2b.register WHERE customer_guid = '$customer_guid'";
            }
            
            $query = $this->db->query($sql);
            return $query;
      }

      function allposts($query,$limit,$start,$col,$dir)
      {   
        if($col == '')
        {
          $sql = $query." LIMIT " .$start. " , " .$limit. ";";
        }
        else
        {
          $sql = $query." ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";";
        }

        $query = $this->db->query($sql);
        //echo $this->db->last_query();die;

       if($query->num_rows()>0)
       {
           return $query->result(); 
       }
       else
       {
           return null;
       }
      }

     function posts_search($query,$limit,$start,$search,$col,$dir)
     { 
          $user_group = $_SESSION['user_group_name'];
          if($col == '')
          {
            $order_sql = "";
          }
          else
          {
            $order_sql = "ORDER BY " .$col. "  " .$dir. "";
          }

          if ( $user_group== 'SUPER_ADMIN') {
              $sql = $query." AND 
                  aa.supplier_name LIKE '%".$search."%' OR 
                  aa.acc_name LIKE '%".$search."%' OR 
                  aa.create_by LIKE '%".$search."%' OR
                  aa.form_status LIKE '%".$search."%' OR
                  aa.comp_email LIKE '%".$search."%' OR
                  aa.register_no LIKE '%".$search."%' 

                  $order_sql LIMIT " .$start. " , " .$limit. ";
                  ";
          } else {
              $sql = $query." AND (register_no LIKE '%".$search."%' OR 
                    aa.supplier_name LIKE '%".$search."%' OR 
                  aa.acc_name LIKE '%".$search."%' OR 
                  aa.create_by LIKE '%".$search."%' OR
                  aa.form_status LIKE '%".$search."%' OR
                  aa.comp_email LIKE '%".$search."%' OR
                  aa.register_no LIKE '%".$search."%' )

                  $order_sql LIMIT " .$start. " , " .$limit. ";
                  ";
          }
          

          $query = $this->db->query($sql);

          //echo $this->db->last_query();die;
          if($query->num_rows()>0)
          {
                return $query->result();  
          }
          else
          {
                return null;
          }
     }

      function posts_search_count($query,$search)
      {
        $user_group = $_SESSION['user_group_name'];
          if ( $user_group== 'SUPER_ADMIN') {
              $sql = $query." AND 
                  aa.supplier_name LIKE '%".$search."%' OR 
                  aa.acc_name LIKE '%".$search."%' OR 
                  aa.create_by LIKE '%".$search."%' OR
                  aa.form_status LIKE '%".$search."%' OR
                  aa.comp_email LIKE '%".$search."%' OR
                  aa.register_no LIKE '%".$search."%' 

                  ;
                  ";
          } else {
              $sql = $query." AND (register_no LIKE '%".$search."%' OR 
                    aa.supplier_name LIKE '%".$search."%' OR 
                  aa.acc_name LIKE '%".$search."%' OR 
                  aa.create_by LIKE '%".$search."%' OR
                  aa.form_status LIKE '%".$search."%' OR
                  aa.comp_email LIKE '%".$search."%' OR
                  aa.register_no LIKE '%".$search."%' )

                  ;
                  ";
          }
            

            $query = $this->db->query($sql);
            return $query->num_rows();
      }
      
        function register_update($register_guid)
    {
        $this->db->select('register_child.*');
        $this->db->where($this->register_guid, $register_guid);
        $this->db->join('register', 'register.register_guid = register_child.register_guid');
       
    }




}

?>