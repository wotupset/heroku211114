@echo off

:top

git add .
git commit -am "make it better"
git push heroku master

pause
pause

goto top
