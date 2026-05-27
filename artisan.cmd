@echo off
cd /d "%~dp0backend"
"%~dp0.tools\php\php.exe" artisan %*
