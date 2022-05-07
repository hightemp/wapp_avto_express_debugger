# FROM nginx:stable-alpine
FROM nginx:stable

USER root

RUN chmod -R a+w /var
RUN chmod a+w /run

RUN echo "" && echo "" && nginx -v && echo "" && echo ""

WORKDIR /var/www/