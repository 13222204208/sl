<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>按楼盘查看数据变更</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
  <style>

  </style>
</head>

<body>

 





  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>


  <script src="/layuiadmin/layui/layui.js"></script>

  <script>
    layui.use(['table', 'laydate', 'jquery', 'form'], function() {
      var table = layui.table;
      var $ = layui.jquery;
      var form = layui.form;


   

      table.render({
        url: "change/houses" //数据接口
          ,
        page: true //开启分页
          ,
        elem: '#LAY_table_user',
        cols: [
          [

            {
              field: 'id',
              title: 'ID',
              width: 80,
              sort: true
            }, {
              field: 'before',
              title: '变更前名称',
              width: 300
            }, {
              field: 'update_name',
              title: '当前名称',
              width: 300
            }, {
              field: 'pid',
              title: '父ID',
              width: 120
            }
          ]
        ],
        parseData: function(res) { //res 即为原始返回的数据
          //console.log(res);
          return {
            "code": '0', //解析接口状态
            "msg": res.message, //解析提示文本
            "count": res.total, //解析数据长度
            "data": res.data //解析数据列表
          }
        },
        id: 'testReload',
        title: '后台用户',
        totalRow: true

      });



      //查询帐号
      $('.demoTable .layui-btn').on('click', function() {

        var keyWord = $('#demoReload');
        var account_num = keyWord.val();

        table.render({
          height: 600,
          url: "query/account" + '/' + account_num //数据接口
            ,
          //page: true,//开启分页
          elem: '#LAY_table_user',
          cols: [
            [

              {
                field: 'id',
                title: 'ID',
                width: 80,
                sort: true
              }, {
                field: 'account_num',
                title: '帐号',
                width: 120
              }, {
                field: 'nickname',
                title: '昵称',
                width: 120
              }, {
                field: 'role',
                title: '角色',
                width: 120
              }, {
                field: 'state',
                title: '状态',
                width: 160
              }, {
                fixed: 'right',
                title: "操作",
                width: 150,
                align: 'center',
                toolbar: '#barDemo'
              }
            ]
          ],
          parseData: function(res) { //res 即为原始返回的数据
            //console.log(res);
            return {
              "code": '0', //解析接口状态
              "msg": res.message, //解析提示文本
              "count": res.total, //解析数据长度
              "data": res.data //解析数据列表
            }
          },
          title: '后台用户',
          totalRow: true

        });
      });


      form.on('select(stateSelect)', function(data) { //选择角色
        let role = data.elem.value; //当前字段变化的值
        url ="query/account/role/" + role //数据接口
        console.log(url);
        table.render({
          height: 600,
          url: url
            ,
          page: true,//开启分页
          elem: '#LAY_table_user',
          cols: [
            [

              {
                field: 'id',
                title: 'ID',
                width: 80,
                sort: true
              }, {
                field: 'account_num',
                title: '帐号',
                width: 120
              }, {
                field: 'nickname',
                title: '昵称',
                width: 120
              }, {
                field: 'role',
                title: '角色',
                width: 120
              }, {
                field: 'state',
                title: '状态',
                width: 160
              }, {
                fixed: 'right',
                title: "操作",
                width: 150,
                align: 'center',
                toolbar: '#barDemo'
              }
            ]
          ],
          parseData: function(res) { //res 即为原始返回的数据
            //console.log(res);
            return {
              "code": '0', //解析接口状态
              "msg": res.message, //解析提示文本
              "count": res.total, //解析数据长度
              "data": res.data //解析数据列表
            }
          },
          title: '后台用户',
          totalRow: true

        });
      });

      table.on('tool(user)', function (obj) {
            var data = obj.data;
         
           if (obj.event === 'del') {
            if (data.id == 1) {
              layer.msg("超级管理员无法删除", {icon: 2});
              return false;
            }
                layer.confirm('真的删除行么', function (index) {
                    $.ajax({
                        url: "del/account",
                        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
                        type: "POST",
                        data: {id: data.id},
                        success: function (msg) {
                  
                            if (msg.status == 200) {
                                //删除这一行
                                obj.del();
                                //关闭弹框
                                layer.close(index);
                                layer.msg("删除成功", {icon: 6});
                            } else {
                                layer.msg("删除失败", {icon: 5});
                            }
                        }
                    });
                    return false;
                });
            } else if (obj.event === 'edit') {
                    layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "修改帐号信息",
                        area: ['420px', '330px'],
                        content: $("#popUpdateTest")//引用的弹出层的页面层的方式加载修改界面表单
                    });
                    //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
                    form.val("formUpdate", data);
                    setFormValue(obj,data);
                }
            
        });

        function setFormValue(obj, data) {
        form.on('submit(editAccount)', function(massage) {
          massage= massage.field; console.log(data.id);
          if (data.id == 1) {
            massage.role = "超级管理员"
          }
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/account",
            type: 'post',
            data: {
              id: data.id,
              nickname: massage.nickname,
              role:massage.role,
              state:massage.state
            },
            success: function(msg) {
              console.log(msg);
              if (msg.status == 200) {
                layer.closeAll('loading');
                layer.load(2);
                layer.msg("修改成功", {
                  icon: 6
                });
                setTimeout(function() {

                  obj.update({
                    nickname: massage.nickname,
                    role:massage.role,
                    state:massage.state
                  }); //修改成功修改表格数据不进行跳转 
 
             
                  layer.closeAll(); //关闭所有的弹出层
                  //window.location.href = "/edit/horse-info";

                }, 1000);

              } else {
                layer.msg("修改失败", {
                  icon: 5
                });
              }
            }
          })
          return false;
        })
      }
      

    });
  </script>

</body>

</html>