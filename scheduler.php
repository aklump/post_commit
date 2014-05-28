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
  $log->append('Gitlab user: ' . $json->user_name . ' (' . $json->user_id . ')');

  if ($conf['job_cmd'] && ($jobs = $conf['jobber']->getJobsFileHandle('a'))) {
    fwrite($jobs, $conf['job_cmd'] . PHP_EOL);
    $log->header();
    $log->append('job added: ' . $conf['job_cmd']);
  }
  fclose($jobs);
}
else {
  header("HTTP/1.0 401 Access Denied");
  $log->append('401: Access Denied');
}

$log->close();

header("Content Type: text/html");
print '<p>' . $log->view("</p>\n</p>") . '</p>' . PHP_EOL;
