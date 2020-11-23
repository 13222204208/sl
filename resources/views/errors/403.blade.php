<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>404 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
</head>
<body class="lay-blog">
		<div class="header">
			<div class="header-wrap">
				<h1 class="logo pull-left">
					
				</h1>
				<form class="layui-form blog-seach pull-left" action="">
					<div class="layui-form-item blog-sewrap">
					 
					</div>
				</form>
				<div class="blog-nav pull-right">
				
				
				</div>
			
			</div>
		
		</div>
		<div class="container-wrap">
			<div class="container">
					<div class="contar-wrap">
						<div style="padding: 150px 0 50px; text-align: center; font-size: 30px; color: #ccc; font-weight: 300;">
							 {{$status}}
						</div>
					</div>
			</div>
		</div>
		<div class="footer">
			
	
		</div>
		<script src="/layuiadmin/layui/layui.js"></script>
	<script>
		layui.config({
		  base: '../res/static/js/' 
		}).use('blog');	
	</script>
</body>
</html>