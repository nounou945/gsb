<?php
require_once('mpdf60/mpdf.php');
$m_pdf= new M_pdf();
$m_pdf->pdf->WriteHtml('<html>'
        . '<div id="entete">
        <img src="./images/logo.jpg" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
      </div>
      <table style="text-align:center;"><tr><td style="font-size:30px;">Fiche de '.$visiteur['nom'].' '.$visiteur['prenom'].'</td><td style="font-size:30px;">en '.$leMois.'</td></tr></table>'
        . '</br></br></br></br></br>');
if(isset($lesFraisForfait)){
    $m_pdf->pdf->WriteHtml("<center><h3>descriptif des éléments forfaitaires</h3></center>"
            . "<table style='width:100%;border-collapse:collapse;border:2px;font-size:24px;'><tr>");
    foreach ( $lesFraisForfait as $unFraisForfait ){
     $m_pdf->pdf->WriteHtml('<th style="font-size:24px;border:2px solid black;text-align:center;">'.$unFraisForfait['libelle'].'</th>');
       //$m_pdf->pdf->WriteHtml('<th style="font-size:24px;border:2px solid black;text-align:center;>'.$unFraisForfait['libelle'].'</th>');
    }
    $m_pdf->pdf->WriteHtml('</tr ><tr>');
    foreach ( $lesFraisForfait as $unFraisForfait ){
     $m_pdf->pdf->WriteHtml('<td style="font-size:24px;border:2px solid black;text-align:center;">'.$unFraisForfait['quantite'].'</td>');
        //$m_pdf->pdf->WriteHtml('<td style="font-size:24px;border:2px solid black;text-align:center;">'.$unFraisForfait['quantite'].'</td>');
    }
    $m_pdf->pdf->WriteHtml("</tr></table>");
}
if(isset($lesFraisHorsForfait)){
    $m_pdf->pdf->WriteHtml("<h3 style='alignement:center;'>Descriptif des éléments hors forfait - ".$nbJustificatifs."justificatifs recus-</h3>");
    $m_pdf->pdf->WriteHtml('<table style="width:100%;display:inline-block;border-collapse:collapse;border:2px solid black;">'
            . ' <tr style="border:2px solid black;" >
                <th class="date" style="font-size:18px;border:2px solid black;">Date</th>
                <th class="libelle" style="font-size:24px;border:2px solid black;">Libellé</th>
                <th class="montant" style="font-size:24pxborder:2px solid black;;">Montant</th>                
             </tr>');
    foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
		  {
        $m_pdf->pdf->WriteHtml('<tr style="border:2px solid black;">');
        $m_pdf->pdf->WriteHtml('<td style="font-size:20px;">'.$unFraisHorsForfait["date"].'</td>');
        if($pdo->estRefuse($unFraisHorsForfait['id'])){
            $m_pdf->pdf->WriteHtml('<td style="font-size:20px;border:2px solid black;text-align:center;">REFUSE:'.$unFraisHorsForfait['libelle'].'</td>');
            
        }
        else{
            $m_pdf->pdf->WriteHtml('<td style="font-size:20px;border:2px solid black;text-align:center;">'.$unFraisHorsForfait['libelle'].'</td>');
        }
        $m_pdf->pdf->WriteHtml('<td style="font-size:20px;border:2px solid black;text-align:center;">'.$unFraisHorsForfait["montant"].'</td></tr>');
    }
    $m_pdf->pdf->WriteHtml('</table>');
    $m_pdf->pdf->WriteHtml('<h3>Etat Actuel</h3></br>');
    $m_pdf->pdf->WriteHtml('<p style="font-size:18px;">'.$libEtat.'</p></br></br></br>');
     $m_pdf->pdf->WriteHtml('<h3>Montant Valide</h3>');
      $m_pdf->pdf->WriteHtml('<p style="font-size:18px;">'.$montantValide.' euro</p></br>');
    
    
}
$m_pdf->pdf->Output();

