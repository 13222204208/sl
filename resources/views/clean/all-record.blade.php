<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>查看全部扫楼记录</title>
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
  <div class="demoTable" style="margin:10px;">
    搜索：
    <div class="layui-inline">
      <input class="layui-input" name="id" id="demoReload" autocomplete="off">
    </div>
    <button class="layui-btn" type="button" data-type="reload">查询</button>
  </div>

  <div class="fr">
    <form class="layui-form layui-from-pane" required lay-verify="required" action="">
      <div class="layui-form-item"> 
        <div class="layui-inline">
          <label class="layui-form-label">开始时间：</label>
          <div class="layui-input-inline">
  
            <input type="text" name="startTime" class="layui-input" lay-verify="required"  id="startTime" placeholder="yyyy-MM-dd HH:mm:ss">
          </div>
  
        </div>
  
        <div class="layui-inline">
          <label class="layui-form-label">结束时间：</label>
          <div class="layui-input-inline">
            <input type="text" class="layui-input" name="stopTime" lay-verify="required" id="stopTime" placeholder="yyyy-MM-dd HH:mm:ss">
          </div>
  
        </div>
        <div class="layui-inline">
          <div class="layui-input-inline">
            <button type="button" class="layui-btn layui-btn-blue" lay-submit=""  lay-filter="search">搜索</button>
          </div>
        </div>
      </div>
  
    </form>
  </div>



  <div class="layui-row" id="popUpdateTest" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">
   
      
      <div class="layui-form-item">
        <label class="layui-form-label">楼盘信息</label>
        <div class="layui-input-block">
          <input type="text" name="houses_info" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>    


    <div class="layui-form-item">
        <label class="layui-form-label">租户名称</label>
        <div class="layui-input-block">
          <input type="text" name="tenant_name" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">是否我司租户</label>
        <div class="layui-input-block">
          <input type="text" name="is_we_company" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">公司类型</label>
        <div class="layui-input-block">
          <input type="text" name="company_type" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>


      <div class="layui-form-item">
        <label class="layui-form-label">联系人</label>
        <div class="layui-input-block">
          <input type="text" name="tenant_user" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <div class="layui-inline">
          <label class="layui-form-label">合同开始时间</label>
          <div class="layui-input-inline">
            <input type="text" class="layui-input" name="start_time" id="start-time" style="width: 300px;" placeholder="yyyy-MM-dd HH:mm:ss">
          </div>
        </div>
      </div>

      <div class="layui-form-item">
        <div class="layui-inline">
          <label class="layui-form-label">合同结束时间</label>
          <div class="layui-input-inline">
            <input type="text" class="layui-input" name="stop_time" id="stop-time" style="width: 300px;" placeholder="yyyy-MM-dd HH:mm:ss">
          </div>
        </div>
      </div>


      <div class="layui-form-item">
        <label class="layui-form-label">付款方式</label>
        <div class="layui-input-block">
          <input type="text" name="pay_type" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <div class="layui-inline">
          <label class="layui-form-label">付款时间</label>
          <div class="layui-input-inline">
            <input type="text" class="layui-input" name="pay_time" id="pay-time" style="width: 300px;" placeholder="yyyy-MM-dd HH:mm:ss">
          </div>
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">租户需求</label>
        <div class="layui-input-block">
          <input type="text" name="tenant_need" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
          <input type="text" name="remark" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">经纪人姓名</label>
        <div class="layui-input-block">
          <input type="text" name="broker_name" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">经纪人手机号</label>
        <div class="layui-input-block">
          <input type="text" name="broker_phone" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>
{{-- 
      <div class="layui-form-item">
        <label class="layui-form-label">提交位置</label>
        <div class="layui-input-block">
          <input type="text" name="position" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div> --}}

      <div class="layui-form-item">
        <label class="layui-form-label">附件</label>
        <div class="layui-input-block" id="myImg">
          
        </div>
      </div>


