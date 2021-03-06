<!DOCTYPE html>
<html lang="en">
<?php
include "goToForbidden.php";
include "connect.php";
date_default_timezone_set("PRC");
function ppp()
{
    $conn = connectMysql();
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['username'])) {
        $query1 = "SELECT * FROM `groups` WHERE `name` = '" . $_SESSION["username"] . "'";
        //  取得查询结果
        $stmt1 = $conn->prepare($query1);
        $stmt1->execute();
        $userInfo = $stmt1->fetch();

        $query = "SELECT * FROM `problems` WHERE `number` = '" . $userInfo['answers'] . "'";
        //  取得查询结果
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $problem = $stmt->fetch();
        if ($userInfo['answers'] != 25) {
            echo "<h3>" . $problem['number'] . '. ' . $problem['title'] . '</h3>';
            if (!$problem['image']) {
            } else {
                echo "<img style='max-width:100%' src='img/" . $problem['image'] . "'>";
            }
            echo "<div class='container text-left'><p>" . $problem['problem'] . '</p></div>';
        }

        if ($userInfo['answers'] == 25) {
            echo '<h1>*恭喜通关*</h1>';
            echo '<img src="img/通关.png" style="max-width:100%"></img>';
            echo '<p>队伍名：' . $userInfo['name'] . '</p>';
            echo '<p>等等，好像还可以继续？-> <a href="egg.php">Continue</a></p>'
        }

        $datetime = date_create()->format('Y-m-d H:i:s');


        if ($userInfo['lockT'] == '1') {
            echo "<p>锁定时间" . $userInfo['lockTime'] . '</p>';
            echo "<p>当前时间" . $datetime . '</p>';
            echo "<p>解锁总共需要2h,请在时间到之后联系管理员解锁</p>";
            echo "<p>新增：如果是不小心误触可以直接和管理员联系解锁</p>";
        }
    } else {
        echo '<p>请先登录</p>';
        echo '<a href="index.php">返回主页</a>';
    }
}

function can()
{
    $conn = connectMysql();
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION['username'])) {
        $query1 = "SELECT * FROM `groups` WHERE `name` = '" . $_SESSION["username"] . "'";
        //  取得查询结果
        $stmt1 = $conn->prepare($query1);
        $stmt1->execute();
        $userInfo = $stmt1->fetch();
        if ($userInfo['answers'] == 25 || $userInfo['lockT'] == '1') {
        } else {
            echo '<input type="text" class="form-control text" name="answer" placeholder="请输入答案">' . '<br>'. '<br>';
            echo '<button type="submit" class="btn btn-primary">提交</button>';
        }
    } else {
        echo '请先登录';
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>答题</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/common.css">
</head>

<body>
    <?php
    include "title.php";
    ?>
    <div class="container text-center">
        <div class="jumbotron" style="background-color: rgba( 103, 193, 245, 0.2 );">
            <form role="form" method="post" action="answer.php">
                <br>

                <?php
                ppp();
                ?>

                <br>
                <!-- <button type="submit" class="btn btn-primary">提交</button> -->
                <?php
                can()
                ?>
            </form>
        </div>
    </div>
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js">
    </script>
    <script src="https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src='js/super.js'> </script>
</body>

</html>