<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<?
session_start();

?>
<script>
	function lottos_recommend(){
	
	var lotto = [];
	
	for(var i =0;i<6;i++){
		var num = Math.floor(Math.random() * 44)+1;
		for(var j in lotto){
			if(num == lotto[j]){ 
				 num = Math.floor(Math.random() * 44) + 1;
			}
		}
		if(num<10){
			num="0"+num;
		}
		lotto.push(num);
		
	}
	lotto.sort((a,b)=>a-b);

	lotto.forEach((item,index)=>$(`#sp${index}`).text(`${item}`));

	
}
	$(function(){
		lottos_recommend();
	})
</script>
<? if(isset($_SESSION['userid'])){?>

<script>


function favoriteDelete(userid,stock){
	if(confirm("정말로 삭제하시겠습니까??")){
	$.ajax({
		url:"/jusik/favoriteDelete",
		data:{"userid":userid,"stock_code":stock},
		type: "post",
		async : false,
		success : function(data){
			alert("삭제성공");
			location.reload();
		},
		error : function(error){
			alert(error);
		}
	});
}else{
	return;
}
}
function printTime(){

	var clock = document.getElementById("clock");
	var now = new Date();
now.getHours()+":"+now.getMinutes()
	clock.innerHTML = (now.getHours()>9) ? (now.getMinutes()>9)? now.getHours()+":"+now.getMinutes() : now.getHours()+":"+"0"+now.getMinutes() : (now.getMinutes()>9)? "0"+now.getHours()+":"+now.getMinutes() : "0"+now.getHours()+":"+"0"+now.getMinutes() ;
	
	setTimeout("printTime()",1000);
}
window.onload = printTime;




$(function(){

$('.btn-example').click(function(){
        var $href = $(this).attr('href');
        layer_popup($href);
    });
    function layer_popup(el){

        var $el = $(el);    //레이어의 id를 $el 변수에 저장
        var isDim = $el.prev().hasClass('dimBg'); //dimmed 레이어를 감지하기 위한 boolean 변수

        isDim ? $('.dim-layer').fadeIn() : $el.fadeIn();

        var $elWidth = ~~($el.outerWidth()),
            $elHeight = ~~($el.outerHeight()),
            docWidth = $(document).width(),
            docHeight = $(document).height();

        // 화면의 중앙에 레이어를 띄운다.
        if ($elHeight < docHeight || $elWidth < docWidth) {
            $el.css({
                marginTop: -$elHeight /2,
                marginLeft: -$elWidth/2
            })
        } else {
            $el.css({top: 0, left: 0});
        }

        $el.find('a.btn-layerClose').click(function(){
            isDim ? $('.dim-layer').fadeOut() : $el.fadeOut(); // 닫기 버튼을 클릭하면 레이어가 닫힌다.
            return false;
        });

        $('.layer .dimBg').click(function(){
            $('.dim-layer').fadeOut();
            return false;
        });

    }

var js_array = new Array();
$.ajax({
	type:"post",
	url:"jusik/JusikData",
	data:{userid:"<?=$_SESSION['userid']?>"},
	async:false,
	success : function(data){
		
		js_array = JSON.parse(data);
		let table = document.getElementById('myTable');
		for(let i=0;i<js_array.length;i++){
			let newRow = table.insertRow();
			let newCell1 = newRow.insertCell(0);
			let newCell2 = newRow.insertCell(1);
			let newCell3 = newRow.insertCell(2);
			newCell1.innerHTML = `<font style="color:${js_array[i][5]}">${js_array[i][0]}</font>`;
			newCell2.innerHTML = `<font style="color:${js_array[i][5]}">${js_array[i][1]}</font>`;
			newCell3.innerHTML = `<font style="color:${js_array[i][5]}">${js_array[i][2]}</font>`;
			
		}
		let table2 = document.getElementById('myTable2');
		for(let i=0;i<js_array.length;i++){
			let newRow = table2.insertRow();
			let newCell1 = newRow.insertCell(0);
			let newCell2 = newRow.insertCell(1);
			
			newCell1.innerHTML = `<font style="color:${js_array[i][5]}" data-toggle="tooltip" data-placement="bottom" title="Market Cap : ${js_array[i][4]}">${js_array[i][0]}</font>`;
			newCell2.innerHTML = `<a href='#' class='btn btn-danger btn-circle' id='delete' onclick='favoriteDelete("<?=$_SESSION['userid']?>","${js_array[i][6]}")'><i class="fas fa-trash"></i></a>`;
			
			
		}
	},
	error : function(error){
		console.log(error);
	}

});




var key_array = new Array();
var val_array = new Array();
var col_array = new Array();
var stock_array = new Array();
for(var i=0;i<js_array.length;i++){
	key_array[i] = js_array[i][0];
	val_array[i] = js_array[i][3];
	stock_array[i] = js_array[i][6];
}


for(var i=0;i<val_array.length;i++){
	if(Number(val_array[i])>0)col_array[i]="rgba(255, 99, 132, 0.9)";
	else if(Number(val_array[i])<0)col_array[i]="rgba(54, 162, 235, 0.9)";
	else col_array[i]="rgba(52, 53, 59, 0.9)";
}
	if(col_array.length>=10){
	
	
	}

	var ctx = document.getElementById('myChart2');
	var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: key_array,
        datasets: [{
            label:'전일대비 (%) ',
            data: val_array,
            pointBackgroundColor: col_array,
			pointBorderColor: col_array,
			borderColor: 'rgb(75, 192, 192,0.5)',
			backgroundColor: 'rgb(211,211,211,0.2)',
			pointRadius:3,
			fill:true,
    
        }]
    },
    options: {
		responsive: true,
		maintainAspectRatio: false,
	layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },

		plugins:{
		autocolors: false,
		annotation:{
			annotations:{
			line1:{	
				type:"line",
				yMin:0,
				yMax:0,
				borderColor: 'rgb(0,0,0,0.3)',
				borderWidth: 1,
				 label: {
              
              enabled: true,
              position: "end"
				}
				}
				
			}
		}
		,
		title: {
        display: true,
        text: '전일대비 (%) ',
        
		},
		legend:{
			display:false
		},
		tooltip:{
			mode: 'x',
			intersect:false,
			borderColor: 'rgb(160, 42, 192,0.5)',
            backgroundColor: 'rgb(211,211,211,0.2)',
            borderWidth: 2,
			borderDash: [2, 2],
            borderRadius: 2,
			titleColor:'rgb(0, 0, 0)',
			bodyColor:'rgb(0, 0, 0)',
			yAlign: "bottom"
		}
		},
       
		
	
    },
	
	
});
myChart.canvas.parentNode.style.height = "48vh";
function repeat(){
	$.ajax({
	type:"post",
	url:"jusik/JusikData",
	data:{userid:"<?=$_SESSION['userid']?>"},
	async:false,
	success : function(data){
		js_array = data;
		js_array = JSON.parse(js_array);
		for(var i=0;i<js_array.length;i++){
		val_array[i] = js_array[i][3];
		}


		for(var i=0;i<val_array.length;i++){
			if(Number(val_array[i])>0)col_array[i]="rgba(255, 99, 132, 0.9)";
			else if(Number(val_array[i])<0)col_array[i]="rgba(54, 162, 235, 0.9)";
			else col_array[i]="rgba(52, 53, 59, 0.9)";
		}
		myChart.data.datasets[0].data = val_array;
		myChart.data.datasets[0].pointBackgroundColor = col_array;
		myChart.data.datasets[0].pointBorderColor = col_array;
		myChart.update();
		let table = document.getElementById('myTable');
		for(let i=0;i<js_array.length;i++){
			table.getElementsByTagName("tr")[i+1].getElementsByTagName("td")[0].innerHTML=`<font style="color:${js_array[i][5]}">${js_array[i][0]}</font>`;
			table.getElementsByTagName("tr")[i+1].getElementsByTagName("td")[1].innerHTML=`<font style="color:${js_array[i][5]}">${js_array[i][1]}</font>`;
			table.getElementsByTagName("tr")[i+1].getElementsByTagName("td")[2].innerHTML=`<font style="color:${js_array[i][5]}">${js_array[i][2]}</font>`;
		}
		
	},
	error : function(error){
		console.log(error);
	}
});
}
var chkDay = [0,6];
var chkHour = [9,10,11,12,13,14,15];
function timeout(){
	console.log("timeout 함수 실행");
	var now = new Date();
	var day = now.getDay();
	var hours = now.getHours();
	var minutes = now.getMinutes();

	if(chkDay.includes(day)){
	
	clearInterval(myVar);
	clearInterval(myTimeout);
	return;
	}else{
	
	if(!chkHour.includes(hours)){
		clearInterval(myVar);
		clearInterval(myTimeout);
		return;
	
	}else{
		if(chkHour==15 && minutes>30){
			clearInterval(myVar);
			clearInterval(myTimeout);
			return;
		
		}
	}
}
}

