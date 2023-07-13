<?php
class G_Settings_Fixed_Contributions_Manager {



  

  public function update(G_Settings_Fixed_Contributions $g){

  	if ( $g ) {	
			$sql = "
				UPDATE  g_settings_fixed_contributions
				SET
					contribution = " . Model::safeSql($g->getContributionName()) .",
					is_enabled	 = " . Model::safeSql($g->getIsEnabled()) ."
				    WHERE id = ". Model::safeSql($g->getId());	


			Model::runSql($sql);
			$return = true;
		}else{
			$return = false;
		}
		return $return;		

  }


}

?>