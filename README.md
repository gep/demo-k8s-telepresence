# PHP Kubernetes telepresence demo
Demo PHP kubernetes project

## Installation

You don't have to run `docker-compose up -d`. This is just a emergency fallback in case we need to debug something locally.

### Image and dependencies

* Build the php-fpm image: `docker build -f docker/php-fpm/Dockerfile -t php-fpm .`
* Then install vendors: `docker run --rm -it -e XDEBUG_REMOTE_PORT=9001 -e PHP_IDE_CONFIG="serverName=k8s-demo" -e APP_ENV=dev -v $(pwd):/application php-fpm:latest composer install`
* Build image again to make sure all the dependencies are in the image: `docker build -f docker/php-fpm/Dockerfile -t php-fpm .`

The same steps are actually for every image for mysql and nginx. Dockerfiles are provided in the corresponding folders.

### Publish the images

Actually all the steps are listed here when you are logged in: https://eu-west-2.console.aws.amazon.com/ecr/repositories/php-fpm/?region=eu-west-2

But in case you are not:

1. `$(aws ecr get-login --no-include-email --region eu-west-2)`
2. `docker build -f docker/php-fpm/Dockerfile -t php-fpm .`
3. `docker tag php-fpm:latest [repo_id]/php-fpm:latest`
4. `docker push [repo_id]/php-fpm:latest`

### Kubernetes project configuration

Once you have the access to your namespace and can execute `kubectl` in the project. Please, make sure you use the right namespace by running `kubenc`. Then perform the following steps:

1. Create a secret for DB connection `./dealtrak-kubernetes/bin/create-secret.sh` to create a database secret for php workers
2. Then create mysql volume: `kubectl create -f dealtrak-kubernetes/mysql-volume.yaml`
3. Mysql service: `kubectl create -f dealtrak-kubernetes/mysql-deployment.yaml`
4. PHP fpm deployment: `kubectl create -f dealtrak-kubernetes/php-fpm-deployment.yaml`
5. PHP service: `kubectl create -f dealtrak-kubernetes/php-fpm-service.yaml`
6. Run migrations for the database: `kubectl create -f dealtrak-kubernetes/php-migrations-job.yaml`.
7. Nginx deployment: `kubectl create -f dealtrak-kubernetes/nginx-deployment.yaml`
8. Nginx loadbalancer: `kubectl create -f dealtrak-kubernetes/nginx-loadbalancer.yaml`

Finally you have the backend demo app fully installed.

## Application usage

Access the app from the internet using the loadbalancer address (nginx service):
```bash
$ kubectl get svc
NAME          TYPE           CLUSTER-IP       EXTERNAL-IP                                                               PORT(S)        AGE
mysql         ClusterIP      172.20.136.173   <none>                                                                    3306/TCP       7h32m
nginx         LoadBalancer   172.20.152.126   [random_id]eu-west-2.elb.amazonaws.com   80:31512/TCP   7h27m
php-fpm       ClusterIP      172.20.122.0     <none>                                                                    9000/TCP       7h30m
```

Finally access it using curl for example:

```bash
curl -X GET \
  'http://[random_id]eu-west-2.elb.amazonaws.com/goods' \
  -H 'Cache-Control: no-cache' \
  -H 'Postman-Token: 7dcaa9a2-89d0-4c5a-b3f0-f540288c9a27'
```

Populate 2 random goods in the DB:
```bash
curl -X PUT \
  'http://[random_id]eu-west-2.elb.amazonaws.com/goods/create/2' \
  -H 'Cache-Control: no-cache' \
  -H 'Postman-Token: a38c3d1d-d1af-4890-8fb4-8c8f0c10417e'
```

### Postman collection
[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/abe60f37d86393256e88)

Get goods response example:
```json
{
    "goods": [
         {
            "id": 1,
            "name": "rowdy-antelope",
            "price": "30.54",
            "created_at": "2019-08-21T08:23:57+00:00",
            "updated_at": "2019-08-21T08:23:57+00:00"
        },
        {
            "id": 2,
            "name": "acute-panda",
            "price": "177.07",
            "created_at": "2019-08-21T08:23:57+00:00",
            "updated_at": "2019-08-21T08:23:57+00:00"
        },
        {
            "id": 3,
            "name": "Lost-Vampire-Unleashed",
            "price": "165.19",
            "created_at": "2019-08-21T08:33:11+00:00",
            "updated_at": "2019-08-21T08:33:11+00:00"
        }
    ],
    "path": "src/Controller/GoodsController.php"
}
```
Put 2 goods response example:
```json
{
    "message": "2 goods created"
}
```

## Debugging

### Telepresence

Probably you want to debug the application directly in the cloud using  [Telepresence](https://www.telepresence.io/):

Launch VPN and then run `telepresence --swap-deployment php-fpm --docker-run --rm -it -e XDEBUG_REMOTE_HOST_IP=[IP_ADDRESS] -e XDEBUG_REMOTE_PORT=9001 -e PHP_IDE_CONFIG="serverName=k8s-demo" -e APP_ENV=dev -v $(pwd):/application php-fpm:latest` in project folder where [IP_ADDRESS] is you machine IP in VPN.
