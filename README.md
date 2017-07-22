# auth-service

## General description

This microservice provides features to:
* Keeping login and password pairs - the login is not empty string, the password is string (can be empty)
* Updating password
* Signing in using login-password pairs. In result microservice returns session ID hash
* Signing out using session ID hash

## Available endpoints

### Creating login-password pair
*PUT /pair*
```json
{
  "login": "john",
  "password": "pa$$w0rd"
}
```

On success it returns: 
```json
{
  "status": "success"
}
```

On failed it returns: 
```json
{
  "status": "failed",
  "login": {
    "codeId": <ERROR_CODE_ID>,
    "text": "..."
  },
  "password": {
    "codeId": <ERROR_CODE_ID>,
    "text": "..."
  }
}
```

### Updating password based on login
*POST /pair*
```json
{
  "login": "john",
  "password": "pa$$w0rd"
}
```

On success it returns: 
```json
{
  "status": "success"
}
```

On failed it returns: 
```json
{
  "status": "failed",
  "login": {
    "codeId": <ERROR_CODE_ID>,
    "text": "..."
  },
  "password": {
    "codeId": <ERROR_CODE_ID>,
    "text": "..."
  }
}
```


### Login
*POST /login*
```json
{
  "login": "john",
  "password": "pa$$w0rd"
}
```

On success it returns: 
```json
{
  "status": "success",
  "sessionId": "abcdef123456asfdasfd123"
}
```

On failed it returns: 
```json
{
  "status": "failed",
  "login": {
    "codeId": <ERROR_CODE_ID>,
    "text": "..."
  },
  "password": {
    "codeId": <ERROR_CODE_ID>,
    "text": "..."
  }
}
```


### Logout
*POST /login*
```json
{
  "sessionId": "abcdef123..."
}
```

On success it returns: 
```json
{
  "status": "success"
}
```

On failed it returns: 
```json
{
  "status": "failed"
}
```



### Checking login
*POST /has-login*
```json
{
  "login": "john"
}
```

On success it returns: 
```json
{
  "hasLogin": true/false
}
```

## List of error codes:

100 - login exists in the database
101 - login does not exist in the database
102 - password is incorrect
