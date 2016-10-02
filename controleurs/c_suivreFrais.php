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
        
                
 break ;  
        }

    case "validerVisiteur":
        
        
    break;
        
    }
