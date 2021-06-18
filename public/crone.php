<?php
//exec('php bin/console bills:create');
$from = "luctio@luctiobackend.online";
$to='yaskovetc@gmail.com';
$subject="Luctio";
$message="Sehr geehrte Damen und Herren,die Traueranzeige von  wurde in unserem Portal freigeschaltet.
                          Unser tiefstes Beileid.
                
                          Ihr Luctio-Team";
$headers="From:".$from;
mail($to, $subject, $message, $headers);