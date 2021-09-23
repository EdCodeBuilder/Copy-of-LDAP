---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Información

Bienvenidos a la documentación y referencia de las API del IDRD.
La URL base para realizar las peticiones mostradas a continuación es la siguiente.

[https://sim.idrd.gov.co/base-ldap/public/](https://sim.idrd.gov.co/base-ldap/public/)

[Descarga la colección de Postman](http://sim.idrd.gov.co/base-ldap/public/docs/collection.json)

<!-- END_INFO -->

#Authentication


<!-- START_ba35aa39474cb98cfb31829e70eb8b74 -->
## Login

Handle a login request to the application.

> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/login" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"username":"'jhon.doe'","password":"'C0ntr4s3%$a^'"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/login");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "username": "'jhon.doe'",
    "password": "'C0ntr4s3%$a^'"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (422):

```json
{
    "message": "'These credentials do not match our records or you do not have permission to enter this module.",
    "details": null,
    "code": 422,
    "requested_at": "2021-09-12T16:45:39-05:00"
}
```
> Respuesta de ejemplo (429):

```json
{
    "message": "Too many login attempts. Please try again in 60 seconds.",
    "details": null,
    "code": 429,
    "requested_at": "2021-09-12T16:45:39-05:00"
}
```
> Respuesta de ejemplo (200):

```json
{
    "token_type": "Bearer",
    "expires_in": 28799,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhb....",
    "refresh_token": "def50200d4733b9e9582...."
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST login`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    username | string |  obligatorio  | Usuario de red (LDAP).
    password | string |  obligatorio  | Contraseña de red (LDAP).

<!-- END_ba35aa39474cb98cfb31829e70eb8b74 -->

<!-- START_2b6e5a4b188cb183c7e59558cce36cb6 -->
## User

Return authenticated user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/user" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/user");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "id": 1,
        "guid": "fbad8322-b960-...",
        "full_name": "JHON DOE",
        "name": "JHON",
        "surname": "DOE",
        "document": "12349586960",
        "email": "jhon.doe@idrd.gov.co",
        "username": "jhon.doe",
        "description": "CONTRATISTA",
        "dependency": "AREA SISTEMAS",
        "company": "SEDE PRINCIPAL",
        "phone": "6605400",
        "ext": "363",
        "sim_id": 123,
        "password_expired": false,
        "is_locked": false,
        "vacation_start_date": null,
        "vacation_final_date": null,
        "expires_at": "2022-02-01 00:00:00",
        "created_at": "2020-06-23 21:15:42",
        "updated_at": "2021-07-09 22:34:29",
        "ldap": {
            "objectclass": [
                "top",
                "person",
                "organizationalPerson",
                "user"
            ],
            "buildingname": null,
            "c": null,
            "cn": "Jhon Doe",
            "co": null,
            "comment": null,
            "commonname": null,
            "company": "Sede Principal",
            "description": "Contratista",
            "distinguishedname": "CN=Jhon Doe,OU=OU,OU=OU,OU=IDRD,DC=dc,DC=local",
            "dn": null,
            "department": null,
            "displayname": "Jhon Doe",
            "facsimiletelephonenumber": null,
            "fax": null,
            "friendlycountryname": null,
            "givenname": "Jhon",
            "homephone": null,
            "homepostaladdress": null,
            "info": null,
            "initials": null,
            "ipphone": null,
            "l": null,
            "mail": "jhon.doe@idrd.gov.co",
            "mailnickname": null,
            "rfc822mailbox": null,
            "mobile": null,
            "mobiletelephonenumber": null,
            "name": "Jhon Doe",
            "othertelephone": null,
            "ou": null,
            "pager": null,
            "pagertelephonenumber": null,
            "physicaldeliveryofficename": "Area",
            "postaladdress": null,
            "postalcode": [
                "1234759607"
            ],
            "postofficebox": null,
            "samaccountname": "jhon.doe",
            "serialnumber": null,
            "sn": "Doe",
            "surname": null,
            "st": null,
            "stateorprovincename": null,
            "street": null,
            "streetaddress": null,
            "telephonenumber": 363,
            "title": null,
            "uid": null,
            "url": null,
            "userprincipalname": "jhon.doe@idrd.gov.co",
            "wwwhomepage": null,
            "instancetype": 4,
            "whencreated": "2018-11-26 17:24:44",
            "whenchanged": "2021-09-13 15:31:53",
            "usncreated": 13863901,
            "memberof": [
                "CN=GS - ISAF ZONA 4,OU=OU,OU=GRUPOS,OU=OU,DC=dc,DC=local",
                "CN=CN,OU=OU,OU=OU,DC=dc,DC=local",
                "CN=OU,OU=OU,OU=OU,DC=dc,DC=local"
            ],
            "usnchanged": 84413708,
            "objectguid": "fbad8322-b960-....",
            "useraccountcontrol": 512,
            "badpwdcount": 0,
            "codepage": 0,
            "countrycode": 0,
            "badpasswordtime": "2021-09-17 15:22:25",
            "lastlogoff": "0",
            "lastlogon": "2021-04-06 15:08:28",
            "pwdlastset": "2021-07-09 22:34:32",
            "primarygroupid": 513,
            "objectsid": "S-1-5-21-......",
            "accountexpires": "2022-02-01 00:00:00",
            "logoncount": 1,
            "samaccounttype": 805306368,
            "lockouttime": 0,
            "objectcategory": "CN=cn,CN=cn,CN=cn,DC=dc,DC=local",
            "dscorepropagationdata": [
                "2020-12-30 16:05:50",
                "2020-12-18 16:13:26",
                "2020-04-29 14:10:50",
                "2020-02-27 20:07:34",
                "1601-01-01 00:04:17"
            ],
            "lastlogontimestamp": "2021-09-13 10:31:53",
            "msds-lastsuccessfulinteractivelogontime": "2018-12-04 10:48:20",
            "msds-lastfailedinteractivelogontime": "2018-12-04 13:03:17",
            "msds-failedinteractivelogoncount": 14,
            "msds-failedinteractivelogoncountatlastsuccessfullogon": 12
        },
        "deleted_at": null
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T14:40:34-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:03-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/user`


<!-- END_2b6e5a4b188cb183c7e59558cce36cb6 -->

<!-- START_4718503641f9ab71a92dd1627b31628d -->
## Change Password

Update logged-in user password.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/change-password" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"old_password":"abc2737","password":"MyStrongerPassword(&%\u00b7**","password_confirmed":"MyStrongerPassword(&%\u00b7**"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/change-password");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "old_password": "abc2737",
    "password": "MyStrongerPassword(&%\u00b7**",
    "password_confirmed": "MyStrongerPassword(&%\u00b7**"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Your password has been changed successfully",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-12T16:45:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/change-password`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    old_password | string |  obligatorio  | Contraseña anterior.
    password | string |  obligatorio  | Nueva contraseña.
    password_confirmed | string |  obligatorio  | Confirmación de la nueva contraseña.

<!-- END_4718503641f9ab71a92dd1627b31628d -->

<!-- START_61739f3220a224b34228600649230ad1 -->
## Logout

Log the user out of the application.

> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/logout" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/logout");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Se ha cerrado la sesión correctamente.",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-12T16:45:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/logout`


<!-- END_61739f3220a224b34228600649230ad1 -->

<!-- START_9d0ef6a741425c3d843e379562be332f -->
## Logout All Devices

Log the user out of the application.

> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/logout-all-devices" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/logout-all-devices");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Se ha cerrado la sesión correctamente.",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-12T16:45:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/logout-all-devices`


<!-- END_9d0ef6a741425c3d843e379562be332f -->

#Módulos


<!-- START_2499bcb3883ce06aadca050136327f97 -->
## Módulos del Usuario

Api para la visualización de los módulos asociados al usuario.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/my-modules" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/my-modules");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "NOMBRE DEL MODULO - STRD.",
            "area": "SAF",
            "redirect": "https:\/\/www.idrd.gov.co\/SIM\/...\/",
            "image": "https:\/\/www.idrd.gov.co\/SIM\/icono.jpg",
            "status": true,
            "missionary": false,
            "compatible": false,
            "access": [
                {
                    "id": "24fa65d",
                    "name": "Crear Persona",
                    "description": "Agrega a una persona solo con sus datos caracteristicos",
                    "module_id": 1,
                    "permission": [
                        {
                            "user_id": 1046,
                            "activity_id": 157,
                            "status": true,
                            "status_int": 1,
                            "created_at": "2020-11-16 12:23:23"
                        }
                    ]
                },
                {
                    "id": "57be65d",
                    "...": "..."
                }
            ],
            "encoded": "a%3A68%3A%7Bi...",
            "created_at": "2021-09-12 16:45:34",
            "updated_at": "2021-09-12 16:45:34"
        }
    ],
    "links": {
        "first": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/my-modules?page=1",
        "last": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/my-modules?page=30",
        "prev": null,
        "next": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/my-modules?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 30,
        "path": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/my-modules",
        "per_page": "1",
        "to": 1,
        "total": 30
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T15:15:11-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:03-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/my-modules`


<!-- END_2499bcb3883ce06aadca050136327f97 -->

#Parques


<!-- START_6fc970b22b1d134b189cab8f6eb4dbb4 -->
## Reporte en excel de parques

Genera un reporte en Excel (.xlsx) codificado en Base64 según filtros especificados.

> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/excel" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/excel");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "name": "PARQUES-FA453A-A625A6.xlsx",
        "file": "data:application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,TyayT8y76hh7A6GAJA887..."
    },
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/excel`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_6fc970b22b1d134b189cab8f6eb4dbb4 -->

<!-- START_1a4ff4ed1f82f294609a1eb4f1e56907 -->
## Buscador de parques

Despliega una lista de coincidencias de parques según los criterios de búsqueda

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks?query=03-036" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks");

    let params = {
            "query": "03-036",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 9,
            "code": "03-036",
            "name": "LAS CRUCES",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 3,
            "locality": "SANTA FE",
            "address": "CARRERA 5A #1- 90",
            "upz_code": "95",
            "upz": "LAS CRUCES",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                }
            ]
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/api\/parks?page=1",
        "last": "http:\/\/localhost\/api\/parks?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http:\/\/localhost\/api\/parks",
        "per_page": 10,
        "to": 1,
        "total": 1
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    query |  opcional  | Código, nombre o dirección del parque.
    locality_id |  opcional  | Arreglo de ids o id de la localidad.
    upz_id |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood_id |  opcional  | Arreglo de ids o id del barrio del parque.
    type_id |  opcional  | Arreglo de ids o id de la escala del parque.
    vigilance |  opcional  | Parques que cuentan con vigilancia. Ejemplo: Con vigilancia, Sin vigilancia.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    column |  opcional  | Campo de ordenamiento Ejemplo: ?column[]=name.
    order |  opcional  | Orden de los resultados true para ascendente o false para descendente Ejemplo: ?order[]=true.
    page |  opcional  | La página a retornar Ejemplo: ?page=3.
    per_page |  opcional  | La cantidad de resultados a retornar Ejemplo: ?per_page=58.

<!-- END_1a4ff4ed1f82f294609a1eb4f1e56907 -->

<!-- START_8283810d8f3077f3e1933e2a4457ed4b -->
## Crear Parque

Crea un parque con información específica.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"code":"03-036","name":"LAS CRUCES","address":"CARRERA 5A #1- 90","stratum":2,"locality_id":7,"upz_code":"aliquam","neighborhood_id":10,"urbanization":"PREDIOS P\u00daBLICOS NO CESI\u00d3N","latitude":"4.585764498926","longitude":"-74.0787936235177","area_hectare":1,"area":5,"grey_area":20,"green_area":30,"capacity":2367,"children_population":33,"youth_population":34,"older_population":32,"enclosure":"Total","households":77,"walking_trails":700,"walking_trails_status":"BUENO","access_roads":53,"access_roads_status":"REGULAR","zone_type":"RESIDENCIAL\/COMERCIAL","scale_id":3,"concern":"IDRD","visited_at":"2021-09-17","general_status":"BUENO","stage_type_id":1,"status_id":2,"admin":"Junta de Acci\u00f3n Comunal\/IDRD","phone":"2800004","email":"lascruces@idrd.gov.co","admin_name":"Jhon Doe","vigilance":"Con Vigilancia","received":"Si","vocation_id":2}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "code": "03-036",
    "name": "LAS CRUCES",
    "address": "CARRERA 5A #1- 90",
    "stratum": 2,
    "locality_id": 7,
    "upz_code": "aliquam",
    "neighborhood_id": 10,
    "urbanization": "PREDIOS P\u00daBLICOS NO CESI\u00d3N",
    "latitude": "4.585764498926",
    "longitude": "-74.0787936235177",
    "area_hectare": 1,
    "area": 5,
    "grey_area": 20,
    "green_area": 30,
    "capacity": 2367,
    "children_population": 33,
    "youth_population": 34,
    "older_population": 32,
    "enclosure": "Total",
    "households": 77,
    "walking_trails": 700,
    "walking_trails_status": "BUENO",
    "access_roads": 53,
    "access_roads_status": "REGULAR",
    "zone_type": "RESIDENCIAL\/COMERCIAL",
    "scale_id": 3,
    "concern": "IDRD",
    "visited_at": "2021-09-17",
    "general_status": "BUENO",
    "stage_type_id": 1,
    "status_id": 2,
    "admin": "Junta de Acci\u00f3n Comunal\/IDRD",
    "phone": "2800004",
    "email": "lascruces@idrd.gov.co",
    "admin_name": "Jhon Doe",
    "vigilance": "Con Vigilancia",
    "received": "Si",
    "vocation_id": 2
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    code | string |  obligatorio  | Código del parque.
    name | string |  obligatorio  | Nombre del parque.
    address | string |  obligatorio  | Dirección del parque.
    stratum | integer |  obligatorio  | Estrato del parque.
    locality_id | integer |  obligatorio  | Id de la localidad del parque.
    upz_code | string |  obligatorio  | Código de la UPZ del parque.
    neighborhood_id | integer |  obligatorio  | Id del barrio del parque.
    urbanization | string |  obligatorio  | Nombre de la urbanización del parque.
    latitude | string |  opcional  | Latitud del parque.
    longitude | string |  opcional  | Longitud del parque.
    area_hectare | integer |  opcional  | Área en hectáreas del parque.
    area | integer |  opcional  | Área del parque.
    grey_area | integer |  opcional  | Área zona dura del parque.
    green_area | integer |  opcional  | Área zona verde del parque
    capacity | integer |  opcional  | Capacidad de personas en el parque.
    children_population | integer |  opcional  | Población infantil.
    youth_population | integer |  opcional  | Población juvenil.
    older_population | integer |  opcional  | Población mayor.
    enclosure | string |  opcional  | Tipo de cerramiento del parque.
    households | integer |  opcional  | Cantidad de viviendas
    walking_trails | integer |  opcional  | Cantidad de senderos.
    walking_trails_status | string |  opcional  | Estado de los senderos.
    access_roads | integer |  opcional  | Cantidad de vías.
    access_roads_status | string |  opcional  | Estado de las vías.
    zone_type | string |  opcional  | Tipo de Zona
    scale_id | integer |  opcional  | Id de la escala del parque.
    concern | string |  opcional  | Competencia/Regulación del parque.
    visited_at | date |  opcional  | Fecha de última visita al parque en formato AAAA-MM-DD.
    general_status | string |  opcional  | Estado general del parque.
    stage_type_id | integer |  opcional  | Id del tipo de escenario
    status_id | integer |  opcional  | Id de estado del parque.
    admin | string |  opcional  | Entidad que administra el parque.
    phone | string |  opcional  | Números telefónicos del parque separados por coma, Ejemplo: 2800004, 6605300.
    email | string |  opcional  | Correo electrónico del parque
    admin_name | string |  opcional  | Nombre del administrador del parque.
    vigilance | string |  opcional  | Cuenta o no con vigilancia. Ejemplo: Con vigilancia, Sin vigilancia.
    received | string |  opcional  | El parque es recibido por el IDRD, Ejemplo: Si, No.
    vocation_id | integer |  opcional  | Id de la vocación del parque.

<!-- END_8283810d8f3077f3e1933e2a4457ed4b -->

<!-- START_bb85d7e6c62b027964ebd5e6c6afc9d4 -->
## Ver parque

