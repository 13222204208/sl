<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>楼盘详情</title>
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

 

  <table class="layui-hide" id="tenant_table_user" lay-filter="user"></table>


  


  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
   <script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">查看房号上的租户</a>
  
  </script> 


  <script src="/layuiadmin/layui/layui.js"></script>

  <script>
    layui.use(['table', 'laydate', 'jquery', 'form'], function() {
      var table = layui.table;
      var $ = layui.jquery;
      var form = layui.form;


      

      table.render({
        height: 600,
        url: "info" //数据接口
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
            },{
              field: 'houses_name',
              title: '楼盘名称',
            }, {
              field: 'map',
              title: '地图位置坐标',
            }, {
              field: 'houses_address',
              title: '地址',
            },{
              field: 'city',
              title: '所属区县',
            },{
              field: 'business_area',
              title: '商圈',
            },{
              field: 'property_type',
              title: '物业类型',
            },   {
              fixed: 'right',
              title: "操作",
              width: 200,
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
        id: 'testReload',
        title: '后台用户',
        totalRow: true

      });

      table.on('tool(user)', function (obj) {
            var data = obj.data;
         
     if (obj.event === 'edit') {
          console.log(data); 
          table.render({
        height: 600,
        url: "tenant/info/"+data.houses_num 
          ,
        page: true //开启分页
          ,
        elem: '#tenant_table_user',
        cols: [
          [

            {
              type: 'numbers',
              title: '序号',
              width: 80,
            },{
              field: 'tenant_name',
              title: '租户名称',
            },{
              field: 'start_time',
              title: '合同开始时间',
            },{
              field: 'stop_time',
              title: '合同结束时间',
            },{
              field: 'state',
              title: '在租状态',
            },{
              field: 'broker_name',
              title: '所属经纪人',
            },{
              field: 'broker_phone',
              title: '所属经纪人手机号',
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

                }
            
        });


      

    });
  </script>

</body>

</html>