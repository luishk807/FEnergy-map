<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="stylesheet" href="../js/sw/spinningwheel.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/sw/spinningwheel-min.js?v=1.4"></script>
<script type="text/javascript" language="javascript">
function openthis() {
	var numbers = {'test3.php':'Reset Map', 'test2_view_all.php':'View All Map', '../test2_logout.php': 'Logout'};
	SpinningWheel.addSlot(numbers);
	//SpinningWheel.setCancelAction(cancel);
	SpinningWheel.setDoneAction(done);
	SpinningWheel.open();
}
function done() {
	var results = SpinningWheel.getSelectedValues();
	//document.getElementById('result').innerHTML = 'values: ' + results.values.join(' ') + '<br />keys: ' + results.keys.join(', ');
	window.location.href=results.keys.join(', ');
}
/*function cancel() {
	alert('here');
}*/
</script>
<title>Untitled Document</title>
</head>

<body>
<form action="">
<input type="button" value="Open" onclick="openthis()" />
</form>
</body>
</html>