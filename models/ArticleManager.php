<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager 
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles() : array
    {
        $sql = "SELECT * FROM article";
        return $this->extracted($sql);
    }
    public function getFilteredArticles($sort, $order) : array
    {
        if($order === "comment_count") {
            $sql = "SELECT article.*, COUNT(comment.id) AS $order
            FROM article
            LEFT JOIN comment ON article.id = comment.id_article
            GROUP BY article.id ORDER BY $order $sort";
        } else {
            $sql = "SELECT * FROM article ORDER BY $order $sort";
        }
        return $this->extracted($sql);
    }
    
    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id) : ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            $article = new Article($article);
            $article->incrementViewCount();
            $this->saveViewCount($article->getId(), $article->getViewCount());
            return $article;
        }
        return null;
    }

    public function saveViewCount(int $id, int $viewCount) : void
    {
        $sql = "UPDATE article SET view_count = :view_count WHERE id = :id";
        $this->db->query($sql, ['id' => $id, 'view_count' => $viewCount]);
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article) : void 
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article) : void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation, date_update) VALUES (:id_user, :title, :content, NOW(), NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article) : void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id) : void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    public function getCommentsCount(int $id): int
    {
        $sql = "SELECT COUNT(id) AS comment_count FROM comment WHERE id_article = :id";
        return $this->db->query($sql, ['id' => $id])->fetchColumn();
    }

    /**
     * @param string $sql
     * @return array
     */
    public function extracted(string $sql): array
    {
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $article = new Article($article);

            $commentsCount = $this->getCommentsCount($article->getId());
            $article->setCommentsCount($commentsCount);
            $articles[] = $article;
        }
        return $articles;
    }

}