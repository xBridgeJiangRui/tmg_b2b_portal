<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="container-fluid">
<br>
<script src="<?php echo base_url('asset/dist/js/Chart.js');?>"></script>

  
    <?php if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
<div class="row">
      
 <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $all; ?></h3>

              <p>All Tickets</p>
            </div>
            <div class="icon">
              <i class="fa fa-ticket"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $Closed; ?></h3>

              <p>Solved</p>
            </div>
            <div class="icon">
              <i class="fa fa-check"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $In_Progress; ?></h3>

              <p>In-Progress</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $New; ?></h3>

              <p>New</p>
            </div>
            <div class="icon">
              <i class="fa fa-comment-o"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
      <!-- <div class="col-md-4">
  <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Categories</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-sm-12 graph_wrap">
          <canvas id="Member_Movement"></canvas>
        </div>
      </div>

      
  </div>
</div> -->

<div class="col-md-12">
  <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Ticket (Previous Year)</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-sm-12 graph_wrap">
          <canvas id="ticket_in_last_year" height="67.5"></canvas>        
        </div>

      </div>
      
  </div>
</div>

<div class="col-md-12">
  <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Sub Categories</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-sm-12 graph_wrap">
          <canvas id="topic_bar" height="69"></canvas>        
        </div>

      </div>

      <div class="box-footer no-border">
              <div class="row">
                <?php foreach ($topic1->result() as $key) { ?>
                <div class="col-md-2 text-center" style="border-right: 1px solid #f4f4f4">
                  <canvas id="<?php echo $key->name ?>"></canvas>

                  <div class="knob-label" style="font-weight: bold"><?php echo $key->name ?></div>
                </div>
                <?php } ?>
                <!-- ./col -->

                <!-- ./col -->
              </div>
              <!-- /.row -->
            </div>
      
  </div>
</div>
</div>

    <?php } ?>
    <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Ticket Status</h3>
          <div class="box-tools pull-right">
            <?php if ($_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
            <button title="" id="search_message_details" type="button" class="btn btn-xs btn-primary"   
            ><i class="fa fa-search" aria-hidden="true"></i>Search
            </button>
            <?php } ?>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" id="acc_concepts">
          <div id="">
          
                  <table id="list" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Ticket Number</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Ticket Status</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <?php if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
                        <th>Code</th>
                        <th>Supplier Name</th>
                        <th>Assigned</th>
                        <?php } ?>
                        <th>Seq</th>
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

