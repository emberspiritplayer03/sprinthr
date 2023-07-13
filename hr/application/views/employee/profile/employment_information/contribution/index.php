<script>
$(function(){
    $('.btn-to-deduct').click(function(){
        var ec_type = $(this).attr('id');
        var ec_id   = $('#ec_id').val();
        if($(this).hasClass('btn-primary')) {
            $(this).html('No');
            $(this).removeClass('btn-primary');
            $(this).addClass('btn-danger');
        }else{
            $(this).html('Yes');
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-primary');
        }
        $(this).attr('disabled','disabled');
        var btn = $(this);
        $.post(base_url+'employee/update_employee_contribution_to_deduct',{ec_id:ec_id,ec_type:ec_type},
        function(o){
          btn.removeAttr('disabled');
        },"json");
    });

    $('.btn-is-tax-exempted').click(function(){
        var employee_id   = $('#employee_id').val();
        if($(this).hasClass('btn-primary')) {
            $(this).html('No');
            $(this).removeClass('btn-primary');
            $(this).addClass('btn-danger');
        }else{
            $(this).html('Yes');
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-primary');
        }
        $(this).attr('disabled','disabled');
        var btn = $(this);
        $.post(base_url+'employee/update_is_tax_exempted',{employee_id:employee_id},
        function(o){
          btn.removeAttr('disabled');
        },"json");
    })

    $('#btn-refresh-contri').click(function(){

      var employee_id = $("#employee_id").val();
      $.post(base_url+'employee/_refresh_employee_contribution',{employee_id:employee_id},
      function(o){
        if(o.is_success) {
          loadContribution(employee_id);
        }
      },"json");    
      
    });

    $('#btn-set-fixed-contri').click(function(){
      var employee_id = $("#employee_id").val();
      $.post(base_url+'employee/_refresh_employee_contribution',{employee_id:employee_id},
      function(o){
        if(o.is_success) {
          loadSetEmployeeFixedContri(employee_id);
        }
      },"json");    
      
    });
});

function loadContribution(employee_id) {
  $("#contribution_wrapper").html('Loading...');
  $.get(base_url+'employee/_load_contribution',{employee_id:employee_id},
    function(o){
      $("#contribution_wrapper").html(o);
    });
}

function loadSetEmployeeFixedContri(employee_id) {
  $("#contribution_wrapper").html('Loading...');
  $.get(base_url+'employee/_load_employee_fixed_contributions',{employee_id:employee_id},
    function(o){
      $("#contribution_wrapper").html(o);
    });
}
</script>
<h2 class="field_title"><?php echo $title_contribution; ?>
  <div class="pull-right"><?php echo $btn_refresh_contribution;?></div>
</h2>

<?php include 'form/contribution_edit.php'; ?>

<input type="hidden" id="ec_id" value="<?php echo Utilities::encrypt($c->id);?>">
<input type="hidden" id="employee_id" value="<?php echo $employee_id;?>">

<div id="contribution_table_wrapper">

    <table class="formtable" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="30%" scope="col">Contribution/Benefits</th>
          <th width="30%" scope="col">EE</th>
          <th width="25%" scope="col">ER</th>
          <th width="15%" scope="col" style="text-align:center">To Deduct</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="30%" align="center"><strong>SSS</strong></td>
          <td width="30%"><?php echo $c->sss_ee; ?></td>
          <td width="25%"><?php echo $c->sss_er; ?></td>
          <td width="15%" style="text-align:center">
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a id="sss" class="btn-to-deduct btn <?php echo ($to_deduct['sss'] == G_Employee_Contribution::YES ? 'btn-primary' : 'btn-danger' ); ?>"><?php echo $to_deduct['sss']; ?></a>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td width="30%" align="center"><strong>PHIC</strong></td>
          <td width="30%"><?php echo $c->philhealth_ee; ?></td>
          <td width="25%"><?php echo $c->philhealth_er; ?></td>
          <td width="15%" style="text-align:center">
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a id="philhealth" class="btn-to-deduct btn <?php echo ($to_deduct['philhealth'] == G_Employee_Contribution::YES ? 'btn-primary' : 'btn-danger' ); ?>"><?php echo $to_deduct['philhealth']; ?></a>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td width="30%" align="center"><strong>HDMF</strong></td>
          <td width="30%"><?php echo $c->pagibig_ee; ?></td>
          <td width="25%"><?php echo $c->pagibig_er; ?></td>
          <td width="15%" style="text-align:center">
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
              <a id="pagibig" class="btn-to-deduct btn <?php echo ($to_deduct['pagibig'] == G_Employee_Contribution::YES ? 'btn-primary' : 'btn-danger' ); ?>"><?php echo $to_deduct['pagibig']; ?></a>
            <?php } ?>
          </td>
        </tr>
        <tr class="form_action_section">
          <td width="30%">&nbsp;</td>
          <td class="action_section" colspan="2">
          <?php /*echo $btn_edit_details;*/ ?>
          </td>
          <td >&nbsp;</td>
        </tr>
      </tbody>
    </table>
    <br />
    <div class="pull-right"><?php echo $btn_fixed_contribution;?></div>
    <div class="clear"></div>
    <br />
    <table class="formtable" id="hor-minimalist-b"  border="0">      
      <thead>
        <tr>
          <th width="30%" scope="col">Fixed Contribution/Benefits</th>
          <th width="30%" scope="col">EE</th>
          <th width="25%" scope="col">ER</th>
          <th width="15%" scope="col" style="text-align:center">Is Activated</th>
        </tr>
      </thead>      
      <tbody>
        <?php foreach( $fixed_contri_data as $fc ){ ?>
          <tr>
            <td width="30%" align="center"><strong><?php echo $fc['type']; ?></strong></td>
            <td width="30%"><?php echo number_format($fc['ee_amount'],2) ?></td>
            <td width="25%"><?php echo number_format($fc['er_amount'],2) ?></td>
            <td width="15%" style="text-align:center">
              <?php if($fc['is_activated'] == 1) { ?>
                <span>Yes</span>
              <?php }else{ ?>
                <span>No</span>
              <?php } ?>                
            </td>
          </tr>
        <?php } ?>        
        <tr class="form_action_section">
          <td width="30%">&nbsp;</td>
          <td class="action_section" colspan="2">
          <?php /*echo $btn_edit_details;*/ ?>
          </td>
          <td >&nbsp;</td>
        </tr>
      </tbody>
    </table>
</div>

<!-- TAX TABLE -->
<br/>
<h2 class="field_title">Tax</h2>

<?php include 'form/contribution_edit.php'; ?>

<input type="hidden" id="ec_id" value="<?php echo Utilities::encrypt($c->id);?>">
<div id="contribution_table_wrapper">
<table class="formtable" id="hor-minimalist-b"  border="0">
  <thead>
    <tr>
      <th width="70%" scope="col">Tax</th>
      <th width="15%" scope="col" style="text-align:center">Exemption</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td width="30%" align="center"><strong>Witholding Tax</strong></td>
      <td width="15%" style="text-align:center">
        <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
          <a class="btn-is-tax-exempted btn <?php echo ($is_exempted == G_Employee::YES ? 'btn-primary' : 'btn-danger' ); ?>"><?php echo $is_exempted; ?></a>
        <?php } ?>
      </td>
    </tr>
    
  </tbody>
</table>
<b>Note : Employee will be auto exempted if will fall under tax table rule of exemption. </b>
</div>