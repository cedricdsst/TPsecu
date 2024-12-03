<?php
function fetch_posts($search = '') {
    global $pdo;
    $sql = "SELECT posts.id, posts.content, posts.image_name, posts.created_at, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id";
    if ($search) {
        $sql .= " WHERE posts.content LIKE :search";
    }
    $sql .= " ORDER BY posts.created_at DESC LIMIT 100";
    $stmt = $pdo->prepare($sql);
    if ($search) {
        $stmt->execute(['search' => '%' . $search . '%']);
    } else {
        $stmt->execute();
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_comments($post_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT comments.content, comments.created_at, users.username 
                           FROM comments 
                           JOIN users ON comments.user_id = users.id 
                           WHERE comments.post_id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function fetch_post_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


?>