Muesta información detallada de un parque en específico.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/9" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "id": 9,
        "code": "03-036",
        "name": "LAS CRUCES",
        "phone": 2800004,
        "stratum": 2,
        "image": "",
        "block": "LAS CRUCES",
        "neighborhood_id": 144,
        "area": 5.79,
        "area_hectare": 1.21,
        "general_status": "BUENO",
        "enclosure": "Total",
        "households": 70,
        "zone_type": "",
        "admin": "IDRD",
        "walking_trails": 600,
        "walking_trails_status": "BUENO",
        "access_roads": "SI",
        "access_roads_status": "REGULAR",
        "children_population": 33,
        "youth_population": 34,
        "older_population": 33,
        "population_chart": [
            33,
            34,
            33
        ],
        "admin_name": "EMILSE ARIAS",
        "status_id": 1,
        "status": "BUENO",
        "latitude": 4.585764498926,
        "longitude": -74.0787936235177,
        "urbanization": "PREDIOS PÚBLICOS NO CESIÓN",
        "vigilance": "Con vigilancia",
        "received": "Si",
        "capacity": 2743.17,
        "stage_type_id": null,
        "stage_type": null,
        "pqrs": "atencionalcliente@idrd.gov.co",
        "email": "lascruces@idrd.gov.co",
        "schedule_service": "Lunes a Viernes: 6:00 AM - 6:00 PM \/ Sábados y Domingos: 5:00 AM - 6:00 PM",
        "schedule_admin": "Lunes a Viernes:  8:00 AM A  4:00 PM \/ Sábados y Domingos:  9:00 AM -2:00 PM",
        "scale_id": 3,
        "scale": "ZONAL",
        "locality_id": 3,
        "locality": "SANTA FE",
        "address": "CARRERA 5A #1- 90",
        "upz_code": "95",
        "upz": "LAS CRUCES",
        "concept_id": 1,
        "concept": "CERTIFICADO",
        "file": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Certificado\/03-036.tif",
        "concern": "IDRD",
        "regulation": "IDRD",
        "regulation_file": null,
        "visited_at": "2021-06-26",
        "rupis": [
            {
                "id": 14,
                "name": "1-372",
                "park_id": 9,
                "created_at": null,
                "updated_at": null,
                "deleted_at": null
            }
        ],
        "story": [
            {
                "id": 4,
                "title": "DATO 1",
                "text": "Lorem ipsum dolor sit amet",
                "park_id": null,
                "created_at": "2021-09-16 09:12:11",
                "updated_at": "2021-09-16 09:12:11",
                "deleted_at": null
            }
        ],
        "origin": {
            "id": 9,
            "park_id": 9,
            "paragraph_1": "El parque zonal que cuenta con una extensión de 12 mil metros cuadrados, fue construido en la localidad de San Cristóbal frente a la reconocida iglesia Nuestra Señora de las Cruces, ubicado en un sector de habitantes bogotanos de escasos recursos.  ",
            "paragraph_2": "Debido a la demanda de espacios para vivir, la arquitectura del barrio sufrió cambios durante los siglos XIX y XX, evidenciados hoy en un sector rodeado por la gran presencia de inquilinatos y pequeÃ±os negocios. En la actualidad es posible evidenciar los pequeños talleres que fueron adecuados alrededor del parque para suplir las necesidades básicas de los habitantes del sector, entre los que se encuentra: sastres, zapateros, carpinteros, Latoneros, tapiceros, ebanistas y peluqueros, quienes se han encargado de frecuentar el recinto lúdico, el cual, ha demostrado ser un escenario recreativo y social, sin distinción de clases sociales o condiciones económicas. \t\t\t",
            "image_1": "984c695db7ed427e4200cd890d310815.jpg",
            "image_2": "c171a0bd9cb26982e9b610ca894efa4d.jpg",
            "image_3": "d73e54a039a62c11f78eaa8f7d00af5f.jpg",
            "image_4": "",
            "image_5": "",
            "image_6": "",
            "images": [
                "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/\/storage\/parks\/images\/984c695db7ed427e4200cd890d310815.jpg",
                "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/\/storage\/parks\/images\/c171a0bd9cb26982e9b610ca894efa4d.jpg",
                "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/\/storage\/parks\/images\/d73e54a039a62c11f78eaa8f7d00af5f.jpg"
            ],
            "created_at": "2021-09-16 14:42:49",
            "updated_at": "2021-09-16 14:42:54",
            "deleted_at": null
        },
        "vocation_id": null,
        "vocation": null,
        "color": "success",
        "green_area": 20,
        "grey_area": 30,
        "area_chart": [
            {
                "name": "Total",
                "data": [
                    20,
                    30
                ]
            }
        ],
        "map": "https:\/\/mapas.bogota.gov.co\/?l=436&b=262&show_menu=false&e=-74.57201001759988,4.2906625340901,-73.61070630666201,4.928542831147915,4686&layerFilter=436;ID_PARQUE='03-036'",
        "plans": [],
        "created_at": "2021-09-16 14:53:25",
        "updated_at": "2021-09-16 14:53:22",
        "deleted_at": null,
        "_links": [
            {
                "rel": "self",
                "type": "GET",
                "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
            },
            {
                "rel": "create",
                "type": "POST",
                "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
            },
            {
                "rel": "update",
                "type": "PUT\/PATCH",
                "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
            },
            {
                "rel": "delete",
                "type": "DELETE",
                "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
            }
        ]
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/{park}`


<!-- END_bb85d7e6c62b027964ebd5e6c6afc9d4 -->

<!-- START_a64178f83dca86e6e54595c420c0473c -->
## Actualizar parque.

Actualiza información de un parque en específico

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/parks/9" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"code":"03-036","name":"LAS CRUCES","address":"CARRERA 5A #1- 90","stratum":2,"locality_id":5,"upz_code":"alias","neighborhood_id":15,"urbanization":"PREDIOS P\u00daBLICOS NO CESI\u00d3N","latitude":"4.585764498926","longitude":"-74.0787936235177","area_hectare":1,"area":5,"grey_area":20,"green_area":30,"capacity":2367,"children_population":33,"youth_population":34,"older_population":32,"enclosure":"Total","households":77,"walking_trails":700,"walking_trails_status":"BUENO","access_roads":53,"access_roads_status":"REGULAR","zone_type":"RESIDENCIAL\/COMERCIAL","scale_id":3,"concern":"IDRD","visited_at":"2021-09-17","general_status":"BUENO","stage_type_id":1,"status_id":2,"admin":"Junta de Acci\u00f3n Comunal\/IDRD","phone":"2800004","email":"lascruces@idrd.gov.co","admin_name":"Jhon Doe","vigilance":"Con Vigilancia","received":"Si","vocation_id":2}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "code": "03-036",
    "name": "LAS CRUCES",
    "address": "CARRERA 5A #1- 90",
    "stratum": 2,
    "locality_id": 5,
    "upz_code": "alias",
    "neighborhood_id": 15,
    "urbanization": "PREDIOS P\u00daBLICOS NO CESI\u00d3N",
    "latitude": "4.585764498926",
    "longitude": "-74.0787936235177",
    "area_hectare": 1,
    "area": 5,
    "grey_area": 20,
    "green_area": 30,
    "capacity": 2367,
    "children_population": 33,
    "youth_population": 34,
    "older_population": 32,
    "enclosure": "Total",
    "households": 77,
    "walking_trails": 700,
    "walking_trails_status": "BUENO",
    "access_roads": 53,
    "access_roads_status": "REGULAR",
    "zone_type": "RESIDENCIAL\/COMERCIAL",
    "scale_id": 3,
    "concern": "IDRD",
    "visited_at": "2021-09-17",
    "general_status": "BUENO",
    "stage_type_id": 1,
    "status_id": 2,
    "admin": "Junta de Acci\u00f3n Comunal\/IDRD",
    "phone": "2800004",
    "email": "lascruces@idrd.gov.co",
    "admin_name": "Jhon Doe",
    "vigilance": "Con Vigilancia",
    "received": "Si",
    "vocation_id": 2
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/parks/{park}`

`PATCH api/parks/{park}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    code | string |  obligatorio  | Código del parque.
    name | string |  obligatorio  | Nombre del parque.
    address | string |  obligatorio  | Dirección del parque.
    stratum | integer |  obligatorio  | Estrato del parque.
    locality_id | integer |  obligatorio  | Id de la localidad del parque.
    upz_code | string |  obligatorio  | Código de la UPZ del parque.
    neighborhood_id | integer |  obligatorio  | Id del barrio del parque.
    urbanization | string |  obligatorio  | Nombre de la urbanización del parque.
    latitude | string |  opcional  | Latitud del parque.
    longitude | string |  opcional  | Longitud del parque.
    area_hectare | integer |  opcional  | Área en hectáreas del parque.
    area | integer |  opcional  | Área del parque.
    grey_area | integer |  opcional  | Área zona dura del parque.
    green_area | integer |  opcional  | Área zona verde del parque
    capacity | integer |  opcional  | Capacidad de personas en el parque.
    children_population | integer |  opcional  | Población infantil.
    youth_population | integer |  opcional  | Población juvenil.
    older_population | integer |  opcional  | Población mayor.
    enclosure | string |  opcional  | Tipo de cerramiento del parque.
    households | integer |  opcional  | Cantidad de viviendas
    walking_trails | integer |  opcional  | Cantidad de senderos.
    walking_trails_status | string |  opcional  | Estado de los senderos.
    access_roads | integer |  opcional  | Cantidad de vías.
    access_roads_status | string |  opcional  | Estado de las vías.
    zone_type | string |  opcional  | Tipo de Zona
    scale_id | integer |  opcional  | Id de la escala del parque.
    concern | string |  opcional  | Competencia/Regulación del parque.
    visited_at | date |  opcional  | Fecha de última visita al parque en formato AAAA-MM-DD.
    general_status | string |  opcional  | Estado general del parque.
    stage_type_id | integer |  opcional  | Id del tipo de escenario
    status_id | integer |  opcional  | Id de estado del parque.
    admin | string |  opcional  | Entidad que administra el parque.
    phone | string |  opcional  | Números telefónicos del parque separados por coma, Ejemplo: 2800004, 6605300.
    email | string |  opcional  | Correo electrónico del parque
    admin_name | string |  opcional  | Nombre del administrador del parque.
    vigilance | string |  opcional  | Cuenta o no con vigilancia. Ejemplo: Con vigilancia, Sin vigilancia.
    received | string |  opcional  | El parque es recibido por el IDRD, Ejemplo: Si, No.
    vocation_id | integer |  opcional  | Id de la vocación del parque.

<!-- END_a64178f83dca86e6e54595c420c0473c -->

<!-- START_ba615adcd27be0472f118655b36ba061 -->
## Eliminar parque.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/9" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/{park}`


<!-- END_ba615adcd27be0472f118655b36ba061 -->

#Parques - Aprovechamiento Económico


<!-- START_7d37a2232223ef23eb9335c91e1dd735 -->
## Aprovechamiento Económico

