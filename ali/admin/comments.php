<?php

require_once '../functions.php';

xiu_get_current_user();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <style> 
    .flip-txt-loading {
      font: 26px Monospace;
      letter-spacing: 5px;
      color: #fff;
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      z-index: 999;
      display: flex;
      justify-content: center;;
      align-items: center;
      background-color: rgba(47,64,80,0.4);
    }

    .flip-txt-loading > span {
      animation: flip-txt  2s infinite;
      display: inline-block;
      transform-origin: 50% 50% -10px;
      transform-style: preserve-3d;
    }

    .flip-txt-loading > span:nth-child(1) {
      -webkit-animation-delay: 0.10s;
              animation-delay: 0.10s;
    }

    .flip-txt-loading > span:nth-child(2) {
      -webkit-animation-delay: 0.20s;
              animation-delay: 0.20s;
    }

    .flip-txt-loading > span:nth-child(3) {
      -webkit-animation-delay: 0.30s;
              animation-delay: 0.30s;
    }

    .flip-txt-loading > span:nth-child(4) {
      -webkit-animation-delay: 0.40s;
              animation-delay: 0.40s;
    }

    .flip-txt-loading > span:nth-child(5) {
      -webkit-animation-delay: 0.50s;
              animation-delay: 0.50s;
    }

    .flip-txt-loading > span:nth-child(6) {
      -webkit-animation-delay: 0.60s;
              animation-delay: 0.60s;
    }

    .flip-txt-loading > span:nth-child(7) {
      -webkit-animation-delay: 0.70s;
              animation-delay: 0.70s;
    }

    @keyframes flip-txt  {
      to {
        -webkit-transform: rotateX(1turn);
                transform: rotateX(1turn);
      }
    }  
  </style>
</head>
<body>
  <div class="main">
    <?php include 'inc/navbar.php'; ?>

    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none;">
          <button class="btn btn-info btn-sm" id="btn-approved-all">批量批准</button>
          <button class="btn btn-danger btn-sm" id="btn-delete-all">批量删除</button>
        </div>
        <!-- <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li>
        </ul> -->
        <ul class="pagination pagination-sm pull-right"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th class="text-center" width="80">作者</th>
            <th class="text-center">评论</th>
            <th class="text-center" width="150">评论在</th>
            <th class="text-center" width="150">提交于</th>
            <th class="text-center" width="40">状态</th>
            <th class="text-center" width="150">操作</th>
          </tr>
        </thead>
        <tbody>
          <!-- <tr class="danger">
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>未批准</td>
            <td class="text-center">
              <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr> -->
        </tbody>
      </table>
    </div>
  </div>
  <div class="flip-txt-loading">
  <span>L</span><span>o</span><span>a</span><span>d</span><span>i</span><span>n</span><span>g</span>
  </div>

  <?php $current_page = 'comments'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
    <tr {{if status == 'held'}}
          class="danger"
        {{else status == "approved"}} 
          class="success" 
        {{/if}} data-id= {{:id}}>
      <td class="text-center"><input type="checkbox" class="input"></td>
      <td class="text-center">{{:author}}</td>
      <td>{{:content}}</td>
      <td>《{{:post_title}}》</td>
      <td>{{:created}}</td>
      <td>
        {{if status == 'held'}}
          待审
        {{else status == "approved"}} 
          已允许
        {{else status == "rejected"}} 
          拒绝
        {{/if}}
      </td>
      <td class="text-center">
        {{if status == 'held'}}
          <a href="javascript:;" class="btn btn-info btn-xs btn-approved">批准</a>
        {{/if}}
          <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
      </td>
    </tr>
    {{/for}}
  </script>
  <script>
    $(document)
      .ajaxStart(function () {
        NProgress.start();
        $(".flip-txt-loading").css("display","flex");
      })
      .ajaxStop(function () {
        NProgress.done();
        $(".flip-txt-loading").css("display","none");
      });

    var currentPage = 1;
    loadPageData(currentPage);
    function loadPageData (page) {
      //ajax拿数据 
      $.getJSON("/admin/api/comments.php",{page: page},function (res) {
        //分页插件使用
        console.log(res);
        $('.pagination').twbsPagination({
          totalPages: res.total_pages,
          visiblePages: 5,
          first: '首页',  
          prev: '《',
          next: '》',
          last: '尾页',
          // 点击插件初始化时的起始页面，关闭他，因为第一页已经被调用一次了
          initiateStartPageClick: false,
          onPageClick: function (event, page) {
            //带一次触发会发送一次
          loadPageData(page);
          }
        });

        //将数据渲染到页面上
        var html = $("#comments_tmpl").render({comments:res.comments});
        $("tbody").html(html);
        currentPage = page;
      });
    };

    //ajax删除（采取委托委托）
    $("tbody").on("click",".btn-delete",function () {
      // console.log(this);
      var $tr = $(this).parent().parent();
      var id = $tr.data("id");
      $.get("/admin/api/comments-delete.php",{ id: id },function (res) {
        //tr的写法，形成了闭包.
        //不应该是remove，应该是重新加载当前页数据
        //tr.remove()
        // console.log(res);
        loadPageData(currentPage);
      });
    });
    //ajax批准
    $("tbody").on("click",".btn-approved",function () {
      console.log(this);
      var $tr = $(this).parent().parent();
      var id = $tr.data("id");
      $.get("/admin/api/comments-edit.php",{ id: id },function (res) {
        loadPageData(currentPage);
      });
    });
  </script>
  <!-- 批量删除功能 -->
  <script>
    $(function () {
      var allCheckeds = [];
      var $btnDeleteAll = $("#btn-delete-all");
      var $btnApprovedAll = $("#btn-approved-all");
      var $btnBatch = $(".btn-batch");
      $("tbody").on("change",".input",function () {
        // console.log(this);//this是input
        var $tr = $(this).parent().parent();
        var id = $tr.data("id");
        if ($(this).prop("checked")) {
        allCheckeds.includes(id) || allCheckeds.push(id);
        } else {
        allCheckeds.splice(allCheckeds.indexOf(id), 1);
        }
        allCheckeds.length ? $btnBatch.fadeIn() : $btnBatch.fadeOut();
        // console.log(allCheckeds);
      });

      $btnDeleteAll.on("click",function () {
        console.log(allCheckeds);
        $.get("/admin/api/comments-delete.php?id="+allCheckeds,{},function (res) {
        loadPageData(currentPage);
        });
      });

      $btnApprovedAll.on("click",function () {
        console.log(allCheckeds);
        $.get("/admin/api/comments-edit.php?id="+allCheckeds,{},function (res) {
        loadPageData(currentPage);
        });
      });
    });

  </script>
</body>
</html>
