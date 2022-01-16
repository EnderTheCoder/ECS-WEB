$(".register-button").click(function () {
    let jsonData = {};

    jsonData.username = $("#username").val();
    jsonData.password = $("#password").val();
    jsonData.email = $("#email").val();
    jsonData.qq = $("#qq").val();

    $.ajax({
        //请求方式
        type: "POST",
        dataType: 'json',
        url: "api/api.php?executor=register",
        data: jsonData,
        success: function (result) {
            if (result.code === 100)
                alert("注册成功")
            if (result.code === 203)
                alert("参数不完整")
            if (result.code === 211)
                alert("用户名已存在")
        },
        error: function (e) {

            console.log(e.status);
            console.log(e.responseText);
        }
    });
})
