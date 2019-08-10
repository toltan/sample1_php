<?php
session_start();
$name = $_SESSION["name"];
$email = $_SESSION["email"];
$select = $_SESSION["select"];
$check = $_SESSION["che"];
$textarea = $_SESSION["textarea"];
mb_language("japanese");
mb_internal_encoding("UTF-8");

require 'vendor/autoload.php';
$grid_email = new \SendGrid\Mail\Mail();
$grid_email->setFrom($email, $name);
$grid_email->setSubject("フォームから連絡があります。");
$grid_email->addTo("forest_comp@example.co.jp", "受信者");
$grid_email->addContent("text/plain", $select."\n".$textarea);
$send_grid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try{
	$response = $send_grid->send($grid_email);
	$h2Content = "送信完了";
	$message = "送信完了しました。<a href='index.php'>ホームに戻る</a>";
}catch(Exception $e){
	echo 'Caught exception: '. $e->getMessage() . "\n";
	$h2Content = "送信失敗";
	$message = "送信に失敗しました。お手数ですが再度送信してください。";
}
//herokuではmb_send_mailが使えない

/*
$to = "forest_comp@example.ne.jp";
$subject = "フォームから連絡があります。";
$send_mail = mb_send_mail($to,$subject,$textarea,$email);
if($send_mail){
	$h2Content = "送信完了";
	$message = "送信完了しました。<a href='index.php'>ホームに戻る</a>";
}else{
	$h2Content = "送信失敗";
	$message = "送信に失敗しました。お手数ですが再度送信してください。";
}*/
?>

<?php include("header.php") ?>

<main id="main">
	<sectin id="content">
		<h2><?php echo($h2Content); ?></h2>
		<ul id="breadcrumb">
			<li><a href="index.php">ホーム</a></li>
			<li><a href="contact.html">お問い合わせ</a></li>
			<li><?php echo($h2Content); ?></li>
		</ul>
	</sectin>
	<section id="contact">
		<p><?php echo($message);?></p>
		<table>
			<tr>
				<th>お名前</th>
				<td><?php echo($name);?></td>
			</tr>
			<tr>
				<th>メールアドレス</th>
				<td><?php echo($email);?></td>
			</tr>
			<tr>
				<th>お問い合わせの種類</th>
				<td><?php echo($select);?></td>
			</tr>
			<tr>
				<th>アンケート</th>
				<td><?php echo($check);?></td>
			</tr>
			<tr>
				<th>お問い合わせ内容</th>
				<td><?php echo($textarea);?></td>
			</tr>
		</table>
	</section>
</main>

<?php include("footer.php") ?>
