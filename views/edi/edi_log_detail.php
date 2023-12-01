<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
    <div class="container-fluid">
        <div class="row">
            <!-- <div class="pull-right box-tools">

                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                    Bulk Download <i class="fa fa-angle-double-down"></i>
                </button>
            </div> -->
            <br>
            <div class="col-md-12">
                <!-- <a class="btn btn-app" href="<?php echo site_url($_SESSION['frommodule']) ?>?loc=<?php echo $_REQUEST['loc']; ?>&p_f=&p_t=&e_f=&e_t=&r_n=">
                    <i class="fa fa-search"></i> Browse
                </a>
                <a class="btn btn-app" href="<?php echo site_url('login_c/location') ?>">
                    <i class="fa fa-bank"></i> Outlet
                </a> -->
                <button title="Regenerate" id="submit" type="button" class="btn btn-app" style="color:#008D4C" data-toggle="modal" data-target="#confirm">
                    <i class="fa fa-refresh"></i>Change Edi Status
                </button>
                <button title="view_all" id="view_all" type="button" class="btn btn-app">
                    <i class="fa fa-list"></i>View All
                </button>
                <!-- <a class="btn btn-app pull-right" style="color:#000000" onclick="bulk_print()">
                    <i class="fa fa-print"></i> Print
                </a> -->
            </div>
        </div>
            <!-- filter -->
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

                                    <div class="col-md-2"><b>PO Ref No</b></div>
                                    <div class="col-md-4">
                                        <input id="po_num" name="po_num" type="text" autocomplete="off" class="form-control pull-right">
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div class="col-md-2"><b>Edi Status</b></div>
                                    <div class="col-md-4">
                                        <select id="edi_status" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($get_edi_status->result() as $row) { ?>
                                                <option value="<?php echo $row->status ?>">
                                                    <?php echo $row->status; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div class="col-md-2"><b>PO Status</b></div>
                                    <div class="col-md-4">
                                        <select id="po_status" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($po_status->result() as $row) { ?>
                                                <option value="<?php echo $row->code ?>">
                                                    <?php echo $row->reason; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div class="col-md-2"><b>PO Date Range<br>(YYYY-MM-DD)</b></div>
                                    <div class="col-md-4">
                                        <input required id="daterange" name="daterange" type="datetime" class="form-control pull-right" id="reservationtime" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-danger" onclick="date_clear()">Clear</a>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div class="col-md-2"><b>Expired Date From<br>(YYYY-MM-DD)</b></div>
                                    <div class="col-md-2">
                                        <input id="expiry_from" name="expiry_from" type="datetime" value="" readonly class="form-control pull-right">
                                    </div>
                                    <div class="col-md-2"><b>Expired Date To<br>(YYYY-MM-DD)</b></div>
                                    <div class="col-md-2">
                                        <input id="expiry_to" name="expiry_to" type="datetime" class="form-control pull-right" readonly value="" onchange="CompareDate()">
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

                                    <div class="col-md-12">
                                        <input type="hidden" name="current_location" class="form-control pull-right" value="<?php echo $_REQUEST['loc']; ?>">
                                        <input type="hidden" name="frommodule" class="form-control pull-right" value="<?php echo $_SESSION['frommodule'] ?>">

                                        <button id="search" class="btn btn-primary" onmouseover="CompareDate()"><i class="fa fa-search"></i> Search</button>
                                        <!-- an F5 function -->
                                        <!-- <a href="" onclick="window.location.reload(true)" class="btn btn-default" ><i class="fa fa-refresh"></i> Refresh</a> -->
                                        <!-- an RESER function -->
                                        <button id="reset" class="btn btn-secondy"><i class="fa fa-repeat"></i> Reset</button>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>EDI Detail</b></h3> &nbsp;
                            <!-- <button id="submit">submit</button> -->
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <!-- <center>
                                <h2> EDI Record</h2>
                            </center> -->
                        <div class="box-body">
                            <!-- <div class="col-md-12"> <br> -->
                                <div class="card-body no-padding">
                                    <table class="table table-striped table-bordered table-hover" id="edi_detail_table">
                                        <thead>
                                            <tr>
                                                <th>PO Refno</th>
                                                <th>Outlet</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>PO Date</th>
                                                <th>Delivery Date</th>
                                                <th>Expiry Date</th>
                                                <th>Amount</th>
                                                <th>Tax</th>
                                                <th>Total Incl Tax</th>
                                                <th>Status</th>
                                                <th>Reject Remark</th>
                                                <th>Export Status</th>
                                                <th>Edi Batch No</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


<script>
    let refno = '';
    let edi_status = '';
    let po_status = '';
    let date_range = '';
    let expiry_from = '';
    let expiry_to = '';
    let period_code = '';
    let supplier_guid = '';
    let customer_guid = '';
    let user_guid = '<?php echo $user_guid; ?>';

    $(document).ready(function() {
        main_table = function(refno, edi_status, po_status, date_range, expiry_from, expiry_to, period_code, supplier_guid, customer_guid) {
            if ($.fn.DataTable.isDataTable('#edi_detail_table')) {
                $('#edi_detail_table').DataTable().destroy();
            }

            var table;

            table = $('#edi_detail_table').DataTable({
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
                    "url": "<?php echo site_url('Edi/edi_detail_table') ?>",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        'customer_guid': (customer_guid == '') ? '<?php echo $_REQUEST['guid'] ?>' : customer_guid,
                        'refno': (refno == '') ? '<?php echo $refno ?>' : "'" + refno + "'",
                        'edi_status': edi_status,
                        'po_status': po_status,
                        'date_range': date_range,
                        'expiry_from': expiry_from,
                        'expiry_to': expiry_to,
                        'period_code': period_code,
                        'supplier_guid': supplier_guid,
                    }
                },
                "columns": [{
                        "data": "RefNo"
                    },
                    {
                        "data": "loc_group"
                    },
                    {
                        "data": "SCode"
                    },

                    {
                        "data": "SName"
                    },
                    {
                        "data": "PODate"
                    },
                    {
                        "data": "DeliverDate"
                    },
                    {
                        "data": "expiry_date"
                    },
                    {
                        "data": "amount"
                    },
                    {
                        "data": "include_tax"
                    },
                    {
                        "data": "amount"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "rejected_remark"
                    },
                    {
                        "data": "export_status"
                    },
                    {
                        "data": "edi_batch_no"
                    },
                    {
                        "data": "action"
                    },
                ],
                dom: 'lBfrtip',

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]

            });
        }

        main_table(refno, status, edi_status, po_status, date_range, expiry_from, expiry_to, period_code, supplier_guid, customer_guid);

        $(document).on('click', '#submit', function() {
            // alert('sdsad');
            // let refno = $('#index').attr('refno[]');
            // console.log(refno);
            let details = [];
            $('#edi_detail_table tbody tr').each(function() {
                if ($(this).closest('tr').find('td').find('input[type="checkbox"]').is(':checked')) {
                    //  $shoot_link = shoot_link + 1;
                    let refno = $(this).closest('tr').find('td').find('input[type="checkbox"]').attr('value');
                    details.push(
                        refno
                    );
                }
            });
            if (details.length > 0) {
                // console.log(details);
                confirmation_modal('Are You Sure want to change EDI status?');
                $(document).off('click', '#confirmation_yes').on('click', '#confirmation_yes', function(){
                    $.ajax({
                        url: "<?php echo site_url('Edi/reflag_edi_export_status') ?>",
                        method: "POST",
                        data: {
                            refno: details,
                            type: 'PO',
                            customer_guid: (customer_guid == '') ? '<?php echo $_REQUEST['guid'] ?>' : customer_guid,
                            user_guid: user_guid,
                        },
                        beforeSend: function() {
                            // $('.btn').button('loading');
                        },
                        success: function(data) {
                            json = JSON.parse(data);
                            // console.log(json.para1);

                            if (json.status == 'false') {

                                alert(json.message.replace(/\\n/g, "\n"));
                                $('.btn').button('reset');

                            } else {
                                alert(json.message.replace(/\\n/g, "\n"));
                                setTimeout(function() {
                                    $('.btn').button('reset');
                                    //window.location = window.location.href + "&openModal=1";
                                }, 300);
                                location.reload();
                            } //close else
                        } //close success
                    }); //close ajax
                });//close document yes click
            } else {
                //console.log('emoty');
                alert('Please Select one data to proceed.');
                return;
            }

        })

        $(document).on('click', '#view_all', function() {

            var modal = $("#medium-modal").modal();

            modal.find('.modal-title').html('Filter');

            methodd = '';

            methodd += '<div class="row"><div class="col-md-2"><b>Supplier Name</b></div> <div class="col-md-10"> <select id="supplier_name" class="form-control"> <option value="">None</option> <?php foreach ($get_supplier_name_list->result() as $row) { ?> <option value="<?php echo $row->supplier_group_name ?>"> <?php echo $row->supplier_name; ?></option> <?php } ?> </select> </div> <div class="clearfix"></div><br> <div class="col-md-2"><b>Customer Name</b></div> <div class="col-md-10"> <select id="customer_name" class="form-control"> <option value="">None</option> <?php foreach ($get_customer_name_list->result() as $row) { ?> <option value="<?php echo $row->acc_guid ?>"> <?php echo $row->acc_name; ?></option> <?php } ?> </select> </div> <div class="clearfix"></div><br></div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"> <button id="submit_view_all" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-search" ></i> Search</button><input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            $('#submit_view_all').click(function() {

                supplier_guid = $('#supplier_name').val();
                customer_guid = $('#customer_name').val();
                console.log(refno);
                main_table(refno, edi_status, po_status, date_range, expiry_from, expiry_to, period_code, supplier_guid, customer_guid);

            });
        });

    });

    $('#search').click(function() {

        refno = $('#po_num').val();
        edi_status = $('#edi_status').val();
        po_status = $('#po_status').val();
        date_range = $('#daterange').val();
        expiry_from = $('#expiry_from').val();
        expiry_to = $('#expiry_to').val();
        period_code = $('#period_code').val();
        // supplier_guid = $('#supplier_name').val();
        // customer_guid = $('#customer_name').val();
        // console.log(refno);
        main_table(refno, edi_status, po_status, date_range, expiry_from, expiry_to, period_code, supplier_guid, customer_guid);

    });

    $('#reset').click(function() {

        refno = '';
        edi_status = '';
        po_status = '';
        date_range = '';
        expiry_from = '';
        expiry_to = '';
        period_code = '';
        supplier_guid = '';
        customer_guid = '';
        // console.log(refno);
        main_table(refno, edi_status, po_status, date_range, expiry_from, expiry_to, period_code, supplier_guid, customer_guid);

    });



    // select date from
    $(function() {
        $('input[name="expiry_from"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
        });
        $(this).find('[name="expiry_from"]').val("");
    });
    // select date range
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        //$('#daterange').data('daterangepicker').setStartDate('<?php echo date('Y-m-d', strtotime('-7 days')) ?>');
        //$('#daterange').data('daterangepicker').setEndDate('<?php echo date('Y-m-d') ?>');
        $(this).find('[name="daterange"]').val("");
    });
    // select date to
    $(function() {
        $('input[name="expiry_to"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
        });
        $(this).find('[name="expiry_to"]').val("");
    });

    function date_clear() {
        $(function() {
            $(this).find('[name="daterange"]').val("");
        });
    }

    function expiry_clear() {
        $(function() {
            $(this).find('[name="expiry_from"]').val("");
            $(this).find('[name="expiry_to"]').val("");
        });
    }

    function CompareDate() {
        var dateOne = $('input[name="expiry_from"]').val(); //Year, Month, Date
        var dateTwo = $('input[name="expiry_to"]').val(); //Year, Month, Date
        if (dateOne > dateTwo) {
            alert("Expiry To : " + dateTwo + " Cannot Be a date before " + dateOne + ".");
            $('#search').attr('disabled', 'disabled');
        } else {
            $('#search').removeAttr('disabled');
        }

    }
</script>