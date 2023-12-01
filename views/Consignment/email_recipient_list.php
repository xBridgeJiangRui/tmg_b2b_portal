<style type="text/css">
    .no-js #loader {
      display: none;
    }

    .js #loader {
      display: block;
      position: absolute;
      left: 100px;
      top: 0;
    }

    .se-pre-con {
      position: fixed;
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      z-index: 9999;
      background: url("<?php echo base_url('assets/loading2.gif') ?>") center no-repeat #fff;
      /*background:   #fff;*/
    }
  </style>

<div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Email Recipient <span class="add_branch_list"></span></h3>
          <div class="box-tools pull-right">
            <div class="box-tools pull-right">
              <button type="button" id="email_recipient_collapse" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
              <button type="button" id="btn_preview_email" class="btn btn-xs btn-warning"><i class="fa fa-envelope"></i> Blast Email</button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
          <form action="<?php echo site_url('Consignment/blast_email') ?>" id="form_blast_email" method="post">
            <table id="table_email_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
              <thead style="white-space: nowrap;"> <!--style="white-space: nowrap;"-->
              <tr>
                <th>No</th>
                <th>Email</th>
                <th>Effective Date</th>
                <th>Statement Date</th>
                <th style="width: 1px;" class="text-center"><input type="checkbox" checked="checked" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
              </tr>
              </thead>
              <tbody>
              <?php $count = 1; ?>
              <?php foreach ($result as $row){ ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row['email_add'];?></td>
                    <td><?php echo $row['effective_date'];?></td>
                    <td><?php echo $row['statement_date'];?></td>
                    <td><input type="checkbox" class="checkbox_guid" name="selected[]" value="<?php echo $row['email_guid']; ?>" checked="checked" /></td>
                </tr>
              <?php $count++; ?>
              <?php } ?>
              </tbody>
            </table>
            <input type="hidden" name="retailer" value="<?php echo $retailer; ?>" />
            <input type="hidden" name="date_start" value="<?php echo $date_start; ?>" />
            <input type="hidden" name="date_end" value="<?php echo $date_end; ?>" />
          </form>
        </div>

      </div>
    </div>
  </div>

<div id="email_preview" class="modal fade" role="dialog" data-keyboard="false">
  <div class="modal-dialog modal-lg" style="width: 50%;">
      <div class="modal-content">
        <div class="modal-header">          
          <h3 class="modal-title">
            <b>Email Preview</b>
            <!-- <div class="box-tools pull-right">
              <button type="button" id="btn_blast_email" class="btn btn-xs btn-success"><i class="fa fa-envelope"></i> Proceed</button>
            </div> -->
          </h3>
          
        </div>
        <div class="modal-body">
          <h4 class="modal-description">This email will be send to selected email.</h4>
          <div id="email-preview-layout"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="button" id="btn_blast_email" class="btn btn-xs btn-success"><i class="fa fa-envelope"></i> Proceed</button>
        </div>
      </div>
  </div>
</div>

<div id="loader_div" class="se-pre-con hidden"></div>

<script type="text/javascript">
$(document).ready(function() {
  $('#table_email_list').DataTable({
    "columnDefs": [
      { className: "aligncenter", targets: [0, -1] },
      { className: "alignright", targets: [] },
      { className: "alignleft", targets: '_all' },
    ],
    'pageLength'  : 9999999999999999,
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [9999999999999999], ["ALL"] ],
    'searching'   : true,
    'ordering'    : true,
    // 'order'       : [ [2 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "sScrollY": "80vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
  });
});
</script>

<script type="text/javascript">
  $("#btn_preview_email, #btn_proceed_blast_email").click(function(){

    total_check = $('.checkbox_guid').is(':checked');
    
    if(total_check == true){
      get_layout_email_preview();
      $('#email_preview').modal('show');
    }else{
      alert('Please select at least one(1) email');
    }
  });

  $("#btn_blast_email").click(function(){

    $.ajax({
      url : "<?php echo site_url('Consignment/blast_email');?>",
      method: "POST",
      data: $("#form_blast_email").serialize(),
      beforeSend : function() {
        $('.btn').button('loading');
        $('#loader_div').removeClass('hidden');
      },
      complete: function() {
        $('.btn').button('reset');
      },
      success : function(data)
      {  
        $(".se-pre-con").fadeOut("slow");
        json = JSON.parse(data);  
        
        var form = $(document.createElement('form'));
        $(form).attr("action", "<?php echo site_url('Consignment');?>");
        $(form).attr("method", "POST");
        $(form).css("display", "none");

        var input_status = $("<input>")
        .attr("type", "text")
        .attr("name", "email_status")
        .val(json['status']);
        $(form).append($(input_status));

        var input_message = $("<input>")
        .attr("type", "text")
        .attr("name", "email_message")
        .val(json['message']);
        $(form).append($(input_message));

        form.appendTo(document.body);
        $(form).submit();

      }
    });
  });
</script>

<script type="text/javascript">

  function get_layout_email_preview(){

    var date_start = '<?php echo $date_start; ?>';
    var date_end = '<?php echo $date_end; ?>';
    var retailer = '<?php echo $retailer; ?>';

    $.ajax({
      url : "<?php echo site_url('Consignment/blast_email_preview');?>",
      method: "POST",
      data:{date_start:date_start,date_end:date_end,retailer:retailer},
      dataType: 'html',
      success: function(html) {         
        $('#email-preview-layout').html(html);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

    $('#email-preview-layout').removeClass('hidden');
  }

</script>