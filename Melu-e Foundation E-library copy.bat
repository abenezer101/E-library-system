@REM @echo off
@REM cd /d %~dp0
@REM start cmd.exe /k "python3 "C:/xampp/htdocs/Melu-e E-Library system/mainBOT.py""


@echo off
cd /d %~dp0

REM Start XAMPP control panel
start "" "C:\xampp\xampp_start.exe"

REM Wait for a few seconds to ensure XAMPP services are up and running
timeout /t 5 /nobreak > nul

REM Start Apache and MySQL services independently to ensure they stay up
start "" "C:\xampp\apache\bin\httpd.exe"
start "" "C:\xampp\mysql\bin\mysqld.exe"

REM Open the specified URL in the default browser
start "" "http://localhost/Melu-e E-Library system/Dashboard/pages/index.php"

REM Start the Python script in a new command prompt window
start cmd.exe /k "python3 "C:/xampp/htdocs/Melu-e E-Library system/mainBOT.py""

REM Exit the current command prompt window
exit


