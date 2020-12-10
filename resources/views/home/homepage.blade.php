<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>主页</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
</head>

<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">

      <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
          <div class="layui-card-header">
            扫楼数量统计
           
          </div>
          <div class="layui-card-body layuiadmin-card-list">
            <p class="layuiadmin-big-font" id="register_num">0</p>
            <p>
              总数
         
            </p>
            <form class="layui-form layui-from-pane" required lay-verify="required" action="">
              <div class="layui-form-item">
           
                <div class="layui-inline">
                  <label class="layui-form-label">开始时间：</label>
                  <div class="layui-input-inline">
          
                    <input type="text" name="startTime" class="layui-input" id="startTime" placeholder="yyyy-MM-dd HH:mm:ss">
                  </div>
          
                </div>
          
                <div class="layui-inline">
                  <label class="layui-form-label">结束时间：</label>
                  <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="stopTime" id="stopTime" placeholder="yyyy-MM-dd HH:mm:ss">
                  </div>
          
                </div>
                <div class="layui-inline">
                  <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-blue" lay-submit=""  lay-filter="search">查询</button>
                  </div>
                </div>
              </div>
          
            </form>
            <p class="layuiadmin-big-font" id="date_num">无</p>
            <p>
              时间段内数量
         
            </p>

          </div>
        </div>
      </div>


      <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
          <div class="layui-card-header">
            租户信息数量
           
          </div>
          <div class="layui-card-body layuiadmin-card-list">
            <p class="layuiadmin-big-font" id="tenant_num">0</p>
            <p>
              总数
         
            </p>
            <form class="layui-form layui-from-pane" required lay-verify="required" action="">
              <div class="layui-form-item">
           
                <div class="layui-inline">
                  <label class="layui-form-label">开始时间：</label>
                  <div class="layui-input-inline">
          
                    <input type="text" name="startTime" class="layui-input" id="startTimeTenant" placeholder="yyyy-MM-dd HH:mm:ss">
                  </div>
          
                </div>
          
                <div class="layui-inline">
                  <label class="layui-form-label">结束时间：</label>
                  <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="stopTime" id="stopTimeTenant" placeholder="yyyy-MM-dd HH:mm:ss">
                  </div>
          
                </div>
                <div class="layui-inline">
                  <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-blue" lay-submit=""  lay-filter="tenantSearch">查询</button>
                  </div>
                </div>
              </div>
          
            </form>
            <p class="layuiadmin-big-font" id="tenant_date_num">无</p>
            <p>
              时间段内数量
         
            </p>

          </div>
        </div>
      </div>


      <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
          <div class="layui-card-header">
            到期租户预警 一个月内
       
          </div>
          <div class="layui-card-body layuiadmin-card-list">
          
          <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
         
          </div>
        </div>
      </div>

      <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
          <div class="layui-card-header">
            楼盘空置率
       
          </div>
          <div class="layui-card-body layuiadmin-card-list">
            已出租即有租户的房间<br>占总体房间数的百分比
            <p class="layuiadmin-big-font" id="tenant_kong">0</p>
          </div>
        </div>
      </div>

      <div class="layui-col-sm12">

        <div class="layui-card">
          <div class="layui-card-header">
            租户类型占比
            <div class="layui-btn-group layuiadmin-btn-group">
           {{--     <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">去年</a>
              <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">今年</a>  --}}
            </div>
          </div>
          <div class="layui-card-body">
            <div class="layui-row">
              <div class="layui-col-sm8">
                <div class="layui-col-sm12">
                  <div id="user-reg" style="height: 350px;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

       


      </div>
    </div>

  </div>
  </div>
  </div>

  <script src="/layuiadmin/layui/layui.js"></script>
  <script>
    layui.config({
      base: '/layuiadmin/lib/extend/' //静态资源所在路径
    }).use(['echarts', 'jquery', 'layer','laydate','form','table'], function() {
      var $ = layui.jquery;
        echarts = layui.echarts;
      var layer = layui.layer ;
      var form = layui.form;
       laydate= layui.laydate;
       table= layui.table;

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

      laydate.render({
        elem: '#startTimeTenant',
        type: 'datetime',
        max: getNowFormatDate()
      });
      //日期时间范围
      laydate.render({
        elem: '#stopTimeTenant',
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

      $.ajax({ 
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "clean/count",
        method: 'get',
        dataType: 'json',
        success: function(res) {
          //  console.log(res);
          if (res.status == 200) {
            $("#register_num").html(res.num);
          } else if (res.status == 403) {
            layer.msg('错误', {
              offset: '15px',
              icon: 2,
              time: 3000
            })
          }
        }
      });

      form.on('submit(search)', function(data) {
        var data = data.field;
          console.log(data);
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "clean/date",
            type: 'post',
            data: data,
            success: function(msg) {
              console.log(msg);
              if (msg.status == 200) {
                $("#date_num").html(msg.dateNum);

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
          url: "tenant/kong",
          method: 'get',
          dataType: 'json',
          success: function(res) {
              
            if (res.status == 200) {
              $("#tenant_kong").html(res.num + '%');
            } else if (res.status == 403) {
              layer.msg('错误', {
                offset: '15px',
                icon: 2,
                time: 3000
              })
            }
          }
        }); 




        $.ajax({ 
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "tenant/count",
          method: 'get',
          dataType: 'json',
          success: function(res) {
            //  console.log(res);
            if (res.status == 200) {
              $("#tenant_num").html(res.num);
            } else if (res.status == 403) {
              layer.msg('错误', {
                offset: '15px',
                icon: 2,
                time: 3000
              })
            }
          }
        });
  
        form.on('submit(tenantSearch)', function(data) {
          var data = data.field;
            console.log(data);
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: "tenant/date",
              type: 'post',
              data: data,
              success: function(msg) {
                console.log(msg);
                if (msg.status == 200) {
                  $("#tenant_date_num").html(msg.dateNum);
  
                } else {
                  layer.msg("修改失败", {
                    icon: 5
                  });
                }
              }
            })
          });


   


    
      $.ajax({ //租户类型占比
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "tenant/type",
        method: 'get',
        dataType: 'json',
        success: function(res) {
          
          var data = res.data;

          if (res.status == 200) {
            arr = [];
            peo = [];
            
            for (let i = 0; i < data.length; i++) {
              if(data[i].is_we_company == 1){
                data[i].is_we_company = "我司租户";
              }else{
                data[i].is_we_company = "外部租户";
              }
              arr.push(data[i].is_we_company);
              peo.push({'name':data[i].is_we_company,'value':data[i].total});
            }

        var optionchartBing = {
          title: {
              text: '租户类型',
              subtext: '租户类型占比饼状图', //副标题
              x: 'center' //标题居中
          },
          tooltip: {
              // trigger: 'item' //悬浮显示对比
          },
          legend: {
              orient: 'vertical', //类型垂直,默认水平
              left: 'left', //类型区分在左 默认居中
              data: arr
          },
          series: [{
              type: 'pie', //饼状
              radius: '60%', //圆的大小
              center: ['50%', '50%'], //居中
              data:peo
          }]
      };

      var dom = document.getElementById("user-reg");
      var myChart = echarts.init(dom);
      myChart.setOption(optionchartBing, true);
            


          } else if (res.status == 403) {
            layer.msg('错误', {
              offset: '15px',
              icon: 2,
              time: 3000
            })
          }
        }
      });

      table.render({
        height: 370,
        url: "due/tenant" //数据接口
          ,
        page: true //开启分页
          ,
        elem: '#LAY_table_user',
        cols: [
          [

          //  {
          //    field: 'id',
           //   title: 'ID',
      
             // sort: true
            //},
            
            
            {
              field: 'tenant_name',
              title: '租户名称',
        
            }, {
              field: 'tenant_user',
              title: '联系人',
        
            }, {
              field: 'stop_time',
              title: '合同到期时间',
        
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


    });
    
  </script>
</body>

</html>