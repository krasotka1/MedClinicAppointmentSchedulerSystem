<?php
$id = '';
if (isset($_GET["id"]))
{
    $id = $_GET["id"];
} 
global $wpdb;
$wpdb->query('DELETE FROM '.$wpdb->prefix.'holidays WHERE id = "'.$id.'"');
header('Location: http://medclinic.x10host.com/holidays/');
exit();
?>