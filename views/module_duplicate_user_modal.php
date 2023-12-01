
<div class="modal-header"> 
    <button type="button" class="close" data-dismiss="modal" onClick="window.location.reload();">&times;</button>
    <h4 class="modal-title" id="myModalLabel">From : <?php echo $get_from_acc_name ?> => To : <?php echo $get_to_acc_name ?></h4>
</div>
<div class="modal-body">
    <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Email</th>
                    <th><?php echo $get_from_acc_name ?></th>
                    <th><?php echo $get_to_acc_name ?></th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php                                       
                    foreach($result->result() as $row)
                    {
                ?>                        
                    <tr>
                        <td>
                            <?php if($row->to_user_id == '')
                            {
                            ?>
                                <a type="button" title="<?php echo $row->user_guid ?>" class="btn btn-xs btn-warning"  href="<?php echo site_url("Module_setup/add_via_user?supplier_guid=".$row->supplier_guid."&from_acc_guid=".$from_acc_guid."&to_acc_guid=".$to_acc_guid."&user_guid=".$row->user_guid) ?>" ?><i class="glyphicon glyphicon-plus" ></i> 
                            <?php } ?>
                        </td> 
                        <th><?php echo $row->user_id ?></th>
                        <td><?php echo $row->from_user_id ?></td> 
                        <td><?php echo $row->to_user_id ?></td> 
                        <td><?php echo $row->remark ?></td> 
                    </tr>
                <?php 
                } 
                ?>
                           
            </tbody>
        </table>
</div>
    <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload();">Close</button>
    </div>