<?php  if($dc_settings){ ?>

 <table width="100%" border="1">

    <?php foreach($dc_settings as $dc){

            if($dc->getContributionName() == 'pagibig'){ 

                    if($dc->getIsEnabled() == 1){
                        $action = 'disabled';
                    }
                    else{
                        $action = 'enabled';
                    }

             ?>

             <tr>
                <td>Paigibig Fixed Contributions</td>
                <td><button class="btn <?php if($action =='enabled'){ echo 'btn-primary';}else{ echo 'btn-danger';} ?>" onclick="toggleFixedContri('<?php echo $action; ?>', '<?php echo $dc->getId(); ?>')">
                    <?php echo $action; ?>
                </button></td>
             </tr>


    <?php    
            } //end of if pagibig
        }
    ?>
      
    </table>


<?php 
}
?>