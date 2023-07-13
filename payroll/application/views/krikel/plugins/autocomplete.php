<p><strong>Demo</strong> <br />

  <script>
$(function() {
$("input#autocomplete").autocomplete({
    source:  base_url + 'source/_autocomplete',
	select: function( event, ui ) {
				$( "#autocomplete" ).val( ui.item.label );
				$( "#project-id" ).val( ui.item.id );
				return false;
			}
	});
});

function getValue() {
	alert($("#autocomplete").val());
}
  </script>

Autocomplete
<input id="autocomplete" class="curve" />
<input id="project-id" class="curve" type="text" />
<br />
<br />

<strong>Include</strong><br /> 
generic.js
 (already included)<br />
<br />
<br />
<strong>Usage:</strong><br />
<strong>Views</strong></p>
<p>
  
  <textarea name="textarea" id="textarea" cols="80" rows="13">
<script>
$(function() {
$("input#autocomplete").autocomplete({
source:  base_url + 'source/_autocomplete',
select: function( event, ui ) {
			$( "#autocomplete" ).val( ui.item.label );
			$( "#project-id" ).val( ui.item.id );
			return false;
		}
});
});
</script>
<input id="autocomplete" class="curve" />
<input id="project-id" class="curve" type="text" />
  </textarea>
  <br />
<strong>Controller</strong></p>
<p>
  <textarea name="textarea2" id="textarea2" cols="80" rows="20" wrap="off">
function _autocomplete() {
    $q = Model::safeSql(strtolower($_GET["term"]), false);
    if ($q != '') {
    $sql = "
            SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name
            FROM g_user u
            WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%')
            ";
        $records = Model::runSql($sql, true);
        foreach ($records as $record) {
            $response[] = array('id'=>$record['id'],'label'=>$record['name']);
        }
    }
    if(count($response)==0)
    {
        $response = '';
    }
    
    header('Content-type: application/json');
    echo json_encode($response);		
}
  </textarea>
</p>
