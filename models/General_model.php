<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class General_model extends CI_Model
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
    function update_data($table, $col_guid, $guid, $data)
    {
        $this->db->where($col_guid, $guid);
        $this->db->update($table, $data);
    }

    // update2 data
    function update2_data($table, $col_guid, $guid, $col_guid2, $guid2, $data)
    {
        $this->db->where($col_guid, $guid);
        $this->db->where($col_guid2, $guid2);
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

    function update_batch($table, $col_guid, $guid, $data_up, $key)
    {
        $this->db->where_in($col_guid, $guid);
        $this->db->update_batch($table, $data_up, $key);
    }

    function insert_ignore_batch($table, $data)
    {
        $this->db->insert_ignore_batch($table, $data);
    }

    // insert data
    function replace_data($table, $data)
    {
        $this->db->replace($table, $data);
    }

    function load_page($data, $check_module)
    {
        if ($check_module == 'panda_po_2') {
            $data_footer = array(
                'activity_logs_section' => 'po'
            );

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('po/panda_po_list_view', $data);
            $this->load->view('general_modal', $data);
            $this->load->view('footer', $data_footer);
        };

        if ($check_module == 'panda_gr') {

            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('gr/panda_gr_list_view', $data);
            $this->load->view('footer');
        };

        if ($check_module == 'panda_grda') {
            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('grda/panda_grda_list_view', $data);
            $this->load->view('footer');
        };

        if ($check_module == 'panda_prdncn') {
            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('prdncn/panda_prdncn_list_view', $data);
            $this->load->view('footer');
        };

        if ($check_module == 'panda_pdncn') {
            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('pdncn/panda_pdncn_list_view', $data);
            $this->load->view('footer');
        };

        if ($check_module == 'panda_pci') {
            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('pci/panda_pci_list_view', $data);
            $this->load->view('footer');
        };

        if ($check_module == 'panda_di') {
            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('di/panda_di_list_view', $data);
            $this->load->view('footer');
        };

        if ($check_module == 'panda_return_collection') {
            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('return_collection/panda_rc_list_view', $data);
            $this->load->view('footer');
        };

        if ($check_module == 'panda_gr_download') {
            $this->panda->get_uri();
            $this->load->view('header');
            $this->load->view('gr/panda_gr_download', $data);
            $this->load->view('footer');
        };
    }

    function check_supchecklist_query($customer_guid)
    {
        $result = $this->db->query("SELECT count(1) as count
        FROM 
        (SELECT * FROM b2b_summary.supcus WHERE customer_guid = '$customer_guid' AND TYPE IN ('S','P','C')) a
        LEFT JOIN
        (SELECT  a.block,a.customer_guid, a.code, a.name, a.supcus_guid,a.reg_no,a.AccountCode,
        MAX(b.remark1) AS remark1
        ,MAX(CASE WHEN b.`title1` = 'IsActive' THEN b.`value1` ELSE NULL END ) AS IsActive
        , MAX(CASE WHEN b.`title1` = 'PAYMENT' THEN b.`value1` ELSE NULL END ) AS PAYMENT
        , MAX(CASE WHEN b.`title1` = 'PIC' THEN b.`value1` ELSE NULL END ) AS PIC
        , MAX(CASE WHEN b.`title1` = 'sup_name' THEN b.`value1` ELSE NULL END ) AS sup_name
        , MAX(CASE WHEN b.`title1` = 'STATUS' THEN b.`value1` ELSE NULL END ) AS STATUS
        , MAX(CASE WHEN b.`title1` = 'invoice_no' THEN b.`value1` ELSE NULL END ) AS invoice_no
        , MAX(CASE WHEN b.`title1` = 'tel' THEN b.`value1` ELSE NULL END ) AS tel
        , MAX(CASE WHEN b.`title1` = 'ACCEPT_FORM' THEN b.`value1` ELSE NULL END ) AS ACCEPT_FORM
        , MAX(CASE WHEN b.`title1` = 'REG_FORM' THEN b.`value1` ELSE NULL END ) AS REG_FORM
        , MAX(CASE WHEN b.`title1` = 'training_pax' THEN b.`value1` ELSE NULL END ) AS training_pax
        , MAX(CASE WHEN b.`title1` = 'STATUS' THEN b.`remark1` ELSE NULL END ) AS remark
        FROM b2b_summary.supcus  AS a
        LEFT JOIN lite_b2b.`supplier_checklist` AS b
        ON a.`customer_guid` = b.`customer_guid` 
        AND a.code = b.scode 
        WHERE a.`customer_guid` = '$customer_guid'
        GROUP BY scode,type
        ORDER BY NAME ASC
        ) b
        ON a.customer_guid = b.customer_guid
        AND a.code = b.code
        AND a.supcus_guid = b.supcus_guid
");
        return $result;
    }

    function check_supchecklist_result($limit, $start, $customer_guid, $dir, $order)
    {
        $result = $this->db->query("SELECT 
        IFNULL(a.customer_guid, b.customer_guid) AS customer_guid
        , IFNULL(a.type, b.type) AS type
        , IFNULL(a.AccountCode, b.AccountCode) AS `AccountCode`
        , IFNULL(a.code, b.code) AS `code`
        , IFNULL(a.name, b.name) AS `name`
        , IFNULL(a.reg_no, b.reg_no) AS `reg_no`
        , IFNULL(a.block, b.block) AS `block`
        ,IF(b.remark1 IS NULL OR b.remark1 = '', '', b.remark1) as remark1
        , IFNULL(a.supcus_guid, b.supcus_guid) AS supcus_guid
        , IFNULL(b.IsActive, '') AS IsActive
        , IFNULL(IF(b.PAYMENT = '',0,b.PAYMENT),0) AS PAYMENT
        , IFNULL(b.PIC, '' ) AS PIC 
        , IFNULL(b.STATUS, '') AS STATUS
        , IFNULL(b.invoice_no, '') AS invoice_no
        , IFNULL(b.sup_name, '') AS sup_name
        , IFNULL(b.tel, '') AS tel
        , IFNULL(b.ACCEPT_FORM, '') AS ACCEPT_FORM
        , IFNULL(b.REG_FORM, '') AS REG_FORM
        , IFNULL(b.training_pax, '') AS training_pax
        , IFNULL(b.remark, '') AS remark
        , '' as folder
        , if(consign = '1', concat('CONSIGN'), concat('OUTRIGHT')) as supply_type
        FROM 
        (SELECT * FROM b2b_summary.supcus WHERE customer_guid = '$customer_guid' AND TYPE IN ('S','P','C')) a
        LEFT JOIN
        (SELECT  a.type,a.block,a.customer_guid, a.code, a.name, a.supcus_guid,a.reg_no,a.AccountCode,
        MAX(b.remark1) AS remark1
        ,MAX(CASE WHEN b.`title1` = 'IsActive' THEN b.`value1` ELSE NULL END ) AS IsActive
        , MAX(CASE WHEN b.`title1` = 'PAYMENT' THEN b.`value1` ELSE NULL END ) AS PAYMENT
        , MAX(CASE WHEN b.`title1` = 'PIC' THEN b.`value1` ELSE NULL END ) AS PIC
        , MAX(CASE WHEN b.`title1` = 'sup_name' THEN b.`value1` ELSE NULL END ) AS sup_name
        , MAX(CASE WHEN b.`title1` = 'STATUS' THEN b.`value1` ELSE NULL END ) AS STATUS
        , MAX(CASE WHEN b.`title1` = 'invoice_no' THEN b.`value1` ELSE NULL END ) AS invoice_no
        , MAX(CASE WHEN b.`title1` = 'tel' THEN b.`value1` ELSE NULL END ) AS tel
        , MAX(CASE WHEN b.`title1` = 'ACCEPT_FORM' THEN b.`value1` ELSE NULL END ) AS ACCEPT_FORM
        , MAX(CASE WHEN b.`title1` = 'REG_FORM' THEN b.`value1` ELSE NULL END ) AS REG_FORM
        , MAX(CASE WHEN b.`title1` = 'training_pax' THEN b.`value1` ELSE NULL END ) AS training_pax
        , MAX(CASE WHEN b.`title1` = 'STATUS' THEN b.`remark1` ELSE NULL END ) AS remark
        FROM b2b_summary.supcus  AS a
        LEFT JOIN lite_b2b.`supplier_checklist` AS b
        ON a.`customer_guid` = b.`customer_guid` 
        AND a.code = b.scode 
        WHERE a.`customer_guid` = '$customer_guid'
        GROUP BY scode,type
        ORDER BY NAME ASC
        ) b
        ON a.customer_guid = b.customer_guid
        AND a.code = b.code
        AND a.supcus_guid = b.supcus_guid
        ORDER BY  $order $dir limit $start,$limit");
        return $result->result();
    }

    function posts_supchecklist_search($limit, $start, $customer_guid, $dir, $order, $search)
    {
        $result = $this->db->query("SELECT * from (
            select
        IFNULL(a.customer_guid, b.customer_guid) AS customer_guid
        , IFNULL(a.type, b.type) AS type
        , IFNULL(a.AccountCode, b.AccountCode) AS `AccountCode`
        , IFNULL(a.code, b.code) AS `code`
        , IFNULL(a.name, b.name) AS `name`
        , IFNULL(a.reg_no, b.reg_no) AS `reg_no`
        , IFNULL(a.block, b.block) AS `block`
        ,IF(b.remark1 IS NULL OR b.remark1 = '', '', b.remark1) as remark1
        , IFNULL(a.supcus_guid, b.supcus_guid) AS supcus_guid
        , IFNULL(b.IsActive, '') AS IsActive
        , IFNULL(IF(b.PAYMENT = '',0,b.PAYMENT),0) AS PAYMENT
        , IFNULL(b.PIC, '' ) AS PIC 
        , IFNULL(b.STATUS, '') AS STATUS
        , IFNULL(b.invoice_no, '') AS invoice_no
        , IFNULL(b.sup_name, '') AS sup_name
        , IFNULL(b.tel, '') AS tel
        , IFNULL(b.ACCEPT_FORM, '') AS ACCEPT_FORM
        , IFNULL(b.REG_FORM, '') AS REG_FORM
        , IFNULL(b.training_pax, '') AS training_pax
        , IFNULL(b.remark, '') AS remark
        , '' as folder
        , if(consign = '1', concat('CONSIGN'), concat('OUTRIGHT')) as supply_type
        FROM 
        (SELECT * FROM b2b_summary.supcus WHERE customer_guid = '$customer_guid' AND TYPE IN ('S','P','C')) a
        LEFT JOIN
        (SELECT  a.type,a.block,a.customer_guid, a.code, a.name, a.supcus_guid,a.reg_no,a.AccountCode,
        MAX(b.remark1) AS remark1
        ,MAX(CASE WHEN b.`title1` = 'IsActive' THEN b.`value1` ELSE NULL END ) AS IsActive
        , MAX(CASE WHEN b.`title1` = 'PAYMENT' THEN b.`value1` ELSE NULL END ) AS PAYMENT
        , MAX(CASE WHEN b.`title1` = 'PIC' THEN b.`value1` ELSE NULL END ) AS PIC
        , MAX(CASE WHEN b.`title1` = 'sup_name' THEN b.`value1` ELSE NULL END ) AS sup_name
        , MAX(CASE WHEN b.`title1` = 'STATUS' THEN b.`value1` ELSE NULL END ) AS STATUS
        , MAX(CASE WHEN b.`title1` = 'invoice_no' THEN b.`value1` ELSE NULL END ) AS invoice_no
        , MAX(CASE WHEN b.`title1` = 'tel' THEN b.`value1` ELSE NULL END ) AS tel
        , MAX(CASE WHEN b.`title1` = 'ACCEPT_FORM' THEN b.`value1` ELSE NULL END ) AS ACCEPT_FORM
        , MAX(CASE WHEN b.`title1` = 'REG_FORM' THEN b.`value1` ELSE NULL END ) AS REG_FORM
        , MAX(CASE WHEN b.`title1` = 'training_pax' THEN b.`value1` ELSE NULL END ) AS training_pax
        , MAX(CASE WHEN b.`title1` = 'STATUS' THEN b.`remark1` ELSE NULL END ) AS remark
        FROM b2b_summary.supcus  AS a
        LEFT JOIN lite_b2b.`supplier_checklist` AS b
        ON a.`customer_guid` = b.`customer_guid` 
        AND a.code = b.scode 
        WHERE a.`customer_guid` = '$customer_guid'
        GROUP BY scode,type
        ORDER BY NAME ASC
        ) b
        ON a.customer_guid = b.customer_guid
        AND a.code = b.code
        AND a.supcus_guid = b.supcus_guid
        ) c 
        WHERE  (`code` LIKE '%$search%' or `name` like '%$search%' OR PIC LIKE '%$search%' OR `STATUS` LIKE '%$search%' OR PAYMENT LIKE '%$search%' OR supply_type LIKE '%$search%' ) 
            ORDER BY  $order $dir limit $start,$limit ");
        return $result->result();
    }


    function check_module_query($check_status, $loc, $check_module, $q_doc_from_to, $q_exp_from_to, $q_refno, $q_period_code, $search)
    {
        $customer_guid = $this->session->userdata('customer_guid');
        if ($check_module == 'panda_po_2') //  check module = panda_po_2
        {

            if (in_array('IAVA', $_SESSION['module_code'])) {
                //$result = $this->db->query("SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."' and location IN ($loc) and status IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");
                // $result = $this->db->query("SELECT count(1) as count FROM (SELECT customer_guid, refno, loc_group, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax, IF(status = '', 'NEW', status) as status, rejected_remark, scode as scode1, sname as sname1,date_format(deliverdate, '%Y-%m-%d %a') delivery_date from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."' and loc_group IN ($loc) and status IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT(gr_refno) as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$_SESSION['customer_guid']."' AND gr_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL - 6 month) GROUP BY po_refno) b ON a.RefNo = b.po_refno WHERE (a.refno like '%".$search."%' or a.loc_group like '%".$search."%' or a.podate like '%".$search."%' or a.scode like '%".$search."%' or a.sname like '%".$search."%' or b.gr_refno like '%".$search."%') ");

                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.pomain a LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND b.`customer_guid` = '$customer_guid' LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' WHERE a.customer_guid = '$customer_guid' AND a.loc_group IN ($loc) AND a.STATUS IN ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code AND (a.refno like " . "'%" . $search . "%'" . "or a.loc_group like " . "'%" . $search . "%'" . " or a.podate like " . "'%" . $search . "%'" . " or a.scode like " . "'%" . $search . "%'" . " or  a.sname like " . "'%" . $search . "%'" . " or  b.gr_refno like " . "'%" . $search . "%'" . " )");

                // $sql = "SELECT count(1) as count FROM b2b_summary.pomain a LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND b.`customer_guid` = '$customer_guid' LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' WHERE a.customer_guid = '$customer_guid' AND a.loc_group IN ($loc) AND a.STATUS IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno $q_period_code AND (a.refno like "."'%".$search."%'". "or a.loc_group like "."'%".$search."%'"." or a.podate like "."'%".$search."%'"." or a.scode like "."'%".$search."%'"." or  a.sname like "."'%".$search."%'"." or  b.gr_refno like "."'%".$search."%'"." )";
                // echo $sql;die;                     
            } else {
                //$result = $this->db->query("SELECT customer_guid, refno, location, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."'and scode IN (".$_SESSION['query_supcode'].")  and location IN ($loc) and status IN ($check_status)  $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");
                // $result = $this->db->query("SELECT count(1) as count FROM (SELECT customer_guid, refno, loc_group, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode as scode1, sname as sname1,date_format(deliverdate, '%Y-%m-%d %a') delivery_date from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."' and scode IN (".$_SESSION['query_supcode'].")  and loc_group IN ($loc) and status IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT(gr_refno) as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid =  '".$_SESSION['customer_guid']."' AND gr_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL - 6 month) GROUP BY po_refno) b ON a.RefNo = b.po_refno WHERE (a.refno like '%".$search."%' or a.loc_group like '%".$search."%' or a.podate like '%".$search."%' or a.scode like '%".$search."%' or a.sname like '%".$search."%' or b.gr_refno like '%".$search."%')");

                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.pomain a LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND b.`customer_guid` = '$customer_guid' LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' WHERE a.customer_guid = '$customer_guid' AND a.loc_group IN ($loc) AND a.STATUS IN ($check_status) and a.scode IN (" . $_SESSION['query_supcode'] . ") AND a.in_kind = 0  $q_doc_from_to $q_exp_from_to $q_refno $q_period_code AND (a.refno like " . "'%" . $search . "%'" . "or a.loc_group like " . "'%" . $search . "%'" . " or a.podate like " . "'%" . $search . "%'" . " or a.scode like " . "'%" . $search . "%'" . " or  a.sname like " . "'%" . $search . "%'" . " or  b.gr_refno like " . "'%" . $search . "%'" . " )");
                // and (refno like "%'.$search.'%" or location like "%'.$search.'%" or podate like "%'.$search.'%" or scode like "%'.$search.'%" or  sname like "%'.$search.'%")
            };
        }
        if ($check_module == 'panda_gr') // panda_gr
        {

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT COUNT(DISTINCT a.refno) as count FROM b2b_summary.grmain  AS a
                LEFT JOIN (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid
                LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "'  and a.loc_group in ($loc) and a.status IN ($check_status) AND a.in_kind = 0 and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");

                //  echo $this->db->last_query();die;
            } else {
                $result = $this->db->query("SELECT COUNT(DISTINCT a.refno) as count FROM b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid
                LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' and a.loc_group in ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.status IN ($check_status) AND a.in_kind = 0 and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");
            };
        }
        if ($check_module == 'panda_grda') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.grmain AS a
                INNER JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno  and a.customer_guid = b.customer_guid
                WHERE b.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.loc_group in ($loc) AND b.transtype IN ($check_status) AND a.in_kind = 0 and (b.refno like '%$search%' or a.loc_group like '%$search%' or sup_cn_date like '%$search%' or code like '%$search%' or name like '%$search%' or b.`sup_cn_no` like '%$search%' or a.invno like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");
            } else {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.grmain AS a
                INNER JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno  and a.customer_guid = b.customer_guid
                WHERE b.`customer_guid` = '" . $_SESSION['customer_guid'] . "' AND a.loc_group in ($loc) AND b.transtype IN ($check_status) AND a.in_kind = 0 AND ap_sup_code IN (" . $_SESSION['query_supcode'] . ") and (b.refno like '%$search%' or a.loc_group like '%$search%' or sup_cn_date like '%$search%' or code like '%$search%' or name like '%$search%' or b.`sup_cn_no` like '%$search%' or a.invno like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");
            };
        }

        if ($check_module == 'panda_prdncn') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT SUM(count) as count FROM (SELECT count(1) as count FROM  b2b_summary.dbnotemain a LEFT JOIN b2b_summary.dbnote_batch b
                ON a.refno = b.b2b_dn_refno AND a.`customer_guid` = b.`customer_guid` WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.locgroup IN ($loc) and a.type IN ($check_status) and (a.refno like '%$search%' or a.locgroup like '%$search%' or a.docdate like '%$search%' or a.code like '%$search%' or  a.name like '%$search%' or  a.status like '%$search%' or b.batch_no like '%$search%' or a.stock_collected_by like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                    UNION ALL
                    SELECT count(1) as count  FROM  b2b_summary.cnnotemain a WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.locgroup IN ($loc) and a.type IN ($check_status) and (a.refno like '%$search%' or a.locgroup like '%$search%' or a.docdate like '%$search%' or  a.code like '%$search%' or  a.name like '%$search%' or  a.status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code)a ");

                // $result = $this->db->query("SELECT SUM(count) as count FROM (SELECT count(1) as count FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "'  and locgroup IN ($loc) and type IN ($check_status) and (refno like '%$search%' or locgroup like '%$search%' or docdate like '%$search%' or  code like '%$search%' or  name like '%$search%' or  status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                //     UNION ALL
                //     SELECT count(1) as count  FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "'  and locgroup IN ($loc) and type IN ($check_status) and (refno like '%$search%' or locgroup like '%$search%' or docdate like '%$search%' or  code like '%$search%' or  name like '%$search%' or  status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code)a ");
            } else {
                $result = $this->db->query("SELECT SUM(count) as count FROM (SELECT count(1) as count FROM b2b_summary.dbnotemain a LEFT JOIN b2b_summary.dbnote_batch b
                ON a.refno = b.b2b_dn_refno AND a.`customer_guid` = b.`customer_guid` WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "' and a.locgroup IN ($loc) and a.type IN ($check_status)  and a.code IN (" . $_SESSION['query_supcode'] . ") and (a.refno like '%$search%' or a.locgroup like '%$search%' or a.docdate like '%$search%' or  a.code like '%$search%' or  a.name like '%$search%' or  a.status like '%$search%' or b.batch_no like '%$search%' or a.stock_collected_by like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                    UNION ALL
                    SELECT count(1) as count FROM  b2b_summary.cnnotemain a WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "' and a.locgroup IN ($loc) and a.type IN ($check_status)   and a.code IN (" . $_SESSION['query_supcode'] . ") and (a.refno like '%$search%' or a.locgroup like '%$search%' or a.docdate like '%$search%' or  a.code like '%$search%' or  a.name like '%$search%' or  a.status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code)a ");

                // $result = $this->db->query("SELECT SUM(count) as count FROM (SELECT count(1) as count FROM b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "' and locgroup IN ($loc) and type IN ($check_status)  and code IN (" . $_SESSION['query_supcode'] . ") and (refno like '%$search%' or locgroup like '%$search%' or docdate like '%$search%' or  code like '%$search%' or  name like '%$search%' or  status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                //     UNION ALL
                //     SELECT count(1) as count FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "' and locgroup IN ($loc) and type IN ($check_status)   and code IN (" . $_SESSION['query_supcode'] . ") and (refno like '%$search%' or locgroup like '%$search%' or docdate like '%$search%' or  code like '%$search%' or  name like '%$search%' or  status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code)a ");
            };
        }

        if ($check_module == 'panda_pdncn') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.cndn_amt WHERE trans_type IN ($check_status) AND  customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code and (refno like '%$search%' or loc_group like '%$search%' or docdate like '%$search%' or docno like '%$search%' or trans_type like '%$search%' or name like '%$search%' or code like '%$search%')");
            } else {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.cndn_amt WHERE trans_type IN ($check_status) AND  customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code and code IN (" . $_SESSION['query_supcode'] . ") and (refno like '%$search%' or loc_group like '%$search%' or docdate like '%$search%' or docno like '%$search%' or trans_type like '%$search%' )");
            }
        }

        if ($check_module == 'panda_pci') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and (inv_refno like '%$search%' or sup_code like '%$search%' or sup_name like '%$search%' or loc_group like '%$search%' or docdate like '%$search%' or promo_refno like '%$search%')");
            } else {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and sup_code IN (" . $_SESSION['query_supcode'] . ") and (inv_refno like '%$search%' or sup_code like '%$search%' or sup_name like '%$search%' or loc_group like '%$search%' or docdate like '%$search%' or promo_refno like '%$search%')");
            }
        }

        if ($check_module == 'panda_di') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.discheme_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and (inv_refno like '%$search%' or refno like '%$search%' or loc_group like '%$search%' or sup_code like '%$search%' or sup_name like '%$search%')");
            } else {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.discheme_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and sup_code IN (" . $_SESSION['query_supcode'] . ") and (inv_refno like '%$search%' or refno like '%$search%' or loc_group like '%$search%' or sup_code like '%$search%' or sup_name like '%$search%')");
            }
        }

        if ($check_module == 'panda_return_collection') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.dbnote_batch AS a INNER JOIN b2b_summary.supcus AS b ON a.sup_code = b.`code` AND a.customer_guid = b.customer_guid AND b.b2b_registration = '1' WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND a.location IN ($loc)    $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code and (a.batch_no like '%$search%' or a.location like '%$search%' or a.expiry_date like '%$search%' or a.sup_name like '%$search%' or a.doc_date like '%$search%' or a.sup_code like '%$search%' or a.b2b_dn_refno like '%$search%')");
            } else {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.dbnote_batch AS a INNER JOIN b2b_summary.supcus AS b ON a.sup_code = b.`code` AND a.customer_guid = b.customer_guid AND b.b2b_registration = '1' WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND a.location IN ($loc)    $q_doc_from_to $q_exp_from_to $q_refno and sup_code IN (" . $_SESSION['query_supcode'] . ") and status IN ($check_status) $q_period_code and (a.batch_no like '%$search%' or a.location like '%$search%' or a.expiry_date like '%$search%' or a.sup_name like '%$search%' or a.doc_date like '%$search%' or a.sup_code like '%$search%' or a.b2b_dn_refno like '%$search%')");
            }
        }

        if ($check_module == 'panda_gr_download') // panda_gr
        {

            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.grmain  AS a
                LEFT JOIN (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "'  and a.loc_group in ($loc) and a.status IN ($check_status) AND a.in_kind = 0 and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%' or a.cross_ref like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");

                //  echo $this->db->last_query();die;
            } else {
                $result = $this->db->query("SELECT count(1) as count FROM b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' and a.loc_group in ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.status IN ($check_status) AND a.in_kind = 0 and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%' or a.cross_ref like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno $q_period_code");
            };
        }
        // echo $this->db->last_query();die;

        return $result;
    }

    function check_module_result($limit, $start, $check_status, $q_doc_from_to, $q_exp_from_to, $q_refno, $q_period_code, $loc, $check_module, $dir, $order)
    {
        $customer_guid = $this->session->userdata('customer_guid');
        if ($check_module == 'panda_po_2') //  check module = panda_po_2
        {
            if (!in_array('VGR', $_SESSION['module_code'])) {
                $module = 'gr_download_child';
            } else {
                $module = 'gr_child';
            }
            // echo $start;
            if (in_array('IAVA', $_SESSION['module_code'])) {
                // $result = $this->db->query("SELECT a.*,b.gr_refno,c.portal_description as rejected_remarks FROM (SELECT customer_guid, refno, loc_group, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode as scode1, sname as sname1, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode, sname,date_format(deliverdate, '%Y-%m-%d %a') delivery_date from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."' and loc_group IN ($loc)  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$_REQUEST['loc']."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$_SESSION['customer_guid']."' AND gr_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL - 6 month) GROUP BY po_refno) b ON a.refno = b.po_refno LEFT JOIN status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' order by $order $dir limit $start,$limit");
                $result = $this->db->query("SELECT a.customer_guid, refno, loc_group, DATE_FORMAT(podate, '%Y-%m-%d %a') AS podate, DATE_FORMAT(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, ROUND(total, 2) AS total, ROUND(gst_tax_sum, 2) AS gst_tax_sum, ROUND(total_include_tax, 2) AS total_include_tax, IF(STATUS = '', 'NEW', STATUS) AS STATUS, rejected_remark, scode AS scode1, sname AS sname1, DATE_FORMAT(deliverdate, '%Y-%m-%d %a') delivery_date, GROUP_CONCAT( DISTINCT '<a href=" . base_url() . "index.php/panda_gr/" . $module . "?trans=',b.gr_refno,'&loc=" . $_REQUEST['loc'] . "&fmodule=1>',gr_refno,'</a>') AS gr_refno , c.portal_description AS rejected_remarks,IF(a.status = '', 'NEW', status) as status FROM b2b_summary.pomain a LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND b.`customer_guid` = '$customer_guid' LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' WHERE a.customer_guid = '$customer_guid' AND a.loc_group IN ($loc) AND a.STATUS IN ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code GROUP BY a.refno order by $order $dir limit $start,$limit");
                // print_r($result->result());
                // echo $this->db->last_query();die;

            } else {
                // $result = $this->db->query("SELECT *,c.portal_description as rejected_remarks FROM (SELECT customer_guid, refno, loc_group, date_format(podate, '%Y-%m-%d %a') as podate,date_format(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = '', 'NEW', status) as status, rejected_remark, scode as scode1, sname as sname1,date_format(deliverdate, '%Y-%m-%d %a') delivery_date from b2b_summary.pomain where customer_guid = '".$_SESSION['customer_guid']."'and scode IN (".$_SESSION['query_supcode'].")  and loc_group IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno and status IN ($check_status)  $q_period_code) a LEFT JOIN (SELECT po_refno,GROUP_CONCAT('<a href=".base_url()."index.php/panda_gr/".$module."?trans=',gr_refno,'&loc=".$_REQUEST['loc']."&fmodule=1>',gr_refno,'</a>') as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = '".$_SESSION['customer_guid']."' AND gr_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL - 6 month) GROUP BY po_refno) b ON a.RefNo = b.po_refno LEFT JOIN status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' order by $order $dir  limit $start,$limit");

                $result = $this->db->query("SELECT a.customer_guid, refno, loc_group, DATE_FORMAT(podate, '%Y-%m-%d %a') AS podate, DATE_FORMAT(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, ROUND(total, 2) AS total, ROUND(gst_tax_sum, 2) AS gst_tax_sum, ROUND(total_include_tax, 2) AS total_include_tax, IF(STATUS = '', 'NEW', STATUS) AS STATUS, rejected_remark, scode AS scode1, sname AS sname1, DATE_FORMAT(deliverdate, '%Y-%m-%d %a') delivery_date, GROUP_CONCAT( DISTINCT '<a href=" . base_url() . "index.php/panda_gr/" . $module . "?trans=',b.gr_refno,'&loc=" . $_REQUEST['loc'] . "&fmodule=1>',gr_refno,'</a>') AS gr_refno , c.portal_description AS rejected_remarks,IF(a.status = '', 'NEW', status) as status FROM b2b_summary.pomain a LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND b.`customer_guid` = '$customer_guid' LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' WHERE a.customer_guid = '$customer_guid' AND a.loc_group IN ($loc) AND a.STATUS IN ($check_status) and a.scode IN (" . $_SESSION['query_supcode'] . ") AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code GROUP BY a.refno order by $order $dir limit $start,$limit");
            };
        }
        if ($check_module == 'panda_gr') // panda_gr
        {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT 
                a.consign,
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno,
                z.inv_date as einvdate
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.loc_group in ($loc) and a.status in ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code GROUP BY a.customer_guid, a.refno order by $order $dir limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT 
                a.consign,
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno,
                z.inv_date as einvdate
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' and a.loc_group in ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.status in ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code GROUP BY a.customer_guid, a.refno order by $order $dir limit $start,$limit");
            };
        }
        if ($check_module == 'panda_grda') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT b.customer_guid
                , a.grdate 
                , IF(b.status = '', 'NEW', b.status) as status
                , b.refno
                , a.loc_group
                , b.`transtype`
                , ap_sup_code AS `code`
                , a.name
                , b.total_amt AS `varianceamt`
                , b.`sup_cn_no`
                , b.`sup_cn_date`
                , dncn_date
                , dncn_date_acc
                , a.invno
                FROM b2b_summary.grmain AS a
                INNER JOIN  (select *, SUM(varianceamt) AS total_amt from b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno  and a.customer_guid = b.customer_guid
                WHERE b.`customer_guid` ='" . $_SESSION['customer_guid'] . "' and b.transtype IN ($check_status) AND a.loc_group in ($loc) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code order by $order $dir  limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT b.customer_guid
                , a.grdate 
                , IF(b.status = '', 'NEW', b.status) as status
                , b.refno
                , a.loc_group
                , b.`transtype`
                , ap_sup_code AS `code`
                , a.name
                , b.total_amt AS `varianceamt`
                , b.`sup_cn_no`
                , b.`sup_cn_date`
                , dncn_date
                , dncn_date_acc
                , a.invno
                FROM b2b_summary.grmain AS a
                INNER JOIN  (select *, SUM(varianceamt) AS total_amt from b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno  and a.customer_guid = b.customer_guid
                WHERE b.`customer_guid` ='" . $_SESSION['customer_guid'] . "' and b.transtype IN ($check_status) AND a.loc_group in ($loc) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code AND ap_sup_code IN (" . $_SESSION['query_supcode'] . ") order by $order $dir  limit $start,$limit");
            };
        }


        if ($check_module == 'panda_prdncn') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("select * from (
                    SELECT  b.`batch_no`,b.`doc_date` AS strb_doc_date,b.uploaded_image, a.stock_collected,a.stock_collected_by,a.date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum, round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM b2b_summary.dbnotemain a LEFT JOIN b2b_summary.dbnote_batch b
                    ON a.refno = b.b2b_dn_refno AND a.`customer_guid` = b.`customer_guid` WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.locgroup IN ($loc) and a.type IN ($check_status)  $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                UNION ALL
                SELECT '' AS batch_no, '' AS strb_doc_date,'' AS uploaded_image, 0 as stock_collected,'' as stock_collected_by,'' as date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum , round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM b2b_summary.cnnotemain a WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.locgroup IN ($loc) and a.type IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code ) a  order by $order $dir  limit $start,$limit ");

                // $result = $this->db->query("select * from (
                //         SELECT stock_collected,date_collected,stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum, round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "'  and locgroup IN ($loc) and type IN ($check_status)  $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                //     UNION ALL
                //     SELECT 0 as stock_collected,'' as date_collected,'' AS stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum , round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "'  and locgroup IN ($loc) and type IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code ) a  order by $order $dir  limit $start,$limit ");
            } else {
                $result = $this->db->query("select * from ( SELECT b.`batch_no`,b.`doc_date` AS strb_doc_date,b.uploaded_image, a.stock_collected,a.stock_collected_by,a.date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum , round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM  b2b_summary.dbnotemain a LEFT JOIN b2b_summary.dbnote_batch b
                ON a.refno = b.b2b_dn_refno AND a.`customer_guid` = b.`customer_guid` WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "' and a.locgroup IN ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.type IN ($check_status)  $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                    UNION ALL
                    SELECT '' AS batch_no, '' AS strb_doc_date,'' AS uploaded_image, 0 as stock_collected,'' as stock_collected_by,'' as date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum , round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM b2b_summary.cnnotemain a WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "' and a.locgroup IN ($loc)  and a.code IN (" . $_SESSION['query_supcode'] . ") and a.type IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code ) a order by $order $dir  limit $start,$limit ");

                // $result = $this->db->query("select * from ( SELECT stock_collected,date_collected,stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum , round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "' and locgroup IN ($loc) and code IN (" . $_SESSION['query_supcode'] . ") and type IN ($check_status)  $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code
                //     UNION ALL
                //     SELECT 0 as stock_collected,'' as date_collected,'' AS stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum , round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "' and locgroup IN ($loc)  and code IN (" . $_SESSION['query_supcode'] . ") and type IN ($check_status) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code ) a order by $order $dir  limit $start,$limit ");
            };
        }


        if ($check_module == 'panda_pdncn') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT customer_guid, trans_type, loc_group, refno, docno, docdate, CODE, name, amount, gst_tax_sum, amount_include_tax,IF(status = '', 'NEW', status) as status FROM b2b_summary.cndn_amt WHERE trans_type IN ($check_status) AND  customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code order by $order $dir  limit $start,$limit ");
            } else {
                $result = $this->db->query("SELECT customer_guid, trans_type, loc_group, refno, docno, docdate, CODE, name,amount, gst_tax_sum, amount_include_tax,IF(status = '', 'NEW', status) as status FROM b2b_summary.cndn_amt WHERE trans_type IN ($check_status) AND  customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) and code IN (" . $_SESSION['query_supcode'] . ") $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code order by $order $dir  limit $start,$limit ");
            }
        }


        if ($check_module == 'panda_pci') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT customer_guid, inv_refno , promo_refno, loc_group,sup_code,sup_name, docdate, sup_code AS CODE, total_bf_tax , gst_value, total_af_tax,IF(status = '','NEW',status) as status
                FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code order by $order $dir  limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT customer_guid, inv_refno , promo_refno, loc_group,sup_code,sup_name, docdate, sup_code AS CODE, total_bf_tax , gst_value, total_af_tax,IF(status = '','NEW',status) as status
                FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) and sup_code IN (" . $_SESSION['query_supcode'] . ") AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code order by $order $dir  limit $start,$limit");
            }
        }


        if ($check_module == 'panda_di') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT inv_refno,refno, loc_group, sup_code, sup_name, docdate, datedue, total_net,IF(status = '', 'NEW', status) as status FROM b2b_summary.discheme_taxinv WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code order by $order $dir  limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT inv_refno,refno, loc_group, sup_code, sup_name, docdate, datedue, total_net,IF(status = '', 'NEW', status) as status FROM b2b_summary.discheme_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) and sup_code IN (" . $_SESSION['query_supcode'] . ") AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code order by $order $dir  limit $start,$limit");
            }
        }

        if ($check_module == 'panda_return_collection') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3' AND a.b2b_dn_refno IS NOT NULL AND a.b2b_dn_refno != '', 'PRDN generated', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', IF(STATUS = '3' AND (a.b2b_dn_refno IS NULL OR a.b2b_dn_refno = ''), 'Pending PRDN' , 'No Status') ) ) ) ) ) ) AS status_desc, status, accepted_at, accepted_by, cancel_remark, IF( STATUS NOT IN ('8','9') ,uploaded_image, '0') AS uploaded_image, b.b2b_registration, a.srb_accept_days, DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY) AS new_expiry_date FROM b2b_summary.dbnote_batch AS a INNER JOIN b2b_summary.supcus AS b ON a.sup_code = b.`code` AND a.customer_guid = b.customer_guid AND b.b2b_registration = '1' WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND a.location IN ($loc)    $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code   ORDER BY $order  limit $start,$limit");

                //$result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3', 'PRDN generated', IF( STATUS = '4', 'NA', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', 'No Status' ) ) ) ) ) ) ) AS status_desc, status, accepted_at FROM b2b_summary.dbnote_batch AS a WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND location IN ($loc)    $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code   ORDER BY FIELD( a.status, '0', '1', '2', '3' , '9' , '8' , '4') ASC , a.doc_date DESC  limit $start,$limit");
                // echo $this->db->last_query();die;
            } else {
                $result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3' AND a.b2b_dn_refno IS NOT NULL AND a.b2b_dn_refno != '', 'PRDN generated', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', IF(STATUS = '3' AND (a.b2b_dn_refno IS NULL OR a.b2b_dn_refno = ''), 'Pending PRDN' , 'No Status') ) ) ) ) ) ) AS status_desc, status, accepted_at, accepted_by, cancel_remark, IF( STATUS NOT IN ('8','9') ,uploaded_image, '0') AS uploaded_image, b.b2b_registration, a.srb_accept_days, DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY) AS new_expiry_date FROM b2b_summary.dbnote_batch AS a INNER JOIN b2b_summary.supcus AS b ON a.sup_code = b.`code` AND a.customer_guid = b.customer_guid AND b.b2b_registration = '1' WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND a.location IN ($loc) and sup_code IN (" . $_SESSION['query_supcode'] . ")  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code   ORDER BY $order  limit $start,$limit");

                //$result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3', 'PRDN generated', IF( STATUS = '4', 'NA', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', 'No Status' ) ) ) ) ) ) ) AS status_desc, status, accepted_at FROM b2b_summary.dbnote_batch AS a WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND location IN ($loc) and sup_code IN (" . $_SESSION['query_supcode'] . ")  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code   ORDER BY FIELD( a.status, '0', '1', '2', '3' , '9' , '8' , '4') ASC , a.doc_date DESC  limit $start,$limit");
            }
        }

        if ($check_module == 'panda_gr_download') // panda_gr
        {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT 
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno  
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.loc_group in ($loc) and a.status in ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code order by $order $dir limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT 
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno  
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' and a.loc_group in ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.status in ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code order by $order $dir limit $start,$limit");
            }
        }
        return $result->result();
    }

    function posts_search($limit, $start, $check_status, $loc, $check_module, $dir, $order, $search, $q_doc_from_to, $q_exp_from_to, $q_refno, $q_period_code)
    {
        $customer_guid = $this->session->userdata('customer_guid');
        if ($check_module == 'panda_po_2') //  check module = panda_po_2
        {
            if (!in_array('VGR', $_SESSION['module_code'])) {
                $module = 'gr_download_child';
            } else {
                $module = 'gr_child';
            }
            if (in_array('IAVA', $_SESSION['module_code'])) {
                // $result = $this->db->query('SELECT *,c.portal_description as rejected_remarks FROM (SELECT customer_guid, refno, loc_group, date_format(podate, "%Y-%m-%d %a") as podate,date_format(expiry_date, "%Y-%m-%d %a") expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = "", "NEW", status) as status, rejected_remark, scode as scode1, sname as sname1,date_format(deliverdate, "%Y-%m-%d %a") delivery_date from b2b_summary.pomain where customer_guid = "'.$_SESSION['customer_guid'].'" and loc_group IN ('.$loc.') and status IN ('.$check_status.')'. $q_doc_from_to.' '.$q_exp_from_to.' '.$q_refno .' '.$q_period_code.') a LEFT JOIN (SELECT po_refno,GROUP_CONCAT("<a href='.base_url().'index.php/panda_gr/'.$module.'?trans=",gr_refno,"&loc='.$_REQUEST['loc'].'&fmodule=1>",gr_refno,"</a>") as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid =  "'.$_SESSION['customer_guid'].'" AND gr_date >= DATE_ADD(DATE_FORMAT(NOW(),"%Y-%m-%d"),INTERVAL - 6 month) GROUP BY po_refno) b ON a.RefNo = b.po_refno LEFT JOIN status_setting c ON a.rejected_remark = c.code AND c.type = "reject_po" WHERE (a.refno like "%'.$search.'%" or a.loc_group like "%'.$search.'%" or a.podate like "%'.$search.'%" or a.scode like "%'.$search.'%" or  a.sname like "%'.$search.'%" or  b.gr_refno like "%'.$search.'%" ) order by '.$order.' '.$dir.' limit ' .$start.','.$limit );

                $result = $this->db->query("SELECT a.customer_guid, refno, loc_group, DATE_FORMAT(podate, '%Y-%m-%d %a') AS podate, DATE_FORMAT(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, ROUND(total, 2) AS total, ROUND(gst_tax_sum, 2) AS gst_tax_sum, ROUND(total_include_tax, 2) AS total_include_tax, IF(STATUS = '', 'NEW', STATUS) AS STATUS, rejected_remark, scode AS scode1, sname AS sname1, DATE_FORMAT(deliverdate, '%Y-%m-%d %a') delivery_date, GROUP_CONCAT( DISTINCT '<a href=" . base_url() . "index.php/panda_gr/" . $module . "?trans=',b.gr_refno,'&loc=" . $_REQUEST['loc'] . "&fmodule=1>',gr_refno,'</a>') AS gr_refno , c.portal_description AS rejected_remarks,IF(a.status = '', 'NEW', status) as status FROM b2b_summary.pomain a LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND b.`customer_guid` = '$customer_guid' LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' WHERE a.customer_guid = '$customer_guid' AND a.loc_group IN ($loc) AND a.STATUS IN ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code AND (a.refno like " . "'%" . $search . "%'" . "or a.loc_group like " . "'%" . $search . "%'" . " or a.podate like " . "'%" . $search . "%'" . " or a.scode like " . "'%" . $search . "%'" . " or  a.sname like " . "'%" . $search . "%'" . " or  b.gr_refno like " . "'%" . $search . "%'" . " ) GROUP BY a.refno order by " . $order . " " . $dir . " limit " . $start . "," . $limit);
                // print_r($result->result());die;

            } else {
                // $result = $this->db->query('SELECT *,c.portal_description as rejected_remarks FROM (SELECT customer_guid, refno, loc_group, date_format(podate, "%Y-%m-%d %a") as podate,date_format(expiry_date, "%Y-%m-%d %a") expiry_date, scode, sname, round( total,2) as total,round(gst_tax_sum,2) as gst_tax_sum, round( total_include_tax,2) as total_include_tax,  IF(status = "", "NEW", status) as status, rejected_remark, scode as scode1, sname as sname1,date_format(deliverdate, "%Y-%m-%d %a") delivery_date from b2b_summary.pomain where customer_guid = "'.$_SESSION['customer_guid'].'" and scode IN ('.$_SESSION['query_supcode'].')  and loc_group IN ('.$loc.') and status IN ('.$check_status.') '.$q_doc_from_to.' '.$q_exp_from_to.' '.$q_refno.' '.$q_period_code.') a LEFT JOIN (SELECT po_refno,GROUP_CONCAT("<a href='.base_url().'index.php/panda_gr/'.$module.'?trans=",gr_refno,"&loc='.$_REQUEST['loc'].'&fmodule=1>",gr_refno,"</a>") as gr_refno FROM b2b_summary.po_grn_inv WHERE customer_guid = "'.$_SESSION['customer_guid'].'" AND gr_date >= DATE_ADD(DATE_FORMAT(NOW(),"%Y-%m-%d"),INTERVAL - 6 month) GROUP BY po_refno) b ON a.RefNo = po_refno LEFT JOIN status_setting c ON a.rejected_remark = c.code AND c.type = "reject_po" WHERE (a.refno like "%'.$search.'%" or a.loc_group like "%'.$search.'%" or a.podate like "%'.$search.'%" or a.scode like "%'.$search.'%" or  a.sname like "%'.$search.'%" or b.gr_refno like "%'.$search.'%") order by '.$order.' '.$dir.'  limit '.$start.','.$limit);
                $result = $this->db->query("SELECT a.customer_guid, refno, loc_group, DATE_FORMAT(podate, '%Y-%m-%d %a') AS podate, DATE_FORMAT(expiry_date, '%Y-%m-%d %a') expiry_date, scode, sname, ROUND(total, 2) AS total, ROUND(gst_tax_sum, 2) AS gst_tax_sum, ROUND(total_include_tax, 2) AS total_include_tax, IF(STATUS = '', 'NEW', STATUS) AS STATUS, rejected_remark, scode AS scode1, sname AS sname1, DATE_FORMAT(deliverdate, '%Y-%m-%d %a') delivery_date, GROUP_CONCAT( DISTINCT '<a href=" . base_url() . "index.php/panda_gr/" . $module . "?trans=',b.gr_refno,'&loc=" . $_REQUEST['loc'] . "&fmodule=1>',gr_refno,'</a>') AS gr_refno , c.portal_description AS rejected_remarks,IF(a.status = '', 'NEW', status) as status FROM b2b_summary.pomain a LEFT JOIN b2b_summary.`po_grn_inv` b ON a.refno = b.`po_refno` AND b.`customer_guid` = '$customer_guid' LEFT JOIN lite_b2b.status_setting c ON a.rejected_remark = c.code AND c.type = 'reject_po' WHERE a.customer_guid = '$customer_guid' AND a.loc_group IN ($loc) AND a.STATUS IN ($check_status) and a.scode IN (" . $_SESSION['query_supcode'] . ") AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code AND (a.refno like " . "'%" . $search . "%'" . "or a.loc_group like " . "'%" . $search . "%'" . " or a.podate like " . "'%" . $search . "%'" . " or a.scode like " . "'%" . $search . "%'" . " or  a.sname like " . "'%" . $search . "%'" . " or  b.gr_refno like " . "'%" . $search . "%'" . " ) GROUP BY a.refno order by " . $order . " " . $dir . " limit " . $start . "," . $limit);
            };
        }
        if ($check_module == 'panda_gr') // panda_gr
        {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT 
                a.consign,
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno,
                z.inv_date as einvdate 
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "'  and a.loc_group in ($loc) and a.status IN ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%' or z.einvno like '%$search%' or a.cross_ref like '%$search%') GROUP BY a.customer_guid, a.refno order by $order $dir limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT
                a.consign, 
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno,
                z.inv_date as einvdate 
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' and a.loc_group in ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.status IN ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%' or z.einvno like '%$search%' or a.cross_ref like '%$search%') GROUP BY a.customer_guid, a.refno order by $order $dir limit $start,$limit");
            };
        }
        if ($check_module == 'panda_grda') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT b.customer_guid
                , a.grdate 
                , IF(b.status = '', 'NEW', b.status) as status
                , b.refno
                , a.loc_group
                , b.`transtype`
                , ap_sup_code AS `code`
                , a.name
                , b.total_amt AS `varianceamt`
                , b.`sup_cn_no`
                , b.`sup_cn_date`
                , dncn_date
                , dncn_date_acc
                , a.invno
                FROM b2b_summary.grmain AS a
                INNER JOIN  (select *, SUM(varianceamt) AS total_amt from b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno  and a.customer_guid = b.customer_guid
                WHERE b.`customer_guid` ='" . $_SESSION['customer_guid'] . "' and b.transtype IN ($check_status) AND a.loc_group in ($loc) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code and (b.refno like '%$search%' or a.loc_group like '%$search%' or sup_cn_date like '%$search%' or code like '%$search%' or name like '%$search%'  or b.`sup_cn_no` like '%$search%' or a.invno like '%$search%') order by $order $dir  limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT b.customer_guid
                , a.grdate 
                , IF(b.status = '', 'NEW', b.status) as status
                , b.refno
                , a.loc_group
                , b.`transtype`
                , ap_sup_code AS `code`
                , a.name
                , b.total_amt AS `varianceamt`
                , b.`sup_cn_no`
                , b.`sup_cn_date`
                , dncn_date
                , dncn_date_acc
                , a.invno
                FROM b2b_summary.grmain AS a
                INNER JOIN  (select *, SUM(varianceamt) AS total_amt from b2b_summary.grmain_dncn WHERE customer_guid = '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid
                WHERE b.`customer_guid` = '" . $_SESSION['customer_guid'] . "' and b.transtype IN ($check_status) AND a.loc_group in ($loc) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code AND ap_sup_code IN (" . $_SESSION['query_supcode'] . ") and (b.refno like '%$search%' or a.loc_group like '%$search%' or sup_cn_date like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%') order by $order $dir  limit $start,$limit");
            };
        }


        if ($check_module == 'panda_prdncn') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("select * from (
                    SELECT b.`batch_no`,b.`doc_date` AS strb_doc_date,b.uploaded_image, a.stock_collected,a.stock_collected_by,a.date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum, round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM b2b_summary.dbnotemain a LEFT JOIN b2b_summary.dbnote_batch b ON a.refno = b.`b2b_dn_refno` AND a.`customer_guid` = b.`customer_guid` WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.locgroup IN ($loc) and a.type IN ($check_status)  
                UNION ALL
                SELECT '' AS batch_no, '' AS strb_doc_date,'' as uploaded_image,0 as stock_collected,'' as stock_collected_by,'' as date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum , round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM b2b_summary.cnnotemain a WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.locgroup IN ($loc) and a.type IN ($check_status)  ) a where  (a.refno like '%$search%' or a.locgroup like '%$search%' or a.docdate like '%$search%' or  a.code like '%$search%' or  a.name like '%$search%' or  a.status like '%$search%' or a.batch_no like '%$search%' or a.stock_collected_by like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code order by $order $dir  limit $start,$limit ");

                // $result = $this->db->query("select * from (
                //         SELECT stock_collected,date_collected,stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum, round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "'  and locgroup IN ($loc) and type IN ($check_status)  
                //     UNION ALL
                //     SELECT 0 as stock_collected,'' as date_collected,'' AS stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum , round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "'  and locgroup IN ($loc) and type IN ($check_status)  ) a where  (refno like '%$search%' or locgroup like '%$search%' or docdate like '%$search%' or  code like '%$search%' or  name like '%$search%' or  status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code order by $order $dir  limit $start,$limit ");
            } else {
                $result = $this->db->query("select * from ( SELECT b.`batch_no`,b.`doc_date` AS strb_doc_date,b.uploaded_image, a.stock_collected,a.stock_collected_by,a.date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum, round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM  b2b_summary.dbnotemain a LEFT JOIN b2b_summary.dbnote_batch b ON a.refno = b.`b2b_dn_refno` AND a.`customer_guid` = b.`customer_guid` WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "' and a.locgroup IN ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.type IN ($check_status) 
                UNION ALL
                SELECT '' AS batch_no, '' AS strb_doc_date,'' AS uploaded_image,0 as stock_collected,'' as stock_collected_by,'' as date_collected,a.customer_guid, a.type, a.refno, a.locgroup, a.docno, a.docdate, a.code, a.name, a.amount, a.gst_tax_sum , round(a.amount+a.gst_tax_sum ,2 ) as total_incl_tax,IF(a.status = '', 'NEW', a.status) AS status FROM  b2b_summary.cnnotemain a WHERE a.sctype IN ('S','C') and a.customer_guid = '" . $_SESSION['customer_guid'] . "' and a.locgroup IN ($loc)  and a.code IN (" . $_SESSION['query_supcode'] . ") and a.type IN ($check_status)  ) a where (a.refno like '%$search%' or a.locgroup like '%$search%' or a.docdate like '%$search%' or  a.code like '%$search%' or  a.name like '%$search%' or  a.status like '%$search%' or a.batch_no like '%$search%' or a.stock_collected_by like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code order by $order $dir  limit $start,$limit ");

                // $result = $this->db->query("select * from ( SELECT stock_collected,date_collected,stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum , round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.dbnotemain  WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "' and locgroup IN ($loc) and code IN (" . $_SESSION['query_supcode'] . ") and type IN ($check_status) 
                //     UNION ALL
                //     SELECT 0 as stock_collected,'' as date_collected,'' AS stock_collected_by,customer_guid, type, refno, locgroup, docno, docdate, code, name, amount, gst_tax_sum , round(amount+gst_tax_sum ,2 ) as total_incl_tax,IF(status = '', 'NEW', status) AS status FROM  b2b_summary.cnnotemain WHERE sctype = 'S' and customer_guid = '" . $_SESSION['customer_guid'] . "' and locgroup IN ($loc)  and code IN (" . $_SESSION['query_supcode'] . ") and type IN ($check_status)  ) a where (refno like '%$search%' or locgroup like '%$search%' or docdate like '%$search%' or  code like '%$search%' or  name like '%$search%' or  status like '%$search%') $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code order by $order $dir  limit $start,$limit ");
            };
        }



        if ($check_module == 'panda_pdncn') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT customer_guid, trans_type, loc_group, refno, docno, docdate, CODE, name, amount, gst_tax_sum, amount_include_tax,IF(status = '', 'NEW', status) as status FROM b2b_summary.cndn_amt WHERE trans_type IN ($check_status) AND  customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code and (refno like '%$search%' or loc_group like '%$search%' or docdate like '%$search%' or docno like '%$search%' or trans_type like '%$search%' or name like '%$search%' or code like '%$search%') order by $order $dir  limit $start,$limit ");
            } else {
                $result = $this->db->query("SELECT customer_guid, trans_type, loc_group, refno, docno, docdate, CODE, name, amount, gst_tax_sum, amount_include_tax,IF(status = '', 'NEW', status) as status FROM b2b_summary.cndn_amt WHERE trans_type IN ($check_status) AND  customer_guid = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) $q_doc_from_to $q_exp_from_to $q_refno  $q_period_code and code IN (" . $_SESSION['query_supcode'] . ")  and (refno like '%$search%' or loc_group like '%$search%' or docdate like '%$search%' or docno like '%$search%' or trans_type like '%$search%' ) order by $order $dir  limit $start,$limit ");
            }
        }


        if ($check_module == 'panda_pci') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT customer_guid, inv_refno , promo_refno, loc_group,sup_code,sup_name, docdate, sup_code AS CODE, total_bf_tax , gst_value, total_af_tax,IF(status = '','NEW',status) as status
                FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and (inv_refno like '%$search%' or sup_code like '%$search%' or sup_name like '%$search%' or loc_group like '%$search%' or docdate like '%$search%' or promo_refno like '%$search%') order by $order $dir  limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT customer_guid, inv_refno , promo_refno, loc_group,sup_code,sup_name, docdate, sup_code AS CODE, total_bf_tax , gst_value, total_af_tax,IF(status = '','NEW',status) as status
                FROM b2b_summary.promo_taxinv  WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and sup_code IN (" . $_SESSION['query_supcode'] . ") and (inv_refno like '%$search%' or loc_group like '%$search%'  or sup_code like '%$search%' or sup_name like '%$search%' or  docdate like '%$search%' or promo_refno like '%$search%')  order by $order $dir  limit $start,$limit");
            }
        }

        if ($check_module == 'panda_di') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT inv_refno, refno, loc_group, sup_code, sup_name, docdate, datedue, total_net,IF(status = '', 'NEW', status) as status FROM b2b_summary.discheme_taxinv WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and (inv_refno like '%$search%' or refno like '%$search%' or loc_group like '%$search%' or sup_code like '%$search%' or sup_name like '%$search%') order by $order $dir  limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT inv_refno, refno, loc_group, sup_code, sup_name, docdate, datedue, total_net,IF(status = '', 'NEW', status) as status FROM b2b_summary.discheme_taxinv WHERE customer_guid  = '" . $_SESSION['customer_guid'] . "' AND loc_group IN ($loc) AND `status` IN ($check_status) $q_doc_from_to $q_refno $q_period_code and sup_code IN (" . $_SESSION['query_supcode'] . ") and (inv_refno like '%$search%' or refno like '%$search%' or loc_group like '%$search%' or sup_code like '%$search%' or sup_name like '%$search%')  order by $order $dir  limit $start,$limit");
            }
        }


        if ($check_module == 'panda_return_collection') {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3' AND a.b2b_dn_refno IS NOT NULL AND a.b2b_dn_refno != '', 'PRDN generated', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', IF(STATUS = '3' AND (a.b2b_dn_refno IS NULL OR a.b2b_dn_refno = ''), 'Pending PRDN' , 'No Status') ) ) ) ) ) ) AS status_desc, status, accepted_at, accepted_by, IF( STATUS NOT IN ('8','9') ,uploaded_image, '0') AS uploaded_image,cancel_remark, b.b2b_registration, a.srb_accept_days, DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY) AS new_expiry_date FROM b2b_summary.dbnote_batch AS a INNER JOIN b2b_summary.supcus AS b ON a.sup_code = b.`code` AND a.customer_guid = b.customer_guid AND b.b2b_registration = '1' WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND location IN ($loc)  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code and (batch_no like '%$search%' or location like '%$search%' or expiry_date like '%$search%' or a.sup_name like '%$search%' or doc_date like '%$search%' or a.sup_code like '%$search%' or a.b2b_dn_refno like '%$search%') ORDER BY $order limit $start,$limit");

                //$result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3', 'PRDN generated', IF( STATUS = '4', 'NA', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', 'No Status' ) ) ) ) ) ) ) AS status_desc, status, accepted_at FROM b2b_summary.dbnote_batch AS a WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND location IN ($loc)  $q_doc_from_to $q_exp_from_to $q_refno  and status IN ($check_status) $q_period_code and (batch_no like '%$search%' or location like '%$search%' or expiry_date like '%$search%' or a.sup_name like '%$search%' or doc_date like '%$search%' or a.sup_code like '%$search%') ORDER BY FIELD( a.status, '0', '1', '2', '3' , '9' , '8' , '4') ASC , a.doc_date DESC  limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3' AND a.b2b_dn_refno IS NOT NULL AND a.b2b_dn_refno != '', 'PRDN generated', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', IF(STATUS = '3' AND (a.b2b_dn_refno IS NULL OR a.b2b_dn_refno = ''), 'Pending PRDN' , 'No Status') ) ) ) ) ) ) AS status_desc, status, accepted_at, accepted_by, IF( STATUS NOT IN ('8','9') ,uploaded_image, '0') AS uploaded_image,cancel_remark, b.b2b_registration, a.srb_accept_days, DATE_ADD(a.doc_date, INTERVAL a.srb_accept_days DAY) AS new_expiry_date FROM b2b_summary.dbnote_batch AS a INNER JOIN b2b_summary.supcus AS b ON a.sup_code = b.`code` AND a.customer_guid = b.customer_guid AND b.b2b_registration = '1' WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND location IN ($loc) and status IN ($check_status) $q_period_code and sup_code IN (" . $_SESSION['query_supcode'] . ") and (batch_no like '%$search%' or location like '%$search%' or expiry_date like '%$search%' or a.sup_name like '%$search%' or doc_date like '%$search%' or a.sup_code like '%$search%' or a.b2b_dn_refno like '%$search%')  ORDER BY $order  limit $start,$limit");

                //$result = $this->db->query("SELECT a.customer_guid, IFNULL(a.b2b_dn_refno, '') AS prdn_refno, batch_no, location, doc_date, expiry_date, sup_code, sup_name, canceled, IF( STATUS = '0', 'Pending Accept', IF( STATUS = '1', 'Accepted', IF( STATUS = '2', 'Pending PRDN', IF( STATUS = '3', 'PRDN generated', IF( STATUS = '4', 'NA', IF( STATUS = '8', 'Amended', IF(STATUS = '9', 'Cancel', 'No Status' ) ) ) ) ) ) ) AS status_desc, status, accepted_at FROM b2b_summary.dbnote_batch AS a WHERE a.customer_guid  = '" . $_SESSION['customer_guid'] . "' AND location IN ($loc) and status IN ($check_status) $q_period_code and sup_code IN (" . $_SESSION['query_supcode'] . ") and (batch_no like '%$search%' or location like '%$search%' or expiry_date like '%$search%' or a.sup_name like '%$search%' or doc_date like '%$search%' or a.sup_code like '%$search%')  ORDER BY FIELD( a.status, '0', '1', '2', '3' , '9' , '8' , '4') ASC , a.doc_date DESC  limit $start,$limit");
            }
        }

        if ($check_module == 'panda_gr_download') // panda_gr
        {
            if (in_array('IAVA', $_SESSION['module_code'])) {
                $result = $this->db->query("SELECT 
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno  
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid = '" . $_SESSION['customer_guid'] . "'  and a.loc_group in ($loc) and a.status in ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%' or z.einvno like '%$search%' or a.cross_ref like '%$search%') order by $order $dir limit $start,$limit");
            } else {
                $result = $this->db->query("SELECT 
                a.customer_guid,
                a.refno,
                IFNULL(b.refno,'') AS grda_status,
                a.loc_group,
                a.dono,
                a.invno,
                DATE_FORMAT(a.docdate, '%Y-%m-%d %a') AS docdate,
                DATE_FORMAT(a.grdate, '%Y-%m-%d %a') grdate,
                a.code,
                a.name,
                a.total,
                a.gst_tax_sum,
                a.tax_code_purchase,
                a.total_include_tax,
                a.doc_name_reg,
                a.cross_ref,
                IF(a.status = '', 'NEW', a.status) AS status,
                z.einvno  
                FROM
                b2b_summary.grmain  AS a
                LEFT JOIN  (select * from b2b_summary.grmain_dncn WHERE customer_guid =  '" . $_SESSION['customer_guid'] . "' group by refno) AS b
                ON a.refno = b.refno and a.customer_guid = b.customer_guid LEFT JOIN b2b_summary.einv_main z ON a.refno = z.refno AND a.customer_guid = z.customer_guid 
                WHERE a.customer_guid =  '" . $_SESSION['customer_guid'] . "' and a.loc_group in ($loc) and a.code IN (" . $_SESSION['query_supcode'] . ") and a.status in ($check_status) AND a.in_kind = 0 $q_doc_from_to $q_exp_from_to $q_refno $q_period_code and (a.refno like '%$search%' or a.loc_group like '%$search%' or a.grdate like '%$search%' or code like '%$search%' or name like '%$search%' or a.invno like '%$search%' or a.dono like '%$search%' or z.einvno like '%$search%' or a.cross_ref like '%$search%') order by $order $dir limit $start,$limit");
            }
            //echo $this->db->last_query();
        }



        return $result->result();
    }

    function edi_log_list($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_name, $customer_name, $limit, $start, $col, $dir, $search, $user_guid)
    {
        if ($col == '' && $dir == '' && $start == '' && $limit == '') {
            $order_by = '';
        } else {
            $order_by = "ORDER BY " . $col . "  " . $dir . " LIMIT " . $start . " , " . $limit . "";
        }

        if ($search == '') {
            $search_in = '';
        } else {
            $search_in = "AND (a.status LIKE '%" . $search . "%' OR
          a.edi_batch_no LIKE '%" . $search . "%' OR
          a.file_name LIKE '%" . $search . "%' OR
          a.refno LIKE '%" . $search . "%' OR
          b.acc_name LIKE '%" . $search . "%' OR
          c.supplier_name LIKE '%" . $search . "%' OR
          a.created_at LIKE '%" . $search . "%' OR
          a.error_message_reason LIKE '%" . $search . "%')";
        }

        if ($edi_batch_no == '') {
            $edi_batch_no_in = '';
        } else {
            $edi_batch_no_in = "AND a.edi_batch_no = '$edi_batch_no'";
        }

        if ($status == '') {
            $status_in = '';
        } elseif ($status == 'ALL') {
            $get_stat = $this->db->query("SELECT code from lite_b2b.set_setting where module_name = 'EDI_PO_FILTER' AND `code` != 'ALL' ");

            foreach ($get_stat->result() as  $row) {
                $check_stat[] = $row->code;
            }

            foreach ($check_stat as &$value) {
                $value = "'" . trim($value) . "'";
            }
            $value_data = implode(',', array_filter($check_stat));
            $status_in = "AND a.status IN ($value_data)";
        } else {
            $status_in = "AND a.status = '$status'";
        }
        if ($generate_date_from == '' || $generate_date_to = '') {
            $generate_date_in = '';
        } else {
            $generate_date_in = "AND a.created_at BETWEEN '$generate_date_from' AND '$generate_date_to'";
        }
        if ($period_code == '') {
            $period_code_in = '';
        } else {
            $period_code_in = "AND DATE_FORMAT(a.`created_at`, '%Y-%m') = '$period_code'";
        }
        if ($supplier_name == '') {
            $supplier_name_in = '';
        } else {
            $supplier_name_in = "AND a.supplier_guid = '$supplier_name'";
        }
        if ($customer_name == '') {
            $customer_name_in = '';
        } else {
            $customer_name_in = "AND a.customer_guid = '$customer_name'";
        }

        if (in_array('IAVA', $_SESSION['module_code'])) 
        {
            $query = $this->db->query("SELECT a.guid,a.status,a.`edi_batch_no`,a.`file_name`,a.`refno`,b.`acc_name`,b.acc_guid,c.`supplier_name`,a.`supplier_guid`,a.`supplier_guid`,a.`created_at`,a.`updated_at`,a.`error_message_reason`
            FROM lite_b2b.`edi_log` AS a
            LEFT JOIN lite_b2b.`acc` AS b
            ON a.`customer_guid` = b.`acc_guid`
            LEFT JOIN lite_b2b.`set_supplier` AS c
            ON a.`supplier_guid` = c.`supplier_guid`
            WHERE a.refno != ''
            AND a.type = 'PO'
            $search_in
            $edi_batch_no_in
            $status_in
            $generate_date_in
            $period_code_in
            $supplier_name_in
            $customer_name_in
            GROUP BY a.`edi_batch_no`,a.supplier_guid,a.customer_guid
            $order_by");
        }
        else
        {
            $query = $this->db->query("SELECT a.guid,a.status,a.`edi_batch_no`,a.`file_name`,a.`refno`,b.`acc_name`,b.acc_guid,c.`supplier_name`,a.`supplier_guid`,a.`supplier_guid`,a.`created_at`,a.`updated_at`,a.`error_message_reason`
            FROM lite_b2b.`edi_log` AS a
            LEFT JOIN lite_b2b.`acc` AS b
            ON a.`customer_guid` = b.`acc_guid`
            LEFT JOIN lite_b2b.`set_supplier` AS c
            ON a.`supplier_guid` = c.`supplier_guid`
            INNER JOIN lite_b2b.set_supplier_user_relationship AS d
            ON a.supplier_guid = d.supplier_guid
            AND a.customer_guid = d.customer_guid
            AND d.user_guid = '$user_guid'
            WHERE a.refno != ''
            AND a.type = 'PO'
            $search_in
            $edi_batch_no_in
            $status_in
            $generate_date_in
            $period_code_in
            $supplier_name_in
            $customer_name_in
            GROUP BY a.`edi_batch_no`,a.supplier_guid,a.customer_guid
            $order_by");
        }

        return $query;
    }

    function old_edi_refno_list($supplier_guid, $customer_guid, $edi_batch_no, $limit, $start, $col, $dir, $search, $implode_refno)
    {
        if ($col == '' && $dir == '' && $start == '' && $limit == '') {
            $order_by = '';
        } else {
            $order_by = "ORDER BY " . $col . "  " . $dir . " LIMIT " . $start . " , " . $limit . "";
        }

        if ($search == '') {
            $search_in = '';
        } else {
            $search_in = "AND JSON_UNQUOTE(JSON_EXTRACT(a.`refno`, CONCAT('$[', Numbers.N - 1,']'))) LIKE '%" . $search . "%' OR
           b.acc_name LIKE '%" . $search . "%'";
        }

        $query = $this->db->query("SELECT a.`edi_batch_no`,CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`refno`, CONCAT('$[', Numbers.N - 1,']'))) AS CHAR(20)) AS refno,b.`acc_name`,c.`supplier_name`,
        '' As total_line
       -- CAST(JSON_LENGTH(d.`po_json_info`,'$.pochild') AS CHAR(2)) AS total_line,
       FROM (
       SELECT @row := @row + 1 AS N FROM 
       -- (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) T3,
       -- (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) T2,
       (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) T1, 
       (SELECT @row:=0) T0
       ) Numbers
       INNER JOIN lite_b2b.`edi_log` AS a
       LEFT JOIN lite_b2b.`acc` AS b
       ON a.`customer_guid` = b.`acc_guid`
       LEFT JOIN lite_b2b.`set_supplier` AS c
       ON a.`supplier_guid` = c.`supplier_guid`
       LEFT JOIN b2b_summary.`pomain_info` AS d
       ON CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`refno`, CONCAT('$[', Numbers.N - 1,']'))) AS CHAR(20)) = d.`RefNo`
       WHERE b.acc_guid = '$customer_guid'
       AND a.edi_batch_no = '$edi_batch_no'
       AND a.supplier_guid = '$supplier_guid'
       AND a.type = 'PO'
       AND CAST(JSON_UNQUOTE(JSON_EXTRACT(a.`refno`, CONCAT('$[', Numbers.N - 1,']'))) AS CHAR(20)) IS NOT NULL
       $search_in
       $order_by");

        return $query;
    }

    function edi_refno_list($supplier_guid, $customer_guid, $edi_batch_no, $limit, $start, $col, $dir, $search, $implode_refno)
    {
        if ($col == '' && $dir == '' && $start == '' && $limit == '') {
            $order_by = '';
        } else {
            $order_by = "ORDER BY " . $col . "  " . $dir . " LIMIT " . $start . " , " . $limit . "";
        }

        if ($search == '') {
            $search_in = '';
        } else {
            $search_in = "AND a.refno LIKE '%" . $search . "%' OR
           b.acc_name LIKE '%" . $search . "%'";
        }

        $query = $this->db->query("SELECT a.edi_batch_no,b.acc_name, a.refno, CAST(JSON_LENGTH(`po_json_info`,'$.pochild') AS CHAR(2)) AS total_line , a.supplier_code, d.supplier_guid, d.supplier_name
        FROM b2b_summary.pomain_info a
        INNER JOIN lite_b2b.acc b
        ON a.customer_guid = b.acc_guid
        INNER JOIN lite_b2b.set_supplier_group c
        ON a.supplier_code = c.supplier_group_name
        AND a.customer_guid = c.customer_guid
        INNER JOIN lite_b2b.set_supplier d
        ON c.supplier_guid = d.supplier_guid
        WHERE b.acc_guid = '$customer_guid'
        AND a.edi_batch_no = '$edi_batch_no'
        AND d.supplier_guid = '$supplier_guid'
        $search_in
        $order_by");

        return $query;
    }

    function edi_detail_list($customer_guid,  $refno, $edi_status, $po_status, $daterange_from, $daterange_to, $expiry_from, $expiry_to, $period_code, $supplier_guid, $limit, $start, $col, $dir, $search)
    {
        if ($col == '' && $dir == '' && $start == '' && $limit == '') {
            $order_by = '';
        } else {
            $order_by = "ORDER BY " . $col . "  " . $dir . " LIMIT " . $start . " , " . $limit . "";
        }

        if ($search == '') {
            $search_in = '';
        } else {
            $search_in = "AND (a.`RefNo` LIKE '%" . $search . "%')";
        }

        if ($refno == '') {
            $refno_in = '';
        } else {
            $refno_in = "AND a.`RefNo` IN ($refno)";
        }
        if ($edi_status == '') {
            $edi_status_in = '';
        } else {
            $edi_status_in = "AND b.status ='$edi_status'";
        }
        if ($po_status == '') {
            $po_status_in = '';
        } elseif ($po_status == 'ALL') {
            $get_stat = $this->db->query("SELECT code from lite_b2b.set_setting where module_name = 'EDI_PO_FILTER' AND `code` != 'ALL' ");

            foreach ($get_stat->result() as  $row) {
                $check_stat[] = $row->code;
            }

            foreach ($check_stat as &$value) {
                $value = "'" . trim($value) . "'";
            }
            $value_data = implode(',', array_filter($check_stat));
            $status_in = "AND a.status IN ($value_data)";
        } else {
            $po_status_in = "AND a.status ='$po_status'";
        }
        if ($daterange_from == '' || $daterange_to = '') {
            $daterange_in = '';
        } else {
            $daterange_in = "AND a.PODate BETWEEN '$daterange_from' AND '$daterange_to'";
        }
        if ($expiry_from == '' || $expiry_to = '') {
            $expiry_in = '';
        } else {
            $expiry_in = "AND a.PODate BETWEEN '$expiry_from' AND '$expiry_to'";
        }
        if ($period_code == '') {
            $period_code_in = '';
        } else {
            $period_code_in = "AND a.PODate = '$period_code'";
        }

        if ($supplier_guid == '') {
            $supplier_guid_in = '';
        } else {
            $supplier_guid_in = "AND b.supplier_guid = '$supplier_guid'";
        }

        $query = $this->db->query("SELECT a.`customer_guid`,a.`status`,a.`RefNo`,a.`SCode`,a.`loc_group`,a.`SName`,a.`PODate`,a.`DeliverDate`,a.`expiry_date`,FORMAT(a.`total_include_tax`,2) AS amount,
      IF(a.tax_code_purchase = '' , '0' , '1' ) AS include_tax,a.`rejected_remark`,b.`export_status`,b.`edi_batch_no`
      FROM b2b_summary.`pomain` AS a
      LEFT JOIN b2b_summary.pomain_info AS b
      ON a.Refno = b.Refno
      WHERE a.`customer_guid` = '$customer_guid'
      $refno_in
      $search_in
      $edi_status_in
      $po_status_in
      $daterange_in
      $expiry_in
      $period_code_in
      $supplier_guid_in
      $order_by");

        return $query;
    }

    public function status($module_name)
    {

        $query = $this->db->query("SELECT module_name,IF(module_name = 'GRDA_FILTER_DOCTYPE' AND reason = 'ALL', 'ALL', code) AS code, reason 
      FROM lite_b2b.`set_setting` 
      WHERE module_name = '$module_name' 
      ORDER BY reason ASC ");

        return $query;
    }

    function check_router()
    {
        // need add if new user group dont want to check querysupcode
        if (
            $_SESSION['user_group_name'] != 'SUPER_ADMIN' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_TESTING_USE' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_NO_NOTIFICATION_SETUP_NOHIDE'
            && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_FINANCE' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_UPLOADED' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_NO_HIDE' && $_SESSION['user_group_name'] != 'SUPER_ADMIN_NO_HIDE' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_WITH_NO_ACTION' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_NO_NOTIFICATION_SETUP' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_CLERK' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_WITH_NO_ACTION_NN' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN' && $_SESSION['user_group_name'] != 'CUSTOMER_CLERK' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_CLERK_NO_HIDE' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_NO_NOTIFICATION' && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_FINANCE_IMPORT' && $_SESSION['user_group_name'] != 'PANDA_TESTING_USE'  && $_SESSION['user_group_name'] != 'CUSTOMER_ADMIN_NN_ALL'
        ) {
            
            if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login() && $this->session->userdata('query_supcode') == '') {
                $this->session->set_flashdata('message', 'Invalid Process');
                redirect('login_c/customer');
            }
        }
        // else
        // {
        //     //print_r($_SESSION['user_group_name']); die;
        //     if($_SESSION['user_guid'] == '7BA14C79BDDB11EBB0C4000D3AA2838A' || $_SESSION['user_guid'] == '8941E3B5BDDB11EBB0C4000D3AA2838A')
        //     {
        //         if ($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '' && $this->session->userdata('user_logs') == $this->panda->validate_login() && $this->session->userdata('query_supcode') == '') {
        //             $this->session->set_flashdata('message', 'Invalid Redirect');
        //             redirect('#');
        //         }
        //     }
        //     //print_r('testing');
        //     //print_r($this->session->userdata('query_supcode')); die;
        // }

    }

    //created by jr
    function edi_grn_tb($edi_batch_no, $status, $generate_date_from, $generate_date_to, $period_code, $supplier_guid, $customer_name, $type, $limit, $start, $col, $dir, $search)
    {
        if ($col == '' && $dir == '' && $start == '' && $limit == '') {
            $order_by = '';
        } else {
            $order_by = "ORDER BY " . $col . "  " . $dir . " LIMIT " . $start . " , " . $limit . "";
        }

        if ($search == '') {
            $search_in = '';
        } else {
            $search_in = "AND (a.status LIKE '%" . $search . "%' OR
            a.file_name LIKE '%" . $search . "%' OR
            a.refno LIKE '%" . $search . "%' OR
            b.acc_name LIKE '%" . $search . "%' OR
            c.supplier_name LIKE '%" . $search . "%' OR
            a.created_at LIKE '%" . $search . "%' OR
            a.error_message_reason LIKE '%" . $search . "%')";
        }

        if ($edi_batch_no == '') {
            $edi_batch_no_in = '';
        } else {
            $edi_batch_no_in = "AND a.refno = '$edi_batch_no'";
        }

        if ($status == '') {
            $status_in = '';
        } elseif ($status == 'ALL') {
            $get_stat = $this->db->query("SELECT code from lite_b2b.set_setting where module_name = 'EDI_GRN_FILTER' AND `code` != 'ALL' ");

            foreach ($get_stat->result() as  $row) {
                $check_stat[] = $row->code;
            }

            foreach ($check_stat as &$value) {
                $value = "'" . trim($value) . "'";
            }
            $value_data = implode(',', array_filter($check_stat));
            $status_in = "AND a.status IN ($value_data)";
        } else {
            $status_in = "AND a.status = '$status'";
        }
        if ($generate_date_from == '' || $generate_date_to = '') {
            $generate_date_in = '';
        } else {
            $generate_date_in = "AND a.created_at BETWEEN '$generate_date_from' AND '$generate_date_to'";
        }
        if ($period_code == '') {
            $period_code_in = '';
        } else {
            $period_code_in = "AND DATE_FORMAT(a.`created_at`, '%Y-%m') = '$period_code'";
        }
        if ($supplier_guid == '') {
            $supplier_guid_in = '';
        } else {
            $supplier_guid_in = "AND a.supplier_guid = '$supplier_guid'";
        }
        if ($customer_name == '') {
            $customer_name_in = '';
        } else {
            $customer_name_in = "AND a.customer_guid = '$customer_name'";
        }
        $query = $this->db->query("SELECT a.`guid`,a.`edi_batch_no`,a.status,a.`type`,a.`file_name`,a.`refno`,b.`acc_name`,b.acc_guid,c.`supplier_name`,a.`created_at`,a.`updated_at`,a.`error_message_reason`,a.`refno`,a.`remark`,a.`updated_at`,a.`updated_by`
        FROM lite_b2b.`edi_log` AS a
        LEFT JOIN lite_b2b.`acc` AS b
        ON a.`customer_guid` = b.`acc_guid`
        LEFT JOIN lite_b2b.`set_supplier` AS c
        ON a.`supplier_guid` = c.`supplier_guid`
        WHERE a.type = '$type'
        $search_in
        $edi_batch_no_in
        $status_in
        $generate_date_in
        $period_code_in
        $supplier_guid_in
        $customer_name_in
        GROUP BY a.`edi_batch_no`,a.supplier_guid,a.customer_guid
        $order_by");

        return $query;
    }

    function edi_grn_remark_tb($customer_guid, $guid, $limit, $start, $col, $dir, $search)
    {
        if ($col == '' && $dir == '' && $start == '' && $limit == '') {
            $order_by = '';
        } else {
            $order_by = "ORDER BY " . $col . "  " . $dir . " LIMIT " . $start . " , " . $limit . "";
        }

        if ($search == '') {
            $search_in = '';
        } else {
            $search_in = "AND JSON_UNQUOTE(JSON_EXTRACT(a.`refno`, CONCAT('$[', Numbers.N - 1,']'))) LIKE '%" . $search . "%' OR
            b.acc_name LIKE '%" . $search . "%'";
        }

        $query = $this->db->query("SELECT guid,remark FROM lite_b2b.`edi_log` WHERE guid = '$guid'");

        return $query;
    }
}
