FROM nginx:alpine

LABEL maintainer="Rasim Aghayev <rasimaqayev@gmail.com>" \
      version="1.0" \
      description="Production-ready Nginx server"

RUN addgroup -g 101 -S nginx && \
    adduser -S -D -H -u 101 -h /var/cache/nginx -s /sbin/nologin -G nginx -g nginx nginx || true

RUN apk add --no-cache \
    curl \
    tzdata \
    ca-certificates

RUN mkdir -p /var/cache/nginx \
             /var/run/nginx \
             /var/log/nginx \
             /var/www/html/be \
             /var/www/html/fe \
             /etc/nginx/conf.d && \
    chown -R nginx:nginx /var/cache/nginx \
                         /var/run/nginx \
                         /var/log/nginx \
                         /var/www/html

COPY --chown=nginx:nginx ./server/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=nginx:nginx ./server/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY --chown=nginx:nginx ./ /var/www/html/be

#HEALTHCHECK --interval=30s --timeout=3s \
#    CMD curl -f http://localhost/up || exit 1

WORKDIR /var/www/html

EXPOSE 80 443

USER nginx

CMD ["nginx", "-g", "daemon off;"]
