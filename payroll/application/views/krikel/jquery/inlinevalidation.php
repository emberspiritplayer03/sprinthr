 <script>
            $(document).ready(function(){
                // binds form submission and fields to the validation engine
                $("#formID").validationEngine({
						ajaxSubmit: true,
					scroll: false,
					ajaxSubmitFile: base_url + 'registration/insert',	
					ajaxSubmitMessage: "",		
					success : function() {},
					unbindEngine:true,
					failure : function() {}	
				});
            });
            
</script>

        <p>
            This demonstration shows the different validators available
            <br/>
        </p>
        <div id="test" class="test" style="width:150px;">This is a div element</div>
    <form id="formID" class="formular" method="post" action="">
       
        
        <fieldset>
        <legend>
                Group required
            </legend>
            <label>
                Checks if at least one of the input is not empty.
                <br/>  <br/>
                <span>Please enter a credit card</label>
                <input value="" class="validate[groupRequired[payments]] text-input" type="text" name="creditcard1" id="creditcard1" />
                <label><strong>OR</strong></label><br/>
                <label>Please enter a paypal acccount</label>
                <input value="" class="validate[groupRequired[payments],custom[email]] text-input" type="text" name="paypal" id="paypal" />
                    <label><strong>OR</strong></label><br/>
                    <label>Please enter a bank account</label>
                    <input value="" class="validate[groupRequired[payments],custom[integer]] text-input" type="text" name="bank" id="bank" />
                <label><strong>OR</strong></label><br/>
                <label>Please  choose from select</label>
                <select class="validate[groupRequired[payments]] text-input" type="text" name="bank2" id="bank2" >
                    <option value="">Choose a payment option</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Bank">Bank account</option>
                </select>
        </fieldset>			
        <fieldset>
            <legend></legend>
        </fieldset>
<fieldset>
  <legend></legend>
        </fieldset>
<input class="submit" type="submit" value="Validate & Send the form!"/><hr/>
    </form>
        <strong>INCLUDE:</strong><br />
Jquery::loadInlineValidation();<br />
<br />
<strong>Script:</strong><br />
<textarea name="textarea" id="textarea" cols="80" rows="13" wrap="soft">
  <script>
$(document).ready(function(){
	// binds form submission and fields to the validation engine
	$("#formID").validationEngine();
});
            
</script>
</textarea>
<br />
<strong>Views</strong><br />
<textarea name="textarea2" id="textarea2" cols="80" rows="13" wrap="soft">
    <form id="formID" class="formular" method="post" action="">
       
        
        <fieldset>
        <legend>
                Group required
            </legend>
            <label>
                Checks if at least one of the input is not empty.
                <br/>  <br/>
                <span>Please enter a credit card</label>
                <input value="" class="validate[groupRequired[payments]] text-input" type="text" name="creditcard1" id="creditcard1" />
                <label><strong>OR</strong></label><br/>
                <label>Please enter a paypal acccount</label>
                <input value="" class="validate[groupRequired[payments],custom[email]] text-input" type="text" name="paypal" id="paypal" />
                    <label><strong>OR</strong></label><br/>
                    <label>Please enter a bank account</label>
                    <input value="" class="validate[groupRequired[payments],custom[integer]] text-input" type="text" name="bank" id="bank" />
                <label><strong>OR</strong></label><br/>
                <label>Please  choose from select</label>
                <select class="validate[groupRequired[payments]] text-input" type="text" name="bank2" id="bank2" >
                    <option value="">Choose a payment option</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Bank">Bank account</option>
                </select>
        </fieldset>			
        <fieldset>
            <legend></legend>
        </fieldset>
<fieldset>
  <legend></legend>
        </fieldset>
<input class="submit" type="submit" value="Validate & Send the form!"/><hr/>
    </form>

</textarea>
