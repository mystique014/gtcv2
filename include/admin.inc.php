<?php

include "connect.inc.php";
include "config.inc.php";
include "functions.inc.php";
include "$dbsys.inc.php";

// Settings
require_once("./include/settings.inc.php");

//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");

// Resume session
if (!grr_resumeSession()) {
    header("Location: ./logout.php?auto=1");
    die();
};

// Paramètres langage
include "language.inc.php";

?>