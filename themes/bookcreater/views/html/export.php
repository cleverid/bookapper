<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Домашний доктор. Справочник.</title>
	<link rel="stylesheet" type="text/css" href="/design/css/gallery_sense.css" />
	<link rel="stylesheet" type="text/css" href="/design/css/jquery.mobile.structure-1.1.0.min.css" />
	<link rel="stylesheet" type="text/css" href="/design/css/jquery.mobile.themes-1.1.0.css" />
	<link rel="stylesheet" type="text/css" href="/design/css/style.css" />

	<script type = "text/javascript" src = "/design/js/cordova-2.5.0.js"></script>
	<script type="text/javascript" charset="utf-8">
		//принудительное закрывание splashscreen при старте
		document.addEventListener("deviceready", onDeviceReady, false);

		function onDeviceReady() {
			navigator.splashscreen.hide();
		}
	</script>
	<script type = "text/javascript" src = "/design/js/jquery.js"></script>
	<script type = "text/javascript" src = "/design/js/gallery_sense_2.0.js"></script>
	<script>
	//setting default values
		$(document).bind("mobileinit", function(){
			$.mobile.defaultPageTransition = "none";
		});
	</script>
	<script type = "text/javascript" src = "/design/js/jquery.mobile-1.1.0.min.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(window).load(function(){
			//foter auto-hide switch off
			$("[data-role=footer]").fixedtoolbar({ tapToggle: false });
		});
	</script>
</head>
<body>
	<div id = "web_page_container">
	<div data-role = "page" id = "menu" data-fullscreen = "false">
		<div data-role = "content" style = "padding: 15px">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider" data-theme="b">Содержание:</li>
				<?foreach($articles as $article):?>
					<li>
						<a href="#page_<?=$article['position']?>" data-transition="none">
							<!--<img src="images/menu_recovery.png">-->
							<p><div style="white-space: pre-wrap"><?=$article['title']?></div></p>
						</a>
					</li>
				<?endforeach;?>
			</ul>
			<br />
			<br />

			<!-- /navbar -->
			<div data-role = "footer" data-position = "fixed" data-theme = "b">
				<div data-role = "navbar" data-iconpos = "left">
					<ul>
						<li><a href="#about" data-icon="info" data-transition="none">О программе</a></li>
						<li><!--<a href="https://play.google.com/store/apps/details?id=com.teamwizardry.PocketDoctor" 
						data-icon="star" data-transition="none">Оставить отзыв >></a>--></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</body>
</html>