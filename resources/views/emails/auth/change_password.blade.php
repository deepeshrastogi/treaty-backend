@include('emails.header')
      <tr>
        <td style="padding:50px 0 15px 0;text-align: center">
          <img src="{{ asset('assets/images/icon-reset_password.svg') }}" />
        </td>
      </tr>
      <tr>
        <td>
          <p style="font-family: poppins; font-weight: 600; font-size: 22px; line-height: 21px; text-align:center; padding: 0 10px; margin-bottom: 0;">Ihr Passwort wurde erfolgreich geändert</p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px; text-align: left;">Sie haben Ihr Passwort erfolgreich geändert. Ab sofort können Sie sich mit Ihrem neuen Passwort in das ADH Treaty System einloggen.</p>
          
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px; text-align: left;">Um sich anzumelden klicken Sie bitte auf den folgenden Button:</p>
          <br/>
          <p style="font-family: poppins; line-height: 21px; padding: 0 10px 0 10px; text-align: left; margin:0;">
            <a href="{{$data['url']}}" style="color: #fff; text-decoration: none; background: #01878A; padding: 5px 10px; font-weight: 600;">Anmelden</a>
          </p>
          <br/><br/><br/>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 0 10px 0 10px; text-align: left;color: #000000; margin:0;">Ihr ADH Service-Team</p>
          <br/><br/>
        </td>
      </tr>
@include('emails.footer')