<?
  # ===========================================================
  # file name:  creds.php
  # purpose:    credentials for Qualys API
  # created:    June 2011
  # authors:    Don Franke
  #             Josh Stevens
  #             Peter Babcock
  #
  # Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php 
  # ===========================================================
  
  $apiprefix = "https://qualysapi.qg2.apps.qualys.com";
  $username = "YOUR API USERID";
  $password = base64_decode("base64 encoded API PASSWORD");

  $proxy = "PROXY:PORT";
  $proxypwd = base64_decode("base64 encoded PROXYPASSWORD");
  $proxyuserpwd = "PROXYUSER:" . $proxypwd;

?>
