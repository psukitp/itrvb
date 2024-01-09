CREATE TABLE users (
    uuid text PRIMARY KEY,
    username TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL
);
CREATE TABLE posts (
    uuid text PRIMARY KEY,
    author_uuid text,
    title TEXT NOT NULL,
    text TEXT NOT NULL,
    FOREIGN KEY(author_uuid) REFERENCES users(uuid)
);
CREATE TABLE comments (
    uuid UUtextID PRIMARY KEY,
    post_uuid text,
    author_uuid text,
    text TEXT NOT NULL,
    FOREIGN KEY(author_uuid) REFERENCES users(uuid),
    FOREIGN KEY(post_uuid) REFERENCES posts(uuid)
);
CREATE TABLE post_likes (
    uuid TEXT PRIMARY KEY,
    post_uuid TEXT NOT NULL,
    user_uuid TEXT NOT NULL,
    FOREIGN KEY (post_uuid) REFERENCES posts(uuid),
    FOREIGN KEY (user_uuid) REFERENCES users(uuid)
);
CREATE TABLE comment_likes (
    uuid TEXT PRIMARY KEY,
    comment_uuid TEXT NOT NULL,
    user_uuid TEXT NOT NULL,
    FOREIGN KEY (comment_uuid) REFERENCES comments(uuid),
    FOREIGN KEY (user_uuid) REFERENCES users(uuid)
);