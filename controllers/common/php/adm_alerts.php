<?php

$_PAGE_TITLE = 'Livres les plus recherchés';

$alerts = $_SQL->query("SELECT COUNT(`alert_id`) AS `num`, `article_title`, `article_url`, `article_authors`, `article_collection`, `article_number`, `article_publisher`
    FROM `alerts`
    JOIN `articles` USING(`article_id`)
    GROUP BY `article_id`
    ORDER BY COUNT(`alert_id`) DESC");

$table = null;
while($a = $alerts->fetch()) {
    $table .= '
        <tr>
            <td>
                <a href="/'.$a["article_url"].'">'.$a["article_title"].'</a><br>
                '.authors($a["article_authors"]).'
            </td>
            <td>'.$a["article_collection"].numero($a["article_number"]).'</td>
            <td class="center">'.$a["num"].'</td>
        </tr>
    ';
}

$_ECHO .= '

    <h1><span class="fa fa-bell"></span> '.$_PAGE_TITLE.'</h1>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Livre</th>
                <th>Collection</th>
                <th>Alertes</th>
            </tr>
        </thead>
        <tbody>
            '.$table.'
        </tbody>
    </table>

';

?>
