<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if ("OPTIONS" === $_SERVER['REQUEST_METHOD']) {
    die();
}
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Testing extends REST_controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('lite_b2b_model');
        // $this->load->model('demo_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index_post()
    {
        $AppPOST = json_decode(file_get_contents('php://input'), true);
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // $db = $this->lite_b2b_model->user_name($)
        $json = array(
            'username' => $username,
            'password' => $password,
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function insert_user_post()
    {
        $AppPOST = json_decode(file_get_contents('php://input'), true);
        $userGuid = $this->input->post('user_guid');
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $json = array(
            'message' => 'sucess insert user',
        );

        $this->demo_model->insert_user_info($userGuid, $username, $password);
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

 

    public function bulk_convert_e_invoice_post()
    {
        // print_r($this->session->userdata('userid'));die;
        $user_id = $this->session->userdata('userid');
        $user_guid = 'B194913010BC11EA8675000D3AA2838A';//$this->session->userdata('user_guid');
        $from_module = 'panda_gr';//$_SESSION['frommodule'];

        $lite_b2b = 'lite_b2b';
        $b2b_summary = 'b2b_summary';

        $details = $this->input->post('bulk');
        $status = $this->input->post('status');
        $loc = $this->input->post('loc');
        // echo $loc;die;
   

        $customer_guid = '8D5B38E931FA11E79E7E33210BD612D3';//$this->session->userdata('customer_guid');

        $error_refno = '';

        //$haha = $this->load->view('print/invoice_pdf', $data, true);
        $this->load->library('Pdf_invoice');
        $pdf = new Pdf_invoice('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Bulk E-Invoice');
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetAuthor('B2B');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->setPageUnit('pt');
        $x = $pdf->pixelsToUnits('20');
        $y = $pdf->pixelsToUnits('20');
        $font_size = $pdf->pixelsToUnits('9.5');
        $pdf->SetFont('helvetica', '', $font_size, '', 'default', true);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        foreach ($details as $refno2) {
            $check_gr_cn_exists = $this->db->query("SELECT * FROM b2b_summary.grmain_dncn a LEFT JOIN b2b_summary.`ecn_main` b ON a.`RefNo` = b.`refno` AND a.`customer_guid` = b.`customer_guid` WHERE a.refno = '$refno2' AND a.customer_guid = '$customer_guid' AND b.`refno` IS NULL");
            if ($check_gr_cn_exists->num_rows() > 0) {
                $this->session->set_flashdata('warning', 'Bulk Generation not support for GRN which have GRDA, Please generate ECN first.');
                redirect('general/view_status?status=' . $status . '&loc=' . $loc . '&p_f=&p_t=&e_f=&e_t=&r_n=');
                https: //b2b2.xbridge.my/index.php/general/view_status?status=&loc=HQ&p_f=&p_t=&e_f=&e_t=&r_n=
            }
        }

        foreach ($details as $refno) { 
            $deletePage = 0;
            $guid = $this->db->query("SELECT REPLACE(UPPER(UUID()),'-','') AS uuid")->row('uuid');

            $grmain = $this->db->query("SELECT a.`customer_guid`
                    , a.`status`
                    , a.`RefNo`
                    , a.`Location`
                    , IF(b.DONo IS NULL, a.`DONo`, b.DONo) AS DONo
                    , IF(b.InvNo IS NULL, a.`InvNo`, b.InvNo) AS InvNo
                    , IF(b.DocDate IS NULL, a.`DocDate`, b.DocDate) AS DocDate
                    , a.`GRDate`
                    , a.`Code`
                    , a.`Name`
                    , a.`consign`
                    , a.Total
                    , a.gst_tax_sum
                    , a.total_include_tax
                    , a.subtotal1
                    FROM $b2b_summary.grmain AS a 
                    LEFT JOIN $b2b_summary.grmain_proposed AS b 
                    ON a.refno = b.refno 
                    AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '$customer_guid' ");

            $check_url = $this->db->query("SELECT rest_url from $lite_b2b.acc where acc_guid = '$customer_guid' ")->row('rest_url');

            $to_shoot_url = $check_url . "/childdata?table=grchild" . "&refno=" . $refno;
            //  echo $to_shoot_url ;die;
            $ch = curl_init($to_shoot_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
           
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $output = json_decode($response);



            $einv_main = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' AND customer_guid = '$customer_guid' ");

            $H_refno = $grmain->row('RefNo');
            $H_invno = $grmain->row('InvNo');
            $H_dono = $grmain->row('DONo');
            $H_inv_date = $grmain->row('DocDate');
            $H_gr_date = $grmain->row('GRDate');
            $H_total_excl_tax = $grmain->row('total_include_tax');
            $H_tax_amount = $grmain->row('gst_tax_sum');
            $H_total_incl_tax = $grmain->row('total_include_tax');

            $pay_by_grn = $this->db->query("SELECT * FROM b2b_summary.grmain a INNER JOIN b2b_summary.supcus b ON a.Code = b.Code WHERE a.Refno = '$refno' AND a.customer_guid = '$customer_guid' AND b.grn_baseon_pocost = 0 AND b.type = 'S' AND b.customer_guid = '$customer_guid'");

            $pay_by_grn_status = $pay_by_grn->num_rows() > 0 ? 1 : 0;
            $pay_by_grn_status = 1;
            if (($httpcode === 200) && ($grmain->num_rows() > 0) && ($output[0]->line != 'No Records Found')) {
                if ($einv_main->num_rows() > 0) {
                    // echo 1;die;
                    //update
                    // $data = array(
                    //     'invno'=> addslashes($H_invno),
                    //     'dono'=> addslashes($H_dono),
                    //     'inv_date'=> $H_inv_date,
                    //     'gr_date'=> $H_gr_date,
                    //     'total_excl_tax' => $H_total_excl_tax,
                    //     'tax_amount'=> $H_tax_amount,
                    //     'total_incl_tax'=> $H_total_incl_tax,
                    //     'updated_at'=> $this->db->query("select now() as naw")->row('naw'),
                    //     'updated_by'=> $user_id,
                    // );

                    // $this->db->where('einv_guid', $einv_main->row('einv_guid'));
                    // $this->db->where('refno', $refno);
                    // $this->db->where('customer_guid', $customer_guid);
                    // $this->db->update("$lite_b2b.einv_main", $data);
                    $affected_rows = 0;

                    // $get_einv_guid = $einv_main->row('einv_guid');
                    $error_refno .= $refno . ' Invoice Already Generated,\n';
                    $deletePage = 1;
                    $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','EAG','E-Invoice ALready Generated',NOW(),'$user_guid')");
                } else {
                    //insert
                    $data = array(
                        'einv_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                        'customer_guid' => $customer_guid,
                        'refno' => $refno,
                        'einvno' => addslashes($H_invno),
                        'invno' => addslashes($H_invno),
                        'dono' => addslashes($H_dono),
                        //'inv_date'=> $H_inv_date,
                        'inv_date' => $this->db->query("SELECT CURDATE() as curdate")->row('curdate'),
                        'gr_date' => $H_gr_date,
                        'einv_generated_date' => $this->db->query("SELECT CURDATE() as curdate")->row('curdate'),
                        'total_excl_tax' => $H_total_excl_tax,
                        'tax_amount' => $H_tax_amount,
                        'total_incl_tax' => $H_total_incl_tax,
                        'created_at' => $this->db->query("select now() as naw")->row('naw'),
                        'created_by' =>  $user_guid,
                        'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                        'updated_by' => $user_guid,
                    );

                    $this->db->insert("$b2b_summary.einv_main", $data);

                    $get_einv_guid = $this->db->query("SELECT einv_guid from $b2b_summary.einv_main where refno = '$refno' and customer_guid = '$customer_guid'")->row('einv_guid');
                    $affected_rows = $this->db->affected_rows();

                    $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_excl_tax = a.Subtotal1 WHERE a.subtotal1 <> b.total_excl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");

                    $this->db->query("UPDATE b2b_summary.grmain a INNER JOIN b2b_summary.einv_main b ON a.RefNo = b.refno AND a.customer_guid = b.customer_guid SET b.total_incl_tax = a.total_include_tax WHERE a.total_include_tax <> b.total_incl_tax AND a.refno = '$refno' and a.customer_guid = '$customer_guid'");
                } //close else for checking einv_main exist or not


                if ($affected_rows > 0) {
                    $this->db->query("DELETE FROM $b2b_summary.einv_child where einv_guid = '$get_einv_guid'");

                    foreach ($output as $output_row) {

                        $itemcode = $output_row->itemcode;
                        $supcheck = 0;
                        $line = $output_row->line;
                        $barcode = $output_row->barcode;
                        $description = $output_row->description;
                        $packsize = $output_row->packsize;
                        $qty = $output_row->qty;
                        $uom = $output_row->um;
                        $unitprice = $output_row->unitprice;
                        $disc_desc = $output_row->disc_desc;
                        $discamt = $output_row->discamt;
                        $unit_disc_prorate = $output_row->unit_disc_prorate;
                        $unit_price_bfr_tax = $output_row->unit_price_bfr_tax;
                        $totalprice = $output_row->totalprice;
                        $gst_tax_amount = $output_row->gst_tax_amount;
                        $gst_unit_total = $output_row->gst_unit_total;


                        $data =  array(
                            'child_guid' => $this->db->query("SELECT upper(replace(uuid(),'-','')) as guid")->row('guid'),
                            'einv_guid' => $get_einv_guid,
                            'line' => $line,
                            'itemtype' => '',
                            /*'itemlink' => $itemcode,*/
                            'itemcode' => $itemcode,
                            'barcode' => $barcode,
                            'description' => $description,
                            'packsize' => $packsize,
                            'qty' => $qty,
                            'uom' => $uom,
                            'unit_price_before_disc' => $unitprice,
                            'item_discount_description' => $disc_desc,
                            'item_disc_amt' => $discamt,
                            'total_bill_disc_prorated' => $unit_disc_prorate,
                            'total_amt_excl_tax' => $unit_price_bfr_tax * $qty,
                            'total_tax_amt' => $gst_tax_amount,
                            'total_amt_incl_tax' => $totalprice,
                            'checked' => $supcheck,
                            'checked_at' => $this->db->query("select now() as naw")->row('naw'),
                            'checked_by' => $user_guid,
                            'created_at' => $this->db->query("select now() as naw")->row('naw'),
                            'created_by' => $user_guid,
                            'updated_at' => $this->db->query("select now() as naw")->row('naw'),
                            'updated_by' => $user_guid,
                        );

                        $this->db->insert("$b2b_summary.einv_child", $data);
                    } //close json foreach child

                } //close else for checking einv_main got update or insert or not



                $header = $this->db->query("SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' ");

                $einv_guidd = $this->db->query("SELECT einv_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' ")->row('einv_guid');

                $child_info = $this->db->query("SELECT * FROM $b2b_summary.einv_child WHERE einv_guid = '$einv_guidd' order by line asc");


                if (!in_array('!SUPPMOV', $_SESSION['module_code'])) {
                    $this->db->query("UPDATE b2b_summary.grmain set status = 'Invoice Generated' where customer_guid ='$customer_guid' and refno = '$refno'");

                    $this->db->query("UPDATE b2b_summary.grmain set hq_update = 1 where customer_guid ='$customer_guid' and refno = '$refno'");

                    $this->db->query("REPLACE into supplier_movement select 
                    upper(replace(uuid(),'-','')) as movement_guid
                    , '$customer_guid'
                    , '$user_guid'
                    , 'generate_inv'
                    , '$from_module'
                    , '$refno'
                    , now()
                    ");
                };

                // HUGH 2019-04-25



                $pdf->AddPage('L', 'A4');
                $pdf->RefNo = $refno;
                // ob_start();

                $com = new Panda("");
                $test = $com->get_serverdb();


                $conn = new mysqli($test['servername'], $test['username'], $test['password'], $test['dbname']);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }



                $header_customer_guid = "SELECT customer_guid FROM $b2b_summary.einv_main WHERE refno = '$refno' ";
                $result = $conn->query($header_customer_guid);
                $header_customer_guid = $result->fetch_assoc();


                // $header_customer_info = "SELECT BRANCH_CODE,BRANCH_NAME,REPLACE(BRANCH_ADD,'\n','<br>') AS BRANCH_ADD, BRANCH_TEL, BRANCH_FAX,comp_reg_no , gst_no FROM backend.`cp_set_branch` JOIN (SELECT comp_reg_no , gst_no FROM backend.`companyprofile` LIMIT 1 )a ";
                // $result = $conn->query($header_customer_info);
                // $header_customer_info = $result->fetch_assoc();




                $header_main = "SELECT * FROM $b2b_summary.einv_main WHERE refno = '$refno' ";
                $result = $conn->query($header_main);
                $header_main = $result->fetch_assoc();



                $customer_hq_branch_info = "SELECT * FROM $b2b_summary.cp_set_branch WHERE SET_SUPPLIER_CODE = 'HQ' AND SET_CUSTOMER_CODE = 'HQ' ";
                $result = $conn->query($customer_hq_branch_info);
                $customer_hq_branch_info = $result->fetch_assoc();



                $gr_info = "SELECT 
                a.`loc_group` as Location
                , a.`Code`
                , a.`Name`
                , a.`Invno`
                FROM $b2b_summary.grmain AS a 
                LEFT JOIN $b2b_summary.grmain_proposed AS b 
                ON a.refno = b.refno 
                AND a.customer_guid = b.customer_guid where a.refno = '$refno' and a.customer_guid = '" . $header_customer_guid['customer_guid'] . "'";
                $result = $conn->query($gr_info);
                $gr_info = $result->fetch_assoc();

                //??? condition code but find by location
                $supcus_customer = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid'";
                $result = $conn->query($supcus_customer);
                $supcus_customer = $result->fetch_assoc();

                $supcus_supplier = "SELECT * FROM b2b_summary.supcus WHERE Code = '" . $gr_info['Code'] . "' AND customer_guid = '$customer_guid'";
                $result = $conn->query($supcus_supplier);
                $supcus_supplier = $result->fetch_assoc();


                $customer_branch_info = "SELECT * FROM b2b_summary.cp_set_branch WHERE BRANCH_CODE = '" . $gr_info['Location'] . "' AND customer_guid = '$customer_guid'";
                $result = $conn->query($customer_branch_info);
                $customer_branch_info = $result->fetch_assoc();


                $pdf->SetFont('helvetica', '', 9.5);

                $html = '<table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;"> <tbody><tr> <td style="width: 80%;text-align: left"> <table cellspacing="0" cellpadding="0"> <tbody> <tr> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Purchase from Registered GST Supplier </td> <td style="border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"> Goods Received Note Issued by </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $supcus_supplier['Name'] . '</b> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <b>' . $customer_branch_info['BRANCH_NAME'] . ' </b> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_supplier['reg_no'] . ' </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> Co Reg No: ' . $supcus_supplier['reg_no'] . '</td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $supcus_supplier['Add1'] . '
                <br>' . $supcus_supplier['Add2'] . '
                <br>' . $supcus_supplier['Add3'] . '
                <br>' . $supcus_supplier['Add4'] . '<br>
                </td>
                 </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;"> <table> <tbody><tr><td>' . $customer_branch_info['BRANCH_ADD'] . '<br> </td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $supcus_supplier['Tel'] . ' <b>  Fax:</b> ' . $supcus_supplier['Fax'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><br><br><b>Tel:</b> ' . $customer_branch_info['BRANCH_TEL'] . ' <b>  Fax:</b> ' . $customer_branch_info['BRANCH_FAX'] . '</td> </tr></tbody></table> </td> </tr> <tr> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td><b>Sup Code:</b> ' . $gr_info['Code'] . ' - ' . $gr_info['Name'] . ' <b><br>Received Loc:</b> ' . $gr_info['Location'] . ' - ' . $supcus_supplier['Name'] . '</td> </tr></tbody></table> </td> <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"> <table> <tbody><tr><td colspan="2"><b>Tax Invoice No:</b> ' . $header_main['invno'] . ' <b><br>Delivery Order:</b> ' . $header_main['dono'] . '</td> <td><b>Invoice Date:</b> ' . $header_main['inv_date'] . ' <b><br>Ref No:</b> ' . $header_main['refno'] . '</td> </tr></tbody></table> </td> </tr> </tbody> </table> </td> <td style="width: 20%;"> <table id="right-table" border="0" cellspacing="0" cellpadding="0" style="width: 100%;height:500px;"> <tbody style="height:500px;"> <tr> <td style="height:60px;border: 1px solid black;" nowrap=""><p style=""> </p><p style="font-size:12px;text-align: center;"><b>E-Invoice</b></p></td> </tr> <tr> <td style="height:60px; text-align: center; border: 1px solid black;" colspan="2"><p style="text-align:left;"> Inv No</p><p style="font-size:12px;"><b>' . $header_main['einvno'] . '</b></p></td> </tr> </tbody> </table> </td> </tr> </tbody></table>';
                if ($deletePage == 1) {
                    $html = '';
                    // $pdf->deletePage($pdf->PageNo());
                }
                $pdf->writeHTML($html, true, false, true, false, '');



                $html = '  
            <table border="1">
            <tr style ="text-align:center;">
              
             
            <th style="width:23.32%;"><b>Barcode / Itemcode</b></th>
            
            <th style="width:23.66%;"><b>Description</b></th>
            <th style="width:6.66%;"><b>Packsize</b></th>
            <th style="width:6.66%;"><b>Unit Price</b></th>
            <th style="width:6.66%;"><b>Discount Description</b></th>
            <th style="width:6.66%;"><b>Discount Amt</b></th>
            <th style="width:6.66%;"><b>Total Bill Disc Prorated</b></th>
            
            <th style="width:6.66%;"><b>Quantity</b></th>
            <th style="width:6.66%;"><b>Total Amt Exclude Tax</b></th>
            <th style="width:6.66%;"><b>Total Amount Include Tax</b></th>
              

            </tr>
            </table>';
                if ($deletePage == 1) {
                    $html = '';
                    // $pdf->deletePage($pdf->PageNo());
                }
                $pdf->writeHTML($html, true, false, true, false, '');


                foreach ($child_info->result() as $row) {

                    if ($row->item_disc_amt == 0) {
                        $row->item_disc_amt = '';
                    }

                    if ($row->item_disc_amt == 0) {
                        $row->total_bill_disc_prorated = null;
                    }

                    $html = '  
            <table style="border-bottom: 1px solid black;">
            <tr style="" >

             
            <td style="width:23.32%;text-align:left">' . $row->barcode . '<br>' . $row->itemcode . '</td>
            
            <td style="width:23.66%">' . $row->description . '</td>
            <td style="width:6.66%;text-align:right">' . $row->packsize . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->unit_price_before_disc, 2) . '</td>
            <td style="width:6.66%;text-align:right">' . $row->item_discount_description . '</td>
            <td style="width:6.66%;text-align:right">' . $row->item_disc_amt . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->total_bill_disc_prorated, 2) . '</td>

            <td style="width:6.66%;text-align:right">' . $row->qty . ' ' . $row->uom . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->total_amt_incl_tax, 2) . '</td>
            <td style="width:6.66%;text-align:right">' . number_format($row->total_amt_excl_tax, 2) . '</td>
              

            </tr>
            </table>';
                    if ($deletePage == 1) {
                        $html = '';
                        // $pdf->deletePage($pdf->PageNo());
                    }
                    $pdf->writeHTML($html, true, false, true, false, '');
                }

                $html = ' <div class="col-xs-12 table-responsive">
            <table class="table table-striped" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
            <tr>
            <td style="width: 55%;text-align: left">
                    
                   <table cellspacing="0" cellpadding="0">
                     
                    <tbody>
                      
                      <tr>
                        
                        <td style="border: 1px solid black;"> Remark: </td>

                      </tr>

                      <tr>
                        
                        <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">The parties hereby agree that the actual physical amount of goods received will be reflected in the Goods Received Note(GRN) notwithstanding validation of the supplierinvoice. The parties also hereby agree that in the event the total purchase price in the GRN and the supplier Invoice different amount, we shall pay the lower amount of the two. 
                          
                        </td>

                      </tr>

                    </tbody>

            </table>
             
               
                   
              
            </td>
            <td style="width: 45%;">


                    <table id="right-table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                    <thead>
                            <tr>
                            
                               

                                <th style="width:100%;text-align: center;border: 1px solid black;" colspan = "2"><b>Document Summary</b></th>
                           
                           

                            </tr>
                    </thead>
                    <tbody> 
                            <tr>
                              
                              

                              <td style="width:70%;text-align: center;border: 1px solid black;" nowrap="">Total Before Tax & Discount</td>

                              <td style="width:30%;text-align: right;border: 1px solid black;" nowrap="">' . number_format($header->row('total_excl_tax'), 2) . '</td>

                            </tr>

                            <tr>

                       
                              <td style="text-align: center; border: 1px solid black;" >Item Tax Amount</td>
                              <td style="text-align: right; border: 1px solid black;" >' . number_format($header->row('tax_amount'), 2) . '</td>


                            </tr>

                            <tr>

                              
                              <td style="text-align: center; border: 1px solid black;" >Total Amount Include Tax</td>
                              <td style="text-align: right; border: 1px solid black;" >' . number_format($header->row('total_incl_tax'), 2) . '</td>



                            </tr>


                    </tbody>
                  
                    </table>


              </td>
            </tr>

                </table>
              </div>';
                if ($deletePage == 1) {
                    $html = '';
                    $pdf->deletePage($pdf->PageNo());
                }
                $pdf->writeHTML($html, true, false, true, false, '');
            } //check api shoot success and grmain got the result or not
            else {
                $pdf->AddPage('L', 'A4');
                $pdf->RefNo = '';
                $html = '';
                $error_refno .= $refno . ' E-Invoice Generated Error,Please Contact Admin ,\n';
                $pdf->deletePage($pdf->PageNo());
                $this->db->query("INSERT INTO einv_err_log (customer_guid,refno,error_code,error_reason,created_at,created_by) VALUES('$customer_guid','$refno','ERR','E-Invoice Generated Error',NOW(),'$user_guid')");
                // echo 'no data';die;
            }
        } //close foreach for looping refno

        //start create pdf
        // Delete page 6

        $pdf->lastPage();
        // ob_end_clean(); 

        // if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        //    // echo 'This is a server using Windows!';die;
        //     $pdf->Output($_SERVER['DOCUMENT_ROOT'] .'/lite_panda_b2b'.$virtual_path.'/invoice/B2B_'.$refno.'.pdf', 'F');  
        // } 
        // else {
        //     //echo 'This is a server not using Windows!';die;
        //     $pdf->Output($_SERVER['DOCUMENT_ROOT'] .$virtual_path.'/invoice/B2B_'.$refno.'.pdf', 'F');  
        // }


        // $pdf->Output($refno.'.pdf', 'I');

        if (($error_refno != '') && ($error_refno != null)) {
            $error_refno = rtrim($error_refno, ',\n') . '';
            // echo $error_refno;die;

            // write some JavaScript code
            $js = <<<EOD
app.alert('$error_refno');
EOD;

            // force print dialog
            $js .= 'print(true);';
        }
        // set javascript
        $pdf->IncludeJS($js);

        ob_end_clean();
        //Close and output PDF document
        $pdf->Output('bulk_e_invoice' . date('Y-m-d') . '.pdf', 'I');







        // if($error > 0){
        //     $success_msg = 'Duplicate successfully';
        //     $button = '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        //     $this->message->success_message($button , $success_msg, '');
        // }
        // else
        // {   
        //     $error_message = 'Failed to duplicate';
        //     $xerror = $this->db->error();
        //     $xerror['message'] = ($xerror['message'] == '') || ($xerror['message'] == null) ? $error_message : $xerror['message'];
        //     $this->message->error_message($xerror['message'], '1');
        // }//close else

    } //close bulk_convert_e_invoice
}
