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
        <button class="layui-btn" data-type="reload" value="0" id="admin-management">添加协议</button>
        <div class="layui-inline" style="color:gray" id="lp_address">
        </div>
    </div>

    <div class="layui-row" id="layuiadmin-form-admin" style="display:none;">
        <form class="layui-form layui-from-pane" required lay-verify="required" style="margin:20px">

            <div class="layui-form-item">
                
               
                    <input type="text" name="title" required lay-verify="type_name" autocomplete="off"
                        placeholder="标题" value="" class="layui-input">
               
            </div>

            <div class="layui-form-item">   
              <textarea class="layui-textarea" name="content" id="LAY_demo1" style="display: none">  
                
              </textarea>
            </div>  

            <div class="layui-form-item">
                
               
                <input type="text" name="key" required lay-verify="type_name" autocomplete="off"
                    placeholder="关键字" value="" class="layui-input">
           
        </div>
              <br>      

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

{{--  
            <div class="layui-form-item">
                
               
                <input type="text" name="title" required lay-verify="type_name" autocomplete="off"
                    placeholder="标题" value="" class="layui-input">
           
        </div>  --}}
            <div class="layui-form-item">   
                <textarea class="layui-textarea" name="content" id="p_content" style="display: none" lay-verify="content" >  
                  
                </textarea>
              </div>  


<br>
            <div class="layui-form-item ">
                <div class="layui-input-block">
                    <div class="layui-footer" style="left: 0;">
                        <button class="layui-btn" lay-submit="" lay-filter="editAccount">修改</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>

    </script>

    <script src="/layuiadmin/layui/layui.js"></script>
    <script>
        layui.use(['table', 'laydate', 'jquery', 'form','layedit'], function () {
            var table = layui.table;
            var laydate = layui.laydate;
            var $ = layui.jquery;
             form = layui.form;
             layedit = layui.layedit;

            layedit.build('LAY_demo1'); //建立编辑器

            $(document).on('click', '#admin-management', function () {
                layer.open({
                    //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                    type: 1,
                    title: "新建服务协议",
                    area: ['600px', '600px'],
                    content: $("#layuiadmin-form-admin") //引用的弹出层的页面层的方式加载修改界面表单
                });
            });

            form.verify({
                type_name: function (value) {
                    if (value.length > 8) {
                        return '最多只能八个字符';
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
                    url: "create/protocol",
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
                                $(".layui-laypage-btn").click();
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

            table.render({
                url: "gain/protocol" //数据接口
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
                            field: 'title',
                            title: '标题',
                            width:150
                        }, {
                            field: 'content',
                            title: '内容',
                      
                        },{
                            field: 'key',
                            title: '关键字',
                            width:150
                      
                        }, {
                            fixed: 'right',
                            title: "操作",
                            align: 'center',
                            toolbar: '#barDemo'
                        }
                    ]
                ],
                parseData: function (res) { //res 即为原始返回的数据
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
                        title: "修改内容",
                        area: ['600px', '500px'],
                        content: $("#popUpdateTest") //引用的弹出层的页面层的方式加载修改界面表单
                    });
                    index= layedit.build('p_content');
                    layedit.setContent(index, data.content);
                   
                    form.verify({
                        content: function(value) { 
                             return layedit.sync(index);
                            }
                    });
                
                    form.val("formUpdate", data);
                    setFormValue(obj, data);
                } 

            });

      

            function setFormValue(obj, data) {
                form.on('submit(editAccount)', function (massage) {
                    massage = massage.field;
                    layedit.sync(index)
                    console.log(massage);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "edit/protocol",
                        type: 'post',
                        data: {
                            id: data.id,
                            content:  massage.content,
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
                                        content: massage.content,
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
