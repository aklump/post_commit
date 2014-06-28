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
$conf['secret']   = 'add_some_secret_here_for_the_url';

/**
 * Defines what commands to call in response to commits
 *
 * @var array
 *   - * array One or more commands to run for any branch commit.
 *   - {ref value} array One or more commands to run based on a commit to this
 *   branch.  This value must match the value coming in, in the json as the ref
 *   value.
 */
$conf['jobs'] = array(

  // For any branch commit.
  '*' => array(
    
  ),

  // One or more commands to execute if the commit was made to master.
  'refs/heads/master' => array(
    '/var/www/dev.website.org/pull_master.sh'
  ),

  // One or more commands ... to develop.
  'refs/heads/develop' => array(
    '/var/www/dev.website.org/pull_develop.sh'
  ),
);

//
// Advanced settings
$conf['logs_dir']       = dirname(__FILE__) . '/logs';
$conf['jobber']         = new Jobber($conf['logs_dir'] . '/pending.txt');
$conf['timezone_name']  = 'UTC';