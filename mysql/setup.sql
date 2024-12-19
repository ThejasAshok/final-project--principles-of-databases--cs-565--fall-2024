-- Step 1: Create the passwords database
CREATE DATABASE IF NOT EXISTS passwords;

-- Step 2: Use the passwords database
USE passwords;

-- Step 3: Create the accounts table
CREATE TABLE IF NOT EXISTS accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) NOT NULL,
    url VARCHAR(255),
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    comment TEXT
);

-- Step 4: Insert 20 sample user records
INSERT INTO accounts (site_name, url, username, password, comment)
VALUES
('YouTube', 'https://www.youtube.com', 'user1', 'password1', 'Personal account'),
('Google', 'https://www.google.com', 'user2', 'password2', 'Work account'),
('Facebook', 'https://www.facebook.com', 'user3', 'password3', 'Social account'),
('Twitter', 'https://www.twitter.com', 'user4', 'password4', 'Social media'),
('Instagram', 'https://www.instagram.com', 'user5', 'password5', 'Photo sharing'),
('LinkedIn', 'https://www.linkedin.com', 'user6', 'password6', 'Professional networking'),
('Netflix', 'https://www.netflix.com', 'user7', 'password7', 'Entertainment'),
('Amazon', 'https://www.amazon.com', 'user8', 'password8', 'Shopping'),
('eBay', 'https://www.ebay.com', 'user9', 'password9', 'E-commerce'),
('Reddit', 'https://www.reddit.com', 'user10', 'password10', 'Community discussion'),
('Spotify', 'https://www.spotify.com', 'user11', 'password11', 'Music streaming'),
('GitHub', 'https://www.github.com', 'user12', 'password12', 'Code repository'),
('StackOverflow', 'https://www.stackoverflow.com', 'user13', 'password13', 'Programming Q&A'),
('Pinterest', 'https://www.pinterest.com', 'user14', 'password14', 'Ideas and inspiration'),
('Quora', 'https://www.quora.com', 'user15', 'password15', 'Question and answers'),
('Zoom', 'https://www.zoom.us', 'user16', 'password16', 'Video conferencing'),
('Slack', 'https://www.slack.com', 'user17', 'password17', 'Team communication'),
('Dropbox', 'https://www.dropbox.com', 'user18', 'password18', 'Cloud storage'),
('Trello', 'https://www.trello.com', 'user19', 'password19', 'Task management'),
('Asana', 'https://www.asana.com', 'user20', 'password20', 'Project management');