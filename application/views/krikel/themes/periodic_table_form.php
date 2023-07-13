

<div class="cssguycomments">
<p>A form is shown when needed, and hidden when the form is cancelled.  The form container is set inline to display:none, which is ok, because if someone has javascript off, the "choose" button in the table has a real destination in its href attribute.</p>
</div>

<div id="prices">
<div id="formcontainer" style="display:none;">
	<form action="#">
		<h2>Get an account!</h2>
		<fieldset>
			<label for="email" class="email">
				Email<br />
				<input id="email" type="text" />
			</label>
			<label for="crazypassword" class="password">
				Password<br />
				<input id="crazypassword" type="password" />
			</label>
			<label for="retype" class="retype">
				Retype Password<br />
				<input id="retype" type="password" />
			</label>
		</fieldset>
		<fieldset class="buttons">
			<input
				type="image"
				alt="Cancel"
				src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/button_cancel.gif"
				onclick="hideTheForm();return false;" />
			<input
				type="image"
				alt="Submit"
				src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/button_submit.gif"
				onclick="return false;" />
		</fieldset>
	</form>
</div>

<table id="pricetable">
	<thead>
		<tr>
			<th class="side">&nbsp;</th>
			<th class="choiceA">$1000</th>
			<th class="choiceB">$100</th>
			<th class="choiceC on">$10</th>
			<th class="choiceD">$1</th>
			<th class="choiceE">Free</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class="side">&nbsp;</td>
			<td class="choiceA"><a href="signUpChoiceA.html" onclick="activateThisColumn('choiceA');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceB"><a href="signUpChoiceB.html" onclick="activateThisColumn('choiceB');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceC on"><a href="signUpChoiceC.html" onclick="activateThisColumn('choiceC');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceD"><a href="signUpChoiceD.html" onclick="activateThisColumn('choiceD');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceE"><a href="signUpChoiceE.html" onclick="activateThisColumn('choiceE');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<td class="side">Number of quarters</td>
			<td class="choiceA">4,000</td>
			<td class="choiceB">400</td>
			<td class="choiceC on">40</td>
			<td class="choiceD">4</td>
			<td class="choiceE">None</td>
		</tr>
		<tr>
			<td class="side">Number of zeros</td>
			<td class="choiceA">3 zeros</td>
			<td class="choiceB">2 zeros</td>
			<td class="choiceC on">1 zero</td>
			<td class="choiceD">No zeros</td>
			<td class="choiceE">None</td>
		</tr>
		<tr>
			<td class="side">Checks on this row</td>
			<td class="choiceA"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceB"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceC on"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceD"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceE">&nbsp;</td>
		</tr>
		<tr>
			<td class="side">Checks on another row</td>
			<td class="choiceA"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceB"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceC on">&nbsp;</td>
			<td class="choiceD">&nbsp;</td>
			<td class="choiceE">&nbsp;</td>
		</tr>
	</tbody>
</table>
</div>

<br><br>
<strong>INCLUDE:</strong>
Style::loadPeriodicTable();<br>
<textarea name="textarea" id="textarea" cols="110" rows="30">
<div id="prices">
<div id="formcontainer" style="display:none;">
	<form action="#">
		<h2>Get an account!</h2>
		<fieldset>
			<label for="email" class="email">
				Email<br />
				<input id="email" type="text" />
			</label>
			<label for="crazypassword" class="password">
				Password<br />
				<input id="crazypassword" type="password" />
			</label>
			<label for="retype" class="retype">
				Retype Password<br />
				<input id="retype" type="password" />
			</label>
		</fieldset>
		<fieldset class="buttons">
			<input
				type="image"
				alt="Cancel"
				src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/button_cancel.gif"
				onclick="hideTheForm();return false;" />
			<input
				type="image"
				alt="Submit"
				src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/button_submit.gif"
				onclick="return false;" />
		</fieldset>
	</form>
</div>

<table id="pricetable">
	<thead>
		<tr>
			<th class="side">&nbsp;</th>
			<th class="choiceA">$1000</th>
			<th class="choiceB">$100</th>
			<th class="choiceC on">$10</th>
			<th class="choiceD">$1</th>
			<th class="choiceE">Free</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class="side">&nbsp;</td>
			<td class="choiceA"><a href="signUpChoiceA.html" onclick="activateThisColumn('choiceA');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceB"><a href="signUpChoiceB.html" onclick="activateThisColumn('choiceB');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceC on"><a href="signUpChoiceC.html" onclick="activateThisColumn('choiceC');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceD"><a href="signUpChoiceD.html" onclick="activateThisColumn('choiceD');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
			<td class="choiceE"><a href="signUpChoiceE.html" onclick="activateThisColumn('choiceE');return false;"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/choose.gif" alt="Choose" /></a></td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<td class="side">Number of quarters</td>
			<td class="choiceA">4,000</td>
			<td class="choiceB">400</td>
			<td class="choiceC on">40</td>
			<td class="choiceD">4</td>
			<td class="choiceE">None</td>
		</tr>
		<tr>
			<td class="side">Number of zeros</td>
			<td class="choiceA">3 zeros</td>
			<td class="choiceB">2 zeros</td>
			<td class="choiceC on">1 zero</td>
			<td class="choiceD">No zeros</td>
			<td class="choiceE">None</td>
		</tr>
		<tr>
			<td class="side">Checks on this row</td>
			<td class="choiceA"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceB"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceC on"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceD"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceE">&nbsp;</td>
		</tr>
		<tr>
			<td class="side">Checks on another row</td>
			<td class="choiceA"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceB"><img src="<?php echo BASE_FOLDER.'themes/'.THEME; ?>/assets/periodictable/i/check.png" alt="yes" /></td>
			<td class="choiceC on">&nbsp;</td>
			<td class="choiceD">&nbsp;</td>
			<td class="choiceE">&nbsp;</td>
		</tr>
	</tbody>
</table>
</div>
</textarea>
