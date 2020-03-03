#!/bin/sh
echo  "\033[32m"
cmd="cd /data/www/private_deploy/kefu && git fetch --all && git rebase origin/master"
echo $cmd
eval $cmd
DATE=`date +%Y%m%d%H%M%S`
echo $DATE
echo $DATE > /data/www/release_version/version_chat