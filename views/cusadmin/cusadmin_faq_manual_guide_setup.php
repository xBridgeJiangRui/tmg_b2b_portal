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
<?php // echo var_dump($_SESSION); ?>
<div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Manual Guide Setup</h3>
          <div class="box-tools pull-right">

          <button title="" data-toggle="modal" data-target="#assign_manual_by_retailer_modal" type="button" class="btn btn-xs btn-warning"   
            ><i class="glyphicon glyphicon-user"></i>Assign By Retailer
          </button>

          <button title="" data-toggle="modal" data-target="#create_manual_modal" type="button" class="btn btn-xs btn-primary"   
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button>

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="">
          
                  <table id="manual_guide_table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Title</th>
                        <th>File Name</th>
                        <th>Language</th>
                        <th>Seq</th>
                        <th>Active</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <th>Updated at</th>
                        <th>Updated by</th>
                        

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($manual_guide->result() as $row) { ?>
                      <tr>
                        <td> 
                          
                          <button title="Detail" type="button" class="btn btn-xs btn-primary Manual_Edit"
                        guide_guid="<?php echo $row->guide_guid ?>"
                        active="<?php echo $row->active ?>"
                        data-title="<?php echo $row->title ?>"
                        description="<?php echo $row->description ?>"
                        file_name="<?php echo $row->file_name ?>"
                        lang_type="<?php echo $row->lang_type ?>"
                        old_customer_guid="<?php echo $row->customer_guid ?>"
                        seq="<?php echo $row->seq ?>"
                        ><i class="fa fa-edit"></i>
                          </button>

                          <button title="Delete" type="button" class="btn btn-xs btn-danger Manual_Delete" 
                          data-guide_guid="<?php echo $row->guide_guid ?>"
                          data-title="<?php echo $row->title ?>" 
                          data-customer_guid="<?php echo $row->customer_guid ?>"
                          file_name="<?php echo $row->file_name ?>"><i class="glyphicon glyphicon-trash"></i>
                          </button>
                        </td>
                        <td><?php echo $row->title ?></td>
                        <td><?php echo $row->file_name ?></td></td>
                        <td><?php echo $row->lang_type ?></td>
                        <td><?php echo $row->seq ?></td>
                        <td>
                        <?php 
                        if ($row->active == 1) {
                          echo '<i class="fa fa-check"></i>';
                        }else {
                          echo '<i class="fa fa-times"></i>';
                        } ?>
                        </td>
                        <td><?php echo $row->created_at ?></td>
                        <td><?php echo $row->created_by ?></td>
                        <td><?php if(($row->updated_at == '000-00-00') || ($row->updated_at  == '0000-00-00 00:00:00'))
                        {
                          echo '';
                        }
                        else
                        {
                          echo $row->updated_at;
                        } ?></td>
                        <td><?php echo $row->updated_by ?></td>
                        
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
             
              </div>  
        </div>

      </div>

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">FAQ</h3>
          <div class="box-tools pull-right">
          <button title="" data-toggle="modal" data-target="#create_faq_modal" type="button" class="btn btn-xs btn-primary"   
            ><i class="glyphicon glyphicon-plus"></i>Create
          </button>

          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concept">
          <div id="">
          
                  <table id="faq_table" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Title</th>
                        <th>File Name</th>
                        <th>Language</th>
                        <th>Seq</th>
                        <th>Active</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <th>Updated at</th>
                        <th>Updated by</th>
                        

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($faq->result() as $row) { ?>
                      <tr>
                        <td> 
                          
                          <button title="Detail" type="button" class="btn btn-xs btn-primary faq_Edit"
                        faq_guid="<?php echo $row->faq_guid ?>"
                        active="<?php echo $row->active ?>"
                        data-title="<?php echo $row->title ?>"
                        description="<?php echo $row->description ?>"
                        file_name="<?php echo $row->file_name ?>"
                        lang_type="<?php echo $row->lang_type ?>"
                        seq="<?php echo $row->seq ?>"
                        ><i class="fa fa-edit"></i>
                          </button>

                          <button title="Delete" type="button" class="btn btn-xs btn-danger faq_Delete" 
                          data-faq_guid="<?php echo $row->faq_guid ?>"
                          data-title="<?php echo $row->title ?>" 
                          file_name="<?php echo $row->file_name ?>"><i class="glyphicon glyphicon-trash"></i>
                          </button>
                        </td>
                        <td><?php echo $row->title ?></td>
                        <td><?php echo $row->file_name ?></td></td>
                        <td><?php echo $row->lang_type ?></td></td>
                        <td><?php echo $row->seq ?></td>
                        <td>
                        <?php 
                        if ($row->active == 1) {
                          echo '<i class="fa fa-check"></i>';
                        }else {
                          echo '<i class="fa fa-times"></i>';
                        } ?>
                        </td>
                        <td><?php echo $row->created_at ?></td>
                        <td><?php echo $row->created_by ?></td>
                        <td><?php echo $row->updated_at ?></td>
                        <td><?php echo $row->updated_by ?></td>
                        
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
             
              </div>  
        </div>

      </div>


    </div>
  </div>
   
<!-- nothing ends after -->
</div>
</div>

<div class="modal fade" id="create_manual_modal" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo site_url('CusAdmin_controller/manual_guide_setup_add')?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
            <h4>Add Manual Guide</h4>        
        </div>
        <div class="modal-body" style="display: inline-block;">
          <!-- <div class="col-md-12">
            <label>Retailer</label>
            <select name="customer_guid" class="form-control input-sm">
              <?php foreach($customer->result() as $row2){ ?>
                <option value="<?php echo $row2->acc_guid;?>"><?php echo $row2->acc_name;?></option>
              <?php } ?>
            </select>
          </div> -->
          <div class="col-md-12">
            <label>Title</label>
            <div class="pull-right"> 
              <label><input name="active" type="checkbox" style="margin-bottom: 2.5px;" checked="true" value="1"> Active</label> 
            </div>
            <input required="true" type="text" id="" name="title" class="form-control" value="" >
          </div>
          <div class="col-md-12">
            <label>Description</label>
            <input required="true" type="text" id="" name="description" class="form-control" value="" >
          </div>
          <div class="col-md-6">
            <label>Language</label>
            <select name="lang_type" class="form-control input-sm">
              <option value="EN">English</option>
              <option value="BM">Bahasa Malaysia</option>
            </select>
          </div>
          <div class="col-md-6"><label>Seq</label><input required="true" type="text" id="" name="seq" class="form-control" value="" ></div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Manual Guide Upload</label>
              <input type="file" name="file[]">
              <p class="help-block">Limit 1 File</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <p class="full-width">
            <span class="buttons pull-left">
              <input type="button" value="No, Cancel" data-dismiss="modal">
            </span>
            <span class="buttons pull-right">
              <input type="submit" value="Yes, Do it!" class="" name="submit">
            </span>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="assign_manual_by_retailer_modal" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo site_url('CusAdmin_controller/manual_guide_mapping_to_retailer')?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
            <h4>Assign Manual Guide by Retailer</h4>        
        </div>
        <div class="modal-body" style="display: inline-block;">
          <div class="col-md-12">
            <label>Retailer</label>
            <select name="selected_retailer" id="selected_retailer" class="form-control">
              <?php foreach($customer->result() as $row2){ ?>
                <option value="<?php echo $row2->acc_guid;?>"><?php echo $row2->acc_name;?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-12">
            <label>Language</label>
            <select name="language" id="language" class="form-control">
              <?php foreach($language_list->result() as $lang){ ?>
                <option value="<?php echo $lang->lang_type;?>"><?php echo $lang->lang_type;?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-12">
            <span id="selected-manual-guide"></span>
          </div>
        </div>
        <div class="modal-footer">
          <p class="full-width">
            <span class="buttons pull-left">
              <input type="button" value="No, Cancel" data-dismiss="modal">
            </span>
            <span class="buttons pull-right">
              <input type="submit" value="Yes, Do it!" class="" name="submit">
            </span>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="create_faq_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="<?php echo site_url('CusAdmin_controller/faq_setup_add')?>" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4>Add FAQ</h4>
                
              </div>
              <div class="modal-body" style="display: inline-block;">
                  <div class="col-md-12"><label>Title</label><div class="pull-right"> <label> <input name="active" type="checkbox" style="margin-bottom: 2.5px;" checked="true" value="1"> Active</label> </div><input required="true" type="text" id="" name="title" class="form-control" value="" ></div>

                  <div class="col-md-12"><label>Description</label><input required="true" type="text" id="" name="description" class="form-control" value="" ></div>

                  <div class="col-md-6">
                    <label>Language</label>
                    <select name="lang_type" class="form-control input-sm">
                      <option value="EN">English</option>
                      <option value="BM">Bahasa Malaysia</option>
                    </select>
                  </div>

                  <div class="col-md-6"><label>Seq</label><input required="true" type="text" id="" name="seq" class="form-control" value="" ></div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">FAQ Upload</label>
                      <input type="file" name="file[]">

                      <p class="help-block">Limit 1 File</p>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="edit_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="edit_form" action="" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <span class="modal-title"></span>
                
              </div>
              <div class="modal-body" style="display: inline-block;">                 
                  
              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<div class="modal fade" id="delete_modal" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="delete_form" action="" method="post" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <span class="modal-title"></span>
                
              </div>
              <div class="modal-body" style="display: inline-block;">                 
                  
              </div>
              <div class="modal-footer">
              <p class="full-width">
                <span class="buttons pull-left">
                    <input type="button" value="No, Cancel" data-dismiss="modal">
                </span>
                <span class="buttons pull-right">
                    <input type="submit" value="Yes, Do it!" class="" name="submit">
                </span>
              </p>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
</div>

<script>

  function multipleCheckboxTrigger(language, status) {
    $('.'+language+'_manual_guide_checkbox').prop('checked', status);
  }

</script>

<script>

  function selectAllCheckboxes() {
    $('.retailer_checkbox').prop('checked', true);
  }

  function unselectAllCheckboxes() {
    $('.retailer_checkbox').prop('checked', false);
  }

  function selectAllGuideCheckboxes() {
    var language = $("#language").val();
    multipleCheckboxTrigger(language, true)
  }

  function unselectAllGuideCheckboxes() {
    var language = $("#language").val();
    multipleCheckboxTrigger(language, false)
  }

</script>

<script>

$(document).ready(function() {
    
  $('#language').change(function() {

    $('.language_div').addClass('hidden');

    var language = $("#language").val();
    var lang_class = language+'_div';

    $('.'+lang_class).removeClass('hidden');

  });

});

</script>

<script>

$(document).ready(function() {

  var retailer = $("#selected_retailer").val();
    
  $.ajax({
    url : "<?php echo site_url('CusAdmin_controller/manual_guide_setup_mapping_by_retailer');?>",
    dataType: 'html',
    method: "POST",
    data:{retailer:retailer},
    success: function(html) {         
      $('#selected-manual-guide').html(html);

      $('.language_div').addClass('hidden');

      var language = $("#language").val();
      var lang_class = language+'_div';

      $('.'+lang_class).removeClass('hidden');
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });

  $('#selected_retailer').change(function() {

    var retailer = $("#selected_retailer").val();
    
    $.ajax({
      url : "<?php echo site_url('CusAdmin_controller/manual_guide_setup_mapping_by_retailer');?>",
      dataType: 'html',
      method: "POST",
      data:{retailer:retailer},
      success: function(html) {         
        $('#selected-manual-guide').html(html);

        $('.language_div').addClass('hidden');

        var language = $("#language").val();
        var lang_class = language+'_div';

        $('.'+lang_class).removeClass('hidden');
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });

  });

  // $.ajax({
	// 	url: "<?php echo site_url('CusAdmin_controller/manual_guide_setup_mapping')?>",
	// 	type: 'post',
	// 	dataType: 'html',
  //   async: false,
  //   data: {guide_guid: guide_guid},
	// 	success: function(data) {
  //     methodd += data;
  //   },
	// 	error: function(xhr, ajaxOptions, thrownError) {
	// 		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	// 	}
	// });
});

</script>
 
<script>

$(document).ready(function(){

$(document).on('click', '#manual_guide_table .Manual_Edit', function(){

    // $('#select-all-retailer').change(function() {
    //   $('.retailer_checkbox').prop('checked', true);
    // });

    // $('#unselect-all-retailer').change(function() {
    //   $('.retailer_checkbox').prop('checked', false);
    // });

  var modal = $("#edit_modal").modal();

  var guide_guid = $(this).attr('guide_guid')
  var active = $(this).attr('active')
  var title = $(this).attr('data-title')
  var lang_type = $(this).attr('lang_type')
  var description = $(this).attr('description')
  var file_name = $(this).attr('file_name')
  var seq = $(this).attr('seq')
  var old_customer_guid = $(this).attr('old_customer_guid')

  if (active == 1 ) {

    activeischecked = 'checked = "true" '

  }

  else {

    activeischecked = ''

  }

  modal.find('.modal-title').html('<h3>Edit Manual Guide - '+title+'</h3>');

  modal.find('#edit_form').attr("action","<?php echo site_url('CusAdmin_controller/manual_guide_setup_edit')?>");

  methodd = '';

  methodd +='<div class="row">';

  methodd += '<div class="col-md-12">';
  methodd += '<label>Retailer</label>';
  methodd += '<span style="padding: inherit;">';
  methodd += '<a style="cursor: pointer;" id="select-all-retailer" onclick="selectAllCheckboxes()">Select All</a>'; 
  methodd += '/';
  methodd += '<a style="cursor: pointer;" id="unselect-all-retailer" onclick="unselectAllCheckboxes()">Unselect All</a>';
  methodd += '</span>';
  methodd += '<div style="height: 180px; overflow-y: auto; border: 1px solid #ccc; background-color: #f5f5f5; padding: 10px;">';

  $.ajax({
		url: "<?php echo site_url('CusAdmin_controller/manual_guide_setup_mapping')?>",
		type: 'post',
		dataType: 'html',
    async: false,
    data: {guide_guid: guide_guid},
		success: function(data) {
      methodd += data;
    },
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});

  methodd += '</div></div>';

  methodd += '<div class="col-md-12"><label>Title</label><div class="pull-right"> <label> <input '+activeischecked+' name="active" type="checkbox" style="margin-bottom: 2.5px;" value="1"> Active</label> </div><input value="'+title+'" required="true" type="text" id="" name="title" class="form-control" value="" ></div>';

  methodd += '<div class="col-md-12"><label>Description</label><input value="'+description+'" required="true" type="text" id="" name="description" class="form-control" value="" ></div>';

  methodd += '<div class="col-md-6"> <label>Language</label> <select name="lang_type" class="form-control input-sm"> <option value="EN">English</option> <option value="BM">Bahasa Malaysia</option> </select> </div>'

  methodd += '<div class="col-md-6"><label>Seq</label><input value="'+seq+'" required="true" type="text" id="" name="seq" class="form-control" value="" ></div>';

  methodd += '<div class="col-md-12"> <div class="form-group"> <label for="">Manual Guide Upload</label> <input type="file" name="file[]"> <p class="help-block">Limit 1 File</p> </div> </div>';

  methodd += '<div class="col-md-12"> <p>File Name : <a href="<?php echo base_url('asset/manual_guide/')?>'+file_name+'" download="'+file_name+'" >'+file_name+'</a></p> </div>';

  methodd += '<div class="col-md-12"><input value="'+guide_guid+'" required="true" type="hidden" id="guide_guid" name="guide_guid" class="form-control" value="" /></div>';

  methodd += '<div class="col-md-12"><input value="'+file_name+'" required="true" type="hidden" id="file_name" name="file_name" class="form-control" value="" /></div>';

  methodd += '<div class="col-md-12"><input value="'+old_customer_guid+'" required="true" type="hidden" id="old_customer_guid" name="old_customer_guid" class="form-control"/></div>';

  methodd += '</div>';

  modal.find('.modal-body').html(methodd);

  modal.find('select[name="lang_type"]').val(lang_type);

  modal.find('select[name="customer_guid"]').val(old_customer_guid);

})

$(document).on('click', '#manual_guide_table .Manual_Delete', function(){

  var modal = $("#delete_modal").modal();

  var guide_guid = $(this).attr('data-guide_guid')
  var title = $(this).attr('data-title')
  var file_name = $(this).attr('file_name')
  var customer_guid = $(this).attr('data-customer_guid')


  modal.find('.modal-title').html('<h3>Delete Manual Guide - '+title+'</h3>');

  modal.find('#delete_form').attr("action","<?php echo site_url('CusAdmin_controller/manual_guide_setup_delete')?>");

  methodd = '';

  methodd +='<div class="row">';


  methodd += '<div class="col-md-12"><p>Are you sure you want to delete <b>'+title+'</b> with the file name <b>'+file_name+'</b>?</p></div>';

  methodd += '<div class="col-md-12"><input value="'+guide_guid+'" required="true" type="hidden" id="guide_guid" name="guide_guid" class="form-control"/></div>';

  methodd += '<div class="col-md-12"><input value="'+file_name+'" required="true" type="hidden" id="file_name" name="file_name" class="form-control"/></div>';

  methodd += '<div class="col-md-12"><input value="'+customer_guid+'" required="true" type="hidden" id="customer_guid" name="customer_guid" class="form-control"/></div>';

  methodd += '</div>';

  modal.find('.modal-body').html(methodd);

})

$(document).on('click', '#faq_table .faq_Edit', function(){

  var modal = $("#edit_modal").modal();

  var faq_guid = $(this).attr('faq_guid')
  var active = $(this).attr('active')
  var title = $(this).attr('data-title')
  var description = $(this).attr('description')
  var file_name = $(this).attr('file_name')
  var lang_type = $(this).attr('lang_type')
  var seq = $(this).attr('seq')

  if (active == 1 ) {

    activeischecked = 'checked = "true" '

  }

  else {

    activeischecked = ''

  }

  modal.find('.modal-title').html('<h3>Edit Manual Guide - '+title+'</h3>');

  modal.find('#edit_form').attr("action","<?php echo site_url('CusAdmin_controller/faq_setup_edit')?>");

  methodd = '';

  methodd +='<div class="row">';

  methodd += '<div class="col-md-12"><label>Title</label><div class="pull-right"> <label> <input '+activeischecked+' name="active" type="checkbox" style="margin-bottom: 2.5px;" value="1"> Active</label> </div><input value="'+title+'" required="true" type="text" id="" name="title" class="form-control" value="" ></div>';

  methodd += '<div class="col-md-12"><label>Description</label><input value="'+description+'" required="true" type="text" id="" name="description" class="form-control" value="" ></div>';

  methodd += '<div class="col-md-6"> <label>Language</label> <select name="lang_type" class="form-control input-sm"> <option value="EN">English</option> <option value="BM">Bahasa Malaysia</option> </select> </div>'

  methodd += '<div class="col-md-6"><label>Seq</label><input value="'+seq+'" required="true" type="text" id="" name="seq" class="form-control" value="" ></div>';

  methodd += '<div class="col-md-12"> <div class="form-group"> <label for="">Manual Guide Upload</label> <input type="file" name="file[]"> <p class="help-block">Limit 1 File</p> </div> </div>';

  methodd += '<div class="col-md-12"> <p>File Name : <a href="<?php echo base_url('asset/faq/')?>'+file_name+'" download="'+file_name+'" >'+file_name+'</a></p> </div>';

  methodd += '<div class="col-md-12"><input value="'+faq_guid+'" required="true" type="hidden" id="faq_guid" name="faq_guid" class="form-control" value="" /></div>';

  methodd += '<div class="col-md-12"><input value="'+file_name+'" required="true" type="hidden" id="file_name" name="file_name" class="form-control" value="" /></div>';

  methodd += '</div>';

  modal.find('.modal-body').html(methodd);

  modal.find('select[name="lang_type"]').val(lang_type);


})

$(document).on('click', '#faq_table .faq_Delete', function(){

  var modal = $("#delete_modal").modal();

  var faq_guid = $(this).attr('data-faq_guid')
  var title = $(this).attr('data-title')
  var file_name = $(this).attr('file_name')


  modal.find('.modal-title').html('<h3>Delete Manual Guide - '+title+'</h3>');

  modal.find('#delete_form').attr("action","<?php echo site_url('CusAdmin_controller/faq_setup_delete')?>");

  methodd = '';

  methodd +='<div class="row">';


  methodd += '<div class="col-md-12"><p>Are you sure you want to delete <b>'+title+'</b> with the file name <b>'+file_name+'</b>?</p></div>';

  methodd += '<div class="col-md-12"><input value="'+faq_guid+'" required="true" type="hidden" id="faq_guid" name="faq_guid" class="form-control" value="" /></div>';

  methodd += '<div class="col-md-12"><input value="'+file_name+'" required="true" type="hidden" id="file_name" name="file_name" class="form-control" value="" /></div>';

  methodd += '</div>';

  modal.find('.modal-body').html(methodd);

})

})


</script>

<script>
  $(document).ready(function () {    
    $('#manual_guide_table').DataTable({
      "columnDefs": [{}],
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, 999999999999], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [1 , 'desc'] ],
      'info'        : true,
      'autoWidth'   : true,
      // dom: 'frtip',
      "buttons": [
        {
          extend: 'excelHtml5',
          exportOptions: { orthogonal: 'export' }
        },
      ],
      dom:'<"row"<"col-sm-2" l><"col-sm-4" B><"col-sm-6" f>>rtip',
    })

    $('#faq_table').DataTable({
      "columnDefs": [{}],
      'paging'      : true,
      'lengthChange': true,
      'lengthMenu'  : [ [10, 25, 50, -1], [10, 25, 50, "ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      'order'       : [ [1 , 'desc'] ],
      'info'        : true,
      'autoWidth'   : true,
      dom: 'frtip',
    })
  })
</script>
