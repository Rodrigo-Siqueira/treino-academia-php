FROM php:8.4-cli

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd --gid "${GROUP_ID}" app \
    && useradd \
        --uid "${USER_ID}" \
        --gid "${GROUP_ID}" \
        --create-home \
        --shell /bin/bash \
        app

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

USER app

CMD ["php", "-v"]
