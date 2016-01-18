<?
    # ===========================================================
    # file name:  present/index.php
    # purpose:    main page to display currently running scans
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

<? include '../creds.php'?>

<?
    $url = "https://qualysapi.qg2.apps.qualys.com/api/2.0/fo/scan/?action=list&state=Running"; // Qualys API v2 only running scans
    $error = "";
    $aryData = array();
    
    // set URL and other appropriate options
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $username .':'.$password); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

	// added these    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyuserpwd);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	// added these for api 2
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Requested-With: Curl'));

    	// grab XML data
	$xmldata = curl_exec($ch);

	//print $xmldata; // DEBUG

	$indexof = strrpos($xmldata, "<?xml version");
	$xmldata = substr($xmldata, $indexof);		// strip off the extra text before the xml

	//print $xmldata; // DEBUG


	if(!$xmldata){
		print curl_error($ch);
	}

    $tag_tree = array();
    $stack = array();
    $i=0;

    # this class is for each tag found in the XML return
   class tag {
        var $name;
        var $attrs=array();
        var $children;
    
        # this is when a tag is found
        function tag($name, $attrs, $children) {
            global $currenttag;
            global $currentvalue;
            global $error;
            global $aryData;
            global $i;

            $currenttag = trim($currenttag);
            $currentvalue = trim($currentvalue);
            $name = trim($name);
   
	    # capture v2 start date
	    if($currenttag=="LAUNCH_DATETIME"&&$name!=""&&$name!="STATUS"){
		$timestamp = strtotime($name);
		$aryData[$i][0] = date("m-d h:i:s a", $timestamp)." CST";
	    }

	    # catpure v2 profile
	    if($currenttag=="TITLE"&&$name!=""&&$name!="SCHEDULED"&&$name!="USER_LOGIN"){
		$aryData[$i][4] = $name;		
	    }

	    # capture v2 status
	    if($currenttag=="STATE"&&$name!=""&&$name!="TARGET"){
		$aryData[$i][3] = $name;		
	    }
 
	    # capture v2 targets 
	    if($currenttag=="TARGET"&&$name!=""&&$name!="SCAN"){
		$aryData[$i][2] = $name;
		$i++;
	    }
        }
    }

    # function used to parse XML
    function startTag($parser, $name, $attrs) {
        global $tag_tree, $stack;
        $tag = new tag($name,$attrs,'');
        global $currenttag;
        global $currentvalue;
        $currenttag = $name;
        array_push($stack,$tag);
        $element = array();
        $element['name'] = $name;
        foreach ($attrs as $key => $value) { 
            $element[$key]=$value;
            $currentvalue=$value;
        }
    }

    # function used to parse XML
    function endTag($parser, $name) {
        global $stack;
        $stack[count($stack)-2]->children[] = $stack[count($stack)-1];
    }   

    # function used to parse XML
    function cdata($parser, $element) {
        global $tag_tree, $stack;
        $tag = new tag($element,$attrs,'');
    }

    # create XML parser and define handlers
    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "startTag", "endTag");
    xml_set_character_data_handler($xml_parser, "cdata");
    $data = xml_parse($xml_parser,$xmldata);

    global $dataTotal;
    global $i;
    $dataTotal = $i;
    
    if(!$data) {
        die(sprintf("XML error: %s at line %d",xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser)));
    }
    
    # release the parser!
    xml_parser_free($xml_parser);
?>
  
<html>
<head>
<link rel="stylesheet" href="../scantronitor.css"> 
</link> 
<title>Scantronitor</title> </head> 
<body>
<?include 'header.php'?>
	
<?if($error!="") {?>	
    <h2 align="center">No scan or map currently running</h2>
<?} else {?>
    <p align="center">
    <table id="datatable" width="1200">
	<tr>
	    <th width="150"><img src="../images/datetime.png" alt="DateTime"></th>
	    <th width="150"><img src="../images/profile.png" alt="Profile"></th>
	    <th width="650"><img src="../images/targets.png" alt="Targets"></th>
	    <th width="250"><img src="../images/status.png" alt="Status"></th>
	</tr>

<?global $aryData;
  global $i;
  $extraMessage = "";
  if($dataTotal == 0){
	$extraMessage = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO SCANS CURRENTLY RUNNING. Please check the 'Past' or 'Future' page for previously run or not yet run scans.";
  }
  for($i=0;$i<$dataTotal;$i++) {
      $aryData[$i][2] = str_replace(",",", ",$aryData[$i][2])?>
	<tr onMouseOver="this.bgColor='#dee7d1'" onMouseOut="this.bgColor='#ffffff'">
	    <td align="left"><?=$aryData[$i][0] ?></td>
	    <td align="left"><?=$aryData[$i][4] ?></td>
	    <td align="left"  width="600"><?=$aryData[$i][2] ?></td>
	    <td align="left"><?=$aryData[$i][3] ?></td>	
	</tr> 
	<?}?>
	<tr>
	    <th colspan="4" align="center"><?=$dataTotal?> Total Record(s)<?=$extraMessage?></th>
	</tr>
    </table>
    </p>
<?}?>
		
<?include '../footer.php'?>
	
</body>
</html>
