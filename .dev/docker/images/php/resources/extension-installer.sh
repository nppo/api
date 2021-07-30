#!/bin/bash
#
# This script allows you to simply use docker-php-ext-install
# It simply make sure already installed extensions are not installed to prevent errors
#
EXTENSIONS=$@

for extension in $EXTENSIONS
do
    if php -m | grep -q $extension; then
        echo "Extension $extension is already installed"
    else
        echo "-- Installing Extension: $extension --"
        docker-php-ext-install $extension
    fi
done
