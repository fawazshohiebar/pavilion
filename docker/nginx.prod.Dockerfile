FROM nginx:stable-alpine

ENV NGINX_USER=laravel
ENV NGINX_GROUP=laravel

# Add user
RUN adduser -g ${NGINX_GROUP} -s /bin/sh -D ${NGINX_USER}

# Create necessary directories
RUN mkdir -p /var/www/html/public

# Copy nginx configuration
COPY docker/nginx.default.conf /etc/nginx/conf.d/default.conf

# Update nginx user in main config
RUN sed -i "s/user nginx/user ${NGINX_USER}/g" /etc/nginx/nginx.conf

# Expose ports
EXPOSE 80 443

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://localhost/ || exit 1

CMD ["nginx", "-g", "daemon off;"]
