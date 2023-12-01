
        <h2 style="margin-top:0px">Account concept</h2>
        <table class="table">
            <tr><td>Isactive</td><?php 
                if($isactive == '1')
                {
                    ?>
                    <td><span class="glyphicon">&#xe013;</span></td></tr>
                    <?php
                }
                else
                {
                    ?>
                    <td><span class="glyphicon">&#xe014;</span></td></tr>
                    <?php
                }
                ?>
	    <!-- <tr><td>Acc Guid</td><td><?php echo $acc_guid; ?></td></tr> -->
        <tr><td>Account Name</td><td><?php echo $acc_name; ?></td></tr>
	    <tr><td>Concept Name</td><td><?php echo $concept_name; ?></td></tr>
	    <tr><td>Created At</td><td><?php echo $created_at; ?></td></tr>
	    <tr><td>Created By</td><td><?php echo $created_by; ?></td></tr>
	    <tr><td>Updated At</td><td><?php echo $updated_at; ?></td></tr>
	    <tr><td>Updated By</td><td><?php echo $updated_by; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('acc_concept') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>