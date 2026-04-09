<?php

if (isset($_POST['signup'])) {
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];


    // Validations
    if (empty($fname) && empty($mname) && empty($lname)) {
        $response = array(
            "error" => "Fields are empty!",
        );
    } else if (empty($fname)) {
        $response = array(
            "error" => "First name is empty",
        );
    } else if (empty($mname)) {
        $response = array(
            "error" => "Middle name is empty!",
        );
    } else if (empty($lname)) {
        $response = array(
            "error" => "Last name is empty!",
        );
    }else{
        //true
        $response = array(
            "success" => "You are now registered!",
        );
    }

    echo json_encode($response);
    exit;
}