En desarollo. Muestra un listado de los aprovechamientos económicos de un parque especificado.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/economic-use" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/economic-use");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 57,
            "park_id": 9,
            "activity_id": 1,
            "status_id": 3,
            "activity": "RECREATIVAS",
            "description": "Realización de festivales, bazares, fiestas patronales, eventos gastronómicos, concursos, manifestaciones artísticas de carácter musical y actividades circenses.",
            "manager": "INSTITUTO DISTRITAL DE RECREACIÓN Y DEPORTE IDRD"
        },
        {
            "id": 58,
            "park_id": 9,
            "activity_id": 2,
            "status_id": 3,
            "activity": "DEPORTIVAS",
            "description": "Todas aquellas que impliquen el desarrollo de actividades motoras y competitivas. Dentro de esta tipología se encuentran eventos deportivos de carácter masivo como carreras, maratones, o cualquier tipo de competencia en vías y escenarios públicos de la ciudad, así como partidos de fútbol en los espacios",
            "manager": "INSTITUTO DISTRITAL DE RECREACIÓN Y DEPORTE IDRD"
        },
        {
            "id": 59,
            "park_id": 9,
            "activity_id": 3,
            "status_id": 3,
            "activity": "RECREACIÓN PASIVA",
            "description": "Conjunto de actividades contemplativas dirigidas al disfrute escénico y la salud física y mental, para las cuales sólo se requieren equipamientos en proporciones mínimas al escenario natural, de mínimo impacto ambiental y paisajístico, tales como senderos para bicicletas, senderos peatonales, miradores, observatorios de aves y mobiliario",
            "manager": "INSTITUTO DISTRITAL DE RECREACIÓN Y DEPORTE IDRD"
        },
        {
            "id": 60,
            "park_id": 9,
            "activity_id": 4,
            "status_id": 3,
            "activity": "ECOTURISMO",
            "description": "Modalidad turística ambientalmente responsable consistente en viajar o visitar áreas naturales no intervenidas, con el fin de disfrutar, apreciar y estudiar los atractivos naturales de dichas áreas, así como cualquier manifestación que pueda encontrarse ahí, a través de un proceso que promueve la conservación.",
            "manager": "INSTITUTO DISTRITAL DE RECREACIÓN Y DEPORTE IDRD"
        },
        {
            "id": 61,
            "park_id": 9,
            "activity_id": 5,
            "status_id": 3,
            "activity": "EVENTOS PUBLICITARIOS",
            "description": "Es toda aquella actividad temporal realizada en el espacio público, en donde se emplean un conjunto de canales de comunicación destinados a divulgar, informar o llamar la atención del público en el ejercicio de una actividad comercial, industrial, artesanal o profesional, con el fin de promover de forma directa o indirecta la contratación de bienes",
            "manager": "INSTITUTO DISTRITAL DE RECREACIÓN Y DEPORTE IDRD"
        },
        {
            "id": 62,
            "park_id": 9,
            "activity_id": 7,
            "status_id": 3,
            "activity": "ZAERT \r\n(ZONAS DE APROVECHAMIENTO ECONÓMICO REGULADAS TEMPORALES)",
            "description": "Actividades comerciales realizadas por la población de vendedores informales, reguladas por el IPES.",
            "manager": "INSTITUTO PARA LA ECONOMÍA SOCIAL"
        },
        {
            "id": 63,
            "park_id": 9,
            "activity_id": 8,
            "status_id": 3,
            "activity": "FILMACIONES DE OBRAS",
            "description": "Trabajos de filmación de obras audiovisuales que impliquen el uso de elementos del espacio público y que generen restricción al derecho colectivo, por los cerramientos de vías, la ubicación de elementos y vehículos que hacen parte de la logística de la respectiva Grabación.",
            "manager": "IDARTES"
        },
        {
            "id": 65,
            "park_id": 9,
            "activity_id": 6,
            "status_id": 3,
            "activity": "MERCADOS TEMPORALES",
            "description": "Mercados destinados a la comercialización de bienes y servicios que promuevan la competitividad de actividades comerciales de floricultores, fruticultores, artesanos, anticuarios, tecnológicas, libreros, productores de objetos artísticos y literarios.",
            "manager": "SECRETARÍA DISTRITAL DE DESARROLO ECONÓMICO"
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/{park}/economic-use`


<!-- END_7d37a2232223ef23eb9335c91e1dd735 -->

#Parques - Auditoría


<!-- START_b5aeeea2f7ca07261c2403d3ab23e5dd -->
## Auditoría

Muestra la cantidad de parques por escalas.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/audits" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/audits");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 97005,
            "user": "JHON DOE",
            "event": "Actualizado",
            "color": "warning",
            "type": "App\\Modules\\Parks\\src\\Models\\Park",
            "type_trans": "Parques",
            "type_id": 26381,
            "old_values": {
                "AreaZDura": null,
                "AreaZVerde": null,
                "PoblacionInfantil": null,
                "PoblacionJuvenil": null,
                "PoblacionMayor": null,
                "Aforo": null
            },
            "new_values": {
                "AreaZDura": "56",
                "AreaZVerde": "36",
                "PoblacionInfantil": "29",
                "PoblacionJuvenil": "38",
                "PoblacionMayor": "39",
                "Aforo": "2183"
            },
            "url": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/26381",
            "ip": "181-23.126.44",
            "user_agent": "Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/93.0.4577.82 Safari\/537.36",
            "tags": "park",
            "created_at": "2021-09-20 11:22:51",
            "updated_at": "2021-09-20 11:22:51"
        }
    ],
    "links": {
        "first": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/audits?page=1",
        "last": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/audits?page=34",
        "prev": null,
        "next": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/audits?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 34,
        "path": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/audits",
        "per_page": "1",
        "to": 1,
        "total": 34
    },
    "code": 200,
    "details": {
        "headers": [
            {
                "text": "#",
                "value": "id"
            },
            {
                "align": "right",
                "text": "Evento",
                "value": "event"
            },
            {
                "text": "Tipo",
                "value": "type_trans"
            },
            {
                "text": "Usuario",
                "value": "user"
            },
            {
                "text": "IP",
                "value": "ip"
            },
            {
                "text": "Tags",
                "value": "tags"
            },
            {
                "text": "Fecha de Registro",
                "value": "created_at"
            }
        ],
        "expanded": [
            {
                "align": "right",
                "label": "URL",
                "field": "url"
            },
            {
                "align": "right",
                "label": "Navegador",
                "field": "user_agent"
            },
            {
                "align": "right",
                "label": "Nuevos Valores",
                "field": "new_values"
            },
            {
                "align": "right",
                "label": "Valores Anteriores",
                "field": "old_values"
            },
            {
                "label": "Fecha de Registro",
                "field": "created_at"
            },
            {
                "label": "Fecha de Actualización",
                "field": "updated_at"
            }
        ]
    },
    "requested_at": "2021-09-21T15:51:41-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/audits`


<!-- END_b5aeeea2f7ca07261c2403d3ab23e5dd -->

#Parques - Barrios


<!-- START_5a97f3b025f3af36ddb88ab3c821c76a -->
## Barrios

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "CANAIMA",
            "upz_code": "1",
            "neighborhood_code": null,
            "upz_id": 1,
            "locality_id": 1,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "TIBABITA RURAL",
            "upz_code": "1",
            "neighborhood_code": null,
            "upz_id": 1,
            "locality_id": 1,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 3,
            "name": "TORCA I",
            "upz_code": "1",
            "neighborhood_code": null,
            "upz_id": 1,
            "locality_id": 1,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 4,
            "name": "TORCA II",
            "upz_code": "1",
            "neighborhood_code": null,
            "upz_id": 1,
            "locality_id": 1,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/localities/{location}/upz/{upz}/neighborhoods`


<!-- END_5a97f3b025f3af36ddb88ab3c821c76a -->

<!-- START_f34a626ade8ab8e38490b863c466bd5b -->
## Crear Barrio

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"LAS CRUCES","neighborhood_code":"45","upz_code":"98"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "LAS CRUCES",
    "neighborhood_code": "45",
    "upz_code": "98"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/localities/{location}/upz/{upz}/neighborhoods`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del barrio máximo 500 caracteres.
    neighborhood_code | string |  opcional  | Código del barrio, debe ser un valor único.
    upz_code | string |  obligatorio  | Código de la UPZ.

<!-- END_f34a626ade8ab8e38490b863c466bd5b -->

<!-- START_cf66ee68236bdc6269ee0a66d16da883 -->
## Actualizar Barrio

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"LAS CRUCES","neighborhood_code":"45","upz_code":"98"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "LAS CRUCES",
    "neighborhood_code": "45",
    "upz_code": "98"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/localities/{location}/upz/{upz}/neighborhoods/{neighborhood}`

`PATCH api/localities/{location}/upz/{upz}/neighborhoods/{neighborhood}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del barrio máximo 500 caracteres.
    neighborhood_code | string |  opcional  | Código del barrio, debe ser un valor único.
    upz_code | string |  obligatorio  | Código de la UPZ.

<!-- END_cf66ee68236bdc6269ee0a66d16da883 -->

<!-- START_453773fca77a4cafa6f8bde8ee71f841 -->
## Eliminar Barrios

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1/neighborhoods/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/localities/{location}/upz/{upz}/neighborhoods/{neighborhood}`


<!-- END_453773fca77a4cafa6f8bde8ee71f841 -->

#Parques - Canchas Sintéticas


<!-- START_abebfac7394b9b20ede7f174bcdc480a -->
## Canchas Sintéticas

En desarollo. Muestra un listado de las canchas sintéticas

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/synthetic-fields" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/synthetic-fields");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 9449,
            "code": "06-012",
            "name": "URBANIZACIÓN TUNJUELITO",
            "scale_id": 4,
            "scale": "VECINAL",
            "locality_id": 6,
            "locality": "TUNJUELITO",
            "address": "CALLE 58A SUR # 12A- 25",
            "upz_code": "62",
            "upz": "TUNJUELITO",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9449"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9449"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9449"
                }
            ],
            "park_endowment_id": 520,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 11 EN GRAMA SINTETICA, SECTOR CENTRAL."
        },
        {
            "id": 11125,
            "code": "11-069",
            "name": "CASA BLANCA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 11,
            "locality": "SUBA",
            "address": "CARRERA 80 # 145-50",
            "upz_code": "23",
            "upz": "CASA BLANCA SUBA",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/11125"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/11125"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/11125"
                }
            ],
            "park_endowment_id": 574,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 9 EN GRAMA SINTETICA, SECTOR NORTE."
        },
        {
            "id": 10733,
            "code": "10-249",
            "name": "URBANIZACIÓN FLORENCIA I SECTOR",
            "scale_id": 4,
            "scale": "VECINAL",
            "locality_id": 10,
            "locality": "ENGATIVA",
            "address": "CARRERA 87 # 75 A- 21",
            "upz_code": "30",
            "upz": "BOYACÁ REAL",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/10733"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/10733"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/10733"
                }
            ],
            "park_endowment_id": 1936,
            "endowment_id": 1,
            "endowment_description": "FÃºtbol 8"
        },
        {
            "id": 7833,
            "code": "01-031",
            "name": "NUEVA URBANIZACIÓN EL CEDRITO",
            "scale_id": 4,
            "scale": "VECINAL",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "CALLE 145 # 9- 90",
            "upz_code": "13",
            "upz": "LOS CEDROS",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7833"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7833"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7833"
                }
            ],
            "park_endowment_id": 2405,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 8 EN GRAMA SINTETICA, SECTOR CENTRAL."
        },
        {
            "id": 7880,
            "code": "01-079",
            "name": "URBANIZACIÓN EL TOBERÍN",
            "scale_id": 4,
            "scale": "VECINAL",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "CARRERA 16 C # 164 79",
            "upz_code": "12",
            "upz": "TOBERÍN",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7880"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7880"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7880"
                }
            ],
            "park_endowment_id": 2568,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 11 EN GRAMA SINTETICA, SECTOR CENTRAL."
        },
        {
            "id": 7961,
            "code": "01-189",
            "name": "DESARROLLO ESTRELLA DEL NORTE",
            "scale_id": 4,
            "scale": "VECINAL",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "CALLE 159 A # 19B- 60",
            "upz_code": "12",
            "upz": "TOBERÍN",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7961"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7961"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7961"
                }
            ],
            "park_endowment_id": 2845,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 5 EN GRAMA SINTETICA, SECTOR OCCIDENTAL."
        },
        {
            "id": 8520,
            "code": "04-038",
            "name": "URBANIZACIÓN ANTIOQUIA",
            "scale_id": 4,
            "scale": "VECINAL",
            "locality_id": 4,
            "locality": "SAN CRISTÓBAL",
            "address": "CARRERA 6 B ESTE # 48 C- 17 SUR",
            "upz_code": "51",
            "upz": "LOS LIBERTADORES",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8520"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8520"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8520"
                }
            ],
            "park_endowment_id": 3299,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 5 EN GRAMA SINTETICA, SECTOR CENTRAL."
        },
        {
            "id": 8583,
            "code": "04-122",
            "name": "LA VICTORIA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 4,
            "locality": "SAN CRISTÓBAL",
            "address": "CALLE 37A BIS SUR # 2A- 04",
            "upz_code": "50",
            "upz": "LA GLORIA",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8583"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8583"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8583"
                }
            ],
            "park_endowment_id": 3433,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 5 NUMERO 1 EN GRAMA SINTETICA, SECTOR CENTRAL."
        },
        {
            "id": 8583,
            "code": "04-122",
            "name": "LA VICTORIA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 4,
            "locality": "SAN CRISTÓBAL",
            "address": "CALLE 37A BIS SUR # 2A- 04",
            "upz_code": "50",
            "upz": "LA GLORIA",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8583"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8583"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8583"
                }
            ],
            "park_endowment_id": 3434,
            "endowment_id": 1,
            "endowment_description": "CANCHA DE Futbol 5 NUMERO 2 EN GRAMA SINTETICA, SECTOR CENTRAL."
        },
        {
            "id": 9660,
            "code": "07-292",
            "name": "URBANIZACIÓN CHICALÁ LOTE A",
            "scale_id": 4,
            "scale": "VECINAL",
            "locality_id": 7,
            "locality": "BOSA",
            "address": "CALLE 55 SUR # 86 A -22",
            "upz_code": "84",
            "upz": "BOSA OCCIDENTAL",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9660"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9660"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9660"
                }
            ],
            "park_endowment_id": 4330,
            "endowment_id": 1,
            "endowment_description": "Cancha Futbol 8"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/api\/parks\/synthetic-fields?page=1",
        "last": "http:\/\/localhost\/api\/parks\/synthetic-fields?page=13",
        "prev": null,
        "next": "http:\/\/localhost\/api\/parks\/synthetic-fields?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 13,
        "path": "http:\/\/localhost\/api\/parks\/synthetic-fields",
        "per_page": 10,
        "to": 10,
        "total": 130
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/synthetic-fields`


<!-- END_abebfac7394b9b20ede7f174bcdc480a -->

#Parques - Cerramientos


<!-- START_843dd3e00757698a7cd63f6a80d28e98 -->
## Cerramientos

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/enclosures" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/enclosures");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "Total",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "Parcial",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 3,
            "name": "Ninguna",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 4,
            "name": "Hola IIII",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 5,
            "name": "CERRADO",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/enclosures`


<!-- END_843dd3e00757698a7cd63f6a80d28e98 -->

<!-- START_4311f8df7e9c281a269d07576eddc4f3 -->
## Crear Cerramientos

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/enclosures" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Total"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/enclosures");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "Total"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/enclosures`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del tipo de cerramiento, máximo 30 caracteres.

<!-- END_4311f8df7e9c281a269d07576eddc4f3 -->

<!-- START_a4f267272aa7843c7664f8b8ab718072 -->
## Actualizar Cerramientos

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/enclosures/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Total"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/enclosures/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "Total"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/enclosures/{enclosure}`

`PATCH api/enclosures/{enclosure}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del tipo de cerramiento, máximo 30 caracteres.

<!-- END_a4f267272aa7843c7664f8b8ab718072 -->

<!-- START_98b5436ab1ac250ab3e0c65e1a00dc82 -->
## Eliminar Cerramientos

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/enclosures/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/enclosures/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/enclosures/{enclosure}`


<!-- END_98b5436ab1ac250ab3e0c65e1a00dc82 -->

#Parques - Datos de interés


<!-- START_a1f07829a2f5fe4944b0b97bcf94da00 -->
## Datos de interés

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 4,
            "title": "DATO 1",
            "text": "Lorem ipsum dolor sit amet",
            "park_id": null,
            "created_at": "2021-09-16 09:12:11",
            "updated_at": "2021-09-16 09:12:11",
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/{park}/stories`


<!-- END_a1f07829a2f5fe4944b0b97bcf94da00 -->

<!-- START_909cc1d1f859b95299d6bdc810720dea -->
## Crear Datos de interés

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"title":"CONDICIONES DE USO","text":"voluptas"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "title": "CONDICIONES DE USO",
    "text": "voluptas"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/{park}/stories`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    title | string |  obligatorio  | Título del tado de interés con máximo 191 caracteres.
    text | string |  obligatorio  | Texto descriptivo con máximo 2500 caracteres.

<!-- END_909cc1d1f859b95299d6bdc810720dea -->

<!-- START_0b25d3cc39f6c5cb90340da61ab63fd1 -->
## Actualizar Datos de interés

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"title":"CONDICIONES DE USO","text":"ut"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "title": "CONDICIONES DE USO",
    "text": "ut"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/parks/{park}/stories/{story}`

`PATCH api/parks/{park}/stories/{story}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    title | string |  obligatorio  | Título del tado de interés con máximo 191 caracteres.
    text | string |  obligatorio  | Texto descriptivo con máximo 2500 caracteres.

<!-- END_0b25d3cc39f6c5cb90340da61ab63fd1 -->

<!-- START_e6f067ad33af30056cf8c575d1befca8 -->
## Eliminar Datos de interés

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/stories/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/{park}/stories/{story}`


<!-- END_e6f067ad33af30056cf8c575d1befca8 -->

#Parques - Diagramas/Renders


<!-- START_efdace03f3d7de637a50daf2dfbb8c9e -->
## Diagramas/Renders

En desarollo. Muestra un listado de los parques que cuentan con diagramas.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/diagrams" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/diagrams");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 9,
            "code": "03-036",
            "name": "LAS CRUCES",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 3,
            "locality": "SANTA FE",
            "address": "CARRERA 5A #1- 90",
            "upz_code": "95",
            "upz": "LAS CRUCES",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                }
            ]
        },
        {
            "id": 7817,
            "code": "01-012",
            "name": "LA VIDA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "CARRERA 14 A # 157-98",
            "upz_code": "12",
            "upz": "TOBERÍN",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7817"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7817"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7817"
                }
            ]
        },
        {
            "id": 7825,
            "code": "01-023",
            "name": "SERVITA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "CALLE 165 # 7-76",
            "upz_code": "10",
            "upz": "LA URIBE",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7825"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7825"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7825"
                }
            ]
        },
        {
            "id": 7865,
            "code": "01-064",
            "name": "NUEVA AUTOPISTA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "CARRERA 20 # 136- 91",
            "upz_code": "13",
            "upz": "LOS CEDROS",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7865"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7865"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7865"
                }
            ]
        },
        {
            "id": 7876,
            "code": "01-075",
            "name": "ALTABLANCA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "CARRERA 8 A # 158- 04",
            "upz_code": "11",
            "upz": "SAN CRISTÓBAL NORTE",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7876"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7876"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7876"
                }
            ]
        },
        {
            "id": 7901,
            "code": "01-1000",
            "name": "EL COUNTRY",
            "scale_id": 2,
            "scale": "METROPOLITANO",
            "locality_id": 1,
            "locality": "USAQUÉN",
            "address": "AVENIDA CALLE 127 # 11 D -90",
            "upz_code": "15",
            "upz": "COUNTRY CLUB",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7901"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7901"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/7901"
                }
            ]
        },
        {
            "id": 8262,
            "code": "02-019",
            "name": "SUCRE O HIPPIES",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 2,
            "locality": "CHAPINERO",
            "address": "CALLE 60 # 7-49",
            "upz_code": "99",
            "upz": "CHAPINERO",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8262"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8262"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8262"
                }
            ]
        },
        {
            "id": 8412,
            "code": "03-014",
            "name": "LOS LACHES LA MINA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 3,
            "locality": "SANTA FE",
            "address": "CARRERA 8 ESTE # 4B- 55",
            "upz_code": "96",
            "upz": "LOURDES",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8412"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8412"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8412"
                }
            ]
        },
        {
            "id": 8449,
            "code": "03-093",
            "name": "PLAZA DE TOROS",
            "scale_id": 6,
            "scale": "GRAN ESCENARIO",
            "locality_id": 3,
            "locality": "SANTA FE",
            "address": "CARRERA 5 # 26B- 72",
            "upz_code": "91",
            "upz": "SAGRADO CORAZÓN",
            "color": "grey",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8449"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8449"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8449"
                }
            ]
        },
        {
            "id": 8501,
            "code": "04-013",
            "name": "MORALBA",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 4,
            "locality": "SAN CRISTÓBAL",
            "address": "CARRERA 16 B ESTE # 42 C -55 SUR",
            "upz_code": "50",
            "upz": "LA GLORIA",
            "color": "success",
            "status_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8501"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8501"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/8501"
                }
            ]
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/api\/parks\/diagrams?page=1",
        "last": "http:\/\/localhost\/api\/parks\/diagrams?page=6",
        "prev": null,
        "next": "http:\/\/localhost\/api\/parks\/diagrams?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 6,
        "path": "http:\/\/localhost\/api\/parks\/diagrams",
        "per_page": 10,
        "to": 10,
        "total": 57
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/diagrams`


<!-- END_efdace03f3d7de637a50daf2dfbb8c9e -->

#Parques - Dotaciones


<!-- START_b4d0d90997561b07c88a4bc4d277bbfd -->
## Dotaciones

