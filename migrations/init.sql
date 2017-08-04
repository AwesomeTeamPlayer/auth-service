CREATE TABLE login_password (login varchar(255) NOT NULL, password VARCHAR(255) NOT NULL);
CREATE UNIQUE INDEX login_password_unique_index ON login_password (login);

CREATE TABLE login_session (login varchar(255) NOT NULL, session_id VARCHAR(255) NOT NULL);
