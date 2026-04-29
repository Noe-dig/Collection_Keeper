<?php

include_once "connection.php";
include_once "api.php";

session_start();

if (isset($_POST['delete_id'])) {
    $id_to_delete = $_POST['delete_id'];
    $sql = "DELETE FROM media WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_to_delete]);

    header("Location: index.php");
    exit;
}

if (isset($_POST['itemAdd'])) {
    $allowed = ['vinyl', 'cd', 'cassette'];
    $media = $_POST['type'];

    if (in_array($media, $allowed)) {
        $coverUrl = getReleaseCoverArt($_POST['artist'], $_POST['album']);
        if (!$coverUrl) { $coverUrl = "img.png"; }

        $userId = $_SESSION['userID'] ?? null;

        $sql = "INSERT INTO media (artist, album, type, cover_url, user_id) 
                VALUES (?, ?, ?, ?, ?)";

        $add = $pdo->prepare($sql);
        $add->execute([
            $_POST['artist'],
            $_POST['album'],
            $_POST['type'],
            $coverUrl,
            $userId
        ]);

        header("Location: index.php");
        exit;
    }
}

function query($pdo, $type)
{
    $userId = $_SESSION['userID'] ?? null;

    if ($userId) {
        $selectQuery = "SELECT id, artist, album, type, cover_url 
                        FROM media 
                        WHERE type = ? AND user_id = ? 
                        ORDER BY artist ASC";
        $params = [$type, $userId];
    } else {
        $selectQuery = "SELECT id, artist, album, type, cover_url 
                        FROM media 
                        WHERE type = ? AND user_id IS NULL 
                        ORDER BY artist ASC";
        $params = [$type];
    }

    $stmt = $pdo->prepare($selectQuery);
    $stmt->execute($params);
    album($stmt);
}

function album($stmt)
{

    while ($dbItem = $stmt->fetch()) {
        $id = $dbItem["id"];
        $artist = htmlspecialchars($dbItem["artist"]);
        $album = htmlspecialchars($dbItem["album"]);
        $image = htmlspecialchars($dbItem["cover_url"]);
        ?>

        <article class="albumArt">
            <table>
                <tr class="albumRow">
                    <td class="cover"><img src="<?= $image ?>" alt="cover for <?= $album ?>" class="coverImg"></td>
                    <td class="artist"><b><?= $artist ?></b></td>
                    <td class="album"><?= $album ?></td>
                    <td class="delete">
                        <form method="post" onsubmit="return confirm('Delete this album from your collection?');">
                            <input type="hidden" name="delete_id" value="<?= $id ?>">
                            <button type="submit" class="btn-delete">x</button>
                        </form>
                    </td>
                </tr>
            </table>
        </article>

        <?php
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Collection Keeper</title>
</head>

<body>
    <section id="add">
        <div>
            <?php if (isset($_SESSION['loggedInUser'])): ?>
                <p>Logged in as: <?= htmlspecialchars($_SESSION['loggedInUser']) ?>
                    | <a href="logout.php">Logout</a></p>
            <?php else: ?>
                <p><a href="login.php">Login</a> to save your collection permanently.</p>
            <?php endif; ?>
        </div>
        <h1>add</h1>
        <form method="post" id="addForm">
            <label for="artist">artist
                <input type="text" name="artist" required /></label>
            <label for="album">album
                <input type="text" name="album" required /></label>
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
        <?php query($pdo, 'cd'); ?>
    </section>
    <section id="cassettes">
        <h1>cassettes</h1>
        <?php query($pdo, 'cassette'); ?>
    </section>
</body>

</html>