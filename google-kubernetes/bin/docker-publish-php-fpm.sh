#!/usr/bin/env bash

echo "Building the php-fpm image..."
docker build -f ./docker/php-fpm/Dockerfile -t gcr.io/${PROJECT_ID}/php-fpm .

echo "Publishing the php-fpm image..."
gcloud docker -- push gcr.io/${PROJECT_ID}/php-fpm