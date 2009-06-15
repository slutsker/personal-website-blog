<?php
  require_once 'connection.php';

  function convertDate($date) {
    return date('M j, Y, g:ia T', $date);
  }
  
  function formatContent($content) {
    return nl2br($content);
  }

  function insertPost($post, $content, $name, $contact, $parent) {
    global $serv;
    if ($content != '') {
      mysql_query("INSERT INTO blog_comments
        (`parent-id`, `descriptor`, `comment-body`, `poster`, `posted-date`, `contact`)
        VALUES ('$parent', '$post', '$content', '$name', NOW(), '$contact')") or die (mysql_error());
    }
    header('Location:' . $serv . '/post/' . $post);
    die;
  }
  
  function displayReply($parent) {
    if ($parent != 0) {
      echo '<div style = "display: none;">';
    }
    echo '<form class = "reply_form" action = "" method = "post">';
    echo '<fieldset>';
    echo '<legend>Post ' . ($parent == 0 ? 'Comment' : 'Reply' ) . '</legend><label for="name">Name</label>';
    echo '<input type="hidden" name = "parent" value = "' . $parent .  '" /><br/>';
    echo '<input type="text" class="name" name = "name" value = "Anonymous" /><br/>';
    echo '<label for="name">Contact</label>';
    echo '<input type="text" class="contact" name = "contact" value = "None" /><br/>';
    echo '<label for="name">Post</label>';
    echo '<textarea rows = "7" cols = "25" name = "content"></textarea>';
    echo '<input type="submit" value="Post Comment" class="submit" />';
    echo '</fieldset>';
    echo '</form>';
    if ($parent != 0) {
      echo '</div>';
    }
  }

  function showComments($descriptor, $parent, $depth) {
    $result = mysql_query("
      SELECT `comment-id`, `parent-id`, `descriptor`, `comment-body`, `poster`, 
        UNIX_TIMESTAMP(`posted-date`) as `posted-date`, `contact` 
      FROM blog_comments 
      WHERE `parent-id` = '$parent' AND descriptor = '$descriptor'
      ORDER BY `posted-date` ASC
    ") or die (mysql_error());
    
    while ($row = mysql_fetch_array($result)) {
      echo '<div class = "comment" style = "margin-left: ' . $depth . 'px">';
      echo formatContent($row['comment-body']);
      echo '<div class = "comment-footer">Posted by ' . $row['poster'] . ' ' . ($row['contact'] != 'None' ? '(' . $row['contact'] . ')' : '') . ' at ' . convertDate($row['posted-date']) . ' <a href = "javascript:nothing(\'\');" class = "reply">Reply</a></div>';
      echo '</div>';
      displayReply($row['comment-id']);
      showComments($descriptor, $row['comment-id'], $depth + 20);
    }
  }

  function displayPost($descriptor, $title, $content, $poster, $posted_date, $num_comments) {
    $link_to_post = '<a href = "./post/' . $descriptor . '">';
    echo '<div class = "post">';
    echo '<h2>' . $link_to_post . $title . '</a></h2>';
    echo formatContent($content);
    echo '<div class = "post-footer">Posted by ' . $poster . ' at ' . convertDate($posted_date) . ' | ' . $link_to_post . $num_comments . ' comments</a></div>';
    echo '</div>';
  }

  function getPosts($descriptor = '') {
    $where_clause = "WHERE blog_posts.descriptor = '$descriptor'";
    if ($descriptor == '') {
      $where_clause = '';
    }
    $result = mysql_query("
      SELECT blog_posts.descriptor, blog_posts.title, blog_posts.content, blog_posts.poster,
        UNIX_TIMESTAMP(blog_posts.posted_date) as posted_date, 
        COUNT(blog_comments.`comment-id`) as num_comments 
      FROM blog_posts LEFT OUTER JOIN blog_comments on blog_posts.descriptor = blog_comments.descriptor 
      $where_clause
      GROUP BY blog_posts.descriptor 
      ORDER BY posted_date DESC
    ") or die (mysql_error());
    
    while ($row = mysql_fetch_array($result)) {
      displayPost($row['descriptor'], $row['title'], $row['content'], $row['poster'],
        $row['posted_date'], $row['num_comments']);
    }
  }
  
  
  if (isset($_GET['post'])) {
    $_GET['post'] = mysql_real_escape_string($_GET['post']);
    if (isset($_POST['content'])) {
      $_POST['content'] = mysql_real_escape_string(htmlentities($_POST['content']));
      $_POST['name'] = mysql_real_escape_string(htmlentities($_POST['name']));
      $_POST['contact'] = mysql_real_escape_string(htmlentities($_POST['contact']));
      $_POST['parent'] = mysql_real_escape_string(htmlentities($_POST['parent']));
      insertPost($_GET['post'], $_POST['content'], $_POST['name'], $_POST['contact'], $_POST['parent']);
    }
    require_once 'header.php';
    getPosts($_GET['post']);
    echo '<div id = "comments-heading">Comments</div>';
    showComments($_GET['post'], 0, 0);
    displayReply(0);
  }
  else {
    require_once 'header.php';
    getPosts();
  }

  require_once 'footer.php';
?>
