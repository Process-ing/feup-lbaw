#!/bin/bash

# Stop execution if a step fails
set -e

# Replace with your group's image name
IMAGE_NAME=gitlab.up.pt:5050/lbaw/lbaw2425/lbaw2411

# Ensure that dependencies are available
composer install
php artisan config:clear
php artisan clear-compiled
php artisan optimize
npm run build

# docker buildx build --push --platform linux/amd64 -t $IMAGE_NAME .
docker buildx build --push --platform linux/amd64,linux/arm64 -t $IMAGE_NAME .
