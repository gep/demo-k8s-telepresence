#!/usr/bin/env bash

kubectl delete hpa php-fpm

echo "Horizontal autoscaling"
kubectl autoscale deployment php-fpm --max 20 --min 2 --cpu-percent 70