<tr>
        <?php if(isset($data['orderData']['files']) && count($data['orderData']['files'])>0){ ?>
        <td style="border-top:5px solid #01878A; background: #F3F3F3;">
          <p style="background: #01878A; font-family: poppins; font-size: 16px; font-weight: 600; color: #ffffff; text-align: left; width: 122px; padding: 10px 15px;">Dateien</p>
          <table border="0" width="100%" cellpadding="0" cellspacing="0">

            <?php 
            
              if(isset($data['orderData']['files']) && count($data['orderData']['files'])){
              $files_a = array_chunk($data['orderData']['files'],2);

              foreach($files_a as $file){

                  echo "<tr>";
                  foreach($file as $f){
                    if(isset($f['file_type'])){
                    echo '<td width="50%" align="left">
                    <label
                      style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 15px 10px 0 10px; text-align: left; color: #727272;">'.$f['file_type'].':</label>
                    <p
                      style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                      '.$f['file_actual_name'].' ('.$f['file_size'].')</p>
                  </td>';
                    }
                  }
                  echo "</tr>";
              }
            }
            ?>

          </table>
        </td>
        <?php } ?>
       </tr>