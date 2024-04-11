<?php

$dsn = 'mysql:dbname=lesson;host=tatuzin_sql-mysql-1';
$user = 'root';
$password = 'root';


try {
    $pdo = new PDO($dsn, $user, $password);
    echo "接続成功\n";
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
    exit();
}

/* 
$sql = "
";

$stm = $pdo->prepare( $sql );
$stm->execute(); */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .table{
        border: black 1px solid;
    }

    .table td ,.table th{
        border: black 1px solid;
    }
</style>
<body>

    <h1>魔法のSQL</h1>
    <h2>1.CASE式のススメ</h2>
    <h3>既存のコード体系を新しい体系に変換して集計する</h3>

    <?php
    $sql = "
        SELECT CASE
            pref_name
            WHEN '徳島' THEN '四国'
            WHEN '香川' THEN '四国'
            WHEN '愛媛' THEN '四国'
            WHEN '高知' THEN '四国'
            WHEN '福岡' THEN '九州'
            WHEN '佐賀' THEN '九州'
            WHEN '長崎' THEN '九州'
            ELSE 'その他' END AS district,
            SUM(population)
        FROM
            PopTbl
        GROUP BY CASE pref_name
            WHEN '徳島' THEN '四国'
            WHEN '香川' THEN '四国'
            WHEN '愛媛' THEN '四国'
            WHEN '高知' THEN '四国'
            WHEN '福岡' THEN '九州'
            WHEN '佐賀' THEN '九州'
            WHEN '長崎' THEN '九州'
            ELSE 'その他' END
            ";

    $stm = $pdo->prepare( $sql );
    $stm ->execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <h4>●県名を地方名に再分類する</h4>
    <table class="table">
        <tr class="table">
            <th>地方名</th>
            <th>人口</th>
        </tr>
        <?php foreach( $result as $key => $val ):?>
        <tr>
            <td><?= $val[ 'district' ] ?></td>
            <td><?= $val[ 'SUM(population)' ]?></td>
        </tr>
        <?php endforeach;?>
    </table>

    <?php
    $sql = "
            SELECT CASE
                WHEN population < 100 THEN '01'
                WHEN population >= 100 AND population < 200 THEN '02'
                WHEN population >= 200 AND population < 300 THEN '03'
                WHEN population >= 300 THEN '04'
                ELSE NULL END as pop_class,
                COUNT(*) as cnt
            FROM
                PopTbl
            GROUP BY CASE
                WHEN population < 100 THEN '01'
                WHEN population >= 100 AND population < 200 THEN '02'
                WHEN population >= 200 AND population < 300 THEN '03'
                WHEN population >= 300 THEN '04'
                ELSE NULL END;
        ";

        $stm = $pdo->prepare( $sql );
        $stm ->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h4>●人口階級ごとに都道府県を分類する</h4>
    <table class="table">
        <tr>
            <th>pop_class</th>
            <th>cnt</th>
        </tr>
        <?php foreach( $result as $key => $val ):?>
        <tr>
            <td><?= $val[ 'pop_class' ] ?></td>
            <td><?= $val[ 'cnt' ]?></td>
        </tr>
        <?php endforeach;?>
    </table>
</body>
</html>