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

.disabled{
    pointer-events:none;
    opacity:0.7;
}

.css_tab{
  background-color: #abe4f5 !important;
  font-weight: bold;
}

.edi_header {
    margin: 0px 0 10px 0;
    font-size: 22px;
    border-bottom: 1px solid #eee;
}

.li_hover:hover{
  font-weight: bold;
}
</style>
<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <h2 class="edi_header">Setup EDI Subscriber</h2>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if($status == '1')
          {
            ?>
            <li class="active"><a class="css_tab" href="#tab_1" >Information</a></li>
            <li class="li_hover"><a href="<?php echo $tab_2;?>" style="color:black" >Column Setting</a></li>
            <li class="li_hover"><a href="<?php echo $tab_3;?>" style="color:black">Method & Format</a></li>
            <li class="li_hover"><a href="<?php echo $tab_summary;?>" style="color:black">Summary</a></li>
            <?php
          }
          else
          {
            ?>
            <li class="active"><a class="css_tab" href="#tab_1" data-toggle="tab" aria-expanded="true">Information</a></li>
            <li class="disabled"><a href="#tab_2" data-toggle="tab" aria-expanded="false" >Column Setting</a></li>
            <li class="disabled"><a href="#tab_3" data-toggle="tab" aria-expanded="false">Method & Format</a></li>
            <li class="disabled"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Summary</a></li>
            <?php
          }
          ?>
        </ul>
        <div class="tab-content" >
          <div class="tab-pane active" id="tab_1">
            <div class="box-body">
            <div class="box box-primary">
            <table class="table table-bordered table-striped dataTable" id="tab_one_table" style="width: 100%;">
              <thead style="white-space: nowrap;">
                <tr>
                  <!-- <th></th> -->
                  <th>Action</th>
                  <th>Retailer Name</th> 
                  <th>Supplier Name</th>
                  <th>Supplier Code</th>
                  <th>Doc Type</th>
                  <th>Reg NO</th>
                  <th>Acc Code</th>
                  <th>EDI Status</th>
                  <th>Created At</th>
                  <th>Created By</th>
                  <th>Updated At</th>
                  <th>Updated By</th>
                </tr>
              </thead>
              <tbody> 
              </tbody>

            </table>
          </div>
          </div>
          <br/>
          <div class="box-footer">

            <button id="next_edi" type="button" class="btn btn-primary"  style="float:right;"><i class="fa fa-arrow-circle-right"></i> Next</button>
       
          </div>
          
          </div>
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- nav-tabs-custom -->
    </div>
  </div>

</div>
</div>

