# Post Commit
A solution to take the post commit hook from github.com and schedule, then pull that repository into your public facing website, most noteably without compromising security by giving ownership of your web files to the apache user, but scheduling cron jobs to be run by a privilaged user instead.

## Installation
1. Save the contents of this project one level above web root in _bin/post_commit_.
1. Move _scheduler.php_ into the webroot of your server and modify the line below to point to the correct _autoload.php_ file.
        
        require_once dirname(__FILE__) . '/../bin/post_commit/vendor/autoload.php';

1. Make adjustements to _config.php_.
1. Set up a cron job to execute _runner.php_ (see below).
1. Set the correct permissions on _bin/post_commit/logs_; they must be owned by the user that will run cron and the group must be the php user who will be executing _scheduler.php_.  Owner/Group privelages must be both RW.

        drwxr-xr-x 2 aklump apache 4.0K May 28 17:02 .
        drwxr-xr-x 5 aklump staff  4.0K May 28 17:01 ..
        -rw-rw-r-- 1 aklump apache    0 May 28 17:16 complete.txt
        -rw-rw-r-- 1 aklump apache    0 May 28 17:16 orders.txt
        -rw-rw-r-- 1 aklump apache    0 May 28 17:16 pending.txt      

1. Compile the post commit hook url and add it to your github project.  Make sure to use https.

        https://user:password@website.com/scheduler.php?key=yourprivatekeyhere

1. Now make a commit to your repo and the website should update itself.
    
## scheduler.php

This should be pinged by a post commit hook on the origin repo via https and including the secret key.

### Testing
You can trigger a single branch response by appending the following to the url, for the purposes of testing.

    ?ref=refs/heads/master
    
## runner.php

This should be the target of a frequent cron job being run by the same user that is owns the website files and _.git_ folder.  This script processes any orders that were scheduled by _scheduler.php_.

    * * * * * /usr/bin/php /var/www/intheloftstudios.com/public_html/runner.php > /dev/null
    
## orders.txt

A log of incoming orders post commit hooks.

## complete.txt

A log of _runner.php_.

## pending.txt

The commands that have not yet been executed by cron.  This file should get emptied at each cron run.

## To dump all logs and cancel standing orders
Don't just delete the log files as permissions are bit tricky; instead use the reset script.

    php reset.php
