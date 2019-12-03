;
var upload_qiniu_ops = {
    init:function( btn_id ,bucket ){
        bucket = ( bucket != undefined ) ? bucket : 'other';

        var that = this;
        //引入Plupload 、qiniu.js后
        Qiniu.uploader({
            multi_selection: false,
            runtimes: 'html5,flash,html4',    //上传模式,依次退化
            browse_button: 'upload',       //上传选择的点选按钮，**必需**
            uptoken_url: common_ops.buildMerchantUrl("/upload/qiniu-token",{ 'bucket':bucket }),            //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
            unique_names: true, // 默认 false，key为文件名。若开启该选项，SDK为自动生成上传成功后的key（文件名）。
            // save_key: true,   // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
            // domain: common_ops.buildCdnPicSUrl( bucket ,'',[]),   //bucket 域名，下载资源时用到，**必需**
            domain: 'http://cdn.static.test.jiatest.cn',
            get_new_uptoken: false,  //设置上传文件的时候是否每次都重新获取新的token
            container: btn_id,           //上传区域DOM ID，默认是browser_button的父元素，
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
            },
            init: {
                'FileUploaded': function(up, file, info) {
                    // var domain = up.getOption('domain');
                    // var res = parseJSON(info);
                    // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                    info = eval('(' + info + ')');
                    that.success(info.key, btn_id);
                },
                'Error': function(up, err, errTip) {
                    //上传出错时,处理相关的事情
                    that.error( errTip );
                }
            }
        });
    },
    success:function (file_key, wrapper) {
        var img_wrapper = $('#' + wrapper).parents('.layui-form-item').find('.img-wrapper');
        $('#' + wrapper + ' [name=avatar]').val(file_key);
        img_wrapper.html([
            '<div class="layui-input-block">',
                '<img width="100" height="100" src="', common_ops.buildPicStaticUrl('hsh', file_key) ,'" alt="">',
            '</div>'
        ].join(''));
    },
    error: function (errTip) {
        $.msg(errTip);
    }
};

var merchant_staff_edit_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use(['form'], function () {
            var form = layui.form;
            upload_qiniu_ops.init('upload_container','hsh');

            form.on('submit(staffFrom)', function (data) {
                var index = $.loading(1,{shade: .5});
                $.ajax({
                    type: 'post',
                    url: common_ops.buildMerchantUrl('/staff/index/save'),
                    dataType: 'json',
                    data: data.field,
                    success:function (response) {
                        $.close(index);
                        if(response.code != 200) {
                            return $.msg(response.msg);
                        }

                        return $.alert(response.msg, function () {
                            history.go(-1);
                        });
                    },
                    error: function () {
                        $.close(index);
                    }
                })

                return false;
            });
        });
    }
};


$(document).ready(function () {
    merchant_staff_edit_ops.init();
});