En desarrollo. Muestra el listado de docationes de un parque especificado y un equipamiento especificado.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/equipment/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/equipment/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 14827,
            "park_id": 9,
            "endowment_id": 1,
            "endowment_num": null,
            "endowment": "FÚTBOL",
            "status_id": 2,
            "status": "REGULAR",
            "material": null,
            "illumination": "SI",
            "economic_use": "SI",
            "area": 1111,
            "floor_material_id": 19,
            "floor_material": "GRAMA SINTETICA",
            "equipment_id": 1,
            "equipment": "CANCHAS DEPORTIVAS",
            "enclosure_id": 1,
            "enclosure": "TOTAL",
            "dressing_room": "NO",
            "light": null,
            "water": null,
            "gas": "",
            "capacity": 0,
            "lane": 0,
            "bath": 0,
            "sanitary_battery": 0,
            "description": "CANCHA DE Futbol 5 EN GRAMA SINTETICA, SECTOR CENTRAL.",
            "maintenance_diagnosis": "",
            "construction_diagnosis": "",
            "positioning": "",
            "destination": "",
            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/34e82ff8e70006afe0512071cb652f3a.jpg",
            "date": "2017-04-03",
            "enclosure_type": "SOLO METALICO (CONTARIMPACTO, MALLA ESLABONADA)",
            "enclosure_height": "5 MTRS",
            "long": 1144,
            "width": 1,
            "covered": "",
            "dunt": 0,
            "male_bath": 0,
            "female_bath": 0,
            "disabled_bath": 0,
            "car_parking": 0,
            "bike_parking": 0,
            "public": 0,
            "sector_id": 14,
            "map": "1855,870,2013,925"
        },
        {
            "id": 14828,
            "park_id": 9,
            "endowment_id": 91,
            "endowment_num": null,
            "endowment": "MICROFUTBOL",
            "status_id": 1,
            "status": "BUENO",
            "material": null,
            "illumination": "SI",
            "economic_use": "SI",
            "area": 582,
            "floor_material_id": 15,
            "floor_material": "ASFALTO SIN SINTETICO",
            "equipment_id": 1,
            "equipment": "CANCHAS DEPORTIVAS",
            "enclosure_id": 3,
            "enclosure": "NINGUNA",
            "dressing_room": "NO",
            "light": null,
            "water": null,
            "gas": "",
            "capacity": 0,
            "lane": 0,
            "bath": 0,
            "sanitary_battery": 0,
            "description": "CANCHA DE MICROFUTBOL- COSTADO SURORIENTAL ",
            "maintenance_diagnosis": "",
            "construction_diagnosis": "",
            "positioning": "",
            "destination": "",
            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/37b960e2bf70ec22ec92cdd686bfdef3.jpg",
            "date": "2017-04-03",
            "enclosure_type": "NINGUNO",
            "enclosure_height": "NINGUNO",
            "long": 480,
            "width": 1,
            "covered": "",
            "dunt": 0,
            "male_bath": 0,
            "female_bath": 0,
            "disabled_bath": 0,
            "car_parking": 0,
            "bike_parking": 0,
            "public": 0,
            "sector_id": 14,
            "map": "1535,1259,1693,1314"
        },
        {
            "id": 14829,
            "park_id": 9,
            "endowment_id": 4,
            "endowment_num": null,
            "endowment": "BALONCESTO",
            "status_id": 3,
            "status": "MALO",
            "material": null,
            "illumination": "SI",
            "economic_use": "SI",
            "area": 492,
            "floor_material_id": 15,
            "floor_material": "ASFALTO SIN SINTETICO",
            "equipment_id": 1,
            "equipment": "CANCHAS DEPORTIVAS",
            "enclosure_id": 3,
            "enclosure": "NINGUNA",
            "dressing_room": "NO",
            "light": null,
            "water": null,
            "gas": "",
            "capacity": 0,
            "lane": 0,
            "bath": 0,
            "sanitary_battery": 0,
            "description": "CANCHA DE BALONCESTO COSTADO SURORIENTAL",
            "maintenance_diagnosis": "",
            "construction_diagnosis": "",
            "positioning": "",
            "destination": "",
            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/271b6cf96c3912aa27c414075bf3650d.jpg",
            "date": "2017-04-03",
            "enclosure_type": "NINGUNO",
            "enclosure_height": "NINGUNO",
            "long": 420,
            "width": 1,
            "covered": "",
            "dunt": 0,
            "male_bath": 0,
            "female_bath": 0,
            "disabled_bath": 0,
            "car_parking": 0,
            "bike_parking": 0,
            "public": 0,
            "sector_id": 14,
            "map": "1235,1144,1395,1199"
        },
        {
            "id": 14830,
            "park_id": 9,
            "endowment_id": 90,
            "endowment_num": null,
            "endowment": "VOLEIBOL",
            "status_id": 3,
            "status": "MALO",
            "material": null,
            "illumination": "SI",
            "economic_use": "SI",
            "area": 200,
            "floor_material_id": 15,
            "floor_material": "ASFALTO SIN SINTETICO",
            "equipment_id": 1,
            "equipment": "CANCHAS DEPORTIVAS",
            "enclosure_id": 3,
            "enclosure": "NINGUNA",
            "dressing_room": "NO",
            "light": null,
            "water": null,
            "gas": "",
            "capacity": 0,
            "lane": 0,
            "bath": 0,
            "sanitary_battery": 0,
            "description": "CANCHA DE VOLEIBOL COSTADO SURORIENTAL",
            "maintenance_diagnosis": "",
            "construction_diagnosis": "",
            "positioning": "",
            "destination": "",
            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/90bc069e44af94e628c4a09879483921.jpg",
            "date": "2017-04-03",
            "enclosure_type": "NINGUNO",
            "enclosure_height": "NINGUNO",
            "long": 160,
            "width": 1,
            "covered": "",
            "dunt": 0,
            "male_bath": 0,
            "female_bath": 0,
            "disabled_bath": 0,
            "car_parking": 0,
            "bike_parking": 0,
            "public": 0,
            "sector_id": 14,
            "map": "1109,983,1267,1038"
        },
        {
            "id": 15910,
            "park_id": 9,
            "endowment_id": 92,
            "endowment_num": null,
            "endowment": "CANCHA MULTIPLE",
            "status_id": 2,
            "status": "REGULAR",
            "material": null,
            "illumination": "SI",
            "economic_use": "SI",
            "area": 94,
            "floor_material_id": 7,
            "floor_material": "ASFALTO CON SINTETICO",
            "equipment_id": 1,
            "equipment": "CANCHAS DEPORTIVAS",
            "enclosure_id": 1,
            "enclosure": "TOTAL",
            "dressing_room": "SI",
            "light": null,
            "water": null,
            "gas": "",
            "capacity": 0,
            "lane": 0,
            "bath": 0,
            "sanitary_battery": 0,
            "description": "CANCHA MULTIPLE DE COLISEO ",
            "maintenance_diagnosis": "",
            "construction_diagnosis": "",
            "positioning": "",
            "destination": "",
            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/ff3e554748fb9f278f87c137a43539dc.jpg",
            "date": "2021-07-05",
            "enclosure_type": "MIXTO (MAMPOSTERIA Y METALICO EN MALLA ESLABONADA)",
            "enclosure_height": "5 MTRS",
            "long": 0,
            "width": 0,
            "covered": "",
            "dunt": 0,
            "male_bath": 0,
            "female_bath": 0,
            "disabled_bath": 0,
            "car_parking": 0,
            "bike_parking": 0,
            "public": 0,
            "sector_id": null,
            "map": null
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/api\/parks\/9\/equipment\/1?page=1",
        "last": "http:\/\/localhost\/api\/parks\/9\/equipment\/1?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http:\/\/localhost\/api\/parks\/9\/equipment\/1",
        "per_page": 10,
        "to": 5,
        "total": 5
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:48-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/{park}/equipment/{equipment}`


<!-- END_b4d0d90997561b07c88a4bc4d277bbfd -->

#Parques - Equipamiento


<!-- START_0b2bbc971c0e542e604cb1a362e4bd2f -->
## Equipamiento

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/equipments" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/equipments");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "CANCHAS DEPORTIVAS"
        },
        {
            "id": 2,
            "name": "EQUIPAMIENTO"
        },
        {
            "id": 3,
            "name": "ESCENARIO DEPORTIVO"
        },
        {
            "id": 4,
            "name": "JUEGOS INFANTILES"
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/equipments`


<!-- END_0b2bbc971c0e542e604cb1a362e4bd2f -->

#Parques - Escalas


<!-- START_ec33cf6c1f795cbd69e1b506cb12208d -->
## Escalas

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/scales" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/scales");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "REGIONAL",
            "description": "Son espacios naturales de gran dimensión",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "METROPOLITANO",
            "description": "Son áreas libres que cubren una superfic",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 3,
            "name": "ZONAL",
            "description": "Son áreas libres, con una dimensión entr",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 4,
            "name": "VECINAL",
            "description": "Son áreas libres, destinadas a la recrea",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 5,
            "name": "BOLSILLO",
            "description": "Son áreas libres con una modal",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 6,
            "name": "GRAN ESCENARIO",
            "description": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 7,
            "name": "METROPOLITANO PROPUESTO",
            "description": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 8,
            "name": "ZONAL PROPUESTO",
            "description": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/scales`


<!-- END_ec33cf6c1f795cbd69e1b506cb12208d -->

<!-- START_d2ec2d77b8971418e4e1f31d855b6979 -->
## Crear Escalas

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/scales" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"ZONAL","description":"Lorem ipsum dolor sit amet."}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/scales");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "ZONAL",
    "description": "Lorem ipsum dolor sit amet."
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/scales`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre de la escala del parque con máximo 50 caracteres.
    description | string |  obligatorio  | Descripción de la escala del parque con máximo 5000 caracteres.

<!-- END_d2ec2d77b8971418e4e1f31d855b6979 -->

<!-- START_baada54bec798a31b4039a67c5144b87 -->
## Actualizar Escalas

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/scales/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"ZONAL","description":"Lorem ipsum dolor sit amet."}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/scales/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "ZONAL",
    "description": "Lorem ipsum dolor sit amet."
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/scales/{scale}`

`PATCH api/scales/{scale}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre de la escala del parque con máximo 50 caracteres.
    description | string |  obligatorio  | Descripción de la escala del parque con máximo 5000 caracteres.

<!-- END_baada54bec798a31b4039a67c5144b87 -->

<!-- START_a796b90c884e558441db6842fceca798 -->
## Eliminar Escalas

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/scales/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/scales/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/scales/{scale}`


<!-- END_a796b90c884e558441db6842fceca798 -->

#Parques - Estado de Certificación


<!-- START_ae69354bc9ac666854eed530c037d6fe -->
## Estado de Certificación

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/certificate-status" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/certificate-status");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "Certificado",
            "park_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "Investigado",
            "park_id": null,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/certificate-status`


<!-- END_ae69354bc9ac666854eed530c037d6fe -->

<!-- START_ce7e98007a6132f4d75550cbf3ccbd5d -->
## Crear Estado de Certificación

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/certificate-status" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Investigado"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/certificate-status");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "Investigado"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/certificate-status`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del estado de certificación, máximo 30 caracteres.

<!-- END_ce7e98007a6132f4d75550cbf3ccbd5d -->

<!-- START_e8a287821b2b14da07dc337929b1ddc1 -->
## Actualizar Estado de Certificación

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/certificate-status/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"Investigado"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/certificate-status/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "Investigado"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/certificate-status/{certified}`

`PATCH api/certificate-status/{certified}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del estado de certificación, máximo 30 caracteres.

<!-- END_e8a287821b2b14da07dc337929b1ddc1 -->

<!-- START_a0ec56cdcd359a9bd84ce5b6e9b221a2 -->
## Eliminar Estado de Certificación

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/certificate-status/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/certificate-status/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/certificate-status/{certified}`


<!-- END_a0ec56cdcd359a9bd84ce5b6e9b221a2 -->

#Parques - Estados


<!-- START_ca348684a41179df2404a6cc46f1aac9 -->
## Estados

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/statuses" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/statuses");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "BUENO",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "REGULAR",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 3,
            "name": "MALO",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/statuses`


<!-- END_ca348684a41179df2404a6cc46f1aac9 -->

<!-- START_37a1a6977116228d02cf6c1b0dc4d30a -->
## Crear Estados

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/statuses" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"BUENO"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/statuses");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "BUENO"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/statuses`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del estado, máximo 30 caracteres.

<!-- END_37a1a6977116228d02cf6c1b0dc4d30a -->

<!-- START_7ec160d0b473565d5fc7ca92ba0817b1 -->
## Actualizar Estados

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/statuses/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"BUENO"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/statuses/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "BUENO"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/statuses/{status}`

`PATCH api/statuses/{status}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del estado, máximo 30 caracteres.

<!-- END_7ec160d0b473565d5fc7ca92ba0817b1 -->

<!-- START_027486ded99341ef4438c3a6a929c7c7 -->
## Eliminar Estados

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/statuses/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/statuses/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/statuses/{status}`


<!-- END_027486ded99341ef4438c3a6a929c7c7 -->

#Parques - Estadísticas


<!-- START_273d5de04cab523fbbe413d368f5677c -->
## Escalas

Muestra la cantidad de parques por escalas.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/stats" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/stats");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "REGIONAL",
            "parks_count": 2
        },
        {
            "id": 2,
            "name": "METROPOLITANO",
            "parks_count": 35
        },
        {
            "id": 3,
            "name": "ZONAL",
            "parks_count": 92
        },
        {
            "id": 4,
            "name": "VECINAL",
            "parks_count": 3731
        },
        {
            "id": 5,
            "name": "BOLSILLO",
            "parks_count": 1806
        },
        {
            "id": 6,
            "name": "GRAN ESCENARIO",
            "parks_count": 14
        },
        {
            "id": 7,
            "name": "METROPOLITANO PROPUESTO",
            "parks_count": 0
        },
        {
            "id": 8,
            "name": "ZONAL PROPUESTO",
            "parks_count": 1
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/stats`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_273d5de04cab523fbbe413d368f5677c -->

<!-- START_a6925b3cb8acdcc1096f28187b356e59 -->
## Administración

Muestra la cantidad de parques totales, administrados por el IDRD y no administrados por el IDRD.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/count" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/count");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "total": 5820,
        "admin": 195,
        "not_admin": 5624
    },
    "details": null,
    "code": 200,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/stats/count`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_a6925b3cb8acdcc1096f28187b356e59 -->

<!-- START_f4ac5000fe435d7b1f71bd40c3374142 -->
## Cerramientos

Muestra la cantidad de parques por tipo de cerramiento.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/enclosure" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/enclosure");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "name": "TOTAL",
            "parks_count": 739
        },
        {
            "name": "POR VALIDAR",
            "parks_count": 2149
        },
        {
            "name": "SIN CERRAMIENTO",
            "parks_count": 2294
        },
        {
            "name": "PARCIAL",
            "parks_count": 554
        },
        {
            "name": "SOLO EN CANCHA",
            "parks_count": 76
        },
        {
            "name": "NINGUNO\nNINGUNO",
            "parks_count": 5
        },
        {
            "name": "RESIDENCIAL",
            "parks_count": 1
        },
        {
            "name": "REGULAR",
            "parks_count": 2
        }
    ],
    "details": null,
    "code": 200,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/stats/enclosure`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_f4ac5000fe435d7b1f71bd40c3374142 -->

<!-- START_31e6af8b78e4cc265739b3f72d916774 -->
## Certificados

Muestra el porcentaje de parques certificados.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/certified" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/certified");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "name": "Parques Certificados",
        "value": 4902,
        "percent": 84.23
    },
    "details": null,
    "code": 200,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/stats/certified`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_31e6af8b78e4cc265739b3f72d916774 -->

<!-- START_ee90756ba55c7b4f4264263079c9ca7c -->
## Localidades

