#!/usr/bin/env bash

cd ~/Projects/

SSH_USER=root
SSH_PWD="<password>"
SSH_HOST="bookapper.medclever.com"
PATH_SERVER_DB="/var/www/volumes/bookapper/database.db"
PATH_SERVER_IMAGES="/var/www/volumes/bookapper/images"

sshpass -p $SSH_PWD scp root@$SSH_HOST:$PATH_SERVER_DB ./Android/PocketDoctor_android/app/src/main/assets/databases
sshpass -p $SSH_PWD scp -r root@$SSH_HOST:$PATH_SERVER_IMAGES ./Android/PocketDoctor_android/app/src/main/assets

sshpass -p $SSH_PWD scp root@$SSH_HOST:$PATH_SERVER_DB ./PocketDoctorUniversal/android/app/src/main/assets/www/database.db
sshpass -p $SSH_PWD scp -r root@$SSH_HOST:$PATH_SERVER_IMAGES ./PocketDoctorUniversal/android/app/src/main/assets

sshpass -p $SSH_PWD scp root@$SSH_HOST:$PATH_SERVER_DB ./XCode/pocketdoctor_ios/
sshpass -p $SSH_PWD scp -r root@$SSH_HOST:$PATH_SERVER_IMAGES ./XCode/pocketdoctor_ios/PocketDoctor
