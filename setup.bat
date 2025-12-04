@echo off
echo Setting up Job App Application...
echo.

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo Docker is not running. Please start Docker Desktop first.
    echo Download from: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

REM Check if job-backoffice database is running
echo Checking if job-backoffice database is running...
docker network inspect jobboard-network >nul 2>&1
if errorlevel 1 (
    echo Error: job-backoffice is not running!
    echo.
    echo The job-app requires the job-backoffice database to be running.
    echo Please setup and start job-backoffice first:
    echo.
    echo    1. Clone job-backoffice repository
    echo    2. Run: setup.bat in job-backoffice folder
    echo    3. Wait for it to complete
    echo    4. Then come back and run this script again
    echo.
    pause
    exit /b 1
)

echo job-backoffice database is running
echo.

REM Create .env file if it doesn't exist
if not exist .env (
    echo Creating .env file from .env.example...
    copy .env.example .env
) else (
    echo .env file already exists
)

REM Stop and remove existing containers
echo Cleaning up existing containers...
docker-compose down >nul 2>&1

REM Build and start containers
echo Building and starting Docker containers...
echo This may take 5-10 minutes on first run...
docker-compose up -d --build

REM Wait for services
echo.
echo Waiting for application to initialize...
timeout /t 20 /nobreak >nul

REM Check container status
echo.
echo Checking container status...
docker-compose ps

echo.
echo Setup complete!
echo.
echo ================================================
echo Your Job App is running at:
echo    Application: http://localhost:8001
echo    PDF Text Extraction: Enabled (poppler-utils)
echo    Gemini AI: Configured
echo    Supabase Storage: Connected
echo ================================================
echo.
echo Useful Commands:
echo    View logs: docker-compose logs -f app
echo    Restart: docker-compose restart
echo    Stop: docker-compose down
echo.
pause