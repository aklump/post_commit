<?php
global $conf;

// Add in default $conf keys.
$conf += array(
  'jobs' => array(),
  'logs_dir'      => dirname(__FILE__) . '/logs',
  // 'jobber'        => new Jobber($conf['logs_dir'] . '/pending.txt'),  
  'timezone_name' => 'UTC',
  'do' => 're',
);
