

<?php 
//UPDATED February 3,2011 ||MARVIN

 
 	//IF YOUR TABLE HAS A FOREIGN KEY(field: position_id,foreign table: position) IF YOU WANT TO CHANGE THE FOREIGN TABLE just follow below
  // the default foreign table below line is base on the field (position_group_id would be position_group), if you want to configure the foreign table just type that	
 // $foreign_table[] =  array('field'=>'position_group_id','foreign_table'=>'s_position_group'); 
 
  //FOREIGN FIELD NAME DEFAULT: name.. TO CHANGE ADD THIS LINE
 //	$foreign_field[] = array('field'=>'position_group_id', 'foreign_field'=>'group_name');
 //THE EXAMPLES ABOVE ARE MORE ON COMPLEXITY OF SCAFFOLDING// 
 
  //TITLE OF THE TABLE
 //$field_title[] = array('field'=> 's_department_id', 'new_field'=>'Department');
 
 //Filter Table
 //$where = ' WHERE application_status_id<3';
 
 //use this for all records
 //$scaffold = new Scaffold("s_position",15, array('s_department_id ','position'), FALSE, '850',$foreign_table,$foreign_field,$field_title,$formIdName);
 //$where = ' WHERE application_status_id<3';
 //$this->var['scaffold'] =  $scaffold->load_scaffolding($msg,$where);
 
//filter table
 
 /**this is add-ons**/
 //add this if you want validate mandatory field
 //$scaffold->mandatoryFields(array('field')); // default all
 
 //add this if you want to validate your email format on the field
 //$scaffold->emailValidation(array('email_address'));
 
 //add this if you want to have a unique record
 //this is for creating new record
 //$scaffold->uniqueFields(array('user_name'));
 
 //add this for new link for create new record
 // this is for the create new record form limitation
// $scaffold->createNewRecordLink('form.php');

	//add this for new link for edit record
	 // this is for the edit record form limitation
	//$scaffold->setEditLink('hr/edit/');
	
	//disable delete
	// add this and the delete button will hide
 	//$scaffold->disableDelete();
	
	//disable add
	// add this and the delete button will hide
 	//$scaffold->disableAdd();
	
	//Action button setup
	// add this if you have any button/ link to apply
		//$button[] = array('label'=>'Information','action'=>'hr/url');
		//$button[] = array('label'=>'Archive','action'=>'hr/url');
	//$scaffold->setActionButton($button)
	
  	//add search
	//$this->var['search']  = $scaffold->build_search_bar(array('name','author','book_status'));
//OPTION
	//SHOW SPECIFIC RECORD
		//$scaffold = new Scaffold("s_company",15, array('name'), FALSE, '450'); 
		//$this->var['scaffold'] = $scaffold->show_one_record(1);
 

class Scaffold {

	var $table = '';		
	var $success=1;						// internal var for table
	
	function Scaffold($table, $max_records = 100, $fields = array(),  $htmlsafe = true, $width = NULL,$foreign_table= array(),$foreign_field = array(),$field_title = array(),$formIdName='myForm'){
		$this->table = $table;						// sets the database table
		$this->max_records = intval($max_records);	// sets the limit on how many records are displayed per page
		$this->fields = $fields;					// sets the fields display
		$this->htmlsafe = $htmlsafe;				// make display html safe		
		$this->width = intval($width);				// width of listing table
		
		$this->foreign_table = $foreign_table;		//$foreign_table[] = array('field'=>'position_id','foreign_table'=>'position_group');
		$this->foreign_field =  $foreign_field;
		$this->field_title = $field_title;			// title of the table
		$this->formIdName = $formIdName;
		//$this->where = $where;
		//$this->show_table();
		
	}
	
	function createNewRecordLink($create_new_record_link='')
	{
		$this->create_new_record_link = $create_new_record_link;	//default the create new in scaffolding
	}
	
	function setEditLink($edit_record_link='') {
		$this->edit_record_link = $edit_record_link;
	}
	
	function emailValidation($fields = array())
	{
		$this->emailFormatFields = $fields;
	}
	
	function setActionButton($button=array()) {
		$this->set_action_button = $button;
	}
	
	function disableDelete() {
		$this->disable_delete=1;
	}
	
	function disableAdd() {
		$this->disable_add=1;
	}
	function disableEdit() {
		$this->disable_edit=1;	
	}
	
	function mandatoryFields($fields = array())
	{
		
	
		if(count($fields)==0)
		{
			$query = 'SELECT * FROM '.$this->table;
  		 	$select = mysql_query($query) or die(mysql_error());
			$i = 0;			
			while($i < mysql_num_fields($select)){
				$column = mysql_fetch_field($select, $i);
				$this->mandatoryFields[] = $column->name;
				$i++;
			}
		}else {
	
			$this->mandatoryFields = $fields;
		}
	}
	
	function uniqueFields($fields = array())
	{
		
		if(count($fields)>0)
		{
			$this->uniqueFields = $fields;
		}else{
			echo 'Please Add Unique Fields on function uniqueFields()';
			exit;
			
			
		}
	}
	
	function validateFields()
	{
		
	
		$err=0;
		if($this->mandatoryFields)
		{
			foreach($this->mandatoryFields as $val){
				if(isset($_POST[$val]))	
				{
					if($_POST[$val]=='')
					{
						$this->err[] = "Fill up the " .  $this->build_friendly_names($val);
						$err = 1;					
					}
				}
			}
		}
		
		if($this->emailFormatFields)
		{
			foreach($this->emailFormatFields as $val){
				if(isset($_POST[$val]))
				{	 
					$x = preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/",$_POST[$val]);
					if($x==0)
					{
						$this->err[] = "Invalid Format " .  $this->build_friendly_names($val);
						$err = 1;
					}
				}
			}
		}
		
		if($this->uniqueFields)
		{
			foreach($this->uniqueFields as $val){
				if(isset($_POST[$val]))
				{	 
					
					$result = mysql_query("SELECT * FROM ". $this->table ." WHERE ". $val ."='". $_POST[$val]."'");
					$count_rows = 	mysql_num_rows($result);
			
					if($count_rows>0)
					{
						$this->err[] = "Already Taken: " .  $this->build_friendly_names($val);
						$err = 1;
					}
				}
			}
			
		}
		return $err;
	}

