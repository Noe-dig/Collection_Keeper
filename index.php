<?php

include_once "connection.php";

if (!isset($_POST['itemAdd'])) {
} else {
    $media = $_GET['type'];

    $sql = "INSERT INTO $media (`artist`,`album`)
            VALUES 
                artist = ?, 
                album = ?"
    ;

    $add = $pdo->prepare($sql);
    $add->execute([
        $_POST['artist'],
        $_POST['album'],
    ]);
}

function query($pdo, $type)
{
    $params = [];
    $where = "";

    if ($type !== NULL) {
        $where = "WHERE shopitems.itemThemeID = ?";
        $params = [$type];
    }

    $selectQuery = "SELECT  artist, 
                            album, 
                            
                    FROM `$type`

                    $where 
                    ORDER BY artist ASC";

    $stmt = $pdo->prepare($selectQuery);
    $stmt->execute($params);
    
    itemArticle($stmt); 
}

function album($stmt)
{
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Collection Keeper</title>
</head>

<body>
    <section id="add">
        <h1>add</h1>
        <form method="get" id="addForm">
            <label for="artist">artist
                <input type="text" name="artist" /></label>
            <label for="album">album
                <input type="text" name="album" /></label>
            <label for="vinyl">
                <input type="radio" class="radio" name="type" id="vinyl" value="vinyl">Vinyl</label>
            <label for="cd">
                <input type="radio" class="radio" name="type" id="cd" value="cd">CD</label>
            <label for="cassette">
                <input type="radio" class="radio" name="type" id="cassette" value="cassette">cassette</label>
            <input type="submit" name="itemAdd" value="Add to collection">
        </form>
    </section>
    <section id="vinyls">
        <h1>vinyls</h1>
        <?php query($pdo, 'vinyl'); ?>
    </section>
    <section id="cds">
        <h1>cd's</h1>
    </section>
    <section id="cassettes">
        <h1>cassettes</h1>
    </section>
</body>

</html>