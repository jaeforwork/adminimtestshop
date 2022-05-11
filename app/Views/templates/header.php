<!DOCTYPE html>
<html lang="ko">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="description" content="">
  <meta name="author" content="">
  <title><?php echo $title?></title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/favicon.ico');?>" />
  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap/css/bootstrap.min.css');?>">
  <!-- Icon Font Css -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/icofont/icofont.min.css');?>">
  <!-- Slick Slider  CSS -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/slick-carousel/slick/slick.css');?>">
  <link rel="stylesheet" href="<?php echo base_url('plugins/slick-carousel/slick/slick-theme.css');?>">

  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/style.css');?>">

</head>

<body id="top">
<!--[if lte IE 7]>
<div class="alert alert-danger text-center" style="width:100%; position:absolute; top:0; left:0; z-index:1040;">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	사용 중인 브라우저는 보안에 취약한 구 버전의 브라우저입니다.<br />
	웹 브라우저를 <a href="https://www.microsoft.com/korea/ie" class="alert-link" target="_blank">업그레이드</a>하시거나,
	다른 브라우저를 사용해보시기 바랍니다.<br />
	<a href="https://www.google.com/chrome?hl=ko" class="alert-link" target="_blank">크롬 바로가기</a>,
	<a href="https://www.mozilla.or.kr/ko/firefox/" class="alert-link" target="_blank">파이어폭스 바로가기</a>
</div>
<![endif]-->
<header>
	<div class="header-top-bar">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<ul class="top-bar-info list-inline-item pl-0 mb-0">
						<li class="list-inline-item"><a href="mailto:support@gmail.com"><i class="icofont-support-faq mr-2"></i>jaeforwork2020@gmail.com</a></li>
						<li class="list-inline-item"><i class="icofont-location-pin mr-2"></i>Address Korea </li>
					</ul>
				</div>
				<div class="col-lg-6">
					<div class="text-lg-right top-right-bar mt-2 mt-lg-0">
						<a href="tel:010-5896-5938" >
							<span>Call Now : </span>
							<span class="h4">010-5896-5938</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<nav class="navbar navbar-expand-lg navigation" id="navbar">
		<div class="container">
		 	 <a class="navbar-brand" href="<?php echo site_url()?>">
			  	<img src="images/logo.png" alt="" class="img-fluid">
			  </a>

		  	<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarmain" aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
			<span class="icofont-navigation-menu"></span>
		  </button>
	  
		  <div class="collapse navbar-collapse" id="navbarmain">
			<ul class="navbar-nav ml-auto">
			  <li class="nav-item active">
				  <a class="nav-link" href="<?php echo base_url('/')?>">Home</a>
			  </li>
			  <li class="nav-item">
          <a class="nav-link" href="">About</a>
        </li>
        <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">File Test <i class="icofont-thin-down"></i></a>
				<ul class="dropdown-menu" aria-labelledby="dropdown03">
            <li><a class="dropdown-item" href="<?php echo base_url('/File/supload')?>">File upload </a></li>
            <li><a class="dropdown-item" href="<?php echo base_url('/File/mupload')?>">File multi upload </a></li>
						<li><a class="dropdown-item" href="<?php echo base_url('/File/delete')?>">File delete </a></li>
					</ul>
			  	</li>
			  <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Login code <i class="icofont-thin-down"></i></a>
          <ul class="dropdown-menu" aria-labelledby="dropdown03">
            <li><a class="dropdown-item" href="<?php echo base_url('/member/login')?>">Login</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url('/member/password')?>">Lost password</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url('/member/login')?>">SMS certification</a></li>
          </ul>
			  </li>
        <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">DB Test <i class="icofont-thin-down"></i></a>
				<ul class="dropdown-menu" aria-labelledby="dropdown03">
            <li><a class="dropdown-item" href="<?php echo base_url('/member/students')?>">Students Transactions </a></li>
						<li><a class="dropdown-item" href="<?php echo base_url('/encrypt')?>">Encrypt / Decrypt</a></li>
            <li><a class="dropdown-item" href="">Entity</a></li>
					</ul>
			  	</li>

			    <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Test code <i class="icofont-thin-down"></i></a>
					<ul class="dropdown-menu" aria-labelledby="dropdown02">
						<li><a class="dropdown-item" href="<?php echo base_url('/player')?>">Mp4 Player</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url('/images/upload')?>">Images upload</a></li>
						<li><a class="dropdown-item" href="<?php echo base_url('/excel/upload')?>">Excel upload</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url('/excel/download')?>">Excel download</a></li>
					</ul>
			  	</li>

			  	<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ajax code <i class="icofont-thin-down"></i></a>
					<ul class="dropdown-menu" aria-labelledby="dropdown03">
						<li><a class="dropdown-item" href="">Ajax scrolling data</a></li>
						<li><a class="dropdown-item" href="">Ajax Posting</a></li>
						<li><a class="dropdown-item" href="<?php echo base_url('/pages/view')?>">Ajax table</a></li>
					</ul>
			  	</li>

          <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="doctor.html" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">API code <i class="icofont-thin-down"></i></a>
					<ul class="dropdown-menu" aria-labelledby="dropdown03">
          <li><a class="dropdown-item" href="<?php echo base_url('/Api/Login')?>">Login</a></li>
						<li><a class="dropdown-item" href="">Board</a></li>
						<li><a class="dropdown-item" href="<?php echo base_url('/Api/Driver')?>">Driver</a></li>
					</ul>
			  	</li>
			   <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
			</ul>
		  </div>
		</div>
	</nav>
</header>
	


