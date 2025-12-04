#!/bin/bash

echo "🚀 Setting up Job App Application..."
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker Desktop first."
    echo "   Download from: https://www.docker.com/products/docker-desktop"
    exit 1
fi

# Check if Docker is running
if ! docker info &> /dev/null; then
    echo "❌ Docker is not running. Please start Docker Desktop first."
    exit 1
fi

# Check if job-backoffice database is running
echo "🔍 Checking if job-backoffice database is running..."
if ! docker network inspect jobboard-network &> /dev/null; then
    echo "❌ Error: job-backoffice is not running!"
    echo ""
    echo "⚠️  The job-app requires the job-backoffice database to be running."
    echo "   Please setup and start job-backoffice first:"
    echo ""
    echo "   1. Clone job-backoffice repository"
    echo "   2. Run: cd job-backoffice && ./setup.sh"
    echo "   3. Wait for it to complete"
    echo "   4. Then come back and run this script again"
    echo ""
    exit 1
fi

echo "✓ job-backoffice database is running"
echo ""

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
else
    echo "✓ .env file already exists"
fi

# Stop and remove existing containers (if any)
echo "🧹 Cleaning up existing containers..."
docker-compose down 2>/dev/null

# Build and start containers
echo "🐳 Building and starting Docker containers..."
echo "   This may take 5-10 minutes on first run..."
docker-compose up -d --build

# Wait for services to be ready
echo ""
echo "⏳ Waiting for application to initialize..."
sleep 20

# Check if containers are running
echo ""
echo "📊 Checking container status..."
docker-compose ps

echo ""
echo "✅ Setup complete!"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📱 Your Job App is running at:"
echo "   🌐 Application: http://localhost:8001"
echo "   📄 PDF Text Extraction: Enabled (poppler-utils)"
echo "   🤖 Gemini AI: Configured"
echo "   ☁️  Supabase Storage: Connected"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "💡 Useful Commands:"
echo "   📋 View logs: docker-compose logs -f app"
echo "   🔄 Restart: docker-compose restart"
echo "   🛑 Stop: docker-compose down"
echo ""