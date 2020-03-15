ConsoleTwitter 
========================

Implement a console-based social networking application (similar to Twitter) satisfying the scenarios below.

<h2>Scenarios</h2>

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

<h2>Details</h2>

* The application must use the console for input and output.
* Users submit commands to the application. 
* There are four commands. “posting”, “reading”, etc. are not part of the commands.
* Commands always start with the user’s name.
* posting: user name -> message
* reading: user name
* following: user name follows another user
* wall: user name wall 

NOTE: Add instructions about how to run the application.

<h2>What we are looking for: </h2>

* Use the language you feel more comfortable.
* Don't use any framework, we want to see you code.
* Pay attention about how your code is organized.
* How you are reflecting the domain in the code.
* We love clean code.
* We don`t think 100% of code coverage is a must, but we love tests.
* We are looking forward to seeing your code and discuss with you your solution.

# Running the application 

To run image just build the docker image and run a new container:

 ```bash
# build the image
docker build . -t othercode/console-twitter:latest

# cleanup the intermediate images
docker rmi $(sudo docker images -f dangling=true -q)

# run the container 
docker run -it --rm othercode/console-twitter:latest
```