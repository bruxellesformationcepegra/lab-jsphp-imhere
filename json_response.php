<?php

    function json_success(){
        echo json_encode(array('message' => 'success'));
        exit();
    }

    function json_mail_error($error){
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array('message' => "Email could not be sent", 'detail'=>$error));
        exit();
    }

    function json_parameter_error($param){
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array('message' => "Parameter '$param' is missing or invalid"));
        exit();
    }

?>