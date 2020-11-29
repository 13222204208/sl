<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>角色管理 </title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
</head>

<body>

  <div class="demoTable" style="margin:30px;">
    <button class="layui-btn" data-type="reload" id="admin-management">添加新角色</button>

  </div>

  <div class="layui-row" id="layuiadmin-form-admin" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" style="margin:20px">

      <div class="layui-form-item">
        <label class="layui-form-label">角色名称</label>
        <div class="layui-input-block">
          <input type="text" name="name" required lay-verify="role_name" autocomplete="off" placeholder="请输入角色名称" value="" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">权限范围</label>
      <div class="layui-input-block" id="roleScope" >


      </div>
      </div>
   
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
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">

       <div class="layui-form-item">
        <label class="layui-form-label">权限范围</label>
      <div class="layui-input-block" id="updateRoleScope" >


      </div>
      </div>
<br>

      <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="editAccount">修改</button>
            {{-- <button type="reset" class="layui-btn layui-btn-primary">重置</button> --}}
          </div>
        </div>
      </div>
    </form>
  </div>

  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
 <script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit">分配权限</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  </script> 

  <script src="/layuiadmin/layui/layui.js"></script>
  <script>
    layui.use(['table', 'laydate', 'jquery', 'form','tree'], function() {
      var table = layui.table;
      var laydate = layui.laydate;
      var $ = layui.jquery;
      var form = layui.form;
      var tree = layui.tree;

      $(document).on('click', '#admin-management', function() {

        layer.open({
          //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
          type: 1,
          title: "新建角色",
          area: ['600px', '500px'],
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


      
      //基本演示
 


            //获取权限名称
            $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "all/permission",
        method: 'get',
        dataType: 'json',
        success: function(res) {console.log(res);
          
          tree.render({
            elem: '#roleScope'
            ,data: res
            ,showCheckbox: true  //是否显示复选框
            ,showLine:false
            ,id: 'demoId1'
        
          });
          if (res.status == 200) {
            
              //$("#roleScope").html(optionData);
              //$("#updateRoleScope").html(optionData);
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

      function getChecked_list(data) {
        var id = "";
        var name ="";
        $.each(data, function (index, item) {
            if (id != "") {
                id = id + "," + item.id;
                if(item.parent_id != null){
                  name = name + "," + item.name;
                }
              
            }
            else {
                id = item.id;
                if(item.parent_id != null){
                    name = item.name;
                }
            }
            var i = getChecked_list(item.children);
            if (i != "") {
                id = id + "," + i;
                name = name + "," + i;
            }
        });
        return name;
    }


      //监听提交
      form.on('submit(create)', function(data) {
        var checkData = tree.getChecked('demoId1');

                var list = new Array();

                list = getChecked_list(checkData);
                console.log(data.field);
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "add/role",
          method: 'POST',
          data: {per:list,name:data.field.name},
          dataType: 'json',
          success: function(res) {
            console.log(res);
            if (res.status == 200) {
              layer.msg('创建角色名称成功', {
                offset: '15px',
                icon: 1,
                time: 1000
              }, function() {
                location.href = 'role';
              })
            } else {
              layer.msg('填写错误或角色名重复', {
                offset: '15px',
                icon: 2,
                time: 3000
              }, function() {
                location.href = 'role';
              })
            }
          }
        });
        return false;
      });

      table.render({
        url: "query/role" //数据接口
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
              field: 'name',
              title: '角色名称',
            
            }, {
              field: 'created_at',
              title: '创建时间',
             
            }, {
              field: 'updated_at',
              title: '更新时间',
             
            },  {
              fixed: 'right',
              title: "操作",
              width: 250,
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

      
      table.on('tool(user)', function (obj) {
            var data = obj.data;
         console.log(data);
           if (obj.event === 'del') {
     
                layer.confirm('真的删除么', function (index) {
                    $.ajax({
                        url: "del/role",
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
                title: "分配权限",
                area: ['600px','600px'],
                content: $("#popUpdateTest") //引用的弹出层的页面层的方式加载修改界面表单
              });

          
                        //获取权限名称
            $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "have/permission",
        method: 'post',
        dataType: 'json',
        data:{id:data.id},
        success: function(res) {
           
          tree.render({
            elem: '#updateRoleScope'
            ,data: res
            ,showCheckbox: true  //是否显示复选框
            ,showLine:false
            ,id: 'demoId2'
        
          });
          if (res.status == 200) {
             

           
            //$("#updateRoleScope").html(res);
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
             
                    //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
                    form.val("formUpdate", data);
                    setFormValue(obj,data);
                }
            
        });

        function setFormValue(obj, data) {
        form.on('submit(editAccount)', function(massage) {
          var checkData = tree.getChecked('demoId2');

          var list = new Array();

          list = getChecked_list(checkData);
         
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/permission",
            type: 'post',
            data: {
              id: data.id,
              permission: list
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

    })
  </script>
</body>

</html>