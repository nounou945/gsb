<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("vues/v_sommaire.php");
($action=$_REQUEST['action']);
switch($action){
    case "validerMois" :
    {$lesMois=$pdo->getLesMoisDisponibles2(); //  utilisation de la nouvelle fonction
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
        include("vues/v_validerMois.php");
        
        if (isset($_POST['lstMois'])){
           $mois=$_POST['lstMois']; 
            $lesVisiteurs=$pdo->getLesVisiteurs($mois);
               $lesCles= array_keys($lesVisiteurs);
               $idASelectionner=$lesCles[0];
            include("vues/v_validerVisiteur.php");
        
            
        }
       if(isset($_POST['$lstVisiteurs'])){
           
                } 
       
                
 break ;  
        }

    case "ficheFrais":
        $idVisiteur=$_POST['lstVisiteurs'];
           $leMois=$_POST['mois'];
           echo $leMois.$idVisiteur;
		
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		$libEtat = $lesInfosFicheFrais['libEtat'];
		$montantValide = $lesInfosFicheFrais['montantValide'];
		$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
		$dateModif =  $lesInfosFicheFrais['dateModif'];
		$dateModif =  dateAnglaisVersFrancais($dateModif);
      include("vues/v_listeEtatFrais2.php");
        
    break;
        
    }
