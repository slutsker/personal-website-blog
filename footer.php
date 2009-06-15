</div>
<div id = "footer">
    <p>This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-Share Alike 3.0 Unported License</a>. Feel free to reuse any of it under the terms of the license.</p>
    <p>This website was last modified on 
    <?php 
        $last_modified = filemtime('index.php');
        $last_modified = max($last_modified, filemtime('aboutme.php'));
        $last_modified = max($last_modified, filemtime('projects.php'));
        echo date('j F Y, G:i', $last_modified) . '.';
    ?>
    <a href = "http://validator.w3.org/check?uri=referer">Valid XHTML</a>
    </p>
</div>
<div id = "debug">
</div>
</body>
</html>
        
