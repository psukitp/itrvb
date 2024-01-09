CREATE TABLE users (
    uuid UUID PRIMARY KEY,
    username TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL
);

CREATE TABLE posts (
    uuid UUID PRIMARY KEY,
    FOREIGN KEY(author_uuid) REFERENCES users(uuid),
    title TEXT NOT NULL,
    text TEXT NOT NULL
);

CREATE TABLE comments (
    uuid UUID PRIMARY KEY,
    post_uuid UUID,
    FOREIGN KEY(author_uuid) REFERENCES users(uuid),
    text TEXT NOT NULL,
    FOREIGN KEY(post_uuid) REFERENCES posts(uuid)
);