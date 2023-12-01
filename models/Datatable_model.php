<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class datatable_model extends CI_Model
{
    function datatable_main_old($query,$type = '')
    {
        $limit = "";
        $query = $this->make_condition($query);

        if (isset($_POST["order"])) {
            $order = $_POST['order'];
            $columns = $_POST['columns'];
            $order_num = count($_POST['order']);
            for ($i = 0; $i < $order_num ; $i++){
                $columnindex = $order[$i]['column'];
                $columnname = $columns[$columnindex]['name'];

                $columnsortorder = mb_strtoupper($order[$i]['dir']);
                if(!empty($columnname)){
                    $order_array[$columnname] = $columnname . ' ' . $columnsortorder;
                }
            }

            if(!empty($order_array)){
                $query .= " ORDER BY " . implode(', ', $order_array);
            }
        }
        
        if ($_POST["length"] != -1) {
            $limit = " LIMIT " . ($_POST['start']) . ", " . ($_POST['length']);
            $query .= $limit;
        }

        $query = $this->db->query($query);
        return $query;
    }

    function datatable_main($query,$type = '',$doc)
    {
        $limit = "";
        $query_condition = $this->search_condition($query,$doc);
        //$query = $this->make_condition($query);
		if (isset($_POST["order"])) {
			$order = $_POST['order'];
			$columns = $_POST['columns'];
			$order_num = count($_POST['order']);
			for ($i = 0; $i < $order_num ; $i++){
				$columnindex = $order[$i]['column'];
                $columnname = $columns[$columnindex]['data'];
                
                $columnsortorder = mb_strtoupper($order[$i]['dir']);
				if(!empty($columnname)){
					$order_array[$columnname] = $columnname . ' ' . $columnsortorder;
				}
               
			}

			if(!empty($order_array)){
				$query_condition .= " ORDER BY " . implode(', ', $order_array);
			}
		}
		
		if ($_POST["length"] != -1) {
            $limit = " LIMIT " . ($_POST['start']) . ", " . ($_POST['length']);
            $query_condition .= $limit;
        }

        $query_condition = $this->db->query($query_condition);
        //echo $this->db->last_query(); die;
        return $query_condition;
    }

    function make_condition($sql)
    {
        $where = "";
        $search_value = TRIM($_POST["search"]["value"]);
        $columns = $_POST['columns'];
        $filter_arr = array();
        foreach ($columns as $row){
            $temp_arr = array();
            if($row['name'] != ''){
                $temp_arr["name"] = $row['name'];
                $filter_arr[] = $temp_arr;
            }
        }

        if ($search_value != '') {
            $where .= " WHERE ";
            foreach ($filter_arr as $val) {
                $where .= "" . $val['name'] . " LIKE '%" . ($search_value) . "%' OR ";
            }
            $where = substr_replace($where, "", -4);

            $where .= "";

            $sql .= $where;
        }

        return $sql;
    }

    function search_condition($sql,$doc)
    {
        $search = TRIM($_POST["search"]["value"]);
        $conditon = '';

        if($doc == 'po_table')
        {
            $conditon = " WHERE (refno like " . "'%" . $search . "%'" . "or loc_group like " . "'%" . $search . "%'" . " or podate like " . "'%" . $search . "%'" . " or supplier_code like " . "'%" . $search . "%'" . " or  supplier_name like " . "'%" . $search . "%'" . " or  grn_refno like " . "'%" . $search . "%'" . " )";
        }
        else if($doc == 'gr_table')
        {
            $conditon = " WHERE (refno like '%$search%' or loc_group like '%$search%' or grdate like '%$search%' or supplier_code like '%$search%' or supplier_name like '%$search%' or invno like '%$search%' or dono like '%$search%' or cross_ref like '%$search%')";
        }
        else if($doc == 'grda_table')
        {
            $conditon = " WHERE (refno like '%$search%' or loc_group like '%$search%' or supplier_code like '%$search%' or supplier_name like '%$search%' or sup_cn_no like '%$search%' )";
        }
        else if($doc == 'pci_table')
        {
            $conditon = " WHERE (inv_refno like '%$search%' or promo_refno like '%$search%' or loc_group like '%$search%' or supplier_name like '%$search%' or supplier_code like '%$search%' or docdate like '%$search%' or status like '%$search%' )";
        }
        else if($doc == 'di_table')
        {
            $conditon = " WHERE (inv_refno like '%$search%' or refno like '%$search%' or loc_group like '%$search%' or supplier_name like '%$search%' or supplier_code like '%$search%' or docdate like '%$search%' or status like '%$search%' )";
        }
        else if($doc == 'si_table')
        {
            $conditon = " WHERE (si_refno like '%$search%' or invoice_date like '%$search%' or Code like '%$search%' )";
        }
        else if($doc == 'prdncn_table')
        {
            $conditon = " WHERE (refno like '%$search%' or loc_group like '%$search%' or supplier_code like '%$search%' or supplier_name like '%$search%' or `status` like '%$search%' or `batch_no` like '%$search%' or stock_collected_by like '%$search%')";
        }
        else if($doc == 'pdncn_table')
        {
            $conditon = " WHERE (refno like '%$search%' or loc_group like '%$search%' or supplier_code like '%$search%' or supplier_name like '%$search%' or trans_type like '%$search%' or docno like '%$search%' or docdate like '%$search%')";
        }

        $sql = $sql.$conditon;
        //print_r($sql); die;
        return $sql;
    }

    function general_get_filtered_data($sql, $doc)
    {
		$query = $this->search_condition($sql, $doc);
        $query = $this->db->query($query)->result_array();
        return count($query);
    }
    
    function general_get_all_data($sql, $doc)
    {
        $query = $this->db->query($sql)->result_array();
        return count($query);
    }

    function general_get_filtered_data_new($sql, $doc)
    {
		$query = $this->search_condition($sql, $doc);
        //print_r($query); die;
        $query = $this->db->query($query)->result_array();
        return count($query);
    }
    
    function general_get_all_data_new($sql, $doc)
    {
        //print_r($sql); die;
        $query = $this->db->query($sql)->result_array();
        return count($query);
    }

    function general_get_filtered_data_old($sql)
    {
        $query = $this->make_condition($sql);
        $query = $this->db->query($query);
        return $query->num_rows();
    }

    function general_get_all_data_old($sql)
    {
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    //created by jr
    function b2b_billing_tb($status, $period_code, $supplier_guid,$limit, $start, $col, $dir, $search)
    {
        if ($col == '' && $dir == '' && $start == '' && $limit == '') {
            $order_by = "ORDER BY  FIELD (a.sorting_two, '1','2','3') ASC , FIELD (a.sorting, '1','2') ASC  , a.name ASC";
        } else {
            $order_by = "ORDER BY  FIELD (a.sorting_two, '1','2','3') ASC , FIELD (a.sorting, '1','2') ASC  , a.name ASC LIMIT " . $start . " , " . $limit . "";
        }

        if ($search == '') {
            $search_in = '';
        } else {
            $search_in = "AND (a.file_status LIKE '%" . $search . "%' OR
            a.period_code LIKE '%" . $search . "%' OR
            a.invoice_number LIKE '%" . $search . "%' OR
            a.name LIKE '%" . $search . "%' OR
            a.invoice_type LIKE '%" . $search . "%' )";
        }

        if ($status == '') {
            $status_in = '';
        } else {
            $status_in = "AND a.file_status = '$status'";
        }

        if ($period_code == '') {
            $period_code_in = '';
        } else {
            $period_code_in = "AND a.period_code = '$period_code'";
        }

        if ($supplier_guid == '') {
            $supplier_guid_in = '';
        } else {
            $supplier_guid_in = "AND a.biller_guid = '$supplier_guid'";
        }


        $query = $this->db->query("SELECT * FROM (SELECT a.`inv_guid`, a.`name`, a.`invoice_number`, a.`inv_status`, a.`period_code`, a.`total_include_tax`, a.`final_amount`, a.`created_at`, a.`biller_guid`, IFNULL(b.`status`, '') AS file_status, IFNULL(b.`created_at`, '') AS slip_created_at, IFNULL(c.`user_id`, '') AS slip_created_by, IFNULL(b.`updated_at`, '') AS slip_updated_at, IFNULL(d.`user_id`, '') AS slip_updated_by, 'Subscription' AS invoice_type, IF(a.`inv_status` = 'Emailed','1','2') AS sorting, IF(b.status = 'Uploaded' && a.`inv_status` = 'Emailed', '1', IF(b.status IS NULL && a.`inv_status` = 'Emailed','2','3')) AS sorting_two , IF(e.`Variance` = '1','Block','') AS variance_status FROM b2b_invoice.supplier_monthly_main a LEFT JOIN lite_b2b.`invoice_slip` b ON a.`invoice_number` = b.`invoice_number` LEFT JOIN lite_b2b.`set_user` c ON b.`created_by` = c.`user_guid` LEFT JOIN lite_b2b.`set_user` d ON b.`updated_by` = d.`user_guid` LEFT JOIN lite_b2b.`query_outstanding_retailer` e ON a.`biller_guid` = e.`supplier_guid` AND e.`Variance` = '1' GROUP BY a.`inv_guid` UNION ALL SELECT a.`inv_guid`, a.`name`, a.`invoice_number`, a.`inv_status`, a.`period_code`, a.`total_include_tax`, a.`final_amount`, a.`created_at`, a.`biller_guid`, IFNULL(b.`status`, '') AS file_status, IFNULL(b.`created_at`, '') AS slip_created_at, IFNULL(c.`user_id`, '') AS slip_created_by, IFNULL(b.`updated_at`, '') AS slip_updated_at, IFNULL(d.`user_id`, '') AS slip_updated_by, 'Registration' AS invoice_type, IF(a.`inv_status` = 'Emailed','1','2') AS sorting, IF(b.status = 'Uploaded' && a.`inv_status` = 'Emailed', '1', IF(b.status IS NULL && a.`inv_status` = 'Emailed','2','3')) AS sorting_two , IF(e.`Variance` = '1','Block','') AS variance_status FROM b2b_invoice.inv_doc a LEFT JOIN lite_b2b.`invoice_slip` b ON a.`invoice_number` = b.`invoice_number` LEFT JOIN lite_b2b.`set_user` c ON b.`created_by` = c.`user_guid` LEFT JOIN lite_b2b.`set_user` d ON b.`updated_by` = d.`user_guid` LEFT JOIN lite_b2b.`query_outstanding_retailer` e ON a.`biller_guid` = e.`supplier_guid` AND e.`Variance` = '1' GROUP BY a.`inv_guid`) a 
        WHERE a.inv_guid != ''
        $search_in
        $status_in
        $period_code_in
        $supplier_guid_in 
        $order_by ");

        //echo $this->db->last_query(); die;

        return $query;
    }
}
