/* Mock data to test our code*/

/*Data for persons*/

/*
CREATE TABLE persons (
       person_id int,
       first_name varchar(24),
       last_name  varchar(24),
       address    varchar(128),
       email      varchar(128),
       phone      char(10),
       PRIMARY KEY(person_id),
       UNIQUE (email)
) tablespace c391ware;
*/
INSERT INTO persons VALUES(1,'Harry', 'Potter', '123 Drive', 'hp@gmail.com', '7808880000');
INSERT INTO persons VALUES(2,'Ron', 'Weasley', '234 Way', 'rw@gmail.com', '7807770000');
INSERT INTO persons VALUES(3,'Hermione', 'Granger', '345 Street', 'hg@gmail.com', '7806660000');
INSERT INTO persons VALUES(4,'Albus', 'Dumbledore', '456 Avenue', 'ad@gmail.com', '7805550000');

/*Data for users*/

/*
CREATE TABLE users (
    user_name           varchar(32),
    password            varchar(32),
    role                char(1),
    person_id           int,
    date_registered     date,
    CHECK (role in ('a', 'd', 's')),
    PRIMARY KEY(user_name),
    FOREIGN KEY(person_id) REFERENCES persons
) tablespace c391ware;
*/
INSERT INTO users VALUES('hpotter','magic1','a', 1, TO_DATE('2015-10-10','YYYY-MM-DD'));
INSERT INTO users VALUES('rweasly','magic2','s', 2, TO_DATE('2015-10-10','YYYY-MM-DD'));
INSERT INTO users VALUES('hgranger','magic3','d', 3, TO_DATE('2015-10-10','YYYY-MM-DD'));
INSERT INTO users VALUES('adore','magic4','s', 4, TO_DATE('2015-10-10','YYYY-MM-DD'));

/*Data for sensors*/

/*
CREATE TABLE sensors(
	sensor_id  		int,
	location   		varchar(64),
	sensor_type  	char(1),
	description  	varchar(128),
	CHECK(sensor_type in ('a', 'i', 't', 'o')),
	PRIMARY KEY(sensor_id)) tablespace c391ware;
*/
INSERT INTO sensors VALUES(10,'Hogwarts','a', 'this is a description');
INSERT INTO sensors VALUES(11,'Canada','i', 'this is a description');
INSERT INTO sensors VALUES(12,'Austalia','t', 'this is a description');
INSERT INTO sensors VALUES(13,'Ocean','o', 'this is a description');

/*Data for subscriptions*/

/*
CREATE TABLE subscriptions(
    sensor_id    int,
    person_id    int,
    PRIMARY KEY(sensor_id, person_id),
    FOREIGN KEY(person_id) REFERENCES persons,
    FOREIGN KEY(sensor_id) REFERENCES sensors
) tablespace c391ware;
*/
INSERT INTO subscriptions VALUES(10,1);
INSERT INTO subscriptions VALUES(11,1);
INSERT INTO subscriptions VALUES(12,1);
INSERT INTO subscriptions VALUES(13,1);
INSERT INTO subscriptions VALUES(10,2);
INSERT INTO subscriptions VALUES(10,3);
INSERT INTO subscriptions VALUES(12,4);


/* To be implemented

CREATE TABLE audio_recordings(
    recording_id int,
    sensor_id int,
    date_created date,
    length int,
    description varchar(128),
    recorded_data blob,
    PRIMARY KEY(recording_id),
    FOREIGN KEY(sensor_id) REFERENCES sensors
) tablespace c391ware;

CREATE TABLE images(
    image_id int,
    sensor_id int,
    date_created date,
    description varchar(128),
    thumbnail blob,
    recoreded_data blob,
    PRIMARY KEY(image_id),
    FOREIGN KEY(sensor_id) REFERENCES sensors
) tablespace c391ware;

CREATE TABLE scalar_data(
    id int,
    sensor_id int,
    date_created date,
    value float,
    PRIMARY KEY(id),
    FOREIGN KEY(sensor_id) REFERENCES sensors
) tablespace c391ware;
*/

commit;

