CREATE TABLE `users_groups` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `active` INTEGER NOT NULL DEFAULT '1'
);