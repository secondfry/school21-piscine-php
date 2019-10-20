FROM php:7.4.0RC3-fpm
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get update -y && apt-get upgrade -y && apt-get install -y git build-essential unzip
RUN pecl install mongodb &&\
	echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini &&\
	echo "extension=mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
