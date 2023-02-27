## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell)
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose restart worker` to restart Messenger worker
6. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Using the service

POST /api/notifications
------
```
{
    "userId": 1,
    "channels": ["email","sms"],
    "body": "This is a test message",
    "title": "Hey there!"
}
```

- userId 1-4 will resolve to a user
- userId 5 will immitate user service request error
- Higher IDs will trigger "user not found error"
- Failing to resolve user will trigger retries
- There are two channels implemented: email, sms
- Email providers available: awsses, mockemail (outputs to stdout)
- Sms providers available: twilio, mocksms (outputs to stdout), mockfailedsms (simulates service provider request error)

