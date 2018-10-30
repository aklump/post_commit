# Post Commit

![post_commit](images/screenshot.jpg)

## Summary

Provides an endpoint to your website to use as a webhook for git post commit hooks.  When triggered the endpoint will queue a `git pull` to be executed by cron.  This allows you to automate a deployment (plus whatever you want, e.g. Drupal cache clear), whenever you push to your repository.  By leveraging cron, you can keep tight permissions and run your git pull as the cron user, not, say, `apache`.  Intended to be used for staging websites, rather than production, where you would want to deploy manually.

**Visit <https://aklump.github.io/post_commit> for full documentation.**

## Quick Start

1. Install in your repository root using `cloudy pm-install aklump/post_commit`
1. Open _bin/config/post_commit.yml_ and set configuration.
1. Create the _logs_ directory as configured in the previous step; be sure to **ignore this file** in SCM.
1. Open _bin/config/post_commit.local.yml_ and modify as needed; be sure to **ignore this file** in SCM.
1. Modify as needed and add _bin/auto_deploy.sh_ to SCM.
1. Run `./bin/post_commit init` to finish installing.
1. Give write permissions to both owner and group for _logs/*_, e.g. `chmod -R ug+w logs`

> Pro Tip: Run `./bin/post_commit config-check` at any time to reveal configuration problems.

### GitHub: Register Web Hook

1. Log in to your server and `cd` to the logs directory.
1. Begin monitoring the _orders.txt_ log using `tail -f orders.txt`.  When you save your webhook below, you should see content appended--a new order--which indicates things are working correctly.  It will resemble:

        $ tail -f orders.txt
        origin user: aklump
        repo: aklump/post_commit
        branch: *
        --------------------------------------------------------------------------------

1. Compile the post commit hook url and add it to your github project.

            https://{user}:{password}@{website}/scheduler.php?key={yourprivatekeyhere}
            
    * Keep the key in the url, do not use the secret textfield.
    * Choose the json format.
    * Make sure to use https if you can, a self-signed cert should work fine.
    * Save the webhook and check _orders.txt_ for a change.


![GitHub Webhook](images/webhook.png)

### Setup cron job

1. Set up a cron job to execute `runner.php`.

This should be the target of a frequent cron job being run by the same user that owns the website files and _.git_ folder.  This script processes any orders that were scheduled by _scheduler.php_.  You can redirect the output to `logs/cron.txt` file during setup, and once all is working you should consider doing like the second example which does not capture the output.

    * * * * * /usr/bin/php /home/user/mysite/opt/post_commit/runner.php >> /home/user/mysite/opt/post_commit/logs/cron.txt
    

Example 2, no log of cron jobs, better once all is working correctly.  You might want to delete `logs/cron.txt` at this point.

    * * * * * /usr/bin/php /home/user/mysite/opt/post_commit/runner.php > /dev/null
            
## Requirements

* You must have [Cloudy](https://github.com/aklump/cloudy) installed on your system to install this package.

* Your repository may not use a passphrase, as this prevents the automatic git pull.

## Installation

The installation script above will generate the following structure where `.` is your repository root.

    .
    ├── bin
    │   ├── post_commit -> ../opt/post_commit/post_commit.sh
    │   └── config
    │       ├── post_commit.yml
    │       └── post_commit.local.yml
    ├── opt
    │   ├── cloudy
    │   └── aklump
    │       └── post_commit
    └── {public web root}

    
### To Update

- Update to the latest version from your repo root: `cloudy pm-update aklump/post_commit`

## Configuration Files

| Filename | Description | VCS |
|----------|----------|---|
| _post_commit.yml_ | Configuration shared across all server environments: prod, staging, dev  | yes |
| _post_commit.local.yml_ | Configuration overrides for a single environment; not version controlled. | no |
| scheduler.php | This should be pinged by a post commit hook on the origin repo via https and including the secret key.  | yes  |

## Usage

* To see all commands use `./bin/post_commit help`

## Contributing

If you find this project useful... please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4E5KZHDQCEUV8&item_name=Gratitude%20for%20aklump%2Fpost_commit).



### Testing

1. Place the url you've created in a browser and look for output.
2. If the screen is white, look in your server error logs.

You can test a single repository by appending the following to the url, for the purposes of testing.

    &repo=jquery.slim_time

You can trigger a single branch response by appending the following to the url, for the purposes of testing.

    &branch=master
    

## orders.txt

**You can truncate all text files without messing up perms using _reset.php_.**

A log of incoming orders post commit hooks.

## complete.txt

A log of _runner.php_.

## pending.txt

The commands that have not yet been executed by cron.  This file should get emptied at each cron run.

## To dump all logs and cancel standing orders

Don't just delete the log files as permissions are bit tricky; instead use the reset script.

    php reset.php

## Troubleshooting

### Jobs get scheduled but not run?

1. Check to make sure the the logs directory and all log files are owned by the cron user and the group is the php user and that both user and group has rw permissions.

1. Does the git repo require a passphrase for pulling?  You will have to disable that.

1. Try logging in to the server as the cron user and running the command.

1. Make sure you can git pull manually using your ssh keys, etc.

