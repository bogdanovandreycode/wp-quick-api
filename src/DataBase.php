<?php

/**
 * @package Wp quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace quickapi;

class DataBase
{
    private static $wpdb;

    public static function init(): void
    {
        global $wpdb;
        self::$wpdb = $wpdb;
    }

    public static function getProjects(): array|object|null
    {
        return self::$wpdb->get_results(
            "SELECT * FROM `wp_qf3_projects`",
            ARRAY_A
        );
    }

    public static function getProject(int $id): array|object|null
    {
        return self::$wpdb->get_row(
            self::$wpdb->prepare("SELECT * FROM `wp_qf3_projects` WHERE `id` = %d", $id),
            ARRAY_A
        );
    }

    public static function getHistory(int $projectId): array|object|null
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare("SELECT `id`, `st_form`, `st_date` FROM `wp_qf3_ps` WHERE `st_formid` = %d", $projectId),
            ARRAY_A
        );
    }

    public static function getHistoryByDate(int $projectId, string $date): array|object|null
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT `id`, `st_form`, `st_date` FROM `wp_qf3_ps` WHERE `st_formid` = %d AND `st_date` > %s",
                $projectId,
                $date
            ),
            ARRAY_A
        );
    }

    public static function getHistoryByDateAndLast(int $projectId, string $date, int $lastId): array|object|null
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT `id`, `st_form`, `st_date` FROM `wp_qf3_ps` WHERE `st_formid` = %d AND `st_date` > %s AND `id` > %d",
                $projectId,
                $date,
                $lastId
            ),
            ARRAY_A
        );
    }

    public static function getForms(int $projectId): array|object|null
    {
        return self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT `id`, `fields` FROM `wp_qf3_forms` WHERE `projectid` = %d",
                $projectId
            ),
            ARRAY_A
        );
    }

    public static function updateForm(int $formId, array $fields): bool|int
    {
        return self::$wpdb->update(
            'wp_qf3_forms', // имя таблицы
            array('fields' => $fields), // массив данных для обновления
            array('id' => $formId), // массив условий для выбора строк для обновления
            array('%s'), // типы данных для обновляемых полей
            array('%d') // типы данных для условий
        );
    }
}
