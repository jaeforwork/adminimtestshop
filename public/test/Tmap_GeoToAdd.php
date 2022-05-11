<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>simpleMap</title>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script
	src="https://apis.openapi.sk.com/tmap/jsv2?version=1&appKey=l7xx9f4e3034dbb04ca39269f054f2923070"></script>
<script type="text/javascript">
	var map, marker;
	function initTmap() {
		// 2. API 사용요청

		var lon="126.986072";
    var lat="37.570028";


			$
					.ajax({
						method : "GET",
						url : "https://apis.openapi.sk.com/tmap/geo/reversegeocoding?version=1&format=json&callback=result",
						async : false,
						data : {
							"appKey" : "l7xx9f4e3034dbb04ca39269f054f2923070",
							"coordType" : "WGS84GEO",
							"addressType" : "A10",
							"lon" : lon,
							"lat" : lat
						},
						success : function(response) {
							// 3. json에서 주소 파싱
							var arrResult = response.addressInfo;

							//법정동 마지막 문자 
							var lastLegal = arrResult.legalDong
									.charAt(arrResult.legalDong.length - 1);

							// 새주소
							newRoadAddr = arrResult.city_do + ' '
									+ arrResult.gu_gun + ' ';

							if (arrResult.eup_myun == ''
									&& (lastLegal == "읍" || lastLegal == "면")) {//읍면
								newRoadAddr += arrResult.legalDong;
							} else {
								newRoadAddr += arrResult.eup_myun;
							}
							newRoadAddr += ' ' + arrResult.roadName + ' '
									+ arrResult.buildingIndex;

							// 새주소 법정동& 건물명 체크
							if (arrResult.legalDong != ''
									&& (lastLegal != "읍" && lastLegal != "면")) {//법정동과 읍면이 같은 경우

								if (arrResult.buildingName != '') {//빌딩명 존재하는 경우
									newRoadAddr += (' (' + arrResult.legalDong
											+ ', ' + arrResult.buildingName + ') ');
								} else {
									newRoadAddr += (' (' + arrResult.legalDong + ')');
								}
							} else if (arrResult.buildingName != '') {//빌딩명만 존재하는 경우
								newRoadAddr += (' (' + arrResult.buildingName + ') ');
							}

							// 구주소
							jibunAddr = arrResult.city_do + ' '
									+ arrResult.gu_gun + ' '
									+ arrResult.legalDong + ' ' + arrResult.ri
									+ ' ' + arrResult.bunji;
							//구주소 빌딩명 존재
							if (arrResult.buildingName != '') {//빌딩명만 존재하는 경우
								jibunAddr += (' ' + arrResult.buildingName);
							}

							result = "새주소 : " + newRoadAddr + "</br>";
							result += "지번주소 : " + jibunAddr + "</br>";
							result += "위경도좌표 : " + lat + ", " + lon;

							var resultDiv = document.getElementById("result");
							resultDiv.innerHTML = result;

						},
						error : function(request, status, error) {
							console.log("code:" + request.status + "\n"
									+ "message:" + request.responseText + "\n"
									+ "error:" + error);
						}
					});

	
	}
</script>
</head>
<body onload="initTmap();">
	<p id="result"></p>
	<div id="map_wrap" class="map_wrap3">
		<div id="map_div"></div>
	</div>
	<div class="map_act_btn_wrap clear_box"></div>
</body>
</html>

			