Muestra la cantidad de parques por localidades.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/localities" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/localities");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": "1",
            "name": "USAQUÉN",
            "parksCount": 489
        },
        {
            "id": "2",
            "name": "CHAPINERO",
            "parksCount": 163
        },
        {
            "id": "3",
            "name": "SANTA FE",
            "parksCount": 95
        },
        {
            "id": "4",
            "name": "SAN CRISTÓBAL",
            "parksCount": 307
        },
        {
            "id": "5",
            "name": "USME",
            "parksCount": 317
        },
        {
            "id": "6",
            "name": "TUNJUELITO",
            "parksCount": 57
        },
        {
            "id": "7",
            "name": "BOSA",
            "parksCount": 271
        },
        {
            "id": "8",
            "name": "KENNEDY",
            "parksCount": 577
        },
        {
            "id": "9",
            "name": "FONTIBÓN",
            "parksCount": 279
        },
        {
            "id": "10",
            "name": "ENGATIVA",
            "parksCount": 582
        },
        {
            "id": "11",
            "name": "SUBA",
            "parksCount": 968
        },
        {
            "id": "12",
            "name": "BARRIOS UNIDOS",
            "parksCount": 131
        },
        {
            "id": "13",
            "name": "TEUSAQUILLO",
            "parksCount": 155
        },
        {
            "id": "14",
            "name": "LOS MÁRTIRES",
            "parksCount": 50
        },
        {
            "id": "15",
            "name": "ANTONIO NARIÑO",
            "parksCount": 60
        },
        {
            "id": "16",
            "name": "PUENTE ARANDA",
            "parksCount": 298
        },
        {
            "id": "17",
            "name": "LA CANDELARIA",
            "parksCount": 13
        },
        {
            "id": "18",
            "name": "RAFAEL URIBE URIBE",
            "parksCount": 310
        },
        {
            "id": "19",
            "name": "CIUDAD BOLÍVAR",
            "parksCount": 568
        },
        {
            "id": "20",
            "name": "SUMAPAZ",
            "parksCount": 126
        },
        {
            "id": "21",
            "name": "DISTRITAL",
            "parksCount": 1
        },
        {
            "id": "22",
            "name": "OTRO MUNICIPIO O CIUDAD",
            "parksCount": 0
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/stats/localities`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_ee90756ba55c7b4f4264263079c9ca7c -->

<!-- START_3a4acbf79cfaf80b936039213950918c -->
## UPZ

Muestra la cantidad de parques por UPZ.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/upz" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/upz");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "name": "LAS CRUCES",
            "code": "95",
            "parks_count": 6
        },
        {
            "name": "VERBENAL",
            "code": "9",
            "parks_count": 57
        },
        {
            "name": "SAN CRISTÓBAL NORTE",
            "code": "11",
            "parks_count": 44
        },
        {
            "name": "TOBERÍN",
            "code": "12",
            "parks_count": 35
        },
        {
            "name": "LOS CEDROS",
            "code": "13",
            "parks_count": 82
        },
        {
            "name": "SANTA BÁRBARA",
            "code": "16",
            "parks_count": 60
        },
        {
            "name": "COUNTRY CLUB",
            "code": "15",
            "parks_count": 45
        },
        {
            "name": "LA URIBE",
            "code": "10",
            "parks_count": 21
        },
        {
            "name": "USAQUÉN",
            "code": "14",
            "parks_count": 57
        },
        {
            "name": "SIN UPZ",
            "code": null,
            "parks_count": 862
        },
        {
            "name": "PASEO DE LOS LIBERTADORES",
            "code": "1",
            "parks_count": 5
        },
        {
            "name": "SIN UPZ",
            "code": "119",
            "parks_count": 11
        },
        {
            "name": "LA ACADEMIA",
            "code": "2",
            "parks_count": 8
        },
        {
            "name": "PARDO RUBIO",
            "code": "90",
            "parks_count": 54
        },
        {
            "name": "CHICÓ LAGO",
            "code": "97",
            "parks_count": 44
        },
        {
            "name": "CHAPINERO",
            "code": "99",
            "parks_count": 11
        },
        {
            "name": "EL REFUGIO",
            "code": "88",
            "parks_count": 34
        },
        {
            "name": "LOURDES",
            "code": "96",
            "parks_count": 48
        },
        {
            "name": "LA MACARENA",
            "code": "92",
            "parks_count": 13
        },
        {
            "name": "SAGRADO CORAZÓN",
            "code": "91",
            "parks_count": 12
        },
        {
            "name": "LAS NIEVES",
            "code": "93",
            "parks_count": 4
        },
        {
            "name": "LOS LIBERTADORES",
            "code": "51",
            "parks_count": 49
        },
        {
            "name": "20 DE JULIO",
            "code": "34",
            "parks_count": 41
        },
        {
            "name": "SOSIEGO",
            "code": "33",
            "parks_count": 35
        },
        {
            "name": "SAN BLAS",
            "code": "32",
            "parks_count": 55
        },
        {
            "name": "LA GLORIA",
            "code": "50",
            "parks_count": 71
        },
        {
            "name": "PARQUE ENTRENUBES",
            "code": "60",
            "parks_count": 1
        },
        {
            "name": "GRAN YOMASA",
            "code": "57",
            "parks_count": 88
        },
        {
            "name": "COMUNEROS",
            "code": "58",
            "parks_count": 64
        },
        {
            "name": "DANUBIO",
            "code": "56",
            "parks_count": 18
        },
        {
            "name": "CIUDAD USME",
            "code": "61",
            "parks_count": 24
        },
        {
            "name": "ALFONSO LÓPEZ",
            "code": "59",
            "parks_count": 40
        },
        {
            "name": "LA FLORA",
            "code": "52",
            "parks_count": 31
        },
        {
            "name": "TUNJUELITO",
            "code": "62",
            "parks_count": 5
        },
        {
            "name": "VENECIA",
            "code": "42",
            "parks_count": 47
        },
        {
            "name": "BOSA CENTRAL",
            "code": "85",
            "parks_count": 122
        },
        {
            "name": "BOSA OCCIDENTAL",
            "code": "84",
            "parks_count": 55
        },
        {
            "name": "EL PORVENIR",
            "code": "86",
            "parks_count": 20
        },
        {
            "name": "APOGEO",
            "code": "49",
            "parks_count": 11
        },
        {
            "name": "TINTAL SUR",
            "code": "87",
            "parks_count": 13
        },
        {
            "name": "CASTILLA",
            "code": "46",
            "parks_count": 58
        },
        {
            "name": "GRAN BRITALIA",
            "code": "81",
            "parks_count": 31
        },
        {
            "name": "CALANDAIMA",
            "code": "79",
            "parks_count": 22
        },
        {
            "name": "PATIO BONITO",
            "code": "82",
            "parks_count": 40
        },
        {
            "name": "TIMIZA",
            "code": "48",
            "parks_count": 86
        },
        {
            "name": "BAVARIA",
            "code": "113",
            "parks_count": 14
        },
        {
            "name": "CARVAJAL",
            "code": "45",
            "parks_count": 57
        },
        {
            "name": "KENNEDY CENTRAL",
            "code": "47",
            "parks_count": 83
        },
        {
            "name": "CORABASTOS",
            "code": "80",
            "parks_count": 10
        },
        {
            "name": "AMÉRICAS",
            "code": "44",
            "parks_count": 65
        },
        {
            "name": "LAS MARGARITAS",
            "code": "83",
            "parks_count": 5
        },
        {
            "name": "TINTAL NORTE",
            "code": "78",
            "parks_count": 2
        },
        {
            "name": "GRANJAS DE TECHO",
            "code": "112",
            "parks_count": 14
        },
        {
            "name": "FONTIBÓN",
            "code": "75",
            "parks_count": 60
        },
        {
            "name": "MODELIA",
            "code": "114",
            "parks_count": 54
        },
        {
            "name": "FONTIBON SAN PABLO",
            "code": "76",
            "parks_count": 30
        },
        {
            "name": "ZONA FRANCA",
            "code": "77",
            "parks_count": 14
        },
        {
            "name": "CAPELLANIA",
            "code": "115",
            "parks_count": 21
        },
        {
            "name": "CIUDAD SALITRE OCCIDENTAL",
            "code": "110",
            "parks_count": 25
        },
        {
            "name": "CIUDAD SALITRE ORIENTAL",
            "code": "109",
            "parks_count": 2
        },
        {
            "name": "BOYACÁ REAL",
            "code": "30",
            "parks_count": 61
        },
        {
            "name": "GARCÉS NAVAS",
            "code": "73",
            "parks_count": 73
        },
        {
            "name": "SANTA CECILIA",
            "code": "31",
            "parks_count": 51
        },
        {
            "name": "ENGATIVA",
            "code": "74",
            "parks_count": 40
        },
        {
            "name": "LAS FERIAS",
            "code": "26",
            "parks_count": 50
        },
        {
            "name": "MINUTO DE DIOS",
            "code": "29",
            "parks_count": 139
        },
        {
            "name": "BOLIVIA",
            "code": "72",
            "parks_count": 47
        },
        {
            "name": "ALAMOS",
            "code": "116",
            "parks_count": 17
        },
        {
            "name": "JARDÍN BOTÁNICO",
            "code": "105",
            "parks_count": 6
        },
        {
            "name": "EL RINCÓN",
            "code": "28",
            "parks_count": 120
        },
        {
            "name": "LA ALHAMBRA",
            "code": "20",
            "parks_count": 52
        },
        {
            "name": "TIBABUYES",
            "code": "71",
            "parks_count": 95
        },
        {
            "name": "SUBA",
            "code": "27",
            "parks_count": 93
        },
        {
            "name": "BRITALIA",
            "code": "18",
            "parks_count": 67
        },
        {
            "name": "SAN JOSÉ DE BAVARIA",
            "code": "17",
            "parks_count": 30
        },
        {
            "name": "NIZA",
            "code": "24",
            "parks_count": 133
        },
        {
            "name": "CASA BLANCA SUBA",
            "code": "23",
            "parks_count": 36
        },
        {
            "name": "EL PRADO",
            "code": "19",
            "parks_count": 59
        },
        {
            "name": "LA FLORESTA",
            "code": "25",
            "parks_count": 53
        },
        {
            "name": "GUAYMARAL",
            "code": "3",
            "parks_count": 5
        },
        {
            "name": "SUBA RURAL",
            "code": "211",
            "parks_count": 5
        },
        {
            "name": "LA SABANA",
            "code": "102",
            "parks_count": 20
        },
        {
            "name": "SANTA ISABEL",
            "code": "37",
            "parks_count": 26
        },
        {
            "name": "RESTREPO",
            "code": "38",
            "parks_count": 45
        },
        {
            "name": "CIUDAD JARDÍN",
            "code": "35",
            "parks_count": 10
        },
        {
            "name": "CIUDAD MONTES",
            "code": "40",
            "parks_count": 114
        },
        {
            "name": "MUZÚ",
            "code": "41",
            "parks_count": 98
        },
        {
            "name": "SAN RAFAEL",
            "code": "43",
            "parks_count": 35
        },
        {
            "name": "PUENTE ARANDA",
            "code": "111",
            "parks_count": 5
        },
        {
            "name": "ZONA INDUSTRIAL",
            "code": "108",
            "parks_count": 12
        },
        {
            "name": "LA CANDELARIA",
            "code": "94",
            "parks_count": 10
        },
        {
            "name": "MARRUECOS",
            "code": "54",
            "parks_count": 102
        },
        {
            "name": "DIANA TURBAY",
            "code": "55",
            "parks_count": 34
        },
        {
            "name": "MARCO FIDEL SUAREZ",
            "code": "53",
            "parks_count": 21
        },
        {
            "name": "SAN JOSÉ",
            "code": "36",
            "parks_count": 26
        },
        {
            "name": "QUIROGA",
            "code": "39",
            "parks_count": 64
        },
        {
            "name": "EL TESORO",
            "code": "68",
            "parks_count": 45
        },
        {
            "name": "LUCERO",
            "code": "67",
            "parks_count": 105
        },
        {
            "name": "ARBORIZADORA",
            "code": "65",
            "parks_count": 44
        },
        {
            "name": "ISMAEL PERDOMO",
            "code": "69",
            "parks_count": 100
        },
        {
            "name": "SAN FRANCISCO",
            "code": "66",
            "parks_count": 35
        },
        {
            "name": "JERUSALÉM",
            "code": "70",
            "parks_count": 65
        },
        {
            "name": "RÍO TUNJUELO",
            "code": "UPR3",
            "parks_count": 1
        },
        {
            "name": "CIUDAD BOLIVAR RURAL",
            "code": "219",
            "parks_count": 3
        },
        {
            "name": "MONTEBLANCO",
            "code": "64",
            "parks_count": 5
        },
        {
            "name": "LOS ANDES",
            "code": "21",
            "parks_count": 2
        },
        {
            "name": "LOS ALCÁZARES",
            "code": "98",
            "parks_count": 3
        },
        {
            "name": "DOCE DE OCTUBRE",
            "code": "22",
            "parks_count": 1
        },
        {
            "name": "PARQUE EL SALITRE",
            "code": "103",
            "parks_count": 12
        },
        {
            "name": "QUINTA PAREDES",
            "code": "107",
            "parks_count": 1
        },
        {
            "name": "PARQUE SIMÓN BOLÍVAR - CAN",
            "code": "104",
            "parks_count": 3
        },
        {
            "name": "GALERÍAS",
            "code": "100",
            "parks_count": 11
        },
        {
            "name": "TEUSAQUILLO",
            "code": "101",
            "parks_count": 1
        },
        {
            "name": "LA ESMERALDA",
            "code": "106",
            "parks_count": 1
        },
        {
            "name": "SIN UPZ",
            "code": "0",
            "parks_count": 531
        },
        {
            "name": "SAN CRISTOBAL RURAL",
            "code": "204",
            "parks_count": 1
        }
    ],
    "details": null,
    "code": 200,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/stats/upz`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_3a4acbf79cfaf80b936039213950918c -->

<!-- START_f8a2d1de0508aaee96994a5e40d00bb6 -->
## Excel

Devuelve un archivo en Excel (.xlsx) condificado en Base64 con información de los parques según los filtros realizados.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/excel" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/stats/excel");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "name": "PARQUES-FA453A-A625A6.xlsx",
        "file": "data:application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,TyayT8y76hh7A6GAJA887..."
    },
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```
> Respuesta de ejemplo (500):

```json
{
    "message": "Server Error"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/stats/excel`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    location |  opcional  | Arreglo de ids o id de la localidad.
    upz |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood |  opcional  | Arreglo de ids o id del barrio del parque.
    certified |  opcional  | Parques que están certificados o no Ejemplo: certified, not_certified.
    admin |  opcional  | Parques que están administrados o no por el IDRD. Ejemplo: admin, is_not_admin.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    park_type |  opcional  | Arreglo de ids de la escala del parque. Ejemplo: [1, 2, 3].

<!-- END_f8a2d1de0508aaee96994a5e40d00bb6 -->

#Parques - Gestión de Usuarios


<!-- START_fbb095467bb6206a0837ae1ea1751d06 -->
## Usuarios

Muestra el listado de usuarios asociados al módulo de parques.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/users" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/users");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "guid": "fbad8322-b960-...",
            "full_name": "JHON DOE",
            "name": "JHON",
            "surname": "DOE",
            "document": "12335678",
            "email": "jhon.doe@idrd.gov.co",
            "username": "jhon.doe",
            "description": "CONTRATISTA",
            "dependency": "AREA DEL IDRD",
            "company": "SEDE PRINCIPAL",
            "phone": "6605400",
            "ext": "363",
            "sim_id": 123,
            "password_expired": false,
            "is_locked": false,
            "vacation_start_date": null,
            "vacation_final_date": null,
            "roles": [
                {
                    "id": 8,
                    "name": "role",
                    "title": "Role",
                    "created_at": "2021-02-19 14:04:17",
                    "updated_at": "2021-02-19 14:04:17"
                }
            ],
            "expires_at": "2022-02-01 00:00:00",
            "created_at": "2020-06-23 21:15:42",
            "updated_at": "2021-07-09 22:34:29",
            "ldap": [],
            "deleted_at": null
        },
        {
            "id": 143,
            "guid": "5f3ad64e-3b46-...",
            "...": "..."
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T16:27:03-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/users`


<!-- END_fbb095467bb6206a0837ae1ea1751d06 -->

<!-- START_92fe1778d185abc188462a96b5a8ba39 -->
## Roles

Muestra el listado de roles asociados al módulo.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/users/roles" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/users/roles");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": "65fe6152",
            "name": "root",
            "title": "Administrador del Sistema",
            "created_at": "2021-02-19 14:04:17",
            "updated_at": "2021-02-19 14:04:17"
        },
        {
            "id": "65fe6152",
            "name": "...",
            "...": "..."
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T16:01:53-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/users/roles`


<!-- END_92fe1778d185abc188462a96b5a8ba39 -->

<!-- START_23a66106177d67ff5336575eab8db166 -->
## Asignación de Roles

Asigna un rol o varios roles especificados a un usuario.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/users/roles/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"roles":["administrador-de-parques"]}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/users/roles/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "roles": [
        "administrador-de-parques"
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/users/roles/{user}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    roles.* | array[string] |  obligatorio  | Arreglo de nombres de roles a asociar a un usuario.

<!-- END_23a66106177d67ff5336575eab8db166 -->

<!-- START_6c6241f906cb0be91c78fa415c0e5e79 -->
## Eliminación de Roles

Elimina un rol o roles asociados a un usuario especificado..

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/users/roles/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"roles":["administrador-de-parques"]}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/users/roles/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "roles": [
        "administrador-de-parques"
    ]
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/users/roles/{user}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    roles.* | array[string] |  obligatorio  | Arreglo de nombres de roles a asociar a un usuario.

<!-- END_6c6241f906cb0be91c78fa415c0e5e79 -->

<!-- START_0765a8819fc542b8ff0ca2b2ac4a589b -->
## Buscador de usuarios

Muestra un listado de coincidencias según los parámetros establecidos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/users/find?username=daniel.prado" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/users/find");

    let params = {
            "username": "daniel.prado",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "guid": "fbad8322-b960-...",
            "full_name": "JHON DOE",
            "name": "JHON",
            "surname": "DOE",
            "document": "12335678",
            "email": "jhon.doe@idrd.gov.co",
            "username": "jhon.doe",
            "description": "CONTRATISTA",
            "dependency": "AREA DEL IDRD",
            "company": "SEDE PRINCIPAL",
            "phone": "6605400",
            "ext": "363",
            "sim_id": 123,
            "password_expired": false,
            "is_locked": false,
            "vacation_start_date": null,
            "vacation_final_date": null,
            "roles": [
                {
                    "id": 8,
                    "name": "role",
                    "title": "Role",
                    "created_at": "2021-02-19 14:04:17",
                    "updated_at": "2021-02-19 14:04:17"
                }
            ],
            "expires_at": "2022-02-01 00:00:00",
            "created_at": "2020-06-23 21:15:42",
            "updated_at": "2021-07-09 22:34:29",
            "ldap": [],
            "deleted_at": null
        },
        {
            "id": 143,
            "guid": "5f3ad64e-3b46-...",
            "...": "..."
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T16:27:03-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/users/find`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    username |  opcional  | Nombre de usuario, documento o nombre completo del usuario para consultar en LDAP.

<!-- END_0765a8819fc542b8ff0ca2b2ac4a589b -->

<!-- START_b6ebcd40b1b7bbd837615c7789f27d74 -->
## Menú

Despliega el menú dinámico dependendo de los permisos asignados al usuario.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/user/menu" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/user/menu");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "icon": "mdi-view-dashboard",
            "title": "Dashboard",
            "to": {
                "name": "dashboard"
            },
            "exact": true,
            "can": true
        }
    ],
    "details": null,
    "code": 200,
    "requested_at": "2021-09-12T16:45:59"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/user/menu`


<!-- END_b6ebcd40b1b7bbd837615c7789f27d74 -->

<!-- START_c5fac7a771e80f7f7629031037951413 -->
## Permisos

Despliega el listado de permisos asociados al usuario y módulo autenticado.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/user/permissions" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/user/permissions");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "id": 1,
        "abilities": [],
        "roles": []
    },
    "details": null,
    "code": 200,
    "requested_at": "2021-09-12T16:45:59"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/user/permissions`


