var ws = {};
var client_id = 0;
var userlist = [];
var user = {};
var GET = getRequest();

$(document).ready(function () {

    ws = new WebSocket(webim.server);

    listenEvent();
});

function listenEvent() {
    /**
     * 连接建立时触发
     */
    ws.onopen = function (e) {
        //必须的输入一个名称和一个图像才可以聊天
        //连接成功
        console.log("connect webim server success.");
        //发送登录信息
        msg = new Object();
        
        msg.uid = GET['uid'];
        msg.cmd = 'login';

        ws.send($.toJSON(msg));

    };

    //有消息到来时触发
    ws.onmessage = function (e) {
        var message = $.evalJSON(e.data);
        var cmd = message.cmd;
        if (cmd == 'login') {

            client_id = $.evalJSON(e.data).fd;
            showUser(message);
            
            //获取历史记录
            ws.send($.toJSON({cmd : 'getHistory'}));
            //alert( "收到消息了:"+e.data );
        }
        else if (cmd == 'getHistory') {
            showHistory(message);
        }
        else if (cmd == 'fromMsg') {
            showNewMsg(message);
        }
        else if (cmd == 'offline') {
            showNewMsg(message);
        }
    };

    /**
     * 连接关闭事件
     */
    ws.onclose = function (e) {
        if (confirm("聊天服务器已关闭")) {
            //alert('您已退出聊天室');
            location.href = 'index.html';
        }
    };

    /**
     * 异常事件
     */
    ws.onerror = function (e) {
        alert("异常:" + e.data);
        console.log("onerror");
    };
}

document.onkeydown = function (e) {
    var ev = document.all ? window.event : e;
    if (ev.keyCode == 13) {
        sendMsg($('#msg-content').val(), 'text');
        return false;
    } else {
        return true;
    }
};

function showUser(dataObj)
{
    $("#post-template .avatar").attr({'src':dataObj.avatar});
    user.uid = dataObj.uid;
    user.name = dataObj.name;
    user.avatar = dataObj.avatar;
}

/**
 * 显示所有在线列表
 * @param dataObj
 */
function showHistory(dataObj) {
    var msg;
    for (var i = 0; i < dataObj.history.length; i++) {
        msg = dataObj.history[i]['msg'];
        if (!msg) continue;
        msg['time'] = dataObj.history[i]['time'];
        msg['user'] = dataObj.history[i]['user'];
        if (dataObj.history[i]['type']) {
            msg['type'] = dataObj.history[i]['type'];
        }
        msg['channal'] = 3;
        showNewMsg(msg);
    }
}


/**
 * 显示新消息
 */
function showNewMsg(dataObj) {
    var content;
    if (!dataObj.type || dataObj.type == 'text') {
        content = xssFilter(dataObj.data);
    }

    var fromId = dataObj.from;
    var channal = dataObj.channal;

    content = parseXss(content);
    var said = '';
    var time_str;

    if (dataObj.time) {
        time_str = GetDateT(dataObj.time)
    } else {
        time_str = GetDateT()
    }

    var html = '';
    var to = dataObj.to;
    
    // 系统消息
    if (dataObj.from == 0) {
        $("#msg-template .avatar").css({'display':'none'});
        said = '<strong><a href="#fakelink">系统消息</a></strong>';
    
    } else {
        $("#msg-template .msg-time").html(time_str);
        $("#msg-template .avatar").css({'display':'block'});
        //历史记录
        if (channal == 3) {
            console.log(dataObj.user);
            said = '<strong><a href="#fakelink">' + dataObj.user.name + '</a></strong>';
            $("#msg-template .avatar").attr({'src':dataObj.user.avatar});
            $("#msg-template .msg-time").html(time_str);
        } else {
        
            //如果说话的是我自己
            if (user.uid == fromId) {
                said = '<strong><a href="#fakelink">' + user.name + '</a></strong>';
                $("#msg-template .avatar").attr({'src':user.avatar});
                $("#msg-template .msg-time").html(time_str);
            } else {
                said = '<strong><a href="#fakelink">' + dataObj.name + '</a></strong>';
                $("#msg-template .avatar").attr({'src':dataObj.avatar});
                $("#msg-template .msg-time").html(time_str);
            }
        }
    }

    html += said + " " + content;
    $("#msg-template .content").html(html);

    $("#chat-messages").append($("#msg-template").html());
    $('#chat-messages')[0].scrollTop = 1000000;
}

function xssFilter(val) {
    val = val.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\x22/g, '&quot;').replace(/\x27/g, '&#39;');
    return val;
}

function parseXss(val) {
    val = val.replace(/#(\d*)/g, '<img src="/static/img/face/$1.gif" />');
    val = val.replace('&amp;', '&');
    return val;
}


function GetDateT(time_stamp) {
    var d;
    d = new Date();

    if (time_stamp) {
        d.setTime(time_stamp * 1000);
    }
    var h, i, s;
    h = d.getHours();
    i = d.getMinutes();
    s = d.getSeconds();

    h = ( h < 10 ) ? '0' + h : h;
    i = ( i < 10 ) ? '0' + i : i;
    s = ( s < 10 ) ? '0' + s : s;
    return h + ":" + i + ":" + s;
}

function getRequest() {
    var url = location.search; // 获取url中"?"符后的字串
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);

        strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            var decodeParam = decodeURIComponent(strs[i]);
            var param = decodeParam.split("=");
            theRequest[param[0]] = param[1];
        }

    }
    return theRequest;
}

function sendMsg(content, type) {
    var msg = {};

    if (typeof content == "string") {
        content = content.replace(" ", "&nbsp;");
    }

    if (!content) {
        return false;
    }

    msg.cmd = 'message';
    msg.from = user.uid;
    msg.channal = 0;
    msg.data = content;
    msg.type = type;
    ws.send($.toJSON(msg));

    showNewMsg(msg);
    $("#msg-content").val('')
}

