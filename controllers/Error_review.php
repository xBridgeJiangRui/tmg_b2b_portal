<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_review extends CI_Controller {

    public function index()
    {
       // $this->load->view('header');
        if($this->session->userdata('loginuser') == true && $this->session->userdata('userid') != '')
        { 
            // echo 1;die;
            if(isset($_REQUEST['guid']))
            {
                $customer_guid = $_REQUEST['guid'];
                $count_branch = $this->db->query("SELECT (aa.count_cp_branch - bb.count_acc_branch) as different2,cc.acc_name,aa.count_cp_branch,bb.count_acc_branch,IF(aa.count_cp_branch > bb.count_acc_branch,CONCAT('Backend Branch > Acc Branch(',aa.count_cp_branch - bb.count_acc_branch,')'),CONCAT('Backend Branch < Acc Branch(',bb.count_acc_branch - aa.count_cp_branch,')')) as different,IF(aa.count_cp_branch > bb.count_acc_branch,CONCAT('<a href=".base_url()."index.php/Error_review?guid=',customer_guid,'&type=left&rtype=0><button class=\"btn btn-sm btn-primary\">View Detail</button></a>'),CONCAT('<a href=".base_url()."index.php/Error_review?guid=',customer_guid,'&type=right&rtype=1><button class=\"btn btn-sm btn-primary\">View Details</button></a>')) as button FROM (SELECT customer_guid,count(*) as count_cp_branch FROM b2b_summary.cp_set_branch GROUP BY customer_guid) aa LEFT JOIN (SELECT b.acc_guid,count(branch_code) as count_acc_branch FROM lite_b2b.acc_branch a INNER JOIN lite_b2b.acc_concept b ON a.concept_guid = b.concept_guid GROUP BY b.acc_guid) bb ON aa.customer_guid = bb.acc_guid INNER JOIN lite_b2b.acc cc ON aa.customer_guid = cc.acc_guid having different2 > 0")->result();
                // echo $this->db->last_query();die;
                if(isset($_REQUEST['type']))
                {
                    if($_REQUEST['type'] == 'left')
                    {
                        if(isset($_REQUEST['rtype']))
                        {
                            if($_REQUEST['rtype'] == 0)
                            {
                                $cp_set_branch = $this->db->query("SELECT aa.branch_code,aa.branch_name,bb.branch_code,bb.branch_name FROM (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') aa LEFT JOIN (SELECT a.* FROM lite_b2b.acc_branch a INNER JOIN lite_b2b.acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code")->result();
                            }
                            else
                            {
                                $cp_set_branch = $this->db->query("SELECT aa.branch_code,aa.branch_name,bb.branch_code,bb.branch_name FROM (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') aa LEFT JOIN (SELECT a.* FROM lite_b2b.acc_branch a INNER JOIN lite_b2b.acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code WHERE bb.branch_code IS NULL")->result();
                            }
                        }
                    }
                    else
                    {
                        if(isset($_REQUEST['rtype']))
                        {
                            if($_REQUEST['rtype'] == 0)
                            {
                                $cp_set_branch = $this->db->query("SELECT aa.branch_code,aa.branch_name,bb.branch_code,bb.branch_name FROM (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') aa RIGHT JOIN (SELECT a.* FROM lite_b2b.acc_branch a INNER JOIN lite_b2b.acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code")->result();
                            }
                            else
                            {
                                $cp_set_branch = $this->db->query("SELECT aa.branch_code,aa.branch_name,bb.branch_code,bb.branch_name FROM (SELECT * FROM b2b_summary.cp_set_branch WHERE customer_guid = '$customer_guid') aa RIGHT JOIN (SELECT a.* FROM lite_b2b.acc_branch a INNER JOIN lite_b2b.acc_concept b ON a.concept_guid = b.concept_guid WHERE b.acc_guid = '$customer_guid') bb ON aa.branch_code = bb.branch_code WHERE aa.branch_code IS NULL")->result();
                            }
                         }
                    }
                }
                $show_cp_set_branch = 1;
            }
            else
            {
                $count_branch = $this->db->query("SELECT (aa.count_cp_branch - bb.count_acc_branch) as different2,cc.acc_name,aa.count_cp_branch,bb.count_acc_branch,IF(aa.count_cp_branch > bb.count_acc_branch,CONCAT('Backend Branch > Acc Branch(',aa.count_cp_branch - bb.count_acc_branch,')'),CONCAT('Backend Branch < Acc Branch(',bb.count_acc_branch - aa.count_cp_branch,')')) as different,IF(aa.count_cp_branch > bb.count_acc_branch,CONCAT('<a href=".base_url()."index.php/Error_review?guid=',customer_guid,'&type=left&rtype=0><button class=\"btn btn-sm btn-primary\">View Detail</button></a>'),CONCAT('<a href=".base_url()."index.php/Error_review?guid=',customer_guid,'&type=right&rtype=0><button class=\"btn btn-sm btn-primary\">View Details</button></a>')) as button FROM (SELECT customer_guid,count(*) as count_cp_branch FROM b2b_summary.cp_set_branch GROUP BY customer_guid) aa LEFT JOIN (SELECT b.acc_guid,count(branch_code) as count_acc_branch FROM lite_b2b.acc_branch a INNER JOIN lite_b2b.acc_concept b ON a.concept_guid = b.concept_guid GROUP BY b.acc_guid) bb ON aa.customer_guid = bb.acc_guid INNER JOIN lite_b2b.acc cc ON aa.customer_guid = cc.acc_guid having different2 > 0")->result();                
                $cp_set_branch = array();
                $show_cp_set_branch = 0;
            }
            
            $data = array(
                'count_branch' => $count_branch,
                'cp_set_branch' => $cp_set_branch,
                'show_cp_set_branch' => $show_cp_set_branch,
            );

            $this->load->view('header');
            $this->load->view('Error_view',$data);
            $this->load->view('footer');
        }
        else
        {
            redirect('Logout');
        }
    }

}
?>

