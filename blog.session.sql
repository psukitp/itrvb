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

-- INSERT INTO users (uuid, username, first_name, last_name)
-- VALUES (
--     'uuid:1231-123-123-123-',
--     'username:psukitp',
--     'first_name:danil',
--     'last_name:myshkin'
--   );