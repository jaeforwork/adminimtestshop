
<!-- footer Start -->
<footer class="footer section gray-bg">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 mr-auto col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<div class="logo mb-4">
						<img src="images/logo.png" alt="" class="img-fluid">
					</div>
					<p>본 홈페이지는 PHP 프로그래머 최재훈 개인 포트폴리오 및 테스트용으로 CI4 MariaDb, AWS EC2 Seoul 리전으로 세팅되어 있습니다.</p>

					<ul class="list-inline footer-socials mt-4">
						<li class="list-inline-item"><a href="https://www.facebook.com/themefisher"><i class="icofont-facebook"></i></a></li>
						<li class="list-inline-item"><a href="https://twitter.com/themefisher"><i class="icofont-twitter"></i></a></li>
						<li class="list-inline-item"><a href="https://www.pinterest.com/themefisher/"><i class="icofont-linkedin"></i></a></li>
					</ul>
				</div>
			</div>

			<div class="col-lg-2 col-md-6 col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">부가페이지</h4>
					<div class="divider mb-4"></div>

					<ul class="list-unstyled footer-menu lh-35">
						<li><a href="#">부가페이지</a></li>
						<li><a href="#">부가페이지</a></li>
						<li><a href="#">부가페이지</a></li>
						<li><a href="#">부가페이지</a></li>
						<li><a href="#">부가페이지</a></li>
					</ul>
				</div>
			</div>

			<div class="col-lg-2 col-md-6 col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">부가페이지2</h4>
					<div class="divider mb-4"></div>

					<ul class="list-unstyled footer-menu lh-35">
						<li><a href="#">부가페이지2</a></li>
						<li><a href="#">부가페이지2</a></li>
						<li><a href="#">부가페이지2</a></li>
						<li><a href="#">부가페이지2</a></li>
						<li><a href="#">부가페이지2</a></li>
					</ul>
				</div>
			</div>

			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="widget widget-contact mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">연락처</h4>
					<div class="divider mb-4"></div>

					<div class="footer-contact-block mb-4">
						<div class="icon d-flex align-items-center">
							<i class="icofont-email mr-3"></i>
							<span class="h6 mb-0">Support Available always</span>
						</div>
						<h4 class="mt-2"><?php echo safe_mailto('jaeforwork2020@gmail.com','jaeforwork2020@gmail.com');?></h4>
					</div>

					<div class="footer-contact-block">
						<div class="icon d-flex align-items-center">
							<i class="icofont-support mr-3"></i>
							<span class="h6 mb-0">Mon to Fri : 09:00 - 18:00</span>
						</div>
						<h4 class="mt-2"><a href="tel:010-5896-5938">010-5896-5938</a></h4>
					</div>
				</div>
			</div>
		</div>
		
		<div class="footer-btm py-4 mt-5">
			<div class="row align-items-center justify-content-between">
				<div class="col-lg-6">
					<div class="copyright">
						&copy; Copyright Reserved to <span class="text-color">www.imtest.shop</span> by <a href="<?php echo base_url();?>" target="_blank">www.imtest.shop</a>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="subscribe-form text-lg-right mt-5 mt-lg-0">
						<form action="#" class="subscribe">
							<input type="text" class="form-control" placeholder="Your Email address">
							<a href="#" class="btn btn-main-2 btn-round-full">Ajax Test</a>
						</form>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<a class="backtop js-scroll-trigger" href="#top">
						<i class="icofont-long-arrow-up"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</footer>
<ul id="alert" class="list-unstyled" style="width:230px; position:fixed; top:10%; left:50%; margin-left:-115px; z-index:1920;"></ul>
    <!-- 
    Essential Scripts
    =====================================-->
  
    <!-- Main jQuery -->
    <script src="<?php echo base_url('plugins/jquery/jquery.js');?>"></script>
    <!-- Bootstrap 4.3.2 -->
    <script src="<?php echo base_url('plugins/bootstrap/js/popper.js');?>"></script>
    <script src="<?php echo base_url('plugins/bootstrap/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('plugins/counterup/jquery.easing.js');?>"></script>
    <!-- Slick Slider -->
    <script src="<?php echo base_url('plugins/slick-carousel/slick/slick.min.js');?>"></script>
    <!-- Counterup -->
    <script src="<?php echo base_url('plugins/counterup/jquery.waypoints.min.js');?>"></script>
    
    <script src="<?php echo base_url('plugins/shuffle/shuffle.min.js');?>"></script>
    <script src="<?php echo base_url('plugins/counterup/jquery.counterup.min.js');?>"></script>
    <!-- Google Map -->
    <script src="<?php echo base_url('plugins/google-map/map.js');?>"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>    
    
    <script src="<?php echo base_url('js/script.js');?>"></script>
    <script src="<?php echo base_url('js/contact.js');?>"></script>

  </body>
  </html>
   