<!-- END_c5fac7a771e80f7f7629031037951413 -->

#Parques - Historia del Parque


<!-- START_dc40e6687cd8ae7a74a87efe57ab5f86 -->
## Historia del Parque

Muestra un listado del recurso.

Breve con fotografías del parque.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "id": 9,
        "park_id": 9,
        "paragraph_1": "El parque zonal que cuenta con una extensión de 12 mil metros cuadrados, fue construido en la localidad de San Cristóbal frente a la reconocida iglesia Nuestra Señora de las Cruces, ubicado en un sector de habitantes bogotanos de escasos recursos.  ",
        "paragraph_2": "Debido a la demanda de espacios para vivir, la arquitectura del barrio sufrió cambios durante los siglos XIX y XX, evidenciados hoy en un sector rodeado por la gran presencia de inquilinatos y pequeÃ±os negocios. En la actualidad es posible evidenciar los pequeños talleres que fueron adecuados alrededor del parque para suplir las necesidades básicas de los habitantes del sector, entre los que se encuentra: sastres, zapateros, carpinteros, Latoneros, tapiceros, ebanistas y peluqueros, quienes se han encargado de frecuentar el recinto lúdico, el cual, ha demostrado ser un escenario recreativo y social, sin distinción de clases sociales o condiciones económicas. \t\t\t",
        "image_1": "984c695db7ed427e4200cd890d310815.jpg",
        "image_2": "c171a0bd9cb26982e9b610ca894efa4d.jpg",
        "image_3": "d73e54a039a62c11f78eaa8f7d00af5f.jpg",
        "image_4": "",
        "image_5": "",
        "image_6": "",
        "images": [
            "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/\/storage\/parks\/images\/984c695db7ed427e4200cd890d310815.jpg",
            "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/\/storage\/parks\/images\/c171a0bd9cb26982e9b610ca894efa4d.jpg",
            "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/\/storage\/parks\/images\/d73e54a039a62c11f78eaa8f7d00af5f.jpg"
        ],
        "created_at": "2021-09-16 14:42:49",
        "updated_at": "2021-09-16 14:42:54",
        "deleted_at": null
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/{park}/origin`


<!-- END_dc40e6687cd8ae7a74a87efe57ab5f86 -->

<!-- START_8998860ecb32f1933999da17a194a741 -->
## Crear Historia del Parque

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"paragraph_1":"soluta"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "paragraph_1": "soluta"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/{park}/origin`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    paragraph_1 | string |  obligatorio  | Texto asociado a la historia del parque, máximo 2500 caracteres.
    paragraph_2 | string |  opcional  | Texto asociado a la historia del parque, máximo 2500 caracteres.
    image_1 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_2 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_3 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_4 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_5 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_6 | file |  opcional  | Imágen asociada al parque (png, jpg).

<!-- END_8998860ecb32f1933999da17a194a741 -->

<!-- START_b0cf262a89b4dd4a8122899a403026d6 -->
## Actualizar Historia del Parque

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"paragraph_1":"consectetur"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "paragraph_1": "consectetur"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/parks/{park}/origin/{origin}`

`PATCH api/parks/{park}/origin/{origin}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    paragraph_1 | string |  obligatorio  | Texto asociado a la historia del parque, máximo 2500 caracteres.
    paragraph_2 | string |  opcional  | Texto asociado a la historia del parque, máximo 2500 caracteres.
    image_1 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_2 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_3 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_4 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_5 | file |  opcional  | Imágen asociada al parque (png, jpg).
    image_6 | file |  opcional  | Imágen asociada al parque (png, jpg).

<!-- END_b0cf262a89b4dd4a8122899a403026d6 -->

<!-- START_55cba2a6f6ec065344d56995ca720a75 -->
## Eliminar Historia del Parque

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/origin/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/{park}/origin/{origin}`


<!-- END_55cba2a6f6ec065344d56995ca720a75 -->

#Parques - Localidades


<!-- START_84cef564cb95c5e627d5da491146fda7 -->
## Localidades

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/localities" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "USAQUÉN",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "CHAPINERO",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 3,
            "name": "SANTA FE",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 4,
            "name": "SAN CRISTÓBAL",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 5,
            "name": "USME",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 6,
            "name": "TUNJUELITO",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 7,
            "name": "BOSA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 8,
            "name": "KENNEDY",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 9,
            "name": "FONTIBÓN",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 10,
            "name": "ENGATIVA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 11,
            "name": "SUBA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 12,
            "name": "BARRIOS UNIDOS",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 13,
            "name": "TEUSAQUILLO",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 14,
            "name": "LOS MÁRTIRES",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 15,
            "name": "ANTONIO NARIÑO",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 16,
            "name": "PUENTE ARANDA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 17,
            "name": "LA CANDELARIA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 18,
            "name": "RAFAEL URIBE URIBE",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 19,
            "name": "CIUDAD BOLÍVAR",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 20,
            "name": "SUMAPAZ",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 21,
            "name": "DISTRITAL",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 22,
            "name": "OTRO MUNICIPIO O CIUDAD",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:03-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/localities`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    column |  opcional  | Nombre de la columna para realizar filtros u ordenamientos Ejemplo: ['name'].
    order |  opcional  | Orden de los resultados true para ascendente o false para descendente Ejemplo: ['true'].
    where |  opcional  | Indica que el valor a buscar debe ser igual al especifivado.
    where_not |  opcional  | Indica que el valor a buscar debe ser diferente al especifivado.
    where_in |  opcional  | Indica que el valor a buscar debe estar entre los valores especificados Ejemplo: 1,2,3.
    where_not_in |  opcional  | Indica que el valor a buscar no debe estar entre los valores especificados Ejemplo: 1,2,3.
    where_between |  opcional  | Indica que el valor a buscar debe estar entre los valores especificados Ejemplo: 2021-01-01,2021-05-31.
    where_not_between |  opcional  | Indica que el valor a buscar no debem estar entre los valores especificados Ejemplo: 1,8.
    or_where |  opcional  | Si está definido el parámetro where indicará que los valores a buscar deben ser iguales al primer valor entregado o igual al segundo valor. Ejemplo: api/ruta?column[]=id&where=1&or_where=2.
    or_where_in |  opcional  | Si está definido el parámetro where_in indicará que los valores a buscar deben estar en primer valor entregado o entre los datos del segundo valor. Ejemplo: api/ruta?column[]=id&where_in=1,2&or_where_in=5,6.
    or_where_not_in |  opcional  | Si está definido el parámetro where_in indicará que los valores a buscar no deben estar en primer valor entregado o entre los datos del segundo valor. Ejemplo: api/ruta?column[]=id&where_not_in=1,2&or_where_not_in=5,6.
    or_where_between |  opcional  | Indica que el valor a buscar debe estar entre los valores especificados Ejemplo: api/ruta?column[]=id&where_between=2021-01-01,2021-05-31&or_where_between=2020-01-01,2020-05-31.
    or_where_not_between |  opcional  | Indica que el valor a buscar no debe estar entre los valores especificados Ejemplo: api/ruta?column[]=id&where_between=2021-01-01,2021-05-31&or_where_between=2020-01-01,2020-05-31.

<!-- END_84cef564cb95c5e627d5da491146fda7 -->

<!-- START_333f5da1371afc6c9a628edc4ad4ce1b -->
## Crear Localidad

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/localities" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"USAQU\u00c9N"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "USAQU\u00c9N"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/localities`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre de la localidad máximo 50 caracteres.

<!-- END_333f5da1371afc6c9a628edc4ad4ce1b -->

<!-- START_dd28a67748ef35400904034e0dce0c17 -->
## Actualizar Localidad

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/localities/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"USAQU\u00c9N"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "USAQU\u00c9N"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/localities/{location}`

`PATCH api/localities/{location}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre de la localidad máximo 50 caracteres.

<!-- END_dd28a67748ef35400904034e0dce0c17 -->

<!-- START_a7f1942e6c4199108260b3c21d4f3532 -->
## Eliminar Localidad

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/localities/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/localities/{location}`


<!-- END_a7f1942e6c4199108260b3c21d4f3532 -->

#Parques - Mapa


<!-- START_293608644a016dafeed8d82e71eb5e31 -->
## Mapa

Provee la configuración del mapa y los servicios para embeber el
mapa desde una etiqueta HTML &lt;iframe&gt; o para utilizar las
librerías de ESRI/ARCGis junto con el API de Catastro.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/esri/config" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/esri/config");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "iframe": {
            "url": "https:\/\/mapas.bogota.gov.co\/?l=436&e=-74.57201001759988,4.2906625340901,-73.61070630666201,4.928542831147915,4686&show_menu=false",
            "dark": "&b=7176",
            "light": "&b=262",
            "filter": "&layerFilter=436;"
        },
        "layer": {
            "url": "https:\/\/serviciosgis.catastrobogota.gov.co\/arcgis\/rest\/services\/recreaciondeporte\/parquesyescenarios\/MapServer\/1",
            "outFields": [
                "OBJECTID",
                "ID_PARQUE",
                "NOMBRE_PARQ",
                "CODIGOPOT",
                "TIPOPARQUE",
                "ID_UPZ",
                "ID_LOCALIDAD",
                "LOCNOMBRE",
                "ESTRATO",
                "ADMINISTRA",
                "ESTADO_CER",
                "FECHAINCORPORACION",
                "SHAPE"
            ],
            "popupTemplate": {
                "title": "{ID_PARQUE} - {NOMBRE_PARQ}",
                "lastEditInfoEnabled": true,
                "content": [
                    {
                        "type": "fields",
                        "fieldInfos": [
                            {
                                "fieldName": "TIPOPARQUE",
                                "label": "Tipo de Parque"
                            },
                            {
                                "fieldName": "ID_UPZ",
                                "label": "UPZ"
                            },
                            {
                                "fieldName": "LOCNOMBRE",
                                "label": "Localidad"
                            },
                            {
                                "fieldName": "ESTRATO",
                                "label": "Estrato"
                            },
                            {
                                "fieldName": "ADMINISTRA",
                                "label": "Administrado por"
                            },
                            {
                                "fieldName": "ESTADO_CER",
                                "label": "Estado Certificado"
                            },
                            {
                                "fieldName": "FECHAINCORPORACION",
                                "label": "Fecha de Incorporación"
                            }
                        ]
                    }
                ]
            }
        },
        "param": "ID_PARQUE=",
        "park_types": [
            {
                "name": "TODO",
                "value": "todo",
                "style": {
                    "backgroundColor": "rgba(89,77,149, 1)",
                    "borderColor": "rgba(89,77,149, 1)"
                }
            },
            {
                "name": "PARQUE REGIONAL",
                "value": "TIPOPARQUE='PARQUE REGIONAL'",
                "style": {
                    "backgroundColor": "rgba(56, 168, 0, 1)",
                    "borderColor": "rgba(56, 168, 0, 1)"
                }
            },
            {
                "name": "PARQUE METROPOLITANO",
                "value": "TIPOPARQUE='PARQUE METROPOLITANO'",
                "style": {
                    "backgroundColor": "rgba(112, 168, 0, 1)",
                    "borderColor": "rgba(112, 168, 0, 1)"
                }
            },
            {
                "name": "PARQUE ZONAL",
                "value": "TIPOPARQUE='PARQUE ZONAL'",
                "style": {
                    "backgroundColor": "rgba(170, 255, 0, 1)",
                    "borderColor": "rgba(170, 255, 0, 1)"
                }
            },
            {
                "name": "ESCENARIO DEPORTIVO",
                "value": "TIPOPARQUE='ESCENARIO DEPORTIVO'",
                "style": {
                    "backgroundColor": "rgba(230, 152, 0, 1)",
                    "borderColor": "rgba(230, 152, 0, 1)"
                }
            },
            {
                "name": "PARQUE VECINAL",
                "value": "TIPOPARQUE='PARQUE VECINAL'",
                "style": {
                    "backgroundColor": "rgba(209, 255, 115, 1)",
                    "borderColor": "rgba(209, 255, 115, 1)"
                }
            },
            {
                "name": "PARQUE DE BOLSILLO",
                "value": "TIPOPARQUE='PARQUE DE BOLSILLO'",
                "style": {
                    "backgroundColor": "rgba(233, 255, 190, 1)",
                    "borderColor": "rgba(233, 255, 190, 1)"
                }
            },
            {
                "name": "ADMINISTRA IDRD",
                "value": "ADMINISTRA='IDRD'",
                "style": {
                    "backgroundColor": "rgb(255,190,200)",
                    "borderColor": "rgba(255,190,200)"
                }
            }
        ]
    },
    "details": null,
    "code": 200,
    "requested_at": "2021-09-21T17:52:03-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/esri/config`


<!-- END_293608644a016dafeed8d82e71eb5e31 -->

#Parques - Modelos


<!-- START_9ffddc5e8aadc2d33fa931e4de160078 -->
## Modelos

Muestra un listado de entidades del módulo al cual se le asociarán permisos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/models" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/models");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": "App\\Models\\Model",
            "name": "Modelo"
        },
        {
            "id": "...",
            "name": "..."
        }
    ],
    "details": null,
    "code": 200,
    "requested_at": "2021-09-21T15:56:20-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/admin/models`


<!-- END_9ffddc5e8aadc2d33fa931e4de160078 -->

#Parques - Parques Asignados


<!-- START_0fe5ab428f6a13e14ccb17fe8cec8faa -->
## Ids de parques asignados.

Muestra un listado de ids de los parques asignados al usuario autenticado.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/owned-keys" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/owned-keys");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        1,
        233,
        98273
    ],
    "details": null,
    "code": 200,
    "requested_at": "2021-09-12T16:35:39"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/owned-keys`


<!-- END_0fe5ab428f6a13e14ccb17fe8cec8faa -->

<!-- START_3aef6b114aaae4ea1415642256b4afe5 -->
## Buscador de Parques Asignados

Muestra el listado de los parques asignados al usuario autenticado

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/owned?query=03-036" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/owned");

    let params = {
            "query": "03-036",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 9,
            "code": "03-036",
            "name": "LAS CRUCES",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 3,
            "locality": "SANTA FE",
            "address": "CARRERA 5A #1- 90",
            "upz_code": "95",
            "upz": "LAS CRUCES",
            "color": "success",
            "status_id": null,
            "created_at": "2021-09-21 13:24:32",
            "updated_at": "2021-09-21 13:24:32",
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                }
            ]
        }
    ],
    "links": {
        "first": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/owned?page=1",
        "last": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/owned?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/owned",
        "per_page": 10,
        "to": 1,
        "total": 1
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T08:30:54-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/owned`

