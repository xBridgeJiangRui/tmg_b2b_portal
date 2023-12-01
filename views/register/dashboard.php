<style type="text/css">

.content-wrapper{
  min-height: 800px !important; 
}

.bg-processing
{
  background-color: #f56e6e !important;
}

.bg-save
{
  background-color: #8cf1f5 !important;
}

.bg-advance
{
  background-color: #e492e8 !important;
}

.bg-archive
{
  background-color: #b6d2eb !important;
}

.bg-noaction
{
  background-color: #b5ffe8 !important;
}

.bg-terminate
{
  background-color: #ffd5b5 !important;
}

.bg-document
{
  background-color: #4DFE92 !important;
}

.progress {
    height: 5px !important;
}

.css_1 {
    float: right;
    background: transparent;
    margin-top: 0;
    margin-bottom: 0;
    padding: 7px 5px;
    position: absolute;
    top: 15px;
    right: 10px;
    border-radius: 2px;
}
</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
<div class="container-fluid">
    <section class="content-header">
        <h1>
        Registration Dashboard
        <small>
            <button id="collapse_summary" type="button" class="btn btn-xs btn-danger"><i class="fa fa-minus-square" aria-hidden="true"></i> Minimize</button>
            <button id="show_summary" type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> Open </button>

        </small>
        </h1>
        
        <div class="css_1">
            <select class="form-control select2" name="select_guid" id="select_guid">
                <?php foreach($get_acc as $row)
                {
                    ?>
                    <option value="<?php echo $row->acc_guid?>" ><?php echo $row->acc_name?></option>
                    <?php
                }
                ?>
                <option value="all" >All</option>
            </select>
        </div>

    </section>

    <!--User Select will append here-->
    <span id="append_view_all">
    </span>
    <!--END append here-->

    <!--View Session Retailer-->
    <div class="row">
        <div class="clearfix"></div><br>

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">All Retailer</h3>
                    <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="col_mini fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body summary_body" >
                    <!-- <div id="dashboard2"> -->
                        <!-- Registered -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>

                                <div class="info-box-content">
                                <span class="info-box-text">Registered</span>
                                <span class="info-box-number"><?php echo $registered ?><?php echo  ' '.'('.$percent_reg.'%)'; ?></span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_reg.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?> 
                                </span>
                                </div>
                            <!-- /.info-box-content -->
                            </div>
                        </div>

                        <!-- Outright -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-document">
                                <span class="info-box-icon"><i class="fa fa-truck"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Outright</span>
                                <span class="info-box-number"><?php echo $outright . ' ('.$percent_outright.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_outright.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                Total Registered : <?php echo $registered ?>
                                </span>
                            </div>
                        </div>
                        </div>

                        <!-- Consignment -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-document">
                                <span class="info-box-icon"><i class="fa fa-truck"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Consignment</span>
                                <span class="info-box-number"><?php echo $consignment . ' ('.$percent_consignment.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_consignment.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                Total Registered : <?php echo $registered ?>
                                </span>
                            </div>
                        </div>
                        </div>

                        <!-- Both -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-document">
                                <span class="info-box-icon"><i class="fa fa-truck"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Outright & Consign</span>
                                <span class="info-box-number"><?php echo $both . ' ('.$percent_both.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_both.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                Total Registered : <?php echo $registered ?>
                                </span>
                            </div>
                        </div>
                        </div>
                            
                        <!-- New -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-blue">
                                <span class="info-box-icon"><i class="fa fa-users"></i></span>

                                <div class="info-box-content">
                                <span class="info-box-text">New</span>
                                <span class="info-box-number"><?php echo $new ?><?php echo  ' '.'('.$percent_new.'%)'; ?></span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_new.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?> 
                                </span>
                                </div>
                        <!-- /.info-box-content -->
                            </div>
                        </div>

                        <!-- Send -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-send"></i></span>

                                <div class="info-box-content">
                                <span class="info-box-text">Send</span>
                                <span class="info-box-number"><?php echo $send . ' ('.$percent_send.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_send.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?>
                                </span>
                                </div>
                        <!-- /.info-box-content -->
                            </div>
                        </div>
                            
                        <!-- Save Progress -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-save">
                                <span class="info-box-icon"><i class="fa fa-save"></i></span>

                                <div class="info-box-content">
                                <span class="info-box-text">Save Progress</span>
                                <span class="info-box-number"><?php echo $save_progress . ' ('.$percent_save_progress.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_save_progress.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?>
                                </span>
                                </div>
                        <!-- /.info-box-content -->
                            </div>
                        </div>

                        <!-- Processing -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-processing">
                                <span class="info-box-icon"><i class="fa fa-hourglass-start"></i></span>

                                <div class="info-box-content">
                                <span class="info-box-text">Processing</span>
                                <span class="info-box-number"><?php echo $processing . ' ('.$percent_processing.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_processing.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?>
                                </span>
                                </div>
                            <!-- /.info-box-content -->
                            </div>
                        </div>

                        <!-- Advance -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-advance">
                                <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Advance</span>
                                <span class="info-box-number"><?php echo $advance . ' ('.$percent_advance.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_advance.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?>
                                </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>

                        <!-- Archive -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-archive">
                                <span class="info-box-icon"><i class="fa fa-archive"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Archive</span>
                                <span class="info-box-number"><?php echo $archived . ' ('.$percent_archived.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_archived.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?>
                                </span>
                            </div> <!-- /.info-box-content -->
                        </div>
                        </div>
                        <!-- </div> -->

                        <!-- No Action -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-noaction">
                                <span class="info-box-icon"><i class="fa fa-exclamation"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">No Action</span>
                                <span class="info-box-number"><?php echo $no_action . ' ('.$percent_no_action.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_no_action.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?>
                                </span>
                            </div>
                        </div>
                        </div>
                        <!-- </div> -->
                        
                        <!-- Terminated -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="info-box bg-terminate">
                                <span class="info-box-icon"><i class="fa fa-hand-stop-o"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Terminated</span>
                                <span class="info-box-number"><?php echo $terminate . ' ('.$percent_terminate.'%)'; ?> </span>

                                <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $percent_terminate.'%'; ?>"></div>
                                </div>
                                <span class="progress-description">
                                of Total Suppliers : <?php echo $total ?>
                                </span>
                            </div>
                        </div>
                        <!-- </div> -->
                </div>  
            </div>
        </div>
    </div>
    
    <!--User Select will append here-->
    <span id="append_view">
    

    </span>
    <!--END append here-->

