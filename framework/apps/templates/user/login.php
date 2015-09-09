<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Quip</title>
</head>
<body>
<form action="/api/user" method="post" role="form">
    <legend>Login Panel</legend>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="auth[email]" id="" placeholder="Email">
    </div>

    <div class="form-group">
        <label for=""></label>
        <input type="password" class="form-control" name="auth[password]" placeholder="">
    </div>



    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<form action="/directory/add" method="post" role="form">
    <legend>添加测试</legend>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="name" id="" placeholder="name">
    </div>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="pid" placeholder="pid">
    </div>



    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<form action="/directory/update" method="post" role="form">
    <legend>更新文件夹名称</legend>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="new_name" id="" placeholder="name">
    </div>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="dir_id" placeholder="id">
    </div>



    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>