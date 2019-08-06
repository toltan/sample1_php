<?php
	include("image_class.php");
	session_start();

	if(isset($_POST["logout"])){
		$_SESSION = array();
		session_destroy();
	}else if(isset($_POST["pass"])){
		$pass = htmlentities($_POST["pass"],ENT_QUOTES,"utf-8");
		$pass = hash("sha256",$pass);
			if($pass == hash("sha256",7974)){
				$_SESSION["pass"] = $pass;
			}
	}
	$img = new Image;
	$err = "";
	if($_SESSION["pass"] == hash("sha256",7974)){//
		if($_FILES['file']['name']){
			$err = $img->type_check();
			if(empty($err)){
				$in = $img->encode();
				$img->image_change($in);
			}
		}elseif($_POST["new_name"] || $_POST["new_text"]){
				$in = $img->encode();
				$err = $img->new_description($in);
		}
		$img->img_call();
		$tmp = $img->replace_src();
	}else{
		$err_mes = "<p>ログインパスワードを入力してください。</p>";
		if($_POST["mode"] == "image_change"){$err_mes = "<p style=\"color:red;\">＊パスワードが正しくありません。</p><p>ログインパスワードを入力してください。</p>";}
		$tmp = $img->replace_pass();
		$tmp = str_replace("!message!",$err_mes,$tmp);
	}

	if($_FILES["file"]["name"] && empty($err)){
		$h2 = "変更しました。";
	}elseif($err == "新商品項目を書き換えました。"){
		$h2 = $err;
	}else if(empty($err)){
		$h2 = "画像変更画面";
	}else{
		$h2 = $err;
	}
?>

<?php include("header.php") ?>

<main id="main">
	<section id="content">
		<h2><?=$h2?></h2>
		<ul id="breadcrumb">
			<li><a href="index.php">ホーム</a></li>
			<li>画像変更</li>
		</ul>
	</section>
<?php echo($tmp);?>
</main>

<?php include("footer.php") ?>