#### Query Parameters

Parámetro | Estado | Descripción
--------- | ------- | ------- | -----------
    query |  opcional  | Código, nombre o dirección del parque.
    locality_id |  opcional  | Arreglo de ids o id de la localidad.
    upz_id |  opcional  | Arreglo de códigos de UPZ o código de la UPZ.
    neighborhood_id |  opcional  | Arreglo de ids o id del barrio del parque.
    type_id |  opcional  | Arreglo de ids o id de la escala del parque.
    vigilance |  opcional  | Parques que cuentan con vigilancia. Ejemplo: Con vigilancia, Sin vigilancia.
    enclosure |  opcional  | Arreglo de tipos de cerramiento del parque. Ejemplo: Total, Parcial, Ninguno.
    column |  opcional  | Campo de ordenamiento Ejemplo: ?column[]=name.
    order |  opcional  | Orden de los resultados true para ascendente o false para descendente Ejemplo: ?order[]=true.
    page |  opcional  | La página a retornar Ejemplo: ?page=3.
    per_page |  opcional  | La cantidad de resultados a retornar Ejemplo: ?per_page=58.

<!-- END_3aef6b114aaae4ea1415642256b4afe5 -->

<!-- START_6a8da6a0d48fe1ea0494810ca679411e -->
## Parques Asignados a Usuario

Muestra un listado de los parques asignados a un usuario en específico.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/owned/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/owned/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 9,
            "code": "03-036",
            "name": "LAS CRUCES",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 3,
            "locality": "SANTA FE",
            "address": "CARRERA 5A #1- 90",
            "upz_code": "95",
            "upz": "LAS CRUCES",
            "color": "success",
            "status_id": null,
            "created_at": "2021-09-21 13:24:32",
            "updated_at": "2021-09-21 13:24:32",
            "deleted_at": null,
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                }
            ]
        }
    ],
    "links": {
        "first": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/owned?page=1",
        "last": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/owned?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "https:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/owned",
        "per_page": 10,
        "to": 1,
        "total": 1
    },
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T08:30:54-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/owned/{user}`


<!-- END_6a8da6a0d48fe1ea0494810ca679411e -->

<!-- START_6f9a9796107cfd24251e0aa9269bd04a -->
## Asignar Parque a Usuario

Asigna la administración de un parque a un usuario en específico.
Puede asignar los parques de toda una localidad, upz, barrio o parque específico.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/owned" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"user_id":1,"type_assignment":"manual","park_id":[0]}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/owned");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "user_id": 1,
    "type_assignment": "manual",
    "park_id": [
        0
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/owned`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    user_id | integer |  obligatorio  | Id del usuario al que se asignará el parque.
    locality_id | integer |  opcional  | Es requerido si el tipo de asignamiento es "locality".
    upz_code | integer |  opcional  | Es requerido si el tipo de asignamiento es "upz".
    neighborhood_id | integer |  opcional  | Es requerido si el tipo de asignamiento es "neighborhood".
    type_assignment | string |  obligatorio  | Tipo de asignamiento Puede ser: locality, upz, neighborhood o manual.
    park_id.* | integer |  opcional  | Es requerido si el tipo de asignamiento es "manual" y debe contener Id de parques.

<!-- END_6f9a9796107cfd24251e0aa9269bd04a -->

<!-- START_163b3fa6a5efbdc2084d91d03988471f -->
## Deasociar un parque asignado a un usuario

Eliminar permisos a parques asignados y desasigna el parque de un usuario en específico.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/owned/1/9" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/owned/1/9");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/owned/{user}/{park}`


<!-- END_163b3fa6a5efbdc2084d91d03988471f -->

<!-- START_e4374548ff4277ffb2fde6054bada34a -->
## Deasociar todos los parques asignados a un usuario

Elimina la asignación de todos los parques a un usuario en específico.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/destroy-all-owned/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/destroy-all-owned/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/destroy-all-owned/{user}`


<!-- END_e4374548ff4277ffb2fde6054bada34a -->

#Parques - Permisos


<!-- START_9690903d31d8efdb0b0cfb69335697d6 -->
## Permisos

Muestra un listado de permisos asociados al módulo.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": "65fe6152",
            "name": "gestionar-datos-sistema",
            "title": "Gestionar Datos",
            "entity_id": null,
            "entity_type": "App\\Models\\Model",
            "only_owned": false,
            "options": [],
            "scope": null,
            "created_at": "2021-09-02 14:02:08",
            "updated_at": "2021-09-02 14:02:08"
        },
        {
            "id": "65fe6152",
            "name": "...",
            "...": "..."
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T15:58:57-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/admin/permissions`


<!-- END_9690903d31d8efdb0b0cfb69335697d6 -->

<!-- START_6847804a3d94edec1a19cb7c036fce11 -->
## Crear Permisos

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"'crear-equipamiento-parque'","title":"Crear equipamientos de parques","entity_type":"App\\Models\\Equipamiento"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "'crear-equipamiento-parque'",
    "title": "Crear equipamientos de parques",
    "entity_type": "App\\Models\\Equipamiento"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/admin/permissions`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del permiso a crear separado por guiones y debe ser único.
    title | string |  obligatorio  | Título o descripción del permiso máximo 191 caracteres.
    entity_type | string |  obligatorio  | Modelo o entidad a la que está asociado el permiso.

<!-- END_6847804a3d94edec1a19cb7c036fce11 -->

<!-- START_18b968ffd7817500356fb1139fff3d8f -->
## Actualizar Permisos

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"'crear-equipamiento-parque'","title":"Crear equipamientos de parques","entity_type":"App\\Models\\Equipamiento"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "'crear-equipamiento-parque'",
    "title": "Crear equipamientos de parques",
    "entity_type": "App\\Models\\Equipamiento"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/parks/admin/permissions/{permission}`

`PATCH api/parks/admin/permissions/{permission}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del permiso a crear separado por guiones y debe ser único.
    title | string |  obligatorio  | Título o descripción del permiso máximo 191 caracteres.
    entity_type | string |  obligatorio  | Modelo o entidad a la que está asociado el permiso.

<!-- END_18b968ffd7817500356fb1139fff3d8f -->

<!-- START_c1c18068b9d3a3761f894b449f155f19 -->
## Eliminar Permisos

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/permissions/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/admin/permissions/{permission}`


<!-- END_c1c18068b9d3a3761f894b449f155f19 -->

#Parques - Roles


<!-- START_dc551bc3f1e647462e564c31667c3c98 -->
## Roles

Muestra un listado de roles asociados al módulo.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": "65fe6152",
            "name": "root",
            "title": "Administrador del Sistema",
            "created_at": "2021-02-19 14:04:17",
            "updated_at": "2021-02-19 14:04:17"
        },
        {
            "id": "65fe6152",
            "name": "...",
            "...": "..."
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T16:01:53-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/admin/roles`


<!-- END_dc551bc3f1e647462e564c31667c3c98 -->

<!-- START_60e4f72e7fadfb581d51de8b1ac42914 -->
## Crear Roles

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"'administrador-parque'","title":"Administrador de parques"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "'administrador-parque'",
    "title": "Administrador de parques"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/admin/roles`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del rol a crear separado por guiones y debe ser único.
    title | string |  obligatorio  | Título o descripción del rol máximo 191 caracteres.

<!-- END_60e4f72e7fadfb581d51de8b1ac42914 -->

<!-- START_a70252255af4aed244c5970f999641b5 -->
## Actualizar Roles

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/parks/admin/roles/{role}`

`PATCH api/parks/admin/roles/{role}`


<!-- END_a70252255af4aed244c5970f999641b5 -->

<!-- START_66a1a90693ea6e7b0db9d839050b799e -->
## Eliminar Roles

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/admin/roles/{role}`


<!-- END_66a1a90693ea6e7b0db9d839050b799e -->

#Parques - Roles y Permisos


<!-- START_9d82303c375f691582a875ed9e1c55a1 -->
## Roles y Permisos

Muestra el listado de permisos asociados a un rol.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1/permissions" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1/permissions");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": "65fe6152",
            "name": "gestionar-datos-sistema",
            "title": "Gestionar Datos",
            "entity_id": null,
            "entity_type": "App\\Models\\Model",
            "only_owned": false,
            "options": [],
            "scope": null,
            "created_at": "2021-09-13 09:29:23",
            "updated_at": "2021-09-13 09:29:23"
        },
        {
            "...": "..."
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T16:04:44-05:00"
}
```
> Respuesta de ejemplo (401):

```json
{
    "message": "No estás autenticado para esta solicitud.",
    "details": {},
    "code": 401,
    "requested_at": "2021-09-21T17:52:51-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/admin/roles/{role}/permissions`


<!-- END_9d82303c375f691582a875ed9e1c55a1 -->

<!-- START_a6c54d5bc63170c06fd3d66e8774edd5 -->
## Asociar Rol a Permisos

Asocia un rol a un permiso específico

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1/permissions/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1/permissions/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/parks/admin/roles/{role}/permissions/{permission}`

`PATCH api/parks/admin/roles/{role}/permissions/{permission}`


<!-- END_a6c54d5bc63170c06fd3d66e8774edd5 -->

<!-- START_7cf5f7386d5cf5af7e747b388b6d7411 -->
## Desasociar Rol a Permisos

Elimina un permiso de un rol especificado

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1/permissions/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/admin/roles/1/permissions/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/admin/roles/{role}/permissions/{permission}`


<!-- END_7cf5f7386d5cf5af7e747b388b6d7411 -->

#Parques - Rupis


<!-- START_21ab9e554f690d5527b5c52f0fc08a49 -->
## Rupis

Muestra un listado del recurso.

RUPI: Es el código de identificación de los predios en el sistema de información de la Defensoría del Espacio Público.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 14,
            "name": "1-372",
            "park_id": 9,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/{park}/rupis`


<!-- END_21ab9e554f690d5527b5c52f0fc08a49 -->

<!-- START_55dfc754ad4463fe8861dce96b7d1127 -->
## Crear Rupis

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"9-123"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "9-123"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/parks/{park}/rupis`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Código rupi.

<!-- END_55dfc754ad4463fe8861dce96b7d1127 -->

<!-- START_5910839807cde115081a04c5a66942ce -->
## Actualizar Rupis

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"9-123"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "9-123"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/parks/{park}/rupis/{rupi}`

`PATCH api/parks/{park}/rupis/{rupi}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Código rupi.

<!-- END_5910839807cde115081a04c5a66942ce -->

<!-- START_a3c02b6f600c5e2f658b12c6e5b9a7d0 -->
## Eliminar Rupis

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/rupis/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/parks/{park}/rupis/{rupi}`


<!-- END_a3c02b6f600c5e2f658b12c6e5b9a7d0 -->

#Parques - Sectores Diagramas/Renders


<!-- START_50d38aa5bf83926ec9a571095ba937b3 -->
## Sectores Diagramas/Renders

En desarollo. Muestra breves datos de un parque y los sectores mapeados del render para mostrar información interactivamente.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/parks/9/sectors" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/parks/9/sectors");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": {
        "park": {
            "id": 9,
            "code": "03-036",
            "name": "LAS CRUCES",
            "scale_id": 3,
            "scale": "ZONAL",
            "locality_id": 3,
            "locality": "SANTA FE",
            "address": "CARRERA 5A #1- 90",
            "upz_code": "95",
            "upz": "LAS CRUCES",
            "color": "success",
            "status_id": 1,
            "created_at": null,
            "updated_at": null,
            "deleted_at": null,
            "sectors": [
                {
                    "id": 14,
                    "park_id": 9,
                    "sector": "Sector I",
                    "coordinate": null,
                    "type": 1,
                    "endowments": [
                        {
                            "id": 14827,
                            "park_id": 9,
                            "endowment_id": 1,
                            "endowment_num": null,
                            "endowment": "FÚTBOL",
                            "status_id": 2,
                            "status": "REGULAR",
                            "material": null,
                            "illumination": "SI",
                            "economic_use": "SI",
                            "area": 1111,
                            "floor_material_id": 19,
                            "floor_material": "GRAMA SINTETICA",
                            "equipment_id": 1,
                            "equipment": "CANCHAS DEPORTIVAS",
                            "enclosure_id": 1,
                            "enclosure": "TOTAL",
                            "dressing_room": "NO",
                            "light": null,
                            "water": null,
                            "gas": "",
                            "capacity": 0,
                            "lane": 0,
                            "bath": 0,
                            "sanitary_battery": 0,
                            "description": "CANCHA DE Futbol 5 EN GRAMA SINTETICA, SECTOR CENTRAL.",
                            "maintenance_diagnosis": "",
                            "construction_diagnosis": "",
                            "positioning": "",
                            "destination": "",
                            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/34e82ff8e70006afe0512071cb652f3a.jpg",
                            "date": "2017-04-03",
                            "enclosure_type": "SOLO METALICO (CONTARIMPACTO, MALLA ESLABONADA)",
                            "enclosure_height": "5 MTRS",
                            "long": 1144,
                            "width": 1,
                            "covered": "",
                            "dunt": 0,
                            "male_bath": 0,
                            "female_bath": 0,
                            "disabled_bath": 0,
                            "car_parking": 0,
                            "bike_parking": 0,
                            "public": 0,
                            "sector_id": 14,
                            "map": "1855,870,2013,925"
                        },
                        {
                            "id": 14828,
                            "park_id": 9,
                            "endowment_id": 91,
                            "endowment_num": null,
                            "endowment": "MICROFUTBOL",
                            "status_id": 1,
                            "status": "BUENO",
                            "material": null,
                            "illumination": "SI",
                            "economic_use": "SI",
                            "area": 582,
                            "floor_material_id": 15,
                            "floor_material": "ASFALTO SIN SINTETICO",
                            "equipment_id": 1,
                            "equipment": "CANCHAS DEPORTIVAS",
                            "enclosure_id": 3,
                            "enclosure": "NINGUNA",
                            "dressing_room": "NO",
                            "light": null,
                            "water": null,
                            "gas": "",
                            "capacity": 0,
                            "lane": 0,
                            "bath": 0,
                            "sanitary_battery": 0,
                            "description": "CANCHA DE MICROFUTBOL- COSTADO SURORIENTAL ",
                            "maintenance_diagnosis": "",
                            "construction_diagnosis": "",
                            "positioning": "",
                            "destination": "",
                            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/37b960e2bf70ec22ec92cdd686bfdef3.jpg",
                            "date": "2017-04-03",
                            "enclosure_type": "NINGUNO",
                            "enclosure_height": "NINGUNO",
                            "long": 480,
                            "width": 1,
                            "covered": "",
                            "dunt": 0,
                            "male_bath": 0,
                            "female_bath": 0,
                            "disabled_bath": 0,
                            "car_parking": 0,
                            "bike_parking": 0,
                            "public": 0,
                            "sector_id": 14,
                            "map": "1535,1259,1693,1314"
                        },
                        {
                            "id": 14829,
                            "park_id": 9,
                            "endowment_id": 4,
                            "endowment_num": null,
                            "endowment": "BALONCESTO",
                            "status_id": 3,
                            "status": "MALO",
                            "material": null,
                            "illumination": "SI",
                            "economic_use": "SI",
                            "area": 492,
                            "floor_material_id": 15,
                            "floor_material": "ASFALTO SIN SINTETICO",
                            "equipment_id": 1,
                            "equipment": "CANCHAS DEPORTIVAS",
                            "enclosure_id": 3,
                            "enclosure": "NINGUNA",
                            "dressing_room": "NO",
                            "light": null,
                            "water": null,
                            "gas": "",
                            "capacity": 0,
                            "lane": 0,
                            "bath": 0,
                            "sanitary_battery": 0,
                            "description": "CANCHA DE BALONCESTO COSTADO SURORIENTAL",
                            "maintenance_diagnosis": "",
                            "construction_diagnosis": "",
                            "positioning": "",
                            "destination": "",
                            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/271b6cf96c3912aa27c414075bf3650d.jpg",
                            "date": "2017-04-03",
                            "enclosure_type": "NINGUNO",
                            "enclosure_height": "NINGUNO",
                            "long": 420,
                            "width": 1,
                            "covered": "",
                            "dunt": 0,
                            "male_bath": 0,
                            "female_bath": 0,
                            "disabled_bath": 0,
                            "car_parking": 0,
                            "bike_parking": 0,
                            "public": 0,
                            "sector_id": 14,
                            "map": "1235,1144,1395,1199"
                        },
                        {
                            "id": 14830,
                            "park_id": 9,
                            "endowment_id": 90,
                            "endowment_num": null,
                            "endowment": "VOLEIBOL",
                            "status_id": 3,
                            "status": "MALO",
                            "material": null,
                            "illumination": "SI",
                            "economic_use": "SI",
                            "area": 200,
                            "floor_material_id": 15,
                            "floor_material": "ASFALTO SIN SINTETICO",
                            "equipment_id": 1,
                            "equipment": "CANCHAS DEPORTIVAS",
                            "enclosure_id": 3,
                            "enclosure": "NINGUNA",
                            "dressing_room": "NO",
                            "light": null,
                            "water": null,
                            "gas": "",
                            "capacity": 0,
                            "lane": 0,
                            "bath": 0,
                            "sanitary_battery": 0,
                            "description": "CANCHA DE VOLEIBOL COSTADO SURORIENTAL",
                            "maintenance_diagnosis": "",
                            "construction_diagnosis": "",
                            "positioning": "",
                            "destination": "",
                            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/90bc069e44af94e628c4a09879483921.jpg",
                            "date": "2017-04-03",
                            "enclosure_type": "NINGUNO",
                            "enclosure_height": "NINGUNO",
                            "long": 160,
                            "width": 1,
                            "covered": "",
                            "dunt": 0,
                            "male_bath": 0,
                            "female_bath": 0,
                            "disabled_bath": 0,
                            "car_parking": 0,
                            "bike_parking": 0,
                            "public": 0,
                            "sector_id": 14,
                            "map": "1109,983,1267,1038"
                        },
                        {
                            "id": 14835,
                            "park_id": 9,
                            "endowment_id": 63,
                            "endowment_num": null,
                            "endowment": "GIMNASIO AIRE LIBRE",
                            "status_id": 2,
                            "status": "REGULAR",
                            "material": null,
                            "illumination": null,
                            "economic_use": null,
                            "area": 189,
                            "floor_material_id": 14,
                            "floor_material": "GRANITO",
                            "equipment_id": 3,
                            "equipment": "ESCENARIO DEPORTIVO",
                            "enclosure_id": 2,
                            "enclosure": "PARCIAL",
                            "dressing_room": "NO",
                            "light": "SI",
                            "water": "NO",
                            "gas": "NO",
                            "capacity": 0,
                            "lane": 0,
                            "bath": 0,
                            "sanitary_battery": 0,
                            "description": "GIMNASIO AL AIRE LIBRE  EN ZONA DE PATINAJE (cuenta con 8 mÃ³dulos de gimnasio) ",
                            "maintenance_diagnosis": "",
                            "construction_diagnosis": "",
                            "positioning": "",
                            "destination": "",
                            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/c8970c20f4bd265ae2ad2dce84fbdef7.jpg",
                            "date": "2017-04-03",
                            "enclosure_type": "SOLO METALICO (CONTARIMPACTO, MALLA ESLABONADA)",
                            "enclosure_height": "1 MTS",
                            "long": 200,
                            "width": 1,
                            "covered": "No",
                            "dunt": 1,
                            "male_bath": 0,
                            "female_bath": 0,
                            "disabled_bath": 0,
                            "car_parking": 0,
                            "bike_parking": 0,
                            "public": 0,
                            "sector_id": 14,
                            "map": "1662,517,1820,572"
                        },
                        {
                            "id": 14840,
                            "park_id": 9,
                            "endowment_id": 57,
                            "endowment_num": null,
                            "endowment": "COLISEO",
                            "status_id": 1,
                            "status": "BUENO",
                            "material": null,
                            "illumination": null,
                            "economic_use": null,
                            "area": 1100,
                            "floor_material_id": 4,
                            "floor_material": "CONCRETO",
                            "equipment_id": 3,
                            "equipment": "ESCENARIO DEPORTIVO",
                            "enclosure_id": 1,
                            "enclosure": "TOTAL",
                            "dressing_room": "SI",
                            "light": "SI",
                            "water": "SI",
                            "gas": "NO",
                            "capacity": 600,
                            "lane": 0,
                            "bath": 2,
                            "sanitary_battery": 2,
                            "description": "COLISEO - cuenta con  (cafeteria, administracion, camerinos, bodega, baterias sanitarias).",
                            "maintenance_diagnosis": "",
                            "construction_diagnosis": "",
                            "positioning": "",
                            "destination": "",
                            "image": "https:\/\/www.idrd.gov.co\/SIM\/Parques\/Foto\/e5a0836b73c3e65725ac1e4a60bc7183.jpg",
                            "date": "2017-04-03",
                            "enclosure_type": "NINGUNO",
                            "enclosure_height": "5 MTRS",
                            "long": 1091,
                            "width": 1,
                            "covered": "Si",
                            "dunt": 1,
                            "male_bath": 1,
                            "female_bath": 1,
                            "disabled_bath": 1,
                            "car_parking": 0,
                            "bike_parking": 0,
                            "public": 0,
                            "sector_id": 14,
                            "map": "1045,468,1194,523"
                        }
                    ]
                }
            ],
            "_links": [
                {
                    "rel": "self",
                    "type": "GET",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "create",
                    "type": "POST",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks"
                },
                {
                    "rel": "update",
                    "type": "PUT\/PATCH",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                },
                {
                    "rel": "delete",
                    "type": "DELETE",
                    "href": "http:\/\/sim.idrd.gov.co\/base-ldap\/public\/api\/parks\/9"
                }
            ]
        },
        "type": 1
    },
    "details": null,
    "code": 200,
    "requested_at": "2021-09-21T17:52:43-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/parks/{park}/sectors`


<!-- END_50d38aa5bf83926ec9a571095ba937b3 -->

#Parques - Tipos de Escenarios


<!-- START_b3cd252fc556c9154c7c1deee05ad141 -->
## Tipos de Escenarios

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/stage-types" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/stage-types");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "CEFE",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "GRANDES ESCENARIOS",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/stage-types`


<!-- END_b3cd252fc556c9154c7c1deee05ad141 -->

<!-- START_181dfe944020c814ec2b655c3c79af42 -->
## Crear Tipo de Escenario

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/stage-types" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"CEFE"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/stage-types");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "CEFE"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/stage-types`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del tipo de escenario, máximo 191 caracteres.

