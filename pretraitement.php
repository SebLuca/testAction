<?php
session_start();

include('class'.DIRECTORY_SEPARATOR.'SqlSlave.php'); // --> sélections spécifiques dans la DB.


// Lancement de l'interface adéquate à un processus lancé dans l'index (modif, suppression ou ajout).
// Pour les modifs et suppressions, on détachera le préfixe alphabétique de l'ID submitté par le bouton.
// Pour l'ajout, on saura qu'il s'agit de lui grâce au "+" de sa value "+Ajouter" x)


$prefixe = $_POST['option'][0]; // On récupère le préfixe du POST.
$_SESSION['id'] = substr($_POST['option'],1); // On récupère l'ID du champ concerné qu'on stocke en session.



// On va utiliser le préfixe pour définir l'origine de son ID, lequel sera traité en conséquence.

if ($prefixe == 'm'){ // ==== si MODIFIER ====

    $ligne = SqlSlave::specifique('getNOM') . ' '; // Récupération de données utiles au processus.
    $ligne .= SqlSlave::specifique('getPRENOM');
    $ligne .= ', ' . SqlSlave::specifique('getAGE') . ' ans';
    $_SESSION['tmpp'] = $ligne;

    $_SESSION['etat'] = 'MODIFIER'; // On lance un nouvel état qui adaptera la div interface au processus choisi.
    header('Location: index.php?');
}

if ($prefixe == 's'){ // ==== si SUPPRIMER ====

    $ligne = SqlSlave::specifique('getPRENOM') . ' '; // Récupération de données utiles au processus.
    $ligne .= SqlSlave::specifique('getNOM');
    $_SESSION['tmpp'] = $ligne;

    $_SESSION['etat'] = 'SUPPRIMER'; // On lance un nouvel état qui adaptera la div interface au processus choisi.
    header('Location: index.php?');
}
if ($prefixe == '+') { // ==== si AJOUTER ====

    $_SESSION['etat'] = 'AJOUTER'; // On lance un nouvel état qui adaptera la div interface au processus choisi.
    header('Location: index.php?');
}?>