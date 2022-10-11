# Console API Gateway Inbound


Certificati API Gateway

Si potrebbero anche estrarre usando il servizio "RESTMan", praticamente facendo l'inverso di quello che fa lo script di caricamento

1. Si tira fuori la lista degli utenti

GET /restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users
(autenticazione Basic)

2. Per ogni utente recuperiamo il certificato

GET /restman/1.0/identityProviders/0000000000000000fffffffffffffffe/users/{{USER_ID}}/certificate
(autenticazione Basic)

3. Dal certificato estraiamo le date di scadenza

base64 -d <<< $cert | openssl x509 -inform DER -noout -dates
