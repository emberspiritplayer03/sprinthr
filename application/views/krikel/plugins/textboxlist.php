<p><strong>Demo</strong> <br />


Textboxlist
<input type="text" name="user_id" id="user_id" />
<br />
<br />

<strong>Include</strong><br /> 
Jquery::loadTextBoxList();<br />
<br />
<br />
<strong>Usage:</strong><br />
<strong>Views</strong></p>

  <script>
$('#user_id').textboxlist({unique: true, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'source/_get_names_autocomplete'}
}}});


  </script>
<p>

  
  <textarea name="textarea" id="textarea" cols="80" rows="13">
		<input type="text" name="user_id" id="user_id" />
        
<script>
$('#user_id').textboxlist({unique: true, plugins: {autocomplete: {
	minLength: 3,
	onlyFromValues: true,
	queryRemote: true,
	remote: {url: base_url + 'source/_get_names_autocomplete'}
}}});
</script>
  </textarea>
  <br />
<strong>Controller</strong></p>
<p>
  <textarea name="textarea2" id="textarea2" cols="80" rows="20" wrap="off">
function _get_names_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {
			$sql = "
				SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name
				FROM g_user u
				WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%')
				";
			
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$response[] = array($record['id'], $record['name'], null);
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
