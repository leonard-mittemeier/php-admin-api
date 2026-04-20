<?php
class View {
    public static function render($content, $title = "Admin") {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?= htmlspecialchars($title) ?></title>
            <link rel="stylesheet" href="/public/assets/style.css">
        </head>
        <body>
            <div class="container">
                <?= $content ?>
            </div>
        </body>
        </html>
        <?php
    }
}