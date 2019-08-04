#!/usr/bin/env bash

echo "Building the nginx image..."
docker build -f ./docker/nginx/Dockerfile -t gcr.io/${PROJECT_ID}/nginx .

echo "Publishing the nginx image..."
gcloud docker -- push gcr.io/${PROJECT_ID}/nginx