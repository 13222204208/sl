<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>添加分类 </title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
</head>

<body>

    <div class="demoTable" style="margin:30px;">
        <button class="layui-btn"  data-type="reload" value="0" id="admin-management">添加分类</button>
        <div class="layui-inline" style="color:gray" id="lp_address">
        </div>

    </div>

    <div class="layui-row" id="layuiadmin-form-admin" style="display:none;">
        <form class="layui-form layui-from-pane" required lay-verify="required" style="margin:20px">

            <div class="layui-form-item">
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-block">
                   {{--  <input type="text" name="type_name" required lay-verify="type_name" autocomplete="off"
                        placeholder="请输入分类名称" value="" class="layui-input"> --}}

                        <textarea placeholder="可以添加多条数据，用逗号 , 分隔"  name="type_name" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">上级分类</label>
              <div class="layui-input-block">
                  <input type="text" name="level" autocomplete="off"
                       value="最高级" id="typeNameId" class="layui-input">
              </div>
          </div>

          <input type="hidden" name="pid" value="0" id="PId">
          <input type="hidden" name="lpname" value="" id="lpname">


            <div class="layui-form-item ">
                <div class="layui-input-block">
                    <div class="layui-footer" style="left: 0;">
                        <button class="layui-btn" lay-submit="" lay-filter="create">保存</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="layui-row" id="popUpdateTest" style="display:none;">
        <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate"
            style="margin:20px">

            <div class="layui-form-item">
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-block">
                    <input type="text" name="type_name" required lay-verify="type_name" autocomplete="off"
                        placeholder="请输入分类名称" value="" class="layui-input">
                </div>
            </div>




            <div class="layui-form-item ">
                <div class="layui-input-block">
                    <div class="layui-footer" style="left: 0;">
                        <button class="layui-btn" lay-submit="" lay-filter="editAccount">保存</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
    <script type="text/html" charset="utf-8" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="show">查看</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>

    </script>

    <script src="/layuiadmin/layui/layui.js"></script>
    <script>
        layui.use(['table', 'laydate', 'jquery', 'form'], function () {
            var table = layui.table;
            var laydate = layui.laydate;
            var $ = layui.jquery;
            var form = layui.form;

            table.render({
                elem: '#LAY_table_user'
                ,cols: [[ //标题栏
                  {field: 'id', title: 'ID', width: 80, sort: true}
                  ,{field: 'username', title: '用户名', width: 120}
                  ,{field: 'email', title: '邮箱', minWidth: 150}
                  ,{field: 'sign', title: '签名', minWidth: 160}
                  ,{field: 'sex', title: '性别', width: 80}
                  ,{field: 'city', title: '城市', width: 100}
                  ,{field: 'experience', title: '积分', width: 80, sort: true}
                ]]
                ,data: [{
                  "id": "10001"
                  ,"username": "杜甫"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "116"
                  ,"ip": "192.168.0.8"
                  ,"logins": "108"
                  ,"joinTime": "2016-10-14"
                }, {
                  "id": "10002"
                  ,"username": "李白"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "12"
                  ,"ip": "192.168.0.8"
                  ,"logins": "106"
                  ,"joinTime": "2016-10-14"
                  ,"LAY_CHECKED": true
                }, {
                  "id": "10003"
                  ,"username": "王勃"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "65"
                  ,"ip": "192.168.0.8"
                  ,"logins": "106"
                  ,"joinTime": "2016-10-14"
                }, {
                  "id": "10004"
                  ,"username": "贤心"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "666"
                  ,"ip": "192.168.0.8"
                  ,"logins": "106"
                  ,"joinTime": "2016-10-14"
                }, {
                  "id": "10005"
                  ,"username": "贤心"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "86"
                  ,"ip": "192.168.0.8"
                  ,"logins": "106"
                  ,"joinTime": "2016-10-14"
                }, {
                  "id": "10006"
                  ,"username": "贤心"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "12"
                  ,"ip": "192.168.0.8"
                  ,"logins": "106"
                  ,"joinTime": "2016-10-14"
                }, {
                  "id": "10007"
                  ,"username": "贤心"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "16"
                  ,"ip": "192.168.0.8"
                  ,"logins": "106"
                  ,"joinTime": "2016-10-14"
                }, {
                  "id": "10008"
                  ,"username": "贤心"
                  ,"email": "xianxin@layui.com"
                  ,"sex": "男"
                  ,"city": "浙江杭州"
                  ,"sign": "人生恰似一场修行"
                  ,"experience": "106"
                  ,"ip": "192.168.0.8"
                  ,"logins": "106"
                  ,"joinTime": "2016-10-14"
                }]
                //,skin: 'line' //表格风格
                ,even: true
                //,page: true //是否显示分页
                //,limits: [5, 7, 10]
                //,limit: 5 //每页默认显示的数量
              });
         
    
        });

    </script>
</body>

</html>