<!--       <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="editAccount">修改</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
          </div>
        </div>
      </div> -->
    </form>
  </div>


  <div id="exportData" style="display: none"> <a href="export" style="color:blue">点击下载数据</a></div>
  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
  <script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
    
    </div>
  </script>
   <script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">查看详情</a>
  
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

            //楼盘名称或租户名称 
            $('.demoTable .layui-btn').on('click', function() {

              var keyWord = $('#demoReload');
              var name = keyWord.val();

              $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "get/permission",
                method: 'get',
                dataType: 'json',
                success: function(res) {
                  console.log(res); 
                  if (res.status == 200) {
                      toolbar = '';
                      if(res.state == true){
                        $('#exportData').show();
                        toolbar = '#toolbarDemo';
                      }   
      
                      table.render({
                        toolbar: toolbar,
                        height: 600,
                        page: true,//开启分页
                        limit:15,
                        elem: '#LAY_table_user',
                        url: "house/tenant",
                        where:{
                          name: name
                        },
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
                              width: 130,
                            },{
                              field: 'houses_info',
                              title: '楼盘信息',
                              width: 180,
                            },{
                              field: 'houses_num',
                              title: '房间号',
                              width: 180,
                            }, {
                              field: 'tenant_name',
                              title: '租户名称',
                              width: 180,
                            },  {
                              field: 'created_at',
                              title: '录入时间',
                              width: 180,
                            }, {
                              field: 'is_we_company',
                              title: '是否我司租户',
                              width: 120,
                            }, {
                              field: 'company_type',
                              title: '公司类型',
                              templet: function(d) {
                                if(d.companytype != null){
                                  return d.companytype.type_name
                                }else{
                                  return '';
                                }
                               
                              },
                              width: 120,
                            }, {
                              field: 'tenant_user',
                              title: '联系人',
                              width: 220,
                              
                            }, {
                              field: 'start_time',
                              title: '合同起始时间',
                              width: 120,
                            },{
                              field: 'stop_time',
                              title: '合同到期时间',
                              width: 120,
                            }, {
                              field: 'pay_type',
                              title: '付款方式',
                              templet: function(d) {
                     
                                if(d.paytype != null){
                                  return d.paytype.type_name
                               }else{
                                 return '';
                               }
                               },
                              width: 100,
                            }, {
                              field: 'pay_time',
                              title: '下次应付款时间',
                              width: 120,
                            }, {
                              field: 'tenant_need',
                              title: '租户需求',
                              templet: function(d) {
                                if(d.tenantneed != null){
                                 return d.tenantneed.type_name
                              }else{
                                return '';
                              }
                               },
                            },{
                              field: 'remark',
                              title: '备注',
                            }, {
                              field: 'broker_name',
                              title: '经纪人姓名',
                              width: 100,
                            }, {
                              field: 'broker_phone',
                              title: '经纪人手机号',
                              width: 100,
                            } ,    {
                              fixed: 'right',
                              title: "操作",
                              width: 150,
                              align: 'center',
                              toolbar: '#barDemo'
                            } 
                          ]
                        ],
                        parseData: function(res) { //res 即为原始返回的数据
                         // console.log(res);return false;
                          return {
                            "code": '0', //解析接口状态
                            "msg": res.message, //解析提示文本
                            "count": res.total, //解析数据长度
                            "data": res.data //解析数据列表
                          }
                        },
                        title: '后台用户',
                        totalRow: true,
                        initSort:{
                          field: 'id',
                          type:'desc'
                        }
              
                      });
           
        
                    }else if (res.status == 403) {
                    layer.msg('错误', {
                      offset: '15px',
                      icon: 2,
                      time: 3000
                    })
                  }
                }
              });



            });



      form.on('submit(search)', function(data) {
        var data = data.field;
        console.log(data);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "get/permission",
          method: 'get',
          dataType: 'json',
          success: function(res) {
            console.log(res); 
            if (res.status == 200) {
                toolbar = '';
                if(res.state == true){
                  $('#exportData').show();
                  toolbar = '#toolbarDemo';
                }   

                   //return false;
        table.render({
          toolbar: toolbar,
        elem: '#LAY_table_user',
        url: 'search/clean',
        where:{
          startTime:data.startTime,
          stopTime:data.stopTime
        },
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
              width: 130,
            },{
              field: 'houses_info',
              title: '楼盘信息',
              width: 180,
            },{
              field: 'houses_num',
              title: '房间号',
              width: 180,
            }, {
              field: 'tenant_name',
              title: '租户名称',
              width: 180,
            },  {
              field: 'created_at',
              title: '录入时间',
              width: 180,
            }, {
              field: 'is_we_company',
              title: '是否我司租户',
              width: 120,
            }, {
              field: 'company_type',
              title: '公司类型',
              templet: function(d) {
                if(d.companytype != null){
                  return d.companytype.type_name
                }else{
                  return '';
                }
               
              },
              width: 120,
            }, {
              field: 'tenant_user',
              title: '联系人',
              width: 220,
              
            }, {
              field: 'start_time',
              title: '合同起始时间',
              width: 120,
            },{
              field: 'stop_time',
              title: '合同到期时间',
              width: 120,
            }, {
              field: 'pay_type',
              title: '付款方式',
              templet: function(d) {
                     
                if(d.paytype != null){
                  return d.paytype.type_name
               }else{
                 return '';
               }
               },
              width: 100,
            }, {
              field: 'pay_time',
              title: '下次应付款时间',
              width: 120,
            }, {
              field: 'tenant_need',
              title: '租户需求',
              templet: function(d) {
                if(d.tenantneed != null){
                 return d.tenantneed.type_name
              }else{
                return '';
              }
               },
            },{
              field: 'remark',
              title: '备注',
            }, {
              field: 'broker_name',
              title: '经纪人姓名',
              width: 100,
            }, {
              field: 'broker_phone',
              title: '经纪人手机号',
              width: 100,
            } ,    {
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
        page: true,
        limit:15,
      });
     
  
              }else if (res.status == 403) {
              layer.msg('错误', {
                offset: '15px',
                icon: 2,
                time: 3000
              })
            }
          }
        });
        return false;
      });
     
      
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "get/permission",
        method: 'get',
        dataType: 'json',
        success: function(res) {
         // console.log(res.data); return false;
          if (res.status == 200) {
              toolbar = '';
              if(res.state == true){
                $('#exportData').show();
                toolbar = '#toolbarDemo';
              }
                   //return false;
                   table.render({
                    toolbar: toolbar,
                  elem: '#LAY_table_user',
                  url: 'gain/clean',
                  toolbar: toolbar,
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
                        width: 130,
                      },{
                        field: 'houses_info',
                        title: '楼盘信息',
                        width: 180,
                      },{
                        field: 'houses_num',
                        title: '房间号',
                        width: 180,
                      }, {
                        field: 'tenant_name',
                        title: '租户名称',
                        width: 180,
                      },  {
                        field: 'created_at',
                        title: '录入时间',
                        width: 180,
                      }, {
                        field: 'is_we_company',
                        title: '是否我司租户',
                        width: 120,
                      }, {
                        field: 'company_type',
                        title: '公司类型',
                        templet: function(d) {
                          if(d.companytype != null){
                            return d.companytype.type_name
                          }else{
                            return '';
                          }
                         
                        },
                        width: 120,
                      }, {
                        field: 'tenant_user',
                        title: '联系人',
                        width: 220,
                        
                      }, {
                        field: 'start_time',
                        title: '合同起始时间',
                        width: 120,
                      },{
                        field: 'stop_time',
                        title: '合同到期时间',
                        width: 120,
                      }, {
                        field: 'pay_type',
                        title: '付款方式',
                        templet: function(d) {
                               
                          if(d.paytype != null){
                            return d.paytype.type_name
                         }else{
                           return '';
                         }
                         },
                        width: 100,
                      }, {
                        field: 'pay_time',
                        title: '下次应付款时间',
                        width: 120,
                      }, {
                        field: 'tenant_need',
                        title: '租户需求',
                        templet: function(d) {
                          if(d.tenantneed != null){
                           return d.tenantneed.type_name
                        }else{
                          return '';
                        }
                         },
                      },{
                        field: 'remark',
                        title: '备注',
                      }, {
                        field: 'broker_name',
                        title: '经纪人姓名',
                        width: 100,
                      }, {
                        field: 'broker_phone',
                        title: '经纪人手机号',
                        width: 100,
                      } ,    {
                        fixed: 'right',
                        title: "操作",
                        width: 150,
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
                  page: true,
                  limit:15,
                });
              

            }else if (res.status == 403) {
            layer.msg('错误', {
              offset: '15px',
              icon: 2,
              time: 3000
            })
          }
        }
      });
    

      table.on('tool(user)', function (obj) {
            var data = obj.data;
         
     if (obj.event === 'edit') {
             
                    layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "详情",
                        area: ['600px','480px'],
                        content: $("#popUpdateTest")//引用的弹出层的页面层的方式加载修改界面表单
                    });
                    //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
                    form.val("formUpdate", data);
                   // setFormValue(obj,data);
                }
                url = window.location.protocol+"//"+window.location.host+"/";
                imgs= data.enclosure;
              arrayList=  imgs.split(',');
              photo ="";
                console.log(arrayList);
                arrayList.forEach(function(element) {
                  photo += '<img width="200px" src="'+url+element+'">';
                  
              });
              $("#myImg").html(photo);
        });
      

    });
  </script>

</body>

</html>