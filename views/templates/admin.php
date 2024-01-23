<?php
/**
 * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun.
 * Et un formulaire pour ajouter un article.
 */

?>

<form action="index.php" method="get" class="form-admin">
    <div class="dropdown-content">
        <input type="hidden" name="action" value="admin">

        <label for="order">Trier par :</label>
        <select name="order" id="order">
            <option value="title">Titre</option>
            <option value="date_creation">Date de création</option>
            <option value="view_count">Vue</option>
            <option value="comment_count">Commentaire</option>
        </select>

        <label for="sort"> Descendant :</label>
        <input type="checkbox" name="sort" id="sort" value="desc">

        <input class="submit-form" type="submit" value="Trier">
    </div>
</form>

<h2>Edition des articles</h2>

<div class="adminArticle">
    <?php

    $alternateClass = 'primary';

    foreach ($articles as $article) {
        $alternateClass = ($alternateClass === 'primary') ? 'secondary' : 'primary';
        ?>
        <div class="articleLine <?= $alternateClass ?>">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="description"><?= $article->getContent(200) ?></div>
            <div class="content flex-col date"><p>créé le:</p><?= $article->displayDateCreation() ?></div>
            <div class="content flex-col"><p>Vues:</p><?= $article->getViewCount() ?></div>
            <div class="content flex-col"><p>Coms:</p><?= $article->getCommentsCount() ?></div>
            <div class="admin-button-container">
                <a class="submit"
                   href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">Modifier</a>
                <a class="submit submit-delete"
                   href="index.php?action=deleteArticle&id=<?= $article->getId() ?>"
                    <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?> >Supprimer</a>
            </div>
        </div>
    <?php } ?>
</div>

<div class="submit-article-container">
    <a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>
</div>