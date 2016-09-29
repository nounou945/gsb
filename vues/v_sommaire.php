    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">
			<li >
				  Visiteur :<br>
				<?php
                                if($_SESSION['comptable']==1){
                                    $user="comptable";
                                }
                                else{
                                    $user="visiteur";
                                }
                                echo $_SESSION['prenom']."  ".$_SESSION['nom']."  " .$user ?>
			</li>
            <?php
            if ($_SESSION['comptable']==0){
           
            ?>
           <li class="smenu">
              <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
      <?php } 
      else { ?>
           
           <li class="smenu">
              <a href="index.php?uc=validerFrais&action=saisirFrais" title="Valider fiche de frais ">Valider fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=suivreFrais&action=validerMois" title="Suivre paiement des fiches de frais">Suivre paiement des fiches de frais</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Deconnexion</a>
           </li>    
    <?php  }
      
      ?>
      </ul>
        
    </div>
            
