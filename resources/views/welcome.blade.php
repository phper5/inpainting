<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>点滴工具-在线智能图片修复</title>
    <meta name="description" content="点滴在线修复工具是一款在线去水印软件。无需安装，即可快速批量去除水印，文字，杂物，并且保持原始文件的画质与格式。"/>
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <link rel="canonical" href="http://recovery.diandi.org" />
    <meta property="og:locale" content="zh_CN" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="点滴工具-在线智能图片修复" />
    <meta property="og:description" content="点滴在线修复工具是一款在线去水印软件。无需安装，即可快速批量去除水印，文字，杂物，并且保持原始文件的画质与格式。" />
    <meta property="og:url" content="http://recovery.diandi.org" />
    <meta property="og:site_name" content="点滴 - 为您提供专业的图片，视频，音频在线解决方案" />
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"  defer="true" >
    <link href="css/app.css" rel="stylesheet">
    <script src="js/jq.js"></script>
    <script src="js/repare.js"></script>
    <script src="{{ asset('vendor/kustomer/js/kustomer.js') }}" defer></script>
    <script src="ali-oss/dist/aliyun-oss-sdk.min.js"  defer="true" ></script>
    <script type="text/javascript">

    var canvas_height=600;
    var canvas_width=600;
    var $resource_id = null;
    var upload = function (e) {
        document.getElementById('demoBox').style.display='none';
        document.getElementById('workBox').style.display='block';
        let file = e.target.files[0];
        let fname = file.name;
        let ext = fname.substring(fname.indexOf("."))
        let storeAs = generateUUID()+'.'+ext;
        let type = file.type.substring(0,file.type.indexOf("/"));
        if (type !="image") {
            console.log('file type wrong ' + file.type);
            return ;
        }
        console.log(file.name + ' => ' + storeAs);
        var doupload = function(token)
        {
            $("#drawing-board").css('backgroundImage','');
            $("#previewimg").html('')
            $("#previewimg").removeClass('loading');
            $("#previewimg").addClass('trans');
            let ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            let api_addr = getServ()+"/api/resource/auth?api_token="+token;


            $data = {"api_token":token};
            apiRequest('/api/resource/auth','get',$data,{
                'success':function (result) {
                    if (result) {
                        let client = new OSS({
                            accessKeyId: result.AccessKeyId,
                            accessKeySecret: result.AccessKeySecret,
                            stsToken: result.SecurityToken,
                            endpoint: result.endpoint,
                            bucket: result.bucket
                        });
                        if (typeof (task_queue)!='undefined' && task_queue)
                        {
                            clearInterval(task_queue );
                        }
                        //storeAs表示上传的object name , file表示上传的文件
                        client.multipartUpload(result['path']+storeAs, file,{
                            callback: {
                                url: result.callback.callbackUrl,
                                /**host: result.callback.callbackBody,**/
                                /* eslint no-template-curly-in-string: [0] */
                                body: result.callback.callbackBody+"&x_filename="+file.name,
                                contentType: result.callback.callbackBodyType,
                                customValue: {
                                    x_filename: file.name
                                },
                            },
                            progress: function (p, checkpoint) {
                                // 断点记录点。浏览器重启后无法直接继续上传，您需要手动触发继续上传。
                                tempCheckpoint = checkpoint;
                                console.log(p);
                            }
                        }).then(function (result) {
                            //image_canvas_url
                            fff = result;
                            console.log('xxxxxx');
                            $resource_id = result.data.data.resource_id;
                            toWork(result.data.data.image_canvas_url,result.data.data.canvas_width,result.data.data.canvas_height);
                            console.log(result);
                        }).catch(function (err) {
                            console.log(err);
                        });
                    }
                }
            });

        }
        getToken(doupload);


    };


    </script>
    <link rel="stylesheet" href="//at.alicdn.com/t/font_584725_0nyjbeaxjw2ep14i.css"  defer="true" >
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            font-display: swap;
        }



        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            flex-direction:column;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;

        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        #workBox {
            display: none;
        }

        /******************/
        #drawbox {
            text-align:center;
            display: flex;
            /**width: 700px;
            height: 700px;**/

        }
        #range-wrap{
            /*width: 30px;height: 150px;*/
            /**
            position: fixed;top: 50%;right:30px;
            margin-top: -75px;
            **/
        }

        #range-wrap input{
            /**transform: rotate(-90deg);**/
            width: 150px;height: 20px;transform-origin: 75px 75px;    border-radius: 15px;outline: none;position: relative;
            /**-webkit-appearance: none;**/
            margin: 15px;
            background: #cccccc;
        }
        #range-wrap input::after{display: block;content:"";width:0;height: 0;border:5px solid transparent;
            border-right:150px solid #00CCFF;border-left-width:0;position: absolute;left: 0;top: 5px;border-radius:15px; z-index: 0; }
        #range-wrap input[type=range]::-webkit-slider-thumb,#range-wrap input[type=range]::-moz-range-thumb{-webkit-appearance: none;}
        #range-wrap input[type=range]::-webkit-slider-runnable-track,#range-wrap input[type=range]::-moz-range-track {height: 10px;border-radius: 10px;box-shadow: none;}
        #range-wrap input[type=range]::-webkit-slider-thumb{-webkit-appearance: none;height: 20px;width: 20px;margin-top: -1px;
            background: #ffffff;border-radius: 50%;box-shadow: 0 0 8px #00CCFF;position: relative;z-index: 999;}

        .color-group{
            margin-left: 10px;
            /*position:fixed;width: 30px;left: 30px;top:50%;transform: translate(0,-150px)*/
        }
        .color-group ul{list-style: none;display: flex;}
        .color-group ul li{width: 30px;height: 30px;margin: 10px 0;border-radius: 50%;box-sizing: border-box;border:3px solid white;box-shadow: 0 0 8px rgba(0,0,0,0.2);cursor: pointer;transition: 0.3s;}
        .color-group ul li.active{box-shadow:0 0 15px #00CCFF;}

        .tools{
            /**position: fixed;left:0;bottom: 30px; width:100%;**/
            display: flex;justify-content: center;text-align: center
        }
        .tools button{border-radius: 50%;width: 50px;height: 50px; background-color: rgba(255,255,255,0.7);border: 1px solid #eee;outline: none;cursor: pointer;box-sizing: border-box;margin: 0 10px;text-align: center;color:#ccc;line-height: 50px;box-shadow:0 0 8px rgba(0,0,0,0.1); transition: 0.3s;}
        .tools button.active,.tools button:active{box-shadow: 0 0 15px #00CCFF; color:#00CCFF;}
        .tools button i{font-size: 24px;}
        @media screen and (max-width: 768px) {
            .tools{bottom:auto;top:20px;}
            .tools button{width: 35px;height: 35px;line-height: 35px;margin-bottom: 15px;box-shadow:0 0 5px rgba(0,0,0,0.1);}
            .tools button.active,.tools button:active{box-shadow: 0 0 5px #00CCFF;}
            .tools button i{font-size: 18px;}
            .tools #swatches{display: none}
            .color-group{left: 0;top:auto;bottom: 20px;display: flex;width:100%;justify-content: center;text-align: center;transform: translate(0,0)}
            .color-group ul li{display: inline-block;margin:0 5px;}
            .color-group ul li.active{box-shadow:0 0 10px #00CCFF;}
            #range-wrap{right:auto;left: 20px;}
        }
    .toolinfo{
        line-height: 50px;
        vertical-align: bottom;
        height: 50px;
    }
        #drawTools {
            display: flex;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        .toolbox {
            display: flex;
            margin: 0 10px;
            height: 50px;
        }
        .toolbox ul {
            margin: 0 auto;
            padding: 0;
        }
        #drawing-board{
            /**background: #999999;**/
            display: block;cursor: crosshair;
           width: 700px;
            height: 700px;
        }

        #save{
            display: none;
        }
        .editarea{
            display: flex;
            width: 100%;
            flex-wrap: wrap;
        }
        #previewimg{
            width: 700px;
            height: 700px;
        }
        .loading {
            background-image: url("images/loading.gif");
            background-repeat:no-repeat;
            background-position: center;
        }
        .trans {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAABlBMVEXe3t7///99o+0QAAAAAHRSTlM2uXDMAAAAEElEQVR42mNg+M+AFeEQBgB+vw/xeaW7jwAAAABJRU5ErkJggg==");
            background-repeat: repeat;
        }
    </style>
</head>
<body itemscope itemtype="http://schema.org/diandi">
<nav class="nav">
    <div class="top">
        <div class="topbar">
            <img src="images/logo.webp">
            <div class="menu">
                <div class="item">
                    <div class="txt"><a href="/">首页</a></div>
                </div>
                <div class="item">
                    <div class="txt"><a href="/feedback">反馈</a></div>
                </div>

            </div>
        </div>
        <div class="menu">
            <div class="item">
                <div class="txt">登录</div>
            </div>
            <div class="item register">
                <div class="txt">注册</div>
            </div>
        </div>
    </div>
</nav>
<header>
    <h1>点滴在线修复</h1>
    <h3>点滴照片修复一款简单易用的在线图片修复工具，可以方便去水印，去杂物，去杂物，方便简单易上手，0基础也可轻松操作。<br>
        无论你是能熟练运用PS的大神，还是什么都不会的小白，使用点滴在线修复工具，只需动动手指，即可一键在线修复您的图片。
    </h3>
</header>
<div class="flex-center position-ref content-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif
    <div id="demoBox">
        <div class="content">
            <div id="demo">
                <img src="images/demo.gif" />
                <div id="demoInfo">
                    <div class="title">点滴在线智能图片修复</div>
                    <ul>
                        <li>移除杂物</li>
                        <li>清除水印</li>
                        <li>删除人物</li>
                        <li>修复破损残缺图片</li>
                        <li> <input type="file" id="btn_upload" accept="image/*" >

                        </li>
                    </ul>
                    <input onclick='javascript:$("#btn_upload").click();' value="文件上传" class="blue_btn">
                </div>
            </div>
            <div class="info">


            </div>
        </div>
    </div>
    <div id="workBox">
        <div class="content">


            <div class="editarea">

                <div id="drawbox">
                    <div>
                        <div>原图</div>
                        <canvas id="drawing-board" disable-scroll="true" class="loading">请升级您的浏览器，或者使用firefox chrome</canvas>
                    </div>
                </div>
                <div id="previewbox">
                    <div>结果</div>
                    <div id="previewimg" class="trans">

                    </div>
                </div>
            </div>



            <div id="drawTools">
                <div class="toolbox">
                    <div class="toolinfo">笔刷大小</div> <div id="range-wrap"><input type="range" id="range" min="1" max="30" value="15" title="调整笔刷粗细"></div>
                </div>
                <div class="toolbox">
                    <div class="toolinfo"  >笔刷颜色</div>
                    <div class="color-group" >
                        <ul>
                            <li id="black" class="color-item active" style="background-color: black;"></li>
                            <li id="red" class="color-item" style="background-color: #FF3333;"></li>
                            <!--    <li id="white" class="color-item" style="background-color: white;"></li>
                                <li id="black" class="color-item active" style="background-color: black;"></li>
                                <li id="red" class="color-item" style="background-color: #FF3333;"></li>
                                <li id="blue" class="color-item" style="background-color: #0066FF;"></li>
                                <li id="yellow" class="color-item" style="background-color: #FFFF33;"></li>
                                <li id="green" class="color-item" style="background-color: #33CC66;"></li>
                                <li id="gray" class="color-item" style="background-color: gray;"></li>-->
                        </ul>
                    </div>
                </div>
                <div class="toolbox">
                    <div class="tools">
                        <button id="brush" class="active" title="画笔"><i class="iconfont icon-qianbi"></i></button>
                        <button id="eraser" title="橡皮擦"><i class="iconfont icon-xiangpi"></i></button>
                        <button id="clear" title="清空"><i class="iconfont icon-qingchu"></i></button>
                        <button id="undo" title="撤销"><i class="iconfont icon-chexiao"></i></button>
                        <button id="save" title="保存"><i class="iconfont icon-fuzhi"></i></button>
                    </div>
                </div>
                <div class="toolbox">
                    <input type="file" value="重新上传" id="upload_btn">
                    <input onclick='javascript:$("#upload_btn").click();' value="重新上传" class="blue_btn">
                    <input type="button" value="开始修复" id="repare_btn" class="blue_btn">
                </div>


            </div>


        </div>
    </div>



</div>
<div class="bar">


</div>

<script>
    document.getElementById('btn_upload').addEventListener('change', upload);
    document.getElementById('upload_btn').addEventListener('change', upload);
    document.getElementById('repare_btn').addEventListener('click', startProcess);
</script>
<script type="application/javascript">
    let canvas = document.getElementById("drawing-board");
    let ctx = canvas.getContext("2d");
    let eraser = document.getElementById("eraser");
    let brush = document.getElementById("brush");
    let reSetCanvas = document.getElementById("clear");
    let aColorBtn = document.getElementsByClassName("color-item");
    let save = document.getElementById("save");
    let undo = document.getElementById("undo");
    let range = document.getElementById("range");
    let clear = false;
    let activeColor = 'black';
    let lWidth = document.getElementById("range").value;
    canvas.width = '300';
        canvas.height = '300';
    //autoSetSize(canvas);

    setCanvasBg('white');

    listenToUser(canvas);

    getColor();

    // window.onbeforeunload = function(){
    //     return "Reload site?";
    // };

    function autoSetSize(canvas) {
        // canvasSetSize();
        //
        // function canvasSetSize() {
        //     let pageWidth = document.documentElement.clientWidth;
        //     let pageHeight = document.documentElement.clientHeight;
        //
        //     // canvas.width = pageWidth;
        //     // canvas.height = pageHeight;
        //     canvas.width = canvas_width;
        //     canvas.height = canvas_height;
        // }
        canvasResize();
        window.onresize = function () {
            // canvasSetSize();'
            canvasResize();
        }

    }

    function setCanvasBg(color) {
        return ;
        ctx.fillStyle = color;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "black";
    }

    function getXy(e) {

        let x = e.clientX;
        let y = e.clientY;
        let l = canvas.getBoundingClientRect().left;
        let t = canvas.getBoundingClientRect().top;
        point = {"x": x-l, "y": y-t};
        // point = {"x": x+0, "y": y+0};
        return point;
    }

    function listenToUser(canvas) {
        let painting = false;
        let lastPoint = {x: undefined, y: undefined};

        if (document.body.ontouchstart !== undefined) {
            canvas.ontouchstart = function (e) {
                this.firstDot = ctx.getImageData(0, 0, canvas.width, canvas.height);//在这里储存绘图表面
                saveData(this.firstDot);
                painting = true;
                // let x = e.touches[0].clientX;
                // let y = e.touches[0].clientY;
                lastPoint=getXy(e.touches[0]);
                // lastPoint = {"x": x, "y": y};
                ctx.save();
                drawCircle(lastPoint.x, lastPoint.y, 0);
            };
            canvas.onwheel = function(event){
                event.preventDefault();
            };
            canvas.onmousewheel = function(event){
                event.preventDefault();
            };
            canvas.ontouchmove = function (e) {
                if (painting) {
                    // let x = e.touches[0].clientX;
                    // let y = e.touches[0].clientY;
                    // let newPoint = {"x": x, "y": y};
                    let newPoint=getXy(e.touches[0]);
                    drawLine(lastPoint.x, lastPoint.y, newPoint.x, newPoint.y);
                    lastPoint = newPoint;
                }
                e.preventDefault();
            };

            canvas.ontouchend = function (e) {
                painting = false;
                e.preventDefault();
            }
        } else {
            canvas.onmousedown = function (e) {
                this.firstDot = ctx.getImageData(0, 0, canvas.width, canvas.height);//在这里储存绘图表面
                saveData(this.firstDot);
                painting = true;
                // let x = e.clientX;
                // let y = e.clientY;
                let point=getXy(e);
                ctx.save();
                drawCircle(point.x, point.y, 0);
            };
            canvas.onmousemove = function (e) {
                //console.log('move'+painting);

                if (painting) {
                    // let x = e.clientX;
                    // let y = e.clientY;
                    // let newPoint = {"x": x, "y": y};
                    let newPoint=getXy(e);
                    drawLine(lastPoint.x, lastPoint.y, newPoint.x, newPoint.y,clear);
                    lastPoint = newPoint;
                }
            };

            canvas.onmouseup = function () {
                painting = false;
                //console.log('up'+painting);
                lastPoint =  {x: undefined, y: undefined};;
            };

            canvas.mouseleave = function () {
                painting = false;
            }
        }
    }

    function drawCircle(x, y, radius) {
        ctx.save();
        ctx.beginPath();
        ctx.arc(x, y, radius, 0, Math.PI * 2);
        ctx.fill();
        if (clear) {
            ctx.clip();
            ctx.clearRect(0,0,canvas.width,canvas.height);
            ctx.restore();
        }
    }

    function drawLine(x1, y1, x2, y2) {
        ctx.lineWidth = lWidth;
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
        if (clear) {
            ctx.save();
            ctx.globalCompositeOperation = "destination-out";
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
            ctx.closePath();
            ctx.clip();
            ctx.clearRect(0,0,canvas.width,canvas.height);
            ctx.restore();
        }else{
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
            ctx.closePath();
        }
    }

    range.onchange = function(){
        lWidth = this.value;
    };

    eraser.onclick = function () {
        clear = true;
        this.classList.add("active");
        brush.classList.remove("active");
    };

    brush.onclick = function () {
        clear = false;
        this.classList.add("active");
        eraser.classList.remove("active");
    };

    reSetCanvas.onclick = function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        //setCanvasBg('white');
    };

    save.onclick = function () {
        let imgUrl = canvas.toDataURL("image/png");
        let saveA = document.createElement("a");
        document.body.appendChild(saveA);
        saveA.href = imgUrl;
        saveA.download = "zspic" + (new Date).getTime();
        saveA.target = "_blank";
        saveA.click();
    };

    function getColor(){
        for (let i = 0; i < aColorBtn.length; i++) {
            aColorBtn[i].onclick = function () {
                for (let i = 0; i < aColorBtn.length; i++) {
                    aColorBtn[i].classList.remove("active");
                    this.classList.add("active");
                    activeColor = this.style.backgroundColor;
                    ctx.fillStyle = activeColor;
                    ctx.strokeStyle = activeColor;
                }
            }
        }
    }

    let historyDeta = [];

    function saveData (data) {
        (historyDeta.length === 10) && (historyDeta.shift());// 上限为储存10步，太多了怕挂掉
        historyDeta.push(data);
    }

    undo.onclick = function(){
        if(historyDeta.length < 1) return false;
        ctx.putImageData(historyDeta[historyDeta.length - 1], 0, 0);
        historyDeta.pop()
    };
</script>
@include('kustomer::kustomer')
<!--<div>已经累计处理文件1个</div>-->
</body>
</html>
