# Add needed arguments
ARG IMAGE_ADD

# Use debian:bullseye-slim image
FROM ${IMAGE_ADD}debian:12.1-slim

# Install applications
RUN apt-get update && \
	apt-get -y install mariadb-client && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* \
