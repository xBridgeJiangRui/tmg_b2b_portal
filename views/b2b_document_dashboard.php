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

.blinker {
  animation: blink-animation 5s steps(10, start) infinite;
  -webkit-animation: blink-animation 2s steps(10, start) infinite;
  background-color: red;
  font-weight: bold;
  font-size:16px;
  color:black;
}

@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Uploading Documents To B2B</h3>
          <div class="box-tools pull-right">
            <?php if(in_array('IAVA',$this->session->userdata('module_code')))
            {
            ?>
            <button id="sync_btn" type="button" class="btn btn-xs btn-danger"><i class="fa fa-refresh" aria-hidden="true" ></i> Sync Document</button>
            <?php
            }
            ?>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >

          <table id="doc_tb" class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead > <!--style="white-space: nowrap;"-->
            <tr>
                <th>Retailer Name</th>
                <th>Condition Type</th>
                <th>PO</th>
                <th>GRN</th>
                <th>GRDA</th>
                <th>STRB</th>
                <th>PRDN</th>
                <th>PRCN</th>
                <th>PDN</th>
                <th>PCN</th>
                <th>PCI</th>
                <th>DI</th>
                <th>Created At</th>
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
  
  //every 40 mins refresh
  window.setTimeout( function() {
    window.location.reload();
  }, 2400000);


  $('#doc_tb').DataTable({
    "columnDefs": [],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    // "sScrollY": "30vh", 
    // "sScrollX": "100%", 
    // "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Pending_document/pending_table');?>",
        "type": "POST",
    },
    columns: [
      { "data": "acc_name" },
      { "data": "type" ,render:function( data, type, row ){

      var element = '';
      if(data == 0)
      {
        element += 'Task Agent Running';
      }
      else if(data == 1)
      {
        element += 'Uploading to B2B';
      }
      else if(data == 99)
      {
        element += 'JSON string Running';
      }
      else
      {
        element += '';
      }

      return element;

      }},
      { "data": "po" },
      { "data": "grn" },
      { "data": "grda" },
      { "data": "strb" },
      { "data": "prdn" },
      { "data": "prcn" },
      { "data": "pdn" },
      { "data": "pcn" },
      { "data": "pci" },
      { "data": "di" },
      { "data": "created_at" },

    ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      //$(nRow).attr('guid', aData['guid']);
      //$(nRow).find('td:eq(2)').css({"background-color":"#80ffaa","color":"black"});
      if(aData['check_po'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(2)').addClass('blinker');
      }

      if(aData['check_grn'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(3)').addClass('blinker');
      }

      if(aData['check_grda'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(4)').addClass('blinker');
      }

      if(aData['check_strb'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(5)').addClass('blinker');
      }

      if(aData['check_prdn'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(6)').addClass('blinker');
      }

      if(aData['check_prcn'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(7)').addClass('blinker');
      }

      if(aData['check_pdn'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(8)').addClass('blinker');
      }

      if(aData['check_pcn'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(9)').addClass('blinker');
      }

      if(aData['check_pci'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(10)').addClass('blinker');
      }

      if(aData['check_di'] == 1)
      {
        $(nRow).find('td:eq(0)').css({"background-color":"#f5200c","color":"black"});
        $(nRow).find('td:eq(11)').addClass('blinker');
      }
      
    },
    "initComplete": function( settings, json ) {
      interval();
    }
  });//close datatable

  $(document).on('click','#sync_btn',function(){
    // alert('OUCHHH...');
    // die;
    confirmation_modal("Are you sure want to Sync?");
    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      $.ajax({
        url:"<?php echo site_url('Pending_document/resync_data');?>",
        method:"POST",
        data:{},
        beforeSend:function(){
          $('.btn').button('loading');
          $('#alertmodal').modal('hide');
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
          if (json.para1 == 'false') {
            $('.btn').button('reset');
            Swal.fire({
                title: json.msg, 
                text: '', 
                type: "error"
            }).then((result) => {
                // Reload the Page
                //location.reload();
                setTimeout(function() {
                $('.sidebar-collapse').css({'padding-right':''});
                }, 300);
            });
          }else{
            $('.btn').button('reset');
            setTimeout(function() {
              Swal.fire({
                title: json.msg, 
                text: '', 
                type: "success"
              }).then((result) => {
                // Reload the Page
                location.reload();
              });
            }, 300);
          }//close else
        }//close success
      });//close ajax
    });//close document yes click
  });

});
</script>
