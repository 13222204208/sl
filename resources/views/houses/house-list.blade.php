<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>楼盘列表 </title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
</head>

<body>

  <div class="layui-row" id="myNum" style="display:none;">

  <table class="layui-hide" id="LAY_table_user"></table>

</div>


      <table class="layui-hide" id="demoTb1" lay-filter="user"></table>

      <script type="text/html" id="barDemo" >
        <a class="layui-btn layui-btn-xs" lay-event="show">查看房号上的租户</a>
      
      </script> 


    <script src="/layuiadmin/layui/layui.js"></script>
    <script>
        layui.config({
            base: '/layuiadmin/modules/'  // 配置模块所在的目录
        }).use(['treeTable','table','form','jquery'], function () {
            var treeTable = layui.treeTable;
             table = layui.table;
            var form = layui.form;
            var $ = layui.jquery;
        // 渲染树形表格
     
                treeTable.render({
                elem: '#demoTb1',
                treeDefaultClose: false,
                treeLinkage: false,	
                url: 'gain/house/num',
                tree: {
                    iconIndex: 2,           // 折叠图标显示在第几列
                    isPidData: true,        // 是否是id、pid形式数据
                    idName: 'id',  // id字段名称
                    pidName: 'parent_id'     // pid字段名称
                },
                cols: [[
                    {type: 'numbers'},
                    {type: 'checkbox'},
                    {field: 'type_name', title: '楼盘信息'},
                    
                    {align: 'center', toolbar: '#barDemo', title: '操作', width: 220}
                ]],
                
            });
     

        treeTable.on('tool(demoTb1)', function (obj) {
            var event = obj.event;
            var data = obj.data;
            if (event === 'show') {
              var id= data.id; console.log(id);
              table.render({
                url: "look/num"+'/'+id //数据接口
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
                            field: 'tenant_name',
                            title: '租户名称',
                            width:100
                        }, {
                            field: 'tenant_user',
                            title: '租户联系人',
                            width:180
                        },{
                            field: 'start_time',
                            title: '合同开始时间',
                            width:120
                        },{
                            field: 'stop_time',
                            title: '合同结束时间',
                            width:120
                        },{
                            field: 'pay_time',
                            title: '下次付款时间',
                            width: 120
                        },{
                            field: 'pay_type',
                            title: '付款方式',
                            width: 100
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

            layer.open({
                    //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                    type: 1,
                    title: "租户列表",
                    area: ['900px', '300px'],
                    content: $("#myNum") //引用的弹出层的页面层的方式加载修改界面表单
                });
            } 
        });



        });
    </script>
</body>

</html>
