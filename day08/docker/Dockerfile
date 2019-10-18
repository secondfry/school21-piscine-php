FROM php:7.4.0RC3-cli
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get update -y && apt-get upgrade -y && apt install -y git build-essential unzip
