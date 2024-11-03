<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['jwt_key'] = $_ENV['JWT_KEY'];
$config['jwt_algorithm'] = $_ENV['JWT_ALGO']; // Algoritma yang digunakan, misalnya HS256
