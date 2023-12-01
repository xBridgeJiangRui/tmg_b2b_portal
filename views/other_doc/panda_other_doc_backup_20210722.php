<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" >
<div class="container-fluid">
<br>
  <?php
  if($this->session->userdata('message'))
  {
    ?>
    <div class="alert alert-success text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <?php
  if($this->session->userdata('warning'))
  {
    ?>
    <div class="alert alert-danger text-center" style="font-size: 18px">
    <?php echo $this->session->userdata('warning') <> '' ? $this->session->userdata('warning') : ''; ?>
  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button><br>
  </div>
    <?php
  }
  ?>

  <div class="col-md-12">
    <a class="btn btn-app pull-right"  style="color:#000000" action="print" onclick="bulk_print('print')" >
      <i class="fa fa-print"></i>Bulk Print
    </a>
    <a class="btn btn-app pull-right"  style="color:#000000" action="download" onclick="bulk_print('download')" >
      <i class="fa fa-download"></i> Bulk Download
    </a>    
  </div>
  <!-- filter by -->
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
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('panda_other_doc/other_doc_filter?code=').$b2b_doc_code;?>">
              <div class="col-md-2"><b>Ref No</b></div>
              <div class="col-md-4">
                 <input id="other_doc_refno" name="other_doc_refno" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Status</b></div>
              <div class="col-md-4">
                <select name="other_doc_status" class="form-control">
                  <?php foreach($other_doc_filter_drop_down->result() as $row)
                  {
                    if($status == $row->code)
                    {
                      $other_doc_selected = 'selected';
                    }
                    else
                    {
                      $other_doc_selected = ''; 
                    }
                  ?>
                      <option value="<?php echo $row->code;?>" <?php echo $other_doc_selected;?>><?php echo $row->reason;?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Document Generation Time<br>(YYYY-MM-DD)</b></div>
              <div class="col-md-4">
                <input required id="daterange" name="other_doc_datetime" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
              </div>
              <div class="col-md-2">
                <a class="btn btn-danger"  onclick="date_clear()">Clear</a>
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                
                <button type="submit" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>

                <a href="<?php echo site_url('panda_other_doc').'?code='.$b2b_doc_code?>" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                
              </div>
                </form>
            </div>
          </div>
        </div>
        <!-- body -->

      </div>
    </div>
    
  </div>
  <!-- filter by -->

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><b><?php echo $b2b_details->row('description');?></b></h3> &nbsp;

          <br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="other_doc_table" class="table table-bordered table-hover" >
                      <form id="other_doc_table_form" method="post" action="<?php echo site_url('general/prints')?>">
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>Refno</th>
                                <th>Supplier Code</th>
                                <th>Supplier Name</th>
                                <th>Document Generation Time</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th><input type="checkbox" id="check-all"></th>
                                <!--End=Column Header-->
                            </tr>
                        </thead>
                      </form>
            </table>
             
        </div>
    </div>

<!-- Modal -->
<div id="postatusmodal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Check PO Status</h4>
      </div>
      <div class="modal-body">
        <p><input type="text" id="po_refno"  name="po_refno" class="form-control">
        <center><span style="font-weight: bolder;" id="webindex_result"></span></center>  </p>
  <center><span style="font-weight: bolder;" id="po_check_grn_refno_result"></span></center>  </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.reload()">Close</button>
      </div>
    </div>

  </div>
</div>
</div>
        </div>
        
        </div>
    </div>
</div>
</div>
</div>
</div>
<script>  
 $(document).ready(function(){  
  // alert();
  var b2b_doc_code = '<?php echo $b2b_doc_code;?>';

  var refno = '<?php echo $refno;?>';

  var status = '<?php echo $status;?>';

  var start_date = '<?php echo $start_date;?>';
  var end_date = '<?php echo $end_date;?>';

      $('#other_doc_table').DataTable({
          // "columnDefs": [ {"targets": 2 ,"visible": false}],
          "columnDefs": [{ "orderable": false, "targets": [5,6] }],
          "serverSide": true, 
          'processing'  : true,
          'paging'      : true,
          'lengthChange': true,
          'lengthMenu'  : [ [10, 25, 50, 9999999999999999], [10, 25, 50, "ALL"] ],
          'searching'   : true,
          'ordering'    : true,
          'order'       : [ [0 , 'asc'] ],
          'info'        : true,
          'autoWidth'   : false,
          "bPaginate": true, 
          "bFilter": true, 
          // "sScrollY": "30vh", 
          // "sScrollX": "100%", 
          // "sScrollXInner": "100%", 
          "bScrollCollapse": true,
          "ajax": {
              "url": "<?php echo $other_doc_table_url;?>",
              "type": "POST",
              "data" : {b2b_doc_code:b2b_doc_code,refno:refno,status:status,start_date:start_date,end_date:end_date},
          },
          columns: [
                    { "data": "refno" },
                    { "data": "supcode" },
                    { "data": "supname" },
                    { "data": "doctime" },
                    { "data": "status" },
                    { "data": "button" , render:function( data, type, row ){

                    var element = '';
                    <?php
                    if(in_array('HBTN',$_SESSION['module_code']))
                    {
                      ?>
                        element += '';
                      <?php
                    }
                    else
                    {
                      ?>
                      element += data;
                      <?php
                    }
                    ?>
                    return element;

                    }},
                    { "data": "box" , render:function( data, type, row ){

                    var element = '';
                    <?php
                    if(in_array('HBTN',$_SESSION['module_code']))
                    {
                      ?>
                        element += '';
                      <?php
                    }
                    else
                    {
                      ?>
                      element += data;
                      <?php
                    }
                    ?>
                    return element;

                    }},
                   ],
          dom: "<'row'<'col-sm-2'l><'col-sm-4'><'col-sm-6'f>>rtip",
          // "pagingType": "simple",
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('RefNo', aData['refno']);
            // $(nRow).attr('status', aData['status']);
          },
          "initComplete": function( settings, json ) {
            interval();
          }
        });//close datatable
        $('.table').on( 'draw.dt', function () {
  
            var id = $(this).closest('table').attr('id');

            // alert($('#paloi_wrapper').html());

            setTimeout(function(){

              var table = $('#'+id).DataTable();

              // alert($('#paloi_wrapper').find('div.row').html());

              if((table.settings()[0]['_buttons'] == undefined) && (table.settings()[0]['oInit']['buttons'] == undefined))
              {

                if($('#buttons_append').is(':visible'))
                {
                  return;
                }

                checking = 0;

                $('#'+id+'_wrapper').find('div.row').find('div[class*=col-sm]').each(function(){

                  if($(this).html() == '')
                  { 

                    checking = 1;
                    var xclass = $(this).attr('class');

                    var buttons = new $.fn.dataTable.Buttons(table, {
                       buttons: [
                         // 'copyHtml5',
                         // 'excelHtml5',
                          {
                            extend: 'excelHtml5',
                            filename: 'Accounting Document'
                          },
                         // 'csvHtml5',
                         // 'pdfHtml5'
                      ]
                    }).container().appendTo($(this));

                    return;
                  }

                });//close each

                

                if(checking == 1)
                {
                  return;
                }

                $('#'+id+'_wrapper').find('div.row').after('<div id="buttons_append"></div>');

                // $('#paloi_length').append('<div id="buttons_append" style="background-color:red;">asdadsadas</div>');

                var buttons = new $.fn.dataTable.Buttons(table, {
                   buttons: [
                     // 'copyHtml5',
                     // 'excelHtml5',
                      {
                        extend: 'excelHtml5',
                        filename: 'Accounting Document'
                      },
                     // 'csvHtml5',
                     // 'pdfHtml5'
                  ]
                }).container().appendTo($('#buttons_append'));

                

              }


            },300);

        });//close datatable add excel button

  });
</script>
<script>
$(function() {
  $('input[name="other_doc_datetime"]').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    },
  });
  //$('#daterange').data('daterangepicker').setStartDate('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
  //$('#daterange').data('daterangepicker').setEndDate('<?php echo date('Y-m-d') ?>');
  $(this).find('[name="other_doc_datetime"]').val("");
});
</script>

<script type="text/javascript">
  function bulk_print(actions)
  {
    var action = actions;
    // alert(action);return;
    // alert();
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    }); 
    var code = "<?php echo $_REQUEST['code'];?>";
    // alert(list_id); 

    if(list_id.length > 0)
    {
      // alert('>0');
      $.ajax({
      type: "POST",
      data: {id:list_id,code:code,action:action},
      url: "<?php echo site_url('general/acc_doc_merge_pdf')?>",
      dataType: "JSON",
      success: function(data)
      { 
          // alert(data.link_url);
          if(data.link_url)
          {
             // alert(2);
             window.location.href = data.link_url;return;
             var newwin = window.open(data.link_url); 
              newwin.onload = function() {

                setTimeout(function(){

                  var url_link = data.pdf_file;

                  $.ajax({
                          type: "POST",
                          data: {url_link:url_link},
                          url: "<?php echo site_url('general/unlink_file')?>",
                          dataType: "JSON",
                          success: function(data)
                          { 
                            alert('delete success'+data);
                          }//close success
                        });//close ajax

                },1000);
              
              };//close onload
          }
          else
          {
              alert('Failed.');
          }
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error Opening data');
        }
        });      
    }
    else
    {
        alert('no data selected');
    }     
  }

</script>