<?php  
	ob_start();
	session_start();
	include_once("phpmailer/phpmailer.php");
	require_once("connect.php");

	if (isset($_POST['button']))
	{
		echo "jest";
		mails("janek testpwy",date('l jS F Y h:i:s A'),"dziala","czy bedzie dzialac?");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Test phpmailer</title>

	<style type="text/css">
		
	</style>
</head>
<body>
	<div>
		<form action="test-2.php" method="POST">
			<input type="hidden" name="cokolwiek" value="asdasdasd">
			<input type="submit" name="button" value="Sprawdź">
		</form>
	</div>
</body>

<script type="text/javascript">

</script>
</html>