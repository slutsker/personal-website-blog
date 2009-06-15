<?php
  $serv = 'http://localhost/personal/';
  $dbh = mysql_connect('localhost','woo','pass') or die (mysql_error());
  @mysql_select_db('sashaslutsker') or die( "Unable to select database");
?>