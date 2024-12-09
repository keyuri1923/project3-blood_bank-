<?php
session_start();
require_once("conn.php");
$request_id=$_REQUEST["rid"];
if(isset($_REQUEST["status"]) && $_REQUEST["status"]=='deny'){
    $sql="update blood_request set status='Rejected' where request_id='".$request_id."' ";
    mysqli_query($conn,$sql);
    header("location:view_request_list.php");

}
else{
    $sql="update blood_request set status='Approved' where request_id='".$request_id."' ";
    mysqli_query($conn,$sql);
    header("location:view_request_list.php");
}






?>