<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>我的预约</title>
    <link rel="stylesheet" href="static/css/basic.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/query.css" />
    <script src="static/js/jquery.min.js"></script>
    <script src="static/layer/layer.js"></script>
    <script type="text/javascript">
        function  sub()
        {
            $.ajax({
                url: 'review_do.php',
                type: 'POST',
                data: {
                    act: 'submit',
                    id: 424,
                    resove_style: 1,
                    content: "哈哈哈",
                    pay_style: 1,
                    hand_money: 200,
                    period: 123,
                    picture: "sanfjksahfjk",
                    parts: [{num:'1',price:'213',name:'A配件'}],
                    remark: 'qw'

                },
                success: function (res) {
                    console.log(res);
//                    var data = JSON.parse(res)
                    if (1) {
                        alert('提交成功')
                        alert(data.msg)
                        history.back(-1)
                    } else {
                        console.log(data)
                    }
                },
                error: function (data) {
                    console.log("ERROR");
                }
            });
        }
    </script>
</head>
<body>
<a href="javascript:void(0)" onclick="sub()" >提交</a>
</body>
</html>
