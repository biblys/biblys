<?php

if (!$_V->isAdmin() && !$_V->isPublisher()) {
  throw new Framework\Exception\AuthException("Page réservée aux éditeurs.");   
}

return require_once("adm_article.php");
