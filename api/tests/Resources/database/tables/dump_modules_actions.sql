CREATE TABLE `modules_actions` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `action` TEXT NOT NULL UNIQUE,
  `active` INTEGER NOT NULL DEFAULT '1'
);
