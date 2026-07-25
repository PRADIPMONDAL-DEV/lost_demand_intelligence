<?php
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "lost_demand_intelligence"
);

if($conn->connect_error){
    die("Connection Failed : ".$conn->connect_error);
}