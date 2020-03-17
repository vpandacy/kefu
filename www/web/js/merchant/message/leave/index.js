;
var leave_message_ops = {
    init: function () {
        this.eventBind();
        this.dateComponent();
    },
    eventBind: function () {
        var table = layui.table;
        //转换静态表格
        table.init('leave_message');

        $(".leave_message_list .ops").click( function(){
            var id = $(this).data("id");
            var callback = {
                "ok":function(){
                    $.ajax({
                        type: 'POST',
                        url: merchant_common_ops.buildMerchantUrl('/message/leave/ops'),
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success:function (res) {
                            var callback = res.code != 200 ? null: function () {
                                window.location.href = window.location.href;
                            };
                            return $.msg(res.msg, res.code ==200, callback);
                        }
                    });
                }
            };
            $.confirm( "确认标记为已处理？？",callback );
        });
    },
    dateComponent: function () {
        var that = this;
        //date range picker
        $(".wrap_search input[name=date_range_picker]").dateRangePicker({
            autoClose: false,
            format: 'YYYY-MM-DD',
            separator: ' 至 ',
            language: 'cn',
            startOfWeek: 'monday',// or monday
            getValue: function () {
                return $(this).val();
            },
            setValue: function (s, start, end) {
                if (!$(this).attr('readonly') && !$(this).is(':disabled') && s != $(this).val()) {
                    $(this).val(s);
                }
                $(".wrap_search input[name=date_from]").val(start);
                $(".wrap_search input[name=date_to]").val(end);
            },
            startDate: false,
            endDate: false,
            time: {
                enabled: false
            },
            minDays: 0,
            maxDays: 0,
            showShortcuts: true,
            shortcuts: null,
            customShortcuts: [
                {
                    name: '前一天',
                    dates: function () {
                        var start = moment().subtract(1, "d").toDate();
                        var end = moment().toDate();
                        return [start, end];
                    }
                },
                {
                    name: '前三天',
                    dates: function () {
                        var start = moment().subtract(3, "d").toDate();
                        var end = moment().toDate();
                        return [start, end];
                    }
                },
                {
                    name: '前五天',
                    dates: function () {
                        var start = moment().subtract(5, "d").toDate();
                        var end = moment().toDate();
                        return [start, end];
                    }
                },
                {
                    name: '前七天',
                    dates: function () {
                        var start = moment().subtract(7, "d").toDate();
                        var end = moment().toDate();
                        return [start, end];
                    }
                }
            ],
            inline: false,
            container: 'body',
            alwaysOpen: false,
            singleDate: false,
            lookBehind: false,
            batchMode: false,
            duration: 200,
            stickyMonths: false,
            dayDivAttrs: [],
            dayTdAttrs: [],
            applyBtnClass: '',//确定btn的class btn-tiny
            singleMonth: 'auto',
            hoveringTooltip: function (days, startTime, hoveringTime) {
                return false;
            },
            showTopbar: true,
            customTopBar: '请选择时间',
            swapTime: false,
            selectForward: false,
            selectBackward: false,
            showWeekNumbers: false,
            getWeekNumber: function (date) {//date will be the first day of a week
                return moment(date).format('w');
            }
        }).bind('datepicker-apply', function (event, obj) {

        });

        $(".wrap_search input[name=date_range_picker]").val(
            $(".wrap_search input[name=date_from]").val()
            + " 至 "
            + $(".wrap_search input[name=date_to]").val());
    },
};

$(document).ready(function () {
    leave_message_ops.init();
});