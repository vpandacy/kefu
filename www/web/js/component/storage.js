// 公共存储库.
var ChatStorage = {
    // 封装自己的读取和存储函数.
    setItem: function (key, value) {
        if(typeof value == 'object') {
            value = JSON.stringify(value);
        }

        if(window.localStorage) {
            localStorage.setItem(key, value);
        }

        if(parent != window) {
            this.setParentCookie(name, value);
        }

        return true;
    },
    // 获取数据对象.
    getItem: function (key, default_value) {
        var value = null,
            keys = [key];

        // 分开数据.
        if(key.indexOf('.') > 0) {
            keys = key.split('.');
        }

        key = keys.shift();

        if(window.localStorage) {
            value = localStorage.getItem(key);
        }

        if(!value && parent.document ) {
            value = this.getParentCookie(key);
        }

        if(!value) {
            return default_value;
        }

        var  source = null;
        // 自动解析数据.
        try{
            source = JSON.parse(value);
        }catch (e) {
            source = value;
        }

        if(keys.length >= 1) {
            var obj = source;
            for(var i = 0; i < keys.length; i++) {
                obj = obj[keys[i]];

                if(!obj) {
                    return default_value;
                }
            }

            return obj;
        }

        return source;
    },
    // 删除本地记录.
    removeItem: function(key) {
        return localStorage.removeItem(key);
    },
    // 删除所有的本地缓存.慎用.不推荐使用.
    clearAll: function() {
        return localStorage.clear();
    },
    // 获取cookie信息.
    getParentCookie: function(name) {
        var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");

        if(!parent.document.cookie) {
            return '';
        }
        arr = parent.document.cookie.match(reg)

        if(arr == null) {
            return ''
        }

        return unescape(arr[2]);
    },
    setParentCookie: function (name, value) {
        var now = new Date();
        // 先存个十年.
        now.setTime(now.getTime() + (3650 * 24 * 3600000));
        parent.document.cookie=name+ "=" + escape(value) + ";path=/;expires="+now.toGMTString()
        return true;
    }
};