<?php

function dump($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

function dumpe($data) {
    dump($data);
    die();
}

function rootDir() {
    return realpath(dirname(__FILE__) ."/../");
}