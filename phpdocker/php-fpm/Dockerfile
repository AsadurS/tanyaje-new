FROM phpdockerio/php73-fpm:latest
WORKDIR "/application"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.3-bcmath php7.3-bz2 php7.3-dba php7.3-enchant php7.3-gd php7.3-gmp php7.3-gearman php7.3-igbinary php7.3-imap php7.3-imagick php7.3-interbase php7.3-intl php7.3-ldap php7.3-memcached php7.3-mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*