## How to start the php server
`php -S localhost:8008 -t api`

## Authorization
You can obtain your API keys from the network group.

This API is a RESTful API with industry standard HTTP requests and JSON responses.

This feature is disabled by default.

## Requests
In order to use our API you need to provide a ident `PB-API-IDENT` and the matching secret key `PB-API-SECRET` as HTTP-Headers in your request. (Disabled by default)

|Name|Type|Format|Description|
|----|----|------|-----------|
|API Ident|String|*pb-api-ident*|Public identification key|
|API Secret|String|*pb-api-secret*|Private identification key|

Any request body data needs to be in a `JSON ENCODED` data array as shown down below.

```javascript
{
    "data":{
        "botid":"1",
        "task":"race"
    }
}
```

## Responses
All requests return a JSON formatted response. The requested data is stored in a `DATA` tag. In order to check if the request was successful we include a boolean `STATUS` tag outside the `DATA` tag. For debugging purposes you can use the `MESSAGE` tag.

```javascript
HTTP/1.1 200 OK
{
    "data"{
        "botid":"1",
        "task":"race"
    },
    "status":true,
    "message":"Request successful."
}
```

## HTTP status codes
HTTP status codes give you information whether your request was successful or not.

|Code|Status|Description|
|----|------|-----------|
|200|Ok|Your request was successful.|
|400|Bad Request|Your request was invalid.|
|401|Unauthorized|Your request has no permission.|
|403|Forbidden|Your request is not authorized.|
|429|Request Overflow|See Rate Limit for more information.|


## Rate Limit
Our API supports using rate limiting to prevent request spam. Every identity can send 1 request per second by default (editable in the config).

This feature is disabled by default.

## Bot Management

### `POST` Set Task
Sets the task for a specific bot
`POST https://api.samklop.xyz/settask`

|Name|Type|Format|Description|
|----|----|------|-----------|
|Bot ID|int|*id*|Bot ID of the selected bot.|
|Task|String|*task*|The new task for the selected bot.|

### `POST` Set Name
Sets the name for a specific bot
`POST https://api.samklop.xyz/setname`

|Name|Type|Format|Description|
|----|----|------|-----------|
|Bot ID|int|*id*|Bot ID of the selected bot.|
|Name|String|*name*|The new name for the selected bot.|

### `POST` Set Data
Sets the data for a specific bot
`POST https://api.samklop.xyz/setname`

|Name|Type|Format|Description|
|----|----|------|-----------|
|Bot ID|int|*id*|Bot ID of the selected bot.|
|Data|String|*data*|The new data for the selected bot.|

### `GET` Get Bot List
Get a list of all bots and their data.
`GET https://api.samklop.xyz/bots`

### `GET` Get Bot Tasks
Get a list of all tasks currently being performed.
`GET https://api.samklop.xyz/tasks`

### `GET` Get Bot Task
Gets the task being performed by the selected bot.
`GET https://api.samklop.xyz/task`
|Name|Type|Format|Description|
|----|----|------|-----------|
|Bot ID|int|*id*|Bot ID of the selected bot.|

### `GET` Get Bot Name
Gets the name of the selected bot
`GET https://api.samklop.xyz/name`
|Name|Type|Format|Description|
|----|----|------|-----------|
|Bot ID|int|*id*|Bot ID of the selected bot.|

### `GET` Get Bot Data
Gets the data of the selected bot
`GET https://api.samklop.xyz/data`
|Name|Type|Format|Description|
|----|----|------|-----------|
|Bot ID|int|*id*|Bot ID of the selected bot.|
