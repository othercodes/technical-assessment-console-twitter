CREATE TABLE IF NOT EXISTS `users`
(
    `id`       INTEGER PRIMARY KEY AUTOINCREMENT,
    `username` VARCHAR(32) UNIQUE,
    `created`  DATE DEFAULT (datetime('now', 'localtime'))
);
CREATE TABLE IF NOT EXISTS `followings`
(
    `id`          INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id`     INTEGER,
    `follower_id` INTEGER,
    `created`     DATE DEFAULT (datetime('now', 'localtime')),
    UNIQUE (user_id, follower_id),
    CONSTRAINT fk_users FOREIGN KEY (user_id) REFERENCES users (id),
    CONSTRAINT fk_followers FOREIGN KEY (follower_id) REFERENCES users (id)
);
CREATE TABLE IF NOT EXISTS `messages`
(
    `id`      INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER,
    `text`    VARCHAR(128),
    `created` DATE DEFAULT (datetime('now', 'localtime')),
    CONSTRAINT fk_users FOREIGN KEY (user_id) REFERENCES users (id)
);
INSERT INTO `users` (`username`)
VALUES ('alice'),
       ('bob'),
       ('charlie');