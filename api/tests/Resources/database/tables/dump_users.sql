CREATE TABLE `users` (
  `id` INTEGER PRIMARY KEY NOT NULL,
  `user_group_id` INTEGER  NOT NULL,
  `name` TEXT NOT NULL,
  `email` TEXT NOT NULL UNIQUE,
  `password` TEXT NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME
);