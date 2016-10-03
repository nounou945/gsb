 <div id="contenu">
      <h2>fiches de frais à valider</h2>
      <h3>Mois Ã  sÃ©lectionner : </h3>
      <form action="index.php?uc=validerFrais&action=consulterFicheDetail" method="post">
      <div class="corpsForm">
         
      <p>
	 
        <label for="lstMois" accesskey="n">Mois : </label>
        <select id="lstMois" name="lstMois">
            <?php
			foreach ($lesMois as $unMois)
			{
                                $mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
				if($mois == $moisASelectionner){
				?>
				<option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
			
			}
           
		   ?>    
            
        </select>
        <label for="lstVisiteurs" accesskey="n">Visiteurs : </label>
        <select id="lstVisiteur" name="lstVisiteur">
       <h3>visiteurs  à sélectionner : </h3>
      
            <?php
			foreach ($lesVisiteurs as $unVisiteur)
			{
                                 $id = $unVisiteur['id'];
				$nom =  $unVisiteur['nom'];
				$prenom =  $unVisiteur['prenom'];
				if($id == $idASelectionner){
				?>
				<option selected value="<?php echo $id?>"><?php echo  $nom." ".$prenom ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $id ?>"><?php echo  $nom." ".$prenom ?> </option>
				<?php 
				}
			
			}
           
		   ?>    
        </select>
      </p>
      </div>
      <div class="piedForm">
          <?php if(empty($_POST['lstMois'])){?>
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
          <?php } ?>
      </div>
        
      </form><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
