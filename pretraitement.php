<?php
session_start();

include('class'.DIRECTORY_SEPARATOR.'SqlSlave.php'); // --> s�lections sp�cifiques dans la DB.


// Lancement de l'interface ad�quate � un processus lanc� dans l'index (modif, suppression ou ajout).
// Pour les modifs et suppressions, on d�tachera le pr�fixe alphab�tique de l'ID submitt� par le bouton.
// Pour l'ajout, on saura qu'il s'agit de lui gr�ce au "+" de sa value "+Ajouter" x)


$prefixe = $_POST['option'][0]; // On r�cup�re le pr�fixe du POST.
$_SESSION['id'] = substr($_POST['option'],1); // On r�cup�re l'ID du champ concern� qu'on stocke en session.



// On va utiliser le pr�fixe pour d�finir l'origine de son ID, lequel sera trait� en cons�quence.

if ($prefixe == 'm'){ // ==== si MODIFIER ====

    $ligne = SqlSlave::specifique('getNOM') . ' '; // R�cup�ration de donn�es utiles au processus.
    $ligne .= SqlSlave::specifique('getPRENOM');
    $ligne .= ', ' . SqlSlave::specifique('getAGE') . ' ans';
    $_SESSION['tmpp'] = $ligne;

    $_SESSION['etat'] = 'MODIFIER'; // On lance un nouvel �tat qui adaptera la div interface au processus choisi.
    header('Location: index.php?');
}

if ($prefixe == 's'){ // ==== si SUPPRIMER ====

    $ligne = SqlSlave::specifique('getPRENOM') . ' '; // R�cup�ration de donn�es utiles au processus.
    $ligne .= SqlSlave::specifique('getNOM');
    $_SESSION['tmpp'] = $ligne;

    $_SESSION['etat'] = 'SUPPRIMER'; // On lance un nouvel �tat qui adaptera la div interface au processus choisi.
    header('Location: index.php?');
}
if ($prefixe == '+') { // ==== si AJOUTER ====

    $_SESSION['etat'] = 'AJOUTER'; // On lance un nouvel �tat qui adaptera la div interface au processus choisi.
    header('Location: index.php?');
}?>