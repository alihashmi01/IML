<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="fb.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
</head>
<body onload="screenHeight();displayChangebox();">
<!-- end:div wrap-->	
<div id="wrap">
	<!-- div header-->
		<div id="header">
		<h1>Build your topic</h1>
			<p class="tagline">Topic Modeling</p>
			
		</div>
	<!--end: div header-->
	<!-- div navigation-->
	<div id="navigation">
			<!-- div navbox-->
			<div id="navbox">
				<ul>
					<li><a href="home.php" target="_blank" >home</a></li>
			</ul>
		 </div>
 	<!--end: div navbox-->
	</div>
	<!--end: div navigation-->
	<!-- div main-->
<div id="main"  style="overflow:auto">
<?php
   $id = 	$favtopic = $_GET["id"];
   
   $filename ="corpus_chunk_file.JSON";
   $jsonString = file_get_contents($filename);
   $data = json_decode($jsonString,true);
	for ($i = 0; $i < count($data["articles"]);$i++)
	{	
		if ($i ==$_GET["id"])
		{
			echo "<h3>".$i.") ".$data["articles"][$i]["headline"]."</h3><br>";
			echo "<h4><U>Article</U>:".$data["articles"][$i]["article"]."<br>";
			echo "<u>keywords</u>:".$data["articles"][$i]["keywords"]."<br>";
			echo "<br>--------<br>";
		}
		
	}
 	
?>
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