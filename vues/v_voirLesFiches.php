<h3>toute les fiches de frais</h3>
<?php if(isset($lesFiches)){?>
<table class="listeLegere">
        <tr>
                <th class="idVisiteur">idVisiteur</th>
                <th class="mois">Mois</th>
                <th class='nbJustificatif'>nb jutificatifs</th>
                <th class="datemodif">date derniere modif</th>
                <th class="idEtat">etat</th>
        </tr>
        <?php
        $i=0;
        foreach($lesFiches as $uneFiche){
            $idVisiteur = $uneFiche['idVisiteur'];
            
            $mois=$uneFiche['mois'];
            
            $nbJustificatifs=$uneFiche['nbJustificatifs'];
            $dateModif=$uneFiche['dateModif'];
            $idEtat=$uneFiche['idEtat'];
        
        ?>
            <tr>
                <td><?php echo $idVisiteur ?></td>
                <td><?php echo $mois ?></td>
                <td><?php echo $nbJustificatifs ?></td>
                <td><?php echo $dateModif ?></td>
                <td><?php echo $idEtat ?></td>
                <td><a href="index.php?uc=voirLesFiches&action=details&id=<?php echo $idVisiteur?>&mois=<?php echo $mois?> ">details</a></td>
                <td><a href="index.php?uc=voirLesFiches&action=pdf&id=<?php echo $idVisiteur?>&mois=<?php echo $mois?>"> <img src="images/iconePdf.png" height="50" width="50"></a></td>
                <td> <a href="index.php?uc=voirLesFiches&action=rembourse&id=<?php echo $idVisiteur?>&mois=<?php echo $mois?> ">remboursement</a></td>
            </tr>
        <?php
        
        } ?>
</table>
<a href="index.php?uc=voirLesFiches&action=rbAll">rembourser tout les visiteurs</a>
<?php }
      else{
          echo "aucune fiche validee disponible";
      }
 ?>
