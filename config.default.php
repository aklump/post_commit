<?php
/**
 * @file
 * Configuration for the deployment runenr
 *
 */
use \AKlump\PostCommit\Jobber;

global $conf;

/**
 * This is the secret key that must be passed to scheduler.php as ?key=
 *
 * @var string
 */
$conf['secret'] = 'add_some_secret_here_for_the_url';

/**
 * Defines what commands to call in response to commits
 *
 * @var array
 *   An associative array containing one or more keys which specify the
 *   name of the repository or '*' for any.
 *   Each value is an array with one or more keys describing the branch or '*'.
 *   Not all providors denote the branch so this is provider dependent.  If
 *   in doubt you should use '*' for any branch.  Finally, the values of each
 *   is an array of shell files to run when encountering this config.
 */

// This will respond to all repositories
$conf['jobs']['*'] = array(

  // For any branch commit.
  '*' => array(),

  // Jobs to schedule when the master is pushed to; remember not all providers
  // specify this information so you might just want to use '*' unless you're
  // certain of what you're doing.
  'master' => array(
    '/var/www/dev.website.org/pull_master.sh'
  ),

  // One or more commands ... to develop.
  'develop' => array(
    '/var/www/dev.website.org/pull_develop.sh'
  ),
);

// To target only a single repo by name replace '*' with repo name, e.g.,
// aklump/jquery.slim_time in this case.
$conf['jobs']['aklump/jquery.slim_time'] = array(
  'master' => array(
    '/var/www/dev.website.org/pull_master.sh'
  ),
);

//
// Advanced settings
$conf['logs_dir']       = dirname(__FILE__) . '/logs';
$conf['jobber']         = new Jobber($conf['logs_dir'] . '/pending.txt');
$conf['timezone_name']  = 'UTC';