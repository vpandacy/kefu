#### workerman 性能优化
* 安装pcntl和posix扩展
    * php -m （看看输出有咩有 pcntl 和 posix）
    * sudo yum install php-process php-devel php-pear libevent-devel  -y
    * sudo apt-get install php-pear php7.1-dev libevent-dev -y
* 安装event扩展（支持php7，libevent不支持php）
    * pecl install event


#### 公司机器安装命令
* 安装pcntl和posix扩展
    * php -m （看看输出有咩有 pcntl 和 posix）
    * sudo yum install php72w-process php72w-devel php72w-pear libevent-devel  -y
* 安装event扩展（支持php7，libevent不支持php）
    * pecl install event
    * 切记，event.so 应该改在socket.so 之后。会出现 php_sockets_le_socket 错误


#### 参考资料
* https://blog.csdn.net/JoeBlackzqq/article/details/84941289