#!/bin/bash

# Upgrades PEAR.
pear upgrade PEAR

# Phing.
pear channel-discover pear.phing.info
pear install phing/phing

# Composer.
#cd /usr/local/bin
#wget http://getcomposer.org/composer.phar
#chmod a+x composer.phar

exit 0;