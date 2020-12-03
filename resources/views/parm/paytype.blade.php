

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>付款方式设置</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">付款方式设置</div>
          <div class="layui-card-body" >
            
            <div class="layui-form" lay-filter="">
    
              <div class="layui-form-item">
                <label class="layui-form-label" style="width:130px">付款方式名称</label>
                <div class="layui-input-inline">
                  <input type="text" name="type_name" placeholder="例如，月付，季付，半年付，" value=""   class="layui-input">
                </div>
              </div>

              <div class="layui-form-item">
                <label class="layui-form-label" style="width:130px">月</label>
                <div class="layui-input-inline">
                  <input type="number" name="month" placeholder="例如，1，3，6，" value=""   class="layui-input">
                </div>
              </div>

 

              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="setmyinfo">保存</button>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>


      <div class="layui-row" id="popUpdateTest" style="display:none;">
        <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate"
            style="margin:20px">

            <div class="layui-form-item">
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-block">
                    <input type="text" name="type_name" required lay-verify="type_name" autocomplete="off"
                        placeholder="请输入分类名称" value="" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
              <label class="layui-form-label">月</label>
              <div class="layui-input-block">
                  <input type="number" name="month" required lay-verify="type_name" autocomplete="off"
                      placeholder="输入月的个数" value="" class="layui-input">
              </div>
          </div>




            <div class="layui-form-item ">
                <div class="layui-input-block">
                    <div class="layui-footer" style="left: 0;">
                        <button class="layui-btn" lay-submit="" lay-filter="editAccount">保存</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    </div>
  </div>

  <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>
  <script type="text/html" id="barDemo">
      <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>

  </script>

  <script src="/layuiadmin/layui/layui.js"></script>
  <script src="/layuiadmin/layui/jquery3.2.js"></script>
  <script> 
    layui.use([ 'form','table'], function() {
			var $ = layui.$,
				admin = layui.admin,
				table = layui.table,
				layer = layui.layer,
				form = layui.form;



			form.on('submit(setmyinfo)', function(data) {//添加付款方式类型
				var data = data.field;
				
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url: "add/paytype",
					method: 'POST',
					data: data,
					success: function(res) {
						console.log(res);
						if (res.status == 200) {
							layer.msg('添加成功', {
								offset: '15px',
								icon: 1,
								time: 3000
              });
              location.href = 'paytype';
						} else {
							console.log(res);
							layer.msg('添加失败', {
								offset: '15px',
								icon: 2,
								time: 3000
							})
						}
					}
				});
				return false;
      });
      
      table.render({
        url: "gain/paytype" //数据接口
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
                    field: 'type_name',
                    title: '名称',
                }, {
                    field: 'month',
                    title: '月',
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

    table.on('tool(user)', function (obj) {
      var data = obj.data;
      console.log(data);
      if (obj.event === 'del') {

          layer.confirm('真的删除此分类么', function (index) {
              $.ajax({
                  url: "del/paytype",
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                          'content')
                  },
                  type: "POST",
                  data: {
                      id: data.id,
                      tree:data.tree
                  },
                  success: function (msg) {

                      if (msg.status == 200) {
                          //删除这一行
                          obj.del();
                          //关闭弹框
                          layer.close(index);
                          layer.msg("删除成功", {
                              icon: 6
                          });
                      } else {
                          layer.msg("删除失败", {
                              icon: 5
                          });
                      }
                  }
              });
              return false;
          });
      } else if (obj.event === 'edit') {
        layer.open({
            //layer提供了5种层类型。可传入的值有：0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
            type: 1,
            title: "修改名称",
            area: ['600px', '300px'],
            content: $("#popUpdateTest") //引用的弹出层的页面层的方式加载修改界面表单
        });
        //动态向表传递赋值可以参看文章进行修改界面的更新前数据的显示，当然也是异步请求的要数据的修改数据的获取
        form.val("formUpdate", data);
        setFormValue(obj, data);
    }

    function setFormValue(obj, data) {
      form.on('submit(editAccount)', function (massage) {
          massage = massage.field;
          console.log(data);

          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: "edit/paytype",
              type: 'post',
              data: {
                  id: data.id,
                  type_name: massage.type_name,
                  month: massage.month
              },
              success: function (msg) { 
                  if (msg.status == 200) {
                      layer.closeAll('loading');
                      layer.load(2);
                      layer.msg("修改成功", {
                          icon: 6
                      });
                      setTimeout(function () {

                          obj.update({
                              type_name: massage.type_name,
                              month :massage.month
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


		


    });
  </script>
</body>
</html>