<?php
session_start();

// Appel de classes :
include('class'.DIRECTORY_SEPARATOR.'SqlReader.php'); // --> gère la sélection dans la DB.
include('class'.DIRECTORY_SEPARATOR.'Message.php'); // --> gère la contruction et l'affichage des messages du chan.
include('class'.DIRECTORY_SEPARATOR.'Changement.php'); // --> gère l'import d'un formulaire à la place du chan.

// Entier retenant l'état à attribuer à la div "interface" en fonction des actions en cours.
if(!isset($_SESSION['etat'])) $_SESSION['etat'] = 'NORMAL';

// Globales véhiclant les messages d'erreur.
if(!isset($_SESSION['erreur1'])) $_SESSION['erreur1'] = '';
if(!isset($_SESSION['erreur2'])) $_SESSION['erreur2'] = '';
if(!isset($_SESSION['erreur3'])) $_SESSION['erreur3'] = '';
if(!isset($_SESSION['erreurALL'])) $_SESSION['erreurALL'] = '';

// Globales fourre-tout.
if(!isset($_SESSION['tmp'])) $_SESSION['tmp'] = 0;
if(!isset($_SESSION['tmpp'])) $_SESSION['tmpp'] = 0;
if(!isset($_SESSION['id'])) $_SESSION['id'] = 0;
if(!isset($_SESSION['nom'])) $_SESSION['nom'] = '';
if(!isset($_SESSION['prenom'])) $_SESSION['prenom'] = '';
if(!isset($_SESSION['age'])) $_SESSION['age'] = 0;

// Tableau et variables globales dont le contenu s'ajoutera à la requête de sélection
// afin de pouvoir trier l'affichage des données de trois façons différentes.
$ordres  = array(
    "nom_participant ASC"=>"Trier par ordre alphabétique",
    "nom_participant DESC"=>"Trier par ordre anti-alphabétique",
    "age_participant"=>"Trier par Age");

// Les données seront triées par ordre alphabétique si aucun autre choix n'a été fait.
if ($_POST) {
    $_SESSION['ordre'] = $_POST['ordrePost'];
}
elseif (!isset($_SESSION['ordre'])){
    $_SESSION['ordre'] = "nom_participant ASC";
}?>

<!DOCTYPE html>

<html>
   
    <head>
    
        <meta charset="utf-8">
        <title>Liste des élèves</title>
        <meta name="description" content="Les élèves de la classe d'INFO3 de l'ESA">
        <link href="style.css" type="text/css" rel="stylesheet"/>
        <link rel="SHORTCUT ICON" href="datas/favicon.gif">

        <!-- Appel des scripts JS permettant l'utilisation de barres de défilement faites maison :D -->
        <link href="flexcrollstyles.css" rel="stylesheet" type="text/css" />
        <script type='text/javascript' src="flexcroll.js"></script>
        
    </head>
    
    <body>
        
        <!-- ############################# ECRAN ############################# -->
        
        <br/>
        <div id="contener">
            
            <div id="ecran_HAUT"></div>
            <div id="ecran_CENTRE">
            
            <!-- TITRE DE LA PAGE, indiquant aussi le nombre d'élèves depuis la variable session "nombre".-->

            <?php // Appel des données via la fonction de la classe effectuant le SELECT dans la DB.
            SqlReader::select('nombre');?>

            <div class="titre">Esa INFO3 (2015-2016) : <span class="count"><?php echo $_SESSION['nombre']; ?></span> élèves.</div>


                <!-- Bouton "Sauver", il sauvegarde réllement les modifications dans la DB. -->
                <input type="button" class="boutonCarre" value="Sauver" title="Enregistrer les changements dans la DB" onclick="self.location.href='sauver.php'">

                <!-- Bouton "Reset", il recrée la DB avec ses données de bases intactes. -->
                <input type="button" class="boutonCarre" id="reset" value="Reset" title="Annuler les changements" onclick="self.location.href='reset.php'">


                <!-- Liste défilante permettant le choix de l'ORDER BY des données -->
                <form name="choixOrdre" method="post" action="index.php" title="Choisir l'ordre d'affichage des données">
                    <select id="ordreform" onchange="document.choixOrdre.submit();" name="ordrePost">
                        <option value="<?php echo $_SESSION['ordre'] ?>"><?php echo $ordres[$_SESSION['ordre']] ?></option>

                        <?php
                        foreach($ordres as $sql => $fr) :

                        if ($sql != $_SESSION['ordre']): ?>

                        <option value="<?php echo $sql ?>"><?php echo $fr ?></option>

                        <?php endif; ?>

                        <?php endforeach; ?>

                    </select>
                </form>
                
                <br/><hr/>

                <?php
                // Appel des données via la fonction de la classe effectuant le SELECT dans la DB.
                SqlReader::select('liste');?>

                <hr/>
                
                <div id="interface1">

                    <div id="chan" class='flexcroll'>

                    <?php

                        switch ($_SESSION['etat']) {

                            //  ======== A appliquer à dans div "interface" si état "NORMAL" : ========
                            case 'NORMAL':

                                // Message de bienvenue à la connexion.
                                if(!isset($_SESSION['message'])){
                                    $_SESSION['message'] = array();
                                    Message::communication("Bienvenue !");
                                }
                                // Messages dans le chan.
                                else Message::communication("");
                                break;

                            //  ======== A appliquer à dans div "interface" si état "MODIFIER" : ========
                            case 'MODIFIER':
                                Changement::modification();
                                break;

                            //  ======== A appliquer à dans div "interface" si état "SUPPRIMER" : ========
                            case 'SUPPRIMER':
                                Changement::suppression();
                                break;

                            //  ======== A appliquer à dans div "interface" si état "AJOUTER" : ========
                            case 'AJOUTER':
                                Changement::modification();
                                break;
                        }?>
                    </div>
                </div>
                
                <!-- Bouton "Ajouter", pour entrer une nouvelle ligne dans la DB. -->
                <?php if($_SESSION['etat'] == 'NORMAL') { ?>
                <form method="post" action="pretraitement.php" >
                <input class="boutonCarre" id="boutonajouter" type="submit" name="option" value="+Ajouter" title="Ajouter un nouvel élève">
                </form><?php }?>

            </div>

            <div id="ecran_BAS"></div>
            
        </div>
        
    </body>
    
</html>