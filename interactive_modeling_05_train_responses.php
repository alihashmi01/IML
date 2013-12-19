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
	<FONT COLOR="RED"> 
		<h3>	<div id="mesgbox">
				
    		<div></h3>
	</FONT> 
	
	<!-- div main-->
			<div id="main"  style="overflow:auto">
 <?php 
	$favtopic = $_GET["favtopic"];
	$CORPUS_CHUNK_FILE = "corpus_chunk_file.JSON";
    //echo  "<form method=\"post\" action=\"train_response_submit.php?action=".$favtopic."\">";
	?>
<form name="responseform" method="get" action="interactive_modeling_06_train_responses_submit.php" onsubmit="return responsevalidate()">
<input type="hidden" name="favtopic"  value="<?php echo $_GET["favtopic"];?>">
<input type="submit" value="Submit">
<?php			
    echo "<h3>Check  if the articles are classified correctly for your topic:  [".$_GET["favtopic"]."]<h3>";
    $jsonString = file_get_contents($CORPUS_CHUNK_FILE);
	$data = json_decode($jsonString,true);
	echo "<br>";
		
	for ($i = 0; $i < count($data["articles"]);$i++)
	{	
		// search ones which are not classified.
	   if ($data["articles"][$i]["predict"] !="notclassified" ){
        echo "<h3>".($i).") ".$data["articles"][$i]["headline"]."</h3><br>";
		//added
		echo "<h4><U>Article</U>:".substr($data["articles"][$i]["article"],0,400)."<a href=\"corpus_chunk_file.php?id=$i\" target='_blank' \"> ...</a><br>";
		echo "<u>keywords</u>:".substr($data["articles"][$i]["keywords"],0,500)."<br>";
		
		//BINARY CLASSIFICATION
		if ($data["articles"][$i]["predict"] ==$favtopic )
		{
			echo "<u>Category predicted</u>:<strong><FONT size=\"3\" COLOR=\"blue\"> ".$data["articles"][$i]["predict"]."</FONT></strong><br>";
		}
		else
		{
		    echo "<u>Category predicted</u>:<FONT COLOR=\"red\"> OTHER </FONT><br>";
		}
		
		echo "Is this story is in your topic?";
		if ($data["articles"][$i]["predict"] ==$favtopic ){
			echo "  $favtopic [Yes]<input id=\"user_response$i\" type=\"radio\" name=\"user_response$i\" value=\"$favtopic\" checked=\"checked\">";
			echo "  [No]<input id=\"user_response$i\" type=\"radio\" name=\"user_response$i\" value=\"OTHER\">";
		}
		else
		{
			echo "  $favtopic [Yes]<input id=\"user_response$i\" type=\"radio\" name=\"user_response$i\" value=\"$favtopic\">";
			echo "  [No]<input id=\"user_response$i\" type=\"radio\" name=\"user_response$i\" value=\"OTHER\" checked=\"checked\">";
		}
		//--added		
		echo "<br>Do you want to modiy key terms that defined your topic: <a href=\"interactive_modeling_modify_topics_keywords.php?favtopic=$favtopic\" target='_blank' \">Modify</a>";
		echo "<br>--------<br>";
		}
			
	}
 	
?>
<input type="submit" value="Submit">
</form>

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