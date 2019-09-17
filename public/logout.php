<?php

session_start(); //Start the session
session_destroy(); // & delete all memory (delete all cookies)
header('Location: index.php');
