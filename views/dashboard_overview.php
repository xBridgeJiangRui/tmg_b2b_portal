<div class="box-body">
    <div class="col-md-4 hidden">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-bar-chart"></i><h3 class="box-title">Monthly Order List (Top 10)</h3>
            </div>
            <div class="box-body" style="overflow-x: scroll;">
            <table class="table table-striped table-valign-middle" style="white-space: nowrap;">
                <tr>
                <th>No</th>
                <th>Description</th>
                <th align="right">Order Qty</th>
                <th align="right">Order Amt</th>
                </tr>
            </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-bar-chart"></i><h3 class="box-title">Monthly Sales List (Top 10)</h3>
            </div>
            <div class="box-body" style="overflow-x: scroll;">
                <table class="table table-striped table-valign-middle" style="white-space: nowrap;">
                <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th align="right">Sales Qty</th>
                    <th align="right">Sales Amt</th>
                </tr>
                <?php $sales_cnt = 1; ?>
                <?php foreach($top_article_monthly_sales AS $sales_data){ ?>
                    <tr>
                    <td><span class="number_circle"><?php echo $sales_cnt; ?></span></td>
                    <td style="color: #FD8B29;"><?php echo $sales_data['articledesc'] ?></td>
                    <td align="right"><?php echo number_format($sales_data['sales_qty']) ?></td>
                    <td align="right"><?php echo number_format($sales_data['sales_amt'],2) ?></td>
                    </tr>
                <?php $sales_cnt++; ?>
                <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <i class="fa fa-bar-chart"></i><h3 class="box-title">Stock List (Top 10)</h3>
            </div>
            <div class="box-body" style="overflow-x: scroll;">
                <table class="table table-striped table-valign-middle" style="white-space: nowrap;">
                    <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th align="right">Stock Qty</th>
                    <th align="right">Stock Amt</th>
                    </tr>
                    <?php $stock_cnt = 1; ?>
                    <?php foreach($top_article_stock AS $stock_data){ ?>
                    <tr>
                        <td><span class="number_circle"><?php echo $stock_cnt; ?></span></td>
                        <td style="color: #FD8B29;"><?php echo $stock_data['articledesc'] ?></td>
                        <td align="right"><?php echo number_format($stock_data['stock_qty']) ?></td>
                        <td align="right"><?php echo number_format($stock_data['stock_amt'],2) ?></td>
                    </tr>
                    <?php $stock_cnt++; ?>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>