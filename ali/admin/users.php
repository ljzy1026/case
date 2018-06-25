<?php

require_once '../functions.php';

xiu_get_current_user();
//增加用户
function add_user () {
  if (empty($_POST['email'])) {
    $GLOBALS['message'] = '请填写邮箱';
    return;
  }
  if (empty($_POST['slug'])) {
    $GLOBALS['message'] = '请填写别名';
    return;
  }
  if (empty($_POST['nickname'])) {
    $GLOBALS['message'] = '请填写昵称';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['message'] = '请填写密码';
    return;
  }
  $email = $_POST['email'];
  $slug = $_POST['slug'];
  $nickname = $_POST['nickname'];
  $password = $_POST['password'];
  $rows = xiu_execute("INSERT INTO users VALUES (NULL, '{$slug}', '{$email}','{$password}','{$nickname}','/static/uploads/avatar.jpg',NULL,'activated');");
  var_dump($rows);
  $GLOBALS['message'] = $rows <= 0 ? '添加失败！' : '添加成功！';
}

//编辑用户
function edit_user () {
  global $current_edit_user;

  // // 只有当时编辑并点保存
  // if (empty($_POST['name']) || empty($_POST['slug'])) {
  //   $GLOBALS['message'] = '请完整填写表单！';
  //   $GLOBALS['success'] = false;
  //   return;
  // }

  // 接收并保存
  $id = $current_edit_user['id'];
  $email = empty($_POST['email']) ? $current_edit_user['email'] : $_POST['email'];
  // 同步数据
  $current_edit_user['email'] = $email;
  $slug = empty($_POST['slug']) ? $current_edit_user['slug'] : $_POST['slug'];
  $current_edit_user['slug'] = $slug;
  $nickname = empty($_POST['nickname']) ? $current_edit_user['nickname'] : $_POST['nickname'];
  $current_edit_user['nickname'] = $nickname;
  $password = empty($_POST['password']) ? $current_edit_user['password'] : $_POST['password'];
  $current_edit_user['password'] = $password;

  // insert into categories values (null, 'slug', 'name');
  $rows = xiu_execute("update users set email = '{$email}', slug = '{$slug}', nickname = '{$nickname}', password = '{$password}' where id = {$id}");

  $GLOBALS['message'] = $rows <= 0 ? '更新失败！' : '更新成功！';
}

//判断类型
if (empty($_GET['id'])) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    add_user();
  }
} else {
  $current_edit_user = xiu_fetch_one('select * from users where id = ' . $_GET['id']);
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    edit_user();
  }
}


//获取数据
$users = xiu_fetch_all('SELECT * FROM users;');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>

    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
      <div class="alert alert-danger">
        <strong><?php echo $message ?></strong>
      </div>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <?php if(isset($current_edit_user)): ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $current_edit_user['id']; ?>" method="post">
            <h2>编辑《<?php echo $current_edit_user['nickname'] ?>》</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" value="<?php echo $current_edit_user['email'] ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" value="<?php echo $current_edit_user['slug'] ?>">
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" value="<?php echo $current_edit_user['nickname'] ?>">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="password" value="<?php echo $current_edit_user['password'] ?>">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
          <?php else: ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <h2>添加新用户</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
          <?php endif ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <!-- <th class="text-center" width="40"><input type="checkbox"></th> -->
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td class="text-center"><img class="avatar" src="/static/assets/img/default.png"></td>
                <td>i@zce.me</td>
                <td>zce</td>
                <td>汪磊</td>
                <td>激活</td>
                <td class="text-center">
                  <a href="post-add.php" class="btn btn-default btn-xs">编辑</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr> -->
              <?php foreach ($users as $line):?>
                <tr>
              <!--     <td class="text-center"><input type="checkbox"></td> -->
                  <td class="text-center"><img class="avatar" src="<?php echo $line['avatar'] ?>"></td>
                  <td><?php echo $line['email'] ?></td>
                  <td><?php echo $line['slug'] ?></td>
                  <td><?php echo $line['nickname'] ?></td>
                  <td><?php echo $line['status'] === 'activated' ? '激活' : ''?></td>
                  <td class="text-center">
                    <a href="?id=<?php echo $line['id'] ?>" class="btn btn-default btn-xs">编辑</a>
                    <a href="/admin/api/users-delete.php?id=<?php echo $line['id'] ?>" class="btn btn-danger btn-xs";<?php echo $line['id'] == 1 ? ' style="display:none;"' : ''?>>删除</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'users'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
