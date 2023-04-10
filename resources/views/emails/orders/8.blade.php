@include('emails.header')

@include('emails.orders.header', $data)

       <tr>
        <td style="border-top:5px solid #01878A; background: #F3F3F3;">
          <p style="background: #01878A; font-family: poppins; font-size: 16px; font-weight: 600; color: #ffffff; text-align: left; width: 122px; padding: 10px 15px;">Einzelheiten</p>
          <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 15px 10px 0 10px; text-align: left; color: #727272;">Auftrag:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">@isset($data['auftragstyp']){{$data['auftragstyp']}}@endisset</p>
              </td>
              
            </tr>
            <tr>
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 5px 10px 0 10px; text-align: left; color: #727272; display: block;">Standort:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                  @isset($data['ort']) {{$data['ort']}}@endisset</p>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 5px 10px 0 10px; text-align: left; color: #727272; display: block;">Nachricht
                  zum Auftrag:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">@isset($data['orderData']['message'])
                                {{$data['orderData']['message']}}
                                @endisset</p>
              </td>
            </tr>
          </table>
        </td>
       </tr>
       <tr>
        <td height="20">&nbsp;</td>
       </tr>
       @include('emails.orders.files', $data)
       @include('emails.orders.footer', $data)

@include('emails.footer');
