<!DOCTYPE html>
<html lang="en">
<head>
  <title>B2B Training</title>
  <link rel="icon" type="image/png" href="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" >
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


<link rel="stylesheet" href="<?php echo base_url('asset/bootstrap/css/bootstrap.min.css')?>">
<script src="<?php echo base_url('asset/jquery.min.js')?>"></script>
</head>
<body>

<div class="container w3-animate-opacity" style="padding-right: 0; padding-left: 0;">
  <div style="padding: 30px 30px 20px 30px;background-color: #4778e3;color: #ffffff;display: flex;
 ">

  	<span><img style="border-radius: 15px;margin-right: 15px" class="fix" src="https://b2b.xbridge.my/asset/dist/img_sunshine/icon-512.png" width="70" height="70" border="0" alt="" /></span><span style="margin: auto;padding: 10px;font-size: 18px;">B2B - Training 18/10/2019</span>

  </div>

  	<br>
  <form method="post" action="<?php echo site_url('Training/sign_in_attend')?>" role="form" style="padding-right: 15px; padding-left: 15px;">
    <div class="form-group">
      <label for="email">IC No.:</label>
      <input type="text" class="form-control" name="i_c" id="" placeholder="Enter Identity Card Number">
    </div>
    <!-- <div class="form-group">
      <label for="pwd">Name:</label>
      <input type="password" class="form-control" name="name" id="" placeholder="Enter Name">
    </div> -->
    <button style="width: 100%;background-color: #44525f;color: white;" type="submit" class="btn btn-default">Submit</button>
  </form>
</div>

</body>
</html>