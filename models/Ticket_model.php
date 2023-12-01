<?php

class Ticket_model extends CI_Model
{
      public function ticket ($query,$limit,$start,$col,$dir)
      {      
        $sql = $query." GROUP BY a.ticket_guid;";
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

        function allposts($query,$limit,$start,$col,$dir)
      {   
        $sql = $query." GROUP BY a.ticket_guid ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";";
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
            if (in_array('OAT',$this->session->userdata('module_code'))) {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%' OR
                    e.user_name LIKE '%".$search."%' OR
                    f.supplier_name LIKE '%".$search."%')

                     GROUP BY a.ticket_guid ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";
                    ";
            }
            else if (in_array('OABYCUST',$this->session->userdata('module_code'))) {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%' OR
                    e.user_name LIKE '%".$search."%' OR
                    f.supplier_name LIKE '%".$search."%')

                     GROUP BY a.ticket_guid ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";
                    ";
            }
            else {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    d.name LIKE '%".$search."%' OR 
                    e.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    c.user_name LIKE '%".$search."%' OR
                    f.supplier_name LIKE '%".$search."%')

                     GROUP BY a.ticket_guid ORDER BY " .$col. "  " .$dir. " LIMIT " .$start. " , " .$limit. ";
                    ";
            }
            

            $query = $this->db->query($sql);

            // echo $this->db->last_query();die;
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
            if (in_array('OAT',$this->session->userdata('module_code'))) {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%' OR
                    e.user_name LIKE '%".$search."%' OR
                    f.supplier_name LIKE '%".$search."%') GROUP BY a.ticket_guid 
                    
                    ";
            } 
            else if (in_array('OABYCUST',$this->session->userdata('module_code'))) {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    b.name LIKE '%".$search."%' OR 
                    c.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    d.user_name LIKE '%".$search."%' OR
                    e.user_name LIKE '%".$search."%' OR
                    f.supplier_name LIKE '%".$search."%') GROUP BY a.ticket_guid 
                    
                    ";
            }                 
            else {
                $sql = $query." AND (ticket_number LIKE '%".$search."%' OR 
                    d.name LIKE '%".$search."%' OR 
                    e.name LIKE '%".$search."%' OR 
                    a.created_at LIKE '%".$search."%' OR
                    ticket_status LIKE '%".$search."%' OR
                    c.user_name LIKE '%".$search."%' OR
                    f.supplier_name LIKE '%".$search."%') GROUP BY a.ticket_guid 
                    
                    ";
            }
            

            $query = $this->db->query($sql);
            return $query->num_rows();
      }




}

?>