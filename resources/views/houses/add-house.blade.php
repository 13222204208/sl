<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>创建楼盘 </title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
</head>

<body>

    <div class="demoTable" style="margin:30px;">
        <button class="layui-btn" data-type="reload" value="0" id="admin-management">创建楼盘</button>

    </div>

    <div class="demoTable" style="margin:20px;">
      搜索楼盘：
      <div class="layui-inline">
        <input class="layui-input" name="id" id="demoReload" autocomplete="off">
      </div>
      <button class="layui-btn" type="button" data-type="reload">查询</button>
    </div>

    <div class="layui-row" id="layuiadmin-form-admin" style="display:none;">
        <form class="layui-form layui-from-pane" required lay-verify="required" style="margin:20px">

          <div class="layui-form-item">
            <label class="layui-form-label" >楼盘名称</label>
            <div class="layui-input-inline">
              <input type="text" name="houses_name" placeholder="" value="" style="width:150%"  class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label" >楼盘地址</label>
            <div class="layui-input-inline">
              <input type="text" name="houses_address" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>

          
          <div class="layui-form-item">
            <label class="layui-form-label" >地图位置坐标</label>
            <div class="layui-input-inline">
              <input type="text" name="map" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>
          
          <div class="layui-form-item">
            <label class="layui-form-label" >所属区县</label>
            <div class="layui-input-inline">
              <input type="text" name="city" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>

          
          <div class="layui-form-item">
            <label class="layui-form-label" >所属商圈</label>
            <div class="layui-input-inline">
              <input type="text" name="business_area" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label" >物业类型</label>
            <div class="layui-input-inline">
              <input type="text" name="property_type" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>



          <div class="layui-form-item">
            <div class="layui-input-block">
              <button class="layui-btn" lay-submit lay-filter="create">保存</button>
            </div>
          </div>
        </form>
    </div>

  

    <div class="layui-row" id="popUpdateTest" style="display:none;">
        <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">

          <div class="layui-form-item">
            <label class="layui-form-label" >楼盘名称</label>
            <div class="layui-input-inline">
              <input type="text" name="houses_name" placeholder="" value="" style="width:150%"  class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label" >楼盘地址</label>
            <div class="layui-input-inline">
              <input type="text" name="houses_address" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>

          
          <div class="layui-form-item">
            <label class="layui-form-label" >地图位置坐标</label>
            <div class="layui-input-inline">
              <input type="text" name="map" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>
          
          <div class="layui-form-item">
            <label class="layui-form-label" >所属区县</label>
            <div class="layui-input-inline">
              <input type="text" name="city" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>

          
          <div class="layui-form-item">
            <label class="layui-form-label" >所属商圈</label>
            <div class="layui-input-inline">
              <input type="text" name="business_area" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label" >物业类型</label>
            <div class="layui-input-inline">
              <input type="text" name="property_type" placeholder="" value=""  style="width:150%" class="layui-input">
            </div>
          </div>



          <div class="layui-form-item">
            <div class="layui-input-block">
              <button class="layui-btn" lay-submit lay-filter="update">修改</button>
            </div>
          </div>
        </form>
    </div>

    <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
    <script type="text/html" id="toolbarDemo">
      <div class="layui-btn-container">
      
      </div>
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑楼盘</a>
        <a class="layui-btn layui-btn-xs" lay-event="show">查看楼盘层级</a>

    </script>

    <script src="/layuiadmin/layui/layui.js"></script>
    <script >
        layui.use(['table', 'laydate', 'jquery', 'form'], function () {
            var table = layui.table;
            var laydate = layui.laydate;
            var $ = layui.jquery;
            var form = layui.form;

            $(document).on('click', '#admin-management', function () {
                layer.open({
                    //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                    type: 1,
                    title: "新建楼盘",
                    area: ['600px', '500px'],
                    content: $("#layuiadmin-form-admin") //引用的弹出层的页面层的方式加载修改界面表单
                });
            });

   

                  //查询帐号
      $('.demoTable .layui-btn').on('click', function() {
        var keyWord = $('#demoReload');
        var house_name = keyWord.val();
        
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url:  "search" + '/' + house_name, //数据接口
          method: 'get',
          dataType: 'json',
          success: function(res) {
            if (res.status == 200) {
              table.render({
                height: 600,
                toolbar: '#toolbarDemo',
                page: true,//开启分页
                data:res.data,
                elem: '#LAY_table_user',
                cols: [
                  [

                    {
                        field: 'id',
                        title: 'ID',
                        width: 80,
                        sort: true
                    }, {
                        field: 'houses_name',
                        title: '楼盘名称',
                    }, {
                        field: 'houses_address',
                        title: '楼盘地址',
                    }, {
                        field: 'map',
                        title: '地图位置坐标',
                    }, {
                        field: 'city',
                        title: '所属区县',
                        width: 100
                    }, {
                        field: 'business_area',
                        title: '所属商圈',
                    }, {
                        field: 'property_type',
                        title: '物业类型',
                        width: 100
                    }, {
                        fixed: 'right',
                        title: "操作",
                        align: 'center',
                        toolbar: '#barDemo'
                    }
                ]
                ],

                title: '后台用户',
                totalRow: true
      
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

            //监听提交
            form.on('submit(create)', function (data) {
                console.log(data.field);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "add/house",
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
                                layer.closeAll();
                              $(".layui-laypage-btn").click() 
                  
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
                url: "look/house" //数据接口
                    ,
                toolbar: '#toolbarDemo',
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
                            field: 'houses_name',
                            title: '楼盘名称',
                        }, {
                            field: 'houses_address',
                            title: '楼盘地址',
                        }, {
                            field: 'map',
                            title: '地图位置坐标',
                        }, {
                            field: 'city',
                            title: '所属区县',
                            width: 100
                        }, {
                            field: 'business_area',
                            title: '所属商圈',
                        }, {
                            field: 'property_type',
                            title: '物业类型',
                            width: 100
                        }, {
                            fixed: 'right',
                            title: "操作",
                            align: 'center',
                            toolbar: '#barDemo'
                        }
                    ]
                ],
                parseData: function (res) { //res 即为原始返回的数据
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
                
                  if (obj.event === 'show') {
                    //console.log(data);console.log(data);return false;
                    var id= data.id;
                    window.location.href = "created?id="+id;
              
                }else if (obj.event === 'edit') {
              console.log(data);
                    layer.open({
                        //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
                        type: 1,
                        title: "修改楼盘信息",
                       area: ['600px','500px'],
                        content: $("#popUpdateTest")//引用的弹出层的页面层的方式加载修改界面表单
                    });

                    //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
                    form.val("formUpdate", data);

                    setFormValue(obj,data);
                    form.render();
                }

            });

            function setFormValue(obj, data) {
        form.on('submit(update)', function(massage) {
          massage= massage.field; 
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "update/house",
            type: 'post',
            data: {
              id: data.id,
              data:massage,
              before:data.houses_name
            },
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
                    business_area: massage.business_area,
                    city: massage.city,
                    houses_address: massage.houses_address,
                    houses_name: massage.houses_name,
                    map: massage.map,
                    property_type: massage.property_type
                
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
