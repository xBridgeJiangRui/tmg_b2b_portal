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
         <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?> ">
          <i class="fa fa-search"></i> Browse
        </a>
        <a class="btn btn-app" href="<?php echo site_url('login_c/location')?>">
          <i class="fa fa-bank"></i> Outlet
        </a>
        <a class="btn btn-app pull-right"  style="color:#000000"  onclick="bulk_print()" >    
            <i class="fa fa-print"></i> Print   
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
              <form role="form" method="POST" id="myForm" action="<?php echo site_url('general/po_filter');?>">
              <div class="col-md-2"><b>PRDN/CN Ref No</b></div>
              <div class="col-md-4">
                 <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Transaction Type<br></b></div>
              <div class="col-md-4">
                <select name="po_status" class="form-control">
                  <?php foreach($filter_status->result() as $row){ ?>
                    <option value="<?php echo $row->code ?>" 
                      <?php if($_REQUEST['status'] == $row->code)
                      {
                        echo 'selected';
                      } 
                      ?>
                    > 
                    <?php echo $row->reason; ?></option>
                 <?php } ?>
                </select> 
              </div>
              
              <div class="clearfix"></div><br>

              <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
              <div class="col-md-4">
                <select name="period_code" class="form-control">
                  <option value="">None</option>
                  <?php foreach($period_code->result() as $row){ ?>
                    <option value="<?php echo $row->period_code ?>" 
                       <?php if(isset($_SESSION['filter_period_code'])){
                      if($_SESSION['filter_period_code'] == $row->period_code)
                      {
                        echo 'selected';
                      } }
                      ?>
                    > 
                    <?php echo $row->period_code; ?></option>
                 <?php } ?>
                    
                 <!--need remove at 2023-08-17 -->
                 <?php if($_SESSION['customer_guid'] == '599348EDCB2F11EA9A81000C29C6CEB2' )
                  {
                    ?>
                    <option value="2021-12">2021-12</option>
                    <option value="2021-11">2021-11</option>
                    <option value="2021-10">2021-10</option>
                    <option value="2021-09">2021-09</option>
                    <option value="2021-08">2021-08</option>
                    <option value="2021-07">2021-07</option>
                    <option value="2021-06">2021-06</option>
                    <option value="2021-05">2021-05</option>
                    <option value="2021-04">2021-04</option>
                    <option value="2021-03">2021-03</option>
                    <?php
                  }
                  ?>

                </select> 
              </div>
              <div class="clearfix"></div><br>

              <div class="col-md-12">
                <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">
                
                <button type="submit" id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                <!-- an F5 function -->
                <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                 <!-- an RESER function -->
                <a href="<?php echo site_url($_SESSION['frommodule'])?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=" class="btn btn-default" ><i class="fa fa-repeat"></i> Reset</a>
                
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
          <h3 class="box-title"><b>PRDN/CN</b></h3> &nbsp;

          <span class="pill_button"><?php 

          if ($_REQUEST['status'] == '') {
            $status = 'all';
          } else if ($_REQUEST['status'] == 'CN_GENERATED') {
            $status = 'CN GENERATED';
          } else {
            $status = $_REQUEST['status'];
          }


          echo ucfirst($status) ?></span>

          <span class="pill_button"><?php 

          if(in_array($check_loc, $hq_branch_code_array)) {
            echo 'All Outlet';
          } else {

            echo $_REQUEST['loc'];

          } ?>

          </span>

          <?php 

          if(isset($_SESSION['filter_period_code']))
            {

          if ($_SESSION['filter_period_code'] != ''  ) { ?>

          <span class="pill_button"><?php 


          echo $_SESSION['filter_period_code'];  ?>
            

          </span>

          <?php } } ?>

          <?php if ($_REQUEST['r_n'] != '') { ?>

          <span class="pill_button"><?php 


          echo $_REQUEST['r_n'];  ?>
            

          </span>

          <?php } ?>

          <br>
            <!-- <?php echo $title_accno ?> -->
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->
          </div>
        </div>
      <div class="box-body">
      <div class="col-md-12">
        <br>
        <div>
            <div class="row">
                <div class="col-md-12"  style="overflow-x:auto"> 
                    <table id="paloi" class="table table-bordered table-hover" >
                        <thead>
                            <tr>
                            <?php // var_dump($_SESSION); ?>
                                <!--Begin=Column Header-->
                                <th>PRDN/CN Refno</th>
                                <th>STRB Refno</th>
                                <th>Outlet</th>
                                <th>Type</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Doc Date</th>
                                <!-- <th>Doc No</th> edit-->
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Incl Tax</th>
                                <th>Stock Collected</th>
                                <th>Stock Collected By</th>
                                <th>Date Collected</th>                                
                                <th>Status</th>
                                <th>Action</th>
                                <th><input id="check-all" type="checkbox" value=""/></th>
                                <!--End=Column Header-->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
          </div>
             <!-- <p><a href="Panda_home/logout">Logout</a></p> -->
        </div> 
      </div>
    </div>
