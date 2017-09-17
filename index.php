<?php

$pdo = new PDO("mysql:host=localhost;dbname=global;charset=utf8", "root");
$array = [];
$string = '';
$sql = "SELECT * FROM books";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['isbn']) && $_POST['isbn'] != '') {
        $a=$_POST['isbn'];
        $array[] = "%$a%";
        $string = "isbn like ?";
    }

    if (isset($_POST['name']) && $_POST['name'] != '') {
        $a=$_POST['name'];
        $array[] = "%$a%";
        $string = ($string != '') ? ($string . " AND name like ?") : 'name like ?';
    }

    if (isset($_POST['author']) && $_POST['author'] != '') {
        $a=$_POST['author'];
        $array[] = "%$a%";
        $string = ($string != '') ? ($string . ' AND author like ?') : 'author like ?';
    }

    $string = ($string != '') ? (' WHERE ' . $string) : '';
}

$sql = $sql . $string . ';';
$statement = $pdo->prepare($sql);
$statement->execute($array);
$results = [];

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $results[] = $row;
}

?>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <title>MySQL</title>
    <style>
        td {padding: 5px 20px 5px 20px;border: 1px solid black}
        thead td {text-align: center;background-color: #dbdbdb;font-weight: 700;}
        table {border-collapse: collapse;border-spacing: 0}
    </style>
</head>
<body>
    <h1>Библиотека успешного человека</h1>
    <form method='POST'>
        <input type="text" name="isbn" placeholder='ISBN' value="<?=(isset($_POST['isbn'])?$_POST['isbn']:'')?>">
        <input type="text" name="name" placeholder='Название книги' value="<?=(isset($_POST['name'])?$_POST['name']:'')?>">
        <input type="text" name="author" placeholder='Автор книги' value="<?=(isset($_POST['author'])?$_POST['author']:'')?>">
        <input type='submit' value='Поиск'>
    </form>
    <table>
        <thead>
            <tr>
                <td>Название</td>
                <td>Автор</td>
                <td>Год выпуска</td>
                <td>Жанр</td>
                <td>ISBN</td>
            </tr>
        </thead>
        <tbody>
            <?php if (null!==$results) { foreach ($results as $value) :?>
            <tr>
                <td><?=$value['name']?></td>
                <td><?=$value['author']?></td>
                <td><?=$value['year']?></td>
                <td><?=$value['genre']?></td>
                <td><?=$value['isbn']?></td>
            </tr>
            <?php endforeach; } ?>
        </tbody>
    </table>

</body>
</html>