#BACK UP
dump-base:
	sudo docker exec mysql /mnt/mysql/backup/scripts.sh

supervisor-restart:
	sudo docker exec php-cli supervisorctl restart all

