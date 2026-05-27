@echo off
set "COMPOSER_CACHE_DIR=%~dp0.tools\composer-cache"
"%~dp0.tools\php\php.exe" "%~dp0.tools\composer.phar" %*
