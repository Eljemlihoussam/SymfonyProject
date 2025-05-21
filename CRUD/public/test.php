<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "PHP est bien configuré pour afficher les erreurs.<br>";

// Essayez de provoquer une erreur pour voir si elle s'affiche
//$nonExistentVariable->method();

echo "Ce script s'est exécuté.<br>";

// Tentez d'inclure le fichier d'index principal de Symfony
// require __DIR__.'/index.php';

?> 