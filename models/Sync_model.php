<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sync_model extends CI_Model
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

     // insert data
    function insert_log($logtable, $logdata)
    {
        $this->db->insert($logtable, $logdata);
    }

    // update data
    function update_data($table,$col_guid, $guid, $data,$col_guid2, $guid2,$col_guid3, $guid3)
    {
        $this->db->where($col_guid, $guid);
        $this->db->where($col_guid2, $guid2);
        $this->db->where($col_guid3, $guid3);
        $this->db->update($table, $data);
    }

    // delete data
    function delete_data($table, $col_guid, $guid)
    {
        $this->db->where($col_guid, $guid);
        $this->db->delete($table);
    }

    // insert ingnore batch data
    function insert_batch($table, $data)
    {
        $this->db->insert_batch($table, $data);
    }    
    // insert data
    function insert_cr($table2, $data2)
    {
        $this->db->insert($table2, $data2);
    }

    function update_batch($table, $col_guid, $guid, $data_up,$key)
    {
        $this->db->where_in($col_guid, $guid);
        $this->db->update_batch($table, $data_up, $key);
    }

     function insert_ignore_batch($table,$data)
    {
        $this->db->insert_ignore_batch($table, $data);
    }

  /*  public function panda_sync()
    {
        $sql = "insert into backend.itemmaster
set itemcode='001',description='Testing 123'
ON DUPLICATE KEY UPDATE
 description='XXX'"
    }*/

    public function panda_insert_record()
    {
        $sql = "SELECT * FROM(
SELECT a.*, IFNULL(trans_guid,'addthis') AS trans_guid FROM 
(SELECT trans_guid AS acc_guid 
, trans_type AS acc_trans
, outlet AS acc_location
, refno AS acc_refno
, line AS acc_line
, bizdate AS acc_bizdate 
, outlet AS acc_outlet
, description AS acc_description
, amount AS acc_amount
, taxableamt AS acc_taxableamt
, amt_dr AS acc_amt_dr
, amt_cr AS acc_amt_cr
, taxable_dr AS acc_taxable_dr
, taxable_cr AS acc_taxable_cr
, gst_amt AS acc_gst_amt
, tax_rate AS acc_tax_rate
, gl_type AS acc_gltype
, gl_code AS acc_glcode
, gst_adj AS acc_gstadj
FROM junk_db.acc_trans_c2 
WHERE description = 'CASH'
)a
LEFT JOIN 
(
SELECT trans_guid
, trans_type
, location
, refno
, line
, bizdate
, outlet
, description
, amount
FROM panda_cm.`daily_trans`
WHERE description = 'CASH'
) b
ON a.acc_guid = b.trans_guid
) c
WHERE trans_guid = 'addthis'";
    $query = $this->db->query($sql);
    return $query;
    }

    public function panda_update_amount()
    {
        $sql = "SELECT * FROM(
SELECT a.*, amount FROM 
(SELECT trans_guid AS acc_guid 
, trans_type AS acc_trans
, outlet AS acc_location
, refno AS acc_refno
, line AS acc_line
, bizdate AS acc_bizdate 
, outlet AS acc_outlet
, description AS acc_description
, amount AS acc_amount
, taxableamt AS acc_taxableamt
, amt_dr AS acc_amt_dr
, amt_cr AS acc_amt_cr
, taxable_dr AS acc_taxable_dr
, taxable_cr AS acc_taxable_cr
, gst_amt AS acc_gst_amt
, tax_rate AS acc_tax_rate
, gl_type AS acc_gltype
, gl_code AS acc_glcode
, gst_adj AS acc_gstadj
FROM junk_db.acc_trans_c2 
WHERE description = 'CASH'
)a
LEFT JOIN 
(
SELECT trans_guid
, trans_type
, location
, refno
, line
, bizdate
, outlet
, description
, amount
FROM panda_cm.`daily_trans`
WHERE description = 'CASH'
) b
ON a.acc_guid = b.trans_guid AND a.acc_description = b.description and a.acc_location = b.location
) c
WHERE acc_amount <> amount";
 $query = $this->db->query($sql);
    return $query;
    }

    public function panda_besides_cash()
    {
        $sql = "SELECT * FROM(
SELECT a.*, IFNULL(trans_guid,'addthis') AS trans_guid FROM 
(SELECT trans_guid AS acc_guid 
, trans_type AS acc_trans
, outlet AS acc_location
, refno AS acc_refno
, line AS acc_line
, bizdate AS acc_bizdate 
, outlet AS acc_outlet
, description AS acc_description
, amount AS acc_amount
, taxableamt AS acc_taxableamt
, amt_dr AS acc_amt_dr
, amt_cr AS acc_amt_cr
, taxable_dr AS acc_taxable_dr
, taxable_cr AS acc_taxable_cr
, gst_amt AS acc_gst_amt
, tax_rate AS acc_tax_rate
, gl_type AS acc_gltype
, gl_code AS acc_glcode
, gst_adj AS acc_gstadj
FROM junk_db.acc_trans_c2 
WHERE gl_type IN ('PMT_CC','PMT_ATM','PMT_SV','PMT_OV','PMT_VR')
)a
LEFT JOIN 
(
SELECT trans_guid
, trans_type
, location
, refno
, line
, bizdate
, outlet
, description
, amount
, gl_type
FROM panda_cm.`daily_trans`
WHERE gl_type IN ('PMT_CC','PMT_ATM','PMT_SV','PMT_OV','PMT_VR')
) b
ON a.acc_guid = b.trans_guid
) c
WHERE trans_guid = 'addthis'";
    $query = $this->db->query($sql);
    return $query;
    }

 public function panda_update_besides_cash()
 {
           $sql = "SELECT * FROM(
SELECT a.*, amount FROM 
(SELECT trans_guid AS acc_guid 
, trans_type AS acc_trans
, outlet AS acc_location
, refno AS acc_refno
, line AS acc_line
, bizdate AS acc_bizdate 
, outlet AS acc_outlet
, description AS acc_description
, amount AS acc_amount
, taxableamt AS acc_taxableamt
, amt_dr AS acc_amt_dr
, amt_cr AS acc_amt_cr
, taxable_dr AS acc_taxable_dr
, taxable_cr AS acc_taxable_cr
, gst_amt AS acc_gst_amt
, tax_rate AS acc_tax_rate
, gl_type AS acc_gltype
, gl_code AS acc_glcode
, gst_adj AS acc_gstadj
FROM junk_db.acc_trans_c2 
WHERE gl_type IN ('PMT_CC','PMT_ATM','PMT_SV','PMT_OV','PMT_VR')
)a
LEFT JOIN 
(
SELECT trans_guid
, trans_type
, location
, refno
, line
, bizdate
, outlet
, description
, amount
FROM panda_cm.`daily_trans`
WHERE gl_type IN ('PMT_CC','PMT_ATM','PMT_SV','PMT_OV','PMT_VR')
) b
ON a.acc_guid = b.trans_guid  AND a.acc_description = b.description and a.acc_location = b.location
) c
WHERE acc_amount <> amount";
 $query = $this->db->query($sql);
    return $query;
 }

}

