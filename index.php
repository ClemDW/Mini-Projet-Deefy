<?php

// ################################
//       AUTOLOADER (COMPOSER)
// ################################
/*
    Pour générer l'autoloader composer :
        1 -> Aller sur https://getcomposer.org/download/ et rentrer les lignes de commandes
            (dans votre dossier où il y a vos classes) pour installer composer
        2 -> Aller sur le terminal et faire : 'php composer.phar install'
        3 -> Mettre la ligne de commandes ci-dessous dans l'index
*/
require_once 'vendor/autoload.php';

// ----------------------------------------------------------- \\

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\dispatch\Dispatcher;

// définir la configuration BD 1 fois au démarrage de l'application \
\iutnc\deefy\db\ConnectionFactory::setConfig('db.config.ini');
session_start();

$dispatcher = new Dispatcher();
$dispatcher->run();