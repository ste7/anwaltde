## be-developer-test-v2 solution


### Installation
```
git clone https://github.com/ste7/anwaltde.git
cd anwaltde
cp .env.example .env
composser install
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail up
http://localhost
```

### Run tests
```
sail test
```

### Post endpoints

Endpoint: ``/api/posts``\
Type: ``GET``\
Params (optional): ``limit, page, userId, title, body``\
Example: ``http://localhost/api/posts?limit=10&page=1&title=Test&body=a&userId=1``

Endpoint: ``/api/posts/{postId}``\
Type: ``GET``\
Params (required): ``postId``\
Example: ``http://localhost/api/posts/1``

Endpoint: ``/api/posts``\
Type: ``POST``\
Params (required): ``userId, title, body``\
Example: ``http://localhost/api/posts``

Endpoint: ``/api/posts/{postId}``\
Type: ``PUT``\
Params (required): ``userId, title, body``\
Example: ``http://localhost/api/posts/1``

### Todo endpoints

Endpoint: ``/api/todos``\
Type: ``GET``\
Params (optional): ``limit, page, userId, title, status, dueOnLte, dueOnGte``\
Example: ``http://localhost/api/todos?limit=10&page=1&title=Qui&status=completed&dueOnLte=2000-01-01&dueOnGte=1990-01-06``

Endpoint: ``/api/todos/{todoId}``\
Type: ``GET``\
Params (required): ``todoId``\
Example: ``http://localhost/api/todos/1``

Endpoint: ``/api/todos``\
Type: ``POST``\
Params (required): ``userId, title, status, dueOn``\
Example: ``http://localhost/api/todos``

Endpoint: ``/api/todos/{todoId}``\
Type: ``PUT``\
Params (required): ``userId, title, status, dueOn``\
Example: ``http://localhost/api/todos/1``
