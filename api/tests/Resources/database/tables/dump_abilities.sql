CREATE TABLE `abilities` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `module_id` INTEGER  NOT NULL,
  `module_action_id` INTEGER  NOT NULL,
  `ability` TEXT NOT NULL UNIQUE,
  `active` INTEGER NOT NULL DEFAULT '1'
);
