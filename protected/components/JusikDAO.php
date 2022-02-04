<?
class JusikDAO
{	
	function favoriteJusikCount($userid,$stock_code){
		$strSQL = "select count(*) from favorites where userid=:userid and stock_code=:stock_code";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($strSQL);
		$command->bindParam(":userid", $userid, PDO::PARAM_STR);
		$command->bindParam(":stock_code", $stock_code, PDO::PARAM_INT);
		$result = $command->queryRow();
		return $result;
	}
	function favoriteJusik($userid){
		$strSQL = "select stock_code from favorites where userid=:userid";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($strSQL);
		$command->bindParam(":userid", $userid, PDO::PARAM_STR);
		$result = $command->queryAll();
		return $result;
	}
	function favoriteAdd($arr){
		$model = new Favorites;
		
		if(isset($arr))
		{
			$model->attributes=$arr;
			if($model->save()){
			return "saveSuccess";
			}else{
			return "saveFail";
			}
				
		}
	}
	function favoriteDelete($arr){
		$strSQL = "delete from favorites where userid=:userid and stock_code=:stock_code";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($strSQL);
		$command->bindParam(":userid", $arr['userid'], PDO::PARAM_STR);
		$command->bindParam(":stock_code", $arr['stock_code'], PDO::PARAM_INT);
		$result = $command->execute();
		return $result;
	}




































}
