<!-- #Hola... Pos deberia ponerme a hacer esto no crees?
#Al chile yo creo que si
#crees que la profe se moleste si no tenemos la database lista para mañana? -->
<!-- probablemente la vdd, de base de datos estoy manejando poquito -->
<?php

$host = 'localhost';
$dbname = 'nombre_base_datos';
$username = 'usuario';
$password = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
#Esto es un boceto de lo que hay que hacer, 
#lo encontre de otra base de datos pero no me he puesto aun en esto