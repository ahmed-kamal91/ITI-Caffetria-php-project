<!-- The iframe that shows the counter -->
<iframe name="counterFrame" src="counter.php" style="border:none; width:200px; height:100px;"></iframe>

<!-- Form that targets the iframe -->
<form action="increase.php" method="post" target="counterFrame">
    <button type="submit">Increase</button>
</form>
