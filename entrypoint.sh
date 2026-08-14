#!/bin/sh

# 1. Run migrations automatically when the app boots up on Render
php artisan migrate --force

# 2. Start the web server (adjust this to match your Docker container command)
php artisan serve --host=0.0.0.0 --port=8000
