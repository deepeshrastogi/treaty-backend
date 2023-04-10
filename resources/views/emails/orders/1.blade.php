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
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 15px 10px 0 10px; text-align: left; color: #727272;">Abrechnungszeitraum:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                  @isset($data['orderData']['billing_period']['from']) {{$data['orderData']['billing_period']['from']}}@endisset -  @isset($data['orderData']['billing_period']['to']){{$data['orderData']['billing_period']['to']}}@endisset</p>
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
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 5px 10px 0 10px; text-align: left; color: #727272; display: block;">Jahr:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                  @isset($data['orderData']['year']){{$data['orderData']['year']}}@endisset</p>
              </td>
            <tr>
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 5px 10px 0 10px; text-align: left; color: #727272; display: block;">Nutzungszeitraum:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                  @isset($data['orderData']['period_use']['from']){{$data['orderData']['period_use']['from']}}@endisset - @isset($data['orderData']['period_use']['to']){{$data['orderData']['period_use']['to']}}@endisset</p>
              </td>
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 5px 10px 0 10px; text-align: left; color: #727272; display: block;">Ersteller der Abrechnung:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                  @isset($data['orderData']['counter_part']['name']){{$data['orderData']['counter_part']['name']}}@endisset</p>
              </td>
            </tr>
            <tr>
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 5px 10px 0 10px; text-align: left; color: #727272; display: block;">Tatsächlich
                  gezahlte Vorauszahlung<br />zur Betriebskostenabrechnung:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                  @isset($data['orderData']['advanced_payment_for_OS']['type']){{$data['orderData']['advanced_payment_for_OS']['type']}}@endisset: @isset($data['orderData']['advanced_payment_for_OS']['cost']){{$data['orderData']['advanced_payment_for_OS']['cost']}}@endisset</p>
              </td>
              <td width="50%" align="left">
                <label
                  style="font-family: poppins; font-size: 14px; line-height: 21px; font-weight: 600; padding: 5px 10px 0 10px; text-align: left; color: #727272; display: block;">Eingangsdatum
                  der <br />Betriebskostenabrechnung:</label>
                <p
                  style="font-family: poppins; font-size: 14px; font-weight: 500; padding: 0 10px 10px 10px; margin: 0; text-align: left; color:#323232">
                  <?=date('d.m.Y')?></p>
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