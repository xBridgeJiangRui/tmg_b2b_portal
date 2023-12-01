<style>
  .card {
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
    margin-right: 10px;
    text-align: center;
    <?php if($_REQUEST['option'] == 'consign'){ echo 'width: 500px;'; } ?>
  }

  .card:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
  }

  .link
  {
    color: black;
    text-decoration: none; 
    background-color: none;
  }

  .link:hover
  {
    color: black;
    text-decoration: none; 
    background-color: none;
  }

</style>

<div class="box-body flex-container">
  <div class="card">
    <span data-toggle="tooltip" title="<?php echo $tooltip_title['daily_sales_amt'] ?>">
      <a href="<?php echo $redirect_sales ?>" class="link">
        <p><b>Daily Sales Amt(thousands)</b></p>
        <h3><b>$<?php echo number_format($yesterday_daily_sales_amt / 1000,2) ?></b></h3>
        <p><b>Compared last week </b><?php echo number_format($daily_sales_diff,2) ?><b>%</b></p>
      </a>
    </span>
  </div>
  <div class="card">
    <span data-toggle="tooltip" title="<?php echo $tooltip_title['total_weekly_sales'] ?>">
      <a href="<?php echo $redirect_sales ?>" class="link">
        <p><b>Total Weekly(thousands)</b></p>
        <h3><b>$<?php echo number_format($total_weekly / 1000,2) ?></b></h3>
      </a>
    </span>
  </div>
  <div class="card">
    <span data-toggle="tooltip" title="<?php echo $tooltip_title['total_monthly_sales'] ?>">
      <a href="<?php echo $redirect_sales ?>" class="link">
        <p><b>G/TTL Monthly(thousands)</b></p>
        <h3><b>$<?php echo number_format($total_monthly / 1000,2) ?></b></h3>
      </a>
    </span>
  </div>
  <div class="card" <?php if($_REQUEST['option'] == 'consign'){ echo 'hidden'; } ?> >
    <span align="center" data-toggle="tooltip" title="<?php echo $tooltip_title['total_weekly_order'] ?>">
      <a href="<?php echo $redirect_pomain ?>" class="link">
        <p><b>Total Weekly Order (thousands)</b></p>
        <h3><b>$<?php echo number_format($order_weekly / 1000,2) ?></b></h3>
      </a>
    </span>
  </div>
  <div class="card" <?php if($_REQUEST['option'] == 'consign'){ echo 'hidden'; } ?> >
    <span data-toggle="tooltip" title="<?php echo $tooltip_title['total_monthly_order'] ?>">
      <a href="<?php echo $redirect_pomain ?>" class="link">
        <p><b>G/TTL Monthly Order(thousands)</b></p>
        <h3><b>$<?php echo number_format($order_monthly / 1000,2) ?></b></h3>
      </a>
    </span>
  </div>
  <div class="card" <?php if($_REQUEST['option'] == 'consign'){ echo 'hidden'; } ?> >
    <a href="<?php echo $redirect_inventory ?>" class="link">
      <p><b>Inventory Amt(thousands)</b></p>
      <h3><b>$<?php echo number_format($inventory_amt / 1000,2) ?></b></h3>
    </a>
  </div>
</div>
