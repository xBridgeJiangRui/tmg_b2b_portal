<style>
/*.content-wrapper{
  min-height: 850px !important; 
}*/
</style>
<div class="content-wrapper" style="min-height: 525px; text-align: justify;">
    <div class="container-fluid">
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
                                <div class="col-md-2"><b>Refno</b></div>
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
                                    <select id="supplier_guid" class="form-control select2">
                                        <option value="" disabled selected="">-SUPPLIER NAME-</option>
                                        <?php foreach ($get_supplier_name_list->result() as $row) { ?>
                                            <option value="<?php echo $row->supplier_guid ?>">
                                                <?php echo $row->supplier_name; ?> - <?php echo $row->supplier_group_name; ?></option>
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
                        <h3 class="box-title"><b>EDI GRN Record</b></h3> &nbsp;
                        <div class="box-tools pull-right">
                            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="box-body">
                        <table class="table table-bordered table-hover" id="tableaccepted"  border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                          <thead style="white-space: nowrap;">
                            <tr>
                                <th>Status</th>
                                <th>Message</th>
                                <th>Batch No</th>
                                <th>RefNo</th>
                                <th>Supplier Name</th>
                                <th>File Name</th>
                                <th>Generated At</th>
                                <th>Updated At</th>
                                <th>Remark</th>
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


<script>
    let edi_batch_no = '';
    let status = '';
    let generate_date_from = '';
    let generate_date_to = '';
    let period_code = '';
    let supplier_guid = '';
    let customer_name = '';
    let type = 'GRN';
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
                "columnDefs": [
                {"targets": 8 ,"orderable": false},
                { "width": "8%", "targets": 0 }
                ],
                "sScrollY": "50vh", 
                "sScrollX": "100%", 
                "sScrollXInner": "100%", 
                "bScrollCollapse": true,
                "ajax": {
                    "url": "<?php echo site_url('Edi/edi_grn_tb') ?>",
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
                        'type': type,
                    },

                },
                "columns": [
                    {
                        "data": "status"
                    },
                    {
                        "data": "error_message_reason"
                    },
                    {
                        "data": "edi_batch_no"
                    },
                    {
                        "data": "refno"
                    },
                    {
                        "data": "supplier_name"
                    },
                    {
                        "data": "file_name"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "updated_at"
                    },
                    {
                        "data": "guid"
                    },
                ],
                //dom: 'lBfrtip',
                dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
                buttons: [
                    'excel'
                ],
                "initComplete": function( settings, json ) {
                // setTimeout(function(){
                  //interval();
                // },300);
              }
            });
        }

        main_table(edi_batch_no, status, generate_date_from, generate_date_to, period_code, supplier_guid, customer_name);

        $(document).on('click', '#remarktb', function() {

            var guid = $(this).attr("guid");

            var modal = $("#medium-modal").modal();

            modal.find('.modal-title').html('Remark Details');

            methodd = '';

            methodd += '<div class="row"> <div class="col-md-12"> <div class="box box-info"> <div class="box-body"> <table id="refnoTable" class="table table-bordered table-hover" width="100%" cellspacing="0"> <thead style="white-space: nowrap;"><tr><th>No</th> <th>Message</th> <th>Remark</th> </tr> </table>  </div> </div> </div> </div>';

            methodd_footer = '<p class="full-width"><span class="pull-right"> <input name="sendsumbit" type="button" class="btn btn-default" data-dismiss="modal" value="Close"> </span></p>';

            modal.find('.modal-footer').html(methodd_footer);
            modal.find('.modal-body').html(methodd);

            setTimeout(function(){

                if ($.fn.DataTable.isDataTable('#edit_table')) {
                    $('#edit_table').DataTable().destroy();
                }

                $('#refnoTable').DataTable({
                    "scrollX": true,
                    "processing": true,
                    "serverSide": true,
                    "ordering": false,
                    "lengthMenu": [
                        [20, 35, 50, 1000000],
                        [20, 35, 50, "ALL"]
                    ],
                    "order": [
                        [0, "asc"]
                    ],
                    "sScrollY": "30vh",
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true,
                    "ajax": {
                        "url": "<?php echo site_url('edi/edi_grn_remark_tb') ?>",
                        "type": "POST",
                        "data": {
                            'guid': guid,
                        }
                    },
                    "columns": [
                        { "data" : "empty" ,render:function( data, type, row , meta ){

                          var element = '';
                          var element1 = row['doc_table'];

                          element = meta.row + meta.settings._iDisplayStart + 1;


                          return element;
                        }},
                        {
                            "data": "message"
                        },
                        {
                            "data": "remark"
                        },
                    ],
                    dom: 'lrtip',
                    "initComplete": function( settings, json ) {
                        setTimeout(function(){
                          interval();
                        },300);
                    }
                });
            },300);
        });

    });


    $('#search').click(function() {

        edi_batch_no = $('#edi_batch_no').val();
        status = $('#status').val();
        generate_date_from = $('#generate_date_from').val();
        generate_date_to = $('#generate_date_to').val();
        period_code = $('#period_code').val();
        supplier_guid = $('#supplier_guid').val();
        customer_name = $('#customer_name').val();

        main_table(edi_batch_no, status, generate_date_from, generate_date_to, period_code, supplier_guid, customer_name);

    })

    $('#reset').click(function() {

        edi_batch_no = '';
        status = '';
        generate_date_from = '';
        generate_date_to = '';
        period_code = '';
        supplier_guid = '';
        customer_name = '';

        main_table(edi_batch_no, status, generate_date_from, generate_date_to, period_code, supplier_guid, customer_name);

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