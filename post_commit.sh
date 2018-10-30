#!/usr/bin/env bash

#
# @file
# Lorem ipsum dolar sit amet consectador.
#

# Define the configuration file relative to this script.
CONFIG="post_commit.core.yml";

# Uncomment this line to enable file logging.
#LOGFILE="post_commit.core.log"

# TODO: Event handlers and other functions go here or source another file.
function on_pre_config() {
    [[ "$(get_command)" == "init" ]] && handle_init
}

# Begin Cloudy Bootstrap
s="${BASH_SOURCE[0]}";while [ -h "$s" ];do dir="$(cd -P "$(dirname "$s")" && pwd)";s="$(readlink "$s")";[[ $s != /* ]] && s="$dir/$s";done;r="$(cd -P "$(dirname "$s")" && pwd)";source "$r/../../cloudy/cloudy/cloudy.sh";[[ "$ROOT" != "$r" ]] && echo "$(tput setaf 7)$(tput setab 1)Bootstrap failure, cannot load cloudy.sh$(tput sgr0)" && exit 1
# End Cloudy Bootstrap

# Input validation.
validate_input || exit_with_failure "Input validation failed."

implement_cloudy_basic

# Handle other commands.
command=$(get_command)
case $command in

    "config-check")

        # URL secret.
        eval $(get_config "url_secret")
        exit_with_failure_if_empty_config "url_secret"
        [ $url_secret == null ] && fail_because "url_secret cannot be null"
        [ ${#url_secret} -lt 8 ] && fail_because "url_secret should be longer than 8 chars"

        # Web root & symlink.
        eval $(get_config_path "web_root")
        exit_with_failure_if_config_is_not_path "web_root"
        [ -L "$web_root/scheduler.php" ] || fail_because "Missing symlink $web_root/scheduler.php"

        # Check logs dir.
        eval $(get_config_path "logs_dir")
        exit_with_failure_if_config_is_not_path "logs_dir"
        touch "$logs_dir/config-check.txt" || fail_because "Logs directory is not writable"
        has_failed || rm "$logs_dir/config-check.txt"

        has_failed && exit_with_failure "Configuration problems detected."
        exit_with_success "Config OK."
        ;;

    "empty-logs")
        php "$ROOT/reset.php" || exit_with_failure "Could not empty logs."
        exit_with_success "Logs are empty."
        ;;

    "init")
        CLOUDY_FAILED="Init failed; correct errors and run again."

        # Populate logs dir with files.
        eval $(get_config_path "logs_dir")
        exit_with_failure_if_config_is_not_path "logs_dir"
        (cd "$logs_dir" && touch complete.txt orders.txt pending.txt cron.txt) && succeed_because "Log files established." || fail_because "Could not create log files."

        # Schedule.php symlink.
        eval $(get_config_path "web_root")
        exit_with_failure_if_config_is_not_path "web_root"
        if [ ! -L "$web_root/scheduler.php" ]; then
            (cd "$web_root" && ln -s ../opt/aklump/post_commit/scheduler.php .) && succeed_because "Symlink created for scheduler.php" || fail_because "Could not create symlink to scheduler.php"
        fi

        has_failed && exit_with_failure
        exit_with_success "Initialization successfully completed."
        ;;

    "json-config")
        eval $(get_config_path_as abs_logs_dir "logs_dir")
        exit_with_failure_if_config_is_not_path "abs_logs_dir"
        json=$CLOUDY_CONFIG_JSON
        echo $json && exit 0;
        ;;

esac

throw "Unhandled command \"$command\"."
