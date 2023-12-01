<style type="text/css">
.alignleft {
  text-align: left;
  white-space: nowrap;
}

.content-wrapper{ 
  min-height: 750px !important;
}

</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
<br>
    <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Document List</h3>
          <div class="box-tools pull-right">
            <!-- <button id="upload_doc_list" class="btn btn-xs btn-default">
              <i class="glyphicon glyphicon-plus"></i> Upload
            </button> -->
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" >
            <table id="doc_list_tb_user" class="table table-hover" >
              <thead style="white-space: nowrap;">
              <tr>
                <th>Action</th>
                <th>Annoucenment</th>
                <th>Company Name</th>
                <th>User Name</th>
                <th>Uploaded By</th>
                <th>Uploaded At</th>

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
<script src="<?php echo base_url('assets/plugins/timepicker/bootstrap-timepicker.min.js')?>"></script>
<script>
$(document).ready(function() {
  $('#doc_list_tb_user').DataTable({
    "columnDefs": [{"targets": 0 ,"orderable": false}],
    "serverSide": true, 
    'processing'  : true,
    'paging'      : true,
    'lengthChange': true,
    'lengthMenu'  : [ [10, 25, 50, 100], [10, 25, 50, 100] ],
    'searching'   : true,
    'ordering'    : true,
    'order'       : [ [5 , 'DESC'] ],
    'info'        : true,
    'autoWidth'   : false,
    "bPaginate": true, 
    "bFilter": true, 
    "fixedColumns": true,
    // "sScrollY": "80vh", 
    "sScrollX": "100%", 
    "sScrollXInner": "100%", 
    "bScrollCollapse": true,
    "ajax": {
        "url": "<?php echo site_url('Sign_upload_doc/doc_list_user_table');?>",
        "type": "POST",
    },
    columns: [

             { "data": "document_guid" ,render:function( data, type, row ){

                var element = '';
                var element1 = row['url_value'];
                var element2 = row['query_guid'];
                var element3 = row['session_guid'];

                element += '<a href='+element1+' target="_blank"><button id="view_pdf" type="button" title="View/Download" class="btn btn-sm btn-warning" ><i class="fa fa-download"></i></button></a>  ';

                <?php
                if(in_array('IAVA',$_SESSION['module_code']))
                {
                ?>
                  element += '<a href="<?php echo site_url('Sign_upload_doc/doc_upload_sites?announcement_guid='); ?>'+row['announcement_guid']+'" target="_blank"><button id="redirect_pdf" type="button" title="EDIT" class="btn btn-sm btn-primary" ><i class="fa fa-edit"></i></button></a>  ';

                  element += '<button id="delete_pdf" type="button" title="DELETE" class="btn btn-sm btn-danger" document_guid = "'+data+'" supplier_guid = '+row['supplier_guid']+' supplier_name =  "'+row['supplier_name']+'" announcement_guid = '+row['announcement_guid']+'><i class="fa fa-trash"></i></button>  ';
               
                <?php
                }
                else
                {
                  ?>
                  if(element2 == element3)
                  {
                    
                    element += '<a href="<?php echo site_url('Sign_upload_doc/doc_upload_sites?announcement_guid='); ?>'+row['announcement_guid']+'" target="_blank"><button id="redirect_pdf" type="button" title="EDIT" class="btn btn-sm btn-primary" ><i class="fa fa-edit"></i></button></a>  ';

                    element += '<button id="delete_pdf" type="button" title="DELETE" class="btn btn-sm btn-danger" document_guid = "'+data+'" supplier_guid = '+row['supplier_guid']+'  supplier_name = "'+row['supplier_name']+'" announcement_guid = '+row['announcement_guid']+' ><i class="fa fa-trash"></i></button>  ';
                    
                  }

                  <?php
                }
                ?>
                return element;
       
              }},
              { "data" : "title" },
              { "data" : "supplier_name" },
              { "data" : "user_name" },
              { "data" : "created_by" },
              { "data" : "created_at" },
              
              
             ],
    dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>rtip",
    // "pagingType": "simple",
    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
      $(nRow).attr('document_guid', aData['document_guid']);
      
    },
    "initComplete": function( settings, json ) {
      setTimeout(function(){
        interval();
      },300);
    }
  });//close datatable

  $(document).on('click','#delete_pdf',function(){

    var document_guid = $(this).attr('document_guid');

    var supplier_guid = $(this).attr('supplier_guid');

    var supplier_name = $(this).attr('supplier_name');

    var announcement_guid = $(this).attr('announcement_guid');

    if(document_guid == '')
    {
      alert('Undefined GUID. Please contact support or refresh page.');
      return;
    }
    
    confirmation_modal('Are you sure want to remove all document for <br> <b> Company : ' +supplier_name+ ' </b> with the user(s) ?');

    $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
      //console.log(formData); die;
      $.ajax({
          url:"<?php echo site_url('Sign_upload_doc/delete_url_file');?>",
          method:"POST",
          data:{document_guid:document_guid,supplier_guid:supplier_guid,announcement_guid:announcement_guid},
          beforeSend:function(){
            $('.btn').button('loading');
          },
          success:function(data)
          {
            json = JSON.parse(data);
            if (json.para1 == '1') {
              alert(json.msg);
              $('#alertmodal').modal('hide');
              $('.btn').button('reset');

            }else{
              alert(json.msg);
              $('.btn').button('reset');
              location.reload();
            }//close else
          }//close success
        });//close ajax
    });//close document yes click
  });//close submit_button


});
</script>
