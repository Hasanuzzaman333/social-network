CREATE TABLE profile(
	first_name VARCHAR(50) NOT NULL,
	last_name VARCHAR(50) NOT NULL,
	nick_name VARCHAR(50),
	email VARCHAR(255) NOT NULL UNIQUE,

	user_id INT NOT NULL AUTO_INCREMENT,
	passcode VARCHAR(1000) NOT NULL,
	creation_date DATETIME NOT NULL DEFAULT NOW(),

  phone_no CHAR (25),
	district VARCHAR(50),
	street_no VARCHAR (10),
	house_no VARCHAR (10),
	country VARCHAR(50),

	b_date DATE NOT NULL,
	gender VARCHAR(6) NOT NULL,
	occupation VARCHAR(255),
	pro_pic_id INTEGER ,
	about VARCHAR(5000),
	fav_quote VARCHAR(2000),

	CONSTRAINT CHECK(gender IN ("Male","Female","Other")),
	CONSTRAINT CHECK(email LIKE "%_@_%._%"),
	PRIMARY KEY (user_id)

);

CREATE TABLE friendship_requests(
  request_by INT,
  request_to INT,
  r_date_time DATETIME NOT NULL DEFAULT NOW(),

  FOREIGN KEY (request_by) REFERENCES profile(user_id),
  FOREIGN KEY (request_to) REFERENCES profile(user_id),

  PRIMARY KEY (request_by, request_to )
);

CREATE TABLE friendship(
  user1_id INT,
  user2_id INT,
  f_date_time DATETIME NOT NULL DEFAULT NOW(),

  FOREIGN KEY (user1_id) REFERENCES profile(user_id),
  FOREIGN KEY (user2_id) REFERENCES profile(user_id),

  PRIMARY KEY(user1_id, user2_id)
);

CREATE TABLE chat(
  sender_id INT,
  receiver_id INT,
  msg VARCHAR(750),

  sending_date_time DATETIME DEFAULT NOW(),

  FOREIGN KEY (sender_id) REFERENCES profile(user_id),
  FOREIGN KEY (receiver_id) REFERENCES profile(user_id),

  PRIMARY KEY(sender_id, receiver_id, msg, sending_date_time)
);

CREATE TABLE secret_questions(
  question_id TINYINT AUTO_INCREMENT,
  question VARCHAR (750),

  PRIMARY KEY(question_id)
);

CREATE TABLE secret_answers(
  question_id TINYINT,
  user_id INT,
  answer VARCHAR (1000),

  FOREIGN KEY (question_id) REFERENCES secret_questions(question_id) ,
  FOREIGN KEY (user_id) REFERENCES profile(user_id),

  PRIMARY KEY(question_id, user_id)
);

CREATE TABLE education(
	user_id INT,
	edu_status VARCHAR(100),
	year_of_passing YEAR ,
	institute VARCHAR (50),

	FOREIGN KEY (user_id) REFERENCES profile(user_id),
	PRIMARY KEY(user_id, edu_status, year_of_passing, institute)

);

CREATE TABLE community(
  comm_id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR (100),
	creation_date_time DATETIME NOT NULL DEFAULT NOW(),
	type VARCHAR(25),
	logo_pic_id INTEGER ,

	PRIMARY KEY (comm_id)
);

CREATE TABLE membership(
	user_id INT,
	comm_id INT,
	joining_date_time DATETIME NOT NULL DEFAULT NOW(),
	member_type TINYINT,

	CONSTRAINT CHECK(member_type IN (1,2,3,4,5)),/*1 is Admin, 2 is Members, 3 is non members, 4 is banned member, 5 is request*/

	FOREIGN KEY (user_id) REFERENCES profile(user_id),
	FOREIGN KEY (comm_id) REFERENCES community(comm_id),

	PRIMARY KEY (user_id,comm_id)
);

CREATE TABLE posts(
	post_id INT NOT NULL AUTO_INCREMENT,
	post VARCHAR (5000),
	is_community_post TINYINT,

	CONSTRAINT CHECK (is_community_post IN (-1,1)),
	PRIMARY KEY (post_id)
);

CREATE TABLE post_pictures(
  pic_id INT NOT NULL AUTO_INCREMENT,
  post_id INT,

  FOREIGN KEY (post_id) REFERENCES posts(post_id),

  PRIMARY KEY (pic_id, post_id)
);

CREATE TABLE posting(
	user_id INT,
	post_id INT,
	postdate_time DATETIME NOT NULL DEFAULT NOW(),

	FOREIGN KEY (user_id) REFERENCES profile(user_id),
	FOREIGN KEY (post_id) REFERENCES posts(post_id),

	PRIMARY KEY (user_id,post_id)
);

CREATE TABLE thumbs(
	user_id INT,
	post_id INT,
	thumb_type TINYINT,

	CONSTRAINT CHECK (thumb_type IN (-1, 1)),

	FOREIGN KEY(user_id) REFERENCES profile(user_id),
	FOREIGN KEY(post_id) REFERENCES posts(post_id),

  PRIMARY KEY (user_id,post_id)
);

CREATE TABLE comments(
	user_id INT,
	post_id INT,
	comment VARCHAR (1000),
	comment_date_time DATETIME NOT NULL DEFAULT NOW(),

	FOREIGN KEY (post_id) REFERENCES posts(post_id),
	FOREIGN KEY (user_id) REFERENCES profile(user_id),

  PRIMARY KEY(user_id,post_id,comment_date_time)
);

CREATE TABLE posts_in_community(
	post_id INT,
	comm_id INT,

	FOREIGN KEY (post_id) REFERENCES posts(post_id),
	FOREIGN KEY (comm_id) REFERENCES community(comm_id),

	PRIMARY KEY (post_id,comm_id)
);

CREATE TABLE advertisement(
	ad_id INT NOT NULL AUTO_INCREMENT,
	ad_msg1 VARCHAR (50),
	ad_msg2 VARCHAR (50),
	ad_msg3 VARCHAR (50),
	ad_msg4 VARCHAR (50),
  PRIMARY KEY (ad_id)
);