#!/bin/bash
#echo "TaskManager monitor v1.0"


SCRIPTS=("news" "media-import" "staff-head" "message-import" "message-cal" "merchant" "sync-message-board" "merchant-notice" "kf-message-push" "uc/attendance-import" "dw/kf-message-push" "dw/media-import" "dw/merchant" "dw/merchant-notice" "dw/message-cal" "dw/message-import" "dw/news");

BASE_DIR=$(dirname $(dirname $(cd "$(dirname "$0")"; pwd)))"/";
LOG_DIR="/data/www/logs/jobs/queue/";

L=${#SCRIPTS[*]}

C=$#
S=$0

show_help() {

    echo -e "\nusage:\n"
    echo -e "$0 [start|stop] [script1] [script2] [script3] [...]"

    echo -e "\n\nexamples:\n"
    echo -e "\tcd $LOG_DIR\n"
    echo -e "\tnohup sh $0 start &"
    echo -e "\tnohup sh $0 start dm1 &"
    echo -e "\tnohup sh $0 start dm1 dm2 &"

    echo -e "\t$0 stop"
    echo -e "\t$0 stop dm1"
    echo -e "\t$0 stop dm1 dm2"

    echo $1
    exit
}

in_scripts() {

    i=0
    while [ $i -lt $L ]
    do
        if [ "${SCRIPTS[$i]}" = "$1" ]; then
            return 1
        fi

        let i++
    done

    return 0
}

if [ $C -lt 1 ]; then
   show_help
fi

# validate params
if [ $C -gt 1 ]; then

    for ((m=2;m<=$#;m++)); do
        eval "tmp=`echo \\$$m`"
        in_scripts $tmp

        if [ "$?" = "0" ]; then
            echo -e "\ndameon script:$tmp is not defined in SCRIPTS"
            exit
        fi
        #shift
    done
fi

if [ "$1" = "stop" ]; then

    # stop all
    if [ "$C" = "1" ]; then
        rm -f $LOG_DIR*.pid
        echo "stop ok!"
        exit
    else

        for((n=2;n<=$#;n++)); do
            eval "tmp=`echo \\$$n`"
            pid_file=${tmp//\//_}
            pid_path="${LOG_DIR}pids/${pid_file}.pid"
            rm -f $pid_path
            echo "stop $pid_file ok!"
            #shift
        done
    fi
fi

if [ "$1" = "start" ]; then

    #start all
    if [ "$C" = "1" ]; then
        i=0
        while [ $i -lt $L ]
        do
            /bin/bash $S start ${SCRIPTS[$i]} &
            let i++
        done
    fi

    #start one
    if [ "$C" = "2" ]; then
        pid_file=${2//\//_}
        pid_path="${LOG_DIR}pids/${pid_file}.pid"
        job_log_path="${LOG_DIR}logs/${pid_file}.log"
        if [ -e $pid_path ]; then
            echo "$2 is already running"
            exit
        else
            echo "start runing $2..."
            touch $pid_path
        fi

        #do running!
        while [ -e $pid_path ]; do
            #php "$BASE_DIR""yii" "queue/"$2"/start" "$LOG_DIR""log/"
            current_full_cmd="php ${BASE_DIR}yii queue/$2/start >> ${job_log_path}"
            echo ${current_full_cmd}
            eval $current_full_cmd
            sleep 1
        done
    fi
fi
