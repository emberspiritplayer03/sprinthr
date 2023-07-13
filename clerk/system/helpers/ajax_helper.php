<?php
Loader::sysScript('main.js');

function ajax_table($config, $styles)
{
	if (!(int)($config['show_page']) || $config['show_page'] == 0) { $config['show_page'] = 1; }
	if (!(int)($config['rows']) || $config['rows'] == 0) { $config['rows'] = 20; }

	$table_id = "#" . $config['table_id'];
	$pager_id = "#" . $config['pager_id'];
	foreach ($styles as $index => $s)
	{
		if ($index == 'column')
		{
			foreach ($s as $column_number => $col_style)
			{
				$style .=  "$table_id .column-" . $column_number . " { $col_style }";
			}
		}
		else if ($index == 'header')
		{
			$style .= "$table_id thead { $s }";
		}
		else if ($index == 'table')
		{
			$style .= "$table_id table { $s }";
		}
		else if ($index == 'body')
		{
			//$style .= "$table_id tbody { $s }";
			$style .= "$table_id td { $s }";
		}
		else if ($index == 'row_odd')
		{
			$style .= "$table_id .row-odd { $s }";
		}
		else if ($index == 'row_even')
		{
			$style .= "$table_id .row-even { $s }";
		}	
	}

	$fields = json_encode($config['fields']);
	$script = "
wg.onReady(function(){
	wg.table.show(
	{ 
		table_id:'". $config['table_id'] ."',
		pager_id:'". $config['pager_id'] ."',
		show_page: ". $config['show_page'] .",
		row_per_page: ". $config['row_per_page'] .",
		url: base_url + '". $config['url'] ."',
		fields: $fields
	})
})";

	write_style($style);
	write_script($script);
}

function ajax_pager($config)
{
$js =
"wg.onReady(function()
{
	wg.pager.show({
		id: '" . $config['element_id'] . "',
		action: base_url + '" . $config['action'] . "',
		total_records: '" . $config['total_records'] . "',
		//row_per_page: '" . $config['row_per_page'] . "',
		pager_id: 'pager-page-selector',
		row_list_id: 'pager-per-page-selector'
	});
});";
write_script($js);
}

function ajax_calendar($element_id)
{
write_script("
wg.onReady(function() {
wg.ajax.update('" . $element_id . "', base_url + 'calendar/index/" . $element_id . "');
});
");
write_script("
var calendar_the_date = '';
function calendar_show_event(m, d, y)
{
	if (calendar_the_date != '')
	{
		document.getElementById(calendar_the_date).style.display = 'none';
	}
	
	document.getElementById(m + '-' + d + '-' + y).style.display = 'block';
	calendar_the_date = m + '-' + d + '-' + y;
}
");
}
?>