<div class="modal fade" id="search_modal" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Search Ticket Message <small id="search_count"></small></h4>
      </div>
      <div class="modal-body">
        <p>One fine body…</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="edit_ticket_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h3 class="modal-title">Ticket Edit</h3>
            </div>
            <div class="modal-body form">
                <form action="" method="" id="form" class="form-horizontal">
                      <div class="form-body">
                        <input type="hidden" name="ticket_guid" id="ticket_guid" value="">
                        <div class="form-group">
                        <label class="control-label col-md-3">Category<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <select id="ticket_category" name="ticket_category" class="form-control">
                                <?php foreach($category->result() as $row) { ?>
                                    <option value=<?php echo $row->t_topic_guid; ?>><?php echo $row->name ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-md-3">Sub Category<span style="color:red">*</span> </label>
                            <div class="col-md-9">
                                <select id="ticket_sub_category" name="ticket_sub_category" class="form-control">
                                <?php foreach($sub_category->result() as $row) { ?>
                                    <option value=<?php echo $row->t_sub_topic_guid; ?>><?php echo $row->name ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>                        

                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" id="edit_ticket_save" class="btn btn-sm btn-primary">Save</button>
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                  </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
  //for minimize and maximize box 

$(document).ready(function(){
$(document).on('click','#edit_ticket',function(){
  var ticket_guid = $(this).attr('ticket_guid');
  var category_guid = $(this).attr('category_guid');
  var sub_category = $(this).attr('sub_category_guid');

  $('#ticket_guid').val(ticket_guid);
  $('#ticket_category').val(category_guid);
  $('#ticket_sub_category').val(sub_category);
});

$(document).on('click','#edit_ticket_save',function(){
  var ticket_guid = $('#ticket_guid').val();
  var category_guid = $('#ticket_category').val();
  var sub_category = $('#ticket_sub_category').val();
  // alert(ticket_guid+' - '+category_guid+' - '+sub_category);die;
  $.ajax({
        url:"<?php echo site_url('Ticket/edit_ticket'); ?>",
        method:"POST",
        data:{ticket_guid:ticket_guid,category_guid:category_guid,sub_category:sub_category},
        success:function(data){
          if(data > 0)
          {
            alert('Record Updated .');
            table.ajax.reload();
            $('#edit_ticket_modal').modal('hide');
          }
          else
          {
            alert('Record Not Update !');
          }
        }
   });

});

tablelist = function(){

   // var table;
     table = $('#list').DataTable({ 
      "serverSide": true, 
      <?php if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
        "order": [[9,'desc'],[4 , 'desc']], 
        <?php } else { ?>
          "order": [[6,'desc'],[4 , 'desc']], 

        <?php } ?>

      <?php if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
        "columnDefs": [{ "visible": false, "targets": 9 }],
        <?php } else { ?>
        "columnDefs": [{ "visible": false, "targets": 6 },{ "visible": false, "targets": 7 }],

        <?php } ?>
      "ordering":true,
      "ajax": {
          "url" : "<?php echo site_url('Ticket/ticket_table'); ?>",
          beforeSend : function() {

           },
           complete: function() {

           },   
          "type": "POST"
      },
      "sScrollY": "70vh", 
      "scrollCollapse": true,
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      'lengthMenu'  : [ [10, 25, 50, 99999999], [10, 25, 50, "ALL"] ],
      "bScrollCollapse": true,
      // "pagingType": "simple",
      'colReorder': true,
       "processing": true,
      "columns": [
        { data: "ticket_number"},
        { data: "name"},
        { data: "sub_name"},
        { data: "ticket_status",render: function ( data, type, row ) {
          if (data == 'New') { word = '<b style="color:red">'+data+'</b>' } else { word = data }
          return word;
        }},
        { data: "created_at"},
        { data: "user_name"},
        <?php if ( $_SESSION['user_group_name'] == 'SUPER_ADMIN') { ?>
        { data: "supplier_group_name"},
        { data: "supplier_name"},
        { data: "assigned_name"},
        <?php } ?>
        { data: "seq"},
        { data: "action"},
        ], 
      "oLanguage": {
        "sLengthMenu": "Show _MENU_ ",
        "sInfoFiltered": " - From _MAX_ records"
      },
      dom: "<'row'<'col-sm-4'l>" + "<'col-sm-8'f>>" +'rtip',
    });

     //search after complete key in value
    $('div.dataTables_filter input').off('keyup.DT input.DT');
 
    var searchDelay = null;
     
    $('div.dataTables_filter input').on('keyup', function() {
        var search = $(this).val();
     
        clearTimeout(searchDelay);
     
        searchDelay = setTimeout(function() {
            if (search != null) {
                table.search(search).draw();
            }
        }, 1400);
    });//close delay

}//close table function

tablelist();




   var searchDelay = null;
     
    $(document).on('keyup', '#search_message_input', function() {

    search_value = this.value;

 
    clearTimeout(searchDelay);
 
    searchDelay = setTimeout(function() {
        if (search_value != null) {
              
              $.ajax({
                        url:"<?php echo site_url('Ticket/search_message_result');?>",
                        method:"POST",
                        data:{search_value:search_value},

                        beforeSend : function() {
    
                        },
                        complete: function() {
      
                        },       
                        success:function(data)
                        { 

                            result = JSON.parse(data);
                            html = ''

                            for(i = 0; i < result['search_result'].length; i++)
                            {
                              

                              html += '<blockquote style="overflow: auto;"> <a title="'+result['search_result'][i].ticket_number+'" href="<?php echo site_url('Ticket/details?t_g=');?>'+result['search_result'][i].ticket_guid+'"><p>'+result['search_result'][i].ticket_number+'</p></a> <small>'+result['search_result'][i].messages+' - <cite title="Source Title">'+result['search_result'][i].created_at+'</cite></small> </blockquote> ';


                            }

                            
                            $('#search_message_result').html(html)


                            count_message = "Result: <b>"+result['search_count']+"</b>"

                            $('#search_count').html(count_message)


                        }//close succcess
                      });//close ajax


        }
    }, 1400);
});//close delay



})
</script>


