<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{	
		$siteDAO = new SiteDAO();
		$lotto_list=$siteDAO->Lotto_list();
		$last_lotto=$siteDAO->Lotto_list(1);
		$lotto_data="";
		$last_data="";
		$color_Arr=Array('num1'=>'#fbc400','num2'=>'#fbc400','num3'=>'#69c8f2','num4'=>'#69c8f2','num5'=>'#69c8f2','num6'=>'#b0d840');
		
		foreach($lotto_list as $rows){
			$lotto_data.="<tr>";
			foreach($rows as $row){
			$lotto_data.="<td>".$row."</td>";
			}
			$lotto_data.="</tr>";
		}
		foreach($last_lotto as $i=>$rows){
			if($i=="no"){
			$last_data.=$rows." 회차<br/><br/>";
			}else{
			if($rows>9){
				$last_data.="<span width='200px' height='200px' style='color:white;padding:10px;border-radius:50%;background:$color_Arr[$i]'>$rows</span>"." ";
				}else{
				$last_data.="<span width='200px' height='200px' style='color:white;padding:10px;border-radius:50%;background:$color_Arr[$i]'>0$rows</span>"." ";
				}
			}
		}
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index',
		array(	'lotto_data'=>$lotto_data,
				'last_data'=>$last_data
				)
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */

	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['test'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	public function actionLogin_ok()
	{	
		$siteDAO = new SiteDAO();
		if($siteDAO->Login_ok($_POST)){
			$this->redirect('/');
		}
	}
	public function actionRegister()
	{	
		// display the login form
		$this->render('register');
	}
	public function actionRegister_ok()
	{	
		$siteDAO = new SiteDAO();
		
		if($siteDAO->Register_ok($_POST)){
			$this->redirect(array('login'));
		}
		
	}
	public function actionIdRepeatChk(){
		$siteDAO = new SiteDAO();
		
		echo $siteDAO->IdRepeatChk($_POST['userid']);
		
	}
	public function actionForgot_password()
	{
	
		// display the login form
		$this->render('forgot_password');
	}
	public function actionMap(){
		
		$this->render('map',"");
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}