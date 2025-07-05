# Englicious

## Prerequisites

Before running the application, make sure you have the following installed:

1. PHP >= 8.0
2. Composer
3. Node.js and npm
4. MySQL/MariaDB
5. `pdftotext` utility (for PDF text extraction)

### Installing pdftotext

#### Windows
1. Download and install Xpdf tools from: https://www.xpdfreader.com/download.html
2. Add the installation directory to your system's PATH environment variable

#### Linux (Ubuntu/Debian)
```bash
sudo apt-get update
sudo apt-get install poppler-utils
```

#### macOS
```bash
brew install poppler
```

## Installation

1. Clone the repository
```bash
git clone <repository-url>
cd englicious
```

2. Install PHP dependencies
```bash
composer install
```

3. Install JavaScript dependencies
```bash
npm install
```

4. Copy the environment file and configure it
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run database migrations
```bash
php artisan migrate
```

7. Build assets
```bash
npm run dev
```

8. Start the development server
```bash
php artisan serve
```

The application should now be running at `http://localhost:8000`.
