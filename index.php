<?php

require_once __DIR__ . '/backend/bootstrap.php';
require_login();

require 'site.php';
load_top();
load_sitebar();
require('widget/content.php');
load_footer();
