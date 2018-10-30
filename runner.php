<?php
/**
 * @file
 * Job runner for processes in pending jobs file.
 *
 * Hit this with cron on a pretty frequent basis.
 */

use AKlump\PostCommit\Logger;

global $conf;
require_once dirname(__FILE__) . '/bootstrap.php';

if (!($jobs = $conf['jobber']->hasJobs())) {
  echo 'No jobs pending.';
  exit(0);
}
elseif (!($fh = $conf['jobber']->getJobsFileHandle('r+'))) {
  echo 'Already processing jobs. Abort.';
  exit(1);
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

exit(0);
