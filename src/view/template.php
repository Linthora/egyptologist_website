<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?php echo $this->title ?></title>
		<meta charset="UTF-8">
		<?php 
			// for some reason I had to add the following line to make the css work always
			echo '<style>';
			include 'skin/BasicScreen.css';
			echo '</style>';
		?>
	</head>
	<body>
		<nav class="menu">
			<ul>
				<?php
					foreach($this->menu as $name => $url) {
						echo '<li><a href="' . $url . '"><div>' 	. $name . '</div></a></li>';
					}
				?>
			</ul>
		</nav>
		<div class="feedback">
			<?php
				if($this->feedback != "") {
					echo $this->feedback;
				}
			?>
		</div>
		<main>
			<h1><?php echo $this->title; ?></h1>
			<div class="content">
				<?php echo $this->content; ?>
			</div>
		</main>
	</body>
</html>