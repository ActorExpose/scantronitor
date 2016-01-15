<?
  # ===========================================================
  # file name:  index.php
  # purpose:    the main page, also provides an FAQ
  # created:    June 2011
  # authors:    Don Franke
  #             Josh Stevens
  #             Peter Babcock
  # ===========================================================
?>
<!----------------------------------------------------------------
  Scantronitor
  Front-end for the Qualys API
  Use this to provide visibility into scanning activity
  Created by Don Franke, Josh Stevens and Pete Babcock, 2011
    
  Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php 
  ---------------------------------------------------------------->
<html>
	<head>
		<link rel=stylesheet href=scantronitor.css>
		</link>
		<title>Scantronitor</title>	</head> 
	<body background=images/scantronitor.png>
	<?php include "header.php";?>
	<p align="center">
		<table id=simplebox width=800>
			<tr><td class="altrow">
					<h3>FAQ Question 1</h3>
				    FAQ Answer 1
					<br><br>
			</td></tr>
			<tr><td>
					<h3>FAQ Question 2</h3>
				    FAQ Answer 2
					<br><br>
			</td></tr>
			<tr><td class="altrow">
					<h3>FAQ Question 3</h3>
				    FAQ Answer 3
					<br><br>
			</td></tr>
			<tr><td>
					<h3>FAQ Question 4</h3>
				    FAQ Answer 4
					<br><br>
			</td></tr>
		</table> 
		</p>
	<?php include 'footer.php'?>	
	</body>
</html>