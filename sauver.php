<?php
session_start();

include('class'.DIRECTORY_SEPARATOR.'Message.php'); // --> gère la contruction et l'affichage des messages du chan.

// Ce code restaure la DB en supprimant le fichier "esa.sqlite" pour ensuite
// le remplacer par une copie de son backup ("safe.sqlite") contenu dans le dossier "datas".

// Création de strings contenant les adresses des fichiers en question.
$lien1 = "esa.sqlite";
$lien2 = "datas".DIRECTORY_SEPARATOR."safe.sqlite";

unlink($lien2); // Suppression du fichier contenant la DB !
copy($lien1, $lien2); // Copie du backup et renommage de la copie.

// Préparation du message de confirmation :
Message::communication("Changements sauvegardés !");

// On rétablit l'état "Normal" au cas où on aurait reseté pendant un processus en cours.
$_SESSION['etat'] = 'NORMAL';

// Et on retourne à l'index.
header('Location: index.php?');?>