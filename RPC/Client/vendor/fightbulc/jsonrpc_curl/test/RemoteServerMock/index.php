<?php

    $json = file_get_contents('php://input');
    $requestArray = json_decode($json, TRUE);
    echo json_encode(['id' => $requestArray['id'], 'result' => 'Hello World']);