<!-- END_181dfe944020c814ec2b655c3c79af42 -->

<!-- START_237133a22e8ec7634b7df41033eaee94 -->
## Actualizar Tipo de Escenario

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/stage-types/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"CEFE"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/stage-types/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "CEFE"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/stage-types/{stage}`

`PATCH api/stage-types/{stage}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre del tipo de escenario, máximo 191 caracteres.

<!-- END_237133a22e8ec7634b7df41033eaee94 -->

<!-- START_26002fae0351eb4d85c1ee04378af3d2 -->
## Eliminar Tipo de Escenario

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/stage-types/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/stage-types/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/stage-types/{stage}`


<!-- END_26002fae0351eb4d85c1ee04378af3d2 -->

#Parques - Tipos de Upz


<!-- START_b8d3e0960ee55253ef5ffde746da170b -->
## Tipos de Upz

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/upz-types" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/upz-types");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "UPZ",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "UPR",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/upz-types`


<!-- END_b8d3e0960ee55253ef5ffde746da170b -->

<!-- START_6e2b5904cc56f293e83a9d9e47e364d9 -->
## Crear Tipo de Upz

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/upz-types" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/upz-types");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/upz-types`


<!-- END_6e2b5904cc56f293e83a9d9e47e364d9 -->

<!-- START_a635842a0e6a1bb7ccb24dc284b75c3f -->
## Actualizar Tipo de Upz

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/upz-types/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/upz-types/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/upz-types/{types}`

`PATCH api/upz-types/{types}`


<!-- END_a635842a0e6a1bb7ccb24dc284b75c3f -->

<!-- START_bf1c04995014562fdca06faeff2ad18f -->
## Eliminar Tipo de Upz

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/upz-types/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/upz-types/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/upz-types/{types}`


<!-- END_bf1c04995014562fdca06faeff2ad18f -->

#Parques - UPZ


<!-- START_8963007a8c8976f265f4883032909784 -->
## UPZ

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "locality_id": 1,
            "name": "PASEO DE LOS LIBERTADORES",
            "upz_code": "1",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "1 - PASEO DE LOS LIBERTADORES",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "locality_id": 1,
            "name": "VERBENAL",
            "upz_code": "9",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "9 - VERBENAL",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 3,
            "locality_id": 1,
            "name": "LA URIBE",
            "upz_code": "10",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "10 - LA URIBE",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 4,
            "locality_id": 1,
            "name": "SAN CRISTÓBAL NORTE",
            "upz_code": "11",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "11 - SAN CRISTÓBAL NORTE",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 5,
            "locality_id": 1,
            "name": "TOBERÍN",
            "upz_code": "12",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "12 - TOBERÍN",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 6,
            "locality_id": 1,
            "name": "LOS CEDROS",
            "upz_code": "13",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "13 - LOS CEDROS",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 7,
            "locality_id": 1,
            "name": "USAQUÉN",
            "upz_code": "14",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "14 - USAQUÉN",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 8,
            "locality_id": 1,
            "name": "COUNTRY CLUB",
            "upz_code": "15",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "15 - COUNTRY CLUB",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 9,
            "locality_id": 1,
            "name": "SANTA BÁRBARA",
            "upz_code": "16",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "16 - SANTA BÁRBARA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 111,
            "locality_id": 1,
            "name": "USAQUEN RURAL",
            "upz_code": "201",
            "upz_type_id": 1,
            "upz_type": "UPZ",
            "composed_name": "201 - USAQUEN RURAL",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/localities/{location}/upz`


<!-- END_8963007a8c8976f265f4883032909784 -->

<!-- START_449deb8e59bdd9e58e22c4bcaa93df36 -->
## Crear UPZ

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"LAS CRUCES","upz_code":"78","locality_id":1}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "LAS CRUCES",
    "upz_code": "78",
    "locality_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/localities/{location}/upz`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre de la UPZ máximo 50 caracteres.
    upz_code | string |  obligatorio  | Código de la upz máximo 50 caracteres y debe ser un valor único.
    locality_id | integer |  obligatorio  | Id de la localidad.

<!-- END_449deb8e59bdd9e58e22c4bcaa93df36 -->

<!-- START_f02f158a5e672984830c775281249d11 -->
## Actualizar UPZ

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"name":"LAS CRUCES","upz_code":"78","locality_id":1}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "name": "LAS CRUCES",
    "upz_code": "78",
    "locality_id": 1
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/localities/{location}/upz/{upz}`

`PATCH api/localities/{location}/upz/{upz}`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    name | string |  obligatorio  | Nombre de la UPZ máximo 50 caracteres.
    upz_code | string |  obligatorio  | Código de la upz máximo 50 caracteres y debe ser un valor único.
    locality_id | integer |  obligatorio  | Id de la localidad.

<!-- END_f02f158a5e672984830c775281249d11 -->

<!-- START_854a49ecda4641467063f6129d8e9caf -->
## Eliminar UPZ

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/localities/1/upz/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/localities/{location}/upz/{upz}`


<!-- END_854a49ecda4641467063f6129d8e9caf -->

#Parques - Vocaciones


<!-- START_5468eeeaed9f146055946852f63140be -->
## Vocaciones

Muestra un listado del recurso.

> Solicitud de ejemplo:

```bash
curl -X GET -G "https://sim.idrd.gov.co/base-ldap/public/api/vocations" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/vocations");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": [
        {
            "id": 1,
            "name": "RECREACION ACTIVA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 2,
            "name": "RECREACION PASIVA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        },
        {
            "id": 3,
            "name": "MIXTA O COMBINADA",
            "created_at": null,
            "updated_at": null,
            "deleted_at": null
        }
    ],
    "code": 200,
    "details": null,
    "requested_at": "2021-09-21T17:52:04-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`GET api/vocations`


<!-- END_5468eeeaed9f146055946852f63140be -->

<!-- START_474d9b7980309ced84c22c10d7260342 -->
## Crear Vocaciones

Almacena un recurso recién creado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/api/vocations" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/vocations");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (201):

```json
{
    "data": "Datos almacenados satisfactoriamente",
    "details": null,
    "code": 201,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST api/vocations`


<!-- END_474d9b7980309ced84c22c10d7260342 -->

<!-- START_974596b11a84f0c99575a8d1dd462c6e -->
## Actualizar Vocaciones

Actualiza el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X PUT "https://sim.idrd.gov.co/base-ldap/public/api/vocations/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/vocations/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos actualizados satisfactoriamente",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`PUT api/vocations/{vocation}`

`PATCH api/vocations/{vocation}`


<!-- END_974596b11a84f0c99575a8d1dd462c6e -->

<!-- START_4f16484756f6ab1a83278f9785126e4f -->
## Eliminar Vocaciones

Elimina el recurso especificado en la base de datos.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #e3435c;">Requiere autenticación y permisos para esta acción</small>
> Solicitud de ejemplo:

```bash
curl -X DELETE "https://sim.idrd.gov.co/base-ldap/public/api/vocations/1" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json"
```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/api/vocations/1");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "Datos eliminados satisfactoriamente",
    "details": null,
    "code": 204,
    "requested_at": "2021-09-20T17:52:01-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`DELETE api/vocations/{vocation}`


<!-- END_4f16484756f6ab1a83278f9785126e4f -->

#Password


<!-- START_1da126ef2388f0db655fc4e586f0f187 -->
## Forgot Password

Send a reset link to the given user.

> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/password/forgot" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"document":"1234567"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/password/forgot");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "document": "1234567"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "¡Te hemos enviado por correo el enlace para restablecer tu contraseña, verifica los correos no deseados!",
    "details": {
        "email": "Hemos enviado un correo a c****@g***.com para restablecer la contraseña de tu cuenta"
    },
    "code": 200,
    "requested_at": "2021-09-12T16:45:39-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST password/forgot`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    document | string |  obligatorio  | Número de documento del usuario.

<!-- END_1da126ef2388f0db655fc4e586f0f187 -->

<!-- START_cafb407b7a846b31491f97719bb15aef -->
## Reset Password

Reset the given user&#039;s password.

> Solicitud de ejemplo:

```bash
curl -X POST "https://sim.idrd.gov.co/base-ldap/public/password/reset" \
    -H "Authorization: Bearer {token}" \
    -H "X-Localization: es" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"token":"eyUysRtsnHAHy6J8a....","email":"jhon.doe@idrd.gov.co","password":"MyStrongerPassword(&%\u00b7**","password_confirmed":"MyStrongerPassword(&%\u00b7**"}'

```

```javascript
const url = new URL("https://sim.idrd.gov.co/base-ldap/public/password/reset");

let headers = {
    "Authorization": "Bearer {token}",
    "X-Localization": "es",
    "Accept": "application/json",
    "Content-Type": "application/json",
}

let body = {
    "token": "eyUysRtsnHAHy6J8a....",
    "email": "jhon.doe@idrd.gov.co",
    "password": "MyStrongerPassword(&%\u00b7**",
    "password_confirmed": "MyStrongerPassword(&%\u00b7**"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Respuesta de ejemplo (200):

```json
{
    "data": "¡Tu contraseña ha sido restablecida, por favor espera unos minutos mientras se raliza la sincronización de datos en todas tus cuentas!",
    "details": null,
    "code": 200,
    "requested_at": "2021-09-12T16:45:39-05:00"
}
```

> Respuesta de error de validación de datos formularios o parámetros:

```json
{
    "message": "Los datos proporcionados no pasaron la validación, por favor verifica.",
    "errors": {
        "atributo": [
            "El campo :attribute es obligatorio.",
            "..."
        ],
        "...": [
            "..."
        ]
    }
}
```

> Respuesta de error 4xx y 5xx:

```json
{
    "message": "Mensaje de error. Ejemplo: No estás autenticado para esta solicitud.",
    "details": "Detalles del error (Si está disponible)",
    "code": "4xx - 5xx",
    "requested_at": "2021-09-21T17:52:53-05:00"
}
```

### HTTP Request
`POST password/reset`

#### Body Parameters

Parámetro | Tipo | Estado | Descripción
--------- | ------- | ------- | ------- | -----------
    token | string |  obligatorio  | Token enviado al correo electrónico del usuario.
    email | string |  obligatorio  | Correo de restauración de contraseña.
    password | string |  obligatorio  | Nueva contraseña del usuario.
    password_confirmed | string |  obligatorio  | Confirmación de la nueva contraseña del usuario.

<!-- END_cafb407b7a846b31491f97719bb15aef -->


