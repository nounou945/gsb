<?php

include("vues/v_sommaire.php");
($action=$_REQUEST['action']);
switch($action){ 
    case "voirFiches":
        $lesFiches=$pdo->getLesFiches();
        include("vues/v_voirLesFiches.php");
    break;
    case "details":
        $idVisiteur=$_SESSION['id'];
        $leMois=$_SESSION['mois'];
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
        $lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
	$numAnnee =substr( $leMois,0,4);
	$numMois =substr( $leMois,5,2);
                
	$libEtat = $lesInfosFicheFrais['libetat'];
	$montantValide = $lesInfosFicheFrais['montantvalide'];
	$nbJustificatifs = $lesInfosFicheFrais['nbjustificatifs'];
	$dateModif =  $lesInfosFicheFrais['datemodif'];
	$dateModif =  dateAnglaisVersFrancais($dateModif);
        include("vues/v_detailsFiche.php");
    break;   
    case 'rembourse':
        $idVisiteur=$_SESSION['id'];
        $leMois=$_SESSION['mois'];
        $rb="rb";
        $pdo->majEtatFicheFrais($idVisiteur,$leMois,$rb);
        echo "mise en paiement";
    break;
    }
    
?>