<script type="text/javascript">
  
$("#search_message_details").click(function(){

  modal = $('#search_modal').modal();

  html = ''

  html += '<input type="text" class="form-control" id="search_message_input" placeholder="Search Message"> ';

  html += '<span id="search_message_result"></span>';

  modal.find('.modal-body p').html(html)

  modal.find('#search_count').html('')


})




</script>



<script type="text/javascript">

<?php 

$backgroundColor=array('rgba(255, 99, 132, 0.2)',
'rgba(54, 162, 235, 0.2)',
'rgba(255, 206, 86, 0.2)',
'rgba(75, 192, 192, 0.2)',
'rgba(153, 102, 255, 0.2)',
'rgba(245, 123, 36, 0.2)',
'rgba(0, 142, 37, 0.2)'); 

//shuffle($backgroundColor);

$borderColor=array('rgba(255,99,132,1)',
'rgba(54, 162, 235, 1)',
'rgba(255, 206, 86, 1)',
'rgba(75, 192, 192, 1)',
'rgba(153, 102, 255, 1)',
'rgba(245, 123, 36, 1)',
'rgba(0, 142, 37, 1)'); 

//shuffle($borderColor);

          ?>

data = {
    datasets: [{
        data: [

        <?php 

        foreach ($Topic as $key) {
          echo "'".$key->topic_count."',";
        }

         ?>

        ],

        backgroundColor:[<?php foreach ($backgroundColor as $key) {
          echo "'".$key."',";
        } ?>],

        borderColor:[<?php foreach ($borderColor as $key) {
          echo "'".$key."',";
        } ?>],
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
    <?php

    foreach ($Topic as $key) {
      echo "'".$key->name."',";
    }

    ?>
    ],



};

var donutEl = document.getElementById("Member_Movement").getContext("2d");

var myDoughnutChart = new Chart(donutEl, {
    type: 'doughnut',
    data: data,
    color:"#F7464A",

});

</script>
<script type="text/javascript">


var MONTHS = [

  <?php for ($i = 1; $i <= 12; $i++) {
    $months = date("M", strtotime( date( 'Y-m-01' )." +$i months"));
    echo " ' ".$months." ' ,";
} ?>

];
var config = {
  type: 'line',
  data: {
    labels: MONTHS,
    datasets: [{
      label: "Ticket Per Month",
      backgroundColor: 'rgba(255, 206, 86, 0.2)',
      borderColor: 'rgba(255, 206, 86, 1)',
      data: [
        <?php foreach ($last_12_month_ticket as $key) {
          echo $key->Count.',';
        } ?>
      ],
      fill: true,
    }]
  },
  options: {
    responsive: true,
    title: {
      display: true,
    },
    tooltips: {
      mode: 'label',
    },
    hover: {
      mode: 'nearest',
      intersect: true
    },
    scales: {
      xAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: 'Month'
        }
      }],
      yAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: 'Ticket'
        },
        ticks: {
        beginAtZero: true,
        userCallback: function(label, index, labels) {
            // when the floored value is the same as the value we have a whole number
            if (Math.floor(label) === label) {
                return label;
            }
          }
        }
      }]
    }
  }
};


