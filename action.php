<?php
session_start();

include('class'.DIRECTORY_SEPARATOR.'Message.php'); // --> gère la contruction et l'affichage des messages du chan.

try {

    $pdo = new PDO('sqlite:esa.sqlite'); // Connexion ...

}
catch(PDOException $e){ // Si erreurs ...

    echo $e->getMessage() . '<br/>';
    echo $e->getLine();
}

switch ($_SESSION['etat']) {

    //  ======== A appliquer si état "MODIFIER" : ========
    case 'MODIFIER':
        $sql = "UPDATE participant SET nom_participant = :nom,
            prenom_participant = :prenom,
            age_participant = :age
            WHERE id_participant = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $_SESSION['nom'], PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $_SESSION['prenom'], PDO::PARAM_STR);
        $stmt->bindParam(':age', $_SESSION['age'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Message de confirmation :
        Message::communication("Modification réussie de la ligne " . chr(39) . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . chr(39) . ".");

        break;


    //  ======== A appliquer si état "SUPPRIMER" : ========
    case 'SUPPRIMER':

        $sql = "DELETE FROM participant WHERE id_participant =  :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Message de confirmation :
        Message::communication("Suppression de la ligne " . chr(39) . $_SESSION['tmpp'] . chr(39) . " effectuée.");

        break;

    //  ======== A appliquer si état "AJOUTER" : ========
    case 'AJOUTER':

        $sql = "INSERT INTO participant (nom_participant, prenom_participant, age_participant) VALUES(?,?,?)";

        $r = $pdo->prepare($sql);

        $r->execute([$_SESSION['nom'], $_SESSION['prenom'], $_SESSION['age']]);

        // Message de confirmation :
        Message::communication("Ajout de la ligne " . chr(39) . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . chr(39) . " réalisé avec succès !");

        break;
}

// Le processus effectué, on revient à l'interface normale.
$_SESSION['etat'] = 'NORMAL';

header('Location: index.php?');?>