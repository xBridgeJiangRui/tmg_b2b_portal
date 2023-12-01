<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
    <div class="container-fluid">
        <!-- <div class="pull-right box-tools">

                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                    Bulk Download <i class="fa fa-angle-double-down"></i>
                </button>
            </div> -->
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filter By</h3><br>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="row">
                                <!-- <form role="form" method="POST" id="myForm" action="<?php echo site_url('Edi'); ?>"> -->
                                <div class="col-md-2"><b>Edi Batch No</b></div>
                                <div class="col-md-4">
                                    <input id="edi_batch_no" type="text" autocomplete="off" class="form-control pull-right">
                                </div>
                                <div class="clearfix"></div><br>

                                <div class="col-md-2"><b>Status</b></div>
                                <div class="col-md-4">
                                    <select id="status" class="form-control">
                                        <option value=""></option>
                                        <?php foreach ($get_edi_status->result() as $row) { ?>
                                            <option value="<?php echo $row->code ?>">
                                                <?php echo $row->reason; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="clearfix"></div><br>


                                <div class="clearfix"></div><br>

                                <div class="col-md-2"><b>Generated Date From<br>(YYYY-MM-DD)</b></div>
                                <div class="col-md-2">
                                    <input id="generate_date_from" name="generate_date_from" type="datetime" value="" readonly class="form-control pull-right">
                                </div>
                                <div class="col-md-2"><b>Generated Date To<br>(YYYY-MM-DD)</b></div>
                                <div class="col-md-2">
                                    <input id="generate_date_to" name="generate_date_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-danger" onclick="expiry_clear()">Clear</a>
                                </div>
                                <div class="clearfix"></div><br>

                                <div class="col-md-2"><b>Filter by Period Code<br>(YYYY-MM)</b></div>
                                <div class="col-md-4">
                                    <select id="period_code" class="form-control">
                                        <option value=""></option>
                                        <?php foreach ($get_period_code->result() as $row) { ?>
                                            <option value="<?php echo $row->period_code ?>">
                                                <?php echo $row->period_code; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="clearfix"></div><br>

                                <div class="col-md-2"><b>Supplier Name</b></div>
                                <div class="col-md-4">
                                    <select id="supplier_name" class="form-control">
                                        <!-- <option value="">None</option> -->
                                        <?php foreach ($get_supplier_name_list->result() as $row) { ?>
                                            <option value="<?php echo $row->get_get_supplier_guid ?>">
                                                <?php echo $row->supplier_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="clearfix"></div><br>

                                <!-- <div class="col-md-2"><b>Customer Name</b></div>
                                <div class="col-md-4">
                                    <select id="customer_name" class="form-control">
                                        <option value="">None</option>
                                        <?php foreach ($get_customer_name_list->result() as $row) { ?>
                                            <option value="<?php echo $row->acc_guid ?>">
                                                <?php echo $row->acc_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div> -->

                                <div class="clearfix"></div><br>

                                <div class="col-md-12">
                                    <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                                    <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">

                                    <button id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                                    <!-- an F5 function -->
                                    <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                                    <!-- an RESER function -->
                                    <button id="reset" class="btn btn-secondy"><i class="fa fa-repeat"></i> Reset</button>

                                </div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>EDI Record</b></h3> &nbsp;
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- <center>
                                <h2> EDI Record</h2>
                            </center> -->
                    <div class="box-body">
                        <div class="col-md-12"> <br>
                            <div class="card-body no-padding">
                                <table class="table table-striped table-bordered table-hover" id="tableaccepted">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Edi Batch No</th>
                                            <th>RefNo</th>
                                            <th>File Name</th>
                                            <th>Retailer Name</th>
                                            <th>Supplier Name</th>
                                            <th>Generated At</th>
                                            <th>Error Message</th>
                                            <th>Action</th>
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
    </div>
</div>


<script>
    let edi_batch_no = '';
    let status = '';
    let generate_date_from = '';
    let generate_date_to = '';
    let period_code = '';
    let supplier_name = '';
    let customer_name = '';
    $(document).ready(function() {

        main_table = function(edi_batch_no, status, generate_date_from, generate_date_to, period_code, get_get_supplier_guid, customer_guid) {

            if ($.fn.DataTable.isDataTable('#tableaccepted')) {
                $('#tableaccepted').DataTable().destroy();
            }

            var table;

            table = $('#tableaccepted').DataTable({
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "lengthMenu": [
                    [20, 35, 50, 1000000],
                    [20, 35, 50, "ALL"]
                ],
                "order": [
                    [6, "desc"]
                ],
                "columnDefs": [
                    { "width": "11%", "targets": 8 },
                    {
                    "targets": [8], //first column
                    "orderable": false, //set not orderable
                    },
                    // {"targets":[2],"className":"row_refno"},
                    // "createdRow": function(row){
                    //     $(row).find(".row_refno").each(function(){
                    //         $(this).attr("Show", this.innerText);
                    //     });
                    // }

                ],
                "ajax": {
                    "url": "<?php echo site_url('Edi/edi_log_list') ?>",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        'edi_batch_no': edi_batch_no,
                        'status': status,
                        'generate_date_from': generate_date_from,
                        'generate_date_to': generate_date_to,
                        'period_code': period_code,
                        'get_get_supplier_guid': get_get_supplier_guid,
                        'customer_guid': customer_guid,
                    },

                },
                "columns": [{
                        "data": "status"
                    },
                    {
                        "data": "edi_batch_no"
                    },

                    {
                        "data": "refno_data",render: function (data, type , row){

                            var element = '';
                            
                            
                            <?php if(!in_array('IAVA',$this->session->userdata('module_code')))
                            {
                            ?>
                                var element1 = data.split("]").join("").split("[").join("").split('"').join("").split(",").join("<br>"); 
                                var element2 = row['supplier_guid'];

                                if(element2 == '5FA503666DF011E887B5000D3AA2838A')
                                {
                                    element += '<span>'+element1+'</span>';
                                }
                                else
                                {
                                    element += '';
                                }
                                

                            <?php
                            }
                            else
                            {
                                ?>
                                var element1 = data.split("]").join("").split("[").join("").split('"').join("").split(",").join("<br>"); 

                                var element2 = row['supplier_guid'];

                                if(element2 == '5FA503666DF011E887B5000D3AA2838A')
                                {
                                    element += '<span>'+element1+'</span>';
                                }
                                else
                                {
                                    element += '';
                                }
                                //var element1 = data.split("]").join("").split("[").join("").split('"').join(""); //.split(",").join("<br>")
                                // var showChar = 100;
                                // var content = element1.replace(/,+/g, '').substring(1, element1.length-1);
                                // var c = content.substr(0, showChar);
                                // var h = content.substr(showChar-1, content.length - showChar);
                                // var content = element1.replace(/,+/g, '').substring(1, element1.length-1);

                                // var element = '<span id="outer" data-shrink="'+h+'" >"'+c+'"</span><span id="show">click</span>';

                                // var len = $('#outer').text();
                                // if(len.length > 20) {
                                // var txt = $('#outer').attr('data-shrink');
                                // console.log(txt);
                                // $('#outer').text(txt);
                                // $('#show').text('...');
                                // }

                                // $('#show').click(function() {
                                // var text = $('#outer').attr('title');
                                // console.log('text', text.length);
                                // $(this).text(text);
                                // $('#show').after('<a id="less"> Show less</a>');
                                // $('#outer').text('');
                                // });


                                // function someFunction() {
                                // console.log('test');
                                // $('#less').remove();
                                // var txt = $('#outer').attr('data-shrink');
                                // $('#show').text('');
                                // $('#outer').text(txt);
                                // $('#show').text('...');
                                // }
                                // var showChar = 100;
                                // var ellipsestext = "";
                                // var moretext = "more";
                                // var lesstext = "less";
                                // // var contentt = JSON.stringify(data);
                                
                                // var content = element1.replace(/,+/g, '').substring(1, element1.length-1);
                                
                                // // console.log(data); 
                                // if(content.length > showChar) {
        
                                //     var c = content.substr(0, showChar);
                                //     var h = content.substr(showChar-1, content.length - showChar);
        
                                //     var element = c + '<span class="morecontent"><span>' + h + '</span> <a href="" class="morelink less">' + moretext + '</a></span>';
        
                                //     //return html;
                                // }


                                // $(".morelink").click(function(){
                                //     if($(this).hasClass("less")) {
                                //         $(this).removeClass("less");
                                //         $(this).html(lesstext);
                                //     } else {
                                //         $(this).addClass("less");
                                //         $(this).html(moretext);
                                //     }
                                //     $(this).parent().prev().toggle();
                                //     $(this).prev().toggle();
                                //     return false;
                                // });
                                <?php
                            }
                            ?>

                            //element += '<span>'+element1+'</span>';

                            //console.log(element1.replace(/,+/g, '').substring(1, element1.length-1)); 

                            

                            return element;

                        }
                    },

                    {
                        "data": "file_name"
                    },

                    {
                        "data": "acc_name"
                    },
                    {
                        "data": "supplier_name"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "error_message_reason"
                    },
                    {
                        "data": "refno"
                    },
                ],
                dom: 'lBfrtip',

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]

            });
        }

        $(document).on('click', '#refnoList', function() {
            //function myFunction() {
            // alert($("#refnoList").data("refno"));
            // let refno = $("#refnoList").attr("refno");

            var modal = $("#medium-modal").modal();

            modal.find('.modal-title').html('EDI Batch Details');

            methodd = '';

            methodd += '<div class="row"> <div class="col-md-12"> <div class="box box-info"> <div class="box-body"> <table id="refnoTable" class="table table-bordered table-striped" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"><tr><th>Edi Batch No</th> <th>Retailer Name</th> <th>Refno</th> <th>Total Line</th> </tr> </table>  </div> </div> </div> </div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            var customer_guid = $(this).attr("acc_guid");
            var edi_batch_no = $(this).attr("edi_batch_no");
            var get_supplier_guid = $(this).attr("supplier_guid");

            $('#refnoTable').DataTable({
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "lengthMenu": [
                    [20, 35, 50, 1000000],
                    [20, 35, 50, "ALL"]
                ],
                "order": [
                    [0, "desc"]
                ],
                "sScrollY": "30vh",
                "sScrollX": "100%",
                "sScrollXInner": "100%",
                "ajax": {
                    "url": "<?php echo site_url('edi/edi_refno_list') ?>",
                    "type": "POST",
                    "data": {
                        'customer_guid': customer_guid,
                        'edi_batch_no': edi_batch_no,
                        'get_supplier_guid': get_supplier_guid,
                    }
                },
                "columns": [{
                        "data": "edi_batch_no"
                    },
                    {
                        "data": "acc_name"
                    },
                    {
                        "data": "refno"
                    },
                    {
                        "data": "total_line"
                    },
                ],
                dom: 'lBfrtip',

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]

            });

        });


        main_table(edi_batch_no, status, generate_date_from, generate_date_to, period_code, supplier_name, customer_name);

        $(document).on('click', '#download_copy_new', function(event){
            var guid = $(this).attr('guid');
            var customer_guid = $(this).attr('acc_guid');
            var get_edi_batch_no = $(this).attr('get_edi_batch_no');
            var get_status = $(this).attr('get_status');
            var get_file_name = $(this).attr('get_file_name');
            var dl_supplier_guid = $(this).attr('dl_supplier_guid');

            if(guid == '' || guid == null || guid == 'null')
            {
                alert('Invalid Download Process');
                return;
            }

            if(customer_guid == '' || customer_guid == null || customer_guid == 'null')
            {
                alert('Invalid Download Process');
                return;
            }

            if(get_edi_batch_no == '' || get_edi_batch_no == null || get_edi_batch_no == 'null')
            {
                alert('Invalid Download Process');
                return;
            }

            if(get_status == '' || get_status == null || get_status == 'null')
            {
                alert('Invalid Download Process');
                return;
            }

            if(get_file_name == '' || get_file_name == null || get_file_name == 'null')
            {
                alert('Invalid Download Process');
                return;
            }

            confirmation_modal('Download CSV File.');
            $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
                $.ajax({
                url:"<?php echo site_url('Edi/download_status') ?>",
                method:"POST",
                data:{guid:guid,customer_guid:customer_guid,get_edi_batch_no:get_edi_batch_no,get_status:get_status,dl_supplier_guid:dl_supplier_guid,get_file_name:get_file_name},
                beforeSend:function(){
                    $('.btn').button('loading');
                },
                success:function(data)
                {
                    json = JSON.parse(data);

                    if(json.para1 == 'false')
                    {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    alert(json.msg);
                    //alert(json.msg.replace(/\\n/g,"\n"));
                    }
                    else
                    {
                    $('.btn').button('reset');
                    $('#alertmodal').modal('hide');
                    alert(json.msg);
                    //alert(json.msg.replace(/\\n/g,"\n"));

                    if(json.dl_path != '' && json.dl_path != null && json.dl_path != 'null')
                    {  
                        var form = document.createElement('a');
                        form.href = json.dl_path;
                        form.download = json.dl_path;
                        document.body.appendChild(form);
                        form.click();
                    }
  
                    setTimeout(function() {
                        location.reload();
                    }, 300); 
                    }
                
                }//close success
                });//close ajax 
            });//close document yes click
        });//close mouse click

        $(document).on('click', '#view_pdf_btn', function(event){
            var post_refno = $(this).attr('post_refno');

            if(post_refno == '' || post_refno == null || post_refno == 'null')
            {
                alert('Invalid Download Process');
                return;
            }

            var modal = $("#medium-modal").modal();

            modal.find('.modal-title').html('View PDF');

            methodd = '';

            methodd +='<div class="col-md-12">';

            methodd += '<div class="col-md-12"><label>RefNo</label> <select class="form-control" name="select_refno" id="select_refno"> </select> </div>';

            methodd += '<div class="col-md-12"> <br/> <span id="append_pdf"></span></div>';

            methodd += '</div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function() {
                if(post_refno != '')
                {
                    get_refno_data = JSON.parse(post_refno); 

                    //console.log(get_refno_data); die;
                    refno_selection = '';
                    refno_selection += '<option value="" selected>-Select-</option> ';
                        Object.keys(get_refno_data).forEach(function(key) {
  
                            refno_selection += '<option value="'+get_refno_data[key]+'" >'+get_refno_data[key]+'</option>';
                        });
                    $('#select_refno').select2().html(refno_selection);

                }
                else
                {
                    $('#select_refno').select2().html('<option value="" disabled>RefNo Not Found</option>');
                }

                $('#select_refno').change(function(){

                    var type_val = $('#select_refno').val();

                    if(type_val != '')
                    {
                        $.ajax({
                        url : "<?php echo site_url('Edi/retrieve_pdf_path'); ?>",
                        method:"POST",
                        data:{type_val:type_val},
                        success:function(result)
                        {
                            json = JSON.parse(result); 
                            
                            //$('#append_pdf').html(json.filename);
                            if(json.count_archive == 0)
                            {
                                $('#append_pdf').html('<embed src="'+json.filename+'" width="100%" height="500px" style="border: none;" toolbar="1" id="pdf_view"/>');
                            }
                            else
                            {
                                $('#append_pdf').html('Archive Document.');
                            }
                        }
                        });
                    }
                    else
                    {
                        $('#append_pdf').html('PDF NOT FOUND. Please contact support.');
                    }
                    
                    });//close selection
            }, 300);
        });//close mouse click
        
    });


    $('#search').click(function() {

        edi_batch_no = $('#edi_batch_no').val();
        status = $('#status').val();
        generate_date_from = $('#generate_date_from').val();
        generate_date_to = $('#generate_date_to').val();
        period_code = $('#period_code').val();
        supplier_name = $('#supplier_name').val();
        customer_name = $('#customer_name').val();
        
        main_table(edi_batch_no, status, generate_date_from, generate_date_to, period_code, supplier_name, customer_name);

    })

    $('#reset').click(function() {

        edi_batch_no = '';
        status = '';
        generate_date_from = '';
        generate_date_to = '';
        period_code = '';
        supplier_name = '';
        customer_name = '';

        main_table(edi_batch_no, status, generate_date_from, generate_date_to, period_code, supplier_name, customer_name);

    });


    // select date from
    $(function() {
        $('input[name="generate_date_from"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
        });
        $(this).find('[name="generate_date_from"]').val("");
    });

    // select date to
    $(function() {
        $('input[name="generate_date_to"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
        });
        $(this).find('[name="generate_date_to"]').val("");
    });

    function expiry_clear() {
        $(function() {
            $(this).find('[name="generate_date_from"]').val("");
            $(this).find('[name="generate_date_to"]').val("");
        });
    }

    function CompareDate() {
        var dateOne = $('input[name="generate_date_from"]').val(); //Year, Month, Date
        var dateTwo = $('input[name="generate_date_to"]').val(); //Year, Month, Date
        if (dateOne > dateTwo) {
            alert("Expiry To : " + dateTwo + " Cannot Be a date before " + dateOne + ".");
            $('#search').attr('disabled', 'disabled');
        } else {
            $('#search').removeAttr('disabled');
        }

    }
</script>