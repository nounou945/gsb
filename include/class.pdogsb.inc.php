<?php
/** 
 * Classe d'acc�s aux donn�es. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi�
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php 
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname= sjeanphilippe';   		
      	private static $user='sjeanphilippe' ;    		
      	private static $mdp='eweeNg9e' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur priv�, cr�e l'instance de PDO qui sera sollicit�e
 * pour toutes les m�thodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui cr�e l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le pr�nom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom,visiteur.comptable as comptable from visiteur 
		where visiteur.login='$login' and visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concern�es par les deux arguments
 
 * La boucle foreach ne peut �tre utilis�e ici car on proc�de
 * � une modification de la structure it�r�e - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donn�
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concern�es par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantit� sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite,lignefraisforfait.idfraisforfait as fraisforfait from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met � jour la table ligneFraisForfait
 
 * Met � jour la table ligneFraisForfait pour un visiteur et
 * un mois donn� en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de cl� idFrais et de valeur la quantit� pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}
/**
 * met � jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concern�
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur poss�de une fiche de frais pour le mois pass� en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Cr�e une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donn�s
 
 * r�cup�re le dernier mois en cours de traitement, met � 'CL' son champs idEtat, cr�e une nouvelle fiche de frais
 * avec un idEtat � 'CR' et cr�e les lignes de frais forfait de quantit�s nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idetat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Cr�e un nouveau frais hors forfait pour un visiteur un mois donn�
 * � partir des informations fournies en param�tre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format fran�ais jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant',0)";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est pass� en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de cl� un mois -aaaamm- et de valeurs l'ann�e et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,5,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
        
        /**
         * retourne les mois disponibles selon les comptables qui on des fiches de  ?alider.
         * @return type
         */
        /**
         * retourne les mois ou les visiteurs ont des fiches de frais � l'etat cr
         * @return type
         */
        public function getLesMoisDisponibles2(){ 
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur in(select id from visiteur where comptable=0)
                    and idetat='cl'
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,5,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
        /**
         * retourne la liste des visiteurs qui ont des fiches de frais pour le mois selection�
         * @param type $mois
         * @return type
         */
        public function getLesVisiteurs($mois){
		$req = "select visiteur.id,visiteur.nom,visiteur.prenom from visiteur where visiteur.id in "
                        . "(select fichefrais.idvisiteur from fichefrais where fichefrais.mois='$mois' and fichefrais.idetat='cl') 
		order by nom desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesVisiteurs =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$selection = $laLigne['id'];
                        $nom=$laLigne['nom'];
                        $prenom=$laLigne['prenom'];
			$lesVisiteurs["$selection"]=array(
                        "id"=>"$selection",
                        "nom"=>"$nom",
                         "prenom"=>"$prenom"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesVisiteurs;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donn�
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'�tat 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select fichefrais.idetat as idetat, fichefrais.datemodif as datemodif, fichefrais.nbjustificatifs as nbjustificatifs, 
fichefrais.montantValide as montantvalide, etat.libelle as libetat from  fichefrais inner join etat on fichefrais.idetat = etat.id 
where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois ='$mois'";
		$res = PdoGsb::$monPdo->query($req);
                $laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'�tat et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif � aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req =("update fichefrais set idetat ='$etat', datemodif = now() 
where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'");
		PdoGsb::$monPdo->exec($req);
	}
      /**
       * permet d'avoir la liste de tous les visiteurs 
       * @return type
       */
        public function getAllVisiteurs(){
		$req ="select * from visiteur where comptable=0";
		
		$res = PdoGsb::$monPdo->query($req);
		$lesVisiteurs =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$selection = $laLigne['id'];
                        $nom=$laLigne['nom'];
                        $prenom=$laLigne['prenom'];
			$lesVisiteurs["$selection"]=array(
                            "id"=>"$selection",
                            "nom"=>"$nom",
                            "prenom"=>"$prenom"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesVisiteurs;
	}
        /**
         * permet de reporter les frais hors forfait 
         * @param type $idFrais
         */
      //public function getAllVisiteurs(){
		//$req ="select * from visiteur where comptable=0";
		
		//$res = PdoGsb::$monPdo->query($req);
		//$lesVisiteurs =array();
		//$laLigne = $res->fetch();
		//while($laLigne != null)	{
			//$selection = $laLigne['id'];
                        //$nom=$laLigne['nom'];
                        //$prenom=$laLigne['prenom'];
			//$lesVisiteurs["$selection"]=array(
                           // "id"=>"$selection",
                           // "nom"=>"$nom",
                         //  "prenom"=>"$prenom"
             //);
		//	$laLigne = $res->fetch(); 		
		//}
		//return $lesVisiteurs;
	//}
        
        /**
         * 
         * @param type $idFrais
         * reportent les frais hors forfaits
         */
        public function reporterFraisHorsForfait($idFrais){
		$req = "select mois,idvisiteur,montant from lignefraishorsforfait where id='$idFrais'";
		$res=PdoGsb::$monPdo->query($req);
                $laLigne=$res->fetch();
                $mois=$laLigne["mois"];
                $visiteur=$laLigne["idvisiteur"];
                $montant=$laLigne["montant"];
                
                $annee= (int)(substr($mois,0,4));
                $leMois=  substr($mois,5,2);
                
                if($leMois<12){
                    $leMois+=1;
                    if($leMois<10){
                        $leMois2="0"."$leMois";
                    }
                }
                else{
                    $annee+=1;
                    $leMois2="01";
                }
                    
                $mois2=$annee."/".$leMois2;
                //return $mois2;
                $req2="select count(*) as nbligne from fichefrais where mois='$mois2' and idvisiteur='$visiteur'";
                PdoGsb::$monPdo->query($req2);
                $laLigne=$res->fetch();
                $nb=(int)($laLigne["nbligne"]);
                if($nb==0){
                $req0=("insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$visiteur','$mois2',0,0,now(),'CR')");
                PdoGsb::$monPdo->exec($req0);
                }
                $req2=("update  lignefraishorsforfait set mois='$mois2' where id='$idFrais'");
                PdoGsb::$monPdo->exec($req2);
                
	}
        /**
         * 
         * @param type $idFrais
         * refusent les frais hors forfaits
         */
        public function refuFraisHorsForfait($idFrais){
            $req=("select refus from lignefraishorsforfait where id='$idFrais'");
            $res=PdoGsb::$monPdo->query($req);
            $laLigne=$res->fetch();
            $refu=(int)($laLigne["refus"]);
            var_dump($refu);
            if($refu==0){
                $req2=("update lignefraishorsforfait set refus=1  where id='$idFrais'");
                PdoGsb::$monPdo->exec($req2);
            }
            
            
        }
        
        /**
         * 
         * @param type $idHF
         * @return boolean
         * verifie si les frais hors  forfaits sont refus�s ou pas
         */
        public function estRefuse($idHF){
            $req=("select refus from lignefraishorsforfait where id='$idHF'");
            $res=PdoGsb::$monPdo->query($req);
            $laLigne=$res->fetch();
            $refu=(int)($laLigne["refus"]);
                if($refu==1){
                    return true;
                }
            return false;
        }
        /**
         * 
         * @param type $unType
         * @return type
         * 
         */
       public function valeurTF($unType){
           $req=("select montant from fraisforfait where id='$unType'");
           $res=PdoGsb::$monPdo->query($req);
           $montant=$res->fetch();
           return $montant;
       }
       /**
        * 
        * @return type
        * retournent les fiches valid�es
        */
        public function getLesFiches(){
          $req=("select * from fichefrais where idetat='va' ");
          $res=PdoGsb::$monPdo->query($req);
          $laLigne=$res->fetch();
          $lesFiches=null;
          while($laLigne != null)	{
			$idVisiteur = $laLigne['idvisiteur'];
                        $mois=$laLigne['mois'];
                        $nbJustificatifs=$laLigne['nbjustificatifs'];
                        $dateModif=$laLigne['datemodif'];
                        $idEtat=$laLigne['idetat'];
                        
			$lesFiches["$idVisiteur"]=array(
                            "idVisiteur"=>"$idVisiteur",
                            "mois"=>"$mois",
                            "nbJustificatifs"=>"$nbJustificatifs",
                            "dateModif"=>"$dateModif",
                            "idEtat"=>"$idEtat"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesFiches;

        }
        /**
         * 
         * @param type $idVisiteur
         * @param type $mois
         * @return type
         * retourne le total des couts hors forfaits
         */
        function totalHF($idVisiteur,$mois){
            $req=("select sum(montant)as totalF from lignefraishorsforfait where idvisiteur='$idVisiteur' and mois='$mois' and refus='0'");
            $res=PdoGsb::$monPdo->query($req);
            $laLigne=$res->fetch();
            $total=$laLigne['totalF'];
            return $total;
        }
        /**
         * 
         * @param type $idVisiteur
         * @param type $mois
         * @return type
         * total des couts forfaitaires
         */
        function totalF($idVisiteur,$mois){
            $req=("select sum(quantite*montant) as totalHF from lignefraisforfait join fraisforfait on idfraisforfait=fraisforfait.id
where idvisiteur='$idVisiteur' and mois='$mois'");
            $res=PdoGsb::$monPdo->query($req);
            $laLigne=$res->fetch();
            $total=$laLigne['totalHF'];
            return $total;
        }
        /**
         * met a l'�tat b toutes les fiches de frais
         */
        function rbAll(){
            $req=("update fichefrais set idetat='rb' where idetat='va'");
            PdoGsb::$monPdo->query($req);
        }
        /**
         * 
         * @param type $idVisiteur
         * @param type $mois
         * @return type
         * recup?rent les infos pour le pdf
         */
         public function getInfosPdf($idVisiteur, $mois){ //rajout
		$req = "select visiteur.id,visiteur.nom,visiteur.prenom from visiteur where visiteur.id='$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
        
    }
      



?>
