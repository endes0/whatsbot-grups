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
sleep(2);
$w->sendMessage($grupo, "		comandos		\n/hola -te saluda. \n/add [numeros de telefonos separados por espacios] -añade participantes. \n/remove [numeros de telefonos separados por espacios] -quita participantes participantes.");
}
}
else if ($comando[0]=='remove') {
if ($comando[1]=='') {
$w->sendMessage($Gnumero, "porfavor escriba numeros de tlf.");
} 
else {
unset($comando[0]);
$w->sendGroupsParticipantsRemove($Gnumero, $comando);
$w->sendMessage($Gnumero, "usuario/s eliminado/s del grupo");
}
}
}
function CrearGrupo($asunto, $participantes)
{
global $w;
$grupo = $w->sendGroupsChatCreate($asunto, $participantes, $creador);
$w->sendMessage($grupo, "bienvenid@s al grupo '".$asunto."' creado por '".$creador."' \nusando Whatsbot by endes3000");
$w->sendMessage($grupo, "		comandos		\n/hola -te saluda. \n/add [numeros de telefonos separados por espacios] -añade participantes. \n/remove [numeros de telefonos separados por espacios] -quita participantes participantes.");
}
function AbGrupo($Gnumero, $Gjid, $participantes, $tipo)
{
global $w;
if ($tipo=='AB') {
$w->sendMessage($Gnumero, "se ha recibido unapeticion de eliminacion des este grupo \nel grupo se va a eliminar dentro de 20 segundos");
sleep(10);	
$w->sendMessage($Gnumero, "el grupo se va ha eliminar dentro de 10 segundos");
sleep(5);
$w->sendMessage($Gnumero, "el grupo se va a eliminar dentro de 5 segundos");
sleep(5);
$w->sendGroupsParticipantsRemove($Gnumero, $participantes);
sleep(1);
$w->sendGroupsChatEnd($Gjid);
}
else {
$w->sendMessage($Gnumero, "se ha recibido una peticion para que whats bot deje de administrar este grupo \nadios.");
$w->sendGroupsChatEnd($Gjid);	
}
}
?>