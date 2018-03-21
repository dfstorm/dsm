
<?php
	$s = "";
	$d = "";
	if(isset($_POST['query']))
	{
		$d = $_POST['query'];
		$s = base64_encode($d);
	}
?>
<h3>base64 converter</h3>
<input type="text" name="re" value="<?php echo $s; ?>" /><br /><br />
<p>..</p>
<form action="/dsm/test.php" method="POST">
	<textarea type="text" name="query"><?php echo $d; ?></textarea>
	<br />
	<input type="submit" name="send" value="send">
</form>

