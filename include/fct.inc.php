<?php
/** 
 * Fonctions pour l'application GSB
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 */
 /**
 * Teste si un quelconque visiteur est connecté
 * @return vrai ou faux 
 */
function estConnecte(){
  return isset($_SESSION['idVisiteur']);
}
/**
 * Enregistre dans une variable session les infos d'un visiteur
 
 * @param $id 
 * @param $nom
 * @param $prenom
 */
function connecter($id,$nom,$prenom,$comptable){ 
	$_SESSION['idVisiteur']= $id; 
	$_SESSION['nom']= $nom;
	$_SESSION['prenom']= $prenom;
        $_SESSION['comptable']=$comptable; //on rajoute compt pour se con en compt
}
/**
 * Détruit la session active
 */
function deconnecter(){
	session_destroy();
}
/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
 
 * @param $madate au format  jj/mm/aaaa
 * @return la date au format anglais aaaa-mm-jj
*/
function dateFrancaisVersAnglais($maDate){
	@list($jour,$mois,$annee) = explode('/',$maDate);
	return date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee));
}
/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa 
 
 * @param $madate au format  aaaa-mm-jj
 * @return la date au format format français jj/mm/aaaa
*/
function dateAnglaisVersFrancais($maDate){
   @list($annee,$mois,$jour)=explode('-',$maDate);
   $date="$jour"."/".$mois."/".$annee;
   return $date;
}
/**
 * retourne le mois au format aaaamm selon le jour dans le mois
 
 * @param $date au format  jj/mm/aaaa
 * @return le mois au format aaaamm
*/
function getMois($date){
		@list($jour,$mois,$annee) = explode('/',$date);
		if(strlen($mois) == 1){
			$mois = "0".$mois;
		}
		return $annee.$mois;
}

