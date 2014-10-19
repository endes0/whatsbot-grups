<?php 
require('whatsapi/whatsprot.class.php');
$w = new WhatsProt($username, $identity, $nickname, $debug);
function login() {
 global $w;
 $username = ''; // Number with country code
 $password = ''; // Password obtained with WART or WhatsAPI
 $debug = false; // You can set true, for more details

 $nickname = "WaGroupBot"; // This is the username (or nickname) displayed by WhatsApp clients.

$w->connect();
$w->eventManager()->bind("onGetGroupMessage", "EsUnComando");
$w->loginWithPassword($password);
$w->PollMessages();
}

function EsUnComando($mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $body)
{
$EsComando = substr($body, 0, 1);
$Comando = substr($body, 1);
if ($EsComando=='/') {
ComandosEx($Comando, $from_user_jid, $from_group_jid, $name);		
}
}

function ComandosEx($MSG, $Ujid, $Gjid, $nombre)
{
global $w;	
$Unumero = substr($Ujid,-9);
$Gnumero = substr($Gjid,-5);
$comando = explode(' ', $MSG);
if ($comando[0]=='hola') {
$w->sendMessage($Gnumero, "hola, $nombre.");
$w->sendMessage($Gnumero, "¿como estas?");	
}
else if ($comando[0]=='add') {
if ($comando[1]=='') {
$w->sendMessage($Gnumero, "porfavor escriba numeros de tlf.");
}
else {
unset($comando[0]);
$w->sendGroupsParticipantsAdd($Gnumero, $comando);
}
}
else if ($comando[0]=='remove') {
if ($comando[1]=='') {
$w->sendMessage($Gnumero, "porfavor escriba numeros de tlf.");
} 
else {
unset($comando[0]);
$w->sendGroupsParticipantsRemove($Gnumero, $comando);
}
}
}
?>