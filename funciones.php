<?php 
require('whatsapi/whatsprot.class.php');
function login() {
 $username = ''; // Number with country code
 $password = ''; // Password obtained with WART or WhatsAPI
 $debug = false; // You can set true, for more details

 $nickname = "WaGroupBot"; // This is the username (or nickname) displayed by WhatsApp clients.


$w = new WhatsProt($username, $identity, $nickname, $debug);
$w->connect();
$w->eventManager()->bind("onGetGroupMessage", "EsUnComando");
$w->loginWithPassword($password);
$w->PollMessages();
}

function EsUnComando($mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $body)
{





}



?>