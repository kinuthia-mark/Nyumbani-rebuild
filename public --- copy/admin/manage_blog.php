<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Blog | Nyumbani Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex !important; background: #f4f7f6 !important; margin: 0; text-align: left !important; font-family: 'Poppins', sans-serif; }
        .admin-main { flex: 1; margin-left: 260px; padding: 40px; box-sizing: border-box; }
        .admin-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        
        .blog-form { display: flex; flex-direction: column; gap: 15px; }
        input[type="text"], textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        textarea { height: 150px; resize: vertical; }
        
        .btn-publish { background: #4175FC; color: white; padding: 12px 25px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; width: fit-content; }
        
        .post-list-item { background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 5px solid #4175FC; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>
    
    <main class="admin-main">
        <?php if(isset($_GET['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Blog post published successfully!
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <h2><i class="fas fa-pen-nib" style="color: #4175FC;"></i> Create New Blog Post</h2>
            <form action="blog_handler.php" method="POST" enctype="multipart/form-data" class="blog-form">
                <div>
                    <label>Post Title</label>
                    <input type="text" name="blog_title" placeholder="e.g. Christmas Celebration 2025" required>
                </div>
                
                <div>
                    <label>Content (The Story)</label>
                    <textarea name="blog_content" placeholder="Write your blog post here..." required></textarea>
                </div>

                <div>
                    <label>Featured Image (Optional)</label><br>
                    <input type="file" name="blog_img" accept="image/*" style="margin-top: 5px;">
                </div>

                <button type="submit" name="submit_blog" class="btn-publish">Publish Post</button>
            </form>
        </div>

        <h3>Recent Posts</h3>
        <div class="post-management-list">
            <?php
            $res = mysqli_query($conn, "SELECT id, title, created_at FROM blog_posts ORDER BY created_at DESC");
            while($row = mysqli_fetch_assoc($res)) {
                echo "<div class='post-list-item'>
                        <div>
                            <strong style='display:block;'>".$row['title']."</strong>
                            <small style='color:#888;'>Published: ".date('M d, Y', strtotime($row['created_at']))."</small>
                        </div>
                        <a href='delete_blog.php?id=".$row['id']."' 
                           onclick='return confirm(\"Delete this post permanently?\")' 
                           style='color:#e74c3c; text-decoration:none;'>
                           <i class='fas fa-trash'></i> Delete
                        </a>
                      </div>";
            }
            ?>
        </div>
    </main>

</body>
</html>