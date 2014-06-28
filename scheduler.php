<?php
/**
 * @file
 * Ping handler for gitlab.com post-commit hook to schedule a develop pull job.
 */
use \AKlump\PostCommit\Logger;
require_once dirname(__FILE__) . '/../bin/post_commit/vendor/autoload.php';

// Make a note that we got hit.
$log = new Logger($conf['logs_dir'] . '/orders.txt');

// Access check
$access = !empty($_GET['key']) && $_GET['key'] === $conf['secret'];

// If access then we'll schedule the job.
if ($access) {

  $json = json_decode(file_get_contents("php://input"));

  // Allow testing in the url via ?ref=refs/heads/master.
  if (isset($_GET['ref'])) {
    $json->ref = $_GET['ref'];
  }

  $log->append('Gitlab user: ' . $json->user_name . ' (' . $json->user_id . ')');
  $log->append('ref: ' . $json->ref);

  // Legacy support from version 0.2
  if (isset($conf['job_cmd'])) {
    $conf['job_cmds']['*'][] = $conf['job_cmd'];
  }

  $total_added = 0;
  if (!($jobs = $conf['jobber']->getJobsFileHandle('a'))) {
    $log->append('Invalid log configuration.');
  }
  elseif (!empty($conf['job_cmds']) && $jobs) {
    foreach ($conf['job_cmds'] as $group => $commands) {
      if ($group === '*' || $group === $json->ref) {
        foreach ($commands as $cmd) {
          fwrite($jobs, $cmd . PHP_EOL);
          $log->header();
          ++$total_added;
        }
      }
    }
  }
  fclose($jobs);

  if ($total_added) {
    $log->append('jobs added: ' . $total_added);
  }
}
else {
  header("HTTP/1.0 401 Access Denied");
  $log->append('401: Access Denied');
}

$log->close();

header("Content Type: text/html");
print '<p>' . $log->view("</p>\n</p>") . '</p>' . PHP_EOL;
