

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>后台管理</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">


</head>
<body class="layui-layout-body">

  <div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
      <div class="layui-header">
        <!-- 头部区域 -->
        <ul class="layui-nav layui-layout-left">
          <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
              <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
          </li>
 <!--          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="http://www.layui.com/admin/" target="_blank" title="前台">
              <i class="layui-icon layui-icon-website"></i>
            </a>
          </li> -->
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;" layadmin-event="refresh" title="刷新">
              <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
          </li>
<!--           <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="template/search?keywords=">
          </li> -->
        </ul>
        <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

<!--           <li class="layui-nav-item" lay-unselect>
            <a lay-href="app/message/index" layadmin-event="message" lay-text="消息中心">
              <i class="layui-icon layui-icon-notice"></i>
              <span class="layui-badge-dot"></span>
            </a>
          </li> -->
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="theme">
              <i class="layui-icon layui-icon-theme"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="note">
              <i class="layui-icon layui-icon-note"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="fullscreen">
              <i class="layui-icon layui-icon-screen-full"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;">
              <cite>{{session('account')}}</cite>
            </a>
            <dl class="layui-nav-child">
          <!--    <dd><a lay-href="bguser/basic/document">基本资料</a></dd> -->
              <dd><a lay-href="user/upassword">修改密码</a></dd>
              <hr>
              <dd layadmin-event="" style="text-align: center;" ><a onclick="logout()">退出</a></dd>
            </dl>
          </li>
          <script>
              function logout(){
                top.location.href="/logout";
              }
          </script>

          <li class="layui-nav-item layui-hide-xs" lay-unselect>
           <!--  <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a> -->
          </li>
          <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
            <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
          </li>
        </ul>
      </div>

      <div class="layui-row" id="popUpdateTest" style="display:none;">
    <form class="layui-form layui-from-pane" required lay-verify="required" lay-filter="formUpdate" style="margin:20px">



      <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
          <input type="text" name="nickname" required lay-verify="required" autocomplete="off" placeholder="" value="" class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
          <select name="role" lay-filter="aihao">

          </select>
        </div>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
          <input type="text" name="state"  autocomplete="off" placeholder="" value="" class="layui-input">
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

      <!-- 侧边菜单 -->
      <div class="layui-side layui-side-menu">
        <div class="layui-side-scroll">
          <div class="layui-logo" lay-href="home/homepage">
            <span>后台管理</span>
          </div>

          <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">

           @if(in_array('统计',$per))
            <li data-name="home" class="layui-nav-item ">
              <a href="javascript:;" lay-tips="数据统计" lay-direction="2">
                <i class="layui-icon layui-icon-home"></i>
                <cite>数据统计</cite>
              </a>
              @if(in_array('统计',$per))
              <dl class="layui-nav-child">

                <dd data-name="console">
                  <a lay-href="home/homepage">数据统计</a>
                </dd>
              </dl>
              @endif
            </li>
            @endif

            @if(in_array('新建楼盘',$per) || in_array('楼盘列表',$per))
            <li data-name="user" class="layui-nav-item">
              <a href="javascript:;" lay-tips="楼盘架构管理" lay-direction="2">
                <i class="layui-icon layui-icon-template"></i>
                <cite>楼盘架构管理</cite>
              </a>
              <dl class="layui-nav-child">
                @if(in_array('新建楼盘',$per))
              <dd>
                  <a lay-href="houses/created">新建楼盘</a>
                </dd>
                @endif

                @if(in_array('楼盘列表',$per))
                <dd>
                  <a lay-href="houses/list">楼盘列表</a>
                </dd> 
                @endif 
               
              </dl>
            </li>
            @endif

          @if(in_array('组织架构管理',$per)||in_array('编辑部门',$per))
            <li data-name="template" class="layui-nav-item">
              <a href="javascript:;" lay-tips="组织架构管理" lay-direction="2">
                <i class="layui-icon layui-icon-template"></i>
                <cite>组织架构管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="branch/created">编辑部门</a></dd>
            {{--      <dd><a lay-href="branch/list">部门列表</a></dd>  --}}
              </dl>
            </li>
            @endif

            @if(in_array('帐号管理',$per)|| in_array('权限管理',$per) || in_array('角色管理',$per))
            <li data-name="template" class="layui-nav-item">
              <a href="javascript:;" lay-tips="经纪人管理" lay-direction="2">
                <i class="layui-icon layui-icon-app"></i>
                <cite>经纪人管理</cite>
              </a>
              <dl class="layui-nav-child">
                @if(in_array('帐号管理',$per))
                <dd><a lay-href="broker/account">帐号管理</a></dd>
                @endif

                @if(in_array('权限管理',$per))
                <dd><a lay-href="broker/power">权限管理</a></dd>
                @endif

                @if(in_array('角色管理',$per))
                <dd><a lay-href="broker/role">角色管理</a></dd>
                @endif
           
              </dl>
            </li>
            @endif

            @if(in_array('经纪人列表',$per) )
            <li data-name="template" class="layui-nav-item">
              <a href="javascript:;" lay-tips="工作管理" lay-direction="2">
                <i class="layui-icon layui-icon-senior"></i>
                <cite>工作管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="work/broker-list">经纪人列表</a></dd>
            

              </dl>
            </li>
            @endif

          @if(in_array('查看全部扫楼记录',$per) || in_array('按楼盘查看数据变更',$per))
            <li data-name="template" class="layui-nav-item">
              <a href="javascript:;" lay-tips="扫楼记录管理" lay-direction="2">
                <i class="layui-icon layui-icon-template"></i>
                <cite>扫楼记录管理</cite>
              </a>
              <dl class="layui-nav-child">
                @if(in_array('查看全部扫楼记录',$per))
                <dd><a lay-href="clean/all-record">查看全部扫楼记录</a></dd>
                @endif

                @if(in_array('按楼盘查看数据变更',$per))
                <dd><a lay-href="clean/record-change">按楼盘查看数据变更</a></dd>
                @endif
              </dl>
            </li>
            @endif

            @if(in_array('租户管理',$per))
            <li data-name="template" class="layui-nav-item">
              <a href="javascript:;" lay-tips="租户管理" lay-direction="2">
                <i class="layui-icon layui-icon-set"></i>
                <cite>租户管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="tenant/query">租户管理</a></dd>
          
              </dl>
            </li>
            @endif

            @if(in_array('表单配置选项',$per) || in_array('合同到期提醒手机',$per))
            <li data-name="template" class="layui-nav-item">
              <a href="javascript:;" lay-tips="参数配置" lay-direction="2">
                <i class="layui-icon layui-icon-set"></i>
                <cite>参数配置</cite>
              </a>
              <dl class="layui-nav-child">
                @if(in_array('表单选项配置',$per))
                <dd>
                  <a >表单选项配置</a>
                  <dl class="layui-nav-child">
                    <dd><a lay-href="parm/company">公司类型配置</a></dd>
                    <dd><a lay-href="parm/demand">租户需求配置</a></dd>
                    <dd><a lay-href="parm/paytype">付款方式配置</a></dd>
                  </dl>
                </dd>
                @endif

                @if(in_array('合同到期提醒手机',$per))
                <dd><a lay-href="parm/phone">合同到期提醒手机</a></dd>
                @endif
              </dl>
            </li>
            @endif


          </ul>
        </div>
      </div>

      <!-- 页面标签 -->
      <div class="layadmin-pagetabs" id="LAY_app_tabs">
        <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-down">
          <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
            <li class="layui-nav-item" lay-unselect>
              <a href="javascript:;"></a>
              <dl class="layui-nav-child layui-anim-fadein">
                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
              </dl>
            </li>
          </ul>
        </div>
     
        <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
          <ul class="layui-tab-title" id="LAY_app_tabsheader">
            <li lay-id="" lay-attr="" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
          </ul>
        </div>
     
      </div>


      <!-- 主体内容 -->
     
      <div class="layui-body" id="LAY_app_body">
        <div class="layadmin-tabsbody-item layui-show">
           <iframe src="" frameborder="0" class="layadmin-iframe"></iframe> 
        </div>
      </div>
     
      <!-- 辅助元素，一般用于移动设备下遮罩 -->
      <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
  </div>

  <script src="/layuiadmin/layui/layui.js"></script>
  <script>
  layui.config({
    base: '/layuiadmin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index']),function(){



  };
  </script>
</body>
</html>


