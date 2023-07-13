<strong>DEMO:</strong>

<div class="formWrapper">
  <form id="myForm" method="post" class="validationForm" action="<?php echo url('test/test');?>">
<table width="542" border="0" cellpadding="3" cellspacing="0">
  <tr>
    <td width="185" valign="top" class="formLabel">Username</td>
    <td width="345" valign="top" class="formControl"><input type="text" class="curve" name="username" id="username" /></td>
  </tr>
  <tr>
    <td valign="top" class="formLabel">Last Name</td>
    <td valign="top" class="formControl"><input type="text" class="curve" name="last_name" id="last_name" /></td>
  </tr>
  <tr>
    <td valign="top" class="formLabel">Address</td>
    <td valign="top" class="formControl"><textarea class="curve" name="address" cols="30" rows="5" style="height:50px" id="address"></textarea></td>
  </tr>
  <tr>
    <td valign="top" class="formLabel">Date Started</td>
    <td valign="top" class="formControl"><input type="text" class="curve" name="date_started" id="date_started" /></td>
  </tr>
  <tr>
    <td valign="top"></td>
    <td valign="top"><span class="button"><span><button class="button_j" id="button" name="button" type="submit">Compute</button></span></span></td>
  </tr>    
</table>
</form>
</div>
<script>
 $('#date_started').datepicker();
$.validator.addMethod("checkAvailability",function(value,element){
	var x= $.ajax({
	url: base_url+'source/json_username_check',
	type: 'POST',
	async: false,
	data: "username=" + value + "&checking=true",
	}).responseText;

	var s = new String(x);
	x=s.trim();

	if(x=="true") return true;
	else return false;
	},"Sorry, this user name is not available");


var validator = $("#myForm").validate({
		rules: {
			username: {
				required:true,
				checkAvailability:true
			},
			last_name: {required:true},
			address: {required:true}
		},
		messages: {
			username:{checkAvailability:"username is already exist"},
			first_name: {required:"first name is required"}
		},
		submitHandler: function() {
			$('#myForm').ajaxSubmit({
				success:function(o) {						
					alert('hello world');
				}
			});
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
		//errorElement: "div"
	});
</script>
<strong>INCLUDE:</strong><br />
Jquery::loadValidator(); <br />
<br />
<strong>Script:</strong><br />
<textarea name="textarea" id="textarea" cols="80" rows="13" wrap="soft">
  <script>
 $('#date_started').datepicker();
$.validator.addMethod("checkAvailability",function(value,element){
	var x= $.ajax({
	url: base_url+'source/json_username_check',
	type: 'POST',
	async: false,
	data: "username=" + value + "&checking=true",
	}).responseText;

	var s = new String(x);
	x=s.trim();

	if(x=="true") return true;
	else return false;
	},"Sorry, this user name is not available");


var validator = $("#myForm").validate({
		rules: {
			username: {
				required:true,
				checkAvailability:true
			},
			last_name: {required:true},
			address: {required:true}
		},
		messages: {
			username:{checkAvailability:"username is already exist"},
			first_name: {required:"first name is required"}
		},
		submitHandler: function() {
			$('#myForm').ajaxSubmit({
				success:function(o) {						
					alert('hello world');
				}
			});
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
		//errorElement: "div"
	});
</script>
</textarea>
<br />
<strong>Controller:</strong><br />
<textarea name="textarea2" id="textarea2" cols="80" rows="13" wrap="soft">
function json_username_check() {
    $new_username = $_POST['username'];
    
    $isExist = Model::runSql("SELECT * FROM g_user WHERE  username='".$new_username."' ",true);
    if(count($isExist)>0) {
        
        echo "false";
    }else {
        echo "true";	
    }
}
</script>
</textarea>
