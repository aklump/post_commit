<?php
/**
 * @file
 * Truncates all .txt files in the logs directory without deleting them or
 * changing permissions/ownership.
 */

use AKlump\PostCommit\Logger;

global $conf;
require_once dirname(__FILE__) . '/bootstrap.php';

foreach (glob($conf['logs_dir'] . '/*.txt') as $file) {
  $log = new Logger($file);
  $log->truncate();
}
exit(0);
