<?php
$content = file_get_contents('test-output-tasks.json');
echo substr(strip_tags($content), 0, 500);
