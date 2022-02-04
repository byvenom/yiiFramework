<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css?var=3" rel="stylesheet">

</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	$(function(){
		$("#exampleUserId").on("change keyup paste" , function(){
		var userid = $("#exampleUserId").val();
		$.ajax({
			type:"POST",
			url:"/site/idRepeatChk",
			data: {userid:userid},
			success: function(res){
				if(res==1){
					$("#exampleUserId").attr('class','form-control form-control-user border-danger-lg');
					$("#idchkText").toggle();
					$("#idchkText").html("<h6 class='text-danger'>아이디가 중복되었습니다</h6>");
					$("#boolIdchk").val("1");
				}else{
					$("#exampleUserId").attr('class','form-control form-control-user');
					$("#idchkText").hide();
					$("#boolIdchk").val("0");
				}
				
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert('통신 실패');
			}
		});
	});
	});
	function chk_form(){
		if(document.getElementById("exampleName").value == '') {
			alert('이름을 입력해주세요.');
			document.getElementById("exampleName").focus();
			return false;
		}
		if(document.getElementById("exampleInputEmail").value == '') {
			alert('이메일을 입력해주세요.');
			document.getElementById("exampleInputEmail").focus();
			return false;
		}
		if(document.getElementById("exampleUserId").value == '') {
			alert('아이디를 입력해주세요.');
			document.getElementById("exampleUserId").focus();
			return false;
		}
		if(document.getElementById("exampleInputPassword").value == '') {
			alert('비밀번호를 입력해주세요.');
			document.getElementById("exampleInputPassword").focus();
			return false;
		}
		if(document.getElementById("exampleRepeatPassword").value == '') {
			alert('비밀번호확인을 입력해주세요.');
			document.getElementById("exampleRepeatPassword").focus();
			return false;
		}
		if(document.getElementById("exampleInputPassword").value !==document.getElementById("exampleRepeatPassword").value){
			alert('비밀번호가 일치하지않습니다.');
			document.getElementById("exampleInputPassword").focus();
			return false;
		}
		if(document.getElementById("boolIdchk").value === "1"){
			alert('아이디가 중복 됩니다.');
			document.getElementById("exampleUserId").focus();
			return false;
		}
		document.getElementById('user').submit();
	}
	
</script>
<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
							<input type="hidden" name="boolIdchk" id="boolIdchk" value="0">
                            <form id='user' class="user" action="/site/register_ok" method="post">
                                <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="exampleName"
                                            placeholder="Name" name="name">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Email Address" name="email">
                                </div>
								<div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="exampleUserId"
                                            placeholder="UserID" name="userid">
                                </div>
								<div id="idchkText" class="text-center" style="display:none;">
								</div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password" name="password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Repeat Password" name="Rpassword">
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary btn-user btn-block" onclick="return chk_form()">
                                    Register Account
                                </a>
                                <hr>
                                <a href="/" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="/" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="/site/forgot_password">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="/site/login">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>

</body>

</html>