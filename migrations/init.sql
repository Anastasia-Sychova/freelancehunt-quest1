CREATE DATABASE quest1;

CREATE TABLE `skills` (
  `id`   int(11) NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `projects` (
  `id`             int(11) NOT NULL,
  `name`           varchar(256) NOT NULL,
  `link`           varchar(256) NOT NULL,
  `skills`         json NOT NULL,
  `cost`           int(11) NOT NULL,
  `currency`       varchar(256) NOT NULL,
  `employer_login` varchar(256) NOT NULL,
  `employer_name`  varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
