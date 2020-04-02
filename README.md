# ConsoleTwitter 

Implement a console-based social networking application (similar to Twitter) satisfying the scenarios below.

## Architecture 

This application has been refactored to follow the hexagonal architecture. 

 1. **Infrastructure**: Database specific connectors all 3th part dependant goes here.
 2. **Application**: Entry point of the application, receives the input data and calls the domain logic.
 3. **Domain**: all the business logic goes here. 

## Life Cycle

 1. [Infrastructure] The application receives the I/O (InputArguments and OutputWriter).
 2. [Infrastructure] The application load the Kernel and pass the I/O to it.
 3. [Infrastructure] The kernel retrieves the input commands and parses it into a pair of command name and arguments list.
 4. [Infrastructure] The kernel load the command and pass the arguments to it.
 5. [Application] The command call the different Application Services.
 6. [Domain] The Application Services executes Domain logic.

## Directory Structure

The repo contains two main directories:

- **applications/**: Contains the CLI specific application.
- **src/**: Holds the business logic.

```bash
src/
├── Shared
│   ├── Application
│   │   ├── Container.php
│   │   ├── Contracts
│   │   │   ├── Container.php
│   │   │   ├── Input.php
│   │   │   ├── Kernel.php
│   │   │   └── Output.php
│   │   └── Provider.php
│   ├── Domain
│   │   ├── Collection.php
│   │   ├── functions.php
│   │   └── ValueObject
│   │       └── StringValueObject.php
│   └── Infrastructure
│       └── Persistence
│           └── DatabaseSQLite
│               ├── Connector.php
│               ├── Exceptions
│               └── Query.php
└── SocialNetwork
    ├── Messages
    │   ├── Domain
    │   │   ├── Contracts
    │   │   │   └── MessagesRepository.php
    │   │   ├── MessageCreated.php
    │   │   ├── MessageId.php
    │   │   ├── MessageOwnerId.php
    │   │   ├── Message.php
    │   │   ├── MessageText.php
    │   │   └── Services
    │   │       ├── MessageCreator.php
    │   │       └── MessageFinder.php
    │   └── Infrastructure
    │       └── Persistence
    │           └── DatabaseSQLiteMessagesRepository.php
    ├── Shared
    │   └── Application
    │       ├── Contracts
    │       │   └── Formatter.php
    │       ├── Formatter.php
    │       ├── Subscriber.php
    │       ├── TimelinePublisher.php
    │       ├── TimelineReader.php
    │       └── WallReader.php
    ├── Subscriptions
    │   ├── Application
    │   │   ├── SubscriptionCreator.php
    │   │   └── SubscriptionFinder.php
    │   ├── Domain
    │   │   ├── Contracts
    │   │   │   └── SubscriptionsRepository.php
    │   │   ├── Exceptions
    │   │   │   ├── InvalidSubscribeToException.php
    │   │   │   ├── SubscriberAlreadySubscribedException.php
    │   │   │   └── SubscriptionNotFoundException.php
    │   │   ├── SubscribedId.php
    │   │   ├── SubscriberId.php
    │   │   ├── SubscriptionId.php
    │   │   └── Subscription.php
    │   └── Infrastructure
    │       └── Persistence
    │           └── DatabaseSQLiteSubscriptionsRepository.php
    └── Users
        ├── Domain
        │   ├── Contracts
        │   │   └── UserRepository.php
        │   ├── Exceptions
        │   │   └── UserNotFoundException.php
        │   ├── Services
        │   │   └── UserFinder.php
        │   ├── UserId.php
        │   ├── UserName.php
        │   └── User.php
        └── Infrastructure
            └── Persistence
                └── DatabaseSQLiteUsersRepository.php
```

## Running the application 

To run image just build the docker image and run a new container:

 ```bash
# build the image
docker build . -t othercode/console-twitter:latest

# cleanup the intermediate images
docker rmi $(sudo docker images -f dangling=true -q)

# run the container 
docker run -it --rm othercode/console-twitter:latest
```

## Scenarios

Quit: Any user can exit the application.

```
> alice quit
``` 

Posting: Alice can publish messages to a personal timeline
 
```
> Alice -> I love the weather today
> Bob -> Damn! We lost!
> Bob -> Good game though.
```

Reading: Bob can view Alice’s timeline
 
```
> Alice
I love the weather today (5 minutes ago)
> Bob
Good game though. (1 minute ago)
Damn! We lost! (2 minutes ago)
```

Following: Charlie can subscribe to Alice’s and Bob’s timelines, and view an aggregated list of all subscriptions
 
```
> Charlie -> I'm in New York today! Anyone want to have a coffee?
> Charlie follows Alice
> Charlie wall
Charlie - I'm in New York today! Anyone want to have a coffee? (2 seconds ago)
Alice - I love the weather today (5 minutes ago)

> Charlie follows Bob
> Charlie wall
Charlie - I'm in New York today! Anyone wants to have a coffee? (15 seconds ago)
Bob - Good game though. (1 minute ago)
Bob - Damn! We lost! (2 minutes ago)
Alice - I love the weather today (5 minutes ago)
```
