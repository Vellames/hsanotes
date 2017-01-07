<?php
require 'PHPMailerAutoload.php';
require "../../../autoload.php";

/*$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = MailSenderConfig::HOST;
$mail->SMTPAuth = MailSenderConfig::AUTH;
$mail->Username = MailSenderConfig::USERNAME;
$mail->Password = MailSenderConfig::PASSWORD;
$mail->Port = MailSenderConfig::PORT;

$mail->setFrom(MailSenderConfig::EMAIL, MailSenderConfig::NAME);

$mail->addAddress('c.vellames@gmail.com', 'Cassiano Vellames');

$mail->isHTML(true);

$mail->Subject = 'Mensagem do site Target';
$mail->Body    =
    '<div style="font-family: \'Tahoma\'">' .
        '<h1>Uma nova mensagem foi enviada pelo site da TARGET!</h1>' .
        '<div><strong>Nome: </strong>' . 0 . '</div>' .
        '<div><strong>Email: </strong>' . 0 . '</div>' .
        '<div><strong>Mensagem: </strong>' . 0 . '</div>' .
    '</div>';

$mail->AltBody = MailSenderConfig::ALT_BODY;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}*/

