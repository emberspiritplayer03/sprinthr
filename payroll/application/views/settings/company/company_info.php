<div class="actions_holder">
    <a class="edit_button" href="javascript:void(0);" onclick="javascript:load_edit_company_info();"><strong></strong>Edit Info</a>
</div>
<div id="form_main" class="inner_form">
<div id="form_default">
<h3 class="section_title">Company Information</h3>
    <table width="50%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="field_label">Company Name:</td>
            <td width="70%" valign="top"> <b><?php echo $cs->getTitle(); ?></b></td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="field_label">Address:</td>
            <td width="70%" valign="top">
                <?php echo($ci ? $ci->getAddress() : 'Undefined'); ?>        
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="field_label">Other Address:</td>
            <td width="70%" valign="top">
                <?php echo($ci ? $ci->getAddress1() : 'Undefined'); ?>        
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="field_label">City:</td>
            <td width="70" valign="top">
                <?php echo($ci ? $ci->getCity() : 'Undefined'); ?>        
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="field_label">State:</td>
            <td width="70%" valign="top">
                <?php echo($ci ? $ci->getState() : 'Undefined'); ?>        
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="field_label">Zip Code:</td>
            <td width="70%" valign="top">
                <?php echo($ci ? $ci->getZipCode() : 'Undefined'); ?>        
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="field_label">Remarks:</td>
            <td width="70%" valign="top">
                <?php echo($ci ? $ci->getRemarks() : 'None'); ?>        
            </td>
        </tr>    
    </table>
</div>
<div class="form_separator"></div>
<div id="form_default">    
    <h3 class="section_title">Contact Information</h3>
    <table width="50%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="field_label">Phone Number:</td>
            <td width="70%" valign="top"> <b><?php echo($ci ? $ci->getPhone() : 'Undefined'); ?></b></td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="field_label">Fax Number:</td>
            <td width="70%" valign="top">
                <?php echo($ci ? $ci->getFax() : 'Undefined'); ?>        
            </td>
        </tr>    
    </table>
</div>
</div>