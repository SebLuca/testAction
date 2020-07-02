<?php
session_start();

// Classe contenant une méthode utilisée pour vérifier le contenu des posts des inputs générés
// lors de l'ajout ou de la modification d'une ligne.

// NB : Cette classe n'est pas dans le dossier "class", avec les autres, car elle est utilisée
// par appel de fichier et fonctionne de façon autonome, pas par instanciation extérieure.

$_SESSION['tmp'] = 0; // Compteur d'erreurs qui déterminera la destination du header.

class Verification
{
    public function verif($texte, $champ, $numMessage){

        // CONTROLE SI CHAMP VIDE.
        if (empty($texte)) {
            $_SESSION['erreur' . $numMessage] = 'Le champ ' . strtoupper($champ) . ' a élé laissé vide ! <br/>';
            $_SESSION['tmp']++;
        }
        else {
                // CONTROLE SI NOMBRE UNIQUEMENT.
                if (ctype_digit($texte)) {
                    if ($champ == "Nom" || $champ == "Prenom") {
                        $_SESSION['erreur' . $numMessage] = 'Erreur dans le champ ' . strtoupper($champ) . '  : valeur en texte simple requise ! <br/>';
                        $_SESSION['tmp']++;
                    }
                    if ($champ == "Age") {
                        $_SESSION['erreur3'] = '';
                    }
                }
                // CONTROLE SI TEXTE UNIQUEMENT.
                if (ctype_alpha($texte)) {
                    if ($champ == "Age"){
                        $_SESSION['erreur3'] = 'Erreur dans le champ AGE : valeur numérique requise ! <br/>';
                        $_SESSION['tmp']++;
                    }
                    if ($champ == "Nom" || $champ == "Prenom") {
                        $_SESSION['erreur' . $numMessage] = '';
                    }
                }
                // CONTROLE SI ON A ENTRE LES MARQUEURS (dans les champs du processus d'ajout).
                if ($texte == 'NOM' || $texte == 'PRENOM') {
                    $_SESSION['erreur' . $numMessage] = 'Veuillez entrer une valeur dans le champ ' . strtoupper($champ) . ' ! <br/>';
                    $_SESSION['tmp']++;
                }
        }
    }
}


$valide = new Verification(); // Instanciation pour utiliser la méthode.

$_POST['nom'] = strtoupper($_POST['nom']); // Mise en majuscule du nom (par conformité avec la mise en page du tableau).

$valide->verif($_POST['nom'],'Nom', '1'); // Vérification du champ "Nom".

$valide->verif($_POST['prenom'], 'Prenom', '2'); // Vérification du champ "Prenom".

$valide->verif($_POST['age'], 'Age', '3'); // Vérification du champ "Age".


// Concaténation des messages d'erreur en une seule variable.
$_SESSION['erreurALL'] = $_SESSION['erreur1'] . $_SESSION['erreur2'] . $_SESSION['erreur3'];


// Préparation des données à inclure dans le HEADER selon les conditions vérifiées.
if (empty($_SESSION['erreur1'])){
    $n = 'nom=' . $_POST['nom'];
}
else {
    $n = ""; // Si erreur, rend le texte intra-champ égal à rien pour ne pas rafficher une valeur antérieure éronnée.
}
if (empty($_SESSION['erreur2'])){
    $pn = '&prenom=' . $_POST['prenom'];
}
else {
    $pn = "";
}
if (empty($_SESSION['erreur3'])){
    $a = '&age=' . $_POST['age'];
}
else {
    $a = "";
}

// Concaténation de la requête HEADER.
$header = 'Location: index.php?' . $n . $pn . $a;

// Si aucune erreur, suite du processus :
if ($_SESSION['tmp'] == 0){
    // Sauvegarde des valeurs en session.
    $_SESSION['nom'] = $_POST['nom'];
    $_SESSION['prenom'] = $_POST['prenom'];
    $_SESSION['age'] = $_POST['age'];
    header('Location: action.php');
}
// Sinon, retour au formulaire ...
else header($header);?>
