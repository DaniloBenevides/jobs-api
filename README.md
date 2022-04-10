# JOBS API

## Technical decisions

I've chosen to simplify this API by not creating a lot of abstractions since there are no complex business rules being handled but 
I've written some integration tests to ensure that it's possible to refactor in the future without problems;
You can check it with the following command after the installation step;

```
vendor/bin/sail artisan test
```

## Installation

1. Install dependencies:

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs

```

2. Copy .env.example to .env

```
cp .env.example .env
```

3. Start containers

```
vendor/bin/sail up -d
```

4. Generate application key

```
vendor/bin/sail artisan key:generate
```

5. Run migrations and seeders

```
vendor/bin/sail artisan migrate --seed
```

6. Create a regular user to interact with the api (***It's important to take note of the token generated***)

```
vendor/bin/sail artisan create:regular-user
```

7. Create a manager user to interact with the api (***It's important to take note of the token generated***)

```
vendor/bin/sail artisan create:manager-user
```

6. Start the workers

```
vendor/bin/sail artisan     queue:work
```

## Endpoints

# List jobs

- List the jobs of the logged in user

Example:

```
curl --request GET \
  --url http://localhost/api/v1/jobs \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer <token>'
```


# Create job
- Create a new job and notify managers if the logged in user is not a Manager

```
curl --request POST \
  --url http://localhost/api/v1/jobs \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer <token>' \
  --header 'Content-Type: application/json' \
  --data '{
	"title": "test",
	"description": "test description"
}'
```



