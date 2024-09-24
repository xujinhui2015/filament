## 本站点所有图片都不带域名路径

- 前端默认读 请求地址+ “/storage/” +图片路径
- 如返回 banner/2024-03/jb/1711468340-1_1698387114291.jpg，拼接的路径为 http://120.78.5.243:81/storage/banner/2024-03/jb/1711468340-1_1698387114291.jpg


## 登录成功
- 登录成功获取到token，放到请求头中 authorization: Bearer 69|xPuf77slokxuoSTjv8lX0wstUU5DDlKjt9fvNg7D


## 默认返回格式
```
// 正确返回
{
    "code": 200,
    "message": "",
    "data": {
        "token": "15|4IR1apOms17X4YdNvHAPbMYn6x048JoiAVQdVzTh2f0f2ed9"
    }
}

// 错误返回并且返回 500状态码
{
    "code": 500, // 错误码，一般都是500
    "message": "账号或者密码不正确", // 错误提示
    "data": {},
}
```

