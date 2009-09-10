<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Error Encountered</title>
	{literal}
	<style type="text/css" media="screen">
		body {
			font-family: Tahoma, Courier;
			font-size: 12px;
			background: url('image/system/shade.png') repeat-x #FFFFFF;
		}
		
		#title {
			color: #D50000;
			font-size: 20px;
		}
		
		#errcontainer {
			width: 600px;
			margin: 0 auto;
		}
		
		#errbox {
			width: 100%;
			padding: 5px;
			background-color: #E1E1E1;
			border: 1px solid #FFFFFF;
			border-left: 3px solid #FFFFFF;
		}
		
		#errmsg {
			padding: 0;
			margin: 0;
			background-color: #D2D2D2;
			font-family: Courier;
		}
		
		#location_text {
			font-weight: bold;
			color: #D50000;
			font-family: Courier;
			margin-top: 8px;
			float: right;
		}
	</style>
	{/literal}
</head>
<body>
	<div id="errcontainer">
		<strong id="title">Critical Error!</strong><span id="location_text">/{$section}/{$action}</span>
		<br />
		<div id="errbox">
			While attempting to display this page we encountered a critical error. Due to the nature of this error, we are unable to display this page. You should report this to the administrator of this site. Additionally, you may try <a href="#" onclick="window.location.reload()">reloading</a> this page.<br />
			<br />
			<strong>Error Details</strong><br />
			<p id="errmsg">
				<strong>File:</strong> {$errFile}<br />
				<strong>Line:</strong> {$errLine}<br />
				<strong>Details</strong><br />
				{$errMsg}
			</p>
		</div>
	</div>
</body>
</html>