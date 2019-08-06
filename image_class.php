<?php
class Image{
		public $img_dir = "./images/";
		public $image_name = "";
		public $image_tmp_name = "";
		public $image_type = "";
		public $image_src = "";
		public $image_data = "./dat/image_src.dat";
		public $image_tmp = "./tmp/image_change.tmp";
		public $image_pass = "./tmp/img_pass.tmp";
		public $new_name = "";
		public $new_text = "";
		
		public function img_call(){//現在の設定を取得
			$img_data_open = fopen("$this->image_data","r") or die("画像の読み込みに失敗しました。");
			$img_data_get = fgets($img_data_open);
			list($src,$name,$text) = explode(",",$img_data_get);
			$this->image_src = $src;
			$this->new_name = $name;
			$this->new_text = $text;
			fclose($img_data_open);
			$this->image_name = $_FILES["file"]["name"];
			$this->image_tmp_name = $_FILES["file"]["tmp_name"];
			$this->image_type = $_FILES["file"]["type"];
		}
		public function image_change($in){//画像書き換え
			$this->image_name = $_FILES["file"]["name"];
			$this->image_tmp_name = $_FILES["file"]["tmp_name"];
			if(move_uploaded_file($this->image_tmp_name,$this->img_dir.$this->image_name)){
				$img_data_open = fopen("$this->image_data","r+") or die("画像の読み込みに失敗しました。");
				flock($img_data_open,LOCK_EX);
				$new_image = $this->img_dir.$this->image_name;
				$new_image .= ",".$in["new_name"].",".$in["new_text"];
				rewind($img_data_open);
				fwrite($img_data_open,$new_image);
				ftruncate($img_data_open,ftell($img_data_open));
				flock($img_data_open,LOCK_UN);
				fclose($img_data_open);	
			}else{
				die("ファイルの読み込みに失敗しました。");
			}
		}
		public function new_description($in){
				$err = "jpg,gif,png以外のファイルはアップロードできません。";
				$img_data_open = fopen("$this->image_data","r+") or die("画像の読み込みに失敗しました。");
				flock($img_data_open,LOCK_EX);
				$img_get = fgets($img_data_open);
				list($src,$name,$text) = explode(",",$img_get);
				if($name != $in["new_name"] || $text != $in["new_text"]){
					$new_image = "$src,".$in["new_name"].",".$in["new_text"];
					//echo("$new_image");
					rewind($img_data_open);
					fwrite($img_data_open,$new_image);
					ftruncate($img_data_open,ftell($img_data_open));
					$err = "新商品項目を書き換えました。";
				}
				flock($img_data_open,LOCK_UN);
				fclose($img_data_open);	
				return $err;
		}
		public function type_check(){
			$err_str ="";
			if($_FILES["file"]["type"] == "image/jpg" || $_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg"){
				return false;
			}else if($_FILES["file"]["type"] == "image/gif"){
				return false;
			}else if($_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/x-png"){
				return false;
			}else{
				$err_str = "jpg,gif,png以外のファイルはアップロードできません。";
				return $err_str;
			}
		}
		public function replace_src(){
			$tmp_class = $this->image_tmp;
			$tmp_open = fopen($tmp_class,"r") or die("画像の読み込みに失敗しました。");
			$tmp_size = filesize($tmp_class);
			$tmp = fread($tmp_open,$tmp_size);
			fclose($tmp_open);
			$tmp = str_replace("!image_name!",basename($this->image_src),$tmp);
			$tmp = str_replace("!img_src!",$this->image_src,$tmp);
			$tmp = str_replace("!new_name!",$this->new_name,$tmp);
			$tmp = str_replace("!new_text!",$this->new_text,$tmp);
			$tmp = str_replace("_br_","\r\n",$tmp);
			return $tmp;
		}
		public function replace_pass(){
			$tmp_class = $this->image_pass;
			$tmp_open = fopen($tmp_class,"r") or die("画像の読み込みに失敗しました。");
			$tmp_size = filesize($tmp_class);
			$tmp = fread($tmp_open,$tmp_size);
			fclose($tmp_open);
			return $tmp;
		}
		public function encode(){
			foreach($_POST as $key => $val){
				$enc = mb_detect_encoding($val);
				$val = mb_convert_encoding($val,"UTF-8",$enc);
				$val = htmlentities($val,ENT_QUOTES,"UTF-8");
				$val = str_replace("\r\n","_br_",$val);
				$val = str_replace("\r","_br_",$val);
				$val = str_replace("\n","_br_",$val);
				$in[$key] = $val;
			}
			return $in;
		}
	}
?>