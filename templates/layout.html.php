<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="/styles.css"/>
		<title>Jo's Jobs - <?=$title;?></title>
	</head>
	<body>
	<header>
		<section>
			<aside>
				<h3>Office Hours:</h3>
				<p>Mon-Fri: 09:00-17:30</p>
				<p>Sat: 09:00-17:00</p>
				<p>Sun: Closed</p>
			</aside>
			<h1>Jo's Jobs</h1>

		</section>
	</header>
    <?=$nav;?>
	
	<img src="/images/randombanner.php"/>
    
    <?=$output;?>

	<footer>
		&copy; Jo's Jobs <?=date('Y');?>
	</footer>
</body>
</html>