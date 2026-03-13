Deploy script
===============

This folder contains a simple server-side deploy script `deploy.sh`.

How to use (server-side):

1. SSH into your server as the deploy user:

```bash
ssh ubuntu@ip-172-31-23-232
```

2. (Optional) move the script into place and make executable, or run directly from the repo:

```bash
# from project root
chmod +x deploy/deploy.sh
# run with defaults (project path and branch)
./deploy/deploy.sh /var/www/ashastables main

# or specify branch and enable migrations
./deploy/deploy.sh /var/www/ashastables main true
```

What the script does:
- Stashes local changes (if any)
- Fetches and pulls the branch from `origin`
- Runs `composer install --no-dev --prefer-dist --optimize-autoloader`
- (optionally) runs `php artisan migrate --force`
- Runs `php artisan config:cache`, `route:cache`, `view:cache`, `optimize`
- Attempts to restart `php-fpm` and `nginx` if `systemctl` is available

Notes:
- Adjust `php`/`php-fpm` service names in the script to match your environment (php8.1-fpm, php7.4-fpm, etc.)
- Ensure the user running the script has permissions to run `composer`, `php artisan`, and `sudo systemctl restart` for the services, or run as a user with those privileges.
- If your deployment flow uses Docker, CI/CD, or other tools, adapt this script accordingly.
