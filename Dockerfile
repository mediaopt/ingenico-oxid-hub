FROM silintl/ubuntu:16.04

ENV REFRESHED_AT 2016-04-22
ENV HTTPD_PREFIX /etc/apache2

# install OS packages
RUN apt-get update && apt-get install -y \
    apache2 \
    curl \
    git \
    libapache2-mod-php \
    netcat \
    php \
    php-cli \
    php-curl \
    php-dom \
    php-intl \
    php-json \
    php-ldap \
    php-mbstring \
    php-mcrypt \
    php-mysql \
    php-zip \
    s3cmd \
    rsyslog-gnutls \
    && phpenmod mcrypt \
    && apt-get clean

# Enable additional configs and mods
RUN ln -s $HTTPD_PREFIX/mods-available/expires.load $HTTPD_PREFIX/mods-enabled/expires.load \
    && ln -s $HTTPD_PREFIX/mods-available/headers.load $HTTPD_PREFIX/mods-enabled/headers.load \
	&& ln -s $HTTPD_PREFIX/mods-available/rewrite.load $HTTPD_PREFIX/mods-enabled/rewrite.load

EXPOSE 8080
EXPOSE 3306

# By default, simply start apache.
#CMD /usr/sbin/apache2ctl -D FOREGROUND

COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
