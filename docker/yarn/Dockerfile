FROM debian:stretch-slim

# Setup the base OS
RUN apt-get update -qq \
 && apt-get install -y --no-install-recommends build-essential  \
    apt-transport-https curl ca-certificates gnupg2 apt-utils nodejs

RUN useradd -mr -u 1001 -g sudo yarn

# Install node from nodesource
# uncomment the next 2 lines for fix
 RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - \
  && apt-get install -y nodejs

# Install yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
 && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
 && apt-get update -qq \
 && apt-get install -y yarn

USER yarn

WORKDIR /usr/src/app