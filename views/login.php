<!DOCTYPE html>
<html lang="en">

<head>
  <title>Tunas Manja B2B</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo base_url("assets/bootstrap/css/bootstrap.min.css"); ?>">
  <link rel="icon" type="image/png" href="<?php echo base_url('asset/dist/img_sunshine/icon-512.png'); ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Set black background color, white text and some padding */
    footer {
      clear: both;
      text-align: center;
      color: #9E9E9E;
      position: bottom;
      bottom: 0;
      width: 100%;
    }

    .font-header {
      color: black;
      font-size: 112px;
      font-weight: 300;
      letter-spacing: -.044em;
      line-height: 120px;
    }

    .font-title {
      color: black;
      font-size: 64px;
      font-weight: 300;
      letter-spacing: -.044em;
      line-height: 120px;
    }

    .font-display1 {
      color: black;
      font-size: 34px;
      font-weight: 400;
      letter-spacing: -.01em;
      line-height: 40px;
    }

    .font-caption {
      color: black;
      font-size: 18px;
      font-weight: 400;
      letter-spacing: 0.011em;
      line-height: 20px;
    }

    .content {
      margin-top: 10%;
      height: 230px;
    }

    .lt {
      float: top;
      width: 100%;
      height: 10%;
      /* background: #455A64; */
      background: #455A64;
      box-shadow: 0px 0px 12px gray;
    }

    .rt {
      float: bottom;
      width: 100%;
      height: 10%;
      /* background: #455A64; */
      background: #455A64;
      box-shadow: 0px 0px 12px gray;
    }

    .logo-image {
      float: left;
      margin-top: 5%;
      margin-left: 4%;
    }

    .left {
      float: left;
      margin-top: 0%;
      margin-right: 8%;
      margin-left: 3%;
    }

    .right {
      float: left;
      margin-top: 1%;
    }

    .modal:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
    }

    .modal-dialog {
      display: inline-block;
      vertical-align: middle;
    }
  </style>


</head>
<?php $useragent = $_SERVER['HTTP_USER_AGENT']; ?>

<?php if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) { ?>

  <script>
    $(document).ready(function() {
      $("#myModal").modal();
    });
  </script>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">


    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="container-fluid">
        <div class="row">
          <!-- <a href="https://play.google.com/store/apps/details?id=com.panda.b2b_app" target="_blank"><img alt="Google Play Store" src="<?php echo base_url('assets/playstore-badge.png') ?>" width="150" height="50" style="margin-left: 5px;"></a>
          <a href="https://apps.apple.com/my/app/xbridge-b2b/id1532628055" target="_blank"><img alt="App Store" src="<?php echo base_url('assets/appstore-badge.jpg') ?>" width="150" height="50" style="margin-left: 5px;"></a> -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<div class="container">
  <div class="content">
    <div class="lt">
      &nbsp;
    </div>
    <div class="logo-image">
      <img src="<?php echo base_url('asset/dist/img_sunshine/icon-512.png'); ?>" style="height:110px; width:110px;">
    </div>
    <div class="left" id="left">
      <h1 class="font-header">
        <span class="font-title">Tunas Manja B2B<span class="glyphicon glyphicon-menu-right"></span></span><br>
        <p class="font-display1" style="margin-left:0%;margin-top:-5%;">Business-to-Business System<br>
          <span class="font-caption" style="margin-left:2%;"></span>
        </p>
      </h1>
      <div class="col-md-10">
        <!-- <a href="https://play.google.com/store/apps/details?id=com.panda.b2b_app" target="_blank"><img alt="Google Play Store" src="<?php echo base_url('assets/playstore-badge.png') ?>" width="150" height="50" style="margin-left: 5px;"></a>
        <a href="https://apps.apple.com/my/app/xbridge-b2b/id1532628055" target="_blank"><img alt="App Store" src="<?php echo base_url('assets/appstore-badge.jpg') ?>" width="150" height="50" style="margin-left: 5px;"></a> -->
      </div>
    </div>


    <?php // echo form_open('Panda_verifylogin'); 
    ?>
    <div class="row">
      <div class="col-sm-6 col-md-4 col-md-offset-0">

        <div class="account-wall">

          <div class="right" id="right">
            <br>
            <div class="jumbotron">
              <form class="form-horizontal" role="form" action="<?php echo site_url('login_c/check') ?>" method="post">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="text" class="form-control" placeholder="Email" id="userid" name="userid" required autofocus autocomplete="off">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                    <span class="input-group-addon" id="view_pass" ><i class="glyphicon glyphicon-eye-open" style="cursor:pointer;" ></i></span>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-lg btn-primary btn-block" type="submit" >Sign in</button>
                </div>
                <div class="form-group">
                  <span class="clearfix"></span><a href="<?php echo site_url('Email_controller') ?>" class="pull-right need-help">Forgot Password? </a>
                  <!-- <a href="<?php echo base_url('asset/Rexbridge B2B Guide.pdf') ?>" target="_blank" class="pull-right need-help">Need help? </a> --><a href="https://www.xbridge.my/contact/" class="pull-left need-help">Contact Us</a><span class="clearfix"></span>
                  <span class="clearfix"></span>
                </div>
                <p class="login-box-msg"><span style="font-size: 18px" class="label label-danger"><?php echo $this->session->userdata('message') != '' ? $this->session->userdata('message') : ''; ?></span></p>
                <span class="label label-danger" style="font-size: 14px"><?php echo ''; ?></span>
                <?php // echo validation_errors(); 
                ?>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="rt">
      &nbsp;
    </div>
  </div>

<script src="<?php echo base_url('asset/jquery.min.js') ?>"></script>
<script type="text/javascript"> 
  $('#view_pass').click(function() {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  });
</script>