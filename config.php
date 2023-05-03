<?php
$config = array (
  'antibrute_config' => 
  array (
    'max_query_attempts' => 30,
    'query_attempts_timestamp' => -60,
    'blocked_ip_timestamp' => -3600,
  ),
);
return $config;
