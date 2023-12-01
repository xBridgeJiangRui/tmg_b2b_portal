
        <h2 style="margin-top:0px">Account branch</h2>
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
	    <tr><td>Branch Code</td><td><?php echo $branch_code; ?></td></tr>
	    <tr><td>Branch Name</td><td><?php echo $branch_name; ?></td></tr>
	    <tr><td>Branch Regno</td><td><?php echo $branch_regno; ?></td></tr>
	    <tr><td>Branch Gstno</td><td><?php echo $branch_gstno; ?></td></tr>
	    <tr><td>Branch Fax</td><td><?php echo $branch_fax; ?></td></tr>
	    <tr><td>Branch Add1</td><td><?php echo $branch_add1; ?></td></tr>
	    <tr><td>Branch Add2</td><td><?php echo $branch_add2; ?></td></tr>
	    <tr><td>Branch Add3</td><td><?php echo $branch_add3; ?></td></tr>
	    <tr><td>Branch Add4</td><td><?php echo $branch_add4; ?></td></tr>
	    <tr><td>Branch Postcode</td><td><?php echo $branch_postcode; ?></td></tr>
	    <tr><td>Branch State</td><td><?php echo $branch_state; ?></td></tr>
	    <tr><td>Branch Country</td><td><?php echo $branch_country; ?></td></tr>
	    <tr><td>Created At</td><td><?php echo $created_at; ?></td></tr>
	    <tr><td>Created By</td><td><?php echo $created_by; ?></td></tr>
	    <tr><td>Updated At</td><td><?php echo $updated_at; ?></td></tr>
	    <tr><td>Updated By</td><td><?php echo $updated_by; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('acc_branch') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>