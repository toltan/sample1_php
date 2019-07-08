<?php include("header.php") ?>

<main id="main">
	<section id="content">
		<h2><?php
				if(isset($_POST["sub"])){
					echo("投稿が完了しました。");
				}else{
					echo("掲示板");
				}
			?>
		</h2>
		<ul id="breadcrumb">
			<li><a href="index.php">ホーム</a></li>
			<li>掲示板</li>
		</ul>
	</section>
	<section id="contact">

<?php include("image.php"); ?>

	</section>
</main>

<?php include("footer.php") ?>
