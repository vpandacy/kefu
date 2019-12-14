#### 服务
##### 准备工作
```
mkdir -p /data/www/logs/kefu/pids
mkdir -p /data/www/logs/kefu/logs
```

##### 列表
```
nohup php yii /data/www/private_kefu/kefu/guest/server/run-all start >> /data/www/logs/kefu/guest_ws.log &
nohup php yii /data/www/private_kefu/kefu/cs/server/run-all start >> /data/www/logs/kefu/cs_ws.log &
```