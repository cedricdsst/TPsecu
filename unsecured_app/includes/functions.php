<?php
function fetch_posts($search = '')
{
    global $pdo;
    $sql = "SELECT posts.id, posts.content, posts.image_name, posts.created_at, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id";
    if ($search) {
        // Directly interpolate user input into the query
        $sql .= " WHERE posts.content LIKE '%$search%'";
    }
    $sql .= " ORDER BY posts.created_at DESC LIMIT 100";

    // Execute the query without preparing it
    $result = $pdo->query($sql);

    return $result->fetchAll(PDO::FETCH_ASSOC);
}


function fetch_comments($post_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT comments.content, comments.created_at, users.username 
                           FROM comments 
                           LEFT JOIN users ON comments.user_id = users.id 
                           WHERE comments.post_id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function fetch_post_by_id($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
