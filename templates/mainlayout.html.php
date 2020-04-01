<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="/styles.css"/>
		<title>Jo's Jobs - <?=$title;?></title>
	</head>
	<body>
	<header>
		<?php require 'header.html.php'; ?>
	</header>

	<nav>
		<?=$nav;?>
	</nav>
	
	<img src="/images/randombanner.php"/>
    
    <main class="home">
		<?=$output;?>
	</main>

	<footer>
		<?php require 'footer.html.php'; ?>
	</footer>
</body>
</html>