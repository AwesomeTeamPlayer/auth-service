CREATE TABLE login_password (login varchar(255) NOT NULL, password VARCHAR(255) NOT NULL);
CREATE UNIQUE INDEX login_password_unique_index ON login_password (login);