var myVar = setInterval(repeat,5000);
var myTimeout = setInterval(timeout,1800000);

});
	
	
</script>
<? } ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css?var=6" rel="stylesheet">
	 <!-- Custom styles for this page -->
    <link href="/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	
</head>


<body id="page-top">
	<div class="dim-layer">
    <div class="dimBg"></div>
    <div id="layer2" class="pop-layer">
        <div class="pop-container">
            <div class="pop-conts">
                <!--content //-->
                <div id="dragdiv2" class="table-responsive" style="height:400px !important;" >
                    <table id="myTable2" class="table myTable headerH">
						<tr>
							<th style="background-color:#5a5c69 ;!important">Company</th>
							<th style="background-color:#5a5c69 ;!important">Favorite</th>
						</tr>
					</table>
				</div>

                <div class="btn-r">
                    <a href="#" class="btn-layerClose">Close</a>
                </div>
                <!--// content-->
            </div>
        </div>
    </div>
</div>	

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SONG <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <!--<a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
-->
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <!--<a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
						-->
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                         <? if(!isset($_SESSION['username'])){ ?>
						<a class="collapse-item" href="site/login">Login</a>
                        <a class="collapse-item" href="site/register">Register</a>
						<? } else{ ?>
						<a class="collapse-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
						<? } ?>
                        <!-- <a class="collapse-item" href="forgot-password.html">Forgot Password</a> -->
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                      <!--   <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a> -->
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <!-- <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a> -->
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
              <!--   <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a> -->
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <!-- <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" Method="POST" action="/jusik/search">
						<?if(isset($_SESSION['userid'])){ ?><input type="hidden" name="userid" value="<?=$_SESSION['userid']?>">
							<? } ?>
                        <div class="input-group">
							
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2" name="str">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" >
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
					<span class="m-0 font-weight-bold text-primary" id="clock"></span>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                      <!--   <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            Dropdown - Messages
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li> -->

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <? if(isset($_SESSION['username'])){ ?> <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$_SESSION['username']?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
							<? }else{ ?>
							<a class="nav-link dropdown-toggle" href="/site/login" 
                                 aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">로그인</span>
								<img class="img-profile rounded-circle"
                                    src="img/undraw_profile_1.svg">
							<? } ?>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (Annual)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Requests</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">★ Fluctuation Rate </h6>
									
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div id="dragdiv" class="chart-area" style="overflow-x:auto;overflow-y:hidden;">
                                        <canvas id="myChart2"  ></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">★ Favorite</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item btn-example" href="#layer2" >Remove</a>
                                           <!--  <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
							

                                <div class="card-body" style="height:490px; !important">
                                    <div id="dragdiv2" class="table-responsive" style="height:400px !important;" >
                                        <table id="myTable"class="table myTable headerH">
											<tr>
												<th style="background-color:#5a5c69 ;!important">Company</th>
												<th style="background-color:#5a5c69 ;!important">Current Price</th>
												<th style="background-color:#5a5c69 ;!important">Full-Time</th>
											</tr>
                                        </table>
                                    </div>
                                    <div class="mt-4 text-center small">
										<span class="mr-2">
                                            <i class="fas fa-circle text-danger"></i> 상승
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> 하락
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-secondary"></i> 유지
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
						<div class="col-lg-5 mb-6">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Lottos</h6>
                                </div>
                               <div class="card-body" align="center">
								<h2 style="color:#e74a3b">
								<? echo $last_data; ?>
								</h2>
							   </div>
							</div>
							 <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Recommend Lottos</h6>
                                </div>
                               <div class="card-body" align="center">
								<h2 style="color : white;">
								<br/>
								<span id='sp0' width="200px" height="200px" style="padding:10px;border-radius:50%;background:#fbc400"></span>
								<span id='sp1' width="200px" height="200px" style="padding:10px;border-radius:50%;background:#fbc400"></span>
								<span id='sp2' width="200px" height="200px" style="padding:10px;border-radius:50%;background:#69c8f2"></span>
								<span id='sp3' width="200px" height="200px" style="padding:10px;border-radius:50%;background:#69c8f2"></span>
								<span id='sp4' width="200px" height="200px" style="padding:10px;border-radius:50%;background:#69c8f2"></span>
								<span id='sp5' width="200px" height="200px" style="padding:10px;border-radius:50%;background:#b0d840"></span>
								<br/>
								<br/>
							
								<a href="javascript:void(0)" onclick="lottos_recommend(event)"><i class="fas fa-redo"></i></a>
								</h2>
							   </div>
							</div>

                        </div>
                        <!-- Content Column -->
                        <div class="col-lg-7 mb-6">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Lottos</h6>
                                </div>
                               <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Round</th>
                                            <th>Num1</th>
                                            <th>Num2</th>
                                            <th>Num3</th>
                                            <th>Num4</th>
                                            <th>Num5</th>
											<th>Num6</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Round</th>
                                            <th>Num1</th>
                                            <th>Num2</th>
                                            <th>Num3</th>
                                            <th>Num4</th>
                                            <th>Num5</th>
											<th>Num6</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                       <? echo $lotto_data ; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                            </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Yeot Website 2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="/site/logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/datatables-demo.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
   <!--  <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script> -->

</body>
<script>
	function dragSpace(id){
		const slider = document.getElementById(id);
		
		let isMouseDown = false;
		let startX, scrollLeft;
		let startY, scrollTop;

		slider.addEventListener('mousedown', (e) =>{
			isMouseDown = true;
			slider.classList.add('active');

			startX = e.pageX - slider.offsetLeft;
			startY = e.pageY - slider.offsetTop;
			scrollLeft = slider.scrollLeft;
			scrollTop  = slider.scrollTop;
		});
		slider.addEventListener('mouseleave', () =>{
			isMouseDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mouseup', () =>{
			isMouseDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mousemove', (e) =>{
		if(!isMouseDown) return;

			e.preventDefault();
			const x = e.pageX - slider.offsetLeft;
			const walk = (x - startX) * 1;
			slider.scrollLeft = scrollLeft - walk;
			const y = e.pageY - slider.offsetTop;
			const walk2 = (y - startY) * 1;
			slider.scrollTop = scrollTop - walk2;

		});
	}
	
	dragSpace("dragdiv2");





</script>
</html>