create table `users` (
    `id` int(11) not null auto_increment,
    `username` varchar(100) not null,
    `email` varchar(150) not null,
    `password` varchar(100) not null,
    `first_name` varchar(80) not null,
    `last_name` varchar(150) not null,
    `ip` text null,
    `browser` text null,
    `home_page` text null,
    `created_at` timestamp default CURRENT_TIMESTAMP,
    primary key (`id`)
) ENGINE=innodb default charset=utf8;

create table `comments` (
    `id` int(11) not null auto_increment,
    `user_id` int(11) not null,
    `text` text not null,
    `created_at` timestamp default CURRENT_TIMESTAMP,
    primary key (`id`),
    foreign key (`user_id`) references `users`(`id`)
        on delete cascade
) ENGINE=innodb default charset=utf8;