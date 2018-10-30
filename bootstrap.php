<?php
/**
 * @file
 * Bootstrap file for post_commit PHP scripts.
 */

use AKlump\PostCommit\Jobber;

require_once dirname(__FILE__) . '/vendor/autoload.php';

global $conf;

$json = exec(__DIR__ . '/post_commit.sh get_config');
if (!$untranslated_config = json_decode($json, TRUE)) {
  throw new \RuntimeException("The configuration is corrupt.");
}
$conf = array_filter([
    'timezone_name' => $untranslated_config['timezone_name'],
    'logs_dir' => $untranslated_config['logs_dir'],
    'secret' => $untranslated_config['url_secret'],
  ]) + [
    'logs_dir' => dirname(__FILE__) . '/logs',
    'timezone_name' => 'UTC',
  ];

$conf['jobber'] = new Jobber($conf['logs_dir'] . '/pending.txt');

// Translate the YAML format to the legacy config format.
$conf['jobs'] = [];
foreach ($untranslated_config['jobs'] as $repository) {
  $actions = [];
  foreach ($repository['actions'] as $branch) {
    if (!empty($branch['branch_name']) && ($scripts = empty($branch['scripts']) ? [] : $branch['scripts'])) {
      $actions[$branch['branch_name']] = $scripts;
    }
  }
  if (count($actions)) {
    $conf['jobs'][$repository['repository_name']] = $actions;
  }
}

