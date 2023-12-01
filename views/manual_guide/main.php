<div class="content-wrapper" style="min-height: 525px;">
<div class="container-fluid">
<br>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Manual Guide</h3> <br>
          <div class="box-tools pull-right">

            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->

            <div class="form-group">
                  <label class="col-sm-4 control-label" style="margin-top: 5px;">Language: </label>

                  <div class="col-sm-8" style="padding-left: 10px;padding-right: 0px;">
                    <select id="lang" class="form-control input-sm">
                      <option value="EN">English</option>
                      <option value="BM">Bahasa Malaysia</option>
                    </select>
                  </div>
                </div>
            
          </div>
        </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-10" id="details-wrap">

              
                <?php if ($manual_guide->num_rows() > 0) { ?>

                <ul>

                <?php foreach ($manual_guide->result() as $value) { ?>
                  <li>
                    <h5><a href="<?php echo $defined_path.'/'.$value->file_name;?>" download="<?php echo $value->file_name;?>" ><?php echo $value->title;?></a></h5>
                    <div class="faded" style="margin:10px 0">
                    <?php echo $value->description ?></div>
                  </li>
                  <hr>
                <?php } ?>

                </ul>

                <?php } else {

                  if ( isset($_REQUEST['sv']) ) {
                    echo '<ul>There is no result match from your search.</ul>';
                  }else{

                    echo '<ul>Coming soon...</ul>';
                  }
                } ?>
              

              </div>
              <div class="col-md-2">

                

              <form method="post" action="<?php echo site_url('manual_guide/index?sv=y')?>">

      
                <div class="input-group">
                  <input type="text" value="<?php echo $search_value; ?>" name="search_value" placeholder="Search All Manual" class="form-control">
                  <span style="cursor: pointer;" onclick="location.href='<?php echo site_url('manual_guide')?>'" class="input-group-addon" title="Clear"><i class="fa fa-refresh"></i></span>
                </div>
    
              </form>

              </div>
            </div>        
          </div>
      </div>
    </div>
  </div>

</div>
</div>

<script type="text/javascript">
  
  $(document).ready(function () {  

    $(document).on('change', '#lang', function(){

      $('input[name="search_value"]').val('');
      language_type = $('#lang').val();

      $.ajax({
        url : "<?php echo site_url('Manual_guide/change_language?lt='); ?>" + language_type,
        success : function(result){
          result = JSON.parse(result);
          html = '';

          if (result['manual_guide'].length != 0){

            for(i = 0; i < result['manual_guide'].length; i++){

              html += '<li>';
              html += '<h5><a href="<?php echo $defined_path.'/'?>'+result['manual_guide'][i].file_name+' " download="'+result['manual_guide'][i].file_name+'" >'+result['manual_guide'][i].title+'</a></h5>';
              html += '<div class="faded" style="margin:10px 0"> '+result['manual_guide'][i].description+'</div>';
              html += '</li>';
              html += '<hr>';

            }

          } else {

            html += 'Coming soon...';

          } 

          $('#details-wrap ul').html(html);

        }
      })
    })

  })

</script>