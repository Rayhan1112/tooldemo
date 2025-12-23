# Deploying to Render

This guide explains how to deploy your Laravel email scheduler application to Render.

## Prerequisites

1. A Render account (sign up at [render.com](https://render.com))
2. Your application code pushed to a GitHub repository
3. A MySQL database instance (you can create one on Render)

## Steps to Deploy

### 1. Prepare Your Repository

Make sure your code is pushed to a GitHub repository that Render can access.

### 2. Create a New Web Service on Render

1. Log in to your Render account
2. Click "New +" and select "Web Service"
3. Connect your GitHub repository
4. Select the branch you want to deploy (usually `main` or `master`)

### 3. Configure Your Web Service

- **Environment**: PHP
- **Build Command**: `composer install && php artisan migrate --force`
- **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

### 4. Environment Variables

Add the following environment variables in the Render dashboard:

#### Application Settings
- `APP_ENV`: `production`
- `APP_DEBUG`: `false`
- `APP_URL`: Your Render service URL (e.g., `https://your-app-name.onrender.com`)

#### Database Settings
- `DB_CONNECTION`: `mysql`
- `DB_HOST`: Your MySQL host
- `DB_PORT`: Your MySQL port (typically `3306`)
- `DB_DATABASE`: Your database name
- `DB_USERNAME`: Your database username
- `DB_PASSWORD`: Your database password

#### Mail Settings
- `MAIL_MAILER`: `smtp`
- `MAIL_HOST`: `smtp.gmail.com`
- `MAIL_PORT`: `587`
- `MAIL_USERNAME`: `rayhan.shaikh@caprium.com`
- `MAIL_PASSWORD`: `jdkr ssin gpgw csvu`
- `MAIL_ENCRYPTION`: `tls`
- `MAIL_FROM_ADDRESS`: `rayhan.shaikh@caprium.com`

#### Security
- `APP_KEY`: Generate with `php artisan key:generate --show` (or use Render's secret generation)

### 5. Deploy

1. Click "Create Web Service"
2. Render will automatically build and deploy your application
3. Monitor the build logs to ensure everything deploys correctly

## Important Notes

- The application uses IST (Indian Standard Time) for all operations
- Make sure your database is properly configured with the correct timezone
- The email scheduler runs automatically via a separate cron job service defined in render.yaml
- The cron job runs every minute to check for and send scheduled emails

## Troubleshooting

### Common Issues:

1. **Database Connection Issues**:
   - Verify your database credentials are correct
   - Ensure your database is accessible from Render

2. **Email Not Sending**:
   - Check that your email credentials are correct
   - Verify that the Gmail account allows less secure apps or use app passwords

3. **Migration Issues**:
   - Make sure the database exists before running migrations
   - Check that your database user has the necessary permissions

### Checking Application Status:

You can check the status of your application by visiting:
- Your deployed URL
- The Render dashboard logs

## Updating Your Application

When you push changes to your connected GitHub repository, Render will automatically rebuild and redeploy your application.

## Additional Configuration

If you need to run additional commands after deployment (like seeding the database), you can modify the build command to include those steps.