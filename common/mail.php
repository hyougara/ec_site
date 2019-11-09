<?php

session_start();
session_regenerate_id(true);
require_once '../common/debug.php';// for debug
require_once '../common/function.php';// for user-defined function

sendMail();