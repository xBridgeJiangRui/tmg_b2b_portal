
<!DOCTYPE html>
<html style="background-color: #4778e3;">
<head>
  <title>QR Reader</title>
  <link rel="icon" type="image/png" href="<?php echo base_url('asset/dist/img_sunshine/icon-512.png');?>" >
  
  <script src="<?php echo base_url('asset/jquery.min.js')?>"></script>
  <script src="<?php echo base_url('asset/instascan.min.js')?>"></script>
  <link rel="stylesheet" href="<?php echo base_url('asset/w3.css')?>">

  <style>
.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   background-color: #44525f;
   color: white;
   padding: 10px
}
</style>

<style>
.w3-lobster {
  font-family: "Lobster", serif;
}

.flash_message{

font-style: italic;
font-family: sans-serif;

}

html {
  transition: background-color ease-in 1s; /* tweak to your liking */
}

</style>
</head>
<body>
  

<div style="padding: 30px 30px 20px 30px;">

  <div style="text-align: center;">

    <video id="preview" style="border-radius: 15px;"></video>

    <div id="user_details" style="font-size: xx-large;text-align: center;"></div>

  </div>
    </div>
    

    <div id="status-area" style="font-size: xx-large;text-align: center;"></div>

    


    <div class="footer" style="display: flex;">
      <img style="border-radius: 50%;width:75px;margin-right: 10px" class="fix" src="https://b2b.xbridge.my/asset/dist/img_sunshine/icon-512.png" border="0" alt="" />

      &nbsp;<label class="w3-lobster" style="font-size:xx-large;">B2B - Rexbridge : Training 18/10/19</label> 
    </div>
    <script type="text/javascript">

          (function($) {
        $.fn.flash_message = function(options) {
          
          options = $.extend({
            text: 'Done',
            time: 5000,
            how: 'before',
            class_name: ''
          }, options);
          
          return $(this).each(function() {
            if( $(this).parent().find('.flash_message').get(0) )
              return;
            
            var message = $('<span />', {
              'class': 'flash_message ' + options.class_name,
              text: options.text
            }).hide().fadeIn('fast');
            
            $(this)[options.how](message);
            
            message.delay(options.time).fadeOut('normal', function() {
              $(this).remove();
              $('html').css({'background-color':'#4778e3' });
            });
            
          });
        };
    })(jQuery);



      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {

        $.ajax({

          url : "<?php echo site_url('Training/scan_qr_code_attendance?g='); ?>" + content,
          beforeSend : function() {

          },
          complete: function() {

          },

          success : function(result){

            result = JSON.parse(result);

            var baseUrl = "http://www.soundjay.com/button/";
            var audio = ["beep-01a.mp3", "beep-02.mp3", "beep-03.mp3", "beep-04.mp3", "beep-05.mp3", "beep-06.mp3", "beep-07.mp3", "beep-08b.mp3", "beep-09.mp3"];

          new Audio(baseUrl + audio['9'-1]).play();


            /*var html = '';

            html += 'Company: '+result.company_name;
            html += "\n"+'Name: '+result.name;
            html += 'User ID: '+result.user_id;*/

            /*alert(result.message);*/
            $('#status-area').flash_message({
                text: result.message,
                how: 'append'
            });

            if (result.status == 'Sign') {

              $('html').css({'background-color':'#91e347' });

            } else if (result.status == 'Signed'){

              $('html').css({'background-color':'#f7dc59' });

            } else if (result.status == 'Error'){

              $('html').css({'background-color':'#f93c3c' });

            }

            
            /*$('#user_details').flash_message({
                text: html,
                how: 'html'
            });
*/

          }

      })



        /*alert(content);*/
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
    </script>

    <script type="text/javascript">
      
     

    </script>
   
</body>
</html>