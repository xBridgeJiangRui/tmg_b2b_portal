<?php

class Training_user_model extends CI_Model
{
      public function register ($customer_guid)
      {      
        $user_guid = $_SESSION['user_guid'];
        $user_group = $_SESSION['user_group_name'];
            if ( $user_group== 'SUPER_ADMIN') {
                $sql = "SELECT COUNT(*) as numrow FROM lite_b2b.training_user_main WHERE customer_guid = '$customer_guid'";
            } else {
                $sql = "SELECT COUNT(*) as numrow FROM lite_b2b.training_user_main WHERE customer_guid = '$customer_guid'";
            }
            
            $query = $this->db->query($sql);
            return $query;
      }

        function allposts($query,$limit,$start,$col,$dir)
      {   
        $sql = $query." ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";";
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

     // function posts_search($query,$limit,$start,$search,$col,$dir)
     // { 
     //       $user_group = $_SESSION['user_group_name'];
     //       if ( $user_group== 'SUPER_ADMIN') {
     //           $sql = $query." Where supplier_group_guid LIKE '%".$search."%' OR 
     //               b.comp_name LIKE '%".$search."%' OR 
     //               c.retailer_name LIKE '%".$search."%' OR 
     //               a.created_at LIKE '%".$search."%' OR
               
     //               ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";
     //               ";
     //       } else {
     //           $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
     //                 b.comp_name LIKE '%".$search."%' OR 
     //               c.retailer_name LIKE '%".$search."%' OR 
     //               a.created_at LIKE '%".$search."%' )

     //               ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";
     //               ";
     //       }
           

     //       $query = $this->db->query($sql);

     //       //echo $this->db->last_query();die;
     //       if($query->num_rows()>0)
     //       {
     //             return $query->result();  
     //       }
     //       else
     //       {
     //             return null;
     //       }
     // }

      function posts_search_count($query,$search)
      {
            $user_group = $_SESSION['user_group_name'];
            if ( $user_group== 'SUPER_ADMIN') {
                $sql = $query." WHERE session_id LIKE '%".$search."%' OR 
                    b.comp_name LIKE '%".$search."%' OR 
                    c.retailer_name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    
                    ";
            } else {
                $sql = $query." AND (session_id LIKE '%".$search."%' OR 
                     b.comp_name LIKE '%".$search."%' OR 
                    c.retailer_name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%')
                    
                    ";
            }
            

            $query = $this->db->query($sql);
            return $query->num_rows();
      }






}

?>