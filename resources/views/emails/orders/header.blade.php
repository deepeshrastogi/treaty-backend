<tr>
        <td style="padding:50px 0 15px 0;text-align: center">
          <img src="{{ asset('assets/images/icon-tick.svg') }}" />
        </td>
      </tr>
      <tr>
        <td><p style="font-family: poppins; font-weight: 600; font-size: 22px; line-height: 21px; text-align:center; padding: 0 10px; margin-bottom: 0;">Herzlichen Dank für Ihren Auftrag</p></td>
      </tr>
      <tr>
        <td height="30">&nbsp;</td>
      </tr>
      <tr>
        <td>
          <p style="font-family: poppins; font-size: 16px; font-weight: 500; padding: 0 10px 0 0; text-align: left; font-weight: bold;margin: 0;">Auftrag: @isset($data['auftragstyp']){{$data['auftragstyp']}}@endisset</p>
          <p style="font-family: poppins; font-size: 16px; font-weight: 500; padding: 0 10px 0 0; text-align: left; font-weight: bold;margin: 0;">ID: @isset($data['order_id']){{$data['order_id']}}@endisset</p>
          <p style="font-family: poppins; font-size: 16px; font-weight: 500; padding: 0 10px 0 0; text-align: left; font-weight: bold;margin: 0;">vom: @isset($data['created_at']){{date('d.m.Y H:i', strtotime($data['created_at']))}}@endisset Uhr</p>
          <p style="font-family: poppins; font-size: 16px; font-weight: 500; padding: 0 10px 0 0; text-align: left; font-weight: bold;margin: 0;">Standort: @isset($data['ort']){{$data['ort']}}@endisset</p>
          <p style="font-family: poppins; font-size: 16px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 0; text-align: left;">Ihr Auftrag wurde erfolgreich übermittelt und wird in Kürze unserem Service-Team bearbeitet. Im Folgenden sehen Sie eine Zusammenfassung Ihres Auftrages. </p>
        </td>
</tr>
