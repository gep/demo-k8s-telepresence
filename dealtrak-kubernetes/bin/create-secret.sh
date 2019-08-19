#!/usr/bin/env bash

kubectl create secret generic mysql --from-literal url=mysql://k8s-demo:42@mysql:3306/k8s-demo

