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

    $filename = "topicsfile01.txt";
	$numfile=$_GET["numfile"];
	if ($numfile == 1){
		$filename = "topicsfile01.JSON";
		$filename_output_topics = "topicsfileout.JSON";
		$corpus_file = "corpus_chunk_file_01.JSON";
	}else{
		$filename = "topicsfile02.JSON";
		$filename_output_topics = "topicsfileout.JSON";
		$corpus_file = "corpus_chunk_file_02.JSON";
		
	}	
  $CORPUS_FILE_SINGLE =  "corpus_chunk_file.JSON";
	

  $getterms = array();
  $getremoveterms = array();
  $gettopics = array();
  
  $gettopics[0] = $_GET["topic0"];
  $gettopics[1] = $_GET["topic1"];
  $gettopics[2] = $_GET["topic2"];
  $gettopics[3] = $_GET["topic3"];
  $gettopics[4] = $_GET["topic4"];
  $favtopic=   $_GET["favtopic"];
  
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
					echo "[adding: ".$getterm."] to  topic [".$data["topics"][$i]["topic"]."]<br>";
					$data["topics"][$i]["keywords"]= $data["topics"][$i]["keywords"]." ".$getterm;
					//replace double space with single space
					$data["topics"][$i]["keywords"]=str_replace("  ", " ", $data["topics"][$i]["keywords"]);
				}
		}
		
	}

	//corpus_chunk_file_02,corpus_chunk_file_01: corpus_chunk_file.JSON
  	$jsonCorpusString = file_get_contents($corpus_file);
	$dataCorpus = json_decode($jsonCorpusString,true);
	$newCorpusJsonString = json_encode($dataCorpus);
    file_put_contents($CORPUS_FILE_SINGLE , $newCorpusJsonString);
    
	
	// write to a new file, original is kept intact.
	//topicsfileout.JSON
	$newJsonString = json_encode($data);
    file_put_contents($filename_output_topics, $newJsonString);
    
    $command = "python interactive_modeling_04_classify_articles.py 2>&1";
	$pid = popen( $command,"r");
	while( !feof( $pid ) )
	{
	 echo fread($pid, 256);
	 flush();
	 ob_flush();
	 usleep(100000);
	}
	pclose($pid);
   
   echo "<br><br><h3>In the next section we will show you articles' text; please validate the topic you defined/created for each article by looking at the terms.</h3>";
   echo "<a  href=\"interactive_modeling_05_train_responses.php?numfile=$numfile&favtopic=".urlencode($favtopic)."\"><h3>Click here to validate classified items.</h3></a>";
	
}
else
{	
    $filename = "topicsfile01.txt";
	$numfile=$_GET["numfile"];
	if ($numfile == 1){
		$filename = "topicsfile01.txt";
	}else{
		$filename = "topicsfile02.txt";
	}	

	echo "<h3>Discovered five topics in the New York Times news archive chunk.</h3><br><br>";

	echo "<h3>-Name the topics by looking at the keywords for each topic.<br> -Check any terms that you think do not fit the topic label by checking the checkbox next to the term.<br> -Add other terms by putting comma separated values: alpha,beta,gamma.
	
	<br><br><font color=\"orange\">Caveat: The keywords are based on the news articles that are used.</font>
	</h3>";
     ?>
      <form name="mainform"  method="get" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return mainformvalidate()">
	  <input type="hidden" name="action" value="submit">
   	  <input type="hidden" name="numfile" value="<?php echo $numfile ;?>">
	  <input type="submit" value="Submit">
	  <div id="mesgbox" class="mesgbox" style="color:orange; font-weight:bold;"> </div>
	  <div id="mesgbox2" class="mesgbox2" style="color:red; font-weight:bold;"> </div>


	<?php			
	//read the data line by line
	echo "<input class = \"favtopic\" type=\"hidden\" size=\"20\" maxlength=\"100\" name=\"favtopic\"><br>";
	$topic_ctr = 100;
	$handle = fopen($filename, "r");
	if ($handle) {
		while (!feof($handle)) {
			$buffer = fgetss($handle, 4096);
			$topic_and_word = explode(" ", $buffer);
			if ($topic_ctr != $topic_and_word[0] && $topic_and_word[0]  != null){
				$topic_ctr = $topic_and_word[0] ;
				$topic_and_word[1]=preg_replace('/[^A-Za-z0-9\-]/', '', $topic_and_word[1]);
				echo "<br><br><h3>_______________________________________________________________________________________";
				
				echo "<br> Topic<input class=\"topicid$topic_and_word[0]\" type=\"text\" size=\"20\"3 maxlength=\"100\" name=\"topic$topic_and_word[0]\" value =\"\">";

				echo " Train this: <input class=\"fav$topic_and_word[0]\" type=\"checkbox\" name=\"favchkbox$topic_and_word[0]\"value=\"".$topic_and_word[0]."\"><br>";

				echo "Add terms of your choice, separated by commas:<input type=\"text\" size=\"40\" maxlength=\"200\" name=\"terms$topic_and_word[0]\"><br>";

				echo "<input class=\"removeterms$topic_and_word[0]\" type=\"hidden\" size=\"40\" maxlength=\"100\" name=\"removeterms$topic_and_word[0]\">";
				
				echo "<font color=\"#BROWN\">Remove terms that do not fit in the topic category:<font color=\"#2C3539\"><br>";
				
			}
			if (substr(bin2hex($topic_and_word[1]), -4)=="0d0a"){
					  	 $slen = strlen($topic_and_word[1]); 
					  	 $topic_and_word[1]=substr($topic_and_word[1],0,$slen-2);
			}
			echo "<font color=\"#2C3539\">";	
			// this is keyword info
			if ( strlen($topic_and_word[1]) > 2){
			echo "<input class=\"target$topic_and_word[0]\" type=\"checkbox\" name=\"chkbox$topic_and_word[0] \"value=\"".trim($topic_and_word[1])."\">"; echo $topic_and_word[1];
			echo "</font>";
			}	
		}
	
		fclose($handle);
	
	}
    echo "</h3>";
	?>
	  <input type="submit" value="Submit">
	  </form>
	  <br>
	  <br>
	<?php			
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