<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>经纪人列表</title>
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

  <div class="demoTable" style="margin:20px;">
    搜索经纪人：
    <div class="layui-inline">
      <input class="layui-input" name="id" id="demoReload" autocomplete="off">
    </div>
    <button class="layui-btn" type="button" data-type="reload">查询</button>
  </div>



  <div class="layui-row" id="popUpdateTest" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">



      <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
          <input type="text" name="nickname" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
          <select name="role" lay-filter="aihao">

          </select>
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
          <input type="text" name="state"  autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="editAccount">修改</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
          </div>
        </div>
      </div>
    </form>
  </div>



  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
  <script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">工作详情</a>
  </script>


  <script src="/layuiadmin/layui/layui.js"></script>

  <script>
    layui.use(['table', 'laydate', 'jquery', 'form'], function() {
      var table = layui.table;
      var $ = layui.jquery;
      var form = layui.form;


      table.render({
        height: 600,
        url: "broker/list" //数据接口
          ,
        page: true //开启分页
          ,
        elem: '#LAY_table_user',
        cols: [
          [
            {
              type:'numbers',
              title:'序号',
              algin:'center',
              width:80
            },

            // {
            //   field: 'id',
            //   title: 'ID',
            //   width: 120,
            //   sort: true
            // }, 
            {
              field: 'account',
              title: '帐号',
        
            }, {
              field: 'name',
              title: '姓名',
            
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
          console.log(res);
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
          page: true,//开启分页
          elem: '#LAY_table_user',
          cols: [
            [

              {
                type: 'numbers',
                title: '序号',
                width: 120,
                algin: 'center'
              }, {
                field: 'account',
                title: '帐号',
      
              }, {
                field: 'name',
                title: '昵称',
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
         
       if (obj.event === 'edit') {
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