<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>楼盘列表</title>
  <meta name="renderer" content="webkit">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
  <style>
      
  </style>
</head>
<body> 
 


 
<table class="layui-hide" id="LAY_table_user" lay-filter="user"></table> 
               
     
  <script type="text/html" id="barDemo">

  
  <!--   <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="resuse">查看</a> -->
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="edit">编辑</a>
  </script>     


<script src="/layuiadmin/layui/layui.js"></script>

<script>
 layui.use(['table', 'form', 'laydate', 'layer', 'jquery','upload'], function() {
      var table = layui.table;
      var laydate = layui.laydate;
      var form = layui.form;
      var util = layui.util;
      var layer = layui.layer;
      var $ = layui.jquery;
      var upload = layui.upload;


  
  //方法级渲染
  table.render({
    elem: '#LAY_table_user'
    ,url: 'query/platform'
    ,cols: [[
      {field:'id', title: 'ID', width:80, sort: true}
      ,{field:'platform_name', title: '分期', width:180, sort: true}
      ,{field:'show_name', title: '楼座', width:180}
      ,{field:'platform_type', title: '单元', width:120}
      ,{field:'platform_sort', title: '楼层', width:120}
      ,{field:'platform_img', title: '楼号',  width:260}
        templet: function(d) {
                if (d.state == 1) {
                  return "开启";
                }else if(d.state == "on"){
                  return "开启";
                }else{
                  return "关闭";
                }
              },}
      ,{
              fixed: 'right',
              title:"操作",
              width: 100,
              align: 'center',
              toolbar: '#barDemo'
            }
    ]]
    ,parseData: function(res) { //res 即为原始返回的数据
          return {
            "code": '0', //解析接口状态
            "msg": res.message, //解析提示文本
            "count": res.total, //解析数据长度
            "data": res.data //解析数据列表
          }
        }
    ,id: 'testReload'
    ,page: true
   
  });

  table.on('tool(user)', function(obj) { //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）
        if (layEvent === 'edit') { //编辑
          layer.open({
            //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
            type: 1,
            title: "编辑",
            area: ['620px', '550px'],
            content: $("#popUpdateTask") //引用的弹出层的页面层的方式加载修改界面表单
          });
           //console.log(data);return false;
          form.val("formUpdate", data);
          setFormValue(obj, data);
          form.render();
        } else if (layEvent === 'LAYTABLE_TIPS') {
          layer.alert('Hi，头部工具栏扩展的右侧图标。');
        }
      });
   
      function setFormValue(obj, data) {
        form.on('submit(setActivity)', function(massage) {
          massage= massage.field; 
          massage.id = data.id;
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/platform",
            type: 'post',
            data: massage,
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
                    platform_name:massage.platform_name,
                    show_name:massage.show_name,
                    platform_type:massage.platform_type,
                    platform_sort:massage.platform_sort,
                    platform_img:massage.platform_img,
                    state:massage.state,        
                  }); //修改成功修改表格数据不进行跳转              
                  layer.closeAll(); //关闭所有的弹出层

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