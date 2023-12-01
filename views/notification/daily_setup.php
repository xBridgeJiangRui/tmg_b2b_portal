<style>
.content-wrapper{
  min-height: 850px !important; 
}

.alignright {
  text-align: right;
}

.alignleft
{
  text-align: left;
}

.cell_breakWord{
  word-break: break-all;
  max-width: 1px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice
{
  background: #3c8dbc;
} 

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
  display: inline-grid;
  white-space: nowrap;
  overflow-x: hidden;
  overflow-y: scroll;
  max-height: 250px;
}

.notes_css {
  min-height: 20px;
  background-color: #7af740;
  border: 1px solid #e3e3e3;
  border-radius: 4px;
  padding: 9px;
  /* margin-bottom: 20px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.05); */
}

.no-padding-right {
  padding-right: 0 !important; /* Add !important to override any existing CSS rules */
}

</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>
    <div class="row">
        <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
            <h3 class="box-title">Notification Report</h3><br>
            <div class="box-tools pull-right">

                <button id="create_btn_retailer" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create By Retailer</button>

                <button id="create_btn_report" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus" aria-hidden="true" ></i> Create By Report</button>
                
            </div>
            </div>
            <div class="box-body">
                
                <table class="table table-bordered table-striped dataTable" id="notification_table"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                <thead style="white-space: nowrap;word-break: break-word !important">
                    <tr>
                    <th>Action</th>
                    <th>Retailer Name</th>
                    <th>Notification Name</th>
                    <th>Notification Description</th>
                    <th>Active</th>
                    <th>Created At</th>
                    <th>Created By</th>
                    <th>Updated At</th>
                    <th>Updated By</th>
                    <th>Sync Status</th>
                    <th>Sync At</th>
                    </tr>
                </thead>
                <tbody> 
                </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
            <h3 class="box-title">Notification User</h3><br>
            <div class="box-tools pull-right">
                <span id='btn_append'> </span>
            </div>
            </div>
            <div class="box-body">
                
                <table class="table table-bordered table-striped dataTable" id="list_table_child"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                <thead style="white-space: nowrap;word-break: break-word !important">
                    <tr>
                    <th>
                        <input type="checkbox" id="checkall_input_table" name="checkall_input_table" table_id="list_table_child">
                    </th> 
                    <!-- <th>Action</th> -->
                    <th>Retailer Name</th>
                    <th>User Email</th>
                    <th>User Name</th>
                    <th>Active</th>
                    <th>Created At</th>
                    <th>Created By</th>
                    <!-- <th>Updated At</th>
                    <th>Updated By</th> -->
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

