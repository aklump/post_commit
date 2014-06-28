# Post Commit
A solution to take the post commit hook from github.com and schedule, then pull that repository into your public facing website, most noteably without compromising security by giving ownership of your web files to the apache user, but scheduling cron jobs to be run by a privilaged user instead.

## Installation
1. Save the contents of this project one level above web root in _bin/post_commit_.
1. Navigate to your webroot and create a symlink to _schedule.php_: something like this:

        ln -s ../bin/post_commit/scheduler.php .

1. Make adjustements to _config.php_.
1. Set up a cron job to execute _runner.php_ (see below).
1. Create a dir called _bin/post_commit/logs_.
1. Create each of these files:

        touch complete.txt orders.txt pending.txt

1. Set the correct ownership and permissions on _bin/post_commit/logs_; they must be owned by the user that will run cron and the group must be the php user who will be executing _scheduler.php_.  Owner/Group privelages must be both RW.  Other needs no permissions.

        drwxr-xr-x 2 aklump apache 4.0K May 28 17:02 .
        drwxr-xr-x 5 aklump staff  4.0K May 28 17:01 ..
        -rw-rw---- 1 aklump apache    0 May 28 17:16 complete.txt
        -rw-rw---- 1 aklump apache    0 May 28 17:16 orders.txt
        -rw-rw---- 1 aklump apache    0 May 28 17:16 pending.txt      

1. Compile the post commit hook url and add it to your github project.  Make sure to use https.

        https://user:password@website.com/scheduler.php?key=yourprivatekeyhere

1. Now make a commit to your repo and the website should update itself.
    
## scheduler.php

This should be pinged by a post commit hook on the origin repo via https and including the secret key.

### Testing
1. Place the url you've created in a browser and look for output.
2. If the screen is white, look in your server error logs.

You can trigger a single branch response by appending the following to the url, for the purposes of testing.

    ?ref=refs/heads/master
    
## runner.php

This should be the target of a frequent cron job being run by the same user that is owns the website files and _.git_ folder.  This script processes any orders that were scheduled by _scheduler.php_.

    * * * * * /usr/bin/php /var/www/intheloftstudios.com/public_html/runner.php > /dev/null
    
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