<script>
$(document).ready(function () {    
  var tab_guid = '<?php echo $link ?>';

  $('#tab_one_table').DataTable({
    //"responsive": true,
    "columnDefs": [
    {"targets": 0 ,"orderable": false},
    ],
    "serverSide": true, 
    'processing'  : false,
    'paging'      : true,
    'lengthChange': false,
    //'lengthMenu'  : [ [10, 25, 50, 9999999], [10, 25, 50, 'All'] ],
    'searching'   : false,
    'ordering'    : false, 
    //'order'       : [  [6 , 'desc'] ],
    'info'        : true,
    'autoWidth'   : true,
    //"sScrollY": "50vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Edi_setup/tab_one_tb');?>",
        "type": "POST",
        data : {tab_guid:tab_guid},
    },
    columns: [
            // {
            //   'className':      'details_control',
            //   'orderable':      false,
            //   'data':           null,
            //   'defaultContent': '<button id="expand_btn" type="button" title="expand" class="btn btn-xs btn-success" ><span id="append_icon"><i class="fa fa-plus-circle"></i></span></button>'
            // },
            { "data" : "guid" ,render:function( data, type, row ){

              var element = '';

              <?php if($_SESSION['user_group_name'] == "SUPER_ADMIN" || $_SESSION['user_group_name'] == 'CUSTOMER_ADMIN_TESTING_USE')
              {
                ?>
                  
                element += '<button id="edit_btn" type="button"  title="REMOVE" class="btn btn-xs btn-info" guid="'+row['guid']+'" supplier_guid="'+row['supplier_guid']+'" customer_guid="'+row['customer_guid']+'" supplier_code="'+row['supplier_code']+'" doc_type="'+row['doc_type']+'"><i class="fa fa-edit"></i></button>';

                <?php
              }
              ?>

              return element;
            }},
            { "data" : "acc_name" },
            { "data" : "supplier_name" },
            { "data" : "supplier_code" },
            { "data" : "doc_type" },
            { "data" : "reg_no" },
            { "data" : "acc_code" },
            { "data" : "edi_status" },
            { "data" : "created_at" },
            { "data" : "created_name" },
            { "data" : "updated_at" },
            { "data" : "updated_name" },
          ],
    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rt',
    // buttons: [
    //   { extend: 'excelHtml5',
    //     exportOptions: {columns: [1,2,3,4,5,6]}},

    //   { extend: 'csvHtml5',  
    //     exportOptions: {columns: [1,2,3,4,5,6]}},
    //     ],
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('final_amount', aData['final_amount']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click','#edit_btn',function(){

    var guid = $(this).attr('guid');
    var customer_guid = $(this).attr('customer_guid');
    var supplier_guid = $(this).attr('supplier_guid');
    var supplier_code = $(this).attr('supplier_code');
    var doc_type = $(this).attr('doc_type');

    var modal = $("#medium-modal").modal();

    modal.find('.modal-title').html('Edit EDI Subscriber');

    methodd = '';

    methodd += '<div class="col-md-12">';

    methodd += '<div class="col-md-12"><label>Retailer Name</label> <select class="form-control select2 get_code" name="edit_retailer" id="edit_retailer"> <option value="" disabled selected>-Select Retailer Name-</option>  <?php foreach($get_acc as $row) { ?> <option value="<?php echo $row->acc_guid?>"><?php echo addslashes($row->acc_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Name</label> <select class="form-control select2 get_code" name="edit_supplier" id="edit_supplier"> <option value="" disabled selected>-Select Supplier Name-</option>  <?php foreach($get_supplier as $row) { ?> <option value="<?php echo $row->supplier_guid?>"><?php echo addslashes($row->supplier_name) ?></option> <?php } ?></select> </div>';

    methodd += '<div class="col-md-12"><label>Supplier Code</label> <select class="form-control select2" name="edit_code" id="edit_code"> <option value="" disabled selected>-Select Retailer & Supplier Name-</option>  </select> </div>';

    methodd += '<div class="col-md-12"><label>Doc Type</label> <select class="form-control select2" name="edit_doctype" id="edit_doctype"> <option value="" disabled selected>-Select Document Type-</option> <option value="PO">Purchase Order (PO)</option> <option value="GRN">Goods Received Note (GRN)</option> <option value="GRDA">Goods Received Diff Advice (GRDA)</option> <option value="PRDN">Purchase Return DN (PRDN)</option> <option value="PRCN"> Purchase Return CN (PRCN)</option> <option value="PDN">Purchase DN (PDN)</option> <option value="PCN">Purchase CN (PCN)</option> <option value="PCI">Promotion Claim Tax Invoice (PCI)</option> <option value="DI">Display Incentive (DI)</option> </select> </div>';

    methodd += '</div>';

    methodd_footer = '<p class="full-width"><span class="pull-left"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"></span><span class="pull-right"><button type="button" id="submit_button" class="btn btn-primary"> Update </button></span></p>';

    modal.find('.modal-footer').html(methodd_footer);
    modal.find('.modal-body').html(methodd);

    setTimeout(function(){
      $('#edit_retailer').val(customer_guid);
      $('#edit_supplier').val(supplier_guid);
      $('#edit_doctype').val(doc_type);
      $('.select2').select2();

      if((customer_guid != '') && (supplier_guid != ''))
      {
         $.ajax({
         url : "<?php echo site_url('Edi_setup/fetch_sup_code'); ?>",
         method:"POST",
         data:{val_acc:customer_guid,val_sup:supplier_guid},
         success:function(result)
         {
            json = JSON.parse(result); 

            code = '';

            Object.keys(json['Code']).forEach(function(key) {

              code += '<option value="'+json['Code'][key]['supplier_group_name']+'">'+json['Code'][key]['supplier_group_name']+'</option>';

            });
            $('#edit_code').select2().html(code);
         }
        });
      }
      else
      {
        $('#edit_code').select2().html('<option value="" disabled>Please select the retailer and supplier</option>');
      }

      $('.get_code').change(function(){
        var val_acc = $('#edit_retailer').val();
        var val_sup = $('#edit_supplier').val();

        if((val_acc != '') && (val_sup != ''))
        {
           $.ajax({
           url : "<?php echo site_url('Edi_setup/fetch_sup_code'); ?>",
           method:"POST",
           data:{val_acc:val_acc,val_sup:val_sup},
           success:function(result)
           {
              json = JSON.parse(result); 

              code = '';

              Object.keys(json['Code']).forEach(function(key) {

                code += '<option value="'+json['Code'][key]['supplier_group_name']+'">'+json['Code'][key]['supplier_group_name']+'</option>';

              });
              $('#edit_code').select2().html(code);
           }
          });
        }
        else
        {
          $('#edit_code').select2().html('<option value="" disabled>Please select the retailer and supplier</option>');
        }
      });//close selection

      $('#edit_code').val(supplier_code);

    },300);

  });//close modal create

  $(document).on('click','#submit_button',function(){

    var edit_retailer = $('#edit_retailer').val();
    var edit_supplier = $('#edit_supplier').val();
    var edit_code = $('#edit_code').val();
    var edit_doctype = $('#edit_doctype').val();

    if((edit_retailer == '') || (edit_retailer == null) || (edit_retailer == 'null'))
    {
      alert('Please Select Retailer.');
      return;
    }

    if((edit_supplier == '') || (edit_supplier == null) || (edit_supplier == 'null'))
    {
      alert('Please Select Supplier.');
      return;
    }

    if((edit_code == '') || (edit_code == null) || (edit_code == 'null'))
    {
      alert('Please Select Code.');
      return;
    }

    if((edit_doctype == '') || (edit_doctype == null) || (edit_doctype == 'null'))
    {
      alert('Please Select Doc Type.');
      return;
    }

    confirmation_modal('Are you sure want to update?');
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Edi_setup/edit_information') ?>",
        method:"POST",
        data:{edit_retailer:edit_retailer,edit_supplier:edit_supplier,edit_code:edit_code,edit_doctype:edit_doctype,tab_guid:tab_guid},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if(json.para1 == 1)
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            alert(json.msg);
          }
          else
          {
            $('.btn').button('reset');
            $('#alertmodal').modal('hide');
            $("#medium-modal").modal('hide');
            alert(json.msg);
            setTimeout(function() {
            $('#tab_one_table').DataTable().ajax.reload(null, false);
            }, 300); 
          }
         
        }//close success
      });//close ajax 
    });//close document yes click
  });//close modal create

  $(document).on('click','#next_edi',function(){
    window.location = "<?= site_url('Edi_setup/tab_two?link=');?>"+tab_guid;
  });//close modal create

  // $(document).on('click','#expand_btn',function(){
  //   var get_class = $(this).attr('class');

  //   if(get_class == 'btn btn-xs btn-success')
  //   {
  //     $(this).html('<i class="fa fa-minus-square"></i>');
  //     $(this).removeClass('btn btn-xs btn-success');
  //     $(this).addClass('btn btn-xs btn-danger');
  //   }

  //   if(get_class == 'btn btn-xs btn-danger')
  //   {
  //     $(this).html('<i class="fa fa-plus-circle"></i>');
  //     $(this).removeClass('btn btn-xs btn-danger');
  //     $(this).addClass('btn btn-xs btn-success');
  //   }
  // });//close

  // Add event listener for opening and closing details
  // $('#tab_one_table tbody').on('click', 'td.details_control', function(){
  //     var tr = $(this).closest('tr');
  //     var table = $('#tab_one_table').DataTable();
  //     var row = table.row( tr );

  //     if(row.child.isShown()){
  //         // This row is already open - close it
  //         row.child.hide();
  //         tr.removeClass('shown');
  //     } else {
  //         // Open this row
  //         //console.log(expand_data(row.data())); die;
  //         $.ajax({
  //           url:"<?php echo site_url('Edi_setup/expand_data_test') ?>",
  //           method:"POST",
  //           //data:{edit_retailer:edit_retailer,edit_supplier:edit_supplier,edit_code:edit_code,edit_doctype:edit_doctype,tab_guid:tab_guid},
  //           beforeSend: function(){
  //             $('.btn').button('loading');
  //           },
  //           success:function(data)
  //           {
  //             $('.btn').button('reset');

  //             json = JSON.parse(data);

  //             //console.log(json.output); 
              
  //             var element = '';

  //             element += '<div class="col-md-12">'; 

  //             element += '<div class="col-md-3"><label>Output : </label> '+json.output+' </div>';

  //             element += '</div>';

  //             return row.child(element).show();;
              
  //           }//close success
  //         });//close ajax 
          
  //         tr.addClass('shown');
  //     }
  // });

  // function expand_data(d) {
  //   // `d` is the original data object for the row
    
  //   $.ajax({
  //     url:"<?php echo site_url('Edi_setup/expand_data_test') ?>",
  //     method:"POST",
  //     //data:{edit_retailer:edit_retailer,edit_supplier:edit_supplier,edit_code:edit_code,edit_doctype:edit_doctype,tab_guid:tab_guid},
  //     beforeSend: function(){
  //       $('.btn').button('loading');
  //     },
  //     success:function(data)
  //     {
  //       $('.btn').button('reset');

  //       json = JSON.parse(data);

  //       //console.log(json.output); 
        
  //       var element = '';

  //       element += '<div class="col-md-12">'; 

  //       element += '<div class="col-md-3"><label>Output : </label> '+json.output+' </div>';

  //       element += '</div>';

  //       return element;
        
  //     }//close success
  //   });//close ajax 
  // }

  // Handle click on "Expand All" button
  // $('#btn-show-all-children').on('click', function(){
  //       // Enumerate all rows
  //       var table = $('#tab_one_table').DataTable();
  //       table.rows().every(function(){
  //           // If row has details collapsed
  //           if(!this.child.isShown()){
  //               // Open this row
  //               this.child(expand_data(this.data())).show();
  //               $(this.node()).addClass('shown');
  //           }
  //       });
  // });

  // Handle click on "Collapse All" button
  // $('#btn-hide-all-children').on('click', function(){
  //       // Enumerate all rows
  //       var table = $('#tab_one_table').DataTable();
  //       table.rows().every(function(){
  //           // If row has details expanded
  //           if(this.child.isShown()){
  //               // Collapse row details
  //               this.child.hide();
  //               $(this.node()).removeClass('shown');
  //           }
  //       });
  // });

  });
</script>

