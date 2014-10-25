<?php 
require('whatsapi/whatsprot.class.php');
function login($usuario, $contrasena) {
 global $w, $mysqli;
 
 $debug = false;
 $nickname = "WaGroupBot";
$mysqli = new mysqli("ejemplo.com", "usuario", "contraseña", "basedatos");
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
$w->sendMessage($Gnumero, "porfavor escriba numeros de tlf.");
}
else {
unset($comando[0]);
$w->sendGroupsParticipantsAdd($Gnumero, $comando);
for ($i = 0; $i <= count($comando); $i++) {
$mysqli->query("INSERT INTO usuarios_grupo (numero, grupo VALUES (".$comando[$i].", ".$Gnumero.")");
}
sleep(2);
$w->sendMessage($Gnumero, "usuario/s añadido/s al grupo");
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
for ($i = 0; $i <= count($comando); $i++) {
$mysqli->query("DELENTE FROM usuarios_grupo WHERE numero = '".$comando[$i++]."'");
}
$w->sendMessage($Gnumero, "usuario/s eliminado/s del grupo");
}
}
else if ($comando[0]=='kick') {
if ($comando[1]=='') {
$w->sendMessage($Gnumero, "inserte un numero de telefono");	
}
else {
$mysqli->query("INSERT INTO kick (numero, grupo VALUES (".$comando[1].", ".$Gnumero.")");
$w->sendGroupsParticipantsRemove($Gjid, $comando[1]);

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
$mysqli->query("INSERT INTO grupos (grupoid, fecha VALUES (".$grupo.", ".$fecha.")");
for ($i = 0; $i <= count($participantes); $i++) {
$mysqli->query("INSERT INTO usuarios_grupo (numero, grupo VALUES (".$participantes[$i].", ".$grupo.")");
}
$w->sendMessage($grupo, "bienvenid@s al grupo '".$asunto."' creado por '".$creador."' \nusando Whatsbot by endes3000");
$w->sendMessage($grupo, "		comandos		\n/hola -te saluda. \n/add [numeros de telefonos separados por espacios] -añade participantes. \n/remove [numeros de telefonos separados por espacios] -quita participantes participantes.");
}
}
function AbGrupo($Gnumero, $Gjid, $participantes, $tipo)
{
global $w, $mysqli;
if ($tipo=='AB') {
$w->sendMessage($Gnumero, "se ha recibido unapeticion de eliminacion des este grupo \nel grupo se va a eliminar dentro de 20 segundos");
sleep(10);	
$w->sendMessage($Gnumero, "el grupo se va ha eliminar dentro de 10 segundos");
sleep(5);
$w->sendMessage($Gnumero, "el grupo se va a eliminar dentro de 5 segundos");
sleep(5);
$mysqli->query("DELENTE FROM usuarios_grupo WHERE grupo = '".$Gnumero."'");
$mysqli->query("DELENTE FROM grupos WHERE grupo = '".$Gnumero."'");
$w->sendGroupsParticipantsRemove($Gnumero, $participantes);
sleep(1);
$w->sendGroupsChatEnd($Gjid);
}
else {
$w->sendMessage($Gnumero, "se ha recibido una peticion para que whats bot deje de administrar este grupo \nadios.");
$w->sendGroupsChatEnd($Gjid);	
}
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
?>