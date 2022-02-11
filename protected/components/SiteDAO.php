<?

class SiteDAO
{
	public function Lotto_list($chk=0){
		$strSQL = "select * from lottos ";
		if($chk==1){
		$strSQL.="order by no desc limit 0,1";
		}
		$connection = Yii::app()->db;
		$command = $connection->createCommand($strSQL);
		if($chk==0){
			$result = $command->queryAll();
		}else{
			$result = $command->queryRow();
		}
		return $result;
	}
	public function IdRepeatChk($userid){

		$strSQL = "select count(*) total from users where userid = :userid";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($strSQL);
		$command->bindParam(":userid", $userid, PDO::PARAM_STR);
		$result = $command->queryRow();
		
		return $result["total"];
	}
	public function Register_ok($user_Arr){
		$model=new Users;
		if($user_Arr['password'] != $user_Arr['Rpassword']){
			return false;
			exit;
		}else{
			unset($user_Arr['Rpassword']);
		}
		$user_Arr['password']=openssl_encrypt($user_Arr['password'], 'aes-256-cbc', Yii::app()->params['aesKey'], false, str_repeat(chr(0), 16));
		//openssl_decrypt($a, 'aes-256-cbc', Yii::app()->params['aesKey'], false, str_repeat(chr(0), 16));
		if(isset($user_Arr))
		{	
			
			$model->attributes=$user_Arr;
			if($model->save())
				Yii::app()->db->close();
				return true;
		}
	
	
	
	}
	public function Login_ok($user_Arr){
		$user_name = "";
		if($this->IdRepeatChk($user_Arr['userid'])==0){
			echo "<script>alert('찾을수 없는 아이디 입니다.');history.back();</script>";
			exit;
		}else{
			$strSQL = "select * from users where userid=:userid";
			$connection = Yii::app()->db;
			$command = $connection->createCommand($strSQL);
			$command->bindParam(":userid",$user_Arr['userid'], PDO::PARAM_STR);
			$result = $command->queryRow();
			if(openssl_decrypt($result['password'], 'aes-256-cbc', Yii::app()->params['aesKey'], false, str_repeat(chr(0), 16)) != $user_Arr['password']){
				echo "<script>alert('비밀번호가 잘못되었습니다.');history.back();</script>";
				exit;
			}
			$user_name=$result['name'];
		}
		session_start();
		$access_ip = $_SERVER["REMOTE_ADDR"];
		date_default_timezone_set('Asia/Seoul');
		$date = date('Y-m-d H:i:s');
		$strSQL = "insert into login_log(access_ip,userid,access_time) values(:access_ip,:userid,:date)";
		$command = $connection->createCommand($strSQL);
		$command->bindParam(":access_ip",$access_ip, PDO::PARAM_STR);
		$command->bindParam(":userid",$user_Arr['userid'], PDO::PARAM_STR);
		$command->bindParam(":date",$date, PDO::PARAM_STR);
		$result = $command->queryRow();
		$_SESSION['userid'] = $user_Arr['userid'];
		$_SESSION['username'] = $user_name;
		return true;
	}











}


?>