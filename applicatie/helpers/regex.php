<?php

$emailRegex = "[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}(?:\.[a-zA-Z]{2,})*";
$passwordRegex = "^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{7,}$";
$phoneRegex = "\+?(( |-)?[0-9]){10,15}";
$accountNumberRegex = "[0-9]{7}";
$creditCardRegex = "([0-9]{4}[- ]){3}[0-9]{4}";