</div>
</div>
</div>
<script>
$(document).ready(function() {
    // var session_retailer = '<?php echo $acc_guid?>'; 
    $('#select_guid').val('all').trigger('change');
    $('#show_summary').hide();

    $(document).on('change','#select_guid',function(){
        var acc_guid = $('#select_guid').val();
        var acc_name = $('#select2-select_guid-container').attr('title');

        if(acc_guid == 'all')
        {
            alert('All Retailer already selected.');
            return;
        }

        if($('#select_guid option:selected').prop('disabled') == true)
        {
            alert(acc_name+' already selected.');
            return;
        }
        $('#select_guid option[value="'+acc_guid+'"]').attr("disabled", true);
        
        //alert(acc_guid); die;
        $.ajax({
            url:"<?php echo site_url('Registration_dashboard/add_new_summary');?>",
            method:"POST",
            data:{acc_guid:acc_guid},
            beforeSend:function(){
                $('.btn').button('loading');
            },
            success:function(data)
            {
                $('.btn').button('reset');
                //alert('123'); die;
                json = JSON.parse(data);
                if(json.para1 == 'true') 
                {
                    view = '';

                    view += '<div class="col-md-6" id="modal_'+json.acc_guid+'"> <div class="box box-info"> <div class="box-header with-border"> <h3 class="box-title">'+json.customer_name+'</h3><br> <div class="box-tools pull-right"> <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="col_mini fa fa-minus"></i></button> <button type="button" class="btn btn-box-tool" id="remove_summary" data-widget="remove" store_guid ="'+json.acc_guid+'"><i class="fa fa-times"></i></button> </div> </div>';

                    
                    view += '<div class="box-body summary_body">';

                    //Registered
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-green"> <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span> <div class="info-box-content"> <span class="info-box-text">Registered</span> <span class="info-box-number">'+json.registered+' ('+json.percent_reg+'%) </span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_reg+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+' </span> </div> </div> </div>';

                    //Outright
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-document"> <span class="info-box-icon"><i class="fa fa fa-truck"></i></span> <div class="info-box-content"> <span class="info-box-text">Outright</span> <span class="info-box-number">'+json.outright+' ('+json.percent_outright+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_outright+'%"></div> </div> <span class="progress-description"> Total Registered : '+json.registered+'</span> </div> </div> </div>';

                    //Consginment
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-document"> <span class="info-box-icon"><i class="fa fa fa-truck"></i></span> <div class="info-box-content"> <span class="info-box-text">Consignment</span> <span class="info-box-number">'+json.consignment+' ('+json.percent_consignment+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_consignment+'%"></div> </div> <span class="progress-description"> Total Registered : '+json.registered+'</span> </div> </div> </div>';

                    //Both
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-document"> <span class="info-box-icon"><i class="fa fa fa-truck"></i></span> <div class="info-box-content"> <span class="info-box-text">Outright & Consign</span> <span class="info-box-number">'+json.both+' ('+json.percent_both+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_both+'%"></div> </div> <span class="progress-description"> Total Registered : '+json.registered+'</span> </div> </div> </div>';

                    //New
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-blue"> <span class="info-box-icon"><i class="fa fa-users"></i></span> <div class="info-box-content"> <span class="info-box-text">New</span> <span class="info-box-number">'+json.new+' ('+json.percent_new+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_new+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+'</span> </div> </div> </div>';

                    //Send
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-yellow"> <span class="info-box-icon"><i class="fa fa-send"></i></span> <div class="info-box-content"> <span class="info-box-text">Send</span> <span class="info-box-number">'+json.send+' ('+json.percent_send+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_send+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+'</span> </div> </div> </div>';

                    //Save Progress
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-save"> <span class="info-box-icon"><i class="fa fa-save"></i></span> <div class="info-box-content"> <span class="info-box-text">Save Progress</span> <span class="info-box-number">'+json.save_progress+' ('+json.percent_save_progress+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_save_progress+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+'</span> </div> </div> </div>';

                    //Processing
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-processing"> <span class="info-box-icon"><i class="fa fa-hourglass-start"></i></span> <div class="info-box-content"> <span class="info-box-text">Processing</span> <span class="info-box-number">'+json.processing+' ('+json.percent_processing+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_processing+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+'</span> </div> </div> </div>';

                    //Advance
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-advance"> <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span> <div class="info-box-content"> <span class="info-box-text">Advance</span> <span class="info-box-number">'+json.advance+' ('+json.percent_advance+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_advance+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+'</span> </div> </div> </div>';

                    //Archive
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-archive"> <span class="info-box-icon"><i class="fa fa-archive"></i></span> <div class="info-box-content"> <span class="info-box-text">Archive</span> <span class="info-box-number">'+json.archived+' ('+json.percent_archived+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_archived+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+'</span> </div> </div> </div>';
                    
                    //No Action
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-noaction"> <span class="info-box-icon"><i class="fa fa-exclamation"></i></span> <div class="info-box-content"> <span class="info-box-text">No Action</span> <span class="info-box-number">'+json.no_action+' ('+json.percent_no_action+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_no_action+'%"></div> </div> <span class="progress-description"> of Total Suppliers : '+json.total+'</span> </div> </div> </div>';

                    //Terminated
                    view += '<div class="col-md-6 col-sm-6 col-xs-12"> <div class="info-box bg-terminate"> <span class="info-box-icon"><i class="fa fa-hand-stop-o"></i></span> <div class="info-box-content"> <span class="info-box-text">No Action</span> <span class="info-box-number">'+json.terminate+' ('+json.percent_terminate+'%)</span> <div class="progress"> <div class="progress-bar" style="width: '+json.percent_terminate+'%"></div> </div> <span class="progress-description"> Total Suppliers : '+json.total+'</span> </div> </div> </div>';

                    view += '</div></div></div>';            
                                
                    $('#append_view').append(view);
                }
            }//close success
        });//close ajax
    });//close edit

    $(document).on('click','#remove_summary',function(){
        var acc_guid = $(this).attr('store_guid');
        $('#select_guid option[value="'+acc_guid+'"]').attr("disabled", false);
        $('#modal_'+acc_guid+'').remove();
    });//close edit

    $(document).on('click','#collapse_summary',function(){
        $('.box-info').addClass('collapsed-box');
        $('.summary_body').css("display","none");
        $('.col_mini').removeClass('fa fa-minus');
        $('.col_mini').addClass('fa fa-plus');
        $('#show_summary').show();
        $('#collapse_summary').hide();
        
    });//close edit

    $(document).on('click','#show_summary',function(){
        $('.box-info').removeClass('collapsed-box');
        $('.summary_body').css("display","block");
        $('.col_mini').removeClass('fa fa-plus');
        $('.col_mini').addClass('fa fa-minus');
        $('#show_summary').hide();
        $('#collapse_summary').show();
    });//close edit



});
</script>
