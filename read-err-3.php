<?php
$content = file_get_contents('test-3-out.json');
echo substr(strip_tags($content), 0, 500);
