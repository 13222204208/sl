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
<br>
    <form class="layui-form layui-from-pane" required lay-verify="required" action="">
      <div class="layui-form-item">
   
        <div class="layui-inline">
          <label class="layui-form-label">开始时间：</label>
          <div class="layui-input-inline">
  
            <input type="text" name="startTime" class="layui-input" id="startTime" placeholder="yyyy-MM-dd HH:mm:ss">
          </div>
  
        </div>
        <input type="hidden" value="" name="broker_id" id="userId">
        <div class="layui-inline">
          <label class="layui-form-label">结束时间：</label>
          <div class="layui-input-inline">
            <input type="text" class="layui-input" name="stopTime" id="stopTime" placeholder="yyyy-MM-dd HH:mm:ss">
          </div>
  
        </div>
        <div class="layui-inline">
          <div class="layui-input-inline" style="margin-right: -120px">
            <button type="button" class="layui-btn layui-btn-blue" lay-submit=""  lay-filter="search">查询</button>
          </div>
        </div>
      </div>
  
    </form>

    
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">



      <div class="layui-form-item">
        <label class="layui-form-label">到过多少楼盘</label>
        <div class="layui-input-block">
          <input type="text" id="comeNum" required lay-verify="required" autocomplete="off" placeholder="" value="0" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">提交过多少记录</label>
        <div class="layui-input-block">
          <input type="text" id="cleanNum" required lay-verify="required" autocomplete="off" placeholder="" value="0" class="layui-input">
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
      laydate = layui.laydate;

      laydate.render({
        elem: '#startTime',
        type: 'datetime',
        max: getNowFormatDate()
      });
      //日期时间范围
      laydate.render({
        elem: '#stopTime',
        type: 'datetime',
        max: getNowFormatDate()
      });

      
      function getNowFormatDate() {
        var date = new Date();
        var seperator1 = "-";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
          month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
          strDate = "0" + strDate;
        }
        var currentdate = date.getFullYear() + seperator1 + month +
          seperator1 + strDate + " " + date.getHours() + seperator2 +
          date.getMinutes() + seperator2 + date.getSeconds();
        return currentdate;
      }




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
              field: 'branch_value',
              title: '部门',
            
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
                        title: "经纪人工作信息",
                        area: ['520px', '430px'],
                        content: $("#popUpdateTest")//引用的弹出层的页面层的方式加载修改界面表单
                    });
                    //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
                    $('#userId').val(data.id);
                    form.on('submit(search)', function(data) {
                      var data = data.field;
                       
                        $.ajax({
                          headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                          },
                          url: "broker/record",
                          type: 'post',
                          data: data,
                          success: function(msg) {
                            console.log(msg);
                            if (msg.status == 200) {
                              $("#comeNum").val(msg.data.comeNum);
                          $("#cleanNum").val(msg.data.cleanNum);
                          
                            } else {
                              layer.msg("修改失败", {
                                icon: 5
                              });
                            }
                          }
                        })
                      });

                  
                    $.ajax({
                      headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      url: "info",
                      type: 'post',
                      data: {
                        id: data.id
                      },
                      success: function(msg) {
                        console.log(msg);
                   
                        if (msg.status == 200) {
                          $("#comeNum").val(msg.data.comeNum);
                          $("#cleanNum").val(msg.data.cleanNum);
          
                        } else {
                          layer.msg("失败", {
                            icon: 5
                          });
                        }
                      }
                    });
                }
            
        });
      

    });
  </script>

</body>

</html>