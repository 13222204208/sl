<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>权限管理 </title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
</head>

<body>
 
  <div class="demoTable" style="margin:30px;">
    <button class="layui-btn" data-type="reload" id="admin-management1">添加权限</button>

  </div>

  <div class="layui-row" id="layuiadmin-form-admin1" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" style="margin:20px">

      <div class="layui-form-item">
        <label class="layui-form-label">权限名称</label>
        <div class="layui-input-block">
          <input type="text" name="name" required lay-verify="name" autocomplete="off" placeholder="请输入权限名称" value="" class="layui-input">
        </div>
      </div>



      <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="create1">保存</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="layui-form" lay-filter="layuiadmin-form-role" id="layuiadmin-form-role" style="padding: 20px 30px 0 0;">
    <div class="layui-form-item">
      <label class="layui-form-label">角色</label>
      <div class="layui-input-block">
        <select name="rolename" lay-filter="selectRole">

        </select>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">权限范围</label>
      <div class="layui-input-block" id="roleScope">
       {{--  <input type="checkbox" name="limits[]" lay-skin="primary" title="楼盘架构管理" value="楼盘架构管理">
        <input type="checkbox" name="limits[]" lay-skin="primary" title="组织架构管理" value="组织架构管理">
        <input type="checkbox" name="limits[]" lay-skin="primary" title="经纪人管理" value="经纪人管理">
        <input type="checkbox" name="limits[]" lay-skin="primary" title="工作管理" value="工作管理">
        <input type="checkbox" name="limits[]" lay-skin="primary" title="扫楼记录管理" value="扫楼记录管理">
        <input type="checkbox" name="limits[]" lay-skin="primary" title="租户管理" value="租户管理">
        <input type="checkbox" name="limits[]" lay-skin="primary" title="参数配置" value="参数配置">
        <input type="checkbox" name="limits[]" lay-skin="primary" title="数据统计" value="数据统计"> --}}

      </div>
    </div>
<!--     <div class="layui-form-item">
      <label class="layui-form-label">具体描述</label>
      <div class="layui-input-block">
        <textarea type="text" name="describe" lay-verify="" autocomplete="off" class="layui-textarea"></textarea>
      </div>
    </div> -->
    <div class="layui-form-item ">
      <div class="layui-input-block">
        <div class="layui-footer" style="left: 0;">
          <button class="layui-btn" lay-submit="" lay-filter="createRole">保存</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
  </div>

  <script src="/layuiadmin/layui/layui.js"></script>
  <script>
    layui.use(['table', 'laydate', 'jquery', 'form','element'], function() {
      var table = layui.table;
      var laydate = layui.laydate;
      var $ = layui.jquery;
      var form = layui.form;
      var element = layui.element;

      $(document).on('click', '#admin-management1', function() {
        layer.open({
          //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
          type: 1,
          title: "新建权限",
          area: ['600px', '300px'],
          content: $("#layuiadmin-form-admin1") //引用的弹出层的页面层的方式加载修改界面表单
        });
      });

      form.verify({
        role_name: function(value) {
          if (value.length > 8) {
            return '最多只能八个字符';
          }
        }
      });

            //监听提交
            form.on('submit(create1)', function(data) {

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "add/power",
          method: 'POST',
          data: data.field,
          dataType: 'json',
          success: function(res) {
            console.log(res);
            if (res.status == 200) {
              layer.msg('创建权限名称成功', {
                offset: '15px',
                icon: 1,
                time: 1000
              }, function() {
                location.href = 'power';
              })
            } else {
              layer.msg('填写错误或角色名重复', {
                offset: '15px',
                icon: 2,
                time: 3000
              }, function() {
                location.href = 'power';
              })
            }
          }
        });
        return false;
      });

      $(document).on('click', '#admin-management', function() {
        layer.open({
          //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
          type: 1,
          title: "新建角色",
          area: ['420px', '200px'],
          content: $("#layuiadmin-form-admin") //引用的弹出层的页面层的方式加载修改界面表单
        });
      });

      form.verify({
        role_name: function(value) {
          if (value.length > 8) {
            return '最多只能八个字符';
          }
        }
      });


      //监听提交
      form.on('submit(create)', function(data) {

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "add/role",
          method: 'POST',
          data: data.field,
          dataType: 'json',
          success: function(res) {
            console.log(res);
            if (res.status == 200) {
              layer.msg('创建角色名称成功', {
                offset: '15px',
                icon: 1,
                time: 1000
              }, function() {
                location.href = 'permission-settings';
              })
            } else if (res.status == 403) {
              layer.msg('填写错误或角色名重复', {
                offset: '15px',
                icon: 2,
                time: 3000
              }, function() {
                location.href = 'permission-settings';
              })
            }
          }
        });
        return false;
      });

      //保存角色权限
      form.on('submit(createRole)', function(data) {
        console.log(data.field);
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "add/role/scope",
          method: 'POST',
          data: data.field,
          dataType: 'json',
          success: function(res) {
            console.log(res);
            if (res.status == 200) {
              layer.msg('保存角色权限范围成功', {
                offset: '15px',
                icon: 1,
                time: 2000
              })
            } else if (res.status == 403) {
              layer.msg('请先选择', {
                offset: '15px',
                icon: 2,
                time: 3000
              }, function() {
                location.href = 'permission-settings';
              })
            }
          }
        });
        return false;
      });

      //获取角色名称
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "gain/role",
        method: 'get',
        dataType: 'json',
        success: function(res) {
          status = res.status;
          role_name = res.role_name;
          if (status == 200) {
            options = "<option value=''>选择角色</option>";
            for (var i = 0; i < role_name.length; i++) {
              var t = role_name[i];
            
              options += '<option value="' + t.name + '">' + t.name + '</option>';
            }
            console.log(options);
            $("select[name='rolename']").html(options);
            form.render('select');
          } else if (res.status == 403) {
            layer.msg('填写错误或角色名重复', {
              offset: '15px',
              icon: 2,
              time: 3000
            }, function() {
              location.href = 'permission-settings';
            })
          }
        }
      });

            //获取权限名称
            $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "gain/power",
        method: 'get',
        dataType: 'json',
        success: function(res) {
          status = res.status;
          role_name = res.role_name;
          if (res.status == 200) {
            optionData = "";
              for (var i = 0; i < role_name.length; i++) {
              var t = role_name[i];
            
              optionData += '<input type="checkbox" name="limits[]" lay-skin="primary" title="' + t.name + '" value="' + t.name + '">';
            }

              console.log(optionData);
              $("#roleScope").html(optionData);
              form.render(); 

            }else if (res.status == 403) {
            layer.msg('填写错误或角色名重复', {
              offset: '15px',
              icon: 2,
              time: 3000
            }, function() {
              location.href = 'power';
            })
          }
        }
      });


    })
  </script>
</body>

</html>