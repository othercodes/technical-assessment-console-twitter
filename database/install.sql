CREATE TABLE IF NOT EXISTS `users`
(
    `id`   VARCHAR(36) PRIMARY KEY,
    `name` VARCHAR(32) UNIQUE
);

CREATE TABLE IF NOT EXISTS `subscriptions`
(
    `id`            VARCHAR(36) PRIMARY KEY,
    `subscribed_id` VARCHAR(36),
    `subscriber_id` VARCHAR(36),
    UNIQUE (subscribed_id, subscriber_id),
    CONSTRAINT fk_users FOREIGN KEY (subscribed_id) REFERENCES users (id),
    CONSTRAINT fk_followers FOREIGN KEY (subscriber_id) REFERENCES users (id)
);

CREATE TABLE IF NOT EXISTS `messages`
(
    `id`       VARCHAR(36) PRIMARY KEY,
    `owner_id` VARCHAR(36),
    `text`     VARCHAR(128),
    `created`  DATE DEFAULT (datetime('now', 'localtime')),
    CONSTRAINT fk_users FOREIGN KEY (owner_id) REFERENCES users (id)
);

-- Initializing the user data.
INSERT INTO `users` (`id`, `name`)
VALUES ('044aae61-f41b-4770-8904-8d14b2c108bc', 'alice'),
       ('c5c06910-beb7-4d25-8a8b-bb6c10657fa8', 'bob'),
       ('80dbb0c7-b9ef-4281-a8a9-ddaa02f6a3de', 'charlie');