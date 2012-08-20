dir=$(
    cd -P -- "$(dirname -- "$0")" && pwd -P
)
dir=$dir"/../"
cd $dir
php ./build/build.php pear
pear package ./package.xml

#cleanup pirum
rm -f ./build/pear/get/Faker*
cd ./build/pear
pirum build .
pirum add . ../../*.tgz
cd $dir
rm *.tgz

