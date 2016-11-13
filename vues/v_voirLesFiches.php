<h3>toute les fiches de frais</h3>
<table class="listeLegere">
        <tr>
                <th class="idVisiteur">idVisiteur</th>
                <th class="mois">Mois</th>
                <th class='nbJustificatif'>nb jutificatifs</th>
                <th class="datemodif">date derniere modif</th>
                <th class="idEtat">etat</th>
        </tr>
        <?php
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
                <td><a href="<?php details($idVisiteur,$mois)?>">details</a></td>
                <td><a href="<?php creerPdf($lesFraisFortfaits,$lesFraisHorsForfaits,$visiteur,$mois)?>"><img src="images/iconePdf.png" height="50" width="50"></a></td>
                <td> <a href='<?php remboursement($idVisiteur,$mois) ?> '>remboursement</a></td>
            </tr>
        <?php
        } ?>
</table>