	function load_scaffolding($msg,$where)
	{
		// the following sets the page variable
		(!empty($_GET['page']) && is_numeric($_GET['page'])) ? $this->page = intval($_GET['page']) : $this->page = 1;
		

		$action = (!empty($_POST['xxbmnnzhfwggsf'])) ? $_POST['xxbmnnzhfwggsf'] : 'list' ;
		switch($action){
			default:
				
				return $this->list_table('',$where);
			break;

			case 'list':
				
				return $this->list_table('',$where);
			break;
			
			case 'listall':
				//this is for loading all records
				return $this->list_table_all('', $where);
			break;
			case 'new':
				 return $this->new_row();
			break;

			case 'create':
			
				return $this->create();
			break;
			
			case 'edit2':
				return $this->edit_one();
			break;
			
			case 'edit':
				return $this->edit_row();
			break;

			case 'update':
				return $this->update();
			break;

			case 'delete':
				return $this->delete_row();
			break;
			
			case 'search':
				 return $this->search();
			break;
		}
	}

	/**
	* This method builds the record listing
	*
	* string $msg 		// pass an optional message to be displayed
	* strgin $where		// pass an optional WHERE parameter SQL call 
	*
	*/
	function list_table($msg = NULL, $where = null){
		
		$start = (($this->page-1)*$this->max_records);				// start parameter for pages
		$end = $this->max_records;									// end parameter for pages
		$page = '';													// var to buiild display
		$totalQuery = mysql_query ('SELECT COUNT(*) FROM '.$this->table.$where) or die(mysql_error());
		
		$totalA = mysql_fetch_array($totalQuery);
		$total = $totalA[0];
		
		if (!empty($this->fields)) {
		// just display the selected fields
			$query = 'SELECT id';
			foreach($this->fields as $val){
				$query .= ', '.$val;
			}
			$query .= ' FROM '.$this->table;
		}else{
			$query = 'SELECT * FROM '.$this->table;
		}
		if(!empty($where)){ $query .= $where; }
		$query = $query.' LIMIT '.$start.', '.$end;
		$select = mysql_query($query) or die(mysql_error());
		$i = 0;
		
		(!empty($this->width)) ? $width = ' width="'.$this->width.'"' : $width = NULL;
		
			$page .= '<table id="" class="scaffold-container" border="0" cellpadding="5" cellspacing="1"'.$width.'" bgcolor="#CCCCCC">';
		$page .= '<caption id="">&nbsp;';
		
		
		if(!empty($msg)) { $page .= $msg; }
		//$page .=$this->build_search_bar();
		if($this->disable_add!=1) {
			if($this->create_new_record_link!='')
			{
				$page .="<a class=\"button\" href=". url($this->create_new_record_link) ." ><span>Create New Record</span></a><br><br>";
			}else{
				$page .='&nbsp;<form  name="newrecord" id="newrecord" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="new"><a class="button" href="javascript:document.newrecord.submit()"><span>Create New Record</span></a></form><br><br>';		
			}
		}		
		if($this->paginate($total, $this->page)!='') {
			$page .= '<div align=left> '. $this->paginate($total, $this->page). '</div>';
		}
		//$page .= '<br><div align=right>Total Records: <a href='.$_SERVER['PHP_SELF'].'>'.  $total . '</a></div>';
		$page .='&nbsp;<div align=right><form class="scaffold-container" name="listall" id="listall" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="listall">Total Record(s):<a href="javascript:document.listall.submit()"> ' . $total. '</a></form></div>';
		$page .= '</caption>';
	
		//$page .= '<thead id="thead" cellpadding="0" cellspacing="1" bgcolor="#ccc">';
		$page .= '<tr id="" align=center bgcolor="#5588BB"  >';
		while($i < mysql_num_fields($select)){
			$column = mysql_fetch_field($select, $i);
			if($column->name != 'id' && $column->name != 'updated_at' && $column->name != 'created_at'){
				$page .= '<th nowrap style="color:#fff">'.$this->build_friendly_names($column->name).'</th>';	
				$footerTD .= '<td>&nbsp;</td>'; 
			}
			$i++;
		}
		if($this->disable_edit!=1) {
			$page .= '<th nowrap colspan=2 style="color:#fff" >Action</th>';	
		}
		
		$page .= '</tr>';
		//$page .= '<thead>';
		
		
		//$page .= '<tfoot id="" bgcolor="white">';
		//$page .= '<tr  bgcolor="#fff">';
		//$page .= $footerTD;
		
		//$page .= '<td nowrap colspan=2>&nbsp;</td>';
		//$page .= '</tr>';
		//$page .= '<tfoot>';
		
		
		//$page .= '<tbody id="tbody" cellpadding="0" cellspacing="1" bgcolor="#ccc">';
		$count = 0;
		$fields = mysql_query('SELECT * FROM '.$this->table) or die(mysql_error());
		while($array = mysql_fetch_array($select)){
			//$page .= (!($count % 2) == 0) ? '<tr class="even" style="background:'.$this->row_even.';">' : '<tr class="odd" style="background:'.$this->row_odd.';">';
			$page .= (!($count % 2) == 0) ? '<tr class="even" bgcolor="white" >' : '<tr class="odd"  bgcolor="#F7F7F7">';
			foreach($array as $column => $value){
				if(!is_int($column) && $column != 'id' && $column != 'updated_at' && $column != 'created_at'){
					$page .= '<td >';
					
					if($this->htmlsafe) {
						
						$page .= htmlentities($value);
					}else{
						if(substr($column,-3)=='_id')
						{
							$page .= $this->get_foreign_key_value($column,$value);
							
						}else {
							$page .=  $value;
						}
						
					}
					$page .= '</td>';
				}
			}
			$count ++;
			if($this->disable_edit!=1) {
				if(count($this->set_action_button)==0) {
					
					if($this->edit_record_link=='') {
						$page .= '<td><div align=center><form name="edit_'.$array[0].'" id="edit_'.$array[0].'" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="edit"><input type="hidden" name="id" value="'.$array[0].'"><a href="javascript:document.edit_'.$array[0].'.submit()">Edit</a></form></td>';
					}else {
						$page .= "<td><div align=center><a href=". url($this->edit_record_link ."/". $array[0]) ." ><span>Edit</span></a></td>";
					}
					
					if($this->disable_delete!=1) {
					$page .= '<td>
						<center><form name="delete_'.$array[0].'" id="delete_'.$array[0].'" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="delete"><input type="hidden" name="id" value="'.$array[0].'"><a href="javascript:" onClick="if (confirm(\'Are you sure?\')){document.delete_'.$array[0].'.submit();}else{return false;}">Delete</a></form></center></td>';
					}
				}
				else {
					$page .= "<td><div align=center>";
					foreach($this->set_action_button as $key=>$value) {
						$page .= "<a href=". url($value['action']."/".$array[0]) ." ><span>".$value['label']."</span></a> &nbsp;&nbsp;&nbsp;";
					}
					$page .= "</div></td>";
				}
			}
			$page .= '</tr>';
		}
		//$page .= '</tbody>';
		$page .= "</table>";
	
		
		//echo $page;
		return $page;
		
		$this->paginate($total);      	
	}

