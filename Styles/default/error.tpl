<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Jason" />
	<title>{$type|capitalize} Error</title>{literal}
	<style>
		h1 {
			font-weight: bold;
			color: maroon;
			padding: 0;
			margin: 0;
		}
		.box {
			border: 2px solid green;
			min-width: 200px;
			margin: 0px 25px 25px 25px;
			padding: 5px;
		}
	</style>{/literal}
</head>
<body>
	<p>
		<h1>NGen &raquo; {$type|capitalize} Error</h1><br /><br />
		<p class="box">
			<strong>Exception Encountered</strong><br />
			<strong>Code</strong>: {$code}<br />
			<strong>Line</strong>: {$line}<br />
			<strong>Location</strong>: {$file}<br />
			<em>{$message}</em>
		</p>
	</p>
</body>
</html>