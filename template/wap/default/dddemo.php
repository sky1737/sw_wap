<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <style type="text/css">
        .container {
            display: flex;
        }

        .show {
            height: 300px;
            width: 300px;
            border: 5px solid royalblue;
        }

        a {
            display: inline-block;
            width: 100px;
            height: 40px;
            background: rgba(255, 255, 0, .2);
            position: relative;
            overflow: hidden;
            transition: background 1s;
        }

        a:hover {
            background: rgba(0, 0, 255, .2);
        }
        input {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 100px;
            opacity: 1;
            filter: alpha(opacity=1);
        }
    </style>
</head>
<body>
<h1>请选择您的头像</h1>
<div class="container">
    <a href="#">浏览
        <input type="file" value="浏览"/>
    </a>
    <div class="show"></div>
</div>
</body>
</html>
<script type="text/javascript">
    var show = document.querySelector(".show");
    var File = document.querySelector("input[type=file]");
    window.onload = function () {
        File.onchange = function () {
            //创建对象
            var reader = new FileReader();
            //console.log(this.files[0])
            reader.readAsDataURL(this.files[0]);
            reader.onload = function () {
                //console.log(reader.result)
                show.style.background = "url(" + reader.result + ") no-repeat center/contain"
            }
        }
    }
</script>