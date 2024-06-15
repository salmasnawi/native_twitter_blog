<?php
session_start();
require_once('classes.php');
require_once('header.php');
if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);
    if (is_object($user)) {
        $username = $user->username;

        if ($user->role == 'admin') {
            header('Location: admin.php');
            exit;
        }
    } else {
        echo "Error: Unable to unserialize user data.";
        exit;
    }
} else {
    header("Location: login.php");
    echo "You are not logged in.";
    exit;}

if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);
    if (is_object($user)) {
        $username = $user->username;
        $myposts = $user->getMyTweets($user->id);
    } else {
        echo "Error: Unable to unserialize user data.";
        exit;
    }
} else {
    header("Location: login.php");

    echo "You are not logged in.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="styles/comments.css">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles/right.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8Ya3W2ggJXfXALMeZjQwdXnLgR2t6+I6auKt+D5r8V5wqK5Q==" crossorigin="anonymous" referrerpolicy="no-referrer">
   <style>
    .unlike-btn {
  background-color: white; 
  color: red; 
  border: none;
  padding: 5px 10px;
  font-size: 14px;
  cursor: pointer;
}


   </style>
</head>
<body>
    <div class="grid-container">
        <div class="leftoo">
            <div class="sidebar-item">
                <h3>Menu</h3>
                <ul>
                    <li><a href="user.php">Profile</a></li>
                    <li><a href="#">Explore</a></li>
                    <li><a href="#">Notifications</a></li>
                </ul>
            </div>
        </div>

        <div class="main">
            <div class="tweet__box tweet__add">
                <div class="tweet-box">
                    <div class="tweet-box-header">
                        <i class="fab fa-twitter"></i>
                        <i class="fas fa-times close-icon"></i>
                    </div>
                    <div class="tweet-box-body">
                        <form action="store_posts.php" method="post" enctype="multipart/form-data">
                            <input type="text" name="title" placeholder="Title" class="tweet-title" required>
                            <textarea name="content" placeholder="What's happening?" required></textarea>
                            <div class="tweet-box-footer">
                                <div class="tweet-icons">
                                    <label for="upload_image" class="icon">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="upload_image" name="image" accept="image/*" style="display:none;">
                                    <i class="far fa-file-image icon"></i>
                                    <i class="fas fa-map-marker-alt icon"></i>
                                    <i class="far fa-grin icon"></i>
                                    <i class="far fa-user icon"></i>
                                </div>
                                <div class="tweet-action">
                                    <button class="button-tweet" type="submit" name="btn_add_post">Tweet</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php foreach($myposts as $tweet) { ?>
    <div class="post-box">
        <h2><?php echo htmlspecialchars($tweet['title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($tweet['content'])); ?></p>
        <?php if (!empty($tweet['image'])): ?>
            <img src="<?php echo htmlspecialchars($tweet['image']); ?>" alt="Tweet Image">
        <?php endif; ?>
        <div class="likes-section">
                        <?php
                        $likes = $user->getLikesForTweet($tweet['id']);
                        ?>
                        <span><?php echo $likes; ?> Likes</span>
                        <form action="like_tweet.php" method="post">
                            <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                            <button type="submit" class="like-btn">
                                <i class="fa fa-heart"></i>
                            </button>
                        </form>
                        <form action="remove_like.php" method="post">
        <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
        <button type="submit" class="unlike-btn">
        <i class="fa fa-thumbs-down"></i>    </form>
                    </div>
    </div>


        <div class="comments-section">
    <div class="tweet__left">
        <img src="images/user.jpg" alt="Profile picture">
        <span><?php echo htmlspecialchars($user->username); ?></span>
    </div>
    <?php
    $comments = $user->getCommentsForTweet($tweet['id']);
    foreach ($comments as $comment) {
        ?>
        <div class="comment">
            <span><?php echo htmlspecialchars($comment['comment_text']); ?></span>
        </div>
        <?php
    }
    ?>

            <form action="store_comment.php" method="post">
                <input type="text" name="comment" placeholder="Add a comment..." required>
                <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                <button type="submit"style="background-color: #e91e63 ;">Comment</button>
            </form>
   
        </div>
     
    
    
<?php } ?>

            </div>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-item">
                <h3>Trends for you</h3>
                <ul>
                    <li><a href="#">#art</a></li>
                    <li><a href="#">#beauty</a></li>
                    <li><a href="#">#sport</a></li>
                </ul>
            </div>
            <div class="sidebar-item">
                <h3>Who to follow</h3>
                <ul>
                    <li><a href="#">@dr.Ashraf Abd Abdelaziz</a></li>
                    <li><a href="#">@Ahmed sultan</a></li>
                    <li><a href="#">@salma atef(me)</a></li>
                </ul>
            </div>
        </div>
        <button class="toggle-button" id="toggleLeftButton">Menu</button>
        <button class="toggle-button" id="toggleRightButton">Trends</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript to toggle sidebars on small screens
        const leftSidebar = document.querySelector('.leftoo');
        const rightSidebar = document.querySelector('.sidebar');
        const toggleLeftButton = document.getElementById('toggleLeftButton');
        const toggleRightButton = document.getElementById('toggleRightButton');

        document.addEventListener('DOMContentLoaded', function () {
            toggleLeftButton.addEventListener('click', function () {
                leftSidebar.classList.toggle('show');
            });
            toggleRightButton.addEventListener('click', function () {
                rightSidebar.classList.toggle('show');
            });
        });
 
    </script>
</body>
</html>
