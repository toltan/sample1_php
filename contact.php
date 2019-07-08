<?php
session_start();
$name = htmlentities($_POST["name"],ENT_QUOTES,"utf-8");
$email = htmlentities($_POST["email"],ENT_QUOTES,"utf-8");
$select = $_POST["select"];
$check = $_POST["check"];
$box = implode("、",$check);
$textarea = htmlentities($_POST["textarea"],ENT_QUOTES,"utf-8");
$textarea = str_replace("\r\n","<br>",$textarea);
$textarea = str_replace("\r","<br>",$textarea);
$textarea = str_replace("\n","<br>",$textarea);
$_SESSION["name"] = $name;
$_SESSION["email"] =$email;
$_SESSION["select"] =$select;
$_SESSION["che"] =$box;
$_SESSION["textarea"] =$textarea;
?>

<?php include("header.php") ?>

<main id="main">
	<sectin id="content">
		<h2>お問い合わせ内容の確認</h2>
		<ul id="breadcrumb">
			<li><a href="index.php">ホーム</a></li>
			<li><a href="contact.html">お問い合わせ</a></li>
			<li>お問い合わせ内容の確認</li>
		</ul>
	</sectin>
	<section id="contact">
		<p>入力した内容が下記の通りでよろしければ、送信ボタンを押してください。</p>
		<form action="complete.php" method="post">
<?php
echo <<< _ECHO_
			<table>
				<tr>
					<th>お名前</th>
					<td>$name</td>
				</tr>
				<tr>
					<th>メールアドレス</th>
					<td>$email</td>
				</tr>
				<tr>
					<th>お問い合わせの種類</th>
					<td>$select</td>
				</tr>
				<tr>
					<th>アンケート</th>
					<td>{$box}</td>
				</tr>
				<tr>
					<th>お問い合わせ内容</th>
					<td>$textarea</td>
				</tr>
				<tr>
					<th colspan="2">
						<input type="submit" value="送信">
						<input type="button" value="戻る" onclick="history.back()"
					</th>
				</tr>
			</table>
_ECHO_;
?>
		</form>
	</section>
</main>

<?php include("footer.php") ?>
