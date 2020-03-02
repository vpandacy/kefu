#!/bin/sh
BASE_DIR=$(dirname $(dirname $(cd "$(dirname "$0")"; pwd)))"/";
YIIC="${BASE_DIR}yii"
cmd=$1
while true
do
    if [ $(ps -ef |grep  "/usr/bin/php $YIIC $cmd" |grep -v grep|wc -l) -eq 0 ];then
        current_full_cmd="/usr/bin/php $YIIC $cmd "
        echo ${current_full_cmd}
        eval $current_full_cmd
    else
        echo 'queue is Running';
    fi
    sleep 3;
done