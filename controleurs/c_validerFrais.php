<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("vues/v_sommaire.php");
($action=$_REQUEST['action']);
switch($action){ 
    case "validerInfo":
    {           $lesMois=$pdo->getLesMoisDisponibles2(); 
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
                $lesVisiteurs=$pdo->getAllVisiteurs();
                $lesCles2 = array_keys( $lesVisiteurs );
		$visiteurASelectionner = $lesCles2[0];
     
      include("vues/v_validerInfo.php");
      break;
    }
    case "consulterFicheDetail":
               $idVisiteur=$_POST['lstVisiteur'];
               $leMois=$_POST['lstMois'];
               

		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		$libEtat = $lesInfosFicheFrais['libetat'];
		$montantValide = $lesInfosFicheFrais['montantvalide'];
		$nbJustificatifs = $lesInfosFicheFrais['nbjustificatifs'];
		$dateModif =  $lesInfosFicheFrais['datemodif'];
		$dateModif =  dateAnglaisVersFrancais($dateModif);
              
                
              
                include("vues/v_detailFrais.php");
                if(isset($_GET['reporter'])){
                  $idHFselec=$GET['reporter'] ;
                  $pdo->reporterFraisHorsForfait($idHFselec);
                  ?>
<h3>Frais HorsForfait reporter </h3>
<?php 
                  
                }
        break;
    
        
        
        
    }
