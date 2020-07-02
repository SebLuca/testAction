<?php
session_start();

include('class'.DIRECTORY_SEPARATOR.'Message.php');

switch ($_SESSION['etat']) {

    case 'MODIFIER':
        $etat = 'Modification annulée ...';
        break;
    case 'SUPPRIMER':
        $etat = 'Suppression annulée ...';
        break;
    case 'AJOUTER':
        $etat = 'Ajout  annulé ...';
        break;
}

// On purge le message d'erreur pour ne pas le réafficher d'emblée au prochain modif/ajout.
$_SESSION['erreurALL'] = '';

// On rétabit l'état normal.
$_SESSION['etat'] = 'NORMAL';

Message::communication($etat);

header('Location: index.php?');