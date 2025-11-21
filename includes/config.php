<?php
// Configuration de la base de données
define('USER',"mma_user");
define('PASSWD',"");
define('SERVER',"localhost");
define('BASE',"mma_election");

// Configuration de l'application
define('SITE_NAME', 'MMA Fighter Election');
define('BASE_URL', 'http://localhost/sae3_mma_charpentier_errebache/');

// Connexion à la base de données
function dbconnect(){
  $dsn="mysql:dbname=".BASE.";host=".SERVER; 
  try{ 
    $connexion=new PDO($dsn,USER,PASSWD); 
    $connexion->exec("set names utf8"); //Support utf8
  } 
  catch(PDOException $e){ 
    printf("Échec de la connexion: %s\n", $e->getMessage()); 
    exit(); 
  } 
  return $connexion; 
}
?>
