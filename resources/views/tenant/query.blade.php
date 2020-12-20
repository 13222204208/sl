<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>租户查询</title>
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

  <form class="layui-form" action="">
    <br>
    <div class="layui-form-item">
       <label class="layui-form-label">  租户名称:</label>

    <div class="layui-inline">
      <input class="layui-input" name="tenant_name" id="demoReload" autocomplete="off">
    </div>
    
  </div>
 

    <div class="layui-form-item">
      <label class="layui-form-label">是否我司:</label>
      <div class="layui-input-block">
          <select name="is_we_company" id="isNo" lay-filter="stateIsNo">
              <option value=""></option>
              <option value="1">我司租户</option>
              <option value="0">外部租户</option>
          </select>
      </div>
  </div>

    <div class="layui-form-item">
        <label class="layui-form-label">到期租户:</label>
        <div class="layui-input-block">
            <select name="day"  lay-filter="stateSelect">
                <option value=""></option>
                <option value="30">30天内到期</option>
                <option value="60">60天内到期</option>
                <option value="90">90天内到期</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item ">
      <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
              <button class="layui-btn" lay-submit="" lay-filter="create">查询</button>
          </div>
      </div>
  </div>
</form>

  <div class="layui-row" id="popUpdateTest" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">



      <div class="layui-form-item">
        <label class="layui-form-label">租户名称</label>
        <div class="layui-input-block">
          <input type="text" name="tenant_name" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">是否我司</label>
        <div class="layui-input-block">
          <input type="text" name="is_we_company" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">联系人</label>
        <div class="layui-input-block">
          <input type="text" name="tenant_user" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">公司类型</label>
        <div class="layui-input-block">
          <input type="text" name="company_type" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
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


      <div class="layui-form-item ">
        <div class="layui-input-block">
          <div class="layui-footer" style="left: 0;">
            <button class="layui-btn" lay-submit="" lay-filter="editAccount">修改</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="layui-row" id="layuiadmin-form-admin" style="display:none;">

    <table class="layui-hide" id="LAY_table_change" lay-filter="changeUser"></table>
    <script type="text/html" id="tooChange">
      <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="changeData">确定转移</button>
      </div>
    </script>
  </div>

  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
  <script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
      <button class="layui-btn layui-btn-sm" lay-event="getCheckData">转移经纪人</button>
    </div>
  </script>
  <script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  </script>


  <script src="/layuiadmin/layui/layui.js"></script>

  <script>
    layui.use(['table', 'laydate', 'jquery', 'form'], function() {
      var table = layui.table;
      var $ = layui.jquery;
      var form = layui.form;
      var laydate= layui.laydate;

      laydate.render({
        elem: '#start-time',
    
      });

      //日期时间选择器
      laydate.render({
        elem: '#stop-time',

      });

      laydate.render({
        elem: '#pay-time',
  
      });
     

      table.render({
        height: 600,
        url: "query/tenant" //数据接口
          ,
        page: true //开启分页
          ,
        elem: '#LAY_table_user',
        toolbar: '#toolbarDemo',
        cols: [
          [
            {type:'checkbox'},
            {
              field: 'id',
              title: 'ID',
              width: 80,
              sort: true
            }, {
              field: 'tenant_name',
              title: '租户名称',
              width: 120
            },{
              field: 'houses_name',
              title: '楼盘名称',
              width: 180
            }, {
              field: 'houses_info',
              title: '租户详情',
              width: 220
            }, {
              field: 'is_we_company',
              title: '是否我司',
              width: 120
            }, {
              field: 'tenant_user',
              title: '联系人',
              width: 120
            }, {
              field: 'company_type',
              title: '公司类型',
              width: 120
            }, {
              field: 'start_time',
              title: '合同起始时间',
              width: 120
            }, {
              field: 'stop_time',
              title: '合同到期时间',
              width: 160
            }, {
              field: 'pay_type',
              title: '付款方式',
              width: 120
            }, {
              field: 'pay_time',
              title: '下次应付款时间',
              width: 160
            }, {
              field: 'broker_name',
              title: '经纪人姓名',
              width: 120
            },  {
              field: 'broker_phone',
              title: '经纪人手机号',
              width: 120
            }, {
              field: 'tenant_need',
              title: '租户需求',
              width: 120
            }, {
              field: 'remark',
              title: '备注',
              width: 120
            }, {
              field: 'created_at',
              title: '创建时间',
              width: 160
            },{
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
        title: '后台用户',
        totalRow: true

      });

 //头工具栏事件
 table.on('toolbar(user)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id);

    if(obj.event == 'getCheckData'){
      var data = checkStatus.data;
       //cdata = JSON.stringify(data);
       if(data.length < 1){
        layer.msg("请选择要更改的信息", {icon: 5});
        return false;
       }
       strid = "";
       for(i=0; i<data.length; i++){
          strid += data[i].id+','; 
       }
       console.log(strid);
       layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "经纪人信息",
                       area: ['800px','600px'],
                        content: $("#layuiadmin-form-admin")//引用的弹出层的页面层的方式加载修改界面表单
                    });
                    table.render({
        height: 600,
        url: "query/account" //数据接口
          ,
        limit:15,
        page: true //开启分页
          ,
        elem: '#LAY_table_change',
        toolbar: '#tooChange',
        cols: [
          [
            {type:'checkbox'},
            {
              type:'numbers',
              title:'序号',
              algin:'center',
              width:80,
            },
            {
              field: 'account',
              title: '帐号',
              width:120,
          
            }, {
              field: 'name',
              title: '名称',
              width:120,
            }, {
              field: 'branch',
              title: '部门',
              width:120,
            },{
                            field: 'status',
                            title: '状态',
                            templet: function(d) {
                                if (d.status == 1) {
                                  return '正常';
                                }else{
                                    return '已禁用';
                                }
                              }
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

      table.on('toolbar(changeUser)', function(obj){
        var checkStatus = table.checkStatus(obj.config.id);

        if(obj.event == 'changeData'){
          var data = checkStatus.data;
          //cdata = JSON.stringify(data);
          if(data.length != 1){
            layer.msg("一次只能分配给一个经纪人", {icon: 5});
            return false;
          }
          brokerId = data[0].id;
         $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/broker",
            type: 'post',
            data: {
              id: brokerId,
              tid:strid
            },
            success: function(msg) {
              
              if (msg.status == 200) {
                layer.closeAll('loading');
                layer.load(2);
                layer.msg("修改成功", {
                  icon: 6,
                  time:2000
                }, function () {
                  layer.closeAll(); //关闭所有的弹出层

                            });
 
             
                  //window.location.href = "/edit/horse-info";

              } else {
                layer.msg("修改失败", {
                  icon: 5
                });
              }
            }
          });

        }
      });

    }
  });

      form.on('submit(create)', function (data) {
       console.log(data.field); 
        if(data.field.is_we_company == ''){
          data.field.is_we_company =3;
        }
       console.log(data.field.is_we_company);
        table.render({
          height: 600,
          url: "stop/date"  //数据接口
            ,
          page: true,//开启分页
          limit:15,
          where:{
            day: data.field.day,
            is_we_company:data.field.is_we_company,
            tenant_name:data.field.tenant_name
          },
          elem: '#LAY_table_user',
          toolbar: '#toolbarDemo',
          cols: [
            [
              {type:'checkbox'},
              {
                field: 'id',
                title: 'ID',
                width: 80,
                sort: true
              }, {
                field: 'tenant_name',
                title: '租户名称',
                width: 120
              }, {
                field: 'houses_name',
                title: '楼盘名称',
                width: 120
              }, {
                field: 'houses_info',
                title: '租户详情',
                width: 120
              }, {
                field: 'is_we_company',
                title: '我司租户',
                width: 120
              }, {
                field: 'tenant_user',
                title: '联系人',
                width: 120
              }, {
                field: 'company_type',
                title: '公司类型',
                width: 160
              }, {
                field: 'start_time',
                title: '合同起始时间',
                width: 120
              }, {
                field: 'stop_time',
                title: '合同到期时间',
                width: 120
              }, {
                field: 'pay_type',
                title: '付款方式',
                width: 120
              }, {
                field: 'pay_time',
                title: '下次应付款时间',
                width: 160
              },  {
                field: 'tenant_need',
                title: '租户需求',
                width: 120
              }, {
                field: 'remark',
                title: '备注',
                width: 120
              }, {
              field: 'broker_name',
              title: '经纪人姓名',
            },  {
              field: 'broker_phone',
              title: '经纪人手机号',
            }, {
                field: 'created_at',
                title: '创建时间',
                width: 160
              },{
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
          title: '后台用户',
          totalRow: true

        });
          

        return false;
    });

      table.on('tool(user)', function (obj) {
             data = obj.data;
         
           if (obj.event === 'del') {
      
                layer.confirm('真的删除行么', function (index) {
                    $.ajax({
                        url: "del/tenant",
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
            } else if (obj.event === 'edit') {
              console.log(data);
                    layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "修改租户信息",
                       area: ['600px','600px'],
                        content: $("#popUpdateTest")//引用的弹出层的页面层的方式加载修改界面表单
                    });

                    //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
                    form.val("formUpdate", data);

                    setFormValue(obj,data);
                    form.render();
                }
            
        });

        function setFormValue(obj, data) {
        form.on('submit(editAccount)', function(massage) {
          massage= massage.field; console.log(massage);
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/tenant",
            type: 'post',
            data: {
              id: data.id,
              data:massage
            },
            success: function(msg) {
              
              if (msg.status == 200) {
                layer.closeAll('loading');
                layer.load(2);
                layer.msg("修改成功", {
                  icon: 6
                });
                setTimeout(function() {

                  obj.update({
                    tenant_name: massage.tenant_name,
                    tenant_type: massage.tenant_type,
                    tenant_user: massage.tenant_user,
                    company_type: massage.company_type,
                    start_time: massage.start_time,
                    stop_time: massage.stop_time,
                    pay_type: massage.pay_type,
                    pay_time: massage.pay_time,
                    tenant_need: massage.tenant_need,
                    remark: massage.remark,
                    uid: massage.uid,
                
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