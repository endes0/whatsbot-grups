<?php 
require('whatsapi/whatsprot.class.php');
function login($usuario, $contrasena) {
 global $w, $mysqli;
 
 $debug = false;
 $nickname = "WaGroupBot";
$mysqli = new mysqli("ejemplo.com", "usuario", "contraseña", "whatsbotg");
$w = new WhatsProt($username, $identity, $nickname, $debug);
$w->connect();
$w->eventManager()->bind("onGetGroupMessage", "EsUnComando");
$w->loginWithPassword($password);
$w->PollMessages();
}

function traduccion($Gnumero, $texto)
{
global $mysqli;
$mysqli->query("SELECT idioma FROM grupos WHERE grupoid = '".$Gnumero."'");
$idioma = $mysqli->fetch_assoc();
$traduccion = explode("; ", fopen("traduccion-".$idioma.".txt", "r"));
return $traduccion[$texto];
}
function EsAdmin($numero, $grupo) {
global $mysqli;
$usuario = $mysqli->query("SELECT admin FROM usuarios_grupo WHERE numero = '".$numero."'");	
if ($usuario=="si") {
return true;	
}
else {
return false;	
}
}
function DeKick() {
global $w;
$kick = $mysqli->query("SELECT * FROM kick ORDER BY id ASC");
$kick->data_seek(0);
while ($datos = $kick->fetch_assoc()) {
$w->sendGroupsParticipantsAdd($datos[grupo], $datos[numero]);
$w->disconnect();
}	
}

function EsUnComando($mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $body)
{
global $mysqli, $w;
$EsComando = substr($body, 0, 1);
$Comando = substr($body, 1);
$Unumero = substr($Ujid,-9);
$Gnumero = substr($Gjid,-5);
if ($EsComando=='/') {
if ($mysqli->connect_errno) {
$w->sendMessage($Gnumero, "hay un problema en whatsbot, intentelo mas tarde");
}
else {
$mysqli->query("INSERT INTO usuarios_grupo (nombre) VALUES (".$name.")");
ComandosEx($Comando, $Unumero, $Gnumero, $name);
}
}
}
function ComandosEx($MSG, $Unumero, $Gnumero, $nombre)
{
global $mysqli, $w;	
$comando = explode(' ', $MSG);
if ($comando[0]=='hola') {
$w->sendMessage($Gnumero, traduccion($Gnumero, "1").$nombre.".");
$w->sendMessage($Gnumero, traduccion($Gnumero, "2"));	
}
else if ($comando[0]=='add') {
if ($comando[1]=='') {
$w->sendMessage($Gnumero, traduccion($Gnumero, "3"));
}
else {
unset($comando[0]);
$w->sendGroupsParticipantsAdd($Gnumero, $comando);
for ($i = 0; $i <= count($comando); $i++) {
$mysqli->query("INSERT INTO usuarios_grupo (numero, grupo) VALUES (".$comando[$i].", ".$Gnumero.")");
}
sleep(2);
$w->sendMessage($Gnumero, traduccion($Gnumero, "4"));
$w->sendMessage($grupo, traduccion($Gnumero, "5"));
}
}
else if ($comando[0]=='remove') {
if ($comando[1]=='') {
$w->sendMessage($Gnumero, traduccion($Gnumero, "3"));
} 
else {
unset($comando[0]);
$w->sendGroupsParticipantsRemove($Gnumero, $comando);
for ($i = 0; $i <= count($comando); $i++) {
$mysqli->query("DELENTE FROM usuarios_grupo WHERE numero = '".$comando[$i++]."'");
}
$w->sendMessage($Gnumero, traduccion($Gnumero, "6"));
}
}
else if ($comando[0]=='kick') {
if ($comando[1]=='') {
$w->sendMessage($Gnumero, traduccion($Gnumero, "7"));	
}
else {
$mysqli->query("INSERT INTO kick (numero, grupo) VALUES (".$comando[1].", ".$Gnumero.")");
$w->sendGroupsParticipantsRemove($Gjid, $comando[1]);
$w->sendMessage($Gnumero, traduccion($Gnumero, "8"));
}
}
}

function CrearGrupo($asunto, $participantes, $creador, $idioma)
{
global $w, $mysqli;
if ($mysqli->connect_errno) {
return false;
}
else {
$grupo = $w->sendGroupsChatCreate($asunto, $participantes);
$fecha = date("Y-m-d H:i:s");
$mysqli->query("INSERT INTO grupos (grupoid, fecha, idioma) VALUES (".$grupo.", ".$fecha.", ".$idioma.")");
for ($i = 0; $i <= count($participantes); $i++) {
$mysqli->query("INSERT INTO usuarios_grupo (numero, grupo) VALUES (".$participantes[$i].", ".$grupo.")");
}
$w->sendMessage($grupo, traduccion($grupo, "9").$asunto.traduccion($grupo, "10").$creador.traduccion($grupo, "11"));
$w->sendMessage($grupo, traduccion($Gnumero, "5"));
}
}
function AbGrupo($Gnumero, $Gjid, $participantes, $tipo)
{
global $w, $mysqli;
if ($tipo=='AB') {
$w->sendMessage($Gnumero, traduccion($Gnumero, "12"));
sleep(10);	
$w->sendMessage($Gnumero, traduccion($Gnumero, "13"));
sleep(5);
$w->sendMessage($Gnumero, traduccion($Gnumero, "14"));
sleep(5);
$mysqli->query("DELENTE FROM usuarios_grupo WHERE grupo = '".$Gnumero."'");
$mysqli->query("DELENTE FROM grupos WHERE grupo = '".$Gnumero."'");
$w->sendGroupsParticipantsRemove($Gnumero, $participantes);
sleep(1);
$w->sendGroupsChatEnd($Gjid);
}
else {
$w->sendMessage($Gnumero, traduccion($Gnumero, "15"));
$w->sendGroupsChatEnd($Gjid);	
}
}
?>