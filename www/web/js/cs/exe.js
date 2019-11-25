//点击鼠标右键响应函数
//tab-content-list
var dom = '';
var tab = {
    list: function (e) {
        dom =e;
        //对右键菜单进行定位
        if(e.clientX+100<=innerWidth){
            var x = e.clientX;
        }
        else{
            var x = e.clientX - 98;
        }
        if(e.clientY+140<=innerHeight){
            var y = e.clientY;
        }
        else{
            var y = e.clientY - 138;
        }
        document.getElementById('menu').style.left=x+'px';
        document.getElementById('menu').style.top=y+'px';
        document.getElementById('menu').style.display='block';
        //取消默认的浏览器自带右键
        e.preventDefault();
    },
    deleteDom: function () {
        dom.path[1].removeChild(dom.path[0]);
    },
    listHide: function () {
        document.getElementById('menu').style.display='none';
    }
}
