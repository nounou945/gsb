<?php

include("vues/v_sommaire.php");
($action=$_REQUEST['action']);
switch($action){ 
    case "voirFiches":
        $lesFiches=$pdo->getLesFiches();
        include("vues/v_voirLesFiches.php");
    break;
    case "details":
        $idVisiteur=$_REQUEST['id'];
        $leMois=$_REQUEST['mois'];
        $totalHF=($pdo->totalHF($idVisiteur,$leMois));
        $totalF=($pdo->totalF($idVisiteur,$leMois));
        
        $total=$totalF+$totalHF;
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
        $idVisiteur=$_REQUEST['id'];
        $leMois=$_REQUEST['mois'];
        $rb="rb";
        $pdo->majEtatFicheFrais($idVisiteur,$leMois,$rb);
        echo "mise en paiement effectuée";
    break;
    case 'rbAll':
        $pdo->rbAll();
        echo"mise en payement effectue pour tout les visiteurs";
    break;
    case 'pdf': //rajout
        $idVisiteur=$_REQUEST['id'];
        $leMois=$_REQUEST['mois'];
        $totalHF=($pdo->totalHF($idVisiteur,$leMois));
        $totalF=($pdo->totalF($idVisiteur,$leMois));
        
        $total=$totalF+$totalHF;
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
        $visiteur=$pdo->getInfosPdf($idVisiteur, $leMois);
        creerPdf($lesFraisForfait,$lesFraisHorsForfait,$idVisiteur,$leMois,$visiteur,$nbJustificatifs,$pdo,$libEtat,$montantValide);
       
       
        
        break;
        
    }
    
        
    
?>
