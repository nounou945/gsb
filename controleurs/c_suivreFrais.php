<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("vues/v_sommaire.php");
($action=$_REQUEST['action']);
$idVisiteur=$_SESSION['idVisiteur'];
switch($action){
    case "validerMois" :
    {$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
        include("vues/v_validerMois.php");
        
 break ;  
        }

    case "validerVisiteur":
        include("vues/v_validerVisiteur.php");
    break;
        
    }
