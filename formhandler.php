<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


$message = "";
$confirmMessage = "";
$user = "This is where the email address goes";                 //The email address that will be used to send the message
$pswd = "This is where the password goes";

$phoneErr = $emailErr = $optionErr = $selectionErr = "";
$option1 = $option2 = $option3 = $name = "";
$email = $whatsapp = $sms = $phone = $emailAddr = "";


// retrieing FORM data

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(Isset($_POST["option1"]))
        $option1 = $_POST["option1"];
    if(Isset($_POST["option2"]))
        $option2 = $_POST["option2"];
    if(Isset($_POST["option3"]))
        $option3 = $_POST["option3"];
    if(Isset($_POST["email"]))
        $email = $_POST["email"];
    if(Isset($_POST["whatsapp"]))
        $whatsapp = $_POST["whatsapp"];
    if(Isset($_POST["sms"]))
        $sms = $_POST["sms"];
    if(Isset($_POST["phone"]))
        $phone = test_input($_POST["phone"]);
    if(Isset($_POST["emailAddr"]))
        $emailAddr = test_input($_POST["emailAddr"]);
    if(Isset($_POST["name"]))
        $name = test_input($_POST["name"]);

    // Requirement checks
    $confirmMessage .= "Name: " . $name . "<br/>";

    if($option1 == "" &&  $option2 == "" && $option3 == ""){
        $optionErr = "Please select atleast one option";
    }
    else {
        $confirmMessage .= "Options: " . $option1 . " " . $option2 . " " . $option3 . "<br/>";
    }
    if($email == "" &&  $whatsapp == "" && $sms == ""){
        $selectionErr = "Please select atleast one option from Email/WhatsApp/SMS";
    }
    if($email != ""){
        if($emailAddr == ""){
            $emailErr = "Please enter a valid email address";
        }
        else {
            $confirmMessage .= "Email: " . $emailAddr . "<br/>";
        }
    }
    if($whatsapp != "" || $sms != ""){
        if($phone == ""){
            $phoneErr = "Please enter a valid phone number(numbers only)";
        }
        else {
            $confirmMessage .= "Contact Number: " . $phone . "<br/>";
        }
        $confirmMessage .= "Contact Method: " . "<br/>";
        if($whatsapp != ""){
            $confirmMessage .= "WhatsApp ";
        }
        if($sms != ""){
            $confirmMessage .= "SMS";
        }
    }
    
    if($phoneErr == "" && $emailErr == "" && $optionErr == "" && $selectionErr == "")
        {
            $message = $confirmMessage;            
        }
    
    
    // Load Composer's autoloader
    require 'vendor/autoload.php';
    
    if($message !== ""){

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        // SMTP::DEBUG_SERVER;
        try {
            //Server settings
            $mail->SMTPDebug = false;                         // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $user;                     // SMTP username
            $mail->Password   = $pswd;                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Set Sender and Receiver
            $mail->setFrom('email', 'Mailer');      // Set Sender Email. For a contact form these could be the same
            $mail->addAddress('email', 'Receiver');     // Add a recipient
            
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "Subject";
            $mail->Body    = $message;
            // $mail->AltBody = $bodyText;          //text for non-html recipient

            $mail->send();
            $confirmMessage = "Subscription Request Sent!";
        } catch (Exception $e) {
            $confirmMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        
        }
    }
   
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
    
    function onSubmit(token) {
        document.getElementById("form1").submit();
    }
    function checkEmail(){
        checkbox = document.getElementById("email");
        
        if (checkbox.checked == true)
            document.getElementById("emailAddr").style.display = 'inline-block'; 
        else
        document.getElementById("emailAddr").style.display = 'none'; 
    }
    function checkContact(){
        checkW = document.getElementById("whatsapp");
        checkS = document.getElementById("sms");
        
        if (checkW.checked == false && checkS.checked == false)
            document.getElementById("contactNum").style.display = 'none'; 
        else
            document.getElementById("contactNum").style.display = 'inline-block'; 
    }
    
    </script>
    <title>Document</title>
    <style>
        body{
            font-family: Helvetica, Arial, sans-serif;
        }
    </style>

</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="form1">
        <fieldset id="space">
            
            <p>If you wish to recieve notifications kindly subscribe by filling out the form below.</p>

            <p>You wish to receive information regarding...</p>
            <input type="checkbox" name="option1" value="Option1"><label for="option1"/>Option 1</label> <br/>
            <input type="checkbox" name="option2" value="Option2"><label for="option2"/>Option 2</label> <br/>
            <input type="checkbox" name="option3" value="Option3"><label for="option3"/>Option 3</label> <br/>
            <p><?php echo $optionErr; ?></p>

            <p>You wish to receive information via...</p>
            <p><input type="checkbox" name="email" id="email" onclick="checkEmail();" value="email"><label for="email"/>Email</label> <input type="email" id="emailAddr" name="emailAddr" style="display:none;"></p>
            <p><?php echo $emailErr; ?></p>
            <div>
                <input type="checkbox" id="whatsapp" name="whatsapp" onclick="checkContact();"><label for="whatsapp"/>WhatsApp</label> <br/>
                <input type="checkbox" id="sms" name="sms" onclick="checkContact();"><label for="sms"/>SMS</label> <br/>
                <p id="contactNum" style="display:none;"><label for="phone">Contact Number: </label><input type="number" name="phone"> *</p>          
                <p><?php echo $phoneErr; ?></p>
                <p>* If you wish to receive notifications by WhatsApp please make sure the phone number you provide works with WhatsApp</p>
            </div>
            <p><?php echo $selectionErr; ?></p>
            <p id="contact-name"><label for="name">Your Name: </label><input type="text" name="name" id="name" required></p>  
            
            <input type="submit" name="submit"> <br/><br/>
        </fieldset>
    </form>
    <p><?php echo $confirmMessage; ?></p>
</body>
</html>