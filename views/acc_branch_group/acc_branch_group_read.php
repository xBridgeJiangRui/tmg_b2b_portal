
        <h2 style="margin-top:0px">Account branch group</h2>
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
	    <tr><td>Concept Name</td><td><?php echo $concept_name; ?></td></tr>
	    <!-- <tr><td>Isactive</td><td><?php echo $isactive; ?></td></tr> -->
	    <tr><td>Group Name</td><td><?php echo $group_name; ?></td></tr>
	    <tr><td>Created At</td><td><?php echo $created_at; ?></td></tr>
	    <tr><td>Created By</td><td><?php echo $created_by; ?></td></tr>
	    <tr><td>Updated At</td><td><?php echo $updated_at; ?></td></tr>
	    <tr><td>Updated By</td><td><?php echo $updated_by; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('acc_branch_group') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>