<?php

include("vues/v_sommaire.php");
($action=$_REQUEST['action']);
switch($action){
    case "validerInfo" :{
        $lesMois=$pdo->getLesMoisDisponibles2(); //  utilisation de la nouvelle fonction
		// Afin de s�lectionner par d�faut le dernier mois dans la zone de liste
		// on demande toutes les cl�s, et on prend la premi�re,
		// les mois �tant tri�s d�croissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
                $lesVisiteurs=$pdo->getAllVisiteurs();
                $lesCles2=array_keys($lesVisiteurs);
                $visiteurASelectionner = $lesCles[0];
        include("vues/v_validerInfo.php");
    }
}

