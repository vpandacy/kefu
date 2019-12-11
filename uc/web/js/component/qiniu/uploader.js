;
var uploader = {
    init:function( btn_wrapper, btn, depend, bucket, other_config){
        if(!depend.hasOwnProperty('uploadSuccess') || typeof depend.uploadSuccess != 'function') {
            throw "请正确配置uploadSuccess方法"
        }

        if(!depend.hasOwnProperty('uploadError') || typeof depend.uploadError != 'function') {
            throw '未正确配置uploadError方法.'
        }

        var config = this.genConfig(btn_wrapper, btn, bucket);

        if(other_config) {
            // 加入配置信息.
            config = Object.assign({}, config, other_config);
        }

        // 设置回调.
        config.init = {
            'FileUploaded': function(up, file, info) {
                // var domain = up.getOption('domain');
                // var res = parseJSON(info);
                // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                info = eval('(' + info + ')');
                // 这里要设置一下this对应作用域.
                depend.uploadSuccess(info.key, btn_wrapper);
            },
            'Error': function(up, err, errTip) {
                //上传出错时,处理相关的事情
                depend.uploadError( up, err, errTip );
            }
        };

        //引入Plupload 、qiniu.js后
        Qiniu.uploader(config);
    },

    // 生成基本配置信息.
    genConfig: function(btn_wrapper, btn, bucket) {
        bucket = ( bucket != undefined ) ? bucket : 'other';

        return {
            multi_selection: false,
            runtimes: 'html5,flash,html4',    //上传模式,依次退化
            browse_button: btn,       //上传选择的点选按钮，**必需**
            uptoken_url: common_ops_url.buildUrl("/upload/qiniu-token",{ 'bucket':bucket }),            //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
            unique_names: true, // 默认 false，key为文件名。若开启该选项，SDK为自动生成上传成功后的key（文件名）。
            // save_key: true,   // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
            // domain: common_ops.buildCdnPicSUrl( bucket ,'',[]),   //bucket 域名，下载资源时用到，**必需**
            domain: 'http://cdn.static.test.jiatest.cn',
            get_new_uptoken: false,  //设置上传文件的时候是否每次都重新获取新的token
            container: btn_wrapper,           //上传区域DOM ID，默认是browser_button的父元素，
            max_file_size: '1mb',           //最大文件体积限制
            flash_swf_url: '/js/component/qiniu/plupload/Moxie.swf',  //引入flash,相对路径
            max_retries: 3,                   //上传失败最大重试次数
            dragdrop: true,                   //开启可拖曳上传
            drop_element: 'container',        //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
            chunk_size: '1mb',                //分块上传时，每片的体积
            auto_start: true,                 //选择文件后自动上传，若关闭需要自己绑定事件触发上传
            filters: {
                mime_types : [
                    {title : "Image files", extensions: "jpg,jpeg,gif,png"}
                ]
            }
        };
    }
};