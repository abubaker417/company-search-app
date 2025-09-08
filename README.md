# Company Search & Reports Application

A Laravel-based centralized company search and reports system that integrates with Singapore (SG) and Mexico (MX) company databases. The application allows unified search across multiple databases, displays company details with country-specific report logic, and includes a shopping cart functionality for purchasing reports.

## Features

### Unified Company Search
- **Multi-database search**: Search across Singapore and Mexico company databases simultaneously
- **Real-time search**: AJAX-powered search with instant results
- **Flexible search criteria**: Search by company name, registration number, or slug
- **Optimized performance**: Efficient queries with proper indexing and limits

### Company Details
- **Country-specific logic**: Different report availability rules for SG vs MX companies
- **Singapore (SG)**: All companies have access to all available reports
- **Mexico (MX)**: Reports available depend on the company's state
- **Comprehensive information**: Display company details, addresses, and available reports

### Shopping Cart System
- **Mixed country support**: Handle reports from different countries in the same cart
- **Dynamic pricing**: Correct pricing logic based on country-specific rules
- **Session-based cart**: Persistent cart across page visits
- **Quantity management**: Add, update, and remove items with quantity controls

### Modern UI/UX
- **Responsive design**: Mobile-first approach with Tailwind CSS
- **Professional interface**: Clean, modern design with intuitive navigation
- **Real-time feedback**: Loading states, success/error messages

## Technical Architecture

### Database Design

#### Singapore Database (`companies_house_sg`)
- **companies**: Company information with direct report access
- **reports**: Available reports with pricing information
- **Logic**: All companies have access to all reports

#### Mexico Database (`companies_house_mx`)
- **companies**: Company information linked to states
- **states**: Mexican states information
- **reports**: Available report types
- **report_state**: Junction table linking reports to states with pricing
- **Logic**: Reports available based on company's state

### Laravel Implementation

#### Models
- `CompanySg`: Singapore companies with direct report relationships
- `CompanyMx`: Mexico companies with state-based report relationships
- `ReportSg`: Singapore reports with pricing
- `ReportMx`: Mexico reports
- `State`: Mexican states
- `ReportState`: Junction model for state-report relationships

#### Controllers
- `CompanyController`: Handles search and company details
- `CartController`: Manages shopping cart operations

#### Key Features
- **Multi-database connections**: Separate database connections for SG and MX
- **Eloquent relationships**: Proper model relationships for data integrity
- **Validation**: Comprehensive input validation and error handling
- **AJAX support**: Real-time search and cart operations

## Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL
- Node.js and npm (for frontend assets)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/abubaker417/company-search-app.git
   cd company-search-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   The application uses mysql databases. Update `.env` if using different databases:
   
   ```env
   DB_MX_HOST=mysql
   DB_MX_PORT=3306
   DB_MX_DATABASE=companies_house_mx
   DB_MX_USERNAME=root
   DB_MX_PASSWORD=rootadmin123
   
   DB_SG_HOST=mysql
   DB_SG_PORT=3306
   DB_SG_DATABASE=companies_house_sg
   DB_SG_USERNAME=root
   DB_SG_PASSWORD=rootadmin123
   ```
   
6. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

## Usage

### Searching Companies
1. Navigate to the home page
2. Enter a company name, registration number, or partial text
3. View real-time search results from both Singapore and Mexico databases
4. Click "View Details" to see company information and available reports

### Viewing Company Details
1. From search results, click on a company
2. View comprehensive company information
3. See available reports based on country-specific logic:
   - **Singapore**: All reports available to all companies
   - **Mexico**: Reports available based on company's state

### Shopping Cart
1. From company details page, select reports and quantities
2. Click "Add to Cart" to add items
3. Navigate to cart to review items and quantities
4. Update quantities or remove items as needed
5. View total pricing with country-specific calculations

## API Endpoints

### Company Search
- `GET /companies` - Company search page
- `GET /companies/search?q={query}` - Search companies (AJAX)
- `GET /companies/{country}/{id}` - Company details
- `GET /companies/{country}/{id}/reports` - Company reports (AJAX)

### Cart Operations
- `GET /cart` - View cart
- `POST /cart/add` - Add item to cart
- `PUT /cart/update/{key}` - Update item quantity
- `DELETE /cart/remove/{key}` - Remove item from cart
- `DELETE /cart/clear` - Clear entire cart
- `GET /cart/count` - Get cart item count (AJAX)

### Performance Optimizations
- **Database indexing**: Proper indexes on search fields
- **Query optimization**: Efficient Eloquent relationships
- **Caching**: Session-based cart and search result caching
- **Pagination**: Limit search results to prevent memory issues
- **AJAX loading**: Real-time search without page reloads

## Testing

### Manual Testing
1. **Search functionality**: Test various search terms
2. **Company details**: Verify country-specific report logic
3. **Cart operations**: Test add, update, remove, and clear operations
4. **Cross-country cart**: Mix reports from different countries
5. **Responsive design**: Test on different screen sizes

## Deployment

### Production
1. **Environment variables**: Properly configured with production values to ensure security and stability.
2. **Database**: Connected to the production database (MySQL) with optimized settings for performance.
4. **SSL & Security**: Configured HTTPS with an SSL certificate for secure data transmission.

### Hosting & Deployment

  - **Dedicated VPS (IONOS)**
    - Application deployed on a **dedicated VPS** purchased from **IONOS**.
    - Configured with **sub-domain mapping**, **SSL certificates**, and production-ready environment.

## Dockerized Setup

You can run the app fully containerized using the provided `Dockerfile` and `docker-compose.yml`.

### Files

```Dockerfile
FROM php:8.1-fpm

ARG user
ARG uid

USER root

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www

WORKDIR /var/www

RUN php --version && composer --version
EXPOSE 8001
CMD ["sh","-c","php artisan serve  --host=0.0.0.0 --port=8001"]
```

```yaml
version: '3.8'

services:
  company-search-app:
    build: ./
    container_name: company-search-app
    volumes:
      - ./:/var/www
    restart: unless-stopped
    ports:
      - "5003:8001"
    networks:
      - pg-network

networks:
  pg-network:
    external: true
```

### Prerequisites

- Docker and Docker Compose installed
- External Docker network named `pg-network` (or change the compose file)

Create the external network if you don't already have it:

```bash
docker network create pg-network || true
```

### First-time Setup (inside the container)

Build and start the container:

```bash
docker compose up --build -d
```

Access the app at:

- `http://localhost:5003`

The container starts Laravel's built-in server on port 8001 and maps it to 5003 on your host.
