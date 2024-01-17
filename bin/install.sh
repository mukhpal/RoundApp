#!/usr/bash
# USAGE
#   sh install.sh
#
# OPTIONS
#   [option]        [alias]    [default]
#   --composer      (-c)        0
#   --database      (-d)        0
#   --role          (-r)        0
#   --migrations    (-m)        1
#
# REQUIREMENTS
# - composer is installed and configured globally
# - git client is installed and configured globally
# - psql command is installed
# - user can create database and role
# - database must not exists

const() {
    C=$(grep -w ^$1 $2 | xargs)
    C=${C#*=}
    echo $C
}

init() {
    DB_DATABASE=roundapp
    DB_USERNAME=roundapp
    DB_PASSWORD=roundapp
}

init_composer() {
   if [ $1 = 1 ]; then
        composer update
   fi
}

init_db() {
    DB_NAME=$(const DB_NAME $1)
    DB_USER=$(const DB_USER $1)
    DB_PASS=$(const DB_PASS $1)
    if [ $2 = 1 ]; then
        psql -d postgres -c "\x" -c "CREATE DATABASE $DB_NAME;"
    fi
    if [ $3 = 1 ]; then
        psql -d postgres -c "\x" -c "CREATE USER $DB_USER WITH ENCRYPTED PASSWORD '$DB_PASS';"
        psql -d postgres -c "\x" -c "GRANT ALL ON DATABASE $DB_NAME TO $DB_USER;"
    fi
}

init_app() {
    if [ $1 = 1 ]; then
        ./yii app/install --interactive=0
    fi

    echo "Changing public app directories permission..."
    sudo chmod -vR 0777 ./web/assets/
    sudo chmod -vR 0777 ./runtime/
}

install() {
    init
    init_composer $2
    init_db $1 $3 $4
    init_app $5
}

COMPOSER=0
DATABASE=0
ROLE=0
MIGRATION=1

for var in "$@"
do
    KEY=$(echo "$var" | sed 's/\(--[a-z]*\)*=.*/\1/')
    VALUE=$(echo ${var#*=})
    case "$KEY" in
    "--composer")
        COMPOSER=$VALUE
        ;;
    "--database")
        DATABASE=$VALUE
        ;;
    "--role")
        ROLE=$VALUE
        ;;
    "--migration")
        MIGRATION=$VALUE
        ;;
    "-c")
        COMPOSER=1
        ;;
    "-d")
        DATABASE=1
        ;;
    "-r")
        ROLE=1
        ;;
    "-m")
        MIGRATION=1
        ;;
    *)
        echo "Param $KEY not supported!"
        ;;
    esac
done


BASEDIR=$(dirname "$0")
cd "$BASEDIR/../"
ENV_FILE=".env"
echo "(COMPOSER, DATABASE, ROLE, MIGRATION) => ($COMPOSER, $DATABASE, $ROLE, $MIGRATION)";
# echo "Are you sure to install application with using this configuration? (y/n)"
#read CHOICE
if [ -f "$ENV_FILE" ];
#&& [ "$CHOICE" = "y" ];
    then
        install $ENV_FILE $COMPOSER $DATABASE $ROLE $MIGRATION
    else
        "File $ENV_FILE doesn't exist!"
fi
