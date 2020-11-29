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

  <div class="demoTable" style="margin:20px;">
       <label class="layui-form-label">  租户名称：</label>

    <div class="layui-inline">
      <input class="layui-input" name="id" id="demoReload" autocomplete="off">
    </div>
    <button class="layui-btn" type="button" data-type="reload">查询</button>

    
  </div>
 
  <form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">到期时间:</label>
        <div class="layui-input-block">
            <select name="city" lay-verify="required" lay-filter="stateSelect">
                <option value=""></option>
                <option value="30">30天内</option>
                <option value="60">60天内</option>
                <option value="90">90天内</option>
            </select>
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
        <label class="layui-form-label">租户类型</label>
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



  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
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
        type: 'datetime'
      });

      //日期时间选择器
      laydate.render({
        elem: '#stop-time',
        type: 'datetime'
      });

      laydate.render({
        elem: '#pay-time',
        type: 'datetime'
      });
     

      table.render({
        height: 600,
        url: "query/tenant" //数据接口
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
            }, {
              field: 'is_we_company',
              title: '租户类型',
            }, {
              field: 'tenant_user',
              title: '联系人',
            }, {
              field: 'company_type',
              title: '公司类型',
            }, {
              field: 'start_time',
              title: '合同起始时间',
            }, {
              field: 'stop_time',
              title: '合同到期时间',
            }, {
              field: 'pay_type',
              title: '付款方式',
            }, {
              field: 'pay_time',
              title: '下次应付款时间',
            },  {
              field: 'tenant_need',
              title: '租户需求',
            }, {
              field: 'remark',
              title: '备注',
            }, {
              field: 'broker_name',
              title: '经纪人姓名',
            },  {
              field: 'broker_phone',
              title: '经纪人手机号',
            },{
              field: 'created_at',
              title: '创建时间',
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



      //查询帐号
      $('.demoTable .layui-btn').on('click', function() {

        var keyWord = $('#demoReload');
        var tenant_name = keyWord.val();
//console.log(tenant_name);
        table.render({
          height: 600,
          url: "gain/info" + '/' + tenant_name //数据接口
            ,
          page: true,//开启分页
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
                width: 120
              }, {
                field: 'is_we_company',
                title: '租户类型',
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
            },{
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

      form.on('select(stateSelect)', function(data) { //更改帐号状态
        let day = data.elem.value; //当前字段变化的值
        console.log(day);
        table.render({
          height: 600,
          url: "stop/date" + '/' + day //数据接口
            ,
          page: true,//开启分页
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
                width: 120
              }, {
                field: 'is_we_company',
                title: '租户类型',
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
          

        return false;
    });

      table.on('tool(user)', function (obj) {
            var data = obj.data;
         
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