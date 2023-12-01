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

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    color: black;
}
</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <!-- head -->
        <div class="box-header with-border">
          <h3 class="box-title">Filter By</h3><br>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- head -->
        <!-- body -->
        <div class="box-body">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2"><b>Retailer Name</b></div>
              <div class="col-md-4">
              <select class="form-control select2" name="retailer_option" id="retailer_option">
                <option value="">-Select-</option>
                <?php foreach($get_acc as $row)
                {
                  ?>
                  <option value="<?php echo $row->acc_guid?>"><?php echo $row->acc_name?></option>
                  <?php
                }
                ?>
              </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Supplier Name</b></div>
              <div class="col-md-4">
              <select class="form-control select2" name="supplier_option" id="supplier_option">
                <option value="">-Select-</option>
                <?php foreach($get_supplier as $row)
                {
                  ?>
                  <option value="<?php echo $row->supplier_guid?>"><?php echo $row->supplier_name?></option>
                  <?php
                }
                ?>
              </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>User Name</b></div>
              <div class="col-md-4">
              <select class="form-control select2" name="user_option" id="user_option" multiple="multiple">
                <option value="" disabled>-Select Retailer & Supplier-</option>
              </select>
              <button id="location_all_dis" class="btn btn-xs btn-danger" type="button" style="float: right;margin-left:5px;margin-top:5px;margin-bottom:5px;" >X</button> 
              <button id="location_all" class="btn btn-xs btn-info" type="button" style="float: right;margin-top:5px;margin-bottom:5px;">ALL</button>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                <button type="button" id="search_data" class="btn btn-primary" ><i class="fa fa-search"></i> Search </button>
                <button type="button" id="reset_data" class="btn btn-danger" ><i class="fa fa-refresh"></i> Reset </button>
              </div>

            </div>
          </div>
        </div>
        <!-- body -->
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Log Table </h3>
          <div class="box-tools pull-right">

          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
            <table id="table1" class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead > <!--style="white-space: nowrap;"-->
                <tr>
                    <th>Action</th>
                    <th>Retailer Name</th>
                    <th>Supplier Name</th>
                    <th>User ID</th>
                    <th>Phone No.</th>
                    <th>Action Web</th>
                    <th>Action Apps</th>

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
$(document).ready(function() {

  $('#table1').DataTable({
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
                "zeroRecords": "<?php echo '<b>No Record Found.</b>'; ?>",
      },
      "pagingType": "simple_numbers",
  });
  $('.remove_padding_right').css({'text-align':'left'});
  $("div.remove_padding").css({"text-align":"left"});

  $(document).on('click','#reset_data',function(){
    
    $('#retailer_option').val('').trigger('change');
    $('#supplier_option').val('').trigger('change');
    $('#user_option').val('').trigger('change');
    $('#date_from').val('').trigger('change');
    $('#date_to').val('').trigger('change');

  });//close search button

  $(document).on('click','#reset_date',function(){
    
    $('#date_from').val('').trigger('change');
    $('#date_to').val('').trigger('change');

  });//close search button

  $(document).on('change','#supplier_option',function(){
    
    supplier_data = $('#supplier_option').val();
    retailer_data =  $('#retailer_option').val();

    if(supplier_data != '' && retailer_data != '')
    {
      $.ajax({
        url : "<?php echo site_url('Portal_logs/fetch_reg_user'); ?>",
        method:"POST",
        data:{supplier_data:supplier_data,retailer_data:retailer_data},
        success:function(result)
        {
          json = JSON.parse(result); 

          vendor = '';

          Object.keys(json['fetch_user']).forEach(function(key) {

            vendor += '<option value="'+json['fetch_user'][key]['ven_email']+'">'+json['fetch_user'][key]['ven_name']+'</option>';

          });
          $('#user_option').select2().html(vendor);
        }
      });
    }
    else
    {
      $('#user_option').select2().html('<option value="" disabled>Please select the supplier & retailer</option>');
    }

    // alert(retailer_data);die;

  });//close user filter button
  
  $(document).on('click','#search_data',function(){

    retailer_option = $('#retailer_option').val();
    supplier_option = $('#supplier_option').val();
    user_option = $('#user_option').val();

    if(retailer_option == '')
    {
        alert('Please Select Retailer');
        return;
    }
    
    user_action_table(retailer_option,supplier_option,user_option);

  });//close search button

  user_action_table = function(retailer_option,supplier_option,user_option)
  { 
    $.ajax({
      url : "<?php echo site_url('Portal_logs/user_action_tb');?>",
      method: "POST",
      data:{retailer_option:retailer_option,supplier_option:supplier_option,user_option:user_option},
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
        $('.btn').button('loading');
      },
      complete: function() {
        $('.btn').button('reset');
        setTimeout(function() {
            Swal.close();
        }, 300);
      },
      success : function(data)
      {  
        json = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#table1')) {
            $('#table1').DataTable().destroy();
        }

        $('#table1').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        // { className: "alignright", targets: [6] },
        // { className: "alignleft", targets: '_all' },
        ],
        'processing'  : true,
        'paging'      : true,
        'lengthChange': true,
        'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
        'searching'   : true,
        'ordering'    : true,
        'order'       : [ [2 , 'asc'] ],
        'info'        : true,
        'autoWidth'   : false,
        "bPaginate": true, 
        "bFilter": true, 
        "sScrollY": "40vh", 
        "sScrollX": "100%", 
        "sScrollXInner": "100%", 
        "bScrollCollapse": true,
          data: json['query_data'],
          columns: [
                    { "data": "ven_email" , render: function(data, type, row){ 
                        var element = '';
                        var element1 = row['status_web'];
                        var element2 = row['status_apps'];

                        if(element1 != 'Access' && element2 != 'Access')
                        {
                            element += '<button id="send_data" type="button"  title="SEND" class="btn btn-xs btn-warning" ven_email="'+row['ven_email']+'" customer_guid="'+row['customer_guid']+'" supplier_guid="'+row['supplier_guid']+'" status_web = "'+row['status_web']+'" status_apps = "'+row['status_apps']+'"><i class="fa fa-send"></i></button>';
                        }

                        return element;
                    }},
                    { "data": "acc_name" },
                    { "data": "comp_name" },
                    { "data": "ven_email" },
                    { "data": "ven_phone" },
                    { "data": "status_web" },
                    { "data": "status_apps" },

                   ],
          dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",  
          "language": {
            "lengthMenu": "Show _MENU_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "zeroRecords": "<?php echo '<b>No Record Found.</b>'; ?>",
          },
         "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).closest('tr').css({"cursor": "pointer"});
            //$(nRow).attr('refno_val', aData['refno_val']);
            // $(nRow).attr('status', aData['status']);
        },
        "initComplete": function( settings, json ) {
          interval();
        }
        });//close datatable
      }//close success
    });//close ajax
  }//close proposed batch table

  $(document).on('click','#send_data',function(){
    
    customer_guid = $(this).attr('customer_guid');
    supplier_guid = $(this).attr('supplier_guid');
    email_address = $(this).attr('ven_email');
    status_web = $(this).attr('status_web');
    status_apps = $(this).attr('status_apps');

    if(customer_guid == '' || customer_guid == null || customer_guid == 'null')
    {
        alert('Invalid Retailer GUID');
        return;
    }

    if(supplier_guid == '' || supplier_guid == null || supplier_guid == 'null')
    {
        alert('Invalid Supplier GUID');
        return;
    }

    if(email_address == '' || email_address == null || email_address == 'null')
    {
        alert('Invalid Email Address');
        return;
    }

    if(status_web == 'Not login web' && status_apps == 'Not login apps')
    {
        mail_type = 'both';
    }
    else if(status_web == 'Not login web')
    {
        mail_type = 'web';
    }
    else if(status_apps == 'Not login apps')
    {
        mail_type = 'apps'
    }
    alert('OPPS!! NOT YET READY YO'); die;
    confirmation_modal('Are you sure to proceed Send?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
    $.ajax({
        url:"<?php echo site_url('Portal_logs/send_reminder') ?>",
        method:"POST",
        data:{customer_guid:customer_guid,supplier_guid:supplier_guid,email_address:email_address,mail_type:mail_type},
        beforeSend:function(){
        $('.btn').button('loading');
        },
        success:function(data)
        {
        json = JSON.parse(data);
        if (json.para1 == '1') {
            $('#alertmodal').modal('hide');
            alert(json.msg);
            $('.btn').button('reset');
        }else{
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
            location.reload();
        }//close else
        }//close success
    });//close ajax 
    });//close confirmation

  });//close search button

  $(document).on('click', '#location_all', function(){
    // alert();
    $("#user_option option").prop('selected',true);
    $(".select2").select2();
    die;
  });//CLOSE ONCLICK  

  $(document).on('click', '#location_all_dis', function(){
    // alert();
    $("#user_option option").prop('selected',false);
    $(".select2").select2();
    die;
  });//CLOSE ONCLICK 
 
});
</script>
