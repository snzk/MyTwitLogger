<?PHP
//tw_GetUserInfo.php
//取得したユーザー情報をMySQLに保存する

	//保存するアイコン画像のファイル名を取得する
    $num = strrpos($iconurl,'/');
    $iconname = substr($iconurl,$num+1);
    //そのままのサイズで画像を取得
    $data = file_get_contents($iconurl);
    //userのscreen_nameで画像をサーバーに保存する
    file_put_contents('/home/users/2/boo.jp-snzk/web/PEAR/PEAR/twIcon/'.$iconname,$data);
    //ユーザー情報の登録
	//既に登録済みか調べる
	$sqlusr = "SELECT * FROM `tbl_twUser` WHERE userid = $userid";
	$rstusr = mysql_query($sqlusr,$con);
	$rstnum = mysql_num_rows($rstusr);
	echo "INSERT INTO `tbl_twUser`(`userid`, `screenname`, `name`, `icon`) VALUES ($userid,$sname,$name,'$iconname')<br />";
	If($rstnum == 0){
		//登録されていなければINSERT文を発行
		$sqlusr = "INSERT INTO `tbl_twUser`(`userid`, `screenname`, `name`, `icon`) VALUES ($userid,$sname,$name,'$iconname')";
		$rstusr = mysql_query($sqlusr,$con);
		If($sqlusr){
			echo $cname."の情報を登録"."<br />";
		}else{
			echo $cname."の情報登録失敗"."<br />";
		}
	}else{
	//既に登録済みの場合、アイコンが保存されている物と変わっていれば変更する。
/*
		$sqlusr = "SELECT * FROM `tbl_twUser` WHERE userid = $userid";
		$rstusr = mysql_query($sqlusr,$con);
		IF($rstusr){
			//
		}else{
			echo "tw_GetUserInfo、登録済みユーザーのSelectクエリ失敗";
		}
*/
	}
?>