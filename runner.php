<?php
/**
 * @file
 * Job runner for processes in pending jobs file.
 *
 * Hit this with cron on a pretty frequent basis.
 */
use \AKlump\PostCommit\Logger;
require_once dirname(__FILE__) . '/vendor/autoload.php';

if (!($jobs = $conf['jobber']->hasJobs())) {
  die('No jobs pending.' . PHP_EOL);
}
elseif (!($fh = $conf['jobber']->getJobsFileHandle('r+'))) {
  die('Already processing jobs. Abort.' . PHP_EOL);
}

$log = new Logger($conf['logs_dir'] . '/complete.txt');

while ($cmd = $conf['jobber']->takeNextJob()) {
  $log->append(str_repeat('=', 80));
  $now = new \DateTime('now', new \DateTimeZone($conf['timezone_name']));
  $log->append($now->format('r'));
  $log->append($cmd);
  $log->append(str_repeat('-', 80));
  print $log->view();

  $process_handle = popen("$cmd 2>&1", 'r');
  if ($result = stream_get_contents($process_handle)) {
    $log->append($result);  
  }
  pclose($process_handle);

  $log->close();
}

exit;
