#### 服务
##### 准备工作
```
mkdir -p /data/www/logs/kefu/pids
mkdir -p /data/www/logs/kefu/logs
```

##### 列表
```
nohup php /data/www/private_kefu/kefu/yii guest/server/run-all start >> /data/www/logs/kefu/guest_ws.log &
nohup php /data/www/private_kefu/kefu/yii cs/server/run-all start >> /data/www/logs/kefu/cs_ws.log &

nohup /bin/bash /data/www/private_kefu/kefu/console/bin/QueueManager.sh start cs/queue/push >> /data/www/logs/kefu/queue.log &
nohup /bin/bash /data/www/private_kefu/kefu/console/bin/QueueManager.sh start guest/queue/push >> /data/www/logs/kefu/queue.log &
```