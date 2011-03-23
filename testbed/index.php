<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>GET/POST Request TestBed App</title>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<link rel="stylesheet" type="text/css" href="scripts/jqueryui/css/redmond/jquery-ui-1.8.10.custom.css"/>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>   
	<script type="text/javascript" src="scripts/jqueryui/js/jquery-ui-1.8.10.custom.min.js"></script>   
	<script language="javascript">
		$(document).ready(function(){
			$('#tabs').tabs();
		});
	</script>
</head>
<body>
	<div>
		Request Type:<br/>
		<input type="radio" name="method" id="method" value="get" checked> GET
		<input type="radio" name="method" id="method" value="post"/> POST
		<input type="radio" name="method" id="method" value="put"/> PUT
		<input type="radio" name="method" id="method" value="delete"/> DELETE
	</div>
	<div id="tabs">
		<ul>
			<li><a href="#page1">Send Variable Data</a></li>
			<li><a href="#page2">Create New User</a></li>
		</ul>
		<div id="page1">
			<form id="mainform">
				<fieldset id="options">
					
					<div>
						<label for="request_url">URL:</label><br/>
						<input type="text" id="request_url" value="ajax.php">
					</div>
					<div>
						Parameters:<br/>
						<select id="parameters" multiple="multiple"></select>
						<input type="button" id="delete_parameter" value="Delete Selected"/>
						<br/>
						Add Parameter:<br/>
						<label for="param_name">Name:</label>
						<input type="text" id="param_name"><br/>
						<label for="param_value">Value:</label>
						<input type="text" id="param_value">
						<input type="button" value="Add Parameter" id="add_parameter">
					</div>
					<div>
						<input type="button" id="btnpush" value="Send Request"/>
					</div>
				</fieldset>
			</form>
		</div>
		<div id="page2">
			<form id="newuserform">
				<fieldset>
					<div>
						<label for="newuser_request_url">Target URL:</label><input type="text" value="create_user.php" id="newuser_request_url"><br/>
					</div>
					<div>
						<label for="newuser_email">Email Address:</label><input type="text" id="newuser_email" value="g3ddylee@gmail.com"><br/>
						<label for="newuser_firstname">First Name:</label><input type="text" id="newuser_firstname" value="Gedrick"><br/>
						<label for="newuser_lastname">Last Name:</label><input type="text" id="newuser_lastname" value="Wilson"><br/>
						<label for="newuser_phone">Phone Number:</label><input type="text" id="newuser_phone" value="9739050488"><br/>
						<label for="newuser_password">Password:</label><input type="text" id="newuser_password" value="Test123">
					</div>
					<div>
					<label for="newuser_htmlspecialchars">HTML Special Chars:</label><input type="checkbox" id="newuser_htmlspecialchars" checked="checked"/>
					</div>	
					<div>
						<input type="button" id="btnCreateUser" value="Create User"/>
					</div>
					<div id="newuser_passingvalues">
						
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<br/><br/>
	<div>
		Response Text:<br/>
		<textarea id="request_result"></textarea><br/>
		<input type="button" id="btnprocess_html" value="Parse HTML"/>
		<input type="button" id="btnprocess_json" value="Parse JSON"/>
		<br/><br/><br/>
		Response HTML:<br/>
		<div id="result_html" style="border:1px solid silver;width:600px;height:400px;"></div>
		
		Decoded JSON:<br/>
		<div id="result_json" style="border:1px solid silver;width:600px;height:400px;overflow:scroll;"></div>
	</div>	
	
<script language="javascript">
	$(document).ready(function(){
			
		$('#btnCreateUser').click(function(){
			var email=$('#newuser_email').val();
			var firstname=$('#newuser_firstname').val();
			var lastname=$('#newuser_lastname').val();
			var phone=$('#newuser_phone').val();
			var password=$('#newuser_password').val();
			var htmlspecialchars=$('#newuser_htmlspecialchars').attr('checked');
			
			$.get('to_json.php',{
				email:		email,
				firstname:	firstname,
				lastname:	lastname,
				phone:		phone,
				password:	password,
				htmlchars:	htmlspecialchars
			},function(data){				
				$('#newuser_passingvalues').text('Request string: ?data='+data);
				var reqtype=$('#method:checked').val();
				var url=$('#newuser_request_url').val();
				$.ajax({
					type: reqtype,
					url: url,
					data: data,
					success: function(response){						
						$('#request_result').val(response);	
					},
					error: function (response){
						alert('Error sending request: '+response.responseText);	
					}
				});				
			});
		});
			
		$('#add_parameter').click(function(){
			//field checking
			if ( ($('#param_name').val()=='') || ($('#param_value').val()=='') ){
				alert('Enter all values');
			}else{
				$('#parameters').append('<option value="'+$('#param_value').val()+'">'+$('#param_name').val()+'</option>');
				//$('#param_name').val('');
				//$('#param_value').val('');
			}
		});
		
		//remove selected options from parameters list
		$('#delete_parameter').click(function(){
			$('#parameters option:selected').remove();	
		});
		
		$('#btnprocess_html').click(function(){
			$('#result_html').html('').html($('#request_result').val());
		});
		
		$('#btnprocess_json').click(function(){
			$.get('json_process.php',{
				json: $('#request_result').val()		
			},
			function(data){
				$('#result_json').html('<pre>'+data+'</pre>');	
			});
		});
		
		$('#btnpush').click(function(){
			$('#request_result').val();
			var params="";
			if ($('#parameters option').length>0){				
				$('#parameters option').each(function(){
					params+=$(this).text() + "=" + $(this).val() + "&";		
				});
				alert(params);
			}
			var reqtype=$('#method:checked').val();
			var url=$('#request_url').val();
			$.ajax({
				type: reqtype,
				url: url,
				data: params,
				success: function(response){
					$('#request_result').val(response);	
				},
				error: function (response){
					alert('Error sending request: '+response.responseText);	
				}
			});
		});
	});
</script>
</body>
</html>
