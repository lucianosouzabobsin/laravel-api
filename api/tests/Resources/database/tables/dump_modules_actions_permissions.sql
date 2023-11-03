CREATE TABLE `modules_actions_permissions` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `module_id` INTEGER  NOT NULL,
  `module_action_id` INTEGER  NOT NULL,
  `name` TEXT NOT NULL UNIQUE,
  `description` TEXT NOT NULL,
  `link` TEXT NOT NULL,
  `active` INTEGER NOT NULL DEFAULT '1'
);
