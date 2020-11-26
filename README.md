# Contact-Form
 A PHP Contact Form that can be included in any HTML/JS website

Libraries Used: 
Composer : used to install PHPMailer
PHPMailer : TO enable SMTP mail transfer using GMail

*** To use this form you will need a GMail account with lowered access control ***

Install Composer and PHPMailer using their installation guides

https://getcomposer.org/download/
https://packagist.org/packages/phpmailer/phpmailer

Once PHPMailer is setup, this file will need some specific alterations to work. FInd these sections in the code and make changes:

1.
$user = "This is where the email address goes";                 //The GMail address that will be used to send the message
$pswd = "This is where the password goes";

2.
 //Set Sender and Receiver
            $mail->setFrom('email', 'Mailer');                  // Set Sender Email. For a contact form these could be the same
            $mail->addAddress('email', 'Receiver');             // Add a recipient

