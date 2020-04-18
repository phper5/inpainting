var task_queue = null;
function startProcess() {
    let canvas = document.getElementById("drawing-board");
    var $mask = canvas.toDataURL("image/png");
    let _token = getToken();
    if (typeof($resource_id) == 'undefined' || !$resource_id) {
        alert("请先上传文件");
        return;
    }
    var blank = document.createElement('canvas');//系统获取一个空canvas对象
    blank.width = canvas.width;
    blank.height = canvas.height;
    if(canvas.toDataURL() == blank.toDataURL()) {
        alert("请先用画笔涂掉您不想要的部分");
        return;
    }
    $("#previewimg").addClass('loading');
    $("#previewimg").removeClass('trans');
    $("#previewimg").html('')
    $data = {"api_token": _token,'mask':$mask,'resource_id':$resource_id};
    if (task_queue)
    {
        clearInterval(task_queue );
    }
    apiRequest('/api/tasks','post',$data,{
        'success':function ($data) {
            //开始监听
            console.log($data);
            task_queue = setInterval('taskQueue("'+$data.task_id+'")',2000);

        }
    });
    console.log('start');
}
function taskQueue($task_id) {
    console.log($task_id);
    let _token = getToken();
    $data = {"api_token": _token,'task_id':$task_id};
    apiRequest('/api/tasks/'+$task_id,'get',$data,{
        'success':function ($data) {
            if ($data.status < 0 || $data.status >20) {
                if (task_queue)
                {
                    clearInterval(task_queue );
                }
            }
            if ($data.status <0) {
                alert("处理失败");
            }
            if ($data.status == 30) {
                $url =  $data.target_file[0].image_canvas_url
                //$url ="http://i.com/images/demo.png";
                $("#previewimg").html('<img src="'+$url+'">')
            }
        }
    });

}
function toWork($url,widht,height) {
    canvas_height = height;
    canvas_width = widht;
    //canvasResize();
    let canvas = document.getElementById("drawing-board");
    canvas.style.backgroundImage = "url('"+$url+"')";
    canvas.width = canvas_width;
    canvas.height = canvas_height;
    canvas.style.height  = canvas_height+"px";
    canvas.style.width  = canvas_width+"px";
    let ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    let preview = document.getElementById('previewimg');
    preview.style.height  = canvas_height+"px";
    preview.style.width  = canvas_width+"px";
    document.getElementById('demoBox').style.display='none';
    document.getElementById('workBox').style.display='block';
}

function generateUUID() {
    var d = new Date().getTime();
    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now(); //use high-precision timer if available
    }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
}
function getServ() {
     //return "http://i.com";
    xy = document.location.protocol;
    if (!xy) {
        xy='http:';
    }
    return xy+"//recovery.diandi.org";
}
function getToken(callback=null) {

    token = localStorage.getItem('token');
    if (token) {
        if (callback)
        {
            callback(token);
        }
        return token;
    }
    success = function ($data) {
        $token = $data.token;
        localStorage.setItem('token',$token);
        if (callback)
        {
            callback($token);
        }

    };
    apiRequest('/api/token','get',{},{
        'success':success
    });


}


function apiRequest ($api,$method,$data={},$callback=null)
{
    $server= getServ();
    $.ajax({
        url: $server+$api,
        method: $method,
        data: $data,
        dataType: "json",
        success: function (data) {
            console.log('xxx');
            console.log(data);
            if (typeof  $callback.success !="undefined")
            {
               $callback.success(data.data)
            }
        },
        error : function(e){
            console.log(e.status);
            if (e.responseJSON.code==102) {
                localStorage.removeItem('token');
                alert("用户信息失效，请重试");
            }
            console.log(e.responseText);
            if (typeof  $callback.error !="undefined")
            {
                $callback.error(data.data)
            }
        }
    });
}
