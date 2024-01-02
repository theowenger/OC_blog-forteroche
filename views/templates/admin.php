<?php
/**
 * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun.
 * Et un formulaire pour ajouter un article.
 */

?>

<h2>Edition des articles</h2>

<div class="adminArticle">
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="content"><?= $article->getContent(200) ?></div>
            <div class="content flex-col"><p>créé le:</p><?= $article->displayDateCreation() ?></div>
            <div class="content flex-col"><p>Vues:</p><?= $article->getViewCount() ?></div>
            <div class="content flex-col"><p>Coms:</p><?= $article->getCommentsCount() ?></div>
            <div><a class="submit"
                    href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">Modifier</a></div>
            <div><a class="submit"
                    href="index.php?action=deleteArticle&id=<?= $article->getId() ?>" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?> >Supprimer</a>
            </div>
        </div>
    <?php } ?>
</div>

<a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>