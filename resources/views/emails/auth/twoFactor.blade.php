@include('emails.header')
      <tr>
        <td style="padding:50px 0;text-align: center">
          <img alt="icon" src="{{ asset('assets/images/icon-reset_password.svg') }}" />
        </td>
      </tr>
      <tr>
        <td style="text-align: center">
          <p style="font-family: poppins; font-weight: 600; font-size: 22px; line-height: 21px; text-align:center; padding: 0 10px; margin-bottom: 0;">Sicherheitscode</p>
          <p style="font-family: poppins; font-weight: 600; font-size: 16px; line-height: 21px; padding: 0 10px; padding-bottom: 20px;">Vielen Dank für die Anmeldung im ADH Treaty System.</p>
          <p style="font-family: poppins; font-size: 16px; line-height: 21px; font-weight: 500; padding: 0 10px;">Anbei übersenden wir Ihnen Ihr Sicherheitscode zum sicheren einloggen:<br /></p>
          <p style="font-family: poppins; font-size: 40px; padding: 0 10px; margin:0 !important; padding-bottom:20px;">{{$data['code']}}</p>  
        </td>
      </tr>
@include('emails.footer')
      