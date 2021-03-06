<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>创建部门 </title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
</head>

<body>
{{--      <div class="demoTable" style="margin:30px;">
        <button class="layui-btn" data-type="reload" value="0" id="admin-management">添加分类</button>
        <div class="layui-inline" style="color:gray" id="lp_address">
        </div>
    </div>  --}}

    <div class="layui-row" id="layuiadmin-form-admin" style="display:none;">
        <form class="layui-form layui-from-pane" required lay-verify="required" style="margin:20px">

            <div class="layui-form-item">
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-block">
                   {{--  <input type="text" name="name" required lay-verify="name" autocomplete="off"
                        placeholder="请输入分类名称" value="" class="layui-input"> --}}

                        <textarea placeholder="可以添加多条数据，用逗号 , 分隔"  name="name" class="layui-textarea"></textarea>
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
                    <input type="text" name="name" required lay-verify="name" autocomplete="off"
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

    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="add">添加子部门</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>

    </script>

    <script src="/layuiadmin/layui/layui.js"></script>
    <script>
        layui.config({
            base: '/layuiadmin/modules/'  // 配置模块所在的目录
        }).use(['table', 'laydate', 'jquery', 'treeTable','form'], function () {
             table = layui.table;
            var laydate = layui.laydate;
            var $ = layui.jquery;
            var form = layui.form;
             treeTable = layui.treeTable;

             var  tableIns = treeTable.render({
                elem: '#LAY_table_user',
                url: 'gain/branch',
                tree: {
                    iconIndex: 2,           // 折叠图标显示在第几列
                    isPidData: true,        // 是否是id、pid形式数据
                    idName: 'id',  // id字段名称
                    pidName: 'parent_id'     // pid字段名称
                },
                cols: [[
                    {type: 'numbers'},
                    {type: 'checkbox'},
                    {field: 'name', title: '组织名称'},
                    
                    {align: 'center', toolbar: '#barDemo', title: '操作', width: 220}
                ]]
            });


            $(document).on('click', '#admin-management', function () {
                layer.open({
                    //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                    type: 1,
                    title: "新建分类",
                    area: ['600px', '300px'],
                    content: $("#layuiadmin-form-admin") //引用的弹出层的页面层的方式加载修改界面表单
                });
            });

            form.verify({
                name: function (value) {
                    if (value.length > 30) {
                        return '最多只能30个字符';
                    }
                }
            });


            //监听提交
            form.on('submit(create)', function (data) {
                console.log(data.field);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "create/name",
                    method: 'POST',
                    data: data.field,
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);
                        if (res.status == 200) {
                            layer.msg('创建成功', {
                                offset: '15px',
                                icon: 1,
                                time: 1000
                            }, function () {
                              
                                //$(".layui-laypage-btn").click();
                                layer.closeAll();
                                tableIns.reload();
                  
                            })
                        } else if (res.status == 403) {
                            layer.msg('填写错误或重复', {
                                offset: '15px',
                                icon: 2,
                                time: 3000
                            }, function () {
                                location.href = 'created';
                            })
                        }
                    }
                });
                return false;
            });

 


            treeTable.on('tool(LAY_table_user)', function (obj)  {
                var data = obj.data;
                console.log(data);
                if (obj.event === 'del') {

                    layer.confirm('真的删除此分类么', function (index) {
                        $.ajax({
                            url: "del/name",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            type: "POST",
                            data: {
                                id: data.id,
                                tree:data.tree
                            },
                            success: function (msg) {

                                if (msg.status == 200) {
                                    //删除这一行
                                    obj.del();
                                    //关闭弹框
                                    layer.close(index);
                                    layer.msg("删除成功", {
                                        icon: 6
                                    });
                                } else {
                                    layer.msg("删除失败", {
                                        icon: 5
                                    });
                                }
                            }
                        });
                        return false;
                    });
                } else if (obj.event === 'edit') {
                    layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "修改名称",
                        area: ['600px', '300px'],
                        content: $("#popUpdateTest") //引用的弹出层的页面层的方式加载修改界面表单
                    });
                    //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
                    form.val("formUpdate", data);
                    setFormValue(obj, data);
                } else if (obj.event === 'add') {

                    layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "新建子部门",
                        area: ['600px', '300px'],
                        content: $("#layuiadmin-form-admin") //引用的弹出层的页面层的方式加载修改界面表单
                    });


                    console.log(data.name);
                    $("#typeNameId").val(data.name);
                    $("#lp_address").append(data.name);
                    $("#PId").val(data.id);
    
                }

            });

            function setFormValue(obj, data) {
                form.on('submit(editAccount)', function (massage) {
                    massage = massage.field;
                    console.log(data);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "update/name",
                        type: 'post',
                        data: {
                            id: data.id,
                            name: massage.name,
                        },
                        success: function (msg) {
                            console.log(msg);
                            if (msg.status == 200) {
                                layer.closeAll('loading');
                                layer.load(2);
                                layer.msg("修改成功", {
                                    icon: 6
                                });
                                setTimeout(function () {

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
            }

        })

    </script>
</body>

</html>
