	<div class="table-responsive">
		
		<table id="example10" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>UserID</th>
            <th>User Group</th>
            <th>Branch Code</th>
            <th>Branch </th>
          </tr>
          </thead>
          <tbody>
<?php
		 
	$DBhost = "localhost";
	$DBuser = "root";
	$DBpass = "";
	$DBname = "lite_b2b";
	
	
	$DBcon = new PDO("mysqli:host=$DBhost;dbname=$DBname",$DBuser,$DBpass);
	
	
	if (isset($_REQUEST['id'])) {
			
		// $id = intval($_REQUEST['id']);
		// $query = "SELECT * FROM set_user WHERE user_guid='$id'";
		// $stmt = $DBcon->prepare( $query );
		// $stmt->execute(array(':id'=>$id));
		// $row=$stmt->fetch(PDO::FETCH_ASSOC);
		// extract($row);

		$q = "SELECT a.*, b.`branch_name`, b.branch_code, c.`user_group_name` FROM set_user a INNER JOIN acc_branch b ON a.`branch_guid` = b.`branch_guid`
			INNER JOIN set_user_group c ON a.`user_group_guid` = c.`user_group_guid` WHERE a.`user_guid` = '".$_REQUEST['id']."'";
		$stmt = $DBcon->prepare( $q );
		$stmt->execute();
		while( $row = $stmt->fetch() ) 
		{
		?>
             <tr>
                <td><?php echo $row['user_id']?></td>
                <td><?php echo $row['user_group_name']?></td>
                <td><?php echo $row['branch_code']?>
                <td><?php echo $row['branch_name']?>
                	<a onclick="return confirm_check();" href="module_setup/delete_user_branch?branch_guid=<?php echo $row['branch_guid']?>&user_guid=<?php echo $row['user_guid']?>"><button style="float: right" title="Delete" onclick="" type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete_branch" data-name="<?php echo $row['branch_name']?>" ><i class="glyphicon glyphicon-trash"></i></button></a>
                </td>
             </tr>	
		<?php
		}				
	}
	?>
			</tbody>
		</table>
			
	</div>

<script type="text/javascript">

function delete_branch()
  {
    $('#delete_branch').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) 

      var modal = $(this)
      modal.find('.modal-title').text(button.data('name'))
      modal.find('[id="created_at"]').text(button.data('created_at'))
      modal.find('[id="created_by"]').text(button.data('created_by'))
      modal.find('[id="updated_at"]').text(button.data('updated_at'))
      modal.find('[id="updated_by"]').text(button.data('updated_by'))

    });
  }

  function confirm_check()
  {
      var answer=confirm("Confirm want to delete record ?");
      return answer;
  }

  </script>