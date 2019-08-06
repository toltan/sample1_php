	<?php
		$home = "index.php";
		$csv = "./bbs/bbs.csv";
		$dat = "./dat/data.dat";
		$tmp_dir = "./tmp";
		$pass = hash("sha256",7974);
		date_default_timezone_set("Asia/Tokyo");
		encode();
		if($bbs_val["mode"] == "post"){add_data();}//データがあればデータナンバーが1プラスされる
		else if($bbs_val["mode"] == "admin"){admin();}
		call_bbs();

		function encode(){//encoding
			global $bbs_val;
			$encode = array();
			if(is_array($_GET)){
				$encode += $_GET;
			}
			if(is_array($_POST)){
				$encode += $_POST;
			}
			foreach($encode as $key => $val){
				if(!is_array($val)){
					$val_code = mb_detect_encoding($val);
					$val = mb_convert_encoding($val,"UTF-8",$val_code);
					$val = htmlentities($val,ENT_QUOTES,"UTF-8");
					$val = str_replace(",","&#44",$val);
					$val = str_replace("\r\n","_br_",$val);
					$val = str_replace("\r","_br_",$val);
					$val = str_replace("\n","_br_",$val);
					$bbs_val[$key] = $val;
				}
			}
		}

		function add_data(){//各ファイルにデータを格納
			global $bbs_val,$csv,$dat;
			if($bbs_val["title"] === ""){$bbs_val["title"] = "no title";}
			if($bbs_val["name"] === ""){$bbs_val["name"] = "no name";}
			$time = time();
			$ip = getenv("REMOTE_ADDR");
			$dat_open = fopen($dat,"r+") or die("データベース読み込みエラー");//dataNo
			flock($dat_open,LOCK_EX);
			$dat_no = fgets($dat_open);
			$dat_no++;
			rewind($dat_open);
			fwrite($dat_open,$dat_no);
			flock($dat_open,LOCK_UN);
			fclose($dat_open);

			$csv_arr = array();//csv追記
			$csv_open = fopen($csv,"r+") or die("データベース読み込みエラー");
			flock($csv_open,LOCK_EX);
			while($line = fgets($csv_open)){//$csv_arrに既存のデータを格納
				array_push($csv_arr,$line);
			}
			$new_arr = "{$dat_no},{$ip},{$bbs_val["title"]},{$bbs_val["name"]},{$bbs_val["comment"]},{$time}\n";
			$new_arr_enc = mb_detect_encoding($new_arr);
			$new_arr = mb_convert_encoding($new_arr,"UTF-8",$new_arr_enc);
			array_unshift($csv_arr,$new_arr);
			$csv_string = implode("",$csv_arr);
			rewind($csv_open);
			fwrite($csv_open,$csv_string);
			flock($csv_open,LOCK_UN);
			fclose($csv_open);
		}

		function call_bbs(){//掲示板表示
			global $bbs_val,$csv,$dat,$tmp_dir;
			$bbs_tmp_open1 = fopen("{$tmp_dir}/comment.tmp","r") or die("コメント取得エラー1");//投稿コメント
			$bbs_tmp_size1 = filesize("{$tmp_dir}/comment.tmp");
			$bbs_tmp1 = fread($bbs_tmp_open1,$bbs_tmp_size1);
			fclose($bbs_tmp_open1);
			$tmp1_str = "";
			$csv_data = fopen($csv,"r") or die;
			while($line = fgets($csv_data)){
				list($no,$i,$tit,$nam,$com,$tim) = explode(",",$line);
				$tim = date("Y/m/d　H:i:s",$tim);
				$com = str_replace("_br_","<br>",$com);
				$tmpl1 = $bbs_tmp1;
				$tmpl1 = str_replace("!no!",$no,$tmpl1);
				$tmpl1 = str_replace("!title!",$tit,$tmpl1);
				$tmpl1 = str_replace("!name!",$nam,$tmpl1);
				$tmpl1 = str_replace("!time!",$tim,$tmpl1);
				$tmpl1 = str_replace("!comment!",$com,$tmpl1);
				$tmpl1_str .= $tmpl1;
			}
			fclose($csv);
			$bbs_tmp_open2 = fopen("{$tmp_dir}/bbs.tmp","r") or die("コメント取得エラー2");//掲示板テンプレート
			$bbs_tmp_size2 = filesize("{$tmp_dir}/bbs.tmp");
			$bbs_tmp2 = fread($bbs_tmp_open2,$bbs_tmp_size2);
			$bbs_tmp2 = str_replace("!message!",$tmpl1_str,$bbs_tmp2);
			fclose($bbs_tmp_open2);
			echo($bbs_tmp2);
		}

		function error($err){
			global $tmp_dir;
			echo("<p style=\"color:red;\">$err</p>");
			$pass_open = fopen("$tmp_dir/pass.tmp","r") or die(ファイルの読み込みに失敗しました。);
			$pass_size = filesize("$tmp_dir/pass.tmp");
			$pass_read = fread($pass_open,$pass_size);
			fclose($pass_open);
			echo($pass_read);
			exit;
		}

		function admin(){
			global $bbs_val,$tmp_dir,$csv,$dat;
			check_pass();
			echo("<p>認証成功</p>");
			//記事削除
			if(isset($_POST["delete"])){
				foreach($_POST as $key => $val){
					if($key === "delete"){
						$del = $val;
					}
				}
				$data_undel = array();
				$liner = array();
				$csv_open = fopen($csv,"r+") or die("ファイルの呼び出しに失敗しました。");
				flock($csv_open,LOCK_EX);
				$i = 0;
				$q = 0;
				$c = count($del);
				while($line = fgets($csv_open)){
					list($no,$ip,$title,$name,$comment,$date) = explode(",",$line);
					$liner = explode(",",$line);
					if(isset($del) && $del[$i] === $no){
						$i++;
						$c--;
						continue;//削除対象は処理を飛ばす
					}
					$liner[0] -= $c;
					if($q === 0){
						$q = $liner[0];
					}
					$line = implode(",",$liner);
					array_push($data_undel,$line);
				}
				$data_undel = implode("",$data_undel);
				rewind($csv_open);
				fwrite($csv_open,$data_undel);
				ftruncate($csv_open,ftell($csv_open));
				flock($csv_open,LOCK_UN);
				fclose($csv_open);

				$data_open = fopen("$dat","r+") or die("ファイルの呼び出しに失敗しました。");
				rewind($data_open);
				fwrite($data_open,$q);
				ftruncate($data_open,ftell($data_open));
				fclose($data_open);
			}

			$csv_str = "";
			$csv_open = fopen($csv,"r") or die("ファイルの呼び出しに失敗しました。");
			while($line = fgets($csv_open)){
				list($no,$ip,$title,$name,$comment,$date) = explode(",",$line);
				$tbody = <<< per
				<tr>
					<td><label><input type="checkbox" name="delete[]" value="$no">削除</label></td>
					<td>!no!</td>
					<td>!name!</td>
					<td>!ip!</td>
					<td>!title!</td>
					<td>!day!</td>
					<td>!comment!</td>
				</tr>
per;
				$date = date("Y/m/d H:i:s",$date);
				$tbody_str = $tbody;
				$tbody_str = str_replace("!no!",$no,$tbody_str);
				$tbody_str = str_replace("!name!",$name,$tbody_str);
				$tbody_str = str_replace("!ip!",$ip,$tbody_str);
				$tbody_str = str_replace("!title!",$title,$tbody_str);
				$tbody_str = str_replace("!day!",$date,$tbody_str);
				$tbody_str = str_replace("!comment!",$comment,$tbody_str);
				$csv_str .= $tbody_str;
			}
			$temp_open = fopen("$tmp_dir/admin.tmp","r") or die("ファイルの呼び出しに失敗しました。");
			$temp_size = filesize("$tmp_dir/admin.tmp");
			$temp_read = fread($temp_open,$temp_size);
			fclose($temp_open);
			$temp_read = str_replace("!tbody!",$csv_str,$temp_read);
			$temp_read = str_replace("!pass!","7974",$temp_read); //
			echo($temp_read);
			exit;
		}
		function check_pass(){
			global $bbs_val,$tmp_dir,$pass;
			if(!$bbs_val["pass"]){
				$pass_open = fopen("$tmp_dir/pass.tmp","r") or die(ファイルの読み込みに失敗しました。);
				$pass_size = filesize("$tmp_dir/pass.tmp");
				$pass_read = fread($pass_open,$pass_size);
				fclose($pass_open);
				echo($pass_read);
				exit;
			}
			elseif(hash("sha256",$bbs_val["pass"]) != $pass){
				error("＊パスワードが正しくありません。");
			}
		}
	?>
