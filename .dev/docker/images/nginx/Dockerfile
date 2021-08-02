FROM nginx:latest AS local-nginx

COPY . /var/www/html

RUN rm /etc/nginx/nginx.conf

COPY ./.dev/docker/images/nginx/resources/nginx.conf /etc/nginx/nginx.conf
COPY ./.dev/docker/images/nginx/resources/default.conf /etc/nginx/vhosts/default.conf
