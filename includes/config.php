<?php
// Configuration de la base de données
define('USER',"mma_user");
define('PASSWD',"");
define('SERVER',"localhost");
define('BASE',"mma_election");

// Pour compatibilité avec les nouvelles fonctions
define('DB_HOST', SERVER);
define('DB_NAME', BASE);
define('DB_USER', USER);
define('DB_PASS', PASSWD);

// Configuration de l'application
define('SITE_NAME', 'MMA Fighter Election');
define('BASE_URL', 'http://localhost/sae3_mma_charpentier_errebache/');

// Charger les classes et fonctions (Architecture SOLID légère)
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Candidat.php';
require_once __DIR__ . '/Electeur.php';
require_once __DIR__ . '/functions_candidats.php';
require_once __DIR__ . '/functions_votes.php';
require_once __DIR__ . '/functions_email.php';
require_once __DIR__ . '/functions_codes.php';
require_once __DIR__ . '/functions_utils.php';

// Connexion à la base de données
function dbconnect(){
  return Database::getInstance()->getConnection();
}
?>
