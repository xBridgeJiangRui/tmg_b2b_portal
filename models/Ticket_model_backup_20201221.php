<?php

class Ticket_model extends CI_Model
{
      public function ticket ()
      {      
        $user_guid = $_SESSION['user_guid'];
        $user_group = $_SESSION['user_group_name'];
            if ( $user_group== 'SUPER_ADMIN') {
                $sql = "SELECT COUNT(*) as numrow FROM lite_b2b.ticket";
            } else {
                $sql = "SELECT COUNT(*) as numrow FROM lite_b2b.ticket WHERE created_by = '$user_guid'";
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

      function posts_search($query,$limit,$start,$search,$col,$dir)
      { 
            $user_group = $_SESSION['user_group_name'];
            if ( $user_group== 'SUPER_ADMIN') {
                $sql = $query." Where ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%' OR
                    e.user_name LIKE '%".$search."%'

                    ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";
                    ";
            } else {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%')

                    ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";
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
                $sql = $query." WHERE ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%' OR
                    e.user_name LIKE '%".$search."%'
                    
                    ";
            } else {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%')
                    
                    ";
            }
            

            $query = $this->db->query($sql);
            return $query->num_rows();
      }




}

?>