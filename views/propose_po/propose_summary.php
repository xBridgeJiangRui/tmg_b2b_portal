<?php if(sizeof($results) != 0){ ?>
<div></div>
<div class="col-md-12">
  <table id="view_cost_summary_table" class="table table-bordered table-striped" width="100%" cellspacing="0">
    <thead style="white-space: nowrap;">
      <tr>
        <th style="text-align: left;">No</th> 
        <th style="text-align: left;">Outlet</th> 
        <th style="text-align: left;">Total Cost</th>
      </tr>
    </thead>
    <tbody>
      <?php $count = 1; ?>
      <?php foreach ($results as $row){ ?>
        <tr>
          <td style="text-align: center;"><?php echo $count;?></td>
          <td style="text-align: left;"><?php echo $row['location'];?> - <?php echo $row['branch_desc'];?></td>
          <td style="text-align: right;"><?php echo number_format($row['outlet_total'],2, '.', ',');?></td>
        </tr>
      <?php $count++; ?>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php } ?>

<script type="text/javascript">
  $(document).ready(function() {
    $('#view_cost_summary_table').DataTable({
      "columnDefs": [
        { className: "aligncenter", targets: [0] },
        { className: "alignright", targets: '_all' },
        { className: "alignleft", targets: [1,2] },
      ],
      'filter'      : true,
      'pageLength'  : 999999999999,
      'processing'  : true,
      'paging'      : false,
      'lengthChange': false,
      'lengthMenu'  : [ [9999999999999999], ["ALL"] ],
      'searching'   : true,
      'ordering'    : true,
      // 'order'       : [ [2 , 'desc'] ],
      'info'        : true,
      'autoWidth'   : false,
      "bPaginate": true, 
      "bFilter": true, 
      "sScrollY": "55vh", 
      "sScrollX": "100%", 
      "sScrollXInner": "100%", 
      "bScrollCollapse": true,
      
    });
  });
</script>