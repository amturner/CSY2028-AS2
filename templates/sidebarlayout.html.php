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
		<?php require 'nav.html.php'; ?>
	</nav>
	
	<img src="/images/randombanner.php"/>
	
	<main class="sidebar">
		<section class="left">
			<?php 
				if (!empty(explode('/', $_SERVER['REQUEST_URI'])[1]))
					$page = explode('/', $_SERVER['REQUEST_URI'])[1];
				else
					$page = '';
			?>
			<?php if ($page == 'admin'): ?>
				<?php require 'admin/adminnav.html.php'; ?>
			<?php else: ?>
				<?php require 'main/jobnav.html.php'; ?>
			<?php endif; ?>
		</section>

		<section class="right">
			<?=$output;?>
		</section>
	</main>

	<footer>
		<?php require 'footer.html.php'; ?>
	</footer>
</body>
</html>