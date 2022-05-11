<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>simpleMap</title>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script
	src="https://apis.openapi.sk.com/tmap/jsv2?version=1&appKey=l7xx9f4e3034dbb04ca39269f054f2923070"></script>
<script type="text/javascript">

	function initTmap() {		

		// 3. 직선거리 계산  API 사용요청
		$
				.ajax({
					method : "GET",
					url : "https://apis.openapi.sk.com/tmap/routes/distance?version=1&format=json&callback=result",//
					async : false,
					data : {
						"appKey" : "l7xx9f4e3034dbb04ca39269f054f2923070",
						"startX" : "126.8929257",
						"startY" : "37.4833096",
						"endX" : "127.12685",
						"endY" : "37.44036",
						"reqCoordType" : "WGS84GEO"
					},
					success : function(response) {

						//console.log(response);

						var distance = response.distanceInfo.distance;

						$("#result").text("두점의 직선거리 : " + distance + "m");
					},
					error : function(request, status, error) {
						console.log("code:" + request.status + "\n"
								+ "message:" + request.responseText + "\n"
								+ "error:" + error);
					}
				});

	}

</script>
<body onload="initTmap();">
	<section class="in_section">
		<div id="map_wrap" class="map_wrap3">
			<div id="map_div"></div>
		</div>
		<div class="map_act_btn_wrap clear_box"></div>
		<p id="result"></p>
</body>
</html>
				