# Layer 7 API Gateway mTLS Certificates Console and DevPortal

This module provides an easy way to access and manage to the Broadcom Layer7 API Gateway mTLS certificates, private keys
and public certificates

With this application you can:
- have a nice developer portal for api gateway users
- have an administrative console for store, generate and check certificates and their expirations

## Interacting with API Gateway

The first step is to add your Layer7 API gateway credentials in the admin panel (/admin).
After that, the crontab can extract all the required information automatically.

### Import from API Gateway

To import users and certificates from the API Gateway

```bash
php artisan gateway:get-users
```

To import trusted certificates

```bash
php artisan gateway:get-trusted-certs
```

To import private keys

```bash
php artisan gateway:get-private-keys
```

To import services from API Gateway

```bash
php artisan gateway:get-services
```

To check the validity of mTLS certificates

```bash
php artisan certificates:check
```

## Default data

The seeders data are:

- user: layer7@admin.com
- password: layer7

## How this program works

To get certificate information we can use the RESTman url on Layer 7, with Basic Auth credentials:

1. Users list

```
GET /restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users
```

2. For each user we can obtain the mTLS certificate

```
GET /restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users/{{USER_ID}}/certificate
```

3. Finally, from the certificate we can get the expiration date with openSSL

```
base64 -d <<< $cert | openssl x509 -inform DER -noout -dates
```

# License

This open source project is licensed under the MIT License
