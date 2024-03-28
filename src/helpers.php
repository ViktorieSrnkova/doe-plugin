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

function rootDir(): string {
    return realpath(dirname(__FILE__) ."/../");
}

function rootUrl(): string {
	return plugin_dir_url(dirname(__FILE__));
}
