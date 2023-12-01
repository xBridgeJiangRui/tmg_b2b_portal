<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_setup_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // insert data
    function insert_data($table, $data)
    {
        $this->db->insert($table, $data);
    }

    // update data
    function update_data($table,$col_guid, $guid, $data)
    {
        $this->db->where($col_guid, $guid);
        $this->db->update($table, $data);
    }

    // delete data
    function delete_data($table, $col_guid, $guid)
    {

        $this->db->where($col_guid, $guid);
        $this->db->delete($table);
    }

}
