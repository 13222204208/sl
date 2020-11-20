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
            到期租户预警
       
          </div>
          <div class="layui-card-body layuiadmin-card-list">
          {{--    <p>申请笔数： <span class=" layui-badge layui-bg-green " id="with_apply_num">0</span></p>  --}}
         
          </div>
        </div>
      </div>

      <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
          <div class="layui-card-header">
            扫楼完成率
       
          </div>
          <div class="layui-card-body layuiadmin-card-list">
          {{--    <p>申请笔数： <span class=" layui-badge layui-bg-green " id="with_apply_num">0</span></p>  --}}
         
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

        <div class="layui-card">
          <div class="layui-card-header">
            资金流动趋势
            <div class="layui-btn-group layuiadmin-btn-group">
              <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">去年</a>
              <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">今年</a>
            </div>
          </div>
          <div class="layui-card-body">
            <div class="layui-row">
              <div class="layui-col-sm8">
                <div class="layui-col-sm12">
                  <div id="cityChart" style="height: 350px;"></div>
                </div>





                <!--               <div class="layui-col-sm4">
              <div class="layuiadmin-card-list">
                <p class="layuiadmin-normal-font">月访问数</p>
                <span>同上期增长</span>
                <div class="layui-progress layui-progress-big" lay-showPercent="yes">
                  <div class="layui-progress-bar" lay-percent="30%"></div>
                </div>
              </div>
              <div class="layuiadmin-card-list">
                <p class="layuiadmin-normal-font">月下载数</p>
                <span>同上期增长</span>
                <div class="layui-progress layui-progress-big" lay-showPercent="yes">
                  <div class="layui-progress-bar" lay-percent="20%"></div>
                </div>
              </div>
              <div class="layuiadmin-card-list">
                <p class="layuiadmin-normal-font">月收入</p>
                <span>同上期增长</span>
                <div class="layui-progress layui-progress-big" lay-showPercent="yes">
                  <div class="layui-progress-bar" lay-percent="25%"></div>
                </div>
              </div>
            </div> -->
              </div>
            </div>
          </div>
        </div>

        <div class="layui-card">
          <div class="layui-card-header">
            平台下注概况
            <div class="layui-btn-group layuiadmin-btn-group">
              <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">去年</a>
              <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">今年</a>
            </div>
          </div>
          <div class="layui-card-body">
            <div class="layui-row">
              <div class="layui-col-sm8">
                <div class="layui-col-sm12">
                  <div id="bottom-pour" style="height: 350px;"></div>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="layui-col-sm4">
          <div class="layui-card">
            <div class="layui-card-header">帐号监控(统计相同IP下登入的会员帐号)</div>
            <div class="layui-card-body">
              <table class="layui-table layuiadmin-page-table" lay-skin="line">
                <thead>
                  <tr>
                    <th>帐号</th>
                    <th>IP</th>
                    <th>最后登录时间</th>

                  </tr>
                </thead>
                <tbody id="repeatIP">
                  <tr>
                    <td><span class="first">yangpanda</span></td>
                    <td><span>127.0.0.1</span></td>
                    <td><i class="layui-icon layui-icon-log"> 11:20</i></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="layui-col-sm4">
          <div class="layui-card">
            <div class="layui-card-header">转帐监控(统计大额转入的会员帐号)</div>
            <div class="layui-card-body">
              <table class="layui-table layuiadmin-page-table" lay-skin="line">
                <thead>
                  <tr>
                    <th>帐号</th>
                    <th>转入金额</th>
                    <th>时间</th>
                  </tr>
                </thead>
                <tbody id="moneySwitch">
                  <tr>
                    <td><span class="first">yangpanda</span></td>
                    <td><span>100万</span></td>
                    <td><span>80万</span></td>
                    <td><i class="layui-icon layui-icon-log"> 11:20</i></td>
                  </tr>
         
            
                </tbody>
              </table>
            </div>
          </div>
        </div>


        <div class="layui-col-sm4">
          <div class="layui-card">
            <div class="layui-card-header">套利监控(统计频繁转出的会员帐号)</div>
            <div class="layui-card-body">
              <table class="layui-table layuiadmin-page-table" lay-skin="line">
                <thead>
                  <tr>
                    <th>帐号</th>
                    <th>转出金额</th>
                    <th>转出时间</th>
                  </tr>
                </thead>
                <tbody id="moneyRollout">
                  <tr>
                    <td><span class="first">yangpanda</span></td>
                    <td><span>60万</span></td>
                    <td><i class="layui-icon layui-icon-log"> 11:20</i></td>
                  </tr>
                
                </tbody>
              </table>
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
          console.log(res.data);
          var data = res.data;

          if (res.status == 200) {
            arr = [];
            peo = [];
            
            for (let i = 0; i < data.length; i++) {
              arr.push(data[i].tenant_type);
              peo.push({'name':data[i].tenant_type,'value':data[i].total});
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


      $.ajax({ //资金流动趋势
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "query/year/cash/flow",
        method: 'get',
        dataType: 'json',
        success: function(res) {
            data= res.data;
          if (res.status == 200) {
            date = [];
            deposit = [];
            draw_money =[];
            reward = [];
            
            for (let i = 0; i < data.length; i++) {
              date.push(data[i].date);
              deposit.push(data[i].deposit);
              draw_money.push(data[i].draw_money);
              reward.push(data[i].reward);
            }

               //资金流动趋势
      var myChart = echarts.init(document.getElementById('cityChart'));
      // 指定图表的配置项和数据
      var option = {
        title: {
          text: '元'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ["存款", "提款", "奖励"]
        },
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        toolbox: {
          feature: {
            saveAsImage: {}
          }
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: date
        },
        yAxis: {
          type: 'value',
          name: '金额'
        },
        series: [{
            name: "存款",
            color: 'green',
            type: "line",
            data: deposit
          },
          {
            name: "提款",
            type: "line",
            color: 'red',
            data: draw_money
          },
          {
            name: "奖励",
            color: 'yellow',
            type: "line",
            data: reward
          }
        ]
      };

      // 使用刚指定的配置项和数据显示图表。
      myChart.setOption(option, true);


          } else if (res.status == 403) {
            layer.msg('错误', {
              offset: '15px',
              icon: 2,
              time: 3000
            })
          }
        }
      });

      $.ajax({ //平台下注情况
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "query/year/betting/records",
        method: 'get',
        dataType: 'json',
        success: function(res) {
          option_data_selected= res;
          console.log(option_data_selected);
          var new_series=new Array();
            var legend_data = new Array();
            var date = new Array;
            for(var x1 in option_data_selected){
                var new_series_per_obj=new Object();
                new_series_per_obj.name=x1;
                new_series_per_obj.type='line';
                new_series_per_obj.data=option_data_selected[x1]['bottom'];
                new_series.push(new_series_per_obj);
                legend_data.push(new_series_per_obj.name);
                date=option_data_selected[x1]['date'];
            }
  
      var myChart = echarts.init(document.getElementById('bottom-pour'));
      // 指定图表的配置项和数据
      var option = {
        title: {
          text: '元'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: legend_data
        },
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        toolbox: {
          feature: {
            saveAsImage: {}
          }
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: date
        },
        yAxis: {
          type: 'value',
          name: '金额'
        },
        series: new_series
      };

      // 使用刚指定的配置项和数据显示图表。
      myChart.setOption(option, true);
/*           if (res.status == 200) {
            date = [];
            platform = [];
            bottom =[];
            
            for (let i = 0; i < data.length; i++) {
              platform.push(data[i]);
            }
            console.log(platform);
          } else if (res.status == 403) {
            layer.msg('错误', {
              offset: '15px',
              icon: 2,
              time: 3000
            })
          } */
        }
      });
    });
    
  </script>
</body>

</html>