CREATE TABLE `personal_access_tokens` (
  `id` INTEGER PRIMARY KEY NOT NULL,
  `tokenable_type` TEXT NOT NULL,
  `tokenable_id` INTEGER NOT NULL,
  `name` TEXT NOT NULL,
  `token` TEXT NOT NULL UNIQUE,
  `abilities` TEXT,
  `last_used_at` DATETIME,
  `expires_at` DATETIME,
  `created_at` DATETIME,
  `updated_at` DATETIME
);
