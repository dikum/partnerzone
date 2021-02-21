<!DOCTYPE html>
<html lang="en">
<head>
<title>@yield('title')</title>


<!--[if lt IE 10]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" href="./assets/images/favicon.ico" type="image/x-icon">
<link rel="icon" type="image/png" sizes="192x192" href="./assets/images/android-chrome-192x192.png">

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Quicksand:500,700" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css">

<link rel="stylesheet" href="./assets/css/waves.min.css" type="text/css" media="all">

<link rel="stylesheet" type="text/css" href="./assets/css/feather.css">

<link rel="stylesheet" type="text/css" href="./assets/css/font-awesome-n.min.css">

<link rel="stylesheet" href="./assets/css/chartist.css" type="text/css" media="all">

<link rel="stylesheet" type="text/css" href="./assets/css/style.css">
<link rel="stylesheet" type="text/css" href="./assets/css/widget.css">

<link href="{{'assets/font-awesome/css/font-awesome.min.css'}}" rel="stylesheet">

<!--<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">-->
<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css" rel="stylesheet">



<link rel="stylesheet" type="text/css" href="./assets/css/custom.css">

<link rel="stylesheet" type="text/css" href="./assets/css/animatedmodal/animate.min.css">
<link rel="stylesheet" type="text/css" href="./assets/css/animatedmodal/normalize.min.css">
<link rel="stylesheet" type="text/css" href="./assets/css/offline/offline.css">
<link rel="stylesheet" type="text/css" href="./assets/css/offline/offline-language-english.css">
<link rel="stylesheet" type="text/css" href="./assets/css/windowjs/window.css">
<link rel="stylesheet" type="text/css" href="./assets/css/dropzone/dropzone.css">

<link rel="stylesheet" type="text/css" href="./assets/css/jodit/jodit.min.css">

</head>
<body>
<script>
	//These values will help control how the dialogs are displayed on one another.
	var dialogIDIndex = -1;
	var dialogIndexArray = [];
	var windowObjectArray = [];
	var dialogZIndex = 9999;
</script>
	<div class="loader-bg">
		<div class="loader-bar"></div>
	</div>

	<div id="pcoded" class="pcoded">
		<div class="pcoded-overlay-box">
			
		</div>
	<div class="pcoded-container navbar-wrapper">

	<nav class="navbar header-navbar pcoded-header">
		<div class="navbar-wrapper">
			<div class="navbar-logo">
				<a href="/"><img class="img-fluid" src="./assets/images/logo.png" alt="Emmanuel TV Logo" /></a>
				<a class="mobile-menu" id="mobile-collapse" href="#!">
					<i class="feather icon-menu icon-toggle-right"></i>
				</a>
				<a class="mobile-options waves-effect waves-light">
					<i class="feather icon-more-horizontal"></i>
				</a>
			</div>
			<div class="navbar-container container-fluid">
				<ul class="nav-left" id="icon-title-list">
					<li>
						<a href="#!" onclick="if (!window.__cfRLUnblockHandlers) return false; javascript:toggleFullScreen()" class="waves-effect waves-light" data-cf-modified-93fd9015cca482e27f619fc4-="">
							<i class="full-screen feather icon-maximize"></i>
						</a>
					<li>
						<div class="icon-title" id="home-icon-title"><img src="./assets/images/home.png"></div>
					</li>

				</ul>


				<ul class="nav-right">
					<li class="header-notification">
						<div class="dropdown-primary dropdown">
							<div class="dropdown-toggle" data-toggle="dropdown">
								<i class="feather icon-bell"></i>
								<span id='notification-count' class="badge bg-c-red"></span>
							</div>
							<ul id="notification-list" class="show-notification notification-view dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">

								<li id='bulk_message_notification' class="hidden-notification" style="display: none">
									<div class='media'>
									<img class='img-radius' src='./assets/images/icons/message.png'>
									<div class='media-body'>
									<h5 class='notification-user'> Sending message(s)</h5>
									<p class='notification-msg partner-batch-email'></p>
									<p class='notification-msg partner-sms'></p>
									<p class='notification-msg list-batch-email'></p>
									<p class='notification-msg list-sms'></p>
									<span class='notification-time'> </span>
									<span class='message-error' style='color:maroon'> </span> 
									</div>
									</div>
								</li>
								
							</ul>
						</div>
					</li>

					<li class="user-profile header-notification">
						<div class="dropdown-primary dropdown">
							<div class="dropdown-toggle" data-toggle="dropdown">
								<span>{{$user}}</span>
								<i class="feather icon-chevron-down"></i>
							</div>
							<ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
								<li>
									<a href="/logout">
										<i class="feather icon-log-out"></i> Logout
									</a>
								</li>
							</ul>
						</div>
					</li>
				</ul>	
			</div>
		</div>
	</nav>