@include('emails.header')
      <tr>
        <td style="padding:50px 0 15px 0;text-align: center">
          <img src="{{ asset('assets/images/big-mail-icon.svg') }}" />
        </td>
      </tr>
      <tr>
      <td>
          <p style="font-family: poppins; font-weight: 600; font-size: 22px; line-height: 21px; text-align:center; padding: 0 10px; margin-bottom: 0;">Willkommen im Kundenportal Treaty</p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px; text-align: left;">Sie haben die Möglichkeit, Aufträge mit Dokumenten,  schnell, einfach und bequem online über unser neues Kundenportal einzureichen.</p>
          <br/><br/>

          <p style="font-family: poppins; font-size: 18px; padding: 0 10px; margin:0 !important; font-weight: 500; text-align: left;">Bei Fragen unterstützen wir Sie gerne bei der Auftragserstellung.Sie erreichen unser Service-Team unter 06151 78748 35.</p>
          <br/><br/>

          <p style="font-family: poppins; font-size: 18px; padding: 0 10px; margin:0 !important; font-weight: 500; text-align: left;">Ihre Zugangsdaten lauten:</p>
          <br/><br/>

          <p style="font-family: poppins; font-size: 18px; padding: 0 10px; margin:0 !important; font-weight: 500; text-align: left;">Benutzer: {{$data['email']}}</p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 0 10px 0 10px; text-align: left; margin:0;">Passwort: <a href="{{$data['url']}}/createpassword/{{$data['token']}}" style="color: #E70000; text-decoration: none;">Bitte erstellen Sie sich ein sicheres Passwort</a></p>
          <br/><br/>
         
          <br/><br/>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 0 10px 0 10px; text-align: left;color: #000000; margin:0;">Ihr ADH Service-Team</p>
          <br/><br/>
        </td>
      </tr>
@include('emails.footer'); 