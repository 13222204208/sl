<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>设置我的密码</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
</head>

<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">修改密码</div>
          <div class="layui-card-body" pad15>

            <div class="layui-form" lay-filter="">
              <div class="layui-form-item">
                <label class="layui-form-label">当前密码</label>
                <div class="layui-input-inline">
                  <input type="password" name="oldPassword" lay-verify="required" lay-verType="tips" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">新密码</label>
                <div class="layui-input-inline">
                  <input type="password" name="password" lay-verify="pass|required" lay-verType="tips" autocomplete="off" id="LAY_password" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">6到16个字符</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">确认新密码</label>
                <div class="layui-input-inline">
                  <input type="password" name="repassword" lay-verify="repass|required|confirmPass" lay-verType="tips" autocomplete="off" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="setmypass">确认修改</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/layuiadmin/layui/layui.js"></script>
  <script>
    layui.config({
        base: '/layuiadmin/'
      }).extend({
        index: 'lib/index'
      }).use(['index', 'user', 'form', 'jquery'], function() {

          var form = layui.form;
          var $ = layui.jquery;


          var $ = layui.$,
            form = layui.form;

          form.render();

          form.verify({
              password: function(value) {
                if (value.length < 6) {
                  return '请输入至少6位';
                }},

                confirmPass: function(value) {

                  if ($('input[name=password]').val() !== value)

                    return '两次密码输入不一致！';

                }

              });

            //提交
            form.on('submit(setmypass)', function(obj) {
              data = obj.field; //console.log(data); return false;

              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "set/mypass",
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                  console.log(res);
                  if (res.status == 200) {
                    layer.msg('修改成功', {
                      offset: '15px',
                      icon: 1,
                      time: 3000
                    })
                  } else if (res.status == 403) {
                    layer.msg('修改失败', {
                      offset: '15px',
                      icon: 1,
                      time: 3000
                    })
                  }
                },
                error: function(error) {
                  layer.msg('失败请确认信息', {
                    offset: '15px',
                    icon: 1,
                    time: 3000
                  }, function() {
                    location.href = '/login';
                  })
                }
              });



            });

          });
  </script>
</body>

</html>