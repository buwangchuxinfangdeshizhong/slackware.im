create user slackware with password 'slackware' ; 

ALTER USER slackware WITH PASSWORD 'slackware';

create database slackware with encoding='utf8' ;

grant all privileges on database slackware to slackware ;

\connect slackware;

alter database slackware owner to slackware;

alter schema public owner to slackware;

