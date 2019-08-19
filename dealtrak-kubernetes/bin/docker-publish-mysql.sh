#!/usr/bin/env bash


echo "Building the mysql image..."
docker build -f ./docker/mysql/Dockerfile -t gcr.io/${PROJECT_ID}/mysql .

echo "Publishing the mysql image..."
gcloud docker -- push gcr.io/${PROJECT_ID}/mysql