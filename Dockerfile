FROM chajr/php56-nginx-extended:latest as builder
USER root
RUN mkdir /build_composer
COPY composer.* /build_composer
WORKDIR /build_composer
RUN composer install

FROM chajr/php56-nginx-extended:latest as rpias
COPY ./ /var/www/rpias
COPY --from=builder /build_composer/vendor /var/www/rpias/vendor
USER root
RUN mv /var/www/rpias/docker/default.conf /etc/nginx/conf.d/
RUN rm -fr /var/www/html
RUN rm -fr /var/www/LOCALHOST
RUN rm -fr /usr/local/bin/composer
RUN chown -R nginx:nginx /var/www/rpias
USER nginx
WORKDIR /var/www/rpias
