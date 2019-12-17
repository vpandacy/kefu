;
var upload_qiniu_ops = {
    /**
     * 初始化项目.
     * @param btn_id    事件发生源.ID选择器.
     * @param bucket
     * @param config    配置
     * @param is_over_write 是否重写配置.
     */
    init:function( btn_id ,bucket ,config,is_over_write){
        bucket = (bucket!=undefined)?bucket:'hsh';
        config = (config == undefined || this.isEmpty(config)) ? {} : config;
        // 是否覆盖配置.
        is_over_write = (is_over_write == undefined) ? true : is_over_write;
        var base_config = {
            browse_button: btn_id,       //上传选择的点选按钮，**必需**
            uptoken_url: financial_common_ops.buildUrl("/upload/qiniu-token",{ 'bucket':bucket }),            //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
            dragdrop: true,
            drop_element: btn_id + '_upload_container',
            domain: financial_common_ops.buildUrl( bucket ,'',[]),   //bucket 域名，下载资源时用到，**必需**
            container: btn_id + '_upload_container',           //上传区域DOM ID，默认是browser_button的父元素，
            init: {
                'FileUploaded': function(up, file, info) {
                    // var domain = up.getOption('domain');
                    // var res = parseJSON(info);
                    // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                    info = eval('(' + info + ')');
                    // {hash: "FrAZkiXkV8u__mivRfUnDeJ8r_jS", key: "o_1cvdi1558trmllqa5i1lve8s37.jpg"}
                    upload.success( info.key,btn_id );
                },
                'Error': function(up, err, errTip) {
                    //上传出错时,处理相关的事情
                    upload.error( errTip );
                }
            },
        };
        config = this.assign(config,base_config,is_over_write);
        //引入Plupload 、qiniu.js后
        var uploader = Qiniu.uploader(this.genConfig(config,is_over_write));
        // 防止外部需要示例.
        return uploader;
    },
    /**
     * 生成配置.
     * @param config            配置.
     * @param is_over_write     是否覆盖.
     * @returns object
     */
    genConfig:function(config,is_over_write){
        var base_config = {
                multi_selection: false,
                runtimes: 'html5,flash,html4',    //上传模式,依次退化
                unique_names: true, // 默认 false，key为文件名。若开启该选项，SDK为自动生成上传成功后的key（文件名）。
                // save_key: true,   // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
                get_new_uptoken: false,  //设置上传文件的时候是否每次都重新获取新的token
                max_file_size: '5mb',           //最大文件体积限制
                flash_swf_url: '/js/component/qiniu/plupload/Moxie.swf',  //引入flash,相对路径
                max_retries: 3,                   //上传失败最大重试次数
                dragdrop: true,                   //开启可拖曳上传
                drop_element: 'container',        //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                //分块上传时，每片的体积
                auto_start: true,                 //选择文件后自动上传，若关闭需要自己绑定事件触发上传
                filters: {
                    mime_types : [
                        {title : "Image files", extensions: "jpg,jpeg,gif,png"}
                    ]
                }
        };
        if(this.isEmpty(config))
        {
            return base_config;
        }

        return this.assign(base_config,config,is_over_write);
    },
    isEmpty:function(target){
        for(var i in target){
            return !i;
        }
        return true;
    },
    // 复制对象.源于Object.assign有兼容问题. 只是简单的一层拷贝.
    assign:function(target,clone,is_over_write){
        if(this.isEmpty(clone)){
            return target;
        }
        for(var key in clone)
        {
            if(!target.hasOwnProperty(key))
            {
                target[key] = clone[key];
            }
            else
            {
                if(is_over_write)
                {
                    target[key] = clone[key];
                }
            }
        }
        return target;
    }
};

var copy_upload_ops = {
    bindPaste:function( target_id ){
        //需要判断浏览器的兼容做法
        if( this.isChrome() ){
            document.addEventListener('paste', this.copyFun);
        }else if( target_id != undefined ){
            document.getElementById(target_id).addEventListener('paste', this.copyFun);
        }

    },
    unBindPaste:function( target_id ){
        if( this.isChrome() ){
            document.removeEventListener('paste',this.copyFun);
        }else if( target_id != undefined ){
            document.getElementById(target_id).removeEventListener('paste', this.copyFun);
        }
    },
    isChrome:function(){
        var ua = navigator.userAgent;
        return ( ua.indexOf("Chrome") > -1 );
    },
    copyFun:function( e ){

        if (e.clipboardData && e.clipboardData.items[0].type.indexOf('image') > -1) {
            var file_target = e.clipboardData.items[0].getAsFile();
            $.ajax({
                url:financial_common_ops.buildUrl("/upload/qiniu-token",{ 'bucket':"hsh" }),
                dataType:"json",
                success:function( res ){
                    var form_data = new FormData();
                    form_data.append('file', file_target );
                    form_data.append('token', res.uptoken );
                    $.ajax({
                        url: "http://up-z2.qiniu.com/",
                        data: form_data,
                        type: 'post',
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        // xhrFields: {
                        //     withCredentials: true
                        // },
                        // crossDomain: true,
                        success: function (data) {
                            upload.success( data.key );
                        },
                        error: function (e, msg) {
                            upload.success( msg );
                        }
                    });
                }
            });
        }
    }
};
