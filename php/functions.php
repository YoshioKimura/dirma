<?php
//セクションチェック
function sessChk(){
	if(!$_SESSION['sess_id']){
		header("Location: index.php");
		exit;
	}
}

//DB接続
function connectDb() {
	try {
		return new PDO(DSN, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	} catch (PDOException $e) {
		exit('データベースに接続できませんでした。'.$e->getMessage());
	}
}

//XSS対策
function escape($s) {
	return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

//ユーザーか否か
function isSignin()
{
	if (!isset($_SESSION['user_id'])) {
		// 変数に値がセットされていない場合
		return false;
	} else {
		return true;
	}
}

//ユーザーID取得
function getUserId($email, $password, $db) {
	$sql = "SELECT id, lpw FROM user_table WHERE email = :email";
	$statement = $db->prepare($sql);
	$statement->bindValue(':email', $email, PDO::PARAM_STR);
	$statement->execute();
	$row = $statement->fetch();
	if (password_verify($password, $row['lpw'])) {
		return $row['id'];
	} else {
		return false;
	}
}

?>
