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
 * This is command that will ultimately be called on post commit hooks.
 *
 * @var string
 */
$conf['job_cmd']  = 'some/cmd/to/schedule';

//
// Advanced settings
$conf['logs_dir'] = dirname(__FILE__) . '/logs';
$conf['jobber']   = new Jobber($conf['logs_dir'] . '/pending.txt');
