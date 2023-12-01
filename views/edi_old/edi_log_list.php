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
                                            <option value="<?php echo $row->status ?>">
                                                <?php echo $row->status; ?></option>
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
                                        <option value="">None</option>
                                        <?php foreach ($get_supplier_name_list->result() as $row) { ?>
                                            <option value="<?php echo $row->supplier_group_name ?>">
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

        main_table = function(edi_batch_no, status, generate_date_from, generate_date_to, period_code, supplier_guid, customer_guid) {

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
                    [0, "desc"]
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
                        'supplier_guid': supplier_guid,
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
                        'edi_batch_no': edi_batch_no
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