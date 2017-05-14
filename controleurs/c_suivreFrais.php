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
        
        if (isset($_POST['lstMois'])||isset($_POST['mois'])){
                if( isset($_POST['mois'])){
                    $mois=$_POST['mois']; 
                }
                else{
                    $mois=$_POST['lstMois'];
                }
                $lesVisiteurs=$pdo->getLesVisiteurs($mois);
               $lesCles= array_keys($lesVisiteurs);
               $idASelectionner=$lesCles[0];
       
            include("vues/v_validerVisiteur.php");
        
            
        }
       if(isset($_POST["lstVisiteur"])){
                $idVisiteur=$_POST['lstVisiteur'];
           $leMois=$_POST['mois'];
           
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
      include("vues/v_detailFrais.php");
        
                } 
       if(isset($_REQUEST['idRP'])){
           $idRP=(int)($_REQUEST['idRP']);
           $pdo->reporterFraisHorsForfait($idRP);
           
           //echo "le frais HF choisi a ete reporte";
           
       }
        if(isset($_REQUEST['idRF'])){
            $idRF=(int)($_REQUEST['idRF']);
            var_dump($idRF);
             $pdo->refuFraisHorsForfait($idRF);
             echo "le frais HF choisi a ete refuse";
        }
                
 break ;  
        }
    case "validerFiche":
        $mois=$_POST["moisVA"];
        $idV=$_POST["idVisiteurVA"];
        $va="va";
        $pdo->majEtatFicheFrais($idV,$mois,$va);
        
    }
