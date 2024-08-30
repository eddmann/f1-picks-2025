# 🏁 F1 Picks

My family and I have been playing a game for the past couple of rounds of this Formula 1 (2024) season.
The game is to predict the top three drivers per race, where after each race picks are scored - 2 points for the correct driver and position, 1 point for the correct driver.
We have been sending these picks over Whatsapp and then tallying up the scores manually.

Before the Formula 1 season summer-break had ended I wanted to see how quickly I could build something which allowed us to share and score these picks online.
I time-boxed 4 hours the Friday night before qualifying of the [Dutch Grand Prix](https://www.formula1.com/en/racing/2024/netherlands) to see how far I could get with the build.
With this time constraint I decided to use [Laravel](https://laravel.com/) for rapid-application development and a whole lotta ChatGPT to help build out the application behaviour.
From the initial ChatGPT prompt that helped me get the basic structure of the application setup I used a [small script](https://github.com/mufeedvh/code2prompt) which allowed me to feed in the current source-code to the prompt to help iterate on the solution and add additional behaviour.
After a lot of back n' forth with ChatGPT and a bunch of manual development I was able to ship a working application just past the 4-hour window!

After the 4-hour window I had:

- Local development environment working with Docker.
- Classless styling using [new.css](https://newcss.net/).
- Working user authentication via Google social login.
- Ability to record picks for a given round race (type), based on a specified pick window.
- Ability for select users (based on role) to record the race (type) results, upon which an asynchronous job is dispatched to update the relevant pick scores.
- Serverless deployment to Lambda, using [Bref](https://bref.sh/), [Serverless Lift](https://www.serverless.com/plugins/serverless-lift) (for handling SQS and CloudFront concerns) and [Neon](https://neon.tech/). 
- Domain logic spread across many different layers 😬, but it works!

Since this 4-hour stint I have:

- Restructured the repository structure to seperate local Docker and application concerns.
- Added a custom domain, instead of access via the CloudFront distribution URL.
- Extracted the custom __stage__ (staging, production) parameters into seperate `serverless.params.yml` file.
