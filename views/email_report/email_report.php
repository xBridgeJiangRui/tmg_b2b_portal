<style type="text/css">

</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
    <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Email Report</h3>
          <div class="box-tools pull-right">

          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
          <div id="">
          
                  <table id="email_report_tb" class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Subject</th>
                        <th>Email Address</th>
                        <th>From Email Address</th>
                        <th>Send At</th>
                        <th>Module</th>
                        <th>Remark</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
             
              </div>  
        </div>

      </div>

    </div>
  </div>

</div>
</div>
<script>
$(document).ready(function() {
  $('#email_report_tb').DataTable({
    "columnDefs": [ ],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [4 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Email_report/email_report_tb');?>",
        "type": "POST",
    },
    columns: [

             { "data": "guid" ,render:function( data, type, row ){

                var element = '';

                element += '<button id="view_content_btn" style="margin-left:5px;" title="Content" class="btn btn-sm btn-info" guid="'+row['guid']+'"><i class="fa fa-eye"></i></button>';

                return element;
       
              }},
             { "data": "subject" },
             { "data": "email_id" },
             { "data": "from_email" },
             { "data": "updated_at" },
             { "data": "module" },
             { "data": "remark" },
             { "data": "status" },

             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('guid', aData['guid']);
      
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

  $(document).on('click','#view_content_btn',function(){

    var guid = $(this).attr('guid');

    $.ajax({
          url:"<?php echo site_url('Email_report/fetch_content');?>",
          method:"POST",
          data:{guid:guid},
          dataType: 'JSON',
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            $('.btn').button('reset');
          },//close success
          complete:function(data)
          { 
            json = JSON.parse(data['responseText']);
            
             $("#medium-modal").show();
             var modal = $("#medium-modal").modal();

             modal.find('.modal-title').html('Email Content');

             methodd = '';

             methodd +='<div class="col-md-12">';

             methodd += '<div class="col-md-12">'+json['content'][0]['content']+'</div>';

             methodd += '</div>';

             methodd_footer = '<p class="full-width"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

             modal.find('.modal-footer').html(methodd_footer);
             modal.find('.modal-body').html(methodd);
          }
    });//close ajax
  });

});
</script>
