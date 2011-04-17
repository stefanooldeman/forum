<?php
//get autclass from the ServiceContainer and make the call.
$authClass = $sc->get('auth_class');
$authClass->forget();
redirect_to('index');
