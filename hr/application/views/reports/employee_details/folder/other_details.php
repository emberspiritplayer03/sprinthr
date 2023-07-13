<div id="form_default">
  <h3 class="section_title">Other Details</h3>
    <table>          
      <tr>
        <td style="color:#777777; width:170px; mso-number-format:'\@';">SSS Number:</td>
        <td><?php echo $employee->getSssNumber(); ?></td>
      </tr>
      <tr>
        <td style="color:#777777; width:170px; mso-number-format:'\@';">Tin Number:</td>
        <td><?php echo $employee->getTinNumber(); ?></td>
      </tr>
       <tr>
         <td style="color:#777777; width:170px; mso-number-format:'\@';">Philhealth Number</td>
         <td>&nbsp;<?php echo $employee->getPhilhealthNumber(); ?></td>
       </tr>
       <tr>
        <td style="color:#777777; width:170px; mso-number-format:'\@';">Pagibig Number</td>
        <td><?php echo $employee->getPagibigNumber(); ?></td>
      </tr>         
    </table>
</div>