	function list_table_all($msg = NULL, $where = null)
	{
		$start = 0;//(($this->page-1)*$this->max_records);				// start parameter for pages
		$end = $this->max_records;									// end parameter for pages
		$page = '';													// var to buiild display
		$totalQuery = mysql_query ('SELECT COUNT(*) FROM '.$this->table. ' ' . $where) or die(mysql_error());
		
		$totalA = mysql_fetch_array($totalQuery);
		$total = $totalA[0];
		
		if (!empty($this->fields)) {
		// just display the selected fields
			$query = 'SELECT id';
			foreach($this->fields as $val){
				$query .= ', '.$val;
			}
			$query .= ' FROM '.$this->table;
		}else{
			$query = 'SELECT * FROM '.$this->table;
		}
		if(!empty($where)){ $query .= $where; }
		$query = $query; // LIMIT '.$start.', '.$end;
		$select = mysql_query($query) or die(mysql_error());
		$i = 0;
		
		(!empty($this->width)) ? $width = ' width="'.$this->width.'"' : $width = NULL;
		
			$page .= '<table class="scaffold-container"  border="0"'.$width.'" cellpadding=5 cellspacing=1 bgcolor=#ccc>';
		$page .= '<caption id="theme01_caption">';
		
		
		if(!empty($msg)) { $page .= $msg; }
	
		if($this->disable_add!=1) {
		//$page .='&nbsp;<form name="newrecord" id="newrecord" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="new"><a class="button" href="javascript:document.newrecord.submit()"><span>Create New Record</span></a></form><br /><br />';
			if($this->create_new_record_link!='')
			{
				$page .="<a class=\"button\" href=". url($this->create_new_record_link) ." ><span>Create New Record</span></a><br><br>";
			}else{
				$page .='&nbsp;<form  name="newrecord" id="newrecord" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="new"><a class="button" href="javascript:document.newrecord.submit()"><span>Create New Record</span></a></form><br><br>';		
			}
		}
		
		if($this->paginate($total, $this->page)!='') {
			$page .= '<div align=left> '. $this->paginate($total, $this->page). '</div>';
		}
		//$page .= '<br><div align=right>Total Records: <a href='.$_SERVER['PHP_SELF'].'>'.  $total . '</a></div>';
		$page .='&nbsp;<div align=right><form class="scaffold-container" name="listall" id="listall" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="listall">Total Record(s):<a href="javascript:document.listall.submit()"> ' . $total. '</a></form></div>';
		$page .= '</caption>';
	
		//$page .= '<thead id="theme01_thead">';
		$page .= '<tr  align=center bgcolor=#5588BB>';
		while($i < mysql_num_fields($select)){
			$column = mysql_fetch_field($select, $i);
			if($column->name != 'id' && $column->name != 'updated_at' && $column->name != 'created_at'){
				$page .= '<th nowrap style="color:#ffffff">'.$this->build_friendly_names($column->name).'</th>';
				$footerTD .= '<td>&nbsp;</td>';
			}
			$i++;
		}
		if($this->disable_edit!=1) {
			$page .= '<th nowrap colspan=2 style="color:#ffffff" >Action</th>';
		}
		$page .= '</tr>';
		//$page .= '<thead>';
		
		
		//$page .= '<tfoot >';
		//$page .= '<tr  >';
		//$page .= $footerTD;
			
		//$page .= '<th nowrap colspan=2>&nbsp;</th>';
		//$page .= '</tr>';
		//$page .= '<tfoot>';
		
		
	//	$page .= '<tbody id="theme01_tbody">';
		$count = 0;
		while($array = mysql_fetch_array($select)){
			//$page .= (!($count % 2) == 0) ? '<tr class="even" style="background:'.$this->row_even.';">' : '<tr class="odd" style="background:'.$this->row_odd.';">';
			$page .= (!($count % 2) == 0) ? '<tr class="even" bgcolor=#ffffff>' : '<tr class="odd" bgcolor="#F7F7F7">';
			foreach($array as $column => $value){
				if(!is_int($column) && $column != 'id' && $column != 'updated_at' && $column != 'created_at'){
					$page .= '<td >';
					if($this->htmlsafe) {
						
						$page .= htmlentities($value);
					}else{
						if(substr($column,-3)=='_id')
						{
							$page .= $this->get_foreign_key_value($column,$value);
							//$page .= $value . $column;
						}else {
							$page .=  $value;
						}
					}
					$page .= '</td>';
				}
			}
			$count ++;
			if($this->disable_edit!=1) {
				if(count($this->set_action_button)==0) {
					
					if($this->edit_record_link=='') {
						$page .= '<td><div align=center><form name="edit_'.$array[0].'" id="edit_'.$array[0].'" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="edit"><input type="hidden" name="id" value="'.$array[0].'"><a href="javascript:document.edit_'.$array[0].'.submit()">Edit</a></form></td>';
					}else {
						$page .= "<td><div align=center><a href=". url($this->edit_record_link ."/". $array[0]) ." ><span>Edit</span></a></td>";
					}
					
					if($this->disable_delete!=1) {
					$page .= '<td>
						<center><form name="delete_'.$array[0].'" id="delete_'.$array[0].'" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline"><input type="hidden" name="xxbmnnzhfwggsf" value="delete"><input type="hidden" name="id" value="'.$array[0].'"><a href="javascript:" onClick="if (confirm(\'Are you sure?\')){document.delete_'.$array[0].'.submit();}else{return false;}">Delete</a></form></center></td>';
					}
				}
				else {
					$page .= "<td><div align=center>";
					foreach($this->set_action_button as $key=>$value) {
						$page .= "<a href=". url($value['action']."/".$array[0]) ." ><span>".$value['label']."</span></a> &nbsp;&nbsp;&nbsp;";
					}
					$page .= "</div></td>";
				}
			}
			$page .= '</tr>';
		}

		//$page .= '</tbody>';
		$page .= "</table>";
	
		
		//echo $page;
		return $page;
		
		$this->paginate($total);      
	
	}


	/**
	* This method builds a new record form page
	*
	*/
	function new_row($msg=''){

		$page = '';
		if(!empty($msg)) { $page .= $msg; }
		
		$selectFields = mysql_query('SELECT * FROM '.$this->table);
		$i = 0;
		$page .= '<form id="'.$this->formIdName.'" action="'.$_SERVER['PHP_SELF'].'" method="POST">'
				. '<input type="hidden" name="xxbmnnzhfwggsf" value="create">'
				. '<div class="formWrapper"><table id="theme01_table" cellpadding="2" cellspacing="0" border="0">'
				. '<caption><div align=right style="float:right"><a class="button" href="'.$_SERVER['PHP_SELF'].'"><span>Back To Listings</span></a></div><br><br></caption>'
				. '<thead id="theme01_thead">'
				. '<tr>'
				. '	<th class="formControl" scope="col" colspan=2>Add New Record</th>'
				. '</tr>'
				. '</thead>	'
				//. '<tfoot >'
				//. '<th>'
				//. '	<td colspan=2>&nbsp;</td>'
				//. '</th>'
				//. '</tfoot>'
				. '<tbody>';
		while($i < mysql_num_fields($selectFields)){
			$column = mysql_fetch_field($selectFields);
	
			if($column->name != 'id'){
				$page .= '<tr>';
				// check for foreign keys..
				if(substr($column->name, -3) == '_id'){
					$page .= $this->build_foreign_key_dropdowns($column->name);
				}elseif($column->blob == 1){
					$page .= '<td  class="formLabel" valign="top"><strong>'.$this->build_friendly_names($column->name).':</strong></td><td  class="formControl"> <textarea name="'.$column->name.'" rows="10" cols="45"></textarea></td>';
				}elseif($column->type == 'timestamp'){
					$page .= '<input type="hidden" name="'.$column->name.'" />';
				}
				elseif($column->type == 'date')
				{	
					
																									
					$page .= '<td  class="formLabel" > <strong>'.$this->build_friendly_names($column->name).':</strong></td><td class="formControl"><input type="text" id="'.$column->name.'"  name="'.$column->name.'" /></td>';
				}else{
					$page .= '<td  class="formLabel"><strong>'.$this->build_friendly_names($column->name).':</strong></td><td class="formControl"><input type="text" name="'.$column->name.'" value="'. $_POST[$column->name] .'" size="35" /></td>';
				}
			}
			$i++;
		}
		$page .= '<tr><td class="formControl" >&nbsp;</td><td class="formControl"><input type="submit" value="Add New Record" /></td></tr>'
				. '</tbody>'
		 		. '</table></div>'
		 		. '</form>';
		 		
		return $page;
	}
	
	

	/**
	* This method inserts a new record
	* It is assumed that there is not a databse field named 'xxbmnnzhfwggsf'
	* That is my control variable for post navigation
	*
	*/
	function create(){
		if($this->validateFields()==0 && $this->success==1){
			$select = mysql_query('SELECT * FROM '.$this->table);
			$insert = 'INSERT INTO '.$this->table.' VALUES(\'\', ';
			$i = mysql_num_fields($select);
			$i--;
			foreach($_POST as $key => $value){
				if($key != 'xxbmnnzhfwggsf'){
					($key == 'updated_at' || $key == 'created_at')? $value = 'NOW()' : (get_magic_quotes_gpc) ? $value =  "'".mysql_real_escape_string(stripslashes($value))."'" : $value = "'".mysql_real_escape_string($value)."'"; 
					$i--;
					if($i > 0){
						$insert .= $value.", ";
					}
				}
			}
			$insert .= $value.')';
			mysql_query($insert) or die(mysql_error());
			$last_idq = mysql_query('SELECT LAST_INSERT_ID()')or die(mysql_error());
			$last_id = mysql_fetch_array($last_idq);
			
			$this->success=0;
			return $this->list_table('<div style="color:#090;"><center>Successfully Added..</center></div>',$this->where);
		}else
		{
			return $this->new_row('<div style="color:#090;">'. implode('<br />',$this->err) .'</div>');
		}
	}

	/**
	* This method builds the edit record form page
	*
	*/
	function edit_row($id='', $msg=''){
		if(!isset($_POST['id']))
		{
			$_POST['id'] = $id;
		}
		
		$page = '';
		if(!empty($msg)) { $page .= $msg; }
		$fields = mysql_query('SELECT * FROM '.$this->table) or die(mysql_error());
		$select = mysql_query('SELECT * FROM '.$this->table.' WHERE id = '.intval($_POST['id']));
		$row = mysql_fetch_row($select);
		$i = 0;
		
		$page .= '<form id="'.$this->formIdName.'" action="'.$_SERVER['PHP_SELF'].'" method="POST">'
				. '<input type="hidden" name="xxbmnnzhfwggsf" value="update">'
		 		. '<div class="formWrapper"><table id="theme01_table" cellpadding="2" cellspacing="0" bgcolor="#ccc" border="0">'
				. '<caption><div align=right style="float:right"><a class="button" href="'.$_SERVER['PHP_SELF'].'"><span>Back To Listings</span></a></div><br><br></caption>'
				. '<thead id="theme01_thead">'
				. '<tr>'
				. '	<th  class="formControl" id="theme01_th" scope="col" colspan=2>Edit Record</th>'
				. '</tr>'
				. '</thead>	'
				//. '<tfoot >'
				//. '<th>'
				//. '	<td colspan=2>&nbsp;</td>'
				//. '</th>'
				//. '</tfoot>'
				. '<tbody id="theme01_tbody">';
		while($i < mysql_num_fields($fields)){
			$field = mysql_fetch_field($fields);
			if($field->name != 'id'){
				$page .= '<tr >';
				// check for foreign keys..
				if(substr($field->name, -3) == '_id'){
					$page .= $this->build_foreign_key_dropdowns($field->name, $row[$i]);
				}elseif($field->blob == 1){
					$page .= '<td class="formControl" valign="top"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl"> <textarea name="'.$field->name.'" rows="10" cols="45">'.$row[$i].'</textarea></td>';
				}elseif($field->type == 'timestamp'){
					$page .= '<td><strong>'.$this->build_friendly_names($field->name).':</strong></td><td>'.$row[$i].'</td>';
				}else{
					if(in_array($field->name, $this->uniqueFields))
					{
						$page .= '<td class="formLabel" border="0"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl"><div align="left"> '. $row[$i]. '</div></td>';
					}else{
						$page .= '<td class="formLabel" border="0"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl"><input type="text" id="'.$field->name.'" name="'.$field->name.'" value="'.$row[$i].'" size="35" /></td>';
					}
				}

			}else{
				$page .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl">'.$row[$i].'<input type="hidden" name="id" value="'.$row[$i].'"></td>';
			}
			$i++;
			$page .= '</tr>';
		}
		$page .= '<tr><td  class="formControl">&nbsp;</td><td class="formControl"><input type="submit" value="Edit Record" /></td></tr>'
				. '</tbody>'
				 . '</table></div>' 
				 . '</form>';
				 
		return $page;
	}
	

	/**
	* This method updates the record
	*
	*/
	function update(){
	
		if($this->validateFields()==0){
		
			$select = mysql_query('SELECT * FROM '.$this->table.' WHERE id = '.intval($_POST['id']));
			$num = mysql_num_fields($select);
			$update = 'UPDATE '.$this->table.' SET ';
			$i = 1;
			$comma = '';
			while($i <= $num){
				$column = mysql_fetch_field($select);
				if($column->name != 'id' && $column->name != 'created_at' && $column->name != 'updated_at'){
						$update .= $comma.$column->name.' = ';
						$update .= (get_magic_quotes_gpc) ? '\''.mysql_real_escape_string(stripslashes($_POST["$column->name"])).'\'' : '\''.mysql_real_escape_string($_POST["$column->name"]).'\'';
						$comma =', ';
				}
				$i++;
			}
			$update .= '  WHERE id = '.intval($_POST['id']);
			mysql_query($update) or die(mysql_error());
			return $this->list_table('<div style="color:#090;"><center>Successfully Updated</center></div>',$this->where);
		
		}
		else
		{
			return $this->edit_row($id,'<div style="color:#090;">'. implode('<br />',$this->err) .'</div>',$this->where);
		}
	}

	/**
	* This method deletes a record
	*
	*/
	function delete_row(){
		mysql_query('DELETE FROM '.$this->table.' WHERE id = '.$_POST['id']) or die(mysql_error());
		
		return $this->list_table('<div style="color:#900;"><center>Successfully Deleted..</center></div>'. $this->where,$this->where);
	}

	/**
	* This method builds the search to be passed to list_table
	*
	*/
	function search(){
		if(!empty($_POST['searchterm'])){
			// safety first... 
			$searchterm = (get_magic_quotes_gpc) ? '\''.mysql_real_escape_string(stripslashes($_POST['searchterm'])).'\'' : '\''.mysql_real_escape_string($_POST['searchterm']).'\'';
			// just in case.. who knows with curl utils
			$field = (get_magic_quotes_gpc) ? '\''.mysql_real_escape_string(stripslashes($_POST['field'])).'\'' : '\''.mysql_real_escape_string($_POST['field']).'\'';
			switch ($_POST['compare']){
				default:
					$compare = '1';
					$compare = NULL;
					$searchterm = NULL;
					break;
				case '=':
					$compare = ' = ';
					break;
				case '>':
					$compare = ' > ';
					break;
				case '<':
					$compare = ' < ';
					break;
				case 'LIKE':
					$compare = ' LIKE ';
					$searchterm = (get_magic_quotes_gpc) ? "'%".mysql_real_escape_string(stripslashes($_POST["searchterm"]))."%'" : "'%".mysql_real_escape_string($_POST["searchterm"])."%'";
					break;
			
			}
			$where = ' WHERE '.$_POST['field'].$compare.$searchterm;
		}else{
			$where = NULL;
		}
		return $this->list_table('<div style="color:#090"></div>',$where);	
	}

	function get_foreign_key_value($field,$value) {
		//$foreignTable = substr($field, 0, -3);

		$foreignTable = $this->checkDefineForeignTable($field);
		$foreignField = $this->checkDefineForeignField($field);
		
		$select = mysql_query('SELECT id, '. $foreignField . ' FROM '.$foreignTable .' WHERE id='.$value ) or die(mysql_error());
		$foreign = mysql_fetch_assoc($select);
		return $foreign[$foreignField];
		
	}
	
	
	
	/**
	* **IMPORTANT:**

	*/
	
	
	function build_foreign_key_dropdowns($field, $value = null) {	
	
		// check for user defined foreign key relationships
		$match = FALSE;
		$dd = '';
		$foreignTable = $this->checkDefineForeignTable($field);
		$foreignField = $this->checkDefineForeignField($field);
		//$foreignTable = substr($field, 0, -3);
		$select = mysql_query('SELECT id, '. $foreignField . ' FROM '.$foreignTable) or die("MYSQL Error: " . mysql_error());
		$foreign = mysql_fetch_assoc($select);
		$dd .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field).'</strong></td><td class="formControl"><div align="left">'
		. '<select name="'.$field.'"';
		do{
			$dd .= "<option value='".$foreign['id']."'";
			if ($foreign['id'] == $value){ $dd .= ' selected';}
			if (!empty($foreign[$foreignField])){
				$dd .= '>'.$foreign[$foreignField].'</option>';
			}else{
				$dd .= '>'.$foreign['id'].'</option>';
			}
		}while($foreign = mysql_fetch_assoc($select));
		$dd .= '</div></td>';
		return $dd;
	}
	
	
		
	
	function checkDefineForeignField($field) {
		
		$r= 'name';
		foreach($this->foreign_field as $key=>$val)
		{
			$a=0;
			foreach($val as $k => $v)
			{
				if($v==$field) {
					$r = $val['foreign_field'];
				}
				$a++;
			}
		}
		
		return $r;
	}
	
	
	
	function checkDefineForeignTable($field) {
	
		//echo $field;
		//print_r($this->foreign_table);
		$r = substr($field,0,-3);
		
		foreach($this->foreign_table as $key=>$val)
		{
			$a=0;
			foreach($val as $k => $v)
			{
				if($v==$field) {
					$r = $val['foreign_table'];
				}
				$a++;
			}
		}
		
		
		return $r;
		
	}
	
	/**
	* This method builds the pagination
	*
	* int $total 	// pass the total number of rows in the table
	*
	*/
	function paginate($total = 1) {		
		// pagination
	
		
		if($total>$this->max_records){
		// Build the recordset paging links
		$num_pages = ceil($total / $this->max_records);
		
		$last_page = $num_pages;
		$nav = '';
		
		if($this->page!=1) {
			$nav .= '<a class=scaffold_paginate href="'.$_SERVER['PHP_SELF'].'?page=' . (1) . '"><span>First</span></a> ';
		}
		
		// Can we have a link to the previous page?
		if($this->page > 1)
		$nav .= '<a class=scaffold_paginate href="'.$_SERVER['PHP_SELF'].'?page=' . ($this->page-1) . '"><span>Prev</span></a> ';
		
		if($num_pages>10) {
			
			if($this->page<=3) {
				$x=1;
			}else {
				$x= $this->page-3;	
				
				if($this->page>4) {
					$nav .= ' ... ';
				}	
			}
			
			$temp = $last_page - 3;
			if($this->page>=$temp ) {
				$nav_last = '';
				$num_pages = $last_page;
			}else {
				$nav_last = ' ... ';
				$num_pages = $this->page+3;	
			}
			

		}else {
			$x=1;	
		}

			for($i = $x; $i < $num_pages+1; $i++)
			{
				if($this->page == $i)
				{
				  // Bold the page and dont make it a link
				  $nav .= ' <a class=current>'.$i.'</a>';
				}
				else
				{
				  // Link the page
				  $nav .= ' <a class=scaffold_paginate href="'.$_SERVER['PHP_SELF'].'?page='.$i.'">'.$i.'</a>';
				
				}
			}
		  $nav .= $nav_last;
		// Can we have a link to the next page?
		if($this->page < $num_pages)
		$nav .= ' <a class=scaffold_paginate href="'.$_SERVER['PHP_SELF'].'?page=' . ($this->page+1) . '">Next</a>';
		
		if($this->page!=$last_page) {
			$nav .= ' <a class=scaffold_paginate href="'.$_SERVER['PHP_SELF'].'?page=' . ($last_page) . '">Last</a>';
		}
		
		// Strip the trailing pipe if there is one
		$nav = ereg_replace('@|$@', "", $nav);
		//echo $nav;
		return $nav;
		}
	}
	
	/**
	* This method builds the search bar display
	*
	*/
	function build_search_bar($fields = array()) {
		if(count($fields)==0)
		{
			// build the fields menu
			$fielddropdown = '<select name="field">';
			$fieldselect = mysql_query('SHOW FIELDS FROM '.$this->table);
			while($fields = mysql_fetch_assoc($fieldselect)){
				$fielddropdown .= '<option value="'.$fields['Field'].'">'.$this->build_friendly_names($fields['Field']).'</option>';
			}
			$fielddropdown .= '</select>';
			$searchterm = (!empty($_POST['searchterm'])) ? $_POST['searchterm'] : '' ;
			$search = '';
			$search .=  '<form name="searchbar" id="searchbar" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline;"><input type="hidden" name="xxbmnnzhfwggsf" value="search">'
					. $fielddropdown
					. '<select name="compare">'
					. '<option value="=">Is Equal To</option>'
					. '<option value="LIKE">Contains</option>'
					. '<option value="<">Is Less Than</option>'
					. '<option value=">">Is Greater Than</option>'
					. '</select>'
					. '<input type="text" name="searchterm" value ="'.$searchterm.'">'
					. '<input type="submit" name="Search" value="Search" />'		
					. '</form><br>';
					
			$return = $search;	
		}else
		{
			$fielddropdown = '<select name="field">';
			foreach($fields as $value)
			{				
				$fielddropdown .= '<option value="'.$value.'">'.$this->build_friendly_names($value).'</option>';
			}
			$fielddropdown .= '</select>';
			$search .=  '<form name="searchbar" id="searchbar" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline;"><input type="hidden" name="xxbmnnzhfwggsf" value="search">'
					. $fielddropdown
					. '<input type="hidden" name="compare" value="LIKE"> &nbsp;'
					. '<input type="text" name="searchterm" value ="'.$searchterm.'">'
					. '<input type="submit" name="Search" value="Search" />'		
					. '</form><br>';
					
			$return = $search;	
			
		}
		if($_POST['xxbmnnzhfwggsf']!='edit' && $_POST['xxbmnnzhfwggsf']!='edit2' && $_POST['xxbmnnzhfwggsf']!='new' )	{	return $return;	}
	}
	
	
	/**
	* This method returns reader friendly names
	* It will swap underscores with spaces and capitalize words for display
	*
	* string $field 	// pass the field name
	*
	*/
	function build_friendly_names($field) {
	
	
		if(substr($field, -3) == '_id'){
			//$new_name= substr($field, 0, -3);
			$new_name = $this->checkDefineFieldTitle($field);
			
			
		}else {
			$new_name = $this->checkDefineFieldTitle($field);
		}
		
		
		return $new_name;
	}
	
	function checkDefineFieldTitle($field) {
		
		if(substr($field, -3) == '_id'){
			$new_title = substr($field, 0, -3);
			$new_title = ucwords(str_replace('_', ' ', $field));
		}else {
			$new_title = ucwords(str_replace('_', ' ', $field)); 
		}
		
		foreach($this->field_title as $key=>$val)
		{
			$a=0;
			foreach($val as $k => $v)
			{
				if($v==$field) {
					$new_title = $val['new_field'];
				}
				$a++;
			}
		}
		return $new_title;
	}
	
	function show_one_record($id)
	{
	
		$action = (!empty($_POST['xxbmnnzhfwggsf'])) ? $_POST['xxbmnnzhfwggsf'] : 'list' ;
		switch($action){
			default:
				return $this->show_1record($id);
			break;
			
			case 'list':
		
				return $this->show_1record($id);
			break;

			case 'edit':
			
				return $this->edit_one_record($id);
			break;
			
			case 'update':
			
				return $this->update_one_record($id);
			break;
			
	
		}
	}
	
	function update_one_record($id)
	{
	
		if($this->validateFields()==0){
	
			$select = mysql_query('SELECT * FROM '.$this->table.' WHERE id = '.intval($id));
			$num = mysql_num_fields($select);
			$update = 'UPDATE '.$this->table.' SET ';
			$i = 1;
			$comma = '';
			while($i <= $num){
				$column = mysql_fetch_field($select);
				if($column->name != 'id' && $column->name != 'created_at' && $column->name != 'updated_at'){
						$update .= $comma.$column->name.' = ';
						$update .= (get_magic_quotes_gpc) ? '\''.mysql_real_escape_string(stripslashes($_POST["$column->name"])).'\'' : '\''.mysql_real_escape_string($_POST["$column->name"]).'\'';
						$comma =', ';
				}
				$i++;
			}
			$update .= '  WHERE id = '.intval($id);
			mysql_query($update) or die(mysql_error());
			return $this->show_1record($id,'<div style="color:#090;"><center>Successfully Modified..</center></div>');
		}
		else
		{
			return $this->edit_one_record($id,'<div style="color:#090;">'. implode('<br />',$this->err) .'</div>');
		}
	}
	
	
	
	function show_1record($id,$msg='')
	{
	
		$array[0] = $id;
		$page = '';
		if(!empty($msg)) { $page .= $msg; }
		
		if (!empty($this->fields)) {
		// just display the selected fields
			$query = 'SELECT id';
			foreach($this->fields as $val){
				$query .= ', '.$val;
			}
			$query .= ' FROM '.$this->table;
		}else{
			$query = 'SELECT * FROM '.$this->table;
		}
		
		$fields = mysql_query($query) or die(mysql_error());
		$select = mysql_query($query.' WHERE id = '.intval($id));
		$row = mysql_fetch_row($select);
		$i = 0;
		
		(!empty($this->width)) ? $width = ' width="'.$this->width.'"' : $width = NULL;
		
		
		$page .= '<form id="'.$this->formIdName.'" name="edit" id="edit" action="'.$_SERVER['PHP_SELF'].'" method="POST">'
				. '<input type="hidden" name="xxbmnnzhfwggsf" value="update">'
		 		. '<div class="formWrapper"><table cellpadding="2" cellpadding="2" cellspacing="0" border="0" '.$width.'">'
				. '<caption>&nbsp;</caption>'
				. '<thead id="theme01_thead">'
				. '<tr>'
				. '	<th scope="col" colspan=2></th>'
				. '</tr>'
				. '</thead>	'
				. '<tfoot >'
				. '<th>'
				. '	<td colspan=2>&nbsp;</td>'
				. '</th>'
				. '</tfoot>'
				. '<tbody id="theme01_tbody">';
		while($i < mysql_num_fields($fields)){
			$field = mysql_fetch_field($fields);
			if($field->name != 'id'){
				$page .= '<tr>';
				// check for foreign keys..
				if(substr($field->name, -3) == '_id'){
					$page .= $this->get_foreign_key_value($field->name, $row[$i]);
				}elseif($field->blob == 1){
					$page .= '<td class="formLabel" valign="top"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl">'.$row[$i].'</td>';
				}elseif($field->type == 'timestamp'){
					$page .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl">'.$row[$i].'</td>';
				}else{
					$page .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl"><div align=left>'.$row[$i].'</div></td>';
				}

			}else{
				//$page .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl">'.$row[$i].'<input type="hidden" name="id" value="'.$row[$i].'"></td>';
				$page .= '<input type="hidden" name="id" value="'.$row[$i].'">';
			}
			$i++;
			$page .= '</tr>';
		}
		$page .= '<tr><td class="formLabel">&nbsp;</td><td class="formControl"><input name="xxbmnnzhfwggsf" type="submit" value="edit" /></td class="formControl"></tr>'
				. '</tbody>'
				 . '</table></div>' 
				 . '</form>';
				 
		return $page;
	}
	
	function edit_one_record($id, $msg='')
	{
		$page = '';
		if(!empty($msg)) { $page .= $msg; }
		if (!empty($this->fields)) {
		// just display the selected fields
			$query = 'SELECT id';
			foreach($this->fields as $val){
				$query .= ', '.$val;
			}
			$query .= ' FROM '.$this->table;
		}else{
			$query = 'SELECT * FROM '.$this->table;
		}
		$fields = mysql_query($query) or die(mysql_error());
		$select = mysql_query($query .' WHERE id = '.intval($id));
		$row = mysql_fetch_row($select);
		$i = 0;
		
		$page .= '<form id="'.$this->formIdName.'" action="'.$_SERVER['PHP_SELF'].'" method="POST">'
				. '<input type="hidden" name="xxbmnnzhfwggsf" value="update">'
		 		. '<div class="formWrapper"><table cellpadding="2" cellpadding="2" cellspacing="0" border="0">'
				. '<caption><div align=right>&nbsp;</caption>'
				. '<thead id="theme01_thead">'
				. '<tr>'
				. '	<th id="theme01_th" scope="col" colspan=2></th>'
				. '</tr>'
				. '</thead>	'
				. '<tfoot >'
				. '<th>'
				. '	<td colspan=2>&nbsp;</td>'
				. '</th>'
				. '</tfoot>'
				. '<tbody id="theme01_tbody">';
		while($i < mysql_num_fields($fields)){
			$field = mysql_fetch_field($fields);
			if($field->name != 'id'){
				$page .= '<tr>';
				// check for foreign keys..
				if(substr($field->name, -3) == '_id'){
					$page .= $this->build_foreign_key_dropdowns($field->name, $row[$i]);
				}elseif($field->blob == 1){
					$page .= '<td class="formLabel" valign="top"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl"> <textarea name="'.$field->name.'" rows="10" cols="45">'.$row[$i].'</textarea></td>';
				}elseif($field->type == 'timestamp'){
					$page .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl">'.$row[$i].'</td>';
				}else{
					if(in_array($field->name, $this->uniqueFields))
					{
						$page .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl"><div align=left> '. $row[$i]. '</div></td>';
					}else{
						$page .= '<td class="formLabel"><strong>'.$this->build_friendly_names($field->name).':</strong></td><td class="formControl"> <input type="text" name="'.$field->name.'" value="'.$row[$i].'" size="35" /></td>';
					}
					
				}

			}else{
				//$page .= '<td><strong>'.$this->build_friendly_names($field->name).':</strong></td><td>'.$row[$i].'<input type="hidden" name="id" value="'.$row[$i].'"></td>';
				$page .= '<input type="hidden" name="id" value="'.$row[$i].'">';
			}
			$i++;
			$page .= '</tr>';
		}
		$page .= '<tr><td class="formControl">&nbsp;</td><td class="formControl"><input type="submit" value="Edit Record" /></td></tr>'
				. '</tbody>'
				 . '</table></div>' 
				 . '</form>';
				 
		return $page;
	}
	
	
	
	/*function get_foreign_key_value($field, $value = null)
	{
		// check for user defined foreign key relationships
		$match = FALSE;
		$dd = '';
		for($i=0; $i<count($this->singular); $i++){
			$match = preg_match('/^'.$this->singular[$i].'$/', substr($field, 0, -3));
			if($match){break;}
		}
		if($match){			
			$foreignTable = str_replace($this->singular, $this->plural, substr($field, 0, -3));
		}else{
			// break off trailing '_id' and pluralize name
			$foreignTable = substr($field, 0, -3);
			(substr($foreignTable, -1) != 'y') ? $foreignTable .= 's' : $foreignTable = substr($foreignTable, 0, -1).'ies';
		}
		$select = mysql_query('SELECT id, name FROM '.$foreignTable) or die(mysql_error());
		$foreign = mysql_fetch_assoc($select);
		$dd .= '<td><strong>'.$this->build_friendly_names(substr($field, 0, -3)).'</strong></td><td>';
		do{
			if($foreign['id'] == $value)
			{		
				$dd .= $foreign['name'];
			}
		}while($foreign = mysql_fetch_assoc($select));
		$dd .= '</td>';
		return $dd;
	}*/
}

?><style>
.scaffold_paginate {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

a.scaffold_paginate {
	border: 1px solid #E8E8E8;
	padding: 2px 6px 2px 6px;
	text-decoration: none;
	color: #000080;
}


a.scaffold_paginate:hover {
	background-color: #5588bb;
	color: #FFF;
	text-decoration: underline;
}

a.current {
	border: 1px solid #000080;
	font: bold 12px Arial,Helvetica,sans-serif;
	padding: 2px 6px 2px 6px;
	cursor: default;
	background:#000080;
	color: #FFF;
	text-decoration: none;
}

span.inactive {
	border: 1px solid #999;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	padding: 2px 6px 2px 6px;
	color: #999;
	cursor: default;
}
table.scaffold-container, table.scaffold-container td, table.scaffold-container th {
	font-size:12px;
}

</style>