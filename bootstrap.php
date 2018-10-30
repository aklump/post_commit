<?php
/**
 * @file
 * Bootstrap file for post_commit PHP scripts.
 */

use AKlump\PostCommit\Jobber;

require_once dirname(__FILE__) . '/vendor/autoload.php';

global $conf;

/**
 * Resolve a config path to it's realpath.
 *
 * @param string $path
 *   The path to resolve as relative to config_path_base.
 *
 * @return string
 *   The absolute path.
 */
function resolve($path) {
  global $untranslated_config;
  $c = $untranslated_config;
  if (substr($path, 0, 1) === '/') {
    return $path;
  }
  $path = rtrim($c['__cloudy']['ROOT'], '/') . '/' . rtrim($c['config_path_base'], '/') . '/' . trim($path, '/');
  if (!file_exists($path)) {
    return $path;
  }

  return realpath($path);
}

$json = exec(__DIR__ . '/post_commit.sh get_config');
if (!$untranslated_config = json_decode($json, TRUE)) {
  throw new \RuntimeException("The configuration is corrupt.");
}

$conf = array_filter([
    'timezone_name' => $untranslated_config['timezone_name'],
    'logs_dir' => resolve($untranslated_config['logs_dir']),
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
      $actions[$branch['branch_name']] = array_map('resolve', $scripts);
    }
  }
  if (count($actions)) {
    $conf['jobs'][$repository['repository_name']] = $actions;
  }
}
