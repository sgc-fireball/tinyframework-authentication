# TinyFramework Authentication

- [Introduction](#introduction)
- [Author](#author)

## Introduction

The Auth plugins allow to control user access and profiles.

## Setup

Outside of docker:

```bash
docker compose up -d
docker exec -it tinyframework-authentication-php-1 bash
```

Insite of docker container:

```bash
composer require sgc-fireball/tinyframework-auth
php console tinyframework:serve
```

## Author

Richard Hülsberg <rh+github@hrdns.de>
