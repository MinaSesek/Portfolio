<?php

## Serverska validacija
if (isset($_POST["SenderName"], $_POST["SenderEmail"], $_POST["SenderMessage"]) && filter_var($_POST["SenderEmail"], FILTER_VALIDATE_EMAIL) && (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))) {

    ## Uključivanje PHPMailer klase
    require 'PHPMailerAutoload.php';

    ## Dodeljivanje vrednosti u promenljive
    $SenderName    = stripslashes($_POST["SenderName"]);
    $SenderEmail   = stripslashes($_POST["SenderEmail"]);
    $SenderMessage = stripslashes(nl2br($_POST["SenderMessage"]));

    // Definisanje poruke tela
    $body = "
<p>Poruku šalje: $SenderName ($SenderEmail)</p>
<p>$SenderMessage</p>
";
    // Google Re-captcha v2 starts here
    // build the request url
    $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $args = array('secret' => '6LfLqiwUAAAAAEKtDz9CBx4S0hi59eGq8CFsZu9V',
        'response' => $_POST['g-recaptcha-response']);
    $request_url = $verify_url.'?'.http_build_query($args);

    // a JSON object is returned
    $response = file_get_contents($request_url);

    // decode the information
    $response_data = json_decode($response);

    // handle the response
    if($response_data->success) {
        if ( ! empty($_POST['Sender'])) {
            $body .= "<hr>";
            foreach ($_POST['Sender'] as $key => $value) {
                $value = stripslashes(nl2br($value));
                $key = str_replace("_", " ", $key);
                $body .= "<p>$key: $value</p>";
            }
        }

        ## Pokretanje i podešavanje PHPMailer-a
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->WordWrap   = 50;
        $mail->Port = $mail_port;
        $mail->isHTML(true);

        ## SMTP podaci
        // Uglavnom treba da se promene podaci ispisani VELIKIM SLOVIMA
        $mail->Host     = $mail_host; // SMTP Server
        $mail->Username = $mail_username; // SMTP username
        $mail->Password = $mail_password; // SMTP password

        ## Email podaci
        $mail->From     = $mail_username; // noreply@
        $mail->FromName = $SenderName; // Ime pošiljaoca (korisnika)
        $mail->addReplyTo($SenderEmail, $SenderName); // E-mail pošiljaoca

        $mail->addAddress($to_email, $SenderName); // Adresa klijenta ili tvoja adresa

        ## Poruka
        $mail->Subject = $subject;           // Naslov maila
        // Izgled i sadržaj poruke. Koristi HTML
        $mail->Body = $body;

        ## Slanje poruke i provera greške
        if ($mail->send()) {
            echo 1;
            exit;
        } else {
            $status = 'error';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit; // Ukoliko se email ne šalje, ova naredba će nam prikazati grešku
        }
    } else {
        echo 'Recaptcha failed';
        exit;
    }

}