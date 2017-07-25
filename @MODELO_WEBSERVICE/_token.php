<?php
include 'config.php';
echo ereg_replace("[^a-z]", '', base64_encode(md5( strtoupper($cliente) . 'md5#validation#check#token')));
