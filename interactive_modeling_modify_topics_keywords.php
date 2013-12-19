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
	if ($_GET["action"]=="submit")
	{

      $filename = "topicsfileout.JSON";
	  $filename_output_topics = "topicsfileout.JSON";
	  $CORPUS_FILE_SINGLE =  "corpus_chunk_file.JSON";
 	  $favtopic=   $_GET["favtopic"];


	  $getterms = array();
	  $getremoveterms = array();
	  $gettopics = array();
	  
	  $gettopics[0] = $_GET["topic0"];
	  $gettopics[1] = $_GET["topic1"];
	  $gettopics[2] = $_GET["topic2"];
	  $gettopics[3] = $_GET["topic3"];
	  $gettopics[4] = $_GET["topic4"];
	  
	  $getterms[0]=explode(",", $_GET["terms0"]);
	  $getterms[1]=explode(",", $_GET["terms1"]);
	  $getterms[2]=explode(",", $_GET["terms2"]);
	  $getterms[3]=explode(",", $_GET["terms3"]);
	  $getterms[4]=explode(",", $_GET["terms4"]);

	  $getremoveterms[0]=explode(",", $_GET["removeterms0"]);
	  $getremoveterms[1]=explode(",", $_GET["removeterms1"]);
	  $getremoveterms[2]=explode(",", $_GET["removeterms2"]);
	  $getremoveterms[3]=explode(",", $_GET["removeterms3"]);
	  $getremoveterms[4]=explode(",", $_GET["removeterms4"]);
	  
		 echo "<h3>You are now building/refining your topic builder for the topic: <u>$favtopic</u>.<br></h3>";
		 
		//read from the file lda generated file
		$jsonString = file_get_contents($filename);
		$data = json_decode($jsonString,true);
		echo "<br>";
		for ($i = 0; $i < count($data["topics"]);$i++)
		{	
			if ($gettopics[$i] != "") {
				$data["topics"][$i]["topic"]=$gettopics[$i];
			}
			foreach($getremoveterms[$i] as $getremoveterm) 
			{		if ($getremoveterm != "") 
					{  
					   echo "<h4>[Removed: ".$getremoveterm."] from topic [".$data["topics"][$i]["topic"]."]<br></h4>";
						// remove terms (case of single one) pls check
						if (strstr($data["topics"][$i]["keywords"], " ".$getremoveterm." ")) {
								$data["topics"][$i]["keywords"]=str_replace(" ".$getremoveterm." ", " ", $data["topics"][$i]["keywords"]);
						}
						else{
								$data["topics"][$i]["keywords"]=str_replace(" ".$getremoveterm, " ", $data["topics"][$i]["keywords"]);
						}
						//replace double space with single space
						$data["topics"][$i]["keywords"]=str_replace("  ", " ", $data["topics"][$i]["keywords"]);
						
					}
			}
			foreach($getterms[$i] as $getterm) 
			{		if ($getterm != "") 
					{
						echo "[adding: ".$getterm."] to topic [".$data["topics"][$i]["topic"]."]<br>";
						$data["topics"][$i]["keywords"]= $data["topics"][$i]["keywords"]." ".$getterm;
						//replace double space with single space
						$data["topics"][$i]["keywords"]=str_replace("  ", " ", $data["topics"][$i]["keywords"]);
						
					}
			}
			
		}

	$newJsonString = json_encode($data);
    file_put_contents($filename_output_topics, $newJsonString);

	}
	else
	{
	?>
      <form name="mainform"  method="get" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return mainformvalidate()">
	  <input type="hidden" name="action" value="submit">
   	  <input type="hidden" name="favtopic" value="<?php echo $_GET["favtopic"];?>">
	  <input type="submit" value="Submit">
	  <div id="mesgbox" class="mesgbox" style="color:orange; font-weight:bold;"> </div>
	  <div id="mesgbox2" class="mesgbox2" style="color:red; font-weight:bold;"> </div>
	
	<?php
	
	echo "<h3>-Check any terms that you think do not fit the topic label by checking the checkbox next to the term.<br> -Add other terms by putting comma separated values: alpha,beta,gamma.
	
	<br><br><font color=\"orange\">Caveat: The keywords are based on the news articles that are used.</font>
	</h3>";

	
	
	$favtopic = $_GET["favtopic"];
    $jsonString = file_get_contents('topicsfileout.JSON');
	$data = json_decode($jsonString,true);
		for ($i = 0; $i < count($data["topics"]);$i++)
		{
			if ($data["topics"][$i]["topic"] == $favtopic) 
			{ 
			 //$pieces = explode(" ", $xtra);
			 $keywordsarray = explode (" ",$data["topics"][$i]["keywords"]);
 			echo "<br><br><h3>_______________________________________________________________________________________<br>";
					
					echo "Showing Key terms for: ".$data["topics"][$i]["topic"];
					
					echo "<input class=\"topicid$i\" type=\"hidden\" size=\"20\"3 maxlength=\"100\" name=\"topic$i\" value =\"".$data["topics"][$i]["topic"]."\">";
					
					echo "<br>Add terms of your choice, separated by commas:<input type=\"text\" size=\"40\" maxlength=\"100\" name=\"terms$i\"><br>";
					echo "<input class=\"removeterms$i\" type=\"hidden\" size=\"40\" maxlength=\"100\" name=\"removeterms$i\">";
					echo "<br><font color=\"#BROWN\">Remove terms that do not fit in the topic category:<font color=\"#2C3539\"><br>";
					echo "<font color=\"#2C3539\">";
					for ($j = 0; $j < count($keywordsarray); $j++)
					{
						if (strlen($keywordsarray[$j] )> 1) 
						{
							echo "<input class=\"target$i\" type=\"checkbox\" name=\"chkbox$i\"value=\"".$keywordsarray[$j]."\">";
							echo $keywordsarray[$j];
						}
					}
					echo "</font>";		
					echo "</h3>";
			}
		}
	  }
 ?>
		  </form>
	<?php			

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