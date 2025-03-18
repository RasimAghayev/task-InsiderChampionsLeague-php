FROM postgres:16-alpine

LABEL maintainer="Rasim Aghayev <rasimaqayev@gmail.com>" \
      version="1.0" \
      description="Production-ready Postgres server"

ENV POSTGRES_DB=myapp \
    POSTGRES_USER=myapp \
    POSTGRES_PASSWORD=mypassword \
    PGDATA=/var/lib/postgresql/data/pgdata

RUN apk add --no-cache \
    pg_cron \
    postgis \
    timescaledb \
    && mkdir -p /docker-entrypoint-initdb.d \
    #&& apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY ./server/postgres /docker-entrypoint-initdb.d/
COPY ./server/postgres/postgresql.conf /etc/postgresql/postgresql.conf

USER root

RUN mkdir -p "$PGDATA" \
    && chown -R postgres:postgres "$PGDATA" \
    && chmod 700 "$PGDATA"

USER postgres

HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
    CMD pg_isready -U $POSTGRES_USER -d $POSTGRES_DB || exit 1

EXPOSE 5432

CMD ["postgres", "-c", "config_file=/etc/postgresql/postgresql.conf"]
