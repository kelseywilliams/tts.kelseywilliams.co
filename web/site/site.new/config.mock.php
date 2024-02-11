<?php

# In the event that anyone decides to create a config.php that is used and included in almost every
# php file in this repo, the structure is as follows in this mock file. 

# But remember, this practice of including a config.php is simply for convenience and simplicity not security.
# Security through obscurity is no security at all.

$config=array(
'host'=>'host',
'username'=>'username',
'password'=>'password',
'db'=>'db'
);
return $config;
?>