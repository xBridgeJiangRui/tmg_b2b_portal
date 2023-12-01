
        <h2 style="margin-top:0px">Account</h2>
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
	    <tr><td>Acc Name</td><td><?php echo $acc_name; ?></td></tr>
	    <tr><td>Acc Regno</td><td><?php echo $acc_regno; ?></td></tr>
	    <tr><td>Acc Gstno</td><td><?php echo $acc_gstno; ?></td></tr>
	    <tr><td>Acc Taxcode</td><td><?php echo $acc_taxcode; ?></td></tr>
	    <tr><td>Acc Add1</td><td><?php echo $acc_add1; ?></td></tr>
	    <tr><td>Acc Add2</td><td><?php echo $acc_add2; ?></td></tr>
	    <tr><td>Acc Add3</td><td><?php echo $acc_add3; ?></td></tr>
	    <tr><td>Acc Add4</td><td><?php echo $acc_add4; ?></td></tr>
	    <tr><td>Acc Postcode</td><td><?php echo $acc_postcode; ?></td></tr>
	    <tr><td>Acc State</td><td><?php echo $acc_state; ?></td></tr>
	    <tr><td>Acc Country</td><td><?php echo $acc_country; ?></td></tr>
	    <tr><td>Created At</td><td><?php echo $created_at; ?></td></tr>
	    <tr><td>Created By</td><td><?php echo $created_by; ?></td></tr>
	    <tr><td>Updated At</td><td><?php echo $updated_at; ?></td></tr>
	    <tr><td>Updated By</td><td><?php echo $updated_by; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('acc') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
       