</div>
</div>
 
<?php  // echo var_dump($_SESSION); ?>
</div>
</div>
  <script type="text/javascript">   
  function bulk_print()   
  {   
    var list_id = [];   
    $(".data-check:checked").each(function() {    
            list_id.push({'id':this.value,'type':$(this).attr('dncn')});    
    });   
     if(list_id.length > 1)   
    {   
      // alert('use merge');   
      var list_id = JSON.stringify(list_id);    
            $.ajax({    
            type: "POST",   
            data: {id:list_id},   
            url: "<?php echo site_url('general/merge_pdf_prdncn?loc='.$_REQUEST['loc'].'&po_type=PO')?>",   
            dataType: "JSON",   
            success: function(data)   
            {     
                // alert(data.link_url);    
                if(data.link_url)   
                {   
                      
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
    else if(list_id.length > 0)   
    {   
        if(confirm('Are you sure open this '+list_id.length+' data?'))    
        {   
            var list_id = JSON.stringify(list_id);    
            $.ajax({    
                type: "POST",   
                data: {id:list_id},   
                url: "<?php echo site_url('general/ajax_bulk_print_prdncn?loc='.$_REQUEST['loc'])?>",   
                dataType: "JSON",   
                success: function(data)   
                {     
                    // alert(data.link_url);    
                    if(data.link_url)   
                    {   
                      data.link_url.forEach(function(element){    
                        window.open(element);     
                      });   
                          
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
    }   
    else    
    {   
        alert('no data selected');    
    }   
  }   
</script>
<script>
$(document).ready(function () {    
  setTimeout(function(){
    $('#large-modal').attr({"data-backdrop":"static","data-keyboard":"false"}); //to remove clicking outside modal for closing
  },300);

  $(document).on('click','#btn_image',function(){
    var refno = $(this).attr('refno');
    var outlet = $(this).attr('outlet');
    var period_code = $(this).attr('period_code');
    var image_type = $(this).attr('image_type');

	  //alert(image_type); die;
    if((refno == '') || (refno == null) || (refno == 'null'))
    {
      alert('Invalid Get STRB RefNo.');
      return;
    }

    if((period_code == '') || (period_code == null) || (period_code == 'null'))
    {
      alert('Invalid Get Period Code.');
      return;
    }

    if((image_type == '') || (image_type == null) || (image_type == 'null'))
    {
      alert('Invalid Get Image Details.');
      return;
    }

    $.ajax({
        url:"<?php echo site_url('Panda_return_collection/strb_view_image') ?>",
        method:"POST",
        data:{refno:refno,period_code:period_code,image_type:image_type,outlet:outlet},
        beforeSend:function(){
          $('.btn').button('loading');
        },
        success:function(data)
        {
          json = JSON.parse(data);
          if (json.para1 == 'false') {
            alert(json.msg);
            $('.btn').button('reset');
          }else{
            $('.btn').button('reset');
            var url = json.file_path_list;
            var name = url.toString().substring(url.lastIndexOf('/') + 1);
            
            var modal = $("#large-modal").modal();

            modal.find('.modal-title').html( 'STRB RefNo : <b>' + refno + '</b>');

            methodd = '';

            //methodd +='<div class="col-md-12">';

            //methodd += '<input type="hidden" class="form-control input-sm" id="supplier_guid" value="'+supplier_guid+'" readonly/>';

            Object.keys(url).forEach(function(key) {

              var before_name = url[key].toString().split('?')[0];
              var after_name = before_name.toString().split("/").slice(-1)[0];
              
              methodd += '<div class="col-md-12"><label>'+after_name+'<span id="alert'+key+'"></span></label>';

              methodd += '<input style="float:right;" type="button" id="show'+key+'" class="btn btn-primary view_image_btn" value="Show" path_url="'+url[key]+'" key="'+key+'" image_name="'+after_name+'">';

              methodd += '<input style="float:right;margin-right:5px;" type="button" id="show'+key+'" class="btn btn-warning dl_image_btn" value="Download" path_url="'+url[key]+'" key="'+key+'">';

              methodd += '</div>';

              methodd += '<div class="clearfix"></div><br>';

              methodd += '<div class="col-md-12"><span id="image'+key+'"></span></div>';

            });

            //methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function(){

              $(document).off('click', '.view_image_btn').on('click', '.view_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var key = $(this).attr('key');
                var image_name = $(this).attr('image_name');
                var value = $(this).val();
                
                if(value == 'Show') 
                {
                  $.ajax({
                    url:"<?php echo site_url('General/strb_show_image_logs');?>",
                    method:"POST",
                    data:{refno:refno,image_name:image_name,},
                    beforeSend:function(){
                      $('.btn').button('loading');
                    },
                    success:function(data)
                    {
                      json = JSON.parse(data);
                      if (json.para1 == 'false') {
                        $('.btn').button('reset');
                        $('#alert'+key+'').html(' - '+json.msg);
                      }else{
                        $('.btn').button('reset');
                        //$(this).attr('value','Hide');
                        //$(this).attr('class','btn btn-danger view_image_btn');
                        $('#image'+key+'').html('<embed src="'+path_url+'" width="100%" height="800px" style="border: none;" toolbar="0" id="image_view'+key+'"/>');
                      }//close else
                    }//close success
                  });//close ajax
                }
                
                // if(value == 'Hide') 
                // {
                //   $(this).attr('value','Show');
                //   $(this).attr('class','btn btn-primary view_image_btn');
                //   $('#image'+key+'').html('');
                //   //alert('show image'); die;
                // }
              });//close modal create

              $(document).off('click', '.dl_image_btn').on('click', '.dl_image_btn', function(){
                var path_url = $(this).attr('path_url');
                var value = $(this).val();
                
                if(value == 'Download') 
                {
                  var form = document.createElement('a');
                  form.href = path_url;
                  form.download = path_url;
                  document.body.appendChild(form);
                  form.click();
                  alert('Download Successful'); 
                  $(this).attr('value','Download Complete');
                  $(this).prop('disabled', true);
                } 
              });//close modal create
            },300);
          }//close else
        }//close success
      });//close ajax 
  });//close image process

  $(document).on('click', '#preview_doc_item_line', function(e) 
  {
      var refno = $(this).attr('refno');
      var doc_type = $(this).attr('doc_type');

      var modal = $("#medium-modal").modal();

      modal.find('.modal-title').html('Preview Item Line');

      methodd = '';

      methodd +='<table class="table table-bordered table-striped" id="preview_po_item_line_table" width="100%"><thead><th>Line</th><th>Itemcode</th><th>Qty</th><th>Price</th><th>Description</th></thead></table>';

      methodd +='</div>';


      methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

      modal.find('.modal-footer').html(methodd_footer);
      modal.find('.modal-body').html(methodd);

      $('#preview_po_item_line_table').DataTable({
        'processing'  : true,
      });

      $('#preview_po_item_line_table_processing').css({'z-index':'1040'}).show();


      setTimeout(function(){
       $.ajax({
            url:"<?php echo site_url('Panda_prdncn/preview_child_item_line'); ?>",
            method:"POST",
            data: {refno:refno,doc_type:doc_type},
            success:function(data)
            { 
              json = JSON.parse(data);
              // alert(json);return;
              if ( $.fn.DataTable.isDataTable('#preview_po_item_line_table') ) {
                $('#preview_po_item_line_table').DataTable().destroy();
              }

              $('#preview_po_item_line_table').DataTable({
                    // "columnDefs": [ {"targets": 1 ,"visible": false}],
                    'processing'  : true,
                    "sScrollY": "40vh", 
                    "sScrollX": "100%", 
                    "sScrollXInner": "100%", 
                    'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
                    "bScrollCollapse": true,
                    // "pagingType": "simple",
                    'order'       : [ [0 , 'asc'] ],
                    data: json['po_item_line'],
                    columns: [  
                              {data: "Line"},
                              {data: "Itemcode"},
                              {data: "Qty", render:function( data, type, row ){
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
                              {data: "TotalPrice", render:function( data, type, row ){
                                var element = ''
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
                                  element += parseFloat(data).toFixed(2);
                                  <?php
                                }
                                ?>
                                //element = parseFloat(data).toFixed(2);
                                return element;
                              }},                              
                              {data: "Description"}
                             ],   
                    dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                      // $(nRow).attr('id', aData['RefNo']);
                    },
                    "initComplete": function( settings, json ) {
                      setTimeout(function(){
                        interval();
                      },300);
                    }
              });//close datatatable

            }//close succcess
       });//close ajax
    },300);          

  });

});
</script>
