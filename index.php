<?php
require("image_class.php");
$img = new Image;
$img->img_call();
$src = $img->image_src;
$name = $img->new_name;
$text= $img->new_text;
$text = str_replace("_br_","<br>",$text);
?>

<?php include("header.php") ?>

<main>
	<ul id="main_photo">
		<li id="catchCopy">心安らぐ<br>大自然の中<br>美味しいケーキと<br>コーヒーで<br>おもてなしします</li>
		<li>
			<ul id="slide_gallery">
				<li><img src="images/main1.jpg" alt="メイン1"></li>
				<li><img src="images/main2.jpg" alt="メイン2"></li>
				<li><img src="images/main3.jpg" alt="メイン3"></li>
			</ul>
		</li>
		<li id="plus_minus">
			<ul id="pl"></ul>
		</li>
		<li id="prev"><img src="images/prev.png" alt="prev"></li>
		<li id="next"><img src="images/next.png" alt="next"></li>
	</ul>
	<section id="main_item">
		<ul>
			<li><img src="images/main_cake.png" alt="定番商品1"></li>
			<li><img src="images/main_coffee.png" alt="定番商品2"></li>
		</ul>
	</section>
	<ul id="new_item">
		<li><img src="images/new_item_frame.png" alt="新商品"></li>
		<li id="description">
			<p><?=$name?></p>
			<p><?=$text?></p>
		</li>
		<li id="change_image"><img src="<?=$src?>"></li>
	</ul>
	<p id="messa">ご来店、心よりお待ちしております</p>
</main>

<?php include("footer.php") ?>
