<html>
	<head>
		<title>mission_3-5.php</title>
		<meta charset="utf-8">
	</head>
	<body>
		<?php
			if(isset($_POST["編集ボタン"])){//編集後の入力ボタンがおされているかどうか
				$filename="mission_3-5.txt";
				$fp=fopen($filename,"a");
				fclose($fp);
				$lines=file($filename);
				foreach($lines as $line){
					$words=explode("＜＞",$line);//配列$words[]に格納
					if($_POST['edit']==$words[0]){
						$pass=$words[4];
					}
				}
				//各項目に入力がされているかいないか
				if(empty($_POST['edit'])){//入力なし
					echo "編集番号を入力してください"."<br>";//入力してください的な表示
				}elseif($_POST['pass']!=$pass){
					echo "パスワードが間違っています"."<br>";
				}else{//入力あり（編集する投稿番号の内容を抜き取る）
					$edit_num=$_POST['edit'];
					//ーーー変数宣言ーーー
					$lines=file($filename);//既存テキスト
					
					//(編集番号と同じ投稿番号の時だけ内容を差し替え)ーーー
					foreach($lines as $line){//取得したテキストを一行ずつ処理
					$words=explode("＜＞",$line);//現在処理中の一行を分割
						if($_POST['edit']==$words[0]){//投稿番号と編集番号が一致したら
							$old_name=$words[1];
							$old_comment=$words[2];
						}
					}
					unset($lines);//ループで使った変数をクリア
				}
			}
		?>
		<form action="" method="post">
			<p><input type="hidden" name="en" placeholder=""value=
				<?php
					if(!empty($edit_num)){
						echo $edit_num;
					}
				?>></p>
			<p><input type="text" name="name" placeholder="名前"value =
				<?php
					if(!empty($old_name)){
						echo $old_name;
					}
				?>></p>
			<p><input type="text" name="comment" placeholder="コメント"value=
				<?php
					if(!empty($old_comment)){
						echo $old_comment;
					}
				?>></p>
			<p><input type="text" name="pass" placeholder="パスワード登録" value=""></p>
			<input type="submit" name="送信ボタン" value="送信">
		</form>
		<hr>
		<form action="" method="post">
			<p><input type="text" name="delete" placeholder="削除対象番号">
			<p><input type="text" name="pass" placeholder="パスワード" value=""></p>
			<input type  ="submit" name="削除ボタン" value="送信">
		</form>
		<hr>
		<form action="" method="post">
			<p><input type="text" name="edit" placeholder="編集対象番号">
			<p><input type="text" name="pass" placeholder="パスワード" value=""></p>
			<input type="submit"  name="編集ボタン" value="送信">
		</form>
		<?php
			$filename="mission_3-5.txt";
			$fp=fopen($filename,"a");
			fclose($fp);
			$lines=file($filename);
			if(isset($_POST['送信ボタン']) && !empty($_POST['en'])){
				echo "";//実際に編集するところ
				foreach($lines as $line){
					$words=explode("＜＞",$line);//配列$words[]に格納
					if($_POST['en']==$words[0]){
						$pass=$words[4];
					}
				}
				if(isset($_POST['en'])){
					if(!empty($pass)){
						if($_POST['pass']!=$pass){
							echo "パスワードが間違っています"."<br>";
						}else{
							$new_date=date(" Y/m/d H:i:s");
							//mission_3-5.txtの内容をコピー
							$lines=file($filename);
							//テキストファイルの中身をクリア
							$fp=fopen($filename,"w");
							fclose($fp);
							$fp=fopen($filename,"a");
							foreach($lines as $line){
								$words=explode("＜＞",$line);
								if($words[0]==$_POST['en']){
									fwrite($fp,$words[0]."＜＞".$_POST['name']."＜＞".$_POST['comment']."＜＞".$new_date."＜＞".$pass."\n");
								}else{
									fwrite($fp,$line);
								}
							}
							unset($line);
							unset($lines);
							unset($edit_num);
							fclose($fp);
						}
					}
				}
			}
		?>

		<?php
			//【入力フォームの処理】（省略）
			//編集後入力フォームからの送信がある時のみ処理
			/*入力フォームに入っているのが、新規入力じゃなくて、
			編集対象の 名前 コメント のとき　の処理*/
	
			$filename="mission_3-5.txt";
			$count="count_3-5.txt";
			if(isset($_POST['送信ボタン']) && empty($_POST['en'])){
				if(empty($_POST['name']) && empty($_POST['comment']) && empty($_POST['pass'])){
					echo "未記入欄があります"."<br>";
				}elseif(empty($_POST['name'])){
					echo "名前を入力してください"."<br>";
				}elseif(empty($_POST['comment'])){
					echo "コメントを入力してください"."<br>";
				}elseif(empty($_POST['pass'])){
					echo "パスワードを登録してください"."<br>";				
				}elseif(!empty($_POST['name']) && !empty($_POST['comment']) && empty($edit_num)){//追記機能
					//ファイルの行数をカウントする用のファイルを用意
					$fp2=fopen($count,"a");
					fclose($fp2);
					$fp2=fopen($count,"r");
					$num=fgets($fp2);//１行目を文字列として読み取る
					fclose($fp2);//ファイルを閉じる
					$num+=1;//１増やす
					//カウント用のファイルを空にして開く
					$fp2=fopen($count,"w");
					fwrite($fp2,$num);//新しい数値を書き込む
					fclose($fp2);

					//投稿内容を$Xに格納
					$date=date(" Y/m/d H:i:s");
					$X=$num."＜＞".$_POST['name']."＜＞".$_POST['comment']."＜＞".$date."＜＞".$_POST['pass']."＜＞"."\n";
	
					//追記モードでファイルオープン
					$fp=fopen($filename,"a");

					//mission__3-5.txtに$Xを書き込む
					fwrite($fp,$X);
	
					//ファイルを閉じる
					fclose($fp);
					unset($line);//$lineの変数解除
				}
			}
		?>

		<?php
			if(isset($_POST['削除ボタン'])){//削除処理
				$filename="mission_3-5.txt";
				$lines=file($filename);
				$fp=fopen($filename,"a");
				fclose($fp);
				foreach($lines as $line){
					$words=explode("＜＞",$line);//配列$words[]に格納
					if($_POST['delete']==$words[0]){
						$pass=$words[4];
					}
				}
				if(empty($_POST['delete']) && empty($_POST['pass'])){
					echo "未記入欄があります<br>";
				}elseif(empty($_POST['delete'])){
					echo "削除番号を入力してください<br>";
				}elseif(empty($_POST['pass'])){
					echo "パスワードを入力してください<br>";
				}else{
					if($_POST['pass']!=$pass){
						echo "パスワードが間違っています<br>";
					}else{
						//中身削除
						$fp=fopen($filename,"w");
						fclose($fp);
	
						//書き込み
						$fp=fopen($filename,"a");
						foreach($lines as $line){
							$words=explode("＜＞",$line);//配列$words[]に格納
							if($words[0] != $_POST['delete']){//削除対象番号と同じ番号の投稿は書き込まない
								fwrite($fp,$line);
							}
						}
						unset($line);
						fclose($fp);
						$fp = fopen($filename ,"a");
						fclose($fp);//0925テキストファイルがまだない時にエラーが出るのを防ぐ
					}
				}
			}
		?>

		<?php
			//記述する処理
			$filename="mission_3-5.txt";
			$fp=fopen($filename,"a");
			fclose($fp);
			$lines=file($filename);
			foreach($lines as $line){
				$words=explode("＜＞",$line);
				echo $words[0]." ".$words[1]." ".$words[2]." ".$words[3]." "."<br>";
			}
		?>
	</body>
</html>