<style>
  .card {
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
    margin-right: 10px;
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
    <a href="<?php echo $redirect_pomain ?>" class="link">
      <p><b>PO Num</b></p>
      <h3><b><?php echo $pomain_total ?></b></h3>
    </a>
  </div>
  <div class="card">
    <a href="<?php echo $redirect_pomain ?>" class="link">
      <p><b>PO Amount</b></p>
      <h3><b>$<?php echo number_format($pomain_amount,2) ?></b></h3>
    </a>
  </div>
  <div class="card">
    <a href="<?php echo $redirect_cndn ?>" class="link">
      <p><b>RTV Num</b></p>
      <h3><b><?php echo $cndn_total ?></b></h3>
    </a>
  </div>
  <div class="card">
    <a href="<?php echo $redirect_cndn ?>" class="link">
      <p><b>RTV Amount</b></p>
      <h3><b>$<?php echo number_format($cndn_amount,2) ?></b></h3>
    </a>
  </div>
  <div class="card">
    <a href="<?php echo $redirect_grmain ?>" class="link">
      <p><b>GRN Num</b></p>
      <h3><b><?php echo $grmain_total ?></b></h3>
    </a>
  </div>
  <div class="card">
    <a href="<?php echo $redirect_grmain ?>" class="link">
      <p><b>GRN Amount</b></p>
      <h3><b>$<?php echo number_format($grmain_amount,2) ?></b></h3>
    </a>
  </div>
  <div class="card">
    <a href="<?php echo site_url('b2b_po') ?>" class="link">
      <p><b>PO Store</b></p>
      <h3><b><?php echo $po_store ?></b></h3>
    </a>
  </div>
</div>