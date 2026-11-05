<?php
use Illuminate\Http\Request;
require __DIR__.'/../vendor/autoload.php';
(require __DIR__.'/../bootstrap/app.php')->handleRequest(Request::capture());
