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
					<li><a href="home.php" target="_blank" >Home</a></li>
			</ul>
		 </div>
 	<!--end: div navbox-->
	</div>
	<!--end: div navigation-->
	<!-- div main-->
			<div id="main"  style="overflow:auto">
		
<?php 

	$favtopic = $_GET["favtopic"];
	$CORPUS_CHUNK_FILE = "corpus_chunk_file.JSON";
    $jsonString = file_get_contents($CORPUS_CHUNK_FILE);
	$data = json_decode($jsonString,true);

	// this saves the user responses in predict_user1 column
	for ($i = 0; $i < count($data["articles"]);$i++)
	{
		   if ($_GET["user_response$i"] != null)
		   {
				$data["articles"][$i]["predict_user1"] = $_GET["user_response$i"];
     		}
	}

	$newJsonString = json_encode($data);
    file_put_contents($CORPUS_CHUNK_FILE, $newJsonString);

	ob_start();

	echo "<table>";
	echo "<tr>";
	echo "<th>Document #</th>";
	echo" <th>Predicted Topic</th>";
	echo" <th>Your prediction</th>";
	echo" </tr>";
	$total_fav_count = 0;
	$total_fav_predicted_count= 0;
	$total_other_count= 0;
	$total_other_predicted_count= 0;
	$tp = 0;
	$tn =0;
	$fp = 0;
	$fn =0;

	$jsonString = file_get_contents($CORPUS_CHUNK_FILE);
	$data = json_decode($jsonString,true);
	for ($i = 0; $i < count($data["articles"]);$i++)
	{
		if ($data["articles"][$i]["predict"] != "notclassified")
		{
			
			echo "<tr>";
			echo "<td><a href=\"corpus_chunk_file.php?id=$i\" target='_blank' \">$i</a></td>";
			
			if ($data["articles"][$i]["predict"]==$favtopic){
				echo "<td>".$data["articles"][$i]["predict"]."  </td>";
			}
			else{
				echo "<td> OTHER </td>";
			}
			
    		echo "<td>".$data["articles"][$i]["predict_user1"]."  </td>";
			echo "</tr>";
			
			//true positive 
			if ($data["articles"][$i]["predict"]==$favtopic &&  $data["articles"][$i]["predict_user1"]==$favtopic)
			{
				$tp++;
			}
			//true negative 
			if ($data["articles"][$i]["predict"]!=$favtopic &&  $data["articles"][$i]["predict_user1"]!=$favtopic)
			{
				$tn++;
			}
			//false negatives
			if ($data["articles"][$i]["predict"]!=$favtopic &&  $data["articles"][$i]["predict_user1"]==$favtopic)
			{
				$fn++;
			}
			//false positives
			if ($data["articles"][$i]["predict"]==$favtopic &&  $data["articles"][$i]["predict_user1"]!=$favtopic)
			{
				$fp++;
			}

			if ($data["articles"][$i]["predict_user1"]==$favtopic){
				 $total_fav_count++;
			}
			if($data["articles"][$i]["predict"]==$favtopic){
				$total_fav_predicted_count++;
			}
			if ($data["articles"][$i]["predict_user1"]!=$favtopic){
				$total_other_count++;
			}
			if ($data["articles"][$i]["predict"]!=$favtopic){
				$total_other_predicted_count++;
			}
			
		}
	}
	echo "<table>";

echo "<br>Total items that you chose in your topic list=".$total_fav_count;                             
echo "<br>Total items predicted as your chosen topic=".$total_fav_predicted_count;
echo "<br>Total items that you did not choose in your topic list=".$total_other_count;
echo "<br>Total items predicted as 'Other'=".$total_other_predicted_count;
$Accuracy =  100*($tp + $tn) / ($tp + $tn + $fp + $fn);

echo "<br>Accuracy:".$Accuracy ." %<br>True Positive($tp) + True Negative($tn) <br>_________________________________________________________________________________<br> (True Positive($tp) + True Negative ($tn) + False Positive ($fp) + False Negative ($fn))";


$metric = ($tp)/($total_fav_count + $fp);
echo "<br><br>Effective Accuracy = True Positives($tp)/ (Total True($total_fav_count) + False Positives($fp))=".round(100*$metric)."%";
echo "<br><br>";
	
	date_default_timezone_set('America/New_York');
	$today = date("Y-m-d_H-i-s");
	$savefilecontent = ob_get_contents();
	$savefile = "train_response_submit".$today.".html";
	file_put_contents($savefile, $savefilecontent);
		

		// call the python script
	$command = "python interactive_modeling_07_classify_articles2.py 2>&1";
	$pid = popen( $command,"r");
	while( !feof( $pid ) )
	{
	 echo fread($pid, 256);
	 flush();
	 ob_flush();
	 usleep(100000);
	}
	pclose($pid);
	
	echo "<br><br>";
   
   echo "<h3>Now we will classify results that we have not seen before for the topic: '$favtopic'</h3>";
  
   echo "<a  href=\"interactive_modeling_08_final_predictions.php?favtopic=".urlencode($favtopic)."\"><h3>Click here to see how articles were classified using the model you built</h3></a>";
	echo "<br><br>";
	echo "<br><br>";
	echo "<br><br>";
	
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