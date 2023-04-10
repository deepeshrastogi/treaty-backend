@include('emails.header')
      <tr>
        <td style="padding:50px 0 15px 0;text-align: center">
          <img src="{{ asset('assets/images/icon-support.svg') }}" alt="phone-icon" />
        </td>
      </tr>
      <tr>
        <td>
          <p style="font-family: poppins; font-weight: 600; font-size: 22px; line-height: 21px; text-align:center; padding: 0 10px; margin-bottom: 0;">Rückruf angefordert</p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px; text-align: left;">Vielen Dank für Ihre Nachricht. Wir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.</p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px; text-align: left;">Sie haben uns folgende Nachricht zugesandt:</p>          
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px;; text-align: left;color: #000000; margin:0;">Email: <a href="mailto:{{$data['email']}}" style="color: #01878A;text-decoration: none;"> {{$data['email']}}</a></p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px;; text-align: left;color: #000000; margin:0;">Phone: <a href="tel:{{$data['phone_no']}}" style="color: #01878A;text-decoration: none;">{{$data['phone_no']}}</a></p>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 15px 10px 0 10px;; text-align: left;color: #000000; margin:0;">Nachricht: <a href="/" style="color: #01878A;text-decoration: none;"> {{$data['message']}}</a></p>
          <br/><br/><br/><br/>
          <p style="font-family: poppins; font-size: 18px; line-height: 21px; font-weight: 500; padding: 0 10px 0 10px; text-align: left;color: #000000; margin:0;">Ihr ADH Service-Team</p>
          <br/><br/>
        </td>
      </tr>
@include('emails.footer')