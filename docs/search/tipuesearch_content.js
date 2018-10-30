var tipuesearch = {"pages":[{"title":"1.0","text":"  Changed format to be a Cloudy script. https:\/\/github.com\/aklump\/cloudy  0.4.7  Changed query string from ref=refs\/heads\/main to branch=main  0.4  Changed the format of the $conf['jobs'] array and added one more layer to support targetting single repositories.  You will need to manually update your config files. ","tags":"","url":"CHANGELOG.html"},{"title":"Post Commit","text":"    Summary  Provides an endpoint to your website to use as a webhook for git post commit hooks.  When triggered the endpoint will queue a git pull to be executed by cron.  This allows you to automate a deployment (plus whatever you want, e.g. Drupal cache clear), whenever you push to your repository.  By leveraging cron, you can keep tight permissions and run your git pull as the cron user, not, say, apache.  Intended to be used for staging websites, rather than production, where you would want to deploy manually.  Visit https:\/\/aklump.github.io\/post_commit for full documentation.  Quick Start   Install in your repository root using cloudy pm-install aklump\/post_commit Open bin\/config\/post_commit.yml and set configuration. Create the logs directory as configured in the previous step; be sure to ignore this file in SCM. Open bin\/config\/post_commit.local.yml and modify as needed; be sure to ignore this file in SCM. Modify as needed and add bin\/auto_deploy.sh to SCM. Run .\/bin\/post_commit init to finish installing. Give write permissions to both owner and group for logs\/*, e.g. chmod -R ug+w logs      Pro Tip: Run .\/bin\/post_commit config-check at any time to reveal configuration problems.   GitHub: Register Web Hook   Log in to your server and cd to the logs directory. Begin monitoring the orders.txt log using tail -f orders.txt.  When you save your webhook below, you should see content appended--a new order--which indicates things are working correctly.  It will resemble:  $ tail -f orders.txt origin user: aklump repo: aklump\/post_commit branch: * --------------------------------------------------------------------------------  Compile the post commit hook url and add it to your github project.      https:\/\/{user}:{password}@{website}\/scheduler.php?key={yourprivatekeyhere}    Keep the key in the url, do not use the secret textfield. Choose the json format. Make sure to use https if you can, a self-signed cert should work fine. Save the webhook and check orders.txt for a change.      Setup cron job   Set up a cron job to execute runner.php.   This should be the target of a frequent cron job being run by the same user that owns the website files and .git folder.  This script processes any orders that were scheduled by scheduler.php.  You can redirect the output to logs\/cron.txt file during setup, and once all is working you should consider doing like the second example which does not capture the output.  * * * * * \/usr\/bin\/php \/home\/user\/mysite\/opt\/post_commit\/runner.php &gt;&gt; \/home\/user\/mysite\/opt\/post_commit\/logs\/cron.txt   Example 2, no log of cron jobs, better once all is working correctly.  You might want to delete logs\/cron.txt at this point.  * * * * * \/usr\/bin\/php \/home\/user\/mysite\/opt\/post_commit\/runner.php &gt; \/dev\/null   Requirements   You must have Cloudy installed on your system to install this package. Your repository may not use a passphrase, as this prevents the automatic git pull.   Installation  The installation script above will generate the following structure where . is your repository root.  . \u251c\u2500\u2500 bin \u2502\u00a0\u00a0 \u251c\u2500\u2500 post_commit -&gt; ..\/opt\/post_commit\/post_commit.sh \u2502\u00a0\u00a0 \u2514\u2500\u2500 config \u2502\u00a0\u00a0     \u251c\u2500\u2500 post_commit.yml \u2502\u00a0\u00a0     \u2514\u2500\u2500 post_commit.local.yml \u251c\u2500\u2500 opt \u2502   \u251c\u2500\u2500 cloudy \u2502   \u2514\u2500\u2500 aklump \u2502       \u2514\u2500\u2500 post_commit \u2514\u2500\u2500 {public web root}   To Update   Update to the latest version from your repo root: cloudy pm-update aklump\/post_commit   Configuration Files       Filename   Description   VCS       post_commit.yml   Configuration shared across all server environments: prod, staging, dev   yes     post_commit.local.yml   Configuration overrides for a single environment; not version controlled.   no     scheduler.php   This should be pinged by a post commit hook on the origin repo via https and including the secret key.   yes     Usage   To see all commands use .\/bin\/post_commit help   Contributing  If you find this project useful... please consider making a donation.  Testing   Place the url you've created in a browser and look for output. If the screen is white, look in your server error logs.   You can test a single repository by appending the following to the url, for the purposes of testing.  &amp;repo=jquery.slim_time   You can trigger a single branch response by appending the following to the url, for the purposes of testing.  &amp;branch=master   orders.txt  You can truncate all text files without messing up perms using reset.php.  A log of incoming orders post commit hooks.  complete.txt  A log of runner.php.  pending.txt  The commands that have not yet been executed by cron.  This file should get emptied at each cron run.  To dump all logs and cancel standing orders  Don't just delete the log files as permissions are bit tricky; instead use the reset script.  php reset.php   Troubleshooting  Jobs get scheduled but not run?   Check to make sure the the logs directory and all log files are owned by the cron user and the group is the php user and that both user and group has rw permissions. Does the git repo require a passphrase for pulling?  You will have to disable that. Try logging in to the server as the cron user and running the command. Make sure you can git pull manually using your ssh keys, etc.  ","tags":"","url":"README.html"},{"title":"Search Results","text":" ","tags":"","url":"search--results.html"}]};
