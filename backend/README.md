# SarayGo REST API

A RESTful API for the SarayGo application built with FlightPHP.

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Apache/Nginx web server

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/saraygo.git
cd saraygo/backend
```

2. Install dependencies:
```bash
composer install
```

3. Create a `.env` file in the project root directory and configure your environment variables:
```env
# Database Configuration
DB_HOST=localhost
DB_NAME=saraygo
DB_USER=your_username
DB_PASS=your_password

# Application Configuration
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# JWT Configuration
JWT_SECRET=your-secret-key
JWT_EXPIRATION=3600

# Email Configuration
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@saraygo.com
MAIL_FROM_NAME=SarayGo

# File Upload Configuration
UPLOAD_DIR=uploads
MAX_FILE_SIZE=5242880 # 5MB
ALLOWED_FILE_TYPES=jpg,jpeg,png,gif
```

4. Create the database and run migrations:
```bash
mysql -u your_username -p your_password < database/schema.sql
```

5. Configure your web server to point to the `public` directory.

## API Documentation

The API documentation is available at `/api/docs` when running the application.

### Authentication

Most endpoints require authentication. Include the JWT token in the Authorization header:
```
Authorization: Bearer your_jwt_token
```

### Available Endpoints

#### Users
- `POST /api/users/register` - Register a new user
- `POST /api/users/login` - Login and get JWT token
- `GET /api/users` - Get all users
- `GET /api/users/@id` - Get user by ID
- `PUT /api/users/@id` - Update user
- `DELETE /api/users/@id` - Delete user

#### Activities
- `GET /api/activities` - Get all activities
- `GET /api/activities/@id` - Get activity by ID
- `POST /api/activities` - Create new activity
- `PUT /api/activities/@id` - Update activity
- `DELETE /api/activities/@id` - Delete activity

#### Blogs
- `GET /api/blogs` - Get all blogs
- `GET /api/blogs/@id` - Get blog by ID
- `POST /api/blogs` - Create new blog
- `PUT /api/blogs/@id` - Update blog
- `DELETE /api/blogs/@id` - Delete blog

#### Categories
- `GET /api/categories` - Get all categories
- `GET /api/categories/@id` - Get category by ID
- `POST /api/categories` - Create new category
- `PUT /api/categories/@id` - Update category
- `DELETE /api/categories/@id` - Delete category

#### Moods
- `GET /api/moods` - Get all moods
- `GET /api/moods/@id` - Get mood by ID
- `POST /api/moods` - Create new mood
- `PUT /api/moods/@id` - Update mood
- `DELETE /api/moods/@id` - Delete mood

#### Recommendations
- `GET /api/recommendations` - Get all recommendations
- `GET /api/recommendations/@id` - Get recommendation by ID
- `POST /api/recommendations` - Create new recommendation
- `PUT /api/recommendations/@id` - Update recommendation
- `DELETE /api/recommendations/@id` - Delete recommendation

#### Reviews
- `GET /api/reviews` - Get all reviews
- `GET /api/reviews/@id` - Get review by ID
- `POST /api/reviews` - Create new review
- `PUT /api/reviews/@id` - Update review
- `DELETE /api/reviews/@id` - Delete review

#### User Moods
- `GET /api/user-moods` - Get all user moods
- `GET /api/user-moods/@id` - Get user mood by ID
- `POST /api/user-moods` - Create new user mood
- `PUT /api/user-moods/@id` - Update user mood
- `DELETE /api/user-moods/@id` - Delete user mood

## Development

1. Start the development server:
```bash
php -S localhost:8000 -t public
```

2. Run tests:
```bash
composer test
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details. 