<script>
$(document).ready(function () {    
    $('#list_table_child').DataTable({
        "columnDefs": [{"targets": '_all' ,"orderable": false}],
        'order': [],
        "sScrollY": "30vh", 
            "sScrollX": "100%", 
            "sScrollXInner": "100%", 
            "bScrollCollapse": true,
        dom: "<'row'<'col-sm-2 remove_padding_right 'l > <'col-sm-10' f>  " + "<'col-sm-1' <'toolbar_list'>>>" +'rt<"row" <".col-md-4 remove_padding" i>  <".col-md-8" p> >',
            "language": {
                    "lengthMenu": "Display _MENU_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "info":           "Show _START_ - _END_ of _TOTAL_ entry",
                    "zeroRecords": "<?php echo '<b>Please Select Notification Report to view data.</b>'; ?>",
        },
        "pagingType": "simple_numbers",
    });
    $('.remove_padding_right').css({'text-align':'left'});
    $("div.remove_padding").css({"text-align":"left"});

    $('#notification_table').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": 0 },
        ],
        "serverSide": true, 
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 100, 9999999], [10, 25, 50, 100, 'ALL'] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [7 , 'desc']],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "50vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
        "ajax": {
            "url": "<?php echo site_url('Daily_email_setup/notification_list_tb');?>",
            "type": "POST",
        },
        columns: [
            { data: "rep_option_guid", render: function(data, type, row){ 
                var element = '';

                element += '<button id="edit_btn" style="margin-left:5px;" title="Edit" class="btn btn-xs btn-info" guid="'+row['rep_option_guid']+'" customer_guid="'+row['customer_guid']+'" report_guid="'+row['report_guid']+'" option_description="'+row['option_description']+'" log_table="'+row['log_table']+'" isactive="'+row['isactive']+'"><i class="fa fa-pencil"></i></button>';

                element += '<button id="delete_btn" style="margin-left:5px;" title="Edit" class="btn btn-xs btn-danger" guid="'+row['rep_option_guid']+'" customer_guid="'+row['customer_guid']+'" ><i class="fa fa-trash"></i></button>';

                return element;
            }},
            { data: "acc_name"},
            { data: "log_table"},
            { data: "option_description"},
            { data: "isactive", render: function(data, type, row){ 
                var element = '';

                if(data == '1')
                {
                    element = 'Yes';
                }
                else
                {
                    element = 'No';
                }

                return element;
            }},
            { data: "created_at"},
            { data: "created_by"},
            { data: "updated_at"},
            { data: "updated_by"},
            { data: "sync_status"},
            { data: "sync_at"},
        ],
        dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
        //     buttons: [

        //    { extend: 'excelHtml5',
        //      exportOptions: {columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20 ]} },

        //    { extend: 'csvHtml5',  
        //      exportOptions: {columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20 ]} },
        //           ],
        // "pagingType": "simple",
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).closest('tr').css({"cursor": "pointer"});
            $(nRow).attr('report_guid', aData['report_guid']);
            $(nRow).attr('rep_option_guid', aData['rep_option_guid']);
            $(nRow).attr('customer_guid', aData['customer_guid']);
            // $(nRow).closest('tr').find('td:eq(0)').css('white-space', 'nowrap');
            // $(nRow).closest('tr').find('td:eq(4)').css('word-break', 'break-all');
        },
        "initComplete": function( settings, json ) {
        interval();
        }
    });//close datatable

    $(document).on('click','#create_btn_retailer',function(){

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Create Notification Report By Retailer');

        methodd = '';

        methodd +='<div class="col-md-12">';

        methodd += '<div class="form-group"><label>Notification Report </label> <select class="form-control select2" name="noti_report" id="noti_report" > <option value=""> -SELECT DATA- </option><?php foreach($get_table as $row) { ?> <option value="<?php echo $row->guid?>"><?php echo addslashes($row->log_table)?>  </option> <?php } ?></select> </div> ';

        methodd += '<div class="form-group"><label>Retailer Name </label> <button id="retailer_list_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="retailer_list_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button> <select class="form-control select2" name="noti_retailer" id="noti_retailer" multiple="multiple"> <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name)?>  </option> <?php } ?></select> </div> ';

        // methodd += '<div class="form-group"><label>Notification Name </label> <input type="text" class="form-control input-sm" id="report_name" name="report_name" autocomplete="off"/> </div> ';

        // methodd += '<div class="form-group"><label>Notification Code </label> <input type="text" class="form-control input-sm" id="report_code" name="report_code" autocomplete="off"/> </div> ';

        methodd += '';
        
        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_submit_retailer" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
    
        setTimeout(function(){
            $('#noti_report').select2();
            $('#noti_retailer').select2();

        },300);

    });//close 
    
    $(document).on('click','#create_submit_retailer',function(){

        var notification_retailer = $('#noti_retailer').val();
        var notification_guid = $('#noti_report').val();
        // var report_name = $('#report_name').val();
        // var report_code = $('#report_code').val();

        if((notification_retailer == '') || (notification_retailer == null) || (notification_retailer == 'null'))
        {
            alert('Please Select Retailer Name.');
            return;
        }
        
        if((notification_guid == '') || (notification_guid == null) || (notification_guid == 'null'))
        {
            alert('Please Select Notification Report.');
            return;
        }

        // if((report_name == '') || (report_name == null) || (report_name == 'null'))
        // {
        //     alert('Please insert Notification Name.');
        //     return;
        // }

        // if((report_code == '') || (report_code == null) || (report_code == 'null'))
        // {
        //     alert('Please insert Notification Code.');
        //     return;
        // }

        //,report_code:report_code,report_name:report_name,

        confirmation_modal('Are you sure want to Create?');
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Daily_email_setup/process_notification_create') ?>",
            method:"POST",
            data:{notification_guid:notification_guid,notification_retailer:notification_retailer},
            beforeSend:function(){
                $('.btn').button('loading');
                swal.fire({
                    allowOutsideClick: false,
                    title: 'Processing...',
                    showCancelButton: false,
                    showConfirmButton: false,
                    onOpen: function () {
                    swal.showLoading()
                    }
                });
            },
            success:function(data)
            {
                json = JSON.parse(data);
                if(json.para1 == 'false')
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "error",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    }).then(() => {
                    location.reload();
                    });
                }
                else
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    $("#medium-modal").modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "success",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    }).then(() => {
                    location.reload();
                    });
                }
            }//close success
        });//close ajax 
        });//close document yes click
    });//close 

    $(document).on('click','#create_btn_report',function(){

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Create Notification Report By Report');

        methodd = '';

        methodd +='<div class="col-md-12">';

        methodd += '<div class="form-group"><label>Retailer Name </label> <select class="form-control select2" name="r_noti_retailer" id="r_noti_retailer" > <option value=""> -SELECT DATA- </option> <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name)?>  </option> <?php } ?></select> </div> ';

        methodd += '<div class="form-group"><label>Notification Report </label> <button id="report_list_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="report_list_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button>  <select class="form-control select2" name="r_noti_report" id="r_noti_report" multiple="multiple"> <?php foreach($get_table as $row) { ?> <option value="<?php echo $row->guid?>"><?php echo addslashes($row->log_table)?>  </option> <?php } ?></select> </div> ';

        // methodd += '<div class="form-group"><label>Notification Name </label> <input type="text" class="form-control input-sm" id="report_name" name="report_name" autocomplete="off"/> </div> ';

        // methodd += '<div class="form-group"><label>Notification Code </label> <input type="text" class="form-control input-sm" id="report_code" name="report_code" autocomplete="off"/> </div> ';

        methodd += '';
        
        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_submit_report" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
    
        setTimeout(function(){
            $('#r_noti_retailer').select2();
            $('#r_noti_report').select2();

        },300);

    });//close 
    
    $(document).on('click','#create_submit_report',function(){

        var r_notification_retailer = $('#r_noti_retailer').val();
        var r_notification_guid = $('#r_noti_report').val();
        // var r_report_name = $('#r_report_name').val();
        // var r_report_code = $('#r_report_code').val();

        if((r_notification_retailer == '') || (r_notification_retailer == null) || (r_notification_retailer == 'null'))
        {
            alert('Please Select Retailer Name.');
            return;
        }
        
        if((r_notification_guid == '') || (r_notification_guid == null) || (r_notification_guid == 'null'))
        {
            alert('Please Select Notification Report.');
            return;
        }

        // if((r_report_name == '') || (r_report_name == null) || (r_report_name == 'null'))
        // {
        //     alert('Please insert Notification Name.');
        //     return;
        // }

        // if((r_report_code == '') || (r_report_code == null) || (r_report_code == 'null'))
        // {
        //     alert('Please insert Notification Code.');
        //     return;
        // }

        //,r_report_code:r_report_code,r_report_name:r_report_name

        confirmation_modal('Are you sure want to Create?');
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Daily_email_setup/process_notification_create_report') ?>",
            method:"POST",
            data:{r_notification_guid:r_notification_guid,r_notification_retailer:r_notification_retailer},
            beforeSend:function(){
                $('.btn').button('loading');
                swal.fire({
                    allowOutsideClick: false,
                    title: 'Processing...',
                    showCancelButton: false,
                    showConfirmButton: false,
                    onOpen: function () {
                    swal.showLoading()
                    }
                });
            },
            success:function(data)
            {
                json = JSON.parse(data);
                if(json.para1 == 'false')
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "error",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    }).then(() => {
                    location.reload();
                    });
                }
                else
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    $("#medium-modal").modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "success",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    }).then(() => {
                    location.reload();
                    });
                }
            }//close success
        });//close ajax 
        });//close document yes click
    });//close 

    $(document).on('click','#edit_btn',function(){
        var guid = $(this).attr('guid');
        var customer_guid = $(this).attr('customer_guid');
        var report_guid = $(this).attr('report_guid');
        var report_name = $(this).attr('log_table');
        var option_description = $(this).attr('option_description');
        // var option_code = $(this).attr('option_code');
        var isactive = $(this).attr('isactive');

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Edit Notification Report');

        methodd = '';

        methodd +='<div class="col-md-12">';

        methodd += '<input type="hidden" class="form-control input-sm" id="main_guid" name="main_guid" autocomplete="off" value="'+guid+'"/>';

        methodd += '<div class="form-group"><label>Retailer Name </label> <select class="form-control select2" name="edit_retailer" id="edit_retailer" > <option value=""> -SELECT DATA- </option><?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name)?>  </option> <?php } ?></select> </div> ';

        methodd += '<div class="form-group"><label>Notification Report </label> <select class="form-control select2" name="edit_report" id="edit_report" > <option value=""> -SELECT DATA- </option><?php foreach($get_table as $row) { ?> <option value="<?php echo $row->guid?>"><?php echo addslashes($row->log_table)?>  </option> <?php } ?></select> </div> ';

        methodd += '<div class="form-group"><label>Notification Name </label> <input type="text" class="form-control input-sm" id="edit_report_name" name="edit_report_name" autocomplete="off" value="'+report_name+'" readonly/> </div> ';

        methodd += '<div class="form-group"><label>Notification Description </label> <input type="text" class="form-control input-sm" id="edit_report_description" name="edit_report_description" autocomplete="off" value="'+option_description+'"/> </div> ';

        // methodd += '<div class="form-group"><label>Notification Code </label> <input type="text" class="form-control input-sm" id="edit_report_code" name="edit_report_code" autocomplete="off" value="'+option_code+'"/> </div> ';

        methodd += '<div class="form-group"><label>Active </label> <select class="form-control" name="edit_active" id="edit_active" > <option value=""> -SELECT DATA- </option> <option value="1"> YES </option> <option value="0"> NO </option> </select> </div> ';
        
        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="edit_submit" class="btn btn-success" value="Update"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
    
        setTimeout(function(){
            $('#edit_report').val(report_guid).trigger('change').select2();
            $('#edit_retailer').val(customer_guid).trigger('change').select2();
            $('#edit_active').val(isactive).trigger('change');


        },300);

    });//close 

    $(document).on('click','#edit_submit',function(){

        var main_guid = $('#main_guid').val();
        var edit_retailer = $('#edit_retailer').val();
        var edit_report = $('#edit_report').val();
        var edit_active = $('#edit_active').val();
        var edit_report_name = $('#edit_report_name').val();
        var edit_report_description = $('#edit_report_description').val();
        // var edit_report_code = $('#edit_report_code').val();
        
        if((main_guid == '') || (main_guid == null) || (main_guid == 'null'))
        {
            alert('Invalid Process.');
            return;
        }

        if((edit_retailer == '') || (edit_retailer == null) || (edit_retailer == 'null'))
        {
            alert('Please Select Retailer Name.');
            return;
        }
        
        if((edit_report == '') || (edit_report == null) || (edit_report == 'null'))
        {
            alert('Please Select Notification Report.');
            return;
        }

        if((edit_report_name == '') || (edit_report_name == null) || (edit_report_name == 'null'))
        {
            alert('Please insert Notification Name.');
            return;
        }

        if((edit_report_description == '') || (edit_report_description == null) || (edit_report_description == 'null'))
        {
            alert('Please insert Notification Description.');
            return;
        }

        // if((edit_report_code == '') || (edit_report_code == null) || (edit_report_code == 'null'))
        // {
        //     alert('Please insert Notification Code.');
        //     return;
        // }

        if((edit_active == '') || (edit_active == null) || (edit_active == 'null'))
        {
            alert('Please select active status.');
            return;
        }

        confirmation_modal('Are you sure want to Edit?');
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Daily_email_setup/process_notification_edit') ?>",
            method:"POST",
            data:{main_guid:main_guid,edit_retailer:edit_retailer,edit_report:edit_report,edit_report_name:edit_report_name,edit_active:edit_active},
            beforeSend:function(){
                $('.btn').button('loading');
                swal.fire({
                    allowOutsideClick: false,
                    title: 'Processing...',
                    showCancelButton: false,
                    showConfirmButton: false,
                    onOpen: function () {
                    swal.showLoading()
                    }
                });
            },
            success:function(data)
            {
                json = JSON.parse(data);
                if(json.para1 == 'false')
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "error",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    })
                }
                else
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    $("#medium-modal").modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "success",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    }).then(() => {
                    location.reload();
                    });
                }
            }//close success
        });//close ajax 
        });//close document yes click
    });//close 

    $(document).on('click', '#delete_btn', function(){
        var guid = $(this).attr('guid');
        var delete_retailer = $(this).attr('customer_guid');

        confirmation_modal('Are you sure want to Remove Notification Report?');
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Daily_email_setup/remove_notification_report') ?>",
            method:"POST",
            data:{guid:guid,delete_retailer:delete_retailer},
            beforeSend:function(){
            $('.btn').button('loading');
            swal.fire({
                allowOutsideClick: false,
                title: 'Processing...',
                showCancelButton: false,
                showConfirmButton: false,
                onOpen: function () {
                swal.showLoading()
                }
            });
            },
            success:function(data)
            {
            json = JSON.parse(data);
            if(json.para1 == 'false')
            {
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                // alert(json.msg);
                Swal.fire({
                title: json.msg, 
                text: '', 
                type: "error",
                allowOutsideClick: false,
                showConfirmButton: true,
                })
            }
            else
            {
                $('.btn').button('reset');
                $('#alertmodal').modal('hide');
                $("#medium-modal").modal('hide');
                // alert(json.msg);
                Swal.fire({
                title: json.msg, 
                text: '', 
                type: "success",
                allowOutsideClick: false,
                showConfirmButton: true,
                }).then(() => {
                location.reload();
                });
            }
            
            }//close success
            
        });//close ajax 
        });//close document yes click
    });//CLOSE ONCLICK  

    $(document).on('click', '#notification_table tbody tr', function(event){
    
        var xstatus = $('#notification_table').DataTable().rows().data().any();
        var rep_option_guid = $(this).attr('rep_option_guid');
        var customer_guid = $(this).attr('customer_guid');
        var report_guid = $(this).attr('report_guid');

        if((xstatus == false) || (xstatus != true)){
        return;
        }

        if(event.target.tagName == "I" || event.target.tagName == "BUTTON" || event.target.tagName == "INPUT") {
        return;
        }

        if((rep_option_guid == '') || (rep_option_guid == null) || (rep_option_guid == 'null'))
        {
            alert('Invalid Notification Report');
            return;
        }

        if((customer_guid == '') || (customer_guid == null) || (customer_guid == 'null'))
        {
            alert('Invalid Retailer');
            return;
        }

        if((report_guid == '') || (report_guid == null) || (report_guid == 'null'))
        {
            alert('Invalid Report');
            return;
        }

        //child_table(debtor_code);

        child_table(rep_option_guid);
        $('#btn_append').html('<button id="create_child_btn" type="button" class="btn btn-xs btn-primary" v_main_guid="'+rep_option_guid+'" v_retailer="'+customer_guid+'" v_report_guid="'+report_guid+'" ><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Create </button> <button id="delete_child_btn" type="button" class="btn btn-xs btn-danger" v_main_guid="'+rep_option_guid+'" v_retailer="'+customer_guid+'" ><i class="fa fa-trash" aria-hidden="true"></i> Delete </button>');

        var id = $(this).closest('table').attr('id');

        var table = $('#'+id).DataTable();

        table.rows('.active').nodes().to$().removeClass("active");

        $(this).closest('table').find('tr').removeClass("active");
        $(this).addClass('active');

    });//close mouse click

    child_table = function(rep_option_guid)
    { 
        $.ajax({
            url : "<?php echo site_url('Daily_email_setup/notification_user_tb');?>",
            method: "POST",
            data:{rep_option_guid:rep_option_guid},
            beforeSend : function() {
                swal.fire({
                    allowOutsideClick: false,
                    title: 'Processing...',
                    showCancelButton: false,
                    showConfirmButton: false,
                    onOpen: function () {
                    swal.showLoading()
                    }
                });
            },
            complete: function() {
                setTimeout(function() {
                    Swal.close();
                }, 300);
            },
            success : function(data)
            {  
                json = JSON.parse(data);

                if ($.fn.DataTable.isDataTable('#list_table_child')) {
                    $('#list_table_child').DataTable().destroy();
                }

                $('#list_table_child').DataTable({
                    "columnDefs": [
                    {"targets": [0] ,"orderable": false},
                    ],
                    'processing'  : true,
                    'paging'      : true,
                    'lengthChange': true,
                    'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
                    'searching'   : true,
                    'ordering'    : true,
                    'order'       : [[5 , 'desc']],
                    'info'        : true,
                    'autoWidth'   : false,
                    "bPaginate": true, 
                    "bFilter": true, 
                    "sScrollY": "50vh", 
                    "sScrollX": "100%", 
                    "sScrollXInner": "100%", 
                    "bScrollCollapse": true,
                    data: json['data'],
                    columns: [
                            { data: "rep_option_guid_c", render: function(data, type, row){ 
                                var element = '';

                                element += '<input type="checkbox" id="flag_checkbox" class="form-checkbox" rep_option_guid_c="'+row['rep_option_guid_c']+'" customer_guid="'+row['customer_guid']+'" user_guid="'+row['user_guid']+'"/>';   

                                return element;
                            }},
                            // { data: "rep_option_guid_c", render: function(data, type, row){ 
                            //     var element = '';

                            //     element += '<button id="edit_btn" style="margin-left:5px;" title="Edit" class="btn btn-xs btn-info" guid="'+row['rep_option_guid']+'" customer_guid="'+row['customer_guid']+'" rep_option_guid_c="'+row['rep_option_guid_c']+'"><i class="fa fa-pencil"></i></button>';

                            //     return element;
                            // }},
                            { "data" : "acc_name" },
                            { "data" : "user_id", render: function(data, type, row){ 
                                var element = '';

                                element = data + '<span id="preview_user_data" style="float:right;" customer_guid="'+row['customer_guid']+'" user_guid="'+row['user_guid']+'"> <i class="fa fa-info-circle"></i></span>';

                                return element;
                            }},
                            { "data" : "user_name"},
                            { "data" : "isactive", render: function(data, type, row){ 
                                var element = '';

                                if(data == '1')
                                {
                                    element = 'Yes';
                                }
                                else
                                {
                                    element = 'No';
                                }

                                return element;
                            }},
                            { "data" : "created_at" },
                            { "data" : "created_by" },
                            // { "data" : "updated_at" },
                            // { "data" : "updated_by" },
                            ],
                    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
                    "language": {
                        "lengthMenu": "Show _MENU_",
                        "infoEmpty": "No records available",
                        "infoFiltered": "(filtered from _MAX_ total records)",
                        "zeroRecords": "<span><?php echo '<b>No Record Found.</b>'; ?></span>",
                    }, 
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                        // $(nRow).attr('status', aData['status']);
                    },
                    "initComplete": function( settings, json ) {
                        interval();
                    },
                });//close datatable
            }//close success
        });//close ajax
    }//close child table

    $(document).on('click','#create_child_btn',function(){
        var add_retailer_guid = $(this).attr('v_retailer');
        var add_guid = $(this).attr('v_main_guid');
        var add_report_guid = $(this).attr('v_report_guid');

        var modal = $("#medium-modal").modal();

        modal.find('.modal-title').html('Create Notification User');

        methodd = '';

        methodd +='<div class="col-md-12">';

        methodd += '<p class="notes_css"><span style="font-size:16px;font-weight:bold;"> Selected User : <span id="append_hint" style="font-size:16px;font-weight:bold;"></span></span></p>';

        methodd += '<input type="hidden" class="form-control input-sm" id="add_guid" name="add_guid" value="'+add_guid+'" autocomplete="off"/>';

        methodd += '<div class="form-group"><label>Notification Report </label> <select class="form-control select2" name="add_report" id="add_report" disabled> <option value=""> -SELECT DATA- </option><?php foreach($get_table as $row) { ?> <option value="<?php echo $row->guid?>"><?php echo addslashes($row->log_table)?>  </option> <?php } ?></select> </div> ';

        methodd += '<div class="form-group"><label>Retailer Name </label> <select class="form-control select2" name="add_retailer" id="add_retailer" disabled> <option value=""> -SELECT DATA- </option><?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name)?>  </option> <?php } ?></select> </div> ';

        methodd += '<div class="form-group"><label>Supplier Name </label> <select class="form-control select2 selection_data" id="add_supplier" name="add_supplier"> </select> </div> ';

        methodd += '<div class="form-group"><label>User Group </label> <select class="form-control select2 selection_data" id="add_user_group" name="add_user_group"> <option value=""> -SELECT DATA- </option><?php foreach($get_user_group as $row) { ?> <option value="<?php echo $row->user_group_guid?>"><?php echo addslashes($row->user_group_name)?> </option> <?php } ?></select> </select> </div> ';

        methodd += '<div class="form-group"><label>User List</label> <button id="user_list_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> <button id="user_list_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button><select class="form-control select2" id="add_user" name="add_user"  multiple="multiple"> </div>';

        methodd += '';
        
        methodd += '</div>';

        methodd_footer = '<p class="full-width"><span class="pull-right"><input type="button" id="create_child_submit" class="btn btn-success" value="Create"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

        modal.find('.modal-footer').html(methodd_footer);
        modal.find('.modal-body').html(methodd);
    
        setTimeout(function(){
            $('#add_retailer').val(add_retailer_guid).trigger('change');
            $('#add_report').val(add_report_guid).trigger('change');
            $('#add_user').select2();
            $('#add_supplier').select2();
            $('#add_user_group').select2();

            if(add_retailer_guid != '')
            {
                $.ajax({
                    url : "<?php echo site_url('Daily_email_setup/fetch_supplier'); ?>",
                    method:"POST",
                    data:{add_retailer_guid:add_retailer_guid},
                    success:function(result)
                    {
                        json = JSON.parse(result); 

                        data_supplier = '';

                        data_supplier += '<option value="" selected> -SELECT DATA- </option>';

                        Object.keys(json['query_supplier_data']).forEach(function(key) {

                            data_supplier += '<option value="'+json['query_supplier_data'][key]['supplier_guid']+'">'+json['query_supplier_data'][key]['supplier_name']+' </option>';

                        });

                        $('#add_supplier').select2().html(data_supplier);
                    }
                });

            }
            else
            {
                $('#add_supplier').select2().html('<option value=""> -SELECT DATA- </option>');
            }

            $('.selection_data').change(function(){

                $('#append_hint').html('0');

                if(add_retailer_guid != '')
                {
                    var add_supplier_guid = $('#add_supplier').val();
                    var add_user_group = $('#add_user_group').val();

                    $.ajax({
                        url : "<?php echo site_url('Daily_email_setup/fetch_user'); ?>",
                        method:"POST",
                        data:{add_retailer_guid:add_retailer_guid,add_supplier_guid:add_supplier_guid,add_user_group:add_user_group},
                        success:function(result)
                        {
                            json = JSON.parse(result); 

                            data = '';

                            Object.keys(json['query_data']).forEach(function(key) {

                                data += '<option value="'+json['query_data'][key]['user_guid']+'">'+json['query_data'][key]['user_name']+' - '+json['query_data'][key]['user_id']+'</option>';

                            });

                            $('#add_user').select2().html(data);
                        }
                    });

                    $('#add_user').on('change', function() {
                        var selectedOptions = $(this).val();
                        var selectedCount = selectedOptions ? selectedOptions.length : 0;
                        $('#append_hint').html(selectedCount);
                    });

                }
                else
                {
                    $('#add_user').select2().html('<option value=""> -SELECT DATA- </option>');
                }
            });

            
        },300);

    });//close 
    
    $(document).on('click','#create_child_submit',function(){
        var main_guid = $('#add_guid').val();
        var add_retailer = $('#add_retailer').val();
        var add_user = $('#add_user').val();

        if((main_guid == '') || (main_guid == null) || (main_guid == 'null'))
        {
            alert('Invalid process.');
            return;
        }

        if((add_retailer == '') || (add_retailer == null) || (add_retailer == 'null'))
        {
            alert('Invalid Process.');
            return;
        }
    
        if((add_user == '') || (add_user == null) || (add_user == 'null'))
        {
            alert('Please select User Name.');
            return;
        }

        confirmation_modal('Are you sure want to Create?');
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Daily_email_setup/process_user_create') ?>",
            method:"POST",
            data:{main_guid:main_guid,add_user:add_user,add_retailer:add_retailer},
            beforeSend:function(){
                $('.btn').button('loading');
                swal.fire({
                    allowOutsideClick: false,
                    title: 'Processing...',
                    showCancelButton: false,
                    showConfirmButton: false,
                    onOpen: function () {
                    swal.showLoading()
                    }
                });
            },
            success:function(data)
            {
                json = JSON.parse(data);
                if(json.para1 == 'false')
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "error",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    })
                }
                else
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    $("#medium-modal").modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "success",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    }).then(() => {
                        $('body').addClass('no-padding-right');
                        child_table(main_guid);
                    });
                }
            }//close success
        });//close ajax 
        });//close document yes click
    });//close 

    $(document).on('click', '#user_list_all', function(){
        $("#add_user option").prop('selected',true);

        var selectedOptions = $('#add_user').val();
        var selectedCount = selectedOptions ? selectedOptions.length : 0;
        $('#append_hint').html(selectedCount);

        $(".select2").select2();
    });//CLOSE ONCLICK  

    $(document).on('click', '#user_list_all_dis', function(){
        $("#add_user option").prop('selected',false);

        var selectedOptions = $('#add_user').val();
        var selectedCount = selectedOptions ? selectedOptions.length : 0;
        $('#append_hint').html(selectedCount);

        $(".select2").select2();
    });//CLOSE ONCLICK 

    $(document).on('click', '#retailer_list_all', function(){
        $("#noti_retailer option").prop('selected',true);
        var selectedOptions = $('#noti_retailer').val();
        $(".select2").select2();
    });//CLOSE ONCLICK  

    $(document).on('click', '#retailer_list_all_dis', function(){
        $("#noti_retailer option").prop('selected',false);
        var selectedOptions = $('#noti_retailer').val();
        $(".select2").select2();
    });//CLOSE ONCLICK 

    $(document).on('click', '#report_list_all', function(){
        $("#r_noti_report option").prop('selected',true);
        var selectedOptions = $('#r_noti_report').val();
        $(".select2").select2();
    });//CLOSE ONCLICK  

    $(document).on('click', '#report_list_all_dis', function(){
        $("#r_noti_report option").prop('selected',false);
        var selectedOptions = $('#r_noti_report').val();
        $(".select2").select2();
    });//CLOSE ONCLICK 
    
    $(document).on('change','#checkall_input_table',function(){

        var id = $(this).attr('table_id');

        var table = $('#'+id).DataTable();

        if($(this).is(':checked'))
        {
            table.rows().nodes().to$().each(function(){

                $(this).find('td').find('#flag_checkbox').prop('checked',true)

            });//close small loop
        }
        else
        {
            table.rows().nodes().to$().each(function(){

                $(this).find('td').find('#flag_checkbox').prop('checked',false)

            });//close small loop
        }//close else

    });//close checkbox

    $(document).on('click', '#delete_child_btn', function(){
        var table = $('#list_table_child').DataTable();
        var main_guid = $(this).attr('v_main_guid');
        // alert(main_guid); die;
        var details = [];

        table.rows().nodes().to$().each(function(){
        if($(this).find('td').find('#flag_checkbox').is(':checked'))
        {
            d_guid = $(this).find('td').find('#flag_checkbox').attr('rep_option_guid_c');
            d_customer_guid = $(this).find('td').find('#flag_checkbox').attr('customer_guid');
            d_user_guid = $(this).find('td').find('#flag_checkbox').attr('user_guid');
            
            if(d_guid == '' || d_guid == 'null' || d_guid == null)
            {
                alert('Invalid Process Error 1.');
                return;
            }

            if(d_customer_guid == '' || d_customer_guid == 'null' || d_customer_guid == null)
            {
                alert('Invalid Process Error 2.');
                return;
            }

            if(d_user_guid == '' || d_user_guid == 'null' || d_user_guid == null)
            {
                alert('Invalid Process Error 3.');
                return;
            }
    
            details.push({'d_guid':d_guid,'d_customer_guid':d_customer_guid,'d_user_guid':d_user_guid});

            // code.push({'d_code':d_code});
        }
        });//close small loop

        var count_selected = details.length;

        if(details == '' || details == 'null' || details == null)
        {
            alert('Please Select Checkbox.');
            return;
        }

        confirmation_modal('Are you sure want to Remove Notification User? <br> <b> Count : '+count_selected+'</b>');
        $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
        $.ajax({
            url:"<?php echo site_url('Daily_email_setup/remove_user_create') ?>",
            method:"POST",
            data:{details:details},
            beforeSend:function(){
                $('.btn').button('loading');
                swal.fire({
                    allowOutsideClick: false,
                    title: 'Processing...',
                    showCancelButton: false,
                    showConfirmButton: false,
                    onOpen: function () {
                    swal.showLoading()
                    }
                });
            },
            success:function(data)
            {
                json = JSON.parse(data);
                if(json.para1 == 'false')
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "error",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    })
                }
                else
                {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    $("#medium-modal").modal('hide');
                    // alert(json.msg);
                    Swal.fire({
                    title: json.msg, 
                    text: '', 
                    type: "success",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    }).then(() => {
                        $('body').addClass('no-padding-right');
                        child_table(main_guid);
                    });
                }
            }//close success
            
        });//close ajax 
        });//close document yes click
    });//CLOSE ONCLICK  

    $(document).on('click','#preview_user_data',function(){
        var user_guid = $(this).attr('user_guid');
        var customer_guid = $(this).attr('customer_guid');

        $.ajax({
            url:"<?php echo site_url('Daily_email_setup/notification_user_info') ?>",
            method:"POST",
            data:{user_guid:user_guid,customer_guid:customer_guid},
            beforeSend:function(){
                $('.btn').button('loading');
            },
            success:function(data)
            {
                json = JSON.parse(data);
                
                var modal = $("#large-modal").modal();

                modal.find('.modal-title').html('User Information : <b>'+json.data_user_id+'</b>');

                methodd = '';

                methodd +='<div class="row"> <div class="col-md-12"> <table id="user_info" class="table table-bordered table-hover " width="100%" cellspacing="0"> <thead style="white-space: nowrap;"> <tr> <th>Supplier Name</th> <th>User Group</th> </tr> </thead> </table>  </div> </div> </div>';

                methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="close_btn" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

                modal.find('.modal-footer').html(methodd_footer);
                modal.find('.modal-body').html(methodd);
            
                setTimeout(function(){

                if ($.fn.DataTable.isDataTable('#user_info')) {
                    $('#user_info').DataTable().destroy();
                }

                $('#user_info').DataTable({
                    "columnDefs": [
                    ],
                    'processing'  : true,
                    'paging'      : true,
                    'lengthChange': true,
                    'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
                    'searching'   : true,
                    'ordering'    : true,
                    'order'       : [ [1 , 'asc'] ],
                    'info'        : true,
                    'autoWidth'   : true,
                    "bPaginate": true, 
                    "bFilter": true, 
                    "sScrollY": "50vh", 
                    "sScrollX": "100%", 
                    "sScrollXInner": "100%", 
                    "bScrollCollapse": true,
                    data: json['data'],
                    columns: [
                        { "data": "supplier_name"},
                        { "data": "user_group_name"},
                        ],
                    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip", 
                    "language": {
                    "lengthMenu": "Show _MENU_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "zeroRecords": "<span><?php echo '<b>No Record Found.</b>'; ?></span>",
                    }, 
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                    // $(nRow).attr('status', aData['status']);
                    },
                    "initComplete": function( settings, json ) {
                    setTimeout(function(){
                        interval();
                    },300);
                    }
                });//close datatable

                },300);
                
                $('.btn').button('reset');
            }//close success
        });//close ajax 
    });
    
})
</script>
