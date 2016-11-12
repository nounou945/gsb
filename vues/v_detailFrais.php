<?php $montantTotal=0; ?>
<h3>Fiche de frais du mois <?php echo $numMois."-".$numAnnee?> : 
    </h3>
    <div class="encadre">
    <p>
        Etat : <?php echo $libEtat?> depuis le <?php echo $dateModif?> <br> Montant totale : <?php echo $montantTotal?>
              
                     
    </p>
  	<table class="listeLegere">
  	   <caption>Eléments forfaitisés </caption>
        <tr>
         <?php
         foreach ( $lesFraisForfait as $unFraisForfait ) 
		 {
			$libelle = $unFraisForfait['libelle'];
		?>	
			<th> <?php echo $libelle?></th>
		 <?php
        }
		?>
		</tr>
        <tr>
        <?php
          foreach (  $lesFraisForfait as $unFraisForfait  ) 
		  {
				$quantite = $unFraisForfait['quantite'];
                                $idF=$unFraisForfait['fraisforfait'];
                                $ratio=$pdo->valeurTF($idF);
                                var_dump($ratio);
                                $montantTotal+=$quantite*$ratio;
                                
		?>
                <td class="qteForfait"><?php echo $quantite?> </td>
		 <?php
          }
		?>
		</tr>
    </table>
  	<table class="listeLegere">
  	   <caption>Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus -
       </caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>                
             </tr>
        <?php      
          foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
		  {     $idHF=$unFraisHorsForfait['id'];
			$date = $unFraisHorsForfait['date'];
			$libelle = $unFraisHorsForfait['libelle'];
                        if($pdo->estRefuse($idHF)){
                            $libelle="REFUSE:"+$libelle;
                        }
			$montant = $unFraisHorsForfait['montant'];
                        $montantTotal+=$montant;
		
                        ?>
             <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td><a href="controleurs/c_validerFrais.php&action=consulterFicheDetail&reporter=<?php echo $idHF ;?>">reporter</a></td>
                <td><a href="controleurs/c_validerFrais.php&action=consulterFicheDetail&refu=<?php echo $idHF ;?>">reporter</a></td>
             </tr>
        <?php 
          }
		?>
    </table>
  </div>
  </div>
 













