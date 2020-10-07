-- Создали БД
CREATE DATABASE `1021437-task-force-1` DEFAULT CHARACTER SET `utf8` DEFAULT COLLATE `utf8_general_ci`;

-- Выбрали БД
USE `1021437-task-force-1`;

-- Создание таблицы городов
CREATE TABLE `tf_cities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `city` VARCHAR(255) NOT NULL COMMENT 'название города',
    `latitude_y` VARCHAR(24) NOT NULL COMMENT 'координаты по широте',
    `longitude_x` VARCHAR(24) NOT NULL COMMENT 'координаты по долготе'
);

-- Создание таблицы с категориями
CREATE TABLE `tf_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(128) NOT NULL COMMENT 'имя категории',
    `category_icon` VARCHAR(255) NOT NULL COMMENT 'иконка категории'
);

-- Создание таблицы с пользователями
CREATE TABLE `tf_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'фио пользователя',
    `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'email пользователя',
    `city_id` INT NOT NULL COMMENT 'id города проживания пользователя',
    `registration_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время регистрации',
    `password` VARCHAR(255) NOT NULL COMMENT 'зашифрованный пароль',
    FOREIGN KEY (`city_id`) REFERENCES `tf_cities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы с пользователями
CREATE TABLE `tf_profiles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL COMMENT 'id пользователя',
    `address` TEXT NULL COMMENT 'адресс проживания',
    `birthday_at` DATETIME NULL COMMENT 'дата рождения',
    `user_info` TEXT COMMENT 'контактная информация',
    `rating` FLOAT UNSIGNED NULL COMMENT 'рейтинг пользователя',
    `views_account` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'количество просмотров профиля пользователя',
    `avatar` VARCHAR(255) COMMENT 'аватар пользователя',
    `phone` CHAR(11) COMMENT 'телефон пользователя',
    `skype` VARCHAR(128) COMMENT 'skype пользователя',
    `telegram` VARCHAR(128) COMMENT 'telegram пользователя',
    `last_visit` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время последней активности',
    `counter_of_failed_tasks` INT DEFAULT 0 COMMENT 'количество проваленных заданий',
    FOREIGN KEY (`user_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы с настройками пользователя
CREATE TABLE `tf_user_preferences` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL COMMENT 'id пользователя',
    `notifications_new_message` BOOLEAN DEFAULT TRUE COMMENT 'уведомление о новом сообщении',
    `notifications_task_actions` BOOLEAN DEFAULT TRUE COMMENT 'уведомление о действии по заданию',
    `notifications_new_review` BOOLEAN DEFAULT TRUE COMMENT 'уведомление о новом отзыве',
    `public_contacts` BOOLEAN DEFAULT FALSE COMMENT 'скрыть показ контактов',
    `hidden_profile` BOOLEAN DEFAULT FALSE COMMENT 'скрыть профиль со страницы «Список исполнителей»',
    FOREIGN KEY (`user_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы с фотографиями работ пользователя
CREATE TABLE `tf_user_attachments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL COMMENT 'id пользователя',
    `image_link` VARCHAR(2048) NOT NULL COMMENT 'ссылка на фото работ',
    FOREIGN KEY (`user_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы со специализациями пользователя
CREATE TABLE `tf_user_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL COMMENT 'id пользователя',
    `categories_id` INT NOT NULL COMMENT 'категории выбранные исполнителем',
    FOREIGN KEY (`user_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`categories_id`) REFERENCES `tf_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы с задачами
CREATE TABLE `tf_tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL COMMENT 'название задачи',
    `description` TEXT NOT NULL COMMENT 'описание лота',
    `category_id` INT NOT NULL COMMENT 'id категории объявления',
    `status` ENUM('new', 'canceled', 'in_work', 'success', 'failed') DEFAULT 'new' COMMENT 'статус задачи',
    `budget` INT UNSIGNED COMMENT 'бюджет',
    `address` TEXT NULL COMMENT 'адресс выполнения задачи',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время создания задачи',
    `start_at` TIMESTAMP COMMENT 'дата и время начала выполнения задачи',
    `city_id` INT COMMENT 'id города',
    `latitude_y` VARCHAR(24) NOT NULL COMMENT 'координаты по широте к области выполнения задания',
    `longitude_x` VARCHAR(24) NOT NULL COMMENT 'координаты по долготе к области выполнения задания',
    `author_id` INT NOT NULL COMMENT 'id автора',
    `executor_id` INT COMMENT 'id исполнителя',
    `ends_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'крайний срок исполнения задания',
    FOREIGN KEY (`category_id`) REFERENCES `tf_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`city_id`) REFERENCES `tf_cities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`author_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`executor_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы для вложенных файлов в задаче
CREATE TABLE `tf_task_attachments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT NOT NULL COMMENT 'id задачи',
    `file_link` VARCHAR(2048) NOT NULL COMMENT 'ссылка на файл в задаче',
    FOREIGN KEY (`task_id`) REFERENCES `tf_tasks` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы откликов на задачу
CREATE TABLE `tf_responses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT NOT NULL COMMENT 'id задачи',
    `executor_id` INT NOT NULL COMMENT 'id исполнителя',
    `text_responses` TEXT NOT NULL COMMENT 'комментарий исполнителя к задаче',
    `budget` INT UNSIGNED COMMENT 'стоимость работ',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время создания отклика',
    FOREIGN KEY (`task_id`) REFERENCES `tf_tasks` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`executor_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы отзывов
CREATE TABLE `tf_feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `author_id` INT NOT NULL COMMENT 'id автора заявки',
    `executor_id` INT NOT NULL COMMENT 'id исполнителя',
    `task_id` INT NOT NULL COMMENT 'id задачи',
    `comment` TEXT NOT NULL COMMENT 'текстовый комментарий',
    `rating` TINYINT UNSIGNED COMMENT 'оценка',
    `status` ENUM('success', 'failed') NOT NULL COMMENT 'статус заявки',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время создания отзыва',
    FOREIGN KEY (`author_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`executor_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`task_id`) REFERENCES `tf_tasks` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы с избранным
CREATE TABLE `tf_favorites` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL COMMENT 'id пользователя',
    `favorite_id` INT NOT NULL COMMENT 'id пользователей в избранном',
    FOREIGN KEY (`user_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`favorite_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Создание таблицы с сообщениями
CREATE TABLE `tf_messages` (
    `id` INT AUTO_INCREMENT  PRIMARY KEY,
    `author_id` INT NOT NULL COMMENT 'id автора сообщения',
    `message` VARCHAR(255) NOT NULL COMMENT 'текст сообщения',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'дата и время создания сообщения',
    `task_id` INT NOT NULL COMMENT 'id задачи',
    `recipient_id` INT NOT NULL COMMENT 'id получателя сообщения',
    FOREIGN KEY (`author_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`task_id`) REFERENCES `tf_tasks` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`recipient_id`) REFERENCES `tf_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
);
