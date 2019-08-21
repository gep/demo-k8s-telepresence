#!/usr/bin/env bash

echo "Building and publishing php-fpm..."
./kubernetes/bin/docker-publish-php-fpm.sh

echo "Building the php-cli-run-migrations image..."
docker build -f ./docker/php-cli-run-migrations/Dockerfile -t gcr.io/${PROJECT_ID}/php-cli-run-migrations .

echo "Publishing the php-cli-run-migrations image..."
gcloud docker -- push gcr.io/${PROJECT_ID}/php-cli-run-migrations