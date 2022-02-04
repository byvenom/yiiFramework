<?php

class JusikController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionFavoriteDelete()
	{
		$JusikDAO = new JusikDAO();
		$result = $JusikDAO->favoriteDelete($_POST);
		if($result){
			echo "success";
		}else{
			echo "fail";
		}
	}
	public function actionFavoriteAdd(){
		$JusikDAO = new JusikDAO();
		$result=$JusikDAO->favoriteJusikCount($_POST['userid'],$_POST['stock_code']);
		
		if($result['count(*)']>0){
		echo "fail";
		return;
		}else{
			$result2=$JusikDAO->favoriteAdd($_POST);
			echo $result2;
			return;
		}
	}
	public function actionSearch(){
		$JusikDAO = new JusikDAO();
	
		$data = "";
		$xml = new SimpleXMLElement("CORPCODE.xml",0,true);
		$data_arr= $xml->list;
		$chk = false;
		
		if(!isset($_POST['userid'])){
		$userid = "";
		}else{
		$userid = $_POST['userid'];
		}
		if(isset($_POST['str'])){
			$str = "";
		}else{
			$str = $_POST['str'];
		}
			
		foreach($data_arr as $row){
		if(strlen($row->stock_code) != 1 && isset($_POST['str'])){
			if(stripos($row->corp_name,$_POST['str']) !==false){
			$chk = true;
			$result=$JusikDAO->favoriteJusikCount($userid,$row->stock_code);
			$data .= "<tr class='h5 mt-12'>";
			$data .= "<td><b style='color:black'>" . $row->corp_name . "</b></td>";
			$data .= "<td>" . $row->corp_code . "</td>";
			$data .= "<td>" . $row->stock_code . "</td>";
			if($result['count(*)']>0){
			$data .= "<td><a href='#' class='btn btn-danger btn-circle' id='delete' onclick='favoriteDelete(\"".$userid."\",\"".$row->stock_code."\")'><i class='fas fa-trash'></i></a></td>";
			}else{
			$data .= "<td><a href='#' class='btn btn-primary btn-circle' id='add' onclick='favoriteAdd(\"".$userid."\",\"".$row->corp_code."\",\"".$row->stock_code."\")'><i class='fa fa-cart-plus' aria-hidden='true'></i></a></td>";
			}
			$data .="</tr>";
					}
				}
			}
		if(!isset($_POST['str'])){
		$str = "";
		}
			$params= array(
				'data' => $data,
				'chk' => $chk,
				'str' => $str
			);
		$this->render('search',$params);
	}
	public function actionJusikData()
	{
		$stock_Arr = array();
		$today_Info_Arr = array();
		$url_list = array();
		$util = new Util();
		$html = new simple_html_dom();
		$mh = curl_multi_init();
		$JusikDAO = new JusikDAO();
		$result = $JusikDAO->favoriteJusik($_POST['userid']);
		foreach ($result as $i=>$row){

			$url_list[$i]="https://finance.naver.com/item/main.nhn?code=$row[stock_code]";
			$stock_Arr[$i] = $row['stock_code'];

		}
		$html_parser_urllist = $util->MultiHTMLParser($url_list);
		foreach($html_parser_urllist as $indexs=>$strs){
		$str_content = $strs['content'];

		$html->load($str_content);
		$str_content = htmlspecialchars($str_content);
		$span_blind = $html->find("span.blind");
		$value = "";
		$value2 = 0;
		$value3 = 0;
		$sum = "";
		$sum2 = "";
		$sum3 = "";
		$upDownCheck="";
		foreach($span_blind as $index=>$c){
				if($index==12){
				$value=preg_replace("/[^0-9]*/s", "", $c);
				}
		}
		$p_no_exday = $html->find("p.no_exday");

		foreach($p_no_exday as $b=>$c){
			$trim_p_no_exday = explode(" ",$c);
			
			foreach($trim_p_no_exday as $dv=>$d){
				$value2 = preg_replace("/[^0-9]*/s", "", $trim_p_no_exday[19]);
				
				
				if(strpos($d,'상승') !== false){
					$upDownCheck = "up";
				}
				if(strpos($d,'하락') !== false){
					$upDownCheck = "down";
				}
				preg_match_all("/[0-9]+/",$trim_p_no_exday[38],$t38);
				preg_match_all("/[0-9]+/",$trim_p_no_exday[39],$t39);
				preg_match_all("/[0-9]+/",$trim_p_no_exday[41],$t41);
				preg_match_all("/[0-9]+/",$trim_p_no_exday[42],$t42);
				
				if(count($t38[0])>1){
						$value3 = explode('<',explode(">",$trim_p_no_exday[38])[1])[0];
					
				}else if(count($t39[0])>1){
					
						$value3 = explode('<',explode(">",$trim_p_no_exday[39])[1])[0];
				}
				else if(count($t41[0])>1){
						$value3 = explode('<',explode(">",$trim_p_no_exday[41])[1])[0];
					
				}
				else if(count($t42[0])>1){
						$value3 = explode('<',explode(">",$trim_p_no_exday[42])[1])[0];
						
				}
			
			}
			
				
			
		}
		$value3.="%";

		$value = number_format($value);
		$color = "";
		if($upDownCheck =="up"){
			$value2 = "+".$value2;
			$value3 = "+".$value3;
			$color="#e74a3b";
		}else if($upDownCheck =="down"){
			$value2 = "-".$value2;
			$value3 = "-".$value3;
			$color="#4e73df";
		}else{
			$color="#858796";
		}
		$em_market_sum = $html->find("em#_market_sum");
		foreach($em_market_sum as $b)
		{
			$sum = preg_replace("/[^0-9]*/s", "", $b);
		}
		if($sum >10000){
			
			$sum2 = substr($sum,-4);
			$sum3 = substr($sum,0,-4);
			$sum = number_format($sum3)."조".number_format($sum2)."억원";
		}else{
			$sum = number_format($sum)."억원";
		}
		$div_tab_con1 = $html->find("div.tab_con1");

		foreach($div_tab_con1 as $b)
		{
			$val4 = strip_tags($b);
			
		}
		$val4 = str_replace(array('& nbsp;','　',' ',"\t","\n","\r","\0","\x0B"),'',$val4);

		$div_wrap_company = $html->find("div.wrap_company");
		$value0 = "";
		foreach($div_wrap_company as $b)
		{
			$value0 = strip_tags($b);
		  
			
		}
		$value0=explode(' ',$value0);
		$value0=str_replace("\t","",$value0);
		$value0=$value0[1];
		array_push($today_Info_Arr,array($value0,$value,$value2,str_replace('%','',$value3),$sum,$color,$stock_Arr[$indexs]));
		
		}
		//echo(json_encode($chartArr,JSON_UNESCAPED_UNICODE));
		echo(json_encode($today_Info_Arr,JSON_UNESCAPED_UNICODE));
		
		
	}
	public function actionJusikDataTest()
	{
		$stock_Arr = array();
		$today_Info_Arr = array();
		$url_list = array();
		$util = new Util();
		$html = new simple_html_dom();
		$mh = curl_multi_init();
		$JusikDAO = new JusikDAO();
		$result = $JusikDAO->favoriteJusik('admin');
		foreach ($result as $i=>$row){

			$url_list[$i]="https://finance.naver.com/item/main.nhn?code=$row[stock_code]";
			$stock_Arr[$i] = $row['stock_code'];

		}
		$html_parser_urllist = $util->MultiHTMLParser($url_list);
		foreach($html_parser_urllist as $indexs=>$strs){
		$str_content = $strs['content'];

		$html->load($str_content);
		$str_content = htmlspecialchars($str_content);
		$span_blind = $html->find("span.blind");
		$value = "";
		$value2 = 0;
		$value3 = 0;
		$sum = "";
		$sum2 = "";
		$sum3 = "";
		$upDownCheck="";
		foreach($span_blind as $index=>$c){
				if($index==12){
				$value=preg_replace("/[^0-9]*/s", "", $c);
				}
		}
		$p_no_exday = $html->find("p.no_exday");

		foreach($p_no_exday as $b=>$c){
			$trim_p_no_exday = explode(" ",$c);
			
			foreach($trim_p_no_exday as $dv=>$d){
				$value2 = preg_replace("/[^0-9]*/s", "", $trim_p_no_exday[19]);
				
				
				if(strpos($d,'상승') !== false){
					$upDownCheck = "up";
				}
				if(strpos($d,'하락') !== false){
					$upDownCheck = "down";
				}
			
				preg_match_all("/[0-9]+/",$trim_p_no_exday[38],$t38);
				preg_match_all("/[0-9]+/",$trim_p_no_exday[39],$t39);
				preg_match_all("/[0-9]+/",$trim_p_no_exday[41],$t41);
				preg_match_all("/[0-9]+/",$trim_p_no_exday[42],$t42);
				
				if(count($t38[0])>1){
						$value3 = explode('<',explode(">",$trim_p_no_exday[38])[1])[0];
					
				}else if(count($t39[0])>1){
					
						$value3 = explode('<',explode(">",$trim_p_no_exday[39])[1])[0];
				}
				else if(count($t41[0])>1){
						$value3 = explode('<',explode(">",$trim_p_no_exday[41])[1])[0];
					
				}
				else if(count($t42[0])>1){
						$value3 = explode('<',explode(">",$trim_p_no_exday[42])[1])[0];
						
				}
			
				
			
			}
			
				
			
		}
		$value3.="%";

		$value = number_format($value);
		$color = "";
		if($upDownCheck =="up"){
			$value2 = "+".$value2;
			$value3 = "+".$value3;
			$color="#e74a3b";
		}else if($upDownCheck =="down"){
			$value2 = "-".$value2;
			$value3 = "-".$value3;
			$color="#4e73df";
		}else{
			$color="#858796";
		}
		$em_market_sum = $html->find("em#_market_sum");
		foreach($em_market_sum as $b)
		{
			$sum = preg_replace("/[^0-9]*/s", "", $b);
		}
		if($sum >10000){
			
			$sum2 = substr($sum,-4);
			$sum3 = substr($sum,0,-4);
			$sum = number_format($sum3)."조".number_format($sum2)."억원";
		}else{
			$sum = number_format($sum)."억원";
		}
		$div_tab_con1 = $html->find("div.tab_con1");

		foreach($div_tab_con1 as $b)
		{
			$val4 = strip_tags($b);
			
		}
		$val4 = str_replace(array('& nbsp;','　',' ',"\t","\n","\r","\0","\x0B"),'',$val4);

		$div_wrap_company = $html->find("div.wrap_company");
		$value0 = "";
		foreach($div_wrap_company as $b)
		{
			$value0 = strip_tags($b);
		  
			
		}
		$value0=explode(' ',$value0);
		$value0=str_replace("\t","",$value0);
		$value0=$value0[1];
		array_push($today_Info_Arr,array($value0,$value,$value2,str_replace('%','',$value3),$sum,$color,$stock_Arr[$indexs]));
		
		}
		//echo(json_encode($chartArr,JSON_UNESCAPED_UNICODE));
		echo(json_encode($today_Info_Arr,JSON_UNESCAPED_UNICODE));
		
		
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}