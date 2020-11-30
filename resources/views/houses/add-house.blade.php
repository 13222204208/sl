

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>添加楼盘</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
</head>
<body>
<style>
    .layui-form-label{
        width:100px;
    }
</style>
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">添加楼盘</div>
          <div class="layui-card-body" >
            
            <div class="layui-form" lay-filter="">
    
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
                <label class="layui-form-label" >房间号</label>
                <div class="layui-input-inline">
                  <input type="text" name="houses_num" placeholder="" value=""  style="width:150%" class="layui-input">
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




    </div>
  </div>

  <script src="/layuiadmin/layui/layui.js"></script>
  <script src="/layuiadmin/layui/jquery3.2.js"></script>
  <script> 
    layui.use([ 'form'], function() {
			var $ = layui.$,
				admin = layui.admin,
				element = layui.element,
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
              //location.href = 'paytype';
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


		


    });
  </script>
</body>
</html>