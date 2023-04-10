@include('emails.header')
      <tr>
        <td style="padding:50px 0 15px 0;text-align: center">
          <img src="{{ asset('assets/images/icon-reset_password.svg') }}" />
        </td>
      </tr>
      <tr>
      <td>
          <p style="font-family: poppins; font-weight: 600; font-size: 22px; line-height: 21px; text-align:center; padding: 0 10px; margin-bottom: 0;">Passwort zurücksetzen anfordern</p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px; text-align: left;">Wir haben eine Anfrage zum Zurücksetzen des Passworts für Ihr Konto erhalten. Wenn Sie keinen Antrag auf Zurücksetzung des Passworts gestellt haben, können Sie diese E-Mail ignorieren.</p>
          <p style="font-family: poppins; font-size: 18px; padding: 0 10px; margin:0 !important; font-weight: 500; text-align: left;">Um Ihr Passwort zurückzusetzen, klicken Sie auf den unten stehenden Link:</p>
          <br/><br/>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 0 10px 0 10px; text-align: left; margin:0;"><a href="{{$data['url']}}/resetpassword/{{$data['token']}}" style="color: #E70000; text-decoration: none;">Link zurücksetzen</a></p>
          <br/><br/>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 0 10px 0 10px; text-align: left;color: #000000; margin:0;">Hinweis: Der Link ist nur für eine Stunde aktiv.</p>
          <br/><br/>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 0 10px 0 10px; text-align: left;color: #000000; margin:0;">Ihr ADH Service-Team</p>
          <br/><br/>
        </td>
      </tr>
@include('emails.footer')