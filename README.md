# nicehashHistory
 Service to track Nicehash Profitability

## Getting Started

This project uses Docker to make it easy to get up and running. Simply install Docker and pull this repo down with `git clone https://github.com/makeSmartio/niceHashHistory_Docker` or download and extract if you don't have git installed.

Then bring up with `./run.sh`
The database (mariaDB) will init with the the db/docker-entrypoint-initdb.d/* unless it already exists.

Recreate the database with `sudo rm -rf db/data/*`

I set up a cron job in nginx/crontab to call  get_activeWorkers.php and/or get_rigs.php file every 1 minute

There are two API's at Nicehash with different levels of detail. I initially started with the activeWorkers API and that is the data you will see on index.php but then I found the rigs2 API with more detail and started polling that too. I recommend you check both out and then pick the one you like more. 

There is some code that helps smooth some of the erroneous  data that Nicehash returns for some users.. I'd pull that out if your data is okay. 

Nicehash does limit the number of API calls you can make against your address, it seems. I have emailed them and they say the limit is quite high, but it does seem that some users hit the limit more than others. 


I included PhpMyAdmin if you want it. Bring it up with: 
`docker run --name myadmin -d --network niceHashhistory_Docker_your-site-net --link db:db -p 8080:80 phpmyadmin`


## Contributing

I am pretty new to git and having people contribute, but I am open to the idea!


## License

Copyright (c) 2021, makeSmart()
All rights reserved.

This source code is licensed under the BSD-style license found in the
LICENSE file in the root directory of this source tree. 

## Acknowledgments

* Thanks to everyone who encouraged me to get this done and push it out as open source. I hope people can either learn from it and/or make it better