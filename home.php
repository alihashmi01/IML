<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="fb.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
</head>
<body onload="screenHeight();displayChangebox();">
<!-- Ali Hashmi, MIT, Topic Modeling-->	
<!-- end:div wrap-->	
<div id="wrap">
	<!-- div header-->
		<div id="header">
		<h1>Topic Discovery</h1>
			<p class="tagline">Topic Modeling</p>
			
		</div>
	<!--end: div header-->
	<!-- div navigation-->
	<div id="navigation">
			<!-- div navbox-->
			<div id="navbox">
				<ul>
					<li><a href="home.php">Home</a></li>
			</ul>
		 </div>
 	<!--end: div navbox-->
	</div>
	<!--end: div navigation-->
	<!-- div main-->
<div id="main"  style="overflow:auto">
<?php	
	echo "<h3>Hello! Today we will build a topic model.</h3><br>";
	echo "<h3>First look  at these two files here. </h3>";
?>
<h3> Click the links to see topics that we have extracted from the two news archive chunks.
<ul>
<br><li>Click: <a href="interactive_modeling_03_home.php?numfile=1">Discovered Topics 1</a></li><br>
<br><li>Click: <a href="interactive_modeling_03_home.php?numfile=2">Discovered Topics 2</a></li><br>
</ul>
</h3>


<!-- end:div main-->
</div>		
			<!-- div footer-->
			<div id="footer">                                  
				<p>©2013</p>
			</div>                                             
			<!-- end:div footer-->
</div>
<!-- end:div wrap-->
</body>
</html>