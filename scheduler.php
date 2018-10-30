<?php
/**
 * @file
 * Ping handler for post-commit hook to schedule a develop pull job.
 */

namespace AKlump\PostCommit;

global $conf;
require_once dirname(__FILE__) . '/bootstrap.php';

// Make a note that we got hit.
$log = new Logger($conf['logs_dir'] . '/orders.txt');

// Access check
$access = !empty($_GET['key']) && $_GET['key'] === $conf['secret'];

// If access then we'll schedule the job.
if ($access) {

  $contents = file_get_contents("php://input");

  // Dump this for troubleshooting purposes.
  file_put_contents($conf['logs_dir'] . '/last.json', $contents);

  $translators = array();
  $translators[] = new GitHub($contents);
  $translators[] = new GitLab($contents);
  foreach ($translators as $data) {
    if ($data->isUnderstood()) {
      break;
    }
  }

  // Allow testing in the url via ?ref=refs/heads/master.
  if (isset($_GET['branch'])) {
    $data->setBranch($_GET['branch']);
  }
  elseif (isset($_GET['ref'])) {
    $data->setBranch($_GET['ref']);
  }
  if (isset($_GET['repo'])) {
    $data->setRepoName($_GET['repo']);
  }

  $config = new Config($conf);
  $config
    ->setBranch($data->getBranch())
    ->setName($data->getRepoName());

  $log->append('origin user: ' . $data->getUsername());
  $log->append('repo: ' . $data->getRepoName());
  $log->append('branch: ' . $data->getBranch());

  $total_added = 0;
  if (!($jobs = $conf['jobber']->getJobsFileHandle('a'))) {
    $log->append('Invalid log configuration.');
  }
  elseif ($jobs && ($commands = $config->getJobs())) {
    foreach ($commands as $cmd) {
      fwrite($jobs, $cmd . PHP_EOL);
      $log->header();
      ++$total_added;
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

header("Content-Type: text/html");
print '<p>' . $log->view("</p>\n</p>") . '</p>' . PHP_EOL;
