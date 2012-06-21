#!/bin/bash

# Upgrades PEAR.
pear upgrade PEAR

pear config-set preferred_state beta

# setup symfony components YAML Required by phpunit

pear channel-discover pear.symfony-project.com
pear install symfony/YAML

# PHPUnit.
#pear config-set auto_discover 1
pear channel-discover pear.phpunit.de

# PPW, PHPCPD, PHPLOC.
pear channel-discover components.ez.no

# Dbunit
pear install phpunit/DbUnit

#mock object

pear install phpunit/PHPUnit_MockObject

# PPW.
pear install phpunit/ppw

# PHPCPD.
pear install phpunit/phpcpd

# PHPLOC.
pear install phpunit/phploc

# install phpunit
pear install --alldeps phpunit/PHPUnit 

# PDepend.
pear channel-discover pear.pdepend.org
pear install pdepend/PHP_Depend-beta

# PHPMD (depends on PDepend)
pear channel-discover pear.phpmd.org
pear install --alldeps phpmd/PHP_PMD

# PHP CodeSniffer.
pear install pear/PHP_CodeSniffer

exit 0;