FROM mysql
COPY ./migrations/seed.sql /migrations/seed.sql

ENTRYPOINT ["bash", "-c", "mysql -u root -h tts-db --port 3306 dummy < /migrations/seed.sql"]