;
/**
 * 错误手机
 * **/
window.onerror = function (message, url, lineNumber, columnNo, error) {
    var data = {
        'sc': "guest" ,
        'message': message,
        'url': url,
        'error': error?error.stack:""
    };

    try{
        var request = new XMLHttpRequest();
        request.open('POST', "/error/capture");
        request.setRequestHeader("Content-type","application/json");
        request.send(JSON.stringify( data ));
        request.onload = function(e){
        };
        request.onerror = function(e){
        };
    }catch (e) {

    }

    return true;
};