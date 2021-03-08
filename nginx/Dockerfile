FROM ubuntu:18.04
RUN apt update \
 && apt-get install -y software-properties-common \
 && add-apt-repository -y ppa:nginx/mainline \
 && add-apt-repository -y ppa:ondrej/php \
 && apt update \
 && apt -y install \
 nginx php7.4-fpm php7.4-mysql
# wget curl iputils-ping vim nano #nice to have


# Add crontab file in the cron directory
ADD crontab /etc/cron.d/hello-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/hello-cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

#Install Cron
RUN apt-get update
RUN apt-get -y install cron
RUN apt-get -y install nano


RUN  mkdir -p /run/php/
 # Forward request logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
 && ln -sf /dev/stderr /var/log/nginx/error.log \
 && ln -sf /dev/stderr /var/log/php7.4-fpm.log

EXPOSE 80
COPY entrypoint.sh entrypoint.sh
CMD ./entrypoint.sh

