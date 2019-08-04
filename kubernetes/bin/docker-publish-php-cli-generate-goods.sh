#!/usr/bin/env bash

echo "Building and publishing php-fpm..."
./kubernetes/bin/docker-publish-php-fpm.sh

echo "Building the php-cli-run-generator image..."
docker build -f ./docker/php-cli-run-generator/Dockerfile -t gcr.io/${PROJECT_ID}/php-cli-run-generator .

echo "Publishing the php-cli-run-generator image..."
gcloud docker -- push gcr.io/${PROJECT_ID}/php-cli-run-generator