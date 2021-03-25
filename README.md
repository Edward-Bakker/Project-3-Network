## How to start the php server
`php -S localhost:8008 -t api`

## Authorization
The API in it's current state does not use API keys, but it might in the future.

This API is a RESTful API with industry standard HTTP requests and JSON responses.

## Requests
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
Our API is using rate limiting to prevent request spam. Every servent ident can request 50 requests per minute and 5 requests per second.

## Bot Management

### `POST` Set Task
Sets the task for a specific bot
`POST https://domain/api/settask`

|Name|Type|Format|Description|
|----|----|------|-----------|
|Bot ID|String|*id*|Bot ID of the selected bot.|
|Task|String|*task*|The new task for the selected bot.|

### `GET` Get Bot List
Get a list of all bots currently connected.
`GET https://domain/api/bots`

### `GET` Get Bot Tasks
Get a list of all tasks currently being performed.
`GET https://domain/api/tasks`

### `GET` Get Bot Task
Get a list of the task being performed by the selected bot.
`GET https://domain/api/task`
