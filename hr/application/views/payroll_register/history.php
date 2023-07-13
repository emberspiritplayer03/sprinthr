<!--<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" />
    <button id="create_schedule_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search Employee</button>
    </div>
</form>
</div>--><!-- #employee_search_container -->

Go to
<select>
    <?php foreach ($months as $m):?>
        <option><?php echo $m;?></option>
    <?php endforeach;?>
</select>
<select>
    <option>2014</option>
    <option>2013</option>
</select> <button class="blue_button" type="submit">Go</button>
<br><br>

<div style="border:1px solid #CCCCCC; margin-bottom:10px; padding: 10px">
    <div style="font-size: 20px; font-weight:bold">January - A</div><br>
    Total Net Pay: P200,000<br>
    Late Expenses: P100,000<br>
    Overtime Expenses: P40,000<br>
</div>

<div style="border:1px solid #CCCCCC; margin-bottom:10px; padding: 10px">
    <div style="font-size: 20px; font-weight:bold">January - B</div><br>
    Total Net Pay: P200,000<br>
    Late Expenses: P100,000<br>
    Overtime Expenses: P40,000<br>
</div>