/* gestion des erreurs*/
/**
 * Indique si une valeur est un entier positif ou nul
 
 * @param $valeur
 * @return vrai ou faux
*/
function estEntierPositif($valeur) {
	return preg_match("/[^0-9]/", $valeur) == 0;
	
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 
 * @param $tabEntiers : le tableau
 * @return vrai ou faux
*/
function estTableauEntiers($tabEntiers) {
	$ok = true;
	foreach($tabEntiers as $unEntier){
		if(!estEntierPositif($unEntier)){
		 	$ok=false; 
		}
	}
	return $ok;
}
/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 
 * @param $dateTestee 
 * @return vrai ou faux
*/
function estDateDepassee($dateTestee){
	$dateActuelle=date("d/m/Y");
	@list($jour,$mois,$annee) = explode('/',$dateActuelle);
	$annee--;
	$AnPasse = $annee.$mois.$jour;
	@list($jourTeste,$moisTeste,$anneeTeste) = explode('/',$dateTestee);
	return ($anneeTeste.$moisTeste.$jourTeste < $AnPasse); 
}
/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa 
 
 * @param $date 
 * @return vrai ou faux
*/
function estDateValide($date){
	$tabDate = explode('/',$date);
	$dateOK = true;
	if (count($tabDate) != 3) {
	    $dateOK = false;
    }
    else {
		if (!estTableauEntiers($tabDate)) {
			$dateOK = false;
		}
		else {
			if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
				$dateOK = false;
			}
		}
    }
	return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques 
 
 * @param $lesFrais 
 * @return vrai ou faux
*/
function lesQteFraisValides($lesFrais){
	return estTableauEntiers($lesFrais);
}
/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant 
 
 * des message d'erreurs sont ajoutés au tableau des erreurs
 
 * @param $dateFrais 
 * @param $libelle 
 * @param $montant
 */
function valideInfosFrais($dateFrais,$libelle,$montant){
	if($dateFrais==""){
		ajouterErreur("Le champ date ne doit pas être vide");
	}
	else{
		if(!estDatevalide($dateFrais)){
			ajouterErreur("Date invalide");
		}	
		else{
			if(estDateDepassee($dateFrais)){
				ajouterErreur("date d'enregistrement du frais dépassé, plus de 1 an");
			}			
		}
	}
	if($libelle == ""){
		ajouterErreur("Le champ description ne peut pas être vide");
	}
	if($montant == ""){
		ajouterErreur("Le champ montant ne peut pas être vide");
	}
	else
		if( !is_numeric($montant) ){
			ajouterErreur("Le champ montant doit être numérique");
		}
}
/**
 * Ajoute le libellé d'une erreur au tableau des erreurs 
 
 * @param $msg : le libellé de l'erreur 
 */
function ajouterErreur($msg){
   if (! isset($_REQUEST['erreurs'])){
      $_REQUEST['erreurs']=array();
	} 
   $_REQUEST['erreurs'][]=$msg;
}
/**
 * Retoune le nombre de lignes du tableau des erreurs 
 
 * @return le nombre d'erreurs
 */
function nbErreurs(){
   if (!isset($_REQUEST['erreurs'])){
	   return 0;
	}
	else{
	   return count($_REQUEST['erreurs']);
	}
}
/**
 * classe technique mpdf
 */
class M_pdf {
 
    public $param;
    public $pdf;
    public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    {
        $this->param =$param;
        $this->pdf = new mPDF($this->param);
    }
}


/**
 * 
 * @param type $lesFraisFortfaits
 * @param type $lesFraisHorsForfaits
 * @param type $idVisiteur
 * @param type $mois
 * @param type $visiteur
 * @param type $nbJustificatifs
 * @param type $pdo
 * @param type $libEtat
 * @param type $montantValide
 */
function creerPdf($lesFraisFortfaits,$lesFraisHorsForfaits,$idVisiteur,$mois,$visiteur,$nbJustificatifs,$pdo,$libEtat,$montantValide){
require_once('mpdf60/mpdf.php');
$m_pdf= new M_pdf();
$m_pdf->pdf->WriteHtml('<html>'
        . '<div id="entete">
        <img src="./images/logo.jpg" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
      </div>
      <table style="text-align:center;"><tr><td style="font-size:30px;">Fiche de '.utf8_encode($visiteur['nom']).' '.utf8_encode($visiteur['prenom']).'</td><td style="font-size:30px;">en '.utf8_encode($mois).'</td></tr></table>'
        . '</br></br></br></br></br>');
if(isset($lesFraisFortfaits)){
    $m_pdf->pdf->WriteHtml("<center><h3>descriptif des el�ments forfaitaires</h3></center>"
            . "<table style='width:100%;border-collapse:collapse;border:2px;font-size:24px;'><tr>");
    foreach ( $lesFraisFortfaits as $unFraisForfait ){
     $m_pdf->pdf->WriteHtml('<th style="font-size:24px;border:2px solid black;text-align:center;">'.  utf8_decode($unFraisForfait['libelle']).'</th>');   
    }
    $m_pdf->pdf->WriteHtml('</tr ><tr>');
    foreach ( $lesFraisFortfaits as $unFraisForfait ){
     $m_pdf->pdf->WriteHtml('<td style="font-size:24px;border:2px solid black;text-align:center;">'.utf8_encode($unFraisForfait['quantite']).'</td>');
    }
    $m_pdf->pdf->WriteHtml("</tr></table>");
}
/*if(isset($lesFraisHorsForfaits)){
    $m_pdf->pdf->WriteHtml("<h3 style='alignement:center;'>Descriptif des �l�ments hors forfait - ".utf8_encode($nbJustificatifs)."justificatifs re�us-</h3>");
    $m_pdf->pdf->WriteHtml('<table style="width:100%;display:inline-block;border-collapse:collapse;border:2px solid black;">'
            . ' <tr style="border:2px solid black;" >
                <th class="date" style="font-size:18px;border:2px solid black;">Date</th>
                <th class="libelle" style="font-size:24px;border:2px solid black;">Libell�</th>
                <th class="montant" style="font-size:24pxborder:2px solid black;;">Montant</th>                
             </tr>');
    foreach ( $lesFraisHorsForfaits as $unFraisHorsForfait ) 
		  {
        $m_pdf->pdf->WriteHtml('<tr style="border:2px solid black;">');
        $m_pdf->pdf->WriteHtml('<td style="font-size:20px;">'.utf8_encode($unFraisHorsForfait["date"]).'</td>');
        if($pdo->estRefuse($unFraisHorsForfait['id'])){
            $m_pdf->pdf->WriteHtml('<td style="font-size:20px;border:2px solid black;text-align:center;">REFUSE:'.utf8_encode($unFraisHorsForfait['libelle']).'</td>');
            
        }
        else{
            $m_pdf->pdf->WriteHtml('<td style="font-size:20px;border:2px solid black;text-align:center;">'.utf8_encode($unFraisHorsForfait['libelle']).'</td>');
        }
        $m_pdf->pdf->WriteHtml('<td style="font-size:20px;border:2px solid black;text-align:center;">'.utf8_encode($unFraisHorsForfait["montant"]).'</td></tr>');
    }
    $m_pdf->pdf->WriteHtml('</table>');
    $m_pdf->pdf->WriteHtml('<h3>Etat Actuel</h3></br>');
    $m_pdf->pdf->WriteHtml('<p style="font-size:18px;">'.utf8_encode($libEtat).'</p></br></br></br>');
     $m_pdf->pdf->WriteHtml('<h3>Montant Valide</h3>');
      $m_pdf->pdf->WriteHtml('<p style="font-size:18px;">'.utf8_encode($montantValide).'?</p></br>');
    
    
}*/
$m_pdf->pdf->Output();
}
?>
