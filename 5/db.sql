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
INSERT INTO comments (uuid, post_uuid, author_uuid, text)
VALUES (
        'uuid:UUtextID',
        'post_uuid:TEXT',
        'author_uuid:TEXT',
        'text:TEXT'
    );
INSERT INTO posts (uuid, author_uuid, title, text)
VALUES (
        'uuid:TEXT',
        'author_uuid:TEXT',
        'title:TEXT',
        'text:TEXT'
    );
INSERT INTO users (uuid, username, first_name, last_name)
VALUES (
        'uuid:TEXT',
        'username:TEXT',
        'first_name:TEXT',
        'last_name:TEXT'
    );