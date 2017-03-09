FROM silintl/ubuntu:14.04

ENV REFRESHED_AT 2016-04-22
ENV HTTPD_PREFIX /etc/apache2

# install OS packages
RUN apt-get update && apt-get install -y \
    apache2 \
    curl \
    git \
    libapache2-mod-php5 \
    netcat \
    php5 \
    php5-cli \
    php5-curl \
    php5-gd \
#    php5-dom \
    php5-intl \
    php5-json \
    php5-ldap \
#    php5-mbstring \
    php5-mcrypt \
    php5-mysql \
#    php5-zip \
    s3cmd \
    rsyslog-gnutls \
#    && phpenmod mcrypt \
    && apt-get clean

# Enable additional configs and mods
RUN ln -s $HTTPD_PREFIX/mods-available/expires.load $HTTPD_PREFIX/mods-enabled/expires.load \
    && ln -s $HTTPD_PREFIX/mods-available/headers.load $HTTPD_PREFIX/mods-enabled/headers.load \
	&& ln -s $HTTPD_PREFIX/mods-available/rewrite.load $HTTPD_PREFIX/mods-enabled/rewrite.load

EXPOSE 8087
EXPOSE 3306

# By default, simply start apache.
#CMD /usr/sbin/apache2ctl -D FOREGROUND

COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
