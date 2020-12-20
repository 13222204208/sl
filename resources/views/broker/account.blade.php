<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>后台帐号</title>
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



  <div class="mainTop layui-clear">

    <div class="fr">
      <form class="layui-form" action="">
        <div class="layui-form-item">

          <div class="layui-inline" style="margin:20px">
            <div class="layui-input-inline">
              <button type="button" class="layui-btn layui-btn-blue" id="admin-management">新建帐号</button>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>

  <div class="layui-row" id="layuiadmin-form-admin" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" style="margin:20px">

      <div class="layui-form-item">
        <label class="layui-form-label">帐号</label>
        <div class="layui-input-block">
          <input type="text" name="account" required lay-verify="required" autocomplete="off" placeholder="请输入帐号" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
          <input type="password" name="password" required lay-verify="required" autocomplete="off" placeholder="请输入密码" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
          <input type="text" name="name" required lay-verify="required" autocomplete="off" placeholder="请输入名称" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">部门选择</label>
      <div class="layui-input-block" id="updateBranchScope" >


      </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">角色选择</label>
      <div class="layui-input-block" id="roleScope" >


      </div>
      </div>

      <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="createAccount">保存</button>
          {{--    <button type="reset" class="layui-btn layui-btn-primary">重置</button>  --}}
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="layui-row" id="update-info" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="accountUpdate" style="margin:20px">

      <div class="layui-form-item">
        <label class="layui-form-label">新的姓名</label>
        <div class="layui-input-block">
          <input type="text" name="name" required lay-verify="required" autocomplete="off" placeholder="请输入修改的姓名" value="" class="layui-input">
        </div>
      </div>

      {{-- <div class="layui-form-item">
        <label class="layui-form-label">新的密码</label>
        <div class="layui-input-block">
          <input type="password" name="password" required lay-verify="required" autocomplete="off" placeholder="请输入新的密码" value="" class="layui-input">
        </div>
      </div> --}}


      <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="updateAccount">修改</button>
            {{--  <button type="reset" class="layui-btn layui-btn-primary">重置</button>  --}}
          </div>
        </div>
      </div>
    </form>
  </div>


  <div class="layui-row" id="popUpdateTest" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">

      <div class="layui-form-item">
        <label class="layui-form-label">部门选择</label>
      <div class="layui-input-block" id="updateBranchName" >


      </div>
      </div>


      <div class="layui-form-item">
        <label class="layui-form-label">角色选择</label>
      <div class="layui-input-block" id="updateRoleScope" >


      </div>
      </div>


      <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="editAccount">修改</button>
            {{--  <button type="reset" class="layui-btn layui-btn-primary">重置</button>  --}}
          </div>
        </div>
      </div>
    </form>
  </div>



  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
  <script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="update">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">分配</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  </script>


  <div id="test12" class="demo-tree-more"></div>
  <script src="/layuiadmin/layui/layui.js"></script>

  <script>
    layui.use(['table', 'laydate', 'jquery', 'form','tree'], function() {
      var table = layui.table;
      var $ = layui.jquery;
      var form = layui.form;
      var tree = layui.tree;

                              //获取部门名称
                              $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "have/branch",
        method: 'post',
        dataType: 'json',
        success: function(res) {
           
          tree.render({
            elem: '#updateBranchScope'
            ,data: res
            ,showCheckbox: true  //是否显示复选框
            ,showLine:false
            ,id: 'demoId2'
        
          });
          if (res.status == 200) {
           
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


      $(document).on('click', '#admin-management', function() {
        layer.open({
          //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
          type: 1,
          title: "新建帐号",
          area: ['620px', '400px'],
          content: $("#layuiadmin-form-admin") //引用的弹出层的页面层的方式加载修改界面表单
        });
      });

      //添加帐号

      function getChecked_list(data) {
        var id = "";
        var name ="";
        $.each(data, function (index, item) {
            if (id != "") {
                id = id + "," + item.id;
                  name = name + "," + item.name;
                
              
            }
            else {
                id = item.id;
                    name = item.name;
                
            }
            var i = getChecked_list(item.children);
            if (i != "") {
                id = id + "," + i;
                name = name + "," + i;
            }
        });
        return id;
    }

      form.on('submit(createAccount)', function(data) {

        var len=$(".education2:checked").length;
        if(len>1){
          $(data.elem).next().attr("class","layui-unselect layui-form-checkbox");
          $(data.elem).prop("checked",false);
          layer.msg('最多只能选一项！',{icon:5});
          return false;
        }

        var checkData = tree.getChecked('demoId2');
//console.log(checkData); return false;
var list = new Array();

list = getChecked_list(checkData);
data = data.field;
data['branch'] =list;
        console.log(data);return false;
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "add/account",
          method: 'POST',
          data: data,
          dataType: 'json',
          success: function(res) {
            console.log(res);
            if (res.status == 200) {
              layer.msg('新建帐号成功', {
                offset: '15px',
                icon: 1,
                time: 2000
              }, function() {
                location.href = 'account';
              })
            } else if (res.status == 403) {
              layer.msg('填写错误或帐号重复', {
                offset: '15px',
                icon: 2,
                time: 3000
              }, function() {
                location.href = 'account';
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
          //console.log(res); return false;
          status = res.status;
           role_name = res.role_name; //console.log( role_name[0].name); return false
          if (res.status == 200) {
            optionData = "";
              for (var i = 0; i < role_name.length; i++) {
              var t = role_name[i];
              optionData += '<input class="education2" type="checkbox" name="limits[]" lay-skin="primary" title="' + t.name + '" value="' + t.id+ '">';
            }

              console.log(optionData);
              $("#roleScope").html(optionData);
              $("#updateRoleScope").html(optionData);
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

      table.render({
        height: 600,
        url: "query/account" //数据接口
          ,
        limit:15,
        page: true //开启分页
          ,
        elem: '#LAY_table_user',
        cols: [
          [
            {
              type:'numbers',
              title:'序号',
              algin:'center',
              width:80,
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
              title: '名称',
           
            }, {
              field: 'branch',
              title: '部门',
           
            },{
                            field: 'status',
                            title: '状态',
                            //width:150,
                            templet: function(d) {
                                if (d.status == 1) {
                                  return '<div class="layui-input-block">'+
                                    '<input type="checkbox" class="switch_checked" lay-filter="switchGoodsID"'+ 'switch_goods_id="'+ d.id+
                                     '" lay-skin="switch" checked '+ 'lay-text="正常|已禁用">'+
                                  '</div>';
                                }else{
                                    return '<div class="layui-input-block">'+
                                        '<input type="checkbox" class="switch_checked" lay-filter="switchGoodsID"'+ 'switch_goods_id="'+ d.id+
                                         '" lay-skin="switch" lay-text="正常|已禁用">'+
                                      '</div>';
                                }
                              }
                        },  {
              fixed: 'right',
              title: "操作",
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

      form.on('switch(switchGoodsID)',function (data) {
                
                //开关是否开启，true或者false
                var checked = data.elem.checked;

                if(checked === false){
                    checked = 2;
                }else{
                    checked = 1;
                }

                //获取所需属性值
                var switch_goods_id = data.elem.attributes['switch_goods_id'].nodeValue;
                console.log(checked);
                console.log(switch_goods_id);
                $.ajax({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "account/status"+'/'+switch_goods_id ,
                    type: 'patch',
                    data: {
                        status:checked
                    },
                    success: function(msg) {
                      console.log(msg);
                      if (msg.status == 200) {

            
                        form.render();

                        layer.msg("修改成功", {
                            icon: 1
                          });
                      } else {
                        layer.msg("修改失败", {
                          icon: 5
                        });
                      }
                    }
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
            }else if (obj.event === 'update') {
            if (data.id == 1) {
              layer.msg("超级管理员无法修改", {icon: 2});
              return false;
            }
            layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "修改帐号信息",
                        area: ['420px', '330px'],
                        content: $("#update-info")//引用的弹出层的页面层的方式加载修改界面表单
                    });
                    console.log(data);
                    form.val("accountUpdate", data);
                form.render();
                    form.on('submit(updateAccount)', function(massage) {
          massage= massage.field; console.log(data);

          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/account",
            type: 'post',
            data: {
              id: data.id,
              name:massage.name,
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
                    name: massage.name,
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


            } else if (obj.event === 'edit') {
              if (data.id == 1) {
              layer.msg("超级管理员拥有所有权限", {icon: 6});
              return false;
            }
                    layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "分配帐号角色",
                        area: ['420px', '530px'],
                        content: $("#popUpdateTest")//引用的弹出层的页面层的方式加载修改界面表单
                    });

                                                  //获取部门名称
                                                  $.ajax({
                                                    headers: {
                                                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    url: "user/branch",
                                                    method: 'post',
                                                    data:{
                                                      id:data.id
                                                    },
                                                    dataType: 'json',
                                                    success: function(res) {
                                                      // console.log(res); return false;
                                                      tree.render({
                                                        elem: '#updateBranchName'
                                                        ,data: res.data.pers
                                                        ,showCheckbox: true  //是否显示复选框
                                                        ,showLine:false
                                                        ,checked:false
                                                        ,id: 'demoId3'
                                                    
                                                      });

                                                      branch=  res.data.branch[0].branch;
                                                      branchid= branch.split(',').map(Number);
                                                      branchid.shift();
                                                  
                                                      tree.setChecked('demoId3',branchid);
                                                     
                                                      form.render();     

                                                      if (res.status == 200) {
                                                       
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


                           //获取权限名称
                           $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "have/role",
        method: 'post',
        dataType: 'json',
        data:{id:data.id},
        success: function(res) {
          status = res.status;
          arr = res.data;
          if (res.status == 200) {
             
             array= new Array();
             zh = new Array();
            $("#updateRoleScope input[type='checkbox']").each(function(){
                var permission = $(this).attr("title");
                array.push(permission);
              });

              zh= array;
            optionData = "";
            let arraySel = Object.values(arr)
            for (let index = 0; index < array.length; index++) {
                const element = array[index];
                const t = zh[index];
                istrue = false;
                for (let i = 0; i < arraySel.length; i++) {
                  if (arraySel[i]== element) {
                    istrue = true;
                  }   
                }
                if (istrue) {
                  optionData += '<input class="education" type="checkbox" checked  name="limits" lay-skin="primary" title="' + t + '" value="' + element + '">';
                } else {
                  optionData += '<input  class="education" type="checkbox"  name="limits" lay-skin="primary" title="' + t + '" value="' + element + '">';
                }
              }

            console.log(optionData);
            $("#updateRoleScope").html(optionData);
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
         
          var checkData = tree.getChecked('demoId3');
        
          //console.log(checkData); return false;
          var list = new Array();
          
          list = getChecked_list(checkData);
         

          var len=$(".education:checked").length;
          if(len>1){
            $(massage.elem).next().attr("class","layui-unselect layui-form-checkbox");
            $(massage.elem).prop("checked",false);
            layer.msg('最多只能选一项！',{icon:5});
            return false;
          }
          massage= massage.field.limits;

          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/role",
            type: 'post',
            data: {
              id: data.id,
              role:massage,
              branch:list
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