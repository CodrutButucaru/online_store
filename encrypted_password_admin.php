<?php
$parola = 'parola';
// Hash the password using the PASSWORD_DEFAULT algorithm (usually bcrypt)
$parola_criptata = password_hash($parola, PASSWORD_DEFAULT);
// Output the hashed password
echo $parola_criptata;
?>
