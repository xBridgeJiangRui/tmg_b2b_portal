<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "https://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Test</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width: 680px;">
  <center>
  </center>
  <p style="margin-top: 0px; margin-bottom: 20px;">
  
  </p>
  <p style="margin-top: 0px; margin-bottom: 20px;">
    <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: center; padding: 7px; color: #222222;">Memo</td>
         
      </tr>
    </thead>
    <tbody>
      <?php foreach ($q_result->result() as $row) { ?>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $row->content;?></td>
        
      </tr>
      <?php } ?>
    </tbody>
  </table>
  </p>
    <p style="margin-top: 0px; margin-bottom: 20px;">
    <center>
     
  </center> 
  </p>
    <p style="margin-top: 0px; margin-bottom: 20px;">  
     
  </p>
</div>
</body>
</html>