var ctx = document.getElementById("ticket_in_last_year").getContext("2d");
window.myLine = new Chart(ctx, config);

</script>

<script type="text/javascript">

var ctx = document.getElementById("topic_bar").getContext("2d");
var myChart = new Chart(ctx, {
  showTooltips: false,
  type: 'bar',
  data: {
    labels: [

    <?php foreach ($topic_bar->result() as $value) { 

        echo "'".$value->name."',";

      } ?>

    ],
    datasets: [{
      label: 'Topic',

      borderWidth: 1,
                hoverBorderWidth: 2,
                hoverBorderColor: 'lightgrey',
      backgroundColor: [<?php foreach ($backgroundColor as $key) {
          echo "'".$key."',";
        } ?>],

      borderColor: [<?php foreach ($borderColor as $key) {
          echo "'".$key."',";
        } ?>],
      data: [

      <?php foreach ($topic_bar->result() as $value) { 

        echo $value->topic_count.",";

      } ?>

      ]

    }],
  },
  options: {
    responsive: true,
    title: {
      display: true,
    },
    tooltips: {
      mode: 'label',
    },
    hover: {
      mode: 'nearest',
      intersect: true
    },
    scales: {
      xAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: 'Topic'
        }
      }],
      yAxes: [{
        display: true,
        scaleLabel: {
          display: true,
          labelString: 'Ticket'
        },
        ticks: {
        beginAtZero: true,
        userCallback: function(label, index, labels) {
            // when the floored value is the same as the value we have a whole number
            if (Math.floor(label) === label) {
                return label;
            }
          }
        }
      }]
    }
  }
});

</script>

<script type="text/javascript">
<?php foreach ($topic1->result() as $key) { ?>

var data = {
  labels: [
   <?php

   $topic_guid = $key->topic_guid;

   $subtopic = $this->db->query("SELECT COUNT(*) AS count, b.name FROM ticket a INNER JOIN ticket_sub_topic b ON a.`sub_topic_guid` = b.`t_sub_topic_guid`  WHERE a.topic_guid ='$topic_guid' GROUP BY a.topic_guid , a.`sub_topic_guid`")->result();

   foreach ($subtopic as $key1) {
     echo  '"'.$key1->name.'",';
   }

   
         //echo '"others"';
  ; ?>
  ],
  datasets: [
    {
      data: [<?php 

        foreach ($subtopic as $key1) {
           echo  '"'.$key1->count.'",';
         }

         //echo '"'.$key->others.'"';


       ?>],

<?php 



?>

backgroundColor:[<?php foreach ($backgroundColor as $key3) {
          echo "'".$key3."',";
        } ?>],

        borderColor:[<?php foreach ($borderColor as $key3) {
          echo "'".$key3."',";
        } ?>],
      hoverBackgroundColor: [
        <?php 

        foreach ($backgroundColor as $key3) {
          echo "'".$key3."',";
        } 

         ?>
      ]
    }]
};

var promisedDeliveryChart = new Chart(document.getElementById('<?php echo $key->name ?>'), {
  type: 'doughnut',
  data: data,
  options: {
    responsive: true,
    legend: {
      display: false
    }
  },
  plugins: [{
        beforeDraw: function(chart, options) {
            var width = chart.chart.width,
                  height = chart.chart.height,
                  ctx = chart.chart.ctx;

              ctx.restore();
              var fontSize = (height / 114).toFixed(2);
              ctx.font = fontSize + "em sans-serif";
              ctx.textBaseline = "middle";

              var text = "<?php echo $key->percent ?>%",
                  textX = Math.round((width - ctx.measureText(text).width) / 2),
                  textY = height / 2;

              ctx.fillText(text, textX, textY);
              ctx.save();
        }
    }]
});



<?php } ?>
</script>