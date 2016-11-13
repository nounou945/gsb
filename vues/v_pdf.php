<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function creerPdf($lesFraisFortfaits,$lesFraisHorsForfaits,$visiteur,$mois){
    
   
// permet d'inclure la biblioth�que fpdf
require('fpdf/fpdf.php');

// instancie un objet de type FPDF qui permet de cr�er le PDF
$pdf=new FPDF();
// ajoute une page
$pdf->AddPage();
// d�finit la police courante
$pdf->SetFont('arial','B',12);
// affiche du texte
$pdf->Image ("images/iconePdf.png",30,10,150,48 );
$pdf->Cell(35,10,'Visiteur',0,1);
$pdf->Cell(35,10,'Nom',0,1);
$pdf->Cell(40,10,"Nom :" .$visiteur['nom'],0);
$pdf->Cell(30,10, $visiteur['prenom'],0,1);
$pdf->Cell(30,10,"Le mois:" .$mois,0,1);


ob_end_clean(); //bloque les affichages apr�s cette methode
// Enfin, le document est termin� et envoy� au navigateur gr�ce � Output().
$pdf->Output();


    
}?>