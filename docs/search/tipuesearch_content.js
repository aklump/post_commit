var tipuesearch = {"pages":[{"title":"1.0","text":"  Changed format to be a Cloudy script. https:\/\/github.com\/aklump\/cloudy  0.4.7  Changed query string from ref=refs\/heads\/main to branch=main  0.4  Changed the format of the $conf['jobs'] array and added one more layer to support targetting single repositories.  You will need to manually update your config files. ","tags":"","url":"CHANGELOG.html"},{"title":"Post Commit","text":"    Summary  Provides an endpoint to your website to use as a webhook for git post commit hooks.  When triggered the endpoint will queue a git pull to be executed by cron.  This allows you to automate a deployment (plus whatever you want, e.g. Drupal cache clear), whenever you push to your repository.  By leveraging cron, you can keep tight permissions and run your git pull as the cron user, not, say, apache.  Intended to be used for staging websites, rather than production, where you would want to deploy manually.  Visit https:\/\/aklump.github.io\/post_commit for full documentation.  Quick Start  Part 1 of 4   Install in your repository root using cloudy pm-install aklump\/post_commit Open bin\/config\/post_commit.yml and set configuration.  I encourage you to use wildcards for the repository and branch at first to get things working; you can tighten that up later. Create the logs directory as configured in the previous step; be sure to ignore this file in SCM. Open bin\/config\/post_commit.local.yml and modify as needed; be sure to ignore this file in SCM. Modify as needed and add bin\/auto_deploy.sh to SCM. Run .\/bin\/post_commit init to finish installing. Give write permissions to both owner and group for logs\/*, e.g. chmod -R ug+w logs      Pro Tip: Run .\/bin\/post_commit config-check at any time to reveal configuration problems.   Part 2 of 4: Test Endpoint   Determine the URL endpoint of the webhook, e.g.,  https:\/\/{website}\/scheduler.php?key={url_private}  Begin monitoring the pending.txt log using tail -f pending.txt. Open the endpoint in your browser, you should see something like:  origin user: repo: branch: * PHP user: apache 127.0.0.1 Tue, 30 Oct 2018 11:25:09 -0700 jobs added: 1 --------------------------------------------------------------------------------  Assert that the absolute path to the job is appended to pending.txt. Begin monitoring the complete.txt log using tail -f complete.txt. Manually run the jobs with .\/bin\/post_commit run. Assert you see output from your job in complete.txt. If you wish to test your endpoint response to a certain repo or branch, use ?&amp;repo={repo}&amp;branch={master}.   Part 2 of 4: Register Web Hook   Log in to your server and cd to the logs directory. Begin monitoring the orders.txt log using tail -f orders.txt.  When you save your webhook below, you should see content appended--a new order--which indicates things are working correctly.  It will resemble:  $ tail -f orders.txt origin user: aklump repo: aklump\/post_commit branch: * --------------------------------------------------------------------------------  If you the site is HTTP Authorized, you will need to add credentials to the URL:  https:\/\/{user}:{password}@{website}\/scheduler.php?key={url_private}  Compile the post commit hook url and add it to your github project.   Keep the key in the url, do not use the secret textfield. Choose the json format. Make sure to use https if you can, a self-signed cert should work fine. Save the webhook and check orders.txt for a change.      Part 3 of 4: Setup cron job   Set up a cron job to execute .\/bin\/post_commit run and log the output.  * * * * * .\/bin\/post_commit run &gt;&gt; \/path\/to\/...\/logs\/cron.txt  Begin monitoring cron.txt using tail -f cron.txt.  Wait for the next cron run and assert content was appended to cron.txt. Now, test the whole setup by committing to your repo and asserting that auto_pull.sh was indeed executed by cron. Remove wildcards in the configuration repository_name and branch_name values as necessary. Disable cron logging in your crontab with:  * * * * * .\/bin\/post_commit run &gt; \/dev\/null  Lastly, run .\/bin\/post_commit empty-logs to flush all logs. You're done.   About the Log Files  Rather than deleting log files, truncate them with .\/bin\/post_commit empty-logs.  This will maintain the correct permissions.       basename   description       orders.txt   A running list of incoming pings.     last.json   The payload from the last ping.     pending.txt   Authorized jobs that are waiting to be run.     complete.txt   All jobs that have been run.  These have been moved from pending.     cron.txt   Output from the cron job that controls the runner.     Requirements   You must have Cloudy installed on your system to install this package. Your repository may not use a passphrase, as this prevents the automatic git pull.   Installation  The installation script above will generate the following structure where . is your repository root.  . \u251c\u2500\u2500 bin \u2502\u00a0\u00a0 \u251c\u2500\u2500 post_commit -&gt; ..\/opt\/post_commit\/post_commit.sh \u2502\u00a0\u00a0 \u2514\u2500\u2500 config \u2502\u00a0\u00a0     \u251c\u2500\u2500 post_commit.yml \u2502\u00a0\u00a0     \u2514\u2500\u2500 post_commit.local.yml \u251c\u2500\u2500 opt \u2502   \u251c\u2500\u2500 cloudy \u2502   \u2514\u2500\u2500 aklump \u2502       \u2514\u2500\u2500 post_commit \u2514\u2500\u2500 {public web root}   To Update   Update to the latest version from your repo root: cloudy pm-update aklump\/post_commit   Configuration Files       Filename   Description   VCS       post_commit.yml   Configuration shared across all server environments: prod, staging, dev   yes     post_commit.local.yml   Configuration overrides for a single environment; not version controlled.   no     scheduler.php   This should be pinged by a post commit hook on the origin repo via https and including the secret key.   yes     Usage   To see all commands use .\/bin\/post_commit help   Contributing  If you find this project useful... please consider making a donation.  Troubleshooting  Jobs get scheduled but not run?   Check to make sure the the logs directory and all log files are owned by the cron user and the group is the php user and that both user and group has rw permissions. Does the git repo require a passphrase for pulling?  You will have to disable that. Try logging in to the server as the cron user and running the command. Make sure you can git pull manually using your ssh keys, etc.  ","tags":"","url":"README.html"},{"title":"Search Results","text":" ","tags":"","url":"search--results.html"}]};
