INSERT INTO `modules_actions_permissions`
(`module_id`,
`module_action_id`,
`name`,
`description`,
`link`,
`active`)
VALUES
(1,1, '*', 'SuperAdmin permissions', 'all', 1),
(2,2, 'configuracao:create', 'permissions Configuração Create', 'all', 1),
(2,3, 'configuracao:update', 'permissions Configuração update', 'all', 1),
(3,3, 'cadastro:update', 'permissions Cadastro update', 'all', 1);